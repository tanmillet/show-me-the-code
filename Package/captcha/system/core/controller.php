<?php
/*
  controller.php
  2014.7.31
  全局通用配置文件
*/
defined('ENTRY') or die('Deny you!');
class mt_controller{
  private static $instance;
  public function __construct(){
    // 将私有属性instance指向自己
    self::$instance=&$this;
    // 使用is_load函数将已经加载的类集中到mt_controller这个类中来 ...
    $classes=is_load();
    foreach($classes as $var=>$item){
      $this->$item=&load_class($item);
    }
	$this->load=&load_class('load');
  }
  //private function __clone();
  public static function &get_instance(){
    return self::$instance;
  }
}
