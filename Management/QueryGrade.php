<?php

require "Classes/Database/DatabaseConnector.php";

header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

if ( isset($_GET['year']) && !empty( $_GET['year'] ) ){
    $year = $_GET['year'];

    $instance = DatabaseConnector::getInstance();
    $outArray = array( "ForGrade" => "" );
    $instance->queryStatementByGettingResult( "select distinct ForGrade from ContestPaper where ContestTime='{$year}'", $outArray );

    $grades = array();
    foreach ( $outArray as $each ){
        array_push( $grades, $each["ForGrade"] );
    }
    $gradeJSONString = json_encode( $grades );
    echo "{\"result\":\"success\", \"grades\":{$gradeJSONString}}";
} else{
    echo '{"result":"failed", "message":"no result"}';
}

