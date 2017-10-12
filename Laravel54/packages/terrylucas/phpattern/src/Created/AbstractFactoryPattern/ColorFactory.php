<?php

namespace TerryLucas2017\Pattern\Created\AbstractFactoryPattern;

/**
 * Class ShapeFactory
 * User: Terry Lucas
 * @package TerryLucas2017\Pattern\Created\AbstractFactoryPattern
 */
class ColorFactory extends AbstractFactory
{
    /**
     * User: Terry Lucas
     * @param string $color
     * @return Color
     */
    public function getColor(string $color): Color
    {
        if ($color == null) {
            return null;
        }

        if ($color == "red") {
            return new Red();
        } elseif ($color == "green") {
            return new Green();
        }

        return null;
    }

    public function getShape(string $shape): Shape
    {
        // TODO: Implement getShape() method.
    }
}