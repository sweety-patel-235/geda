<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class DeveloperApplicationsDocsTable extends AppTable
{
	var $table = 'developer_applications_docs';

    public function initialize(array $config)
    {
        $this->table($this->table);       	
    }
}
?>