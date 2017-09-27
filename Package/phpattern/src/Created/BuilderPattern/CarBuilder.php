<?php
/**
 * Created by PhpStorm.
 * User: Terry Lucas
 * Date: 2017/8/11
 * Time: 10:48
 */

namespace TerryLucas2017\Pattern\Created\BuilderPattern;

use TerryLucas2017\Pattern\Created\BuilderPattern\Parts\Car;
use TerryLucas2017\Pattern\Created\BuilderPattern\Parts\Door;
use TerryLucas2017\Pattern\Created\BuilderPattern\Parts\Engine;
use TerryLucas2017\Pattern\Created\BuilderPattern\Parts\Vehicle;
use TerryLucas2017\Pattern\Created\BuilderPattern\Parts\Wheel;

/**
 * Class CarBuilder
 * User: Terry Lucas
 * @package TerryLucas2017\Pattern\Created\BuilderPattern
 */
class CarBuilder implements Builder
{
    /**
     * User: Terry Lucas
     * @var
     */
    private $car;

    /**
     * User: Terry Lucas
     */
    public function addDoor()
    {
        $this->car->setPart('rightDoor', new Door());
        $this->car->setPart('leftDoor', new Door());
        $this->car->setPart('trunkLid', new Door());
    }

    /**
     * User: Terry Lucas
     */
    public function addEngine()
    {
        $this->car->setPart('engine', new Engine());
    }

    /**
     * User: Terry Lucas
     */
    public function addWheel()
    {
        $this->car->setPart('wheelLF', new Wheel());
        $this->car->setPart('wheelRF', new Wheel());
        $this->car->setPart('wheelLR', new Wheel());
        $this->car->setPart('wheelRR', new Wheel());
    }

    /**
     * User: Terry Lucas
     */
    public function createVehicle()
    {
        $this->car = new Car();
    }

    /**
     * User: Terry Lucas
     * @return Vehicle
     */
    public function getVehicle(): Vehicle
    {
        return $this->car;
    }
}