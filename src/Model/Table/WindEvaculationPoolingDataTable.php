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
class WindEvaculationPoolingDataTable extends AppTable
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
	var $table = 'wind_evaculation_pooling_data';
	
	public function initialize(array $config)
	{
		$this->table($this->table);
	}
	public function save_pooling($application_id,$arr_data,$member_id)
	{
		
		$save_data           							= TableRegistry::get('WindEvaculationPoolingData');		
		$save_data_entity    							= $save_data->newEntity(); 
		$save_data_entity->app_dev_per_id   		 	= $application_id;
		$save_data_entity->name_of_pooling_sub   		= $arr_data['name_of_pooling_sub'];
		$save_data_entity->distict_of_pooling_sub   	= $arr_data['distict_of_pooling_sub'];
		$save_data_entity->taluka_of_pooling_sub   		= $arr_data['taluka_of_pooling_sub'];
		$save_data_entity->village_of_pooling_sub   	= $arr_data['village_of_pooling_sub'];
		// $save_data_entity->cap_of_pooling_sub   		= $arr_data['cap_of_pooling_sub'];
		$save_data_entity->vol_of_pooling_sub   		= $arr_data['vol_of_pooling_sub'];
		$save_data_entity->sub_mw_of_pooling_sub   		= $arr_data['sub_mw_of_pooling_sub'];
		$save_data_entity->sub_mva_of_pooling_sub   	= $arr_data['sub_mva_of_pooling_sub'];
		$save_data_entity->conn_mw_of_pooling_sub   	= $arr_data['conn_mw_of_pooling_sub'];
		$save_data_entity->conn_mva_of_pooling_sub   	= $arr_data['conn_mva_of_pooling_sub'];
		$save_data_entity->created_by   		 		= $member_id;
		$save_data_entity->created_date           	    = $this->NOW();

		
		$save_data->save($save_data_entity);
	
	}
	public function fetchdata($application_id)
	{
		$fetch_pooling_details = $this->find('all',array('conditions'=>array('app_dev_per_id'=>$application_id)))->toArray();		
		return $fetch_pooling_details;
	}

	
}