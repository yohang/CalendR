<?php

/*
 * This file is part of CalendR, a Fréquence web project.
 *
 * (c) 2012 Fréquence web
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CalendR\Event\Collection;

use CalendR\Event\EventInterface;

/**
 * Represents an event collection.
 */
interface CollectionInterface extends \Countable
{
    /**
     * Adds an event to the collection
     *
     * @abstract
     * @param CalendR\Event\EventInterface $event
     */
    public function add(EventInterface $event);

    /**
     * Removes an event from the collection
     *
     * @abstract
     * @param CalendR\Event\EventInterface $event
     */
    public function remove(EventInterface $event);

    /**
     * Find events in the collection
     *
     * @abstract
     * @param $index
     */
    public function find($index);
}
