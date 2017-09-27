<?php

namespace App\Yoona\Traits\Com;

use App\Model\Admin\CustomerModel;
use App\Yoona\Dtos\YoAuditAdditional;
use App\Yoona\Dtos\YoCustomerInfo;
use App\Yoona\Dtos\YoCustomerResult;
use App\Yoona\Dtos\YoTrialAction;
use App\Yoona\Dtos\YoTrialAdditional;
use App\Yoona\Dtos\YoTrialAuth;
use App\Yoona\Dtos\YoTrialCompany;
use App\Yoona\Dtos\YoTrialFamily;
use App\Yoona\Dtos\YoTrialMobile;
use App\Yoona\Dtos\YoTrialPic;
use App\Yoona\Packages\Curl;

/**
 * Created by PhpStorm.
 * User: Promise tan
 * Date: 2017/1/3
 * Time: 16:29
 */
trait YoComTrait
{
    //进行第三方推送客户实名认证
    //借钱么实名规则：判断实名只要判断名字和身份证号即可，在实名前需要判断是否符合条件办理单
    /**
     * @author: promise tan
     * @return bool
     */
    public function isValidCustomer($realName = null, $idCard = null, $mobile = null)
    {
        //第三方平台推送客户信息时，若有推送姓名和身份证号，默认为已实名
        if (!is_null($realName) && !is_null($idCard)) {
            return true;
        }

        if (!is_null($mobile)) {
            //查询客户信息表，如有记录的，则表示已实名，防止第三方多次推送时信息不全
            $model = CustomerModel::where("mobile", $mobile)->first();
            if (!is_null($model) && !is_null($model->real_name) && !is_null($model->cert_no)) {
                return true;
            }
        }

        return false;
    }

    //进行身份的核实 -- 是否存量客户
    //借钱么有现成的接口，先借用
    /**
     * @author: promise tan
     * @return bool
     */
    public function checkIdentity($mobile, $idCard)
    {
        $url = yoconf('env')['ALL_STREAM'];

        $params = [
            'userName' => 'bqfqg',
            'password' => 'nh217v6EYUpgOgfLZDQ93g==',
            'clientInfo' => "$mobile|$idCard",
            'serviceName' => 'checkUserExist'
        ];

        $header = [
            'Content-Type: application/x-www-form-urlencoded',
        ];

        $response = yohttp()->request('', $url, $params, $header);

        yolog()->info("$mobile-{$idCard}调取存量客户接口,返回结果为：" . $response, "core");

        $res = json_decode($response);
        if (isset($res->clientExist[0]) && ($res->clientExist[0] == 1) && isset($res->clientNames[0])) {
            return true;
        }

        return false;//POST
    }

    //判断客户是否属于销售
    /**
     * @author: promise tan
     * @param $userKey
     * @param $workNo
     * @return bool
     */
    public function authOpCustomer($userKey, $workNo)
    {
        $selRst = YoCustomerResult::where('user_key', '=', $userKey)->where('work_no', '=', $workNo)->first();

        return is_null($selRst) ? false : true;
    }

    /**
     * @param $where
     * @param $upData
     * @return mixed
     */
    public function upCompanyToDB($where, $upData)
    {
        return YoTrialCompany::updateOrCreate($where, $upData);
    }

    /**
     * @param $where
     * @param $upData
     * @return mixed
     */
    public function upCustomerToDB($where, $upData)
    {
        return YoCustomerInfo::updateOrCreate($where, $upData);
    }

    /**
     * @author: promise tan
     * @param $where
     * @param $upData
     * @return static
     */
    public function upTrialFamilyToDB($where, $upData)
    {
        return YoTrialFamily::updateOrCreate($where, $upData);
    }

    /**
     * @author: promise tan
     * @param $where
     * @param $upData
     * @return static
     */
    public function upTrialMobileToDB($where, $upData)
    {
        return YoTrialMobile::updateOrCreate($where, $upData);
    }

    /**
     * @author: promise tan
     * @param $where
     * @param $upData
     * @return static
     */
    public function upTrialActionToDB($where, $upData)
    {
        return YoTrialAction::updateOrCreate($where, $upData);
    }

    /**
     * @author: promise tan
     * @param $where
     * @param $upData
     * @return static
     */
    public function upTrialAdditionalToDB($where, $upData)
    {
        return YoTrialAdditional::updateOrCreate($where, $upData);
    }

    /**
     * @author: promise tan
     * @param $where
     * @param $upData
     * @return static
     */
    public function upTrialAuthToDB($where, $upData)
    {
        return YoTrialAuth::updateOrCreate($where, $upData);
    }

    /**
     * @author: promise tan
     * @param $where
     * @param $upData
     * @return static
     */
    public function upTrialPicToDB($where, $upData)
    {
        return YoTrialPic::updateOrCreate($where, $upData);
    }

    /**
     * @author: promise tan
     * @param $where
     * @param $upData
     * @return static
     */
    public function upCustomerResultToDB($where, $upData)
    {
        return YoCustomerResult::updateOrCreate($where, $upData);
    }

    /**
     * @author: promise tan
     * @param $where
     * @param $upData
     * @return static
     */
    public function upAuditAdditionalToDB($where, $upData)
    {
        return YoAuditAdditional::updateOrCreate($where, $upData);
    }

    /**
     * 根据代码返回文本信息
     * @param $modelName       Model类名
     * @param $parameterName   静态变量名
     * @param $code            代码
     * @return mixed
     */
    public function text($modelName, $parameterName, $code)
    {
        $lut = yoconf('constant')['ModelMap'];
        $className = collect($lut)->get($modelName, '');
        if (empty($className)) {
            throw new ModelNotFoundException;
        }

        $ref = new \ReflectionClass($className);
        if (!$ref->getProperty($parameterName)->isStatic()) {
            throw new ModelNotFoundException;
        }

        $arr = $className::$$parameterName;
        return is_array($arr) ? array_get($arr, $code, null) : null;
    }

    /**
     * 根据文本返回代码
     * @param $modelName         Model类名
     * @param $parameterName     静态变量名
     * @param $text              文本信息
     * @return mixed|null
     */
    public function code($modelName, $parameterName, $text)
    {
        $lut = yoconf('constant')['ModelMap'];
        $className = collect($lut)->get($modelName, '');
        if (empty($className)) {
            throw new ModelNotFoundException;
        }

        $ref = new \ReflectionClass($className);
        if (!$ref->getProperty($parameterName)->isStatic()) {
            throw new ModelNotFoundException;
        }

        $arr = $className::$$parameterName;
        return in_array($arr, $text) ? array_search($arr, $text) : null;
    }
}