<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class SldcApplicationDetailsTable extends AppTable
{
	var $table = 'sldc_application_details';

    public function initialize(array $config)
    {
        $this->table($this->table);       	
    }
}
?>