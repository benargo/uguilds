<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('save_file'))
{
	function save_file($destination, $content) 
	{
		// Determine the true destination
		$destination = explode('/', $destination);
		$directory = array_slice($destination, 0, -1);

		// Make the directory
		if(!is_dir(implode('/', $directory))) mkdir(implode('/', $directory), 0777, true);
		
		// Save the file
		file_put_contents(implode('/', $destination), $content);
	}
}