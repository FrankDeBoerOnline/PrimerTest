<?php

namespace PrimerTest\Department;

use FrankDeBoerOnline\Common\DateTime\DateTime;
use FrankDeBoerOnline\Common\Persist\Persistable;
use FrankDeBoerOnline\Common\Persist\Persisting;
use PrimerTest\User\User;

class Department implements Persistable
{

    use Persisting;

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var DateTime
     */
    private $datetimeCreated;

    /**
     * @return DepartmentPersistMapper
     */
    public static function getPersistingMapper()
    {
        return (new DepartmentPersistMapper());
    }

    public function __construct($name = '', $description = '', DateTime $datetimeCreated = null)
    {
        $this
            ->setName($name)
            ->setDescription($description)
            ->setDatetimeCreated($datetimeCreated);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDatetimeCreated()
    {
        return clone $this->datetimeCreated;
    }

    /**
     * @param DateTime $datetimeCreated
     * @return $this
     */
    public function setDatetimeCreated(DateTime $datetimeCreated = null)
    {
        $this->datetimeCreated = new DateTime($datetimeCreated);
        return $this;
    }

}