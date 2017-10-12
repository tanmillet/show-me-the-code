<?php
/**
 * Created by PhpStorm.
 * User: Terry Lucas
 * Date: 2017/8/8
 * Time: 15:30
 */

namespace TerryLucas2017\Pattern\Created\FactoryMethodPattern;

/**
 * Class GermanFactory
 * User: Terry Lucas
 * @package TerryLucas2017\Pattern\Created\FactoryMethodPattern
 */
class ItalianFactory extends FactoryMethod
{
    /**
     * User: Terry Lucas
     * @param string $type
     * @return Vehicle
     */
    public function createVehicle(string $type): Vehicle
    {
        switch ($type) {
            case parent::CHEAP:
                return new Bicycle();
            case parent::FAST:
               return new CarFerrari();
            default:
                throw new \InvalidArgumentException("$type is not a valid vehicle");
        }
    }
}