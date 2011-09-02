<div id="mix-view">

    <!-- 
        混塔區塊
    -->
    <div id="mashup" style="width:600px">
        <input id="mix_id" type="hidden" value="<?php echo $mix_id ?>" />
        <h1>混塔圖</h1>
        <div class="box" style="width:475px;height:475px;border:2px solid #CCC">
            <?php if ($img_mode): ?>
                <img src="<?php echo site_url('file/get/' . $mix_id . '.png/475/475/mix') ?>" />
            <?php else: ?>
                <?php foreach ($mix_items as $item): ?>
                    <div id="item-<?php echo $item['item_id'] ?>" class="item" 
                         style="
                         display: none;
                         position:absolute;
                         z-Index:<?php echo $item['item_zIndex'] ?>;
                         left:<?php echo $item['item_left'] ?>px;
                         top:<?php echo $item['item_top'] ?>px;">
                        <a href="#">
                            <img alt="#"
                                 src="<?php echo site_url('file/get/' . $item['item_cover'] . '/500/500/crop') ?>" 
                                 width="<?php echo $item['item_width'] ?>"
                                 height="<?php echo $item['item_height'] ?>" />
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>  
    </div>
    <button onclick="location.href='<?php echo site_url('mix/view/' . $mix_id . '') ?>'">資訊模式</button>
    <button onclick="location.href='<?php echo site_url('mix/view/' . $mix_id . '/imgmode?' . time()) ?>'">純圖模式</button>
    <button onclick="location.href='<?php echo site_url('mix/edit/' . $mix_id) ?>'">編輯</button>    
</div>


<!-- END #mix-edit -->

<!-- JS 測試區 -->
<script type="text/javascript">
    $(document).ready(function(){
        function fixMixBoxItem(){
            var box =  $('#mashup > .box');
            var items = box.find('.item');
            $.each(items,function(i){
                var item = $(items[i]);
                item.css('display','inline-block')
                var left = parseInt(item.css('left')) + box.position().left;
                var top  = parseInt(item.css('top')) + box.position().top;                
                item.offset({ top: top, left: left });
                //
                item.hover(
                function () {
                    $(this).css('border','1px solid #333');
                    $(this).css('margin','0px');
                }, 
                function () {
                    $(this).css('border','none');
                    $(this).css('margin','1px');
                });
            });
        };
        fixMixBoxItem();
    });
</script>