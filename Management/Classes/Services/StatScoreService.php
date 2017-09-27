<?php
error_reporting(0);

require_once dirname(__FILE__) ."/../Database/DatabaseConnector.php";
require_once dirname(__FILE__) ."/../Objects/ApplyContestant.php";
require_once dirname(__FILE__) ."/../Objects/ContestPaper.php";

class StatScoreCreator implements ICreatable{
    public function createByResult( $result ){
        if ( $result->num_rows > 0 ){
            $row = $result->fetch_array();

            return $row['ScoreDetail'];
        } else{
            return null;
        }
    }
}
/// Ver 1.0 Log
//
// for current version, each entry of stat table is denominated by two fields : 'paperID' & 'region'
//
class StatContext{
    public $_currentPaperID;
    public $_currentPaper;
    public $_currentQues;
    public $_currentDomains;
    public $_currentQuesIndexes;
    public $_currentDomainIndexes;
    public $_currentStatResult;

    public $_currentContestant;
    public $_currentRegionStatResult;

    public function __construct( $paperID ){
        $this->_currentPaperID = $paperID;
        $this->_currentPaper = null;
        $this->_currentDomains = null;
        $this->_currentQues = null;
        $this->_currentQuesIndexes = null;
        $this->_currentDomainIndexes = null;
        $this->_currentStatResult = null;

        $this->_currentContestantScore = null;
        $this->_currentRegionStatResult = null;
    }
}

class StatScoreService {
    const const_StatScoresTableName = 'statscores';

    private $m_inputContestants;
    private $m_outputForPapers;
    private $m_statPapers;
    private $m_successIDs;
    private $m_failedIDs;   // only for upload scores

    private $m_currentContext;

    // if for upload, $inputContestants would be a map paperID => contestants
    public function __construct( Array $inputContestants ){
       $this->m_inputContestants = $inputContestants;

        $this->m_outputForPapers = Array();
        $this->m_statPapers = Array();
        $this->m_successIDs = Array();
        $this->m_failedIDs = Array();

        $this->m_currentContext = null;
    }

    public function getFailedIDs(){
        return $this->m_failedIDs;
    }

    // the raw score has been decoded
    public function startUpdateStat(){
        foreach ( $this->m_inputContestants as $index => $contestants ){
            $this->m_currentContext = new StatContext( $index );
            if ( !$this->loadCurrentContext() ){
                continue;
            }

            $statResult = &$this->m_currentContext->_currentStatResult;
            foreach ( $contestants as $eachContestantWrapper ){

                $eachContestant = $eachContestantWrapper[ 0 ];
                $actionType = $eachContestantWrapper[ 1 ];

                if ( empty( $eachContestant ) || empty( $actionType ) ){
                    // log
                    continue;
                }

                $contestantID = $eachContestant->getApplyID();
                $storedContestant = ApplyContestant::loadFromDatabaseByID( $contestantID );
                if ( !empty( $storedContestant ) ){
                    if ( $storedContestant->getPaperID() == $index ){
                        //check the same paper
                        if ( $actionType == 'update'){
                            // check the one
                            $currentScores = json_decode( $eachContestant->_scoreRawDetail, TRUE ) ;
                            $this->m_currentContext->_currentContestantScore = $currentScores;
                            if ( $this->validateUpdateScores( $eachContestant->_score ) ){
                                // remove the one
                                if ( $eachContestant->updateByID() ){
                                    $this->m_currentContext->_currentContestantScore = json_decode( $storedContestant->_scoreRawDetail, TRUE );
                                    $this->m_currentContext->_currentRegionStatResult = &$statResult[ $storedContestant->_school ];
                                    $this->deleteStatData();

                                    $this->m_currentContext->_currentContestantScore = $currentScores;
                                    $this->m_currentContext->_currentRegionStatResult = &$statResult[ $eachContestant->_school ];
                                    $this->addStatData();

                                    array_push( $this->m_successIDs, $contestantID );
                                }
                            }
                        } else if ( $actionType == 'delete' ){
                            if ( ApplyContestant::deleteFromDatabase( Array( "ApplyID='$contestantID'") ) ){
                                $currentScores = json_decode( $storedContestant->_scoreRawDetail, true );

                                $this->m_currentContext->_currentContestantScore = $currentScores;
                                $this->m_currentContext->_currentRegionStatResult = &$statResult[ $storedContestant->_school ];

                                $this->deleteStatData();
                                array_push( $this->m_successIDs, $contestantID );
                            }
                        } else{
                            // error
                        }
                    } else{
                        // error
                    }
                }
            }

            $this->doSumStat();
            $this->m_outputForPapers[ $index ] = $this->m_currentContext->_currentStatResult;
            //clear context
            $this->m_currentContext = null;
        }
    }

