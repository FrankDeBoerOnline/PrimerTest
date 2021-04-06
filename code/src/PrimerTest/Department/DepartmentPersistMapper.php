<?php

namespace PrimerTest\Department;

use FrankDeBoerOnline\Common\Persist\Mapping\AbstractMapping;

class DepartmentPersistMapper extends AbstractMapping
{

    CONST MUTABLE = true;
    CONST TABLE_NAME = 'department';
    CONST UNIQUE_COLUMN = 'id';
    CONST COLUMNS = [
        'id',
        'name',
        'description',
        'datetime_created'
    ];

    /**
     * @param Department $persistableObject
     * @return string[]
     */
    public function objectToArray($persistableObject)
    {
        return [
            'id' => (int)$persistableObject->getId(),
            'name' => (string)$persistableObject->getName(),
            'description' => (string)$persistableObject->getDescription(),
            'datetime_created' => (string)$persistableObject->getDatetimeCreated()->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * @param array $record
     * @return Department
     */
    public function arrayToObject($record)
    {
        $department = new Department($record['name'], $record['description'], $record['datetime_created']);
        $department->setId($record['id']);
        return $department;
    }

}