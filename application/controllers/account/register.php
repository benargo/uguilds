<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CI -> Controllers -> Account -> Register
 *
 * Handles the login to the web service
 */
require_once(APPPATH .'controllers/account/Account_Controller.php');

class Register extends Account_Controller 
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
	 * Redirects to the main login page
	 *
	 * @access public
	 * @return void
	 */
	public function index()
	{
		$this->load->helper('url');

		redirect('account/login');
	}

	/**
	 * verify()
	 *
	 * Verifies a user's registration
	 */
	public function verify()
	{
		$character = new uGuilds\Character($this->input->post('character'));

		/**
		 * 1. Email Address exists in Database?
		 *
		 * if   = No  -> Show full registration form
		 * else = Yes -> Account Authenticates?
		 */
		if($query->num_rows() === 0) // No -> Show full registration form
		{
			// Load the character
			$items = $character->items;
			unset($items['averageItemLevel'], $items['averageItemLevelEquipped']);
			
			foreach($items as $slot => $item)
			{
				$items[$slot]['slot'] = $slot;
				$items[$slot]['icon'] = $character->getIcon($item['icon'], 1800);
			}
			shuffle($items);

			$this->theme->data(array('content' => $this->load->view('account/login/register', array(
				'character_name' => $this->input->post('character'), 
				'email' 		 => $this->input->post('email'),
				'password' 		 => $this->input->post('password'),
				'remainder'		 => true,
				'items' => array($items[0]['slot'] => $items[0], $items[1]['slot'] => $items[1])), true)));
		}
		else // Yes -> Account has characters in this guild?
		{
			$row = $query->row();

			$this->account = new uGuilds\Account($row->id);

			/**
			 * 2. Account has characters in this guild?
			 *
			 * if   = No  -> Show add Character form
			 * else = Yes -> Account authenticates?
			 */
			if($this->account->get_all_characters()) // No -> Show add Character Form
			{
				
			} 

			if($this->input->post('password') === NULL)
			{
				$this->load->helper('url');

				redirect('account/login/')
			}
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
						$this->theme->data(array('authentication_error' => "<p>Sorry, but either your email address or password was incorrect.</p>"));
						$this->theme->data(array('content' => $this->load->view('account/login/index', $this->theme->data(), true)));
					}
					else // Yes -> Lock out for 30 minutes
					{
						if(!$this->session->userdata('login_locked') || $this->session->userdata('login_locked') <= time())
						{
							$this->session->set_userdata(array('login_locked' => time() + 1800));
						}

						$this->theme->data(array('content' => $this->load->view('account/login/locked', $this->theme->data(), true)));
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
						$this->theme->data(array('content' => $this->load->view('account/login/inactive', $this->theme->data(), true)));
					}
					else // Yes -> Check if Account is suspended
					{
						/**
						 * 5. Account is Suspended?
						 *
						 * if   = No  -> Prevent authentication attempt
						 * else = Yes -> Log in & set session data
						 */
						if(!$this->account->is_suspended) // No -> Prevent authentication attempt
						{
							$this->theme->data(array('content' => $this->load->view('account/login/suspended', $this->theme->data(), true)));
						}
						else // Yes -> Log in & set session data
						{
							$this->session->set_userdata(array(
								'user_id' => $account->_id, 
								'character_name' => $this->input->post('character')));

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
		}

		// Render the page
		$this->theme->view('page');
	}