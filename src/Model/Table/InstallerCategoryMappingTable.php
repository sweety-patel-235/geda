<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class InstallerCategoryMappingTable extends AppTable
{
	var $table = 'installer_category_mapping';
    public function initialize(array $config)
    {
        $this->table($this->table);       	
    }
}