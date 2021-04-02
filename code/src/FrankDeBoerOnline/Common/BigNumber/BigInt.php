<?php

namespace FrankDeBoerOnline\Common\BigNumber;

use FrankDeBoerOnline\Common\BigNumber\Error\BigNumberIntegerNotScalable;

/**
 *
 * Acts as a BigNumber but scale always set to 0, so no decimal points
 *
 * Class BigInt
 * @package FrankDeBoerOnline\Common\BigNumber
 */
class BigInt extends BigNumber
{

    public function __construct($number)
    {
        parent::__construct($number, 0);
    }

    /**
     * @param int $scale
     * @return void
     * @throws BigNumberIntegerNotScalable
     */
    public function setScale($scale)
    {
        if($scale !== 0) {
            throw new BigNumberIntegerNotScalable();
        }

        parent::setScale(0);
    }

}