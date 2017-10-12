<?php
/**
 * Created by PhpStorm.
 * Author Terry Lucas
 * Date 17.10.12
 * Time 14:49
 */

namespace TerryLucas2017\Pattern\Structural\Decorator;

/**
 * Class Decorator
 * Author Terry Lucas
 * @package TerryLucas2017\Pattern\Structural\Decorator
 */
class Decorator implements Component
{
    /**
     * @author Terry Lucas
     * @var Component
     */
    private $componet;

    /**
     * @author Terry Lucas
     * Decorator constructor.
     * @param Component $component
     */
    public function __construct(Component $component)
    {
        $this->componet = $component;
    }

    /**
     * @author Terry Lucas
     */
    public function sampleOperation()
    {
        $this->componet->sampleOperation();
    }
}