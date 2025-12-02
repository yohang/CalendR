# CalendR

**A modern, object-oriented calendar management library for PHP 8.2+.**

CalendR provides a clean, immutable, and iterable API to manipulate time periods (Years, Months, Weeks, Days...) and manage associated events.

[![CI Status](https://github.com/yohang/CalendR/actions/workflows/ci.yml/badge.svg)](https://github.com/yohang/CalendR/actions/workflows/ci.yml)
[![Coverage Status](https://coveralls.io/repos/github/yohang/CalendR/badge.svg?branch=master)](https://coveralls.io/github/yohang/CalendR?branch=main)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fyohang%2FCalendR%2Fmain)](https://dashboard.stryker-mutator.io/reports/github.com/yohang/CalendR/main)

## ✨ Features

* **Object-Oriented Periods:** Manipulate `Year`, `Month`, `Week`, `Day` as objects, not strings or timestamps.
* **Fully Iterable:** Iterate over a Year to get Months, or a Month to get Days, using native `foreach` loops.
* **Immutable by Design:** Based on `DateTimeImmutable`, ensuring safe date manipulations.
* **Event Management:** Fetch and aggregate events from multiple sources (Doctrine, API, etc.) for any period.
* **Zero Dependencies:** The core library has no external dependencies.
* **Framework Integrations:** Includes a Symfony Bundle and Twig extensions.

## 📦 Installation

```bash
composer require yohang/calendr
```

## 🚀 Usage

### 1. Navigating Time

The Calendar class is your main entry point. It acts as a factory to create periods configured with your preferences (e.g., first day of the week).

```php
<?php

use CalendR\Calendar;

$calendar = new Calendar();

// Get a specific year
$year = $calendar->getYear(2025);

foreach ($year as $month) {
    echo $month->format('F Y') . "\n";
    
    // Iterate over days in that month
    foreach ($month as $day) {
        // ...
    }
}
```

### 2. Working with Periods

Every period object implements PeriodInterface, providing powerful methods:

```php
<?php

$month = $calendar->getMonth(2025, 1); // January 2025

// Check containment
if ($month->contains(new \DateTimeImmutable('2025-01-15'))) {
    echo "We are in the middle of the month!";
}

// Navigation
$nextMonth = $month->getNext(); // February 2025
$prevMonth = $month->getPrevious(); // December 2024

// DatePeriod compatibility
foreach ($month->getDatePeriod() as $date) {
    // $date is a DateTimeImmutable
}
```

### 3. Managing Events

CalendR can attach events to any period. You need to configure an EventManager with one or more ProviderInterface.

#### Implement your Event

```php
<?php

use CalendR\Event\AbstractEvent;

class MyEvent extends AbstractEvent
{
    public function __construct(
        private \DateTimeImmutable $begin,
        private \DateTimeImmutable $end
    ) {}

    public function getBegin(): \DateTimeInterface { return $this->begin; }
    public function getEnd(): \DateTimeInterface { return $this->end; }
    public function getUid(): string { return uniqid(); }
}
```

#### Create a Provider

```php
<?php

use CalendR\Event\Provider\ProviderInterface;

class MyProvider implements ProviderInterface
{
    public function getEvents(\DateTimeInterface $begin, \DateTimeInterface $end, array $options = []): array
    {
        // Query your database or API here using $begin and $end
        return [
            new MyEvent($begin, $end),
        ];
    }
}
```

#### Retrieve Events

```php
<?php

$manager = $calendar->getEventManager();
$manager->addProvider('my_source', new MyProvider());

$month = $calendar->getMonth(2025, 1);
$events = $manager->find($month);

foreach ($events as $event) {
    // ...
}
```

## 🧩 Symfony & Twig Integration

If you use Symfony, the bundle is automatically configured.

```yaml
# config/packages/calendr.yaml
calendr:
    periods:
        default_first_weekday: 1 # Monday
```

### Twig Functions

You can access periods directly in your templates:

```twig
{# Iterate over days of the current month #}
{% set month = calendr_month(2025, 1) %}

<table>
    {% for week in month %}
        <tr>
            {% for day in week %}
                <td>
                    {{ day.begin|date('d') }}
                    
                    {# Fetch events for this specific day #}
                    {% for event in calendr_events(day) %}
                        <span class="event">{{ event.uid }}</span>
                    {% endfor %}
                </td>
            {% endfor %}
        </tr>
    {% endfor %}
</table>
```
