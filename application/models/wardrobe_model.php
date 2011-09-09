<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 衣櫃資料模型
 *
 * @author Liao San-Kai <liaosankai@gmail.com>
 */
class Wardrobe_model extends CI_Model {

    /**
     * 驗證規則
     * 
     * @var array
     */
    protected $fields_validation = array();

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
     * 找到標籤名稱的代碼
     * 
     * @param String $tag_title 標籤名稱
     * @param Boolean $auto_create 自動建立
     * @return int 標籤代碼
     */
    private function _find_tag_id($tag_title='', $auto_create=FALSE)
    {
        //若為空字串，就直接回傳0
        if (!mb_strlen($tag_title))
        {
            return 0;
        }
        //搜尋並回傳代碼
        $this->db->select('tag_id');
        $this->db->from('tags');
        $this->db->where('tag_flag', 'wardrobe');
        $this->db->where('tag_title', trim($tag_title));
        $query = $this->db->get();
        if ($query->num_rows())
        {
            return $query->row()->tag_id;
        }

        //不存在而且有指定要自動建立，就嘗試建立後再回傳
        if ($auto_create)
        {
            $tag_data = array(
                'tag_title' => $this->security->xss_clean($tag_title),
                'tag_flag' => 'wardrobe',
                'add_time' => time(),
            );
            $this->db->insert('tags', $tag_data);
            return $this->db->insert_id();
        }
        //找不到符合標籤，回傳0
        return 0;
    }

    //--------------------------------------------------------------------------
    /**
     * 取得收錄代碼(單品在衣櫃中的代碼)
     * 
     * @param int $item_id 單品代碼
     * @param int $wardrobe_id 衣櫃代碼
     * @return int 收錄代碼
     */
    private function _find_record_id($item_id=NULL, $wardrobe_id=NULL)
    {
        $this->db->select('wardrobe_item_id');
        $this->db->from('wardrobes_items');
        $this->db->where('wardrobe_id', $wardrobe_id);
        $this->db->where('item_id', $item_id);
        $query = $this->db->get();
        if ($query->num_rows())
        {
            return $query->row()->wardrobe_item_id;
        }
        return 0;
    }

    //--------------------------------------------------------------------------
    /**
     * 新增單品到衣櫃
     * 
     * @param int $item_id 單品代碼
     * @param int $wardrobe_id 衣櫃代碼
     * @param string $tag_title 標籤名稱
     * @return int 影響的筆數 
     */
    public function add_item($wardrobe_id=NULL, $item_id=NULL, $tag_title=NULL)
    {
        //單品尚未在衣櫃中(沒有此收錄)，才進行加入的動作
        if (!$this->_find_record_id($item_id, $wardrobe_id))
        {
            $add_new_item = array(
                'wardrobe_id' => $wardrobe_id,
                'item_id' => $item_id,
                'add_time' => time(),
            );
            $this->db->insert('wardrobes_items', $new_add);

            //若有指定標籤，為此單品貼上標籤
            if (mb_strle($tag_title))
            {
                $this->paste_tag($item_id, $wardrobe_id, $tag_title);
            }
        }
        return $this->db->affected_rows();
    }

    //--------------------------------------------------------------------------
    /**
     * 從衣櫃中移除單品
     * 
     * @param int $item_id 單品代碼
     * @param int $wardrobe_id 衣櫃代碼
     * @return int 影響的筆數 
     */
    public function remove_item($wardrobe_id=NULL, $item_id=NULL)
    {
        //單品必需在衣櫃中(有此收錄)，才進行加入的動作
        $record_id = $this->_find_record_id($item_id, $wardrobe_id);
        if ($record_id)
        {
            //從衣櫃中移除
            $this->db->where('wardrobe_item_id', $record_id);
            $this->db->delete('wardrobes_items');
            //移除相關的標籤
            $this->db->delete('wardrobes_items_tags');
        }
        return $this->db->affected_rows();
    }

