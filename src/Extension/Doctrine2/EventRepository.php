<?php

/*
 * This file is part of CalendR, a Fréquence web project.
 *
 * (c) 2012 Fréquence web
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CalendR\Extension\Doctrine2;

use CalendR\Bridge\Doctrine\ORM\EventRepository as BaseEventRepository;

/**
 * Trait that transforms a Doctrine2 EntityRepository into
 * a CalendR Event Provider.
 *
 * @deprecated since 2.2, will be removed in 3.0. Use CalendR\Bridge\Doctrine\ORM\EventRepository instead
 *
 * @author Yohan Giarelli <yohan@giarel.li>
 */
trait EventRepository
{
    use BaseEventRepository;
}
