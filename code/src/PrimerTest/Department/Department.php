<?php

namespace PrimerTest\Department;

use FrankDeBoerOnline\Common\DateTime\DateTime;
use FrankDeBoerOnline\Common\Persist\Persistable;
use FrankDeBoerOnline\Common\Persist\Persisting;

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

    /**
     * Department constructor.
     * @param string $name
     * @param string $description
     * @param mixed|null $datetimeCreated
     */
    public function __construct($name = '', $description = '', $datetimeCreated = null)
    {
        $this
            ->setName($name)
            ->setDescription($description)
            ->setDatetimeCreated($datetimeCreated);
    }

    /**
     * @return string[]
     */
    public function toJSON()
    {
        return [
            'department_id' => (string)$this->getId(),
            'name' => (string)$this->getName(),
            'description' => (string)$this->getDescription(),
            'datetime_created' => (string)$this->getDatetimeCreated()->getTimestamp()
        ];
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
     * @param mixed|null $datetimeCreated
     * @return $this
     */
    public function setDatetimeCreated($datetimeCreated = null)
    {
        $this->datetimeCreated = new DateTime($datetimeCreated);
        return $this;
    }

}