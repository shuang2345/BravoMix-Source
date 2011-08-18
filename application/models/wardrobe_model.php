<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 衣櫃資料模型
 *
 * @author Liao San-Kai
 */
class Wardrobe_model extends CI_Model {

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
    }

    //--------------------------------------------------------------------------
    /**
     * 取得使用者的基本衣櫃的代碼
     * 
     * @return int 衣櫃代碼
     */
    public function user_basic($user_id=NULL)
    {
        $this->db->select('wardrobe_id');
        $this->db->from('wardrobes');
        $this->db->where('wardrobe_type', 'basic');
        $this->db->where('wardrobe_user_id', $user_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            $row = $query->row_array();
            return $row['wardrobe_id'];
        }
        else
        {
            return 0;
        }
    }

    //--------------------------------------------------------------------------
    /**
     * 建立新衣櫃
     * 
     * @param String $title 衣櫃名稱
     * @param String $type 衣櫃類型
     * @param int $user_id 衣櫃擁有者代碼
     * @return int 影響的筆數
     */
    public function create($title=NULL, $type=NULL, $user_id=NULL)
    {
        $data = array(
            'wardrobe_title' => $title,
            'wardrobe_type' => $type,
            'wardrobe_user_id' => $user_id,
            'add_time' => time(),
        );
        $this->db->insert('wardrobes', $data);
        return $this->db->insert_id();
    }

    //--------------------------------------------------------------------------
    /**
     * 找出衣櫃中的單品
     * 
     * @param int $wardrobe_id 衣櫃代碼
     * @param String $tag_title 標籤名稱
     * @return array
     */
    public function find_items($wardrobe_id=NULL, $tag_title=NULL)
    {
        $tag_title = urldecode($tag_title);
        if ($tag_title)
        {
            $tag_id = $this->_find_tag_id($tag_title);
            $this->db->where('wardrobes_items.tag_id', $tag_id);
        }
        $this->db->distinct();
        $this->db->select('items.*,wardrobes_items.add_time');
        $this->db->from('wardrobes_items');
        $this->db->join('items', 'wardrobes_items.item_id = items.item_id');
        $this->db->where('wardrobes_items.wardrobe_id', $wardrobe_id);
        $this->db->order_by('wardrobes_items.add_time', 'DESC');
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }

    //--------------------------------------------------------------------------
    /**
     * 找出衣櫃的單品標籤
     * 
     * @param int $wardrobe_id 衣櫃代碼
     * @return array
     */
    public function find_tags($wardrobe_id=NULL)
    {
        $this->db->distinct();
        $this->db->select('tags.tag_id, tags.tag_title');
        $this->db->from('wardrobes_items');
        $this->db->join('tags', 'wardrobes_items.tag_id = tags.tag_id');
        $this->db->where('tags.tag_flag', 'wardrobe');
        $this->db->where('wardrobes_items.wardrobe_id', $wardrobe_id);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }

    //--------------------------------------------------------------------------
    /**
     * 找到標籤名稱的ID
     * 
     * @param String $tag_title 標籤名稱
     * @return int 標籤代碼
     */
    private function _find_tag_id($tag_title='')
    {
        $this->db->select('tag_id');
        $this->db->from('tags');
        $this->db->where('tag_flag', 'wardrobe');
        $this->db->where('tag_title', trim($tag_title));
        $query = $this->db->get();
        if ($query->num_rows())
        {
            return $query->row()->tag_id;
        }
        return 0;
    }

    //--------------------------------------------------------------------------
    /**
     * 新增單品到衣櫃
     * 
     * @param int $item_id 單品代碼
     * @param int $type 衣櫃代碼
     * @param String $tag_title 標籤名稱
     * @return 
     */
    public function is_exists($item_id=NULL, $wardrobe_id=NULL, $tag_title=NULL)
    {
        $tag_title = urldecode($tag_title);
        if ($tag_title)
        {
            $tag_id = $this->_find_tag_id($tag_title);
            $this->db->where('wardrobes_items.tag_id', $tag_id);
        }
        //加至衣櫃之前先檢查是否已經存在
        $this->db->select('wardrobe_id');
        $this->db->from('wardrobes_items');
        $this->db->where('wardrobe_id', $wardrobe_id);
        $this->db->where('item_id', $item_id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    //--------------------------------------------------------------------------
    /**
     * 新增單品到衣櫃
     * 
     * @param int $item_id 單品代碼
     * @param int $type 衣櫃代碼
     * @param String $tag_title 標籤名稱
     * @return 
     */
    public function add($item_id=NULL, $wardrobe_id=NULL, $tag_title=NULL)
    {
        //標籤名稱是否存在，若沒有就新增，有就直接取得代碼來使用
        $tag_id = $this->_find_tag_id($tag_title);
        if (!$tag_id)
        {
            $tag_data = array(
                'tag_title' => $this->security->xss_clean($tag_title),
                'tag_flag' => 'wardrobe',
                'add_time' => time(),
            );
            $this->db->insert('tags', $tag_data);
            $tag_id = $this->db->insert_id();
        }
        //如果此單品未在衣櫃中，就加入
        if ($this->is_exists($item_id, $wardrobe_id, $tag_title))
        {
            $new_add = array(
                'wardrobe_id' => $wardrobe_id,
                'item_id' => $item_id,
                'tag_id' => $tag_id,
                'add_time' => time(),
            );
            $this->db->insert('wardrobes_items', $new_add);
            return $this->db->affected_rows();
        }
        else
        {
            return 0;
        }
    }

}

/* End of file file_model.php */
/* Location: ./application/models/file_model.php */