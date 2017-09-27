<?php

namespace TerryLucas2017\Pattern\Created\SingletonPattern;

/**
 * Class Singleton
 * User: Terry Lucas
 * @package TerryLucas2017\Pattern\Created\SingletonPattern
 */
final class Singleton
{
    /**
     * User: Terry Lucas
     * Date: ${DATE}
     * @var
     */
    private static $instance;

    /**
     * User: Terry Lucas
     * @return Singleton
     * 懒汉式
     */
    static public function getInstance(): Singleton
    {
        if (null === self::$instance) {
            static::$instance = new  static();
        }

        return static::$instance;

    }

    /**
     * User: Terry Lucas
     * 反序列化对象时被调用
     */
    private function __wakeup()
    {
        // TODO: Implement __wakeup() method.
    }

    /**
     * User: Terry Lucas
     * 使用 clone 关键字作对象复制时被调用
     */
    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    /**
     * @author: Terry Lucas
     * Singleton constructor.
     * 构造函数，对象初始化时被调用
     */
    private function __construct()
    {
    }
}