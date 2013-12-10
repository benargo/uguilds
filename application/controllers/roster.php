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
		$this->_setPageTitle('Guild Roster');
		$this->_setPageAuthor($this->uguilds->guild->guildName);

		// Load the header
		$this->_loadHeader();
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

		$custom_data = array("races"   => $races,
							 "classes" => $classes,
							 "members" => $this->uguilds->guild->getMembers('rank'),
							 "ranks"   => $this->uguilds->guild->ranks,
							 "uri"	   => '/roster');

		// Load the roster table header and filter system
		$this->load->view('controllers/Roster/header.php', $this->data($custom_data));

		// Load the roster list
		$this->load->view('controllers/Roster/list.php', $this->data($custom_data));
		unset($races, $classes, $custom_data);

		// Load the footer
		$this->_loadFooter();
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