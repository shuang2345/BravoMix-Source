<div id="item-roll">
    <div class="pagination quotes">
        <?php echo $pager ?>
    </div>

    <ul class="gallery clearfix">
        <?php foreach ($items as $key => $item): ?>
            <li>
                <a href="<?php echo site_url('item/view/'.$item['item_id'])?>">
                    <span>&nbsp;</span>
                    <em><?php echo $item['item_title'] ?></em>
                    <img alt="<?php echo element('item_cover', $item, 'no_image.png')?>" width="170" height="120" 
                         src="<?php echo site_url('file/get/' . element('item_cover', $item, 'no_image.png') . '/170/120') ?>" />
                </a>
            </li>         
        <?php endforeach; ?>
    </ul>  
    
    <div class="pagination quotes">
        <?php echo $pager ?>
    </div>    
</div>