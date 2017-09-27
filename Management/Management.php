<?php
session_start();

if ( isset( $_POST['account'] ) && !empty( $_POST['account'] )
&& isset( $_POST['password'] ) && !empty( $_POST['password'] ) ){
    if ( $_POST['account'] != "admin" || $_POST['password'] != "admin" ){
        header("Location: ./ManagementLogin.php");
        die();
    }
} else{
    header("Location: ./ManagementLogin.php");
    die();
}
?>

<html>
    <head>
        <meta charset="utf-8">
        <script src="js_vendors/jquery-1.9.1-min.js"></script>

        <script src="js_vendors/jquery.ui.widget.js"></script>
        <script src="js_vendors/jquery.ui.progressbar.min.js"></script>
        <script src="js_vendors/jquery.iframe-transport.js"></script>
        <script src="js_vendors/jquery.fileupload.js"></script>
        <script src="js_vendors/jquery.xdr-transport.js"></script>

        <script src="js_vendors/spin-min.js"></script>
        <link rel="stylesheet" type="text/css" href="css/management_main.css">
 
        <script src="js/management_main_v1.js" charset="utf-8"></script>
    </head>

    <body>
        <div class="container">
            <div class="containerMask">
                <div class="editBox commonDialog">
                    <div class="boxHead"><a>修改</a></div>

                    <div class="editContent">
                        <div class="editInfoWrapper nameEdit">
                            <a>姓名</a>
                            <input type="text" value="" infoType="name">
                        </div>
                        <div class="editInfoWrapper schoolEdit">
                            <a>学校</a>
                            <input type="text" value="" infoType="school">
                        </div>

                        <div class="editInfoWrapper scoreEdit">
                            <a>该名学生的总分为：</a>
                            <span>0</span>
                        </div>

                        <div class="editScoreWrapper">

                        </div>
                    </div>

                    <div class="dialogButtonGroup">
                        <input class="commonButton editOK" type="button" value="确定">
                        <input class="commonButton editCancel" type="button" value="取消">
                    </div>

                </div>
                <div class="actionBox commonDialog">
                    <div class="boxHead"><a>修改情况</a><div class="dialogExit"></div></div>
                    <div class="actionTableWrapper">
                        <table class="actionTable" rules="col">
                            <tbody>
                            <tr>
                                <th></th>
                                <th><a>报名号</a></th>
                                <th><a>动作</a></th>
                                <th><a>详细信息</a></th>
                            </tr>
                            </tbody>
                            <tbody class="actionTableData">
                            </tbody>
                        </table>
                    </div>

                    <div class="helperButtonGroup">
                        <input class="actionSelectAll" type="checkbox"><a>全选</a>
                        <input class="actionSelectReverse" type="checkbox"><a>反选</a>
                    </div>

                    <div class="dialogButtonGroup">
                        <input class="commonButton removeModifications" type="button" value="撤除修改">
                        <input class="commonButton submitModifications" type="submit" value="提交修改">
                    </div>
                </div>
                <div class="exportBox commonDialog">
                    <div class="boxHead"><a>导出</a></div>
                    <div class="selectColumns">
                        <div class="selectColumn">
                            <a>年份</a>
                            <select class="yearSelect">
                            </select>
                        </div>

                        <div class="selectColumn">
                            <a>年级</a>
                            <select class="gradeSelect">
                            </select>
                        </div>
                    </div>

                    <p class="exportResult"></p>
                    <div class="dialogButtonGroup">
                        <input class="commonButton exportOK" type="button" value="确定">
                        <input class="commonButton exportCancel" type="button" value="取消">
                    </div>
                </div>
                <div class="uploadProgressBox">
                    <div class="progressBar"><div class="progress"></div></div>
                    <div class="uploadMessageLabel uploadActionMessage"><a class="actionLabel"></a><b class="fileLabel"></b><b class="fileSizeLabel"></b></div>
                    <div class="uploadMessageLabel uploadResultMessage"><a>文件处理可能需要一些时间,请稍等。</a></div>
                    <input type="button" class="uploadOK" value="确定">
                </div>
            </div>

            <div class="header">
                <div class="leftHeader">
                    <div class='logoWrapper'>
                        <img class='logo' src='img/36logo.gif'>
                        <span>奥林教育</span>
                    </div>

                    <div class="loginUser">
                        <div class="loginUserWrapper">
                            <div class="userAction"></div>
                            <a class="loginPortrait">
                                <img src="img/avatar.png">
                            </a>
                        </div>
                    </div>

                    <div class="searchColumn">
                        <input class="search sctl" type="text">
                        <div class="searchSubmitWrapper">
                            <input class="searchSubmit" type="submit" value="">
                        </div>
                    </div>

                    <div class="searchHelperDropDown">
                        <div class="helperRow" param="applyID">
                            <a>报名号</a>
                            <input type="text" class="textInput sctl">
                        </div>

                        <div class="helperRow" param="name">
                            <a>姓名</a>
                            <input class="textInput sctl" type="text">
                        </div>

                        <div class="helperRow" param="grade">
                            <a>年级</a>
                            <input class="textInput sctl" type="text">
                        </div>

                        <div class="helperRow" param="score">
                            <a>总分</a>
                            <input type="text" class="numberInput rangeStartInput sctl">
                            <span>-</span>
                            <input class="numberInput rangeEndInput sctl" type="text">
                        </div>
                    </div>

                    <div class="dropDownUserAction">
                        <a class="loginUsername">Admin</a>
                        <a class="exit">离开</a>
                    </div>

                </div>

                <table class="headTabs">
                    <tr>
                        <td class="headTab"><div class="headTabInner" ref="0"><img src="img/uploadpaper.png"><a>试卷模版上传</a></div></td>
                        <td class="headTab"><div class="headTabInner" ref="1"><img src="img/uploadscores.png"><a>成绩上传</a></div></td>
                        <td class="headTab"><div class="headTabInner" ref="2"><img src="img/addcomment.png"><a>成绩评价上传</a></div></td>
                        <td class="headTab"><div class="headTabInner" ref="3"><img src="img/queryscores.png"><a>学生成绩编辑</a></div></td>
                    </tr>
                </table>
            </div>

            <div class="center">
                <div class="bottomActionBox">
                    <table class="panelActions">
                        <tr>
                            <td><div class="actionBoxEntry"><div></div></div></td>
                            <td class="action remove"><a>删除</a></td>
                            <td class="action edit"><a>编辑</a></td>
                            <td class="action selectAll"><a>全选</a></td>
                            <td class="action export"><a>导出</a></td>
                        </tr>
                    </table>
                </div>


                <div class="content">
                    <div class="listPages">
                        <div class="page uploadPaperTemplate">
                            <div class="templateDownload" href="doc/template/试卷模版.xlsx">
                                <div class="downloadTemplateTipsWrapper">
                                    <div class="downloadTemplateTips">
                                        <a>下载试卷模版</a>
                                    </div>
                                    <div class="downloadTemplateIcon"></div>
                                </div>
                            </div>
                            <div class="notice"><a>若替换已存在的试卷，则关联该试卷的绩被成清空</a></div>
                            <input class="paperUploader" type="file" name="paperFile" data-url="UploadPaperTemplate.php">
                            <div class="dropArea">
                                <div class="fileAdd"><a>+</a></div>
                            </div>
                        </div>

                        <div class="page uploadScoresTemplate">
                            <div class="templateDownload" href="doc/template/XXXX年成绩表.xlsx">
                                <div class="downloadTemplateTipsWrapper">
                                    <div class="downloadTemplateTips">
                                        <a>下载成绩模版</a>
                                    </div>
                                    <div class="downloadTemplateIcon"></div>
                                </div>
                            </div>
                            <div class="notice"><a>注意：请将下载后的文件名添加对应的年份，如“2013年九年级学生成绩”或“八年级成绩2014年”。可以多次更新哦~</a></div>
                            <input class="scoreUploader" type="file" name="scoreFile" data-url="UploadScores.php">
                            <div class="dropArea">
                                <div class="fileAdd"><a>+</a></div>
                            </div>
                        </div>

                        <div class="page uploadCommentTemplate">
                            <div class="templateDownload" href="doc/template/评语模版_请改名添加对应的年份_如2013年试卷评语.xlsx">
                                <div class="downloadTemplateTipsWrapper">
                                    <div class="downloadTemplateTips">
                                        <a>下载评语模版</a>
                                    </div>
                                    <div class="downloadTemplateIcon"></div>
                                </div>
                            </div>
                            <div class="notice"><a>注意：请将下载后的模版添加对应的年份，如“2013年评语”</a></div>
                            <input class="commentUploader" type="file" name="commentFile" data-url="UploadComment.php">
                            <div class="dropArea">
                                <div class="fileAdd"><a>+</a></div>
                            </div>
                        </div>

                        <div class="page queryPanel">

                            <div class="contestantsTableWrapper">
                                <table class="contestantsTable">
                                    <tbody>
                                    <tr>
                                        <th class="title"> </th>
                                        <th colspan="2" class="title"><a>报名号</a></th>
                                        <th class="title"><a>姓名</a></th>
                                        <th class="title"><a>年级</a></th>
                                        <th colspan="2" class="title"><a>学校</a></th>
                                        <th class="title"><a>总分</a></th>
                                        <th colspan="2" class="title"> </th>
                                    </tr>
                                    </tbody>
                                    <tbody class="dataRows" >
                                    </tbody>
                                </table>
                            </div>

                            <div class="numberedNavigator">
                                <div class="jumpButton backButton"><a>&lt;&lt;</a></div>
                                <div class="numbered" index="0"><a>1</a></div>
                                <div class="numbered" index="1"><a>2</a></div>
                                <div class="numbered" index="2"><a>3</a></div>
                                <div class="numbered" index="3"><a>4</a></div>
                                <div class="numbered" index="4"><a>5</a></div>
                                <div class="jumpButton forwardButton"><a>&gt;&gt;</a></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="footer"></div>
        </div>
    </body>

</html>

