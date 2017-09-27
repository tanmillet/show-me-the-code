<?php

require "GlobalConfig.php";

function DoFailedAction(){
    echo '<html>
            <head>
                <meta charset="utf-8">
                <meta http-equiv="refresh" content="5; url=Management.php">
            </head>
            <body>
                <p>错误的链接, 下载失败, 即将跳转回原页面</p>
            </body>
           </html>';
}
if ( isset( $_GET[ 'fid' ] ) && !empty( $_GET[ 'fid' ] ) ){
    $fileName =  $_GET[ 'fid' ] . ".xlsx";
    $path = dirname(__FILE__)  . Config::const_ExportFileTmpDir . $fileName;

    if ( file_exists( $path ) ){
        $result = file_get_contents( $path );
        if ( $result === false ){
            DoFailedAction();
        } else{
            header("Expires: 0");
            header("Content-Type: application/octet-stream");
            header("Content-Disposition: attachment;filename={$fileName}");
            header("Content-Transfer-Encoding: binary ");

            file_put_contents( 'php://output', $result );
        }
    } else{
        DoFailedAction();
    }
} else{
    DoFailedAction();
}