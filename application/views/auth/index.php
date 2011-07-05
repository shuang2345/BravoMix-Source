<div class='mainInfo'>

	<!--Users-->
    
	<p><h1>使用者列表</h1></p>
	<p>登入帳號：<?=$this->session->userdata('email')?></p>
    
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
	
	<p><a href="<?php echo site_url('auth/create_user');?>">申請帳號</a></p>
    <p><a href="<?php echo site_url('auth/update_user');?>">編輯個人資料</a></p>
	
	<p><a href="<?php echo site_url('auth/logout'); ?>">登出</a></p>
    
    
    <?
	//個人資料
	//var_dump($this->session->userdata); 
	?>

	
</div>
