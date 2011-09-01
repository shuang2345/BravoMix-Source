<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 混搭控制器
 *
 *
 * @author Liao San-Kai
 */
class Mix extends MY_Controller {

    /**
     * 建構子
     */
    function __construct()
    {
        parent::__construct();

        $this->load->helper('array');
        $this->load->model('wardrobe_model');
        $this->load->model('mix_model');
    }

    //--------------------------------------------------------------------------
    /**
     * 檢視混搭
     *
     * @param int $mix_id 混搭代碼
     */
    public function view($mix_id=NULL)
    {
        //若有POST或GET的資料，改用POST或GET的資料
        $mix_id = ($this->input->get_post('mix_id')) ?
                $this->input->get_post('mix_id') : $mix_id;

        //取出指定混搭代碼內的所有單品
        $mix_items = $this->mix_model->find_items($mix_id);

        //組合視圖變數
        $data['mix_items'] = $mix_items;
        $data['mix_id'] = $mix_id;

        //處理Ajax請求模式
        if ($this->input->is_ajax_request())
        {
            $respone['result'] = TRUE;
            $respone['data'] = $data;
            echo json_encode($respone);
            exit;
        }
        else
        {
            $this->template->render('mix/view', $data);
        }
    }

    //--------------------------------------------------------------------------
    /**
     * 混搭清單
     */
    public function roll($limit=6, $orderby='add_time', $vector='DESC', $offset=0)
    {

    }

    //--------------------------------------------------------------------------
    /**
     * 編輯混搭
     *
     * @param int $mix_id 混搭代碼
     */
    public function edit($mix_id=NULL)
    {
        //讀取指定的衣櫃中的所有單品
        $items = $this->wardrobe_model->find_items($this->loginer->wardrobe_id, null);
        //取得指定衣櫃可選用的所有標籤
        $tags = $this->wardrobe_model->find_tags($this->loginer->wardrobe_id);

        //將代碼數字化(用來避免輸入數字之外的參數導致出錯)        
        $mix_id = intval($mix_id);

        //混搭中的單品
        $mix_items = $this->mix_model->find_items($mix_id);

        //組合視圖變數
        $data['items'] = $items;
        $data['tags'] = $tags;
        $data['mix_id'] = $mix_id;

        //繪出視圖
        $this->template->add_js('/assets/js/jquery-ui/jquery-ui-1.8.16.custom.min.js');
        $this->template->add_css('/assets/js/jquery-ui/styles/ui-lightness/jquery-ui-1.8.16.custom.css');
        $this->template->set_layout('template/layout/1-col');
        $this->template->render('mix/edit', $data);
    }

    //--------------------------------------------------------------------------
    /**
     * 儲存混搭
     *
     * @param int $mix_id 混搭代碼
     */
    public function save($mix_id=NULL)
    {
        //若有POST或GET的資料，改用POST或GET的資料
        $mix_id = ($this->input->get_post('mix_id')) ?
                $this->input->get_post('mix_id') : $mix_id;

        //檢查混搭代碼
        $mix_id = $this->mix_model->check_id($mix_id, TRUE);

        //刪除指定代碼混搭的所有單品
        $this->mix_model->remove_item($mix_id);

        //逐一追加每個單品
        foreach ($this->input->get_post('mix_items') as $key => $item)
        {
            $this->mix_model->add_item($mix_id, $item);
        }
        //輸出結果
        if ($this->input->is_ajax_request())
        {
            $respone['result'] = TRUE;
            $respone['mix_id'] = $mix_id;
            echo json_encode($respone);
            exit;
        }
    }

}

/* End of file item.php */
/* Location: ./application/controllers/item.php */