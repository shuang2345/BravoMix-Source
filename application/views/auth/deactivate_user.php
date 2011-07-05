<div class='mainInfo'>

	<div class="pageTitle">Deactivate User</div>
    <div class="pageTitleBorder"></div>
	<p>確定要停用 '<?php echo $user['username']; ?>'('<?php echo $user['email']; ?>')的帳號嗎?</p>
	
    <?php echo form_open("auth/deactivate/".$user['id']);?>
    	
      <p>
      	<label for="confirm">Yes:</label>
		<input type="radio" name="confirm" value="yes" checked="checked" />
      	<label for="confirm">No:</label>
		<input type="radio" name="confirm" value="no" />
      </p>
      
      <?php echo form_hidden($csrf); ?>
      <?php echo form_hidden(array('id'=>$user['id'])); ?>
      
      <p><?php echo form_submit('submit', 'Submit');?></p>

    <?php echo form_close();?>

</div>
