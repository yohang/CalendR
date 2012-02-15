# CalendR ![project status](http://stillmaintained.com/frequence-web/CalendR.png) [![Build Status](https://secure.travis-ci.org/yohang/CalendR.png?branch=master)](http://travis-ci.org/yohang/CalendR) #

CalendR is an Object Oriented Calendar management library on top of PHP5.3+ Date objects.
You can use it to deal with all your needs about calendars and events.

Basic Usage
-----------

```php
    <?php

    use CalendR\Calendar;

    // Use the factory to get your period
    $factory = new Calendar;
    $month = $factory->getMonth(2012, 01);

    ?>

    <table>
        <?php // Iterate over your month and get weeks ?>
        <?php foreach ($month as $week): ?>
            <tr>
                <?php // Iterate over your month and get days ?>
                <?php foreach ($week as $day): ?>

                    <?php //Check days that are out of your month ?>
                    <td<?php $month->contains($day->getBegin()) or print ' style="color: grey;"' ?>>
                        <?php echo $day->getBegin()->format('d') ?>
                    </td>

                <?php endforeach ?>
            </tr>
        <?php endforeach ?>
    </table>

```

You can find more documentation in the docs directory.

Installation
------------

CalendR is hosted on [packagist](http://packagist.org), you can install it with composer.

Create a composer.json file

```json
    {
        "require": {
            "yohang/calendr": "1.*"
        }
    }
```

Install composer and run it

```sh
    wget http://getcomposer.org/composer.phar
    php composer.phar install
```

(Optional) Autoload CalendR

```php
    require 'vendor/.composer/autoload.php';
```

Integration
-----------

Symfony2 : [FrequenceWebCalendRBundle](https://github.com/frequence-web/FrequenceWebCalendRBundle)

Contribute
----------

CalendR is still in beta and all comments/PRs are welcome :)

TODO (And / Or planned)
-----------------------

 * Renderers
 * Integration for current frameworks (Symfony2 bundle already in development)
 * Add providers for some hosted services
