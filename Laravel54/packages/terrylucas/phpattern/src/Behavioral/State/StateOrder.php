<?php
namespace TerryLucas2017\Pattern\Behavioral\State;

/**
 * Created by PhpStorm.
 * Author Terry Lucas
 * Date 17.11.22
 * Time 10:11
 */
/**
 * Class StateOrder
 * Author Terry Lucas
 * @package TerryLucas2017\Pattern\Behavioral\State
 */
abstract class StateOrder
{

    /**
     * @author Terry Lucas
     * @var
     */
    private $details;

    /**
     * @author Terry Lucas
     * @var
     */
    protected static $state;

    /**
     * @author Terry Lucas
     * @return mixed
     */
    abstract protected function done();

    /**
     * @author Terry Lucas
     * @param string $state
     */
    protected function setStates(string $state)
    {
        $this->details['status'] = $state;
        $this->details['updatedTime'] = time();
    }

    /**
     * @author Terry Lucas
     * @return mixed
     */
    protected function getStatus()
    {
        return $this->details['status'];
    }

}