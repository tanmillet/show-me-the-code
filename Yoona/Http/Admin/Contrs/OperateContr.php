<?php
namespace App\Yoona\Http\Admin\Contrs;

use App\Yoona\Http\BaseContr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

/**
 * Author: CHQ
 * Time: 2017/1/10 11:24
 * Usage: 统计相关
 * Update:
 */
class OperateContr extends BaseContr
{

    //统计分析-转化率统计
    public function transformationRatio()
    {
        return view('Yoona.Admin.transformation_ratio');
    }
}