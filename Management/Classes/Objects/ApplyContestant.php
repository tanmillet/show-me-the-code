<?php
require_once dirname(__FILE__) . "/../Database/DatabaseConnector.php";

class ContestantCreator implements ICreatable{
    public function createByResult( $result ){
        if ( $result->num_rows > 0 ){
            // only read one matched constant
            // # in current circumstance, result oly contains one entry
            if ( $row = $result->fetch_array() ){
                return new ApplyContestant( $row['ApplyID'], $row['Name'], $row['Grade'], $row['School']
                    , $row['ScoreDetail'], $row['Score'], $row['PaperID'], $row['ContestTime'], $row['RankAmongSchool'], $row['RankAmongAll'] );

            } else{
                return null;
            }
        } else{
            return null;
        }
    }
}

class ContestantsCreator implements ICreatable{
    public function createByResult( $result ){
        if ( $result->num_rows > 0 ){
            $contestants = array();
            while( $row = $result->fetch_array() ){
                $newContestant = new ApplyContestant( $row['ApplyID'], $row['Name'], $row['Grade'], $row['School']
                    , $row['ScoreDetail'], $row['Score'], $row['PaperID'], $row['ContestTime'] );
                array_push( $contestants, $newContestant );
            }

            return $contestants;
        } else{
            return null;
        }
    }
}
/// Ver 1.0 Log
//
// each contestant may includes a paperID on account of it can be more easier to retrieving the paper template or stat data.
// normally, contest time & grade determinate a paper, in other word, you can use time & grade to fetch a paper instance.
//
class ApplyContestant {
    const const_ContestantTableName = 'contestant';

    public $_name = null;
    public $_grade = null;
    public $_school = null;

    // will be parsed in JS
    public $_scoreRawDetail = null;
    public $_contestTime = null;
    public $_score = null;

    private $m_paper = null;
    private $m_paperID = null;
    private $m_applyID = null;

    public function __construct( $id, $name, $grade, $school, $scoreRaw, $score, $paperID, $contestTime ){
        $this->m_applyID = $id;

        $this->_name = $name;
        $this->_grade = $grade;
        $this->_school = $school;
        $this->_score = $score;
        $this->_scoreRawDetail = $scoreRaw;
        $this->_contestTime = $contestTime;

        $this->m_paperID = $paperID;
    }

    public function getSchoolRank(){
        if ( !empty( $this->m_applyID ) && !empty( $this->m_paperID ) ){

            $dbConnector = DatabaseConnector::getInstance();

            $queryArray = array( "rank" => 0 );
            $userQueryString = "select count(*) as rank from " . self::const_ContestantTableName . " where Score> $this->_score and School='$this->_school' and PaperID=$this->m_paperID";
            $dbConnector->queryStatementByGettingResult( $userQueryString, $queryArray );

            return $queryArray[0]["rank"];
        }
        return 0;
    }

    public function getRegionRank(){
        if ( !empty( $this->m_applyID ) && !empty( $this->m_paperID ) ){

            $dbConnector = DatabaseConnector::getInstance();

            $queryArray = array( "rank" => 0 );
            $userQueryString = "select count(*) as rank from " . self::const_ContestantTableName . " where Score> $this->_score and PaperID=$this->m_paperID";
            $dbConnector->queryStatementByGettingResult( $userQueryString, $queryArray );

            return $queryArray[0]["rank"];
        }
        return 0;
    }

    public function getApplyID(){
        return $this->m_applyID;
    }

    public function toJSON(){
        return'{"applyID":"' . $this->m_applyID . '","name":"' .$this->_name . '","grade":"' .$this->_grade . '","school":"'
        . $this->_school .'","detail":'. ( $this->_scoreRawDetail != null ? $this->_scoreRawDetail : 'null' ) .',"score":' . ($this->_score == null ? 'null' : $this->_score).',"paperID":"' .$this->m_paperID .'"}';
    }

    // get the paper. if not loaded, read paper from database
    // if failed, returns null
    public function getPaper(){
        if ( !empty( $this->m_paper ) ){
            return $this->m_paper;
        } else{
            if ( !empty( $this->m_paperID ) ){
                $this->m_paper = ContestPaper::loadFromDatabase( $this->m_paperID );
                return $this->m_paper;
            } else{
                return null;
            }
        }
    }

    public function getPaperID(){
        return $this->m_paperID;
    }

    // true if add successfully
    // otherwise false
    public function writeToDatabase(){
        // object can not be incomplete
        $conn = DatabaseConnector::getInstance();

        $queryString = "select * from " . self::const_ContestantTableName . " where ApplyID='$this->m_applyID'";
        $isExistedContestant = $conn->queryStatement( $queryString, new ContestantCreator() );

        if ( !empty( $isExistedContestant ) ){
                return $isExistedContestant;
        } else{
            $detail = str_replace( '\\u', '\\\\u', $this->_scoreRawDetail );
            $valueMaps = array(
                'ApplyID' => "'$this->m_applyID'",
                'Name' => "'$this->_name'",
                'Grade' => "'$this->_grade'",
                'School' => "'$this->_school'",
                'ScoreDetail' => "'$detail'",
                'PaperID' => $this->m_paperID,
                'ContestTime' => "'$this->_contestTime'",
                'Score' => $this->_score
            );
            return $conn->executeInsert( self::const_ContestantTableName, $valueMaps );
        }
    }

