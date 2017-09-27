<?php
session_start();

// browser detect
require "BrowserDetector.php";

$browserInfo = BrowserDetector::Detects();

$browserSupportPattern = array (
    'MSIE' => 10,
    'Firefox' => 5,
    'Chrome' => 6,
    'Safari' => 5.1,
    'Opera' => 13,
);

$browserName = $browserInfo['name'];
if ( !array_key_exists( $browserName, $browserSupportPattern )
    || $browserInfo['version'] < $browserSupportPattern[ $browserName ] ){
    echo '<html>
            <head>
                <meta charset="utf-8">
                <script type="text/javascript">
                    window.onload = function(){
                        setTimeout( function(){
                           window.history.back();
                        }, 5000);
                    }
                </script>
            </head>
            <body>
                <p>您的浏览器版本太低, 请使用IE 10, Chrome 5, FireFox 4, Safari 5.1,Opera 12 以上版本</p>
            </body>
           </html>';
    session_unset();
    session_destroy();
    exit( 0 );
}

?>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="css/query_main.css">
<?php
    $isQueryFailed = null;
    if ( isset( $_SESSION['_flagQueryFailed'] ) && !empty( $_SESSION['_flagQueryFailed'] )){
        $isQueryFailed = $_SESSION['_flagQueryFailed'];
    }

    if ( !empty( $isQueryFailed ) && $isQueryFailed == true ){
        unset( $_SESSION['_flagQueryFailed'] );
        echo '<link rel="stylesheet" type="text/css" href="css/query_failed.css">';
        echo '
        <script>
            window.onload = function(){
                var container = document.getElementsByClassName("container")[0];
                if (container != null){
                    var msgBox = document.createElement("div");
                    msgBox.className = "failedMsgBox";
                    msgBox.style.left = ( container.offsetLeft - 30 ).toString() + "px";
                    msgBox.style.top = ( container.offsetTop - 80 ).toString() + "px";

                    var label = document.createElement("span");
                    label.className  = "failedMsgLabel";
                    label.innerHTML = "输入姓名或报考号未能匹配";

                    msgBox.appendChild( label );
                    document.body.appendChild( msgBox );
                }
            }
        </script>';
    }
?>
</head>
<body>
<div class="container">
    <div class="queryPanel">

        <div class="companyTitle">
            <a>全国初中数学联赛</a>
        </div>

        <div class="queryTitleBackground">
            <div class="queryTitle">
                <a>成绩查询</a>
            </div>
        </div>

        <div class="fieldsetContainer">
            <form action="Auth.php" method="post">
                <div class="inputWrapper">
                    <input type="text" name="queryId" placeholder="报考号" autofocus required>
                </div>

                <div class="inputWrapper">
                    <input type="text" name="queryName" placeholder="名字" autofocus required>
                </div>

                <div class="submitWrapper">
                    <input type="submit" name="queryButton" value="查询">
                </div>
            </form>
        </div>

    </div>
</div>
</body>

</html>