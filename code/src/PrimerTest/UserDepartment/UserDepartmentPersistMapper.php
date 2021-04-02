<?php

namespace PrimerTest\UserDepartment;

use FrankDeBoerOnline\Common\Persist\Error\DatabaseError;
use FrankDeBoerOnline\Common\Persist\Mapping\AbstractMapping;
use PrimerTest\Department\Department;
use PrimerTest\User\User;

class UserDepartmentPersistMapper extends AbstractMapping
{

    CONST MUTABLE = true;
    CONST TABLE_NAME = 'user_department';
    CONST UNIQUE_COLUMN = 'id';
    CONST COLUMNS = [
        'id',
        'user_id',
        'department_id'
    ];

    /**
     * @param UserDepartment $persistableObject
     * @param bool $ignore_mutability
     * @return string
     * @throws DatabaseError
     * @throws Throwable
     */
    public function persist($persistableObject, $ignore_mutability = false)
    {
        $this->transactional(function() use($persistableObject, $ignore_mutability) {
            $persistableObject->getUser()->persist(true);
            $persistableObject->getDepartment()->persist(true);
            parent::persist($persistableObject, $ignore_mutability);
        });

        return $this->getUnique();
    }

    /**
     * @param UserDepartment $persistableObject
     * @return string[]
     */
    public function objectToArray($persistableObject)
    {
        return [
            'id' => (string)$persistableObject->getId(),
            'user_id' => (string)$persistableObject->getUser()->getId(),
            'department_id' => (string)$persistableObject->getDepartment()->getId()
        ];
    }

    /**
     * @param array $record
     * @return UserDepartment
     */
    public function arrayToObject($record)
    {
        $user = User::find($record['user_id']);
        $department = Department::find($record['department_id']);

        $userDepartment = new UserDepartment($user, $department);
        $userDepartment->setId($record['id']);
        return $userDepartment;
    }

}