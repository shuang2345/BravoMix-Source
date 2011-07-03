<div class='mainInfo'>

	<h1>Users</h1>
	<p>Below is a list of the users.</p>
	
	<div id="infoMessage"><?php echo $message;?></div>
	
	<table cellpadding=0 cellspacing=10>
		<tr>
			<th>使用者名稱</th>
			<th>使用者暱稱</th>
			<th>電子信箱</th>
			<th>身分</th>
			<th>啟用狀態</th>
		</tr>
		<?php foreach ($users as $user):?>
			<tr>
				<td><?php echo $user['user_name']?></td>
				<td><?php echo $user['user_nickname']?></td>
				<td><?php echo $user['email'];?></td>
				<td><?php echo $user['group_description'];?></td>
				<td><?php echo ($user['active']) ? anchor("auth/deactivate/".$user['id'], '已啟用') : anchor("auth/activate/". $user['id'], '未啟用');?></td>
			</tr>
		<?php endforeach;?>
	</table>
	
	<p><a href="<?php echo site_url('auth/create_user');?>">Create a new user</a></p>
	
	<p><a href="<?php echo site_url('auth/logout'); ?>">Logout</a></p>
	
</div>
