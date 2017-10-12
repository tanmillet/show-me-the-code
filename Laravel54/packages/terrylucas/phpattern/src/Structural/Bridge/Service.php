<?php

namespace TerryLucas2017\Pattern\Structural\Bridge;


/**
 * Class Service
 * User: Terry Lucas
 * @package TerryLucas2017\Pattern\Structural\Bridge
 */
abstract class Service
{
    /**
     * User: Terry Lucas
     * Date: ${DATE}
     * @var IFormatter
     */
    protected $format;

    /**
     * @author: Terry Lucas
     * Service constructor.
     * @param IFormatter $formatter
     */
    public function __construct(IFormatter $formatter)
    {
        $this->format = $formatter;
    }

    /**
     * User: Terry Lucas
     * @param IFormatter $formatter
     */
    public function setFormater(IFormatter $formatter)
    {
        $this->format = $formatter;
    }

    /**
     * User: Terry Lucas
     * @return mixed
     */
    abstract public function get();
}