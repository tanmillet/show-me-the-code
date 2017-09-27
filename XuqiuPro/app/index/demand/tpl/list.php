<div style="float:right;width:100%;text-align:center">
    <form class="form-search" action="index.php" method="get" style="margin-bottom:5px">
        <div class="input-append">
<!--            <input type="text" class="input-medium search-query span1" name="id" value="--><?php //echo $_GET['id'];?><!--" placeholder="需求ID" />-->
            <input type="text" class="input-medium search-query span3" name="title" id="title" value="<?php echo $_GET['title'];?>" placeholder="需求标题"  />
            <select name="deptid" id="deptid">
                <option value="">==担当部门==</option>
                <?php
                    foreach(Config_Web::$dept_infos as $dept_id=>$dept){
                        $selected = $_GET['deptid'] == $dept_id ? "selected" : "";
                        echo '<option value="'.$dept_id.'" '.$selected.'>'.$dept.'</option>';
                    }
                ?>
            </select>
            <select name="adminname" id="admin_name_select">
            </select>
            <select name="status" id="status">
                <option value="">==需求当前状态==</option>
                <?php
                    foreach(Config_Web::$demand_status as $ix=>$ds){
                        $selected = $_GET['status'] == $ix ? "selected" : "";
                        echo '<option value="'.$ix.'" '.$selected.'>'.$ds.'</option>';
                    }
                ?>
            </select>
<!--            <input type="text" class="span3" name="expectantstartdate" value="--><?php //echo $_GET['expectantstartdate'];?><!--" placeholder="期待开始时间" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"   /> --->
<!--            <input type="text" class="span3" name="expectantenddate" value="--><?php //echo $_GET['expectantenddate'];?><!--" placeholder="期待结束时间" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"  />-->
            <input type="hidden" name="m" value="<?php echo $_GET['m'];?>" />
            <input type="hidden" name="p" value="<?php echo $_GET['p'];?>" />
            <input type="hidden" id="adminname" value="<?php echo $_GET['adminname'];?>" />
            <input type="hidden" id="username" value="<?php echo $_GET['username'];?>" />
            <input type="hidden" id="changePriUrl" value="?m=<?php echo $_GET['m'];?>&p=uppriority"/>
            <button type="submit" class="btn btn-primary">Search</button>
        </div>

        <div style="float: right;">
            <a href="index.php?m=demand&p=add"><input type="button" class="btn" value="添加需求"></a>
            <a href="index.php?m=demand&p=mydemand"><input type="button" class="btn" value="{我}参与的需求"></a>
<!--            <a href="index.php?m=demand&p=mycreatedemand"><input type="button" class="btn" value="{我}创建的需求"></a>-->
        </div>


    </form>