    public function startStat(){
        foreach ( $this->m_inputContestants as $index => $contestants ){
            $this->m_currentContext = new StatContext( $index );
            // load current context
            if ( !$this->loadCurrentContext() ){
                foreach ( $contestants as $eachFailedContestant ){
                    if ( !empty( $eachFailedContestant ) ){
                        array_push( $this->m_failedIDs, $eachFailedContestant->getApplyID() );
                    }
                }
                continue;
            }

            $statResult = &$this->m_currentContext->_currentStatResult;
            foreach ( $contestants as $contestantIndex => $eachContestant ){
                $applyID = $eachContestant->getApplyID();

                $this->m_currentContext->_currentContestantScore = json_decode( $eachContestant->_scoreRawDetail, TRUE );
                $this->m_currentContext->_currentRegionStatResult = &$statResult[ $eachContestant->_school ];

                if ( !$this->validateUpdateScores( $eachContestant->_score ) ){
                    array_push( $this->m_failedIDs, $applyID );
                    continue;
                }

                $result = $eachContestant->writeToDatabase();
                if ( $result === TRUE ){
                    $this->addStatData();
                    array_push( $this->m_successIDs, $applyID );
                } else if ( $result instanceof ApplyContestant ){
                    $resultPaperID = $result->getPaperID();
                    // paper the same
                    if ( $resultPaperID ==  $eachContestant->getPaperID() ){
                        if ( $eachContestant->updateByID() ){
                            $this->addStatData();

                            $this->m_currentContext->_currentContestantScore = json_decode( $result->_scoreRawDetail, TRUE );
                            $this->m_currentContext->_currentRegionStatResult = &$statResult[ $result->_school ];
                            $this->deleteStatData();

                            array_push( $this->m_successIDs, $applyID );
                        } else{
                            array_push( $this->m_failedIDs, $applyID );
                        }
                    } else{
                        if ( $eachContestant->fullUpdateByID() ){
                            $storedCurrentContext = $this->m_currentContext;
                            $this->m_currentContext = new StatContext( $resultPaperID );
                            if ( !$this->loadCurrentContext() ){
                                array_push( $this->m_failedIDs, $applyID );
                                continue;
                            }

                            $this->m_currentContext->_currentContestantScore = json_decode( $result->_scoreRawDetail, TRUE );
                            $currentStateResult = &$this->m_currentContext->_currentStatResult;
                            $this->m_currentContext->_currentRegionStatResult = &$currentStateResult[ $result->_school ];
                            $this->deleteStatData();

                            $this->doSumStat();
                            $this->m_outputForPapers[ $resultPaperID ] = $this->m_currentContext->_currentStatResult;

                            // switch back
                            $this->m_currentContext = $storedCurrentContext;

                            $this->addStatData();

                            array_push( $this->m_successIDs, $applyID );
                        } else{
                            array_push( $this->m_failedIDs, $applyID );
                        }
                    }
                } else{
                    array_push( $this->m_failedIDs, $applyID );
                }
            }

            // final stat
            $this->doSumStat();
            $this->m_outputForPapers[ $index ] = $this->m_currentContext->_currentStatResult;
            //clear context
            $this->m_currentContext = null;
        }
    }

    // call within loop
    private function doSumStat(){
        unset( $this->m_currentContext->_currentRegionStatResult );
        $this->m_currentContext->_currentRegionStatResult = array();

        $sumDetail = &$this->m_currentContext->_currentRegionStatResult;
        // calculate the sum & remove unnecessary field
        foreach ( $this->m_currentContext->_currentStatResult as $schoolName => $eachSchoolRankDetail ){
            foreach ($this->m_currentContext->_currentQuesIndexes as $eachIndex ){
                $sumDetail[ $eachIndex ][ 'avgscore' ] += $eachSchoolRankDetail[ $eachIndex ][ 'avgscore' ];
            }
            // add domain sum values
            foreach ( $this->m_currentContext->_currentDomainIndexes as $eachIndex ){
                $sumDetail[ $eachIndex ][ 'avgcount' ] += $eachSchoolRankDetail[ $eachIndex ][ 'avgcount' ];
            }

            $sumDetail["totalcount"] += $eachSchoolRankDetail["totalcount"];
        }


        $this->m_currentContext->_currentStatResult[ '全省' ] = $this->m_currentContext->_currentRegionStatResult;
    }

    private function updateStatData( $existedScores ){
        $scores = $this->m_currentContext->_currentContestantScore;
        $regionStat = &$this->m_currentContext->_currentRegionStatResult;
        foreach ( $this->m_currentContext->_currentQuesIndexes as $eachIndex ){
            $regionStat[ $eachIndex ][ 'avgscore' ]
                += ( $scores[ $eachIndex ][ 'score' ] - $existedScores[ $eachIndex ][ 'score' ] );
        }
        // add domain sum values
        foreach ( $this->m_currentContext->_currentDomainIndexes as $eachIndex ){
            $regionStat[ $eachIndex ][ 'avgcount' ]
                += ( $scores[ $eachIndex ][ 'count' ] - $existedScores[ $eachIndex ][ 'count' ] );
        }
    }

