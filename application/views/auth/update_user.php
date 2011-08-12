<div class='mainInfo'>
<p>Hi,<?php echo $user_name; ?></p>

    <h1>編輯資料</h1>
    <p>請輸入個人資料</p>

    <div id="infoMessage"><?php echo $message;?></div>
    <?php echo form_open("auth/update_user");?>

      <p>姓名：
      <span id="show_username"><?php echo $user_name;?></span><span id="show_messsage" style="display:none;padding-left:10px;color:red">已成功提交姓名變更申請</span><input type="button" id="edit_username" value="申請變更姓名" />
      <div id="show_button" style="display:none;"><input type="button" id="save_username" value="送出" /><input type="button" id="cancel" value="取消" /></div>
      </p>

      <p>匿稱：
      <?php echo form_input($user_nickname);?>
      </p>

      <p>性別：
      <?php echo form_radio($user_sex_m) . "男" . form_radio($user_sex_f) . "女";?>
      </p>

      <p>行動電話：
      <?php echo form_input($phone);?>
      </p>

      <p>市內電話：
      <?php echo form_input($cellphone);?>
      </p>

      <p>生日：
      <?php echo $user_birthday;?>
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
      </p>

      <p><?php echo form_submit('submit', '儲存');?></p>
      <p><a href="<?php echo site_url('auth/personal_data');?>">個人資料</a></p>


    <?php echo form_close();?>

</div>
<script type="text/javascript" src="/assets/js/update_user.js"></script> 
