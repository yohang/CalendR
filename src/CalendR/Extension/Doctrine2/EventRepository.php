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
     * @return array|\CalendR\Event\EventInterface
     */
    public function getEvents(\DateTime $begin, \DateTime $end, array $options = array())
    {
        $qb = $this->createQueryBuilderForGetEvent($options);
        $beginField = $this->getBeginFieldName();
        $endField = $this->getEndFieldName();

        return $qb
            ->where(
                $qb->expr()->orX(
                    // Period in event
                    $qb->expr()->andX(
                        $qb->expr()->lte($beginField, $begin),
                        $qb->expr()->gte($endField, $end)
                    ),
                    // Event in period
                    $qb->expr()->andX(
                        $qb->expr()->gte($beginField, $begin),
                        $qb->expr()->lt($endField, $end)
                    ),
                    // Event begins during period
                    $qb->expr()->andX(
                        $qb->expr()->lt($beginField, $end),
                        $qb->expr()->gte($beginField, $begin)
                    ),
                    // Event ends during period
                    $qb->expr()->andX(
                        $qb->expr()->gte($endField, $begin),
                        $qb->expr()->lt($endField, $end)
                    )
                )
            )
        ;
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function createQueryBuilderForGetEvent(array $options)
    {
        return $this->createQueryBuilder('evt');
    }

    /**
     * Returns the begin date field name
     *
     * @abstract
     * @return string
     */
    public function getBeginFieldName()
    {
        return 'evt.begin';
    }

    /**
     * Returns the end date field name
     * @abstract
     * @return string
     */
    public function getEndFieldName()
    {
        return 'evt.end';
    }
}
