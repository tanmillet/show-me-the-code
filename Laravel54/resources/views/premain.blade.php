<!DOCTYPE html>
<head>
    <meta charset="utf-8"/>
    <title>WeChat Manager</title>
    <script src="http://code.jquery.com/jquery-1.10.2.min.js">
    </script>
    <script>
        $(document).ready(function () {
            $(document).keyup(function (event) {
                if (event.keyCode == 13) {
                    $.ajax({
                        type: "POST",
                        url: "/terrylucas/premain/",
                        data: "code=" + $("#in").val(),
                        success: function (msg) {
                            $("ul").append("<li>" + msg + "</li>"); //获取返回值并输出
                        }
                    });
                }
            });
            $("#in")[0].focus();
        });
    </script>
    <style>

        body {
            background-color: #0c0c0c;
            margin: 0px;
            padding: 0px;
            color: #fff;
            font-family: "微软雅黑";
            font-size: 14px;
        }

        .window {
            width: 100%;
        }

        ul {
            margin: 0px;
            padding: 0px;
            list-style: none;
        }

        input {
            background-color: #396da5;
            border: 0;
            color: #fff;
            outline: none;
            width: 50%
        } </style>
</head>
<body>
<div class="window">
    <div id="text">
        <ul>
            <li>代号：CA 含义：签署接口检测</li>
            <li>输入您需要的检测接口代号: <input type="text" name="" id='in'></li>
        </ul>

    </div>
</div>
</body>
</html>