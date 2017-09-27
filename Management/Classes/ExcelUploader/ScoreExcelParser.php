<?php
require dirname(__FILE__) ."/../Objects/ContestPaper.php";
require dirname(__FILE__) ."/../Objects/ApplyContestant.php";
require dirname(__FILE__) ."/../PHPExcel/IOFactory.php";

class ScoreExcelParser {
    private $m_contestTime;

    private $m_document;
    private $m_sheet;
    private $m_processCount;
    private $m_outputContestant;
    private $m_failedMessages;

    private $m_paperCache;

    //
    private $m_currentContestantMetadata;
    private $m_currentDomainCounter;
    private $m_firstRowRowIndex;

    private $m_titleColumnIndex;    // column index => "string value"
    private $m_indexesSpan;

    public function __construct( $excelFilePath, $time ){
        if ( !empty( $excelFilePath ) && preg_match( "/^\\d{4}$/", $time ) ){
            try{
                $this->m_document = PHPExcel_IOFactory::load( $excelFilePath );
                if ( !empty( $this->m_document ) ){
                    $sheetCount = $this->m_document->getsheetcount();
                    if ( $sheetCount > 0 ){
                        $this->m_document->setActiveSheetIndex( 0 );
                        $this->m_sheet = $this->m_document->getActiveSheet();

                        $this->m_mergedCellsCache = $this->m_sheet->getMergeCells();
                        $this->m_contestTime = $time;
                    } else{
                        throw new Exception( 'No sheet available' );
                    }
                } else{
                    throw new Exception( 'No document available' );
                }
            } catch( Exception $exception ){
                $this->m_document = null;
                $this->m_sheet = null;

                $this->m_lastExceptionMessage = $exception->getMessage();
		throw $exception;
            }
        }
    }

    const ApplyIDTitle = "报考号";
    const NameTitle = "姓名";
    const GradeTitle = "年级";
    const SchoolTitle = "学校";
    const FullScoreTitle = "总分";

    public function startParse(){
        $this->startParseHead();
        $this->startParseData();

        return $this->m_outputContestant;
    }

    public function getParsedContestant(){
        return $this->m_outputContestant;
    }

    private function startParseHead(){
        $firstRow = $this->m_sheet->getRowIterator();
        if ( !$firstRow->valid() ){
            throw new Exception (  '第一行读取失败' );
        }

        $rowInstance = $firstRow->current();
        $cellIterator = $rowInstance->getCellIterator();
        if ( !$cellIterator->valid() ) {
            throw new Exception ( '第一行读取失败' );
        }
        $this->m_firstRowRowIndex = $rowInstance->getRowIndex();

        $this->m_titleColumnIndex = array();
        $this->m_indexesSpan = array();

        $titles = array( self::ApplyIDTitle => "applyID", self::NameTitle => "name", self::GradeTitle => "grade"
            , self::SchoolTitle => "school", self::FullScoreTitle => "score" );

        $lastIndex = null;
        $lastNum = 1;
        foreach ( $cellIterator as $key => $value ){
            $cellValue = $value->getValue();
            if ( array_key_exists( $cellValue, $titles ) ){
                $this->m_titleColumnIndex[ $cellValue ] = $key;
            } else{
                $matched = array();
                preg_match_all("/(.+)([\\d]+)/", $cellValue, $matched );

                if ( count( $matched ) == 3
                    && count( $matched[ 0 ] ) > 0 ){

                    $index = $matched[ 1 ][ 0 ];
                    $num = $matched[ 2 ][ 0 ];

                    if ( $lastIndex == null || $lastIndex != $index ){
                        if ( $num != 1 ){
                            throw new Exception( '解析失败于第一行,列:', $key );
                        }

                        $this->m_indexesSpan[ $index ][ "start" ] = $key;
                        $this->m_indexesSpan[ $index ][ "span" ] = 1;
                        $lastIndex = $index;
                        $lastNum = 1;
                    } else{
                        if ( $lastNum + 1 != $num ){
                            throw new Exception( '解析失败于第一行,列:', $key );
                        }

                        $this->m_indexesSpan[ $index ][ "span" ] += 1;
                        $lastNum = $num;
                    }
                }
            }
        }

        // check
        $isValid = true;
        if ( !$isValid || empty( $this->m_indexesSpan ) ){
            throw new Exception (  '第一行读取失败' );
        }
    }

