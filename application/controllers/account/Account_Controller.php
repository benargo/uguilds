<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CI -> Controllers -> Account
 *
 * Handles the login to the web service
 */
class Account_Controller extends UG_Controller 
{
	protected $account;

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
	 * _send_activation_email()
	 *
	 * Loads the Email library, preprares an activation email and sends it out.
	 * For the moment, emails are sent as plain text. HTML email templates will come later
	 *
	 * @access public
	 * @return Either void or an Exception
	 */
	protected function _send_activation_email()
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
			
			site_url('account/activate/'. $this->encrypt->encode($this->account->_id) .'/'. $this->account->activation_code) ."\n\n".
				
			"See you soon!\n".
				
			$this->guild->name ."\n\n".
				
			"Privacy Notice: This email has been sent by uGuilds on behalf of ". $this->guild->name .". The information contained within this email is both private and confidential. If you are not the intended recipient, please delete this email from your system. uGuilds respects your privacy and will never email you without your consent, nor will we pass on your details to any third party person or organisation under any circumstances. For further information, please visit http://www.uguilds.com/legal/privacy. Thank you for your support and cooperation.\n".
			
			"Copyright ". date('Y') ." uGuilds & ". $this->guild->name .".");

			if($this->email->send())
			{
				return true;
			}		
		}

		throw new Exception('Activation email failed to send, sorry about that.');
	}

}

