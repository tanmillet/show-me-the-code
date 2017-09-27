<?php

require "/GlobalConfig.php";
require_once "Classes/ExcelUploader/UploadHandler.php";
require_once "Classes/ExcelUploader/ValueExcelParser.php";
require_once "Classes/Objects/ContestPaper.php";

$scoreFilesDir = dirname(__FILE__) .Config::const_UploadTmpDir;

$handler = new UploadHandler( $scoreFilesDir, 'commentFile' );
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
                // find papers
                $papers = ContestPaper::loadFromDatabaseByConditions( array( "ContestTime='$year'") );

                if ( !empty( $papers ) ){
                    $parser = new ValueExcelParser( $path, $year );

                    $comments = $parser->startParse();

                    $leftComments = null;
                    $successPapers = array();
                    if ( array_key_exists( "其他年级", $comments ) ){
                        $leftComments = $comments[ "其他年级" ] ;
                    }

                    foreach ( $papers as $eachPaper ){
                        $grade = $eachPaper->_forGrade;
                        if ( array_key_exists( $grade, $comments ) ){
                            $eachPaper->_comment = $comments[ $grade ];
                            if ( $eachPaper->updateCommentToDatabase() ){
                                array_push( $successPapers, $grade );
                            }

                        } else{
                            if ( !empty( $leftComments ) ){
                                $eachPaper->_comment = $leftComments;
                                if ( $eachPaper->updateCommentToDatabase() ){
                                    array_push( $successPapers, $grade );
                                }
                            }
                        }
                        $gradeMapsPaper[ $grade ] = $eachPaper;
                    }

                    $message = "已完成";
                    $successCount = count( $successPapers );
                    if ( $successCount > 0 ){
                        $excludeLast = $successCount - 1;
                        for ( $index = 0; $index < $excludeLast; ++$index ){
                            $message .= ( $successPapers[ $index ] . "、" );
                        }
                        $message .= $successPapers[ $excludeLast ];
                    }
                    echo( '{"result":"success", "message":"已完成' .$message .'的评语上传"}');
                } else{
                    echo( '{"result":"failed", "message":"未上传试卷模版，请先上传试卷模版"}');
                }
            } catch ( Exception $err ){
                echo( '{"result":"failed", "message": "'+ $err->getMessage() + '"}');
            }
        } else{
            echo( '{"result":"failed", "message":"上传失败，请尝试重新上传"}');
        }
    } else{
        echo( '{"result":"failed", "message":"文件名中不存在年份信息或是年份信息不合法，请在文件名中添加正确的XXXX年"}');
    }
} else{
    echo( '{"result":"failed", "message":"上传失败，请尝试重新上传"}');
}
