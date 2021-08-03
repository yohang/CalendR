<?php

namespace CalendR\Test\Stubs;

use CalendR\Event\AbstractEvent;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="CalendR\Test\Stubs\EventRepository")
 */
class Event extends AbstractEvent
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=31)
     */
    protected ?string $id = null;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    protected ?\DateTimeImmutable $begin = null;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    protected ?\DateTimeImmutable $end = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Returns an unique identifier for the Event.
     * Could be any string, but MUST to be unique.
     *   ex : 'event-8', 'meeting-43'
     *
     * @return string an unique event identifier
     */
    public function getUid(): string
    {
        return $this->getId();
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

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
