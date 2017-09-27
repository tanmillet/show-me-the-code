<?php

Route::group(
/**
 * @author: promise tan
 * @param $route
 */
    [
        'namespace' => 'App\Yoona\Http\Front\Contrs',
        'prefix' => 'yofront',
    ], function ($route) {
    //面签者首页
    $route->get('/interviewer', 'InterviewerContr@index');
    //面签者客户列表
    $route->get('/interviewer/lists', 'AuditFlowContr@lists');
    //面签确认资料进度信息
    $route->get('/interviewer/infolist/{type}/{userKey}', 'AuditFlowContr@infolist');
    //面签确认信息
    $route->match(array('GET', 'POST'), '/interviewer/info/{type}/{userKey}', 'AuditFlowContr@info');
    //面签补录图片信息
    $route->get('/interviewer/uploadinfo/{userKey}', 'AuditFlowContr@uploadinfo');
    //面签补录图片信息
    $route->get('/interviewer/pic/{type}/{userKey}', 'AuditFlowContr@pic');
    //面签补录 面审成功
    $route->get('/interviewer/success/{type}/{userKey}', 'AuditFlowContr@success');
    //查看我得消息详情
    $route->get('/notice/{noteId}', 'InterviewerContr@myNote');
    //客户列表管理
    $route->get('/order/lists', 'AuditFlowContr@orderlists');
    //协审录入列表
    $route->get('/examine/inputlist', 'AuditFlowContr@inputlist');
    //协审录入资料
    $route->get('/examine/setexamine/{userKey}', 'AuditFlowContr@setexamine');
});