<?php
/**
 * Short description for file
 * This Model use for adminuser_role_rights table. It extends AppModel Class
 * Userroleright Model use for Select, Update data from adminuser_role_rights table
  *
 * @category  Class File
 * @Desc      Manage and display all admin user role rights related functionality and data respectivly, Mostly used for Admin section
 * @author    Jitendra Rath0d
 * @version   Web App Security 2.0.7
 * @since     File available since TMWAS 2.0.7
 */
 
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Validation\Validator;

use App\Model\Table\Entity;
use App\Model\Entity\Userroleright;

class UserrolerightTable extends Table {
	
	public $useTable = 'user_role_rights';

	public function initialize(array $config)
    {
        $this->table($this->useTable);
    }
	
	/**
	 *
	 * The status of $name is universe
	 *
	 * Potential value are Class Name
	 *
	 * @var String
	 *
	 */
	var $name = 'Userroleright';
	/**
	 *
	 * The status of $useTable is universe
	 *
	 * Potential value are Class Name
	 *
	 * @public String
	 *
	 */
	
	
	var $validate = array(
		'roleid' => array(
						'notEmpty' => array(
							'rule' => 'notEmpty',
							'last' => true,
							'message' => 'Role can not be blank.'
						)
					),
		'rightid' => array(
						'notEmpty' => array(
							'rule' => 'notEmpty',
							'last' => true,
							'message' => 'Right can not be blank.'
						)
					)

	);

	/**
	*
	* saveAdminuserRoleRight
	*
	* Behaviour		: Public
	* @param		: $roleid	: Role id which rights we need to save in database.
	* @param		: $rightid	: Right id for the role.
	* @return		: This functions save the admin user role right based on arguments.
	* @defination	: This method insert the new record in adminuser_role_rights table according to passed arguments.
	*
	*/
	public function saveAdminuserRoleRight($roleid, $rightid)
	{
		$arrright=array();
		$arrright=explode('_',$rightid);
		$trnmoduleid=(isset($arrright[0])?$arrright[0]:0);
		$trntypeid=(isset($arrright[1])?$arrright[1]:0);

		$Userroleright = new Userroleright;
		$Userroleright->roleid 		= $roleid;
		$Userroleright->trnmoduleid = $trnmoduleid;
		$Userroleright->trntypeid 	= $trntypeid;
		$Userroleright->created 	= date("Y-m-d H:i:s");

		$this->save($Userroleright);
	}

	/**
	*
	* getAllAdminUserRoleRight
	*
	* Behaviour		: Public
	* @param		: $roleid : Role id which all the rights we need to retrive.
	* @return		: This functions returns admin user role rights.
	* @defination	: This method returns admin user role rights based on argument role id and it return role rights as an array.
	*
	*/
	public function getAllAdminUserRoleRight($roleid)
	{
		
		$arrReturn				= array();
		$arrAdminuserroleright	= array();
		$arrAdminuserroleright	= $this->find('all', array('conditions'=>array('roleid'=>$roleid)))->toArray();
		
		foreach($arrAdminuserroleright as $keyid=>$userroleright)
		{	
			$row=$userroleright;
			
			if(array_key_exists($userroleright['trnmoduleid'],$arrReturn))
			{
				$arrReturn[$userroleright['trnmoduleid']]=$arrReturn[$userroleright['trnmoduleid']].','.(isset($userroleright['trntypeid'])?$userroleright['trntypeid']:'');
			}
			else
			{	
				$arrReturn[$userroleright['trnmoduleid']]=(isset($userroleright['trntypeid'])?$userroleright['trntypeid']:'');
			} 		
		}
		return $arrReturn;
	}
}
?>