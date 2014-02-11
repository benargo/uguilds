<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * \uGuilds\models\Theme
 * 
 * @author Ben Argo
 * @version 1.1
 */

class Theme extends CI_Model 
{
	// Variables & Constants
	const DEFAULT_THEME = 'a6e284d6c07328787bb817c6a0000b29';

	// Properties
	private $_id;
	private $load;
	public $controller_name;

	// Files
	private $xml;
	private $css = array();
	private $javascript = array();
	private $images;
	private $views = array();
	private $data = array();

	/**
	 * __construct()
	 * 
	 * @access public
	 * @return void
	 */
	function __construct()

	{
		parent::__construct();
		$ci =& get_instance();
		$this->load =& $ci->load;
		$this->controller_name = ucwords($ci->router->directory) .'/'. ucwords($ci::controller_name());

		$this->_findById($ci->guild->theme);

		$this->data['theme_path'] = $this->getPath();
		$this->data['locale'] = $ci->guild->locale;
		$this->data['guild'] =& $ci->guild;
	}

	/**
	 * __get()
	 *
	 * @access public
	 * @param string $var
	 * @return mixed
	 */
	function __get($var)
	{
		switch($var)
		{
			case "_id": // Private
			case "id": // Public
				if(is_null($this->_id))
				{
					$this->_reset();
				}
				return $this->_id;
				break;

			case "name":
				return $this->name;
				break;

			case "data": // Prefered
			case "themeData":
				return $this->data;
				break;
		}
	}

	/**
	 * _findById()
	 * 
	 * @access private
	 * @return void
	 */
	private function _findById($id = self::DEFAULT_THEME)
	{
		$this->_id = $id;

		if(file_exists(FCPATH .'themes/'. $this->_id .'/theme.xml'))
		{
			$this->xml = simplexml_load_file(FCPATH .'themes/'. $this->_id .'/theme.xml');
			$this->getImages();
		}
	}

	/**
	 * _reset()
	 *
	 * A function which we can call if we need to fall back on the default theme	
	 * @access private
	 * @return void
	 */
	private function _reset()
	{
		$this->_findByID(self::DEFAULT_THEME);
	}

	/**
	 * getPath()
	 *
	 * @access public
	 * @param bool $prepend
	 * @return string
	 */
	public function getPath($prepend = false)
	{
		$path = '/themes/'. $this->_id;

		if($prepend)
		{
			$path = FCPATH .'themes/'. $this->_id;
		}

		return $path;
	}

	/**
	 * getCssFiles()
	 *
	 * @access public
	 * @return array
	 */
	public function getCssFiles() 
	{
		if(empty($this->css))
		{
			$this->css[] = FCPATH .'media/css/uGuilds.css';

			$files = scandir(FCPATH .'themes/'. $this->_id .'/css');
			foreach($files as $key => $value)
			{
				$files[$key] = FCPATH .'themes/'. $this->_id .'/css/'. $value;
			}

			$this->css = array_merge($this->css, preg_grep('/\.css$/', $files));

			if(is_dir(FCPATH .'media/css/Controller/'. $this->controller_name))
			{
				$files = scandir(FCPATH .'media/css/Controller/'. $this->controller_name);
				foreach($files as $key => $value)
				{
					$files[$key] = FCPATH .'media/css/Controller/'. $this->controller_name .'/'. $value;
				}

				$this->css = array_merge($this->css, preg_grep('/\.css$/', $files));
			}
		}

		return $this->css;
	}

	/**
	 * getJavaScriptFiles()
	 *
	 * @access public
	 * @return array
	 */
	public function getJavaScriptFiles()
	{
		if(empty($this->javascript))
		{
			$files = scandir(FCPATH .'themes/'. $this->_id .'/js');
			$this->javascript = array_merge($this->javascript, preg_grep('/\.min\.js$/', $files));

			if(is_dir(FCPATH .'media/js/Controller/'. $this->controller_name))
			{
				$files = scandir(FCPATH .'media/js/Controller/'. $this->controller_name);
				$this->javascript = array_merge($this->javascript, preg_grep('/\.min\.js$/', $files));
			}
		}

		return $this->javascript;
	}

	/**
	 * getImages()
	 *
	 * Scans the images directory and returns acceptable image formats
	 * Acceptable file endings are: gif, jp(e)g, png, tiff, svg  
	 * @access public
	 * @return array
	 */
	public function getImages()
	{
		if(empty($this->images))
		{
			$files = scandir(FCPATH .'themes/'. $this->_id .'/images');
			$this->images = preg_grep('/\.(gif|jpe?g|png|tiff|svg)$/', $files);
		}
		return $this->images;
	}

	/**
	 * get_includes()
	 * 
	 * @access public
	 * @return void
	 */
	private function get_includes()
	{
		$ci =& get_instance();
		$dir = scandir(APPPATH .'views/includes');
		foreach($dir as $file)
		{
			if(preg_match('/.*\.php$/', $file))
			{
				$file = str_replace('.php', '', $file);
				$this->data[$file] = $ci->load->view('includes/'. $file, $this->data, true);
			}
		}
	}

	/**
	 * data()
	 *
	 * @access public
	 * @param array $data
	 * @return views
	 */
	public function data(array $data = array())
	{
		$this->data = array_merge($this->data, $data);
		return $this->data;
	}

	/**
	 * view()
	 *
	 * Gets the requested view and returns it as data
	 * @see http://ellislab.com/codeigniter/user-guide/general/views.html
	 * @access public
	 * @param string $name
	 * @param array $data
	 * @return \CodeIgniter\View
	 */
	public function view($name, array $data = array(), $as_data = false)
	{
		$this->data = array_merge($this->data, $data);

		if(!is_link(APPPATH .'views/themes/'. $this->_id) && is_dir(FCPATH .'themes/'. $this->_id .'/views'))
		{
			symlink(FCPATH .'themes/'. $this->_id .'/views', APPPATH .'views/themes/'. $this->_id);
		}

		if(file_exists(readlink(APPPATH .'views/themes/'. $this->_id) .'/'. $name .'.php'))
		{
			if($name === 'page')
			{
				$this->get_includes();
			}
			
			return $this->load->view('themes/'. $this->_id .'/'. $name, $this->data, $as_data);
		}
	}

	/**
	 * set_controller_name()
	 *
	 * Overrides the controller name
	 *
	 * @access public
	 * @param string $name
	 * @return void
	 */
	public function set_controller_name($name)
	{
		$this->controller_name = ucwords($name);	
	}

}