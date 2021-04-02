<?php

namespace FrankDeBoerOnline\Common\DateTime;

use FrankDeBoerOnline\Common\DateTime\Error\DateTimeError;

use DateTimeZone;
use Exception;

class DateTime extends \DateTime
{

    CONST DEFAULT_TIME = 'now';
    CONST DEFAULT_TIMEZONE = 'UTC';

    CONST FORMAT_STRING = 'Y-m-d H:i:sP';

    /**
     * DateTime constructor.
     * @param mixed $time
     * @param DateTimeZone|null $timezone
     * @throws DateTimeError
     */
    public function __construct($time = self::DEFAULT_TIME, DateTimeZone $timezone = null)
    {
        try {
            $validTime = $this->getValidTime($time);
            $validTimezone = $this->getValidTimeZone($timezone);

            parent::__construct($validTime, $validTimezone);
            $this->setTimezone($validTimezone);

        } catch (Exception $e) {
            throw new DateTimeError($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->format();
    }

    /**
     * @param string $format
     * @return string
     */
    public function format($format = '')
    {
        return parent::format(($format ? $format : $this::FORMAT_STRING));
    }

    /**
     * @return $this
     */
    public function toLocalTimezone()
    {
        $newDateTime = clone $this;
        $newDateTime->setTimezone(self::getLocalTimezone());
        return $newDateTime;
    }

    /**
     * @return $this
     */
    public function toDefaultTimezone()
    {
        $newDateTime = clone $this;
        $newDateTime->setTimezone(self::getDefaultTimezone());
        return $newDateTime;
    }

    /**
     * @return DateTimeZone
     */
    static public function getDefaultTimezone()
    {
        return (new DateTimeZone(self::DEFAULT_TIMEZONE));
    }

    /**
     * @return DateTimeZone
     */
    static public function getLocalTimezone()
    {
        return (new DateTimeZone(date_default_timezone_get()));
    }

    /**
     * @param mixed $time
     * @return string
     */
    protected function getValidTime($time = self::DEFAULT_TIME)
    {
        if(is_a($time, \DateTime::class)) {
            return $time->format();
        }

        if(is_integer($time)) {
            return '@'.(int)$time;
        }

        return $time;
    }

    /**
     * @param DateTimeZone|null $timezone
     * @return DateTimeZone
     */
    protected function getValidTimeZone(DateTimeZone $timezone = null)
    {
        if(!$timezone) {
            return self::getDefaultTimezone();
        }

        return (clone $timezone);
    }

    /**
     * @param bool $ignoreHolidays
     * @return bool
     */
    public function isBusinessDay($ignoreHolidays = true)
    {
        if($ignoreHolidays) {
            true;
        }

        return ((int)$this->format('N') < 6);
    }

}