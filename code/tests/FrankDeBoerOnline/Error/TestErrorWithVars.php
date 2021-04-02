<?php

namespace Tests\FrankDeBoerOnline\Error;

use FrankDeBoerOnline\Error\Error;

class TestErrorWithVars extends Error
{

    CONST ERROR_MESSAGE = 'Replacing var1 with \'{$var1}\' and var2 with \'{$var2}\'.';
    CONST ERROR_CODE = 2;

}