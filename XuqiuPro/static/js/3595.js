var textRoll=function(){
	$('#movetext li:last').css({'height':'0px','opacity': '0'}).insertBefore('#movetext li:first').animate({'height':'35px','opacity': '1'}, 'slow', function() {
 $(this).removeAttr('style');
});
}
function lislider(i,ied){
	$("#dotboxul li").children().eq(ied).removeClass('current');
	$("#dotboxul li").children().eq(i).addClass('current');
}
function imgslider(i,ied){
	//$(".slide").stop(true,true);
	if( ied == 'all'){
		$(".slide").css('display','none');
		$(".slide").eq(i).css('display','block');
	}else{
		$(".slide").eq(ied).fadeOut(1000);
		$(".slide").eq(i).fadeIn(1000);
	}
}
$(function (){
// 屏蔽ie6下的fadeIn 和 fadeOut
	var old_fade_in = $.fn.fadeIn;
	var old_fade_out = $.fn.fadeOut;
	$.fn.fadeIn = function(){
		if ($.browser.msie && ($.browser.version == "6.0") && (!$.support.style)) {
			$(this).show();
			if (typeof arguments[arguments.length-1] === 'function'){
				arguments[arguments.length-1]();
			}
		}else{
			old_fade_in.call( this, arguments[0], arguments[1], arguments[2] );
		}
		return this;
	}
	$.fn.fadeOut = function(){
		if ($.browser.msie && ($.browser.version == "6.0") && (!$.support.style)) {
			$(this).hide();
			if (typeof arguments[arguments.length-1] === 'function'){
				arguments[arguments.length-1]();
			}
		}else{
			old_fade_out.call(this, arguments[0], arguments[1], arguments[2] );
		}
		return this;
	}
});
$(document).ready(function () {
	//alert('222');
	//$("#onloada").trigger('click');
	//$("#onloada").click(function(){
	//	alert('a');
		//getUser();
	//});
	getUser();
	imgslider(0,'all');
	var numbers = $(".dotbox").children().length;
	$(".dotbox a").click(function(){
		var i = $(this).index();
		var ied = $(this).parent().children(".current").index();
		lislider(i,ied);
		imgslider(i,ied);
		return false;
	});
	//自动轮播图片
	var nowimg = 0;//初始显示图片
	var imgnum = $(".dotbox a").length;
	$.imgsl = function(){
		//alert($(".dotbox a").index());
		$(".dotbox a").each(function(index){
			if(index == nowimg){
				$(this).trigger('click');
			}
		});
		nowimg++;
		if(nowimg > (imgnum - 1)){
			nowimg = 0;
		}
		imgtime = setTimeout($.imgsl,5000);
	}
	$.imgsl();
	var imgcheck = 0;
	$(".imgnav").hover(function(){
		clearTimeout(imgtime);
		if(imgcheck == 1){
			clearTimeout(hoveroff);
			imgcheck = 0;
		}
	},function(){
		hoveroff = setTimeout($.imgsl,5000);
		if(imgcheck == 0){
			imgcheck = 1;
		}
	});
	//论坛临时处理start
    /*
	$("#tobbs").click(function(){
		alert('论坛施工维护中。。。\n\n\n联系加群 385034135');return false;
	});
    
	$("#BBSslides a").click(function(){
		return false;
	});
	$(".bbscon a").click(function(){
		return false;
	});
	$(".footercon4 a").click(function(){
		return false;
	});
	$("#tobbs").attr('href','');
	$("#BBSslides a").attr('href','');
	$(".bbscon a").attr('href','');
	$(".footercon4 a").attr('href','');
    */
	//论坛临时处理end
	//点击游戏区服时自动登录该游戏网站
	/*
	$('.switchgame').click(function(){
		var gid = $(this).attr('gvalue');
		var str = $.ajax({
			type: "POST",
			url: "/GamePlay/switchname",
			async:false,
			cache:false,
			data: "gid="+gid,
			dataType: "json",
			success: function(data){}
		}).responseText;
		var jsonstr = $.parseJSON(str);
		if (jsonstr.status != 1) {
			switch (jsonstr.info){
				case "未登录":alert(jsonstr.info);break;
				case "网络异常请重试":alert(jsonstr.info);break;
				default:alert(jsonstr.info);
			}
			return false;
		}else{
			//成功验证后，根据gid登录到游戏官网

			return true;
		}
	});
	*/
	//去除a标签点击的虚线框
	$(".menubg a").focus(function(){
		$(this).blur();
	});
	//首页导航图片切换
	$('#navslides').slides({
		preload: true,
		effect: 'fade',
		generateNextPrev: false,
		play: 4000 
	});
	//用户登录页面的登录和注册点击
	$(".regnow").click(function(){
		window.location.href = $(this).attr('uhref');
	});
	$(".loginnow").click(function(){
		$("#log_form").submit();return false;
	});
	$("#log_form").submit(function(){
		var str = $.ajax({
			type: "POST",
			url: "/IframeLogin/AjaxLogin",
			async:false,
			cache:false,
			data: {'username': $('#li3input').val(),'password': $('#li4input').val()},
			dataType: "json",
			success: function(data){}
		}).responseText;
		var jsonstr = $.parseJSON(str);
		if (jsonstr.status == 1) {
			return true;
		}else{
			alert(jsonstr.info);
			return false;
		}
	});
	
	//首页论坛图片切换
	$('#BBSslides').slides({
		play:5000,
		preload: true,
		generateNextPrev: false,
		animationStart: function(){
			$(".shadow").animate({backgroundColor:"#000"}, 60);
		},
		animationComplete: function(){
			$(".shadow").animate({backgroundColor:"#FFF"}, 60);
		},
		slidesLoaded: function() {}
	});
	$(".menubg a").hover(function(){
		$(this).addClass("navhover");
	},function(){
		$(this).removeClass("navhover");
	});
	
	$(".lane_dd a").hover(function(){
		var classname = $(this).attr("hvalue");
		$(this).addClass(classname+'_hover');
	},function(){
		var classname = $(this).attr("hvalue");
		$(this).removeClass(classname+'_hover');
	});	
	$(".user").hover(function(){
		$(this).addClass('showlogin');
	},function(){
		$(this).removeClass('showlogin');
	});
	$(".allgame").hover(function(){
		$(this).addClass('allgamehover');
	},function(){
		$(this).removeClass('allgamehover');
	});
	//登录框的弹出和关闭
	$("#login_a").click(function(){
		var docheight = $(document).height();
		var windowWidth = $(window).width();
		var formWidth = $("#webloginbox").width();
		var leftpx = (parseInt(windowWidth) - parseInt(formWidth))/2;
		$("#loginmask").css({"height":docheight,"display":'block'});
		$("#webloginbox").css({"display":'block',"left":leftpx+"px"});
	});
	$(document).keyup(function(e){
        var key =  e.which;
        if(key == 27){closeLoginForm();}
    });
	$('.closeLF').click(function(){
		closeLoginForm();
	});
	//文字移动
	var roll=setInterval('textRoll()',3000);
	$("#movetext li").hover(function() {
		clearInterval(roll);
	}, function() {
		roll = setInterval('textRoll()', 3000)
    });
	$('.logininfo li a').hover(function(){
		var classname = $(this).parent().attr('id');
		var hovername = classname+"_hover";
		$(this).parent().removeClass(classname);
		$(this).parent().addClass(hovername);
	},function(){
		var classname = $(this).parent().attr('id');
		var hovername = classname+"_hover";
		$(this).parent().removeClass(hovername);
		$(this).parent().addClass(classname);
	});
	//用户中心登录边框
	$(".li3input").focus(function(){
		$(this).parent().removeClass("blurborder");
		$(this).parent().addClass("focusborder");
	});
	$(".li3input").blur(function(){
		$(this).parent().removeClass("focusborder");
		$(this).parent().addClass("blurborder");
	});
	$(".li4input").focus(function(){
		$(this).parent().removeClass("blurborder");
		$(this).parent().addClass("focusborder");
	});
	$(".li4input").blur(function(){
		$(this).parent().removeClass("focusborder");
		$(this).parent().addClass("blurborder");
	});
	//登录复选框
	$("#li5span").click(function(){
		var cname = $(this).attr("class");
		if( cname == "spannocheck"){
			$("#leftform #remember").val("1");
			$(this).removeClass("spannocheck");
			$(this).addClass("spanchecked");
		}else{
			$("#leftform #remember").val("0");
			$(this).removeClass("spanchecked");
			$(this).addClass("spannocheck");
		}
	});
	//记住密码登录
	$("#jizhu").click(function(){
					  if(this.checked){
						$("#remember").val("1");
						alert("慎用！您的用户信息将在本机保存7天");
					  }else{
						$("#remember").val("0");
					  }
					});
    $('.slist_a').click(function(){
        var serverstatus = $(this).attr('status');
        if( serverstatus == -1){
            alert('服务器正在维护,详情请查询官网公告');return false;
        }else if( serverstatus == 0){
            alert('服务器暂未开启,详情请查询官网公告');return false;
        }else{
		    var str = $.ajax({
			    type: "POST",
			    url: "/Getinfo/checklogin",
			    async:false,
			    cache:false,
			    data: {},
			    dataType: "json",
			    success: function(data){}
		    }).responseText;
		    var jsonstr = $.parseJSON(str);
            if(jsonstr.status != 1){
                alert('未登录');return false;
            }else{
            }
        }
    });
	
});
function checklogin(){
    $.ajax({
        type:'get',
        url:"/Getinfo/checklogin",
        async:false,
        cache:false,
        dataType:"json",
        success:function(data){
            if(data.status != 1){
                alert('未登录');return false;
            }else{
                return true;
            }
        }
    });
}
function login(){
	var username = $("[name=username]").val();
	var password = $("[name=password]").val();
	if(username.length<3 || username.length>16){
		alert("用户名长度不小于3或者大于16");
		return false;
	}else if(password.length<6 ||password.length>16){
		alert("密码长度不小于6或者大于16");
		return false;
	}else{
		$.post("index.php?m=user&p=login","username="+username+"&password="+password,function(ret){
			if(ret.ok){
				var jump = ret.jump ? ret.jump : "index.php";
				location.href = jump;
			}else{
				alert(ret.tip);
			}
		},"json");
	}
}
function getUser(){
	var hashkey = '';
	$.ajax({
		type: "get",
		url: "index.php?m=user&p=info",
		async:true,
		cache:false,
		dataType: "json",
		success: function(data){
			if(data.status == 1 ){
				showtoplogin();
				shownavlogined();
				$("#navuser").html(data.data.username);
				$('.uname').attr('title',data.data.username);
				$('.uname').attr('alt',data.data.username);
				$('.uname').html(data.data.username);
				$("#last_login_time").text(data.data.last_login_time);
				$("#last_login_ip").text(data.data.last_login_ip);
				message_num();
			}else{
				showtopnologin();
				shownavnologin();
			}
		}
	});
}
function message_num(){
	$.get("index.php?m=ajax&p=message_num","",function(ret){
		ret.num = ret.num ? ret.num : 0;
		$('.usermessage a').html('消息('+ret.num+')');
	},"json");
}
function shownavnologin(){
	$("#navnologin").css({'display':'block'});
	$("#navlogined").css({'display':'none'});
}
function shownavlogined(){
	$("#navnologin").css({'display':'none'});
	$("#navlogined").css({'display':'block'});
	$(".login_on").show();
}
function showtoplogin(){
	$('.userdiv').css({'display':'block'});
	$('.nologin').css({'display':'none'});
}
function showtopnologin(){
	$('.userdiv').css({'display':'none'});
	$('.nologin').css({'display':'block'});
}
function onCheck(){
	$(".MobileCode").css({"display":"none"});
	$(".MobileCodeSuc").css({"display":"inline-block"});
	$(".clockbox").css({"display":"inline"});
}
function noCheck(){
	$(".MobileCode").css({"display":"inline-block"});
	$(".MobileCodeSuc").css({"display":"none"});
	$(".clockbox").css({"display":"none"});
}
function clock(){
	var date = parseInt($(".clockspan").html()) - 1;
		if(date != 0){
			$(".clockspan").html(date);
		}else{
			clearInterval(ints);
		}
}

