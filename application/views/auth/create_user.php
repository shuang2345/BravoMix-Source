<div class='mainInfo'>

	<h1>新建帳號</h1>
	<p>請輸入個人資料</p>
	
	<div id="infoMessage"><?php echo $message;?></div>

    <?php echo form_open("auth/create_user");?>
      <p>姓名：
      <?php echo form_input($user_name);?>
      </p>
      
      <p>匿稱：
      <?php echo form_input($user_nickname);?>
      </p>
      
      <p>性別：
      <?php echo form_input($user_sex);?>
      </p>
      
      <p>E-mail：
      <?php echo form_input($email);?>
      </p>
      
<!--      <p>行動電話：
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
      
      <p>生日：
      <?php echo form_input($user_birthday);?>
      </p>

<!--	  <p>職業：
      <?php echo form_input($user_job);?>
      </p>
      
      <p>身高：
      <?php echo form_input($user_body_tall);?>
      </p>
      
      <p>體重：
      <?php echo form_input($user_body_weight);?>
      </p>
      
      <p>胸圍：
      <?php echo form_input($user_body_r1);?>
      </p>
      
      <p>腰圍：
      <?php echo form_input($user_body_r2);?>
      </p>
      
      <p>臀圍：
      <?php echo form_input($user_body_r3);?>
      </p>
      
      <p>肩寬：
      <?php echo form_input($user_body_shoulder);?>
      </p>
      
      <p>腿長：
      <?php echo form_input($user_body_leg);?>
      </p>-->
      
      <p>請輸入密碼：
      <?php echo form_input($password);?>
      </p>
      
      <p>再次輸入密碼：
      <?php echo form_input($password_confirm);?>
      </p>
      
      
      <p><?php echo form_submit('submit', 'Create User');?></p>

      
    <?php echo form_close();?>

</div>
