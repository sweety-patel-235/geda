<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

use Cake\ORM\TableRegistry;
use Cake\ORM\Table;

use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Controller\Component;
use Cake\Utility\Security;

use Cake\Event\Event;

use Cake\View\Helper\PaginatorHelper;
use Cake\Validation\Validator;

class UsersController extends AppController 
{	
	/**
	 *
	 * The status of $name is universe
	 *
	 * Potential value is name of class
	 *
	 * @public string
	 *
	 */
	
    public $user_department = array();

    /**
	 *
	 * The status of $FLAG is universe
	 *
	 * Potential value is name of class
	 *
	 * @public string
	 *
	 */
	
    public $FLAG = 1;
	
    /**
     *
     * The status of $STATUS_ACTIVE is universe
     *
     * Potential value is 1, which indicate the Status is active
     *
     * @public int
     *
     */
	public $STATUS_ACTIVE = 1;
	/**
	 *
	 * The status of $STATUS_INACTIVE is universe
	 *
	 * Potential value is 0 which indicate that status in inactive
	 *
	 * @public int
	 *
	 */
	
	public $arrDefaultAdminUserRights = array(); 
	var $helpers = array('Time','Html','Form','ExPaginator');
	
	public $STATUS_INACTIVE 			= 0;
	public $PAGE_NAME 					= '';
	
	/* model object Variable */
	
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
		$this->loadComponent('Image');

		$this->loadModel('Users');
		$this->loadModel('Userrole');
		$this->loadModel('Userroleright');
		$this->loadModel('Adminaction');
		$this->loadModel('Department');
		$this->loadModel('UserDepartment');
		$this->loadModel('Admintrntype');
		$this->loadModel('Admintrnmodule');

