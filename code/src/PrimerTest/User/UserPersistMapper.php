<?php

namespace PrimerTest\User;

use FrankDeBoerOnline\Common\Persist\Mapping\AbstractMapping;

class UserPersistMapper  extends AbstractMapping
{

    CONST MUTABLE = true;
    CONST TABLE_NAME = 'user';
    CONST UNIQUE_COLUMN = 'id';
    CONST COLUMNS = [
        'id',
        'name',
        'email',
        'password',
        'datetime_created'
    ];

    /**
     * @param User $persistableObject
     * @return string[]
     */
    public function objectToArray($persistableObject)
    {
        return [
            'id' => (int)$persistableObject->getId(),
            'name' => (string)$persistableObject->getName(),
            'email' => (string)$persistableObject->getEmail(),
            'password' => (string)$persistableObject->getPassword(),
            'datetime_created' => (string)$persistableObject->getDatetimeCreated()->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * @param array $record
     * @return User
     */
    public function arrayToObject($record)
    {
        $user = new User($record['name'], $record['email'], $record['datetime_created']);
        $user->setId($record['id']);
        $user->setPassword($record['password']);
        return $user;
    }

}