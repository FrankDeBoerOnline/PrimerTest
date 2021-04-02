<?php

namespace FrankDeBoerOnline\Common\Persist\Mapping;

use Closure;
use Throwable;

use FrankDeBoerOnline\Common\Persist\Database;
use FrankDeBoerOnline\Common\Persist\Error\DatabaseError;
use FrankDeBoerOnline\Common\Persist\Error\DatabaseErrorImmutableObject;
use FrankDeBoerOnline\Common\Persist\Error\DatabaseErrorInvalidColumn;
use FrankDeBoerOnline\Common\Persist\Error\DatabaseErrorInvalidUnique;

abstract class AbstractMapping implements MappingInterface
{

    CONST TABLE_NAME = 'unknown';
    CONST UNIQUE_COLUMN = 'guid';
    CONST COLUMNS = [
        'id',
        'guid',
    ];

    CONST MUTABLE = false;

    /**
     * @var mixed
     */
    private $unique;

    /**
     * @param $persistableObject
     * @return array
     */
    abstract public function objectToArray($persistableObject);

    /**
     * @param array $record
     * @return mixed
     */
    abstract public function arrayToObject($record);

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this::TABLE_NAME;
    }

    /**
     * @return string
     */
    public function getUniqueColumn()
    {
        return $this::UNIQUE_COLUMN;
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return $this::COLUMNS;
    }

    /**
     * @return mixed
     */
    public function getUnique()
    {
        return $this->unique;
    }

    /**
     * @param $record
     * @return $this
     */
    protected function setUnique($record)
    {
        $this->unique = $record[$this::UNIQUE_COLUMN];
        return $this;
    }

    /**
     * @param object $persistableObject
     * @param bool $ignore_mutability
     * @return string
     * @throws DatabaseError
     * @throws DatabaseErrorImmutableObject
     */
    public function persist($persistableObject, $ignore_mutability = false)
    {
        $record = $this->objectToArray($persistableObject);
        $this->setUnique($record);

        try {
            return Database::persist($this, $record);

        } catch(DatabaseErrorImmutableObject $e) {
            if($ignore_mutability) {
                return $this->getUnique();
            }

            throw $e;
        }
    }

    /**
     * @param mixed $persistableObject
     * @return int|bool
     * @throws DatabaseError
     * @throws DatabaseErrorInvalidUnique
     */
    public function delete($persistableObject)
    {
        $record = $this->objectToArray($persistableObject);
        $this->setUnique($record);
        return Database::delete($this, $record);
    }

    /**
     * @param string $unique
     * @return mixed|null
     * @throws DatabaseError
     */
    public function find($unique)
    {
        $record = Database::find($this, $unique);
        if(!$record) {
            return null;
        }

        $this->setUnique($record);
        return $this->arrayToObject($record);
    }

    /**
     * @param string $column
     * @param mixed $value
     * @return mixed|null
     * @throws DatabaseError
     * @throws DatabaseErrorInvalidColumn
     */
    public function findBy($column, $value)
    {
        $record = Database::findBy($this, $column, $value);
        if(!$record) {
            return null;
        }

        $this->setUnique($record);
        return $this->arrayToObject($record);
    }

    /**
     * @param Closure $func
     * @return mixed
     * @throws Throwable
     */
    public function transactional(Closure $func)
    {
        return Database::transactional($func);
    }

}