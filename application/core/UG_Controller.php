<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UG_Controller extends CI_Controller {

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