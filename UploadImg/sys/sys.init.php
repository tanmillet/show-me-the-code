<?php
final class Sys_Init {   
	private static $_root_paths = array('config','public','sys','model','sdk',);
	private static $_files    = array();
	private static $_ext      = '.php';
	private static $_init     = false;

	public static function init(){
		if( self::$_init ){
			return;
		}
		self::$_init = true;
		spl_autoload_register( array(__CLASS__, 'auto_load') );
	}
	public static function auto_load( $class ){
		$class = strtolower( $class );
		$_num  = substr_count( $class, '_' );
		if( empty($_num) ){
			return false;
		}
		$file = str_replace( '_', '.', $class ); //文件名，不带.php后缀
		$class_arr  = explode( "_", $class );
		$first_dir  = $class_arr[0];
		array_pop( $class_arr ); //去尾
		if(in_array($first_dir,self::$_root_paths)){
			$dirpath = "";
		}else{
			$dirpath = APP_PATH;       
		}
		$file_path = implode( $class_arr, DS ) . DS; //文件路径

		if( $require_file = self::find_file($dirpath,$file_path,$file) ){
			require_once($require_file);
			return true;
		}
		return false;
	}
	public static function find_file( $dirpath,$file_path,$file,$ext=NULL ){
		$ext = $ext ? ".{$ext}" : self::$_ext;
		$found = false;
		$filePath = $dirpath . $file_path . $file . $ext; //define路径，文件路径，文件名，后缀
		$key = md5($filePath);
		if( isset(self::$_files[$key]) ){ //已加载过，则无需再次加载
			$found = self::$_files[$key];
		}
		if( is_file($filePath) ){ //是有效的php文件
			self::$_files[$key] = $filePath;
			$found = $filePath;
		}
		return $found;
	}
}