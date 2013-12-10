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
							 "ranks"   => $this->uguilds->guild->ranks);

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
		if($this->uri->segments !== array_unique($this->uri->segments))
		{
			$this->load->helper('url');
			redirect('/'.implode('/', array_unique($this->uri->segments)));
		}

		$races = new uGuilds\Races;
		$classes = new uGuilds\Classes;

		$data = array("races"   => $races,
					  "classes" => $classes,
					  "ranks"   => $this->uguilds->guild->ranks,
					  "uri"		=> '/'.implode('/', $this->uri->segments));

		$params = array();
		$segments = array_slice($this->uri->segments, 1);
		foreach($segments as $segment)
		{
			list($key, $value) = explode("=", $segment);
			$params[$key] = $value;
			if($key == 'race' || $key == 'class')
			{
				$params[$key] = $data[$key.'s']->getByName($value)->id;
			}
		}

		$data['members'] = $this->uguilds->guild->filter($params);

		// Load the roster table header and filter system
		$this->load->view('controllers/Roster/header.php', $this->data($data));

		// Load the roster list
		$this->load->view('controllers/Roster/list.php', $this->data($data));
		unset($races, $classes, $data);

		// Load the footer
		$this->_loadFooter();
		
	}

}