<?php
namespace uGuilds;

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Races extends \BattlenetArmory\Races {

	/**
	 * getIcon()
	 * 
	 * @access public
	 * @param int $id
	 * @param bool $gender
	 * @return string
	 */
	public function getIcon($id, $gender) 
      {
         if(!file_exists(FCPATH .'media/images/races/race_'. $id . '_'. $gender .'.jpg'))
         {
            $image = imagecreatefromjpeg('http://media.blizzard.com/wow/icons/18/race_'. $id .'_'. $gender .'.jpg');
            imagejpeg($image, FCPATH .'media/images/races/race_'. $id .'_'. $gender .'.jpg', 100);
         }

         return '/media/images/races/race_'. $id .'_'. $gender .'.jpg';
      }

    /**
     * getALL()
     *
     * @access public
     * @param mixed (string/int) $side
     * @return array
     */
    public function getAll($side = 'both')
    {
        switch($side)
        {
            case 0:
               $side = 'alliance';
               break;

            case 1:
               $side = 'horde';
               break;

            case 'both':
            default:
               unset($side);
               break;
        }

         
        $races = '';

        foreach($this->datas as $race)
        {
            if(isset($side) && $race['side'] != $side || $race['side'] == 'neutral')
            {
               continue;
            }

            $races[$race['id']] = $race['name'];
         }

        // Sort the races by name
        asort($races);

        // Generate the return
        $return = array();
        foreach($races as $id => $name)
        {
            $return[] = $this->datas[$id];
        }
        return $return;
      }
}