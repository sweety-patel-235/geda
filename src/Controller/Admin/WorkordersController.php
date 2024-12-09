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

class WorkordersController extends AppController
{	
	var $helpers = array('Time','Html','Form','ExPaginator');
	public $arrDefaultAdminUserRights 	= array(); 
	public $PAGE_NAME 					= '';
	public $contact_code_min = 	1000;
	public $contact_code_max =	9999;
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
		$this->loadModel('ProjectLeads');		
		$this->loadModel('SiteSurveys');		
		$this->loadModel('SiteSurveysImages');		
		$this->loadModel('Commercial');		
		$this->loadModel('Workorder');		
	
		$this->set('Userright',$this->Userright);
    }

    private function SetVariables($post_variables) { 

		if(isset($post_variables['lat']))
			$this->request->data['Workorder']['latitude']		= $post_variables['lat'];
			if(isset($post_variables['proj_id']))
			$this->request->data['Workorder']['project_id']		= $post_variables['proj_id'];
		if(isset($post_variables['pv_cost']))
			$this->request->data['Workorder']['pv_cost'] 		= $post_variables['pv_cost'];
		if(isset($post_variables['pv_qty']))
			$this->request->data['Workorder']['pv_qty'] 		= $post_variables['pv_qty'];
		if(isset($post_variables['inverter_cost']))
			$this->request->data['Workorder']['inverter_cost'] 	= $post_variables['inverter_cost'];
		if(isset($post_variables['interter_qty']))
			$this->request->data['Workorder']['interter_qty'] 	= $post_variables['interter_qty'];
		if(isset($post_variables['bos_cost']))
			$this->request->data['Workorder']['bos_cost'] 		= $post_variables['bos_cost'];
		if(isset($post_variables['bos_qty']))
			$this->request->data['Workorder']['bos_qty'] 		= $post_variables['bos_qty'];
		if(isset($post_variables['other_cost']))
			$this->request->data['Workorder']['other_cost'] 	= $post_variables['other_cost'];
		if(isset($post_variables['other_qty']))
			$this->request->data['Workorder']['other_qty'] 	= $post_variables['other_qty'];
		if(isset($post_variables['taxes']))
			$this->request->data['Workorder']['taxes'] 		= $post_variables['taxes'];
		if(isset($post_variables['total_cost']))
			$this->request->data['Workorder']['total_cost'] 	= $post_variables['total_cost'];
		if(isset($post_variables['debt']))
			$this->request->data['Workorder']['debt'] 			= $post_variables['debt'];
		if(isset($post_variables['interest']))
			$this->request->data['Workorder']['interest'] 		= $post_variables['interest'];
		if(isset($post_variables['description']))
			$this->request->data['Workorder']['description'] 	= $post_variables['description'];
		if(isset($post_variables['om_cost']))
			$this->request->data['Workorder']['om_cost'] 		= $post_variables['om_cost'];
		if(isset($post_variables['cuf']))
			$this->request->data['Workorder']['cuf'] 			= $post_variables['cuf'];
		if(isset($post_variables['loan_tenure']))
			$this->request->data['Workorder']['loan_tenure'] 	= $post_variables['loan_tenure'];
		if(isset($post_variables['wo_number']))
			$this->request->data['Workorder']['workorder_number'] = $post_variables['wo_number'];
		if(isset($post_variables['wo_date']))
			$this->request->data['Workorder']['workorder_date']   = $post_variables['wo_date'];
		if(isset($post_variables['Capacity']))
			$this->request->data['Workorder']['Capacity']       = $post_variables['Capacity'];
		if(isset($post_variables['proj_id']))
			$this->request->data['Workorder']['project_id']   = $post_variables['proj_id'];
	}

	/*
	 * Displays a index
	 *
	 * @param mixed What page to display
	 * @return void
	 */
	public function index() {
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
	function enable($id=null) {
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
	 * getProjectEstimation
	 *
	 * Behaviour : public
	 *
	 * @defination : Method is use to add new admin user and provide specific righs using admin interface
	 *
	 */
	public function setcommercialdetail()
	{
		$this->autoRender 	= false;		
		$this->SetVariables($this->request->data);
		$cus_id	= $this->ApiToken->customer_id;
		$comm_id = (isset($this->request->data['Commercial']['id'])?$this->request->data['Commercial']['id']:0);
		if(!empty($cus_id))
		{
			if(empty($comm_id)){
				$commercialEntity = $this->Commercial->newEntity($this->request->data());
				$commercialEntity->installer_id = $cus_id;
			}else{
				$commercialData 	= $this->Commercial->get($comm_id);
				$commercialEntity = $this->Commercial->patchEntity($commercialData,$this->request->data());
				$commercialEntity->installer_id = $cus_id;
			}	
			$this->Commercial->save($commercialEntity);
			$message['comm_id'] = 	$commercialEntity->id; 
			$this->ApiToken->SetAPIResponse('result', $message);
			$status				= 'ok';
			$this->ApiToken->SetAPIResponse('msg', 'Commercial Details Saved Successfully.');			
		} else {
			$status				= 'error';
			$error				= 'Invalid Request';
			$this->ApiToken->SetAPIResponse('msg', $error);
		}
		$this->ApiToken->SetAPIResponse('type', $status);
		echo stripslashes($this->ApiToken->GenerateAPIResponse());
	}
	public function commercial_step()
	{ 
		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		$cus_id	= $this->ApiToken->customer_id;
		$comm_id = (isset($this->request->data['Commercial']['id'])?$this->request->data['Commercial']['id']:0);
		$comm_step 	= (isset($this->request->data['comm_step'])?$this->request->data['comm_step']:0);
		if(!empty($comm_id) && !empty($cus_id)) {
			$commData 	= $this->Commercial->get($comm_id);
			$commEntity = $this->Commercial->patchEntity($commData,$this->request->data());
			//$this->SiteSurveys->save($surveysEntity);
			$message['project_id'] = $commEntity->project_id;
			$message['installer_id'] = $commEntity->installer_id;
			if(!empty($comm_step) && $comm_step==1)
			{
				$message['pv_cost'] = $commEntity->pv_cost;
				$message['pv_qty']	 = $commEntity->pv_qty;
				$message['inverter_cost'] = $commEntity->inverter_cost;
				$message['interter_qty'] = $commEntity->interter_qty;
				$message['bos_cost'] = $commEntity->bos_cost;
				$message['bos_qty'] = $commEntity->bos_qty;
				$message['other_cost'] = $commEntity->other_cost;
				$message['other_qty'] = $commEntity->other_qty;
				$message['taxes'] = $commEntity->taxes;
				$message['total_cost'] = $commEntity->total_cost;
			}elseif(!empty($comm_step) && $comm_step==3)
			{	
				$message['debt'] = $commEntity->debt;
				$message['interest'] = $commEntity->interest;
				$message['description'] = $commEntity->description;
				$message['om_cost'] = $commEntity->om_cost;
				$message['cuf'] = $commEntity->cuf;
				$message['escalation_om'] = $commEntity->escalation_om;
			}
			$this->ApiToken->SetAPIResponse('result', $message);
			$this->ApiToken->SetAPIResponse('type', 'ok');
			$this->ApiToken->SetAPIResponse('msg', 'Commercial Details Saved Successfully.');
		} else {
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'Invalid request data.');
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	
	/**
	 *
	 * getprojectassumption
	 *
	 * Behaviour : public
	 *
	 * @defination : Method is use to add new admin user and provide specific righs using admin interface
	 *
	 */
	public function getworkorder()
	{
		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		$cus_id	= $this->ApiToken->customer_id;
		$customerData   = $this->Customers->find('all', array('conditions'=>array('id'=>$cus_id)))->first();
  		$cus_id   = (isset($customerData['installer_id'])?$customerData['installer_id']:0);
		$proj_id = (isset($this->request->data['Workorder']['project_id'])?$this->request->data['Workorder']['project_id']:0);
		if(!empty($cus_id)){
			$commData 	= $this->Workorder->find('all',array("conditions"=>array('Workorder.project_id'=>$proj_id,'Workorder.installer_id'=>$cus_id)))->first();
			if(!empty($commData)){	
				$status				= 'ok';
				$result['proj_id']			=	$commData['project_id'];
				$result['wo_date']			=	$commData['workorder_date']->format('d-m-Y');
				$result['Capacity']         =   $commData['Capacity'];
				$result['wo_number']    	=	$commData['workorder_number'];
				$this->ApiToken->SetAPIResponse('result',$result);
			}else{
				$result['proj_id']			=	$proj_id;
				$result['wo_date']			=	"";
				$data=$this->ProjectNotes->find('all')
            		->where(['project_id' => $proj_id])
            		->first();
				$result['Capacity']         =$data->estimate_capacity;
				$result['wo_number']		=	"";
				$status				= 'ok';
				$this->ApiToken->SetAPIResponse('result',$result);
			}
		}else
		{
			$status				= 'error';
			$error				= 'Invalid Request';
			$this->ApiToken->SetAPIResponse('msg', $error);
		}
		$this->ApiToken->SetAPIResponse('type', $status);
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	public function setworkorder()
	{
		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		$cus_id	= $this->ApiToken->customer_id;
		$customerData   = $this->Customers->find('all', array('conditions'=>array('id'=>$cus_id)))->first();
 		$cus_id   = (isset($customerData['installer_id'])?$customerData['installer_id']:0);
		$proj_id = (isset($this->request->data['Workorder']['project_id'])?$this->request->data['Workorder']['project_id']:0);
		$workorder_number = (isset($this->request->data['Workorder']['workorder_number'])?$this->request->data['Workorder']['workorder_number']:0);
		if(!empty($cus_id) && !empty($proj_id) && !empty($workorder_number)) {
			$commData 	= $this->Workorder->find('all',array("conditions"=>array('Workorder.project_id'=>$proj_id,'Workorder.installer_id'=>$cus_id)))->first();
			$date = (isset($this->request->data['Workorder']['workorder_date'])?$this->request->data['Workorder']['workorder_date']:$this->NOW());
			if(!empty($commData)){
				$saveData = $this->Workorder->get($commData['id']);
				$commEntity 			= $this->Workorder->patchEntity($saveData,$this->request->data());
			}else{
				$commEntity 			= $this->Workorder->newEntity($this->request->data());
			}
			$commEntity->project_id 	= $proj_id;
			$commEntity->installer_id 	= $cus_id;
			$commEntity->workorder_date = date('Y-m-d',strtotime($date)); 
			$commEntity->created 		= $this->NOW(); 
			if($this->Workorder->save($commEntity)) {
				$ApplicationData 		= $this->ApplyOnlines->find('all', array('conditions'=>array('project_id'=>$proj_id)))->first();
				if (!empty($ApplicationData)) {
					if ($this->ApplyOnlineApprovals->can_workstart($ApplicationData->application_status)) 
						{
							$this->ApplyOnlines->SetStatusToWorkOrderStats($ApplicationData->id,$this->ApplyOnlineApprovals->WORK_STARTS);
							$this->ApplyOnlineApprovals->saveStatus($ApplicationData->id,$this->ApplyOnlineApprovals->WORK_STARTS,$this->ApiToken->customer_id,"");
							$applyOnlinesData       = $this->ApplyOnlines->viewApplication($ApplicationData->id);
							if($applyOnlinesData->apply_state=='4' || strtolower($applyOnlinesData->apply_state)=='gujarat')
                        	{
	                            $INSTATTER_NAME     = $applyOnlinesData->installer['installer_name'];
	                            $CUSTOMER_NAME      = trim($applyOnlinesData->customer_name_prefixed." ".$applyOnlinesData->name_of_consumer_applicant." ".$applyOnlinesData->last_name." ".$applyOnlinesData->third_name);
	                            $EmailVars          = array("CUSTOMER_NAME"=>$CUSTOMER_NAME,
	                                                        "INSTATTER_NAME"=>$INSTATTER_NAME,
	                                                    	"APPLCATION_NUMBER"=>$applyOnlinesData->application_no);
	                            $template_include   = 'work_starts';
	                            $subject            = "[REG: Application No. ".$applyOnlinesData->application_no."] Work Starts";
	                            $sms_text = 'Your Application no. '.$applyOnlinesData->application_no.' for a Rooftop Solar PV (RTPV) system work started by installer is '.$applyOnlinesData->installer['installer_name'].'. Thank you.';
	                            if(!empty($applyOnlinesData->consumer_mobile))
	                            {
	                                $this->ApplyOnlines->sendSMS($applyOnlinesData->id,$applyOnlinesData->consumer_mobile,$sms_text);
	                            }
	                            if(!empty($applyOnlinesData->installer_mobile))
	                            {
	                                $this->ApplyOnlines->sendSMS($applyOnlinesData->id,$applyOnlinesData->installer_mobile,$sms_text);
	                            }
	                            $email      = new Email('default');
	                                $email->profile('default');
	                                $email->viewVars($EmailVars);
	                                $message_send = $email->template($template_include, 'default')
	                                        ->emailFormat('html')
	                                        ->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
	                                        ->to($applyOnlinesData->installer_email)
	                                        ->subject(Configure::read('EMAIL_ENV').$subject)
	                                        ->send();
	                            $to     = $applyOnlinesData->consumer_email;
	                            if(empty($to))
	                            {
	                                $to = $applyOnlinesData->email;
	                            }
	                            if(!empty($to))
	                            {
	                                $email          = new Email('default');
	                                $email->profile('default');
	                                $email->viewVars($EmailVars);
	                                $message_send   = $email->template($template_include, 'default')
	                                        ->emailFormat('html')
	                                        ->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
	                                        ->to($to)
	                                        ->subject(Configure::read('EMAIL_ENV').$subject)
	                                        ->send();
	                            }
                        	}
						}
				}
				$status				= 'ok';
				$result['project_id']			=	$proj_id;
				$result['wo_date']				=	date('d-m-Y',strtotime($date));
				$result['wo_number']			=	$commEntity->workorder_number;
				$error				            =   'Work Order Details Saved Successfully';
				$this->ApiToken->SetAPIResponse('msg', $error);
				$this->ApiToken->SetAPIResponse('result',$result);
			} else {
				$status				= 'error';
				$error				= 'Please try after some time';
				$this->ApiToken->SetAPIResponse('msg', $error);
			} 
		}else
		{
			$status				= 'error';
			$error				= 'Invalid Request';
			$this->ApiToken->SetAPIResponse('msg', $error);
		}
		
		$this->ApiToken->SetAPIResponse('type', $status);
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	
}
