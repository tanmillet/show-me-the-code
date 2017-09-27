<?php
require dirname(__FILE__) ."/../PHPExcel/IOFactory.php";

class ValueExcelParser {
    private $m_contestTime;

    private $m_document;
    private $m_sheet;
    private $m_outputcomment;

    private $m_firstRowRowIndex;

    private $m_titleColumnIndex;    // column index => "string value"

    private $m_InputPaperName;
    public function startParse(){
        $this->m_outputcomment = array();

        if ( !empty( $this->m_sheet ) ){
            $firstRow = $this->m_sheet->getRowIterator();
            if ( !$firstRow->valid() ){
                throw new Exception ( 'excel first row invalid' );
            }

            $rowInstance = $firstRow->current();
            $cellIterator = $rowInstance->getCellIterator();
            if ( !$cellIterator->valid() ) {
                throw new Exception ( 'excel first row invalid' );
            }
            $rowCount = $this->m_sheet->getHighestRow();
            $this->m_firstRowRowIndex = $rowInstance->getRowIndex();

            while($this->m_firstRowRowIndex < $rowCount)
            {
                $nowrow = $this->m_sheet->getRowIterator($this->m_firstRowRowIndex);
                $rowInstance = $nowrow->current();
                $cellIterator = $rowInstance->getCellIterator();
                $this->startParseHead( $cellIterator);
                $this->startParseData( $rowCount);
            }
            $this->check();
            $this->allencode();
        }

        return $this->m_outputcomment;
    }

    const CommentTitle = "评语";
    const AccuracyTitle = "正确率段";

    public function __construct( $excelFilePath, $time ){
        if ( !empty( $excelFilePath ) ){
            try{
                $this->m_document = PHPExcel_IOFactory::load( $excelFilePath );
                if ( !empty( $this->m_document ) ){
                    $sheetCount = $this->m_document->getsheetcount();
                    if ( $sheetCount > 0 ){
                        $this->m_document->setActiveSheetIndex( 0 );
                        $this->m_sheet = $this->m_document->getActiveSheet();
                        $this->m_mergedCellsCache = $this->m_sheet->getMergeCells();
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
            }
        }
    }
    public function getParsedComment(){
        return $this->m_outputcomment;
    }

    private function startParseHead( $cellIterator){
        $this->m_titleColumnIndex = array();
        $titles = array( self::CommentTitle => "comment", self::AccuracyTitle => "accuracy" );
        foreach ( $cellIterator as $key => $value ){
            $cellValue = $value->getValue();
            if ( array_key_exists( $cellValue, $titles ) ){
                $this->m_titleColumnIndex[ $cellValue ] = $key;
            }
            else if($cellValue == '试卷年级')
            {
                $cellIterator->next();
                $this->m_InputPaperName = array();
                for($i = 0;;$cellIterator->next(),$i ++)
                {
                    $value = $cellIterator->current();
                    $cellValue = $value->getValue();
                    if(!empty($cellValue)){
                        $this->m_InputPaperName[$i] = $cellValue;
                    }
                    else{
                        break;
                    }
                }
            }
        }
        if ( $this->m_InputPaperName == null ) {
            throw new Exception ( 'Input Comment has not grade' );
        }
    }

    private function startParseData( $rowCount){
        $commentColumnIndex = $this->m_titleColumnIndex[ self::CommentTitle ];
        $accuracyColumnIndex = $this->m_titleColumnIndex[ self::AccuracyTitle ];
        $duration = 0;
        for ( $rowIndex = $this->m_firstRowRowIndex + 1; $rowIndex <= $rowCount; ++$rowIndex ){

            $accuracy = $this->m_sheet->getCellByColumnAndRow( $accuracyColumnIndex, $rowIndex )->getValue();
            if ( empty( $accuracy ) ){
                continue;
            }
            if ($accuracy == '正确率段'||$accuracy == '评语'||$accuracy == '试卷年级')
            {
                break;
            }
            $comment = $this->m_sheet->getCellByColumnAndRow( $commentColumnIndex, $rowIndex )->getValue();
            $matched = array();
            preg_match_all("/([\\d\\.]+)%-([\\d\\.]+)%/", $accuracy, $matched );
            if ( count( $matched ) == 3)
            {
                $low = $matched[1][0];
                $high = $matched[2][0];
                if($low > $high)
                {
                    //swap low high
                    $temp = $low;
                    $low = $high;
                    $high = $temp;
                }
                if($high == 100)
                    $high = 101;//(90,100]
                $duration += $high - $low;
            }

            $commentObj = array();
            $commentObj[ "start" ] = (int)$low;
            $commentObj[ "end" ] = (int)$high;
            $commentObj[ "comment" ] = $comment;


            $contestant =  json_encode( $commentObj );
            foreach($this->m_InputPaperName as $currentname){
                if ( !array_key_exists(   $currentname,$this->m_outputcomment ) ){
                    $this->m_outputcomment[  $currentname ] = array();
                }
                array_push( $this->m_outputcomment[ $currentname ], $contestant );

            }
        }
        $this->m_firstRowRowIndex = $rowIndex;

    }
    private function check(){
        //check
        foreach($this->m_InputPaperName as $currentname)
        {
            $duration = 0;
            for($i = 0;$i < count($this->m_outputcomment[ $currentname]);$i ++)
            {
                $comment1 = $this->m_outputcomment[ $currentname ][$i];
                $commentdetail1 = json_decode($comment1,true);
                if ($commentdetail1["start"] < 0)
                    throw new Exception ( '请检查正确率段最小值，小于0%' );
                if ($commentdetail1["end"] > 101)
                    throw new Exception ( '请检查正确率段最大值，已经超过101%' );
                $duration += $commentdetail1["end"] - $commentdetail1["start"];

                for($j = $i + 1;$j < count($this->m_outputcomment[ $currentname ]);$j ++)
                {
                    $comment2 = $this->m_outputcomment[ $currentname ][$j];
                    $commentdetail2 = json_decode($comment2,true);
                    //compare
                    if($commentdetail1["start"] > $commentdetail2["start"])
                        $bigstart = $commentdetail1["start"];
                    else
                        $bigstart = $commentdetail2["start"];
                    if($commentdetail1["end"] < $commentdetail2["end"])
                        $smallend = $commentdetail1["end"];
                    else
                        $smallend = $commentdetail2["end"];
                    if($bigstart < $smallend)
                    {
                        throw new Exception ( '请检查正确率段是否有冲突' );
                    }
                }
            }
            if($duration < 99 || $duration > 102)
            {
                throw new Exception ( '请检查正确率段是否有遗漏？' );
            }
        }
    }

    private function allencode(){
        foreach ($this->m_outputcomment as $key => $encodeObject){
            foreach($encodeObject as $key2 => $tabstring)
            {
                $encodeObject[$key2] = json_decode($tabstring,true);
            }
            $this->m_outputcomment[$key] = json_encode($encodeObject);
        }
    }
}


