<?php
session_start();

date_default_timezone_set('PRC');

require_once "Classes/Objects/ApplyContestant.php";
require_once "Classes/Services/StatScoreService.php";

function failedExit(){
    header("Location: ./QueryScore.php");
    session_unset();
    session_destroy();
    die ( "访问失败,即将跳转,尝试重新查询" );
}

if ( !isset( $_SESSION['contestant'] ) || empty( $_SESSION['contestant'] ) ){
    failedExit();
}

$contestant = unserialize( $_SESSION['contestant'] );
$paper = null;
$statAmongSchool = null;
$statAmongRegion = null;

$showSchoolRank = null;
$showRegionRank = null;
if ( isset( $contestant ) && !empty( $contestant ) ){
    $paper = $contestant->getPaper();
    if ( empty( $paper ) ){
        failedExit();
    }

    $statAmongSchool = StatScoreService::obtainAvgStatScores( $paper->getPaperID(), $contestant->_school );
    $statAmongRegion = StatScoreService::obtainAvgStatScores( $paper->getPaperID(), "全省" );

    $showSchoolRank = $contestant->getSchoolRank();
    $showRegionRank = $contestant->getRegionRank();

    if ( $statAmongSchool == null || $statAmongRegion == null ){
        failedExit();
    }
} else{
    failedExit();
}
?>
<html>
    <head>
        <meta charset='utf-8'>
        <script src="js/pre_v1.js" charset="UTF8"></script>
        <link rel="stylesheet" type="text/css" href="css/queryresult_main.css" media="screen">
        <link rel="stylesheet" type="text/css" href="css/queryresult_print.css" media="print">
<?php
        if ( $paper->_fullScore == $contestant->_score ){
            echo '
            <link rel="stylesheet" type="text/css" href="css/effect_fullscore.css" media="screen">
            <link rel="stylesheet" type="text/css" href="css/effect_fullscore_print.css" media="print">';
        }
?>
        <script>
            function ready (){
                var el = document.getElementById( 'nav' );
                el.style.cssText = 'visibility: visible; display: block; position: absolute; width: 50px; height: 100px; left: 840px; top: ' + ( getWindowHeight() - 220 ).toString() + 'px';

                var scEl = document.getElementsByClassName( 'scrollToTop' )[ 0 ];
                scEl.onclick = function(){ scrollToAnchor( 0 ); };

                var prEl = document.getElementsByClassName( 'print' )[ 0 ];
                prEl.onclick = function(){ window.print(); };
                window.onscroll = scrollCallback;
            }
        </script>
    </head>
    <body onload="ready()">
