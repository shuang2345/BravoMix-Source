<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Layout
{
    protected $obj;
    protected $layout;

    public function __construct($layout = "template")
    {
        $this->obj =& get_instance();
        $this->layout = $layout;
    }

    public function setLayout($layout)
    {
      $this->layout = $layout;
    }

    public function view($view, $data = NULL, $return = FALSE)
    {
        $title = (isset($data['title'])) ? " - " . $data['title'] : "";
        $loadedData = array(
            "content" => $this->obj->load->view($view, $data, true),
            "site_name" => $this->obj->config->item('site_name') . $title,
            "site_description" => $this->obj->config->item('site_description'),
            "site_keywords" => $this->obj->config->item('site_keywords'),
        );

        if($return)
        {
            $output = $this->obj->load->view($this->layout, $loadedData, true);
            return $output;
        }
        else
        {
            $this->obj->load->view($this->layout, $loadedData, false);
        }
    }
}
?>