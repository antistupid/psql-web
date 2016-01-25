<?php

namespace Model;

use Hasty\DB\Model;
use JsonSerializable;

class PgDatabase extends Model implements JsonSerializable
{

    static $__table_name__ = 'pg_database';

    /** @field string */
    public $datname;
    /** @field integer */
    public $datdba;
    /** @field integer */
    public $encoding;
    /** @field string */
    public $datcollate;
    /** @field string */
    public $datctype;
    /** @field boolean */
    public $datistemplate;
    /** @field boolean */
    public $datallowconn;
    /** @field integer */
    public $datconnlimit;
    /** @field integer */
    public $datlastsysoid;
    /** @field integer */
    public $datfrozenxid;
    /** @field integer */
    public $datminmxid;
    /** @field integer */
    public $dattablespace;
    /** @field string */
    public $datacl;

    private function _getValues()
    {
        return [
            'datname' => $this->datname,
            'datdba' => $this->datdba,
            'encoding' => $this->encoding,
            'datcollate' => $this->datcollate,
            'datctype' => $this->datctype,
            'datistemplate' => $this->datistemplate,
            'datallowconn' => $this->datallowconn,
            'datconnlimit' => $this->datconnlimit,
            'datlastsysoid' => $this->datlastsysoid,
            'datfrozenxid' => $this->datfrozenxid,
            'datminmxid' => $this->datminmxid,
            'dattablespace' => $this->dattablespace,
            'datacl' => $this->datacl,
        ];
    }

    public function jsonSerialize()
    {
        return [
            'datname' => $this->datname,
            'datdba' => $this->datdba,
            'encoding' => $this->encoding,
            'datcollate' => $this->datcollate,
            'datctype' => $this->datctype,
            'datistemplate' => $this->datistemplate,
            'datallowconn' => $this->datallowconn,
            'datconnlimit' => $this->datconnlimit,
            'datlastsysoid' => $this->datlastsysoid,
            'datfrozenxid' => $this->datfrozenxid,
            'datminmxid' => $this->datminmxid,
            'dattablespace' => $this->dattablespace,
            'datacl' => $this->datacl,
        ];
    }

    public function getValueWithoutKeys()
    {
        return array_values($this->_getValues());
    }

    public static function getColumns()
    {
        return [
            'Name',
            'Owner',
            'Encoding',
            'Collate',
            'Ctype',
            'Is Template',
            'Allow Conn',
            'Conn Limit',
            'Last Sys Oid',
            'Frozen Xid',
            'Min Mxid',
            'Table Space',
            'Acl',
        ];
    }

}
