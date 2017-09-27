<?php
session_start();

date_default_timezone_set('PRC');

require_once "GlobalConfig.php";
require_once "Classes/ExcelUploader/UploadHandler.php";
require_once "Classes/ExcelUploader/ScoreExcelParser.php";
require_once "Classes/Services/StatScoreService.php";

$scoreFilesDir = dirname(__FILE__) . Config::const_UploadTmpDir;

$handler = new UploadHandler( $scoreFilesDir, 'scoreFile' );
$handler->handleUploading();

$fileName = $handler->getFileName();
if ( !empty( $fileName ) ){
    $yearReg = "/([\\d]{4})(年)/";
    $matched = array();
    preg_match( $yearReg,  $fileName, $matched );

    $year = null;
    if ( count( $matched ) == 3
        && strlen( $matched[ 1 ] ) == 4
        && $matched[ 1 ] >= 2000 && $matched[ 1 ] <= 2100 ){
        $year = $matched[ 1 ];

        $path = $handler->getMovedPath();
        if ( !empty( $path ) ){
            try{
                $parser = new ScoreExcelParser( $path, $year );

                $service = new StatScoreService( $parser->startParse() );
                $service->startStat();
                $service->writeToDatabase();

                $succIDs = $service->getSuccessIDs();
                $succCount = count( $succIDs );

                $failedIds = array_merge( $parser->getFailedMessages(), $service->getFailedIDs() );
                $failedCount = count( $failedIds );

                $count = $parser->getProcessCount();
                //
                $message = "已完成，总计处理{$count}个，成功处理{$succCount}个<br>";

                $isShowFailed = ( $failedCount > 0 && ( $failedCount / $count ) * 100 <= 25 );
                $outputIDs = $isShowFailed == true ?
                    $failedIds : $succIDs;

                if ( count( $outputIDs ) > 0 ){
                    $message .= $isShowFailed == true ?
                        "如下为*失败*列表:<br>" : "如下为处理成功列表:<br>";

                    foreach ( $outputIDs as $index => $id ){
                        if ( ( $index + 1 ) % 4 == 0 ){
                            $message .= ( "[$id] <br>" );
                        } else{
                            $message .= ( "[$id] " );
                        }
                    }
                }

                echo '{"result":"success","message":"' . $message . '"}';
            } catch ( Exception $err ){
                echo( '{"result":"failed", "message":"'. $err->getMessage() .'"}');
            }
        } else{
            echo( '{"result":"failed", "message":"上传失败，请尝试重新上传"}');
        }

    } else{
        echo( '{"result":"failed", "message":"文件名中不存在年份信息，请在文件名中添加XXXX年"}');
    }
} else{
    echo( '{"result":"failed", "message":"上传失败，请尝试重新上传"}');
}

