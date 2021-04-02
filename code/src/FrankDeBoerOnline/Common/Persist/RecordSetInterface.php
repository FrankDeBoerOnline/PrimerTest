<?php

namespace FrankDeBoerOnline\Common\Persist;

use FrankDeBoerOnline\Common\Persist\Error\DatabaseError;
use FrankDeBoerOnline\Common\Persist\Mapping\MappingInterface;

interface RecordSetInterface
{

    /**
     * @return MappingInterface
     */
    public function getMapper();

    /**
     * @return bool
     * @throws DatabaseError
     */
    public function execute();

    /**
     * @return mixed
     */
    public function fetch();

    /**
     * @return array
     */
    public function fetchAll();

    /**
     * @return int
     */
    public function getRowCount();

    /**
     * @return array
     */
    public function getColumns();

}