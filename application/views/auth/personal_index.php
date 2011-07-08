<div class='mainInfo'>

<p>Hi,<?=$profile->user_name?>你好.歡迎來到BravoMix.</p>
<p>你目前的身分是<?=$profile->group_description?>.</p>
    <!--Users-->
    <? //var_dump($profile)?>
<? if ($profile->group_id == 1) { ?>
<p><a href="<?php echo site_url('auth/index');?>">管理介面</a></p>
<? }?>
<p><a href="<?php echo site_url('auth/personal_data');?>">個人資料</a></p>
<p><a href="<?php echo site_url('auth/change_password');?>">忘記密碼</a></p>
<p><a href="<?php echo site_url('auth/logout'); ?>">登出</a></p>

</div>