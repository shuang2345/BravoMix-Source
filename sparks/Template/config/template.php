<?php

/**
 * 預設使用的樣板
 */
$config['template_layout'] = 'layouts/template';

/**
 * CSS
 * 
 * 'path' => 'media'
 */
$config['template_css'] = array(
    base_url() . '/includes/css/bravomix.css' => 'screen',
);

/**
 * Javascript
 */
$config['template_js'] = array(
    base_url() . '/includes/js/jquery-1.6.2.min.js',

);

/**
 * 樣板預設的變數
 */
$config['template_vars'] = array(
    'site_name' => 'Welcome to BraovMix',
    'site_description' => 'BraovMix,流行服飾搭配',
    'site_keywords' => 'BraovMix,流行服飾搭配',
);