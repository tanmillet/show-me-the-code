<?php
/**
 * Created by PhpStorm.
 * Author Terry Lucas
 * Date 17.10.16
 * Time 14:07
 */

namespace TerryLucas2017\Pattern\Structural\Facade;


class Alarm
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
        echo 'Alarm activate';
    }

    /**
     * @author Terry Lucas
     */
    public function disactivate()
    {
        echo 'Alarm disactivate';
    }

    /**
     * @author Terry Lucas
     */
    public function ring()
    {
        echo 'Alarm ring';
    }

    public function stopRing()
    {
        echo 'Alarm stopring';
    }
}