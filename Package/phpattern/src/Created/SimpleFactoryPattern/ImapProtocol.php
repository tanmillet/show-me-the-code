<?php

namespace TerryLucas2017\Pattern\Created\SimpleFactoryPattern;

/**
 * Class ImapProtocol
 * User: Terry Lucas
 * @package TerryLucas2017\Pattern\Created
 */
class ImapProtocol implements IProtocol
{

    /**
     * User: Terry Lucas
     * func: 实现imap连接
     */
    public function connect()
    {
        echo " imap connect " . PHP_EOL;
    }
}