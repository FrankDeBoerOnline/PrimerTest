<?php

use Phinx\Db\Adapter\AdapterInterface;
use Phinx\Migration\AbstractMigration;

class AddDepartmentTable extends AbstractMigration
{

    public function change()
    {
        $this->table('department')
            ->addColumn('name', AdapterInterface::PHINX_TYPE_STRING)
            ->addColumn('description', AdapterInterface::PHINX_TYPE_TEXT)
            ->addColumn('datetime_created', AdapterInterface::PHINX_TYPE_DATETIME)
            ->addIndex(['name'], ['unique' => 'NAME_UNIQUE'])
            ->create();
    }
}
