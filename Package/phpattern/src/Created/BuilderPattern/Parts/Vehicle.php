<?php

namespace TerryLucas2017\Pattern\Created\BuilderPattern\Parts;

/**
 * Class Vehicle
 * User: Terry Lucas
 * @package TerryLucas2017\Pattern\Created\BuilderPattern\Parts
 */
abstract class Vehicle
{
    /**
     * User: Terry Lucas
     * Date: ${DATE}
     * @var array
     */
    private $data = [];

    /**
     * User: Terry Lucas
     * @param $key
     * @param $value
     */
    public function setPart($key, $value)
    {
        $this->data[$key] = $value;
    }

}