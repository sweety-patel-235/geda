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
use Cake\Network\Email\Email;
use Cake\Event\Event;
use Cake\Datasource\ConnectionManager;


/**
 * Short description for file
 * This Model use for developer Wororder . It extends Table Class
 * @category  Class File
 * @Desc      Manage installer information
 * @author    
 * @version   Solar 1.0
 * @since     File available since Solar 1.0
 */

class DeveloperWorkorderTable extends AppTable
{
	var $table 		= 'developer_workorder';
	var $data 		= array();
	var $dataRecord = array();

	public function initialize(array $config)
	{
		$this->table($this->table);
	}
}