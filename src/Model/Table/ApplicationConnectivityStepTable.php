<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;
use Cake\Core\Configure;
use Cake\Network\Email\Email;
use Couchdb\Couchdb;
class ApplicationConnectivityStepTable extends AppTable
{
	var $table 		= 'application_connectivity_step';
	var $couchdbid 	= '';
	public function initialize(array $config)
	{
		$this->table($this->table);         
	}
}
