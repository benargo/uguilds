<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Roster extends UG_Controller {

	/**
	 * Construction function
	 *
	 * @access public
	 */
	public function __construct() 
	{
		parent::__construct();
		$this->theme->data(array('page_title' => 'Guild Roster',
					  			 'author' => $this->guild->name));
		$this->theme->getIncludes();
	}

	/**
	 * index()
	 *
	 * @access public
	 * @see all()
	 */
	public function index()
	{
		$this->all();
	}

	/**
	 * all()
	 *
	 * @access public
	 */
	public function all()
	{
		$races = new uGuilds\Races;
		$classes = new uGuilds\Classes;	

		$this->theme->data(array("races"   => $races,
							 "classes" => $classes,
							 "members" => $this->guild->getMembers('rank'),
							 "ranks"   => $this->guild->ranks));

		$this->theme->data(array("content" => $this->load->view('controllers/Roster/header', $this->theme->data(), true)
							 			. $this->load->view('controllers/Roster/list', $this->theme->data(), true)));

		$this->theme->view('page');
	}

	/**
	 * filter()
	 *
	 * @access public
	 */
	public function filter()
	{

		$races = new uGuilds\Races;
		$classes = new uGuilds\Classes;

		$data = array("races"   => $races,
					  "classes" => $classes,
					  "ranks"   => $this->uguilds->guild->ranks);

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
		$data['members'] = $this->uguilds->guild->getMembers('rank');
		$data['filtered'] = $this->uguilds->guild->filter($params);

		// Load the roster table header and filter system
		$this->load->view('controllers/Roster/header.php', $this->data($data));

		// Load the roster list
		$this->load->view('controllers/Roster/list.php', $this->data($data));
		unset($races, $classes, $data);

		// Load the footer
		$this->_loadFooter();
		
	}

}