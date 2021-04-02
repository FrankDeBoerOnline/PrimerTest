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

    public function __construct($name = '', $email = '', $password = '', DateTime $datetimeCreated = null)
    {
        $this
            ->setName($name)
            ->setEmail($email)
            ->setPassword($password)
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
     * @param DateTime $datetimeCreated
     * @return $this
     */
    public function setDatetimeCreated(DateTime $datetimeCreated = null)
    {
        $this->datetimeCreated = new DateTime($datetimeCreated);
        return $this;
    }

}