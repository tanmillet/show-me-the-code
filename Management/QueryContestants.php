<?php
require_once 'Classes/Objects/ApplyContestant.php';

header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

if ( count( $_GET ) > 0 ){
    $page = 0;
    $pageUnit = 50;

    $queryCond = array();
    if ( isset( $_GET['name'] ) && !empty( $_GET['name'] ) ){
        $name = $_GET['name'];
        array_push( $queryCond, "Name like '%$name%'" );
    }
    if ( isset( $_GET['grade'] ) && !empty( $_GET['grade'] ) ){
        $grade = $_GET['grade'];
        array_push( $queryCond, "Grade='$grade'" );
    }
    if ( isset( $_GET['applyID'] ) && !empty( $_GET['applyID'] ) ){
        $applyID = $_GET['applyID'];
        array_push( $queryCond, "ApplyID like '%$applyID%'" );
    }
    if ( isset( $_GET['score'] ) && !empty( $_GET['score'] ) ){
        $score = $_GET['score'];
        $range = explode( "," , $score );
        if ( count( $range ) == 2 ){
            $lower = $range[ 0 ];
            $higher = $range[ 1 ];
            array_push( $queryCond, "Score >= $lower and Score < $higher" );
        }
    }
    if ( isset( $_GET['page'] ) && is_numeric( $_GET['page'] ) ){
        $page = $_GET['page'];
    } else{
        $page = 0;
    }

    if ( !isset( $_GET['unit'] ) || empty( $_GET['unit'] ) ){
        $pageUnit = 50;
    }

    $keyword = null;
    $result = null;
    if ( isset( $_GET['keyword'] ) && !empty( $_GET['keyword'] ) ){
        $keyword = $_GET['keyword'];
        $result = ApplyContestant::loadFromDatabaseByKeyword( $keyword, $page * $pageUnit, ( $page + 1 ) * $pageUnit );
    } else{
        if ( count( $queryCond ) > 0 ){
            $result = ApplyContestant::loadFromDatabaseByConditions( $queryCond, $page * $pageUnit, ( $page + 1 ) * $pageUnit );
        } else{
            echo '{ "result":"failed","errMsg":"无查询结果","errCode":0}';
        }
    }

    if ( !empty( $result ) && count( $result ) == 2 && !empty( $result[ 0 ] ) ){
        $contestants = $result[ 0 ];
        $count = $result[ 1 ];
        if ( $count > 0 ){
            $data = '{ "result":"success","pageNumber":'. ceil( $result[ 1 ] / $pageUnit ) . ', "data":[';
            $contestantCountExcludeLast = count( $contestants ) - 1;
            // $contestants = $result[ 0 ];
            for ( $index = 0; $index < $contestantCountExcludeLast; ++$index ){
                $eachContestant = ($contestants[ $index ]);
                $data .= $eachContestant->toJSON();
                $data .= ',';
            }

            $data .= ( $contestants[ $contestantCountExcludeLast ]->toJSON() .']}' );
            echo ( $data );
        } else{
            echo '{ "result":"failed","errMsg":"无查询结果","errCode":0}';
        }
    }

} else{
    header( "HTTP/1.0 404 Not Found" );
    echo '{ "result":"failed","errMsg":"给定的参数错误","errCode":1}';
}



