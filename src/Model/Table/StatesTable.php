<?php
/************************************************************
* File Name : OffersTable.php 								*
* purpose	: Offer Model Table file 						*
* @package  : 												*
* @author 	: CP Soni										*
* @since 	: 23/04/2016									*
************************************************************/

namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

use App\Model\Table\Entity;

class StatesTable extends AppTable
{
	var $table = 'states';
	var $STATUS_ACTIVE      = 'A';
    var $STATUS_INACTIVE    = 'I';

	public function initialize(array $config)
    {
        $this->table($this->table);       	
    }

    public function getSteates($region_id)
    {
        $arrState       = array();
        $arrConditions  = array("States.region_id"=>$region_id);
        $arrState       = $this->find('all',array("conditions"=>$arrConditions))->toArray();
        return $arrState;
    }

    public function getStateByName($state="")
    {
        $arrState = array(); 
        if (!empty($state)) {
            $arrConditions= array("LOWER(States.statename)"=>strtolower($state));
            $arrState = $this->find('all',array("conditions"=>$arrConditions))->first();
        }
        return $arrState;
    }
}
?>