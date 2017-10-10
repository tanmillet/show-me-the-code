<?php
/**
 * Created by PhpStorm.
 * Author Terry Lucas
 * Date 17.10.10
 * Time 17:50
 */

namespace TerryLucas2017\Pattern\Created\SingletonPattern;

/**
 * Class TestDemo
 * Author Terry Lucas
 * @package TerryLucas2017\Pattern\Created\SingletonPattern
 */
final class TestDemo
{
    /**
     * @author Terry Lucas
     * @var
     */
    private static $instance;

    /**
     * @author Terry Lucas
     * TestDemo constructor.
     */
    private function __construct()
    {
    }

    /**
     * @author Terry Lucas
     */
    private function __wakeup()
    {
        // TODO: Implement __wakeup() method.
    }

    /**
     * @author Terry Lucas
     */
    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    /**
     * @author Terry Lucas
     * @return TestDemo
     */
    static public function getInstance(): TestDemo
    {
        if (NULL === self::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }
}