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
 * Time: 2017/1/10 11:17
 * Usage: 客户相关
 * Update:
 */
class CustomerContr extends BaseContr
{

    //客户查询
    public function getCust()
    {
        return view('Yoona.Admin.cust_list');
    }
}