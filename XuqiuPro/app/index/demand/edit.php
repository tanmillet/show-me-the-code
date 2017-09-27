<?php

//只有自己的创建的需求才有权限进行修改
$tb = 'demand';
$demand_id = intval($_GET['id']);
$demand_status = intval($_GET['demandstatus']);
$adminname = $sso_uinfo['user_name'];

if (isset($_POST) && !empty($_POST)){

    include "myauth.php";
    $data = $_POST['data'];

    $sql = "select count(*) AS num from {$tb} where adminname ='{$adminname}' AND id ={$demand_id}";
    $flag = Model_Db::mydb()->getOne($sql);
    if (empty($flag) || $flag['num'] == 0){
        echo json_encode(array("success"=>0,"error"=>"编辑需求没有权限"));
        exit;
    }

    if(empty($data['id'])){
        echo json_encode(array("success"=>0,"error"=>"修改的需求ID不能为空"));
        exit;
    }

    if(empty($data['title'])){
        echo json_encode(array("success"=>0,"error"=>"需求标题不能为空"));
        exit;
    }
    if(empty($data['type'])){
        echo json_encode(array("success"=>0,"error"=>"需求类型不能为空"));
        exit;
    }

    $data['expectantstartdate'] = strtotime($data['expectantstartdate']);
    $data['expectantenddate'] = strtotime($data['expectantenddate']);
    $data['createdate'] = time();

    $flag = Model_Db::mydb()->update($tb,$data,"id=".$data['id']);
    if($flag){
        echo (empty($_GET['my'])) ? json_encode(array("success"=>1,"jump"=>"?m=demand&p=list")) : json_encode(array("success"=>1,"jump"=>"?m=demand&p=mydemand"));
    }else{
        echo json_encode(array("success"=>0,"error"=>"插入数据库错误"));
    }

}else{
    switch($demand_status){
        case 1:
        case 2:
            //需求可以编辑
            $sql = "select * from {$tb} where id ={$demand_id}";
            $data = Model_Db::mydb()->getOne($sql);
            include 'tpl/edit.php';
            break;
        case 3:
        case 4:
        case 5:
            //需求不可编辑
            echo "<script type='text/javascript'>alert('需求不可编辑！可去标题详细页面进行追加需求。');</script>";
            break;
        default:
            echo "<script type='text/javascript'>alert('参数出错！联系技术组。');</script>";
            break;
    }
}

