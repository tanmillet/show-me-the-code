<?php
/*
  common.php
  核心函数
  2014.7.31
*/
defined('ENTRY') or die('Deny you!');

// load class ...
if(!function_exists('load_class')){
  // 默认目录就是system/core下的核心类 ...
  function &load_class($name,$directory='system/core/'){
    static $_classes=array();
    // 如果已经加载过这个类，直接返回 ...
    if(isset($_classes[$name])){
      return $_classes[$name];
    }
    // 如果不存在这个类，停止运行 ...
    if(!(file_exists($directory.$name.'.php'))){
      die('Sorry,the class file does not exists!');
    }
    require_once($directory.$name.'.php');
    // 记录下来 ...
    is_load($name);
    // 实例化这个类并返回 ...
    $_classes[$name]=new $name();
    return $_classes[$name];
  }
}

// class who have been loaded ...
if(!function_exists('is_load')){
  function is_load($name=null){
    static $_loaded_classes=array();
    // 如果已经加载过，直接返回数组
    if(isset($_loaded_classes[$name])){
      return $_loaded_classes;
    }
    if($name!=null){
      $_loaded_classes[]=$name;
    }
    // 如果没有加载过，压入到数组中并返回
    return $_loaded_classes;
  }
}

// 格式化显示数组
if(!function_exists('specho')){
  function specho($arr,$debug=false){
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
	if($debug==true){
	  die();
	}
  }
}
