<?php

Route::group(
/**
 * @author: promise tan
 * @param $route
 */
    [
        'namespace' => 'App\Yoona\Http\Admin\Contrs',
        'prefix' => 'yo',
    ], function ($route) {

    $route->get('/test', function () {
        // $address = '江西省赣州市赣县茅店镇桥';
        // $_url = sprintf('http://maps.google.com/maps?output=js&q=%s',rawurlencode($address));
        // $_result = false;
        // if($_result = file_get_contents($_url)) {
        //     if(strpos($_result,'errortips') > 1 || strpos($_result,'Did you mean:') !== false) return false;
        //     preg_match('!center:\s*{lat:\s*(-?\d+\.\d+),lng:\s*(-?\d+\.\d+)}!U', $_result, $_match);
        //     $_coords['lat'] = $_match[1];
        //     $_coords['long'] = $_match[2];
        // }
        // return $_coords;
        // $f=fopen("http://api.map.baidu.com/geocoder/v2/?ak=您百度的密钥&callback=renderReverse&location=纬度,经度&output=json&pois=0",r);
        // $response=fread($f,1024);
        // fclose($f);
        // echo $response;//具体地理位置

        // //演示示例DEMO
        // $R360['R360companyName'] = '佰仟';
        // $R360['R360category'] = 'A-农、林、牧、渔业';
        // $outputs = tranfer('R360')->invoke($R360)->tranferOne('yo_trial_company')->gc('YoTrialCompany')->getOutputs();
        // var_dump($outputs);
        // $outputs = tranfer('R360')->invoke($R360)->tranferOne('yo_trial_company' , true)->getOutputs();
        // var_dump($outputs);
        // $outputs = tranfer('R360')->invoke($R360)->tranferAll()->getOutputs();
        // var_dump($outputs);
        // $outputs = tranfer('R360')->invoke($R360)->tranferAll(true)->getOutputs();
        // var_dump($outputs);

        // $outputs = tranfer('R360')->tranferForm(new \App\Yoona\Dtos\YoCustomerInfo() , ['user_key' => 'X0d4c54c248ed37c4fe042324eeabf139'])->getOutputs();
        // var_dump($outputs);
        //
        // $outputs = tranfer('R360')->tranferForm(new \App\Yoona\Dtos\YoCustomerInfo() , ['user_key' => 'X0d4c54c248ed37c4fe042324eeabf39'])->getOutputs();
        // var_dump($outputs);

        // dump($this->app);
        // \Illuminate\Support\Facades\App::make('validator')->resolver(function ($translator, $data, $rules, $messages) {
        //     return new \App\Yoona\Requests\Validators\CustomValidator($translator, $data, $rules, $messages);
        // });
        //
        // $attributes = ['phone' => 123];
        // $rules = ['phone' => 'Phone2343'];
        // $v = \Illuminate\Support\Facades\Validator::make($attributes,$rules);
        //
        // dump($v->messages());
        // dump($v->passes());

        ///
        // dump(yoconf('env.dbconnection'));
        // dump(yoconf()->get('env.dbconnection'));
        // dump(C()->get('env.dbconnection1' , function (){
        //     return 1+1;
        // }));
    });
    $route->get('/projects', 'ProjectAdminContr@projects');
    $route->get('/logout', 'ProjectAdminContr@logout');
    $route->match(array('GET', 'POST'), '/unlock', 'ProjectAdminContr@login');
    //2017-1-10新增 统计分析-转化率统计
    $route->get('transformationratio', 'OperateContr@transformationRatio');
    //2017-1-10新增 客户查询
    $route->get('custlist', 'CustomerContr@getCust');
    //2017-1-10新增 面签订单管理
    $route->get('facesign', 'LoanOrderContr@faceSign');
    //2017-1-10新增 面签销售分区配置-列表
    $route->get('salearea', 'CfgContr@saleAreaList');
    //2017-1-10新增 面签销售分区配置-新增
    $route->get('saleareaadd', 'CfgContr@saleAreaAdd');
    //2017-1-10新增 城市配置-列表
    $route->get('city', 'CfgContr@saleAreaList');
    //2017-1-10新增 城市配置-新增
    $route->get('cityadd', 'CfgContr@saleAreaAdd');
    //2017-1-10新增 产品配置-列表
    $route->get('product', 'CfgContr@productList');
    //2017-1-10新增 产品配置-新增
    $route->get('productadd', 'CfgContr@productAdd');
    $route->get('/react', 'LogContr@reactIndex');
    //2017-1-20新增 客户状态码配置-列表
    $route->get('customstatus/{page?}', 'CfgContr@customStatusList')->where(['page' => '[0-9]+']);
    //2017-1-20新增 客户状态码配置-编辑（新增/修改）
    $route->match(array('GET', 'POST'), 'customstatus-edit/{id?}', 'CfgContr@customStatusEdit');
    //2017-1-20新增 客户状态码配置-禁用
    $route->get('customstatus-del/{id}', 'CfgContr@customStatusDel');
    //2017-2-6新增 客户状态码配置-启用
    $route->get('customstatus-enable/{id}', 'CfgContr@customStatusEnable');
    //2017-2-7新增 客户影像资料类型配置-列表
    $route->get('custom-pic-type/{page?}', 'CfgContr@customPicType')->where(['page' => '[0-9]+']);
    //2017-2-7新增 客户影像资料类型配置-编辑（新增/修改）
    $route->match(array('GET', 'POST'), 'custom-pic-type-edit/{id?}', 'CfgContr@customPicTypeEdit');
    //2017-2-7新增 客户影像资料类型配置-禁用
    $route->get('custom-pic-type-del/{id}', 'CfgContr@customPicTypeDel');
    //2017-2-7新增 客户影像资料类型配置-启用
    $route->get('custom-pic-type-enable/{id}', 'CfgContr@customPicTypeEnable');
});