<?php

namespace CalendR\Period;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * The period factory.
 *
 * Contains methods that instantiate periods from given arguments
 *
 * @author Yohan Giarelli <yohan@giarel.li>
 */
class Factory implements FactoryInterface
{
    protected array $options;

    protected ?OptionsResolver $resolver = null;

    public function __construct(array $options = [])
    {
        $this->options = $this->resolveOptions($options);
    }

    public function createSecond(\DateTimeInterface $begin): Second
    {
        return new $this->options['second_class']($begin, $this);
    }

    public function createMinute(\DateTimeInterface $begin): Minute
    {
        return new $this->options['minute_class']($begin, $this);
    }

    public function createHour(\DateTimeInterface $begin): Hour
    {
        return new $this->options['hour_class']($begin, $this);
    }

    public function createDay(\DateTimeInterface $begin): Day
    {
        return new $this->options['day_class']($begin, $this);
    }

    public function createWeek(\DateTimeInterface $begin): Week
    {
        return new $this->options['week_class']($begin, $this);
    }

    public function createMonth(\DateTimeInterface $begin): Month
    {
        return new $this->options['month_class']($begin, $this);
    }

    public function createYear(\DateTimeInterface $begin): Year
    {
        return new $this->options['year_class']($begin, $this);
    }

    public function createRange(\DateTimeInterface $begin, \DateTimeInterface $end): Range
    {
        return new $this->options['range_class']($begin, $end, $this);
    }

    public function setOption(string $name, $value): void
    {
        $this->resolver->clear();
        $this->resolver->setDefaults($this->options);

        $this->options = $this->resolver->resolve([$name => $value]);
    }

    public function getOption(string $name)
    {
        return $this->options[$name];
    }

    protected function resolveOptions(array $options): array
    {
        if (!$this->resolver instanceof OptionsResolver) {
            $this->resolver = new OptionsResolver();
            $this->resolver->setDefaults(
                [
                    'second_class'  => Second::class,
                    'minute_class'  => Minute::class,
                    'hour_class'    => Hour::class,
                    'day_class'     => Day::class,
                    'week_class'    => Week::class,
                    'month_class'   => Month::class,
                    'year_class'    => Year::class,
                    'range_class'   => Range::class,
                    'first_weekday' => Day::MONDAY,
                ]
            );
            $this->setDefaultOptions($this->resolver);
        }

        return $this->resolver->resolve($options);
    }

    /**
     * Override this method if you have to change default/allowed options.
     */
    protected function setDefaultOptions(OptionsResolver $resolver): void
    {
    }

    public function setFirstWeekday(int $firstWeekday): void
    {
        $this->setOption('first_weekday', $firstWeekday);
    }

    public function getFirstWeekday(): int
    {
        return $this->getOption('first_weekday');
    }

    public function findFirstDayOfWeek(\DateTimeInterface $dateTime): \DateTimeInterface
    {
        $day   = clone $dateTime;
        $delta = ((int)$day->format('w') - $this->getFirstWeekday() + 7) % 7;

        return $day->sub(new \DateInterval(sprintf('P%sD', $delta)));
    }
}
