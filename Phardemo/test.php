<?php
/**
 * Created by PhpStorm.
 * Author Terry Lucas
 * Date 17.11.30
 * Time 11:01
 */

//使用phar文件
require_once "lib/user.phar";
require_once "phar://user.phar/index.php";

//phar 文件进行别名获取
$phar = new Phar('lib/user.phar' , 0);
 $phar->setAlias('lucas.phar');

//phar 文件进行提出原文建
$phar = new Phar('lib/user.phar');
$phar->extractTo('testdir');

