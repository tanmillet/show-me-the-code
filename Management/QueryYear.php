<?php

require "Classes/Database/DatabaseConnector.php";

header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

$instance = DatabaseConnector::getInstance();
$outArray = array( "ContestTime" => "" );
$instance->queryStatementByGettingResult( "select distinct ContestTime from ContestPaper", $outArray);

$years = array();
foreach ( $outArray as $each ){
    array_push( $years, $each["ContestTime"] );
}
$yearJSONString = json_encode( $years );

echo "{ \"result\":\"success\", \"years\":{$yearJSONString}}";





