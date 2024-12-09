<?php
namespace App\Model\Table;

use App\Model\Table\Entity;
use App\Model\Entity\User;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Network\Email\Email;
use Cake\Utility\Security;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Datasource\ConnectionManager;

class SendRegistrationFailureTable extends AppTable
{
	var $table                  = 'send_registration_failure';
	
	public function initialize(array $config)
	{
		$this->table($this->table);         
	}
	public function fetchApiSendRegistration($application_id,$arrReuqest=array(),$member_id='') {
		$ApplyOnlines               = TableRegistry::get('ApplyOnlines');
		$applyOnlinesData           = $ApplyOnlines->viewApplication($application_id);

		$branch_master              = TableRegistry::get('BranchMasters');
		$branchDetails              = $branch_master->find('all',array('conditions'=>array('discom_id'=>$applyOnlinesData->area)))->first();
		$discom_id                  = $branchDetails->id;
		$thirpartyApi               = TableRegistry::get('ThirdpartyApiLog');
		$payment_status             = 0;
		$responseData               =  $thirpartyApi->sendConsumerDetails($applyOnlinesData->consumer_no,$applyOnlinesData->discom,$applyOnlinesData->project_id,$applyOnlinesData->id);
		
		return $responseData;
	} 
	public function SaveVendorSendRequest($application_id=0)
	{
		if (empty($application_id)) return false;
		$RegistrationFailureData = $this->find('all',array('conditions'=>array('application_id'=>$apiapplication_id)))->first();
		if(empty($RegistrationFailureData))
		{
			$ApplyOnlines 		= TableRegistry::get('ApplyOnlines');
			$applyOnlinesData 	= $ApplyOnlines->viewApplication($application_id);
			$NewEntity 					= $this->newEntity();
			$NewEntity->application_id 	= $application_id;
			$NewEntity->created 		= $this->NOW();
			$this->save($NewEntity);	
		}
		return true;
	}
}