<?php
namespace App\Model\Table;

use App\Model\Table\Entity;
use App\Model\Entity\User;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\View\View;
use Dompdf\Dompdf;
use Cake\Utility\Security;
use Cake\Core\Configure;
use Cake\Network\Email\Email;
use Cake\Event\Event;
use Cake\Datasource\ConnectionManager;


/**
 * Short description for file
 * This Model use for developer Wororder . It extends Table Class
 * @category  Class File
 * @Desc      Manage installer information
 * @author    
 * @version   Solar 1.0
 * @since     File available since Solar 1.0
 */

class DeveloperAssignWorkorderTable extends AppTable
{
	var $table 		= 'developer_assign_workorder';
	var $data 		= array();
	var $dataRecord = array();

	public function initialize(array $config)
	{
		$this->table($this->table);
	}

	public function getWorkOrderRemainingCapacity($assign_work_order_id,$set_capacity='',$get_total_capacity=0,$application_id=0) {
		$Applications 			= TableRegistry::get('Applications');
		$mappedApplications		= $Applications->find('all',array(
							'fields'	=> array('map_workorder_id','Applications.pv_capacity_ac','Applications.total_wind_hybrid_capacity','Applications.total_capacity','application_type'),
							'conditions'=> array('map_workorder_id'=>$assign_work_order_id,'Applications.id !='=>$application_id)))->toArray();

		$totalCapacity 			= 0;
		if(!empty($mappedApplications)) {
			foreach($mappedApplications as $mapApplicationDetails) {
				if($mapApplicationDetails->application_type == 2) {
					$totalCapacity 	= $totalCapacity + $mapApplicationDetails->pv_capacity_ac;
				} elseif($mapApplicationDetails->application_type == 3) {
					$totalCapacity 	= $totalCapacity + $mapApplicationDetails->total_capacity;
				} else {
					$totalCapacity 	= $totalCapacity + $mapApplicationDetails->total_wind_hybrid_capacity;
				}
			}
		}
		$usedCapacity 		= $totalCapacity;
		if($set_capacity != '') {
			$totalCapacity 	= $totalCapacity + $set_capacity;
		} 
		if($get_total_capacity == 1) {
			return $totalCapacity;
		}
		$assignedWorkorderDetails 	= $this->find('all',array('conditions'=>array('id'=>$assign_work_order_id)))->first();
		if($assignedWorkorderDetails->capacity < $totalCapacity) {
			return 'Capacity should not greater than Assigned capacity '.$assignedWorkorderDetails->capacity.' MW - Used capacity '.$usedCapacity.' MW';
		}
		return true;
	}
	public function getWorkOrderMappedApplication($assign_work_order_id) {
		$Applications 			= TableRegistry::get('Applications');
		$mappedApplications		= $Applications->find('all',array(
							'fields'	=> array('map_workorder_id','Applications.pv_capacity_ac','Applications.total_wind_hybrid_capacity','Applications.total_capacity','application_no','Applications.id'),
							'conditions'=> array('map_workorder_id'=>$assign_work_order_id,'Applications.application_status > '=>0)))->toArray();

		$arrApplicationsMapped 	= array();
		if(!empty($mappedApplications)) {
			foreach($mappedApplications as $mapApplicationDetails) {
				$arrApplicationsMapped[] 	= '<a href="'.URL_HTTP.'view-applications/'.encode($mapApplicationDetails->id).'" target="_blank">'.$mapApplicationDetails->application_no.'</a>';
			}
		}
		
		return $arrApplicationsMapped;
	}
}