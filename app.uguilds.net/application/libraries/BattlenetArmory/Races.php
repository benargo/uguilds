<?php
namespace BattlenetArmory;

class Races extends Battlenet {
	
	private $datas;

   	function __construct($region)
      {
   		$jsonConnect = new jsonConnect();
   		$data = $jsonConnect->getRaces($region);
   		$count = count($data['races']);
   		for ($i=0; $i < $count; $i++){
   			$this->datas[$data['races'][$i]['id']] = $data['races'][$i]; 
   		}
   		$data = null;
   	}

   	public function getRace($id,$field)
      {
   		return $this->datas[$id][$field];
   	}

      public function getIcon($id, $gender) 
      {
         if(!file_exists(FCPATH .'media/images/races/race_'. $id . '_'. $gender .'.jpg'))
         {
            $image = imagecreatefromjpeg('http://media.blizzard.com/wow/icons/18/race_'. $id .'_'. $gender .'.jpg');
            imagejpeg($image, FCPATH .'media/images/races/race_'. $id .'_'. $gender .'.jpg', 100);
         }

         return '/media/images/races/race_'. $id .'_'. $gender .'.jpg';
      }

      public function getAll($side = 'both')
      {
         switch($side)
         {
            case 0:
               $side = 'alliance';
               break;

            case 1:
               $side = 'horde';

            case 'both':
            default:
               unset($side);
               break;
         }

         
         $races = '';

         foreach($this->datas as $race)
         {
            if(isset($side) && $race['side'] == $side)
            {
               $races[$race['id']] = $race['name'];
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
