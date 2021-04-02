<?php

namespace Tests\FrankDeBoerOnline\Common\DateTime;

use FrankDeBoerOnline\Common\DateTime\BusinessDate;
use FrankDeBoerOnline\Common\DateTime\Error\DateTimeError;
use PHPUnit\Framework\TestCase;

class BusinessDateTest extends TestCase
{

    /**
     * @param string $constructDate
     * @param string $expectedDate
     * @throws DateTimeError
     * @dataProvider dataProviderNextBusinessDay
     */
    public function testNextBusinessDay($constructDate, $expectedDate)
    {
        $businessDate = new BusinessDate($constructDate);
        $this->assertEquals($expectedDate, (string)$businessDate);
    }

    public function dataProviderNextBusinessDay()
    {
        return [
            ['2018-12-08', '2018-12-10'],
            ['2018-12-09', '2018-12-10'],
            ['2018-12-10', '2018-12-10'],
            ['2018-12-11', '2018-12-11'],
            ['2018-12-14', '2018-12-14'],
            ['2018-12-15', '2018-12-17'],
        ];
    }

    /**
     * @param string $constructDate
     * @param string $expectedDate
     * @throws DateTimeError
     * @dataProvider dataProviderPreviousBusinessDay
     */
    public function testPreviousBusinessDay($constructDate, $expectedDate)
    {
        $businessDate = new BusinessDate($constructDate, null, BusinessDate::DAY_SEARCH_DOWN);
        $this->assertEquals($expectedDate, (string)$businessDate);
    }

    public function dataProviderPreviousBusinessDay()
    {
        return [
            ['2018-12-08', '2018-12-07'],
            ['2018-12-09', '2018-12-07'],
            ['2018-12-10', '2018-12-10'],
            ['2018-12-11', '2018-12-11'],
            ['2018-12-14', '2018-12-14'],
            ['2018-12-15', '2018-12-14'],
        ];
    }

}