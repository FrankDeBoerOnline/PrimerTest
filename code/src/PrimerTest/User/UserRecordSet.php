<?php

namespace PrimerTest\User;

use Doctrine\DBAL\Exception;
use FrankDeBoerOnline\Common\Persist\AbstractRecordSet;

class UserRecordSet extends AbstractRecordSet
{

    public function __construct()
    {
        $this->setMapper(new UserPersistMapper());
    }

    /**
     * @return void
     * @throws Exception
     */
    protected function buildStatement()
    {
        $userTable = UserPersistMapper::TABLE_NAME;
        $prepareSql = "SELECT $userTable.* FROM $userTable";
        $prepareSql.= " ORDER BY $userTable.name";
        $this->statement = $this->getConnection()->prepare($prepareSql);
    }

}