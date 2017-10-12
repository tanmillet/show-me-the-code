<?php
/**
 * Created by PhpStorm.
 * User: Terry Lucas
 * Date: 2017/8/8
 * Time: 11:27
 */

namespace TerryLucas2017\Pattern\Created\SimpleFactoryPattern;

/**
 * Class HttpProtocol
 * User: Terry Lucas
 * @package TerryLucas2017\Pattern\Created
 */
class HttpProtocol implements IProtocol
{
    /**
     * User: Terry Lucas
     * func: 实现http连接
     */
    public function connect()
    {
        echo " http connect " . PHP_EOL;
    }

}