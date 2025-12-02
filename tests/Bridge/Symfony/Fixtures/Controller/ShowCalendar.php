<?php

declare(strict_types=1);

namespace App\Controller;

use CalendR\Calendar;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final readonly class ShowCalendar
{
    public function __construct(
        private Calendar $calendar,
        private Environment $twig,
    ) {
    }

    public function __invoke(int $year, int $month): Response
    {
        $month = $this->calendar->getMonth($year, $month);
        $previousMonth = $month->getPrevious();
        $nextMonth = $month->getNext();

        return new Response(
            $this->twig->render(
                'calendar.html.twig',
                [
                    'month' => $month,
                    'previousMonth' => $previousMonth,
                    'nextMonth' => $nextMonth,
                ],
            ),
        );
    }
}
