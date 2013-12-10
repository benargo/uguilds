<?php
namespace uGuilds;

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Classes extends \BattlenetArmory\Classes {

    private $data;

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
     * __construct()
     * 
     * @access public
     * @return void
     */
    function __construct()
    {
        $ci =& get_instance();
        parent::__construct(strtolower($ci->uguilds->guild->region));
        foreach($this->datas as $key => $datum)
        {
            $this->data[$key] = (object) $datum;
        }
        unset($this->datas);
    }

    /**
     * __get()
     *
     * @access public
     * @param string $param
     * @return mixed
     */
    function __get($param)
    {
        switch($param)
        {
            case "data": // Prefered
            case "datas":
                return $this->data;
                break;
        }
    }

    /**
     * getClass()
     *
     * @access public
     * @param $id
     * @param $field
     * @return stdClass $datum
     */
    public function getClass($id, $field){
        return $this->data[$id]->$field;
    }    

    /**
     * getByName()
     *
     * @access public
     * @param string $name
     * @return array
     */
    public function getByName($name)
    {
        $name = ucwords(str_replace('-', ' ', $name));

        foreach($this->data as $class)
        {
            if($class->name == $name)
            {
                return $class;
            }
        }
    }

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
        foreach($this->data as $class)
        {
        	$classes[$class->id] = $class->name;
        }

        // Sort the races by name
        asort($classes);

        // Generate the return
        $return = array();
        foreach($classes as $id => $name)
        {
        	$return[$id] = $this->data[$id];
        }
        return $return;
    }
}