<?php
!defined('WEB') AND exit('Access Denied!');

/*
 * 递归方式对变量中的特殊字符去除转义
 * @access public 
 * @param mix $value
 * return $value
 */

if(!function_exists('addslashes_deep')){
	function addslashes_deep($value){
		if(empty($value)){
			return $value;
		}else{
			return is_array($value) ? array_map('addslashes_deep', $value) : addslashes($value);
		}
	}
}

/*
* 递归方式对变量中的特殊字符去除转义
* @access public
* @param mix $value
* return $value
*/
if(!function_exists('stripslashes_deep')){
	function stripslashes_deep($value){
		$value = is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
		return $value ;
	}
}

if(!get_magic_quotes_gpc()){
	$_GET = addslashes_deep($_GET);
	$_POST = addslashes_deep($_POST);
	$_COOKIE = addslashes_deep($_COOKIE);
}
error_reporting( E_ALL ^ E_NOTICE );
if( $_REQUEST['error'] ){
	ini_set("display_errors","On");
}
date_default_timezone_set( 'Asia/Shanghai' );
ini_set('default_charset', "utf-8");
define( 'DS', DIRECTORY_SEPARATOR ); //目录分割符
define( 'ROOT_PATH', dirname(__FILE__).DS);
define( 'ROOT_APP_PATH', 'app'.DS ); //应用跟目录
define( 'STATIC_PATH', 'static'.DS );     //图片、js等静态资源
define( 'PUBLIC_PATH', 'public'.DS ); //公共的目录，跟项目无关的通用类，如redis，mysql等
define( 'SYS_PATH', 'sys'.DS );    //本框架运行需要的类库
define( 'APP_PATH', ROOT_APP_PATH . APP . DS );   //具体应用目录
define( 'DATA_PATH', dirname(__FILE__).DS."data".DS); //可写的目录
define( 'UPLOAD_PATH', DS . "static" .DS.'upload' . DS );  //上传目录

define('GATE_GM_SEND_TIMEOUT', '5'); //GM网关 发送超时
define('GATE_GM_REV_TIMEOUT', '5');  //GM网关 接受超时
define('GM_BINARY_NET_IS_BIG_ENDIAN', true); //检查 网络输入的二进制字符串 是否为  大端(网络字节序)
define('GM_BINARY_MACHINE_IS_BIG_ENDIAN', pack('s', 1) === pack('S', 1)); //检查 本地运行的机器 的二进制字符串 是否为  大端(网络字节序)
define('GM_BINARY_IS_SHOULD_TO_CHANGE', GM_BINARY_MACHINE_IS_BIG_ENDIAN === GM_BINARY_NET_IS_BIG_ENDIAN); //作为 字节序不同 需要 翻转字符串 的判断


if ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') || $_REQUEST['is_ajax']) {
	define('IS_AJAX', true);
}else{
	define('IS_AJAX', false);
}

require_once( SYS_PATH . 'sys.init.php' );     //类的自动加载
Sys_Init::init();