<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UG_Controller extends CI_Controller {

	protected static $controller_name;

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

		if(is_null(self::$controller_name))
		{
			self::$controller_name = get_class($this);
		}
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
	protected function data( array $extra_data = array() )
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
		echo 'Rendering:';
		$this->theme->view('page', $this->data());
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
		$ci =& get_instance();
		$controller_name = get_class($ci);
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
		$ci =& get_instance();
		$controller_name = str_replace('_', '/', get_class($ci));
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