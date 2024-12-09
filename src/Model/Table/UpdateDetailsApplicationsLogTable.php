<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class UpdateDetailsApplicationsLogTable extends AppTable
{
	var $table          = 'update_details_applications_log';
    var $data           = array(); 
    var $TYPE_ARR       = array('application/pdf'); 
    var $TYPE_PHOTO_ARR = array('image/jpeg'); 
    var $EXT_ARR        = array('pdf'); 
    var $EXT_PHOTO_ARR  = array('jpg', 'jpeg'); 
    public function initialize(array $config)
    {
        $this->table($this->table);       	
    }

}