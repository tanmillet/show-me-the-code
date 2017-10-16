<?php
/**
 * Created by PhpStorm.
 * Author Terry Lucas
 * Date 17.10.16
 * Time 14:05
 */

namespace TerryLucas2017\Pattern\Structural\Facade;


/**
 * Class Sensor
 * Author Terry Lucas
 * @package TerryLucas2017\Pattern\Facade\SecurityFacade
 */
class Sensor
{
    /**
     * @author Terry Lucas
     * Sensor constructor.
     */
    public function __construct()
    {
    }

    /**
     * @author Terry Lucas
     */
    public function activate()
    {
        echo 'Sensor activate';
    }

    /**
     * @author Terry Lucas
     */
    public function disactivate()
    {
        echo 'Sensor disactivate';
    }

    /**
     * @author Terry Lucas
     */
    public function trigger()
    {
        echo 'Sensor trigger';
    }
}