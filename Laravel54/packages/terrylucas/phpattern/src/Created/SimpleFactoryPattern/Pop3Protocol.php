<?php
/**
 * Created by PhpStorm.
 * User: Terry Lucas
 * Date: 2017/8/8
 * Time: 11:28
 */

namespace TerryLucas2017\Pattern\Created\SimpleFactoryPattern;


class Pop3Protocol implements IProtocol
{
    /**
     * User: Terry Lucas
     * func: 实现pop3连接
     */
    public function connect()
    {
        echo " pop3 connect " . PHP_EOL;
    }

}