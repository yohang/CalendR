Twig Extension
==============

Calendr provides a Twig extension.

You must instantiate it by injecting the CalendR factory

```php

$factory   = new CalendR\Calendar;
$extension = new CalendR\Extension\Twig\CalendRExtension($factory);

```

Provided functions
------------------

 * `calendr_year()`   : return an instance of year
 * `calendr_month()`  : return an instance of month
 * `calendr_week()`   : return an instance of week
 * `calendr_day()`    : return an instance of day
 * `calendr_events()` : return events for a period

 All these functions take the same arguments that the equivalent factory method
