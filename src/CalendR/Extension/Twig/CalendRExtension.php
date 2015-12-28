<?php

namespace CalendR\Extension\Twig;

use CalendR\Calendar;

class CalendRExtension extends \Twig_Extension
{
    /**
     * @var Calendar
     */
    protected $factory;

    /**
     * @param Calendar $factory
     */
    public function __construct(Calendar $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @return array<\Twig_Function>
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('calendr_year', [$this, 'getYear']),
            new \Twig_SimpleFunction('calendr_month', [$this, 'getMonth']),
            new \Twig_SimpleFunction('calendr_week', [$this, 'getWeek']),
            new \Twig_SimpleFunction('calendr_day', [$this, 'getDay']),
            new \Twig_SimpleFunction('calendr_events', [$this, 'getEvents']),
        ];
    }

    /**
     * @return mixed
     */
    public function getYear()
    {
        return call_user_func_array([$this->factory, 'getYear'], func_get_args());
    }

    /**
     * @return mixed
     */
    public function getMonth()
    {
        return call_user_func_array([$this->factory, 'getMonth'], func_get_args());
    }

    /**
     * @return mixed
     */
    public function getWeek()
    {
        return call_user_func_array([$this->factory, 'getWeek'], func_get_args());
    }

    /**
     * @return mixed
     */
    public function getDay()
    {
        return call_user_func_array([$this->factory, 'getDay'], func_get_args());
    }

    /**
     * @return mixed
     */
    public function getEvents()
    {
        return call_user_func_array([$this->factory, 'getEvents'], func_get_args());
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'calendr';
    }
}
