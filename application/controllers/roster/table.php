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

		$this->theme->data(array('page_title' => 'Guild Roster',
                                 'author' => $this->guild->name));
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

		$this->theme->data(array("races"   => $this->races,
							 	 "classes" => $this->classes,
							 	 "members" => $this->guild->getMembers('rank'),
							 	 "ranks"   => $this->guild->ranks,
							 	 "uri"	   => '/roster'));

		$this->theme->data(array("content" => $this->load->view('controllers/Roster/table', $this->theme->data(), true)));

		$this->theme->view('page');
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

		$data = array("races"   => $this->races,
					"classes" => $this->classes,
					"ranks"   => $this->guild->ranks);

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
		$data['uri'] = '/'.implode('/', $this->uri->segments);
		$data['members'] = $this->guild->getMembers('rank');
		$data['filtered'] = $this->guild->filter($params);

		$this->theme->data($data);
		$this->theme->data(array("content" => $this->load->view('controllers/Roster/table', $this->theme->data(), true)));
		$this->theme->view('page');	
	}
}

