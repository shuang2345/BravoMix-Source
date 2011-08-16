<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 預設使用的樣板
 */
$config['template_layout'] = 'template/layout/fw-18-2-col';

/**
 * 預先載入的CSS檔
 */
$config['template_css'] = array(
    'assets/css/bravomix.css' => 'screen',
);

/**
 * 預先載入的JS檔
 */
$config['template_js'] = array(
    'assets/js/jquery-1.6.2.min.js',
);

/**
 * 樣板預設的變數
 */
$config['template_vars'] = array(
    'site_name' => 'Welcome to BraovMix',
    'site_description' => 'BraovMix,流行服飾搭配',
    'site_keywords' => 'BraovMix,流行服飾搭配',
);