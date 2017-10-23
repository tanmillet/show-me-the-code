<?php
/**
 * Created by PhpStorm.
 * Author Terry Lucas
 * Date 17.10.23
 * Time 11:14
 */

namespace TerryLucas2017\Pattern\Structural\Composite;


/**
 * Class InputElement
 * Author Terry Lucas
 * @package TerryLucas2017\Pattern\Structural\Composite
 */
class InputElement implements IRenderable
{
    /**
     * @author Terry Lucas
     * @return string
     */
    public function reader(): string
    {
        return '<input type="text" />';
    }
}