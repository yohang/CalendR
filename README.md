# CalendR

[![Build Status](https://travis-ci.org/yohang/CalendR.svg?branch=master)](https://travis-ci.org/yohang/CalendR)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/yohang/CalendR/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/yohang/CalendR/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/yohang/CalendR/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/yohang/CalendR/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/ac050bc0-c3b2-4d88-be63-059a0d968157/mini.png)](https://insight.sensiolabs.com/projects/ac050bc0-c3b2-4d88-be63-059a0d968157)

CalendR is an Object Oriented Calendar management library on top of PHP5.3+ Date objects.
You can use it to deal with all your needs about calendars and events.

Complete documentation
----------------------

Complete documentation is available [here](http://yohang.github.com/CalendR).

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
    require 'vendor/autoload.php';
```

Contribute
----------

CalendR is still in beta and all comments/PRs are welcome :)

License
-------

CalendR is licensed under the MIT License - see the LICENSE file for details

TODO (And / Or planned)
-----------------------

 * Renderers (WIP)
 * Add providers for some hosted services
 * Add Hour, Minute and Second periods (maybe, don't know if this is really useful).

[![githalytics.com alpha](https://cruel-carlota.pagodabox.com/6dcce0ab659d3a643298bb645fdb643f "githalytics.com")](http://githalytics.com/yohan/CalendR)
