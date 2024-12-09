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
class HybridShareDetailsTable extends AppTable
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
	var $table = 'hybrid_share_details';
	
	public function initialize(array $config)
	{
		$this->table($this->table);
	}
	
	public function save_hybrid_share_details($application_id,$arr_land_detail,$member_id)
	{
		
		$save_data           							= TableRegistry::get('HybridShareDetails'); 
		$save_data_entity    							= $save_data->newEntity(); 
		$save_data_entity->app_dev_per_id   		 	= $application_id;
		$save_data_entity->name_of_share_holder   	    = $arr_land_detail['name_of_share_holder'];
		$save_data_entity->equity_persontage   		    = $arr_land_detail['equity_persontage'];
		$save_data_entity->created_by   		 		= $member_id;
		$save_data_entity->created_date           	    = $this->NOW();		
		$save_data->save($save_data_entity);     
		
	}
    public function fetchdata($application_id)
	{
		$fetch_hybrid_details = $this->find('all',array('conditions'=>array('app_dev_per_id'=>$application_id)))->toArray();		
		return $fetch_hybrid_details;
	}

	
}