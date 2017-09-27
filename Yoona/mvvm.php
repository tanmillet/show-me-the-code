<?php
/**
 * Created by PhpStorm.
 * User: Promise tan
 * Date: 2016/12/29
 * Time: 18:07
 */
if (!function_exists('isUserPermissionRenderSide')) {
    /**
     * @author: promise tan
     * @param 判断用户是否有权限进行渲染侧边栏
     * @param string $logFileName
     */
    function userPermissionRenderSide($currentUrl = '')
    {
        $menu_arr = \Illuminate\Support\Facades\Session::get('yo_permission');

        $html = '';
        //print_r($menu_arr);
        if ($menu_arr) {
            foreach ($menu_arr['parent'] as $key => $val) {
                if (isset($menu_arr['menu'][$key])) {
                    $cite_href = 'javascript:;';
                    $more_html = '<span class="layui-nav-more"></span>';
                } else {
                    $cite_href = $val['url'];
                    $more_html = '';
                }
                $html .= '<li class="layui-nav-item">
                <a href="' . $cite_href . '">                    
                    <cite>' . $val['name'] . '</cite>' . $more_html . '
                </a>';
                if (isset($menu_arr['menu'][$key])) {
                    $html .= '<dl class="layui-nav-child">';
                    foreach ($menu_arr['menu'][$key] as $val) {
                        $html .= '<dd><a href="' . $val[1] . '"><cite>' . $val[0] . '</cite></a></dd>';
                    }
                    $html .= '</dl>';
                }
                $html .= '</li>';
            }
        }
        return $html;
        //根据权限进行查询
        // return ' <li class="layui-nav-item layui-nav-itemed">
        //         <a href="javascript:;">
        //             <i class="fa fa-cubes" aria-hidden="true" data-icon="fa-cubes"></i>
        //             <cite>产品配置管理</cite><span class="layui-nav-more"></span>
        //         </a>
        //         <dl class="layui-nav-child">
        //             <dd class="layui-this"><a href="/yo/product"><cite>产品配置管理列表</cite></a></dd>
        //             <dd><a href="/yo/productadd"><cite>新增</cite></a></dd>
        //         </dl>
        //     </li>
        //     <li class="layui-nav-item">
        //         <a href="javascript:;">
        //             <i class="fa fa-cogs"></i>
        //             <cite>城市配置</cite><span class="layui-nav-more"></span>
        //         </a>
        //         <dl class="layui-nav-child">
        //             <dd><a href="/yo/city"><cite>城市配置列表</cite></a></dd>
        //             <dd><a href="/yo/cityadd"><cite>新增</cite></a></dd>
        //         </dl>
        //     </li>
        //     <li class="layui-nav-item">
        //         <a href="javascript:;">
        //             <i class="fa fa-cogs"></i>
        //             <cite>面签销售分区配置</cite><span class="layui-nav-more"></span>
        //         </a>
        //         <dl class="layui-nav-child">
        //             <dd><a href="/yo/salearea"><cite>面签销售分区配置（列表）</cite></a></dd>
        //             <dd><a href="/yo/saleareaadd"><cite>新增</cite></a></dd>
        //         </dl>
        //     </li>
        //     <li class="layui-nav-item">
        //         <a href="/yo/facesign">
        //             <i class="fa fa-stop-circle"></i><cite>面签订单管理</cite>
        //         </a>
        //     </li>
        //     <li class="layui-nav-item">
        //         <a href="/yo/custlist">
        //             <i class="fa fa-stop-circle"></i><cite>客户查询</cite>
        //         </a>
        //     </li>
        //     <li class="layui-nav-item">
        //         <a href="javascript:;">
        //             <i class="fa fa-cogs"></i>
        //             <cite>统计分析</cite><span class="layui-nav-more"></span>
        //         </a>
        //         <dl class="layui-nav-child">
        //             <dd><a href="/yo/transformationratio"><cite>转化率统计</cite></a></dd>
        //         </dl>
        //     </li>
        //     <li class="layui-nav-item">
        //         <a href="/bqjieqianadmin/admin/index">
        //             <i class="fa fa-stop-circle"></i><cite>返回权限管理后台</cite>
        //         </a>
        //     </li>';
    }

}

//客户来源
if (!function_exists('customType')) {

    function customType()
    {
        return ['1' => '融360', '2' => 'KN'];
    }
}

//信息来源
if (!function_exists('informationType')) {
    function informationType()
    {
        return ['1' => '客户', '2' => '第三方', '3' => '销售'];
    }
}


