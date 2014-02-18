<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Testing extends UG_Controller 
{
	/**
	 * Construction function
	 *
	 * @access public
	 */
	public function __construct() 
	{
		parent::__construct();
	}

	public function index()
	{
		$this->load->helper('form');

		$this->load->view('testing/index');
	}

	public function begin()
	{
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules(array(
			array(
				'field' => 'terms',
				'label' => 'Terms and Conditions',
				'rules' => 'required'
			),
		));

		if($this->form_validation->run() === FALSE)
		{
			$this->load->view('testing/index');
		}
		else
		{
			$this->load->view('testing/routes');
		}
	}

	public function routes()
	{
		if($this->session->userdata('testing') === true)
		{
			$this->load->helper('form');

			$this->load->view('testing/routes');
		}
		else
		{
			$this->load->helper('url');
			redirect('/testing');
		}
	}

	private function get_test_email_address()
	{
		$query = $this->db->query("SELECT COUNT(_id) AS id FROM ug_Accounts ORDER BY id DESC");

		if($query->num_rows() > 0)
		{
			$row = $query->row();

			return 'test'. ((int) $row->_id + 1) .'@uguilds.net';
		}
	}

	public function red()
	{
		if($this->session->userdata('testing') === true)
		{
			$this->load->helper('form');
			$this->load->helper('url');

			$this->load->view('testing/red');
		}
		else
		{
			$this->load->helper('url');
			redirect('/testing');
		}
	}

	public function green()
	{
		if($this->session->userdata('testing') === true)
		{
			$this->load->helper('form');
			$this->load->helper('url');

			$this->load->view('testing/green');
		}
		else
		{
			$this->load->helper('url');
			redirect('/testing');
		}
	}

	public function purple()
	{
		if($this->session->userdata('testing') === true)
		{
			$this->load->helper('form');
			$this->load->helper('url');

			$this->load->view('testing/purple');
		}
		else
		{
			$this->load->helper('url');
			redirect('/testing');
		}
	}

	public function feedback()
	{
		$this->load->helper('form');

		$this->load->library('email');

		$this->email->from('noreply@uguilds.net', 'uGuilds');
		$this->email->to('logs@uguilds.net');

		$this->email->subject('uGuilds Beta Testing: '. ucfirst($this->input->post('route')) .' Route Feedback');
		$this->email->message($this->input->post('comments'));

		$this->email->send();

		$this->load->view('testing/thanks');
	}

	public function logout()
	{
		$this->session->unset_userdata('testing');
		$this->load->helper('url');

		redirect('testing');
	}
}

