<?php
class mysql{
  //private $host;
  //private $username;
  //private $password;
  private $db;
  private $connection;
  private $trans_switch=false;
  private $trans_status=true;
  public function __construct($host,$username,$password,$db){
    $this->connection=mysql_connect($host,$username,$password);
	$this->db=$db;
	mysql_select_db($db,$this->connection);
  }
  
  // 执行SQL...
  private function query($sql){
    $result=mysql_query($sql,$this->connection);
	if(!$result){
	  return array(
	    'code'=>4,
		'debug'=>$this->error_handler()
	  );
	}else{
	  return array(
	    'code'=>8,
		'result'=>$result
	  );
	}
  }
  public function insert($sql){
    $result=$this->query($sql);
	if($result['code']==8){
	  return true;
	}else{
	  return false;
	}
  }
  // 以数组形式返回查询结果...
  public function array_result($sql){
    $result=$this->query($sql);
	if($result['code']==4){
	  return $result['debug'];
	}else if($result['code']==8){
	  // 组装二维数组...
	  while($row=mysql_fetch_array($result['result'])){
	    $arr[]=$row;
	  }
	  return $arr;
	}
  }
  // 以对象形式返回查询结果...
  public function object_array($sql){
    $result=$this->query($sql);
	if($result['code']==4){
	  return $result['debug'];
	}else if($result['code']==8){
	  // 组装二维数组...
	  while($row=mysql_fetch_object($result['result'])){
	    $arr[]=$row;
	  }
	  return $arr;
	}
  }
  
  // 开启事务方法组...
  public function trans_begin(){
    $this->trans_switch=true;
	$this->trans_status=true;
    // set autocommit=0 || start transaction || begin ...
	$this->query("set autocommit=0");
  }
  private function trans_commit(){
    $this->query("commit");
  }
  public function trans_rollback(){
    $this->query("rollback");
  }
  public function trans_status(){
    if($this->trans_status==true){
	  $this->trans_commit();
	  return true;
	}else{
	  $this->trans_rollback();
	  return false;
	}
  }
  public function trans_complete(){
    $this->query("set autocommit=1");
	$this->trans_status=true;
	$this->trans_switch=false;
  }
  
  // 记录错误log，默认不记录到log文件中...
  private function error_handler($log=false){
    // 如果开启事务，那么记录错误
    if($this->trans_switch==true){
	  $this->trans_status=false;
	}
    return mysql_errno($this->connection).':'.mysql_error($this->connection);
  }
  // 释放资源连接...
  private function free_result($result){
    mysql_free_result($result);
  }
  // 关闭数据库连接...
  public function close_connection(){
    mysql_close($this->connection);
  }
  private function clean($sql){
    //return mysql_real_escape_sql();
  }
}