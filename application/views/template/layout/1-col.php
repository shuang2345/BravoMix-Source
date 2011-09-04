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
        <?php echo $meta_tag; ?>
        <!-- CSS -->
        <?php echo $styles; ?>
        <link href="/assets/css/layout/1-col.css" rel="stylesheet" type="text/css" />
        <!-- JS -->
        <?php echo $scripts_header; ?>
    </head>

    <body>
        <div id="top">
        </div>

        <!-- Begin Wrapper -->
        <div id="wrapper">

            <!-- Begin Header -->
            <div id="header">
                <ul>
                    <a href="<?php echo site_url(); ?>"><img src="http://www.bravomix.com/includes/images/logo_bravomix.png"></a>
                </ul>
            </div>
            <!-- End Header -->

            <!-- Begin Naviagtion -->
            <div id="nav">
                <ul class="links">
                    <li><a href="<?php echo site_url('item/roll'); ?>">時尚單品</a></li>
                    <li><a href="<?php echo site_url('mix/roll'); ?>">時尚搭配</a></li>
                    <li><a>時尚達人</a></li>
                    <li><a>品牌專區</a></li>
                </ul>
            </div>
            <!-- End Naviagtion -->

            <!-- Begin Content -->
            <div id="content">
                <?php echo $content; ?>
            </div>
            <!-- End Content -->

            <!-- Begin Footer -->
            <div id="footer">

            </div>
            <!-- End Footer -->

        </div>
        <!-- End Wrapper -->

        <div id="bottom">

        </div>
        <?php echo $scripts_footer; ?>
    </body>
</html>
