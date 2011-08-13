<h1>忘記密碼</h1>
<p>請輸入您申請的 Email 帳號</p>

<div id="infoMessage"><?php echo $message;?></div>

<?php echo form_open("auth/forgot_password");?>

      <p>電子郵件 E-mail:<?php echo form_input($email);?></p>
      
      <p><?php echo form_submit('submit', 'Submit');?></p>
      
<?php echo form_close();?>

<p><a href="<?php echo site_url('auth/create_user');?>">申請帳號</a> | <a href="<?php echo site_url('auth/login');?>">登入系統</a></p>