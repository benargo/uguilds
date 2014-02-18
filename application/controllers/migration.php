<?php

class Migration extends UG_Controller
{
	function __construct()
	{
		parent::__construct();

		if(!$this->input->is_cli_request())
		{
			show_error('Access to Migrations is limited to the CLI', 403);
		}
	}

	public function index()
	{
		$this->load->library('')
	}
}