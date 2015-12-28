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
 * a CalendR Event Provider.
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
        $begin = sprintf("'%s'", $begin->format('Y-m-d H:i:s'));
        $end   = sprintf("'%s'", $end->format('Y-m-d H:i:s'));
        $qb    = $this->createQueryBuilderForGetEvent($options);

        return $qb
            ->andWhere(
                $qb->expr()->orX(
                // Period in event
                    $qb->expr()->andX(
                        $qb->expr()->lte($this->getBeginFieldName(), $begin),
                        $qb->expr()->gte($this->getEndFieldName(), $end)
                    ),
                    // Event in period
                    $qb->expr()->andX(
                        $qb->expr()->gte($this->getBeginFieldName(), $begin),
                        $qb->expr()->lt($this->getEndFieldName(), $end)
                    ),
                    // Event begins during period
                    $qb->expr()->andX(
                        $qb->expr()->lt($this->getBeginFieldName(), $end),
                        $qb->expr()->gte($this->getBeginFieldName(), $begin)
                    ),
                    // Event ends during period
                    $qb->expr()->andX(
                        $qb->expr()->gte($this->getEndFieldName(), $begin),
                        $qb->expr()->lt($this->getEndFieldName(), $end)
                    )
                )
            )
            ;
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
     * Returns the begin date field name.
     *
     * @return string
     */
    public function getBeginFieldName()
    {
        return 'evt.begin';
    }

    /**
     * Returns the end date field name.
     *
     * @return string
     */
    public function getEndFieldName()
    {
        return 'evt.end';
    }
}
