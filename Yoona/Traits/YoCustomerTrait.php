<?php
namespace App\Yoona\Traits;
use Illuminate\Support\Facades\Input;
use App\Yoona\Dtos\YoCustomerInfo;

trait YoCustomerTrait
{
	/**
	 * 获取面签经理的客户信息
	 * @param  [int] $sa_id [面签经理id]
	 */
	public function getMyCustomer($sa_id){
		$query = YoCustomerInfo::leftJoin('yo_cumstom_result','yo_cumstom_result.user_key','=','yo_customer_info.user_key')
			->leftJoin('yo_trial_additional_info','yo_cumstom_result.user_key','=','yo_trial_additional_info.user_key')
			->where('yo_cumstom_result.work_no',$sa_id);

		if (Input::get('real_name')) {
			$query = $query->where('yo_customer_info.real_name',Input::get('real_name'));
		}

		if (Input::get('cert_no')) {
			$query = $query->where('yo_customer_info.cert_no',Input::get('cert_no'));
		}

		if (Input::get('mobile')) {
			$query = $query->where('yo_customer_info.mobile',Input::get('mobile'));
		}

		if (Input::get('user_key')) {
			$query = $query->where('yo_customer_info.user_key',Input::get('user_key'));
		}

		

		if (Input::get('start_created_at') || Input::get('end_created_at')) {
			$start_created_at = !empty(Input::get('start_created_at')) ? Input::get('start_created_at'). ' 00:00:00' : '0000-00-00 00:00:00';
			$end_created_at = !empty(Input::get('end_created_at')) ? Input::get('end_created_at').' 23:59:59' : '2099-01-01 01:01:01';
			$query = $query->whereBetween('yo_customer_info.created_at',array($start_created_at,$end_created_at));
		}

		if (Input::get('product_id')) {
			$query = $query->where('yo_customer_info.id',Input::get('product_id'));
		}

		if (Input::get('information_type')) {
			$query = $query->where('yo_customer_info.information_type',Input::get('information_type'));
		}

		if (Input::get('custom_type')) {
			$query = $query->where('yo_customer_info.custom_type',Input::get('custom_type'));
		}
		$count = $query->count();
		$query = $query->orderby('yo_customer_info.id','desc')->select('yo_customer_info.id','yo_customer_info.*','yo_cumstom_result.status','yo_trial_additional_info.channel')->paginate(15);

		return [$query,$count];

	}
}