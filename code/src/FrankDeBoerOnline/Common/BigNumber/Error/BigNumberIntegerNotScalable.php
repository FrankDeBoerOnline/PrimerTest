<?php

namespace FrankDeBoerOnline\Common\BigNumber\Error;

class BigNumberIntegerNotScalable extends BigNumberError
{

    CONST ERROR_CODE = 1;
    CONST ERROR_MESSAGE = 'Integer values do not have scales (decimals)';

}