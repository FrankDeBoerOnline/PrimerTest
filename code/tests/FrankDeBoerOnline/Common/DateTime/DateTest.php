<?php

namespace Tests\FrankDeBoerOnline\Common\DateTime;

use \DateTimeZone;

use FrankDeBoerOnline\Common\DateTime\Date;
use FrankDeBoerOnline\Common\DateTime\DateTime;
use FrankDeBoerOnline\Common\DateTime\Error\DateTimeError;
use PHPUnit\Framework\TestCase;

class DateTest extends TestCase
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
        $date = new Date($time, $timezone);
        $this->assertEquals($expectedFormattedResult, $date->format('Y-m-d H:i:sP'));
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
                'expectedFormattedResult' => '2012-08-13 00:00:00+00:00'
            ],
            [
                'time' => 946684801,
                'timezone' => null,
                'expectedFormattedResult' => '2000-01-01 00:00:00+00:00'
            ],
            [
                'time' => new DateTime('2012-08-13 13:04:00'),
                'timezone' => null,
                'expectedFormattedResult' => '2012-08-13 00:00:00+00:00'
            ],
            [
                'time' => '2012-08-13',
                'timezone' => new DateTimeZone('Europe/Amsterdam'),
                'expectedFormattedResult' => '2012-08-13 00:00:00+02:00'
            ],
            [
                'time' => '2012-08-13 00:00:00+00:00',
                'timezone' => new DateTimeZone('Europe/Amsterdam'),
                'expectedFormattedResult' => '2012-08-13 00:00:00+02:00'
            ],
            [
                'time' => '2012-08-13 00:00:00+02:00',
                'timezone' => null,
                'expectedFormattedResult' => '2012-08-12 00:00:00+00:00'
            ],
            [
                'time' => new DateTime('2012-08-13 13:04:00', new DateTimeZone('Europe/Amsterdam')),
                'timezone' => null,
                'expectedFormattedResult' => '2012-08-13 00:00:00+00:00'
            ],
            [
                'time' => new DateTime('2012-08-13 13:04:00', new DateTimeZone('Europe/Amsterdam')),
                'timezone' => new DateTimeZone('Europe/Amsterdam'),
                'expectedFormattedResult' => '2012-08-13 00:00:00+02:00'
            ],
            [
                'time' => new DateTime('2012-08-13 13:04:00'),
                'timezone' => new DateTimeZone('Europe/Amsterdam'),
                'expectedFormattedResult' => '2012-08-13 00:00:00+02:00'
            ],
            [
                'time' => new DateTime('2012-08-13 13:04:00', new DateTimeZone('Europe/Amsterdam')),
                'timezone' => new DateTimeZone('Europe/Athens'),
                'expectedFormattedResult' => '2012-08-13 00:00:00+03:00'
            ],
        ];
    }

    /**
     * @throws DateTimeError
     */
    public function testToString()
    {
        $date = new Date('2018-12-07 18:39:44');
        $this->assertEquals('2018-12-07', (string)$date, "Simple time extraction failed");

        $date = new Date('2018-12-07 01:39:44+0200');
        $this->assertEquals('2018-12-06', (string)$date, "Timezone should be converted to UTC");

        $date = new Date('2018-12-07 01:39:44+0200', new DateTimeZone('Europe/Amsterdam'));
        $this->assertEquals('2018-12-07', (string)$date, "Timezone should be included now");

        // Setting Timezone to default (UTC) should change date
        $date->setTimezone(Date::getDefaultTimezone());
        $this->assertEquals('2018-12-06', (string)$date, "Timezone did not change correctly");

    }

}