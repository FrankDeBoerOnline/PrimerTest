<?php

namespace FrankDeBoerOnline\Common\Persist;

use Closure;
use Throwable;

use Doctrine\DBAL\Connection as dbalConnection;
use FrankDeBoerOnline\Common\Persist\Error\DatabaseErrorConnectionError;


interface ConnectionInterface
{

    /**
     * @param ConnectionUrl $connectionUrl
     * @throws DatabaseErrorConnectionError
     */
    public function __construct(ConnectionUrl $connectionUrl);

    /**
     * @return dbalConnection
     */
    public function getDbalConnection();

    /**
     * @return bool
     */
    public function isConnected();

    /**
     * @return bool
     */
    public function isDebugging();

    /**
     * @param bool $debugging
     * @return $this
     */
    public function setDebugging($debugging);

    /**
     * @param Closure $func
     * @throws Throwable
     */
    public function transactional(Closure $func);

}