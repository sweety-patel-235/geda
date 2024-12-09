<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

/**
 * 
 * 
 * Short description for file
 * This Model use for Igadmintrnmodule table. It extends Table Class
 * Igadmintrngroup Model use for Select, Update data from Igadmintrnmodule table
 * @author chirag
 *
 * @category  Class File
 * @Desc      Manage admin Transaction module related functionality and data respectivly, Mostly used for Admin section      
 * @author    Employee Code : -
 * @version   IG 
 * @since     File available since IG
 */
class AdmintrnmoduleTable extends Table {
	/**
	 * 
	 * The status of $name is universe
	 *
	 * Potential value are Class Name
	 *
	 * @var String 
	 *
	 */
	var $useTable = 'admintrnmodules';
	public function initialize(array $config)
    {
        $this->table($this->useTable);
    }

}
?>