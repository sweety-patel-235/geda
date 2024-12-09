<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class InstallerCategoryTable extends AppTable
{
	var $table = 'installer_category';
    public function initialize(array $config)
    {
        $this->table($this->table);       	
    }
}