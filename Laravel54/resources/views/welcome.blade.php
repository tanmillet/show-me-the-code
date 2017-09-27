<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
    <div class="site-banner">
        <div class="site-banner-bg" style="background-image: url(/img/lucas1.jpg); background-color: #010600;">
        </div>
        <div class="site-banner-main">
            <div class="site-zfj site-zfj-anim">
                <i class="layui-icon" style="color: #fff; color: rgba(255,255,255,.7);"></i>
            </div>
            <div class="layui-anim site-desc site-desc-anim">
                <cite>由职业前端倾情打造，面向所有层次的前后端程序猿，零门槛开箱即用的前端UI解决方案</cite>
            </div>
            <div class="site-download">
                <a href="//res.layui.com/download/layui/layui-v1.0.9_rls.zip" class="layui-inline site-down" target="_blank">
                    <cite class="layui-icon"></cite>
                    立即下载
                </a>
            </div>
            <div class="site-version">
                <span>当前版本：<cite class="site-showv">1.0.9-rls</cite></span>
                <span><a href="http://fly.layui.com/jie/7582.html" target="_blank">更新日志</a></span>
                <span>下载量：<em class="site-showdowns">196667</em></span>
            </div>
            <div class="site-banner-other">
                <iframe src="//ghbtns.com/github-btn.html?user=sentsin&amp;repo=layui&amp;type=watch&amp;count=true" width="105" height="20"></iframe>
                <iframe src="//ghbtns.com/github-btn.html?user=sentsin&amp;repo=layui&amp;type=fork&amp;count=true" width="60" height="20"></iframe>
            </div>
        </div>
    </div>

        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @if (Auth::check())
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ url('/login') }}">Login</a>
                        <a href="{{ url('/register') }}">Register</a>
                    @endif
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    Laravel
                </div>

                <div class="links">
                    <a href="https://laravel.com/docs">Documentation</a>
                    <a href="https://laracasts.com">Laracasts</a>
                    <a href="https://laravel-news.com">News</a>
                    <a href="https://forge.laravel.com">Forge</a>
                    <a href="https://github.com/laravel/laravel">GitHub</a>
                </div>
            </div>
        </div>
    </body>
</html>
