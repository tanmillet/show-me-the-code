<?php

namespace TerryLucas2017\Pattern\Created\AbstractFactoryPattern;

/**
 * Class FactoryProducer
 * User: Terry Lucas
 * @package TerryLucas2017\Pattern\Created\AbstractFactoryPattern
 */
class FactoryProducer
{
    /**
     * User: Terry Lucas
     * @param string $choice
     * @return AbstractFactory
     */
    public function getFactory(string $choice): AbstractFactory
    {
        if ($choice == 'shape') {
            return new ShapeFactory();
        } elseif ($choice == 'color') {
            return new ColorFactory();
        }

        return null;
    }
}