//来源渠道
if (!function_exists('channelType')) {
    function channelType()
    {
        return ['1' => 'APP', '2' => '微信'];
    }
}

//客户状态
if (!function_exists('customStatus')) {
    function customStatus()
    {
        return ['99' => '撤销', '100' => '新客源', '150' => '待预审', '170' => '预审同步API失败', '200' => '预审中（API已同步）', '300' => '预审通过', '350' => '预审不通过', '360' => '客户拒绝面签', '400' => '待面签', '450' => '面签退回', '500' => '面签中', '550' => '面签不通过', '600' => '面签通过', '700' => '可贷款'];
    }
}

//是否选择
if (!function_exists('renderIsOrNot')) {

    function renderIsOrNot($isOrNot = '', $name = 'isOrNot', $class = '')
    {
        $arr = array('1' => '是', '0' => '否', '2' => '不确定');
        $htmlStr = '<select name="' . $name . '" class="' . $class . '">  <option value="">请选择</option>';
        foreach ($arr as $key => $item) {
            $selected = ($isOrNot == $key) ? 'selected ' : '';
            $htmlStr .= '<option value="' . $key . '" ' . $selected . '>' . $item . '</option>';
        }
        $htmlStr .= '</select>';

        return $htmlStr;
    }
}

//省份列表
if (!function_exists('renderProvince')) {

    function renderProvinceCode($province_code = '', $class = '')
    {
        $province_lists = \App\Yoona\Dtos\SyncCodeLibrary::getProvinceLists();
        $htmlStr = '<select name="province_code" class="' . $class . '">  <option value="">请选择</option>';
        foreach ($province_lists as $provinceCode) {
            $selected = ($province_code == $provinceCode['code']) ? 'selected ' : '';
            $htmlStr .= '<option value="' . $provinceCode['code'] . '" ' . $selected . '>' . $provinceCode['name'] . '</option>';
        }
        $htmlStr .= '</select>';

        return $htmlStr;
    }
}

//城市列表
if (!function_exists('renderCity')) {

    function renderCity($province_code, $city_code = '', $class = '')
    {
        $city_lists = \App\Yoona\Dtos\SyncCodeLibrary::getCityLists($province_code);
        $htmlStr = '<select name="city_code" class="' . $class . '">  <option value="">请选择</option>';
        foreach ($city_lists as $cityCode) {
            $selected = ($city_code == $cityCode['code']) ? 'selected ' : '';
            $htmlStr .= '<option value="' . $cityCode['code'] . '" ' . $selected . '>' . $cityCode['name'] . '</option>';
        }
        $htmlStr .= '</select>';

        return $htmlStr;
    }
}

//县区列表
if (!function_exists('renderDistrict')) {

    function renderDistrict($city_code, $district_code = '', $class = '')
    {
        $district_lists = \App\Yoona\Dtos\SyncCodeLibrary::getDistrictLists($city_code);
        $htmlStr = '<select name="district_code" class="' . $class . '">  <option value="">请选择</option>';
        foreach ($district_lists as $districtCode) {
            $selected = ($district_code == $districtCode['code']) ? 'selected ' : '';
            $htmlStr .= '<option value="' . $districtCode['code'] . '" ' . $selected . '>' . $districtCode['name'] . '</option>';
        }
        $htmlStr .= '</select>';

        return $htmlStr;
    }
}

//单位行业类别
if (!function_exists('renderCompanyCategory')) {

    function renderCompanyCategory($company_category = '', $class = '')
    {
        $companyCategories = \App\Yoona\Dtos\YoTrialCompany::$company_category;
        $htmlStr = '<select name="company_category" class="' . $class . '">  <option value="">请选择</option>';
        foreach ($companyCategories as $key => $companyCategory) {
            $selected = ($company_category == $key) ? 'selected ' : '';
            $htmlStr .= '<option value="' . $key . '" ' . $selected . '>' . $companyCategory . '</option>';
        }
        $htmlStr .= '</select>';

        return $htmlStr;
    }
}

//单位性质
if (!function_exists('renderCompanyProperties')) {

    function renderCompanyProperties($company_properties = '', $class = '')
    {
        $companyProperties = \App\Yoona\Dtos\YoTrialCompany::$company_property;
        $htmlStr = '<select name="company_properties" class="' . $class . '">  <option value="">请选择</option>';
        foreach ($companyProperties as $key => $companyProperty) {
            $selected = ($company_properties == $key) ? 'selected ' : '';
            $htmlStr .= '<option value="' . $key . '" ' . $selected . '>' . $companyProperty . '</option>';
        }
        $htmlStr .= '</select>';

        return $htmlStr;
    }
}

