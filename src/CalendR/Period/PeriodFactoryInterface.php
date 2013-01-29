<?php
namespace CalendR\Period;

interface PeriodFactoryInterface
{
    /**
     * @param $name
     * @param \DateTime $start
     */
    public function create($name, \DateTime $start);

    /**
     * @param array $options
     */
    public function setOptions(array $options);

    /**
     * @param $option
     * @param $value
     */
    public function setOption($option, $value);

    /**
     * @return array
     */
    public function getOptions();

    /**
     * @param $option
     * @return mixed
     */
    public function getOption($option);

}
