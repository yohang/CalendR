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
class Factory implements FactoryInterface
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
     * {@inheritdoc}
     */
    public function createSecond(\DateTime $begin)
    {
        return new $this->options['second_class']($begin, $this);
    }

    /**
     * {@inheritdoc}
     */
    public function createMinute(\DateTime $begin)
    {
        return new $this->options['minute_class']($begin, $this);
    }

    /**
     * {@inheritdoc}
     */
    public function createHour(\DateTime $begin)
    {
        return new $this->options['hour_class']($begin, $this);
    }

    /**
     * {@inheritdoc}
     */
    public function createDay(\DateTime $begin)
    {
        return new $this->options['day_class']($begin, $this);
    }

    /**
     * {@inheritdoc}
     */
    public function createWeek(\DateTime $begin)
    {
        return new $this->options['week_class']($begin, $this);
    }

    /**
     * {@inheritdoc}
     */
    public function createMonth(\DateTime $begin)
    {
        return new $this->options['month_class']($begin, $this);
    }

    /**
     * {@inheritdoc}
     */
    public function createYear(\DateTime $begin)
    {
        return new $this->options['year_class']($begin, $this);
    }

    /**
     * {@inheritdoc}
     */
    public function createRange(\DateTime $begin, \DateTime $end)
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
            $this->resolver = new OptionsResolver();
            $this->resolver->setDefaults(
                array(
                    'second_class' => 'CalendR\Period\Second',
                    'minute_class' => 'CalendR\Period\Minute',
                    'hour_class' => 'CalendR\Period\Hour',
                    'day_class' => 'CalendR\Period\Day',
                    'week_class' => 'CalendR\Period\Week',
                    'month_class' => 'CalendR\Period\Month',
                    'year_class' => 'CalendR\Period\Year',
                    'range_class' => 'CalendR\Period\Range',
                    'first_weekday' => Day::MONDAY,
                    'strict_dates' => false,
                )
            );
            $this->setDefaultOptions($this->resolver);
        }

        return $this->resolver->resolve($options);
    }

    /**
     * Override this method if you have to change default/allowed options.
     *
     * @param OptionsResolverInterface $resolver
     */
    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function setFirstWeekday($firstWeekday)
    {
        $this->setOption('first_weekday', $firstWeekday);
    }

    /**
     * {@inheritdoc}
     */
    public function getFirstWeekday()
    {
        return $this->getOption('first_weekday');
    }

    /**
     * {@inheritdoc}
     */
    public function findFirstDayOfWeek($dateTime)
    {
        $day = clone $dateTime;
        $delta = ((int) $day->format('w') - $this->getFirstWeekday() + 7) % 7;
        $day->sub(new \DateInterval(sprintf('P%sD', $delta)));

        return $day;
    }

    /**
     * {@inheritdoc}
     */
    public function getStrictDates()
    {
        return $this->getOption('strict_dates');
    }

    /**
     * {@inheritdoc}
     */
    public function setStrictDates($strict)
    {
        $this->setOption('strict_dates', (bool) $strict);
    }
}
