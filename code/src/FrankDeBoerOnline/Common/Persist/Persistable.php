<?php

namespace FrankDeBoerOnline\Common\Persist;

use Closure;
use Throwable;

use FrankDeBoerOnline\Common\Persist\Error\DatabaseErrorImmutableObject;
use FrankDeBoerOnline\Common\Persist\Mapping\MappingInterface;
use FrankDeBoerOnline\Common\Persist\Error\DatabaseError;
use FrankDeBoerOnline\Common\DateTime\DateTime;
use FrankDeBoerOnline\Common\DateTime\Error\DateTimeError;

interface Persistable
{

    /**
     * @param int $version
     * @param mixed $created
     * @param mixed|null $updated
     * @throws DateTimeError
     */
    public function setPersistingRecordDetails($version = 0, $created = DateTime::DEFAULT_TIME, $updated = null);

    /**
     * @return int
     */
    public function getPersistVersion();

    /**
     * @return DateTime
     * @throws DateTimeError
     */
    public function getPersistCreated();

    /**
     * @return DateTime|null
     */
    public function getPersistUpdated();

    /**
     * @param bool $ignore_mutability
     * @return string
     * @throws DatabaseErrorImmutableObject
     */
    public function persist($ignore_mutability = false);

    /**
     * @return int|bool
     */
    public function delete();

    /**
     * @param Closure $func
     * @return mixed
     * @throws DatabaseError
     * @throws Throwable
     */
    public function transactional(Closure $func);

    /**
     * @return MappingInterface
     */
    static public function getPersistingMapper();

    /**
     * @param mixed $unique
     * @return static|null
     */
    static public function find($unique);

    /**
     * @param string|array $column
     * @param mixed $value
     * @return $this|null
     */
    static public function findBy($column, $value = null);

}