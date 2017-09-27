//安全级别数组的赋值
function evaluate(j, b) {
    this.j = j;
    this.b = b;
}
//检查字符的安全级别，分四类
function checkSecLevel(c, j) {
    var b = false;
    switch (j) {
    case 0:
        if ((c >= 'A') && (c <= 'Z')) b = true;
        break;
    case 1:
        if ((c >= 'a') && (c <= 'z')) b = true;
        break;
    case 2:
        if ((c >= '0') && (c <= '9')) b = true;
        break;
    case 3:
        if ("!@#$%^&*()_+-='\";:[{]}\|.>,</?`~".indexOf(c) >= 0) b = true;
        break;
    case 4:
        if (checkSecLevel(c, 0) || checkSecLevel(c, 1)) b = true;
        break;
    default:
        break;
    }
    return b;
};
//检查字符串的长度
function checkStringLen(e, g) {
    if ((e == null) || isNaN(g)) {
        return false;
    } else if (e.length < g) {
        return false;
    }
    return true;
}

function checkStringLevel(e, f) {
    var i = 0;
    var jj = new Array(new evaluate(0, false), new evaluate(1, false), new evaluate(2, false), new evaluate(3, false));
    if ((e == null) || isNaN(f)) return false;
    for (var k = 0; k < e.length; k++) {
        for (var d = 0; d < jj.length; d++) {
            if (!jj[d].b && checkSecLevel(e.charAt(k), jj[d].j)) {
                jj[d].b = true;
                break;
            }
        }
    }
    for (var d = 0; d < jj.length; d++) {
        if (jj[d].b) i++;
    }
    if (i < f) return false;
    return true;
}
function checkSign(h) {
    return (checkStringLen(h, "10") && checkStringLevel(h, "4"));
}
function checkNum(h) {
    return (checkStringLen(h, "8") && checkStringLevel(h, "3"));
}
function checkChar(h) {
    return (checkStringLen(h, "6") && checkStringLevel(h, "2"));
}

//检查密码强弱
function checkPasslevel(o) {
    if (o == "") {
        $("#pwlevel").attr("class", "Strength1");
        return;
    }
    if (checkSign(o)) {
        $("#pwlevel").attr("class", "Strength3");
    } else if (checkNum(o)) {
        $("#pwlevel").attr("class", "Strength3");
    } else if (checkChar(o)) {
        $("#pwlevel").attr("class", "Strength2");
    } else {
        $("#pwlevel").attr("class", "Strength1");
    }
}

