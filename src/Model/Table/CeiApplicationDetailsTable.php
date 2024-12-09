<?php
namespace App\Model\Table;

use App\Model\Table\Entity;
use App\Model\Entity\User;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

use Cake\Utility\Security;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Datasource\ConnectionManager;

class CeiApplicationDetailsTable extends AppTable
{
    var $table = 'cei_application_details';
    var $data  = array();

    public function initialize(array $config)
    {
        $this->table($this->table);         
    }
    /**
     * Add validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationAdd(Validator $validator)
    {  
        $validator->notEmpty('drawing_app_no', 'Enter drawing application number.');
        $validator->notEmpty('drawing_app_status', 'Enter drawing application status.');
        return $validator;
    }
}