    // grade and contest time can not be modified
    public function updateByID(){
        if ( !empty( $this->m_applyID ) ){
            $conn = DatabaseConnector::getInstance();

            $detail = str_replace( '\\u', '\\\\u', $this->_scoreRawDetail );
            $values = array(
                "Name='$this->_name'",
                "School='$this->_school'",
                "ScoreDetail='$detail'",
                "Score=$this->_score"
            );

            $cond = array( "ApplyID='$this->m_applyID'" );

            return $conn->executeUpdate( self::const_ContestantTableName, $values, $cond );
        }
        return false;
    }

    public function fullUpdateByID(){
        if ( !empty( $this->m_applyID ) ){
            $conn = DatabaseConnector::getInstance();

            $detail = str_replace( '\\u', '\\\\u', $this->_scoreRawDetail );
            $values = array(
                "Grade='$this->_grade'",
                "ContestTime='$this->_contestTime'",
                "PaperID=$this->m_paperID",
                "Name='$this->_name'",
                "School='$this->_school'",
                "ScoreDetail='$detail'",
                "Score=$this->_score"
            );

            $cond = array( "ApplyID='$this->m_applyID'" );

            return $conn->executeUpdate( self::const_ContestantTableName, $values, $cond );
        }
        return false;
    }

    // ###
    // function uploadScores($paperID, ... $scoreDetails )
    // function updateScores()? ? ?

    // if failed returns null
    public static function loadFromDatabase( $applyID, $name ){
        if ( !empty( $applyID ) && !empty( $name ) ){
            $dbConnector = DatabaseConnector::getInstance();

            $userQueryString = "select * from " . self::const_ContestantTableName . " where Name='$name' and ApplyID='$applyID'";
            return $dbConnector->queryStatement( $userQueryString, new ContestantCreator() );
        } else{
            return null;
        }
    }

    public static function loadFromDatabaseByID( $applyID ){
        if ( !empty( $applyID ) ){
            $dbConnector = DatabaseConnector::getInstance();

            $userQueryString = "select * from " . self::const_ContestantTableName . " where ApplyID='$applyID'";
            return $dbConnector->queryStatement( $userQueryString, new ContestantCreator() );
        } else{
            return null;
        }
    }

    public static function deleteFromDatabase( array $cond ){
        if ( !empty( $cond ) ){
            $dbConnector = DatabaseConnector::getInstance();
            return $dbConnector->executeDelete( self::const_ContestantTableName, $cond );
        } else{
            return false;
        }
    }

    public static function loadFromDatabaseByKeyword( $keyword, $limitStart = 0, $limitEnd = 0 ){
        if ( empty( $keyword ) ){
            return null;
        }

        $dbConnector = DatabaseConnector::getInstance();
        $queryString = self::const_ContestantTableName." WHERE ApplyID like '%$keyword%'or Name like '%$keyword%'or Grade like '%$keyword%' or School like '%$keyword%'";

        $suffix = "";
        if ( $limitStart < $limitEnd ){
            $diff = $limitEnd - $limitStart;
            $suffix = " LIMIT $limitStart, $diff";
        }

        $contestants = $dbConnector->queryStatement( "SELECT * FROM " .$queryString . $suffix , new ContestantsCreator() );
        $result = Array( "total" => 0 );
        $dbConnector->queryStatementByGettingResult( "SELECT count(*) as total FROM " . $queryString, $result );

        $total = 0;

        if ( count( $result) > 0 ){
            $total = $result[ 0 ][ "total" ];
        }

        return Array( $contestants, $total );
    }

    //
    public static function loadFromDatabaseByConditions( array $cond, $limitStart = 0, $limitEnd = 0 ){
        $dbConnector = DatabaseConnector::getInstance();
        $prefix = "select * from " . self::const_ContestantTableName;
        $queryString = "";

        $result = Array( "total" => 0 );
        $contestants = null;
        if ( !empty( $cond ) ){
            $queryString .= ' where ';

            $condCountExcludeLast = count( $cond ) - 1;
            foreach ( $cond as $condKey => $condValue ){
                if ( $condKey == $condCountExcludeLast ){
                    $queryString .= $condValue;
                } else{
                    $queryString .= "$condValue and";
                }
            }

            if ( $limitStart < $limitEnd ){
                $queryString .= " LIMIT $limitStart, $limitEnd";
            }

            $contestants = $dbConnector->queryStatement( $prefix . $queryString, new ContestantsCreator() );
            $dbConnector->queryStatementByGettingResult(  "select count(*) as total from " . self::const_ContestantTableName . $queryString, $result );
        }
        $total = 0;

        if ( count( $result) > 0 ){
            $total = $result[ 0 ][ "total" ];
        }

        return Array( $contestants, $total );
    }
}
