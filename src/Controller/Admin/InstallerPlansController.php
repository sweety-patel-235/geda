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

/**
 *
 * Installer Plan Controller
 *
 * @defination : Class is used for managing the plans for installer user in the site.
 *
 * Author : Khushal Bhalsod
 */
class InstallerPlansController extends AppController
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
		$this->loadModel('InstallerPlans');
		$this->loadModel('Adminaction');
		$this->set('Userright',$this->Userright);
    }

    /**
	 *
	 * SetVariables
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to set varibles.
	 *
	 * Author : Khushal Bhalsod
	 */
    private function SetVariables($post_variables) {
		if(isset($post_variables['id']))
			$this->request->data['InstallerPlans']['id']	= $post_variables['id'];
	}

	/*
	 * Displays a index
	 *
	 * Behaviour: Public
	 *
	 * @defination: method is use to list installation plans
	 */
	public function index() {
		
		$this->intCurAdminUserRight = $this->Userright->LIST_INSTALLER_PLAN;
		$this->setAdminArea();
		
		if (!empty($this->InstallerPlans->validate)) {
			foreach ($this->InstallerPlans->validate as $field => $rules) {
				$this->InstallerPlans->validator()->remove($field); //Remove all validation in search page
			}
		}
		$arrInstallerPlanList	= array();
		$arrCondition		= array();
		$this->SortBy		= "InstallerPlans.id";
		$this->Direction	= "ASC";
		$this->intLimit		= PAGE_RECORD_LIMIT;
		$this->CurrentPage  = 1;/*((isset($this->request->data['start']) ? $this->request->data['start'] : '10')/$this->intLimit)+1*/
		$option=array();
		$option['colName']  = array('id','plan_name','plan_price','user_limit','action');
		
		$this->SetSortingVars('InstallerPlans',$option);

		$arrCondition			= $this->_generateInstallerPlanSearchCondition();
		$this->paginate			= array('conditions' 	=> 	$arrCondition,
										'order'		 	=>	array($this->SortBy => $this->Direction),
										'page'			=> 	$this->CurrentPage,
										'limit' 		=> 	$this->intLimit);
		$arrInstallerPlanList	= $this->paginate('InstallerPlans');
		
		$option['dt_selector']	='table-example';
		$option['formId']		='index-formmain';
		$option['url']			= WEB_ADMIN_PREFIX.'installerPlans';
		
		$JqdTablescr = $this->JqdTable->create($option);
		
		$out					= array();
		$arrInstallerPlanList 	= $arrInstallerPlanList->toArray();
		$period 				= $this->period;
		$limit 					= $this->intLimit;
		$CurrentPage 			= $this->CurrentPage;
		$SortBy 				= $this->SortBy;
		$Direction 				= $this->Direction;
		$page_count 			= (isset($this->request->params['paging']['InstallerPlans']['pageCount'])?$this->request->params['paging']['InstallerPlans']['pageCount']:0);
		$this->set(compact('arrInstallerPlanList', 'JqdTablescr', 'period', 'limit', 'CurrentPage', 'SortBy', 'Direction', 'page_count'));
	
		$blnEditPlanRights		= $this->Userright->checkadminrights($this->Userright->EDIT_INSTALLER_PLAN);
		foreach($arrInstallerPlanList as $key=>$val) {
			$temparr=array();
			foreach($option['colName'] as $key) {
				if(isset($val[$key])) {
					$temparr[$key]=$val[$key];
				}
				if($key=='action') {
					$temparr['action']='';
					if($blnEditPlanRights){
						$temparr['action'].= $this->Userright->linkEditAdminuser(constant('WEB_URL').constant('ADMIN_PATH').'InstallerPlans/edit/'.encode($val['id']),'<i class="fa fa-edit"> </i>','','editRecord',' title="Edit Plan Info"')."&nbsp;";
						if(empty($val['status']))
							$temparr['action'].= $this->Userright->linkEnableAdminuser(constant('WEB_URL').constant('ADMIN_PATH').'InstallerPlans/enable/'.encode($val['id']),'<i class="fa fa-check-circle-o"></i>','','actionRecord',' title="Activate"')."&nbsp;";
						if(!empty($val['status']))
							$temparr['action'].= $this->Userright->linkDisableAdminuser(constant('WEB_URL').constant('ADMIN_PATH').'InstallerPlans/disable/'.encode($val['id']),'<i class="fa fa-circle-o"></i>','','actionRecord',' title="De-Activate"')."&nbsp;";
					}
				}		
			}
			$out[]=$temparr;
		}
		if ($this->request->is('ajax')){
			header('Content-type: application/json');
			echo json_encode(array(	"draw" 			  => intval($this->request->data['draw']),
									"recordsTotal"    => intval($this->request->params['paging']['InstallerPlans']['count']),
									"recordsFiltered" => intval($this->request->params['paging']['InstallerPlans']['count']),
									"data"            => $out));
			die;
		}
	}

	/**
	 *
	 * _generateInstallerPlanSearchCondition
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use generate installer plan search condition.
	 *
	 * Author : Khushal Bhalsod
	 */
	private function _generateInstallerPlanSearchCondition($id=null)
	{
		$arrCondition	= array();		
		if(!empty($id)) $this->request->data['InstallerPlans']['id'] = $id;
		
		if(isset($this->request->data) && count($this->request->data)>0) {
            if(isset($this->request->data['InstallerPlans']['id']) && trim($this->request->data['InstallerPlans']['id'])!='') {
                $strID = trim($this->request->data['InstallerPlans']['id'],',');
                $arrCondition['InstallerPlans.id'] = $this->request->data['InstallerPlans']['id'];
            }
			if(isset($this->request->data['InstallerPlans']['status']) && !empty($this->request->data['InstallerPlans']['status'])) {
                $status = $this->request->data['InstallerPlans']['status'];
				if($this->request->data['InstallerPlans']['status']=='I') $status = $this->STATUS_INACTIVE;
				$arrCondition['InstallerPlans.status'] = $status;
            }
			if(isset($this->request->data['InstallerPlans']['plan_name']) && $this->request->data['InstallerPlans']['plan_name']!='') {
                $arrCondition['InstallerPlans.plan_name LIKE'] = '%'.$this->request->data['InstallerPlans']['plan_name'].'%';
            }
						
			if(isset($this->request->data['InstallerPlans']['search_date']) && $this->request->data['InstallerPlans']['search_date']!='') {
                if($this->request->data['InstallerPlans']['search_period'] == 1 || $this->request->data['InstallerPlans']['search_period'] == 2) {
                	$arrSearchPara	= $this->InstallerPlans->setSearchDateParameter($this->request->data['InstallerPlans']['search_period'],$this->modelClass);
                	$this->request->data[$this->modelClass] = array_merge($this->request->data[$this->modelClass],$arrSearchPara[$this->modelClass]);
                    $this->dateDisabled	= true;
                }
                $arrperiodcondi = $this->InstallerPlans->findConditionByPeriod( $this->request->data['InstallerPlans']['search_date'],
																		$this->request->data['InstallerPlans']['search_period'],
																		$this->request->data['InstallerPlans']['DateFrom'],
																		$this->request->data['InstallerPlans']['DateTo'],
																		$this->Session->read('InstallerPlans.timezone'));
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
	public function add()
	{
		$this->intCurAdminUserRight = $this->Userright->ADD_INSTALLER_PLAN;
		$this->setAdminArea();
		$InstallerPlans = $this->InstallerPlans->newEntity($this->request->data() ,['validate' => 'default']);
		
		$arrAdminDefaultRights = array();
		$timezone = '';
		$arrError = array();

		if(!$InstallerPlans->errors() && !empty($this->request->data)) {
			$newPlan =  $this->InstallerPlans->newEntity($this->request->data['InstallerPlans']);

			if($this->InstallerPlans->save($newPlan)) {
				$this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->ADD_INSTALLER_PLAN,$newPlan->id,'Activated Plan Id :: '.$newPlan->id);
				$this->Flash->set('Installer Plan has been saved.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
				return $this->redirect(WEB_ADMIN_PREFIX.'/installerplans');
			}
		}
		$data = $this->request->data;
		$this->set(compact('InstallerPlans','data','arrError'));
	}


	/**
	 *
	 * installer plan edit
	 *
	 * Behaviour : Public
	 *
	 * @param :  $id  : Id is use to identify for which plan details to be edited
	 * @defination : Method is use to update edited detail of particular installer plan using admin interface
	 *
	 */

	function edit($id = null)
	{
		$this->intCurAdminUserRight = $this->Userright->EDIT_INSTALLER_PLAN;
		$decode = intval(decode($id));
		$this->set('did',$decode);
		$this->setAdminArea();
		
		if(!empty($this->request->data)){
			$this->request->data['InstallerPlans']['id'] = $id;
		}
		$planData = $this->InstallerPlans->get($decode);
		$planEntity = $this->InstallerPlans->patchEntity($planData, $this->request->data(),['validate' => 'default']);
		$arrAdminDefaultRights = array();
		$arrError = array();
		if (!$planEntity->errors() && !empty($this->request->data)) {
			
			$planEntity = $this->InstallerPlans->patchEntity($planData, $this->request->data);
			$planEntity->id = $decode;
			if($this->InstallerPlans->save($planEntity)) {
				$blnSaved = true; 
				$this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->EDIT_INSTALLER_PLAN,$decode,'Edited Plan Id :: '.$decode);
				$this->Flash->set('Plan has been updated',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
				return $this->redirect(array('action'=>'index'));
			}
		} else if(!$planEntity->errors()) { 
			$planEntity->id 		= $id;
			if (empty($planEntity->id)) {
				$this->Flash->set('Invalid Plan',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'warning']]);
				return $this->redirect(array('action'=>'index'));
				exit();
			}
		}
		$this->set('data',$this->request->data);
		$this->set('InstallerPlans',$planEntity);
		$this->set(compact('arrError'));
	}

	/**
	 *
	 * plan_enable
	 *
	 * Behaviour : Public
	 *
	 * @param : $id   : Id is use to identify plan to be activate
	 * @defination : Method is use to enabled the plan which is disabled
	 *
	 */
	function enable($id=null) {
		$this->intCurAdminUserRight = $this->Userright->EDIT_INSTALLER_PLAN;
		$id 	= intval(decode($id));
		$this->setAdminArea();

		if(((int)$id)==0) $this->redirect('index');
		$planArr 			= $this->InstallerPlans->get($id);
		$planArr->status 	= 1;
		
		if($this->InstallerPlans->save($planArr))
		{
			$this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->ACTIVATED_INSTALLER_PLAN,$id,'Activated Plan Id :: '.$id);
			$this->Flash->set('Plan has been Activated.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
			return $this->redirect(array('controller'=> 'installerplans','action'=>'index'));
			exit;
		}
		else
		{
			$this->Flash->set('Plan Activation Error! Contact Administrator.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'error']]);
			exit;
		}
	}

	/**
     *
     * plan_disable
     *
     * Behaviour : Public
     *
     * @param : $id   : Id is use to identify particular plan is to be disabled
     * @defination : Method is use to disable particular plan is active
     *
     */
	function disable($id=null) {
		$this->intCurAdminUserRight = $this->Userright->EDIT_INSTALLER_PLAN;
		$id = intval(decode($id));
		$this->setAdminArea();

		if(((int)$id)==0) $this->redirect('index');

		if($this->InstallerPlans->updateAll(['status' => 0], ['id' => $id]))
		{
			$this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->IN_ACTIVATED_INSTALLER_PLAN,$id,'Activated Plan Id :: '.$id);
			$this->Flash->set('Plan has been De-Activated.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);		
			return $this->redirect(array('controller'=> 'installerplans','action'=>'index'));
			exit;
		}
		else
		{
			$this->Flash->set('Plan De-Activation Error! Contact Administrator.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'error']]);
		}
	}
	
	
	/**
	 *
	 * getinstallerActiveplan
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to get search installer.
	 *
	 * Author : Khushal Bhalsod
	 */
	public function getinstallerActiveplan()
	{
		$this->autoRender 	= false;
		$installerData 		= array();
		$this->SetVariables($this->request->data);
		
		$arrFiltres['status'] 	= $this->InstallerPlans->STATUS_ACTIVE;
		$installerPlanData  	= $this->InstallerPlans->find('all')->where($arrFiltres)->toArray();
		
		$this->ApiToken->SetAPIResponse('type', 'ok');
		$this->ApiToken->SetAPIResponse('result', $installerPlanData);
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
}
