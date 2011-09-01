<div id="mix-edit">

    <!-- 
        衣櫃區塊
    -->
    <div id="wardrobe" style="float: right;border:1px solid #369;width:400px;height:500px">

        <ul class="item-tag">
            <li>所有</li>
            <li>別人上傳的</li>
        </ul>

        <div class="item-roll"></div>
    </div>

    <!-- 
        混塔區塊
    -->
    <div id="mashup" style="width:600px">
        <input id="mix_id" type="hidden" value="<?php echo $mix_id ?>" />
        <h1>混塔圖</h1>
        <div class="box" style="width:475px;height:475px;border:2px solid #CCC"></div>
        <button class="save">儲存搭配</button>
    </div>

</div>


<!-- END #mix-edit -->

<!-- JS 測試區 -->
<script type="text/javascript">
    $(document).ready(function(){
        /**
         * 初始化衣櫃
         **/
        function initWardrobe(){
            var wardrobeDataUrl = '<?php echo site_url('wardrobe/view') ?>';
            $.post(wardrobeDataUrl, {}, function(resp){
                var itemData = resp.data.items;
                $.each(itemData,function(key){
                    var item = itemData[key];
                    //混搭台裡面如果沒有，再加至衣櫃中
                    if(! $('#item-'+item.item_id).length){
                        var div = $('<div><img /></div>');
                        div.attr('id', 'item-'+item.item_id);
                        div.addClass('item');
                        //
                        div.find('img').attr('src','<?php echo site_url('file/get/') ?>/'+item.item_cover+'/500/500/crop');
                        div.find('img').width(150);
                        div.find('img').height(150);
                        div.appendTo( $('#wardrobe > .item-roll') ); 
                    }
                });
                initItems();                
            },'json');
        }        
        /**
         * 初始化混搭台
         **/
        function initMixBox(){
            var mixDataUrl = '<?php echo site_url('mix/view') ?>';
            $.post(mixDataUrl, {mix_id: $('#mix_id').val()}, function(resp){
                var itemData = resp.data.mix_items;
                $.each(itemData,function(key){
                    var item = itemData[key];
                    //
                    var div = $('<div><img /></div>');
                    div.attr('id', 'item-'+item.item_id);
                    div.addClass('item');
                    div.find('img').attr('src','<?php echo site_url('file/get/') ?>/'+item.item_cover+'/500/500/crop');
                    div.find('img').width(item.item_width);
                    div.find('img').height(item.item_height);
                    ///////////
                    var box =  $('#mashup > .box');
                    var left = parseInt(item.item_left) + box.position().left;
                    var top  = parseInt(item.item_top) + box.position().top;
                    console.log('left:'+left+',top:'+top);
                    div.appendTo(box);
                    div.css('position','absolute');
                    div.css('zIndex',item.item_zIndex);
                    div.offset({ top: top, left: left });
                    div.draggable( "option" , 'revert' ,false );
                    div.draggable( "option" , 'containment', $('#mashup > .box'));
                    //建立移除按鈕
                    $('<a class="remove">[x]</a>').appendTo(div);
                    div.find('a.remove').css('display','none');
                    div.find('a.remove').css('position','absolute');
                    div.find('a.remove').css('cursor','pointer');
                    div.find('a.remove').css('top',0);
                    div.find('a.remove').css('left',0);
                    div.find('a.remove').click(function(){
                        $(this).remove();
                        div.trigger('mouseout');
                        div.css('position','relative');
                        div.css('top',0);
                        div.css('left',0);    
                        div.find('img').width(150);
                        div.find('img').height(150);
                        div.draggable( "option" , 'revert' ,'invalid' );
                        div.draggable( "option" , 'containment', $( "#mix-edit" ).length ? "#mix-edit" : "document");
                        div.appendTo( $('#wardrobe .item-roll') );                  
                    })
                    /**
                     * 讓單品可調整大小
                     */
                    div.resizable({
                        containment: box,
                        autoHide: true,
                        aspectRatio: 1,
                        maxHeight: 400,
                        maxWidth: 400,
                        minHeight: 100,
                        minWidth: 100,
                        resize: function(evt, ui){
                            var img = $(this).find('img');
                            img.width(ui.size.width);
                            img.height(ui.size.height);
                            //todo:resizable的containment無效，要自己修正
                            resizing = true;
                        },
                        stop: function(evt, ui){
                            resizing = false;
                        }
                    }); 
                });
                initWardrobe();
            },'json');
        }
        initMixBox(); 
        /**
         * 單品初始化
         */
        function initItems(){
            var items = $('#mix-edit').find('.item');
            items.css('display','inline-block');
            items.css('margin','2px');
            items.find('img').css('cursor','move');
            /**
             * 單品可移動化
             */
            items.draggable({
                helper: 'original',
                //iframeFix: true,
                cancel: '.ui-resizable-handle',
                cursor: 'move',
                refreshPositions: true,
                revert: 'invalid' ,
                containment: $( "#mix-edit" ).length ? "#mix-edit" : "document",
                stack: '.item',
                handle: 'img'
            });
            /**
             * 將單品移到最前面
             */        
            items.click(function(){
                var otherItemsIndex = new Array();
                $.each($(this).siblings('.item'),function(key){
                    if(isNaN($(this).css('zIndex'))){
                        otherItemsIndex[key] = 0;
                    } else {
                        otherItemsIndex[key] = $(this).css('zIndex');
                    }                
                });
                var maxZIndex = Math.max.apply(Math, otherItemsIndex);
                $(this).css('zIndex',maxZIndex+1); 
            });
            /**
             * 顯示單移範圍框
             */
            var resizing = false;
            items.hover(
            function () {
                if(!resizing){
                    //顯示外框
                    $(this).css('border','2px dashed #CCC');
                    $(this).css('margin','0px');
                    //顯示移除按鈕
                    $(this).find('a.remove').css('display','block');
                }
            }, 
            function () {
                if(!resizing){
                    //隱藏外框
                    $(this).css('border','none');
                    $(this).css('margin','2px');
                    //隱藏移除按鈕
                    $(this).find('a.remove').css('display','none');                
                }
            });
            /**
             * 當單品被移至範圍區時
             */
            $('#mashup > .box').droppable({
                accept: items,
                activeClass: "ui-state-highlight",
                drop: function( evt, ui ) {
                    var box = $(this);
                    var left = ui.offset.left;
                    var top  = ui.offset.top;
                    ui.draggable.appendTo(box);
                    ui.draggable.css('position','absolute');
                    ui.draggable.offset({ top: top, left: left });
                    ui.draggable.draggable( "option" , 'revert' ,false );
                    ui.draggable.draggable( "option" , 'containment', $('#mashup > .box'));
                    //建立移除按鈕
                    $('<a class="remove">[x]</a>').appendTo(ui.draggable);
                    ui.draggable.find('a.remove').css('display','none');
                    ui.draggable.find('a.remove').css('position','absolute');
                    ui.draggable.find('a.remove').css('cursor','pointer');
                    ui.draggable.find('a.remove').css('top',0);
                    ui.draggable.find('a.remove').css('left',0);
                    ui.draggable.find('a.remove').click(function(){
                        $(this).remove();
                        ui.draggable.trigger('mouseout');
                        ui.draggable.css('position','relative');
                        ui.draggable.css('top',0);
                        ui.draggable.css('left',0);    
                        ui.draggable.find('img').width(150);
                        ui.draggable.find('img').height(150);
                        ui.draggable.draggable( "option" , 'revert' ,'invalid' );
                        ui.draggable.draggable( "option" , 'containment', $( "#mix-edit" ).length ? "#mix-edit" : "document");
                        ui.draggable.appendTo( $('#wardrobe .item-roll') );                  
                    })
                    /**
                     * 讓單品可調整大小
                     */
                    ui.draggable.resizable({
                        containment: box,
                        autoHide: true,
                        aspectRatio: 1,
                        maxHeight: 400,
                        maxWidth: 400,
                        minHeight: 100,
                        minWidth: 100,
                        resize: function(evt, ui){
                            var img = $(this).find('img');
                            img.width(ui.size.width);
                            img.height(ui.size.height);
                            //todo:resizable的containment無效，要自己修正
                            resizing = true;
                        },
                        stop: function(evt, ui){
                            resizing = false;
                        }
                    });  
                }
            });               
        }//end initItems
  
        /**
         * 儲存搭配
         **/
        $('#mashup').find('button.save').click(function(){
            var box = $(this).parent().find('.box');
            var mixItems = box.find('.item');
            var mixData = new Array();
            $.each(mixItems,function(i){
                var item = $(mixItems[i]);
                var itemId = item.attr('id').split('-').pop();
                var relativeTop = $(this).position().top - box.position().top;
                var relativeLeft = $(this).position().left - box.position().left;
                var itemZIndex = item.css('zIndex');
                mixData[i] = {
                    item_id : itemId,
                    item_top: relativeTop,
                    item_left: relativeLeft,
                    item_zIndex: itemZIndex,
                    item_width: item.width(),
                    item_height: item.height()
                }
            });
            //準備欲儲存的資料參數
            var params = {
                mix_id: $('#mix_id').val(),
                mix_items: mixData
            }
            var saveMixUrl = '<?php echo site_url('mix/save') ?>';
            $.post(saveMixUrl, params, function(resp){
                console.log(resp);
                if(true == resp.result) {
                    alert('混搭資訊已儲存');
                    //若兩個mix_id不一樣，表是為新增
                    if(resp.mix_id != params.mix_id){
                        $('#mix_id').val(resp.mix_id);
                    }
                } else {
                    alert('無法儲存混搭資訊');
                }
            },'json');
        });        
    });
</script>