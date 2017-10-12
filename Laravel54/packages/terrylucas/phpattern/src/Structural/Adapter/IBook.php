<?php

namespace TerryLucas2017\Pattern\Structural\Adapter;

/**
 * Created by PhpStorm.
 * User: Terry Lucas
 * Date: 2017/8/17
 * Time: 17:11
 */
/**
 * User: Terry Lucas
 * Interface IBook
 * @package TerryLucas2017\Pattern\Structural\Adapter
 */
interface IBook
{
    /**
     * User: Terry Lucas
     * @return mixed
     */
    public function open();

    /**
     * User: Terry Lucas
     * @return mixed
     */
    public function turnPage();

    /**
     * User: Terry Lucas
     * @return int
     */
    public function getPage(): int;
}