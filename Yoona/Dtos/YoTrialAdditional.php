<?php

namespace App\Yoona\Dtos;

/**
 * Class YoTrialAdditional
 * Author: promise tan
 * @package App\Yoona\Dtos
 */
class YoTrialAdditional extends YoBase
{
    //
    /**
     * author: promise tan
     * Date: ${DATE}
     * @var string
     */
    protected $table = 'yo_trial_additional_info';

    /**
     * @author: promise tan
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(YoCustomerInfo::class, 'user_key', 'user_key');
    }

}
