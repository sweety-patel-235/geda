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
class ApplicationOpenAccessAdditionalDataTable extends AppTable
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
	var $table = 'application_open_access_additional_data';
	
	public function initialize(array $config)
	{
		$this->table($this->table);
	}
	
	public function save_module_open_access($application_id,$arr_modules,$member_id)
	{
		
		$save_data           							= TableRegistry::get('ApplicationOpenAccessAdditionalData'); 
		$save_data_entity    							= $save_data->newEntity(); 
		$save_data_entity->app_dev_per_id   		 	= $application_id;
		$save_data_entity->capacity_type   			    = 1;
		$save_data_entity->nos_mod_inv   				= $arr_modules['nos_mod'];
		$save_data_entity->mod_inv_capacity   		    = $arr_modules['mod_capacity'];
		$save_data_entity->mod_inv_total_capacity   	= $arr_modules['mod_total_capacity'];
		$save_data_entity->mod_inv_make   			    = $arr_modules['mod_make'];
		$save_data_entity->created_by   		 		= $member_id;
		$save_data_entity->created_date           	    = $this->NOW();
		$save_data_entity->type_of_spv_technologies     = $arr_modules['type_of_spv'];
		$save_data_entity->type_of_solar_panel   		= $arr_modules['type_of_solar'];
		
		$save_data->save($save_data_entity);
		
	}

	public function save_inverter_open_access($application_id,$arr_modules,$member_id)
	{
		$save_data           							= TableRegistry::get('ApplicationOpenAccessAdditionalData'); 
		$save_data_entity    							= $save_data->newEntity(); 
		$save_data_entity->app_dev_per_id   		 	= $application_id;
		$save_data_entity->capacity_type   			    = 2;
		$save_data_entity->nos_mod_inv   				= $arr_modules['nos_inv'];
		$save_data_entity->mod_inv_capacity   		    = $arr_modules['inv_capacity'];
		$save_data_entity->mod_inv_total_capacity   	= $arr_modules['inv_total_capacity'];
		$save_data_entity->mod_inv_make   			    = $arr_modules['inv_make'];
		$save_data_entity->created_by   		 		= $member_id;
		$save_data_entity->created_date           	    = $this->NOW();
		$save_data_entity->type_of_inverter_used		= $arr_modules['type_of_inverter_used'];
		$save_data->save($save_data_entity);
	}
	public function getcapacitysum($application_id,$capacity_type){
		$ApplicationAdditionalData = $this->find();
		$ApplicationAdditionalData->hydrate(false);
		$totalCapacity = $ApplicationAdditionalData->select(['capacity' => $ApplicationAdditionalData->func()->sum('ApplicationOpenAccessAdditionalData.mod_inv_total_capacity')])->where(['app_dev_per_id'=>$application_id,'capacity_type' =>$capacity_type])->first();
		return $totalCapacity;
	}

	public function getOpenAccessDataSum($application_id,$capacity_type){
		
		$ApplicationOpenAccessAdditionalData = $this->find();
		$ApplicationOpenAccessAdditionalData->hydrate(false);
		$totalwinddatasum = $ApplicationOpenAccessAdditionalData->select(['nos_mod_inv' => $ApplicationOpenAccessAdditionalData->func()->sum('ApplicationOpenAccessAdditionalData.nos_mod_inv'),'mod_inv_capacity' => $ApplicationOpenAccessAdditionalData->func()->sum('ApplicationOpenAccessAdditionalData.mod_inv_capacity'),'mod_inv_total_capacity' => $ApplicationOpenAccessAdditionalData->func()->sum('ApplicationOpenAccessAdditionalData.mod_inv_total_capacity')])->where(['app_dev_per_id'=>$application_id,'capacity_type' =>$capacity_type])->first();
		//echo"<pre>"; print_r($totalwinddatasum); die();
		return $totalwinddatasum;
	}
	
	public function fetchdata($application_id,$capacity_type)
	{
		$fetchhybrid_module           = TableRegistry::get('ApplicationOpenAccessAdditionalData'); 
		$fetch_moduleDetails = $this->find('all',array(	'fields'	=> array('capacity_type','nos_mod_inv','mod_inv_capacity','mod_inv_total_capacity','mod_inv_make','app_dev_per_id','id','type_of_spv_technologies','type_of_solar_panel','type_of_inverter_used'),'conditions'=>array('app_dev_per_id'=>$application_id,'capacity_type'=>$capacity_type)))->toArray();
		
		return $fetch_moduleDetails;
	}
}