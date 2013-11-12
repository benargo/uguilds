<?php
namespace uGuilds;

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Classes extends \BattlenetArmory\Classes {

	/**
	 * getIcon()
	 *
	 * @access public
	 * @param int $id
	 * @return string
	 */
	public function getIcon($id) 
    {
        if(!file_exists(FCPATH .'media/images/classes/class_'. $id . '.jpg'))
        {
        	$image = imagecreatefromjpeg('http://media.blizzard.com/wow/icons/18/class_'. $id .'.jpg');
        	imagejpeg($image, FCPATH .'media/images/classes/class_'. $id .'.jpg', 100);
        }

    	return '/media/images/classes/class_'. $id .'.jpg';
    }

    /**
     * getAll()
     * 
     * @access public
     * @return array
     */
    public function getAll()
    {
        $classes = array();
        foreach($this->datas as $class)
        {
        	$classes[$class['id']] = $class['name'];
        }

        // Sort the races by name
        asort($classes);

        // Generate the return
        $return = array();
        foreach($classes as $id => $name)
        {
        	$return[] = $this->datas[$id];
        }
        return $return;
    }
}