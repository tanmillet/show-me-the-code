<?php

namespace TerryLucas2017\Pattern\Created\AbstractFactoryPattern;

/**
 * Class ShapeFactory
 * User: Terry Lucas
 * @package TerryLucas2017\Pattern\Created\AbstractFactoryPattern
 */
class ShapeFactory extends AbstractFactory
{
    public function getColor(string $color): Color
    {
        // TODO: Implement getColor() method.
    }

    /**
     * User: Terry Lucas
     * @param string $shape
     * @return Shape
     */
    public function getShape(string $shape): Shape
    {
        if ($shape == null) {
            return null;
        }

        if ($shape == "rectangle") {
            return new Rectangle();
        } elseif ($shape == "square") {
            return new Square();
        }

        return null;
    }
}