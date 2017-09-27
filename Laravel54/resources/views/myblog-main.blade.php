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
            <!-- start header-->
            <div class="header">
                <div class="logo"><a href="index.html"><img src="/myblog/images/logo.png" alt=""></a></div>
                <div class="cssmenu">
                    <ul>
                        <li><a href="/">Home</a></li>
                        <li><a href="portfolio.html">Life</a></li>
                        <li class="active"><a href="/myblog1">Blog</a></li>
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
            <!-- start content -->
            <div class="main1">
                <div class="content">
                    <h2><span>PHP</span> <span>Laravel</span> <span>OOP</span> <span>PATTERN</span></h2>
                </div>
                <div class="blog">
                    <div class="blog_list">
                        <h4><a href="">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</a>
                        </h4>
                        <h5>January 3rd, 2013 , Posted by&nbsp;<a href="index.html">H. Rackham</a></h5>
                        <div class="blog_para">
                            <p class="para1"><a href="details.html" rel="lightbox"><img src="/myblog/images/pic9.jpg"
                                                                                        alt=""></a>"On the other hand,
                                we denounce with righteous indignation and dislike men who are so beguiled and
                                demoralized by the charms of pleasure of the moment, so blinded by desire, that they
                                cannot foresee the pain and trouble that are bound to ensue; and equal blame belongs to
                                those who fail in their duty through weakness of will,</p>
                            <div class="read_more"><a class="btn" href="details.html">Read More</a></div>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="blog_list">
                        <h4><a href="">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</a>
                        </h4>
                        <h5>march 26th, 2013 , Posted by&nbsp;<a href="index.html">J. Smith</a></h5>
                        <div class="blog_para">
                            <p class="para1"><a href="details.html" rel="lightbox"><img src="/myblog/images/pic10.jpg"
                                                                                        alt=""></a>"On the other hand,
                                we denounce with righteous indignation and dislike men who are so beguiled and
                                demoralized by the charms of pleasure of the moment, so blinded by desire, that they
                                cannot foresee the pain and trouble that are bound to ensue; and equal blame belongs to
                                those who fail in their duty through weakness of will,</p>
                            <div class="read_more"><a class="btn" href="details.html">Read More</a></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="blog_list">
                        <h4><a href="">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</a>
                        </h4>
                        <h5>Auguest 3rd, 2013 , Posted by&nbsp;<a href="index.html">H. Rackham</a></h5>
                        <div class="blog_para">
                            <p class="para1"><a href="details.html" rel="lightbox"><img src="/myblog/images/pic11.jpg"
                                                                                        alt=""></a>"On the other hand,
                                we denounce with righteous indignation and dislike men who are so beguiled and
                                demoralized by the charms of pleasure of the moment, so blinded by desire, that they
                                cannot foresee the pain and trouble that are bound to ensue; and equal blame belongs to
                                those who fail in their duty through weakness of will,</p>
                            <div class="read_more"><a class="btn" href="details.html">Read More</a></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>