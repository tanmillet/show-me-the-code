<?php
class Sys_Logs{
	public static $myself = array();
	public static function x(){
		if( !is_object(self::$myself) ){
		    self::$myself = new self();
		}
		return self::$myself;
	}

	/**
	 * @param String 	$type  		类型mysql\redis\memcache...
	 * @param String 	$errorlog   日志文本
	 * @param boolean   $die   		写完日志后是否die掉，默认false，不die掉
	 * @param String 	$ext   		日志文件后缀,默认后缀为 .php
	 *
	 * */
	public function writeLog( $type, $errorlog, $die=false, $ext='.php' ){
		$path = DATA_PATH .$type.DS;
		if( !is_dir($path) ){
			if( !@mkdir( $path ) ){
				return false;
			}
		}
		$file = $path.$type.date('Ymd').$ext;
		file_put_contents($file, '<?php exit;?>'.$errorlog."\n", FILE_APPEND);
        if( $die ){
        	die(" {$type} Invalid");
        }else{
        	return true;
        }
	}
}