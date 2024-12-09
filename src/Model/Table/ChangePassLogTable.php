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
class ChangePassLogTable extends AppTable {
	/**
	 * The status of $name is universe
	 * Potential value are Class Name
	 * @var String
	 */
	public $Name 	= 'ChangePassLog';
    /**
     * The status of $useTable is universe
     * Potential value are Database Table name
     * @var String
     */
    public $useTable = 'change_pass_log';
    public function initialize(array $config)
    {
        $this->table($this->useTable);
    }
}   
?>