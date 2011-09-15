<div id="mix-roll">
    <div class="filter">
        排序規則↓
    </div>
    <div>
        <div class="pagination quotes">
            <?php echo $pager ?>
        </div>

        <table class="grid prepend-1 span-18">
            <tr>
                <?php foreach ($mixs as $key => $mix): ?>
                    <td>
                        <a href="<?php echo site_url('mix/view/' . $mix['mix_id']) ?>">
                            <div class="cell">

                                <img alt="#" width="150" height="150" 
                                     src="file/get/<?php echo element('mix_id', $mix, 'no_image.png') ?>/150/150/crop" />

                                <strong><?php echo $mix['mix_id'] ?></strong>
                            </div>
                        </a>
                    </td>
                    <?php if (1 == $chumk || $key != 0 && ($key + 1) % $chumk == 0): ?>
                    </tr><tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tr>
        </table>

        <div class="pagination quotes">
            <?php echo $pager ?>
        </div>    
    </div>    
</div>