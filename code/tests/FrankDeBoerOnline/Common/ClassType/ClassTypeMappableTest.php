<?php

namespace Tests\FrankDeBoerOnline\Common\ClassType;

use FrankDeBoerOnline\Common\ClassType\Error\ClassTypeErrorInvalidClass;
use FrankDeBoerOnline\Common\ClassType\Error\ClassTypeErrorInvalidType;
use PHPUnit\Framework\TestCase;

class ClassTypeMappableTest extends TestCase
{

    /**
     * @throws ClassTypeErrorInvalidType
     * @throws ClassTypeErrorInvalidClass
     */
    public function testConstruct()
    {
        $user = new User();
        $this->assertSame('USER', $user->getClassType());

        $group = new Group();
        $this->assertSame('GROUP', $group->getClassType());
    }

    /**
     * @throws ClassTypeErrorInvalidClass
     * @throws ClassTypeErrorInvalidType
     */
    public function testConstructInvalid()
    {
        $this->expectException(ClassTypeErrorInvalidClass::class);
        new Invalid();
    }

}