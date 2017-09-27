<?php
//获取$_POST
$where = array(1);
if(isset($_POST) && !empty($_POST)){

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

    include "myauth.php";

    //分配者进行数据库更新
    $tb1 = 'demand'; // 更新需求的状态 和 更新需求技术完成时间
    $tb2 = 'user_demand'; // 添加一条数据在db中 若获取的用户的名称已经在任务中则不添加
    $data = $_POST['data'];

    if(empty($data['adminname'])){
        echo json_encode(array("success"=>0,"error"=>"担当者不能为空"));
        exit;
    }

//    if(empty($data['enddate']) || empty($data['startdate'])){
//        echo json_encode(array("success"=>0,"error"=>"担当完成时间不能为空"));
//        exit;
//    }

//    $data['startdate'] = strtotime($data['startdate']);
//    $data['enddate'] = strtotime($data['enddate']);

    $data['startdate'] = time();
    $data['enddate'] = time();

    //查询用户是否在该任务中
    $where[] = "username='".$data['adminname']."'";
    $where[] = "demandid='".$data['id']."'";
    $where = implode(" and " ,$where);
    $sql = "select count(*) as num from {$tb2} where {$where}";
    $total = Model_Db::mydb()->getOne($sql);
    if (!empty($total) && $total['num'] != 0 ){
        echo json_encode(array("success"=>0,"error"=>"该用户已经存在需求任务中"));
        exit;
    }

    $data_2['demandid'] = $data['id'];
    $data_2['issuedate'] = time();
    $data_2['username'] = $data['adminname'];
    $data_2['adminname'] = $sso_uinfo['user_name'];
    unset($data['adminname']);
    $data['status'] = 2;

    $flag1 = Model_Db::mydb()->update($tb1,$data,"id=".$data['id']);
    $flag2 = Model_Db::mydb()->insert($tb2,$data_2);

    if($flag1 && $flag2){
        echo json_encode(array("success"=>1,"jump"=>"?m=demand&p=list"));
    }else{
        echo json_encode(array("success"=>0,"error"=>"插入数据库错误"));
    }

} else {

    include 'tpl/issue.php';
}


