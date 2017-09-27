<style type="text/css">
    .shiji_li{
        float: left;
    }
    .shiji_nr{

    }
</style>
<table class="table table-bordered">

        <tr>
            <td>需求时间轴</td>
            <td>
                <div class="reset-line">
                    <ul>
                        <li class="shiji_li"><em>需求创建时间</em><span class="shiji_nr">待开发</span></li>
                        <li class="shiji_li"><em>需求分配时间</em><span class="shiji_nr">待开发</span></li>
                        <li class="shiji_li"><em>需求开始时间</em><span class="shiji_nr">待开发</span></li>
                        <li class="shiji_li"><em>需求完成时间</em><span class="shiji_nr">待开发</span></li>
                        <li class="shiji_li"><em>需求验收时间</em><span class="shiji_nr">待开发</span></li>
                    </ul>
                </div>
            </td>
        </tr>

        <tr>
            <td>需求标题</td>
            <td><?php echo $data['title'];?></td>
        </tr>

        <tr>
            <td>担当部门</td>
            <td>
                <?php echo Config_Web::$dept_infos[$data['type']];?>
            </td>
        </tr>

        <tr>
            <td>需求内容</td>
            <td width="90%">
                <?php echo $data['content'].$zhuijias;?>
            </td>
        </tr>

        <tr>
            <td>需求附件</td>
            <td width="90%">
                <a target="_blank" href="<?php echo $data['datapath']?>"><?php echo $data['datapath']?></a>
            </td>
        </tr>

        <tr>
            <td>需求状态</td>
            <td width="90%">
                <?php echo Config_Web::$demand_status[$data['status']]?>
            </td>
        </tr>

        <tr>
            <td>需求创建者</td>
            <td width="90%">
                <?php echo $data['adminname']?>
            </td>
        </tr>

        <tr>
            <td>需求担当者</td>
            <td width="90%">
                <?php
                foreach($usernames as $username){
                    echo $username['username'].'  ';
                }
                ?>
            </td>
        </tr>
        <tr>
            <td>需求创建时间</td>
            <td width="90%">
                <?php echo date("Y-m-d H:i:s",$data['createdate'])?>
            </td>
        </tr>
    </table>

<form class="ajax_form" style="float:left;width:100%" action="index.php?m=demand&p=demand" method="post">
<input type="hidden" name="data[demandid]" value="<?php echo  $data['id']?>">
<table class="table table-bordered">
    <tr>
        <td>追加需求内容</td>
        <td width="90%">
            <textarea id="ueditor" name="data[comment]"></textarea>
        </td>
    </tr>

    <tr>
        <td colspan="2">
            <input type="submit" value=" 提 交 " class="btn btn-primary" />
            <input type="button" value=" 取 消 " class="btn" onclick="history.go(-1)">
        </td>
    </tr>
    </table>
</form>
<script type="text/javascript">
    UE.getEditor("ueditor",{elementPathEnabled : false,wordCount:false,initialFrameHeight:260});
</script>