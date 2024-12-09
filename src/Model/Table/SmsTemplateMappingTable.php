<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;
use Cake\Core\Configure;
use Cake\Network\Email\Email;
class SmsTemplateMappingTable extends AppTable
{
	var $table = 'sms_template_mapping';
	public function initialize(array $config)
	{
		$this->table($this->table);
		
	}
}