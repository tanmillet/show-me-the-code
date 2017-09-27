//window.UEDITOR_HOME_URL = "http://img.3595.com/static/ueditor/";
$(document).ready(function(){
	$(".show-form-modal").on("click",function(){
		var remote = $(this).attr("href");
		var width = $(this).attr("data-width") ? $(this).attr("data-width") : 800;
		var height = $(this).attr("data-height") ? $(this).attr("data-height") : 800;
		var title = $(this).attr("title") ? $(this).attr("title") : 'Ajax Form';
		$("#myModal").remove();
		createFormModal(width,height,title);
		$("#myModal .modal-body").load(remote);
		$('#myModal').modal({show:true});
		return false;
	});
	dataTable();
	$(".select2").each(function(){
		$(this).select2();
	});
});
function createFormModal(width,height,title){
	var html = '<div id="myModal" class="modal hide fade" style="max-height:750px;overflow:auto;width:'+width+'px;margin-left:-'+(width/2)+'px" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="myModalLabel">'+title+'</h3></div><div class="modal-body" style="max-height:'+height+'px"></div></div>';
	$("body").append(html);
}
function showTip(msg,showtime,fadetime){
	var showtime = showtime ? showtime : 2000;
	var fadetime = fadetime ? fadetime : 1000;
	var scrollTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;
	var clientHeight = document.compatMode == "CSS1Compat" ? document.documentElement.clientHeight :   document.body.clientHeight;
	var top = clientHeight / 2 + scrollTop;
	if($(".tip_wrap").length){
		$(".tip_wrap").remove();
	}
	if(!msg){
		$("body").append('<div class="tip_wrap"><div class="tip_container"><div class="ball_big"></div><div class="ball_small"></div></div></div>');
	}else{
		$("body").append('<div class="tip_wrap"><div class="tip_container"><div class="tip_c">'+msg+'</div></div></div>');
	}
	$(".tip_wrap").css("top",top+"px").show();
	if(msg){
		setTimeout(function(){hideTip(fadetime)},showtime);
	}
}
function hideTip(time){
	$(".tip_wrap").fadeOut(time);
}
function dataTable(select){
	select = select ? select : '.dataTable';
	$(select).DataTable({
		bDestroy: true,
		lengthMenu: [[500, 25, 50, -1], [500, 25, 50, "All"]],
        bPaginate: false, //是否分页
        dom: 'T<"clear">lfrtip',
        bInfo : false,
        oTableTools: {
           "sSwfPath": "/static/DataTables/extensions/TableTools/swf/copy_csv_xls_pdf.swf"
       }
	});
}