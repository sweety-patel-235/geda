<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\ORM\Table;

use Cake\Core\Configure;
use Cake\Network\Email\Email;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Controller\Component;
use Cake\Utility\Security;

use Cake\Event\Event;
use Cake\View\Helper\PaginatorHelper;
use Cake\Validation\Validator;

class memberController extends AppController
{	
	public $user_department = array();
	public $arrDefaultAdminUserRights = array(); 
	public $helpers = array('Time','Html','Form','ExPaginator');
	public $PAGE_NAME = '';
	
	private function SetVariables($post_variables) {
		if(isset($post_variables['mobile']))
			$this->request->data['Members']['mobile']	= $post_variables['mobile'];
		if(isset($post_variables['email']))
			$this->request->data['Members']['email']		= $post_variables['email'];
		if(isset($post_variables['name']))
			$this->request->data['Members']['name']		= $post_variables['name'];
		if(isset($post_variables['pass']))
			$this->request->data['Members']['password']	= $post_variables['pass'];
		if(isset($post_variables['token']))
			$this->ApiToken->token	= $post_variables['token'];
	}
	
	/*
	 * initialize controller
	 *
	 * @return void
	 */
	public function initialize()
    {
        // Always enable the CSRF component.
		parent::initialize();
		$this->loadComponent('Paginator');

		$this->loadModel('Users');
		$this->loadModel('Userrole');
		$this->loadModel('Userroleright');
		$this->loadModel('Adminaction');
		$this->loadModel('Members');
		$this->loadModel('BranchMasters');
		$this->loadModel('Sessions');
		$this->loadModel('Department');
		$this->loadModel('Parameters');
		$this->loadModel('UserDepartment');
		$this->loadModel('Admintrntype');
		$this->loadModel('Admintrnmodule');
		$this->loadModel('ApiToken');
		$this->loadModel('SmsResponse');
		$this->loadModel('Installers');
		$this->loadModel('States');
		$this->set('Userright',$this->Userright);
    }
	/*
	 * Displays a index
	 *
	 * @param mixed What page to display
	 * @return void
	 */
	public function index() {
		
		$this->intCurAdminUserRight = $this->Userright->LIST_MEMBER;
		$this->setAdminArea();
		
		if (!empty($this->Members->validate)) {
			foreach ($this->Members->validate as $field => $rules) {
				$this->Members->validator()->remove($field); //Remove all validation in search page
			}
		}
		
		$arrcustomerList	= array();
		$arrCondition		= array();
		$this->SortBy		= "Members.id";
		$this->Direction	= "ASC";
		$this->intLimit		= PAGE_RECORD_LIMIT;
		$this->CurrentPage  = 1;
		$option 			= array();
		$option['colName']  = array('id','name','email','mobile','last_login_date','created','action');
		
		$this->SetSortingVars('Members',$option);
		$arrCondition		= $this->_generateCustomerSearchCondition();
		
		$this->paginate		= array('conditions' => $arrCondition,
									'fields' => array('Members.id','Members.name','Members.email','Members.mobile','Members.status','Members.last_login_date','Members.created'),
									'order'=>array($this->SortBy => $this->Direction),
									'page'=> $this->CurrentPage,
									'limit' => $this->intLimit);
		$arrcustomerList	= $this->paginate('Members');
		$arrUserType['']	= "Select";
		$usertypes = array();

		$option['dt_selector']	='table-example';
		$option['formId']		='formmain';
		$option['url']			= WEB_ADMIN_PREFIX.'member';
		$JqdTablescr 			= $this->JqdTable->create($option);
		$this->set('arrcustomerList',$arrcustomerList->toArray());
		$this->set('JqdTablescr',$JqdTablescr);
		$this->set('arrUserType',$arrUserType);
		$this->set('period',$this->period);
		$this->set('limit',$this->intLimit);
		$this->set("CurrentPage",$this->CurrentPage);
		$this->set("SortBy",$this->SortBy);
		$this->set("Direction",$this->Direction);
		$this->set("page_count",(isset($this->request->params['paging']['Members']['pageCount'])?$this->request->params['paging']['Members']['pageCount']:0));
		$out = array();
		
		/*$blnEditAdminuserRights		= $this->Userright->checkadminrights($this->Userright->ANALYSTS_EDIT);
		$blnEnableAdminuserRights	= $this->Userright->checkadminrights($this->Userright->ANALYSTS_ENABLE);	
		$blnDisableAdminuserRights	= $this->Userright->checkadminrights($this->Userright->ANALYSTS_DISABLE);*/
		//pr($arrcustomerList->toArray()); exit;
		$blnEditMembersRights		= $this->Userright->checkadminrights($this->Userright->EDIT_MEMBER);
		foreach($arrcustomerList->toArray() as $key => $val) {
			$temparr = array();
			foreach($option['colName'] as $key) {
				if(isset($val[$key])) {
					if($key == 'last_login_date' || $key == 'created')
						$temparr[$key] = date("d-m-Y H:i:s",strtotime($val[$key])); 
					else
						$temparr[$key] = $val[$key];
				}else{
					$temparr[$key]='';
				}
				if($key == 'action') {
					if($key=='action') {
						$temparr['action']='';
						if($blnEditMembersRights){
							$temparr['action'].= $this->Userright->linkEditMember(constant('WEB_URL').constant('ADMIN_PATH').'member/manage/'.encode($val['id']),'<i class="fa fa-edit"></i>','','actionRecord',' title="Edit Member"')."&nbsp;";
						}
						if($blnEditMembersRights){
							if(empty($val['status'])) {
								$temparr['action'].= $this->Userright->linkEnableAdminuser(constant('WEB_URL').constant('ADMIN_PATH').'member/enable/'.encode($val['id']),'<i class="fa fa-check-circle-o"></i>','','actionRecord',' title="Activate"')."&nbsp;";
							}else if(!empty($val['status'])) {
								$temparr['action'].= $this->Userright->linkDisableAdminuser(constant('WEB_URL').constant('ADMIN_PATH').'member/disable/'.encode($val['id']),'<i class="fa fa-circle-o"></i>','','actionRecord',' title="In-Activate"')."&nbsp;";
							}
							//$temparr['action'].= $this->Userright->linkListProjects(constant('WEB_URL').constant('ADMIN_PATH').'member/index/'.encode($val['id']),'<i class="fa fa-eye"></i>','',' target="_blank" title = "View Projects"')."&nbsp;";
						}
					}
				}		
			}
			$out[] = $temparr;
		}
		if ($this->request->is('ajax')){
			header('Content-type: application/json');
			echo json_encode(array('condi'=>$arrCondition,"draw" => intval($this->request->data['draw']),
			"recordsTotal"    => intval( $this->request->params['paging']['Members']['count']),
			"recordsFiltered" => intval( $this->request->params['paging']['Members']['count']),
			"data"            => $out));
			die;
		}
	}

