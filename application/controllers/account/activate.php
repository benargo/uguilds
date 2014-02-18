<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CI -> Controllers -> Account -> Activate
 *
 * Handles the login to the web service
 */
require_once(APPPATH .'controllers/account/Account_Controller.php');

class Activate extends Account_Controller
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
			'page_title' => 'Activate Your Account',
			'author' => $this->guild->name,
		));
	}

	/**
	 * index()
	 *
	 * Shows a 404 error
	 *
	 * @access public
	 * @return void
	 */
	public function index()
	{
		show_404();
	}

	/**
	 * verify()
	 *
	 * Handles the activation procedure
	 *
	 * @access public
	 * @param (string) $account_id - An encrypted string containing the account ID
	 * @param (string) $activation_code - The activation code
	 * @return void
	 */
	public function verify()
	{
		// Load some libraries
		//$this->load->library('encrypt');

		$this->account = new uGuilds\Account($this->uri->segment(3));

		/**
		 * 1. Activation code matches URI?
		 *
		 * if   = No -> SHow error
		 * else = Yes -> Show error
		 */
		if($this->account->activation_code !== $this->uri->segment(4)) // No -> Show error
		{
			$this->theme->data(array('content' => $this->load->view('account/activate/error', array(), true)));
		}
		else // Yes -> Set account as active
		{
			/**
			 * 2. Activate the Account in the database
			 *
			 * if   = No  -> Show error
			 * else = Yes -> Is password field null?
			 */
			if(!$this->db->simple_query(
				"UPDATE ug_Accounts
				SET is_active = 1,
					activation_code = '". md5(time()) ."'
				WHERE id = '". $this->account->id ."'"))
			{
				$this->theme->data(array('content' => $this->load->view('account/activate/error', array(), true)));
			}
			else
			{
				/**
				 * 3. Is password field null?
				 *
				 * if   = Yes -> Show password reset form
				 * else = No  -> Set session data
				 */
				if(is_null($this->account->password)) // Yes -> Show password reset form
				{
					$this->load->helper('form');

					$this->theme->data(array('content' => $this->load->view('account/activate/password_null', array(
						'account_id' => $this->account->id,
						'character_name' => $this->account->get_active_character()->name
					), true)));
				}
				else // No -> Set session data
				{
					// Set the session data, we can automatically log them in :)
					$this->session->set_userdata(array(
						'user_id' => $this->account->id, 
						'character_name' => $this->account->get_active_character()->name
					));

					$this->theme->data(array(
						'content' => $this->load->view('account/activate/success', array(
							'character_name' => $this->account->get_active_character()->name
					), true),
						'account' => $this->account));

				} // END 3. Is Password field null?
			} // END 2. Activate the Account in the database
		} // END: 1. Activation code matches URI?

		// Render the page
		$this->theme->view('page');
	}
}

