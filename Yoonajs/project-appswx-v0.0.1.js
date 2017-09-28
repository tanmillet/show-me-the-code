/**
 * Created by hp on 2016/12/1.
 * author tan - promise
 * globals service
 */

ProjectGlobals.Apps.Swx = {};

//异步提交网络异常
ProjectGlobals.Apps.Swx.asynError = function () {
}

//贷款信息提交校验
ProjectGlobals.Apps.Swx.upLoanInfoValidate = function (params) {
    var validateRules =
        {
            'id_number': ['required'],
            'periods': ['required'],
            'loan_reason': ['required'],
            'apply_product_code': ['required'],
            'sales_point_name': ['required'],
            'sales_point_address': ['required'],
            'sales_manager_mobile':['isPhone'],
        },
        validateMessages =
        {
            'required': '不能为空！',
            'number': '只能位数字！',
        },
        validateAttrbutes =
        {
            'id_number': '用户身份证',
            'periods': '申请期数',
            'loan_reason': '贷款用途',
            'apply_product_code': '贷款产品',
            'sales_point_name': '销售点名称',
            'sales_point_address': '销售点地址',
            'sales_manager_mobile': '销售经理联系方式',
        },
        validateError = '';

    validateError = ProjectGlobals.Globals.validateForm(
        params,
        validateRules,
        validateMessages,
        validateAttrbutes,
        validateError);

    return validateError;
}

//贷款信息提交校验
ProjectGlobals.Apps.Swx.upAppliacntInfoValidate = function (params) {

    var validateRules =
        {
            'age': ['required','moreThanZero'],
            // 'residential_mobile': ['isPhoneH'],
            // 'email': ['isEmail'],
            // 'residential_postcode': ['isPostCode'],
            // 'census_postcode': ['isPostCode'],
            // 'id_card_valid': ['required', 'after'],
            'id_card_valid': ['after'],
            'hometown': ['required'],
            'is_marital': ['required'],
            'census_city': ['required', 'isChinese'],
            'mobile': ['required', 'isPhone'],
            // 'qq_num': ['isDigits'],
            // 'social_security_number': ['isDigits'],
            'housing_properties': ['required'],
            'residential_city': ['required'],
            'residential_town': ['required', 'isChinese'],
            'education_level': ['required'],
            'children_num': ['required', 'minmax010'],
            'residential_postcode': ['isNotNullPostCode'],
            'email': ['isNotNullEmail'],
            'census_postcode': ['isNotNullPostCode'],
            'residential_mobile': ['isNotNullPhoneH']
        },
        validateMessages =
        {
            'required': '输入不能为空！',
            'isEmail': '输入正确填写邮编！',
            'isPhoneH': '输入正确的固定电话号码，区号和分机号分别用-隔开！',
            'isPostCode': '输入正确填写您的身份证号码！',
            'after': '输入身份证有效期必须大于当前！',
            'isPhone': '输入输入正确的手机号码！',
            'isChinese': '输入不能全为非汉字！',
            'minmax010': '输入最小值0最大值10之间的数字！',
            'isNotNullPostCode': '输入正确填写邮编！',
            'isNotNullEmail': '格式不正确',
            'isNotNullPhoneH': '输入正确的固定电话号码，区号和分机号分别用-隔开！',
            'moreThanZero': '必须大于0',
        },
        validateAttrbutes =
        {
            'age': '年龄',
            'id_card_valid': '有效期',
            'hometown': '籍贯',
            'residential_mobile': '住宅电话',
            'email': '邮箱',
            'residential_postcode': '邮编',
             'census_postcode': '邮编',
            'is_marital': '婚姻状况',
            'mobile': '联系电话',
            'census_city': '户籍地址',
            'housing_properties': '住房性质',
            'residential_city': '住宅地址',
            // 'qq_num': 'QQ',
            'residential_town': '详细地址',
            'education_level': '教育程度',
            'children_num': '子女数目',
            'email': '邮箱地址',
            // 'social_security_number': '个人社保电脑号',
        },
        validateError = '';

    validateError = ProjectGlobals.Globals.validateForm(
        params,
        validateRules,
        validateMessages,
        validateAttrbutes,
        validateError);

    return validateError;
}

