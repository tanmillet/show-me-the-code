<?php
/**
 * Created by PhpStorm.
 * User: Promise tan
 * Date: 2016/12/29
 * Time: 18:07
 */
//加载帮助方法
require app_path().'/YoSDK/helper.php';

//加载路由
foreach (glob(app_path('YoSDK/Routes') . '/*.php') as $file) {
    require app_path() . str_replace('\\', '/', '\\YoSDK\\Routes\\' . basename($file, '.php')).'.php';
}
