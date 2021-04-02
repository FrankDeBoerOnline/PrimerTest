<?php

use Phinx\Db\Adapter\AdapterInterface;
use Phinx\Migration\AbstractMigration;

class AddUserTable extends AbstractMigration
{

    public function change()
    {
        $this->table('user')
            ->addColumn('name', AdapterInterface::PHINX_TYPE_STRING)
            ->addColumn('email', AdapterInterface::PHINX_TYPE_STRING)
            ->addColumn('password', AdapterInterface::PHINX_TYPE_STRING)
            ->addColumn('datetime_created', AdapterInterface::PHINX_TYPE_DATETIME)
            ->addIndex(['email'], ['unique' => 'EMAIL_UNIQUE'])
            ->create();
    }
}
