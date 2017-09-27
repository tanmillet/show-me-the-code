<?php
class Model_Redis{
protected static $my = array();
	public static function mycahe(){
        if( !is_object(self::$my[__FUNCTION__]) ){
            self::$my[__FUNCTION__] = new Public_Redis(Config_Redis::$redis);
        }
        return self::$my[__FUNCTION__];
    }
}