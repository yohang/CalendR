<?php

namespace CalendR\Period;

interface PeriodInterface
{
    function contains(\DateTime $date);
}