//婚姻状况
if (!function_exists('renderIsMarried')) {

    function renderIsMarried($is_married = '', $class = '')
    {
        $isMarriedLists = \App\Yoona\Dtos\YoTrialFamily::$is_married;
        $htmlStr = '<select name="is_married" class="' . $class . '">  <option value="">请选择</option>';
        foreach ($isMarriedLists as $key => $isMarried) {
            $selected = ($is_married == $key) ? 'selected ' : '';
            $htmlStr .= '<option value="' . $key . '" ' . $selected . '>' . $isMarried . '</option>';
        }
        $htmlStr .= '</select>';

        return $htmlStr;
    }
}

//直系亲属关系
if (!function_exists('renderImmediateRelationship')) {

    function renderImmediateRelationship($immediate_relationship = '', $class = '')
    {
        $immediateRelationships = \App\Yoona\Dtos\YoTrialFamily::$immediate_relationship;
        $htmlStr = '<select name="immediate_relationship" class="' . $class . '">  <option value="">请选择</option>';
        foreach ($immediateRelationships as $key => $immediateRelationship) {
            $selected = ($immediate_relationship == $key) ? 'selected ' : '';
            $htmlStr .= '<option value="' . $key . '" ' . $selected . '>' . $immediateRelationship . '</option>';
        }
        $htmlStr .= '</select>';

        return $htmlStr;
    }
}

//教育程度
if (!function_exists('renderEducationLevel')) {

    function renderEducationLevel($education_level = '', $class = '')
    {
        $educationLevels = \App\Yoona\Dtos\YoCustomerInfo::$education_level;
        $htmlStr = '<select name="education_level" class="' . $class . '">  <option value="">请选择</option>';
        foreach ($educationLevels as $key => $educationLevel) {
            $selected = ($education_level == $key) ? 'selected ' : '';
            $htmlStr .= '<option value="' . $key . '" ' . $selected . '>' . $educationLevel . '</option>';
        }
        $htmlStr .= '</select>';

        return $htmlStr;
    }
}

//面签类型
if (!function_exists('renderAuditType')) {

    function renderAuditType($audit_type = '', $class = '')
    {
        $auditTypes = \App\Yoona\Dtos\YoAuditAdditional::$audit_type;
        $htmlStr = '<select name="audit_type" class="' . $class . '">  <option value="">请选择</option>';
        foreach ($auditTypes as $key => $auditType) {
            $selected = ($audit_type == $key) ? 'selected ' : '';
            $htmlStr .= '<option value="' . $key . '" ' . $selected . '>' . $auditType . '</option>';
        }
        $htmlStr .= '</select>';

        return $htmlStr;
    }
}

//银行流水月入金额
if (!function_exists('renderBankIncome')) {

    function renderBankIncome($bank_income = '', $class = '')
    {
        $bankIncomes = \App\Yoona\Dtos\YoAuditAdditional::$bank_income;
        $htmlStr = '<select name="bank_income" class="' . $class . '">  <option value="">请选择</option>';
        foreach ($bankIncomes as $key => $bankIncome) {
            $selected = ($bank_income == $key) ? 'selected ' : '';
            $htmlStr .= '<option value="' . $key . '" ' . $selected . '>' . $bankIncome . '</option>';
        }
        $htmlStr .= '</select>';

        return $htmlStr;
    }
}

//客户如何知道本产品
if (!function_exists('renderKnowAbout')) {

    function renderKnowAbout($know_about = '', $class = '')
    {
        $knowAbouts = \App\Yoona\Dtos\YoAuditAdditional::$know_about;
        $htmlStr = '<select name="know_about" class="' . $class . '">  <option value="">请选择</option>';
        foreach ($knowAbouts as $key => $knowAbout) {
            $selected = ($know_about == $key) ? 'selected ' : '';
            $htmlStr .= '<option value="' . $key . '" ' . $selected . '>' . $knowAbout . '</option>';
        }
        $htmlStr .= '</select>';

        return $htmlStr;
    }
}

//面签手机
if (!function_exists('renderAuditPhone')) {

    function renderAuditPhone($audit_phone = '', $class = '')
    {
        $auditPhones = \App\Yoona\Dtos\YoAuditAdditional::$audit_phone;
        $htmlStr = '<select name="audit_phone" class="' . $class . '">  <option value="">请选择</option>';
        foreach ($auditPhones as $key => $auditPhone) {
            $selected = ($audit_phone == $key) ? 'selected ' : '';
            $htmlStr .= '<option value="' . $key . '" ' . $selected . '>' . $auditPhone . '</option>';
        }
        $htmlStr .= '</select>';

        return $htmlStr;
    }
}

