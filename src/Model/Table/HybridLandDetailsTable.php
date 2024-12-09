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
class HybridLandDetailsTable extends AppTable
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
	var $table = 'hybrid_land_details';
	
	public function initialize(array $config)
	{
		$this->table($this->table);
	}
	public function save_hybrid_land_details($application_id,$arr_land_detail,$member_id)
	{
		
		$save_data           							= TableRegistry::get('HybridLandDetails'); 
		$save_data_entity    							= $save_data->newEntity(); 
		$save_data_entity->app_dev_per_id   		 	= $application_id;
		$save_data_entity->app_geo_loc_id				= $arr_land_detail['app_geo_loc_id'];
		$save_data_entity->land_category   			    = $arr_land_detail['land_category'];
		$save_data_entity->land_plot_servey_no   		= $arr_land_detail['land_plot_servey_no'];
		$save_data_entity->land_taluka   		        = $arr_land_detail['land_taluka'];
		$save_data_entity->land_state   	            = $arr_land_detail['land_state'];
		$save_data_entity->land_district   			    = $arr_land_detail['land_district'];
        $save_data_entity->land_latitude   	            = $arr_land_detail['land_latitude'];
		$save_data_entity->land_longitude   			= $arr_land_detail['land_longitude'];
        $save_data_entity->area_of_land                 = $arr_land_detail['area_of_land'];
        $save_data_entity->deed_of_land                 = $arr_land_detail['deed_of_land'];
        $save_data_entity->deed_doc                     = isset($arr_land_detail['deed_doc'])?$arr_land_detail['deed_doc']:null;
		$save_data_entity->created_by   		 		= $member_id;
		$save_data_entity->created_date           	    = $this->NOW();
		
		$save_data->save($save_data_entity);        
		
	}
    public function fetchdata($application_id)
	{
		$fetch_land_details = $this->find('all',array('conditions'=>array('app_dev_per_id'=>$application_id)))->toArray();		
		return $fetch_land_details;
	}

	
}