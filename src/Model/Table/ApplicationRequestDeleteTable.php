<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
/**
 * Short description for file
 * This Model use for Proposal table. It extends AppModel Class
 * @category  Class File
 * @Desc      Manage Proposal information
 * @author    Pravin Sanghani
 * @version   RR
 * @since     File available since RR 1.0
 */
class ApplicationRequestDeleteTable extends AppTable {
	/**
	 * The status of $name is universe
	 * Potential value are Class Name
	 * @var String
	 */

	 public $Name 	= 'ApplicationRequestDelete';
	/**
	 * The status of $useTable is universe
	 * Potential value are Database Table name
	 * @var String
	 */

	public $useTable = 'application_request_delete';
	
	public function initialize(array $config)
	{
		$this->table($this->useTable);
	}
	/**
	*
	* findLatestApprovedRequest
	* Behaviour : public
	* Parameter : application_id
	* @defination : Method is used to return latest request status from applicaton-id
	*
	*/
	public function findLatestApprovedRequest($application_id)
	{
		$canDelete 				= 0;
		if(!empty($application_id))
		{
			$fetchApplication 	= $this->find('all',array('conditions'=> array('application_id'	=> $application_id),'order'=>array('id'=>'desc')))->first();
			if(!empty($fetchApplication)) {
				$canDelete 		= $fetchApplication->status;
			}
		}
		return $canDelete;
	}
	/**
	*
	* findLatestRequest
	* Behaviour : public
	* Parameter : application_id
	* @defination : Method is used to return latest request from applicaton-id
	*
	*/
	public function findLatestRequest($application_id)
	{
		if(!empty($application_id))
		{
			$fetchApplication 	= $this->find('all',array('conditions'=> array('application_id'	=> $application_id),'order'=>array('id'=>'desc')))->first();
			if(!empty($fetchApplication)) {
				return $fetchApplication;
			}
		}
		return 0;
	}
	/**
	 * TotalApplicationDeleteRequest
	 * Behaviour : public
	 * @param : $state, $main_branch_id
	 * @defination : Method is use to find total application request for delete 
	 */
	public function TotalApplicationDeleteRequest($state = '',$main_branch_id='',$capacity=0,$status=0) 
	{
		$DeleteRequest 	= $this->find();
		$DeleteRequest->hydrate(false);
		
		$ApplyOnlines   = TableRegistry::get('ApplyOnlines');
		if ($main_branch_id['member_type'] == $ApplyOnlines->DISCOM)
		{
			$arrConditions['apply_onlines.'.$main_branch_id['field']]	= $main_branch_id['id'];
		} 
		if($status == 0) {
			$arrConditions[0]	= ['OR'=>['ApplicationRequestDelete.status IS NULL','ApplicationRequestDelete.status'=>0]];
		} else {
			$arrConditions['ApplicationRequestDelete.status']			= $status;
		}
		if($capacity == 1) {
			$arrFields['TotalCount'] = $DeleteRequest->func()->sum('apply_onlines.pv_capacity');
		} else {
			$arrFields['TotalCount'] = $DeleteRequest->func()->count(0);
		}
		
	   	$DeleteRequest->select($arrFields)
	   			->join([
						[   'table'=>'apply_onlines',
							'type'=>'inner',
							'conditions'=>'apply_onlines.id = ApplicationRequestDelete.application_id'
						]
					]
				)
	   			->where($arrConditions);
		$resultArray = $DeleteRequest->toList();
		if($capacity == 1) {
			$TotalCount  = isset($resultArray[0]['TotalCount'])?_FormatGroupNumberV2($resultArray[0]['TotalCount']):0;
		} else {
			$TotalCount  = isset($resultArray[0]['TotalCount'])?$resultArray[0]['TotalCount']:0;
		}
		return $TotalCount;
	}
}