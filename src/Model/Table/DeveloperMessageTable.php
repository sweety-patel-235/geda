<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class DeveloperMessageTable extends AppTable
{
	var $table 	= 'developer_messages';
	public function initialize(array $config)
	{
		$this->table($this->table);
	}
}