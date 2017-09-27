<?php
date_default_timezone_set('PRC');

require dirname(__FILE__) . "/../../GlobalConfig.php";
require dirname(__FILE__) . "/../PHPExcel/IOFactory.php";
require dirname(__FILE__) . "/../Objects/ContestPaper.php";

class ParseState{
    const StartState = 0;
    const HeadState = 1;
    const TemplateHeadState = 2;
    const TemplateContentState = 3;
    const AbbrState = 4;
    const EndState = 5;
}

class ColumnType{
    const CorrectAnswerColumn = 0;
    const QuestionScoreColumn = 1;
}

class PaperExcelParser {
    private $m_document;
    private $m_sheet;
    private $m_drawings;

    private $m_headArray;

    private $m_templateCount;
    private $m_templateHeadSpanArray;
    private $m_templateHeadArrays;
    private $m_templateContentArrays;
    private $m_currentTemplateRow;  // 0 -> TemplateRowCount

    private $m_abbrPairs;   // domain abbr.

    private $m_parseState;

    // meta data
    private $m_grades;
    private $m_quesTypeIndexes;
    private $m_contestTime;
    private $m_domainIndexes;
    private $m_fullScore;
    private $m_sumScore;

    // meta predata
    private $m_quesTypeScoreCount;
    private $m_quesTypeGradeCount;
    private $m_paperMetadata;
    private $m_currentPaperMetadata;
    private $m_currentPaperDomainCounter;

    // parse out data
    private $m_quesNumber;  // vary in each index( question type )
    private $m_currentIndex;    // vary in each index( question type )

    // if is point column, this would be a point list
    private $m_quesTypeAnsSeq;  // vary in each index( question type )
    private $m_quesTypeDomainSeq;// vary in each index( question type )

    private $m_columnType;  // vary in each column type( may multi-index )
    private $m_lastColumnType;

    private $m_currentPair; // vary in each column

    private $m_mergedCellsCache;
    // this will be rethrow
    private $m_lastExceptionMessage;

    const HeadTitle = "试卷结构与对比";
    const TemplateTitle = "正确答案及每题所属领域";
    const AbbrFirstTitle = "领域";
    const AbbrSecondTitle = "缩写";
    const TemplateRowCount = 3; // each template is this row number

    const GradeTitle = "年级";
    const QuestionCountTitle = "题目总数";
    const PaperScoreTitle = "试卷总分";

    const CorrectAnswerLabel = "正确答案";
    const DomainLabel = "所属领域";
    const PointLabel = "分值";

    public function __construct( $excelFilePath ){
        if ( !empty( $excelFilePath ) ){
            try{
                $this->m_document = PHPExcel_IOFactory::load( $excelFilePath );
                if ( !empty( $this->m_document ) ){
                    $sheetCount = $this->m_document->getsheetcount();
                    if ( $sheetCount > 0 ){
                        $this->m_document->setActiveSheetIndex( 0 );
                        $this->m_sheet = $this->m_document->getActiveSheet();

                        $this->m_mergedCellsCache = $this->m_sheet->getMergeCells();
                        // load drawings
                        $this->m_drawings = array();
                        foreach ( $drawings = $this->m_sheet->getDrawingCollection() as $eachDrawing ){
                            //$eachDrawing->getCoo
                            $value = $eachDrawing->getCoordinates();
                            $coordinate = PHPExcel_Cell::coordinateFromString( $value );
                            $colIndex = PHPExcel_Cell::columnIndexFromString( $coordinate[ 0 ] );
                            // index 1 => row
                            $rowIndex = ( int )$coordinate[ 1 ];
                            if ( !array_key_exists( $rowIndex, $this->m_drawings ) ){
                                $this->m_drawings[ $rowIndex ] = array();
                            }

                            $this->m_drawings[ $rowIndex ][ $colIndex ] = $eachDrawing;
                        }
                    } else{
                        throw new Exception( '无Sheet可读' );
                    }
                } else{
                    throw new Exception( '无文档可读' );
                }
            } catch( Exception $exception ){

                $this->m_document = null;
                $this->m_sheet = null;

                $this->m_lastExceptionMessage = $exception->getMessage();
            }
        }
    }

