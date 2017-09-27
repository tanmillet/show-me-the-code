<?php
date_default_timezone_set('PRC');

class UploadHandler{
    private $m_name = 'paperFile';
    private $m_exts = '/^xls|xlsx$/';
    private $m_maxSize = 10485760;
    private $m_storedDir = '';
    private $m_movedPath = null;
    private $m_fileName = null;

    private $m_errorMessage = array(
        'type_failed' => '文件类型错误',
        'size_exceeded' => '文件太大'
    );

    public function __construct($storedDir, $name){
        $this->m_storedDir = $storedDir;
        $this->m_name = $name;
    }

    public function handleUploading(){
        if (isset( $_SERVER['REQUEST_METHOD'] )){
            $method = $_SERVER['REQUEST_METHOD'];
            if ( $method == 'POST' ){
                if ( $content = $this->handlePost() ){
                    // upload successfully, parse the excel file and write into DB
                    // $this->makeResponse( $content );
                } else{
                    header( 'HTTP/1.1 404 Not Found');
                }
            } else{
                header( 'HTTP/1.1 405 Method Not Allowed' );
            }
        } else{
            header( 'HTTP/1.1 404 Not Found');
        }
    }

    public function getMovedPath(){
        return $this->m_movedPath;
    }

    public function getFileName(){
        return $this->m_fileName;
    }

    private function handlePost(){
        if ( isset( $_FILES[$this->m_name] ) ){
            $uploadInstance = $_FILES[$this->m_name];
            if ( !empty( $uploadInstance )
                && $uploadInstance['error'] == UPLOAD_ERR_OK
                && is_uploaded_file( $uploadInstance['tmp_name'] ) ){

                $result = $this->validatesFile( $uploadInstance );
                if ( $result === true ){
                    $timeStamp = time() . mt_rand();
                    $movedFilePath = $this->m_storedDir. "$timeStamp". '.xlsx';
                    if ( move_uploaded_file( $uploadInstance['tmp_name'], $movedFilePath ) ){
                        $this->m_fileName = $uploadInstance['name'];
                        $this->m_movedPath = $movedFilePath;
                        return true;
                    } else{
                        return false;
                    }
                } else{
                    return false;
                }
            } else{
                return false;
            }
        } else{
            return false;
        }
    }

    private function validatesFile( $uploadInstance ){
        // not a excel file
        $splitName = explode(".", $uploadInstance['name']);
        $extension = end($splitName);
        if ( preg_match( $this->m_exts, $extension ) ){
            if ( $uploadInstance['size'] < $this->m_maxSize ){
                return true;
            } else{
                return $this->m_errorMessage['size_exceeded'];
            }
        } else{
            return $this->m_errorMessage['type_failed'];
        }
    }

    private function makeResponse($content){
        echo $content;
    }
}






