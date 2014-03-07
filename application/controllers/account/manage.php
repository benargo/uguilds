<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CI -> Controllers -> Account -> Manage
 *
 * Account management system
 */
require_once(APPPATH .'controllers/account/account.php');

class Manage extends Account
{
	/**
	 * __construct()
	 *
	 * Initialises the controller, 
	 * and determines which function should actually be called based on any number of scenarios.
	 *
	 * Potential scenarios include:
	 * 1. Is the user logged in?
	 *
	 * @access public
	 * @return void
	 */
	function __construct() 
	{
		parent::__construct();

		/**
		 * 1. Is the user logged in?
		 *
		 * if   = No -> Redirect to the login page
		 */
		if(!$this->is_logged_in()) // No -> Redirect to the login page
		{
			// Redirect to the login page.
			$this->load->helper('url');
			redirect('account/login');
		}
	}

	/**
	 * index()
	 *
	 * Index page for the account management system.
	 *
	 * @access public
	 * @return text/html
	 */
	public function index()
	{
		$this->data['page_title'] 		= 'My Account &amp; Characters';
		$this->data['guild_name'] 		= $this->guild->guild_name;
		$this->data['active_character'] = $this->account->get_active_character()->id;

		$this->data['subview'] = 'account/manage/index';

		$this->render();
	}
}