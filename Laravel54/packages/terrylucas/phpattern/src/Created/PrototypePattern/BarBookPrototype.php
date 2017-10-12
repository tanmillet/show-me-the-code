<?php

namespace TerryLucas2017\Pattern\Created\PrototypePattern;

/**
 * Class BarBookPrototype
 * User: Terry Lucas
 * @package TerryLucas2017\Pattern\Created\PrototypePattern
 */
class BarBookPrototype extends BookPrototype
{
    /**
     * User: Terry Lucas
     * Date: ${DATE}
     * @var string
     */
    protected $category = 'Bar';

    /**
     * User: Terry Lucas
     */
    public function __clone()
    {
    }
}