</div>
<table class="table table-bordered table-striped table-hover dataTable">
	<thead>
	<tr>
		<th nowrap="nowrap"><input type="checkbox" onclick="check(this)" /></th>
        <th nowrap="nowrap">优先级</th>
		<th nowrap="nowrap">需求标题</th>
		<th nowrap="nowrap">担当部门</th>
        <th nowrap="nowrap">担当者</th>
		<th nowrap="nowrap">状态</th>
		<th nowrap="nowrap">创建者</th>
		<th nowrap="nowrap">创建时间</th>
        <th nowrap="nowrap">可供操作</th>
	</tr>
	</thead>
	<tbody>
		
	<?php 
	foreach($demands as $k=>$demand){	
	?>
	<tr>
	    <td style="text-align:center"><input type="checkbox" class="demand_id_checkbox" value="<?php echo $demand['id'];?>" /></td>
<!--        <td>-->
<!--            <a target="_blank" href="index.php?m=--><?php //echo $_GET['m'];?><!--&p=demand&id=--><?php //echo $demand['id'];?><!--"><span class="badge badge-info">--><?php //echo $demand['id'];?><!--</span></a>-->
<!--        </td>-->
        <td>
<!--            <input type="text" style="width: 20px;" value="--><?php //echo $demand['priority'];?><!--" id="--><?php //echo 'pri_'.$demand['id']?><!--" >-->
<!--            <a class="btn btn-danger" title="优先级级别说明：数字越小优先度越高。 注意：0 表示未设置优先度" onclick="changeDemandPrio(--><?php //echo $demand['id']?><?php //echo $demand['status']?>
            <a href="javascript:void(0);" title="优先级级别说明：数字越小优先度越高。 注意：0 表示未设置优先度"><?php echo $demand['priority']?></a>
        </td>
        <td><a href="index.php?m=<?php echo $_GET['m'];?>&p=demand&id=<?php echo $demand['id'];?>"><?php echo $demand['title']?></a></td>

        <td>
            <?php
                switch($demand['type']){
                    case 1:
                        echo "<span class=\"badge badge-important\">",Config_Web::$dept_infos[$demand['type']],"</span>";
                        break;
                    case 2:
                        echo "<span class=\"badge badge-warning\">",Config_Web::$dept_infos[$demand['type']],"</span>";
                        break;
                    case 3:
                        echo "<span class=\"badge badge-success\">",Config_Web::$dept_infos[$demand['type']],"</span>";
                        break;
                    default :
                        break;
                }
            ?>


        </td>
        <td><?php echo $demand['other'];?></td>

<!--        <td>--><?php //echo mb_substr($demand['content'],0,600),"...";?><!--</td>-->
<!--        <td>-->
<!--            <a target="_blank" href="--><?php //echo $demand['datapath'];?><!--">--><?php //echo $demand['datapath'];?><!--</a>-->
<!--        </td>-->
        <td>
                <?php

                    switch($demand['status']){
                        case 1:
                            echo "<span class=\"label label-warning\">",Config_Web::$demand_status[$demand['status']],"</span>";
                            break;
                        case 2:
                            echo "<span class=\"label label-info\">",Config_Web::$demand_status[$demand['status']],"</span>";
                            break;
                        case 3:
                            echo "<span class=\"label label-success\">",Config_Web::$demand_status[$demand['status']],"</span>";
                            break;
                        case 4:
                            echo "<span class=\"label label-inverse\">",Config_Web::$demand_status[$demand['status']],"</span>";
                            break;
                        case 5:
                            echo "<span class=\"label label-important\">",Config_Web::$demand_status[$demand['status']],"</span>";
                            break;
                        default :
                            break;
                    }
                ?>
        </td>
        <td><?php echo $demand['adminname'];?></td>
<!--        <td style="text-align:center;">-->
<!---->
<!--        </td>-->
        <td><?php echo date("Y-m-d H:i:s",$demand['createdate'])?></td>

        <td>
            <?php
            switch($demand['status'])
            {
                case 1:
                case 2:
                case 3:
                    ?>
                    <a class="btn btn-danger" href="?m=demand&p=issue&title=<?php echo $demand['title'];?>&id=<?php echo $demand['id'];?>&demandstatus=<?php echo $demand['status'];?>&op=FP">分配</a>
                    <a class="btn btn-danger ajax_del" onclick="opDemand(5,<?php echo $demand['id'];?>,<?php echo $demand['status'];?>,'GB','')"  href="javascript:void(0);">验收</a>
                    <a class="btn btn-info" title="<?php echo $demand['id'];?>" onclick="editDemand(<?php echo $demand['status'];?>,<?php echo $demand['id'];?>)" href="javascript:void(0);">编辑</a>
                    <?php
                    break;
                case 4:
                    ?>
                    <a class="btn btn-danger ajax_del" onclick="opDemand(5,<?php echo $demand['id'];?>,<?php echo $demand['status'];?>,'GB','')"  href="javascript:void(0);">验收</a>
                    <a class="btn btn-info" title="<?php echo $demand['id'];?>" onclick="editDemand(<?php echo $demand['status'];?>,<?php echo $demand['id'];?>)" href="javascript:void(0);">编辑</a>
                    <?php
                    break;
                case 5:
                    ?>
                    <label style="color: red;font-weight: bolder;">需求已验收完毕</label>
                    <?php
                    break;}
            ?>
        </td>
	</tr>
	<?php
	}
	?>
	</tbody>
</table>
<?php echo $page->show();?>


<script type="text/javascript">
function check(obj){
	$(".demand_id_checkbox").prop("checked",$(obj).prop("checked"));
}
function loadUname(){
    var adminname = $("#adminname").attr("value");
    $.get("index.php?m=ajax&p=select&type=uname&adminname="+adminname,function(ret){
        $("#admin_name_select").html(ret);
    });
}
loadUname();
function changeDemandPrio(id,status){
    var prioval = $("#pri_"+id).attr("value"),
        url = $("#changePriUrl").attr("value");
    $.get(url+"&id="+id+"&op=YXD&upprio="+prioval+"&demandstatus="+status,function(re){
        alert(re.error);
    },"json");
}

function editDemand(status,demand_id){

    var base_url = "<?php echo Config_Web::$base_url;?>";
    //如果状态是1,2 可进行编辑
    if(status === 1 || status === 2){
        location.href = base_url+"index.php?m=demand&p=edit&demandstatus="+status+"&id="+demand_id+"&op=BJ";
        return true;
    }else if(status === 3 || status === 4 || status === 5){
        //如果状态是3,4,5 则去标题进去详细页进行追加需求
        alert("需求不可编辑！可去标题详细页面进行追加需求。");
        return false;
    }else{
        alert("参数出错！联系技术组。");
        return false;
    }
}

function opDemand(new_status,demand_id,status,op,view){

    var base_url = "<?php echo Config_Web::$base_url;?>";
    var g_title = $("#title").val(),
        g_dept = $("#deptid").val(),
        g_adminname= $("#admin_name_select").val(),
        g_demand_status= $("#status").val(),
        str = '';
    if ($.trim(g_title) != ''){
        str +='&g_titile='+g_title;
    }
    if($.trim(g_dept) != ''){
        str +='&g_dept='+g_dept;
    }
    if($.trim(g_adminname) != ''){
        str +='&g_adminname='+g_adminname;
    }
    if($.trim(g_demand_status) != ''){
        str +='&g_demand_status='+g_demand_status;
    }
    var url = base_url+"index.php?m=demand&p=delete&status="+new_status+"&id="+demand_id+"&demandstatus="+status+"&op="+op+"&my="+view;
    $.get(url, function(result){
        var result =  $.parseJSON(result);
        if (parseInt(result.success) === 0){
            alert(result.error);
        }else{
            location.href = result.jump + str;
        }
    });

}
</script>
