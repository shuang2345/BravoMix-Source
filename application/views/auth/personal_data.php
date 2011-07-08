<div class='mainInfo'>

<p>Hi,<?=$profile->user_name?>你好.歡迎來到BravoMix.</p>
	<!--Users-->

個人資料


 	  <p>匿稱：
      <?=$profile->user_nickname?>
      </p>

      <p>性別：
      <?=$profile->user_sex?>
      </p>

      <p>行動電話：
	  <?=$profile->phone?>
      </p>

      <p>市內電話：
      <?=$profile->cellphone?>
      </p>

      <p>國家：
      <?=$profile->user_country?>
      </p>

      <p>城市：
      <?=$profile->user_city?>
      </p>

      <p>生日：
      <?=$profile->user_birthday?>
      </p>

	  <p>職業：
      <?=$profile->user_job?>
      </p>

      <p>身高：
      <?=$profile->user_body_tall?>
      </p>

      <p>體重：
      <?=$profile->user_body_weight?>
      </p>

      <p>胸圍：
      <?=$profile->user_body_r1?>
      </p>

      <p>腰圍：
      <?=$profile->user_body_r2?>
      </p>

      <p>臀圍：
      <?=$profile->user_body_r3?>
      </p>

      <p>肩寬：
      <?=$profile->user_body_shoulder?>
      </p>

      <p>腿長：
      <?=$profile->user_body_leg ?>
      </p>


      <p><a href="<?php echo site_url('auth/update_user');?>">編輯個人資料</a></p>
      <? if ($profile->group_id == 1) { ?>
      <p><a href="<?php echo site_url('auth/index');?>">回管理介面</a></p>
      <? }?>
      <p><a href="<?php echo site_url('auth/personal_index');?>">回個人首頁</a></p>
      <p><a href="<?php echo site_url('auth/logout'); ?>">登出</a></p>
</div>