    private function doHeadParse( $row ){
        foreach( $row->getCellIterator() as $key => $cell ){
            $cellValue = trim( $cell->getValue() );

            if ( $cellValue == self::TemplateTitle ){
                $this->m_parseState = ParseState::TemplateHeadState;
                return;
            }

            if ( $cellValue != null ){
                if ( !isset( $this->m_headArray[ $key ] ) ){
                    $this->m_headArray[ $key ] = array();
                }
                array_push( $this->m_headArray[ $key ], $cellValue );
            }
        }
    }

    private function doTemplateHeadParse( $row ){
        if ( $this->m_currentTemplateRow != self::TemplateRowCount ){
            throw new Exception( 'Template head parse error' );
        } else{
            $isANullRow = true;
            $cellDataInRow = null;
            $mayAbbrColumns = false;

            $columnSpan = 0;
            $columnStart = null;
            foreach( $row->getCellIterator() as $key => $cell ){
                $cellValue = trim( $cell->getValue() );
                if ( !empty( $cellValue ) ){
                    if ( $columnStart == null ){
                        $columnStart = PHPExcel_Cell::columnIndexFromString( $cell->getColumn() );
                    }

                    $isANullRow = false;

                    if ( $mayAbbrColumns && $cellValue == self::AbbrSecondTitle ){
                        $this->m_parseState = ParseState::AbbrState;
                        return;
                    }

                    if ( empty( $cellDataInRow ) ){
                        $cellDataInRow = array();
                    }
                    if ( $cellValue == self::AbbrFirstTitle ){
                        $mayAbbrColumns = true;
                    }

                    array_push( $cellDataInRow, $cellValue );
                    ++$columnSpan;
                }
            }

            if ( !$isANullRow ){
                // only when runs into head parse again this value would be increased by 1
                if ( !$mayAbbrColumns ){
                    ++$this->m_templateCount;
                    // append
                    $this->m_templateHeadArrays[ $this->m_templateCount ] = $cellDataInRow;
                    $this->m_templateHeadSpanArray[ $this->m_templateCount ] = array( "start" => $columnStart, "span" => $columnSpan );

                    --$this->m_currentTemplateRow;
                    $this->m_parseState = ParseState::TemplateContentState;
                } else{ // this situation is rare, only when Abbr column has one column
                    $this->m_parseState = ParseState::AbbrState;
                }
            }
        }
    }

