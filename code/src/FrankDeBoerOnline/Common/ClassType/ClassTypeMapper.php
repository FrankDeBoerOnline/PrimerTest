<?php

namespace FrankDeBoerOnline\Common\ClassType;

use FrankDeBoerOnline\Common\ClassType\Error\ClassTypeErrorInvalidClass;
use FrankDeBoerOnline\Common\ClassType\Error\ClassTypeErrorInvalidType;
use FrankDeBoerOnline\Configuration\Configuration;
use FrankDeBoerOnline\Configuration\Error\ResourceError;
use FrankDeBoerOnline\Configuration\Error\ResourceErrorInvalidFileType;
use FrankDeBoerOnline\Configuration\Error\ResourceFinderError;
use FrankDeBoerOnline\Configuration\Error\ResourceFinderErrorNotFound;

class ClassTypeMapper
{

    static private $_configCache = [];

    /**
     * @param string $classType
     * @param string $type
     * @return bool
     */
    static public function isValidType($classType, $type)
    {
        try {
            $config = self::getConfig($classType);
        } catch (ClassTypeErrorInvalidType $e) {}

        return isset($config['types'][strtoupper($type)]);
    }

    /**
     * @param string $classType
     * @param mixed $targetClass
     * @return bool
     */
    static public function isValidClass($classType, $targetClass)
    {
        try {
            $config = self::getConfig($classType);
        } catch (ClassTypeErrorInvalidType $e) {}

        return isset($config['classes'][self::getClassName($targetClass)]);
    }

    /**
     * @param string $classType
     * @param string $type
     * @return string $className
     * @throws ClassTypeErrorInvalidType
     */
    static public function getClassNameByType($classType, $type)
    {
        $type = strtoupper($type);
        if(self::isValidType($classType, $type)) {
            $config = self::getConfig($classType);
            if(isset($config['types'][$type])) {
                return $config['types'][$type];
            }
        }

        throw new ClassTypeErrorInvalidType();
    }

    /**
     * @param string $classType
     * @param mixed $targetClass
     * @return string
     * @throws ClassTypeErrorInvalidClass
     * @throws ClassTypeErrorInvalidType
     */
    static public function getTypeByClassName($classType, $targetClass)
    {
        $config = self::getConfig($classType);
        $className = self::getClassName($targetClass);
        if(isset($config['classes'][$className])) {
            return $config['classes'][$className];
        }

        throw new ClassTypeErrorInvalidClass(['class' => $className]);
    }

    /**
     * @param string $classType
     * @param string $type
     * @return ClassType
     * @throws ClassTypeErrorInvalidType
     */
    static public function getClassTypeByType($classType, $type)
    {
        $type = strtoupper($type);
        if(self::isValidType($classType, $type)) {
            return (new $classType($type));
        }
        throw new ClassTypeErrorInvalidType(['type' => $type]);
    }

    /**
     * @param string $classType
     * @param mixed $targetClass
     * @return ClassType
     * @throws ClassTypeErrorInvalidType
     * @throws ClassTypeErrorInvalidClass
     */
    static public function getClassTypeByClass($classType, $targetClass)
    {
        return (new $classType(
            self::getTypeByClassName($classType, $targetClass)
        ));
    }



    /**
     * @param string $classType
     * @return array
     * @throws ClassTypeErrorInvalidType
     */
    static private function getConfig($classType)
    {
        /**
         * @var ClassType $classType
         */

        if(!$classType::CLASS_TYPE_FILE) {
            throw new ClassTypeErrorInvalidType();
        }

        if(!isset(self::$_configCache[$classType::CLASS_TYPE_FILE])) {
            try {
                self::loadConfig($classType);

            } catch (ResourceError $e) {
                throw new ClassTypeErrorInvalidType('', 0, $e);
            } catch (ResourceFinderError $e) {
                throw new ClassTypeErrorInvalidType('', 0, $e);
            }
        }

        return self::$_configCache[$classType::CLASS_TYPE_FILE];
    }

    /**
     * @param string $classType
     * @throws ResourceErrorInvalidFileType
     * @throws ResourceFinderErrorNotFound
     */
    static private function loadConfig($classType)
    {
        /**
         * @var ClassType $classType
         */

        self::$_configCache[$classType::CLASS_TYPE_FILE] = [
            'types' => [],
            'classes' => []
        ];

        $config = Configuration::get($classType::CLASS_TYPE_FILE);
        foreach($config as $type => $class) {
            $type = strtoupper($type);
            self::$_configCache[$classType::CLASS_TYPE_FILE]['types'][$type] = $class;
            self::$_configCache[$classType::CLASS_TYPE_FILE]['classes'][$class] = $type;
        }
    }

    /**
     * @param mixed $targetClass
     * @return string
     */
    static private function getClassName($targetClass)
    {
        return is_string($targetClass) ? $targetClass : get_class($targetClass);
    }

}