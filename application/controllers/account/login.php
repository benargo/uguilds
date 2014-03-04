<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CI -> Controllers -> Account -> Login
 *
 * Handles the login to the web service
 */
require_once(APPPATH .'controllers/account/account.php');

class Login extends Account
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

		$this->data['page_title'] = 'Login/Register';
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
			$this->data['subview'] = 'account/login/locked';
			$this->render();
			exit;
		}

		if(isset($_SERVER['HTTP_REFERER']))
		{
			$this->session->set_flashdata('login_referer', $_SERVER['HTTP_REFERER']);
		}

		$this->_show_login_form();

		$this->render();
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
		$this->session->keep_flashdata('login_referer');

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

				$this->data['email']			= $this->input->post('email');
				$this->data['password']			= $this->input->post('password');
				$this->data['password_confirm'] = '';
				$this->data['members']		 	= $members;
				$this->data['character_name'] 	= '';
				$this->data['remainder']		= false;

				$this->data['subview'] = 'account/login/register';
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
						$this->data['authentication_error'] = "<p>Sorry, but either your email address or password was incorrect.</p>";
						$this->data['subview'] = 'account/login/index';
					}
					else // Yes -> Lock out for 30 minutes
					{
						if(!$this->session->userdata('login_locked') || $this->session->userdata('login_locked') <= time())
						{
							$this->session->set_userdata(array('login_locked' => time() + 1800));
						}

						$this->data['subview'] = 'account/login/locked';
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

						$this->data['character_name'] = $this->account->get_active_character()->name;
						$this->data['email']		  = $this->input->post('email');

						$this->data['subview'] = 'account/login/activate';
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
							$this->data['subview'] = 'account/login/suspended';
						}
						else // Yes -> Log in & set session data
						{
							$this->session->set_userdata(array(
								'user_id' => $this->account->id, 
								'character_name' => $this->account->get_active_character()->name));

							/**
							 * 6. Referer Set?
							 *
							 * if   = No  -> Redirect to root
							 * else = Yes -> Redirect to referer
							 */
							if(!$this->session->flashdata('login_referer')) // No -> Redirect to root
							{
								redirect(site_url());
							}
							else // Yes -> Redirect to referer
							{
								redirect($this->session->flashdata('login_referer'));
							} // END: 6. Referer Set?

						} // END: 5. Account is Suspended?

					} // END: 4. Account is Active?

				} // END: 3. Account Authenticates?

			} // END: 2. Account Exists?

		} // END: 1. Login Form Validates?

		// Render the page
		$this->render();
	}

	/**
	 * logout()
	 *
	 * Logs the user out, ending their session.
	 *
	 * @access public
	 * @return void
	 */
	public function logout()
	{
		$this->session->unset_userdata('user_id');
		$this->session->unset_userdata('character_name');

		$this->load->helper('url');
		redirect('/');
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

		$this->data['email'] = $this->input->post('email');
		$this->data['password'] = $this->input->post('password');
		$this->data['subview'] = 'account/login/index';
	}
}

