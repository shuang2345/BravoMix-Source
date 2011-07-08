<h1>更改密碼</h1>

<div id="infoMessage"><?php echo $message;?></div>

<?php echo form_open("auth/change_password");?>

      <p>請輸入舊密碼:<br />
      <?php echo form_input($old_password);?>
      </p>

      <p>請輸入新密碼:<br />
      <?php echo form_input($new_password);?>
      </p>

      <p>在輸入一次:<br />
      <?php echo form_input($new_password_confirm);?>
      </p>

      <?php echo form_input($user_id);?>
      <p><?php echo form_submit('submit', '送出');?></p>

<?php echo form_close();?>