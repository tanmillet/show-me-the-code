<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <title>监控</title>
    <script type="text/javascript" src="/pre/js/jquery-2.2.4.min.js"></script>
    <link href="/pre/css/animate.css" rel="stylesheet">
    <script type="text/javascript" src="/pre/component/layer-v3.0.3/layer/layer.js"></script>
    <link rel="stylesheet" href="/pre/component/font-awesome-4.7.0/css/font-awesome.min.css">
    <link href="/pre/css/default.css" rel="stylesheet">
    <script type="text/javascript" src="/pre/js/win10.js"></script>
    <style>
        * {
            font-family: "Microsoft YaHei", 微软雅黑, "MicrosoftJhengHei", 华文细黑, STHeiti, MingLiu
        }
    </style>
</head>
<body>
<div id="win10">
    <img id="win10_img_loader1" class="img-loader" src="/pre/img/wallpapers/windows.jpg">
    <div class="desktop">
        <div id="win10-shortcuts" class="shortcuts-hidden">
            <div class="shortcut" onclick="Win10.openUrl('http://127.0.0.1:8000/terrylucas/premain/','欢迎您！监控页面')">
                <div class="icon"><img src="/pre/img/icon/blogger.png"/></div>
                <div class="title">监控</div>
            </div>
            <div class="shortcut" onclick="Win10.openUrl('http://127.0.0.1:8000/terrylucas/pre/','开发文档')">
                <div class="icon"><img src="/pre/img/icon/doc.png"/></div>
                <div class="title">开发文档</div>
            </div>
            <div class="shortcut" onclick="window.open('https://github.com/tanmillet/precautiontolog')">
                <div class="icon"><img src="/pre/img/icon/github.png"/></div>
                <div class="title">github</div>
            </div>
        </div>
    </div>
</div>
</body>
</html>