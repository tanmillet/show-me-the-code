<form class="ajax_form" action="index.php?m=<?php echo $_GET['m'];?>&p=add" method="post" style="float:left;width:100%">
	<table class="table table-bordered">
		<tr>
			<td>需求标题</td>
			<td><input type="text" name="data[title]" class="span4" /></td>
		</tr>
		
		<tr>
			<td>担当部门</td>
			<td>
			<select name="data[type]" style="width:300px;">
			<option value="">选择部门</option>
				<?php
					foreach(Config_Web::$dept_infos as $dept_id=>$dept){
						echo '<option value="'.$dept_id.'">'.$dept.'</option>';
					}
				?>
			</select>
			</td>
		</tr>
		
		<tr>
			<td>需求内容</td>
			<td width="90%">
			  	<textarea id="ueditor" name="data[content]"></textarea>
			</td>
		</tr>
		
<!--		<tr>-->
<!--			<td>希望完成时间</td>-->
<!--			<td>-->
<!--				<input type="text" class="span3" name="data[expectantstartdate]" value="--><?php //echo $start_time;?><!--" placeholder="开始时间" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"   /> - -->
<!--    			<input type="text" class="span3" name="data[expectantenddate]" value="--><?php //echo $end_time;?><!--" placeholder="结束时间" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"  />-->
<!--			</td>-->
<!--		</tr>-->
		
		<tr>
			<td>需求附件</td>
			<td width="90%">
				<input type="text" class="span7" name="data[datapath]" id="path" placeholder="填写文档网络地址，或者点击本地上传" />
				<a href="?m=common&p=upload&type=attachment&callback_id=thumb" class="show-form-modal btn" title="本地上传">本地上传</a>
                *最大上传文件2M
                <br />
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="submit" value=" 提 交 " class="btn btn-primary" />
				<input type="button" value=" 取 消 " class="btn" onclick="history.go(-1)" />
			</td>
		</tr>
	</table>
</form>
<script type="text/javascript">

$(".select2").select2();

function uploadCallBack(callback_id,path){
	$("#path").val(path);
}
UE.getEditor("ueditor",{elementPathEnabled : false,wordCount:false,initialFrameHeight:500});
</script>