    private function doTemplateContentParse( $row ){
        if ( $this->m_currentTemplateRow == self::TemplateRowCount
            || $this->m_currentTemplateRow <= 0
            || !array_key_exists( $this->m_templateCount, $this->m_templateHeadArrays )
            || !array_key_exists( $this->m_templateCount, $this->m_templateHeadSpanArray ) ){
            throw new Exception( 'Template head parse error' );
        } else{
            // get drawings
            $rowIndex = $row->getRowIndex();
            $currentRowDrawings = null;
            if ( array_key_exists( $rowIndex,$this->m_drawings ) ){
                $currentRowDrawings = $this->m_drawings[ $rowIndex ];
            }

            $cellDataInRow = array();
            $cellIterator = $row->getCellIterator();
            $keySequence = null;

            // range
            $span = $this->m_templateHeadSpanArray[ $this->m_templateCount ];
            $start = $span[ 'start' ];
            $end = $start + $span[ 'span' ];

            foreach( $cellIterator as $key => $cell ){
                $cellColumn = PHPExcel_Cell::columnIndexFromString( $cell->getColumn() );
                if ( $cellColumn < $start || $cellColumn >= $end ){
                    continue;
                }

                if ( $keySequence == null ){
                    $keySequence = $key;
                } else{
                    if ( $keySequence + 1 != $key ){
                        if ( !empty( $currentRowDrawings ) ){
                            $diff = $key - $keySequence;
                            for ( $eachDiff = 1; $eachDiff < $diff; ++$eachDiff ){
                                $colIndex = $cell->columnIndexFromString( $cell->getColumn() );
                                if ( array_key_exists( $colIndex, $currentRowDrawings ) ){
                                    $wroteString = self::writeParsedImgToFile( $currentRowDrawings[ $colIndex ] );
                                    if ( empty ( $wroteString ) ){
                                        throw new Exception( ' 行:' . $rowIndex . '列:' . $colIndex . '不是连续的列, 该列不包含图像,可能是其他对象, 请将该对象替换成图片, 重新上传' );
                                    } else{
                                        array_push( $cellDataInRow, $wroteString );
                                    }
                                }
                            }
                        } else{
                            throw new Exception( 'row:' . $rowIndex . 'key seq:' . $keySequence . '不是连续的列, 该列不包含图像,可能是其他对象, 请将该对象替换成图片, 重新上传' );
                        }
                    } else{
                        $cellValue = trim( $cell->getValue() );

                        if ( empty( $cellValue ) ){
                            $colIndex = $cell->columnIndexFromString( $cell->getColumn() );
                            if ( array_key_exists( $colIndex, $currentRowDrawings ) ){
                                $wroteString = self::writeParsedImgToFile( $currentRowDrawings[ $colIndex ] );
                                if ( empty ( $wroteString ) ){
                                    throw new Exception( '不是连续的列, 该列不包含图像,可能是其他对象, 请将该对象替换成图片, 重新上传' );
                                } else{
                                    array_push( $cellDataInRow, $wroteString );
                                    $keySequence = $key;
                                }
                            }
                        } else{
                            array_push( $cellDataInRow, $cellValue );
                            $keySequence = $key;
                        }
                    }
                }
            }

            $this->m_templateContentArrays[ $this->m_templateCount ][ $this->m_currentTemplateRow ] = $cellDataInRow;
            --$this->m_currentTemplateRow;
        }
    }

    // determinate by column sequence /
    private function doAbbrColumnParse( $row ){
        $domainFullName = null;
        foreach( $row->getCellIterator() as $key => $cell ){
            $cellValue = trim( $cell->getValue() );
            if ( !empty( $cellValue ) ){
                if ( empty( $domainFullName ) ){
                    $domainFullName = $cellValue;
                } else{
                    // make pair
                    $this->m_abbrPairs[ $cellValue ] = $domainFullName;
                }
            }
        }
    }

    // return a src link
    private static function writeParsedImgToFile( $drawing ){
        try{
            if ($drawing instanceof PHPExcel_Worksheet_MemoryDrawing) {
                ob_start();
                call_user_func(
                    $drawing->getRenderingFunction(),
                    $drawing->getImageResource()
                );

                $imageContents = ob_get_contents();
                ob_end_clean();
                $extension = 'jpg';
            } else{
                $zipReader = fopen($drawing->getPath(),'r');
                $imageContents = '';

                while (!feof($zipReader)) {
                    $imageContents .= fread($zipReader,1024);
                }
                fclose($zipReader);
                $extension = $drawing->getExtension();
            }

            // image process
            $imgInstance = imagecreatefromstring ( $imageContents );
            $white = imagecolorallocate( $imgInstance, 255, 255, 255);
            imagecolortransparent( $imgInstance, $white );

            $timeStamp = time();

            $file = 'paper_'.$timeStamp. mt_rand(). '.'.$extension;
            $myFileName =  dirname(__FILE__) . Config::const_ImageDir .$file;

            if ( imagepng( $imgInstance, $myFileName ) && imagedestroy( $imgInstance ) ){
                $ref = Config::const_ImageRelativeDir . $file;
                return "<img src=\'$ref\'>";
            } else{
                throw new Exception('Image save failed');
            }
        } catch ( Exception $err ){
            return null;
        }
    }