    public function getProcessCount(){
        return $this->m_processCount;
    }

    public function getFailedMessages(){
        return $this->m_failedMessages;
    }

    private function startParseData(){
        $this->m_processCount = 0;
        $this->m_outputContestant = array();
        $this->m_paperCache = array();
        $this->m_failedMessages = array();
        $rowCount = $this->m_sheet->getHighestRow();

        $applyIDColumnIndex = $this->m_titleColumnIndex[ self::ApplyIDTitle ];
        $nameColumnIndex = $this->m_titleColumnIndex[ self::NameTitle ];
        $gradeColumnIndex = $this->m_titleColumnIndex[ self::GradeTitle ];
        $schoolColumnIndex = $this->m_titleColumnIndex[ self::SchoolTitle ];
        $scoreColumnIndex = $this->m_titleColumnIndex[ self::FullScoreTitle ];

        // offset 1
        for ( $rowIndex = $this->m_firstRowRowIndex + 1; $rowIndex <= $rowCount; ++$rowIndex ){
            ++$this->m_processCount;

            $applyID = trim( $this->m_sheet->getCellByColumnAndRow( $applyIDColumnIndex, $rowIndex )->getValue() );
            if ( empty( $applyID ) ){
                array_push( $this->m_failedMessages, "第{$rowIndex}行");
                continue;
            }
            $name = trim( $this->m_sheet->getCellByColumnAndRow( $nameColumnIndex, $rowIndex )->getValue() );
            if ( empty( $name ) ){
                array_push( $this->m_failedMessages, $applyID);
                continue;
            }
            $grade = trim( $this->m_sheet->getCellByColumnAndRow( $gradeColumnIndex, $rowIndex )->getValue() );
            if ( empty( $grade ) ){
                array_push( $this->m_failedMessages, $applyID);
                continue;
            }
            $school = trim( $this->m_sheet->getCellByColumnAndRow( $schoolColumnIndex, $rowIndex )->getValue() );
            if ( empty( $school ) ){
                array_push( $this->m_failedMessages, $applyID);
                continue;
            }
            $fullScore = trim( $this->m_sheet->getCellByColumnAndRow( $scoreColumnIndex, $rowIndex )->getValue() );

            // read paper
            $paper = null;
            if ( array_key_exists( $grade, $this->m_paperCache ) ){
                $paper = $this->m_paperCache[ $grade ];
            } else{
                $paper = ContestPaper::loadFromDatabaseWith( $grade, $this->m_contestTime );
                if ( !empty( $paper ) ){
                    $paper->_questionTypeTemplate = json_decode( $paper->_questionTypeTemplate, true );
                    $paper->_domainTemplate = json_decode( $paper->_domainTemplate, true );
                    $this->m_paperCache[ $grade ] = $paper;
                }
            }

            if ( empty( $paper ) ){
                array_push( $this->m_failedMessages, $applyID );
                continue;
            }

            // get paper index
            $occurError = false;
            $currentIndexMetadata = array();

            $answerSeq = array();
            $this->m_currentContestantMetadata = array();
            $this->m_currentDomainCounter = array();
            $paperQuesTypeIndexes = $paper->_questionTypeTemplate[ "index" ];
            $paperDomains = $paper->_domainTemplate[ "index" ];
            // score not check
            ////
            ///

            foreach ( $paperQuesTypeIndexes as $eachIndex ){
                $span = $this->m_indexesSpan[ $eachIndex ];
                if ( empty( $span ) ){
                    break;
                }

                $quesTemplate = $paper->_questionTypeTemplate[ $eachIndex ];
                $domainArray = $quesTemplate[ "domains" ];
                $count = $quesTemplate[ "count" ];
                $score = $quesTemplate[ "score" ];

                if ( empty( $quesTemplate ) || empty( $domainArray ) || empty( $count )
                    || empty( $score ) || $count > $span[ "span" ]){
                    $occurError = true;
                    break;
                }

                $domainArray = explode( ",", $domainArray );
                if ( $count != count( $domainArray ) ){
                    $occurError = true;
                    break;
                }

                $indexScore = 0;
                $type = $quesTemplate[ "type" ];
                if ( $type == "TrueOrFalse" ){
                    $points = $quesTemplate[ "points" ];
                    // in TrueOrFalse count must be 1
                    if ( empty( $points ) ){
                        $occurError = true;
                        break;
                    }
                    $points = explode( ",", $points );
                    if ( count( $points ) != 1 ){
                        $occurError = true;
                        break;
                    }
                    $points = $points[ 0 ];

                    $startAt = $span[ "start" ];
                    $finalAt = $startAt + $count;
                    for ( $index = $startAt ; $index < $finalAt; ++$index ){
                        $eachValue = trim( strtoupper( $this->m_sheet->getCellByColumnAndRow( $index, $rowIndex )->getValue() ) ) ;
                        if ( $eachValue == "" ){    // fill with default value
                            $eachValue = "F";
                        } else if ( $eachValue != "T" && $eachValue != "F" ){
                            $occurError = true;
                            break;
                        }

                        $domain = $domainArray[ $index - $startAt ];
                        if ( !array_key_exists( $domain, $this->m_currentDomainCounter ) ){
                            $this->m_currentDomainCounter[ $domain ] = 0;
                        }

                        if ( $eachValue == "T" ){
                            $indexScore += $points;
                            $this->m_currentDomainCounter[ $domain ] += 1;
                        }

                        array_push( $answerSeq, $eachValue );
                    }

                    // higher than max score
                    if ( $occurError || $indexScore > $score ){
                        break;
                    }

                    $this->m_currentContestantMetadata[ $eachIndex ] = array(
                        "score" => $indexScore,
                        "urans" => null,
                        "print" => $answerSeq
                    );
                    $indexScore = 0;
                    $answerSeq = array();
                } else if ( $type == "Points" ){
                    $values = $quesTemplate[ "values" ];
                    // in TrueOrFalse count must be 1
                    if ( empty( $values ) ){
                        break;
                    }
                    $values = explode( ",", $values );
                    if ( count( $values ) != $count ){
                        break;
                    }

                    $startAt = $span[ "start" ];
                    $finalAt = $startAt + $count;
                    for ( $index = $startAt ; $index < $finalAt; ++$index ){
                        $eachValue = trim( $this->m_sheet->getCellByColumnAndRow( $index, $rowIndex )->getValue() );
                        $offset = $index - $startAt;
                        if ( $eachValue == "" ){    // fill with default value
                            $eachValue = "0";
                        } else if ( !preg_match( "/^\\d+$/", $eachValue ) && $eachValue > $values[ $offset ] && $eachValue < 0 ){
                            $occurError = true;
                            break;
                        }

                        $domain = $domainArray[ $offset ];
                        if ( !array_key_exists( $domain, $this->m_currentDomainCounter ) ){
                            $this->m_currentDomainCounter[ $domain ] = 0;
                        }

                        if ( $eachValue > 0 ){
                            $indexScore += $eachValue;
                            $this->m_currentDomainCounter[ $domain ] += 1;
                        }

                        array_push( $answerSeq, $eachValue );
                    }

                    // higher than max score
                    if ( $occurError || $indexScore > $score ){
                        break;
                    }

                    $this->m_currentContestantMetadata[ $eachIndex ] = array(
                        "score" => $indexScore,
                        "urans" => null,
                        "print" => $answerSeq
                    );
                    $indexScore = 0;
                    $answerSeq = array();
                } else{
                    $occurError = true;
                    break;
                }
            }

            if ( $occurError ){
                array_push( $this->m_failedMessages, $applyID );
                continue;
            }

            // append to metadata
            //
            foreach ( $paperDomains as $domain ){
                $domainCount = array_key_exists( $domain, $this->m_currentDomainCounter ) ?
                    $this->m_currentDomainCounter[ $domain ] : 0;
                $this->m_currentContestantMetadata[ $domain ] = array( "count" => $domainCount, "print" => $domainCount );
            }

            // create contestant
            $paperID = $paper->getPaperID();
			$contestant = new ApplyContestant( $applyID, $name, $grade, $school, json_encode( $this->m_currentContestantMetadata )
                , $fullScore, $paperID, $this->m_contestTime );
            if ( !array_key_exists( $paperID, $this->m_outputContestant ) ){
                $this->m_outputContestant[ $paperID ] = array();
            }
            array_push( $this->m_outputContestant[ $paperID ], $contestant );
        }
    }
}

