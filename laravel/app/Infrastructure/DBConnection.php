<?php
namespace App\Infrastructure;

use App\Infrastructure\Exceptions\DBConnectionException;

/**
 * Class DBConnection
 *
 * Provides a wrapper around PDO for connection handling
 * @package App\Infrastructure
 */
class DBConnection
{

    private static $driver_sqlite = "sqlite";
    private static $driver_sqlite2 = "sqlite2";
    private static $driver_sqlsrv = "sqlsrv";
    private static $driver_firebird = "firebird";
    private static $driver_oci = "oci";

    private static $config_dsn = "dsn";
    private static $config_driver = "driver";
    private static $config_host = "host";
    private static $config_port = "port";
    private static $config_dbname = "dbname";
    private static $config_username = "username";
    private static $config_password = "password";
    private static $config_charset = "charset";
    private static $config_attributes = "options";

    /** @var  DBConnection */
    private static $instance;
    /** @var  PDOTransactional */
    private $pdoConnection;
    private $config = array();

    private function __construct($config)
    {
        $this->validateConfig($config);
        $this->initializeConfiguration($config);
        // Try to initialize PDO connection
        try {
            $this->pdoConnection = new PDOTransactional($this->config[self::$config_dsn], $this->config[self::$config_username],
                $this->config[self::$config_password]);
            $this->pdoConnection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $ex) {
            throw new DBConnectionException("Unable to create connection!", DBConnectionException::CREATE_PDO_FAILED,
                $ex);
        }
    }

    /**
     * Methods that examines constructor argument $config
     * and validates them. Password is set to "" if missing.
     *
     * @param $config
     * @throws DBConnectionException
     */
    private function validateConfig($config)
    {
        // DSN string takes precedence over host/port/dbname params
        if (!isset($config[self::$config_dsn]) === true) {
            if (!isset($config[self::$config_driver]) === true) {
                throw new DBConnectionException("Missing DSN connection string and driver configuration!",
                    DBConnectionException::CONFIG_MISSING);
            }

            if (!isset($config[self::$config_host]) === true) {
                throw new DBConnectionException("Missing DSN connection string and host configuration!",
                    DBConnectionException::CONFIG_MISSING);
            }
        }
    }

    /**
     * Load default configuration and merge it with user-specific one.
     * @param $config
     */
    private function initializeConfiguration($config)
    {
        $this->config = array(
            self::$config_dsn => null,
            self::$config_driver => null,
            self::$config_host => null,
            self::$config_port => null,
            self::$config_dbname => null,
            self::$config_username => null,
            self::$config_password => null,
            self::$config_attributes => array(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION),
        );

        $this->config = array_merge($this->config, $config);

        if (!empty($this->config[self::$config_charset]) && $this->config[self::$config_driver] === "mysql") {
            $this->config[self::$config_attributes] = array_push($this->config[self::$config_attributes], array(
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES '" . $this->config[self::$config_charset] . "'"
            ));
        }

        if (empty($this->config[self::$config_dsn]) === true) {
            $this->config[self::$config_dsn] = $this->createDsnString();
        }
    }

    /**
     * Creates DSN string from $config parameters
     * @param $config
     * @return string
     */
    private function createDsnString()
    {
        $dsnString = null;
        switch ($this->config[self::$config_driver]) {
            case self::$driver_sqlite:
            case self::$driver_sqlite2:
                $dsnString = implode(":",
                    array($this->config[self::$config_driver], $this->config[self::$config_host]));
                break;
            case self::$driver_firebird:
            case self::$driver_oci:
                $dsnString = implode(":",
                    array($this->config[self::$config_driver], "dbname=" . $this->config[self::$config_dbname]));
                break;
            default:
                $dsnString = implode(":",
                    array($this->config[self::$config_driver], "host=" . $this->config[self::$config_host]));
                $dsnString .= ";dbname=" . $this->config[self::$config_dbname];
                break;
        }

        if (!empty($this->config[self::$config_charset])) {
            $dsnString .= ";charset=" . $this->config[self::$config_charset];
        }

        if (!empty($this->config[self::$config_port]) === true && is_numeric($this->config[self::$config_port])) {
            $dsnString .= ";port=" . $this->config[self::$config_port];
        }

        return $dsnString;
    }

    /**
     * DBConnection is a singleton. Use this method to fetch an instance!
     * @return PDOTransactional
     */
    public static function getConnection($config = null)
    {
        if (!isset(self::$instance)) {
            self::$instance = new DBConnection($config);
        }

        return self::$instance->getPdoConnection();
    }

    /**
     * @return PDOTransactional
     */
    public function getPdoConnection()
    {
        return $this->pdoConnection;
    }
}