    private function addStatData(){
        $scores = $this->m_currentContext->_currentContestantScore;
        $regionStat = &$this->m_currentContext->_currentRegionStatResult;

        foreach ( $this->m_currentContext->_currentQuesIndexes as $eachIndex ){
            $regionStat[ $eachIndex ][ 'avgscore' ] += $scores[ $eachIndex ][ 'score' ];
        }
        // add domain sum values
        foreach ( $this->m_currentContext->_currentDomainIndexes as $eachIndex ){
            $regionStat[ $eachIndex ][ 'avgcount' ] += $scores[ $eachIndex ][ 'count' ];
        }

        $regionStat["totalcount"] += 1;
    }

    private function validateUpdateScores( $totalScore ){
        $currentScores = $this->m_currentContext->_currentContestantScore;
        $paperQues = $this->m_currentContext->_currentQues;
        $paperDomains = $this->m_currentContext->_currentDomains;
		
        foreach ( $this->m_currentContext->_currentQuesIndexes as $eachIndex ){
            if ( array_key_exists( $eachIndex, $currentScores ) ){
                $score = $currentScores[ $eachIndex ][ 'score' ];
                if ( !is_numeric( $score ) ){
                    return FLASE;
                }
                $totalScore -= $score;
                if ( $score < 0 || $score > $paperQues[ $eachIndex ][ 'score' ] ){
                    return FLASE;
                }
            } else{
                return FLASE;
            }
        }

        if ( $totalScore != 0 ){
            return FALSE;
        }

        // add domain sum values
        foreach ( $this->m_currentContext->_currentDomainIndexes as $eachIndex ){
            if ( array_key_exists( $eachIndex, $currentScores ) ){
                $count = $currentScores[ $eachIndex ][ 'count' ];
                if ( !is_numeric( $count ) ){
                    return FLASE;
                }
                if ( $count < 0 || $count > $paperDomains[ $eachIndex ][ 'count' ] ){
                    return FALSE;
                }
            } else{
                return FLASE;
            }
        }
        return TRUE;
    }


    private function deleteStatData(){
        $scores = $this->m_currentContext->_currentContestantScore;
        $regionStat = &$this->m_currentContext->_currentRegionStatResult;

        foreach ( $this->m_currentContext->_currentQuesIndexes as $eachIndex ){
            $regionStat[ $eachIndex ][ 'avgscore' ] -= $scores[ $eachIndex ][ 'score' ];
        }
        // add domain sum values
        foreach ( $this->m_currentContext->_currentDomainIndexes as $eachIndex ){
            $regionStat[ $eachIndex ][ 'avgcount' ] -= $scores[ $eachIndex ][ 'count' ];
        }

        $regionStat["totalcount"] -= 1;
    }

    private function addRegionStatData( $addedRegionStat ){
        $regionStat = &$this->m_currentContext->_currentRegionStatResult;

        foreach ( $this->m_currentContext->_currentQuesIndexes as $eachIndex ){
            $regionStat[ $eachIndex ][ 'avgscore' ] += $addedRegionStat[ $eachIndex ][ 'avgscore' ];
        }
        // add domain sum values
        foreach ( $this->m_currentContext->_currentDomainIndexes as $eachIndex ){
            $regionStat[ $eachIndex ][ 'avgcount' ] += $addedRegionStat[ $eachIndex ][ 'avgcount' ];
        }

        $regionStat[ 'totalcount' ] += $addedRegionStat[ 'totalcount' ];
    }

    // paper ID can not be null
    private function loadCurrentContext(){
        $currentID = $this->m_currentContext->_currentPaperID;
        if ( array_key_exists( $currentID, $this->m_statPapers ) ){
            $this->m_currentContext->_currentPaper = $this->m_statPapers[ $currentID ];
        } else{
            $this->m_currentContext->_currentPaper = ContestPaper::loadFromDatabase( $currentID );
            $this->m_statPapers[ $currentID ] =  $this->m_currentContext->_currentPaper;
        }

        $currentPaper = $this->m_currentContext->_currentPaper;
        if ( empty( $currentPaper ) ){
            return false;
        }
        // JSON decode the paper template
        $this->m_currentContext->_currentQues = json_decode( $currentPaper->_questionTypeTemplate, true );
        $this->m_currentContext->_currentDomains  = json_decode( $currentPaper->_domainTemplate, true );

        // indexes
        $this->m_currentContext->_currentQuesIndexes = $this->m_currentContext->_currentQues[ "index" ];
        $this->m_currentContext->_currentDomainIndexes = $this->m_currentContext->_currentDomains[ "index" ];

        if ( array_key_exists( $currentID, $this->m_outputForPapers ) ){
            $this->m_currentContext->_currentStatResult = &$this->m_outputForPapers[ $currentID ];
        } else{
            $this->m_currentContext->_currentStatResult = array();
            $this->m_outputForPapers[ $currentID ] = $this->m_currentContext->_currentStatResult;
        }

        return true;
    }

