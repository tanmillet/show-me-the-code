<form class="ajax_form" action="index.php?m=<?php echo $_GET['m'];?>&id=<?php echo $_GET['id']?>&p=edit&demandstatus=<?php echo $_GET['demandstatus'];?>&op=BJ&my=<?php echo $_GET['my']?>" method="post" style="float:left;width:100%">
    <input type="hidden" name="data[id]" value="<?php echo $_GET['id']?>">
    <input type="hidden" name="data[adminname]" value="<?php echo $data['adminname']?>">
    <input type="hidden" name="data[status]" value="<?php echo $data['status']?>">
	<table class="table table-bordered">
		<tr>
			<td>需求标题</td>
			<td><input type="text" name="data[title]" class="span4" value="<?php echo $data['title']?>"/></td>
		</tr>
		
		<tr>
			<td>担当部门</td>
			<td>
			<select name="data[type]" style="width:300px;">
			<option value="">选择部门</option>
                <?php
                foreach(Config_Web::$dept_infos as $dept_id=>$dept){
                    $selected = $data['type'] == $dept_id ? "selected" : "";
                    echo '<option value="'.$dept_id.'" '.$selected.'>'.$dept.'</option>';
                }
                ?>
            </select>
			</td>
		</tr>

        <tr>
            <td>需求优先级</td>
            <td>
                <input type="text" name="data[priority]" class="span4" value="<?php echo $data['priority'];?>" readonly/> 优先级级别说明：数字越小优先度越高。<label style="color: red;font-size: small;">注意：0 表示未设置优先度</label>
            </td>
        </tr>
		
		<tr>
			<td>需求内容</td>
			<td width="90%">
			  	<textarea id="ueditor" name="data[content]">
                    <?php echo $data['content']?>
                </textarea>
			</td>
		</tr>
		
		<tr>
			<td>希望完成时间</td>
			<td>
				<input type="text" class="span3" name="data[expectantstartdate]" value="<?php echo date("Y-m-d H:i:s",$data['expectantstartdate']);?>" placeholder="开始时间" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"   /> -
    			<input type="text" class="span3" name="data[expectantenddate]" value="<?php echo date("Y-m-d H:i:s",$data['expectantenddate']);?>" placeholder="结束时间" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"  />
			</td>
		</tr>
		
		<tr>
			<td>需求附件</td>
			<td width="90%">
				<input type="text" class="span7" name="data[datapath]" id="path" placeholder="填写文档网络地址，或者点击本地上传" value="<?php echo $data['datapath'];?>"/>
				<a href="?m=common&p=upload&type=attachment&callback_id=thumb" class="show-form-modal btn" title="本地上传">本地上传</a><br />
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
UE.getEditor("ueditor",{elementPathEnabled : false,wordCount:false,initialFrameHeight:400});
</script>