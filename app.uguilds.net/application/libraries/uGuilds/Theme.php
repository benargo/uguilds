<?php namespace uGuilds;

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * uGuilds\Theme
 *
 * @author Ben Argo <ben@benargo.com>
 */
class Theme {

	/**
	 * variables
	 */
	private $_id;
	private $_rev;
	private $name;
	private $css;
	private $javascript;
	private $jquery_version = '2.0';
	private $theme_data;

	/**
	 * __construct()
	 *
	 * @access public
	 * @return void
	 */
	public function __construct()
	{

	}

	/**
	 * findByName()
	 *
	 * @access public
	 * @param string $name
	 * @return void
	 */
	public function findByName($name) 
	{
		// Get a copy of the database
		$ci = get_instance();
		$db = $ci->couchdb;

		// Find the theme ID
		$this->_id = $db->startkey($name)->limit(1)->getView('find_theme', 'by_name')->rows[0]->value;

		// Find the theme
		$this->findByID();
	}

	/** 
	 * findByID()
	 *
	 * @access private
	 * @param string $_id
	 * @return void
	 */
	private function findByID($id = NULL) 
	{
		// Check if we've passed an ID in. 
		// If we have, this is an override
		if(isset($id))
		{
			$this->_id = $id;
		}

		// Get a copy of the database
		$ci = get_instance();
		$db = $ci->couchdb;

		// Create the new Theme
		$doc = $db->asCouchDocuments()->getDoc($this->_id);

		foreach($doc->getFields() as $key => $value) 
		{
			if(empty($doc->$key)) 
			{
				continue;
			}

			$this->$key = $doc->$key;
		}
	}

	/**
	 * reset()
	 *
	 * A function which we can call if we need to fall back on the default theme
	 * @access private
	 * @param function $functionName
	 * @return result of another function
	 */
	private function reset($functionName = NULL) 
	{
		$this->findByName('default');

		if($functionName)
		{
			return $this->{$functionName}();
		}
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
		$files = array(
			'<link rel="stylesheet" media="all" href="/media/css/reset.css">'."\n\t",
			$this->getControllerCss());

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

				$files[] = '<link rel="stylesheet" media="'. $css->media .'" href="/themes/'. $this->_id .'/css/'. $css->url .'">'."\n\t";
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
		$ci = get_instance();
		$controller_name = get_class($ci);
		
		if(file_exists(FCPATH.'/media/css/controller/'. $controller_name .'.css'))
		{
			return '<link rel="stylesheet" media="all" href="/media/css/controller/'. $controller_name .'.css">'."\n\t";
		}
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

		$ci = get_instance();

		$files = array(
			// jQuery
			'<script src="//ajax.googleapis.com/ajax/libs/jquery/'. $this->jquery_version .'/jquery.min.js"></script>'."\n",
			// Controller JS
			$this->getControllerJS(),
			// Google Analytics
			"\t<script><!--\n".
			"\t\t/* Google Analytics */\n".
			"\t\tvar _gaq = _gaq || [];\n".
  			"\t\t_gaq.push(['_setAccount', 'UA-45138102-1']);\n".
  			"\t\t_gaq.push(['_setDomainName', '". $ci->uguilds->getDomain() ."']);\n".
  			"\t\t_gaq.push(['_setAllowLinker', true]);".
  			"\t\t_gaq.push(['_trackPageview']);\n".
  			"\t--></script>\n");

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
				$files[] = "\t".'<script src="/themes/'. $this->_id .'/js/'. $js .'"></script>'."\n";
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
	 * @access private
	 * @return string
	 */
	private function getControllerJS()
	{
		$ci = get_instance();
		$controller_name = get_class($ci);
		
		if(file_exists(FCPATH.'/media/js/controller/'. $controller_name .'.js'))
		{
			return "\t".'<script src="/media/js/controller/'. $controller_name .'.js">'."\n";
		}
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
		$ci = get_instance();
		return $ci->load->view('themes/'. $this->_id .'/header');
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
		$ci = get_instance();
		return $ci->load->view('themes/'. $this->_id .'/footer');
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

	/**
	 * renderAdminMenu()
	 *
	 * @access public
	 * @return string
	 */
	public function renderAdminMenu()
	{
		$ci = get_instance();

	}

	/**
	 * renderLoginLink()
	 *
	 * @access public
	 * @return string
	 */
	public function renderLoginLink()
	{
		$ci = get_instance();

	}

}

