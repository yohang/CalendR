# CalendR ![project status](http://stillmaintained.com/frequence-web/CalendR.png) [![Build Status](https://secure.travis-ci.org/yohang/CalendR.png?branch=master)](http://travis-ci.org/yohang/CalendR) #

Object Oriented Calendar management on top of PHP5.3+ Date objects

Basic Usage
-----------

```php
<?php

use CalendR\Factory;

$factory = new Factory;
$month = $factory->getMonth(2012, 01);

?>

<table>
    <?php foreach ($month as $week): ?>
        <tr>
            <?php foreach ($week as $day): ?>
                <td<?php $month->contains($day->getDate()) or print ' style="color: grey;"' ?>>
                    <?php echo $day->getDate()->format('d') ?>
                </td>
            <?php endforeach ?>
        </tr>
    <?php endforeach ?>
</table>