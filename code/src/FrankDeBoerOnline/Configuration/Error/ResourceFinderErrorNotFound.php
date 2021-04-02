<?php

namespace FrankDeBoerOnline\Configuration\Error;

class ResourceFinderErrorNotFound extends ResourceFinderError
{

    CONST ERROR_CODE = 1;
    CONST ERROR_MESSAGE = 'Resource not found (\'{$resourceName}\')';

}