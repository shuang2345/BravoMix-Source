<div class='mainInfo'>

    <div class="pageTitle">登入</div>
    <div class="pageTitleBorder"></div>
    <p>請輸入電子信箱及密碼</p>

    <div id="infoMessage"><?php echo $message;?></div>

    <?php echo form_open("auth/login");?>

      <p>
        <label for="email">電子信箱:</label>
        <?php echo form_input($email);?>
      </p>

      <p>
        <label for="password">密碼:</label>
        <?php echo form_input($password);?>
      </p>

      <p>
          <label for="remember">保持登入:</label>
          <?php echo form_checkbox('remember', '1', FALSE);?>
      </p>


      <p><?php echo form_submit('submit', '登入');?></p>
    <?php echo form_close();?>

    <p><a href="<?php echo site_url('auth/create_user');?>">申請帳號</a></p>
    <p><a href="<?php echo site_url('auth/update_user');?>">編輯個人資料</a></p>

</div>