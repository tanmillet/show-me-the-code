<?php
if(empty($_FILES)){
	$time = time();
	$host = $_SERVER['HTTP_HOST'];
	$sign = md5($time.Config_Common::$upload_signkey.$_GET['type'].$host);
	include "tpl/upload.php";
}