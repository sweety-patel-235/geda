<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
/**
 * Short description for file
 * This Model use for Ticket table. It extends AppModel Class
 * @category  Class File
 * @Desc      Manage Ticket information
 * @author    jaysinh Rajpoot
 * @version   RR
 * @since     File available since RR 1.0
 */
class DepartmentTable extends Table {
	/**
	 * The status of $name is universe
	 * Potential value are Class Name
	 * @var String
	 */
	public $Name 	= 'Department';

    /**
     * The status of $useTable is universe
     * Potential value are Database Table name
     * @var String
     */
    public $useTable = 'departments';
    public function initialize(array $config)
    {
        $this->table($this->useTable);
    }
	/**
	 *
	 * GetDepartmentList
	 *
	 * Behaviour : Public
	 *
	 * @defination : Use for get Department details if having master right to logged in user
	 *
	 */
	public function GetDepartmentList()
	{
		return $this->find('list',['keyField' => 'id','valueField' => 'name'])->toArray();
	}
}
?>