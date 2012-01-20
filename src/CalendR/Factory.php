<?php

namespace CalendR;

class Factory
{
    public function getMonth($year, $month)
    {
        return new Period\Month($year, $month);
    }
}
