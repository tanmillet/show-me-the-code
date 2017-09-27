<?php
/**
 * Created by PhpStorm.
 * User: Terry Lucas
 * Date: 2017/8/8
 * Time: 14:04
 */

namespace TerryLucas2017\Pattern\Created\AbstractFactoryPattern;


class Rectangle implements Shape
{
    /**
     * User: Terry Lucas
     */
    public function draw()
    {
        echo "Shape is Rectangle" . PHP_EOL;
    }
}