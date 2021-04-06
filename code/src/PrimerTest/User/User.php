<?php

namespace PrimerTest\User;

use FrankDeBoerOnline\Common\DateTime\DateTime;
use FrankDeBoerOnline\Common\Persist\Persistable;
use FrankDeBoerOnline\Common\Persist\Persisting;

class User implements Persistable
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
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * @var DateTime
     */
    private $datetimeCreated;

    /**
     * @return UserPersistMapper
     */
    public static function getPersistingMapper()
    {
        return (new UserPersistMapper());
    }

    /**
     * User constructor.
     * @param string $name
     * @param string $email
     * @param mixed|null $datetimeCreated
     */
    public function __construct($name = '', $email = '', $datetimeCreated = null)
    {
        $this
            ->setName($name)
            ->setEmail($email)
            ->setDatetimeCreated($datetimeCreated);
    }

    /**
     * @return string[]
     */
    public function toJSON()
    {
        return [
            'user_id' => (string)$this->getId(),
            'name' => (string)$this->getName(),
            'email' => (string)$this->getEmail(),
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
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
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
     * @param DateTime|mixed|null $datetimeCreated
     * @return $this
     */
    public function setDatetimeCreated($datetimeCreated = null)
    {
        $this->datetimeCreated = new DateTime($datetimeCreated);
        return $this;
    }

}