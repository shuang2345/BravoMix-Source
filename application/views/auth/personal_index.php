<div class='mainInfo'>

<p>Hi,<?php echo $profile->user_name; ?>你好.歡迎來到BravoMix.</p>
<p>你目前的身分是<?php echo $profile->group_description; ?>.</p>

<?php if($profile->group_id == 1): ?>
<p><a href="<?php echo site_url('auth/index');?>">管理介面</a></p>
<?php endif; ?>
<p><a href="<?php echo site_url('auth/update_user');?>">更新個人資料</a></p>
<p><a href="<?php echo site_url('auth/personal_data');?>">個人資料</a></p>
<p><a href="<?php echo site_url('auth/logout'); ?>">登出</a></p>

</div>