<?php

namespace PrimerTest\UserDepartment;

use FrankDeBoerOnline\Common\Persist\Error\DatabaseError;
use FrankDeBoerOnline\Common\Persist\Persistable;
use FrankDeBoerOnline\Common\Persist\Persisting;
use PrimerTest\Department\Department;
use PrimerTest\User\User;

class UserDepartment implements Persistable
{

    use Persisting;

    /**
     * @var int
     */
    private $id;

    /**
     * @var User
     */
    private $user;

    /**
     * @var Department
     */
    private $department;

    public static function getPersistingMapper()
    {
        return (new UserDepartmentPersistMapper());
    }

    /**
     * @param User|null $user
     * @param Department|null $department
     * @return UserDepartment[]
     * @throws DatabaseError
     */
    public static function getUserDepartments(User $user = null, Department $department = null)
    {
        $userDepartmentRecordSet = new UserDepartmentRecordSet($user, $department);
        if($userDepartmentRecordSet->execute()) {
            return $userDepartmentRecordSet->fetchAll();
        }

        return [];
    }

    /**
     * @param User $user
     * @param Department $department
     * @return UserDepartment|null
     * @throws DatabaseError
     */
    public static function findUserDepartment(User $user, Department $department)
    {
        $userDepartmentRecordSet = new UserDepartmentRecordSet($user, $department);
        if($userDepartmentRecordSet->execute()) {
            return $userDepartmentRecordSet->fetch();
        }

        return null;
    }

    /**
     * UserDepartment constructor.
     * @param User|null $user
     * @param Department|null $department
     */
    public function __construct(User $user = null, Department $department = null)
    {
        $this->setUser($user);
        $this->setDepartment($department);
    }

    /**
     * @return string[]
     */
    public function toJSON()
    {
        return [
            'ud_id' => $this->getId(),
            'user' => $this->getUser()->toJSON(),
            'department' => $this->getDepartment()->toJSON()
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
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return $this
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return Department
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * @param Department|null $department
     * @return $this
     */
    public function setDepartment(Department $department = null)
    {
        $this->department = $department;
        return $this;
    }

}