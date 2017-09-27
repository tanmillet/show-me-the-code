<?php

namespace TerryLucas2017\Pattern\Created\SimpleFactoryPattern;

/**
 * User: Terry Lucas
 * func: 设计一个连接服务器的框架，需要三个协议，"POP3"、"IMAP"、"HTTP"，可以把这三个作为产品类，共同实现一个接口
 * Interface IProtocol
 * @package TerryLucas2017\Pattern\Created
 */
interface IProtocol
{
    public function connect();
}