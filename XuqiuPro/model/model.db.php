<?php
class Model_Db{
	protected static $my = array();
	public static function mydb(){
        if( !is_object(self::$my[__FUNCTION__]) ){
            self::$my[__FUNCTION__] = new Public_Mysql(Config_Mysql::$mydb);
        }
        return self::$my[__FUNCTION__];
    }
}