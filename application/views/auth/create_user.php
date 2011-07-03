<div class='mainInfo'>

	<h1>Create User</h1>
	<p>Please enter the users information below.</p>
	
	<div id="infoMessage"><?php echo $message;?></div>
	
    <?php echo form_open("auth/create_user");?>
      <p>姓名:<br />
      <?php echo form_input($user_name);?>
      </p>
      
      <p>匿稱:<br />
      <?php echo form_input($user_nickname);?>
      </p>
      
      <p>公司:<br />
      <?php echo form_input($company);?>
      </p>
      
      <p>E-mail:<br />
      <?php echo form_input($email);?>
      </p>
      
      <p>電話:<br />
      <?php echo form_input($phone1);?>-<?php echo form_input($phone2);?>-<?php echo form_input($phone3);?>
      </p>
      
      <p>請輸入密碼:<br />
      <?php echo form_input($password);?>
      </p>
      
      <p>再次輸入密碼:<br />
      <?php echo form_input($password_confirm);?>
      </p>
      
      
      <p><?php echo form_submit('submit', 'Create User');?></p>

      
    <?php echo form_close();?>

</div>
