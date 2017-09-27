//单点登陆
var _sso = {
	get_cookie : function (name){
		var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
	    if(arr = document.cookie.match(reg)){
	        return unescape(arr[2]);
	    }else{
	        return "";
	    }
	},
	login_notify : function (){
		var sso_url = this.get_cookie("sso_url");
		var project_id = this.get_cookie("sso_project_id");
		$.getJSON(sso_url + "index.php?m=index&p=get_login_notify_url&project_id="+project_id+"&callback=?",function(ret){
			if(ret.login_notify_url){
				$.each(ret.login_notify_url,function(){
					$.ajax({
						type : "get",
						async : true,
						url : this.url,
						dataType : "jsonp",
						data : {"ticket":this.ticket},
						success : function(r){},
					});
				});
			}
		});
	},
};
_sso.login_notify();