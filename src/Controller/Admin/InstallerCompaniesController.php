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
use Cake\Datasource\ConnectionManager;

/**
 *
 * Installer Company Controller
 *
 * @defination : Class is used for managing the companies for installer user in the site.
 *
 * Author : CP Soni
 */
class InstallerCompaniesController extends AppController
{	
	var $helpers = array('Time','Html','Form','ExPaginator');
	
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
		$this->loadModel('ApiToken');
		$this->loadModel('InstallerCompanies');
		$this->loadModel('Adminaction');
		$this->loadModel('InstallerStates');
		$this->loadModel('InstallerAgencyRating');
		$this->loadModel('InstallerPlans');
		$this->loadModel('States');
		$this->loadModel('Customers');
		$this->set('Userright',$this->Userright);
		$this->conn = ConnectionManager::get('default');
    }


	/*
	 * Displays a index
	 *
	 * Behaviour: Public
	 *
	 * @defination: method is use to list installation companies
	 */
	public function index() {
		
		$this->intCurAdminUserRight = $this->Userright->LIST_INSTALLER;
		$this->setAdminArea();
		
		if (!empty($this->InstallerCompanies->validate)) {
			foreach ($this->InstallerCompanies->validate as $field => $rules) {
				$this->InstallerCompanies->validator()->remove($field); //Remove all validation in search page
			}
		}
		$arrInstallerPlanList	= array();
		$arrCondition			= array();
		$this->SortBy			= "InstallerCompanies.id";
		$this->Direction		= "ASC";
		$this->intLimit			= PAGE_RECORD_LIMIT;
		$this->CurrentPage  	= 1;/*((isset($this->request->data['start']) ? $this->request->data['start'] : '10')/$this->intLimit)+1*/
		$option=array();
		$option['colName']  	= array('id','installer_name','address','state','mobile','action');
		
		$this->SetSortingVars('InstallerCompanies',$option);

		$arrCondition			= $this->_generateInstallerCompaniesSearchCondition();
		$this->paginate			= array('conditions' 	=> 	$arrCondition,
										'order'		 	=>	array($this->SortBy => $this->Direction),
										'page'			=> 	$this->CurrentPage,
										'limit' 		=> 	$this->intLimit);
		$arrInstallerPlanList	= $this->paginate('InstallerCompanies');
		
		$option['dt_selector']	='table-example';
		$option['formId']		='index-formmain';
		$option['url']			= WEB_ADMIN_PREFIX.'InstallerCompanies';
		
		$JqdTablescr = $this->JqdTable->create($option);

		$option1=array();
		$option1['colName']  	= array('id','plan_name','plan_price','coupen_code','amount','is_flat','payment_status','start_date','expire_date','comment','status');
		$option1['dt_selector']	= 'table-example-survey';
		$option1['formId']		= 'formmain_subscription';
		$option1['url']			= WEB_ADMIN_PREFIX.'InstallerSubscription/get_subscription';
		$JqdTablescr_sub 		= $this->JqdTable->create($option1);
		$installers_plan 		= $this->InstallerPlans->find('list',array('keyField' => 'id', 'valueField' => function ($row) {return $row->plan_name." - ".$row->plan_price;}, 'conditions'=>array('status'=>'1')))->toArray();
		
		$arr_ins_plan 			= array("0"=>"Please select Plan");
		$installers_plan 		= array_merge($arr_ins_plan,$installers_plan);
		
		$out					= array();
		$arrInstallerPlanList 	= $arrInstallerPlanList->toArray();
		$period 				= $this->period;
		$limit 					= $this->intLimit;
		$CurrentPage 			= $this->CurrentPage;
		$SortBy 				= $this->SortBy;
		$Direction 				= $this->Direction;
		$page_count 			= (isset($this->request->params['paging']['InstallerCompanies']['pageCount'])?$this->request->params['paging']['InstallerCompanies']['pageCount']:0);
		$this->set(compact('arrInstallerPlanList', 'JqdTablescr', 'JqdTablescr_sub', 'installers_plan', 'period', 'limit', 'CurrentPage', 'SortBy', 'Direction', 'page_count'));
	
		$blnEditCompanyRights		= $this->Userright->checkadminrights($this->Userright->EDIT_INSTALLER);
		foreach($arrInstallerPlanList as $key=>$val) {
			$temparr=array();
			foreach($option['colName'] as $key) {
				if($key=='state') {
					if($val[$key]==NULL)
					{
						$temparr[$key]='';
					}
					else
					{
						$temparr[$key]=$val[$key];
					}
					
				}
				else if(isset($val[$key])) {
					$temparr[$key]=$val[$key];
				}
				else if($key=='action') {  
					$temparr['action']='<a target="_blank" title="View Record" href="'.WEB_ADMIN_URL.'InstallerCompanies/view/'.encode($val['id']).'"><i class="fa fa-eye"></i></a>&nbsp';
					$temparr['action'].='<a target="_blank" title="View Subscription" href="javascript:;" onclick="javascript:show_modal(\''.encode($val['id']).'\');"><i class="fa fa-list-alt"></i></a>';
					
				}		
			}
			$out[]=$temparr;
		}
		if ($this->request->is('ajax')){
			header('Content-type: application/json');
			echo json_encode(array(	"draw" 			  => intval($this->request->data['draw']),
									"recordsTotal"    => intval($this->request->params['paging']['InstallerCompanies']['count']),
									"recordsFiltered" => intval($this->request->params['paging']['InstallerCompanies']['count']),
									"data"            => $out));
			die;
		}
	}
	/**
	 *
	 * _generateInstallerCompaniesearchCondition
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use generate installer plan search condition.
	 *
	 * Author : Khushal Bhalsod
	 */
	private function _generateInstallerCompaniesSearchCondition($id=null)
	{
		$arrCondition	= array();		
		if(!empty($id)) $this->request->data['InstallerCompanies']['id'] = $id;
		
		if(isset($this->request->data) && count($this->request->data)>0) {
            if(isset($this->request->data['InstallerCompanies']['id']) && trim($this->request->data['InstallerCompanies']['id'])!='') {
                $strID = trim($this->request->data['InstallerCompanies']['id'],',');
                $arrCondition['InstallerCompanies.id'] = $this->request->data['InstallerCompanies']['id'];
            }
			if(isset($this->request->data['InstallerCompanies']['status']) && !empty($this->request->data['InstallerCompanies']['status'])) {
                $status = $this->request->data['InstallerCompanies']['status'];
				if($this->request->data['InstallerCompanies']['status']=='I') $status = $this->STATUS_INACTIVE;
				$arrCondition['InstallerCompanies.status'] = $status;
            }
			if(isset($this->request->data['InstallerCompanies']['installer_name']) && $this->request->data['InstallerCompanies']['installer_name']!='') {
                $arrCondition['InstallerCompanies.installer_name LIKE'] = '%'.$this->request->data['InstallerCompanies']['installer_name'].'%';
            }
			if(isset($this->request->data['InstallerCompanies']['state']) && $this->request->data['InstallerCompanies']['state']!='') {
                $arrCondition['InstallerCompanies.state LIKE'] = '%'.$this->request->data['InstallerCompanies']['state'].'%';
            }
						
			if(isset($this->request->data['InstallerCompanies']['search_period']) && $this->request->data['InstallerCompanies']['search_period']!='') {
                if($this->request->data['InstallerCompanies']['search_period'] == 1 || $this->request->data['InstallerCompanies']['search_period'] == 2) {
                	$arrSearchPara	= $this->InstallerCompanies->setSearchDateParameter($this->request->data['InstallerCompanies']['search_period'],$this->modelClass);
                	$this->request->data[$this->modelClass] = array_merge($this->request->data[$this->modelClass],$arrSearchPara[$this->modelClass]);
                    $this->dateDisabled	= true;
                }
                $arrperiodcondi = $this->InstallerCompanies->findConditionByPeriod( 'created',
																		$this->request->data['InstallerCompanies']['search_period'],
																		$this->request->data['InstallerCompanies']['DateFrom'],
																		$this->request->data['InstallerCompanies']['DateTo'],
																		$this->Session->read('InstallerCompanies.timezone'));
               	if(!empty($arrperiodcondi)){
                	$arrCondition['between'] = $arrperiodcondi['between'];
                }
            }
		}
		return $arrCondition;
	}

	/**
	 *
	 * plan_add
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to add new installer plan
	 *
	 */
	public function manage($id = null)
	{
		$stateArr  = array();
		$attingArr  = array();
		if(empty($id)){
			$this->intCurAdminUserRight = $this->Userright->ADD_INSTALLER;
			$this->setAdminArea();
			$InstallerCompanies = $this->InstallerCompanies->newEntity($this->request->data() ,['validate' => 'default']);
		}else{
			$id=intval(decode($id));
			$this->intCurAdminUserRight = $this->Userright->EDIT_INSTALLER;
			$this->setAdminArea();
			$InstallerData 					= $this->InstallerCompanies->get($id);
			$InstallerCompanies 			= $this->InstallerCompanies->patchEntity($InstallerData,$this->request->data,['validate'=>'default']);
			$stateArr = $this->InstallerStates->getInstallerStateList($id);
			$attingArr = $this->InstallerAgencyRating->installerRatting($id);
		}
		$arrAdminDefaultRights = array();
		$timezone = '';
		$arrError = array();
		
		if(!$InstallerCompanies->errors() && !empty($this->request->data)) {
			if($this->InstallerCompanies->save($InstallerCompanies)) {
				$sql = "DELETE FROM  `installer_agency_rating` WHERE `installer_id` = '".$InstallerCompanies->id."'";
				$this->conn->execute($sql);
				foreach ($this->request->data['InstallerCompanies']['type'] as $key => $value) {
					$data= array();
					$data['installer_id'] = $InstallerCompanies->id;
					$data['type'] = $this->request->data['InstallerCompanies']['type'][$key];
					$data['validupto'] = $this->request->data['InstallerCompanies']['validupto'][$key];
					$data['appno'] = $this->request->data['InstallerCompanies']['appno'][$key];
					$data['rate_agency'] = $this->request->data['InstallerCompanies']['rate_agency'][$key];
					$data['agency_rate'] =$this->request->data['InstallerCompanies']['agency_rate'][$key];
					$data['mnre_rate'] = $this->request->data['InstallerCompanies']['mnre_rate'][$key];
					$newARLead = $this->InstallerAgencyRating->newEntity($data);
					$this->InstallerAgencyRating->save($newARLead);
				}
				if(isset($this->request->data['InstallerCompanies']['installer_state']) && !empty($this->request->data['InstallerCompanies']['installer_state']))
				{
					$sql = "DELETE FROM  `installer_region_states` WHERE `installer_id` = '".$InstallerCompanies->id."'";
					$this->conn->execute($sql);
					foreach($this->request->data['InstallerCompanies']['installer_state'] as $key => $value) {
						$sql="INSERT INTO `installer_region_states`(`installer_id`, `state_id`, `updated`) VALUES ('".$InstallerCompanies->id."','".$value."','NOW()') ON DUPLICATE KEY UPDATE `state_id`= '".$value."',  `updated` ='NOW()' ";
						$this->conn->execute($sql);
					}
				}
				if(empty($id)){
					$this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->ADD_INSTALLER,$InstallerCompanies->id,'Inserted Installer Id :: '.$InstallerCompanies->id);
					$this->Flash->set('Installer  has been saved.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
					return $this->redirect(WEB_ADMIN_PREFIX.'/InstallerCompanies');
				}else{
					
					$this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->EDIT_INSTALLER,$id,'Edited Installer Id :: '.$id);
					$this->Flash->set('Installer has been edit.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
					return $this->redirect(WEB_ADMIN_PREFIX.'/InstallerCompanies');
				}
			}
		}
		$this->set('east_states',$this->States->getSteates(1));
		$this->set('north_states',$this->States->getSteates(2));
		$this->set('south_states',$this->States->getSteates(3));
		$this->set('north_east_states',$this->States->getSteates(4));
		$this->set('central_states',$this->States->getSteates(5));
		$this->set('west_states',$this->States->getSteates(6));
		$data = $this->request->data;
		$this->set(compact('InstallerCompanies','data','stateArr','attingArr'));
	}
	/**
	 *
	 * view
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to view installer 
	 *
	 */
	public function view($id= null)
	{
		if(empty($id)) {
				$this->Flash->error('Please Select Valid Installer.');             
				return $this->redirect(WEB_ADMIN_PREFIX.'/InstallerCompanies');
		} else {
			$encode_id = $id;
			$id=intval(decode($id));
			$this->intCurAdminUserRight = $this->Userright->LIST_COMPANY;
			$customerData 				= $this->InstallerCompanies->get($id);
		}
		$stateArr = $this->InstallerStates->getInstallerStateList($id);
		$attingArr = $this->InstallerAgencyRating->installerRatting($id);
		


		$this->set(compact('customerData','stateArr','attingArr','id'));

		$arrInstallerPlanList	= array();
		$arrCondition		= array();
		$this->SortBy		= "InstallerCompanies.id";
		$this->Direction	= "ASC";
		$this->intLimit		= PAGE_RECORD_LIMIT;
		$this->CurrentPage  = 1;/*((isset($this->request->data['start']) ? $this->request->data['start'] : '10')/$this->intLimit)+1*/
		$option=array();
		$option['colName']  = array('id','installer_name','address','state','contact','action');
		
		$this->SetSortingVars('InstallerCompanies',$option);
		

		if(!empty($customerData['company_id'])){
			$conditions = array('InstallerCompanies.company_id'=>$customerData['company_id'],'InstallerCompanies.id NOT IN'=>$id);	
		}else{
			$conditions = array('InstallerCompanies.company_id NOT IN'=>$customerData['company_id'],'InstallerCompanies.id NOT IN'=>$id);	
		}

		$this->paginate			= array('conditions' 	=> $conditions,
										'order'		 	=>	array($this->SortBy => $this->Direction),
										'page'			=> 	$this->CurrentPage,
										'limit' 		=> 	$this->intLimit);
		$arrInstallerPlanList	= $this->paginate('InstallerCompanies');
		
		$option['dt_selector']	='table-example';
		$option['formId']		='index-formmain';
		$option['url']			= WEB_ADMIN_PREFIX.'InstallerCompanies/view/'.$encode_id;
		
		$JqdTablescr = $this->JqdTable->create($option);
		
		$out					= array();
		$arrInstallerPlanList 	= $arrInstallerPlanList->toArray();
		$period 				= $this->period;
		$limit 					= $this->intLimit;
		$CurrentPage 			= $this->CurrentPage;
		$SortBy 				= $this->SortBy;
		$Direction 				= $this->Direction;
		$page_count 			= (isset($this->request->params['paging']['InstallerCompanies']['pageCount'])?$this->request->params['paging']['InstallerCompanies']['pageCount']:0);
		$this->set(compact('arrInstallerPlanList', 'JqdTablescr', 'period', 'limit', 'CurrentPage', 'SortBy', 'Direction', 'page_count'));
	
		$blnEditCompanyRights		= $this->Userright->checkadminrights($this->Userright->EDIT_INSTALLER);
		foreach($arrInstallerPlanList as $key=>$val) {
			$temparr=array();
			foreach($option['colName'] as $key) {
				if(isset($val[$key])) {
					$temparr[$key]=$val[$key];
				}
				if($key=='action') {  
					$temparr['action']='<a target="_blank" title="View Record" href="'.WEB_ADMIN_URL.'InstallerCompanies/view/'.encode($val['id']).'"><i class="fa fa-eye"></i>
</a>';
					
				}		
			}
			$out[]=$temparr;
		}
		if ($this->request->is('ajax')){
			header('Content-type: application/json');
			echo json_encode(array(	"draw" 			  => intval($this->request->data['draw']),
									"recordsTotal"    => intval($this->request->params['paging']['InstallerCompanies']['count']),
									"recordsFiltered" => intval($this->request->params['paging']['InstallerCompanies']['count']),
									"data"            => $out));
			die;
		}
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
		$this->intCurAdminUserRight = $this->Userright->EDIT_INSTALLER;
		$id = intval(decode($id));
		$this->setAdminArea();

		if(((int)$id)==0) $this->redirect('index');

		if($this->InstallerCompanies->updateAll(['status' => 0], ['id' => $id]))
		{
			$this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->EDIT_INSTALLER,$id,'Inactivated Installer Companies user id :: '.$id);
			$this->Flash->set('Installer Companies has been De-Activated.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
			return $this->redirect(WEB_ADMIN_URL.'InstallerCompanies/view/'.encode($id));
			exit;
		}
		else
		{
			$this->Flash->set('Installer Companies De-Activation Error! Contact Administrator.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'error']]);
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
		$this->intCurAdminUserRight = $this->Userright->EDIT_INSTALLER;
		$id = intval(decode($id));
		$this->setAdminArea();

		if(((int)$id)==0) $this->redirect('index');
		if($this->InstallerCompanies->updateAll(['status' => 1], ['id' => $id]))
		{
			$this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->EDIT_INSTALLER,$id,'Activated Installer Companies id :: '.$id);
			$this->Flash->set('Installer Companies has been Activated.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
			return $this->redirect(WEB_ADMIN_URL.'InstallerCompanies/view/'.encode($id));
			exit;
		}
		else
		{
			$this->Flash->set('Installer Companies Activation Error! Contact Administrator.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'error']]);
			exit;
		}
	}
	/**
	 *
	 * viewbranches
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to view installer branches
	 *
	 */
	public function viewbranches($id= null)
	{
		if(empty($id)) {
			$this->Flash->error('Please Select Valid Installer.');             
			return $this->redirect(WEB_ADMIN_PREFIX.'/InstallerCompanies');
		} else {
			$encode_id 		= $id;
			$id 			= intval(decode($id));
			$customerData	= $this->InstallerCompanies->get($id);
		}
		$arrInstallerPlanList	= array();
		$this->SortBy			= "InstallerCompanies.id";
		$this->Direction		= "ASC";
		$this->intLimit			= PAGE_RECORD_LIMIT;
		$this->CurrentPage  	= 1;
		$option 				= array();
		$option['colName']  	= array('id','installer_name','address','state','contact','action');
		$this->SetSortingVars('InstallerCompanies',$option);
		$company_id 			= !empty($customerData['company_id'])?$customerData['company_id']:0;
		if(!empty($customerData['company_id'])) {
			$conditions = array('InstallerCompanies.company_id'=>$customerData['company_id'],'InstallerCompanies.id NOT IN'=>$id);	
		}
		$this->paginate			= array('conditions' 	=> 	$conditions,
										'order'		 	=>	array($this->SortBy => $this->Direction),
										'page'			=> 	$this->CurrentPage,
										'limit' 		=> 	$this->intLimit);
		$arrInstallerPlanList	= $this->paginate('InstallerCompanies');
		$out					= array();
		$arrInstallerPlanList 	= $arrInstallerPlanList->toArray();
		foreach($arrInstallerPlanList as $key=>$val) {
			$temparr=array();
			foreach($option['colName'] as $key) {
				if(isset($val[$key])) {
					$temparr[$key]=$val[$key];
				}
				if($key=='action') {
					$temparr['action']='<a target="_blank" title="View Record" href="'.WEB_ADMIN_URL.'InstallerCompanies/view/'.encode($val['id']).'"><i class="fa fa-eye"></i></a>';
				}
			}
			$out[]=$temparr;
		}
		header('Content-type: application/json');
		echo json_encode(array(	"draw" 			  => intval($this->request->data['draw']),
								"recordsTotal"    => intval($this->request->params['paging']['InstallerCompanies']['count']),
								"recordsFiltered" => intval($this->request->params['paging']['InstallerCompanies']['count']),
								"data"            => $out));
		die;
	}

	/**
	 *
	 * viewsubusers
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to view installer users
	 *
	 */
	public function viewsubusers($id= null)
	{
		if(empty($id)) {
			$this->Flash->error('Please Select Valid Installer.');             
			return $this->redirect(WEB_ADMIN_PREFIX.'/InstallerCompanies');
		} else {
			$encode_id 		= $id;
			$id 			= intval(decode($id));
			$customerData	= $this->InstallerCompanies->get($id);
		}
		$arrUserRoles 			= $this->Parameters->getCustomreUserRights();
		$arrInstallerPlanList	= array();
		$this->SortBy			= "Customers.id";
		$this->Direction		= "ASC";
		$this->intLimit			= PAGE_RECORD_LIMIT;
		$this->CurrentPage  	= 1;
		$option 				= array();
		$option['colName']  	= array('id','name','email','mobile','user_role','default_admin','status','last_login_date');
		$this->SetSortingVars('Customers',$option);
		$installer_id 			= !empty($customerData['id'])?$customerData['id']:0;
		$conditions 			= array('Customers.installer_id'=>$installer_id);
		$this->paginate			= array('conditions' 	=> 	$conditions,
										'order'		 	=>	array($this->SortBy => $this->Direction),
										'page'			=> 	$this->CurrentPage,
										'limit' 		=> 	$this->intLimit);
		$arrSubusers			= $this->paginate('Customers');
		$out					= array();
		$arrSubusersList 		= $arrSubusers->toArray();
		foreach($arrSubusersList as $key=>$val) {
			$temparr = array();
			foreach($option['colName'] as $key) {
				switch ($key) {
					case 'last_login_date':
						$temparr[$key] = date("Y-m-d H:i:s",strtotime($val[$key]));
						break;
					case 'default_admin':
						$temparr[$key] = empty($val[$key])?"NO":"YES";
						break;
					case 'user_role':
						$role_ids 	= explode(",",$val[$key]);
						$Roles 		= "";
						if (!empty($role_ids)) {
							foreach ($role_ids as $role_id) {
								$Roles .= isset($arrUserRoles[$role_id])?$arrUserRoles[$role_id].", ":"";
							}
						}
						$temparr[$key] 	= !empty($Roles)?trim($Roles,", "):"-";
						break;
					case 'status':
						$temparr[$key] = empty($val[$key])?"IN-ACTIVE":"ACTIVE";
						break;
					default:
						$temparr[$key] = (isset($val[$key]) && !empty($val[$key])?$val[$key]:"-");
						break;
				}
			}
			$out[] = $temparr;
		}
		header('Content-type: application/json');
		echo json_encode(array(	"draw" 			  => intval($this->request->data['draw']),
								"recordsTotal"    => intval($this->request->params['paging']['Customers']['count']),
								"recordsFiltered" => intval($this->request->params['paging']['Customers']['count']),
								"data"            => $out));
		die;
	}
}