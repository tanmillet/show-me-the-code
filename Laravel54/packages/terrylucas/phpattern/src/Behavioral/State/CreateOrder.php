<?php
/**
 * Created by PhpStorm.
 * Author Terry Lucas
 * Date 17.11.22
 * Time 10:36
 */

namespace TerryLucas2017\Pattern\Behavioral\State;

/**
 * Class CreateOrder
 * Author Terry Lucas
 * @package TerryLucas2017\Pattern\Behavioral\State
 */
class CreateOrder extends StateOrder
{
    /**
     * @author Terry Lucas
     * CreateOrder constructor.
     */
    public function __construct()
    {
        $this->setStatus('created');
    }

    /**
     * @author Terry Lucas
     */
    protected function done()
    {
        static::$state = new ShippingOrder();
    }
}