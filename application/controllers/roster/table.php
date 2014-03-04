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
		$this->load->model('races');
		$this->load->model('classes');

		$this->data['races'] = $this->races;
		$this->data['classes'] = $this->classes;
		$this->data['members'] = $this->guild->getMembers('rank');
		$this->data['ranks'] = $this->guild->ranks;
		$this->data['uri'] = '/roster';

		$this->data['subview'] = 'controllers/Roster/table';

		$this->render();
	}

	/**
	 * filter()
	 *
	 * @access public
	 */
	public function filter()
	{
		$this->load->model('races');
		$this->load->model('classes');

		$this->data['races'] = $this->races;
		$this->data['classes'] = $this->classes;
		$this->data['ranks'] = $this->guild->ranks;

		$params = array();
		$segments = array_slice($this->uri->segments, 1);
		foreach($segments as $segment)
		{
			list($key, $value) = explode("=", $segment);
			$params[$key] = $value;
			if($key == 'race')
			{
				$params[$key] = $data['races']->getByName($value)->id;
			}
			if($key == 'class')
			{
				$params[$key] = $data['classes']->getByName($value)->id;
			}

		}

		$this->data['uri'] = '/'.implode('/', $this->uri->segments);
		$this->data['members'] = $this->guild->getMembers('rank');
		$this->data['filtered'] = $this->guild->filter($params);

		$this->data['subview'] = 'controllers/Roster/table';

		$this->render();
	}
}

