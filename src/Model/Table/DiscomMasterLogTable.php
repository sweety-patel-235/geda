<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class DiscomMasterLogTable extends AppTable
{
	var $table = 'discom_master_log';
	
	public function initialize(array $config)
	{
		$this->table($this->table);       	
	}
}
?>