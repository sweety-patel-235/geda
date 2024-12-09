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
use Cake\Auth\DefaultPasswordHasher;
use Cake\Network\Email\Email;


/**
 * Short description for file
 * This Model use for Members. It extends Table Class
 * @category  Class File
 * @Desc      Manage Members
 * @author    Khushal Bhalsod
 * @version   Solar 1.0
 * @since     File available since Solar 1.0
 */

class MembersTable extends AppTable
{
	var $STATUS_ACTIVE 			= 1;
	var $STATUS_INACTIVE 		= 0;

	var $activation_code_min 	= 1000;
	var $activation_code_max 	= 9999;

	var $password_start_digit 	= 100000;
	var $password_end_digit 	= 999999;

	var $member_type_discom 	= 6002;

	var $DEFAULT_USER_TIMEZONE 		= 'Asia/Kolkata';
	var $RETAILER_RECHARGE_ROLE_ID 	= '';
	var $validationSet				= "";
	var $validate					= array();
	var $table 						= 'Members';

	public function initialize(array $config)
	{
		$this->table('members');
		$this->addAssociations([
		  //'hasMany' => ['EventInvitations','EventBids','EventLogs','EventLots'],
		  'belongsTo' => ['Installers']
		]);
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
	public $validate_timezone =  array(
		/*
			'timezone' => array(
					'rule' => array('maxLength',5),
					'required' => true,
					'allowEmpty' => false,
					'message' => 'Please select valid Time zone.'
			)
			*/
	);

	public $validate_registration = array(

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
	public function identicalFieldValues( $field=array(), $compare_field=null )
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
	/*public function beforeSave($event, $entity, $options)
	{
		if(isset($entity->password) && !empty($entity->password))
		{
			$entity->password = Security::hash(Configure::read('Security.salt') . $entity->password);
		}
		return TRUE;
	}*/

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
	public function getAdminUserTypeWise($Admintype=null,$status=1)
	{
		if($Admintype==null)return array();

		$arrConditions=array("Members.usertype"=>$Admintype,"Members.status"=>$status);

		return $this->find('all',array("conditions"=>$arrConditions));
	}

	public function GenerateCustomerRightSession($adminuserid, $session)
	{
		$this->id = $adminuserid;
		$arrAdminuser = $this->get($this->id);
		$arrAdminRoleRights = array();
		$arrUserRights 		= array();

		$objUserroleright 	= TableRegistry::get('Userroleright');
		$Admintransaction 	= TableRegistry::get('Admintransaction');
		//pr($arrAdminuser);
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
		if(empty($userrights))
		{
			$this->Flash->set('You are not authorized to view that page.');
			return $this->redirect('/admin/users/login');
		}
		foreach($userrights as $moduleid=>$permissiontypes) {
			$conarr[]=" trnmoduleid='".$moduleid."' AND trntype IN(".implode(",",array_unique(explode(",",$permissiontypes))).") ";
		}
		$arradmintransaction = $Admintransaction->find('list',array('fields'=>array('id'),'conditions'=>array('OR'=>$conarr)));
		$arradmintransaction = $arradmintransaction->toArray();
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
	 * GetParameterList
	 *
	 * Behaviour : Public
	 *
	 * @defination : Use for get Parameter list if having master right to logged in user
	 *
	 */
	public function GetCustomernameList(){
		return $this->find('list',array('keyField' => 'id',
	'valueField' => 'name','conditions'=>array('status'=>$this->STATUS_ACTIVE,'customer_type'=>'customer'),'order'=>array('name')));
	}
	/**
	 *
	 * GetInstallerNameList
	 *
	 * Behaviour : Public
	 *
	 * @defination : Use for get Parameter list if having master right to logged in user
	 *
	 */
	public function GetInstallerNameList(){
		return $this->find('list',array('keyField' => 'id',
	'valueField' => 'name','conditions'=>array('status'=>$this->STATUS_ACTIVE,'customer_type'=>'installer'),'order'=>array('name')));
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
	public function GetCustomerList($Admintype=null,$status=1)
	{
		if($Admintype==null)return array();
		$arrConditions=array("usertype"=>$Admintype,"status"=>$status);

		return $this->find('list',array('fields'=>array("Members.id","Members.name"),"conditions"=>$arrConditions));
	}
	/**
	 *
	 *  LoggedinUser
	 *
	 * Behaviour   : Public
	 *
	 * @param : $Admintype : If available should pass here to define data belong to which Admin Type
	 * @return :  its returns the Admin User Data Array
	 * @defination : this method find the particular admin type wise Admin users. for e.g. Admin Type Consultant
	 *
	 */
	public function LoggedinUser($cus_id=null)
	{
		$this->query()
			->update()
			->set(array("last_login_date" => date('Y-m-d H:i:s')))
			->where(array("id" => $cus_id))
			->execute();
		return true;
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
	public function GetAllCustomer($id=null)
	{
		if($id==null)return array();
		$arrConditions=array("id"=>$id,'status'=>$this->STATUS_ACTIVE);
		return $this->find('list',array('fields'=>array("Members.id","Members.name"),"conditions"=>$arrConditions));
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
	 * Add validation rules.
	 *
	 * @param \Cake\Validation\Validator $validator Validator instance.
	 * @return \Cake\Validation\Validator
	 */
	public function validationAdd(Validator $validator)
	{
		$validator->notEmpty('name', 'First Name can not be blank.');
		$validator->notEmpty('lastname', 'Last Name can not be blank.');

		$validator->notEmpty('username', 'Username can not be blank.');
		$validator->notEmpty('password', 'Password can not be blank.');
		$validator->notEmpty('confirmpassword', 'Confirmpassword can not be blank.');
		$validator->notEmpty('member_type','User Type must be select');
		$validator->notEmpty('state','State must be select');
		$validator->add('email', [
			'unique' => [
				'message'   => 'Email is already exists!',
				'provider'  => 'table',
				'rule'      => 'validateUnique'
			]
		]);
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
	 * Default validation rules for developer registration.
	 *
	 */
	public function validationRegistration(Validator $validator)
	{
		$validator->notEmpty('name', 'Please Enter Name.');
		$validator->notEmpty('password', 'Please Enter Password.');
		$validator->notEmpty('email', 'Please Enter Email.');
		$validator->notEmpty('company_name', 'Please Enter Company Name.');

		$validator->add('email', [
			'unique' => [
				'message'   => 'Email is already exists!',
				'provider'  => 'table',
				'rule'      => 'validateUnique'
			]
		]);


		return $validator;
	}

	/**
	 * Default validation rules for developer registration.
	 *
	 */
	public function validationCustomer(Validator $validator)
	{
		$validator->notEmpty('name', 'Please Enter Name.');

		$validator->add('email', [
			'unique' => [
				'message'   => 'Email is already exists!',
				'provider'  => 'table',
				'rule'      => 'validateUnique'
			]
		]);
		$validator->add('name', [
			'validFormat'=>[
				'rule' => array('custom', '/(^([0-9A-Za-z ]+)?$)/'),
				'message' => 'Name field contain invalid characters'
			]
		]);
		$validator->add('mobile', [
			'validFormat'=>[
				'rule' => array('custom', '/(^([0-9]+)(\d+)?$)/'),
				'message' => 'Numbers only'
			]
		]);
		$validator->add('mobile', [
			'length' => [
				'rule' => ['maxLength', 10],
				'message' => 'Mobile number allows 10 digits!',
			]
		]);
		$validator->add('mobile', [
			'length_1' => [
				'rule' => ['minLength', 10],
				'message' => 'Mobile number allows 10 digits!',
			]
		]);

		return $validator;
	}

	/* Validation rules for change password */
	function validationPassword(Validator $validator )
	{
		$validator
			->add('old_password','custom',[
				'rule'=>  function($value, $context) {
					$user = $this->get($context['data']['id']);
					if ($user) {
						//if ((new DefaultPasswordHasher)->check($value, $user->password)) {
						  //  return true;
						//}
						if($user['password'] == Security::hash(Configure::read('Security.salt') . $value)){
							return true;
						}
					}
					return false;
				},
				'message'=>'The old password does not match the current password!',
			])
			->notEmpty('old_password');

		$validator
			->add('password1', [
				'length' => [
					'rule' => ['minLength', 8],
					'message' => 'The password have to be at least 8 characters!',
				]
			])
			->add('password1',[
				'match'=>[
					'rule'=> ['compareWith','password2'],
					'message'=>'The passwords does not match!',
				]
			])
			->notEmpty('password1');
		$validator
			->add('password2', [
				'length' => [
					'rule' => ['minLength', 8],
					'message' => 'The password have to be at least 8 characters!',
				]
			])
			->add('password2',[
				'match'=>[
					'rule'=> ['compareWith','password1'],
					'message'=>'The passwords does not match!',
				]
			])
			->notEmpty('password2');

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
		$validator->notEmpty('member_type','User Type must be select');
		$validator->notEmpty('state','State must be select');
		$validator->add('email', [
			'unique' => [
				'message'   => 'Email is already exists!',
				'provider'  => 'table',
				'rule'      => 'validateUnique'
			]
		]);

		/*$validator->add('password', 'passwordsEqual', [
			'rule' => function ($value, $context) {
				return
					isset($context['data']['confirmpassword']) &&
					$context['data']['confirmpassword'] === $value;
			},
			'message' => 'Password are mismatch.'
		]);*/

		return $validator;
	}
	public function GenerateActivationCode($id) {
		$activation_code	= rand($this->activation_code_min, $this->activation_code_max);
		TableRegistry::get('Members')->updateAll(
		   array(array("activation_code" => $activation_code)),
		   array("id" => $id)
		);
		return $activation_code;
	}
	public function GetCustomerByActivationCode($activation_code,$cus_id) {

		if(empty($activation_code)) return array();
			$activation_code	= $activation_code;
		$customer	= $this->find('all',array('conditions'=>array("activation_code"=>$activation_code,"id"=>$cus_id)))->toArray();
		if(!empty($customer))
			return $customer;
		return array();
	}
	public function ActivateCustomer($customer) {

		if(!empty($customer[0]['activation_code']) && !empty($customer[0]['id'])) {

			$this->updateAll(
						array("status" => $this->STATUS_ACTIVE, "activation_code" =>$customer[0]['activation_code']),
						array("id" => $customer[0]['id'])
			);
		}
		return true;
	}
	public function changePassword($customer,$activation_code) {

		if(!empty($customer['email'])) {
			$this->updateAll(
						array("password" =>Security::hash(Configure::read('Security.salt') . $activation_code)),
						array("id" => $customer['id'])
					);
		}
		return true;
	}

	public function SendSMSActivationCode($customer_id,$mobile,$activation_code)
	{
		$MESSAGE			= urlencode("Thank you for registering with ".PRODUCT_NAME.". Your activation code is ".$activation_code);
		$FIND_ARRAY			= array("[SMS_USER]","[SMS_PASS]","[MESSAGE]","[MOBILE]");
		$REPL_ARRAY			= array(SMS_USER,SMS_PASS,$MESSAGE,$mobile);
		$SMS_GATEWAY_URL 	= str_replace($FIND_ARRAY,$REPL_ARRAY,SMS_GATWAY_URL);

		$SMS_CONTENT		= $this->ApiCall($SMS_GATEWAY_URL);

		//Store SMS Response
		//$this->SmsResponse	= $this->makeModelObject("SmsResponse");
		//$this->SmsResponse->SaveSMSResponse($customer_id, $SMS_GATEWAY_URL, $SMS_CONTENT, $mobile);
	}
	public function SendSMSForgotPassword($customer_id, $mobile, $pin,$FromWelcome=false)
	{
		if ($FromWelcome) {
			$MESSAGE = urlencode("Welcome to ".PRODUCT_NAME.". Your password is ".$pin);
		} else {
			$MESSAGE = urlencode("Your ".PRODUCT_NAME." password is ".$pin);
		}
		$FIND_ARRAY			= array("[SMS_USER]","[SMS_PASS]","[MESSAGE]","[MOBILE]");
		$REPL_ARRAY			= array(SMS_USER,SMS_PASS,$MESSAGE,$mobile);
		$SMS_GATEWAY_URL 	= str_replace($FIND_ARRAY,$REPL_ARRAY,SMS_GATWAY_URL);

		$SMS_CONTENT		= $this->ApiCall($SMS_GATEWAY_URL);

		//Store SMS Response
		//$this->SmsResponse	= $this->makeModelObject("SmsResponse");
		//$this->SmsResponse->SaveSMSResponse($customer_id, $SMS_GATEWAY_URL, $SMS_CONTENT, $mobile);
	}
	public function ChangeMemberPassword($member,$NewPassword,$Debug=false)
	{
		if(!empty($member->id)) {
			if ($Debug) {
				echo Security::hash(Configure::read('Security.salt') . $NewPassword);
				die;
			}
			$this->updateAll(
						array(	"password" => Security::hash(Configure::read('Security.salt') . $NewPassword),
								"password_otp" => "",
								"modified" => $this->NOW()),
						array("id" => $member->id)
					);
			$ChangePassLogTable         		= TableRegistry::get('ChangePassLog');
			$customer_entity    				= $ChangePassLogTable->newEntity();
			$customer_entity->customer_member_id= $member->id;
			$customer_entity->user_type 		= 'member';
			$customer_entity->action 			= 'change_password';
			$customer_entity->ip_address        = $_SERVER['REMOTE_ADDR'];
			$customer_entity->browser_info 		= $_SERVER['HTTP_USER_AGENT'];
			$customer_entity->new_password 		= passencrypt($NewPassword);
			$customer_entity->created          	= $this->NOW();
			$ChangePassLogTable->save($customer_entity);
		}
		return true;
	}

	/* Validation rules for change password */
	public function validationChangePassword(Validator $validator )
	{
		$validator->add('password1', [
				'length' => [
					'rule' => ['minLength', 8],
					'message' => 'The password have to be at least 8 characters!',
				]
			])
			->add('password1',[
				'match'=>[
					'rule'=> ['compareWith','password2'],
					'message'=>'The passwords does not match!',
				]
			])
			->notEmpty('password1');
		$validator->add('password2', [
				'length' => [
					'rule' => ['minLength', 8],
					'message' => 'The password have to be at least 8 characters!',
				]
			])
			->add('password2',[
				'match'=>[
					'rule'=> ['compareWith','password1'],
					'message'=>'The passwords does not match!',
				]
			])
			->notEmpty('password2');
		return $validator;
	}

	public function findByMemberID($email="")
	{
		$Member	= $this->find('all',array('conditions'=>array("email"=>$email)))->first();
		if(!empty($Member)) {
			return $Member;
		} else {
			return false;
		}
	}

	public function findByMemberOTP($OTP="")
	{
		$Member	= $this->find('all',array('conditions'=>array("password_otp"=>$OTP)))->first();
		if(!empty($Member)) {
			return $Member;
		} else {
			return false;
		}
	}

	public function SendOTPForChangePassword($member)
	{

		$MemberPasswordEmailTo 	= TableRegistry::get('MemberPasswordEmailTo');

		$EmailRecipients 		= $MemberPasswordEmailTo->find("all",['conditions'=>['branch_id'=>$member->branch_id]])->toArray();
		
		if (empty($EmailRecipients)) {
			return false;
		} else {
			$ToEmails = array();
			foreach ($EmailRecipients as $EmailRecipient) {
				$ToEmails[] = $EmailRecipient->email;
			}
		}
		if (empty($ToEmails)) {
			return false;
		}

		$OTP 		= mt_rand(1000000,10000000);
		$to			= implode(",",$ToEmails);
		// $to 		= "kalpak@yugtia.com";
		$subject 	= PRODUCT_NAME." - MEMBER RESET PASSWORD OTP FOR LOGIN ID - ".$member->email;
		$email 		= new Email('default');
		$email->profile('default');
		$email->viewVars(array('PasswordOTP' => $OTP,'MEMBER_EMAIL'=>$member->email));
		$message_send = $email->template('send_password_otp_to_member', 'default')
			->emailFormat('html')
			->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
			->to($ToEmails)
			->subject(Configure::read('EMAIL_ENV').$subject)
			->send();
		$Emaillog        					= TableRegistry::get('Emaillog');
		$EmaillogEntity             		= $Emaillog->newEntity();
		$EmaillogEntity->email           	= $to;
		$EmaillogEntity->send_date       	= $this->NOW();
		$EmaillogEntity->action          	= Configure::read('EMAIL_ENV').$subject;
		$EmaillogEntity->description     	= json_encode(array('PasswordOTP' => $OTP,'MEMBER_EMAIL'=>$member->email));
		$Emaillog->save($EmaillogEntity);
		$this->SavePasswordOTPLog($member,$OTP);

		$EmailIDs = (is_array($ToEmails)?implode(", ",$ToEmails):$ToEmails);

		return $EmailIDs;
	}

	public function SavePasswordOTPLog($member,$PasswordOTP)
	{
		if(!empty($member->id)) {
			$this->updateAll(array("password_otp" =>$PasswordOTP),array("id" => $member->id));
			$ChangePassLogTable         		= TableRegistry::get('ChangePassLog');
			$customer_entity    				= $ChangePassLogTable->newEntity();
			$customer_entity->customer_member_id= $member->id;
			$customer_entity->user_type 		= 'member';
			$customer_entity->action 			= 'send_password_otp';
			$customer_entity->ip_address        = $_SERVER['REMOTE_ADDR'];
			$customer_entity->browser_info 		= $_SERVER['HTTP_USER_AGENT'];
			$customer_entity->new_password 		= passencrypt($PasswordOTP);
			$customer_entity->created          	= $this->NOW();
			$ChangePassLogTable->save($customer_entity);
		}
		return true;
	}
}
?>