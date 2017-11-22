<?php
/**
 * Created by PhpStorm.
 * Author Terry Lucas
 * Date 17.11.22
 * Time 10:36
 */

namespace TerryLucas2017\Pattern\Behavioral\State;

/**
 * Class ContextOrder
 * Author Terry Lucas
 * @package TerryLucas2017\Pattern\Behavioral\State
 */
class ContextOrder extends StateOrder
{
    /**
     * @author Terry Lucas
     * @return StateOrder
     */
    public function getState(): StateOrder
    {
        return static::$state;
    }

    /**
     * @author Terry Lucas
     * @param StateOrder $state
     */
    public function setState(StateOrder $state)
    {
        static::$state = $state;
    }

    /**
     * @author Terry Lucas
     */
    public function done()
    {
        static::$state->done();
    }

    /**
     * @author Terry Lucas
     * @return string
     */
    public function getStatus(): string
    {
        return static::$state->getStatus();
    }
}