    ///
    // if failed, returns null
    public static function obtainAvgStatScores( $paperID, $region ){
        if ( !empty( $paperID ) && !empty( $region ) ){
            $dbConnector = DatabaseConnector::getInstance();

            $statQueryString = "select * from " . self::const_StatScoresTableName . " where PaperID=$paperID and Region='$region'";
            return $dbConnector->queryStatement( $statQueryString, new StatScoreCreator() );
        } else{
            return null;
        }
    }

    // if existed add offset
    // otherwise insert new
    public function writeToDatabase(){
        if ( count( $this->m_outputForPapers ) != 0 ){
            // write into database
            $dbConnector = DatabaseConnector::getInstance();

            foreach ( $this->m_outputForPapers as $eachPaperID => $regionsStat ){
                $this->m_currentContext = new StatContext( $eachPaperID );

                foreach( $regionsStat as $regionName => $eachRegionStat ){
                    $statQueryString = "select * from " . self::const_StatScoresTableName . " where PaperID=$eachPaperID and Region='$regionName'";
                    $isExisted = $dbConnector->queryStatement( $statQueryString, new StatScoreCreator() );

                    if ( empty( $isExisted ) ){
						$decodedRegionStat = str_replace( '\\u', '\\\\u', json_encode( $eachRegionStat ));
                        $insertString = array(
                            'PaperID' => $eachPaperID,
                            'Region' => "'$regionName'",
                            'ScoreDetail' => "'$decodedRegionStat'"
                        );
                        $dbConnector->executeInsert( self::const_StatScoresTableName, $insertString );
                    } else{
                        if ( empty( $this->m_currentContext->_currentPaper ) ){
                            $this->m_currentContext->_currentPaper = array_key_exists( $eachPaperID, $this->m_statPapers ) ? $this->m_statPapers[ $eachPaperID ]
                                : ContestPaper::loadFromDatabase( $eachPaperID );

                            if ( !empty( $this->m_currentContext->_currentPaper ) ){
                                $currentPaper = $this->m_currentContext->_currentPaper;

                                $quesTemplate = json_decode( $currentPaper->_questionTypeTemplate, true );
                                $domainTemplate = json_decode( $currentPaper->_domainTemplate, true );

                                $this->m_currentContext->_currentQuesIndexes = $quesTemplate["index"];
                                $this->m_currentContext->_currentDomainIndexes = $domainTemplate["index"];
                            }
                        }

                        if ( empty( $this->m_currentContext->_currentPaper ) ){
                            // error
                        } else{
                            $this->m_currentContext->_currentRegionStatResult = $eachRegionStat;
                            $isExistedRegionStatResult = json_decode( $isExisted, TRUE );
                            $this->addRegionStatData( $isExistedRegionStatResult );

							$decodedRegionStat = str_replace( '\\u', '\\\\u', json_encode( $this->m_currentContext->_currentRegionStatResult ));
                            $setValues = array(
                                "ScoreDetail='$decodedRegionStat'"
                            );
                            $conditions = array(
                                "PaperID=$eachPaperID",
                                "Region='$regionName'"
                            );

                            $dbConnector->executeUpdate( self::const_StatScoresTableName, $setValues, $conditions );
                            $this->m_currentContext->_currentRegionStatResult = null;
                        }
                    }
                }

                $this->m_currentContext = null;
            }
        }
    }

    public function getSuccessIDs(){
        return $this->m_successIDs;
    }

    public static function clearStatScores( $paperID, array $region = null ){
        // clear all
        $dbConnector = DatabaseConnector::getInstance();

        $deleteString = "delete from " . self::const_StatScoresTableName. " where PaperID='$paperID'";
        if ( !empty( $region ) ){
            array_push( $region, "'全省'" );

            $regionCountExcludeLast = count( $region ) - 1;

            $deleteString .= " and Region in(";
            for ( $eachIndex = 0; $eachIndex < $regionCountExcludeLast; ++$eachIndex ){
                $deleteString .= "'$region[$eachIndex]',";
            }
            $deleteString .= "'$region[$regionCountExcludeLast]'";
        }

        return $dbConnector->executeStatement( $deleteString );
    }
}