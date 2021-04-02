<?php

namespace FrankDeBoerOnline\Common\Persist;

use Closure;
use FrankDeBoerOnline\Common\Persist\Error\DatabaseErrorConnectionUrl;
use Throwable;

use FrankDeBoerOnline\Common\Persist\Error\DatabaseError;
use FrankDeBoerOnline\Common\Persist\Error\DatabaseErrorConnectionError;
use FrankDeBoerOnline\Common\Persist\Error\DatabaseErrorImmutableObject;
use FrankDeBoerOnline\Common\Persist\Error\DatabaseErrorInvalidColumn;
use FrankDeBoerOnline\Common\Persist\Error\DatabaseErrorInvalidUnique;
use FrankDeBoerOnline\Common\Persist\Error\DatabaseErrorInvalidConnectionKey;
use FrankDeBoerOnline\Common\Persist\Error\DatabaseErrorUnknownConnection;

use FrankDeBoerOnline\Common\Persist\Mapping\AbstractMapping;

class Database
{

    CONST DEFAULT_CONNECTION_KEY = 'default';

    /**
     * @var ConnectionInterface[]
     */
    static private $connections = [];

    /**
     * Current active connection
     * @var string
     */
    static private $connectionKey = self::DEFAULT_CONNECTION_KEY;


    /**
     * @return string
     */
    public static function getConnectionKey()
    {
        return self::$connectionKey;
    }

    /**
     * @param string $connectionKey
     * @throws DatabaseErrorUnknownConnection
     */
    public static function setConnectionKey($connectionKey)
    {
        if(!isset(self::$connections[$connectionKey])) {
            throw new DatabaseErrorUnknownConnection();
        }

        self::$connectionKey = $connectionKey;
    }

    /**
     * @return ConnectionInterface
     */
    static public function getConnection()
    {
        self::setDefaultConnection();
        return self::$connections[self::getConnectionKey()];
    }

    /**
     * @param ConnectionUrl $connectionUrl
     * @param string $connectionKey
     * @return bool
     * @throws DatabaseErrorConnectionError
     * @throws DatabaseErrorInvalidConnectionKey
     */
    static public function setConnection(ConnectionUrl $connectionUrl, $connectionKey = self::DEFAULT_CONNECTION_KEY)
    {
        if(!$connectionKey) {
            throw new DatabaseErrorInvalidConnectionKey();
        }

        self::$connections[$connectionKey] = new Connection($connectionUrl);
        return self::$connections[$connectionKey]->isConnected();
    }

    static private function setDefaultConnection()
    {
        if(isset(self::$connections[self::DEFAULT_CONNECTION_KEY])) {
            return;
        }

        // Application configuration has priority
        try {
            self::setConnection(ConnectionUrl::fromApplicationEnvironment());
            return;

        } catch(DatabaseErrorConnectionUrl $e) {
        } catch(DatabaseErrorInvalidConnectionKey $e) {
        } catch(DatabaseErrorConnectionError $e) {}

        // Search for global configuration
        try {
            self::setConnection(ConnectionUrl::fromEnvironment());
            return;

        } catch(DatabaseErrorConnectionUrl $e) {
        } catch(DatabaseErrorInvalidConnectionKey $e) {
        } catch(DatabaseErrorConnectionError $e) {}
    }

    /**
     * @return bool
     */
    static public function getDebugging()
    {
        return self::getConnection()->isDebugging();
    }

    /**
     * @param $debug
     */
    static public function setDebugging($debug)
    {
        self::getConnection()->setDebugging($debug);
    }

    /**
     * @return bool
     */
    static public function isConnected()
    {
        return self::getConnection()->isConnected();
    }

    /**
     * @param Closure $func
     * @throws Throwable
     */
    static public function transactional(Closure $func)
    {
        self::getConnection()->transactional($func);
    }

    /**
     * @param AbstractMapping $mapper
     * @param array $record
     * @return string
     * @throws DatabaseError
     * @throws DatabaseErrorImmutableObject
     */
    static public function persist(AbstractMapping $mapper, $record)
    {
        return RecordSingle::persist($mapper, $record);
    }

    /**
     * @param AbstractMapping $mapper
     * @param array $record
     * @return int
     * @throws DatabaseError
     * @throws DatabaseErrorInvalidUnique
     */
    static public function delete(AbstractMapping $mapper, $record)
    {
        return RecordSingle::delete($mapper, $record);
    }

    /**
     * @param AbstractMapping $mapper
     * @param mixed $unique
     * @return array|false
     * @throws DatabaseError
     */
    static public function find(AbstractMapping $mapper, $unique)
    {
        return RecordSingle::find($mapper, $unique);
    }

    /**
     * @param AbstractMapping $mapper
     * @param string|array $column
     * @param mixed $value
     * @return array|false
     * @throws DatabaseError
     * @throws DatabaseErrorInvalidColumn
     */
    static public function findBy(AbstractMapping $mapper, $column, $value = null)
    {
        return RecordSingle::findBy($mapper, $column, $value);
    }

}