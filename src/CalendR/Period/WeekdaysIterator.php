<?php

namespace CalendR\Period;

class WeekdaysIterator extends WeekIterator
{
    public function next()
    {
        $dayInterval = new \DateInterval('P1D');
        if (!$this->valid()) {
            $this->current = clone $this->week->getBegin();
        } else {
            $this->current->add($dayInterval);
        }
        while (5 < $this->current->format('N')) { // skip SATURDAY and SUNDAY
            $this->current->add($dayInterval);
        }
        if (!$this->week->contains($this->current)) {
            $this->current = null;
        }
    }
}
