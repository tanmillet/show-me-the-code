<?php
/**
 * Created by PhpStorm.
 * User: Promise tan
 * Date: 2017/1/3
 * Time: 17:44
 */

namespace App\Yoona\Http\Admin\Contrs;


use App\Yoona\Http\BaseContr;

class LogContr extends BaseContr
{

    public function reactIndex()
    {

        return view('Yoona.Admin.react');
    }
}