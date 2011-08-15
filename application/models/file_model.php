<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 檔案資料模型
 *
 * @author Liao San-Kai
 */
class File_model extends CI_Model {

    /**
     * 建構子
     */
    public function __construct()
    {
        parent::__construct();
    }

    //--------------------------------------------------------------------------
    /**
     * 儲存圖片
     * 
     * @param array $data 檔案資訊
     * @param Boolean $commit 是否確認
     * 
     * TODO: 登入者的帳號帶入
     */
    public function save($data=array(), $commit=FALSE)
    {
        $data['add_time'] = time();
        $data['commit'] = $commit;
        $data['creator'] = 'admin';
        $this->db->insert('files', $data);
    }

}

/* End of file file_model.php */
/* Location: ./application/models/file_model.php */