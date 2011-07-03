<div class='mainInfo'>

	<h1>Create User</h1>
	<p>Please enter the users information below.</p>
	
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
      
      <p>行動電話：
	  <?php echo form_input($cellphone);?>
      </p>
      
      <p>市內電話：
      <?php echo form_input($phone);?>
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
