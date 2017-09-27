<?php

namespace App\Yoona\Dtos;

/**
 * Class YoAuditPic
 * Author: promise tan
 * @package App\Yoona\Dtos
 */
class YoAuditPic extends YoBase
{
    //
    /**
     * author: promise tan
     * Date: ${DATE}
     * @var string
     */
    protected $table = 'yo_audit_pic';

    /**
     * @author: promise tan
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(YoCustomerInfo::class, 'user_key', 'user_key');
    }
}
