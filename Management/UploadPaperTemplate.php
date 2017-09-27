<?php
session_start();

date_default_timezone_set('PRC');

require_once "Classes/ExcelUploader/UploadHandler.php";
require_once "Classes/ExcelUploader/PaperExcelParser.php";
require_once "Classes/Objects/ContestPaper.php";
require_once "Classes/Services/StatScoreService.php";

$paperFilesDir = dirname(__FILE__) . Config::const_UploadTmpDir;

$handler = new UploadHandler( $paperFilesDir, 'paperFile' );
$handler->handleUploading();

$path = $handler->getMovedPath();
//$path = "F:\\projects\\management\\management\\doc\\papera.xlsx";
if ( !empty( $path ) ){
    try{
        $paperParser = new PaperExcelParser( $path );
        $paperParser->startParsing();
        $paperParser->parseOutMetaData();

        $papers = $paperParser->getPapers();

        $finalMessage = "";
        $successArray = array();
        foreach ( $papers as $index => $eachPaper ){
            $result = $eachPaper->writeToDatabase();

            if ( $result === true ){
                array_push( $successArray, $eachPaper->_forGrade );
            } else if ( $result instanceof ContestPaper ){
                $paperID = $result->getPaperID();

                $result->_domainTemplate = $eachPaper->_domainTemplate;
                $result->_questionTypeTemplate = $eachPaper->_questionTypeTemplate;
                $result->_fullScore = $eachPaper->_fullScore;
                if ( $result->updateById() ){
                    if ( StatScoreService::clearStatScores( $paperID )
                    && ApplyContestant::deleteFromDatabase( array( "PaperID=$paperID" ) ) ){
                        array_push( $successArray, $eachPaper->_forGrade );
                    }
                }
            } else{
                echo( '{"result":"failed"}');
                break;
            }
        }

        if ( !empty( $successArray ) ){
            $finalMessage = join( "、", $successArray );
            echo( '{"result":"success","message":"已完成'.$finalMessage  .'的上传工作"}');
        } else{
            echo( '{"result":"success","message":"没有试卷被上传"}');
        }
    } catch( Exception $err ){
        echo( '{"result":"failed","message":"'. $err->getMessage() .'"}');
    }
} else{
    echo( '{"result":"failed","message":"服务器上传文件失败"}');
}








