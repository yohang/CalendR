<?php

declare(strict_types=1);

namespace App\Controller;

use CalendR\Calendar;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Template('calendar.html.twig')]
#[Route('/calendar/{year}/{month}', name: 'show_calendar', requirements: ['year' => '\d{4}', 'month' => '\d{1,2}'])]
final readonly class ShowCalendar
{
    public function __construct(
        private Calendar $calendar,
    ) {
    }

    public function __invoke(int $year, int $month): array
    {
        $month = $this->calendar->getMonth($year, $month);
        $previousMonth = $month->getPrevious();
        $nextMonth = $month->getNext();

        return [
            'month' => $month,
            'previousMonth' => $previousMonth,
            'nextMonth' => $nextMonth,
        ];
    }
}
