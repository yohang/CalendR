# CalendR ![project status](http://stillmaintained.com/frequence-web/CalendR.png) [![Build Status](https://secure.travis-ci.org/yohang/CalendR.png?branch=master)](http://travis-ci.org/yohang/CalendR) #

Object Oriented Calendar management on top of PHP5.3+ Date objects

Basic Usage
-----------

```php
<?php

use CalendR\Factory;

// Use the factory to get your period
$factory = new Factory;
$month = $factory->getMonth(2012, 01);

?>

<table>
    <?php // Iterate over your month and get weeks ?>
    <?php foreach ($month as $week): ?>
        <tr>
            <?php // Iterate over your month and get days ?>
            <?php foreach ($week as $day): ?>

                <?php //Check days that are out of your month ?>
                <td<?php $month->contains($day->getDate()) or print ' style="color: grey;"' ?>>
                    <?php echo $day->getDate()->format('d') ?>
                </td>

            <?php endforeach ?>
        </tr>
    <?php endforeach ?>
</table>

```

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
``
