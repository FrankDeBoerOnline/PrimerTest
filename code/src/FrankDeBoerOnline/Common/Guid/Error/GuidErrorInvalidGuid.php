<?php

namespace FrankDeBoerOnline\Common\Guid\Error;

class GuidErrorInvalidGuid extends GuidError
{

    CONST ERROR_CODE = 1;
    CONST ERROR_MESSAGE = 'Invalid Guid (\'{$guidIdentifier}\')';

}