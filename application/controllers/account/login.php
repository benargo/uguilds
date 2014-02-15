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
	 * @return output
	 */
	public function index()
	{
		if($this->session->userdata('login_locked') >= time())
		{
			$this->theme->data(array('content' => $this->load->view('account/login/locked', $this->theme->data(), true)));
			$this->theme->view('page');
			exit;
		}

		if(isset($_SERVER['HTTP_REFERER']))
		{
			$this->session->set_userdata(array('login_referer' => $_SERVER['HTTP_REFERER']));
		}

		$this->load->helper('form');

		$this->theme->data(array('content' => $this->load->view('account/login/index', $this->theme->data(), true)));

		$this->theme->view('page');
	}

	/**
	 * authenticate()
	 *
	 * Attempts to authenticate the User.
	 * A data flow diagram on the method is available at:
	 * @link 
	 *
	 * @access public
	 * @return output
	 */
	public function authenticate()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules(array(
			array(
				'field' => 'character',
				'label' => 'Character Name',
				'rules' => 'required'),

			array(
				'field' => 'password',
				'label' => 'Password',
				'rules' => 'required')
			));

		if($this->form_validation->run() === FALSE)
		{
			$this->theme->data(array('content' => $this->load->view('account/login/index', $this->theme->data(), true)));
			$this->theme->view('page');
			exit;
		}

		if(($account = uGuilds\Account::factory($this->input->post('character'))) === FALSE)
		{
			$this->theme->data(array('content' => $this->load->view('account/login/register', $this->theme->data(), true)));
			$this->theme->view('page');
			exit;
		}

		if(!$account->authenticate($this->input->post('password')))
		{
			$this->session->set_userdata(array('login_attempts' => ($this->session->userdata('login_attempts') ? $this->session->userdata('login_attempts')++ : 1)));

			if($this->session->userdata('login_attempts') >= 3)
			{
				$this->theme->data(array('authentication_error' => "<p>Sorry, but either your email address or password was incorrect.</p>"));
				$this->theme->data(array('content' => $this->load->view('account/login/index', $this->theme->data(), true)));
				$this->theme->view('page');
				exit;
			}
			else
			{
				if(!$this->session->userdata('login_locked') || $this->session->userdata('login_locked') <= time())
				{
					$this->session->set_userdata(array('login_locked' => time() + 1800));
				}

				$this->theme->data(array('content' => $this->load->view('account/login/locked', $this->theme->data(), true)));
				$this->theme->view('page');
				exit;
			}
		}

		if(!$account->is_active)
		{

		}
				$this->session->set_userdata(array(	'user_id' => $account->_id, 
												'character_name' => $this->input->post('character')));

				if($this->session->userdata('login_referer'))
				{
					redirect($this->session->userdata('login_referer'));
				}
				else
				{
					redirect(site_url());
				}
			}
			else
			{
			}
		}
		else
		{
		}
	}
}

