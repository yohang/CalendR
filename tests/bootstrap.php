<?php

declare(strict_types=1);

use Symfony\Component\Filesystem\Filesystem;

require_once __DIR__.'/../vendor/autoload.php';

(new Filesystem())->remove(__DIR__.'/Bridge/Symfony/Fixtures/app/var');

require __DIR__.'/Bridge/Symfony/Fixtures/app/AppKernel.php';
