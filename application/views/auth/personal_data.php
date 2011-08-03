<div class='mainInfo'>

<h2><?php echo $profile->user_name?> 個人資料</h2>
 	  <p>匿稱：
      <?php echo $profile->user_nickname;?>
      </p>

      <p>性別：
      <?php echo $profile->user_sex;?>
      </p>

      <p>行動電話：
	  <?php echo $profile->phone;?>
      </p>

      <p>市內電話：
      <?php echo $profile->cellphone;?>
      </p>

      <p>國家：
      <?php echo $profile->user_country;?>
      </p>

      <p>城市：
      <?php echo $profile->user_city;?>
      </p>

      <p>生日：
      <?php echo $profile->user_birthday;?>
      </p>

	  <p>職業：
      <?php echo $profile->user_job;?>
      </p>

      <p>身高：
      <?php echo $profile->user_body_tall;?>
      </p>

      <p>體重：
      <?php echo $profile->user_body_weight;?>
      </p>

      <p>胸圍：
      <?php echo $profile->user_body_r1;?>
      </p>

      <p>腰圍：
      <?php echo $profile->user_body_r2;?>
      </p>

      <p>臀圍：
      <?php echo $profile->user_body_r3;?>
      </p>

      <p>肩寬：
      <?php echo $profile->user_body_shoulder;?>
      </p>

      <p>腿長：
      <?php echo $profile->user_body_leg;?>
      </p>


      <p><a href="<?php echo site_url('auth/update_user');?>">編輯個人資料</a></p>
      <?php if($profile->group_id == 1): ?>
      <p><a href="<?php echo site_url('auth/index');?>">回管理介面</a></p>
      <?php endif; ?>
      <p><a href="<?php echo site_url('auth/personal_index');?>">回個人首頁</a></p>
      <p><a href="<?php echo site_url('auth/logout'); ?>">登出</a></p>
</div>
