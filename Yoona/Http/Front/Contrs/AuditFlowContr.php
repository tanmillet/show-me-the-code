<?php
/**
 * Created by PhpStorm.
 * User: Promise tan
 * Date: 2017/1/3
 * Time: 17:44
 */

namespace App\Yoona\Http\Front\Contrs;

use App\Yoona\Dtos\YoCustomerStatus;
use App\Yoona\Http\ApiContr;
use Illuminate\Support\Facades\Request;

/**
 *
 * @功能 面审和协录
 *
 * Class AuditFlowContr
 * Author: promise tan
 * @package App\Yoona\Http\Front\Contrs
 */
class AuditFlowContr extends ApiContr
{
    /**
     * @author: promise tan
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function lists()
    {
        //获取需要面签者的客户列表
        $customers = $this->model('YoCustomerResult', false)
            ->with('customer')
            ->where(['status' => YoCustomerStatus::$CS_AUDIT_PREPARE])->get();

        $opCustomerType = 'audit';

        return view('Yoona.Front.S.lists', compact('customers', 'opCustomerType'));
    }


    /**
     * @author: promise tan
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function infolist($type, $userKey)
    {
        $customer = $this->model('YoCustomerInfo')->where(['user_key' => $userKey])->first();
        if (empty($customer)) {
            return redirect('yo/404'); //TODO
        }

        $tokenNum = (empty($customer)) ? 0 : $customer->token_num;

        return view('Yoona.Front.S.infolist', compact('tokenNum', 'userKey'));
    }

    /**
     * @author: promise tan
     * @param $type
     * @param $userKey
     * @return \Illuminate\Contracts\View\Factory|
     * \Illuminate\Http\RedirectResponse|
     * \Illuminate\Routing\Redirector|\Illuminate\View\View|string
     */
    public function info($type, $userKey)
    {
        //数据显示
        $src = yoconf('constant')['AuditSrc'];
        $rst = collect($src)->contains('sign', $type);

        if (!$rst) {
            return ((Request::isMethod('POST') && Request::ajax()))
                ? $this->responseError('正确操作客户信息！')
                : redirect('yo/404'); //TODO
        }

        $auditSrc = collect($src)->map(function ($val, $index) use ($type) {
            if ($val['sign'] === $type) {
                return $val;
            }
        })->reject(function ($val) {
            return empty($val);
        })->first();

        //数据更新
        if (Request::isMethod('POST') && Request::ajax()) {
            $validator = new $auditSrc['validator'];

            $inputs = $validator->setValidateParams(Request::all())->valid();

            if (!empty($inputs->getValidatorResMsg())) {
                return $this->responseError($inputs->getValidatorResMsg());
            }

            $rst = $this->{$auditSrc['upmethod']}(['user_key' => $userKey], $inputs->getValidateParams());

            return ($rst) ? $this->responseSuccess('更新成功！')  : $this->responseError('更新失败！') ;
        }

        //数据显示
        $info = tranfer()->tranferForm(
            $this->model($auditSrc['model'], false),
            ['user_key' => $userKey]
        )->getOutputs();

        return view('Yoona.Front.S.' . $auditSrc['viewName'], compact('info', 'auditSrc' , 'userKey'));
    }

    /**
     * @author: promise tan
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function uploadinfo($userKey)
    {
        $pics = $this->model('YoPicType')->where(['project_name' => 'yopic'])->get(); //TODO 项目名称 为默认值
        $customerPics = $this->model('YoTrialPic')->where(['user_key' => $userKey])->get();

        $customerPicTypeIds = $customerPics->map(function ($val, $index) {
            return $val->type;
        })->flatten()->toArray();

        $pics = $pics->map(function ($val, $index) use ($customerPicTypeIds) {
            $val->isOwerPic = 0;
            if (in_array($val->id, $customerPicTypeIds)) {
                $val->isOwerPic = 1;
            }

            return $val;
        });

        return view('Yoona.Front.S.uploadinfo', compact('pics', 'userKey'));
    }

    /**
     * @author: promise tan
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pic($type, $userKey)
    {
        //数据更新 TODO
        if (Request::isMethod('POST')) {


            echo '数据更新';
            die();
        }

        //数据显示
        $pics = $this->model('YoPicType')->where(['project_name' => 'yopic'])->get(); //TODO 项目名称 为默认值
        $rst = $pics->contains('type_sign', $type);
        if ($rst) {
            $picSrc = $pics->map(function ($val, $index) use ($type) {
                if ($val['type_sign'] === $type) {
                    return $val;
                }
            })->reject(function ($val) {
                return empty($val);
            })->first()->toArray();

        } else {
            return redirect('yo/404'); //TODO
        }

        $customerPics = $this->model('YoTrialPic')->where([
            'user_key' => $userKey,
            'type' => $picSrc['id']
        ])->get();

        return view('Yoona.Front.S.' . $picSrc['type_sign'], compact('customerPics', 'userKey'));
    }

    /**
     * @author: promise tan
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function success($type, $userKey)
    {
        $customer = $this->model('YoCustomerResult', false)
            ->with('customer')
            ->where([
                'user_key' => $userKey,
                'work_no' => workNo()
            ])->first();

        $src = yoconf('constant')['AuditStep'];
        $rst = collect($src)->contains('sign', $type);

        $type = ($rst) ? $type : 'default';

        $topMsg = collect($src)->map(function ($val, $index) use ($type, $userKey) {
            if ($val['sign'] === $type) {
                $val['op']['url'] = ($val['sign'] == 'interview') ? $val['op']['url'] . $userKey : $val['op']['url'];
                return $val;
            }
        })->reject(function ($val) {
            return empty($val);
        })->first();

        return view('Yoona.Front.S.success', compact('customer', 'userKey', 'topMsg'));
    }

    /**
     * @author: promise tan
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function inputlist()
    {
        //获取需要面签者的客户列表
        $customers = $this->model('YoCustomerResult', false)
            ->with('customer')
            ->where(['status' => YoCustomerStatus::$CS_AUDIT_PREPARE])->get();

        $opCustomerType = 'examine';

        return view('Yoona.Front.S.lists', compact('customers', 'opCustomerType'));
    }

    /**
     * @author: promise tan
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function setexamine($userKey)
    {
        //数据更新 TODO
        if (Request::isMethod('POST')) {

            echo '数据更新';
            die();
        }

        //查询协审数据数据进行中转
        $info = tranfer('Audit')->tranferForm(
            $this->model('YoAuditAssist', false),
            ['user_key' => $userKey]
        )->getOutputs();

        return view('Yoona.Front.S.setexamine', compact('userKey', 'info'));
    }

    /**
     * @author: promise tan
     */
    public function orderlists()
    {
        //获取需要面签者的客户列表
        $customers = $this->model('YoCustomerResult', false)
            ->with('customer')
            ->get();

        $opCustomerType = 'all';

        return view('Yoona.Front.S.lists', compact('customers', 'opCustomerType'));
    }

}