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

class SubsidyController extends AppController
{	
	public $user_department = array();
	public $arrDefaultAdminUserRights = array(); 
	public $helpers = array('Time','Html','Form','ExPaginator');
	public $PAGE_NAME = '';
	
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
		$this->loadModel('StateSubsidy');
		$this->loadModel('Adminaction');
		$this->loadModel('States');
		$this->loadModel('Parameters');
		$this->set('Userright',$this->Userright);
    }
	/*
	 * Displays a index
	 *
	 * @param mixed What page to display
	 * @return void
	 */
	public function index() 
	{
		$this->intCurAdminUserRight = $this->Userright->LIST_SUBSIDY;
		$this->setAdminArea();
		$arrcustomerList	= array();
		$arrCondition		= array();
		$this->SortBy		= "StateSubsidy.id";
		$this->Direction	= "ASC";
		$this->intLimit		= PAGE_RECORD_LIMIT;
		$this->CurrentPage  = 1;
		$option 			= array();
		$option['colName']  = array('id','state','customer_type','state_subcidy_type','state_subsidy','state_capacity',
									'central_subcidy_type','central_subsidy','central_capacity',
									'other_subcidy_type','other_subsidy','other_capacity',
									'created','action');
		$this->SetSortingVars('StateSubsidy',$option);
		$option['dt_selector']	='table-example';
		$option['formId']		='formmain';
		$option['lengthChange']	='false';
		$option['url']			= WEB_ADMIN_PREFIX.'subsidy';
		$JqdTablescr 			= $this->JqdTable->create($option);
		$CustomerType 			= $this->Parameters->GetParameterList($this->StateSubsidy->CUSTOMER_TYPE_PARA_ID);
		$this->set('CustomerTypes',$CustomerType);
		$this->set('JqdTablescr',$JqdTablescr);
		$this->set('period',$this->period);
		$this->set('limit',$this->intLimit);
		$this->set("CurrentPage",$this->CurrentPage);
		$this->set("SortBy",$this->SortBy);
		$this->set("Direction",$this->Direction);
		if ($this->request->is('ajax')) {
			$arrCondition		= $this->_generateSearchCondition();
			$this->paginate		= array('conditions' => $arrCondition,
										'order'=> array($this->SortBy => $this->Direction),
										'page'=> $this->CurrentPage,
										'limit' => $this->intLimit);
			$arrSubsidyList		= $this->paginate('StateSubsidy');
			$out 				= array();
			$blnEditSubsidy		= $this->Userright->checkadminrights($this->Userright->EDIT_SUBSIDY);
			$blnViewSubsidy		= $this->Userright->checkadminrights($this->Userright->VIEW_SUBSIDY);
			$CustomerTypes 		= $CustomerType->toArray();
			foreach($arrSubsidyList->toArray() as $key => $val) {
				$temparr 		= array();
				foreach($option['colName'] as $key) {
					if(isset($val[$key])) {
						if($key == 'state') {
							$temparr[$key] = ucwords($val[$key]);
						} else if($key == 'customer_type') {
							$temparr[$key] = isset($CustomerTypes[$val[$key]])?$CustomerTypes[$val[$key]]:"-";
						} else if($key == 'created') {
							$temparr[$key] = date("d-m-Y H:i:s",strtotime($val[$key]));
						} else if ($key == "state_subcidy_type" || $key == "central_subcidy_type" || $key == "other_subcidy_type") {
							$Subsidy_Type 	= ($val[$key] == 1)?"FIXED":"PERCENTAGE";
							$temparr[$key] 	= $Subsidy_Type;
						} else {
							$temparr[$key] = $val[$key];
						}
					} else {
						$temparr[$key] = '';
					}
					if($key=='action') {
						$temparr['action'] = '';
						if($blnEditSubsidy) {
							$EditLink 			= constant('WEB_URL').constant('ADMIN_PATH').'subsidy/manage/'.encode($val['id']);
							$EditIcon 			= '<i class="fa fa-edit"></i>';
							$temparr['action'] .= $this->Userright->makeLink($this->Userright->EDIT_SUBSIDY,$EditLink,$EditIcon,false,' title="Edit Subsidy"');
						}
					}
				}
				$out[] = $temparr;
			}
			header('Content-type: application/json');
			echo json_encode(array(
			"recordsTotal"    => intval( $this->request->params['paging']['StateSubsidy']['count']),
			"recordsFiltered" => intval( $this->request->params['paging']['StateSubsidy']['count']),
			"data"            => $out));
			die;
		}
	}

	/**
	 *
	 * _generateSearchCondition
	 *
	 * Behaviour : Private
	 *
	 * @param : $id  : Id is use to identify for which search condition to be generated if its not null
	 * @defination : Method is use to generate search condition using which search user data can be listed
	 *
	 */
	private function _generateSearchCondition($id=null)
	{
		$arrCondition			= array();
		if(!empty($id)) $this->request->data['Subsidy']['id'] = $id;
		if(isset($this->request->data) && count($this->request->data)>0)
        {
            if(isset($this->request->data['Subsidy']['id']) && trim($this->request->data['Subsidy']['id'])!='')
            {
                $strID = trim($this->request->data['Subsidy']['id'],',');
                $arrCondition['Subsidy.id'] = $this->request->data['Subsidy']['id'];
            }
			if(isset($this->request->data['Subsidy']['state']) && $this->request->data['Subsidy']['state']!='')
            {
                $arrCondition['StateSubsidy.state LIKE'] = '%'.strtolower($this->request->data['Subsidy']['state']).'%';
            }
			if(isset($this->request->data['Subsidy']['customer_type']) && $this->request->data['Subsidy']['customer_type']!='')
            {
                $arrCondition['StateSubsidy.customer_type'] = $this->request->data['Subsidy']['customer_type'];
            }
		}
		return $arrCondition;
	}

	/**
	 *
	 * manage
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to add/edit record
	 *
	 */
	public function manage($id = '')
	{
		$this->intCurAdminUserRight = $this->Userright->EDIT_SUBSIDY;
		$this->setAdminArea();
		$state_list 	= $this->States->find('list',['keyField'=>'statename','valueField'=>'statename'])->toArray();
		$arrStates 		= array();
		if(!empty($state_list)) {
			foreach ($state_list as $key => $value) {
				$state_key 				= strtolower(preg_replace("/[^a-z0-9]/i","",$key));
				$arrStates[$state_key] 	= $value;
			}
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if(!empty($id)) {
				$id 						= decode($id);
				$mode 						= 'Edit';
				$SubsidyEntityGet 			= $this->StateSubsidy->get($id);
				$this->StateSubsidy->data 	= $this->request->data();
				if (isset($this->StateSubsidy->data['StateSubsidy']['state'])) {
					$state_key 											= $this->StateSubsidy->data['StateSubsidy']['state'];
					$this->StateSubsidy->data['StateSubsidy']['state'] 	= isset($arrStates[$state_key])?$arrStates[$state_key]:"";
				}
				$this->StateSubsidy->RID 	= $id;
				$StateSubsidyEntity 		= $this->StateSubsidy->patchEntity($SubsidyEntityGet,$this->request->data,['validate'=>'Add']);
				if(!$StateSubsidyEntity->errors()) {
					$StateSubsidyEntity->modified 		= $this->NOW();
					$StateSubsidyEntity->modified_by 	= $this->Session->read('User.id');
					if($this->StateSubsidy->save($StateSubsidyEntity)) {
						if(!empty($id)) {
							$this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->EDIT_STATESUBSIDY,$StateSubsidyEntity->id,'State subsidy updated for id :: '.$StateSubsidyEntity->id);
							$this->Flash->set('State subsidy updated.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
						}
						return $this->redirect(WEB_ADMIN_PREFIX.'/subsidy');
					}
				}
				$this->StateSubsidy->data['StateSubsidy']['state'] = strtolower(preg_replace("/[^a-z0-9]/i","",$this->StateSubsidy->data['StateSubsidy']['state']));
			} else {
				$mode 						= 'Add';
				$this->StateSubsidy->data 	= $this->request->data();
				if (isset($this->StateSubsidy->data['StateSubsidy']['state'])) {
					$state_key 											= $this->StateSubsidy->data['StateSubsidy']['state'];
					$this->StateSubsidy->data['StateSubsidy']['state'] 	= isset($arrStates[$state_key])?$arrStates[$state_key]:"";
				}
				$StateSubsidyEntity 		= $this->StateSubsidy->newEntity($this->request->data,['validate'=>'Add']);
				if(!$StateSubsidyEntity->errors()) {
					$StateSubsidyEntity->created 		= $this->NOW();
					$StateSubsidyEntity->created_by 	= $this->Session->read('User.id');
					$StateSubsidyEntity->modified 		= $this->NOW();
					$StateSubsidyEntity->modified_by 	= $this->Session->read('User.id');
					if($this->StateSubsidy->save($StateSubsidyEntity)) {
						if(!empty($id)) {
							$this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->ADD_STATESUBSIDY,$StateSubsidyEntity->id,'State subsidy added for id :: '.$StateSubsidyEntity->id);
							$this->Flash->set('State subsidy added.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
						}
						return $this->redirect(WEB_ADMIN_PREFIX.'/subsidy');
					}
				}
				$this->StateSubsidy->data['StateSubsidy']['state'] = strtolower(preg_replace("/[^a-z0-9]/i","",$this->StateSubsidy->data['StateSubsidy']['state']));
			}
		} else {
			if(!empty($id)) {
				$id 						= decode($id);
				$StateSubsidyEntity 		= $this->StateSubsidy->get($id);
				$StateSubsidyEntity->state 	= strtolower(preg_replace("/[^a-z0-9]/i","",$StateSubsidyEntity->state));
				$mode 				= 'Edit';
			} else {
				$mode 				= 'Add';
				$StateSubsidyEntity = $this->StateSubsidy->newEntity();
			}
		}
		$arrError 		= array();
		$subsity_types	= array("0"=>"PERCENTAGE","1"=>"FIXED");
		$CustomerType 	= $this->Parameters->GetParameterList($this->StateSubsidy->CUSTOMER_TYPE_PARA_ID)->toArray();
		$this->set('state_list',$arrStates);
		$this->set('CustomerTypes',$CustomerType);
		$this->set('StateSubsidy',$StateSubsidyEntity);
		$this->set('subsity_types',$subsity_types);
		$this->set('mode',$mode);
		$this->set('data',$this->request->data);
		$this->set('arrError', $arrError);
	}
	
}