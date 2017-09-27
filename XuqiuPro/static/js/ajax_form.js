$(document).ready(function(){
	set_ajax_form();
	var ac = "ajax_del";
	$("."+ac).off("click").on("click",function(){
		var obj = $(this);
		var url = $(obj).attr("href");
		if(window.confirm("确定要执行吗？")){
			get(url,obj);
		}
		return false;
	});
	var ac = "ajax_get";
	$("."+ac).each(function(){
		var url = $(this).attr("href");
		$(this).attr("onclick","get('"+url+"',this);return false;");
	});
});
function set_ajax_form(){
	$(".ajax_form").each(function(i,obj){
		var form_id = "ajax_form_"+i;
		$(obj).attr("id",form_id);
		var url = $(obj).attr("action");
		var callback = $(obj).attr("callback") ? $(obj).attr("callback") : "";
		$(obj).attr("action","javascript:doSubmit('"+form_id+"','"+url+"','"+callback+"')");
	});
}
function doSubmit(form_id,url,callback){
	showProcess(form_id,"请稍候...");
	var param = $("#"+form_id).serialize();
	$.post(url,param,function(re){
		if(!re.success){
			if(re.jump){
				location.href = re.jump;
			}else{
				showError(form_id, re.error);
			}
		}else if(re.jump){
			showSuccess(form_id,"正在跳转...");
			location.href = re.jump;
		}else{
			showSuccess(form_id,"提交成功");
			if(callback){
				eval(callback+"(re);");
			}
		}
	},"json");
}
function showError(form_id, msg){
	$(".form-tip").hide();
	if(!$("#error_tip").length){
		$("#"+form_id).append('<div id="error_tip" class="form-tip alert alert-error" style="margin-top:10px;"><span class="badge badge-important" style="margin-right:10px">Ops!</span><span></span></div>');
	}
	$("#error_tip").show().children().eq(1).text(msg);
}
function showProcess(form_id, msg){
	$(".form-tip").hide();
	if(!$("#process_tip").length){
		$("#"+form_id).append('<div id="process_tip" class="form-tip alert alert-info" style="margin-top:10px;"><span class="badge badge-info" style="margin-right:10px">Olala!</span><span></span></div>');
	}
	$("#process_tip").show().children().eq(1).text(msg);
}
function showSuccess(form_id, msg){
	$(".form-tip").hide();
	if(!$("#success_tip").length){
		$("#"+form_id).append('<div id="success_tip" class="form-tip alert alert-info" style="margin-top:10px;"><span class="badge badge-success" style="margin-right:10px">Oye！</span><span></span></div>');
	}
	$("#success_tip").show().children().eq(1).text(msg);
}
function get(url,obj){
	if($(obj).attr("disabled")){
		return;
	}
	showTip();
	$(obj).attr("disabled","disabled");
	$.get(url,"",function(re){
		hideTip();
		$(obj).removeAttr("disabled");
		if(re.success){
			if(re.msg){
				showTip(re.msg);
			}else{
				showTip("操作成功");
			}
			if(re.jump){
				location.href = re.jump;
			}else if(re.remove){
				$(obj).parents(re.remove).remove();
			}else if(re.html && re.parent){
				$(obj).parents(re.parent).after(re.html);
			}else if(!re.noreload){
				location.reload();
			}
		}else if(re.jump){
			location.href = re.jump;
		}else{
			alert(re.error);
		}
	},"json");
}