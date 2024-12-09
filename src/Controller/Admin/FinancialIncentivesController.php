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

class FinancialIncentivesController extends AppController 
{	
	var $arrDefaultAdminUserRights = array(); 
	
	var $helpers = array('Time','Html','Form','ExPaginator');

	var $parameter_status = array('A'=>"Active",'I'=>"In-Active");

    /*
	 * initialize controller
	 *
	 * @return void
	 */
	public function initialize()
    {
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
		$this->loadModel('ApiToken');
		$this->loadModel('FinancialIncentives');
		$this->loadModel('Parameters');

		$this->set('Userright',$this->Userright);
    }

	/*
	 * Displays a index
	 *
	 * @param mixed What page to display
	 *
	 * @return void
	 */
	public function index() {
		
		$this->intCurAdminUserRight = $this->Userright->LIST_FINANCIAL_INCENTIVES;
		$this->setAdminArea();
		
		if (!empty($this->FinancialIncentives->validate)) {
			foreach ($this->FinancialIncentives->validate as $field => $rules) {
				$this->FinancialIncentives->validator()->remove($field);
			}
		}
		
		$arrIncentiveList	= array();
		$arrCondition		= array();
		$this->SortBy		= "FinancialIncentives.id";
		$this->Direction	= "ASC";
		$this->intLimit		= PAGE_RECORD_LIMIT;
		$this->CurrentPage  = 1;
		$option 			= array();
		$option['colName']  = array('state','action');
		
		$this->SetSortingVars('FinancialIncentives',$option);
		$arrCondition	= $this->_generateFinancialIncentivesearchCondition(); 
		$this->paginate	= array('fields'=>['FinancialIncentives.id','FinancialIncentives.state','FinancialIncentives.settlement_period','FinancialIncentives.minimum_capacity','FinancialIncentives.max_contract_load'],
								'conditions' => $arrCondition,
								'order'=>array($this->SortBy=>$this->Direction),
								'page'=> $this->CurrentPage,
								'limit' => $this->intLimit);
		
		$arrIncentiveList		= $this->paginate('FinancialIncentives');
		$option['dt_selector']	='table-example';
		$option['formId']		='index-formmain';
		$option['url']			= WEB_ADMIN_PREFIX.'FinancialIncentives';
		
		$JqdTablescr = $this->JqdTable->create($option);
		$this->set('arrIncentiveList',$arrIncentiveList->toArray());
		$this->set('para_status',$this->para_status);
		$this->set('JqdTablescr',$JqdTablescr);
		$this->set('period',$this->period);
		$this->set('limit',$this->intLimit);
		$this->set("CurrentPage",$this->CurrentPage);
		$this->set("SortBy",$this->SortBy);
		$this->set("Direction",$this->Direction);
		$this->set("page_count",(isset($this->request->params['paging']['FinancialIncentives']['pageCount'])?$this->request->params['paging']['FinancialIncentives']['pageCount']:0));
		$out = array();
		$blnEditIncentiveRights	= $this->Userright->checkadminrights($this->Userright->EDIT_FINANCIAL_INCENTIVES);
		
		foreach($arrIncentiveList->toArray() as $key=>$val) {
			$temparr = array();
			foreach($option['colName'] as $key) {
				if(isset($val[$key])) {
					$temparr[$key] = $val[$key];
				}
				if($key == 'action') {
					$temparr['action'] = '';
					if($blnEditIncentiveRights)
						$temparr['action'] .= $this->Userright->linkEditFinancialIncentive(constant('WEB_URL').constant('ADMIN_PATH').'financialIncentives/edit/'.encode($val['id']),'<i class="fa fa-edit"> </i>','','editRecord',' title="Edit"')."&nbsp;";
				}		
			}
			$out[] = $temparr;
		}
		if ($this->request->is('ajax')){
			header('Content-type: application/json');
			echo json_encode(array(	"draw" 			  => intval($this->request->data['draw']),
									"recordsTotal"    => intval($this->request->params['paging']['FinancialIncentives']['count']),
									"recordsFiltered" => intval($this->request->params['paging']['FinancialIncentives']['count']),
									"data"            => $out));
			die;
		}
	}

	/**
	 *
	 * financial_incentive_add
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to add new financial incentive.
	 *
	 */
	public function add()
	{
		$this->intCurAdminUserRight = $this->Userright->ADD_FINANCIAL_INCENTIVES;
		$this->setAdminArea();
		
		$arrAdminDefaultRights = array();
		$timezone = '';
		$arrError = array();

		$FinancialIncentives = $this->FinancialIncentives->newEntity($this->request->data());
		if (!$FinancialIncentives->errors() && !empty($this->request->data)) {
			
			$FinancialIncentives->created 		= $this->NOW();
			$FinancialIncentives->created_by 	= $this->Session->read('User.id');
			if ($this->FinancialIncentives->save($FinancialIncentives)) { 
				$this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->ADD_FINANCIAL_INCENTIVES,$FinancialIncentives->id,'Added Financial Incentives id::'.$FinancialIncentives->id);
				$this->Flash->set('Financial Incentives added successfully.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
                return $this->redirect('admin/financialIncentives');
			}
		}
		$this->set('para_status', $this->parameter_status);
		$this->set('data',	$this->request->data);
		$this->set(compact('FinancialIncentives', 'arrError'));
	}

