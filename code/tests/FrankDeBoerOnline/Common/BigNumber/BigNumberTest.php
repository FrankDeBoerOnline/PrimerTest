<?php

namespace Tests\FrankDeBoerOnline\Common\BigNumber;

use FrankDeBoerOnline\Common\BigNumber\BigNumber;
use PHPUnit\Framework\TestCase;

class BigNumberTest extends TestCase
{

    /**
     * @param mixed $number
     * @param int $scale
     * @param string $expectedValue
     * @dataProvider dataProviderRounding
     */
    public function testRounding($number, $scale, $expectedValue)
    {
        $bigNumber = new BigNumber($number, $scale);
        $this->assertSame($expectedValue, (string)$bigNumber);
    }

    public function dataProviderRounding()
    {
        return [
            [
                'number' => 0.556,
                'scale' => 2,
                'expectedValue' => '0.56'
            ],
            [
                'number' => 0.5,
                'scale' => 0,
                'expectedValue' => '1'
            ],
            [
                'number' => 0.554,
                'scale' => 2,
                'expectedValue' => '0.55'
            ],
            [
                'number' =>        '0.999999999999998',
                'scale' => 16,
                'expectedValue' => '0.9999999999999980'
            ],
            [
                'number' =>        '0.999999999999994',
                'scale' => 14,
                'expectedValue' => '0.99999999999999'
            ],
            [
                'number' => '0.99999999999999999999999999999999999999999998',
                'scale' => 15,
                'expectedValue' => '1.000000000000000'
            ],
            [
                'number' => '0.99999999999999999999999999999999999999999998',
                'scale' => 45,
                'expectedValue' => '0.999999999999999999999999999999999999999999980'
            ],
        ];
    }

    /**
     * @param mixed $number
     * @param mixed $betweenMin
     * @param mixed $betweenMax
     * @param bool $expectedResult
     * @dataProvider dataProviderIsBetween
     */
    public function testIsBetween($number, $betweenMin, $betweenMax, $expectedResult)
    {
        $bigNumber = new BigNumber($number, 3);
        $this->assertSame($expectedResult, $bigNumber->isBetween($betweenMin, $betweenMax));
    }

    public function dataProviderIsBetween()
    {
        return [
            ['2', 0, 1, false],
            ['1', 0, 1, true],
            ['-1', -2, -1, true],
            ['2', 1, 3, true],
            ['1.999', 1, 2, true],
        ];
    }

}