<?php declare(strict_types=1);


namespace DH\EPGP\Repositories;


use DH\EPGP\Utilities\DB;

/**
 * Class Repository
 * @package DH\EPGP\Repositories
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
class Repository
{
    protected DB $db;

    /**
     * Repository constructor.
     */
    public function __construct()
    {
        $this->db = DB::getInstance();
    }
}
