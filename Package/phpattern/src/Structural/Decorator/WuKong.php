<?php
/**
 * Created by PhpStorm.
 * Author Terry Lucas
 * Date 17.10.12
 * Time 15:02
 */

namespace TerryLucas2017\Pattern\Structural\Decorator;


/**
 * Class WuKong
 * Author Terry Lucas
 * @package TerryLucas2017\Pattern\Structural\Decorator
 */
class WuKong implements IWuKong
{

    /**
     * @author Terry Lucas
     * WuKong constructor.
     */
    public function __construct()
    {

    }

    /**
     * @author Terry Lucas
     * @return string
     */
    public function say(): string
    {
        return 'wukong';
    }
}