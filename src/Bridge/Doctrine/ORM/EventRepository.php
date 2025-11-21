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

        $beginField = $this->getBeginFieldName();
        $endField = $this->getEndFieldName();

        return $qb
            ->andWhere(
                $qb->expr()->andX(
                    "${beginField} < :end",
                    "${endField} > :begin"
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

    abstract public function createQueryBuilder(string $alias, string|null $indexBy = null): QueryBuilder;

    abstract public function getBeginFieldName(): string;

    abstract public function getEndFieldName(): string;
}
