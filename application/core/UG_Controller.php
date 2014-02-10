<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UG_Controller extends CI_Controller {

	protected static $controller_name;
	public $domain;
	public $guild;

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

		// Set the controller name
		if(is_null(self::$controller_name))
		{
			self::$controller_name = get_class($this);
		}

		// Look up the domain name
		if(is_null($this->domain)) 
		{
			// Determine the domain name from SERVER_NAME
			$this->domain = $_SERVER['SERVER_NAME'];

			// If they're running on the application domain name, then return a 403 Forbidden error
			if($this->domain === "app.uguilds.net") 
			{
				show_error("The application cannot be run on 'app.uguilds.net'", 403);
			}
		}

		$cache_path = APPPATH .'cache/uGuilds/WoW/guild_objects/'. $this->domain;

		// Check if there's a cache file for this guild and it's valid
		if(file_exists($cache_path)
			&& filemtime($cache_path) >= time() - $this->config->item('battle.net')['GuildsTTL'])
		{
			$this->guild = unserialize(file_get_contents($cache_path));
		}
		else // No cache file, generate one from the database
		{
			$this->guild = new uGuilds\WoW\Guild($this->domain);

			// If, for some reason, we were unable to fetch the guild from Battle.net
			if(empty($this->guild->data))
			{
				unset($this->guild);

				$this->guild = unserialize(file_get_contents($cache_path));
			}
		}

		// Load the theme model
		$this->load->model('theme');
	}

	/**
	 * controller_name()
	 *
	 * Returns the controller name
	 * 
	 * @access public
	 * @static
	 * @return string
	 */
	public static function controller_name()
	{
		return self::$controller_name;
	}

	/**
	 * data()
	 * 
	 * @access protected
	 * @param $extra_data
	 * @return array
	 */
	protected function data(array $extra_data = array())
	{
		return $this->theme->data($extra_data);
	}

	/**
	 * render()
	 *
	 * Renders the final page
	 *
	 * @access protected
	 * @return text/html
	 */
	protected function render()
	{
		$this->theme->view('page');
	}


	/**
	 * getNamespace()
	 *
	 * Gets the namespace of the current namespace
	 * Can only be called by the child classes
	 *
	 * @access public
	 * @return string
	 */
	public function getNamespace()
	{
		if(get_class($this) != 'UG_Controller')
		{
			return __NAMESPACE__;
		}
	}

	/**
	 * getControllerCss()
	 *
	 * Prints out any additional CSS files neccessary for the controller. 
	 * This is defined by uGuilds and applies to all themes.
	 * i.e. All themes have these basic standards for the controllers and they may be overwritten per theme.
	 *
	 * @access public
	 * @return string
	 */
	public function getControllerCSS() 
	{
		$controller_name = get_class($this);
		$files = array();

		if(is_dir(FCPATH .'media/css/controller/'. ucwords($this->router->directory) . ucwords($controller_name)))
		{
			$files = scandir(FCPATH .'media/css/controller/'. ucwords($this->router->directory) . ucwords($controller_name) .'/');
			$files = preg_grep('/\.css$/', $files);

			foreach($files as $key => $value)
			{
				$files[$key] = '/media/css/controller/'. ucwords($this->router->directory) . ucwords($controller_name) .'/'. $value;
			}
		}

		return $files;
	}

	/**
	 * getControllerJS()
	 *
	 * Prints out any additional JS files neccessary for the controller. 
	 * This is defined by uGuilds, applies to all themes and cannot be overwritten.
	 *
	 * @access public
	 * @return string
	 */
	public function getControllerJS()
	{
		$controller_name = str_replace('_', '/', get_class($this));
		$files = array();

		if(is_dir(FCPATH .'media/js/controller/'. ucwords($this->router->directory) . ucwords($controller_name) .'/'))
		{
			$files = scandir(FCPATH .'media/js/controller/'. ucwords($this->router->directory) . ucwords($controller_name) .'/');
			$files = preg_grep('/\.min\.js/', $files);

			foreach($files as $key => $value)
			{
				$files[$key] = '/media/js/controller/'. ucwords($this->router->directory) . ucwords($controller_name) .'/'. $value;
			}
		}

		return $files;
	} 
}