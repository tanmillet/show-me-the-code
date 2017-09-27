<?php

$tb = 'demand';
$tb2 = 'demand_comment';
if(isset($_POST) && !empty($_POST)){

    include "myauth.php";

    $data = $_POST['data'];
    $data['demandid'] = $data['id'];
    $data['username'] = $sso_uinfo['user_name'];
    $data['commentdate'] = time();
    unset($data['id']);
    $flag = Model_Db::mydb()->insert($tb2,$data);
    if($flag){
        echo json_encode(array("success"=>1,"jump"=>"?m=demand&p=comment&id=".$data['demandid']));
    }else{
        echo json_encode(array("success"=>0,"error"=>"添加失败"));
    }

}else{

    $id = $_GET['id'];
    $sql = "select * from {$tb} where id = ".$_GET['id'];
    $data = Model_Db::mydb()->getOne($sql);


    $sql = "select username,comment,commentdate from {$tb2} where demandid= ".$_GET['id']." order by commentdate desc limit 0,15";
    $comments = Model_Db::mydb()->getAll($sql);


    include 'tpl/comment.php';
}

