<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
//use PHPExcel\PHPExcel;
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
use Dompdf\Dompdf;

class ProjectsController extends AppController
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

    var $helpers = array('Time','Html','Form','ExPaginator');
	public $arrDefaultAdminUserRights = array(); 
	public $PAGE_NAME 					= '';
	public $paginate = [ 'limit' => PAGE_RECORD_LIMIT];
	
    private function SetVariables($post_variables) {
		if(isset($post_variables['proj_id']))
			$this->request->data['Projects']['id']					= $post_variables['proj_id'];
		if(isset($post_variables['proj_name']))
			$this->request->data['Projects']['name'] 				= $post_variables['proj_name'];
		if(isset($post_variables['verification_code']))
			$this->request->data['Projects']['verification_code']	= $post_variables['verification_code'];
		if(isset($post_variables['address']))
			$this->request->data['Projects']['address']				= $post_variables['address'];
		if(isset($post_variables['city']))
			$this->request->data['Projects']['city']				= $post_variables['city'];
		if(isset($post_variables['state']))
			$this->request->data['Projects']['state']				= $post_variables['state'];
		if(isset($post_variables['country']))
			$this->request->data['Projects']['country']				= $post_variables['country'];
		if(isset($post_variables['pincode']))
			$this->request->data['Projects']['pincode']				= $post_variables['pincode'];
		if(isset($post_variables['lat']))
			$this->request->data['Projects']['latitude']			= $post_variables['lat'];
		if(isset($post_variables['lon']))
			$this->request->data['Projects']['longitude']			= $post_variables['lon'];
		if(isset($post_variables['solar_radiation']))
			$this->request->data['Projects']['solar_radiation']		= $post_variables['solar_radiation'];
		if(isset($post_variables['roof_area']))
			$this->request->data['Projects']['area']				= $post_variables['roof_area'];
		if(isset($post_variables['area_type']))
			$this->request->data['Projects']['area_type']			= $post_variables['area_type'];
		if(isset($post_variables['c_type']))
			$this->request->data['Projects']['customer_type']		= $post_variables['c_type'];
		if(isset($post_variables['capacity_kw']))
			$this->request->data['Projects']['capacity_kw']			= $post_variables['capacity_kw'];
		if(isset($post_variables['esti_cost']))
			$this->request->data['Projects']['estimated_cost']		= $post_variables['esti_cost'];
		if(isset($post_variables['energy_con']))
			$this->request->data['Projects']['estimated_kwh_year']	= $post_variables['energy_con'];
		if(isset($post_variables['customized']))
			$this->request->data['Projects']['customized']			= $post_variables['customized'];
		if(isset($post_variables['discom_id']))
			$this->request->data['Projects']['discom_id']			= $post_variables['discom_id'];
		if(isset($post_variables['bill']))
			$this->request->data['Projects']['avg_monthly_bill']	= $post_variables['bill'];
		if(isset($post_variables['contract_load']))
			$this->request->data['Projects']['contract_load']		= $post_variables['contract_load'];
		if(isset($post_variables['backup_type']))
			$this->request->data['Projects']['backup_type']			= $post_variables['backup_type'];
		if(isset($post_variables['capacity']))
			$this->request->data['Projects']['diesel_genset_kva']	= $post_variables['capacity'];
		if(isset($post_variables['hours']))
			$this->request->data['Projects']['usage_hours']			= $post_variables['hours'];
		if(isset($post_variables['estimated_saving_year']))
			$this->request->data['Projects']['estimated_saving_year']	= $post_variables['estimated_saving_year'];
		if(isset($post_variables['op_maintence_cost_month']))
			$this->request->data['Projects']['op_maintence_cost_month']	= $post_variables['op_maintence_cost_month'];
		if(isset($post_variables['proj_id']))
			$this->request->data['Projects']['project_id']			= $post_variables['proj_id'];
		if(isset($post_variables['user_capacity']))
			$this->request->data['Projects']['user_capacity']		= $post_variables['user_capacity'];
		if(isset($post_variables['userlat']))
			$this->request->data['Projects']['userlat']		       = $post_variables['userlat'];
		if(isset($post_variables['userlong']))
			$this->request->data['Projects']['userlong']		   = $post_variables['userlong'];
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
		$this->loadModel('Projects');
		$this->loadModel('Customers');
		$this->loadModel('Department');
		$this->loadModel('UserDepartment');
		$this->loadModel('Admintrntype');
		$this->loadModel('Admintrnmodule');
		$this->loadModel('ApiToken');
		$this->loadModel('GhiData');
		$this->loadModel('CustomerProjects');
		$this->loadModel('FinancialIncentives');
		$this->loadModel('Installers');
		$this->loadModel('InstallerProjects');
		$this->loadModel('Parameters');
		$this->loadModel('Emaillog');
		$this->loadModel('StateSubsidy');
		$this->loadModel('SiteSurveys');
		$this->loadModel('SiteSurveysImages');
		$this->loadComponent('PhpExcel');
        $this->loadModel('Leads');
        $this->loadModel('BranchMasters');
        $this->loadModel('DiscomMaster');
        $this->loadModel('States');
        $this->loadModel('ProjectAssignBd');
		$this->set('Userright',$this->Userright);
    }
	/*
	 * Displays a index
	 *
	 * @param mixed What page to display
	 * @return void
	 */
	public function index($id = null) {
		
		$this->intCurAdminUserRight = $this->Userright->LIST_CUSTOMER;
		$this->setAdminArea();
		if(!empty($id))
			$id=intval(decode($id));
		if (!empty($this->Projects->validate)) {
			foreach ($this->Projects->validate as $field => $rules) {
				$this->Projects->validator()->remove($field); //Remove all validation in search page
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
		$this->SortBy		= "Projects.id";
		$this->Direction	= "ASC";
		$this->intLimit		= isset($this->request->data['length']) ? $this->request->data['length'] : PAGE_RECORD_LIMIT;
		$start_page 	= isset($this->request->data['start']) ? $this->request->data['start'] : 1;
		$this->CurrentPage  = $start_page;
		$option=array();	
		$option['colName']  = array('id','name','customername','customer_email','city','state','recommended_capacity','ins_count','status','created','action');
		$sortArr=array('customername'=>'c.name','customer_email'=>'c.email','ins_count'=>'count(ins.id)');
		$this->SetSortingVars('Projects',$option,$sortArr);
		$arrCondition		= $this->_generateProjectSearchCondition();
		$arr_date_search=array();
		if(array_key_exists('date_search', $arrCondition))
		{
			$arr_date_search=$arrCondition['date_search'];
			unset($arrCondition['date_search']);
		}
		if(!empty($id)){
			$arrCondition['i.customer_id'] = $id;
			$query_data= $this->Projects->find('all',array('fields'=>array('Projects.id','Projects.name','c.name','Projects.city','Projects.state','Projects.recommended_capacity','Projects.status','Projects.created'),'join'=>[
			                        'i' => [
			                            'table' => 'customer_projects',
			                            'type' => 'INNER',
			                            'conditions' => ['i.project_id = Projects.id']
			                        ],'c' => [
			                            'table' => 'customers',
			                            'type' => 'INNER',
			                            'conditions' => ['c.id = i.customer_id']
			                        ]],
									'conditions' => $arrCondition,
									'order'=>array($this->SortBy=>$this->Direction),
									'page'=> $this->CurrentPage,
									'limit' => $this->intLimit));
		} else {
			$query_data=$this->Projects->find('all',array('fields'=>array('Projects.id','Projects.name','c.name','customer_email'=>'c.email','Projects.city','Projects.state','Projects.recommended_capacity','ins_count' => 'count(ins.id)','Projects.status','Projects.created'),'join'=>[
			                        'i' => [
			                            'table' => 'customer_projects',
			                            'type' => 'INNER',
										'alias' => 'i',
			                            'conditions' => ['i.project_id = Projects.id']
			                        ],'c' => [
			                            'table' => 'customers',
			                            'type' => 'INNER',
										'alias' => 'c',
			                            'conditions' => ['c.id = i.customer_id']
			                        ]
									,'ins' => [
			                            'table' => 'installer_projects',
			                            'type' => 'INNER',
			                            'conditions' => ['i.project_id = ins.project_id']
			                        ]],
									'conditions' => $arrCondition,
									'order'=>array($this->SortBy=>$this->Direction),
									'group'=>'ins.project_id',
									'page'=> $this->CurrentPage,
									'limit' => $this->intLimit));
			if(!empty($arr_date_search))
			{
				$fields_date  = $arr_date_search['between'][0];
				$StartTime  = $arr_date_search['between'][1];
       			$EndTime    = $arr_date_search['between'][2];
                $query_data->where([function ($exp, $q) use ($StartTime, $EndTime, $fields_date) {
				return $exp->between($fields_date, $StartTime, $EndTime);
           		 }]);
			}
			
		}
		$start_page=isset($this->request->data['start']) ? $this->request->data['start'] : 1;				
		$this->paginate['limit'] = $this->intLimit;
		$this->paginate['page'] = ($start_page/$this->paginate['limit'])+1;
		$arrAdminuserList	= $this->paginate($query_data);
		$arrStatus =array(''=>'Select','pending'=>'Pending', 'inprocess'=>'In Process', 'completed'=>'Completed','deleted'=>'Deleted');
		$usertypes = array();
		$option['order_by'] = "'order': [[ 8, 'desc' ]]";

		$option['dt_selector']	='table-example';
		$option['formId']		='formmain';
		if(!empty($id)){
			$option['url']			= WEB_ADMIN_PREFIX.'projects/index/'.encode($id);
		}else{
			$option['url']			= WEB_ADMIN_PREFIX.'projects/';
		}
		$JqdTablescr = $this->JqdTable->create($option);
		
		$option1=array();
		$option1['colName']  = array('id','building_name','contact_name','designation','address1','address2','address3','mobile','surveyer_name', 'action');
		$option1['dt_selector']	='table-example-survey';
		$option1['formId']		='formmain_surveys';
		$option1['url']			= WEB_ADMIN_PREFIX.'projects/get_surveys';
		$JqdTablescr_survey = $this->JqdTable->create($option1);
		$this->set('arrAdminuserList',$arrAdminuserList->toArray());
		$this->set('JqdTablescr',$JqdTablescr);
		$this->set('JqdTablescr_survey',$JqdTablescr_survey);
		$this->set('arrStatus',$arrStatus);
		$this->set('period',$this->period);
		$this->set('limit',$this->intLimit);
		$this->set("CurrentPage",$this->CurrentPage);
		$this->set("SortBy",$this->SortBy);
		$this->set("Direction",$this->Direction);
		$this->set("page_count",(isset($this->request->params['paging']['Projects']['pageCount'])?$this->request->params['paging']['Projects']['pageCount']:0));
		$out=array();	
		foreach($arrAdminuserList->toArray() as $key=>$val) {
			$temparr=array();
			foreach($option['colName'] as $key) {

				if($key=='created') {
					$temparr[$key]= date('Y-m-d H:i:s',strtotime($val[$key]));
				}elseif($key=='customername') {
					$temparr[$key]= ucwords($val['c']['name']);
				}elseif($key=='status') {
					$temparr[$key]= ucwords($val[$key]);
				}else if(isset($val[$key])){
					$temparr[$key]=$val[$key];
				}
				if($key=='action') {
					//javascript:show_modal(\'
					$temparr['action']='';
					$total_survey=$this->SiteSurveys->find('all',array('conditions'=>array('project_id' => $val['id'])))->toArray();
					$temparr['action'].= $this->Userright->linkViewProjects(constant('WEB_URL').constant('ADMIN_PATH').'projects/view/'.encode($val['id']),'<i class="fa fa-eye"> </i>','','rel="viewRecord" target="_blank" ','title="View Project"')."&nbsp;";
					$temparr['action'].= $this->Userright->linkViewProjects('javascript:;','<i class="surveys fa fa-list-alt" id=""> </i>','','onclick="javascript:show_modal(\''.encode($val['id']).'\',\''.count($total_survey).'\');" target=""','title="View Serveys"')."&nbsp;";
						
				}		
			}
			$out[]=$temparr;
		}	
		if ($this->request->is('ajax')){
			header('Content-type: application/json');
			echo json_encode(array('condi'=>$arrCondition,"draw" => intval($this->request->data['draw']),
			"recordsTotal"    => intval( $this->request->params['paging']['Projects']['count']),
			"recordsFiltered" => intval( $this->request->params['paging']['Projects']['count']),
			"data"            => $out));
			die;
		}
	}

    private function _generateAdminuserroleSearchCondition($id = null)
    {
        $arrCondition = array();
        $blnSinCompany = true;
        if (!empty($id)) $this->request->data['Userrole']['id'] = $id;
        if (isset($this->request->data) && count($this->request->data) > 0) {
            if (isset($this->request->data['Userrole']['id']) && trim($this->request->data['Userrole']['id']) != '') {
                $strID = trim($this->request->data['Userrole']['id'], ',');
                $arrCondition['Userrole.id'] = array_unique(explode(',', $strID));
            }

            if (isset($this->request->data['Userrole']['rolename']) && $this->request->data['Userrole']['rolename'] != '') {
                $arrCondition['Userrole.rolename LIKE'] = '%' . $this->request->data['Userrole']['rolename'] . '%';
            }

            if (isset($this->request->data['Userrole']['rights']) && $this->request->data['Userrole']['rights'] != '') {
                //$arrCondition['Userrole.rights LIKE'] = '%'.$this->request->data['Userrole']['email'].'%';
            }

            if (isset($this->request->data['Userrole']['search_date']) && $this->request->data['Userrole']['search_date'] != '') {
                /*$arrDate = array('Userrole.created', 'Userrole.modified');
                if (in_array($this->request->data['Userrole']['search_date'], $arrDate)) {
                */  if ($this->request->data['Userrole']['search_period'] == 1 || $this->request->data['Userrole']['search_period'] == 2) {
                    $arrSearchPara = $this->Userrole->setSearchDateParameter($this->request->data['Userrole']['search_period'], 'Userrole');
                    //$this->request->data['Userrole'] = Set::merge($this->request->data['Userrole'], $arrSearchPara);
                    $this->request->data['Userrole'] = array_merge($this->request->data['Userrole'],$arrSearchPara['Userrole']);

                    $this->dateDisabled = true;
                }

                $arrperiodcondi = $this->Userrole->findConditionByPeriod($this->request->data['Userrole']['search_date'],
                    $this->request->data['Userrole']['search_period'],
                    $this->request->data['Userrole']['DateFrom'],
                    $this->request->data['Userrole']['DateTo'],
                    $this->Session->read('Userrole.timezone'));
                $arrCondition = array_merge($arrCondition, $arrperiodcondi);
                //}
            }
        }
        return $arrCondition;
    }
	/**
	 *
	 * _generateProjectSearchCondition
	 *
	 * Behaviour : Private
	 *
	 * @param : $id  : Id is use to identify for which project condition to be generated if its not null 
	 *
	 * @defination : Method is use to generate search condition using which projct data can be listed
	 *
	 */
	private function _generateProjectSearchCondition($id=null)
	{
		$arrCondition	= array();
		$blnSinCompany	= true;
		if(!empty($id)) $this->request->data['Projects']['id'] = $id;
		//if(count($this->request->data)==0) $this->request->data['Projects']['status'] = $this->Projects->STATUS_ACTIVE;
		if(isset($this->request->data) && count($this->request->data)>0)
        {
            if(isset($this->request->data['Projects']['id']) && trim($this->request->data['Projects']['id'])!='') {
                $strID = trim($this->request->data['Projects']['id'],',');
                $arrCondition['Projects.id'] = $this->request->data['Projects']['id'];
            }
			
			if(isset($this->request->data['Projects']['name']) && $this->request->data['Projects']['name']!='') {
                $arrCondition['Projects.name LIKE'] = '%'.$this->request->data['Projects']['name'].'%';
            }
			if(isset($this->request->data['Projects']['city']) && $this->request->data['Projects']['city']!='') {
                $arrCondition['Projects.city LIKE'] = '%'.$this->request->data['Projects']['city'].'%';
            }
			if(isset($this->request->data['Projects']['email']) && $this->request->data['Projects']['email']!='') {
                $arrCondition['c.email LIKE'] = '%'.$this->request->data['Projects']['email'].'%';
            }
			if(isset($this->request->data['Projects']['state']) && $this->request->data['Projects']['state']!='') {
                $arrCondition['Projects.state LIKE'] = '%'.$this->request->data['Projects']['state'].'%';
            }
			if(isset($this->request->data['Projects']['status']) && $this->request->data['Projects']['status']!=''){
                $arrCondition['Projects.status'] = $this->request->data['Projects']['status'];
            }
			if(isset($this->request->data['Projects']['search_date']) && $this->request->data['Projects']['search_date']!='') {
            	$arrDate = array('Projects.created');
				if(in_array($this->request->data['Projects']['search_date'], $arrDate)) {
					if($this->request->data['Projects']['search_period'] == 1 || $this->request->data['Projects']['search_period'] == 2) {
						$arrSearchPara	= $this->Projects->setSearchDateParameter($this->request->data['Projects']['search_period'],'Projects');
						$this->request->data['Projects'] = array_merge($this->request->data['Projects'],$arrSearchPara['Projects']);
						$this->dateDisabled	= true;
					}
					$arrperiodcondi = $this->Projects->findConditionByPeriod($this->request->data['Projects']['search_date'],
																				$this->request->data['Projects']['search_period'],
																				$this->request->data['Projects']['DateFrom'],
																				$this->request->data['Projects']['DateTo'],
																				$this->Session->read('Projects.timezone'));

				if(!empty($arrperiodcondi)){
						$arrCondition['date_search']=$arrperiodcondi;
                }
				}
			}
		}
		//print_r($arrCondition);
		//die;
		return $arrCondition;
	}
	/**
	 * manageindustrie
	 * Behaviour : Public
	 * @defination : Method is use to add new industrie type
	 */
	public function manage($id = null)
	{
		//$this->layout = "popup";

		if(empty($id)) {
			$this->intCurAdminUserRight = $this->Userright->ADD_PROJECT;
			$customersEntity 			= $this->Projects->newEntity($this->request->data(),['validate'=>'add']);
		} else {
			$id=intval(decode($id));
			$this->intCurAdminUserRight = $this->Userright->EDIT_PROJECT;
			$customerData 				= $this->Projects->get($id);
			$customersEntity 			= $this->Projects->patchEntity($customerData,$this->request->data(),['validate'=>'edit']);
				
			//$industrieEntity 			= $this->Parameter->patchEntity($this->request->data(),['validate'=>'editmanageindustrie']);
		}

		$this->setAdminArea();
		$arrAdminDefaultRights	= array();
		$arrAdminTransaction	= array();
		$arrError 				= array();
		//pr($this->request->data);
		if (!empty($this->request->data))
		{
			if (empty($id)) {
				$customersEntity->created 		= $this->NOW();
				$customersEntity->created_by 	= $this->Session->read('User.id');
			} else {
				$customersEntity->updated 		= $this->NOW();
				$customersEntity->updated_by 	= $this->Session->read('User.id');
			}
				if ($this->Projects->save($customersEntity))
				{
					if(empty($id)){
						$this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->ADD_PARAMETER_TYPE,$customersEntity->para_id,'Added Parameter id::'.$customersEntity->para_id);
						$this->Flash->set('Customer added successfully.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
                       	return $this->redirect('admin/projects');
					} else {
						$this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->EDIT_PARAMETER,$customersEntity->para_id,'Updated Parameter id::'.$customersEntity->para_id);
                        $this->Flash->set('Customer updated successfully.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
                       	return $this->redirect('admin/projects');
					}
				}
			//}
		} else if(!empty($id)){
			
			$customersEntity 		= $this->Projects->get($id);
			$customersEntity 		= $this->Projects->patchEntity($customersEntity,$this->request->data());

			if(!isset($customersEntity->id) || empty($customersEntity->id)) {
                $this->Flash->set('Invalid Customer Id.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'error']]);
			}
		}		
		$this->set('Projects',$customersEntity);
		$this->set('para_status',$this->industrie_status);
		$this->set('data',	$this->request->data);
		$this->set('arrError', $arrError);
		$this->set('para_id', $id);
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

		if($this->Projects->updateAll(['status' => 0], ['id' => $id]))
		{
			$this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->ANALYSTS_INACTIVATED,$id,'Inactivated Admin user id :: '.$id);
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
			$this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->ANALYSTS_ACTIVATED,$user_arr->id,'Activated Admin user id :: '.$user_arr->id);
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
		/*$this->User->bindModel(
			array('belongsTo' => array(
					'TimeZone' => array(
						'className' => 'TimeZone',
						'foreignKey' => 'timezone',
					)
				)
			)
		);*/
		$arrAdminDefaultRights = array();
		$timezone = '';
		$arrError = array();

		if(!$userEntity->errors() && !empty($this->request->data)) {
			$this->request->data['Users']['userrights'] = ltrim($this->arrDefaultAdminUserRights);
            $this->request->data['Users']['apikey'] = sha1(time());
			$newUsers =  $this->Users->newEntity($this->request->data['Users']);
			if($this->Users->save($newUsers)) {
				$this->UserDepartment->AddUserDepartment($newUsers->id,$this->request->data['Users']);
				$this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->ANALYSTS_ADD,$newUsers->id,'Added Admin user id::'.$newUsers->id);
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
	private function SendActivationCodeToCustomer($customer_id,$activation_code, $email, $mobile, $blnEmail=true)
	{
		if (!empty($mobile) && SEND_SMS) {
			//Send sms to customer
			$this->Customers->SendSMSActivationCode($customer_id,$mobile,$activation_code);
		}
		if(!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) && $blnEmail && SEND_EMAIL) {	
		//Send email to customer.

			App::uses('CakeEmail', 'Network/Email');
			$Email = new CakeEmail();
			$Email->viewVars(array('activation_code' => $activation_code));
			$Email->template('send_activation_code', 'empty')
				->emailFormat('text')
				->subject(Configure::read('EMAIL_ENV').'Aha Solar Activation Code')
				->to($email)
				->from(array('do-not-reply@recyclerewards.in' => 'Aha Solar'))
				->send();
		}
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
	public function getprojectestimation()
	{
		$bill 			= $this->request->data['bill'];
		$energy_con 		= $this->request->data['energy_con'];
		$area_type 		= $this->request->data['area_type'];
		$solarPenalArea 	= 0;
		$montly_pv_generation 	= 0;
		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);

		if(isset($this->request->data) && !empty($this->request->data)) {
			/* Create or update project data here. */
			if(!empty($this->request->data['Projects']['id'])) {
				$projectsData 									= $this->Projects->get($this->request->data['Projects']['id']);
				$requestData['Projects']['address']				= (!empty($this->request->data['address'])?$this->request->data['address']:$projectsData['address']);
				$requestData['Projects']['city']				= (!empty($this->request->data['city'])?$this->request->data['city']:$projectsData['city']);
				$requestData['Projects']['state']				= (!empty($this->request->data['state'])?$this->request->data['state']:$projectsData['state']);
				$requestData['Projects']['state_short_name']	= (!empty($this->request->data['state_short_name'])?$this->request->data['state_short_name']:$projectsData['state_short_name']);
				$requestData['Projects']['country']				= (!empty($this->request->data['country'])?$this->request->data['country']:$projectsData['country']);
				$requestData['Projects']['pincode']				= (!empty($this->request->data['pincode'])?$this->request->data['pincode']:$projectsData['pincode']);
				$requestData['Projects']['landmark']			= (!empty($this->request->data['landmark'])?$this->request->data['landmark']:$projectsData['landmark']);
				$requestData['Projects']['userlat']	    	    = (!empty($this->request->data['userlat'])?$this->request->data['userlat']:$projectsData['userlat']);
				$requestData['Projects']['userlong']	    	= (!empty($this->request->data['userlong'])?$this->request->data['userlong']:$projectsData['userlong']);
			} else {
				$requestData['Projects']['address']				= (isset($this->request->data['address'])?$this->request->data['address']:'');
		                $requestData['Projects']['city']				= (isset($this->request->data['city'])?$this->request->data['city']:'');
		                $requestData['Projects']['state']				= (isset($this->request->data['state'])?$this->request->data['state']:'');
		                $requestData['Projects']['state_short_name']	= (isset($this->request->data['state_short_name'])?$this->request->data['state_short_name']:'');
		                $requestData['Projects']['country']				= (isset($this->request->data['country'])?$this->request->data['country']:'');
		                $requestData['Projects']['pincode']				= (isset($this->request->data['pincode'])?$this->request->data['pincode']:'');
		                $requestData['Projects']['landmark']			= (isset($this->request->data['landmark'])?$this->request->data['landmark']:'');
		                $requestData['Projects']['userlat']	    	    = (!empty($this->request->data['userlat'])?$this->request->data['userlat']:$projectsData['userlat']);
				        $requestData['Projects']['userlong']	    	= (!empty($this->request->data['userlong'])?$this->request->data['userlong']:$projectsData['userlong']);
			}
			$requestData['proj_name']		= isset($this->request->data['proj_name']) ? $this->request->data['proj_name'] : '';
			$requestData['Projects']['id'] 	= isset($this->request->data['Projects']['id'])?$this->request->data['Projects']['id']:'';
			$requestData['energy_con'] 		= isset($this->request->data['energy_con']) ? $this->request->data['energy_con'] : '';
			$requestData['area_type']	 	= isset($this->request->data['area_type']) ? $this->request->data['area_type'] : '';
			$requestData['area'] 			= isset($this->request->data['roof_area']) ? $this->request->data['roof_area'] : '';
			$requestData['latitude'] 		= isset($this->request->data['lat']) ? $this->request->data['lat'] : ''; 
			$requestData['longitude'] 		= isset($this->request->data['lon']) ? $this->request->data['lon'] : ''; 
			$requestData['avg_monthly_bill']= isset($this->request->data['bill']) ? $this->request->data['bill'] : ''; 
			$requestData['backup_type'] 	= isset($this->request->data['backup_type']) ? $this->request->data['backup_type'] : '';
			$requestData['usage_hours'] 	= isset($this->request->data['hours']) ? $this->request->data['hours'] : ''; 
			$requestData['project_type'] 	= isset($this->request->data['c_type']) ? $this->request->data['c_type'] : '';
			$requestData['Projects']['project_source'] 	= $this->Parameters->sourceSolarCalculator;
			$result 						= $this->Projects->getprojectestimation($requestData,$this->ApiToken->customer_id);
			$this->ApiToken->SetAPIResponse('result', $result);	
			$status = 'ok';
			$this->ApiToken->SetAPIResponse('type', $status);
		} else {
			$status = "error";
			$this->ApiToken->SetAPIResponse('type', $status);
			$this->ApiToken->SetAPIResponse('msg', 'Invalid request!');
		}
		echo stripslashes($this->ApiToken->GenerateAPIResponse());
	}
	/**
	 * Not used any where this function (will used in next version)
	 *
	 * getSavingChartData
	 *
	 * Behaviour : private
	 *
	 * @defination : Method is use to calculate month saving chart data.
	 */
	private function getSavingChartData($bill,$energy_con,$montly_pv_generation)
	{
		$year 			= date('Y');
		$yearChart		= array();
		$savingDataArr	= array();
		$result 		= array();
		for($i=$year;$i<=($year+25);$i++) {
			$yearly_saving 	= '';
			if($year==date('Y')) {
				$result[$i]['bill'] 				= $bill;
				$result[$i]['energy_con'] 			= $energy_con;
				$result[$i]['montly_pv_generation'] = $montly_pv_generation;

			} elseif($year > date('Y')) {
				$result[$i]['bill'] 				= ($result[$i-1]['bill'] * 12 * (1+(BILL_INCREASE/100)));
				$result[$i]['energy_con'] 			= ($result[$i-1]['energy_con'] * 12 * (1+(ENERGY_CON_INCREASE/100))); 
				$result[$i]['montly_pv_generation'] = ($result[$i-1]['montly_pv_generation'] * 12 * (1-(PV_GENERATION_DECREASE/100)));
			}
			$yearly_saving 			= ($result[$i]['bill'] - ($result[$i]['energy_con'] - $result[$i]['montly_pv_generation']) * (($result[$i]['bill']/$result[$i]['energy_con'])-0.5)); 
			$savingDataArr[] 		= "{'x':".$year.",'y':".round($yearly_saving)."}";
			$year++;
		}
		return $savingDataArr;
	}
	/**
	 *
	 * genrateApiChartData
	 *
	 * Behaviour : private
	 *
	 * @defination : Method is use to calculate PV Area in Foot
	 *
	 */
	private function genrateApiChartData($fromPvSystem=array(), $monthChartDataArr)
	{
		$year			= date('Y');
		$yearChart		= array();	
		$monthChart		= array();
		$yearArr		= array();
		$monthArr		= array();
		foreach($fromPvSystem as $key=>$arrChartType)
		{
			if($key <= 12) {
				$monthDataVal 	= (isset($monthChartDataArr[$key])?round($monthChartDataArr[$key]):0);
				$monthArr[]		= "{'x':".$key.",'y':".$monthDataVal."}";
			}	
			$yearArr[]=	"{'x':".$year.",'y':".$arrChartType['yearlyEnergyGenerated']."}";
			$year++;
		}
		return array('yearChart'=>'['.implode(',',$yearArr).']','monthChart'=>'['.implode(',',$monthArr).']');
	}
	/**
	 *
	 * calculatePvInFoot
	 *
	 * Behaviour : private
	 *
	 * @defination : Method is use to calculate PV Area in Foot
	 *
	 */
	private function calculatePvInFoot($area=null)
	{
		if(empty($area))
			return false;
		$pvArea	= 0;
		if($area <= RUF_RESIDENT_FOOT_LIMIT) {
			$pvArea	= ($area*RUF_RESIDENT/100);
		} else if($area == RUF_COMMERCE_FOOT_LIMIT) { echo "xc";
			$pvArea	= ($area*RUF_COMMERCE/100);
		} else { 
			$pvArea	= ($area*RUF_INDUSTRIAL/100);
		}
		return $pvArea;
	}
	/**
	 *
	 * calculatePvInMeter
	 *
	 * Behaviour : private
	 *
	 * @defination : Method is use to calculate PV Area in Meter
	 *
	 */
	private function calculatePvInMeter($area=null)
	{
		if(empty($area))
			return false;
		$pvArea		=	0;
		if($area <= RUF_RESIDENT_METER_LIMIT) {
			$pvArea	=	($area*RUF_RESIDENT/100);
		} else if($area <= RUF_COMMERCE_METER_LIMIT) {
			$pvArea	=	($area*RUF_COMMERCE/100);
		} else {
			$pvArea	=	($area*RUF_INDUSTRIAL/100);
		}
		return $pvArea;
	}
	/**
	 *
	 * calculatecapitalcost
	 *
	 * Behaviour : private
	 *
	 * @defination : Method is use to calculate Capital cost
	 *
	 */
	private function calculatecapitalcost($spvi=null,$state='',$customer_type='')
	{
		if(empty($spvi))
			return false;
		$cost	=	0;
		if(strtolower($state)=='gujarat' && $customer_type=='3001')
		{
			$cost	=	($spvi*COST_FOR_GUJARAT);
		}else
		{
			if($spvi <= 10) {
				$cost	=	($spvi*COST_UPTO_10_KW);
			} else if($spvi > 10 && $spvi <= 100) {
				$cost	=	($spvi*COST_FOR_10_TO_100_KW);
			} else if($spvi > 100 && $spvi <= 500) {
				$cost	=	($spvi*COST_FOR_100_TO_500_KW);
			} else if($spvi > 500 && $spvi <= 1000) {
				$cost	=	($spvi*COST_FOR_500_TO_1000_KW);
			} else if($spvi > 1000 && $spvi <= 10000) {
				$cost	=	($spvi*COST_FOR_1000_TO_10000_KW);
			} else {
				$cost	=	($spvi*COST_ABOVE_10000_KW);
			}
		}	
		return $cost;
	}
	/**
	 * @name calculatecapitalcostwithsubsidy
	 * @uses getting Solar Tarif Data(Cost Solar with subsidy)
	 * @param $recommended_capacity, $cost
	 * @return $costwithsubsidy
	 * @author Khushal Bhalsod
	 */
	private function calculatecapitalcostwithsubsidy($recommendedSolarPvInstall='',$capitalCost='',$state="",$customertype="")
	{	
		$subsidyData = array();
		$subsidyData = $this->StateSubsidy->getSubcidyDataByState(strtolower($state));
		$capitalCost = ($capitalCost * 100000);
		$subsidy = 0;
		$sutatesubsidy 		= 0;
		$centralsubsidy 	= 0;
		$othersubsidy 		= 0;
		if(empty($recommendedSolarPvInstall) || empty($capitalCost))
			return false;
		$costwithsubsidy = 0;
		
		if($recommendedSolarPvInstall < 500 && !empty($subsidyData)) { 
				
			if($customertype == '3001') { 
				
			if($subsidyData['state_subcidy_type']=='0') {
				$subsidy 			= ($capitalCost * (($subsidyData['state_subsidy']/100)));
				if(!empty($subsidyData['state_capacity']) && $recommendedSolarPvInstall <= $subsidyData['state_capacity'] ) {
					$sutatesubsidy 	= $subsidy + ($subsidyData['state_subsidy'] * ($recommendedSolarPvInstall/100));	
				}
				else
				{
						$sutatesubsidy 			= ($capitalCost * (($subsidyData['state_subsidy']/100)));
				}
			} else {
					 $sutatesubsidy 	=   $subsidyData['state_subsidy'] * $subsidyData['state_capacity'];
				
			}
			if($subsidyData['central_subcidy_type']=='0') {
				if(!empty($subsidyData['central_capacity']) && $recommendedSolarPvInstall <= $subsidyData['central_capacity'] ) {
					$centralsubsidy  = ($capitalCost * ($subsidyData['central_subsidy']/100));
				}
			} else {
				$centralsubsidy 	=  $subsidy + $subsidyData['central_subsidy'];
			}
			if($subsidyData['other_subcidy_type']=='0'){
				$subsidy 			= ($capitalCost * (($subsidyData['other_subsidy']/100)));
				if(!empty($subsidyData['other_capacity']) && $recommendedSolarPvInstall <= $subsidyData['other_capacity'] ) {
						$othersubsidy	= $subsidy + ($subsidyData['other_subsidy'] * ($recommendedSolarPvInstall/100));
					}
				else
				{
					$othersubsidy 	= $subsidy + ($subsidyData['other_subsidy'] * (2/100));
				}	
			} else {
				$othersubsidy 	= $subsidy + $subsidyData['other_subsidy'];
			}
			
			$costwithsubsidy = $capitalCost - ($sutatesubsidy + $centralsubsidy + $othersubsidy);

		} else {
			$costwithsubsidy = $capitalCost;
		}
		} else {
			$costwithsubsidy = $capitalCost;
		}
		return $costwithsubsidy;
	}
	/**
	 * @name getTarifCalculation
	 * @uses getting Solar Tarif Data(Cost Solar)
	 * @param $year, $curYearEnergyGenerated, $avg_monthly_bill,$capitalCost 
	 * @return $result
	 * @author Khushal Bhalsod
	 */
	private function getTarifCalculation($year,$curYearEnergyGenerated,$avg_monthly_bill,$capitalCost)
	{
		$years						= $year;
		$curYearEnergyGenerated 	= $curYearEnergyGenerated;
		$capitalCost 				= $capitalCost;
		$tariff_value 				= array();
		$cog_value 					= array();
		$levelized_cog_value		= array();
		$result 					= array(); 

		$suminsuranceval			= 0;
		$cog_o_and_m_total			= 0;
		$discount_factor_total		= 0;
		$cog_depreciation_total 	= 0;
		$cog_insurance_cost_total	= 0;
		$cog_return_on_equity_total	= 0;
		$cog_tax_total				= 0;
		$cog_gross_cost_total		= 0;
		$cog_ad_tax_benifit_total	= 0;
		$cog_net_cost_total			= 0;

		########### Get interest loan ###############	
		$loanDataRes = $this->getInterestOnLoan($years,$capitalCost);
		
		for($i=1;$i<=$years;$i++) {
				######################################### TARIFF CALCULATION ######################################		
			if($i == 1) {
				$tariff_value[$i]['net_generation'] 					= $curYearEnergyGenerated;
				$tariff_value[$i]['o_and_m_expense'] 					= $capitalCost * (O_AND_M_COST/100);
				$tariff_value[$i]['insurance_cost']						= $capitalCost * (O_AND_M_COST/100);
				/* used this for calculate advanced tax benefit */
				$tariff_value[$i]['accelerated_opening_balance']   		= $capitalCost * 100000;
				$tariff_value[$i]['accelerated_depreciation_rate']  	= (RATE_OF_ACCELERATED_DEPRE/100); 
				$tariff_value[$i]['accelerated_depreciation_amount']	= $tariff_value[$i]['accelerated_opening_balance'] * $tariff_value[$i]['accelerated_depreciation_rate'];
				$tariff_value[$i]['accelerated_closing_balance'] 		= $tariff_value[$i]['accelerated_opening_balance'] - $tariff_value[$i]['accelerated_depreciation_amount'];
				$tariff_value[$i]['AD Benefit'] 						= $tariff_value[$i]['accelerated_depreciation_amount'] * (CORPORATE_TAX_RATE/100);
				/* used this for calculate advanced tax benefit */
			} else {
				$tariff_value[$i]['net_generation'] 					= $tariff_value[$i-1]['net_generation'] * (1-(ANNUAL_DEGREDATION/100));
				$tariff_value[$i]['o_and_m_expense'] 					= $tariff_value[$i-1]['o_and_m_expense'] * (1+(O_AND_M_ESCLATION/100));
				$suminsuranceval 										+= $tariff_value[$i-1]['insurance_cost']; 
				$tariff_value[$i]['insurance_cost']						= ($capitalCost-($suminsuranceval)) * (O_AND_M_COST/100);
				/* used this for calculate advanced tax benefit */
				$tariff_value[$i]['accelerated_opening_balance']   		= $tariff_value[$i-1]['accelerated_closing_balance'];
				$tariff_value[$i]['accelerated_depreciation_rate']  	= (RATE_OF_ACCELERATED_DEPRE/100); 
				$tariff_value[$i]['accelerated_depreciation_amount']	= $tariff_value[$i]['accelerated_opening_balance'] * $tariff_value[$i]['accelerated_depreciation_rate'];
				$tariff_value[$i]['accelerated_closing_balance'] 		= $tariff_value[$i]['accelerated_opening_balance'] - $tariff_value[$i]['accelerated_depreciation_amount'];
				$tariff_value[$i]['AD Benefit'] 						= $tariff_value[$i]['accelerated_depreciation_amount'] * (CORPORATE_TAX_RATE/100);
				/* used this for calculate advanced tax benefit */
			}
				$depreciationAmt 						= ((($i<=10)?RATE_DEPRECATION_FOR_10:RATE_DEPRECATION_NEXT_15)/100) * $capitalCost * 100000;
				$tariff_value[$i]['depreciation'] 		= $depreciationAmt/100000;
				$tariff_value[$i]['interest_on_loan']	= $loanDataRes[$i]['interest_on_loan'];
				$tariff_value[$i]['return_on_equity']	= $capitalCost * (1-(DEBT_FRATION/100))*(ROE/100);
				$tariff_value[$i]['tax']				= (($i<=ALTERNATE_TAX_RATE)?(($tariff_value[$i]['return_on_equity']*(MIN_ALTERNATE_TAX_RATE/100))/(1-(MIN_ALTERNATE_TAX_RATE/100))):(($tariff_value[$i]['return_on_equity']*(CORPORATE_TAX_RATE/100))/(1-(CORPORATE_TAX_RATE/100))));
				$tariff_value[$i]['gross_cost']			= $tariff_value[$i]['o_and_m_expense'] + $tariff_value[$i]['depreciation']+$tariff_value[$i]['interest_on_loan']+$tariff_value[$i]['insurance_cost']+$tariff_value[$i]['return_on_equity']+$tariff_value[$i]['tax'];
				$tariff_value[$i]['ad_tax_benifit']		= ($tariff_value[$i]['AD Benefit']/100000);
				$tariff_value[$i]['net_cost']			= $tariff_value[$i]['gross_cost']-$tariff_value[$i]['ad_tax_benifit'];
				
				######################################### Discount FACTOR #########################################
				$tariff_value[$i]['discount_factor']	= 1/(pow((1+(DISCOUNT_FACTOR/100)),($i-1)));

				######################################### Discounted COG ###########################################
				
				$cog_value[$i]['o_and_m_expense'] 		= (($curYearEnergyGenerated > 0)?($tariff_value[$i]['o_and_m_expense'] * $tariff_value[$i]['discount_factor'] * 100000)/($curYearEnergyGenerated):0);
				$cog_value[$i]['depreciation'] 			= (($curYearEnergyGenerated > 0)?($tariff_value[$i]['depreciation'] * $tariff_value[$i]['discount_factor'] * 100000)/($curYearEnergyGenerated):0);
				$cog_value[$i]['interest_on_loan'] 		= (($tariff_value[$i]['net_generation'] > 0)?($tariff_value[$i]['interest_on_loan'] * $tariff_value[$i]['discount_factor'] * 100000)/($tariff_value[$i]['net_generation']):0);
				$cog_value[$i]['insurance_cost'] 		= (($curYearEnergyGenerated > 0)?($tariff_value[$i]['insurance_cost'] * $tariff_value[$i]['discount_factor'] * 100000)/($curYearEnergyGenerated):0);
				$cog_value[$i]['return_on_equity'] 		= (($curYearEnergyGenerated > 0)?($tariff_value[$i]['return_on_equity'] * $tariff_value[$i]['discount_factor'] * 100000)/($curYearEnergyGenerated):0);
				$cog_value[$i]['tax'] 					= (($tariff_value[$i]['net_generation'] > 0)?($tariff_value[$i]['tax'] * $tariff_value[$i]['discount_factor'] * 100000)/($tariff_value[$i]['net_generation']):0);
				$cog_value[$i]['gross_cost'] 			= (($curYearEnergyGenerated > 0)?($tariff_value[$i]['gross_cost'] * $tariff_value[$i]['discount_factor'] * 100000)/($curYearEnergyGenerated):0);
				$cog_value[$i]['ad_tax_benifit'] 		= (($curYearEnergyGenerated > 0)?($tariff_value[$i]['ad_tax_benifit'] * $tariff_value[$i]['discount_factor'] * 100000)/($curYearEnergyGenerated):0);
				$cog_value[$i]['net_cost'] 				= (($curYearEnergyGenerated > 0)?($tariff_value[$i]['net_cost'] * $tariff_value[$i]['discount_factor'] * 100000)/($curYearEnergyGenerated):0);
		
				######################################### Levelized COG ###########################################
				$cog_o_and_m_total 						+= $cog_value[$i]['o_and_m_expense'];
				$cog_depreciation_total 				+= $cog_value[$i]['depreciation'];
				$cog_insurance_cost_total 				+= $cog_value[$i]['insurance_cost'];
				$cog_return_on_equity_total 			+= $cog_value[$i]['return_on_equity'];
				$cog_tax_total 							+= $cog_value[$i]['tax'];
				$cog_gross_cost_total 					+= $cog_value[$i]['gross_cost'];
				$cog_ad_tax_benifit_total 				+= $cog_value[$i]['ad_tax_benifit'];
				$cog_net_cost_total 					+= $cog_value[$i]['net_cost'];
				$discount_factor_total 					+= $tariff_value[$i]['discount_factor'];  
		}

		$levelized_cog_value['o_and_m_expense']			= ($cog_o_and_m_total)/($discount_factor_total);
		$levelized_cog_value['depreciation']			= ($cog_depreciation_total)/($discount_factor_total);
		$levelized_cog_value['interest_on_loan'] 		= '';
		$levelized_cog_value['insurance_cost'] 			= ($cog_insurance_cost_total)/($discount_factor_total);
		$levelized_cog_value['return_on_equity'] 		= ($cog_return_on_equity_total)/($discount_factor_total);
		$levelized_cog_value['tax'] 					= ($cog_tax_total)/($discount_factor_total);
		$levelized_cog_value['gross_cog'] 				= ($cog_gross_cost_total)/($discount_factor_total);
		$levelized_cog_value['tax_benefit']				= ($cog_ad_tax_benifit_total)/($discount_factor_total);
		$levelized_cog_value['net_cog'] 				= ($cog_net_cost_total)/($discount_factor_total);
		$result['gross_cog'] 	= $levelized_cog_value['gross_cog'];
		$result['net_cog'] 		= $levelized_cog_value['net_cog'];
		return $result; 
	}
	/**
	 * @name getInterestOnLoan
	 * @uses getting interest on loan data.
	 * @param int $year ,float $capitalCost
	 * @return $arrResult
	 * @author Khushal Bhalsod
	 */
	private function getInterestOnLoan($years, $capitalCost)
	{
		$arrResult 	= array();		
		for($i=1;$i<=$years;$i++) {

			if($i == 1) {
				$arrResult[$i]['openinig_amount'] 		= (($capitalCost * (1-(CAPITAL_SUBSIDY/100))) * (DEBT_FRATION/100) * 100000);
				$arrResult[$i]['annual_principal_paid'] = ($i>MORATORIUM_PERIOD?($i<=(LOAN_TENURE+MORATORIUM_PERIOD)?$arrResult[$i]['openinig_amount']/(LOAN_TENURE):0):0);
				$arrResult[$i]['closing_balance'] 		= $arrResult[$i]['openinig_amount'] - $arrResult[$i]['annual_principal_paid'];
				$arrResult[$i]['annual_interest']	 	= ((($arrResult[$i]['openinig_amount']+$arrResult[$i]['closing_balance'])/2) * (INTEREST_RATE_ON_LOAN/100)); 
				$arrResult[$i]['interest_on_loan'] 		= ($arrResult[$i]['annual_interest']/100000);
			} else {
				$arrResult[$i]['openinig_amount'] 		= $arrResult[$i-1]['closing_balance'];
				$arrResult[$i]['annual_principal_paid'] = (($i>MORATORIUM_PERIOD)?(($i<=(LOAN_TENURE+MORATORIUM_PERIOD))?($arrResult[$i]['openinig_amount']/(LOAN_TENURE)):0):0);
				$arrResult[$i]['closing_balance'] 		= $arrResult[$i]['openinig_amount'] - $arrResult[$i]['annual_principal_paid'];
				$arrResult[$i]['annual_interest']	 	= ((($arrResult[$i]['openinig_amount']+$arrResult[$i]['closing_balance'])/2) * (INTEREST_RATE_ON_LOAN/100)); 
				$arrResult[$i]['interest_on_loan'] 		= ($arrResult[$i]['annual_interest']/100000);
			}
		}
		return $arrResult;
	}
	/**
	 * @name getSolarRediation
	 * @uses getting Solar Rediation Data as per logic
	 * @param float $lat ,float $val
	 * @return $rediationArr
	 * @author Pravin Sanghani
	 * @since 2015-11-19
	 */
	private function getSolarRediation($lat=null,$long=null)
	{
		$ghidata=array();
		$ghidata=$this->GhiData->getGhiData($long,$lat);		
		if(!empty($ghidata))
			return $ghidata;
		else
			return $this->GhiData->getGhiData(72.5800,23.0300);
	}
	/**
	 * @name getBillingChart
	 * @uses getting Solar Rediation Data as per logic
	 * @param float $lat ,float $val
	 * @return $rediationArr
	 * @author Pravin Sanghani
	 * @since 2015-11-19
	 */
	private function getBillingChart($contractLoad,$unitRate,$averageEnrgyGenInYear,$capitalCost,$backUpType,$usage_hours)
	{

		$years 				= 25;
		$breakEvenPeriod 	= 0;
		
		$capitalCost 		= (0-($capitalCost*100000));
		$firstMonthEnrgyGen = $averageEnrgyGenInYear/12;
		$solarChart 		= array();
		$conventionalScenarioArr = array();
		$fromPvSystem 		= array();
		$netEnergy 			= array();
		$cashFlows 			= array();
		$breakEvenAnalysis 	= array();
		$actualMonthlyCredit 	= 0;
		$cost_electricty 	= 0;
		$contractLoad 		= round($contractLoad,2);
		
		if(isset($backUpType) && $backUpType == $this->Projects->BACKUP_TYPE_GENERATOR) {
			$cost_electricty = $this->calculateGeneratorUsage($contractLoad, $usage_hours);
		} elseif(isset($backUpType) && $backUpType == $this->Projects->BACKUP_TYPE_INVERTER) {
			$cost_electricty = $this->calculateInverterUsage($contractLoad, $usage_hours);
		}
		for($i=1;$i<=$years;$i++)	
		{
			if($i==1) {
				$conventionalScenarioArr[$i]['loadFector']=LOAD_FECTORE;
				$fromPvSystem[$i]['monthlyEnergyGenerated']=round($firstMonthEnrgyGen);
				$fromPvSystem[$i]['yearlyEnergyGenerated']=round($firstMonthEnrgyGen*12);
			} else {
				$conventionalScenarioArr[$i]['loadFector']=round(($conventionalScenarioArr[$i-1]['loadFector']*(1+LOAD_FECTORE_INCREASE/100)), 2);
				$fromPvSystem[$i]['monthlyEnergyGenerated']=round($fromPvSystem[$i-1]['monthlyEnergyGenerated']-($fromPvSystem[$i-1]['monthlyEnergyGenerated']/100));
				$fromPvSystem[$i]['yearlyEnergyGenerated']=round($fromPvSystem[$i]['monthlyEnergyGenerated']*12);
			}
			$conventionalScenarioArr[$i]['unitsConsumed']=round(((($contractLoad*24*365)*$conventionalScenarioArr[$i]['loadFector']/100)/12));	
			$netEnergy[$i]['netMonthlyConsumption']=round($conventionalScenarioArr[$i]['unitsConsumed']-$fromPvSystem[$i]['monthlyEnergyGenerated']);
			$netEnergy[$i]['totalMonthlyBill']=round($netEnergy[$i]['netMonthlyConsumption']*$unitRate);
			$conventionalScenarioArr[$i]['totalMonthlyBill']=round($conventionalScenarioArr[$i]['unitsConsumed']*$unitRate+$cost_electricty);	
			$cashFlows[$i]['netMonthlyCashFlow']=round($actualMonthlyCredit-$netEnergy[$i]['totalMonthlyBill']);
			$cashFlows[$i]['monthlyFinancialSaving']=round($conventionalScenarioArr[$i]['totalMonthlyBill']+$cashFlows[$i]['netMonthlyCashFlow']);
			$cashFlows[$i]['annualFinancialSaving']=round($cashFlows[$i]['monthlyFinancialSaving']*12);
			if($i==1) {
				$breakEvenAnalysis[$i]['cumulativeFinancialSaving']=round($capitalCost+$cashFlows[$i]['annualFinancialSaving']);
				$breakEvenAnalysis[$i]['addFraction']=(($capitalCost < 0 )?(($breakEvenAnalysis[$i]['cumulativeFinancialSaving'] > 0 )?($breakEvenAnalysis[$i]['cumulativeFinancialSaving']/($breakEvenAnalysis[$i]['cumulativeFinancialSaving']-$capitalCost)):0):0);
			} else {
				$breakEvenAnalysis[$i]['cumulativeFinancialSaving']=round($breakEvenAnalysis[$i-1]['cumulativeFinancialSaving']+$cashFlows[$i]['annualFinancialSaving']);
				$breakEvenAnalysis[$i]['addFraction']=(($breakEvenAnalysis[$i-1]['cumulativeFinancialSaving'] < 0 )?(($breakEvenAnalysis[$i]['cumulativeFinancialSaving'] > 0 )?($breakEvenAnalysis[$i]['cumulativeFinancialSaving']/($breakEvenAnalysis[$i]['cumulativeFinancialSaving']-$breakEvenAnalysis[$i-1]['cumulativeFinancialSaving'])):0):0);
			}
			$breakEvenAnalysis[$i]['addYear']	=	(($breakEvenAnalysis[$i]['cumulativeFinancialSaving'] < 0 )?1:0);
			$breakEvenPeriod+=$breakEvenAnalysis[$i]['addYear'];
			$breakEvenPeriod+=$breakEvenAnalysis[$i]['addFraction'];
		}
			if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] == "203.88.138.46") {
				//print_r($netEnergy);
				//echo $breakEvenPeriod;
			}
			$solarChart['conventionalScenarioArr']=$conventionalScenarioArr;
			$solarChart['fromPvSystem']=$fromPvSystem;
			$solarChart['netEnergy']=$netEnergy;
			$solarChart['cashFlows']=$cashFlows;
			$solarChart['breakEvenAnalysis']=$breakEvenAnalysis;
			$solarChart['breakEvenPeriod']=$breakEvenPeriod;
			return $solarChart;
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
	public function getprojectassumption()
	{
		$this->autoRender = false;		
		$this->SetVariables($this->request->data);
		
		$projectsEntity 		=	$this->Projects->get($this->request->data['Projects']['id']);
		$estimated_cost	=	$projectsEntity['estimated_cost'];
		$estimated_cost = ($estimated_cost*100000);
		
		$o_and_m_cost	= (($estimated_cost*O_AND_M_COST)/100); 
		$o_and_m_esclation	= (($estimated_cost*O_AND_M_ESCLATION)/100); 
		$debt	= (($estimated_cost*DEBT_FRATION)/100); 
		$interastOnLoan	= (($estimated_cost*INTEREST_RATE_ON_LOAN)/100); 
		$insuranceCost	= (($estimated_cost*INSURANCE_COST)/100); 
		$rateOfDepreFor10	= (($estimated_cost*RATE_DEPRECATION_FOR_10)/100); 
		$rateOfDepreNext15	= (($estimated_cost*RATE_DEPRECATION_NEXT_15)/100); 
		$rateOfAcceleratedDepre	= (($estimated_cost*RATE_OF_ACCELERATED_DEPRE)/100); 
		$this->request->data['Projects']['assu_om_cost'] 			= $o_and_m_cost;
		$this->request->data['Projects']['assu_escalation_om'] 		= $o_and_m_esclation;
		$this->request->data['Projects']['assu_debt'] 				= $debt;
		$this->request->data['Projects']['assu_interest_rate'] 		= $interastOnLoan;
		$this->request->data['Projects']['assu_insurance_cost'] 	= $insuranceCost;
		$this->request->data['Projects']['assu_rate_depre_for_10'] 	= (defined('RATE_DEPRECATION_FOR_10')?RATE_DEPRECATION_FOR_10:"0");
		$this->request->data['Projects']['assu_rate_depre_next_15'] = (defined('RATE_DEPRECATION_NEXT_15')?RATE_DEPRECATION_NEXT_15:"0");
		$this->request->data['Projects']['assu_accelerated_depre'] 	= $rateOfAcceleratedDepre;
		$customersEntity 			= $this->Projects->patchEntity($projectsEntity,$this->request->data(),['validate'=>'edit']);
		if ($this->Projects->save($projectsEntity)) {
			$status				= 'ok';
			$result['om_cost']			=	_FormatGroupNumber($o_and_m_cost);
			$result['escalation_om']	=	(defined('O_AND_M_ESCLATION')?O_AND_M_ESCLATION:"0");
			$result['debt']				=	_FormatGroupNumber($debt);
			$result['interest_rate']	=	(defined('ASSUMPTION_INTEREST')?ASSUMPTION_INTEREST:"0");
			$result['insurance_cost']	=	_FormatGroupNumber($insuranceCost);
			$result['rate_of_desp_10']	=	(defined('RATE_DEPRECATION_FOR_10')?RATE_DEPRECATION_FOR_10:"0");
			$result['rate_of_desp_15']	=	(defined('RATE_DEPRECATION_NEXT_15')?RATE_DEPRECATION_NEXT_15:"0");
			$result['rate_of_acc_desp']	=	(defined('RATE_OF_ACCELERATED_DEPRE')?RATE_OF_ACCELERATED_DEPRE:"0");
			$this->ApiToken->SetAPIResponse('result',$result);
		} else {
			$status				= 'error';
			$error				= 'Please try after some time';
			$this->ApiToken->SetAPIResponse('msg', $error);
		} 
		$this->ApiToken->SetAPIResponse('type', $status);
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	
	/**
	*
	* getprojectlist
	*
	* Behaviour : public
	*
	* @defination : Method is used to get project list.
	*
	*/
	public function getprojectlist() 
	{
		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		$projectData 		= array();
		$customer_id 		= $this->ApiToken->customer_id;
		$this->intLimit		= isset($this->request->data['length']) ? $this->request->data['length'] : PAGE_RECORD_LIMIT;
		$start_page 		= isset($this->request->data['page']) ? $this->request->data['page'] : 1;
		$this->CurrentPage  = $start_page;
		$arrCondition 		= array('c.customer_id'=>$customer_id);
		if(isset($this->request->data['proj_name']) && $this->request->data['proj_name']!='') {
            $arrCondition['Projects.name LIKE'] = '%'.$this->request->data['proj_name'].'%';
        }
      	$ProjectList 	= $this->Projects->find('all',['join'=>[
					        'c' => [
					            'table' => 'customer_projects',
					            'type' => 'INNER',
					            'conditions' => ['c.project_id = Projects.id']
			            	],
			            	'ApplyOnline' => [
					            'table' => 'apply_onlines',
					            'type' => 'LEFT',
					            'conditions' => ['ApplyOnline.project_id = Projects.id']
			            	],
			            	'parameters' => [
					            'table' => 'parameters',
					            'type' => 'LEFT',
					            'conditions' => ['parameters.para_id = Projects.customer_type']
			            	]],
			            	'fields' => array('Projects.id','Projects.name','Projects.address','Projects.landmark','Projects.city','Projects.state','Projects.country','Projects.created','Projects.customer_type','parameters.para_value','Projects.capacity_kw','Projects.recommended_capacity','Projects.maximum_capacity','ApplyOnline.project_id'),
			            	'conditions' => $arrCondition,
			            	'order' => array('Projects.id' => 'DESC'),
			            	'page'	=> $this->CurrentPage,
							'limit' => $this->intLimit
			            ]);
						
		$this->paginate['limit'] 	= $this->intLimit;
		$this->paginate['page'] 	= $this->CurrentPage;
		$projectData 				= array();
		try {
			$arrProjects				= $this->paginate($ProjectList);
			if(!empty($arrProjects)) {
				$projectArr = $arrProjects->toArray();
				foreach($projectArr as $key=>$value) {
					$projectData[$key]['id']					= $projectArr[$key]['id'];
					$projectData[$key]['name'] 					= $projectArr[$key]['name'];
					$projectData[$key]['landmark'] 				= $projectArr[$key]['landmark'];
					$projectData[$key]['address'] 				= $projectArr[$key]['address'];
					$projectData[$key]['city'] 					= $projectArr[$key]['city'];
					$projectData[$key]['state']					= $projectArr[$key]['state'];

					$arr_state_details 							= $this->States->find('all',array('conditions'=>array('or'=>['statename'=>strtolower($projectArr[$key]['state']),'id'=>$projectArr[$key]['state']])))->first();
					$installer[$key]['state_id']				= 0;
					if(!empty($arr_state_details))
					{
						$projectData[$key]['state_id']			= $arr_state_details->id;
					}
					$projectData[$key]['country']				= $projectArr[$key]['country'];
					$projectData[$key]['cus_type'] 				= $projectArr[$key]['parameters']['para_value'];
					$projectData[$key]['capacity_kw'] 			= $projectArr[$key]['capacity_kw'];
					$projectData[$key]['recommended_capacity'] 	= $projectArr[$key]['recommended_capacity'];
					$projectData[$key]['maximum_capacity'] 		= $projectArr[$key]['maximum_capacity'];
					$projectData[$key]['proj_time'] 			= date("h:i a", strtotime($projectArr[$key]['created']));
					$projectData[$key]['proj_date'] 			= date("d/m/y", strtotime($projectArr[$key]['created']));
					$projectData[$key]['hasInstaller'] 			= 0;
					$id 										= $projectData[$key]['id'];
					$installerlist 								= $this->InstallerProjects->countinstaller($id);
					if($installerlist > 0) {
						$projectData[$key]['hasInstaller']	= 1;
					}
					$projectData[$key]['apply_online']	= 1;
					if (isset($projectArr[$key]['ApplyOnline']['project_id']) && $projectArr[$key]['ApplyOnline']['project_id'] > 0)
					{
						$projectData[$key]['apply_online']	= 0;
					}
				}	
			}		
			$this->ApiToken->SetAPIResponse('type', 'ok');
			$this->ApiToken->SetAPIResponse('result', $projectData);
			$this->ApiToken->SetAPIResponse('limit', $this->intLimit);
			$this->ApiToken->SetAPIResponse('CurrentPage', $this->CurrentPage);
			$this->ApiToken->SetAPIResponse('page_count',(isset($this->request->params['paging']['Projects']['pageCount'])?$this->request->params['paging']['Projects']['pageCount']:0));
		} catch (NotFoundException $e) {
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('result', $projectData);
			$this->ApiToken->SetAPIResponse('limit', $this->intLimit);
			$this->ApiToken->SetAPIResponse('CurrentPage', $this->CurrentPage);
			$this->ApiToken->SetAPIResponse('page_count',(isset($this->request->params['paging']['Projects']['pageCount'])?$this->request->params['paging']['Projects']['pageCount']:0));
		}
		
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	
	/**
	*
	* getProjectContactedInstaller
	*
	* Behaviour : public
	*
	* @defination : Method is used to get project contacted installer.
	*
	*/
	public function getProjectContactedInstaller() {

		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		$project_id 	= $this->request->data['proj_id'];
		$installerArr 	= array();
		
		if(!empty($project_id)) { 
			$installerArr 	= $this->InstallerProjects->find('all',['join'=>[
						        'Installers' => [
						            'table' => 'installers',
						            'type' => 'INNER',
						            'conditions' => ['Installers.id = InstallerProjects.installer_id']
				            	],
				            	'parameters' => [
						            'table' => 'parameters',
						            'type' => 'INNER',
						            'conditions' => ['parameters.para_id = InstallerProjects.status']
				            	]],
				            	'fields' => array('Installers.installer_name','Installers.address','Installers.city','Installers.mobile','lead_status'=>'InstallerProjects.status'),
				            	'conditions' => array('InstallerProjects.project_id'=>$project_id),
				            	'order' => array('Installers.installer_name' => 'ASC')])->toArray(); 

			$installerData = array();
			if(!empty($installerArr)) {
				foreach($installerArr as $key=>$value) {
					$installerData[$key]['installer_name']		= $installerArr[$key]['Installers']['installer_name'];
					$installerData[$key]['address'] 			= $installerArr[$key]['Installers']['address'];
					$installerData[$key]['city'] 				= $installerArr[$key]['Installers']['city'];
					$installerData[$key]['mobile'] 				= $installerArr[$key]['Installers']['mobile'];
					$installerData[$key]['lead_status'] 		= $installerArr[$key]['lead_status'];
				}	
			}		
			$this->ApiToken->SetAPIResponse('type', 'ok');
			$this->ApiToken->SetAPIResponse('result', $installerData);
		} else {
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'Invalid Request');
		}
      	echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	/**
	*
	* deleteCustomerProject
	*
	* Behaviour : public
	*
	* @defination : Method is used to delete customer project.
	*
	*/
	public function deleteCustomerProject() 
	{ 
		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		$project_id = $this->request->data['proj_id'];

		if(!empty($project_id))	{
			$this->Projects->updateAll(['status' => 'deleted'], ['id' => $project_id]);
			$this->ApiToken->SetAPIResponse('type', 'ok');
			$this->ApiToken->SetAPIResponse('msg', 'Success');
		} else {
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'Invalid Request');
		}		
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	*
	* getprojectdetail
	*
	* Behaviour : public
	*
	* @defination : Method is used to get project detail.
	*
	*/
	public function getprojectdetail() 
	{
		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		$project_id 		= isset($this->request->data['proj_id'])?$this->request->data['proj_id']:0;
		$projectData 		= array();
		$customerData 		= array();
		$returnArr 			= array();
		$bill_category 		= array();
		$branchmaster_list 	= array();
		$discom_arr 		= array();
		$installerData 		= array();
		if(!empty($project_id)) {
			$ProjectInfo 		= $this->Projects->find('all', array('conditions'=>array('id'=>$project_id)))->toArray();
			$ProjectInfo 		= (!empty($ProjectInfo[0]))?$ProjectInfo[0]:$ProjectInfo;
            if(!empty($ProjectInfo)) {
                $ProjectInfo->createdDate 	= date("d/m/Y", strtotime($ProjectInfo->created));
                $ProjectInfo->createdTime 	= date("h:i:a", strtotime($ProjectInfo->created));
                $customerArr 				= $this->Customers->getCustomerByProjectid($project_id);
                $customerData 				= (isset($customerArr[0])?$customerArr[0]:array());
                $project_state_name 		= $ProjectInfo->state;
                $arrState 					= $this->States->getStateByName($project_state_name);
                $project_state 				= isset($arrState->id)?$arrState->id:0;
				$projectInstallers 			= $this->InstallerProjects->getProjectwiseInstallerList($project_id);
				if (!empty($projectInstallers)) {
					foreach ($projectInstallers as $Installer) {
						$installerData[] = $Installer['installers'];
					}
				}
				$BillCategoryList 	= $this->Parameters->GetParameterList(3);
				if(!empty($BillCategoryList)) {
					foreach($BillCategoryList as $k=>$v){
						$bill_category[] = ['id'=>$k,'name'=>$v];
					}
				}
				$arrCondition 		= [	'BranchMasters.status'=>'1',
										'BranchMasters.parent_id'=>'0',
										'BranchMasters.state'=>$project_state];
				$discoms 			= $this->BranchMasters->find("list",
																[	'keyField'=>'id',
																	'valueField'=>'title',
																	'conditions'=>$arrCondition
																])->toArray();
				if(empty($discoms)) {
					$discom_arr[] = array("id"=>0,"title"=>"--NO DISCOM--");
				} else {
					foreach($discoms as $id=>$title) {
						$discom_arr[] = array("id"=>$id,"title"=>$title);
					}
				}
				if(isset($project_state) && !empty($project_state)) {
					$discom_list = $this->DiscomMaster->find("all",
															[	'fields'=>['id','title'],
																'conditions'=>['DiscomMaster.state_id'=>$project_state,
																				'DiscomMaster.type'=>3]
															])->toArray();
				}
            }
			$returnArr 	= array('projectData'=>$ProjectInfo,
								'installerData'=>$installerData,
								'customerData'=>$customerData,
								'bill_category'=>$bill_category,
								'discom_list'=>$discom_list,
								'discoms'=>$discom_arr);
			$this->ApiToken->SetAPIResponse('type', 'ok');
			$this->ApiToken->SetAPIResponse('result', $returnArr);
		} else {
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'Project not found.');
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	*
	* getfinancialincentives
	*
	* Behaviour : public
	*
	* Parameter : state(str), city(str)	
	*
	* @defination : Method is used to get financial incentives.
	*
	*/
	public function getfinancialincentives() 
	{
		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		$incentiveData		= array();
		$arrCondition		= array();
		if(isset($this->request->data['state']) && $this->request->data['state']!='') {
           $arrCondition['state like'] = '%'.$this->request->data['state'].'%';
        }
        $incentiveData  = $this->FinancialIncentives->find('all',array("conditions"=>$arrCondition))->first();
		$result = "<p><strong>State</strong></p><p>".$incentiveData['state']."</p>";
		if (isset($incentiveData['netmetering']) && !empty($incentiveData['netmetering'])) {
			$result .= "<p><strong>Net Metering</strong></p><p>".($incentiveData['netmetering'])."</p>";
		}
		$result .= "<p><strong>Incentive</strong></p><p>".($incentiveData['incentive'])."</p>";
		if (isset($incentiveData['other_text']) && !empty($incentiveData['other_text'])) {
			$HEADING = (isset($incentiveData['other_title']) && !empty($incentiveData['other_title'])?$incentiveData['other_title']:"Additional Information");
			$result .= "<p><strong>".$HEADING."</strong></p><p>".($incentiveData['other_text'])."</p>";
		}
		$result = str_replace(array("\n", "\t", "\r"), '', $result);
		$this->ApiToken->SetAPIResponse('type', 'ok');
		$this->ApiToken->SetAPIResponse('result', $result);
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	*
	* edit_project_estimation
	*
	* Behaviour : public
	*
	* Parameter : proj_id
	*
	* @defination : Method is used to get edit estimation data.
	*
	*/
	public function edit_project_estimation() {

		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		$project_id 		= $this->request->data['Projects']['id'];
		$projectData 		= array(); 
		
		if(!empty($project_id)) {
			$projectData 	= $this->Projects->get($project_id);			
			$arrReturn['proj_name'] 		= $projectData['name'];
			$arrReturn['lat'] 				= $projectData['latitude'];
			$arrReturn['lon'] 				= $projectData['longitude'];
			$arrReturn['c_type'] 			= $projectData['customer_type'];
			$arrReturn['roof_area'] 		= $projectData['area'];
			$arrReturn['area_type'] 		= $projectData['area_type'];
			$arrReturn['bill'] 				= $projectData['avg_monthly_bill'];
			$arrReturn['energy_con'] 		= $projectData['estimated_kwh_year'];
			$arrReturn['avg_gen'] 			= $projectData['avg_generate']; 
			$arrReturn['capacity'] 			= ($projectData['recommended_capacity'] > 10)?floor($projectData['recommended_capacity']):number_format($projectData['recommended_capacity'],1);
			$arrReturn['highcapacity'] 		= $projectData['maximum_capacity'];
			$arrReturn['backup_type'] 		= $projectData['backup_type'];
			$arrReturn['hours'] 			= $projectData['usage_hours'];
			$arrReturn['saving_month'] 		= _FormatGroupNumberV2($projectData['estimated_saving_month']);
			$arrReturn['solar_ratio'] 		= round($projectData['solar_ratio']);
			$arrReturn['is_residential'] 	= ($projectData['customer_type'] == '3001')?'1':'0';
			
			$this->ApiToken->SetAPIResponse('type', 'ok');
			$this->ApiToken->SetAPIResponse('result', $arrReturn);
		} else {
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'Invalid request data.');
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	*
	* getProjectEstimationById
	*
	* Behaviour : public
	*
	* Parameter : proj_id
	*
	* @defination : Method is used to get project estimation data using project id.
	*
	*/
	public function getProjectEstimationById() {

		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		$project_id 		= $this->request->data['Projects']['id'];
		$projectData 		= array(); 
		
		if(!empty($project_id)) {
			$this->ApiToken->SetAPIResponse('type', 'ok');
			$projectData 											= $this->Projects->get($project_id);
			$this->request->data['Projects']['id']					= (isset($projectData['id'])?$projectData['id']:0);
			$this->request->data['proj_name']  						= (isset($projectData['name'])?$projectData['name']:0);
			$this->request->data['latitude']						= (isset($projectData['latitude'])?$projectData['latitude']:0);
			$this->request->data['Projects']['latitude']			= (isset($projectData['latitude'])?$projectData['latitude']:0);
			$this->request->data['longitude']						= (isset($projectData['longitude'])?$projectData['longitude']:0);
			$this->request->data['Projects']['longitude']			= (isset($projectData['longitude'])?$projectData['longitude']:0);
			$this->request->data['project_type']					= (isset($projectData['customer_type'])?$projectData['customer_type']:0);
			$this->request->data['customer_type']					= (isset($projectData['customer_type'])?$projectData['customer_type']:0);
			$this->request->data['Projects']['customer_type']		= (isset($projectData['customer_type'])?$projectData['customer_type']:0);
			$this->request->data['area']							= (isset($projectData['area'])?$projectData['area']:0);
			$this->request->data['Projects']['area']				= (isset($projectData['area'])?$projectData['area']:0);
			$this->request->data['Projects']['area_type'] 			= (isset($projectData['area_type'])?$projectData['area_type']:0);
			$this->request->data['area_type'] 						= (isset($projectData['area_type'])?$projectData['area_type']:0);
			$this->request->data['avg_monthly_bill'] 				= (isset($projectData['avg_monthly_bill'])?$projectData['avg_monthly_bill']:0);
			$this->request->data['bill'] 							= (isset($projectData['avg_monthly_bill'])?$projectData['avg_monthly_bill']:0);
			$this->request->data['backup_type']						= (isset($projectData['backup_type'])?$projectData['backup_type']:0);
			$this->request->data['usage_hours']						= (isset($projectData['usage_hours'])?$projectData['usage_hours']:0);
			$this->request->data['Projects']['usage_hours']			= (isset($projectData['usage_hours'])?$projectData['usage_hours']:0);
			$this->request->data['energy_con']						= (isset($projectData['estimated_kwh_year'])?$projectData['estimated_kwh_year']:0);
			$this->request->data['Projects']['estimated_kwh_year'] 	= (isset($projectData['estimated_kwh_year'])?$projectData['estimated_kwh_year']:0);
			$this->request->data['Projects']['address'] 			= (isset($projectData['address'])?$projectData['address']:'');
			$this->request->data['Projects']['city']				= (isset($projectData['city'])?$projectData['city']:'');
			$this->request->data['Projects']['state']				= (isset($projectData['state'])?$projectData['state']:'');
			$this->request->data['Projects']['state_short_name']	= (isset($projectData['state_short_name'])?$projectData['state_short_name']:'');
			$this->request->data['Projects']['country']				= (isset($projectData['country'])?$projectData['country']:'');
			$this->request->data['Projects']['pincode']				= (isset($projectData['postal_code'])?$projectData['postal_code']:'');
			$this->request->data['Projects']['landmark']			= (isset($projectData['landmark'])?$projectData['landmark']:'');
			$result = $this->Projects->getprojectestimation($this->request->data,$this->ApiToken->customer_id);
			$this->ApiToken->SetAPIResponse('result', $result);
			$status = 'ok';
			$this->ApiToken->SetAPIResponse('type', $status);
			echo $this->ApiToken->GenerateAPIResponse();
			exit;
		} else {
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'Invalid request data.');
			echo $this->ApiToken->GenerateAPIResponse();
			exit;
		}
	}

	/**
	*
	* get_financial_incentive_state_list
	*
	* Behaviour : public
	*
	* Parameter : 
	*
	* @defination : Method is used to get edit estimation data.
	*
	*/
	public function get_financial_incentive_state_list() {

		$this->autoRender 	= false;
		$stateData 			= array(); 
		$stateData  		= $this->FinancialIncentives->find('all', array(
														'fields' => array('state'), 
														'group' => 'state'))->toArray();
		$arrReturn 			= $stateData;
		$this->ApiToken->SetAPIResponse('type', 'ok');
		$this->ApiToken->SetAPIResponse('result', $arrReturn);
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	*
	* get_financial_incentive_city_list
	*
	* Behaviour : public
	*
	* Parameter : state(str)
	*
	* @defination : Method is used to get edit estimation data.
	*
	*/
	public function get_financial_incentive_city_list() {

		$this->autoRender 	= false;
		$state 		= $this->request->data['state'];
		$cityData 	= array();
		if(!empty($state)) {
			$cityData  	= $this->FinancialIncentives->find('all', array(
														'fields' => array('city'),
														'conditions' => array('state'=>$state), 
														'group' => 'city'))->toArray();
			$arrReturn 	= $cityData;
			$this->ApiToken->SetAPIResponse('type', 'ok');
			$this->ApiToken->SetAPIResponse('result', $arrReturn);
		} else {
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'Invalid request data.');
		}		
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	*
	* getestimationbycapacity
	*
	* Behaviour : public
	*
	* Parameter : user_capacity, proj_id
	*
	* @defination : Method is used to get estimation by capacity.
	*
	*/
	public function getestimationbycapacity()
	{
		$this->autoRender 		= false;
		$this->SetVariables($this->request->data);
		$project_id 			= $this->request->data['Projects']['id'];
		$solarPvInstall			= $this->request->data['Projects']['user_capacity'];
		$projectData 			= $this->Projects->get($project_id);
		
		$capitalCost			= $this->Projects->calculatecapitalcost($solarPvInstall,$projectData['state'],$projectData['customer_type']);

		$solarRediationData		= $this->Projects->getSolarRediation($projectData['latitude'],$projectData['longitude']);
		
		$annualTotalRad			= ($solarRediationData['ann_glo']*365);
		/*$averageEnrgyGenInDay = (((($solarPvInstall*$solarRediationData['ann_glo'])*PERFORMANCE_RATIO)/100)*1.1);*/
		/*$monthChartDataArr	= $this->calculateMonthChartData($solarRediationData,$solarPvInstall);*/

		$capacityAcualEnrgyCon	= ((($projectData['estimated_kwh_year']*12)/$annualTotalRad));
		$contractLoad			= ((($projectData['estimated_kwh_year']*12)/((24*365*LOAD_FECTORE)/100)));
		$recommendedSolarPvInstall 		= min($solarPvInstall, $contractLoad, $capacityAcualEnrgyCon);
		$highRecommendedSolarPvInstall 	= max($solarPvInstall, $contractLoad, $capacityAcualEnrgyCon);

		/** overwrite recommanded capacity based on changed value from scaller */
		$recommendedSolarPvInstall 	= $solarPvInstall;
		/** overwrite recommanded capacity based on changed value from scaller */

		$averageEnrgyGenInYear	= round(((($solarPvInstall*$annualTotalRad)*PERFORMANCE_RATIO)/100));
		$capitalCostsubsidy		= $this->Projects->calculatecapitalcostwithsubsidy($recommendedSolarPvInstall,$capitalCost,$projectData['state'],$projectData['customer_type']);
		$averageEnrgyGenInDay 	= (((($recommendedSolarPvInstall*$solarRediationData['ann_glo'])*PERFORMANCE_RATIO)/100));
		$monthChartDataArr		= $this->Projects->calculateMonthChartData($solarRediationData,$recommendedSolarPvInstall);
		
		/* Calculate saving */
		$bill 					= $projectData['avg_monthly_bill'];
		$energy_con 			= $projectData['estimated_kwh_year'];
		$montly_pv_generation 	= ($averageEnrgyGenInDay * 30);
		$monthly_saving 		= ($bill - ($energy_con - $montly_pv_generation) * (($bill/$energy_con)-0.5)); 
		/* Calculate saving */

		$cost_solar				= 0.0;
		$unitRate				= (($projectData['avg_monthly_bill']/$projectData['estimated_kwh_year'])-0.5);
		$solarChart 			= $this->Projects->getBillingChart($contractLoad,$unitRate,$averageEnrgyGenInYear,round(($capitalCostsubsidy/100000),2),$projectData['backup_type'],$projectData['usage_hours']);
		$payBack 				= (isset($solarChart['breakEvenPeriod'])?$solarChart['breakEvenPeriod']:0);
		$fromPvSystem 			= (isset($solarChart['fromPvSystem'])?$solarChart['fromPvSystem']:array());
		$gross_solar_cost		= $this->Projects->getTarifCalculation(25,$fromPvSystem[1]['yearlyEnergyGenerated'],$projectData['avg_monthly_bill'],$capitalCost);
		$cost_solar				= $gross_solar_cost['net_cog'];
		$chart					= $this->Projects->genrateApiChartData($fromPvSystem,$monthChartDataArr);
		$averageEnrgyGenInMonth	= ($averageEnrgyGenInYear/12);
		$solar_ratio			= (($energy_con > 0)?(($averageEnrgyGenInMonth/$energy_con) * 100):0);
		
		$estimated_cost_subsidy = isset($capitalCostsubsidy)?round(($capitalCostsubsidy/100000),2):$capitalCost;
		$payBackGraphData 		=  $this->Projects->GetPaybackChartData($estimated_cost_subsidy, ($monthly_saving*12));

		if($payBackGraphData) {
			foreach ($payBackGraphData as $key => $value) {
				$savingChartArr[] 		= "{'x':".$key.",'y':".round($value)."}";	
			}
		}

		$projectData['contract_load']			= $contractLoad;
		$projectData['paybackChart']			= '['.implode(',',$savingChartArr).']';
		$projectData['cost_solar']				= $cost_solar;
		$projectData['solar_ratio']				= $solar_ratio;
		$projectData['estimated_saving_month']	= $monthly_saving;
		$projectData['payback']					= $payBack;
		$projectData['estimated_cost']			= $capitalCost;
		$projectData['estimated_cost_subsidy']	= $capitalCostsubsidy;
		$projectData['avg_generate']			= $averageEnrgyGenInMonth;
		$projectData['recommended_capacity']	= $recommendedSolarPvInstall;
		$projectData['maximum_capacity']		= $highRecommendedSolarPvInstall;

		$projectsEntity = $this->Projects->patchEntity($projectData,$this->request->data());
		$dataProject 	= array();
		 if ($this->Projects->save($projectsEntity)) {
			$dataProject['CustomerProjects']['customer_id']	= $this->ApiToken->customer_id;
			$dataProject['CustomerProjects']['project_id']	= $projectsEntity->id;
			
			$status	= 'ok';
			$messege = array(); 
			$messege = array_merge($messege,$chart);
			$messege['proj_id']			= $projectsEntity->id;
			$messege['cost_solar']		= $cost_solar;
			$messege['paybackChart']	= '['.implode(',',$savingChartArr).']';
			$messege['saving_month']	= _FormatGroupNumberV2($monthly_saving);
			$messege['capacity']		= $recommendedSolarPvInstall;
			$messege['highcapacity']	= round($highRecommendedSolarPvInstall);
			$messege['est_cost']		= $capitalCost;
			$messege['est_cost_subsidy'] = (!empty($capitalCostsubsidy)?round($capitalCostsubsidy/100000,2):0);
			$messege['avg_gen']			= _FormatGroupNumberV2($averageEnrgyGenInMonth,2);
			$messege['payback']			= round($payBack,2);
			$messege['solar_ratio'] 	= ($solar_ratio > 100)?'100':round($solar_ratio);
			$this->ApiToken->SetAPIResponse('result', $messege);
		} else {
			$status	= 'error';
			$error	= 'Please try after some time';
			$this->ApiToken->SetAPIResponse('msg', $error);
		}
		$this->ApiToken->SetAPIResponse('type', $status);
		echo stripslashes($this->ApiToken->GenerateAPIResponse());
	}

	/**
	*
	* calculateMonthChartData
	*
	* Behaviour : public
	*
	* Parameter : $solarRediationData,$solarPvInstall
	*
	* @defination : Method is used to calculate month chart data.
	*
	*/
	public function calculateMonthChartData($solarRediationData,$solarPvInstall)
	{
		$chartDataArr = array();
		if(!empty($solarRediationData)) {
			for($i=1;$i<=12;$i++) {
				$keyName 		= strtolower(date('M', mktime(0, 0, 0, $i, 10)))."_glo";
				$monthGloVal 	= (isset($solarRediationData[$keyName])?$solarRediationData[$keyName]:0);
				$chartDataArr[$i] = ((((($solarPvInstall*$monthGloVal)*PERFORMANCE_RATIO)/100))*cal_days_in_month(CAL_GREGORIAN, $i, date("Y")));
			}
		}
		return $chartDataArr;
	}
	/**
	*
	* calculateInverterUsage
	*
	* Behaviour : public
	*
	* Parameter : $contractLoad
	*
	* Parameter : $usage_hours
	*
	* @defination : Method is used to calculate inverter usage.
	*
	*/
	public function calculateInverterUsage($contract_load=0, $usage_hours)
	{
		$cost_electricty 		= 0; 
		$usage_hours 			= (isset($usage_hours)?$usage_hours:0);
		$electricity_equivalent = (($usage_hours>0)?$usage_hours:0) * 0.5 * $contract_load;
		$cost_electricty_day 	= $electricity_equivalent * INVERTER_ELECTRICITY_COST;
		$cost_electricty 		= (($cost_electricty_day > 0)?($cost_electricty_day * 365/12):0);
		return $cost_electricty;
	}
	/**
	*
	* calculateMonthChartData
	*
	* Behaviour : public
	*
	* Parameter : $contractLoad
	*
	* Parameter : $usage_hours: 
	*
	* @defination : Method is used to calculate generator usage.
	*
	*/
	public function calculateGeneratorUsage($contract_load=0, $usage_hours)
	{
		$cost_electricty 		= 0; 
		$usage_hours 			= (isset($usage_hours)?$usage_hours:0);
		$electricity_equivalent = (($usage_hours>0)?$usage_hours:0) * 0.5 * $contract_load;
		$cost_electricty_day 	= $electricity_equivalent * GENERATOR_ELECTRICITY_COST;
		$cost_electricty 		= (($cost_electricty_day > 0)?($cost_electricty_day * 365/12):0);
		return $cost_electricty;
	}

	/**
	 *
	 * view
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to view Project
	 *
	 */
	public function view($id= null)
	{
		if(empty($id)) {
			$this->Flash->error('Please Select Valid Project.');             
			return $this->redirect(WEB_ADMIN_PREFIX.'/project/index/');
		} else {
			$id=intval(decode($id));
			$this->intCurAdminUserRight = $this->Userright->ADD_INSTALLER_PLAN;
			$customerData 				= $this->Projects->get($id);
		}
		$requestData 									= array();
		$requestData['energy_con'] 						= $customerData->estimated_kwh_year;
		$requestData['area_type'] 						= $customerData->area_type;
		$requestData['area'] 							= $customerData->area;
		$requestData['latitude'] 						= $customerData->latitude;
		$requestData['longitude'] 						= $customerData->longitude;
		$requestData['avg_monthly_bill'] 				= $customerData->avg_monthly_bill;
		$requestData['backup_type'] 					= $customerData->backup_type;
		$requestData['usage_hours'] 					= $customerData->usage_hours;
		$requestData['project_type'] 					= $customerData->project_type;
		$requestData['Projects']['address'] 			= (isset($customerData->address)?$customerData->address:'');
		$requestData['Projects']['city']				= (isset($customerData->city)?$customerData->city:'');
		$requestData['Projects']['state']				= (isset($customerData->state)?$customerData->state:'');
		$requestData['Projects']['state_short_name']	= (isset($customerData->state_short_name)?$customerData->state_short_name:'');
		$requestData['Projects']['country']				= (isset($customerData->country)?$customerData->country:'');
		$requestData['Projects']['pincode']				= (isset($customerData->postal_code)?$customerData->postal_code:'');
		$requestData['Projects']['landmark']			= (isset($customerData->landmark)?$customerData->landmark:'');

		$areaTypeArr 									= $this->Parameters->getAreaType();
		$custTypeArr 									= $this->Parameters->getProjectType();
		$resultArr 										= $this->Projects->getprojectestimation($requestData);
		$customerArr 									= $this->Customers->getCustomerByProjectid($id);
		$projectInstallers 								= $this->InstallerProjects->getProjectwiseInstallerList($id);
		
		/* Energy and Month Saving Data */
		$solarRediationData = $this->GhiData->getGhiData($customerData->longitude,$customerData->latitude);
		$energyAndSavingDataArr = $this->Projects->getMonthEnergyAndSavingData($solarRediationData,$customerData->recommended_capacity,$customerData->avg_monthly_bill,$customerData->estimated_kwh_year);
		/* Solar PV Chart Data */
		
		$monthSavinData 	= (!empty($energyAndSavingDataArr['saving_data'])?$energyAndSavingDataArr['saving_data']:array());
		$monthly_saving 	= array_sum($monthSavinData);
		$estimated_cost_subsidy = isset($customerData->estimated_cost_subsidy)?round(($customerData->estimated_cost_subsidy),2):$customerData->estimated_cost;
		$payBackGraphData 	= $this->Projects->GetPaybackChartData($estimated_cost_subsidy, ($monthly_saving));
		$paybackGraphImg 	= $this->Projects->paybackGraph($payBackGraphData);
		
		$this->set(compact('customerData','resultArr','projectInstallers','id','customerArr','areaTypeArr','custTypeArr','paybackGraphImg'));
	}

	public function viewreport($id = null)
	{
		$this->layout = false;	
		if(empty($id)) {
				$this->Flash->error('Please Select Valid Project.');             
				return $this->redirect(WEB_ADMIN_PREFIX.'/project/index/');
		} else {
			$id=intval(decode($id));
			$this->intCurAdminUserRight = $this->Userright->EDIT_PROJECT;
			//$customerData 				= $this->Projects->get($id);
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
				'conditions' => ['Projects.id' => $id],
				'order' => array('Projects.id' => 'DESC')])
				->autoFields(true)->first();
		}
		$pdfPath = $this->genratePDFreport($id,$project,true);
	}
	/**
	 *
	 * mailprojectdetail
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to mail Project detail to installer  
	 *
	 */
	public function mailprojectdetail($id= null)
	{
		$this->layout = false;	
		if(empty($id)) {
				$this->Flash->error('Please Select Valid Project.');             
				return $this->redirect(WEB_ADMIN_PREFIX.'/project/index/');
		} else {
			$id=intval(decode($id));
			$this->intCurAdminUserRight = $this->Userright->ADD_INSTALLER_PLAN;
			$projInstallers = $this->InstallerProjects->getProjectwiseInstallerList($id);
			/* Get project details */
			if(!empty($this->request->data))
			{	
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
				'conditions' => ['Projects.id' => $id],
				'order' => array('Projects.id' => 'DESC')])
				->autoFields(true)->first();
				$pdfPath = $this->genratePDFreport($id,$project,false,1);
				sleep(10);	
				$removeDup=array();
				 foreach ($projInstallers as $key => $value) {
					if(in_array($value->installers['id'], $this->request->data['installer']))
					{
						if(in_array($value->installers['id'],$removeDup)){
							continue;
						}
						$email = "";
						$email = str_replace(';',',',$value->installers['email']);
						//Send email to customer.
						//$email = "pravin.sanghani@yugtia.com";
						$this->request->data['Emaillog']['email']	=$email;
						$this->request->data['Emaillog']['action']='mailprojectdetail';
						$this->request->data['Emaillog']['description']='Send Prject report To Installer';
						$emailEntity			= $this->Emaillog->newEntity($this->request->data());
						$emailEntity->send_date = $this->NOW();
						$this->Emaillog->save($emailEntity);
						$Email = new Email('default');
						$Email->profile('default');
						$Email->viewVars(array('project_detail' => $project));
						$Email->template('send_installers_query_report', 'default')
							->emailFormat('html')
							->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
							->to(explode(',',$email))
							->bcc(explode(',',SEND_QUERY_INSTALLER_BCC_EMAIL))
							->subject(Configure::read('EMAIL_ENV').SEND_QUERY_INSTALLER_SUBJECT)
							->attachments($pdfPath)
							->send();	
						$removeDup[] = $value->installers['id'];
							
					}
				}
			}
			$this->Flash->set('Mail sent successfully.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
            return $this->redirect('admin/projects/view/'.encode($id)); 
		}
		$this->set(compact('projectInstallers','id'));
	
	}

	/**
	 *
	 * genratePDFreport
	 *
	 * Behaviour : Private
	 *
	 * @defination : Method is use to genratePDFreport
	 *
	 */
	private function genratePDFreport($id,$project,$isdownload=false,$isInstallerhide=0)
	{
		$this->layout = false;
		/* Get all project installers */
		$projectInstallers 	= array();
		if(empty($isInstallerhide))
		{
			$projectInstallers =$this->InstallerProjects->getProjectwiseInstallerList($id);
		}
		
		$projectData 						= $this->getestimationbycapacityByProjectID($project->id);
		$ProjectEstimation 					= $this->getProjectEstimationByPID($project->id);
		$solar_ratio 					 	= $ProjectEstimation['solar_ratio'];
		$project->estimated_saving_month 	= $ProjectEstimation['saving_month'];
		$project->estimated_cost 		 	= $ProjectEstimation['est_cost'];
		$project->estimated_cost_subsidy 	= $ProjectEstimation['est_cost_subsidy'] > 0?($ProjectEstimation['est_cost_subsidy']*100000):0;

		/* Generate map URL based on project location */
		$stream_opts =  [
    					"ssl" => [
				        "verify_peer"=>false,
				        "verify_peer_name"=>false,
				    	]
						];  
		$latLng = $project->latitude.",".$project->longitude;
		$mapUrl = 'https://maps.googleapis.com/maps/api/staticmap?center='.$latLng.'&maptype=hybrid&zoom=10&size=272x378&markers=color:blue%7C'.$latLng.'&sensor=false';
		$mapImage = file_get_contents($mapUrl, false, stream_context_create($stream_opts));
		$mapImage = 'data:image/png;base64,' . base64_encode($mapImage);

		/* Radiation Graph Generate */
		$radiationGraphArr 	= $this->Projects->getSolarRediationGHIChartData($project->latitude,$project->longitude);
		
		if(!empty($radiationGraphArr['radiation_ghi_data'])) {
			$radiationGraphData['radiation_ghi_data'] = (!empty($radiationGraphArr['radiation_ghi_data'])?$radiationGraphArr['radiation_ghi_data']:array());
			$radiationGraphImg = $this->Projects->radiationGraph($radiationGraphData);
		} else {
			$radiationGraphImg="";
		}
		/* Energy and Month Saving Data */
		
		$solarRediationData = $this->GhiData->getGhiData($project->longitude,$project->latitude);
		$energyAndSavingDataArr = $this->Projects->getMonthEnergyAndSavingData($solarRediationData,$project->recommended_capacity,$project->avg_monthly_bill,$project->estimated_kwh_year);
		
		/* Solar PV Chart Data */
		$monthSavinData 	= (!empty($energyAndSavingDataArr['saving_data'])?$energyAndSavingDataArr['saving_data']:array());
		$monthly_saving 	= array_sum($monthSavinData);
		$estimated_cost_subsidy = isset($project->estimated_cost_subsidy)?round(($project->estimated_cost_subsidy/100000),2):$project->estimated_cost;
		$payBackGraphData 	= $this->Projects->GetPaybackChartData($estimated_cost_subsidy, ($monthly_saving));
		
		if(!empty($payBackGraphData)) {
			$paybackGraphImg 	= $this->Projects->paybackGraph($payBackGraphData);
		} else {
			$paybackGraphImg="";
		}
		/* Get Environment Benefit Data. */
		$inpdataArr['recommendedCapacity'] = $project->estimated_kwh_year;
		$inpdataArr['estimatedKWHYear'] = $project->recommended_capacity;
		$environmentData = $this->Projects->calculateSolarPowerGreenSavingsData($inpdataArr);
		$hideInstaller = $isInstallerhide;
		
		/* Get PDF Report id. */
		$projectReportId = $this->Projects->GetProjectPDFReportId($id);
		
		$this->set(compact("project","projectInstallers","mapImage","radiationGraphImg","paybackGraphImg","radiationGraphData","energyAndSavingDataArr","projectReportId","environmentData","hideInstaller","monthly_saving","solar_ratio","ProjectEstimation"));
		$this->set('pageTitle','Project');

		/* Generate PDF for estimation of project */
		require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
		$dompdf = new Dompdf($options = array());
		
		$dompdf->set_base_path(SITE_ROOT_DIR_PATH.'/pdf/');
		$dompdf->set_option('defaultFont', "Arial");
		
		$html = $this->render('../Installers/project_estimation');
		
		//exit($html);
		$dompdf->loadHtml($html,'UTF-8');
		$dompdf->setPaper('A4', 'portrait');
		
		$dompdf->render();
		// Output the generated PDF to Browser
		if($isdownload){
			$dompdf->stream('report-'.$projectReportId);	
		}
		$output = $dompdf->output();
		$pdfPath = SITE_ROOT_DIR_PATH.'/tmp/report-'.$projectReportId.'.pdf';
		file_put_contents($pdfPath, $output);	
		return $pdfPath; 
	}

	/**
	 *
	 * sendProjectReportToCustomer
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use send project report to app login customer. 
	 *
	 */
	public function sendProjectReportToCustomer()
	{
		$this->autoRender = false;
		$this->layout = false;

		$customerId = $this->ApiToken->customer_id;
		$projectId 	= $this->request->data('proj_id');

		if(empty($projectId) || empty($customerId)) {
			$type 	= 'error';
			$msg 	= 'Invalid Request';
		} else {

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
			
			$projectdata = $this->Projects->get($projectId);
			$customerArr = $this->Customers->findById($customerId)->first();
			$projectName = (isset($projectdata->name)?$projectdata->name:'');

			if($customerArr['email']) {
				$pdfPath = $this->genratePDFreport($projectId,$projectdata,false,0);
				
				$to			= $customerArr['email'];
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

				$type 	= 'ok';
				$msg 	= 'Project report send successfully.';  
			} else {
				$type 	= 'error';
				$msg 	= 'Invalid Request';
			}
		}				
		$this->ApiToken->SetAPIResponse('type',$type);
		$this->ApiToken->SetAPIResponse('msg',$msg);
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	/**
	*
	* getestimationbycapacityByProjectID
	*
	* Behaviour : public
	*
	* Parameter : user_capacity, proj_id
	*
	* @defination : Method is used to get estimation by capacity.
	*
	*/
	public function getestimationbycapacityByProjectID($project_id=0)
	{
		$this->autoRender 		= false;
		$projectData 			= $this->Projects->get($project_id);
		$solarPvInstall			= $projectData->user_capacity;
		
		$capitalCost			= $this->calculatecapitalcost($solarPvInstall,$projectData->state,$projectData->customer_type);
		$solarRediationData		= $this->getSolarRediation($projectData->latitude,$projectData->longitude);
		
		$annualTotalRad			= ($solarRediationData['ann_glo']*365);
		$capacityAcualEnrgyCon	= ((($projectData->estimated_kwh_year*12)/$annualTotalRad));
		$contractLoad			= ((($projectData->estimated_kwh_year*12)/((24*365*LOAD_FECTORE)/100)));
		$recommendedSolarPvInstall 		= min($solarPvInstall, $contractLoad, $capacityAcualEnrgyCon);	
		$highRecommendedSolarPvInstall 	= max($solarPvInstall, $contractLoad, $capacityAcualEnrgyCon);	
		$averageEnrgyGenInYear	= round(((($solarPvInstall*$annualTotalRad)*PERFORMANCE_RATIO)/100)*1.1);
		
		$capitalCostsubsidy		= $this->calculatecapitalcostwithsubsidy($recommendedSolarPvInstall,$capitalCost,$projectData->state,$projectData->customer_type);
		$averageEnrgyGenInDay 	= (((($recommendedSolarPvInstall*$solarRediationData['ann_glo'])*PERFORMANCE_RATIO)/100));
		$monthChartDataArr		= $this->calculateMonthChartData($solarRediationData,$recommendedSolarPvInstall);
		/* Calculate saving */
		$bill 					= $projectData->avg_monthly_bill;
		
		$energy_con 			= $projectData->estimated_kwh_year;

		$montly_pv_generation 	= ($averageEnrgyGenInDay * 30);
		if($energy_con!=0)
		{
				$monthly_saving 		= ($bill - ($energy_con - $montly_pv_generation) * (($bill/$energy_con)-0.5)); 
		}
		else
		{
				$monthly_saving 		= ($bill - ($energy_con - $montly_pv_generation) * (0-0.5)); 
		}
		/* Calculate saving */
		$cost_solar				= 0.0;
		if($projectData->estimated_kwh_year!=0)
		{
			$unitRate				= (($projectData->avg_monthly_bill/$projectData->estimated_kwh_year)-0.5);
		}
		else
		{
			$unitRate				= (($projectData->avg_monthly_bill)-0.5);
		}
		
		$solarChart 			= $this->getBillingChart($contractLoad,$unitRate,$averageEnrgyGenInYear,$capitalCostsubsidy,$projectData->backup_type,$projectData->usage_hours);
		
		$payBack 				= (isset($solarChart['breakEvenPeriod'])?$solarChart['breakEvenPeriod']:0);
		$fromPvSystem 			= (isset($solarChart['fromPvSystem'])?$solarChart['fromPvSystem']:array());
		$gross_solar_cost		= $this->getTarifCalculation(25,$fromPvSystem[1]['yearlyEnergyGenerated'],$projectData->avg_monthly_bill,$capitalCost);
		$cost_solar				= $gross_solar_cost['net_cog'];
		$chart					= $this->genrateApiChartData($fromPvSystem,$monthChartDataArr);
		$averageEnrgyGenInMonth	= ($averageEnrgyGenInYear/12);
		$solar_ratio			= (($energy_con > 0)?(($averageEnrgyGenInMonth/$energy_con) * 100):0);
			
		
		$projectData['contract_load']			= $contractLoad;
		$projectData['cost_solar']				= $cost_solar;
		$projectData['solar_ratio']				= ($solar_ratio > 100)?'100':round($solar_ratio);
		$projectData['estimated_saving_month']	= $monthly_saving;
		$projectData['payback']					= $payBack;
		$projectData['estimated_cost']			= $capitalCost;
		$projectData['estimated_cost_subsidy']	= $capitalCostsubsidy;
		$projectData['avg_generate']			= $averageEnrgyGenInMonth;
		$projectData['recommended_capacity']	= $recommendedSolarPvInstall;
		$projectData['maximum_capacity']		= $highRecommendedSolarPvInstall;
		
		$status						= 'ok';
		$messege 					= array(); 
		$messege 					= array_merge($messege,$chart);
		$messege['proj_id']			= $project_id;
		$messege['cost_solar']		= $cost_solar;
		$messege['saving_month']	= _FormatGroupNumberV2($monthly_saving);
		$messege['capacity']		= $recommendedSolarPvInstall;
		$messege['highcapacity']	= round($highRecommendedSolarPvInstall);
		$messege['est_cost']		= $capitalCost;
		$messege['est_cost_subsidy'] = (!empty($capitalCostsubsidy)?round($capitalCostsubsidy/100000,2):0);
		$messege['avg_gen']			= _FormatGroupNumberV2($averageEnrgyGenInMonth,2);
		$messege['payback']			= round($payBack,2);
		$messege['solar_ratio'] 	= ($solar_ratio > 100)?'100':round($solar_ratio);

		return $projectData;
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
			$projectData 											= $this->Projects->get($project_id);
			$this->request->data['latitude']						= (isset($projectData['latitude'])?$projectData['latitude']:0);
			$this->request->data['Projects']['latitude']			= (isset($projectData['latitude'])?$projectData['latitude']:0);
			$this->request->data['longitude']						= (isset($projectData['longitude'])?$projectData['longitude']:0);
			$this->request->data['Projects']['longitude']			= (isset($projectData['longitude'])?$projectData['longitude']:0);
			$this->request->data['customer_type']					= (isset($projectData['customer_type'])?$projectData['customer_type']:0);
			$this->request->data['project_type']					= (isset($projectData['customer_type'])?$projectData['customer_type']:0);
			$this->request->data['Projects']['customer_type']		= (isset($projectData['customer_type'])?$projectData['customer_type']:0);
			$this->request->data['area']							= (isset($projectData['area'])?$projectData['area']:0);
			$this->request->data['Projects']['area']				= (isset($projectData['area'])?$projectData['area']:0);
			$this->request->data['area_type'] 						= (isset($projectData['area_type'])?$projectData['area_type']:0);
			$this->request->data['bill'] 							= (isset($projectData['avg_monthly_bill'])?$projectData['avg_monthly_bill']:0);
			$this->request->data['avg_monthly_bill'] 				= (isset($projectData['avg_monthly_bill'])?$projectData['avg_monthly_bill']:0);
			$this->request->data['backup_type']						= (isset($projectData['backup_type'])?$projectData['backup_type']:0);
			$this->request->data['usage_hours']						= (isset($projectData['usage_hours'])?$projectData['usage_hours']:0);
			$this->request->data['usage_hours']						= (isset($projectData['usage_hours'])?$projectData['usage_hours']:0);
			$this->request->data['Projects']['usage_hours']			= (isset($projectData['usage_hours'])?$projectData['usage_hours']:0);
			$this->request->data['energy_con']						= (isset($projectData['estimated_kwh_year'])?$projectData['estimated_kwh_year']:0);
			$this->request->data['Projects']['estimated_kwh_year'] 	= (isset($projectData['estimated_kwh_year'])?$projectData['estimated_kwh_year']:0);
			$this->request->data['Projects']['address'] 			= (isset($projectData['address'])?$projectData['address']:'');
			$this->request->data['Projects']['city']				= (isset($projectData['city'])?$projectData['city']:'');
			$this->request->data['Projects']['state']				= (isset($projectData['state'])?$projectData['state']:'');
			$this->request->data['Projects']['state_short_name']	= (isset($projectData['state_short_name'])?$projectData['state_short_name']:'');
			$this->request->data['Projects']['country']				= (isset($projectData['country'])?$projectData['country']:'');
			$this->request->data['Projects']['pincode']				= (isset($projectData['postal_code'])?$projectData['postal_code']:'');
			$this->request->data['Projects']['landmark']			= (isset($projectData['landmark'])?$projectData['landmark']:'');
			$result = $this->Projects->getprojectestimation($this->request->data);
		}
		return $result;
	}
	/**
	 *
	 * downloadcsv
	 *
	 * Behaviour : public
	 *
	 * @defination : Method is use to download .xls file from project list
	 *
	 */
	public function downloadcsv()
	{
		$arrCondition		= $this->_generateProjectSearchCondition();
		$arr_date_search=array();
		if(array_key_exists('date_search', $arrCondition))
		{
			$arr_date_search=$arrCondition['date_search'];
			unset($arrCondition['date_search']);
		}
		$query_data	= $this->Projects->find('all',['join'=>[
				        'customer_projects' => [
				            'table' => 'customer_projects',
				            'type' => 'INNER',
				            'conditions' => ['Projects.id = customer_projects.project_id']
		            	],
		            	'c' => [
				            'table' => 'customers',
				            'type' => 'INNER',
							'alias' => 'c',
				            'conditions' => ['c.id = customer_projects.customer_id']
		            	],
						'installer_projects' => [
						            'table' => 'installer_projects',
						            'type' => 'INNER',
						            'conditions' => ['Projects.id = installer_projects.project_id']
				            	],
						'installers' => [
						            'table' => 'installers',
						            'type' => 'INNER',
						            'conditions' => ['installers.id = installer_projects.installer_id']
				            	],
						'parameters' => [
				            'table' => 'parameters',
				            'type' => 'LEFT',
				            'conditions' => ['parameters.para_id = Projects.customer_type']
		            	]],
		            	'conditions' => $arrCondition,
		            	'fields' => array('Projects.id','Projects.customer_type','Projects.address','Projects.state','Projects.landmark','Projects.latitude','Projects.longitude','Projects.recommended_capacity','Projects.address','c.name','c.email','c.mobile','installers.installer_name','installers.email','installers.mobile','parameters.para_value'),
		            	]);	
				if(!empty($arr_date_search))
				{
					$fields_date  = $arr_date_search['between'][0];
	       			$StartTime    = $arr_date_search['between'][1];
	       			$EndTime    = $arr_date_search['between'][2];
	                $query_data->where([function ($exp, $q) use ($StartTime, $EndTime,$fields_date) {
					return $exp->between($fields_date, $StartTime, $EndTime);
	           		 }]);
				}
				$projectArr = $query_data->toArray();
				$PhpExcel=$this->PhpExcel;
				$PhpExcel->createExcel();

				//$PhpExcel->additonalSheet(1,'Introduction');
				//$gdImage = imagecreatefrompng('pdf/images/logo_pdf.png');
				
				// Add a drawing to the worksheetecho date('H:i:s') . " Add a drawing to the worksheet\n";
				$objDrawing = new \PHPExcel_Worksheet_MemoryDrawing();
				
				
				$PhpExcel->writeCellValue('A1', 'Project ID');
				$PhpExcel->writeCellValue('B1', 'Customer Name');
				$PhpExcel->writeCellValue('C1', 'Customer Email');	
				$PhpExcel->writeCellValue('D1', 'Customer Mobile');
				$PhpExcel->writeCellValue('E1', 'Customer Type');
				$PhpExcel->writeCellValue('F1', 'Project State');
				$PhpExcel->writeCellValue('G1', 'Project Landmark');
				$PhpExcel->writeCellValue('H1', 'Project Latitude');
				$PhpExcel->writeCellValue('I1', 'Project Longitude');
				$PhpExcel->writeCellValue('J1', 'Project Address');
				$PhpExcel->writeCellValue('K1', 'Recommended Capacity');
				$PhpExcel->writeCellValue('L1', 'Installer Name');
				$PhpExcel->writeCellValue('M1', 'Installer Email');
				$PhpExcel->writeCellValue('N1', 'Installer Mobile');
				$PhpExcel->fillCellFont('A1:N1','000000',TRUE);
				$j=2;
				if (!empty($projectArr))
				{
					foreach ($projectArr as $row) 
					{
						$PhpExcel->writeCellValue('A'.$j, $row->id);
						$PhpExcel->writeCellValue('B'.$j, $row['c']['name']);
						$PhpExcel->writeCellValue('C'.$j, $row['c']['email']);	
						$PhpExcel->writeCellValue('D'.$j, $row['c']['mobile']);
						$PhpExcel->writeCellValue('E'.$j, $row->parameters['para_value']);
						$PhpExcel->writeCellValue('F'.$j, $row->state);
						$PhpExcel->writeCellValue('G'.$j, $row->landmark);
						$PhpExcel->writeCellValue('H'.$j, $row->latitude);
						$PhpExcel->writeCellValue('I'.$j, $row->longitude);
						$PhpExcel->writeCellValue('J'.$j, $row->address);
						$PhpExcel->writeCellValue('K'.$j, $row->recommended_capacity);
						$PhpExcel->writeCellValue('L'.$j, $row->installers['installer_name']);
						$PhpExcel->writeCellValue('M'.$j, $row->installers['email']);
						$PhpExcel->writeCellValue('N'.$j, $row->installers['mobile']);
						$j++;
					}
				}
				for($i=65;$i<=78;$i++)
				{
					if($i==66 || $i==67)
					{
						$PhpExcel->getExcelObj()->getActiveSheet()->getColumnDimension(chr($i))->setAutoSize(true);

					}
					elseif($i==68 || $i==69)
					{
						$PhpExcel->getExcelObj()->getActiveSheet()->getColumnDimension(chr($i))->setWidth(15);
					}
					elseif($i==70 )
					{
						$PhpExcel->getExcelObj()->getActiveSheet()->getColumnDimension(chr($i))->setWidth(20);
					}
					elseif($i==78)
					{
						$PhpExcel->getExcelObj()->getActiveSheet()->getColumnDimension(chr($i))->setWidth(25);
					}
					elseif($i==77)
					{
						$PhpExcel->getExcelObj()->getActiveSheet()->getColumnDimension(chr($i))->setWidth(75);
					}
					elseif($i==71 || $i==74 || $i==76)
					{
						$PhpExcel->getExcelObj()->getActiveSheet()->getColumnDimension(chr($i))->setWidth(50);
					}
					else
					{
						$PhpExcel->getExcelObj()->getActiveSheet()->getStyle(chr($i).'1')->getAlignment()->setWrapText(true);
					}
				}
				$PhpExcel->downloadFile(time());
				exit;
	}

	/**
	 *
	 * _generateProjectSurveySearchCondition
	 *
	 * @param : $id  : Id is use to identify for which project survey condition to be generated
	 *
	 * Behaviour : Private
	 *
	 * @defination : Method is use to generate search survey condition for perticular project
	 *
	 */
	private function _generateProjectSurveySearchCondition($id)
	{
		$arrCondition	= array();
		$blnSinCompany	= true;
		$this->request->data['SiteSurveys']['id'] = $id;
		//if(count($this->request->data)==0) $this->request->data['Projects']['status'] = $this->Projects->STATUS_ACTIVE;
		if(isset($this->request->data) && count($this->request->data)>0)
        {
            if(isset($this->request->data['SiteSurveys']['id']) && trim($this->request->data['SiteSurveys']['id'])!='') {
                $arrCondition['SiteSurveys.project_id'] = $this->request->data['SiteSurveys']['id'];
            }
		}
		return $arrCondition;
	}

	/**
	 *
	 * get_surveys
	 *
	 * Behaviour : public
	 *
	 * @defination : Method is use to fetch surveys from project id
	 *
	 */
	public function get_surveys($project_id = null)
	{
		$this->autoRender=false;
		$pr_id=$this->request->data['project_id'];
		$project_id=decode($pr_id);
		
		$arrAdminuserList	= array();
		$arrUserType		= array();
		$arrCondition		= array();
		$this->SortBy		= "SiteSurveys.id";
		$this->Direction	= "ASC";
		$this->intLimit		= PAGE_RECORD_LIMIT;
		$this->CurrentPage  = 1;
		$option=array();
	
		$option['colName']  = array('id','building_name','contact_name','designation','address1','address2','address3','mobile','surveyer_name', 'action');
		$sortArr=array();
		$this->SetSortingVars('SiteSurveys',$option,$sortArr);
		$arrCondition		= $this->_generateProjectSurveySearchCondition($project_id);
		$query_data=$this->SiteSurveys->find('all',array(
									'fields'=>array('SiteSurveys.id','SiteSurveys.building_name','SiteSurveys.contact_name','SiteSurveys.designation', 'SiteSurveys.address1','SiteSurveys.address2','SiteSurveys.address3','SiteSurveys.mobile', 'SiteSurveys.surveyer_name'),
									'conditions' => $arrCondition,
									'order'=>array($this->SortBy=>$this->Direction),
									'page'=> $this->CurrentPage,
									'limit' => $this->intLimit));
		$start_page=isset($this->request->data['start']) ? $this->request->data['start'] : 1;				
		$this->paginate['limit'] = PAGE_RECORD_LIMIT;
		$this->paginate['page'] = ($start_page/$this->paginate['limit'])+1;
		$arrAdminuserList	= $this->paginate($query_data);
		
		$arrStatus =array(''=>'Select','pending'=>'Pending', 'inprocess'=>'In Process', 'completed'=>'Completed','deleted'=>'Deleted');
		$usertypes = array();
		$option['dt_selector']	='table-example-survey';
		$option['formId']		='formmain_surveys';
		if(!empty($project_id)){
			$option['url']			= WEB_ADMIN_PREFIX.'projects/get_surveys/'.$pr_id;
		}else{
			$option['url']			= WEB_ADMIN_PREFIX.'projects/';
		}
		$JqdTablescr = $this->JqdTable->create($option);
		$this->set('arrAdminuserList',$arrAdminuserList->toArray());
		$this->set('JqdTablescr',$JqdTablescr);
		$this->set('arrStatus',$arrStatus);
		$this->set('period',$this->period);
		$this->set('limit',$this->intLimit);
		$this->set("CurrentPage",$this->CurrentPage);
		$this->set("SortBy",$this->SortBy);
		$this->set("Direction",$this->Direction);
		$this->set("page_count",(isset($this->request->params['paging']['ProjectSurvey']['pageCount'])?$this->request->params['paging']['ProjectSurvey']['pageCount']:0));
		$out=array();
		$counter_id=1;
		foreach($arrAdminuserList->toArray() as $key=>$val) {
			$temparr=array();
			foreach($option['colName'] as $key) {

				if(isset($val[$key])){
					$temparr[$key]=$val[$key];
				}
				if($key=='action') {
					$temparr['action']='';
					$temparr['action'].= $this->Userright->linkViewProjects(constant('WEB_URL').constant('ADMIN_PATH').'projects/viewsurveyreport/'.encode($val['id']),'<i class="fa fa-download" id=""> </i>','','target=""','title="View Serveys"')."&nbsp;";
					
						
				}		
			}
			$out[]=$temparr;
		}
		if ($this->request->is('ajax'))
		{

			header('Content-type: application/json');
			echo json_encode(array('condi'=>$arrCondition,"draw" => intval($this->request->data['draw']),
			"recordsTotal"    => intval( $this->request->params['paging']['SiteSurveys']['count']),
			"recordsFiltered" => intval( $this->request->params['paging']['SiteSurveys']['count']),
			"data"            => $out));
			die;
		}
		exit;
	}

	/**
	 *
	 * get_project_name
	 *
	 * Behaviour : public
	 *
	 * @defination : Method is use to fetch project name from project id
	 *
	 */
	public function get_project_name()
	{
		$pr_id=decode($this->request->data['project_id']);
		$arr_projets_data=$this->Projects->find('all',array('fields'=>array('Projects.name'),
										  'conditions'=>array('id'=>$pr_id)))->first();
		if(!empty($arr_projets_data))
		{
			echo $arr_projets_data['name'];
		}
		exit;
	}

	/**
	 *
	 * create_xls
	 *
	 * Behaviour : public
	 *
	 * @param : project_id  : Id is use to identify for which project of site survey .xls file download
	 *
	 * @defination : Method is use to download .xls file from modal popup of survey listing
	 *
	 */
	public function create_xls()
	{
		$this->autoRender = false;
		$pr_id=decode($this->request->data('project_id'));

		$result_data=$this->SiteSurveys->find('all',array('conditions' => array('project_id'=>$pr_id)))->toArray();
		$arr_projets_data=$this->Projects->find('all',array('fields'=>array('Projects.name'),
										  'conditions'=>array('id'=>$pr_id)))->first();
		$project_name='';
		if(!empty($arr_projets_data))
		{
			$project_name = $arr_projets_data['name'];
			$arr_installer = $this->InstallerProjects->find('all',array('conditions' => array('project_id'=>$pr_id)))->toarray();
		}
		$all_area_types     = $this->SiteSurveys->AREA_PARAMS;
		$all_area_type_smp  = $this->SiteSurveys->AREA_PARAMS_SMP;
		$all_load           = $this->SiteSurveys->LOAD_PARAMS;
		$all_meter          = $this->SiteSurveys->ALL_METER_TYPE;
		$all_meter_accuracy = $this->SiteSurveys->ALL_METER_ACCURACY_CLASS;
		$all_roof           = $this->SiteSurveys->ALL_ROOF_TYPE;
		$all_roof_st        = $this->SiteSurveys->ALL_ROOF_STRENGTH;
		$all_billing        = $this->SiteSurveys->ALL_BILLING_CYCLE;
		$this->survey_download_xls($result_data, $project_name, $all_area_types, $all_area_type_smp, $all_load, $all_meter, $all_meter_accuracy, $all_roof, $all_roof_st, $all_billing);
	}

	/**
	 *
	 * viewsurveyreport
	 *
	 * Behaviour : public
	 *
	 * @param : id  : Id is use to identify for which site PDF file should be downlaoded
	 *
	 * @defination : Method is use to download .pdf file using genratesurveyPDFreport()
	 *
	 */
	public function viewsurveyreport($id = null)
	{
		$this->layout = false;	
		if(empty($id)) {
				$this->Flash->error('Please Select Valid Project.');             
				return $this->redirect(WEB_ADMIN_PREFIX.'/project/index/');
		} else {
			$id=intval(decode($id));
		}
		$pdfPath = $this->genratesurveyPDFreport($id,true);
	}

	/**
	 *
	 * viewprojectsurveyreport
	 *
	 * Behaviour : public
	 *
	 * @param : id  : Id is use to identify for which Project all site PDF file should be downlaoded
	 *
	 * @defination : Method is use to download .pdf file using genratesurveyPDFreport()
	 *
	 */
	public function viewprojectsurveyreport($id = null)
	{
		$this->layout = false;	
		if(empty($id)) {
				$this->Flash->error('Please Select Valid Project.');             
				return $this->redirect(WEB_ADMIN_PREFIX.'/project/index/');
		} else {
			$id=intval(decode($id));
		}
		$pdfPath = $this->genratesurveyPDFreport($id,true,1);
	}
	
   	/**
    *
    * List all projects on which installer received leads
    */
    public function ahaleads($type = 'pending')
    {
        $this->set("pageTitle","AHA Leads");
        $installerId = $this->Session->read('Customers.id');
        $projects = $this->ProjectLeads->getProjectLeads($installerId,$type);
        $this->set('projectLeads',$this->paginate($projects));
        $this->set(compact("type"));
    }

    /**
    *
    * List all projects on which installer received leads
    */
    public function forward($id = null)
    {
        $projectLeadId = intval(decode($id)); 
        $this->set("pageTitle","Forword");
        $projectLead  = $this->ProjectLeads->findById($projectLeadId)->contain("Projects")->first();
        //prd($projectData);
        $installerId = $this->Session->read('Customers.id');
        $installersData = $this->Installers->find("all");
        $installers = $this->paginate($installersData);
        $this->set(compact("projectLead","installers"));
    }

    /**
	*
	* findProjectByKeyword
	*
	* Behaviour : public
	*
	* @defination : Method is used to get project detail by keyword for logged in customer.
	*
	*/
	public function findProjectByKeyword() 
	{
		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		$keyword 			= isset($this->request->data['keyword'])?$this->request->data['keyword']:"";
		$customer_id 		= $this->ApiToken->customer_id;
		$projectData 		= array();
		if(!empty($keyword)) {
			$arrCondition 		= array("c.id"=>intval($customer_id),
										"Projects.name LIKE "=>'%'.($keyword).'%',
										"ApplyOnline.project_id IS"=> NULL);
			$fields 			= array('Projects.id','Projects.name','c.name','Projects.city','Projects.state','Projects.address',
										'Projects.country','Projects.recommended_capacity','Projects.status','Projects.created',
										'Projects.landmark','Projects.capacity_kw','Projects.maximum_capacity');
			$getProjects 		= $this->Projects->find('all',array('fields'=>$fields,
														'join'=>[
																	'i' => [
																		'table' => 'customer_projects',
																		'type' => 'INNER',
																		'conditions' => ['i.project_id = Projects.id']
																	],
																	'c' => [
																		'table' => 'customers',
																		'type' => 'INNER',
																		'conditions' => ['c.id = i.customer_id']
																	],
													            	'ApplyOnline' => [
															            'table' => 'apply_onlines',
															            'type' => 'LEFT',
															            'conditions' => ['ApplyOnline.project_id = Projects.id']
													            	],
                                                                    'ins' => [
                                                                        'table' => 'installer_projects',
                                                                        'type' => 'INNER',
                                                                        'conditions' => ['ins.project_id = Projects.id']
                                                                    ]
																],
														'conditions' => $arrCondition,
														'group' => array('Projects.id'),
														'limit' => PAGE_RECORD_LIMIT));
            if(!empty($getProjects)) {
				$arrProjects = $getProjects->toArray();
				foreach ($arrProjects as $key=>$arrProject) {
					$projectData[$key]['id']					= $arrProject['id'];
					$projectData[$key]['name'] 					= $arrProject['name'];
					$projectData[$key]['landmark'] 				= $arrProject['landmark'];
					$projectData[$key]['address'] 				= $arrProject['address'];
					$projectData[$key]['city'] 					= $arrProject['city'];
					$projectData[$key]['state']					= $arrProject['state'];
					$projectData[$key]['country']				= $arrProject['country'];
					$projectData[$key]['capacity_kw'] 			= $arrProject['capacity_kw'];
					$projectData[$key]['recommended_capacity'] 	= $arrProject['recommended_capacity'];
					$projectData[$key]['maximum_capacity'] 		= $arrProject['maximum_capacity'];
					$projectData[$key]['customer'] 				= $arrProject['c']['name'];
					$projectData[$key]['proj_time'] 			= date("h:i a", strtotime($arrProject['created']));
					$projectData[$key]['proj_date'] 			= date("d/m/y", strtotime($arrProject['created']));
				}
            }
			$this->ApiToken->SetAPIResponse('type', 'ok');
			$this->ApiToken->SetAPIResponse('result', $projectData);
		} else {
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'Keyword is required.');
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}


    /**
     *
     * projectAssignBD
     *
     * Behaviour : Public
     *
     * @defination : Mobile Api, Assign Project to Buisness Developer Customer
     *
     * Create by : sachin patel
     */

    public function projectAssignBd(){
        $error	= '';
        $this->autoRender 	= false;
        $customer_id = $this->ApiToken->customer_id;

        if(isset($customer_id) && $customer_id !=""){
            if(isset($this->request->data['projects_id']) && $this->request->data['projects_id'] !=""){
                $this->ProjectAssignBd->deleteAll(['ProjectAssignBd.projects_id'=>$this->request->data['projects_id']]);
                if(isset($this->request->data['assign_customer_ids']) && $this->request->data['assign_customer_ids'] !=""){
                    $customersArray = explode(",",$this->request->data['assign_customer_ids']);
                    if(!empty($customersArray)) {
                        foreach ($customersArray as $customerId) {
                            $this->request->data['customers_id'] = $customerId;
                            $this->request->data['created_by'] = $customer_id;
                            $assign = $this->ProjectAssignBd->newEntity($this->request->data);
                            $assign->created = $this->NOW();
                            if ($this->ProjectAssignBd->save($assign)) {
                                $this->ApiToken->SetAPIResponse('type', 'ok');
                                $this->ApiToken->SetAPIResponse('msg', 'Assign Successfully');
                            }
                        }
                    }
                }
                else
                {
                	$this->ApiToken->SetAPIResponse('type', 'ok');
                    $this->ApiToken->SetAPIResponse('msg', 'Assign removed Successfully');
                }
            }else {
                $error = 'project_id not found';
            }
        }else {
            $error = "Customer Not Found";
        }
        if($error!=""){
            $this->ApiToken->SetAPIResponse('type', 'eroor');
            $this->ApiToken->SetAPIResponse('result', $error);
        }
        echo $this->ApiToken->GenerateAPIResponse();
        exit;
    }

    /**
     *
     * removeProjectAssignBd
     *
     * Behaviour : Public
     *
     * @defination : Mobile Api, Remove Assigned Project of Buisness Developer Customer
     *
     * Create by : sachin patel
     */

    public function removeProjectAssignBd(){
        $error	= '';
        $this->autoRender 	= false;
        $customer_id = $this->ApiToken->customer_id;

        if(isset($customer_id) && $customer_id !=""){
            if(isset($this->request->data['projects_id']) && $this->request->data['projects_id'] !=""){
                if(isset($this->request->data['remove_customer_ids']) && $this->request->data['remove_customer_ids'] !=""){
                    $customersArray = explode(",",$this->request->data['remove_customer_ids']);
                    if(!empty($customersArray)) {
                        foreach ($customersArray as $customerId) {
                            if($customerId !="") {
                                $this->ProjectAssignBd->deleteAll(['ProjectAssignBd.projects_id' => $this->request->data['projects_id'], 'ProjectAssignBd.customers_id' => $customerId]);
                            }
                        }
                        $this->ApiToken->SetAPIResponse('type', 'ok');
                        $this->ApiToken->SetAPIResponse('msg', 'Remove Successfully');
                    }
                }
            }else {
                $error = 'project_id not found';
            }
        }else {
            $error = "Customer Not Found";
        }
        if($error!=""){
            $this->ApiToken->SetAPIResponse('type', 'eroor');
            $this->ApiToken->SetAPIResponse('result', $error);
        }
        echo $this->ApiToken->GenerateAPIResponse();
        exit;
    }
}
