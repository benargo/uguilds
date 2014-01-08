<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UG_Controller extends CI_Controller {

	function __construct()
	{
		parent::__construct();
	}

	/**
	 * data()
	 * 
	 * @access protected
	 * @param $extra_data
	 * @return array
	 */
	protected function data($extra_data = NULL)
	{
		$data = array(/*"account"	=> $this->account,*/
					 "guild"	=> $this->guild,
					 "locale"	=> $this->guild->locale);

		if($extra_data)
		{	
			if(is_array($extra_data))
			{
				$data = array_merge($data, $extra_data);
			}
			else
			{
				array_push($data, $extra_data);
			}
		}

		return $data;
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