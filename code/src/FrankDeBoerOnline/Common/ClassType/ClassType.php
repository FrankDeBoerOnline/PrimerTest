<?php

namespace FrankDeBoerOnline\Common\ClassType;

use FrankDeBoerOnline\Common\ClassType\Error\ClassTypeErrorInvalidType;
use FrankDeBoerOnline\Common\ClassType\Error\ClassTypeErrorInvalidClass;

abstract class ClassType
{

    CONST CLASS_TYPE_FILE = '';

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $className;

    /**
     * ClassType constructor.
     * @param string $type
     * @throws ClassTypeErrorInvalidType
     */
    public function __construct($type)
    {
        $this->setType($type);
        $this->setClassName(static::ClassName($type));
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getType();
    }

    /**
     * @param string $name
     * @param $arguments
     * @return static
     * @throws ClassTypeErrorInvalidType
     */
    public static function __callStatic($name, $arguments)
    {
        return (new static($name));
    }

    /**
     * @param mixed $targetClass
     * @return static
     * @throws ClassTypeErrorInvalidType
     * @throws ClassTypeErrorInvalidClass
     */
    static public function fromClass($targetClass)
    {
        return ClassTypeMapper::getClassTypeByClass(static::class, $targetClass);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return $this
     * @throws ClassTypeErrorInvalidType
     */
    protected function setType($type)
    {
        if(!$this->isValidType($type)) {
            throw new ClassTypeErrorInvalidType(['type' => $type]);
        }

        $this->type = strtoupper((string)$type);
        return $this;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @param string $className
     * @return $this
     */
    protected function setClassName(string $className)
    {
        $this->className = $className;
        return $this;
    }

    /**
     * @param string $type
     * @return bool
     */
    static public function isValidType($type)
    {
        return ClassTypeMapper::isValidType(static::class, $type);
    }

    /**
     * @param mixed $targetClass
     * @return bool
     */
    static public function isValidClass($targetClass)
    {
        return ClassTypeMapper::isValidClass(static::class, $targetClass);
    }

    /**
     * @param mixed $targetClass
     * @return string
     * @throws ClassTypeErrorInvalidType
     * @throws ClassTypeErrorInvalidClass
     */
    static public function Type($targetClass)
    {
        return ClassTypeMapper::getTypeByClassName(static::class, $targetClass);
    }

    /**
     * @param string $type
     * @return string
     * @throws ClassTypeErrorInvalidType
     */
    static public function ClassName($type)
    {
        return ClassTypeMapper::getClassNameByType(static::class, $type);
    }

}