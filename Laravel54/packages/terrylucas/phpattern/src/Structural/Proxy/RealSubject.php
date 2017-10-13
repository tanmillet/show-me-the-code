<?php
/**
 * Created by PhpStorm.
 * Author Terry Lucas
 * Date 17.10.13
 * Time 15:23
 */

namespace TerryLucas2017\Pattern\Structural\Proxy;

/**
 * Class RealSubject
 * Author Terry Lucas
 * @package TerryLucas2017\Pattern\Structural\Proxy
 */
class RealSubject extends Subject
{

    /**
     * @author Terry Lucas
     * @return string
     */
    public function lucasRequest(): string
    {
        // TODO: Implement lucasRequest() method.
        return 'this is real subject';
    }
}