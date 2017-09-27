<?php
require "Classes/Services/StatScoreService.php";

$updateData = file_get_contents( 'php://input' );
if ( !empty( $updateData ) ){

    $jsonData = json_decode( $updateData, true );

    /*
     * [{ "action":, "applyID":, "detail": }]
     *
     */

    if ( is_array( $jsonData ) ){
        $contestantsForPapers = array();

        foreach ( $jsonData as $eachData ){
            $action = $eachData[ 'action' ];
            if ( empty( $action ) ){
                continue;
            }

            $contestant = null;
            if ( $action == 'update' ){
                $applyID = $eachData[ 'applyID' ];
                $name = $eachData[ 'name' ];
                $school = $eachData[ 'school' ];
                $score = $eachData[ 'score' ];
                $paperID = $eachData[ 'paperID' ];

                if ( empty( $applyID ) || empty( $name ) || empty( $school )
                    || empty( $score ) || empty( $paperID ) ){
                    continue;
                }

                $detail = json_encode( $eachData[ 'detail' ] );

                $contestant = new ApplyContestant( $applyID, $name, null, $school, $detail, $score, $paperID, null );
            } else if ( $action == 'delete' ){
                $applyID = $eachData[ 'applyID' ];
                $paperID = $eachData[ 'paperID' ];
                if ( empty( $applyID ) || empty( $paperID ) ){
                    continue;
                }

                $contestant = new ApplyContestant( $applyID, null, null, null, null, null, $paperID, null );
            } else{
                continue;
            }

            if ( !empty( $contestant ) ){
                if ( !array_key_exists( $paperID, $contestantsForPapers ) ){
                    $contestantsForPapers[ $paperID ] = array();
                }
                array_push( $contestantsForPapers[ $paperID ], array( $contestant, $action ) );
            }
        }

        if ( count( $contestantsForPapers ) > 0 ){
            $service = new StatScoreService( $contestantsForPapers );
            $service->startUpdateStat();
            $service->writeToDatabase();

            $ids = $service->getSuccessIDs();
            //
            $set = array();
            foreach ( $ids as $index => $id ){
                array_push( $set, $id );
            }
            echo '{"result":"success","set":' . json_encode( $set ) . '}';
        }
    } else{
        echo '{"result":"failed","message":"no data"}';
    }
} else{
    echo '{"result":"failed","message":"no data"}';
}
