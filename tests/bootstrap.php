<?php

require_once __DIR__.'/../vendor/autoload.php';

Doctrine\Common\Annotations\AnnotationRegistry::registerFile(
    __DIR__.'/../vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php'
);

$classLoader = new \Composer\Autoload\ClassLoader();
$classLoader->add('CalendR\\Test', __DIR__);
$classLoader->register(true);
