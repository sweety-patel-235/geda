<?php
namespace App\Model\Table;

use App\Model\Table\Entity;
use App\Model\Entity\User;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;
//use App\Model\Table\Security;

use Cake\Utility\Security;
use Cake\Core\Configure;
use Cake\Event\Event;
//use Cake\Event\Event;

/**
 * Short description for file
 * This Model use for Ticket table. It extends Table Class
 * @category  Class File
 * @Desc      Manage Ticket information
 * @author    Pravin Sanghani
 * @version   Solar 1.0
 * @since     File available since Solar 1.0
 */

class DiscomMasterHtTable extends AppTable
{
	
	var $table 			= 'discom_master_ht';
	var $dataPass 		= array();
	
	public function initialize(array $config)
	{
		$this->table($this->table);
		//$this->loadHelper('MyUtils');
	}
	/**
	* findDiscomHt
	* Behaviour : public
	* Parameter : discom API data
	* @defination : Method is used find ht subdivision details.
	*/
	public function findDiscomHt($passData,$discom_id)
	{
		$ThirdpartyApiLog 	= TableRegistry::get('ThirdpartyApiLog');
		$conditionsArr 		= array('division_sort_code'=> $passData['division_api'],
									'ht_code'			=> $passData['sub_division_api'],
									'circle_sort_code'	=> $passData['circle_api'],
									'discom_code'		=> $ThirdpartyApiLog->arr_discom_map[$discom_id]);
		
		$HTSubdivision 		= $this->find('all',array('conditions'=>$conditionsArr,'order'=>array('id'=>'desc')))->first();
		return $HTSubdivision;
	}
	/**
	* findTotalExsitingCapacity
	* Behaviour : public
	* Parameter : consumer_no, $application_no
	* @defination : Method is used find total existing capacity for passed consumer number if total applications of passed consumer number and meter installation done for all records .
	*/
	public function findTotalExsitingCapacity($consumer_no,$application_no,$discom_id)
	{
		
		$ApplyOnlines 			= TableRegistry::get('ApplyOnlines');
		$ApplyOnlinesOthers 	= TableRegistry::get('ApplyOnlinesOthers');
		$ApplyOnlineApprovals 	= TableRegistry::get('ApplyOnlineApprovals');
		$HtConsumerOffline 		= TableRegistry::get('HtConsumerOffline');
		$conditionsArr 			= array('consumer_no'=>$consumer_no,'ApplyOnlines.id !='=>$application_no);
		if(!in_array($discom_id, array($ApplyOnlines->torent_ahmedabad,$ApplyOnlines->torent_surat,$ApplyOnlines->torent_dahej)))
		{
			$conditionsArr['ApplyOnlines.discom not in'] = array($ApplyOnlines->torent_ahmedabad,$ApplyOnlines->torent_surat,$ApplyOnlines->torent_dahej);
		} else {
			$conditionsArr['ApplyOnlines.discom in'] = array($ApplyOnlines->torent_ahmedabad,$ApplyOnlines->torent_surat,$ApplyOnlines->torent_dahej);
		}
		$meterInsCounter 		= 0;
		$existingCapacity 		= 0;
		$totalApplications 		= $ApplyOnlines->find('all',array(
														'join'		=> ['apply_onlines_others'=>['table'=>'apply_onlines_others','type'=>'inner','conditions'=>'ApplyOnlines.id=apply_onlines_others.application_id']],
														'fields' 	=> array('ApplyOnlines.id','pv_capacity','apply_onlines_others.pv_dc_capacity','ApplyOnlines.created'),
														'conditions'=>$conditionsArr))->toArray();

		if(!empty($totalApplications)) {
			foreach($totalApplications as $application) {
				$meterInstallationDone 	= $ApplyOnlineApprovals->find('all',array(
											'conditions'=>array('application_id'=> $application->id,
																'stage' 		=> $ApplyOnlineApprovals->METER_INSTALLATION)))->first();
				if(!empty($meterInstallationDone)) {
					$meterInsCounter++;
					if(isset($application->created) && strtotime($application->created) >= strtotime(APPLICATION_NEW_SCHEME_DATE)) {
						$existingCapacity 	= $existingCapacity + $application->apply_onlines_others['pv_dc_capacity'];
					} else {
						$existingCapacity 	= $existingCapacity + $application->pv_capacity;
					}
				}
			}
			pr($existingCapacity);
			if(count($totalApplications) == $meterInsCounter) {
				$offLineData 			= $HtConsumerOffline->find('all',array('conditions'=>array('consumer_no'=>$consumer_no,'discom'=>$discom_id)))->first();
				if(!empty($offLineData))
				{
					$existingCapacity 	= $existingCapacity + $offLineData->existing_capacity;
				}
				return $existingCapacity;
			}
		} else {
			$offLineData 			= $HtConsumerOffline->find('all',array('conditions'=>array('consumer_no'=>$consumer_no,'discom'=>$discom_id)))->first();
			if(!empty($offLineData))
			{
				$existingCapacity 	= $existingCapacity + $offLineData->existing_capacity;
				return $existingCapacity;
			}
		}
		return 0;
	}
}