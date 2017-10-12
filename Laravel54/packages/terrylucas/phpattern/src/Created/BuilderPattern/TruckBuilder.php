<?php

namespace TerryLucas2017\Pattern\Created\BuilderPattern;

use TerryLucas2017\Pattern\Created\BuilderPattern\Parts\Truck;
use TerryLucas2017\Pattern\Created\BuilderPattern\Parts\Vehicle;

/**
 * Class TruckBuilder
 * User: Terry Lucas
 * @package TerryLucas2017\Pattern\Created\BuilderPattern
 */
class TruckBuilder implements Builder
{
    /**
     * User: Terry Lucas
     * Date: ${DATE}
     * @var
     */
    private $truck;

    /**
     * User: Terry Lucas
     */
    public function addDoor()
    {
    }

    /**
     * User: Terry Lucas
     */
    public function addEngine()
    {
    }

    /**
     * User: Terry Lucas
     */
    public function addWheel()
    {
    }

    /**
     * User: Terry Lucas
     */
    public function createVehicle()
    {
        $this->truck = new Truck();
    }

    /**
     * User: Terry Lucas
     * @return Vehicle
     */
    public function getVehicle(): Vehicle
    {
        return $this->truck;
    }
}