<?php

namespace TerryLucas2017\Pattern\Created\BuilderPattern;

use TerryLucas2017\Pattern\Created\BuilderPattern\Parts\Vehicle;

/**
 * Class Director
 * User: Terry Lucas
 * @package TerryLucas2017\Pattern\Created\BuilderPattern
 */
class Director
{
    /**
     * User: Terry Lucas
     * @param Builder $builder
     * @return Vehicle
     */
    public function build(Builder $builder): Vehicle
    {
        $builder->createVehicle();

        $builder->addDoor();

        $builder->addEngine();

        $builder->addWheel();

        $builder->getVehicle();
    }
}