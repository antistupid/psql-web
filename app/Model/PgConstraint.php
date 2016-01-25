<?php

namespace Model;

use Hasty\DB\Model;
use JsonSerializable;

class PgConstraint extends Model implements JsonSerializable
{

    static $__table_name__ = 'pg_constraint';

    /** @field string */
    public $conname;

    /** @field integer */
    public $connamespace;

    /** @field string */
    public $contype;

    /** @field boolean */
    public $condeferrable;

    /** @field boolean */
    public $condeferred;

    /** @field boolean */
    public $convalidated;

    /** @field integer */
    public $conrelid;

    /** @field integer */
    public $contypeid;

    /** @field integer */
    public $conindid;

    /** @field integer */
    public $confrelid;

    function jsonSerialize()
    {
    }
}
