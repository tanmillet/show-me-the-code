<form class="ajax_form" action="index.php?m=<?php echo $_GET['m'];?>&p=issue&demandstatus=<?php echo $_GET['demandstatus'];?>&op=FP" method="post" style="float:left;width:100%">

    <input type="hidden" name="data[id]" value="<?php echo $_GET['id'];?>">

    <table class="table table-bordered">

        <tr>
            <td>需求标题</td>
            <td><input type="text" name="data[title]" value="<?php echo $_GET['title']?>" class="span4" readonly/></td>
        </tr>


        <tr>
            <td>指派担当者</td>
            <td>
                <select name="data[adminname]" id="admin_name_select">
                </select>
            </td>
        </tr>

<!--        <tr>-->
<!--            <td>担当完成时间</td>-->
<!--            <td>-->
<!--                <input type="text" class="span3" name="data[startdate]" value="--><?php //echo $start_time;?><!--" placeholder="开始时间" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"   /> --->
<!--                <input type="text" class="span3" name="data[enddate]" value="--><?php //echo $end_time;?><!--" placeholder="结束时间" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"  />-->
<!--            </td>-->
<!--        </tr>-->

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

    function loadUname(){
        $.get("index.php?m=ajax&p=select&type=demanduname",function(ret){
            $("#admin_name_select").html(ret);
        });
    }
    loadUname();
</script>