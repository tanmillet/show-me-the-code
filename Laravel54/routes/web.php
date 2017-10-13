<?php

    /*
    |--------------------------------------------------------------------------
    | Web Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register web routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | contains the "web" middleware group. Now create something great!
    |
    */

Route::group(['prefix' => 'admin'], function () {
    \TCG\Voyager\Facades\Voyager::routes();
});

//     Route::get(
//         '/',
//         function () {
//
//             app('lucaspattern')->exc();
//
//             return 'ok';
// //            return view('select');
//
//             // $rb = function (){
//             //   static $i = 9;
//             //   $i++;
//             //
//             //   return $i;
//             // };
//             //
//             // dump($rb());
//             // dump($rb());
//             // dump($rb());
//             //
//             // die();
//             // $content = file_get_contents(storage_path('logs/laravel-11-analysis-info-2017-07-25.log'));
//             //
//             // preg_match_all('|\[[\d-]+\s([\d:]+)\]\s[^:]*:\s([a-zA-Z1-9-_]+)|',$content,$matches);
//             // dump($matches);
//             // $rb = function (){
//             //     $items = [];
//             //     for($i = 0;$i < 1440;$i++){
//             //         $items[] = 0;
//             //     }
//             //     return $items;
//             // };
//             // $datas['ca_success'] = $rb();
//             // foreach($matches[2] as $k=>$v){
//             //     dump($v);
//             //     if(!isset($datas[$v])){
//             //         continue;
//             //     }
//             //
//             //     //把分钟数转换成下标
//             //     $ind = \Carbon\Carbon::parse($matches[1][$k])->hour * 60 + \Carbon\Carbon::parse($matches[1][$k])->minute;
//             //     $datas[$v][$ind] += 1;
//             // }
//             //
//             // dump($datas);
//             //
//             // die();
//             $total = 1000000;
//             for ($done = 1; $done <= $total; $done++) {
//                 show_status($done, $total);
//             }
//             // return view('myblog-index');
//         }
//     );
//
//     Route::get(
//         '/myblog1',
//         function () {
//             return view('myblog-main');
//         }
//     );
//
//     Route::match(['get', 'post'], '/crawler', 'CrawlerController@crawler')->name('crawler');
//     Route::match(['get', 'post'], '/provinces', 'LucasCityController@provinces')->name('provinces');
//     Route::match(['get', 'post'], '/downtowns/{province}', 'LucasCityController@downtowns')->name('downtowns');
//     Route::match(['get', 'post'], '/countys/{downtown}', 'LucasCityController@countys')->name('countys');
//
//     function show_status($done, $total, $size = 30)
//     {
//
//         static $start_time;
//
//         // if we go over our bound, just ignore it
//         if ($done > $total) {
//             return;
//         }
//
//         if (empty($start_time)) {
//             $start_time = time();
//         }
//         $now = time();
//
//         $perc = (double)($done / $total);
//
//         $bar = floor($perc * $size);
//
//         $status_bar = "\r[";
//         $status_bar .= str_repeat("=", $bar);
//         if ($bar < $size) {
//             $status_bar .= ">";
//             $status_bar .= str_repeat(" ", $size - $bar);
//         } else {
//             $status_bar .= "=";
//         }
//
//         $disp = number_format($perc * 100, 0);
//
//         $status_bar .= "] $disp%  $done/$total";
//
//         $rate = ($now - $start_time) / $done;
//         $left = $total - $done;
//         $eta = round($rate * $left, 2);
//
//         $elapsed = $now - $start_time;
//
//         $status_bar .= " remaining: ".number_format($eta)." sec.  elapsed: ".number_format($elapsed)." sec.";
//
//         echo "$status_bar  ";
//
//         flush();
//
//         // when done, send a newline
//         if ($done == $total) {
//             echo "\n";
//         }
//
//     }

    // Route::match(['get', 'post'], '/terrylucas/pre/', 'PrecautionController@index')->name('precaution');
