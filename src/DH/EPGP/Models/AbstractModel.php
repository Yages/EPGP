<?php


namespace DH\EPGP\Models;

use JsonSerializable;

/**
 * Class Model
 * @package DH\EPGP\Models
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
abstract class AbstractModel implements JsonSerializable
{
    abstract public function load() : bool;

    abstract public function save() : bool;

    abstract public function jsonSerialize();
}
