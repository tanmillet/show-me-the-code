<?php

$tb1 = 'demand';
$tb2 = 'user_demand';

$where = array(1);

if($_GET['id']){
    $where[] = "t1.id='".$_GET['id']."'";
}

if($_GET['title']){
    $where[] = "t1.title like '%".$_GET['title']."%'";
}

if($_GET['deptid']){
    $where[] = "t1.type='".$_GET['deptid']."'";
}

if($_GET['adminname']){
    $where[] = "t1.adminname='".$_GET['adminname']."'";
}

if($_GET['status']){
    $where[] = "t1.status='".$_GET['status']."'";
}

//if($_GET['expectantstartdate']){
//    $where[] = "t1.expectantstartdate >='".strtotime($_GET['expectantstartdate'])."'";
//}
//
//if($_GET['expectantenddate']){
//    $where[] = "t1.expectantenddate <='".strtotime($_GET['expectantenddate'])."'";
//}

$usernmae = $sso_uinfo['user_name'];
$where = implode(" and " ,$where);
$sql = "select count(*) as num from demand AS t1 , user_demand AS  t2 where {$where} AND t1.id = t2.demandid AND t2.username = '{$usernmae}'";

$total = Model_Db::mydb()->getOne($sql);
$pagesize = 100;
$page = new Public_Page($total['num'], $pagesize);

$sql = "select t1.* from demand AS t1 , user_demand AS  t2 where {$where} AND t1.id = t2.demandid AND t2.username = '{$usernmae}' order by t1.priority,t1.id desc limit {$page->offset},{$pagesize}";
$demands = Model_Db::mydb()->getAll($sql);


include 'tpl/mydemand.php';