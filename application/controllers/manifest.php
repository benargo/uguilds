<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manifest extends UG_Controller {

	private $files = array();

	/**
	 * Construction function
	 * 
	 * @access public
	 */
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		if(ENVIRONMENT == 'productions')
		{
			header('Content-type: text/cache-manifest');
		
			$this->_getEmblems();
			$this->_getSystemMedia();
			$this->_getThemeFiles();

			$this->load->view('controllers/Manifest/manifest', array('files' => $this->files));
		}
	}

	/**
	 * _getEmblems()
	 *
	 * @access private
	 * @return void
	 */
	private function _getEmblems()
	{
		$files = scandir(FCPATH.'media/BattlenetArmory');
		$files = preg_grep('/emblem_'.
				strtoupper($this->uguilds->guild->region).'_'.
				preg_replace('/\ /', '_',$this->uguilds->guild->realm).'_'.
				preg_replace('/\ /', '_', $this->uguilds->guild->name).'_/', $files);

		foreach($files as $file)
		{
			$this->files[] = '/media/BattlenetArmory/'.$file;
		}
	}

	/**
	 * _getSystemCSS()
	 *
	 * @access private
	 * @return void
	 */
	private function _getSystemMedia()
	{
		$this->files[] = '//code.jquery.com/jquery-1.10.2.min.js';
		$iterator = new RecursiveDirectoryIterator(FCPATH.'media');
		foreach (new RecursiveIteratorIterator($iterator) as $filename => $file) 
		{
			if(preg_match_all('/\.css|\.eot|\.svg|\.ttf|\.woff|\.jpe?g|\.gif|\.png|\.tiff|\.min\.js$/', $file->getFileName())
				&& !preg_match_all('/\/BattlenetArmory\//', $file->getPathname()))
			{
				$this->files[] = preg_replace('/'.str_replace('/','\/',FCPATH).'/','/',$file->getPathname());
			}
		}
	}

	/**
	 * _getThemeFiles()
	 *
	 * @access private
	 * @return void
	 */
	private function _getThemeFiles() 
	{
		$files = simplexml_load_file(FCPATH.'themes/'.$this->uguilds->theme->id .'/theme.xml')->files;
		foreach($files->file as $file)
		{
			$this->files[] = '/themes/'. $this->uguilds->theme->id .'/'. (string) $file;
		}
	}
}