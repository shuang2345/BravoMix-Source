<div id="item-view">
    <h1><?php echo $item_title ?></h1>
    <table style="width:100%">
        <tr>
            <td style="width:500px;">
                <div class="pikachoose">
                    <ul id="pikame" class="jcarousel-skin-pika">
                        <li>
                            <a href="#"><img alt="#" src="<?php echo site_url('file/get/' . $item_cover . '/300/250') ?>"/></a>
                            <span>封面</span>
                        </li>             
                        <?php foreach ($item_images as $key => $image): ?>
                            <?php if ($item_cover && $item_cover != element('file_name', $image)): ?>
                                <li>
                                    <a href="#"><img alt="#" src="<?php echo site_url('file/get/' . element('file_name', $image, 'no_image.png') . '/300/250') ?>"/></a>
                                    <span><?php echo element('client_name', $image) ?></span>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>            
                    </ul>
                </div>                  
            </td>
            <td style="vertical-align: top">
                <ul class="item-info">
                    <li>品牌：<?php echo $item_brand ?></li>
                    <li>連結：</li>
                    <li>售價：<?php echo $item_price ?></li>
                    <li>分類：<?php foreach ($item_kind_tags as $tag): ?><a href="#"><?php echo $tag['tag_title'] ?></a> | <?php endforeach; ?></li>
                    <li>風格：<?php foreach ($item_style_tags as $tag): ?><a href="#"><?php echo $tag['tag_title'] ?></a> | <?php endforeach; ?></li>
                </ul>        
                <?php if($show_edit_button):?>
                <button onclick="location.href='<?php echo site_url('item/edit/' . $item_id) ?>'">編輯</button>
                <?php endif;?>                
                <button id="addToWardrobe" title="<?php echo $item_id ?>">加到衣櫃</button>
            </td>
        </tr>
    </table>
</div>
<script type="text/javascript">
    /**
     * 加至衣櫃
     */    
    $(document).ready(function(){
        $("#addToWardrobe").click(function(){
            var item_id = $(this).attr('title');
            var ajax_url = '<?php echo site_url('wardrobe/recruit') ?>/'+item_id;
            $.post(ajax_url,{},function(response){
                if(response.result){
                    alert('已加至衣櫃中');
                } else {
                    alert(response.error);
                }
            },'json');
        });
    });    
    /**
     * 圖片展示
     */    
    $(document).ready(function(){
        $("#pikame").PikaChoose({carousel:false});
    });
</script>