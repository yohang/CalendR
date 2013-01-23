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

use Doctrine\ORM\QueryBuilder;

/**
 * Helper class for Doctrine2 CalendR integration
 *
 * @author Yohan Giarelli <yohan@giarel.li>
 */
class QueryHelper
{
    /**
     * This method is a helper to retrieve events in your Doctrine2 Repositories.
     * Use it like :
     * ```php
     *  public function getEvents(\DateTime $begin, \DateTime $end, array $options = array()) {
     *      return QueryHelper::addEventQuery(
     *          $this->createQueryBuilder('evt'),
     *          'evt.begin',
     *          'evt.end',
     *          $begin,
     *          $end
     *      )->getQuery()->getResult();
     *  }
     * ```
     *
     * @deprecated This method is deprecated from release, prefer the use of the EventRepository trait.
     *             However, the methods won't be removed until PHP5.3 is officialy maintained.
     *
     * @param QueryBuilder $qb
     * @param string       $beginField
     * @param string       $endField
     * @param \DateTime    $begin
     * @param \DateTime    $end
     *
     * @return QueryBuilder
     */
    public static function addEventQuery(QueryBuilder $qb, $beginField, $endField, \DateTime $begin, \DateTime $end)
    {
        $begin = sprintf("'%s'", $begin->format('Y-m-d H:i:s'));
        $end = sprintf("'%s'", $end->format('Y-m-d H:i:s'));

        return $qb
            ->andWhere(
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
}
