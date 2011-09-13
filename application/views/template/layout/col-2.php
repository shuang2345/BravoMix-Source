<!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!-- Consider adding an manifest.appcache: h5bp.com/d/Offline -->
<!--[if gt IE 8]><!--><html class="no-js" lang="en"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <base href="<?php echo base_url(); ?>" />
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
        <div id="top"></div>

        <!-- Begin Wrapper -->
        <div id="wrapper" class="container">

            <!-- Begin Header -->
            <div id="header">
                <ul class="menu">
                    <li><a href="<?php echo site_url('auth/create_user'); ?>">註冊</a></li>
                    <li><a href="<?php echo site_url('auth/login'); ?>">登入</a></li>
                    <li><a href="#">小幫手</a></li>
                </ul>
                <a href="<?php echo site_url('welcome'); ?>">
                    <h1 class="logo"><strong>BravoMix</strong></h1>
                </a>
            </div>
            <!-- End Header -->

            <!-- Begin Navigation -->
            <div id="nav">
                <ul class="menu">
                    <li class="tab-mix <?php echo ('mix' == $ctrl) ? 'current' : ''; ?>"><a href="<?php echo site_url('mix/roll'); ?>">搭配</a></li>
                    <li class="tab-item <?php echo ('item' == $ctrl) ? 'current' : ''; ?>"><a href="<?php echo site_url('item/roll'); ?>">單品</a></li>                    
                    <li class="tab-expert <?php echo ('expert' == $ctrl) ? 'current' : ''; ?>"><a href="#">達人</a></li>
                    <li class="tab-brand <?php echo ('brand' == $ctrl) ? 'current' : ''; ?>"><a href="#">品牌專區</a></li>
                    <li class="tab-unknow <?php echo ('unknow' == $ctrl) ? 'current' : ''; ?>"><a href="#">我不知道要穿什麼</a></li>
                </ul>
            </div>
            <!-- End Navigation -->

            <div id="center" class="span-24">
                <!-- Begin Left Column -->
                <div id="aside" class="span-5">

                    <div id="search">
                        <input type="text" value="" />
                        <input type="button" value="GO" />
                    </div>

                    <div class="profile">
                        <img src="assets/images/no_image.png" width="64" height="64" />
                        <a href="auth/" >liaosankai</a>

                        <ul class="menu">
                            <li><a href="#">個人設定</a></li>
                            <li><a href="#">我的衣櫃</a></li>
                            <li><a href="#">我的收件匣</a> (2)</li>                            
                        </ul>                        
                    </div>
                    <hr />
                    <div class="profile">
                        <h4>我所追蹤的網友</h4>
                        <ul class="menu">
                            <li><a href="#">大米</a></li>
                            <li><a href="#">lulu卡</a></li>
                            <li><a href="#">大口徑</a></li>                            
                        </ul>                        
                    </div>
                    <hr />
                    <div class="profile">
                        <h4>關注的單品</h4>
                        <ul class="menu">
                            <li><a href="#">追蹤</a></li>
                            <li><a href="#">我的衣櫃</a></li>
                            <li><a href="#">收件匣</a></li>                            
                        </ul>                        
                    </div>                    
                    <div class="clearfix">&nbsp;</div>
                </div>
                <!-- End Left Column -->

                <!--Begin Content -->
                <div  class="span-14 ">
                    <?php echo $content; ?>
                </div>
                <!-- End Content -->

                <div id="aside" class="span-5 last">
   
                    <div class="profile">
                        <h4>正在關注你的網友</h4>
                        <ul class="menu">
                            <li><a href="#">大米</a></li>
                            <li><a href="#">lulu卡</a></li>
                            <li><a href="#">大口徑</a></li>                            
                        </ul>
                        <a href="#">檢視更多</a>
                    </div>
                    <hr />
                    <div class="profile">
                        <h4>關注的單品</h4>
                        <ul class="menu">
                            <li><a href="#">追蹤</a></li>
                            <li><a href="#">我的衣櫃</a></li>
                            <li><a href="#">收件匣</a></li>                            
                        </ul>                        
                    </div>                    
                    <div class="clearfix">&nbsp;</div>
                </div>
            </div>

            <!-- Begin Footer -->
            <div id="footer" class="span-24">
                <ul class="menu">
                    <li><a href="#">合作機會</a></li>
                    <li><a href="#">工作機會</a></li>
                    <li><a href="#">連絡我們</a></li>
                </ul>
            </div>
            <!-- End Footer -->

        </div>
        <!-- End Wrapper -->

        <div id="bottom" class="notice"><?php echo "Page rendered in " . $this->benchmark->elapsed_time() . " seconds" ?></div>
        <?php echo $scripts_footer; ?>
    </body>
</html>
