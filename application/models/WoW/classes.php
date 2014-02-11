<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH .'libraries/BattlenetArmory/Classes.php');

class Classes extends CI_Model
{
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

    private $talent_calculator_ids = array(
        1  => 'Z',
        2  => 'b',
        3  => 'Y',
        4  => 'c',
        5  => 'X',
        6  => 'd',
        7  => 'W',
        8  => 'e',
        9  => 'V',
        10 => 'f',
        11 => 'U');

    /**
     * __construct()
     * 
     * @access public
     * @return void
     */
    function __construct()
    {
        parent::__construct();

        $classes = new BattlenetArmory\Classes(strtolower($this->guild->region));

        asort($classes->datas);
        
        foreach($classes->datas as $key => $data)
        {
            $this->data[$key] = (object) $data;
            $this->data[$key]->talent_calculator_id = $this->talent_calculator_ids[$key];
        }
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
     * get_class()
     *
     * @access public
     * @param $id
     * @param $field
     * @return stdClass $datum
     */
    public function get_class($id, $field = NULL)
    {
        if($field)
        {
            return $this->data[$id]->$field;
        }
        return $this->data[$id];
    }    

    /**
     * getByName()
     *
     * @access public
     * @param string $name
     * @return array
     */
    public function get_by_name($name)
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
	public function get_icon($id, $size = 18)
    {
        $name = strtolower(preg_replace('/\ /', '', $this->names[$id]));

        if($size != 18 && $size != 56)
        {
            $size = 18;
        }

        if(!file_exists(FCPATH ."media/images/icons/$size/classicon_$name.jpg"))
        {
            $this->load->helper('save_image');

        	$image = imagecreatefromjpeg("http://media.blizzard.com/wow/icons/$size/classicon_$name.jpg");
        	save_jpeg($image, FCPATH ."media/images/icons/$size/classicon_$name.jpg", 100);
        }

    	return "/media/images/icons/$size/classicon_$name.jpg";
    }

    /**
     * get_all()
     *
     * Returns all the classes
     * 
     * @access public
     * @return array
     */
    public function get_all()
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