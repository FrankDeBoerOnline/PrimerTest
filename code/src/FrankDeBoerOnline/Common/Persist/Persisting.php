<?php

namespace FrankDeBoerOnline\Common\Persist;

use Closure;
use FrankDeBoerOnline\Common\DateTime\Error\DateTimeError;
use Throwable;

use FrankDeBoerOnline\Common\DateTime\DateTime;
use FrankDeBoerOnline\Common\Persist\Error\DatabaseErrorImmutableObject;
use FrankDeBoerOnline\Common\Persist\Mapping\MappingInterface;

use FrankDeBoerOnline\Common\Persist\Error\DatabaseError;

trait Persisting
{

    /**
     * @var int
     */
    protected $persist_record_version;

    /**
     * @var DateTime
     */
    protected $persist_record_created;

    /**
     * @var DateTime
     */
    protected $persist_record_updated;


    /**
     * @return MappingInterface
     */
    abstract static public function getPersistingMapper();

    /**
     * @param int $version
     * @param mixed $created
     * @param mixed|null $updated
     * @throws DateTimeError
     */
    public function setPersistingRecordDetails($version = 0, $created = DateTime::DEFAULT_TIME, $updated = null)
    {
        $this->persist_record_version = (int)$version;
        $this->persist_record_created = new DateTime($created);
        $this->persist_record_updated = $updated ? new DateTime($updated) : null;
    }

    /**
     * @return int
     */
    public function getPersistVersion()
    {
        return (int)$this->persist_record_version;
    }

    /**
     * @return DateTime
     * @throws DateTimeError
     */
    public function getPersistCreated()
    {
        return ($this->persist_record_created ? clone $this->persist_record_created : new DateTime());
    }

    /**
     * @return DateTime|null
     */
    public function getPersistUpdated()
    {
        return ($this->persist_record_updated ? clone $this->persist_record_updated : null);
    }

    /**
     * @param bool $ignore_mutability
     * @return string
     * @throws DatabaseErrorImmutableObject
     * @throws DatabaseError
     */
    public function persist($ignore_mutability = false)
    {
        return $this::getPersistingMapper()->persist($this, $ignore_mutability);
    }

    /**
     * @return int|bool
     */
    public function delete()
    {
        return $this::getPersistingMapper()->delete($this);
    }

    /**
     * @param Closure $func
     * @return mixed
     * @throws Throwable
     */
    public function transactional(Closure $func)
    {
        return static::getPersistingMapper()->transactional($func);
    }

    /**
     * @param string $unique
     * @return $this|null
     */
    static public function find($unique)
    {
        return static::getPersistingMapper()->find($unique);
    }

    /**
     * @param string|array $column
     * @param mixed $value
     * @return $this|null
     */
    static public function findBy($column, $value = null)
    {
        return static::getPersistingMapper()->findBy($column, $value);
    }

}