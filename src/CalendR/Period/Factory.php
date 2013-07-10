<?php

/*
 * This file is part of CalendR, a Fréquence web project.
 *
 * (c) 2013 Fréquence web
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CalendR\Period;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * The period factory.
 *
 * Contains static methods that instantiate periods from given arguments
 *
 * @author Yohan Giarelli <yohan@frequence-web.fr>
 */
class Factory
{
    /**
     * Creates and returns a new Day instance
     *
     * @param int|\DateTime $yearOrStart
     * @param int|array     $monthOrOptions
     * @param int           $day
     * @param array         $options
     *
     * @return \CalendR\Period\Day
     */
    public static function createDay($yearOrStart, $monthOrOptions = null, $day = null, array $options = array())
    {
        if (is_array($monthOrOptions)) {
            $options = $monthOrOptions;
        }

        if (!$yearOrStart instanceof \DateTime) {
            $yearOrStart = new \DateTime(sprintf('%s-%s-%s', $yearOrStart, $monthOrOptions, $day));
        }

        $className = self::extractOption($options, 'day_class');

        return new $className($yearOrStart, $options);
    }

    /**
     * Creates and returns a new week instance
     *
     * @param int|\DateTime $yearOrStart
     * @param int|array     $weekOrOptions
     * @param array         $options
     *
     * @return \CalendR\Period\Week
     */
    public static function createWeek($yearOrStart, $weekOrOptions = null, array $options = array())
    {
        if (is_array($weekOrOptions)) {
            $options = $weekOrOptions;
        }

        if (!$yearOrStart instanceof \DateTime) {
            $yearOrStart = new \DateTime(sprintf('%s-W%s', $yearOrStart, str_pad($weekOrOptions, 2, 0, STR_PAD_LEFT)));
        }

        $className = self::extractOption($options, 'week_class');

        return new $className($yearOrStart, $options);
    }

    /**
     * Creates and returns a new month
     *
     * @param int|\DateTime $yearOrStart
     * @param int|array     $monthOrOptions
     * @param array         $options
     *
     * @return \CalendR\Period\Month
     */
    public static function createMonth($yearOrStart, $monthOrOptions = null, array $options = array())
    {
        if (is_array($monthOrOptions)) {
            $options = $monthOrOptions;
        }

        if (!$yearOrStart instanceof \DateTime) {
            $yearOrStart = new \DateTime(sprintf('%s-%s-01', $yearOrStart, $monthOrOptions));
        }

        $className = self::extractOption($options, 'month_class');

        return new $className($yearOrStart, $options);
    }

    /**
     * Creates and returns a new year
     *
     * @param int|\DateTime $yearOrStart
     * @param array         $options
     *
     * @return \CalendR\Period\Year
     */
    public static function createYear($yearOrStart, array $options = array())
    {
        if (!$yearOrStart instanceof \DateTime) {
            $yearOrStart = new \DateTime(sprintf('%s-01-01', $yearOrStart));
        }

        $className = self::extractOption($options, 'year_class');

        return new $className($yearOrStart, $options);
    }

    /**
     * Creates and returns a new range
     *
     * @param int|\DateTime $begin
     * @param int|\DateTime $end
     * @param array         $options
     *
     * @return \CalendR\Period\Range
     */
    public static function createRange($begin, $end, array $options = array())
    {
        $className = self::extractOption($options, 'range_class');

        return new $className($begin, $end, $options);
    }

    /**
     * @param array $options
     *
     * @return array
     */
    public static function resolveOptions(array $options)
    {
        static $resolver = null;

        if (null === $resolver) {
            $resolver = new OptionsResolver;
            $resolver->setDefaults(
                array(
                    'day_class'     => 'CalendR\Period\Day',
                    'week_class'    => 'CalendR\Period\Week',
                    'month_class'   => 'CalendR\Period\Month',
                    'year_class'    => 'CalendR\Period\Year',
                    'range_class'   => 'CalendR\Period\Range',
                    'first_weekday' => Day::MONDAY
                )
            );
        }

        return $resolver->resolve($options);
    }

    /**
     * @param array  $options
     * @param string $option
     *
     * @return mixed
     */
    protected static function extractOption(array & $options, $option)
    {
        $options = static::resolveOptions($options);

        return $options[$option];
    }
}
