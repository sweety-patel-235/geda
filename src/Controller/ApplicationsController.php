<?php
namespace App\Controller;
ini_set('memory_limit', '-1');
ini_set('max_execution_time', '-1');
use App\Controller\AppController;
use Cake\View\View;
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
use Cake\Datasource\ConnectionManager;
use Dompdf\Dompdf;
use Couchdb\Couchdb;

class ApplicationsController extends FrontAppController
{
	public $user_department = array();
	public $arrDefaultAdminUserRights = array();
	public $helpers = array('Time','Html','Form','ExPaginator');
	public $PAGE_NAME = '';
	public $CUSTOMER_STATE_ID = 4;
	public $paginate = [
		'limit' => PAGE_RECORD_LIMIT,
		'order' => [
			'ApplyOnlines.id ' => 'desc'
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

		$this->loadModel('ApiToken');
		$this->loadModel('ApplyOnlines');
		$this->loadModel('DiscomMaster');
		$this->loadModel('FesibilityReport');
		$this->loadModel('RegistrationScheme');
		$this->loadModel('RegistrationSchemeDocument');
		$this->loadModel('WorkCompletion');
		$this->loadModel('WorkCompletionDocument');
		$this->loadModel('ReChargingCertificate');
		$this->loadModel('Users');
		$this->loadModel('Userrole');
		$this->loadModel('Userroleright');
		$this->loadModel('ApplyOnlineApprovals');
		$this->loadModel('Adminaction');
		$this->loadModel('Parameters');
		$this->loadModel('BranchMasters');
		$this->loadModel('Members');
		$this->loadModel('Installers');
		$this->loadModel('Customers');
		$this->loadModel('States');
		$this->loadModel('Sessions');
		$this->loadModel('ApplyonlinDocs');
		$this->loadModel('Projects');
		$this->loadModel('InstallerProjects');
		$this->loadModel('CustomerProjects');
		$this->loadModel('Payumoney');
		$this->loadModel('ApplyonlinePayment');
		$this->loadModel('ApplyonlineMessage');
		$this->loadModel('ApplicationsMessage');
		$this->loadModel('Installation');
		$this->loadModel('WorkCompletion');
		$this->loadModel('CeiReApplicationDetails');
		$this->loadModel('ReThirdpartyApiLog');
		$this->loadModel('InstallerCategory');
		$this->loadModel('InstallerCategoryMapping');
		$this->loadModel('ApplicationDeleteLog');
		$this->loadModel('SpinWebserviceApi');
		$this->loadModel('UpdateDetails');
		$this->loadModel('UpdateDetailsApplicationsLog');
		$this->loadModel('UpdateCapacity');
		$this->loadModel('UpdateCapacityApplicationsLog');
		$this->loadModel('UpdateCapacityProjectsLog');
		$this->loadModel('Subsidy');
		$this->loadModel('ApplyOnlinesOthers');
		$this->loadModel('Inspectionpdf');
		$this->loadModel('Emaillog');
		$this->loadModel('ApplyonlineUnReadMessage');
		$this->loadModel('EnergyGenerationLog');
		$this->loadModel('ApplicationRequestDelete');
		$this->loadModel('SolarTypeLog');
		$this->loadModel('SendRegistrationFailure');
		$this->loadModel('Couchdb');
		$this->loadModel('ReCouchdb');
		$this->loadModel('Developers');
		$this->loadModel('DeveloperCustomers');
		$this->loadModel('UpdateDiscomDataLog');
		$this->loadModel('DistrictMaster');
		$this->loadModel('Applications');
		$this->loadModel('ApplicationCategory');
		$this->loadModel('EndUseElectricity');
		$this->loadModel('ApplicationStages');
		$this->loadModel('ApplicationsDocs');
		$this->loadModel('ReApplicationPayment');
		$this->loadModel('ManufacturerMaster');
		$this->loadModel('ApplicationHybridAdditionalData');
		$this->loadModel('ApplicationGeoLocation');
		$this->loadModel('ApplicationConnectivityStep');
		$this->loadModel('DeveloperWorkorder');
		$this->loadModel('DeveloperAssignWorkorder');
		$this->loadModel('WindApplicationDeveloperPermission');
		$this->loadModel('OpenAccessApplicationDeveloperPermission');
		//$this->loadModel('HybridApplicationDeveloperPermission');
		$this->loadModel('TransferDeveloperPermission');
		$this->loadModel('wind_manufacturer_rlmm');
		$this->loadModel('GeoApplicationRejectLog');
		$this->loadModel('GeoApplicationPayment');
		$this->loadModel('ApplicationProjectCommissioning');
		$this->loadModel('application_end_use_electricity');
		$this->loadModel('MemberRoles');
		$this->loadModel('DeveloperApplicationQuery');
		$this->loadModel('TransferDeveloperApplicationQuery');
		$this->loadModel('DeveloperCompany');
		$this->loadModel('WindWtgDetail');
		$this->loadModel('WheelingApplicationDetails');
		$this->loadModel('SldcApplicationDetails');
		$this->loadModel('BptaApplicationDetails');
		$this->loadModel('MeterSealingApplicationDetails');
		$this->loadModel('PowerInjectionApplicationDetails');
		$this->set('ApplicationsMessage',$this->ApplicationsMessage);
		$this->set('InspectionReport',$this->InspectionReport);
		$this->set('Userright',$this->Userright);

		$customer_type 	= $this->Session->read('Customers.customer_type');
		$this->set("customer_type",$customer_type);

		$member_type 	= $this->Session->read('Members.member_type');
		$area 			= $this->Session->read('Members.area');
		$circle 		= $this->Session->read('Members.circle');
		$division 		= $this->Session->read('Members.division');
		$subdivision 	= $this->Session->read('Members.subdivision');
		$section 		= $this->Session->read('Members.section');

		$this->set("JREDA",$this->ApplyOnlines->JREDA);
		$this->set("DISCOM",$this->ApplyOnlines->DISCOM);
		$this->set("CEI",$this->ApplyOnlines->CEI);
		$this->set("MStatus",$this->ApplicationStages);
		$this->set("member_type",$member_type);
		$this->set("area",$area);
		$this->set("circle",$circle);
		$this->set("division",$division);
		$this->set("subdivision",$subdivision);
		$this->set("section",$section);

		$is_installer = false;
		if ($customer_type == "installer") {
			$is_installer = true;
		}
		$this->set("is_installer",$is_installer);
		$this->set("customer_types",array("customer","installer"));

		if (BLOCK_APPLICATION == 1)
		{
			if ($customer_type == "installer") {
				$this->Flash->set(BLOCK_APPLICATION_MESSAGE,['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'error']]);
				return $this->redirect('installer-dashboard');
			} else if ($member_type == $this->ApplyOnlines->JREDA) {
				return $this->redirect('member');
			}  else if ($member_type == $this->ApplyOnlines->DISCOM) {
				return $this->redirect('member');
			} else if ($member_type == $this->ApplyOnlines->CEI) {
				return $this->redirect('member');
			} else {
				return $this->redirect('home');
			}
		}

		if($this->request->params['action']=='index' || $this->request->params['action']=='trackapplication')
		{
			//$this->loadComponent('Csrf');
		}
	}
	/*
	 * Displays a index
	 *
	 * @param mixed What page to display
	 * @return void
	 */
	public function index($id = 0,$activetab=0) {
		
		$is_installer 			= false;
		$installer_id           = '';
		$type_of_applicant 		= $this->ApiToken->arrFirmDropdown;
		$designation 			= $this->ApiToken->arrDesignation;
		$injectionLevel 		= $this->ApiToken->arrInjectionLevel;
		$gridLevel 				= $this->ApiToken->arrGridLevel;
		$EndSTU 				= $this->ApiToken->arrEndSTU;
		$EndCTU 				= $this->ApiToken->arrEndCTU;
		$Applications 			= '';
		$tab_id 				= ($activetab>1) ? $activetab : 1;
		$customerId 			= $this->Session->read("Customers.id");
		$member_id 				= $this->Session->read("Members.id");
		$ses_customer_type 		= $this->Session->read('Customers.customer_type');
		if($ses_customer_type == "installer") {
			$is_installer 		= true;
			$customer_details 	= $this->DeveloperCustomers->find('all',array('conditions'=>array('id'=>$customerId)))->first();
			$installer_id 		= $customer_details['installer_id'];
		}
		
		if(empty($customerId) && empty($member_id))
		{
			return $this->redirect(URL_HTTP.'/home');
		}
		
		$arrReqURL 	= explode("/",$this->request->url);
		$type 		= strtolower($arrReqURL[0]);
		if($type != 'groundmounted') {
			unset($injectionLevel[1]);
		}

		$applicationCategory 	= $this->ApplicationCategory->find('all',array('conditions'=>array('route_name'=>$arrReqURL[0])))->first();
		if(empty($applicationCategory)) {
			return $this->redirect(URL_HTTP.'/home');
		}
		if(in_array($applicationCategory->id,array(5)))
		{
			unset($gridLevel[2]);
			unset($injectionLevel[3]);
			unset($injectionLevel[4]);
		}
		$id 						= (!empty($id)) ? decode($id) : $id;

		$Applications 				= $this->Applications->viewApplication($id);
		if(isset($Applications->application_type) && !empty($Applications->application_type) && $applicationCategory->id != $Applications->application_type && !empty($id)) {
			$applicationCategory 	= $this->ApplicationCategory->find('all',array('conditions'=>array('id'=>$Applications->application_type)))->first();
			return $this->redirect('/'.$applicationCategory->route_name."/".encode($id));
		}
		if(empty($Applications))
		{
			$Applications 	= $this->Applications->newEntity();
		}

		if(!empty($Applications) && (!empty($Applications->comm_date) || $Applications->comm_date!='0000-00-00'))
		{
			$Applications->comm_date 	= date('d-m-Y',strtotime($Applications->comm_date));
		}
		$application_errors = array();
		if(!empty($this->request->data))
		{
			
			$cur_tab 	= $this->request->data['tab_id'];
			$errors		= array();
			$this->request->data['Applications']['application_id'] 	= $id;
			$this->request->data['Applications']['application_type']= $applicationCategory->id;
			$this->Applications->dataRecord 						= $Applications;
			switch ($cur_tab) {
				case '1':
					$response 		= $this->general_profile($this->request->data);
				break;
				case '2':
					$response 		= $this->technical_details($this->request->data);
					//json_encode(array('success'=>'1','response_errors'=>''));
				break;
				case '3':
					$response 		= $this->fees_structure($this->request->data);
				break;
				default:
					# code...
					break;
			}
			
			$arrResponse 		= json_decode($response,1);
			
			if(isset($arrResponse['redirect_url']) && !empty($arrResponse['redirect_url'])) {
				return $this->redirect($arrResponse['redirect_url']);
			}
			$application_errors = $arrResponse['response_errors'];
			if(isset($arrResponse['isApplicable']) && $arrResponse['isApplicable'] !== true) {
				//$application_errors
				$application_errors['error_capacity']['custom'] = $arrResponse['isApplicable']; //'Capacity should not greater than Work Order capacity.'
				
			}

			if(!empty($application_errors))
			{
				$Applications->errors($application_errors);
				$tab_id 		= $cur_tab;
			} else {

				if(isset($this->request->data['next_'.$cur_tab]) && $arrResponse['success']=='1')
				{
					$Applications 	= $this->Applications->viewApplication($id);

					$tab_id 		= $cur_tab+1;
				}
				else
				{
					$tab_id 		= $cur_tab;
					if($tab_id==3) // || ($tab_id==2 && in_array($applicationCategory->id,array(5,6)))
					{
						return $this->redirect(URL_HTTP.'/Applications/list');
					}

				}
				$strUrl 	= isset($arrResponse['application_id']) ?  $arrResponse['application_id'] : '';

				if(empty($id) && !empty($strUrl)) {
					$Applications 				= $this->Applications->viewApplication($strUrl);
					
					$applicationCategory 	= $this->ApplicationCategory->find('all',array('conditions'=>array('id'=>$Applications->application_type)))->first();
					return $this->redirect('/'.$applicationCategory->route_name."/".encode($Applications->id)."/".$tab_id);
					
				}
			}
		}
	
		
		$arrStateData 		= $this->States->find("list",['keyField'=>'statename','valueField'=>'statename'])->toArray();
		$district 			= $this->DistrictMaster->find("list",['keyField'=>'id','valueField'=>'name','conditions'=>['state_id'=>4]]);
		$arrEndUse 			= $this->EndUseElectricity->find('all',array('conditions'=>['application_id'=>$id]))->toArray();
		$arrEndUseElec		= array();
		if(!empty($arrEndUse)) {
			foreach($arrEndUse as $selectEnd) {
				$arrEndUseElec[] 	= $selectEnd->application_end_use_electricity;
			}
		}
		$discom_arr = array();
		$discoms 	= $this->BranchMasters->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['BranchMasters.status'=>'1','BranchMasters.parent_id'=>'0','BranchMasters.state'=>$this->ApplyOnlines->gujarat_st_id]])->toArray();
		if(!empty($discoms)) {
			foreach($discoms as $keyid=>$title) {
				$discom_arr[$keyid] = $title;
			}
		}
		$Wind_Data 			= $this->ApplicationHybridAdditionalData->fetchdata($Applications->id,3);
		$errorWind 			= 0;
		if(!empty($application_errors) || (isset($arrResponse['addMoreError']) && $arrResponse['addMoreError'] == 1)) {
			$Wind_Data 		= array();
			if(isset($this->request->data['Applications']['wtg_no'])) {
				foreach($this->request->data['Applications']['wtg_no'] as $key=>$value) {
					$Wind_Data[$key]['nos_mod_inv'] 			= $value;
					$Wind_Data[$key]['mod_inv_capacity'] 		= $this->request->data['Applications']['capacity_wtg'][$key];
					$Wind_Data[$key]['mod_inv_total_capacity'] 	= $this->request->data['Applications']['total_capacity'][$key];
					$Wind_Data[$key]['mod_inv_make'] 			= $this->request->data['Applications']['make'][$key];
					$Wind_Data[$key]['application_id'] 			= $Applications->id;
					$Wind_Data[$key]['capacity_type'] 			= 3;
					$Wind_Data[$key]['id'] 						= isset($this->request->data['Applications']['id_wind'][$key]) ? $this->request->data['Applications']['id_wind'][$key] : 0;
					if(empty($value) || empty($this->request->data['Applications']['capacity_wtg'][$key]) || empty($this->request->data['Applications']['make'][$key])) {
						$errorWind 	= 1;
					}
				}
			}
		}
		$Hybrid_Module_Data 	= $this->ApplicationHybridAdditionalData->fetchdata($Applications->id,1);
		$errorModule 			= 0;
		if(!empty($application_errors) || (isset($arrResponse['addMoreError']) && $arrResponse['addMoreError'] == 1)) {
			$Hybrid_Module_Data 		= array();
			if(isset($this->request->data['Applications']['nos_mod'])) {
				foreach($this->request->data['Applications']['nos_mod'] as $key=>$value) {
					$Hybrid_Module_Data[$key]['nos_mod_inv'] 				= $value;
					$Hybrid_Module_Data[$key]['mod_inv_capacity'] 			= $this->request->data['Applications']['mod_capacity'][$key];
					$Hybrid_Module_Data[$key]['mod_inv_total_capacity'] 	= $this->request->data['Applications']['mod_total_capacity'][$key];
					$Hybrid_Module_Data[$key]['mod_inv_make'] 				= $this->request->data['Applications']['mod_make'][$key];
					$Hybrid_Module_Data[$key]['mod_type_of_spv'] 			= $this->request->data['Applications']['mod_type_of_spv'][$key];
					$Hybrid_Module_Data[$key]['mod_type_of_solar_panel'] 	= $this->request->data['Applications']['mod_type_of_solar_panel'][$key];
					$Hybrid_Module_Data[$key]['application_id'] 			= $Applications->id;
					$Hybrid_Module_Data[$key]['capacity_type'] 				= 1;
					$Hybrid_Module_Data[$key]['id'] 						= isset($this->request->data['Applications']['id_module'][$key]) ? $this->request->data['Applications']['id_module'][$key] : 0;
					
					if(empty($value) || empty($this->request->data['Applications']['mod_capacity'][$key]) || empty($this->request->data['Applications']['mod_make'][$key]) || empty($this->request->data['Applications']['mod_type_of_spv'][$key]) || empty($this->request->data['Applications']['mod_type_of_solar_panel'][$key])) {
						$errorModule 	= 1;
					}
				}
			}
			
		}
		$Hybrid_Inverter_Data 	= $this->ApplicationHybridAdditionalData->fetchdata($Applications->id,2);
		$errorInverter 			= 0;
		if(!empty($application_errors) || (isset($arrResponse['addMoreError']) && $arrResponse['addMoreError'] == 1)) {
			$Hybrid_Inverter_Data 		= array();
			if(isset($this->request->data['Applications']['nos_inv'])) {
				foreach($this->request->data['Applications']['nos_inv'] as $key=>$value) {
					$Hybrid_Inverter_Data[$key]['nos_mod_inv'] 				= $value;
					$Hybrid_Inverter_Data[$key]['mod_inv_capacity'] 		= $this->request->data['Applications']['inv_capacity'][$key];
					$Hybrid_Inverter_Data[$key]['mod_inv_total_capacity'] 	= $this->request->data['Applications']['inv_total_capacity'][$key];
					$Hybrid_Inverter_Data[$key]['mod_inv_make'] 			= $this->request->data['Applications']['inv_make'][$key];
					$Hybrid_Inverter_Data[$key]['inv_used'] 				= $this->request->data['Applications']['inv_used'][$key];
					$Hybrid_Inverter_Data[$key]['application_id'] 			= $Applications->id;
					$Hybrid_Inverter_Data[$key]['capacity_type'] 			= 2;
					$Hybrid_Inverter_Data[$key]['id'] 						= isset($this->request->data['Applications']['id_inverter'][$key]) ? $this->request->data['Applications']['id_inverter'][$key] : 0;
					
					if(empty($value) || empty($this->request->data['Applications']['inv_capacity'][$key]) || empty($this->request->data['Applications']['inv_make'][$key])  || empty($this->request->data['Applications']['inv_used'][$key])) {
						$errorInverter 	= 1;
					}
				}
			}
		}

		$hybrid_capacity 		= $this->Applications->find('all',array('conditions'=>['id'=>$Applications->id]))->first();
		$totalInverternos		= $this->ApplicationHybridAdditionalData->getwinddatasum($Applications->id,2);
		$totalModulenos			= $this->ApplicationHybridAdditionalData->getwinddatasum($Applications->id,1);
		$type_manufacturer_mod 	= $this->ManufacturerMaster->manufacturerList(1);
		$type_manufacturer_inv 	= $this->ManufacturerMaster->manufacturerList(2);
		$type_manufacturer_wind = $this->ManufacturerMaster->manufacturerList(3);
		
		$developer_list 		= $this->Developers->find('list',['keyField'=>'id','valueField'=>'installer_name','conditions'=>array('status'=>1)])->toArray();
		$assigned_workorder_list= $this->DeveloperAssignWorkorder->find('list',['keyField'=>'id','valueField'=>'workorder_no','conditions'=>array('assign_installer_id'=>$installer_id,'
			status'=>1,'application_id is NULL')])->toArray();
		$arrAssignedWorkList 	= array();
		if(!empty($assigned_workorder_list)) {
			foreach($assigned_workorder_list as $key=>$val) {
				$developerDetailsData 		= $this->DeveloperAssignWorkorder->get($key);
				$totalRegistredWorkorder 	= $this->DeveloperAssignWorkorder->getWorkOrderRemainingCapacity($key,'',1);
				if(($developerDetailsData->capacity > $totalRegistredWorkorder) || (isset($Applications->map_workorder_id) && !empty($Applications->map_workorder_id))) {
					$arrAssignedWorkList[$key] 	= $val;
				}
			}
		}
		$substation_details = $this->get_substation_details();
		echo"<pre>"; print_r($substation_details); die();
		
		$this->set('substation_details',$substation_details);
		$this->set('type_manufacturer_wind',$type_manufacturer_wind);
		$this->set('type_manufacturer_inv',$type_manufacturer_inv);
		$this->set('type_manufacturer_mod',$type_manufacturer_mod);
		$this->set('totalInverternos',$totalInverternos);
		$this->set('totalModulenos',$totalModulenos);
		$this->set('Wind_Data',$Wind_Data);
		$this->set('hybrid_capacity',$hybrid_capacity);
		$this->set('Hybrid_Inverter_Data',$Hybrid_Inverter_Data);
		$this->set('Hybrid_Module_Data',$Hybrid_Module_Data);
		$this->set('pageTitle','Application');
		$this->set('type_of_applicant',$type_of_applicant);
		$this->set('designation',$designation);
		$this->set('Applications',$Applications);
		$this->set('injectionLevel',$injectionLevel);
		$this->set('gridLevel',$gridLevel);
		$this->set('arrDistictData',$district);
		$this->set('type',$type);
		$this->set('EndSTU',$EndSTU);
		$this->set('EndCTU',$EndCTU);
		$this->set('applicationCategory',$applicationCategory);
		$this->set("ApplicationError",$application_errors);
		$this->set('Couchdb',$this->Couchdb);
		$this->set("tab_id",$tab_id);
		$this->set("arrEndUseElec",$arrEndUseElec);
		$this->set("applicationID",$id);
		$this->set("discom_arr",$discom_arr);
		$this->set("arrStateData",$arrStateData);
		$this->set("errorWind",$errorWind);
		$this->set("errorModule",$errorModule);
		$this->set("errorInverter",$errorInverter);
		$this->set("developer_list",$developer_list);
		$this->set("assigned_workorder_list",$arrAssignedWorkList);
		$this->set("type_of_spv",$this->OpenAccessApplicationDeveloperPermission->type_of_spv);
		$this->set("type_of_solar_panel",$this->OpenAccessApplicationDeveloperPermission->type_of_solar_panel);
		$this->set("type_of_inverter_used",$this->OpenAccessApplicationDeveloperPermission->type_of_inverter_used);
	}

	
	 /*
	 *
	 * general_profile
	 *
	 * Behaviour : Private
	 *
	 * @param : $request_data   : tab1 form posted data should be passed
	 * @defination : Method is use to check validation of first tab and insert/update subsidy record for first tab.
	 *
	 */
	private function general_profile($request_data)
	{
		if(!empty($this->Session->read('Members.member_type')))
		{
			$customerId 				= $this->Session->read("Members.id");
		}
		else
		{
			$customerId 				= $this->Session->read("Customers.id");
		}
		
		$applicaiton_exist 				= $this->Applications->viewApplication($request_data['Applications']['application_id']);
		
		if(empty($applicaiton_exist) && isset($request_data['Applications']['map_workorder_id']) && !empty($request_data['Applications']['map_workorder_id'])) {
			$assigned_workorder_details = $this->DeveloperAssignWorkorder->find('all',
								['conditions'=>array('id'=>$request_data['Applications']['map_workorder_id'],'status'=>1,'application_id is NULL')])->first();

			if(!empty($assigned_workorder_details)) {
				$developerDetails 		= $this->Developers->find('all',array('conditions'=>array('id'=>$assigned_workorder_details->installer_id)))->first();
				
				require_once(ROOT . DS . 'vendor' . DS . 'couchdb' . DS . 'couchdb.php');

				$COUCHDB        = new Couchdb();
				
				$request_data['Applications']['name_of_applicant'] 		= $developerDetails->installer_name;
				$request_data['Applications']['address']  				= $developerDetails->address;
				$request_data['Applications']['address1']  				= $developerDetails->address1; 
				$request_data['Applications']['taluka']  				= $developerDetails->taluka; 
				$request_data['Applications']['city']  					= $developerDetails->city; 
				$request_data['Applications']['state']  				= $developerDetails->state; 
				$request_data['Applications']['district']  				= $developerDetails->district; 
				$request_data['Applications']['pincode']  				= $developerDetails->pincode; 
				$request_data['Applications']['contact']  				= $developerDetails->contact; 
				$request_data['Applications']['mobile']  				= $developerDetails->mobile; 
				$request_data['Applications']['email']  				= $developerDetails->email; 
				$request_data['Applications']['pan']  					= $developerDetails->pan; 
				$request_data['Applications']['GST']  					= $developerDetails->GST; 
				$request_data['Applications']['type_of_applicant']  	= $developerDetails->type_of_applicant; 
				$request_data['Applications']['applicant_others']  		= $developerDetails->applicant_others; 
				$request_data['Applications']['msme']  					= $developerDetails->msme; 
				$request_data['Applications']['name_director']  		= $developerDetails->name_director; 
				$request_data['Applications']['type_director']  		= $developerDetails->type_director; 
				$request_data['Applications']['type_director_others']  	= $developerDetails->type_director_others; 
				$request_data['Applications']['director_whatsapp']  	= $developerDetails->director_whatsapp; 
				$request_data['Applications']['director_mobile'] 		= $developerDetails->director_mobile; 
				$request_data['Applications']['director_email']  		= $developerDetails->director_email; 
				$request_data['Applications']['name_authority']  		= $developerDetails->name_authority; 
				$request_data['Applications']['type_authority']  		= $developerDetails->type_authority; 
				$request_data['Applications']['type_authority_others']  = $developerDetails->type_authority_others; 
				$request_data['Applications']['authority_whatsapp']  	= $developerDetails->authority_whatsapp; 
				$request_data['Applications']['authority_mobile']  		= $developerDetails->authority_mobile; 
				$request_data['Applications']['authority_email']  		= $developerDetails->authority_email; 
				$request_data['Applications']['workorder_installer_id'] = $assigned_workorder_details->installer_id; 
				$ApplicationEntity 				= $this->Applications->newEntity($request_data);
				$ApplicationEntity->created 	= $this->NOW();
				$ApplicationEntity->created_by 	= $customerId;
				$ApplicationEntity->customer_id = $customerId;
				$ApplicationEntity->modified 	= $this->NOW();
				$ApplicationEntity->modified_by = $customerId;

				if($this->Session->read("Customers.id") && !empty($this->Session->read("Customers.id"))) {
					$customer_details 				= $this->DeveloperCustomers->find('all',array('conditions'=>array('id'=>$customerId)))->first();
					$ApplicationEntity->installer_id= $customer_details->installer_id;
				}
				$this->Applications->save($ApplicationEntity);
				$application_no = $this->Applications->GenerateApplicationNo($ApplicationEntity);
				
				$this->Applications->updateAll(['application_no'=>$application_no],['id'=>$ApplicationEntity->id]);
				$application_id 		= $ApplicationEntity->id;
				$main_path 				= WWW_ROOT.APPLICATIONS_PATH.$application_id.'/';
				if(!is_dir(APPLICATIONS_PATH.$application_id)){
					@mkdir(APPLICATIONS_PATH.$application_id, 0777,true);
				}
				$arrDeveloperFieldData 		= array('pan_card','registration_document','d_file_board','d_msme'); //,'upload_undertaking'
				$arrDeveloperAccessData 	= array('d_pan_card','d_registration_document','d_file_board','d_msme'); //,'d_upload_undertaking'
				$arrApplicationAccessData 	= array('a_pan_card','a_registration_document','a_file_board','a_msme'); //,'a_upload_undertaking'
				$arrApplicationFieldData 	= array('pan_card','registration_document','d_file_board','a_msme'); //,'upload_undertaking'
				$arrApplicationPrefixData 	= array('pc','rd','fb','amm'); //,'auu'
				foreach($arrDeveloperFieldData as $kFile=>$valFile) {
					$fieldDeveloper 		= $arrDeveloperFieldData[$kFile];
					
					if(!empty($developerDetails->$fieldDeveloper)) {
						$document_details      	= $this->Couchdb->find('all',array('conditions'=>array('application_id'=>$assigned_workorder_details->installer_id,'access_type'=>$arrDeveloperAccessData[$kFile])))->first();
						
						if(!is_dir($main_path)) mkdir($main_path, 0755);
						$output     = $COUCHDB->getDocument($document_details->document_id,$document_details->file_attached,$document_details->doc_mime_type,1);
						
						$ext 				= substr(strtolower(strrchr($document_details->file_attached, '.')), 1);
						$prefix_file 	 	= $arrApplicationPrefixData[$kFile];

						$access_type 		= $arrApplicationAccessData[$kFile];
						$file_name   		= $prefix_file.date('YmdHis').rand();
						$file_location  	= $main_path.$file_name.'.'.$ext;
						file_put_contents($file_location, $output);
					
						$passFileName 		= $file_name.'.'.$ext;

						$couchdbId 			= $this->Couchdb->saveData($main_path,$file_location,$prefix_file,$passFileName,$customerId,$access_type);
						$this->Applications->updateAll(
							array($arrApplicationFieldData[$kFile] 	=> $passFileName),
							array("id" => $application_id)
						);
					}
				}
				//$this->DeveloperAssignWorkorder->updateAll(['application_id'=>$ApplicationEntity->id],['id'=>$assigned_workorder_details->id]);
				$strUrl 				= isset($ApplicationEntity->id) ?  $ApplicationEntity->id : '';
				$applicaitonDetails 	= $this->Applications->viewApplication($ApplicationEntity->id);
				$applicationCategory 	= $this->ApplicationCategory->find('all',array('conditions'=>array('id'=>$applicaitonDetails->application_type)))->first();
				return json_encode(array('success'=>'1','response_errors'=>array(),'redirect_url'=>URL_HTTP.$applicationCategory->route_name."/".encode($applicaitonDetails->id)."/1"));	
			}
		}

		$this->Applications->dataPass 	= $request_data['Applications'];

		if(empty($applicaiton_exist))
		{
			$ApplicationEntity 				= $this->Applications->newEntity($request_data,['validate'=>'tab1']);
			$ApplicationEntity->created 	= $this->NOW();

			$ApplicationEntity->created_by 	= $customerId;
			$ApplicationEntity->customer_id = $customerId;
			if($this->Session->read("Customers.id") && !empty($this->Session->read("Customers.id"))) {
				$customer_details 				= $this->DeveloperCustomers->find('all',array('conditions'=>array('id'=>$customerId)))->first();
				$ApplicationEntity->installer_id= $customer_details->installer_id;
			}
			
			$saveText						= 'inserted';
		}
		else
		{
			$ApplicationEntity 					= $this->Applications->patchEntity($applicaiton_exist,$request_data,['validate'=>'tab1']);
			$saveText							= 'updated';
			//$ApplicationEntity->customer_id 	= $customerId;
			//$ApplicationEntity->application_no 	= $this->Applications->GenerateApplicationNo($ApplicationEntity);
		}
		$ApplicationEntity->modified 		= $this->NOW();
		$ApplicationEntity->modified_by 	= $customerId;
		//$ApplicationEntity->tab1_submit 	= '1';
		//$ApplicationEntity->tab2_submit 	= '1';
		if(!empty($ApplicationEntity->errors()))
		{
			return json_encode(array('success'=>'0','response_errors'=>$ApplicationEntity->errors()));
		}
		else
		{
			
			$this->Applications->save($ApplicationEntity);
			$application_no = $this->Applications->GenerateApplicationNo($ApplicationEntity);
			$this->Applications->updateAll(['application_no'=>$application_no],['id'=>$ApplicationEntity->id]);
			if(isset($this->Applications->dataPass['f_registration_document']['tmp_name']) && !empty($this->Applications->dataPass['f_registration_document']['tmp_name']))
			{
				$file_name 	= $this->imgfile_upload($this->Applications->dataPass['f_registration_document'],'rd',$ApplicationEntity->id,'registration_document','a_registration_document');
				$this->Applications->updateAll(
					array("registration_document" => $file_name),
					array("id" => $ApplicationEntity->id)
				);
			}
			if(isset($this->Applications->dataPass['f_pan_card']['tmp_name']) && !empty($this->Applications->dataPass['f_pan_card']['tmp_name']))
			{
				$file_name 	= $this->imgfile_upload($this->Applications->dataPass['f_pan_card'],'pc',$ApplicationEntity->id,'pan_card','a_pan_card');
				$this->Applications->updateAll(
					array("pan_card" => $file_name),
					array("id" => $ApplicationEntity->id)
				);
			}
			if(isset($this->Applications->dataPass['f_file_board']['tmp_name']) && !empty($this->Applications->dataPass['f_file_board']['tmp_name']))
			{
				$file_name 	= $this->imgfile_upload($this->Applications->dataPass['f_file_board'],'fb',$ApplicationEntity->id,'d_file_board','a_file_board');
				$this->Applications->updateAll(
					array("d_file_board" => $file_name),
					array("id" => $ApplicationEntity->id)
				);
			}

			if(isset($this->Applications->dataPass['a_upload_undertaking']['tmp_name']) && !empty($this->Applications->dataPass['a_upload_undertaking']['tmp_name']))
			{
				$file_name 	= $this->imgfile_upload($this->Applications->dataPass['a_upload_undertaking'],'auu',$ApplicationEntity->id,'upload_undertaking','a_upload_undertaking');
				$this->Applications->updateAll(
					array("upload_undertaking" => $file_name),
					array("id" => $ApplicationEntity->id)
				);
			}
			if(isset($this->Applications->dataPass['app_msme']['tmp_name']) && !empty($this->Applications->dataPass['app_msme']['tmp_name']))
			{
				$file_name 	= $this->imgfile_upload($this->Applications->dataPass['app_msme'],'amm',$ApplicationEntity->id,'a_msme','a_msme');
				$this->Applications->updateAll(
					array("a_msme" => $file_name),
					array("id" => $ApplicationEntity->id)
				);
			}
			$this->Flash->success("Application $saveText successfully.");
			return json_encode(array('success'=>'1','response_errors'=>'','application_id'=>$ApplicationEntity->id));
		}
	}
	 /**
	*
	* imgfile_upload
	*
	* Behaviour : public
	*
	* @param : id  : $file is use to identify for which image should be select and $path is use to identify the image folder path.
	*
	* @defination : Method is use to save the image in file folder .
	*
	*/
	public function imgfile_upload($file,$prefix_file='',$application_id,$file_field,$access_type='')
	{
		$customerId 	= $this->Session->read('Customers.id');
		$name 			= $file['name'];
		$path 			= WWW_ROOT.APPLICATIONS_PATH.$application_id.'/';
		if(!file_exists(APPLICATIONS_PATH.$application_id)){
			@mkdir(APPLICATIONS_PATH.$application_id, 0777,true);
		}
		$updateRequestData 	= $this->Applications->find('all',array('conditions'=>array('id'=>$application_id)))->first();
		if(!empty($updateRequestData->$file_field) && file_exists($path.$updateRequestData->$file_field))
		{
			@unlink($path.$updateRequestData->$file_field);
		}
		$ext    		= substr(strtolower(strrchr($file['name'], '.')), 1);
		$file_name   	= $prefix_file.date('YmdHis').rand();
		$file_location  = $path.$file_name.'.'.$ext;
		
		move_uploaded_file($file['tmp_name'],$file_location);
		
		$passFileName 	= $file_name.'.'.$ext;
		$couchdbId 		= $this->Couchdb->saveData($path,$file_location,$prefix_file,$passFileName,$customerId,$access_type);
		return $file_name.'.'.$ext;
	}
	/**
	 *
	 * technical_details
	 *
	 * Behaviour : Private
	 *
	 * @param : $request_data   : tab1 form posted data should be passed
	 * @defination : Method is use to check validation of first tab and insert/update subsidy record for first tab.
	 *
	 */
	private function technical_details($request_data)
	{
		if(!empty($this->Session->read('Members.member_type')))
		{
			$customerId 				= $this->Session->read("Members.id");
		}
		else
		{
			$customerId 				= $this->Session->read("Customers.id");
		}
		
		$applicaiton_exist 				= $this->Applications->viewApplication($request_data['Applications']['application_id']);

				
		$this->Applications->dataPass 				= $request_data['Applications'];
		$this->Applications->dataPass['created'] 	= isset($applicaiton_exist->created) ? $applicaiton_exist->created : '';
		
		if(empty($applicaiton_exist))
		{

			$ApplicationEntity 				= $this->Applications->newEntity($request_data,['validate'=>'tab2']);
			$ApplicationEntity->created 	= $this->NOW();
			$ApplicationEntity->created_by 	= $customerId;
			$saveText						= 'inserted';
		}
		else
		{

			$ApplicationEntity 				= $this->Applications->patchEntity($applicaiton_exist,$request_data,['validate'=>'tab2']);
			$saveText						= 'updated';

		}
		$ApplicationEntity->modified 		= $this->NOW();
		$ApplicationEntity->modified_by 	= $customerId;
		//$ApplicationEntity->tab1_submit 	= '1';
		//$ApplicationEntity->tab2_submit 	= '1';
		$errorWind 				= 0;
		$isApplicable 			= true;
		$totalCapacityAdded 	= isset($request_data['Applications']['pv_capacity_ac']) ? $request_data['Applications']['pv_capacity_ac'] : 0;
		if(isset($request_data['Applications']['wtg_no'])) {
			foreach($request_data['Applications']['wtg_no'] as $key=>$value) {

				if(empty($value) || empty($request_data['Applications']['capacity_wtg'][$key]) || empty($request_data['Applications']['make'][$key])) {
					$errorWind 	= 1;
				}
				if(!empty($this->request->data['Applications']['total_capacity'][$key])) {
					$totalCapacityAdded		= $totalCapacityAdded + $this->request->data['Applications']['total_capacity'][$key];
				}
			}
		}
		$errorModule 			= 0;
		if(isset($request_data['Applications']['nos_mod'])) {
			foreach($request_data['Applications']['nos_mod'] as $key=>$value) {
				if(empty($value) || empty($request_data['Applications']['mod_capacity'][$key]) || empty($request_data['Applications']['mod_make'][$key])) {
					$errorModule 	= 1;
				}
			}
		}
		$errorInverter 			= 0;
		if(isset($request_data['Applications']['nos_inv'])) {
			foreach($request_data['Applications']['nos_inv'] as $key=>$value) {
				if(empty($value) || empty($request_data['Applications']['inv_capacity'][$key]) || empty($request_data['Applications']['inv_make'][$key])) {
					$errorInverter 	= 1;
				}
				if(!empty($this->request->data['Applications']['inv_total_capacity'][$key])) {
					$totalCapacityAdded		= $totalCapacityAdded + $this->request->data['Applications']['inv_total_capacity'][$key];
				}
			}
		}
		if(!empty($applicaiton_exist->map_workorder_id) != '') {
			$isApplicable 	= $this->DeveloperAssignWorkorder->getWorkOrderRemainingCapacity($applicaiton_exist->map_workorder_id,$totalCapacityAdded,0,$applicaiton_exist->id);
		}
		if(!empty($ApplicationEntity->errors()) || $errorWind == 1 || $errorModule == 1 || $errorInverter==1 || $isApplicable !== true)
		{
			return json_encode(array('success'=>'0','response_errors'=>$ApplicationEntity->errors(),'addMoreError'=>1,'isApplicable'=>$isApplicable));
		}
		else
		{
			$ApplicationEntity->comm_date 	= date('Y-m-d',strtotime($ApplicationEntity->comm_date));
			$this->Applications->save($ApplicationEntity);
			$application_no = $this->Applications->GenerateApplicationNo($ApplicationEntity);
			$this->Applications->updateAll(['application_no'=>$application_no],['id'=>$ApplicationEntity->id]);
		
			if(isset($this->Applications->dataPass['f_sale_discom']['tmp_name']) && !empty($this->Applications->dataPass['f_sale_discom']['tmp_name']))
			{
				$file_name 	= $this->imgfile_upload($this->Applications->dataPass['f_sale_discom'],'fsd',$ApplicationEntity->id,'f_sale_discom','f_sale_discom');
				$this->Applications->updateAll(
					array("f_sale_discom" => $file_name),
					array("id" => $ApplicationEntity->id)
				);
			}

			$this->EndUseElectricity->deleteAll(['application_id'=>$ApplicationEntity->id]);
			$endEle 	= ($request_data['Applications']['grid_connectivity'] == 1) ? 'end_stu' : 'end_ctu';
			
			if(isset($request_data['Applications'][$endEle]) && !empty($request_data['Applications'][$endEle])) {
				/*foreach($request_data['Applications'][$endEle] as $val)
				{*/
					if(!empty($request_data['Applications'][$endEle])) {
						$endUseEleEntity 									= $this->EndUseElectricity->newEntity();
						$endUseEleEntity->application_id 					= $ApplicationEntity->id;
						$endUseEleEntity->application_end_use_electricity 	= $request_data['Applications'][$endEle];
						$endUseEleEntity->created 							= $this->NOW();
						$this->EndUseElectricity->save($endUseEleEntity);
					}
				//}
			}
			if(isset($this->request->data['Applications']['wtg_no']) && !empty($this->request->data['Applications']['wtg_no'])){
				$this->ApplicationHybridAdditionalData->deleteAll(['application_id' => $ApplicationEntity->id,'capacity_type' => 3]);
				foreach($this->request->data['Applications']['wtg_no'] as $key=>$val)
				{
					if(!empty($this->request->data['Applications']['wtg_no'][$key])){
						$arr_modules['wtg_no']				= $val;
						$arr_modules['capacity_wtg']		= $this->request->data['Applications']['capacity_wtg'][$key];
						$arr_modules['total_capacity']		= $this->request->data['Applications']['total_capacity'][$key];
						$arr_modules['make']				= $this->request->data['Applications']['make'][$key];
						
						$this->ApplicationHybridAdditionalData->save_wind($ApplicationEntity->id,$arr_modules,$customerId);
					}
					
				}
			}
			if(isset($this->request->data['Applications']['nos_mod']) && !empty($this->request->data['Applications']['nos_mod'])){
				
			 $this->ApplicationHybridAdditionalData->deleteAll(['application_id' => $ApplicationEntity->id,'capacity_type' => 1]);
				foreach($this->request->data['Applications']['nos_mod'] as $key=>$val)
				{
					if(!empty($this->request->data['Applications']['nos_mod'][$key])){
						$arr_modules['nos_mod']					= $val;
						$arr_modules['mod_capacity']			= $this->request->data['Applications']['mod_capacity'][$key];
						$arr_modules['mod_total_capacity']		= $this->request->data['Applications']['mod_total_capacity'][$key];
						$arr_modules['mod_make']				= $this->request->data['Applications']['mod_make'][$key];
						$arr_modules['mod_type_of_spv']			= $this->request->data['Applications']['mod_type_of_spv'][$key];
						$arr_modules['mod_type_of_solar_panel']	= $this->request->data['Applications']['mod_type_of_solar_panel'][$key];
						
						$this->ApplicationHybridAdditionalData->save_module_hybrid($ApplicationEntity->id,$arr_modules,$customerId);
					}
					
				}
			}
			if(isset($this->request->data['Applications']['nos_inv']) && !empty($this->request->data['Applications']['nos_inv'])){
				
				$this->ApplicationHybridAdditionalData->deleteAll(['application_id' => $ApplicationEntity->id,'capacity_type' => 2]);
				foreach($this->request->data['Applications']['nos_inv'] as $key=>$val)
				{
					if(!empty($this->request->data['Applications']['nos_inv'][$key])){
						$arr_inverters['nos_inv']				= $val;
						$arr_inverters['inv_capacity']			= $this->request->data['Applications']['inv_capacity'][$key];
						$arr_inverters['inv_total_capacity']	= $this->request->data['Applications']['inv_total_capacity'][$key];
						$arr_inverters['inv_make']				= $this->request->data['Applications']['inv_make'][$key];
						$arr_inverters['inv_used']				= $this->request->data['Applications']['inv_used'][$key];
						
						$this->ApplicationHybridAdditionalData->save_inverter_hybrid($ApplicationEntity->id,$arr_inverters,$this->Session->read('Members.id'));
					}	
				}
			}
			$total_wind_data = $this->ApplicationHybridAdditionalData->getwinddatasum($ApplicationEntity->id,3);
			if(!empty($total_wind_data['mod_inv_capacity'])){
				$this->Applications->updateAll(array('wtg_no'=>$total_wind_data['nos_mod_inv'],'capacity_wtg'=>$total_wind_data['mod_inv_capacity'],'total_capacity'=>$total_wind_data['mod_inv_total_capacity']),array('id'=>$ApplicationEntity->id));
			}
			$total_hybrid_module_capacity = $this->ApplicationHybridAdditionalData->getcapacitysum($ApplicationEntity->id,1);
			$this->Applications->updateAll(array('module_hybrid_capacity'=>$total_hybrid_module_capacity['capacity']),array('id'=>$ApplicationEntity->id));
			
			$total_hybrid_inverter_capacity = $this->ApplicationHybridAdditionalData->getcapacitysum($ApplicationEntity->id,2);
			if(!empty($total_hybrid_inverter_capacity['capacity'])){

				 //$total_wind_hybrid_capacity = ($ApplicationEntity->total_capacity + $total_hybrid_inverter_capacity['capacity']);
				$total_wind_hybrid_capacity = ($total_wind_data['mod_inv_total_capacity'] + $total_hybrid_inverter_capacity['capacity']);
				$this->Applications->updateAll(array('total_wind_hybrid_capacity'=>$total_wind_hybrid_capacity,'inverter_hybrid_capacity'=>$total_hybrid_inverter_capacity['capacity']),array('id'=>$ApplicationEntity->id));
			}
			
			/*if(isset($ApplicationEntity->application_type) && in_array($ApplicationEntity->application_type, array(5,6))) 
			{
				$approval 			= $this->ApplicationStages->Approvalstage($ApplicationEntity->id);
				if(!in_array(29,$approval))
				{
					$application_status 		= $this->ApplicationStages->APPLICATION_GENERATE_OTP;
					$sms_mobile 				= $ApplicationEntity->authority_mobile;
					$this->Applications->updateAll(array('application_status'=>$application_status,'modified'=>$this->NOW(),'modified_by'=>$customerId),array('id'=>$ApplicationEntity->id));
					if(!empty($sms_mobile)) {
						$sms_message =str_replace('##application_no##',$ApplicationEntity->application_no, OTP_RESEND);
						$this->Applications->SendSMSActivationCode($ApplicationEntity->id,$sms_mobile,$sms_message,'OTP_RESEND');
					}
				}
			}*/
			
			$this->Flash->success("Application $saveText successfully.");
			return json_encode(array('success'=>'1','response_errors'=>''));
		}
	}
	/**
	 *
	 * fees_structure
	 *
	 * Behaviour : Private
	 *
	 * @param : $request_data   : tab1 form posted data should be passed
	 * @defination : Method is use to check validation of first tab and insert/update subsidy record for first tab.
	 *
	 */
	private function fees_structure($request_data)
	{
		if(!empty($this->Session->read('Members.member_type')))
		{
			$customerId 				= $this->Session->read("Members.id");
		}
		else
		{
			$customerId 				= $this->Session->read("Customers.id");
		}
		
		$applicaiton_exist 				= $this->Applications->viewApplication($request_data['Applications']['application_id']);

		
		$this->Applications->dataPass 	= $request_data['Applications'];
		
		

		$ApplicationEntity 				= $this->Applications->patchEntity($applicaiton_exist,$request_data);
		$saveText						= 'updated';


		$ApplicationEntity->modified 		= $this->NOW();
		$ApplicationEntity->modified_by 	= $customerId;
		//$ApplicationEntity->tab1_submit 	= '1';
		//$ApplicationEntity->tab2_submit 	= '1';
	
		$applicationCategory 	= $this->ApplicationCategory->find('all',array('conditions'=>array('id'=>$request_data['Applications']['application_type'])))->first();
		$application_fee 			= isset($applicationCategory->application_charges) ? $applicationCategory->application_charges : 0; 
		$application_tax_percentage = isset($applicationCategory->application_tax_percentage) ? $applicationCategory->application_tax_percentage : 0; 
		$gst_fees 					= ($application_fee*$application_tax_percentage)/100;
		$application_total_fee 		= $application_fee + $gst_fees; 
		$ApplicationEntity->application_fee 		= $application_fee;
		$ApplicationEntity->gst_fees 				= $gst_fees;
		$ApplicationEntity->application_total_fee 	= $application_total_fee;
		
		if(!empty($ApplicationEntity->errors()))
		{
			return json_encode(array('success'=>'0','response_errors'=>$ApplicationEntity->errors()));
		}
		else
		{
			$tds_deduction = 0;
			if($request_data['Applications']['liable_tds'] == 1 && $request_data['Applications']['terms_agree'] == 1) {
				$application_tds_percentage = isset($applicationCategory->application_tds_percentage) ? $applicationCategory->application_tds_percentage : 0; 
				$tds_deduction 								= ($application_fee*$application_tds_percentage)/100;
				$ApplicationEntity->tds_deduction 			= $tds_deduction;
				$ApplicationEntity->application_total_fee 	= $application_total_fee-$tds_deduction;
			} else {
				$ApplicationEntity->tds_deduction 			= 0;
				$ApplicationEntity->liable_tds 				= 0;
				$ApplicationEntity->terms_agree 			= 0;
			}
			$this->Applications->save($ApplicationEntity);
			$approval 			= $this->ApplicationStages->Approvalstage($ApplicationEntity->id);
			if(!in_array(29,$approval))
			{
				$application_status 		= $this->ApplicationStages->APPLICATION_GENERATE_OTP;
				$sms_mobile 				= $ApplicationEntity->authority_mobile;
				$this->Applications->updateAll(array('application_status'=>$application_status,'modified'=>$this->NOW(),'modified_by'=>$customerId),array('id'=>$ApplicationEntity->id));
				if(!empty($sms_mobile)) {
					/*$sms_message 	= str_replace('##application_no##',$ApplicationEntity->application_no, OTP_VERIFICATION);
					$this->Applications->SendSMSActivationCode($ApplicationEntity->id,$sms_mobile,$sms_message,'OTP_VERIFICATION');*/
					$sms_message =str_replace('##application_no##',$ApplicationEntity->application_no, OTP_RESEND);
					$this->Applications->SendSMSActivationCode($ApplicationEntity->id,$sms_mobile,$sms_message,'OTP_RESEND');
				}
				
			}
			$this->Flash->success("Application $saveText successfully.");
			return json_encode(array('success'=>'1','response_errors'=>''));
		}
	}
	/**
	 * list
	 * Behaviour : public
	 * @param : $page   : pass page number for pagination
	 * @defination : Method is use to display applications list.
	 */
	public function list($page = '1')
	{
		$this->paginate['page'] = intval($page);
		$customer_id 		= $this->Session->read("Customers.id");
		$member_id 			= $this->Session->read("Members.id");
		$state 				= '';
		$branch_id 			= '';
		$main_branch_id		= '';
		$member_type 		= '';
		
		$member_type 		= $this->Session->read('Members.member_type');
		$area 				= $this->Session->read('Members.area');
		$circle 			= $this->Session->read('Members.circle');
		$division 			= $this->Session->read('Members.division');
		$subdivision 		= $this->Session->read('Members.subdivision');
		$section 			= $this->Session->read('Members.section');
		$authority_account 	= $this->Session->read('Members.authority_account');
		$TotalPvCapacity 	= 0;
		$login_type 		= '';
		if($this->Session->check("Members.state")){
			$state 			= $this->Session->read("Members.state");
		}
		if($this->Session->check("Customers.customer_type")){
			$cust_type 		= $this->Session->read("Customers.customer_type");
		}
		if($this->Session->check("Customers.login_type")){
			$login_type 	= $this->Session->read("Customers.login_type");
		}
		/*
		if($this->Session->check("Members.branch_id")) {
			$branch_id 		= $this->Session->read("Members.branch_id");
			if(!empty($branch_id)) {
				$main_branch_id = $this->BranchMasters->findMasterId($branch_id);
			}
		}
		*/
		if($this->Session->check("Members.member_type")){
			$member_type 	= $this->Session->read("Members.member_type");
		}

		$main_branch_id = array();
		if (!empty($member_id))
		{
			if(!empty($area)) {
				$branchDetails 	= $this->BranchMasters->find('all',array('conditions'=>array('discom_id'=>$area)))->first();
			}
		}
		if(isset($this->request->data['Reset']) && !empty($this->request->data['Reset'])){
			$this->Session->delete("Customers.SearchApplication");
			$this->Session->delete("MembersSearchApplication");
			$this->Session->delete("Customers.Page");
			return $this->redirect(URL_HTTP.'applications-list');
		}
		$this->Session->write('Customers.Page',$page);

		$this->removeExtraTags();

		if(isset($this->request->data['Search']) && !empty($this->request->data['Search'])){
			$this->Session->write("MembersSearchApplication",$this->request->data);
			$this->Session->write('Customers.SearchApplication',serialize($this->request->data));
		} else {
			if($this->Session->check("MembersSearchApplication")) {
				$this->request->data = $this->Session->read("MembersSearchApplication");
			}
			if($this->Session->check("Customers.SearchApplication"))
			{
				$this->request->data = unserialize($this->Session->read("Customers.SearchApplication"));
			}
		}
		$consumer_no 			= isset($this->request->data['consumer_no']) ? $this->request->data['consumer_no'] : '';
		$application_search_no 	= isset($this->request->data['application_search_no']) ? $this->request->data['application_search_no'] : '';
		$installer_name 		= (isset($this->request->data['installer_name'])) ? $this->request->data['installer_name'] : '';
		$discom_name 			= isset($this->request->data['discom_name']) ? $this->request->data['discom_name'] : '';
		$payment_status 		= isset($this->request->data['payment_status']) ? $this->request->data['payment_status'] : '';
		$order_by_form 			= isset($this->request->data['order_by_form']) ? $this->request->data['order_by_form'] : 'Applications.modified|DESC';
		$disclaimer_subsidy 	= isset($this->request->data['disclaimer_subsidy']) ? $this->request->data['disclaimer_subsidy'] : '';
		$pcr_code 				= isset($this->request->data['pcr_code']) ? $this->request->data['pcr_code'] : '';
		$msme 					= isset($this->request->data['msme']) ? $this->request->data['msme'] : '';
		$msmeonly 				= isset($this->request->data['msmeonly']) ? $this->request->data['msmeonly'] : '';
		$inspection_status 		= isset($this->request->data['inspection_status']) ? $this->request->data['inspection_status'] : '';
		$geda_letter_status 	= isset($this->request->data['geda_letter_status']) ? $this->request->data['geda_letter_status'] : '';
		$geda_approved_status 	= isset($this->request->data['geda_approved_status']) ? $this->request->data['geda_approved_status'] : '';
		if(isset($this->request->data['category'][0]) && $this->request->data['category'][0]=='3002,3003')
		{
			$this->request->data['category'] = explode(",",$this->request->data['category'][0]);
		}
		$category 				= isset($this->request->data['category']) ? $this->request->data['category'] : '';
		$receipt_no 			= isset($this->request->data['receipt_no']) ? $this->request->data['receipt_no'] : '';
		$is_enhancement 		= isset($this->request->data['is_enhancement']) ? $this->request->data['is_enhancement'] : '';
		$this->request->data['ses_login_type'] 	= $this->Session->read('Customers.login_type');
		$this->request->data['order_by_form'] 	= $order_by_form;
		$this->request->data['customer_id'] 	= $customer_id;
		$this->request->data['member_id'] 		= $member_id;
		$this->request->data['member_type'] 	= $member_type;
		$this->request->data['main_branch_id'] 	= isset($branchDetails->id) ? $branchDetails->id : 0;
		
		//echo $customer_id;exit;
		$installer_id 			= '';
		if(!empty($customer_id)) {
			$this->set("pageTitle","My Applications");
			$this->layout = 'frontend';
			if($login_type == 'developer') {
				$customer_details 	= $this->DeveloperCustomers->find('all',array('conditions'=>array('id'=>$customer_id)))->first();
				$installer_id 		= $customer_details['installer_id'];
			}  elseif($cust_type == 'installer') {
				$customer_details 	= $this->Customers->find('all',array('conditions'=>array('id'=>$customer_id)))->first();
				$installer_id 		= $customer_details['installer_id'];
			}
			$this->request->data['installer_id'] 	= $installer_id;
			$ApplicationsList 	= $this->Applications->getDataApplications($this->request->data);
		
			$this->set('is_member',false);
		} else if(!empty($member_id)) {
			//Vishal
			$maxRoleOrders = $this->MemberRoles->find()->select(['app_type'])->where(['member_id' => $member_id])->toArray();
			$appTypes = array_map(function($role) {
				return $role->app_type;
			}, $maxRoleOrders);			
			$this->request->data['display_app'] = $appTypes;
			//Vishal
			$this->set("pageTitle","Applications");
			$this->layout 		= 'frontend';
			$ApplicationsList 	= $this->Applications->getDataApplications($this->request->data);
			$this->set('is_member',true);
			$this->set('member_type',$member_type);
		} else {
			return $this->redirect('home');
		}
		$memberApproved 	= in_array($member_id,ALLOW_DEVELOPERS_ALL_ACCESS) ? '1' : '0';
		if(!empty($state)) {
			$discom_list 	= $this->DiscomMaster->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['DiscomMaster.state_id'=>$state,'DiscomMaster.type'=>3,'status'=>'1']])->toArray();
			$division 		= $this->Session->read("Members.division");
			if (!empty($division) && isset($discom_list[$division])) {
				unset($discom_list[$division]);
			}
			$this->set('discomList',$discom_list);
		}
		$discom_arr = array();
		$discoms 	= $this->BranchMasters->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['BranchMasters.status'=>'1','BranchMasters.parent_id'=>'0','BranchMasters.state'=>$this->ApplyOnlines->gujarat_st_id]])->toArray();
		if(!empty($discoms)) {
			foreach($discoms as $id=>$title) {
				$discom_arr[$id] = $title;
			}
		}
		$current_date = strtotime(date('Y-m-d H:i:s'));
		
		$output_quota  			= true;
		$ApplicationsListData 	= $ApplicationsList['list'];
		$TotalPvCapacity 		= $ApplicationsList['TotalCapacityData'];

				
		try
		{
			$paginate_data = $this->paginate($ApplicationsListData);
		}
		catch (NotFoundException $e)
		{
			return $this->redirect('/Applications/list');
		}

		//change Vishal
		if(isset($appTypes) && !empty($appTypes)){
			$applicationCategory 	= $this->ApplicationCategory->find('list',array('keyField'=>'id','valueField'=>'category_name','conditions'=>array('id IN'=>$appTypes)))->toArray();
		}else{
			$applicationCategory 	= $this->ApplicationCategory->find('list',array('keyField'=>'id','valueField'=>'category_name','conditions'=>array('id !='=>1)))->toArray();
		}

		$transferApplicationList = $this->WindWtgDetail->find('list', [
			'keyField' => 'application_id',
			'valueField' => 'application_id',
			'conditions' => ['transfer_status' => 1]
		])->distinct(['application_id'])->toArray();
			
		//change Vishal
		
		/* status of application */
		$gridLevel 				= $this->ApiToken->arrGridLevel;
		$EndSTU 				= $this->ApiToken->arrEndSTU;
		$EndCTU 				= $this->ApiToken->arrEndCTU;
		$ApplicationsDocs 		= $this->ApplicationsDocs->find('all',array('conditions'=>array('doc_type'=>'CTUStep2')))->toArray();

		$substation_details = $this->get_substation_details();
		
		$this->set("substation_details",$substation_details);
		$this->set("APPROVED_FROM_GEDA",$this->ApplicationStages->APPROVED_FROM_GEDA);
		$this->set("APPLY_ONLINE_MAIN_STATUS",$this->ApplicationStages->apply_online_main_status);
		//$this->set("APPLY_ONLINE_MAIN_STATUS_TP",$this->ApplicationStages->apply_online_main_status_TP);
		//$this->set("APPLY_ONLINE_MAIN_STATUS_CTU",$this->ApplicationStages->apply_online_main_status_CTU);
		//$this->set("APPLY_ONLINE_MAIN_STATUS_STU",$this->ApplicationStages->apply_online_main_status_STU);
		$this->set("APPLICATION_SUBMITTED",$this->ApplicationStages->APPLICATION_SUBMITTED);
		$this->set("CONNECTIVITY_STEP1",$this->ApplicationStages->CONNECTIVITY_STEP1);
		$this->set("CTU1",$this->ApplicationStages->CTU1);
	//	$this->set("FUNDS_ARE_AVAILABLE_BUT_SCHEME_IS_NOT_ACTIVE",$this->ApplicationStages->FUNDS_ARE_AVAILABLE_BUT_SCHEME_IS_NOT_ACTIVE);
	//	$this->set("FUNDS_ARE_NOT_AVAILABLE",$this->ApplicationStages->FUNDS_ARE_NOT_AVAILABLE);
//		$this->set("SUBSIDY_AVAILIBILITY",$this->ApplicationStages->SUBSIDY_AVAILIBILITY);
		//$this->set("WORK_STARTS",$this->ApplicationStages->WORK_STARTS);
		$this->set("APPLICATION_GENERATE_OTP",$this->ApplicationStages->APPLICATION_GENERATE_OTP);
		$this->set("ApplicationsDocs",$ApplicationsDocs);
		/* end status of application */
		$this->set("JREDA",$this->ApplyOnlines->JREDA);
		$this->set("DISCOM",$this->ApplyOnlines->DISCOM);
		$this->set("CEI",$this->ApplyOnlines->CEI);
		$this->set("MStatus",$this->ApplicationStages);
		$this->set("WindDevPermissionApp",$this->WindApplicationDeveloperPermission);
		$this->set("OpenDevPermissionApp",$this->OpenAccessApplicationDeveloperPermission);
		$this->set("HybridDevPermissionApp",$this->HybridApplicationDeveloperPermission);
		$this->set("ApplyOnlines",$this->ApplyOnlines);
		$this->set("FesibilityReport",$this->FesibilityReport);
		$this->set("application_status",$this->ApplicationStages->application_status);
		$this->set("application_dropdown_status",$this->ApplicationStages->application_dropdown_status);
		$this->set("branch_id",$branch_id);
		$this->set("subdivision",$this->Session->read("Members.subdivision"));
		$this->set('ApplicationsDetails',$paginate_data);
		$this->set("ApplicationsList",$ApplicationsList);
		$this->set("MemberTypeDiscom",$this->Members->member_type_discom);
		$this->set("discom_details",$main_branch_id);
		$this->set("payment_on",Configure::read('PAYUMONEY_PAYMENT'));
		$this->set("APPLY_ONLINE_GUJ_STATUS",$this->ApplicationStages->apply_online_guj_status);
		$this->set("applyOnlinesDataDocList",$this->ApplyonlinDocs);
		$this->set('discom_arr',$discom_arr);
		$this->set('quota_msg_disp',$output_quota);
		$this->set('ApiLogResponse',$this->ReThirdpartyApiLog);
		$this->set('SpinLogResponse',$this->SpinWebserviceApi);
		$this->set('member_id',$member_id);
		$this->set('customer_type_list',$this->Parameters->GetParameterList(3));
		$this->set('Inspectionpdf',$this->Inspectionpdf);
		$this->set('memberApproved',$memberApproved);
		$this->set('TotalPvCapacity',$TotalPvCapacity);
		$this->set('ApplicationsMessage',$this->ApplicationsMessage);
		//$this->set("apply_online_model",$this->ApplyOnlines);
		$this->set("authority_account",$authority_account);
		$this->set("ApplicationRequestDelete",$this->ApplicationRequestDelete);
		$this->set("login_type",$login_type);
		$this->set('Couchdb',$this->Couchdb);
		$this->set('gridLevel',$gridLevel);
		$this->set('EndSTU',$EndSTU);
		$this->set('EndCTU',$EndCTU);
		$this->set('applicationCategory',$applicationCategory);
		$this->set('installer_id',$installer_id);
		$this->set('SEEGEOLOCATION',$this->Applications->SEEGEOLOCATION);
		//$this->set('memberRoles',$this->MemberRoles);
		$this->set('developerApplicationQuery',$this->DeveloperApplicationQuery);
		$this->set('member_id',$member_id);
		$this->set('customer_id',$customer_id);
		$this->set('transferApplicationList',array_values($transferApplicationList));
		$this->set("TransferDevPermissionApp",$this->TransferDeveloperPermission);
		$this->set('TransferDeveloperApplicationQuery',$this->TransferDeveloperApplicationQuery);
		
	}
	/**
	 * VarifyOtp
	 * Behaviour : public
	 * @param : post parameter encoded applicaiton id and otp
	 * @defination : Method is use to verify otp of application.
	 */
	public function VarifyOtp()
	{
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['appid'])?$this->request->data['appid']:0);
		$otp 				= (isset($this->request->data['otp'])?$this->request->data['otp']:'');
		if(empty($id) || empty($otp)) {
			$ErrorMessage 	= "Please Enter OTP.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} else {
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->Applications->find('all',array('Fields'=>['otp','id'],'conditions'=>array('id'=>$id)))->first();
			if (!empty($applyOnlinesData)) {
				if ($this->request->is('post') || $this->request->is('put')) {
					if($otp == $applyOnlinesData->otp) {
						if(!empty($applyOnlinesData->otp_created_date))
						{
							$otp_created_date 	= strtotime($applyOnlinesData->otp_created_date);
							$current_date 		= strtotime($this->NOW());
							$datediff 			= ($current_date - $otp_created_date);
							if(($datediff/(60)) > OTP_VALIDITY_TIME)
							{
								$ErrorMessage 	= "OTP has been expired. Click on Resend OTP button in order to get new OTP.";
								$success 		= 0;
								$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
								$this->ApiToken->SetAPIResponse('success',$success);
								echo $this->ApiToken->GenerateAPIResponse();
								exit;
							}
						}
						$application_status = $this->ApplicationStages->APPLICATION_PENDING;
						$customer_id 		= $this->Session->read("Customers.id");
						$this->Applications->updateAll(array('application_status'=>$application_status,'otp_verified_status'=>1,'modified'=>$this->NOW(),'modified_by'=>$customer_id),array('id'=>$id));
						
						$this->ApplicationStages->saveStatus($id,$this->ApplicationStages->APPLICATION_PENDING,$customer_id,'');
						//$this->SendApplicationLetterToCustomer($id);

						$this->Flash->set('OTP Verified successfully.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
						$ErrorMessage 	= "OTP Verified successfully.";
						$success 		= 1;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					} else {
						$ErrorMessage 	= "Error while otp verification.";
						$success 		= 0;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					}
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate form details.";
				$success 		= 0;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	/**
	 * resend_otp
	 * Behaviour : public
	 * @param : app_id encoded applicaiton id 
	 * @defination : Method is use to resend otp of application.
	 */
	public function resend_otp($app_id)
	{
		$application_id    	= intval(decode($app_id));
		$ApplicationData 	= $this->Applications->find('all',array('conditions'=>array('id'=>$application_id)))->first();
		$ses_customer_type 	= $this->Session->read('Customers.customer_type');
		$is_installer 		= false;
		if ($ses_customer_type == "installer") {
			$is_installer 	= true;
		}
		if(!empty($ApplicationData))
		{
			$sms_mobile = $ApplicationData->authority_mobile;

			$sms_message =str_replace('##application_no##',$ApplicationData->application_no, OTP_RESEND);
			$this->Applications->SendSMSActivationCode($ApplicationData->id,$sms_mobile,$sms_message,'OTP_RESEND');
			
			$this->Flash->set('OTP Resend successfully.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
			$this->redirect(URL_HTTP.'applications-list');
		}
	}
	/**
	 * UploadDocument
	 * Behaviour : public
	 * @param : application_id encoded applicaiton id 
	 * @defination : Method is use to upload signed document.
	 */
	public function UploadDocument()
	{
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['application_id'])?$this->request->data['application_id']:0);
		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} else {
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->Applications->viewApplication($id);
			$customer_id 			= $this->Session->read("Customers.id");
			if (!empty($applyOnlinesData) && !empty($customer_id)) {
				if ($this->request->is('post')) {
					$ApplicationsDocsEntity = $this->ApplicationsDocs->newEntity();
					if(!empty($this->request->data['file']['name']))
					{
						$prefix_file 	= '';
						$name 			= $this->request->data['file']['name'];
						$ext 			= substr(strtolower(strrchr($name, '.')), 1);
						$file_name 		= $prefix_file.date('Ymdhms').rand();

						$uploadPath 	= APPLICATIONS_PATH.$id.'/';
						if(!file_exists(APPLICATIONS_PATH.$id)) {
							@mkdir(APPLICATIONS_PATH.$id, 0777);
						}
						$file_location 	= WWW_ROOT.$uploadPath.'a_doc'.'_'.$file_name.'.'.$ext;
						if(move_uploaded_file($this->request->data['file']['tmp_name'],$file_location))
						{
							$couchdbId 		= $this->Couchdb->saveData($uploadPath,$file_location,$prefix_file,'a_doc_'.$file_name.'.'.$ext,$this->Session->read('Customers.id'),'Signed_Doc');
							$ApplicationsDocsEntity->couchdb_id			= $couchdbId;
							$ApplicationsDocsEntity->application_id		= $id;
							$ApplicationsDocsEntity->file_name        	= 'a_doc'.'_'.$file_name.'.'.$ext;
							$ApplicationsDocsEntity->doc_type         	= 'Signed_Doc';
							$ApplicationsDocsEntity->title            	= 'Upload_Document';
							$ApplicationsDocsEntity->created          	= $this->NOW();
							$ApplicationsDocsEntity->created_by         = $customer_id;
							$application_status 						= $this->ApplicationStages->APPLICATION_SUBMITTED;
							$this->Applications->updateAll(array('application_status'=>$application_status,'modified'=>$this->NOW(),'modified_by'=>$customer_id),array('id'=>$id));
							
							$this->ApplicationStages->saveStatus($id,$this->ApplicationStages->APPLICATION_SUBMITTED,$customer_id,'');
						}
					}

					if($this->ApplicationsDocs->save($ApplicationsDocsEntity)) {
						//$this->SendApplicationLetterToCustomer($id);
						$ErrorMessage 	= "Upload Document Succesfully.";
						$success 		= 1;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					} else {
						$ErrorMessage 	= "Error while uploading document.";
						$success 		= 0;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					}
					
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate form details.";
				$success 		= 0;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	/**
	* view
	* Behaviour : Public
	* @defination : Method is use to view installer
	*/
	public function downloadpdf($id = null)
	{
		if(!empty($this->Session->read('Members.member_type')))
		{
			$customerId = $this->Session->read("Members.id");
		}
		else
		{
			$customerId = $this->Session->read("Customers.id");
		}

		if(empty($customerId))
		{
			return $this->redirect('/home');
		}
		$application_data = $this->Applications->generateApplicationPdf($id);
		if(empty($application_data)) {
			$this->Flash->error('Please Select Valid Application.');
			return $this->redirect('home');
		}
	}
	/**
	 * document_verify
	 * Behaviour : Public
	 * @defination : Method is use to verify application's document
	 */
	public function document_verify($application_id='')
	{
		if(!empty($application_id))
		{
			$app_id = intval(decode($application_id));
			if($app_id>0)
			{
				$applyOnlinesData   = $this->Applications->viewApplication($app_id);
				$member_id 			= $this->Session->read("Members.id");
				$this->ApplicationStages->saveStatus($app_id,$this->ApplicationStages->DOCUMENT_VERIFIED,$member_id,'');
				$applyOnlinesData->application_status 	= $this->ApplicationStages->APPROVED_FROM_GEDA;

				$this->ApplicationStages->saveStatus($app_id,$this->ApplicationStages->APPROVED_FROM_GEDA,$member_id,'');
				$registration_no 						= $this->Applications->GenerateRegistrationNo($applyOnlinesData);
				
				$this->Applications->updateAll(array('registration_no'=>$registration_no),array('id'=>$applyOnlinesData->id));

				$sms_text 			= str_replace('##application_no##',$applyOnlinesData->application_no,DOC_VERIFIED);
				if(!empty($applyOnlinesData->mobile))
				{
					$this->ApplyOnlines->sendSMS($app_id,$applyOnlinesData->mobile,$sms_text,'DOC_VERIFIED');
				}

				$subject 			= "[REG:Application No. ".$applyOnlinesData->application_no."] Verification of Documents";
				$CUSTOMER_NAME 		= trim($applyOnlinesData->name_of_applicant);
				$EmailVars 			= array("CUSTOMER_NAME"=>$CUSTOMER_NAME);

				if(!empty($applyOnlinesData->email))
				{
					$email 					= new Email('default');
					$email->profile('default');
					$email->viewVars($EmailVars);
					$message_send 			= $email->template('re_document_verification', 'default')
							->emailFormat('html')
							->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
							->to($applyOnlinesData->email)
							->subject(Configure::read('EMAIL_ENV').$subject)
							->send();
				}
				$this->Flash->set('Application documents verified successfully.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
				$this->redirect('/applications-list');
			}
		}
		else
		{
			$this->redirect('/applications-list');
		}
	}
	/**
	* downloadRegistrationPdf
	* Behaviour : Public
	* @defination : Method is use to view installer
	*/
	public function downloadRegistrationPdf($id = null)
	{
		if(!empty($this->Session->read('Members.member_type')))
		{
			$customerId = $this->Session->read("Members.id");
		}
		else
		{
			$customerId = $this->Session->read("Customers.id");
		}

		if(empty($customerId))
		{
			return $this->redirect('/home');
		}
		$application_data = $this->Applications->generateRegistrationPdf($id);
		if(empty($application_data)) {
			$this->Flash->error('Please Select Valid Application.');
			return $this->redirect('home');
		}
	}
	/**
	* downloadRegistrationPdf
	* Behaviour : Public
	* @defination : Method is use to view installer
	*/
	public function downloadOpenAccessRegistrationPdf($id = null)
	{
		if(!empty($this->Session->read('Members.member_type')))
		{
			$customerId = $this->Session->read("Members.id");
		}
		else
		{
			$customerId = $this->Session->read("Customers.id");
		}

		if(empty($customerId))
		{
			return $this->redirect('/home');
		}
		$application_data = $this->Applications->generateOpenAccessRegistrationPdf($id);
		if(empty($application_data)) {
			$this->Flash->error('Please Select Valid Application.');
			return $this->redirect('home');
		}
	}
	/**
	* downloadReApplicationPdf
	* Behaviour : Public
	* @defination : Method is use to view installer
	*/
	public function downloadReApplicationPdf($id = null)
	{
		if(!empty($this->Session->read('Members.member_type')))
		{
			$customerId = $this->Session->read("Members.id");
		}
		else
		{
			$customerId = $this->Session->read("Customers.id");
		}

		if(empty($customerId))
		{
			return $this->redirect('/home');
		}
		$application_data = $this->Applications->generateRePaymentReceiptPdf($id);
		if(empty($application_data)) {
			$this->Flash->error('Please Select Valid Application.');
			return $this->redirect('home');
		}
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
	public function view($id = null)
	{

		$customer_id 		= $this->Session->read("Customers.id");
		$member_id 			= $this->Session->read("Members.id");
		$is_member          = false;
		if(!empty($member_id)){
			$is_member      = true;
		}
		if(empty($id)) {
			$this->Flash->error('Please Select Valid Installer.');
			return $this->redirect('home');
		} else {
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->Applications->viewApplication($id);
			$applyOnlinesDataDocList= $this->ApplicationsDocs->find("all",['conditions'=>['application_id'=>$id,'doc_type in'=>array('others','Signed_Doc')]])->toArray();

			$applyOnlinesDataDocListStage1= $this->ApplicationsDocs->find("all",['conditions'=>['application_id'=>$id,'doc_type in'=>array('STUstep1','CTUstep1')]])->toArray();
			$applyOnlinesDataDocListStage2= $this->ApplicationsDocs->find("all",['conditions'=>['application_id'=>$id,'doc_type in'=>array('STUstep2','CTUstep2')]])->toArray();
			$applyOnlinesDataDocListTP= $this->ApplicationsDocs->find("all",['conditions'=>['application_id'=>$id,'doc_type '=>'TPfile']])->first();
			$applyOnlinesDataDocListPC= $this->ApplicationsDocs->find("all",['conditions'=>['application_id'=>$id,'doc_type '=>'ProjectCommissioning']])->first();


			$Applyonlinprofile  	= $this->ApplicationsDocs->find('all',['conditions'=>['application_id'=>$id,'doc_type'=>'profile']])->first();
			$connectivitystage_data =$this->ApplicationConnectivityStep->find('all',['conditions'=>['application_id'=>$id]])->first();
		}
		$injectionLevel 		= $this->ApiToken->arrInjectionLevel;
		$gridLevel 				= $this->ApiToken->arrGridLevel;
		$EndSTU 				= $this->ApiToken->arrEndSTU;
		$EndCTU 				= $this->ApiToken->arrEndCTU;
		$discom_list = $this->BranchMasters->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['BranchMasters.parent_id'=>'0']])->toArray();
		$payumoney_data = $this->ReApplicationPayment->find('all',['fields'=>array('ReApplicationPayment.transaction_id','ReApplicationPayment.payment_date'),'join'=>[
						'rsp' => [
							'table' => 're_success_payment',
							'type' => 'INNER',
							'conditions' => ['ReApplicationPayment.id = rsp.payment_id']
						]]])->where(['rsp.application_id' => $id])->first();
		$transaction_id='';
		$payment_date='';
		if(!empty($payumoney_data))
		{
			$transaction_id=($payumoney_data->transaction_id);
			$payment_date=(!empty($payumoney_data->payment_date) ? date(LIST_DATE_FORMAT,strtotime($payumoney_data->payment_date)) : '');
		}
		$FeasibilityData 		= $this->FesibilityReport->find("all",['conditions'=>['application_id'=>$id]])->first();
		$member_type 	= $this->Session->read('Members.member_type');
		$page_cur 		= '1';
		if($this->Session->check("Customers.Page"))
		{
			$page_cur 	= $this->Session->read("Customers.Page");
		}
		
		$memberViewPanAdhar 		= in_array($member_id, $this->ApplyOnlines->ALLOWED_APPROVE_GEDAIDS) ? '1' : '0';
		$EndUseDetails 				= $this->EndUseElectricity->find('all',array('conditions'=>array('application_id'=>$id)))->first();
		$district 					= $this->DistrictMaster->find("all",['conditions'=>['id'=>$applyOnlinesData->project_district]])->first();
		$applicationCategory 		= $this->ApplicationCategory->find("all",['conditions'=>['id'=>$applyOnlinesData->application_type]])->first();

		$totalModulenos				= $this->ApplicationHybridAdditionalData->getwinddatasum($id,1);
		$totalInverternos			= $this->ApplicationHybridAdditionalData->getwinddatasum($id,2);
		
		$openAccessDeveloperApp 	= $this->OpenAccessApplicationDeveloperPermission->find('all',array('fields'=>['id'],'conditions'=>array('application_id'=>$id)))->first();
		$windDeveloperApp 			= $this->WindApplicationDeveloperPermission->find('all',array('fields'=>['id','app_order'],'conditions'=>array('application_id'=>$id)))->toArray();
		$transferDeveloperApp 		= $this->TransferDeveloperPermission->find('all',array('fields'=>['id','app_order'],'conditions'=>array('application_id'=>$id)))->toArray();

		$this->set("applicationCategory",$applicationCategory);
		$this->set("district",$district);
		$this->set("APPLY_ONLINE_MAIN_STATUS",$this->ApplicationStages->apply_online_main_status);
		$this->set("APPLY_ONLINE_MAIN_STATUS_TP",$this->ApplicationStages->apply_online_main_status_TP);
		$this->set("APPLY_ONLINE_MAIN_STATUS_CTU",$this->ApplicationStages->apply_online_main_status_CTU);
		$this->set("APPLY_ONLINE_MAIN_STATUS_STU",$this->ApplicationStages->apply_online_main_status_STU);
		$this->set("pageTitle","Application View");
		$this->set("applyOnlinesDataDocList",$applyOnlinesDataDocList);
		$this->set("applyOnlinesDataDocListStage1",$applyOnlinesDataDocListStage1);
		$this->set("applyOnlinesDataDocListStage2",$applyOnlinesDataDocListStage2);
		$this->set("applyOnlinesDataDocListTP",$applyOnlinesDataDocListTP);
		$this->set("applyOnlinesDataDocListPC",$applyOnlinesDataDocListPC);
		$this->set("connectivitystage_data",$connectivitystage_data);
		$this->set("discom_list",$discom_list);
		$this->set('ApplyOnlines',$applyOnlinesData);
		$this->set('transaction_id',$transaction_id);
		$this->set('payment_date',$payment_date);
		$this->set('Applyonlinprofile',$Applyonlinprofile);
		$this->set('member_type',$member_type);
		$this->set("MemberTypeDiscom",$this->Members->member_type_discom);
		$this->set("APPLY_ONLINE_GUJ_STATUS",$this->ApplicationStages->apply_online_guj_status);
		$this->set("MStatus",$this->ApplicationStages);
		$this->set('logged_in_id',$this->Session->read('Customers.id'));
		$this->set('member_type',$member_type);
		$this->set('is_member',$is_member);
		$this->set('FeasibilityData',$FeasibilityData);
		$this->set('page_cur',$page_cur);
		$this->set('encode_id',$encode_id);
		$this->set('memberViewPanAdhar',$memberViewPanAdhar);
		$this->set("payment_on",Configure::read('PAYUMONEY_PAYMENT'));
		$this->set("injectionLevel",$injectionLevel);
		$this->set("gridLevel",$gridLevel);
		$this->set("EndSTU",$EndSTU);
		$this->set("EndCTU",$EndCTU);
		$this->set("EndUseDetails",$EndUseDetails);
		$this->set('Couchdb',$this->Couchdb);
		$this->set('totalModulenos',$totalModulenos);
		$this->set('totalInverternos',$totalInverternos);
		$this->set('openAccessDeveloperApp',$openAccessDeveloperApp);
		$this->set('windDeveloperApp',$windDeveloperApp);
		$this->set('transferDeveloperApp',$transferDeveloperApp);
	}
	/**
	 * mapDiscom
	 * Behaviour : public
	 * @param : application_id encoded applicaiton id 
	 * @defination : Method is use to assign discom to perticular application.
	 */
	public function mapDiscom()
	{
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['application_id'])?$this->request->data['application_id']:0);
		$discom 			= (isset($this->request->data['discom'])?$this->request->data['discom']:0);
		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} else {
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->Applications->viewApplication($id);
			$customer_id 			= $this->Session->read("Customers.id");
			if (!empty($applyOnlinesData) && !empty($customer_id) && !empty($discom)) {
				if ($this->request->is('post')) {
					$this->Applications->updateAll(array('discom'=>$discom,'modified'=>$this->NOW(),'modified_by'=>$customer_id),array('id'=>$id));

					$ErrorMessage 	= "Update Discom Succesfully.";
					$success 		= 1;
					$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
					$this->ApiToken->SetAPIResponse('success',$success);
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate form details.";
				$success 		= 0;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	public function ReplayMessage()
	{
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['app_id'])?$this->request->data['app_id']:0);
		$message 			= (isset($this->request->data['message'])?$this->request->data['message']:'');

		if(empty($id) || empty($message)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} else {
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->Applications->viewApplication($id);
			if (!empty($applyOnlinesData)) {
				if ($this->request->is('post') || $this->request->is('put')) {
					$ApplicationsMessage 				= $this->ApplicationsMessage->GetLastMessageByApplicationForClaim($id,1);
					$customer_type 						= $this->Session->read('Customers.customer_type');
					$customer_id          				= $this->Session->read("Customers.id");
					$member_id          				= $this->Session->read("Members.id");
					$member_type 						= $this->Session->read('Members.member_type');
					$browser 							= isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:"-";
					$ApplicationsMessageEntity						= $this->ApplicationsMessage->newEntity();
					$ApplicationsMessageEntity->application_id 		= $id;
					$ApplicationsMessageEntity->message 			= strip_tags($message);
					$ApplicationsMessageEntity->user_type 			= !empty($customer_type)?$customer_type:0;
					$ApplicationsMessageEntity->user_id 			= !empty($customer_id)?$customer_id:$member_id;
					$ApplicationsMessageEntity->ip_address 			= $this->IP_ADDRESS;
					$ApplicationsMessageEntity->created 			= $this->NOW();
					$ApplicationsMessageEntity->browser_info 		= json_encode($browser);
					$ApplicationsMessageEntity->application_status 	= $ApplicationsMessage['application_status'];
					if (isset($ApplicationsMessage['last_message_id']) && !empty($ApplicationsMessage['last_message_id']))
					{
						$ApplicationsMessageEntity->reply_msg_id 	= decode($ApplicationsMessage['last_message_id']);
						$ApplicationsMessageEntity->for_claim		= 0;//2;
					}
					if($this->ApplicationsMessage->save($ApplicationsMessageEntity)) {
						$applyid = $applyOnlinesData->id;
						if(!empty($applyid)) {
							$data 				= $this->Applications->get($applyid);
							$data->query_sent 	= '0';
							$data->query_date 	= NULL;
							$data->modified 	= date('Y-m-d H:i:s');
							$this->Applications->save($data);
						}

						/** Update Subsidy Claim Messge as Replied By Client */
						if (isset($ApplicationsMessage['last_message_id']) && !empty($ApplicationsMessage['last_message_id']))
						{
							$MessageDetails 	= $this->ApplicationsMessage->get(decode($ApplicationsMessage['last_message_id']));
							$Message_For 		= 0;
							if (!empty($MessageDetails)) {
								$Message_For 				= $MessageDetails->user_id;
								$MessageDetails->for_claim 	= 0; //2
								$this->ApplicationsMessage->save($MessageDetails);
							}
						}
						/** Update Subsidy Claim Messge as Replied By Client */
						//$this->ApplyOnlines->SendEmailToCustomer($id,$ApplicationsMessageEntity->id);
						$ErrorMessage 	= "Message sent successfully.";
						$success 		= 1;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					} else {
						$ErrorMessage 	= "Error while sending message.";
						$success 		= 0;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					}
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate form details.";
				$success 		= 0;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	/**
	 * SendMessage
	 * Behaviour : Public
	 * @defination : Method is use to SendMessage To Customer or Admin
	 */
	public function SendMessage()
	{
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['appid'])?$this->request->data['appid']:0);
		$message 			= (isset($this->request->data['messagebox'])?$this->request->data['messagebox']:'');
		$for_claim 			= (isset($this->request->data['for_claim'])?decode($this->request->data['for_claim']):0);
		
		if(empty($id) || empty($message)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} else {
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->Applications->viewApplication($id);

			if (!empty($applyOnlinesData)) {
				if ($this->request->is('post') || $this->request->is('put')) {
					$customer_type 						= $this->Session->read('Customers.customer_type');
					$customer_id          				= $this->Session->read("Customers.id");
					$member_id          				= $this->Session->read("Members.id");
					$member_type 						= $this->Session->read('Members.member_type');

					$browser 										= isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:"-";
					$ApplicationsMessageEntity						= $this->ApplicationsMessage->newEntity();
					$ApplicationsMessageEntity->application_id 		= $id;
					$ApplicationsMessageEntity->message 			= strip_tags($message);
					$ApplicationsMessageEntity->user_type 			= !empty($customer_type)?0:$member_type;
					$ApplicationsMessageEntity->user_id 			= !empty($customer_id)?$customer_id:$member_id;
					$ApplicationsMessageEntity->ip_address 			= $this->IP_ADDRESS;
					$ApplicationsMessageEntity->for_claim 			= $for_claim;
					$ApplicationsMessageEntity->application_status 	= $applyOnlinesData->application_status;
					$ApplicationsMessageEntity->created 			= $this->NOW();
					$ApplicationsMessageEntity->browser_info 		= json_encode($browser);
					
					if($this->ApplicationsMessage->save($ApplicationsMessageEntity)) {

						//$this->Applications->SendEmailToCustomer($id,$ApplicationsMessageEntity->id);
						$ErrorMessage 	= "Message sent successfully.";
						$success 		= 1;
						$applyid 		= $applyOnlinesData->id;
						if(!empty($applyid)) {
							$data 				= $this->Applications->get($applyid);
							$data->query_sent	= '1';
							$data->query_date 	= date('Y-m-d H:i:s');
							$data->modified 	= date('Y-m-d H:i:s');
							$this->Applications->save($data);
						}
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					} else {
						$ErrorMessage 	= "Error while sending message.";
						$success 		= 0;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					}
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate form details.";
				$success 		= 0;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	/**
	 * GetMessages
	 * Behaviour : Public
	 * @defination : Method is use to GetMessages per application
	 */
	public function GetMessages($id)
	{
		$this->autoRender 	= false;
		$ApplicationsMessage = array();
		if(!empty($id)) {
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$member_id      = $this->Session->read("Members.id");
			$ApplicationsMessage 	= $this->ApplicationsMessage->GetAllMessagesById($id,$member_id);
		}
		$view 			= new View($this->request,$this->response);
		$view->layout 	= 'empty';
		$view->set('ApplicationsMessage', $ApplicationsMessage);
		$html = $view->render('/Applications/get_messages');
		$this->ApiToken->SetAPIResponse('html',$html);
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	/**
	 * OtherDocument
	 * Behaviour : Public
	 * @defination : Method is use to upload other document.
	 */
	public function OtherDocument()
	{
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['other_application_id'])?$this->request->data['other_application_id']:0);
		$message 				= (isset($this->request->data['message'])?$this->request->data['message']:0);
		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} else {
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applicationData 		= $this->Applications->viewApplication($id);
			if (!empty($applicationData)) {
				if ($this->request->is('post')) {
						$ApplyonlinDocsEntity = $this->ApplicationsDocs->newEntity();
						if(!empty($this->request->data['file']['name']))
						{
							if(($this->request->data["file"]["size"] > 1000000)){
								$ErrorMessage 	= "File Size Should be less then 1MB.";
								$success 		= 0;
								$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
								$this->ApiToken->SetAPIResponse('success',$success);
								echo $this->ApiToken->GenerateAPIResponse();
								exit;
							}
							$prefix_file = '';
							$name=$this->request->data['file']['name'];
							$ext 			= substr(strtolower(strrchr($name, '.')), 1);
							$file_name 		= $prefix_file.date('Ymdhms').rand();

							$uploadPath = APPLICATIONS_PATH.$id.'/';
							if(!file_exists(APPLICATIONS_PATH.$id)) {
								@mkdir(APPLICATIONS_PATH.$id, 0777);
							}
							$file_location 	= WWW_ROOT.$uploadPath.'doc'.'_'.$file_name.'.'.$ext;
							if(move_uploaded_file($this->request->data['file']['tmp_name'],$file_location))
							 {
							 	$couchdbId 		= $this->Couchdb->saveData($uploadPath,$file_location,$prefix_file,'doc_'.$file_name.'.'.$ext,$this->Session->read('Customers.id'),'others');
								$ApplyonlinDocsEntity->couchdb_id		= $couchdbId;
								$ApplyonlinDocsEntity->application_id	= $id;
								$ApplyonlinDocsEntity->file_name        = 'doc'.'_'.$file_name.'.'.$ext;
								$ApplyonlinDocsEntity->doc_type         = 'others';
								$ApplyonlinDocsEntity->title            = $message;
								$ApplyonlinDocsEntity->created          = $this->NOW();
							}
						}
						if($this->ApplicationsDocs->save($ApplyonlinDocsEntity)) {
							$ErrorMessage 	= "Upload Other Document Succesfully.";
							$success 		= 1;
							$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
							$this->ApiToken->SetAPIResponse('success',$success);
						} else {
							$ErrorMessage 	= "Error while uploading document.";
							$success 		= 0;
							$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
							$this->ApiToken->SetAPIResponse('success',$success);
						}
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate form details.";
				$success 		= 0;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	public function RemoveHybrid()
	{

		$this->autoRender 	= false;
		$ids 				= (isset($this->request->data['id'])?$this->request->data['id']:0);
		$application_id 	= (isset($this->request->data['application_id'])?$this->request->data['application_id']:0);
		$capacity 			= (isset($this->request->data['capacity'])?$this->request->data['capacity']:0);
		$capacity_type 		= (isset($this->request->data['capacity_type'])?$this->request->data['capacity_type']:0);
		$hybrid_capacity 	= $this->Applications->find('all',array('conditions'=>['id'=>$application_id]))->first();
		if(empty($ids)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} else {
			
			if($capacity_type == 3){
				$remove_capacity  = ($hybrid_capacity->total_capacity - $capacity);
				$this->Applications->updateAll(array('total_capacity'=>$remove_capacity,),array('id'=>$application_id));
			}
			if($capacity_type == 1){
				$remove_capacity  = ($hybrid_capacity->module_hybrid_capacity - $capacity);
				$this->Applications->updateAll(array('module_hybrid_capacity'=>$remove_capacity),array('id'=>$application_id));
			}
			if($capacity_type == 2){
				$remove_capacity  = ($hybrid_capacity->inverter_hybrid_capacity - $capacity);
				$remove_total_capacity  = ($hybrid_capacity->total_wind_hybrid_capacity - $capacity);
				$this->Applications->updateAll(array('inverter_hybrid_capacity'=>$remove_capacity,'total_wind_hybrid_capacity'=>$remove_total_capacity),array('id'=>$application_id));
			}
			
			$this->ApplicationHybridAdditionalData->deleteAll(['id' => $ids]);
			$success 		= 1;
			//$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	public function RemoveWind()
	{
		$this->autoRender 	= false;
		$ids 				= (isset($this->request->data['id'])?$this->request->data['id']:0);
		$application_id 	= (isset($this->request->data['application_id'])?$this->request->data['application_id']:0);
		$capacity 			= (isset($this->request->data['capacity'])?$this->request->data['capacity']:0);
		$capacity_type 		= (isset($this->request->data['capacity_type'])?$this->request->data['capacity_type']:0);
		$nos 				= (isset($this->request->data['nos'])?$this->request->data['nos']:0);
		$total_capacity 	= (isset($this->request->data['total_capacity'])?$this->request->data['total_capacity']:0);

		$wind_data 	= $this->Applications->find('all',array('conditions'=>['id'=>$application_id]))->first();
		if(empty($ids)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} else {
			
			if($capacity_type == 3){
				$remove_nos  			= ($wind_data->wtg_no - $nos);
				$remove_capacity_wtg  	= ($wind_data->capacity_wtg - $capacity);
				$remove_total_capacity  = ($wind_data->total_capacity - $total_capacity);
				$this->Applications->updateAll(array('wtg_no'=>$remove_nos,'capacity_wtg'=>$remove_capacity_wtg,'total_capacity'=>$remove_total_capacity),array('id'=>$application_id));
			}
			
			$this->ApplicationHybridAdditionalData->deleteAll(['id' => $ids]);
			$success 		= 1;
			//$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	/**
	 * ConnectivityStep1Document
	 * Behaviour : Public
	 * @defination : Method is use to upload other document.
	 */
	public function TPDocument()
	{
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['TP_application_id'])?$this->request->data['TP_application_id']:0);
		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} else {
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->Applications->viewApplication($id);
			$customer_id 			= $this->Session->read("Customers.id");
			$member_id 			= $this->Session->read("Members.id");
			if (!empty($applyOnlinesData) && (!empty($customer_id) || !empty($member_id))) {
				if ($this->request->is('post')) {
					if((empty($this->request->data['TPfile']['name'])) ) {
						$ErrorMessage 	= "Please Upload file.";
						$success 		= 0;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					} else {
						if(!empty($this->request->data['TPfile']['name']))
						{	$ApplicationsDocsEntity = $this->ApplicationsDocs->newEntity();
							$prefix_file 	= '';
							$name 			= $this->request->data['TPfile']['name'];

							$ext 			= substr(strtolower(strrchr($name, '.')), 1);

							$file_name 		= $prefix_file.date('Ymdhms').rand();
							$uploadPath 	= APPLICATIONS_PATH.$id.'/';
							if(!file_exists(APPLICATIONS_PATH.$id)) {
								@mkdir(APPLICATIONS_PATH.$id, 0777);
							}
							$file_location 	= WWW_ROOT.$uploadPath.'TPfile'.'_'.$file_name.'.'.$ext;
							if(move_uploaded_file($this->request->data['TPfile']['tmp_name'],$file_location))
							{

								$couchdbId 		= $this->ReCouchdb->saveData($uploadPath,$file_location,$prefix_file, 'TPfile_'.$file_name.'.'.$ext,$this->Session->read('Customers.id'),'TPfile');
								$ApplicationsDocsEntity->couchdb_id			= $couchdbId;
								$ApplicationsDocsEntity->application_id		= $id;
								$ApplicationsDocsEntity->file_name        	= 'TPfile'.'_'.$file_name.'.'.$ext;
								$ApplicationsDocsEntity->doc_type         	= 'TPfile';
								$ApplicationsDocsEntity->title            	= $this->request->data['TP'];
								$ApplicationsDocsEntity->created          	= $this->NOW();
								$ApplicationsDocsEntity->created_by         = $customer_id;
								$application_status 						= $this->ApplicationStages->TFR;
								$this->ApplicationsDocs->deleteAll(['application_id' => $id,'doc_type'=>'STUstep1']);
							}
							$this->ApplicationsDocs->save($ApplicationsDocsEntity);
						}
						if($this->ApplicationsDocs->save($ApplicationsDocsEntity)) {
							$this->Applications->updateAll(array('application_status'=>$application_status,'modified'=>$this->NOW(),'modified_by'=>$customer_id),array('id'=>$id));
							$this->ApplicationStages->saveStatus($id,$this->ApplicationStages->TFR,$customer_id,'');
							//$this->ApplicationStages->saveStatus($id,$this->ApplicationStages->CONNECTIVITY_STEP1,$customer_id,'');
							//$this->ApplicationStages->saveStatus($id,$this->ApplicationStages->CONNECTIVITY_STEP2,$customer_id,'');
							$ErrorMessage 	= "Upload Document Succesfully.";
							$success 		= 1;
							$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
							$this->ApiToken->SetAPIResponse('success',$success);
						} else {
							$ErrorMessage 	= "Error while uploading document.";
							$success 		= 0;
							$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
							$this->ApiToken->SetAPIResponse('success',$success);
						}
					}
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate form details.";
				$success 		= 0;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	/**
	 * STUstep2Document
	 * Behaviour : Public
	 * @defination : Method is use to upload other document.
	 */
	public function STUstep2Document()
	{
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['STUstep2_application_id'])?$this->request->data['STUstep2_application_id']:0);
		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} else {
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->Applications->viewApplication($id);
			$customer_id 			= $this->Session->read("Customers.id");
			$member_id 			= $this->Session->read("Members.id");
			if (!empty($applyOnlinesData) && (!empty($customer_id) || !empty($member_id))) {
				if ($this->request->is('post')) {
					//$ApplicationsDocsEntity = $this->ApplicationsDocs->newEntity();
					if((empty($this->request->data['file1']['name'])) || (empty($this->request->data['grid_connectivity_capacity']))  ) {
						$ErrorMessage 	= "Please upload at least one file and add capacity .";
						$success 		= 0;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					} else {
						if(!empty($this->request->data['grid_connectivity_capacity']) && !empty($this->request->data['grid_connectivity_capacity'])){

							$this->ApplicationConnectivityStep->updateAll(array('grid_connectivity_capacity'=>$this->request->data['grid_connectivity_capacity']),array('application_id'=>$id));
						}
						$counter 					= 1;
						$file_array = array('file1'=>'title1','file2'=>'title2','file3'=>'title3','file4'=>'title4');
					
						foreach ($file_array as $key => $value) {
							$ApplicationsDocsEntity = $this->ApplicationsDocs->newEntity();
							//$file_name_prefix = 'STUstep2_'.$counter ;
							if(!empty($this->request->data[$key]['name']))
							{
								$prefix_file 	= '';
								$name 			= $this->request->data[$key]['name'];
								$ext 			= substr(strtolower(strrchr($name, '.')), 1);
								$file_name 		= $prefix_file.date('Ymdhms').rand();
								$uploadPath 	= APPLICATIONS_PATH.$id.'/';
								if(!file_exists(APPLICATIONS_PATH.$id)) {
									@mkdir(APPLICATIONS_PATH.$id, 0777);
								}
								$file_location 	= WWW_ROOT.$uploadPath.'STUstep2'.'_'.$file_name.'.'.$ext;
								if(move_uploaded_file($this->request->data[$key]['tmp_name'],$file_location))
								{

									$couchdbId 		= $this->ReCouchdb->saveData($uploadPath,$file_location,$prefix_file, 'STUstep2_'.$file_name.'.'.$ext,$this->Session->read('Customers.id'),'STUstep2');
									$ApplicationsDocsEntity->couchdb_id			= $couchdbId;
									$ApplicationsDocsEntity->application_id		= $id;
									$ApplicationsDocsEntity->file_name        	= 'STUstep2_'.$file_name.'.'.$ext;
									$ApplicationsDocsEntity->doc_type         	= 'STUstep2';
									$ApplicationsDocsEntity->title            	= $this->request->data[$value];
									$ApplicationsDocsEntity->created          	= $this->NOW();
									$ApplicationsDocsEntity->created_by         = $customer_id;
									$application_status 						= $this->ApplicationStages->CONNECTIVITY_STEP2;
									$this->Applications->updateAll(array('application_status'=>$application_status,'modified'=>$this->NOW(),'modified_by'=>$customer_id),array('id'=>$id));
									$this->ApplicationsDocs->save($ApplicationsDocsEntity);
								}
							} $counter++;
						}
						$application_doc 	= $this->ApplicationsDocs->find('all',array('conditions'=>array('application_id'=>$id,'doc_type'=>'STUstep2')))->first();
						if(!empty($application_doc)) {
							//$this->SendApplicationLetterToCustomer($id);
							
							$this->ApplicationStages->saveStatus($id,$this->ApplicationStages->CONNECTIVITY_STEP2,$customer_id,'');
							$ErrorMessage 	= "Upload Document Succesfully.";
							$success 		= 1;
							$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
							$this->ApiToken->SetAPIResponse('success',$success);
						}
						 else {
							$ErrorMessage 	= "Error while uploading document.";
							$success 		= 0;
							$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
							$this->ApiToken->SetAPIResponse('success',$success);
						}
					}	
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate form details.";
				$success 		= 0;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	
	/**
	 * STUStep1Document
	 * Behaviour : Public
	 * @defination : Method is use to upload drwaing file certificate.
	 */
	public function STUStep1Document()
	{	
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['STUStep1_application_id'])?$this->request->data['STUStep1_application_id']:0);

		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} 
		else{
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->Applications->viewApplication($id);
			$customer_id 			= $this->Session->read("Customers.id");
			$member_id 				= $this->Session->read("Members.id");
			if (!empty($applyOnlinesData) && (!empty($customer_id) || !empty($member_id))) {
				if ($this->request->is('post')) {
					//$ApplicationsDocsEntity = $this->ApplicationsDocs->newEntity();
					if((empty($this->request->data['connectivity_upload_file']['name'])) || (empty($this->request->data['bg_upload_file']['name'])) || (empty($this->request->data['stu_connectivity_date']))  || (empty($this->request->data['stu_validity_date']))  ) {
						$ErrorMessage 	= "Please select all the fields.";
						$success 		= 0;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					} else {
						if(!empty($this->request->data['stu_connectivity_date']) && !empty($this->request->data['stu_validity_date'])){
							$stu_connectivity_date = date('Y-m-d',strtotime($this->request->data['stu_connectivity_date']));
							$stu_validity_date = date('Y-m-d',strtotime($this->request->data['stu_validity_date']));
							$ApplicationConnectivityStepEntity = $this->ApplicationConnectivityStep->newEntity();
							$ApplicationConnectivityStepEntity->application_id		= $id;
							$ApplicationConnectivityStepEntity->connectivity_type   = 1;
							$ApplicationConnectivityStepEntity->Date_of_validity    = $stu_connectivity_date;
							$ApplicationConnectivityStepEntity->Date_of_connectivity = $stu_validity_date;
							$ApplicationConnectivityStepEntity->created          	= $this->NOW();
							$this->ApplicationConnectivityStep->deleteAll(['application_id' => $id]);
							$this->ApplicationConnectivityStep->save($ApplicationConnectivityStepEntity);
						}
						if(!empty($this->request->data['connectivity_upload_file']['name']))
						{	$ApplicationsDocsEntity = $this->ApplicationsDocs->newEntity();
							$prefix_file 	= '';
							$name 			= $this->request->data['connectivity_upload_file']['name'];

							$ext 			= substr(strtolower(strrchr($name, '.')), 1);

							$file_name 		= $prefix_file.date('Ymdhms').rand();
							$uploadPath 	= APPLICATIONS_PATH.$id.'/';
							if(!file_exists(APPLICATIONS_PATH.$id)) {
								@mkdir(APPLICATIONS_PATH.$id, 0777);
							}
							$file_location 	= WWW_ROOT.$uploadPath.'STUstep1'.'_'.$file_name.'.'.$ext;
							if(move_uploaded_file($this->request->data['connectivity_upload_file']['tmp_name'],$file_location))
							{

								$couchdbId 		= $this->ReCouchdb->saveData($uploadPath,$file_location,$prefix_file, 'STUstep1_'.$file_name.'.'.$ext,$this->Session->read('Customers.id'),'STUstep1');
								$ApplicationsDocsEntity->couchdb_id			= $couchdbId;
								$ApplicationsDocsEntity->application_id		= $id;
								$ApplicationsDocsEntity->file_name        	= 'STUstep1'.'_'.$file_name.'.'.$ext;
								$ApplicationsDocsEntity->doc_type         	= 'STUstep1';
								$ApplicationsDocsEntity->title            	= 'connectivity_upload_approval ';
								$ApplicationsDocsEntity->created          	= $this->NOW();
								$ApplicationsDocsEntity->created_by         = $customer_id;
								$application_status 						= $this->ApplicationStages->STU;
								$this->ApplicationsDocs->deleteAll(['application_id' => $id,'doc_type'=>'STUstep1']);
								
								
							}
							$this->ApplicationsDocs->save($ApplicationsDocsEntity);
						}
						if(!empty($this->request->data['bg_upload_file']['name']))
						{	$ApplicationsDocsEntity = $this->ApplicationsDocs->newEntity();
							$prefix_file 	= '';
							$name 			= $this->request->data['bg_upload_file']['name'];
							$ext 			= substr(strtolower(strrchr($name, '.')), 1);
							$file_name 		= $prefix_file.date('Ymdhms').rand();
							$uploadPath 	= APPLICATIONS_PATH.$id.'/';
							if(!file_exists(APPLICATIONS_PATH.$id)) {
								@mkdir(APPLICATIONS_PATH.$id, 0777);
							}
							$file_location 	= WWW_ROOT.$uploadPath.'STUstep1'.'_'.$file_name.'.'.$ext;
							if(move_uploaded_file($this->request->data['bg_upload_file']['tmp_name'],$file_location))
							{

								$couchdbId 		= $this->ReCouchdb->saveData($uploadPath,$file_location,$prefix_file, 'STUstep1_'.$file_name.'.'.$ext,$this->Session->read('Customers.id'),'STUstep1');
								$ApplicationsDocsEntity->couchdb_id			= $couchdbId;
								$ApplicationsDocsEntity->application_id		= $id;
								$ApplicationsDocsEntity->file_name        	= 'STUstep1'.'_'.$file_name.'.'.$ext;
								$ApplicationsDocsEntity->doc_type         	= 'STUstep1';
								$ApplicationsDocsEntity->title            	= 'BG Upload File ';
								$ApplicationsDocsEntity->created          	= $this->NOW();
								$ApplicationsDocsEntity->created_by         = $customer_id;
								$application_status 						= $this->ApplicationStages->STU;
								// /$this->ApplicationsDocs->deleteAll(['application_id' => $id,'doc_type'=>'STUstep1']);
								
								
							}
							$this->ApplicationsDocs->save($ApplicationsDocsEntity);
						}
						
						if($this->ApplicationsDocs->save($ApplicationsDocsEntity)) {
							$this->Applications->updateAll(array('application_status'=>$application_status,'modified'=>$this->NOW(),'modified_by'=>$customer_id),array('id'=>$id));
							$this->ApplicationStages->saveStatus($id,$this->ApplicationStages->STU,$customer_id,'');
							$ErrorMessage 	= "Upload Document Succesfully.";
							$success 		= 1;
							$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
							$this->ApiToken->SetAPIResponse('success',$success);
						} else {
							$ErrorMessage 	= "Error while uploading document.";
							$success 		= 0;
							$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
							$this->ApiToken->SetAPIResponse('success',$success);
						}
					}
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate form details.";
				$success 		= 0;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	/**
	 * STUStep1Document
	 * Behaviour : Public
	 * @defination : Method is use to upload drwaing file certificate.
	 */
	public function STUStep1newDocument()
	{	
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['STUStep1new_application_id'])?$this->request->data['STUStep1new_application_id']:0);

		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} 
		else{
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->Applications->viewApplication($id);
			$customer_id 			= $this->Session->read("Customers.id");
			$member_id 				= $this->Session->read("Members.id");
			if (!empty($applyOnlinesData) && (!empty($customer_id) || !empty($member_id))) {
				if ($this->request->is('post')) {
					//$ApplicationsDocsEntity = $this->ApplicationsDocs->newEntity();
					if((empty($this->request->data['stunew_file1']['name'])) ) {
						$ErrorMessage 	= "Please select all the fields.";
						$success 		= 0;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					} else {
						if(!empty($this->request->data['stunew_file1']['name']))
						{	
							$ApplicationConnectivityStepEntity = $this->ApplicationConnectivityStep->newEntity();
							$ApplicationConnectivityStepEntity->application_id		= $id;
							$ApplicationConnectivityStepEntity->connectivity_type   = 2;
							$ApplicationConnectivityStepEntity->Date_of_validity    = NULL;
							$ApplicationConnectivityStepEntity->Date_of_connectivity = NULL;
							$ApplicationConnectivityStepEntity->created          	= $this->NOW();
							$this->ApplicationConnectivityStep->deleteAll(['application_id' => $id]);
							$this->ApplicationConnectivityStep->save($ApplicationConnectivityStepEntity);

							$ApplicationsDocsEntity = $this->ApplicationsDocs->newEntity();
							$prefix_file 	= '';
							$name 			= $this->request->data['stunew_file1']['name'];

							$ext 			= substr(strtolower(strrchr($name, '.')), 1);

							$file_name 		= $prefix_file.date('Ymdhms').rand();
							$uploadPath 	= APPLICATIONS_PATH.$id.'/';
							if(!file_exists(APPLICATIONS_PATH.$id)) {
								@mkdir(APPLICATIONS_PATH.$id, 0777);
							}
							$file_location 	= WWW_ROOT.$uploadPath.'STUstep1'.'_'.$file_name.'.'.$ext;
							if(move_uploaded_file($this->request->data['stunew_file1']['tmp_name'],$file_location))
							{

								$couchdbId 		= $this->ReCouchdb->saveData($uploadPath,$file_location,$prefix_file, 'STUstep1_'.$file_name.'.'.$ext,$this->Session->read('Customers.id'),'STUstep1');
								$ApplicationsDocsEntity->couchdb_id			= $couchdbId;
								$ApplicationsDocsEntity->application_id		= $id;
								$ApplicationsDocsEntity->file_name        	= 'STUstep1'.'_'.$file_name.'.'.$ext;
								$ApplicationsDocsEntity->doc_type         	= 'STUstep1';
								$ApplicationsDocsEntity->title            	= 'stunew_file1 ';
								$ApplicationsDocsEntity->created          	= $this->NOW();
								$ApplicationsDocsEntity->created_by         = $customer_id;
								$application_status 						= $this->ApplicationStages->CONNECTIVITY_STEP1;
								$this->ApplicationsDocs->deleteAll(['application_id' => $id,'doc_type'=>'STUstep1']);
								
								
							}
							$this->ApplicationsDocs->save($ApplicationsDocsEntity);
						}
						
						if($this->ApplicationsDocs->save($ApplicationsDocsEntity)) {
							$this->Applications->updateAll(array('application_status'=>$application_status,'modified'=>$this->NOW(),'modified_by'=>$customer_id),array('id'=>$id));
							$this->ApplicationStages->saveStatus($id,$this->ApplicationStages->CONNECTIVITY_STEP1,$customer_id,'');
							$ErrorMessage 	= "Upload Document Succesfully.";
							$success 		= 1;
							$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
							$this->ApiToken->SetAPIResponse('success',$success);
						} else {
							$ErrorMessage 	= "Error while uploading document.";
							$success 		= 0;
							$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
							$this->ApiToken->SetAPIResponse('success',$success);
						}
					}
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate form details.";
				$success 		= 0;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	/**
	 * CTUStepDocument
	 * Behaviour : Public
	 * @defination : Method is use to upload drwaing file certificate.
	 */
	public function CTUStepDocument()
	{	
		
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['CTUStep1_application_id'])?$this->request->data['CTUStep1_application_id']:0);

		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} 
		else{
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->Applications->viewApplication($id);
			$customer_id 			= $this->Session->read("Customers.id");
			$member_id 				= $this->Session->read("Members.id");


			if (!empty($applyOnlinesData) && (!empty($customer_id) || !empty($member_id))) {
				if ($this->request->is('post')) {
					if((empty($this->request->data['ip_upload_file']['name']))) {
						$ErrorMessage 	= "Please select In Principal Document.";
						$success 		= 0;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					} else {
						if(!empty($this->request->data['ip_upload_file']['name']))
						{	

							$ApplicationsDocsEntity = $this->ApplicationsDocs->newEntity();
							$prefix_file 	= '';
							$name 			= $this->request->data['ip_upload_file']['name'];

							$ext 			= substr(strtolower(strrchr($name, '.')), 1);

							$file_name 		= $prefix_file.date('Ymdhms').rand();
							$uploadPath 	= APPLICATIONS_PATH.$id.'/';
							if(!file_exists(APPLICATIONS_PATH.$id)) {
								@mkdir(APPLICATIONS_PATH.$id, 0777);
							}
							$file_location 	= WWW_ROOT.$uploadPath.'CTUstep1'.'_'.$file_name.'.'.$ext;
							if(move_uploaded_file($this->request->data['ip_upload_file']['tmp_name'],$file_location))
							{

								$couchdbId 		= $this->ReCouchdb->saveData($uploadPath,$file_location,$prefix_file, 'CTUstep1_'.$file_name.'.'.$ext,$this->Session->read('Customers.id'),'CTUstep1');
								$ApplicationsDocsEntity->couchdb_id			= $couchdbId;
								$ApplicationsDocsEntity->application_id		= $id;
								$ApplicationsDocsEntity->file_name        	= 'CTUstep1'.'_'.$file_name.'.'.$ext;
								$ApplicationsDocsEntity->doc_type         	= 'CTUstep1';
								$ApplicationsDocsEntity->title            	= 'In Principle Document';
								$ApplicationsDocsEntity->created          	= $this->NOW();
								$ApplicationsDocsEntity->created_by         = $customer_id;
								$application_status 						= $this->ApplicationStages->CTU1;
								$this->ApplicationsDocs->deleteAll(['application_id' => $id,'doc_type'=>'CTUstep1']);
								
								
							}
							$this->ApplicationsDocs->save($ApplicationsDocsEntity);
						}

						if($this->ApplicationsDocs->save($ApplicationsDocsEntity)) {
							$this->Applications->updateAll(array('application_status'=>$application_status,'modified'=>$this->NOW(),'modified_by'=>$customer_id),array('id'=>$id));
							$this->ApplicationStages->saveStatus($id,$this->ApplicationStages->CTU1,$customer_id,'');
							$ErrorMessage 	= "Upload Document Succesfully.";
							$success 		= 1;
							$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
							$this->ApiToken->SetAPIResponse('success',$success);
						} else {
							$ErrorMessage 	= "Error while uploading document.";
							$success 		= 0;
							$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
							$this->ApiToken->SetAPIResponse('success',$success);
						}
					}
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate form details.";
				$success 		= 0;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	/**
	 * CTUStep2Document
	 * Behaviour : Public
	 * @defination : Method is use to upload drwaing file certificate.
	 */
	public function CTUStep2Document()
	{	
		
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['CTUStep2_application_id'])?$this->request->data['CTUStep2_application_id']:0);

		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} 
		else{
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->Applications->viewApplication($id);
			$customer_id 			= $this->Session->read("Customers.id");
			$member_id 				= $this->Session->read("Members.id");


			if (!empty($applyOnlinesData) && (!empty($customer_id) || !empty($member_id))) {
				if ($this->request->is('post')) {
					if( (empty($this->request->data['fp_upload_file']['name'])) ) {
						$ErrorMessage 	= "Please select Final Principal Document.";
						$success 		= 0;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					} else {
						
						if(!empty($this->request->data['fp_upload_file']['name']))
						{	$ApplicationsDocsEntity = $this->ApplicationsDocs->newEntity();
							$prefix_file 	= '';
							$name 			= $this->request->data['fp_upload_file']['name'];
							$ext 			= substr(strtolower(strrchr($name, '.')), 1);
							$file_name 		= $prefix_file.date('Ymdhms').rand();
							$uploadPath 	= APPLICATIONS_PATH.$id.'/';
							if(!file_exists(APPLICATIONS_PATH.$id)) {
								@mkdir(APPLICATIONS_PATH.$id, 0777);
							}
							$file_location 	= WWW_ROOT.$uploadPath.'CTUstep2'.'_'.$file_name.'.'.$ext;
							if(move_uploaded_file($this->request->data['fp_upload_file']['tmp_name'],$file_location))
							{

								$couchdbId 		= $this->ReCouchdb->saveData($uploadPath,$file_location,$prefix_file, 'CTUstep2_'.$file_name.'.'.$ext,$this->Session->read('Customers.id'),'CTUstep2');
								$ApplicationsDocsEntity->couchdb_id			= $couchdbId;
								$ApplicationsDocsEntity->application_id		= $id;
								$ApplicationsDocsEntity->file_name        	= 'CTUstep2'.'_'.$file_name.'.'.$ext;
								$ApplicationsDocsEntity->doc_type         	= 'CTUstep2';
								$ApplicationsDocsEntity->title            	= 'Final Principle Document';
								$ApplicationsDocsEntity->created          	= $this->NOW();
								$ApplicationsDocsEntity->created_by         = $customer_id;
								$application_status 						= $this->ApplicationStages->CTU2;
								$this->ApplicationsDocs->deleteAll(['application_id' => $id,'doc_type'=>'CTUstep2']);
								
								
							}
							$this->ApplicationsDocs->save($ApplicationsDocsEntity);
						}
						if($this->ApplicationsDocs->save($ApplicationsDocsEntity)) {
							$this->Applications->updateAll(array('application_status'=>$application_status,'modified'=>$this->NOW(),'modified_by'=>$customer_id),array('id'=>$id));
							$this->ApplicationStages->saveStatus($id,$this->ApplicationStages->CTU2,$customer_id,'');
							$ErrorMessage 	= "Upload Document Succesfully.";
							$success 		= 1;
							$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
							$this->ApiToken->SetAPIResponse('success',$success);
						} else {
							$ErrorMessage 	= "Error while uploading document.";
							$success 		= 0;
							$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
							$this->ApiToken->SetAPIResponse('success',$success);
						}
					}
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate form details.";
				$success 		= 0;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	public function generateSendOTPDeveloper()
	{
		$installer_id 	= isset($this->request->data['installer_id']) ? $this->request->data['installer_id'] : '';
		$is_email 		= isset($this->request->data['is_email']) ? $this->request->data['is_email'] : '';
	
		if(!empty($installer_id)) {
			$InstallerEntity 	= $this->Developers->find('all',array('conditions'=>array('id'=>$installer_id,'payment_status !='=>1)))->first();
			if(!empty($InstallerEntity)) {
				$x 					= 5; // Amount of digits
				$min 				= pow(10,$x);
				$max 				= (pow(10,$x+1)-1);
				$activation_code    = rand($min, $max);
				$activation_email   = rand($min, $max);
				$sms_mobile 		= $InstallerEntity->mobile;
				if($is_email ==0 || $is_email == '') {
					//$sms_message 		= "Thank you for registering with ".PRODUCT_NAME.". Your activation code is ".$activation_code;
					/*$this->Developers->SendSMSActivationCode($InstallerEntity->id,$InstallerEntity->mobile,$activation_code);
					$this->Developers->updateAll(
						array("otp" => $activation_code,'otp_created_date' => $this->NOW(),'otp_verified_status'=>'0','modified'=>$this->NOW()),
						array("id" => $InstallerEntity->id)
					);*/
				}
				if($is_email == 1 || $is_email == '') {
					$to					= $InstallerEntity->email; //$project->customer['email'];
					//$to			= 'pravin.sanghani@yugtia.com'; //$project->customer['email'];
					$subject			= "Third Party Developer OTP";
					$email 				= new Email('default');
					$email->profile('default');
					$email->viewVars(array('activation_code' => $activation_email,'URL_VERIFY'=>URL_HTTP.'developer-verify-otp/'.encode($InstallerEntity->id),'installer_name'=>$InstallerEntity->installer_name));			
					$email->template('thirdparty_email_otp', 'default')
							->emailFormat('html')
							->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
							->to($to)
							->subject(Configure::read('EMAIL_ENV').$subject)
							->send();
					$this->Developers->updateAll(
						array("otp_email" => $activation_email,'otp_email_created_date' => $this->NOW(),'otp_email_verified_status'=>'0','modified'=>$this->NOW()),
						array("id" => $InstallerEntity->id)
					);
				}
			}
		}
	}
	public function verifyOtpDeveloper()
	{
		if(INSTALLER_REGISTRATION == 0) {
			return $this->redirect(URL_HTTP);
		}
		//$this->autoRender 	= false;
		$this->layout 			= 'frontend';
		$id 					= (isset($this->request->data['insid']) ? $this->request->data['insid'] : (!empty($installer_id) ? $installer_id : 0));
		$otp 					= (isset($this->request->data['otp'])?$this->request->data['otp']:'');
		$is_email 				= (isset($this->request->data['is_email'])?$this->request->data['is_email']:'');
		
		
		$encode_id 				= $id;
		$id 					= intval(decode($id));
		$installerData 			= $this->Developers->find('all',array('Fields'=>['otp','otp_email','id','otp_verified_status','otp_email_verified_status','registration_type'],'conditions'=>array('id'=>$id)))->first();
		if (!empty($installerData)) {
			if ($this->request->is('post') || $this->request->is('put')) {
				if(empty($otp)) {
					$ErrorMessage 	= "Please Enter OTP.";
					$success 		= 0;
					$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
					$this->ApiToken->SetAPIResponse('success',$success);
				} else {

					/*if($otp == $installerData->otp && $is_email==0) {
						if(!empty($installerData->otp_created_date))
						{
							$otp_created_date 	= strtotime($installerData->otp_created_date);
							$current_date 		= strtotime($this->NOW());
							$datediff 			= ($current_date - $otp_created_date);
							if(($datediff/(60)) > OTP_VALIDITY_TIME)
							{
								$ErrorMessage 	= "OTP has been expired. Click on Resend OTP button in order to get new OTP.";
								$success 		= 0;
								$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
								$this->ApiToken->SetAPIResponse('success',$success);
								echo $this->ApiToken->GenerateAPIResponse();
								exit;
							}
						}
						$this->Developers->updateAll(array('otp_verified_status'=>1),array('id'=>$id));
						$this->Flash->set('Mobile OTP Verified successfully.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
						$ErrorMessage 	= "Mobile OTP Verified successfully.";
						$success 		= 1;
					} else */if($otp == $installerData->otp_email && $is_email==1) {
						if(!empty($installerData->otp_email_created_date))
						{
							$otp_email_created_date = strtotime($installerData->otp_email_created_date);
							$current_date 			= strtotime($this->NOW());
							$datediff 				= ($current_date - $otp_email_created_date);
							if(($datediff/(60)) > OTP_VALIDITY_TIME)
							{
								$ErrorMessage 	= "OTP has been expired. Click on Resend OTP button in order to get new OTP.";
								$success 		= 0;
								$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
								$this->ApiToken->SetAPIResponse('success',$success);
								echo $this->ApiToken->GenerateAPIResponse();
								exit;
							}
						}
						$this->Developers->updateAll(array('otp_email_verified_status'=>1),array('id'=>$id));
						$this->Flash->set('Email OTP Verified successfully.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);

						$ErrorMessage 	= "Email OTP Verified successfully.";
						$success 		= 1;
					} else {
						$ErrorMessage 	= "Error while otp verification.";
						$success 		= 0;
					}
					
					$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
					$this->ApiToken->SetAPIResponse('success',$success);
					$installerData 		= $this->Developers->find('all',array('Fields'=>['otp_verified_status','otp_email_verified_status','registration_type'],'conditions'=>array('id'=>$id)))->first();
				
					$redirect_payment 	= 0;
					if($installerData->otp_verified_status == 1 && $installerData->otp_email_verified_status == 1 && $installerData->registration_type != 1) {
							
						$redirect_payment 	= 1;
						//$this->Developers->saveDeveloperDetails($id);
					}
					$this->ApiToken->SetAPIResponse('redirect_payment',$redirect_payment);
					echo $this->ApiToken->GenerateAPIResponse();
					exit;
				}
			}

		} else {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
			echo $this->ApiToken->GenerateAPIResponse();
			exit;
		}
		$this->set('pageTitle','OTP Verification');
		$this->set('installer_id',$encode_id);
		$this->set('installerData',$installerData);
		$this->set('company_id',encode($installerData->company_id));
		//echo $this->ApiToken->GenerateAPIResponse();
		//exit;
	}

	public function getModel(){
		
		$this->autoRender 		= false;
		$makeId 					= isset($this->request->data['makeId'])?$this->request->data['makeId']:0;

		$data 					= array();
		if (!empty($makeId)) {
			$model 			= $this->wind_manufacturer_rlmm->find("all",['fields'=>array('model_name'),'conditions'=>['make_id'=>$makeId]]);
			if(!empty($model)) {
				foreach($model as $val) {
					$data[$val->model_name]= $val->model_name;
				}
			}
			
		}
		$this->ApiToken->SetAPIResponse('msg', 'list of model');
		$this->ApiToken->SetAPIResponse('data', $data);
		$this->ApiToken->SetAPIResponse('type','ok');
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	public function getModelDetails(){
		$this->autoRender 		= false;
		$modelNm 				= isset($this->request->data['modelNm'])?$this->request->data['modelNm']:'';
		$data 					= array();
		if (!empty($modelNm)) {
			$modelDetail 			= $this->wind_manufacturer_rlmm->find("all",['fields'=>array('rotor_dimension','hub_height','capacity','validity_till'),'conditions'=>['model_name'=>$modelNm]])->toArray();
			
			if(!empty($modelDetail)) {
				foreach($modelDetail as $val) {
					$data['rotor'][$val->rotor_dimension]= $val->rotor_dimension;
					$data['hub'][$val->hub_height]= $val->hub_height;
					$data['capacity'][$val->capacity]= $val->capacity;
					$data['validity'] = date('j-M-Y',strtotime($val->validity_till));
				}
				
			}
			
		}
		$this->ApiToken->SetAPIResponse('msg', 'list of model details');
		$this->ApiToken->SetAPIResponse('data', $data);
		$this->ApiToken->SetAPIResponse('type','ok');
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	/**
	 *
	 * fetchceidata
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to fetch data from cei application details aacording to application id.
	 *
	 */
	public function fetchceidata()
	{
		$appid 		= intval(decode($this->request->data['app_id']));
		$cei_data 	= $this->CeiReApplicationDetails->find('all',array('conditions'=>array('application_id'=>$appid)))->first();
		if(!empty($cei_data))
		{
			echo json_encode(array('type'=>'ok','response'=>$cei_data));
		}
		else
		{
			echo json_encode(array('type'=>'error','response'=>''));
		}
		exit;
	}
	/**
	 *
	 * fetch_status_api
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to fetch status from thirdparty API and update or add record in cei application details.
	 *
	 */
	public function fetch_restatus_api()
	{
		//echo"<pre>"; print_r($this->request->data); die();
		$this->autoRender	= false;
		$appid 				= intval(decode($this->request->data['app_id']));
		$drawing_number 	= isset($this->request->data['drawing_number']) ? $this->request->data['drawing_number'] : '';
		$cei_number 		= isset($this->request->data['cei_number']) ? $this->request->data['cei_number'] : '';
		$api_type 			= $this->request->data['api_type'];
		$pass_param 		= $drawing_number;
		//echo"<pre>"; print_r($pass_param); die();
		if($pass_param == '')
		{
			$pass_param 	= $cei_number;
		}
		$response 			= $this->ReThirdpartyApiLog->third_party_call($appid,$pass_param,$api_type);
		
		//echo"<pre>"; print_r($response); die();
		$exist_cei 			= $this->CeiReApplicationDetails->find('all',array('conditions'=>array('application_id'=>$appid)))->first();
		if(empty($exist_cei))
		{
			$ceiappEntity						= $this->CeiReApplicationDetails->newEntity($this->request->data);
			$ceiappEntity->application_id 		= $appid;
			$ceiappEntity->created 				= $this->NOW();
			$ceiappEntity->updated 				= $this->NOW();
		}
		else
		{
			$getceidata 						= $this->CeiReApplicationDetails->get($exist_cei->id);
			$ceiappEntity						= $this->CeiReApplicationDetails->patchEntity($getceidata,$this->request->data);
			$ceiappEntity->updated 				= $this->NOW();
		}
		if($drawing_number!='')
		{
			$ceiappEntity->drawing_app_no 		= $drawing_number;
			$ceiappEntity->drawing_app_status	= $response;
			$ceiappEntity->status 				= '1';
			$status_update 						= $this->ApplicationStages->DRAWING_APPLIED;
		}
		if($cei_number!='')
		{
			$ceiappEntity->cei_app_no 			= $cei_number;
			$ceiappEntity->cei_app_status		= $response;
			$ceiappEntity->status 				= '2';
			$status_update 						= $this->ApplicationStages->CEI_APP_NUMBER_APPLIED;
		}
		if($this->CeiReApplicationDetails->save($ceiappEntity))
		{
			//$this->SetApplicationStatus($status_update,$appid,'');
		}
		echo json_encode(array('type'=>'ok','response'=>$response));
		exit;
	}
	/*
	 * Function for inspectionstage
	 * @param mixed What page to display
	 * @return void
	 */
	public function inspectionstage()
	{
		echo"<pre>"; print_r($this->request->data); die();
		$this->autoRender 	= false;
		$stage 				= (isset($this->request->data['approval_type'])?$this->request->data['approval_type']:0);
		$getexistingdata 	= (isset($this->request->data['show-prev-report'])?$this->request->data['show-prev-report']:0);

		if (!$getexistingdata) {
			switch($stage)
			{
				
				case 51:
				{
					$id 	= (isset($this->request->data['appid'])?decode($this->request->data['appid']):0);
					$status = (isset($this->request->data['application_status'])?($this->request->data['application_status']):0);
					//$reason = (isset($this->request->data['reason'])?($this->request->data['reason']):"");
					$drawing_app_no 		= (isset($this->request->data['drawing_app_no'])?($this->request->data['drawing_app_no']):"");
					$drawing_app_status 	= (isset($this->request->data['drawing_app_status'])?($this->request->data['drawing_app_status']):"");


					$reason 				= '';
					$exist_cei 			= $this->CeiReApplicationDetails->find('all',array('conditions'=>array('application_id'=>$id)))->first();
					$ceiappEntity       = $this->request->data;
					if(empty($exist_cei))
					{
						$ceiappEntity					= $this->CeiReApplicationDetails->newEntity($this->request->data);
						$ceiappEntity->application_id 	= $id;
						$ceiappEntity->created 			= $this->NOW();
						$ceiappEntity->updated 			= $this->NOW();
					}
					else
					{
						$getceidata 					= $this->CeiReApplicationDetails->get($exist_cei->id);
						$ceiappEntity					= $this->CeiReApplicationDetails->patchEntity($getceidata,$this->request->data);
						$ceiappEntity->updated 			= $this->NOW();
					}
					$ceiappEntity->status 			= '1';
					$status 						= $this->ApplicationStages->DRAWING_APPLIED;

					if($this->CeiReApplicationDetails->save($ceiappEntity))
					{
						$this->SetApplicationStatus($status,$id,$reason);
						if(strtolower($drawing_app_status) == 'completed')
						{
							$status 				= $this->ApplicationStages->APPROVED_FROM_CEI;
							$this->SetApplicationStatus($status,$id,$reason);
						}
					}
						//$status = ($status == 1)?$this->ApplyOnlineApprovals->APPROVED_FROM_CEI:$this->ApplyOnlineApprovals->REJECTED_FROM_CEI;
					$this->ApiToken->SetAPIResponse('msg', 'Applicaton status updated.');
					$this->ApiToken->SetAPIResponse('type','ok');
					break;
				}
				case 6:
				{
					$id 	= (isset($this->request->data['appid'])?decode($this->request->data['appid']):0);
					$status = (isset($this->request->data['application_status'])?($this->request->data['application_status']):0);
					//$reason = (isset($this->request->data['reason'])?($this->request->data['reason']):"");
					$cei_app_no 			= (isset($this->request->data['cei_app_no'])?($this->request->data['cei_app_no']):"");
					$cei_app_status 		= (isset($this->request->data['cei_app_status'])?($this->request->data['cei_app_status']):"");
					$reason 				= '';
					$exist_cei 				= $this->CeiReApplicationDetails->find('all',array('conditions'=>array('application_id'=>$id)))->first();
					$ceiappEntity       	= $this->request->data;
					if(empty($exist_cei))
					{
						$ceiappEntity					= $this->CeiReApplicationDetails->newEntity($this->request->data);
						$ceiappEntity->application_id 	= $id;
						$ceiappEntity->created 			= $this->NOW();
						$ceiappEntity->updated 			= $this->NOW();
					}
					else
					{
						$getceidata 					= $this->CeiReApplicationDetails->get($exist_cei->id);
						$ceiappEntity					= $this->CeiReApplicationDetails->patchEntity($getceidata,$this->request->data);
						$ceiappEntity->updated 			= $this->NOW();
					}
					$ceiappEntity->status 				= '2';
					$status 							= $this->ApplicationStages->CEI_APP_NUMBER_APPLIED;
					if($this->CeiReApplicationDetails->save($ceiappEntity))
					{
						$this->SetApplicationStatus($status,$id,$reason);
						if(strtolower($cei_app_status) == 'completed')
						{
							$status 				= $this->ApplicationStages->CEI_INSPECTION_APPROVED;
							$this->SetApplicationStatus($status,$id,$reason);
						}
					}
						//$status = ($status == 1)?$this->ApplyOnlineApprovals->APPROVED_FROM_CEI:$this->ApplyOnlineApprovals->REJECTED_FROM_CEI;
					$this->ApiToken->SetAPIResponse('msg', 'Applicaton status updated.');
					$this->ApiToken->SetAPIResponse('type','ok');
					break;
				}
				default:{
					$this->ApiToken->SetAPIResponse('msg', 'Applicaton status update failed.');
					$this->ApiToken->SetAPIResponse('type','error');
					break;
				}
			}
		} else {
			$InspectionReport 	= $this->InspectionReport->getInspectionReport($stage,$this->request->data['appid']);
			$inspection_data 	= "";
			if (isset($InspectionReport->inspection_data) && !empty($InspectionReport->inspection_data)) {
				$inspection_data = unserialize($InspectionReport->inspection_data);
			}
			$this->ApiToken->SetAPIResponse('msg', 'Inspection Report data.');
			$this->ApiToken->SetAPIResponse('type','ok');
			$this->ApiToken->SetAPIResponse('inspection_data',$inspection_data);
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	private function SetApplicationStatus($status,$id,$reason="")
	{
		$member_id 			= $this->Session->read('Members.id');
		$applyOnlinesData 	= $this->Applications->viewApplication($id);
		if ($this->ApplicationStages->validateNewStatus($status,$applyOnlinesData->application_status) || $status=='CANCELLED_REOPEN')
		{
			if($status!='CANCELLED_REOPEN')
			{
			$arrData 		= array("application_status"=>$status);
			$this->Applications->updateAll($arrData,['id' => $id]);
			}
			$sms_text 		= '';
			$subject 		= '';
			$EmailVars 		= array();
			$sms_template 	= '';
			if($status==$this->ApplicationStages->DRAWING_APPLIED)
			{
				$sms_text 			= str_replace('##geda_application_no##',$applyOnlinesData->geda_application_no,DRAWING_APPLIED);
				$sms_template 		= 'DRAWING_APPLIED';
				$subject 			= "[REG: GEDA Application No. ".$applyOnlinesData->geda_application_no."] CEI Drawing Applied";
				$CUSTOMER_NAME 		= trim($applyOnlinesData->customer_name_prefixed." ".$applyOnlinesData->name_of_consumer_applicant." ".$applyOnlinesData->last_name." ".$applyOnlinesData->third_name);
				$cei_data 			= $this->CeiReApplicationDetails->find('all',array('conditions'=>array('application_id'=>$id)))->first();
				$drawing_number 	= '';
				if(!empty($cei_data))
				{
					$drawing_number = $cei_data->drawing_app_no;
				}
				$EmailVars 			= array("CUSTOMER_NAME"=>$CUSTOMER_NAME,'CEI_DRAWING_NUMBER'=>$drawing_number);
				$template_applied 	= 'drawing_applied';
			}
			else if($status==$this->ApplicationStages->CEI_APP_NUMBER_APPLIED)
			{
				$sms_text 			= str_replace('##geda_application_no##',$applyOnlinesData->geda_application_no,CEI_APP_NUMBER_APPLIED);
				$sms_template 		= 'CEI_APP_NUMBER_APPLIED';
				$subject 			= "[REG: GEDA Application No. ".$applyOnlinesData->geda_application_no."] CEI Application Number";
				$CUSTOMER_NAME 		= trim($applyOnlinesData->customer_name_prefixed." ".$applyOnlinesData->name_of_consumer_applicant." ".$applyOnlinesData->last_name." ".$applyOnlinesData->third_name);
				$cei_data	        = $this->CeiReApplicationDetails->find('all',array('conditions'=>array('application_id'=>$id)))->first();
				$cei_app_no         = '';
				if(!empty($cei_data))
				{
					$cei_app_no     = $cei_data->cei_app_no;
				}
				$EmailVars 			= array("CUSTOMER_NAME"=>$CUSTOMER_NAME,'CEI_APPLICATION_NUMBER'=>$cei_app_no);
				$template_applied 	= 'ceinumber_applied';
			}
			
			else if($status==$this->ApplicationStages->CEI_INSPECTION_APPROVED)
			{
				$sms_text 			= str_replace('##geda_application_no##',$applyOnlinesData->geda_application_no,CEI_INSPECTION_APPROVED);
				$sms_template 		= 'CEI_INSPECTION_APPROVED';
				$subject 			= "[REG: GEDA Application No. ".$applyOnlinesData->geda_application_no."] Inspection From CEI";
				$CUSTOMER_NAME 		= trim($applyOnlinesData->customer_name_prefixed." ".$applyOnlinesData->name_of_consumer_applicant." ".$applyOnlinesData->last_name." ".$applyOnlinesData->third_name);
				$cei_data 			= $this->CeiReApplicationDetails->find('all',array('conditions'=>array('application_id'=>$id)))->first();
				$cei_app_no 	= '';
				if(!empty($cei_data))
				{
					$cei_app_no = $cei_data->cei_app_no;
				}
				$EmailVars 			= array("CUSTOMER_NAME"=>$CUSTOMER_NAME,'CEI_APPLICATION_NUMBER'=>$cei_app_no);
				$template_applied 	= 'cei_inspection';
			}
			
			
			if($sms_text!='')
			{
				if(!empty($applyOnlinesData->consumer_mobile))
				{
					$this->Applications->sendSMS($id,$applyOnlinesData->consumer_mobile,$sms_text,$sms_template);
				}
				if(!empty($applyOnlinesData->installer_mobile))
				{
					//$this->ApplyOnlines->sendSMS($id,$applyOnlinesData->installer_mobile,$sms_text);
				}
			}
			if($subject!='')
			{
				if(!empty($applyOnlinesData->installer_email))
				{
					$email 			= new Email('default');
					$email->profile('default');
					$email->viewVars($EmailVars);
					$message_send 	= $email->template($template_applied, 'default')
							->emailFormat('html')
							->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
							->to($applyOnlinesData->installer_email)
							->subject(Configure::read('EMAIL_ENV').$subject)
							->send();
				}
				$to 	= $applyOnlinesData->consumer_email;
				if(empty($to))
				{
					$to = $applyOnlinesData->email;
				}
				if(!empty($to))
				{
					$email 			= new Email('default');
					$email->profile('default');
					$email->viewVars($EmailVars);
					$message_send 	= $email->template($template_applied, 'default')
							->emailFormat('html')
							->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
							->to($to)
							->subject(Configure::read('EMAIL_ENV').$subject)
							->send();
					/* Email Log */
					$Emaillog                  = $this->Emaillog->newEntity();
					$Emaillog->email           = $to;
					$Emaillog->send_date       = $this->NOW();
					$Emaillog->action          = Configure::read('EMAIL_ENV').$subject;
					$Emaillog->description     = json_encode(array('EMAIL_ADDRESS' => $to,'EmailVars' => $EmailVars,'URL_HTTP'=>URL_HTTP));
					$this->Emaillog->save($Emaillog);
					/* Email Log */
				}
			}
		}
		if($status!='CANCELLED_REOPEN')
		{
			$this->ApplicationStages->saveStatus($id,$status,$member_id,$reason);
		}
	}
	/**
	 * Work_Execution
	 * Behaviour : Public
	 * @defination : Method is use to upload drwaing file certificate.
	 */
	public function Work_Execution()
	{	
		
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['Work_Execution_application_id'])?$this->request->data['Work_Execution_application_id']:0);

		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} 
		else{
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->Applications->viewApplication($id);
			$customer_id 			= $this->Session->read("Customers.id");
			$member_id 				= $this->Session->read("Members.id");


			if (!empty($applyOnlinesData) && (!empty($customer_id) || !empty($member_id))) {
				if ($this->request->is('post')) {
					
						$this->Applications->updateAll(array('application_status'=>$this->ApplicationStages->WORK_EXECUTED,'modified'=>$this->NOW(),'modified_by'=>$customer_id),array('id'=>$id));
						$this->ApplicationStages->saveStatus($id,$this->ApplicationStages->WORK_EXECUTED,$customer_id,'');
						$ErrorMessage 	= "WORK EXECUTED Succesfully.";
						$success 		= 1;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate form details.";
				$success 		= 0;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	 *
	 * rechargingcertificate
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to Charging Certificate
	 *
	 */
	public function rechargingcertificate($id= null)
	{
		//$this->setMemberArea();
		//$this->validateMemberPermission("CHARGING_CERTIFICATE");

		if(empty($id)) {
			$this->Flash->error('Please select valid application.');
			return $this->redirect(URL_HTTP.'/apply-online-list');
		} else {
			$encode_id 					= $id;
			$id 						= intval(decode($id));

			//$checkfeasibilitydone 		= $this->ReChargingCertificate->fetchApiMeterInstallation($id);
			// if($checkfeasibilitydone == '1')
			// {
			// 	return $this->redirect(URL_HTTP.'/apply-online-list');
			// }
			$applicationsData 			= $this->Applications->viewApplication($id);

			$NEWRECORD 					= false;
			if ($applicationsData->application_status < $this->ApplicationStages->REGISTRATION) {
				//$this->Flash->error('Process is not yet open for the selected application.');
				//return $this->redirect(URL_HTTP.'/apply-online-list');
			}
			$application_data 				= $this->Applications->viewApplication($id);

			
			//if ($this->request->is('post') || $this->request->is('put')) {
			if(!empty($id)){

				$rid 						= 0;
				$ReChargingCertificate_data	= $this->ReChargingCertificate->find('all',array('conditions'=>array('application_id'=>$application_data->id)))->first();

				if(!empty($ReChargingCertificate_data))
				{
					$ReChargingCertificate 			= $this->ReChargingCertificate->get($ReChargingCertificate_data->id);
					$ReChargingCertificate 			= $this->ReChargingCertificate->patchEntity($ReChargingCertificate,$this->request->data,['validate'=>'add']);
				}
				else
				{

					$ReChargingCertificate 			= $this->ReChargingCertificate->newEntity($this->request->data,['validate'=>'add']);
					$ReChargingCertificate->application_id 	= $id;
					$ReChargingCertificate->created 	= $this->NOW();
					$ReChargingCertificate->created_by= $this->Session->read('Members.id');
				}
				$NEWRECORD 							= true;
				$agreement_date 					= '';
				$meter_installed_date 				= '';
				if(isset($this->request->data['agreement_date']) && !empty($this->request->data['agreement_date']))
				{
					$agreement_date 				= date('Y-m-d',strtotime($this->request->data['agreement_date']));
					$ReChargingCertificate->agreement_date = $agreement_date;
				}
				if(isset($this->request->data['meter_installed_date']) && !empty($this->request->data['meter_installed_date']))
				{
					$meter_installed_date 	= date('Y-m-d',strtotime($this->request->data['meter_installed_date']));
					$ReChargingCertificate->meter_installed_date 	= $meter_installed_date;
				}
				if(empty($ReChargingCertificate->errors()))
				{
					$ReChargingCertificate->application_id 		= $id;
					$ReChargingCertificate->sanctioned_load_phase = isset($this->request->data['sanctioned_load_phase'])?$this->request->data['sanctioned_load_phase']:1;
					$ReChargingCertificate->pv_capacity_phase 	= isset($this->request->data['pv_capacity_phase'])?$this->request->data['pv_capacity_phase']:1;
					$ReChargingCertificate->agreement_date 		= $agreement_date;
					$ReChargingCertificate->meter_installed_date 	= $meter_installed_date;
					$ReChargingCertificate->solar_meter 			= isset($this->request->data['solar_meter'])?$this->request->data['solar_meter']:1;
					$ReChargingCertificate->bi_directional_meter 	= isset($this->request->data['bi_directional_meter'])?$this->request->data['bi_directional_meter']:1;
					$ReChargingCertificate->modified 				= $this->NOW();
					$ReChargingCertificate->modified_by 			= $this->Session->read('Members.id');
					$rid 										= intval(decode($this->request->data['id']));
					if (!empty($rid)) {
						$ReChargingCertificate->id 	= $rid;
					}
					if ($this->ReChargingCertificate->save($ReChargingCertificate)) {
						if($application_data->transmission_line!=$ReChargingCertificate->pv_capacity_phase)
						{
							$message 						= 'Transmission line has been modified by Discom from '.$this->Applications->PHASE_ARRAY[$application_data->transmission_line]. ' to '.$this->Applications->PHASE_ARRAY[$ReChargingCertificate->pv_capacity_phase];
							$member_id          			= $this->Session->read("Members.id");
							$member_type 					= $this->Session->read('Members.member_type');
							$browser 						= isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:"-";
							$ApplicationsMessageEntity					= $this->ApplicationsMessage->newEntity();
							$ApplicationsMessageEntity->application_id 	= $id;
							$ApplicationsMessageEntity->message 			= strip_tags($message);
							$ApplicationsMessageEntity->user_type 		= !empty($member_type)?$member_type:0;
							$ApplicationsMessageEntity->user_id 			= !empty($member_id)?$member_id:0;
							$ApplicationsMessageEntity->ip_address 		= $this->IP_ADDRESS;
							$ApplicationsMessageEntity->created 			= $this->NOW();
							$ApplicationsMessageEntity->browser_info 	= json_encode($browser);
							$this->ApplicationsMessage->save($ApplicationsMessageEntity);
							$this->Applications->updateAll(array('transmission_line'=>$ReChargingCertificate->pv_capacity_phase),array('id'=>$application_data->id));
						}
						if (empty($rid)) {
							$rid = $ReChargingCertificate->id;
						}
						if ($NEWRECORD) {
							$this->SetApplicationStatus($this->ApplicationStages->METER_INSTALLATION,$id);
							$this->SetApplicationStatus($this->ApplicationStages->APPROVED_FROM_DISCOM,$id);
						}
						$Execution_data         = $this->Installation->find('all',array('conditions'=>array('project_id'=>$application_data->project_id)))->first();
						if(!empty($Execution_data))
						{
							$arrUpdate          = array();
							if(empty($Execution_data->meter_serial_no))
							{
								$arrUpdate['meter_serial_no']       = $ReChargingCertificate->bi_directional_meter;
							}
							if(empty($Execution_data->solar_meter_serial_no))
							{
								$arrUpdate['solar_meter_serial_no'] = $ReChargingCertificate->solar_meter;
							}
							if(empty($Execution_data->bi_date) || $Execution_data->bi_date=='0000-00-00')
							{
								$arrUpdate['bi_date']               = $ReChargingCertificate->meter_installed_date;
							}
							if(empty($Execution_data->agreement_date) || $Execution_data->agreement_date=='0000-00-00')
							{
								$arrUpdate['agreement_date']        = $ReChargingCertificate->agreement_date;
							}
							if(!empty($arrUpdate))
							{
								$this->Installation->updateAll($arrUpdate,array('project_id'=>$applicationsData->project_id));
								$this->ReChargingCertificate->updateAll(array('update_execution'=>'1'),array('id'=>$ReChargingCertificate->id));
							}
						}
						$this->Flash->success('Data saved successfully.');
						return $this->redirect(URL_HTTP.'/apply-onlines/Rechargingcertificate/'.$encode_id);
					}
				}
				$ReChargingCertificate = $ReChargingCertificate;
			} else {
				$ReChargingCertificate 			= $this->ReChargingCertificate->getReportData($id);
				$rid 							= isset($ReChargingCertificate['id'])?encode($ReChargingCertificate['id']):"";
				if (empty($rid)) {
					$ReChargingCertificate 		= $this->ReChargingCertificate->newEntity();
					$ReChargingCertificate->pv_capacity_phase = $application_data->transmission_line;
				}
				$ReChargingCertificate->created 	= isset($ReChargingCertificate['created'])?$ReChargingCertificate['created']:$this->NOW();
			}
		}
		$applicationsData->aid 		= $this->Applications->GenerateApplicationNo($applicationsData);
		$RegistrationScheme 		= array();
		//$RegistrationScheme 		= new stdClass();
		$RegistrationScheme 		= $this->CeiApplicationDetails->find('all',array('conditions'=>array('application_id'=>$applicationsData->id)))->first();

		if(!empty($RegistrationScheme)){
			$RegistrationScheme->aid 	= $RegistrationScheme->drawing_app_no;
		}
		else{
			@$RegistrationScheme->aid 	= '';
			@$RegistrationScheme->created 	= '';
		}
		$fesibility 				= $this->FesibilityReport->getReportData($id);
		$fesibility->aid 			= $this->FesibilityReport->GenerateApplicationNo($fesibility,$applicationsData->state);
		$applicationSubmission 		= $this->ApplicationStages->find('all',array('conditions'=>array('application_id'=>$applicationsData->id,'stage'=>$this->ApplicationStages->APPLICATION_SUBMITTED)))->first();

		$this->set('id',$encode_id);
		$this->set('rid',$rid);
		$this->set('Applications',$applicationsData);
		$this->set('phasearray',array("1"=>"Single Phase","3"=>"3 Phase"));
		$this->set('RegistrationScheme',$RegistrationScheme);
		$this->set('ReChargingCertificate',$ReChargingCertificate);
		$this->set('applicationSubmission',$applicationSubmission);
		$this->set('fesibility',$fesibility);
		$this->set("pageTitle","Charging Certificate for Net-meter Installation");
	}
	public function get_details(){

		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['STUStep1_application_id'])?$this->request->data['STUStep1_application_id']:0);
		
		if(empty($id)) {
			$ErrorMessage 	= "Invalid Rcxfequest. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		}	else{
			$encode_id 					= $id;
			$id 						= intval(decode($id));

				$get_data 			= $this->Applications->find("all",['conditions'=>array('id'=>$id)])->first();
				//echo"<pre>"; print_r($get_data->application_type); die();

				if($get_data->application_type == 2){
					$ac_capacity 	=	(!empty($get_data->pv_capacity_ac) ? $get_data->pv_capacity_ac : '');
					$dc_capacity 	=   (!empty($get_data->pv_capacity_dc) ? $get_data->pv_capacity_dc : '');
				}else if($get_data->application_type == 3){
					$ac_capacity 	=	(!empty($get_data->total_capacity) ? $get_data->total_capacity : '');
					$dc_capacity 	=   (!empty($get_data->capacity_wtg) ? $get_data->capacity_wtg : '');
				}else if($get_data->application_type == 4){

					$ac_inverter 	=	(!empty($get_data->inverter_hybrid_capacity) ? $get_data->inverter_hybrid_capacity : '');
					$ac_wtg 		=  (!empty($get_data->total_capacity) ? $get_data->total_capacity : '');


					$dc_capacity 	=   (!empty($get_data->capacity_wtg) && !empty($get_data->wtg_no)) ? (($get_data->wtg_no * $get_data->capacity_wtg)) : ''; 

					if($ac_inverter > $ac_wtg){
						//echo"<pre>"; print_r('dc is greater'); die();
						$ac_capacity 	=	$ac_inverter;
					}else {
						$ac_capacity 	=	$ac_wtg;
					}
				}
				// else if($get_data->application_type == 4){

				// 	$ac 	=	(!empty($get_data->total_wind_hybrid_capacity) ? $get_data->total_wind_hybrid_capacity : '');
				// 	$dc 	=   (!empty($get_data->capacity_wtg) && !empty($get_data->wtg_no)) ? (($get_data->wtg_no * $get_data->capacity_wtg)) : ''; 

				// 	if($dc > $ac){
				// 		//echo"<pre>"; print_r('dc is greater'); die();
				// 		$ac_capacity 	=	(!empty($get_data->capacity_wtg) && !empty($get_data->wtg_no)) ? (($get_data->wtg_no * $get_data->capacity_wtg)) : '';
				// 		$dc_capacity 	=   (!empty($get_data->total_wind_hybrid_capacity) ? $get_data->total_wind_hybrid_capacity : '');
				// 	}else {
				// 		//echo"<pre>"; print_r('ac is greater'); die();
				// 		$ac_capacity 	=	(!empty($get_data->total_wind_hybrid_capacity) ? $get_data->total_wind_hybrid_capacity : '');
				// 		$dc_capacity 	=   (!empty($get_data->capacity_wtg) && !empty($get_data->wtg_no)) ? (($get_data->wtg_no * $get_data->capacity_wtg)) : ''; 
				// 	}
				// }

				$get_developer_id 	= $this->DeveloperCustomers->find("all",['conditions'=>array('id'=>$get_data->customer_id)])->first();
				$get_developer 		= $this->Developers->find("all",['fields'=>array('id','company_id'),'conditions'=>array('id'=>$get_data->installer_id)])->first();

				$get_company_name 	= $this->DeveloperCompany->find("all",['fields'=>array('id','company_name'),'conditions'=>array('id'=>$get_developer->company_id)])->first();
				
				$discom_name 		= $this->BranchMasters->find("all",['fields'=>array('id','title'),'conditions'=>array('id'=>$get_data->discom)])->first();
				
				$applicationCategory 	= $this->ApplicationCategory->find('all',array('conditions'=>array('id'=>$get_data->application_type)))->first();
				$end_use_electricity = $this->application_end_use_electricity->find('all',array('conditions'=>array('application_id'=>$get_data->id)))->first();
				
				$end_stuarray = array(1 => 'Captive Use', 2 => 'Third Party Sale',3 => 'Sale to DISCOM', 4 => 'Proto Type');
				// Key to check
				$keyToCheck = $end_use_electricity->application_end_use_electricity;
				if (array_key_exists($keyToCheck, $end_stuarray)) {
				    $end_stu = $end_stuarray[$keyToCheck]; 
				}
				
				//$injection_levelarray = array(1 => 'Below 11 KV', 2 => '11 kv', 3 => '66 kv',4 => 'Above 66kv');
				$injection_levelarray = $this->ApiToken->arrInjectionLevel;
				// Key to check
				
				$keyToCheckinjection = $get_data->injection_level;
				if (array_key_exists($keyToCheckinjection, $injection_levelarray)) {
				    $injection_level = $injection_levelarray[$keyToCheckinjection]; 
				}

				$substation_details = $this->get_substation_details();
				

				$keyToCheckSS = $get_data->getco_substation;
				if (array_key_exists($keyToCheckSS, $substation_details)) {
				    // Display the value corresponding to the key
				     $ss_name = $substation_details[$keyToCheckSS]; 
				}


				// echo"<pre>"; print_r($get_data); die();

				// upload_undertaking
				// pan_card
				// d_file_board
					//WWW_ROOT.APPLICATIONS_PATH.$application_id.
				//$pan_card = WWW_ROOT.APPLICATIONS_PATH.$get_data->id.'/'. $get_data->pan_card;
				//echo"<pre>"; print_r($pan_card); die();
				$data = array();
				$data['company_name'] 				= isset($get_company_name->company_name)?$get_company_name->company_name:'';
				$data['applicant_name'] 			= isset($get_data->name_of_applicant)?$get_data->name_of_applicant:'';
				$data['pan_card']     				=  isset($get_data->pan)?$get_data->pan :'';
				$data['mobile'] 					=  isset($get_data->mobile)?$get_data->mobile:'';
				$data['email_id']     				=  isset($get_data->email)?$get_data->email:'';
				$data['address'] 					=  isset($get_data->address)?$get_data->address:'';
				$data['city'] 						=  isset($get_data->city)?$get_data->city:'';
				$data['state'] 						=  isset($get_data->state)?$get_data->state:'';
				$data['gst_no'] 					=  isset($get_data->GST)?$get_data->GST:'';
				$data['prov_proj_no']   			=  isset($get_data->application_no)?$get_data->application_no:'';
				$data['applicant_type']   			=  isset($get_data->type_of_applicant)?$get_data->type_of_applicant:'';
				$data['project_type']				=  isset($applicationCategory->category_name)?$applicationCategory->category_name:'';
				$data['project_purpose']			=  isset($end_stu)?$end_stu:'';
				$data['ss_name']					=  isset($ss_name)?$ss_name:'';
				$data['ss_id']						=  isset($get_data->getco_substation)?$get_data->getco_substation:'';
				$data['getcogetco_field_office']	= '';

				$data['applied_capacity_ac']		=  isset($ac_capacity)?$ac_capacity:'';
				$data['applied_capacity_dc']		=  isset($dc_capacity)?$dc_capacity:'';
				$data['voltage_class']				=  isset($injection_level)?$injection_level:'';
				$data['pwr_company']				=  isset($discom_name->title)?$discom_name->title:'';
				$data['developer_registration_no']	=  isset($get_developer_id->developer_registration_no)?$get_developer_id->developer_registration_no:'';

				$data['pancard_doc']				=  URL_HTTP.APPLICATIONS_PATH.$get_data->id.'/'. $get_data->pan_card;

				$data['undertaking']				=  URL_HTTP.APPLICATIONS_PATH.$get_data->id.'/'. $get_data->upload_undertaking;

				$data['board_resolution']			=  URL_HTTP.APPLICATIONS_PATH.$get_data->id.'/'. $get_data->d_file_board;
				//echo"<pre>"; print_r($data); die();
			$data = json_encode($data);
			if(!empty($data)) {
				
					$data 	= $data;
					$success 		= 1;
					$this->ApiToken->SetAPIResponse('message',$data);
					$this->ApiToken->SetAPIResponse('remark','Data Fetch kindly click to Submit');
					$this->ApiToken->SetAPIResponse('success',$success);
			
			}else {
				$ErrorMessage 	= "Invalid Request. Please validate form details.";
				$success 		= 0;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	public function get_substation_details() {
	 
	 	$apiUrl  = 'https://saralsetu.guvnl.com/API/SSMaster.php';
	 	//$apiUrl  = 'https://devakshayurjasetu.guvnl.com/API/SSMaster.php';


		$conn    = curl_init($apiUrl);

		curl_setopt($conn, CURLOPT_CONNECTTIMEOUT, 300);
		curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($conn, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($conn, CURLOPT_HTTPHEADER, [
		    "Authorization: PsPuH#GvLUn^2005"
		]);
		curl_setopt($conn, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($conn, CURLOPT_RETURNTRANSFER, true);
	
		$response = curl_exec($conn);
		//echo"<pre>"; print_r($response); die();
        if (curl_errno($conn)) {
            $error_msg = curl_error($conn);
            $this->Flash->error('cURL error: ' . $error_msg);
        } else {
            $responseData = json_decode($response, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                // Extract only the SS ID and SS Name for the dropdown
                $dropdownData = [];
                foreach ($responseData as $item) {
                    $dropdownData[$item['SS_ID']] = $item['SS_Name'];
                }
                $this->set('dropdownData', $dropdownData);
                return $dropdownData;
            } else {
                $this->Flash->error('Response is not a valid JSON: ' . $response);
            }
        }

        curl_close($conn);
		
	}

	/**
	 * Substation_save
	 * Behaviour : Public
	 * @defination : Method is use to upload other document.
	 */
	public function Substation_save()
	{
		//echo"<pre>"; print_r($this->request->data); die();
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['Substation_application_id'])?$this->request->data['Substation_application_id']:0);
		$getco_substation 	= (isset($this->request->data['getco_substation'])?$this->request->data['getco_substation']:0);
		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} else {
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->Applications->viewApplication($id);
			$customer_id 			= $this->Session->read("Customers.id");
			$member_id 			= $this->Session->read("Members.id");
			if (!empty($applyOnlinesData) && (!empty($customer_id) || !empty($member_id))) {
				if ($this->request->is('post')) {
					if((empty($this->request->data['getco_substation'])) ) {
						$ErrorMessage 	= "Please Select Substation.";
						$success 		= 0;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					} else {
						if(!empty($this->request->data['getco_substation']))
						{	
							$this->Applications->updateAll(array('getco_substation'=>$getco_substation,'modified'=>$this->NOW(),'modified_by'=>$customer_id),array('id'=>$id));
						
							$ErrorMessage 	= "Substation Update Succesfully";
							$success 		= 1;
							$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
							$this->ApiToken->SetAPIResponse('success',$success);
						} else {
							$ErrorMessage 	= "Error while updating substation.";
							$success 		= 0;
							$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
							$this->ApiToken->SetAPIResponse('success',$success);
						}
					}
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate form details.";
				$success 		= 0;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	/**
	 * WHEELINGDocument
	 * Behaviour : Public
	 * @defination : Method is use to complete the WHEELINGDocument
	 */
	public function WHEELINGDocument()
	{	
		//echo"<pre>"; print_r($this->request->data); die();
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['WHEELING_application_id'])?$this->request->data['WHEELING_application_id']:0);
		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} else {
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->Applications->viewApplication($id);
			$customer_id 			= $this->Session->read("Customers.id");
			$member_id 			= $this->Session->read("Members.id");
			if (!empty($applyOnlinesData) && (!empty($customer_id) || !empty($member_id))) {
				if ($this->request->is('post')) {
					if((empty($this->request->data['Wheeling_Agreement_document']['name'])) ) {
						$ErrorMessage 	= "Please upload at least one file .";
						$success 		= 0;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					} else {
						
						$WheelingApplicationDetailsEntity = $this->WheelingApplicationDetails->newEntity();

						$WheelingApplicationDetailsEntity->application_id		= $id;
						$WheelingApplicationDetailsEntity->application_type		= $applyOnlinesData->application_type;
						$WheelingApplicationDetailsEntity->created_date 		= $this->NOW();
						
						$insert_id= $this->WheelingApplicationDetails->save($WheelingApplicationDetailsEntity);

						$insertId = $insert_id->id;
						
						if(!empty($this->request->data['Wheeling_Agreement_document']['name']))
						{
							$prefix_file 	= '';
							$name 			= $this->request->data['Wheeling_Agreement_document']['name'];
							$ext 			= substr(strtolower(strrchr($name, '.')), 1);
							$file_name 		= $prefix_file.date('Ymdhms').rand();
							$uploadPath 	= APPLICATIONS_PATH.$id.'/';
							if(!file_exists(APPLICATIONS_PATH.$id)) {
								@mkdir(APPLICATIONS_PATH.$id, 0777);
							}
							$file_location 	= WWW_ROOT.$uploadPath.'Wheeling'.'_'.$file_name.'.'.$ext;
							if(move_uploaded_file($this->request->data['Wheeling_Agreement_document']['tmp_name'],$file_location))
							{

								$couchdbId 		= $this->ReCouchdb->saveData($uploadPath,$file_location,$prefix_file, 'Wheeling_'.$file_name.'.'.$ext,$this->Session->read('Customers.id'),'Wheeling');

								$this->WheelingApplicationDetails->updateAll(array('Wheeling_Agreement_document'=>'Wheeling_'.$file_name.'.'.$ext),array('id'=>$insertId));
								//$this->Applications->updateAll(array('application_status'=>$this->ApplicationStages->Wheeling,'modified'=>$this->NOW(),'modified_by'=>$customer_id),array('id'=>$id));
								
							}
						} 
						$sldcentry 	= $this->WheelingApplicationDetails->find('all',array('conditions'=>array('id'=>$insertId)))->first();
						if(!empty($sldcentry)) {
							//$this->SendApplicationLetterToCustomer($id);
							
							$this->ApplicationStages->saveStatus($id,$this->ApplicationStages->WHELLING,$customer_id,'');
							$ErrorMessage 	= "Upload Documents Succesfully.";
							$success 		= 1;
							$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
							$this->ApiToken->SetAPIResponse('success',$success);
						}
						 else {
							$ErrorMessage 	= "Error while uploading documents.";
							$success 		= 0;
							$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
							$this->ApiToken->SetAPIResponse('success',$success);
						}
					}	
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate form details.";
				$success 		= 0;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	 * WHEELINGApprovalDocument
	 * Behaviour : Public
	 * @defination : Method is use to complete the WHEELINGApprovalDocument
	 */
	public function WHEELINGApprovalDocument()
	{	
		//echo"<pre>"; print_r($this->request->data); die();
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['WHEELINGApproval_application_id'])?$this->request->data['WHEELINGApproval_application_id']:0);
		//echo"<pre>"; print_r($this->request->data); die();
		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} 
		else{
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->Applications->viewApplication($id);
			$customer_id 			= $this->Session->read("Customers.id");
			$member_id 				= $this->Session->read("Members.id");

			if (!empty($applyOnlinesData) && (!empty($customer_id) || !empty($member_id))) {
				
				if((empty($this->request->data['wheeling_reason'])) || (empty($this->request->data['wheeling_approval_date'])) ) {
					$ErrorMessage 	= "Please select required the fields.";
					$success 		= 0;
					$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
					$this->ApiToken->SetAPIResponse('success',$success);

				} else {
					
						if(!empty($member_id)){
							$wheeling_approval_date = str_replace('/', '-', $this->request->data['wheeling_approval_date']); 
							$wheeling_approval_date = date('Y-m-d',strtotime($wheeling_approval_date));
							
							$this->WheelingApplicationDetails->updateAll(array('wheeling_approve'=>$this->request->data['wheeling_approve'],'wheeling_reason'=>$this->request->data['wheeling_reason'],'wheeling_approved_by'=>$member_id,'wheeling_approval_date'=>$wheeling_approval_date,'modified_by'=>$member_id,'modified_date'=>$this->NOW(),),array('application_id'=>$id));

							$this->Applications->updateAll(array('application_status'=>$this->ApplicationStages->WHELLING_APPROVED,'modified'=>$this->NOW(),'modified_by'=>$member_id),array('id'=>$id));
							$this->ApplicationStages->saveStatus($id,$this->ApplicationStages->WHELLING_APPROVED,$member_id,'');
						}
						
						$ErrorMessage 	= "Data Save Succesfully.";
						$success 		= 1;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					//echo"<pre>"; print_r($success); die();
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate form details.";
				$success 		= 0;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	
	/**
	 *
	 * fetchWheelingdataDocument
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to fetch data from cei application details aacording to application id.
	 *
	 */
	public function fetchWheelingdataDocument()
	{
		$appid 			= intval(decode($this->request->data['app_id']));
		$path 			= array();
		$path['Wheeling_Agreement_document'] 	= '';
		if(!empty($appid))
		{
			$WheelingDoc = $this->WheelingApplicationDetails->find('all',array(
           											'conditions' => [
           														'application_id'=>$appid,
           													]
           											))->first();
			if(!empty($WheelingDoc->Wheeling_Agreement_document))
			{
				$uploadPath 				= APPLICATIONS_PATH.$appid.'/';
				$path['Wheeling_Agreement_document'] 	= "<strong style='margin-bottom:10px;'><a target=\"_PROFILE\" href=\"".URL_HTTP.'app-docs/Wheeling_Agreement_document/'.encode($appid)."\" title=\"Click here to open\">View Wheeling Agreement</a></strong>";

			}
						
			echo json_encode(array('type'=>'ok','response'=>'','document_link'=>$path));
		}
		else
		{
			echo json_encode(array('type'=>'ok','response'=>'','document_link'=>$path));
		}
		exit;
	}
		/**
	 *
	 * cei_inspection
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is add to geo_cordinate
	 *
	 */
	public function cei_inspection($id = null) 
	{
		$customer_id 		= $this->Session->read("Customers.id");
		$member_id 			= $this->Session->read("Members.id");
		$application_type   = (isset($this->request->data['application_type'])?$this->request->data['application_type']:0);
		$application_id     = (isset($this->request->data['application_id'])?$this->request->data['application_id']:0);
		$is_member          = false;
		if(!empty($member_id)){
			$is_member      = true;
		}
		if(empty($id)) {
			$this->Flash->error('Please Select Valid Installer.');
			return $this->redirect('home');
		} else {

			$encode_id 						= $id;
			$id 							= intval(decode($id));
			$applyOnlinesData 				= $this->Applications->viewApplication($id);

			$wtg_applications 				= $this->ApplicationGeoLocation->find("all",['conditions'=>array('application_id'=>$id,'approved'=>1)])->toArray();

			// $geo_application_data 			= $this->ApplicationGeoLocation->find('all',
	  //                                       [ 'fields'=>['id','wtg_location','cei_re_application_details.cei_app_no','cei_re_application_details.cei_app_status'],
	  //                                           'join'=>[['table'=>'wind_wtg_detail','type'=>'left','conditions'=>'ApplicationGeoLocation.id = wind_wtg_detail.app_geo_loc_id'], 
	  //                                       ['table'=>'cei_re_application_details','type'=>'left','conditions'=>'ApplicationGeoLocation.id = cei_re_application_details.app_geo_loc_id']],
	  //                                           'conditions'=>['application_id'=>$id]])->toArray();	

			//echo"<pre>"; print_r($geo_application_data); die();
			$applicationCategory 			= $this->ApplicationCategory->find('all',array('conditions'=>array('id'=>$applyOnlinesData->application_type)))->first();
		}

		//echo"<pre>"; print_r($wtg_applications); die();
	  	
		$this->set('logged_in_id',$this->Session->read('Customers.id'));
		$this->set('is_member',$is_member);
		$this->set('encode_id',$encode_id);
		$this->set('applyOnlinesData',$applyOnlinesData);
		$this->set('applicationCategory',$applyOnlinesData);
		$this->set('CeiReApplicationDetails',$this->CeiReApplicationDetails);
		$this->set('wtg_applications',$wtg_applications);
		$this->set("pageTitle","CEI Inspection");
	}
		/**
	 *
	 * cei_inspection
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is add to geo_cordinate
	 *
	 */
	public function cei_drawing($id = null) 
	{
		$customer_id 		= $this->Session->read("Customers.id");
		$member_id 			= $this->Session->read("Members.id");
		$application_type   = (isset($this->request->data['application_type'])?$this->request->data['application_type']:0);
		$application_id     = (isset($this->request->data['application_id'])?$this->request->data['application_id']:0);
		$is_member          = false;
		if(!empty($member_id)){
			$is_member      = true;
		}
		if(empty($id)) {
			$this->Flash->error('Please Select Valid Installer.');
			return $this->redirect('home');
		} else {

			$encode_id 						= $id;
			$id 							= intval(decode($id));
			$applyOnlinesData 				= $this->Applications->viewApplication($id);

			$wtg_applications 				= $this->ApplicationGeoLocation->find("all",['conditions'=>array('application_id'=>$id,'approved'=>1)])->toArray();
			//$re_cei_drawing 				= $this->CeiReApplicationDetails->find("all",['fields'=>['app_geo_loc_id','drawing_app_status'],'conditions'=>array('application_id'=>$id,'drawing_app_status'=>'Completed')])->toArray();
			//echo"<pre>"; print_r($re_cei_drawing); die();

			//echo"<pre>"; print_r($wtg_applications); die();
			// $geo_application_data 			= $this->ApplicationGeoLocation->find('all',
	  //                                       [ 'fields'=>['id','wtg_location','cei_re_application_details.cei_app_no','cei_re_application_details.cei_app_status'],
	  //                                           'join'=>[['table'=>'wind_wtg_detail','type'=>'left','conditions'=>'ApplicationGeoLocation.id = wind_wtg_detail.app_geo_loc_id'], 
	  //                                       ['table'=>'cei_re_application_details','type'=>'left','conditions'=>'ApplicationGeoLocation.id = cei_re_application_details.app_geo_loc_id']],
	  //                                           'conditions'=>['application_id'=>$id]])->toArray();	

			//echo"<pre>"; print_r($geo_application_data); die();
			$applicationCategory 			= $this->ApplicationCategory->find('all',array('conditions'=>array('id'=>$applyOnlinesData->application_type)))->first();
		}

		//echo"<pre>"; print_r($wtg_applications); die();
	  	
		$this->set('logged_in_id',$this->Session->read('Customers.id'));
		$this->set('is_member',$is_member);
		$this->set('encode_id',$encode_id);
		$this->set('applyOnlinesData',$applyOnlinesData);
		$this->set('applicationCategory',$applyOnlinesData);
		$this->set('CeiReApplicationDetails',$this->CeiReApplicationDetails);
		$this->set('wtg_applications',$wtg_applications);
		$this->set("pageTitle","CEI Inspection");
	}
	/**
	 * SLDCDocument
	 * Behaviour : Public
	 * @defination : Method is use to upload other document.
	 */
	public function SLDCDocument()
	{
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['SLDC_application_id'])?$this->request->data['SLDC_application_id']:0);
		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} else {
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->Applications->viewApplication($id);
			$customer_id 			= $this->Session->read("Customers.id");
			$member_id 			= $this->Session->read("Members.id");
			if (!empty($applyOnlinesData) && (!empty($customer_id) || !empty($member_id))) {
				if ($this->request->is('post')) {
					if((empty($this->request->data['sldc_file1']['name'])) ) {
						$ErrorMessage 	= "Please upload at least one file and add capacity .";
						$success 		= 0;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					} else {
						
						$SldcApplicationDetailsEntity = $this->SldcApplicationDetails->newEntity();

						$SldcApplicationDetailsEntity->application_id		= $id;
						$SldcApplicationDetailsEntity->application_type		= $applyOnlinesData->application_type;
						
						$insert_id= $this->SldcApplicationDetails->save($SldcApplicationDetailsEntity);

						$insertId = $insert_id->id;
						
						$counter 					= 1;
						$file_array = array('sldc_file1'=>'sldc_title1','sldc_file2'=>'sldc_title2','sldc_file3'=>'sldc_title3','sldc_file4'=>'sldc_title4','sldc_file5'=>'sldc_title5');
						

						foreach ($file_array as $key => $value) {
							if(!empty($this->request->data[$key]['name']))
							{
								$prefix_file 	= '';
								$name 			= $this->request->data[$key]['name'];
								$ext 			= substr(strtolower(strrchr($name, '.')), 1);
								$file_name 		= $prefix_file.date('Ymdhms').rand();
								$uploadPath 	= APPLICATIONS_PATH.$id.'/';
								if(!file_exists(APPLICATIONS_PATH.$id)) {
									@mkdir(APPLICATIONS_PATH.$id, 0777);
								}
								$file_location 	= WWW_ROOT.$uploadPath.'SLDC'.'_'.$file_name.'.'.$ext;
								if(move_uploaded_file($this->request->data[$key]['tmp_name'],$file_location))
								{

									$couchdbId 		= $this->ReCouchdb->saveData($uploadPath,$file_location,$prefix_file, 'SLDC_'.$file_name.'.'.$ext,$this->Session->read('Customers.id'),'SLDC');

									$this->SldcApplicationDetails->updateAll(array('sldc_title'.$counter=>$this->request->data[$value],'sldc_file'.$counter=>'SLDC_'.$file_name.'.'.$ext),array('id'=>$insertId));
									$this->Applications->updateAll(array('application_status'=>$this->ApplicationStages->SLDC,'modified'=>$this->NOW(),'modified_by'=>$customer_id),array('id'=>$id));
									
								}
								//echo"<pre>"; print_r($name); 
							} $counter++;
						}
						$sldcentry 	= $this->SldcApplicationDetails->find('all',array('conditions'=>array('id'=>$insertId)))->first();
						if(!empty($sldcentry)) {
							//$this->SendApplicationLetterToCustomer($id);
							
							$this->ApplicationStages->saveStatus($id,$this->ApplicationStages->SLDC,$customer_id,'');
							$ErrorMessage 	= "Upload SLDC Documents Succesfully.";
							$success 		= 1;
							$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
							$this->ApiToken->SetAPIResponse('success',$success);
						}
						 else {
							$ErrorMessage 	= "Error while uploading documents.";
							$success 		= 0;
							$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
							$this->ApiToken->SetAPIResponse('success',$success);
						}
					}	
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate form details.";
				$success 		= 0;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	/**
	 * SLDCAppApprovalDocument
	 * Behaviour : Public
	 * @defination : Method is use to complete the SLDCAppApprovalDocument
	 */
	public function SLDCApprovalDocument()
	{	
		//echo"<pre>"; print_r($this->request->data); die();
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['SLDCApproval_application_id'])?$this->request->data['SLDCApproval_application_id']:0);
		//echo"<pre>"; print_r($this->request->data); die();
		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} 
		else{
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->Applications->viewApplication($id);
			$customer_id 			= $this->Session->read("Customers.id");
			$member_id 				= $this->Session->read("Members.id");

			if (!empty($applyOnlinesData) && (!empty($customer_id) || !empty($member_id))) {
				
				if((empty($this->request->data['sldc_reason'])) || (empty($this->request->data['sldc_approved_date'])) ) {
					$ErrorMessage 	= "Please select required the fields.";
					$success 		= 0;
					$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
					$this->ApiToken->SetAPIResponse('success',$success);

				} else {
					
						if(!empty($member_id)){
							$sldc_approved_date = str_replace('/', '-', $this->request->data['sldc_approved_date']); 
							$sldc_approved_date = date('Y-m-d',strtotime($sldc_approved_date));
							
							$this->SldcApplicationDetails->updateAll(array('sldc_approval'=>$this->request->data['sldc_approval'],'sldc_reason'=>$this->request->data['sldc_reason'],'sldc_approved_date'=>$sldc_approved_date,'modified_by'=>$member_id,'modified_date'=>$this->NOW(),),array('application_id'=>$id));

							$this->Applications->updateAll(array('application_status'=>$this->ApplicationStages->SLDC_APPROVED,'modified'=>$this->NOW(),'modified_by'=>$member_id),array('id'=>$id));
							$this->ApplicationStages->saveStatus($id,$this->ApplicationStages->SLDC_APPROVED,$member_id,'');
						}
						
						$ErrorMessage 	= "Data Save Succesfully.";
						$success 		= 1;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					//echo"<pre>"; print_r($success); die();
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate form details.";
				$success 		= 0;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	/**
	 *
	 * fetchSLDCdataDocument
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to fetch data from cei application details aacording to application id.
	 *
	 */
	public function fetchSldcdataDocument()
	{
		
		$appid 			= intval(decode($this->request->data['app_id']));
		$path 			= array();
		$path['sldc_file1'] 	= '';
		if(!empty($appid))
		{
			$SldcDoc = $this->SldcApplicationDetails->find('all',array(
           											'conditions' => [
           														'application_id'=>$appid,
           													]
           											))->first();
			if(!empty($SldcDoc->sldc_file1))
			{
				$uploadPath 				= APPLICATIONS_PATH.$appid.'/';
				$path['sldc_file1'] 	= "<strong style='margin-bottom:10px;'><a target=\"_PROFILE\" href=\"".URL_HTTP.'app-docs/sldc_file1/'.encode($appid)."\" title=\"Click here to open\">View ". $SldcDoc->sldc_title1." Document</a></strong>";

			}
			if(!empty($SldcDoc->sldc_file2))
			{
				$uploadPath 				= APPLICATIONS_PATH.$appid.'/';
				$path['sldc_file2'] 	= "<strong style='margin-bottom:10px;'><a target=\"_PROFILE\" href=\"".URL_HTTP.'app-docs/sldc_file2/'.encode($appid)."\" title=\"Click here to open\">View ". $SldcDoc->sldc_title2." Document</a></strong>";

			}
			if(!empty($SldcDoc->sldc_file3))
			{
				$uploadPath 				= APPLICATIONS_PATH.$appid.'/';
				$path['sldc_file3'] 	= "<strong style='margin-bottom:10px;'><a target=\"_PROFILE\" href=\"".URL_HTTP.'app-docs/sldc_file3/'.encode($appid)."\" title=\"Click here to open\">View ". $SldcDoc->sldc_title3." Document</a></strong>";

			}
			if(!empty($SldcDoc->sldc_file4))
			{
				$uploadPath 				= APPLICATIONS_PATH.$appid.'/';
				$path['sldc_file4'] 	= "<strong style='margin-bottom:10px;'><a target=\"_PROFILE\" href=\"".URL_HTTP.'app-docs/sldc_file4/'.encode($appid)."\" title=\"Click here to open\">View ". $SldcDoc->sldc_title4." Document</a></strong>";

			}
			if(!empty($SldcDoc->sldc_file5))
			{
				$uploadPath 				= APPLICATIONS_PATH.$appid.'/';
				$path['sldc_file5'] 	= "<strong style='margin-bottom:10px;'><a target=\"_PROFILE\" href=\"".URL_HTTP.'app-docs/sldc_file5/'.encode($appid)."\" title=\"Click here to open\">View ". $SldcDoc->sldc_title5." Document</a></strong>";

			}
           	//$path 		= $uploadPath.$SldcDoc->file_name;	
           	// /echo"<pre>"; print_r($path); die();						
			echo json_encode(array('type'=>'ok','response'=>'','document_link'=>$path));
		}
		else
		{
			echo json_encode(array('type'=>'ok','response'=>'','document_link'=>$path));
		}
		exit;
	}
	/**
	 * WHEELINGDocument
	 * Behaviour : Public
	 * @defination : Method is use to complete the WHEELINGDocument
	 */
	public function BPTADocument()
	{	
		//echo"<pre>"; print_r($this->request->data); die();
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['BPTA_application_id'])?$this->request->data['BPTA_application_id']:0);
		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} else {
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->Applications->viewApplication($id);
			$customer_id 			= $this->Session->read("Customers.id");
			$member_id 			= $this->Session->read("Members.id");
			if (!empty($applyOnlinesData) && (!empty($customer_id) || !empty($member_id))) {
				if ($this->request->is('post')) {
					if((empty($this->request->data['bpta_document1']['name'])) || (empty($this->request->data['bpta_document2']['name'])) ) {
						$ErrorMessage 	= "Please upload at least one file .";
						$success 		= 0;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					} else {
						
						$BptaApplicationDetailsEntity = $this->BptaApplicationDetails->newEntity();

						$BptaApplicationDetailsEntity->application_id		= $id;
						$BptaApplicationDetailsEntity->application_type		= $applyOnlinesData->application_type;
						$BptaApplicationDetailsEntity->created_date 		= $this->NOW();
						
						$insert_id= $this->BptaApplicationDetails->save($BptaApplicationDetailsEntity);

						$insertId = $insert_id->id;
						
						if(!empty($this->request->data['bpta_document1']['name']))
						{
							$prefix_file 	= '';
							$name 			= $this->request->data['bpta_document1']['name'];
							$ext 			= substr(strtolower(strrchr($name, '.')), 1);
							$file_name 		= $prefix_file.date('Ymdhms').rand();
							$uploadPath 	= APPLICATIONS_PATH.$id.'/';
							if(!file_exists(APPLICATIONS_PATH.$id)) {
								@mkdir(APPLICATIONS_PATH.$id, 0777);
							}
							$file_location 	= WWW_ROOT.$uploadPath.'BPTA'.'_'.$file_name.'.'.$ext;
							if(move_uploaded_file($this->request->data['bpta_document1']['tmp_name'],$file_location))
							{

								$couchdbId 		= $this->ReCouchdb->saveData($uploadPath,$file_location,$prefix_file, 'BPTA_'.$file_name.'.'.$ext,$this->Session->read('Customers.id'),'BPTA');

								$this->BptaApplicationDetails->updateAll(array('bpta_document1'=>'BPTA_'.$file_name.'.'.$ext),array('id'=>$insertId));
								//$this->Applications->updateAll(array('application_status'=>$this->ApplicationStages->BPTA,'modified'=>$this->NOW(),'modified_by'=>$customer_id),array('id'=>$id));
								
							}
						} 
						if(!empty($this->request->data['bpta_document2']['name']))
						{
							$prefix_file 	= '';
							$name 			= $this->request->data['bpta_document2']['name'];
							$ext 			= substr(strtolower(strrchr($name, '.')), 1);
							$file_name 		= $prefix_file.date('Ymdhms').rand();
							$uploadPath 	= APPLICATIONS_PATH.$id.'/';
							if(!file_exists(APPLICATIONS_PATH.$id)) {
								@mkdir(APPLICATIONS_PATH.$id, 0777);
							}
							$file_location 	= WWW_ROOT.$uploadPath.'BPTA'.'_'.$file_name.'.'.$ext;
							if(move_uploaded_file($this->request->data['bpta_document2']['tmp_name'],$file_location))
							{

								$couchdbId 		= $this->ReCouchdb->saveData($uploadPath,$file_location,$prefix_file, 'BPTA_'.$file_name.'.'.$ext,$this->Session->read('Customers.id'),'BPTA');

								$this->BptaApplicationDetails->updateAll(array('bpta_document2'=>'BPTA_'.$file_name.'.'.$ext),array('id'=>$insertId));
								//$this->Applications->updateAll(array('application_status'=>$this->ApplicationStages->BPTA,'modified'=>$this->NOW(),'modified_by'=>$customer_id),array('id'=>$id));
								
							}
						} 
						$sldcentry 	= $this->BptaApplicationDetails->find('all',array('conditions'=>array('id'=>$insertId)))->first();
						if(!empty($sldcentry)) {
							//$this->SendApplicationLetterToCustomer($id);
							
							$this->ApplicationStages->saveStatus($id,$this->ApplicationStages->BPTA,$customer_id,'');
							$ErrorMessage 	= "Upload BPTA Documents Succesfully.";
							$success 		= 1;
							$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
							$this->ApiToken->SetAPIResponse('success',$success);
						}
						 else {
							$ErrorMessage 	= "Error while uploading documents.";
							$success 		= 0;
							$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
							$this->ApiToken->SetAPIResponse('success',$success);
						}
					}	
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate form details.";
				$success 		= 0;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	 * BPTAApprovalDocument
	 * Behaviour : Public
	 * @defination : Method is use to complete the BPTAApprovalDocument
	 */
	public function BPTAApprovalDocument()
	{	
		//echo"<pre>"; print_r($this->request->data); die();
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['BPTAApproval_application_id'])?$this->request->data['BPTAApproval_application_id']:0);
		//echo"<pre>"; print_r($this->request->data); die();
		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} 
		else{
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->Applications->viewApplication($id);
			$customer_id 			= $this->Session->read("Customers.id");
			$member_id 				= $this->Session->read("Members.id");

			if (!empty($applyOnlinesData) && (!empty($customer_id) || !empty($member_id))) {
				
				if((empty($this->request->data['bpta_reason'])) || (empty($this->request->data['bpta_approval_date'])) ) {
					$ErrorMessage 	= "Please select required the fields.";
					$success 		= 0;
					$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
					$this->ApiToken->SetAPIResponse('success',$success);

				} else {
					
						if(!empty($member_id)){
							$bpta_approval_date = str_replace('/', '-', $this->request->data['bpta_approval_date']); 
							$bpta_approval_date = date('Y-m-d',strtotime($bpta_approval_date));
							
							$this->BptaApplicationDetails->updateAll(array('bpta_approve'=>$this->request->data['bpta_approve'],'bpta_reason'=>$this->request->data['bpta_reason'],'bpta_approved_by'=>$member_id,'bpta_approval_date'=>$bpta_approval_date,'modified_by'=>$member_id,'modified_date'=>$this->NOW()),array('application_id'=>$id));

							$this->Applications->updateAll(array('application_status'=>$this->ApplicationStages->BPTA_APPROVED,'modified'=>$this->NOW(),'modified_by'=>$member_id),array('id'=>$id));
							$this->ApplicationStages->saveStatus($id,$this->ApplicationStages->BPTA_APPROVED,$member_id,'');
						}
						
						$ErrorMessage 	= "Data Save Succesfully.";
						$success 		= 1;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					//echo"<pre>"; print_r($success); die();
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate form details.";
				$success 		= 0;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	
	/**
	 *
	 * fetchBPTAdataDocument
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to fetch data from cei application details aacording to application id.
	 *
	 */
	public function fetchBPTAdataDocument()
	{
		
		$appid 			= intval(decode($this->request->data['app_id']));
		$path 			= array();
		$path['bpta_document1'] 	= '';
		if(!empty($appid))
		{
			$BptaDoc = $this->BptaApplicationDetails->find('all',array(
           											'conditions' => [
           														'application_id'=>$appid,
           													]
           											))->first();
			//echo"<pre>"; print_r($BptaDoc); die();
			if(!empty($BptaDoc->bpta_document1))
			{
				$uploadPath 				= APPLICATIONS_PATH.$appid.'/';
				$path['bpta_document1'] 	= "<strong style='margin-bottom:10px;'><a target=\"_PROFILE\" href=\"".URL_HTTP.'app-docs/bpta_document1/'.encode($appid)."\" title=\"Click here to open\">View Bpta 1 Document</a></strong>";

			}
			if(!empty($BptaDoc->bpta_document2))
			{
				$uploadPath 				= APPLICATIONS_PATH.$appid.'/';
				$path['bpta_document2'] 	= "<strong style='margin-bottom:10px;'><a target=\"_PROFILE\" href=\"".URL_HTTP.'app-docs/bpta_document2/'.encode($appid)."\" title=\"Click here to open\">View Bpta 2 Document</a></strong>";

			}
							
			echo json_encode(array('type'=>'ok','response'=>'','document_link'=>$path));
		}
		else
		{
			echo json_encode(array('type'=>'ok','response'=>'','document_link'=>$path));
		}
		exit;
	}


	/**
	 * METER_SEALINGDocument
	 * Behaviour : Public
	 * @defination : Method is use to complete the METER_SEALINGDocument
	 */
	public function METER_SEALINGDocument()
	{	
		//echo"<pre>"; print_r($this->request->data); die();
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['METER_SEALING_application_id'])?$this->request->data['METER_SEALING_application_id']:0);
		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} else {
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->Applications->viewApplication($id);
			$customer_id 			= $this->Session->read("Customers.id");
			$member_id 			= $this->Session->read("Members.id");
			if (!empty($applyOnlinesData) && (!empty($customer_id) || !empty($member_id))) {
				if ($this->request->is('post')) {
					if((empty($this->request->data['meter_sealing_report']['name'])) ) {
						$ErrorMessage 	= "Please upload at least one file .";
						$success 		= 0;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					} else {
						
						$MeterSealingApplicationDetailsEntity = $this->MeterSealingApplicationDetails->newEntity();

						$MeterSealingApplicationDetailsEntity->application_id		= $id;
						$MeterSealingApplicationDetailsEntity->application_type		= $applyOnlinesData->application_type;
						$MeterSealingApplicationDetailsEntity->created_date 		= $this->NOW();
						
						$insert_id= $this->MeterSealingApplicationDetails->save($MeterSealingApplicationDetailsEntity);

						$insertId = $insert_id->id;
						
						if(!empty($this->request->data['meter_sealing_report']['name']))
						{
							$prefix_file 	= '';
							$name 			= $this->request->data['meter_sealing_report']['name'];
							$ext 			= substr(strtolower(strrchr($name, '.')), 1);
							$file_name 		= $prefix_file.date('Ymdhms').rand();
							$uploadPath 	= APPLICATIONS_PATH.$id.'/';
							if(!file_exists(APPLICATIONS_PATH.$id)) {
								@mkdir(APPLICATIONS_PATH.$id, 0777);
							}
							$file_location 	= WWW_ROOT.$uploadPath.'meter_sealing'.'_'.$file_name.'.'.$ext;
							if(move_uploaded_file($this->request->data['meter_sealing_report']['tmp_name'],$file_location))
							{

								$couchdbId 		= $this->ReCouchdb->saveData($uploadPath,$file_location,$prefix_file, 'meter_sealing_'.$file_name.'.'.$ext,$this->Session->read('Customers.id'),'meter_sealing');

								$this->MeterSealingApplicationDetails->updateAll(array('meter_sealing_report'=>'meter_sealing_'.$file_name.'.'.$ext),array('id'=>$insertId));
								
							}
						} 
						$MeterSealingentry 	= $this->MeterSealingApplicationDetails->find('all',array('conditions'=>array('id'=>$insertId)))->first();
						if(!empty($MeterSealingentry)) {
							
							$this->ApplicationStages->saveStatus($id,$this->ApplicationStages->METER_SEALING,$customer_id,'');
							$ErrorMessage 	= "Upload Documents Succesfully.";
							$success 		= 1;
							$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
							$this->ApiToken->SetAPIResponse('success',$success);
						}
						 else {
							$ErrorMessage 	= "Error while uploading documents.";
							$success 		= 0;
							$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
							$this->ApiToken->SetAPIResponse('success',$success);
						}
					}	
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate form details.";
				$success 		= 0;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	 * METER_SEALINGApprovalDocument
	 * Behaviour : Public
	 * @defination : Method is use to complete the METER_SEALINGApprovalDocument
	 */
	public function METER_SEALINGApprovalDocument()
	{	
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['METER_SEALINGApproval_application_id'])?$this->request->data['METER_SEALINGApproval_application_id']:0);
		//echo"<pre>"; print_r($this->request->data); die();
		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} 
		else{
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->Applications->viewApplication($id);
			$customer_id 			= $this->Session->read("Customers.id");
			$member_id 				= $this->Session->read("Members.id");

			if (!empty($applyOnlinesData) && (!empty($customer_id) || !empty($member_id))) {
				
				if((empty($this->request->data['meter_reason'])) || (empty($this->request->data['meter_approval_date'])) ) {
					$ErrorMessage 	= "Please select required the fields.";
					$success 		= 0;
					$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
					$this->ApiToken->SetAPIResponse('success',$success);

				} else {
					
						if(!empty($member_id)){
							$meter_approval_date = str_replace('/', '-', $this->request->data['meter_approval_date']); 
							$meter_approval_date = date('Y-m-d',strtotime($meter_approval_date));
							
							$this->MeterSealingApplicationDetails->updateAll(array('meter_approve'=>$this->request->data['meter_approve'],'meter_reason'=>$this->request->data['meter_reason'],'meter_approved_by'=>$member_id,'meter_approval_date'=>$meter_approval_date,'modified_by'=>$member_id,'modified_date'=>$this->NOW(),),array('application_id'=>$id));

							$this->Applications->updateAll(array('application_status'=>$this->ApplicationStages->METER_SEALING_APPROVED,'modified'=>$this->NOW(),'modified_by'=>$member_id),array('id'=>$id));
							$this->ApplicationStages->saveStatus($id,$this->ApplicationStages->METER_SEALING_APPROVED,$member_id,'');
						}
						
						$ErrorMessage 	= "Data Save Succesfully.";
						$success 		= 1;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					//echo"<pre>"; print_r($success); die();
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate form details.";
				$success 		= 0;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	
	/**
	 *
	 * fetchWheelingdataDocument
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to fetch data from cei application details aacording to application id.
	 *
	 */
	public function fetchMETER_SEALINGdataDocument()
	{
		$appid 			= intval(decode($this->request->data['app_id']));
		$path 			= array();
		$path['wheeling_agreement'] 	= '';
		if(!empty($appid))
		{
			$MeterSealingDoc = $this->MeterSealingApplicationDetails->find('all',array(
           											'conditions' => [
           														'application_id'=>$appid,
           													]
           											))->first();
			//echo"<pre>"; print_r($MeterSealingDoc); die();
			if(!empty($MeterSealingDoc->meter_sealing_report))
			{
				$uploadPath 				= APPLICATIONS_PATH.$appid.'/';
				$path['meter_sealing_report'] 	= "<strong style='margin-bottom:10px;'><a target=\"_PROFILE\" href=\"".URL_HTTP.'app-docs/meter_sealing_report/'.encode($appid)."\" title=\"Click here to open\">View Meter Sealing Report</a></strong>";

			}
						
			echo json_encode(array('type'=>'ok','response'=>'','document_link'=>$path));
		}
		else
		{
			echo json_encode(array('type'=>'ok','response'=>'','document_link'=>$path));
		}
		exit;
	}

	/**
	 * POWER_INJECTIONDocument
	 * Behaviour : Public
	 * @defination : Method is use to complete the POWER_INJECTIONDocument
	 */
	public function POWER_INJECTIONDocument()
	{	
		//echo"<pre>"; print_r($this->request->data); die();
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['POWER_INJECTION_application_id'])?$this->request->data['POWER_INJECTION_application_id']:0);
		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} else {
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->Applications->viewApplication($id);
			$customer_id 			= $this->Session->read("Customers.id");
			$member_id 			= $this->Session->read("Members.id");
			if (!empty($applyOnlinesData) && (!empty($customer_id) || !empty($member_id))) {
				if ($this->request->is('post')) {
					if((empty($this->request->data['power_injection_report']['name'])) ) {
						$ErrorMessage 	= "Please upload at least one file .";
						$success 		= 0;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					} else {
						
						$PowerInjectionApplicationDetailsEntity = $this->PowerInjectionApplicationDetails->newEntity();

						$PowerInjectionApplicationDetailsEntity->application_id		= $id;
						$PowerInjectionApplicationDetailsEntity->application_type		= $applyOnlinesData->application_type;
						$PowerInjectionApplicationDetailsEntity->created_date 		= $this->NOW();
						
						$insert_id= $this->PowerInjectionApplicationDetails->save($PowerInjectionApplicationDetailsEntity);

						$insertId = $insert_id->id;
						
						if(!empty($this->request->data['power_injection_report']['name']))
						{
							$prefix_file 	= '';
							$name 			= $this->request->data['power_injection_report']['name'];
							$ext 			= substr(strtolower(strrchr($name, '.')), 1);
							$file_name 		= $prefix_file.date('Ymdhms').rand();
							$uploadPath 	= APPLICATIONS_PATH.$id.'/';
							if(!file_exists(APPLICATIONS_PATH.$id)) {
								@mkdir(APPLICATIONS_PATH.$id, 0777);
							}
							$file_location 	= WWW_ROOT.$uploadPath.'power_injection'.'_'.$file_name.'.'.$ext;
							if(move_uploaded_file($this->request->data['power_injection_report']['tmp_name'],$file_location))
							{

								$couchdbId 		= $this->ReCouchdb->saveData($uploadPath,$file_location,$prefix_file, 'power_injection_'.$file_name.'.'.$ext,$this->Session->read('Customers.id'),'power_injection');

								$this->PowerInjectionApplicationDetails->updateAll(array('power_injection_report'=>'power_injection_'.$file_name.'.'.$ext),array('id'=>$insertId));
								
							}
						} 
						$PowerInjectionentry 	= $this->PowerInjectionApplicationDetails->find('all',array('conditions'=>array('id'=>$insertId)))->first();
						if(!empty($PowerInjectionentry)) {
							
							$this->ApplicationStages->saveStatus($id,$this->ApplicationStages->POWER_INJECTION,$customer_id,'');
							$ErrorMessage 	= "Upload Documents Succesfully.";
							$success 		= 1;
							$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
							$this->ApiToken->SetAPIResponse('success',$success);
						}
						 else {
							$ErrorMessage 	= "Error while uploading documents.";
							$success 		= 0;
							$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
							$this->ApiToken->SetAPIResponse('success',$success);
						}
					}	
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate form details.";
				$success 		= 0;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	 * POWER_INJECTIONApprovalDocument
	 * Behaviour : Public
	 * @defination : Method is use to complete the POWER_INJECTIONApprovalDocument
	 */
	public function POWER_INJECTIONApprovalDocument()
	{	
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['POWER_INJECTIONApproval_application_id'])?$this->request->data['POWER_INJECTIONApproval_application_id']:0);
		//echo"<pre>"; print_r($this->request->data); die();
		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} 
		else{
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->Applications->viewApplication($id);
			$customer_id 			= $this->Session->read("Customers.id");
			$member_id 				= $this->Session->read("Members.id");

			if (!empty($applyOnlinesData) && (!empty($customer_id) || !empty($member_id))) {
				
				if((empty($this->request->data['power_injection_reason'])) || (empty($this->request->data['power_injection_approval_date'])) ) {
					$ErrorMessage 	= "Please select required the fields.";
					$success 		= 0;
					$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
					$this->ApiToken->SetAPIResponse('success',$success);

				} else {
					
						if(!empty($member_id)){
							$power_injection_approval_date = str_replace('/', '-', $this->request->data['power_injection_approval_date']); 
							$power_injection_approval_date = date('Y-m-d',strtotime($power_injection_approval_date));
							
							$this->PowerInjectionApplicationDetails->updateAll(array('power_injection_approve'=>$this->request->data['power_injection_approve'],'power_injection_reason'=>$this->request->data['power_injection_reason'],'power_injection_approved_by'=>$member_id,'power_injection_approval_date'=>$power_injection_approval_date,'modified_by'=>$member_id,'modified_date'=>$this->NOW(),),array('application_id'=>$id));

							$this->Applications->updateAll(array('application_status'=>$this->ApplicationStages->POWER_INJECTION_APPROVED,'modified'=>$this->NOW(),'modified_by'=>$member_id),array('id'=>$id));
							$this->ApplicationStages->saveStatus($id,$this->ApplicationStages->POWER_INJECTION_APPROVED,$member_id,'');
						}
						
						$ErrorMessage 	= "Data Save Succesfully.";
						$success 		= 1;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					//echo"<pre>"; print_r($success); die();
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate form details.";
				$success 		= 0;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	
	/**
	 *
	 * fetchWheelingdataDocument
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to fetch data from cei application details aacording to application id.
	 *
	 */
	public function fetchPOWER_INJECTIONdataDocument()
	{
		$appid 			= intval(decode($this->request->data['app_id']));
		$path 			= array();
		$path['wheeling_agreement'] 	= '';
		if(!empty($appid))
		{
			$PowerInjectionDoc = $this->PowerInjectionApplicationDetails->find('all',array(
           											'conditions' => [
           														'application_id'=>$appid,
           													]
           											))->first();
			//echo"<pre>"; print_r($PowerInjectionDoc); die();
			if(!empty($PowerInjectionDoc->power_injection_report))
			{
				$uploadPath 				= APPLICATIONS_PATH.$appid.'/';
				$path['power_injection_report'] 	= "<strong style='margin-bottom:10px;'><a target=\"_PROFILE\" href=\"".URL_HTTP.'app-docs/power_injection_report/'.encode($appid)."\" title=\"Click here to open\">View Power Injection Report</a></strong>";

			}
						
			echo json_encode(array('type'=>'ok','response'=>'','document_link'=>$path));
		}
		else
		{
			echo json_encode(array('type'=>'ok','response'=>'','document_link'=>$path));
		}
		exit;
	}
	/**
	 *
	 * IntimationCompletion
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to complete Intimation under execution popup
	 *
	 */
	public function IntimationCompletion()
	{
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['comp_intimation_id'])?$this->request->data['comp_intimation_id']:0);
		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} else {
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->Applications->viewApplication($id);
			$customer_id 			= $this->Session->read("Customers.id");
			$member_id 			= $this->Session->read("Members.id");
			if (!empty($applyOnlinesData) && (!empty($customer_id))) {
				if ($this->request->is('post')) {
					
						
						$ApplicationProjectCommissioningEntity = $this->ApplicationProjectCommissioning->newEntity();

						$ApplicationProjectCommissioningEntity->application_id			= $id;
						$ApplicationProjectCommissioningEntity->application_type		= $applyOnlinesData->application_type;
						$ApplicationProjectCommissioningEntity->intimation_date			= $this->NOW();
						$ApplicationProjectCommissioningEntity->intimation_completion	= 1;
						$ApplicationProjectCommissioningEntity->created_by 				= $customer_id;
						$ApplicationProjectCommissioningEntity->created 				= $this->NOW();
						
						$insert_id= $this->ApplicationProjectCommissioning->save($ApplicationProjectCommissioningEntity);

						$insertId = $insert_id->id;
						
						$ProjectCommissioningentry 	= $this->ApplicationProjectCommissioning->find('all',array('conditions'=>array('id'=>$insertId)))->first();
						if(!empty($ProjectCommissioningentry)) {
							
							$this->ApplicationStages->saveStatus($id,$this->ApplicationStages->INTIMATION_FOR_COMPLETION,$customer_id,'');
							$ErrorMessage 	= "Intimation submitted successfully.";
							$success 		= 1;
							$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
							$this->ApiToken->SetAPIResponse('success',$success);
						}
						 else {
							$ErrorMessage 	= "Error while uploading documents.";
							$success 		= 0;
							$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
							$this->ApiToken->SetAPIResponse('success',$success);
						}
						
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate form details.";
				$success 		= 0;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;

	}
	// /**
	//  * ProjectCommissioningStage
	//  * Behaviour : Public
	//  * @defination : Method is use to complete the ProjectCommissioningStage
	//  */
	// public function ProjectCommissioningStage_old()
	// {	
		
	// 	$this->autoRender 	= false;
	// 	$id 				= (isset($this->request->data['ProjectCommissioning_application_id'])?$this->request->data['ProjectCommissioning_application_id']:0);
	// 	//echo"<pre>"; print_r($this->request->data); die();
	// 	if(empty($id)) {
	// 		$ErrorMessage 	= "Invalid Request. Please validate form details.";
	// 		$success 		= 0;
	// 		$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
	// 		$this->ApiToken->SetAPIResponse('success',$success);
	// 	} 
	// 	else{
	// 		$encode_id 				= $id;
	// 		$id 					= intval(decode($id));
	// 		$applyOnlinesData 		= $this->Applications->viewApplication($id);
	// 		$customer_id 			= $this->Session->read("Customers.id");
	// 		$member_id 				= $this->Session->read("Members.id");


	// 		if (!empty($applyOnlinesData) && (!empty($customer_id) || !empty($member_id))) {
	// 			if ($this->request->is('post')) {
	// 				if((empty($this->request->data['pc_upload_file']['name'])) ||(empty($this->request->data['PC_meter_no'])) || (empty($this->request->data['PC_date'])) ) {
	// 					$ErrorMessage 	= "Please select All the fields.";
	// 					$success 		= 0;
	// 					$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
	// 					$this->ApiToken->SetAPIResponse('success',$success);
	// 				} else {
	// 					if(!empty($this->request->data['pc_upload_file']['name']))
	// 					{	

	// 						$ApplicationsDocsEntity = $this->ApplicationsDocs->newEntity();
	// 						$prefix_file 	= '';
	// 						$name 			= $this->request->data['pc_upload_file']['name'];

	// 						$ext 			= substr(strtolower(strrchr($name, '.')), 1);

	// 						$file_name 		= $prefix_file.date('Ymdhms').rand();
	// 						$uploadPath 	= APPLICATIONS_PATH.$id.'/';
	// 						if(!file_exists(APPLICATIONS_PATH.$id)) {
	// 							@mkdir(APPLICATIONS_PATH.$id, 0777);
	// 						}
	// 						$file_location 	= WWW_ROOT.$uploadPath.'ProjectCommissioning'.'_'.$file_name.'.'.$ext;
	// 						if(move_uploaded_file($this->request->data['pc_upload_file']['tmp_name'],$file_location))
	// 						{

	// 							$couchdbId 		= $this->ReCouchdb->saveData($uploadPath,$file_location,$prefix_file, 'ProjectCommissioning_'.$file_name.'.'.$ext,$this->Session->read('Customers.id'),'ProjectCommissioning');
	// 							$ApplicationsDocsEntity->couchdb_id			= $couchdbId;
	// 							$ApplicationsDocsEntity->application_id		= $id;
	// 							$ApplicationsDocsEntity->file_name        	= 'ProjectCommissioning'.'_'.$file_name.'.'.$ext;
	// 							$ApplicationsDocsEntity->doc_type         	= 'ProjectCommissioning';
	// 							$ApplicationsDocsEntity->title            	= 'ProjectCommissioning Document';
	// 							$ApplicationsDocsEntity->created          	= $this->NOW();
	// 							$ApplicationsDocsEntity->created_by         = $customer_id;
	// 							$application_status 						= $this->ApplicationStages->ProjectCommissioning;
	// 							$this->ApplicationsDocs->deleteAll(['application_id' => $id,'doc_type'=>'ProjectCommissioning']);
								
								
	// 						}
	// 						$this->ApplicationsDocs->save($ApplicationsDocsEntity);
	// 						$PC_date = date('Y-m-d',strtotime($this->request->data['PC_date']));
	// 						$ProjectCommissioning = $this->ApplicationProjectCommissioning->newEntity();
	// 						$ProjectCommissioning->PC_meter_no         	= $this->request->data['PC_meter_no'];
	// 						$ProjectCommissioning->PC_date            	= $PC_date;
	// 						$ProjectCommissioning->application_id       = $id;
	// 						$ProjectCommissioning->created          	= $this->NOW();
	// 						$ProjectCommissioning->created_by           = $customer_id;
	// 						$this->ApplicationProjectCommissioning->save($ProjectCommissioning);
	// 					}

	// 					if($this->ApplicationsDocs->save($ApplicationsDocsEntity)) {
	// 						$this->Applications->updateAll(array('application_status'=>$application_status,'modified'=>$this->NOW(),'modified_by'=>$customer_id),array('id'=>$id));
							
	// 						$this->ApplicationStages->saveStatus($id,$this->ApplicationStages->ProjectCommissioning,$customer_id,'');
	// 						$ErrorMessage 	= "Data Save Succesfully.";
	// 						$success 		= 1;
	// 						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
	// 						$this->ApiToken->SetAPIResponse('success',$success);
	// 					} else {
	// 						$ErrorMessage 	= "Error while uploading document.";
	// 						$success 		= 0;
	// 						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
	// 						$this->ApiToken->SetAPIResponse('success',$success);
	// 					}
	// 				}
	// 			}
	// 		} else {
	// 			$ErrorMessage 	= "Invalid Request. Please validate form details.";
	// 			$success 		= 0;
	// 			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
	// 			$this->ApiToken->SetAPIResponse('success',$success);
	// 		}
	// 	}
	// 	echo $this->ApiToken->GenerateAPIResponse();
	// 	exit;
	// }

	/**
	 * ProjectCommissioningStage
	 * Behaviour : Public
	 * @defination : Method is use to complete the ProjectCommissioningStage
	 */
	public function ProjectCommissioningStage()
	{	
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['ProjectCommissioning_application_id'])?$this->request->data['ProjectCommissioning_application_id']:0);
		//echo"<pre>"; print_r($this->request->data); die();
		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} 
		else{
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->Applications->viewApplication($id);
			$customer_id 			= $this->Session->read("Customers.id");
			$member_id 				= $this->Session->read("Members.id");

			if (!empty($applyOnlinesData) && (!empty($customer_id) || !empty($member_id))) {
				
				if((empty($this->request->data['pc_upload_file']['name'])) ||(empty($this->request->data['PC_meter_no'])) || (empty($this->request->data['PC_date'])) ) {
					$ErrorMessage 	= "Please select required the fields.";
					$success 		= 0;
					$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
					$this->ApiToken->SetAPIResponse('success',$success);

				} else {
						
						if(!empty($member_id)){

							if(!empty($this->request->data['pc_upload_file']['name']))
							{
								$prefix_file 	= '';
								$name 			= $this->request->data['pc_upload_file']['name'];
								$ext 			= substr(strtolower(strrchr($name, '.')), 1);
								$file_name 		= $prefix_file.date('Ymdhms').rand();
								$uploadPath 	= APPLICATIONS_PATH.$id.'/';
								if(!file_exists(APPLICATIONS_PATH.$id)) {
									@mkdir(APPLICATIONS_PATH.$id, 0777);
								}
								$file_location 	= WWW_ROOT.$uploadPath.'pc_upload_file'.'_'.$file_name.'.'.$ext;
								if(move_uploaded_file($this->request->data['pc_upload_file']['tmp_name'],$file_location))
								{

									$couchdbId 		= $this->ReCouchdb->saveData($uploadPath,$file_location,$prefix_file, 'pc_upload_file_'.$file_name.'.'.$ext,$this->Session->read('Customers.id'),'pc_upload_file');

									$this->ApplicationProjectCommissioning->updateAll(array('pc_upload_file'=>'pc_upload_file_'.$file_name.'.'.$ext),array('application_id'=>$id));
									
								}
							} 
							$PC_date = str_replace('/', '-', $this->request->data['PC_date']); 
							$PC_date = date('Y-m-d',strtotime($PC_date));
							
							$this->ApplicationProjectCommissioning->updateAll(array('PC_meter_no'=>$this->request->data['PC_meter_no'],'PC_date'=>$PC_date,'modified_by'=>$member_id,'modified_date'=>$this->NOW()),array('application_id'=>$id));

							$this->Applications->updateAll(array('application_status'=>$this->ApplicationStages->PROJECT_COMMISSIONING,'modified'=>$this->NOW(),'modified_by'=>$member_id),array('id'=>$id));
							$this->ApplicationStages->saveStatus($id,$this->ApplicationStages->PROJECT_COMMISSIONING,$member_id,'');
						}
						
						$ErrorMessage 	= "Data Save Succesfully.";
						$success 		= 1;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					//echo"<pre>"; print_r($success); die();
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate form details.";
				$success 		= 0;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	/**
	 *
	 * fetch_restatus_api_all
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to fetch status from thirdparty API and update or add record in cei application details.
	 *
	 */
	public function fetch_restatus_api_all()
	{
		//echo"<pre>"; print_r($this->request->data); die();
		$this->autoRender	= false;
		$appid 				= intval(decode($this->request->data['app_id']));
		$drawing_number 	= isset($this->request->data['drawing_number']) ? $this->request->data['drawing_number'] : '';
		$cei_number 		= isset($this->request->data['cei_number']) ? $this->request->data['cei_number'] : '';
		$api_type 			= $this->request->data['api_type'];
		$pass_param 		= $drawing_number;
		//echo"<pre>"; print_r($pass_param); die();
		if($pass_param == '')
		{
			$pass_param 	= $cei_number;
		}
		$response 			= $this->ReThirdpartyApiLog->third_party_call($appid,$pass_param,$api_type);
		
		//echo"<pre>"; print_r($response); die();
		$exist_cei 			= $this->CeiReApplicationDetails->find('all',array('conditions'=>array('application_id'=>$appid)))->first();
		// if(empty($exist_cei))
		// {
		// 	$ceiappEntity						= $this->CeiReApplicationDetails->newEntity($this->request->data);
		// 	$ceiappEntity->application_id 		= $appid;
		// 	$ceiappEntity->created 				= $this->NOW();
		// 	$ceiappEntity->updated 				= $this->NOW();
		// }
		// else
		// {
		// 	$getceidata 						= $this->CeiReApplicationDetails->get($exist_cei->id);
		// 	$ceiappEntity						= $this->CeiReApplicationDetails->patchEntity($getceidata,$this->request->data);
		// 	$ceiappEntity->updated 				= $this->NOW();
		// }
		// if($drawing_number!='')
		// {
		// 	$ceiappEntity->drawing_app_no 		= $drawing_number;
		// 	$ceiappEntity->drawing_app_status	= $response;
		// 	$ceiappEntity->status 				= '1';
		// 	$status_update 						= $this->ApplicationStages->DRAWING_APPLIED;
		// }
		// if($cei_number!='')
		// {
		// 	$ceiappEntity->cei_app_no 			= $cei_number;
		// 	$ceiappEntity->cei_app_status		= $response;
		// 	$ceiappEntity->status 				= '2';
		// 	$status_update 						= $this->ApplicationStages->CEI_APP_NUMBER_APPLIED;
		// }
		// if($this->CeiReApplicationDetails->save($ceiappEntity))
		// {
		// 	//$this->SetApplicationStatus($status_update,$appid,'');
		// }
		echo json_encode(array('type'=>'ok','response'=>$response));
		exit;
	}
	/**
	 * cei_drawing_all
	 * Behaviour : Public
	 * @defination : Method is use to complete the cei_drawing_all
	 */
	public function cei_drawing_all()
	{	
		//echo"<pre>"; print_r($this->request->data); die();
		$this->autoRender 	= false;
		$id 			= (isset($this->request->data['appid'])?decode($this->request->data['appid']):0);
		$app_geo_loc_id 	= (isset($this->request->data['app_geo_loc_id'])?$this->request->data['app_geo_loc_id']:0);
		$drawing_app_no 		= (isset($this->request->data['drawing_app_no'])?($this->request->data['drawing_app_no']):"");
		$drawing_app_status 	= (isset($this->request->data['drawing_app_status'])?($this->request->data['drawing_app_status']):"");


		$reason 				= '';
		$exist_cei 			= $this->CeiReApplicationDetails->find('all',array('conditions'=>array('application_id'=>$id,'app_geo_loc_id'=>$app_geo_loc_id)))->first();
		//echo"<pre>"; print_r($exist_cei); die();
		//$ceiappEntity       = $this->request->data;
		if(empty($exist_cei))
		{
			$ceiappEntity					= $this->CeiReApplicationDetails->newEntity($this->request->data);
			$ceiappEntity->application_id 	= $id;
			$ceiappEntity->app_geo_loc_id 	= $app_geo_loc_id;
			$ceiappEntity->created 			= $this->NOW();
			$ceiappEntity->updated 			= $this->NOW();
			$ceiappEntity->status 			= '1';
			$status 						= $this->ApplicationStages->DRAWING_APPLIED;
		}
		else
		{
			$getceidata 					= $this->CeiReApplicationDetails->get($exist_cei->id);
			$ceiappEntity					= $this->CeiReApplicationDetails->patchEntity($getceidata,$this->request->data);
			$ceiappEntity->updated 			= $this->NOW();
		}
		

		if($this->CeiReApplicationDetails->save($ceiappEntity))
		{
			$this->SetApplicationStatus($status,$id,$reason);
			if(strtolower($drawing_app_status) == 'completed')
			{
				$status 				= $this->ApplicationStages->APPROVED_FROM_CEI;
				$this->SetApplicationStatus($status,$id,$reason);
			}
		}
			//$status = ($status == 1)?$this->ApplyOnlineApprovals->APPROVED_FROM_CEI:$this->ApplyOnlineApprovals->REJECTED_FROM_CEI;
		$this->ApiToken->SetAPIResponse('msg', 'Applicaton status updated.');
		$this->ApiToken->SetAPIResponse('type','ok');
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	/**
	 * cei_drawing_all
	 * Behaviour : Public
	 * @defination : Method is use to complete the cei_drawing_all
	 */
	public function cei_inspection_all()
	{	
		//echo"<pre>"; print_r($this->request->data); die();
		$this->autoRender 	= false;
		$id 	= (isset($this->request->data['appid'])?decode($this->request->data['appid']):0);
		$app_geo_loc_id 	= (isset($this->request->data['app_geo_loc_id'])?$this->request->data['app_geo_loc_id']:0);
		$cei_app_no 			= (isset($this->request->data['cei_app_no'])?($this->request->data['cei_app_no']):"");
		$cei_app_status 		= (isset($this->request->data['cei_app_status'])?($this->request->data['cei_app_status']):"");

		$reason 				= '';
		$exist_cei 				= $this->CeiReApplicationDetails->find('all',array('conditions'=>array('application_id'=>$id,'app_geo_loc_id'=>$app_geo_loc_id)))->first();
		$ceiappEntity       	= $this->request->data;
		if(empty($exist_cei))
		{
			$ceiappEntity					= $this->CeiReApplicationDetails->newEntity($this->request->data);
			$ceiappEntity->application_id 	= $id;
			$ceiappEntity->created 			= $this->NOW();
			$ceiappEntity->updated 			= $this->NOW();
		}
		else
		{
			$getceidata 					= $this->CeiReApplicationDetails->get($exist_cei->id);
			$ceiappEntity					= $this->CeiReApplicationDetails->patchEntity($getceidata,$this->request->data);
			$ceiappEntity->updated 			= $this->NOW();
		}
		$ceiappEntity->status 				= '2';
		$status 							= $this->ApplicationStages->CEI_APP_NUMBER_APPLIED;
		if($this->CeiReApplicationDetails->save($ceiappEntity))
		{
			$this->SetApplicationStatus($status,$id,$reason);
			if(strtolower($cei_app_status) == 'completed')
			{
				$status 				= $this->ApplicationStages->CEI_INSPECTION_APPROVED;
				$this->SetApplicationStatus($status,$id,$reason);
			}
		}
			//$status = ($status == 1)?$this->ApplyOnlineApprovals->APPROVED_FROM_CEI:$this->ApplyOnlineApprovals->REJECTED_FROM_CEI;
		$this->ApiToken->SetAPIResponse('msg', 'Applicaton status updated.');
		$this->ApiToken->SetAPIResponse('type','ok');
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	
	public function bpta($id = null) 
	{
		$customer_id 		= $this->Session->read("Customers.id");
		$member_id 			= $this->Session->read("Members.id");
		$application_type   = (isset($this->request->data['application_type'])?$this->request->data['application_type']:0);
		$application_id     = (isset($this->request->data['application_id'])?$this->request->data['application_id']:0);
		$is_member          = false;
		if(!empty($member_id)){
			$is_member      = true;
		}
		if(empty($id)) {
			$this->Flash->error('Please Select Valid Installer.');
			return $this->redirect('home');
		} else {

			$encode_id 						= $id;
			$id 							= intval(decode($id));
			$applyOnlinesData 				= $this->Applications->viewApplication($id);

			$wtg_applications 				= $this->ApplicationGeoLocation->find("all",['conditions'=>array('application_id'=>$id,'approved'=>1)])->toArray();
			
			$applicationCategory 			= $this->ApplicationCategory->find('all',array('conditions'=>array('id'=>$applyOnlinesData->application_type)))->first();
		}

		$this->set('logged_in_id',$this->Session->read('Customers.id'));
		$this->set('is_member',$is_member);
		$this->set('encode_id',$encode_id);
		$this->set('applyOnlinesData',$applyOnlinesData);
		$this->set('applicationCategory',$applyOnlinesData);
		$this->set('BptaApplicationDetails',$this->BptaApplicationDetails);
		$this->set('wtg_applications',$wtg_applications);
		$this->set("pageTitle","BPTA");
	}
	public function bpta_approval($id = null) 
	{
		$customer_id 		= $this->Session->read("Customers.id");
		$member_id 			= $this->Session->read("Members.id");
		$application_type   = (isset($this->request->data['application_type'])?$this->request->data['application_type']:0);
		$application_id     = (isset($this->request->data['application_id'])?$this->request->data['application_id']:0);
		$is_member          = false;
		if(!empty($member_id)){
			$is_member      = true;
		}
		if(empty($id)) {
			$this->Flash->error('Please Select Valid Installer.');
			return $this->redirect('home');
		} else {

			$encode_id 						= $id;
			$id 							= intval(decode($id));
			$applyOnlinesData 				= $this->Applications->viewApplication($id);

			$wtg_applications 				= $this->ApplicationGeoLocation->find("all",['conditions'=>array('application_id'=>$id,'approved'=>1)])->toArray();
			
			$applicationCategory 			= $this->ApplicationCategory->find('all',array('conditions'=>array('id'=>$applyOnlinesData->application_type)))->first();
		}

		$this->set('logged_in_id',$this->Session->read('Customers.id'));
		$this->set('is_member',$is_member);
		$this->set('encode_id',$encode_id);
		$this->set('applyOnlinesData',$applyOnlinesData);
		$this->set('applicationCategory',$applyOnlinesData);
		$this->set('BptaApplicationDetails',$this->BptaApplicationDetails);
		$this->set('wtg_applications',$wtg_applications);
		$this->set("pageTitle","CEI Inspection");
	}
	/**
	 * BPTADocumentAll
	 * Behaviour : Public
	 * @defination : Method is use to complete the BPTADocumentAll
	 */
	public function BPTADocumentAll()
	{	
		//echo"<pre>"; print_r($this->request->data); die();
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['BPTA_application_id'])?$this->request->data['BPTA_application_id']:0);
		$geo_id 			= (isset($this->request->data['BPTA_app_geo_loc_id'])?$this->request->data['BPTA_app_geo_loc_id']:0);
		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} else {
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->Applications->viewApplication($id);
			$customer_id 			= $this->Session->read("Customers.id");
			$member_id 			= $this->Session->read("Members.id");
			if (!empty($applyOnlinesData) && (!empty($customer_id) || !empty($member_id))) {
				if ($this->request->is('post')) {
					if((empty($this->request->data['bpta_document1']['name'])) || (empty($this->request->data['bpta_document2']['name'])) ) {
						$ErrorMessage 	= "Please upload at least one file .";
						$success 		= 0;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					} else {
						
						$BptaApplicationDetailsEntity = $this->BptaApplicationDetails->newEntity();

						$BptaApplicationDetailsEntity->application_id		= $id;
						$BptaApplicationDetailsEntity->app_geo_loc_id		= $geo_id;
						$BptaApplicationDetailsEntity->application_type		= $applyOnlinesData->application_type;
						$BptaApplicationDetailsEntity->created_date 		= $this->NOW();
						
						$insert_id= $this->BptaApplicationDetails->save($BptaApplicationDetailsEntity);

						$insertId = $insert_id->id;
						
						if(!empty($this->request->data['bpta_document1']['name']))
						{
							$prefix_file 	= '';
							$name 			= $this->request->data['bpta_document1']['name'];
							$ext 			= substr(strtolower(strrchr($name, '.')), 1);
							$file_name 		= $prefix_file.date('Ymdhms').rand();
							$uploadPath 	= APPLICATIONS_PATH.$id.'/';
							if(!file_exists(APPLICATIONS_PATH.$id)) {
								@mkdir(APPLICATIONS_PATH.$id, 0777);
							}
							$file_location 	= WWW_ROOT.$uploadPath.'BPTA'.'_'.$file_name.'.'.$ext;
							if(move_uploaded_file($this->request->data['bpta_document1']['tmp_name'],$file_location))
							{

								$couchdbId 		= $this->ReCouchdb->saveData($uploadPath,$file_location,$prefix_file, 'BPTA_'.$file_name.'.'.$ext,$this->Session->read('Customers.id'),'BPTA');

								$this->BptaApplicationDetails->updateAll(array('bpta_document1'=>'BPTA_'.$file_name.'.'.$ext),array('id'=>$insertId));
								
							}
						} 
						if(!empty($this->request->data['bpta_document2']['name']))
						{
							$prefix_file 	= '';
							$name 			= $this->request->data['bpta_document2']['name'];
							$ext 			= substr(strtolower(strrchr($name, '.')), 1);
							$file_name 		= $prefix_file.date('Ymdhms').rand();
							$uploadPath 	= APPLICATIONS_PATH.$id.'/';
							if(!file_exists(APPLICATIONS_PATH.$id)) {
								@mkdir(APPLICATIONS_PATH.$id, 0777);
							}
							$file_location 	= WWW_ROOT.$uploadPath.'BPTA'.'_'.$file_name.'.'.$ext;
							if(move_uploaded_file($this->request->data['bpta_document2']['tmp_name'],$file_location))
							{

								$couchdbId 		= $this->ReCouchdb->saveData($uploadPath,$file_location,$prefix_file, 'BPTA_'.$file_name.'.'.$ext,$this->Session->read('Customers.id'),'BPTA');

								$this->BptaApplicationDetails->updateAll(array('bpta_document2'=>'BPTA_'.$file_name.'.'.$ext),array('id'=>$insertId));
							}
						} 
						$sldcentry 	= $this->BptaApplicationDetails->find('all',array('conditions'=>array('id'=>$insertId)))->first();
						if(!empty($sldcentry)) {
							//$this->SendApplicationLetterToCustomer($id);
							
							$this->ApplicationStages->saveStatus($id,$this->ApplicationStages->BPTA,$customer_id,'');
							$ErrorMessage 	= "Upload BPTA Documents Succesfully.";
							$success 		= 1;
							$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
							$this->ApiToken->SetAPIResponse('success',$success);
						}
						 else {
							$ErrorMessage 	= "Error while uploading documents.";
							$success 		= 0;
							$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
							$this->ApiToken->SetAPIResponse('success',$success);
						}
					}	
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate form details.";
				$success 		= 0;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	/**
	 * BPTAApprovalDocumentAll
	 * Behaviour : Public
	 * @defination : Method is use to complete the BPTAApprovalDocumentAll
	 */
	public function BPTAApprovalDocumentAll()
	{	
		
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['BPTAApproval_application_id'])?$this->request->data['BPTAApproval_application_id']:0);
		$geo_id 			= (isset($this->request->data['BPTAApproval_app_geo_loc_id'])?$this->request->data['BPTAApproval_app_geo_loc_id']:0);
		//echo"<pre>"; print_r($this->request->data); die();
		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} 
		else{
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->Applications->viewApplication($id);
			$customer_id 			= $this->Session->read("Customers.id");
			$member_id 				= $this->Session->read("Members.id");

			if (!empty($applyOnlinesData) && (!empty($customer_id) || !empty($member_id))) {
				
				if((empty($this->request->data['bpta_reason'])) || (empty($this->request->data['bpta_approval_date'])) ) {
					$ErrorMessage 	= "Please select required the fields.";
					$success 		= 0;
					$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
					$this->ApiToken->SetAPIResponse('success',$success);

				} else {
					
						if(!empty($member_id)){
							$bpta_approval_date = str_replace('/', '-', $this->request->data['bpta_approval_date']); 
							$bpta_approval_date = date('Y-m-d',strtotime($bpta_approval_date));
							
							$this->BptaApplicationDetails->updateAll(array('bpta_approve'=>$this->request->data['bpta_approve'],'bpta_reason'=>$this->request->data['bpta_reason'],'bpta_approved_by'=>$member_id,'bpta_approval_date'=>$bpta_approval_date,'modified_by'=>$member_id,'modified_date'=>$this->NOW()),array('application_id'=>$id,'app_geo_loc_id'=>$geo_id));

							$this->Applications->updateAll(array('application_status'=>$this->ApplicationStages->BPTA_APPROVED,'modified'=>$this->NOW(),'modified_by'=>$member_id),array('id'=>$id));
							$this->ApplicationStages->saveStatus($id,$this->ApplicationStages->BPTA_APPROVED,$member_id,'');
						}
						
						$ErrorMessage 	= "Data Save Succesfully.";
						$success 		= 1;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate form details.";
				$success 		= 0;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	public function wheeling($id = null) 
	{
		$customer_id 		= $this->Session->read("Customers.id");
		$member_id 			= $this->Session->read("Members.id");
		$application_type   = (isset($this->request->data['application_type'])?$this->request->data['application_type']:0);
		$application_id     = (isset($this->request->data['application_id'])?$this->request->data['application_id']:0);
		$is_member          = false;
		if(!empty($member_id)){
			$is_member      = true;
		}
		if(empty($id)) {
			$this->Flash->error('Please Select Valid Installer.');
			return $this->redirect('home');
		} else {

			$encode_id 						= $id;
			$id 							= intval(decode($id));
			$applyOnlinesData 				= $this->Applications->viewApplication($id);

			$wtg_applications 				= $this->ApplicationGeoLocation->find("all",['conditions'=>array('application_id'=>$id,'approved'=>1)])->toArray();
			
			$applicationCategory 			= $this->ApplicationCategory->find('all',array('conditions'=>array('id'=>$applyOnlinesData->application_type)))->first();
		}

		$this->set('logged_in_id',$this->Session->read('Customers.id'));
		$this->set('is_member',$is_member);
		$this->set('encode_id',$encode_id);
		$this->set('applyOnlinesData',$applyOnlinesData);
		$this->set('applicationCategory',$applyOnlinesData);
		$this->set('WheelingApplicationDetails',$this->WheelingApplicationDetails);
		$this->set('wtg_applications',$wtg_applications);
		$this->set("pageTitle","Wheeling");
	}
	public function wheeling_approval($id = null) 
	{
		$customer_id 		= $this->Session->read("Customers.id");
		$member_id 			= $this->Session->read("Members.id");
		$application_type   = (isset($this->request->data['application_type'])?$this->request->data['application_type']:0);
		$application_id     = (isset($this->request->data['application_id'])?$this->request->data['application_id']:0);
		$is_member          = false;
		if(!empty($member_id)){
			$is_member      = true;
		}
		if(empty($id)) {
			$this->Flash->error('Please Select Valid Installer.');
			return $this->redirect('home');
		} else {

			$encode_id 						= $id;
			$id 							= intval(decode($id));
			$applyOnlinesData 				= $this->Applications->viewApplication($id);

			$wtg_applications 				= $this->ApplicationGeoLocation->find("all",['conditions'=>array('application_id'=>$id,'approved'=>1)])->toArray();
			
			$applicationCategory 			= $this->ApplicationCategory->find('all',array('conditions'=>array('id'=>$applyOnlinesData->application_type)))->first();
		}

		$this->set('logged_in_id',$this->Session->read('Customers.id'));
		$this->set('is_member',$is_member);
		$this->set('encode_id',$encode_id);
		$this->set('applyOnlinesData',$applyOnlinesData);
		$this->set('applicationCategory',$applyOnlinesData);
		$this->set('WheelingApplicationDetails',$this->WheelingApplicationDetails);
		$this->set('wtg_applications',$wtg_applications);
		$this->set("pageTitle","Wheeling Approval");
	}
	

	/**
	 * WHEELINGDocument
	 * Behaviour : Public
	 * @defination : Method is use to complete the WHEELINGDocument
	 */
	public function WHEELINGDocumentAll()
	{	
		//echo"<pre>"; print_r($this->request->data); die();
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['WHEELING_application_id'])?$this->request->data['WHEELING_application_id']:0);
		$geo_id 			= (isset($this->request->data['WHEELING_app_geo_loc_id'])?$this->request->data['WHEELING_app_geo_loc_id']:0);
		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} else {
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->Applications->viewApplication($id);
			$customer_id 			= $this->Session->read("Customers.id");
			$member_id 			= $this->Session->read("Members.id");
			if (!empty($applyOnlinesData) && (!empty($customer_id) || !empty($member_id))) {
				if ($this->request->is('post')) {
					if((empty($this->request->data['Wheeling_Agreement_document']['name'])) ) {
						$ErrorMessage 	= "Please upload at least one file .";
						$success 		= 0;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					} else {
						
						$WheelingApplicationDetailsEntity = $this->WheelingApplicationDetails->newEntity();

						$WheelingApplicationDetailsEntity->application_id		= $id;
						$WheelingApplicationDetailsEntity->app_geo_loc_id		= $geo_id;
						$WheelingApplicationDetailsEntity->application_type		= $applyOnlinesData->application_type;
						$WheelingApplicationDetailsEntity->created_date 		= $this->NOW();
						
						$insert_id= $this->WheelingApplicationDetails->save($WheelingApplicationDetailsEntity);

						$insertId = $insert_id->id;
						
						if(!empty($this->request->data['Wheeling_Agreement_document']['name']))
						{
							$prefix_file 	= '';
							$name 			= $this->request->data['Wheeling_Agreement_document']['name'];
							$ext 			= substr(strtolower(strrchr($name, '.')), 1);
							$file_name 		= $prefix_file.date('Ymdhms').rand();
							$uploadPath 	= APPLICATIONS_PATH.$id.'/';
							if(!file_exists(APPLICATIONS_PATH.$id)) {
								@mkdir(APPLICATIONS_PATH.$id, 0777);
							}
							$file_location 	= WWW_ROOT.$uploadPath.'Wheeling'.'_'.$file_name.'.'.$ext;
							if(move_uploaded_file($this->request->data['Wheeling_Agreement_document']['tmp_name'],$file_location))
							{

								$couchdbId 		= $this->ReCouchdb->saveData($uploadPath,$file_location,$prefix_file, 'Wheeling_'.$file_name.'.'.$ext,$this->Session->read('Customers.id'),'Wheeling');

								$this->WheelingApplicationDetails->updateAll(array('Wheeling_Agreement_document'=>'Wheeling_'.$file_name.'.'.$ext),array('id'=>$insertId));
								//$this->Applications->updateAll(array('application_status'=>$this->ApplicationStages->Wheeling,'modified'=>$this->NOW(),'modified_by'=>$customer_id),array('id'=>$id));
								
							}
						} 
						$sldcentry 	= $this->WheelingApplicationDetails->find('all',array('conditions'=>array('id'=>$insertId)))->first();
						if(!empty($sldcentry)) {
							//$this->SendApplicationLetterToCustomer($id);
							
							$this->ApplicationStages->saveStatus($id,$this->ApplicationStages->WHELLING,$customer_id,'');
							$ErrorMessage 	= "Upload Documents Succesfully.";
							$success 		= 1;
							$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
							$this->ApiToken->SetAPIResponse('success',$success);
						}
						 else {
							$ErrorMessage 	= "Error while uploading documents.";
							$success 		= 0;
							$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
							$this->ApiToken->SetAPIResponse('success',$success);
						}
					}	
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate form details.";
				$success 		= 0;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	 * WHEELINGApprovalDocument
	 * Behaviour : Public
	 * @defination : Method is use to complete the WHEELINGApprovalDocument
	 */
	public function WHEELINGApprovalDocumentAll()
	{	
		//echo"<pre>"; print_r($this->request->data); die();
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['WHEELINGApproval_application_id'])?$this->request->data['WHEELINGApproval_application_id']:0);
		$geo_id 			= (isset($this->request->data['WHEELINGApproval_app_geo_loc_id'])?$this->request->data['WHEELINGApproval_app_geo_loc_id']:0);
		//echo"<pre>"; print_r($this->request->data); die();
		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} 
		else{
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->Applications->viewApplication($id);
			$customer_id 			= $this->Session->read("Customers.id");
			$member_id 				= $this->Session->read("Members.id");

			if (!empty($applyOnlinesData) && (!empty($customer_id) || !empty($member_id))) {
				
				if((empty($this->request->data['wheeling_reason'])) || (empty($this->request->data['wheeling_approval_date'])) ) {
					$ErrorMessage 	= "Please select required the fields.";
					$success 		= 0;
					$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
					$this->ApiToken->SetAPIResponse('success',$success);

				} else {
					
						if(!empty($member_id)){
							
							
							//$wheeling_approval_date = str_replace('/', '-', $this->request->data['wheeling_approval_date']); 

							$date_parts = explode('/', $this->request->data['wheeling_approval_date']);

							if (count($date_parts) === 3) {
							    // Rearrange the date parts to YYYY-MM-DD
							    $wheeling_approval_date = $date_parts[2] . '-' . $date_parts[0] . '-' . $date_parts[1];
							} else {
							    // Handle error if date format is incorrect
							    $wheeling_approval_date = 'Invalid date format';
							}

							//echo"<pre>"; print_r($wheeling_approval_date); die();
							//$wheeling_approval_date = date('Y-m-d',strtotime($wheeling_approval_date));
							
							$this->WheelingApplicationDetails->updateAll(array('wheeling_approve'=>$this->request->data['wheeling_approve'],'wheeling_reason'=>$this->request->data['wheeling_reason'],'wheeling_approved_by'=>$member_id,'wheeling_approval_date'=>$wheeling_approval_date,'modified_by'=>$member_id,'modified_date'=>$this->NOW()),array('application_id'=>$id,'app_geo_loc_id'=>$geo_id));

							$this->Applications->updateAll(array('application_status'=>$this->ApplicationStages->WHELLING_APPROVED,'modified'=>$this->NOW(),'modified_by'=>$member_id),array('id'=>$id));
							$this->ApplicationStages->saveStatus($id,$this->ApplicationStages->WHELLING_APPROVED,$member_id,'');
						}
						
						$ErrorMessage 	= "Data Save Succesfully.";
						$success 		= 1;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					//echo"<pre>"; print_r($success); die();
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate form details.";
				$success 		= 0;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	public function meter($id = null) 
	{
		$customer_id 		= $this->Session->read("Customers.id");
		$member_id 			= $this->Session->read("Members.id");
		$application_type   = (isset($this->request->data['application_type'])?$this->request->data['application_type']:0);
		$application_id     = (isset($this->request->data['application_id'])?$this->request->data['application_id']:0);
		$is_member          = false;
		if(!empty($member_id)){
			$is_member      = true;
		}
		if(empty($id)) {
			$this->Flash->error('Please Select Valid Installer.');
			return $this->redirect('home');
		} else {

			$encode_id 						= $id;
			$id 							= intval(decode($id));
			$applyOnlinesData 				= $this->Applications->viewApplication($id);

			$wtg_applications 				= $this->ApplicationGeoLocation->find("all",['conditions'=>array('application_id'=>$id,'approved'=>1)])->toArray();
			
			$applicationCategory 			= $this->ApplicationCategory->find('all',array('conditions'=>array('id'=>$applyOnlinesData->application_type)))->first();
		}

		$this->set('logged_in_id',$this->Session->read('Customers.id'));
		$this->set('is_member',$is_member);
		$this->set('encode_id',$encode_id);
		$this->set('applyOnlinesData',$applyOnlinesData);
		$this->set('applicationCategory',$applyOnlinesData);
		$this->set('MeterSealingApplicationDetails',$this->MeterSealingApplicationDetails);
		$this->set('wtg_applications',$wtg_applications);
		$this->set("pageTitle","Meter Sealing");
	}
	public function meter_approval($id = null) 
	{
		$customer_id 		= $this->Session->read("Customers.id");
		$member_id 			= $this->Session->read("Members.id");
		$application_type   = (isset($this->request->data['application_type'])?$this->request->data['application_type']:0);
		$application_id     = (isset($this->request->data['application_id'])?$this->request->data['application_id']:0);
		$is_member          = false;
		if(!empty($member_id)){
			$is_member      = true;
		}
		if(empty($id)) {
			$this->Flash->error('Please Select Valid Installer.');
			return $this->redirect('home');
		} else {

			$encode_id 						= $id;
			$id 							= intval(decode($id));
			$applyOnlinesData 				= $this->Applications->viewApplication($id);

			$wtg_applications 				= $this->ApplicationGeoLocation->find("all",['conditions'=>array('application_id'=>$id,'approved'=>1)])->toArray();
			
			$applicationCategory 			= $this->ApplicationCategory->find('all',array('conditions'=>array('id'=>$applyOnlinesData->application_type)))->first();
		}

		$this->set('logged_in_id',$this->Session->read('Customers.id'));
		$this->set('is_member',$is_member);
		$this->set('encode_id',$encode_id);
		$this->set('applyOnlinesData',$applyOnlinesData);
		$this->set('applicationCategory',$applyOnlinesData);
		$this->set('MeterSealingApplicationDetails',$this->MeterSealingApplicationDetails);
		$this->set('wtg_applications',$wtg_applications);
		$this->set("pageTitle","Meter Sealing Approval");
	}

	/**
	 * METER_SEALINGDocument
	 * Behaviour : Public
	 * @defination : Method is use to complete the METER_SEALINGDocument
	 */
	public function METER_SEALINGDocumentAll()
	{	
		//echo"<pre>"; print_r($this->request->data); die();
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['METER_SEALING_application_id'])?$this->request->data['METER_SEALING_application_id']:0);
		$geo_id 			= (isset($this->request->data['METER_SEALING_app_geo_loc_id'])?$this->request->data['METER_SEALING_app_geo_loc_id']:0);
		
		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} else {
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->Applications->viewApplication($id);
			$customer_id 			= $this->Session->read("Customers.id");
			$member_id 			= $this->Session->read("Members.id");
			if (!empty($applyOnlinesData) && (!empty($customer_id) || !empty($member_id))) {
				if ($this->request->is('post')) {
					if((empty($this->request->data['meter_sealing_report']['name'])) ) {
						$ErrorMessage 	= "Please upload at least one file .";
						$success 		= 0;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					} else {
						
						$MeterSealingApplicationDetailsEntity = $this->MeterSealingApplicationDetails->newEntity();

						$MeterSealingApplicationDetailsEntity->application_id		= $id;
						$MeterSealingApplicationDetailsEntity->application_type		= $applyOnlinesData->application_type;
						$MeterSealingApplicationDetailsEntity->app_geo_loc_id		= $geo_id;
						$MeterSealingApplicationDetailsEntity->created_date 		= $this->NOW();
						
						$insert_id= $this->MeterSealingApplicationDetails->save($MeterSealingApplicationDetailsEntity);

						$insertId = $insert_id->id;
						
						if(!empty($this->request->data['meter_sealing_report']['name']))
						{
							$prefix_file 	= '';
							$name 			= $this->request->data['meter_sealing_report']['name'];
							$ext 			= substr(strtolower(strrchr($name, '.')), 1);
							$file_name 		= $prefix_file.date('Ymdhms').rand();
							$uploadPath 	= APPLICATIONS_PATH.$id.'/';
							if(!file_exists(APPLICATIONS_PATH.$id)) {
								@mkdir(APPLICATIONS_PATH.$id, 0777);
							}
							$file_location 	= WWW_ROOT.$uploadPath.'meter_sealing'.'_'.$file_name.'.'.$ext;
							if(move_uploaded_file($this->request->data['meter_sealing_report']['tmp_name'],$file_location))
							{

								$couchdbId 		= $this->ReCouchdb->saveData($uploadPath,$file_location,$prefix_file, 'meter_sealing_'.$file_name.'.'.$ext,$this->Session->read('Customers.id'),'meter_sealing');

								$this->MeterSealingApplicationDetails->updateAll(array('meter_sealing_report'=>'meter_sealing_'.$file_name.'.'.$ext),array('id'=>$insertId));
								
							}
						} 
						$MeterSealingentry 	= $this->MeterSealingApplicationDetails->find('all',array('conditions'=>array('id'=>$insertId)))->first();
						if(!empty($MeterSealingentry)) {
							
							$this->ApplicationStages->saveStatus($id,$this->ApplicationStages->METER_SEALING,$customer_id,'');
							$ErrorMessage 	= "Upload Documents Succesfully.";
							$success 		= 1;
							$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
							$this->ApiToken->SetAPIResponse('success',$success);
						}
						 else {
							$ErrorMessage 	= "Error while uploading documents.";
							$success 		= 0;
							$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
							$this->ApiToken->SetAPIResponse('success',$success);
						}
					}	
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate form details.";
				$success 		= 0;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	 * METER_SEALINGApprovalDocument
	 * Behaviour : Public
	 * @defination : Method is use to complete the METER_SEALINGApprovalDocument
	 */
	public function METER_SEALINGApprovalDocumentAll()
	{	
		//echo"<pre>"; print_r($this->request->data); die();
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['METER_SEALINGApproval_application_id'])?$this->request->data['METER_SEALINGApproval_application_id']:0);
		$geo_id 				= (isset($this->request->data['METER_SEALINGApproval_app_geo_loc_id'])?$this->request->data['METER_SEALINGApproval_app_geo_loc_id']:0);
		
		
		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} 
		else{
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->Applications->viewApplication($id);
			$customer_id 			= $this->Session->read("Customers.id");
			$member_id 				= $this->Session->read("Members.id");

			if (!empty($applyOnlinesData) && (!empty($customer_id) || !empty($member_id))) {
				
				if((empty($this->request->data['meter_reason'])) || (empty($this->request->data['meter_approval_date'])) ) {
					$ErrorMessage 	= "Please select required the fields.";
					$success 		= 0;
					$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
					$this->ApiToken->SetAPIResponse('success',$success);

				} else {
					
						if(!empty($member_id)){
							
							$date_parts = explode('/', $this->request->data['meter_approval_date']);

							if (count($date_parts) === 3) {
							    // Rearrange the date parts to YYYY-MM-DD
							    $meter_approval_date = $date_parts[2] . '-' . $date_parts[0] . '-' . $date_parts[1];
							} else {
							    // Handle error if date format is incorrect
							    $meter_approval_date = 'Invalid date format';
							}

							$this->MeterSealingApplicationDetails->updateAll(array('meter_approve'=>$this->request->data['meter_approve'],'meter_reason'=>$this->request->data['meter_reason'],'meter_approved_by'=>$member_id,'meter_approval_date'=>$meter_approval_date,'modified_by'=>$member_id,'modified_date'=>$this->NOW(),),array('application_id'=>$id,'app_geo_loc_id'=>$geo_id));

							$this->Applications->updateAll(array('application_status'=>$this->ApplicationStages->METER_SEALING_APPROVED,'modified'=>$this->NOW(),'modified_by'=>$member_id),array('id'=>$id));
							$this->ApplicationStages->saveStatus($id,$this->ApplicationStages->METER_SEALING_APPROVED,$member_id,'');
						}
						
						$ErrorMessage 	= "Data Save Succesfully.";
						$success 		= 1;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					//echo"<pre>"; print_r($success); die();
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate form details.";
				$success 		= 0;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	public function power_injection($id = null) 
	{
		$customer_id 		= $this->Session->read("Customers.id");
		$member_id 			= $this->Session->read("Members.id");
		$application_type   = (isset($this->request->data['application_type'])?$this->request->data['application_type']:0);
		$application_id     = (isset($this->request->data['application_id'])?$this->request->data['application_id']:0);
		$is_member          = false;
		if(!empty($member_id)){
			$is_member      = true;
		}
		if(empty($id)) {
			$this->Flash->error('Please Select Valid Installer.');
			return $this->redirect('home');
		} else {

			$encode_id 						= $id;
			$id 							= intval(decode($id));
			$applyOnlinesData 				= $this->Applications->viewApplication($id);

			$wtg_applications 				= $this->ApplicationGeoLocation->find("all",['conditions'=>array('application_id'=>$id,'approved'=>1)])->toArray();
			
			$applicationCategory 			= $this->ApplicationCategory->find('all',array('conditions'=>array('id'=>$applyOnlinesData->application_type)))->first();
		}

		$this->set('logged_in_id',$this->Session->read('Customers.id'));
		$this->set('is_member',$is_member);
		$this->set('encode_id',$encode_id);
		$this->set('applyOnlinesData',$applyOnlinesData);
		$this->set('applicationCategory',$applyOnlinesData);
		$this->set('PowerInjectionApplicationDetails',$this->PowerInjectionApplicationDetails);
		$this->set('wtg_applications',$wtg_applications);
		$this->set("pageTitle","Power Injection");
	}
	public function power_injection_approval($id = null) 
	{
		$customer_id 		= $this->Session->read("Customers.id");
		$member_id 			= $this->Session->read("Members.id");
		$application_type   = (isset($this->request->data['application_type'])?$this->request->data['application_type']:0);
		$application_id     = (isset($this->request->data['application_id'])?$this->request->data['application_id']:0);
		$is_member          = false;
		if(!empty($member_id)){
			$is_member      = true;
		}
		if(empty($id)) {
			$this->Flash->error('Please Select Valid Installer.');
			return $this->redirect('home');
		} else {

			$encode_id 						= $id;
			$id 							= intval(decode($id));
			$applyOnlinesData 				= $this->Applications->viewApplication($id);

			$wtg_applications 				= $this->ApplicationGeoLocation->find("all",['conditions'=>array('application_id'=>$id,'approved'=>1)])->toArray();
			
			$applicationCategory 			= $this->ApplicationCategory->find('all',array('conditions'=>array('id'=>$applyOnlinesData->application_type)))->first();
		}

		$this->set('logged_in_id',$this->Session->read('Customers.id'));
		$this->set('is_member',$is_member);
		$this->set('encode_id',$encode_id);
		$this->set('applyOnlinesData',$applyOnlinesData);
		$this->set('applicationCategory',$applyOnlinesData);
		$this->set('PowerInjectionApplicationDetails',$this->PowerInjectionApplicationDetails);
		$this->set('wtg_applications',$wtg_applications);
		$this->set("pageTitle","Power Injection Approval");
	}
		/**
	 * POWER_INJECTIONDocumentAll
	 * Behaviour : Public
	 * @defination : Method is use to complete the POWER_INJECTIONDocumentAll
	 */
	public function POWER_INJECTIONDocumentAll()
	{	
		//echo"<pre>"; print_r($this->request->data); die();
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['POWER_INJECTION_application_id'])?$this->request->data['POWER_INJECTION_application_id']:0);
		$geo_id 				= (isset($this->request->data['POWER_INJECTION_app_geo_loc_id'])?$this->request->data['POWER_INJECTION_app_geo_loc_id']:0);
		
		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} else {
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->Applications->viewApplication($id);
			$customer_id 			= $this->Session->read("Customers.id");
			$member_id 			= $this->Session->read("Members.id");
			if (!empty($applyOnlinesData) && (!empty($customer_id) || !empty($member_id))) {
				if ($this->request->is('post')) {
					if((empty($this->request->data['power_injection_report']['name'])) ) {
						$ErrorMessage 	= "Please upload at least one file .";
						$success 		= 0;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					} else {
						
						$PowerInjectionApplicationDetailsEntity = $this->PowerInjectionApplicationDetails->newEntity();

						$PowerInjectionApplicationDetailsEntity->application_id		= $id;
						$PowerInjectionApplicationDetailsEntity->app_geo_loc_id		= $geo_id;
						$PowerInjectionApplicationDetailsEntity->application_type		= $applyOnlinesData->application_type;
						$PowerInjectionApplicationDetailsEntity->created_date 		= $this->NOW();
						
						$insert_id= $this->PowerInjectionApplicationDetails->save($PowerInjectionApplicationDetailsEntity);

						$insertId = $insert_id->id;
						
						if(!empty($this->request->data['power_injection_report']['name']))
						{
							$prefix_file 	= '';
							$name 			= $this->request->data['power_injection_report']['name'];
							$ext 			= substr(strtolower(strrchr($name, '.')), 1);
							$file_name 		= $prefix_file.date('Ymdhms').rand();
							$uploadPath 	= APPLICATIONS_PATH.$id.'/';
							if(!file_exists(APPLICATIONS_PATH.$id)) {
								@mkdir(APPLICATIONS_PATH.$id, 0777);
							}
							$file_location 	= WWW_ROOT.$uploadPath.'power_injection'.'_'.$file_name.'.'.$ext;
							if(move_uploaded_file($this->request->data['power_injection_report']['tmp_name'],$file_location))
							{

								$couchdbId 		= $this->ReCouchdb->saveData($uploadPath,$file_location,$prefix_file, 'power_injection_'.$file_name.'.'.$ext,$this->Session->read('Customers.id'),'power_injection');

								$this->PowerInjectionApplicationDetails->updateAll(array('power_injection_report'=>'power_injection_'.$file_name.'.'.$ext),array('id'=>$insertId));
								
							}
						} 
						$PowerInjectionentry 	= $this->PowerInjectionApplicationDetails->find('all',array('conditions'=>array('id'=>$insertId)))->first();
						if(!empty($PowerInjectionentry)) {
							
							$this->ApplicationStages->saveStatus($id,$this->ApplicationStages->POWER_INJECTION,$customer_id,'');
							$ErrorMessage 	= "Upload Documents Succesfully.";
							$success 		= 1;
							$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
							$this->ApiToken->SetAPIResponse('success',$success);
						}
						 else {
							$ErrorMessage 	= "Error while uploading documents.";
							$success 		= 0;
							$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
							$this->ApiToken->SetAPIResponse('success',$success);
						}
					}	
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate form details.";
				$success 		= 0;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	 * POWER_INJECTIONApprovalDocumentAll
	 * Behaviour : Public
	 * @defination : Method is use to complete the POWER_INJECTIONApprovalDocumentAll
	 */
	public function POWER_INJECTIONApprovalDocumentAll()
	{	
		//echo"<pre>"; print_r($this->request->data); die();
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['POWER_INJECTIONApproval_application_id'])?$this->request->data['POWER_INJECTIONApproval_application_id']:0);
		$geo_id 				= (isset($this->request->data['POWER_INJECTIONApproval_app_geo_loc_id'])?$this->request->data['POWER_INJECTIONApproval_app_geo_loc_id']:0);
		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} 
		else{
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->Applications->viewApplication($id);
			$customer_id 			= $this->Session->read("Customers.id");
			$member_id 				= $this->Session->read("Members.id");

			if (!empty($applyOnlinesData) && (!empty($customer_id) || !empty($member_id))) {
				
				if((empty($this->request->data['power_injection_reason'])) || (empty($this->request->data['power_injection_approval_date'])) ) {
					$ErrorMessage 	= "Please select required the fields.";
					$success 		= 0;
					$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
					$this->ApiToken->SetAPIResponse('success',$success);

				} else {
					
						if(!empty($member_id)){
							//$power_injection_approval_date = str_replace('/', '-', $this->request->data['power_injection_approval_date']); 
							//$power_injection_approval_date = date('Y-m-d',strtotime($power_injection_approval_date));

							$date_parts = explode('/', $this->request->data['power_injection_approval_date']);

							if (count($date_parts) === 3) {
							    // Rearrange the date parts to YYYY-MM-DD
							    $power_injection_approval_date = $date_parts[2] . '-' . $date_parts[0] . '-' . $date_parts[1];
							} else {
							    // Handle error if date format is incorrect
							    $power_injection_approval_date = 'Invalid date format';
							}
							//echo"<pre>"; print_r($power_injection_approval_date); die();
							
							$this->PowerInjectionApplicationDetails->updateAll(array('power_injection_approve'=>$this->request->data['power_injection_approve'],'power_injection_reason'=>$this->request->data['power_injection_reason'],'power_injection_approved_by'=>$member_id,'power_injection_approval_date'=>$power_injection_approval_date,'modified_by'=>$member_id,'modified_date'=>$this->NOW()),array('application_id'=>$id,'app_geo_loc_id'=>$geo_id));

							$this->Applications->updateAll(array('application_status'=>$this->ApplicationStages->POWER_INJECTION_APPROVED,'modified'=>$this->NOW(),'modified_by'=>$member_id),array('id'=>$id));
							$this->ApplicationStages->saveStatus($id,$this->ApplicationStages->POWER_INJECTION_APPROVED,$member_id,'');
						}
						
						$ErrorMessage 	= "Data Save Succesfully.";
						$success 		= 1;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					//echo"<pre>"; print_r($success); die();
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate form details.";
				$success 		= 0;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	public function intimation($id = null) 
	{
		$customer_id 		= $this->Session->read("Customers.id");
		$member_id 			= $this->Session->read("Members.id");
		$application_type   = (isset($this->request->data['application_type'])?$this->request->data['application_type']:0);
		$application_id     = (isset($this->request->data['application_id'])?$this->request->data['application_id']:0);
		$is_member          = false;
		if(!empty($member_id)){
			$is_member      = true;
		}
		if(empty($id)) {
			$this->Flash->error('Please Select Valid Installer.');
			return $this->redirect('home');
		} else {

			$encode_id 						= $id;
			$id 							= intval(decode($id));
			$applyOnlinesData 				= $this->Applications->viewApplication($id);

			$wtg_applications 				= $this->ApplicationGeoLocation->find("all",['conditions'=>array('application_id'=>$id,'approved'=>1)])->toArray();
			
			$applicationCategory 			= $this->ApplicationCategory->find('all',array('conditions'=>array('id'=>$applyOnlinesData->application_type)))->first();
		}

		$this->set('logged_in_id',$this->Session->read('Customers.id'));
		$this->set('is_member',$is_member);
		$this->set('encode_id',$encode_id);
		$this->set('applyOnlinesData',$applyOnlinesData);
		$this->set('applicationCategory',$applyOnlinesData);
		$this->set('ApplicationProjectCommissioning',$this->ApplicationProjectCommissioning);
		$this->set('wtg_applications',$wtg_applications);
		$this->set("pageTitle","Intimation Completion");
	}
	public function project_commissioning($id = null) 
	{
		$customer_id 		= $this->Session->read("Customers.id");
		$member_id 			= $this->Session->read("Members.id");
		$application_type   = (isset($this->request->data['application_type'])?$this->request->data['application_type']:0);
		$application_id     = (isset($this->request->data['application_id'])?$this->request->data['application_id']:0);
		$is_member          = false;
		if(!empty($member_id)){
			$is_member      = true;
		}
		if(empty($id)) {
			$this->Flash->error('Please Select Valid Installer.');
			return $this->redirect('home');
		} else {

			$encode_id 						= $id;
			$id 							= intval(decode($id));
			$applyOnlinesData 				= $this->Applications->viewApplication($id);

			$wtg_applications 				= $this->ApplicationGeoLocation->find("all",['conditions'=>array('application_id'=>$id,'approved'=>1)])->toArray();
			
			$applicationCategory 			= $this->ApplicationCategory->find('all',array('conditions'=>array('id'=>$applyOnlinesData->application_type)))->first();
		}

		$this->set('logged_in_id',$this->Session->read('Customers.id'));
		$this->set('is_member',$is_member);
		$this->set('encode_id',$encode_id);
		$this->set('applyOnlinesData',$applyOnlinesData);
		$this->set('applicationCategory',$applyOnlinesData);
		$this->set('ApplicationProjectCommissioning',$this->ApplicationProjectCommissioning);
		$this->set('wtg_applications',$wtg_applications);
		$this->set("pageTitle","Project Commissioning");
	}
		/**
	 *
	 * IntimationCompletionAll
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to complete Intimation under execution popup
	 *
	 */
	public function IntimationCompletionAll()
	{
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['comp_intimation_id'])?$this->request->data['comp_intimation_id']:0);
		$geo_id 			= (isset($this->request->data['int_comp_app_geo_loc_id'])?$this->request->data['int_comp_app_geo_loc_id']:0);
		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} else {
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->Applications->viewApplication($id);
			$customer_id 			= $this->Session->read("Customers.id");
			$member_id 			= $this->Session->read("Members.id");
			if (!empty($applyOnlinesData) && (!empty($customer_id))) {
				if ($this->request->is('post')) {
					
						$ApplicationProjectCommissioningEntity = $this->ApplicationProjectCommissioning->newEntity();

						$ApplicationProjectCommissioningEntity->application_id			= $id;
						$ApplicationProjectCommissioningEntity->app_geo_loc_id			= $geo_id;
						$ApplicationProjectCommissioningEntity->application_type		= $applyOnlinesData->application_type;
						$ApplicationProjectCommissioningEntity->intimation_date			= $this->NOW();
						$ApplicationProjectCommissioningEntity->intimation_completion	= 1;
						$ApplicationProjectCommissioningEntity->created_by 				= $customer_id;
						$ApplicationProjectCommissioningEntity->created 				= $this->NOW();
						
						$insert_id= $this->ApplicationProjectCommissioning->save($ApplicationProjectCommissioningEntity);

						$insertId = $insert_id->id;
						
						$ProjectCommissioningentry 	= $this->ApplicationProjectCommissioning->find('all',array('conditions'=>array('id'=>$insertId)))->first();
						if(!empty($ProjectCommissioningentry)) {
							
							$this->ApplicationStages->saveStatus($id,$this->ApplicationStages->INTIMATION_FOR_COMPLETION,$customer_id,'');
							$ErrorMessage 	= "Intimation submitted successfully.";
							$success 		= 1;
							$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
							$this->ApiToken->SetAPIResponse('success',$success);
						}
						 else {
							$ErrorMessage 	= "Error while uploading documents.";
							$success 		= 0;
							$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
							$this->ApiToken->SetAPIResponse('success',$success);
						}
						
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate form details.";
				$success 		= 0;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;

	}
	/**
	 * ProjectCommissioningStageAll
	 * Behaviour : Public
	 * @defination : Method is use to complete the ProjectCommissioningStageAll
	 */
	public function ProjectCommissioningStageAll()
	{	
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['ProjectCommissioning_application_id'])?$this->request->data['ProjectCommissioning_application_id']:0);
		$geo_id 				= (isset($this->request->data['ProjectCommissioning_app_geo_loc_id'])?$this->request->data['ProjectCommissioning_app_geo_loc_id']:0);
		//echo"<pre>"; print_r($this->request->data); die();
		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} 
		else{
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->Applications->viewApplication($id);
			$customer_id 			= $this->Session->read("Customers.id");
			$member_id 				= $this->Session->read("Members.id");

			if (!empty($applyOnlinesData) && (!empty($customer_id) || !empty($member_id))) {
				
				if((empty($this->request->data['pc_upload_file']['name'])) ||(empty($this->request->data['PC_meter_no'])) || (empty($this->request->data['PC_date'])) ) {
					$ErrorMessage 	= "Please select required the fields.";
					$success 		= 0;
					$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
					$this->ApiToken->SetAPIResponse('success',$success);

				} else {
						
						if(!empty($member_id)){

							if(!empty($this->request->data['pc_upload_file']['name']))
							{
								$prefix_file 	= '';
								$name 			= $this->request->data['pc_upload_file']['name'];
								$ext 			= substr(strtolower(strrchr($name, '.')), 1);
								$file_name 		= $prefix_file.date('Ymdhms').rand();
								$uploadPath 	= APPLICATIONS_PATH.$id.'/';
								if(!file_exists(APPLICATIONS_PATH.$id)) {
									@mkdir(APPLICATIONS_PATH.$id, 0777);
								}
								$file_location 	= WWW_ROOT.$uploadPath.'pc_upload_file'.'_'.$file_name.'.'.$ext;
								if(move_uploaded_file($this->request->data['pc_upload_file']['tmp_name'],$file_location))
								{

									$couchdbId 		= $this->ReCouchdb->saveData($uploadPath,$file_location,$prefix_file, 'pc_upload_file_'.$file_name.'.'.$ext,$this->Session->read('Customers.id'),'pc_upload_file');

									$this->ApplicationProjectCommissioning->updateAll(array('pc_upload_file'=>'pc_upload_file_'.$file_name.'.'.$ext),array('application_id'=>$id));
									
								}
							} 
							// $PC_date = str_replace('/', '-', $this->request->data['PC_date']); 
							// $PC_date = date('Y-m-d',strtotime($PC_date));
							
							$date_parts = explode('/', $this->request->data['PC_date']);

							if (count($date_parts) === 3) {
							    // Rearrange the date parts to YYYY-MM-DD
							    $PC_date = $date_parts[2] . '-' . $date_parts[0] . '-' . $date_parts[1];
							} else {
							    // Handle error if date format is incorrect
							    $PC_date = 'Invalid date format';
							}
							$this->ApplicationProjectCommissioning->updateAll(array('PC_meter_no'=>$this->request->data['PC_meter_no'],'PC_date'=>$PC_date,'modified_by'=>$member_id,'modified_date'=>$this->NOW()),array('application_id'=>$id,'app_geo_loc_id'=>$geo_id));

							$this->Applications->updateAll(array('application_status'=>$this->ApplicationStages->PROJECT_COMMISSIONING,'modified'=>$this->NOW(),'modified_by'=>$member_id),array('id'=>$id));
							$this->ApplicationStages->saveStatus($id,$this->ApplicationStages->PROJECT_COMMISSIONING,$member_id,'');
						}
						
						$ErrorMessage 	= "Data Save Succesfully.";
						$success 		= 1;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					//echo"<pre>"; print_r($success); die();
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate form details.";
				$success 		= 0;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
}