	/**
	 *
	 * _generateFinancialIncentivesearchCondition
	 *
	 * Behaviour : Private
	 *
	 * @param : $id  : Id is use to identify for which incntive condition to be generated if its not null 
	 *
	 * @defination : Method is use to generate search condition using which incentive data can be listed
	 *
	 */
	private function _generateFinancialIncentivesearchCondition($id=null)
	{
		$arrCondition	= array();
		$blnSinCompany	= true;
		
		if(!empty($id)) $this->request->data['FinancialIncentives']['id'] = $id;
		if(count($this->request->data)==0) $this->request->data['FinancialIncentives']['status'] = $this->STATUS_ACTIVE;
		if(isset($this->request->data) && count($this->request->data)>0) { 

            if(isset($this->request->data['FinancialIncentives']['id']) && trim($this->request->data['FinancialIncentives']['id'])!='') {
                $strID = trim($this->request->data['FinancialIncentives']['id'],',');
                $arrCondition['FinancialIncentives.id'] = $this->request->data['FinancialIncentives']['id'];
            }

			if(isset($this->request->data['FinancialIncentives']['state']) && $this->request->data['FinancialIncentives']['state']!='') {
                $arrCondition['FinancialIncentives.state LIKE'] = '%'.$this->request->data['FinancialIncentives']['state'].'%';
            }

			if(isset($this->request->data['FinancialIncentives']['status']) && !empty($this->request->data['FinancialIncentives']['status'])) {
                $status = $this->request->data['FinancialIncentives']['status'];
                $arrCondition['FinancialIncentives.status'] = $status;
            }	

			if(isset($this->request->data['FinancialIncentives']['search_date']) && $this->request->data['FinancialIncentives']['search_date']!='') {
            	$arrDate = array('FinancialIncentives.created');
				if(in_array($this->request->data['FinancialIncentives']['search_date'], $arrDate)) {
					if($this->request->data['FinancialIncentives']['search_period'] == 1 || $this->request->data['FinancialIncentives']['search_period'] == 2) {
						$arrSearchPara	= $this->FinancialIncentives->setSearchDateParameter($this->request->data['FinancialIncentives']['search_period'],'FinancialIncentives');
						$this->request->data['FinancialIncentives'] = array_merge($this->request->data['FinancialIncentives'],$arrSearchPara['FinancialIncentives']);
						$this->dateDisabled	= true;
					}
					$arrperiodcondi = $this->FinancialIncentives->findConditionByPeriod($this->request->data['FinancialIncentives']['search_date'],
																				$this->request->data['FinancialIncentives']['search_period'],
																				$this->request->data['FinancialIncentives']['DateFrom'],
																				$this->request->data['FinancialIncentives']['DateTo'],
																				$this->Session->read('User.timezone'));
					$arrCondition = array_merge($arrCondition,$arrperiodcondi);
				}
			}
			return $arrCondition;
		}
	}

	/**
	 *
	 * financial_incentive_edit
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to edit financial incentive.
	 *
	 */
	function edit($id = null)
	{
		$this->intCurAdminUserRight = $this->Userright->EDIT_FINANCIAL_INCENTIVES;
		$this->setAdminArea();
		$decode 	= intval(decode($id));
		$arrAdminDefaultRights	= array();
		$blnSaved 	= false;
		$arrError 	= array();
		
		if(!empty($this->request->data)){
			$this->request->data['FinancialIncentives']['id'] = $id;
		}
		$incentiveData 			= $this->FinancialIncentives->get($decode);
		$FinancialIncentives 	= $this->FinancialIncentives->patchEntity($incentiveData, $this->request->data());
		
		if (!$FinancialIncentives->errors() && !empty($this->request->data)) {
			$FinancialIncentives = $this->FinancialIncentives->patchEntity($incentiveData, $this->request->data);
			$FinancialIncentives->id = $decode;
			
			if($this->FinancialIncentives->save($FinancialIncentives)) {
				$this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->EDIT_FINANCIAL_INCENTIVES,$id,'Edited Financial Incentives Id::'.$id);
				$blnSaved = true; 
				$this->Flash->set('Financial Incentives has been updated',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
				return $this->redirect(array('action'=>'index'));
			}
		} else if(!$FinancialIncentives->errors()) { 
			$FinancialIncentives->id 	= $id;
			if (empty($FinancialIncentives->id)) {
				$this->Flash->set('Invalid Request',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'warning']]);
				return $this->redirect(array('action'=>'index'));
				exit();
			}
		}
		
		$this->set('FinancialIncentives',$FinancialIncentives);
		$this->set('data',$this->request->data);
		$this->set('para_status',$this->parameter_status);
		$this->set('arrError', $arrError);
	}
}