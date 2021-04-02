<?php

namespace FrankDeBoerOnline\Common\Persist\Mapping;

use Closure;
use Throwable;

use FrankDeBoerOnline\Common\Persist\Error\DatabaseError;
use FrankDeBoerOnline\Common\Persist\Error\DatabaseErrorImmutableObject;

interface MappingInterface
{

    public function objectToArray($persistableObject);

    public function arrayToObject($record);

    /**
     * @return string
     */
    public function getTableName();

    /**
     * @return string
     */
    public function getUniqueColumn();

    /**
     * @return array
     */
    public function getColumns();

    /**
     * @return mixed
     */
    public function getUnique();

    /**
     * @param mixed $persistableObject
     * @param bool $ignore_mutability
     * @return string
     * @throws DatabaseError
     * @throws DatabaseErrorImmutableObject
     */
    public function persist($persistableObject, $ignore_mutability = false);

    public function delete($unique);

    public function find($unique);

    public function findBy($column, $value);

    /**
     * @param Closure $func
     * @return mixed
     * @throws Throwable
     */
    public function transactional(Closure $func);

}