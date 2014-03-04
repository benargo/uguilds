<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UG_Controller extends CI_Controller 
{
	protected $controller_name;
	private $domain;
	protected $account;
	public $guild;
	public $data;

	/**
	 * __construct()
	 *
	 * Initialise the class
	 *
	 * @access public
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();

		$this->controller_name = ucwords($this->router->directory) . get_class($this);

		$this->find_guild();
		$this->data['locale'] = $this->guild->locale;

		$this->theme->find_by_id($this->guild->theme);
		$this->data['theme'] =& $this->theme;

		if($this->session->userdata('user_id'))
		{
			$this->account = new uGuilds\Account($this->session->userdata('user_id'));
			$this->data['account'] =& $this->account;
		}
	}

	/**
	 * controller()
	 *
	 * Returns the controller name
	 *
	 * @access public
	 * @return string
	 */
	public function controller()
	{
		return $this->controller_name;
	}

	/**
	 * render()
	 *
	 * Renders the final page
	 *
	 * @access protected
	 * @return void
	 */
	protected function render()
	{
		$this->load->view($this->theme->get_page(), $this->data);
	}

	/**
	 * find_guild()
	 *
	 * Sets the domain if it's not been set yet,
	 * then loads a guild, either from the cache or by creating a new one
	 * 
	 * @access private
	 * @return void
	 */
	private function find_guild() 
	{
		// Look up the domain name
		if(is_null($this->domain)) 
		{
			$this->set_domain();
		}

		// Check if there's a cache file for this guild and it's valid
		if(file_exists(APPPATH . 'cache/uGuilds/guild_objects/'. $this->domain .'.txt') 
			&& filemtime(APPPATH . 'cache/uGuilds/guild_objects/'. $this->domain .'.txt') >= time() - $this->config->item('GuildTTL', 'battle.net'))
		{
			$this->guild = unserialize(file_get_contents(APPPATH . 'cache/uGuilds/guild_objects/'. $this->domain .'.txt'));
			$this->data['guild'] =& $this->guild;
		}
		else // No cache file, generate one from the database
		{
			$this->guild = new uGuilds\Guild($this->domain);
			$this->data['guild'] =& $this->guild;
		}
	}

	/** 
	 * set_domain()
	 *
	 * If the domain is null, set it, simple.
	 *
	 * @access private
	 * @return void
	 */
	private function set_domain() 
	{
		// Determine the domain name from SERVER_NAME
		$this->domain = $_SERVER['SERVER_NAME'];

		// If they're running on the application domain name, then return a 403 Forbidden error
		if($this->domain === "app.uguilds.net") 
		{
			show_error("The application cannot be run on 'app.uguilds.net'", 403);
		}
	}
}