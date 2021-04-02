<?php

namespace FrankDeBoerOnline\Common\Persist;

use FrankDeBoerOnline\Common\Persist\Mapping\AbstractMapping;

class DatabaseObjectCache
{

    /**
     * @var array
     */
    private $objectCache = [];

    /**
     * @param AbstractMapping $mapper
     * @param string $unique
     * @return bool
     */
    public function isCached(AbstractMapping $mapper, $unique)
    {
        return isset($this->objectCache[$mapper::TABLE_NAME][$unique]);
    }

    /**
     * @param AbstractMapping $mapper
     * @param string $unique
     * @return array
     */
    public function getCache(AbstractMapping $mapper, $unique)
    {
        return $this->objectCache[$mapper::TABLE_NAME][$unique];
    }

    /**
     * @param AbstractMapping $mapper
     * @param string $unique
     * @param array|null $record
     * @return array|null
     */
    public function setCache(AbstractMapping $mapper, $unique, $record = null)
    {
        if(!isset($this->objectCache[$mapper::TABLE_NAME])) {
            $this->objectCache[$mapper::TABLE_NAME] = [];
        }

        if($record === null) {
            unset($this->objectCache[$mapper::TABLE_NAME][$unique]);
        } else {
            $this->objectCache[$mapper::TABLE_NAME][$unique] = $record;
        }

        return $record;
    }

    /**
     * @param AbstractMapping $mapper
     * @param string $unique
     * @return void
     */
    public function unsetCache(AbstractMapping $mapper, $unique)
    {
        $this->setCache($mapper, $unique, null);
    }

}