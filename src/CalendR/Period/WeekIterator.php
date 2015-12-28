<?php

namespace CalendR\Period;

class WeekIterator implements \Iterator
{
    /**
     * @var null|\DateTime
     */
    protected $current = null;

    protected $week;

    public function __construct(PeriodInterface $week)
    {
        $this->week = $week;
    }

    /**
     * {@inheritDoc}
     */
    public function current()
    {
        return $this->week->getFactory()->createDay($this->current);
    }

    /**
     * {@inheritDoc}
     */
    public function next()
    {
        $dayInterval = new \DateInterval('P1D');
        if (!$this->valid()) {
            $this->current = clone $this->week->getBegin();
        } else {
            $this->current->add(new \DateInterval('P1D'));
            if (!$this->week->contains($this->current)) {
                $this->current = null;
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function key()
    {
        return $this->current->format('d-m-Y');
    }

    /**
     * {@inheritDoc}
     */
    public function valid()
    {
        return null !== $this->current;
    }

    /**
     * {@inheritDoc}
     */
    public function rewind()
    {
        $this->current = null;
        $this->next();
    }
}
