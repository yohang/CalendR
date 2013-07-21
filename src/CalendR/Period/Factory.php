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
 * Contains methods that instantiate periods from given arguments
 *
 * @author Yohan Giarelli <yohan@frequence-web.fr>
 */
class Factory
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @var OptionsResolverInterface
     */
    protected $resolver;

    /**
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $this->options = $this->resolveOptions($options);
    }

    /**
     * Creates and returns a new Day instance
     *
     * @param int|\DateTime $yearOrStart
     * @param int|array     $month
     * @param int           $day
     *
     * @return \CalendR\Period\Day
     */
    public function createDay($yearOrStart, $month = null, $day = null)
    {
        if (!$yearOrStart instanceof \DateTime) {
            $yearOrStart = new \DateTime(sprintf('%s-%s-%s', $yearOrStart, $month, $day));
        }

        return new $this->options['day_class']($yearOrStart, $this);
    }

    /**
     * Creates and returns a new week instance
     *
     * @param int|\DateTime $yearOrStart
     * @param int|array     $week
     *
     * @return \CalendR\Period\Week
     */
    public function createWeek($yearOrStart, $week = null)
    {
        if (!$yearOrStart instanceof \DateTime) {
            $yearOrStart = new \DateTime(sprintf('%s-W%s', $yearOrStart, str_pad($week, 2, 0, STR_PAD_LEFT)));
        }

        return new $this->options['week_class']($yearOrStart, $this);
    }

    /**
     * Creates and returns a new month
     *
     * @param int|\DateTime $yearOrStart
     * @param int|array     $month
     *
     * @return \CalendR\Period\Month
     */
    public function createMonth($yearOrStart, $month = null)
    {
        if (!$yearOrStart instanceof \DateTime) {
            $yearOrStart = new \DateTime(sprintf('%s-%s-01', $yearOrStart, $month));
        }

        return new $this->options['month_class']($yearOrStart, $this);
    }

    /**
     * Creates and returns a new year
     *
     * @param int|\DateTime $yearOrStart
     * @param array         $options
     *
     * @return \CalendR\Period\Year
     */
    public function createYear($yearOrStart, array $options = array())
    {
        if (!$yearOrStart instanceof \DateTime) {
            $yearOrStart = new \DateTime(sprintf('%s-01-01', $yearOrStart));
        }

        return new $this->options['year_class']($yearOrStart, $this);
    }

    /**
     * Creates and returns a new range
     *
     * @param int|\DateTime $begin
     * @param int|\DateTime $end
     *
     * @return \CalendR\Period\Range
     */
    public function createRange($begin, $end)
    {
        return new $this->options['range_class']($begin, $end, $this);
    }

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function setOption($name, $value)
    {
        $this->resolver->replaceDefaults($this->options);
        $this->options = $this->resolver->resolve(array($name => $value));
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function getOption($name)
    {
        return $this->options[$name];
    }

    /**
     * @param array $options
     *
     * @return array
     */
    protected function resolveOptions(array $options)
    {
        if (null === $this->resolver) {
            $this->resolver = new OptionsResolver;
            $this->resolver->setDefaults(
                array(
                    'day_class'     => 'CalendR\Period\Day',
                    'week_class'    => 'CalendR\Period\Week',
                    'month_class'   => 'CalendR\Period\Month',
                    'year_class'    => 'CalendR\Period\Year',
                    'range_class'   => 'CalendR\Period\Range',
                    'first_weekday' => Day::MONDAY
                )
            );
            $this->setDefaultOptions($this->resolver);
        }

        return $this->resolver->resolve($options);
    }

    /**
     * Override this method if you have to change default/allowed options
     *
     * @param OptionsResolverInterface $resolver
     */
    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    }
}
