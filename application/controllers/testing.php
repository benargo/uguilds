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
				'field' => 'email',
				'label' => 'Email Address',
				'rules' => 'trim|required|valid_email|xss_clean'
			),
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
			$this->load->library('email');

			$this->email->from('noreply@uguilds.net', 'uGuilds');
			$this->email->to($this->input->post('email'));
			$this->email->bcc('Ben.Argo@uwe.ac.uk');

			$this->email->subject('uGuilds Beta Testing Confirmation');
			$this->email->message("Thank you for taking part in uGuilds' ('the Service') user testing session ('the Session') today. This test will follow a supervised pre-planned methodology with the option for open questions and comments after the session.
By taking part in this user test you agree to the following terms and conditions:

- You agree that the data gathered during the Session will be retained and used for the improvement of the Service as provided under applicable law.
- You consent to audio recordings taking place during the course of the Session.
- You acknowledge that, where possible, data gathered during the Session will be anonymised. Unfortunately, it may not be possible to anonymise all information gathered.
- You have the right to withdraw from the Session at any time no questions asked. However, any anonymous data gathered prior to withdrawal will be retained.
- You acknowledge that all data gathered is done so in accordance Data Protection Act 1998.
- You acknowledge that the registered data controller for the Session is University of the West of England, Frenchay Campus, Coldharbour Lane, Bristol, BS16 1QY.
- You acknowledge that whilst every effort is made to ensure that the content of the Service is accurate, the Service is provided \"as is\" and makes no representations or warranties in relation to the accuracy or completeness of the information found on it.
- We do not warrant that the Service will be error, virus or bug free and you accept that it is Your responsibility to make adequate provision for protection against such threats.
Please sign below indicating that you agree to take part in the Session.

You should retain a copy of this document for your records.

Regards,
Ben Argo
uGuilds");

			$this->email->send();

			$this->session->set_userdata('testing', true);

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
		$query = $this->db->query("SELECT COUNT(_id) AS _id FROM ug_Accounts ORDER BY _id DESC");

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

			$this->load->view('testing/red', array(
				'test_email' => $this->get_test_email_address(),
				'password'	 => 'test'. date('Ymd'),
			));
		}
		else
		{
			$this->load->helper('url');
			redirect('/testing');
		}
	}
}

