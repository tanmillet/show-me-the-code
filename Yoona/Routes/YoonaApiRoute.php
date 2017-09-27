<?php
/**
 * Created by PhpStorm.
 * User: shuquan.ou
 * Date: 2017/1/11
 * Time: 17:46
 */

Route::group([
    'namespace' => 'App\Yoona\Http\OpenApi',
    'prefix' => 'yo/api',
], function ($route) {
    //第三方接口保存客户信息
    $route->post('/store', 'OpenApiContr@storeCustomer');
    //面签者进行 客户进行退回
    $route->post('/recust/{userKey}', 'OpenApiContr@rollbackCustomer');
    //面签者进行 客户审核
    $route->post('/subaudit/{userKey}', 'OpenApiContr@submitAudit');
    //图片上传保存到OSS统一入口
    $route->post('/storePic/{userKey}', 'OpenApiContr@storePic');
    $route->get('/map', 'OpenBaiDuMapContr@vmap');
});

Route::group([
    'namespace' => 'App\Yoona\Http\OpenApi',
    'prefix' => 'yo/api/test',
], function ($route) {
    $route->get('/create', 'CreateDataContr@create');
});