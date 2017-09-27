<?php

namespace App\Yoona\Dtos;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * Class YoCustomerStatus
 * Author: promise tan
 * @package App\Yoona\Dtos
 */
class YoCustomerStatus extends Model
{
    use SoftDeletes;
    /*
     * 99-撤销；100-新客源；150-待预审；170-预审同步API失败；
     * 200-预审中（API已同步）300-预审通过；350-预审不通过；
    * 360-客户拒绝面签；400-待面签；450-面签退回；500-面签中；
    * 550-面签不通过；600-面签通过；700-可贷款
    */
    /**
     * author: promise tan
     * Date: ${DATE}
     * @var int
     */
    static public $CS_OP_CANCEL = 99;
    /**
     * author: promise tan
     * Date: ${DATE}
     * @var int
     */
    static public $CS_YO_NEW = 100;
    /**
     * author: promise tan
     * Date: ${DATE}
     * @var int
     */
    static public $CS_TRIAL_PREPARE = 150;
    /**
     * author: promise tan
     * Date: ${DATE}
     * @var int
     */
    static public $CS_TRIAL_ERROR = 170;
    /**
     * author: promise tan
     * Date: ${DATE}
     * @var int
     */
    static public $CS_TRIAL_STARTING = 200;
    /**
     * author: promise tan
     * Date: ${DATE}
     * @var int
     */
    static public $CS_TRIAL_SUCCESS = 300;
    /**
     * author: promise tan
     * Date: ${DATE}
     * @var int
     */
    static public $CS_TRIAL_FAILURE = 350;
    /**
     * author: promise tan
     * Date: ${DATE}
     * @var int
     */
    static public $CS_AUDIT_REFUSE = 360;
    /**
     * author: promise tan
     * Date: ${DATE}
     * @var int
     */
    static public $CS_AUDIT_PREPARE = 400;
    /**
     * author: promise tan
     * Date: ${DATE}
     * @var int
     */
    static public $CS_AUDIT_BACK = 450;
    /**
     * author: promise tan
     * Date: ${DATE}
     * @var int
     */
    static public $CS_AUDIT_STARTING = 500;
    /**
     * author: promise tan
     * Date: ${DATE}
     * @var int
     */
    static public $CS_AUDIT_FAILURE = 550;
    /**
     * author: promise tan
     * Date: ${DATE}
     * @var int
     */
    static public $CS_AUDIT_SUCCESS = 600;
    /**
     * author: promise tan
     * Date: ${DATE}
     * @var int
     */
    static public $CS_YO_END = 700;
    /**
     * author: promise tan
     * Date: ${DATE}
     * @var string
     */
    protected $table = 'yo_customer_status';
    protected $dates = ['deleted_at'];
    protected $guarded = [
        'id',
        'updated_at',
        'created_at'
    ];
}
