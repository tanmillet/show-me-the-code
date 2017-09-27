/*
几点说明：
		1.表里尽可能的存的是代码，像地区代码使用公共的，所以在要求省市区（镇/县）的表，目前只保留最小一级的代码，像罗湖区代码440303，基础表里存的名字是广东省深圳市罗湖区
		2.大部分表除了主键id，还有唯一索引的字段user_key外，都默认null，理由是都是1对1关系，所以考虑到model的createorupdate的方法，所以必填字段建议由程序进行控制
		3.所有表的字段如系统自动生成的或存代码的，如用户唯一标识user_key，默认使用固定长度类型，如CHAR、TINYINT等，不使用可变长度类型，理由是查询速率的优化等
		4.所有表只是初步设计，包括表名，字段名，字段类型以及字段长度等如觉得有修改的地方都可以提出来，由于考虑到api接口同步数据，所以字段名尽可能的不要重复
*/

DROP TABLE IF EXISTS `yo_customer_info`;
CREATE TABLE `yo_customer_info` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_key` CHAR(32) NOT NULL COMMENT '用户唯一标识',
  `mobile` CHAR(11) NOT NULL COMMENT '手机号码',
  `openid` VARCHAR(50) DEFAULT NULL COMMENT '绑定关联的微信openid',
  `real_name` VARCHAR(10) DEFAULT NULL COMMENT '真实姓名',
  `cert_no` CHAR(18) DEFAULT NULL COMMENT '身份证号码',
  `cert_valid_date` INT(11) DEFAULT NULL COMMENT '身份证有效期',
  `cert_city` CHAR(6) DEFAULT NULL COMMENT '身份证地址所在城市代码',
  `cert_address` VARCHAR(30) DEFAULT NULL COMMENT '身份证地址',
  `bank_card` VARCHAR(20) DEFAULT NULL COMMENT '银行卡卡号',
  `bank_name` VARCHAR(5) DEFAULT NULL COMMENT '开户行名称，使用银行英文缩写，如：中国工商银行-ICBC；中国银行-BOC等',
  `bank_city` CHAR(6) DEFAULT NULL COMMENT '开户行所在城市代码',
  `branch_name` VARCHAR(50) DEFAULT NULL COMMENT '支行名称',
  `branch_mobile` CHAR(11) DEFAULT NULL COMMENT '银行预留手机号',
  `bank_auth_result` TINYINT(1) DEFAULT 0 COMMENT '银行卡认证结果：0、未认证、1、通过认证；2、不通过认证；',
  `wechat_num` VARCHAR(20) DEFAULT NULL COMMENT '微信号',
  `wechat_name` VARCHAR(10) DEFAULT NULL COMMENT '微信昵称',
  `qq_num` VARCHAR(20) DEFAULT NULL COMMENT 'QQ号',
  `birthday` INT(11) DEFAULT NULL COMMENT '生日',
  `email` VARCHAR(50) DEFAULT NULL COMMENT '邮箱',
  `information_type` VARCHAR(50) DEFAULT NULL COMMENT '信息来源：客户，第三方，销售',
  `custom_type` VARCHAR(50) DEFAULT NULL COMMENT '客户来源：融360，KN',
  `education_level` TINYINT(1) DEFAULT NULL COMMENT '教育程度：0-未知；1-小学；2-初中；3-高中；4-中专；5-大专；6-本科；7-研究生及以上',
  `audit_address` VARCHAR(30) DEFAULT NULL COMMENT '面签地址',
  `audit_amount` INT(11) DEFAULT NULL COMMENT '审批额度',
  `created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` TIMESTAMP NULL DEFAULT NULL COMMENT '用于软删除记录时间，默认为空',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_key_unique` (`user_key`),
  UNIQUE KEY `mobile_unique` (`mobile`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '无预约现金贷——客户个人信息';

-- 预审阶段相关表
DROP TABLE IF EXISTS `yo_trial_company`;
CREATE TABLE `yo_trial_company` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_key` CHAR(32) NOT NULL COMMENT '用户唯一标识',
  `company_name` VARCHAR(50) DEFAULT NULL COMMENT '单位名称',
  `company_category` CHAR(1) DEFAULT NULL COMMENT '单位行业类别代码：A-农、林、牧、渔业；B-采矿业；C-制造业； D-电力、热力、燃气及水生产和供应业；E-建筑业；F-批发和零售业；G-交通运输、仓储和邮政业；H-住宿和餐饮业；I-信息传输、软件和信息技术服务业；J-金融业；K-房地产业；L-租赁和商务服务业；M-科学研究和技术服务业；N-水利、环境和公共设施管理业；O-居民服务、修理和其他服务业；P-教育；Q-卫生和社会工作；R-文化、体育和娱乐业；S-公共管理、社会保障和社会组织；T-国际组织',
  `company_properties` CHAR(2) DEFAULT NULL COMMENT '单位性质代码：10-机关；20-科研设计单位；21-高等教育单位；22-中初教育单位；23-医疗卫生单位；29-其他事业单位；31-国有企业；32-三资企业；39-其他企业；40-部队；55-农村建制村；56-城镇社区；99-其他',
  `company_district` CHAR(6) DEFAULT NULL COMMENT '单位所在城市的区/县/镇代码',
  `company_address` VARCHAR(30) DEFAULT NULL COMMENT '单位所在地址',
  `company_department` VARCHAR(10) DEFAULT NULL COMMENT '部门',
  `company_position` VARCHAR(10) DEFAULT NULL COMMENT '职位',
  `company_phone` VARCHAR(20) DEFAULT NULL COMMENT '工作电话（座机或手机号码）',
  `company_email` VARCHAR(50) DEFAULT NULL COMMENT '工作邮箱',
  `monthly_income` DECIMAL(11,2) DEFAULT NULL COMMENT '月收入',
  `length_of_employment` INT(11) DEFAULT NULL COMMENT '工作年限',
  `colleague_name` VARCHAR(10) DEFAULT NULL COMMENT '同事姓名',
  `colleague_mobile`  CHAR(11) DEFAULT NULL COMMENT '同事手机',
  `created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_key_unique` (`user_key`),
  CONSTRAINT `TrialCompany_UserKey` FOREIGN KEY (`user_key`) REFERENCES `yo_customer_info` (`user_key`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '无预约现金贷——预审阶段工作单位信息';

DROP TABLE IF EXISTS `yo_trial_family`;
CREATE TABLE `yo_trial_family` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_key` CHAR(32) NOT NULL COMMENT '用户唯一标识',
  `is_married` TINYINT(1) DEFAULT NULL COMMENT '婚姻状况：0-未婚；1-已婚；2-离异；3-丧偶',
  `immediate_name` VARCHAR(10) DEFAULT NULL COMMENT '直系亲属联系人姓名',
  `immediate_relationship` TINYINT(1) DEFAULT NULL COMMENT '与直系亲属关系：1-父亲；2-母亲；3-儿子；4-女儿',
  `immediate_mobile` CHAR(11) DEFAULT NULL COMMENT '直系亲属联系人手机号码',
  `family_city` CHAR(6) DEFAULT NULL COMMENT '家庭地址所在城市代码',
  `family_address` VARCHAR(30) DEFAULT NULL COMMENT '家庭地址',
  `created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_key_unique` (`user_key`),
  CONSTRAINT `TrialFamily_UserKey` FOREIGN KEY (`user_key`) REFERENCES `yo_customer_info` (`user_key`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '无预约现金贷——预审阶段工作单位信息';

DROP TABLE IF EXISTS `yo_trial_mobile`;
CREATE TABLE `yo_trial_mobile` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_key` CHAR(32) NOT NULL COMMENT '用户唯一标识',
  `ip` VARCHAR(15) DEFAULT NULL COMMENT '当前申请时的ip地址',
  `directories` TEXT DEFAULT NULL COMMENT '通讯录信息，存json串',
  `call_records` TEXT DEFAULT NULL COMMENT '通话记录信息，存json串',
  `imei` VARCHAR(50) DEFAULT NULL COMMENT '手机IMEI码',
  `phone_type` VARCHAR(50) DEFAULT NULL COMMENT '手机型号（包括手机品牌）',
  `gps_msg` TEXT DEFAULT NULL COMMENT 'GPS信息：经纬度',
  `gps_address` VARCHAR(50) DEFAULT NULL COMMENT 'GPS地址',
  `is_root` TINYINT(1) DEFAULT NULL COMMENT '是否越狱/ROOT：0-否；1-是',
  `app_version` VARCHAR(50) DEFAULT NULL COMMENT 'APP版本',
  `system_version` VARCHAR(50) DEFAULT NULL COMMENT '手机系统版本',
  `mac_address` VARCHAR(50) DEFAULT NULL COMMENT '手机MAC地址',
  `wifi` VARCHAR(50) DEFAULT NULL COMMENT '当前申请时的WIFI名称',
  `wifi_mac_address` VARCHAR(50) DEFAULT NULL COMMENT 'WIFI MAC地址',
  `app_num`  INT(11) DEFAULT NULL COMMENT '手机APP数量',
  `finance_app_num`  INT(11) DEFAULT NULL COMMENT '手机金融APP数量',
  `created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_key_unique` (`user_key`),
  CONSTRAINT `TrialMobile_UserKey` FOREIGN KEY (`user_key`) REFERENCES `yo_customer_info` (`user_key`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '无预约现金贷——预审阶段手机相关信息';

DROP TABLE IF EXISTS `yo_trial_phone_record_list`;
CREATE TABLE `yo_trial_phone_record_list` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `trial_mobile_id` INT(11) NOT NULL COMMENT '预审阶段手机相关信息id',
  `phone_num` VARCHAR(20) DEFAULT NULL COMMENT '通话号码',
  `phone_addtime` INT(11) DEFAULT NULL COMMENT '通话时间',
  `phone_time` INT(11) DEFAULT NULL COMMENT '通话时长，单位是秒',
  `phone_type` TINYINT(1) DEFAULT NULL COMMENT '通话类型：1-呼出；2-接听',
  `created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  CONSTRAINT `TrialMobile_Phone_Record` FOREIGN KEY (`trial_mobile_id`) REFERENCES `yo_trial_mobile` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '无预约现金贷——预审阶段手机通话记录';

DROP TABLE IF EXISTS `yo_trial_directories_list`;
CREATE TABLE `yo_trial_directories_list` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `trial_mobile_id` INT(11) DEFAULT NULL COMMENT '预审阶段手机相关信息id',
  `communication_name` VARCHAR(10) DEFAULT NULL COMMENT '通讯录姓名',
  `communication_mobile` VARCHAR(20) DEFAULT NULL COMMENT '通讯录电话',
  `communication_company` VARCHAR(50) DEFAULT NULL COMMENT '通讯录公司名称',
  `communication_birthday` VARCHAR(50) DEFAULT NULL COMMENT '通讯录生日',
  `communication_email` VARCHAR(50) DEFAULT NULL COMMENT '通讯录邮箱',
  `created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
   CONSTRAINT `TrialMobile_Directories` FOREIGN KEY (`trial_mobile_id`) REFERENCES `yo_trial_mobile` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '无预约现金贷——预审阶段手机通讯录';

DROP TABLE IF EXISTS `yo_trial_additional_info`;
CREATE TABLE `yo_trial_additional_info` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_key` CHAR(32) NOT NULL COMMENT '用户唯一标识',
  `channel` TINYINT(1) DEFAULT NULL COMMENT '渠道：1-APP；2-微信',
  `product_name` VARCHAR(50) DEFAULT NULL COMMENT '产品名称',
  `product_code` VARCHAR(50) DEFAULT NULL COMMENT '产品代码',
  `amount` DECIMAL(11,2) DEFAULT NULL COMMENT '金额',
  `periods` INT(2) DEFAULT NULL COMMENT '分期期数',
  `per_repayment` DECIMAL(11,2) DEFAULT NULL COMMENT '每期还款额',
  `description` TEXT DEFAULT NULL COMMENT '商品描述',
  `loan_purpose` VARCHAR(50) DEFAULT NULL COMMENT '借款用途',
  `scan_product_num` INT(11) DEFAULT NULL COMMENT '下单前浏览商品个数',
  `danger_level` VARCHAR(50) DEFAULT NULL COMMENT '商品高危等级',
  `business_type` VARCHAR(50) DEFAULT NULL COMMENT '业务：MMT,XJD',
  `is_wifi` TINYINT(1) DEFAULT NULL COMMENT '是否使用WiFi：0-否；1-是',
  `is_gsm` TINYINT(1) DEFAULT NULL COMMENT '是否使用GSM：0-否；1-是',
  `attach` TEXT DEFAULT NULL COMMENT '附加字段',
  `created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_key_unique` (`user_key`),
  CONSTRAINT `TrialAdditional_UserKey` FOREIGN KEY (`user_key`) REFERENCES `yo_customer_info` (`user_key`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '无预约现金贷——预审阶段补充信息';

DROP TABLE IF EXISTS `yo_trial_auth`;
CREATE TABLE `yo_trial_auth` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_key` CHAR(32) NOT NULL COMMENT '用户唯一标识',
  `fr_idcard_vivo_score` TEXT DEFAULT NULL COMMENT '人脸识别比对结果1',
  `fr_nciic_vivo_score` TEXT DEFAULT NULL COMMENT '人脸识别比对结果2',
  `fr_idcard_nciic_score` TEXT DEFAULT NULL COMMENT '人脸识别比对结果3',
  `vivo_score` DECIMAL(3,2) DEFAULT NULL COMMENT '活体检验分数',
  `fr_start_time` INT(11) DEFAULT NULL COMMENT '人脸识别开始时间',
  `fr_end_time` INT(11) DEFAULT NULL COMMENT '人脸识别结束时间',
  `fr_fail_cnt` INT(11) DEFAULT NULL COMMENT '人脸识别失败次数',
  `cert_valid_score` DECIMAL(3,2) DEFAULT NULL COMMENT '身份证识别分数',
  `created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_key_unique` (`user_key`),
  CONSTRAINT `TrialAuth_UserKey` FOREIGN KEY (`user_key`) REFERENCES `yo_customer_info` (`user_key`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '无预约现金贷——预审阶段认证相关';

DROP TABLE IF EXISTS `yo_trial_action`;
CREATE TABLE `yo_trial_action` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_key` CHAR(32) NOT NULL COMMENT '用户唯一标识',
  `enter_auth_page_time` INT(11) DEFAULT NULL COMMENT '身份验证开始时间：用户进入身份',
  `leave_auth_page_time` INT(11) DEFAULT NULL COMMENT '身份验证结束时间：点击用户提交按钮的时间',
  `focus_cert_wg_time` INT(11) DEFAULT NULL COMMENT '身份证填写开始时间：焦点第一次聚焦在身份证控件时间',
  `unfocus_cert_wg_time` INT(11) DEFAULT NULL COMMENT '身份证填写完成时间：焦点第一次离开身份证控件时间',
  `focus_vcode_wg_time` INT(11) DEFAULT NULL COMMENT '验证码填写开始时间：焦点第一次聚焦在验证码控件时间',
  `unfocus_vcode_wg_time` INT(11) DEFAULT NULL COMMENT '验证码填写结束时间：焦点第一次离开验证码控件时间',
  `focus_cert_wg_num` INT(11) DEFAULT NULL COMMENT '填写身份证跳转次数：焦点聚焦到身份证控件的次数',
  `focus_card_wg_num` INT(11) DEFAULT NULL COMMENT '填写银行卡跳转次数：焦点聚焦到银行卡控件的次数',
  `focus_phone_wg_num` INT(11) DEFAULT NULL COMMENT '填写手机号跳转次数：焦点聚焦到手机号控件的次数',
  `start_home_info_time` INT(11) DEFAULT NULL COMMENT '家庭信息填写开始时间：用户进入家庭信息页面的时间',
  `end_home_info_time` INT(11) DEFAULT NULL COMMENT '家庭信息填写结束时间：用户离开家庭信息页面的时间',
  `start_work_info_time` INT(11) DEFAULT NULL COMMENT '工作信息填写开始时间：用户进入工作信息页面的时间',
  `end_work_info_time` INT(11) DEFAULT NULL COMMENT '工作信息填写结束时间：用户离开工作信息页面的时间',
  `fp_try_cnt` INT(11) DEFAULT NULL COMMENT '尝试首付次数',
  `period_try_cnt` INT(11) DEFAULT NULL COMMENT '尝试分期次数',
  `loan_amt_try_cnt` INT(11) DEFAULT NULL COMMENT '尝试借款金额次数',
  `cert_pic1_upload_time` INT(11) DEFAULT NULL COMMENT '身份证照片正面上传时间',
  `cert_pic2_upload_time` INT(11) DEFAULT NULL COMMENT '身份证照片反面上传时间',
  `created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_key_unique` (`user_key`),
  CONSTRAINT `TrialAction_UserKey` FOREIGN KEY (`user_key`) REFERENCES `yo_customer_info` (`user_key`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '无预约现金贷——预审阶段行为相关';

DROP TABLE IF EXISTS `yo_trial_pic`;
CREATE TABLE `yo_trial_pic` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_key` CHAR(32) NOT NULL COMMENT '用户唯一标识',
  `url` CHAR(32) DEFAULT NULL COMMENT '上传到服务器后的文件名（有后缀名），目录定义为常用类的常量',
  `type` TINYINT(1) DEFAULT NULL COMMENT '影像资料类型',
  `filename` VARCHAR(50) DEFAULT NULL COMMENT '原始文件名',
  `created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `url_unique` (`url`),
  CONSTRAINT `TrialPic_UserKey` FOREIGN KEY (`user_key`) REFERENCES `yo_customer_info` (`user_key`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `TrialPic_TypeKey` FOREIGN KEY (`type`) REFERENCES `yo_customer_pic_type` (`type_code`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '无预约现金贷——预审阶段影像资料';

-- 面审阶段相关表
DROP TABLE IF EXISTS `yo_audit_additional`;
CREATE TABLE `yo_audit_additional` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_key` CHAR(32) NOT NULL COMMENT '用户唯一标识',
  `audit_type` TINYINT(1) DEFAULT NULL COMMENT '面签类型：1-住址；2-单位；3-无法判断；4-其他',
  `audit_type_text` VARCHAR(30) DEFAULT NULL COMMENT '面签类型为其他时，具体的说明',
  `bank_income` TINYINT(1) DEFAULT NULL COMMENT '银行流水月入金额：1-3000元以下；2-3000至6000元；3-6001至10000万；4-10001至20000元；5-20001元以上',
  `know_about` TINYINT(1) DEFAULT NULL COMMENT '客户如何知道本产品：1-佰仟老客户；2-老客户介绍；3-其他人介绍；4-广告；5-网上看到；6-其他',
  `know_about_text` VARCHAR(30) DEFAULT NULL COMMENT '客户如何知道本产品为其他时，具体的说明',
  `audit_phone` TINYINT(1) DEFAULT NULL COMMENT '面签手机：1-iPhone系列；2-三星系列；3-华为系列；4-小米系列；5-OPPO系列；6-vivo系列；7-其他',
  `audit_phone_text` VARCHAR(30) DEFAULT NULL COMMENT '面签手机为其他时，具体的说明',
  `has_credit_card` TINYINT(1) DEFAULT NULL COMMENT '是否有信用卡：0-否；1-是',
  `credit_quota` TINYINT(1) DEFAULT NULL COMMENT '信用卡额度：1-5000以下；2-5000至10000元；3-10001至20000元；4-20001至50000元；5-5万元以上',
  `audit_wechat_num` VARCHAR(30) DEFAULT NULL COMMENT '微信号',
  `housing_status` TINYINT(1) DEFAULT NULL COMMENT '住房状况：1-自有房；2-家族房；3-租住房；4-宿舍',
  `has_car` TINYINT(1) DEFAULT NULL COMMENT '是否有车：0-否；1-是',
  `car_price` TINYINT(1) DEFAULT NULL COMMENT '车辆价值：1-5万以下；2-6至10万；3-11至30万；4-30万以上',
  `family_kwnoable` TINYINT(1) DEFAULT NULL COMMENT '家人是否知情：0-否；1-是',
  `family_num` TINYINT(1) DEFAULT NULL COMMENT '家庭人数：1-1人；2-2人；3-3至4人；4-4人以上',
  `audit_gps_address` VARCHAR(30) DEFAULT NULL COMMENT '面签地址（GPS抓取）',
  `audit_net_address` VARCHAR(30) DEFAULT NULL COMMENT '网约地址',
  `audit_num` INT(11) DEFAULT NULL COMMENT '面签尝试次数',
  `audit_time` INT(11) DEFAULT NULL COMMENT '面签员接单响应时间，单位秒',
  `created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_key_unique` (`user_key`),
  CONSTRAINT `AuditAdditional_UserKey` FOREIGN KEY (`user_key`) REFERENCES `yo_customer_info` (`user_key`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '无预约现金贷——面审阶段补充信息';

DROP TABLE IF EXISTS `yo_audit_assist`;
CREATE TABLE `yo_audit_assist` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_key` CHAR(32) NOT NULL COMMENT '用户唯一标识',
  `family_address_real` TINYINT(1) DEFAULT NULL COMMENT '家庭地址是否真实：0-否；1-是；2-无法判断',
  `company_address_real` TINYINT(1) DEFAULT NULL COMMENT '工作地址是否真实：0-否；1-是；2-无法判断',
  `is_live_with_family` TINYINT(1) DEFAULT NULL COMMENT '客户是否与家人同住：0-否；1-是；',
  `is_accompany` TINYINT(1) DEFAULT NULL COMMENT '是否有人陪同：0-否；1-是；',
  `accompany_num` TINYINT(1) DEFAULT NULL COMMENT '陪同办理人数：0-0人；1-1人；2-2人；3-3人及以上',
  `is_accompany_apply` TINYINT(1) DEFAULT NULL COMMENT '陪同人员是否有申请过：0-否；1-是；',
  `accompany_relationship` TINYINT(1) DEFAULT NULL COMMENT '客户与陪同人员关系：1-亲属；2-朋友；3-室友；4-同事；5-其他',
  `accompany_relationship_text` VARCHAR(10) DEFAULT NULL COMMENT '客户与陪同人员关系为其他时具体的说明',
  `accompany_sex` TINYINT(1) DEFAULT NULL COMMENT '陪同人性别：1-男；2-女；3-男女都有；4-没有人陪同',
  `data_truth` TINYINT(1) DEFAULT NULL COMMENT '资料是否有作假：0-否；1-是',
  `is_bad_habit` TINYINT(1) DEFAULT NULL COMMENT '申请人是否有吸毒、赌博等不良嗜好：0-否；1-是；2-不确定',
  `is_cheat` TINYINT(1) DEFAULT NULL COMMENT '是否为疑似欺诈客户：0-否；1-是；2-不确定',
  `is_follow_by_rate` TINYINT(1) DEFAULT NULL COMMENT '面签时是否关注产品费率、还款时间等信息：0-否；1-是；2-不确定',
  `is_overdue` TINYINT(1) DEFAULT NULL COMMENT '是否为银行或同行当前逾期客户：0-否；1-是；2-不确定',
  `is_carry_bank_card` TINYINT(1) DEFAULT NULL COMMENT '是否随身携带登记银行卡：0-否；1-是',
  `company_info_real` TINYINT(1) DEFAULT NULL COMMENT '工作信息是否真实：0-否；1-是；2-不确定',
  `address_info_real` TINYINT(1) DEFAULT NULL COMMENT '住址信息是否真实：0-否；1-是；2-不确定',
  `forecast_comprehensive_income` TINYINT(1) DEFAULT NULL COMMENT '预估综合月收入水平：1-3000元以下；2-3000至6000元；3-6001至10000万；4-10001至20000元；5-20001元以上以下',
  `custom_level` TINYINT(1) DEFAULT NULL COMMENT '客户资质评级：1-非常好；2-较好；3-一般；4-有风险；5-高风险',
  `is_check_original` TINYINT(1) DEFAULT NULL COMMENT '是否查验原件：0-否；1-是',
  `compare_result` TINYINT(1) DEFAULT NULL COMMENT '原件和申请材料是否相同：0-不相同；1-相同；2-部分相同',
  `is_data_cheat` TINYINT(1) DEFAULT NULL COMMENT '原件资料是否有作假：0-否；1-是；2-部分作假；3-无法判断',
  `is_identity_match` TINYINT(1) DEFAULT NULL COMMENT '客户的身份/穿着与住址/单位是否匹配：0-否；1-是；2-无法判断',
  `is_abnormal_behavior` TINYINT(1) DEFAULT NULL COMMENT '是否有异常行为：0-否；1-是',
  `is_update_address` TINYINT(1) DEFAULT NULL COMMENT '是否修改面签地址：0-否；1-是',
  `is_check_bankincome` TINYINT(1) DEFAULT NULL COMMENT '是否查看了银行流水：0-否；1-是',
  `is_abnormal_with_company` TINYINT(1) DEFAULT NULL COMMENT '客户提供的联系人和单位信息是否有异常：0-否；1-是；2-无法判断',
  `is_know_about_company` TINYINT(1) DEFAULT NULL COMMENT '是否明确知道客户企业在当地的存在（地理位置、企业规模）：0-不知道；1-知道；2-不确定',
  `add_qq_or_wechat` TINYINT(1) DEFAULT NULL COMMENT '已添加客户的微信/QQ：1-QQ；2-微信；3-都已添加；0-都没有',
  `has_apply_product` TINYINT(1) DEFAULT NULL COMMENT '客户是否办理过佰仟其他业务：0-否；1-是',
  `has_apply_bussiness` TINYINT(1) DEFAULT NULL COMMENT '客户是否办理过同类业务：0-否；1-是',
  `has_settle` TINYINT(1) DEFAULT NULL COMMENT '是否都已结清；0-否；1-是；2-部分已结清',
  `is_overdue_three_months` TINYINT(1) DEFAULT NULL COMMENT '最近3个月是否有过逾期：0-否；1-是；2-不告知',
  `live_time` TINYINT(1) DEFAULT NULL COMMENT '客户在当地居住生活时间；1-6个月以内：2-6个月至1年；3-1至3年；4-3年以上',
  `is_immediate_family_live` TINYINT(1) DEFAULT NULL COMMENT '客户直系亲属是否在当地居住生活：0-否；1-是',
  `is_custom_info_match` TINYINT(1) DEFAULT NULL COMMENT '客户身份证、社保卡、结婚证、工牌上的照片、姓名及身份证号码是否一致：0-否；1-是；2-部分一致',
  `apply_reason` TINYINT(1) DEFAULT NULL COMMENT '客户办理申请的原因：1-购买生活用品；2-生意周转；3-还债/欠款；4-其他',
  `apply_reason_text` VARCHAR(30) DEFAULT NULL COMMENT '客户办理申请的原因为其他时，具体说明',
  `remark` TEXT DEFAULT NULL COMMENT '其他补充信息',
  `is_update_audit` TINYINT(1) DEFAULT NULL COMMENT '面审是否有修改之前填写资料：1-是；0-否',
  `created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_key_unique` (`user_key`),
  CONSTRAINT `AuditAssist_UserKey` FOREIGN KEY (`user_key`) REFERENCES `yo_customer_info` (`user_key`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '无预约现金贷——面审阶段面签员协审信息';

DROP TABLE IF EXISTS `yo_audit_pic`;
CREATE TABLE `yo_audit_pic` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_key` CHAR(32) NOT NULL COMMENT '用户唯一标识',
  `url` CHAR(32) DEFAULT NULL COMMENT '上传到服务器后的文件名（有后缀名），目录定义为常用类的常量',
  `type` TINYINT(1) DEFAULT NULL COMMENT '影像资料类型',
  `filename` VARCHAR(50) DEFAULT NULL COMMENT '原始文件名',
  `created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `url_unique` (`url`),
  CONSTRAINT `AuditPic_UserKey` FOREIGN KEY (`user_key`) REFERENCES `yo_customer_info` (`user_key`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `AuditPic_TypeKey` FOREIGN KEY (`type`) REFERENCES `yo_customer_pic_type` (`type_code`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '无预约现金贷——面审阶段影像资料';

DROP TABLE IF EXISTS `yo_cumstom_result`;
CREATE TABLE `yo_cumstom_result` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_key` CHAR(32) NOT NULL COMMENT '用户唯一标识',
  `status` INT(3) DEFAULT NULL COMMENT '客户状态：99-撤销；100-新客源；150-待预审；170-预审同步API失败；200-预审中（API已同步）；300-预审通过；350-预审不通过；360-客户拒绝面签；400-待面签；450-面签退回；500-面签中；550-面签不通过；600-面签通过；700-可贷款',
  `remark` TEXT DEFAULT NULL COMMENT '备注，记录退回原因等',
  `work_no` char(30) DEFAULT NULL COMMENT '面签者 工号',
  `created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_key_unique` (`user_key`),
  CONSTRAINT `cumstom_result_UserKey` FOREIGN KEY (`user_key`) REFERENCES `yo_customer_info` (`user_key`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '无预约现金贷——客户状态记录';

DROP TABLE IF EXISTS `yo_cumstom_log`;
CREATE TABLE `yo_cumstom_log` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_key` CHAR(32) NOT NULL COMMENT '用户唯一标识',
  `status` INT(3) NOT NULL  COMMENT '操作状态',
  `operator` VARCHAR(30) DEFAULT NULL COMMENT '操作者',
  `remark` TEXT DEFAULT NULL COMMENT '操作信息',
  `created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  CONSTRAINT `cumstom_log_UserKey` FOREIGN KEY (`user_key`) REFERENCES `yo_customer_info` (`user_key`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '无预约现金贷——客户操作记录';

-- 其他功能表
DROP TABLE IF EXISTS `yo_notices`;
CREATE TABLE `yo_notices` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `title` text COMMENT '标题（存html代码）',
  `content` text COMMENT '正文内容（存html代码）',
  `author` varchar(20) DEFAULT NULL COMMENT '发送人',
  `announcement_type` tinyint(4) DEFAULT NULL COMMENT '公告类型',
  `target_type` tinyint(4) DEFAULT NULL COMMENT '对象类型',
  `target_no` longtext COMMENT '对象集合',
  `status` tinyint(4) DEFAULT '1' COMMENT '状态：0-失效；1-有效',
  `read_users` longtext COMMENT '已阅读用户编号',
  `created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '无预约现金贷——公告信息';

DROP TABLE IF EXISTS `yo_customer_status`;
CREATE TABLE `yo_customer_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(10) NOT NULL COMMENT '客户状态码',
  `name` VARCHAR(20) DEFAULT NULL COMMENT '客户状态码对应名称',
  `created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` TIMESTAMP NULL DEFAULT NULL COMMENT '用于软删除记录时间，默认为空',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_unique` (`code`),
  UNIQUE KEY `name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '客户状态配置表';

DROP TABLE IF EXISTS `yo_customer_pic_type`;
CREATE TABLE `yo_customer_pic_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_code` TINYINT(1) NOT NULL COMMENT '类型代码',
  `type_name` VARCHAR(20) DEFAULT NULL COMMENT '类型代码对应名称',
  `created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` TIMESTAMP NULL DEFAULT NULL COMMENT '用于软删除记录时间，默认为空',
  PRIMARY KEY (`id`),
  UNIQUE KEY `type_code_unique` (`type_code`),
  UNIQUE KEY `type_name_unique` (`type_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '客户影像资料类型配置表';

-- 客户申请提现流程相关表
DROP TABLE IF EXISTS `yo_contract`;
CREATE TABLE `yo_contract` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_key` CHAR(32) NOT NULL COMMENT '用户唯一标识',
  `apply_amount` INT(11) COMMENT '申请金额',
  `order_no` varchar(20) DEFAULT NULL COMMENT '提单编号',
  `status` INT(4) DEFAULT NULL COMMENT '合同状态',
  `created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `user_key`(`user_key`),
  CONSTRAINT `contract_UserKey` FOREIGN KEY (`user_key`) REFERENCES `yo_customer_info` (`user_key`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '无预约现金贷——合同信息表';
