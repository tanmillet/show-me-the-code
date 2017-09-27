<?php

namespace App\Yoona\Dtos;

/**
 * Class YoTrialFamily
 * Author: promise tan
 * @package App\Yoona\Dtos
 */
class YoTrialFamily extends YoBase
{
    //
    /**
     * author: promise tan
     * Date: ${DATE}
     * @var string
     */
    protected $table = 'yo_trial_family';

    //婚姻状况
    public static $is_married = array(
        '0' => '未婚',
        '1' => '已婚',
        '2' => '离异',
        '3' => '丧偶',
    );

    //直系亲属关系
    public static $immediate_relationship = array(
        '1' => '父亲',
        '2' => '母亲',
        '3' => '儿子',
        '4' => '女儿',
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
