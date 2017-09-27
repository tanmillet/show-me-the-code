<table class="table table-bordered table-striped table-hover">
	<thead>
	<tr>
		<td style="width:10%;">登陆账号</td><td><?php echo $sso_uinfo['user_name'];?></td>
	</tr>
	<tr>
		<td>open_id</td><td><?php echo $sso_uinfo['open_id'];?></td>
	</tr>
	<tr>
		<td>登陆时间</td><td><?php echo $sso_uinfo['login_time'];?></td>
	</tr>
	<tr>
		<td>登陆IP</td><td><?php echo $sso_uinfo['login_ip'];?></td>
	</tr>
	<tr>
		<td>账号角色</td><td><?php echo $sso_uinfo['user_role'];?></td>
	</tr>
	<tr>
		<td>校验时间</td><td><?php echo $sso_uinfo['verify_time'];?></td>
	</tr>
</table>
