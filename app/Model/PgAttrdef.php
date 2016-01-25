<?php

namespace Model;

use Hasty\DB\Model;
use JsonSerializable;

class PgAttrdef extends Model implements JsonSerializable
{

    static $__table_name__ = 'pg_attrdef';

    /** @field integer */
    public $adrelid;

    /** @field integer */
    public $adnum;

    /** @field string */
    public $adbin;

    /** @field string */
    public $adsrc;

    function jsonSerialize()
    {
    }
}
