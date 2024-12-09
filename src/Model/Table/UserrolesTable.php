<?php
/**
 *
 *
 * Short description for file
 * This Model use for adminusers table. It extends AppModel Class
 * Userrole Model use for Select, Update data from adminuser_roles table
 *
 * @category  Class File
 * @Desc      Manage and display all admin user role related functionality and data respectivly, Mostly used for Admin section
 * @author    Jitendra Rathod
 * @version   IG
 * @since     File available since IG
 */
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Validation\Validator;

	

class UserrolesTable extends Table 
{
	/**s
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
	public function initialize(array $config)
    {
        $this->table('user_roles');
    }
	//public $useTable = 'user_roles';

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
	//public $helpers = ['Session'];
	//$this->loadHelper('Session');
	
	/**
	 *
	 * The status of $validate is universe
	 *
	 * set Validation rules for admin user data save
	 *
	 * @var Array
	 *
	 */
	 //$this->loadComponent('RequestHandler','Session'/*,'Cookie','JqdTable'*/);
	 public $components = array('RequestHandler'/*,'Session','Cookie'*/,'JqdTable');
	 
	var $validate = array(
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
	);

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
		$arrAdminuserRole = $this->find('list',['keyField' => 'id','valueField' => 'rolename'])->order(['rolename' =>'ASC'])->toArray(); /*array("Userroles.id","Userroles.rolename"),"order"=>"Userroles.rolename ASC"))->toArray()*/;
		if(is_array($arrAdminuserRole) && count($arrAdminuserRole)>0)
			return $arrAdminuserRole;
		return $arrAdminuserRole;
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

}
?>