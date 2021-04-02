<?php

namespace Tests\FrankDeBoerOnline\Common\Persist;

use Doctrine\DBAL\ConnectionException;

use FrankDeBoerOnline\Common\DateTime\DateTime;
use FrankDeBoerOnline\Common\DateTime\Error\DateTimeError;
use FrankDeBoerOnline\Common\Persist\Database;
use FrankDeBoerOnline\Common\Persist\Error\DatabaseErrorImmutableObject;
use FrankDeBoerOnline\Common\Persist\Test\TestWithGuid;
use FrankDeBoerOnline\Common\Persist\Error\DatabaseError;
use Phinx\Console\PhinxApplication;
use Phinx\Wrapper\TextWrapper;
use Tests\PrimerTest\AbstractTest;

class DatabaseTest extends AbstractTest
{

    public  function testConnection()
    {
        $this->assertTrue(Database::isConnected(), 'Not connected to any database.');
    }

    /**
     * @throws DatabaseError
     * @throws DateTimeError
     * @throws DatabaseErrorImmutableObject
     */
    public function testWithGuid()
    {
        $bookDateTime = new DateTime();
        $newTestWithGuid = new TestWithGuid('Test7', '1234', $bookDateTime);
        $guid = $newTestWithGuid->getGuidIdentifier();
        $newTestWithGuid->persist(true);

        $getTestWithGuid = TestWithGuid::find($guid);
        $this->assertNotNull($getTestWithGuid, "Object not fetched");

        $this->assertSame('Test7', $getTestWithGuid->getName());
        $this->assertSame('1234', (string)$getTestWithGuid->getAmount());
        $this->assertSame($guid, $getTestWithGuid->getGuidIdentifier());
        $this->assertSame((string)$bookDateTime, (string)$getTestWithGuid->getBookDate());

        $newTestWithGuid->delete();
    }

    /**
     * @throws DatabaseError
     * @throws DatabaseErrorImmutableObject
     * @throws DateTimeError
     */
    public function testWithGuidUpdate()
    {

        $newTestWithGuid = new TestWithGuid('Test7', '1234', new DateTime());
        $newTestWithGuid->persist(true);

        $bookDateTime = new DateTime('2017-04-02 14:45:00');
        $newTestWithGuid = new TestWithGuid('Test7', '1234', $bookDateTime);
        $guid = $newTestWithGuid->getGuidIdentifier();
        $newTestWithGuid->persist(true);

        $getTestWithGuid = TestWithGuid::find($guid);
        $this->assertSame('Test7', $getTestWithGuid->getName());
        $this->assertSame('1234', (string)$getTestWithGuid->getAmount());
        $this->assertSame($guid, $getTestWithGuid->getGuidIdentifier());
        $this->assertSame((string)$bookDateTime, (string)$getTestWithGuid->getBookDate());

        $getTestWithGuid->delete();
    }

    /**
     * @throws DatabaseError
     * @throws DatabaseErrorImmutableObject
     * @throws DateTimeError
     */
    public function testWithGuidDelete()
    {

        $newTestWithGuid = new TestWithGuid('Test7', '1234', new DateTime());
        $newTestWithGuid->persist(true);
        $guid = $newTestWithGuid->getGuidIdentifier();
        $getTestWithGuid = TestWithGuid::find($guid);

        $this->assertSame($guid, $getTestWithGuid->getGuidIdentifier());

        $newTestWithGuid = new TestWithGuid('Test7', '1234', new DateTime('2017-04-02 14:45:00'));
        $newTestWithGuid->delete();

        $guid = $newTestWithGuid->getGuidIdentifier();
        $getTestWithGuid = TestWithGuid::find($guid);
        $this->assertNull($getTestWithGuid);
    }

}