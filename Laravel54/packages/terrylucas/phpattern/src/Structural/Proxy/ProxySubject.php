<?php
namespace TerryLucas2017\Pattern\Structural\Proxy;

/**
 * Created by PhpStorm.
 * Author Terry Lucas
 * Date 17.10.13
 * Time 15:21
 */
/**
 * Class ProxySubject
 * Author Terry Lucas
 * @package TerryLucas2017\Pattern\Structural\Proxy
 */
class ProxySubject extends Subject
{

    /**
     * @author Terry Lucas
     * @var RealSubject
     */
    private $realSubject;

    /**
     * @author Terry Lucas
     * ProxySubject constructor.
     * @param RealSubject $realSubject
     */
    public function __construct(RealSubject $realSubject)
    {
        $this->realSubject = $realSubject;
    }

    /**
     * @author Terry Lucas
     * @return string
     */
    public function lucasRequest(): string
    {
        $subject = '';

        //request before
        $subject .= $this->preRequest();

        $subject .= $this->realSubject->lucasRequest();

        //request after
        $subject .= $this->postRequest();

        return $subject;
    }

    /**
     * @author Terry Lucas
     * @return string
     */
    private function preRequest()
    {
        return ' this is preRequest! ';
    }

    /**
     * @author Terry Lucas
     * @return string
     */
    private function postRequest()
    {
        return ' this is postRequest! ';
    }
}