<?php
/**
 * Created by PhpStorm.
 * User: Terry Lucas
 * Date: 2017/8/8
 * Time: 14:06
 */

namespace TerryLucas2017\Pattern\Created\AbstractFactoryPattern;

/**
 * Class Red
 * User: Terry Lucas
 * @package TerryLucas2017\Pattern\Created\AbstractFactoryPattern
 */
class Green implements Color
{
    /**
     * User: Terry Lucas
     */
    public function fill()
    {
        echo "Color is Green" . PHP_EOL;
    }
}