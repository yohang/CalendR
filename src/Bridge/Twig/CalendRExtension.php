<?php

namespace CalendR\Bridge\Twig;

use CalendR\Calendar;
use CalendR\Event\Collection\CollectionInterface;
use CalendR\Event\EventInterface;
use CalendR\Period\Day;
use CalendR\Period\Month;
use CalendR\Period\PeriodInterface;
use CalendR\Period\Week;
use CalendR\Period\Year;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Extension for using periods and events from Twig
 *
 * @author Yohan Giarelli <yohan@giarel.li>
 */
class CalendRExtension extends AbstractExtension
{
    protected Calendar $factory;

    public function __construct(Calendar $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @return array<TwigFunction>
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('calendr_year', [$this, 'getYear']),
            new TwigFunction('calendr_month', [$this, 'getMonth']),
            new TwigFunction('calendr_week', [$this, 'getWeek']),
            new TwigFunction('calendr_day', [$this, 'getDay']),
            new TwigFunction('calendr_events', [$this, 'getEvents']),
        ];
    }

    public function getYear($yearOrStart): Year
    {
        return $this->factory->getYear($yearOrStart);
    }

    public function getMonth($yearOrStart, ?int $month = null): Month
    {
        return $this->factory->getMonth($yearOrStart, $month);
    }

    public function getWeek($yearOrStart, ?int $week = null): Week
    {
        return $this->factory->getWeek($yearOrStart, $week);
    }

    public function getDay($yearOrStart, ?int $month = null, ?int $day = null): Day
    {
        return $this->factory->getDay($yearOrStart, $month, $day);
    }

    public function getEvents(PeriodInterface $period, array $options = []): CollectionInterface
    {
        return $this->factory->getEvents($period, $options);
    }

    public function getName(): string
    {
        return self::class;
    }
}
