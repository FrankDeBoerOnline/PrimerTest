<?php

namespace Tests\FrankDeBoerOnline\Common\BigNumber;

use FrankDeBoerOnline\Common\BigNumber\Error\BigNumberInvalidInteger;
use FrankDeBoerOnline\Common\BigNumber\StrictBigInt;
use PHPUnit\Framework\TestCase;

class StrictBigIntTest extends TestCase
{

    /**
     * @param mixed $input
     * @param string $expectedResult
     * @dataProvider dataProviderValidIntegers
     */
    public function testValidIntegers($input, $expectedResult)
    {
        $int = new StrictBigInt($input);
        $this->assertSame($expectedResult, (string)$int);
    }

    public function dataProviderValidIntegers()
    {
        return [
            [1, '1'],
            [1.0, '1'],
            ['1.0', '1'],
            [3, '3'],
            [1114455555, '1114455555'],
            [-1114455555, '-1114455555'],
            ['999999999999999999999999999999999999999999999999', '999999999999999999999999999999999999999999999999'],
            ['-999999999999999999999999999999999999999999999999', '-999999999999999999999999999999999999999999999999'],
            ['aa', '0'],
            [0x12, '18']
        ];
    }

    /**
     * @param mixed $input
     * @dataProvider dataProviderInvalidIntegers
     */
    public function testInvalidIntegers($input)
    {
        $this->expectException(BigNumberInvalidInteger::class);
        new StrictBigInt($input);
    }

    public function dataProviderInvalidIntegers()
    {
        return [
            [1.2],
            [3.00001],
            [-3.00001],
            ['3.01'],
        ];
    }

}