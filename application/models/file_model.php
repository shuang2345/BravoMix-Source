<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 檔案資料模型
 *
 * @author Liao San-Kai
 */
class File_model extends Oborci_Model {

    /**
     * 資料表名稱
     * 
     * @var String
     */
    protected $db_table = 'files';

    /**
     * 資料表結構映射表
     * @var array
     */
    protected $db_fields = array(
        //資料識別碼
        'id' => 'file_id',
        //實體名稱(含副檔名)
        'file_name' => 'file_name',
        //檔案類型
        'file_type' => 'file_type',
        //副檔名
        'file_path' => 'file_path',
        //檔案路徑
        'file_path' => 'file_path',
        //完整路徑
        'full_path' => 'full_path',
        //實體名稱(不含副檔名)
        'raw_name' => 'raw_name',
        //原始名稱
        'orig_name' => 'orig_name',
        //客戶端名稱
        'client_name' => 'client_name',
        //副檔名(帶有點號)
        'file_ext' => 'file_ext',
        //檔案大小
        'file_size' => 'file_size',
        //是否為圖片
        'is_image' => 'is_image',
        //圖片寬度
        'image_width' => 'image_width',
        //圖片高度
        'image_height' => 'image_height',
        //圖片類型
        'image_type' => 'image_type',
        //圖片尺寸屬性    
        'image_size_str' => 'image_size_str',
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
     * @var array
     */
    protected $db_created_field = 'created_on';

    /**
     * 關聯表
     * @var array
     */
    protected $db_updated_field = 'updated_on';

    /**
     * 使用時間戳記代替datetime(Y-m-d H:i:s)格式
     * @var array
     */
    protected $unix_timestamp = TRUE;    
    /**
     * 關聯表
     * @var array
     */
    protected $db_relations = array(
        //單品圖片
        'item_model' => array(
            'relation' => 'has_and_belongs_to_many',
            'join_table' => 'files_items',
            'join_key' => 'file_id',
            'key' => 'item_id',
        ),
        //混搭的全身圖
        'mix_model' => array(
            'relation' => 'has_and_belongs_to_many',
            'join_table' => 'files_mixs',
            'join_key' => 'file_id',
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
     * 進行資料新增前的處理
     * 
     * @param $data 欲處理的資料
     * @return $data 處理完的資料
     */
    public function before_insert($data)
    {
        $data = parent::before_insert($data);
        //記錄新增時間(秒數)
        $data['created_on'] = time();
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

/* End of file file_model.php */
/* Location: ./application/models/file_model.php */