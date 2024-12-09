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
class WindWtgDetailTable extends AppTable
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
	var $table = 'wind_wtg_detail';
	
	public function initialize(array $config)
	{
		$this->table($this->table);
	}
	public function getWtgSum($application_id)
	{
		$ApplicationGeoLocation 	= TableRegistry::get('ApplicationGeoLocation');
		$application_geo_loc = $ApplicationGeoLocation->find('all', array(
			'fields'	=> ['sumOfCapacity'=>'sum(wtg_capacity)'],
			'join'		=> ['table' => 'wind_wtg_detail','type' => 'LEFT','conditions' => ['ApplicationGeoLocation.id = wind_wtg_detail.app_geo_loc_id']],
			'conditions' => array('wind_wtg_detail.app_dev_per_id' => $application_id)
			))->first();
			return isset($application_geo_loc->sumOfCapacity)?$application_geo_loc->sumOfCapacity/1000:0;
		}	
}