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
class WindEnergyAdditionalDataTable extends AppTable
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
	var $table = 'wind_energy_additional_data';
	
	public function initialize(array $config)
	{
		$this->table($this->table);
	}
	public function save_energy($application_id,$arr_modules,$member_id)
	{
		
		$save_data           							= TableRegistry::get('WindEnergyAdditionalData'); 
		$save_data_entity    							= $save_data->newEntity(); 
		$save_data_entity->app_dev_per_id   		 	= $application_id;
		$save_data_entity->capacity_type   			    = 1;
		$save_data_entity->nos_mod_inv   				= $arr_modules['energy_discom'];
		$save_data_entity->mod_inv_capacity   		    = $arr_modules['energy_per'];
		$save_data_entity->created_by   		 		= $member_id;
		$save_data_entity->created_date           	    = $this->NOW();
		$save_data->save($save_data_entity);
		
	}

	
}