    // each head includes its title( at 0 ) and its value ( at 1- range)
    private function parseOutHead(){
        foreach ( $this->m_headArray as $colKey => $value ){
            $rowCount = count( $value );
            if ( $rowCount > 0 ){
                $key = $value[ 0 ];
                if ( $key == self::GradeTitle ){
                    for ( $index = 1; $index < $rowCount; ++$index ){
                        array_push( $this->m_grades, $value[ $index ] );
                    }
                } else if ( preg_match( "/^\\d{4}年$/", $key ) == 1 ){
                    $this->m_contestTime = substr( $key, 0, 4 );
                } else if ( $key == self::PaperScoreTitle ){
                    if ( is_numeric( $value ) ){
                        $this->m_fullScore = $value;
                    }
                } else if ( $key == self::QuestionCountTitle ){
                    // do nothing
                } else {
                    // question type
                    array_push( $this->m_quesTypeIndexes, $key );
                    $this->m_quesTypeScoreCount[ $key ] = array_slice( $value,1 );
                }
            }
        }
    }

    private function parseOutQuesPointAndCount(){
        $this->m_quesTypeGradeCount = array();
        foreach ( $this->m_quesTypeIndexes as $eachIndex ){
            $scoreCount = $this->m_quesTypeScoreCount[ $eachIndex ];
            if ( !empty( $scoreCount ) ){
                $count = count( $scoreCount );
                if ( $count == 1 ){
                    $singleScoreCount = $scoreCount[ 0 ];
                    $matched = array();

                    preg_match_all( "/([\\d]+)\\*([\\d]+)分/", $singleScoreCount, $matched );
                    if ( empty( $matched ) || empty( $matched[ 0 ] ) ){
                        throw new Exception( '不是合法的分数信息:', $singleScoreCount );
                    }
                    $score = $matched[ 2 ][ 0 ] * $matched[ 1 ][ 0 ];
                    $this->m_quesTypeGradeCount[ $eachIndex ] = array( "point" => $matched[ 2 ][ 0 ]
                        , "count" => $matched[ 1 ][ 0 ]
                        , "score" => $score );
                    $this->m_sumScore += $score;
                } else{
                    $sumCount = 0;
                    $points = array();
                    $score = 0;
                    foreach ( $scoreCount as $eachScoreCount ){
                        $matched = array();
                        preg_match_all( "/([\\d]+)\\*([\\d]+)分/", $eachScoreCount, $matched );

                        if ( empty( $matched ) || empty( $matched[ 0 ] ) ){
                            throw new Exception( '不是合法的分数信息:', $singleScoreCount );
                        }
                        $sumCount += $matched[ 1 ][ 0 ];
                        $score += $matched[ 1 ][ 0 ] * $matched[ 2 ][ 0 ];
                        array_push( $points, $matched[ 2 ][ 0 ] );
                    }
                    $this->m_quesTypeGradeCount[ $eachIndex ] = array( "point" => $points
                    , "count" => $sumCount, "score" => $score );

                    $this->m_sumScore += $score;
                }
            } else{
                throw new Exception( 'No score info of', $eachIndex );
            }
        }
    }

