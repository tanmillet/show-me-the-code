<?php

namespace App\Yoona\Dtos;

/**
 * Class YoAuditAssist
 * Author: promise tan
 * @package App\Yoona\Dtos
 */
class YoAuditAssist extends YoBase
{
    //
    /**
     * author: promise tan
     * Date: ${DATE}
     * @var string
     */
    protected $table = 'yo_audit_assist';

    /**
     * @author: promise tan
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(YoCustomerInfo::class, 'user_key', 'user_key');
    }
}
