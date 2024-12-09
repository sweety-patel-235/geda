<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

/**
 * 
 * 
 * Short description for file
 * This Model use for Igadmintrntype table. It extends AppModel Class
 * Igadmintrngroup Model use for Select, Update data from Admintrntypes table
 * @author chirag
 *
 * @category  Class File
 * @Desc      Manage admin Transaction module related functionality and data respectivly, Mostly used for Admin section      
 * @author    Employee Code : -
 * @version    
 * @since     File available since 
 */
class AdmintrntypeTable extends Table {
	/**
	 * 
	 * initialize Table
	 *
	 */
	public $useTable = 'admintrntypes';
	public function initialize(array $config)
    {
        $this->table($this->useTable);
    }
}
?>