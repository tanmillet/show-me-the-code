<?php
/**
 * Created by PhpStorm.
 * User: Terry Lucas
 * Date: 2017/8/8
 * Time: 13:53
 */

namespace TerryLucas2017\Pattern\Created\AbstractFactoryPattern;


/**
 * Class AbstractFactoryPattern
 * User: Terry Lucas
 * @package TerryLucas2017\Pattern\Created\AbstractFactoryPattern
 */
class AbstractFactoryPattern
{
    /**
     * User: Terry Lucas
     */
    public function exc()
    {
        $fp = new FactoryProducer();
        $shape = $fp->getFactory('shape');
        $color = $fp->getFactory('color');

        $rectangle = $shape->getShape('rectangle');
        $rectangle->draw();

        $square = $shape->getShape('square');
        $square->draw();

        $red = $color->getColor('red');
        $red->fill();

        $green = $color->getColor('green');
        $green->fill();

    }
}