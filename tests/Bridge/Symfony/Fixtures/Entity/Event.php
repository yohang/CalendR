<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\EventRepository;
use CalendR\Event\EventInterface;
use CalendR\Event\EventTrait;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: EventRepository::class)]
#[Table]
class Event implements EventInterface
{
    use EventTrait;

    #[Id]
    #[Column]
    #[GeneratedValue(strategy: 'AUTO')]
    public ?int $id = null;

    #[Column]
    public ?\DateTimeImmutable $begin = null;

    #[Column]
    public ?\DateTimeImmutable $end = null;

    public function getBegin(): \DateTimeInterface
    {
        return $this->begin;
    }

    public function getEnd(): \DateTimeInterface
    {
        return $this->end;
    }
}
