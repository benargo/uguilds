<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UG_Controller extends CI_Controller {

	// Variables
	protected $page_title;
	protected $page_author;

	/**
	 * data()
	 * 
	 * @access protected
	 * @param $extra_data
	 * @return array
	 */
	protected function data($extra_data = NULL)
	{
		$data = array(/*"account"	=> $this->uguilds->account,*/
					 "guild"	=> $this->uguilds->guild,
					 "locale"	=> $this->uguilds->locale);

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
	 * _setPageTitle()
	 *
	 * @access protected
	 * @param $title
	 * @return void
	 */
	protected function _setPageTitle($title = "{{ missing: page_title }}")
	{
		$this->page_title = $title;
	}

	/**
	 * _setPageAuthor()
	 *
	 * @access protected
	 * @param $author
	 * @return void
	 */
	protected function _setPageAuthor($author = NULL)
	{
		if(is_null($author))
		{
			$author = uGuilds\Guild::instance()->name;
		}
		
		$this->page_author = $author;
	}

	/**
	 * _loadHeader()
	 *
	 * @access protected
	 * @return output
	 */
	protected function _loadHeader()
	{
		$this->load->view('includes/head', $this->data());
		$this->uguilds->theme->loadHeader();
	}

	/**
	 * _loadFooter()
	 *
	 * @access protected
	 * @return output
	 */
	protected function _loadFooter()
	{
		$this->load->view('includes/footer', $this->data());
	}
}