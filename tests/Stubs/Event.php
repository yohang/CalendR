<?php

declare(strict_types=1);

namespace CalendR\Test\Stubs;

use CalendR\Event\AbstractEvent;
use CalendR\Event\EventInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event extends AbstractEvent
{
    #[ORM\Id]
    #[ORM\Column(type: 'datetime_immutable')]
    protected ?string $id = null;

    #[ORM\Column(type: 'datetime_immutable')]
    protected ?\DateTimeImmutable $begin = null;

    #[ORM\Column(type: 'datetime_immutable')]
    protected ?\DateTimeImmutable $end = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setBegin(\DateTimeImmutable $begin): void
    {
        $this->begin = $begin;
    }

    public function getBegin(): \DateTimeImmutable
    {
        return $this->begin;
    }

    public function setEnd(\DateTimeImmutable $end): void
    {
        $this->end = $end;
    }

    public function getEnd(): \DateTimeImmutable
    {
        return $this->end;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function isEqualTo(EventInterface $event): bool
    {
        if (!$event instanceof self) {
            return false;
        }

        return $this->id === $event->id;
    }
}
