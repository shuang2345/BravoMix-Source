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
     * 找出所有混搭
     *
     * @param int $limit 筆數
     * @param String $orderby 排序
     * @param String $vector 方向
     * @param int $offset 位移
     * @return Array 
     */
    public function find_all($limit=20, $orderby='add_time', $vector='DESC', $offset=0)
    {
        $this->db->select('*');
        $this->db->from('mixs');
        $this->db->limit($limit, $offset);
        $this->db->order_by($orderby, $vector);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    //--------------------------------------------------------------------------
    /**
     * 找出混塔的單品內容
     * 
     * @param int $mix_id 混搭代碼
     * @return Array 搜尋結果，若無資料會回傳空陣列
     */
    public function find_items($mix_id)
    {
        $this->db->select('mixs_items.item_id, item_title, items.item_cover, item_top, item_left, item_width, item_height, item_zIndex');
        $this->db->from('items');
        $this->db->join('mixs_items', 'items.item_id = mixs_items.item_id');
        $this->db->where('mix_id', $mix_id);
        $this->db->order_by('item_zIndex','ASC');
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }

    //--------------------------------------------------------------------------
    /**
     * 將指定的單品代碼從指定混搭代碼的中移除，若沒有指定單品代碼
     * 表示移除所有單品
     * 
     * @param int $mix_id 混搭代碼
     * @return int 
     */
    public function remove_item($mix_id=NULL, $item_id=NULL)
    {
        if ($mix_id)
        {
            if ($item_id)
            {
                $this->db->where('item_id', $item_id);
            }
            $this->db->where('mix_id', $mix_id);
            $this->db->delete('mixs_items');
        }
        return $this->db->affected_rows();
    }

    //--------------------------------------------------------------------------
    /**
     * 追加一個單品至混搭中
     * 
     * @param int $mix_id 混搭代碼
     * @return int 
     */
    public function add_item($mix_id, $savedata)
    {
        $savedata['mix_id'] = $mix_id;
        $this->db->insert('mixs_items', $savedata);
    }

    //--------------------------------------------------------------------------
    /**
     * 檢查混搭代碼的是否存在，若不存在，是否要自動建立？
     * 
     * @param int $mix_id 混搭代碼
     * @param boolean $auto_new 自動建立
     * @return int 若存在，回傳檢查值，不存在回傳0，若自動建立=TRUE，則回傳建立後的新ID
     */
    public function check_id($mix_id, $auto_new=FALSE)
    {
        $this->db->where('mix_id', $mix_id);
        $this->db->from('mixs');
        if ($this->db->count_all_results())
        {
            return $mix_id;
        }
        else
        {
            if ($auto_new)
            {
                $data = array(
                    'mix_describe' => '',
                    'add_time' => time(),
                );
                $this->db->insert('mixs', $data);
                return $this->db->insert_id();
            }
        }
        return 0;
    }

}

/* End of file item_model.php */
/* Location: ./application/models/item_model.php */