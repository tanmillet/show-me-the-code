<?php
/*
  load.php
  2014.8.4
  加载工具...
*/
defined('ENTRY') or die('Deny you!');
class load{
  private $_model=array();
  private $_view=array();
  // 加载模板文件 ... View
  // 模板文件位置:application/view
  public function view($template_path,$data){
    if(!is_array($data)){
	  die('Your data input is not array format!');
	}
	// 组装好模板文件路径，加载并渲染 ... 如果存在子文件夹 ... 
	if(strstr($template_path,DIRECTORY_SEPARATOR)){
	  $view_file='application'.DIRECTORY_SEPARATOR.'view';
	  $sep_path_file=explode(DIRECTORY_SEPARATOR,$template_path);
	  foreach($sep_path_file as $key=>$item){
	    $view_file.=DIRECTORY_SEPARATOR.$item;
	  }
	  $view_file.='.php';
	}else{
	  $view_file='application'.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.$template_path.'.php';
	}
	// 分解数据 ...
	extract($data);
	// 开启数据缓冲 ...
	//ob_start();
	require_once $view_file;
	//ob_end_flush();
  }
  // 加载数据模型 ... Model
  public function model($name,$directory='application/model/'){
    // 如果该model已经被加载过，直接返回 ...
    if(in_array($name,$this->_model)){
	  return;
	}
    // 加载系统核心model类 ...
    if(!file_exists('system/core/model.php')){
	  die('The system core Model class does not exists!');
	}
	require_once 'system/core/model.php';
	if(!file_exists($directory.$name.'.php')){
	  die('The diy model class does not exists...');
	}
	require_once $directory.$name.'.php';
	// 获取当前控制器实例 ...
	$mt=&get_instance();
	$mt->$name=new $name();
	$this->_model[]=$name;
	return;
  }
}
