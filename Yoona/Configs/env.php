<?php
/**
 * Created by PhpStorm.
 * User: Promise tan
 * Date: 2017/1/3
 * Time: 16:54
 */
return [
    'dbconnection' => 'mysql-yo',
    'mail'=>[
        'view' => 'Yoona.Admin.Email.email_demo',
        'from'=>env('MAIL_USERNAME'),
        'sender'=>env('MAIL_USERNAME'),
        'to'=>env('MAIL_TO'),
        'subject'=>'系统邮件',
        'cc'=>explode(',', env('MAIL_CC'))
    ],
    'ALL_STREAM' => 'http://10.80.2.242:8080/clf_service/http/internalService',
];
