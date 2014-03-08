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
class Password extends Account
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

		if($this->is_logged_in())
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
		// Check whether there is an authenticated session. If not, it will redirect accordingly.
		$this->check_login();

		// Set the page title
		$this->data['page_title'] = 'Change Your Password';

		// Load some helpful stuff
		$this->load->library('form_validation');

		// Used in part 3 - To check whether the password verifies
		$this->data['authentication_error'] = '';

		/**
		 * 1. Form submitted?
		 *
		 * if   = Yes -> Form validates?
		 * else = No  -> Show form
		 */
		if($this->input->post('current') && $this->input->post('new_1') && $this->input->post('new_2'))
		{
			// Set some validation rules
			$this->form_validation->set_rules(array(
				array(
					'field' => 'current',
					'label' => 'Current Password',
					'rules' => 'trim|required'
				),
				array(
					'field' => 'new_1',
					'label' => 'Password',
					'rules' => 'trim|required|matches[new_2]'
				),
				array(
					'field' => 'new_2',
					'label' => 'Confirm Password',
					'rules' => 'trim|required|matches[new_1]'
				),
			));

			/**
			 * 2. Form validates?
			 *
			 * if   = Yes -> Current password verifies?
			 * else = No -> Show form again
			 */
			if($this->form_validation->run() != FALSE)
			{
				/**
				 * 3. Current password verifies?
				 *
				 * if = Yes -> User's password changed successfully?
				 * else = No -> Show form again
				 */
				if($this->account->authenticate($this->input->post('current')))
				{
					// Load the URL helper, needed for the redirect we'll need in the next step.
					$this->load->helper('url');

					/**
					 * 4. User's password changed successfully?
					 *
					 * if = Yes -> Compile success message and redirect to account management page
					 * else = No -> Compile failure message and redirect to account management page
					 */
					if($this->account->set_password($this->input->post('new_1')))
					{
						$this->session->set_flashdata('message', 'Thank you '. $this->account->get_active_character()->name .'. You have succesfully changed your password.');
					}
					else // No -> Compile failure message and redirect to account management page
					{
						$this->session->set_flashdata('message', 'Sorry, we had an internal error when we tried to update your password. Can you <a href="/account/password/change">try again?</a>');
					}
					// END: 4. User's password changed successfully?

					redirect('account');
				}
				else // No -> Show form again
				{
					$this->data['authentication_error'] = "<p>The 'current password' you supplied does not match the one on your account.</p>";
					$this->data['subview'] = 'account/password/change';
				}
				// END: 3. Current password verifies?
			}
			else // No -> Show form again
			{
				$this->data['subview'] = 'account/password/change';
			}
			// END: 2. Form validates?
		}
		else // No -> Show form
		{
			$this->data['subview'] = 'account/password/change';
		}
		// END: 1. Form submitted?

		$this->render();
	}
	// END: change();

	/**
	 * recover()
	 *
	 * Allows a user to recover their account if they've forgotten their password.
	 *
	 * Begins by asking for an email address. 
	 * It then finds the account, sets the password to NULL and sends them an activation email
	 *
	 * If the user is logged in, it will return a 404 error.
	 *
	 * @access public
	 * @return void
	 */
	public function recover()
	{
		$this->data['page_title'] = 'Account Recovery';

		/**
		 * 1. Is there an active session?
		 *
		 * if 	= Yes -> Show a 404 Error
		 * else = No  -> Email address posted?
		 */
		if($this->is_logged_in())
		{
			show_404('account/password/recover', false);
		}
		else // No -> Email address posted?
		{
			// Load some helpful stuff
			$this->load->library('form_validation');

			/**
			 * 2. Email address posted?
			 *
			 * if 	= Yes -> Form validates?
			 * else = No  -> Show the form
			 */
			if($this->input->post('email'))
			{
				$this->form_validation->set_rules(array(
					array(
						'field' => 'email',
						'label' => 'Email Address',
						'rules' => 'trim|required|valid_email|xss_clean'
					),
					array(
						'field' => 'new_1',
						'label' => 'Password',
						'rules' => 'trim|required|matches[new_2]'
					),
					array(
						'field' => 'new_2',
						'label' => 'Confirm Password',
						'rules' => 'trim|required|matches[new_1]'
					),
				));

				/**
				 * 3. Form validates?
				 *
				 * if 	= Yes -> Account exists?
				 * else = No  -> Show form again
				 */
				if($this->form_validation->run() != false)
				{
					/**
					 * 4. Account exists?
					 *
					 * if 	= Yes -> change password
					 * else = No  -> Show registration form
					 */
					if(($this->account = uGuilds\Account::factory($this->input->post('email'))) !== false)
					{
						// Change the password
						$this->db->update_string(
							'Accounts', 
							array(
								'password' => $this->input->post('new_1'),
								'is_active'   => 0,
								'activation_code'
							), 
							'id = '. $this->account->id);

						$this->send_activation_email();

						$this->data['email'] = $this->account->get_email();
						$this->data['subview'] = 'account/password/reactivate';
					}
					else // No -> Show registration form
					{
						$members = $this->guild->get_unlinked_members('name');
						foreach($members as $key => $member)
						{
							$members[$key] = $member->name;
						}

						$this->data['email']			= $this->input->post('email');
						$this->data['password']			= $this->input->post('new_1');
						$this->data['password_confirm'] = $this->input->post('new_2');
						$this->data['members']		 	= $members;
						$this->data['character_name'] 	= '';
						$this->data['remainder']		= false;

						$this->data['subview'] = 'account/login/register';
					}
				}
				else // No -> Show form again
				{
					$this->data['subview'] = 'account/password/recover';
				}
			}
			else // No -> Show the form
			{
				$this->data['subview'] = 'account/password/recover';
			}
			// END: 2. Email address posted?
		}
		// END: 1. Is there an active session?

		$this->render();
	}
	// END: recover();
}
// END: uGuilds -> Controllers -> Account -> Password

