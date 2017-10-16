<?php
/**
 * Created by PhpStorm.
 * Author Terry Lucas
 * Date 17.10.16
 * Time 13:57
 */

namespace TerryLucas2017\Pattern\Structural\Facade;

/**
 * Class Camera
 * Author Terry Lucas
 * @package TerryLucas2017\Pattern\Facade\SecurityFacade
 */
/**
 * Class Camera
 * Author Terry Lucas
 * @package TerryLucas2017\Pattern\Facade\SecurityFacade
 */
class Camera
{
    /**
     * @author Terry Lucas
     * Camera constructor.
     */
    public function __construct()
    {
    }

    /**
     * @author Terry Lucas
     */
    public function turnOn()
    {
        echo 'Camera turnon';
    }

    /**
     * @author Terry Lucas
     */
    public function turnOff()
    {
        echo 'Camera turnoff';
    }

    /**
     * @author Terry Lucas
     */
    public function rotate()
    {
        echo 'Camera rotate';
    }
}