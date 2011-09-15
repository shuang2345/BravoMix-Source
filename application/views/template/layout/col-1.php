<!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!-- Consider adding an manifest.appcache: h5bp.com/d/Offline -->
<!--[if gt IE 8]><!--><html class="no-js" lang="en"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <base href="<?php echo base_url()?>" />
    <title><?php echo $site_title; ?></title>
    <meta name="description" content="<?php echo $site_description; ?>" />
    <meta name="keywords" content="<?php echo $site_keywords; ?>" />
    <?php echo $meta_tag; ?>
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/normalize.css" type="text/css" media="screen, projection">
    <link rel="stylesheet" href="assets/css/blueprint/screen.css" type="text/css" media="screen, projection">
    <link rel="stylesheet" href="assets/css/blueprint/print.css" type="text/css" media="print">
    <!--[if lt IE 8]><link rel="stylesheet" href="assets/css/blueprint/ie.css" type="text/css" media="screen, projection"><![endif]-->
    <?php echo $styles; ?>
    <!-- JS -->
    <?php echo $scripts_header; ?>
</head>
<body>
	<div id="top">
        <div class="top-wrapper notice">
             <?php echo "Page rendered in " . $this->benchmark->elapsed_time() . " seconds" ?>
        </div>
    </div>

    <!-- Begin Wrapper -->
	<div id="webpage" class="container showgrid">
        <div class="webpage-wrapper">
        
		<!-- Begin Header -->
        <div id="header" class="column span-24">
                    <div class="header-wrapper">
                        <ul class="menu-bar">
                            <li><a href="auth/create_user">註冊</a></li>
                            <li><a href="auth/login">登入</a></li>
                            <li><a href="#">小幫手</a></li>
                        </ul>
                        <a href="welcome">
                            <h1 class="logo"><strong>BravoMix</strong></h1>
                        </a>
                    </div>
                </div>
        <!-- End Header -->

        <!-- Begin Navigation -->
        <div id="nav" class="column span-24">
                    <div class="nav-wrapper">
                        <ul class="menu-bar">
                            <li class="opt-mix <?php echo ('mix' == $ctrl) ? 'current' : ''; ?>">
                                <a href="mix/roll">搭配</a></li>
                            <li class="opt-item <?php echo ('item' == $ctrl) ? 'current' : ''; ?>">
                                <a href="item/roll">單品</a></li>                    
                            <li class="opt-expert <?php echo ('expert' == $ctrl) ? 'current' : ''; ?>">
                                <a href="#">達人</a></li>
                            <li class="opt-brand <?php echo ('brand' == $ctrl) ? 'current' : ''; ?>">
                                <a href="#">品牌專區</a></li>
                            <li class="opt-unknow <?php echo ('unknow' == $ctrl) ? 'current' : ''; ?>">
                                <a href="#">我不知道要穿什麼</a></li>
                        </ul>
                    </div>
                </div>
        <!-- End Navigation -->

        <!--Begin Content -->
         <div id="content" class="last column span-24">
                    <div class="content-wrapper">
                        <?php echo $content; ?>
                        <div class="clearfix">&nbsp;</div>
                    </div>
                </div>
        <!-- End Content -->


        <!-- Begin Footer -->
        <div id="footer" class="span-24">
                    <div class="footer-wrapper">
                        <ul class="menu-bar">
                            <li><a href="#">合作機會</a></li>
                            <li><a href="#">工作機會</a></li>
                            <li><a href="#">連絡我們</a></li>
                        </ul>
                    </div>
                </div>
        <!-- End Footer -->
			 <div class="clearfix"></div>
		</div>
    </div>
    <!-- End Wrapper -->

    <div id="bottom">
            <div class="bottom-wrapper">
                
            </div>
        </div>
        <?php echo $scripts_footer; ?>
</body>
</html>
