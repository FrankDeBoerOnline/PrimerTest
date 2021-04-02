<?php

namespace Tests\FrankDeBoerOnline\Common\ClassType;

use FrankDeBoerOnline\Common\ClassType\Error\ClassTypeErrorInvalidClass;
use FrankDeBoerOnline\Common\ClassType\Error\ClassTypeErrorInvalidType;
use PHPUnit\Framework\TestCase;

class ClassTypeTest extends TestCase
{

    /**
     * @throws ClassTypeErrorInvalidType
     */
    public function testConstruct()
    {
        $classType = new TestType('user');
        $this->assertSame('USER', $classType->getType());
        $this->assertSame(User::class, $classType->getClassName());

        $classType = new TestType('GROUP');
        $this->assertSame('GROUP', $classType->getType());
        $this->assertSame(Group::class, $classType->getClassName());
    }

    /**
     * @throws ClassTypeErrorInvalidType
     */
    public function testConstructException()
    {
        $this->expectException(ClassTypeErrorInvalidType::class);
        new TestType('invalid');
    }

    /**
     * @throws ClassTypeErrorInvalidType
     * @throws ClassTypeErrorInvalidClass
     */
    public function testFromClass()
    {
        $classType = TestType::fromClass(new User());
        $this->assertSame('USER', $classType->getType());
        $this->assertSame(User::class, $classType->getClassName());
    }

    public function testIsValidType()
    {
        $this->assertTrue(TestType::isValidType('User'));
        $this->assertTrue(TestType::isValidType('Group'));
        $this->assertFalse(TestType::isValidType('Invalid'));
    }

    public function testIsValidClass()
    {
        $this->assertTrue(TestType::isValidClass(new User()));
        $this->assertTrue(TestType::isValidClass(new Group()));
        $this->assertFalse(TestType::isValidClass($this));
    }

    /**
     * @throws ClassTypeErrorInvalidType
     * @throws ClassTypeErrorInvalidClass
     */
    public function testType()
    {
        $this->assertSame('USER', TestType::Type(new User()));
        $this->assertSame('USER', TestType::Type(User::class));
    }

    /**
     * @throws ClassTypeErrorInvalidType
     */
    public function testClassName()
    {
        $this->assertSame(User::class, TestType::ClassName('user'));
    }


}