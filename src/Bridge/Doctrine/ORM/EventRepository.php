<?php

/*
 * This file is part of CalendR, a Fréquence web project.
 *
 * (c) 2012 Fréquence web
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CalendR\Bridge\Doctrine\ORM;

use CalendR\Event\EventInterface;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * Trait that transforms a Doctrine2 EntityRepository into
 * a CalendR Event Provider.
 *
 * @author Yohan Giarelli <yohan@giarel.li>
 */
trait EventRepository
{
    public function getEventsQueryBuilder(\DateTimeInterface $begin, \DateTimeInterface $end, array $options = []): QueryBuilder
    {
        $qb = $this->createQueryBuilderForGetEvent($options);

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
            );
    }

    public function getEventsQuery(\DateTimeInterface $begin, \DateTimeInterface $end, array $options = []): AbstractQuery
    {
        return $this->getEventsQueryBuilder($begin, $end, $options)->getQuery();
    }

    /**
     * @param \DateTime $begin
     * @param \DateTime $end
     * @param array $options
     *
     * @return array<EventInterface>
     */
    public function getEvents(\DateTimeInterface $begin, \DateTimeInterface $end, array $options = []): array
    {
        return $this->getEventsQuery($begin, $end, $options)->getResult();
    }

    public function createQueryBuilderForGetEvent(array $options): QueryBuilder
    {
        return $this->createQueryBuilder('evt');
    }

    public function getBeginFieldName(): string
    {
        return 'evt.begin';
    }

    public function getEndFieldName(): string
    {
        return 'evt.end';
    }
}
