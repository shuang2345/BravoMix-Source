<div class='mainInfo'>

    <h1>新建帳號</h1>
    <p>請輸入個人資料</p>

    <div id="infoMessage"><?php echo $message;?></div>

    <?php echo form_open("auth/create_user");?>
      <p>E-mail：
      <?php echo form_input($email);?>
      </p>

      <p>姓名：
      <?php echo form_input($user_name);?>
      </p>

      <p>暱稱：
      <?php echo form_input($user_nickname);?>
      </p>

      <p>性別：<?php echo form_radio($user_sex_m) . "男" . form_radio($user_sex_f) . "女";?></p>

      <!--
      <p>行動電話：
      <?php echo form_input($cellphone);?>
      </p>

      <p>市內電話：
      <?php echo form_input($phone);?>
      </p>
      -->
      <p>國家：
      <?php echo form_input($user_country);?>
      </p>

      <p>城市：
      <?php echo form_input($user_city);?>
      </p>

	  <p>生日：<?php echo $user_birthday;?>
	  </p>

      <p>請輸入密碼：
      <?php echo form_input($password);?>
      </p>

      <p>再次輸入密碼：
      <?php echo form_input($password_confirm);?>
      </p>

      <p><?php echo form_submit('submit', 'Create User');?></p>


    <?php echo form_close();?>

</div>