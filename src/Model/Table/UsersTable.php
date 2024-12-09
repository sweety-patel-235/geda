<?php
namespace App\Model\Table;

use App\Model\Table\Entity;
use App\Model\Entity\User;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

use Cake\Utility\Security;
use Cake\Core\Configure;
use Cake\Event\Event;

/**
 * Short description for file
 * This Model use for user table. It extends Table Class
 * @category  Class File
 * @Desc      Manage User information
 * @author    Dhingani Yatin
 * @version   EX
 * @since     File available since EX 1.0
 */

class UsersTable extends AppTable
{
	/**
	 *
	 * The status of $STATUS_ACTIVE is universe
	 *
	 * Potential value are 1 (identify Admin User Active)
	 *
	 * @var Int
	 *
	 */
	var $STATUS_ACTIVE = 1;
	/**
	 *
	 * The status of $STATUS_INACTIVE is universe
	 *
	 * Potential value are 0 (identify Admin User InActive/Deactive)
	 *
	 * @var Int
	 *
	 */
	var $STATUS_INACTIVE 			= 0;
	var $DEFAULT_USER_TIMEZONE		='Asia/Kolkata';
	var $RETAILER_RECHARGE_ROLE_ID 	= '';
	public $validate				= array();
	public $validationSet			= "";
	
	public function initialize(array $config)
    {
        $this->table('users');
    }
	/**
	 *
	 * The status of $validate_timezone is universe
	 *
	 * Potential value are validate time zone
	 *
	 * @var Array
	 *
	 */
	var $validate_timezone =  array(
		/*
			'timezone' => array(
					'rule' => array('maxLength',5),
					'required' => true,
					'allowEmpty' => false,
					'message' => 'Please select valid Time zone.'
			)
			*/
	);

	/**
	 *
	 *  identicalFieldValues
	 *
	 * Behaviour : Public
	 *
	 * @return : its return boolean
	 * @defination : befor saving data in User table Password field compared with Confirm password field.
	 *
	 */
    function identicalFieldValues( $field=array(), $compare_field=null )
    {
        foreach( $field as $key => $value ){
            $v1 = $value;
            $v2 = $this->data[$this->name][ $compare_field ];
            if($v1 !== $v2) {
                return FALSE;
            } else {
                continue;
            }
        }
        return TRUE;
    }
	/**
	 *
	 *  beforeSave
	 *
	 * Behaviour : Public
	 *
	 * @return : its return boolean
	 * @defination : befor saving data in User table Password field encrypted with Security salt
	 *
	 */
	//Public function beforeSave(Event $event,Entity $entity)
	public function beforeSave($event, $entity, $options)
	{
		if(isset($entity->password) && !empty($entity->password))
		{
			$entity->password = Security::hash(Configure::read('Security.salt') . $entity->password);
		}elseif(isset($entity->password) && !empty($entity->newpassword)){
			$entity->password = Security::hash(Configure::read('Security.salt') . $entity->newpassword);
		}
		return TRUE;
	}

	/**
	 *
	 *  getAdminUserTypeWise
	 *
	 * Behaviour   : Public
	 *
	 * @param : $Admintype : If available should pass here to define data belong to which Admin Type
	 * @return :  its returns the Admin User Data Array
	 * @defination : this method find the particular admin type wise Admin users. for e.g. Admin Type Consultant
	 *
	 */
	function getAdminUserTypeWise($Admintype=null,$status=1)
	{
		if($Admintype==null)return array();

		$arrConditions=array("User.usertype"=>$Admintype,"User.status"=>$status);

		return $this->find('all',array("conditions"=>$arrConditions));
	}

	public function GenerateAdminuserRightSession($adminuserid, $session)
	{
		$this->id = $adminuserid;
		$arrAdminuser = $this->get($this->id);
		$arrAdminRoleRights = array();
		$arrUserRights 		= array();
		
		$objUserroleright 	= TableRegistry::get('Userroleright');
		$Admintransaction 	= TableRegistry::get('Admintransaction');
		
		$arrAdminRoleRights = $objUserroleright->getAllAdminUserRoleRight($arrAdminuser['usertype']);
		if(is_array($arrAdminRoleRights) && count($arrAdminRoleRights)>0)
		{
			$arrUserRights 		= unserialize($arrAdminuser['userrights']);
		}
		$userrights			= array();
		$conarr				= array();
        if(is_array($arrAdminRoleRights) && count($arrAdminRoleRights)>0) {
            foreach ($arrAdminRoleRights as $keyid => $arrights) {
                $userrights[$keyid] = $arrights;
            }
        }
        if(is_array($arrUserRights) && count($arrUserRights)>0) {
            foreach ($arrUserRights as $kid => $arights) {
                if (isset($userrights[$kid])) {
                    $userrights[$kid] = $userrights[$kid] . "," . $arights;
                } else {
                    $userrights[$kid] = $arights;
                }
            }
        }
		foreach($userrights as $moduleid=>$permissiontypes) {
			$conarr[]=" trnmoduleid='".$moduleid."' AND trntype IN(".implode(",",array_unique(explode(",",$permissiontypes))).") ";
		}
		$arradmintransaction = array();
		if(!empty($conarr)){
			$arradmintransaction = $Admintransaction->find('list',array('fields'=>array('id'),'conditions'=>array('OR'=>$conarr)));
			$arradmintransaction = $arradmintransaction->toArray();
		}
		return $arradmintransaction;
	}

