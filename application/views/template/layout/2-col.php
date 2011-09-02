<!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!-- Consider adding an manifest.appcache: h5bp.com/d/Offline -->
<!--[if gt IE 8]><!--><html class="no-js" lang="en"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <title><?php echo $site_title; ?></title>
        <meta name="description" content="<?php echo $site_description; ?>" />
        <meta name="keywords" content="<?php echo $site_keywords; ?>" />

        <!-- CSS -->
        <?php echo link_tag('/assets/css/layout/normalize.css') . "\n"; ?>
        <?php echo link_tag('/assets/css/layout/2-col.css') . "\n"; ?>
        <?php echo $styles; ?>

        <!-- JS -->
        <?php echo $scripts_header; ?>
    </head>

    <body>
        <div id="top">
        </div>

        <!-- Begin Wrapper -->
        <div id="wrapper">

            <!-- Begin Header -->
            <header id="header">
                <a href="<?php echo site_url() ?>"><img src="http://www.bravomix.com/includes/images/logo_bravomix.png"></a>
            </header>
            <!-- End Header -->

            <!-- Begin Navigation -->
            <nav id="nav">
                <ul class="links">
                    <li><a href="<?php echo site_url('item/roll') ?>">時尚單品</a></li>
                    <li><a href="<?php echo site_url('mix/roll') ?>">時尚搭配</a></li>
                    <li><a>時尚達人</a></li>
                    <li><a>品牌專區</a></li>
                </ul>
            </nav>
            <!-- End Navigation -->

            <!-- Begin Left Column -->
            <aside id="leftcolumn">
                <h3>帳號區</h3>
                <ul>
                    <li><a href="<?php echo site_url('auth/login'); ?>">登入帳號</a></li>
                    <li><a href="<?php echo site_url('auth/logout'); ?>">登出帳號</a></li>
                    <li><a href="<?php echo site_url('auth/forgot_password'); ?>">忘記密碼</a></li>
                    <li><a href="<?php echo site_url('auth/create_user'); ?>">申請帳號</a></li>
                </ul>
                <hr/>
                <ul>
                    <li><a href="<?php echo site_url('auth/personal_data'); ?>">個人資料</a></li>
                    <li><a href="<?php echo site_url('auth/update_user'); ?>">編輯資料</a></li>
                </ul>
                <hr/>
                <h3>時尚單品</h3>
                <ul>
                    <li><a href="<?php echo site_url('wardrobe/view'); ?>">我的衣櫃</a></li>
                    <li><a href="<?php echo site_url('item/roll'); ?>">單品列表</a></li>
                    <li><a href="<?php echo site_url('item/edit/new'); ?>">建立新單品</a></li>
                </ul>
                <hr/>
                <h3>時尚混搭</h3>
                <ul>
                    <li><a href="<?php echo site_url('mix/roll'); ?>">混搭列表</a></li>
                    <li><a href="<?php echo site_url('mix/edit/new'); ?>">建立搭配</a></li>
                </ul>                
            </aside>
            <!-- End Left Column -->

            <!-- Begin Content -->
            <section id="content">
                <?php echo $content; ?>
            </section>
            <!-- End Content -->

            <!-- Begin Footer -->
            <footer id="footer">
            </footer>
            <!-- End Footer -->

        </div>
        <!-- End Wrapper -->

        <div id="bottom">
        </div>
        <?php echo $scripts_footer; ?>
    </body>
</html>
