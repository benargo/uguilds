<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * uGuilds -> Controllers -> Account -> Password
 *
 * This controller handles various operations relating to users passwords. 
 * Particuarly, it allows an individual user to:
 * 1. Change their password
 * 2. Recover their password
 * 3. {insert additional features here}
 *
 * @package uGuilds
 * @author Ben Argo <ben@benargo.com>
 * @version 1.0
 * @copyright Copyright © 2013-2014, Ben Argo
 * @license GPL v3
 *
 * First of all, require the Account master controller
 */
require_once(APPPATH .'controllers/account/account.php');

/**
 * Table of Contents
 * 1. Construction Function
 * 2. Index page
 * 3. Change of password
 * 4. Password recovery
 */
class Login extends Account
{
	/**
	 * __construct()
	 *
	 * Initialises the class
	 *
	 * Even though this is an account-related class, it does not require valid authentication on all levels.
	 * So just load the parent classes
	 *
	 * @access public
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * index()
	 *
	 * Index page for this controller.
	 *
	 * This should redirect based on whether the user is logged in or not.
	 * If the user is logged in, redirect them to the password change page.
	 * Else, redirect them to the password recovery page.
	 *
	 * @access public
	 * @return void
	 */
	public function index()
	{
		$this->load->helper('url');

		if($this->session->userdata('user_id'))
		{
			redirect('account/password/change');
		}
		else
		{
			redirect('account/password/recover');
		}
	}

	/**
	 * change()
	 *
	 * Allows a user to change their password.
	 *
	 * Begins by checking whether if there is an authenticated session and acting accordingly.
	 * Then displays the form to begin the password change.
	 *
	 * @access public
	 * @return void
	 */
	public function change()
	{
		$this->check_login();
	}
}

