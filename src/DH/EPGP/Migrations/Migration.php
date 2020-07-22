<?php


namespace DH\EPGP\Migrations;



use DateTimeImmutable;
use DH\EPGP\Traits\DBAwareTrait;
use Exception;

/**
 * Class Migration
 * @package DH\EPGP\Migrations
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
abstract class Migration
{
    use DBAwareTrait;

    /** @var DateTimeImmutable  */
    private DateTimeImmutable $date;

    /**
     * Migration constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->date = new DateTimeImmutable();
        $this->connect();
    }

    public function __destruct()
    {
        $this->disconnect();
    }

    public function begin() : void
    {
        $this->pdo()->beginTransaction();
    }

    public function finalise() : void
    {
        $this->pdo()->commit();
    }

    public function rollback() : void
    {
        $this->pdo()->rollBack();
    }

    abstract public function migrate();
}
