<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CI -> Controllers -> Account
 *
 * Root controller for the Account system.
 *
 * This controller should be the basis for all account based systems.
 *
 * @package uGuilds
 * @author Ben Argo <ben@benargo.com>
 * @version 1.0
 * @copyright Copyright © 2013-2014, Ben Argo
 * @license GPL v3
 */
class Account extends UG_Controller 
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
	}

	/**
	 * check_login()
	 *
	 * Checks whether there is an authenticated session.
	 * If not, redirect to the login page
	 *
	 * @access protected
	 * @return void
	 */
	protected function check_login()
	{
		if(!$this->is_logged_in())
		{
			$this->load->helper('url');
			redirect('account/login');
		}
	}

	/**
	 * is_logged_in()
	 *
	 * Checks whether there is an authenticated session
	 * and whether $this->account is set to a valid account object.
	 *
	 * @access protected
	 * @return boolean
	 */
	protected function is_logged_in()
	{
		// Return true or false
		return (bool) $this->session->userdata('user_id');
	}

	/**
	 * is_officer()
	 *
	 * Checks whether or not the character is either the guild master or a registered officer
	 *
	 * @access protected
	 * @return boolean
	 */
	protected function is_officer()
	{
		
	}

	/**
	 * send_activation_email()
	 *
	 * Loads the Email library, preprares an activation email and sends it out.
	 * For the moment, emails are sent as plain text. HTML email templates will come later
	 *
	 * @access protected
	 * @return Either void or an Exception
	 */
	protected function send_activation_email()
	{
		if(isset($this->account))
		{
			$this->load->library(array('email', 'encrypt'));
			$this->load->helper('url');

			$this->email->from('noreply@uguilds.net', $this->guild->name);
			$this->email->to($this->encrypt->decode($this->account->email));

			$this->email->bcc('logs@uguilds.net');

			$this->email->subject('Activate Your Account with '. $this->guild->name);
			$this->email->message("Dear ". $this->account->get_active_character()->name .",\n\n".
			"We're thrilled that you're taking part in our guild. However, before we can let you roam free we need you to activate your account. It's very easy, mind. All you need to do is copy and paste the following link into your web browser:\n\n".
			
			site_url('account/activate/'. $this->account->id .'/'. $this->account->activation_code) ."\n\n".
				
			"See you soon!\n".
				
			$this->guild->name ."\n\n".
				
			"Privacy Notice: This email has been sent by uGuilds on behalf of ". $this->guild->name .". The information contained within this email is both private and confidential. If you are not the intended recipient, please delete this email from your system. uGuilds respects your privacy and will never email you without your consent, nor will we pass on your details to any third party person or organisation under any circumstances. For further information, please visit http://www.uguilds.com/legal/privacy. Thank you for your support and cooperation.\n".
			
			"Copyright ". date('Y') ." uGuilds & ". $this->guild->name .".");

			if($this->email->send())
			{
				return true;
			}

			show_error($this->email->print_debugger());
		}

		show_error('Activation email failed to send, sorry about that.');
	}

}

