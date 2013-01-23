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
        return array(
            'calendr_year'   => new \Twig_Function_Method($this, 'getYear'),
            'calendr_month'  => new \Twig_Function_Method($this, 'getMonth'),
            'calendr_week'   => new \Twig_Function_Method($this, 'getWeek'),
            'calendr_day'    => new \Twig_Function_Method($this, 'getDay'),
            'calendr_events' => new \Twig_Function_Method($this, 'getEvents'),
        );
    }

    /**
     * @return mixed
     */
    public function getYear()
    {
        return call_user_func_array(array($this->factory, 'getYear'), func_get_args());
    }

    /**
     * @return mixed
     */
    public function getMonth()
    {
        return call_user_func_array(array($this->factory, 'getMonth'), func_get_args());
    }

    /**
     * @return mixed
     */
    public function getWeek()
    {
        return call_user_func_array(array($this->factory, 'getWeek'), func_get_args());
    }

    /**
     * @return mixed
     */
    public function getDay()
    {
        return call_user_func_array(array($this->factory, 'getDay'), func_get_args());
    }

    /**
     * @return mixed
     */
    public function getEvents()
    {
        return call_user_func_array(array($this->factory, 'getEvents'), func_get_args());
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'calendr';
    }
}
