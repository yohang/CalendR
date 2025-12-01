<?php

declare(strict_types=1);

namespace CalendR\Bridge\Doctrine\ORM;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\QueryBuilder;

/**
 * Trait that transforms a Doctrine2 EntityRepository into a CalendR Event Provider.
 */
trait EventRepository
{
    public function getEventsQueryBuilder(\DateTimeInterface $begin, \DateTimeInterface $end, array $options = []): QueryBuilder
    {
        $qb = $this->createQueryBuilderForGetEvent($options);

        return $qb
            ->andWhere(
                $qb->expr()->andX(
                    $qb->expr()->lt($this->getBeginFieldName(), ':end'),
                    $qb->expr()->gt($this->getEndFieldName(), ':begin'),
                )
            )
            ->setParameter(':begin', $begin)
            ->setParameter(':end', $end);
    }

    public function getEventsQuery(\DateTimeInterface $begin, \DateTimeInterface $end, array $options = []): AbstractQuery
    {
        return $this->getEventsQueryBuilder($begin, $end, $options)->getQuery();
    }

    public function getEvents(\DateTimeInterface $begin, \DateTimeInterface $end, array $options = []): array
    {
        return $this->getEventsQuery($begin, $end, $options)->getResult();
    }

    public function createQueryBuilderForGetEvent(array $options): QueryBuilder
    {
        return $this->createQueryBuilder('evt');
    }

    abstract public function getBeginFieldName(): string;

    abstract public function getEndFieldName(): string;
}
