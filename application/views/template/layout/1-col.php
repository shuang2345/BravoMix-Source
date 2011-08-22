<!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!-- Consider adding an manifest.appcache: h5bp.com/d/Offline -->
<!--[if gt IE 8]><!--><html class="no-js" lang="en"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <title><?php echo $site_title ?></title>
        <meta name="description" content="<?php echo $site_description ?>" />
        <meta name="keywords" content="<?php echo $site_keywords ?>" />
        <?php echo $meta_tag; ?>
        <!-- CSS -->
        <?php echo link_tag('/assets/css/layout/normalize.css') . "\n"; ?>
        <?php echo link_tag('/assets/css/layout/1-col.css') . "\n"; ?>
        <?php echo $styles ?>

        <!-- JS -->
        <?php echo $scripts ?>        
    </head>

    <body>
        <div id="top">
        </div>

        <!-- Begin Wrapper -->
        <div id="wrapper">

            <!-- Begin Header -->
            <header id="header">
                <ul>
                    <li><a href="<?php echo site_url('wardrobe/view') ?>">我的衣櫃</a></li>
                    <li><a href="<?php echo site_url('auth/logout') ?>">登出</a></li>
                </ul>
            </header>
            <!-- End Header -->

            <!-- Begin Naviagtion -->
            <nav id="nav">
                <ul class="links">
                    <li><a href="<?php echo site_url('item/roll') ?>">時尚單品</a></li>
                    <li><a>時尚搭配</a></li>
                    <li><a>時尚達人</a></li>
                    <li><a>品牌專區</a></li>
                </ul>
            </nav>
            <!-- End Naviagtion -->

            <!-- Begin Content -->
            <section id="content">
                <?php echo $content ?>
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

    </body>
</html>
