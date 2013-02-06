<?php

namespace CalendR\Period;

/**
 * Represents a Month
 *
 * @author Yohan Giarelli <yohan@giarel.li>
 */
class Month extends PeriodAbstract implements \Iterator
{
    /**
     * @var WeekInterface
     */
    private $current;

    /** @var string */
    protected $dayClass;

    /** @var string */
    protected $weekClass;

    /**
     * @param \DateTime $start
     * @param int $firstWeekday
     * @param array $classes
     * @throws Exception\NotAMonth
     */
    public function __construct(\DateTime $start, $classes = array())
    {
        if (!self::isValid($start)) {
            throw new Exception\NotAMonth;
        }
        parent::__construct($start);

        $this->end = clone $start;
        $this->end->add(new \DateInterval('P1M'));
        $this->dayClass = (!empty($classes['dayClass'])) ? $classes['dayClass'] : __NAMESPACE__ . '\Day';
        $this->weekClass = (!empty($classes['weekClass'])) ? $classes['weekClass'] : __NAMESPACE__ . '\Week';
    }

    /**
     * Returns the period as a DatePeriod
     *
     * @return \DatePeriod
     */
    public function getDatePeriod()
    {
        return new \DatePeriod($this->begin, new \DateInterval('P1D'), $this->end);
    }

    /**
     * Returns a Day array
     *
     * @return array<Day>
     */
    public function getDays()
    {
        $days = array();
        foreach ($this->getDatePeriod() as $date) {
            $days[] = new $this->dayClass($date);
        }

        return $days;
    }

    /**
     * Returns a Range period begining at the first day of first week of this month,
     * and ending at the last day of the last week of this month.
     *
     * @return Range
     */
    public function getExtendedMonth()
    {
        return new Range($this->getFirstDayOfFirstWeek(), $this->getLastDayOfLastWeek());
    }

    /**
     * Returns the first day of the first week of month.
     * First day of week is configurable via self::setFirstWeekday()
     *
     * @return \DateTime
     */
    public function getFirstDayOfFirstWeek()
    {
        $delta  = $this->begin->format('w') ?: 7;
        $weekClass = $this->weekClass;
        $delta -= $weekClass::firstWeekDay;

        $firstDay = clone $this->begin;
        $firstDay->sub(new \DateInterval(sprintf('P%sD', $delta)));

        return $firstDay;
    }

    /**
     * Returns the last day of last week of month
     * First day of week is configurable via self::setFirstWeekday()
     *
     * @return \DateTime
     */
    public function getLastDayOfLastWeek()
    {
        $lastDay = clone $this->end;
        $lastDay->sub(new \DateInterval('P1D'));
        $weekClass = $this->weekClass;
        $lastWeekday = $weekClass::firstWeekDay === Day::SUNDAY ? Day::SATURDAY : $weekClass::firstWeekDay - 1;

        $delta = $lastDay->format('w') - $lastWeekday;
        $delta = 7 - ($delta < 0 ? $delta + 7 : $delta);
        $delta = $delta === 7 ? 0 : $delta;
        $lastDay->add(new \DateInterval(sprintf('P%sD', $delta)));

        return $lastDay;
    }

    /**
     * Returns the monday of the first week of this month.
     *
     * @deprecated see self::getFirstDayOfFirstWeek
     *
     * @return \DateTime
     */
    public function getFirstMonday()
    {
        $delta = $this->begin->format('w') ?: 7;
        $delta--;

        $monday = clone $this->begin;
        $monday->sub(new \DateInterval(sprintf('P%sD', $delta)));

        return $monday;
    }

    /**
     * Returns the sunday of the last week of this month.
     *
     * @deprecated see self::getLastDayOfLastWeek
     *
     * @return \DateTime
     */
    public function getLastSunday()
    {
        $sunday = clone $this->end;
        $sunday->sub(new \DateInterval('P1D'));

        $delta = 7 - ($sunday->format('w') ?: 7);
        $sunday->add(new \DateInterval(sprintf('P%sD', $delta)));

        return $sunday;
    }

    /*
    * Iterator implementation
    */

    /**
     * @return Week
     */
    public function current()
    {
        return $this->current;
    }

    /**
     * {@inheritDoc}
     */
    public function next()
    {
        if (!$this->valid()) {
            $this->current = new $this->weekClass($this->getFirstDayOfFirstWeek());
        } else {
            $this->current = $this->current->getNext();

            if ($this->current->getBegin()->format('m') != $this->begin->format('m')) {
                $this->current = null;
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function key()
    {
        return $this->current->getNumber();
    }

    /**
     * {@inheritDoc}
     */
    public function valid()
    {
        return null !== $this->current();
    }

    /**
     * {@inheritDoc}
     */
    public function rewind()
    {
        $this->current = null;
        $this->next();
    }

    /**
     * Returns the month name (probably in english)
     *
     * @return string
     */
    public function __toString()
    {
        return $this->format('F');
    }

    /**
     * @param \DateTime $start
     *
     * @return bool
     */
    public static function isValid(\DateTime $start)
    {
        if (1 != $start->format('d')) {
            return false;
        }

        return true;
    }

    /**
     * Returns a \DateInterval equivalent to the period
     *
     * @return \DateInterval
     */
    public static function getDateInterval()
    {
        return new \DateInterval('P1M');
    }
}
