<div id="item-edit">

    <?php echo isset($validation_error) ? print_r($validation_error, TRUE) : '' ?>
    <?php echo form_open('item/edit/' . $item_id) ?>

    <div class="images-list" style="float:left">
        <fieldset>
            <legend>單品圖示</legend>
            <?php foreach ($item_images as $image): ?>
                <p class="field">
                    <img src="<?php echo site_url('file/get/' . element('file_name', $image, 'no_image.png') . '/100/80') ?>" />
                    <?php echo form_hidden('item_images[][filename]', element('file_name', $image)) ?>
                    <input class="doUpload" type="button" value="上傳商品圖" />        

                    <?php if (element('file_name', $image)): ?>
                        <input type="button" class="removeImg" value="移除" />
                        <input type="button" class="coverImg" value="設為代表圖" />
                    <?php else: ?>
                        <input type="button" class="removeImg" value="移除" style="display:none" />
                        <input type="button" class="coverImg" value="設為代表圖" style="display:none" />
                    <?php endif; ?>

                </p>
            <?php endforeach; ?>
        </fieldset>   

    </div>

    <div id="right">
        <fieldset>
            <legend>單品屬性</legend>
            <img id="cover" src="<?php echo site_url('file/get/' . (($item_cover) ? $item_cover : 'no_image.png') . '/170/120') ?>" style="float:right" />
            <?php echo form_hidden('item_id', $item_id) ?>
            <?php echo form_hidden('item_cover', $item_cover) ?>
            <p class="field">
                <label for="item_title">　　品名：</label>
                <?php echo form_input('item_title', $item_title, 'maxlength="24"') ?>
            </p>
            <p class="field">
                <label for="item_brand">　　品牌：</label>
                <?php echo form_input('item_brand', $item_brand, 'maxlength="15"') ?>
            </p>
            <p class="field">
                <label for="item_price">建議價格：</label>
                <?php echo form_input('item_price', $item_price, 'maxlength="6"') ?>
            </p>  
            <p class="field">
                <label for="item_link">購買連結：</label>
                <?php echo form_input('item_link', $item_link, 'size="32"') ?>
            </p>    

        </fieldset>   

        <fieldset>
            <legend>單品種類(最多三項)</legend>
            <?php foreach ($item_kind_tags as $tag): ?>
                <p class="field">
                    <?php echo form_input('item_kind_tags[][tag_title]', element('tag_title', $tag), 'maxlength ="6"') ?>
                </p>
            <?php endforeach; ?> 
        </fieldset>  

        <fieldset>
            <legend>單品風格(最多三項)</legend>
            <?php foreach ($item_style_tags as $tag): ?>
                <p class="field">
                    <?php echo form_input('item_style_tags[][tag_title]', element('tag_title', $tag), 'maxlength ="6"') ?>
                </p>
            <?php endforeach; ?>    
        </fieldset>    
        <?php echo form_submit('doSubmit', ($item_id) ? '儲存' : '新增', 'class="button"') ?>
    </div>
    <?php echo form_close() ?>

    <!--隱藏式表單-->
    <?php echo form_open_multipart('file/put', array('id' => 'fileForm', 'style' => 'display:none')) ?>
    <?php echo form_close() ?> 
</div>
<!-- JS 測試區 -->
<script type="text/javascript">
    $(document).ready(function(){
        var removeBtn = null;
        var hiddenFileField = null;
        var reviewImg = null;
        /**
         * 將表單以iFrame方式Ajax送出隱藏式檔案上傳表單
         */
        var fileForm = $('#fileForm');
        var fileField = fileForm.find('input[type="file"]');
        fileForm.bind('submit', function(e) {
            e.preventDefault();
            $(this).ajaxSubmit({
                data: {	json_encode: true },
                success: function(data, status, xhr){
                    var fileName = data.upload_data.file_name;
                    var fileWidth = 100;
                    var fileHeight = 75;
                    var thubmPath = '<?php echo site_url('file/get') ?>';
                    //更新預覽圖
                    reviewImg.load(function() {
                        //顯示移除按鈕
                        removeBtn.show();
                    });                      
                    reviewImg.attr('src',new Array(thubmPath,fileName,fileWidth,fileHeight).join('/'));
                    //更新隱藏檔名
                    hiddenFileField.attr('value',fileName);
                },
                dataType : 'json'
            });	
        });
        /**
         * 上傳按鈕
         */
        $('.doUpload').file().choose(function(e, input) {
            hiddenFileField = $(this).parent().find('input[type="hidden"]');
            reviewImg = $(this).parent().find('img');
            removeBtn = $(this).parent().find(':button');
            //            
            fileForm.find(':file').remove();
            input.attr('name','userfile');
            fileForm.append(input);
            fileForm.submit();
        });    
        /**
         * 註冊移除按鈕事件
         */
        $('.removeImg').bind('click',function(){
            var removeBtn = $(this);
            var coverBtn = removeBtn.parent().find('input.coverImg');
            hiddenFileField = removeBtn.parent().find('input[type="hidden"]');
            
            reviewImg = removeBtn.parent().find('img');
            //如果移除的為代表圖，要同時清空目前的代表圖
            if(hiddenFileField.val() == $('input[name="item_cover"]').val()){
                $('input[name="item_cover"]').val('');
                $('#cover').attr('src','<?php echo site_url('file/get/no_image/170/120') ?>');
            }
            hiddenFileField.val('');
            reviewImg.attr('src','<?php echo site_url('file/get/no_image/100/75') ?>');
            reviewImg.load(function() {
                removeBtn.hide();
                coverBtn.hide();
            });       
        });        
        /**
         * 設為代表圖按鈕事件
         */
        $('.coverImg').bind('click',function(){
            var coverBtn = $(this);
            var coverImg = coverBtn.parent().find('img');
            var coverImgName = coverBtn.parent().find('input[type="hidden"]').val();
            var src = coverImg.attr('src').split('/');
            src[src.length-1] = 120;
            src[src.length-2] = 170;
            src = src.join('/');
            $('#cover').attr('src',src);      
            $('input[name="item_cover"]').val(coverImgName);
        });
    });    
</script>