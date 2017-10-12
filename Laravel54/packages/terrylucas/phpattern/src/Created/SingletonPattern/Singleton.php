<?php

namespace TerryLucas2017\Pattern\Created\SingletonPattern;

/**
 * Class Singleton
 * Author Terry Lucas
 * @package TerryLucas2017\Pattern\Created\SingletonPattern
 */
final class Singleton
{
    /**
     * @author Terry Lucas
     * @var
     */
    private static $instance;

    /**
     * @author Terry Lucas
     * @return Singleton
     */
    static public function getInstance(): Singleton
    {
        if (NULL === self::$instance) {
            static::$instance = new  static();
        }

        return static::$instance;
    }

    /**
     * 反序列化对象时被调用
     * @author Terry Lucas
     */
    private function __wakeup()
    {
        // TODO: Implement __wakeup() method.
    }

    /**
     * 使用 clone 关键字作对象复制时被调用
     * @author Terry Lucas
     */
    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    /**
     * 构造函数，对象初始化时被调用
     * @author Terry Lucas
     * Singleton constructor.
     */
    private function __construct()
    {
    }
}