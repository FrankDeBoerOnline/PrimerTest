<?php

namespace PrimerTest\UserDepartment;

use FrankDeBoerOnline\Common\Persist\AbstractRecordSet;
use PrimerTest\Department\Department;
use PrimerTest\User\User;

use PDO;
use Doctrine\DBAL\DBALException;

class UserDepartmentRecordSet extends AbstractRecordSet
{

    /**
     * @var User
     */
    private $user;

    /**
     * @var Department
     */
    private $department;

    public function __construct(User $user = null, Department $department = null)
    {
        $this->setUser($user);
        $this->setDepartment($department);
        $this->setMapper(new UserDepartmentPersistMapper());
    }

    /**
     * @return void
     * @throws DBALException
     */
    protected function buildStatement()
    {
        $userDepartmentTable = UserDepartmentPersistMapper::TABLE_NAME;

        $prepareSql = "SELECT $userDepartmentTable.* FROM $userDepartmentTable";

        $prepareSql.= " WHERE 1";

        if($this->getUser()) {
            $prepareSql .= " AND $userDepartmentTable.user_id = :userId";
        }

        if($this->getDepartment()) {
            $prepareSql .= " AND $userDepartmentTable.department_id = :departmentId";
        }

        $prepareSql .= " ORDER BY $userDepartmentTable.id";

        $this->statement = $this->getConnection()->prepare($prepareSql);

        if($this->getUser()) {
            $this->statement->bindValue('userId', $this->getUser()->getId(), PDO::PARAM_INT);
        }

        if($this->getDepartment()) {
            $this->statement->bindValue('departmentId', $this->getDepartment()->getId(), PDO::PARAM_INT);
        }
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