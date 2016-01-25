<?php

namespace Model;

use Hasty\DB\Model;
use JsonSerializable;

class PgNamespace extends Model implements JsonSerializable
{

    static $__table_name__ = 'pg_namespace';

    /** @hiddenfield integer */
    public $oid;

    /** @field string */
    public $nspname;

    /** @field integer */
    public $nspowner;

    /** @field array */
    public $nspacl;

    public function jsonSerialize()
    {
        return [];
    }

}