	public function AddValidation($field,$Rules=array()) {
		$this->validator()->add($field,$Rules);
	}

	public function setValiationRules($rule="register")
	{
		$param = 'validate'.strtolower($rule);
		if (isset($this->{$param})) {
			$this->validationSet	= $rule;
			$this->validate			= $this->{$param};
		}
	}

	public function validates($options = array()) 
	{
		// copy the data over from a custom var, otherwise
		$actionSet = 'validate' . Inflector::camelize(Router::getParam('action'));
		if (isset($this->validationSet)) {
			$temp			= $this->validate;
			$param			= 'validate' . $this->validationSet;
			$this->validate = $this->{$param};
		} elseif(isset($this->{$actionSet})) {
			$temp			= $this->validate;
			$param			= $actionSet;
			$this->validate = $this->{$param};
		}
		$errors = $this->invalidFields($options);
		
		// copy it back
		if (isset($temp)) {
			$this->validate = $temp;
			unset($this->validationSet);
		}
		if (is_array($errors)) {
			return count($errors) === 0;
		}
		return $errors;
	}

	/**
	 *
	 *  getAdminUserList
	 *
	 * Behaviour   : Public
	 *
	 * @param : $Admintype : If available should pass here to define data belong to which Admin Type
	 * @return :  its returns the Admin User Data Array
	 * @defination : this method find the particular admin type wise Admin users. for e.g. Admin Type Consultant
	 *
	 */
	function GetUserList($Admintype=null,$status=1)
	{
		if($Admintype==null)return array();
		$arrConditions=array("usertype"=>$Admintype,"status"=>$status);
	
		return $this->find('list',array('fields'=>array("User.id","User.username"),"conditions"=>$arrConditions));
	}

	/**
	 *
	 *  GetAllUser
	 *
	 * Behaviour   : Public
	 *
	 * @param : $Admintype : If available should pass here to define data belong to which Admin Type
	 * @return :  its returns the Admin User Data Array
	 * @defination : this method find the particular admin type wise Admin users. for e.g. Admin Type Consultant
	 *
	 */
	function GetAllUser($id=null)
	{
		if($id==null)return array();
		$arrConditions=array("id"=>$id,'status'=>$this->STATUS_ACTIVE);
		return $this->find('list',array('fields'=>array("User.id","User.username"),"conditions"=>$arrConditions));
	}

	/**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationLogin(Validator $validator)
    {
    	$validator->notEmpty('LoginUsername', 'Please Enter Username.');
		$validator->notEmpty('LoginPassword', 'Please Enter Password.');

    	return $validator;
    }
	/**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationChangePassword(Validator $validator)
    {
    	$validator->notEmpty('password', 'Please Enter Password.');
		$validator->notEmpty('confirm_password', 'Please Confirm Password.')
		->add('confirm_password', 'passwordsEqual', [
		    'rule' => function ($value, $context) {
		        return
		            isset($context['data']['password']) &&
		            $context['data']['password'] === $value;
		    },
		    'message' => 'Password are mismatch.'
		]);

    	return $validator;
    }
	

	/**
     * Add validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationAdd(Validator $validator)
    {
    	$validator->notEmpty('firstname', 'First Name can not be blank.');
		$validator->notEmpty('lastname', 'Last Name can not be blank.');

		$validator->notEmpty('username', 'Username can not be blank.');
		$validator->notEmpty('password', 'Password can not be blank.');
		$validator->notEmpty('confirmpassword', 'Confirmpassword can not be blank.');
		$validator->notEmpty('usertype','User Type must be select');
		$validator->add('confirmpassword', 'passwordsEqual', [
		    'rule' => function ($value, $context) {
		        return
		            isset($context['data']['password']) &&
		            $context['data']['password'] === $value;
		    },
		    'message' => 'Password are mismatch.'
		]);

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
    	$validator->notEmpty('firstname', 'First Name can not be blank.');
		$validator->notEmpty('lastname', 'Last Name can not be blank.');

		$validator->notEmpty('username', 'Username can not be blank.');
		$validator->notEmpty('usertype','User Type must be select');
		$validator->add('password', 'passwordsEqual', [
		    'rule' => function ($value, $context) {
		        return
		            isset($context['data']['confirmpassword']) &&
		            $context['data']['confirmpassword'] === $value;
		    },
		    'message' => 'Password are mismatch.'
		]);
		

    	return $validator;
    }
    /**
    * userslist
    * list like key value
    * @return list of User
    */
    public function userslist($char = ''){
        return $this->find('list', ['keyField' => 'id','valueField' => 'username','conditions'=>array('username like'=>'%'.$char.'%')])->order('username')->limit(10)->toArray();
    }
}
?>