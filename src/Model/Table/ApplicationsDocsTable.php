<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class ApplicationsDocsTable extends AppTable
{
	var $table = 'applications_docs';

    public function initialize(array $config)
    {
        $this->table($this->table);       	
    }
}
?>