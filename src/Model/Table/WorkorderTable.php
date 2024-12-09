<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
/**
 * Short description for file
 * This Model use for Ticket table. It extends AppModel Class
 * @category  Class File
 * @Desc      Manage Ticket information
 * @author    jaysinh Rajpoot
 * @version   RR
 * @since     File available since RR 1.0
 */
class WorkorderTable extends Table {
	/**
	 * The status of $name is universe
	 * Potential value are Class Name
	 * @var String
	 */
	public $Name 	= 'Workorder';

    /**
     * The status of $useTable is universe
     * Potential value are Database Table name
     * @var String
     */
    public $useTable = 'project_workorder';
    public function initialize(array $config)
    {
        $this->table($this->useTable);
    }
    /**
     * Add validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationtab(Validator $validator)
    {   
        $validator->notEmpty('workorder_number', 'Work Order Number can not be blank.');
        
        return $validator;
    }
}
?>