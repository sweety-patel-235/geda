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
class WindHybridDpLetterTable extends AppTable
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
	var $table = 'wind_hybrid_dp_letter';
	
	public function initialize(array $config)
	{
		$this->table($this->table);
	}

	
	
}