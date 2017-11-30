<?php
/**
 * Created by PhpStorm.
 * Author Terry Lucas
 * Date 17.11.30
 * Time 10:47
 */
require_once "user.class.php";

/**
 * @author Terry Lucas
 * @param $name
 * @param $email
 * @return user
 */
function make_user($name, $email)
{
    $u = new user();
    $u->setName($name);

    return $u;
}

/**
 * @author Terry Lucas
 * @param $u
 */
function dump_user($u)
{
    $u->introduce();
}