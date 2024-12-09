<?php

namespace App\Model\Table;

use App\Model\Entity\User;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
/**
 * @category  Class File
 * @Desc      Manage Solar Type Log
 * @author    Employee Code : -
 * @version   SG
 * @since     File available since SG
 */
class SolarTypeLogTable extends AppTable
{
	/**
	 *
	 * The status of $name is universe
	 *
	 * Potential value are Class Name
	 *
	 * @var String
	 *
	 */
	var $table = 'applyonline_solar_type_log';
	var $SOLAR_TYPE_NEW 		= 0;
	var $SOLAR_TYPE_ADDITION 	= 1;
	var $SOLAR_TYPE_REDUCTION 	= 2;
	var $arrSolarTypes 			= array(0=>"NEW","1"=>"ADDITION","2"=>"REDUCTION");
	public function initialize(array $config)
	{
		$this->table($this->table);
	}

	public function getSolarTypeText($SOLAR_TYPE=0)
	{
		return isset($this->arrSolarTypes[$SOLAR_TYPE])?$this->arrSolarTypes[$SOLAR_TYPE]:"NEW";
	}

	public function getSolarTypeFlag($EXISTING_PV_CAPACITY=0)
	{
		if (!empty($EXISTING_PV_CAPACITY)) {
			return $this->SOLAR_TYPE_ADDITION;
		} else {
			return $this->SOLAR_TYPE_NEW;
		}
	}

	public function findSolarTypeFlag($application_id=0)
	{
		if (!empty($application_id))
		{
			$RecordExists = $this->find('all', array('conditions'=>array('application_id' => $application_id)))->first();
			if (isset($RecordExists->id) && !empty($RecordExists->id)) {
				return $this->getSolarTypeText($RecordExists->solar_type);
			} else {
				return $this->getSolarTypeText(0);
			}
		}
	}

	public function SaveOrUpdateSolarType($application_id,$solar_type)
	{
		$RecordExists = $this->find('all', array('conditions'=>array('application_id' => $application_id)))->first();
		if (isset($RecordExists->id) && !empty($RecordExists->id))
		{
			$updateFields = array(	"solar_type"		=> $solar_type,
									"old_solar_type"	=> $RecordExists->solar_type,
									"modified"			=> $this->NOW());
			$this->updateAll($updateFields,["application_id"=>$application_id]);
		} else {
			$NewEntity 					= $this->newEntity();
			$NewEntity->application_id 	= $application_id;
			$NewEntity->solar_type 		= $solar_type;
			$NewEntity->old_solar_type 	= $solar_type;
			$NewEntity->created 		= $this->NOW();
			$NewEntity->modified 		= $this->NOW();
			$this->save($NewEntity);
		}
	}
}
?>