//信用卡额度
if (!function_exists('renderCreditQuota')) {

    function renderCreditQuota($credit_quota = '', $class = '')
    {
        $creditQuotas = \App\Yoona\Dtos\YoAuditAdditional::$credit_quota;
        $htmlStr = '<select name="credit_quota" class="' . $class . '">  <option value="">请选择</option>';
        foreach ($creditQuotas as $key => $creditQuota) {
            $selected = ($credit_quota == $key) ? 'selected ' : '';
            $htmlStr .= '<option value="' . $key . '" ' . $selected . '>' . $creditQuota . '</option>';
        }
        $htmlStr .= '</select>';

        return $htmlStr;
    }
}

//住房状况
if (!function_exists('renderHousingStatus')) {

    function renderHousingStatus($housing_status = '', $class = '')
    {
        $housingStatus = \App\Yoona\Dtos\YoAuditAdditional::$housing_status;
        $htmlStr = '<select name="housing_status" class="' . $class . '">  <option value="">请选择</option>';
        foreach ($housingStatus as $key => $housingStatu) {
            $selected = ($housing_status == $key) ? 'selected ' : '';
            $htmlStr .= '<option value="' . $key . '" ' . $selected . '>' . $housingStatu . '</option>';
        }
        $htmlStr .= '</select>';

        return $htmlStr;
    }
}

//车辆价值
if (!function_exists('renderCarPrice')) {

    function renderCarPrice($car_price = '', $class = '')
    {
        $carPrices = \App\Yoona\Dtos\YoAuditAdditional::$car_price;
        $htmlStr = '<select name="car_price" class="' . $class . '">  <option value="">请选择</option>';
        foreach ($carPrices as $key => $carPrice) {
            $selected = ($car_price == $key) ? 'selected ' : '';
            $htmlStr .= '<option value="' . $key . '" ' . $selected . '>' . $carPrice . '</option>';
        }
        $htmlStr .= '</select>';

        return $htmlStr;
    }
}

//家庭人数
if (!function_exists('renderFamilyNum')) {

    function renderFamilyNum($family_num = '', $class = '')
    {
        $familyNums = \App\Yoona\Dtos\YoAuditAdditional::$family_num;
        $htmlStr = '<select name="family_num" class="' . $class . '">  <option value="">请选择</option>';
        foreach ($familyNums as $key => $familyNum) {
            $selected = ($family_num == $key) ? 'selected ' : '';
            $htmlStr .= '<option value="' . $key . '" ' . $selected . '>' . $familyNum . '</option>';
        }
        $htmlStr .= '</select>';

        return $htmlStr;
    }
}

//判断客户列表操作选项
if (!function_exists('renderOpCustomer')) {

    function renderOpCustomer($userKey, $opCustomerType = 'all')
    {
        $htmlStr = '';

        $htmlCancel = '<a href="javascript:;" class="cancels"  data-ckey=' . $userKey . ' onclick="cancel();"><i class="R_angle"></i> 回退订单</a>';
        $htmlAudit = '<a class="continue" href="/yofront/interviewer/infolist/up/' . $userKey . '">面签补录</a>';
        $htmlExamine = '<a class="continue"  href="/yofront/examine/setexamine/' . $userKey . '">协查录入</a>';
        $htmlSel = '<a class="continue"  href="/yofront/interviewer/infolist/sel/' . $userKey . '">查看详情</a>';

        switch ($opCustomerType) {
            case 'all' :
                return $htmlStr . $htmlSel;
                break;
            case 'audit':
                return $htmlStr . $htmlCancel . $htmlAudit;
                break;
            case 'examine':
                return $htmlExamine;
                break;
            default:
                return $htmlStr;
                break;
        }

    }
}


//验证登陆check
if (!function_exists('checkLogined')) {

    function checkLogined($productName = '')
    {
        if (!App\Util\AdminAuth::check()) {
            header("Location:" . url("/bqjieqianadmin/admin/bkend"));
            exit;
        }
    }
}

//客户影像资料类型代码生成
if (!function_exists('buildPicType')) {
    function buildPicType()
    {
        $code = \Illuminate\Support\Facades\DB::table("yo_customer_pic_type")
            ->orderBy("type_code", "DESC")
            ->pluck("type_code");
        return empty($code) ? 1 : ($code + 1);
    }
}
