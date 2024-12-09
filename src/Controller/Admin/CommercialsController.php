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

class CommercialsController extends AppController
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
	
		$this->set('Userright',$this->Userright);
    }

    private function SetVariables($post_variables) { 

		if(isset($post_variables['lat']))
			$this->request->data['Commercial']['latitude']		= $post_variables['lat'];
		if(isset($post_variables['proj_id']))
			$this->request->data['Commercial']['project_id']	= $post_variables['proj_id'];
		if(isset($post_variables['pv_cost']))
			$this->request->data['Commercial']['pv_cost'] 		= $post_variables['pv_cost'];
		if(isset($post_variables['pv_qty']))
			$this->request->data['Commercial']['pv_qty'] 		= $post_variables['pv_qty'];
		if(isset($post_variables['inverter_cost']))
			$this->request->data['Commercial']['inverter_cost'] = $post_variables['inverter_cost'];
		if(isset($post_variables['inverter_qty']))
			$this->request->data['Commercial']['inverter_qty'] 	= $post_variables['inverter_qty'];
		if(isset($post_variables['bos_cost']))
			$this->request->data['Commercial']['bos_cost'] 		= $post_variables['bos_cost'];
		if(isset($post_variables['bos_qty']))
			$this->request->data['Commercial']['bos_qty'] 		= $post_variables['bos_qty'];
		if(isset($post_variables['other_cost']))
			$this->request->data['Commercial']['other_cost'] 	= $post_variables['other_cost'];
		if(isset($post_variables['other_qty']))
			$this->request->data['Commercial']['other_qty'] 	= $post_variables['other_qty'];
		if(isset($post_variables['taxes']))
			$this->request->data['Commercial']['taxes'] 		= $post_variables['taxes'];
		if(isset($post_variables['total_cost']))
			$this->request->data['Commercial']['total_cost'] 	= $post_variables['total_cost'];
		if(isset($post_variables['debt']))
			$this->request->data['Commercial']['debt'] 			= $post_variables['debt'];
		if(isset($post_variables['interest_rate']))
			$this->request->data['Commercial']['interest'] 		= $post_variables['interest_rate'];
		if(isset($post_variables['description']))
			$this->request->data['Commercial']['description'] 	= $post_variables['description'];
		if(isset($post_variables['om_cost']))
			$this->request->data['Commercial']['om_cost'] 		= $post_variables['om_cost'];
		if(isset($post_variables['cuf']))
			$this->request->data['Commercial']['cuf'] 			= $post_variables['cuf'];
		if(isset($post_variables['loanternure']))
			$this->request->data['Commercial']['loanternure'] 	= $post_variables['loanternure'];
		if(isset($post_variables['escalation_om']))
			$this->request->data['Commercial']['escalation_om'] = $post_variables['escalation_om'];
		if(isset($post_variables['comm_id']))
			$this->request->data['Commercial']['id'] = $post_variables['comm_id'];
		if(isset($post_variables['pv_rating']))
			$this->request->data['Commercial']['pv_rating'] = $post_variables['pv_rating'];
		if(isset($post_variables['inverter_rating']))
			$this->request->data['Commercial']['inverter_rating'] = $post_variables['inverter_rating'];
		if(isset($post_variables['bos_rating']))
			$this->request->data['Commercial']['bos_rating'] = $post_variables['bos_rating'];
		if(isset($post_variables['lumpsum_cost']))
			$this->request->data['Commercial']['lumpsum_cost'] = $post_variables['lumpsum_cost'];
		if(isset($post_variables['is_lumpsumcost']))
			$this->request->data['Commercial']['is_lumpsumcost'] = $post_variables['is_lumpsumcost'];
		if(isset($post_variables['fc_charge_rs']))
			$this->request->data['Commercial']['fc_charge_rs'] = $post_variables['fc_charge_rs'];
		if(isset($post_variables['fc_charge_kw']))
			$this->request->data['Commercial']['fc_charge_kw'] = $post_variables['fc_charge_kw'];
		if(isset($post_variables['note3']))
			$this->request->data['Commercial']['note3'] = $post_variables['note3'];
		if(isset($post_variables['vc_charge']))
			$this->request->data['Commercial']['vc_charge'] = $post_variables['vc_charge'];
		if(isset($post_variables['deprecation']))
			$this->request->data['Commercial']['deprecation'] = $post_variables['deprecation'];
		if(isset($post_variables['energy_charge_details']))
			$this->request->data['Commercial']['energy_charge_details']				= serialize(json_decode($post_variables['energy_charge_details'],true));
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

	/**
     *
     * commercial_step
     *
     * Behaviour : public
     *
     * @defination : Method is use for get project commercial data and save data (API function).
     *
     */
	public function commercial_step()
	{ 
		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		
		$cus_id	= $this->ApiToken->customer_id;
		$customerData   = $this->Customers->find('all', array('conditions'=>array('id'=>$cus_id)))->first();
		$cus_id		= (!empty($customerData['installer_id'])?$customerData['installer_id']:0); 
		$proj_id 	= (isset($this->request->data['Commercial']['project_id'])?$this->request->data['Commercial']['project_id']:0);
		$comm_step 	= (isset($this->request->data['comm_step'])?$this->request->data['comm_step']:0);
		
		if(!empty($cus_id) && !empty($proj_id)) {
			$commercialData	= $this->Commercial->find('all', array('conditions'=>array('Commercial.project_id'=>$proj_id,'Commercial.installer_id'=>$cus_id)))->first();
			if(!empty($commercialData)){
				
				$commData 	= $this->Commercial->get($commercialData['id']);
				$commEntity = $this->Commercial->patchEntity($commData,$this->request->data());
				$commEntity->project_id = $proj_id;
				$commEntity->installer_id = $cus_id;
			}else{
				$commEntity = $this->Commercial->newEntity($this->request->data());
				$commEntity->project_id = $proj_id;
				$commEntity->installer_id = $cus_id;
			}
			
			$message['project_id'] 		= $commEntity->project_id;
			$message['installer_id'] 	= $commEntity->installer_id;
			
			if(!empty($comm_step) && $comm_step==1) {

				$message['pv_cost'] 			= (isset($commEntity->pv_cost)?$commEntity->pv_cost:0);
				$message['pv_qty'] 				= (isset($commEntity->pv_qty)?$commEntity->pv_qty:0);
				$message['inverter_cost'] 		= (isset($commEntity->inverter_cost)?$commEntity->inverter_cost:0);
				$message['inverter_qty'] 		= (isset($commEntity->inverter_qty)?$commEntity->inverter_qty:0);
				$message['bos_cost'] 			= (isset($commEntity->bos_cost)?$commEntity->bos_cost:0);
				$message['bos_qty'] 			= (isset($commEntity->bos_qty)?$commEntity->bos_qty:0);
				$message['other_cost'] 			= (isset($commEntity->other_cost)?$commEntity->other_cost:0);
				$message['other_qty'] 			= (isset($commEntity->other_qty)?$commEntity->other_qty:0);
				$message['taxes'] 				= (isset($commEntity->taxes)?$commEntity->taxes:0);
				$message['total_cost'] 			= (isset($commEntity->total_cost)?$commEntity->total_cost:0);
				$message['pv_rating'] 			= (isset($commEntity->pv_rating)?$commEntity->pv_rating:"");
				$message['inverter_rating'] 	= (isset($commEntity->inverter_rating)?$commEntity->inverter_rating:"");
				$message['bos_rating'] 			= (isset($commEntity->bos_rating)?$commEntity->bos_rating:"");
				$message['lumpsum_cost'] 		= (isset($commEntity->lumpsum_cost)?$commEntity->lumpsum_cost:0);
				$message['is_lumpsumcost'] 		= (isset($commEntity->is_lumpsumcost)?$commEntity->is_lumpsumcost:0);
			
			} elseif(!empty($comm_step) && $comm_step==3) {

				$energy_charge_details 				= array("energy_charge"=>array(array("ecmorethen"=>"","ecbetween2_to"=>"","eccharges_upto"=>"","ecmorethen_rs"=>"","ecbetween1_to_rs"=>"","ecbetween2_to_rs"=>"","ecbetween2_from"=>"","eccharges_upto_rs"=>"","ecbetween1_from"=>"","ecbetween1_to"=>"")));	
				$message['fc_charge_rs'] 			= (isset($commEntity->fc_charge_rs)?$commEntity->fc_charge_rs:0);
				$message['fc_charge_kw'] 			= (isset($commEntity->fc_charge_kw)?$commEntity->fc_charge_kw:0);
				$message['vc_charge'] 				= (isset($commEntity->vc_charge)?$commEntity->vc_charge:0);
				$message['note3'] 					= (isset($commEntity->note3)?$commEntity->note3:0);
				$message['energy_charge_details']	= (!empty($commEntity->energy_charge_details)?unserialize($commEntity->energy_charge_details):$energy_charge_details);
			}

			if($this->request->data('submit')=="1"){
				$this->Commercial->save($commEntity);
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
	 * getcommercialdefault
	 *
	 * Behaviour : public
	 *
	 * @defination : Method is use to get project commercial step 2 data.
	 *
	 */
	public function getcommercialdefault()
	{
		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		
		$cus_id	= $this->ApiToken->customer_id;
		$customerData   = $this->Customers->find('all', array('conditions'=>array('id'=>$cus_id)))->first();
		$cus_id		= (!empty($customerData['installer_id'])?$customerData['installer_id']:0);
		$proj_id 	= (isset($this->request->data['Commercial']['project_id'])?$this->request->data['Commercial']['project_id']:0);
		
		if(!empty($cus_id) && !empty($proj_id)) {
			
			$commercialData	= $this->Commercial->find('all', array('conditions'=>array('Commercial.project_id'=>$proj_id,'Commercial.installer_id'=>$cus_id)))->first();
			
			if(!empty($commercialData)){
				$commData 	= $this->Commercial->get($commercialData['id']);
				$commEntity = $this->Commercial->patchEntity($commData,$this->request->data());
			}else{
				$commEntity = $this->Commercial->newEntity($this->request->data());
			}
			
			$estimated_cost			= $commEntity['estimated_cost'];
			$estimated_cost 		= ($estimated_cost*100000);
			$o_and_m_cost			= (($estimated_cost*O_AND_M_COST)/100); 
			$o_and_m_esclation		= (($estimated_cost*O_AND_M_ESCLATION)/100); 
			$debt					= (($estimated_cost*DEBT_FRATION)/100); 
			$interastOnLoan			= (($estimated_cost*INTEREST_RATE_ON_LOAN)/100); 
			$insuranceCost			= (($estimated_cost*INSURANCE_COST)/100); 
			$rateOfDepreFor10		= (($estimated_cost*RATE_DEPRECATION_FOR_10)/100); 
			$rateOfDepreNext15		= (($estimated_cost*RATE_DEPRECATION_NEXT_15)/100); 
			$rateOfAcceleratedDepre	= (($estimated_cost*RATE_OF_ACCELERATED_DEPRE)/100);

			$this->request->data['Commercial']['om_cost'] 				= (isset($this->request->data['Commercial']['om_cost'])?$this->request->data['Commercial']['om_cost']:$o_and_m_cost);
			$this->request->data['Commercial']['escalation_om'] 		= (isset($this->request->data['Commercial']['escalation_om'])?$this->request->data['Commercial']['escalation_om']:$o_and_m_esclation);
			$this->request->data['Commercial']['cuf'] 					= (isset($this->request->data['Commercial']['cuf'])?$this->request->data['Commercial']['cuf']:$o_and_m_esclation);
			$this->request->data['Commercial']['debt'] 					= (isset($this->request->data['Commercial']['debt'])?$this->request->data['Commercial']['debt']:$debt);
			$this->request->data['Commercial']['interest'] 				= (isset($this->request->data['Commercial']['interest'])?$this->request->data['Commercial']['interest']:$interastOnLoan);
			$this->request->data['Commercial']['insurance_cost'] 		= (isset($this->request->data['Commercial']['insurance_cost'])?$this->request->data['Commercial']['insurance_cost']:$insuranceCost);
			$this->request->data['Commercial']['deprecation'] 			= (isset($this->request->data['Commercial']['deprecation'])?$this->request->data['Commercial']['deprecation']:(defined('RATE_DEPRECATION_FOR_10')?RATE_DEPRECATION_FOR_10:"0"));
			$this->request->data['Commercial']['rate_of_desp_15'] 		= (isset($this->request->data['Commercial']['rate_of_desp_15'])?$this->request->data['Commercial']['rate_of_desp_15']:(defined('RATE_DEPRECATION_NEXT_15')?RATE_DEPRECATION_NEXT_15:"0"));
			$this->request->data['Commercial']['loanternure'] 			= (isset($this->request->data['Commercial']['loanternure'])?$this->request->data['Commercial']['loanternure']:$insuranceCost);

			$commEntity = $this->Commercial->patchEntity($commData,$this->request->data());
			$status 	= "ok";
			
			if($this->request->data('submit')==1){
				$this->Commercial->save($commEntity);
				$this->ApiToken->SetAPIResponse('msg', "Save Successfully");
			} else { 
				$commercialData	= $this->Commercial->find('all', array('conditions'=>array('Commercial.project_id'=>$proj_id,'Commercial.installer_id'=>$cus_id)))->first();
				$status						= 'ok';
				$result['om_cost']			= (isset($commercialData['om_cost'])?$commercialData['om_cost']:0);
				$result['escalation_om']	= (isset($commercialData['escalation_om'])?$commercialData['escalation_om']:0);
				$result['debt']				= (isset($commercialData['debt'])?$commercialData['debt']:0);
				$result['interest']			= (isset($commercialData['interest'])?$commercialData['interest']:0);
				$result['insurance_cost']	= (isset($commercialData['insurance_cost'])?$commercialData['insurance_cost']:0);
				$result['deprecation']		= (isset($commercialData['deprecation'])?$commercialData['deprecation']:0);
				$result['rate_of_desp_15']	= (isset($commercialData['rate_of_desp_15'])?$commercialData['rate_of_desp_15']:0);
				$result['rate_of_acc_desp']	= (isset($commercialData['rate_of_acc_desp'])?$commercialData['rate_of_acc_desp']:0);
				$result['cuf'] 				= (isset($commercialData['cuf'])?$commercialData['cuf']:0);
				$result['loanternure']		= (isset($commercialData['loanternure'])?$commercialData['loanternure']:0);
				$this->ApiToken->SetAPIResponse('result',$result);	
			}	
		} else {
			$status				= 'error';
			$error				= 'Invalid Request';
			$this->ApiToken->SetAPIResponse('msg', $error);
		}
		
		$this->ApiToken->SetAPIResponse('type', $status);
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	 *
	 * getFinancialSummery
	 *
	 * Behaviour : public
	 *
	 * @defination : Method is use to get project financial summery.
	 *
	 */
	public function getFinancialSummery()
	{
		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		$project_id = (isset($this->request->data['proj_id'])?$this->request->data['proj_id']:0);
		
		if(!empty($project_id)) {
			
			$commercialData = $this->Commercial->findByProjectId($project_id)
								->select(['pv_cost','pv_qty','inverter_cost','inverter_qty','bos_cost','bos_qty','other_cost','other_qty','total_cost','lumpsum_cost','is_lumpsumcost']); 
			
			$this->ApiToken->SetAPIResponse('type', 'ok');
			$this->ApiToken->SetAPIResponse('result', $commercialData);
			
		} else {
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'Invalid Request.');
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	
	
}
