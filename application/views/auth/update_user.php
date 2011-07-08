<div class='mainInfo'>
<p>Hi,<?=$profile->user_name?>.</p>

    <h1>編輯資料</h1>
    <p>請輸入個人資料</p>

    <div id="infoMessage"><?php echo $message;?></div>
    <?php echo form_open("auth/update_user");?>

      <p>匿稱：
      <?php echo form_input(array('name'=>'user_nickname', 'value'=>$profile->user_nickname));?>
      </p>

      <p>性別：
      <?php echo form_input(array('name'=>'user_sex', 'value'=>$profile->user_sex));?>
      </p>

      <p>行動電話：
      <?php echo form_input(array('name'=>'phone', 'value'=>$profile->phone));?>
      </p>

      <p>市內電話：
      <?php echo form_input(array('name'=>'cellphone', 'value'=>$profile->cellphone));?>
      </p>

      <p>國家：
      <?php echo form_input(array('name'=>'user_country', 'value'=>$profile->user_country));?>
      </p>

      <p>城市：
      <?php echo form_input(array('name'=>'user_city', 'value'=>$profile->user_city));?>
      </p>

      <p>生日：
      <?php echo form_input(array('name'=>'user_birthday', 'value'=>$profile->user_birthday));?>
      </p>

      <p>職業：
      <?php echo form_input(array('name'=>'user_job', 'value'=>$profile->user_job));?>
      </p>

      <p>身高：
      <?php echo form_input(array('name'=>'user_body_tall', 'value'=>$profile->user_body_tall));?>
      </p>

      <p>體重：
      <?php echo form_input(array('name'=>'user_body_weight', 'value'=>$profile->user_body_weight));?>
      </p>

      <p>胸圍：
      <?php echo form_input(array('name'=>'user_body_r1', 'value'=>$profile->user_body_r1));?>
      </p>

      <p>腰圍：
      <?php echo form_input(array('name'=>'user_body_r2', 'value'=>$profile->user_body_r2));?>
      </p>

      <p>臀圍：
      <?php echo form_input(array('name'=>'user_body_r3', 'value'=>$profile->user_body_r3));?>
      </p>

      <p>肩寬：
      <?php echo form_input(array('name'=>'user_body_shoulder', 'value'=>$profile->user_body_shoulder));?>
      </p>

      <p>腿長：
      <?php echo form_input(array('name'=>'user_body_leg', 'value'=>$profile->user_body_leg));?>
      </p>

      <p><?php echo form_submit('submit', '儲存');?></p>
      <p><a href="<?php echo site_url('auth/personal_data');?>">個人資料</a></p>


    <?php echo form_close();?>

</div>