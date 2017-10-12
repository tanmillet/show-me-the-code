<?php
namespace TerryLucas2017\Pattern\Created\FactoryMethodPattern;

/**
 * Class Bicycle
 * User: Terry Lucas
 * @package TerryLucas2017\Pattern\Created\FactoryMethodPattern
 */
/**
 * Class Bicycle
 * User: Terry Lucas
 * @package TerryLucas2017\Pattern\Created\FactoryMethodPattern
 */
class Bicycle implements Vehicle
{
    /**
     * User: Terry Lucas
     * Date: ${DATE}
     * @var
     */
    private $color;

    /**
     * User: Terry Lucas
     * @param string $color
     */
    public function setColor(string $color)
    {
        $this->color = $color;
    }
}