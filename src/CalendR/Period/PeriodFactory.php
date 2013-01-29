<?php
namespace CalendR\Period;

class PeriodFactory implements PeriodFactoryInterface
{
    /** @var int */
    private $weekFirstDay = Day::MONDAY;

    /** @var string */
    private $dayClass = 'CalendR\Period\Day';

    /** @var string */
    private $weekClass = 'CalendR\Period\Week';

    /** @var string */
    private $monthClass = 'CalendR\Period\Month';

    /** @var string */
    private $yearClass = 'CalendR\Period\Year';

    public function setOptions(array $options)
    {
        foreach ($options as $option=>$value){
            $this->setOption($option, $value);
        }
    }

    public function setOption($option, $value)
    {
        if (property_exists($this, $option)){
            if ('weekFirstDay' == $option && ($value < 0 || $value > 6)){
                    throw new Exception\NotAWeekday(
                        sprintf(
                            '"%s" is not a valid weekFirstDay. Days are between 0 (Sunday) and 6 (Friday)',
                            $value
                        ));
            }
            $this->$option = $value;
        }
    }

    public function getWeekFirstDay()
    {
        return $this->weekFirstDay;
    }

    public function create($name, \DateTime $start)
    {
        $propertyName = strtolower($name).'Class';
        return new $this->$propertyName($start, $this);
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        $array['weekFirstDay'] = $this->weekFirstDay;
        $array['dayClass'] = $this->dayClass;
        $array['weekClass'] = $this->weekClass;
        $array['monthClass'] = $this->monthClass;
        $array['yearClass'] = $this->yearClass;

        return $array;
    }

    /**
     * @param $option
     * @return mixed
     */
    public function getOption($option)
    {
        if(property_exists($this, $option)){
            return $this->$option;
        }
        return null;
    }
}
