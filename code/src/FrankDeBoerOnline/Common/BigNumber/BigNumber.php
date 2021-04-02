<?php

namespace FrankDeBoerOnline\Common\BigNumber;

use \Moontoast\Math\BigNumber as MathBigNumber;

/**
 * Acts as a wrapper for the BigNumber Library from Moontoast. This makes replacing it easier for the future
 * and fixing parent package bugs.
 *
 * Class BigNumber
 * @package FrankDeBoerOnline\Common\BigNumber
 */
class BigNumber extends MathBigNumber
{

    public function __construct($number, $scale = null)
    {
        // There is a bug in the main class where you cannot set a scale of '0' at construct, solved here
        if ($scale !== null) {
            $this->setScale($scale);
        }

        parent::__construct($number, null);
    }

    /**
     * Sets the value of this BigNumber to a new value
     * Overwrites for correct rounding
     *
     * @param mixed $number May be of any type that can be cast to a string
     *                      representation of a base 10 number
     * @return $this
     */
    public function setValue($number)
    {
        parent::setValue(
            $this->roundTo(
                $this->filterNumber($number),
                $this->getScale()
            )
        );

        return $this;
    }

    /**
     * Round a number to decimal points
     *
     * @param mixed $number
     * @param int $scale
     * @return string
     */
    public function roundTo($number, $scale = 0)
    {
        $scale = ($scale < 0) ? 0 : (int)$scale;

        if (strcmp(bcadd($number, '0', $scale), bcadd($number, '0', $scale + 1)) == 0) {
            return bcadd($number, '0', $scale);
        }

        if ($this->getNumberScale($number) - $scale > 1) {
            $number = $this->roundTo((string)$number, $scale + 1);
        }

        $t = '0.' . str_repeat('0', $scale) . '5';
        return ((string)$number < 0 ? bcsub($number, $t, $scale) : bcadd($number, $t, $scale));
    }

    /**
     * Get the number of decimals in a number
     *
     * @param $number
     * @return int
     */
    protected function getNumberScale($number) {
        $dotPosition = strpos($number, '.');
        if ($dotPosition === false) {
            return 0;
        }

        return (strlen($number) - strpos($number, '.') - 1);
    }

    /**
     * Check if current number falls between (or equals to) 2 numbers
     *
     * @param mixed $minNumber
     * @param mixed $maxNumber
     * @return bool
     */
    public function isBetween($minNumber, $maxNumber)
    {
        return $this->isGreaterThanOrEqualTo($minNumber) && $this->isLessThanOrEqualTo($maxNumber);
    }

}