<?php
/**
 * Created by PhpStorm.
 * Author Terry Lucas
 * Date 17.11.22
 * Time 10:29
 */

namespace TerryLucas2017\Pattern\Behavioral\State;


/**
 * Class ShippingOrder
 * Author Terry Lucas
 * @package TerryLucas2017\Pattern\Behavioral\State
 */
class ShippingOrder extends StateOrder
{
    /**
     * @author Terry Lucas
     * ShippingOrder constructor.
     */
    public function __construct()
    {
        $this->setStates('shipping');
    }

    /**
     * @author Terry Lucas
     */
    protected function done()
    {
        $this->setStates('completed');
    }
}