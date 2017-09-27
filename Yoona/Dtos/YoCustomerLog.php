<?php

namespace App\Yoona\Dtos;

/**
 * Class YoCustomerLog
 * Author: promise tan
 * @package App\Yoona\Dtos
 */
class YoCustomerLog extends YoBase
{
    //
    /**
     * author: promise tan
     * Date: ${DATE}
     * @var string
     */
    protected $table  = 'yo_cumstom_log';

    /**
     * @author: promise tan
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(YoCustomerInfo::class, 'user_key', 'user_key');
    }
}
