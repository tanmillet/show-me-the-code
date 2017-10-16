<?php
/**
 * Created by PhpStorm.
 * Author Terry Lucas
 * Date 17.10.16
 * Time 14:17
 */

namespace TerryLucas2017\Pattern\Structural\Facade;


/**
 * Class Client
 * Author Terry Lucas
 * @package TerryLucas2017\Pattern\Facade\SecurityFacade
 */
class Client
{
    /**
     * @author Terry Lucas
     * @var SecurityFacade
     */
    private $_security;

    /**
     * @author Terry Lucas
     * Client constructor.
     * @param SecurityFacade $securityFacade
     */
    public function __construct(SecurityFacade $securityFacade)
    {
        $this->_security = $securityFacade;
    }

    /**
     * @author Terry Lucas
     */
    public function activate()
    {
        $this->_security->activate();
    }

    /**
     * @author Terry Lucas
     */
    public function disactivate()
    {
        $this->_security->disactivate();
    }
}