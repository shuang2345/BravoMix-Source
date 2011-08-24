<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('Facebook_model');
    }
    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/welcome
     *  - or -
     *      http://example.com/index.php/welcome/index
     *  - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function index()
    {
        $this->template->render('welcome_message');
    }

    public function facebook()
    {
        $fb_data = $this->session->userdata('fb_data'); // This array contains all the user FB information
        $this->system->d_print($fb_data);

        $data = array(
                'fb_data' => $fb_data,
        );

        $this->load->view('home', $data);
    }
    
    public function vcode()
    {
        // test auth random image
        echo "<img src='".base_url()."vcode'>";
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */