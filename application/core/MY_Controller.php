<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */