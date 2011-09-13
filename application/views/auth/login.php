<div class='mainInfo'>

    <div class="pageTitle">登入</div>
    <div class="pageTitleBorder"></div>
    <p>請輸入電子信箱及密碼</p>

    <div id="infoMessage"><?php echo $message; ?></div>

    <?php echo form_open("auth/login"); ?>

    <p>
        <label for="email">電子信箱:</label>
        <?php echo form_input($email); ?>
    </p>

    <p>
        <label for="password">密碼:</label>
        <?php echo form_input($password); ?>
    </p>
    <?php if ($show_captcha): ?>
        <p>
            <label for="remember">驗證碼:</label>
            <?php echo form_input($vcode); ?><br /><?php echo $images; ?><br />
            <input type="button" value="不清楚嗎？請重新刷圖" id="regen_code" />
        </p>
    <?php endif; ?>
    <p>
        <label for="remember">保持登入:</label>
        <?php
        echo form_checkbox(array(
            'name' => 'remember',
            'id' => 'remember',
            'value' => '1',
            'checked' => FALSE));
        ?>
    </p>

    <p><?php echo form_submit('submit', '登入'); ?></p>
<?php echo form_close(); ?>

    <p><a href="<?php echo site_url('auth/create_user'); ?>">申請帳號</a> | <a href="<?php echo site_url('auth/forgot_password'); ?>">忘記密碼</a></p>
</div>