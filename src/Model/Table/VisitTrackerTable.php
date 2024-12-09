<?php

namespace App\Model\Table;

use App\Model\Entity\User;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
/**
 * @category  Class File
 * @Desc      Manage admin section user logs data respectivly, Mostly used for Admin section      
 * @author    Employee Code : -
 * @version   IG 
 * @since     File available since IG
 */
class VisitTrackerTable extends AppTable 
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
	var $table = 'visitor_tracker';
	public function initialize(array $config)
    {
		$this->table($this->table);
    } 
}
?>