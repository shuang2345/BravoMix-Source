<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 標籤資料模型
 *
 * @author Liao San-Kai
 */
class Tag_model extends Oborci_Model {

    /**
     * 資料表名稱
     * 
     * @var String
     */
    protected $db_table = 'tags';

    /**
     * 資料表結構映射表
     * 
     * map => field
     * @var array
     */
    protected $db_fields = array(
        //資料識別碼(PK)
        'id' => 'tag_id',
        //標籤名稱
        'title' => 'item_title',
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
        //單品
        'item_model' => array(
            'relation' => 'has_and_belongs_to_many',
            'join_table' => 'items_tags',
            'join_key' => 'tag_id',
            'key' => 'item_id',
        ),
        //混搭
        'mix_model' => array(
            'relation' => 'has_and_belongs_to_many',
            'join_table' => 'mixs_tags',
            'join_key' => 'tag_id',
            'key' => 'mix_id',
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