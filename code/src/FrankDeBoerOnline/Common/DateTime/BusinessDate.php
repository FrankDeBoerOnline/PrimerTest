<?php

namespace FrankDeBoerOnline\Common\DateTime;

use FrankDeBoerOnline\Common\DateTime\Error\DateTimeError;

use DateInterval;
use DateTimeZone;
use Exception;

/**
 * Only accept business days. This excludes Saturday, Sunday and Holidays.
 *
 */
class BusinessDate extends Date
{

    CONST DAY_SEARCH_INTERVAL = 'P1D';
    CONST DAY_SEARCH_DOWN = 'down';
    CONST DAY_SEARCH_UP = 'up';

    /**
     * BusinessDate constructor.
     * @param string $time
     * @param DateTimeZone|null $timezone
     * @param string $searchType Search for the first business day occurrence up or down
     * @throws DateTimeError
     */
    public function __construct($time = 'now', DateTimeZone $timezone = null, $searchType = self::DAY_SEARCH_UP)
    {
        parent::__construct($time, $timezone);
        $this->searchBusinessDay($searchType);
    }

    /**
     * @param string $searchType
     * @return bool
     * @throws DateTimeError
     */
    public function searchBusinessDay($searchType = self::DAY_SEARCH_UP)
    {
        if($this->isBusinessDay()) {
            return true;
        }

        $this->add($this->getDateInterval($searchType !== self::DAY_SEARCH_UP));
        return $this->searchBusinessDay($searchType);
    }

    /**
     * @param bool $invert
     * @return DateInterval
     * @throws DateTimeError
     */
    protected function getDateInterval($invert = false)
    {
        try {
            $interval = new DateInterval(self::DAY_SEARCH_INTERVAL);
            $interval->invert = $invert;
            return $interval;

        } catch (Exception $e) {
            throw new DateTimeError($e->getMessage(), $e->getCode(), $e);
        }
    }

}