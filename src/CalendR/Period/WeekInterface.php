<?php

/*
 * This file is part of CalendR, a Fréquence web project.
 *
 * (c) 2012 Fréquence web
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CalendR\Period;

interface WeekInterface extends PeriodInterface
{
    /**
     * @return int
     */
    function getNumber();

    /**
     * @return int
     */
    static function getFirstWeekday();

}
