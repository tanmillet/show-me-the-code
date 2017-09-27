<?php
/*
  matrix.php
  核心逻辑流程文件
  2014.7.31
*/
defined('ENTRY') or die('Deny you!');

// 载入核心函数库 ...
require_once SYS.'/common/common.php';


// 载入全站通用配置文件 ...
require_once SYS.'/config/config.php';
// 载入数据库配置文件 ...
require_once SYS.'/config/db.php';
// 载入路由配置文件 ...
require_once SYS.'/config/router.php';
// 整合所有配置数据 ...
$CFG['common']=$common_config;
$CFG['db']=$db_config;
$CFG['router']=$router_config;


// 加载必备核心基础类，这些类不必继承mt_controller类 ...



// 加载controller core文件 ... 实现控制入口单一化 
require_once SYS.'/core/controller.php';
function &get_instance(){
  return mt_controller::get_instance();
}


// 如果没有指定路由，使用router config默认的 ...
if(isset($_GET['c']) && $_GET['c']!=''){
  $controller=$_GET['c'];
  if(isset($_GET['m']) && $_GET['m']!=''){
    $method=$_GET['m'];
  }else{
    $method='index';
  }
}else{
  $controller=$CFG['router']['default'];
  $method='index';
}
// 根据contoller和method加载对应的控制器类和方法 ...
if(file_exists(APP.'/controller/'.$controller.'.php')){
  require_once APP.'/controller/'.$controller.'.php';
}else{
  die('Sorry,there is no this controller file...');
}
$mt=new $controller();
$mt->$method();
