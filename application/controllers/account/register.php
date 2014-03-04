<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CI -> Controllers -> Account -> Register
 *
 * Handles the login to the web service
 */
require_once(APPPATH .'controllers/account/account.php');

class Register extends Account
{
	private $character;
	private $members;

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
	 *
	 * @access public
	 * @param (string) $character_name
	 * @param (string) $email
	 * @param (string) $password
	 * @return void
	 */
	public function verify()
	{
		// Helpers and libraries we need
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		// Load the list of members
		$this->members = $this->guild->get_unlinked_members();
		foreach($this->members as $key => $member)
		{
			$this->members[$key] = $member->name;
		}
		sort($this->members);

		// Form Validation Rules
		$this->form_validation->set_rules(array(
			array(
				'field' => 'email',
				'label' => 'Email Address',
				'rules' => 'trim|required|valid_email|xss_clean'
			),
			array(
				'field' => 'password',
				'label' => 'Password',
				'rules' => 'trim|required|matches[password_confirm]'
			),
			array(
				'field' => 'password_confirm',
				'label' => 'Confirm Password',
				'rules' => 'trim|required|matches[password]'
			)));

		/**
		 * 1. Form validates?
		 *
		 * if   = No  -> Show registration form with errors
		 * else = Yes -> Character Verifies?
		 */
		if($this->form_validation->run() === FALSE) // No -> Show registration form with errors
		{
			$this->data['character_name']	= $this->input->post('character');
			$this->data['email']		 	= $this->input->post('email');
			$this->data['password'] 		= '';
			$this->data['password_confirm'] = '';
			$this->data['members']		 	= $this->members;
			$this->data['remainder']		= false;

			$this->data['subview'] = 'account/login/register';
		}
		else // Yes -> Show character verification form
		{
			// Turn the cache off
			$config = $this->config->item('battle.net');
			$config['cachestatus'] = FALSE;
			$this->config->set_item('battle.net', $config);

			// Load the character
			$this->character = new uGuilds\Character($this->input->post('character'));

			// Calculate items to verify
			$items = $this->character->items;
			unset($items['averageItemLevel'], $items['averageItemLevelEquipped']);
			
			foreach($items as $slot => $item)
			{
				$items[$slot]['slot'] = $slot;
				$items[$slot]['icon'] = $this->character->getIcon($item['icon'], 18);
			}

			/**
			 * 2. Full form submitted?
			 *
			 * if   = No  -> Show character verification form
			 * else = Yes -> Character verifies?
			 */
			if(!$this->input->post('slot1') && !$this->input->post('slot2')) // No -> Show character verification form
			{
				$this->_show_character_verification_form($items);
			}
			else // Yes -> Character verifies?
			{
				/**
				 * 3. Character verifies?
				 *
				 * if   = No  -> Show character verification form
				 * else = Yes -> Create account
				 */
				if(array_key_exists($this->input->post('slot1'), $this->character->items)
					&& array_key_exists($this->input->post('slot2'), $this->character->items)) // No -> Show character verification form
				{
					$this->_show_character_verification_form($items);
				}
				else // Yes -> Create account
				{
					$this->load->library('encrypt');

					/**
					 * 4. Create account
					 *
					 * try 	 = Yes -> Send activation email
					 * catch = No  -> Show error
					 */
					try // Yes -> Send activation email
					{
						$this->db->query(
							"INSERT INTO ug_Accounts (
								id,
								email,
								password,
								activation_code,
								active_character)
							VALUES (
								". $this->db->escape(sha1($this->input->post('email'))) .",
								". $this->db->escape($this->encrypt->encode($this->input->post('email'))) .",
								'". password_hash($this->input->post('password'), PASSWORD_DEFAULT) ."',
								'". md5(time()) ."',
								 ". $this->character->id .")");

						$this->db->query(
							"UPDATE ug_Characters
							SET account_id = ". $this->db->escape(sha1($this->input->post('email'))) ."
							WHERE id = ". $this->character->id);

						$this->account = uGuilds\Account::factory($this->input->post('email'));

						$this->_send_activation_email();

						$this->data['character_name'] = $this->character->name;
						$this->data['email']		  = $this->input->post('email');

						$this->data['subview'] = 'account/login/activate';
		
					}
					catch(Exception $e) // No -> Show error
					{
						show_error($e->getMessage());
					}
				}
			}

		} // END: 1. Form Validates


		// Render the page
		$this->theme->view('page');
	}

	/**
	 * _show_character_verification_form()
	 *
	 * Renders the verification form given some items
	 *
	 * @access private
	 * @param (array) $items - An array containing all the items
	 * @return void
	 */
	private function _show_character_verification_form(array $items)
	{
		shuffle($items);

		$this->data['character_name'] 	= $this->character->name;
		$this->data['email'] 		 	= $this->input->post('email');
		$this->data['password']		 	= $this->input->post('password');
		$this->data['password_confirm'] = $this->input->post('password_confirm');
		$this->data['members';		 	= $this->members;
		$this->data['remainder']		= true;
		$this->data['items'] 			= array($items[0]['slot'] => $items[0], $items[1]['slot'] => $items[1]);

		$this->data['subview'] = 'account/login/register';
	}
}