<?php
    echo "<div class='header'>
               <div class='headerWrapper'>
                    <div class='headerLeft'>
                        <div class='headerInfoWrapper'>
                            <a class='contestantName'>{$contestant->_name}</a>
                            <a>{$contestant->_school}</a>
                            <a>{$paper->_forGrade}</a>
                        </div>
                    </div>

                    <span>{$paper->_contestTime}年全国初中数学联赛成绩分析</span>
               </div>
                <div class='headerRight'>
                    <span>奥林教育</span>
                    <img class='logo' src='img/36logo.gif'></div>
                </div>
           </div>
        <div class='container'>
            <div id='nav'>
                <div class='toolButton scrollToTop'><div><table><tr><td>移至</td></tr><tr><td>顶部</td></tr></table></div></div>
                <div class='toolButton print'><div><table><tr><td>打印</td></tr><tr><td>页面</td></tr></table></div></div>
            </div>
            <div class='content'>
                <div class='paperWrapper'>
                    <div class='paperHeader'>
                        <div class='paperTitle'><a>下表为您参加{$paper->_contestTime}全国初中数学联赛的成绩，以及您在不同的数学领域所得到的成绩：</a></div>
                    </div>
                    <div class='firstcolumn'>
                    <div class='scoreCell cell'>
                        <div class='fullScoreStamp'></div>
                        <div class='cellMask'></div>
                        <p>您的试卷得分为</p>
                        <b>{$contestant->_score}</b><a>分</a>
                    </div>
                    <div class='rankCell cell'>
                        <p>您击败了所在学校</p>
                            <b id='schoolRank'>0.00%</b><a>的同学</a>
                        <p>您击败了全省</p>
                            <b id='regionRank'>0.00%</b><a>的考生</a>
                    </div>
                    </div>
                    <div id='detailWrapper'>
                        <div class='answerTitle'><a>您在{$paper->_forGrade}的作答结果</a></div>
                        <div id='tableWrapper'></div>
                    </div>
                    <div class='column'>
                        <div class='scoreCompareCell cell'>
                                <p>您的总得分</p>
                                <div class='scoreWrapper'><div class='horizontalbar' id='yourScore'><a></a></div></div>
                                <p>您学校的平均得分</p>
                                <div class='scoreWrapper'><div class='horizontalbar' id='schoolAvgScore'><a></a></div></div>
                                <p>全省的平均得分</p>
                                <div class='scoreWrapper'><div class='horizontalbar' id='allAvgScore'><a></a></div></div>
                        </div>
                        <div class='domainCompareCell cell'>
                                <p>您答题的总题数</p>
                                <div class='domainWrapper'><div class='horizontalbar' id='yourDomain'><a></a></div></div>
                                <p>您学校答对的平均总题数</p>
                                <div class='domainWrapper'><div class='horizontalbar' id='schoolAvgDomain'><a></a></div></div>
                                <p>全省答对的平均总题数</p>
                                <div class='domainWrapper'><div class='horizontalbar' id='allAvgDomain'><a></a></div></div>
                        </div>

                    </div>
                    <div class='column'>
                        <div class='answerTitle'><a>您的题型得分如下</a></div>
                        <div id='quesTypeTable'></div>
                    </div>

                    <div class='column'>
                        <div class='answerTitle'><a>本年度试卷构成与成绩分析</a></div>
                        <div id='domainTable'></div>
                    </div>

                    <div class='footerHolder'></div>
                </div>
           </div>
        </div>
";
    $formatedQuesTypeTemplate = str_replace( "\\\\", "\\", $paper->_questionTypeTemplate );
    echo "
        <script>
            var paperTemplate = new PaperTemplate( $paper->_fullScore, '$formatedQuesTypeTemplate', '$paper->_domainTemplate' );
            if ( paperTemplate.isValid() ){
                paperTemplate.initTemplate();
                var score = new Scores( paperTemplate, $contestant->_score, '$contestant->_scoreRawDetail', '$statAmongSchool', '$statAmongRegion' );

               LoadQuesTypeScoreTableColumn( score.getMappedQuestionTypeScoreDataset() );
               LoadDomainTypeTableColumn( score.getMappedDomainAnalysisDataset() );

               var schoolRank = 0.00;
               var regionRank = 0.00;
               if ( score._selfScore != 0 ){
                    if ( $showSchoolRank == 0 ){
                        schoolRank = 99.99;
                    } else{
                        schoolRank = 100 * ( 1 - ( ( $showSchoolRank ).toFixed( 3 ) / ( score._schoolDetail['totalcount'] ).toFixed( 3 ) ) );
                    }

                   if ( $showRegionRank == 0 ){
                        regionRank = 99.99;
                   } else{
                        regionRank = 100 * ( 1 - ( (  $showRegionRank ).toFixed( 3 ) / score._regionDetail['totalcount'] ) );
                   }
               }

               document.getElementById( 'schoolRank' ).innerHTML = schoolRank.toFixed( 2 ) + '%';
               document.getElementById( 'regionRank' ).innerHTML = regionRank.toFixed( 2 ) + '%';

               LoadTable(score);
               tablecloth();
            }
        </script>";

?>
    </body>
</html>