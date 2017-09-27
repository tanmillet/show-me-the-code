<?php
class Public_Crypt{
	private static $key = 'mykey123!@#%mykey123!@#%';
	/**
	 * 加密
	 * @param $string string
	 * @return string
	 * */
	public static function encrypt($string){	
		$iv_size = mcrypt_get_iv_size(MCRYPT_3DES,MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size,MCRYPT_RAND);
		$crypttext = mcrypt_encrypt(MCRYPT_3DES, self::$key, $string, MCRYPT_MODE_ECB, $iv);
	    return str_replace(array('+',"/"),array('-','_'),base64_encode($crypttext));
	}
	/**
	 * 解密
	 * @param $string string
	 * @return string
	 * */
	public static function decrypt($string){
		$string = base64_decode(str_replace(array('-',"_"),array('+','/'),$string));
		$iv_size = mcrypt_get_iv_size(MCRYPT_3DES, MCRYPT_MODE_ECB);
	    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	    $decrypttext = mcrypt_decrypt(MCRYPT_3DES, self::$key, $string, MCRYPT_MODE_ECB, $iv);
	    return trim($decrypttext);
	}
}