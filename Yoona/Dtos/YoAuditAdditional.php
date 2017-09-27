<?php

namespace App\Yoona\Dtos;


/**
 * Class YoAuditAdditional
 * Author: promise tan
 * @package App\Yoona\Dtos
 */
class YoAuditAdditional extends YoBase
{
    //
    /**
     * author: promise tan
     * Date: ${DATE}
     * @var string
     */
    protected $table = 'yo_audit_additional';

    //面签类型
    public static $audit_type = array(
        '1' => '住址',
        '2' => '单位',
        '3' => '无法判断',
        '4' => '其他'
    );

    //银行流水月入金额
    public static $bank_income = array(
        '1' => '3000元以下',
        '2' => '3000至6000元',
        '3' => '6001至10000万',
        '4' => '10001至20000元',
        '5' => '20001元以上',
    );

    //客户如何知道本产品
    public static $know_about = array(
        '1' => '佰仟老客户',
        '2' => '老客户介绍',
        '3' => '其他人介绍',
        '4' => '广告',
        '5' => '网上看到',
        '6' => '其他',
    );

    //面签手机
    public static $audit_phone = array(
        '1' => 'iPhone系列',
        '2' => '三星系列',
        '3' => '华为系列',
        '4' => '小米系列',
        '5' => 'OPPO系列',
        '6' => 'vivo系列',
        '7' => '其他',
    );

    //信用卡额度
    public static $credit_quota = array(
        '1' => '5000以下',
        '2' => '5000至10000元',
        '3' => '10001至20000元',
        '4' => '20001至50000元',
        '5' => '5万元以上',
    );

    //住房状况
    public static $housing_status = array(
        '1' => '自有房',
        '2' => '家族房',
        '3' => '租住房',
        '4' => '宿舍',
    );

    //车辆价值
    public static $car_price = array(
        '1' => '5万以下',
        '2' => '6至10万',
        '3' => '11至30万',
        '4' => '30万以上',
    );

    //家庭人数
    public static $family_num = array(
        '1' => '1人',
        '2' => '2人',
        '3' => '3至4人',
        '4' => '4人以上',
    );

    /**
     * @author: promise tan
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(YoCustomerInfo::class, 'user_key', 'user_key');
    }
}
