<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <title>Upload Form</title>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <script type="text/javascript" src="https://raw.github.com/malsup/form/master/jquery.form.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
				//顯示檔案位欄
				$('.fileForm').find('input[type="file"]').hide();
				//註冊上傳按鈕事件
				$('.doSubmit').bind('click',function(){
					var submitBtn = $(this);
					var fileForm = submitBtn.parent();
					var imgObj = fileForm.find('img');
					var fileField = fileForm.find('input[type="file"]');
					//將表單以iFrame方式Ajax送出
					fileForm.bind('submit', function(e) {
						e.preventDefault();
						$(this).ajaxSubmit({
							data: {	json_encode: true },
							success: function(data, status, xhr){
								var fileName = data.upload_data.file_name;
								var fileWidth = 100;
								var fileHeight = 75;
								var thubmPath = '<?php echo site_url('file/get')?>';
								imgObj.attr('src',new Array(thubmPath,fileName,fileWidth,fileHeight).join('/'));
							},
							dataType : 'json'
						});		
					});	
					//完成檔案選擇，送出表單
					fileField.bind('change',function(){
						fileForm.submit();
					});		
					//開啟檔案選擇視窗
					fileField.trigger('click');
				});
            });
        </script>
    </head>
    <body>

        <?php echo $error; ?>

        <?php echo form_open_multipart('file/put',array('class'=>'fileForm')); ?>
		<img src="#" />
        <input type="file" name="userfile" size="20" />
        <input class="doSubmit" type="button" value="上傳" />
        <?php echo form_close(); ?>
		
        <?php echo form_open_multipart('file/put',array('class'=>'fileForm')); ?>
		<img src="#" />
        <input type="file" name="userfile" size="20" />
        <input class="doSubmit" type="button" value="上傳" />
        <?php echo form_close(); ?>		
    </body>
</html>
