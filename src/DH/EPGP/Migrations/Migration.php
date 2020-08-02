<?php declare(strict_types=1);


namespace DH\EPGP\Migrations;



use DateTimeImmutable;
use DH\EPGP\Utilities\DB;
use Exception;

/**
 * Class Migration
 * @package DH\EPGP\Migrations
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
abstract class Migration
{
    /** @var DateTimeImmutable  */
    private DateTimeImmutable $date;

    protected DB $db;

    /**
     * Migration constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->date = new DateTimeImmutable();
        $this->db = DB::getInstance();
    }


    public function begin() : void
    {
        $this->db->pdo()->beginTransaction();
    }

    public function finalise() : void
    {
        $this->db->pdo()->commit();
    }

    public function rollback() : void
    {
        $this->db->pdo()->rollBack();
    }

    abstract public function migrate();
}
