<?php

namespace TerryLucas2017\Pattern\Created\FactoryMethodPattern;


/**
 * Class FactoryMethod
 * User: Terry Lucas
 * @package TerryLucas2017\Pattern\Created\FactoryMethodPattern
 */
abstract class FactoryMethod
{
    const CHEAP = 'cheap';
    const FAST = 'fast';

    /**
     * User: Terry Lucas
     * @param string $type
     * @return Vehicle
     */
    abstract protected function createVehicle(string $type): Vehicle;

    /**
     * User: Terry Lucas
     * @param string $type
     * @return Vehicle
     */
    public function create(string $type): Vehicle
    {
        $obj = $this->createVehicle($type);
        $obj->setColor('black');

        return $obj;
    }
}