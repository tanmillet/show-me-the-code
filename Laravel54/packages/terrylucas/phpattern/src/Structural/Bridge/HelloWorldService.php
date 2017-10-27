<?php

namespace TerryLucas2017\Pattern\Structural\Bridge;

/**
 * Class HelloWorldService
 * User: Terry Lucas
 * @package TerryLucas2017\Pattern\Structural\Bridge
 */
class HelloWorldService extends Service
{
    /**
     * User: Terry Lucas
     */
    public function get()
    {
        $this->format->format('Hello World!');
    }
    
}