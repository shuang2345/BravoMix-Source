<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Layout library
 *
 * @author Liao San-Kai
 * @see http://d.hatena.ne.jp/localdisk/20110413/1302667273 (原型)
 */
class Template {

    /**
     * ci
     * 
     * @var Codeigniter
     */
    private $_ci;

    /**
     * data
     * 
     * @var array 
     */
    private $_data = array();

    /**
     * layout
     * 
     * @var string 
     */
    private $_layout;

    /**
     * Scripts
     * 
     * @var string 
     */
    private $_scripts = array();

    /**
     * Styles
     * 
     * @var string 
     */
    private $_styles = array();

    /**
     * constructor
     * 
     * @param string $layout 
     */
    public function __construct($config = array())
    {
        $this->_ci = get_instance();
        $this->_ci->load->helper('html');

        log_message('debug', 'Tempalte Class Initialized');

        empty($config) OR $this->initialize($config);
    }

    /**
     * initialize
     * 
     * @param array $config 
     */
    public function initialize($config)
    {
        $this->_layout = $config['template_layout'];

        //add css
        foreach ($config['template_css'] as $href => $media)
        {
            $this->add_css($href, $media);
        }
        //add js
        foreach ($config['template_js'] as $src)
        {
            $this->add_js($src);
        }

        //add var
        foreach ($config['template_vars'] as $key => $val)
        {
            $this->set($key, $val);
        }
    }

    /**
     * set layout
     * 
     * @param string $layout 
     */
    public function set_layout($layout)
    {
        $this->_layout = $layout;
        return $this;
    }

    /**
     * set
     * 
     * @param string $key
     * @param string $value 
     */
    public function set($key, $value)
    {
        $this->_data[$key] = $value;
        return $this;
    }

    /**
     * add css
     * 
     * @param string $path
     * @param string $media
     * @return void
     */
    public function add_css($href=NULL, $media='screen')
    {
        $href = ltrim($href, "/");
        $link = array(
            'href' => $href,
            'rel' => 'stylesheet',
            'type' => 'text/css',
            'media' => $media
        );
        $this->_styles[] = link_tag($link);
    }

    /**
     * add js
     * 
     * @param string $src
     */
    public function add_js($src)
    {
        $this->_scripts[] = script_tag($src);
    }

    /**
     * render
     * 
     * @param String $view
     * @param array $data
     * @param Boolean $return
     * @return string
     */
    public function render($view, $data = NULL, $return = FALSE)
    {
        $this->set('styles', join("\n", $this->_styles) . "\n");
        $this->set('scripts', join("\n", $this->_scripts) . "\n");
        $this->set('content', $this->_ci->load->view($view, $data, TRUE));

        if ($return === TRUE)
        {
            $out = $this->_ci->load->view($this->_layout, $this->_data, $return);
            return $out;
        }
        $this->_ci->load->view($this->_layout, $this->_data);
    }

}