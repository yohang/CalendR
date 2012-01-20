<?php

namespace CalendR\Period;

class Month implements \Iterator, PeriodInterface
{
    /**
     * @var \DatePeriod
     */
    private $period;

    /**
     * @var Week
     */
    private $current;

    /**
     * @var \DateTime
     */
    private $begin;

    /**
     * @var \DateTime
     */
    private $end;

    public function __construct($year, $month)
    {
        $this->begin = new \DateTime(sprintf('%s-%s-01', $year, $month));
        $this->end = clone $this->begin;
        $this->end->add(new \DateInterval('P1M'));

        $this->period = new \DatePeriod($this->begin, new \DateInterval('P1D'), $this->end);
    }

    public function contains(\DateTime $date)
    {
        return $date->format('m') == $this->begin->format('m');
    }

    public function current()
    {
        return $this->current;
    }

    public function next()
    {
        if (!$this->valid()) {
            $delta = $this->begin->format('w');
            $delta = $delta ?: 7;
            $delta--;

            $start = clone $this->begin;
            $end = clone $this->begin;

            $this->current = new Week(
                $start->sub(new \DateInterval(sprintf('P%sD', $delta))),
                $end->add(new \DateInterval(sprintf('P%sD', 7 - $delta)))
            );
        } else {
            $start = clone $this->current->getEnd();

            if ($start->format('m') == $this->begin->format('m')) {
                $end = clone $start;
                $this->current = new Week($start, $end->add(new \DateInterval('P7D')));
            } else {
                $this->current = null;
            }
        }
    }

    public function key()
    {
        return $this->current->getNumber();
    }

    public function valid()
    {
        return null !== $this->current();
    }

    public function rewind()
    {
        $this->current = null;
        $this->next();
    }

}
