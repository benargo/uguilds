<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Races extends CI_Model {

    protected $data;

    /**
     * __construct()
     * 
     * @access public
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        
        $races = new \BattlenetArmory\Races(strtolower($this->guild->region));

        asort($races->datas);

        foreach($races->datas as $key => $data)
        {
            $this->data[$key] = (object) $data;
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
     * get_race()
     *
     * Get a specific race
     *
     * @access public
     * @param $id
     * @param $field
     * @return stdClass $datum
     */
    public function get_race($id, $field = NULL)
    {
        if($field)
        {
            return $this->data[$id]->$field;
        }
        return $this->data[$id];
    }

    /**
     * get_by_name()
     *
     * Get a specific race by name
     *
     * @access public
     * @param string $name
     * @return array
     */
    public function get_by_name($name)
    {
        $name = ucwords(str_replace('-', ' ', $name));

        foreach($this->data as $race)
        {
            if($race->name == $name && $race->side == $this->guild->get_faction())
            {
                return $race;
            }
        }
    }

	/**
	 * get_icon()
     *
     * Get a race's icon
	 * 
	 * @access public
	 * @param int $id
	 * @param bool $gender
	 * @return string
	 */
	public function get_icon($id, $gender = 0) 
    {
        if(!file_exists(FCPATH ."media/images/races/race_". $id . '_'. $gender .'.jpg'))
        {
            $image = imagecreatefromjpeg('http://media.blizzard.com/wow/icons/18/race_'. $id .'_'. $gender .'.jpg');
            imagejpeg($image, FCPATH .'media/images/races/race_'. $id .'_'. $gender .'.jpg', 100);
        }

        return '/media/images/races/race_'. $id .'_'. $gender .'.jpg';
    }

    /**
     * get_all()
     *
     * Get all the races
     *
     * @access public
     * @param mixed (string/int) $side
     * @return array
     */
    public function get_all($side = 'both')
    {
        switch($side)
        {
            case 0:
            case "alliance":
                $side = 'alliance';
                break;

            case 1:
            case "horde":
                $side = 'horde';
                break;

            case "both":
            default:
                unset($side);
                break;
        }
         
        $races = array();

        foreach($this->data as $race)
        {
            if(isset($side) && $race->side != $side || $race->side == 'neutral')
            {
               continue;
            }

            $races[$race->id] = $race->name;
         }

        return $races;
    }
}