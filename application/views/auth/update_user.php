<div class='mainInfo'>
<p>登入帳號：<?=$this->session->userdata('email')?></p>

	<h1>編輯資料</h1>
	<p>請輸入個人資料</p>
	
	<div id="infoMessage"><?php echo $message;?></div>
	
    <?php echo form_open("auth/update_user");?>
      <p>姓名：
      <?php echo form_input($user_name);?>
      </p>
      
      <p>匿稱：
      <?php echo form_input($user_nickname);?>
      </p>
      
      <p>性別：
      <?php echo form_input($user_sex);?>
      </p>

      <p>行動電話：
	  <?php echo form_input($cellphone);?>
      </p>
      
      <p>市內電話：
      <?php echo form_input($phone);?>
      </p>
      
      <p>國家：
      <?php echo form_input($user_country);?>
      </p>
      
      <p>城市：
      <?php echo form_input($user_city);?>
      </p>
      
      <p>生日：
      <?php echo form_input($user_birthday);?>
      </p>

	  <p>職業：
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
      </p>
      
      
      <p><?php echo form_submit('submit', 'Update User');?></p>

      
    <?php echo form_close();?>

</div>
