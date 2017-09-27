<?php
/**
 * 单点登陆
 * */
class Sdk_Sso{
	public static $project_id = 17; //项目id
	public static $secret_key = "56c2e01697b682101397c75f38d237ae"; //秘钥
	public static $verify_sec = 600; //每10分钟回主站校验一次
	public static $cookie_expire = 3600; //cookie有效时间，1小时
	public static $sso_url = "http://sso.3595.com/"; //sso地址
	public static $http_auth = false; //当sso系统出现故障，又着急用后台时，可以临时把该值设为true，采用HTTP认证，正常情况下务必要设为false
	public static $auth_user = "admin"; //采用http认证时的账号
	public static $auth_pw = "111111"; //采用http认证时的密码
	public static $cookie_fields = array(
			"verify_time",
			"user_name",
			"sign",
			"login_time",
			"user_role",
			"login_ip",
			"open_id",
	);
	/**
	 * 是否有权限操作
	 * @param string $op_code 操作标识
	 * @return bool
	 * */
	public static function check_privilege($op_code){
		if(self::$http_auth){
			return $_SESSION['http_auth_user'] ? true : false;
		}
		$uinfo = self::get_login_info();
		if(empty($uinfo)){
			return false;
		}
		$data = array(
			"user_name" => $uinfo['user_name'],
			"time" => time(),
			"op_code" => $op_code,
			"project_id" => self::$project_id,
		);
		$data['sign'] = self::create_privilege_sign(self::$secret_key, $data['user_name'], $data['project_id'], $data['op_code'], $data['time']);
		$data['op_code'] = urlencode($data['op_code']);
		$query = http_build_query($data);
		$url = self::$sso_url . "index.php?m=index&p=check_privilege&".$query;
		$ret = file_get_contents($url);
		$arr = json_decode($ret,1);
		return $arr['ok'];
	}
	/**
	 * 创建权限查询签名
	 * @param string $key 秘钥
	 * @param string $user_name 账号
	 * @param int $project_id 项目id
	 * @param string $op_code 操作标识
	 * @param int $time 时间戳
	 * @return string
	 * */
	public static function create_privilege_sign($key, $user_name, $project_id, $op_code, $time){
		$sign = md5($key . $user_name . $project_id . $op_code . $time);
		return $sign;
	}
	/**
	 * 获取登陆地址
	 * @return string
	 * */
	public static function get_login_url(){
		return self::$sso_url . "?project_id=" . self::$project_id;
	}
	/**
	 * 获取退出地址
	 * @return string
	 * */
	public static function get_logout_url(){
		return self::$sso_url . "?p=logout&project_id=" . self::$project_id;
	}
	/**
	 * 获取当前基础域名
	 * @return string
	 * */
	public static function get_base_domain(){
		return $_SERVER['HTTP_HOST'];
	}
	/**
	 * 是否登陆状态
	 * @return mix
	 * */
	public static function get_login_info(){
		if(self::$http_auth){
			if(empty($_SESSION['http_auth_user'])){
				if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SESSION['login'])) {
					$_SESSION['login'] = 1;
					header('WWW-Authenticate: Basic realm="My Realm"');
					header('HTTP/1.0 401 Unauthorized');
				}elseif($_SERVER['PHP_AUTH_USER'] == self::$auth_user &&  $_SERVER['PHP_AUTH_PW'] == self::$auth_pw) {
					$_SESSION['http_auth_user'] = $_SERVER['PHP_AUTH_USER'];
					header("location:".$_SERVER['REQUEST_URI']);
				}else{
					session_unset($_SESSION['login']);
					header("location:".$_SERVER['REQUEST_URI']);
				}
			}else{
				return array("user_name"=>$_SESSION['http_auth_user']);
			}
			exit;
		}
		if(!self::check_sign()){
			self::delete_cookie();
			return false;
		}
		foreach(self::$cookie_fields as $f){
			$login_info[$f] = $_COOKIE['sso_'.$f];
		}
		if(time() - strtotime($login_info['verify_time']) > self::$verify_sec){
			$login_info = self::check_login($login_info['open_id']);
			if(is_array($login_info)){
				self::set_cookie($login_info);
			}else{
				self::delete_cookie();
			}
		}
		return $login_info;
	}
	/**
	 * 退出登陆
	 * @return array
	 * */
	public static function logout(){
		self::delete_cookie();
		return array("ok"=>1);;
	}
	/**
	 * 清除cookie
	 * */
	public static function delete_cookie(){
		foreach(self::$cookie_fields as $f){
			setcookie('sso_'.$f, "", time()-3600, '/' ,self::get_base_domain());
		}
		return true;
	}
	/**
	 * 检查签名是否合法
	 * @return bool
	 * */
	public static function check_sign(){
		foreach(self::$cookie_fields as $f){
			if($f=='sign'){
				continue;
			}
			$login_info[$f] = $_COOKIE['sso_'.$f];
		}
		$sign = self::create_sign($login_info);
		return $sign == $_COOKIE['sso_sign'];
	}
	/**
	 * 创建签名
	 * @param array $login_info 登陆信息
	 * @return string
	 * */
	public static function create_sign($login_info){
		ksort($login_info);
		$json = json_encode($login_info);
		return $sign = md5($json. self::$project_id . self::$secret_key);
	}
	public static function get_open_id($ticket){
		$url = self::$sso_url."?m=index&p=get_open_id&ticket=".$ticket;
		$json = file_get_contents($url);
		$ret = json_decode($json, 1);
		return $ret['open_id'];
	}
	/**
	 * 登陆通知
	 * @return array
	 * */
	public static function login(){
		$ret = array("ok"=>0);
		if($_GET['ticket'] && $open_id = self::get_open_id($_GET['ticket'])){
			$uinfo = self::check_login($open_id);
			if(is_array($uinfo)){
				$flag = self::set_cookie($uinfo);
				if($flag){
					$ret = array("ok"=>1);
				}else{
					$ret['error'] = "set_cookie error";
				}
			}else{
				$ret['error'] = "json_decode error";
			}
		}else{
			$ret['error'] = "ticket error";
		}
		return $ret;
	}
	/**
	 * 到主站检查是否还是登陆状态
	 * @param string $token
	 * @return array
	 * */
	public static function check_login($open_id){
		$url = self::$sso_url."?m=index&p=check_login&open_id=".$open_id;
		$login_info = file_get_contents($url);
		return $uinfo = json_decode($login_info, 1);
	}
	/**
	 * 设置登陆cookie
	 * @param array $uinfo 账号信息数组
	 * @return bool
	 * */
	public static function set_cookie($uinfo){
		if(empty($uinfo)){
			return false;
		}
		$sign = self::create_sign($uinfo);
		setcookie('sso_sign', $sign, time()+self::$cookie_expire, '/' ,self::get_base_domain(), false, true);
		
		foreach(self::$cookie_fields as $f){
			if($f=="sign"){
				continue;
			}
			setcookie('sso_'.$f, $uinfo[$f], time()+self::$cookie_expire, '/' ,self::get_base_domain(), false, true);
		}
		setcookie('sso_url', self::$sso_url, time()+self::$cookie_expire, '/' ,self::get_base_domain(), false,false); //sso地址需要js读取，httponly设为false
		setcookie('sso_project_id', self::$project_id, time()+self::$cookie_expire, '/' ,self::get_base_domain(), false,false); //项目id需要js读取，httponly设为false
		return true;
	}
	/**
	 * 加密
	 * @param string $str 原文
	 * @param string $key 秘钥
	 * @return string 密文
	 * */
	public static function encrypt($str, $key="") {
        if ($str == ""){
			return "";
		}
		$key = $key ? $key : self::$secret_key;
        $result = "";
        for($i = 0;$i < ceil(strlen($str) / strlen($key));$i++) {
			$result = $result . bin2hex(substr($str, $i * strlen($key), ($i + 1) * strlen($key)) ^ $key);
        }
		return $result;
	}
	/**
	 * 解密
	 * @param string $str 密文
	 * @param string $key 秘钥
	 * @return string 原文
	 * */
	public static function decrypt($str, $key="") {
        if ($str == ""){
			return "";
		}
		$key = $key ? $key : self::$secret_key;
        $result = "";
        $j = 0;
        for($i = 0;$i < strlen($str) / 2;$i++) {
			if ($j >= strlen($key)){
				$j = 0;
			}
			$result = $result . (chr((hexdec(substr($str, $i * 2, 2)))) ^ substr($key, $j, 1));
			$j++;
		}
		return $result;
	}
}