    private function parseOutTemplate(){
        if ( count( $this->m_templateHeadArrays ) != count( $this->m_templateContentArrays ) ){
            throw new Exception( 'head can not match the content');
        }

        $this->clear();
        $leftGrades = $this->m_grades;
        $leftGradeHasSomePaper = false;

        // parse out
        $this->m_sumScore = 0;
        $this->parseOutQuesPointAndCount();
        // parse out abbr
        $this->m_domainIndexes = array_keys( $this->m_abbrPairs );

        if ( empty( $this->m_domainIndexes ) ){
            throw new Exception( '无领域缩写信息' );
        }

        foreach ( $this->m_templateHeadArrays as $key => $headData ){
            // read the first head data
            $dataCount = count( $headData );
            $templateContentArray = $this->m_templateContentArrays[ $key ];
            if ( count( $templateContentArray ) != 2 ){
                throw new Exception( '不是合法的表格' );
            }

            if ( $dataCount > 0 ){
                // read the first one as the grade
                $grade = $headData[ 0 ];
                if ( !in_array( $grade, $leftGrades ) ){
                    $leftGradeHasSomePaper = true;
                }
                // specific grade
                // separator
                for ( $index = 1; $index < $dataCount; ++$index ){
                    $matched = array();
                    preg_match_all("/(.+)([\\d]+)/", $headData[ $index ], $matched );

                    // answer
                    // only the first column has merged so index should offset by -1
                    // ### $templateContentArray 1 - 2
                    $this->m_currentPair = array( $templateContentArray[ 2 ][ $index - 1 ], $templateContentArray[ 1 ][ $index - 1 ] );

                    $parsedIndex = $matched[ 1 ][ 0 ];
                    if ( count( $matched ) == 3
                        && count( $matched[ 0 ] ) > 0
                        && in_array( $parsedIndex, $this->m_quesTypeIndexes ) ){
                        // question type title with number
                        $quesTypeNum = $matched[ 2 ][ 0 ];

                        $this->parseOutColumns( $parsedIndex, $quesTypeNum );
                    } else{
                        $this->parseOutSeparator();
                    }
                }

                // last one
                $this->appendToCurrentPaper();
                $this->m_currentPaperMetadata[ "quesTemplate" ][ "index" ] = $this->m_quesTypeIndexes;
                // domain updated
                $this->appendToCurrentPaperDomain();

                // finished
                if ( $leftGradeHasSomePaper ){
                    foreach ( $leftGrades as $eachGrade ){
                        $this->m_paperMetadata[ $eachGrade ] = $this->m_currentPaperMetadata;
                    }
                    $leftGrades = null;
                } else{
                    $this->m_paperMetadata[ $grade ] = $this->m_currentPaperMetadata;
                    if( ( $key = array_search( $grade, $leftGrades ) ) !== false ) {
                        unset( $leftGrades[ $key ] );
                    }
                }

                if ( empty( $leftGrades ) ){
                    break;
                }
                // clear
                $this->clear();

            } else{
                throw new Exception( 'invalid template head');
            }
        }

        if ( !( empty( $leftGrades ) ) ){
            foreach ( $leftGrades as $key => $value ){
                unset ( $this->m_grades[ $key ] );
            }
        }
    }

    private function clear(){
        $this->m_currentPaperMetadata = array();
        $this->m_currentPaperDomainCounter = array();

        $this->m_quesNumber = 1;
        $this->m_currentIndex = null;

        $this->m_quesTypeAnsSeq = array();
        $this->m_quesTypeDomainSeq = array();

        $this->m_columnType = ColumnType::CorrectAnswerColumn;
        $this->m_lastColumnType = ColumnType::CorrectAnswerColumn;
        $this->m_currentPair = null;
    }

    private function parseOutColumns( $parsedIndex, $quesTypeNum ){
        // check index
        if ( empty( $this->m_currentIndex ) ){
            $this->m_currentIndex = $parsedIndex;
            $this->m_quesNumber = 1;
        } else{
            if ( $this->m_currentIndex == $parsedIndex ){
                if ( $quesTypeNum != $this->m_quesNumber + 1 ){
                    throw new Exception('题号必须在题型中连续');
                }
                $this->m_quesNumber = $quesTypeNum;
            } else{
                if ( $quesTypeNum != 1 ){
                    throw new Exception('题号必须在题型中连续');
                } else{
                    $this->appendToCurrentPaper();
                    $this->m_currentIndex = $parsedIndex;
                    $this->m_quesNumber = 1;

                    $this->m_quesTypeAnsSeq = array();
                    $this->m_quesTypeDomainSeq = array();
                }
            }
        }

        $answer = $this->m_currentPair[ 0 ];
        $domain = $this->m_currentPair[ 1 ];

        // valid answer and domain
        if ( empty( $answer ) ){    // maybe a answer in image form
            if ( empty( $domain ) ){
                throw new Exception( '不是合法的题目答案' );
            } else if ( !array_key_exists( $domain, $this->m_domainIndexes  ) ){
                throw new Exception( '未知的领域值: ', $domain );
            }
        } else{
            // find in the drawing collections
        }
        array_push( $this->m_quesTypeAnsSeq, $answer );
        array_push( $this->m_quesTypeDomainSeq, $domain );

        $this->m_currentPaperDomainCounter[ $domain ] += 1;
    }

