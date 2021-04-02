<?php

namespace FrankDeBoerOnline\Common\Persist;

use FrankDeBoerOnline\Common\Persist\Error\DatabaseErrorConnectionUrl;
use FrankDeBoerOnline\Configuration\Configuration;
use FrankDeBoerOnline\Error\Error;

class ConnectionUrl
{

    CONST DRIVER_MYSQL = 'mysql';
    CONST DRIVER_SQLITE = 'sqlite';

    CONST SUPPORTED_DRIVERS = [
        self::DRIVER_MYSQL,
        self::DRIVER_SQLITE,
    ];

    /**
     * @var string
     */
    private $driver;

    /**
     * @var string
     */
    private $host;

    /**
     * @var int
     */
    private $port;

    /**
     * @var string
     */
    private $database;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @param string $driver
     * @param string $host
     * @param int $port
     * @param string $database
     * @param string $username
     * @param string $password
     * @throws DatabaseErrorConnectionUrl
     */
    public function __construct($driver = self::DRIVER_MYSQL, $host = 'localhost', $port = 3306, $database = '', $username = '', $password = '')
    {
        $this->setDriver($driver);
        $this->setHost($host);
        $this->setPort($port);
        $this->setDatabase($database);
        $this->setUsername($username);
        $this->setPassword($password);
    }

    /**
     * @return static
     * @throws DatabaseErrorConnectionUrl
     */
    static public function fromEnvironment()
    {
        return (new static(
            getenv('DB_TYPE'),
            getenv('DB_HOST'),
            getenv('DB_PORT'),
            getenv('DB_DATABASE'),
            getenv('DB_USERNAME'),
            getenv('DB_PASSWORD')
        ));
    }

    /**
     * @param string $configName
     * @return static
     * @throws DatabaseErrorConnectionUrl
     */
    static public function fromApplicationEnvironment($configName = 'database')
    {
        try {

            $configuration = Configuration::get();
            if(!isset($configuration->$configName)) {
                throw new DatabaseErrorConnectionUrl();
            }

            $databaseParameters = Configuration::get()->$configName;
            return (new static(
                $databaseParameters->driver,
                $databaseParameters->host,
                $databaseParameters->port,
                $databaseParameters->name,
                $databaseParameters->user,
                $databaseParameters->password
            ));

        } catch (Error $e) {
            throw new DatabaseErrorConnectionUrl();
        }
    }

    /**
     * @param string $value
     * @return string
     */
    static public function replaceInternalVars($value)
    {

        return str_replace(
            [
                '%DIR%'
            ],

            [
                Configuration::getAppConfigDir()
            ],

            $value);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getUrl();
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        $url = $this->getDriver() . '://';

        if($this->getUsername()) {
            $url .= $this->getUsername();

            if($this->getPassword()) {
                $url .= ':' . urlencode($this->getPassword());
            }

            $url .= '@';
        }

        $url .= $this->getHost();

        if($this->getPort()) {
            $url .= ':' . (string)$this->getPort();
        }

        $url .= '/' . $this->getDatabase();

        return $url;
    }

    /**
     * @return string
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @param string $driver
     * @return $this
     * @throws DatabaseErrorConnectionUrl
     */
    public function setDriver($driver)
    {
        if(!in_array($driver, static::SUPPORTED_DRIVERS)) {
            throw new DatabaseErrorConnectionUrl('Unsupported Driver');
        }

        $this->driver = $driver;
        return $this;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param string $host
     * @return $this
     */
    public function setHost($host)
    {
        $this->host = static::replaceInternalVars($host);
        return $this;
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param int $port
     * @return $this
     */
    public function setPort($port)
    {
        $this->port = (int)$port;
        return $this;
    }

    /**
     * @return string
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * @param string $database
     * @return $this
     */
    public function setDatabase($database)
    {
        $this->database = static::replaceInternalVars($database);
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = static::replaceInternalVars($username);
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = trim((string)$password);
        return $this;
    }

}