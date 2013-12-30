<?php namespace uGuilds;

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * uGuilds\Theme
 *
 * @author Ben Argo <ben@benargo.com>
 */
class Theme {

	const DEFAULT_THEME = 'a6e284d6c07328787bb817c6a0000b29';

	/**
	 * variables
	 */
	private $_id;
	private $name;
	private $css = array();
	private $javascript;
	private $jquery_version = '2.0.0';
	private $themeData;

	/**
	 * __construct()
	 *
	 * @access public
	 * @return void
	 */
	function __construct()
	{
		$this->themeData = new ThemeData;
	}

	/**
	 * __get()
	 *
	 * @access public
	 * @param $var
	 * @return mixed
	 */
	function __get($var)
	{
		switch($var)
		{
			case "id":
				return $this->_id;
				break;

			case "name":
				return $this->name;
				break;

			case "css":
				return $this->getCssFiles();
				break;

			case "javascript":
				return $this->getJavaScriptFiles();
				break;

			case "jquery_version": /* preferred */
			case "jQuery":
				return $this->jquery_version;

			case "data": /* preferred */
			case "themeData":
				return $this->themeData;
		}
	}

	/**
	 * load()
	 * 
	 * @access public
	 * @static true
	 * @return $this
	 */
	public static function load()
	{
		$ci =& get_instance();
		return $ci->uguilds->theme;
	}

	/** 
	 * findByID()
	 *
	 * @access public
	 * @param string $_id
	 * @return void
	 */
	public function findByID($id = NULL) 
	{
		// Check if we've passed an ID in. 
		// If we have, this is an override
		if(isset($id))
		{
			$this->_id = $id;
		}

		if(file_exists(FCPATH .'/themes/'. $this->_id .'/theme.json'))
		{
			$theme_json = json_decode(file_get_contents(FCPATH .'/themes/'. $this->_id .'/theme.json'));
			foreach($theme_json as $key => $value)
			{
				$this->$key = $value;
			}
		}
	}

	/**
	 * reset()
	 *
	 * A function which we can call if we need to fall back on the default theme
	 * @access private
	 * @return $this
	 */
	private function reset() 
	{
		$this->findByID(self::DEFAULT_THEME);

		return $this;
	}

	/**
	 * getCssFiles()
	 *
	 * @access public
	 * @return array
	 */
	public function getCssFiles() 
	{
		// No CSS, fall back to the default theme
		if(empty($this->css))
		{
			return $this->reset();
		}

		// Create an empty array
		$files = $this->getControllerCss();

		// Loop through each of the CSS files
		foreach($this->css as $css) 
		{
			// If this file exists, keep going
			if(file_exists(FCPATH .'/themes/'. $this->_id .'/css/'. $css->url))
			{
				// Provide a default in case there's no media type
				if(is_null($css->media))
				{
					$css->media = 'screen';
				}

				$files .= "\t".'<link rel="stylesheet" media="'. $css->media .'" href="/themes/'. $this->_id .'/css/'. $css->url .'">'."\n";
			}
		}

		// Return the CSS Files
		return $files;
	}

	/**
	 * getControllerCss()
	 *
	 * Prints out any additional CSS files neccessary for the controller. 
	 * This is defined by uGuilds and applies to all themes.
	 * i.e. All themes have these basic standards for the controllers and they may be overwritten per theme.
	 *
	 * @access private
	 * @return string
	 */
	private function getControllerCss() 
	{
		$ci =& get_instance();
		$controller_name = get_class($ci);
		$return = '';


		if(is_dir(FCPATH .'media/css/controller/'. $controller_name))
		{
			$files = scandir(FCPATH .'media/css/controller/'. $controller_name .'/');
			$files = preg_grep('/\.css$/', $files);
			foreach($files as $file)
			{
				$return .= "\t".'<link rel="stylesheet" media="all" href="/media/css/controller/'. $controller_name .'/'. $file .'" />'."\n";
			}
		}

		return $return;
	}

	/**
	 * getJavaScriptFiles()
	 *
	 * @access public
	 * @return array
	 */
	public function getJavaScriptFiles()
	{
		/**
		 * Create an empty array, including defaults:
		 * - Controller JS
		 * - jQuery
		 * - Google Analytics
		 */

		$ci =& get_instance();
		$files = '';
	
		// jQuery Migrate
		if(version_compare($this->jquery_version, '1.9.0', '>='))
		{
			$files .= '<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>'."\n";
		}
		
		// Google Analytics
		$files .= 	"\t<script><!--\n".
					"\t\t/* Google Analytics */\n".
					"\t\tvar _gaq = _gaq || [];\n".
  					"\t\t_gaq.push(['_setAccount', 'UA-45138102-1']);\n".
  					"\t\t_gaq.push(['_setDomainName', '". $ci->uguilds->domain ."']);\n".
  					"\t\t_gaq.push(['_setAllowLinker', true]);".
  					"\t\t_gaq.push(['_trackPageview']);\n".
  					"\t--></script>\n";

  		
		// Controller JS
		$files .= $this->getControllerJS();

		// Failsafe in case of no JavaScript
		if(empty($this->javascript))
		{
			return $files;
		}

		// Loop through each of the JavaScript files
		foreach($this->javascript as $js)
		{
			// If the file exists, keep going
			if(file_exists(FCPATH .'/themes/'. $this->_id .'/js/'. $js))
			{
				$files .= "\t".'<script src="/themes/'. $this->_id .'/js/'. $js .'"></script>'."\n";
			}
		}

		return $files;
	}

	/**
	 * loadHeader()
	 *
	 * This loads the theme-specific header
	 *
	 * @access public
	 * @return view
	 */
	public function loadHeader()
	{
		$ci =& get_instance();
		return $ci->load->view('themes/'. $this->_id .'/header', $this->data);
	}

	/**
	 * loadFooter()
	 *
	 * This loads the theme-specific footer
	 *
	 * @access public
	 * @return view
	 */
	public function loadFooter()
	{
		$ci =& get_instance();
		return $ci->load->view('themes/'. $this->_id .'/footer', $this->data);
	}

	/**
	 * getPath()
	 *
	 * Gets the path for the theme files
	 *
	 * @access public
	 * @return string
	 */
	public function getPath()
	{
		return "/themes/". $this->_id;
	}
}

