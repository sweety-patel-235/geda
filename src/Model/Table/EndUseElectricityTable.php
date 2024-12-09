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
 * Short description for file
 * This Model use for installer . It extends Table Class
 * @category  Class File
 * @Desc      Manage installer information
 * @author    Jayshree Tailor
 * @version   Solar 1.0
 * @since     File available since Solar 1.0
 */

class EndUseElectricityTable extends AppTable
{
	var $table 		= 'application_end_use_electricity';
	var $data 		= array();
	var $dataRecord = array();

	public function initialize(array $config)
	{
		$this->table($this->table);
	}
}