<?php

namespace FrankDeBoerOnline\Common\ClassType;

use FrankDeBoerOnline\Common\ClassType\Error\ClassTypeErrorInvalidType;
use FrankDeBoerOnline\Common\ClassType\Error\ClassTypeErrorInvalidClass;

trait ClassTypeMapping
{

    /**
     * @var ClassType
     */
    protected $_classType;

    /**
     * @return string
     */
    public function getClassType()
    {
        return (string)$this->_classType;
    }

    /**
     * @param string $classType
     * @return $this
     * @throws ClassTypeErrorInvalidType
     * @throws ClassTypeErrorInvalidClass
     */
    protected function setClassType($classType)
    {
        $this->_classType = ClassTypeMapper::getClassTypeByClass($classType, $this);
        return $this;
    }

}