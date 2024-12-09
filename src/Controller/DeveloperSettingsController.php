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

class DeveloperSettingsController extends FrontAppController
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
		$this->loadModel('ChargingCertificate');
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
		$this->loadModel('CeiApplicationDetails');
		$this->loadModel('ThirdpartyApiLog');
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
		$this->loadModel('ApplyonlineMessage');
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
		$this->loadModel('ApplicationConnectivityStep');
		$this->loadModel('DeveloperWorkorder');
		$this->loadModel('DeveloperAssignWorkorder');
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
		$this->set("MStatus",$this->ApplyOnlineApprovals);
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
	 * Displays a workorder
	 *
	 * @param mixed What page to display
	 * @return void
	 */
	public function workorder($id = 0,$activetab=0) {
		
		$is_installer 			= false;
		$installer_id           = '';
		$type_of_applicant 		= $this->ApiToken->arrFirmDropdown;
		$designation 			= $this->ApiToken->arrDesignation;
		$injectionLevel 		= $this->ApiToken->arrInjectionLevel;
		$gridLevel 				= $this->ApiToken->arrGridLevel;
		$EndSTU 				= $this->ApiToken->arrEndSTU;
		$EndCTU 				= $this->ApiToken->arrEndCTU;
		$Workorder 				= '';
		$customerId 			= $this->Session->read("Customers.id");
		$member_id 				= $this->Session->read("Members.id");
		$ses_customer_type 		= $this->Session->read('Customers.customer_type');
		if($ses_customer_type == "installer") {
			$is_installer 		= true;
			$customer_details 	= $this->DeveloperCustomers->find('all',array(
				'conditions'=> array('id'=>$customerId)))->first();
			$installer_id 		= $customer_details['installer_id'];
		}
		
		if(empty($customerId) && empty($member_id))
		{
			return $this->redirect(URL_HTTP.'/home');
		}
		$errorWorkOrder 	= 0;
		$application_errors = array();

		if( isset($this->request->data['Workorder']) && !empty($this->request->data['Workorder']))
		{
			$errors			= array();
			$requestData 	= $this->request->data['Workorder'];

			foreach($requestData['work_no'] as $key=>$workorder) {

				if(!empty($workorder) && !empty($requestData['capacity'][$key]) && !empty($requestData['workorder_date'][$key])) 
				{
					$flagSave 									= 0;
					if(isset($requestData['id_wororder'][$key]) && !empty($requestData['id_wororder'][$key])) {
						$workorderDetails 						= $this->DeveloperWorkorder->get($requestData['id_wororder'][$key]);
						$developerWorkorderEntity 				= $this->DeveloperWorkorder->patchEntity($workorderDetails,array());
						$flagSave 								= 1;
					} elseif(!empty($requestData['workorder_doc'][$key]['name'])) {
						$developerWorkorderEntity 				= $this->DeveloperWorkorder->newEntity();
						$developerWorkorderEntity->created 		= $this->NOW();
						$developerWorkorderEntity->created_by 	= $customerId;
						$flagSave 								= 1;
					}
					$subTotal 	= 0;
					if(isset($requestData['child_work_no_'.$key][0]) && !empty($requestData['child_work_no_'.$key][0]))
					{
						foreach($requestData['child_work_no_'.$key] as $keychild => $workorder_child) { 
							if(!empty($requestData['child_capacity_'.$key][$keychild])) {
								$subTotal 		= $subTotal + $requestData['child_capacity_'.$key][$keychild];
							}
						}
						if($subTotal > $requestData['capacity'][$key]) {
							$flagSave 		= 0;
						}
					}
					
					if($flagSave == 1) {
						$developerWorkorderEntity->installer_id 	= $installer_id;
						$developerWorkorderEntity->workorder_no 	= $workorder;
						$developerWorkorderEntity->capacity 		= $requestData['capacity'][$key];
						$developerWorkorderEntity->workorder_date 	= date('Y-m-d',strtotime($requestData['workorder_date'][$key]));
						$developerWorkorderEntity->modified 		= $this->NOW();
						$developerWorkorderEntity->modified_by 		= $customerId;
						
						$this->DeveloperWorkorder->save($developerWorkorderEntity);
						if(!empty($requestData['workorder_doc'][$key]['name'])) {
							$prefix_file 	= '';
							$name 			= $requestData['workorder_doc'][$key]['name'];
							$ext 			= substr(strtolower(strrchr($name, '.')), 1);
							$file_name 		= $prefix_file.date('Ymdhms').rand();

							$uploadPath 	= DEVELOPER_WORKORDER_PATH.$developerWorkorderEntity->id.'/';
							if(!is_dir(DEVELOPER_WORKORDER_PATH.$developerWorkorderEntity->id)) {
								@mkdir(DEVELOPER_WORKORDER_PATH.$developerWorkorderEntity->id, 0777);
							}
							$file_location 	= WWW_ROOT.$uploadPath.'wo'.'_'.$file_name.'.'.$ext;
							if(move_uploaded_file($requestData['workorder_doc'][$key]['tmp_name'],$file_location))
							{
								$couchdbId 		= $this->ReCouchdb->saveData($uploadPath,$file_location,$prefix_file,'wo_'.$file_name.'.'.$ext,$customerId,'workorder');
								$this->DeveloperWorkorder->updateAll(['workorder_doc'=>'wo'.'_'.$file_name.'.'.$ext],['id'=>$developerWorkorderEntity->id]);
							}
						}
						if(isset($requestData['child_work_no_'.$key][0]) && !empty($requestData['child_work_no_'.$key][0]))
						{
							foreach($requestData['child_work_no_'.$key] as $keychild => $workorder_child) {
								if(!empty($workorder_child) && !empty($requestData['child_developer_'.$key][$keychild]) && !empty($requestData['child_capacity_'.$key][$keychild])) 
								{
									$flagChildSave 	= 1;
									if(isset($requestData['child_id_wororder_'.$key][$keychild]) && !empty($requestData['child_id_wororder_'.$key][$keychild])) {
										$workorderAssignDetails 					= $this->DeveloperAssignWorkorder->get($requestData['child_id_wororder_'.$key][$keychild]);
										if($workorderAssignDetails->status > 0) {
											$flagChildSave 							= 0;
										}
										$developerChildWorkorderEntity 				= $this->DeveloperWorkorder->patchEntity($workorderAssignDetails,array());
									} else {
										$developerChildWorkorderEntity 				= $this->DeveloperAssignWorkorder->newEntity();
										$developerChildWorkorderEntity->created 	= $this->NOW();
										$developerChildWorkorderEntity->created_by 	= $customerId;
									
									}
									if($flagChildSave == 1) {
										$developerChildWorkorderEntity->workorder_id 		= $developerWorkorderEntity->id;
										$developerChildWorkorderEntity->installer_id 		= $installer_id;
										$developerChildWorkorderEntity->assign_installer_id = $requestData['child_developer_'.$key][$keychild]; 
										$developerChildWorkorderEntity->workorder_no 		= $workorder_child;
										$developerChildWorkorderEntity->capacity 			= $requestData['child_capacity_'.$key][$keychild];
										$developerChildWorkorderEntity->modified 			= $this->NOW();
										$developerChildWorkorderEntity->modified_by 		= $customerId;
										$this->DeveloperAssignWorkorder->save($developerChildWorkorderEntity);
									}
									
								}
							}
						}
					}
					
					
				} else {
					$errorWorkOrder 	= 1;
				}
			}
			if($errorWorkOrder == 0) {
				$this->Flash->set('Project details saved successfully.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
			}
		}
		
		$developerWorkOrderDetails  	= $this->DeveloperWorkorder->find('all',array(
											'conditions'=>array('installer_id'=>$installer_id),
											'order'		=> array('id'=>'asc')))->toArray();
		$developerList 					= $this->Developers->find('all',array(
											'conditions'=>array('geda_approval'=>1,'payment_status'=>1,'id !='=>$installer_id),
											'order' 	=> array('installer_name'=>'asc')))->toArray();
		if(!empty($developerWorkOrderDetails)) {
			foreach($developerWorkOrderDetails as $key=>$workorderDetails) {
				$developerChildDetails  	= $this->DeveloperAssignWorkorder->find('all',array(
											'conditions'=>array('workorder_id'=>$workorderDetails->id),
											'order'		=> array('id'=>'asc')))->toArray();
				$workorderDetails->assignWorkorder	= $developerChildDetails;
				
			}
		}
		$arrDeveloper 					= array();
		if(!empty($developerList)) {
			foreach($developerList as $developerData) {
				$arrDeveloper[$developerData->id] 	= $developerData->installer_name;
			}
		}
		$this->set('pageTitle','Set Project');
		$this->set('type_of_applicant',$type_of_applicant);
		$this->set('designation',$designation);
		$this->set('Workorder',$Workorder);
		$this->set("ApplicationError",$application_errors);
		$this->set("developerWorkOrderDetails",$developerWorkOrderDetails);
		$this->set("ReCouchdb",$this->ReCouchdb);
		$this->set("arrDeveloper",$arrDeveloper);
		$this->set("errorWorkOrder",$errorWorkOrder);
		$this->set("DeveloperAssignWorkorder",$this->DeveloperAssignWorkorder);
	}
	public function addComponent() {
		$this->layout 		= 'ajax';
		$newRowCounter 		= $this->request->data['newRowCounter'];
		$arrDeveloper 		= array();
		$ses_customer_type 	= $this->Session->read('Customers.customer_type');
		$customerId 		= $this->Session->read("Customers.id");
		if($ses_customer_type == "installer") {
			$is_installer 		= true;
			$customer_details 	= $this->DeveloperCustomers->find('all',array('conditions'=>array('id'=>$customerId)))->first();
			$installer_id 		= $customer_details['installer_id'];
		}
		$developerList 					= $this->Developers->find('all',array(
												'conditions'=> array('geda_approval'=>1,'payment_status'=>1,'id !='=>$installer_id),
												'order' 	=> array('installer_name'=>'asc')))->toArray();
		if(!empty($developerList)) {
			foreach($developerList as $developerData) {
				$arrDeveloper[$developerData->id] 	= $developerData->installer_name;
			}
		}
		$this->set('newRowCounter',$newRowCounter);
		$this->set("arrDeveloper",$arrDeveloper);
		//pr($newRowCounter);
	}
	/*
	 * Displays a assigned_workorder
	 *
	 * @param mixed What page to display
	 * @return void
	 */
	public function assigned_workorder($id = 0,$activetab=0) {
		
		$is_installer 			= false;
		$installer_id           = '';
		$type_of_applicant 		= $this->ApiToken->arrFirmDropdown;
		$designation 			= $this->ApiToken->arrDesignation;
		$injectionLevel 		= $this->ApiToken->arrInjectionLevel;
		$gridLevel 				= $this->ApiToken->arrGridLevel;
		$EndSTU 				= $this->ApiToken->arrEndSTU;
		$EndCTU 				= $this->ApiToken->arrEndCTU;
		$Workorder 				= '';
		$customerId 			= $this->Session->read("Customers.id");
		$member_id 				= $this->Session->read("Members.id");
		$ses_customer_type 		= $this->Session->read('Customers.customer_type');
		if($ses_customer_type == "installer") {
			$is_installer 		= true;
			$customer_details 	= $this->DeveloperCustomers->find('all',array('conditions'=>array('id'=>$customerId)))->first();
			$installer_id 		= $customer_details['installer_id'];
		}
		
		if(empty($customerId))
		{
			return $this->redirect(URL_HTTP.'/home');
		}
		$errorWorkOrder 	= 0;
		$application_errors = array();
		

		$developerAssignedDetails  	= $this->DeveloperAssignWorkorder->find('all',array(
									'conditions'=>array('assign_installer_id'=>$installer_id),
									'order'		=> array('id'=>'asc')))->toArray();
	
		
		$this->set('pageTitle','Assigned Project');
		$this->set('type_of_applicant',$type_of_applicant);
		$this->set('designation',$designation);
		$this->set('Workorder',$Workorder);
		$this->set("ApplicationError",$application_errors);
		$this->set("developerAssignedDetails",$developerAssignedDetails);
		$this->set("ReCouchdb",$this->ReCouchdb);
		$this->set("DeveloperAssignWorkorder",$this->DeveloperAssignWorkorder);
	}
	/**
	 * approve_assigned_workorder
	 * Behaviour : Public
	 * @defination : Method is use to accept Project set by third party
	 */
	public function approve_assigned_workorder($assigned_wo_id='')
	{
		$customerId = $this->Session->read("Customers.id");
		if(empty($customerId))
		{
			return $this->redirect(URL_HTTP.'/home');
		}
		if(!empty($assigned_wo_id))
		{
			$assigned_workorder_id = intval(decode($assigned_wo_id));
			if($assigned_workorder_id>0)
			{
				$this->DeveloperAssignWorkorder->updateAll(['status'=>1,'approved_date'=>$this->NOW(),'modified'=>$this->NOW(),'modified_by'=>$customerId],['id'=>$assigned_workorder_id]);
				$this->Flash->set('Project accepted successfully.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
				$this->redirect('/DeveloperSettings/assigned_workorder');
			}
		}
		else
		{
			$this->redirect('/DeveloperSettings/assigned_workorder');
		}
	}
	/**
	 * reject_assigned_workorder
	 * Behaviour : Public
	 * @defination : Method is use to accept Project set by third party
	 */
	public function reject_assigned_workorder()
	{
		$customerId 			= $this->Session->read("Customers.id");
		$assigned_workorder_id 	= isset($this->request->data['assigned_workorder_id']) ? intval(decode($this->request->data['assigned_workorder_id'])) : 0;
		$reason 				= isset($this->request->data['messagebox']) ? $this->request->data['messagebox'] : '';
		if(empty($customerId))
		{
			$success 	= 2;
			$Message 	= "users/logout";
		}
		if($assigned_workorder_id>0 && !empty($reason))
		{
			$this->DeveloperAssignWorkorder->updateAll(['status'=>2,'reject_reason'=>$reason,'rejected_date'=>$this->NOW(),'modified'=>$this->NOW(),'modified_by'=>$customerId],['id'=>$assigned_workorder_id]);
			$this->Flash->set('Project rejected successfully.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
			$success 	= 1;
			$Message 	= "Project rejected successfully.";
		}
		else
		{
			$Message 	= "Invalid Request. Please validate form details.";
			$success 	= 0;
		}
		$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
		$this->ApiToken->SetAPIResponse('success',$success);
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
}