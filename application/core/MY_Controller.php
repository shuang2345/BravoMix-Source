<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 佈局控制器
 *
 * @author Liao San-Kai <liaosankai@gmail.com>
 */
class MY_Controller extends CI_Controller
{

    //網站登入者
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
    }

    /**
     * 初始化衣櫃
     * 
     * 如果登入者沒有一個衣櫃的話，系統將自動建立一個預設衣櫃
     */
    public function _init_wardrobe()
    {
        //記錄登入者的預設衣櫃代碼
        $this->loginer->wardrobe_id =
                $this->wardrobe_model->find_loginer_wardbore($this->loginer->id, TRUE);
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


        if ($ctrl != 'auth' && $ctrl != 'welcome')
        {
            if ($this->ion_auth->logged_in())
            {
                //登入者資料
                $this->loginer = $this->ion_auth->profile();
                //登入者衣櫃
                $this->_init_wardrobe();
            }
            else
            {
                redirect('auth/login');
            }
        }

        $this->template->set('ctrl', $ctrl);
        $this->template->set('action', $action);
    }

}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */