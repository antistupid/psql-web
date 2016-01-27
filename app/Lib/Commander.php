<?php

namespace Lib;

use Hasty\DB\DBException;
use Hasty\DB\Query;
use Hasty\Session;
use Model\PgAttrdef;
use Model\PgAttribute;
use Model\PgClass;
use Model\PgConstraint;
use Model\PgDatabase;
use Model\PgNamespace;

class Commander
{
    private $command;

    public function __construct($command)
    {
        $this->command = $command;
    }

    public function generateTable($keys, $list) {
        $t = [];
        $t[] = '<table class="table table-striped"><thead><tr>';
        for ($i = 0; $i < count($keys); $i++) {
            $t[] = '<th>' . $keys[$i] . '</th>';
        }
        $t[] = '</tr></thead><tbody>';
        for ($j = 0; $j < count($list); $j++) {
            $t[] = '<tr>';
            for ($k = 0; $k < count($list[$j]); $k++) {
                $t[] = '<td>' . $list[$j][$k] . '</td>';
            }
            $t[] = '</tr>';
        }
        $t[] = '</tr></tbody></table>';
        return \implode('', $t);
    }

    public function execute()
    {
        $command = $this->_parseCommand(trim($_POST['command']));
        $dbname = Session::get('dbname');

        if (is_a($command, 'Lib\PostgresqlCommand') &&
            $command->command !== 'c' &&
            !$dbname
        ) {
            return new CommandResult(false, [
                'msg' => 'There is no selected database.'
            ]);
        }

        if ($dbname)
            Query::config([
                'schema' => 'pgsql',
                'host' => Session::get('host'),
                'user' => Session::get('username'),
                'pass' => Session::get('password'),
                'dbname' => $dbname,
            ]);

        /** normal query */
        if (!is_a($command, 'Lib\PostgresqlCommand')) {
            try {
                $result = Query::query($command->query);
                if (count($result) === 0)
                    return new CommandResult(true, [
                        'table' => null
                    ]);
                $keys = array_keys($result[0]);
                $list = [];
                foreach ($result as $v) {
                    foreach ($v as $k2 => $v2) {
                        if (is_resource($v2))
                            $v[$k2] = '<resource type>';
                        $v[$k2] = htmlspecialchars($v[$k2]);
                    }
                    $list[] = array_values($v);
                }
                return new CommandResult(true, [
                    'table' => [
                        'keys' => $keys,
                        'values' => $list,
                    ]
                ]);
            } catch (DBException $e) {
                header('Content-Type: application/json');
                header($_SERVER["SERVER_PROTOCOL"] . " 406 Not Acceptable");
                return new CommandResult(false, [
                    'msg' => $e->getMessage()
                ]);
            }
        }

        /** backslash command */
        switch ($command->command) {
            case 'c':
                Query::config([
                    'schema' => 'pgsql',
                    'host' => Session::get('host'),
                    'user' => Session::get('username'),
                    'pass' => Session::get('password'),
                    'dbname' => $command->arguments,
                ]);
                $pd = new PgDatabase();
                try {
                    $databases = Query::get([$pd])->select()->all();
                } catch (\Exception $e) {
                    return new CommandResult(false, [
                        'msg' => 'FATAL:  database "' . $command->arguments . "\" does not exist\nPrevious connection kept",
                    ], 406);
                }
                Session::set('dbname', $command->arguments);
                $dblist = [];
                foreach ($databases as $v)
                    $dblist[] = array_values($v);
                return new CommandResult(true, [
                    'msg' => 'You are now connected to database "' . $command->arguments . '" as user "postgres".',
                    'databases' => $dblist,
                ]);
            case 'l':
                $pd = new PgDatabase();
                $list = [];
                $databases = Query::get([$pd])->select()->all();
                foreach ($databases as $v)
                    $list[] = array_values($v);
                return new CommandResult(true, [
                    'table' => [
                        'keys' => PgDatabase::getColumns(),
                        'values' => $list,
                        'total' => count($list)
                    ]
                ]);
            case 'd':
                if ($command->arguments) {
                    $pc = new PgClass();
                    $pa = new PgAttribute();
                    $pad = new PgAttrdef();
                    $pct = new PgConstraint();

                    $relations = Query::get([
                        $pa,
                        [$pc, [Query::eq($pc->oid, $pa->attrelid)]],
                        [new PgAttrdef(), [
                            Query::eq($pad->adrelid, $pc->oid),
                            Query::eq($pad->adnum, $pa->attnum)
                        ], 'LEFT JOIN'],
                        // [new PgConstraint(), [
                        //     Factory::eq(PgConstraint::$conrelid, PgClass::$oid),
                        //     Factory::eqany(PgAttribute::$attnum, PgConstraint::$conkey)
                        // ], 'LEFT JOIN']
                    ])->select([
                        $pa->attnum->alias('number'),
                        $pa->attname->alias('name'),
                        $pa->attnotnull->alias('notnull'),
                        $pa->atttypid->func('format_type', [$pa->atttypmod])->alias('Type'),
                    ])->where([
                        Query::gt($pa->attnum, 0),
                        Query::eq($pc->relname, $command->arguments),
                        Query::eq($pc->relkind, 'r')
                    ])->table();

                    if (count($relations) === 0)
                        return new CommandResult(false, [
                            'msg' => 'Did not find any relation named "' . $command->arguments . '".']);

                    return new CommandResult(true, [
                        'table' => $relations
                    ]);
                }

                if ($command->arguments) {
                    $keys = ['number', 'name', 'notnull', 'type', 'primarykey',
                        'uniquekey', 'default', 'foreignkey', 'foreignkey_fieldnum',
                        'foreignkey_connnum'];
                    $relations = Query::query("SELECT
    f.attnum AS number,
    f.attname AS name,
    f.attnotnull AS notnull,
    pg_catalog.format_type(f.atttypid,f.atttypmod) AS type,
    CASE
        WHEN p.contype = 'p' THEN 't'
        ELSE 'f'
    END AS primarykey,
    CASE
        WHEN p.contype = 'u' THEN 't'
        ELSE 'f'
    END AS uniquekey,
    CASE
        WHEN f.atthasdef = 't' THEN d.adsrc
        ELSE ''
    END AS default_value,
    CASE
        WHEN p.contype = 'f' THEN g.relname
    END AS foreignkey,
    CASE
        WHEN p.contype = 'f' THEN p.confkey
    END AS foreignkey_fieldnum,
    CASE
        WHEN p.contype = 'f' THEN p.conkey
    END AS foreignkey_connnum
FROM pg_attribute f
    JOIN pg_class c ON c.oid = f.attrelid
    JOIN pg_type t ON t.oid = f.atttypid
    LEFT JOIN pg_attrdef d ON d.adrelid = c.oid AND d.adnum = f.attnum
    LEFT JOIN pg_namespace n ON n.oid = c.relnamespace
    LEFT JOIN pg_constraint p ON p.conrelid = c.oid AND f.attnum = ANY (p.conkey)
    LEFT JOIN pg_class AS g ON p.confrelid = g.oid
WHERE c.relkind = 'r'::char
    AND n.nspname = 'public'
    AND c.relname = $1
    AND f.attnum > 0 ORDER BY number
;", [$command->arguments]);

                } else {
                    $pc = new PgClass();
                    $pn = new PgNamespace();

                    $relations = Query::get([
                        $pc,
                        [$pn, [Query::eq($pn->oid, $pc->relnamespace)]],
                    ])->select([
                        $pn->nspname->alias('Schema'),
                        $pc->relname->alias('Name'),
                        $pc->relkind->condition([
                            ['r', 'table',],
                            ['v', 'view',],
                            ['m', 'materialized view',],
                            ['i', 'index',],
                            ['S', 'sequence',],
                            ['s', 'special',],
                            ['f', 'foreign table',],
                        ])->alias('Type'),
                        $pc->relowner->func('pg_get_userbyid')->alias('Owner'),
                    ])->where([
                        Query::in($pc->relkind, ['r', 'v', 'm', 'S', 'f', '']),
                        Query::neq($pn->nspname, 'pg_catalog'),
                        Query::neq($pn->nspname, 'information_schema'),
                        Query::regexpneq($pn->nspname, '^pg_toast'),
                    ])->order_by([
                        1, 2
                    ])->table();

                    return new CommandResult(true, [
                        'table' => $relations
                    ]);
                }
            default:
                return new CommandResult(true, [
                    'msg' => 'no such command'
                ]);
        }
    }

    private function _parseCommand($command)
    {
        if (substr($command, 0, 1) === '\\')
            return new PostgresqlCommand($command);
        else
            return new QueryCommand($command);
    }
}

class CommandResult
{
    private $isSuccess = false;
    private $msg;

    public function __construct($isSuccess, $msg)
    {
        $this->isSuccess = $isSuccess;
        $this->msg = $msg;
    }

    /**
     * @return boolean
     */
    public function getIsSuccess()
    {
        return $this->isSuccess;
    }

    public function getValue()
    {
        return $this->msg;
    }
}

class PostgresqlCommand
{
    public $command;
    public $arguments;

    public function __construct($command)
    {
        $match = null;
        preg_match_all("@\\\\([^\s]+)(\\s+)?(.*)@", $command, $match);
        $this->command = $match[1][0];
        $this->arguments = $match[3][0];
    }
}

class QueryCommand
{
    public $query;

    public function __construct($command)
    {
        $this->query = $command;
    }
}
