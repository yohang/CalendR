<?php

namespace CalendR;

class Factory
{
    /**
     * @param \DateTime|int $yearOrStart year if month is filled, month begin datetime otherwise
     * @param null|int $month number (1~12)
     * @return Period\Month
     */
    public function getMonth($yearOrStart, $month = null)
    {
        if (!$yearOrStart instanceof \DateTime) {
            $yearOrStart = new \DateTime(sprintf('%s-%s-01', $yearOrStart, $month));
        }

        return new Period\Month($yearOrStart);
    }

    public function getWeek($year, $week)
    {
        return new Period\Week(new \DateTime(sprintf('%s-W%s', $year, str_pad($week, 2, '0', STR_PAD_LEFT))));
    }
}
