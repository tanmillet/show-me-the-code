function setCookie(name,value){
  document.cookie = name + "=" + value;
}
function getCookie(name){
	var c = document.cookie;
	var tmp = c.split(";");
	for(var i=0;i<tmp.length;i++){
		var s = tmp[i].split("=");
		if(s[0].replace(/[ ]/g,"") == name){
			return s[1];
		}
	}
}
function showlogin(data){
	$('#passport').html(data.username);
	serverhref = "";
	if(data.last_game_id && data.last_server_id){
		serverhref = platform_url + "/api.php?m=game&p=play&game_id="+data.last_game_id+"&server_id="+data.last_server_id;
		$("#lastservera").attr('href',"client://loadgame|" + serverhref).attr("game_id",data.last_game_id).attr("server_id",data.last_server_id);
	}else{
		$("#lastservera").attr('href',"javascript:alert('您还没登陆过游戏，请点击下面的区服进入');");
	}
	
	if(data.last_server_name){
		$("#lastserverli").html(data.last_server_name);
	}else{
		$("#lastserverli").html("暂无");
	}
	$('#logined').css({'display':'block'});
	$('#loginform').css({'display':'none'});
}
function extilogin(){
	$.get("index.php?m=user&p=logout","",function(ret){
		location.reload();
	});
}

function islogin(){
	var weburl = 'index.php';
	var str = $.ajax({
		type: "POST",
		url: weburl+"?m=user&p=info",
		async:false,
		cache:false,
		data: '',
		dataType: "json",
		success: function(data){}
	}).responseText;
	var jsonstr = jQuery.parseJSON(str);
	if (jsonstr.status == 1) {
		showlogin(jsonstr.data);
	}else{
		$("#logined").css({'display':'none'});
		$("#loginform").css({'display':'block'});
	}
}
function checkremember(){
	if( parseInt($("#remember").val()) == 1 ){
		$("#txt_ck").removeClass('is_t');
		$("#txt_ck").addClass('txt_ck');
		$("#remember").val('0');
	}else{
		$("#txt_ck").removeClass('txt_ck');
		$("#txt_ck").addClass('is_t');
		$("#remember").val('1');
	}
}
function flsubmit(){
    var username = $("#username").val();
    var password = $("#password").val();
    if(!username || !password){
    	alert("请填写登陆信息");
    }else{
    	$.post("index.php?m=user&p=login","username="+username+"&password="+password,function(ret){
    		
    	},"json");
    }
}
document.onkeydown = function(e){    
	noNumbers(e);
}  
function noNumbers(e){
	var keynum;
	var keychar;
	var numcheck;
	if(window.event){
		//keynum = e.keyCode;
	}else if(e.which){
		//keynum = e.which;
	}
if(keynum == 106){alert('a');}
}
$(document).ready(function(){
	if(getCookie("username")){
		$("#username").val(getCookie("username"));
	}
    //初始化,记住账号为空
    $('#remember').val('0');
    //var cc = getOs();
    //$("#testos").html($cc);
	islogin();
	//记住账号
	$("#txt_ck").click(function(){
		checkremember();
	});
	//登录按钮点击
	$("#login_bt").click(function(){
		$("#form_left").submit();return false;
	});
	$("#lastservera").click(function(){
		var game_id = $(this).attr("game_id");
		var server_id = $(this).attr("server_id");
		var url = $(this).attr("href");
		if(server_id && game_id){
			$.get("?m=client&p=check","type=server&game_id="+game_id+"&server_id="+server_id,function(ret){
				if(ret.ok){
					window.location.href = url;
				}else{
					alert(ret.tip);
				}
			},"json");
		}
		return false;
	});
	//表单提交方法
	$("#form_left").submit(function(){
		var username = $("#username").val();
	    var password = $("#password").val();
	    if(!username || !password){
	    	alert("请填写登陆信息");
	    }else{
	    	if( parseInt($("#remember").val()) == 1 ){
	    		setCookie("username",$("#username").val());
	    	}else{
	    		setCookie("username","");
	    	}
	    	$.post("index.php?m=user&p=login","username="+username+"&password="+password,function(ret){
	    		if(ret.ok){
	    			location.reload();
	    		}else{
	    			alert(ret.tip);
	    		}
	    	},"json");
	    }
		return false;
	});
	//注销
	$("#exitlogin").click(function(){
		extilogin();return false;
	});
	//刷新
	$("#refrash").click(function(){
		location.reload();return false;
	});
	$("#slist li").click(function(){
		var game_id = $(this).attr("game_id");
		var server_id = $(this).attr("server_id");
		var uhref = $(this).attr('uhref');
		$.get("index.php?m=user&p=info","",function(ret){
			if(!ret.status){
				alert("登陆失效，请重新登陆");
				location.reload();
			}else{
				$.get("?m=client&p=check","type=server&game_id="+game_id+"&server_id="+server_id,function(ret){
					if(ret.ok){
						window.location.href = "client://loadgame|" + uhref;
					}else{
						alert(ret.tip);
					}
				},"json");
				
			}
		},"json");
	});
});