	/**
	 *
	 * _generateCustomerSearchCondition
	 *
	 * Behaviour : Private
	 *
	 * @param : $id  : Id is use to identify for which user condition to be generated if its not null
	 * @defination : Method is use to generate search condition using which admin user data can be listed
	 *
	 */
	private function _generateCustomerSearchCondition($id=null)
	{
		$arrCondition	= array();
		$blnSinCompany	= true;
		if(!empty($id)) $this->request->data['Members']['id'] = $id;
		if(count($this->request->data)==0) $this->request->data['Members']['status'] = $this->Members->STATUS_ACTIVE;
		if(isset($this->request->data) && count($this->request->data)>0)
        {
            if(isset($this->request->data['Members']['id']) && trim($this->request->data['Members']['id'])!='')
            {
                $strID = trim($this->request->data['Members']['id'],',');
                $arrCondition['Members.id'] = $this->request->data['Members']['id'];/* array_unique(explode(',',$strID));*/
            }

			if(isset($this->request->data['Members']['status']) && !empty($this->request->data['Members']['status']))
            {
                $status = $this->request->data['Members']['status'];
				if($this->request->data['Members']['status']=='I') $status = $this->Members->STATUS_INACTIVE;
				$arrCondition['Members.status'] = $status;
            }

			if(isset($this->request->data['Members']['username']) && $this->request->data['Members']['username']!='')
            {
                $arrCondition['Members.username LIKE'] = '%'.$this->request->data['Members']['username'].'%';
            }

			if(isset($this->request->data['Members']['email']) && $this->request->data['Members']['email']!='')
            {
                $arrCondition['Members.email LIKE'] = '%'.$this->request->data['Members']['email'].'%';
            }

			if(isset($this->request->data['Members']['mobile']) && $this->request->data['Members']['mobile']!='')
            {
                $arrCondition['Members.mobile LIKE'] = '%'.$this->request->data['Members']['mobile'].'%';
            }
			if(isset($this->request->data['Members']['city']) && $this->request->data['Members']['city']!='')
            {
                $arrCondition['Members.city LIKE'] = '%'.$this->request->data['Members']['city'].'%';
            }
			if(isset($this->request->data['Members']['designation']) && $this->request->data['Members']['designation']!='')
            {
                $arrCondition['Members.designation LIKE'] = '%'.$this->request->data['Members']['designation'].'%';
            }

			if(isset($this->request->data['Members']['usertype']) && $this->request->data['Members']['usertype']!='')
            {
                $arrCondition['Members.usertype'] = $this->request->data['Members']['usertype'];
            }
			if(isset($this->request->data['Members']['name']) && $this->request->data['Members']['name']!='')
            {
                $arrCondition['Members.name LIKE'] = '%'.$this->request->data['Members']['name'].'%';
            }
			if(isset($this->request->data['Members']['search_date']) && $this->request->data['Members']['search_date']!='')
            {
                if($this->request->data['Members']['search_period'] == 1 || $this->request->data['Members']['search_period'] == 2)
                {
                	$arrSearchPara	= $this->Members->setSearchDateParameter($this->request->data['Members']['search_period'],$this->modelClass);
                	
                	$this->request->data[$this->modelClass] = array_merge($this->request->data[$this->modelClass],$arrSearchPara[$this->modelClass]);
                    $this->dateDisabled						= true;
                }
                $arrperiodcondi = $this->Members->findConditionByPeriod( $this->request->data['Members']['search_date'],
																		$this->request->data['Members']['search_period'],
																		$this->request->data['Members']['DateFrom'],
																		$this->request->data['Members']['DateTo'],
																		$this->Session->read('Members.timezone'));
               	if(!empty($arrperiodcondi)){
                	$arrCondition['between'] = $arrperiodcondi['between'];
                }
            }
		}
		return $arrCondition;
	}

	
    /**
     *
     * admin_disable
     *
     * Behaviour : Public
     *
     * @param : $id   : Id is use to identify particular admin whoes account is to be disabled
     * @defination : Method is use to disable particular User who profile is active
     *
     */
	function disable($id=null) {
		$this->initAdminRightHelper();
		$this->intCurAdminUserRight = $this->Userright->EDIT_CUSTOMER;
		$id = intval(decode($id));
		$this->setAdminArea();

		if(((int)$id)==0) $this->redirect('index');
		if($this->Members->updateAll(['status' => 0], ['id' => $id]))
		{
			$this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->INACTIVE_MEMBER,$id,'Inactivated Customer id :: '.$id);
			$this->Flash->set('Customer has been In-Activated.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
			return $this->redirect(array('action'=>'index'));
			exit;
		}
		else
		{
			$this->Flash->set('Customer In-Activation Error! Contact Administrator.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'error']]);
		}
	}
	/**
	 *
	 * admin_enable
	 *
	 * Behaviour : Public
	 *
	 * @param : $id   : Id is use to identify admin whoes profile is to be activate
	 * @defination : Method is use to enabled the admin profile who is disabled
	 *
	 */
	function enable($id=null) {
		$this->initAdminRightHelper();
		$this->intCurAdminUserRight = $this->Userright->EDIT_CUSTOMER;
		$id = intval(decode($id));
		$this->setAdminArea();

		if(((int)$id)==0) $this->redirect('index');
		if($this->Members->updateAll(['status' => 1], ['id' => $id]))
		{
			$this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->ACTIVE_MEMBER,$id,'Activated Customer id :: '.$id);
			$this->Flash->set('Customer has been Activated.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
			return $this->redirect(array('action'=>'index'));
			exit;
		}
		else
		{
			$this->Flash->set('Customer Activation Error! Contact Administrator.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'error']]);
			exit;
		}
	}
	/**
	 *
	 * admin_add
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to add new admin user and provide specific righs using admin interface
	 *
	 */
	public function manage($id = '')
	{
		if(!empty($id)) {
			$this->intCurAdminUserRight = $this->Userright->EDIT_MEMBER;
			$this->setAdminArea();
			$id = decode($id);
			$MembersEntityGet = $this->Members->get($id);
			$MembersEntity = $this->Members->patchEntity($MembersEntityGet,$this->request->data() ,['validate' => 'edit']);
			$mode = 'Edit';
		} else {
			$this->intCurAdminUserRight = $this->Userright->ADD_MEMBER;
			$this->setAdminArea();
			$MembersEntity = $this->Members->newEntity($this->request->data() ,['validate' => 'add']);
			$mode = 'Add';
		}

		$arrAdminDefaultRights = array();
		$timezone = '';
		$arrError = array();

		if(!empty($this->request->data)) {
			if(!$MembersEntity->errors()) {
				if($MembersEntity->member_type != $this->Members->member_type_discom) {//user type DisCom
					$MembersEntity->branch_id = 0;
				}
				if (empty($id)) {
					$MembersEntity->created 	= $this->NOW();
					$MembersEntity->created_by 	= $this->Session->read('User.id');
					$MembersEntity->status 		= $this->Members->STATUS_ACTIVE;
				} else {
					$MembersEntity->updated 	= $this->NOW();
					$MembersEntity->updated_by 	= $this->Session->read('User.id');
				}
				//$newUsers =  $this->Members->newEntity($this->request->data['Members']);
				if(empty($MembersEntity->password)){
					unset($MembersEntity['password']);
				}

				if($this->Members->save($MembersEntity)) {
					if(!empty($id)) {
						$this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->EDIT_MEMBER,$MembersEntity->id,'Added Members user id::'.$MembersEntity->id);
						$this->Flash->set('Members has been updated.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
					} else {
						$this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->ADD_MEMBER,$MembersEntity->id,'Added Members user id::'.$MembersEntity->id);
						$this->Flash->set('Members has been saved.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
					}

					return $this->redirect(WEB_ADMIN_PREFIX.'/member');
				}
			}
		} else {
			unset($MembersEntity->password);
		}

		$ADMINUSER_RETAILERS = array();
		$MemberType = $this->Parameters->GetParameterList(6);
		$State_list = $this->States->find("list",['keyField'=>'id','valueField'=>'statename']);
		$branch_list = $this->BranchMasters->find("list",['keyField'=>'id','valueField'=>'title']);

		$this->set('Members',$MembersEntity);
		$this->set('member_type_discom',$this->Members->member_type_discom);
		$this->set('State_list',$State_list);
		$this->set('branch_list',$branch_list);
		$this->set('MemberType',$MemberType);
		$this->set('mode',$mode);
		$this->set('emailrights',array());
		$this->set('department',$this->Department->GetDepartmentList());
		$this->set('data',$this->request->data);
		$this->set('timezone',$timezone);
		$this->set('DEFAULT_USER_TIMEZONE', $this->Users->DEFAULT_USER_TIMEZONE);
		$this->set('ADMINUSER_RETAILERS', $ADMINUSER_RETAILERS);
		$this->set('arrError', $arrError);
	}
	/**
	 *
	 * SendActivationCodeToCustomer
	 *
	 * Behaviour : private
	 *
	 * @defination : Method is use to add new admin user and provide specific righs using admin interface
	 *
	 */
	private function SendActivationCodeToCustomer($customer_id,$activation_code, $email, $mobile, $blnEmail=true)
	{
		if (!empty($mobile) && SEND_SMS) {
			//Send sms to customer
			$this->Members->SendSMSActivationCode($customer_id,$mobile,$activation_code);
		}
		if(!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) && $blnEmail && SEND_EMAIL) {	
			//Send email to customer.
			$Email = new Email('default');
			$Email->profile('default');
			$Email->viewVars(array('activation_code' => $activation_code));
			$Email->template('send_activation_code', 'empty')
				->emailFormat('text')
				->subject(Configure::read('EMAIL_ENV').PRODUCT_NAME.' Activation Code')
				->to($email)
				->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
				->send();
		}
	}
	private function SendWellComeEmailToCustomer($customer_name, $email)
	{
		$email="pravin.sanghani@yugtia.com";
		if(!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) && SEND_EMAIL) {	
			//Send email to customer.
			$Email = new Email('default');
			$Email->profile('default');
			$Email->viewVars(array('customer_name' => $customer_name));
			$Email->template('send_well_come_email', 'default')
				->emailFormat('html')
				->subject(Configure::read('EMAIL_ENV')."Wellcome to ".PRODUCT_NAME)
				->to($email)
				->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
				->send();
		}
	}
	/**
	 *
	 * login
	 *
	 * Behaviour : public
	 *
	 * @defination : Method is use to add new admin user and provide specific righs using admin interface
	 *
	 */
	public function login()
	{
		$this->autoRender = false;
		$this->SetVariables($this->request->data);
		if (!empty($this->request->data)) {
			$customersEntity = $this->Members->newEntity($this->request->data,['validate' => 'login']);
			$conditions = array(
					"Members.email" => (isset($this->request->data['Members']['email'])?$this->request->data['email']:''),
					"Members.password" =>  Security::hash(Configure::read('Security.salt') . (isset($this->request->data['Members']['password'])?$this->request->data['Members']['password']:'')));
			
			$error='';
			$customersEntity = $this->Members->find('all',array('conditions' => $conditions/*, 'fields' => $fields*/))->toArray();
			if(empty($customersEntity[0]['id'])) {
				$status				= 'error';
				$error				= 'Please check user detail';
			} else { 
				/* Check customer is installer or not */
				$installercnt	= $this->Installers->find('all', array('conditions'=>array('customer_id'=>$customersEntity[0]['id'])))->count();
				$is_installer 	= ($installercnt>0)?'true':'false';
				/* Update Last Login Date */
				$custData 			= $this->Members->get($customersEntity[0]['id']);
				$custPatchEntity 	= $this->Members->patchEntity($custData,$this->request->data());
				$custPatchEntity->last_login_date = $this->NOW();
				$this->Members->save($custPatchEntity);
				/* Send OTP for in active customer */
				if(isset($customersEntity[0]['status']) &&  $customersEntity[0]['status'] == $this->Members->STATUS_INACTIVE) {
					$activation_code = $this->Members->GenerateActivationCode($customersEntity[0]['id']);
					$this->SendActivationCodeToCustomer($customersEntity[0]['id'],$activation_code, '', $customersEntity[0]['mobile']);
				}
				/* Send OTP for in active customer */
				$status				= 'ok';
				$this->ApiToken->LoggedInAPIUser($this->ApiToken->token, $customersEntity[0]['id']);
				$this->ApiToken->SetAPIResponse('cus_id', $customersEntity[0]['id']);
				$this->ApiToken->SetAPIResponse('active_status', $customersEntity[0]['status']);
				$this->ApiToken->SetAPIResponse('is_installer', $is_installer);
			}				
		} else {
			$status				= 'error';
			$error				= 'Please check user detail';
		}
		$this->ApiToken->SetAPIResponse('msg', $error);
		$this->ApiToken->SetAPIResponse('type', $status);
		echo $this->ApiToken->GenerateAPIResponse();
		exit;	
	}
	public function otpactivate()
	{
		$this->autoRender 	= false;	
		$activation_code	= $this->request->data('otp');
		$cus_id				= $this->ApiToken->customer_id;
		$this->SetVariables($this->request->data);
		$customer			= $this->Members->GetCustomerByActivationCode($activation_code,$cus_id);
		if(!empty($customer) && $customer[0]['activation_code'] == $activation_code) {
			if($this->Members->ActivateCustomer($customer)) {
				$this->SendWellComeEmailToCustomer($customer[0]['name'],$customer[0]['email']);
				$this->ApiToken->SetAPIResponse('type', 'ok');
				$this->ApiToken->SetAPIResponse('msg', 'User activated successfully.');
			} else {
				$this->ApiToken->SetAPIResponse('type', 'error');
				$this->ApiToken->SetAPIResponse('msg', 'Something went wrong in activation.');
			}
		} else {
			$this->response->statusCode(403);
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'Invalid activation code.');

		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	public function forgotpass() {
		
		$this->autoRender = false;	
		$email		= $this->request->data('email');
		$customer	= $this->Members->find('all', array('conditions'=>array('email'=>$email)))->toArray();
		$customer 	= (isset($customer[0]))?$customer[0]:'';
		if(empty($customer)) {
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'User not found.');
		} else {
			$activation_code = $this->Members->GenerateActivationCode($customer['id']);
			$this->SendActivationCodeToCustomer($customer['id'],$activation_code, $customer['email'], $customer['mobile']);
			$this->ApiToken->LoggedInAPIUser($this->ApiToken->token, $customer['id']);
			$this->ApiToken->SetAPIResponse('type', 'ok');
			$this->ApiToken->SetAPIResponse('msg', 'You will receive new OTP shortly.');
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	public function token() {
		
		$this->autoRender = false;	
			
		$token 		= $this->ApiToken->GenerateNewToken();
		$version 	= $this->request->data('version');
		if($version == "" )
		{
			$version = '2.1';
			$this->ApiToken->SetAPIResponse('flag', '0');
			$this->ApiToken->SetAPIResponse('msg', 'Sorry for inconvenience, Server is under maintenance. Please try afer sometime');
			
		}else if($version != CURRENT_VERSION && !in_array($version,explode(',',OLD_VERSION))) {
			$this->ApiToken->SetAPIResponse('flag', '0');
			$this->ApiToken->SetAPIResponse('msg', 'Please update to latest version.');
		} else {
			$this->ApiToken->SetAPIResponse('flag', '1');
			$this->ApiToken->SetAPIResponse('msg', '');
		}
		$this->ApiToken->SetAPIResponse('type', 'ok');
		$this->ApiToken->SetAPIResponse('token', $token);
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	private function SendForgotPasswordEmailToCustomer($customer_id, $email, $mobile,$pin)
	{
		if (!empty($customer_id) && SEND_SMS) {
			//Send sms to customer
			$this->Members->SendSMSForgotPassword($customer_id, $mobile, $pin);
		}
		if(!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) && SEND_EMAIL) {
			//Send email to customer.
			$Email = new Email('default');
			$Email->profile('default');
			$Email->viewVars(array('customer_id' => $customer_id, 'pin' => $pin));
			$Email->template('forgot_password', 'empty')
				->emailFormat('text')
				->subject(Configure::read('EMAIL_ENV').PRODUCT_NAME.' New Generated Password')
				->to($email)
				->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
				->send();
		}
	}
	
	/**
	*
	* changeloginpassword
	*
	* Behaviour : public
	*
	* @defination : Method is use to change the login password.
	*
	*/
	public function changeloginpassword() {
		
		$this->autoRender = false;
		$this->SetVariables($this->request->data);	
		$oldpassword	= $this->request->data('pass');
		$new_password	= $this->request->data('new_pass');
		$cus_id			= $this->ApiToken->customer_id;

		$custData 		= $this->Members->get($cus_id);
		$old_password 	= Security::hash(Configure::read('Security.salt') . $oldpassword);
		
		if(!empty($custData)) {
			if(isset($oldpassword) && !empty($oldpassword) && !empty($new_password)) {
				if($old_password == $custData['password']) { 
					$custPatchEntity = $this->Members->patchEntity($custData, $this->request->data);
					$custPatchEntity->password = $new_password;
					$this->Members->save($custPatchEntity);

					$this->ApiToken->SetAPIResponse('type', 'ok');
					$this->ApiToken->SetAPIResponse('msg', 'Password changed successfully.');
				} else {
					$this->ApiToken->SetAPIResponse('type', 'error');
					$this->ApiToken->SetAPIResponse('msg', 'Current password does not match.');
				}
			} elseif(empty($oldpassword) && !empty($new_password)) {
				$custPatchEntity = $this->Members->patchEntity($custData, $this->request->data);
				$custPatchEntity->password 	= $new_password;
				$this->Members->save($custPatchEntity);

				/* Check customer is installer or not */
				$installercnt	= $this->Installers->find('all', array('conditions'=>array('customer_id'=>$cus_id)))->count();
				$is_installer 	= ($installercnt>0)?'true':'false';
				
				$this->ApiToken->SetAPIResponse('type', 'ok');
				$this->ApiToken->SetAPIResponse('msg', 'Password changed successfully.');
				$this->ApiToken->SetAPIResponse('is_installer', $is_installer);
			}
		} else{
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'User not found.');
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
}