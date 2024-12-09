<?php
/**
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

class AdmintransactionTable extends Table {
	/**
	 *
	 * The status of $name is universe
	 *
	 * Potential value are Class Name
	 *
	 * @var String
	 *
	 */
	var $table = 'admintransactions';
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
		$this->belongsTo('Admintrngroup');
    }
	public function getAllActiveAdminTransaction()
	{
		$arrReturn = array();
		$arrAdminTransaction = $this->find('all',array('conditions'=>array('showtrnflg'=>'Y'),'order'=>array('Igadmintransaction.trnmoduleid','Igadmintransaction.menuorder')));

		if(is_array($arrAdminTransaction) && count($arrAdminTransaction)>0)
			return $arrAdminTransaction;
		return $arrReturn;
	}
	public function getUserModuleRights($userrights)
	{
		$arrModuleRight = array();
		$arrModuleRight = $this->find('all',array('conditions'=>array('showtrnflg'=>'Y','id in'=>$userrights),'order'=>array('Admintransaction.trnmoduleid','Admintransaction.trntype')))->toArray();
		return $arrModuleRight;
	}
}
?>