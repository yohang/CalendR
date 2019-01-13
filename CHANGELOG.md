CHANGELOG
=========

2.1.2
-----

 * Made compatible with Symfony 4 / Twig 2
 * Deprecated Silex Service provider 
 * Fixed a bug in events
 * Removed custom test bootstrap and migrated to PSR-4

2.1.0
-----
 * Made compatible with PHP 7.1+
 * Updated to PHPUnit >= 4

2.0.0
-----

 * Non-strict mode does not exists anymore (use the factory / calendar to create periods)
 * No more google calendar provider (hard to maintain and a bit out of the scope of this library)
 * Removed first-monday / last-sunday in favor of first weekday / last weekday
 * Factory argument is now mandatory for period instantiation
