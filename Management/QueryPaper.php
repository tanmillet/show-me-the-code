<?php
require_once "Classes/Objects/ContestPaper.php";

header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

$paperID = $_GET['pid'];
if ( isset( $paperID ) && !empty( $paperID ) ){
    $paper = ContestPaper::loadFromDatabase( $paperID );
    if ( !empty( $paper ) ){
        echo '{"result":"success","data":' .$paper->toJSON() .'}';
    } else{
        echo '{ "result":"failed","errMsg":"无此试卷模版","errCode":0}';
    }
} else{
    header( "HTTP/1.0 404 Not Found" );
    echo '{ "result":"failed","errMsg":"给定的参数错误","errCode":1}';
}