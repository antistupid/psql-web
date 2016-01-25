<?php

namespace Model;

use Hasty\DB\Model;
use JsonSerializable;

class PgAttribute extends Model implements JsonSerializable
{

    static $__table_name__ = 'pg_attribute';

    /**
     * @field integer
     * @var \DB\Field
     */
    public $attrelid;

    /**
     * @field string
     * @var \DB\Field
     */
    public $attname;

    /**
     * @field integer
     * @var \DB\Field
     */
    public $atttypid;

    /**
     * @field integer
     * @var \DB\Field
     */
    public $attlen;

    /**
     * @field integer
     * @var \DB\Field
     */
    public $attnum;

    /**
     * @field integer
     * @var \DB\Field
     */
    public $attndims;

    /** @field integer */
    public $attcacheoff;

    /** @field integer */
    public $atttypmod;

    /** @field integer */
    public $attbyval;

    /** @field string */
    public $attstorage;

    /** @field string */
    public $attalign;

    /**
     * @field boolean
     * @var \DB\Field
     */
    public $attnotnull;

    /** @field boolean */
    public $atthasdef;

    /** @field boolean */
    public $attisdropped;

    /** @field boolean */
    public $attislocal;

    /** @field integer */
    public $attinhcount;

    /** @field integer */
    public $attcollation;

    /** @field array */
    public $attacl;

    /** @field array */
    public $attoptions;

    /** @field array */
    public $attfdwoptions;

    function jsonSerialize()
    {
    }

    public function __get($name)
    {
        dump('1111111');
        dump($name);
    }
}
