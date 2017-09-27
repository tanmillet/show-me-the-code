<?php

namespace App\Yoona\Dtos;

/**
 * Class YoTrialAuth
 * Author: promise tan
 * @package App\Yoona\Dtos
 */
class YoTrialAuth extends YoBase
{
    //
    /**
     * author: promise tan
     * Date: ${DATE}
     * @var string
     */
    protected $table  = 'yo_trial_auth';

    /**
     * @author: promise tan
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(YoCustomerInfo::class, 'user_key', 'user_key');
    }
}
