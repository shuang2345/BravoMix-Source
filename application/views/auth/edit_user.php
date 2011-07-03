<div class='mainInfo'>

	<h1>Edit User</h1>
	<p>Please enter the users information below.</p>
	
	<div id="infoMessage"><?php echo $message;?></div>
	
    <?php echo form_open("admin/edit_user/".$this->uri->segment(3));?>
      <p>姓名：
      <?php echo form_input($firstName);?>
      </p>
      
      <p>匿稱：
      <?php echo form_input($lastName);?>
      </p>
      
      <p>市內電話:
      <?php echo form_input($phone);?>
      </p>
      
      <p>E-mail:
      <?php echo form_input($email);?>
      </p>
      
      <p>行動電話：
      <?php echo form_input($cellphone);?>
      </p>
      
      <p>
      	<input type=checkbox name="reset_password"> <label for="reset_password">Reset Password</label>
      </p>
      
      <?php echo form_input($user_id);?>
      <p><?php echo form_submit('submit', 'Submit');?></p>

      
    <?php echo form_close();?>

</div>
