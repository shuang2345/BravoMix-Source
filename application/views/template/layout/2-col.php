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
        <!-- CSS -->
        <?php echo link_tag('assets/css/layout/2-col.css') . "\n"; ?>
        <?php echo $styles ?>

    </head>

    <body>
        <div id="top">
        </div>

        <!-- Begin Wrapper -->
        <div id="wrapper">

            <!-- Begin Header -->
            <div id="header">
            </div>
            <!-- End Header -->

            <!-- Begin Navigation -->
            <div id="navigation">
            </div>
            <!-- End Navigation -->

            <!-- Begin Content -->
            <div id="content">
                <?php echo $content ?>
            </div>
            <!-- End Content -->

            <!-- Begin Left Column -->
            <div id="leftcolumn">
            </div>
            <!-- End Left Column -->

            <!-- Begin Right Column -->
            <div id="rightcolumn">
            </div>
            <!-- End Right Column -->

            <!-- Begin Footer -->
            <div id="footer">
            </div>
            <!-- End Footer -->

        </div>
        <!-- End Wrapper -->

        <div id="bottom">
        </div>
        <!-- JS -->
        <?php echo $scripts ?>
    </body>
</html>