function is_username(s) {
    return /^[\w\@\.\_\-]{1,32}$/i.test(s);
}
function is_password(s) {
    return /^.{6,32}$/i.test(s);
}
function is_mobile(s) {
    return /^1[358]\d{9}$/.test(s);
}
function is_email(s) {
    return /([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)/.test(s);
}
function is_idcard(card) {
    card = card.toUpperCase();
    if (card == checkIDCard(card)) {
        return true;
    }
    return false;
}

function is_truename(s) {
    return /^[\u4e00-\u9fa5]{2,5}$/i.test(s);
}

function isChinese(s) {
    var re = /[^\u4e00-\u9fa5]/;
    if (re.test(s)) return false;
    return true;
}
//检验身份证号码
function checkIDCard(num) {
    var aCity = {
        11 : "北京",
        12 : "天津",
        13 : "河北",
        14 : "山西",
        15 : "内蒙古",
        21 : "辽宁",
        22 : "吉林",
        23 : "黑龙江 ",
        31 : "上海",
        32 : "江苏",
        33 : "浙江",
        34 : "安徽",
        35 : "福建",
        36 : "江西",
        37 : "山东",
        41 : "河南",
        42 : "湖北 ",
        43 : "湖南",
        44 : "广东",
        45 : "广西",
        46 : "海南",
        50 : "重庆",
        51 : "四川",
        52 : "贵州",
        53 : "云南",
        54 : "西藏 ",
        61 : "陕西",
        62 : "甘肃",
        63 : "青海",
        64 : "宁夏",
        65 : "新疆",
        71 : "台湾",
        81 : "香港",
        82 : "澳门",
        91 : "国外 "
    }
    num = num.toUpperCase();
    //身份证号码为15位或者18位，15位时全为数字，18位前17位为数字，最后一位是校验位，可能为数字或字符X。   
    if (! (/(^\d{15}$)|(^\d{17}([0-9]|X)$)/.test(num))) {
        return "输入的身份证号长度不对，或者号码不符合规定！";
    }
    if (aCity[parseInt(num.substr(0, 2))] == null) return "非法地区";
    var len, re;
    len = num.length;
    if (len == 15) {
        re = new RegExp(/^(\d{6})(\d{2})(\d{2})(\d{2})(\d{3})$/);
        var arrSplit = num.match(re);
        var dtmBirth = new Date('19' + arrSplit[2] + '/' + arrSplit[3] + '/' + arrSplit[4]);
        var bGoodDay;
        bGoodDay = (dtmBirth.getYear() == Number(arrSplit[2])) && ((dtmBirth.getMonth() + 1) == Number(arrSplit[3])) && (dtmBirth.getDate() == Number(arrSplit[4]));
        if (!bGoodDay) {
            return "输入的身份证号里出生日期不对！";
        } else {
            /**
						var arrInt = new Array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2); 
						var arrCh = new Array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'); 
						var nTemp = 0, i;   
						num = num.substr(0, 6) + '19' + num.substr(6, num.length - 6); 
						for(i = 0; i < 14; i ++) { 
							nTemp += num.substr(i, 1) * arrInt[i]; 
						} 
						num += arrCh[nTemp % 11];
						*/
            return num;
        }
    }
    if (len == 18) {
        re = new RegExp(/^(\d{6})(\d{4})(\d{2})(\d{2})(\d{3})([0-9]|X)$/);
        var arrSplit = num.match(re);
        var dtmBirth = new Date(arrSplit[2] + "/" + arrSplit[3] + "/" + arrSplit[4]);
        var bGoodDay;
        bGoodDay = (dtmBirth.getFullYear() == Number(arrSplit[2])) && ((dtmBirth.getMonth() + 1) == Number(arrSplit[3])) && (dtmBirth.getDate() == Number(arrSplit[4]));
        if (!bGoodDay) {
            return "输入的身份证号里出生日期不对！";
        } else {
            var valnum;
            var arrInt = new Array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
            var arrCh = new Array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
            var nTemp = 0,
            i;
            for (i = 0; i < 17; i++) {
                nTemp += num.substr(i, 1) * arrInt[i];
            }
            valnum = arrCh[nTemp % 11];
            if (valnum != num.substr(17, 1)) {
                return "18位身份证的校验码不正确！";
            }
            return num;
        }
    }
    return false;
}

function checkInput(event, callback, except) {
    var src_el = window.event ? event.srcElement: event.target;
    var keycode = window.event ? event.keyCode: event.which;
    if (is_ctrl(keycode)) return keycode;
    if (callback(keycode)) return keycode;
    if (except) {
        for (i = 0; i < except.length; ++i) {
            if (keycode == except.charCodeAt(i)) return keycode;
        }
    }
    preventEvent(event);
}
function fleshVerify() {
    //重载验证码
    var timenow = new Date().getTime();
    document.getElementById('verifyImg').src = 'index.php?m=common&p=verify&' + timenow;
}
function __error(el, msg) {
    $(el + "_tip").attr("class", "msgx msgC").html(msg);
    $(el).attr("error", "trur");
	return false;
}
function __success(el, msg) {
    $(el + "_tip").attr("class", "msgx msgB").html(msg);
    $(el).removeAttr('error')
}
// 检查用户名是否可用
function checkUsername() {
    var uname = $('#username').val();
    if (uname == "") {
        __error('#username', "请输入3595通行证。");
    } else if (uname.length < 3 || uname.length >16) {
        __error('#username', "通行证长度为3到16位。");
    } else if (!is_username(uname)) {
        __error('#username', "通行证只能是3-15位字母、数字、符号  _@-.  组成");
    } else {
        $.post('index.php?m=user&p=check&type=username', {
            'username': $('#username').val()
        },
        function(data) {
            if (data) {
                if (data.ok == 1) {
                    __success("#username", '&nbsp;');
                } else {
                    __error("#username", data.tip);
                    //$("#username").focus();
                }
            }

        },
        'json');
    }
}
//检查密码
function checkPassword(obj) {

    var password = $(obj).val();

    if (password == "") {
        __error("#" + obj.id, "请输入您的密码。");
    } else if (!is_password(password)) {
        __error("#" + obj.id, "请输入6-32位字符，区分大小写。");
    } else if (/\s/.test(password)) {
        __error("#" + obj.id, "密码格式不正确，不支持空格符号作为密码。");
    } else {
        __success("#" + obj.id, '&nbsp;');
    };
}

//检查二次输入密码
function checkRepassword(obj) {

    var password = $("#password").val();
    var repassword = $(obj).val();

    if (password == "") {
        __error("#repassword", "请先输入密码。");
    } else {
        if (password == repassword) {
            __success("#" + obj.id, '&nbsp;');
        } else {
            __error("#" + obj.id, "您两次输入的密码不正确。");
        }
    }
}
// 检查邮箱是否可用
function checkEmail(obj) {
    var id = $(obj).attr('id');
    var s = $(obj).val();
    if (s == "") {
        __error("#" + id, "请您输入邮箱。");
    } else if (!is_email(s)) {
        __error("#" + id, "您的邮箱格式格式不正确。");
    } else if (s.length > 50) {
        __error("#" + id, "邮箱长度只能在50位之内。");
    } else {
	
        $.post('index.php?m=user&p=check&type=email', {
            'email': s
        },
        function(data) {
            if (data) {
                if (data.ok) {
                    __success("#" + id, '&nbsp;');
                } else {
                    __error("#" + id, data.tip);
                }
            }
        },
        'json');
    }
}
//检查手机号
function checkMobile(obj) {
    var s = $(obj).val();
    if (s == "") {
        //alert($(obj).attr('id'));
        if ($(obj).attr('id') == "phone") {
            __error("#" + obj.id, "请您输入手机号。");
        } else {
            $("#" + obj.id + "_tip").attr("class", "msgx msgE").html('请输入手机号');
            $("#" + obj.id).removeAttr('error'); return;
        }
        //
    } else if (!is_mobile(s)) {
        __error("#" + obj.id, "您的手机格式格式不正确。");
    } else {
        $.post('/Register/checkPhone', {
            'tellphone': s
        },
        function(data) {
            if (data) {
                if (data.status == 1) {
                    __success("#" + obj.id, '&nbsp;');
                } else {
                    __error("#" + obj.id, data.info);
                }
            }
        },
        'json');
    }
}
//检查验证码
function checkCheck(obj) {
    var code = $(obj).val();
    if (code == "") {
        __error("#" + obj.id, "请输入验证码");
    } else if (code.length < 4){
		__error("#" + obj.id, "请输入四位验证码");
    }else{
		$.post('index.php?m=user&p=check&type=verify', {
			'verify':code
        },
        function(data) {
			if (data.ok) {
				__success("#"+ obj.id, '&nbsp;');
			} else {
				__error("#"+ obj.id, data.tip);
			}

        },
        'json');
	}
}
//检查姓名
function checkTruename(obj) {
    var s = $(obj).val();
    if (s == "") {
        __error("#" + obj.id, "请输入姓名");
    } else if (!is_truename(s)) {
        __error("#" + obj.id, "您的姓名格式不正确。");
    } else {

        __success("#" + obj.id, '&nbsp;');
    }
}
//检查身份证号
function checkIDCardS(obj) {
    var s = $(obj).val();
    if (s == "") {
        __error("#" + obj.id, "请输入身份证号");
    } else if (!is_idcard(s)) {
        __error("#" + obj.id, "身份证号不正确。");
    } else {
        $.post('index.php?m=user&p=check&type=idcard', {
            'idcard': $('#idcard').val()
        },
        function(data) {
            if (data) {
                if (data.ok) {
                    __success("#" + obj.id, '&nbsp;');
                } else {
                    __error("#" + obj.id, data.tip);
                }
            }
        },
        'json');
    }
}

// 检查常用邮箱是否可用
function checkCommonEmail() {
    $.post('/Register/checkEmail', {
        'email': $('#cm').val()
    },
    function(data) {
        if (data) {
            alert(data.info);
            $("#cm").focus();
        }
    },
    'json');
}

// 检查身份证是否可用
function checkIdcard() {
    $.post('/Register/checkIdcard', {
        'idcard': $('#idcard').val()
    },
    function(data) {
        if (data) {
            alert(data.info);
            $("#idcard").focus();
        }

    },
    'json');
}
function doSubmit(form_id){
	if ($("input[error]").size() != 0) {
        return;
    } else if ($("#agree:checked").size() === 0) {
        return;
    }else{
		var param = $("#"+form_id).serialize();
		$.post("index.php?m=user&p=reg",param,function(re){
			if(!re.ok){
				alert(re.tip);
			}else if(re.jump){
				location.href = re.jump;
			}
		},"json");
    }
}
$(document).ready(function() {
    $("#jiazhang").click(function() {
        if (this.checked) {
            $(".tc").fadeIn("slow");
            //alert($("#pt").attr('checked'));
            if ($("#pt").attr('checked')) {
                $("#commonphone").hide();
            } else {
                $("#commonphone").fadeIn("slow");
            }
        } else {
            $(".tc").fadeOut("slow");
        }
    });
    $("#password").blur(function() {
        checkPassword(this);
    });
    $("#repassword").blur(function() {
        checkRepassword(this);
    });
    $("#password").keyup(function() {
        checkPasslevel(this.value);
    });
    $("#email").blur(function() {
        checkEmail(this);
    });
    $("#phone").blur(function() {
        checkMobile(this);
    });
    $("#verify").blur(function() {
        checkCheck(this);
    });
    $("#truename").blur(function() {
        checkTruename(this);
    });
    $("#idcard").blur(function() {
        checkIDCardS(this);
    });
    $("#ut").click(function() {
        $("#usernameli").fadeIn("slow");
        $("#commonemail").fadeIn("slow");
        $("#phoneli").hide();
        $("#emailli").hide();

        $("#commonphone").fadeIn("slow");

        $("#email").removeAttr('error')

    });

    $("#pt").click(function() {
        $("#phoneli").fadeIn("slow");
        $("#commonemail").fadeIn("slow");
        $("#usernameli").hide();
        $("#emailli").hide();
        $("#commonphone").hide();
        $("#email").removeAttr('error'); $("#commonphone").removeAttr('error')
    });

    $("#et").click(function() {
        $("#emailli").fadeIn("slow");
        $("#phoneli").hide();
        $("#commonphone").fadeIn("slow");

        $("#email").removeAttr('error'); $("#usernameli").hide();
        $("#commonemail").hide();
        $("#username").removeAttr('error'); $("#phone").removeAttr('error')
    });
});
