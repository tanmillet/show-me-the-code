<?php
/**
 * Created by PhpStorm.
 * User: Terry Lucas
 * Date: 2017/8/17
 * Time: 17:14
 */

namespace TerryLucas2017\Pattern\Structural\Adapter;


/**
 * User: Terry Lucas
 * Interface IEbook
 * @package TerryLucas2017\Pattern\Structural\Adapter
 */
interface IEbook
{
    /**
     * User: Terry Lucas
     * @return mixed
     */
    public function unlock();

    /**
     * User: Terry Lucas
     * @return mixed
     */
    public function nextPage();

    /**
     * User: Terry Lucas
     * @return int
     */
    public function getPage(): int;
}