    //--------------------------------------------------------------------------
    /**
     * 檢查是否已經有此標籤
     * 
     * @param string $tag_title 標籤名稱
     * @param int $item_id 單品代碼
     * @param int $wardrobe_id 衣櫃代碼
     * @return int 0=沒有,0之外=有
     */
    public function check_tag($tag_title=NULL, $item_id=NULL, $wardrobe_id=NULL)
    {
        if ($wardrobe_id)
        {
            $tag_id = $this->_find_tag_id($tag_title, TRUE);
            if ($item_id)
            {
                $record_id = $this->_find_record_id($item_id, $wardrobe_id);
                if ($record_id)
                {

                    $this->db->from('wardrobes_items_tags');
                    $this->db->where('wardrobe_item_id', $record_id);
                    $this->db->where('tag_id', $tag_id);
                    return $this->db->count_all_results();
                }
            }
            else
            {
                $this->db->from('wardrobes_tags');
                $this->db->where('wardrobe_id', $wardrobe_id);
                $this->db->where('tag_id', $tag_id);
                return $this->db->count_all_results();
            }
        }
        return 0;
    }

    //--------------------------------------------------------------------------
    /**
     * 為單品貼上標籤
     * 
     * @param string $tag_title 標籤名稱
     * @param int $item_id 單品代碼
     * @param int $wardrobe_id 衣櫃代碼
     * @return int
     */
    public function paste_tag($tag_title=NULL, $item_id=NULL, $wardrobe_id=NULL)
    {
        //先檢查衣櫃是否已經有此標籤
        if (!$this->check_tag($tag_title, NULL, $wardrobe_id))
        {
            //取得標籤代碼
            $tag_id = $this->_find_tag_id($tag_title, TRUE);
            $add_new_tag = array(
                'wardrobe_id' => $wardrobe_id,
                'tag_id' => $tag_id,
                'add_time' => time(),
            );
            $this->db->insert('wardrobes_tags', $add_new_tag);
        }
        //先檢查單品是否已經有此標籤
        if (!$this->check_tag($tag_title, $item_id, $wardrobe_id))
        {
            //取得收錄代碼
            $record_id = $this->_find_record_id($item_id, $wardrobe_id);
            if ($record_id)
            {
                //取得標籤代碼
                $tag_id = $this->_find_tag_id($tag_title, TRUE);
                $add_new_tag = array(
                    'wardrobe_item_id' => $record_id,
                    'tag_id' => $tag_id,
                    'add_time' => time(),
                );
                $this->db->insert('wardrobes_items_tags', $add_new_tag);
            }
        }
        return $this->db->affected_rows();
    }

    //--------------------------------------------------------------------------
    /**
     * 撕掉衣櫃單品上的標籤
     * 
     * @param string $tag_title 標籤名稱 
     * @param int $wardrobe_id 衣櫃代碼
     * @param int $item_id 單品代碼
     * @return int 影響的筆數 
     */
    public function tear_tag($tag_title=NULL, $wardrobe_id=NULL, $item_id=NULL)
    {
        //取得收錄代碼
        $record_id = $this->_find_record_id($item_id, $wardrobe_id);

        //若收錄代碼存在，撕掉此收錄上的標籤
        if ($record_id)
        {
            //取得標籤代碼
            $tag_id = $this->_find_tag_id($tag_title, TRUE);
            //
            $this->db->where('tag_id', $tag_id);
            $this->db->where('wardrobe_item_id', $record_id);
            $this->db->delete('wardrobes_items_tags');
        }
        return $this->db->affected_rows();
    }

    //--------------------------------------------------------------------------
    /**
     * 取得登入者的衣櫃的代碼
     * 
     * @param int $user_id 使用者代碼
     * @param boolean $auto_create 是否自動建立
     * @return int 衣櫃代碼
     */
    public function find_loginer_wardbore($user_id=NULL, $auto_create=FALSE)
    {
        $this->db->select('wardrobe_id');
        $this->db->from('wardrobes');
        $this->db->where('wardrobe_type', 'basic');
        $this->db->where('wardrobe_user_id', $user_id);
        $query = $this->db->get();
        //回傳衣櫃代碼
        if ($query->num_rows() > 0)
        {
            return $query->row()->wardrobe_id;
        }
        //如果有說要自動建立，就建立後再回傳衣櫃代碼
        if ($auto_create)
        {
            return $this->create_wardbore('我的衣櫃', 'basic', $user_id);
        }
        //沒有衣櫃代碼，回傳0
        return 0;
    }