ProjectGlobals.Apps.Swx.upBankInfoValidate = function (params) {

    var validateRules =
        {
            'bank_no': ['required', 'isBankCard'],
            'bank_city': ['required'],
            'bank_account_name': ['required'],
            'subbranch_name1': ['required'],
        },
        validateMessages =
        {
            'required': '输入不能为空！',
            'isBankCard': '输入正确的银行账号！',
        },
        validateAttrbutes =
        {
            'bank_no': '银行账户号',
            'bank_city': '开户城市',
            'bank_account_name': '账户名',
            'subbranch_name1': '开户行',
        },
        validateError = '';

    validateError = ProjectGlobals.Globals.validateForm(
        params,
        validateRules,
        validateMessages,
        validateAttrbutes,
        validateError);

    return validateError;
}

ProjectGlobals.Apps.Swx.upCompanyInfoValidate = function (params) {
    var validateRules =
        {
            'company_name': ['required', 'isChinese'],
            'company_nature': ['required'],
            'company_category': ['required'],
            'company_mobile': ['required'],
            'legal': ['required','isChinese'],
            'company_city': ['required'],
            'company_town': ['required'],
            'company_mobile': ['required', 'isPhoneV'],            
            'is_social_security': ['required'],            
            'registration_time': ['isNotNullBefore'],
            'registered_capital': ['isNotNullNum'],
            'zip_code': ['isNotNullPostCode'],
            'employee_number': ['required','isNum'],
            'is_social_security': ['required'],
        },
        validateMessages =
        {
            'required': '输入不能为空！',
            'isPhoneV': '输入正确的电话号码！',
            'isChinese': '输入不能全为非汉字！',
            'isNotNullBefore': '不能在当前日期之后',
            'isNotNullPostCode': '格式错误',
            'isNotNullNum': '输入必须为数字',
        },
        validateAttrbutes =
        {
            'company_name': '单位名称',
            'company_nature': '单位性质',
            'company_category': '行业类别',
            'company_mobile': '单位电话',
            'legal': '法人代表',
            'company_city': '单位地址',
            'company_town': '详细地址',
            'company_mobile': '单位电话',
            'is_social_security': '是否缴纳',            
            'registration_time': '注册时间',
            'registered_capital': '注册资金',
            'zip_code': '单位邮编',
            'employee_number': '员工人数',
            'is_social_security': '是否缴纳社保',
        },
        validateError = '';

    validateError = ProjectGlobals.Globals.validateForm(
        params,
        validateRules,
        validateMessages,
        validateAttrbutes,
        validateError);

    return validateError;
}

ProjectGlobals.Apps.Swx.upEstateInfoValidate = function (params) {
    var validateRules =
        {
            'estate_size': ['isNotNullNum'],
            'loan_number': ['isNotNullNum'],
            'lan_balance': ['isNotNullNum'],
            'estate_price':['isNotNullNum'],
            'month_pay_money': ['isNotNullNum'],
            'acquired_way':['required'],
            'usage': ['required'],
        },
        validateMessages =
        {
            'required': '输入不能为空！',
            'isNotNullNum': '输入必须为数字',
        },
        validateAttrbutes =
        {
            'estate_size': '房产面积',
            'loan_number': '贷款金额',
            'lan_balance': '贷款余额',
            'estate_price': '购买价格',
            'month_pay_money': '月还款额',
            'acquired_way': '房产取得方式',
            'usage': '房产使用情况',


        },
        validateError = '';

    validateError = ProjectGlobals.Globals.validateForm(
        params,
        validateRules,
        validateMessages,
        validateAttrbutes,
        validateError);

    return validateError;
}

