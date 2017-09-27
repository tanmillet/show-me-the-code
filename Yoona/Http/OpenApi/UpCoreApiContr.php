<?php

namespace App\Yoona\Http\OpenApi;

use App\Yoona\Dtos\YoCustomerStatus;
use App\Yoona\Http\ApiContr;
use App\Yoona\Jobs\AssignCustomerToSa;

// 1	核身接口		借钱么	核心	名字、身份证号码	是否已存在有效合同的存量客户
// 2	预审接口		借钱么	核心	点击查看	0表示预审通过、1表示预审通过需要面前，2表示预审拒绝，-1表示系统错误
// 3	提单接口(申请额度)		借钱么	核心	总表信息	success
// 4	额度查询接口		借钱么	核心	客户编号	客户最高额度、客户剩余额度、调整幅度比、可调整次数	客户进行提现或者申请调额时调用
// 5	提单接口(提额申请)		借钱么	核心			另外给字段
// 6	查询接口(审批状态)		借钱么	核心	流水号	是否申请通过，审批后的最高额度、可用额度	适用于额度申请以及提额申请
// 7	还款计划接口		借钱么	核心	流水号	还款计划信息信息(数组)
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
class UpCoreApiContr extends ApiContr
{
    /**
     *
     * @title 接口2
     *
     * @function 0表示预审通过、1表示预审通过需要面前，2表示预审拒绝，-1表示系统错误
     *
     * @author: promise tan
     * @return int
     */
    public function trialAuth($userKey)
    {

        //
        $rst = 1;

        try {

            //进行客户默认分配
            if ((int)$rst === 1) {
                $this->dispatch(new AssignCustomerToSa($userKey));
                yolog('预审接口 :客户默认分配任务调用成功！', 'core')->info();
            }

            //更新客户状态状态
            $customerStatus = ((int)$rst >= 1)
                ? ((int)$rst === 1) ? YoCustomerStatus::$CS_AUDIT_PREPARE : YoCustomerStatus::$CS_TRIAL_FAILURE
                : ((int)$rst === 0) ? YoCustomerStatus::$CS_TRIAL_SUCCESS : YoCustomerStatus::$CS_TRIAL_ERROR;

            $dbRst = $this->upCustomerResultToDB(['user_key' => $userKey], ['status' => $customerStatus]);
            ($dbRst) ? yolog('预审接口 : 数据库更新客户状态成功！', 'core')->info() : yolog()->error('预审接口 : 数据库更新客户为 { ' . $customerStatus . ' } 状态失败！', 'core');

        } catch (\Exception $e) {
            yolog()->error('预审接口 : ' . $e->getMessage(), 'core');
        }

        return $rst;
    }

    /**
     *
     * @title 接口3
     *
     * @function
     *
     * @author: promise tan
     * @return int
     */
    public function aduitAuth()
    {

        $applyMoney = 5000;



        return 0;
    }

    /**
     *
     * @title 接口4
     *
     * @function 客户编号    客户最高额度、客户剩余额度、调整幅度比、可调整次数    客户进行提现或者申请调额时调用
     *
     * @author: promise tan
     * @return array
     */
    public function limitMoney()
    {

        return [];
    }

    /**
     *
     * @title 接口5 提单接口(提额申请)
     *
     * @function
     *
     * @author: promise tan
     * @return array
     */
    public function promoteMoney()
    {

        return [];
    }

    /**
     *
     * @title 接口6 查询接口(审批状态)
     *
     * @function 流水号    是否申请通过，审批后的最高额度、可用额度    适用于额度申请以及提额申请
     *
     * @author: promise tan
     * @return array
     */
    public function selOrderInfo()
    {

        return [];
    }

    /**
     *
     * @title 接口7    还款计划接口        借钱么    核心    流水号
     *
     * @function 还款计划信息信息(数组)
     *
     * @author: promise tan
     * @return array
     */
    public function selSchedule()
    {

        return [];
    }

    /**
     *
     * @title 接口8    默认分配客户接口
     *
     * @author: promise tan
     * @param $userKey
     * @return array
     */
    public function assignCtoS($userKey)
    {

        return [];
    }
}