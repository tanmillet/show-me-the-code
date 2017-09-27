<?php


include "myauth.php";

$tb = 'demand';
$data['status'] = $_GET['status'];
$flag = Model_Db::mydb()->update($tb,$data,"id=".$_GET['id']);
//返回页面需要的参数
if($flag){
    echo empty($_GET['my']) ? json_encode(array("success"=>1,"jump"=>"index.php?m=demand&p=list")) : json_encode(array("success"=>1,"jump"=>"index.php?m=demand&p=mydemand"));
}else{
    echo json_encode(array("success"=>0,"error"=>"插入数据库错误"));
}