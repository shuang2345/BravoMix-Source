<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->library('form_validation');
        $this->load->helper('url');
    }

    //redirect if needed, otherwise display the user list
    function index()
    {
        if (!$this->ion_auth->logged_in())
        {
            //redirect them to the login page
            redirect('auth/login', 'refresh');
        }
        elseif (!$this->ion_auth->is_admin())
        {
            //redirect them to the home page because they must be an administrator to view this
            redirect('auth/personal_index', 'refresh');
            //redirect($this->config->item('base_url'), 'refresh');
        }
        else
        {
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            // user profile
            $this->data['profile'] = $this->ion_auth->profile();

            //list the users
            $this->data['users'] = $this->ion_auth->get_users_array();
            $this->layout->view('auth/index', $this->data);
        }
    }

    // show user profile
    function personal_index()
    {
        if (!$this->ion_auth->logged_in())
        {
            redirect('auth/login', 'refresh');
        }

        $this->data['profile'] = $this->ion_auth->profile();
        $this->layout->view('auth/personal_index', $this->data);
    }


    // view user profile
    function personal_data()
    {
        if (!$this->ion_auth->logged_in())
        {
            redirect('auth/login', 'refresh');
        }

        // get user profile
        $this->data['profile'] = $this->ion_auth->profile();
        $this->layout->view('auth/personal_data', $this->data);
    }

    //log the user in
    function login()
    {

        if ($this->ion_auth->logged_in())
        {
            redirect('auth/personal_index', 'refresh');
        }
        
        $check_login_error = $this->ion_auth->check_login_error();
        if($check_login_error)
            $this->load->helper('captcha');

        $this->data['title'] = "登入帳號";

        //validate form input
        $this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if($check_login_error)
            $this->form_validation->set_rules('vcode', 'Captcha Code', 'required');

        if ($this->form_validation->run() == true)
        {
            //check to see if the user is logging in
            //check for "remember me"
            $remember = (bool) $this->input->post('remember');
            
            // check Captcha Code
            if($check_login_error)
            {
                $code = $this->input->post('vcode');
                if($code != $this->session->userdata('vcode'))
                    redirect('auth/login', 'refresh');
            }


            if ($this->ion_auth->login($this->input->post('email'), $this->input->post('password'), $remember))
            {
                //if the login is successful
                //redirect them back to the home page
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                // remove session data: Captcha Code
                if($check_login_error)
                {
                    $this->session->unset_userdata('vcode');
                    $this->session->set_userdata('login_error_times', 0);
                }

                redirect($this->config->item('base_url'), 'refresh');
            }
            else
            {
                //if the login was un-successful
                //redirect them back to the login page
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                $login_error_times = $this->session->userdata('login_error_times');
                $this->session->set_userdata('login_error_times', $login_error_times+1);
                redirect('auth/login', 'refresh'); //use redirects instead of loading views for compatibility with MY_Controller libraries
            }
        }
        else
        {
            //the user is not logging in so display the login page
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            
            $this->data['email'] = array('name' => 'email',
                'id' => 'email',
                'type' => 'text',
                'value' => $this->form_validation->set_value('email'),
            );
            $this->data['password'] = array('name' => 'password',
                'id' => 'password',
                'type' => 'password',
            );

            if($check_login_error)
            {
                $this->data['vcode'] = array('name' => 'vcode',
                    'id' => 'vcode',
                    'type' => 'text',
                );

                $vals = array(
                    'word' => $this->system->generate_code(6),
                    'img_path' => './captcha/',
                    'img_url' => site_url() . "captcha/",
                    'img_width' => 150,
                    'img_height' => 30,
                    'expiration' => 7200
                );

                $cap = create_captcha($vals);
                $this->data['captcha_image'] = $cap['image'];
                $this->session->set_userdata('vcode', $cap['word']);
            }

            $this->data['show_captcha'] = $check_login_error;
            $this->layout->view('auth/login', $this->data);
        }
    }

    //log the user out
    function logout()
    {
        $this->data['title'] = "Logout";

        //log the user out
        $logout = $this->ion_auth->logout();

        //redirect them back to the page they came from
        redirect('auth', 'refresh');
    }

    //change password
    function change_password()
    {
        $this->form_validation->set_rules('old', 'Old password', 'required');
        $this->form_validation->set_rules('new', 'New Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
        $this->form_validation->set_rules('new_confirm', 'Confirm New Password', 'required');

        if (!$this->ion_auth->logged_in())
        {
            redirect('auth/login', 'refresh');
        }
        $user = $this->ion_auth->get_user($this->session->userdata('user_id'));

        if ($this->form_validation->run() == false)
        { //display the form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $this->data['old_password'] = array('name' => 'old',
                'id' => 'old',
                'type' => 'password',
            );
            $this->data['new_password'] = array('name' => 'new',
                'id' => 'new',
                'type' => 'password',
            );
            $this->data['new_password_confirm'] = array('name' => 'new_confirm',
                'id' => 'new_confirm',
                'type' => 'password',
            );
            $this->data['user_id'] = array('name' => 'user_id',
                'id' => 'user_id',
                'type' => 'hidden',
                'value' => $user->id,
            );

            //render
            $this->layout->view('auth/change_password', $this->data);
        }
        else
        {
            $identity = $this->session->userdata($this->config->item('identity', 'ion_auth'));

            $change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

            if ($change)
            { //if the password was successfully changed
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                $this->logout();
            }
            else
            {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect('auth/change_password', 'refresh');
            }
        }
    }

    //forgot password
    function forgot_password()
    {
        $this->form_validation->set_rules('email', 'Email Address', 'required');
        if ($this->form_validation->run() == false)
        {
            //setup the input
            $this->data['email'] = array('name' => 'email',
                'id' => 'email',
            );
            //set any errors and display the form
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            $this->layout->view('auth/forgot_password', $this->data);
        }
        else
        {
            //run the forgotten password method to email an activation code to the user
            $forgotten = $this->ion_auth->forgotten_password($this->input->post('email'));

            if ($forgotten)
            { //if there were no errors
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect("auth/login", 'refresh'); //we should display a confirmation page here instead of the login page
            }
            else
            {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect("auth/forgot_password", 'refresh');
            }
        }
    }

    //reset password - final step for forgotten password
    public function reset_password($code)
    {
        $reset = $this->ion_auth->forgotten_password_complete($code);

        if ($reset)
        {  //if the reset worked then send them to the login page
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("auth/login", 'refresh');
        }
        else
        { //if the reset didnt work then send them back to the forgot password page
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("auth/forgot_password", 'refresh');
        }
    }

    //activate the user
    function activate($id, $code=false)
    {
        if ($code !== false)
            $activation = $this->ion_auth->activate($id, $code);
        else if ($this->ion_auth->is_admin())
            $activation = $this->ion_auth->activate($id);


        if ($activation)
        {
            //redirect them to the auth page
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("auth", 'refresh');
        }
        else
        {
            //redirect them to the forgot password page
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("auth/forgot_password", 'refresh');
        }
    }

    //deactivate the user
    function deactivate($id = NULL)
    {
        // no funny business, force to integer
        $id = (int) $id;

        $this->load->library('form_validation');
        $this->form_validation->set_rules('confirm', 'confirmation', 'required');
        $this->form_validation->set_rules('id', 'user ID', 'required|is_natural');

        if ($this->form_validation->run() == FALSE)
        {
            // insert csrf check
            $this->data['csrf'] = $this->_get_csrf_nonce();
            $this->data['user'] = $this->ion_auth->get_user_array($id);
            $this->layout->view('auth/deactivate_user', $this->data);
        }
        else
        {
            // do we really want to deactivate?
            if ($this->input->post('confirm') == 'yes')
            {
                // do we have a valid request?
                if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
                {
                    show_404();
                }

                // do we have the right userlevel?
                if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
                {
                    $this->ion_auth->deactivate($id);
                }
            }

            //redirect them back to the auth page
            redirect('auth', 'refresh');
        }
    }

    //create a new user
    function create_user()
    {
        $this->data['title'] = "Create User";

        if($this->ion_auth->logged_in())
        {
            redirect('auth/index', 'refresh');
        }

        //validate form input
        $this->form_validation->set_rules('user_name', '姓名', 'required|min_length[1]|max_length[12]|xss_clean');
        $this->form_validation->set_rules('user_nickname', '暱稱', 'required|min_length[1]|max_length[52]|xss_clean');
        $this->form_validation->set_rules('email', '電子信箱', 'required|valid_email');
        $this->form_validation->set_rules('user_sex', '性別', 'required|xss_clean');
        $this->form_validation->set_rules('password', '密碼', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', '密碼確認', 'required');
        $this->form_validation->set_rules('register_code', '驗證碼', 'required');

        if ($this->form_validation->run() == true)
        {
            $register_code = $this->input->post('register_code');
            if($register_code != $this->session->userdata('register_code'))
                redirect('auth/create_user', 'refresh');
            
            $username = strtolower($this->input->post('user_name'));
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $user_birthday = $this->input->post('year') . '-' . $this->input->post('month') . '-' . $this->input->post('day');

            $additional_data = array(
                'user_name' => $this->input->post('user_name'),
                'user_nickname' => $this->input->post('user_nickname'),
                'user_sex' => $this->input->post('user_sex'),
                'user_birthday' => $user_birthday,
            );
        }

        if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data))
        {
            //check to see if we are creating the user
            //redirect them back to the admin page
            $this->session->set_flashdata('message', "User Created");
            // unset register_code variable value
            $this->session->unset_userdata('register_code');
            redirect("auth", 'refresh');
        }
        else
        {
            //display the create user form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['user_name'] = array('name' => 'user_name',
                'id' => 'user_name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('user_name'),
            );

            $this->data['user_nickname'] = array('name' => 'user_nickname',
                'id' => 'user_nickname',
                'type' => 'text',
                'value' => $this->form_validation->set_value('user_nickname'),
            );

            $this->data['email'] = array('name' => 'email',
                'id' => 'email',
                'type' => 'text',
                'value' => $this->form_validation->set_value('email'),
            );

            $this->data['user_sex_m'] = array(
                'name' => 'user_sex',
                'value' => 'M',
                'checked' => FALSE
            );
            $this->data['user_sex_f'] = array('name' => 'user_sex',
                'value' => 'F',
                'checked' => TRUE
            );

            $this->data['password'] = array('name' => 'password',
                'id' => 'password',
                'type' => 'password',
                'value' => $this->form_validation->set_value('password'),
            );
            $this->data['password_confirm'] = array('name' => 'password_confirm',
                'id' => 'password_confirm',
                'type' => 'password',
                'value' => $this->form_validation->set_value('password_confirm'),
            );
            
            $this->data['user_birthday'] = $this->_get_birthday_input();

            // load captcha module
            $this->load->helper('captcha');
            $this->data['register_code'] = array('name' => 'register_code',
                'id' => 'register_code',
                'type' => 'text',
            );

            $vals = array(
                'word'  => $this->system->generate_code(6),
                'img_path' => './captcha/',
                'img_url' => site_url() . "captcha/",
                'img_width' => 150,
                'img_height' => 30,
                'expiration' => 7200
            );

            $cap = create_captcha($vals);
            $this->data['captcha_image'] = $cap['image'];
            $this->session->set_userdata('register_code', $cap['word']);

            $this->layout->view('auth/create_user', $this->data);
        }
    }

    public function change_username()
    {
        if (!$this->ion_auth->logged_in())
        {
            redirect('auth/login', 'refresh');
        }
        $id = $this->session->userdata('user_id');
        $user_name = $this->input->post('username');
        $data = array(
            'user_name' => $user_name,
        );
        
        if($this->ion_auth->update_user($id, $data))
        {
            echo 'ok';
        } 
    }

    public function update_user()
    {
        if (!$this->ion_auth->logged_in())
        {
            redirect('auth/login', 'refresh');
        }

        $id = $this->session->userdata('user_id');
        $this->data['title'] = "Update User";

        //個人資料
        $this->data['profile'] = $this->ion_auth->profile();

        //validate form input
        $this->form_validation->set_rules('user_nickname', '暱稱', 'min_length[1]|max_length[12]|xss_clean');
        $this->form_validation->set_rules('user_sex', '性別', 'xss_clean');

        $this->form_validation->set_rules('user_body_tall', '身高', 'xss_clean');
        $this->form_validation->set_rules('user_body_weight', '體重', 'xss_clean');
        $this->form_validation->set_rules('user_body_r1', '胸圍', 'xss_clean');
        $this->form_validation->set_rules('user_body_r2', '腰圍', 'xss_clean');
        $this->form_validation->set_rules('user_body_r3', '臀圍', 'xss_clean');
        $this->form_validation->set_rules('user_body_shoulder', '肩寬', 'xss_clean');
        $this->form_validation->set_rules('user_body_leg', '腿長', 'xss_clean');

        $this->form_validation->set_rules('cellphone', '行動電話', 'trim|strip_tags|min_length[10]|max_length[10]|xss_clean|numeric');
        $this->form_validation->set_rules('phone', '市內電話', 'trim|strip_tags|min_length[8]|max_length[17]|xss_clean');

        if ($this->form_validation->run() == true)
        {
            $username = strtolower($this->input->post('user_name'));

            //$email = $this->input->post('email');
            //$password = $this->input->post('password');
            $user_birthday = $this->input->post('year') . '-' . $this->input->post('month') . '-' . $this->input->post('day');

            $update_data = array(
                'user_nickname' => $this->input->post('user_nickname'),
                'user_sex' => $this->input->post('user_sex'),
                'user_birthday' => $user_birthday,
                'user_body_tall' => $this->input->post('user_body_tall'),
                'user_body_weight' => $this->input->post('user_body_weight'),
                'user_body_r1' => $this->input->post('user_body_r1'),
                'user_body_r2' => $this->input->post('user_body_r2'),
                'user_body_r3' => $this->input->post('user_body_r3'),
                'user_body_shoulder' => $this->input->post('user_body_shoulder'),
                'user_body_leg' => $this->input->post('user_body_leg'),
                'phone' => $this->input->post('phone'),
                'cellphone' => $this->input->post('cellphone'),

            );
        }
        
        if ($this->form_validation->run() == true && $this->ion_auth->update_user($id, $update_data))
        {
            //check to see if we are creating the user
            //redirect them back to the admin page
            $this->session->set_flashdata('message', "User Updated");
            redirect("auth/update_user", 'refresh');
        }
        else
        {
            //display the create user form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['user_name'] = $this->data['profile']->user_name;
            $this->data['user_nickname'] = array('name' => 'user_nickname',
                'id' => 'user_nickname',
                'type' => 'text',
                'value' => $this->form_validation->run() ? $this->form_validation->set_value('user_nickname') : $this->data['profile']->user_nickname,
            );

            $this->data['user_sex_m'] = array(
                'name' => 'user_sex',
                'value' => 'M',
                'checked' => ($this->data['profile']->user_sex == 'M') ? TRUE : FALSE
            );
            $this->data['user_sex_f'] = array('name' => 'user_sex',
                'value' => 'F',
                'checked' => ($this->data['profile']->user_sex == 'F') ? TRUE : FALSE
            );

            $this->data['phone'] = array('name' => 'phone',
                'id' => 'phone',
                'type' => 'text',
                'value' => $this->form_validation->run() ? $this->form_validation->set_value('phone') : $this->data['profile']->phone,
            );
            $this->data['cellphone'] = array('name' => 'cellphone',
                'id' => 'cellphone',
                'type' => 'text',
                'value' => $this->form_validation->run() ? $this->form_validation->set_value('cellphone') : $this->data['profile']->cellphone,
            );


            $this->data['user_country'] = array('name' => 'user_country',
                'id' => 'user_country',
                'type' => 'text',
                'value' => $this->form_validation->set_value('user_country'),
            );
            $this->data['user_city'] = array('name' => 'user_city',
                'id' => 'user_city',
                'type' => 'text',
                'value' => $this->form_validation->set_value('user_city'),
            );
            
            $this->data['user_birthday'] = $this->_get_birthday_input($this->data['profile']->user_birthday);

            $this->data['user_body_tall'] = array('name' => 'user_body_tall',
                'id' => 'user_body_tall',
                'type' => 'text',
                'value' => $this->form_validation->run() ? $this->form_validation->set_value('user_body_tall') : $this->data['profile']->user_body_tall,
            );
            $this->data['user_body_weight'] = array('name' => 'user_body_weight',
                'id' => 'user_body_weight',
                'type' => 'text',
                'value' => $this->form_validation->run() ? $this->form_validation->set_value('user_body_weight') : $this->data['profile']->user_body_weight,
            );
            $this->data['user_body_r1'] = array('name' => 'user_body_r1',
                'id' => 'user_body_r1',
                'type' => 'text',
                'value' => $this->form_validation->run() ? $this->form_validation->set_value('user_body_r1') : $this->data['profile']->user_body_r1,
            );
            $this->data['user_body_r2'] = array('name' => 'user_body_r2',
                'id' => 'user_body_r2',
                'type' => 'text',
                'value' => $this->form_validation->run() ? $this->form_validation->set_value('user_body_r2') : $this->data['profile']->user_body_r2,
            );
            $this->data['user_body_r3'] = array('name' => 'user_body_r3',
                'id' => 'user_body_r3',
                'type' => 'text',
                'value' => $this->form_validation->run() ? $this->form_validation->set_value('user_body_r3') : $this->data['profile']->user_body_r3,
            );
            $this->data['user_body_shoulder'] = array('name' => 'user_body_shoulder',
                'id' => 'user_body_shoulder',
                'type' => 'text',
                'value' => $this->form_validation->run() ? $this->form_validation->set_value('user_body_shoulder') : $this->data['profile']->user_body_shoulder,
            );
            $this->data['user_body_leg'] = array('name' => 'user_body_leg',
                'id' => 'user_body_leg',
                'type' => 'text',
                'value' => $this->form_validation->run() ? $this->form_validation->set_value('user_body_leg') : $this->data['profile']->user_body_leg,
            );

            $this->layout->view('auth/update_user', $this->data);
        }
    }

    //Delete the user
    function delete($id, $code = false)
    {
        if ($code !== false)
            $activation = $this->ion_auth->activate($id, $code);
        else if ($this->ion_auth->is_admin())
            $activation = $this->ion_auth->activate($id);


        if ($activation)
        {
            //redirect them to the auth page
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("auth", 'refresh');
        }
        else
        {
            //redirect them to the forgot password page
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("auth/forgot_password", 'refresh');
        }
    }

    function _get_csrf_nonce()
    {
        $this->load->helper('string');
        $key = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);

        return array($key => $value);
    }

    function _valid_csrf_nonce()
    {
        if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
                $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue'))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    
    private function _get_birthday_input($data = NULL)
    {
        if(isset($data))
            $data = explode("-", $data);
        else
            $data = array();

        $year = (isset($data[0])) ? $data[0] : "0";
        $month = (isset($data[1])) ? $data[1] : "0";
        $day = (isset($data[2])) ? $data[2] : "0";
        
        // year
        
        $options = array("0" => "請選擇");
        for($i=1911;$i<=date("Y");$i++)
        {
            $options[$i] = $i;
        }
        $year = form_dropdown('year', $options, $year);
        
        // month

        $options = array("0" => "請選擇");
        for($i=1;$i<=12;$i++)
        {
            $options[$i] = $i;
        }
        $month = form_dropdown('month', $options, $month);

        // day

        $options = array("0" => "請選擇");
        for($i=1;$i<=31;$i++)
        {
            $options[$i] = $i;
        }
        $day = form_dropdown('day', $options, $day);
        
        return $year . "年" . $month . "月" . $day . "日";
    }

}
