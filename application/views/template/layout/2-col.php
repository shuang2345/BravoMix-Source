<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?php echo $site_name ?></title>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <meta name="description" content="<?php echo $site_description ?>" />
        <meta name="keywords" content="<?php echo $site_keywords ?>" />
        <!-- CSS -->
        <?php echo link_tag('assets/css/layout/2-col.css') ?>
        <?php echo $styles ?>
        <!-- JS -->
        <?php echo $scripts ?>
    </head>

    <body>
        <div id="top">
            <?php echo $top ?>
        </div>

        <!-- Begin Wrapper -->
        <div id="wrapper">

            <!-- Begin Header -->
            <div id="header">
                <?php echo $header ?>
            </div>
            <!-- End Header -->

            <!-- Begin Navigation -->
            <div id="navigation">
                <?php echo $navigation ?>
            </div>
            <!-- End Navigation -->

            <!-- Begin Left Column -->
            <div id="leftcolumn">
                <?php echo $leftcolumn ?>
            </div>
            <!-- End Left Column -->

            <!-- Begin Right Column -->
            <div id="rightcolumn">
                <?php echo $content ?>
            </div>
            <!-- End Right Column -->

            <!-- Begin Footer -->
            <div id="footer">
                <?php echo $footer ?>
            </div>
            <!-- End Footer -->

        </div>
        <!-- End Wrapper -->

        <div id="bottom">
            <?php echo $bottom ?>
        </div>
    </body>
</html>
