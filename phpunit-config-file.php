<?php

use PHPUnit\Runner\Version;

require_once __DIR__ . '/vendor/autoload.php';

if (Version::series() >= 10) {
    echo 'phpunit-10.xml.dist';

    return;
}

echo 'phpunit.xml.dist';