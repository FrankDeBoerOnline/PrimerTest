<?php

namespace Tests\PrimerTest;

use Doctrine\DBAL\ConnectionException;
use Phinx\Console\PhinxApplication;
use Phinx\Wrapper\TextWrapper;

use FrankDeBoerOnline\Common\Persist\Database;

use PHPUnit\Framework\TestCase;

abstract class AbstractTest extends TestCase
{

    CONST PHINX_MIGRATION_ENVIRONMENT = 'dev';

    static private function runMigrations()
    {
        $app = new PhinxApplication();
        $wrap = new TextWrapper($app);

        $wrap->setOption('configuration', dirname(__DIR__) . '/phinx.php');

        # Run migrations
        $consoleOutput = $wrap->getMigrate(self::PHINX_MIGRATION_ENVIRONMENT);
        if($wrap->getExitCode() !== 0) {
            return $consoleOutput;
        }

        #Run seeds
        $consoleOutput = $wrap->getSeed(self::PHINX_MIGRATION_ENVIRONMENT);
        if($wrap->getExitCode() !== 0) {
            return $consoleOutput;
        }

        return true;
    }

    public static function setUpBeforeClass()
    {
        $consoleOutput = self::runMigrations();
        if($consoleOutput !== true) {
            exit($consoleOutput);
        }

        Database::getConnection()->getDbalConnection()->setAutoCommit(false);
        Database::getConnection()->getDbalConnection()->beginTransaction();
    }

    /**
     * @throws ConnectionException
     */
    public static function tearDownAfterClass()
    {
        while(Database::getConnection()->getDbalConnection()->getTransactionNestingLevel() > 1) {
            Database::getConnection()->getDbalConnection()->rollBack();
        }
        Database::getConnection()->getDbalConnection()->rollBack();
    }

}