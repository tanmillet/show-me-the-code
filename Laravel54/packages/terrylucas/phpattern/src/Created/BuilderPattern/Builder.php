<?php

namespace TerryLucas2017\Pattern\Created\BuilderPattern;

use TerryLucas2017\Pattern\Created\BuilderPattern\Parts\Vehicle;

/**
 * User: Terry Lucas
 * Interface Builder
 * @package TerryLucas2017\Pattern\Created\BuilderPattern
 */
interface Builder
{
    /**
     * User: Terry Lucas
     * @return mixed
     */
    public function createVehicle();

    /**
     * User: Terry Lucas
     * @return mixed
     */
    public function addWheel();

    /**
     * User: Terry Lucas
     * @return mixed
     */
    public function addDoor();

    /**
     * User: Terry Lucas
     * @return mixed
     */
    public function addEngine();

    /**
     * User: Terry Lucas
     * @return Vehicle
     */
    public function getVehicle(): Vehicle;
}