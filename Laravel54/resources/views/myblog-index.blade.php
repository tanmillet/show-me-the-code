<!DOCTYPE HTML>
<html>
<head>
    <title>Terry Lucas | Blog :: Min Blog</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link href="/myblog/css/style.css" rel="stylesheet" type="text/css" media="all"/>
    <script src="/myblog/js/jquery.min.js"></script>
    <script>
        $(function () {
            var pull = $('#pull');
            menu = $('nav ul');
            menuHeight = menu.height();

            $(pull).on('click', function (e) {
                e.preventDefault();
                menu.slideToggle();
            });

            $(window).resize(function () {
                var w = $(window).width();
                if (w > 320 && menu.is(':hidden')) {
                    menu.removeAttr('style');
                }
            });
        });
    </script>
</head>
<body>
<div class="body">
    <div class="wrap">
        <div class="wrapper">
            <div class="header">
                <div class="logo"><a href="index.html"><img src="/myblog/images/logo.png" alt=""></a></div>
                <div class="cssmenu">
                    <ul>
                        <li class="active"><a href="index.html">Home</a></li>
                        <li><a href="portfolio.html">Life</a></li>
                        <li><a href="/myblog1">Blog</a></li>
                        <li><a href="about.html">About</a></li>
                        <div class="clear"></div>
                    </ul>
                </div>
                <div class="clear"></div>
                <div class="top-nav">
                    <nav class="clearfix">
                        <ul>
                            <li><a href="index.html">Home</a></li>
                            <li><a href="portfolio.html">Life</a></li>
                            <li><a href="blog.html">Blog</a></li>
                        </ul>
                        <a href="#" id="pull">Menu</a></nav>
                </div>
            </div>
            <div class="banner"><img src="/myblog/images/banner.png" alt=""/>
                <h2>Do one thing at a time, and do well.</h2>
                <h3>Do one thing at a time, and do well.</h3>
            </div>
        </div>
    </div>
</div>
</body>
</html>