ProjectGlobals.Apps.Swx.upFamilyInfoValidate = function (params) {
    var validateRules =
        {
            // 'spouse_name': ['required'],
            // 'spouse_id_number': ['required', 'isIdCard'],
            // 'spouse_mobile': ['required', 'isPhone'],
            // 'spouse_age': ['required'],
            // 'spouse_town': ['required', 'isChinese'],
            // 'spouse_company_mobile': ['required', 'isPhoneV'],
            //'family_id_number': ['required', 'isIdCard'],
            'family_name': ['required'],
            'family_mobile': ['required', 'isPhone'],
            'family_ralate': ['required'],
            'family_isknow': ['required'],
            'family_isagree': ['required'],
            'family_company_mobile': ['required', 'isPhoneV'],
            'family_house_mobile': ['required', 'isPhoneV'],
            'usage': ['required'],
            'family_city': ['required'],
            'family_town': ['required'],
            'family_isknow': ['required'],
            'family_isagree': ['required'],
        },
        validateMessages =
        {
            'required': '输入不能为空！',
            'isPhone': '请输入正确的电话号码！',
            'isIdCard': '请正确填写您的身份证号码！',
            'isPhoneV': '输入正确的电话号码！',
            'isChinese': '输入不能全为非汉字！',
        },
        validateAttrbutes =
        {
            // 'spouse_name': '配偶姓名',
            // 'spouse_id_number': '配偶身份证号码',
            // 'spouse_mobile': '配偶联系电话',
            // 'spouse_age': '配偶年龄',
            // 'spouse_town': '配偶单位详细地址',
            // 'spouse_company_mobile': '配偶单位电话',
            //'family_id_number': '家庭成员身份证号码',
            'family_name': '家庭成员姓名',
            'family_mobile': '家庭成员电话',
            'family_ralate': '家庭成员关系',
            'family_address': '家庭成员住宅详细',
            'family_isknow': '家庭成员是否知悉贷款',
            'family_isagree': '家庭成员是否同意贷款',
            'family_company_mobile': '家庭成员单位电话',
            'family_house_mobile': '家庭成员住宅电话',
            'usage': '家庭成员关系',
            'family_city': '住宅地址',
            'family_town': '详细地址',
            'family_isknow': '家庭成员是否知悉贷款',
            'family_isagree': '家庭成员是否同意贷款',
        },
        validateError = '';

    validateError = ProjectGlobals.Globals.validateForm(
        params,
        validateRules,
        validateMessages,
        validateAttrbutes,
        validateError);

    return validateError;
}

ProjectGlobals.Apps.Swx.upOtherInfoValidate = function (params) {
    var validateRules =
        {
            'colleague_name': ['required'],
            'colleague_mobile': ['required', 'isPhone'],
            'colleague_town': ['required'],
            'colleague_city': ['required'],
            'friend_name': ['required'],
            'friend_mobile': ['required', 'isPhone'],
            'friend_town': ['required'],
            'friend_city': ['required']
        },
        validateMessages =
        {
            'required': '输入不能为空！',
            'isPhone': '请输入正确的手机号码！',
        },
        validateAttrbutes =
        {
            'colleague_name': '同事姓名',
            'colleague_mobile': '同事联系电话',
            'colleague_town': '同事详细地址',
            'colleague_city': '同事联系地址',
            'friend_name': '朋友姓名',
            'friend_mobile': '朋友联系电话',
            'friend_town': '朋友详细地址',
            'friend_city': '朋友联系地址',
        },
        validateError = '';

    validateError = ProjectGlobals.Globals.validateForm(
        params,
        validateRules,
        validateMessages,
        validateAttrbutes,
        validateError);

    return validateError;
}

ProjectGlobals.Apps.Swx.upCreditInfoValidate = function (params) {
    var validateRules =
        {},
        validateMessages =
        {},
        validateAttrbutes =
        {},
        validateError = '';

    validateError = ProjectGlobals.Globals.validateForm(
        params,
        validateRules,
        validateMessages,
        validateAttrbutes,
        validateError);

    return validateError;
}

ProjectGlobals.Apps.Swx.upGuaranteeInfoValidate = function (params) {
    var validateRules =
        {
            'name': ['required'],
            'id_card': ['required','isIdCard'],
            'email': ['required','isEmail'],
            'phone': ['required','isPhone'],
            'address': ['required'],
            'address_detailed': ['required'],
            'bank_account': ['required'],
            'bank_code': ['required','isBankCard'],
        },
        validateMessages =
        {
            'required': '输入不能为空！',
            'isIdCard': '格式不对',
            'isEmail': '格式不对',
            'isPhone': '格式不对',
            'isBankCard': '格式不对',
        },
        validateAttrbutes =
        {
            'name': '保证人姓名',
            'id_card': '身份证号码',
            'email': '电子邮件',
            'phone': '联系电话',
            'address': '联系地址',
            'address_detailed': '详细地址',
            'bank_account': '开户行',
            'bank_code': '银行账号',
        },
        validateError = '';

    validateError = ProjectGlobals.Globals.validateForm(
        params,
        validateRules,
        validateMessages,
        validateAttrbutes,
        validateError);

    return validateError;
}