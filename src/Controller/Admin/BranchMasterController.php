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

class BranchMasterController extends AppController
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

		$this->loadModel('Users');
		$this->loadModel('Adminaction');
		$this->loadModel('States');
		$this->loadModel('BranchMasters');
		$this->set('Userright',$this->Userright);
    }
	/*
	 * Displays a index
	 *
	 * @param mixed What page to display
	 * @return void
	 */
	public function index() {
		
		$this->intCurAdminUserRight = $this->Userright->LIST_BRANCH_MASTER;
		$this->setAdminArea();
		
		if (!empty($this->BranchMasters->validate)) {
			foreach ($this->BranchMasters->validate as $field => $rules) {
				$this->BranchMasters->validator()->remove($field); //Remove all validation in search page
			}
		}
		
		$arrcustomerList	= array();
		$arrCondition		= array();
		$this->SortBy		= "BranchMasters.id";
		$this->Direction	= "ASC";
		$this->intLimit		= PAGE_RECORD_LIMIT;
		$this->CurrentPage  = 1;
		$option 			= array();
		$option['colName']  = array('id','title','city','area','created','action');
		
		$this->SetSortingVars('BranchMasters',$option);
		$arrCondition		= $this->_generateCustomerSearchCondition();
		
		$this->paginate		= array('conditions' => $arrCondition,
									'order'=>array($this->SortBy => $this->Direction),
									'page'=> $this->CurrentPage,
									'limit' => $this->intLimit);
		$arrcustomerList	= $this->paginate('BranchMasters');
		$arrUserType['']	= "Select";
		$usertypes = array();

		$option['dt_selector']	='table-example';
		$option['formId']		='formmain';
		$option['url']			= WEB_ADMIN_PREFIX.'BranchMaster';
		$JqdTablescr 			= $this->JqdTable->create($option);
		$main_branch = $this->BranchMasters->find("list",['keyField'=>'id','valieField'=>'title','conditions'=>['parent_id'=>'0']]);
		$state_list = $this->States->find('all',['keyField'=>'id','valueField'=>'statename'])->toArray();
		if(empty($state_list)){
			$state_list = array();
		}
		$this->set('state_list',$state_list);
		$this->set('main_branch',$main_branch);
		$this->set('arrcustomerList',$arrcustomerList->toArray());
		$this->set('JqdTablescr',$JqdTablescr);
		$this->set('arrUserType',$arrUserType);
		$this->set('period',$this->period);
		$this->set('limit',$this->intLimit);
		$this->set("CurrentPage",$this->CurrentPage);
		$this->set("SortBy",$this->SortBy);
		$this->set("Direction",$this->Direction);
		$this->set("page_count",(isset($this->request->params['paging']['BranchMasters']['pageCount'])?$this->request->params['paging']['BranchMasters']['pageCount']:0));
		$out = array();
		
		$blnEditBranchMastersRights		= $this->Userright->checkadminrights($this->Userright->EDIT_BRANCH_MASTER);
		foreach($arrcustomerList->toArray() as $key => $val) {
			$temparr = array();
			foreach($option['colName'] as $key) {
				if(isset($val[$key])) {
					if($key == 'last_login_date' || $key == 'created')
						$temparr[$key] = date("d-m-Y H:i:s",strtotime($val[$key]));
					else
						$temparr[$key] = $val[$key];
				} else {
					$temparr[$key]='';
				}
				
				if($key=='action') {
					$temparr['action'] = '';
					if($blnEditBranchMastersRights) {
						if($val['parent_id'] != 0){
							$temparr['action'].= $this->Userright->linkEditBranchMaster(constant('WEB_URL').constant('ADMIN_PATH').'BranchMaster/manage/0/'.encode($val['id']),'<i class="fa fa-edit"></i>','','actionRecord',' title="Edit Member"')."&nbsp;";
						} else {
							$temparr['action'].= $this->Userright->linkEditBranchMaster(constant('WEB_URL').constant('ADMIN_PATH').'BranchMaster/manage/1/'.encode($val['id']),'<i class="fa fa-edit"></i>','','actionRecord',' title="Edit Member"')."&nbsp;";
						}
					}
				}
			}
			$out[] = $temparr;
		}
		if ($this->request->is('ajax')) {
			header('Content-type: application/json');
			echo json_encode(array('condi'=>$arrCondition,"draw" => intval($this->request->data['draw']),
			"recordsTotal"    => intval( $this->request->params['paging']['BranchMasters']['count']),
			"recordsFiltered" => intval( $this->request->params['paging']['BranchMasters']['count']),
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
		if(!empty($id)) $this->request->data['BranchMasters']['id'] = $id;
		//$arrCondition['BranchMasters.parent_id !='] = 0;
		if(isset($this->request->data) && count($this->request->data)>0)
        {
            if(isset($this->request->data['BranchMasters']['id']) && trim($this->request->data['BranchMasters']['id'])!='')
            {
                $strID = trim($this->request->data['BranchMasters']['id'],',');
                $arrCondition['BranchMasters.id'] = $this->request->data['BranchMasters']['id'];
            }
			if(isset($this->request->data['BranchMasters']['parent_id']) && $this->request->data['BranchMasters']['parent_id']!='')
            {
                $arrCondition['BranchMasters.parent_id'] = $this->request->data['BranchMasters']['parent_id'];
            }

			if(isset($this->request->data['BranchMasters']['title']) && $this->request->data['BranchMasters']['title']!='')
            {
                $arrCondition['BranchMasters.title'] = '%'.$this->request->data['BranchMasters']['title'].'%';
            }

			if(isset($this->request->data['BranchMasters']['city']) && $this->request->data['BranchMasters']['city']!='')
            {
                $arrCondition['BranchMasters.city LIKE'] = '%'.$this->request->data['BranchMasters']['city'].'%';
            }
			if(isset($this->request->data['BranchMasters']['area']) && $this->request->data['BranchMasters']['area']!='')
            {
                $arrCondition['BranchMasters.area LIKE'] = '%'.$this->request->data['BranchMasters']['area'].'%';
            }
			if(isset($this->request->data['BranchMasters']['search_date']) && $this->request->data['BranchMasters']['search_date']!='')
            {
                if($this->request->data['BranchMasters']['search_period'] == 1 || $this->request->data['BranchMasters']['search_period'] == 2)
                {
                	$arrSearchPara	= $this->BranchMasters->setSearchDateParameter($this->request->data['BranchMasters']['search_period'],$this->modelClass);
                	
                	$this->request->data[$this->modelClass] = array_merge($this->request->data[$this->modelClass],$arrSearchPara[$this->modelClass]);
                    $this->dateDisabled						= true;
                }
                $arrperiodcondi = $this->BranchMasters->findConditionByPeriod( $this->request->data['BranchMasters']['search_date'],
																		$this->request->data['BranchMasters']['search_period'],
																		$this->request->data['BranchMasters']['DateFrom'],
																		$this->request->data['BranchMasters']['DateTo'],
																		$this->Session->read('BranchMasters.timezone'));
               	if(!empty($arrperiodcondi)){
                	$arrCondition['between'] = $arrperiodcondi['between'];
                }
            }
		}
		return $arrCondition;
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
	public function manage($is_main = '',$id = '')
	{
		if(!empty($id)) {
			$this->intCurAdminUserRight = $this->Userright->EDIT_BRANCH_MASTER;
			$this->setAdminArea();
			$id = decode($id);
			$BranchMastersEntityGet = $this->BranchMasters->get($id);
			if($is_main == 1){
				$BranchMastersEntity = $this->BranchMasters->patchEntity($BranchMastersEntityGet,$this->request->data() ,['validate' => 'main_edit']);
			} else {
				$BranchMastersEntity = $this->BranchMasters->patchEntity($BranchMastersEntityGet,$this->request->data() ,['validate' => 'edit']);
			}
			$mode = 'Edit';
		} else {
			$this->intCurAdminUserRight = $this->Userright->ADD_BRANCH_MASTER;
			$this->setAdminArea();
			if($is_main == 1){
				$BranchMastersEntity = $this->BranchMasters->newEntity($this->request->data() ,['validate' => 'main_add']);
			} else {
				$BranchMastersEntity = $this->BranchMasters->newEntity($this->request->data() ,['validate' => 'add']);
			}
			$mode = 'Add';
		}

		$arrAdminDefaultRights = array();
		$timezone = '';
		$arrError = array();

		if(!empty($this->request->data)) {
			if(!$BranchMastersEntity->errors()) {
				
				if (empty($id)) {
					$BranchMastersEntity->created 	= $this->NOW();
					$BranchMastersEntity->created_by 	= $this->Session->read('User.id');
				} else {
					$BranchMastersEntity->updated 	= $this->NOW();
					$BranchMastersEntity->updated_by 	= $this->Session->read('User.id');
				}
				if($this->BranchMasters->save($BranchMastersEntity)) {
					if(!empty($id)) {
						$this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->EDIT_BRANCH_MASTER,$BranchMastersEntity->id,'Added BranchMasters user id::'.$BranchMastersEntity->id);
						$this->Flash->set('BranchMasters has been updated.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
					} else {
						$this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->ADD_BRANCH_MASTER,$BranchMastersEntity->id,'Added BranchMasters user id::'.$BranchMastersEntity->id);
						$this->Flash->set('BranchMasters has been saved.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
					}

					return $this->redirect(WEB_ADMIN_PREFIX.'/BranchMaster');
				}
			}
		}
		$main_branch = $this->BranchMasters->find("list",['keyField'=>'id','valieField'=>'title','conditions'=>['parent_id'=>'0']]);
		$state_list = $this->States->find('list',['keyField'=>'id','valueField'=>'statename'])->toArray();
		if(empty($state_list)){
			$state_list = array();
		}
		$this->set('state_list',$state_list);
		$this->set('main_branch',$main_branch);
		$this->set('is_main',$is_main);
		$this->set('BranchMasters',$BranchMastersEntity);
		$this->set('mode',$mode);
		$this->set('emailrights',array());
		$this->set('data',$this->request->data);
		$this->set('timezone',$timezone);
		$this->set('DEFAULT_USER_TIMEZONE', $this->Users->DEFAULT_USER_TIMEZONE);
		$this->set('arrError', $arrError);
	}
	
}