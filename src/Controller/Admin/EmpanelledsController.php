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
use Dompdf\Dompdf;

class EmpanelledsController extends AppController
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
		$this->loadModel('Empanelled');
		$this->loadModel('Emaillog');

		$this->set('Userright',$this->Userright);
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
		
		$arrAdminuserList	= array();
		$arrUserType		= array();
		$arrCondition		= array();
		$this->SortBy		= "Empanelled.id";
		$this->Direction	= "ASC";
		$this->intLimit		= 10;
		$this->CurrentPage  = 1;  /*((isset($this->request->data['start']) ? $this->request->data['start'] : '10')/$this->intLimit)+1*/
		$option=array();
		$option['colName']  = array('id','agency','level','image_path','status','created','action');
		//$sortArr=array('customername'=>'c.name');
		$this->SetSortingVars('Empanelled',$option);
		$arrStatus = array("1"=>"Active","0"=>"Inactive");
		$arrEmpLevel = array("1"=>"State","0"=>"Country","2"=>"City");

		$arrCondition		= $this->_generateProjectSearchCondition();
		
		$this->paginate		= array('fields'=>array('Empanelled.id','Empanelled.agency','Empanelled.level','Empanelled.image_path','Empanelled.status','Empanelled.created'),'conditions' => $arrCondition,
		'order'=>array($this->SortBy=>$this->Direction),
		'page'=> $this->CurrentPage,
		'limit' => $this->intLimit);
		$arrAdminuserList	= $this->paginate('Empanelled');
		//pr($arrAdminuserList);exit;
		
		//$arrStatus =array(''=>'Select','pending'=>'Pending', 'inprocess'=>'In Process', 'completed'=>'Completed','deleted'=>'Deleted');
		
		//$usertypes = $this->Userrole->getAdminuserRoles();
		$usertypes = array();
		//foreach($usertypes as $key=>$value) $arrUserType[$key] = $value;
		$option['order_by'] = "'order': [[ 0, 'desc' ]]";

		$option['dt_selector']	='table-example';
		$option['formId']		='formmain';
		$option['url']			= WEB_ADMIN_PREFIX.'empanelleds/';
		
		$JqdTablescr = $this->JqdTable->create($option);
		$this->set('arrAdminuserList',$arrAdminuserList->toArray());
		$this->set('JqdTablescr',$JqdTablescr);
		$this->set('arrStatus',$arrStatus);
		$this->set('arrEmpLevel',$arrEmpLevel);
		$this->set('period',$this->period);
		$this->set('limit',$this->intLimit);
		$this->set("CurrentPage",$this->CurrentPage);
		$this->set("SortBy",$this->SortBy);
		$this->set("Direction",$this->Direction);
		$this->set("page_count",(isset($this->request->params['paging']['Empanelled']['pageCount'])?$this->request->params['paging']['Empanelled']['pageCount']:0));
		$out=array();
		
		foreach($arrAdminuserList->toArray() as $key=>$val) {
			$temparr=array();
			foreach($option['colName'] as $key) {
				
				if($key=='level') {
					$temparr[$key]= $arrEmpLevel[$val[$key]];
				} else if($key=='status') {
					$temparr[$key] = isset($arrStatus[$val[$key]])?$arrStatus[$val[$key]]:$val[$key];
				} else if($key=='image_path') {
					$temparr[$key] = '<img class="img-rounded" style="border: 2px solid;" width="60px" height="30px" src="'.EMPANELLED_IMG_URL.$val['id'].'/'. $val[$key].'">';
				} else if($key=='created') {
					$temparr[$key]= date('Y-m-d H:i:s',strtotime($val[$key]));
				} else if($key=='action') {
					$temparr['action'] = '';
					$temparr['action'].= $this->Userright->linkViewProjects(constant('WEB_URL').constant('ADMIN_PATH').'empanelleds/manage/'.encode($val['id']),'<i class="fa fa-edit"> </i>','','rel="viewRecord"','title="Manage Empanelled"')."&nbsp;";
				} else if(isset($val[$key])){
					$temparr[$key]=$val[$key];
				}		
			}
			$out[]=$temparr;
		}
		if ($this->request->is('ajax')){
			header('Content-type: application/json');
			echo json_encode(array('condi'=>$arrCondition,"draw" => intval($this->request->data['draw']),
			"recordsTotal"    => intval( $this->request->params['paging']['Empanelled']['count']),
			"recordsFiltered" => intval( $this->request->params['paging']['Empanelled']['count']),
			"data"            => $out));
			die;
		}
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
		if(!empty($id)) $this->request->data['Empanelled']['id'] = $id;
		//if(count($this->request->data)==0) $this->request->data['Empanelled']['status'] = $this->Empanelled->STATUS_ACTIVE;
		if(isset($this->request->data) && count($this->request->data)>0)
        {
            if(isset($this->request->data['Empanelled']['id']) && trim($this->request->data['Empanelled']['id'])!='') {
                $strID = trim($this->request->data['Empanelled']['id'],',');
                $arrCondition['Empanelled.id'] = $this->request->data['Empanelled']['id'];
            }
			
			if(isset($this->request->data['Empanelled']['name']) && $this->request->data['Empanelled']['name']!='') {
                $arrCondition['Empanelled.name LIKE'] = '%'.$this->request->data['Empanelled']['name'].'%';
            }
			if(isset($this->request->data['Empanelled']['city']) && $this->request->data['Empanelled']['city']!='') {
                $arrCondition['Empanelled.city LIKE'] = '%'.$this->request->data['Empanelled']['city'].'%';
            }
			if(isset($this->request->data['Empanelled']['status']) && $this->request->data['Empanelled']['status']!='') {
                $arrCondition['Empanelled.status'] = $this->request->data['Empanelled']['status'];
            }


			if(isset($this->request->data['Empanelled']['search_date']) && $this->request->data['Empanelled']['search_date']!='') {
            	$arrDate = array('Empanelled.created');
				if(in_array($this->request->data['Empanelled']['search_date'], $arrDate)) {
					if($this->request->data['Empanelled']['search_period'] == 1 || $this->request->data['Empanelled']['search_period'] == 2) {
						$arrSearchPara	= $this->Empanelled->setSearchDateParameter($this->request->data['Empanelled']['search_period'],'Empanelled');
						$this->request->data['Empanelled'] = array_merge($this->request->data['Empanelled'],$arrSearchPara['Empanelled']);
						$this->dateDisabled	= true;
					}
					$arrperiodcondi = $this->Empanelled->findConditionByPeriod($this->request->data['Empanelled']['search_date'],
																				$this->request->data['Empanelled']['search_period'],
																				$this->request->data['Empanelled']['DateFrom'],
																				$this->request->data['Empanelled']['DateTo'],
																				$this->Session->read('User.timezone'));
					$arrCondition = array_merge($arrCondition,$arrperiodcondi);
				}
			}
		}
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
			$customersEntity 			= $this->Empanelled->newEntity($this->request->data(),['validate'=>'add']);
		} else {
			$id=intval(decode($id));
			$this->intCurAdminUserRight = $this->Userright->EDIT_PROJECT;
			$customerData 				= $this->Empanelled->get($id);
			$customersEntity 			= $this->Empanelled->patchEntity($customerData,$this->request->data(),['validate'=>'edit']);
				
			//$industrieEntity 			= $this->Parameter->patchEntity($this->request->data(),['validate'=>'editmanageindustrie']);
		}

		$this->setAdminArea();
		$arrAdminDefaultRights	= array();
		$arrAdminTransaction	= array();
		$arrError 				= array();
		$arrStatus = array('1'=>"Active","0"=>"Inactive");
		$arrEmpLevel = array('1'=>"State","0"=>"Country","2"=>"City");
		
		if (!empty($this->request->data))
		{
			if (empty($id)) {
				$customersEntity->created 		= $this->NOW();
				$customersEntity->created_by 	= $this->Session->read('User.id');
			} else {
				$customersEntity->updated 		= $this->NOW();
				$customersEntity->updated_by 	= $this->Session->read('User.id');
			}
			if ($this->Empanelled->save($customersEntity))
			{

				if(isset($this->request->data['Empanelled']['image_path_logo']['name']) && !empty($this->request->data['Empanelled']['image_path_logo']['name']))
				{

					$image_path = EMPANELLED_IMG_PATH.$customersEntity->id.'/';
					if(!file_exists($image_path))
						mkdir($image_path, 0755,true);
					$file_name = $this->file_upload($image_path,$this->request->data['Empanelled']['image_path_logo'],false,65,65,$image_path);
					$this->Empanelled->updateAll(['image_path' => $file_name], ['id' => $customersEntity->id]);
				}
				if(empty($id)){
					$this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->ADD_PARAMETER_TYPE,$customersEntity->para_id,'Added Parameter id::'.$customersEntity->para_id);
					$this->Flash->set('Empanelled added successfully.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
                   	return $this->redirect('admin/empanelleds');
				} else {
					$this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->EDIT_PARAMETER,$customersEntity->para_id,'Updated Parameter id::'.$customersEntity->para_id);
                    $this->Flash->set('Empanelled updated successfully.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
                   	return $this->redirect('admin/empanelleds');
				}
			}
			//}
		} else if(!empty($id)){
			
			$customersEntity 		= $this->Empanelled->get($id);
			$customersEntity 		= $this->Empanelled->patchEntity($customersEntity,$this->request->data());

			if(!isset($customersEntity->id) || empty($customersEntity->id)) {
                $this->Flash->set('Invalid Customer Id.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'error']]);
			}
		}
		
			
		$this->set('Empanelled',$customersEntity);
		$this->set('arrStatus',$arrStatus);
		$this->set('arrEmpLevel',$arrEmpLevel);
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
		$energy_con 	= $this->request->data['energy_con'];
		$area_type 		= $this->request->data['area_type'];
		$solarPenalArea = 0;
		$this->autoRender 	= false;		
		$this->SetVariables($this->request->data);
		
		/* Create or update project data here. */
		if(!empty($this->request->data['Projects']['id'])) {
			$projectsData 	= $this->Projects->get($this->request->data['Projects']['id']);
			$this->request->data['Projects']['address']					= (!empty($projectsData['address'])?$projectsData['address']:'');
			$this->request->data['Projects']['city']					= (!empty($projectsData['city'])?$projectsData['city']:'');
			$this->request->data['Projects']['state']					= (!empty($projectsData['state'])?$projectsData['state']:'');
			$this->request->data['Projects']['state_short_name']		= (!empty($projectsData['state_short_name'])?$projectsData['state_short_name']:'');
			$this->request->data['Projects']['country']					= (!empty($projectsData['country'])?$projectsData['country']:'');
			$this->request->data['Projects']['pincode']					= (!empty($projectsData['pincode'])?$projectsData['pincode']:'');
			$this->request->data['Projects']['landmark']				= (!empty($projectsData['landmark'])?$projectsData['landmark']:'');
		} else {
			$locationdata = GetLocationByLatLong($this->request->data['Projects']['latitude'],$this->request->data['Projects']['longitude']);	
			$this->request->data['Projects']['address']					= (isset($locationdata['address'])?$locationdata['address']:'');
			$this->request->data['Projects']['city']					= (isset($locationdata['city'])?$locationdata['city']:'');
			$this->request->data['Projects']['state']					= (isset($locationdata['state'])?$locationdata['state']:'');
			$this->request->data['Projects']['state_short_name']		= (isset($locationdata['state_short_name'])?$locationdata['state_short_name']:'');
			$this->request->data['Projects']['country']					= (isset($locationdata['country'])?$locationdata['country']:'');
			$this->request->data['Projects']['pincode']					= (isset($locationdata['postal_code'])?$locationdata['postal_code']:'');
			$this->request->data['Projects']['landmark']				= (isset($locationdata['landmark'])?$locationdata['landmark']:'');
		}
		
		if($area_type == $this->Projects->AREA_TYPE_FOOT) {
			$solarPenalArea	= $this->calculatePvInFoot($this->request->data['Projects']['area']);
		} elseif($area_type == $this->Projects->AREA_TYPE_METER) { 
			$solarPenalArea	= $this->calculatePvInMeter($this->request->data['Projects']['area']);	
		}
		
		$solarPvInstall 	= ceil($solarPenalArea/12);
		$solarRediationData	= $this->getSolarRediation($this->request->data['Projects']['latitude'],$this->request->data['Projects']['longitude']);
		$annualTotalRad		= ($solarRediationData['ann_glo']*365);
		/*$averageEnrgyGenInDay 	= (((($solarPvInstall*$solarRediationData['ann_glo'])*PERFORMANCE_RATIO)/100)*1.1);*/
		
		$contractLoad 			= round(((($this->request->data['Projects']['estimated_kwh_year']*12)/((24*365*LOAD_FECTORE)/100))));
		$capacityAcualEnrgyCon	= round(((($this->request->data['Projects']['estimated_kwh_year']*12)/$annualTotalRad)));
		
		$recommendedSolarPvInstall = min($solarPvInstall, $contractLoad, $capacityAcualEnrgyCon);	
		$averageEnrgyGenInDay 	= (((($recommendedSolarPvInstall*$solarRediationData['ann_glo'])*PERFORMANCE_RATIO)/100)*1.1);
		$monthChartDataArr		= $this->calculateMonthChartData($solarRediationData,$recommendedSolarPvInstall);
		
		$capitalCost 			= $this->calculatecapitalcost($recommendedSolarPvInstall,$this->request->data['Projects']['state'],$this->request->data['Projects']['customer_type']);
		$capitalCostsubsidy 	= $this->calculatecapitalcostwithsubsidy($recommendedSolarPvInstall,$capitalCost,$this->request->data['Projects']['state'],$this->request->data['Projects']['customer_type']);
		
		$highRecommendedSolarPvInstall 	= max($solarPvInstall, $contractLoad, $capacityAcualEnrgyCon);	
		$averageEnrgyGenInYear	= round(((($recommendedSolarPvInstall*$annualTotalRad)*PERFORMANCE_RATIO)/100)*1.1);
		
		/* Calculate saving */
		$montly_pv_generation 	= ($averageEnrgyGenInDay * 30);
		$monthly_saving 		= ($bill - ($energy_con - $montly_pv_generation) * (($bill/$energy_con)-0.5)); 
		
		/* Calculate saving */
		$cost_solar				= 0.0;	
		$unitRate				= (($this->request->data['Projects']['avg_monthly_bill']/$this->request->data['Projects']['estimated_kwh_year'])-0.5);
		$solarChart 			= $this->getBillingChart($contractLoad,$unitRate,$averageEnrgyGenInYear,$capitalCostsubsidy,$this->request->data['Projects']['backup_type'],$this->request->data['Projects']['usage_hours']);
		
		$payBack 				= (isset($solarChart['breakEvenPeriod'])?$solarChart['breakEvenPeriod']:0);
		$fromPvSystem 			= (isset($solarChart['fromPvSystem'])?$solarChart['fromPvSystem']:array());
		$gross_solar_cost		= $this->getTarifCalculation(25,$fromPvSystem[1]['yearlyEnergyGenerated'],$this->request->data['Projects']['avg_monthly_bill'],$capitalCost);	
		$cost_solar				= $gross_solar_cost['net_cog'];
		$chart					= $this->genrateApiChartData($fromPvSystem, $monthChartDataArr);	
		$averageEnrgyGenInMonth	= ($averageEnrgyGenInYear/12);
		$solar_ratio			= (($energy_con > 0)?(($averageEnrgyGenInMonth/$energy_con) * 100):0);
		
		$this->request->data['Projects']['contract_load']			= (isset($contractLoad)?$contractLoad:0);
		$this->request->data['Projects']['cost_solar']				= (isset($cost_solar)?$cost_solar:0);
		$this->request->data['Projects']['solar_ratio']				= (isset($solar_ratio)?$solar_ratio:0);
		$this->request->data['Projects']['estimated_saving_month']	= (isset($monthly_saving)?$monthly_saving:0);
		$this->request->data['Projects']['payback']					= (isset($payBack)?$payBack:0);
		$this->request->data['Projects']['estimated_cost']			= (isset($capitalCost)?$capitalCost:0);
		$this->request->data['Projects']['estimated_cost_subsidy']	= (isset($capitalCostsubsidy)?$capitalCostsubsidy:0);
		$this->request->data['Projects']['avg_generate']			= (isset($averageEnrgyGenInMonth)?$averageEnrgyGenInMonth:0);
		$this->request->data['Projects']['recommended_capacity']	= (isset($recommendedSolarPvInstall)?$recommendedSolarPvInstall:0);
		$this->request->data['Projects']['maximum_capacity']		= (isset($highRecommendedSolarPvInstall)?$highRecommendedSolarPvInstall:0);
		
		$rec_capacity = 0;
		$rec_capacity = ($recommendedSolarPvInstall > 10)?floor($recommendedSolarPvInstall):number_format($recommendedSolarPvInstall,1);
		if(!empty($this->request->data['Projects']['id'])) {
			$projectsData 	= $this->Projects->get($this->request->data['Projects']['id']);
			$projectsEntity = $this->Projects->patchEntity($projectsData,$this->request->data());
			$projectsEntity->modified 		= $this->NOW();	
			$projectsEntity->modified_by 	= $this->ApiToken->customer_id;
		}else{
			$projectsEntity = $this->Projects->newEntity($this->request->data['Projects']);
			$projectsEntity->created 		= $this->NOW();
			$projectsEntity->created_by 	= $this->ApiToken->customer_id;
		}	
		
		
		$dataProject = array();
		if(round($rec_capacity)==0)
		{
			$status				= 'error';
			$error				= 'Please enter valid estimation details like Rooftop Area, Monthly Bill and Unit Consumed.';
			$this->ApiToken->SetAPIResponse('msg', $error);		
		}else if ($this->Projects->save($projectsEntity)) {
			$dataProject['CustomerProjects']['customer_id']	= $this->ApiToken->customer_id;
			$dataProject['CustomerProjects']['project_id']	= $projectsEntity->id;

			if(empty($this->request->data['Projects']['id'])) {
				$customerProjectsEntity = $this->CustomerProjects->newEntity($dataProject);
				$this->CustomerProjects->save($customerProjectsEntity);	
			}
			
			$status		= 'ok';
			$messege	= array(); 
			$messege 	= array_merge($messege,$chart);
			$messege['proj_id'] 		= $projectsEntity->id;
			$messege['is_residential'] 	= ($projectsEntity->customer_type == '3001')?'1':'0';
			$messege['cost_solar']		= $cost_solar;
			$messege['saving_month']	= _FormatGroupNumberV2($monthly_saving);
			$messege['capacity']		= $rec_capacity;
			$messege['highcapacity']	= round($highRecommendedSolarPvInstall);
			$messege['est_cost']		= $capitalCost;
			$messege['est_cost_subsidy']	= round($capitalCostsubsidy,2);
			$messege['avg_gen']			= round($averageEnrgyGenInMonth,2);
			$messege['payback']			= round($payBack,2);
			$messege['solar_ratio'] 	= ($solar_ratio > 100)?'100':round($solar_ratio);
				$this->ApiToken->SetAPIResponse('result', $messege);	
		} else {
			$status				= 'error';
			$error				= 'Please try after some time';
			$this->ApiToken->SetAPIResponse('msg', $error);
		}
		$this->ApiToken->SetAPIResponse('type', $status);
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
			} else if($spvi <= 100) {
				$cost	=	($spvi*COST_FOR_10_TO_100_KW);
			} else {
				$cost	=	($spvi*COST_ABOVE_100_KW);
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
		if(empty($recommendedSolarPvInstall) || empty($capitalCost))
			return false;
		$costwithsubsidy = 0;
		if($recommendedSolarPvInstall < 500){ 
			$subsidy 			= ($capitalCost * ((SUBSIDY_PERC/100)));
		if(strtolower($state) == 'gujarat' && $customertype == '3001'){ 
			if($recommendedSolarPvInstall <= 2 ){
				$costwithsubsidy 	= ($capitalCost - ($subsidy + (10 * ($recommendedSolarPvInstall/100))));
			}
			else
			{
				$costwithsubsidy 	= ($capitalCost - ($subsidy + (10 * (2/100))));
			}
				
		}else	
			$costwithsubsidy 	= ($capitalCost - ($subsidy + OTHER_SUBSIDY));		
		} else {
			$costwithsubsidy = 0;
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
	public function getprojectlist() {

		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		$projectData 	= array();
		$customer_id 	= $this->ApiToken->customer_id;

		$arrCondition 	= array('c.customer_id'=>$customer_id, 'Projects.status'=>'pending');
		if(isset($this->request->data['proj_name']) && $this->request->data['proj_name']!='') {
            $arrCondition['Projects.name LIKE'] = '%'.$this->request->data['proj_name'].'%';
        }
      	$projectArr 	= $this->Projects->find('all',['join'=>[
					        'c' => [
					            'table' => 'customer_projects',
					            'type' => 'INNER',
					            'conditions' => ['c.project_id = Projects.id']
			            	],
			            	'parameters' => [
					            'table' => 'parameters',
					            'type' => 'LEFT',
					            'conditions' => ['parameters.para_id = Projects.customer_type']
			            	]],
			            	'fields' => array('Projects.id','Projects.name','Projects.address','Projects.city','Projects.state','Projects.country','Projects.created','Projects.customer_type','parameters.para_value'),
			            	'conditions' => $arrCondition,
			            	'order' => array('Projects.id' => 'DESC')])->toArray();
		
		$projectData = array();
		if(!empty($projectArr)) {
			foreach($projectArr as $key=>$value) {
				$projectData[$key]['id']			= $projectArr[$key]['id'];
				$projectData[$key]['name'] 			= $projectArr[$key]['name'];
				$projectData[$key]['address'] 		= $projectArr[$key]['address'];
				$projectData[$key]['city'] 			= $projectArr[$key]['city'];
				$projectData[$key]['state']			= $projectArr[$key]['state'];
				$projectData[$key]['country']		= $projectArr[$key]['country'];
				$projectData[$key]['cus_type'] 		= $projectArr[$key]['parameters']['para_value'];
				$projectData[$key]['proj_time'] 	= date("h:i a", strtotime($projectArr[$key]['created']));
				$projectData[$key]['proj_date'] 	= date("d/m/y", strtotime($projectArr[$key]['created']));
			}	
		}		
		$resultData = $projectData;
		$this->ApiToken->SetAPIResponse('type', 'ok');
		$this->ApiToken->SetAPIResponse('result', $resultData);
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
	public function getprojectdetail() {

		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		$project_id 		= $this->request->data['proj_id'];
		$projectData 		= array(); 
		
		if(!empty($project_id)) {
			$projectData 	= $this->Projects->find('all', array('conditions'=>array('id'=>$project_id), 'fields'=>array('id','name','address','city','state','country','created')))->toArray();
			$arrReturn 	= (!empty($projectData[0]))?$projectData[0]:$projectData; 
			$this->ApiToken->SetAPIResponse('type', 'ok');
			$this->ApiToken->SetAPIResponse('result', $arrReturn);
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
	public function getfinancialincentives() {

		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		$incentiveData	= array();
		$arrCondition	= array();

		if(isset($this->request->data['state']) && $this->request->data['state']!='') {
           $arrCondition['state like'] = '%'.$this->request->data['state'].'%';
        }
		/*
		if(isset($this->request->data['city']) && $this->request->data['city']!='') { 
            $arrCondition['city like'] = '%'.$this->request->data['city'].'%';
        } */
        $incentiveData  = $this->FinancialIncentives->find('all',array("conditions"=>$arrCondition))->first();
		$result ="<p><strong>State</strong></p>
						<p>".$incentiveData['state']."</p>
					<p><strong>Settlement Period</strong></p>
						<p>".$incentiveData['settlement_period']."</p>
					<p><strong>Minimum Capacity</strong></p>
						<p>".$incentiveData['minimum_capacity']."</p>
					<p><strong>Max. Contract Load</strong></p>
						<p>".$incentiveData['max_contract_load']."</p>
					<p><strong>Incentive</strong></p>
						<p>".$incentiveData['incentive']."</p>";

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
			$projectData 	= $this->Projects->get($project_id);
			
			$this->request->data['Projects']['latitude']		= (isset($projectData['latitude'])?$projectData['latitude']:0);
			$this->request->data['Projects']['longitude']		= (isset($projectData['longitude'])?$projectData['longitude']:0);
			$this->request->data['Projects']['customer_type']	= (isset($projectData['customer_type'])?$projectData['customer_type']:0);

			$this->request->data['Projects']['area']	= (isset($projectData['area'])?$projectData['area']:0);
			$this->request->data['area_type'] 			= (isset($projectData['area_type'])?$projectData['area_type']:0);
			$this->request->data['bill'] 				= (isset($projectData['avg_monthly_bill'])?$projectData['avg_monthly_bill']:0);
			$this->request->data['backup_type']			= (isset($projectData['backup_type'])?$projectData['backup_type']:0);
			$this->request->data['usage_hours']			= (isset($projectData['usage_hours'])?$projectData['usage_hours']:0);
			$this->request->data['Projects']['usage_hours']			= (isset($projectData['usage_hours'])?$projectData['usage_hours']:0);
			$this->request->data['energy_con']			= (isset($projectData['estimated_kwh_year'])?$projectData['estimated_kwh_year']:0);
			$this->request->data['Projects']['estimated_kwh_year'] = (isset($projectData['estimated_kwh_year'])?$projectData['estimated_kwh_year']:0);
			$result = $this->getprojectestimation();
		} else {
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'Invalid request data.');
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
		$this->autoRender = false;		
		$this->SetVariables($this->request->data);
		$project_id 			= $this->request->data['Projects']['id'];
		$solarPvInstall			= $this->request->data['Projects']['user_capacity'];
		$projectData 			= $this->Projects->get($project_id);
		
		$capitalCost			= $this->calculatecapitalcost($solarPvInstall,$projectData['state'],$projectData['customer_type']);
		$solarRediationData		= $this->getSolarRediation($projectData['latitude'],$projectData['longitude']);
		
		$annualTotalRad			= ($solarRediationData['ann_glo']*365);
		/*$averageEnrgyGenInDay 	= (((($solarPvInstall*$solarRediationData['ann_glo'])*PERFORMANCE_RATIO)/100)*1.1);*/
		/*$monthChartDataArr		= $this->calculateMonthChartData($solarRediationData,$solarPvInstall);*/

		$capacityAcualEnrgyCon	= ((($projectData['estimated_kwh_year']*12)/$annualTotalRad));
		$contractLoad			= ((($projectData['estimated_kwh_year']*12)/((24*365*LOAD_FECTORE)/100)));
		$recommendedSolarPvInstall 		= min($solarPvInstall, $contractLoad, $capacityAcualEnrgyCon);	
		$highRecommendedSolarPvInstall 	= max($solarPvInstall, $contractLoad, $capacityAcualEnrgyCon);	
		$averageEnrgyGenInYear	= round(((($solarPvInstall*$annualTotalRad)*PERFORMANCE_RATIO)/100)*1.1);
		
		$capitalCostsubsidy		= $this->calculatecapitalcostwithsubsidy($recommendedSolarPvInstall,$capitalCost,$projectData['state'],$projectData['customer_type']);
		$averageEnrgyGenInDay 	= (((($recommendedSolarPvInstall*$solarRediationData['ann_glo'])*PERFORMANCE_RATIO)/100)*1.1);
		$monthChartDataArr		= $this->calculateMonthChartData($solarRediationData,$recommendedSolarPvInstall);
		/* Calculate saving */
		$bill 					= $projectData['avg_monthly_bill'];
		$energy_con 			= $projectData['estimated_kwh_year'];

		$montly_pv_generation 	= ($averageEnrgyGenInDay * 30);
		$monthly_saving 		= ($bill - ($energy_con - $montly_pv_generation) * (($bill/$energy_con)-0.5)); 
		/* Calculate saving */
		$cost_solar				= 0.0;
		$unitRate				= (($projectData['avg_monthly_bill']/$projectData['estimated_kwh_year'])-0.5);
		$solarChart 			= $this->getBillingChart($contractLoad,$unitRate,$averageEnrgyGenInYear,$capitalCostsubsidy,$projectData['backup_type'],$projectData['usage_hours']);
		
		$payBack 				= (isset($solarChart['breakEvenPeriod'])?$solarChart['breakEvenPeriod']:0);
		$fromPvSystem 			= (isset($solarChart['fromPvSystem'])?$solarChart['fromPvSystem']:array());
		$gross_solar_cost		= $this->getTarifCalculation(25,$fromPvSystem[1]['yearlyEnergyGenerated'],$projectData['avg_monthly_bill'],$capitalCost);
		$cost_solar				= $gross_solar_cost['net_cog'];
		$chart					= $this->genrateApiChartData($fromPvSystem,$monthChartDataArr);
		$averageEnrgyGenInMonth	= ($averageEnrgyGenInYear/12);
		$solar_ratio			= (($energy_con > 0)?(($averageEnrgyGenInMonth/$energy_con) * 100):0);
			
		
		$projectData['contract_load']			= $contractLoad;
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
			$messege['saving_month']	= _FormatGroupNumberV2($monthly_saving);
			$messege['capacity']		= $recommendedSolarPvInstall;
			$messege['highcapacity']	= round($highRecommendedSolarPvInstall);
			$messege['est_cost']		= $capitalCost;
			$messege['est_cost_subsidy']	= round($capitalCostsubsidy,2);
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
				$chartDataArr[$i] = ((((($solarPvInstall*$monthGloVal)*PERFORMANCE_RATIO)/100)*1.1)*cal_days_in_month(CAL_GREGORIAN, $i, date("Y")));
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
		$requestData =array();
		$requestData['energy_con'] = $customerData->estimated_kwh_year;
		$requestData['area_type'] = $customerData->area_type;
		$requestData['area'] = $customerData->area;
		$requestData['latitude'] = $customerData->latitude;
		$requestData['longitude'] = $customerData->longitude;
		$requestData['avg_monthly_bill'] = $customerData->avg_monthly_bill;
		$requestData['backup_type'] = $customerData->backup_type;
		$requestData['usage_hours'] = $customerData->usage_hours;
		$requestData['project_type'] = $customerData->project_type;
		$areaTypeArr = $this->Parameters->getAreaType();
		$custTypeArr = $this->Parameters->getProjectType();
		$resultArr = $this->Projects->getprojectestimation($requestData);
		$customerArr = $this->Customers->getCustomerByProjectid($id);
		$projectInstallers =$this->InstallerProjects->getProjectwiseInstallerList($id);
		/* Energy and Month Saving Data */
		$solarRediationData = $this->GhiData->getGhiData($customerData->longitude,$customerData->latitude);
		$energyAndSavingDataArr = $this->Projects->getMonthEnergyAndSavingData($solarRediationData,$customerData->recommended_capacity,$customerData->avg_monthly_bill,$customerData->estimated_kwh_year);
		
		/* Solar PV Chart Data */
		$monthSavinData 	= (!empty($energyAndSavingDataArr['saving_data'])?$energyAndSavingDataArr['saving_data']:array());
		$monthly_saving 	= array_sum($monthSavinData);
		$estimated_cost_subsidy = isset($customerData->estimated_cost_subsidy)?round(($customerData->estimated_cost_subsidy/100000),2):$customerData->estimated_cost;
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
	private function genratePDFreport($id,$project,$isdownload=false,$isInstallerhide=0)
	{
		$this->layout = false;
		/* Get all project installers */
		$projectInstallers 	= array();
		if(empty($isInstallerhide))
		{
			$projectInstallers =$this->InstallerProjects->getProjectwiseInstallerList($id);
		}	

		/* Generate map URL based on project location */
		$latLng = $project->latitude.",".$project->longitude;
		$mapUrl = 'https://maps.googleapis.com/maps/api/staticmap?center='.$latLng.'&maptype=hybrid&zoom=10&size=272x378&markers=color:blue%7C'.$latLng.'&sensor=false';
		$mapImage = file_get_contents($mapUrl);
		$mapImage = 'data:image/png;base64,' . base64_encode($mapImage);

		/* Radiation Graph Generate */
		$radiationGraphArr 	= $this->Projects->getSolarRediationGHIChartData($project->latitude,$project->longitude);
		if(!empty($radiationGraphArr['radiation_ghi_data'])) {
			$radiationGraphData['radiation_ghi_data'] = (!empty($radiationGraphArr['radiation_ghi_data'])?$radiationGraphArr['radiation_ghi_data']:array());
			$radiationGraphImg = $this->Projects->radiationGraph($radiationGraphData);
		}else{
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
		if(!empty($payBackGraphData)){
			$paybackGraphImg 	= $this->Projects->paybackGraph($payBackGraphData);
		}else{
			$paybackGraphImg="";
		}
		/* Get Environment Benefit Data. */
		$inpdataArr['recommendedCapacity'] = $project->estimated_kwh_year;
		$inpdataArr['estimatedKWHYear'] = $project->recommended_capacity;
		$environmentData = $this->Projects->calculateSolarPowerGreenSavingsData($inpdataArr);
		$hideInstaller = $isInstallerhide;
		/* Get PDF Report id. */
		$projectReportId = $this->Projects->GetProjectPDFReportId($id);
		$this->set(compact("project","projectInstallers","mapImage","radiationGraphImg","paybackGraphImg","radiationGraphData","energyAndSavingDataArr","projectReportId","environmentData","hideInstaller"));
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

}