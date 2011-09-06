<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 衣櫃控制器
 * 
 * @author Liao San-Kai <liaosankai@gmail.com>
 */
class Wardrobe extends MY_Controller {

    /**
     * 建構子
     */
    function __construct()
    {
        parent::__construct();

        $this->load->helper('array');
        $this->load->model('wardrobe_model');
    }

    //--------------------------------------------------------------------------
    /**
     * 檢視衣櫃
     * 
     * @param string $tag_title 標籤名稱(中文)※需要進行urldecode
     * @param int $wardrobe_id 欲檢視的衣櫃代碼
     */
    public function view($tag_title=NULL, $wardrobe_id=NULL)
    {
        //若有POST或GET的資料，改用POST或GET的資料
        $tag_title = ($this->input->get_post('tag_title')) ?
                urldecode($this->input->get_post('tag_title')) : urldecode($tag_title);
        $wardrobe_id = ($this->input->get_post('wardrobe_id')) ?
                $this->input->get_post('wardrobe_id') : intval($wardrobe_id);

        //若沒有指定衣櫃代碼，使用登入者的衣櫃代碼為預設衣櫃代碼
        $wardrobe_id = ($wardrobe_id) ? $wardrobe_id : $this->loginer->wardrobe_id;

        //從衣櫃中找出符合標籤(tag)的所有單品
        $items = $this->wardrobe_model->find_items($wardrobe_id, $tag_title);

        //取得指定衣櫃可選用的所有標籤
        $tags = $this->wardrobe_model->find_tags($wardrobe_id);

        //組合視圖變數
        $data['items'] = $items;
        $data['tags'] = $tags;
        $data['view_tag_title'] = $tag_title;

        //如果以Ajax方式請求，以JSON格式輸出
        if ($this->input->is_ajax_request())
        {
            $respone['result'] = TRUE;
            $respone['data'] = $data;
            echo json_encode($respone);
            exit;
        }
        //繪出視圖
        $this->template->set_layout('template/layout/1-col');
        $this->template->add_css('/assets/css/gallery/3-mini-paper-clip.css', 'screen');
        $this->template->render('wardrobe/view', $data);
    }

    //--------------------------------------------------------------------------
    /**
     * 衣櫃清單
     * 
     * @todo 由於目前的需求，一位使用者只有一個衣櫃，故此action謹保留
     */
    public function roll()
    {
        
    }

    //--------------------------------------------------------------------------
    /**
     * 追加單品至衣櫃中
     * 
     * 若沒有指定衣櫃代碼，使用登入者預設的衣櫃
     * 
     * @param int $item_id 單品代碼
     * @param string $tag_title 標籤名稱(中文)※需要urldecode
     * @param int $wardrobe_id 衣櫃代碼* 
     */
    public function attach_item($item_id=NULL, $tag_title=NULL, $wardrobe_id=NULL)
    {
        //若有POST或GET的資料，改用POST或GET的資料
        $item_id = ($this->input->get_post('item_id')) ?
                $this->input->get_post('item_id') : intval(item_id);
        $tag_title = ($this->input->get_post('tag_title')) ?
                urldecode($this->input->get_post('tag_title')) : urldecode($tag_title);
        $wardrobe_id = ($this->input->get_post('wardrobe_id')) ?
                $this->input->get_post('wardrobe_id') : intval($wardrobe_id);

        //若沒有指定衣櫃代碼，使用登入者的衣櫃代碼為預設衣櫃代碼
        $wardrobe_id = ($wardrobe_id) ? $wardrobe_id : $this->loginer->wardrobe_id;

        //如果有指定單品代碼，再繼續
        $respone['result'] = $this->wardrobe_model->add_item($item_id, $tag_title, $wardrobe_id);
    
    }

    //--------------------------------------------------------------------------
    /**
     * 從衣櫃移除單品(也就是移除tag)
     * 
     * 若沒有指定衣櫃代碼，使用登入者預設的衣櫃
     * 
     * @param int $item_id 單品代碼
     * @param int $tag_title 標籤名稱(中文)※需要urldecode
     * @param int $wardrobe_id 衣櫃代碼 
     */
    public function remove_item($item_id=NULL, $tag_title=NULL, $wardrobe_id=NULL)
    {
        //若有POST或GET的資料，改用POST或GET的資料
        $item_id = ($this->input->get_post('item_id')) ?
                $this->input->get_post('item_id') : intval(item_id);
        $tag_title = ($this->input->get_post('tag_title')) ?
                urldecode($this->input->get_post('tag_title')) : urldecode($tag_title);
        $wardrobe_id = ($this->input->get_post('wardrobe_id')) ?
                $this->input->get_post('wardrobe_id') : intval($wardrobe_id);
        
        //若沒有指定衣櫃代碼，使用登入者的衣櫃代碼為預設衣櫃代碼
        $wardrobe_id = ($wardrobe_id) ? $wardrobe_id : $this->loginer->wardrobe_id;
        
        //如果有指定單品代碼，再繼續
        if ($item_id)
        {
            //檢查此單品是否不在衣櫃中
            if (!$this->wardrobe_model->is_exists($item_id, $wardrobe_id, $tag_title))
            {
                $respone['error'] = '已經從衣櫃中移除';
            }
            else
            {
                //將單品從衣櫃中移除
                if ($this->wardrobe_model->remove($item_id, $wardrobe_id, $tag_title))
                {
                    $respone['result'] = TRUE;
                    $respone['error'] = NULL;
                }
                else
                {
                    $respone['error'] = '已經從衣櫃中移除';
                }
            }
        }
        else
        {
            $respone['error'] = '單品(' . $item_id . ')或衣櫃(' . $wardrobe_id . ')不存在';
        }
        if ($this->input->is_ajax_request())
        {
            echo json_encode($respone);
            exit;
        }
        else
        {
            redirect('wardrobe/view/' . $tag_title);
        }
    }

}

/* End of file wardrobe.php */
/* Location: ./application/controllers/wardrobe.php */