<?php

/**
 *
 *
 * Short description for file
 * This Model use for igadmintransactions table. It extends Table Class
 * Igadmintransaction Model use for Select, Update data from igadmintransactions table
 * @author
 *
 * @category  Class File
 * @Desc      Manage admin transaction related functionality and data respectivly, Mostly used for Admin section
 * @author    Employee Code : - Dhingani Yatin
 * @version   IG
 * @since     File available since IG
 */
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class AdmintrngroupTable extends Table {
	/**
	 *
	 * The status of $name is universe
	 *
	 * Potential value are Class Name
	 *
	 * @var String
	 *
	 */
	var $table = 'admintrngroup';
	/**
	 *
	 * The status of $useTable is universe
	 *
	 * Potential value are Class Name
	 *
	 * @public String
	 *
	 */
	public function initialize(array $config)
    {
		$this->table($this->table);
    }
}
?>