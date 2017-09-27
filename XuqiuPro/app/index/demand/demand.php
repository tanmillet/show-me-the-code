<?php
$tb = 'demand';
$tb2 = 'user_demand';
$tb3 = 'demand_comment';
//查询需求详细页
//显示内容1：需求详细内容 2：时间轴 3：追加需求信息
if (isset($_POST) && !empty($_POST)){
    $data = $_POST['data'];
    $data['demandid'] = $data['demandid'];
    $data['username'] = $sso_uinfo['user_name'];
    $data['commentdate'] = time();
    $flag = Model_Db::mydb()->insert($tb3,$data);
    if($flag){
        echo json_encode(array("success"=>1,"jump"=>"?m=demand&p=demand&id=".$data['demandid']));
    }else{
        echo json_encode(array("success"=>0,"error"=>"添加失败"));
    }
}else{

    //需求详细内容
    $sql = "select * from {$tb} where id = ".$_GET['id'];
    $data = Model_Db::mydb()->getOne($sql);

    //需求担当者
    $sql = "select username from {$tb2} where demandid = ".$_GET['id'];
    $usernames = Model_Db::mydb()->getAll($sql);

    //需求追加
    $zhuijias = '';
    $sql = "select comment,username from {$tb3} where demandid = ".$_GET['id'];
    $zhuijia = Model_Db::mydb()->getAll($sql);
    if (!empty($zhuijia)){
        foreach ($zhuijia as $zj) {
            $zhuijias .= '追加需求者:'.$zj['username'].' <br/>追加内容:'.$zj['comment'];
        }
    }
    include 'tpl/demand.php';
}