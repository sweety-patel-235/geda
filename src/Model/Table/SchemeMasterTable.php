<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;
class SchemeMasterTable extends AppTable
{
	var $table 	= 'scheme_master';
	public function initialize(array $config)
	{
		$this->table($this->table);       	
	}
	/**
	*
	* findActiveSchemeId
	*
	* Behaviour : public
	*
	* @defination : Method is used to find current active scheme id from database.
	*
	*/
	public function findActiveSchemeId()
	{
		$arrScheme 		= $this->find('all',array(
								'fields'	=> array('id'),
								'conditions'=> array('status'	=> 1)
							))->toArray();
		$arrSchemeIds 	= array();
		if(!empty($arrScheme)) {
			foreach($arrScheme as $schemeData) {
				$arrSchemeIds[]	= $schemeData->id;
			}
			//return $arrSchemeIds;
		}
		return $arrSchemeIds[0];
	}
	/**
	*
	* findSchemeDetails
	*
	* Behaviour : public
	*
	* @defination : Method is used to find current active scheme id from database.
	*
	*/
	public function findSchemeDetails($scheme_id='')
	{
		$arrScheme 		= $this->find('all',array(
								'conditions'=> array('id'	=> $scheme_id)
							))->first();
		if(!empty($arrScheme)) {
			return $arrScheme;
		}
		return 0;
	}
	/**
	*
	* findApplicationSchemeId
	*
	* Behaviour : public
	*
	* @defination : Method is used to find current active scheme id from database.
	*
	*/
	public function findApplicationSchemeId($application_id='')
	{
		$ApplyOnlinesOthers = TableRegistry::get('ApplyOnlinesOthers');
		$arrOthers			= $ApplyOnlinesOthers->find('all',array(
								'conditions'=> array('application_id'	=> $application_id)
							))->first();
		
		if(!empty($arrOthers) && isset($arrOthers->scheme_id)) {
			return $arrOthers->scheme_id;
		}
		return 0;
	}
	/**
	* findSchemeIdFromConnection
	* Behaviour : public
	* @defination : Method is used to find current active scheme id from database form connection Type.
	*/
	public function findSchemeIdFromConnection($connectionType='')
	{
		$schemeDetails		= $this->find('all',array(
								'conditions'=> array('scheme_code'	=> strtolower($connectionType),'status'=>1)
							))->first();
		if(!empty($schemeDetails) && isset($schemeDetails->id)) {
			return $schemeDetails->id;
		}
		return 0;
	}
}