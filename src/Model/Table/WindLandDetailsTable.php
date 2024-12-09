<?php
namespace App\Model\Table;

use App\Model\Table\Entity;
use App\Model\Entity\User;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

use Cake\Utility\Security;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Datasource\ConnectionManager;

/**
 * @category  Class File
 * @author    Employee Code : -
 * @version   GED 1.0
 * @since     File available since GED
 */
class WindLandDetailsTable extends AppTable
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
	var $table = 'wind_land_details';
	
	public function initialize(array $config)
	{
		$this->table($this->table);
	}
	public function save_wind_land_details($application_id,$arr_land_detail,$member_id)
	{
		
		$save_data           							= TableRegistry::get('WindLandDetails'); 
		$detailExists 	= $save_data->find('all', array('conditions' => array('app_geo_loc_id' => $arr_land_detail['app_geo_loc_id'])))->first();

		
		if (isset($detailExists) && !empty($detailExists)) {
			$save_data_entity    							= $save_data->patchEntity($detailExists, $arr_land_detail);
		}else{
			$save_data_entity    							= $save_data->newEntity(); 
		}
		if(isset($arr_land_detail['deed_doc']) && !empty($arr_land_detail['deed_doc'])){
			$save_data_entity->deed_doc                     = isset($arr_land_detail['deed_doc']) && !empty($arr_land_detail['deed_doc']) ?$arr_land_detail['deed_doc']:null;
			$save_data_entity->couch_id                     = isset($arr_land_detail['couch_id']) && !empty($arr_land_detail['couch_id'])?$arr_land_detail['couch_id']:null;
		}
		
		$save_data_entity->app_dev_per_id   		 	= $application_id;
		$save_data_entity->app_geo_loc_id				= isset($arr_land_detail['app_geo_loc_id']) ? $arr_land_detail['app_geo_loc_id'] : 0;
		$save_data_entity->land_category   			    = $arr_land_detail['land_category'];
		$save_data_entity->land_plot_servey_no   		= $arr_land_detail['land_plot_servey_no'];
		$save_data_entity->land_village   				= $arr_land_detail['land_village'];
		$save_data_entity->land_taluka   		        = $arr_land_detail['land_taluka'];
		$save_data_entity->land_state   	            = $arr_land_detail['land_state'];
		$save_data_entity->land_district   			    = $arr_land_detail['land_district'];
        $save_data_entity->land_latitude   	            = $arr_land_detail['land_latitude'];
		$save_data_entity->land_longitude   			= $arr_land_detail['land_longitude'];
        $save_data_entity->area_of_land                 = $arr_land_detail['area_of_land'];
        $save_data_entity->deed_of_land                 = $arr_land_detail['deed_of_land'];
        $save_data_entity->created_by   		 		= $member_id;
		$save_data_entity->created_date           	    = $this->NOW();
		
		$save_data->save($save_data_entity);  

		
		
	}

	public function save_open_access_land_details($application_id,$arr_land_detail,$member_id)
	{
		 
		$save_data           							= TableRegistry::get('WindLandDetails'); 
		
		$exist 	=	$save_data->find('all', array('conditions' => array('id' => $arr_land_detail['id_inv_land'])))->first();

		if (isset($exist) && !empty($exist)) {
			
			$save_data_entity 	= $save_data->patchEntity($exist, $arr_land_detail);
		}else{
			$save_data_entity    							= $save_data->newEntity(); 
		}
		if(isset($arr_land_detail['deed_doc']) && !empty($arr_land_detail['deed_doc'])){
			$save_data_entity->deed_doc                     = isset($arr_land_detail['deed_doc']) && !empty($arr_land_detail['deed_doc']) ?$arr_land_detail['deed_doc']:null;
			$save_data_entity->couch_id                     = isset($arr_land_detail['couch_id']) && !empty($arr_land_detail['couch_id'])?$arr_land_detail['couch_id']:null;

		}
		$save_data_entity->app_dev_per_id   		 	= $application_id;
		$save_data_entity->app_geo_loc_id				= 0;
		$save_data_entity->land_category   			    = $arr_land_detail['land_category'];
		$save_data_entity->land_plot_servey_no   		= $arr_land_detail['land_plot_servey_no'];
		$save_data_entity->land_village   				= $arr_land_detail['land_village'];
		$save_data_entity->land_taluka   		        = $arr_land_detail['land_taluka'];
		$save_data_entity->land_state   	            = $arr_land_detail['land_state'];
		$save_data_entity->land_district   			    = $arr_land_detail['land_district'];
        $save_data_entity->land_latitude   	            = $arr_land_detail['land_latitude'];
		$save_data_entity->land_longitude   			= $arr_land_detail['land_longitude'];
        $save_data_entity->area_of_land                 = $arr_land_detail['area_of_land'];
        $save_data_entity->deed_of_land                 = $arr_land_detail['deed_of_land'];
		$save_data_entity->created_by   		 		= $member_id;
		$save_data_entity->created_date           	    = $this->NOW();
		
		$save_data->save($save_data_entity); 		
		
	}
	public function fetchdata($application_id, $geo = 1)
	{
		$TalukaMaster = TableRegistry::get('TalukaMaster');
		$fetch_land_details = [];
	
		
			if ($geo == 1) {
				$fetch_land_details = $this->find('all', [
					'conditions' => ['app_dev_per_id' => $application_id, 'app_geo_loc_id !=' => 0]
				])->toArray();
				return $fetch_land_details;
			} else {
				$fetch_land_details = $this->find('all', [
					'conditions' => ['app_dev_per_id' => $application_id, 'app_geo_loc_id' => 0]
				])->toArray();
	
				foreach ($fetch_land_details as $k => $v) {
					$taluka = $TalukaMaster->find("list", [
						'keyField' => 'id',
						'valueField' => 'name',
						'conditions' => ['district_id' => $v['land_district']]
					])->toArray();
	
					$fetch_land_details[$k]['taluka'] = !empty($taluka) ? $taluka : [];
				}
				
				return $fetch_land_details;
			}
		
		
		
	}

	
}