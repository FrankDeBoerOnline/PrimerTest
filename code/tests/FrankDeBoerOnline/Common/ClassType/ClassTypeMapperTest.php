<?php

namespace Tests\FrankDeBoerOnline\Common\ClassType;

use FrankDeBoerOnline\Common\ClassType\ClassTypeMapper;
use FrankDeBoerOnline\Common\ClassType\Error\ClassTypeErrorInvalidClass;
use FrankDeBoerOnline\Common\ClassType\Error\ClassTypeErrorInvalidType;
use PHPUnit\Framework\TestCase;

class ClassTypeMapperTest extends TestCase
{

    public function testIsValidType()
    {
        $this->assertTrue(ClassTypeMapper::isValidType(TestType::class, 'USER'));
        $this->assertTrue(ClassTypeMapper::isValidType(TestType::class, 'GROUP'));
        $this->assertFalse(ClassTypeMapper::isValidType(TestType::class, 'INVALID'));
    }

    public function testIsValidClass()
    {
        $this->assertTrue(ClassTypeMapper::isValidClass(TestType::class, new User()));
        $this->assertTrue(ClassTypeMapper::isValidClass(TestType::class, new Group()));
        $this->assertFalse(ClassTypeMapper::isValidClass(TestType::class, $this));
    }


    /**
     * @throws ClassTypeErrorInvalidType
     */
    public function testGetClassTypeByType()
    {
        $classType = ClassTypeMapper::getClassTypeByType(TestType::class, 'user');
        $this->assertSame('USER', $classType->getType());

        $classType = ClassTypeMapper::getClassTypeByType(TestType::class, 'group');
        $this->assertSame('GROUP', $classType->getType());
    }

    /**
     * @throws ClassTypeErrorInvalidType
     */
    public function testGetClassTypeByTypeException()
    {
        $this->expectException(ClassTypeErrorInvalidType::class);
        ClassTypeMapper::getClassTypeByType(TestType::class, 'invalid');
    }

    /**
     * @throws ClassTypeErrorInvalidType
     * @throws ClassTypeErrorInvalidClass
     */
    public function testGetTypeByClass()
    {
        $this->assertSame('USER', ClassTypeMapper::getTypeByClassName(TestType::class, User::class));
        $this->assertSame('GROUP', ClassTypeMapper::getTypeByClassName(TestType::class, Group::class));
    }

    /**
     * @throws ClassTypeErrorInvalidType
     * @throws ClassTypeErrorInvalidClass
     */
    public function testGetTypeByClassException()
    {
        $this->expectException(ClassTypeErrorInvalidClass::class);
        ClassTypeMapper::getTypeByClassName(TestType::class, Invalid::class);
    }

    /**
     * @throws ClassTypeErrorInvalidType
     * @throws ClassTypeErrorInvalidClass
     */
    public function testGetClassTypeByClass()
    {
        $classType = ClassTypeMapper::getClassTypeByClass(TestType::class, User::class);
        $this->assertSame('USER', $classType->getType());

        $classType = ClassTypeMapper::getClassTypeByClass(TestType::class, Group::class);
        $this->assertSame('GROUP', $classType->getType());
    }

    /**
     * @throws ClassTypeErrorInvalidType
     * @throws ClassTypeErrorInvalidClass
     */
    public function testGetClassTypeByClassException()
    {
        $this->expectException(ClassTypeErrorInvalidClass::class);
        ClassTypeMapper::getClassTypeByClass(TestType::class, Invalid::class);
    }

    /**
     * @throws ClassTypeErrorInvalidType
     */
    public function testGetClassNameByType()
    {
        $className = ClassTypeMapper::getClassNameByType(TestType::class, 'user'); // Lower-case should work
        $this->assertSame('Tests\FrankDeBoerOnline\Common\ClassType\User', $className);

        $className = ClassTypeMapper::getClassNameByType(TestType::class, 'GROUP');
        $this->assertSame('Tests\FrankDeBoerOnline\Common\ClassType\Group', $className);
    }

    /**
     * @throws ClassTypeErrorInvalidType
     */
    public function testGetClassNameByTypeException()
    {
        $this->expectException(ClassTypeErrorInvalidType::class);
        ClassTypeMapper::getClassNameByType(TestType::class, 'INVALID');
    }

}