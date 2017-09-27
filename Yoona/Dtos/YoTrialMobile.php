<?php

namespace App\Yoona\Dtos;

/**
 * Class YoTrialMobile
 * Author: promise tan
 * @package App\Yoona\Dtos
 */
class YoTrialMobile extends YoBase
{
    //
    /**
     * author: promise tan
     * Date: ${DATE}
     * @var string
     */
    protected $table = 'yo_trial_mobile';

    /**
     * @author: promise tan
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(YoCustomerInfo::class , 'user_key' , 'user_key');
    }
}
