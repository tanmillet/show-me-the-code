<?php
/**
 * Created by PhpStorm.
 * User: Terry Lucas
 * Date: 2017/8/8
 * Time: 11:36
 */

namespace TerryLucas2017\Pattern\Created\SimpleFactoryPattern;


class NullProtocol implements IProtocol
{

    /**
     * User: Terry Lucas
     * func: 实现imap连接
     */
    public function connect()
    {
        echo " null connect " . PHP_EOL;
    }
}