    private function parseOutSeparator(){
        // other separator
        $label = $this->m_currentPair[ 0 ];
        $domainLabel = $this->m_currentPair[ 1 ];
        if ( $domainLabel != self::DomainLabel ){
            throw new Exception( '未知的标签: '. $domainLabel );
        }

        if ( $label == self::CorrectAnswerLabel ){
            $this->m_lastColumnType = $this->m_columnType;
            $this->m_columnType = ColumnType::CorrectAnswerColumn;
        } else if ( $label == self::PointLabel ){
            $this->m_lastColumnType = $this->m_columnType;
            $this->m_columnType = ColumnType::QuestionScoreColumn;
        } else{
            throw new Exception( '未知的标签: '. $label );
        }
    }

    // call last
    private function appendToCurrentPaperDomain(){
        // get all the domain
        foreach ( $this->m_domainIndexes as $eachDomain ){
            if ( array_key_exists( $eachDomain, $this->m_currentPaperDomainCounter ) ){
                $this->m_currentPaperMetadata[ "domainTemplate" ][ $eachDomain ] =
                    array( "name" => $this->m_abbrPairs[ $eachDomain ], "count" => $this->m_currentPaperDomainCounter[ $eachDomain ] );
            } else{
                $this->m_currentPaperMetadata[ "domainTemplate" ][ $eachDomain ] =
                    array( "name" => $this->m_abbrPairs[ $eachDomain ], "count" => 0 );
            }
        }
        $this->m_currentPaperMetadata[ "domainTemplate" ]["index"] = $this->m_domainIndexes;
    }

    private function appendToCurrentPaper(){
        $count = count( $this->m_quesTypeAnsSeq );
        $gradeCountInfo = $this->m_quesTypeGradeCount[ $this->m_currentIndex ];
        if ( $gradeCountInfo[ "count"] != $count ){
            throw new Exception('读取题型个数与头部题型个数不匹配, 请检查');
        }
        $score = $gradeCountInfo[ "score" ];
        $points = $gradeCountInfo[ "point" ];
        if ( empty( $score ) ){
            throw new Exception('未知的得分');
        }

        // shift to new question type
        ///
        $labels = $this->makeLabels();
        if ( empty( $labels ) ){
            throw new Exception('unknown labels');
        }

        if ( count( $this->m_quesTypeAnsSeq ) != count( $this->m_quesTypeDomainSeq ) ){
            throw new Exception('读取题型个数与头部题型个数不匹配, 请检查');
        } else{
            $this->m_currentPaperMetadata[ "quesTemplate" ][ $this->m_currentIndex ] = array(
                "count" => $this->m_quesNumber,
                "range" => "1-". $this->m_quesNumber,
                "values" => join( ",", $this->m_quesTypeAnsSeq ),
                "labels" => $labels,
                "domains" => join( ",", $this->m_quesTypeDomainSeq ),
                "type" => $this->getType(),
                "score" => $score,
                "points" => is_array( $points ) ? join( ",", $points ) : $points
            );
            // update
            $this->m_lastColumnType = $this->m_columnType;
        }
    }

