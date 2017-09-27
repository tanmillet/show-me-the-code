<?php

namespace TerryLucas2017\Pattern\Created\PrototypePattern;

/**
 * Class BarPrototype
 * User: Terry Lucas
 * @package TerryLucas2017\Pattern\Created\PrototypePattern
 */
abstract class BookPrototype
{
    /**
     * User: Terry Lucas
     * Date: ${DATE}
     * @var
     */
    protected $title;

    /**
     * User: Terry Lucas
     * Date: ${DATE}
     * @var
     */
    protected $category;

    /**
     * @author: Terry Lucas
     * @return mixed
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @author: Terry Lucas
     * @param mixed $title
     * @return BarPrototype
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * User: Terry Lucas
     * @return mixed
     */
    abstract public function __clone();
}