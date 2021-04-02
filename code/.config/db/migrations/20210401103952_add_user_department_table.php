<?php

use Phinx\Db\Adapter\AdapterInterface;
use Phinx\Migration\AbstractMigration;

class AddUserDepartmentTable extends AbstractMigration
{

    public function change()
    {
        $this->table('user_department')
            ->addColumn('user_id', AdapterInterface::PHINX_TYPE_INTEGER)
            ->addColumn('department_id', AdapterInterface::PHINX_TYPE_INTEGER)
            ->addIndex(['user_id', 'department_id'], ['unique' => 'UNIQUE'])
            ->create();
    }
}
