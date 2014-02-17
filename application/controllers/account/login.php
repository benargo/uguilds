<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CI -> Controllers -> Account -> Login
 *
 * Handles the login to the web service
 */
require_once(APPPATH .'controllers/account/Account_Controller.php');

class Login extends Account_Controller 
{
	/**
	 * __construct()
	 *
	 * Initialises the controller
	 *
	 * @access public
	 * @return void
	 */
	function __construct() 
	{
		parent::__construct();

		$this->theme->data(array(
			'page_title' => 'Login',
			'author' => $this->guild->name,
		));
	}

	/**
	 * index()
	 *
	 * Renders the login form
	 *
	 * @access public
	 * @return output
	 */
	public function index()
	{
		if($this->session->userdata('login_locked') >= time())
		{
			$this->theme->data(array('content' => $this->load->view('account/login/locked', $this->theme->data(), true)));
			$this->theme->view('page');
			exit;
		}

		if(isset($_SERVER['HTTP_REFERER']))
		{
			$this->session->set_userdata(array('login_referer' => $_SERVER['HTTP_REFERER']));
		}

		$this->_show_login_form();

		$this->theme->view('page');
	}

	/**
	 * authenticate()
	 *
	 * Attempts to authenticate the User.
	 * A data flow diagram on the method is available at:
	 * @link https://github.com/benargo/uguilds/blob/master/documentation/authentication_flow_diagram.png
	 *
	 * @access public
	 * @return output
	 */
	public function authenticate()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules(array(
			array(
				'field' => 'email',
				'label' => 'Email Address',
				'rules' => 'trim|required|valid_email|xss_clean'
			),
			array(
				'field' => 'password',
				'label' => 'Password',
				'rules' => 'trim|required')
			));

		/**
		 * 1. Login Form Validates?
		 *
		 * if   = No  -> Show validation errors -> Show login form
		 * else = Yes -> Check if Account exists
		 */
		if($this->form_validation->run() === FALSE) // No -> Show validation errors -> Show login form
		{
			$this->_show_login_form();
			
		}
		else // Yes -> Check if Account exists
		{
			/**
			 * 2. Account Exists?
			 *
			 * if   = No  -> Load registration form
			 * else = Yes -> Check if Account authenticates
			 */
			if(!uGuilds\Account::factory($this->input->post('email'))) // No -> Load registration form
			{
				$members = $this->guild->get_unlinked_members();
				foreach($members as $key => $member)
				{
					$members[$key] = $member->name;
				}
				sort($members);

				$this->theme->data(array('content' => $this->load->view('account/login/register', array(
					'email'			 	=> $this->input->post('email'),
					'password' 		 	=> $this->input->post('password'),
					'password_confirm' 	=> '',
					'members'		 	=> $members,
					'character_name' 	=> '',
					'remainder'		 	=> false
				), true)));
			}
			else // Yes -> Check if Account authenticates
			{
				$this->account = uGuilds\Account::factory($this->input->post('email'));

				/**
				 * 3. Account Authenticates?
				 *
				 * if   = No  -> Check if login attempts >= 3
				 * else = Yes -> Check if Account is active
				 */
				if(!$this->account->authenticate($this->input->post('password'))) // No -> Check if login attempts >= 3
				{
					$login_attempts = 1;

					dump($this->account);

					if($this->session->userdata('login_attempts'))
					{
						$login_attempts = $this->session->userdata('login_attempts') + 1;
					}

					$this->session->set_userdata(array('login_attempts' => $login_attempts));

					/**
					 * 3.5. Login Attempts >= 3?
					 *
					 * if   = No  -> Show login form
					 * else = Yes -> Lock out for 30 minutes
					 */
					if($this->session->userdata('login_attempts') >= 3) // No -> Show login form
					{
						$this->theme->data(array('authentication_error' => "<p>Sorry, but either your email address or password was incorrect.</p>"));
						$this->theme->data(array('content' => $this->load->view('account/login/index', $this->theme->data(), true)));
					}
					else // Yes -> Lock out for 30 minutes
					{
						if(!$this->session->userdata('login_locked') || $this->session->userdata('login_locked') <= time())
						{
							$this->session->set_userdata(array('login_locked' => time() + 1800));
						}

						$this->theme->data(array('content' => $this->load->view('account/login/locked', array(), true)));
					}
				}
				else // Yes -> Check if Account is active
				{
					/**
					 * 4. Account is Active?
					 *
					 * if   = No  -> Send activation email
					 * else = Yes -> Check if Account is suspended
					 */
					if(!$this->account->is_active) // No -> Send activation email
					{
						$this->_send_activation_email();
						$this->theme->data(array('content' => $this->load->view('account/login/activate', array(
							'character_name' => $this->account->get_active_character()->name,
							'email' 		 => $this->input->post('email')
						), true)));
					}
					else // Yes -> Check if Account is suspended
					{
						/**
						 * 5. Account is Suspended?
						 *
						 * if   = No  -> Prevent authentication attempt
						 * else = Yes -> Log in & set session data
						 */
						if($this->account->is_suspended) // No -> Prevent authentication attempt
						{
							$this->theme->data(array('content' => $this->load->view('account/login/suspended', array(), true)));
						}
						else // Yes -> Log in & set session data
						{
							$this->session->set_userdata(array(
								'user_id' => $this->account->_id, 
								'character_name' => $this->account->get_active_character()->name));

							/**
							 * 6. Referer Set?
							 *
							 * if   = No  -> Redirect to root
							 * else = Yes -> Redirect to referer
							 */
							if(!$this->session->userdata('login_referer')) // No -> Redirect to root
							{
								redirect(site_url());
							}
							else // Yes -> Redirect to referer
							{
								redirect($this->session->userdata('login_referer'));
							} // END: 6. Referer Set?

						} // END: 5. Account is Suspended?

					} // END: 4. Account is Active?

				} // END: 3. Account Authenticates?

			} // END: 2. Account Exists?

		} // END: 1. Login Form Validates?

		// Render the page
		$this->theme->view('page');
	}

	/**
	 * _show_login_form()
	 *
	 * Shows the login form
	 *
	 * @access private
	 * @return void
	 */
	private function _show_login_form()
	{
		$this->load->helper('form');

		$this->theme->data(array('content' => $this->load->view('account/login/index', array(
			'email' => $this->input->post('email'),
			'password' => $this->input->post('password')
		), true)));
	}
}

