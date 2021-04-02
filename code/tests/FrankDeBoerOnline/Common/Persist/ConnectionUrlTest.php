<?php

namespace Tests\FrankDeBoerOnline\Common\Persist;

use Doctrine\DBAL\DBALException;
use FrankDeBoerOnline\Common\Persist\ConnectionUrl;
use FrankDeBoerOnline\Common\Persist\Database;
use FrankDeBoerOnline\Common\Persist\Error\DatabaseError;
use FrankDeBoerOnline\Common\Persist\Error\DatabaseErrorConnectionUrl;
use FrankDeBoerOnline\Configuration\Configuration;
use PHPUnit\Framework\TestCase;

class ConnectionUrlTest extends TestCase
{

    /**
     * @throws DatabaseErrorConnectionUrl
     */
    public function testDefaults()
    {
        $connectionUrl = new ConnectionUrl();
        $this->assertSame('mysql://localhost:3306/', $connectionUrl->getUrl());
    }

    /**
     * @throws DatabaseErrorConnectionUrl
     */
    public function testFullUrl()
    {
        $connectionUrl = new ConnectionUrl('mysql', '127.0.0.1', 13306, 'testdb', 'user1', '12345');
        $this->assertSame('mysql://user1:12345@127.0.0.1:13306/testdb', (string)$connectionUrl);
    }

    /**
     * @throws DatabaseErrorConnectionUrl
     */
    public function testWithoutPassword()
    {
        $connectionUrl = new ConnectionUrl('mysql', '127.0.0.1', 13306, 'testdb', 'user1');
        $this->assertSame('mysql://user1@127.0.0.1:13306/testdb', (string)$connectionUrl);
    }

    /**
     * @throws DatabaseErrorConnectionUrl
     */
    public function testSQLiteUrl()
    {
        // We test this because most parameters are ignored for this driver
        $connectionUrl = new ConnectionUrl('sqlite', '', 0, '/tmp/testdb.sqlite3');
        $this->assertSame('sqlite:////tmp/testdb.sqlite3', (string)$connectionUrl);
    }

    /**
     * @throws DatabaseError
     * @throws DatabaseErrorConnectionUrl
     * @throws DBALException
     */
    public function testSQLiteConnection()
    {
        @unlink('/tmp/testdb.sqlite3');
        $connectionUrl = new ConnectionUrl('sqlite', '', 0, '/tmp/testdb.sqlite3');
        $this->assertTrue(Database::setConnection($connectionUrl, 'testsqlite'));
        @unlink('/tmp/testdb.sqlite3');
    }

    public function testDriverException()
    {
        $this->expectException(DatabaseErrorConnectionUrl::class);
        (new ConnectionUrl('invalid'));
    }

    public function testReplaceInternalVars()
    {
        // AppConfigDir
        $appConfigDir = Configuration::getAppConfigDir();
        $this->assertSame($appConfigDir, ConnectionUrl::replaceInternalVars('%DIR%'));
    }

    /**
     * @throws DatabaseErrorConnectionUrl
     */
    public function testFromApplicationEnvironment()
    {
        $appConfigDir = Configuration::getAppConfigDir();
        $connectionUrl = ConnectionUrl::fromApplicationEnvironment();
        $this->assertSame(
            "sqlite://ignored:ignored@ignored:1234/$appConfigDir/../tests/db/data/test.sqlite3",
            (string)$connectionUrl
        );
    }

}