<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class InstallerMessageTable extends AppTable
{
	var $table 	= 'installer_messages';
	public function initialize(array $config)
	{
		$this->table($this->table);
	}
}