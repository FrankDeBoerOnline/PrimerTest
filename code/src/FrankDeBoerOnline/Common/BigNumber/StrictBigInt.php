<?php

namespace FrankDeBoerOnline\Common\BigNumber;

use FrankDeBoerOnline\Common\BigNumber\Error\BigNumberInvalidInteger;

/**
 * Converts values to integers and makes sure you cannot input values not considered 'convertable' integers
 *
 * For instance
 * 1.0 = 1 (valid)
 * 0x12 = 18 (valid)
 * a = 0 (valid)
 * 1.2 (invalid, not convertible to int)
 *
 *
 * Class StrictBigInt
 * @package FrankDeBoerOnline\Common\BigNumber
 */
class StrictBigInt extends BigInt
{

    CONST DECIMAL_COUNT_CHECK = 200;

    /**
     * Sets the value of this StrictBigInt to a new value
     *
     *
     * @param mixed $number May be of any type that can be cast to a string
     *                      representation of a base 10 number
     * @return $this
     * @throws BigNumberInvalidInteger
     */
    public function setValue($number)
    {
        // First set the value
        parent::setValue($number);

        $compare = new BigNumber($number, $this::DECIMAL_COUNT_CHECK);
        if(!$compare->isEqualTo($this)) {
            throw new BigNumberInvalidInteger(['integer' => $number]);
        }

        return $this;
    }

}