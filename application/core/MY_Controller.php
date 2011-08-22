<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 佈局控制器
 *
 * @author Liao San-Kai
 */
class MY_Controller extends CI_Controller {

    public $loginer;

    /**
     * 建構子
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->library('ion_auth');
        $this->load->spark('template');
        $this->load->model('wardrobe_model');

        //權限判斷
        $this->_auth();

        if ($this->ion_auth->logged_in())
        {
            //登入者資料
            $this->loginer = $this->ion_auth->profile();
            //登入者衣櫃
            $this->_init_wardrobe();
        } else {
            redirect('auth/login');
        }
    }

    /**
     * 初始化衣櫃
     * 
     * 如果登入者沒有一個衣櫃的話，系統將自動建立一個預設衣櫃
     */
    public function _init_wardrobe()
    {
        //取得登入者的衣櫃代碼
        $find_id = $this->wardrobe_model->user_basic($this->loginer->id);

        //如果不存在，為登入者建立一個
        if (!$find_id)
        {
            $find_id = $this->wardrobe_model->create('我的衣櫃', 'basic', $this->loginer->id);
        }
        //記錄登入者的預設衣櫃代碼
        $this->loginer->wardrobe_id = $find_id;
    }

    //--------------------------------------------------------------------------
    /**
     * 權限判斷
     * 
     * @todo 依照登入者角色做權限判斷
     */
    private function _auth()
    {
        $ctrl = $this->router->class;
        $action = $this->router->method;
    }

}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */