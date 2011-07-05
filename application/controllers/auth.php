<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->database();
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
			//無管理者權限將導向首頁
			redirect($this->config->item('base_url'), 'refresh');
		}
		else
		{
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			//list the users
			$this->data['users'] = $this->ion_auth->get_users_array();
			$this->load->view('auth/index', $this->data);
		}
	}

	//log the user in
	function login()
	{
		$this->data['title'] = "Login";

		//validate form input
		$this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() == true)
		{ //check to see if the user is logging in
			//check for "remember me"
			$remember = (bool) $this->input->post('remember');

			if ($this->ion_auth->login($this->input->post('email'), $this->input->post('password'), $remember))
			{ //if the login is successful
				//redirect them back to the home page
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				//登入後轉至登入首頁
				redirect('auth/index', 'refresh');
				//redirect($this->config->item('base_url'), 'refresh');
			}
			else
			{ //if the login was un-successful
				//redirect them back to the login page
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('auth/login', 'refresh'); //use redirects instead of loading views for compatibility with MY_Controller libraries
			}
		}
		else
		{  //the user is not logging in so display the login page
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

			$this->load->view('auth/login', $this->data);
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
			$this->load->view('auth/change_password', $this->data);
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
			$this->load->view('auth/forgot_password', $this->data);
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
			$this->load->view('auth/deactivate_user', $this->data);
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

		//原本需要有管理權限才可新增帳號, 更改後改為無限制
		/*if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}*/

		//validate form input
		$this->form_validation->set_rules('user_name', '姓名', 'required|min_length[1]|max_length[12]|xss_clean');
		$this->form_validation->set_rules('user_nickname', '暱稱', 'required|min_length[1]|max_length[12]|xss_clean');
		$this->form_validation->set_rules('user_sex', '性別', 'required|xss_clean');
		$this->form_validation->set_rules('email', '電子信箱', 'required|valid_email');
		
		$this->form_validation->set_rules('user_country', '國家', 'required|xss_clean');
		$this->form_validation->set_rules('user_city', '城市', 'required|xss_clean');
		$this->form_validation->set_rules('user_birthday', '生日', 'required|xss_clean');
		$this->form_validation->set_rules('user_job', '職業', 'required|xss_clean');
		$this->form_validation->set_rules('user_body_tall', '身高', 'required|xss_clean');
		$this->form_validation->set_rules('user_body_weight', '體重', 'required|xss_clean');
		$this->form_validation->set_rules('user_body_r1', '胸圍', 'required|xss_clean');
		$this->form_validation->set_rules('user_body_r2', '腰圍', 'required|xss_clean');
		$this->form_validation->set_rules('user_body_r3', '臀圍', 'required|xss_clean');
		$this->form_validation->set_rules('user_body_shoulder', '肩寬', 'required|xss_clean');
		$this->form_validation->set_rules('user_body_leg', '腿長', 'required|xss_clean');
		
		$this->form_validation->set_rules('cellphone', '行動電話', 'required|trim|strip_tags|min_length[10]|max_length[10]|xss_clean|numeric');
		$this->form_validation->set_rules('phone', '市內電話', 'required|trim|strip_tags|min_length[8]|max_length[17]|xss_clean');
		$this->form_validation->set_rules('password', '密碼', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
		$this->form_validation->set_rules('password_confirm', '密碼確認', 'required');

		if ($this->form_validation->run() == true)
		{
			$username = strtolower($this->input->post('user_name'));
			$email = $this->input->post('email');
			$password = $this->input->post('password');

			$additional_data = array('user_name' => $this->input->post('user_name'),
				'user_nickname' => $this->input->post('user_nickname'),
				'user_sex' => $this->input->post('user_sex'),
				
				'user_country' => $this->input->post('user_country'),
				'user_city' => $this->input->post('user_city'),
				'user_birthday' => $this->input->post('user_birthday'),
				'user_job' => $this->input->post('user_job'),
				'user_body_tall' => $this->input->post('user_body_tall'),
				'user_body_weight' => $this->input->post('user_body_weight'),
				'user_body_r1' => $this->input->post('user_body_r1'),
				'user_body_r2' => $this->input->post('user_body_r2'),
				'user_body_r3' => $this->input->post('user_body_r3'),
				'user_body_shoulder' => $this->input->post('user_body_shoulder'),
				'user_body_leg' => $this->input->post('user_body_leg'),
				
				//'phone' => $this->input->post('phone1') . '-' . $this->input->post('phone2') . '-' . $this->input->post('phone3'),
				'phone' => $this->input->post('phone'),
				'cellphone' => $this->input->post('cellphone'),
				
			);
		}
		if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data))
		{ //check to see if we are creating the user
			//redirect them back to the admin page
			$this->session->set_flashdata('message', "User Created");
			redirect("auth", 'refresh');
		}
		else
		{ //display the create user form
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
			$this->data['user_sex'] = array('name' => 'user_sex',
				'id' => 'user_sex',
				'type' => 'text',
				'value' => $this->form_validation->set_value('user_sex'),
			);
			$this->data['email'] = array('name' => 'email',
				'id' => 'email',
				'type' => 'text',
				'value' => $this->form_validation->set_value('email'),
			);
			$this->data['phone'] = array('name' => 'phone',
				'id' => 'phone',
				'type' => 'text',
				'value' => $this->form_validation->set_value('phone'),
			);
			$this->data['cellphone'] = array('name' => 'cellphone',
				'id' => 'cellphone',
				'type' => 'text',
				'value' => $this->form_validation->set_value('cellphone'),
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
			$this->data['user_birthday'] = array('name' => 'user_birthday',
				'id' => 'user_birthday',
				'type' => 'text',
				'value' => $this->form_validation->set_value('user_birthday'),
			);
			$this->data['user_job'] = array('name' => 'user_job',
				'id' => 'user_job',
				'type' => 'text',
				'value' => $this->form_validation->set_value('user_job'),
			);
			$this->data['user_body_tall'] = array('name' => 'user_body_tall',
				'id' => 'user_body_tall',
				'type' => 'text',
				'value' => $this->form_validation->set_value('user_body_tall'),
			);
			$this->data['user_body_weight'] = array('name' => 'user_body_weight',
				'id' => 'user_body_weight',
				'type' => 'text',
				'value' => $this->form_validation->set_value('user_body_weight'),
			);
			$this->data['user_body_r1'] = array('name' => 'user_body_r1',
				'id' => 'user_body_r1',
				'type' => 'text',
				'value' => $this->form_validation->set_value('user_body_r1'),
			);
			$this->data['user_body_r2'] = array('name' => 'user_body_r2',
				'id' => 'user_body_r2',
				'type' => 'text',
				'value' => $this->form_validation->set_value('user_body_r2'),
			);
			$this->data['user_body_r3'] = array('name' => 'user_body_r3',
				'id' => 'user_body_r3',
				'type' => 'text',
				'value' => $this->form_validation->set_value('user_body_r3'),
			);
			$this->data['user_body_shoulder'] = array('name' => 'user_body_shoulder',
				'id' => 'user_body_shoulder',
				'type' => 'text',
				'value' => $this->form_validation->set_value('user_body_shoulder'),
			);
			$this->data['user_body_leg'] = array('name' => 'user_body_leg',
				'id' => 'user_body_leg',
				'type' => 'text',
				'value' => $this->form_validation->set_value('user_body_leg'),
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
			$this->load->view('auth/create_user', $this->data);
		}
	}
	
	//Delete the user
	function delete($id, $code=false)
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

}
