<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 單品控制器
 *
 * @author Liao San-Kai
 */
class Item extends CI_Controller {

    /**
     * 建構子
     */
    function __construct()
    {
        parent::__construct();
        $this->load->spark('Oborci/0.9');
        $this->load->model(array(
            'item_model',
            'file_model',
        ));
    }

    /**
     * 預設動作
     */
    public function index()
    {
        echo '<pre>';
        $items = $this->item_model->find_all();
        $item = $this->item_model->find(1);
        $all_items_title = $this->item_model->find_one(array('title'=>'單品2號'));
        print_r($all_items_title);
        print_r($item);
        $files = $this->item_model->find_from('file_model', array('id' => 6 ));
        print_r($files);
        //
        print_r($this->item_model->find_all());

        echo '</pre>';
    }

}

/* End of file item.php */
/* Location: ./application/controllers/item.php */