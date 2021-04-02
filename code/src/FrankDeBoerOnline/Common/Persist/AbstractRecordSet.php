<?php

namespace FrankDeBoerOnline\Common\Persist;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Statement;
use Doctrine\DBAL\Connection;

use FrankDeBoerOnline\Common\Persist\Error\DatabaseError;
use FrankDeBoerOnline\Common\Persist\Mapping\MappingInterface;

abstract class AbstractRecordSet implements RecordSetInterface
{

    /**
     * @var MappingInterface
     */
    protected $mapper;

    /**
     * @var Statement
     */
    protected $statement;

    /**
     * @var array|false
     */
    protected $currentRecord;

    /**
     * @return Statement
     */
    abstract protected function buildStatement();

    /**
     * @return Connection
     */
    protected function getConnection()
    {
        return Database::getConnection()->getDbalConnection();
    }

    /**
     * @return MappingInterface
     */
    public function getMapper()
    {
        return $this->mapper;
    }

    /**
     * @param MappingInterface $mapper
     * @return $this
     */
    protected function setMapper(MappingInterface $mapper)
    {
        $this->mapper = $mapper;
        return $this;
    }

    /**
     * @return bool
     * @throws DatabaseError
     */
    public function execute()
    {
        $this->buildStatement();
        try {
            return $this->statement->execute();

        } catch(DBALException $e) {
            throw new DatabaseError($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @return array|false
     */
    protected function getCurrentRecord()
    {
        return $this->currentRecord;
    }

    /**
     * @return $this
     */
    protected function setCurrentRecord()
    {
        $this->currentRecord = $this->statement->fetch();
        return $this;
    }

    /**
     * @return mixed
     */
    public function fetch()
    {
        $this->setCurrentRecord();
        if($this->getCurrentRecord()) {
            $mapper = $this->getMapper();
            if($mapper) {
                return $mapper->arrayToObject($this->getCurrentRecord());
            }
        }

        return $this->getCurrentRecord();
    }

    /**
     * @return array
     */
    public function fetchAll()
    {
        $result = [];
        while($row = $this->fetch()) {
            $result[] = $row;
        }

        return $result;
    }

    /**
     * @return int
     */
    public function getRowCount()
    {
        return $this->statement->rowCount();
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        $columns = [];
        for($i = 0; $i < $this->statement->columnCount(); $i++) {
            $column = $this->statement->fetchColumn($i);
            $columns[] = $column;
        }
        return $columns;
    }

}