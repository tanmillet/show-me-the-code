<?php
/**
 * Created by PhpStorm.
 * Author Terry Lucas
 * Date 17.10.12
 * Time 14:51
 */

namespace TerryLucas2017\Pattern\Structural\Decorator;

/**
 * Class ConcreteComponent
 * Author Terry Lucas
 * @package TerryLucas2017\Pattern\Structural\Decorator
 */
class ConcreteComponent implements Component
{

    /**
     * @author Terry Lucas
     * ConcreteComponent constructor.
     */
    public function __construct()
    {
    }

    /**
     * @author Terry Lucas
     */
    public function sampleOperation()
    {
        echo __CLASS__.__METHOD__.'<br/>';
    }
}