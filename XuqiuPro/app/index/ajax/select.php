<?php
$ret = '';
switch($_GET['type']){
	case 'uname' :
        $ch = curl_init("http://sso.3595.com/api.php?m=user&p=list") ;
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回
        $output = curl_exec($ch) ;
        $uname = strval($_GET['adminname']);
        $usernames = json_decode($output);
        $ret .= '<option value="">==需求创建者==</option>';
		foreach($usernames as $o){
			$selected = ($uname == $o->user_name) ? "selected" : "";
			$ret .= '<option value="'.$o->user_name.'" '.$selected.'>'.$o->user_name.'</option>';
		}
		break;
    case 'demanduname' :
        $ch = curl_init("http://sso.3595.com/api.php?m=user&p=list") ;
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回
        $output = curl_exec($ch) ;
        $uname = strval($_GET['adminname']);
        $usernames = json_decode($output);
        $ret .= '<option value="">==需求担当者==</option>';
        foreach($usernames as $o){
            $selected = ($uname == $o->user_name) ? "selected" : "";
            $ret .= '<option value="'.$o->user_name.'" '.$selected.'>'.$o->user_name.'</option>';
        }
		break;
}
die($ret);