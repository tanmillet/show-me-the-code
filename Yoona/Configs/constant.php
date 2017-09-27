<?php
/**
 * Created by PhpStorm.
 * User: Promise tan
 * Date: 2017/1/3
 * Time: 16:54
 */
return [
    //面签补录资料进行阶段显示成功
    'AuditStep' => [
        [
            'sign' => 'interview',
            'title' => '面签客户资料补录完成',
            'op' => [
                'url' => '/yofront/examine/setexamine/',
                'title' => '进行面签协审',
            ],
        ],
        [
            'sign' => 'examine',
            'title' => '面签资料审核中...',
            'op' => [
                'url' => '/yofront/interviewer/',
                'title' => '完成',
            ],
        ],
        [
            'sign' => 'default',
            'title' => '',
            'op' => [
                'url' => '',
                'title' => '',
            ],
        ]
    ],
    //面签确认资料页面基本信息
    'AuditSrc' => [
        [
            'key' => 1,
            'sign' => 'company',
            'model' => 'YoTrialCompany',
            'viewName' => 'companyinfo',
            'title' => '单位信息',
            'upmethod' => 'upCompanyToDB',
            'validator' => \App\Yoona\Requests\UpCompanyValidator::class,
        ],
        [
            'key' => 2,
            'sign' => 'family',
            'model' => 'YoTrialFamily',
            'viewName' => 'familyinfo',
            'title' => '家庭信息',
            'upmethod' => 'upTrialFamilyToDB',
            'validator' => \App\Yoona\Requests\UpFamilyValidator::class,
        ],
        [
            'key' => 3,
            'sign' => 'other',
            'model' => 'YoTrialAdditional',
            'viewName' => 'otherinfo',
            'title' => '其它信息',
            'upmethod' => 'upTrialAdditionalToDB',
            'validator' => \App\Yoona\Requests\UpTrialAdditionalValidator::class,
        ],
        [
            'key' => 12,
            'sign' => 'makeup',
            'model' => 'YoAuditAdditional',
            'viewName' => 'makeup',
            'title' => '面签补录资料',
            'upmethod' => 'upAuditAdditionalToDB',
            'validator' => \App\Yoona\Requests\UpAuditAdditionalValidator::class,
        ]
    ],
    'ModelMap' =>
        [
            'YoAuditAdditional' => \App\Yoona\Dtos\YoAuditAdditional::class,
            'YoAuditAssist' => \App\Yoona\Dtos\YoAuditAssist::class,
            'YoAuditPic' => \App\Yoona\Dtos\YoAuditPic::class,
            'YoCustomerInfo' => \App\Yoona\Dtos\YoCustomerInfo::class,
            'YoCustomerLog' => \App\Yoona\Dtos\YoCustomerLog::class,
            'YoCustomerResult' => \App\Yoona\Dtos\YoCustomerResult::class,
            'YoCustomerStatus' => \App\Yoona\Dtos\YoCustomerStatus::class,
            'YoCustomerPicType' => \App\Yoona\Dtos\YoCustomerPicType::class,
            'YoContract' => \App\Yoona\Dtos\YoContract::class,
            'YoNotice' => \App\Yoona\Dtos\YoNotice::class,
            'YoTrialCompany' => \App\Yoona\Dtos\YoTrialCompany::class,
            'YoTrialFamily' => \App\Yoona\Dtos\YoTrialFamily::class,
            'YoTrialAdditional' => \App\Yoona\Dtos\YoTrialAdditional::class,
            'YoTrialAction' => \App\Yoona\Dtos\YoTrialAction::class,
            'YoTrialMobile' => \App\Yoona\Dtos\YoTrialMobile::class,
            'YoTrialAuth' => \App\Yoona\Dtos\YoTrialAuth::class,
            'YoTrialPic' => \App\Yoona\Dtos\YoTrialPic::class,
            'YoPicType' => \App\Yoona\Dtos\YoPicType::class,
        ],
];