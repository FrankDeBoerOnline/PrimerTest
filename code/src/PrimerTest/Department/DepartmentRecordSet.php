<?php

namespace PrimerTest\Department;

use Doctrine\DBAL\Exception;
use FrankDeBoerOnline\Common\Persist\AbstractRecordSet;

class DepartmentRecordSet extends AbstractRecordSet
{

    public function __construct()
    {
        $this->setMapper(new DepartmentPersistMapper());
    }

    /**
     * @return void
     * @throws Exception
     */
    protected function buildStatement()
    {
        $departmentTable = DepartmentPersistMapper::TABLE_NAME;
        $prepareSql = "SELECT $departmentTable.* FROM $departmentTable";
        $prepareSql.= " ORDER BY $departmentTable.name";
        $this->statement = $this->getConnection()->prepare($prepareSql);
    }

}