    //--------------------------------------------------------------------------
    /**
     * 建立新衣櫃
     * 
     * @param String $title 衣櫃名稱
     * @param String $type 衣櫃類型
     * @param int $user_id 衣櫃擁有者代碼
     * @return int 衣櫃的代碼
     */
    public function create_wardbore($title=NULL, $type=NULL, $user_id=NULL)
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
     * @todo 這邊效能如果很差，可以考慮直接刻SQL了
     * 
     * @param int $wardrobe_id 衣櫃代碼
     * @param string $tag_title 標籤名稱
     * @return array 結果陣列，若無資料會回傳空陣列
     */
    public function find_items($wardrobe_id=NULL, $tag_title=NULL)
    {
        //若有指定標籤，追加過濾條件
        if (mb_strlen($tag_title))
        {
            //找出此標籤名稱的ID
            $tag_id = $this->_find_tag_id($tag_title);
            //找出擁有此標籤的收錄代碼
            $this->db->select('wardrobe_item_id');
            $this->db->from('wardrobes_items_tags');
            $this->db->where('tag_id', $tag_id);
            $query = $this->db->get();
            if ($query->num_rows() > 0)
            {
                foreach ($query->result_array() as $row)
                {
                    $ids[] = $row['wardrobe_item_id'];
                };
                $this->db->where_in('wardrobe_item_id', $ids);
            }
            else
            {
                return array();
            }
        }

        //建立SQL查詢
        $this->db->distinct();
        $this->db->select('items.item_id, item_title, item_cover, item_describe, item_price');
        $this->db->from('wardrobes_items');
        $this->db->join('items', 'wardrobes_items.item_id = items.item_id');
        $this->db->where('wardrobes_items.wardrobe_id', $wardrobe_id);
        $this->db->order_by('wardrobes_items.add_time', 'DESC');

        //取得結果集
        $query = $this->db->get();
        $this->db->last_query() . "\n";
        $result = $query->result_array();

        //追加讀取出每個單品擁有的標籤(tags)
        foreach ($result as $key => $row)
        {
            $result[$key]['item_tags'] = $this->find_tags($wardrobe_id, $row['item_id']);
        }

        $query->free_result();
        return $result;
    }

    //--------------------------------------------------------------------------
    /**
     * 找出衣櫃用過的標籤，若有指定單品，會僅列單品的部分
     * 
     * @todo 這邊在找單品標籤直接刻了SQL來用，若資料庫有移植會有問題，要注意
     * 
     * @param int $wardrobe_id 衣櫃代碼
     * @param int $wardrobe_id 單品代碼
     * @return array 結果陣列，若無資料會回傳空陣列
     */
    public function find_tags($wardrobe_id=NULL, $item_id=NULL)
    {
        if ($wardrobe_id)
        {
            if ($item_id)
            {
                $sql = "SELECT tag_title "
                        . "FROM tags "
                        . "JOIN ( "
                        . "   SELECT wit.tag_id AS tag_id "
                        . "   FROM wardrobes_items_tags AS wit "
                        . "   JOIN wardrobes_items AS wi "
                        . "   ON wit.wardrobe_item_id = wi.wardrobe_item_id "
                        . "   WHERE wi.wardrobe_id = {$wardrobe_id} AND wi.item_id = {$item_id} "
                        . ") AS r "
                        . "ON tags.tag_id = r.tag_id ";
                $query = $this->db->query($sql);
                if ($query->num_rows() > 0)
                {
                    return $query->result_array();
                }
            }
            else
            {
                $this->db->select('tag_title');
                $this->db->from('wardrobes_tags');
                $this->db->join('tags', 'wardrobes_tags.tag_id = tags.tag_id');
                $this->db->where('wardrobes_tags.wardrobe_id', $wardrobe_id);
                $query = $this->db->get();
                if ($query->num_rows() > 0)
                {
                    return $query->result_array();
                }
            }
        }
        return array();
    }

}

/* End of file file_model.php */
/* Location: ./application/models/file_model.php */