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
        
        $ci =& get_instance();
        $races = new \BattlenetArmory\Races(strtolower($ci->guild->region));

        foreach($races->datas as $key => $data)
        {
            $this->data[$key] = (object) $data;
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
     * getRace()
     *
     * @access public
     * @param $id
     * @param $field
     * @return stdClass $datum
     */
    public function getRace($id, $field = NULL)
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
    public function getByName($name)
    {
        $ci =& get_instance();
        $name = ucwords(str_replace('-', ' ', $name));

        foreach($this->data as $race)
        {
            if($race->name == $name && $race->side == $ci->guild->getFaction())
            {
                return $race;
            }
        }
    }

	/**
	 * getIcon()
	 * 
	 * @access public
	 * @param int $id
	 * @param bool $gender
	 * @return string
	 */
	public function get_icon($id, $gender = 0, $size = 18) 
    {
        $ci =& get_instance();
        $ci->load->helper('battlenet');

        return get_icon('race_'. $id .'_'. $gender, $size);
    }

    /**
     * getALL()
     *
     * @access public
     * @param mixed (string/int) $side
     * @return array
     */
    public function getAll($input = 'both')
    {
        switch($input)
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

               break;
        }
         
        $races = '';

        foreach($this->data as $race)
        {
            if(isset($side) && $race->side != $side || $race->side == 'neutral')
            {
               continue;
            }

            $races[$race->id] = $race->name;
         }

        // Sort the races by name
        asort($races);

        // Generate the return
        $return = array();
        foreach($races as $id => $name)
        {
            $return[$id] = $this->data[$id];
        }
        return $return;
      }
}