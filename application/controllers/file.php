<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 檔案控制
 *
 * @author Liao San-Kai
 */
class File extends CI_Controller {

    /**
     * 檔案上傳設定
     */
    private $upload_config = array(
        //上傳路徑
        'upload_path' => './uploads/',
        //合法的檔案類型
        'allowed_types' => 'gif|jpg|png',
        //限定檔案大小，0=不限定
        'max_size' => '1024KB',
        //限定寬度(圖片時)，0=不限定
        'max_width' => '2560',
        //限定高度(圖片時)，0=不限定
        'max_height' => '2048',
        //限定檔名長度，0=不限定
        'max_filename' => '0',
        //重新亂數命名
        'encrypt_name' => TRUE,
        //移除檔名中的空白
        'remove_spaces' => TRUE,
    );

    /**
     * 建構子
     */
    function __construct()
    {
        parent::__construct();

        $this->load->spark('Oborci/0.9');
        $this->load->model('file_model');
    }

    /**
     * 預設動作
     * 
     * @param $filename 實體檔名
     */
    public function index($filename=NULL, $width=NULL, $height=NULL)
    {
		$this->get($filename);
    }

    /**
     * 取得檔案
     * 
     * 若為圖片會直接顯示，其它檔案變成下載
     * 
     * @param $filename 實體檔名
     * @param $width 縮圖寬度(圖片限定)
     * @param $height 縮圖高度(圖片限定)
     */
    public function get($filename=NULL, $width=NULL, $height=NULL)
    {
		//實體檔案位置
		$filepath = $this->upload_config['upload_path'].$filename;
		
		//如果有限定寬度和高度，檢查是否有縮圖
		if($width && $height){
			//取得縮圖位置
			$fileinfo = pathinfo($filepath);
			$filepath = $fileinfo['dirname'].'/thumbs/'.$fileinfo['filename']
					  . '_'.join('_',array($width,$height,'thumb'))
				      . '.'.$fileinfo['extension'];
			//如果縮圖存在，顯示縮圖，不存則建立縮圖
			if(file_exists($filepath)){
				$filepath = base_url().$filepath;
				header("Location: {$filepath}");
			} else {
				//縮圖建立成功就顯示
				if($this->_create_thumb($filename,$width,$height)){
					$this->get($filename,$width,$height);
				} else {
					header('HTTP/1.1 404 Not Found');
				}
			}			
		} else {
			if(file_exists($filepath)){
				$filepath = base_url().$filepath;
				header("Location: {$filepath}");
			} else {
				header('HTTP/1.1 404 Not Found');
			}
		}
    }

    /**
     * 建立縮圖
     * 
     * @param $filename 實體檔名
     * @param $width 縮圖寬度(圖片限定)
     * @param $height 縮圖高度(圖片限定)
	 * @return Boolean 建立結果
     */
    public function _create_thumb($filename=NULL, $width=NULL, $height=NULL)
    {
		//取得實體檔案位置
		$source_image = $this->upload_config['upload_path'].$filename;
		//分析檔案資訊
		$fileinfo = pathinfo($source_image);
		//定義新縮圖位置(這邊要略過thumb)
		$new_image = $fileinfo['dirname'].'/thumbs/'.$fileinfo['filename']
		           . '_'.join('_',array($width,$height))
				   . '.'.$fileinfo['extension'];
		$thumb_config = array(
			'create_thumb' => TRUE,
			'source_image' => $source_image,
			'width' => $width,
			'height' => $height,
			'new_image' => $new_image
		);
		$this->load->library('image_lib', $thumb_config);
		return $this->image_lib->resize();
    }
	
    /**
     * 取得指定檔案的資訊
     * 
     * @param $filename 實體檔名
     */
    public function info($filename=NULL)
    {
        
    }

    /**
     * 刪除檔案
     * 
     * 若為Ajax模式請求，結果將會以JSON輸出
     * @param $filename 實體檔名
     */
    public function delete($filename=NULL)
    {

    }

    /**
     * 上傳檔案頁面
     */
    public function upload()
    {
        $this->load->view('file/upload_form', array('error' => ''));
    }

    /**
     * 上傳檔案動作
     * 
     * 若為Ajax模式請求，結果將會以JSON輸出
	 *
	 * ※目前AjaxForm送出都是用隱藏的iframe包住再送出
	 *   所以$this->input->is_ajax_request()是無效的
	 *   因為沒有HTTP_X_REQUESTED_WITH=XMLHttpRequest的header
     * 
     * $param String $field_name 自訂的檔案欄位名稱
	 * $param String $json_encode 將結果輸出成JSON格式
     */
    public function put($field_name='userfile',$json_encode=FALSE)
    {
	    //若有POST或GET的資料，改用POST或GET的資料
		$field_name = ($this->input->get_post('field_name'))?
					  $this->input->get_post('field_name'):$field_name;
		$json_encode = ($this->input->get_post('json_encode'))?
					$this->input->get_post('json_encode'):$json_encode;
		
		//載入Upload類別庫
		$this->load->library('upload', $this->upload_config);

        //建立回應內容
        $data = array();
        if (!$this->upload->do_upload($field_name))
        {
            //儲入執行結果與錯誤訊息
            $data['result'] = FALSE;
            $data['error'] = $this->upload->display_errors();
			$data['upload_data'] = array();
        }
        else
        {
            //儲入執行結果與成功的檔案資訊
            $data['result'] = TRUE;
			$data['error'] = NULL;
            $data['upload_data'] = $this->upload->data();
            $this->file_model->insert($data['upload_data']);
        }
        //判斷請求模式給予不同格式輸出
        if ($json_encode)
        {
            echo json_encode($data);
			exit;
        }
        else if($_FILES)
        {
			$this->load->view('file/upload_success', $data);
        }
		else 
		{
			$this->upload();
		}
    }

}

/* End of file file.php */
/* Location: ./application/controllers/file.php */