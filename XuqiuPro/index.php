<?php
define('WEB', true);
define( 'APP', 'index' );
require_once "common.php";
if(!$sso_uinfo=Sdk_Sso::get_login_info()){
	header("location:".Sdk_Sso::get_logout_url());
	exit;
}
$mod  = trim($_REQUEST['m']) ? trim($_REQUEST['m']) : 'demand';
$page = trim($_REQUEST['p']) ? trim($_REQUEST['p']) : 'list';
$file =  APP_PATH. "{$mod}". DS ."{$page}".'.php';
if( !is_file($file) ){
    exit($file.' file is not exists...');
}
ini_set('session.cookie_path', '/');
ini_set('session.cookie_domain', $_SERVER['HTTP_HOST']);
ini_set('session.cookie_lifetime', 3600);

ob_start();
require_once( $file ); //先执行接口文件，获得$keywords来设置头部，主要是新闻类的
$ob_content = ob_get_clean(); //取出并清除缓冲区内容

if(!$noheadfoot){
	require_once( APP_PATH . 'common/head.php' );
}
echo $ob_content;
if(!$noheadfoot){
	require_once( APP_PATH . 'common/foot.php' );
}