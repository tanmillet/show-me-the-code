<?php
if(empty($_FILES)){
	die("file empty");
}else{
	$sign = $_GET['sign'];
	$type = $_GET['type'];
	$time = $_GET['time'];
	$host = $_GET['host'];
	if(empty($sign) || empty($time) || empty($type) || empty($host)){
		echo json_encode(array("error"=>"缺少参数"));
		exit;
	}
	$mysign = md5($time.Config_Common::$upload_signkey.$type.$host);
	if($mysign!=$sign){
		echo json_encode(array("error"=>"sign错误"));
		exit;
	}
	if(time() - $time >300){
		echo json_encode(array("error"=>"time expire"));
		exit;
	}
	$conf = Config_Upload::$conf;
	if(empty($conf[$host])){
		echo json_encode(array("error"=>$host." 没配置"));
		exit;
	}
	$dir_id = $conf[$host]['dir_id'];
	$type_conf = $conf[$host]['type'][$type];
	if(empty($conf)){
		echo json_encode(array("error"=>$type." 没有配置"));
		exit;
	}
	$upload = new Public_Upload($type_conf['type'], $type_conf['max_size'], $type_conf['allow_ext']);
	$ret = array();
	$save_path = "upload".DS.$dir_id.DS.$type_conf['dir'];
	foreach($_FILES as $fn=>$file){
		$path = $upload->save($file, $save_path, false);
		if($path){
			$ret[$fn] = array("path"=>Config_Common::$domain.$path);
		}else{
			$ret[$fn] = array("error"=>$upload->error);
		}
	}
	echo json_encode($ret);
}