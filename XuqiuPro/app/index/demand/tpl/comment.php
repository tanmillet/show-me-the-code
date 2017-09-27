<form class="ajax_form" action="index.php?m=<?php echo $_GET['m'];?>&p=comment&op=PL&demandstatus=<?php echo $_GET['demandstatus'];?>&my=<?php echo $_GET['my']?>" method="post" style="float:left;width:100%">
<input type="hidden" name="data[id]" value="<?php echo $_GET['id']?>">
<table class="table table-bordered">
    <tr>
        <td>需求标题</td>
        <td width="90%"><input type="text" class="span4" value="<?php echo $data['title']?>" readonly/></td>
    </tr>

    <tr>
        <td>担当部门</td>
        <td width="90%">
            <input type="text" class="span4" value="<?php echo Config_Web::$dept_infos[$data['type']];?>" readonly/>
        </td>
    </tr>

<!--    <tr>-->
<!--        <td>需求内容</td>-->
<!--        <td width="90%">-->
<!--            <textarea readonly style="width: 550px;height: 300px;">-->
<!--                --><?php //echo strip_tags($data['content'])?>
<!--            </textarea>-->
<!--        </td>-->
<!--    </tr>-->

    <tr>
        <td colspan="2">
            <label>评论内容</label>
        </td>
    </tr>


    <?php foreach($comments as $comment):?>
        <tr>
            <td>
                <p><?php echo $comment['username']?></p>
                <p>
                    <?php
                        $time = floor((time()-$comment['commentdate']));
                        echo Config_Web::gettime($time);

                    ?>
                </p>
            </td>
            <td width="90%">
                <label><?php echo $comment['comment']?></label>
            </td>
        </tr>
    <?php endforeach;?>


    <tr>
        <td width="10%">我要评论</td>
        <td width="90%">
			  	<textarea id="ueditor" name="data[comment]">
                </textarea>
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
    UE.getEditor("ueditor",{elementPathEnabled : false,wordCount:false,initialFrameHeight:260});
</script>