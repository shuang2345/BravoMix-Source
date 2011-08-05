<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 單品資料模型
 *
 * @author Liao San-Kai
 */
class Item_model extends Oborci_Model {

    /**
     * 資料表名稱
     * 
     * @var String
     */
    protected $db_table = 'items';

    /**
     * 資料表結構映射表
     * 
     * map => field
     * @var array
     */
    protected $db_fields = array(
        //資料識別碼(PK)
        'id' => 'item_id',
        //單品名稱
        'title' => 'item_title',
        //單品售價
        'price' => 'item_price',
        //單品描述
        'describe' => 'item_describe',
        //單品被按讚次數
        'count_nice' => 'item_nice_count',
        //單品分享FB次數
        'count_fb' => 'item_fb_count',
        //單品檢視次數
        'count_view' => 'item_views',
        //單品建立者(FK)
        'creator' => 'item_user_id',
        //建立時間
        'created_on' => 'add_time',
        //最後更新
        'updated_on' => 'edit_time',
    );

    /**
     * 資料主鍵(Primary Key)
     * 
     * 輸入映射表PK欄位的Key值，非資料表原始欄位名稱，假如映射表如下:
     * protected $db_fields = array(
     *     'pkid' => 'id',
     * )
     * 那麼資料主鍵應設定為mypk而不是id，如下:
     * protected $db_primary_key = 'pkid';
     * 
     * @var String
     */
    protected $db_primary_key = 'id';

    /**
     * 關聯表
     * 
     * @var array
     */
    protected $db_relations = array(
        //單品的圖片
        'file_model' => array(
            'relation' => 'has_and_belongs_to_many',
            'join_table' => 'files_items',
            'join_key' => 'item_id',
            'key' => 'file_id',
        ),
        //單品的標籤
        'tag_model' => array(
            'relation' => 'has_and_belongs_to_many',
            'join_table' => 'items_tags',
            'join_key' => 'item_id',
            'key' => 'tag_id',
        ),
        //混搭的內容物
        'mix_model' => array(
            'relation' => 'has_and_belongs_to_many',
            'join_table' => 'mixs_items',
            'join_key' => 'item_id',
            'key' => 'mix_id',
        ),
        //衣櫃的內容物
        'wardrobe_model' => array(
            'relation' => 'has_and_belongs_to_many',
            'join_table' => 'wardrobes_items',
            'join_key' => 'item_id',
            'key' => 'wardrobe_id',
        ),
    );

    /**
     * 建構子
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Modify callback before_insert()
     */
    public function before_insert($data)
    {
        log_message('debug', 'Data will be inserted');
        $data = parent::before_insert($data);
        return $data;
    }

    /**
     * Modify callback after_insert()
     */
    public function after_insert($data, $returns)
    {
        log_message('debug', 'Data has been inserted');
        $returns = parent::after_insert($data, $returns);
        return $returns;
    }

    /**
     * Modify callback before_update()
     */
    public function before_update($id, $data)
    {
        log_message('insert', 'Data will be updated');
        if ($data['prio'] == 'high')
        {
            log_message('debug', 'id: ' . $id . ' -- PRIORITY ALERT !');
        }
        $data = array($id, $data);
        return $data;
    }

    /**
     * Modify callback after_update()
     */
    public function after_update($id, $data, $returns)
    {
        log_message('insert', 'Data has been updated');
        return $returns;
    }

}

/* End of file item_model.php */
/* Location: ./application/models/item_model.php */