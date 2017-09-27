<?php
session_start();

require_once "Classes/Objects/ApplyContestant.php";

if ( isset( $_POST['queryId'] ) && !empty( $_POST['queryId'] )
    && isset( $_POST['queryName'] ) && !empty( $_POST['queryName'] ) ){
    $contestant = ApplyContestant::loadFromDatabase( $_POST['queryId'], $_POST['queryName'] );
    $resultURL = null;
    if ( !empty( $contestant ) ){
        $_SESSION['contestant'] = serialize( $contestant );
        unset( $_SESSION['_flagQueryFailed'] );

        $resultURL = "./QueryResult.php";
    } else{
        // output no query result
        $_SESSION['_flagQueryFailed'] = true;
        $resultURL = "./QueryScore.php";
    }
    header("Location: {$resultURL}");
    exit();
} else{
    header("Location: ./QueryScore.php");
    session_unset();
    session_destroy();
    exit();
}




