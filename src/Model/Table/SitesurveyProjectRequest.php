<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class SitesurveyProjectRequestTable extends AppTable
{
	var $table = 'sitesurvey_project_request';
	var $data  = array();

    public function initialize(array $config)
    {
        $this->table($this->table);       	
    }
}
?>