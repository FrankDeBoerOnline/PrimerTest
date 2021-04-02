<?php

namespace FrankDeBoerOnline\Common\DateTime;

use DateTimeZone;
use FrankDeBoerOnline\Common\DateTime\Error\DateTimeError;

class Date extends DateTime
{

    CONST FORMAT_STRING = 'Y-m-d';

    /**
     * Date constructor.
     * @param string $time
     * @param DateTimeZone|null $timezone
     * @throws DateTimeError
     */
    public function __construct($time = 'now', DateTimeZone $timezone = null)
    {
        parent::__construct($time, $timezone);
        $this->setTime(0,0,0);
    }

}