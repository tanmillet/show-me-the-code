<?php
 $tb = 'demand';
if (isset($_POST) && !empty($_POST)){
	//获取的添加需求信息
	$data = $_POST['data'];
	if(empty($data['title'])){
		echo json_encode(array("success"=>0,"error"=>"需求标题不能为空"));
		exit;
	}
	if(empty($data['type'])){
		echo json_encode(array("success"=>0,"error"=>"需求类型不能为空"));
		exit;
	}
	
	//需求期待时间
//	$data['expectantstartdate'] = strtotime($data['expectantstartdate']);
//	$data['expectantenddate'] = strtotime($data['expectantenddate']);
	$data['expectantstartdate'] = time();
	$data['expectantenddate'] = time();
	$data['adminname'] = $sso_uinfo['user_name'];
	$data['createdate'] = time();
	
	//数据写入数据库
	$flag = Model_Db::mydb()->insert($tb,$data);
	if($flag){
		echo json_encode(array("success"=>1,"jump"=>"?m=demand&p=list"));
	}else{
		echo json_encode(array("success"=>0,"error"=>"添加失败"));
	}
}else{
	include "tpl/add.php";
}