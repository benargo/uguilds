<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CI -> Controllers -> Account -> Login
 *
 * Handles the login to the web service
 */
class Login extends UG_Controller 
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
	 * @return void
	 */
	public function index()
	{
		if(isset($_SERVER['HTTP_REFERER']))
		{
			$this->session->set_userdata(array('login_referer' => $_SERVER['HTTP_REFERER']));
		}

		$this->load->helper('form');
	}
}