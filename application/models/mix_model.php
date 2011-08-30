<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 混搭資料模型
 *
 * @author Liao San-Kai
 */
class Mix_model extends CI_Model {

    /**
     * 驗證規則
     * 
     * @var array
     */
    protected $fields_validation = array(
    );

    //--------------------------------------------------------------------------
    /**
     * 建構子
     */
    public function __construct()
    {
        parent::__construct();
        //載入驗證器
        $this->load->library('form_validation');
    }

    //--------------------------------------------------------------------------
    /**
     * 找出混塔的單品內容
     * 
     * @param int $mix_id 混搭代碼
     * @return Array 
     */
    public function find_items($mix_id)
    {
        $this->db->select('mixs_items.item_id, item_title, items.item_cover, item_top, item_left, item_width, item_height, item_zIndex');
        $this->db->from('items');
        $this->db->join('mixs_items', 'items.item_id = mixs_items.item_id');
        $this->db->where('mix_id', $mix_id);
        $query = $this->db->get();
        //echo $this->db->last_query();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }

    //--------------------------------------------------------------------------
    /**
     * 清除指定混搭代碼的所有組合
     * 
     * @param int $mix_id 混搭代碼
     * @return int 
     */
    public function clear_mix($mix_id)
    {
        $sql = "DELETE FROM mixs_items WHERE mix_id = {$mix_id}";
        $this->db->query($sql);
        return $this->db->affected_rows();
    }

    //--------------------------------------------------------------------------
    /**
     * 新增一組
     * 
     * @param int $mix_id 混搭代碼
     * @return int 
     */
    public function add_mix($mix_id, $savedata)
    {
        $savedata['mix_id'] = $mix_id;
        $this->db->insert('mixs_items', $savedata);
    }

}

/* End of file item_model.php */
/* Location: ./application/models/item_model.php */