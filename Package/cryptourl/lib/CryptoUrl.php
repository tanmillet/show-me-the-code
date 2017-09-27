<?php

class CryptoUrl
{

    static private $encrypt_method;
    static private $secret_key = "This is my secret key";
    static private $secret_iv = "This is my secret iv";


    public function __construct()
    {
        $this->secret_iv = mcrypt_create_iv($this->secret_iv);
        $this->cipher = "MCRYPT_RIJNDAEL_256";
    }


    public function encrypt($cipher="MCRYPT_RIJNDAEL_256", $secret_key="This is my secret key" ,$array)
    {
        $obj = new stdClass();
        //TODO iterate recursively, by now just iterate one level, not multidimensional array
        foreach ($array as $key => $value) {
            $obj->$key = $value;
        }

        $objSerialized = serialize($obj);
        $objEncrypted = mcrypt_encrypt( self::$encrypt_method, $secret_key, $objSerialized);
        $string = $this->_urlsafe_b64encode($objEncrypted);

        return $string;
    }

    //$method=$this->method
    public function decrypt($method="", $secret_key="This is my secret key", $string )
    {
        $objEncrypted = $this->_urlsafe_b64decode($string);

        if (!empty($objEncrypted)) {
            $objSerialized = mcrypt_decrypt( $method, $secret_key, $objEncrypted);
            $obj = unserialize($objSerialized);
        }

        if(is_object($obj)){
           $arr = get_object_vars($obj); // object to array
        }

        return $arr;
    }



    private function _urlsafe_b64encode($string)
    {
        $data = base64_encode($string);
        $data = str_replace(array('+', '/', '='), array('-', '_', '.'), $data);
        return $data;
    }


    private function _urlsafe_b64decode($string)
    {
        $data = str_replace(array('-', '_', '.'), array('+', '/', '='), $string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }

}

