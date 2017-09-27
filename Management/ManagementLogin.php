<?php
session_start();
?>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="css/mangementlogin_main.css">
</head>
<body>
<div class="container">
    <div class="queryPanel">

        <div class="companyTitle">
            <a>成绩上传管理</a>
        </div>

        <div class="fieldsetContainer">
            <form action="Management.php" method="post">
                <div class="inputWrapper">
                    <input type="text" name="account" placeholder="管理员账号" autofocus required>
                </div>

                <div class="inputWrapper">
                    <input type="password" name="password" placeholder="密码" autofocus required>
                </div>

                <div class="submitWrapper">
                    <input type="submit" name="queryButton" value="登录">
                </div>
            </form>
        </div>

    </div>
</div>
</body>

</html>