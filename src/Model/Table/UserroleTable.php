<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use App\Model\Entity\User;
use Cake\Validation\Validator;
/**
 * Short description for file
 * This Model use for Ticket table. It extends Table Class
 * @category  Class File
 * @Desc      Manage Ticket information
 * @author    Dhingani Yatin
 * @version   EX
 * @since     File available since EX 1.0
 */
class UserroleTable extends AppTable {
	/**
	 *
	 * The status of $name is universe
	 *
	 * Potential value are Class Name
	 *
	 * @var String
	 *
	 */
	var $name = 'Userrole';
	/**
	 *
	 * The status of $useTable is universe
	 *
	 * Potential value are Class Name
	 *
	 * @public String
	 *
	 */
	public $useTable = 'user_roles';
	public function initialize(array $config)
    {
        $this->table($this->useTable);
    }
	/**
	 *
	 * The status of $useTable is universe
	 *
	 * Potential value are Class Name
	 *
	 * @public String
	 *
	 */
	public $ADMIN_USER_ROLE_CONSULTANT = 5;

	/**
	 *
	 * The status of $validate is universe
	 *
	 * set Validation rules for admin user data save
	 *
	 * @var Array
	 *
	 */
	/*var $validate = array(
		'rolename' => array(
						'notEmpty' => array(
							'rule' => 'notEmpty',
							'last' => true,
							'message' => 'Role Name can not be blank.'
						),
						'minLength' => array(
							'rule' =>  array('minLength',5),
							'last' => true,
							'message' => 'Role Name must be atleast 5 characters.'
						),
						'unique' => array(
							'rule' => 'isUnique',
							'last' => true,
							'message' => 'Role Name should be unique.'
						)
					)
	);*/

	/**
	 *
	 * getAdminuserRoles
	 *
	 * Behaviour	: Public
	 *
	 * @return		: This function returns all the admin roles
	 * @defination	: This method will return all the admin user roles as an array.
	 *
	 */
	public function getAdminuserRoles()
	{
		$arrReturn		= array();
		$arrAdminuserRole = $this->find('list',['keyField' => 'id','valueField' => 'rolename'])->toArray();
		
		if(is_array($arrAdminuserRole) && count($arrAdminuserRole)>0)
			return $arrAdminuserRole;
		return $arrReturn;
	}

	/**
	*
	* setAdminuserRoles
	*
	* Behaviour		: Public
	* @param		: $Session : Session component for writing admin user role in session.
	* @return		: This functions returns admin user roles array.
	* @defination	: This method returns admin user roles array and write Adminuser.roles session if it found it empty.
	*
	*/
	public function setAdminuserRoles($Session)
	{
		$AdminRoles = $Session->read('User.roles');
		if(empty($AdminRoles))
		{
			$arrAdminRole = $this->getAdminuserRoles();
			$Session->write('User.roles',serialize($arrAdminRole));
		} else {
			$arrAdminRole = unserialize($AdminRoles);
		}
		return $arrAdminRole;
	}

	/**
     * Add validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationAdd(Validator $validator)
    {
    	$validator->notEmpty('rolename', 'Role Name can not be blank.');
    	return $validator;
    }
    /**
     * Edit validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationEdit(Validator $validator)
    {
    	$validator->notEmpty('rolename', 'Role Name can not be blank.');
    	return $validator;
    }
}
?>