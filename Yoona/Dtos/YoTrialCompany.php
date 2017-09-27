<?php

namespace App\Yoona\Dtos;

/**
 * Class YoTrialCompany
 * Author: promise tan
 * @package App\Yoona\Dtos
 */
class YoTrialCompany extends YoBase
{
    //
    /**
     * author: promise tan
     * Date: ${DATE}
     * @var string
     */
    protected $table = 'yo_trial_company';

    //行业类别代码
    public static $company_category = array(
        'A' => '农、林、牧、渔业',
        'B' => '采矿业',
        'C' => '制造业',
        'D' => '电力、热力、燃气及水生产和供应业',
        'E' => '建筑业',
        'F' => '批发和零售业',
        'G' => '交通运输、仓储和邮政业',
        'H' => '住宿和餐饮业',
        'I' => '信息传输、软件和信息技术服务业',
        'J' => '金融业',
        'K' => '房地产业',
        'L' => '租赁和商务服务业',
        'M' => '科学研究和技术服务业',
        'N' => '水利、环境和公共设施管理业',
        'O' => '居民服务、修理和其他服务业',
        'P' => '教育',
        'Q' => '卫生和社会工作',
        'R' => '文化、体育和娱乐业',
        'S' => '公共管理、社会保障和社会组织',
        'T' => '国际组织',
    );

    //单位性质
    public static $company_property = array(
        '10' => '机关',
        '20' => '科研设计单位',
        '21' => '高等教育单位',
        '22' => '中初教育单位',
        '23' => '医疗卫生单位',
        '29' => '其他事业单位',
        '31' => '国有企业',
        '32' => '三资企业',
        '39' => '其他企业',
        '40' => '部队',
        '55' => '农村建制村',
        '56' => '城镇社区',
        '99' => '其他',
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
