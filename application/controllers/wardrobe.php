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
        
        print_r($items);

        //取得衣櫃可選用的所有標籤
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
        $this->template->add_css('/assets/css/gallery/4-cork-borad.css', 'screen');
        $this->template->render('wardrobe/view', $data);
    }

    //--------------------------------------------------------------------------
    /**
     * 衣櫃清單
     * 
     * @todo 由於目前的需求，一位使用者只有一個衣櫃，故此action僅保留
     */
    public function roll()
    {
        
    }

    //--------------------------------------------------------------------------
    /**
     * 為衣櫃中的單品貼上標籤
     * 
     * ※若沒有指定衣櫃代碼，使用登入者預設的衣櫃
     * ※這個action通常用在ajax方式
     * 
     * @param string $tag_title 標籤名稱(中文)※需要urldecode
     * @param int $item_id 單品代碼     
     * @param int $wardrobe_id 衣櫃代碼 
     */
    public function paste_tag($tag_title=NULL, $item_id=NULL, $wardrobe_id=NULL)
    {
        //若有POST或GET的資料，改用POST或GET的資料
        $item_id = ($this->input->get_post('item_id')) ?
                $this->input->get_post('item_id') : $item_id;
        $tag_title = ($this->input->get_post('tag_title')) ?
                urldecode($this->input->get_post('tag_title')) : urldecode($tag_title);
        $wardrobe_id = ($this->input->get_post('wardrobe_id')) ?
                $this->input->get_post('wardrobe_id') : intval($wardrobe_id);

        //若沒有指定衣櫃代碼，使用登入者的衣櫃代碼為預設衣櫃代碼
        $wardrobe_id = ($wardrobe_id) ? $wardrobe_id : $this->loginer->wardrobe_id;

        echo 'item_id:' . $item_id . "<br />\n";
        //貼上標籤        
        $result = $this->wardrobe_model->paste_tag($tag_title, $item_id, $wardrobe_id);

        //如果以Ajax方式請求，以JSON格式輸出結果
        if ($this->input->is_ajax_request())
        {
            $respone['result'] = $result;
            echo json_encode($respone);
            exit;
        }
    }

    //--------------------------------------------------------------------------
    /**
     * 撕掉衣櫃單品上的標籤
     * 
     * @param int $item_id 單品代碼
     * @param string $tag_title 標籤名稱
     * @param int $wardrobe_id 衣櫃代碼
     * @return int 影響的筆數 
     */
    public function tear_tag($tag_title=NULL, $item_id=NULL, $wardrobe_id=NULL)
    {
        //若有POST或GET的資料，改用POST或GET的資料
        $item_id = ($this->input->get_post('item_id')) ?
                $this->input->get_post('item_id') : intval($item_id);
        $tag_title = ($this->input->get_post('tag_title')) ?
                urldecode($this->input->get_post('tag_title')) : urldecode($tag_title);
        $wardrobe_id = ($this->input->get_post('wardrobe_id')) ?
                $this->input->get_post('wardrobe_id') : intval($wardrobe_id);

        //若沒有指定衣櫃代碼，使用登入者的衣櫃代碼為預設衣櫃代碼
        $wardrobe_id = ($wardrobe_id) ? $wardrobe_id : $this->loginer->wardrobe_id;

        //撕除標籤
        $result = $this->wardrobe_model->tear_tag($tag_title, $wardrobe_id, $item_id);

        //如果以Ajax方式請求，以JSON格式輸出結果
        if ($this->input->is_ajax_request())
        {
            $respone['result'] = $result;
            echo json_encode($respone);
            exit;
        }
    }

    //--------------------------------------------------------------------------
    /**
     * 追加單品至衣櫃中
     * 
     * 若沒有指定衣櫃代碼，使用登入者預設的衣櫃
     * 
     * @param int $wardrobe_id 衣櫃代碼
     * @param int $item_id 單品代碼
     * @param string $tag_title 標籤名稱(中文)※需要urldecode     *
     */
    public function attach_item($wardrobe_id=NULL, $item_id=NULL, $tag_title=NULL)
    {
        //若有POST或GET的資料，改用POST或GET的資料
        $wardrobe_id = ($this->input->get_post('wardrobe_id')) ?
                $this->input->get_post('wardrobe_id') : intval($wardrobe_id);
        $item_id = ($this->input->get_post('item_id')) ?
                $this->input->get_post('item_id') : intval($item_id);
        $tag_title = ($this->input->get_post('tag_title')) ?
                urldecode($this->input->get_post('tag_title')) : urldecode($tag_title);

        //若沒有指定衣櫃代碼，使用登入者的衣櫃代碼為預設衣櫃代碼
        $wardrobe_id = ($wardrobe_id) ? $wardrobe_id : $this->loginer->wardrobe_id;

        //追加單品
        $result = $this->wardrobe_model->add_item($wardrobe_id, $item_id, $tag_title);

        //如果以Ajax方式請求，以JSON格式輸出
        if ($this->input->is_ajax_request())
        {
            $respone['result'] = $result;
            echo json_encode($respone);
            exit;
        }
        echo '追加單品結果:' . ($result) ? '成功' : '失敗';
    }

    //--------------------------------------------------------------------------
    /**
     * 從衣櫃移除單品(也就是移除tag)
     * 
     * 若沒有指定衣櫃代碼，使用登入者預設的衣櫃
     * 
     * @param int $item_id 單品代碼
     * @param int $wardrobe_id 衣櫃代碼 
     */
    public function remove_item($wardrobe_id=NULL, $item_id=NULL)
    {
        //若有POST或GET的資料，改用POST或GET的資料
        $wardrobe_id = ($this->input->get_post('wardrobe_id')) ?
                $this->input->get_post('wardrobe_id') : intval($wardrobe_id);
        $item_id = ($this->input->get_post('item_id')) ?
                $this->input->get_post('item_id') : intval(item_id);

        //若沒有指定衣櫃代碼，使用登入者的衣櫃代碼為預設衣櫃代碼
        $wardrobe_id = ($wardrobe_id) ? $wardrobe_id : $this->loginer->wardrobe_id;

        //移除單品
        $result = $this->wardrobe_model->remove_item($wardrobe_id, $item_id);

        //如果以Ajax方式請求，以JSON格式輸出
        if ($this->input->is_ajax_request())
        {
            $respone['result'] = $result;
            echo json_encode($respone);
            exit;
        }
        echo '追加單品結果:' . ($result) ? '成功' : '失敗';
    }

}

/* End of file wardrobe.php */
/* Location: ./application/controllers/wardrobe.php */