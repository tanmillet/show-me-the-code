<?php
/**
 * Created by PhpStorm.
 * User: Promise tan
 * Date: 2016/12/29
 * Time: 18:07
 */
//加载帮助方法
require app_path() . '/Yoona/helper.php';

//加载P层 表示解析层 进行解析 view与model 数据进行传输的枢纽
require app_path() . '/Yoona/mvvm.php';

//加载路由
foreach (glob(app_path('Yoona/Routes') . '/*.php') as $file) {
    require app_path() . str_replace('\\', '/', '\\Yoona\\Routes\\' . basename($file, '.php')) . '.php';
}

//加载注册logger
$this->app->bind('yologger', function () {
    return new \App\Yoona\Packages\Logger();
});

//加载注册configer
$this->app->bind('yoconfiger', function () {
    return new \App\Yoona\Packages\Configer(app_path('Yoona/Configs'));
});

//加载注册httper
$this->app->bind('yohttper', function () {
    return new \App\Yoona\Packages\Httper();
});

//加载注册sender
$this->app->bind('yosender', function () {
    return new \App\Yoona\Packages\Sender();
});

// //注册校验扩展类
$this->app->validator->resolver(function ($translator, $data, $rules, $messages) {
    return new \App\Yoona\Requests\Validators\CustomValidator($translator, $data, $rules, $messages);
});
