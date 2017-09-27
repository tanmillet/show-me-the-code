<?php

namespace App\Yoona\Dtos;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * Class YoCustomerInfo
 * Author: promise tan
 * @package App\Yoona\Dtos
 */
class YoCustomerInfo extends YoBase
{
    use SoftDeletes;
    /**
     * author: promise tan
     * Date: ${DATE}
     * @var string
     */
    protected $table = 'yo_customer_info';
    protected $dates = ['deleted_at'];

    //教育水平
    public static $education_level = array(
        '0' => '未知',
        '1' => '小学',
        '2' => '初中',
        '3' => '高中',
        '4' => '中专',
        '5' => '大专',
        '6' => '本科',
        '7' => '研究生及以上',
    );

    /**
     * @author: promise tan
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function trialCompany()
    {
        return $this->hasOne(YoTrialCompany::class, 'user_key', 'user_key');
    }

    /**
     * @author: promise tan
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function trialFamily()
    {
        return $this->hasOne(YoTrialFamily::class, 'user_key', 'user_key');
    }

    /**
     * @author: promise tan
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function trialAdditional()
    {
        return $this->hasOne(YoTrialAdditional::class, 'user_key', 'user_key');
    }

    /**
     * @author: promise tan
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function auditAdditional()
    {
        return $this->hasOne(YoAuditAdditional::class, 'user_key', 'user_key');
    }

    /**
     * @author: promise tan
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function auditAssist()
    {
        return $this->hasOne(YoAuditAssist::class, 'user_key', 'user_key');
    }

    /**
     * @author: promise tan
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function trialMobile()
    {
        return $this->hasOne(YoTrialMobile::class, 'user_key', 'user_key');
    }


    /**
     * @author: promise tan
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function trialAuth()
    {
        return $this->hasOne(YoTrialAuth::class, 'user_key', 'user_key');
    }

    /**
     * @author: promise tan
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function trialAction()
    {
        return $this->hasOne(YoTrialAction::class, 'user_key', 'user_key');
    }

    /**
     * @author: promise tan
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customerLogs()
    {
        return $this->hasMany(YoCustomerLog::class, 'user_key', 'user_key');
    }

    /**
     * @author: promise tan
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trialPics()
    {
        return $this->hasMany(YoTrialPic::class, 'user_key', 'user_key');
    }

    /**
     * @author: promise tan
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function auditPics()
    {
        return $this->hasMany(YoAuditPic::class, 'user_key', 'user_key');
    }

    /**
     * @author: promise tan
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contracts()
    {
        return $this->hasMany(YoContract::class, 'user_key', 'user_key');
    }

    /**
     * @author: promise tan
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rstinfo()
    {
        return $this->hasOne(YoCustomerResult::class, 'cumstom_result_UserKey', 'user_key');
    }
}