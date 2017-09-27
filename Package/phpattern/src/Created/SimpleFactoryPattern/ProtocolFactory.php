<?php

namespace TerryLucas2017\Pattern\Created\SimpleFactoryPattern;

/**
 * Class ProtocolFactory
 * User: Terry Lucas
 * @package TerryLucas2017\Pattern\Created
 */
class ProtocolFactory
{
    /**
     * User: Terry Lucas
     * @param string $protocol
     * @return IProtocol
     */
    static public function getProtocol(string $protocol): IProtocol
    {
        if (!isset($protocol)) {
            return new NullProtocol();
        }

        switch ($protocol) {
            case "http" :
                return new HttpProtocol();
                break;
            case "pop3" :
                return new Pop3Protocol();
                break;
            case "imap" :
                return new ImapProtocol();
                break;
            default:
                return new NullProtocol();
        }
    }
}