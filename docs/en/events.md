Using events and providers
==========================

CalendR can manage your events by adding them to the event manager, or by defining an event provider.

## Events

### How it works

You have to create an Event class that implements the CalendR\Event\EventInterface to use the event management.
You can use the built-in CalendR\Event\Event class, but in most of case you'd better to implement your own class.
You can also extend the CalendR\Event\Abstract event that provide base methods for your event class.

#### Define your event class

This class can be a Doctrine entity, for example.

```php

use CalendR\Event\AbstractEvent;

class Event extends AbstractEvent
{

    protected $begin;
    protected $end;
    protected $uid;

    public function __construct($uid, \DateTime $start, \DateTime $end)
    {
        $this->uid = $uid;
        $this->begin = clone $start;
        $this->end = clone $end;
    }

    function getUid()
    {
        return $this->uid;
    }

    public function getBegin()
    {
        return $this->begin;
    }

    public function getEnd()
    {
        return $this->end;
    }
}
```

#### Add your events to the manager

```php

$event = new Event('event-1', new \DateTime('2012-01-01'), new \DateTime('2012-01-03'));
$factory->getEventManager()->add($event);

```

#### Find your events

```php

$month = $factory->getMonth(2012, 01);
$events = $factory->getEvents($month);

```

## Providers

Events can come from providers, like a Doctrine Entity Repository

### How it works

You have to implements the CalendR\Event\Provider\ProviderInterface to make your class become provider.

#### Define your provider

```php

use CalendR\Event\Provider\ProviderInterface;

class Provider implements ProviderInterface
{
    public function find(\DateTime $start, \DateTime $end)
    {
        /*
            Your stuff, you have to return an event array here.
            In most of cases, it will be a database query.
        */
    }
}

```

#### Add your provider to the EventManager

```php

$factory->getEventManager()->setProvider(new Provider);

```

And that's all. You can now find your events from your provider via $factory->getEvents()

### Extra : Bundled Providers

CalendR comes with 2 providers : Cache and Aggregate

The Cache provider decorate your provider to make request only when necessary.
The Aggregate provider allows you to use multiple providers