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
        // TODO: Implement getPersistingMapper() method.
    }

    /**
     * @param User $user
     * @return UserDepartment[]
     * @throws DatabaseError
     */
    public static function getDepartmentsForUser(User $user)
    {
        $userDepartmentRecordSet = new UserDepartmentRecordSet($user);
        if($userDepartmentRecordSet->execute()) {
            return $userDepartmentRecordSet->fetchAll();
        }

        return [];
    }

    /**
     * @param Department $department
     * @return UserDepartment[]
     * @throws DatabaseError
     */
    public static function getUsersForDepartment(Department $department)
    {
        $userDepartmentRecordSet = new UserDepartmentRecordSet(null, $department);
        if($userDepartmentRecordSet->execute()) {
            return $userDepartmentRecordSet->fetchAll();
        }

        return [];
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