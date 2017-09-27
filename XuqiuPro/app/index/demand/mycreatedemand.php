<?php

//创建需求者 并且 user_role = 1

$where = array(1);
$usernmae = $sso_uinfo['user_name'];

$where[] = "adminname='".$usernmae."'";

if($_GET['title']){
    $where[] = "title like '%".$_GET['title']."%'";
}

if($_GET['deptid']){
    $where[] = "type='".$_GET['deptid']."'";
}

if($_GET['status']){
    $where[] = "status='".$_GET['status']."'";
}

$where = implode(" and " ,$where);
$sql = "select count(*) as num from demand where {$where}";
$total = Model_Db::mydb()->getOne($sql);
$pagesize = 50;
$page = new Public_Page($total['num'], $pagesize);

$sql = "select * from demand where {$where} order by id desc limit {$page->offset},{$pagesize}";
$demands = Model_Db::mydb()->getAll($sql);
if(!empty($demands)){
    foreach ($demands as $key=>$demand) {
        $sql = "select username from user_demand where demandid = ".$demand['id'];
        $names = Model_Db::mydb()->getAll($sql);
        $username = '';
        if(!empty($names)){
            foreach ($names as $name) {
                $username .= $name['username']." ";
            }
        }else{
            $username = '人员未分配';
        }

        $demands[$key]['other'] = $username;
    }
}
include "tpl/mycreatedemand.php";


