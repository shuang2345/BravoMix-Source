<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 單品資料模型
 *
 * @author Liao San-Kai
 */
class Item_model extends CI_Model {

    /**
     * 驗證規則
     * 
     * @var array
     */
    protected $fields_validation = array(
        //單品名稱
        'item_title' => array(
            'field' => 'item_title',
            'label' => 'Item Title',
            'rules' => 'trim|required|max_length[24]|xss_clean'
        ),
        //單品品牌
        'item_brand' => array(
            'field' => 'item_brand',
            'label' => 'Item Brand',
            'rules' => 'trim|xss_clean'
        ),
        //單品連結
        'item_link' => array(
            'field' => 'item_link',
            'label' => 'Item Link',
            'rules' => 'trim|xss_clean'
        ),
        //單品售價
        'item_price' => array(
            'field' => 'item_price',
            'label' => 'Item Price',
            'rules' => 'trim|is_natural|xss_clean'
        ),
        //單品描述
        'item_describe' => array(
            'field' => 'item_describe',
            'label' => 'Item Describe',
            'rules' => 'trim|xss_clean'
        ),
        //單品封面
        'item_cover' => array(
            'field' => 'item_cover',
            'label' => 'Item Cover',
            'rules' => 'trim|xss_clean'
        ),
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
     * 儲存單品資料
     * @param Array $data 儲存的資料
     * @return Boolean $result 儲存結果
     */
    public function save($data)
    {
        //檢查資料
        $this->form_validation->set_rules($this->fields_validation);
        //檢查不通過，把錯誤訊息加入後回傳
        if ($this->form_validation->run() == FALSE)
        {
            $data['save_result'] = FALSE;
            $data['validation_error'] = validation_errors();
            return $data;
        }
        else
        {
            //將每個資料進行XSS過濾
            foreach ($this->fields_validation as $key => $rule)
            {
                if (isset($data[$rule['field']]))
                {
                    $val = $data[$rule['field']];
                    $savedata[$rule['field']] = $this->security->xss_clean($val);
                }
            }
            //如果有單品代碼，進行更新
            if ($data['item_id'])
            {
                $savedata['edit_time'] = time();
                $old_data = $this->find($data['item_id']);
                $data = $savedata + $old_data;
                $this->db->where('item_id', $data['item_id']);
                $this->db->update('items', $savedata);
            }
            else
            {
                $savedata['add_time'] = time();
                $this->db->insert('items', $savedata);
                $data['item_id'] = $this->db->insert_id();
                $data = $this->find($data['item_id']);
            }
            $data['save_result'] = TRUE;
            return $data;
        }
    }

    //--------------------------------------------------------------------------
    /**
     * 找出單品資料
     * 
     * @param int $item_id 單品代碼
     * @return Array
     */
    public function find($item_id=NULL)
    {
        $query = $this->db->get_where('items', array('item_id' => $item_id), 1, 0);
        $result = $query->row_array();
        $query->free_result();
        return $result;
    }

    //--------------------------------------------------------------------------
    /**
     * 找出所有單品
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
        $this->db->from('items');
        $this->db->limit($limit, $offset);
        $this->db->order_by($orderby, $vector);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        //如果單品未設定封面，就自動使用其中一張來當封面
        foreach ($result as $key => $item)
        {
            if ($item['item_cover'] == '')
            {
                $image = $this->find_images($item['item_id'], 1);
                if (count($image))
                {
                    $item['item_cover'] = $image[0]['file_name'];
                    $result[$key] = $item;
                }
            }
        }
        return $result;
    }

    //--------------------------------------------------------------------------
    /**
     * 找出單品標籤
     * 
     * @param int $item_id 單品代碼
     * @param String $flag 標籤標記
     * @param int $limit 數量
     * @return Array
     */
    public function find_tags($item_id=NULL, $flag=NULL, $limit=NULL)
    {
        $this->db->select('tags.tag_id,tag_title,tag_flag');
        $this->db->from('tags');
        $this->db->join('items_tags', 'tags.tag_id = items_tags.tag_id');
        $this->db->where('item_id', $item_id);
        $this->db->where('tag_flag', $flag);
        $this->db->order_by('tag_title', 'DESC');
        if ($limit)
        {
            $this->db->limit($limit);
        }
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }

    //--------------------------------------------------------------------------
    /**
     * 清空單品所有標籤
     * 
     * @param int $item_id 單品代碼
     * @param String $tag_flag 標籤標誌
     * @return int 影響的列數
     */
    public function clear_tags($item_id=NULL, $tag_flag=NULL)
    {
        $clear_tags = $this->find_tags($item_id, $tag_flag);
        if (count($clear_tags))
        {
            $del_ids = array();
            foreach ($clear_tags as $tag)
            {
                $del_ids[] = $tag['tag_id'];
            }
            $sql = "DELETE FROM items_tags WHERE item_id = {$item_id} AND tag_id IN (" . join(',', $del_ids) . ")";
            $this->db->query($sql);
            return $this->db->affected_rows();
        }
        return 0;
    }

    //--------------------------------------------------------------------------
    /**
     * 追加標籤至單品
     * 
     * @param int $item_id 單品代碼
     * @param int $tag_title 標籤名稱
     * @param String $tag_flag 標籤標誌
     * @return int 影響的列數
     */
    public function add_tag($item_id=NULL, $tag_title=NULL, $tag_flag=NULL)
    {
        if (strlen(trim($tag_title)))
        {
            //標籤名稱是否存在，若沒有就新增，有就直接取得代碼來使用
            $this->db->select('tag_id');
            $this->db->from('tags');
            $this->db->where('tag_flag', $tag_flag);
            $this->db->where('tag_title', $tag_title);
            $query = $this->db->get();
            if ($query->num_rows())
            {
                $tag_id = $query->row()->tag_id;
            }
            else
            {
                $tag_data = array(
                    'tag_title' => $this->security->xss_clean($tag_title),
                    'tag_flag' => $this->security->xss_clean($tag_flag),
                    'add_time' => time(),
                );
                $this->db->insert('tags', $tag_data);
                $tag_id = $this->db->insert_id();
            }
            //貼上新標籤，若沒有就貼上，有就略過
            $this->db->select('item_id');
            $this->db->from('items_tags');
            $this->db->where('tag_id', $tag_id);
            $query = $this->db->get();
            if ($query->num_rows() == 0)
            {
                $new_tag = array(
                    'tag_id' => $tag_id,
                    'item_id' => $item_id,
                );
                $this->db->insert('items_tags', $new_tag);
            }
            return $this->db->affected_rows();
        }
        return 0;
    }

    //--------------------------------------------------------------------------
    /**
     * 找出單品的圖片
     * 
     * @param int $item_id 單品代碼
     * @param int $limit 圖片數量
     * @return Array
     * 
     * TODO: 這邊應該不需要全部SELECT出來
     */
    public function find_images($item_id=NULL, $limit=NULL)
    {
        $this->db->select('*');
        $this->db->from('files');
        $this->db->join('files_items', 'files.file_id = files_items.file_id');
        $this->db->where('item_id', $item_id);
        $this->db->order_by('file_name', 'DESC');
        if ($limit)
        {
            $this->db->limit($limit);
        }
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }

    //--------------------------------------------------------------------------
    /**
     * 清空單品圖片
     * 
     * 這個動作只會移除關聯，不會刪除實體圖片檔案，但會將實體圖片更新為「未確認」
     * 
     * @param int $item_id 單品代碼
     * @return int 影響的列數
     * 
     * TODO: 應追加登入者(建立者)的帳號判斷
     */
    public function clear_images($item_id=NULL)
    {
        $clear_images = $this->find_images($item_id);
        if (count($clear_images))
        {
            $del_ids = array();
            foreach ($clear_images as $image)
            {
                $del_ids[] = $image['file_id'];
            }
            //刪除關聯
            $sql = "DELETE FROM files_items WHERE item_id = {$item_id} AND file_id IN (" . join(',', $del_ids) . ")";
            $this->db->query($sql);
            //將所有圖片設為未確認
            $sql = 'UPDATE files SET commit = 0 WHERE file_id IN (' . join(',', $del_ids) . ')';
            $this->db->query($sql);
            return $this->db->affected_rows();
        }
        return 0;
    }

    //--------------------------------------------------------------------------
    /**
     * 追加一張圖片至單品
     * 
     * @param int $item_id 單品代碼
     * @param String $filename 圖片實體名稱
     * @return int 影響的列數
     * 
     * TODO: 把未確認的檔案從資料庫與實體刪除
     */
    public function add_image($item_id=NULL, $filename=NULL)
    {
        //圖片名稱是否存在，若沒有就新增，有就直接取得代碼來使用
        $this->db->select('file_id');
        $this->db->from('files');
        $this->db->where('file_name', $filename);
        $query = $this->db->get();
        if ($query->num_rows())
        {
            $new_image = array(
                'file_id' => $query->row()->file_id,
                'item_id' => $item_id
            );
            $this->db->insert('files_items', $new_image);
        }
        //把使用到的檔案進行確認
        if ($this->db->affected_rows())
        {
            $this->db->where('file_id', $new_image['file_id']);
            $this->db->update('files', array('commit' => TRUE));
        }
        return $this->db->affected_rows();
    }

}

/* End of file item_model.php */
/* Location: ./application/models/item_model.php */