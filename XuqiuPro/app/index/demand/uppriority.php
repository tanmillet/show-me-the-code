<?php

include "myauth.php";

//需求分配 是否有权限
$mod = $_GET['m'];
$page = $_GET['p'];


$op_code = "m={$mod}&p={$page}";
$free_op_code = Common_Conf_Privilege::$free_op_code;

if(!Sdk_Sso::check_privilege($op_code) && !in_array($op_code,$free_op_code)){
    $tip = "您没有该操作权限";
    if(IS_AJAX){
        echo json_encode(array("success"=>0,"error"=>$tip,"tip"=>$tip));
        exit;
    }
}

$tb = 'demand';
$data['priority'] = $_GET['upprio'];
$flag = Model_Db::mydb()->update($tb,$data,"id=".$_GET['id']);
if($flag){
    echo json_encode(array("success"=>1,"error"=>"需求优先度修改成功"));
}else{
    echo json_encode(array("success"=>0,"error"=>"插入数据库错误"));
}