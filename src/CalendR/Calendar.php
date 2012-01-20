<?php

namespace CalendR;

/**
 * Factory class for calendar handling
 */
class Calendar
{
    /**
     * @param \DateTime|int $yearOrStart
     * @return Period\Year
     */
    public function getYear($yearOrStart)
    {
        if (!$yearOrStart instanceof \DateTime) {
            $yearOrStart = new \DateTime(sprintf('%s-01-01', $yearOrStart));
        }

        return new Period\Year($yearOrStart);
    }

    /**
     * @param \DateTime|int $yearOrStart year if month is filled, month begin datetime otherwise
     * @param null|int $month number (1~12)
     * @return \CalendR\Period\Month
     */
    public function getMonth($yearOrStart, $month = null)
    {
        if (!$yearOrStart instanceof \DateTime) {
            $yearOrStart = new \DateTime(sprintf('%s-%s-01', $yearOrStart, $month));
        }

        return new Period\Month($yearOrStart);
    }

    /**
     * @param \DateTime|int $yearOrStart
     * @param null|int $week
     * @return Period\Week
     */
    public function getWeek($yearOrStart, $week = null)
    {
        if (!$yearOrStart instanceof \DateTime) {
            $yearOrStart = new \DateTime(sprintf('%s-W%s', $yearOrStart, str_pad($week, 2, '0', STR_PAD_LEFT)));
        }

        return new Period\Week($yearOrStart);
    }

    /**
     * @param \DateTime|int $yearOrStart
     * @param null|int $month
     * @param null|int $day
     */
    public function getDay($yearOrStart, $month = null, $day = null)
    {
        if (!$yearOrStart instanceof \DateTime) {
            $yearOrStart = new \DateTime(sprintf('%s-%s-%s', $yearOrStart, $month, $day));
        }

        return new Period\Day($yearOrStart);
    }
}
