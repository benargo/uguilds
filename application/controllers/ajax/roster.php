<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Roster extends UG_Controller {

	/**
	 * __construct()
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('ajax');
	}
	
	/**
	 * all()
	 *
	 * @access public
	 * @return json
	 */
	public function all()
	{
		header('Content-Type: application/json');
		header('Last-Modified: '.date('r', $this->uguilds->guild->getData()['lastModified']/1000));

		$races = new uGuilds\Races(strtolower($this->uguilds->guild->region));
		$classes = new uGuilds\Classes(strtolower($this->uguilds->guild->region));

		$json = array('lastModified' => date('r', $this->uguilds->guild->getData()['lastModified']/1000),
			'members' => $this->uguilds->guild->getMembers('rank'),
			'races' => $races->getAll($this->uguilds->guild->getData()['side']),
			'classes' => $classes->getAll());

		echo $this->ajax->asJSON($json);
	}
	
}