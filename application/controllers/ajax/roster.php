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

		$filename = APPPATH .'cache/uGuilds/ajax_rosters/'. strformat($this->uguilds->guild->region) .'_'. strformat($this->uguilds->guild->realm) .'_'. strformat($this->uguilds->guild->name) .'.txt';

		if(file_exists($filename) && filemtime($filename) >= $this->uguilds->guild->getData()['lastModified']/1000)
		{
			$json = unserialize(file_get_contents($filename));
			http_response_code(304);		
		}
		else
		{
			$races = new uGuilds\Races(strtolower($this->uguilds->guild->region));
			$classes = new uGuilds\Classes(strtolower($this->uguilds->guild->region));

			$json = array('lastModified' => date('r', $this->uguilds->guild->getData()['lastModified']/1000),
				'members' => $this->uguilds->guild->getMembers('rank'),
				'races' => $races->getAll($this->uguilds->guild->getData()['side']),
				'classes' => $classes->getAll(),
				'ranks' => $this->uguilds->guild->ranks);

			file_put_contents($filename, serialize($json));
		}

		echo $this->ajax->asJSON($json);
	}
	
}