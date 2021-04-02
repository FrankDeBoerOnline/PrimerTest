<?php

namespace FrankDeBoerOnline\Common\Persist\Test;

use FrankDeBoerOnline\Common\DateTime\Error\DateTimeError;
use FrankDeBoerOnline\Common\Persist\Mapping\AbstractMapping;
use FrankDeBoerOnline\Common\Guid\Error\GuidErrorInvalidGuid;

class TestWithGuidPersistMapper extends AbstractMapping
{

    CONST MUTABLE = true;
    CONST TABLE_NAME = 'test_with_guid';
    CONST UNIQUE_COLUMN = 'guid';
    CONST COLUMNS = [
        'guid',
        'name',
        'amount',
        'bookDateTime',
    ];

    /**
     * @param TestWithGuid $persistableObject
     * @return array
     */
    public function objectToArray($persistableObject)
    {
        $record = [
            'guid' => (string)$persistableObject->getGuidIdentifier(),
            'name' => (string)$persistableObject->getName(),
            'amount' => (string)$persistableObject->getAmount(),
            'bookDateTime' => (string)$persistableObject->getBookDate()->format('Y-m-d H:i:s'),
        ];

        return $record;
    }

    /**
     * @param array $record
     * @return TestWithGuid
     * @throws GuidErrorInvalidGuid
     * @throws DateTimeError
     */
    public function arrayToObject($record)
    {
        $testWithGuid = new TestWithGuid($record['name'], $record['amount'], $record['bookDateTime']);
        $testWithGuid->setGuidIdentifier($record['guid']);
        return $testWithGuid;
    }

}