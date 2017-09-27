<?php
/**
 * Created by PhpStorm.
 * User: Promise tan
 * Date: 2017/1/3
 * Time: 17:44
 */

namespace App\Yoona\Http\Admin\Contrs;

use App\Yoona\Traits\YoCustomerTrait;
use App\Yoona\Http\BaseContr;
use App\Util\AdminAuth;

class LoanOrderContr extends BaseContr
{
	use YoCustomerTrait;

    //面签订单管理
    public function faceSign(){
    	$query = self::getMyCustomer(AdminAuth::user()->work_no);
    	$page_num = $query['1']/15;   	
    	
        return view('Yoona.Admin.face_sign')->with('res',$query['0'])->with('page_num',$page_num);
    }
}