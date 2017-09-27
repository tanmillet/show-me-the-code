<?php

date_default_timezone_set('PRC');

require_once "GlobalConfig.php";
require_once "Classes/Services/ExportService.php";
require_once "Classes/PHPExcel/Writer/Abstract.php";
require_once "Classes/PHPExcel/Writer/Excel2007.php";

if ( !empty( $_GET ) ){
    $year = null;
    $grade = null;

    if ( isset( $_GET[ "year" ] ) && !empty( $_GET[ "year" ] ) ){
        $year = $_GET[ "year" ];
    }

    if ( isset( $_GET[ "grade" ] ) && !empty( $_GET[ "grade" ] ) ){
        $grade = $_GET[ "grade" ];
    }

    if ( !empty( $year ) && !empty( $grade ) ){
        $result = ExportService::ExportExcel( $year, $grade );
        if ( !empty( $result ) && $result instanceof PHPExcel ){
            $timeStamp = time();

            $filename = $timeStamp. mt_rand();
            $fileFullName = $filename. '.xlsx';

            $writer = new PHPExcel_Writer_Excel2007( $result );
            $writer->setOffice2003Compatibility( true );

            $writer->save( dirname(__FILE__) . Config::const_ExportFileTmpDir . $fileFullName );

            echo "{ \"result\": \"success\", \"fid\": \"{$filename}\" }";
        } else{
            echo '{ "result": "failed", "message": "错误,请尝试重新下载" }';
        }
    } else{
        echo '{ "result": "failed", "message": "错误,请尝试重新下载" }';
    }
} else{
    echo '{ "result": "failed", "message": "错误,未给定下载参数" }';
}