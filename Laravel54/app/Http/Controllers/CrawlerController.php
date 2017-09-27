<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;

    class CrawlerController extends Controller
    {
        //
        public function __construct()
        {

        }

        public function crawler()
        {
            $str = file_get_contents('http://www.stats.gov.cn/tjsj/tjbz/xzqhdm/201703/t20170310_1471429.html');

            dump($str);
        }
    }
