<?php

/*
 * This file is part of CalendR, a Fréquence web project.
 *
 * (c) 2012 Fréquence web
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CalendR\Event\Provider;

/**
 * Base interface for event providers
 *
 * @author Yohan Giarelli <yohan@giarel.li>
 */
interface ProviderInterface
{
    /**
     * Return events that matches to $begin && $end
     * $end date should be exclude
     *
     * @param \DateTime $begin
     * @param \DateTime $end
     * @param array     $options
     */
    public function getEvents(\DateTime $begin, \DateTime $end, array $options = array());
}
