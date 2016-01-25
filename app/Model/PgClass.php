<?php

namespace Model;

use Hasty\DB\Model;
use JsonSerializable;

class PgClass Extends Model implements JsonSerializable
{

    static $__table_name__ = 'pg_class';

    /** @var \DB\Field $nspname  */
    /** @hiddenfield integer */
    public $oid;

    /** @var \DB\Field $nspname  */
    /** @field string */
    public $relname;

    /** @var \DB\Field $nspname  */
    /** @field integer */
    public $relnamespace;

    /** @var \DB\Field $nspname  */
    /** @field integer */
    public $reltype;

    /** @var \DB\Field $nspname  */
    /** @field integer */
    public $reloftype;

    /** @var \DB\Field $nspname  */
    /** @field integer */
    public $relowner;

    /** @var \DB\Field $nspname  */
    /** @field integer */
    public $relam;

    /** @var \DB\Field $nspname  */
    /** @field integer */
    public $relfilenode;

    /** @var \DB\Field $nspname  */
    /** @field integer */
    public $reltablespace;

    /** @var \DB\Field $nspname  */
    /** @field integer */
    public $relpages;

    /** @var \DB\Field $nspname  */
    /** @field integer */
    public $reltuples;

    /** @var \DB\Field $nspname  */
    /** @field integer */
    public $relallvisible;

    /** @var \DB\Field $nspname  */
    /** @field integer */
    public $reltoastrelid;

    /** @var \DB\Field $nspname  */
    /** @field integer */
    public $reltoastidxid;

    /** @var \DB\Field $nspname  */
    /** @field boolean */
    public $relhasindex;

    /** @var \DB\Field $nspname  */
    /** @field boolean */
    public $relisshared;

    /** @var \DB\Field $nspname  */
    /** @field string */
    public $relpersistence;

    /** @var \DB\Field $nspname  */
    /** @field string */
    public $relkind;

    /** @var \DB\Field $nspname  */
    /** @field integer */
    public $relnatts;

    /** @var \DB\Field $nspname  */
    /** @field integer */
    public $relchecks;

    /** @var \DB\Field $nspname  */
    /** @field boolean */
    public $relhasoids;

    /** @var \DB\Field $nspname  */
    /** @field boolean */
    public $relhaspkey;

    /** @var \DB\Field $nspname  */
    /** @field boolean */
    public $relhasrules;

    /** @var \DB\Field $nspname  */
    /** @field boolean */
    public $relhastriggers;

    /** @var \DB\Field $nspname  */
    /** @field boolean */
    public $relhassubclass;

    /** @var \DB\Field $nspname  */
    /** @field boolean */
    public $relispopulated;

    /** @var \DB\Field $nspname  */
    /** @field integer */
    public $relfrozenxid;

    /** @var \DB\Field $nspname  */
    /** @field integer */
    public $relminmxid;

    /** @var \DB\Field $nspname  */
    /** @field array */
    public $relacl;

    /** @var \DB\Field $nspname  */
    /** @field array */
    public $reloptions;

    function jsonSerialize()
    {
    }
}