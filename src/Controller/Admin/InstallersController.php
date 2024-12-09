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
use Cake\Datasource\ConnectionManager;

use Cake\Event\Event;
use Cake\View\Helper\PaginatorHelper;
use Cake\Validation\Validator;
use Dompdf\Dompdf;

class InstallersController extends AppController
{	
	var $helpers = array('Time','Html','Form','ExPaginator');
	public $arrDefaultAdminUserRights 	= array(); 
	public $PAGE_NAME 					= '';
	public $contact_code_min = 	1000;
	public $contact_code_max =	9999;
	public $paginate = [
        'limit' => PAGE_RECORD_LIMIT,
        'order' => [
            'Installers.id ' => 'desc'
        ]
    ];
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
		$this->loadModel('Department');
		$this->loadModel('UserDepartment');
		$this->loadModel('Admintrntype');
		$this->loadModel('Admintrnmodule');
		$this->loadModel('ApiToken');
		$this->loadModel('GhiData');
		$this->loadModel('Installers');
		$this->loadModel('Projects');
		$this->loadModel('Customers');
		$this->loadModel('CustomerProjects');
		$this->loadModel('InstallerProjects');
		$this->loadModel('InstallerPlans');
		$this->loadModel('InstallerActivationCodes');
		$this->loadModel('ProjectLeads');		
		$this->loadModel('InstallersCoupan');		
		$this->loadModel('ContactedInstaller');		
		$this->loadModel('SiteSurveys');		
		$this->loadModel('Commercial');		
		$this->loadModel('Commissioning');		
		$this->loadModel('Company');		
		$this->loadModel('Workorder');
		$this->loadModel('Proposal');		
		$this->loadModel('Installation');	
		$this->loadModel('CustomerSubscription');	
		$this->loadModel('Subscription');
		$this->loadModel('InstallerTerms');
		$this->loadModel('InstallerSubscription');
        $this->loadModel('CheckUserRole');
		$this->loadModel('Parameters');
		$this->loadModel('States');
		$this->loadModel('InstallerCategoryMapping');
		$this->loadModel('InstallerCredendtials');
		$this->set('Userright',$this->Userright);
    }

    private function SetVariables($post_variables) { 

		if(isset($post_variables['lat']))
			$this->request->data['Installers']['latitude']			= $post_variables['lat'];
		if(isset($post_variables['lon']))
			$this->request->data['Installers']['longitude']			= $post_variables['lon'];
		if(isset($post_variables['installer_name']))
			$this->request->data['Installers']['installer_name'] 	= $post_variables['installer_name'];
		if(isset($post_variables['customer_type']))
			$this->request->data['Installers']['customer_type'] 	= $post_variables['customer_type'];
		if(isset($post_variables['company_name']))
			$this->request->data['Installers']['installer_name'] 	= $post_variables['company_name'];
		if(isset($post_variables['company_id']))
			$this->request->data['Installers']['company_id'] 		= $post_variables['company_id'];
		if(isset($post_variables['company_plan_id']))
			$this->request->data['Installers']['installer_plan_id'] = $post_variables['company_plan_id'];
		if(isset($post_variables['company_city']))
			$this->request->data['Installers']['city'] 				= $post_variables['company_city'];
		if(isset($post_variables['company_state']))
			$this->request->data['Installers']['state'] 			= $post_variables['company_state'];
		if(isset($post_variables['company_pincode']))
			$this->request->data['Installers']['pincode'] 			= $post_variables['company_pincode'];
		if(isset($post_variables['about_company']))
			$this->request->data['Installers']['about_installer'] 	= $post_variables['about_company'];
		if(isset($post_variables['company_address']))
			$this->request->data['Installers']['address'] 			= $post_variables['company_address'];
		if(isset($post_variables['company_mobile']))
			$this->request->data['Installers']['mobile'] 			= $post_variables['company_mobile'];

		if(isset($post_variables['installer_id']))
			$this->request->data['InstallerProjects']['installer_id'] 	= $post_variables['installer_id'];
		if(isset($post_variables['proj_id']))
		{
			$this->request->data['InstallerProjects']['project_id'] 	= $post_variables['proj_id'];
			$this->request->data['Installers']['project_id'] 	= $post_variables['proj_id'];
		}
		if(isset($post_variables['project_id']))
			$this->request->data['InstallerProjects']['project_id'] 	= $post_variables['project_id'];
		if(isset($post_variables['status']))
			$this->request->data['InstallerProjects']['status'] 		= $post_variables['status'];
		if(isset($post_variables['month']))
			$this->request->data['Installers']['use_month'] 	= $post_variables['month'];
		if(isset($post_variables['users']))
			$this->request->data['Installers']['sub_users'] 	= $post_variables['users'];
		if(isset($post_variables['dis_code']))
			$this->request->data['Installers']['coupan_code'] 	= $post_variables['dis_code'];
		if(isset($post_variables['contact_code']))
			$this->request->data['Installers']['contact_code'] 	= $post_variables['contact_code'];
		if(isset($post_variables['query']))
			$this->request->data['Installers']['query'] 	= $post_variables['query'];
		$this->request->data['Installers']['sub_user_code'] 	= (isset($post_variables['code'])?$post_variables['code']:'');
	}

	/*
	 * Displays a index
	 *
	 * @param mixed What page to display
	 * @return void
	 */
	public function index() 
	{
		$this->intCurAdminUserRight = $this->Userright->LIST_CUSTOMER;
		$this->setAdminArea();
		
		if (!empty($this->Customers->validate)) {
			foreach ($this->Customers->validate as $field => $rules) {
				$this->Customers->validator()->remove($field); //Remove all validation in search page
			}
		}
		
		$arrcustomerList	= array();
		$arrCondition		= array();
		$this->SortBy		= "Customers.id";
		$this->Direction	= "ASC";
		$this->intLimit		= PAGE_RECORD_LIMIT;
		$this->CurrentPage  = 1;
		$option 			= array();
		$option['colName']  = array('id','name','email','mobile','action');
		
		$this->SetSortingVars('Customers',$option);
		$arrCondition		= $this->_generateCustomerSearchCondition();
		
		/*$arrCondition['between'] = ["Users.lastlogin","2015-10-01 01:00:00","2015-10-02 23:59:59"];*/
		$this->paginate		= array('conditions' => $arrCondition,
									'fields' => array('id','name','email','mobile','status'),
									'order'=>array($this->SortBy=>$this->Direction),
									'page'=> $this->CurrentPage,
									'limit' => PAGE_RECORD_LIMIT);
		$arrcustomerList	= $this->paginate('Customers');
		$arrUserType['']	= "Select";
		
		
		
		//$usertypes = $this->Userrole->getAdminuserRoles();
		$usertypes = array();
		//foreach($usertypes as $key=>$value) $arrUserType[$key] = $value;

		$option['dt_selector']	='table-example';
		$option['formId']		='formmain';
		$option['url']			= WEB_ADMIN_PREFIX.'customers';
		$JqdTablescr 			= $this->JqdTable->create($option);
		$this->set('arrcustomerList',$arrcustomerList->toArray());
		$this->set('JqdTablescr',$JqdTablescr);
		$this->set('arrUserType',$arrUserType);
		$this->set('period',$this->period);
		$this->set('limit',$this->intLimit);
		$this->set("CurrentPage",$this->CurrentPage);
		$this->set("SortBy",$this->SortBy);
		$this->set("Direction",$this->Direction);
		$this->set("page_count",(isset($this->request->params['paging']['Customers']['pageCount'])?$this->request->params['paging']['Customers']['pageCount']:0));
		$out = array();
		
		/*$blnEditAdminuserRights		= $this->Userright->checkadminrights($this->Userright->ANALYSTS_EDIT);
		$blnEnableAdminuserRights	= $this->Userright->checkadminrights($this->Userright->ANALYSTS_ENABLE);	
		$blnDisableAdminuserRights	= $this->Userright->checkadminrights($this->Userright->ANALYSTS_DISABLE);*/
		//pr($arrcustomerList->toArray()); exit;
		$blnEditCustomersRights		= $this->Userright->checkadminrights($this->Userright->EDIT_CUSTOMER);
		foreach($arrcustomerList->toArray() as $key => $val) {
			$temparr = array();
			foreach($option['colName'] as $key) {
				if(isset($val[$key])) {
					$temparr[$key] = $val[$key];
				}else{
					$temparr[$key]='';
				}
				if($key == 'action') {
					if($key=='action') {
						$temparr['action']='';
						//$temparr['action'].= $this->Userright->linkEditAdminuser(constant('WEB_URL').constant('ADMIN_PATH').'customers/view/'.encode($val['id']),'<i class="fa fa-edit"> </i>','','viewRecord',' title="View Customer Info"')."&nbsp;";
						if($blnEditCustomersRights){
							if(empty($val['status']))
								$temparr['action'].= $this->Userright->linkEnableAdminuser(constant('WEB_URL').constant('ADMIN_PATH').'customers/enable/'.encode($val['id']),'<i class="fa fa-check-circle-o"></i>','','actionRecord',' title="Activate"')."&nbsp;";
							if(!empty($val['status']))
								$temparr['action'].= $this->Userright->linkDisableAdminuser(constant('WEB_URL').constant('ADMIN_PATH').'customers/disable/'.encode($val['id']),'<i class="fa fa-circle-o"></i>','','actionRecord',' title="De-Activate"')."&nbsp;";
						}
					}
				}		
			}
			$out[] = $temparr;
		}
		if ($this->request->is('ajax')){
			header('Content-type: application/json');
			echo json_encode(array('condi'=>$arrCondition,"draw" => intval($this->request->data['draw']),
			"recordsTotal"    => intval( $this->request->params['paging']['Customers']['count']),
			"recordsFiltered" => intval( $this->request->params['paging']['Customers']['count']),
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
		if(!empty($id)) $this->request->data['Customers']['id'] = $id;
		if(count($this->request->data)==0) $this->request->data['Customers']['status'] = $this->Customers->STATUS_ACTIVE;
		if(isset($this->request->data) && count($this->request->data)>0)
        {
            if(isset($this->request->data['Customers']['id']) && trim($this->request->data['Customers']['id'])!='')
            {
                $strID = trim($this->request->data['Customers']['id'],',');
                $arrCondition['Customers.id'] = $this->request->data['Customers']['id'];/* array_unique(explode(',',$strID));*/
            }

			if(isset($this->request->data['Customers']['status']) && !empty($this->request->data['Customers']['status']))
            {
                $status = $this->request->data['Customers']['status'];
				if($this->request->data['Customers']['status']=='I') $status = $this->Customers->STATUS_INACTIVE;
				$arrCondition['Customers.status'] = $status;
            }

			if(isset($this->request->data['Customers']['username']) && $this->request->data['Customers']['username']!='')
            {
                $arrCondition['Customers.username LIKE'] = '%'.$this->request->data['Customers']['username'].'%';
            }

			if(isset($this->request->data['Customers']['email']) && $this->request->data['Customers']['email']!='')
            {
                $arrCondition['Customers.email LIKE'] = '%'.$this->request->data['Customers']['email'].'%';
            }

			if(isset($this->request->data['Customers']['mobile']) && $this->request->data['Customers']['mobile']!='')
            {
                $arrCondition['Customers.mobile LIKE'] = '%'.$this->request->data['Customers']['mobile'].'%';
            }
			if(isset($this->request->data['Customers']['city']) && $this->request->data['Customers']['city']!='')
            {
                $arrCondition['Customers.city LIKE'] = '%'.$this->request->data['Customers']['city'].'%';
            }
			if(isset($this->request->data['Customers']['designation']) && $this->request->data['Customers']['designation']!='')
            {
                $arrCondition['Customers.designation LIKE'] = '%'.$this->request->data['Customers']['designation'].'%';
            }

			if(isset($this->request->data['Customers']['usertype']) && $this->request->data['Customers']['usertype']!='')
            {
                $arrCondition['Customers.usertype'] = $this->request->data['Customers']['usertype'];
            }
			if(isset($this->request->data['Customers']['name']) && $this->request->data['Customers']['name']!='')
            {
                $arrCondition['Customers.name LIKE'] = '%'.$this->request->data['Customers']['name'].'%';
            }
			if(isset($this->request->data['Customers']['search_date']) && $this->request->data['Customers']['search_date']!='')
            {
                if($this->request->data['Customers']['search_period'] == 1 || $this->request->data['Customers']['search_period'] == 2)
                {
                	$arrSearchPara	= $this->Customers->setSearchDateParameter($this->request->data['Customers']['search_period'],$this->modelClass);
                	
                	$this->request->data[$this->modelClass] = array_merge($this->request->data[$this->modelClass],$arrSearchPara[$this->modelClass]);
                    $this->dateDisabled						= true;
                }
                $arrperiodcondi = $this->Customers->findConditionByPeriod( $this->request->data['Customers']['search_date'],
																		$this->request->data['Customers']['search_period'],
																		$this->request->data['Customers']['DateFrom'],
																		$this->request->data['Customers']['DateTo'],
																		$this->Session->read('Customers.timezone'));
               	if(!empty($arrperiodcondi)){
                	$arrCondition['between'] = $arrperiodcondi['between'];
                }
            }
		}
		return $arrCondition;
	}

	private function _generateCustomerSubscriptionSearchCondition($id=null)
	{
		$arrCondition	= array();
		$blnSinCompany	= true;
		if(!empty($id)) $this->request->data['CustomerSubscription']['id'] = $id;
		if(count($this->request->data)==0) $this->request->data['v']['status'] = $this->Customers->STATUS_ACTIVE;
		if(isset($this->request->data) && count($this->request->data)>0)
        {
            if(isset($this->request->data['CustomerSubscription']['id']) && trim($this->request->data['CustomerSubscription']['id'])!='')
            {
                $strID = trim($this->request->data['CustomerSubscription']['id'],',');
                $arrCondition['CustomerSubscription.id'] = $this->request->data['CustomerSubscription']['id'];/* array_unique(explode(',',$strID));*/
            }

			if(isset($this->request->data['CustomerSubscription']['status']) && !empty($this->request->data['CustomerSubscription']['status']))
            {
                $status = $this->request->data['CustomerSubscription']['status'];
				if($this->request->data['CustomerSubscription']['status']=='I') $status = $this->Customers->STATUS_INACTIVE;
				$arrCondition['CustomerSubscription.status'] = $status;
            }

			
			if(isset($this->request->data['CustomerSubscription']['search_date']) && $this->request->data['CustomerSubscription']['search_date']!='')
            {
                if($this->request->data['CustomerSubscription']['search_period'] == 1 || $this->request->data['CustomerSubscription']['search_period'] == 2)
                {
                	$arrSearchPara	= $this->Customers->setSearchDateParameter($this->request->data['CustomerSubscription']['search_period'],$this->modelClass);
                	
                	$this->request->data[$this->modelClass] = array_merge($this->request->data[$this->modelClass],$arrSearchPara[$this->modelClass]);
                    $this->dateDisabled						= true;
                }
                $arrperiodcondi = $this->Customers->findConditionByPeriod( $this->request->data['CustomerSubscription']['search_date'],
																		$this->request->data['CustomerSubscription']['search_period'],
																		$this->request->data['CustomerSubscription']['DateFrom'],
																		$this->request->data['CustomerSubscription']['DateTo'],
																		$this->Session->read('CustomerSubscription.timezone'));
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
	function disable($id=null) 
	{
		$this->initAdminRightHelper();
		$this->intCurAdminUserRight = $this->Userright->EDIT_CUSTOMER;
		$id = intval(decode($id));
		$this->setAdminArea();

		if(((int)$id)==0) $this->redirect('index');

		if($this->Customers->updateAll(['status' => 0], ['id' => $id]))
		{
			$this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->IN_ACTIVATED_CUSTOMER,$id,'Inactivated Customer id :: '.$id);
			$this->Flash->set('Customer has been De-Activated.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
			
			return $this->redirect(array('action'=>'index'));
			exit;
		}
		else
		{
			$this->Flash->set('Customer De-Activation Error! Contact Administrator.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'error']]);
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
	function enable($id=null) 
	{
		$this->initAdminRightHelper();
		$this->intCurAdminUserRight = $this->Userright->EDIT_CUSTOMER;
		$id = intval(decode($id));
		$this->setAdminArea();

		if(((int)$id)==0) $this->redirect('index');
		$user_arr = $this->Customers->get($id);
		$user_arr->status = 1;
		
		if($this->Customers->save($user_arr))
		{
			$this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->ACTIVATED_CUSTOMER,$user_arr->id,'Activated Customer id :: '.$user_arr->id);
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
	 * _generateInstallerSearchCondition
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use generate installer search condition.
	 *
	 * Author : Khushal Bhalsod
	 */
	private function _generateInstallerSearchCondition($id=null)
	{
		$arrCondition	= array();		
		if(!empty($id)) $this->request->data['Installers']['id'] = $id;
		if(isset($this->request->data) && count($this->request->data)>0) {
			if(isset($this->request->data['Installers']['project_id']) && trim($this->request->data['Installers']['project_id'])!='') {
                $project_data 	= $this->Projects->find('all',array('conditions'=>array('id'=>$this->request->data['Installers']['project_id'])))->first();
				$this->request->data['Installers']['state'] 	= $project_data->state;
			}
            if(isset($this->request->data['Installers']['id']) && trim($this->request->data['Installers']['id'])!='') {
                $strID = trim($this->request->data['Installers']['id'],',');
                $arrCondition['Installers.id'] = $this->request->data['Installers']['id'];
            }
			if(isset($this->request->data['Installers']['status']) && $this->request->data['Installers']['status']!='') {
                $arrCondition['Installers.status'] = $this->request->data['Installers']['status'];
            }else{
				$arrCondition['Installers.status'] = '1';
			}
			if(isset($this->request->data['Installers']['installer_name']) && $this->request->data['Installers']['installer_name']!='') {
                $arrCondition['Installers.installer_name LIKE'] = '%'.$this->request->data['Installers']['installer_name'].'%';
            }
			if(isset($this->request->data['Installers']['email']) && $this->request->data['Installers']['email']!='') {
                $arrCondition['Installers.email LIKE'] = '%'.$this->request->data['Installers']['email'].'%';
            }
			if(isset($this->request->data['Installers']['mobile']) && $this->request->data['Installers']['mobile']!='') {
                $arrCondition['Installers.mobile LIKE'] = '%'.$this->request->data['Installers']['mobile'].'%';
            }
            if(isset($this->request->data['Installers']['address']) && $this->request->data['Installers']['address']!='') {
                $arrCondition['Installers.address LIKE'] = '%'.$this->request->data['Installers']['address'].'%';
            }
			if(isset($this->request->data['Installers']['state']) && $this->request->data['Installers']['state']!='') {
                $arrCondition['Installers.state LIKE'] = '%'.$this->request->data['Installers']['state'].'%';
            }			
			if(isset($this->request->data['Installers']['search_date']) && $this->request->data['Installers']['search_date']!='') {
                if($this->request->data['Installers']['search_period'] == 1 || $this->request->data['Installers']['search_period'] == 2) {
                	$arrSearchPara	= $this->Installers->setSearchDateParameter($this->request->data['Installers']['search_period'],$this->modelClass);
                	$this->request->data[$this->modelClass] = array_merge($this->request->data[$this->modelClass],$arrSearchPara[$this->modelClass]);
                    $this->dateDisabled						= true;
                }
                $arrperiodcondi = $this->Installers->findConditionByPeriod( $this->request->data['Installers']['search_date'],
																		$this->request->data['Installers']['search_period'],
																		$this->request->data['Installers']['DateFrom'],
																		$this->request->data['Installers']['DateTo'],
																		$this->Session->read('Installers.timezone'));
               	if(!empty($arrperiodcondi)){
                	$arrCondition['between'] = $arrperiodcondi['between'];
                }
            }
		}
		return $arrCondition;
	}

	/**
	 *
	 * getinstallerlist
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to get installer list.
	 *
	 */
	public function getinstallerlist()
	{
		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		$StateName 			= "";
		$latitude 			= 0;
		$longitude 			= 0;
		$project_id 		= (isset($this->request->data['project_id']))?$this->request->data['project_id']:0;
		if (empty($project_id)) {
			if(!empty($this->request->data['Installers']['latitude']) && !empty($this->request->data['Installers']['longitude'])) {
				$location 	= $this->Installers->GetLocationByLatLong($this->request->data['Installers']['latitude'], $this->request->data['Installers']['longitude']);
				$StateName 	= (isset($location['state']) &&  !empty($location['state'])?$location['state']:"");
				$latitude 	= $this->request->data['Installers']['latitude'];
				$longitude 	= $this->request->data['Installers']['longitude'];
			}
		} else {
			$projectDetail = $this->Projects->find('all',['conditions'=>['id'=>intval($project_id)]])->first();
			if (!empty($projectDetail)) {
				$StateName 	= (isset($projectDetail->state) &&  !empty($projectDetail->state)?$projectDetail->state:"");
				$latitude 	= (isset($projectDetail->latitude) &&  !empty($projectDetail->latitude)?$projectDetail->latitude:"");
				$longitude 	= (isset($projectDetail->longitude) &&  !empty($projectDetail->longitude)?$projectDetail->longitude:"");
			} else if(!empty($this->request->data['Installers']['latitude']) && !empty($this->request->data['Installers']['longitude'])) {
				$location 	= $this->Installers->GetLocationByLatLong($this->request->data['Installers']['latitude'], $this->request->data['Installers']['longitude']);
				$StateName 	= (isset($location['state']) &&  !empty($location['state'])?$location['state']:"");
				$latitude 	= $this->request->data['Installers']['latitude'];
				$longitude 	= $this->request->data['Installers']['longitude'];
			}
		}
		$page 			= intval(isset($this->request->data['page'])?$this->request->data['page']:1);
		$InstallerData 	= $this->Installers->GetInstallersByState($StateName,$latitude,$longitude,$page,PAGE_RECORD_LIMIT);
		$this->ApiToken->SetAPIResponse('type', 'ok');
		$this->ApiToken->SetAPIResponse('result', $InstallerData['data']);
		$this->ApiToken->SetAPIResponse('limit', PAGE_RECORD_LIMIT);
		$this->ApiToken->SetAPIResponse('CurrentPage', $page);
		$this->ApiToken->SetAPIResponse('page_count',$InstallerData['page_count']);
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	
	/**
	 *
	 * Get Project Installers
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to get project installer.
	 *
	 * Author : CP Soni
	 */
	public function getprojectinstallers()
	{
		$this->autoRender 	= false;
		$installerData 		= array();
		$projectId 			= $this->request->data['proj_id'];
		if($projectId != ""){
			$installerData  	= $this->Installers->getProjectInstallers($projectId);
			$this->ApiToken->SetAPIResponse('type', 'ok');
			$this->ApiToken->SetAPIResponse('result', $installerData);
		} else {
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', "Invalid Request!");
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	 *
	 * Get Project Installers
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to get project installer.
	 *
	 * Author : CP Soni
	 */
	public function getprojectfindinstallerlist()
	{
		$this->autoRender 	= false;
		$installerData 		= array();
		$projectId 			= $this->request->data['proj_id'];
		if($projectId != ""){
			$installerData  = $this->Installers->getProjectFindInstallerList($projectId);
			$this->ApiToken->SetAPIResponse('type', 'ok');
			$this->ApiToken->SetAPIResponse('result', $installerData);
		}else{
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', "Invalid Request!");
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	 *
	 * searchinstaller
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to get search installer.
	 *
	 * Author : Khushal Bhalsod
	 */
	public function searchinstaller()
	{
		$this->autoRender 	= false;
		$installerData 		= array();
		$this->SetVariables($this->request->data);
		$mnre_image 		= MNRE_IMG_URL; 
		$installer 			= array();
		$arrFiltres 		= $this->_generateInstallerSearchCondition();
		$arrFiltres['Installers.status'] = 1;
		$installerData  	= $this->Installers->find('all')->where($arrFiltres)->toArray();
		if(!empty($installerData)) {
			foreach($installerData as $key=>$value) { 
				$installer[$key]['id']				= (isset($installerData[$key]['id'])?$installerData[$key]['id']:'');
				$installer[$key]['installer_name'] 	= (isset($installerData[$key]['installer_name'])?$installerData[$key]['installer_name']:'');
				$installer[$key]['address'] 		= str_replace($installerData[$key]['city'], "", $installerData[$key]['address']);
				$installer[$key]['address'] 		= str_replace($installerData[$key]['state'], "", $installerData[$key]['address']);
				$installer[$key]['city'] 			= (isset($installerData[$key]['city'])?$installerData[$key]['city']:'');
				$installer[$key]['state'] 			= (isset($installerData[$key]['state'])?$installerData[$key]['state']:'');
				$installer[$key]['contact'] 		= (isset($installerData[$key]['mobile'])?$installerData[$key]['mobile']:'');
				$installer[$key]['email'] 			= (isset($installerData[$key]['email'])?$installerData[$key]['email']:'');
				$installer[$key]['fax_no'] 			= (isset($installerData[$key]['fax_no'])?$installerData[$key]['fax_no']:'');
				$installer[$key]['mobile'] 			= (isset($installerData[$key]['mobile'])?$installerData[$key]['mobile']:'');
				$installer[$key]['mnre_image'] 		= (!empty($installerData[$key]['application_code'])?$mnre_image:'');
				$installer[$key]['geda_image'] 		= (!empty($installerData[$key]['geda_rate'])?GEDA_IMG_URL:'');
				$installer[$key]['empanelled_logo'] = array();
				if(!empty($installerData[$key]['application_code'])){
					$installer[$key]['empanelled_logo'][] = array('image_url'=>$mnre_image);
				}
				if(!empty($installerData[$key]['geda_rate'])){
					$installer[$key]['empanelled_logo'][] = array('image_url'=>GEDA_IMG_URL);
				}
				if(isset($value['total_points'])){
					$installer[$key]['rating'] 		= round(($value['total_points']/20) * 5);
				}else{
					$installer[$key]['rating'] 		= 5;
				}
			}	
		}		
		
		$this->ApiToken->SetAPIResponse('type', 'ok');
		$this->ApiToken->SetAPIResponse('result', $installer);
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	 *
	 * sendquery
	 *
	 * Behaviour : Public
	 *	
	 * Parameter : proj_id(int), installer_id(str)	
	 *
	 * @defination : Method is use to send query.
	 *
	 */
	public function sendquery()
	{
		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		$project_id 		= $this->request->data['proj_id'];
		$installer_id 		= $this->request->data['installer_id'];
		
		if(!empty($installer_id)) {
			$ins_ids = explode('#',$installer_id);
		}

		if(!empty($project_id) && !empty($installer_id) && !empty($ins_ids[0])) { 
			if(count($ins_ids)>5)
			{
				$this->ApiToken->SetAPIResponse('type', 'error');
				$this->ApiToken->SetAPIResponse('msg', 'Maximum 5 installers at a time can select in project.');
			}
			else
			{
				$arr_count_project 	= $this->InstallerProjects->find('all',array('conditions'=>array('project_id'=>$project_id,'status'=>'4001')))->toArray();
				if(count($arr_count_project)>=5)
				{
					$this->ApiToken->SetAPIResponse('type', 'error');
					$this->ApiToken->SetAPIResponse('msg', 'You have already send query for project.');
				}
				elseif((count($arr_count_project)+count($ins_ids))>5)
				{
					$this->ApiToken->SetAPIResponse('type', 'error');
					$this->ApiToken->SetAPIResponse('msg', 'You can select maximum '.(5-count($arr_count_project)).' installers');
				}
				else
				{
					$projLeadData 		= array();			
					$existingInstaller 	= array();
					$installerList 		= $this->InstallerProjects->getProjectwiseInstallerList($project_id);
					if(!empty($installerList))
					{
						foreach($installerList as $keys=>$insArray)
						{
							$existingInstaller[]= $insArray->installers['id'];
						}
					}	
			
					/*Store project lead*/
					foreach ($ins_ids as $key => $value) { 
						if(!in_array($value,$existingInstaller)){
						$insProjData['InstallerProjects']['installer_id']	= $value;
						$insProjData['InstallerProjects']['project_id']		= $project_id;
						$insProjData['InstallerProjects']['status']			= 4001;
						
						$insProjEntity 			= $this->InstallerProjects->newEntity($insProjData);
						$insProjEntity->created = $this->NOW();
						$this->InstallerProjects->save($insProjEntity);
					}
					}
					$custProjectData = $this->CustomerProjects
								    ->find('all')
								    ->select(['Customer.name','Parameter.para_value','Project.latitude','Project.longitude','Customer.mobile','Customer.email','Customer.city','Customer.state','Project.city','Project.state','Project.landmark','Project.name','Project.area','Project.avg_monthly_bill','Project.estimated_kwh_year','Project.backup_type','Project.usage_hours','Project.name','Project.estimated_cost','Project.estimated_cost_subsidy','Project.payback','Project.avg_generate','Project.recommended_capacity','Project.maximum_capacity'])
								    ->join([ 
										'Project' => [
								            'table' => 'projects',
								            'type' => 'INNER',
								            'conditions' => ['Project.id = CustomerProjects.project_id']
						            	],
						            	'Customer' => [
								            'table' => 'customers',
								            'type' => 'INNER',
								            'conditions' => ['Customer.id = CustomerProjects.customer_id']
						            	],
								       	'Parameter' => [
								            'table' => 'parameters',
								            'type' => 'LEFT',
								            'conditions' => ['Parameter.para_id = Project.customer_type']
						            	]])
								    ->where(array('CustomerProjects.project_id' =>$project_id))->first();
			
					$backup = (isset($custProjectData['Project']['backup_type'])?$custProjectData['Project']['backup_type']:'');
	            	$custProjectData['Project']['backup_type_name'] = '';
	            	if($backup == $this->Projects->BACKUP_TYPE_GENERATOR) {
	               		$custProjectData['Project']['backup_type_name'] = "Generator";
	            	}elseif($backup == $this->Projects->BACKUP_TYPE_INVERTER) {
	                	$custProjectData['Project']['backup_type_name'] = "Inverter";
	            	} else {
	                	$custProjectData['Project']['backup_type_name'] = "No";
	            	} 						    
					$installerData	= $this->Installers->find('all',array('conditions'=>array('id IN' =>$ins_ids)))->toArray();
					$this->sendQueryEmail($custProjectData, $installerData);
				
					/*Send Project Report PDF to Customer*/
					$this->sendMailToCustomer($project_id);
					$this->ApiToken->SetAPIResponse('type', 'ok');
					$this->ApiToken->SetAPIResponse('msg', 'Query send successfully.');
				}
				
			}
			
		} else {
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'Invalid request data.');
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	 *
	 * sendMailToCustomer
	 *
	 * Behaviour : Public
	 *	
	 * Parameter : projectId(int)
	 *
	 * @defination : Method is use to generate solar pdf report and send to customer.
	 *
	 */
	public function sendMailToCustomer($projectId='')
	{
		ini_set('max_execution_time', 300);
		$this->layout = false;
		$projectId = $projectId;
		$this->loadModel('Projects');
		$this->loadModel('InstallerProjects');

		/* Get project details */
		$project 	= $this->Projects->find('all',['join'=>[
					        'c' => [
					            'table' => 'customer_projects',
					            'type' => 'LEFT',
					            'conditions' => ['c.project_id = Projects.id']
			            	],
			            	'customer' => [
					            'table' => 'customers',
					            'type' => 'LEFT',
					            'conditions' => ['customer.id = c.customer_id']
			            	],
			            	'custtype' => [
					            'table' => 'parameters',
					            'type' => 'LEFT',
					            'conditions' => ['custtype.para_id = Projects.customer_type']
			            	]],
			            	'fields' => array('custtype.para_value','customer.name','customer.email','customer.mobile','customer.city','customer.state'),
			            	'conditions' => ['Projects.id' => $projectId],
			            	'order' => array('Projects.id' => 'DESC')])
							->autoFields(true)->first();
							
		/* Get all project installers */
		$projectInstallers 	= $this->InstallerProjects->find('all',['join'=>[
					        'installers' => [
					            'table' => 'installers',
					            'type' => 'LEFT',
					            'conditions' => ['InstallerProjects.installer_id = installers.id']
			            	]],
			            	'fields' => array('installers.installer_name','installers.address','installers.city','installers.state','installers.contact','installers.contact1'),
			            	'conditions' => ['InstallerProjects.project_id' => $projectId],
			            	'order' => array('installers.installer_name' => 'ASC')])->toArray();
		
		$ProjectEstimation 				 = $this->getProjectEstimationByPID($project->id);
		$solar_ratio 					 = $ProjectEstimation['solar_ratio'];
		$project->estimated_saving_month = $ProjectEstimation['saving_month'];
		$project->estimated_cost 		 = $ProjectEstimation['est_cost'];
		$project->estimated_cost_subsidy = $ProjectEstimation['est_cost_subsidy'] > 0?($ProjectEstimation['est_cost_subsidy']*100000):0;


		/* Generate map URL based on project location */
		$latLng = $project->latitude.",".$project->longitude;
		$mapUrl = 'https://maps.googleapis.com/maps/api/staticmap?center='.$latLng.'&maptype=hybrid&zoom=10&size=272x378&markers=color:blue%7C'.$latLng.'&sensor=false';
		$mapImage = file_get_contents($mapUrl);
		$mapImage = 'data:image/png;base64,' . base64_encode($mapImage);

		/* Radiation Graph Generate */
		$radiationGraphArr 	= $this->Projects->getSolarRediationGHIChartData($project->latitude,$project->longitude);
		$radiationGraphData['radiation_ghi_data'] = (!empty($radiationGraphArr['radiation_ghi_data'])?$radiationGraphArr['radiation_ghi_data']:array());
		if (!empty($radiationGraphData['radiation_ghi_data'])) {
			$radiationGraphImg = $this->radiationGraph($radiationGraphData);
		} else {
			$radiationGraphImg = "";
		}

		/* Energy and Month Saving Data */
		$solarRediationData 	= $this->GhiData->getGhiData($project->longitude,$project->latitude);
		$energyAndSavingDataArr = $this->Projects->getMonthEnergyAndSavingData($solarRediationData,$project->recommended_capacity,$project->avg_monthly_bill,$project->estimated_kwh_year);
		
		/* Solar PV Chart Data */
		$monthSavinData 	= (!empty($energyAndSavingDataArr['saving_data'])?$energyAndSavingDataArr['saving_data']:array());
		$monthly_saving 	= array_sum($monthSavinData);
		$estimated_cost_subsidy = isset($project->estimated_cost_subsidy)?round(($project->estimated_cost_subsidy/100000),2):$project->estimated_cost;
		$payBackGraphData 	= $this->Projects->GetPaybackChartData($estimated_cost_subsidy, ($monthly_saving));
		if (!empty($payBackGraphData)) {
			$paybackGraphImg 	= $this->paybackGraph($payBackGraphData);
		} else {
			$paybackGraphImg 	= "";
		}

		/* Get Environment Benefit Data. */
		$inpdataArr['recommendedCapacity'] = $project->estimated_kwh_year;
		$inpdataArr['estimatedKWHYear'] = $project->recommended_capacity;
		$environmentData = $this->Projects->calculateSolarPowerGreenSavingsData($inpdataArr);
		
		/* Get PDF Report id. */
		$projectData 	 = array();
		$projectReportId = $this->Projects->GetProjectPDFReportId($projectId);
		$hideInstaller   = 0;
		$this->set(compact("project","projectData","projectInstallers","mapImage","radiationGraphImg","paybackGraphImg","radiationGraphData","energyAndSavingDataArr","projectReportId","environmentData","hideInstaller","monthly_saving","solar_ratio","ProjectEstimation"));
		$this->set('pageTitle','Project');

		/* Generate PDF for estimation of project */
		require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
		$dompdf = new Dompdf($options = array());
		$dompdf->set_base_path(SITE_ROOT_DIR_PATH.'/pdf/');
		$dompdf->set_option('defaultFont', "Helvetica");
		$html = $this->render('project_estimation');
		//exit($html);
	  	$dompdf->loadHtml($html);
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();
		// Output the generated PDF to Browser
		//$dompdf->stream("project_estimation");
		$output = $dompdf->output();
		$pdfPath = SITE_ROOT_DIR_PATH.'/tmp/report-'.$projectReportId.'.pdf';
    	file_put_contents($pdfPath, $output);
		//exit;
		$to		= $project->customer['email'];
		//$to			= 'khushal@yugtia.com';
		$subject	= "Project Query Report";
		$email 		= new Email('default');
	 	$email->profile('default');

		$email->viewVars(array('project_detail' => $project, 'installer_list' => $projectInstallers));			
		$email->template('send_query_report', 'default')
				->emailFormat('html')
				->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
			    ->to($to)
			    ->attachments($pdfPath)
			    ->subject(Configure::read('EMAIL_ENV').$subject)
			    ->send();
	}

	/**
	 *
	 * energyGraph
	 *
	 * Behaviour : Public
	 *	
	 * Parameter : gData(int)
	 *
	 * @defination : Method is use to generate energy graph.
	 *
	 */
	public function energyGraph($gData = array())
	{
		require_once(ROOT . DS  . 'vendor' . DS  . 'jpgraph' . DS . 'src' . DS . 'jpgraph.php');
		require_once(ROOT . DS  . 'vendor' . DS  . 'jpgraph' . DS . 'src' . DS . 'jpgraph_bar.php');		
 		
		$ydata = (isset($gData['energy_data'])?array_values($gData['energy_data']):array());

		// Create the graph. 
		$graph = new \Graph(600,350,'auto');
		$graph->img->SetMargin(30,90,40,50);
		$graph->title->Set("Solar Energy Generation in kWh");
		$graph->SetScale("textlin");
		$graph->SetBox(false);

		//$graph->ygrid->SetColor('gray');
		$graph->ygrid->Show(false);
		$graph->ygrid->SetFill(false);
		
		$graph->yaxis->HideLine(false);
		$graph->yaxis->HideTicks(false,false);

		// For background to be gradient, setfill is needed first.
		$graph->SetBackgroundGradient('#FFFFFF', '#FFFFFF', GRAD_HOR, BGRAD_PLOT);

		// Create the bar plots
		$barplot = new \BarPlot($ydata);
		$graph->Add($barplot);
		$barplot->SetWeight(0);
		$barplot->SetFillGradient("#71BF57","#71BF57",GRAD_HOR);
		$barplot->SetWidth(17);

		// Display the graph
		$filepath = WWW_ROOT."/tmp/energy".time().".png";
		$graphData = $graph->Stroke($filepath); 
		return $filepath;
	}

	/**
	 *
	 * paybackGraph
	 *
	 * Behaviour : Public
	 *	
	 * Parameter : gData(int)
	 *
	 * @defination : Method is use to generate payback graph.
	 *
	 */
	public function paybackGraph($gData = array())
	{
		require_once(ROOT . DS  . 'vendor' . DS  . 'jpgraph' . DS . 'src' . DS . 'jpgraph.php');
		require_once(ROOT . DS  . 'vendor' . DS  . 'jpgraph' . DS . 'src' . DS . 'jpgraph_scatter.php');		
 		
		$ydata = (isset($gData)?array_values($gData):array());
		$xdata = (isset($gData)?array_keys($gData):array());

		// Create the graph. 
		$graph = new \Graph(700,350,'auto');
		$graph->img->SetMargin(80,90,40,50);
		$graph->title->Set("");
		$graph->SetScale("intlin");
		$graph->SetShadow();
		$graph->SetBox(false);
		$graph->title->Set("Solar Payback");
		$graph->xaxis->SetPos("min"); 

		$graph->yaxis->SetLabelMargin(12);
		$graph->xaxis->SetLabelMargin(6);
		$graph->xaxis->SetTickLabels($xdata);
		$graph->yaxis->SetTickSide(SIDE_LEFT);
		$graph->xaxis->SetTickSide(SIDE_DOWN);
		// For background to be gradient, setfill is needed first.
		$graph->SetBackgroundGradient('#FFFFFF', '#FFFFFF', GRAD_HOR);

		$lineplot = new \ScatterPlot($ydata);
		$lineplot->mark->SetType(MARK_SQUARE);
		$lineplot->mark->SetFillColor("#FFCB29");
		$lineplot->SetImpuls();
		$lineplot->SetColor("#71BF57");
		$lineplot->SetWeight(6);
		$lineplot->mark->SetWidth(10);
		$graph->Add($lineplot);
		
		// Display the graph
		$filepath = WWW_ROOT."/tmp/payback".time().".png";
		$graphData = $graph->Stroke($filepath);
		return $filepath;
	}

	/**
	 *
	 * radiationGraph
	 *
	 * Behaviour : Public
	 *	
	 * Parameter : gData(int)
	 *
	 * @defination : Method is use to generate solar radiation GHI graph.
	 *
	 */
	public function radiationGraph($gData = array())
	{
		require_once(ROOT . DS  . 'vendor' . DS  . 'jpgraph' . DS . 'src' . DS . 'jpgraph.php');
		require_once(ROOT . DS  . 'vendor' . DS  . 'jpgraph' . DS . 'src' . DS . 'jpgraph_bar.php');		
 		
 		$ydata = (isset($gData['radiation_ghi_data'])?array_values($gData['radiation_ghi_data']):array());
		// Create the graph. 
		$graph = new \Graph(550,350,'auto');
		$graph->img->SetMargin(30,90,40,50);
		$graph->title->Set("Monthly Solar Radiation GHI (kW h/m2)");
		$graph->SetScale("textlin");
		$graph->SetBox(false);

		//$graph->ygrid->SetColor('gray');
		$graph->ygrid->Show(false);
		$graph->ygrid->SetFill(false);
		
		$graph->yaxis->HideLine(false);
		$graph->yaxis->HideTicks(false,false);

		// For background to be gradient, setfill is needed first.
		$graph->SetBackgroundGradient('#FFFFFF', '#FFFFFF', GRAD_HOR, BGRAD_PLOT);

		// Create the bar plots
		$barplot = new \BarPlot($ydata);
		$graph->Add($barplot);
		$barplot->SetWeight(0);
		$barplot->SetFillGradient("#71BF57","#71BF57",GRAD_HOR);
		$barplot->SetWidth(17);

		// Display the graph
		$filepath = WWW_ROOT."/tmp/radiation".time().".png";
		$graphData = $graph->Stroke($filepath); 
		return $filepath;
	}

	/**
	 *
	 * sendQueryEmail
	 *
	 * Behaviour : Public
	 *		
	 * Parameter : $projectDetail(array), $installerList(array)	
	 *
	 * @defination : Method is use to send query email.
	 *
	 */
	public function sendQueryEmail($projectDetail, $installerList)
	{
		if(!empty($projectDetail) && !empty($installerList)) { 
			
			$projectState = (isset($projectDetail['Project']['state'])?$projectDetail['Project']['state']:'');
			if(!empty($projectState) && strtolower($projectState) == 'jharkhand'){
				$to			= array(SEND_QUERY_EMAIL,SEND_QUERY_EMAIL_JHARKHAND);
			}else{
				$to			= array(SEND_QUERY_EMAIL);
			}
			 //SEND_QUERY_EMAIL;
			$subject	= "Project Query";
			$email 		= new Email('default');
		 	$email->profile('default');

			$email->viewVars(array('project_detail' => $projectDetail, 'installer_list' => $installerList));			
			$email->template('send_query', 'default')
				->emailFormat('html')
				->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
			    ->to($to)
			    ->subject(Configure::read('EMAIL_ENV').$subject)
			    ->send();
		}
	}

	/**
	 *
	 * ProfessionalVersionRegistration
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use for Manage Subscription
	 *
	 * Author : Pravin Sanghani
	 */

	function managesubscriptions($id=null)
	{
		$profileImage = '';
		
		if(empty($id)){
			$this->intCurAdminUserRight = $this->Userright->ADD_SUBSCRIPTION;
			$this->setAdminArea();
			$customerEntity 			= $this->CustomerSubscription->newEntity($this->request->data());
			$pageTitle = 'Add customer';
		}else{
			$id=intval(decode($id));
			$this->intCurAdminUserRight = $this->Userright->EDIT_SUBSCRIPTION;
			$this->setAdminArea();
			$customerData 				= $this->CustomerSubscription->get($id);
			$profileImage = $customerData['profile_image'];
			$customerEntity 			= $this->CustomerSubscription->patchEntity($customerData,$this->request->data());
			$pageTitle = 'Edit customer';
		}
		$arrAdminDefaultRights = array();
		$timezone = '';
		$arrError = array();
		if (!$customerEntity->errors() && !empty($this->request->data)) {
			$customerEntity->created 	= $this->NOW();
			$customerEntity->created_by 	= $this->Session->read('User.id');
			if ($this->CustomerSubscription->save($customerEntity)) {
				
				if(empty($id)) {
					$this->writeadminlog($this->Session->read('User.id'),33,$customerEntity->id,'Added Subscription id::'.$customerEntity->id);
					$this->Flash->set('Subscription added successfully.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
					return $this->redirect(array('action'=>'subscriptions'));
				}
				else
				{
					$this->writeadminlog($this->Session->read('User.id'),34,$customerEntity->id,'Added Subscription id::'.$customerEntity->id);
					$this->Flash->set('Subscription updated successfully.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
					return $this->redirect(array('action'=>'subscriptions'));
				}
				
			}
		}
		$insList = $this->Installers->find('list', ['keyField' => 'id','valueField' => 'installer_name'])->order('installer_name')->toArray();
		$subList = $this->Subscription->find("all",array("conditions"=>array("Subscription.status"=>1)))->toArray();
		$subscriptionData = array();
		foreach ($subList as $subkey => $subVal) {

			$subscriptionData[$subVal['id']]= $subVal['lable'];
			
		}
		$this->set('insList', $insList);
		$this->set('subscriptionData', $subscriptionData);
		$this->set('data',	$this->request->data);
		$this->set('CustomerSubscription',	$customerEntity);
		$this->set('title', $pageTitle);
	}
	

	public function subscriptions() 
	{
		$this->intCurAdminUserRight = $this->Userright->LIST_CUSTOMER;
		$this->setAdminArea();	
		$arrcustomerList	= array();
		$arrCondition		= array();
		$this->SortBy		= "CustomerSubscription.id";
		$this->Direction	= "ASC";
		$this->intLimit		= PAGE_RECORD_LIMIT;
		$this->CurrentPage  = 1;
		$option 			= array();
		$option['colName']  = array('id','installer_id','subscription_id','payment_request_id','txn_id','payment_status','payment_mode','action');
		
		$subList = $this->Subscription->find("all",array("conditions"=>array("Subscription.status"=>1)))->toArray();
		$subscriptionData = array();
		foreach ($subList as $subkey => $subVal) {

			$subscriptionData[$subVal['id']]= $subVal['lable'];
			
		}

		$this->SetSortingVars('CustomerSubscription',$option);
		$arrCondition		= $this->_generateCustomerSubscriptionSearchCondition();
		
		/*$arrCondition['between'] = ["Users.lastlogin","2015-10-01 01:00:00","2015-10-02 23:59:59"];*/
		$this->paginate		= array('fields'=>array('CustomerSubscription.id','i.installer_name','s.lable','CustomerSubscription.payment_request_id','CustomerSubscription.txn_id','CustomerSubscription.payment_status','CustomerSubscription.payment_mode','CustomerSubscription.status'),'join'=>[
			                        'i' => [
			                            'table' => 'installers',
			                            'type' => 'INNER',
			                            'conditions' => ['i.id = CustomerSubscription.installer_id']
			                        ],'s' => [
			                            'table' => 'subscription',
			                            'type' => 'INNER',
			                            'conditions' => ['s.id = CustomerSubscription.subscription_id']
			                        ]

			                        ],
									'conditions' => $arrCondition,
									'order'=>array($this->SortBy=>$this->Direction),
									'page'=> $this->CurrentPage,
									'limit' => PAGE_RECORD_LIMIT);
		$arrcustomerList	= $this->paginate('CustomerSubscription');
		$arrUserType['']	= "Select";
		
		
		
		//$usertypes = $this->Userrole->getAdminuserRoles();
		$usertypes = array();
		//foreach($usertypes as $key=>$value) $arrUserType[$key] = $value;

		$option['dt_selector']	='table-example';
		$option['formId']		='formmain';
		$option['url']			= WEB_ADMIN_PREFIX.'installers/subscriptions';
		$JqdTablescr 			= $this->JqdTable->create($option);
		$this->set('arrcustomerList',$arrcustomerList->toArray());
		$this->set('JqdTablescr',$JqdTablescr);
		$this->set('arrUserType',$arrUserType);
		$this->set('period',$this->period);
		$this->set('limit',$this->intLimit);
		$this->set("CurrentPage",$this->CurrentPage);
		$this->set("SortBy",$this->SortBy);
		$this->set("Direction",$this->Direction);
		$this->set("page_count",(isset($this->request->params['paging']['CustomerSubscription']['pageCount'])?$this->request->params['paging']['CustomerSubscription']['pageCount']:0));
		$out = array();
		
		/*$blnEditAdminuserRights		= $this->Userright->checkadminrights($this->Userright->ANALYSTS_EDIT);
		$blnEnableAdminuserRights	= $this->Userright->checkadminrights($this->Userright->ANALYSTS_ENABLE);	
		$blnDisableAdminuserRights	= $this->Userright->checkadminrights($this->Userright->ANALYSTS_DISABLE);*/
		//pr($arrcustomerList->toArray()); exit;
		$blnEditCustomersRights		= $this->Userright->checkadminrights($this->Userright->EDIT_CUSTOMER);
		foreach($arrcustomerList->toArray() as $key => $val) {
			$temparr = array();
			foreach($option['colName'] as $key) {
				if($key == 'installer_id'){
					$temparr[$key] = $val['i']['installer_name'];
				}else if($key == 'subscription_id'){
					$temparr[$key] = $val['s']['lable'];
				}else if(isset($val[$key])) {
					$temparr[$key] = $val[$key];
				} else {
					$temparr[$key]='';
				}
				if($key == 'action') {
					if($key=='action') {
						$temparr['action']='';
						$temparr['action'].= $this->Userright->linkEditAdminuser(constant('WEB_URL').constant('ADMIN_PATH').'installers/managesubscriptions/'.encode($val['id']),'<i class="fa fa-edit"> </i>','','viewRecord',' title="View Customer Info"')."&nbsp;";
					}
				}		
			}
			$out[] = $temparr;
		}
		if ($this->request->is('ajax')){
			header('Content-type: application/json');
			echo json_encode(array('condi'=>$arrCondition,"draw" => intval($this->request->data['draw']),
			"recordsTotal"    => intval( $this->request->params['paging']['CustomerSubscription']['count']),
			"recordsFiltered" => intval( $this->request->params['paging']['CustomerSubscription']['count']),
			"data"            => $out));
			die;
		}
	}

	/**
	 *
	 * professionalVersionRegistrationV1
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use for professional version registration.
	 *
	 * Author : Khushal Bhalsod
	 */
	public function professionalVersionRegistrationV1()
	{
		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		$customer_id 		= $this->ApiToken->customer_id;
		if(!empty($customer_id)) { 
			$installercnt	  	= 0;
			$customerdetail 	= array();
			$customerdetail 	= $this->Customers->get($customer_id);
			if(isset($this->request->data['Installers']['sub_user_code']) && $this->request->data['Installers']['sub_user_code']!='') 
			{
				$sub_user_code		= $this->request->data['Installers']['sub_user_code']; 	
				$installerCount 	= $this->Installers->find('all', array('conditions'=>array('sub_user_code'=>$sub_user_code)))->count();
				$installercnt 		= $this->Installers->find('all', array('conditions'=>array('sub_user_code'=>$sub_user_code)))->toArray();
				$customerdetail['installer_id'] 		= $installercnt[0]->id;	
				$customerdetail['customer_parent_id']	= $installercnt[0]->customer_id;	
				$customerdetail['activation_code']		= $this->request->data['Installers']['sub_user_code'];
				$custPatchEntity 						= $this->Customers->patchEntity($customerdetail,$this->request->data);
				unset($custPatchEntity->password); 
				$this->Customers->save($custPatchEntity);
			} else {
				$installersEntity 				= $this->Installers->newEntity($this->request->data());
				$installersEntity->created 		= $this->NOW();
				$installersEntity->customer_id 	= $customer_id;
				$installersEntity->email 		= (isset($customerdetail['email'])?$customerdetail['email']:'');
				$installersEntity->mobile 		= (isset($customerdetail['mobile'])?$customerdetail['mobile']:'');
				if($installercnt == 0) 
				{
					if ($this->Installers->save($installersEntity)) {
						$customerdetail['installer_id'] 		= $installersEntity->id;
						$custPatchEntity 						= $this->Customers->patchEntity($customerdetail,$this->request->data);
						unset($custPatchEntity->password);
						$this->Customers->save($custPatchEntity);
						$status	= 'ok';
						$this->ApiToken->SetAPIResponse('type', $status);
						$this->ApiToken->SetAPIResponse('ins_id', $installersEntity->id);				
					} else {
						$status	= 'error';
						$error	= 'Registration fail.';
						$this->ApiToken->SetAPIResponse('type', $status);
						$this->ApiToken->SetAPIResponse('msg', $error);				
					}
				} else {
					$status	= 'error';
					$error	= 'This email is already registered.';
					$this->ApiToken->SetAPIResponse('type', $status);
					$this->ApiToken->SetAPIResponse('msg', $error);
				}
			}
		} else {
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'Invalid request.');
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	 *
	 * ProfessionalVersionRegistration
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use for professional version registration.
	 *
	 * Author : Khushal Bhalsod
	 */
	public function professionalVersionRegistration()
	{
		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		$customer_id = $this->ApiToken->customer_id;
		if(!empty($customer_id)) {
			$sub_user_code 	= isset($this->request->data['Installers']['sub_user_code']) && !empty($this->request->data['Installers']['sub_user_code']!='')?$this->request->data['Installers']['sub_user_code']:"";
			$company_id 	= isset($this->request->data['Installers']['company_id']) && !empty($this->request->data['Installers']['company_id']!='')?$this->request->data['Installers']['company_id']:"";
			$company_name 	= isset($this->request->data['Installers']['installer_name']) && !empty($this->request->data['Installers']['installer_name']!='')?$this->request->data['Installers']['installer_name']:"";
			if (empty($sub_user_code))
			{
				$installercnt	  						= 0;
				$customerdetail 						= array();
				$customerdetail 						= $this->Customers->get($customer_id);
				
				if (!empty($company_id)) {

					$installer 	= $this->Installers->find('all',array('conditions'=>array('company_id'=>$company_id)))->first();
					$Company = $this->Company->find('all',array('conditions'=>array('id'=>$company_id)))->first();
					$installer_name = $Company->company_name;
					if(!empty($installer))
					{
						$cInstaller 				= $this->Installers->get($installer->id); 
						$installersEntity 			= $this->Installers->patchEntity($cInstaller,$this->request->data());
						$installer_name = $installer->installer_name;
					}
					else
					{
						$installersEntity 			= $this->Installers->newEntity($this->request->data());
					}
					
                    if (!empty($Company)) {
						$installersEntity->company_id 			= $company_id;
						$installersEntity->installer_name 		= $installer_name;
					} else {
						$arrCompany['Company']['company_name']	= $company_name;
						$CompanyEntity 			= $this->Company->newEntity($arrCompany);
						$CompanyEntity->created = $this->NOW();
						$CompanyEntity->updated = $this->NOW();
						if ($this->Company->save($CompanyEntity)) {
							$installersEntity->company_id 		= $CompanyEntity->id;
							$installersEntity->installer_name 	= $company_name;
						} else {
							$installersEntity->company_id 		= 0;
							$installersEntity->installer_name 	= $company_name;
						}
					}
				}
				else
				{
					$installersEntity 						= $this->Installers->newEntity($this->request->data());
					$arrCompany['Company']['company_name']	= $company_name;
					$CompanyEntity 			= $this->Company->newEntity($arrCompany);
					$CompanyEntity->created = $this->NOW();
					$CompanyEntity->updated = $this->NOW();
					if ($this->Company->save($CompanyEntity)) {
						$installersEntity->company_id 		= $CompanyEntity->id;
						$installersEntity->installer_name 	= $company_name;
					} else {
						$installersEntity->company_id 		= 0;
						$installersEntity->installer_name 	= $company_name;
					}
				}
				$installersEntity->created 				= $this->NOW();
				$installersEntity->modified 			= $this->NOW();
				$installersEntity->customer_id 			= $customer_id;
				$installersEntity->email 				= (isset($customerdetail['email'])?$customerdetail['email']:'');
				$installersEntity->mobile 				= (isset($customerdetail['mobile'])?$customerdetail['mobile']:'');
				$installersEntity->installer_plan_id 	= $this->InstallerPlans->DEFAULT_PLAN_ID;
				$installercnt 							= $this->Installers->find('all',array('conditions'=>array('customer_id'=>$customer_id)))->count();
				$planId 								= $this->InstallerPlans->DEFAULT_PLAN_ID; //$this->request->data['Installers']['installer_plan_id'];
				$insplanData 							= $this->InstallerPlans->get($planId);
				if($installercnt == 0) {
					if ($this->Installers->save($installersEntity)) {
						/* Send worker activation codes email */
						$coupan_code = (isset($this->request->data['Installers']['coupan_code'])?$this->request->data['Installers']['coupan_code']:'');
						$Amount_Paid = $insplanData['plan_price'];
						if($coupan_code != '')
						{
						$installerCupn = $this->InstallersCoupan->find('all', array('conditions'=>array('coupan_code'=>$coupan_code)))->toArray();
						if(!empty($installerCupn)){
							$coupanObj = $installerCupn[0];
							if(empty($coupanObj->is_flat) || $coupanObj->is_flat == 0)
							{
								$Amount_Paid = $insplanData['plan_price'] - (($insplanData['plan_price'] * $coupanObj->amount)/100);
							} else {
								$Amount_Paid = $insplanData['plan_price'] - $coupanObj->amount;
							}
						}
						}
						if(isset($insplanData['user_limit']) && $insplanData['user_limit'] > 0) { 
							if($Amount_Paid<='0')
							{
								$InstallerSubscriptionEntity                    = $this->InstallerSubscription->newEntity();
						        $InstallerSubscriptionEntity->payment_status    = '';
						        $InstallerSubscriptionEntity->installer_id      = $installersEntity->id;
						        $InstallerSubscriptionEntity->coupen_code       = $coupan_code;
						        $InstallerSubscriptionEntity->transaction_id    = '';
						        $InstallerSubscriptionEntity->created           = $this->NOW();
						        $InstallerSubscriptionEntity->modified          = $this->NOW();
						        $InstallerSubscriptionEntity->payment_gateway   = '';
						        $InstallerSubscriptionEntity->comment           = '100% Discount';
						        $InstallerSubscriptionEntity->payment_data      = '';
						        
						        $arr_ins_details                                = $this->Installers->find('all',array('conditions'=>array('id'=>$InstallerSubscriptionEntity->installer_id)))->toArray();
						        $plan_id                                        = $arr_ins_details[0]['installer_plan_id'];
						        $arr_plan_details                               = $this->InstallerPlans->find('all',array('conditions'=>array('id'=>$plan_id)))->toArray();
						        $InstallerSubscriptionEntity->amount    		= '0';
			                    $InstallerSubscriptionEntity->coupen_id 		= '0';
			                    $InstallerSubscriptionEntity->is_flat   		= '0';
						        if($coupan_code!='')
						        {
						            $installerCupn                              = $this->InstallersCoupan->find('all', array('conditions'=>array('coupan_code'=>$coupan_code)))->toArray();
						            if(!empty($installerCupn)){
						                $coupanObj                              = $installerCupn[0];
						                $InstallerSubscriptionEntity->amount    = $coupanObj->amount;
						                $InstallerSubscriptionEntity->coupen_id = $coupanObj->id;
						                $InstallerSubscriptionEntity->is_flat   = $coupanObj->is_flat;
						            }
						        }
						        $InstallerSubscriptionEntity->plan_name         = $arr_plan_details[0]['plan_name'];
						        $InstallerSubscriptionEntity->plan_price        = $arr_plan_details[0]['plan_price'];
						        $InstallerSubscriptionEntity->plan_id           = $plan_id;
						        $InstallerSubscriptionEntity->user_limit        = $arr_plan_details[0]['user_limit'];
						        $InstallerSubscriptionEntity->start_date        = date('Y-m-d');
						        $InstallerSubscriptionEntity->expire_date       = date('Y-m-d',strtotime("+ 30 days"));
						        $InstallerSubscriptionEntity->status            = '1';
						        $InstallerSubscriptionEntity->created_by        = $arr_ins_details[0]['customer_id'];
						        $InstallerSubscriptionEntity->modified_by       = $arr_ins_details[0]['customer_id'];
						        $this->InstallerSubscription->save($InstallerSubscriptionEntity);
								$insCodeArr = array();
								for ($i=0; $i < $insplanData['user_limit']; $i++) {
									$activation_codes = $this->Installers->generateInstallerActivationCodes();
									$insCodeArr[] 												= $activation_codes;
									$insCodedata['InstallerActivationCodes']['installer_id']	= $installersEntity->id;
									$insCodedata['InstallerActivationCodes']['activation_code']	= $activation_codes;
									$insCodedata['InstallerActivationCodes']['start_date']      = date('Y-m-d');
                        			$insCodedata['InstallerActivationCodes']['expire_date']     = date('Y-m-d',strtotime("+ 30 days"));
									$insCodeEntity = $this->InstallerActivationCodes->newEntity($insCodedata);
									$this->InstallerActivationCodes->save($insCodeEntity);
								}
								$this->Customers->updateAll(['user_role'=>$this->Parameters->admin_role,'default_admin'=>1,'installer_id' => $installersEntity->id,'modified' => $this->NOW()], ['id' => $customer_id]);
								$this->SendProfessionalRegistrationNotificationEmail($installersEntity->id, $insCodeArr);
								$status	= 'ok';
								$this->ApiToken->SetAPIResponse('type', $status);
								$this->ApiToken->SetAPIResponse('ins_id', $installersEntity->id);
							}
							else
							{
								$status	= 'payment';
								$this->ApiToken->SetAPIResponse('type', $status);
								$this->ApiToken->SetAPIResponse('ins_id', encode($installersEntity->id));
								$this->ApiToken->SetAPIResponse('coupan_code', $coupan_code);
								
							}
						}				
					} else {
						$status	= 'error';
						$error	= 'Registration fail.';
						$this->ApiToken->SetAPIResponse('type', $status);
						$this->ApiToken->SetAPIResponse('msg', $error);				
					}
				} else {
					$Installer = $this->Installers->find('all',array('conditions'=>array('customer_id'=>$customer_id)))->first();
					$insCodeArr = array();
					if ($Installer) {
						$Codes = $this->InstallerActivationCodes->find('all',array('conditions'=>array('installer_id'=>$Installer->id)))->toArray();
						foreach($Codes as $Code) {
							$insCodeArr[] = $Code->activation_code;
						}
						$this->SendProfessionalRegistrationNotificationEmail($Installer->id, $insCodeArr);
					}
					$status	= 'error';
					$error	= 'This email is already registered.';
					$this->ApiToken->SetAPIResponse('type', $status);
					$this->ApiToken->SetAPIResponse('msg', $error);
				}
			} else {
				$Codes 			= $this->InstallerActivationCodes->find('all',array('conditions'=>array('activation_code'=>$sub_user_code)))->toArray();
				$installer_id 	= 0;
				$code_id 		= 0;
				if (!empty($Codes)) {
					foreach($Codes as $Code) {
						$is_used 		= $Code->is_used;
						$installer_id 	= $Code->installer_id;
						$code_id 		= $Code->id;
						$expire_date 	= $Code->expire_date;
					}
					if ($is_used || empty($installer_id)) {
						$status	= 'error';
						if ($is_used) {
							$error	= 'Activation code has already been used by another user.';
						} else {
							$error	= 'Please contact Administrator user, Error while validating activation code.';
						}
						$this->ApiToken->SetAPIResponse('type', $status);
						$this->ApiToken->SetAPIResponse('msg', $error);
					} else {
						$Installer 		= $this->Installers->find('all',array('conditions'=>array('id'=>$installer_id)))->first();
						$installercnt	= 0;
						$customerdetail = array();
						$customerdetail = $this->Customers->get($customer_id);
						$NewRecord 		= true;
						if ($customerdetail->installer_id == 0) {
							$installersEntity 			= $this->Installers->newEntity($this->request->data());
							$installersEntity->created 	= $this->NOW();
						} else {
							$cInstaller 				= $this->Installers->get($customerdetail->installer_id); 
							$installersEntity 			= $this->Installers->patchEntity($cInstaller,$this->request->data());
							$installersEntity->modified = $this->NOW();
							$NewRecord 					= false;
						}
						$installersEntity->customer_id 			= $Installer->customer_id;
						$installersEntity->company_id 			= $Installer->company_id;
						$installersEntity->installer_name 		= $Installer->installer_name;
						$installersEntity->email 				= (isset($customerdetail['email'])?$customerdetail['email']:'');
						$installersEntity->mobile 				= (isset($customerdetail['mobile'])?$customerdetail['mobile']:'');
						$installersEntity->installer_plan_id 	= $Installer->installer_plan_id;
						if ($this->Installers->save($installersEntity)) {
							$this->Customers->updateAll(['user_role'=>'no_role','installer_id' => $Installer->id,'modified' => $this->NOW(), 'start_date' => date('Y-m-d'), 'expire_date' => $expire_date], ['id' => $customer_id]);
							$this->InstallerActivationCodes->updateAll(['is_used' =>1], ['id' => $code_id]);
							if ($NewRecord) {
								$this->Installers->updateAll(['sub_users' =>($Installer->sub_users+1)], ['id' => $installer_id]);
							}
							$status	= 'ok';
							$msg	= 'User associated successfully with '.$Installer->installer_name.'.';
							$this->ApiToken->SetAPIResponse('type', $status);
							$this->ApiToken->SetAPIResponse('msg', $msg);
							$this->ApiToken->SetAPIResponse('ins_id', $installersEntity->id);	
						} else {
							$status	= 'error';
							$error	= 'Error while activating User. Please contact administrator.';
							$this->ApiToken->SetAPIResponse('type', $status);
							$this->ApiToken->SetAPIResponse('msg', $error);		
						}
					}
				} else {
					$status	= 'error';
					$error	= 'Invalid activation code.';
					$this->ApiToken->SetAPIResponse('type', $status);
					$this->ApiToken->SetAPIResponse('msg', $error);
				}
			}
		} else {
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'Invalid request.');
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	* SendProfessionalRegistrationNotificationEmail
	* Behaviour : public
	* @defination : Method is used to send registration email.
	* Author : Khushal Bhalsod
	*/
	public function SendProfessionalRegistrationNotificationEmail($insId, $insCodeArr)
	{
		if(!empty($insId) && !empty($insCodeArr)) {
			$insData = $this->Installers->get($insId);
			if(!empty($insData['email'])) { 	
				$to			= $insData['email'];
				$bcc 		= "kalpak@yugtia.com";
				$subject	= PRODUCT_NAME." Registration";
				$email 		= new Email('default');
			 	$email->profile('default');
				$email->viewVars(array('insData' => $insData,'insCodeArr'=>$insCodeArr));			
				$email->template('professional_registration', 'default')
						->emailFormat('html')
						->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
					    ->to($to)
						->subject(Configure::read('EMAIL_ENV').$subject)
					    ->send();
			}
		}
	}

	/**
	*
	* getInstallerProfile
	*
	* Behaviour : public
	*
	* @defination : Method is used to get the Installer profile.
	*
	* Author : Khushal Bhalsod
	*/
	public function getInstallerProfile() 
	{
		$this->autoRender = false;
		$this->SetVariables($this->request->data);
		$cus_id		= $this->ApiToken->customer_id;
		$insData 	= array();
		if(!empty($cus_id)) {
			$insData 	= $this->Installers
									->find('all')
								    ->select(['Customer.name','Installers.id','Installers.installer_name','Installers.mobile','Installers.installer_plan_id','Installers.about_installer','Installers.address','Installers.profile_pic','InstallerPlan.plan_name','Company.company_name'])
								    ->join([
										'Company' => [
								            'table' => 'companies',
								            'type' => 'LEFT',
								            'conditions' => ['Company.id = Installers.company_id']
						            	],
										'Customer' => [
								            'table' => 'customers',
								            'type' => 'INNER',
								            'conditions' => ['Customer.installer_id = Installers.id']
						            	],
						            	'InstallerPlan' => [
								            'table' => 'installer_plans',
								            'type' => 'LEFT',
								            'conditions' => ['InstallerPlan.id = Installers.installer_plan_id']
						            	]])
								    ->where(array('Customer.id' => $cus_id))->first();
			$arrReturn['company_name'] 		= (isset($insData['company_name'])?$insData['company_name']:'');
			$arrReturn['company_name'] 		= (empty($insData['company_name'])?$insData['installer_name']:$insData['company_name']);
			$arrReturn['installer_name'] 	= (isset($insData['installer_name'])?$insData['installer_name']:'');
			$arrReturn['mobile'] 			= (isset($insData['mobile'])?$insData['mobile']:'');
			$arrReturn['active_plan_id'] 	= (isset($insData['installer_plan_id'])?$insData['installer_plan_id']:'');
			$arrReturn['active_plan_name'] 	= (isset($insData['InstallerPlan']['plan_name'])?$insData['InstallerPlan']['plan_name']:'');
			$arrReturn['about_company'] 	= (isset($insData['about_installer'])?$insData['about_installer']:'');
			$arrReturn['company_address'] 	= (isset($insData['address'])?$insData['address']:'');
			if (isset($insData['profile_pic']) && !empty($insData['profile_pic'])) {
				if (file_exists(INSTALLER_PROFILE_PATH.$insData['id'].'/'.$insData['profile_pic'])) {
					$arrReturn['logo'] = INSTALLER_PROFILE_URL.$insData['id'].'/'.$insData['profile_pic'];
				} else {
					$arrReturn['logo'] = IMAGE_URL."default-img.png";	
				}
			} else {
				$arrReturn['logo'] = IMAGE_URL."default-img.png";
			}
			$this->ApiToken->SetAPIResponse('type', 'ok');
			$this->ApiToken->SetAPIResponse('result', $arrReturn);
		} else {
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'Invalid Request.');
		}		
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	*
	* getInstallerProjectLead
	*
	* Behaviour : public
	*
	* @defination : Method is used to get the Installer project.
	*
	* Author : Khushal Bhalsod
	*/
	public function getInstallerProjectLead()
	{
		$this->autoRender = false;
		$this->SetVariables($this->request->data);
		$cus_id				= $this->ApiToken->customer_id;
		$customerdata		= $this->Customers->find('all', array('fields'=>['id','installer_id'],'conditions'=>array('id'=>$cus_id)))->first();
		$installer_id		= isset($customerdata['installer_id'])?$customerdata['installer_id']:0;
		/*$installerdata		= $this->Installers->find('all', array('conditions'=>array('id'=>$installerid)))->first();
		$installer_id 		= (isset($installerdata['id'])?$installerdata['id']:0);*/
		$installer 		= array();
		$condition 		= array('InstallerProjects.installer_id' =>$installer_id,'InstallerProjects.status'=>'4001');
		$condition[0]       = 'Project.name is not null';
        $condition['Project.name != '] = '';
		if(isset($this->request->data['Installers']['search_area']) && !empty($this->request->data['Installers']['sub_user_code']))
		{
			$condition = array('Project.area Like' =>"%".$this->request->data['Installers']['search_area']."%",'InstallerProjects.installer_id' =>$installer_id,'InstallerProjects.status'=>'4001');	
		}
		if(isset($this->request->data['Installers']['customer_type']) && !empty($this->request->data['Installers']['customer_type']))
		{
			$condition = array('Project.customer_type' => $this->request->data['Installers']['customer_type'],'InstallerProjects.installer_id' =>$installer_id,'InstallerProjects.status'=>'4001');	
		}
		if(!empty($installer_id)) {
			$insLeadArr 	= $this->InstallerProjects
									->find('all')
								    ->select(['Project.id','Project.name','Project.latitude','Project.longitude','Project.address','Project.city','Project.created','Project.recommended_capacity','Parameter.para_value','Customer.name','Project.state'])
								    ->join([ 
										'Project' => [
								            'table' => 'projects',
								            'type' => 'INNER',
								            'conditions' => ['Project.id = InstallerProjects.project_id']
						            	],
						            	'Customer' 	=> [
						                            'table' => 'customers',
						                            'type' => 'LEFT',
						                            'conditions' => ['Project.created_by = Customer.id']],
						            	'Parameter' => [
								            'table' => 'parameters',
								            'type' => 'INNER',
								            'conditions' => ['Parameter.para_id = Project.customer_type']
						            	]])
								    ->where($condition)
								    ->order(array('Project.id'=>'DESC'))->toArray();
			
			if(!empty($insLeadArr)) {
				foreach($insLeadArr as $key=>$value) { 
					$installer[$key]['id']				= (isset($insLeadArr[$key]['Project']['id'])?$insLeadArr[$key]['Project']['id']:'');
					$installer[$key]['name']			= (isset($insLeadArr[$key]['Project']['name'])?$insLeadArr[$key]['Project']['name']:'');
					$installer[$key]['address'] 		= (isset($insLeadArr[$key]['Project']['address'])?$insLeadArr[$key]['Project']['address']:'');
					$installer[$key]['city'] 			= (isset($insLeadArr[$key]['Project']['city'])?$insLeadArr[$key]['Project']['city']:'');
					$installer[$key]['lat'] 			= (isset($insLeadArr[$key]['Project']['latitude'])?$insLeadArr[$key]['Project']['latitude']:'');
					$installer[$key]['lon'] 			= (isset($insLeadArr[$key]['Project']['longitude'])?$insLeadArr[$key]['Project']['longitude']:'');
					$installer[$key]['capacity'] 		= (isset($insLeadArr[$key]['Project']['recommended_capacity'])?$insLeadArr[$key]['Project']['recommended_capacity']:'');
					$arr_state_details 				= $this->States->find('all',array('conditions'=>array('or'=>['statename'=>strtolower($insLeadArr[$key]['Project']['state']),'id'=>$insLeadArr[$key]['Project']['state']])))->first();
						$installer[$key]['state_id']	= 0;
						if(!empty($arr_state_details))
						{
							$installer[$key]['state_id']	= $arr_state_details->id;
						}
					$installer[$key]['cus_type'] 		= (isset($insLeadArr[$key]['Parameter']['para_value'])?$insLeadArr[$key]['Parameter']['para_value']:'');
					$installer[$key]['proj_time'] 		= (isset($insLeadArr[$key]['Project']['created'])?date("h:i a", strtotime($insLeadArr[$key]['Project']['created'])):'');
					$installer[$key]['proj_date'] 		= (isset($insLeadArr[$key]['Project']['created'])?date("d/m/Y", strtotime($insLeadArr[$key]['Project']['created'])):'');
					$installer[$key]['projectcreator'] 					= (isset($insLeadArr[$key]['Customer']['name'])?$insLeadArr[$key]['Customer']['name']:'');;
				}	
			}			
			$this->ApiToken->SetAPIResponse('type', 'ok');
			$this->ApiToken->SetAPIResponse('result', $installer);
		} else {
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'Invalid Request.');
		}		
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	*
	* getInstallerProjectLead
	*
	* Behaviour : public
	*
	* @defination : Method is used to get the Installer project.
	*
	* Author : Khushal Bhalsod
	*/
    public function getInstallerProject(){

        $this->autoRender = false;
        $this->SetVariables($this->request->data);
        $cus_id				= $this->ApiToken->customer_id;
        $customerdata		= $this->Customers->find('all', array('fields'=>['id','installer_id','user_role'],'conditions'=>array('id'=>$cus_id)))->first();
        $installer_id 		= isset($customerdata['installer_id'])?$customerdata['installer_id']:0;
        /*$installerdata		= $this->Installers->find('all', array('conditions'=>array('id'=>$installerid)))->first();
        $installer_id 		= (isset($installerdata['id'])?$installerdata['id']:0);*/
        $installer 			= array();
        $condition          = array();
        $condition[] 			= array('InstallerProjects.installer_id IN ' =>$installer_id,'InstallerProjects.status'=>'4002');
        $condition[0]       = 'Project.name is not null';
        $condition['Project.name != '] = '';
        $this->intLimit		= isset($this->request->data['length']) ? $this->request->data['length'] : PAGE_RECORD_LIMIT;
        $start_page 		= isset($this->request->data['page']) ? $this->request->data['page'] : 1;
        $this->CurrentPage  = $start_page;

        if(isset($this->request->data['Installers']['search_area']) && !empty($this->request->data['Installers']['sub_user_code'])){
            $condition[] = array('Project.area Like' =>"%".$this->request->data['Installers']['search_area']."%");
        }
        if(isset($this->request->data['Installers']['customer_type']) && !empty($this->request->data['Installers']['customer_type'])){
            $condition[] = array('Project.customer_type Like' =>"%".$this->request->data['Installers']['customer_type']."%");
        }
        if(!empty($installer_id) && $installer_id != 0) {
		$condition[] = array('InstallerProjects.installer_id IN ' =>$installer_id,'InstallerProjects.status'=>'4002');
		}
        if(isset($this->request->data['project_source']) && !empty($this->request->data['project_source'])){
            $condition[] = array('Project.project_source'=>$this->request->data['project_source']);
        }

        if(!empty($installer_id)) {
        	$JoinTables = array('Project' 	=> array(
						                            'table' => 'projects',
						                            'type' => 'LEFT',
						                            'conditions' => ['Project.id = InstallerProjects.project_id']),
        						'Customer' 	=> array(
						                            'table' => 'customers',
						                            'type' => 'LEFT',
						                            'conditions' => ['Project.created_by = Customer.id']),
        						'Parameter' => array(
        											'table' => 'parameters',
                            						'type' => 'LEFT',
                            						'conditions' => ['Parameter.para_id = Project.customer_type'])
        					);
        	$user_roles = explode(",",$customerdata->user_role);
        	if (in_array($this->Parameters->bd_role,$user_roles) && (!in_array($this->Parameters->admin_role,$user_roles))) {
        		$JoinTables['ProjectAssigned'] = array(	'table' 		=> 'project_assign_bd',
							                            'type' 			=> 'LEFT',
							                            'conditions' 	=> ['ProjectAssigned.projects_id = Project.id']);
        		$condition[] = array("ProjectAssigned.customers_id"=>$cus_id);
        	}
            $ProjectLeads 	= $this->InstallerProjects->find('all',
            					array('fields'	=> ['Project.id','Project.name','Project.address','Project.city','Project.created',
                									'Project.recommended_capacity','Project.latitude','Project.longitude',
                									'Parameter.para_value','Customer.name','Project.state'],
                    			'join' 		=> $JoinTables,
                    			'conditions'=> $condition,
                    			'order'		=> array('Project.id'=>'DESC')));
            $pageCount = 0;
            if(count($ProjectLeads->toArray()) > 0) {
                $pageCount = count($ProjectLeads->toArray()) / $this->intLimit;
            }
            $this->paginate['limit'] 	= $this->intLimit;
            $this->paginate['page'] 	= $this->CurrentPage;
            $installer 					= array();

            try {
                $insLeadArr	= $this->paginate($ProjectLeads);
                if(!empty($insLeadArr)) {
                    $insLeadArr = $insLeadArr->toArray();
                    foreach($insLeadArr as $key=>$value) {
                        $installer[$key]['id']			= (isset($insLeadArr[$key]['Project']['id'])?$insLeadArr[$key]['Project']['id']:'');
                        $installer[$key]['name']		= (isset($insLeadArr[$key]['Project']['name'])?$insLeadArr[$key]['Project']['name']:'');
                        $installer[$key]['address'] 	= (isset($insLeadArr[$key]['Project']['address'])?$insLeadArr[$key]['Project']['address']:'');
                        $installer[$key]['city'] 		= (isset($insLeadArr[$key]['Project']['city'])?$insLeadArr[$key]['Project']['city']:'');
                        $installer[$key]['state'] 		= (isset($insLeadArr[$key]['Project']['state'])?$insLeadArr[$key]['Project']['state']:'');
                        $installer[$key]['country'] 	= (isset($insLeadArr[$key]['Project']['country'])?$insLeadArr[$key]['Project']['country']:'');
                        $installer[$key]['lat'] 		= (isset($insLeadArr[$key]['Project']['latitude'])?$insLeadArr[$key]['Project']['latitude']:'');
                        $installer[$key]['lon'] 		= (isset($insLeadArr[$key]['Project']['longitude'])?$insLeadArr[$key]['Project']['longitude']:'');
                        $installer[$key]['capacity'] 	= (isset($insLeadArr[$key]['Project']['recommended_capacity'])?$insLeadArr[$key]['Project']['recommended_capacity']:'');
                        $installer[$key]['cus_type']	= (isset($insLeadArr[$key]['Parameter']['para_value'])?$insLeadArr[$key]['Parameter']['para_value']:'');
                        $installer[$key]['proj_time'] 	= (isset($insLeadArr[$key]['Project']['created'])?date("h:i a", strtotime($insLeadArr[$key]['Project']['created'])):'');
                        $installer[$key]['proj_date'] 	= (isset($insLeadArr[$key]['Project']['created'])?date("d/m/Y", strtotime($insLeadArr[$key]['Project']['created'])):'');
						$arr_state_details 				= $this->States->find('all',array('conditions'=>array('or'=>['statename'=>strtolower($insLeadArr[$key]['Project']['state']),'id'=>$insLeadArr[$key]['Project']['state']])))->first();
						$installer[$key]['state_id']	= 0;
						if(!empty($arr_state_details))
						{
							$installer[$key]['state_id']	= $arr_state_details->id;
						}
						
						$installer[$key]['hasInstaller'] 					= 0;
						$id 			= $installer[$key]['id'];
						$installerlist 	= $this->InstallerProjects->countinstaller($id);
						if($installerlist > 0)
						{
							$installer[$key]['hasInstaller']				= 1;
						}
						$installer[$key]['projectcreator'] 					= (isset($insLeadArr[$key]['Customer']['name'])?$insLeadArr[$key]['Customer']['name']:'');;
				   }
                    $this->ApiToken->SetAPIResponse('type', 'ok');
                    $this->ApiToken->SetAPIResponse('result', $installer);
                    $this->ApiToken->SetAPIResponse('limit', $this->intLimit);
                    $this->ApiToken->SetAPIResponse('CurrentPage', $this->CurrentPage);
                    $this->ApiToken->SetAPIResponse('page_count',$pageCount);
                }
            } catch (NotFoundException $e) {
                $this->ApiToken->SetAPIResponse('type', 'error');
                $this->ApiToken->SetAPIResponse('result', $installer);
                $this->ApiToken->SetAPIResponse('limit', $this->intLimit);
                $this->ApiToken->SetAPIResponse('CurrentPage', $this->CurrentPage);
                $this->ApiToken->SetAPIResponse('page_count',(isset($this->request->params['paging']['Project']['pageCount'])?$this->request->params['paging']['Project']['pageCount']:0));
            }
        } else {
            $this->ApiToken->SetAPIResponse('type', 'error');
            $this->ApiToken->SetAPIResponse('msg', 'Invalid Request.');
        }
        echo $this->ApiToken->GenerateAPIResponse();
        exit;
    }

	/**
	*
	* changeInstallerProjectStatus
	*
	* Behaviour : public
	*
	* @defination : Method is used to change status of Installer project.
	*
	* Author : Khushal Bhalsod
	*/
	public function changeInstallerProjectStatus()
	{ 
		$this->autoRender = false;
		$this->SetVariables($this->request->data);
		$cus_id			= $this->ApiToken->customer_id;
		$customerdata	= $this->Customers->find('all', array('fields'=>['id','installer_id'],'conditions'=>array('id'=>$cus_id)))->first();
		$installer_id 	= isset($customerdata['installer_id'])?$customerdata['installer_id']:0;
		$installerdata	= $this->Installers->find('all', array('conditions'=>array('id'=>$installer_id)))->first();
		/*$installer_id 	= (isset($installerdata['id'])?$installerdata['id']:0);*/
		$project_id 	= (isset($this->request->data['proj_id'])?$this->request->data['proj_id']:0);
		$status 		= (isset($this->request->data['status'])?$this->request->data['status']:0);
		
		if(!empty($installer_id) && !empty($project_id) && !empty($status)) {
			
			if($status == "4002") { 
				$activation_codes = $this->Installers->generateInstallerActivationCodes();
				$this->InstallerProjects->updateAll(['status' => $status,'contact_code' => $activation_codes], ['installer_id' => $installer_id,'project_id' => $project_id]);
				
				/*Send Message to customer*/ 
				$installer_name = (!empty($installerdata['installer_name'])?$installerdata['installer_name']:'');
				
				$Customer_data 	= $this->CustomerProjects->find('all', array('conditions'=>array('project_id'=>$project_id)))->first();
				$customer_id 	= $Customer_data['customer_id'];//$this->ApiToken->customer_id;
				$this->Customers->SendCustomerInstallerVerificationCode($installer_name,$customer_id,$activation_codes);
			
			} else {
				$this->InstallerProjects->updateAll(['status' => $status], ['installer_id' => $installer_id,'project_id' => $project_id]);		
			}
			$this->ApiToken->SetAPIResponse('type', 'ok');
			$this->ApiToken->SetAPIResponse('msg', 'Project status change successfully.');
		} else {
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'Invalid Request.');
		}	
		echo $this->ApiToken->GenerateAPIResponse();
		exit;	
	}

	/**
	*
	* forwardInstallerProjectLead
	*
	* Behaviour : public
	*
	* @defination : Method is used to forward Installer project lead.
	*
	* Author : Khushal Bhalsod
	*/
	public function forwardInstallerProjectLead()
	{ 
		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		$cus_id			= $this->ApiToken->customer_id;
		$customerData   = $this->Customers->find('all', array('conditions'=>array('id'=>$cus_id)))->first();
  		$cus_id   		= (isset($customerData['installer_id'])?$customerData['installer_id']:0);
	 	$project_id 	= (isset($this->request->data['InstallerProjects']['project_id'])?$this->request->data['InstallerProjects']['project_id']:0);
		$installer_id 	= (isset($this->request->data['InstallerProjects']['installer_id'])?$this->request->data['InstallerProjects']['installer_id']:0);
		if(!empty($project_id) && !empty($installer_id)) {
			$ins_ids = explode('#',$installer_id);
			/*Store installer project*/
			$existingInstaller = array();
			$installerList = $this->InstallerProjects->getProjectwiseInstallerList($project_id);
			if(!empty($installerList))
			{
				foreach($installerList as $keys=>$insArray)
				{
					$existingInstaller[]= $insArray->installers['id'];
				}
			}	
			/*Store project lead*/
			foreach ($ins_ids as $key => $value) { 
				if(!in_array($value,$existingInstaller)){
					$insProjData = array();
					$insProjData['installer_id']	= $value;
					$insProjData['project_id']		= $project_id;
					$insProjData['status']			= isset($this->request->data['InstallerProjects']['status'])?$this->request->data['InstallerProjects']['status']:0;
					$insProjEntity = $this->InstallerProjects->newEntity($insProjData);
					$insProjEntity->created = $this->NOW();
					$this->InstallerProjects->save($insProjEntity);
				}
			}
			$this->ApiToken->SetAPIResponse('type', 'ok');
			$this->ApiToken->SetAPIResponse('msg', 'Project lead forwarded.');
		} else {
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'Invalid request data.');
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	*
	* updateInstallerDetail
	*
	* Behaviour : public
	*
	* @defination : Method is used to update installer detail.
	*
	* Author : Khushal Bhalsod
	*/
	public function updateInstallerDetail()
	{
		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		$cus_id				= $this->ApiToken->customer_id;
		$customerdata		= $this->Customers->find('all', array('fields'=>['id','installer_id'],'conditions'=>array('id'=>$cus_id)))->first();
		$installer_id 		= isset($customerdata['installer_id'])?$customerdata['installer_id']:0;		
		if(!empty($installer_id) & !empty($this->request->data)) { 
			
			$this->request->data['modified'] 	= $this->NOW();
			
			/*Update Installer Profile Picture*/
			if(isset($this->request->data['profile_pic']['name']) && !empty($this->request->data['profile_pic']['name'])){
				$installerData 		= $this->Installers->get($installer_id);
				$db_profile_image 	= $installerData->toArray()['profile_pic'];

				$image_path = INSTALLER_PROFILE_PATH.$installer_id.'/';
				if(file_exists($image_path.$db_profile_image)){
					@unlink($image_path.$db_profile_image);
					@unlink($image_path.'r_'.$db_profile_image);
				}
				if(!file_exists(INSTALLER_PROFILE_PATH.$installer_id))
					mkdir(INSTALLER_PROFILE_PATH.$installer_id, 0755);
				$file_name = $this->file_upload($image_path,$this->request->data['profile_pic'],true,65,65,$image_path);
				unset($this->request->data['profile_pic']);	
				$this->Installers->updateAll(['profile_pic' => $file_name], ['id' => $installer_id]);
			}
			/* Update Installer Profile Picture */
			$this->request->data['installer_name'] 	= $this->request->data['company_name'];
			$this->request->data['about_installer'] = $this->request->data['about_company'];
			$this->request->data['address'] 		= $this->request->data['company_address'];

			unset($this->request->data['Installers']);
			unset($this->request->data['company_address']);
			unset($this->request->data['about_company']);
			unset($this->request->data['active_plan_name']);
			unset($this->request->data['active_plan_id']);
			unset($this->request->data['company_name']);
			$this->Installers->updateAll($this->request->data, ['id' => $installer_id]);

			$this->ApiToken->SetAPIResponse('type', 'ok');
			$this->ApiToken->SetAPIResponse('msg', 'Profile updated successfully.');
		} else {
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'Invalid request data.');
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	*
	* importInstallerList
	*
	* Behaviour : public
	*
	* @defination : Method is used to import installer using csv file.
	*
	* Author : Khushal Bhalsod
	*/
	public function importInstallerList()
	{
		$installerData = $this->Installers->find("list",['keyField' => 'id','valueField' => 'installer_name'])->toArray();
		$installer = array_map(function ($name) {
			return strtolower(preg_replace("/[^A-Za-z0-9]/", "", $name));
		}, $installerData);
		$handle = fopen(INSTALLER_PROFILE_PATH."installer_database.csv", "r");
		$updated_counter  = 0;
		$inserted_counter = 0;
		$columns = fgetcsv($handle, 1000, ",");
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
		{
         	$name 	= strtolower(preg_replace("/[^A-Za-z0-9]/", "", $data[1]));
			if (false !== $installer_id = array_search($name, $installer)) {
			  	$this->Installers->updateAll([
				   	'installer_name'	=> isset($data[1])?$data[1]:'',
					'profile_pic'		=> isset($data[2])?$data[2]:'',
					'address'			=> isset($data[3])?$data[3]:'',
					'pincode'			=> isset($data[4])?$data[4]:'',
					'city'				=> isset($data[5])?$data[5]:'',
					'state'				=> isset($data[6])?$data[6]:'',
					'longitude'			=> isset($data[7])?$data[7]:'',
					'latitude'			=> isset($data[8])?$data[8]:'',
					'application_code'	=> isset($data[9])?$data[9]:'',
					'rating_agency'		=> isset($data[10])?$data[10]:'',
					'rating'			=> isset($data[11])?$data[11]:'',
					'rating_category'	=> isset($data[12])?$data[12]:'',
					'contact'			=> isset($data[13])?$data[13]:'',
					'contact1'			=> isset($data[14])?$data[14]:'',
					'mobile'			=> isset($data[15])?$data[15]:'',
					'email'				=> isset($data[16])?$data[16]:'',
					'fax_no'			=> isset($data[17])?$data[17]:'',
					'website'			=> isset($data[18])?$data[18]:'',
					'about_installer'	=> isset($data[19])?$data[19]:'',
					'branch_address1'	=> isset($data[20])?$data[20]:'',
					'branch_address2'	=> isset($data[21])?$data[21]:'',
					'branch_address3'	=>isset($data[22])?$data[22]:'',
					'pan'				=> isset($data[23])?$data[23]:'',
					'modified'			=> $this->NOW()], ['id' => $installer_id]);
			  		$updated_counter++;

			} else { 
				$insData['Installers']['installer_name']	= isset($data[1])?$data[1]:'';
				$insData['Installers']['profile_pic']		= isset($data[2])?$data[2]:'';
				$insData['Installers']['address']			= isset($data[3])?$data[3]:'';
				$insData['Installers']['pincode']			= isset($data[4])?$data[4]:'';
				$insData['Installers']['city']				= isset($data[5])?$data[5]:'';
				$insData['Installers']['state']				= isset($data[6])?$data[6]:'';
				$insData['Installers']['longitude']			= isset($data[7])?$data[7]:'';
				$insData['Installers']['latitude']			= isset($data[8])?$data[8]:'';
				$insData['Installers']['application_code']	= isset($data[9])?$data[9]:'';
				$insData['Installers']['rating_agency']		= isset($data[10])?$data[10]:'';
				$insData['Installers']['rating']			= isset($data[11])?$data[11]:'';
				$insData['Installers']['rating_category']	= isset($data[12])?$data[12]:'';
				$insData['Installers']['contact']			= isset($data[13])?$data[13]:'';
				$insData['Installers']['contact1']			= isset($data[14])?$data[14]:'';
				$insData['Installers']['mobile']			= isset($data[15])?$data[15]:'';
				$insData['Installers']['email']				= isset($data[16])?$data[16]:'';
				$insData['Installers']['fax_no']			= isset($data[17])?$data[17]:'';
				$insData['Installers']['website']			= isset($data[18])?$data[18]:'';
				$insData['Installers']['about_installer']	= isset($data[19])?$data[19]:'';
				$insData['Installers']['branch_address1']	= isset($data[20])?$data[20]:'';
				$insData['Installers']['branch_address2']	= isset($data[21])?$data[21]:'';
				$insData['Installers']['branch_address3']	= isset($data[22])?$data[22]:'';
				$insData['Installers']['pan']				= isset($data[23])?$data[23]:'';

				$insEntity 				= $this->Installers->newEntity($insData);
				$insEntity->created 	= $this->NOW();
				$insEntity->modified 	= $this->NOW();
				$this->Installers->save($insEntity);
			    $inserted_counter++;
			}
		}
		echo "Installers Inserted : ".$inserted_counter."<br/>";
		echo "Installers Updated : ".$updated_counter."<br/>";
		exit;
	}

	function getregisteramount()
	{
		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		$cus_id			= $this->ApiToken->customer_id;
		$coupan_code   	= '';
		$dicountAmount 	= 0 ;
		$coupan_code = (isset($this->request->data['Installers']['coupan_code'])?$this->request->data['Installers']['coupan_code']:'');
		if(!empty($this->request->data['month']) || !empty($this->request->data['users'])){
			$amount = (($this->request->data['month'] *  $this->request->data['users']) * RATE) ;
			if(!empty($coupan_code)){
				$installerCupn = $this->InstallerCoupan->find('all', array('conditions'=>array('coupan_code'=>$coupan_code)))->toArray();
				if(!empty($installerCupn)){
					$coupanObj = $installerCupn[0];
					if(empty($coupanObj->is_flat))
					{
						$dicountAmount = (($amount * $coupanObj->amount)/100);
					} else {
						$dicountAmount = $amount - $coupanObj->amount;
					}
				}else{
					$amountArr['amount'] = $amount; 
					$amountArr['discount'] = $dicountAmount; 
					$amountArr['final_amount'] = $amount - $dicountAmount; 
					$this->ApiToken->SetAPIResponse('type', 'error');
					$this->ApiToken->SetAPIResponse('msg', 'Invalid Coupan.');
					$this->ApiToken->SetAPIResponse('result', $amountArr);
					echo $this->ApiToken->GenerateAPIResponse();
					exit;
				}
			}
			$amountArr['amount'] = $amount; 
			$amountArr['discount'] = $dicountAmount; 
			$amountArr['final_amount'] = $amount - $dicountAmount; 
			$this->ApiToken->SetAPIResponse('type', 'ok');
			$this->ApiToken->SetAPIResponse('result', $amountArr);	
		}
		else{
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'Invalid request data.');
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
     *
     * customercontacted
     *
     * Behaviour : public
     *
     * @defination : Method is use for verify installer contacted.
     *
     */
	function customercontacted()
	{
		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		$cus_id			= $this->ApiToken->customer_id;
		$customerdata	= $this->Customers->find('all', array('fields'=>['id','installer_id'],'conditions'=>array('id'=>$cus_id)))->first();
		$installer_id 	= isset($customerdata['installer_id'])?$customerdata['installer_id']:0;
		/*$installerdata	= $this->Installers->find('all', array('conditions'=>array('id'=>$installerid)))->first();
		$installer_id 	= (isset($installerdata['id'])?$installerdata['id']:0);*/
		$contact_code 	= (isset($this->request->data['Installers']['contact_code'])?$this->request->data['Installers']['contact_code']:'');
		$project_id 	= (isset($this->request->data['InstallerProjects']['project_id'])?$this->request->data['InstallerProjects']['project_id']:'');
		
		if(!empty($project_id) && !empty($contact_code)){ 
			$installerStatus = $this->InstallerProjects->find("all",array('conditions'=>array('installer_id'=>$installer_id,'project_id'=>$project_id,'contact_code'=>$contact_code)))->first();
			if(!empty($installerStatus)) { 
				if(empty($installerStatus['contacted_status'])) {
					$this->InstallerProjects->updateAll(['contacted_status' => 1], ['installer_id' => $installer_id,'project_id' => $project_id]);
					$this->ApiToken->SetAPIResponse('type', 'Ok');
					$this->ApiToken->SetAPIResponse('msg', 'Project verified successfully');
					$this->ApiToken->SetAPIResponse('code_status',1);
				} else { 					
					$this->ApiToken->SetAPIResponse('type', 'Ok');
					$this->ApiToken->SetAPIResponse('msg', 'Project already verified.');
					$this->ApiToken->SetAPIResponse('code_status',0);
				}				
			}else{
				$this->ApiToken->SetAPIResponse('type', 'Ok');
				$this->ApiToken->SetAPIResponse('msg', 'Please enter correct code.');
				$this->ApiToken->SetAPIResponse('code_status',0);
			}		
		}else{
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'Invalid request data.');
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	function customercontactedetail()
	{
		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);

		$cus_id				= $this->ApiToken->customer_id;
		$customerdata		= $this->Customers->find('all', array('fields'=>['id','installer_id'],'conditions'=>array('id'=>$cus_id)))->first();
		$installer_id 		= isset($customerdata['installer_id'])?$customerdata['installer_id']:0;
		/*$installerdata		= $this->Installers->find('all', array('conditions'=>array('id'=>$installerid)))->first();
		$installer_id 		= (isset($installerdata['id'])?$installerdata['id']:0);*/
		$installer 			= array('id'=>'','location'=>'','capacity'=>'','name'=>'','mobile'=>'','email'=>'','is_contacted'=>'0');
		$project_id 		= (isset($this->request->data['InstallerProjects']['project_id'])?$this->request->data['InstallerProjects']['project_id']:'');
		if(!empty($project_id)){
			$condition 	= array('CustomerProjects.project_id' => $project_id);
			$insLeadArr = $this->CustomerProjects->find('all',
							array(	'fields'=>['Project.id','Project.name','Project.address','Project.city','Project.created','Project.recommended_capacity','Project.latitude','Project.longitude','Customers.name','Customers.mobile','Customers.email'],
									'join'=>[ 	'Project' => ['table' => 'projects','type' => 'INNER','conditions' => ['Project.id = CustomerProjects.project_id']],
								            	'Customers' => ['table' => 'customers','type' => 'INNER','conditions' => ['Customers.id = CustomerProjects.customer_id']]
								            ],
				            		'conditions'=>$condition))->toArray();
			if($insLeadArr > 0)
			{
				foreach($insLeadArr as $key=>$value) { 
					$installerStatus = $this->InstallerProjects->find("all",array('conditions'=>array('installer_id'=>$installer_id,'project_id'=>$project_id,'contacted_status'=>'1')))->first();
					$installer['is_contacted']		= '0';
					if(!empty($installerStatus))
					{
						$installer['is_contacted']	= '1';
					}
					$installer['id']				= (isset($insLeadArr[$key]['Project']['id'])?$insLeadArr[$key]['Project']['id']:'');
					$installer['location']			= (isset($insLeadArr[$key]['Project']['address'])?$insLeadArr[$key]['Project']['address']:'');
					$installer['capacity']			= (isset($insLeadArr[$key]['Project']['recommended_capacity'])?$insLeadArr[$key]['Project']['recommended_capacity']:'');
					$installer['name']				= (isset($insLeadArr[$key]['Customers']['name'])?$insLeadArr[$key]['Customers']['name']:'');
					$installer['mobile']			= (isset($insLeadArr[$key]['Customers']['mobile'])?$insLeadArr[$key]['Customers']['mobile']:'');
					$installer['email']				= (isset($insLeadArr[$key]['Customers']['email'])?$insLeadArr[$key]['Customers']['email']:'');
				}
				$this->ApiToken->SetAPIResponse('result', $installer);
				$this->ApiToken->SetAPIResponse('type', 'Ok');
				
			}else{
				$this->ApiToken->SetAPIResponse('type', 'error');
				$this->ApiToken->SetAPIResponse('msg', 'Installer Not found.');
			}		
		}else{
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'Invalid request data.');
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	function projectdashboard()
	{
		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		$cus_id				= $this->ApiToken->customer_id;
		$customerdata		= $this->Customers->find('all', array('fields'=>['id','installer_id'],'conditions'=>array('id'=>$cus_id)))->first();
		$installer_id 		= isset($customerdata['installer_id'])?$customerdata['installer_id']:0;
		/*$installerdata		= $this->Installers->find('all', array('conditions'=>array('id'=>$installerid)))->first();
		$installer_id 		= (isset($installerdata['id'])?$installerdata['id']:0);*/
		$contact_code   	= '';
		$dicountAmount 		= 0 ;
		$project_id 		= (isset($this->request->data['InstallerProjects']['project_id'])?$this->request->data['InstallerProjects']['project_id']:'');
		if(!empty($cus_id)) {
			if(!empty($project_id))
			{
				$status['site_survey'] 		= $this->SiteSurveys->find('all',array('conditions'=>array('SiteSurveys.project_id'=>$project_id,'SiteSurveys.installer_id'=>$installer_id)))->count();
				$status['contacted'] 		= $this->InstallerProjects->find('all',array('conditions'=>array('InstallerProjects.project_id'=>$project_id,'InstallerProjects.installer_id'=>$installer_id,'InstallerProjects.contacted_status' => '1')))->count();
				$status['lead_accepted'] 	= $this->InstallerProjects->find('all',array('conditions'=>array('InstallerProjects.project_id'=>$project_id,'InstallerProjects.installer_id'=>$installer_id,'InstallerProjects.status' => '4002')))->count();
				$status['forwarded'] 		= $this->InstallerProjects->find('all',array('conditions'=>array('InstallerProjects.project_id'=>$project_id,'InstallerProjects.installer_id'=>$installer_id,'InstallerProjects.status' => '4004')))->count();
				$status['commercial'] 		= $this->Commercial->find('all',array('conditions'=>array('Commercial.project_id'=>$project_id,'Commercial.installer_id'=>$installer_id)))->count();
				$status['proposal'] 		= $this->Proposal->find('all',array('conditions'=>array('Proposal.project_id'=>$project_id,'Proposal.installer_id'=>$installer_id)))->count();
				$status['terms_condition'] 	= $this->InstallerTerms->find('all',array('conditions'=>array('InstallerTerms.installer_id'=>$installer_id,'InstallerTerms.is_default'=>'1')))->count();
				$status['workorder'] 		= $this->Workorder->find('all',array('conditions'=>array('Workorder.project_id'=>$project_id,'Workorder.installer_id'=>$installer_id)))->count();
				$status['installations'] 	= $this->Installation->find('all',array('conditions'=>array('Installation.project_id'=>$project_id,'Installation.installer_id'=>$installer_id)))->count();
				$status['commissioning'] 	= $this->Commissioning->find('all',array('conditions'=>array('Commissioning.project_id'=>$project_id,'Commissioning.installer_id'=>$installer_id)))->count();	
				$this->ApiToken->SetAPIResponse('result', $status);
				$this->ApiToken->SetAPIResponse('type', 'Ok');
			} else {
				$this->ApiToken->SetAPIResponse('type', 'error');
				$this->ApiToken->SetAPIResponse('msg', 'Please Select Project.');
			}		
		} else {
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'Invalid request data.');
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	public function companylist()
	{
		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		$cus_id			= $this->ApiToken->customer_id;
		$data=array();
		if(!empty($cus_id)){
			$query= (isset($this->request->data['Installers']['query'])?$this->request->data['Installers']['query']:'');
			$result = $this->Company->companylist($query);
			$data = array();
			if(!empty($result))
			{	
				foreach ($result as $key => $val) {
					$data[]=array('name'=>$val,'id'=>$key);
				}
				$this->ApiToken->SetAPIResponse('result', $data);
				$this->ApiToken->SetAPIResponse('type', 'Ok');
				
			}else
			{
				$this->ApiToken->SetAPIResponse('type', 'error');
				$this->ApiToken->SetAPIResponse('msg', 'No data Found.');
			}
		}else
		{
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'Invalid Request.');
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	public function getProfessionalUpgradeMessage()
	{
		$this->autoRender 	= false;
		
		$this->ApiToken->SetAPIResponse('type', 'Ok');
		$this->ApiToken->SetAPIResponse('msg', 'Professional version is free for 3 month.');
		
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	
	/**
	 *
	 * getProjectEstimationByPID
	 *
	 * Behaviour : public
	 *
	 * @defination : Method is use to add new admin user and provide specific righs using admin interface
	 *
	 */
	public function getProjectEstimationByPID($project_id=0)
	{
		$this->autoRender 	= false;
		$projectData 		= array(); 
		$result 			= array(); 
		if(!empty($project_id)) {
			$projectData 										= $this->Projects->get($project_id);
			$this->request->data['latitude']					= (isset($projectData['latitude'])?$projectData['latitude']:0);
			$this->request->data['Projects']['latitude']		= (isset($projectData['latitude'])?$projectData['latitude']:0);
			$this->request->data['longitude']					= (isset($projectData['longitude'])?$projectData['longitude']:0);
			$this->request->data['Projects']['longitude']		= (isset($projectData['longitude'])?$projectData['longitude']:0);
			$this->request->data['customer_type']				= (isset($projectData['customer_type'])?$projectData['customer_type']:0);
			$this->request->data['project_type']				= (isset($projectData['customer_type'])?$projectData['customer_type']:0);
			$this->request->data['Projects']['customer_type']	= (isset($projectData['customer_type'])?$projectData['customer_type']:0);
			$this->request->data['area']						= (isset($projectData['area'])?$projectData['area']:0);
			$this->request->data['Projects']['area']			= (isset($projectData['area'])?$projectData['area']:0);
			$this->request->data['area_type'] 					= (isset($projectData['area_type'])?$projectData['area_type']:0);
			$this->request->data['bill'] 						= (isset($projectData['avg_monthly_bill'])?$projectData['avg_monthly_bill']:0);
			$this->request->data['avg_monthly_bill'] 			= (isset($projectData['avg_monthly_bill'])?$projectData['avg_monthly_bill']:0);
			$this->request->data['backup_type']					= (isset($projectData['backup_type'])?$projectData['backup_type']:0);
			$this->request->data['usage_hours']					= (isset($projectData['usage_hours'])?$projectData['usage_hours']:0);
			$this->request->data['usage_hours']					= (isset($projectData['usage_hours'])?$projectData['usage_hours']:0);
			$this->request->data['Projects']['usage_hours']		= (isset($projectData['usage_hours'])?$projectData['usage_hours']:0);
			$this->request->data['energy_con']					= (isset($projectData['estimated_kwh_year'])?$projectData['estimated_kwh_year']:0);
			$this->request->data['Projects']['estimated_kwh_year'] = (isset($projectData['estimated_kwh_year'])?$projectData['estimated_kwh_year']:0);
			$result = $this->Projects->getprojectestimation($this->request->data);
		}
		return $result;
	}

	/**
	*
	* importinstallerlistv2
	*
	* Behaviour : public
	*
	* @defination : Method is used to import installer using csv file.
	*
	* Author : KALPAK PRAJAPATI
	*/
	public function importinstallerlistv2()
	{
		$handle 			= fopen(INSTALLER_PROFILE_PATH."20200330.csv", "r");
		$updated_counter  	= 0;
		$inserted_counter 	= 0;
		$wrong_counter 		= 0;
		$columns 			= fgetcsv($handle, 1000, ",");
		$WrongEmail 		= array();
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
		{
			if(isset($data[1]) && !empty($data[1]))
			{
				$installer_id = isset($data[0])?$data[0]:0;
				if($this->Installers->validateEmail(trim($data[14])))
				{
					if (!empty($installer_id))
					{
						$installer_data 	= $this->Installers->get($installer_id);
						$installer_name 	= isset($data[1]) && !empty($data[1])?$data[1]:$installer_data->installer_name;
						$contact_person 	= isset($data[2]) && !empty($data[2])?$data[2]:$installer_data->contact_person;
						$designation 		= isset($data[3]) && !empty($data[3])?$data[3]:$installer_data->designation;
						$profile_pic 		= isset($data[4]) && !empty($data[4])?$data[4]:$installer_data->profile_pic;
						$address 			= isset($data[5]) && !empty($data[5])?$data[5]:$installer_data->address;
						$pincode 			= isset($data[6]) && !empty($data[6])?$data[6]:$installer_data->pincode;
						$city 				= isset($data[7]) && !empty($data[7])?$data[7]:$installer_data->city;
						$state 				= isset($data[8]) && !empty($data[8])?$data[8]:$installer_data->state;
						$longitude 			= isset($data[9]) && !empty($data[9])?$data[9]:$installer_data->longitude;
						$latitude 			= isset($data[10]) && !empty($data[10])?$data[10]:$installer_data->latitude;
						$contact 			= isset($data[11]) && !empty($data[11])?$data[11]:$installer_data->contact;
						$contact1 			= isset($data[12]) && !empty($data[12])?$data[12]:$installer_data->contact1;
						$mobile 			= isset($data[13]) && !empty($data[13])?$data[13]:$installer_data->mobile;
						$email 				= isset($data[14]) && !empty($data[14])?trim($data[14]):$installer_data->email;
						$fax_no 			= isset($data[15]) && !empty($data[15])?$data[15]:$installer_data->fax_no;
						$website 			= isset($data[16]) && !empty($data[16])?$data[16]:$installer_data->website;
						$about_installer 	= isset($data[17]) && !empty($data[17])?$data[17]:$installer_data->about_installer;
						$installer_plan_id 	= isset($data[18]) && !empty($data[18])?$data[18]:$installer_data->installer_plan_id;
						$coupan_code 		= isset($data[19]) && !empty($data[19])?$data[19]:$installer_data->coupan_code;
						$use_month 			= isset($data[20]) && !empty($data[20])?$data[20]:$installer_data->use_month;
						$sub_users 			= isset($data[21]) && !empty($data[21])?$data[21]:$installer_data->sub_users;
						$sub_user_code 		= isset($data[22]) && !empty($data[22])?$data[22]:$installer_data->sub_user_code;
						$cumulative_rating 	= isset($data[23]) && !empty($data[23])?$data[23]:$installer_data->cumulative_rating;
						$application_code 	= isset($data[24]) && !empty($data[24])?$data[24]:$installer_data->application_code;
						$rating_agency 		= isset($data[25]) && !empty($data[25])?$data[25]:$installer_data->rating_agency;
						$rating 			= isset($data[26]) && !empty($data[26])?$data[26]:$installer_data->rating;
						$rating_category 	= isset($data[27]) && !empty($data[27])?$data[27]:$installer_data->rating_category;
						$geda_rate 			= isset($data[28]) && !empty($data[28])?$data[28]:$installer_data->geda_rate;
						$empaneled_city 	= isset($data[29]) && !empty($data[29])?$data[29]:$installer_data->empaneled_city;
						$branch_address1 	= isset($data[30]) && !empty($data[30])?$data[30]:$installer_data->branch_address1;
						$branch_address2 	= isset($data[31]) && !empty($data[31])?$data[31]:$installer_data->branch_address2;
						$branch_address3 	= isset($data[32]) && !empty($data[32])?$data[32]:$installer_data->branch_address3;
						$kw_non_dcr_10 		= isset($data[33]) && !empty($data[33])?$data[33]:$installer_data->kw_non_dcr_10;
						$kw_dcr_10 			= isset($data[34]) && !empty($data[34])?$data[34]:$installer_data->kw_dcr_10;
						$kw_non_dcr_100 	= isset($data[35]) && !empty($data[35])?$data[35]:$installer_data->kw_non_dcr_100;
						$kw_dcr_100 		= isset($data[36]) && !empty($data[36])?$data[36]:$installer_data->kw_dcr_100;
						$GST 				= isset($data[37]) && !empty($data[37])?$data[37]:$installer_data->GST;
						$pan 				= isset($data[38]) && !empty($data[38])?$data[38]:$installer_data->pan;
						$jreda_work_order 	= isset($data[39]) && !empty($data[39])?$data[39]:$installer_data->jreda_work_order;
						$jreda_nib_no 		= isset($data[40]) && !empty($data[40])?$data[40]:$installer_data->jreda_nib_no;
						$status 			= isset($data[41]) && !empty($data[41])?$data[41]:$installer_data->status;
						$otp 				= isset($data[42]) && !empty($data[42])?$data[42]:$installer_data->otp;
						$updateFields 		= [ 	'installer_name' 	=> $installer_name,
													'contact_person' 	=> $contact_person,
													'designation' 		=> $designation,
													'profile_pic' 		=> $profile_pic,
													'address' 			=> $address,
													'pincode' 			=> $pincode,
													'city' 				=> $city,
													'state' 			=> $state,
													'longitude' 		=> $longitude,
													'latitude' 			=> $latitude,
													'contact' 			=> $contact,
													'contact1' 			=> $contact1,
													'mobile' 			=> $mobile,
													'email' 			=> $email,
													'fax_no' 			=> $fax_no,
													'website' 			=> $website,
													'about_installer' 	=> $about_installer,
													'installer_plan_id' => $installer_plan_id,
													'coupan_code' 		=> $coupan_code,
													'use_month' 		=> $use_month,
													'sub_users' 		=> $sub_users,
													'sub_user_code' 	=> $sub_user_code,
													'cumulative_rating' => $cumulative_rating,
													'application_code' 	=> $application_code,
													'rating_agency' 	=> $rating_agency,
													'rating' 			=> $rating,
													'rating_category' 	=> $rating_category,
													'geda_rate' 		=> $geda_rate,
													'empaneled_city' 	=> $empaneled_city,
													'branch_address1' 	=> $branch_address1,
													'branch_address2' 	=> $branch_address2,
													'branch_address3' 	=> $branch_address3,
													'kw_non_dcr_10' 	=> $kw_non_dcr_10,
													'kw_dcr_10' 		=> $kw_dcr_10,
													'kw_non_dcr_100' 	=> $kw_non_dcr_100,
													'kw_dcr_100' 		=> $kw_dcr_100,
													'GST' 				=> $GST,
													'pan' 				=> $pan,
													'jreda_work_order' 	=> $jreda_work_order,
													'jreda_nib_no' 		=> $jreda_nib_no,
													'status' 			=> $status,
													'otp' 				=> $otp,
													'stateflg' 			=> 4,
													'modified'			=> $this->NOW()
												];
						$this->Installers->updateAll($updateFields,['id' => $installer_id]);
					  	$updated_counter++;
					} else {
						$company_name	= isset($data[1])?$data[1]:'';
						$arr_company 	= $this->Company->find('all',array('conditions'=>array('company_name'=>$company_name)))->first();
						if (empty($arr_company)) {
							$cdata['Company']['company_name'] 	= isset($data[1])?$data[1]:'';
							$companyEntity 						= $this->Company->newEntity($cdata);
							$this->Company->save($companyEntity);
							$company_id = $companyEntity->id;
						} else {
							$company_id = $arr_company->id;
						}
						$installer_name 	= isset($data[1]) && !empty($data[1])?$data[1]:'';
						$contact_person 	= isset($data[2]) && !empty($data[2])?$data[2]:'';
						$designation 		= isset($data[3]) && !empty($data[3])?$data[3]:'';
						$profile_pic 		= isset($data[4]) && !empty($data[4])?$data[4]:'';
						$address 			= isset($data[5]) && !empty($data[5])?$data[5]:'';
						$pincode 			= isset($data[6]) && !empty($data[6])?$data[6]:'';
						$city 				= isset($data[7]) && !empty($data[7])?$data[7]:'';
						$state 				= isset($data[8]) && !empty($data[8])?$data[8]:'';
						$longitude 			= isset($data[9]) && !empty($data[9])?$data[9]:'';
						$latitude 			= isset($data[10]) && !empty($data[10])?$data[10]:'';
						$contact 			= isset($data[11]) && !empty($data[11])?$data[11]:'';
						$contact1 			= isset($data[12]) && !empty($data[12])?$data[12]:'';
						$mobile 			= isset($data[13]) && !empty($data[13])?$data[13]:'';
						$email 				= isset($data[14]) && !empty($data[14])?trim($data[14]):'';
						$fax_no 			= isset($data[15]) && !empty($data[15])?$data[15]:'';
						$website 			= isset($data[16]) && !empty($data[16])?$data[16]:'';
						$about_installer 	= isset($data[17]) && !empty($data[17])?$data[17]:'';
						$installer_plan_id 	= isset($data[18]) && !empty($data[18])?$data[18]:'';
						$coupan_code 		= isset($data[19]) && !empty($data[19])?$data[19]:'';
						$use_month 			= isset($data[20]) && !empty($data[20])?$data[20]:'';
						$sub_users 			= isset($data[21]) && !empty($data[21])?$data[21]:'';
						$sub_user_code 		= isset($data[22]) && !empty($data[22])?$data[22]:'';
						$cumulative_rating 	= isset($data[23]) && !empty($data[23])?$data[23]:'';
						$application_code 	= isset($data[24]) && !empty($data[24])?$data[24]:'';
						$rating_agency 		= isset($data[25]) && !empty($data[25])?$data[25]:'';
						$rating 			= isset($data[26]) && !empty($data[26])?$data[26]:'';
						$rating_category 	= isset($data[27]) && !empty($data[27])?$data[27]:'';
						$geda_rate 			= isset($data[28]) && !empty($data[28])?$data[28]:'';
						$empaneled_city 	= isset($data[29]) && !empty($data[29])?$data[29]:'';
						$branch_address1 	= isset($data[30]) && !empty($data[30])?$data[30]:'';
						$branch_address2 	= isset($data[31]) && !empty($data[31])?$data[31]:'';
						$branch_address3 	= isset($data[32]) && !empty($data[32])?$data[32]:'';
						$kw_non_dcr_10 		= isset($data[33]) && !empty($data[33])?$data[33]:'';
						$kw_dcr_10 			= isset($data[34]) && !empty($data[34])?$data[34]:'';
						$kw_non_dcr_100 	= isset($data[35]) && !empty($data[35])?$data[35]:'';
						$kw_dcr_100 		= isset($data[36]) && !empty($data[36])?$data[36]:'';
						$GST 				= isset($data[37]) && !empty($data[37])?$data[37]:'';
						$pan 				= isset($data[38]) && !empty($data[38])?$data[38]:'';
						$jreda_work_order 	= isset($data[39]) && !empty($data[39])?$data[39]:'';
						$jreda_nib_no 		= isset($data[40]) && !empty($data[40])?$data[40]:'';
						$status 			= isset($data[41]) && !empty($data[41])?$data[41]:1;
						$otp 				= isset($data[42]) && !empty($data[42])?$data[42]:'';
						$insData['Installers'] 	= [ 'company_id' 		=> $company_id,
													'installer_name' 	=> $installer_name,
													'contact_person' 	=> $contact_person,
													'designation' 		=> $designation,
													'profile_pic' 		=> $profile_pic,
													'address' 			=> $address,
													'pincode' 			=> $pincode,
													'city' 				=> $city,
													'state' 			=> $state,
													'longitude' 		=> $longitude,
													'latitude' 			=> $latitude,
													'contact' 			=> $contact,
													'contact1' 			=> $contact1,
													'mobile' 			=> $mobile,
													'email' 			=> $email,
													'fax_no' 			=> $fax_no,
													'website' 			=> $website,
													'about_installer' 	=> $about_installer,
													'installer_plan_id' => $installer_plan_id,
													'coupan_code' 		=> $coupan_code,
													'use_month' 		=> $use_month,
													'sub_users' 		=> $sub_users,
													'sub_user_code' 	=> $sub_user_code,
													'cumulative_rating' => $cumulative_rating,
													'application_code' 	=> $application_code,
													'rating_agency' 	=> $rating_agency,
													'rating' 			=> $rating,
													'rating_category' 	=> $rating_category,
													'geda_rate' 		=> $geda_rate,
													'empaneled_city' 	=> $empaneled_city,
													'branch_address1' 	=> $branch_address1,
													'branch_address2' 	=> $branch_address2,
													'branch_address3' 	=> $branch_address3,
													'kw_non_dcr_10' 	=> $kw_non_dcr_10,
													'kw_dcr_10' 		=> $kw_dcr_10,
													'kw_non_dcr_100' 	=> $kw_non_dcr_100,
													'kw_dcr_100' 		=> $kw_dcr_100,
													'GST' 				=> $GST,
													'pan' 				=> $pan,
													'jreda_work_order' 	=> $jreda_work_order,
													'jreda_nib_no' 		=> $jreda_nib_no,
													'status' 			=> $status,
													'otp' 				=> $otp,
													'stateflg' 			=> 4,
													'status' 			=> 1
												];
						$insEntity 				= $this->Installers->newEntity($insData);
						$insEntity->created 	= $this->NOW();
						$insEntity->modified 	= $this->NOW();
						$this->Installers->save($insEntity);
						$installer_id = $insEntity->id;
					    $inserted_counter++;
					}

					$cat_id 		= isset($data[46])?$data[46]:0;
					$category_id 	= 0;
					switch ($cat_id) {
						case 'A':
							$category_id = 1;
							break;
						case 'B':
							$category_id = 2;
							break;
						case 'C':
							$category_id = 3;
							break;
						default:
							$category_id = 0;
							break;
					}
					$allowed_bands = array();
					if (isset($data[47]) && !empty($data[47])) {
						array_push($allowed_bands,trim($data[47]));
					}
					if (isset($data[48]) && !empty($data[48])) {
						array_push($allowed_bands,trim($data[48]));
					}
					if (isset($data[49]) && !empty($data[49])) {
						array_push($allowed_bands,trim($data[49]));
					}
					if (isset($data[50]) && !empty($data[50])) {
						array_push($allowed_bands,trim($data[50]));
					}

					$FindCond 	= ['installer_id'=>$installer_id];
					
					$CMC 		= $this->InstallerCategoryMapping->find('all',['conditions'=>$FindCond])->count();

				
					if($CMC == 0)
					{
						////json_encode($allowed_bands);
						$mapdata['InstallerCategoryMapping']['installer_id'] 	= $installer_id;
						$mapdata['InstallerCategoryMapping']['category_id'] 	= $category_id;
						$mapdata['InstallerCategoryMapping']['allowed_bands'] 	= '["1","2","3","4"]';
						$mapdata['InstallerCategoryMapping']['short_name'] 		= isset($data[51])?$data[51]:'';

						$InstallerCategoryMappingEntity 						= $this->InstallerCategoryMapping->newEntity($mapdata);
						
						$this->InstallerCategoryMapping->save($InstallerCategoryMappingEntity);
					} else {

						//json_encode($allowed_bands),
						/*$UF = [	'category_id'	=> $category_id,
								'allowed_bands'	=> '["1","2","3","4"]',
								'short_name' 	=> (isset($data[51])?$data[51]:'')]; 
						$this->InstallerCategoryMapping->updateAll($UF,['installer_id' => $installer_id]);*/
					}
					$arrInstallers = $this->Installers->find('all',['conditions'=>[ 'Installers.stateflg'=>4,'Installers.id'=>$installer_id]])->order(array('Installers.id'=>'ASC'));

			        if (!empty($arrInstallers))
			        {
			            foreach($arrInstallers as $arrInstaller)
			            {
			                echo "\r\n--".$arrInstaller->id." -- ".$arrInstaller->email." -- ".$arrInstaller->mobile."--\r\n".'<br/>';

			                $AutoRegister = true;
			                if ($AutoRegister)
			                {
			                    if ($arrInstaller->email == '' && $arrInstaller->mobile == '') {
			                        continue; //no email & mobile skip registration
			                    }
			                    $arrName 						= explode(" ",$arrInstaller->contact_person);
			                    $RandomPassword                 = strtolower($arrName[0]).'@2019';//$this->randomPassword();
			                    $arrEmail                       = explode(",",$arrInstaller->email);
			                    $CustomerEmail                  = trim($arrEmail[0]);
			                    $customersEntity                = $this->Customers->newEntity();
			                    $customersEntity->mobile        = $arrInstaller->mobile;
			                    $customersEntity->email         = $CustomerEmail;
			                    $customersEntity->name          = $arrInstaller->contact_person;
			                    $customersEntity->password      = $this->EncryptPassword($RandomPassword);
			                    $customersEntity->status        = $this->Customers->STATUS_ACTIVE;
			                    $customersEntity->customer_type = "installer";
			                    $customersEntity->state         = 4;
			                    $customersEntity->created       = $this->NOW();
			                    $customercnt                    = $this->Customers->find('all', array('conditions'=>array('email'=>$CustomerEmail)))->count();
			                    $IsInstallerCreated             = $this->Customers->find('all', array('conditions'=>array('installer_id'=>$arrInstaller->id)))->count();

			                    echo "\r\n--".$customercnt." -- ".$IsInstallerCreated."--\r\n";

			                    if($customercnt == 0 && $IsInstallerCreated == 0)
			                    {
			                    	
			                        if ($this->Customers->save($customersEntity)) 
			                        {
			                            $arrInstaller->installer_plan_id                = $this->InstallerPlans->DEFAULT_PLAN_ID;
			                            $insplanData                                    = $this->InstallerPlans->get($this->InstallerPlans->DEFAULT_PLAN_ID);
			                            $InstallerSubscriptionEntity                    = $this->InstallerSubscription->newEntity();
			                            $InstallerSubscriptionEntity->payment_status    = '';
			                            $InstallerSubscriptionEntity->installer_id      = $arrInstaller->id;
			                            $InstallerSubscriptionEntity->coupen_code       = '';
			                            $InstallerSubscriptionEntity->transaction_id    = '';
			                            $InstallerSubscriptionEntity->created           = $this->NOW();
			                            $InstallerSubscriptionEntity->modified          = $this->NOW();
			                            $InstallerSubscriptionEntity->payment_gateway   = '';
			                            $InstallerSubscriptionEntity->comment           = '100% Discount';
			                            $InstallerSubscriptionEntity->payment_data      = '';
			                            $InstallerSubscriptionEntity->amount            = '0';
			                            $InstallerSubscriptionEntity->coupen_id         = '0';
			                            $InstallerSubscriptionEntity->is_flat           = '0';
			                            $InstallerSubscriptionEntity->plan_name         = $insplanData->plan_name;
			                            $InstallerSubscriptionEntity->plan_price        = $insplanData->plan_price;
			                            $InstallerSubscriptionEntity->plan_id           = $this->InstallerPlans->DEFAULT_PLAN_ID;
			                            $InstallerSubscriptionEntity->user_limit        = $insplanData->user_limit;
			                            $InstallerSubscriptionEntity->start_date        = date('Y-m-d');
			                            $InstallerSubscriptionEntity->expire_date       = date('Y-m-d',strtotime("+ 30 days"));
			                            $InstallerSubscriptionEntity->status            = '1';
			                            $InstallerSubscriptionEntity->created_by        = $customersEntity->id;
			                            $InstallerSubscriptionEntity->modified_by       = $customersEntity->id;
			                            $this->InstallerSubscription->save($InstallerSubscriptionEntity);
			                            $insCodeArr = array();
			                            for ($i=0; $i < $insplanData->user_limit; $i++) {
			                                $activation_codes = $this->Installers->generateInstallerActivationCodes();
			                                $insCodeArr[]                                               = $activation_codes;
			                                $insCodedata['InstallerActivationCodes']['installer_id']    = $arrInstaller->id;
			                                $insCodedata['InstallerActivationCodes']['activation_code'] = $activation_codes;
			                                $insCodedata['InstallerActivationCodes']['start_date']      = date('Y-m-d');
			                                $insCodedata['InstallerActivationCodes']['expire_date']     = date('Y-m-d',strtotime("+ 30 days"));
			                                $insCodeEntity = $this->InstallerActivationCodes->newEntity($insCodedata);
			                                $this->InstallerActivationCodes->save($insCodeEntity);
			                            }
			                            $this->Customers->updateAll(['user_role'=>$this->Parameters->admin_role,'default_admin'=>1,'installer_id' => $arrInstaller->id,'modified' => $this->NOW()], ['id' => $customersEntity->id]);

			                            $PasswordInfo['InstallerCredendtials']['installer_id']   = $arrInstaller->id;
			                            $PasswordInfo['InstallerCredendtials']['password']       = $RandomPassword;
			                            $InstallerCredendtials = $this->InstallerCredendtials->newEntity($PasswordInfo);
			                            $this->InstallerCredendtials->save($InstallerCredendtials);
			                        }
			                    }
			                }
			            }
			        } 
			    }
			    else
				{
					$WrongEmail[] 	= $data[14];
					echo $data[14].'<br>';
					$wrong_counter++;
				}	
			}
		}
		echo "Installers Inserted : ".$inserted_counter."<br/>";
		echo "Installers Updated : ".$updated_counter."<br/>";
		echo "Wrong Emails Updated : ".$wrong_counter."<br/>";
		echo implode("<br>",$WrongEmail);
		exit;
	}

	/**
	*
	* importinstallerlistv3
	*
	* Behaviour : public
	*
	* @defination : Method is used to import installer using csv file.
	*
	* Author : KALPAK PRAJAPATI
	*/
	public function importinstallerlistv3()
	{
		$handle 			= fopen(INSTALLER_PROFILE_PATH."DISCOM-FINAL_4.csv", "r");
		$updated_counter  	= 0;
		$inserted_counter 	= 0;
		$columns 			= fgetcsv($handle, 1000, ",");
		$arrInstallers 		= array();
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
		{
			$installer_id = isset($data[0])?$data[0]:0;
			if (empty($installer_id))
			{
				$company_name	= isset($data[1])?$data[1]:'';
				$arr_company 	= $this->Company->find('all',array('conditions'=>array('company_name'=>$company_name)))->first();
				if (empty($arr_company)) {
					echo "<br/>--New Installer Company::".$company_name."--<br />";
				} else {
					echo "<br/>--Old Installer Company::".$company_name." -- ".$arr_company->id."--<br />";
				}
				$company_name	= isset($data[1])?$data[1]:'';
				$arr_installer 	= $this->Installers->find('all',array('conditions'=>array('installer_name'=>$company_name)))->first();
				if (empty($arr_installer)) {
					echo "<br/>--New Installer::".$company_name."--<br />";
				} else {
					echo "<br/>--Old Installer::".$company_name." -- ".$arr_installer->id."--<br />";
					array_push($arrInstallers,$arr_installer->id);
				}
			}
		}
		echo implode(",",$arrInstallers);
		exit;
	}
	public function randomPassword() 
    {
        $alphabet       = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass           = array(); //remember to declare $pass as an array
        $alphaLength    = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
    public function EncryptPassword($Password="")
    {
        $NewPassword = Security::hash(Configure::read('Security.salt') . $Password);
        return $NewPassword;
    }
}