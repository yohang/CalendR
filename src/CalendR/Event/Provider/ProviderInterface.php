<?php

namespace CalendR\Event\Provider;

interface ProviderInterface
{
    /**
     * Return events that matches to $begin && $end
     * $end date should be exclude
     *
     * @abstract
     * @param \DateTime $begin
     * @param \DateTime $end
     */
    public function getEvents(\DateTime $begin, \DateTime $end);
}