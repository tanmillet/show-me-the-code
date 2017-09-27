<?php
class Public_Mysql{
	private $config;
	public $my;
	public function __construct( $config ){        
		$this->config = $config;
	}
	public function connect(){
		if(!is_object($this->my)){
			$this->my = new mysqli($this->config['host'], $this->config['user'], $this->config['password'], $this->config['db_name']);
			if(mysqli_connect_errno()){
				die("connect error:".mysqli_connect_error());
			}
			$this->my->set_charset('utf8');
		}
		return $this->my;
	}
	public function query($sql){
		if(!is_object($this->my)){
			$this->connect();
		}
		if(!$result = $this->my->query($sql)){
			$this->errorlog($sql);
		}
		return $result;
	}
	public function getOne($sql){
		$result = $this->query($sql);
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$result->free();
		return $row;
	}
	public function getAll($sql){
		$rows = array();
		$result = $this->query($sql);
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$rows[] = $row;
		}
		$result->free();
		return $rows;
	}
	public function insert($table_name, $fields_arr){
		$sql = "INSERT INTO `{$table_name}` set ";
        foreach($fields_arr as $key => $value){
            $sql .= "`{$key}`='{$value}',";
        }
        $this->query(substr($sql,0,-1));
        return $this->insertId();
	}
	public function update($table_name, $fields_arr, $where){
		$sql = "update `{$table_name}` set ";
        foreach($fields_arr as $key => $value){
            $sql .= "`{$key}`='{$value}',";
        }
        $sql = substr($sql,0,-1)." where {$where}";
        return $this->query($sql);
	}
	public function insertId(){
		return $this->my->insert_id;
	}
	/*错误日志收集*/
    private function errorlog( $msg='' ){
        $filename   = basename(__FILE__);
        if($_REQUEST['error']){
        	echo $msg."<br />".$this->my->error;
        }
        $error = date("Y-m-d H:i:s")."|".$filename."|".$msg."|".$this->my->error;
        Sys_Logs::x()->writeLog( 'sql', $error, false );
    }
}