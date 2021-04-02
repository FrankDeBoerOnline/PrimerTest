<?php

namespace Tests\FrankDeBoerOnline\Common\DateTime;

use Exception;
use \DateTimeZone;

use FrankDeBoerOnline\Common\DateTime\DateTime;
use FrankDeBoerOnline\Common\DateTime\Error\DateTimeError;

use PHPUnit\Framework\TestCase;

class DateTimeTest extends TestCase
{

    /**
     * @param mixed $time
     * @param mixed $timezone
     * @param string $expectedFormattedResult
     * @throws DateTimeError
     * @dataProvider dataProviderValidConstruct
     */
    public function testValidConstruct($time, $timezone = null, $expectedFormattedResult = '')
    {
        $dateTime = new DateTime($time, $timezone);
        $this->assertEquals($expectedFormattedResult, $dateTime->format('Y-m-d H:i:sP'));
    }

    /**
     * @return array
     * @throws DateTimeError
     */
    public function dataProviderValidConstruct()
    {
        return [
            [
                'time' => '2012-08-13',
                'timezone' => null,
                'expectedFormattedResult' => '2012-08-13 00:00:00+00:00'
            ],
            [
                'time' => '2012-08-13 13:04:00',
                'timezone' => null,
                'expectedFormattedResult' => '2012-08-13 13:04:00+00:00'
            ],
            [
                'time' => 946684800,
                'timezone' => null,
                'expectedFormattedResult' => '2000-01-01 00:00:00+00:00'
            ],
            [
                'time' => new DateTime('2012-08-13 13:04:00'),
                'timezone' => null,
                'expectedFormattedResult' => '2012-08-13 13:04:00+00:00'
            ],
            [
                'time' => '2012-08-13',
                'timezone' => new DateTimeZone('Europe/Amsterdam'),
                'expectedFormattedResult' => '2012-08-13 00:00:00+02:00'
            ],
            [
                'time' => '2012-08-13 00:00:00+00:00',
                'timezone' => new DateTimeZone('Europe/Amsterdam'),
                'expectedFormattedResult' => '2012-08-13 02:00:00+02:00'
            ],
            [
                'time' => '2012-08-13 00:00:00+02:00',
                'timezone' => null,
                'expectedFormattedResult' => '2012-08-12 22:00:00+00:00'
            ],
            [
                'time' => new DateTime('2012-08-13 13:04:00', new DateTimeZone('Europe/Amsterdam')),
                'timezone' => null,
                'expectedFormattedResult' => '2012-08-13 11:04:00+00:00'
            ],
            [
                'time' => new DateTime('2012-08-13 13:04:00', new DateTimeZone('Europe/Amsterdam')),
                'timezone' => new DateTimeZone('Europe/Amsterdam'),
                'expectedFormattedResult' => '2012-08-13 13:04:00+02:00'
            ],
            [
                'time' => new DateTime('2012-08-13 13:04:00'),
                'timezone' => new DateTimeZone('Europe/Amsterdam'),
                'expectedFormattedResult' => '2012-08-13 15:04:00+02:00'
            ],
            [
                'time' => new DateTime('2012-08-13 13:04:00', new DateTimeZone('Europe/Amsterdam')),
                'timezone' => new DateTimeZone('Europe/Athens'),
                'expectedFormattedResult' => '2012-08-13 14:04:00+03:00'
            ],
        ];
    }

    /**
     * @throws DateTimeError
     */
    public function testToDefaultTimezone()
    {
        $dateTime = new DateTime('2018-12-07 19:17:00', new DateTimeZone('Europe/Athens'));
        $this->assertEquals('2018-12-07 19:17:00+02:00', (string)$dateTime);

        // Summer time
        $dateTime = new DateTime('2018-08-01 19:17:00', new DateTimeZone('Europe/Athens'));
        $this->assertEquals('2018-08-01 19:17:00+03:00', (string)$dateTime);

        $defaultDateTime = $dateTime->toDefaultTimezone();
        $this->assertEquals('2018-08-01 16:17:00+00:00', (string)$defaultDateTime);
    }

    /**
     *
     * @param string $dateString
     * @param bool $expectedResult
     * @throws DateTimeError
     * @dataProvider dataProviderIsBusinessDateIgnoreHolidays
     */
    public function testIsBusinessDateIgnoreHolidays($dateString, $expectedResult)
    {
        $date = new DateTime($dateString);
        $this->assertEquals($expectedResult, $date->isBusinessDay());
    }

    public function dataProviderIsBusinessDateIgnoreHolidays()
    {
        return [
            ['2018-12-07', true],
            ['2018-12-08', false],
            ['2018-12-09', false],
            ['2018-12-10', true],
            ['2018-12-11', true],
            ['2018-12-12', true],
            ['2018-12-13', true],
            ['2018-12-14', true],
            ['2018-12-15', false],
        ];
    }

}