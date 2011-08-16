<div id="item-view">



    <table style="width:100%;border:0px">
        <tr>
            <td>
                <div class="item-info">
                    <h3><?php echo $item_title ?></h3>
                    <p>單品品牌：<?php echo $item_brand ?></p>
                    <p>購買連結：<?php echo $item_link ?></p>
                    <p>單品售價：<?php echo $item_price ?></p>
                    <p>分類TAG：<?php foreach ($item_kind_tags as $tag): ?><a href="#"><?php echo $tag['tag_title'] ?></a> | <?php endforeach; ?></p>
                    <p>風格TAG：<?php foreach ($item_style_tags as $tag): ?><a href="#"><?php echo $tag['tag_title'] ?></a> | <?php endforeach; ?></p>
                </div>
            </td>
            <td style="vertical-align: top">
                <ul>
                    <li><a href="#">讚</a></li>
                    <li><a href="#">分享</a></li>
                    <li><a href="#">加至衣櫃中</a></li>
                    <li><a href="<?php echo site_url('item/edit/' . $item_id) ?>">編輯</a></li>
                </ul>  
            </td>
        </tr>
    </table>
    <hr />
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

</div>
<script type="text/javascript">
    $(document).ready(function(){
        $("#pikame").PikaChoose({carousel:true});
    });
</script>