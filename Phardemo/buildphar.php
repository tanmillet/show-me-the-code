<?php
/**
 * Created by PhpStorm.
 * Author Terry Lucas
 * Date 17.11.30
 * Time 10:47
 */
//在php.ini中修改phar.readonly这个选项，去掉前面的分号，并改值为off。
// 进行文件项目进行打包
$phar = new Phar('user.phar', 0, 'user.phar');
$phar->buildFromDirectory(dirname(__FILE__) . '/lucas');
//设置项目的启动项
$phar->setStub($phar->createDefaultStub('index.php', 'index.php'));
//进行打包工作
$phar->compressFiles(Phar::GZ);