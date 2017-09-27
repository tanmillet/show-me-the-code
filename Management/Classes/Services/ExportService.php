<?php

require_once dirname(__FILE__) . "/../PHPExcel.php";
require_once dirname(__FILE__) . "/../Objects/ContestPaper.php";
require_once dirname(__FILE__) . "/../Objects/ApplyContestant.php";

class ExportService{
    const const_AllGrade = "全部年级";

    const const_ApplyIDColumn = '0';
    const const_NameColumn = '1';
    const const_GradeColumn = '2';
    const const_SchoolColumn = '3';

    const const_ApplyIDValue = '报名号';
    const const_NameValue = '姓名';
    const const_GradeValue = '年级';
    const const_SchoolValue = '学校';
    const const_ScoreValue = '总得分';
    // return a excel writer
    public static function ExportExcel( $year, $grade ){
        if ( empty( $year ) || empty( $grade ) ){
            return null;
        }

        if ( $grade == self::const_AllGrade ){
            $papers = ContestPaper::loadFromDatabaseByConditions( array( "ContestTime='{$year}'") );
            if ( !empty( $papers ) ){
                $doc = new PHPExcel();

                $sheets = $doc->getAllSheets();
                $countDiff =  count( $papers ) - count( $sheets );
                if ( $countDiff > 0 ){
                    for ( $each = 0; $each < $countDiff; ++$each ){
                        $doc->createSheet();
                    }
                }

                foreach ( $papers as $count => $eachPaper ){
                   self::StartWriteExcelSheet( $eachPaper,$doc, $count );
                }

                return $doc;
            } else{
                return null;
            }
        } else{
            $paper = ContestPaper::loadFromDatabaseWith( $grade, $year );
            if ( !empty( $paper ) ){
                return self::StartWriteExcelSheet( $paper, new PHPExcel(), 0 );
            }
        }
    }

    private static function StartWriteExcelSheet( $paper, $doc, $index ){
        try{
            $doc->setActiveSheetIndex( $index );
            $sheet = $doc->getActiveSheet();

            $sheet->setTitle( $paper->_forGrade );

            $paperID = $paper->getPaperID();
            $result = ApplyContestant::loadFromDatabaseByConditions( array( "PaperID={$paperID}") );

            if ( empty( $result ) ){
                return null;
            }

            self::DoWriteExcelSheetCore( $paper, $result[ 0 ], $sheet );
            return $doc;
        } catch( Exception $ex ){
            return null;
        }
    }

    private static function DoWriteExcelSheetCore( $paper, $contestants, $sheet ){
        if ( empty( $paper ) || empty( $contestants ) || empty( $sheet ) ){
            throw new Exception();
        }

        $quesTemplate = json_decode( $paper->_questionTypeTemplate, true );
        $indexes = $quesTemplate[ "index" ];
        $indexesSpan = array();

        $start = self::const_SchoolColumn + 1;
        $offset = 0;
        foreach ( $indexes as $eachIndex ){
            if ( array_key_exists( $eachIndex, $quesTemplate )
                && array_key_exists( "count", $quesTemplate[ $eachIndex ] ) ){
                $span =  $quesTemplate[ $eachIndex ][ "count" ];
                $indexesSpan[ $eachIndex ] = array( "start" => ( $start + $offset ), "span" => $span );
                $offset += $span;
            }
        }

        if ( empty( $indexesSpan ) ){
            throw new Exception();
        }

        // write the head
        $scoreColumn = $start +$offset;

        $titleRow = 1;
        $sheet->getCellByColumnAndRow( self::const_ApplyIDColumn, $titleRow )->setValue( self::const_ApplyIDValue );
        $sheet->getCellByColumnAndRow( self::const_NameColumn, $titleRow )->setValue( self::const_NameValue );
        $sheet->getCellByColumnAndRow( self::const_GradeColumn, $titleRow )->setValue( self::const_GradeValue );
        $sheet->getCellByColumnAndRow( self::const_SchoolColumn, $titleRow )->setValue( self::const_SchoolValue );
        foreach ( $indexes as $eachIndex ){
            $span = $indexesSpan[ $eachIndex ];
            $spanStart = $span[ "start" ];
            $spanValue = $span[ "span" ];
            for ( $col = 1; $col <= $spanValue; ++$col ){
                $sheet->getCellByColumnAndRow( $spanStart , $titleRow )->setValue( $eachIndex . $col );
                ++$spanStart;
            }
        }
        $sheet->getCellByColumnAndRow( $scoreColumn, $titleRow )->setValue( self::const_ScoreValue );

        // content
        foreach ( $contestants as $row => $eachContestant ){
            $row = $row + 1 + $titleRow;    // one offset for title

            $sheet->getCellByColumnAndRow( self::const_ApplyIDColumn, $row )->setValue( $eachContestant->getApplyID() );
            $sheet->getCellByColumnAndRow( self::const_NameColumn, $row )->setValue( $eachContestant->_name );
            $sheet->getCellByColumnAndRow( self::const_GradeColumn, $row )->setValue( $eachContestant->_grade );
            $sheet->getCellByColumnAndRow( self::const_SchoolColumn, $row )->setValue( $eachContestant->_school );

            $scoreDetail = json_decode( $eachContestant->_scoreRawDetail, true );
            if ( $scoreDetail == null || $scoreDetail == false ){
                continue;
            }

            foreach ( $indexes as $eachIndex ){
                $type = $quesTemplate[ $eachIndex ][ "type" ];
                $print = $scoreDetail[ $eachIndex ][ "print" ];

                $span = $indexesSpan[ $eachIndex ];
                $spanStart = $span[ "start" ];
                $spanValue = $span[ "span" ];

                if ( $type == "TrueOrFalse" ){
                    for ( $col = 1; $col <= $spanValue; ++$col ){
                        $printValue =  array_key_exists( $col - 1, $print ) ? $print[ $col - 1 ] : "F" ;
                        $sheet->getCellByColumnAndRow( $spanStart, $row )->setValue( $printValue );
                        ++$spanStart;
                    }
                } else if ( $type == "Points" ){
                    for ( $col = 1; $col <= $spanValue; ++$col ){
                        $printValue =  array_key_exists( $col - 1, $print ) ? $print[ $col - 1 ] : 0 ;
                        $sheet->getCellByColumnAndRow( $spanStart, $row )->setValue( $printValue );
                        ++$spanStart;
                    }
                } else{
                    continue;
                }
            }
            $sheet->getCellByColumnAndRow( $scoreColumn, $row )->setValue( $eachContestant->_score );
        }
    }
}
