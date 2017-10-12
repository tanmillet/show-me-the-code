<?php
/**
 * Created by PhpStorm.
 * Author Terry Lucas
 * Date 17.10.12
 * Time 14:59
 */

namespace TerryLucas2017\Pattern\Structural\Decorator;


/**
 * Class WuKong72Bian
 * Author Terry Lucas
 * @package TerryLucas2017\Pattern\Structural\Decorator
 */
class WuKong72Bian implements IWuKong
{

    /**
     * @author Terry Lucas
     * @var WuKong
     */
    private $wk;

    /**
     * @author Terry Lucas
     * WuKong72Bian constructor.
     * @param WuKong $wuKong
     */
    public function __construct(IWuKong $wuKong)
    {
        $this->wk = $wuKong;
    }

    /**
     * @author Terry Lucas
     * @return string
     */
    public function say(): string
    {
        return $this->wk->say();
    }
}