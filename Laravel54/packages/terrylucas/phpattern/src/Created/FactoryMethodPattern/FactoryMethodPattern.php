<?php

namespace TerryLucas2017\Pattern\Created\FactoryMethodPattern;

/**
 * Class FactoryMethodPattern
 * User: Terry Lucas
 * @package TerryLucas2017\Pattern\Created\FactoryMethodPattern
 */
class FactoryMethodPattern
{
    /**
     * User: Terry Lucas
     */
    public function exc()
    {
        $op = new GermanFactory();
        $bicycle = $op->create(FactoryMethod::CHEAP);
        print_r($bicycle);

        $mercedes = $op->create(FactoryMethod::FAST);
        $mercedes->setColor('red');
        print_r($mercedes);

        $op = new ItalianFactory();
        $bicycle = $op->create(FactoryMethod::CHEAP);
        print_r($bicycle);

        $mercedes = $op->create(FactoryMethod::FAST);
        $mercedes = $mercedes->setColor('green');
        print_r($mercedes);

    }
}