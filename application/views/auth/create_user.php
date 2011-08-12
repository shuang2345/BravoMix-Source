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
	  <p>生日：<?php echo $user_birthday;?></p>

      <p>請輸入密碼：
      <?php echo form_input($password);?>
      </p>

      <p>再次輸入密碼：
      <?php echo form_input($password_confirm);?>
      </p>

      <p>
          <label for="remember">驗證碼:</label>
          <?php echo form_input($register_code);?><br /><?php echo $images;?><br />
          <input type="button" value="不清楚嗎？請重新刷圖" id="regen_code" />
      </p>

      <p><?php echo form_submit('submit', 'Create User');?></p>


    <?php echo form_close();?>

</div>

<script type="text/javascript" src="/assets/js/create_user.js"></script> 