		$this->set('Userright',$this->Userright);
    }
	/*
	 * Displays a index
	 *
	 * @param mixed What page to display
	 * @return void
	 */
	public function index() {
		
		$this->intCurAdminUserRight = $this->Userright->ANALYSTS_LIST;
		$this->setAdminArea();
		
		if (!empty($this->User->validate)) {
			foreach ($this->User->validate as $field => $rules) {
				$this->User->validator()->remove($field); //Remove all validation in search page
			}
		}
		/*$this->User->bindModel(
			array('belongsTo' => array(
					'TimeZone' => array(
						'className' => 'TimeZone',
						'foreignKey' => 'timezone',
					)
				)
			)
		);*/
		$arrAdminuserList	= array();
		$arrUserType		= array();
		$arrCondition		= array();
		$this->SortBy		= "Users.id";
		$this->Direction	= "ASC";
		$this->intLimit		= 10;
		$this->CurrentPage  = 1;/*((isset($this->request->data['start']) ? $this->request->data['start'] : '10')/$this->intLimit)+1*/
		$option=array();
		$option['colName']  = array('id','username','firstname','lastname','city','email','mobile','action');
		
		$this->SetSortingVars('Users',$option);

		$arrCondition		= $this->_generateAdminuserSearchCondition();
		$this->paginate		= array('conditions' => $arrCondition,
									'order'=>array($this->SortBy=>$this->Direction),
									'page'=> $this->CurrentPage,
									'limit' => $this->intLimit);
		$arrAdminuserList	= $this->paginate('Users');
		$arrUserType['']	= "Select";
		
		$usertypes = $this->Userrole->getAdminuserRoles();
		foreach($usertypes as $key=>$value) $arrUserType[$key] = $value;

		$option['dt_selector']	='table-example';
		$option['formId']		='index-formmain';
		$option['url']			= WEB_ADMIN_PREFIX.'users';
		
		$JqdTablescr = $this->JqdTable->create($option);
		$this->set('arrAdminuserList',$arrAdminuserList->toArray());
		$this->set('JqdTablescr',$JqdTablescr);
		$this->set('arrUserType',$arrUserType);
		$this->set('period',$this->period);
		$this->set('limit',$this->intLimit);
		$this->set("CurrentPage",$this->CurrentPage);
		$this->set("SortBy",$this->SortBy);
		$this->set("Direction",$this->Direction);
		$this->set("page_count",(isset($this->request->params['paging']['Users']['pageCount'])?$this->request->params['paging']['Users']['pageCount']:0));
		$out=array();
		$blnEditAdminuserRights		= $this->Userright->checkadminrights($this->Userright->ANALYSTS_EDIT);
		$blnEnableAdminuserRights	= $this->Userright->checkadminrights($this->Userright->ANALYSTS_ENABLE);	
		$blnDisableAdminuserRights	= $this->Userright->checkadminrights($this->Userright->ANALYSTS_DISABLE);		

		foreach($arrAdminuserList->toArray() as $key=>$val) {
			$temparr=array();
			foreach($option['colName'] as $key) {
				if(isset($val[$key])) {
					$temparr[$key]=$val[$key];
				}elseif(isset($val[$key]))
				{
					$temparr[$key]=$val[$key];
				}
				if($key=='action') {
					$temparr['action']='';
					if($blnEditAdminuserRights)
						$temparr['action'].= $this->Userright->linkEditAdminuser(constant('WEB_URL').constant('ADMIN_PATH').'users/edit/'.encode($val['id']),'<i class="fa fa-edit"> </i>','','editRecord',' title="Edit User Info"')."&nbsp;";
						$temparr['action'].= $this->Userright->linkChangeUserPassword(constant('WEB_URL').constant('ADMIN_PATH').'users/change_password/'.encode($val['id']),'<i class="fa fa-key"> </i>','','changepassword',' title="Change Password"')."&nbsp;";
					if($blnEnableAdminuserRights && empty($val['status']))
						$temparr['action'].= $this->Userright->linkEnableAdminuser(constant('WEB_URL').constant('ADMIN_PATH').'users/enable/'.encode($val['id']),'<i class="fa fa-check-circle-o"></i>','','actionRecord',' title="Activate"')."&nbsp;";
					if($blnDisableAdminuserRights && !empty($val['status']))
						$temparr['action'].= $this->Userright->linkDisableAdminuser(constant('WEB_URL').constant('ADMIN_PATH').'users/disable/'.encode($val['id']),'<i class="fa fa-circle-o"></i>','','actionRecord',' title="De-Activate"')."&nbsp;";
				}		
			}
			$out[]=$temparr;
		}
		if ($this->request->is('ajax')){
			header('Content-type: application/json');
			echo json_encode(array(	"draw" 			  => intval($this->request->data['draw']),
									"recordsTotal"    => intval($this->request->params['paging']['Users']['count']),
									"recordsFiltered" => intval($this->request->params['paging']['Users']['count']),
									"data"            => $out));
			die;
		}
	}

	/**
	 * Displays a login page
	 *
	 * @param mixed What page to display
	 * @return void
	 */
	public function login() {
		
		$this->layout = "login";
		$this->setAdminArea(false);
		
		$redirect = (isset($_GET["redirect"]))?$_GET["redirect"]:"";
		if (!empty($this->request->data)) {
			$UsersEntity = $this->Users->newEntity($this->request->data['Login'],['validate' => 'login']);	
			$this->set('Login',$UsersEntity);
			if (!$UsersEntity->errors()) {
				$this->checklogin();
			}
		} else { 
			$UsersEntity = $this->Users->newEntity($this->request->data,['validate' => 'login']);
			$this->set('Login',$UsersEntity);
			$this->set('cutom_admin','');
		}
		$redirect = WEB_ADMIN_PREFIX."/dashboard";
		$this->set("redirect",$redirect);
	}



	/**
	 * Displays a view
	 *
	 * @param mixed What page to display
	 * @return void
	 */
	public function admin_home() {
		$this->initAdminRightHelper();
		$this->intCurAdminUserRight = $this->Userright->ADMIN_HOME;
		$this->setAdminArea();
	}

	/**
	 *
	 * checklogin
	 *
	 * Behaviour : Private
	 *
	 * @defination : Method is use to check and validate user detail at time of login if succesfull redirect to home page else through to login page with approriate message
	 *
	 */
	private function checklogin()
	{	
		$conditions = array(
					"Users.username" => (isset($this->request->data['Login']['LoginUsername'])?$this->request->data['Login']['LoginUsername']:''),
					"Users.password" =>  Security::hash(Configure::read('Security.salt') . (isset($this->request->data['Login']['LoginPassword'])?$this->request->data['Login']['LoginPassword']:'')));
		//$fields = array('Users.*');
		$query = $this->Users->find('all',array('conditions' => $conditions/*, 'fields' => $fields*/));
		$first_admin_data = $query->first();
		if(!($admin = $first_admin_data)) {
			$this->Flash->set('Username or password are invalid.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'warning']]);
		}elseif($admin['status']!=1){
			$this->Flash->set('Your account has been suspended.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'error']]);
		}else{
			$arrAdminRoleRights = $this->Userroleright->getAllAdminUserRoleRight($admin->usertype);
			$arrUserRights = unserialize($admin->userrights);
			
			if(!empty($arrUserRights))
				$arrUserRights = array_merge($arrUserRights, $arrAdminRoleRights);
			else
				$arrUserRights = $arrUserRights;
			
			$user_data = $this->Users->get($admin['id']);
			
			$this->Users->updateAll(['lastlogin' => $this->NOW()], ['id' => $admin['id']]);
			$displayname = (!empty($admin['firstname']) && !empty($admin['lastname'])?$admin['firstname']." ".$admin['lastname']:$admin['username']);
			
			$this->Session->write('User.id',$admin['id']);
			$this->Session->write('User.usertype',$admin['usertype']);
			$this->Session->write('User.username',$admin['username']);
			$this->Session->write('User.displayname',$displayname);
			$this->Session->write('User.timezone',$admin['timezone']);
			$this->Session->write('User.profile_pic',$admin['profile_pic']);
			$this->Session->write('User.location',$admin['city']);
			$timezone	= '';
			//$timezone 						= $this->TimeZone->getTimeZoneById($admin['User']['timezone']);
			$time_zone	= str_replace("UTC","",(empty($time_zone)?"UTC":$time_zone));
			$time_zone	= empty($time_zone)?"UTC":$time_zone;
			//$this->Session->write('User.timezone_iana',$timezone['TimeZone']['iana_timezone_id']);
			$this->Session->write('User.time_zone',$time_zone);
			$this->Session->write('User.isDst',(isset($admin['is_dst'])?$admin['is_dst']:"true"));
			$arradmintransaction = $this->Users->GenerateAdminuserRightSession($this->Session->read('User.id'),$this->Session->write('User.name', $admin['firstname'] . " " . $admin['lastname']));
			
			if(empty($arradmintransaction)){
				$msg = "You have no any role writes.Please contact to you admin";
				$this->Flash->set($msg,['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'warning']]);
				return $this->redirect(WEB_ADMIN_PREFIX.'/users/login');
			}
			$this->Session->write('User.userrights', $arradmintransaction);
			$this->writeadminlog($admin['id'],$this->Adminaction->ADMIN_LOGIN,'login','Login to Admin');
			if(isset($this->request->data['redirect'])
				&& !empty($this->request->data['redirect'])
				&& !preg_match("|^http(s)?://".REFERER_HOST."|i",$this->request->data['redirect']) == false) {
				return $this->redirect(WEB_ADMIN_PREFIX.'/dashboard');
			} else {
				return $this->redirect(WEB_ADMIN_PREFIX.'/dashboard');
			}
			exit();
		}
	}

	/**
	 *
	 * admin_logout
	 *
	 * Behaviour : Public
	 *
	 * @defination : Use to logout and throw user to login page after distroying all the logged in session
	 *
	 */
	function logout() 
	{
		$this->Session->destroy();
		$intAdminuserid = $this->Session->read('User.id');
		if(!empty($intAdminuserid)) $this->writeadminlog($this->Session->read('User.id'),$this->Igadminaction->ADMIN_LOGOUT,'logout','Logout from Admin');
        $this->Session->destroy();
		
		$this->Cookie->delete('AU');
		$msg = "You have successfully logged out."; 
		$this->Flash->set($msg,['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
		return $this->redirect(WEB_ADMIN_PREFIX.'/users/login');
		exit();
	}

	/**
	 *
	 * _generateAdminuserSearchCondition
	 *
	 * Behaviour : Private
	 *
	 * @param : $id  : Id is use to identify for which user condition to be generated if its not null
	 * @defination : Method is use to generate search condition using which admin user data can be listed
	 *
	 */
	private function _generateAdminuserSearchCondition($id=null)
	{
		$arrCondition	= array();
		$blnSinCompany	= true;
		if(!empty($id)) $this->request->data['Users']['id'] = $id;
		if(count($this->request->data)==0) $this->request->data['Users']['status'] = $this->STATUS_ACTIVE;
		if(isset($this->request->data) && count($this->request->data)>0)
        {
            if(isset($this->request->data['Users']['id']) && trim($this->request->data['Users']['id'])!='')
            {
                $strID = trim($this->request->data['Users']['id'],',');
                $arrCondition['Users.id'] = $this->request->data['Users']['id'];/* array_unique(explode(',',$strID));*/
            }

			if(isset($this->request->data['Users']['status']) && !empty($this->request->data['Users']['status']))
            {
                $status = $this->request->data['Users']['status'];
				if($this->request->data['Users']['status']=='I') $status = $this->STATUS_INACTIVE;
				$arrCondition['Users.status'] = $status;
            }

			if(isset($this->request->data['Users']['username']) && $this->request->data['Users']['username']!='')
            {
                $arrCondition['Users.username LIKE'] = '%'.$this->request->data['Users']['username'].'%';
            }

			if(isset($this->request->data['Users']['email']) && $this->request->data['Users']['email']!='')
            {
                $arrCondition['Users.email LIKE'] = '%'.$this->request->data['Users']['email'].'%';
            }

			if(isset($this->request->data['Users']['mobile']) && $this->request->data['Users']['mobile']!='')
            {
                $arrCondition['Users.mobile LIKE'] = '%'.$this->request->data['Users']['mobile'].'%';
            }
			if(isset($this->request->data['Users']['city']) && $this->request->data['Users']['city']!='')
            {
                $arrCondition['Users.city LIKE'] = '%'.$this->request->data['Users']['city'].'%';
            }
			if(isset($this->request->data['Users']['designation']) && $this->request->data['Users']['designation']!='')
            {
                $arrCondition['Users.designation LIKE'] = '%'.$this->request->data['Users']['designation'].'%';
            }

			if(isset($this->request->data['Users']['usertype']) && $this->request->data['Users']['usertype']!='')
            {
                $arrCondition['Users.usertype'] = $this->request->data['Users']['usertype'];
            }
			if(isset($this->request->data['Users']['name']) && $this->request->data['Users']['name']!='')
            {
                $arrName = array("Users.firstname LIKE"=>'%'.$this->request->data['Users']['name'].'%',
									"Users.lastname LIKE"=>'%'.$this->request->data['Users']['name'].'%'
									);
				$arrCondition['OR'] = $arrName;
            }
			if(isset($this->request->data['Users']['search_date']) && $this->request->data['Users']['search_date']!='')
            {
                if($this->request->data['Users']['search_period'] == 1 || $this->request->data['Users']['search_period'] == 2)
                {
                	$arrSearchPara	= $this->Users->setSearchDateParameter($this->request->data['Users']['search_period'],$this->modelClass);
                	
                	$this->request->data[$this->modelClass] = array_merge($this->request->data[$this->modelClass],$arrSearchPara[$this->modelClass]);
                    $this->dateDisabled						= true;
                }
                $arrperiodcondi = $this->Users->findConditionByPeriod( $this->request->data['Users']['search_date'],
																		$this->request->data['Users']['search_period'],
																		$this->request->data['Users']['DateFrom'],
																		$this->request->data['Users']['DateTo'],
																		$this->Session->read('User.timezone'));
               	if(!empty($arrperiodcondi)){
                	$arrCondition['between'] = $arrperiodcondi['between'];
                }
            }
		}
		return $arrCondition;
	}


	/**
	 *
	 * admin_adminuserrights
	 *	
	 * Behaviour : Public
	 *
	 * @defination : Method is use to manage rights assigne to particular admin user and all the related activity such as add or remove rights for particular admin user
	 *
	 */
	function userrights()
	{
		$this->initAdminRightHelper();
		$this->intCurAdminUserRight = $this->Userright->ANALYSTS_RIGHTS;
		$this->setAdminArea();
		$id							= "";
		$roleid						= 0;
		$adminuserrights			= "";
		$arrAdminuser				= array();
		$arradmintrngroup			= array();
		$arradmintransaction		= array();
		$arrAdminUserRights			= array();
		$strAdminUserRights			= array();
		$arrRoleRights				= array();
		$arrAdminRole				= array();
		$strAdminRole				= "";

		if(!empty($this->data))
		{
			$id = (isset($this->data['User']['id']))?$this->data['User']['id']:"";
			if(isset($this->data['User']['action']) && $this->data['User']['action']=="Save")
			{
				$this->User->id = $id;
				if(isset($this->data['User']['userrights']))
				{
					foreach($this->data['User']['userrights'] as $intRights) {
						if(!empty($intRights)) $adminuserrights .=','.$intRights;
					}
					if(!in_array($this->Userright->ADMIN_HOME,$this->data['User']['userrights'])) $adminuserrights .=','.$this->Userright->ADMIN_HOME;
					$adminuserrights = trim($adminuserrights,',');
				} else {
					$this->_setDetaultAdminUserRights();
					$adminuserrights .= ltrim(implode(',',$this->arrDefaultAdminUserRights));
				}

				$this->User->saveField("userrights",$adminuserrights);
				$this->writeadminlog($this->Session->read('User.id'), $this->Igadminaction->ANALYSTS_ADD_REMOVE_RIGHTS, $this->User->id,'Changed Admin rights id :: '.$this->User->id);

				if($this->Session->read('User.id') == $id) {
					$this->User->GenerateAdminuserRightSession($this->Session->read('User.id'), $this->Session);
					$this->RegenerateAdminMenu();
				}
				$msg = "User rights has been updated successfully."; 
				$this->Flash->set($msg,['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
			}
			unset($this->request->data['User']['action']);
			if(!empty($id))
			{
				$arrAdminUserRights = $this->User->find('first',array('conditions'=>array('id'=>$id),'fields'=>'userrights,usertype'));
				if(isset($arrAdminUserRights['User']['usertype']))
					$roleid = $arrAdminUserRights['User']['usertype'];

				if(isset($arrAdminUserRights['User']['userrights']) && $arrAdminUserRights['User']['userrights']!='') {
					$arrAdminUserRights = explode(',',$arrAdminUserRights['User']['userrights']);
				} else {
					$arrAdminUserRights = array();
				}
				unset($this->request->data['User']['userrights']);
				if(count($arrAdminUserRights)>0)
				{
					foreach($arrAdminUserRights as $intRights) {
						$this->request->data['User']['userrights'][$intRights] = $intRights;
					}
				}
			}
		}
		$arrAdminuser = $this->User->find('list',array('fields'=>'id,username','conditions'=>array('status'=>1)));
		$arradmintransaction = $this->Igadmintransaction->find('all',array('conditions'=>array('showtrnflg'=>'Y'),'order'=>array('Igadmintransaction.trnmoduleid','Igadmintransaction.menuorder')));
		$arradmintrngroup = $this->Igadmintrngroup->find('all');
		$arradmintrngroup = Set::combine($arradmintrngroup, '{n}.Igadmintrngroup.id', '{n}.Igadmintrngroup');
		$arradmintrntype = $this->Igadmintrntype->find('all');
		$arradmintrnmodule = $this->Igadmintrnmodule->find('all');
		$arrRoleRights	= $this->Userroleright->getAllAdminUserRoleRight($roleid);
		$arrAdminRole	= $this->Userrole->getAdminuserRoles();
		if(array_key_exists($roleid, $arrAdminRole))
			$strAdminRole	= $arrAdminRole[$roleid];
		$this->set('arrRoleRights', $arrRoleRights);
		$this->set('arrAdminuser',$arrAdminuser);
		$this->set('arradmintrntype',$arradmintrntype);
		$this->set('arradmintrnmodule',$arradmintrnmodule);
		$this->set('arradmintrngroup',$arradmintrngroup);
		$this->set('arradmintransaction',$arradmintransaction);
		$this->set('id',$id);
		$this->set('strAdminRole',$strAdminRole);
		$this->set('arrAdminUserRights',$arrAdminUserRights);
	}

	/**
	 *
	 * admin_managerights
	 *
	 * Behaviour : Public
	 *
	 * @defination : Use for managing admin user rights if having master right to logged in user
	 *
	 */
	function managerights()
	{
		$this->intCurAdminUserRight = $this->Userright->MASTER_USER_RIGHT;
		$this->setAdminArea();
		
		$id						= "";
		$roleid					= 0;
		$adminuserrights		= "";
		$arrAdminuser			= array();
		$arradmintrngroup		= array();
		$arradmintransaction	= array();
		$arrAdminUserRights		= array();
		$strAdminUserRights		= array();
		$arrRoleRights			= array();
		if(!empty($this->request->data))
		{
			$id = (isset($this->request->data['User']['id']))?$this->request->data['User']['id']:"";
			if(isset($this->request->data['User']['action']) && $this->request->data['User']['action']=="Save")
			{
				//$this->User->id = $id;
				$arrcon=array();
				foreach($this->request->data['User']['userrights'] as $intRights) {
					$temparr=array();
					if($intRights)
					{
						$temparr=explode('_',$intRights);
						if(array_key_exists($temparr[0],$arrcon))
						{
							$arrcon[$temparr[0]]=$arrcon[$temparr[0]].','.(isset($temparr[1])?$temparr[1]:'');
						}
						else
						{	
							$arrcon[$temparr[0]]=(isset($temparr[1])?$temparr[1]:'');
						}
					}
				}
				$adminuserrights=serialize($arrcon);
				
				$this->Users->updateAll(['userrights' => $adminuserrights], ['id' => $this->request->data['User']['id']]);
				$this->writeadminlog($this->Session->read('User.id'), $this->Adminaction->ADD_REMOVE_ADMIN_USER_RIGHTS, $this->request->data['User']['id'],'Changed Admin rights id :: '.$this->request->data['User']['id']);

				$msg = "User rights has been updated successfully."; 
				$this->Flash->set($msg,['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
			}
			unset($this->request->data['User']['action']);
			if(!empty($id))
			{
				$arrAdminUserRights = $this->Users->find('all',array('conditions'=>array('id'=>$id)))->limit(1)->toArray();
				if(isset($arrAdminUserRights[0]->userrights) && $arrAdminUserRights[0]->userrights!='') {
					$arrUserRights = (!empty($arrAdminUserRights[0]->userrights)) ? unserialize($arrAdminUserRights[0]->userrights) : array();
				} else {
					$arrUserRights = array();
				}
				$roleid = (isset($arrAdminUserRights[0]->usertype)?$arrAdminUserRights[0]->usertype:0);
				unset($this->request->data['User']['userrights']);
				
				if(count($arrUserRights)>0)
				{
					foreach($arrUserRights as $modid=>$typeids) {
						$arrtmp=array();
						$arrtmp=explode(',',$typeids);
						foreach($arrtmp as $arrtype){
							$this->request->data['User']['userrights'][$modid.'_'.$arrtype]=$modid.'_'.$arrtype;
						}	
					}
				}
			}
		}
		$arrAdminuser		= $this->Users->find('list',['keyField' => 'id','valueField' => 'username','conditions'=>array('status'=>1,'id != '=> $this->Session->read('User.id'))])->toArray();
		$arrRoleRights 		= $this->Userroleright->getAllAdminUserRoleRight($roleid);
		$arradmintrntype 	= $this->Admintrntype->find('all')->toArray();
		$arradmintrnmodule 	= $this->Admintrnmodule->find('all')->toArray();
		/*pr($arrAdminuser);echo '<br>----';
		pr($arrRoleRights);echo '<br>----';
		pr($arradmintrnmodule);*/
		
		$this->set('arradmintrntype', $arradmintrntype);
		$this->set('arradmintrnmodule', $arradmintrnmodule);
		$this->set('arrAdminuser',$arrAdminuser);
		$this->set('id',$id);
		$this->set('arrAdminUserRights',$arrAdminUserRights);
		$this->set("title_for_layout","Add/Remove Administrator Rights");
	}

	/**
	 *
	 * admin_edit
	 *
	 * Behaviour : Public
	 *
	 * @param :  $id  : Id is use to identify for which user details to be edited
	 * @defination : Method is use to update edited detail of particular admin user using admin interface
	 *
	 */

	function edit($id = null)
	{
		$this->intCurAdminUserRight = $this->Userright->ANALYSTS_EDIT;
		$decode = intval(decode($id));
		$this->set('did',$decode);
		$this->setAdminArea();
		
		if(!empty($this->request->data)){
			$this->request->data['Users']['id'] = $id;
		}
		$userData = $this->Users->get($decode);
		$db_profile_image = (isset($userData->toArray()['profile_pic'])?$userData->toArray()['profile_pic']:'');
		$userEntity = $this->Users->patchEntity($userData, $this->request->data(),['validate' => 'edit']);
		
		$arrAdminDefaultRights = array();
		$arrUserEmailRights = array();
		$arrEmailRights = array();
		$intUserType = '';
		$blnSaved = false;
		$timezone = '';
		$arrError = array();
		
		if (!$userEntity->errors() && !empty($this->request->data)) {
			//prd($this->request);
			$this->_setDetaultAdminUserRights();
			$this->request->data['User']['userrights'] = ltrim(implode(',',$this->arrDefaultAdminUserRights));

			$intUserType = $this->request->data['Users']['usertype'];

			$userEntity = $this->Users->patchEntity($userData, $this->request->data);
			$userEntity->id = $decode;
			unset($userEntity->password);
			if($this->Users->save($userEntity)) {
				if($id == $this->Session->read('User.id')) {
					$this->Cookie->write('AU', 'Y');
				}
				$this->UserDepartment->AddUserDepartment($decode,$this->request->data['Users'],$this->FLAG);
				$this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->EDIT_ADMIN_USER,$id,'Edited Admin user id::'.$id);
				//upload profile pic
				if(isset($this->request->data['User']['profile_pic']['name']) && !empty($this->request->data['User']['profile_pic']['name'])){
					
					$image_path = PROFILE_PATH.$userEntity->id.'/';
					if(file_exists($image_path.$db_profile_image)){
						@unlink($image_path.$db_profile_image);
						@unlink($image_path.'r_'.$db_profile_image);
					}
					
					if(!file_exists(PROFILE_PATH.$userEntity->id))
						mkdir(PROFILE_PATH.$userEntity->id, 0700);
					
					$file_name = $this->file_upload($image_path,$this->request->data['User']['profile_pic'],true,65,65,$image_path);
					$this->Users->updateAll(['profile_pic' => $file_name], ['id' => $userEntity->id]);
				}

				if($id == $this->Session->read('User.id')) {
					$this->Session->write('User.timezone',$this->data['User']['timezone']);
					$timezone 						= $this->TimeZone->getTimeZoneById($this->data['User']['timezone']);
					$time_zone						= isset($timezone['TimeZone']['utc_name'])?$timezone['TimeZone']['utc_name']:"";
					$time_zone						= str_replace("UTC","",(empty($time_zone)?"UTC":$time_zone));
					$time_zone						= empty($time_zone)?"UTC":$time_zone;
					$this->Session->write('User.timezone_iana',$timezone['TimeZone']['iana_timezone_id']);
					$this->Session->write('User.time_zone',$time_zone);
					$this->Session->write('User.isDst',(isset($this->data['User']['is_dst'])?$this->data['User']['is_dst']:true));
				}
				$blnSaved = true; 
				$this->Flash->set('User has been updated',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
				return $this->redirect(array('action'=>'index'));
			}
		} else if(!$userEntity->errors()) { 
			$userEntity->password 	= '';
			$userEntity->id 		= $id;
			$intUserType = $userEntity->usertype;
			if (empty($userEntity->id)) {
				$this->Flash->set('Invalid User',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'warning']]);
				return $this->redirect(array('action'=>'index'));
				exit();
			}
		}
		if(isset($userEntity->timezone)) $timezone = $userEntity->timezone;
		$ADMINUSER_RETAILERS = array();
		
		$select_user_department = $this->UserDepartment->GetUserDepartment($decode,'1');
		for($i=0;$i<count($select_user_department);$i++){
			$this->request->data['Users']['department_id'][] = $select_user_department[$i]->department_id;	
		}
		
		$this->set('emailrights',$arrEmailRights);
		$this->set('arrUserEmailRights',$arrUserEmailRights);
		$this->set('Users',$userEntity);
		$this->set('data',$this->request->data);
		$this->set('department',$this->Department->GetDepartmentList());
		$this->set('oldusertype',$intUserType);
		$this->set('userdepartment',$this->user_department);
		$this->set('timezone', $timezone);
		$this->set('DEFAULT_USER_TIMEZONE', $this->Users->DEFAULT_USER_TIMEZONE);
		$this->set('RETAILER_RECHARGE_ROLE_ID', $this->Users->RETAILER_RECHARGE_ROLE_ID);
		$this->set('ADMINUSER_RETAILERS', $ADMINUSER_RETAILERS);
		$this->set('arrError', $arrError);
	}

	/**
	 *
	 * admin_changeprofile
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to change profile of particular User i.e with respect to profile rights will be assigned to him
	 *
	 */
    function changeprofile(){
		$this->initAdminRightHelper();
		$this->intCurAdminUserRight = $this->Userright->ANALYSTS_CHANGE_PROFILE;
        $this->setAdminArea();
		
        $this->User->setValiationRules("edit");

        $this->User->bindModel(
			array('belongsTo' => array(
					'TimeZone' => array(
						'className' => 'TimeZone',
						'foreignKey' => 'timezone',
					)
				)
			)
		);

		/** add validation for password and confirmpassword */
		if(isset($this->data['User']['newpassword']) && !empty($this->data['User']['newpassword'])) {
        	$this->User->AddValidation("newpassword",$this->User->validateregister['newpassword']);
        	$this->User->AddValidation("confirmnewpassword",$this->User->validateregister['newpassword']);
        }
        /** add validation for password and confirmpassword */
		
		$arrAdminDefaultRights 	= array();
		$arrUserEmailRights 	= array();
		$arrEmailRights 		= array();
		$intUserType 			= '';
		$timezone 				= '';
		$arrError 				= array();
		$renderctp 				= true;
        $id 					= $this->Session->read("User.id");
        $usertype 				= $this->Session->read('User.usertype');
        $arrAdminRights = array();
		if (!empty($this->data)) {
			if(isset($this->data['User']['userrights']) && is_array($this->data['User']['userrights'])){
                $arrAdminRights = $this->data['User']['userrights'];
                $this->request->data['User']['userrights'] = implode(",",$this->data['User']['userrights']);
            }
			if(isset($this->data['User']['emailrights']) && is_array($this->data['User']['emailrights'])) {
                $arrUserEmailRights = $this->data['User']['emailrights'];
				$this->request->data['User']['emailrights'] = implode(",",$this->data['User']['emailrights']);
			} else if(in_array($this->Userright->ANALYSTS_ADD_REMOVE_EMAIL_RIGHTS,$this->Session->read('User.userrights'))) {
                $this->request->data['User']['emailrights'] = '';
           	} 
			unset($this->request->data['User']['usertype']);
			$this->User->validate = array_merge($this->User->validate,$this->User->validate_timezone);
            if ($this->User->save($this->data)) {

                $this->writeadminlog($this->Session->read('User.id'),$this->Igadminaction->CHANGED_PROFILE,null,'Change Profile');
				$this->Session->write('User.timezone',$this->data['User']['timezone']);
				$timezone = $this->TimeZone->getTimeZoneById($this->data['User']['timezone']);
				$time_zone						= isset($timezone['TimeZone']['utc_name'])?$timezone['TimeZone']['utc_name']:"";
				$time_zone						= str_replace("UTC","",(empty($time_zone)?"UTC":$time_zone));
				$time_zone						= empty($time_zone)?"UTC":$time_zone;
				
				$this->Session->write('User.timezone_iana',$timezone['TimeZone']['iana_timezone_id']);
				$this->Session->write('User.time_zone',$time_zone);
				$this->Session->write('User.isDst',(isset($this->data['User']['is_dst'])?$this->data['User']['is_dst']:true));

				$this->Flash->set('User profile has been updated.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
                $this->redirect(array('action'=>'changeprofile'));
                $renderctp = false;
            }
        } else {
			$this->data = $this->User->read(null, $id);
            $intUserType = $this->data['User']['usertype'];
			$arrUserEmailRights = explode(',',$this->data['User']['emailrights']);
			if (empty($this->data)) {
				$this->Flash->set('Invalid User.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'warning']]);
                $this->redirect(array('action'=>'index'));
                exit();
            }
        }
		if($renderctp) {
			$arrEmailRights = $this->listemailrights();
			$arrEmailRights = Set::combine($arrEmailRights, '{n}.Igemail.id', '{n}.Igemail.name');
		}

		if(isset($this->data['User']['timezone'])) $timezone = $this->data['User']['timezone'];

	
		$ADMINUSER_RETAILERS = array();
		$RETAILERS =array();
		/** admin user and retailers mapping information */
			
		$this->set('emailrights',$arrEmailRights);
		$this->set('arrUserEmailRights',$arrUserEmailRights);
		$this->set('data',$this->data);
		$this->set('oldusertype',$intUserType);
        $this->set('changeprofile','yes');
		$this->set('timezone', $timezone);
		$this->set('arrTimeZoneList', '');
	//	$this->set('arrTimeZoneList', $this->TimeZone->getTimeZoneList());
		$this->set('DEFAULT_ADMINUSER_TIMEZONE', $this->User->DEFAULT_ADMINUSER_TIMEZONE);
		$this->set('RETAILER_RECHARGE_ROLE_ID', $this->User->RETAILER_RECHARGE_ROLE_ID);
		$this->set('arrError', $arrError);
		$this->set('RETAILER', $RETAILERS);
		$this->set('ADMINUSER_RETAILERS', $ADMINUSER_RETAILERS);
        if($renderctp) $this->render("edit");
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
		$this->intCurAdminUserRight = $this->Userright->ANALYSTS_DISABLE;
		$id = intval(decode($id));
		$this->setAdminArea();

		if(((int)$id)==0) $this->redirect('index');

		if($this->Users->updateAll(['status' => 0], ['id' => $id]))
		{
			$this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->INACTIVATED_ADMIN_USER,$id,'Inactivated Admin user id :: '.$id);
			$this->Flash->set('User has been De-Activated.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
			
			return $this->redirect(array('action'=>'index'));
			exit;
		}
		else
		{
			$this->Flash->set('User De-Activation Error! Contact Administrator.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'error']]);
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
		$this->intCurAdminUserRight = $this->Userright->ANALYSTS_ENABLE;
		$id = intval(decode($id));
		$this->setAdminArea();

		if(((int)$id)==0) $this->redirect('index');
		$user_arr = $this->Users->get($id);
		$user_arr->status = 1;
		
		if($this->Users->save($user_arr))
		{
			$this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->ACTIVATED_ADMIN_USER,$user_arr->id,'Activated Admin user id :: '.$user_arr->id);
			$this->Flash->set('User has been Activated.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
			return $this->redirect(array('action'=>'index'));
			exit;
		}
		else
		{
			$this->Flash->set('User Activation Error! Contact Administrator.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'error']]);
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
	public function add()
	{
		$this->intCurAdminUserRight = $this->Userright->ANALYSTS_ADD;
		$this->setAdminArea();
		$userEntity = $this->Users->newEntity($this->request->data() ,['validate' => 'add']);
		
		$arrAdminDefaultRights = array();
		$timezone = '';
		$arrError = array();

		if(!$userEntity->errors() && !empty($this->request->data)) {

			$this->request->data['Users']['userrights'] = ltrim(implode(',',$this->arrDefaultAdminUserRights));
            $this->request->data['Users']['apikey'] = sha1(time());
			$newUsers =  $this->Users->newEntity($this->request->data['Users']);

			if($this->Users->save($newUsers)) {

				if(isset($this->request->data['User']['profile_pic']['name']) && !empty($this->request->data['User']['profile_pic']['name'])){
					
					$image_path = PROFILE_PATH.$newUsers->id.'/';
					
					if(!file_exists(PROFILE_PATH.$newUsers->id))
						mkdir(PROFILE_PATH.$newUsers->id, 0700);
					
					$file_name = $this->file_upload($image_path,$this->request->data['User']['profile_pic'],true,65,65,$image_path);
					$this->Users->updateAll(['profile_pic' => $file_name], ['id' => $newUsers->id]);
				}

				$this->UserDepartment->AddUserDepartment($newUsers->id,$this->request->data['Users']);
				$this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->ADD_ADMIN_USER,$newUsers->id,'Added Admin user id::'.$newUsers->id);
				$this->Flash->set('User has been saved.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
				return $this->redirect(WEB_ADMIN_PREFIX.'/users');
			}
		}
		if(isset($this->data['User']['timezone'])) $timezone = $this->data['User']['timezone'];
		$ADMINUSER_RETAILERS = array();
		$this->set('Users',$userEntity);
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
    * _setDetaultAdminUserRights
    *
    * Behaviour : private
    *
    * @defination : Method is use to set default rights to amdinuser
    *
    */

	private function _setDetaultAdminUserRights()
	{
		$this->initAdminRightHelper();
		$this->arrDefaultAdminUserRights = array();
	}

	/**
	 *
	 * departmentwiseuserlist
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to get Department wise user and provide specific righs using admin interface
	 *
	 */
	public function departmentwiseuserlist($did)
	{
		$this->autoRender=false;
		
		$parent_id = $this->Session->read("User.id");
		$data=$this->UserDepartment->GetDepartmentwiseUserlist($did,$parent_id);
		
		echo json_encode($data);
	}

	/**
	 * userlist
	 * Behaviour : Public
	 * @defination : Method is subscription type list for subscription
	 */
	public function userlist($char='')
	{
		$this->autoRender = false;

		$result = $this->Users->userslist($char);
		$data = array();
		if(!empty($result))
		{	
			foreach ($result as $key => $val) {
				$name = $val . '|' . $key;
				array_push($data, $name);
			}
		}
		echo json_encode($data);
	}

	/**
	 *
	 * change_password
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to change user password
	 *
	 */
	public function change_password($id = null)
	{
		$this->intCurAdminUserRight = $this->Userright->ANALYSTS_EDIT;
		$this->setAdminArea();
		$decode 	= intval(decode($id));
		$arrAdminDefaultRights	= array();
		$blnSaved 	= false;
		$arrError 	= array();
		
		if(!empty($this->request->data)){
			$this->request->data['Users']['id'] = $id;
		}
		$incentiveData 	= $this->Users->get($decode);
		$Users 	= $this->Users->patchEntity($incentiveData, $this->request->data(),['validate' => 'ChangePassword']);
		
		if (!$Users->errors() && !empty($this->request->data)) {
			$Users = $this->Users->patchEntity($incentiveData, $this->request->data);
			$Users->id = $decode;
			if($this->Users->save($Users)) {
				$this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->EDIT_ADMIN_USER,$id,'Changed password of Admin user id::'.$id);
				$blnSaved = true; 
				$this->Flash->set('Password has been changed',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
				return $this->redirect(array('action'=>'index'));
			}
		} else if(!$Users->errors()) { 
			$Users->id 	= $id;
			if (empty($Users->id)) {
				$this->Flash->set('Invalid Request',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'warning']]);
				return $this->redirect(array('action'=>'index'));
				exit();
			}
		}		
		$this->set('Users',$Users);
		$this->set('data',$this->request->data);
		$this->set('arrError', $arrError);
	}
}
