<?php
/**
 * Created by PhpStorm.
 * Author Terry Lucas
 * Date 17.10.16
 * Time 13:55
 */

namespace TerryLucas2017\Pattern\Structural\Facade;

/**
 * Class SecurityFacade
 * Author Terry Lucas
 * @package TerryLucas2017\Pattern\Facade\SecurityFacade
 */
class SecurityFacade
{
    /**
     * @author Terry Lucas
     * @var Light
     */
    private $_light;
    /**
     * @author Terry Lucas
     * @var Alarm
     */
    private $_alarm;
    /**
     * @author Terry Lucas
     * @var Camera
     */
    private $_camera;
    /**
     * @author Terry Lucas
     * @var Sensor
     */
    private $_sensor;

    /**
     * @author Terry Lucas
     * SecurityFacade constructor.
     * @param Light $light
     * @param Alarm $alarm
     * @param Sensor $sensor
     * @param Camera $camera
     */
    public function __construct(Light $light, Alarm $alarm, Sensor $sensor, Camera $camera)
    {
        $this->_light = $light;
        $this->_alarm = $alarm;
        $this->_camera = $camera;
        $this->_sensor = $sensor;
    }

    /**
     * @author Terry Lucas
     */
    public function activate()
    {
        $this->_camera->turnOn();
        $this->_light->turnOn();
        $this->_sensor->activate();
        $this->_alarm->activate();
    }

    /**
     * @author Terry Lucas
     */
    public function disactivate()
    {
        $this->_camera->turnOff();
        $this->_light->turnOff();
        $this->_sensor->disactivate();
        $this->_alarm->disactivate();
    }
}