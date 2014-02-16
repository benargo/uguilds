<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CI -> Controllers -> Account -> Register
 *
 * Handles the login to the web service
 */
require_once(APPPATH .'controllers/account/Account_Controller.php');

class Register extends Account_Controller 
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
			$this->theme->data(array('content' => $this->load->view('account/login/register', array(
				'character_name' 	=> '',
				'email'			 	=> $this->input->post('email'),
				'password' 		 	=> $this->input->post('password'),
				'password_confirm' 	=> '',
				'members'		 	=> $this->members,
				'remainder'		 	=> false
			), true)));
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
								email,
								password,
								activation_code,
								active_character)
							VALUES (
								'". $this->encrypt->encode($this->input->post('email')) ."',
								'". password_hash($this->input->post('password'), PASSWORD_DEFAULT) ."',
								'". md5(time()) ."',
								 ". $this->character->id .")");

						$insert_id = $this->db->insert_id();

						$this->db->query(
							"UPDATE ug_Characters
							SET account_id = ". $insert_id ."
							WHERE _id = ". $this->character->id);

						$this->account = new uGuilds\Account($insert_id);

						$this->_send_activation_email();

						$this->theme->data(array('content' => $this->load->view('account/login/activate', array(
							'character_name' => $this->character->name,
							'email' 		 => $this->input->post('email')
						), true)));
		
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

		$this->theme->data(array('content' => $this->load->view('account/login/register', array(
			'character_name' 	=> $this->character->name, 
			'email' 		 	=> $this->input->post('email'),
			'password' 		 	=> $this->input->post('password'),
			'password_confirm' 	=> $this->input->post('password_confirm'),
			'members'		 	=> $this->members,
			'remainder'		 	=> true,
			'items' => array($items[0]['slot'] => $items[0], $items[1]['slot'] => $items[1])), true)));
	}
}



