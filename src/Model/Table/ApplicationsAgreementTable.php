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
use Cake\Event\Event;
use Cake\Datasource\ConnectionManager;
use Cake\I18n\Date;
/**
 * @category  Class File
 * @author    Employee Code : -
 * @version   GED 1.0
 * @since     File available since GED
 */
class ApplicationsAgreementTable extends AppTable
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
	var $table = 'applications_agreement';
	
	public function initialize(array $config)
	{
		$this->table($this->table);
	}
	public function save_data($application_id,$arr_modules,$customer_id)
	{
		$ReCouchdb           									= TableRegistry::get('ReCouchdb'); 
		$saveapplication_data           						= TableRegistry::get('ApplicationsAgreement'); 
		$saveapplication_data_entity    						= $saveapplication_data->newEntity(); 
		$saveapplication_data_entity->application_id   		 	= $application_id;
		$saveapplication_data_entity->consumer_count			= $arr_modules['consumer_count'];
		$saveapplication_data_entity->total_consumer_details_application			= $arr_modules['total_consumer_details_application'];
		$saveapplication_data_entity->total_allocate_capacity	= $arr_modules['total_allocate_capacity'];
		$saveapplication_data_entity->created_by   		 		= $customer_id;
		$saveapplication_data_entity->created_date           	= $this->NOW();
		$saveapplication_data->save($saveapplication_data_entity);
		$insertId = $saveapplication_data_entity->id;

	}

	public function fetchdata($application_id,$application_type)
	{
		$fetchapplication_data        = TableRegistry::get('ApplicationGeoLocation'); 
		$fetchapplication_dataDetails = $this->find('all',array('fields'=> array('capacity_type','nos_mod_inv','mod_inv_capacity','mod_inv_total_capacity','mod_inv_make','application_id','id'),'conditions'=>array('application_id'=>$application_id,'capacity_type'=>$capacity_type)))->toArray();
		
		return $fetchapplication_dataDetails;
	}
	public function internal_clashed_docs($geo_id)
    {
        $fetchgeoapplication_data        = TableRegistry::get('GeoApplicationClashedData'); 
        $fetchgeoapplication_dataDetails = $fetchgeoapplication_data->find('all',array('fields'=> array('id','application_id','clashed_geo_id','uploadfile','uploadfile_type'),'conditions'=>array('clashed_geo_id'=>$geo_id,'clashed_for'=>2)))->first();
       	return $fetchgeoapplication_dataDetails;
    }
	public function get_total_consumer($application_id)
	{
		
		$AgreementConsumerDetails        = TableRegistry::get('AgreementConsumerDetails'); 
		$applicationCount 		= $AgreementConsumerDetails->find('all',array(
									'conditions'=>array(
										'application_id'		=> $application_id,
										)))->distinct(['consumer_no'])->toArray();
		$total_consumer = count($applicationCount);
		
		return $total_consumer;
	}

	
	public function get_total_allocated_capacity($id)
	{
		
		$AgreementConsumerDetails     = TableRegistry::get('AgreementConsumerDetails');

		$wtg_capacity = $AgreementConsumerDetails->find('all', ['conditions' => ['application_id' => $id],'fields' => ['total' => 'SUM(wtg_capacity)']])->first();

		$capacity_allocated = $AgreementConsumerDetails->find('all', ['conditions' => ['application_id' => $id],'fields' => ['total' => 'SUM(capacity_allocated)']])->first();
		$total_allocated_capacity = $wtg_capacity->total-$capacity_allocated->total;
		$value = 100; // The value you want to calculate the percentage of
		$total_allocated_capacity_per = ($total_allocated_capacity / $value) * 100;
		return $total_allocated_capacity_per;
		
	}
	
	
}