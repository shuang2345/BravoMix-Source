<div id="item-roll">
    <h1>時尚混搭列表</h1>
    <div class="pagination quotes">
        <?php echo $pager ?>
    </div>

    <ul class="gallery clearfix">
        <?php foreach ($mixs as $key => $mix): ?>
            <li>
                <a href="<?php echo site_url('mix/view/' . $mix['mix_id']) ?>">
                    <span>&nbsp;</span>
                    <em><?php echo $mix['mix_id'] ?></em>
                    <img alt="" width="150" height="150" 
                         src="<?php echo site_url('file/get/' . element('mix_id', $mix, 'no_image.png') . '.png/150/150/mix') ?>" />
                </a>
            </li>         
        <?php endforeach; ?>
    </ul>  

    <div class="pagination quotes">
        <?php echo $pager ?>
    </div>    
</div>