<?php declare(strict_types=1);


namespace DH\EPGP\Traits;


use PDO;
use PDOException;

/**
 * Class DB
 * @package DH\EPGP\Traits
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
trait DBAwareTrait
{
    /** @var PDO PDO Instance */
    private ?PDO $pdo;

    /** @var array Configuration data array */
    private ?array $config;

    /**
     * DB constructor.
     */
    public function connect()
    {
        if ($this->loadConfig()) {
            try {
                $this->pdo = new PDO($this->config['dsn'], $this->config['user'], $this->config['pass']);
            } catch(PDOException $e) {
                error_log('Database connection error: '.$e->getMessage());
                die('Unable to connect to database.');

            }
        } else {
            error_log('Unable to load site configuration, check ini file exists.');
            die('Unable to load site configuration.');
        }
    }

    /**
     * Removes the reference to the PDO object, freeing the database handle.
     */
    public function disconnect()
    {
        $this->pdo = null;
    }

    /**
     * Returns the PDO instance or null if not initialised for some reason.
     * @return PDO|null
     */
    public function pdo() : ?PDO
    {
        if (empty($this->pdo)) return null;

        return $this->pdo;
    }

    /**
     * Attempts to load configuration for the database.
     * @return bool
     */
    private function loadConfig() : bool
    {
        $path = dirname(__FILE__, 5) . '/db-epgp.ini';
        $this->config = parse_ini_file($path);

        return (!empty($this->config));
    }
}
