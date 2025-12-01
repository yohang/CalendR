CHANGELOG
=========

4.0.0
-----

 * Simplified date comparisons
 * Removed all internal usage of \DateTime in favor of \DateTimeImmutable
 * Added enum DayOfWeek
 * Renamed Indexed and Basic classes
 * Renamed Cache provider and made it use PSR-16 instead of doctrine/cache
 * Added full type and generic types (psalm based)
 * Made Factory optional again, non-week based period does not need it at all.
 * Simplified iterators via \IteratorAggregate
 * Moved CI to GitHub Actions & Dagger
 * Dropped support for PHP < 8.2
 * Dropped support for Twig < 3
 

2.2.0
-----

 * Added `CalendR\Bridge` namespace and deprecated `CalendR\Extension` (no BC-break)
 * Removing `CalendR` directories to use full PSR-4 structure
 * Introduced built-in Symfony Bundle

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
