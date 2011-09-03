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
    public function view($mix_id=NULL, $img_mode=FALSE)
    {
        //若有POST或GET的資料，改用POST或GET的資料
        $mix_id = ($this->input->get_post('mix_id')) ?
                $this->input->get_post('mix_id') : $mix_id;

        //取出指定混搭代碼內的所有單品
        $mix_items = $this->mix_model->find_items($mix_id);

        //組合視圖變數
        $data['mix_items'] = $mix_items;
        $data['mix_id'] = $mix_id;
        $data['img_mode'] = $img_mode;

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
        //如果為Ajax模式請求，就直接輸出JSON格式
        if ($this->input->is_ajax_request())
        {
            $data = $this->mix_model->find_all($limit, $orderby, $vector, $offset);
            json_encode($data);
            exit;
        }
        $this->load->library('pagination');
        //計算單品總數(尚未考慮條件)
        $mix_total = $this->db->count_all_results('mixs');
        $config['base_url'] = site_url('mix/roll/' . join('/', array($limit, $orderby, $vector)));
        $config['uri_segment'] = 6;
        $config['num_links'] = 10;
        $config['total_rows'] = $mix_total;
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
        $data['mixs'] = $this->mix_model->find_all($limit, $orderby, $vector, $offset);
        $data['pager'] = $this->pagination->create_links();

        //套用視圖
        $this->template->add_css('/assets/css/pagination.css', 'screen');
        $this->template->add_css('/assets/css/gallery/3-mini-paper-clip.css', 'screen');
        $this->template->render('mix/roll', $data);
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
        //合成混搭圖
        $this->mix_img($mix_id);

        //輸出結果
        if ($this->input->is_ajax_request())
        {
            $respone['result'] = TRUE;
            $respone['mix_id'] = $mix_id;
            echo json_encode($respone);
            exit;
        }
    }

    //--------------------------------------------------------------------------
    /**
     * 合成混搭圖
     */
    public function mix_img($mix_id=NULL, $rebulid=TRUE)
    {
        //將代碼數字化(用來避免輸入數字之外的參數導致出錯)        
        $mix_id = intval($mix_id);

        //檢查此混塔代碼是否存在
        $mix_id = $this->mix_model->check_id($mix_id, FALSE);

        if ($mix_id)
        {
            //如果混搭圖已存在，而且沒有要求要重新建圖，那就直接回傳此圖片
            if (file_exists("uploads/mixs/{$mix_id}.png") && $rebulid != TRUE)
            {
                //mix圖已經有了，不需要動作
            }
            else
            {
                //為此混搭圖建立空白圖
                $mix_img = "uploads/mixs/{$mix_id}.png";
                system(escapeshellcmd("convert -size 475x475 -strip -colors 8 -depth 8 xc:none {$mix_img}"));

                //找出混搭中的單品            
                $mix_items = $this->mix_model->find_items($mix_id);

                //製作各單品縮圖並加至空白圖中
                foreach ($mix_items as $key => $item)
                {
                    //為混搭中的單品圖，再做一次縮圖
                    //混塔中的單品圖用的一定是裁切圖(uploads/crops/)                    
                    $size = "{$item['item_width']}x{$item['item_height']}!";
                    if (file_exists("uploads/" . $item['item_cover']))
                    {
                        $from = "uploads/{$item['item_cover']}";
                        $to = "uploads/thumbs/crop_{$item['item_width']}_{$item['item_height']}_{$item['item_cover']}";
                    }
                    else
                    {
                        $from = "uploads/no_image.png";
                        $to = "uploads/thumbs/raw_{$item['item_width']}_{$item['item_height']}_no_image.png";
                    }
                    //將完成的縮圖合併到空白圖中
                    system(escapeshellcmd("convert -resize {$size} {$from} {$to}"));
                    system(escapeshellcmd("composite -geometry +{$item['item_left']}+{$item['item_top']} {$to} {$mix_img} {$mix_img}"));
                }
                //將此混搭圖的縮圖清掉(mix_150_150_7.png   
                $thumbimages = glob("uploads/thumbs/mix_*_" . $mix_id . ".png", GLOB_BRACE);
                foreach ($thumbimages as $removeImg)
                {
                    unlink($removeImg);
                }
            }
        }
    }

}

/* End of file item.php */
/* Location: ./application/controllers/item.php */
