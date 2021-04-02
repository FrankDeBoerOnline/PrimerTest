<?php

namespace FrankDeBoerOnline\Configuration\Error;

class ResourceErrorInvalidFileType extends ResourceError
{

    CONST ERROR_CODE = 1;
    CONST ERROR_MESSAGE = 'Invalid Resource File Type (\'{$fileType}\'). Must be either \'json\' or \'yml\'';

}