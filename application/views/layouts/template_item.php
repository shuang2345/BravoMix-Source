<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
    "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <title><?php echo $site_name ?></title>
        <meta name="description" content="<?php echo $site_description ?>" />
        <meta name="keywords" content="<?php echo $site_keywords ?>" />
        <!-- 樣式表 -->
        <?php echo $styles ?>
        <!-- 腳本表 -->
        <?php echo $scripts ?>
    </head>
    <body>
        <h1>Bravomix.com</h1>
        <p class="credits"><em>by:</em> <a href="#">崇祈股份有限公司</a> </p>
        <ul>
            <li><a href="<?php echo site_url('item/roll') ?>">單品列表</a></li>
            <li><a href="<?php echo site_url('item/edit/new') ?>">建立新單品</a></li>
        </ul>
        <?php echo $content ?>
    </body>
</html>