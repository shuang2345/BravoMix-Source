<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 單品控制器
 *
 * @author Liao San-Kai
 */
class Item extends MY_Controller {

    /**
     * 建構子
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->helper('array');
        $this->load->model('item_model');
        $this->load->model('wardrobe_model');
    }

    //--------------------------------------------------------------------------
    /**
     * 預設動作
     */
    public function index()
    {

    }

    //--------------------------------------------------------------------------
    /**
     * 單品清單
     *
     * $config['uri_segment']的值為函式參數量數+2
     *
     * @param int $offset 位置
     * @param int $limit 讀取筆數(每頁大小)
     * @param type $orderby 排序欄位
     * @param type $vector 遞增或遞增
     */
    public function roll($limit=6, $orderby='add_time', $vector='DESC', $offset=0)
    {
        //如果為Ajax模式請求，就直接輸出JSON格式
        if ($this->input->is_ajax_request())
        {
            $data = $this->item_model->find_all($limit, $orderby, $vector, $offset);
            json_encode($data);
            exit;
        }
        $this->load->library('pagination');
        //計算單品總數(尚未考慮條件)
        $item_total = $this->db->count_all_results('items');
        $config['base_url'] = site_url('item/roll/' . join('/', array($limit, $orderby, $vector)));
        $config['uri_segment'] = 6;
        $config['num_links'] = 10;
        $config['total_rows'] = $item_total;
        $config['per_page'] = $limit;
        //
        $config['full_tag_open'] = '<ul>';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li';
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li class="next">';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="current">';
        $config['cur_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);

        //讀取指定的範圍資料
        $data['items'] = $this->item_model->find_all($limit, $orderby, $vector, $offset);
        $data['pager'] = $this->pagination->create_links();

        //套用視圖
        $this->template->add_css('/assets/css/pagination.css', 'screen');
        $this->template->add_css('/assets/css/gallery/3-mini-paper-clip.css', 'screen');
        $this->template->render('item/roll', $data);
    }

    //--------------------------------------------------------------------------
    /**
     * 單品刪除
     *
     * @param int $item_id 單品代碼
     */
    public function delete($item_id=NULL)
    {
        //若有POST或GET的資料，改用POST或GET的資料
        $item_id = ($this->input->get_post('item_id')) ?
                $this->input->get_post('item_id') : $item_id;

        //嘗試讀取指定單品代碼的資料
        $item = $this->item_model->delete($data['item_id']);
    }

    //--------------------------------------------------------------------------
    /**
     * 單品檢視
     *
     * @param int $item_id 單品代碼
     */
    public function view($item_id=NULL)
    {
        //若有POST或GET的資料，改用POST或GET的資料
        $item_id = ($this->input->get_post('item_id')) ?
                $this->input->get_post('item_id') : $item_id;

        //嘗試讀取指定單品代碼的基本資料
        $data = $this->item_model->find($item_id);

        //是否要顯示編輯按鈕
        $data['show_edit_button'] = FALSE;
        if ($this->loginer->id == $data['item_user_id'])
        {
            $data['show_edit_button'] = TRUE;
        }

        //如果單品存在，繼續讀取相關資訊
        if (count($data))
        {
            //讀取指定單品代碼的分類標籤
            $data['item_kind_tags'] = $this->item_model->find_tags($data['item_id'], 'kind', 3);
            //讀取指定單品代碼的風格標籤
            $data['item_style_tags'] = $this->item_model->find_tags($data['item_id'], 'style', 3);
            //讀取指定單品代碼的圖片
            $data['item_images'] = $this->item_model->find_images($data['item_id'], 5);
        }
        //如果是以Ajax模式請求，自動輸出JSON格式
        if ($this->input->is_ajax_request())
        {
            $response['total'] = count($data);
            $response['data'] = $data;
            echo json_encode($response);
        }
        else
        {
            $data = $data + array(
                'item_id' => '',
                'item_title' => '',
                'item_brand' => '',
                'item_price' => '',
                'item_link' => '',
                'item_cover' => '',
            );
            $data['item_kind_tags'] = (isset($data['item_kind_tags'])) ? $data['item_kind_tags'] : array();
            $data['item_style_tags'] = (isset($data['item_style_tags'])) ? $data['item_style_tags'] : array();
            $data['item_images'] = (isset($data['item_images'])) ? $data['item_images'] : array();
            //
            $this->template->add_js('/assets/js/pikaChoose/jquery.pikachoose.full.js');
            $this->template->add_css('/assets/js/pikaChoose/styles/bottom.css');
            $this->template->render('item/view', $data);
        }
    }

    //--------------------------------------------------------------------------
    /**
     * 單品儲存/編輯
     *
     * @param int $item_id 單品代碼
     */
    public function edit($item_id=NULL)
    {
        //若有POST或GET的資料，改用POST或GET的資料
        $item_id = ($this->input->get_post('item_id')) ?
                $this->input->get_post('item_id') : $item_id;

        // Fixed: if add new item
        $item_id = intval($item_id);
        if (count($_POST))
        {
            //儲存單品資料
            $data = $this->item_model->save($_POST);
            //如果儲存成功，再做下面的事
            if (isset($data['save_result']) && $data['save_result'] === TRUE)
            {
                $item_id = $data['item_id'];
                //重貼單品標籤
                foreach (array('kind', 'style') as $flag)
                {
                    $this->item_model->clear_tags($item_id, $flag);
                    foreach (element("item_{$flag}_tags", $_POST, array()) as $tag)
                    {
                        $this->item_model->add_tag($item_id, $tag['tag_title'], $flag);
                    }
                    $data["item_{$flag}_tags"] = $this->item_model->find_tags($item_id, $flag);
                }
                //重貼單品圖片
                $this->item_model->clear_images($item_id);
                foreach (element('item_images', $_POST, array()) as $image)
                {
                    $this->item_model->add_image($item_id, $image['filename']);
                }
                //將此商品加到自己的衣櫃中
                if (!$this->wardrobe_model->is_exists($item_id, $this->loginer->wardrobe_id, '我所上傳的'))
                {
                    $this->wardrobe_model->add($item_id, $this->loginer->wardrobe_id, '我所上傳的');
                }
                redirect('item/edit/' . $item_id);
            }
        }
        else
        {
            /*
              //透過AJAX取得單品資料
              $this->load->spark('curl-1.2.0');
              $this->curl->http_header('Accept', 'application/json');
              $this->curl->http_header('X-Requested-With', 'XMLHttpRequest');
              $curldata = $this->curl->simple_post('item/view/' . $item_id);
              $response = json_decode($curldata, TRUE);
             */
            //########用Ajax方式有權限上的問題，這邊先改回直接自己處理 2011-08-19#######

            if ($item_id > 0)
            {
                $response['data'] = $this->item_model->find($item_id);
                //如果非單品建立者不能編輯
                if ($this->loginer->id != $response['data']['item_user_id'])
                {
                    redirect('item/view/' . $response['data']['item_id']);
                }
                //如果單品存在，繼續讀取相關資訊
                if (count($response['data']))
                {
                    //讀取指定單品代碼的分類標籤
                    $response['data']['item_kind_tags'] =
                            $this->item_model->find_tags($response['data']['item_id'], 'kind', 3);
                    //讀取指定單品代碼的風格標籤
                    $response['data']['item_style_tags'] =
                            $this->item_model->find_tags($response['data']['item_id'], 'style', 3);
                    //讀取指定單品代碼的圖片
                    $response['data']['item_images'] =
                            $this->item_model->find_images($response['data']['item_id'], 5);
                }
                //##################################################################
                //基本資料初始化
                $data = ($response['data']) ? $response['data'] : array(
                    'item_id' => '',
                    'item_title' => '',
                    'item_brand' => '',
                    'item_price' => '',
                    'item_link' => '',
                    'item_cover' => '',
                        );

                //標籤與圖片讀取(填滿固定數量)
                $data['item_kind_tags'] = element('item_kind_tags', $response['data'], array());
                $data['item_style_tags'] = element('item_style_tags', $response['data'], array());
                $data['item_images'] = element('item_images', $response['data'], array());
            }
            else
            {
                $data = array(
                    'item_id' => '',
                    'item_title' => '',
                    'item_brand' => '',
                    'item_price' => '',
                    'item_link' => '',
                    'item_cover' => '',
                );
            }
        }
        //標籤與圖片讀取(填滿固定數量)
        $data['item_kind_tags'] = array_pad(element('item_kind_tags', $data, array()), 3, array());
        $data['item_style_tags'] = array_pad(element('item_style_tags', $data, array()), 3, array());
        $data['item_images'] = array_pad(element('item_images', $data, array()), 5, array());

        //載入視圖
        $this->template->add_js('/assets/js/jquery.form.js');
        $this->template->add_js('/assets/js/jcrop/jquery.Jcrop.min.js');
        $this->template->add_js('/assets/js/jcrop/jquery.color.js');
        $this->template->add_js('/assets/js/jquery-custom-file-input.js');
        $this->template->add_js('/assets/js/jquery-ui/jquery-ui-1.8.16.custom.min.js');
        $this->template->add_css('/assets/js/jcrop/styles/jquery.Jcrop.css');
        $this->template->add_css('/assets/js/jquery-ui/styles/ui-lightness/jquery-ui-1.8.16.custom.css');
        $this->template->render('item/edit', $data);
    }

}

/* End of file item.php */
/* Location: ./application/controllers/item.php */
