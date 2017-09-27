<?php
Class Public_Socket{
    /**
     * socket 资源标识
     * @var resource
     */
    private $socket = null;
    /**
     * socket 资源 是否连接标识
     * @var resource
     */
    private $connected = false;
    /**
     * 新建建立连接
     * @param $host string 连接的IP
     * @param $port string 连接的PORT
     * @param $send_timeout int 发送超时时间
     * @param $rev_timeout  int 接受超时时间
     */
    public function __construct($host,$port,$send_timeout,$rev_timeout){
        $this->socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);

        if(!$this->socket){
            throw new Exception('Create TCP Socket Failure!');
        }
        $is_connected = socket_connect($this->socket,$host,$port);
        if(!$is_connected){
            throw new Exception("Connect to {$host}:($port) Failure! ERROR STR:". socket_strerror(socket_last_error($this->socket)) );
        }else{
            $this->connected  = true;
        }
        // 发送超时
        socket_set_option($this->socket,SOL_SOCKET,SO_SNDTIMEO,array('sec'=>$send_timeout,'usec'=>0));
        // 接受超时
        socket_set_option($this->socket,SOL_SOCKET,SO_RCVTIMEO,array('sec'=>$rev_timeout,'usec'=>0));
        return true;
    }

    /** 写入 数据
     * @param $data
     * @return bool|int
     */
    public function write($data){
        //连接关闭，或者 连接失败时 不能 写入数据
        if(!$this->connected){
            return false;
        }
        $str_len = strlen($data);
        $sent_data_len = socket_write($this->socket,$data,$str_len);
        if($sent_data_len){
            socket_clear_error($this->socket); #清除错误
        }else{
            throw new Exception("Write Socket ERROR Failure! ERROR STR:". socket_strerror(socket_last_error($this->socket)) );
        }
        return $sent_data_len;
    }

    /** 接受 数据
     * @param $str_len int
     * @return bool|string
     */
    public function read($str_len = 0){
        //连接关闭，或者 连接失败时 不能 读取数据
        if(!$this->connected){
            return false;
        }
        if($str_len <= 0){
            $str_len = 1024;
        }
        $read_data = socket_read($this->socket,$str_len);
        if(socket_last_error($this->socket)){
            header("Content-Type:text/html;charset=gb2312");
            throw new Exception("Read Socket ERROR Failure! ERROR STR:". socket_strerror(socket_last_error($this->socket)) );
        }
        socket_clear_error($this->socket); #清除错误
        return $read_data;
    }

    /**
     * 关闭Socket
     * @throws Exception
     */
    public function close(){
        $this->connected = false;
        try{
            #尝试关闭socket连接
            socket_close($this->socket);
        }catch (Exception $e){
            throw new Exception("Close The Socket Failure! ERROR STR:". socket_strerror(socket_last_error($this->socket)) );
        }
    }
    /**
     *  析构函数
     *  防止未关闭连接
     */
    public function __destruct(){
        try{
            #尝试关闭socket连接
            socket_close($this->socket);
        }catch (Exception $e){
            //TODO:NOTHING
        }
    }
}