    private function getType(){
        switch ( $this->m_lastColumnType ){
            case ColumnType::CorrectAnswerColumn:
                return "TrueOrFalse";
            case ColumnType::QuestionScoreColumn:
                return "Points";
            default:
                return null;
        }
    }

    private function makeLabels(){
        switch ( $this->m_lastColumnType ){
            case ColumnType::CorrectAnswerColumn:
                return "题号,正确答案,您的答案,所属领域";
            case ColumnType::QuestionScoreColumn:
                return "题号,分值,您的分值,所属领域";
            default:
                return null;
        }
    }

    public function parseOutMetaData(){
        if ( $this->m_parseState != ParseState::EndState ){
            throw new Exception( '解析异常' );
        }

        if ( empty( $this->m_headArray )
            || empty( $this->m_templateHeadArrays )
            || empty( $this->m_templateContentArrays )
            || empty( $this->m_abbrPairs )){
            throw new Exception( '文件结构错误, 不是合法的试卷模版' );
        }

        // init global
        $this->m_grades = array();

        $this->m_quesTypeIndexes = array();
        $this->m_domainIndexes = array();

        $this->m_quesTypeGradeCount = array();
        $this->m_paperMetadata = array();

        $this->parseOutHead();
        $this->parseOutTemplate();
    }

    public function startParsing(){
        if ( empty( $this->m_document ) || empty( $this->m_sheet ) ){
            throw new Exception( $this->m_lastExceptionMessage );
        }

        // paper template
        $this->m_parseState = ParseState::StartState;

        // init
        $this->m_templateCount = 0;
        $this->m_templateHeadSpanArray = array();
        $this->m_templateHeadArrays = array();
        $this->m_templateContentArrays = array();
        $this->m_abbrPairs = array();

        $this->m_currentTemplateRow = self::TemplateRowCount;
        foreach( $this->m_sheet->getRowIterator() as $row ){
            switch ( $this->m_parseState ){
                case ParseState::StartState:
                    // if the file is empty
                    // this may result some error
                    $rowIterator = $row->getCellIterator();
                    if ( $rowIterator->valid() ){

                        foreach( $rowIterator as $key => $cell ){
                            $cellValue = trim( $cell->getValue() );
                            if ( $cellValue == self::HeadTitle ){
                                $this->m_parseState = ParseState::HeadState;
                            }
                        }

                        if ( $this->m_parseState == ParseState::StartState ){
                            throw new Exception( '文件结构错误, 不是合法的试卷模版' );
                        }
                    }
                    break;

                case ParseState::HeadState:
                    $this->doHeadParse( $row );
                    break;

                case ParseState::TemplateHeadState:
                    $this->doTemplateHeadParse( $row );
                    break;

                case ParseState::TemplateContentState:
                    $this->doTemplateContentParse( $row );
                    if ( $this->m_currentTemplateRow == 0 ){
                        $this->m_currentTemplateRow = self::TemplateRowCount;
                        $this->m_parseState = ParseState::TemplateHeadState;
                    } break;

                // only occurs to template head
                case ParseState::AbbrState:
                    $this->doAbbrColumnParse( $row );
                    break;

                default:
                    throw new Exception( '内部异常' );
            }
        }
        $this->m_parseState = ParseState::EndState;
    }

    public function getPapers(){
        $papers = array();
        foreach ( $this->m_grades as $eachGrade ){
            $metadata = $this->m_paperMetadata[ $eachGrade ];
            $paper = new ContestPaper( null, $this->m_contestTime
				, str_replace( '\\u', '\\\\u', (str_replace( '\\\\', '\\\\\\\\\\', json_encode( $metadata[ "quesTemplate" ] ) )))
				, str_replace( '\\u', '\\\\u', (json_encode( $metadata[ "domainTemplate" ] )))
                , $this->m_sumScore  /* no full score now */
                , $eachGrade );
            array_push( $papers, $paper );
        }
        return $papers;
    }
}
