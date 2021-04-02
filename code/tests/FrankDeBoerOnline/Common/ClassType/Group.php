<?php

namespace Tests\FrankDeBoerOnline\Common\ClassType;

use FrankDeBoerOnline\Common\ClassType\ClassTypeMapping;
use FrankDeBoerOnline\Common\ClassType\Error\ClassTypeErrorInvalidClass;
use FrankDeBoerOnline\Common\ClassType\Error\ClassTypeErrorInvalidType;

class Group
{

    use ClassTypeMapping;

    /**
     * Group constructor.
     * @throws ClassTypeErrorInvalidType
     * @throws ClassTypeErrorInvalidClass
     */
    public function __construct()
    {
        $this->setClassType(TestType::class);
    }

}