<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Uguilds extends CI_Migration 
{
	public function up()
	{
		$this->db->simple_query(file_get_contents(APPPATH .'migrations/sql/011_uguilds.sql'));
	}

	public function down()
	{
		
	}
}