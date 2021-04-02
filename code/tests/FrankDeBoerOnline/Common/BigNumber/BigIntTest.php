<?php

namespace Tests\FrankDeBoerOnline\Common\BigNumber;

use FrankDeBoerOnline\Common\BigNumber\BigInt;
use FrankDeBoerOnline\Common\BigNumber\Error\BigNumberIntegerNotScalable;
use PHPUnit\Framework\TestCase;

class BigIntTest extends TestCase
{

    /**
     * @param mixed $input
     * @param string $expectedValue
     * @dataProvider dataProviderConstruct
     */
    public function testConstruct($input, $expectedValue)
    {
        $bigInt = new BigInt($input);
        $this->assertSame($expectedValue, (string)$bigInt);
    }

    public function dataProviderConstruct()
    {
        return [
            [0.345, '0'],
            [-0.345, '0'],
            [0.545, '1'],
            [-0.545, '-1'],
            [11111111.345, '11111111'],
            [-11111111.345, '-11111111'],
            ['777777777777777777777777777777777777.6', '777777777777777777777777777777777778'],
            ['-777777777777777777777777777777777777.6', '-777777777777777777777777777777777778'],
        ];
    }

    /**
     * @throws BigNumberIntegerNotScalable
     */
    public function testSetScaleException()
    {
        $this->expectException(BigNumberIntegerNotScalable::class);
        $bigInt = new BigInt(12);
        $bigInt->setScale(1);
    }

}