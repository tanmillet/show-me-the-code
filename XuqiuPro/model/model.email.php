<?php
class Model_Email{
	protected static $my = null;
	protected static $mail = null;
	public static function sys(){
        if( !is_object(self::$mail) ){
            self::$mail = new Public_Mail();
            self::$mail->setServer(Config_Web::$email['smtp'], Config_Web::$email['sys_account'], Config_Web::$email['sys_password']);
			self::$mail->setFrom(Config_Web::$email['sys_account']);
        }
        if( !is_object(self::$my) ){
        	self::$my = new self();
        }
        return self::$my;
    }
    public function sendMail($to_email,$title, $body){
    	if(empty($to_email) || empty($title) || empty($body)){
    		return false;
    	}
    	self::$mail->setReceiver($to_email);
    	self::$mail->setMailInfo($title, $body);
		return self::$mail->sendMail();
    }
}