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
		$this->config->load('battle.net');

		header('Content-Type: application/json');
		header('Last-Modified: '.date('r', $this->guild->getData()['lastModified']/1000));
		header('Cache-Control: max-age='. $this->config->item('battle.net')['GuildsTTL']);

		$filename = APPPATH .'cache/uGuilds/ajax_rosters/'. strformat($this->guild->region) .'_'. strformat($this->guild->realm) .'_'. strformat($this->guild->name) .'.txt';

		if(file_exists($filename) && filemtime($filename) >= $this->guild->getData()['lastModified']/1000)
		{
			$json = unserialize(file_get_contents($filename));
		}
		else
		{
			$this->load->model('races');
			$this->load->model('classes');

			$json = array('lastModified' => date('r', $this->guild->getData()['lastModified']/1000),
				'members' => $this->guild->getMembers('rank'),
				'races' => $this->races->getAll($this->guild->getData()['side']),
				'classes' => $this->classes->getAll(),
				'ranks' => $this->guild->ranks);

			file_put_contents($filename, serialize($json));
		}

		echo $this->ajax->asJSON($json);
	}
	
}