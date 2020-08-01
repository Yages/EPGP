<?php declare(strict_types=1);


namespace DH\EPGP\Utilities;


use PDO;
use PDOException;

/**
 * Class DB
 * @package DH\EPGP\Utilities
 * @author Lucas Bradd <lucas@bradd.com.au>
 */
class DB
{
    /** @var DB|null */
    private static ?DB $instance = null;

    /** @var PDO PDO Instance */
    private PDO $pdo;

    /** @var array Configuration data array */
    private array $config;

    /**
     * DB constructor.
     */
    public function __construct()
    {
        $this->connect();
    }

    /**
     * DB constructor.
     */
    private function connect()
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
     * Attempts to load configuration for the database.
     * @return bool
     */
    private function loadConfig() : bool
    {
        $path = dirname(__FILE__, 5) . '/db-epgp.ini';
        $this->config = parse_ini_file($path);

        return (!empty($this->config));
    }

    /**
     * @return DB
     */
    public static function getInstance() : DB
    {
        if (self::$instance === null) {
            self::$instance = new DB();
        }

        return self::$instance;
    }

    /**
     * @return PDO
     */
    public function pdo() : PDO
    {
        return $this->pdo;
    }
}
