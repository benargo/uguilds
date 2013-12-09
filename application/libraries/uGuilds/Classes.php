<?php
namespace uGuilds;

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Classes extends \BattlenetArmory\Classes {

    private $names = array(
        1  => 'Warrior',
        2  => 'Paladin',
        3  => 'Hunter',
        4  => 'Rogue',
        5  => 'Priest',
        6  => 'Death Knight',
        7  => 'Shaman',
        8  => 'Mage',
        9  => 'Warlock',
        10 => 'Monk',
        11 => 'Druid');

	/**
	 * getIcon()
	 *
	 * @access public
	 * @param int $id
	 * @return string
	 */
	public function getIcon($id, $size = 18)
    {
        $name = strtolower(preg_replace('/\ /', '', $this->names[$id]));

        if($size != 18 && $size != 56)
        {
            $size = 18;
        }

        if(!file_exists(FCPATH ."media/images/icons/$size/classicon_$name.jpg"))
        {
        	$image = imagecreatefromjpeg("http://media.blizzard.com/wow/icons/$size/classicon_$name.jpg");
        	imagejpeg($image, FCPATH ."media/images/icons/$size/classicon_$name.jpg", 100);
        }

    	return "/media/images/icons/$size/classicon_$name.jpg";
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
        	$return[$id] = $this->datas[$id];
        }
        return $return;
    }
}