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
     *
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    protected $begin;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    protected $end;

    /**
     * @return int
     */
    public function getId()
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
    public function getUid()
    {
        return $this->getId();
    }

    /**
     * @param \DateTime $begin
     */
    public function setBegin($begin)
    {
        $this->begin = $begin;
    }

    /**
     * @return \DateTime
     */
    public function getBegin()
    {
        return $this->begin;
    }

    /**
     * @param \DateTime $end
     */
    public function setEnd($end)
    {
        $this->end = $end;
    }

    /**
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

}
