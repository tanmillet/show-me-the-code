<?php

namespace App\Yoona\Http\OpenApi;

use App\Yoona\Dtos\YoCustomerStatus;
use App\Yoona\Jobs\InvokeCoreAuditAuth;
use App\Yoona\Packages\Osser;
use App\Yoona\Traits\Com\YoComTrait;
use App\Yoona\Http\ApiContr;
use App\Yoona\Traits\YoBasicTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

// 1   第三方平台获客接口
/**
 *
 * @功能 对一些业务需要接口
 *
 * @service 移动web app ios等
 *
 * Class ApiAdminContr
 * Author: promise tan
 * @package App\Yoona\Http\Admin\Contrs
 */
class OpenApiContr extends ApiContr
{
    use YoBasicTrait, YoComTrait;

    /**
     *
     * @title 接口1    创建客户信息接口——预审阶段
     *
     * @function 从第三方平台推送的信息保存到表里
     *
     * @author: promise tan
     * @return array
     */
    public function storeCustomer($orgin = '', $intputs)
    {
        set_time_limit(1800);

        // if (!Request::isMethod('post')) {
        //     return false;
        // }
        //$orgin 来源核实

        //接收数据
        // $inputs = Request::all();
        $inputs = $intputs;

        //进行第三方推送客户实名认证
        // ($orgin) ? (!$this->isValidCustomer()) ? yolog()->info('实名认证通过', $orgin) : '' : ''; //TODO

        //判断是否存量客户
        if ($this->checkIdentity()) {
            return false;
        }

        //获取解析数据
        $outputs = tranfer($orgin)->invoke($inputs)->tranferAll(true)->getOutputs();


        //过滤类
        // $filterParamObj = new UpCompanyInfoRequest();
        // $filterParamObj->setValidateParams($outputs['yo_trial_company']);
        //
        // $errorMsgs = $filterParamObj->validRes();
        // if (!empty($errorMsgs)) {
        //     return ['status' => 'failed', 'info' => ['status_code' => 401, 'message' => $errorMsgs]];
        // }

        // .......
        //判断客户是否存在
        $cumstomer = $this->model('YoCustomerInfo')->where(['mobile' => $outputs['yo_customer_info']['mobile']])->first();
        //DB 更新
        $where = ['user_key' => (!empty($cumstomer) && isset($cumstomer->mobile)) ? $cumstomer->user_key : yoid(13, 'Y')];

        // var_dump($outputs);die();
        // DB::beginTransaction();
        try {
            // 更新其它的 TODO
            $this->upCustomerToDB($where, $outputs['yo_customer_info']);
            $this->upTrialFamilyToDB($where, $outputs['yo_trial_family']);
            $this->upTrialMobileToDB($where, $outputs['yo_trial_mobile']);
            $this->upTrialActionToDB($where, $outputs['yo_trial_action']);
            $this->upTrialAdditionalToDB($where, $outputs['yo_trial_additional_info']);
            $this->upTrialAuthToDB($where, $outputs['yo_trial_auth']);
            $this->upTrialPicToDB($where, $outputs['yo_trial_pic']);
            $this->upCompanyToDB($where, $outputs['yo_trial_company']);

            // if () {
            //     DB::commit();
            //
            //     //添加推送消息给核心系统
            //     $this->dispatch(new ConveyCoreTrialAut($where['user_key']));
            //     return true;
            // }
        } catch (\Exception $e) {

            // DB::rollback();
            // return false;
            var_dump($e->getMessage());
        }
    }

    /**
     * @author: promise tan
     * @param $userKey
     * @return \Illuminate\Http\JsonResponse
     */
    public function rollbackCustomer($userKey)
    {

        if (!Request::isMethod('POST') || !Request::ajax()) {
            return $this->responseError(' 客户回退失败，合法操作！');
        }

        if (!$this->authOpCustomer($userKey, workNo())) {
            return $this->responseError(' 客户回退失败，合法操作！');
        }

        $remark = Request::get('remark', '');

        $upData = [
            'remark' => $remark,
            'status' => YoCustomerStatus::$CS_AUDIT_BACK,
        ];
        $where = [
            'user_key' => $userKey,
            'work_no' => workNo()
        ];

        $selRst = $this->model('YoCustomerResult')->where($where)->first();

        if (empty($selRst)) {
            return $this->responseError(' 客户回退失败，无权进行操作改客户/客户不存在！');
        }

        $dbRst = $this->upCustomerResultToDB($where, $upData);

        $rsp = ($dbRst)
            ? $this->setStatusCode(200)->responseSuccess(' 客户回退成功！')
            : $this->responseError(' 客户回退失败！');

        return $rsp;
    }

    /**
     *
     * @author: promise tan
     * @param $userKey
     */
    public function submitAudit($userKey)
    {
        $this->dispatch(new InvokeCoreAuditAuth($userKey));
    }

    /**
     *
     * @funtion store pic to oss serve
     *
     * @author: promise tan
     * @param $userKey
     */
    public function storePic($userKey)
    {
        yolog()->info(interviewer()->work_no . ' - ' . interviewer()->username . '-进行了上传临时图片-面签补录资料！'
            , 'upload-images'
        );

        $imageFile = Request::file('filepic');
        $work_no = interviewer()->work_no;

        $backInfo = ['status' => false];

        if ($imageFile && $imageFile->isValid()) {
            //上传文件的后缀
            $imageExtension = $imageFile->getClientOriginalExtension();
            //临时文件的绝对路径
            $imageTempPath = $imageFile->getRealPath();
            //文件保存路径
            $imagePath = '/imgsys/yoona/' . date("Y-m-d", time()) . '/';

            if (!is_dir($imagePath)) {
                @mkdir($imagePath, 0777);
            } else {
                yolog()->info(interviewer()->work_no . ' - ' . interviewer()->username . '-进行了上传临时图片创建文件夹 { ' . $imagePath . ' } 失败！'
                    , 'upload-images'
                );

                echo '';
                exit;
            }

            //文件保存的名字
            $imageSaveName = time() . "_" . $work_no . '_' . $userKey . '.' . $imageExtension;

            $imageFileContent = file_get_contents($imageTempPath);

            //创建Osser对接
            $oss = Osser::getInstance();

            $upRes = $oss->uploadFileByContent($imageFileContent, [
                'folder' => $imagePath,
                'fileName' => $imageSaveName
            ]);

            $backInfo = ($upRes['success'])
                ? [
                    'status' => true,
                    'data' => [
                        'httpurl' => $upRes['visit_path'],
                        'image' => $upRes['data'],
                        'type' => Request::input('type')
                    ]
                ]
                : ['status' => false];
        }

        $str = '<script>parent.callback(' . json_encode($backInfo) . ');</script>';

        echo $str;
        exit;
    }
}