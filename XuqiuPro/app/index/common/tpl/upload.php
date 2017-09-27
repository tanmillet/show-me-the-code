<script src="/static/uploadify/jquery.uploadify.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="/static/uploadify/uploadify.css">
<form  action="" method="post" >
<table class="table table-bordered" style="float:left;">
<tr>
<td width="10%">本地文件</td>
<td width="90%"><input type="file" class="span7" name="upload" id="upload" /></td>
</tr>
<tr>
<td colspan="2">
<a class="btn btn-info"  href="javascript:$('#upload').uploadify('upload')">上 传</a>
<input type="button" value=" 取 消 " class="btn" onclick="$('.close').click()" />
</td>
</tr>
</table>
</form>
<script>
var callback_id = '<?php echo $_GET['callback_id'];?>';
$(function() {
	$('#upload').uploadify({
		'fileTypeExts' : '*.doc;*.txt;*.xls;*.rar;*.zip;*.jpg;*.jpeg;*.png;*.gif',
		'uploadLimit' : 1,
		'multi' : false,
		'auto' : false,
		'fileObjName' : 'upload',
		'swf'      : '/static/uploadify/uploadify.swf',
		'uploader' : '<?php echo Config_Common::$img_domain;?>api.php?m=upload&p=index&type=<?php echo $_GET['type'];?>&time=<?php echo $time;?>&sign=<?php echo $sign;?>&host=<?php echo $host;?>',
		'onUploadSuccess' : function(file, data, response) {
			data = $.parseJSON(data);
			if(data.upload.path){
				uploadCallBack(callback_id,data.upload.path);
	            $('.close').click();
			}else if(data.upload.error){
				alert(data.upload.error);
			}
	    }
	});
});
</script>