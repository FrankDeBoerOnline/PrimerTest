<?php

namespace FrankDeBoerOnline\Common\Persist;

use Closure;
use Doctrine\DBAL\Connection as dbalConnection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Logging\EchoSQLLogger;

use FrankDeBoerOnline\Common\Persist\Error\DatabaseErrorConnectionError;

class Connection implements ConnectionInterface
{

    /**
     * @var dbalConnection;
     */
    private $dbalConnection;

    /**
     * @var bool
     */
    private $debugging = false;


    public function __construct(ConnectionUrl $connectionUrl)
    {
        $this->connect($connectionUrl);
    }

    public function getDbalConnection()
    {
        return $this->dbalConnection;
    }

    /**
     * @param ConnectionUrl $connectionUrl
     * @throws DatabaseErrorConnectionError
     */
    protected function connect(ConnectionUrl $connectionUrl)
    {
        try {

            $this->dbalConnection = DriverManager::getConnection(['url' => (string)$connectionUrl]);
            $this->dbalConnection->connect();

        } catch (DBALException $e) {
            throw new DatabaseErrorConnectionError($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function isConnected()
    {
        return $this->getDbalConnection()->isConnected();
    }

    public function isDebugging()
    {
        return $this->debugging;
    }

    public function setDebugging($debugging)
    {
        if((bool)$debugging) {
            $this->getDbalConnection()->getConfiguration()->setSQLLogger(new EchoSQLLogger());
            $this->debugging = true;

        } else {
            $this->getDbalConnection()->getConfiguration()->setSQLLogger(null);
            $this->debugging = false;
        }

        return $this;
    }

    public function transactional(Closure $func)
    {
        $this->getDbalConnection()->transactional($func);
    }

}