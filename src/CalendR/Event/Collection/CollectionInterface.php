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
     * @param EventInterface $event
     */
    public function add(EventInterface $event);

    /**
     * Removes an event from the collection
     *
     * @param EventInterface $event
     */
    public function remove(EventInterface $event);

    /**
     * Return all events;
     *
     * @return array<EventInterface>
     */
    public function all();

    /**
     * Returns if there is events corresponding to $index period
     *
     * @param mixed $index
     *
     * @return bool
     */
    public function has($index);

    /**
     * Find events in the collection
     *
     * @param mixed $index
     *
     * @return array<EventInterface>
     */
    public function find($index);
}
