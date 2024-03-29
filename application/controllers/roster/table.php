<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Table extends UG_Controller 
{
	/**
	 * Construction function
	 *
	 * @access public
	 */
	public function __construct() 
	{
		parent::__construct();

		$this->config->load('battle.net');
		$this->load->helper('battlenet');
		$this->load->model('races');
		$this->load->model('classes');

		header('Last-Modified: '. date('r', $this->guild->getData()['lastModified']/1000));
		header('Cache-Control: max-age='. $this->config->item('battle.net')['GuildsTTL']);

		$this->data['page_title'] = 'Guild Roster';
	}


	/**
	 * index()
	 *
	 * @access public
	 */
	public function index()
	{
		$this->data['races'] = $this->races;
		$this->data['classes'] = $this->classes;
		$this->data['members'] = $this->guild->getMembers('rank');
		$this->data['ranks'] = $this->guild->ranks;
		$this->data['uri'] = '/roster';

		$this->data['subview'] = 'controllers/Roster/table';

		$this->render();
	}
}

