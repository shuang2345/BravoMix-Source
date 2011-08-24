<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 衣櫃控制器
 * 
 *
 * @author Liao San-Kai
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
     * @param int $wardrobe_id 衣櫃代碼
     * @param int $tag_title 標籤名稱(中文)※需要urldecode
     */
    public function view($tag_title=NULL, $wardrobe_id=NULL)
    {
        $tag_title = urldecode($tag_title);
        //
        if (!$wardrobe_id)
        {
            $wardrobe_id = $this->loginer->wardrobe_id;
        }
        //讀取指定的衣櫃中的所有單品
        $items = $this->wardrobe_model->find_items($wardrobe_id, $tag_title);
        //取得指定衣櫃可選用的所有標籤
        $tags = $this->wardrobe_model->find_tags($wardrobe_id);

        $data['items'] = $items;
        $data['tags'] = $tags;
        $data['view_tag_title'] = $tag_title;

        $this->template->set_layout('template/layout/1-col');
        $this->template->add_css('/assets/css/gallery/3-mini-paper-clip.css', 'screen');
        $this->template->render('wardrobe/view', $data);
    }

    //--------------------------------------------------------------------------
    /**
     * 收錄單品至衣櫃中
     * 
     * 若沒有指定衣櫃代碼，使用登入者預設的衣櫃
     * 
     * @param int $item_id 單品代碼
     * @param int $tag_title 標籤名稱(中文)※需要urldecode
     * @param int $wardrobe_id 衣櫃代碼* 
     */
    public function attach_item($item_id=NULL, $tag_title=NULL, $wardrobe_id=NULL)
    {
        $tag_title = urldecode($tag_title);
        $respone['result'] = FALSE;
        //如果沒指定衣櫃代碼，使用登入者預設的衣櫃
        if (!$wardrobe_id)
        {
            $wardrobe_id = $this->loginer->wardrobe_id;
        }
        //如果有指定單品代碼，再繼續
        if ($item_id)
        {
            //檢查此單品是否已在衣櫃中
            if ($this->wardrobe_model->is_exists($item_id, $wardrobe_id, $tag_title))
            {
                $respone['error'] = '單品已在衣櫃中';
            }
            else
            {
                //將單品加入衣櫃中
                if ($this->wardrobe_model->add($item_id, $wardrobe_id, $tag_title))
                {
                    $respone['result'] = TRUE;
                    $respone['error'] = NULL;
                }
                else
                {
                    $respone['error'] = '單品已在衣櫃中';
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
    }

    //--------------------------------------------------------------------------
    /**
     * 衣櫃清單
     */
    public function roll($limit=6, $orderby='add_time', $vector='DESC', $offset=0)
    {
        
    }

    //--------------------------------------------------------------------------
    /**
     * 從衣櫃移除單品(其實就是移除tag)
     * 
     * 若沒有指定衣櫃代碼，使用登入者預設的衣櫃
     * 
     * @param int $item_id 單品代碼
     * @param int $tag_title 標籤名稱(中文)※需要urldecode
     * @param int $wardrobe_id 衣櫃代碼 
     */
    public function remove_item($item_id=NULL, $tag_title=NULL, $wardrobe_id=NULL)
    {
        $tag_title = urldecode($tag_title);
        $respone['result'] = FALSE;
        //如果沒指定衣櫃代碼，使用登入者預設的衣櫃
        if (!$wardrobe_id)
        {
            $wardrobe_id = $this->loginer->wardrobe_id;
        }
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

/* End of file item.php */
/* Location: ./application/controllers/item.php */