function closeLoginForm(){
	if( $("#webloginbox").css("display") == "block" ){
	$("#loginmask").css({"display":'none'});
	$("#webloginbox").css({"display":'none'});
	}
}
function AddFavorite(sURL, sTitle) {   
    try {   
        window.external.addFavorite(sURL, sTitle);   
    } catch (e) {   
        try {   
            window.sidebar.addPanel(sTitle, sURL, "");   
        } catch (e) {   
            alert("加入收藏失败，请使用Ctrl+D进行添加");   
        }   
    }   
}     
  
function SetHome(obj, vrl) {   
    try {   
        obj.style.behavior = 'url(#default#homepage)';   
        obj.setHomePage(vrl);   
    } catch (e) {   
        if (window.netscape) {   
            try {   
                netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");   
            } catch (e) {   
                alert("此操作被浏览器拒绝！\n请在浏览器地址栏输入“about:config”并回车\n然后将 [signed.applets.codebase_principal_support]的值设置为'true',双击即可。");   
            }   
            var prefs = Components.classes['@mozilla.org/preferences-service;1'].getService(Components.interfaces.nsIPrefBranch);   
            prefs.setCharPref('browser.startup.homepage', vrl);   
        }   
    }   
}
function toDesktop(sUrl,sName){
try {
    var WshShell = new ActiveXObject("WScript.Shell");
    var oUrlLink = WshShell.CreateShortcut(WshShell.SpecialFolders("Desktop") + "\\" + sName + ".url");
    oUrlLink.TargetPath = sUrl;
    oUrlLink.Save();
} catch(e){ alert("当前安全级别不允许操作！"); }
} 
