<?php

namespace TerryLucas2017\Pattern\Created\SimpleFactoryPattern;

/**
 * Created by PhpStorm.
 * User: Terry Lucas
 * Date: 2017/8/8
 * Time: 11:25
 */
class FactoryPattern
{
    public function exc()
    {
        $fp = new ProtocolFactory();

        $fp = $fp->getProtocol('http');

        $fp->connect();

        $fp = new ProtocolFactory();

        $fp = $fp->getProtocol('imap');

        $fp->connect();

        $fp = new ProtocolFactory();

        $fp = $fp->getProtocol('pop3');

        $fp->connect();

        $fp = new ProtocolFactory();

        $fp = $fp->getProtocol('');

        $fp->connect();
    }
}