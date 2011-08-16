<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 佈局控制器
 *
 * @author Liao San-Kai
 */
class MY_Controller extends CI_Controller {

    /**
     * 建構子
     */
    public function __construct()
    {
        parent::__construct();

        //權限判斷
        $this->_auth();

        //
        $this->load->spark('template');

        //初始化各區塊
        /*
        $this->_top();
        $this->_header();
        $this->_navigation();
        $this->_leftcolumn();
        $this->_rightcolumn();
        $this->_navigation();
        $this->_footer();
        $this->_bottom();
        */
    }

    //--------------------------------------------------------------------------
    /**
     * 權限判斷
     * 
     * @todo 依照登入者角色做權限判斷
     */
    private function _auth()
    {
        
    }

    //--------------------------------------------------------------------------
    /**
     * 置頂列
     */
    private function _top()
    {
        $panel = $this->load->view('template/top', NULL, TRUE);
        $this->template->set('top', $panel);
    }

    //--------------------------------------------------------------------------
    /**
     * 頁首
     */
    private function _header()
    {
        $panel = $this->load->view('template/header', NULL, TRUE);
        $this->template->set('header', $panel);
    }

    //--------------------------------------------------------------------------
    /**
     * 導覽頁
     */
    private function _navigation()
    {
        $panel = $this->load->view('template/navigation', NULL, TRUE);
        $this->template->set('navigation', $panel);
    }

    //--------------------------------------------------------------------------
    /**
     * 右欄
     */
    private function _leftcolumn()
    {
        $panel = $this->load->view('template/leftcolumn', NULL, TRUE);
        $this->template->set('leftcolumn', $panel);
    }

    //--------------------------------------------------------------------------
    /**
     * 左欄
     */
    private function _rightcolumn()
    {
        $panel = $this->load->view('template/rightcolumn', NULL, TRUE);
        $this->template->set('rightcolumn', $panel);
    }

    //--------------------------------------------------------------------------
    /**
     * 頁尾
     */
    private function _footer()
    {
        $panel = $this->load->view('template/footer', NULL, TRUE);
        $this->template->set('footer', $panel);
    }

    //--------------------------------------------------------------------------
    /**
     * 置底列
     */
    private function _bottom()
    {
        $panel = $this->load->view('template/bottom', NULL, TRUE);
        $this->template->set('bottom', $panel);
    }

}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */