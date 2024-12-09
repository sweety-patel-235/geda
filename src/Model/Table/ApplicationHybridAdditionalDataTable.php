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
class ApplicationHybridAdditionalDataTable extends AppTable
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
	var $table = 'application_hybrid_additional_data';
	
	public function initialize(array $config)
	{
		$this->table($this->table);
	}
	public function save_wind($application_id,$arr_modules,$member_id)
	{
		//echo"<pre>"; print_r($arr_modules); die();
		$savehybrid_data           							= TableRegistry::get('ApplicationHybridAdditionalData'); 
		$savehybrid_data_entity    							= $savehybrid_data->newEntity(); 
		$savehybrid_data_entity->application_id   		 	= $application_id;
		$savehybrid_data_entity->capacity_type   			= 3;
		$savehybrid_data_entity->nos_mod_inv   				= $arr_modules['wtg_no'];
		$savehybrid_data_entity->mod_inv_capacity   		= $arr_modules['capacity_wtg'];
		$savehybrid_data_entity->mod_inv_total_capacity   	= $arr_modules['total_capacity'];
		$savehybrid_data_entity->mod_inv_make   			= $arr_modules['make'];
		$savehybrid_data_entity->created_by   		 		= $member_id;
		$savehybrid_data_entity->created_date           	= $this->NOW();
		$savehybrid_data->save($savehybrid_data_entity);
	}
	public function save_module_hybrid($application_id,$arr_modules,$member_id)
	{

		$savehybrid_data           							= TableRegistry::get('ApplicationHybridAdditionalData'); 
		$savehybrid_data_entity    							= $savehybrid_data->newEntity(); 
		$savehybrid_data_entity->application_id   		 	= $application_id;
		$savehybrid_data_entity->capacity_type   			= 1;
		$savehybrid_data_entity->nos_mod_inv   				= $arr_modules['nos_mod'];
		$savehybrid_data_entity->mod_inv_capacity   		= $arr_modules['mod_capacity'];
		$savehybrid_data_entity->mod_inv_total_capacity   	= $arr_modules['mod_total_capacity'];
		$savehybrid_data_entity->mod_inv_make   			= $arr_modules['mod_make'];
		$savehybrid_data_entity->mod_type_of_spv   			= $arr_modules['mod_type_of_spv'];
		$savehybrid_data_entity->mod_type_of_solar_panel   	= $arr_modules['mod_type_of_solar_panel'];
		$savehybrid_data_entity->created_by   		 		= $member_id;
		$savehybrid_data_entity->created_date           	= $this->NOW();

		$savehybrid_data->save($savehybrid_data_entity);
	}

	public function save_inverter_hybrid($application_id,$arr_modules,$member_id)
	{
		$savehybrid_data           							= TableRegistry::get('ApplicationHybridAdditionalData'); 
		$savehybrid_data_entity    							= $savehybrid_data->newEntity(); 
		$savehybrid_data_entity->application_id   		 	= $application_id;
		$savehybrid_data_entity->capacity_type   			= 2;
		$savehybrid_data_entity->nos_mod_inv   				= $arr_modules['nos_inv'];
		$savehybrid_data_entity->mod_inv_capacity   		= $arr_modules['inv_capacity'];
		$savehybrid_data_entity->mod_inv_total_capacity   	= $arr_modules['inv_total_capacity'];
		$savehybrid_data_entity->mod_inv_make   			= $arr_modules['inv_make'];
		$savehybrid_data_entity->inv_used   				= $arr_modules['inv_used'];
		$savehybrid_data_entity->created_by   		 		= $member_id;
		$savehybrid_data_entity->created_date           	= $this->NOW();
		$savehybrid_data->save($savehybrid_data_entity);
	}
	public function getcapacitysum($application_id,$capacity_type){
		$ApplicationHybridAdditionalData = $this->find();
		$ApplicationHybridAdditionalData->hydrate(false);
		$totalCapacity = $ApplicationHybridAdditionalData->select(['capacity' => $ApplicationHybridAdditionalData->func()->sum('ApplicationHybridAdditionalData.mod_inv_total_capacity')])->where(['application_id'=>$application_id,'capacity_type' =>$capacity_type])->first();
		return $totalCapacity;
	}
	public function getwinddatasum($application_id,$capacity_type){

		$ApplicationHybridAdditionalData = $this->find();
		$ApplicationHybridAdditionalData->hydrate(false);
		$totalwinddatasum = $ApplicationHybridAdditionalData->select(['nos_mod_inv' => $ApplicationHybridAdditionalData->func()->sum('ApplicationHybridAdditionalData.nos_mod_inv'),'mod_inv_capacity' => $ApplicationHybridAdditionalData->func()->sum('ApplicationHybridAdditionalData.mod_inv_capacity'),'mod_inv_total_capacity' => $ApplicationHybridAdditionalData->func()->sum('ApplicationHybridAdditionalData.mod_inv_total_capacity')])->where(['application_id'=>$application_id,'capacity_type' =>$capacity_type])->first();
		//echo"<pre>"; print_r($totalwinddatasum); die();
		return $totalwinddatasum;
	}
	public function fetchdata($application_id,$capacity_type)
	{
		$fetchhybrid_module           = TableRegistry::get('ApplicationHybridAdditionalData'); 
		$fetchhybrid_moduleDetails = $this->find('all',array(	'fields'	=> array('capacity_type','nos_mod_inv','mod_inv_capacity','mod_inv_total_capacity','mod_inv_make','mod_type_of_spv','mod_type_of_solar_panel','inv_used','application_id','id'),'conditions'=>array('application_id'=>$application_id,'capacity_type'=>$capacity_type)))->toArray();
		
		return $fetchhybrid_moduleDetails;
	}
	public function fetchdatafordownloadapplication($application_id,$capacity_type)
	{
		$fetchhybrid_module           = TableRegistry::get('ApplicationHybridAdditionalData'); 
		$fetchhybrid_moduleDetails = $this->find('all',
                                            [ 'fields'=>['capacity_type','nos_mod_inv','mod_inv_capacity','mod_inv_total_capacity','mod_inv_make','application_id','id','manufacturer_master.name','mod_type_of_spv','mod_type_of_solar_panel','inv_used'],
                                                'join'=>[['table'=>'manufacturer_master','type'=>'left','conditions'=>'ApplicationHybridAdditionalData.mod_inv_make = manufacturer_master.short_code']],
                                                'conditions'=>['application_id'=>$application_id,'capacity_type'=>$capacity_type]
                                            ])->toArray();
		return $fetchhybrid_moduleDetails;
	}
}