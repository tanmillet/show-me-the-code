<?php
require_once dirname(__FILE__) . "/../Database/DatabaseConnector.php";

class PaperCreator implements ICreatable{
    public function createByResult( $result ){
        if ( $result->num_rows > 0 ){
            $row = $result->fetch_array();

            return new ContestPaper( $row['PaperID'], $row['ContestTime'], $row['QuestionTypeTemplate'], $row['DomainTemplate']
                , $row['FullScore'], $row['ForGrade'], $row['Comment'] );
        } else{
            return null;
        }
    }
}

class PapersCreator implements ICreatable{
    public function createByResult( $result ){
        if ( $result->num_rows > 0 ){
            $papers = array();
            while ( $row = $result->fetch_array() ){
                array_push( $papers, new ContestPaper( $row['PaperID'], $row['ContestTime'], $row['QuestionTypeTemplate'], $row['DomainTemplate']
                    , $row['FullScore'], $row['ForGrade'], $row['Comment'] ) );
            }
            return $papers;
        } else{
            return null;
        }
    }
}
/// Ver 1.0 Log
//
// contestTime & forGrade can also determinate a paper, which is same to paper ID
// but for current version 1.0, use paper ID as primary key
// when upload or modify a paper, must check contestTime & forGrade those two values are
// exited in one paper entry first. if does, throw a upload error.
//
class ContestPaper {
    const const_PaperTableName = "contestpaper";

    public $_contestTime = null;
    public $_questionTypeTemplate = null;
    public $_domainTemplate = null;
    public $_fullScore = null;
    public $_forGrade = null;
    public $_comment = null;

    private $m_paperID = null;

    function __construct( $id, $time, $quesTemp, $domainTemp, $score, $grade, $comment = null ){
        $this->m_paperID = $id;

        $this->_contestTime = $time;
        $this->_questionTypeTemplate = $quesTemp;
        $this->_domainTemplate = $domainTemp;
        $this->_fullScore = $score;
        $this->_forGrade = $grade;
        $this->_comment = $comment;
    }

    public function getPaperID(){
        return $this->m_paperID;
    }

    // string concatenate
    // the inner JSON string will be appended
    public function toJSON(){
        return '{"paperID":"' .$this->m_paperID . '","contestTime":"'
            . $this->_contestTime . '","quesTemplate":' .$this->_questionTypeTemplate
            . ',"domainTemplate":' .$this->_domainTemplate .',"fullScore":' .$this->_fullScore
            . ',"forGrade":"' .$this->_forGrade .'"}';
    }

    public function updateCommentToDatabase(){
        if ( !empty( $this->m_paperID ) && !empty( $this->_comment ) ){
            $dbConnector = DatabaseConnector::getInstance();
            $setValues = array( "Comment='$this->_comment'" );
            $cond = array( "PaperID='$this->m_paperID'" );
            return $dbConnector->executeUpdate( self::const_PaperTableName, $setValues, $cond );
        } else{
            return false;
        }
    }

    // write OK returns true
    public function writeToDatabase(){
        if ( !empty( $this->_forGrade ) && !empty( $this->_contestTime ) ){
            $dbConnector = DatabaseConnector::getInstance();

            $paperQueryString = "select * from " . self::const_PaperTableName . " where ForGrade='$this->_forGrade' and ContestTime='$this->_contestTime'";
            $isExistedPaper = $dbConnector->queryStatement( $paperQueryString, new PaperCreator() );

            if ( !empty( $isExistedPaper ) ){
                return $isExistedPaper;
            } else{
                if ( !empty( $this->_questionTypeTemplate )
                    && !empty( $this->_domainTemplate ) ){

                    $insertArray = array(
                        'ContestTime' => "'$this->_contestTime'",
                        'QuestionTypeTemplate' => "'$this->_questionTypeTemplate'",
                        'DomainTemplate' => "'$this->_domainTemplate'",
                        'FullScore' => $this->_fullScore,
                        'ForGrade' => "'$this->_forGrade'"
                    );
                    return $dbConnector->executeInsert( self::const_PaperTableName, $insertArray );
                } else{
                    return false;
                }
            }
        } else{
            return false;
        }
    }

    public function updateByGradeAndTime(){
        if ( !empty( $this->_forGrade ) && !empty( $this->_contestTime ) ){
            $dbConnector = DatabaseConnector::getInstance();

            $updateArray = array(
                "QuestionTypeTemplate='$this->_questionTypeTemplate'",
                "DomainTemplate='$this->_domainTemplate'",
                "FullScore=$this->_fullScore",
            );

            $condition = array( "ForGrade='$this->_forGrade'", "ContestTime='$this->_contestTime'" );
            return $dbConnector->executeUpdate( self::const_PaperTableName, $updateArray, $condition );
        } else{
            return false;
        }
    }

    public function updateById(){
        if ( !empty( $this->m_paperID ) ){
            $dbConnector = DatabaseConnector::getInstance();

            $updateArray = array(
                "QuestionTypeTemplate='$this->_questionTypeTemplate'",
                "DomainTemplate='$this->_domainTemplate'",
                "FullScore=$this->_fullScore",
            );

            $condition = array( "PaperID=$this->m_paperID" );
            return $dbConnector->executeUpdate( self::const_PaperTableName, $updateArray, $condition );
        } else{
            return false;
        }
    }

    // read paper
    // if failed return null
    // otherwise returns a Paper instance
    public static function loadFromDatabase( $paperID ){
        if ( !empty( $paperID ) ){
            $dbConnector = DatabaseConnector::getInstance();

            $paperQueryString = "select * from " . self::const_PaperTableName . " where PaperID=$paperID";
            return $dbConnector->queryStatement( $paperQueryString, new PaperCreator() );
        } else{
            return null;
        }
    }

    public static function loadFromDatabaseWith( $forGrade, $contestTime ){
        if ( !empty( $forGrade ) && !empty( $contestTime ) ){
            $dbConnector = DatabaseConnector::getInstance();

            $paperQueryString = "select * from " . self::const_PaperTableName . " where ForGrade='$forGrade' and ContestTime='$contestTime'";
            return $dbConnector->queryStatement( $paperQueryString, new PaperCreator() );
        } else{
            return null;
        }
    }

    public static function loadFromDatabaseByConditions( array $conditions ){
        if ( !empty( $conditions ) ){
            $dbConnector = DatabaseConnector::getInstance();
            $queryString = "select * from " . self::const_PaperTableName.' where ';

            $condCountExcludeLast = count( $conditions ) - 1;
            foreach ( $conditions as $condKey => $condValue ){
                if ( $condKey == $condCountExcludeLast ){
                    $queryString .= $condValue;
                } else{
                    $queryString .= "$condValue and";
                }
            }

            $papers = $dbConnector->queryStatement( $queryString ,new PapersCreator () );
            return $papers;
        }
        return null;
    }

    public static function deleteFromDatabase( array $cond ){
        if ( !empty( $cond ) ){
            $dbConnector = DatabaseConnector::getInstance();
            return $dbConnector->executeDelete( self::const_PaperTableName, $cond );
        } else{
            return false;
        }
    }
} 