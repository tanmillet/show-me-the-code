<?php
/**
 * Created by PhpStorm.
 * Author Terry Lucas
 * Date 17.10.16
 * Time 14:02
 */

namespace TerryLucas2017\Pattern\Structural\Facade;


/**
 * Class Light
 * Author Terry Lucas
 * @package TerryLucas2017\Pattern\Facade\SecurityFacade
 */
class Light
{
    /**
     * @author Terry Lucas
     * Light constructor.
     */
    public function __construct()
    {
    }

    /**
     * @author Terry Lucas
     */
    public function turnOn()
    {
        echo 'Light turnon';
    }

    /**
     * @author Terry Lucas
     */
    public function turnOff()
    {
        echo 'Light turnoff';
    }

    /**
     * @author Terry Lucas
     */
    public function changeBulb()
    {
        echo 'Light changebulb';
    }
}