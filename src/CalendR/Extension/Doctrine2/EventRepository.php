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

/**
 * Trait that transforms a Doctrine2 EntityRepository into
 * a CalendR Event Provider
 *
 * @author Yohan Giarelli <yohan@giarel.li>
 */
trait EventRepository
{
    /**
     * @param \DateTime $begin
     * @param \DateTime $end
     * @param array     $options
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getEventsQueryBuilder(\DateTime $begin, \DateTime $end, array $options = array())
    {
        return QueryHelper::addEventQuery(
            $this->createQueryBuilderForGetEvent($options),
            $this->getBeginFieldName(),
            $this->getEndFieldName(),
            $begin,
            $end
        );
    }

    /**
     * @param \DateTime $begin
     * @param \DateTime $end
     * @param array     $options
     *
     * @return \Doctrine\ORM\Query
     */
    public function getEventsQuery(\DateTime $begin, \DateTime $end, array $options = array())
    {
        return $this->getEventsQueryBuilder($begin, $end, $options)->getQuery();
    }

    /**
     * @param \DateTime $begin
     * @param \DateTime $end
     * @param array     $options
     *
     * @return array<\CalendR\Event\EventInterface>
     */
    public function getEvents(\DateTime $begin, \DateTime $end, array $options = array())
    {
        return $this->getEventsQuery($begin, $end, $options)->getResult();
    }

    /**
     * @param array $options
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function createQueryBuilderForGetEvent(array $options)
    {
        return $this->createQueryBuilder('evt');
    }

    /**
     * Returns the begin date field name
     *
     * @return string
     */
    public function getBeginFieldName()
    {
        return 'evt.begin';
    }

    /**
     * Returns the end date field name
     *
     * @return string
     */
    public function getEndFieldName()
    {
        return 'evt.end';
    }
}
