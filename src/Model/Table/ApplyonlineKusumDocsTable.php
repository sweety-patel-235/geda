<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class ApplyonlineKusumDocsTable extends AppTable
{
	var $table = 'applyonline_kusum_docs';

    public function initialize(array $config)
    {
        $this->table($this->table);       	
    }
}
?>