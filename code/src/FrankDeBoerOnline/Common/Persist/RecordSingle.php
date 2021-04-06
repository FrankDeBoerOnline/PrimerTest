<?php

namespace FrankDeBoerOnline\Common\Persist;

use Doctrine\DBAL\Connection;
use FrankDeBoerOnline\Common\Persist\Error\DatabaseError;
use FrankDeBoerOnline\Common\Persist\Error\DatabaseErrorImmutableObject;
use FrankDeBoerOnline\Common\Persist\Error\DatabaseErrorInvalidColumn;
use FrankDeBoerOnline\Common\Persist\Error\DatabaseErrorInvalidUnique;
use FrankDeBoerOnline\Common\Persist\Mapping\AbstractMapping;

use Doctrine\DBAL\DBALException;

class RecordSingle
{

    CONST FIND_SQL = 'SELECT %s.* FROM %s WHERE %s = ? ORDER BY id DESC LIMIT 1';

    CONST FETCH_ASSOC = 2;

    /**
     * @var DatabaseObjectCache
     */
    static private $objectCache;

    /**
     * @return Connection
     */
    static private function getConnection()
    {
        return Database::getConnection()->getDbalConnection();
    }

    /**
     * @return DatabaseObjectCache
     */
    static private function getObjectCache()
    {
        if(!isset(self::$objectCache)) {
            self::$objectCache = new DatabaseObjectCache();
        }

        return self::$objectCache;
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
        $exists = self::exists($mapper, $record[$mapper::UNIQUE_COLUMN]);
        if($exists) {

            if(!$mapper::MUTABLE) {
                throw new DatabaseErrorImmutableObject(get_class($mapper) . ' is immutable');
            }

            try {
                $affectedRows = self::getConnection()->update($mapper::TABLE_NAME, $record, [$mapper::UNIQUE_COLUMN => $record[$mapper::UNIQUE_COLUMN]]);
                if($affectedRows) {
                    self::getObjectCache()->unsetCache($mapper, $record[$mapper::UNIQUE_COLUMN]);
                }

                return (int)$affectedRows;

            } catch (DBALException $e) {
                throw new DatabaseError($e->getMessage(), $e->getCode(), $e);
            }
        }

        try {
            self::getConnection()->insert($mapper::TABLE_NAME, $record);

        } catch (DBALException $e) {
            throw new DatabaseError($e->getMessage(), $e->getCode(), $e);
        }

        return self::getConnection()->lastInsertId();
    }

    /**
     * @param AbstractMapping $mapper
     * @param array $record
     * @return int
     * @throws DatabaseError
     * @throws DatabaseErrorImmutableObject
     * @throws DatabaseErrorInvalidUnique
     */
    static public function delete(AbstractMapping $mapper, $record)
    {

        if(!$mapper::MUTABLE) {
            throw new DatabaseErrorImmutableObject(get_class($mapper) . ' is immutable');
        }

        $formattedUnique = self::getValidUniqueString($record[$mapper::UNIQUE_COLUMN]);
        $exists = self::exists($mapper, $formattedUnique);

        if(!$exists) {
            self::getObjectCache()->unsetCache($mapper, $formattedUnique);
            return 0; # No records affected
        }

        try {
            $affectedRows = self::getConnection()->delete($mapper::TABLE_NAME, [$mapper::UNIQUE_COLUMN => $record[$mapper::UNIQUE_COLUMN]]);
            if($affectedRows) {
                self::getObjectCache()->unsetCache($mapper, $formattedUnique);
            }

            return (int)$affectedRows;

        } catch(DBALException $e) {
            throw new DatabaseError($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param AbstractMapping $mapper
     * @param mixed $unique
     * @return array|false
     * @throws DatabaseError
     */
    static public function find(AbstractMapping $mapper, $unique)
    {
        $formattedUnique = self::getValidUniqueString($unique);

        if(self::getObjectCache()->isCached($mapper, $formattedUnique)) {
            return self::getObjectCache()->getCache($mapper, $formattedUnique);
        }

        $record = self::fetchOne(self::FIND_SQL, $mapper::TABLE_NAME, $mapper::UNIQUE_COLUMN, $formattedUnique);
        return ($record !== false ? self::getObjectCache()->setCache($mapper, $formattedUnique, $record) : false);
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
        if(!in_array($column, $mapper::COLUMNS)) {
            throw new DatabaseErrorInvalidColumn();
        }

        return self::fetchOne(self::FIND_SQL, $mapper::TABLE_NAME, $column, $value);
    }

    /**
     * @param AbstractMapping $mapper
     * @param $unique
     * @return array|bool|null
     * @throws DatabaseError
     * @throws DatabaseErrorInvalidUnique
     */
    static private function exists(AbstractMapping $mapper, $unique)
    {
        return (0 < self::rowCount(self::FIND_SQL, $mapper::TABLE_NAME, $mapper::UNIQUE_COLUMN, self::getValidUniqueString($unique)));
    }

    /**
     * @param $sql
     * @param $table
     * @param $column
     * @param $value
     * @return array|false
     * @throws DatabaseError
     */
    static private function fetchOne($sql, $table, $column, $value)
    {
        $conn = self::getConnection();
        try {
            $statement = $conn->prepare(sprintf($sql, $table, $table, $column));
            $statement->bindValue(1, $value);
            $statement->execute();
            return $statement->fetch(self::FETCH_ASSOC);

        } catch(DBALException $e) {
            throw new DatabaseError($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param $sql
     * @param $table
     * @param $column
     * @param $value
     * @return int
     * @throws DatabaseError
     */
    static private function rowCount($sql, $table, $column, $value)
    {
        $conn = self::getConnection();
        try {
            $statement = $conn->prepare('SELECT COUNT(*) AS rowcount FROM (' . sprintf($sql, $table, $table, $column) . ') AS fullQuery');
            $statement->bindValue(1, $value);
            $statement->execute();
            $row = $statement->fetch();
            return ($row && isset($row['rowcount']) ? (int)$row['rowcount'] : 0);

        } catch(DBALException $e) {
            throw new DatabaseError($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param mixed $unique
     * @return string
     * @throws DatabaseErrorInvalidUnique
     */
    static private function getValidUniqueString($unique)
    {
        $formattedUnique = trim((string)$unique);
        if($formattedUnique === '') {
            throw new DatabaseErrorInvalidUnique();
        }
        return $formattedUnique;
    }

}