<?php


namespace DH\EPGP\Models;

use DH\EPGP\Utilities\DB;
use JsonSerializable;

/**
 * Class Model
 * @package DH\EPGP\Models
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
abstract class AbstractModel implements JsonSerializable
{
    /** @var DB */
    protected DB $db;

    /**
     * AbstractModel constructor.
     */
    public function __construct()
    {
        $this->db = DB::getInstance();
    }

    abstract public function load() : bool;

    abstract public function save() : bool;

    abstract public function jsonSerialize();
}
