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


class ApplyOnlinesController extends FrontAppController
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
		$this->loadModel('Developers');
		$this->loadModel('DeveloperCustomers');
		$this->loadModel('UpdateDiscomDataLog');
		$this->loadModel('SchemeMaster');
		$this->set('ApplyonlineMessage',$this->ApplyonlineMessage);
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


	/**
	 *
	 * validateAccess
	 *
	 * Behaviour : Public
	 *
	 * @param : $isRistricted   : Value is true of false, base on this restriction is set in admin adrea
	 * @defination : Method is use to set admin area use for admin base on restriction set for particular
	 *
	 */
	public function validateAccess($page="")
	{
		switch ($page) {
			case 'JERDA_REGISTRATION':
			{
				$member_type = $this->Session->read('Members.member_type');
				$customer_type = $this->Session->read('Customers.customer_type');
				if ($member_type == $this->ApplyOnlines->JREDA) {
					return true;
				}
				if ($customer_type == "customer" || $customer_type == "installer") {
					return true;
				}
				break;
			}
			case 'WORK_COMPLETION_REGISTRATION':
			{
				$member_type = $this->Session->read('Members.member_type');
				$customer_type = $this->Session->read('Customers.customer_type');
				if ($member_type == $this->ApplyOnlines->JREDA) {
					return true;
				}
				if ($customer_type == "customer" || $customer_type == "installer") {
					return true;
				}
				break;
			}
		}
		$this->Flash->error('You are not authorized to access this application.');
		return $this->redirect(URL_HTTP.'/apply-online-list');
	}


	/**
	 *
	 * validateMemberPermission
	 *
	 * Behaviour : Public
	 *
	 * @param : $isRistricted   : Value is true of false, base on this restriction is set in admin adrea
	 * @defination : Method is use to set admin area use for admin base on restriction set for particular
	 *
	 */
	public function validateMemberPermission($page="")
	{
		switch ($page) {
			case 'FESIBILITY':
			{
				$member_type = $this->Session->read('Members.member_type');
				// /$member_type == $this->ApplyOnlines->DISCOM
				if (!empty($member_type)) {
					return true;
				}
				break;
			}
			case 'JERDA_REGISTRATION':
			{
				$member_type = $this->Session->read('Members.member_type');
				$customer_type = $this->Session->read('Customers.customer_type');
				if ($member_type == $this->ApplyOnlines->JREDA) {
					return true;
				}
				if ($customer_type == "customer" || $customer_type == "installer") {
					return true;
				}
				break;
			}
			case 'CHARGING_CERTIFICATE':
			{
				$member_type = $this->Session->read('Members.member_type');
				//$member_type == $this->ApplyOnlines->DISCOM
				if (!empty($member_type)) {
					return true;
				}
				break;
			}
			case 'WORK_COMPLETION':
			{
				$member_type = $this->Session->read('Members.member_type');
				if ($member_type == $this->ApplyOnlines->DISCOM) {
					return true;
				}
				break;
			}
		}
		$this->Flash->error('You are not authorized to access this application.');
		return $this->redirect(URL_HTTP.'/apply-online-list');
	}

	/*
	 * Displays a index
	 *
	 * @param mixed What page to display
	 * @return void
	 */
	public function index($id = 0, $project_id = 0) {
		$is_installer 			= false;
		$installer_id           = '';
		if(!empty($this->Session->read('Members.member_type')))
		{
			$this->setMemberArea();
			$member_type 		= $this->Session->read('Members.member_type');
			$customerId 		= $this->Session->read("Members.id");
			$ses_customer_type 	= $this->Session->read('Members.member_type');
			$ses_login_type 	= 'member';
			$is_installer 		= false;
			$decode_id 			= decode($id);
			$app_oth_details 	= $this->ApplyOnlinesOthers->find('all',array('conditions'=>array('application_id'=>$decode_id)))->first();
			$customerTable 		= ($app_oth_details->created_by_type == 'developer') ? 'DeveloperCustomers' : 'Customers';
			$installerTable 	= ($app_oth_details->created_by_type == 'developer') ? 'Developers' : 'Installers';
		}
		else
		{
			$this->setCustomerArea();
			$customerId 		= $this->Session->read("Customers.id");
			$ses_customer_type 	= $this->Session->read('Customers.customer_type');
			$ses_login_type 	= $this->Session->read('Customers.login_type');
			$customerTable 		= ($ses_login_type == 'developer') ? 'DeveloperCustomers' : 'Customers';
			$installerTable 	= ($ses_login_type == 'developer') ? 'Developers' : 'Installers';
			if ($ses_customer_type == "installer") {
				$is_installer 	= true;
				$customer_details 	= $this->$customerTable->find('all',array('conditions'=>array('id'=>$customerId)))->first();
				$installer_id 		= $customer_details['installer_id'];
			}
		}
		//$this->removeExtraTags('ApplyOnlines');

		$tab 		= '';
		$ApplyonlinDocsList = array();
		$Applyonlinprofile 	= array();
		if($id == '0' && $project_id == '0' && $is_installer==false)
		{
			return $this->redirect('project');
		}
		$project_id 	= decode($project_id);
		$create_project = '0';
		if(!empty($id) && $id!='0'){
			$id 	= decode($id);
			$app_details 	= $this->ApplyOnlines->find('all',array('conditions'=>array('id'=>$id)))->first();
			$project_id 	= $app_details->project_id;
			$this->set("edit_id",$id);
			$this->set("str_url",encode($id).'/'.encode($project_id));
		} else {
			$ApplyOnlinesPendingToSubmit = $this->ApplyOnlines->find("all",['fields'=>['id'],'conditions'=>['or'=>['application_status is null','application_status'=>'','application_status'=>'0'],'customer_id'=>$customerId,'project_id'=>$project_id]])->toArray();
			//echo 'customer_id'.$customerId,'project_id'.$project_id;
			//pr($ApplyOnlinesPendingToSubmit);exit;
			if(!empty($ApplyOnlinesPendingToSubmit)) {
				return $this->redirect('apply-onlines/'.encode($ApplyOnlinesPendingToSubmit[0]['id']).'/'.encode($project_id));
			}
			if($project_id>0)
			{
				$ApplyOnlinesAlreadySubmit = $this->ApplyOnlines->find("all",['conditions'=>['application_status is not null','customer_id'=>$customerId,'project_id'=>$project_id,'project_id is not null']])->toArray();

				if(!empty($ApplyOnlinesAlreadySubmit)) {
					$this->Flash->set('Application is already been submitted for selected project.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'error']]);
					return $this->redirect('apply-online-list');
				}
				$this->set("str_url",'0/'.encode($project_id));
			}
			else if($is_installer==true)
			{
				$create_project = '1';
				$this->set("str_url",'');
			}
			$id = '';
		}
		$connection         = ConnectionManager::get('default');
		$sql_icm 			= " SELECT ic.id
								FROM installer_category_mapping as icm left join installer_category as ic on icm.category_id=ic.id
								WHERE installer_id 	='".$installer_id."'";
		$icm_output  		= $connection->execute($sql_icm)->fetchAll('assoc');
		$allocatedCategory  = 0;
		if(isset($icm_output[0]['id']))
		{
			$allocatedCategory  = $icm_output[0]['id'];
		}
		$arrFieldsMap 	= array('rpo_rec','rpo_is_captive','rpo_is_obligation','gerc_is_distribution','gerc_certificate','rpo_is_cpp','capacity_cpp','rpo_is_captive_rpo','rpo_is_cert_getco','capacity_rpo_cert','rec_is_registration','rec_registration_copy','rec_is_receipt','rec_receipt_copy','rec_is_power_evaluation','rec_power_evaluation','rec_is_allowed_sancation','rec_is_valid_min_cap','upload_undertaking');
		$project_data 		= $this->Projects->find('all',array('conditions'=>array('id'=>$project_id)))->first();
		$execution_data 	= array();
		if(isset($customerId) && !empty($customerId)){
			$this->layout = 'frontend';
			if(!empty($id)) {
				$ApplyOnlineData 						= $this->ApplyOnlines->get($id);
				$ApplyOnlinesEntity						= $this->ApplyOnlines->patchEntity($ApplyOnlineData,$this->request->data,['validate'=>'tab1']);
				$this->ApplyOnlines->data_entity 		= $ApplyOnlinesEntity;
				$project_id 							= $ApplyOnlinesEntity->project_id;
				$execution_data 						= $this->Installation->find('all',array('conditions'=>array('project_id'=>$project_id)))->first();

				$OthersData 							= $this->ApplyOnlinesOthers->find('all',array('conditions'=>array('application_id'=>$id)))->first();

				//$ApplyOnlineOthersGet 				= $this->ApplyOnlinesOthers->get($OthersData->id);

				$ApplyOnlinesOthersEntity				= $this->ApplyOnlinesOthers->patchEntity($OthersData,$this->request->data);

				$ApplyOnlinesOthersEntity->modified_by_type = $ses_login_type;
				$ApplyOnlinesOthersEntity->modified_by 		= $customerId;
				$this->ApplyOnlines->data_entity_others 	= $ApplyOnlinesOthersEntity;
				

			} else {
				$ApplyOnlinesEntity	= $this->ApplyOnlines->newEntity($this->request->data,['validate'=>'tab1']);
				$this->ApplyOnlines->data_entity 	= $ApplyOnlinesEntity;
				$ApplyOnlinesEntity->created 		= $this->NOW();
				$ApplyOnlinesOthersEntity			= $this->ApplyOnlinesOthers->newEntity($this->request->data);
				$ApplyOnlinesOthersEntity->scheme_id 		= $this->SchemeMaster->findActiveSchemeId();
				$ApplyOnlinesOthersEntity->modified_by_type = $ses_login_type;
				$ApplyOnlinesOthersEntity->modified_by 		= $customerId;
				$ApplyOnlinesOthersEntity->created_by_type 	= $ses_login_type;
				$ApplyOnlinesOthersEntity->created_by 		= $customerId;
			}


			$condition_state_list = array('id'=>'4');
			
			$customer_details 	= $this->$customerTable->find('all',array('conditions'=>array('id'=>$customerId)))->first();

			if($is_installer == true && (!isset($this->request->data) || empty($this->request->data)) && empty($id) && $create_project=='0')
			{
				
				$state_details 		= $this->States->find('all',array('conditions'=>array('statename'=>$customer_details['state'])))->first();
				if(!empty($state_details))
				{
					$this->request->data['ApplyOnlines']['apply_state'] = $state_details['id'];
				}
				else
				{
					$this->request->data['ApplyOnlines']['apply_state'] = $customer_details['state'];
				}
				$this->request->data['ApplyOnlines']['installer_id'] 	= $customer_details['installer_id'];
				$condition_state_list 									= array('id'=>$this->request->data['ApplyOnlines']['apply_state']);
				$this->request->data['ApplyOnlines']['disclaimer']		= '1';
				$this->request->data['tab_1'] 							= '1';
			}
			$project_errors = array();
			if(isset($this->request->data) && !empty($this->request->data) && (isset($this->request->data['tab_4'])))
			{
				//&& $this->request->data['project_type']!=$this->ApplyOnlines->category_others && empty($this->request->data['project_social_consumer'])
				if($allocatedCategory==3 || ($this->request->data['project_type']!=$this->ApplyOnlines->category_residental))
				{
					$this->request->data['project_disclaimer_subsidy']	= '1';
					//$this->request->data['project_renewable_attr_chk']	= '1';
				}
				$this->Projects->data_post 						= $this->request->data;
				$this->Projects->data_post['ins_id'] 			= $customer_details['installer_id'];
				$ApplyOnlinesEntity								= $this->Projects->newEntity($this->request->data,['validate'=>'tab4']);
				//&& $this->request->data['project_type']!=$this->ApplyOnlines->category_others && empty($this->request->data['project_social_consumer'])
				if($allocatedCategory==3 || ($this->request->data['project_type']!=$this->ApplyOnlines->category_residental))
				{
					$ApplyOnlinesEntity->project_disclaimer_subsidy	= '1';
					//$ApplyOnlinesEntity->project_renewable_attr_chk	= '1';

				}
				$project_errors 									= $ApplyOnlinesEntity->errors();
				if(!$ApplyOnlinesEntity->errors())
				{
					$this->request->data['ses_login_type']			= $ses_login_type;
					$resultArr = $this->Projects->getprojectestimation($this->request->data,$customerId);
					return $this->redirect('apply-onlines/0/'.encode($resultArr['proj_id']));
				}
			}
			if(isset($this->request->data) && !empty($this->request->data) && (isset($this->request->data['tab_1']) || isset($this->request->data['tab_2']) || isset($this->request->data['tab_3']) || isset($this->request->data['save_submit'])) && $create_project=='0')
			{

			   $this->ApplyOnlines->data = $this->request->data;
				if(isset($this->request->data['tab_1']) && !empty($this->request->data['tab_1'])) {
					if(empty($this->request->data['ApplyOnlines']['id'])) {
						unset($this->request->data['ApplyOnlines']['id']);
						$ApplyOnlinesEntity	= $this->ApplyOnlines->newEntity($this->request->data,['validate'=>'tab1']);
						$this->ApplyOnlines->data_entity = $ApplyOnlinesEntity;
						//$ApplyOnlinesEntity->transmission_line 						= 1;
						//$this->request->data['ApplyOnlines']['transmission_line']	= 1;
						$ApplyOnlinesEntity->net_meter 								= 1;
						$this->request->data['ApplyOnlines']['net_meter']			= 1;
						$ApplyOnlinesEntity->rpo_rec 								= 0;
						$this->request->data['ApplyOnlines']['rpo_rec']				= 0;
						$this->request->data['ApplyOnlines']['created'] 			= $this->NOW();
						$ApplyOnlinesEntity->created 								= $this->NOW();
						if(!empty($project_data))
						{
							$ApplyOnlinesEntity->lattitue 	= $project_data->latitude;
							$ApplyOnlinesEntity->longitude 	= $project_data->longitude;
						}
						$ApplyOnlinesOthersEntity			= $this->ApplyOnlinesOthers->newEntity($this->request->data);

						$ApplyOnlinesOthersEntity->renewable_attr 	= $project_data->project_renewable_attr;

						$ApplyOnlinesOthersEntity->renewable_rec 	= $project_data->project_renewable_rec;
						$ApplyOnlinesOthersEntity->scheme_id 		= $this->SchemeMaster->findActiveSchemeId();
						$ApplyOnlinesOthersEntity->modified_by_type = $ses_login_type;
						$ApplyOnlinesOthersEntity->modified_by 		= $customerId;
						$ApplyOnlinesOthersEntity->created_by_type 	= $ses_login_type;
						$ApplyOnlinesOthersEntity->created_by 		= $customerId;

					} else {
						$ApplyOnlineGet 	= $this->ApplyOnlines->get($this->request->data['ApplyOnlines']['id']);
						$ApplyOnlinesEntity	= $this->ApplyOnlines->patchEntity($ApplyOnlineGet,$this->request->data,['validate'=>'tab1']);
						$this->ApplyOnlines->data_entity = $ApplyOnlinesEntity;
						$this->request->data['ApplyOnlines']['modified'] 		= $this->NOW();
						$OthersData 						= $this->ApplyOnlinesOthers->find('all',array('conditions'=>array('application_id'=>$ApplyOnlineGet->id)))->first();

						//$ApplyOnlineOthersGet 				= $this->ApplyOnlinesOthers->get($ApplyOnlineGet->id);

						$ApplyOnlinesOthersEntity			= $this->ApplyOnlinesOthers->patchEntity($OthersData,$this->request->data);
						$ApplyOnlinesOthersEntity->modified_by_type = $ses_login_type;
						$ApplyOnlinesOthersEntity->modified_by 		= $customerId;
					}
					if(!$ApplyOnlinesEntity->errors()){
						if($allocatedCategory==3 || (!empty($project_data) && $project_data->project_disclaimer_subsidy==1))
						{
							$ApplyOnlinesEntity->disclaimer_subsidy	= '1';
						}
						$tab = 'tab_1';
						//$this->request->data['ApplyOnlines']['created'] = $this->NOW();
					} else {
						//$this->request->data['ApplyOnlines']['created'] = $this->NOW();
						$tab = '';
					}
				} else if(isset($this->request->data['tab_2']) && !empty($this->request->data['tab_2'])) {
					$ApplyOnlineGet 								= $this->ApplyOnlines->get($this->request->data['ApplyOnlines']['id']);
					if(isset($this->ApplyOnlines->data['ApplyOnlines']['category']) && !empty($this->ApplyOnlines->data['ApplyOnlines']['category']))
					{
						$this->Projects->updateAll(['customer_type'=>$this->ApplyOnlines->data['ApplyOnlines']['category']],['id'=>$project_id]);
					}
					if(!isset($this->request->data['ApplyOnlines']['transmission_line']))
					{
						$this->request->data['ApplyOnlines']['transmission_line'] = !empty($ApplyOnlineGet->transmission_line) ? $ApplyOnlineGet->transmission_line : '';
					}
					$this->ApplyOnlines->data['ApplyOnlines']['transmission_line'] 	= $this->request->data['ApplyOnlines']['transmission_line'];
					if(!isset($this->request->data['ApplyOnlines']['net_meter']))
					{
						$this->request->data['ApplyOnlines']['net_meter'] = !empty($ApplyOnlineGet->net_meter) ? $ApplyOnlineGet->net_meter : '1';
					}
					$this->ApplyOnlines->data['ApplyOnlines']['net_meter'] 	= $this->request->data['ApplyOnlines']['net_meter'];

					if(($this->request->data['ApplyOnlines']['category']!=$this->ApplyOnlines->category_residental && $this->request->data['ApplyOnlines']['category']!=$this->ApplyOnlines->category_others && empty($this->request->data['ApplyOnlines']['social_consumer'])))
					{
						$this->ApplyOnlines->data['ApplyOnlines']['disclaimer_subsidy'] = 1;
						$this->request->data['ApplyOnlines']['disclaimer_subsidy']		= 1;
						$this->Projects->updateAll(['project_disclaimer_subsidy'=>'1'],['id'=>$project_id]);
					}
					/*elseif(($this->request->data['renewable_attr']=='0' || $this->request->data['renewable_attr']=='1') && !empty($this->request->data['ApplyOnlines']['social_consumer']))
					{
						$this->ApplyOnlines->data['ApplyOnlines']['disclaimer_subsidy'] = 0;
						$this->request->data['ApplyOnlines']['disclaimer_subsidy']		= 0;
						$this->Projects->updateAll(['project_disclaimer_subsidy'=>'0'],['id'=>$project_id]);
					}*/
					if($ApplyOnlinesEntity->query_sent==0)
					{
						$search_response=json_encode(['discom'=>$this->request->data['ApplyOnlines']['discom'],'consumer_no'=>$this->request->data['ApplyOnlines']['consumer_no'],'discom_name'=>$this->request->data['ApplyOnlines']['discom_name']]);
					}
					$ApplyOnlinesEntity								= $this->ApplyOnlines->patchEntity($ApplyOnlineGet,$this->request->data,['validate'=>'tab2']);
					if($ApplyOnlinesEntity->query_sent==0)
					{
							//$ApplyOnlinesEntity->api_response = $search_response;
							$ApplyOnlinesEntity->mobile       = $ApplyOnlinesEntity->consumer_mobile;
							$ApplyOnlinesEntity->email        = $ApplyOnlinesEntity->consumer_email;
					}
					if($allocatedCategory==3)
					{
						$ApplyOnlinesEntity->disclaimer_subsidy 	= '1';
					}

					$this->ApplyOnlines->data_entity 				= $ApplyOnlinesEntity;

					/*if($this->request->data['ApplyOnlines']['category'] == $this->ApplyOnlines->category_others || $this->request->data['ApplyOnlines']['category'] == $this->ApplyOnlines->category_residental)
					{
						$connection         = ConnectionManager::get('default');
						$sql_project 		= " UPDATE projects SET project_renewable_rec = NULL , project_renewable_attr = NULL WHERE id = '".$project_id."'";
						$connection->execute($sql_project);

						$sql_applyonline 	= " UPDATE apply_onlines_others SET renewable_rec = NULL , renewable_attr = NULL WHERE application_id = '".$id."'";
						$connection->execute($sql_applyonline);
						$this->request->data['renewable_attr'] 		= NULL;
						$this->request->data['renewable_rec']		= NULL;
						$ApplyOnlinesOthersEntity->renewable_attr 	= NULL;
						$ApplyOnlinesOthersEntity->renewable_rec 	= NULL;
					}
					else
					{*/
						if(isset($this->request->data['renewable_attr']))
						{
							$ApplyOnlinesOthersEntity->renewable_attr 	= $this->request->data['renewable_attr'];
						}
						if(isset($this->request->data['ApplyOnlines']['pv_dc_capacity']))
						{
							$ApplyOnlinesOthersEntity->pv_dc_capacity 	= $this->request->data['ApplyOnlines']['pv_dc_capacity'];
						}
						if(isset($this->request->data['ApplyOnlines']['tariff']))
						{
							$ApplyOnlinesOthersEntity->tariff 			= $this->request->data['ApplyOnlines']['tariff'];
						}
						if(isset($this->request->data['renewable_rec']))
						{
							if($ApplyOnlinesOthersEntity->renewable_attr == 1)
							{
								$this->request->data['renewable_rec'] = NULL;
							}

							$ApplyOnlinesOthersEntity->renewable_rec 	= $this->request->data['renewable_rec'];
						}
						if(isset($this->request->data['ApplyOnlines']['msme']))
						{
							$ApplyOnlinesOthersEntity->msme 			= $this->request->data['ApplyOnlines']['msme'];
						}
						if(isset($this->request->data['ApplyOnlines']['msme_category']))
						{
							$ApplyOnlinesOthersEntity->msme_category 		= $this->request->data['ApplyOnlines']['msme_category'];
						}
						if(isset($this->request->data['ApplyOnlines']['contract_load_more']))
						{
							$ApplyOnlinesOthersEntity->contract_load_more 	= (isset($this->request->data['ApplyOnlines']['msme']) && $this->request->data['ApplyOnlines']['msme'] == 1) ? $this->request->data['ApplyOnlines']['contract_load_more'] : 0;
						}
						if(isset($this->request->data['ApplyOnlines']['type_of_applicant']))
						{
							$ApplyOnlinesOthersEntity->type_of_applicant 	= $this->request->data['ApplyOnlines']['type_of_applicant'];
						}
						if(isset($this->request->data['ApplyOnlines']['applicant_others']))
						{
							$ApplyOnlinesOthersEntity->applicant_others 		= $this->request->data['ApplyOnlines']['applicant_others'];
						}
						if(isset($this->request->data['ApplyOnlines']['msme_aadhaar_no']))
						{
							$ApplyOnlinesOthersEntity->msme_aadhaar_no 		= $this->request->data['ApplyOnlines']['msme_aadhaar_no'];
						}
						if(isset($this->request->data['ApplyOnlines']['type_authority']))
						{
							$ApplyOnlinesOthersEntity->type_authority 		= $this->request->data['ApplyOnlines']['type_authority'];
						}
						if(isset($this->request->data['ApplyOnlines']['name_authority']))
						{
							$ApplyOnlinesOthersEntity->name_authority 		= $this->request->data['ApplyOnlines']['name_authority'];
						}
						if(isset($this->request->data['ApplyOnlines']['map_installer_id']))
						{
							$ApplyOnlinesOthersEntity->map_installer_id 	= ($this->request->data['ApplyOnlines']['map_installer_id']);
						}
						foreach($arrFieldsMap as $Fkey=>$Fval) {
							if(isset($this->request->data['ApplyOnlines'][$Fval]))
							{
								$ApplyOnlinesOthersEntity->$Fval 			= $this->request->data['ApplyOnlines'][$Fval];
							}
						}
					//}


					if(!$ApplyOnlinesEntity->errors()){
						$tab = 'tab_2';
					} else {
						$tab = 'tab_1';
					}

				} else if((isset($this->request->data['tab_3']) && !empty($this->request->data['tab_3'])) || (isset($this->request->data['save_submit']) && !empty($this->request->data['save_submit']))) {
					$ApplyOnlineGet 						= $this->ApplyOnlines->get($this->request->data['ApplyOnlines']['id']);
					if(CAPTCHA_DISPLAY == 1) {
						$this->request->data['ApplyOnlines']['g-recaptcha-response'] = $this->request->data['g-recaptcha-response'];
					}
					$ApplyOnlinesEntity						= $this->ApplyOnlines->patchEntity($ApplyOnlineGet,$this->request->data,['validate'=>'tab3']);
					$this->ApplyOnlines->data_entity 		= $ApplyOnlinesEntity;
					$ApplyOnlinesEntity->member_assign_id 	= $ApplyOnlinesEntity->discom_name;
					if (!empty($ApplyOnlinesEntity->discom_name)) {
						$arrDiscom 								= $this->DiscomMaster->GetDiscomHirarchyByID($ApplyOnlinesEntity->discom_name);
						$ApplyOnlinesEntity->area 				= $arrDiscom->area;
						$ApplyOnlinesEntity->circle 			= $arrDiscom->circle;
						$ApplyOnlinesEntity->division 			= $ApplyOnlinesEntity->discom_name;
						$subdiv_details = $this->getdetailsSubdivision($ApplyOnlinesEntity->consumer_no,$ApplyOnlinesEntity->discom,$ApplyOnlinesEntity->project_id,$ApplyOnlinesEntity->id,'',$ApplyOnlinesEntity->division,$ApplyOnlinesEntity->tno);
						$ApplyOnlinesEntity->subdivision 		= key($subdiv_details['subdivision']);
						if(isset($subdiv_details['first_name']) && empty($subdiv_details['first_name']))
						{
							if(isset($subdiv_details['response_msg']) && !empty($subdiv_details['response_msg'])) {
								$this->Flash->error($subdiv_details['response_msg']);
							} else {
								$this->Flash->error('Incorrect Consumer number or T-no');
							}
							return $this->redirect('apply-onlines/'.encode($ApplyOnlinesEntity->id));
						}
					}
					if($ApplyOnlinesEntity->govt_agency == 1 && GOVERMENT_AGENCY==1)
					{
						$ApplyOnlinesEntity->disCom_application_fee = Configure::read('APPLY_AMOUNT_GOVERNMENT');
						$tax_applicable = Configure::read('APPLY_AMOUNT_GOV_TAX');
					}
					elseif($ApplyOnlinesEntity->category == $this->ApplyOnlines->category_residental && ($ApplyOnlinesEntity->social_consumer==0 || SOCIAL_SECTOR_PAYMENT==0))
					{
						$ApplyOnlinesEntity->disCom_application_fee = Configure::read('APPLY_AMOUNT_RESIDENTIAL');
						$tax_applicable = 0;
					}
					else
					{
						$ApplyOnlinesEntity->disCom_application_fee = Configure::read('APPLY_AMOUNT_NON_GOVERNMENT');
						if($ApplyOnlinesEntity->pv_capacity > 1000) {
							$ApplyOnlinesEntity->disCom_application_fee = floatval(PRICE_PER_KW_GT1MW) * floatval($ApplyOnlinesEntity->pv_capacity);
						}
						$tax_applicable = Configure::read('APPLY_AMOUNT_NON_GOV_TAX');
					}
					$amt_tax_percent 							= Configure::read('APPLY_TAX_PERCENT');
					$ApplyOnlinesEntity->jreda_processing_fee 	= $tax_applicable;
					if($amt_tax_percent=='%')
					{
						$ApplyOnlinesEntity->jreda_processing_fee = ($ApplyOnlinesEntity->disCom_application_fee*$tax_applicable)/100;
					}
					if(isset($this->request->data['save_submit']) && !empty($this->request->data['save_submit']))
					{
						$approval=$this->ApplyOnlineApprovals->Approvalstage($ApplyOnlinesEntity->id);
						if($ApplyOnlinesEntity->disclaimer_subsidy == 1) {
							$allocatedCategory 	= 3;
						}
						$applyOnline 			= $this->ApplyOnlines->find();
						$total_application 		= $applyOnline->select(['total_pvcapacity' => $applyOnline->func()->sum('pv_capacity')])->where(array('installer_id'=>$ApplyOnlinesEntity->installer_id,'application_status not in'=>array($this->ApplyOnlineApprovals->WAITING_LIST)))->first();
						$installerCapacityTotal = $total_application->total_pvcapacity;

						$availableCapacityData 	= $this->InstallerCategoryMapping->find('all',['fields'=>['installer_category.capacity'],'join'=>[['table'=>'installer_category','type'=>'left','conditions'=>'InstallerCategoryMapping.category_id = installer_category.id']],'conditions'=>['InstallerCategoryMapping.installer_id'=>$ApplyOnlinesEntity->installer_id]])->toArray();

						if(!empty($availableCapacityData) && $installerCapacityTotal>$availableCapacityData[0]['installer_category']['capacity'] && $allocatedCategory!=3)
						{
							$ApplyOnlinesEntity->application_status = $this->ApplyOnlineApprovals->WAITING_LIST;
						}
						else if(!in_array(29,$approval))
						{
							$ApplyOnlinesEntity->application_status = $this->ApplyOnlineApprovals->APPLICATION_GENERATE_OTP;
						}
						else
						{
							$approval = $this->ApplyOnlineApprovals->find('all',array('conditions'=>array('application_id'=>$ApplyOnlinesEntity->id)))->last();
							$ApplyOnlinesEntity->application_status = $approval->stage;
						}
					}
					if((empty($ApplyOnlinesEntity->errors()) && isset($this->request->data['submit_captcha'])) && $this->captchaValidation() != 0) {
						$tab = 'tab_3';
					} else {
						$tab = 'tab_2';
					}
				}
				if(isset($ApplyOnlinesEntity->installer_id) && !empty($ApplyOnlinesEntity->installer_id)) {
					$customersData 	= $this->$customerTable->find('all',array('conditions'=>array('installer_id'=>$ApplyOnlinesEntity->installer_id)))->first();
				}
				$ApplyOnlinesEntity->customer_id 	= (isset($customersData->id) && !empty($customersData->id)) ? $customersData->id : $customerId;
				if($project_id != '0')
				{
					$ApplyOnlinesEntity->project_id = $project_id;
				}
				$ApplyOnlinesEntity->modified 		= $this->NOW();
				//$ApplyOnlinesEntity->created 		= $this->NOW();
				$this->ApplyOnlines->data_entity 	= $ApplyOnlinesEntity;
				
				if(!$ApplyOnlinesEntity->errors())
				{
					if(!empty($this->request->data['ApplyOnlines']['aadhar_no_or_pan_card_no']))
					{
						$ApplyOnlinesEntity->aadhar_no_or_pan_card_no	= passencrypt($this->request->data['ApplyOnlines']['aadhar_no_or_pan_card_no']);
					}
					if(!empty($this->request->data['ApplyOnlines']['pan_card_no']))
					{
						$ApplyOnlinesEntity->pan_card_no 				= passencrypt($this->request->data['ApplyOnlines']['pan_card_no']);
					}
					if(!empty($this->request->data['ApplyOnlines']['house_tax_holding_no']))
					{
						$ApplyOnlinesEntity->house_tax_holding_no 		= passencrypt($this->request->data['ApplyOnlines']['house_tax_holding_no']);
					}
					if($this->ApplyOnlines->save($ApplyOnlinesEntity))
					{
						$applyOnlinesOthersDataOrg 	= $this->ApplyOnlinesOthers->find('all',array('conditions'=>array('application_id'=>$id)))->first();

						$id = $ApplyOnlinesEntity->id;
						$ApplyOnlinesOthersEntity->application_id 	= $id;
						$ApplyOnlinesOthersEntity->renewable_rec 	= ($ApplyOnlinesOthersEntity->renewable_attr == 1) ? NULL : $ApplyOnlinesOthersEntity->renewable_rec;
						if(!empty($applyOnlinesOthersDataOrg))
						{
							$ApplyOnlinesOthersEntity->file_company_incorporation = $applyOnlinesOthersDataOrg->file_company_incorporation;
							$ApplyOnlinesOthersEntity->file_board 			= $applyOnlinesOthersDataOrg->file_board;
							$ApplyOnlinesOthersEntity->upload_certificate 	= $applyOnlinesOthersDataOrg->upload_certificate;
							$ApplyOnlinesOthersEntity->gerc_certificate 	= $applyOnlinesOthersDataOrg->gerc_certificate;
							$ApplyOnlinesOthersEntity->rec_registration_copy= $applyOnlinesOthersDataOrg->rec_registration_copy;
							$ApplyOnlinesOthersEntity->rec_receipt_copy 	= $applyOnlinesOthersDataOrg->rec_receipt_copy;
							$ApplyOnlinesOthersEntity->rec_power_evaluation = $applyOnlinesOthersDataOrg->rec_power_evaluation;
							$ApplyOnlinesOthersEntity->ppa_doc 				= $applyOnlinesOthersDataOrg->ppa_doc;
							$ApplyOnlinesOthersEntity->agreement_customer 	= $applyOnlinesOthersDataOrg->agreement_customer;
							$ApplyOnlinesOthersEntity->upload_undertaking 	= $applyOnlinesOthersDataOrg->upload_undertaking;
						}

						$this->ApplyOnlinesOthers->save($ApplyOnlinesOthersEntity);
						$application_no 			= $this->ApplyOnlines->GenerateApplicationNo($ApplyOnlinesEntity);
						$this->ApplyOnlines->updateAll(array('application_no'=>$application_no),array('id'=>$id));
						if(isset($this->request->data['ApplyOnlines']['common_meter']))
						{
							$this->Projects->updateAll(array('project_common_meter'=>$this->request->data['ApplyOnlines']['common_meter']),array('id'=>$project_id));
						}
						if(isset($this->request->data['renewable_attr']))
						{
							$this->Projects->updateAll(array('project_renewable_attr'=>$this->request->data['renewable_attr']),array('id'=>$project_id));
						}
						if(isset($this->request->data['renewable_rec']))
						{
							if($ApplyOnlinesOthersEntity->renewable_attr == 1)
							{
								$this->request->data['renewable_rec'] = NULL;
							}
							$this->Projects->updateAll(array('project_renewable_rec'=>$this->request->data['renewable_rec']),array('id'=>$project_id));
						}
						$this->set("str_url",encode($id).'/'.encode($project_id));
						$image_path = APPLYONLINE_PATH.$ApplyOnlinesEntity->id.'/';
						if(!file_exists(APPLYONLINE_PATH.$ApplyOnlinesEntity->id)) {
							@mkdir(APPLYONLINE_PATH.$ApplyOnlinesEntity->id, 0777);
						}
						if(!empty($this->request->data['ApplyOnlines']['file_attach_photo_scan_of_aadhar']) && !empty($this->request->data['ApplyOnlines']['file_attach_photo_scan_of_aadhar']['name'])) {
							$db_attach_photo_scan_of_aadhar = $ApplyOnlinesEntity->attach_photo_scan_of_aadhar;
							if(file_exists($image_path.$db_attach_photo_scan_of_aadhar) && !empty($db_attach_photo_scan_of_aadhar)){
								@unlink($image_path.$db_attach_photo_scan_of_aadhar);
							}
							$file_name = $this->file_upload($image_path,$this->request->data['ApplyOnlines']['file_attach_photo_scan_of_aadhar'],false,65,65,$image_path,'aadhar','attach_photo_scan_of_aadhar');
							$this->ApplyOnlines->updateAll(['attach_photo_scan_of_aadhar' => $file_name], ['id' => $ApplyOnlinesEntity->id]);
						}
						if(!empty($this->request->data['ApplyOnlines']['file_attach_recent_bill']) && !empty($this->request->data['ApplyOnlines']['file_attach_recent_bill']['name'])) {
							$db_attach_recent_bill = $ApplyOnlinesEntity->attach_recent_bill;
							if(file_exists($image_path.$db_attach_recent_bill) && !empty($db_attach_recent_bill)){
								@unlink($image_path.$db_attach_recent_bill);
							}
							$file_name = $this->file_upload($image_path,$this->request->data['ApplyOnlines']['file_attach_recent_bill'],false,65,65,$image_path,'recent','attach_recent_bill');
							$this->ApplyOnlines->updateAll(['attach_recent_bill' => $file_name], ['id' => $ApplyOnlinesEntity->id]);
						}
						if(!empty($this->request->data['ApplyOnlines']['file_attach_latest_receipt']) && !empty($this->request->data['ApplyOnlines']['file_attach_latest_receipt']['name']) ) {
							$db_attach_recent_bill = $ApplyOnlinesEntity->attach_latest_receipt;
							if(file_exists($image_path.$db_attach_recent_bill) && !empty($db_attach_recent_bill)){
								@unlink($image_path.$db_attach_recent_bill);
							}
							$file_name = $this->file_upload($image_path,$this->request->data['ApplyOnlines']['file_attach_latest_receipt'],false,65,65,$image_path,'tax_receipt_','attach_latest_receipt');
							$this->ApplyOnlines->updateAll(['attach_latest_receipt' => $file_name], ['id' => $ApplyOnlinesEntity->id]);
						}
						if(!empty($this->request->data['ApplyOnlines']['file_attach_pan_card_scan']) && !empty($this->request->data['ApplyOnlines']['file_attach_pan_card_scan']['name']) ) {
							$db_attach_pan_card_scan = $ApplyOnlinesEntity->attach_pan_card_scan;
							if(file_exists($image_path.$db_attach_pan_card_scan) && !empty($db_attach_pan_card_scan)){
								@unlink($image_path.$db_attach_pan_card_scan);
							}
							$file_name = $this->file_upload($image_path,$this->request->data['ApplyOnlines']['file_attach_pan_card_scan'],false,65,65,$image_path,'pan_','attach_pan_card_scan');
							$this->ApplyOnlines->updateAll(['attach_pan_card_scan' => $file_name], ['id' => $ApplyOnlinesEntity->id]);
						}
						if(!empty($this->request->data['file_company_incorporation']) && !empty($this->request->data['file_company_incorporation']['name'])) {
							$db_attach_incorporation = $ApplyOnlinesOthersEntity->file_company_incorporation;

							if(file_exists($image_path.$db_attach_incorporation) && !empty($db_attach_incorporation)){
								@unlink($image_path.$db_attach_incorporation);
							}
							$file_name = $this->file_upload($image_path,$this->request->data['file_company_incorporation'],false,'','','','incop_','file_company_incorporation');

							$this->ApplyOnlinesOthers->updateAll(['file_company_incorporation' => $file_name], ['application_id' => $ApplyOnlinesEntity->id]);
						}

						if(!empty($this->request->data['file_board']) && !empty($this->request->data['file_board']['name'])) {
							$db_attach_board = $ApplyOnlinesOthersEntity->file_board;
							if(file_exists($image_path.$db_attach_board) && !empty($db_attach_board)){
								@unlink($image_path.$db_attach_board);
							}
							$file_name = $this->file_upload($image_path,$this->request->data['file_board'],false,'','','','board_','file_board');
							$this->ApplyOnlinesOthers->updateAll(['file_board' => $file_name], ['application_id' => $ApplyOnlinesEntity->id]);

						}
						if(!empty($this->request->data['ApplyOnlines']['file_upload_certificate']) && !empty($this->request->data['ApplyOnlines']['file_upload_certificate']['name'])) {
							$db_attach_board = $ApplyOnlinesOthersEntity->upload_certificate;
							if(file_exists($image_path.$db_attach_board) && !empty($db_attach_board)) {
								@unlink($image_path.$db_attach_board);
							}
							$file_name = $this->file_upload($image_path,$this->request->data['ApplyOnlines']['file_upload_certificate'],false,'','','','upcert_','upload_certificate');
							$this->ApplyOnlinesOthers->updateAll(['upload_certificate' => $file_name], ['application_id' => $ApplyOnlinesEntity->id]);

						}
						if(!empty($this->request->data['ApplyOnlines']['file_gerc_certificate']) && !empty($this->request->data['ApplyOnlines']['file_gerc_certificate']['name'])) {
							$db_attach_board = $ApplyOnlinesOthersEntity->gerc_certificate;
							if(file_exists($image_path.$db_attach_board) && !empty($db_attach_board)){
								@unlink($image_path.$db_attach_board);
							}
							$file_name = $this->file_upload($image_path,$this->request->data['ApplyOnlines']['file_gerc_certificate'],false,'','','','gerc_','gerc_certificate');
							$this->ApplyOnlinesOthers->updateAll(['gerc_certificate' => $file_name], ['application_id' => $ApplyOnlinesEntity->id]);
						}
						if(!empty($this->request->data['ApplyOnlines']['file_rec_registration_copy']) && !empty($this->request->data['ApplyOnlines']['file_rec_registration_copy']['name'])) {
							$db_attach_board = $ApplyOnlinesOthersEntity->rec_registration_copy;
							if(file_exists($image_path.$db_attach_board) && !empty($db_attach_board)){
								@unlink($image_path.$db_attach_board);
							}
							$file_name = $this->file_upload($image_path,$this->request->data['ApplyOnlines']['file_rec_registration_copy'],false,'','','','rec_re_c_','rec_registration_copy');
							$this->ApplyOnlinesOthers->updateAll(['rec_registration_copy' => $file_name], ['application_id' => $ApplyOnlinesEntity->id]);
						}
						if(!empty($this->request->data['ApplyOnlines']['file_rec_receipt_copy']) && !empty($this->request->data['ApplyOnlines']['file_rec_receipt_copy']['name'])) {
							$db_attach_board = $ApplyOnlinesOthersEntity->rec_receipt_copy;
							if(file_exists($image_path.$db_attach_board) && !empty($db_attach_board)){
								@unlink($image_path.$db_attach_board);
							}
							$file_name = $this->file_upload($image_path,$this->request->data['ApplyOnlines']['file_rec_receipt_copy'],false,'','','','rec_receipt_','rec_receipt_copy');
							$this->ApplyOnlinesOthers->updateAll(['rec_receipt_copy' => $file_name], ['application_id' => $ApplyOnlinesEntity->id]);
						}
						if(!empty($this->request->data['ApplyOnlines']['file_rec_power_evaluation']) && !empty($this->request->data['ApplyOnlines']['file_rec_power_evaluation']['name'])) {
							$db_attach_board = $ApplyOnlinesOthersEntity->rec_power_evaluation;
							if(file_exists($image_path.$db_attach_board) && !empty($db_attach_board)){
								@unlink($image_path.$db_attach_board);
							}
							$file_name = $this->file_upload($image_path,$this->request->data['ApplyOnlines']['file_rec_power_evaluation'],false,'','','','rec_p_e_','rec_power_evaluation');
							$this->ApplyOnlinesOthers->updateAll(['rec_power_evaluation' => $file_name], ['application_id' => $ApplyOnlinesEntity->id]);
						}
						if(!empty($this->request->data['ApplyOnlines']['file_ppa_doc']) && !empty($this->request->data['ApplyOnlines']['file_ppa_doc']['name'])) {
							$db_attach_board = $ApplyOnlinesOthersEntity->ppa_doc;
							if(file_exists($image_path.$db_attach_board) && !empty($db_attach_board)){
								@unlink($image_path.$db_attach_board);
							}
							$file_name = $this->file_upload($image_path,$this->request->data['ApplyOnlines']['file_ppa_doc'],false,'','','','ppa_d','ppa_doc');
							$this->ApplyOnlinesOthers->updateAll(['ppa_doc' => $file_name], ['application_id' => $ApplyOnlinesEntity->id]);
						}
						if(!empty($this->request->data['ApplyOnlines']['file_agreement_customer']) && !empty($this->request->data['ApplyOnlines']['file_agreement_customer']['name'])) {
							$db_attach_board = $ApplyOnlinesOthersEntity->agreement_customer;
							if(file_exists($image_path.$db_attach_board) && !empty($db_attach_board)){
								@unlink($image_path.$db_attach_board);
							}
							$file_name = $this->file_upload($image_path,$this->request->data['ApplyOnlines']['file_agreement_customer'],false,'','','','agr_c','agreement_customer');
							$this->ApplyOnlinesOthers->updateAll(['agreement_customer' => $file_name], ['application_id' => $ApplyOnlinesEntity->id]);
						}
						if(!empty($this->request->data['ApplyOnlines']['app_upload_undertaking']) && !empty($this->request->data['ApplyOnlines']['app_upload_undertaking']['name'])) {
							$db_attach_board = $ApplyOnlinesOthersEntity->upload_undertaking;
							if(file_exists($image_path.$db_attach_board) && !empty($db_attach_board)){
								@unlink($image_path.$db_attach_board);
							}
							$file_name = $this->file_upload($image_path,$this->request->data['ApplyOnlines']['app_upload_undertaking'],false,'','','','app_u_under_','upload_undertaking');
							$this->ApplyOnlinesOthers->updateAll(['upload_undertaking' => $file_name], ['application_id' => $ApplyOnlinesEntity->id]);
						}
						if($tab =='tab_3' && isset($this->request->data['save_submit']))
						{
							if(empty($ApplyOnlinesEntity->payment_status))
							{
								$this->ApplyOnlines->updateAll(['payment_status' => '0'], ['id' => $ApplyOnlinesEntity->id]);
							}
							$approval=$this->ApplyOnlineApprovals->Approvalstage($ApplyOnlinesEntity->id);
							if($ApplyOnlinesEntity->application_status!=$this->ApplyOnlineApprovals->WAITING_LIST && !in_array(29,$approval))
							{
								$sms_mobile 	= $ApplyOnlinesEntity->installer_mobile;
								if($is_installer==true)
								{
									$sms_mobile = $ApplyOnlinesEntity->consumer_mobile;
									/*if(isset($ApplyOnlinesOthersEntity->map_installer_id) && !empty($ApplyOnlinesOthersEntity->map_installer_id))
									{
										$dev_fetchData 		= $this->Developers->find("all",['conditions'=>['id'=>$ApplyOnlinesOthersEntity->map_installer_id]])->first();
										if(!empty($dev_fetchData))
										{
											$sms_mobile		= $dev_fetchData->mobile;
										}
									}*/
								}
								$sms_message = str_replace('##application_no##',              $ApplyOnlinesEntity->application_no, OTP_VERIFICATION);
								$this->ApplyOnlines->SendSMSActivationCode($ApplyOnlinesEntity->id,$sms_mobile,$sms_message,'OTP_VERIFICATION');
								if($ApplyOnlinesEntity->govt_agency == 1 && GOVERMENT_AGENCY==1) 
								{
									$sms_mobile 	= $ApplyOnlinesEntity->installer_mobile;
									$this->ApplyOnlines->SendSMSActivationCode($ApplyOnlinesEntity->id,$sms_mobile,$sms_message,'OTP_VERIFICATION');
								}
							}
							$this->Flash->set('Your Application send successful.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
							return $this->redirect('apply-online-list');
						} else if($tab =='tab_2') {
							$this->save_other_doc($this->request->data['ApplyOnlines'],$ApplyOnlinesEntity->id);
							$this->CreateMyProject($ApplyOnlinesEntity->id,true,false);
						} else if($tab =='tab_3') {
							return $this->redirect('apply-onlines/'.encode($ApplyOnlinesEntity->id));
						}
					}
				}
			} else if(isset($this->request->data['previous_2']) && !empty($this->request->data['previous_2'])) {
				$tab = '';
			} else if(isset($this->request->data['previous_3']) && !empty($this->request->data['previous_3'])) {
				$tab = 'tab_1';
			}
			if(!empty($id)){
				$ApplyonlinDocsList = $this->ApplyonlinDocs->find('all',['conditions'=>['application_id'=>$id,'doc_type'=>'others']])->toArray();
				$Applyonlinprofile  = $this->ApplyonlinDocs->find('all',['conditions'=>['application_id'=>$id,'doc_type'=>'profile']])->first();
			}
				if(!empty($this->Session->read('Members.member_type')))
			{
				$state 			 = $this->Session->read('Members.state');
				}
			else
			{
				$state 			 = $this->Session->read('Customers.state');
			}
			if(isset($ApplyOnlinesEntity)){
				if(!empty($ApplyOnlinesEntity->apply_state)) {
					$state = $ApplyOnlinesEntity->apply_state;
				}else{
					$ApplyOnlinesEntity->apply_state = $state;
					if(!empty($project_data))
					{
						if($project_data->state != '')
						{
							$state_list 					= $this->States->find("all",['conditions'=>['statename'=>$project_data->state]])->first();
							$ApplyOnlinesEntity->apply_state=  $state_list->id;
							$state 							= $ApplyOnlinesEntity->apply_state;
						}
					}
			}
				if(isset($ApplyOnlinesOthersEntity->tariff)) {
					$ApplyOnlinesEntity->tariff 			= $ApplyOnlinesOthersEntity->tariff;
				}
				if(isset($ApplyOnlinesOthersEntity->pv_dc_capacity) && empty($ApplyOnlinesEntity->errors())) {
					$ApplyOnlinesEntity->pv_dc_capacity 			= $ApplyOnlinesOthersEntity->pv_dc_capacity;
				}
				if(isset($ApplyOnlinesOthersEntity->msme)) {
					$ApplyOnlinesEntity->msme 						= $ApplyOnlinesOthersEntity->msme;
				}
				if(!empty($ApplyOnlinesOthersEntity->msme_category)) {
					$ApplyOnlinesEntity->msme_category 				= $ApplyOnlinesOthersEntity->msme_category;
				}
				if(isset($ApplyOnlinesOthersEntity->contract_load_more)) {
					$ApplyOnlinesEntity->contract_load_more 		= (isset($ApplyOnlinesEntity->msme) && $ApplyOnlinesEntity->msme==1) ? $ApplyOnlinesOthersEntity->contract_load_more : 0;
				}
				if(!empty($ApplyOnlinesOthersEntity->type_of_applicant)) {
					$ApplyOnlinesEntity->type_of_applicant 				= $ApplyOnlinesOthersEntity->type_of_applicant;
				}
				if(!empty($ApplyOnlinesOthersEntity->applicant_others)) {
					$ApplyOnlinesEntity->applicant_others 			= $ApplyOnlinesOthersEntity->applicant_others;
				}
				if(!empty($ApplyOnlinesOthersEntity->msme_aadhaar_no)) {
					$ApplyOnlinesEntity->msme_aadhaar_no 			= $ApplyOnlinesOthersEntity->msme_aadhaar_no;
				}
				if(!empty($ApplyOnlinesOthersEntity->type_authority)) {
					$ApplyOnlinesEntity->type_authority 			= $ApplyOnlinesOthersEntity->type_authority;
				}
				if(!empty($ApplyOnlinesOthersEntity->name_authority)) {
					$ApplyOnlinesEntity->name_authority 			= $ApplyOnlinesOthersEntity->name_authority;
				}
				if(isset($ApplyOnlinesOthersEntity->map_installer_id) &&  empty($ApplyOnlinesEntity->errors())) {
					$ApplyOnlinesEntity->map_installer_id 			= ($ApplyOnlinesOthersEntity->map_installer_id);
				}
				$arrErrors=$ApplyOnlinesEntity->errors();
				foreach($arrFieldsMap as $Fkey=>$Fval) {
					if(isset($ApplyOnlinesOthersEntity->$Fval) && !isset($arrErrors[$Fval])) {
						$ApplyOnlinesEntity->$Fval 					= $ApplyOnlinesOthersEntity->$Fval;
					}
				}
				if(!empty($ApplyOnlinesEntity->aadhar_no_or_pan_card_no)) {
					$ApplyOnlinesEntity->aadhar_no_or_pan_card_no 	= passdecrypt($ApplyOnlinesEntity->aadhar_no_or_pan_card_no);
				}
				if(!empty($ApplyOnlinesEntity->pan_card_no)) {
					$ApplyOnlinesEntity->pan_card_no 				= passdecrypt($ApplyOnlinesEntity->pan_card_no);
				}
				if(!empty($ApplyOnlinesEntity->house_tax_holding_no)) {
					$ApplyOnlinesEntity->house_tax_holding_no 		= passdecrypt($ApplyOnlinesEntity->house_tax_holding_no);
				}
				if(!empty($project_data) && $project_data->customer_type !='' && $project_data->customer_type !='NULL' && empty($ApplyOnlinesEntity->category) && empty($ApplyOnlinesEntity->errors()))
				{
					$ApplyOnlinesEntity->category = $project_data->customer_type;
				}
				if(empty($ApplyOnlinesEntity->social_consumer) && !empty($project_data) && empty($ApplyOnlinesEntity->errors()))
				{
					$ApplyOnlinesEntity->social_consumer = $project_data->project_social_consumer;
				}
				if(empty($ApplyOnlinesEntity->common_meter) && !empty($project_data))
				{
					$ApplyOnlinesEntity->common_meter 	= $project_data->project_common_meter;
				}
				if(empty($ApplyOnlinesEntity->disclaimer_subsidy) && !empty($project_data))
				{
					$ApplyOnlinesEntity->disclaimer_subsidy = $project_data->project_disclaimer_subsidy;
				}
				if(empty($ApplyOnlinesEntity->renewable_attr) && !empty($project_data))
				{
					$ApplyOnlinesEntity->renewable_attr = $project_data->project_renewable_attr;
				}
				if(empty($ApplyOnlinesEntity->renewable_rec) && !empty($project_data))
				{
					if($project_data->project_renewable_attr == 1)
					{
						$project_data->project_renewable_rec = NULL;
					}
					$ApplyOnlinesEntity->renewable_rec 	= $project_data->project_renewable_rec;
				}
				if(!empty($project_data) && $project_data->address !='' && $ApplyOnlinesEntity->address1=='')
				{
					$ApplyOnlinesEntity->address1 = $project_data->address;
				}
				if(!empty($project_data) && $project_data->city !='' && $ApplyOnlinesEntity->city=='')
				{
					$ApplyOnlinesEntity->city = $project_data->city;
				}
				if(!empty($project_data) && $project_data->state !='' && $ApplyOnlinesEntity->state=='')
				{
					$ApplyOnlinesEntity->state 	= $project_data->state;
				}
				if(!empty($project_data) && $project_data->pincode !='' && $ApplyOnlinesEntity->pincode=='')
				{
					$ApplyOnlinesEntity->pincode = $project_data->pincode;
				}
				$arr_customer_details 		= $this->$customerTable->find('all',array('conditions'=>array('id'=>$customerId)))->first();
				if(($ApplyOnlinesEntity->apply_state!='4' && strtolower($ApplyOnlinesEntity->apply_state)!='gujarat')) {
					if($ApplyOnlinesEntity->mobile == '')
					{
						$ApplyOnlinesEntity->mobile = $arr_customer_details->mobile;
					}
					if($ApplyOnlinesEntity->email == '')
					{
						$ApplyOnlinesEntity->email = $arr_customer_details->email;
					}
				}

				if($ApplyOnlinesEntity->landline_no == '' && !empty($arr_customer_details->landline))
				{
					$ApplyOnlinesEntity->landline_no = $arr_customer_details->landline;
				}
				if(!empty($project_data) && $project_data->area !='' && empty($ApplyOnlinesEntity->roof_of_proposed))
				{
					$ApplyOnlinesEntity->roof_of_proposed = $project_data->area;
				}
				if(!empty($project_data) && $project_data->avg_monthly_bill !='' && empty($ApplyOnlinesEntity->bill))
				{
					$ApplyOnlinesEntity->bill = $project_data->avg_monthly_bill;
				}
				if(!empty($project_data) && $project_data->estimated_kwh_year !='' && empty($ApplyOnlinesEntity->energy_con))
				{
					$ApplyOnlinesEntity->energy_con = $project_data->estimated_kwh_year;
				}
				$customer_details 	= $this->$customerTable->find('all',array('conditions'=>array('id'=>$this->Session->read('Customers.id'))))->first();
				$installer_details 	= $this->$installerTable->find('all',array('conditions'=>array('id'=>$ApplyOnlinesEntity->installer_id)))->first();
				$assign_slots           = array();
				if(isset($customer_details->installer_id) && !empty($customer_details->installer_id))
				{
					$arr_condition      = array("installer_id" => $customer_details->installer_id);
					$InstallerList      = TableRegistry::get('InstallerCategoryMapping');
					$arr_result         = $InstallerList->find('all',array('conditions'=>$arr_condition))->first();
					$installer_category = isset($arr_result->category_id) ? $arr_result->category_id : 0;
					if($installer_category == '2' && $create_project ==1 && empty($project_errors)){
						//return $this->redirect('apply-online-list');
						$quota_first_page = $this->Projects->checked_total_capacity_installer($customer_details->installer_id);
						if($quota_first_page!==true)
						{
							return $this->redirect('apply-online-list');
						}
					}
					if(!empty($arr_result))
					{
						$assign_slots 	= $this->ApplyOnlines->assign_slot_array($arr_result['allowed_bands']);
					}
				}
				if(($ApplyOnlinesEntity->apply_state=='4' || strtolower($ApplyOnlinesEntity->apply_state)=='gujarat') && $is_installer==false)
				{
					if($ApplyOnlinesEntity->consumer_email=='' )
					{
						$ApplyOnlinesEntity->consumer_email 	= $this->Session->read('Customers.email');
					}
					if($ApplyOnlinesEntity->consumer_mobile=='' )
					{
						$ApplyOnlinesEntity->consumer_mobile 	= $customer_details->mobile;
					}
					if($ApplyOnlinesEntity->installer_email=='' && !empty($installer_details))
					{
						$ApplyOnlinesEntity->installer_email 	= $installer_details->email;
						$this->request->data['ApplyOnlines']['installer_email']= $installer_details->email;
					}
					if($ApplyOnlinesEntity->installer_mobile==''  && !empty($installer_details))
					{
						$ApplyOnlinesEntity->installer_mobile 	= $installer_details->mobile;
						$this->request->data['ApplyOnlines']['installer_mobile'] 	= $installer_details->mobile;
					}
				}
				elseif($ApplyOnlinesEntity->apply_state=='4' && $is_installer==true && !isset($this->request->data['tab_2']))
				{
					$installer_details 	= $this->$installerTable->find('all',array('conditions'=>array('id'=>$customer_details->installer_id)))->first();
					if($ApplyOnlinesEntity->installer_email=='')
					{
						$ApplyOnlinesEntity->installer_email 	= $installer_details->email;
					}
					if($ApplyOnlinesEntity->installer_mobile=='')
					{
						$ApplyOnlinesEntity->installer_mobile 	= $installer_details->mobile;
					}

				}
			}
			$installers_list = array();

			if(isset($state) && !empty($state)) {
				$state_list  = $this->States->find('all',array(
														'conditions'=>array('id'=>$state)))->first();
				if($project_id>0)
				{
					$installers_list = $this->Installers->find('list',['keyField'=>'id','valueField'=>'installer_name','join'=>[['table'=>'installer_projects','type'=>'inner','conditions'=>'installer_projects.installer_id = Installers.id']],'conditions'=>['installer_projects.project_id'=>$project_id,'Installers.stateflg'=>$this->ApplyOnlines->gujarat_st_id]])->toArray();
				}
				if(empty($installers_list))
				{
					$installers_list = $this->Installers->find('list',['keyField'=>'id','valueField'=>'installer_name','join'=>[['table'=>'states','type'=>'inner','conditions'=>'states.id = Installers.stateflg']],'conditions'=>['Installers.stateflg'=>$this->ApplyOnlines->gujarat_st_id]])->toArray();
				}
			} else {
				$installers_list = $this->Installers->find('list',['keyField'=>'id','valueField'=>'installer_name'])->toArray();
			}

			$state_list	 		= $this->States->find("list",['keyField'=>'id','valueField'=>'statename','conditions'=>$condition_state_list,'order'=>['statename'=>'ASC']]);
			$discom_list 		= $this->DiscomMaster->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['DiscomMaster.state_id'=>$state,'DiscomMaster.type'=>3,'status'=>'1']]);

			$discom_arr = array();
			$discoms 	= $this->BranchMasters->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['BranchMasters.status'=>'1','BranchMasters.parent_id'=>'0','BranchMasters.state'=>$state]])->toArray();
			if(!empty($discoms)) {
				foreach($discoms as $id=>$title) {
					$discom_arr[$id] = $title;
				}
			}
			$applyonlineapproval=$this->ApplyOnlineApprovals->find('all',array('conditions'=>array('application_id'=>$ApplyOnlinesEntity->id,'stage'=>'1')))->first();
			$enabled_fields =array();
			$enabled_fields=['next','installer_id','add1','add2','district','city','state','pincode','save_submit'];
			if(isset($ApplyOnlinesEntity->id) && $ApplyOnlinesEntity->id != "" && !empty($applyonlineapproval) && (!empty($this->Session->read('Members.member_type'))) && ($this->Session->read('Members.member_type') == $this->ApplyOnlines->JREDA)){
				//$this->set("enabled_fields",$enabled_fields);
			}
			if($ApplyOnlinesEntity->errors())
			{
				$application_id_error = '0';
				if(!empty($ApplyOnlinesEntity->id))
				{
					$application_id_error 		= $ApplyOnlinesEntity->id;
					$applyOnlinesOthersDataOrg 	= $this->ApplyOnlinesOthers->find('all',array('conditions'=>array('application_id'=>$ApplyOnlinesEntity->id)))->first();
					if(!empty($applyOnlinesOthersDataOrg))
					{
						$ApplyOnlinesOthersEntity->file_company_incorporation 	= $applyOnlinesOthersDataOrg->file_company_incorporation;
						$ApplyOnlinesOthersEntity->file_board 					= $applyOnlinesOthersDataOrg->file_board;
						$ApplyOnlinesOthersEntity->upload_certificate 			= $applyOnlinesOthersDataOrg->upload_certificate;
					}
					$this->ApplyOnlines->saveErrorLog($application_id_error,$ApplyOnlinesEntity->errors());
				}
			}

			$output_quota = true;
			if($create_project ==1){
				//$output_quota = $this->ApplyOnlines->checked_total_capacity($installer_id);
			}


			$applyonlineapproval 	= $this->ApplyOnlineApprovals->find('all',array('conditions'=>array('application_id'=>$ApplyOnlinesEntity->id,'stage'=>'1')))->first();
			$type_modules           = $this->Installation->TYPE_MODULES ;
			$type_inverters         = $this->Installation->TYPE_INVERTERS ;
			$make_inverters         = $this->Installation->MAKE_INVERTERS ;
			$customer_state 		= $this->Session->read('Customers.state');
			
			$developer_list 	= $this->DeveloperCustomers->find("all",array("fields"=>["id","title"=>"CONCAT(name, ' ', developer_registration_no)"],'conditions'=>array('status'=>1)))->toArray();
			$arrDeveloper 			= array();
			if(!empty($developer_list)) {
				foreach($developer_list as $developer) {
					$arrDeveloper[encode($developer->id)]	= $developer->title;
				}
			}

			if($ses_login_type == 'developer' || (isset($app_oth_details->map_installer_id) && !empty($app_oth_details->map_installer_id))) {
				$installers_list = $this->Installers->find('list',['keyField'=>'id','valueField'=>'installer_name','conditions'=>array('status'=>1)])->toArray();
			}

			$this->set("customer_state",$customer_state);
			$this->set('discom_arr',$discom_arr);
			$this->set('ApplyonlinDocsList',$ApplyonlinDocsList);
			$this->set('customers_name',$this->Session->read('Customers.name'));
			$this->set('state_list',$state_list);
			$this->set('installer_list',$installers_list);
			$this->set('discom_list',$discom_list);
			$this->set('customer_name_prifix',$this->Customers->customer_name_prifix);
			$this->set('customer_type_list',$this->Parameters->GetParameterList(3));
			$this->set('ApplyOnlines',$ApplyOnlinesEntity);
			$this->set('pageTitle','Apply Online');
			$this->set('ApplyOnlineErrors',$ApplyOnlinesEntity->errors());
			$this->set("pv_ca_gt50",Configure::read('PV_CAPACITY_GT50'));
			$this->set("pv_ca_lt50",Configure::read('PV_CAPACITY_LT50'));
			$this->set('project_id',$project_id);
			$this->set("type_modules",$type_modules);
			$this->set("type_inverters",$type_inverters);
			$this->set("make_inverters",$make_inverters);
			$this->set("execution_data",$execution_data);
			$this->set("uplaod_image_limit",(Configure::read('UPLOAD_IMAGE_LIMIT')*100));
			$this->set("tab",$tab);
			$this->set("id",$id);
			$this->set("SITE_KEY",Configure::read('SITE_KEY') );
			$this->set('Applyonlinprofile',$Applyonlinprofile);
			$this->set("amt_government",Configure::read('APPLY_AMOUNT_GOVERNMENT'));
			$this->set("amt_non_government",Configure::read('APPLY_AMOUNT_NON_GOVERNMENT'));
			$this->set("amt_residental",Configure::read('APPLY_AMOUNT_RESIDENTIAL'));
			$this->set("amt_gov_tax",Configure::read('APPLY_AMOUNT_GOV_TAX'));
			$this->set("amt_non_gov_tax",Configure::read('APPLY_AMOUNT_NON_GOV_TAX'));
			$this->set("amt_tax_percent",Configure::read('APPLY_TAX_PERCENT'));
			$this->set("applyonlineapproval",$applyonlineapproval);
			//$this->set('projectTypeArr',$this->Parameters->getProjectType());
			$this->set('projectTypeArr',$this->Parameters->GetParameterList(3));
			$this->set('backupTypeArr',$this->Projects->backupTypeArr);
			$this->set('areaTypeArr',$this->Parameters->getAreaType());
			$this->set('create_project',$create_project);
			$this->set('assign_slots',$assign_slots);
			$this->set('ApplyOnlineObj',$this->ApplyOnlines);
			$this->set('quota_msg_disp',$output_quota);
			$this->set('MStatus',$this->ApplyOnlineApprovals);
			$this->set('allocatedCategory',$allocatedCategory);
			$this->set('ProjectsDetails',$project_data);
			$this->set('ApplyOnlinesOthers',$ApplyOnlinesOthersEntity);
			$this->set('Couchdb',$this->Couchdb);
			$this->set('is_installer',$is_installer);
			$this->set('developer_list',$arrDeveloper);
			$this->set('ses_login_type',$ses_login_type);
		} else {
			return $this->redirect('home');
		}
	}


	public function captchaValidation(){
		if(CAPTCHA_DISPLAY == 1) {
			if($this->request->data['g-recaptcha-response'] == ""){
				return 0;
			}else {
					$secret = Configure::read('SECRET_KEY');
					$verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$this->request->data['g-recaptcha-response']}");
					$captcha_success = json_decode($verify);
					if ($captcha_success->success == false) {
						$this->Flash->error('Not Validated Captcha');
						return 0;
					} else if ($captcha_success->success == true) {
						return 1;
					}

			}
		}
		return 1;
	}

	public function instoler_list_by_state_id()
	{
		$this->autoRender = false;
		$state 		= $this->request->data['state'];
		$project_id = $this->request->data['project_id'];
		$state_list = $this->States->find('all',array(
							  'conditions'=>array('id'=>$state)))->first();
		$installers_list = $this->Installers->find('all',['fields'=>['id','installer_name'],'join'=>[['table'=>'installer_projects','type'=>'inner','conditions'=>'installer_projects.installer_id = Installers.id']],'conditions'=>['installer_projects.project_id'=>$project_id,'Installers.state'=>$state_list->statename]])->toArray();
		if(empty($installers_list))
		{
			$installers_list = $this->Installers->find('all',['fields'=>['id','installer_name'],'join'=>[['table'=>'states','type'=>'inner','conditions'=>'states.statename = Installers.state']],'conditions'=>['Installers.state'=>$state_list->statename]])->toArray();
		}

		//$installers_list = $this->Installers->find('all',['fields'=>['id','installer_name'],'join'=>[['table'=>'states','type'=>'inner','conditions'=>'states.statename = Installers.state']],'conditions'=>['Installers.stateflg'=>$state]])->toArray();
		$this->ApiToken->SetAPIResponse('msg', 'list of installers');
		$this->ApiToken->SetAPIResponse('data', $installers_list);
		$this->ApiToken->SetAPIResponse('type','ok');
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/*
	 * Displays a index
	 *
	 * @param mixed What page to display
	 * @return void
	 */
	public function save() {
		$this->autoRender = false;
		$customerId = $this->ApiToken->customer_id;
		if(isset($customerId) && !empty($customerId)) {
			$this->layout = 'frontend';
			$ApplyOnlinesEntity	= $this->ApplyOnlines->newEntity($this->request->data,['validate'=>'add']);
			if(isset($this->request->data) && !empty($this->request->data)) {
				if(!$ApplyOnlinesEntity->errors()) {
					$ApplyOnlinesEntity->customer_id 	= $customerId;
					$ApplyOnlinesEntity->created 		= $this->now();
					$ApplyOnlinesEntity->member_assign_id 	= $ApplyOnlinesEntity->discom_name;
					$ApplyOnlinesEntity->application_status = $this->ApplyOnlineApprovals->APPLICATION_SUBMITTED;

					if($this->ApplyOnlines->save($ApplyOnlinesEntity)){

						//$this->ApplyOnlineApprovals->saveStatus($ApplyOnlinesEntity->id,$this->ApplyOnlineApprovals->APPLICATION_SUBMITTED,$ApplyOnlinesEntity->discom_name);


						$message = 'Your Application send success full.';
						$status = 'ok';
						$this->ApiToken->SetAPIResponse('application_id',$ApplyOnlinesEntity->id);
					}
				} else if($this->ismobile() || (isset($this->request->data['mobile_type']) && $this->request->data['mobile_type'] == 1)) {
					$message = 'Some field are required';
					$status = 'error';
				}
				}
			} else {
			$message = 'customer id not found';
				$status = 'error';
			}
			$this->ApiToken->SetAPIResponse('msg', $message);
			$this->ApiToken->SetAPIResponse('type', $status);
			echo $this->ApiToken->GenerateAPIResponse();
			exit;
		}
	/*
	 * API for save document of applayonline from
	 *
	 * @param mixed What page to display
	 * @return void
	 */
	public function uploadAplicationDoc() {
		if(!empty($this->request->data) && isset($this->request->data['id']) && !empty($this->request->data['id'])) {
				$ApplyOnlineGet = $this->ApplyOnlines->get($this->request->data['id']);
				$ApplyOnlinesEntity = $this->ApplyOnlines->patchEntity($ApplyOnlineGet,$this->request->data);

			$image_path = APPLYONLINE_PATH.$ApplyOnlinesEntity->id.'/';

			if(!file_exists(APPLYONLINE_PATH.$ApplyOnlinesEntity->id)){
				@mkdir(APPLYONLINE_PATH.$ApplyOnlinesEntity->id, 0777,true);
			}

			if(!empty($this->request->data['file_attach_photo_scan_of_aadhar'])) {
				$db_attach_photo_scan_of_aadhar = $ApplyOnlinesEntity->attach_photo_scan_of_aadhar;
				if(file_exists($image_path.$db_attach_photo_scan_of_aadhar)){
					@unlink($image_path.$db_attach_photo_scan_of_aadhar);
					@unlink($image_path.'r_'.$db_attach_photo_scan_of_aadhar);
				}
				$file_name = $this->file_upload($image_path,$this->request->data['file_attach_photo_scan_of_aadhar'],true,65,65,$image_path,'aadhar');
				$this->ApplyOnlines->updateAll(['attach_photo_scan_of_aadhar' => $file_name], ['id' => $ApplyOnlinesEntity->id]);
			}
			if(!empty($this->request->data['file_attach_recent_bill'])) {
				$db_attach_recent_bill = $ApplyOnlinesEntity->attach_recent_bill;
				if(file_exists($image_path.$db_attach_recent_bill)){
					@unlink($image_path.$db_attach_recent_bill);
					@unlink($image_path.'r_'.$db_attach_recent_bill);
				}
				$file_name = $this->file_upload($image_path,$this->request->data['file_attach_recent_bill'],true,65,65,$image_path,'recent');

				$this->ApplyOnlines->updateAll(['attach_recent_bill' => $file_name], ['id' => $ApplyOnlinesEntity->id]);
			}
			if(!empty($this->request->data['file_attach_latest_receipt'])) {
				$db_attach_recent_bill = $ApplyOnlinesEntity->attach_latest_receipt;
				if(file_exists($image_path.$db_attach_recent_bill)){
					@unlink($image_path.$db_attach_recent_bill);
					@unlink($image_path.'r_'.$db_attach_recent_bill);
				}
				$file_name = $this->file_upload($image_path,$this->request->data['file_attach_latest_receipt'],true,65,65,$image_path,'tax_receipt_');

				$this->ApplyOnlines->updateAll(['attach_latest_receipt' => $file_name], ['id' => $ApplyOnlinesEntity->id]);
			}
			if(!empty($this->request->data['file_attach_pan_card_scan']) && !empty($this->request->data['file_attach_pan_card_scan']['name']) ) {
				$db_attach_pan_card_scan = $ApplyOnlinesEntity->attach_pan_card_scan;
				if(file_exists($image_path.$db_attach_pan_card_scan)){
					@unlink($image_path.$db_attach_pan_card_scan);
					@unlink($image_path.'r_'.$db_attach_pan_card_scan);
				}
				$file_name = $this->file_upload($image_path,$this->request->data['file_attach_pan_card_scan'],true,65,65,$image_path,'pan_');

				$this->ApplyOnlines->updateAll(['attach_pan_card_scan' => $file_name], ['id' => $ApplyOnlinesEntity->id]);
			}


			$message = 'Application document upload';
			$status = 'ok';
			$this->ApiToken->SetAPIResponse('msg', $message);
			$this->ApiToken->SetAPIResponse('type', $status);
			echo $this->ApiToken->GenerateAPIResponse();
			exit;
		} else {

			$message = 'Error in application online document upload';
			$status = 'error';
			$this->ApiToken->SetAPIResponse('msg', $message);
			$this->ApiToken->SetAPIResponse('type', $status);
			echo $this->ApiToken->GenerateAPIResponse();
			exit;
		}
	}

	/*
	 * API for get getBillCategory of applayonline from
	 *
	 * @param mixed What page to display
	 * @return void
	*/
	public function getBillCategory(){
		$BillCategoryList = $this->Parameters->GetParameterList(3);
		$var_data = array();
		$branchmaster_list = array();

		if(isset($this->request->data['state']) && !empty($this->request->data['state'])) {
			$branchmaster_list = $this->DiscomMaster->find("all",['fields'=>['id','title'],'conditions'=>['DiscomMaster.state_id'=>$this->request->data['state'],'DiscomMaster.type'=>3,'status'=>'1']])->toArray();
		}
		if(!empty($BillCategoryList)) {
			foreach($BillCategoryList as $k=>$v){
				$var_data[] = ['id'=>$k,'name'=>$v];
			}
			$status = 'ok';
			$message = 'Bill Category List';
		}
		if(empty($branchmaster_list)) {
			$branchmaster_list = array();
		}
		$discom_arr = array();
		$discoms 	= $this->BranchMasters->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['BranchMasters.status'=>'1','BranchMasters.parent_id'=>'0','BranchMasters.state'=>$this->request->data['state']]])->toArray();
		if(empty($discoms)) {
			$discom_arr = array("id"=>0,"title"=>"--NO DISCOM--");
		} else {
			foreach($discoms as $id=>$title) {
				$discom_arr[] = array("id"=>$id,"title"=>$title);
			}
		}
		$this->ApiToken->SetAPIResponse('data', array('bill_category'=>$var_data,'discom_list'=>$branchmaster_list,'discoms'=>$discom_arr));
		$this->ApiToken->SetAPIResponse('msg', $message);
		$this->ApiToken->SetAPIResponse('type', $status);
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	public function getBillCategory_old(){
		$BillCategoryList = $this->Parameters->GetParameterList(3);
		$var_data = array();
		foreach($BillCategoryList as $k=>$v){
			$var_data[] = ['id'=>$k,'name'=>$v];
		}
		if(!empty($BillCategoryList)){
			$status = 'ok';
			$message = 'Bill Category List';
			$this->ApiToken->SetAPIResponse('data', $var_data);
		}else{
			$message = 'No Bill Category List found';
			$this->ApiToken->SetAPIResponse('data', array());
		}
		$this->ApiToken->SetAPIResponse('msg', $message);
		$this->ApiToken->SetAPIResponse('type', $status);
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	public function applyonline_list($page = '1')
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
		$TotalPvCapacity= 0;
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
			$member_type = $this->Session->read("Members.member_type");
		}

		$main_branch_id = array();
		if (!empty($member_id) && $member_type == $this->ApplyOnlines->DISCOM)
		{
			$field      = "area";
			$id         = $area;

			if (!empty($section)) {
				$field      = "section";
				$id         = $section;
			} else if (!empty($subdivision)) {
				$field      = "subdivision";
				$id         = $subdivision;
			} else if (!empty($division)) {
				$field      = "division";
				$id         = $division;
			} else if (!empty($circle)) {
				$field      = "circle";
				$id         = $circle;
			}
			$main_branch_id = array("field"=>$field,"id"=>$id);
		}
		if(isset($this->request->data['Reset']) && !empty($this->request->data['Reset'])){
			$this->Session->delete("Customers.Search");
			$this->Session->delete("MembersSearch");
			$this->Session->delete("Customers.Page");
			return $this->redirect(URL_HTTP.'/apply-online-list');
		}
		$this->Session->write('Customers.Page',$page);

		$this->removeExtraTags();

		if(isset($this->request->data['Search']) && !empty($this->request->data['Search'])){
			$this->Session->write("MembersSearch",$this->request->data);
			$this->Session->write('Customers.Search',serialize($this->request->data));
		} else {
			if($this->Session->check("MembersSearch")) {
				$this->request->data = $this->Session->read("MembersSearch");
			}
			if($this->Session->check("Customers.Search"))
			{
				$this->request->data = unserialize($this->Session->read("Customers.Search"));
			}
		}
		$consumer_no 			= isset($this->request->data['consumer_no']) ? $this->request->data['consumer_no'] : '';
		$application_search_no 	= isset($this->request->data['application_search_no']) ? $this->request->data['application_search_no'] : '';
		$installer_name 		= (isset($this->request->data['installer_name'])) ? $this->request->data['installer_name'] : '';
		$discom_name 			= isset($this->request->data['discom_name']) ? $this->request->data['discom_name'] : '';
		$payment_status 		= isset($this->request->data['payment_status']) ? $this->request->data['payment_status'] : '';
		$order_by_form 			= isset($this->request->data['order_by_form']) ? $this->request->data['order_by_form'] : 'ApplyOnlines.modified|DESC';
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
		$arrExtra['ses_login_type'] 	= $this->Session->read('Customers.login_type');
		//echo $customer_id;exit;
		$installer_id 			= '';
		if(!empty($customer_id)) {
			$this->set("pageTitle","My Apply-online List");
			$this->layout = 'frontend';
			if($login_type == 'developer') {
				$customer_details 	= $this->DeveloperCustomers->find('all',array('conditions'=>array('id'=>$customer_id)))->first();
				$installer_id 		= $customer_details['installer_id'];
			}  elseif($cust_type == 'installer') {
				$customer_details 	= $this->Customers->find('all',array('conditions'=>array('id'=>$customer_id)))->first();
				$installer_id 		= $customer_details['installer_id'];
			}
			if(isset($this->request->data['from_date']) && isset($this->request->data['to_date']) && !empty($this->request->data['from_date']) && !empty($this->request->data['to_date'])) {
				$ApplyOnlinesList 	= $this->ApplyOnlines->getDataapplyonline($customer_id,'',$state,$this->request->data['from_date'],$this->request->data['to_date'],'',$this->request->data['status'],$installer_id,$consumer_no,$application_search_no,$installer_name,$discom_name,$payment_status,$order_by_form,$disclaimer_subsidy,$pcr_code,$msme,$msmeonly,$category,$inspection_status,$geda_letter_status,$geda_approved_status,$receipt_no,$is_enhancement,$arrExtra);
			} else {
				if(!empty($this->request->data['status'])){
					$ApplyOnlinesList 	= $this->ApplyOnlines->getDataapplyonline($customer_id,'',$state,'','','',$this->request->data['status'],$installer_id,$consumer_no,$application_search_no,$installer_name,$discom_name,$payment_status,$order_by_form,$disclaimer_subsidy,$pcr_code,$msme,$msmeonly,$category,$inspection_status,$geda_letter_status,$geda_approved_status,$receipt_no,$is_enhancement,$arrExtra);
				} else {
					$ApplyOnlinesList 	= $this->ApplyOnlines->getDataapplyonline($customer_id,'',$state,'','','','',$installer_id,$consumer_no,$application_search_no,$installer_name,$discom_name,$payment_status,$order_by_form,$disclaimer_subsidy,$pcr_code,$msme,$msmeonly,$category,$inspection_status,$geda_letter_status,$geda_approved_status,$receipt_no,$is_enhancement,$arrExtra);
				}
			}
			$this->set('is_member',false);
		} else if(!empty($member_id)) {
			$this->set("pageTitle","Apply-online List");
			$this->layout 		= 'frontend';
			if(isset($this->request->data['from_date']) && isset($this->request->data['to_date']) && !empty($this->request->data['from_date']) && !empty($this->request->data['to_date'])) {
				if($member_type == $this->ApplyOnlines->DISCOM) {
					$ApplyOnlinesList 	= $this->ApplyOnlines->getDataapplyonline('',$member_id,$state,$this->request->data['from_date'],$this->request->data['to_date'],$main_branch_id,$this->request->data['status'],'',$consumer_no,$application_search_no,$installer_name,$discom_name,$payment_status,$order_by_form,$disclaimer_subsidy,$pcr_code,$msme,$msmeonly,$category,$inspection_status,$geda_letter_status,$geda_approved_status,$receipt_no,$is_enhancement,$arrExtra);
				} else {
					$ApplyOnlinesList 	= $this->ApplyOnlines->getDataapplyonline('',$member_id,$state,$this->request->data['from_date'],$this->request->data['to_date'],'',$this->request->data['status'],'',$consumer_no,$application_search_no,$installer_name,$discom_name,$payment_status,$order_by_form,$disclaimer_subsidy,$pcr_code,$msme,$msmeonly,$category,$inspection_status,$geda_letter_status,$geda_approved_status,$receipt_no,$is_enhancement,$arrExtra);
				}
			} else {
				if($member_type == $this->ApplyOnlines->DISCOM) {
					if(isset($this->request->data['status']) && !empty($this->request->data['status'])){
						$ApplyOnlinesList 	= $this->ApplyOnlines->getDataapplyonline('',$member_id,$state,'','',$main_branch_id,$this->request->data['status'],'',$consumer_no,$application_search_no,$installer_name,$discom_name,$payment_status,$order_by_form,$disclaimer_subsidy,$pcr_code,$msme,$msmeonly,$category,$inspection_status,$geda_letter_status,$geda_approved_status,$receipt_no,$is_enhancement,$arrExtra);
					} else {
					$ApplyOnlinesList 	= $this->ApplyOnlines->getDataapplyonline('',$member_id,$state,'','',$main_branch_id,'','',$consumer_no,$application_search_no,$installer_name,$discom_name,$payment_status,$order_by_form,$disclaimer_subsidy,$pcr_code,$msme,$msmeonly,$category,$inspection_status,$geda_letter_status,$geda_approved_status,$receipt_no,$is_enhancement,$arrExtra);
					}
				} else {
					if(isset($this->request->data['status']) && !empty($this->request->data['status'])){
						$ApplyOnlinesList 	= $this->ApplyOnlines->getDataapplyonline('',$member_id,$state,'','','',$this->request->data['status'],'',$consumer_no,$application_search_no,$installer_name,$discom_name,$payment_status,$order_by_form,$disclaimer_subsidy,$pcr_code,$msme,$msmeonly,$category,$inspection_status,$geda_letter_status,$geda_approved_status,$receipt_no,$is_enhancement,$arrExtra);
					} else {
						//echo 'come';exit;
						$ApplyOnlinesList 	= $this->ApplyOnlines->getDataapplyonline('',$member_id,$state,'','','','','',$consumer_no,$application_search_no,$installer_name,$discom_name,$payment_status,$order_by_form,$disclaimer_subsidy,$pcr_code,$msme,$msmeonly,$category,$inspection_status,$geda_letter_status,$geda_approved_status,$receipt_no,$is_enhancement,$arrExtra);
					}

				}
			}

			$this->set('is_member',true);
			$this->set('member_type',$member_type);
		} else {
			return $this->redirect('home');
		}
		$memberApproved 	= in_array($member_id, $this->ApplyOnlines->ALLOWED_APPROVE_GEDAIDS) ? '1' : '0';
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
		$category_map_data 		= $this->InstallerCategoryMapping->find('all',array('conditions'=>array('installer_id'=>$installer_id)))->first();
		/*if($current_date>strtotime(DATE_STOP_CATEGORYB) && !empty($category_map_data) && $category_map_data->category_id=='2')
		{
			$output_quota = 'PV capacity quota over.';
		}
		else
		{*/
			$output_quota  		= true;
			if($login_type != 'developer') { 
				$output_quota 	= $this->ApplyOnlines->checked_total_capacity($installer_id);
			}
			
		//}
			$ApplyOnlinesListData 	= $ApplyOnlinesList['list'];
			$TotalPvCapacity 		= $ApplyOnlinesList['TotalCapacityData'];
		try
		{
			$paginate_data = $this->paginate($ApplyOnlinesListData);
		}
		catch (NotFoundException $e)
		{
			return $this->redirect('/apply-online-list');
		}
		/* status of application */
		$installers_list 		= $this->Installers->getInstallerListReport();
		$this->set("APPLY_ONLINE_MAIN_STATUS",$this->ApplyOnlineApprovals->apply_online_main_status);
		$this->set("APPLICATION_SUBMITTED",$this->ApplyOnlineApprovals->APPLICATION_SUBMITTED);
		$this->set("FEASIBILITY_APPROVAL",$this->ApplyOnlineApprovals->FEASIBILITY_APPROVAL);
		$this->set("FUNDS_ARE_AVAILABLE_BUT_SCHEME_IS_NOT_ACTIVE",$this->ApplyOnlineApprovals->FUNDS_ARE_AVAILABLE_BUT_SCHEME_IS_NOT_ACTIVE);
		$this->set("FUNDS_ARE_NOT_AVAILABLE",$this->ApplyOnlineApprovals->FUNDS_ARE_NOT_AVAILABLE);
		$this->set("SUBSIDY_AVAILIBILITY",$this->ApplyOnlineApprovals->SUBSIDY_AVAILIBILITY);
		$this->set("WORK_STARTS",$this->ApplyOnlineApprovals->WORK_STARTS);
		$this->set("APPLICATION_GENERATE_OTP",$this->ApplyOnlineApprovals->APPLICATION_GENERATE_OTP);
		/* end status of application */
		$this->set("JREDA",$this->ApplyOnlines->JREDA);
		$this->set("DISCOM",$this->ApplyOnlines->DISCOM);
		$this->set("CEI",$this->ApplyOnlines->CEI);
		$this->set("MStatus",$this->ApplyOnlineApprovals);
		$this->set("ApplyOnlines",$this->ApplyOnlines);
		$this->set("FesibilityReport",$this->FesibilityReport);
		$this->set("application_status",$this->ApplyOnlineApprovals->application_status);
		$this->set("application_dropdown_status",$this->ApplyOnlines->apply_online_dropdown_status);
		$this->set("branch_id",$branch_id);
		$this->set("subdivision",$this->Session->read("Members.subdivision"));
		$this->set('ApplyOnlineLeads',$paginate_data);
		$this->set("ApplyOnlinesList",$ApplyOnlinesList);
		$this->set("MemberTypeDiscom",$this->Members->member_type_discom);
		$this->set("discom_details",$main_branch_id);
		$this->set("payment_on",Configure::read('PAYUMONEY_PAYMENT'));
		$this->set("APPLY_ONLINE_GUJ_STATUS",$this->ApplyOnlineApprovals->apply_online_guj_status);
		$this->set("applyOnlinesDataDocList",$this->ApplyonlinDocs);
		$this->set('discom_arr',$discom_arr);
		$this->set('quota_msg_disp',$output_quota);
		$this->set('ApiLogResponse',$this->ThirdpartyApiLog);
		$this->set('SpinLogResponse',$this->SpinWebserviceApi);
		$this->set('member_id',$member_id);
		$this->set('customer_type_list',$this->Parameters->GetParameterList(3));
		$this->set('Inspectionpdf',$this->Inspectionpdf);
		$this->set('memberApproved',$memberApproved);
		$this->set('TotalPvCapacity',$TotalPvCapacity);
		$this->set('Installers',$installers_list);
		//$this->set("apply_online_model",$this->ApplyOnlines);
		$this->set("authority_account",$authority_account);
		$this->set("ApplicationRequestDelete",$this->ApplicationRequestDelete);
		$this->set("login_type",$login_type);
		$this->set('Couchdb',$this->Couchdb);
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
			$applyOnlinesData 		= $this->ApplyOnlines->viewApplication($id);
			$applyOnlinesDataDocList= $this->ApplyonlinDocs->find("all",['conditions'=>['application_id'=>$id,'doc_type in'=>array('others','Signed_Doc','Self_Certificate')]])->toArray();
			$Applyonlinprofile  	= $this->ApplyonlinDocs->find('all',['conditions'=>['application_id'=>$id,'doc_type'=>'profile']])->first();
		}
		$discom_list = $this->BranchMasters->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['BranchMasters.parent_id'=>'0']])->toArray();
		$payumoney_data = $this->Payumoney->find('all',['fields'=>array('Payumoney.transaction_id','Payumoney.payment_date'),'join'=>[
						'ap' => [
							'table' => 'applyonline_payment',
							'type' => 'INNER',
							'conditions' => ['Payumoney.id = ap.payment_id']
						]]])->where(['ap.application_id' => $id])->first();
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

		$applyOnlinesOthersData 	= $this->ApplyOnlinesOthers->find('all',array('conditions'=>array('application_id'=>$id)))->first();
		$memberViewPanAdhar 		= in_array($member_id, $this->ApplyOnlines->ALLOWED_APPROVE_GEDAIDS) ? '1' : '0';
		$this->set("APPLY_ONLINE_MAIN_STATUS",$this->ApplyOnlineApprovals->apply_online_main_status);
		$this->set("pageTitle","Apply-online View");
		$this->set("applyOnlinesDataDocList",$applyOnlinesDataDocList);
		$this->set("discom_list",$discom_list);
		$this->set('ApplyOnlines',$applyOnlinesData);
		$this->set('transaction_id',$transaction_id);
		$this->set('payment_date',$payment_date);
		$this->set('Applyonlinprofile',$Applyonlinprofile);
		$this->set('member_type',$member_type);
		$this->set("MemberTypeDiscom",$this->Members->member_type_discom);
		$this->set("APPLY_ONLINE_GUJ_STATUS",$this->ApplyOnlineApprovals->apply_online_guj_status);
		$this->set("MStatus",$this->ApplyOnlineApprovals);
		$this->set('logged_in_id',$this->Session->read('Customers.id'));
		$this->set('member_type',$member_type);
		$this->set('is_member',$is_member);
		$this->set('FeasibilityData',$FeasibilityData);
		$this->set('page_cur',$page_cur);
		$this->set('encode_id',$encode_id);
		$this->set('applyOnlinesOthersData',$applyOnlinesOthersData);
		$this->set('memberViewPanAdhar',$memberViewPanAdhar);
		$this->set("payment_on",Configure::read('PAYUMONEY_PAYMENT'));
		$this->set('Couchdb',$this->Couchdb);
	}

	public function paymentfeebycapacity()
	{
		$data 		= [];
		if(isset($this->request->data['capacity'])){
			$capacity 	= $this->request->data['capacity'];

			$var_data 	= [['discom'=>'250','jreda'=>'0','total'=>'250'],['discom'=>'750','jreda'=>'0','total'=>'750']];
			if($capacity > 50){
				$data 	= $var_data[1];
			} else {
				$data 	= $var_data[0];
			}
			$status = 'ok';
			$message = 'list of fee';
		} else {
			$status = 'error';
			$message = 'please pass capacity';
		}
		$this->ApiToken->SetAPIResponse('msg', $message);
		$this->ApiToken->SetAPIResponse('type', $status);
		$this->ApiToken->SetAPIResponse('data', $data);
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	public function forward()
	{
		$this->autoRender = false;
		$member_id = $this->Session->read('Members.id');
		if(isset($this->request->data['id']) && !empty($this->request->data['id'])) {
			$ApplyOnlinesEntity = $this->ApplyOnlines->setStatus($this->request->data);
			$this->ApplyOnlineApprovals->saveStatus($ApplyOnlinesEntity->id,$this->ApplyOnlineApprovals->APPLICATION_SUBMITTED,$member_id);
			echo '1';
		} else {
			echo '0';
		}
	}

	// update application status
	public function changeStatus() {
		$this->layout 		= false;
		$this->autoRender 	= false;
		$member_id 			= $this->Session->read('Members.id');
		if(!empty($member_id) && isset($this->request->data['id']) && !empty($this->request->data['id'])) {
			$ApplyOnlinesEntity = $this->ApplyOnlines->viewApplication($this->request->data['id']);
			$this->SetApplicationStatus($this->request->data['application_status'],$ApplyOnlinesEntity->id);
			$ApplicationData = $this->ApplyOnlines->viewApplication($this->request->data['id']);
			$this->sendMailToCustomer($ApplicationData->toArray());
			$this->ApplyOnlineApprovals->saveStatus($ApplicationData->id,$ApplicationData->application_status,$member_id);
			echo '1';
		} else {
			echo '0';
		}
		exit;
	}

	public function sendMailToCustomer($entity_application) {
		$status 	= $entity_application['application_status'];
		$to_email	= $entity_application['email'];
		if ($status == $this->ApplyOnlineApprovals->SUBSIDY_AVAILIBILITY) {
			if ($this->ApplyOnlines->ApproveCEIMatrix($entity_application['pv_capacity'])) {
				//$NextStatus = $this->ApplyOnlineApprovals->WORK_STARTS;
				//$this->SetApplicationStatus($NextStatus,$entity_application['id']);
			}
		}
		$subject	= '';
		if(($status == 3 || $status == 5 || $status == 6) && !empty($to_email))
		{
			if ($status == 3) {
				$this->SendSubsidyLetterOfApproval($entity_application['id']);
			} else {
				if ($status == 5) {
					$entity_application['body'] = "Funds are not available and hence approval for subsidy is not granted. However, in case the Applicant wants to install solar PV system without subsidy then the Applicant can do so subject to the approval for connectivity from the DisCom.";
					$subject = 'GEDA: Funds are not available';
				} else if ($status == 6) {
					$entity_application['body'] = "Funds are available but scheme is not active. So, the Applicant can apply later when the scheme is active.";
					$subject = 'GEDA: Funds are available but scheme is not active';
				}
				$to			= $to_email;
				$email 		= new Email('default');
				$email->profile('default');
				$email->viewVars(array('viewVars' => $entity_application));
				$message_send = $email->template('customer_application_status', 'default')
					->emailFormat('html')
					->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
					->to($to)
					->subject(Configure::read('EMAIL_ENV').$subject)
					->send();
			}
		}
		return true;
	}

	public function save_other_doc($data,$ApplyOnlinesEntityId,$doc_type='others'){

		$ids 		= array(0,0);
		$image_path = APPLYONLINE_PATH.$ApplyOnlinesEntityId.'/';

		foreach ($data['Aplication_doc_title'] as $key => $value) {
			if(!empty($data['Aplication_doc_title'][$key])) {
				if(!empty($data['Aplication_doc_id'][$key])) {
					$getEntity  = $this->ApplyonlinDocs->get($data['Aplication_doc_id'][$key]);
					$ApplyonlinDocsEntity = $this->ApplyonlinDocs->patchEntity($getEntity,array());
				} else {
					$ApplyonlinDocsEntity = $this->ApplyonlinDocs->newEntity();
				}
				if(isset($data['Aplication_doc_file'][$key]) && !empty($data['Aplication_doc_file'][$key]['name'])) {
					$db_file_name = $ApplyonlinDocsEntity->file_name;
					if(file_exists($image_path.$db_file_name) && !empty($db_file_name)){
						@unlink($image_path.$db_file_name);
						if(file_exists($image_path.'r_'.$db_file_name)) {
							@unlink($image_path.'r_'.$db_file_name);
						}
					}

					$image_path = APPLYONLINE_PATH.$ApplyOnlinesEntityId.'/';
					$file_name = $this->file_upload($image_path,$data['Aplication_doc_file'][$key],false,65,65,$image_path,'doc_'.$key,'others'.($key+1),$ApplyonlinDocsEntity);
					$ApplyonlinDocsEntity->doc_type 	= $doc_type;
					$ApplyonlinDocsEntity->file_name 	= $file_name;
					$ApplyonlinDocsEntity->created 		= $this->NOW();
					$ApplyonlinDocsEntity->couchdb_id 	= $this->Couchdb->couchdb_id;

				}
				$ApplyonlinDocsEntity->title 			= $data['Aplication_doc_title'][$key];
				$ApplyonlinDocsEntity->application_id 	= $ApplyOnlinesEntityId;
				$this->ApplyonlinDocs->save($ApplyonlinDocsEntity);
				$ids[] = $ApplyonlinDocsEntity->id;
			}
		}

		if(isset($data['profile_image']) && !empty($data['profile_image']['name']))
		{
			if(!empty($data['profile_image_id'])) {
					$getEntity  = $this->ApplyonlinDocs->get($data['profile_image_id']);
					$ApplyonlinDocsEntity = $this->ApplyonlinDocs->patchEntity($getEntity,array());
			} else {
					$ApplyonlinDocsEntity = $this->ApplyonlinDocs->newEntity();
			}
			$db_file_name = $ApplyonlinDocsEntity->file_name;
			if(file_exists($image_path.$db_file_name) && !empty($db_file_name)){
				@unlink($image_path.$db_file_name);
				if(file_exists($image_path.'r_'.$db_file_name)) {
					@unlink($image_path.'r_'.$db_file_name);
				}
			}
			$image_path = APPLYONLINE_PATH.$ApplyOnlinesEntityId.'/';
			$file_name 	= $this->file_upload($image_path,$data['profile_image'],true,65,65,$image_path,'profile_','profile');
			$ApplyonlinDocsEntity->doc_type 		= 'profile';
			$ApplyonlinDocsEntity->file_name 		= $file_name;
			$ApplyonlinDocsEntity->application_id 	= $ApplyOnlinesEntityId;
			$ApplyonlinDocsEntity->created 			= $this->NOW();
			$ApplyonlinDocsEntity->couchdb_id 		= $this->Couchdb->couchdb_id;
			$this->ApplyonlinDocs->save($ApplyonlinDocsEntity);
			$ids[] = $ApplyonlinDocsEntity->id;
		}
		// $conditions_notin = ['id NOT IN '=>$ids,'application_id'=>$ApplyOnlinesEntityId];
	 //    $ApplyonlinDocsDeleteList = $this->ApplyonlinDocs->find("all")->where($conditions_notin)->toArray();
	 //    foreach ($ApplyonlinDocsDeleteList as $key => $value) {
	 //    	$db_file_name = $value['file_name'];
	 //    	if(file_exists($image_path.$db_file_name)){
		// 		@unlink($image_path.$db_file_name);
		// 		@unlink($image_path.'r_'.$db_file_name);
		// 	}
	 //    }
	 //    $this->ApplyonlinDocs->deleteAll($conditions_notin);
	}


	/**
	 *
	 * fesibility
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to fesibility report by discom installer
	 *
	 */
	public function fesibility($id= null)
	{
		$this->setMemberArea();
		$this->validateMemberPermission("FESIBILITY");
		$main_branch_id = array();

		$area 			= $this->Session->read('Members.area');
		$circle 		= $this->Session->read('Members.circle');
		$division 		= $this->Session->read('Members.division');
		$subdivision 	= $this->Session->read('Members.subdivision');
		$section 		= $this->Session->read('Members.section');
		$member_id 		= $this->Session->read('Members.id');
		$member_type 	= $this->Session->read('Members.member_type');
		if (!empty($member_id) && $member_type == $this->ApplyOnlines->DISCOM)
		{
			$field      = "area";
			$uid         = $area;
			if (!empty($section)) {
				$field      = "section";
				$uid         = $section;
			} else if (!empty($subdivision)) {
				$field      = "subdivision";
				$uid         = $subdivision;
			} else if (!empty($division)) {
				$field      = "division";
				$uid         = $division;
			} else if (!empty($circle)) {
				$field      = "circle";
				$uid         = $circle;
			}
			$main_branch_id = array("field"=>$field,"id"=>$uid);
		}
		if(empty($id)) {
			$this->Flash->error('Please select valid application.');
			return $this->redirect(URL_HTTP.'/apply-online-list');
		} else {
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->ApplyOnlines->viewApplication($id);
			if($applyOnlinesData->discom!='15' && $applyOnlinesData->discom!='16')
			{
				$checkfeasibilitydone 	= $this->FesibilityReport->fetchApiFeasibility($id);
				if($checkfeasibilitydone == '1')
				{
					return $this->redirect(URL_HTTP.'/apply-online-list');
				}
			}
			$connection         = ConnectionManager::get('default');
			$sql_fea 			= "SELECT id FROM `fesibility_report` group by application_id having count(0)>1";
			$fesibility_output  = $connection->execute($sql_fea)->fetchAll('assoc');
			foreach($fesibility_output as $key=>$fea_data)
			{
				$this->FesibilityReport->deleteAll(['id' => $fea_data['id']]);
			}
			if ($this->request->is('post')) {
				$rid = 0;
				if(isset($this->request->data) && !empty($this->request->data))
				{
						$this->FesibilityReport->data  = $this->request->data;
				}
				$this->FesibilityReport->data['pv_capacity']  = $applyOnlinesData->pv_capacity;
				if (isset($this->request->data['rid']) && !empty($this->request->data['rid'])) {
					$rid 		= intval(decode($this->request->data['rid']));
					$NEWRECORD 	= false;
					if (!empty($rid)) {
						$FesibilityReport 		= $this->FesibilityReport->get($rid);
						$FesibilityReport 		= $this->FesibilityReport->patchEntity($FesibilityReport,$this->request->data,['validate'=>'Add']);
						$FesibilityReport->id 	= $rid;
					} else {
						$FesibilityReport 		= $this->FesibilityReport->newEntity($this->request->data,['validate'=>'Add']);
						$NEWRECORD 				= true;
					}
				} else {
					$fesibility_data_already= $this->FesibilityReport->find('all',array('conditions'=>array('application_id'=>$id)))->first();
					if(!empty($fesibility_data_already))
					{
						$FesibilityReport 		= $this->FesibilityReport->get($fesibility_data_already->id);
						$FesibilityReport 		= $this->FesibilityReport->patchEntity($FesibilityReport,$this->request->data,['validate'=>'Add']);
					}
					else
					{
						$FesibilityReport 					= $this->FesibilityReport->newEntity($this->request->data,['validate'=>'Add']);
					}
					$FesibilityReport->application_id 	= $id;
					$FesibilityReport->created 			= $this->NOW();
					$FesibilityReport->created_by 		= $this->Session->read('Members.id');
				}
				if (empty($FesibilityReport->field_officer)) {
					$FesibilityReport->field_officer = $applyOnlinesData->installer['installer_name'];
				}
				if (!empty($FesibilityReport->approved_by_subdivision)) {
					$FesibilityReport->approved_by_subdivision 	= 1;
					$FesibilityReport->subdivision_approved_by 	= $this->Session->read('Members.id');
				}
				if(empty($FesibilityReport->application_fee))
				{
					$FesibilityReport->application_fee 	= $applyOnlinesData->disCom_application_fee;
				}
				$FesibilityReport->modified 			= $this->NOW();
				$FesibilityReport->modified_by 			= $this->Session->read('Members.id');
				$estimated_due_date  					= (isset($this->request->data['estimated_due_date'])?$this->request->data['estimated_due_date']:'');

				if(!$FesibilityReport->errors())
				{
				$FesibilityReport->estimated_due_date   = date('Y-m-d',strtotime($estimated_due_date));
				if ($this->FesibilityReport->save($FesibilityReport)) {
					$this->request->data['consumer_no'] = !empty($this->request->data['consumer_no']) ? $this->request->data['consumer_no'] : $applyOnlinesData->consumer_no;
					$this->ApplyOnlines->UpdateApplicationData($this->request->data,$id);
					if (empty($rid)) {
						$rid = $FesibilityReport->id;
					}
					if ($FesibilityReport->approved == 1) {
						$this->SetApplicationStatus($this->ApplyOnlineApprovals->FIELD_REPORT_SUBMITTED,$id);

					} else if ($FesibilityReport->approved == 0 && $FesibilityReport->approved != '') {
						$this->SetApplicationStatus($this->ApplyOnlineApprovals->FIELD_REPORT_REJECTED,$id);
					}
					$this->Flash->success('Fesibility report submitted.');
					return $this->redirect(URL_HTTP.'/apply-onlines/fesibility/'.$encode_id);
				}
				}
				$fesibility = $FesibilityReport;
			} else {
				$fesibility 									= $this->FesibilityReport->getReportData($id);
				$rid 											= isset($fesibility['id'])?encode($fesibility['id']):"";
				if (empty($fesibility)) {
					$fesibility 								= $this->FesibilityReport->newEntity(array());
					$fesibility->field_officer 					=  $applyOnlinesData->installer['installer_name'];
					$fesibility->created 						= $this->NOW();
					$fesibility->proposed_capacity 				= $applyOnlinesData->pv_capacity;
					$fesibility->application_fee 				= $applyOnlinesData->disCom_application_fee;
					$fesibility->sanction_load      			= $applyOnlinesData->sanction_load_contract_demand;
					$fesibility->recommended_capacity_by_discom	= $applyOnlinesData->pv_capacity;
				}
				$address = "";
				if (!empty($applyOnlinesData->address1)) {
					$address .= $applyOnlinesData->address1.", ";
				}
				if (!empty($applyOnlinesData->address2)) {
					$address .= $applyOnlinesData->address2.", ";
				}
				if (!empty($applyOnlinesData->city)) {
					$address .= $applyOnlinesData->city.", ";
				}
				if (!empty($applyOnlinesData->state)) {
					$address .= $applyOnlinesData->state.", ";
				}
				if (!empty($applyOnlinesData->pincode)) {
					$address .= $applyOnlinesData->pincode.", ";
				}
				$edd 								= $fesibility->estimated_due_date;
				if($edd != '0000-00-00' && $edd != '')
				{
					$fesibility->estimated_due_date = $edd->format('d-m-Y');
				}
				else
				{
					$fesibility->estimated_due_date = '';
				}
				$fesibility->comunication_address 	= rtrim($address,", ");
				$fesibility->consumer_no 			= $applyOnlinesData->consumer_no;
				$fesibility->mobile 				= $applyOnlinesData->mobile;
				$fesibility->category 				= $applyOnlinesData->category;

				//date('d-m-Y',strtotime());
				//pr($fesibility);
			}
		}
		$applyOnlinesData->aid 	= $this->ApplyOnlines->GenerateApplicationNo($applyOnlinesData);
		//print_r($applyOnlinesData);
		$division 				= $this->DiscomMaster->find("all",['fields'=>['title'],'conditions'=>['DiscomMaster.id'=>$applyOnlinesData->division,'DiscomMaster.type'=>3,'status'=>'1']])->toArray();
		$circle 				= $this->DiscomMaster->find("all",['fields'=>['title'],'conditions'=>['DiscomMaster.id'=>$applyOnlinesData->circle,'DiscomMaster.type'=>2,'status'=>'1']])->toArray();
		$subdivision 			= $this->DiscomMaster->find("all",['fields'=>['title'],'conditions'=>['DiscomMaster.id'=>$applyOnlinesData->subdivision,'DiscomMaster.type'=>4,'status'=>'1']])->toArray();
		$BillCategoryList 		= $this->Parameters->GetParameterList(3);
		$this->set('id',$encode_id);
		$this->set('rid',$rid);
		$this->set('division',$division);
		$this->set('circle',$circle);
		$this->set('subdivision',$subdivision);
		$this->set('BillCategoryList',$BillCategoryList);
		$this->set('ApplyOnlines',$applyOnlinesData);
		$this->set('fesibility',$fesibility);
		$this->set('RejectReason',$this->FesibilityReport->RejectReason);
		$this->set("pageTitle","Submit Fesibility Report");
		$this->set("discom_details",$main_branch_id);
		$this->set("Mstatus",$this->ApplyOnlineApprovals);
		$this->set("member_type",$member_type);
		$this->set("DISCOM",$this->ApplyOnlines->DISCOM);

		$issubdivision 	= $this->Session->read('Members.subdivision');
		$section 		= $this->Session->read('Members.section');
		if ($issubdivision > 0 && $section == 0) {
			$this->set("issubdivision",1);
		} else {
			$this->set("issubdivision",0);
		}
	}

	/**
	 *
	 * doregistration
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to JERDA REGISTRATION
	 *
	 */
	public function doregistration($id= null)
	{
		$this->ValidateAccess("JERDA_REGISTRATION");
		$this->validateMemberPermission("JERDA_REGISTRATION");
		if(empty($id)) {
			$this->Flash->error('Please select valid application.');
			return $this->redirect(URL_HTTP.'/apply-online-list');
		} else {
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->ApplyOnlines->viewApplication($id);
			$NEWRECORD 				= false;
			if ($this->request->is('post') || $this->request->is('put')) {
				$rid = 0;
				if (isset($this->request->data['id']) && !empty($this->request->data['id'])) {
					$rid 		= intval(decode($this->request->data['id']));
					if (!empty($rid)) {
						$RegistrationScheme 		= $this->RegistrationScheme->get($rid);
						$RegistrationScheme 		= $this->RegistrationScheme->patchEntity($RegistrationScheme,$this->request->data,['validate'=>'Add']);
						$RegistrationScheme->id 	= $rid;
					} else {
						$RegistrationScheme 		= $this->RegistrationScheme->newEntity($this->request->data,['validate'=>'Add']);
						$NEWRECORD 					= true;
					}
				} else {
					$NEWRECORD 								= true;
					$RegistrationScheme 					= $this->RegistrationScheme->newEntity($this->request->data,['validate'=>'Add']);
					$RegistrationScheme->application_id 	= $id;
					$RegistrationScheme->created 			= $this->NOW();
					if (!empty($this->Session->read('Members.id'))) {
						$RegistrationScheme->created_by 		= $this->Session->read('Members.id');
					} else {
						$RegistrationScheme->created_by 		= $this->Session->read('Customers.id');
					}
				}
				$RegistrationScheme->modified 			= $this->NOW();
				if (!empty($this->Session->read('Members.id'))) {
					$RegistrationScheme->modified_by 		= $this->Session->read('Members.id');
				} else {
					$RegistrationScheme->modified_by 		= $this->Session->read('Customers.id');
				}
				if ($this->RegistrationScheme->save($RegistrationScheme)) {
					if (empty($rid)) {
						$rid = $RegistrationScheme->id;
					}
					if ($NEWRECORD) {
						$this->SetApplicationStatus($this->ApplyOnlineApprovals->REGISTRATION,$id);
					}
					$this->SaveDocuments($id,$rid);
					$this->Flash->success('Application for Registeration of the Sceme for Rooftop Solar PV System submitted successfully.');
					return $this->redirect(URL_HTTP.'/apply-onlines/do-registration/'.$encode_id);
				}
				$RegistrationScheme = $RegistrationScheme;
			} else {
				$RegistrationScheme = $this->RegistrationScheme->getReportData($id);
				$rid 				= isset($RegistrationScheme['id'])?encode($RegistrationScheme['id']):"";
				if (empty($RegistrationScheme)) {
					$RegistrationScheme 				= $this->RegistrationScheme->newEntity(array());
					$RegistrationScheme->created 		= $this->NOW();
				} else {
					$RegistrationScheme->aid 			= $this->RegistrationScheme->GenerateApplicationNo($RegistrationScheme,$applyOnlinesData->state);
					$RegistrationScheme->documents 		= $this->RegistrationSchemeDocument->getReportData($RegistrationScheme['id']);
				}
			}
		}
		$applyOnlinesData->aid 	= $this->ApplyOnlines->GenerateApplicationNo($applyOnlinesData);
		$fesibility 			= $this->FesibilityReport->getReportData($id);
		$fesibility->aid 		= $this->FesibilityReport->GenerateApplicationNo($fesibility,$applyOnlinesData->state);
		$this->set('id',$encode_id);
		$this->set('rid',$rid);
		$this->set('ApplyOnlines',$applyOnlinesData);
		$this->set('fesibility',$fesibility);
		$this->set('RegistrationScheme',$RegistrationScheme);
		$this->set("pageTitle","Application for Registeration of the Sceme for Rooftop Solar PV System");
	}

	/*
	 * API for save document of applayonline from
	 * @param mixed What page to display
	 * @return void
	 */
	private function SaveDocuments($id,$rid) {
		$image_path = APPLYONLINE_PATH.$id.'/registration/';
		if(!file_exists($image_path)) {
			@mkdir($image_path, 0777,true);
		}
		if(!empty($this->request->data['document_1'])) {
			$file_name = $this->file_upload($image_path,$this->request->data['document_1'],false,0,0,$image_path,'tech_spec_');
			if (!empty($file_name) && file_exists($image_path.$file_name)) {
				$RegistrationSchemeDocument 					= $this->RegistrationSchemeDocument->newEntity();
				$RegistrationSchemeDocument->application_id 	= $id;
				$RegistrationSchemeDocument->rid 				= $rid;
				$RegistrationSchemeDocument->filename 			= $file_name;
				$RegistrationSchemeDocument->status 			= 1;
				$RegistrationSchemeDocument->created 			= $this->NOW();
				$RegistrationSchemeDocument->modified 			= $this->NOW();
				if (!empty($this->Session->read('Members.id'))) {
					$RegistrationSchemeDocument->created_by 	= $this->Session->read('Members.id');
					$RegistrationSchemeDocument->modified_by 	= $this->Session->read('Members.id');
				} else {
					$RegistrationSchemeDocument->created_by 	= $this->Session->read('Customers.id');
					$RegistrationSchemeDocument->modified_by 	= $this->Session->read('Customers.id');
				}
				$this->RegistrationSchemeDocument->save($RegistrationSchemeDocument);
			}
		}
		if(!empty($this->request->data['document_2'])) {
			$file_name = $this->file_upload($image_path,$this->request->data['document_2'],false,0,0,$image_path,'tech_spec_1_');
			if (!empty($file_name) && file_exists($image_path.$file_name)) {
				$RegistrationSchemeDocument 					= $this->RegistrationSchemeDocument->newEntity();
				$RegistrationSchemeDocument->application_id 	= $id;
				$RegistrationSchemeDocument->rid 				= $rid;
				$RegistrationSchemeDocument->filename 			= $file_name;
				$RegistrationSchemeDocument->status 			= 1;
				$RegistrationSchemeDocument->created 			= $this->NOW();
				$RegistrationSchemeDocument->modified 			= $this->NOW();
				if (!empty($this->Session->read('Members.id'))) {
					$RegistrationSchemeDocument->created_by 	= $this->Session->read('Members.id');
					$RegistrationSchemeDocument->modified_by 	= $this->Session->read('Members.id');
				} else {
					$RegistrationSchemeDocument->created_by 	= $this->Session->read('Customers.id');
					$RegistrationSchemeDocument->modified_by 	= $this->Session->read('Customers.id');
				}
				$this->RegistrationSchemeDocument->save($RegistrationSchemeDocument);
			}
		}
		if(!empty($this->request->data['document_3'])) {
			$file_name = $this->file_upload($image_path,$this->request->data['document_3'],false,0,0,$image_path,'drawing_');
			if (!empty($file_name) && file_exists($image_path.$file_name)) {
				$RegistrationSchemeDocument 					= $this->RegistrationSchemeDocument->newEntity();
				$RegistrationSchemeDocument->application_id 	= $id;
				$RegistrationSchemeDocument->rid 				= $rid;
				$RegistrationSchemeDocument->filename 			= $file_name;
				$RegistrationSchemeDocument->status 			= 1;
				$RegistrationSchemeDocument->created 			= $this->NOW();
				$RegistrationSchemeDocument->modified 			= $this->NOW();
				if (!empty($this->Session->read('Members.id'))) {
					$RegistrationSchemeDocument->created_by 	= $this->Session->read('Members.id');
					$RegistrationSchemeDocument->modified_by 	= $this->Session->read('Members.id');
				} else {
					$RegistrationSchemeDocument->created_by 	= $this->Session->read('Customers.id');
					$RegistrationSchemeDocument->modified_by 	= $this->Session->read('Customers.id');
				}
				$this->RegistrationSchemeDocument->save($RegistrationSchemeDocument);
			}
		}
	}

	public function getSubdivision() {
		$this->autoRender 		= false;
		$division 				= isset($this->request->data['division'])?$this->request->data['division']:0;
		$subdivision 			= isset($this->request->data['subdivision'])?$this->request->data['subdivision']:0;
		$division 				= intval($division);
		$subdivision 			= intval($subdivision);
		if (!empty($subdivision)) {
			$section 			= $this->DiscomMaster->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['DiscomMaster.subdivision'=>$subdivision,'DiscomMaster.type'=>5,'status'=>'1']]);
			$data['section'] 	= $section;
		} else {
			$subdivisions 			= $this->DiscomMaster->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['DiscomMaster.division'=>$division,'DiscomMaster.type'=>4,'status'=>'1']]);
			$section 				= $this->DiscomMaster->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['DiscomMaster.division'=>$division,'DiscomMaster.type'=>5,'status'=>'1']]);
			$data['subdivision'] 	= $subdivisions;
			$data['section'] 		= $section;
		}
		$this->ApiToken->SetAPIResponse('msg', 'list of subdivision and sections');
		$this->ApiToken->SetAPIResponse('data', $data);
		$this->ApiToken->SetAPIResponse('type','ok');
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	public function assigndiscom() {
		$this->autoRender 		= false;
		$subdivision 			= intval(isset($this->request->data['subdivision'])?$this->request->data['subdivision']:0);
		$section 				= intval(isset($this->request->data['section'])?$this->request->data['section']:0);
		$app_id 				= intval(isset($this->request->data['id'])?$this->request->data['id']:0);
		if (!empty($subdivision)) {
			$this->ApplyOnlines->updateAll(['subdivision' 	=> $subdivision,
											'section' 		=> $section,
											'modified' 		=> $this->NOW()],
											['id' => $app_id]);
			$this->ApiToken->SetAPIResponse('msg', 'Subdivision assigned.');
			$this->ApiToken->SetAPIResponse('type','ok');
		} else {
			$this->ApiToken->SetAPIResponse('msg', 'Subdivision is required field.');
			$this->ApiToken->SetAPIResponse('type','error');
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	private function SendLetterOfApproval($id=0)
	{
		$this->autoRender 			= false;
		$applyOnlinesData 			= $this->ApplyOnlines->viewApplication($id);
		$applyOnlinesData->aid 		= $this->ApplyOnlines->GenerateApplicationNo($applyOnlinesData);
		$fesibility 				= $this->FesibilityReport->getReportData($id);
		$fesibility->aid 			= $this->FesibilityReport->GenerateApplicationNo($fesibility,$applyOnlinesData->state);
		$division 					= $this->DiscomMaster->find("all",['fields'=>['title'],'conditions'=>['DiscomMaster.id'=>$applyOnlinesData->division,'DiscomMaster.type'=>3,'status'=>'1']])->toArray();
		$circle 					= $this->DiscomMaster->find("all",['fields'=>['title'],'conditions'=>['DiscomMaster.id'=>$applyOnlinesData->circle,'DiscomMaster.type'=>2,'status'=>'1']])->toArray();
		$subdivision 				= $this->DiscomMaster->find("all",['fields'=>['title'],'conditions'=>['DiscomMaster.id'=>$applyOnlinesData->subdivision,'DiscomMaster.type'=>4,'status'=>'1']])->toArray();

		$LETTER_APPLICATION_NO 		= $applyOnlinesData->aid;
		$APPLICATION_DATE 			= date("d M Y",strtotime($applyOnlinesData->created));
		$CUSTOMER_NAME 				= trim($applyOnlinesData->customer_name_prefixed." ".$applyOnlinesData->name_of_consumer_applicant);
		$CONSUMER_NO 				= $applyOnlinesData->consumer_no;
		$FESIBILITY_REF_NO 			= $fesibility->aid;
		$APPLICATION_NO				= $applyOnlinesData->aid;
		$APPROVED_CAPACITY 			= floatval($fesibility->recommended_capacity_by_discom);
		$SUBDIVISION 				= (isset($subdivision[0]['title'])?$subdivision[0]['title']:"-");
		$DIVISION 					= (isset($division[0]['title'])?$division[0]['title']:"-");
		$EmailVars 					= array("LETTER_APPLICATION_NO"=>$LETTER_APPLICATION_NO,
											"APPLICATION_DATE"=>$APPLICATION_DATE,
											"CUSTOMER_NAME"=>$CUSTOMER_NAME,
											"CONSUMER_NO"=>$CONSUMER_NO,
											"FESIBILITY_REF_NO"=>$FESIBILITY_REF_NO,
											"APPLICATION_NO"=>$APPLICATION_NO,
											"APPROVED_CAPACITY"=>$APPROVED_CAPACITY,
											"SUBDIVISION"=>$SUBDIVISION,
											"DIVISION"=>$DIVISION);
		$to			= "kalpak.yugtia@gmail.com";
		$email 		= new Email('default');
		$subject 	= "Letter of Approval - ".$APPLICATION_NO;
		$email->profile('default');
		$email->viewVars($EmailVars);
		$message_send = $email->template('letter_of_approval', 'default')
			->emailFormat('html')
			->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
			->to($to)
			->subject(Configure::read('EMAIL_ENV').$subject)
			->send();
		return true;
	}

	public function send_approval_letter($id=0)
	{
		$this->autoRender = false;
		//$this->SendLetterOfApproval($id);
		//$this->SendApplicationConfirmation($id);
		//$this->SendRegistrationConfirmation($id);
		//$this->SendSubsidyLetterOfApproval($id);
		$this->SendApplicationLetterToCustomer($id);
	}

	private function SendApplicationConfirmation($id=0)
	{
		$this->autoRender 			= false;
		$applyOnlinesData 			= $this->ApplyOnlines->viewApplication($id);
		$applyOnlinesData->aid 		= $this->ApplyOnlines->GenerateApplicationNo($applyOnlinesData);
		$division 					= $this->DiscomMaster->find("all",['fields'=>['title'],'conditions'=>['DiscomMaster.id'=>$applyOnlinesData->division,'DiscomMaster.type'=>3,'status'=>'1']])->toArray();
		$APPLICATION_NO				= $applyOnlinesData->aid;
		$APPLICATION_DATE 			= date("d M Y H:i A",strtotime($applyOnlinesData->created));
		$CUSTOMER_NAME 				= trim($applyOnlinesData->customer_name_prefixed." ".$applyOnlinesData->name_of_consumer_applicant);
		$CONSUMER_NO 				= $applyOnlinesData->consumer_no;
		$DIVISION 					= (isset($division[0]['title'])?$division[0]['title']:"-");
		$PV_CAPACITY 				= floatval($applyOnlinesData->pv_capacity);
		$MODE_OF_PAYMENT			= "BHIM/NEFT/RTGS/Credit Card/Debit Card";
		$EmailVars 					= array("APPLICATION_DATE"=>$APPLICATION_DATE,
											"CUSTOMER_NAME"=>$CUSTOMER_NAME,
											"CONSUMER_NO"=>$CONSUMER_NO,
											"APPLICATION_NO"=>$APPLICATION_NO,
											"PV_CAPACITY"=>$PV_CAPACITY,
											"MODE_OF_PAYMENT"=>$MODE_OF_PAYMENT,
											"DIVISION"=>$DIVISION);
		$to			= "kalpak.yugtia@gmail.com";
		$email 		= new Email('default');
		$subject 	= "Application Acknowledgement Of - ".$APPLICATION_NO;
		$email->profile('default');
		$email->viewVars($EmailVars);
		$message_send = $email->template('application_receipt', 'default')
			->emailFormat('html')
			->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
			->to($to)
			->subject(Configure::read('EMAIL_ENV').$subject)
			->send();
		return true;
	}

	private function SendRegistrationConfirmation($id=0)
	{
		$this->autoRender 			= false;
		$applyOnlinesData 			= $this->ApplyOnlines->viewApplication($id);
		$applyOnlinesData->aid 		= $this->ApplyOnlines->GenerateApplicationNo($applyOnlinesData);
		$division 					= $this->DiscomMaster->find("all",['fields'=>['title'],'conditions'=>['DiscomMaster.id'=>$applyOnlinesData->division,'DiscomMaster.type'=>3,'status'=>'1']])->toArray();
		$APPLICATION_NO				= $applyOnlinesData->aid;
		$APPLICATION_DATE 			= date("d M Y H:i A",strtotime($applyOnlinesData->created));
		$CUSTOMER_NAME 				= trim($applyOnlinesData->customer_name_prefixed." ".$applyOnlinesData->name_of_consumer_applicant);
		$CONSUMER_NO 				= $applyOnlinesData->consumer_no;
		$DIVISION 					= (isset($division[0]['title'])?$division[0]['title']:"-");
		$PV_CAPACITY 				= floatval($applyOnlinesData->pv_capacity);
		$MODE_OF_PAYMENT			= "BHIM/NEFT/RTGS/Credit Card/Debit Card";
		$EmailVars 					= array("APPLICATION_DATE"=>$APPLICATION_DATE,
											"CUSTOMER_NAME"=>$CUSTOMER_NAME,
											"CONSUMER_NO"=>$CONSUMER_NO,
											"APPLICATION_NO"=>$APPLICATION_NO,
											"PV_CAPACITY"=>$PV_CAPACITY,
											"MODE_OF_PAYMENT"=>$MODE_OF_PAYMENT,
											"DIVISION"=>$DIVISION);
		$to			= "kalpak.yugtia@gmail.com";
		$email 		= new Email('default');
		$subject 	= "Registration Acknowledgement Of - ".$APPLICATION_NO;
		$email->profile('default');
		$email->viewVars($EmailVars);
		$message_send = $email->template('registration_receipt', 'default')
			->emailFormat('html')
			->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
			->to($to)
			->subject(Configure::read('EMAIL_ENV').$subject)
			->send();
		return true;
	}


	/**
	 *
	 * chargingcertificate
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to Charging Certificate
	 *
	 */
	public function chargingcertificate($id= null)
	{
		$this->setMemberArea();
		$this->validateMemberPermission("CHARGING_CERTIFICATE");
		if(empty($id)) {
			$this->Flash->error('Please select valid application.');
			return $this->redirect(URL_HTTP.'/apply-online-list');
		} else {
			$encode_id 					= $id;
			$id 						= intval(decode($id));
			$checkfeasibilitydone 		= $this->ChargingCertificate->fetchApiMeterInstallation($id);
			if($checkfeasibilitydone == '1')
			{
				return $this->redirect(URL_HTTP.'/apply-online-list');
			}
			$applyOnlinesData 			= $this->ApplyOnlines->viewApplication($id);
			$NEWRECORD 					= false;
			if ($applyOnlinesData->application_status < $this->ApplyOnlineApprovals->REGISTRATION) {
				//$this->Flash->error('Process is not yet open for the selected application.');
				//return $this->redirect(URL_HTTP.'/apply-online-list');
			}
			$application_data 				= $this->ApplyOnlines->viewApplication($id);
			if ($this->request->is('post') || $this->request->is('put')) {
				$rid 						= 0;
				$ChargingCertificate_data	= $this->ChargingCertificate->find('all',array('conditions'=>array('application_id'=>$application_data->id)))->first();

				if(!empty($ChargingCertificate_data))
				{
					$ChargingCertificate 			= $this->ChargingCertificate->get($ChargingCertificate_data->id);
					$ChargingCertificate 			= $this->ChargingCertificate->patchEntity($ChargingCertificate,$this->request->data,['validate'=>'add']);
				}
				else
				{

					$ChargingCertificate 			= $this->ChargingCertificate->newEntity($this->request->data,['validate'=>'add']);
					$ChargingCertificate->application_id 	= $id;
					$ChargingCertificate->created 	= $this->NOW();
					$ChargingCertificate->created_by= $this->Session->read('Members.id');
				}
				$NEWRECORD 							= true;
				$agreement_date 					= '';
				$meter_installed_date 				= '';
				if(isset($this->request->data['agreement_date']) && !empty($this->request->data['agreement_date']))
				{
					$agreement_date 				= date('Y-m-d',strtotime($this->request->data['agreement_date']));
					$ChargingCertificate->agreement_date = $agreement_date;
				}
				if(isset($this->request->data['meter_installed_date']) && !empty($this->request->data['meter_installed_date']))
				{
					$meter_installed_date 	= date('Y-m-d',strtotime($this->request->data['meter_installed_date']));
					$ChargingCertificate->meter_installed_date 	= $meter_installed_date;
				}
				if(empty($ChargingCertificate->errors()))
				{
					$ChargingCertificate->application_id 		= $id;
					$ChargingCertificate->sanctioned_load_phase = isset($this->request->data['sanctioned_load_phase'])?$this->request->data['sanctioned_load_phase']:1;
					$ChargingCertificate->pv_capacity_phase 	= isset($this->request->data['pv_capacity_phase'])?$this->request->data['pv_capacity_phase']:1;
					$ChargingCertificate->agreement_date 		= $agreement_date;
					$ChargingCertificate->meter_installed_date 	= $meter_installed_date;
					$ChargingCertificate->solar_meter 			= isset($this->request->data['solar_meter'])?$this->request->data['solar_meter']:1;
					$ChargingCertificate->bi_directional_meter 	= isset($this->request->data['bi_directional_meter'])?$this->request->data['bi_directional_meter']:1;
					$ChargingCertificate->modified 				= $this->NOW();
					$ChargingCertificate->modified_by 			= $this->Session->read('Members.id');
					$rid 										= intval(decode($this->request->data['id']));
					if (!empty($rid)) {
						$ChargingCertificate->id 	= $rid;
					}
					if ($this->ChargingCertificate->save($ChargingCertificate)) {
						if($application_data->transmission_line!=$ChargingCertificate->pv_capacity_phase)
						{
							$message 						= 'Transmission line has been modified by Discom from '.$this->ApplyOnlines->PHASE_ARRAY[$application_data->transmission_line]. ' to '.$this->ApplyOnlines->PHASE_ARRAY[$ChargingCertificate->pv_capacity_phase];
							$member_id          			= $this->Session->read("Members.id");
							$member_type 					= $this->Session->read('Members.member_type');
							$browser 						= isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:"-";
							$ApplyonlineMessageEntity					= $this->ApplyonlineMessage->newEntity();
							$ApplyonlineMessageEntity->application_id 	= $id;
							$ApplyonlineMessageEntity->message 			= strip_tags($message);
							$ApplyonlineMessageEntity->user_type 		= !empty($member_type)?$member_type:0;
							$ApplyonlineMessageEntity->user_id 			= !empty($member_id)?$member_id:0;
							$ApplyonlineMessageEntity->ip_address 		= $this->IP_ADDRESS;
							$ApplyonlineMessageEntity->created 			= $this->NOW();
							$ApplyonlineMessageEntity->browser_info 	= json_encode($browser);
							$this->ApplyonlineMessage->save($ApplyonlineMessageEntity);
							$this->ApplyOnlines->updateAll(array('transmission_line'=>$ChargingCertificate->pv_capacity_phase),array('id'=>$application_data->id));
						}
						if (empty($rid)) {
							$rid = $ChargingCertificate->id;
						}
						if ($NEWRECORD) {
							$this->SetApplicationStatus($this->ApplyOnlineApprovals->METER_INSTALLATION,$id);
							$this->SetApplicationStatus($this->ApplyOnlineApprovals->APPROVED_FROM_DISCOM,$id);
						}
						$Execution_data         = $this->Installation->find('all',array('conditions'=>array('project_id'=>$application_data->project_id)))->first();
						if(!empty($Execution_data))
						{
							$arrUpdate          = array();
							if(empty($Execution_data->meter_serial_no))
							{
								$arrUpdate['meter_serial_no']       = $ChargingCertificate->bi_directional_meter;
							}
							if(empty($Execution_data->solar_meter_serial_no))
							{
								$arrUpdate['solar_meter_serial_no'] = $ChargingCertificate->solar_meter;
							}
							if(empty($Execution_data->bi_date) || $Execution_data->bi_date=='0000-00-00')
							{
								$arrUpdate['bi_date']               = $ChargingCertificate->meter_installed_date;
							}
							if(empty($Execution_data->agreement_date) || $Execution_data->agreement_date=='0000-00-00')
							{
								$arrUpdate['agreement_date']        = $ChargingCertificate->agreement_date;
							}
							if(!empty($arrUpdate))
							{
								$this->Installation->updateAll($arrUpdate,array('project_id'=>$applyOnlinesData->project_id));
								$this->ChargingCertificate->updateAll(array('update_execution'=>'1'),array('id'=>$ChargingCertificate->id));
							}
						}
						$this->Flash->success('Data saved successfully.');
						return $this->redirect(URL_HTTP.'/apply-onlines/chargingcertificate/'.$encode_id);
					}
				}
				$ChargingCertificate = $ChargingCertificate;
			} else {
				$ChargingCertificate 			= $this->ChargingCertificate->getReportData($id);
				$rid 							= isset($ChargingCertificate['id'])?encode($ChargingCertificate['id']):"";
				if (empty($rid)) {
					$ChargingCertificate 		= $this->ChargingCertificate->newEntity();
					$ChargingCertificate->pv_capacity_phase = $application_data->transmission_line;
				}
				$ChargingCertificate->created 	= isset($ChargingCertificate['created'])?$ChargingCertificate['created']:$this->NOW();
			}
		}
		$applyOnlinesData->aid 		= $this->ApplyOnlines->GenerateApplicationNo($applyOnlinesData);
		$RegistrationScheme 		= array();
		//$RegistrationScheme 		= new stdClass();
		$RegistrationScheme 		= $this->CeiApplicationDetails->find('all',array('conditions'=>array('application_id'=>$applyOnlinesData->id)))->first();

		if(!empty($RegistrationScheme)){
			$RegistrationScheme->aid 	= $RegistrationScheme->drawing_app_no;
		}
		else{
			@$RegistrationScheme->aid 	= '';
			@$RegistrationScheme->created 	= '';
		}
		$fesibility 				= $this->FesibilityReport->getReportData($id);
		$fesibility->aid 			= $this->FesibilityReport->GenerateApplicationNo($fesibility,$applyOnlinesData->state);
		$applicationSubmission 		= $this->ApplyOnlineApprovals->find('all',array('conditions'=>array('application_id'=>$applyOnlinesData->id,'stage'=>$this->ApplyOnlineApprovals->APPLICATION_SUBMITTED)))->first();

		$this->set('id',$encode_id);
		$this->set('rid',$rid);
		$this->set('ApplyOnlines',$applyOnlinesData);
		$this->set('phasearray',array("1"=>"Single Phase","3"=>"3 Phase"));
		$this->set('RegistrationScheme',$RegistrationScheme);
		$this->set('ChargingCertificate',$ChargingCertificate);
		$this->set('applicationSubmission',$applicationSubmission);
		$this->set('fesibility',$fesibility);
		$this->set("pageTitle","Charging Certificate for Net-meter Installation");
	}

	private function SetApplicationStatus($status,$id,$reason="")
	{
		$member_id 			= $this->Session->read('Members.id');
		$applyOnlinesData 	= $this->ApplyOnlines->viewApplication($id);
		if ($this->ApplyOnlineApprovals->validateNewStatus($status,$applyOnlinesData->application_status) || $status=='CANCELLED_REOPEN')
		{
			if($status!='CANCELLED_REOPEN')
			{
			$arrData 		= array("application_status"=>$status);
			$this->ApplyOnlines->updateAll($arrData,['id' => $id]);
			}
			$sms_text 		= '';
			$subject 		= '';
			$EmailVars 		= array();
			$sms_template 	= '';
			if($status==$this->ApplyOnlineApprovals->DRAWING_APPLIED)
			{
				$sms_text 			= str_replace('##geda_application_no##',$applyOnlinesData->geda_application_no,DRAWING_APPLIED);
				$sms_template 		= 'DRAWING_APPLIED';
				$subject 			= "[REG: GEDA Application No. ".$applyOnlinesData->geda_application_no."] CEI Drawing Applied";
				$CUSTOMER_NAME 		= trim($applyOnlinesData->customer_name_prefixed." ".$applyOnlinesData->name_of_consumer_applicant." ".$applyOnlinesData->last_name." ".$applyOnlinesData->third_name);
				$cei_data 			= $this->CeiApplicationDetails->find('all',array('conditions'=>array('application_id'=>$id)))->first();
				$drawing_number 	= '';
				if(!empty($cei_data))
				{
					$drawing_number = $cei_data->drawing_app_no;
				}
				$EmailVars 			= array("CUSTOMER_NAME"=>$CUSTOMER_NAME,'CEI_DRAWING_NUMBER'=>$drawing_number);
				$template_applied 	= 'drawing_applied';
			}
			else if($status==$this->ApplyOnlineApprovals->CEI_APP_NUMBER_APPLIED)
			{
				$sms_text 			= str_replace('##geda_application_no##',$applyOnlinesData->geda_application_no,CEI_APP_NUMBER_APPLIED);
				$sms_template 		= 'CEI_APP_NUMBER_APPLIED';
				$subject 			= "[REG: GEDA Application No. ".$applyOnlinesData->geda_application_no."] CEI Application Number";
				$CUSTOMER_NAME 		= trim($applyOnlinesData->customer_name_prefixed." ".$applyOnlinesData->name_of_consumer_applicant." ".$applyOnlinesData->last_name." ".$applyOnlinesData->third_name);
				$cei_data	        = $this->CeiApplicationDetails->find('all',array('conditions'=>array('application_id'=>$id)))->first();
				$cei_app_no         = '';
				if(!empty($cei_data))
				{
					$cei_app_no     = $cei_data->cei_app_no;
				}
				$EmailVars 			= array("CUSTOMER_NAME"=>$CUSTOMER_NAME,'CEI_APPLICATION_NUMBER'=>$cei_app_no);
				$template_applied 	= 'ceinumber_applied';
			}
			else if($status==$this->ApplyOnlineApprovals->FEASIBILITY_APPROVAL)
			{
				$sms_text 			= str_replace('##geda_application_no##',$applyOnlinesData->geda_application_no,FEASIBILITY_APPROVAL);
				$sms_template 		= 'FEASIBILITY_APPROVAL';
				$subject 			= "[REG: GEDA Application No. ".$applyOnlinesData->geda_application_no."] Technical Feasibility Report";
				$CUSTOMER_NAME 		= trim($applyOnlinesData->customer_name_prefixed." ".$applyOnlinesData->name_of_consumer_applicant." ".$applyOnlinesData->last_name." ".$applyOnlinesData->third_name);
				$EmailVars 			= array("CUSTOMER_NAME"=>$CUSTOMER_NAME,'TEXT_FESIBILITY'=>'done');
				$template_applied 	= 'fesibility_approval';
			}
			else if($status==$this->ApplyOnlineApprovals->FIELD_REPORT_REJECTED)
			{
				$sms_text 			= str_replace('##geda_application_no##',$applyOnlinesData->geda_application_no,FIELD_REPORT_REJECTED);
				$sms_template 		= 'FIELD_REPORT_REJECTED';
				$subject 			= "[REG: GEDA Application No. ".$applyOnlinesData->geda_application_no."] Technical Feasibility Report";
				$CUSTOMER_NAME 		= trim($applyOnlinesData->customer_name_prefixed." ".$applyOnlinesData->name_of_consumer_applicant." ".$applyOnlinesData->last_name." ".$applyOnlinesData->third_name);
				$EmailVars 			= array("CUSTOMER_NAME"=>$CUSTOMER_NAME,'TEXT_FESIBILITY'=>'rejected');
				$template_applied 	= 'fesibility_approval';
			}
			else if($status==$this->ApplyOnlineApprovals->APPROVED_FROM_CEI)
			{
				$sms_text 			= str_replace('##geda_application_no##',$applyOnlinesData->geda_application_no,APPROVED_FROM_CEI);
				$sms_template 		= 'APPROVED_FROM_CEI';
				$subject 			= "[REG: GEDA Application No. ".$applyOnlinesData->geda_application_no."] CEI Drawing Approved";
				$CUSTOMER_NAME 		= trim($applyOnlinesData->customer_name_prefixed." ".$applyOnlinesData->name_of_consumer_applicant." ".$applyOnlinesData->last_name." ".$applyOnlinesData->third_name);
				$cei_data 			= $this->CeiApplicationDetails->find('all',array('conditions'=>array('application_id'=>$id)))->first();
				$drawing_number 	= '';
				if(!empty($cei_data))
				{
					$drawing_number = $cei_data->drawing_app_no;
				}
				$EmailVars 			= array("CUSTOMER_NAME"=>$CUSTOMER_NAME,'CEI_DRAWING_NUMBER'=>$drawing_number);
				$template_applied 	= 'cei_approval';
			}
			else if($status==$this->ApplyOnlineApprovals->METER_INSTALLATION)
			{
				$sms_text 			= str_replace('##geda_application_no##',$applyOnlinesData->geda_application_no,METER_INSTALLATION);
				$sms_template 		= 'METER_INSTALLATION';
				$subject 			= "[REG: GEDA Application No. ".$applyOnlinesData->geda_application_no."] Meter Installation Report";
				$CUSTOMER_NAME 		= trim($applyOnlinesData->customer_name_prefixed." ".$applyOnlinesData->name_of_consumer_applicant." ".$applyOnlinesData->last_name." ".$applyOnlinesData->third_name);
				$EmailVars 			= array("CUSTOMER_NAME"=>$CUSTOMER_NAME,'METER_INSTALLATION'=>'done');
				$template_applied 	= 'meter_installation';

			}
			else if($status==$this->ApplyOnlineApprovals->CEI_INSPECTION_APPROVED)
			{
				$sms_text 			= str_replace('##geda_application_no##',$applyOnlinesData->geda_application_no,CEI_INSPECTION_APPROVED);
				$sms_template 		= 'CEI_INSPECTION_APPROVED';
				$subject 			= "[REG: GEDA Application No. ".$applyOnlinesData->geda_application_no."] Inspection From CEI";
				$CUSTOMER_NAME 		= trim($applyOnlinesData->customer_name_prefixed." ".$applyOnlinesData->name_of_consumer_applicant." ".$applyOnlinesData->last_name." ".$applyOnlinesData->third_name);
				$cei_data 			= $this->CeiApplicationDetails->find('all',array('conditions'=>array('application_id'=>$id)))->first();
				$cei_app_no 	= '';
				if(!empty($cei_data))
				{
					$cei_app_no = $cei_data->cei_app_no;
				}
				$EmailVars 			= array("CUSTOMER_NAME"=>$CUSTOMER_NAME,'CEI_APPLICATION_NUMBER'=>$cei_app_no);
				$template_applied 	= 'cei_inspection';
			}
			else if($status==$this->ApplyOnlineApprovals->APPROVED_FROM_GEDA)
			{
				$sms_text 			= str_replace('##application_no##',$applyOnlinesData->application_no, GEDA_APPROVAL);
				$sms_template 		= 'GEDA_APPROVAL';
				$subject 			= "[REG: Application No. ".$applyOnlinesData->application_no."] Accepted From GEDA";
				$CUSTOMER_NAME 		= trim($applyOnlinesData->customer_name_prefixed." ".$applyOnlinesData->name_of_consumer_applicant." ".$applyOnlinesData->last_name." ".$applyOnlinesData->third_name);
				$EmailVars 			= array("CUSTOMER_NAME"=>$CUSTOMER_NAME,'application_no'=>$applyOnlinesData->application_no);
				$template_applied 	= 'geda_approval';
			}
			else if($status==$this->ApplyOnlineApprovals->REJECTED_FROM_GEDA)
			{
				$sms_text 			= str_replace('##application_no##',$applyOnlinesData->application_no, GEDA_REJECTED);
				$sms_template 		= 'GEDA_REJECTED';
				$subject 			= "[REG: Application No. ".$applyOnlinesData->application_no."] Rejected From GEDA";
				$CUSTOMER_NAME 		= trim($applyOnlinesData->customer_name_prefixed." ".$applyOnlinesData->name_of_consumer_applicant." ".$applyOnlinesData->last_name." ".$applyOnlinesData->third_name);
				$EmailVars 			= array("CUSTOMER_NAME"=>$CUSTOMER_NAME,'application_no'=>$applyOnlinesData->application_no);
				$template_applied 	= 'rejected_geda';
			}
			else if($status==$this->ApplyOnlineApprovals->CLAIM_SUBSIDY)
			{
				$sms_text 			= str_replace('##geda_application_no##',$applyOnlinesData->geda_application_no,CLAIM_SUBSIDY);
				$sms_template 		= 'CLAIM_SUBSIDY';
				$subject 			= "[REG: GEDA Application No. ".$applyOnlinesData->geda_application_no."] Subsidy Claimed";
				$CUSTOMER_NAME 		= trim($applyOnlinesData->customer_name_prefixed." ".$applyOnlinesData->name_of_consumer_applicant." ".$applyOnlinesData->last_name." ".$applyOnlinesData->third_name);
				$EmailVars 			= array("CUSTOMER_NAME"=>$CUSTOMER_NAME,'geda_no'=>$applyOnlinesData->geda_application_no);
				$template_applied 	= 'claimed_subsidy';
			}
			else if($status=='CANCELLED_REOPEN')
			{
				$sms_text 			= str_replace('##geda_application_no##',$applyOnlinesData->geda_application_no,CANCELLED_REOPEN);
				$sms_template 		= 'CANCELLED_REOPEN';
				$subject 			= "[REG: GEDA Application No. ".$applyOnlinesData->geda_application_no."] Application Reopened";
				$CUSTOMER_NAME 		= trim($applyOnlinesData->customer_name_prefixed." ".$applyOnlinesData->name_of_consumer_applicant." ".$applyOnlinesData->last_name." ".$applyOnlinesData->third_name);
				$EmailVars 			= array("CUSTOMER_NAME"=>$CUSTOMER_NAME,'geda_no'=>$applyOnlinesData->geda_application_no);
				$template_applied 	= 'cancelled_reopen';
			}
			if($sms_text!='')
			{
				if(!empty($applyOnlinesData->consumer_mobile))
				{
					$this->ApplyOnlines->sendSMS($id,$applyOnlinesData->consumer_mobile,$sms_text,$sms_template);
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
			$this->ApplyOnlineApprovals->saveStatus($id,$status,$member_id,$reason);
		}
	}

	/**
	 *
	 * workcompletion
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to work completion
	 *
	 */
	public function workcompletion($id= null)
	{
		$this->setMemberArea();
		$this->validateMemberPermission("WORK_COMPLETION");
		if(empty($id)) {
			$this->Flash->error('Please select valid application.');
			return $this->redirect(URL_HTTP.'/apply-online-list');
		} else {
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->ApplyOnlines->viewApplication($id);
			if ($this->request->is('post') || $this->request->is('put')) {
				$rid = 0;
				if (isset($this->request->data['id']) && !empty($this->request->data['id'])) {
					$rid 		= intval(decode($this->request->data['id']));
					$NEWRECORD 	= false;
					if (!empty($rid)) {
						$WorkCompletion 		= $this->WorkCompletion->get($rid);
						$WorkCompletion 		= $this->WorkCompletion->patchEntity($WorkCompletion,$this->request->data,['validate'=>'Add']);
						$WorkCompletion->id 	= $rid;
					} else {
						$WorkCompletion 		= $this->WorkCompletion->newEntity($this->request->data,['validate'=>'Add']);
						$NEWRECORD 					= true;
					}
				} else {
					$WorkCompletion 					= $this->WorkCompletion->newEntity($this->request->data,['validate'=>'Add']);
					$WorkCompletion->application_id 	= $id;
					$WorkCompletion->created 			= $this->NOW();
					$WorkCompletion->created_by 		= $this->Session->read('Members.id');
					$NEWRECORD 							= true;
				}
				$WorkCompletion->techspec 			= serialize($this->request->data['techspec']);
				$WorkCompletion->invertors 			= serialize($this->request->data['invertors']);
				$WorkCompletion->modified 			= $this->NOW();
				$WorkCompletion->modified_by 		= $this->Session->read('Members.id');
				if ($this->WorkCompletion->save($WorkCompletion)) {
					if (empty($rid)) {
						$rid = $WorkCompletion->id;
					}
					if ($NEWRECORD) {
						$this->SetApplicationStatus($this->ApplyOnlineApprovals->WORK_STARTS,$id);
					}
					$this->SaveWorkCompletionDocuments($id,$rid);
					$this->Flash->success('Work completion report submitted succesfully.');
					return $this->redirect(URL_HTTP.'/apply-onlines/workcompletion/'.$encode_id);
				}
				$WorkCompletion = $WorkCompletion;
			} else {
				$WorkCompletion = $this->WorkCompletion->getReportData($id);
				$rid 				= isset($WorkCompletion['id'])?encode($WorkCompletion['id']):"";
				if (empty($WorkCompletion)) {
					$WorkCompletion 				= $this->WorkCompletion->newEntity(array());
					$WorkCompletion->created 		= $this->NOW();
				} else {
					$WorkCompletion->aid 			= $this->WorkCompletion->GenerateApplicationNo($WorkCompletion,$applyOnlinesData->state);
					$WorkCompletion->documents 		= $this->WorkCompletionDocument->getReportData($WorkCompletion['id']);
				}
			}
		}
		$applyOnlinesData->aid 	= $this->ApplyOnlines->GenerateApplicationNo($applyOnlinesData);
		$this->set('id',$encode_id);
		$this->set('rid',$rid);
		$this->set('ApplyOnlines',$applyOnlinesData);
		$this->set('workcompletion',$WorkCompletion);
		$this->set('ModuleTypes',$this->WorkCompletion->ModuleTypes);
		$this->set('InverterTypes',$this->WorkCompletion->InverterTypes);
		$this->set("pageTitle","Work Completion Report");
	}

	/*
	 * API for save document of work completion from
	 * @param mixed What page to display
	 * @return void
	 */
	private function SaveWorkCompletionDocuments($id,$rid) {
		$image_path = APPLYONLINE_PATH.$id.'/workcompletion/';
		if(!file_exists($image_path)) {
			@mkdir($image_path, 0777,true);
		}
		if(!empty($this->request->data['document_1'])) {
			$file_name = $this->file_upload($image_path,$this->request->data['document_1'],false,0,0,$image_path,'tech_spec_');
			if (!empty($file_name) && file_exists($image_path.$file_name)) {
				$WorkCompletionDocument 					= $this->WorkCompletionDocument->newEntity();
				$WorkCompletionDocument->application_id 	= $id;
				$WorkCompletionDocument->rid 				= $rid;
				$WorkCompletionDocument->filename 			= $file_name;
				$WorkCompletionDocument->status 			= 1;
				$WorkCompletionDocument->created 			= $this->NOW();
				$WorkCompletionDocument->modified 			= $this->NOW();
				if (!empty($this->Session->read('Members.id'))) {
					$WorkCompletionDocument->created_by 	= $this->Session->read('Members.id');
					$WorkCompletionDocument->modified_by 	= $this->Session->read('Members.id');
				} else {
					$WorkCompletionDocument->created_by 	= $this->Session->read('Customers.id');
					$WorkCompletionDocument->modified_by 	= $this->Session->read('Customers.id');
				}
				$this->WorkCompletionDocument->save($WorkCompletionDocument);
			}
		}
		if(!empty($this->request->data['document_2'])) {
			$file_name = $this->file_upload($image_path,$this->request->data['document_2'],false,0,0,$image_path,'tech_spec_1_');
			if (!empty($file_name) && file_exists($image_path.$file_name)) {
				$WorkCompletionDocument 					= $this->WorkCompletionDocument->newEntity();
				$WorkCompletionDocument->application_id 	= $id;
				$WorkCompletionDocument->rid 				= $rid;
				$WorkCompletionDocument->filename 			= $file_name;
				$WorkCompletionDocument->status 			= 1;
				$WorkCompletionDocument->created 			= $this->NOW();
				$WorkCompletionDocument->modified 			= $this->NOW();
				if (!empty($this->Session->read('Members.id'))) {
					$WorkCompletionDocument->created_by 	= $this->Session->read('Members.id');
					$WorkCompletionDocument->modified_by 	= $this->Session->read('Members.id');
				} else {
					$WorkCompletionDocument->created_by 	= $this->Session->read('Customers.id');
					$WorkCompletionDocument->modified_by 	= $this->Session->read('Customers.id');
				}
				$this->WorkCompletionDocument->save($WorkCompletionDocument);
			}
		}
	}

	/*
	 * Function for CreateMyProject
	 * @param mixed What page to display
	 * @return void
	 */
	private function CreateMyProject($application_id=0,$CreateMyProject=true,$is_fesibility=false)
	{
		if (!empty($application_id))
		{
			$applyOnlinesData = $this->ApplyOnlines->get($application_id);
			$applyOnlinesData->aid 	= $this->ApplyOnlines->GenerateApplicationNo($applyOnlinesData);
			$proj_name 		= "APPLICATION - ".$applyOnlinesData->aid;
			$lat 			= $applyOnlinesData->lattitue;
			$lon 			= $applyOnlinesData->longitude;
			$roof_area 		= ($applyOnlinesData->pv_capacity * 12);
			$c_type 		= $applyOnlinesData->category;
			$energy_con 	= !empty($applyOnlinesData->energy_con)?$applyOnlinesData->energy_con:0;
			$area_type 		= '2002';
			$bill 			= $applyOnlinesData->bill;
			$backup_type 	= 0;
			$hours 			= 0;
			$location_flag 	= 'auto';
			$customer_id 	= $applyOnlinesData->customer_id;
			$installer_id 	= $applyOnlinesData->installer_id;

			$address 			= $applyOnlinesData->address1;
			$city 				= $applyOnlinesData->city;
			$state 				= $applyOnlinesData->state;
			$state_short_name 	= $applyOnlinesData->state;
			$pincode 			= $applyOnlinesData->pincode;
			$country 			= $applyOnlinesData->country;
			$SendQuery 			= true;
			$this->request->data['Projects']['name'] 	= $proj_name;
			if (!empty($applyOnlinesData->project_id)) {
				$this->request->data['Projects']['id']	= $applyOnlinesData->project_id;
				$project_details= $this->Projects->find('all',array('conditions'=>array('id'=>$applyOnlinesData->project_id)))->first();
				$this->request->data['proj_name'] 		= $project_details->name;
				$SendQuery 								= false;
			}
			$pv_app_capacity 	= $applyOnlinesData->pv_capacity;
			if($is_fesibility == true)
			{
				$fesibility 	= $this->FesibilityReport->find('all',array('conditions'=>array('application_id'=>$application_id)))->first();
				$pv_app_capacity= $fesibility->recommended_capacity_by_discom;
			}
			$this->request->data['latitude']						= $lat;
			$this->request->data['Projects']['latitude']			= $lat;
			$this->request->data['longitude']						= $lon;
			$this->request->data['Projects']['longitude']			= $lon;
			$this->request->data['customer_type']					= $c_type;
			$this->request->data['project_type']					= $c_type;
			$this->request->data['Projects']['customer_type']		= $c_type;
			$this->request->data['area']							= $roof_area;
			$this->request->data['Projects']['area']				= $roof_area;
			$this->request->data['area_type'] 						= $area_type;
			$this->request->data['bill'] 							= $bill;
			$this->request->data['avg_monthly_bill'] 				= $bill;
			$this->request->data['backup_type']						= $backup_type;
			$this->request->data['usage_hours']						= $hours;
			$this->request->data['Projects']['usage_hours']			= $hours;
			$this->request->data['energy_con']						= $energy_con;
			$this->request->data['Projects']['estimated_kwh_year'] 	= $energy_con;
			$this->request->data['recommended_capacity']			= $pv_app_capacity;
			$this->request->data['Projects']['recommended_capacity']= $pv_app_capacity;
			$this->request->data['address']							= $address;
			$this->request->data['city']							= $city;
			$this->request->data['state']							= $state;
			$this->request->data['state_short_name']				= $state_short_name;
			$this->request->data['country']							= $country;
			$this->request->data['postal_code']						= $pincode;
			$ses_login_type 										= $this->Session->read('Customers.login_type');
			$this->request->data['ses_login_type']					= $ses_login_type;
			$result 												= $this->Projects->getprojectestimationV2($this->request->data,$customer_id,$CreateMyProject);

			/** Update Project Ref. No in Table */
			if (empty($applyOnlinesData->project_id)) {
				$arrData 	= array("project_id"=>$result['proj_id']);
				$this->ApplyOnlines->updateAll($arrData,['id' => $application_id]);
			}
			/** Update Project Ref. No in Table */
			//$this->ApplyOnlines->updateAll(array('pv_capacity'=>$pv_app_capacity),['id' => $application_id]);
			/** Send Query to Installer */
			if ($SendQuery) $this->SendQueryToInstaller($result['proj_id'],$installer_id);
			/** Send Query to Installer */

			return $result;
		}
	}

	/*
	 * Function for GetProjectEstimation
	 * @param mixed What page to display
	 * @return void
	 */
	private function GetProjectEstimation($application_id=0)
	{
		if (!empty($application_id))
		{
			$applyOnlinesData = $this->ApplyOnlines->get($application_id);
			$applyOnlinesData->aid 	= $this->ApplyOnlines->GenerateApplicationNo($applyOnlinesData);
			$proj_name 		= "APPLICATION - ".$applyOnlinesData->aid;
			$lat 			= $applyOnlinesData->lattitue;
			$lon 			= $applyOnlinesData->longitude;
			$roof_area 		= ($applyOnlinesData->pv_capacity * 12);
			$c_type 		= $applyOnlinesData->category;
			$energy_con 	= !empty($applyOnlinesData->energy_con)?$applyOnlinesData->energy_con:0;
			$area_type 		= '2002';
			$bill 			= $applyOnlinesData->bill;
			$backup_type 	= 0;
			$hours 			= 0;
			$location_flag 	= 'auto';
			$customer_id 	= $applyOnlinesData->customer_id;
			$installer_id 	= $applyOnlinesData->installer_id;

			$address 			= $applyOnlinesData->address1;
			$city 				= $applyOnlinesData->city;
			$state 				= $applyOnlinesData->state;
			$state_short_name 	= $applyOnlinesData->state;
			$pincode 			= $applyOnlinesData->pincode;
			$country 			= $applyOnlinesData->country;

			if (!empty($applyOnlinesData->project_id)) {
				$this->request->data['Projects']['id']				= $applyOnlinesData->project_id;
			}
			$this->request->data['latitude']						= $lat;
			$this->request->data['Projects']['latitude']			= $lat;
			$this->request->data['longitude']						= $lon;
			$this->request->data['Projects']['longitude']			= $lon;
			$this->request->data['customer_type']					= $c_type;
			$this->request->data['project_type']					= $c_type;
			$this->request->data['Projects']['customer_type']		= $c_type;
			$this->request->data['area']							= $roof_area;
			$this->request->data['Projects']['area']				= $roof_area;
			$this->request->data['area_type'] 						= $area_type;
			$this->request->data['bill'] 							= $bill;
			$this->request->data['avg_monthly_bill'] 				= $bill;
			$this->request->data['backup_type']						= $backup_type;
			$this->request->data['usage_hours']						= $hours;
			$this->request->data['Projects']['usage_hours']			= $hours;
			$this->request->data['energy_con']						= $energy_con;
			$this->request->data['Projects']['estimated_kwh_year'] 	= $energy_con;
			$this->request->data['recommended_capacity']			= $applyOnlinesData->pv_capacity;
			$this->request->data['Projects']['recommended_capacity']= $applyOnlinesData->pv_capacity;
			$this->request->data['address']							= $address;
			$this->request->data['city']							= $city;
			$this->request->data['state']							= $state;
			$this->request->data['state_short_name']				= $state_short_name;
			$this->request->data['country']							= $country;
			$this->request->data['postal_code']						= $pincode;
			$result 												= $this->Projects->getprojectestimationV2($this->request->data,$customer_id,false);
			return $result;
		}
	}

	/**
	 *
	 * SendQueryToInstaller
	 *
	 * Behaviour : private
	 *
	 * Parameter : $project_id, $installer_id
	 *
	 * @defination : Method is use to send query email.
	 *
	 */
	private function SendQueryToInstaller($project_id,$installer_id)
	{
		$this->autoRender 	= false;
		if(!empty($project_id) && !empty($installer_id))
		{
			$insProjData['InstallerProjects']['installer_id']	= $installer_id;
			$insProjData['InstallerProjects']['project_id']		= $project_id;
			$insProjEntity 			= $this->InstallerProjects->newEntity($insProjData);
			$insProjEntity->created = $this->NOW();
			$this->InstallerProjects->save($insProjEntity);
			$custProjectData = $this->CustomerProjects
									->find('all')
									->select(['Customer.name','Parameter.para_value','Project.latitude','Project.longitude','Customer.mobile','Customer.email','Customer.city','Customer.state','Project.name','Project.area','Project.city','Project.state','Project.avg_monthly_bill','Project.estimated_kwh_year','Project.backup_type','Project.usage_hours','Project.name','Project.estimated_cost','Project.estimated_cost_subsidy','Project.payback','Project.avg_generate','Project.recommended_capacity','Project.maximum_capacity'])
									->join([
										'Project' => [
											'table' => 'projects',
											'type' => 'INNER',
											'conditions' => ['Project.id = CustomerProjects.project_id']
										],
										'Customer' => [
											'table' => 'customers',
											'type' => 'INNER',
											'conditions' => ['Customer.id = CustomerProjects.customer_id']
										],
										'Parameter' => [
											'table' => 'parameters',
											'type' => 'LEFT',
											'conditions' => ['Parameter.para_id = Project.customer_type']
										]])
									->where(array('CustomerProjects.project_id' =>$project_id))->first();

			$backup = (isset($custProjectData['Project']['backup_type'])?$custProjectData['Project']['backup_type']:'');
			$custProjectData['Project']['backup_type_name'] = '';
			if($backup == $this->Projects->BACKUP_TYPE_GENERATOR) {
			   $custProjectData['Project']['backup_type_name'] = "Generator";
			}elseif($backup == $this->Projects->BACKUP_TYPE_INVERTER) {
				$custProjectData['Project']['backup_type_name'] = "Inverter";
			} else {
				$custProjectData['Project']['backup_type_name'] = "No";
			}
			$Installers = $this->Installers->find('all',array('conditions'=>array('id' =>$installer_id)))->toArray();
			$this->sendQueryEmail($custProjectData, $Installers);
		}
	}

	/**
	 *
	 * sendQueryEmail
	 *
	 * Behaviour : private
	 *
	 * Parameter : $projectDetail(array), $installerList(array)
	 *
	 * @defination : Method is use to send query email.
	 *
	 */
	private function sendQueryEmail($projectDetail, $installerList)
	{
		if(!empty($projectDetail) && !empty($installerList))
		{
			$to			= SEND_QUERY_EMAIL;
			$subject	= "Project Query";
			$email 		= new Email('default');
			$email->profile('default');
			$email->viewVars(array('project_detail' => $projectDetail, 'installer_list' => $installerList));
			$email->template('send_query', 'default')
				->emailFormat('html')
				->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
				->to($to)
				->subject(Configure::read('EMAIL_ENV').$subject)
				->send();
		}
	}

	/*
	 * Function for inspectionstage
	 * @param mixed What page to display
	 * @return void
	 */
	public function inspectionstage()
	{
		$this->autoRender 	= false;
		$stage 				= (isset($this->request->data['approval_type'])?$this->request->data['approval_type']:0);
		$getexistingdata 	= (isset($this->request->data['show-prev-report'])?$this->request->data['show-prev-report']:0);
		if (!$getexistingdata) {
			switch($stage)
			{
				case 1:
				{
					$id 	= (isset($this->request->data['appid'])?decode($this->request->data['appid']):0);
					$status = (isset($this->request->data['application_status'])?($this->request->data['application_status']):0);
					$reason = (isset($this->request->data['reason'])?($this->request->data['reason']):"");
					$member_type 			= $this->Session->read('Members.member_type');
					if ($member_type == $this->ApplyOnlines->CEI  && $status > 0) {
						$status = ($status == 1)?$this->ApplyOnlineApprovals->APPROVED_FROM_CEI:$this->ApplyOnlineApprovals->REJECTED_FROM_CEI;
						$this->SetApplicationStatus($status,$id,$reason);
					}
					$this->ApiToken->SetAPIResponse('msg', 'Applicaton status updated.');
					$this->ApiToken->SetAPIResponse('type','ok');
					break;
				}
				case 2:
				{
					$id 	= (isset($this->request->data['appid'])?decode($this->request->data['appid']):0);
					$status = (isset($this->request->data['application_status'])?($this->request->data['application_status']):0);
					$reason = (isset($this->request->data['reason'])?($this->request->data['reason']):"");
					$member_type = $this->Session->read('Members.member_type');
					if ($member_type == $this->ApplyOnlines->DISCOM && $status > 0) {
						$status = ($status == 1)?$this->ApplyOnlineApprovals->APPROVED_FROM_DISCOM:$this->ApplyOnlineApprovals->REJECTED_FROM_DISCOM;
						$this->SetApplicationStatus($status,$id,$reason);
					}
					$this->ApiToken->SetAPIResponse('msg', 'Applicaton status updated.');
					$this->ApiToken->SetAPIResponse('type','ok');
					break;
				}
				case 3:
				{
					$id 	= (isset($this->request->data['appid'])?decode($this->request->data['appid']):0);
					$status = (isset($this->request->data['application_status'])?($this->request->data['application_status']):0);
					$reason = (isset($this->request->data['reason'])?($this->request->data['reason']):"");
					$member_type = $this->Session->read('Members.member_type');
					$member_id = $this->Session->read('Members.id');
					if ($member_type == $this->ApplyOnlines->JREDA && $status > 0) {
						$status = ($status == 1)?$this->ApplyOnlineApprovals->APPROVED_FROM_JREDA:$this->ApplyOnlineApprovals->REJECTED_FROM_JREDA;
						$this->SetApplicationStatus($status,$id,$reason);
					}
					$this->InspectionReport->saveInspectionReport($stage,$this->request->data,$member_id);
					$this->ApiToken->SetAPIResponse('msg', 'Applicaton status updated.');
					$this->ApiToken->SetAPIResponse('type','ok');
					break;
				}
				case 4:
				{
					$id 	= (isset($this->request->data['appid'])?decode($this->request->data['appid']):0);
					$status = (isset($this->request->data['application_status'])?($this->request->data['application_status']):0);
					$reason = (isset($this->request->data['reason'])?($this->request->data['reason']):"");
					$member_type 	= $this->Session->read('Members.member_type');
					$member_id 		= $this->Session->read('Members.id');
					if ($member_type == $this->ApplyOnlines->DISCOM && $status > 0) {
						$status = ($status == 1)?$this->ApplyOnlineApprovals->FEASIBILITY_APPROVAL:$this->ApplyOnlineApprovals->FIELD_REPORT_REJECTED;
						$this->SetApplicationStatus($status,$id,$reason);
						$arrData 	= array("division_approved_by"=>$member_id,
											"division_approved_date"=>$this->NOW());
						$this->FesibilityReport->updateAll($arrData,['application_id' => $id]);
						if ($status == $this->ApplyOnlineApprovals->FEASIBILITY_APPROVAL) {
							//$this->CreateMyProject($id,true,true);
							//$this->SendLetterOfApproval($id);

							$this->SetApplicationStatus($this->ApplyOnlineApprovals->SUBSIDY_AVAILIBILITY,$id);
							$ApplicationData = $this->ApplyOnlines->viewApplication($id);
							$this->sendMailToCustomer($ApplicationData->toArray());
							$this->ApplyOnlineApprovals->saveStatus($ApplicationData->id,$ApplicationData->application_status,$member_id);
						}
					}
					$this->ApiToken->SetAPIResponse('msg', 'Applicaton status updated.');
					$this->ApiToken->SetAPIResponse('type','ok');
					break;
				}
				case 5:
				{
					$id 	= (isset($this->request->data['appid'])?decode($this->request->data['appid']):0);
					$status = (isset($this->request->data['application_status'])?($this->request->data['application_status']):0);
					$member_type 			= $this->Session->read('Members.member_type');
					$reason = (isset($this->request->data['reason'])?($this->request->data['reason']):"");
					if ($member_type == $this->ApplyOnlines->CEI  && $status > 0) {
						$status = ($status == 1)?$this->ApplyOnlineApprovals->CEI_INSPECTION_APPROVED:$this->ApplyOnlineApprovals->REJECTED_FROM_CEI;
						$this->SetApplicationStatus($status,$id,$reason);
					}
					$this->ApiToken->SetAPIResponse('msg', 'Applicaton status updated.');
					$this->ApiToken->SetAPIResponse('type','ok');
					break;
				}
				case 51:
				{
					$id 	= (isset($this->request->data['appid'])?decode($this->request->data['appid']):0);
					$status = (isset($this->request->data['application_status'])?($this->request->data['application_status']):0);
					//$reason = (isset($this->request->data['reason'])?($this->request->data['reason']):"");
					$drawing_app_no 		= (isset($this->request->data['drawing_app_no'])?($this->request->data['drawing_app_no']):"");
					$drawing_app_status 	= (isset($this->request->data['drawing_app_status'])?($this->request->data['drawing_app_status']):"");


					$reason 				= '';
					$exist_cei 			= $this->CeiApplicationDetails->find('all',array('conditions'=>array('application_id'=>$id)))->first();
					$ceiappEntity       = $this->request->data;
					if(empty($exist_cei))
					{
						$ceiappEntity					= $this->CeiApplicationDetails->newEntity($this->request->data);
						$ceiappEntity->application_id 	= $id;
						$ceiappEntity->created 			= $this->NOW();
						$ceiappEntity->updated 			= $this->NOW();
					}
					else
					{
						$getceidata 					= $this->CeiApplicationDetails->get($exist_cei->id);
						$ceiappEntity					= $this->CeiApplicationDetails->patchEntity($getceidata,$this->request->data);
						$ceiappEntity->updated 			= $this->NOW();
					}
					$ceiappEntity->status 			= '1';
					$status 						= $this->ApplyOnlineApprovals->DRAWING_APPLIED;
					if($this->CeiApplicationDetails->save($ceiappEntity))
					{
						$this->SetApplicationStatus($status,$id,$reason);
						if(strtolower($drawing_app_status) == 'completed')
						{
							$status 				= $this->ApplyOnlineApprovals->APPROVED_FROM_CEI;
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
					$exist_cei 				= $this->CeiApplicationDetails->find('all',array('conditions'=>array('application_id'=>$id)))->first();
					$ceiappEntity       	= $this->request->data;
					if(empty($exist_cei))
					{
						$ceiappEntity					= $this->CeiApplicationDetails->newEntity($this->request->data);
						$ceiappEntity->application_id 	= $id;
						$ceiappEntity->created 			= $this->NOW();
						$ceiappEntity->updated 			= $this->NOW();
					}
					else
					{
						$getceidata 					= $this->CeiApplicationDetails->get($exist_cei->id);
						$ceiappEntity					= $this->CeiApplicationDetails->patchEntity($getceidata,$this->request->data);
						$ceiappEntity->updated 			= $this->NOW();
					}
					$ceiappEntity->status 				= '2';
					$status 							= $this->ApplyOnlineApprovals->CEI_APP_NUMBER_APPLIED;
					if($this->CeiApplicationDetails->save($ceiappEntity))
					{
						$this->SetApplicationStatus($status,$id,$reason);
						if(strtolower($cei_app_status) == 'completed')
						{
							$status 				= $this->ApplyOnlineApprovals->CEI_INSPECTION_APPROVED;
							$this->SetApplicationStatus($status,$id,$reason);
						}
					}
						//$status = ($status == 1)?$this->ApplyOnlineApprovals->APPROVED_FROM_CEI:$this->ApplyOnlineApprovals->REJECTED_FROM_CEI;
					$this->ApiToken->SetAPIResponse('msg', 'Applicaton status updated.');
					$this->ApiToken->SetAPIResponse('type','ok');
					break;
				}
				case 7:
				{
					$id 	= (isset($this->request->data['appid'])?decode($this->request->data['appid']):0);
					$status = $this->ApplyOnlineApprovals->CLAIM_SUBSIDY;
					$reason = '';
					$this->SetApplicationStatus($status,$id,$reason);
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

	/**
	*
	* getProjectEstimationById
	*
	* Behaviour : public
	*
	* Parameter : project_id
	* Parameter : customer_id
	*
	* @defination : Method is used to get project estimation data using project id.
	*
	*/
	public function getProjectEstimationById($project_id=0,$customer_id=0) {

		$this->autoRender 	= false;
		$projectData 		= array();
		if(!empty($project_id)) {
			$projectData 	= $this->Projects->get($project_id);

			$projectData = $this->Projects->getprojectestimation($this->request->data,$customer_id);
		}
		return $projectData;
	}

	/*
	 * send subsidy approval letter to customer
	 * @param mixed What page to display
	 * @return void
	 */
	private function SendSubsidyLetterOfApproval($id=0)
	{
		$this->autoRender 			= false;
		/*$applyOnlinesData 			= $this->ApplyOnlines->viewApplication($id);
		$applyOnlinesData->aid 		= $this->ApplyOnlines->GenerateApplicationNo($applyOnlinesData);
		$division 					= $this->DiscomMaster->find("all",['fields'=>['title'],'conditions'=>['DiscomMaster.id'=>$applyOnlinesData->division,'DiscomMaster.type'=>3]])->toArray();
		$circle 					= $this->DiscomMaster->find("all",['fields'=>['title'],'conditions'=>['DiscomMaster.id'=>$applyOnlinesData->circle,'DiscomMaster.type'=>2]])->toArray();
		$subdivision 				= $this->DiscomMaster->find("all",['fields'=>['title'],'conditions'=>['DiscomMaster.id'=>$applyOnlinesData->subdivision,'DiscomMaster.type'=>4]])->toArray();
		$LETTER_APPLICATION_NO 		= $applyOnlinesData->aid;
		$APPLICATION_DATE 			= date("d.m.Y",strtotime($applyOnlinesData->created));
		$CUSTOMER_NAME 				= trim($applyOnlinesData->customer_name_prefixed." ".$applyOnlinesData->name_of_consumer_applicant);
		$CUSTOMER_EMAIL 			= $applyOnlinesData->email;
		$CUSTOMER_ADDRESS 			= "";

		if (!empty($applyOnlinesData->address1)) {
			$CUSTOMER_ADDRESS .= $applyOnlinesData->address1.",<br />";
		}
		if (!empty($applyOnlinesData->address2)) {
			$CUSTOMER_ADDRESS .= $applyOnlinesData->address2.",<br />";
		}
		if (!empty($applyOnlinesData->city)) {
			$CUSTOMER_ADDRESS .= ucfirst($applyOnlinesData->city)." ";
		}
		if (!empty($applyOnlinesData->state)) {
			$CUSTOMER_ADDRESS .= ucfirst($applyOnlinesData->state)." ";
		}
		if (!empty($CUSTOMER_ADDRESS)) {
			$CUSTOMER_ADDRESS .= "INDIA";
		}
		$project_id 	= $applyOnlinesData->project_id;
		$CreateProject 	= false;
		if (empty($project_id)) {
			$CreateProject = true;
		}
		$AhaProjectData 			= $this->CreateMyProject($id,$CreateProject);
		$customer_id 				= $applyOnlinesData->customer_id;
		$ESTIMATED_COST 			= 0;
		$TOTAL_SUBSIDY_AMOUNT 		= 0;
		$STATE_SUBSIDY 				= "-";
		$STATE_SUBSIDY_AMOUNT 		= "-";
		$CENTRAL_SUBSIDY 			= "-";
		$CENTRAL_SUBSIDY_AMOUNT 	= "-";
		if (!empty($AhaProjectData)) {
			$SubsidyDetail 		= $AhaProjectData['SubsidyDetail'];

			if ($SubsidyDetail['state_subcidy_type'] == 0) {
				$STATE_SUBSIDY 			= $SubsidyDetail['state_subsidy']."%";
				$STATE_SUBSIDY_AMOUNT 	= ($SubsidyDetail['state_subsidy_amount'] > 0)?$this->get_money_indian_format($SubsidyDetail['state_subsidy_amount']):"-";
			} else {
				$STATE_SUBSIDY 			= ($SubsidyDetail['state_subsidy'] > 0)?$this->get_money_indian_format($SubsidyDetail['state_subsidy']):"-";
				$STATE_SUBSIDY_AMOUNT 	= ($SubsidyDetail['state_subsidy_amount'] > 0)?$this->get_money_indian_format($SubsidyDetail['state_subsidy_amount']):"-";
			}

			if ($SubsidyDetail['central_subcidy_type'] == 0) {
				$CENTRAL_SUBSIDY 			= $SubsidyDetail['central_subsidy']."%";
				$CENTRAL_SUBSIDY_AMOUNT 	= ($SubsidyDetail['central_subsidy_amount'] > 0)?$this->get_money_indian_format($SubsidyDetail['central_subsidy_amount']):"-";
			} else {
				$CENTRAL_SUBSIDY 			= ($SubsidyDetail['central_subsidy'] > 0)?$this->get_money_indian_format($SubsidyDetail['central_subsidy']):"-";
				$CENTRAL_SUBSIDY_AMOUNT 	= ($SubsidyDetail['central_subsidy_amount'] > 0)?$this->get_money_indian_format($SubsidyDetail['central_subsidy_amount']):"-";
			}

			$ESTIMATED_COST 		= ($SubsidyDetail['total_cost'] > 0)?$this->get_money_indian_format($SubsidyDetail['total_cost']):0;
			$TOTAL_SUBSIDY_AMOUNT 	= ($SubsidyDetail['total_subsidy'] > 0)?$this->get_money_indian_format($SubsidyDetail['total_subsidy']):0;
		}

		$FESIBILITY_APPROVED_DATE 	= $APPLICATION_DATE;
		$FESIBILITY_REF_NO 			= "";
		$fesibility 				= $this->FesibilityReport->getReportData($id);
		if (!empty($fesibility))
		{
			$fesibility->aid 			= $this->FesibilityReport->GenerateApplicationNo($fesibility,$applyOnlinesData->state);
			$FESIBILITY_APPROVED_DATE 	= date("d.m.Y",strtotime($fesibility->division_approved_date));
			$APPROVED_CAPACITY 			= floatval($fesibility->recommended_capacity_by_discom);
			$FESIBILITY_REF_NO 			= $fesibility->aid;
		}
		$INSTALLER_NAME 			= $applyOnlinesData->installer['installer_name'];
		$CONSUMER_NO 				= $applyOnlinesData->consumer_no;
		$APPLICATION_NO				= $applyOnlinesData->aid;
		$APPROVED_CAPACITY 			= floatval($applyOnlinesData->pv_capacity);
		$SUBDIVISION 				= (isset($subdivision[0]['title'])?$subdivision[0]['title']:"-");
		$DIVISION 					= (isset($division[0]['title'])?$division[0]['title']:"-");

		$JREDA_WORK_ORDER_NO 		= isset($applyOnlinesData->installer['jreda_work_order'])?$applyOnlinesData->installer['jreda_work_order']:"-";
		$JERDA_WORK_NIB 			= isset($applyOnlinesData->installer['jreda_nib_no'])?$applyOnlinesData->installer['jreda_nib_no']:"-";
		$AGREEMENT_DATE 			= $APPLICATION_DATE;
		$CUSTOMER_TYPE 				= isset($applyOnlinesData->parameter_cats['para_value'])?$applyOnlinesData->parameter_cats['para_value']:"-";;
		$INSTALLATION_DATE 			= date('d.m.Y', strtotime("+3 months", strtotime($APPLICATION_DATE)));

		$EmailVars 					= array("LETTER_APPLICATION_NO"=>$LETTER_APPLICATION_NO,
											"APPLICATION_DATE"=>$APPLICATION_DATE,
											"FESIBILITY_APPROVED_DATE"=>$FESIBILITY_APPROVED_DATE,
											"INSTALLER_NAME"=>$INSTALLER_NAME,
											"CUSTOMER_NAME"=>$CUSTOMER_NAME,
											"ESTIMATED_COST"=>$ESTIMATED_COST,
											"STATE_SUBSIDY"=>$STATE_SUBSIDY,
											"STATE_SUBSIDY_AMOUNT"=>$STATE_SUBSIDY_AMOUNT,
											"CENTRAL_SUBSIDY"=>$CENTRAL_SUBSIDY,
											"CENTRAL_SUBSIDY_AMOUNT"=>$CENTRAL_SUBSIDY_AMOUNT,
											"TOTAL_SUBSIDY_AMOUNT"=>$TOTAL_SUBSIDY_AMOUNT,
											"CUSTOMER_ADDRESS"=>$CUSTOMER_ADDRESS,
											"CONSUMER_NO"=>$CONSUMER_NO,
											"FESIBILITY_REF_NO"=>$FESIBILITY_REF_NO,
											"APPLICATION_NO"=>$APPLICATION_NO,
											"APPROVED_CAPACITY"=>$APPROVED_CAPACITY,
											"SUBDIVISION"=>$SUBDIVISION,
											"DIVISION"=>$DIVISION,
											"INSTALLATION_DATE"=>$INSTALLATION_DATE,
											"JREDA_WORK_ORDER_NO"=>$JREDA_WORK_ORDER_NO,
											"JERDA_WORK_NIB"=>$JERDA_WORK_NIB,
											"AGREEMENT_DATE"=>$AGREEMENT_DATE,
											"CUSTOMER_TYPE"=>$CUSTOMER_TYPE);
		$to			= empty($CUSTOMER_EMAIL)?"kalpak@yugtia.com":$CUSTOMER_EMAIL;
		$to 		= "kalpak@ahasolar.in";
		$bcc 		= "kalpak@yugtia.com";
		$email 		= new Email('default');
		$subject 	= "Letter of Subsidy Approval - ".$APPLICATION_NO;
		$email->profile('default');
		$email->viewVars($EmailVars);
		$message_send = $email->template('subsidy_approval', 'default')
			->emailFormat('html')
			->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
			->to($to)
			->bcc($bcc)
			->subject(Configure::read('EMAIL_ENV').$subject)
			->send();*/
		return true;
	}

	/*
	 * Send Application Letter To Customer
	 * @param mixed What page to display
	 * @return void
	 */
	private function SendApplicationLetterToCustomer($id=0)
	{
		$this->autoRender 			= false;
		$applyOnlinesData 			= $this->ApplyOnlines->viewApplication($id);
		$applyOnlinesData->aid 		= $this->ApplyOnlines->GenerateApplicationNo($applyOnlinesData);
		$LETTER_APPLICATION_NO 		= $applyOnlinesData->application_no;
		$STATENAME 					= "";
		$arrState 					= $this->States->find("list",['keyField'=>'id','valueField'=>'statename','conditions'=>['States.id'=>$applyOnlinesData->apply_state]])->toArray();
		if(!empty($arrState)) {
			$STATENAME 				= $arrState[$applyOnlinesData->apply_state];
		}
		$installer_name 			= $applyOnlinesData->installer['installer_name'];
		$ses_login_type 			= $this->Session->read('Customers.login_type');
		if($ses_login_type == 'developer' && !empty($applyOnlinesData->apply_onlines_others['map_installer_id'])) {
			$mapInstallerDetails 	= $this->Installers->find('all',array(
												'fields'	=>	array('installer_name'),
												'conditions'=>	array('id'=>$applyOnlinesData->apply_onlines_others['map_installer_id'])))->first();
			$installer_name 		= $mapInstallerDetails->installer_name;
		}
		$INSTATTER_NAME 			= $installer_name;
		
		$CUSTOMER_NAME 				= trim($applyOnlinesData->customer_name_prefixed." ".$applyOnlinesData->name_of_consumer_applicant);
		$EmailVars 					= array("LETTER_APPLICATION_NO"=>$LETTER_APPLICATION_NO,
											"CUSTOMER_NAME"=>$CUSTOMER_NAME,
											"STATENAME"=>$STATENAME,
											"INSTATTER_NAME"=>$INSTATTER_NAME);
		$to 	= '';
		if (!empty($applyOnlinesData->email)) {
			$to = $applyOnlinesData->email;
		} else {
			//$to = "kalpak.yugtia@gmail.com";
		}
		$template_include 		= 'application_submission_letter';
		$subject 				= "Submission of Rooftop Solar PV Application No - ".$LETTER_APPLICATION_NO;
		$template_include	= 'application_submission_gujarat_letter';
		$subject 			= "[REG: Application No. ".$LETTER_APPLICATION_NO."] Submission of Application";
		$sms_text = str_replace(array('##application_no##','##installer_name##'),array($applyOnlinesData->application_no,$installer_name),APPLICATION_SUBMITED);
		if(!empty($applyOnlinesData->consumer_mobile))
		{
			$this->ApplyOnlines->sendSMS($id,$applyOnlinesData->consumer_mobile,$sms_text,'APPLICATION_SUBMITED');
		}
		if(!empty($applyOnlinesData->installer_mobile))
		{
			//$this->ApplyOnlines->sendSMS($id,$applyOnlinesData->installer_mobile,$sms_text);
		}
		if($to != '') {
			$email 		= new Email('default');
			$email->profile('default');
			$email->viewVars($EmailVars);
			$message_send = $email->template($template_include, 'default')
				->emailFormat('html')
				->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
				->to($to)
				->subject(Configure::read('EMAIL_ENV').$subject)
				->send();
		}
		if(!empty($applyOnlinesData->installer_email))
		{
			$email 		= new Email('default');
			$email->profile('default');
			$email->viewVars($EmailVars);
			$message_send = $email->template($template_include, 'default')
					->emailFormat('html')
					->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
					->to($applyOnlinesData->installer_email)
					->subject(Configure::read('EMAIL_ENV').$subject)
					->send();
		}
		return true;
	}

	/*
	 * get_money_indian_format
	 * @param mixed $amount
	 * @param boolean $suffix
	 * @return mixed $thecash
	 */
	private function get_money_indian_format($amount, $suffix = 1)
	{
		$explrestunits = "";
		$num = $amount;
		if(strlen($num)>3) {
			$lastthree = substr($num, strlen($num)-3, strlen($num));
			$restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
			$restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
			$expunit = str_split($restunits, 2);
			for($i=0; $i<sizeof($expunit); $i++) {
				// creates each of the 2's group and adds a comma to the end
				if($i==0) {
					$explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
				} else {
					$explrestunits .= $expunit[$i].",";
				}
			}
			$thecash = $explrestunits.$lastthree;
		} else {
			$thecash = $num;
		}
		if(!$suffix) {
			return $thecash;
		} else {
			return 'Rs. '. $thecash.'/-';
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
		$this->generateApplicationPdf($id);
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
			$applyOnlinesData 		= $this->ApplyOnlines->viewApplication($id);

			if (!empty($applyOnlinesData)) {
				if ($this->request->is('post') || $this->request->is('put')) {
					$customer_type 						= $this->Session->read('Customers.customer_type');
					$customer_id          				= $this->Session->read("Customers.id");
					$member_id          				= $this->Session->read("Members.id");
					$member_type 						= $this->Session->read('Members.member_type');
					$browser 									= isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:"-";
					$ApplyonlineMessageEntity					= $this->ApplyonlineMessage->newEntity();
					$ApplyonlineMessageEntity->application_id 	= $id;
					$ApplyonlineMessageEntity->message 			= strip_tags($message);
					$ApplyonlineMessageEntity->user_type 		= !empty($customer_type)?0:$member_type;
					$ApplyonlineMessageEntity->user_id 			= !empty($customer_id)?$customer_id:$member_id;
					$ApplyonlineMessageEntity->ip_address 		= $this->IP_ADDRESS;
					$ApplyonlineMessageEntity->for_claim 		= $for_claim;
					$ApplyonlineMessageEntity->created 			= $this->NOW();
					$ApplyonlineMessageEntity->browser_info 	= json_encode($browser);
					if($this->ApplyonlineMessage->save($ApplyonlineMessageEntity)) {
						$this->ApplyOnlines->SendEmailToCustomer($id,$ApplyonlineMessageEntity->id);
						$ErrorMessage 	= "Message sent successfully.";
						$success 		= 1;
						$applyid 		= $applyOnlinesData->id;
						if(!empty($applyid)) {
							$data 				= $this->ApplyOnlines->get($applyid);
							$data->query_sent	= '1';
							$data->query_date 	= date('Y-m-d H:i:s');
							$data->modified 	= date('Y-m-d H:i:s');
							$this->ApplyOnlines->save($data);
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
		$ApplyonlineMessage = array();
		if(!empty($id)) {
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$member_id      = $this->Session->read("Members.id");
			$ApplyonlineMessage 	= $this->ApplyonlineMessage->GetAllMessagesById($id,$member_id);
		}
		
		$view 			= new View($this->request,$this->response);
		$view->layout 	= 'empty';
		$view->set('ApplyonlineMessage', $ApplyonlineMessage);
		$html = $view->render('/ApplyOnlines/get_all_messages');
		$this->ApiToken->SetAPIResponse('html',$html);
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	/**
	 *
	 * document_verify
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to verify application's document
	 *
	 */
	public function document_verify($application_id='')
	{
		if(!empty($application_id))
		{
			$app_id = intval(decode($application_id));
			if($app_id>0)
			{
				$this->ApplyOnlines->updateAll(array('application_status'=>$this->ApplyOnlineApprovals->DOCUMENT_VERIFIED),array('id'=>$app_id));
				$applyOnlinesData   = $this->ApplyOnlines->viewApplication($app_id);
				$member_id 			= $this->Session->read("Members.id");
				$this->ApplyOnlineApprovals->saveStatus($app_id,$this->ApplyOnlineApprovals->DOCUMENT_VERIFIED,$member_id,'');

				$sms_text = str_replace('##application_no##',$applyOnlinesData->application_no,DOC_VERIFIED);

				if(!empty($applyOnlinesData->consumer_mobile))
				{
					$this->ApplyOnlines->sendSMS($app_id,$applyOnlinesData->consumer_mobile,$sms_text,'DOC_VERIFIED');
				}
				if(!empty($applyOnlinesData->installer_mobile))
				{
					//$this->ApplyOnlines->sendSMS($app_id,$applyOnlinesData->installer_mobile,$sms_text);
				}

				$subject 			= "[REG:Application No. ".$applyOnlinesData->application_no."] Verification of Documents";
				$CUSTOMER_NAME 				= trim($applyOnlinesData->customer_name_prefixed." ".$applyOnlinesData->name_of_consumer_applicant." ".$applyOnlinesData->last_name." ".$applyOnlinesData->third_name);
				$EmailVars 					= array("CUSTOMER_NAME"=>$CUSTOMER_NAME);

				if(!empty($applyOnlinesData->installer_email))
				{
					$email 					= new Email('default');
					$email->profile('default');
					$email->viewVars($EmailVars);
					$message_send 			= $email->template('document_verification', 'default')
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
					$message_send 	= $email->template('document_verification', 'default')
							->emailFormat('html')
							->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
							->to($to)
							->subject(Configure::read('EMAIL_ENV').$subject)
							->send();
				}
				$this->SendRegistrationFailure->fetchApiSendRegistration($app_id);
				$this->Flash->set('Application documents verified successfully.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
				$this->redirect('apply-online-list');
			}
		}
		else
		{
			$this->redirect('apply-online-list');
		}
	}
	/**
	*
	* getDivision
	*
	* Behaviour : public
	*
	* Parameter : discom
	*
	* @defination : Method is used to get division for perticular discom.
	*
	*/
	public function getDivision() {
		$this->autoRender 		= false;
		$discom 				= isset($this->request->data['discom'])?$this->request->data['discom']:0;
		$data 					= array();
		if (!empty($discom)) {
			$branch_detail 		= $this->BranchMasters->find('all',array('conditions'=>array('id'=>$discom)))->first();
			$division 			= $this->DiscomMaster->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['DiscomMaster.area'=>$branch_detail->discom_id,'DiscomMaster.type'=>3,'status'=>'1']]);
			$data['division'] 	= $division;
		}
		$this->ApiToken->SetAPIResponse('msg', 'list of division');
		$this->ApiToken->SetAPIResponse('data', $data);
		$this->ApiToken->SetAPIResponse('type','ok');
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	/**
	*
	* getSubDivisionConsumer
	*
	* Behaviour : public
	*
	* Parameter : discom
	*
	* @defination : Method is used to get subdivision from consumer number.
	*
	*/
	public function getSubDivisionConsumer() {

		$this->autoRender 			= false;
		$consumer_no 				= isset($this->request->data['consumer_no'])?$this->request->data['consumer_no']:0;
		$discom_id 					= isset($this->request->data['discom'])?$this->request->data['discom']:0;
		$application_no 			= isset($this->request->data['id'])?$this->request->data['id']:0;
		$project_id 				= isset($this->request->data['project_id'])?$this->request->data['project_id']:0;
		$division_id 				= isset($this->request->data['division_id'])?$this->request->data['division_id']:0;
		$tno 						= isset($this->request->data['tno'])?$this->request->data['tno']:0;
		$category 					= isset($this->request->data['category'])?$this->request->data['category']:0;
		$action 					= isset($this->request->data['action'])?$this->request->data['action']:'';

		$data 						= $this->getdetailsSubdivision($consumer_no,$discom_id,$project_id,$application_no,'',$division_id,$tno,$category,$action);
		$this->ApiToken->SetAPIResponse('msg', 'list of subdivision');
		$this->ApiToken->SetAPIResponse('data', $data);
		$this->ApiToken->SetAPIResponse('type','ok');
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	public function getdetailsSubdivision($consumer_no,$discom_id,$project_id,$application_no,$get_search_data='',$division_sel='',$tno='',$category='',$action='')
	{
		$return_data = $this->ApplyOnlines->check_consumer_block($consumer_no,$discom_id,$category);
		$data_subdiv						= array();
		$data_subdiv['first_name'] 			= '';
		$data_subdiv['middle_name'] 		= '';
		$data_subdiv['last_name'] 			= '';
		$data_subdiv['address1'] 			= '';
		$data_subdiv['sanction_load'] 		= '';
		$data_subdiv['category'] 			= '';
		$data_subdiv['transmission_line']	= '';
		$data_subdiv['taluka']				= '';
		$data_subdiv['state']				= '';
		$data_subdiv['city']				= '';
		$data_subdiv['pincode']				= '';
		$data_subdiv['circle_api']			= '';
		$data_subdiv['division_api']		= '';
		$data_subdiv['sub_division_api']	= '';
		$data_subdiv['success']             = '1';
		$data_subdiv['api_consumer_no']     = '1';
		$data_subdiv['response_msg']        = '';
		$existingCapacity 					= 0;
		if($return_data ===true)
		{
			if($get_search_data=='')
			{
				/*if($discom_id != $this->ApplyOnlines->torent_ahmedabad && $discom_id != $this->ApplyOnlines->torent_surat)
				{*/
					$arr_output = $this->ThirdpartyApiLog->searchConsumerApi($consumer_no,$discom_id,$project_id,$application_no,$tno);
				//}
					echo"<pre>"; print_r($arr_output); die();
			}
			else
			{
				$applyOnlineDetails = $this->ApplyOnlines->find('all',array('conditions'=>array('id'=>$application_no)))->first();
				if(!empty($applyOnlineDetails->api_response))
				{
					$arr_output     = json_decode($applyOnlineDetails->api_response);
				}
			}
			//pr($arr_output);
			//pr($action);
			if(!empty($arr_output))
			{
				$flag_disp_data 				= 0;
				if($discom_id != $this->ApplyOnlines->torent_ahmedabad && $discom_id != $this->ApplyOnlines->torent_surat && $discom_id != $this->ApplyOnlines->torent_dahej)
				{
					$data_subdiv['success'] 		= $arr_output->P_OUT_STS_CD;
					$data_subdiv['response_msg'] 	= $arr_output->P_OUT_MSG_SERVER;

					if($action=='additional_capacity')
					{
						if(isset($arr_output->P_OUT_STS_CD) && ($arr_output->P_OUT_STS_CD == 1) || isset($arr_output->P_OUT_DATA->P_OUT_STS_CD) && ($arr_output->P_OUT_DATA->P_OUT_STS_CD == 1))
						{
							if(isset($arr_output->P_OUT_DATA->OUTPUT_DATA))
							{
								$output_details_obj 	= $arr_output->P_OUT_DATA->OUTPUT_DATA;
								$flag_disp_data 		= 1;
							}
							elseif(isset($arr_output->P_OUT_DATA))
							{
								$output_details_obj 	= $arr_output->P_OUT_DATA;
								$flag_disp_data 		= 1;
							}
							$data_subdiv['success'] 		= '1';
							$data_subdiv['response_msg'] 	= '';
							if(isset($output_details_obj->SOLAR_FLAG) && strtolower($output_details_obj->SOLAR_FLAG)!='y')
							{
								$flag_disp_data 				= 0;
								$data_subdiv['success'] 		= 0;
								$data_subdiv['response_msg'] 	= "Consumer No. is not allowed to add additional capacity. Go to 'New Application' in Apply tab to submit application";
							}
							elseif(!isset($output_details_obj->SOLAR_FLAG) && isset($output_details_obj->P_OUT_MSG_SERVER))
							{
								$flag_disp_data 				= 0;
								$data_subdiv['success'] 		= 0;
								$data_subdiv['response_msg'] 	= $output_details_obj->P_OUT_MSG_SERVER;
							}
							elseif(!isset($output_details_obj->SOLAR_FLAG))
							{
								$flag_disp_data 				= 0;
								$data_subdiv['success'] 		= 0;
								$data_subdiv['response_msg'] 	= "Consumer No. is not allowed to add additional capacity. Go to 'New Application' in Apply tab to submit application";
							}
							if($flag_disp_data == 0) {
								$DiscomMasterHt 			= TableRegistry::get('DiscomMasterHt');
								if(!empty($output_details_obj->SDO))
								{
									$arrPassData['circle_api'] 		= $output_details_obj->CIRCLE;
									$arrPassData['division_api']	= $output_details_obj->DIV;
									$arrPassData['sub_division_api']= $output_details_obj->SDO;
									$HTSubdivision 					= $DiscomMasterHt->findDiscomHt($arrPassData,$discom_id);

									if(!empty($HTSubdivision))
									{
										$existingCapacity 	= $DiscomMasterHt->findTotalExsitingCapacity($consumer_no,$application_no,$discom_id);
										if(!empty($existingCapacity)) {
											$flag_disp_data 			= 1;
											$data_subdiv['success'] 	= '1';
											$data_subdiv['response_msg']= '';
										}
										
									} elseif(strlen($consumer_no) == 5) {
										$appDetails 	= $this->ApplyOnlines->find('all',array('conditions'=>array('consumer_no'=>$consumer_no,'discom'=>$discom_id)))->first();
										if(!empty($appDetails)) {
											$existingCapacity 	= $DiscomMasterHt->findTotalExsitingCapacity($consumer_no,$application_no,$discom_id);
											if(!empty($existingCapacity)) {
												$flag_disp_data 			= 1;
												$data_subdiv['success'] 	= '1';
												$data_subdiv['response_msg']= '';
											}
										}
										
									}
								}
							}
						}
						else
						{
							if(isset($arr_output->P_OUT_MSG_SERVER))
							{
								$data_subdiv['success'] 		= 0;
								$data_subdiv['response_msg'] 	= $arr_output->P_OUT_MSG_SERVER;
							} 
							else {
								$data_subdiv['success'] 		= 0;
								$data_subdiv['response_msg'] 	= "Consumer No. is not allowed to add additional capacity. Go to 'New Application' in Apply tab to submit application";
							}
							
						}
					}
					elseif($arr_output->P_OUT_STS_CD == 1  || $arr_output->P_OUT_STS_CD == -1)
					{
						if(isset($arr_output->P_OUT_DATA->OUTPUT_DATA))
						{
							$output_details_obj 	= $arr_output->P_OUT_DATA->OUTPUT_DATA;
							$flag_disp_data 		= 1;
						}
						elseif(isset($arr_output->P_OUT_DATA))
						{
							$output_details_obj 	= $arr_output->P_OUT_DATA;
							$flag_disp_data 		= 1;
						}
						if(isset($output_details_obj->SOLAR_FLAG) && strtolower($output_details_obj->SOLAR_FLAG)=='y')
						{
							$flag_disp_data 				= 0;
							$data_subdiv['success'] 		= 0;
							$data_subdiv['response_msg'] 	= "Consumer No. is not allowed to New Application. Go to 'PV Capacity Enhancement' in Apply tab to submit application for additional capacity";
						}
					}
				}
				else
				{
					if($action=='additional_capacity')
					{ 
						if(isset($arr_output->P_OUT_STS_CD) && ($arr_output->P_OUT_STS_CD == 1) || isset($arr_output->P_OUT_DATA->P_OUT_STS_CD) && ($arr_output->P_OUT_DATA->P_OUT_STS_CD == 1))
						{
							if(isset($arr_output->P_OUT_DATA->OUTPUT_DATA))
							{
								$output_details_obj 	= $arr_output->P_OUT_DATA->OUTPUT_DATA;
								$flag_disp_data 		= 1;
							}
							elseif(isset($arr_output->P_OUT_DATA))
							{
								$output_details_obj 	= $arr_output->P_OUT_DATA;
								$flag_disp_data 		= 1;
							}
							$data_subdiv['success'] 		= '1';
							$data_subdiv['response_msg'] 	= '';
							if(isset($output_details_obj->SOLAR_FLAG) && strtolower($output_details_obj->SOLAR_FLAG)!='y')
							{
								$flag_disp_data 				= 0;
								$data_subdiv['success'] 		= 0;
								$data_subdiv['response_msg'] 	= "Consumer No. is not allowed to add additional capacity. Go to 'New Application' in Apply tab to submit application";
							}
							if($flag_disp_data == 0) {
								$DiscomMasterHt 			= TableRegistry::get('DiscomMasterHt');
								if(!empty($output_details_obj->SDO))
								{
									$arrPassData['circle_api'] 		= $output_details_obj->CIRCLE;
									$arrPassData['division_api']	= $output_details_obj->DIV;
									$arrPassData['sub_division_api']= $output_details_obj->SDO;
									$HTSubdivision 					= $DiscomMasterHt->findDiscomHt($arrPassData,$discom_id);
									if(!empty($HTSubdivision))
									{
										$existingCapacity 	= $DiscomMasterHt->findTotalExsitingCapacity($consumer_no,$application_no,$discom_id);
										if(!empty($existingCapacity)) {
											$flag_disp_data 			= 1;
											$data_subdiv['success'] 	= '1';
											$data_subdiv['response_msg']= '';
										}
										
									}
								}
							}
						}
						else
						{

							if(isset($arr_output->P_OUT_MSG_SERVER))
							{
								$flag_disp_data 				= 0;
								$data_subdiv['success'] 		= 0;
								$data_subdiv['response_msg'] 	= $arr_output->P_OUT_MSG_SERVER;
							} else {
								$data_subdiv['success'] 		= 0;
								$data_subdiv['response_msg'] 	= "Consumer No. is not allowed to add additional capacity. Go to 'New Application' in Apply tab to submit application";
							}
							
						}
					}
					elseif(isset($arr_output->P_OUT_DATA) && !empty($arr_output->P_OUT_DATA))
					{
						$output_details_obj 			= $arr_output->P_OUT_DATA;
						$data_subdiv['success'] 		= $output_details_obj->P_OUT_STS_CD;
						$data_subdiv['response_msg'] 	= $output_details_obj->P_OUT_MSG_SERVER;
						if(isset($output_details_obj->SOLAR_FLAG) && strtolower($output_details_obj->SOLAR_FLAG)=='y')
						{
							$flag_disp_data 				= 0;
							$data_subdiv['success'] 		= 0;
							$data_subdiv['response_msg'] 	= "Consumer No. is not allowed to New Application. Go to 'PV Capacity Enhancement' in Apply tab to submit application for additional capacity";
						}
						elseif(strtolower($arr_output->P_OUT_DATA->P_OUT_MSG_CLIENT)=='success')
						{
							$flag_disp_data 			= 1;
						}
					}
					else
					{
						$data_subdiv['success'] 		= '0';
						$data_subdiv['response_msg'] 	= 'Invalid Consumer Number.';
					}
				}
				if($flag_disp_data == 1)
				{
					$output_name 						= preg_replace('!\s+!', ' ', $output_details_obj->NAME);
					$output_name 						= str_replace(array('M/S ','m/s '), array('',''), $output_name);
					$arr_name 							= explode(" ",trim($output_name));
					if(count($arr_name)>2)
					{
						$data_subdiv['first_name'] 		= $arr_name[0];
						$data_subdiv['middle_name'] 	= $arr_name[1];
						$last_name_data 				= str_replace(array($arr_name[0]." ".$arr_name[1]." "), array(''), $output_name);
						$data_subdiv['last_name'] 		= $last_name_data;
						//$data_subdiv['last_name'] 		= $arr_name[2];
					}
					else
					{
						$data_subdiv['first_name'] 		= $arr_name[0];
						$data_subdiv['last_name'] 		= $arr_name[1];
					}
					if($discom_id != $this->ApplyOnlines->torent_ahmedabad && $discom_id != $this->ApplyOnlines->torent_surat  && $discom_id != $this->ApplyOnlines->torent_dahej)
					{
						if(isset($output_details_obj->ADDRESS))
						{
							$data_subdiv['address1'] 		= $output_details_obj->ADDRESS;
							$data_subdiv['api_consumer_no'] = $arr_output->apirequest->P_IN_DATA->INPUT_DATA->cnsmr_no;
						}
						elseif(isset($output_details_obj->ADDRS))
						{
							$data_subdiv['address1'] 		= $output_details_obj->ADDRS;
							$data_subdiv['api_consumer_no'] = $consumer_no;
						}
					}
					else
					{
						$data_subdiv['address1'] 		= $output_details_obj->ADDRS;
						$data_subdiv['api_consumer_no'] = $consumer_no;
					}
					$categorySelected 					= strtoupper($output_details_obj->CATEGORY);
					$phaseSelected 						= $output_details_obj->PHASE;
					$loadSelected 						= $output_details_obj->LOAD;

					if($discom_id == $this->ApplyOnlines->torent_ahmedabad || $discom_id == $this->ApplyOnlines->torent_surat)
					{
						$arrCategory 					= explode("|",strtolower($categorySelected));
						if(count($arrCategory)>1)
						{
							$arrPhase 					= explode("|",$phaseSelected);
							$arrLoad 					= explode("|",$loadSelected);
							$project_data 				= $this->Projects->find('all',array('conditions'=>array('id'=>$project_id)))->first();
							if(!empty($project_data) && $project_data->customer_type == 3001) {
								$selKey 				= array_search('residential',$arrCategory);
								$keyCategory 			= $selKey;
							} else {
								$selKey 				= array_search('residential',$arrCategory);
								$keyCategory 			= ($selKey == 0) ? 1 : 0;
							}
							foreach($arrCategory as $k1=>$cat) {
								$categoryMapped 		= isset($this->ThirdpartyApiLog->arr_category_map[strtoupper($cat)]) ? $this->ThirdpartyApiLog->arr_category_map[strtoupper($cat)] : '';
								if($categoryMapped == $project_data->customer_type)
								{
									$keyCategory 		= $k1;
									break;
								}
							}
							$categorySelected 			= $arrCategory[$keyCategory];
							$phaseSelected 				= $arrPhase[$keyCategory];
							$loadSelected 				= $arrLoad[$keyCategory];
						}
					}
					$data_subdiv['sanction_load'] 		= $loadSelected;
					$data_subdiv['subcategory']			= isset($output_details_obj->SUB_CATEGORY) ? $output_details_obj->SUB_CATEGORY : '';
					$categorySelected 					= ($data_subdiv['subcategory'] == 'C') ? 'COMMERCIAL' : $categorySelected;
					$categorySelected 					= ($data_subdiv['subcategory'] == 'L') ? 'INDUSTRIAL' : $categorySelected;
					$data_subdiv['category'] 			= isset($this->ThirdpartyApiLog->arr_category_map[strtoupper($categorySelected)]) ? $this->ThirdpartyApiLog->arr_category_map[strtoupper($categorySelected)] : '';
					$data_subdiv['transmission_line']	= $this->ThirdpartyApiLog->arr_phase_map[$phaseSelected];
					$data_subdiv['taluka']				= $output_details_obj->TALUKA;
					$data_subdiv['district']			= $output_details_obj->DISTRICT;
					$data_subdiv['city']				= $output_details_obj->CITY;
					$data_subdiv['circle_api']			= $output_details_obj->CIRCLE;
					$data_subdiv['division_api']		= $output_details_obj->DIV;
					$data_subdiv['sub_division_api']	= $output_details_obj->SDO;
					$data_subdiv['tariff']				= isset($output_details_obj->TARIFF) ? $output_details_obj->TARIFF : '';
					$data_subdiv['installed_capacity']	= !empty($existingCapacity) ? $existingCapacity : (isset($output_details_obj->SOLAR_PV_INSTALLED_LOAD)? $output_details_obj->SOLAR_PV_INSTALLED_LOAD : '');

				}
			}
			if (!empty($consumer_no) || !empty($get_search_data)) {
				$subdivision 			= substr($consumer_no,0,3);
				$applyOnlineDetails		= $this->ApplyOnlines->find('all',array('conditions'=>array('id'=>$application_no)))->first();
				if(!empty($data_subdiv['sub_division_api']))
				{
					$DiscomMasterHt 	= TableRegistry::get('DiscomMasterHt');
					if(!empty($data_subdiv['division_api']))
					{
						$HTSubdivision 	= $DiscomMasterHt->findDiscomHt($data_subdiv,$discom_id);
						if(!empty($HTSubdivision))
						{
							$data_subdiv['sub_division_api'] = $HTSubdivision->sort_code;
						}

					}
					$subdivision  		= $data_subdiv['sub_division_api'];
				}
				elseif(isset($applyOnlineDetails->subdivision) && !empty($applyOnlineDetails->subdivision))
				{
					$discom_details 	= $this->DiscomMaster->find("all",['conditions'=>['id'=>$applyOnlineDetails->subdivision]])->first();
					$subdivision  		= $discom_details->short_code;
				}
				$arr_dis_details = array();
				if($discom_id != $this->ApplyOnlines->torent_ahmedabad && $discom_id != $this->ApplyOnlines->torent_surat  && $discom_id != $this->ApplyOnlines->torent_dahej)
				{
					$discom_details 		= $this->DiscomMaster->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['DiscomMaster.short_code'=>$subdivision,'DiscomMaster.type'=>4,'status'=>'1']]);
					$arr_dis_details 		= $discom_details->toarray();
				}
				elseif(!empty($data_subdiv['division_api']))
				{
					$discom_details 		= $this->DiscomMaster->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['DiscomMaster.short_code'=>$data_subdiv['division_api'],'DiscomMaster.type'=>4,'status'=>'1']]);
					$arr_dis_details 		= $discom_details->toarray();
				}
				if (!empty($arr_dis_details)) {
					$data_subdiv['subdivision']	= $arr_dis_details;
					$discom_data_details		= $this->DiscomMaster->find("all",['conditions'=>['id'=>key($arr_dis_details),'status'=>'1']])->first();

					$division_data 				= $this->DiscomMaster->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['circle'=>$discom_data_details->circle,'type'=>'3','status'=>'1']]);
					$data_subdiv['division'] 	= $division_data;
					$data_subdiv['seldivision']	= $discom_data_details->division;
					if(!empty($data_subdiv['division_api']))
					{
						$division_data 		= $this->DiscomMaster->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['DiscomMaster.short_code'=>$data_subdiv['division_api'],'DiscomMaster.type'=>3,'area'=>$discom_data_details->area,'circle'=>$discom_data_details->circle,'status'=>'1']]);
						$data_subdiv['division'] 	= $division_data;
					}
					$branch_detail 		= $this->BranchMasters->find("all",array('conditions'=>array('discom_id'=>$discom_data_details->area)))->first();
					$data_subdiv['seldiscom']	= $branch_detail->id;
				}
				else
				{
					/*$branch_detail 				= $this->BranchMasters->find("all",array('conditions'=>array('id'=>$discom_id)))->first();

					$discom_data_details		= $this->DiscomMaster->find("all",['conditions'=>['id'=>$branch_detail->discom_id]])->first();
					$division_data 				= $this->DiscomMaster->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['area'=>$branch_detail->discom_id,'type'=>'3']]);*/
					//$discom_details 		= $this->DiscomMaster->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['DiscomMaster.division'=>$subdivision,'DiscomMaster.type'=>4]]);
					//$arr_dis_details 		= $arr_dis_details;
					if(!empty($applyOnlineDetails->subdivision))
					{

						$discom_details 		= $this->DiscomMaster->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['id'=>$applyOnlineDetails->subdivision]]);
					}
					else
					{

						$discom_details 		= $this->DiscomMaster->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['division'=>$division_sel,'DiscomMaster.type'=>4,'status'=>'1']]);
					}

					$arr_dis_details 			= $discom_details->toarray();
					$data_subdiv['subdivision']	= $arr_dis_details;
					$data_subdiv['division']	= '';//$division_data;
					$data_subdiv['seldiscom']	= $discom_id;
				}
			}
			else
			{
				$data_subdiv['subdivision']		= array('id'=>'');
				$data_subdiv['division']		= '';
			}
		}
		else
		{
			$data_subdiv['success']         = '0';
			$data_subdiv['response_msg']    = 'Consumer No. is currently exist in block list';
		}
		return $data_subdiv;
	}
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
				/*$ErrorMessage 	= STOP_ADD_APPLICATION_MSG;
				$success 		= 0;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
				echo $this->ApiToken->GenerateAPIResponse();
				exit;*/
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->ApplyOnlines->find('all',array('Fields'=>['otp','id'],'conditions'=>array('id'=>$id)))->first();
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
						$application_status = $this->ApplyOnlineApprovals->APPLICATION_PENDING;
						$this->ApplyOnlines->updateAll(array('application_status'=>$application_status),array('id'=>$id));
						$customer_id 			= $this->Session->read("Customers.id");
						$this->ApplyOnlineApprovals->saveStatus($id,$this->ApplyOnlineApprovals->APPLICATION_PENDING,$customer_id,'');
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
	public function resend_otp($app_id)
	{
		$application_id    	= intval(decode($app_id));
		$ApplyOnlinesdata 	= $this->ApplyOnlines->find('all',array('conditions'=>array('id'=>$application_id)))->first();
		$ses_customer_type 		= $this->Session->read('Customers.customer_type');
		$is_installer 			= false;
		if ($ses_customer_type == "installer") {
			$is_installer 		= true;
		}
		if(!empty($ApplyOnlinesdata))
		{
			$sms_mobile = $ApplyOnlinesdata->installer_mobile;
			if($is_installer==true)
			{
				$sms_mobile = $ApplyOnlinesdata->consumer_mobile;
			}

			$sms_message =str_replace('##application_no##',$ApplyOnlinesdata->application_no, OTP_RESEND);
			$this->ApplyOnlines->SendSMSActivationCode($ApplyOnlinesdata->id,$sms_mobile,$sms_message,'OTP_RESEND');
			
			$this->Flash->set('OTP Resend successfully.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
			$this->redirect(URL_HTTP.'/apply-online-list');
		}
	}
	/**
	 *
	 * geda_letter
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to view geda approval letter
	 *
	 */
	public function geda_letter($id = null)
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
		$pdf_path = $this->generateGedaLetterPdf($id,false,false);
	}
	/**
	 *
	 * inspection_letter
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to view discom inspection letter
	 *
	 */
	public function inspection_letter($id = null)
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
		$pdf_path = $this->generateDiscomLetterPdf($id,false);
	}
	/**
	 *
	 * feasibility_report
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to view feasibility report
	 *
	 */
	public function feasibility_report($id = null)
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
		$pdf_path = $this->generateFesibilityReportPdf($id,false);
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
	public function fetch_status_api()
	{
		$this->autoRender	= false;
		$appid 				= intval(decode($this->request->data['app_id']));
		$drawing_number 	= isset($this->request->data['drawing_number']) ? $this->request->data['drawing_number'] : '';
		$cei_number 		= isset($this->request->data['cei_number']) ? $this->request->data['cei_number'] : '';
		$api_type 			= $this->request->data['api_type'];
		$pass_param 		= $drawing_number;

		if($pass_param == '')
		{
			$pass_param 	= $cei_number;
		}
		$response 			= $this->ThirdpartyApiLog->third_party_call($appid,$pass_param,$api_type);
		//echo"<pre>"; print_r($response); die();
		$exist_cei 			= $this->CeiApplicationDetails->find('all',array('conditions'=>array('application_id'=>$appid)))->first();
		if(empty($exist_cei))
		{
			$ceiappEntity						= $this->CeiApplicationDetails->newEntity($this->request->data);
			$ceiappEntity->application_id 		= $appid;
			$ceiappEntity->created 				= $this->NOW();
			$ceiappEntity->updated 				= $this->NOW();
		}
		else
		{
			$getceidata 						= $this->CeiApplicationDetails->get($exist_cei->id);
			$ceiappEntity						= $this->CeiApplicationDetails->patchEntity($getceidata,$this->request->data);
			$ceiappEntity->updated 				= $this->NOW();
		}
		if($drawing_number!='')
		{
			$ceiappEntity->drawing_app_no 		= $drawing_number;
			$ceiappEntity->drawing_app_status	= $response;
			$ceiappEntity->status 				= '1';
			$status_update 						= $this->ApplyOnlineApprovals->DRAWING_APPLIED;
		}
		if($cei_number!='')
		{
			$ceiappEntity->cei_app_no 			= $cei_number;
			$ceiappEntity->cei_app_status		= $response;
			$ceiappEntity->status 				= '2';
			$status_update 						= $this->ApplyOnlineApprovals->CEI_APP_NUMBER_APPLIED;
		}
		//echo"<pre>"; print_r($response); die();
		if($this->CeiApplicationDetails->save($ceiappEntity))
		{
			//$this->SetApplicationStatus($status_update,$appid,'');
		}
		echo json_encode(array('type'=>'ok','response'=>$response));
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
		$cei_data 	= $this->CeiApplicationDetails->find('all',array('conditions'=>array('application_id'=>$appid)))->first();
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
	 * fetchprojectdata
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to fetch data from project details according to application id.
	 *
	 */
	public function fetchprojectdata()
	{
		$appid 				= intval(decode($this->request->data['app_id']));
		$application_data 	= $this->ApplyOnlines->find('all',array('conditions'=>array('id'=>$appid)))->first();
		$project_data 		= $this->Projects->find('all',array('conditions'=>array('id'=>$application_data->project_id)))->first();
		$areaTypeArr    	= $this->Parameters->getAreaType();
		if(!empty($project_data))
		{
			$arearType 		= (isset($project_data->area_type)?$project_data->area_type:'');
			$project_data->area_type_text = (!empty($arearType) && isset($areaTypeArr[$arearType])) ? $areaTypeArr[$arearType] : '';
			echo json_encode(array('type'=>'ok','response'=>$project_data));
		}
		else
		{
			echo json_encode(array('type'=>'error','response'=>''));
		}
		exit;
	}
	public function getdatafromexel()
	{

		$arr_application 	= $this->ApplyOnlines->find('all',array('conditions'=>array('payment_status'=>'1','apply_state'=>'22')))->toArray();
		$PhpExcel=$this->PhpExcel;
		$PhpExcel->createExcel();
		// Add a drawing to the worksheetecho date('H:i:s') . " Add a drawing to the worksheet\n";
		$objDrawing = new \PHPExcel_Worksheet_MemoryDrawing();

		$objDrawing->setCoordinates('A1');
		$objDrawing->setWorksheet($PhpExcel->getExcelObj()->getActiveSheet());
		$j=1;
		$PhpExcel->writeCellValue('A'.$j, 'Name');
		$PhpExcel->writeCellValue('B'.$j, 'Application No');
		//$PhpExcel->writeCellValue('C'.$j, "Orientation");
		$PhpExcel->writeCellValue('C'.$j,"Application Date");
		$PhpExcel->writeCellValue('D'.$j, "Capacity");
		$PhpExcel->writeCellValue('E'.$j, "Category");
		$PhpExcel->writeCellValue('F'.$j, "Social sector");
		$PhpExcel->writeCellValue('G'.$j, "Transaction Number");
		$PhpExcel->writeCellValue('H'.$j, "Amount");
		$PhpExcel->writeCellValue('I'.$j,"Payment Date");

		$j++;
		foreach($arr_application as $application_data)
		{
			$applyOnlinesData 			= $this->ApplyOnlines->viewApplication($application_data->id);
			$payumoney_data = $this->Payumoney->find('all',[
							'conditions' => ['id' => $application_data->payumoney_id]])->first();
		$transaction_id='';
		$payment_date='';
		if(!empty($payumoney_data))
		{
			$transaction_id=($payumoney_data->transaction_id);
			$payment_date=(!empty($payumoney_data->payment_date) ? date(LIST_DATE_FORMAT,strtotime($payumoney_data->payment_date)) : '');
		}
		$application_date=(!empty($application_data->created) ? date(LIST_DATE_FORMAT,strtotime($application_data->created)) : '');
			$social_text = 'No';

			if($application_data->social_consumer=='1')
			{
				$social_text = 'Yes';
			}
			$params=$this->Parameters->GetParameterList(3);

			$PhpExcel->writeCellValue('A'.$j, $application_data->customer_name_prefixed.'-'.$application_data->name_of_consumer_applicant.'-'.$application_data->last_name);
			$PhpExcel->writeCellValue('B'.$j, $application_data->application_no);
			//$PhpExcel->writeCellValue('C'.$j, "Orientation");
			$PhpExcel->writeCellValue('C'.$j, $application_date);
			$PhpExcel->writeCellValue('D'.$j, $application_data->pv_capacity);
			$PhpExcel->writeCellValue('E'.$j, $applyOnlinesData->parameter_cats['para_value']);
			$PhpExcel->writeCellValue('F'.$j, $social_text);
			$PhpExcel->writeCellValue('G'.$j, $transaction_id);
			$PhpExcel->writeCellValue('H'.$j, ($application_data->disCom_application_fee+$application_data->jreda_processing_fee));
			$PhpExcel->writeCellValue('I'.$j,$payment_date);
			$j++;
		}
		$PhpExcel->downloadFile(time());
		exit;
	}
	public function getGedaappnumber()
	{
		$arr_application 	= $this->ApplyOnlines->viewApplication('130541');
		$geda_application_no 	= $this->ApplyOnlines->GenerateGedaApplicationNo($arr_application);
		$this->ApplyOnlines->updateAll(array('geda_application_no'=>$geda_application_no),array('id'=>$arr_application->id));
		$arr_application 	= $this->ApplyOnlines->viewApplication('130538');
		$geda_application_no 	= $this->ApplyOnlines->GenerateGedaApplicationNo($arr_application);
		$this->ApplyOnlines->updateAll(array('geda_application_no'=>$geda_application_no),array('id'=>$arr_application->id));
	/*	$arr_application 	= $this->ApplyOnlines->viewApplication('47055');
		$geda_application_no 	= $this->ApplyOnlines->GenerateGedaApplicationNo($arr_application);
		$this->ApplyOnlines->updateAll(array('geda_application_no'=>$geda_application_no,'application_status'=>31),array('id'=>$arr_application->id));
		$arr_application 	= $this->ApplyOnlines->viewApplication('47095');
		$geda_application_no 	= $this->ApplyOnlines->GenerateGedaApplicationNo($arr_application);
		$this->ApplyOnlines->updateAll(array('geda_application_no'=>$geda_application_no,'application_status'=>31),array('id'=>$arr_application->id));*/
		exit;

	}
	/**
	 *
	 * geda_inspection_letter
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to view geda inspection letter
	 *
	 */
	public function geda_inspection_letter($id = null)
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
		$pdf_path = $this->generateGedaInspectionLetterPdf($id,false);
		$this->redirect($pdf_path);
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
			$applyOnlinesData 		= $this->ApplyOnlines->viewApplication($id);
			if (!empty($applyOnlinesData)) {
				if ($this->request->is('post') || $this->request->is('put')) {
					$ApplyonlineMessage 				= $this->ApplyonlineMessage->GetLastMessageByApplicationForClaim($id,1);
					$customer_type 						= $this->Session->read('Customers.customer_type');
					$customer_id          				= $this->Session->read("Customers.id");
					$member_id          				= $this->Session->read("Members.id");
					$member_type 						= $this->Session->read('Members.member_type');
					$browser 							= isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:"-";
					$ApplyonlineMessageEntity					= $this->ApplyonlineMessage->newEntity();
					$ApplyonlineMessageEntity->application_id 	= $id;
					$ApplyonlineMessageEntity->message 			= strip_tags($message);
					$ApplyonlineMessageEntity->user_type 		= !empty($customer_type)?$customer_type:0;
					$ApplyonlineMessageEntity->user_id 			= !empty($customer_id)?$customer_id:$member_id;
					$ApplyonlineMessageEntity->ip_address 		= $this->IP_ADDRESS;
					$ApplyonlineMessageEntity->created 			= $this->NOW();
					$ApplyonlineMessageEntity->browser_info 	= json_encode($browser);
					if (isset($ApplyonlineMessage['last_message_id']) && !empty($ApplyonlineMessage['last_message_id']))
					{
						$ApplyonlineMessageEntity->reply_msg_id = decode($ApplyonlineMessage['last_message_id']);
						$ApplyonlineMessageEntity->for_claim	= 2;
					}
					if($this->ApplyonlineMessage->save($ApplyonlineMessageEntity)) {
						$applyid = $applyOnlinesData->id;
						if(!empty($applyid)) {
							$data 				= $this->ApplyOnlines->get($applyid);
							$data->query_sent 	= '0';
							$data->query_date 	= date('0-0-0 0:0:0');
							$data->modified 	= date('Y-m-d H:i:s');
							$this->ApplyOnlines->save($data);
						}

						/** Update Subsidy Claim Messge as Replied By Client */
						if (isset($ApplyonlineMessage['last_message_id']) && !empty($ApplyonlineMessage['last_message_id']))
						{
							$MessageDetails 	= $this->ApplyonlineMessage->get(decode($ApplyonlineMessage['last_message_id']));
							$Message_For 		= 0;
							if (!empty($MessageDetails)) {
								$Message_For 				= $MessageDetails->user_id;
								$MessageDetails->for_claim 	= 2;
								$this->ApplyonlineMessage->save($MessageDetails);
							}
							/** Insert Unread Message Counter */
							$this->ApplyonlineUnReadMessage->saveUnReadMessage($ApplyonlineMessageEntity->id,$Message_For);
							/** Insert Unread Message Counter */
						}
						/** Update Subsidy Claim Messge as Replied By Client */
						$this->ApplyOnlines->SendEmailToCustomer($id,$ApplyonlineMessageEntity->id);
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
			$applyOnlinesData 		= $this->ApplyOnlines->viewApplication($id);
			if (!empty($applyOnlinesData)) {
				if ($this->request->is('post')) {
					$output_quota 	= $this->ApplyOnlines->checked_total_capacity($applyOnlinesData->installer_id,$applyOnlinesData->pv_capacity,$applyOnlinesData->category,$applyOnlinesData->social_consumer,$applyOnlinesData->id,$applyOnlinesData->disclaimer_subsidy);
					if($output_quota===true)
					{
						$ApplyonlinDocsEntity = $this->ApplyonlinDocs->newEntity();
						if(!empty($this->request->data['file']['name']))
						{
							$prefix_file = '';
							$name=$this->request->data['file']['name'];
							$ext 			= substr(strtolower(strrchr($name, '.')), 1);
							$file_name 		= $prefix_file.date('Ymdhms').rand();

							$uploadPath = APPLYONLINE_PATH.$id.'/';
							if(!file_exists(APPLYONLINE_PATH.$id)) {
								@mkdir(APPLYONLINE_PATH.$id, 0777);
							}
							$file_location 	= WWW_ROOT.$uploadPath.'doc'.'_'.$file_name.'.'.$ext;
							if(move_uploaded_file($this->request->data['file']['tmp_name'],$file_location))
							 {
								$couchdbId 		= $this->Couchdb->saveData($uploadPath,$file_location,$prefix_file,'doc_'.$file_name.'.'.$ext,$this->Session->read('Customers.id'),'Signed_Doc');
								$ApplyonlinDocsEntity->couchdb_id		= $couchdbId;
								$ApplyonlinDocsEntity->application_id	= $id;
								$ApplyonlinDocsEntity->file_name        = 'doc'.'_'.$file_name.'.'.$ext;
								$ApplyonlinDocsEntity->doc_type         = 'Signed_Doc';
								$ApplyonlinDocsEntity->title            = 'Upload_Document';
								$ApplyonlinDocsEntity->created          = $this->NOW();

								 //pr($ApplyonlinDocsEntity);exit;
								 $application_status = $this->ApplyOnlineApprovals->APPLICATION_SUBMITTED;
								 $this->ApplyOnlines->updateAll(array('application_status'=>$application_status),array('id'=>$id));
								 $customer_id 			= $this->Session->read("Customers.id");
								 $this->ApplyOnlineApprovals->saveStatus($id,$this->ApplyOnlineApprovals->APPLICATION_SUBMITTED,$customer_id,'');
								//if($applyOnlinesData->category == $this->ApplyOnlines->category_residental)
								$applyOnlinesOthersData 	= $this->ApplyOnlinesOthers->find('all',array('conditions'=>array('application_id'=>$id)))->first();
								/*if(($applyOnlinesOthersData->renewable_attr === 0 || $applyOnlinesOthersData->renewable_attr == 1) && $applyOnlinesData->category != $this->ApplyOnlines->category_residental)
								{

								}
								else
								{*/ // && $applyOnlinesOthersData->contract_load_more!=1
								if($applyOnlinesData->social_consumer == 0){
									$application_status = $this->ApplyOnlineApprovals->APPROVED_FROM_GEDA;
									$this->ApplyOnlines->updateAll(array('application_status'=>$application_status),array('id'=>$id));
									$this->ApplyOnlineApprovals->saveStatus($id,$this->ApplyOnlineApprovals->APPROVED_FROM_GEDA,$customer_id,'');
									$geda_application_no 	= $this->ApplyOnlines->GenerateGedaApplicationNo($applyOnlinesData);
									$this->ApplyOnlines->updateAll(array('geda_application_no'=>$geda_application_no),array('id'=>$applyOnlinesData->id));
								}
								//}


							}
						}

						if($this->ApplyonlinDocs->save($ApplyonlinDocsEntity)) {
							$this->SendApplicationLetterToCustomer($id);
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
					else
					{
						$ErrorMessage 	= $output_quota;
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
	*
	* getApplicationData
	*
	* Behaviour : public
	*
	* Parameter : discom
	*
	* @defination : Method is used to get subdivision from consumer number.
	*
	*/
	public function getApplicationData() {
		$this->autoRender 			= false;
		$consumer_no 				= isset($this->request->data['consumer_num'])?$this->request->data['consumer_num']:0;
		$discom_data 				= isset($this->request->data['discom_data'])?$this->request->data['discom_data']:0;
		$app_id 				= isset($this->request->data['apllication_id'])?$this->request->data['apllication_id']:0;
		$t_no 				= isset($this->request->data['t_no'])?$this->request->data['t_no']:0;
		$data 						= $this->getdetailofappdata($consumer_no,$discom_data,$app_id,$t_no);
		$this->ApiToken->SetAPIResponse('msg', 'list of subdivision');
		$this->ApiToken->SetAPIResponse('data', $data);
		$this->ApiToken->SetAPIResponse('type','ok');
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	private function getdetailofappdata($consumer_no,$discom_data,$app_id,$t_no)
	{

		$data_subdiv				= array();
		if (!empty($consumer_no)) {
			$discom_details 		= $this->DiscomMaster->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['DiscomMaster.short_code'=>substr($consumer_no,0,3),'DiscomMaster.type'=>4,'status'=>'1']]);
			$arr_dis_details 		= $discom_details->toarray();
			if (!empty($arr_dis_details)) {
				$data_subdiv['subdivision']	= $arr_dis_details;
				$discom_data_details		= $this->DiscomMaster->find("all",['conditions'=>['id'=>key($arr_dis_details),'status'=>'1']])->first();
				$division_data 				= $this->DiscomMaster->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['circle'=>$discom_data_details->circle,'type'=>'3','status'=>'1']]);
				$data_subdiv['division'] 	= $division_data;
				$data_subdiv['seldivision']	= $discom_data_details->division;
				$branch_detail 		= $this->BranchMasters->find("all",array('conditions'=>array('discom_id'=>$discom_data_details->area)))->first();
				$data_subdiv['seldiscom']	= $branch_detail->id;
			}
			else
			{
				$data_subdiv['subdivision']	= array('id'=>'');
				$data_subdiv['division']	= '';
			}
		}
		else
		{
			$data_subdiv['subdivision']		= array('id'=>'');
			$data_subdiv['division']		= '';
		}
		return $data_subdiv;
	}
	/*public function update_apply_online()
	{
		$this->autoRender	= false;
		$arr_all_data 		= $this->ApplyOnlines->find('all')->toArray();
		$counter 			= 0;
		foreach($arr_all_data as $apply_data)
		{
			$aadhar_no_or_pan_card_no 	= passencrypt($apply_data->aadhar_no_or_pan_card_no);
			$pan_card_no 				= passencrypt($apply_data->pan_card_no);
			$house_tax_holding_no 		= passencrypt($apply_data->house_tax_holding_no);
			$this->ApplyOnlines->updateAll(['aadhar_no_or_pan_card_no' => $aadhar_no_or_pan_card_no,'pan_card_no' => $pan_card_no,'house_tax_holding_no' => $house_tax_holding_no], ['id' => $apply_data->id]);
			$counter++;
		}
		echo 'Total '.$counter.' records updated';
	}*/

	/**
	 * ApprovePayment
	 * Behaviour : Public
	 * @defination : Method is use to approve fesibility payment
	 */
	public function ApprovePayment()
	{
		$this->autoRender 	= false;
		$member_id          = $this->Session->read("Members.id");
		$id 				= (isset($this->request->data['application_id'])?$this->request->data['application_id']:0);
		$mode 			    = (isset($this->request->data['payment_approve'])?$this->request->data['payment_approve']:'');
		$message 			= (isset($this->request->data['message'])?$this->request->data['message']:'');
		if(empty($id) || empty($message)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} else {
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$FeasibilityData 		= $this->FesibilityReport->find("all",['conditions'=>['application_id'=>$id]])->first();
			$Feasibility_Data 		= $this->FesibilityReport->get($FeasibilityData->id);
			if (!empty($Feasibility_Data)) {
				if ($this->request->is('post')) {
					$FesibilityReportData 		= $this->FesibilityReport->patchEntity($Feasibility_Data,$this->request->data);
					$FesibilityReportData->application_id   = $id;
					if(!empty($this->request->data['file']['name']))
					{
						$prefix_file = '';
						$name=$this->request->data['file']['name'];
						$ext 			= substr(strtolower(strrchr($name, '.')), 1);
						$file_name 		= $prefix_file.date('Ymdhms').rand();


						$uploadPath = WWW_ROOT.FEASIBILITY_PATH.$id.'/'.'paymentdata'.'/';
						if(!file_exists(FEASIBILITY_PATH.$id.'/'.'paymentdata')){
								@mkdir(FEASIBILITY_PATH.$id.'/'.'paymentdata', 0777,true);
							}
						$uploadFile  = $uploadPath.$file_name.'.'.$ext;
						
						if(move_uploaded_file($this->request->data['file']['tmp_name'],$uploadFile))
						{
							$couchdbId 		= $this->Couchdb->saveData($uploadPath,$uploadFile,$prefix_file,$file_name.'.'.$ext,$member_id,'paymentdata');
							$FesibilityReportData->file_name = $file_name.'.'.$ext;
						}
					}

					$FesibilityReportData->message 			= strip_tags($message);
					$FesibilityReportData->payment_approve 	= $mode;
					if($this->FesibilityReport->save($FesibilityReportData)) {
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
	 * ApprovePayment
	 * Behaviour : Public
	 * @defination : Method is use to approve fesibility payment
	 */
	public function ApproveGeda()
	{
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['geda_id'])?$this->request->data['geda_id']:0);
		$approve_status 			    = (isset($this->request->data['geda_approve'])?$this->request->data['geda_approve']:'');
		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} else {
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->ApplyOnlines->viewApplication($id);
			if (!empty($applyOnlinesData)) {
				if ($this->request->is('post')) {
					if($approve_status == 1)
					{
						$applyOnlinesData->application_status = $this->ApplyOnlineApprovals->APPROVED_FROM_GEDA;
						$geda_application_no 	= $this->ApplyOnlines->GenerateGedaApplicationNo($applyOnlinesData);
						$this->ApplyOnlines->updateAll(array('geda_application_no'=>$geda_application_no),array('id'=>$applyOnlinesData->id));
					}
					else
					{
						$applyOnlinesData->application_status = $this->ApplyOnlineApprovals->REJECTED_FROM_GEDA;
					}
					$status = ($approve_status == 1)?$this->ApplyOnlineApprovals->APPROVED_FROM_GEDA:$this->ApplyOnlineApprovals->REJECTED_FROM_GEDA;
					$reason ="";
					$this->SetApplicationStatus($status,$id,$reason);

					if($this->ApplyOnlines->save($applyOnlinesData)) {
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
	public function test_fesibility()
	{
		$this->autoRender 	= false;
		//$this->FesibilityReport->fetchApiFeasibility('315');
		//echo $this->FesibilityReport->ValidateApplicationPayment('3000');
		$this->ChargingCertificate->fetchApiMeterInstallation('315');
		exit;
	}
	/**
	 * SelfCertification
	 * Behaviour : Public
	 * @defination : Method is use to upload self certificate.
	 */
	public function SelfCertification()
	{
		$this->autoRender 	= false;
		$customerId 		= $this->Session->read("Customers.id");
		$id 				= (isset($this->request->data['application_id'])?$this->request->data['application_id']:0);
		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		}
		else{
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->ApplyOnlines->viewApplication($id);
			if (!empty($applyOnlinesData)) {
				if ($this->request->is('post')) {
					$ApplyonlinDocsEntity = $this->ApplyonlinDocs->newEntity();
					if(!empty($this->request->data['file']['name']))
					{
						$prefix_file = '';
						$name=$this->request->data['file']['name'];
						$ext 			= substr(strtolower(strrchr($name, '.')), 1);
						$file_name 		= $prefix_file.date('Ymdhms').rand();

						$uploadPath = APPLYONLINE_PATH.$id.'/';
						if(!file_exists(APPLYONLINE_PATH.$id)) {
							@mkdir(APPLYONLINE_PATH.$id, 0777);
						}
						$file_location 	= WWW_ROOT.$uploadPath.'doc'.'_'.$file_name.'.'.$ext;
						if(move_uploaded_file($this->request->data['file']['tmp_name'],$file_location))
						 {
						 	$passFileName   = 'doc'.'_'.$file_name.'.'.$ext;
							$couchdbId      = $this->Couchdb->saveData($uploadPath,$file_location,$prefix_file,$passFileName,$customerId,'Self_Certificate');
							$ApplyonlinDocsEntity->application_id	= $id;
							$ApplyonlinDocsEntity->file_name        = 'doc'.'_'.$file_name.'.'.$ext;
							$ApplyonlinDocsEntity->doc_type         = 'Self_Certificate';
							$ApplyonlinDocsEntity->title            = 'Self Certificate';
							$ApplyonlinDocsEntity->created 			= $this->NOW();
							$ApplyonlinDocsEntity->couchdb_id 		= $couchdbId;
							$this->FesibilityReport->Cei_All_Stage_APProved($id,'first');
						}
					}
					if($this->ApplyonlinDocs->save($ApplyonlinDocsEntity)) {
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
	 * getSearchedData
	 * Behaviour : Public
	 * @defination : Method is use to fetch api response from apply_online database.
	 */
	public function getSearchedData()
	{
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['id'])?$this->request->data['id']:0);
		$discom 			= (isset($this->request->data['discom'])?$this->request->data['discom']:0);
		$applyDetails 		= $this->ApplyOnlines->find('all',array('conditions'=>array('id'=>$id)))->first();
		$data 				= array();
		if(!empty($applyDetails->api_response))
		{
			$data 			= $this->getdetailsSubdivision('0',$discom,$applyDetails->project_id,$id,'searched');
		}
		$this->ApiToken->SetAPIResponse('msg', 'list of subdivision');
		$this->ApiToken->SetAPIResponse('data', $data);
		$this->ApiToken->SetAPIResponse('type','ok');
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	public function call_shell()
	{
		$this->autoRender 	= false;
		//$myShell = new \App\Shell\HelloShell;
		echo exec('php /var/www/vhosts/ahasolar.in/demo-gujarat.ahasolar.in/bin/cake.php Hello');
		//$myShell->main();
		exit;
	}
	public function getSubDivisionTorrent()
	{
		$this->autoRender 	= false;
		$division_id 			= isset($this->request->data['division'])?$this->request->data['division']:0;
		$discom_details 		= $this->DiscomMaster->find("all",['conditions'=>['DiscomMaster.division'=>$division_id,'DiscomMaster.type'=>4,'status'=>'1']])->first();
		$data['subdivision']	= '';
		if(!empty($discom_details))
		{
			$data['subdivision']= $discom_details->title;
		}
		$this->ApiToken->SetAPIResponse('msg', 'list of subdivision');
		$this->ApiToken->SetAPIResponse('data', $data);
		$this->ApiToken->SetAPIResponse('type','ok');
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	/**
	 * ReopenApplication
	 * Behaviour : Public
	 * @defination : Method is use to reopen application which having status cancelled.
	 */
	public function ReopenApplication()
	{
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['reopen_application_id'])?$this->request->data['reopen_application_id']:0);
		$message 			= 'Reopen by GEDA - '.(isset($this->request->data['message'])?($this->request->data['message']):"");
		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} else {
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->ApplyOnlines->viewApplication($id);
			if (!empty($applyOnlinesData)) {
				if ($this->request->is('post')) {
					$member_id          				= $this->Session->read("Members.id");
					$member_type 						= $this->Session->read('Members.member_type');
					$browser 							= isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:"-";
					$ApplyonlineMessageEntity					= $this->ApplyonlineMessage->newEntity();
					$ApplyonlineMessageEntity->application_id 	= $id;
					$ApplyonlineMessageEntity->message 			= strip_tags($message);
					$ApplyonlineMessageEntity->user_type 		= !empty($member_type)?$member_type:0;
					$ApplyonlineMessageEntity->user_id 			= !empty($member_id)?$member_id:0;
					$ApplyonlineMessageEntity->ip_address 		= $this->IP_ADDRESS;
					$ApplyonlineMessageEntity->created 			= $this->NOW();
					$ApplyonlineMessageEntity->browser_info 	= json_encode($browser);
					if($this->ApplyonlineMessage->save($ApplyonlineMessageEntity)) {
						$this->ApplyOnlineApprovals->deleteAll(['application_id' => $id,'stage'=>$this->ApplyOnlineApprovals->APPLICATION_CANCELLED]);
						$application_last_stage 				= $this->ApplyOnlineApprovals->getLastStage($id);
						$application_status 					= $application_last_stage->stage;
						$this->ApplyOnlines->updateAll(array('application_status'=>$application_status),array('id'=>$id));
						$this->SetApplicationStatus('CANCELLED_REOPEN',$id);
						$ErrorMessage 	= "Application reopened successfully.";
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
	 * ReopenApplication
	 * Behaviour : Public
	 * @defination : Method is use to reopen application which having status cancelled.
	 */
	public function ResetApplication()
	{
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['reset_application_id'])?$this->request->data['reset_application_id']:0);
		$message 			= 'Reset by GEDA - '.(isset($this->request->data['message'])?($this->request->data['message']):"");
		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} else {
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->ApplyOnlines->viewApplication($id);
			$validation_quota 		= $this->ApplyOnlines->checked_total_capacity($applyOnlinesData->installer_id,$applyOnlinesData->pv_capacity,$applyOnlinesData->category,$applyOnlinesData->social_consumer,$applyOnlinesData->id,$applyOnlinesData->disclaimer_subsidy);
			if (!empty($applyOnlinesData) && $validation_quota===true) {
				if ($this->request->is('post')) {
					$member_id          				= $this->Session->read("Members.id");
					$member_type 						= $this->Session->read('Members.member_type');
					$browser 							= isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:"-";
					$ApplyonlineMessageEntity					= $this->ApplyonlineMessage->newEntity();
					$ApplyonlineMessageEntity->application_id 	= $id;
					$ApplyonlineMessageEntity->message 			= strip_tags($message);
					$ApplyonlineMessageEntity->user_type 		= !empty($member_type)?$member_type:0;
					$ApplyonlineMessageEntity->user_id 			= !empty($member_id)?$member_id:0;
					$ApplyonlineMessageEntity->ip_address 		= $this->IP_ADDRESS;
					$ApplyonlineMessageEntity->created 			= $this->NOW();
					$ApplyonlineMessageEntity->browser_info 	= json_encode($browser);
					if($this->ApplyonlineMessage->save($ApplyonlineMessageEntity)) {

						$this->ApplyOnlineApprovals->deleteAll(['application_id' => $id]);
						$application_status 					= $this->ApplyOnlineApprovals->APPLICATION_GENERATE_OTP;
						$this->ApplyOnlines->updateAll(array('application_status'=>$application_status),array('id'=>$id));

						$sms_mobile 							= $applyOnlinesData->consumer_mobile;

						$sms_message 		= str_replace('##application_no##',$applyOnlinesData->application_no, OTP_VERIFICATION_RESET);
						$this->ApplyOnlines->SendSMSActivationCode($id,$sms_mobile,$sms_message,'OTP_VERIFICATION_RESET');
						$signed_doc_details = $this->ApplyonlinDocs->find('all',array('conditions'=>array('application_id' => $id,'doc_type'=>'Signed_Doc')))->toArray();
						foreach($signed_doc_details as $signed_doc)
						{
							$path = APPLYONLINE_PATH.$id.'/'.$signed_doc['file_name'];
							if (file_exists($path))
							{
								unlink($path);
							}
						}
						$this->ApplyonlinDocs->deleteAll(['application_id' => $id,'doc_type'=>'Signed_Doc']);
						$ErrorMessage 	= "Application reset successfully.";
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
				$ErrorMessage 		= "Invalid Request. Please validate form details.";
				$success 			= 0;
				if(!empty($validation_quota))
				{
					$ErrorMessage 	= $validation_quota;
				}
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	public function put_waitinglist()
	{
		$this->autoRender 	= false;
		$sql 					= "SELECT apply_onlines.id as apply_id,ic.capacity,ic.id from apply_onlines left join installer_category_mapping as icm on apply_onlines.installer_id=icm.installer_id left join installer_category as ic on icm.category_id=ic.id WHERE apply_onlines.id In (SELECT apply_onlines.id FROM `apply_onlines` LEFT JOIN apply_online_approvals ON apply_online_approvals.application_id = apply_onlines.id WHERE apply_onlines.installer_id in (select installer_id from installer_category_mapping where category_id=2) and apply_onlines.social_consumer='0' AND apply_online_approvals.stage IN ('23', '31') GROUP by apply_online_approvals.application_id) AND apply_onlines.created between '2018-10-25 00:00:00' and '2018-10-26 00:00:00' order by apply_onlines.id asc";
		$sql 					= "SELECT apply_onlines.id as apply_id,ic.capacity,ic.id from apply_onlines left join installer_category_mapping as icm on apply_onlines.installer_id=icm.installer_id left join installer_category as ic on icm.category_id=ic.id WHERE apply_onlines.id In (SELECT apply_onlines.id FROM `apply_onlines` LEFT JOIN apply_online_approvals ON apply_online_approvals.application_id = apply_onlines.id WHERE apply_onlines.installer_id in (select installer_id from installer_category_mapping where category_id=2) and apply_onlines.social_consumer='0' AND apply_online_approvals.stage = '31' and apply_online_approvals.created between '2018-10-21 00:00:00' and '2018-10-22 00:00:00' GROUP by apply_online_approvals.application_id) and application_status = '31' order by apply_onlines.id asc";
		$connection             = ConnectionManager::get('default');
		$applicationData_output = $connection->execute($sql)->fetchAll('assoc');
		//print_r($applicationData_output);
		$arr_data=array();
		foreach($applicationData_output as $application)
		{
			$arr_data[] = $application['apply_id'];
			$sql_ins = "insert into apply_online_approvals(`application_id`,`member_id`,`stage`,`reason`,`created`) values('".$application['apply_id']."','1','30','PV capacity quota has been finished','".date('Y-m-d H:i:s')."')";
			echo $sql_ins;
			$connection->execute($sql_ins);
			$sql_upd = "update `apply_onlines` set application_status='30' where id='".$application['apply_id']."'";
			$connection->execute($sql_upd);
		}
		echo count($arr_data).'------<br>';
		echo implode(",", $arr_data);
		exit;
	}
	public function make_meter_data()
	{
		//$this->ChargingCertificate->fetchApiMeterInstallation(469);
	}
	public function make_fesibility()
	{
		$this->autoRender = false;
		//$arr_data=array(793,809,1077,1549,2047,2546,3445,4338,1319,2255,2242,5221,4298,4296,4280,4293,3802,5312,5315,2562,928,1001,2052,2188,2823,6733,5758,5785,1220,5722,15,1010,1116,5846,1962,2896,606,1256,3315,853,869,1034,1560,1768,1891,1899,2254,2339,2408,2485,2519,2815,3046,3117,3951,4003,1274,1393,1583,1885,1998,2307,2838,2929,3376,499,929,1292,1704,1993,2144,6469,6504,6562,1083,1090,1500,1710,1757,1797,2500,2556,2570,4145,4774,5243,283,1102,1716,2414,4936,1428,1437,1455,1796,1881,2644,3143,3377,4725,5247,6596,1568,1791,2477,481,2215,4212,7101,7700,1073,3501,3553,6344,1829,3388,3397,8180,8218,2175,2240,2249,2291,3006,3010,3013,3017,3382,3398,8290,1790,1451,2775,5467,5595,2078,1627,2323,2849,2927,3394,7797,7812,7625,834,991,7502,2835);
		$arr_data=array(8030);
		$count=0;
		foreach($arr_data as $f_data)
		{
			echo $f_data.'<br>';
			$count++;
			$this->FesibilityReport->fetchApiFeasibility($f_data);

		}
		echo $count;
		exit;
	}
	public function make_fesibility_update()
	{
		$this->autoRender = false;
		//$arr_data=array(145,469,883,1694,1209,666,670,445,2792,2805,2863,644,1763,3998,4506,358,660,2230,832,824,930,327,641,743,819,855,1511,3241,688,863,256,828,6347,2475,412,1800,1863,4461,4471);
		$arr_data=array(41761);
		$count=0;
		foreach($arr_data as $application_id)
		{
			echo $application_id.'<br>';
			$count++;
			//$this->FesibilityReport->fetchApiFeasibility($f_data);
			$ApplyOnlines               = TableRegistry::get('ApplyOnlines');
			$applyOnlinesData           = $ApplyOnlines->viewApplication($application_id);

			$branch_master              = TableRegistry::get('BranchMasters');
			$branchDetails              = $branch_master->find('all',array('conditions'=>array('discom_id'=>$applyOnlinesData->area)))->first();
			$discom_id                  = $branchDetails->id;
			$thirpartyApi               = TableRegistry::get('ThirdpartyApiLog');
				 $feasibility_api_data       = $thirpartyApi->searchFeasibilityApi($applyOnlinesData->consumer_no,$discom_id,$applyOnlinesData->project_id,$applyOnlinesData->id);
		//echo '<pre>';
		//print_r($feasibility_api_data);
			if(!empty($feasibility_api_data))
			{
				if($feasibility_api_data->P_OUT_STS_CD == 1  || $feasibility_api_data->P_OUT_STS_CD == -1)
				{
					$output_details_obj     = $feasibility_api_data->P_OUT_DATA->OUTPUT_DATA;
					$quotation_number       = $output_details_obj->SR_NUMBER;
					$estimated_amount       = $output_details_obj->FQ_AMT;
					$estimated_due_date     = $output_details_obj->FQ_DUE_DATE;
					$payment_date           = $output_details_obj->FQ_PAID_DATE;
					$sanction_load          = isset($output_details_obj->LOAD) ? $output_details_obj->LOAD : $applyOnlinesData->sanction_load_contract_demand;
					if(trim($quotation_number)!='' && trim($estimated_amount)!='' && trim($estimated_due_date)!='')
					{
						$Members                = TableRegistry::get('Members');
						$members_data           = $Members->find('all',array('conditions'=>array('division'=>$applyOnlinesData->division,'subdivision'=>'0')))->first();
						$ApplyOnlines           = TableRegistry::get('ApplyOnlines');
						$applyOnline_data       = $ApplyOnlines->find('all',array('conditions'=>array('id'=>$application_id)))->first();
						$arrFesibility          = $this->FesibilityReport->find('all',array('conditions'=>array('application_id'=>$application_id)))->first();
						$NEWRECORD  = false;
						$pass_data = array();
						if (!empty($arrFesibility)) {
							$FesibilityReport                               = $this->FesibilityReport->get($arrFesibility->id);
							$FesibilityReport                               = $this->FesibilityReport->patchEntity($FesibilityReport,$pass_data);
							$FesibilityReport->id                           = $arrFesibility->id;
						} else {
							$FesibilityReport                               = $this->FesibilityReport->newEntity($pass_data);
							$NEWRECORD                                      = true;
							$FesibilityReport->created                      = $this->NOW();
							$FesibilityReport->created_by                   = $members_data->id;
						}
						$FesibilityReport->sanction_load                    = $sanction_load;//applyOnlinesData->sanction_load_contract_demand;
						$FesibilityReport->category                         = $applyOnlinesData->category;
						$FesibilityReport->recommended_capacity_by_discom   = $applyOnlinesData->pv_capacity;
						$FesibilityReport->field_officer                    = $applyOnlinesData->installer['installer_name'];
						$FesibilityReport->application_id                   = $application_id;
						$FesibilityReport->application_fee                  = $applyOnlinesData->disCom_application_fee;
						$FesibilityReport->quotation_number                 = $quotation_number;
						$FesibilityReport->estimated_amount                 = $estimated_amount;
						$FesibilityReport->estimated_due_date               = $estimated_due_date;
						$FesibilityReport->payment_date                     = $payment_date;
						echo $applyOnline_data->created;
						$checked_date   = strtotime($applyOnline_data->created);
						if(!empty($payment_date) && $payment_date!='0000-00-00'  && strtotime($payment_date) > $checked_date)
						{
							/*$payment                            = strtotime($payment_date);
							$EstimationGenerationDate           = strtotime($estimated_due_date);
							$arr_data_est                       = explode("-",$estimated_due_date);
							$expire_time = mktime(0,0,0,$arr_data_est[1],$arr_data_est[2]+32,$arr_data_est[0]);

						  //  echo $NoDaysSinceApplicationEstimation.'<br>';
							if ($payment >=$EstimationGenerationDate && $payment <= $expire_time)
							{*/
								$FesibilityReport->payment_approve              = '1';
							//}
						}
						$FesibilityReport->modified                         = $this->NOW();
						$FesibilityReport->modified_by                      = $members_data->id;
						$FesibilityReport->division_approved_by             = $members_data->id;
						$FesibilityReport->division_approved_date           = $this->NOW();
						$this->FesibilityReport->save($FesibilityReport);
					}
				}
			}
		}
		echo $count;
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
			$applyOnlinesData 		= $this->ApplyOnlines->viewApplication($id);
			if (!empty($applyOnlinesData)) {
				if ($this->request->is('post')) {
						$ApplyonlinDocsEntity = $this->ApplyonlinDocs->newEntity();
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

							$uploadPath = APPLYONLINE_PATH.$id.'/';
							if(!file_exists(APPLYONLINE_PATH.$id)) {
								@mkdir(APPLYONLINE_PATH.$id, 0777);
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
						if($this->ApplyonlinDocs->save($ApplyonlinDocsEntity)) {
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
	/**
	 * RemoveApplication
	 * Behaviour : Public
	 * @defination :Delete Application of Waiting List.
	 */
	public function RemoveApplication()
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
			if(!empty($this->Session->read("Members.id"))) {
				$customerId = $this->Session->read("Members.id");
			} else {
				$customerId = $this->Session->read("Customers.id");
			}
			$applyOnlinesData 		= $this->ApplyOnlines->viewApplication($id);
			$application_data 		= $this->ApplyOnlines->find('all',array('conditions'=>array('id'=>$id)))->toArray();
			$proj_id 				= $applyOnlinesData['project_id'];
			if (!empty($applyOnlinesData)) {
				if ($this->request->is('post')) {
					$this->InstallerProjects->deleteAll(['project_id' => $proj_id]);
					$proj_data=$this->Projects->get($proj_id);
					$this->Projects->delete($proj_data);
					$this->ApplyonlinDocs->deleteAll(['application_id' => $id]);
					$this->ApplyOnlineApprovals->deleteAll(['application_id' => $id]);
					$entity = $this->ApplyOnlines->get($id);
					$this->ApplyOnlines->delete($entity);
					$path = APPLYONLINE_PATH.$id;
					if (file_exists($path))
					{
						$removedir = "rm -rf ".$path;
						system($removedir);
					}
					$browser 					   = isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:"-";
					$removeentity                  = $this->ApplicationDeleteLog->newEntity();
					$removeentity->application_id  = $id;
					$removeentity->customer_id     = $customerId;
					$removeentity->ip_address      = $this->IP_ADDRESS;
					$removeentity->browser_info	   = json_encode($browser);
					$removeentity->application_data= json_encode($application_data);
					$removeentity->created 		   = $this->NOW();

					if($this->ApplicationDeleteLog->save($removeentity)){
						$ErrorMessage 	= "The Application has been deleted from Unified Single Window Portal of GEDA.";
						$success 		= 1;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					}
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate Details.";
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
	 * payment_receipt
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to view geda approval letter
	 *
	 */
	public function payment_receipt($id = null)
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
		$pdf_path = $this->ApplyOnlines->generatePaymentReceiptPdf($id,false);
	}
	/**
	 *
	 * getAgreementLetter
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to view installer
	 *
	 */
	public function getAgreementLetter($id = null)
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
		$pdf_path = $this->generateAgreementLetter($id,false);
	}
	/**
	 *
	 * updateApiData
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to fetch torrent and GUVNL API data and update in our database.
	 *
	 */
	public function updateApiData($id=null)
	{
		$this->autoRender	= false;
		$application_id = intval(decode($this->request->data['update_application_id']));
		if(!empty($application_id))
		{
			echo $this->CommonFetchDetails($application_id);
			exit;
		}
	}
	/**
	 *
	 * download_document
	 *
	 * Behaviour : Public
	 *
	 *@param : pass type and encrypted id of application/document
	 *
	 * @defination : Method is use to download/view image and document attached with application.
	 *
	 */
	public function download_document($type=null,$id=null)
	{
		$this->autoRender	= false;
		if(!empty($this->Session->read('Members.member_type')))
		{
			$customerId 		= $this->Session->read("Members.id");
		}
		else
		{
			$customerId 		= $this->Session->read("Customers.id");
		}
		if(empty($customerId) && !in_array($type,array('gst_certificate','upload_undertaking','pan_card','registration_document','fr_copy_registration','fr_pan_card','fr_receipt','fr_indemnity_bond','fr_account_cheque','d_gst_certificate','d_upload_undertaking','d_pan_card','d_registration_document','d_file_board','a_pan_card','a_registration_document','a_msme','a_upload_undertaking','a_file_board','f_sale_discom','geo_cordinate_file')))
		{
			return $this->redirect('/home');
		}
		$this->ApplyOnlines->donwload_view_docs($type,$id);

	}

	/**
	 * trackapplication
	 * Behaviour : Public
	 * @defination : Method is use to track consumer application.
	 */
	public function trackapplication()
	{
		$ApplyOnlineLead 	= array();
		$InvalidID 			= false;
		if(isset($this->request->data) && !empty($this->request->data) && isset($this->request->data['geda_application_no']) && !empty($this->request->data['geda_consumer_no']) && !empty($this->request->data['geda_application_no']) && !empty($this->request->data['geda_mobile_no']))
		{
			$geda_application_no 	= $this->request->data['geda_application_no'];
			$geda_consumer_no 		= $this->request->data['geda_consumer_no'];
			$geda_mobile_no 		= $this->request->data['geda_mobile_no'];
			$ApplyOnlineLead 		= $this->ApplyOnlines->FindByApplicationNo($geda_application_no,$geda_consumer_no,$geda_mobile_no);
			if (empty($ApplyOnlineLead)) {
				$InvalidID = true;
			}
		}
		elseif(isset($this->request->data) && !empty($this->request->data) && isset($this->request->data['geda_application_no']))
		{
			$InvalidID = true;
		}
		$state 		= $this->CUSTOMER_STATE_ID;
		$discom_arr = array();
		$discoms 	= $this->BranchMasters->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['BranchMasters.status'=>'1','BranchMasters.parent_id'=>'0','BranchMasters.state'=>$state]])->toArray();
		if(!empty($discoms)) {
			foreach($discoms as $id=>$title) {
				$discom_arr[$id] = $title;
			}
		}

		/* status of application */
		$this->set("APPLY_ONLINE_MAIN_STATUS",$this->ApplyOnlineApprovals->apply_online_main_status);
		$this->set("APPLICATION_SUBMITTED",$this->ApplyOnlineApprovals->APPLICATION_SUBMITTED);
		$this->set("FEASIBILITY_APPROVAL",$this->ApplyOnlineApprovals->FEASIBILITY_APPROVAL);
		$this->set("FUNDS_ARE_AVAILABLE_BUT_SCHEME_IS_NOT_ACTIVE",$this->ApplyOnlineApprovals->FUNDS_ARE_AVAILABLE_BUT_SCHEME_IS_NOT_ACTIVE);
		$this->set("FUNDS_ARE_NOT_AVAILABLE",$this->ApplyOnlineApprovals->FUNDS_ARE_NOT_AVAILABLE);
		$this->set("SUBSIDY_AVAILIBILITY",$this->ApplyOnlineApprovals->SUBSIDY_AVAILIBILITY);
		$this->set("WORK_STARTS",$this->ApplyOnlineApprovals->WORK_STARTS);
		$this->set("APPLICATION_GENERATE_OTP",$this->ApplyOnlineApprovals->APPLICATION_GENERATE_OTP);
		/* end status of application */
		$this->set("JREDA",$this->ApplyOnlines->JREDA);
		$this->set("DISCOM",$this->ApplyOnlines->DISCOM);
		$this->set("CEI",$this->ApplyOnlines->CEI);
		$this->set("MStatus",$this->ApplyOnlineApprovals);
		$this->set("ApplyOnlines",$this->ApplyOnlines);
		$this->set("FesibilityReport",$this->FesibilityReport);
		$this->set("application_status",$this->ApplyOnlineApprovals->application_status);
		$this->set("application_dropdown_status",$this->ApplyOnlines->apply_online_dropdown_status);
		$this->set("branch_id","");
		$this->set("subdivision","");
		$this->set('ApplyOnlineLeads',"");
		$this->set("ApplyOnlineLead",$ApplyOnlineLead);
		$this->set("MemberTypeDiscom",$this->Members->member_type_discom);
		$this->set("discom_details","");
		$this->set("payment_on",Configure::read('PAYUMONEY_PAYMENT'));
		$this->set("APPLY_ONLINE_GUJ_STATUS",$this->ApplyOnlineApprovals->apply_online_guj_status);
		$this->set("applyOnlinesDataDocList",$this->ApplyonlinDocs);
		$this->set('discom_arr',$discom_arr);
		$this->set('quota_msg_disp',"");
		$this->set('InvalidID',$InvalidID);
		$this->set('pageTitle','Track Consumer Application');
	}
	/**
	 * delete_application
	 * Behaviour : Public
	 * @defination : Method is use to delete application - to test delete application shell.
	 */
	public function delete_application()
	{
		$arrStatus 			= array($this->ApplyOnlineApprovals->APPLICATION_PENDING);
		$GUJARAT_STATE 		= 4;
		$Date 				= date('Y-m-d',strtotime(date('Y-m-d').' -3 days'));
		$arrApplications 	= $this->ApplyOnlines->find('all',
								[
									'fields'		=>['ApplyOnlines.id','apply_online_approvals.created'],
									'join'			=>[['table'=>'apply_online_approvals','type'=>'left','conditions'=>'apply_online_approvals.application_id = ApplyOnlines.id']],
									'conditions'	=> ['ApplyOnlines.application_status IN '=>$arrStatus,'ApplyOnlines.apply_state'=>$GUJARAT_STATE,'apply_online_approvals.created < ' => $Date,
										'apply_online_approvals.stage IN '=>$arrStatus]
								]
							);
		//print_r($arrApplications);
		foreach($arrApplications as $arrApplication)
		{
			$id 					= $arrApplication->id;
			//echo $id.'<br>';
		}
		//exit;
		foreach($arrApplications as $arrApplication)
		{
			$id 					= $arrApplication->id;
			$applyOnlinesData 		= $this->ApplyOnlines->viewApplication($id);
			$application_data 		= $this->ApplyOnlines->find('all',array('conditions'=>array('id'=>$id)))->toArray();
			$proj_id = $applyOnlinesData['project_id'];
			if (!empty($applyOnlinesData)) {
				$this->InstallerProjects->deleteAll(['project_id' => $proj_id]);
				$proj_data 			= $this->Projects->get($proj_id);
				$this->Projects->delete($proj_data);
				$this->ApplyonlinDocs->deleteAll(['application_id' => $id]);
				$this->ApplyOnlineApprovals->deleteAll(['application_id' => $id]);
				$entity 			= $this->ApplyOnlines->get($id);
				$this->ApplyOnlines->delete($entity);
				$path 				= APPLYONLINE_PATH.$id;
				if (file_exists($path))
				{
					$removedir 		= "rm -rf ".$path;
					system($removedir);
				}
				$application_data[0]['delete_type']= 'cron';
				$browser 					   	= isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:"-";
				$removeentity                  	= $this->ApplicationDeleteLog->newEntity();
				$removeentity->application_id  	= $id;
				$removeentity->customer_id     	= $application_data[0]['installer_id'];
				$removeentity->ip_address      	= $_SERVER['REMOTE_ADDR'];
				$removeentity->browser_info	   	= json_encode($browser);
				$removeentity->application_data	= json_encode($application_data);
				$removeentity->created 		   	= $this->NOW();
				if($this->ApplicationDeleteLog->save($removeentity)){
				echo "application ".$application_data['id']." deleted<br>";
				}
			}
		}
		exit;
	}
	/**
	 * update_execution_meter
	 * Behaviour : Public
	 * @defination : Method is use to update execution meter data - to manually update meter installation data using script.
	 */
	public function update_execution_meter()
	{
		/*$this->autoRender 	= false;
		$sql 				= "SELECT * from apply_onlines WHERE id In (6,9,16,27,36,41,47,51,54,76,88,91,99,104,108,112,116,117,119,120,122,127,138,145,151,152,154,156,160,166,168,170,201,206,216,217,225,230,232,242,244,249,256,268,272,273,281,287,289,300,304,311,316,318,326,341,345,346,347,349,353,358,361,381,383,385,389,412,418,420,423,428,433,436,439,441,445,453,462,469,473,476,486,489,519,521,527,529,535,544,545,552,562,582,586,594,620,629,633,637,642,643,644,659,662,666,670,675,676,686,687,688,691,696,697,712,735,772,801,824,834,842,844,849,861,863,889,899,901,904,914,930,933,936,968,985,998,1002,1015,1032,1036,1042,1051,1077,1101,1109,1114,1122,1123,1127,1129,1135,1177,1200,1209,1228,1267,1298,1319,1355,1393,1398,1399,1440,1451,1481,1500,1537,1546,1549,1576,1582,1584,1591,1602,1606,1610,1611,1634,1635,1637,1646,1659,1665,1667,1669,1671,1672,1688,1690,1694,1697,1720,1734,1740,1744,1745,1749,1750,1756,1758,1763,1772,1773,1775,1778,1782,1797,1800,1820,1825,1832,1838,1841,1845,1847,1850,1852,1858,1863,1867,1879,1886,1896,1908,1911,1933,1945,1948,1949,1955,2012,2035,2037,2055,2073,2078,2083,2091,2092,2097,2100,2104,2107,2110,2119,2141,2158,2162,2172,2191,2227,2238,2240,2249,2255,2257,2262,2278,2286,2291,2301,2304,2314,2323,2334,2342,2356,2400,2410,2411,2416,2417,2424,2435,2436,2437,2441,2442,2445,2450,2473,2475,2481,2486,2513,2521,2523,2527,2546,2548,2566,2596,2602,2646,2658,2677,2679,2687,2689,2711,2714,2729,2739,2744,2748,2753,2755,2759,2766,2768,2775,2779,2780,2789,2792,2805,2863,2876,2916,2921,2951,2963,3017,3020,3021,3057,3107,3111,3112,3115,3120,3135,3137,3143,3154,3183,3206,3228,3235,3244,3246,3292,3303,3316,3326,3337,3358,3363,3373,3392,3393,3406,3414,3420,3447,3450,3467,3485,3514,3555,3561,3573,3577,3580,3589,3592,3649,3766,3851,3930,3944,3950,3951,3959,3971,3986,3996,4016,4050,4176,4201,4247,4281,4341,4351,4375,4404,4441,4483,4503,4594,4640,4648,4675,4824,4857,4876,4899,4906,4920,4949,4954,4964,4988,4989,5018,5090,5120,5125,5132,5154,5160,5205,5212,5244,5255,5257,5258,5263,5265,5287,5292,5308,5311,5316,5397,5444,5500,5561,5569,5581,5619,5636,5650,5702,5796,5814,5824,5827,5868,5869,5874,5917,5967,5979,5987,6035,6056,6073,6090,6097,6127,6129,6240,6376,6543,6622,6710,6802,6824,6848,6864,6869,6983,7022,7042,7102,7313,7380,7405,7412,7513,7645,7739,7742,7752,7753,7761,7768,7770,7799,7820,7871,7885,7890,7894,7897,7971,8013,8021,8053,8056,8077,8100,8106,8216,8237,8248,8252,8254,8260,8402,8460,8461,8463,8464,8466,8473,8476,8484,8490,8495,8496,8522,8526,8536,8579,8580,8592,8608,8630,8646,8679,8732,8736,8750,8766,8838,8851,8871,8887,8888,8891,8918,8927,8934,8948,8949,8974,8990,8997,9056,9068,9069,9075,9076,9092,9094,9129,9136,9137,9142,9161,9209,9256,9257,9283,9333,9335,9381,9390,9395,9437,9439,9442,9444,9465,9498,9540,9543,9544,9545,9550,9556,9568,9600,9619,9626,9630,9631,9635,9644,9649,9656,9657,9676,9678,9695,9696,9697,9706,9715,9733,9755,9786,9788,9793,9796,9816,9823,9831,9853,9877,9899,9924,9951,9955,9968,9993,9999,10001,10002,10059,10100,10107,10133,10153,10190,10236,10246,10298,10354,10361,10410,10457,10463,10516,10562,10589,10649,10661,10690,10832,10837,10845,10859,10876,10940,10945,10966,10987,11014,11084,11086,11092,11111,11116,11156,11263,11318,11343,11397,11454,11496,11539,11558,11581,11591,11760,11815,11841,11872,11900,11942,11949,12127,12134,12142,12172,12176,12180,12184,12185,12225,12237,12245,12252,12337,12382,12400,12633,12642,12649,12675,12715,12797,12824,12825,12833,12836,12850,12856,12937,13103,13123,13144,13211,13270,13272,13297,13316,13473,13493,13625,13630,13667,13854,13953,14333,14363,14375,14379,14396,14454,14480,14660,14687,14776,14804,14816,14852,14903,14943,15054,15092,15093,15117,15494,15812,15919,16000,16143,16146,16156,16170,16232,16353,16724,16878,17110,17119,17337,17382,17568,17890,17898,17916,17997,18119,18127,18260,18618,18678,18810,19216,19248,19263,19372,19377,20042,20275,20492,20510,21927,22246,23114,23896,23968,23973,23984,23987,25379,26347,27246,28159,32418,35269)";
		$connection             = ConnectionManager::get('default');
		$applicationData_output = $connection->execute($sql)->fetchAll('assoc');
		//print_r($applicationData_output);
		$arr_data=array();
		echo '<pre>';
		foreach($applicationData_output as $application)
		{
			$arr_data[] = $application['id'];
			echo $application['id'].'-->'.$application['project_id'].'<br>';
			$Execution_data         = $this->Installation->find('all',array('conditions'=>array('project_id'=>$application['project_id'])))->first();
			$ChargingCertificate 	= $this->ChargingCertificate->find('all',array('conditions'=>array('application_id'=>$application['id'])))->first();
			if(!empty($Execution_data))
			{
				$arrUpdate          = array();
				if(empty($Execution_data->meter_serial_no))
				{
					$arrUpdate['meter_serial_no']       = $ChargingCertificate->bi_directional_meter;
				}
				if(empty($Execution_data->solar_meter_serial_no))
				{
					$arrUpdate['solar_meter_serial_no'] = $ChargingCertificate->solar_meter;
				}
				if(empty($Execution_data->bi_date) || $Execution_data->bi_date=='0000-00-00')
				{
					$arrUpdate['bi_date']               = $ChargingCertificate->meter_installed_date;
				}
				if(empty($Execution_data->agreement_date) || $Execution_data->agreement_date=='0000-00-00')
				{
					$arrUpdate['agreement_date']        = $ChargingCertificate->agreement_date;
				}
				if(!empty($arrUpdate))
				{
					print_r($arrUpdate);
					$this->Installation->updateAll($arrUpdate,array('project_id'=>$application['project_id']));
					$this->ChargingCertificate->updateAll(array('update_execution'=>'1'),array('id'=>$ChargingCertificate->id));
				}
			}
		}
		echo count($arr_data).'------<br>';
		echo implode(",", $arr_data);
		exit;*/
	}

	/**
	 * getapplicationsummary
	 * Behaviour : Public
	 * @defination : Method is use MNRE applications SUMMARY.
	 */
	public function getapplicationsummary()
	{
		$this->autoRender 	= false;
		$application_ids	= "24";
		echo $this->ApplyOnlines->generateSubsidySummarySheet($application_ids);
		exit;
	}
	/**
	 * test_shell_pcr
	 * Behaviour : Public
	 * @defination : Method is use test pcr submit shell script.
	 */
	public function test_shell_pcr()
	{
		$this->autoRender 			= false;
		$query_sent 		     	= 1;
		$GUJARAT_STATE 		     	= 4;

		$application_status         = array($this->ApplyOnlineApprovals->CLAIM_SUBSIDY);
		$ApplyOnlines 				= $this->ApplyOnlines->find();
		$TotalCapacityData 			= $ApplyOnlines->select(['TotalCapacity' => $ApplyOnlines->func()->sum('ApplyOnlines.pv_capacity')])->where(['apply_state' => $GUJARAT_STATE,
						'approval_id' => SPIN_APPROVAL_ID])->toArray();
		if($TotalCapacityData[0]['TotalCapacity']<SPIN_APPROVED_CAPACITY)
		{
			$arrConditions      = [ 'application_status IN '=>$application_status,
									'pcr_code IS NULL',
									'apply_state'=>$GUJARAT_STATE];
			$arrApplications    = $this->ApplyOnlines->find('all',
																	[   'fields'        => ["id"],
																		'conditions'    => $arrConditions,
																		'order'         => 'ApplyOnlines.id ASC',
																	]
															);
			$FetchedRowCount = $arrApplications->count();

			if (!empty($FetchedRowCount))
			{
				foreach($arrApplications as $arrApplication)
				{
					//pr($arrApplication);
					//exit;
					$LastProcessedApplicationID = $arrApplication->id;
					$this->SpinWebserviceApi->pcr_submit($arrApplication->id);
				}
			}
		}
			pr($TotalCapacityData);
			exit;

	}

	/**
	 *
	 * AddUpdateRequest
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to AddUpdateRequest for perticular application
	 *
	 */
	public function addUpdateRequest($id = null)
	{
		$this->autoRander   = false;
		$this->layout       = 'popup';
		$customer_id 		= $this->Session->read("Customers.id");
		$member_id 			= $this->Session->read("Members.id");
		$is_member          = false;
		$UpdateDetails_errors 	= array();
		$originalData 			= array();
		$originalNewData 		= array();
		if(!empty($member_id)){
			$is_member      = true;
		}
		if(empty($customer_id) && empty($member_id)) {
			return $this->redirect('home');
		}
		else
		{
			$application_id 	= decode($id);
			if(empty($application_id))
			{
				return $this->redirect(URL_HTTP.'apply-online-list');
			}
			$encode_id 			= $id;
			$id 				= intval(decode($id));
			$UpdateDetailsEntity= $this->UpdateDetails->viewUpdateDetails($id);
			$updatereq_exist    = $this->UpdateDetails->viewUpdateDetails($id);
			$ApplicationData    = $this->ApplyOnlines->viewApplication($id);
			if(empty($UpdateDetailsEntity))
			{
				$UpdateDetailsEntity 	= $this->UpdateDetails->newEntity();
			}
			if($UpdateDetailsEntity->received_at == 1)
			{
				$updateDetailsLoadLog 	= $this->UpdateDetailsApplicationsLog->find('all',array('conditions'=>array('application_id'=>$UpdateDetailsEntity->application_id,'old_data like'=>'%sanction_load_contract_demand%')))->first();

				$i=0;
				if(!empty($updateDetailsLoadLog))
				{
					$arrLog 					= json_decode($updateDetailsLoadLog->old_data,2);
					$originalData[$i]['text'] 	= 'Sanctioned /Contract Load (in kW)';
					$originalData[$i]['val'] 	= $arrLog['sanction_load_contract_demand'];
					$originalNewData[$i]['text']= 'Sanctioned /Contract Load (in kW)';
					$originalNewData[$i]['val'] = $ApplicationData->sanction_load_contract_demand;
					$i++;
				}
				$updateDetailsLoadLog 	= $this->UpdateDetailsApplicationsLog->find('all',array('conditions'=>array('application_id'=>$UpdateDetailsEntity->application_id,'old_data like'=>'%name_of_consumer_applicant%')))->first();
				if(!empty($updateDetailsLoadLog))
				{
					$arrLog 					= json_decode($updateDetailsLoadLog->old_data,2);
					$originalData[$i]['text'] 	= 'Consumer Name';
					$originalData[$i]['val'] 	= $arrLog['name_of_consumer_applicant'];
					$originalNewData[$i]['text']= 'Consumer Name';
					$originalNewData[$i]['val'] = $ApplicationData->name_of_consumer_applicant;
					$i++;
				}
				$updateDetailsLoadLog 	= $this->UpdateDetailsApplicationsLog->find('all',array('conditions'=>array('application_id'=>$UpdateDetailsEntity->application_id,'old_data like'=>'%subdivision%')))->first();
				if(!empty($updateDetailsLoadLog))
				{
					$arrLog 					= json_decode($updateDetailsLoadLog->old_data,2);
					$originalData[$i]['text'] 	= 'Discom Details';
					$disDetails = $this->ApplyOnlines->getDiscomDetails($arrLog['circle'],$arrLog['division'],$arrLog['subdivision'],$arrLog['area']);
					$originalData[$i]['val'] 	= $disDetails;
					$originalNewData[$i]['text']= 'Discom Details';
					$originalNewData[$i]['val'] = $this->ApplyOnlines->getDiscomDetails($ApplicationData->circle,$ApplicationData->division,$ApplicationData->subdivision,$ApplicationData->area);
					$i++;
				}
			}
			if(isset($this->request->data['save_submit']))
			{
				$this->UpdateDetails->data 						= $this->request->data['UpdateDetails'];
				$this->UpdateDetails->data['aadhar_card']		= (isset($updatereq_exist->aadhar_card) && !empty($updatereq_exist->aadhar_card))?$updatereq_exist->aadhar_card:'';
				$this->UpdateDetails->data['profile_image']		= (isset($updatereq_exist->profile_image) && !empty($updatereq_exist->profile_image))?$updatereq_exist->profile_image:'';
				$this->UpdateDetails->data['electricity_bill']	= (isset($updatereq_exist->electricity_bill) && !empty($updatereq_exist->electricity_bill))?$updatereq_exist->electricity_bill:'';

				$request_data 				= $this->request->data['UpdateDetails'];
				if(empty($updatereq_exist))
				{
					$UpdateDetailsEntity 				= $this->UpdateDetails->newEntity($this->request->data,['validate'=>'add']);
					$UpdateDetailsEntity->created	 	= $this->NOW();
					$UpdateDetailsEntity->created_by 	= $customer_id;
					$saveText 							= 'added';
				}
				else
				{
					$UpdateDetailsEntity 	= $this->UpdateDetails->patchEntity($updatereq_exist,$this->request->data,['validate'=>'add']);
					$saveText 				= 'updated';
				}
				if(!empty($UpdateDetailsEntity->errors()))
				{
					$UpdateDetails_errors 	= $UpdateDetailsEntity->errors();
				}

				if(empty($UpdateDetailsEntity->errors()))
				{
					$UpdateDetailsEntity->aadhar_card 		= $this->UpdateDetails->data['aadhar_card'];
					$UpdateDetailsEntity->profile_image 	= $this->UpdateDetails->data['profile_image'];
					$UpdateDetailsEntity->electricity_bill 	= $this->UpdateDetails->data['electricity_bill'];
					if(isset($request_data['aadhar_card']['tmp_name']) && !empty($request_data['aadhar_card']['tmp_name']))
					{
						$file_name 								= $this->imgfile_upload($request_data['aadhar_card'],'aadhar_',$application_id,'aadhar_card','aadhar_card_update');
						$UpdateDetailsEntity->aadhar_card 		= $file_name;
					}
					if(isset($request_data['profile_image']['tmp_name']) && !empty($request_data['profile_image']['tmp_name']))
					{
						$file_name 								= $this->imgfile_upload($request_data['profile_image'],'profile_',$application_id,'profile_image','profile_image');
						$UpdateDetailsEntity->profile_image 	= $file_name;
					}
					if(isset($request_data['electricity_bill']['tmp_name']) && !empty($request_data['electricity_bill']['tmp_name']))
					{
						$file_name 								= $this->imgfile_upload($request_data['electricity_bill'],'ele_',$application_id,'electricity_bill','electricity_bill');
						$UpdateDetailsEntity->electricity_bill	= $file_name;
					}
					$UpdateDetailsEntity->application_id		= $id;
					$UpdateDetailsEntity->modified				= $this->NOW();
					$UpdateDetailsEntity->modified_by			= $customer_id;
					$this->UpdateDetails->save($UpdateDetailsEntity);
					$message 									= $UpdateDetailsEntity->reason;
					$browser 									= isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:"-";
					$ApplyonlineMessageEntity					= $this->ApplyonlineMessage->newEntity();
					$ApplyonlineMessageEntity->application_id  	= $id;
					$ApplyonlineMessageEntity->message 			= strip_tags($message);
					$ApplyonlineMessageEntity->user_type 		= 0;
					$ApplyonlineMessageEntity->user_id 			= !empty($customer_id) ? $customer_id : 0;
					$ApplyonlineMessageEntity->ip_address 		= $this->IP_ADDRESS;
					$ApplyonlineMessageEntity->created 			= $this->NOW();
					$ApplyonlineMessageEntity->browser_info 	= json_encode($browser);
					$this->ApplyonlineMessage->save($ApplyonlineMessageEntity);
					$this->Flash->success("Request $saveText successfully.");
					return $this->redirect(URL_HTTP.'ApplyOnlines/AddUpdateRequest/'.encode($id));
				}
			}
		}
		$this->set("UpdateDetails",$UpdateDetailsEntity);
		$this->set("UpdateDetailsErrors",$UpdateDetails_errors);
		$this->set("is_member",$is_member);
		$this->set("originalData",$originalData);
		$this->set("originalNewData",$originalNewData);
		$this->set("Couchdb",$this->Couchdb);
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
		$path 			= WWW_ROOT.UPDATEDETAILS_PATH.$application_id.'/';
		if(!file_exists(UPDATEDETAILS_PATH.$application_id)){
			@mkdir(UPDATEDETAILS_PATH.$application_id, 0777,true);
		}
		$updateRequestData 	= $this->UpdateDetails->viewUpdateDetails($application_id);
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
	 * updaterequest
	 *
	 * Behaviour : public
	 *
	 * @param : $request_data   : tab1 form posted data should be passed
	 * @defination : Method is use to check validation of first tab and insert/update subsidy record for first tab.
	 *
	 */
	public function updaterequest()
	{
		//$this->setCustomerArea();
		$customerId 			= $this->Session->read("Customers.id");
		$ses_customer_type 		= $this->Session->read('Customers.customer_type');
		$InstallerID 			= 0;
		$customer_id 			= $this->Session->read("Customers.id");
		$member_id 				= $this->Session->read("Members.id");
		$is_member          	= false;
		if(empty($member_id) && empty($customer_id))
		{
			return $this->redirect(URL_HTTP.'home');
		}
		$area 			= $this->Session->read('Members.area');
		$circle 		= $this->Session->read('Members.circle');
		$division 		= $this->Session->read('Members.division');
		$subdivision 	= $this->Session->read('Members.subdivision');
		$section 		= $this->Session->read('Members.section');
		$member_id 		= $this->Session->read('Members.id');
		$member_type 	= $this->Session->read('Members.member_type');
		$main_branch_id = array();
		if (!empty($member_id) && $member_type == $this->ApplyOnlines->DISCOM)
		{
			$field      = "area";
			$id         = $area;

			if (!empty($section)) {
				$field      = "section";
				$id         = $section;
			} else if (!empty($subdivision)) {
				$field      = "subdivision";
				$id         = $subdivision;
			} else if (!empty($division)) {
				$field      = "division";
				$id         = $division;
			} else if (!empty($circle)) {
				$field      = "circle";
				$id         = $circle;
			}
			$main_branch_id = array("field"=>$field,"id"=>$id);
		}

		if(!empty($member_id)){
			$is_member      	= true;
		}
		if($ses_customer_type == "installer") {
			$is_installer 		= true;
			$customer_details 	= $this->Customers->find('all',array('conditions'=>array('id'=>$customerId)))->first();
			$InstallerID 		= $customer_details['installer_id'];
		}

		$from_date 				= isset($this->request->data['DateFrom'])?$this->request->data['DateFrom']:'';
		$end_date 				= isset($this->request->data['DateTo'])?$this->request->data['DateTo']:'';
		$request_status 		= isset($this->request->data['status'])?$this->request->data['status']:'';
		$request_no 			= isset($this->request->data['request_no'])?$this->request->data['request_no']:'';
		$recevied_status 		= isset($this->request->data['recevied_status'])?$this->request->data['recevied_status']:'';
		$geda_application_no 	= isset($this->request->data['geda_application_no'])?$this->request->data['geda_application_no']:'';
		$installer_name 		= isset($this->request->data['installer_name_multi'])?explode(",",$this->request->data['installer_name_multi']):'';
		$arrRequestList			= array();
		$arrCondition			= array('UpdateDetails.id IS NOT NULL');
		if($is_member == false)
		{
			$arrCondition['ApplyOnlines.installer_id'] 				= $InstallerID;
		}


		//$arrCondition['ApplyOnlines.pcr_submited IS '] 			= NULL;

		$this->SortBy		= "ApplyOnlines.id";
		$this->Direction	= "ASC";
		$this->intLimit		= PAGE_RECORD_LIMIT;
		$this->CurrentPage  = 1;
		$option 			= array();
		$memberApproved 	= '0';
		if($is_member)
		{
			$memberApproved 	= in_array($member_id, $this->ApplyOnlines->ALLOWED_APPROVE_GEDAIDS) ? '1' : '0';
			if(in_array($member_id, $this->ApplyOnlines->ALLOWED_APPROVE_GEDAIDS))
			{
				$option['colName']  = array('id','geda_application_no','installer_name','request_no','request_date','received_at','received_date','received_by','action');
			}
			else
			{
				$option['colName']  = array('id','geda_application_no','installer_name','request_no','request_date','received_at','received_date','action');
			}
		}
		else
		{
			$option['colName']  = array('id','geda_application_no','installer_name','request_no','request_date','received_at','received_date');
		}
		$sortArr 			= array('id'=>'ApplyOnlines.id',
									'geda_application_no'=>'ApplyOnlines.geda_application_no',
									'installer_name' => 'installers.installer_name',
									'request_no'=>'UpdateDetails.id',
									'request_date'=>'UpdateDetails.created',
									'received_at'=>'UpdateDetails.received_at',
									'received_by'=>'Members.name',
									'received_date'=>'UpdateDetails.received_date');
		$this->SetSortingVars('ApplyOnlines',$option,$sortArr);

		$option['dt_selector']			='table-example';
		$option['formId']				='formmain';
		$option['url']					= '';
		$option['recordsperpage']		= PAGE_RECORD_LIMIT;
		//$option['allsortable']			= '-1';
		$option['total_records_data']	= 0;
		$option['bPaginate']			= 'true';
		$option['bLengthChange']		= 'false';
		$option['order_by'] 			= "order : [[3,'ASC']]";
		$JqdTablescr 					= $this->JqdTable->create($option);
		$Joins 							= array([	'table'		=> $this->ApplyOnlines->table,
													'alias' 	=> 'ApplyOnlines',
													'type' 		=> 'LEFT',
													'conditions'=> 'UpdateDetails.application_id=ApplyOnlines.id'],
												[	'table'		=> $this->Installers->table,
													'alias' 	=> 'installers',
													'type' 		=> 'LEFT',
													'conditions'=> 'ApplyOnlines.installer_id=installers.id'],
												[	'table'		=> 'members',
													'alias' 	=> 'Members',
													'type' 		=> 'LEFT',
													'conditions'=> 'UpdateDetails.received_by=Members.id'],
												);
		if ($this->request->is('ajax'))
		{
			if ($geda_application_no != '') {
				$arrCondition['ApplyOnlines.geda_application_no LIKE '] = '%'.$geda_application_no.'%';
			}
			$CountFields	= array('UpdateDetails.id');
			$Fields 		= array('ApplyOnlines.id',
									'ApplyOnlines.geda_application_no',
									'installers.installer_name',
									'UpdateDetails.id',
									'UpdateDetails.created',
									'UpdateDetails.received_at',
									'Members.name',
									'UpdateDetails.received_date');
			if ($request_no != '') {
				$arrCondition['UpdateDetails.id'] = $request_no;
			}
			if ($recevied_status != '') {
				$arrCondition['UpdateDetails.received_at'] = $recevied_status;
			}
			if ($geda_application_no != '') {
				$arrCondition['ApplyOnlines.geda_application_no LIKE '] = '%'.$geda_application_no.'%';
			}
			if ($installer_name != '') {
				//$arrCondition['ApplyOnlines.installer_id LIKE '] = '%'.$installer_name.'%';
				$arrCondition['ApplyOnlines.installer_id in'] 		= $installer_name;
			}
			if(!empty($main_branch_id)) {
				$arrCondition['ApplyOnlines.'.$main_branch_id['field']]= $main_branch_id['id'];
			}
			
			$query_data 	= $this->UpdateDetails->find('all',array(	'fields'		=> $Fields,
																		'conditions' 	=> $arrCondition,
																		'join' 			=> $Joins,
																		'order'			=> array($this->SortBy=>$this->Direction),
																		'page' 			=> $this->CurrentPage,
																		'limit' 		=> $this->intLimit));


			if(!empty($from_date) && !empty($end_date))
			{
				$fields_date  	= "UpdateDetails.created";
				$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
				$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
				$query_data->where([function ($exp, $q) use ($StartTime, $EndTime,$fields_date) {
					return $exp->between($fields_date, $StartTime, $EndTime);
				}]);
			}
			$query_data_count 	= $this->UpdateDetails->find('all',array('fields'		=> $CountFields,
																		'conditions' 	=> $arrCondition,
																		'join' 			=> $Joins,
															));
			if(!empty($from_date) && !empty($end_date))
			{
				$fields_date  	= "UpdateDetails.created";
				$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
				$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
				$query_data_count->where([function ($exp, $q) use ($StartTime, $EndTime,$fields_date) {
					return $exp->between($fields_date, $StartTime, $EndTime);
				}]);
			}

			$total_query_records	= $query_data_count->count();
			$start_page 			= isset($this->request->data['start']) ? $this->request->data['start'] : 1;
			$this->paginate['limit']= PAGE_RECORD_LIMIT;
			$this->paginate['page']	= ($start_page/$this->paginate['limit'])+1;
			if(isset($this->request->data['page_no']) && !empty($this->request->data['page_no']))
			{
				$posible_page 				= $total_query_records/$this->paginate['limit'];
				if($posible_page < $this->request->data['page_no']) {
					$this->paginate['page'] = $posible_page;
				} else {
					$this->paginate['page'] = $this->request->data['page_no'];
				}
			}
			else
			{
				$this->paginate['page'] 	= ($start_page/$this->paginate['limit'])+1;
			}


			$arrRequestList	= $this->paginate($query_data);
			$out 			= array();
			$counter 		= 1;
			$page_mul 		= ($this->CurrentPage-1);
			foreach($arrRequestList->toArray() as $key=>$val)
			{
				$temparr 	= array();
				foreach($option['colName'] as $key) {
					if($key=='id') {
						$temparr[$key]=$counter + ($page_mul * $this->paginate['limit']);
					}
					else if($key=='request_no')
					{
						$temparr[$key]=$val->id;
					}
					else if($key=='received_at') {
						if ($val->received_at == 1)
						{
							$temparr[$key] = "REQUEST APPROVED AT DisCom";
						}
						else if ($val->received_at == 2)
						{
							$temparr[$key] = "REQUEST REJECTED AT DisCom";
						}
						else {
							$temparr[$key] = "REQUEST PENDING AT DisCom";
						}
					}
					else if($key=='received_date') {
						if(!is_null($val->received_date) && !empty($val->received_date)&& trim($val->received_date) != '0000-00-00 00:00:00')
						{
							$temparr[$key]	= date('m-d-Y H:i a',strtotime($val->received_date));
						}
						else
						{
							$temparr[$key]	= '-';
						}
					}
					else if($key=='request_date') {
						if(!is_null($val->created) && !empty($val->created)&& trim($val->created) != '0000-00-00 00:00:00')
						{
							$temparr[$key]	= date('m-d-Y H:i a',strtotime($val->created));
						}
						else
						{
							$temparr[$key]	= '-';
						}
					}
					else if($key=='geda_application_no') {
						if(!is_null($val->ApplyOnlines['geda_application_no']) && !empty($val->ApplyOnlines['geda_application_no']))
						{
							$temparr[$key]	= '<a href="#" data-toggle="modal" data-title="Request to Update DisCom Data" class="UpdateRequest dropdown-item showModel" data-url="'.URL_HTTP.'ApplyOnlines/AddUpdateRequest/'.encode($val->ApplyOnlines['id']).'" data-id="'.encode($val->ApplyOnlines['id']).'">'.$val->ApplyOnlines['geda_application_no'].'
							</a>';
						}
						else
						{
							$temparr[$key]	= '-';
						}
					}
					else if($key=='installer_name') {
						if(!is_null($val->installers['installer_name']) && !empty($val->installers['installer_name']))
						{
							$temparr[$key]	= $val->installers['installer_name'];
						}
						else
						{
							$temparr[$key]	= '-';
						}
					}
					else if($key=='received_by') {
						if(!is_null($val->Members['name']) && !empty($val->Members['name']))
						{
							$temparr[$key]	= $val->Members['name'];
						}
						else
						{
							$temparr[$key]	= '-';
						}
					}
					else if($key=='action') {
						$temparr[$key]	= '<button type="button" class="btn green SubmitRequest approve_Status" data-id="'. encode($val->id) .'">
						<i class="fa fa-check-square-o" aria-hidden="true"></i> Approve</button>';
					}
					else if (isset($val[$key])) {
						$temparr[$key]	= $val[$key];
					} else {
						$temparr[$key]	= "-";
					}
				}
				$counter++;
				$out[] = $temparr;
			}
			header('Content-type: application/json');
			echo json_encode(array(	"draw" 				=> intval($this->request->data['draw']),
									"recordsTotal"    	=> intval($this->request->params['paging']['UpdateDetails']['count']),
									"recordsFiltered" 	=> intval($this->request->params['paging']['UpdateDetails']['count']),
									"data"            	=> $out));
			die;
		}
		$installers_list = array();
		if($is_member == true)
		{

			$installers_list 	= $this->Installers->find("list",[
														'keyField'=>'id',
														'valueField'=>'installer_name'
													]
													)->toArray();
		}
		$REQUEST_STATUS 	= array("0"=>"Pending","1"=>"Approved","2"=>"Rejected");
		$RECEVIED_STATUS 	= array("1"=>"YES","0"=>"NO");
		$installers_list 	= $this->Installers->getInstallerListReport();
		$this->set('arrRequestList',$arrRequestList);
		$this->set('JqdTablescr',$JqdTablescr);
		$this->set('period',$this->period);
		$this->set('limit',$this->intLimit);
		$this->set("CurrentPage",$this->CurrentPage);
		$this->set("SortBy",$this->SortBy);
		$this->set("Direction",$this->Direction);
		$this->set("REQUEST_STATUS",$REQUEST_STATUS);
		$this->set("RECEVIED_STATUS",$RECEVIED_STATUS);
		$this->set("pagetitle",'Update Consumer Request');
		$this->set("page_count",0);
		$this->set("is_member",$is_member);
		$this->set("Installers",$installers_list);
		$this->set("memberApproved",$memberApproved);
	}
	/**
	 *
	 * fetchUpdateRequest
	 *
	 * Behaviour : public
	 *
	 * @defination : Method is use to fetch update request data.
	 *
	 */
	public function fetchUpdateRequest()
	{
		$this->autoRender       = false;
		$response = '';
		$requestid            = intval(decode($this->request->data['requestid']));
		$requestid_fetchData   = $this->UpdateDetails->find("all",['conditions'=>['id'=>$requestid]])->first();
		if(!empty($requestid_fetchData))
		{
			$response   = $requestid_fetchData;
		}
		echo json_encode(array('type'=>'ok','response'=>$response));
		exit;
	}
	/**
	 *
	 * ApproveRequest
	 *
	 * Behaviour : public
	 *
	 * @defination : Method is use to approved or rejected status of request.
	 *
	 */
	public function ApproveRequest()
	{
		$this->autoRender   = false;
		$id                 = (isset($this->request->data['requestid']) ? decode($this->request->data['requestid']) : 0);
		$memberId         	= $this->Session->read("Members.id");
		$member_type 		= $this->Session->read('Members.member_type');
		if(empty($id)) {
			$ErrorMessage   = "Invalid Request. Please validate form details.";
			$success        = 0;
		} else {
			$UpdateDetailsData  		= $this->UpdateDetails->find("all",['conditions'=>['id'=>$id]])->first();
			$UpdateDetails_Data        	= $this->UpdateDetails->get($UpdateDetailsData->id);
			if (!empty($UpdateDetails_Data)) {
				if ($this->request->is('post') || $this->request->is('put')) {

					$UpdateDetailsEntity= $this->UpdateDetails->patchEntity($UpdateDetails_Data,$this->request->data);

					$UpdateDetailsEntity->received_msg 			= strip_tags((isset($this->request->data['received_msg'])?$this->request->data['received_msg']:''));
					$UpdateDetailsEntity->received_by 			= $memberId;
					$UpdateDetailsEntity->received_ip_address 	= $this->request->clientIp();
					$UpdateDetailsEntity->received_date			= $this->NOW();

					//$UpdateDetailsEntity->received_at 	= $this->request->data['received_at'];
					$UpdateDetailsEntity->modified   			= $this->NOW();
					$UpdateDetailsEntity->modified_by 			= $memberId;
					if($this->UpdateDetails->save($UpdateDetailsEntity)) {
						if($UpdateDetailsEntity->received_at==1)
						{
							$OutputGuvnlData= $this->CommonFetchDetails($UpdateDetailsData->application_id,0);
							$viewAppdata 	= $this->ApplyOnlines->viewApplication($UpdateDetailsData->application_id);

							$ResponseData 	= json_decode($OutputGuvnlData,2);
							if($ResponseData['success']==1 || $ResponseData['success']==46)
							{
								$RecordsUpdate 				= $ResponseData['response'];
								if($UpdateDetailsData->is_name_update == 1)
								{
									$arrayOldData 			= array();
									$arrayOldData['name_of_consumer_applicant'] = $viewAppdata->name_of_consumer_applicant;
									$arrayOldData['last_name']	= $viewAppdata->last_name;
									$arrayOldData['third_name'] = $viewAppdata->third_name;

									$LogEntity 					= $this->UpdateDetailsApplicationsLog->newEntity();
									$LogEntity->application_id 	= $UpdateDetailsData->application_id;
									$LogEntity->created 		= $this->NOW();
									$LogEntity->created_by 	    = $memberId;
									$LogEntity->old_data 		= json_encode($arrayOldData);
									$LogEntity->new_data 		= json_encode($RecordsUpdate);
									$this->UpdateDetailsApplicationsLog->save($LogEntity);
									$this->ApplyOnlines->updateAll(array('name_of_consumer_applicant'=>$RecordsUpdate['first_name'],'last_name'=>$RecordsUpdate['middle_name'],'third_name'=>$RecordsUpdate['last_name']),array('id'=>$UpdateDetailsData->application_id));
								}
								if($UpdateDetailsData->is_contract_load == 1)
								{
									$arrayOldData 			= array();
									$arrayOldData['sanction_load_contract_demand'] = $viewAppdata->sanction_load_contract_demand;

									$LogEntity 					= $this->UpdateDetailsApplicationsLog->newEntity();
									$LogEntity->application_id 	= $UpdateDetailsData->application_id;
									$LogEntity->created 		= $this->NOW();
									$LogEntity->created_by 	    = $memberId;
									$LogEntity->old_data 		= json_encode($arrayOldData);
									$LogEntity->new_data 		= json_encode($RecordsUpdate);
									$this->UpdateDetailsApplicationsLog->save($LogEntity);

									$this->ApplyOnlines->updateAll(array('sanction_load_contract_demand'=>$RecordsUpdate['sanction_load']),array('id'=>$UpdateDetailsData->application_id));
								}
								if($UpdateDetailsData->is_division_details == 1)
								{
									$discom_id 				= $viewAppdata->discom;
									if(!empty($RecordsUpdate['sub_division_api']))
									{
										$DiscomMasterHt 	= TableRegistry::get('DiscomMasterHt');
										if(!empty($RecordsUpdate['division_api']))
										{
											$conditionsArr 	= array('division_sort_code'=>$RecordsUpdate['division_api'],
																	'ht_code'=>$RecordsUpdate['sub_division_api'],
																	'discom_code'=>$this->ThirdpartyApiLog->arr_discom_map[$discom_id]);

											$HTSubdivision 	= $DiscomMasterHt->find('all',array('conditions'=>$conditionsArr))->first();

											if(!empty($HTSubdivision))
											{
												$RecordsUpdate['sub_division_api'] = $HTSubdivision->sort_code;
											}
										}
										$subdivision  		= $RecordsUpdate['sub_division_api'];
									}
									$arr_dis_details = array();
									if($discom_id != $this->ApplyOnlines->torent_ahmedabad && $discom_id != $this->ApplyOnlines->torent_surat  && $discom_id != $this->ApplyOnlines->torent_dahej)
									{
										$discom_details 	= $this->DiscomMaster->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['DiscomMaster.short_code'=>$subdivision,'DiscomMaster.type'=>4,'status'=>'1']]);
										$arr_dis_details 	= $discom_details->toarray();
									}
									elseif(!empty($RecordsUpdate['division_api']))
									{
										$discom_details 	= $this->DiscomMaster->find("list",
															['keyField'=>'id',
															'valueField'=>'title',
															'conditions'=>['DiscomMaster.short_code'=>$RecordsUpdate['division_api'],'DiscomMaster.type'=>4,'status'=>'1']
															]);
										$arr_dis_details 	= $discom_details->toarray();

									}
									if (!empty($arr_dis_details)) {
										$RecordsUpdate['subdivision']	= $arr_dis_details;
										$arrUpdate['subdivision']		= key($arr_dis_details);
										$discom_data_details			= $this->DiscomMaster->find("all",['conditions'=>['id'=>key($arr_dis_details),'status'=>'1']])->first();

										if(!empty($RecordsUpdate['division_api']))
										{
											$division_data 				= $this->DiscomMaster->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['DiscomMaster.short_code'=>$RecordsUpdate['division_api'],'DiscomMaster.type'=>3,'area'=>$discom_data_details->area,'circle'=>$discom_data_details->circle,'status'=>'1']])->toArray();

											$RecordsUpdate['division'] 	= $division_data;
											$arrUpdate['division']		= key($division_data);
											$arrDiscom 					= $this->DiscomMaster->GetDiscomHirarchyByID($arrUpdate['division']);
											$arrUpdate['circle'] 		= $arrDiscom->circle;
										}
										$arrayOldData 				= array();
										$arrayOldData['area']		= $viewAppdata->area;
										$arrayOldData['circle'] 	= $viewAppdata->circle;
										$arrayOldData['division'] 	= $viewAppdata->division;
										$arrayOldData['subdivision']= $viewAppdata->subdivision;

										$LogEntity 					= $this->UpdateDetailsApplicationsLog->newEntity();
										$LogEntity->application_id 	= $UpdateDetailsData->application_id;
										$LogEntity->created 		= $this->NOW();
										$LogEntity->created_by 	    = $memberId;
										$LogEntity->old_data 		= json_encode($arrayOldData);
										$LogEntity->new_data 		= json_encode($RecordsUpdate);
										$this->UpdateDetailsApplicationsLog->save($LogEntity);

										$this->ApplyOnlines->updateAll($arrUpdate,array('id'=>$UpdateDetailsData->application_id));
									}
								}
								$message 						= "The request to update the DisCom details has been approved";
								$browser 						= isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:"-";
								$ApplyonlineMessageEntity					= $this->ApplyonlineMessage->newEntity();
								$ApplyonlineMessageEntity->application_id  	= $UpdateDetailsData->application_id;
								$ApplyonlineMessageEntity->message 			= strip_tags($message);
								$ApplyonlineMessageEntity->user_type 		= !empty($member_type)?$member_type:0;
								$ApplyonlineMessageEntity->user_id 			= !empty($memberId)?$memberId:0;
								$ApplyonlineMessageEntity->ip_address 		= $this->IP_ADDRESS;
								$ApplyonlineMessageEntity->created 			= $this->NOW();
								$ApplyonlineMessageEntity->browser_info 	= json_encode($browser);
								$this->ApplyonlineMessage->save($ApplyonlineMessageEntity);

								$CUSTOMER_NAME 				= trim($viewAppdata->customer_name_prefixed." ".$viewAppdata->name_of_consumer_applicant);
								$APPLICATION_NO 			= $viewAppdata->geda_application_no;
								$EmailVars 					= array("APPLICATION_REGISTRATION_NO"=>$viewAppdata->geda_application_no,
																	"CUSTOMER_NAME"=>$CUSTOMER_NAME,
																	"APPLICATION_NO"=>$APPLICATION_NO);

								$to_email 	= $viewAppdata->installer_email;
								//$bcc 		= 'jayshree.tailor@yugtia.com';
								//$to			= "kalpak.yugtia@gmail.com";
								//->bcc($bcc)
									
								$email 		= new Email('default');
								$subject 	= "Update DisCom Data Of - ".$APPLICATION_NO;
								$email->profile('default');
								$email->viewVars($EmailVars);
								$message_send = $email->template('update_discom_approval', 'default')
									->emailFormat('html')
									->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
									->to($to_email)
									->subject(Configure::read('EMAIL_ENV').$subject)
									->send();

							}
						}
						$ErrorMessage   	= "Request Status Updated Sucessfully.";
						$success        	= 1;
					} else {
						$ErrorMessage   	= "Error while sending message.";
						$success        	= 0;
					}
				}
			}else {
				$ErrorMessage   			= "Invalid Request. Please validate form details.";
				$success        			= 0;
			}
		}
		echo json_encode(array('message'=>$ErrorMessage,'success'=>$success));
		exit;
	}
	/**
	 *
	 * CommonFetchDetails
	 *
	 * Behaviour : public
	 *
	 * @defination : Method is use to fetch data from GUVNL server
	 *
	 */
	public function CommonFetchDetails($application_id,$update_data=1)
	{
		$view_data 		= $this->ApplyOnlines->get($application_id);
		$return_data 	= $this->ApplyOnlines->check_consumer_block($view_data->consumer_no,$view_data->discom,$view_data->category);
		if($return_data ===true && !empty($view_data->consumer_no) && !empty($view_data->discom))
		{
			$arr_output 	= $this->ThirdpartyApiLog->searchConsumerApi($view_data->consumer_no,$view_data->discom,$view_data->project_id,$application_id,$view_data->tno);
			$data_subdiv 	= array();
			$flag_disp_data	= 0;
			if($view_data->discom != $this->ApplyOnlines->torent_ahmedabad && $view_data->discom != $this->ApplyOnlines->torent_surat && $view_data->discom != $this->ApplyOnlines->torent_dahej)
			{
				$data_subdiv['success'] 		= $arr_output->P_OUT_STS_CD;
				$data_subdiv['response_msg'] 	= $arr_output->P_OUT_MSG_SERVER;
				if(($arr_output->P_OUT_STS_CD == 1  || $arr_output->P_OUT_STS_CD == -1) || ($update_data==0 && $arr_output->P_OUT_STS_CD == 46))
				{
					if($arr_output->P_OUT_STS_CD == 46)
					{
						$flag_disp_data 		= 1;
					}

					if(isset($arr_output->P_OUT_DATA->OUTPUT_DATA))
					{
						$output_details_obj 	= $arr_output->P_OUT_DATA->OUTPUT_DATA;
						$flag_disp_data 		= 1;
					}
					elseif(isset($arr_output->P_OUT_DATA) && !empty($arr_output->P_OUT_DATA))
					{
						$output_details_obj 	= $arr_output->P_OUT_DATA;
						$flag_disp_data 		= 1;
					}
				}
			}
			else
			{
				if(isset($arr_output->P_OUT_DATA) && !empty($arr_output->P_OUT_DATA))
				{
					$output_details_obj 			= $arr_output->P_OUT_DATA;
					$data_subdiv['success'] 		= $output_details_obj->P_OUT_STS_CD;
					$data_subdiv['response_msg'] 	= $output_details_obj->P_OUT_MSG_SERVER;
					if(strtolower($arr_output->P_OUT_DATA->P_OUT_MSG_CLIENT)=='success')
					{
						$flag_disp_data 			= 1;
					}
				}
				else
				{
					$data_subdiv['success'] 		= '0';
					$data_subdiv['response_msg'] 	= 'Invalid Consumer Number.';
				}
			}
			if($flag_disp_data == 1)
			{
				$output_name 						= preg_replace('!\s+!', ' ', $output_details_obj->NAME);
				$arr_name 							= explode(" ",trim($output_name));
				$data_subdiv['middle_name'] 		= '';
				if(count($arr_name)>2)
				{
					$data_subdiv['first_name'] 		= $arr_name[0];
					$data_subdiv['middle_name'] 	= $arr_name[1];
					$data_subdiv['last_name'] 		= $arr_name[2];
				}
				else
				{
					$data_subdiv['first_name'] 		= $arr_name[0];
					$data_subdiv['last_name'] 		= isset($arr_name[1]) ? $arr_name[1] : '';
				}
				if($view_data->discom != $this->ApplyOnlines->torent_ahmedabad && $view_data->discom != $this->ApplyOnlines->torent_surat && $view_data->discom != $this->ApplyOnlines->torent_dahej)
				{
					if(isset($output_details_obj->ADDRESS))
					{
						$data_subdiv['address1'] 	= $output_details_obj->ADDRESS;
					}
					elseif(isset($output_details_obj->ADDRS))
					{
						$data_subdiv['address1'] 	= $output_details_obj->ADDRS;
					}
				}
				else
				{
					$data_subdiv['address1'] 		= $output_details_obj->ADDRS;
				}
				$data_subdiv['sanction_load'] 		= $output_details_obj->LOAD;
				$data_subdiv['circle_api']			= $output_details_obj->CIRCLE;
				$data_subdiv['division_api']		= $output_details_obj->DIV;
				$data_subdiv['sub_division_api']	= $output_details_obj->SDO;
			}
			else
			{
				if(isset($arr_output->P_OUT_MSG_SERVER) && !empty($arr_output->P_OUT_MSG_SERVER))
				{
					$data_subdiv['success'] 		= '0';
					$data_subdiv['response_msg'] 	= $arr_output->P_OUT_MSG_SERVER;
				}
			}
		}
		else
		{
			$data_subdiv['success']         = '0';
			if(empty($view_data->consumer_no) || empty($view_data->discom))
			{
				$data_subdiv['response_msg']    = 'Consumer No. is empty.';
			}
			else
			{
				$data_subdiv['response_msg']    = 'Consumer No. is currently exist in block list.';
			}
		}
		if($data_subdiv['success']==1 && $update_data==1)
		{
			$this->ApplyOnlines->updateAll(array('name_of_consumer_applicant'=>$data_subdiv['first_name'],'last_name'=>$data_subdiv['middle_name'],'third_name'=>$data_subdiv['last_name'],'address1'=>$data_subdiv['address1'],'sanction_load_contract_demand'=>$data_subdiv['sanction_load']),array('id'=>$application_id));
		}
		return json_encode(array('success'=>$data_subdiv['success'],'response'=>$data_subdiv));
	}
	/**
	 *
	 * addReductionRequest
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to addReductionRequest for perticular application
	 *
	 */
	public function addReductionRequest($id = null,$request_id = null)
	{
		$this->autoRander   	= false;
		$this->layout       	= 'popup';
		$customer_id 			= $this->Session->read("Customers.id");
		$member_id 				= $this->Session->read("Members.id");
		$is_member          	= false;
		$UpdateCapacity_errors 	= array();
		if(!empty($member_id)){
			$is_member      = true;
		}

		if(empty($customer_id) && empty($member_id)) {
			return $this->redirect('home');
		}
		else
		{
			$application_id 	= decode($id);
			if(empty($application_id))
			{
				return $this->redirect(URL_HTTP.'apply-online-list');
			}
			$encode_id 					= $id;
			$id 						= intval(decode($id));

			$UpdateCapacityEntity 		= $this->UpdateCapacity->viewCapacityDetails($id);
			$updatereq_exist    		= $this->UpdateCapacity->viewCapacityDetails($id);

			$ApplicationData    		= $this->ApplyOnlines->find('all',array('conditions'=>array('id'=>$id)))->first();
			$ApplicationOthersData    	= $this->ApplyOnlinesOthers->find('all',array('conditions'=>array('application_id'=>$id)))->first();
			if(empty($UpdateCapacityEntity))
			{
				$UpdateCapacityEntity 	= $this->UpdateCapacity->newEntity();
				$updatereq_exist    	= array();
			}
			elseif($UpdateCapacityEntity->received_at==1)
			{
				$UpdateCapacityEntity 	= $this->UpdateCapacity->newEntity();
				$updatereq_exist    	= array();
			}
			if(!empty($request_id))
			{
				$request_id 			= intval(decode($request_id));

				$UpdateCapacityEntity 	= $this->UpdateCapacity->get($request_id);
				$updatereq_exist    	= $this->UpdateCapacity->get($request_id);
			}
			if(empty($ApplicationData->original_capacity))
			{
				$registered_capacity = $ApplicationData->pv_capacity;
			}
			else
			{
				$registered_capacity = $ApplicationData->original_capacity;
			}
			$ApplicationData->pv_dc_capacity= $ApplicationOthersData->pv_dc_capacity;
			if(isset($this->request->data['save_submit']))
			{
				$this->UpdateCapacity->data 						= $this->request->data['UpdateCapacity'];
				$this->UpdateCapacity->data['consent_letter']		= (isset($updatereq_exist->consent_letter) && !empty($updatereq_exist->consent_letter))?$updatereq_exist->consent_letter:'';

				$this->UpdateCapacity->data['registered_capacity']	= $ApplicationData->pv_capacity;
				$this->UpdateCapacity->data['reg_capacity_dc']		= $ApplicationOthersData->pv_dc_capacity;

				$request_data 							= $this->request->data['UpdateCapacity'];
				if(empty($updatereq_exist))
				{
					$UpdateCapacityEntity 				= $this->UpdateCapacity->newEntity($this->request->data,['validate'=>'add']);
					$UpdateCapacityEntity->created	 	= $this->NOW();
					$UpdateCapacityEntity->created_by 	= $customer_id;
					$saveText 							= 'added';
				}
				else
				{
					$UpdateCapacityEntity 	= $this->UpdateCapacity->patchEntity($updatereq_exist,$this->request->data,['validate'=>'add']);
					$saveText 				= 'updated';
				}
				if(!empty($UpdateCapacityEntity->errors()))
				{
					$UpdateCapacity_errors 	= $UpdateCapacityEntity->errors();
				}
				if(empty($UpdateCapacityEntity->errors()))
				{
					$UpdateCapacityEntity->consent_letter 		= $this->UpdateCapacity->data['consent_letter'];
					if(isset($request_data['consent_letter']['tmp_name']) && !empty($request_data['consent_letter']['tmp_name']))
					{
						$file_name 								= $this->imgfile_upload($request_data['consent_letter'],'consent_',$application_id,'consent_letter','consent_letter');
						$UpdateCapacityEntity->consent_letter 	= $file_name;
					}

					$UpdateCapacityEntity->application_id		= $id;
					$UpdateCapacityEntity->modified				= $this->NOW();
					$UpdateCapacityEntity->modified_by			= $customer_id;

					$this->UpdateCapacity->save($UpdateCapacityEntity);
					$message 									= $UpdateCapacityEntity->reason;
					$browser 									= isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:"-";
					$ApplyonlineMessageEntity					= $this->ApplyonlineMessage->newEntity();
					$ApplyonlineMessageEntity->application_id 	= $id;
					$ApplyonlineMessageEntity->message 			= strip_tags($message);
					$ApplyonlineMessageEntity->user_type 		= 0;
					$ApplyonlineMessageEntity->user_id 			= !empty($customer_id) ? $customer_id : 0;
					$ApplyonlineMessageEntity->ip_address 		= $this->IP_ADDRESS;
					$ApplyonlineMessageEntity->created 			= $this->NOW();
					$ApplyonlineMessageEntity->browser_info 	= json_encode($browser);
					$this->ApplyonlineMessage->save($ApplyonlineMessageEntity);
					$this->Flash->success("Request $saveText successfully.");
					return $this->redirect(URL_HTTP.'ApplyOnlines/addReductionRequest/'.encode($id));
				}
			}
		}
		$this->set("UpdateCapacity",$UpdateCapacityEntity);
		$this->set("UpdateCapacityErrors",$UpdateCapacity_errors);
		$this->set("is_member",$is_member);
		$this->set("registred_capacity",$registered_capacity);
		$this->set("applicationDetails",$ApplicationData);
		$this->set("Couchdb",$this->Couchdb);
	}
	/**
	 *
	 * capacityrequest
	 *
	 * Behaviour : public
	 *
	 * @param :
	 * @defination : Method is use to list capacity
	 *
	 */
	public function capacityrequest()
	{
		//$this->setCustomerArea();
		$customerId 			= $this->Session->read("Customers.id");
		$ses_customer_type 		= $this->Session->read('Customers.customer_type');
		$InstallerID 			= 0;
		$customer_id 			= $this->Session->read("Customers.id");
		$member_id 				= $this->Session->read("Members.id");
		$is_member          	= false;
		if(empty($member_id) && empty($customer_id))
		{
			return $this->redirect(URL_HTTP.'home');
		}
		$area 			= $this->Session->read('Members.area');
		$circle 		= $this->Session->read('Members.circle');
		$division 		= $this->Session->read('Members.division');
		$subdivision 	= $this->Session->read('Members.subdivision');
		$section 		= $this->Session->read('Members.section');
		$member_id 		= $this->Session->read('Members.id');
		$member_type 	= $this->Session->read('Members.member_type');
		$main_branch_id = array();
		if (!empty($member_id) && $member_type == $this->ApplyOnlines->DISCOM)
		{
			$field      = "area";
			$id         = $area;

			if (!empty($section)) {
				$field      = "section";
				$id         = $section;
			} else if (!empty($subdivision)) {
				$field      = "subdivision";
				$id         = $subdivision;
			} else if (!empty($division)) {
				$field      = "division";
				$id         = $division;
			} else if (!empty($circle)) {
				$field      = "circle";
				$id         = $circle;
			}
			$main_branch_id = array("field"=>$field,"id"=>$id);
		}
		if(!empty($member_id)){
			$is_member      	= true;
		}
		if($ses_customer_type == "installer") {
			$is_installer 		= true;
			$customer_details 	= $this->Customers->find('all',array('conditions'=>array('id'=>$customerId)))->first();
			$InstallerID 		= $customer_details['installer_id'];
		}

		$from_date 				= isset($this->request->data['DateFrom'])?$this->request->data['DateFrom']:'';
		$end_date 				= isset($this->request->data['DateTo'])?$this->request->data['DateTo']:'';
		$request_status 		= isset($this->request->data['status'])?$this->request->data['status']:'';
		$request_no 			= isset($this->request->data['request_no'])?$this->request->data['request_no']:'';
		$recevied_status 		= isset($this->request->data['recevied_status'])?$this->request->data['recevied_status']:'';
		$geda_application_no 	= isset($this->request->data['geda_application_no'])?$this->request->data['geda_application_no']:'';
		$installer_name 		= isset($this->request->data['installer_name_multi'])?explode(",",$this->request->data['installer_name_multi']):'';
		$arrRequestList			= array();
		$arrCondition			= array('UpdateCapacity.id IS NOT NULL');
		if($is_member == false)
		{
			$arrCondition['ApplyOnlines.installer_id'] 				= $InstallerID;
		}


		//$arrCondition['ApplyOnlines.pcr_submited IS '] 			= NULL;

		$this->SortBy		= "ApplyOnlines.id";
		$this->Direction	= "ASC";
		$this->intLimit		= PAGE_RECORD_LIMIT;
		$this->CurrentPage  = 1;
		$option 			= array();
		if($is_member)
		{
			$option['colName']  = array('id','geda_application_no','installer_name','pv_capacity','pv_capacity_dc','request_no','request_date','received_at','received_date','action');
		}
		else
		{
			$option['colName']  = array('id','geda_application_no','installer_name','pv_capacity','pv_capacity_dc','request_no','request_date','received_at','received_date');
		}
		$sortArr 			= array('id'					=> 'ApplyOnlines.id',
									'geda_application_no'	=> 'ApplyOnlines.geda_application_no',
									'installer_name' 		=> 'installers.installer_name',
									'pv_capacity' 			=> 'UpdateCapacity.pv_capacity',
									'pv_capacity_dc' 		=> 'UpdateCapacity.pv_capacity_dc',
									'request_no'			=> 'UpdateCapacity.id',
									'request_date'			=> 'UpdateCapacity.created',
									'received_at'			=> 'UpdateCapacity.received_at',
									'received_date'			=> 'UpdateCapacity.received_date');
		$this->SetSortingVars('ApplyOnlines',$option,$sortArr);

		$option['dt_selector']			='table-example';
		$option['formId']				='formmain';
		$option['url']					= '';
		$option['recordsperpage']		= PAGE_RECORD_LIMIT;
		//$option['allsortable']			= '-1';
		$option['total_records_data']	= 0;
		$option['bPaginate']			= 'true';
		$option['bLengthChange']		= 'false';
		$option['order_by'] 			= "order : [[4,'ASC']]";
		$JqdTablescr 					= $this->JqdTable->create($option);
		$Joins 							= array([	'table'		=> $this->ApplyOnlines->table,
													'alias' 	=> 'ApplyOnlines',
													'type' 		=> 'LEFT',
													'conditions'=> 'UpdateCapacity.application_id=ApplyOnlines.id'],
												[	'table'		=> $this->Installers->table,
													'alias' 	=> 'installers',
													'type' 		=> 'LEFT',
													'conditions'=> 'ApplyOnlines.installer_id=installers.id'],
												);
		if ($this->request->is('ajax'))
		{
			if ($geda_application_no != '') {
				$arrCondition['ApplyOnlines.geda_application_no LIKE '] = '%'.$geda_application_no.'%';
			}
			$CountFields	= array('UpdateCapacity.id');
			$Fields 		= array('ApplyOnlines.id',
									'ApplyOnlines.geda_application_no',
									'installers.installer_name',
									'UpdateCapacity.pv_capacity',
									'UpdateCapacity.pv_capacity_dc',
									'UpdateCapacity.id',
									'UpdateCapacity.created',
									'UpdateCapacity.received_at',
									'UpdateCapacity.received_date');
			if ($request_no != '') {
				$arrCondition['UpdateCapacity.id'] = $request_no;
			}
			if ($recevied_status != '') {
				$arrCondition['UpdateCapacity.received_at'] = $recevied_status;
			}
			if ($geda_application_no != '') {
				$arrCondition['ApplyOnlines.geda_application_no LIKE '] = '%'.$geda_application_no.'%';
			}
			if ($installer_name != '') {
				//$arrCondition['ApplyOnlines.installer_id LIKE '] = '%'.$installer_name.'%';
				$arrCondition['ApplyOnlines.installer_id in'] 		= $installer_name;
			}
			if(!empty($main_branch_id)) {
				$arrCondition['ApplyOnlines.'.$main_branch_id['field']]= $main_branch_id['id'];
			}
			$query_data 	= $this->UpdateCapacity->find('all',array(	'fields'		=> $Fields,
																		'conditions' 	=> $arrCondition,
																		'join' 			=> $Joins,
																		'order'			=> array($this->SortBy=>$this->Direction),
																		'page' 			=> $this->CurrentPage,
																		'limit' 		=> $this->intLimit));


			if(!empty($from_date) && !empty($end_date))
			{
				$fields_date  	= "UpdateCapacity.created";
				$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
				$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
				$query_data->where([function ($exp, $q) use ($StartTime, $EndTime,$fields_date) {
					return $exp->between($fields_date, $StartTime, $EndTime);
				}]);
			}
			$query_data_count 	= $this->UpdateCapacity->find('all',array('fields'		=> $CountFields,
																		'conditions' 	=> $arrCondition,
																		'join' 			=> $Joins,
															));
			if(!empty($from_date) && !empty($end_date))
			{
				$fields_date  	= "UpdateCapacity.created";
				$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
				$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
				$query_data_count->where([function ($exp, $q) use ($StartTime, $EndTime,$fields_date) {
					return $exp->between($fields_date, $StartTime, $EndTime);
				}]);
			}

			$total_query_records	= $query_data_count->count();
			$start_page 			= isset($this->request->data['start']) ? $this->request->data['start'] : 1;
			$this->paginate['limit']= PAGE_RECORD_LIMIT;
			$this->paginate['page']	= ($start_page/$this->paginate['limit'])+1;
			if(isset($this->request->data['page_no']) && !empty($this->request->data['page_no']))
			{
				$posible_page 				= $total_query_records/$this->paginate['limit'];
				if($posible_page < $this->request->data['page_no']) {
					$this->paginate['page'] = $posible_page;
				} else {
					$this->paginate['page'] = $this->request->data['page_no'];
				}
			}
			else
			{
				$this->paginate['page'] 	= ($start_page/$this->paginate['limit'])+1;
			}


			$arrRequestList	= $this->paginate($query_data);
			$out 			= array();
			$counter 		= 1;
			$page_mul 		= ($this->CurrentPage-1);
			foreach($arrRequestList->toArray() as $key=>$val)
			{
				$temparr 	= array();
				foreach($option['colName'] as $key) {
					if($key=='id') {
						$temparr[$key]=$counter + ($page_mul * $this->paginate['limit']);
					}
					else if($key=='request_no')
					{
						$temparr[$key]=$val->id;
					}
					else if($key=='received_at') {
						if ($val->received_at == 1)
						{
							$temparr[$key] = "REQUEST APPROVED AT DisCom";
						}
						else if ($val->received_at == 2)
						{
							$temparr[$key] = "REQUEST REJECTED AT DisCom";
						}
						else {
							$temparr[$key] = "REQUEST PENDING AT DisCom";
						}
					}
					else if($key=='received_date') {
						if(!is_null($val->received_date) && !empty($val->received_date) && trim($val->received_date) != '0000-00-00 00:00:00')
						{
							$temparr[$key]	= date('m-d-Y H:i a',strtotime($val->received_date));
						}
						else
						{
							$temparr[$key]	= '-';
						}
					}
					else if($key=='request_date') {
						if(!is_null($val->created) && !empty($val->created) && trim($val->created) != '0000-00-00 00:00:00')
						{
							$temparr[$key]	= date('m-d-Y H:i a',strtotime($val->created));
						}
						else
						{
							$temparr[$key]	= '-';
						}
					}
					else if($key=='geda_application_no') {
						if(!is_null($val->ApplyOnlines['geda_application_no']) && !empty($val->ApplyOnlines['geda_application_no']))
						{
							$temparr[$key]	= '<a href="#" data-toggle="modal" data-title="Request to Reduce the Registered Capacity" class="UpdateRequest dropdown-item showModel" data-url="'.URL_HTTP.'ApplyOnlines/addReductionRequest/'.encode($val->ApplyOnlines['id']).'/'.encode($val->id).'">'.$val->ApplyOnlines['geda_application_no'].'
							</a>';
						}
						else
						{
							$temparr[$key]	= '-';
						}
					}
					else if($key=='installer_name') {
						if(!is_null($val->installers['installer_name']) && !empty($val->installers['installer_name']))
						{
							$temparr[$key]	= $val->installers['installer_name'];
						}
						else
						{
							$temparr[$key]	= '-';
						}
					}
					else if($key=='action') {
						if ($val->received_at != 1)
						{
							$temparr[$key]	= '<button type="button" class="btn green SubmitRequest approve_Status" data-id="'. encode($val->id) .'">
						<i class="fa fa-check-square-o" aria-hidden="true"></i> Approve</button>';
						}
						else
						{
							$temparr[$key]	= '-';
						}
					}
					else if (isset($val[$key])) {
						$temparr[$key]	= $val[$key];
					} else {
						$temparr[$key]	= "-";
					}
				}
				$counter++;
				$out[] = $temparr;
			}
			header('Content-type: application/json');
			echo json_encode(array(	"draw" 				=> intval($this->request->data['draw']),
									"recordsTotal"    	=> intval($this->request->params['paging']['UpdateCapacity']['count']),
									"recordsFiltered" 	=> intval($this->request->params['paging']['UpdateCapacity']['count']),
									"data"            	=> $out));
			die;
		}
		$installers_list = array();
		if($is_member == true)
		{

			$installers_list 	= $this->Installers->find("list",[
														'keyField'=>'id',
														'valueField'=>'installer_name'
													]
													)->toArray();
		}
		$REQUEST_STATUS 	= array("0"=>"Pending","1"=>"Approved","2"=>"Rejected");
		$RECEVIED_STATUS 	= array("1"=>"YES","0"=>"NO");
		$installers_list 	= $this->Installers->getInstallerListReport();
		$this->set('arrRequestList',$arrRequestList);
		$this->set('JqdTablescr',$JqdTablescr);
		$this->set('period',$this->period);
		$this->set('limit',$this->intLimit);
		$this->set("CurrentPage",$this->CurrentPage);
		$this->set("SortBy",$this->SortBy);
		$this->set("Direction",$this->Direction);
		$this->set("REQUEST_STATUS",$REQUEST_STATUS);
		$this->set("RECEVIED_STATUS",$RECEVIED_STATUS);
		$this->set("pagetitle",'Update Consumer Request');
		$this->set("page_count",0);
		$this->set("is_member",$is_member);
		$this->set("Installers",$installers_list);
	}
	/**
	 *
	 * fetchCapacityRequest
	 *
	 * Behaviour : public
	 *
	 * @defination : Method is use to fetch capacity data from request table.
	 *
	 */
	public function fetchCapacityRequest()
	{
		$this->autoRender       = false;
		$response = '';
		$requestid            = intval(decode($this->request->data['requestid']));
		$requestid_fetchData   = $this->UpdateCapacity->find("all",['conditions'=>['id'=>$requestid]])->first();
		if(!empty($requestid_fetchData))
		{
			$response   = $requestid_fetchData;
		}
		echo json_encode(array('type'=>'ok','response'=>$response));
		exit;
	}
	/**
	 *
	 * ApproveCapacityRequest
	 *
	 * Behaviour : public
	 *
	 * @defination : Method is use to approved or rejected status of capacity request.
	 *
	 */
	public function ApproveCapacityRequest()
	{
		$this->autoRender   = false;
		$id                 = (isset($this->request->data['requestid']) ? decode($this->request->data['requestid']) : 0);
		$memberId         	= $this->Session->read("Members.id");
		$member_type 		= $this->Session->read('Members.member_type');
		if(empty($id)) {
			$ErrorMessage   = "Invalid Request. Please validate form details.";
			$success        = 0;
		} else {
			$UpdateCapacityData 	= $this->UpdateCapacity->find("all",['conditions'=>['id'=>$id]])->first();
			$UpdateCapacity_Data	= $this->UpdateCapacity->get($UpdateCapacityData->id);
			if (!empty($UpdateCapacity_Data)) {
				if ($this->request->is('post') || $this->request->is('put')) {
					if($UpdateCapacityData->received_at != 1)
					{
						$UpdateCapacityEntity= $this->UpdateCapacity->patchEntity($UpdateCapacity_Data,$this->request->data);

						$UpdateCapacityEntity->received_msg 		= strip_tags((isset($this->request->data['received_msg'])?$this->request->data['received_msg']:''));

						$UpdateCapacityEntity->received_by 			= $memberId;
						$UpdateCapacityEntity->received_ip_address 	= $this->request->clientIp();
						$UpdateCapacityEntity->received_date 		= $this->NOW();

						//$UpdateDetailsEntity->received_at 	= $this->request->data['received_at'];
						$UpdateCapacityEntity->modified   			= $this->NOW();
						$UpdateCapacityEntity->modified_by 			= $memberId;

						if($this->UpdateCapacity->save($UpdateCapacityEntity)) {
							if($UpdateCapacityEntity->received_at==1)
							{
								$viewAppdata 	= $this->ApplyOnlines->find('all',array('conditions'=>array('id'=>$UpdateCapacityData->application_id)))->first();
								$appOtherAppdata= $this->ApplyOnlinesOthers->find('all',array('conditions'=>array('application_id'=>$UpdateCapacityData->application_id)))->first();
								if(empty($viewAppdata->original_capacity))
								{
									$this->ApplyOnlines->updateAll(array('original_capacity'=>$viewAppdata->pv_capacity),array('id'=>$viewAppdata->id));
								}
								$this->executionSubsidyCapacity($UpdateCapacityData->application_id,$UpdateCapacityData->pv_capacity,$UpdateCapacityData->id);

								$arrayOldData['pv_capacity']	= $viewAppdata->pv_capacity;
								$arrayOldData['pv_dc_capacity']	= $appOtherAppdata->pv_dc_capacity;
								$new_dc_capacity 				= (!empty($UpdateCapacityData->pv_capacity_dc)) ? $UpdateCapacityData->pv_capacity_dc : $appOtherAppdata->pv_dc_capacity;
								$LogEntity 					= $this->UpdateCapacityApplicationsLog->newEntity();
								$LogEntity->application_id 	= $UpdateCapacityData->application_id;
								$LogEntity->request_id 		= $UpdateCapacityData->id;
								$LogEntity->created 		= $this->NOW();
								$LogEntity->created_by 	    = $memberId;
								$LogEntity->old_data 		= json_encode($arrayOldData);
								$LogEntity->new_data 		= json_encode(array('pv_capacity'=>$UpdateCapacityData->pv_capacity,'pv_dc_capacity'=>$new_dc_capacity));
								$this->UpdateCapacityApplicationsLog->save($LogEntity);
								$this->ApplyOnlines->updateAll(array('pv_capacity'=>$UpdateCapacityData->pv_capacity),array('id'=>$UpdateCapacityData->application_id));

								if(!empty($UpdateCapacityData->pv_capacity_dc)) {
									$this->ApplyOnlinesOthers->updateAll(array('pv_dc_capacity'=>$UpdateCapacityData->pv_capacity_dc),array('application_id'=>$UpdateCapacityData->application_id));
								}
								
								/** APPLICATION SOLAR TYPE FOR VENDOR API */
								$SOLAR_TYPE_FLAG = $this->SolarTypeLog->SOLAR_TYPE_REDUCTION;
								$this->SolarTypeLog->SaveOrUpdateSolarType($UpdateCapacityData->application_id,$SOLAR_TYPE_FLAG);
								$this->SendRegistrationFailure->SaveVendorSendRequest($UpdateCapacityData->application_id);
								/** APPLICATION SOLAR TYPE FOR VENDOR API */

								$message 					= "The request to reduce the Registered Capacity has been approved";
								$browser 					= isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:"-";
								$ApplyonlineMessageEntity					= $this->ApplyonlineMessage->newEntity();
								$ApplyonlineMessageEntity->application_id  	= $UpdateCapacityData->application_id;
								$ApplyonlineMessageEntity->message 			= strip_tags($message);
								$ApplyonlineMessageEntity->user_type 		= !empty($member_type)?$member_type:0;
								$ApplyonlineMessageEntity->user_id 			= !empty($memberId)?$memberId:0;
								$ApplyonlineMessageEntity->ip_address 		= $this->IP_ADDRESS;
								$ApplyonlineMessageEntity->created 			= $this->NOW();
								$ApplyonlineMessageEntity->browser_info 	= json_encode($browser);
								$this->ApplyonlineMessage->save($ApplyonlineMessageEntity);


								$CUSTOMER_NAME 				= trim($viewAppdata->customer_name_prefixed." ".$viewAppdata->name_of_consumer_applicant);


								$PV_CAPACITY 				= floatval($viewAppdata->pv_capacity);
								$APPLICATION_NO 			= $viewAppdata->geda_application_no;
								$EmailVars 					= array("APPLICATION_REGISTRATION_NO"=>$viewAppdata->geda_application_no,
																	"CUSTOMER_NAME"=>$CUSTOMER_NAME,
																	"APPLICATION_NO"=>$APPLICATION_NO,
																	"NEW_CAPACITY"=>$UpdateCapacityData->pv_capacity,
																	"OLD_CAPACITY"=>$PV_CAPACITY);

								$to_email 	= $viewAppdata->installer_email;
								//$bcc 		= 'jayshree.tailor@yugtia.com';
								//$to			= "kalpak.yugtia@gmail.com";
								//->bcc($bcc)
									
								$email 		= new Email('default');
								$subject 	= "Reduction in Capacity Of - ".$APPLICATION_NO;
								$email->profile('default');
								$email->viewVars($EmailVars);
								$message_send = $email->template('capacity_approval', 'default')
									->emailFormat('html')
									->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
									->to($to_email)
									->subject(Configure::read('EMAIL_ENV').$subject)
									->send();
							}
							$ErrorMessage   	= "Request Status Updated Sucessfully.";
							$success        	= 1;
						} else {
							$ErrorMessage   	= "Error while sending message.";
							$success        	= 0;
						}
					}
					else {
						$ErrorMessage   			= "Request Already approved.";
						$success        			= 0;
					}
				}
			}else {
				$ErrorMessage   			= "Invalid Request. Please validate form details.";
				$success        			= 0;
			}
		}
		echo json_encode(array('message'=>$ErrorMessage,'success'=>$success));
		exit;
	}
	public function executionSubsidyCapacity($applicationId,$setCapacity,$reuqestId)
	{
		$applicationStage 	= $this->ApplyOnlineApprovals->Approvalstage($applicationId);

		if(in_array($this->ApplyOnlineApprovals->CLAIM_SUBSIDY, $applicationStage))
		{

			$SubsidyData 	= $this->Subsidy->find('all',array('conditions' => array('application_id' => $applicationId)))->first();
			$modules_data  	= isset($SubsidyData->modules_data) ? unserialize($SubsidyData->modules_data) : '';
			$inverter_data 	= isset($SubsidyData->inverter_data) ? unserialize($SubsidyData->inverter_data) : '';
		}
		else if(in_array($this->ApplyOnlineApprovals->WORK_EXECUTED, $applicationStage))
		{
			$viewAppData 	= $this->ApplyOnlines->viewApplication($applicationId);
			$inscommData  	= $this->Installation->find('all',array('conditions'=>array('Installation.project_id'=>$viewAppData->project_id)))->first();
			$modules_data  	= isset($inscommData->modules_data) ? unserialize($inscommData->modules_data) : '';
			$inverter_data 	= isset($inscommData->inverter_data) ? unserialize($inscommData->inverter_data) : '';
		}
		$total_commulative= 0;
		for($i=1;$i<=3;$i++)
		{
			$row            = $i-1;
			$m_capacity     = '';
			$m_make         = '';
			$m_modules      = '';
			$m_type_modules = '';
			$m_type_other   = '';
			if(isset($modules_data[$row]))
			{
				$m_capacity         = $modules_data[$row]['m_capacity'];
				$m_make             = $modules_data[$row]['m_make'];
				$m_modules          = $modules_data[$row]['m_modules'];
				$m_type_modules     = $modules_data[$row]['m_type_modules'];
				$m_type_other       = $modules_data[$row]['m_type_other'];
				$total_commulative  = $total_commulative + ($modules_data[$row]['m_capacity'] * $modules_data[$row]['m_modules']);
			}
		}
		if ($total_commulative > 0)
		{
			$total_commulative  = round(($total_commulative/1000),3);
		}
		$total_commulative_i  = 0;
		for($i=1;$i<=3;$i++)
		{
			$row                  = $i-1;
			$i_capacity           = '';
			$i_make               = '';
			$i_make_other         = '';
			$i_modules            = '';
			$i_type_modules       = '';
			$i_type_other         = '';
			$i_phase              = '';
			if(isset($inverter_data[$row]))
			{
				$i_capacity         = $inverter_data[$row]['i_capacity'];
				$i_make             = $inverter_data[$row]['i_make'];
				$i_make_other       = $inverter_data[$row]['i_make_other'];
				$i_modules          = $inverter_data[$row]['i_modules'];
				$i_type_modules     = $inverter_data[$row]['i_type_modules'];
				$i_type_other       = $inverter_data[$row]['i_type_other'];
				if(isset($inverter_data[$row]['i_phase']))
				{
				  $i_phase       = $inverter_data[$row]['i_phase'];
				}
				$total_commulative_i= $total_commulative_i + ($inverter_data[$row]['i_capacity']*$inverter_data[$row]['i_modules']);
			}
		}
		if ($total_commulative_i > 0)
		{
			$total_commulative_i  = round(($total_commulative_i),3);
		}
		$min_cap = $setCapacity;
		if($total_commulative!=0 && $total_commulative_i!=0)
		{
			$min_cap = min($total_commulative,$total_commulative_i,$setCapacity);
		}

		$this->CreateMyProjectSetCapacity($applicationId,true,$min_cap,$reuqestId);
	}
	/**
	*
	* CreateMyProjectSetCapacity
	*
	* Behaviour : private
	*
	* @param : application_id, CreateMyProject
	*
	* @defination : Method is use to update capacity in project and apply online table.
	*
	*/
	private function CreateMyProjectSetCapacity($application_id=0,$CreateMyProject=true,$set_capacity='',$reuqestId='')
	{
		$app_details   		= $this->ApplyOnlines->find('all',array('conditions'=>array('id'=>$application_id)))->first();
		$subsidy_details   	= $this->Subsidy->find('all',array('conditions'=>array('application_id'=>$application_id)))->first();
		$project_data       = $this->Projects->find('all',array('conditions'=>array('id'=>$app_details->project_id)))->first();
		$memberId         	= $this->Session->read("Members.id");
		if(!empty($project_data))
		{
			if(strtolower($project_data->state)!='gujarat')
			{
				$this->Projects->updateAll([
											'state' 			=> 'Gujarat',
											'state_short_name' 	=> 'GJ'
											],
											['id' 	=> $app_details->project_id]);
				$project_data       = $this->Projects->find('all',array('conditions'=>array('id'=>$app_details->project_id)))->first();
			}
			$latitude 		= $project_data->latitude;
			$longitude 		= $project_data->longitude;
			$pv_app_capacity= $app_details->pv_capacity;


			if($set_capacity != '')
			{
				$pv_app_capacity 	= $set_capacity;
			}

			$LogEntity 				= $this->UpdateCapacityProjectsLog->newEntity();
			$LogEntity->project_id 	= $project_data->id;
			$LogEntity->request_id 	= $reuqestId;
			$LogEntity->created 	= $this->NOW();
			$LogEntity->created_by 	= $memberId;
			$LogEntity->old_data 	= json_encode(array('recommended_capacity'=>$project_data->recommended_capacity));
			$LogEntity->new_data 	= json_encode(array('recommended_capacity'=>$pv_app_capacity));
			$this->UpdateCapacityProjectsLog->save($LogEntity);


			$arr_project_data['proj_name']              = $project_data->name;
			$arr_project_data['latitude']               = $latitude;
			$arr_project_data['longitude']              = $longitude;
			$arr_project_data['customer_type']          = $project_data->customer_type;
			$arr_project_data['project_type']           = $project_data->customer_type;
			$arr_project_data['area']                   = $project_data->area;
			$arr_project_data['area_type']              = $project_data->area_type;
			$arr_project_data['bill']                   = $project_data->avg_monthly_bill;
			$arr_project_data['avg_monthly_bill']       = $project_data->avg_monthly_bill;
			$arr_project_data['backup_type']            = $project_data->backup_type;
			$arr_project_data['usage_hours']            = $project_data->usage_hours;
			$arr_project_data['energy_con']             = $project_data->estimated_kwh_year;
			$arr_project_data['recommended_capacity']   = $pv_app_capacity;
			$arr_project_data['address']                = $project_data->address;
			$arr_project_data['city']                   = $project_data->city;
			$arr_project_data['state']                  = $project_data->state;
			$arr_project_data['state_short_name']       = $project_data->state_short_name;
			$arr_project_data['country']                = $project_data->country;
			$arr_project_data['postal_code']            = $project_data->pincode;
			$arr_project_data['Projects']['id']         = $project_data->id;
			$result                                     = $this->Projects->getprojectestimationV2($arr_project_data,$app_details->customer_id,$CreateMyProject);
		}
		return $result;
	}
	/**
	*
	* RemoveApplicationConsumerNo
	*
	* Behaviour : private
	*
	* @param : consumer numbers
	*
	* @defination : Method is use to delete application from apply onlines table.
	*
	*/
	public function RemoveApplicationConsumerNo()
	{
		$this->layout 			= false;
		//$arrConsumers 			= array('32402051558','14509095821','500048038','00321309073','61682032957');
		//$arrConsumers 			= array('72905062401');
		//$arrConsumers 			= array('881793','1311980','10851055184');
		//$arrConsumers 			= array('13001003855','08412011708');
		//$arrConsumers 			= array('14908007659','15203006709','31801600155','89669000149','20943114713');
		//$arrConsumers 			= array('20526','3114647','3313813');
		//$arrConsumers 			= array('30108');
		$arrConsumers 			= array('16304011989');
		$application_data 		= $this->ApplyOnlines->find('all',array('conditions'=>array('consumer_no in'=>$arrConsumers)))->toArray();
		if(!empty($application_data))
		{
			foreach($application_data as $appData)
			{
				$browser 					   = isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:"-";
				$removeentity                  = $this->ApplicationDeleteLog->newEntity();
				$removeentity->application_id  = $appData->id;
				$removeentity->customer_id     = 0;
				$removeentity->ip_address      = $this->IP_ADDRESS;
				$removeentity->browser_info	   = json_encode($browser);
				$removeentity->application_data= json_encode($appData);
				$removeentity->created 		   = $this->NOW();

				$this->ApplicationDeleteLog->save($removeentity);
				$entity = $this->ApplyOnlines->get($appData->id);
				$this->ApplyOnlines->delete($entity);
				//$this->ApplyOnlineApprovals->deleteAll(['application_id' => $appData->id]);
				echo $appData->id." --> ".$appData->consumer_no.'<br>';
			}
		}
		exit;
	}

	/**
	* Function Name : mapview
	* @param
	* @return
	* @author Kalpak Prajapati
	*/
	public function mapview()
	{
		$Installers 		= $this->Installers->find('list',['keyField'=>'id','valueField'=>'installer_name'])->toArray();
		$discom_arr 		= array();
		$state 				= 4;
		$discoms 			= $this->BranchMasters->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['BranchMasters.status'=>'1','BranchMasters.parent_id'=>'0','BranchMasters.state'=>$state]])->toArray();
		$this->set('getProjectClusterData',$this->getProjectClusterData());
		$this->set('Categories',$this->Parameters->GetParameterList(3));
		$this->set('Discoms',$discoms);
		$this->set('Installers',$Installers);
	}

	/**
	* Function Name : random_color_marker
	* @param $map_icons
	* @return
	* @author Kalpak Prajapati
	*/
	private function random_color_marker($map_icons)
	{
		$found      = true;
		$counter    = 0;
		$img_name   = "pin56_[RAND_NUM].png";
		while ($found) {
			$RAND_NUM   = rand(0,21);
			$color_name = str_replace("[RAND_NUM]",$RAND_NUM,$img_name);
			if (!in_array($color_name,$map_icons)) {
				$found = false;
			}
			if ($counter == 5) {
				$found = false;
			}
			$counter++;
		}
		return $color_name;
	}

   /**
	* Function Name : getProjectClusterData
	* @param
	* @return
	* @author Kalpak Prajapati
	*/
	public function getProjectClusterData()
	{
		$arrResult['data']      = array();
		$arrResult['map_style'] = '';
		$arrResult['map_icons'] = '';
		$resultArray        	= array();
		$arrCondition 			= array('ApplyOnlines.lattitue IS NOT ' => NULL,
										'ApplyOnlines.longitude IS NOT ' => NULL,
										'ApplyOnlines.application_status IS NOT ' => NULL);
		$Joins 					= array('Parameters' => ['table' => 'parameters','type' => 'INNER','conditions' => ['Parameters.para_id = ApplyOnlines.category']],
										'Installers' => ['table' => 'installers','type' => 'INNER','conditions' => ['Installers.id = ApplyOnlines.installer_id']]);
		$arrFields 				= array('Parameters.para_value','ApplyOnlines.category','ApplyOnlines.pv_capacity',
										'ApplyOnlines.lattitue','ApplyOnlines.longitude','Installers.installer_name',
										'ApplyOnlines.geda_application_no','ApplyOnlines.application_no');
		if (isset($this->request->data['ApplyOnlines']['discom']) && !empty($this->request->data['ApplyOnlines']['discom'])) {
			$arrCondition['ApplyOnlines.discom'] = intval($this->request->data['ApplyOnlines']['discom']);
		}
		if (isset($this->request->data['ApplyOnlines']['installer']) && !empty($this->request->data['ApplyOnlines']['installer'])) {
			$arrCondition['ApplyOnlines.installer_id'] = intval($this->request->data['ApplyOnlines']['installer']);
		}
		if (isset($this->request->data['ApplyOnlines']['category']) && !empty($this->request->data['ApplyOnlines']['category'])) {
			$arrCondition['ApplyOnlines.category'] = intval($this->request->data['ApplyOnlines']['category']);
		}
		if (isset($this->request->data['ApplyOnlines']['city']) && !empty($this->request->data['ApplyOnlines']['city'])) {
			$arrCondition['ApplyOnlines.city LIKE '] = "%".$this->request->data['ApplyOnlines']['city']."%";
		}
		if (isset($this->request->data['ApplyOnlines']['meter_installed']) && $this->request->data['ApplyOnlines']['meter_installed'] != '') {
			if ($this->request->data['ApplyOnlines']['meter_installed'] == 1 || $this->request->data['ApplyOnlines']['meter_installed'] == 0) {
				$Joins['Meter_Installed'] = ['table' => 'apply_online_approvals',
											'type' => 'LEFT',
											'conditions' => ['Meter_Installed.application_id = ApplyOnlines.id AND Meter_Installed.stage = '.$this->ApplyOnlineApprovals->METER_INSTALLATION]];
				$arrFields['Meter_Installed_Status'] = "IF(Meter_Installed.id > 0,'Y','N')";
			}
		}
		$arrClusterRows  = $this->ApplyOnlines
								->find('all',['fields'=>$arrFields,'join'=>$Joins])
								->hydrate(false)
								->where($arrCondition);
		if (isset($this->request->data['ApplyOnlines']['meter_installed']) && $this->request->data['ApplyOnlines']['meter_installed'] != '') {
			if (isset($this->request->data['ApplyOnlines']['meter_installed']) && $this->request->data['ApplyOnlines']['meter_installed'] == 1) {
				$arrClusterRows->having("Meter_Installed_Status = 'Y'");
			} else if (isset($this->request->data['ApplyOnlines']['meter_installed']) && $this->request->data['ApplyOnlines']['meter_installed'] == 0) {
				$arrClusterRows->having("Meter_Installed_Status = 'N'");
			}
		}
		$arrClusterRows->order('ApplyOnlines.category','ASC');
		$arrResultRows	= $arrClusterRows->toList();
		$map_icons      = array();
		$map_style      = array();
		$Counter        = 0;
		$ApplicationCnt = 0;
		$Prev_Group_Id  = 0;
		if (!empty($arrResultRows)) {
			foreach ($arrResultRows as $Row) {
				$TAG = preg_replace("/[^0-9a-z]/i","",strtolower($Row['Parameters']['para_value']));
				if (!isset($map_icons[$Row['category']])) {
					$COLOR_CODE     = $this->random_color_marker($map_style);
					array_push($map_style,$COLOR_CODE);
					$map_icons[$Row['category']]   = array("group"=>$Row['Parameters']['para_value'],
															"lbl"=>$TAG,
															"count"=>0,
															"icon"=>$COLOR_CODE);

					if ($Prev_Group_Id > 0) {
						$map_icons[$Prev_Group_Id]['count'] = $Counter;
						$Counter = 0;
					}
					$Prev_Group_Id = $Row['category'];
				}
				$Application_No 		= (!empty($Row['geda_application_no'])?$Row['geda_application_no']:$Row['application_no']);
				$arrResult['data'][] 	= array("lat"=>$Row['lattitue'],
												"lng"=>$Row['longitude'],
												"options"=>array("icon"=>"/img/mapIcons/pins/".$map_icons[$Row['category']]['icon']),
												"tag"=>$TAG,
												"data"=>array(	"Category"=>$Row['Parameters']['para_value'],
																"Installer"=>$Row['Installers']['installer_name'],
																"Capacity"=>$Row['pv_capacity'],
																"Application_No"=>$Application_No));

				$Counter++;
				$ApplicationCnt++;
			}
			if ($Prev_Group_Id > 0) {
				$map_icons[$Prev_Group_Id]['count'] = $Counter;
				$Counter = 0;
			}
		}
		$this->set("ApplicationCnt",$ApplicationCnt);
		$arrResult['map_icons'] = $map_icons;
		return $arrResult;
	}

	/**
	 * getlastsubsidymessage
	 * Behaviour : Public
	 * @defination : Method is use to getlastsubsidymessage for per application
	 */
	public function getlastsubsidymessage()
	{
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['appid'])?$this->request->data['appid']:0);
		$ApplyonlineMessage = array("last_message_id"=>0,"last_message"=>"");
		if(!empty($id)) {
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$member_id      		= $this->Session->read("Members.id");
			$ApplyonlineMessage 	= $this->ApplyonlineMessage->GetLastMessageByApplicationForClaim($id,1);
		}
		$this->ApiToken->SetAPIResponse('success',0);
		$this->ApiToken->SetAPIResponse('last_message',$ApplyonlineMessage);
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	 * ReplyToMessage
	 * Behaviour : Public
	 * @defination : Method is use to ReplyToMessage To Subsidy Claim Request
	 */
	public function ReplyToMessage()
	{
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['appid'])?$this->request->data['appid']:0);
		$last_message_id 	= (isset($this->request->data['last_message_id'])?$this->request->data['last_message_id']:0);
		$message 			= (isset($this->request->data['messagebox'])?$this->request->data['messagebox']:'');
		if(empty($id) || empty($message)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} else {
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->ApplyOnlines->viewApplication($id);
			if (!empty($applyOnlinesData)) {
				if ($this->request->is('post') || $this->request->is('put')) {
					$customer_type 								= $this->Session->read('Customers.customer_type');
					$customer_id          						= $this->Session->read("Customers.id");
					$member_id          						= $this->Session->read("Members.id");
					$member_type 								= $this->Session->read('Members.member_type');
					$browser 									= isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:"-";
					$ApplyonlineMessageEntity					= $this->ApplyonlineMessage->newEntity();
					$ApplyonlineMessageEntity->application_id 	= $id;
					$ApplyonlineMessageEntity->message 			= strip_tags($message);
					$ApplyonlineMessageEntity->user_type 		= !empty($customer_type)?0:$member_type;
					$ApplyonlineMessageEntity->user_id 			= !empty($customer_id)?$customer_id:$member_id;
					$ApplyonlineMessageEntity->ip_address 		= $this->IP_ADDRESS;
					$ApplyonlineMessageEntity->created 			= $this->NOW();
					$ApplyonlineMessageEntity->browser_info 	= json_encode($browser);
					$ApplyonlineMessageEntity->for_claim 		= 2;
					$ApplyonlineMessageEntity->reply_msg_id 	= decode($last_message_id);
					if($this->ApplyonlineMessage->save($ApplyonlineMessageEntity)) {
						$ErrorMessage 	= "Message sent successfully.";
						$success 		= 1;
						$applyid 		= $applyOnlinesData->id;
						if(!empty($applyid)) {
							$data 				= $this->ApplyOnlines->get($applyid);
							$data->query_sent	= '0';
							$data->query_date 	= "0000-00-00 00:00:00";
							$data->modified 	= date('Y-m-d H:i:s');
							$this->ApplyOnlines->save($data);
						}

						/** Update Subsidy Claim Messge as Replied By Client */
						$MessageDetails = $this->ApplyonlineMessage->get(decode($last_message_id));
						$Message_For 	= 0;
						if (!empty($MessageDetails)) {
							$Message_For 				= $MessageDetails->user_id;
							$MessageDetails->for_claim 	= 2;
							$this->ApplyonlineMessage->save($MessageDetails);
						}
						/** Update Subsidy Claim Messge as Replied By Client */

						/** Insert Unread Message Counter */
						$this->ApplyonlineUnReadMessage->saveUnReadMessage($ApplyonlineMessageEntity->id,$Message_For);
						/** Insert Unread Message Counter */

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
	 * GetUnreadMessages
	 * Behaviour : Public
	 * @defination : Method is use to GetUnreadMessages per application
	 */
	public function GetUnreadMessages()
	{
		$this->autoRender 		= false;
		$ApplyonlineMessage 	= array();
		$member_id      		= $this->Session->read("Members.id");
		$ApplyonlineMessage 	= $this->ApplyonlineUnReadMessage->GetUnreadMessages($member_id);
		$view 					= new View($this->request,$this->response);
		$view->layout 			= 'empty';
		$view->set(compact('ApplyonlineMessage', $ApplyonlineMessage));
		$html = $view->render('/ApplyOnlines/get_unread_messages');
		$this->ApiToken->SetAPIResponse('html',$html);
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	 * MarkAsRead
	 * Behaviour : Public
	 * @defination : Method is use to MarkAsRead per application
	 */
	public function MarkAsRead($message_id="")
	{
		$this->autoRender 		= false;
		$success 				= 0;
		$member_id      		= $this->Session->read("Members.id");
		if (!empty($member_id) && !empty($message_id)) {
			$success 		= 1;
			$message_id 	= decode($message_id);
			$this->ApplyonlineUnReadMessage->MarkAsRead($message_id);
		}
		$this->ApiToken->SetAPIResponse('success',$success);
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	*
	* getConsumerGenerationData
	*
	* Behaviour : public
	*
	* @param : consumer numbers
	*
	* @defination : Method is use to get consumer generation data.
	*
	*/
	public function getConsumerGenerationData()
	{
		$this->layout 			= false;
		$arrError 				= array();
		$consumer_no 			= isset($this->request->data['consumer_no'])?$this->request->data['consumer_no']:"";
		$t_no 					= isset($this->request->data['t_no'])?$this->request->data['t_no']:"";
		$category 				= isset($this->request->data['category'])?$this->request->data['category']:"";
		$start_date 			= isset($this->request->data['start_date'])?$this->request->data['start_date']:"";
		$end_date 				= isset($this->request->data['end_date'])?$this->request->data['end_date']:"";
		$generation 			= isset($this->request->data['generation'])?$this->request->data['generation']:"";
		if(empty($consumer_no)) {
			$arrError[] = 'Consumer No is required.';
		} else if(empty($category)) {
			$arrError[] = 'Tariff category is required.';
		} else if(empty($start_date)) {
			$arrError[] = 'Generation State Time is required.';
		} else if(empty($end_date)) {
			$arrError[] = 'Generation End Time is required.';
		} else if(empty($generation)) {
			$arrError[] = 'Generation is required.';
		}
		if (!empty($arrError)) {
			$this->ApiToken->SetAPIResponse('type',"error");
			$this->ApiToken->SetAPIResponse('msg',$arrError[0]);
		} else {
			$EnergyGenerationLog           			= $this->EnergyGenerationLog->newEntity();
			$EnergyGenerationLog->consumer_no  		= $consumer_no;
			$EnergyGenerationLog->t_no     			= $t_no;
			$EnergyGenerationLog->category      	= $category;
			$EnergyGenerationLog->start_date	   	= date("Y-m-d H:i:s",strtotime($start_date));
			$EnergyGenerationLog->end_date 			= date("Y-m-d H:i:s",strtotime($end_date));
			$EnergyGenerationLog->generation 		= $generation;
			$EnergyGenerationLog->ip_address 		= $this->request->clientIp();
			$EnergyGenerationLog->created 		   	= $this->NOW();
			$this->EnergyGenerationLog->save($EnergyGenerationLog);
			$this->ApiToken->SetAPIResponse('type',"ok");
			$this->ApiToken->SetAPIResponse('msg',"Generation data saved successfully.");
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	*
	* deleteapplicationbygeda
	*
	* Behaviour : private
	*
	* @param :
	*
	* @defination : Method is use to delete application from apply onlines table.
	*
	*/
/*	public function deleteapplicationbygeda()
	{
		$ApplyOnlineLead 	= array();
		$InvalidID 			= false;
		$discom_arr 		= array();
		if ($this->request->is('post')) {
			$consumer_no 			= isset($this->request->data['geda_consumer_no'])?$this->request->data['geda_consumer_no']:"";
			$geda_application_no 	= isset($this->request->data['geda_application_no']?$this->request->data['geda_application_no']:"";
			$is_delete 				= isset($this->request->data['is_delete'])?$this->request->data['is_delete']:"N";
			if (!empty($consumer_no) && !empty($geda_application_no)) {
				$arrCondition 		= array("consumer_no"=>$consumer_no,"geda_application_no"=>$geda_application_no);
			} else if (!empty($consumer_no)) {
				$arrCondition 		= array("consumer_no"=>$consumer_no);
			} elseif (!empty($geda_application_no)) {
				$arrCondition 		= array("geda_application_no"=>$geda_application_no);
			} else {
				$arrCondition 		= array();
			}
			if (!empty($arrCondition) && !empty($is_delete) && $is_delete == "Y") {
				$application_data 	= $this->ApplyOnlines->find('all',array('conditions'=>$arrCondition))->toArray();
				if(!empty($application_data)) {
					foreach($application_data as $appData) {
						$browser 					   = isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:"-";
						$removeentity                  = $this->ApplicationDeleteLog->newEntity();
						$removeentity->application_id  = $appData->id;
						$removeentity->customer_id     = 0;
						$removeentity->ip_address      = $this->IP_ADDRESS;
						$removeentity->browser_info	   = json_encode($browser);
						$removeentity->application_data= json_encode($appData);
						$removeentity->created 		   = $this->NOW();
						if($this->ApplicationDeleteLog->save($removeentity)) {
							$entity = $this->ApplyOnlines->get($appData->id);
							$this->ApplyOnlines->delete($entity);
							$this->Flash->success("The Application has been deleted from Unified Single Window Portal of GEDA.");
							return $this->redirect(URL_HTTP.'/remove-application');
						}
					}
				} else {
					$this->Flash->error("The Application not found in Unified Single Window Portal of GEDA.");
					return $this->redirect(URL_HTTP.'/remove-application');
				}
			} else if (!empty($arrCondition) && $is_delete == "N") {
				$application_data 	= $this->ApplyOnlines->find('all',array('conditions'=>$arrCondition))->toArray();
				if(!empty($application_data)) {
					foreach ($application_data as $appData) {
						$geda_mobile_no 		= $appData->consumer_mobile;
						$ApplyOnlineLead 		= $this->ApplyOnlines->FindByApplicationNo($geda_application_no,$consumer_no,$geda_mobile_no);
						if (empty($ApplyOnlineLead)) {
							$InvalidID = true;
						}
					}
				} else {
					$InvalidID = true;
				}
				$state 		= $this->CUSTOMER_STATE_ID;
				$discoms 	= $this->BranchMasters->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['BranchMasters.status'=>'1','BranchMasters.parent_id'=>'0','BranchMasters.state'=>$state]])->toArray();
				if(!empty($discoms)) {
					foreach($discoms as $id=>$title) {
						$discom_arr[$id] = $title;
					}
				}
			} else {
				$this->Flash->error("Invalid parameters for application review status.");
				return $this->redirect(URL_HTTP.'/remove-application');
			}
		}

		
		$this->set("APPLY_ONLINE_MAIN_STATUS",$this->ApplyOnlineApprovals->apply_online_main_status);
		$this->set("APPLICATION_SUBMITTED",$this->ApplyOnlineApprovals->APPLICATION_SUBMITTED);
		$this->set("FEASIBILITY_APPROVAL",$this->ApplyOnlineApprovals->FEASIBILITY_APPROVAL);
		$this->set("FUNDS_ARE_AVAILABLE_BUT_SCHEME_IS_NOT_ACTIVE",$this->ApplyOnlineApprovals->FUNDS_ARE_AVAILABLE_BUT_SCHEME_IS_NOT_ACTIVE);
		$this->set("FUNDS_ARE_NOT_AVAILABLE",$this->ApplyOnlineApprovals->FUNDS_ARE_NOT_AVAILABLE);
		$this->set("SUBSIDY_AVAILIBILITY",$this->ApplyOnlineApprovals->SUBSIDY_AVAILIBILITY);
		$this->set("WORK_STARTS",$this->ApplyOnlineApprovals->WORK_STARTS);
		$this->set("APPLICATION_GENERATE_OTP",$this->ApplyOnlineApprovals->APPLICATION_GENERATE_OTP);
	
		$this->set("JREDA",$this->ApplyOnlines->JREDA);
		$this->set("DISCOM",$this->ApplyOnlines->DISCOM);
		$this->set("CEI",$this->ApplyOnlines->CEI);
		$this->set("MStatus",$this->ApplyOnlineApprovals);
		$this->set("ApplyOnlines",$this->ApplyOnlines);
		$this->set("FesibilityReport",$this->FesibilityReport);
		$this->set("application_status",$this->ApplyOnlineApprovals->application_status);
		$this->set("application_dropdown_status",$this->ApplyOnlines->apply_online_dropdown_status);
		$this->set("branch_id","");
		$this->set("subdivision","");
		$this->set('ApplyOnlineLeads',"");
		$this->set("ApplyOnlineLead",$ApplyOnlineLead);
		$this->set("MemberTypeDiscom",$this->Members->member_type_discom);
		$this->set("discom_details","");
		$this->set("payment_on",Configure::read('PAYUMONEY_PAYMENT'));
		$this->set("APPLY_ONLINE_GUJ_STATUS",$this->ApplyOnlineApprovals->apply_online_guj_status);
		$this->set("applyOnlinesDataDocList",$this->ApplyonlinDocs);
		$this->set('discom_arr',$discom_arr);
		$this->set('quota_msg_disp',"");
		$this->set('InvalidID',$InvalidID);
		$this->set('pageTitle','Delete Consumer Application');
	}*/
	/**
	*
	* RemoveCommonMeter
	*
	* Behaviour : private
	*
	* @param :
	*
	* @defination : Method is use to remove common meter from apply onlien and project and recalulte project cost related data
	*
	*/
	public function RemoveCommonMeter()
	{
		$this->layout 			= false;
		$application_id 		= intval(decode($this->request->data['meter_application_id']));
		
		if(!empty($application_id))
		{
			$ApplicationData 	= $this->ApplyOnlines->find('all',array(
											'fields'	=> array('project_id'),
											'conditions'=> array('id'=>$application_id)))->first();

			
			$this->ApplyOnlines->updateAll(['common_meter'=>0],['id'=>$application_id]);
			$this->Projects->updateAll(['project_common_meter'=>0],['id'=>$ApplicationData->project_id]);
			$this->Subsidy->updateAll(['common_meter'=>0],['application_id'=>$application_id]);
			echo json_encode(array('type'=>'ok','response'=>'Common meter removed successfully.'));
		}
		else
		{
			echo json_encode(array('type'=>'error','response'=>'Common meter not removed .'));
		}
		exit;
	}
	/**
	 * RemoveApplicationMember
	 * Behaviour : Public
	 * @defination :Delete Application from admin panel.
	 */
	public function RemoveApplicationMember()
	{
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['application_id']) ? $this->request->data['application_id'] : 0);
		$reason 			= (isset($this->request->data['reason']) ? $this->request->data['reason'] : 0);
		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} else if(CAN_DELETE_APPLICATION_MEMBER == 1) {
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			if(!empty($this->Session->read("Members.id")))
			{
				$customerId = $this->Session->read("Members.id");
			}
			else
			{
				$customerId = $this->Session->read("Customers.id");
			}

			$applyOnlinesData 		= $this->ApplyOnlines->viewApplication($id);
			$application_data 		= $this->ApplyOnlines->find('all',array('conditions'=>array('id'=>$id)))->first();
			
			$proj_id = $applyOnlinesData['project_id'];
			if (!empty($applyOnlinesData)) {
				if ($this->request->is('post')) {
					$browser 					   	= isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:"-";
					$removeentity                  	= $this->ApplicationDeleteLog->newEntity();
					$removeentity->application_id  	= $id;
					$removeentity->customer_id     	= $customerId;
					$removeentity->ip_address      	= $this->IP_ADDRESS;
					$removeentity->reason      		= $reason;
					$removeentity->browser_info	   	= json_encode($browser);
					$removeentity->application_data	= json_encode($application_data);
					$removeentity->created 		    = $this->NOW();
					$this->ApplicationDeleteLog->save($removeentity);

					$sql_icm 			= " SET FOREIGN_KEY_CHECKS = 0;DELETE FROM apply_onlines WHERE id = '".$id."';SET FOREIGN_KEY_CHECKS = 1;";
					$connection         = ConnectionManager::get('default');
					$icm_output  		= $connection->execute($sql_icm);
					
					//$entity = $this->ApplyOnlines->get($id);
					//$this->ApplyOnlines->delete($entity);
					
					$ErrorMessage 	= "Application Delete Successfully.";
					$success 		= 1;
					$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
					$this->ApiToken->SetAPIResponse('success',$success);
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate Details.";
				$success 		= 0;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	public function makeGedaLetter()
	{
		//$arrApplication 	= array(53929,53925,54002,53988,54021,54117,54132,54130,54121,54183,54022,54193,54181,54227);
		//$arrApplication 	= array(53846);
		//$arrApplication 	= array(47406,53795,54286,54521,54559,54656,54853,54893);
		//$arrApplication 	= array(55272);
		//$arrApplication 	= array(54435);
		//$arrApplication 	= array(54748,56473,56912);
		//$arrApplication 	= array(63242,63432,64364);
		$arrApplication 	= array(53910,54313,55049,55987,55703,56085,56660,55048,55955,58629,57460,59599,59735,59964,59452,55965,60279,60813,55375,61635,62289,62393,61201,62483,62422,62414,61734,61775,56857,60916,61720,60903,62388,61686);
		foreach($arrApplication as $id) {
			$applyOnlinesData 		= $this->ApplyOnlines->viewApplication($id);
			if (!empty($applyOnlinesData)) {
				$applyOnlinesOthersData 	= $this->ApplyOnlinesOthers->find('all',array('conditions'=>array('application_id'=>$id)))->first();
				// && $applyOnlinesOthersData->contract_load_more!=1
				if($applyOnlinesData->social_consumer == 0){
					$application_status = $this->ApplyOnlineApprovals->APPROVED_FROM_GEDA;
					$this->ApplyOnlines->updateAll(array('application_status'=>$application_status),array('id'=>$id));
					$this->ApplyOnlineApprovals->saveStatus($id,$this->ApplyOnlineApprovals->APPROVED_FROM_GEDA,0,'');
					$geda_application_no 	= $this->ApplyOnlines->GenerateGedaApplicationNo($applyOnlinesData);
					$this->ApplyOnlines->updateAll(array('geda_application_no'=>$geda_application_no),array('id'=>$applyOnlinesData->id));
					echo $id.' -- Processed<br>';
				}
			}
		}
		exit;
	}
	/**
	 *
	 * AdditionalCapacity
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to add additional capacity feature.
	 *
	 */
	public function AdditionalCapacity($id = 0, $project_id = 0) {
		$is_installer 			= false;
		$installer_id           = '';

		if(!empty($this->Session->read('Members.member_type')))
		{
			$this->setMemberArea();
			$member_type 		= $this->Session->read('Members.member_type');
			$customerId 		= $this->Session->read("Members.id");
			$ses_customer_type 	= $this->Session->read('Members.member_type');
			$ses_login_type 	= 'Members';
			$is_installer 		= false;
			$decode_id 			= decode($id);
			$app_oth_details 	= $this->ApplyOnlinesOthers->find('all',array('conditions'=>array('application_id'=>$decode_id)))->first();
			$customerTable 		= ($app_oth_details->created_by_type == 'developer') ? 'DeveloperCustomers' : 'Customers';
			$installerTable 	= ($app_oth_details->created_by_type == 'developer') ? 'Developers' : 'Installers';
		}
		else
		{
			$this->setCustomerArea();
			$customerId 		= $this->Session->read("Customers.id");
			$ses_customer_type 	= $this->Session->read('Customers.customer_type');
			$ses_login_type 	= $this->Session->read('Customers.login_type');
			$customerTable 		= ($ses_login_type == 'developer') ? 'DeveloperCustomers' : 'Customers';
			$installerTable 	= ($ses_login_type == 'developer') ? 'Developers' : 'Installers';
			if ($ses_customer_type == "installer") {
				$is_installer 	= true;
				$customer_details 	= $this->$customerTable->find('all',array('conditions'=>array('id'=>$customerId)))->first();
				$installer_id 		= $customer_details['installer_id'];
			}
		}

		//$this->removeExtraTags('ApplyOnlines');

		$tab 		= '';
		$ApplyonlinDocsList = array();
		$Applyonlinprofile 	= array();
		if($id == '0' && $project_id == '0' && $is_installer==false)
		{
			return $this->redirect('project');
		}
		$project_id 	= decode($project_id);

		$create_project = '0';
		if(!empty($id) && $id!='0'){
			$id 	= decode($id);
			$app_details 	= $this->ApplyOnlines->find('all',array('conditions'=>array('id'=>$id)))->first();
			$project_id 	= $app_details->project_id;
			$this->set("edit_id",$id);
			$this->set("str_url",encode($id).'/'.encode($project_id));
		} else {
			$ApplyOnlinesPendingToSubmit = $this->ApplyOnlines->find("all",['fields'=>['id'],'conditions'=>['or'=>['application_status is null','application_status'=>'','application_status'=>'0'],'customer_id'=>$customerId,'project_id'=>$project_id]])->toArray();
			//echo 'customer_id'.$customerId,'project_id'.$project_id;
			//pr($ApplyOnlinesPendingToSubmit);exit;
			if(!empty($ApplyOnlinesPendingToSubmit)) {
				return $this->redirect('add-additional-capacity/'.encode($ApplyOnlinesPendingToSubmit[0]['id']).'/'.encode($project_id));
			}
			if($project_id>0)
			{
				$ApplyOnlinesAlreadySubmit = $this->ApplyOnlines->find("all",['conditions'=>['application_status is not null','customer_id'=>$customerId,'project_id'=>$project_id,'project_id is not null']])->toArray();

				if(!empty($ApplyOnlinesAlreadySubmit)) {
					$this->Flash->set('Application is already been submitted for selected project.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'error']]);
					return $this->redirect('apply-online-list');
				}
				$this->set("str_url",'0/'.encode($project_id));
			}
			else if($is_installer==true)
			{
				$create_project = '1';
				$this->set("str_url",'');
			}
			$id = '';
		}
		$connection         = ConnectionManager::get('default');
		$sql_icm 			= " SELECT ic.id
								FROM installer_category_mapping as icm left join installer_category as ic on icm.category_id=ic.id
								WHERE installer_id 	='".$installer_id."'";
		$icm_output  		= $connection->execute($sql_icm)->fetchAll('assoc');
		$allocatedCategory  = 0;
		if(isset($icm_output[0]['id']))
		{
			$allocatedCategory  = $icm_output[0]['id'];
		}
		$arrFieldsMap 	= array('rpo_rec','rpo_is_captive','rpo_is_obligation','gerc_is_distribution','gerc_certificate','rpo_is_cpp','capacity_cpp','rpo_is_captive_rpo','rpo_is_cert_getco','capacity_rpo_cert','rec_is_registration','rec_registration_copy','rec_is_receipt','rec_receipt_copy','rec_is_power_evaluation','rec_power_evaluation','rec_is_allowed_sancation','rec_is_valid_min_cap','upload_undertaking');
		$project_data 		= $this->Projects->find('all',array('conditions'=>array('id'=>$project_id)))->first();
		$execution_data 	= array();
		if(isset($customerId) && !empty($customerId)){
			$this->layout = 'frontend';
			if(!empty($id)) {
				$ApplyOnlineData 						= $this->ApplyOnlines->get($id);
				$ApplyOnlinesEntity						= $this->ApplyOnlines->patchEntity($ApplyOnlineData,$this->request->data,['validate'=>'tab1']);
				$this->ApplyOnlines->data_entity 		= $ApplyOnlinesEntity;
				$project_id 							= $ApplyOnlinesEntity->project_id;
				$execution_data 						= $this->Installation->find('all',array('conditions'=>array('project_id'=>$project_id)))->first();

				$OthersData 							= $this->ApplyOnlinesOthers->find('all',array('conditions'=>array('application_id'=>$id)))->first();

				//$ApplyOnlineOthersGet 				= $this->ApplyOnlinesOthers->get($OthersData->id);

				$ApplyOnlinesOthersEntity				= $this->ApplyOnlinesOthers->patchEntity($OthersData,$this->request->data);
				$ApplyOnlinesOthersEntity->modified_by_type = $ses_login_type;
				$ApplyOnlinesOthersEntity->modified_by 		= $customerId;
				$this->ApplyOnlines->data_entity_others 	= $ApplyOnlinesOthersEntity;

			} else {
				$ApplyOnlinesEntity	= $this->ApplyOnlines->newEntity($this->request->data,['validate'=>'tab1']);
				$this->ApplyOnlines->data_entity 	= $ApplyOnlinesEntity;
				$ApplyOnlinesEntity->created 		= $this->NOW();
				$ApplyOnlinesOthersEntity			= $this->ApplyOnlinesOthers->newEntity($this->request->data);
				$ApplyOnlinesOthersEntity->scheme_id 		= $this->SchemeMaster->findActiveSchemeId();
				$ApplyOnlinesOthersEntity->modified_by_type = $ses_login_type;
				$ApplyOnlinesOthersEntity->modified_by 		= $customerId;
				$ApplyOnlinesOthersEntity->created_by_type 	= $ses_login_type;
				$ApplyOnlinesOthersEntity->created_by 		= $customerId;
			}


			$condition_state_list = array('id'=>'4');
			$customer_details 	= $this->$customerTable->find('all',array('conditions'=>array('id'=>$customerId)))->first();
			if($is_installer == true && (!isset($this->request->data) || empty($this->request->data)) && empty($id) && $create_project=='0')
			{
				$state_details 		= $this->States->find('all',array('conditions'=>array('statename'=>$customer_details['state'])))->first();
				if(!empty($state_details))
				{
					$this->request->data['ApplyOnlines']['apply_state'] = $state_details['id'];
				}
				else
				{
					$this->request->data['ApplyOnlines']['apply_state'] = $customer_details['state'];
				}
				$this->request->data['ApplyOnlines']['installer_id'] 	= $customer_details['installer_id'];
				$condition_state_list 									= array('id'=>$this->request->data['ApplyOnlines']['apply_state']);
				$this->request->data['ApplyOnlines']['disclaimer']		= '1';
				$this->request->data['tab_1'] 							= '1';
			}
			$project_errors = array();
			if(isset($this->request->data) && !empty($this->request->data) && (isset($this->request->data['tab_4'])))
			{
				//&& $this->request->data['project_type']!=$this->ApplyOnlines->category_others && empty($this->request->data['project_social_consumer'])
				if($allocatedCategory==3 || ($this->request->data['project_type']!=$this->ApplyOnlines->category_residental))
				{
					$this->request->data['project_disclaimer_subsidy']	= '1';
					//$this->request->data['project_renewable_attr_chk']	= '1';
				}
				$this->Projects->data_post 						= $this->request->data;
				$this->Projects->data_post['ins_id'] 			= $customer_details['installer_id'];
				$ApplyOnlinesEntity								= $this->Projects->newEntity($this->request->data,['validate'=>'tab4']);
				//&& $this->request->data['project_type']!=$this->ApplyOnlines->category_others && empty($this->request->data['project_social_consumer'])
				if($allocatedCategory==3 || ($this->request->data['project_type']!=$this->ApplyOnlines->category_residental))
				{
					$ApplyOnlinesEntity->project_disclaimer_subsidy	= '1';
					//$ApplyOnlinesEntity->project_renewable_attr_chk	= '1';

				}
				$project_errors 									= $ApplyOnlinesEntity->errors();
				if(!$ApplyOnlinesEntity->errors())
				{
					$this->request->data['ses_login_type']			= $ses_login_type;
					$resultArr = $this->Projects->getprojectestimation($this->request->data,$customerId);
					return $this->redirect('add-additional-capacity/0/'.encode($resultArr['proj_id']));
				}
			}
			if(isset($this->request->data) && !empty($this->request->data) && (isset($this->request->data['tab_1']) || isset($this->request->data['tab_2']) || isset($this->request->data['tab_3']) || isset($this->request->data['save_submit'])) && $create_project=='0')
			{
				$this->request->data['isEnhancement'] 	= 1;
			   	$this->ApplyOnlines->data 				= $this->request->data;
				if(isset($this->request->data['tab_1']) && !empty($this->request->data['tab_1'])) {
					if(empty($this->request->data['ApplyOnlines']['id'])) {
						unset($this->request->data['ApplyOnlines']['id']);
						$ApplyOnlinesEntity	= $this->ApplyOnlines->newEntity($this->request->data,['validate'=>'tab1']);
						$this->ApplyOnlines->data_entity = $ApplyOnlinesEntity;
						//$ApplyOnlinesEntity->transmission_line 						= 1;
						//$this->request->data['ApplyOnlines']['transmission_line']	= 1;
						$ApplyOnlinesEntity->net_meter 								= 1;
						$this->request->data['ApplyOnlines']['net_meter']			= 1;
						$this->request->data['ApplyOnlines']['created'] 			= $this->NOW();
						$ApplyOnlinesEntity->created 								= $this->NOW();
						if(!empty($project_data))
						{
							$ApplyOnlinesEntity->lattitue 	= $project_data->latitude;
							$ApplyOnlinesEntity->longitude 	= $project_data->longitude;
						}
						$ApplyOnlinesOthersEntity			= $this->ApplyOnlinesOthers->newEntity($this->request->data);

						$ApplyOnlinesOthersEntity->renewable_attr 	= $project_data->project_renewable_attr;
						$ApplyOnlinesOthersEntity->scheme_id 		= $this->SchemeMaster->findActiveSchemeId();
						$ApplyOnlinesOthersEntity->renewable_rec 	= $project_data->project_renewable_rec;
						$ApplyOnlinesOthersEntity->modified_by_type = $ses_login_type;
						$ApplyOnlinesOthersEntity->modified_by 		= $customerId;
						$ApplyOnlinesOthersEntity->created_by_type 	= $ses_login_type;
						$ApplyOnlinesOthersEntity->created_by 		= $customerId;

					} else {
						$ApplyOnlineGet 	= $this->ApplyOnlines->get($this->request->data['ApplyOnlines']['id']);
						$ApplyOnlinesEntity	= $this->ApplyOnlines->patchEntity($ApplyOnlineGet,$this->request->data,['validate'=>'tab1']);
						$this->ApplyOnlines->data_entity = $ApplyOnlinesEntity;
						$this->request->data['ApplyOnlines']['modified'] 		= $this->NOW();
						$OthersData 						= $this->ApplyOnlinesOthers->find('all',array('conditions'=>array('application_id'=>$ApplyOnlineGet->id)))->first();

						//$ApplyOnlineOthersGet 				= $this->ApplyOnlinesOthers->get($ApplyOnlineGet->id);

						$ApplyOnlinesOthersEntity			= $this->ApplyOnlinesOthers->patchEntity($OthersData,$this->request->data);
						$ApplyOnlinesOthersEntity->renewable_rec 	= $project_data->project_renewable_rec;
						$ApplyOnlinesOthersEntity->modified_by_type = $ses_login_type;
					}
					if(!$ApplyOnlinesEntity->errors()){
						if($allocatedCategory==3 || (!empty($project_data) && $project_data->project_disclaimer_subsidy==1))
						{
							$ApplyOnlinesEntity->disclaimer_subsidy	= '1';
						}
						$tab = 'tab_1';
						//$this->request->data['ApplyOnlines']['created'] = $this->NOW();
					} else {
						//$this->request->data['ApplyOnlines']['created'] = $this->NOW();
						$tab = '';
					}
				} else if(isset($this->request->data['tab_2']) && !empty($this->request->data['tab_2'])) {
					$ApplyOnlineGet 								= $this->ApplyOnlines->get($this->request->data['ApplyOnlines']['id']);
					if(isset($this->ApplyOnlines->data['ApplyOnlines']['category']) && !empty($this->ApplyOnlines->data['ApplyOnlines']['category']))
					{
						$this->Projects->updateAll(['customer_type'=>$this->ApplyOnlines->data['ApplyOnlines']['category']],['id'=>$project_id]);
					}
					if(!isset($this->request->data['ApplyOnlines']['transmission_line']))
					{
						$this->request->data['ApplyOnlines']['transmission_line'] = !empty($ApplyOnlineGet->transmission_line) ? $ApplyOnlineGet->transmission_line : '';
					}
					$this->ApplyOnlines->data['ApplyOnlines']['transmission_line'] 	= $this->request->data['ApplyOnlines']['transmission_line'];
					if(!isset($this->request->data['ApplyOnlines']['net_meter']))
					{
						$this->request->data['ApplyOnlines']['net_meter'] = !empty($ApplyOnlineGet->net_meter) ? $ApplyOnlineGet->net_meter : '1';
					}
					$this->ApplyOnlines->data['ApplyOnlines']['net_meter'] 	= $this->request->data['ApplyOnlines']['net_meter'];

					if(($this->request->data['ApplyOnlines']['category']!=$this->ApplyOnlines->category_residental && $this->request->data['ApplyOnlines']['category']!=$this->ApplyOnlines->category_others && empty($this->request->data['ApplyOnlines']['social_consumer'])))
					{
						$this->ApplyOnlines->data['ApplyOnlines']['disclaimer_subsidy'] = 1;
						$this->request->data['ApplyOnlines']['disclaimer_subsidy']		= 1;
						$this->Projects->updateAll(['project_disclaimer_subsidy'=>'1'],['id'=>$project_id]);
					}
					/*elseif(($this->request->data['renewable_attr']=='0' || $this->request->data['renewable_attr']=='1') && !empty($this->request->data['ApplyOnlines']['social_consumer']))
					{
						$this->ApplyOnlines->data['ApplyOnlines']['disclaimer_subsidy'] = 0;
						$this->request->data['ApplyOnlines']['disclaimer_subsidy']		= 0;
						$this->Projects->updateAll(['project_disclaimer_subsidy'=>'0'],['id'=>$project_id]);
					}*/
					if($ApplyOnlinesEntity->query_sent==0)
					{
						$search_response=json_encode(['discom'=>$this->request->data['ApplyOnlines']['discom'],'consumer_no'=>$this->request->data['ApplyOnlines']['consumer_no'],'discom_name'=>$this->request->data['ApplyOnlines']['discom_name']]);
					}
					$ApplyOnlinesEntity								= $this->ApplyOnlines->patchEntity($ApplyOnlineGet,$this->request->data,['validate'=>'tab2']);
					if($ApplyOnlinesEntity->query_sent==0)
					{
							//$ApplyOnlinesEntity->api_response = $search_response;
							$ApplyOnlinesEntity->mobile       = $ApplyOnlinesEntity->consumer_mobile;
							$ApplyOnlinesEntity->email        = $ApplyOnlinesEntity->consumer_email;
					}
					if($allocatedCategory==3)
					{
						$ApplyOnlinesEntity->disclaimer_subsidy 	= '1';
					}

					$this->ApplyOnlines->data_entity 				= $ApplyOnlinesEntity;

					if(isset($this->request->data['renewable_attr']))
					{
						$ApplyOnlinesOthersEntity->renewable_attr 	= $this->request->data['renewable_attr'];
					}
					if(isset($this->request->data['ApplyOnlines']['tariff']))
					{
						$ApplyOnlinesOthersEntity->tariff 			= $this->request->data['ApplyOnlines']['tariff'];
					}
					if(isset($this->request->data['ApplyOnlines']['pv_dc_capacity']))
					{
						$ApplyOnlinesOthersEntity->pv_dc_capacity 	= $this->request->data['ApplyOnlines']['pv_dc_capacity'];
					}
					if(isset($this->request->data['ApplyOnlines']['existing_ac_capacity']))
					{
						$ApplyOnlinesOthersEntity->existing_ac_capacity = $this->request->data['ApplyOnlines']['existing_ac_capacity'];
					}
					if(isset($this->request->data['renewable_rec']))
					{
						if($ApplyOnlinesOthersEntity->renewable_attr == 1)
						{
							$this->request->data['renewable_rec'] = NULL;
						}

						$ApplyOnlinesOthersEntity->renewable_rec 	= $this->request->data['renewable_rec'];
					}
					if(isset($this->request->data['ApplyOnlines']['msme']))
					{
						$ApplyOnlinesOthersEntity->msme 			= $this->request->data['ApplyOnlines']['msme'];
					}
					if(isset($this->request->data['ApplyOnlines']['msme_category']))
					{
						$ApplyOnlinesOthersEntity->msme_category 	= $this->request->data['ApplyOnlines']['msme_category'];
					}
					if(isset($this->request->data['ApplyOnlines']['contract_load_more']))
					{
						$ApplyOnlinesOthersEntity->contract_load_more 	= (isset($this->request->data['ApplyOnlines']['msme']) && $this->request->data['ApplyOnlines']['msme'] == 1) ? $this->request->data['ApplyOnlines']['contract_load_more'] : 0;
					}
					if(isset($this->request->data['ApplyOnlines']['type_of_applicant']))
					{
						$ApplyOnlinesOthersEntity->type_of_applicant 	= $this->request->data['ApplyOnlines']['type_of_applicant'];
					}
					if(isset($this->request->data['ApplyOnlines']['applicant_others']))
					{
						$ApplyOnlinesOthersEntity->applicant_others 		= $this->request->data['ApplyOnlines']['applicant_others'];
					}
					if(isset($this->request->data['ApplyOnlines']['msme_aadhaar_no']))
					{
						$ApplyOnlinesOthersEntity->msme_aadhaar_no 		= $this->request->data['ApplyOnlines']['msme_aadhaar_no'];
					}
					if(isset($this->request->data['ApplyOnlines']['type_authority']))
					{
						$ApplyOnlinesOthersEntity->type_authority 		= $this->request->data['ApplyOnlines']['type_authority'];
					}
					if(isset($this->request->data['ApplyOnlines']['name_authority']))
					{
						$ApplyOnlinesOthersEntity->name_authority 		= $this->request->data['ApplyOnlines']['name_authority'];
					}
					if(isset($this->request->data['ApplyOnlines']['map_installer_id']))
					{
						$ApplyOnlinesOthersEntity->map_installer_id 	= ($this->request->data['ApplyOnlines']['map_installer_id']);
					}
					foreach($arrFieldsMap as $Fkey=>$Fval) {
						if(isset($this->request->data['ApplyOnlines'][$Fval]))
						{
							$ApplyOnlinesOthersEntity->$Fval 			= $this->request->data['ApplyOnlines'][$Fval];
						}
					}

					if(!$ApplyOnlinesEntity->errors()){
						$tab = 'tab_2';
					} else {
						$tab = 'tab_1';
					}

				} else if((isset($this->request->data['tab_3']) && !empty($this->request->data['tab_3'])) || (isset($this->request->data['save_submit']) && !empty($this->request->data['save_submit']))) {
					$ApplyOnlineGet 						= $this->ApplyOnlines->get($this->request->data['ApplyOnlines']['id']);
					if(CAPTCHA_DISPLAY == 1) {
						$this->request->data['ApplyOnlines']['g-recaptcha-response'] = $this->request->data['g-recaptcha-response'];
					}
					$ApplyOnlinesEntity						= $this->ApplyOnlines->patchEntity($ApplyOnlineGet,$this->request->data,['validate'=>'tab3']);
					$this->ApplyOnlines->data_entity 		= $ApplyOnlinesEntity;
					$ApplyOnlinesEntity->member_assign_id 	= $ApplyOnlinesEntity->discom_name;

					if (!empty($ApplyOnlinesEntity->discom_name)) {
						$arrDiscom 								= $this->DiscomMaster->GetDiscomHirarchyByID($ApplyOnlinesEntity->discom_name);
						$ApplyOnlinesEntity->area 				= $arrDiscom->area;
						$ApplyOnlinesEntity->circle 			= $arrDiscom->circle;
						$ApplyOnlinesEntity->division 			= $ApplyOnlinesEntity->discom_name;
						$subdiv_details = $this->getdetailsSubdivision($ApplyOnlinesEntity->consumer_no,$ApplyOnlinesEntity->discom,$ApplyOnlinesEntity->project_id,$ApplyOnlinesEntity->id,'',$ApplyOnlinesEntity->division,$ApplyOnlinesEntity->tno,$ApplyOnlinesEntity->category,'additional_capacity');

						$ApplyOnlinesEntity->subdivision 		= key($subdiv_details['subdivision']);
						
						if(isset($subdiv_details['first_name']) && empty($subdiv_details['first_name']))
						{
							if(isset($subdiv_details['response_msg']) && !empty($subdiv_details['response_msg'])) {
								$this->Flash->error($subdiv_details['response_msg']);
							} else {
								$this->Flash->error('Incorrect Consumer number or T-no');
							}
							return $this->redirect('add-additional-capacity/'.encode($ApplyOnlinesEntity->id));
						}
						$this->ApplyOnlinesOthers->updateAll(array('existing_capacity'=>$subdiv_details['installed_capacity'],'is_enhancement'=>1),array('application_id'=>$ApplyOnlinesEntity->id));		

						/** APPLICATION SOLAR TYPE FOR VENDOR API */
						$SOLAR_TYPE_FLAG = $this->SolarTypeLog->getSolarTypeFlag($subdiv_details['installed_capacity']);
						$this->SolarTypeLog->SaveOrUpdateSolarType($ApplyOnlinesEntity->id,$SOLAR_TYPE_FLAG);
						/** APPLICATION SOLAR TYPE FOR VENDOR API */
					}
					if($ApplyOnlinesEntity->govt_agency == 1 && GOVERMENT_AGENCY==1)
					{
						$ApplyOnlinesEntity->disCom_application_fee = Configure::read('APPLY_AMOUNT_GOVERNMENT');
						$tax_applicable = Configure::read('APPLY_AMOUNT_GOV_TAX');
					}
					elseif($ApplyOnlinesEntity->category == $this->ApplyOnlines->category_residental && ($ApplyOnlinesEntity->social_consumer==0 || SOCIAL_SECTOR_PAYMENT==0))
					{
						$ApplyOnlinesEntity->disCom_application_fee = Configure::read('APPLY_AMOUNT_RESIDENTIAL');
						$tax_applicable = 0;
					}
					else
					{
						$ApplyOnlinesEntity->disCom_application_fee = Configure::read('APPLY_AMOUNT_NON_GOVERNMENT');
						if($ApplyOnlinesEntity->pv_capacity > 1000) {
							$ApplyOnlinesEntity->disCom_application_fee = floatval(PRICE_PER_KW_GT1MW) * floatval($ApplyOnlinesEntity->pv_capacity);
						}
						$tax_applicable = Configure::read('APPLY_AMOUNT_NON_GOV_TAX');
					}
					$amt_tax_percent 							= Configure::read('APPLY_TAX_PERCENT');
					$ApplyOnlinesEntity->jreda_processing_fee 	= $tax_applicable;
					if($amt_tax_percent=='%')
					{
						$ApplyOnlinesEntity->jreda_processing_fee = ($ApplyOnlinesEntity->disCom_application_fee*$tax_applicable)/100;
					}
					if(isset($this->request->data['save_submit']) && !empty($this->request->data['save_submit']))
					{
						$approval=$this->ApplyOnlineApprovals->Approvalstage($ApplyOnlinesEntity->id);
						if($ApplyOnlinesEntity->disclaimer_subsidy == 1) {
							$allocatedCategory 	= 3;
						}
						$applyOnline 			= $this->ApplyOnlines->find();
						$total_application 		= $applyOnline->select(['total_pvcapacity' => $applyOnline->func()->sum('pv_capacity')])->where(array('installer_id'=>$ApplyOnlinesEntity->installer_id,'application_status not in'=>array($this->ApplyOnlineApprovals->WAITING_LIST)))->first();
						$installerCapacityTotal = $total_application->total_pvcapacity;

						$availableCapacityData 	= $this->InstallerCategoryMapping->find('all',['fields'=>['installer_category.capacity'],'join'=>[['table'=>'installer_category','type'=>'left','conditions'=>'InstallerCategoryMapping.category_id = installer_category.id']],'conditions'=>['InstallerCategoryMapping.installer_id'=>$ApplyOnlinesEntity->installer_id]])->toArray();

						if(!empty($availableCapacityData) && $installerCapacityTotal>$availableCapacityData[0]['installer_category']['capacity'] && $allocatedCategory!=3)
						{
							$ApplyOnlinesEntity->application_status = $this->ApplyOnlineApprovals->WAITING_LIST;
						}
						else if(!in_array(29,$approval))
						{
							$ApplyOnlinesEntity->application_status = $this->ApplyOnlineApprovals->APPLICATION_GENERATE_OTP;
						}
						else
						{
							$approval = $this->ApplyOnlineApprovals->find('all',array('conditions'=>array('application_id'=>$ApplyOnlinesEntity->id)))->last();
							$ApplyOnlinesEntity->application_status = $approval->stage;
						}
					}
					if((empty($ApplyOnlinesEntity->errors()) && isset($this->request->data['submit_captcha'])) && $this->captchaValidation() != 0) {
						$tab = 'tab_3';
					} else {
						$tab = 'tab_2';
					}
				}
				if(isset($ApplyOnlinesEntity->installer_id) && !empty($ApplyOnlinesEntity->installer_id)) {
					$customersData 	= $this->$customerTable->find('all',array('conditions'=>array('installer_id'=>$ApplyOnlinesEntity->installer_id)))->first();
				}
				$ApplyOnlinesEntity->customer_id 	= (isset($customersData->id) && !empty($customersData->id)) ? $customersData->id : $customerId;
				if($project_id != '0')
				{
					$ApplyOnlinesEntity->project_id = $project_id;
				}
				$ApplyOnlinesEntity->modified 		= $this->NOW();
				//$ApplyOnlinesEntity->created 		= $this->NOW();
				$this->ApplyOnlines->data_entity 	= $ApplyOnlinesEntity;

				if(!$ApplyOnlinesEntity->errors())
				{
					if(!empty($this->request->data['ApplyOnlines']['aadhar_no_or_pan_card_no']))
					{
						$ApplyOnlinesEntity->aadhar_no_or_pan_card_no	= passencrypt($this->request->data['ApplyOnlines']['aadhar_no_or_pan_card_no']);
					}
					if(!empty($this->request->data['ApplyOnlines']['pan_card_no']))
					{
						$ApplyOnlinesEntity->pan_card_no 				= passencrypt($this->request->data['ApplyOnlines']['pan_card_no']);
					}
					if(!empty($this->request->data['ApplyOnlines']['house_tax_holding_no']))
					{
						$ApplyOnlinesEntity->house_tax_holding_no 		= passencrypt($this->request->data['ApplyOnlines']['house_tax_holding_no']);
					}
					if($this->ApplyOnlines->save($ApplyOnlinesEntity))
					{
						$applyOnlinesOthersDataOrg 	= $this->ApplyOnlinesOthers->find('all',array('conditions'=>array('application_id'=>$id)))->first();

						$id = $ApplyOnlinesEntity->id;
						$ApplyOnlinesOthersEntity->application_id 	= $id;
						$ApplyOnlinesOthersEntity->renewable_rec 	= ($ApplyOnlinesOthersEntity->renewable_attr == 1) ? NULL : $ApplyOnlinesOthersEntity->renewable_rec;
						if(!empty($applyOnlinesOthersDataOrg))
						{
							$ApplyOnlinesOthersEntity->file_company_incorporation = $applyOnlinesOthersDataOrg->file_company_incorporation;
							$ApplyOnlinesOthersEntity->file_board = $applyOnlinesOthersDataOrg->file_board;
							$ApplyOnlinesOthersEntity->upload_certificate = $applyOnlinesOthersDataOrg->upload_certificate;
							$ApplyOnlinesOthersEntity->gerc_certificate 	= $applyOnlinesOthersDataOrg->gerc_certificate;
							$ApplyOnlinesOthersEntity->rec_registration_copy= $applyOnlinesOthersDataOrg->rec_registration_copy;
							$ApplyOnlinesOthersEntity->rec_receipt_copy 	= $applyOnlinesOthersDataOrg->rec_receipt_copy;
							$ApplyOnlinesOthersEntity->rec_power_evaluation = $applyOnlinesOthersDataOrg->rec_power_evaluation;
							$ApplyOnlinesOthersEntity->ppa_doc 				= $applyOnlinesOthersDataOrg->ppa_doc;
							$ApplyOnlinesOthersEntity->agreement_customer 	= $applyOnlinesOthersDataOrg->agreement_customer;
							$ApplyOnlinesOthersEntity->upload_undertaking 	= $applyOnlinesOthersDataOrg->upload_undertaking;
						}
						

						$this->ApplyOnlinesOthers->save($ApplyOnlinesOthersEntity);
						$application_no 			= $this->ApplyOnlines->GenerateApplicationNo($ApplyOnlinesEntity);
						$this->ApplyOnlines->updateAll(array('application_no'=>$application_no),array('id'=>$id));
						if(isset($this->request->data['ApplyOnlines']['common_meter']))
						{
							$this->Projects->updateAll(array('project_common_meter'=>$this->request->data['ApplyOnlines']['common_meter']),array('id'=>$project_id));
						}
						if(isset($this->request->data['renewable_attr']))
						{
							$this->Projects->updateAll(array('project_renewable_attr'=>$this->request->data['renewable_attr']),array('id'=>$project_id));
						}
						if(isset($this->request->data['renewable_rec']))
						{
							if($ApplyOnlinesOthersEntity->renewable_attr == 1)
							{
								$this->request->data['renewable_rec'] = NULL;
							}
							$this->Projects->updateAll(array('project_renewable_rec'=>$this->request->data['renewable_rec']),array('id'=>$project_id));
						}
						$this->set("str_url",encode($id).'/'.encode($project_id));
						$image_path = APPLYONLINE_PATH.$ApplyOnlinesEntity->id.'/';
						if(!file_exists(APPLYONLINE_PATH.$ApplyOnlinesEntity->id)) {
							@mkdir(APPLYONLINE_PATH.$ApplyOnlinesEntity->id, 0777);
						}
						if(!empty($this->request->data['ApplyOnlines']['file_attach_photo_scan_of_aadhar']) && !empty($this->request->data['ApplyOnlines']['file_attach_photo_scan_of_aadhar']['name'])) {
							$db_attach_photo_scan_of_aadhar = $ApplyOnlinesEntity->attach_photo_scan_of_aadhar;
							if(file_exists($image_path.$db_attach_photo_scan_of_aadhar) && !empty($db_attach_photo_scan_of_aadhar)){
								@unlink($image_path.$db_attach_photo_scan_of_aadhar);
							}
							$file_name = $this->file_upload($image_path,$this->request->data['ApplyOnlines']['file_attach_photo_scan_of_aadhar'],false,65,65,$image_path,'aadhar','attach_photo_scan_of_aadhar');
							$this->ApplyOnlines->updateAll(['attach_photo_scan_of_aadhar' => $file_name], ['id' => $ApplyOnlinesEntity->id]);
						}
						if(!empty($this->request->data['ApplyOnlines']['file_attach_recent_bill']) && !empty($this->request->data['ApplyOnlines']['file_attach_recent_bill']['name'])) {
							$db_attach_recent_bill = $ApplyOnlinesEntity->attach_recent_bill;
							if(file_exists($image_path.$db_attach_recent_bill) && !empty($db_attach_recent_bill)){
								@unlink($image_path.$db_attach_recent_bill);
							}
							$file_name = $this->file_upload($image_path,$this->request->data['ApplyOnlines']['file_attach_recent_bill'],false,65,65,$image_path,'recent','attach_recent_bill');
							$this->ApplyOnlines->updateAll(['attach_recent_bill' => $file_name], ['id' => $ApplyOnlinesEntity->id]);
						}
						if(!empty($this->request->data['ApplyOnlines']['file_attach_latest_receipt']) && !empty($this->request->data['ApplyOnlines']['file_attach_latest_receipt']['name']) ) {
							$db_attach_recent_bill = $ApplyOnlinesEntity->attach_latest_receipt;
							if(file_exists($image_path.$db_attach_recent_bill) && !empty($db_attach_recent_bill)){
								@unlink($image_path.$db_attach_recent_bill);
							}
							$file_name = $this->file_upload($image_path,$this->request->data['ApplyOnlines']['file_attach_latest_receipt'],false,65,65,$image_path,'tax_receipt_','attach_latest_receipt');
							$this->ApplyOnlines->updateAll(['attach_latest_receipt' => $file_name], ['id' => $ApplyOnlinesEntity->id]);
						}
						if(!empty($this->request->data['ApplyOnlines']['file_attach_pan_card_scan']) && !empty($this->request->data['ApplyOnlines']['file_attach_pan_card_scan']['name']) ) {
							$db_attach_pan_card_scan = $ApplyOnlinesEntity->attach_pan_card_scan;
							if(file_exists($image_path.$db_attach_pan_card_scan) && !empty($db_attach_pan_card_scan)){
								@unlink($image_path.$db_attach_pan_card_scan);
							}
							$file_name = $this->file_upload($image_path,$this->request->data['ApplyOnlines']['file_attach_pan_card_scan'],false,65,65,$image_path,'pan_','attach_pan_card_scan');
							$this->ApplyOnlines->updateAll(['attach_pan_card_scan' => $file_name], ['id' => $ApplyOnlinesEntity->id]);
						}
						if(!empty($this->request->data['file_company_incorporation']) && !empty($this->request->data['file_company_incorporation']['name'])) {
							$db_attach_incorporation = $ApplyOnlinesOthersEntity->file_company_incorporation;

							if(file_exists($image_path.$db_attach_incorporation) && !empty($db_attach_incorporation)){
								@unlink($image_path.$db_attach_incorporation);
							}
							$file_name = $this->file_upload($image_path,$this->request->data['file_company_incorporation'],false,'','','','incop_','file_company_incorporation');

							$this->ApplyOnlinesOthers->updateAll(['file_company_incorporation' => $file_name], ['application_id' => $ApplyOnlinesEntity->id]);
						}

						if(!empty($this->request->data['file_board']) && !empty($this->request->data['file_board']['name'])) {
							$db_attach_board = $ApplyOnlinesOthersEntity->file_board;
							if(file_exists($image_path.$db_attach_board) && !empty($db_attach_board)){
								@unlink($image_path.$db_attach_board);
							}
							$file_name = $this->file_upload($image_path,$this->request->data['file_board'],false,'','','','board_','file_board');
							$this->ApplyOnlinesOthers->updateAll(['file_board' => $file_name], ['application_id' => $ApplyOnlinesEntity->id]);

						}
						if(!empty($this->request->data['ApplyOnlines']['file_upload_certificate']) && !empty($this->request->data['ApplyOnlines']['file_upload_certificate']['name'])) {
							$db_attach_board = $ApplyOnlinesOthersEntity->upload_certificate;
							if(file_exists($image_path.$db_attach_board) && !empty($db_attach_board)){
								@unlink($image_path.$db_attach_board);
							}
							$file_name = $this->file_upload($image_path,$this->request->data['ApplyOnlines']['file_upload_certificate'],false,'','','','upcert_','upload_certificate');
							$this->ApplyOnlinesOthers->updateAll(['upload_certificate' => $file_name], ['application_id' => $ApplyOnlinesEntity->id]);

						}
						if(!empty($this->request->data['ApplyOnlines']['file_gerc_certificate']) && !empty($this->request->data['ApplyOnlines']['file_gerc_certificate']['name'])) {
							$db_attach_board = $ApplyOnlinesOthersEntity->gerc_certificate;
							if(file_exists($image_path.$db_attach_board) && !empty($db_attach_board)){
								@unlink($image_path.$db_attach_board);
							}
							$file_name = $this->file_upload($image_path,$this->request->data['ApplyOnlines']['file_gerc_certificate'],false,'','','','gerc_','gerc_certificate');
							$this->ApplyOnlinesOthers->updateAll(['gerc_certificate' => $file_name], ['application_id' => $ApplyOnlinesEntity->id]);
						}
						if(!empty($this->request->data['ApplyOnlines']['file_rec_registration_copy']) && !empty($this->request->data['ApplyOnlines']['file_rec_registration_copy']['name'])) {
							$db_attach_board = $ApplyOnlinesOthersEntity->rec_registration_copy;
							if(file_exists($image_path.$db_attach_board) && !empty($db_attach_board)){
								@unlink($image_path.$db_attach_board);
							}
							$file_name = $this->file_upload($image_path,$this->request->data['ApplyOnlines']['file_rec_registration_copy'],false,'','','','rec_re_c_','rec_registration_copy');
							$this->ApplyOnlinesOthers->updateAll(['rec_registration_copy' => $file_name], ['application_id' => $ApplyOnlinesEntity->id]);
						}
						if(!empty($this->request->data['ApplyOnlines']['file_rec_receipt_copy']) && !empty($this->request->data['ApplyOnlines']['file_rec_receipt_copy']['name'])) {
							$db_attach_board = $ApplyOnlinesOthersEntity->rec_receipt_copy;
							if(file_exists($image_path.$db_attach_board) && !empty($db_attach_board)){
								@unlink($image_path.$db_attach_board);
							}
							$file_name = $this->file_upload($image_path,$this->request->data['ApplyOnlines']['file_rec_receipt_copy'],false,'','','','rec_receipt_','rec_receipt_copy');
							$this->ApplyOnlinesOthers->updateAll(['rec_receipt_copy' => $file_name], ['application_id' => $ApplyOnlinesEntity->id]);
						}
						if(!empty($this->request->data['ApplyOnlines']['file_rec_power_evaluation']) && !empty($this->request->data['ApplyOnlines']['file_rec_power_evaluation']['name'])) {
							$db_attach_board = $ApplyOnlinesOthersEntity->rec_power_evaluation;
							if(file_exists($image_path.$db_attach_board) && !empty($db_attach_board)){
								@unlink($image_path.$db_attach_board);
							}
							$file_name = $this->file_upload($image_path,$this->request->data['ApplyOnlines']['file_rec_power_evaluation'],false,'','','','rec_p_e_','rec_power_evaluation');
							$this->ApplyOnlinesOthers->updateAll(['rec_power_evaluation' => $file_name], ['application_id' => $ApplyOnlinesEntity->id]);
						}
						if(!empty($this->request->data['ApplyOnlines']['file_ppa_doc']) && !empty($this->request->data['ApplyOnlines']['file_ppa_doc']['name'])) {
							$db_attach_board = $ApplyOnlinesOthersEntity->ppa_doc;
							if(file_exists($image_path.$db_attach_board) && !empty($db_attach_board)){
								@unlink($image_path.$db_attach_board);
							}
							$file_name = $this->file_upload($image_path,$this->request->data['ApplyOnlines']['file_ppa_doc'],false,'','','','ppa_d','ppa_doc');
							$this->ApplyOnlinesOthers->updateAll(['ppa_doc' => $file_name], ['application_id' => $ApplyOnlinesEntity->id]);
						}
						if(!empty($this->request->data['ApplyOnlines']['file_agreement_customer']) && !empty($this->request->data['ApplyOnlines']['file_agreement_customer']['name'])) {
							$db_attach_board = $ApplyOnlinesOthersEntity->agreement_customer;
							if(file_exists($image_path.$db_attach_board) && !empty($db_attach_board)){
								@unlink($image_path.$db_attach_board);
							}

							$file_name = $this->file_upload($image_path,$this->request->data['ApplyOnlines']['file_agreement_customer'],false,'','','','agr_c','agreement_customer');
							$this->ApplyOnlinesOthers->updateAll(['agreement_customer' => $file_name], ['application_id' => $ApplyOnlinesEntity->id]);
						}
						if(!empty($this->request->data['ApplyOnlines']['app_upload_undertaking']) && !empty($this->request->data['ApplyOnlines']['app_upload_undertaking']['name'])) {
							$db_attach_board = $ApplyOnlinesOthersEntity->upload_undertaking;
							if(file_exists($image_path.$db_attach_board) && !empty($db_attach_board)){
								@unlink($image_path.$db_attach_board);
							}
							$file_name = $this->file_upload($image_path,$this->request->data['ApplyOnlines']['app_upload_undertaking'],false,'','','','app_u_under_','upload_undertaking');
							$this->ApplyOnlinesOthers->updateAll(['upload_undertaking' => $file_name], ['application_id' => $ApplyOnlinesEntity->id]);
						}
						if($tab =='tab_3' && isset($this->request->data['save_submit']))
						{
							if(empty($ApplyOnlinesEntity->payment_status))
							{
								$this->ApplyOnlines->updateAll(['payment_status' => '0'], ['id' => $ApplyOnlinesEntity->id]);
							}
							$approval=$this->ApplyOnlineApprovals->Approvalstage($ApplyOnlinesEntity->id);
							if($ApplyOnlinesEntity->application_status!=$this->ApplyOnlineApprovals->WAITING_LIST && !in_array(29,$approval))
							{
								$sms_mobile 	= $ApplyOnlinesEntity->installer_mobile;
								if($is_installer==true)
								{
									$sms_mobile = $ApplyOnlinesEntity->consumer_mobile;
									/*if(isset($ApplyOnlinesOthersEntity->map_installer_id) && !empty($ApplyOnlinesOthersEntity->map_installer_id))
									{
										$dev_fetchData 		= $this->Developers->find("all",['conditions'=>['id'=>$ApplyOnlinesOthersEntity->map_installer_id]])->first();
										if(!empty($dev_fetchData))
										{
											$sms_mobile		= $dev_fetchData->mobile;
										}
									}*/
								}
								$sms_message = str_replace('##application_no##', $ApplyOnlinesEntity->application_no, OTP_VERIFICATION);
								$this->ApplyOnlines->SendSMSActivationCode($ApplyOnlinesEntity->id,$sms_mobile,$sms_message,'OTP_VERIFICATION');
								if($ApplyOnlinesEntity->govt_agency == 1 && GOVERMENT_AGENCY==1) 
								{
									$sms_mobile 	= $ApplyOnlinesEntity->installer_mobile;
									$this->ApplyOnlines->SendSMSActivationCode($ApplyOnlinesEntity->id,$sms_mobile,$sms_message,'OTP_VERIFICATION');
								}
							}
							$this->Flash->set('Your Application send successful.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
							return $this->redirect('apply-online-list');
						} else if($tab =='tab_2') {
							$this->save_other_doc($this->request->data['ApplyOnlines'],$ApplyOnlinesEntity->id);
							$this->CreateMyProject($ApplyOnlinesEntity->id,true,false);
						} else if($tab =='tab_3') {
							return $this->redirect('add-additional-capacity/'.encode($ApplyOnlinesEntity->id));
						}
					}
				}
			} else if(isset($this->request->data['previous_2']) && !empty($this->request->data['previous_2'])) {
				$tab = '';
			} else if(isset($this->request->data['previous_3']) && !empty($this->request->data['previous_3'])) {
				$tab = 'tab_1';
			}
			if(!empty($id)){
				$ApplyonlinDocsList = $this->ApplyonlinDocs->find('all',['conditions'=>['application_id'=>$id,'doc_type'=>'others']])->toArray();
				$Applyonlinprofile  = $this->ApplyonlinDocs->find('all',['conditions'=>['application_id'=>$id,'doc_type'=>'profile']])->first();
			}
				if(!empty($this->Session->read('Members.member_type')))
			{
				$state 			 = $this->Session->read('Members.state');
				}
			else
			{
				$state 			 = $this->Session->read('Customers.state');
			}
			if(isset($ApplyOnlinesEntity)){
				if(!empty($ApplyOnlinesEntity->apply_state)) {
					$state = $ApplyOnlinesEntity->apply_state;
				}else{
					$ApplyOnlinesEntity->apply_state = $state;
					if(!empty($project_data))
					{
						if($project_data->state != '')
						{
							$state_list 					= $this->States->find("all",['conditions'=>['statename'=>$project_data->state]])->first();
							$ApplyOnlinesEntity->apply_state=  $state_list->id;
							$state 							= $ApplyOnlinesEntity->apply_state;
						}
					}
			}
				if(isset($ApplyOnlinesOthersEntity->tariff)) {
					$ApplyOnlinesEntity->tariff 			= $ApplyOnlinesOthersEntity->tariff;
				}
				if(isset($ApplyOnlinesOthersEntity->pv_dc_capacity) && empty($ApplyOnlinesEntity->errors())) {
					$ApplyOnlinesEntity->pv_dc_capacity 			= $ApplyOnlinesOthersEntity->pv_dc_capacity;
				}
				if(isset($ApplyOnlinesOthersEntity->existing_ac_capacity) && empty($ApplyOnlinesEntity->errors())) {
					$ApplyOnlinesEntity->existing_ac_capacity 		= $ApplyOnlinesOthersEntity->existing_ac_capacity;
				}
				if(isset($ApplyOnlinesOthersEntity->msme)) {
					$ApplyOnlinesEntity->msme 						= $ApplyOnlinesOthersEntity->msme;
				}
				if(!empty($ApplyOnlinesOthersEntity->msme_category)) {
					$ApplyOnlinesEntity->msme_category 				= $ApplyOnlinesOthersEntity->msme_category;
				}
				if(isset($ApplyOnlinesOthersEntity->contract_load_more)) {
					$ApplyOnlinesEntity->contract_load_more 		= (isset($ApplyOnlinesEntity->msme) && $ApplyOnlinesEntity->msme==1) ? $ApplyOnlinesOthersEntity->contract_load_more : 0;
				}
				if(!empty($ApplyOnlinesOthersEntity->type_of_applicant)) {
					$ApplyOnlinesEntity->type_of_applicant 			= $ApplyOnlinesOthersEntity->type_of_applicant;
				}
				if(!empty($ApplyOnlinesOthersEntity->applicant_others)) {
					$ApplyOnlinesEntity->applicant_others 			= $ApplyOnlinesOthersEntity->applicant_others;
				}
				if(!empty($ApplyOnlinesOthersEntity->msme_aadhaar_no)) {
					$ApplyOnlinesEntity->msme_aadhaar_no 			= $ApplyOnlinesOthersEntity->msme_aadhaar_no;
				}
				if(!empty($ApplyOnlinesOthersEntity->type_authority)) {
					$ApplyOnlinesEntity->type_authority 			= $ApplyOnlinesOthersEntity->type_authority;
				}
				if(!empty($ApplyOnlinesOthersEntity->name_authority)) {
					$ApplyOnlinesEntity->name_authority 			= $ApplyOnlinesOthersEntity->name_authority;
				}
				if(isset($ApplyOnlinesOthersEntity->map_installer_id) &&  empty($ApplyOnlinesEntity->errors())) {
					$ApplyOnlinesEntity->map_installer_id 			= ($ApplyOnlinesOthersEntity->map_installer_id);
				}
				$arrErrors=$ApplyOnlinesEntity->errors();
				foreach($arrFieldsMap as $Fkey=>$Fval) {
					if(isset($ApplyOnlinesOthersEntity->$Fval) && !isset($arrErrors[$Fval])) {
						$ApplyOnlinesEntity->$Fval 					= $ApplyOnlinesOthersEntity->$Fval;
					}
				}
				if(!empty($ApplyOnlinesEntity->aadhar_no_or_pan_card_no)) {
					$ApplyOnlinesEntity->aadhar_no_or_pan_card_no 	= passdecrypt($ApplyOnlinesEntity->aadhar_no_or_pan_card_no);
				}
				if(!empty($ApplyOnlinesEntity->pan_card_no)) {
					$ApplyOnlinesEntity->pan_card_no 				= passdecrypt($ApplyOnlinesEntity->pan_card_no);
				}
				if(!empty($ApplyOnlinesEntity->house_tax_holding_no)) {
					$ApplyOnlinesEntity->house_tax_holding_no 		= passdecrypt($ApplyOnlinesEntity->house_tax_holding_no);
				}
				if(!empty($project_data) && $project_data->customer_type !='' && $project_data->customer_type !='NULL' && empty($ApplyOnlinesEntity->category) && empty($ApplyOnlinesEntity->errors()))
				{
					$ApplyOnlinesEntity->category = $project_data->customer_type;
				}
				if(empty($ApplyOnlinesEntity->social_consumer) && !empty($project_data) && empty($ApplyOnlinesEntity->errors()))
				{
					$ApplyOnlinesEntity->social_consumer = $project_data->project_social_consumer;
				}
				if(empty($ApplyOnlinesEntity->common_meter) && !empty($project_data))
				{
					$ApplyOnlinesEntity->common_meter 	= $project_data->project_common_meter;
				}
				if(empty($ApplyOnlinesEntity->disclaimer_subsidy) && !empty($project_data))
				{
					$ApplyOnlinesEntity->disclaimer_subsidy = $project_data->project_disclaimer_subsidy;
				}
				if(empty($ApplyOnlinesEntity->renewable_attr) && !empty($project_data))
				{
					$ApplyOnlinesEntity->renewable_attr = $project_data->project_renewable_attr;
				}
				if(empty($ApplyOnlinesEntity->renewable_rec) && !empty($project_data))
				{
					if($project_data->project_renewable_attr == 1)
					{
						$project_data->project_renewable_rec = NULL;
					}
					$ApplyOnlinesEntity->renewable_rec 	= $project_data->project_renewable_rec;
				}
				if(!empty($project_data) && $project_data->address !='' && $ApplyOnlinesEntity->address1=='')
				{
					$ApplyOnlinesEntity->address1 = $project_data->address;
				}
				if(!empty($project_data) && $project_data->city !='' && $ApplyOnlinesEntity->city=='')
				{
					$ApplyOnlinesEntity->city = $project_data->city;
				}
				if(!empty($project_data) && $project_data->state !='' && $ApplyOnlinesEntity->state=='')
				{
					$ApplyOnlinesEntity->state 	= $project_data->state;
				}
				if(!empty($project_data) && $project_data->pincode !='' && $ApplyOnlinesEntity->pincode=='')
				{
					$ApplyOnlinesEntity->pincode = $project_data->pincode;
				}
				$arr_customer_details 		= $this->$customerTable->find('all',array('conditions'=>array('id'=>$customerId)))->first();
				if(($ApplyOnlinesEntity->apply_state!='4' && strtolower($ApplyOnlinesEntity->apply_state)!='gujarat')) {
					if($ApplyOnlinesEntity->mobile == '')
					{
						$ApplyOnlinesEntity->mobile = $arr_customer_details->mobile;
					}
					if($ApplyOnlinesEntity->email == '')
					{
						$ApplyOnlinesEntity->email = $arr_customer_details->email;
					}
				}

				if($ApplyOnlinesEntity->landline_no == '' && !empty($arr_customer_details->landline))
				{
					$ApplyOnlinesEntity->landline_no = $arr_customer_details->landline;
				}
				if(!empty($project_data) && $project_data->area !='' && empty($ApplyOnlinesEntity->roof_of_proposed))
				{
					$ApplyOnlinesEntity->roof_of_proposed = $project_data->area;
				}
				if(!empty($project_data) && $project_data->avg_monthly_bill !='' && empty($ApplyOnlinesEntity->bill))
				{
					$ApplyOnlinesEntity->bill = $project_data->avg_monthly_bill;
				}
				if(!empty($project_data) && $project_data->estimated_kwh_year !='' && empty($ApplyOnlinesEntity->energy_con))
				{
					$ApplyOnlinesEntity->energy_con = $project_data->estimated_kwh_year;
				}
				$customer_details 	= $this->$customerTable->find('all',array('conditions'=>array('id'=>$this->Session->read('Customers.id'))))->first();
				$installer_details 	= $this->$installerTable->find('all',array('conditions'=>array('id'=>$ApplyOnlinesEntity->installer_id)))->first();
				$assign_slots           = array();
				if(isset($customer_details->installer_id) && !empty($customer_details->installer_id))
				{
					$arr_condition      = array("installer_id" => $customer_details->installer_id);
					$InstallerList      = TableRegistry::get('InstallerCategoryMapping');
					$arr_result         = $InstallerList->find('all',array('conditions'=>$arr_condition))->first();
					$installer_category = isset($arr_result->category_id) ? $arr_result->category_id : 0;
					if($installer_category == '2' && $create_project ==1 && empty($project_errors)){
						//return $this->redirect('apply-online-list');
						$quota_first_page = $this->Projects->checked_total_capacity_installer($customer_details->installer_id);
						if($quota_first_page!==true)
						{
							return $this->redirect('apply-online-list');
						}
					}
					if(!empty($arr_result))
					{
						$assign_slots 	= $this->ApplyOnlines->assign_slot_array($arr_result['allowed_bands']);
					}
				}
				if(($ApplyOnlinesEntity->apply_state=='4' || strtolower($ApplyOnlinesEntity->apply_state)=='gujarat') && $is_installer==false)
				{
					if($ApplyOnlinesEntity->consumer_email=='' )
					{
						$ApplyOnlinesEntity->consumer_email 	= $this->Session->read('Customers.email');
					}
					if($ApplyOnlinesEntity->consumer_mobile=='' )
					{
						$ApplyOnlinesEntity->consumer_mobile 	= $customer_details->mobile;
					}
					if($ApplyOnlinesEntity->installer_email=='' && !empty($installer_details))
					{
						$ApplyOnlinesEntity->installer_email 	= $installer_details->email;
						$this->request->data['ApplyOnlines']['installer_email']= $installer_details->email;
					}
					if($ApplyOnlinesEntity->installer_mobile==''  && !empty($installer_details))
					{
						$ApplyOnlinesEntity->installer_mobile 	= $installer_details->mobile;
						$this->request->data['ApplyOnlines']['installer_mobile'] 	= $installer_details->mobile;
					}
				}
				elseif($ApplyOnlinesEntity->apply_state=='4' && $is_installer==true && !isset($this->request->data['tab_2']))
				{
					$installer_details 	= $this->$installerTable->find('all',array('conditions'=>array('id'=>$customer_details->installer_id)))->first();
					if($ApplyOnlinesEntity->installer_email=='')
					{
						$ApplyOnlinesEntity->installer_email 	= $installer_details->email;
					}
					if($ApplyOnlinesEntity->installer_mobile=='')
					{
						$ApplyOnlinesEntity->installer_mobile 	= $installer_details->mobile;
					}

				}
			}
			$installers_list = array();

			if(isset($state) && !empty($state)) {
				$state_list  = $this->States->find('all',array(
														'conditions'=>array('id'=>$state)))->first();
				if($project_id>0)
				{
					$installers_list = $this->Installers->find('list',['keyField'=>'id','valueField'=>'installer_name','join'=>[['table'=>'installer_projects','type'=>'inner','conditions'=>'installer_projects.installer_id = Installers.id']],'conditions'=>['installer_projects.project_id'=>$project_id,'Installers.stateflg'=>$this->ApplyOnlines->gujarat_st_id]])->toArray();
				}
				if(empty($installers_list))
				{
					$installers_list = $this->Installers->find('list',['keyField'=>'id','valueField'=>'installer_name','join'=>[['table'=>'states','type'=>'inner','conditions'=>'states.id = Installers.stateflg']],'conditions'=>['Installers.stateflg'=>$this->ApplyOnlines->gujarat_st_id]])->toArray();
				}
			} else {
				$installers_list = $this->Installers->find('list',['keyField'=>'id','valueField'=>'installer_name'])->toArray();
			}

			$state_list	 		= $this->States->find("list",['keyField'=>'id','valueField'=>'statename','conditions'=>$condition_state_list,'order'=>['statename'=>'ASC']]);
			$discom_list 		= $this->DiscomMaster->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['DiscomMaster.state_id'=>$state,'DiscomMaster.type'=>3,'status'=>'1']]);

			$discom_arr = array();
			$discoms 	= $this->BranchMasters->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['BranchMasters.status'=>'1','BranchMasters.parent_id'=>'0','BranchMasters.state'=>$state]])->toArray();
			if(!empty($discoms)) {
				foreach($discoms as $id=>$title) {
					$discom_arr[$id] = $title;
				}
			}
			$applyonlineapproval=$this->ApplyOnlineApprovals->find('all',array('conditions'=>array('application_id'=>$ApplyOnlinesEntity->id,'stage'=>'1')))->first();
			$enabled_fields =array();
			$enabled_fields=['next','installer_id','add1','add2','district','city','state','pincode','save_submit'];
			if(isset($ApplyOnlinesEntity->id) && $ApplyOnlinesEntity->id != "" && !empty($applyonlineapproval) && (!empty($this->Session->read('Members.member_type'))) && ($this->Session->read('Members.member_type') == $this->ApplyOnlines->JREDA)){
				//$this->set("enabled_fields",$enabled_fields);
			}
			if($ApplyOnlinesEntity->errors())
			{
				$application_id_error = '0';
				if(!empty($ApplyOnlinesEntity->id))
				{
					$application_id_error = $ApplyOnlinesEntity->id;
					$applyOnlinesOthersDataOrg 	= $this->ApplyOnlinesOthers->find('all',array('conditions'=>array('application_id'=>$ApplyOnlinesEntity->id)))->first();
					if(!empty($applyOnlinesOthersDataOrg))
					{
						$ApplyOnlinesOthersEntity->file_company_incorporation 	= $applyOnlinesOthersDataOrg->file_company_incorporation;
						$ApplyOnlinesOthersEntity->file_board	 				= $applyOnlinesOthersDataOrg->file_board;
						$ApplyOnlinesOthersEntity->upload_certificate 			= $applyOnlinesOthersDataOrg->upload_certificate;
					}
					$this->ApplyOnlines->saveErrorLog($application_id_error,$ApplyOnlinesEntity->errors());
				}
			}
			$output_quota = true;
			if($create_project ==1){
				//$output_quota = $this->ApplyOnlines->checked_total_capacity($installer_id);
			}

			$applyonlineapproval 	= $this->ApplyOnlineApprovals->find('all',array('conditions'=>array('application_id'=>$ApplyOnlinesEntity->id,'stage'=>'1')))->first();
			$type_modules           = $this->Installation->TYPE_MODULES ;
			$type_inverters         = $this->Installation->TYPE_INVERTERS ;
			$make_inverters         = $this->Installation->MAKE_INVERTERS ;
			$customer_state 		= $this->Session->read('Customers.state');
			$developer_list 		= $this->DeveloperCustomers->find("all",array("fields"=>["id","title"=>"CONCAT(name, ' ', developer_registration_no)"],'conditions'=>array('status'=>1)))->toArray();
			$arrDeveloper 			= array();
			if(!empty($developer_list)) {
				foreach($developer_list as $developer) {
					$arrDeveloper[encode($developer->id)]	= $developer->title;
				}
			}

			if($ses_login_type == 'developer') {
				$installers_list = $this->Installers->find('list',['keyField'=>'id','valueField'=>'installer_name','conditions'=>array('status'=>1)])->toArray();
			}

			$this->set("customer_state",$customer_state);
			$this->set('discom_arr',$discom_arr);
			$this->set('ApplyonlinDocsList',$ApplyonlinDocsList);
			$this->set('customers_name',$this->Session->read('Customers.name'));
			$this->set('state_list',$state_list);
			$this->set('installer_list',$installers_list);
			$this->set('discom_list',$discom_list);
			$this->set('customer_name_prifix',$this->Customers->customer_name_prifix);
			$this->set('customer_type_list',$this->Parameters->GetParameterList(3));
			$this->set('ApplyOnlines',$ApplyOnlinesEntity);
			$this->set('pageTitle','Apply Online');
			$this->set('ApplyOnlineErrors',$ApplyOnlinesEntity->errors());
			$this->set("pv_ca_gt50",Configure::read('PV_CAPACITY_GT50'));
			$this->set("pv_ca_lt50",Configure::read('PV_CAPACITY_LT50'));
			$this->set('project_id',$project_id);
			$this->set("type_modules",$type_modules);
			$this->set("type_inverters",$type_inverters);
			$this->set("make_inverters",$make_inverters);
			$this->set("execution_data",$execution_data);
			$this->set("uplaod_image_limit",(Configure::read('UPLOAD_IMAGE_LIMIT')*100));
			$this->set("tab",$tab);
			$this->set("id",$id);
			$this->set("SITE_KEY",Configure::read('SITE_KEY') );
			$this->set('Applyonlinprofile',$Applyonlinprofile);
			$this->set("amt_government",Configure::read('APPLY_AMOUNT_GOVERNMENT'));
			$this->set("amt_non_government",Configure::read('APPLY_AMOUNT_NON_GOVERNMENT'));
			$this->set("amt_residental",Configure::read('APPLY_AMOUNT_RESIDENTIAL'));
			$this->set("amt_gov_tax",Configure::read('APPLY_AMOUNT_GOV_TAX'));
			$this->set("amt_non_gov_tax",Configure::read('APPLY_AMOUNT_NON_GOV_TAX'));
			$this->set("amt_tax_percent",Configure::read('APPLY_TAX_PERCENT'));
			$this->set("applyonlineapproval",$applyonlineapproval);
			//$this->set('projectTypeArr',$this->Parameters->getProjectType());
			$this->set('projectTypeArr',$this->Parameters->GetParameterList(3));
			$this->set('backupTypeArr',$this->Projects->backupTypeArr);
			$this->set('areaTypeArr',$this->Parameters->getAreaType());
			$this->set('create_project',$create_project);
			$this->set('assign_slots',$assign_slots);
			$this->set('ApplyOnlineObj',$this->ApplyOnlines);
			$this->set('quota_msg_disp',$output_quota);
			$this->set('MStatus',$this->ApplyOnlineApprovals);
			$this->set('allocatedCategory',$allocatedCategory);
			$this->set('ProjectsDetails',$project_data);
			$this->set('ApplyOnlinesOthers',$ApplyOnlinesOthersEntity);
			$this->set('Couchdb',$this->Couchdb);
			$this->set('is_installer',$is_installer);
			$this->set('ses_login_type',$ses_login_type);
		} else {
			return $this->redirect('home');
		}
	}
	/**
	*
	* getSubDivisionConsumerCapacity
	*
	* Behaviour : public
	*
	* Parameter : discom
	*
	* @defination : Method is used to get subdivision from consumer number for additonal capacity.
	*
	*/
	public function getSubDivisionConsumerCapacity() {

		$this->autoRender 			= false;
		$consumer_no 				= isset($this->request->data['consumer_no'])?$this->request->data['consumer_no']:0;
		$discom_id 					= isset($this->request->data['discom'])?$this->request->data['discom']:0;
		$application_no 			= isset($this->request->data['id'])?$this->request->data['id']:0;
		$project_id 				= isset($this->request->data['project_id'])?$this->request->data['project_id']:0;
		$division_id 				= isset($this->request->data['division_id'])?$this->request->data['division_id']:0;
		$tno 						= isset($this->request->data['tno'])?$this->request->data['tno']:0;
		$category 					= isset($this->request->data['category'])?$this->request->data['category']:0;
		$action 					= 'additional_capacity';

		$data 						= $this->getdetailsSubdivision($consumer_no,$discom_id,$project_id,$application_no,'',$division_id,$tno,$category,$action);
		$this->ApiToken->SetAPIResponse('msg', 'list of subdivision');
		$this->ApiToken->SetAPIResponse('data', $data);
		$this->ApiToken->SetAPIResponse('type','ok');
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	/**
	 * DeleteApplicationRequest
	 * Behaviour : Public
	 * @defination :Delete Application from admin panel.
	 */
	public function DeleteApplicationRequest()
	{
		$this->autoRender 		= false;
		
		$id 					= (isset($this->request->data['application_id']) ? $this->request->data['application_id'] : 0);
		$delete_request_id 		= (isset($this->request->data['delete_request_id']) ? $this->request->data['delete_request_id'] : 0);
		$reason 				= (isset($this->request->data['reason']) ? $this->request->data['reason'] : 0);
		$consent_not_available 	= (isset($this->request->data['consent_not_available']) ? $this->request->data['consent_not_available'] : 0);
		$consumer_consent_letter= (isset($this->request->data['consumer_consent_letter']) ? $this->request->data['consumer_consent_letter'] : '');
		$vendor_consent_letter 	= (isset($this->request->data['vendor_consent_letter']) ? $this->request->data['vendor_consent_letter'] : '');
		$Request_data 			= $this->ApplicationRequestDelete->find('all',array('conditions'=>array('id'=>$delete_request_id)))->first();

		$success 				= 1;
		if(empty($id)) {
			$ErrorMessage 		= "Invalid Request. Please validate form details.";
			$success 			= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} 
		else if(empty($this->Session->read("Members.id")) && empty($this->Session->read("Customers.id")))
		{
			$ErrorMessage 		= "login";
			$success 			= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} else {
			if(empty($delete_request_id)) {
				if(empty($consent_not_available) && (empty($consumer_consent_letter['name']) || empty($vendor_consent_letter['name']))) {
					$msg 				= empty($consumer_consent_letter['name']) ? 'Consumer ' : 'Installer ';
					$ErrorMessage 		= $msg."Consent Letter is required.";
					$success 			= 0;
				} else if($consent_not_available==1 && (empty($vendor_consent_letter['name']))) {
					$msg 				= 'Installer ';
					$ErrorMessage 		= $msg."Consent Letter is required.";
					$success 			= 0;
				} else if($consent_not_available==2 && (empty($consumer_consent_letter['name']))) {
					$msg 				= 'Consumer ';
					$ErrorMessage 		= $msg."Consent Letter is required.";
					$success 			= 0;
				}
			}
			else {
				
				if(empty($consent_not_available) && (empty($consumer_consent_letter['name']) || empty($vendor_consent_letter['name'])) && (empty($Request_data->consumer_consent_letter) || empty($Request_data->vendor_consent_letter))) {
					$msg 				= empty($consumer_consent_letter['name']) ? 'Consumer ' : 'Insempty($Request_data->vendor_consent_letter)taller ';
					$ErrorMessage 		= $msg."Consent Letter is required.";
					$success 			= 0;
				} else if($consent_not_available==1 && (empty($vendor_consent_letter['name'])) && empty($Request_data->vendor_consent_letter)) {
					$msg 				= 'Installer ';
					$ErrorMessage 		= $msg."Consent Letter is required.";
					$success 			= 0;
				} else if($consent_not_available==2 && (empty($consumer_consent_letter['name'])) && empty($Request_data->consumer_consent_letter)) {
					$msg 				= 'Consumer ';
					$ErrorMessage 		= $msg."Consent Letter is required.";
					$success 			= 0;
				}
			}
		}
			 
		if($success == 1) {
			$encode_id 			= $id;
			$id 				= intval(decode($id));
			if(!empty($this->Session->read("Members.id")))
			{
				$customerId 	= $this->Session->read("Members.id");
				$user_type 		= 1;
			}
			else
			{
				$customerId 	= $this->Session->read("Customers.id");
				$user_type 		= 0;
			}

			$applyOnlinesData 	= $this->ApplyOnlines->viewApplication($id);
			$application_data 	= $this->ApplyOnlines->find('all',array('conditions'=>array('id'=>$id)))->first();
			
			$proj_id = $applyOnlinesData['project_id'];
			if (!empty($applyOnlinesData)) {
				if ($this->request->is('post')) {
					$browser 					   	= isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:"-";
					if(!empty($Request_data)) {
						$requestfetchEntity         = $this->ApplicationRequestDelete->get($delete_request_id);

						$requestEntity              = $this->ApplicationRequestDelete->patchEntity($requestfetchEntity,array());
					} else {
						$requestEntity              = $this->ApplicationRequestDelete->newEntity();
						$requestEntity->created_by 	= $customerId;
						$requestEntity->created 	= $this->NOW();
						$requestEntity->user_type 	= $user_type;
					}
					$requestEntity->application_id  = $id;
					$requestEntity->reason      	= $reason;
					$requestEntity->modified_by 	= $customerId;
					$requestEntity->modified 		= $this->NOW();
					
					if(!empty($consumer_consent_letter['name']))
					{
						$prefix_file 	= 'con_';
						$name 			=$consumer_consent_letter['name'];
						$ext 			= substr(strtolower(strrchr($name, '.')), 1);
						$file_name 		= $prefix_file.date('Ymdhms').rand();

						$uploadPath = APPLYONLINE_PATH.$id.'/';
						if(!file_exists(APPLYONLINE_PATH.$id)) {
							@mkdir(APPLYONLINE_PATH.$id, 0777);
						}
						$file_location 	= WWW_ROOT.$uploadPath.$file_name.'.'.$ext;
						if(move_uploaded_file($consumer_consent_letter['tmp_name'],$file_location))
						{
							$requestEntity->consumer_consent_letter	= $file_name.'.'.$ext;
							if(isset($Request_data->consumer_consent_letter) && !empty($Request_data->consumer_consent_letter))
							{
								$fileconsent 	= APPLYONLINE_PATH.$id."/".$Request_data->consumer_consent_letter;
								if(file_exists($fileconsent)) {
									unlink($fileconsent);
								}
							}
						}
					}
					if(!empty($vendor_consent_letter['name']))
					{
						$prefix_file 	= 'ven_';
						$name 			= $vendor_consent_letter['name'];
						$ext 			= substr(strtolower(strrchr($name, '.')), 1);
						$file_name 		= $prefix_file.date('Ymdhms').rand();

						$uploadPath = APPLYONLINE_PATH.$id.'/';
						if(!file_exists(APPLYONLINE_PATH.$id)) {
							@mkdir(APPLYONLINE_PATH.$id, 0777);
						}
						$file_location 	= WWW_ROOT.$uploadPath.$file_name.'.'.$ext;
						if(move_uploaded_file($vendor_consent_letter['tmp_name'],$file_location))
						{
							$requestEntity->vendor_consent_letter	= $file_name.'.'.$ext;
							if(isset($Request_data->vendor_consent_letter) && !empty($Request_data->vendor_consent_letter))
							{
								$fileconsent 	= APPLYONLINE_PATH.$id."/".$Request_data->vendor_consent_letter;
								if(file_exists($fileconsent)) {
									unlink($fileconsent);
								}
							}
						}
					}
					$requestEntity->consent_not_available 			= $consent_not_available;
					$this->ApplicationRequestDelete->save($requestEntity);
					$ErrorMessage 	= "Delete Application Request Saved Succesfully";
					$success 		= 1;
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate Details.";
				$success 		= 0;
			}
		}
		$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
		$this->ApiToken->SetAPIResponse('success',$success);
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	/**
	 * DeleteApplicationRequestList
	 * Behaviour : Public
	 * @defination :Delete Application from admin panel.
	 */
	public function DeleteApplicationRequestList()
	{
		$this->setMemberArea();
		$member_type 		= $this->Session->read('Members.member_type');
		$member_id 			= $this->Session->read("Members.id");
		$ses_customer_type 	= $this->Session->read('Members.member_type');
		$authority_account 	= $this->Session->read('Members.authority_account');
		$area 				= $this->Session->read('Members.area');
		$circle 			= $this->Session->read('Members.circle');
		$division 			= $this->Session->read('Members.division');
		$subdivision 		= $this->Session->read('Members.subdivision');
		$section 			= $this->Session->read('Members.section');
		$main_branch_id = array();
		if (!empty($member_id) && $member_type == $this->ApplyOnlines->DISCOM)
		{
			$field      	= "area";
			$id         	= $area;
			if (!empty($section)) {
				$field      = "section";
				$id         = $section;
			} else if (!empty($subdivision)) {
				$field      = "subdivision";
				$id         = $subdivision;
			} else if (!empty($division)) {
				$field      = "division";
				$id         = $division;
			} else if (!empty($circle)) {
				$field      = "circle";
				$id         = $circle;
			}
			$main_branch_id = array("field"=>$field,"id"=>$id);
		}
		$is_installer 				= false;
		$ALLOWED_APPROVE_GEDAIDS    = $this->ApplyOnlines->ALLOWED_APPROVE_GEDAIDS;
		$newInstallerRegistration   = ($member_id > 0 && ($authority_account==1 || in_array($member_id,ALLOW_DELETE_APPLICATION_ACCESS))) ? true : false;
		
		if(empty($member_id) || !$newInstallerRegistration) {
			return $this->redirect(URL_HTTP);
		}
		$from_date 				= isset($this->request->data['DateFrom'])?$this->request->data['DateFrom']:'';
		$end_date 				= isset($this->request->data['DateTo'])?$this->request->data['DateTo']:'';
		$application_no 		= isset($this->request->data['application_no'])?$this->request->data['application_no']:'';
		$approval_status 		= isset($this->request->data['approval_status'])?$this->request->data['approval_status']:'';
		$arrRequestList			= array();
		$arrCondition			= array();
		
		//$arrCondition['ApplyOnlines.pcr_submited IS '] 			= NULL;

		$this->SortBy		= "Installers.id";
		$this->Direction	= "DESC";
		$this->intLimit		= PAGE_RECORD_LIMIT;
		$this->CurrentPage  = 1;
		$option 			= array();
		$memberApproved 	= '0';
		
		$memberApproved 	= in_array($member_id, ALLOW_DELETE_APPLICATION_ACCESS) ? '1' : '0';
		
		$option['colName']  = array('id','application_no','created','created_by','status','approved_by','modified','action');
			
		$sortArr 			= array('id'			=> 'ApplicationRequestDelete.id',
									'application_no'=> 'apply_onlines.application_no',
									'created'		=> 'ApplicationRequestDelete.created',
									'created_by'	=> 'ApplicationRequestDelete.created_by',
									'status' 		=> 'ApplicationRequestDelete.status',
									'approved_by'	=> 'ApplicationRequestDelete.approved_by',
									'modified'		=> 'ApplicationRequestDelete.modified');
		$this->SetSortingVars('Installers',$option,$sortArr);

		$option['dt_selector']			='table-example';
		$option['formId']				='formmain';
		$option['url']					= '';
		$option['recordsperpage']		= PAGE_RECORD_LIMIT;
		//$option['allsortable']			= '-1';
		$option['total_records_data']	= 0;
		$option['bPaginate']			= 'true';
		$option['bLengthChange']		= 'false';
		$option['order_by'] 			= "order : [[0,'DESC']]";
		$JqdTablescr 					= $this->JqdTable->create($option);
		$Joins['apply_onlines'] 		= array('table'=>'apply_onlines','type'=>'INNER','conditions'=>'ApplicationRequestDelete.application_id=apply_onlines.id');
		$Joins['customers'] 			= array('table'=>'customers','type'=>'LEFT','conditions'=>'ApplicationRequestDelete.created_by=customers.id');
		$Joins['installers'] 			= array('table'=>'installers','type'=>'LEFT','conditions'=>'apply_onlines.installer_id=installers.id');
		$Joins['members_c'] 				= array('table'=>'members','type'=>'LEFT','conditions'=>'ApplicationRequestDelete.created_by=members_c.id');
		$Joins['members_app'] 			= array('table'=>'members','type'=>'LEFT','conditions'=>'ApplicationRequestDelete.approved_by=members_app.id');
		if ($this->request->is('ajax'))
		{
			$CountFields	= array('ApplicationRequestDelete.id');
			$Fields 		= array('ApplicationRequestDelete.id',
									'apply_onlines.application_no',
									'ApplicationRequestDelete.created',
									'ApplicationRequestDelete.application_id',
									'ApplicationRequestDelete.created_by',
									'ApplicationRequestDelete.status',
									'ApplicationRequestDelete.approved_by',
									'ApplicationRequestDelete.modified',
									'ApplicationRequestDelete.user_type',
									'members_c.name',
									'members_app.name',
									'installers.installer_name',
									);
			
			if ($application_no != '') {
				$arrCondition['apply_onlines.application_no LIKE '] = '%'.$application_no.'%';
			}
			if (!empty($approval_status)) {
				$arrCondition['ApplicationRequestDelete.status'] 	= $approval_status;
			}
			if($approval_status == 0) {
				$arrCondition[0] = ['OR'=>['ApplicationRequestDelete.status IS NULL','ApplicationRequestDelete.status'=>0]];

			}
			if(!empty($main_branch_id)) {
				$arrCondition['apply_onlines.'.$main_branch_id['field']] = $main_branch_id['id'];
			}
			$query_data 	= $this->ApplicationRequestDelete->find('all',array(
																'fields'		=> $Fields,
																'conditions' 	=> $arrCondition,
																'join' 			=> $Joins,
																'order'			=> array($this->SortBy=>$this->Direction),
																'page' 			=> $this->CurrentPage,
																'limit' 		=> $this->intLimit));
			if(!empty($from_date) && !empty($end_date))
			{
				$fields_date  	= "ApplicationRequestDelete.created";
				$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
				$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
				$query_data->where([function ($exp, $q) use ($StartTime, $EndTime,$fields_date) {
					return $exp->between($fields_date, $StartTime, $EndTime);
				}]);
			}
			
			$query_data_count 	= $this->ApplicationRequestDelete->find('all',array('fields'		=> $CountFields,
																		'conditions' 	=> $arrCondition,
																		'join' 			=> $Joins,
															));
			if(!empty($from_date) && !empty($end_date))
			{
				$fields_date  	= "ApplicationRequestDelete.created";
				$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
				$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
				$query_data_count->where([function ($exp, $q) use ($StartTime, $EndTime,$fields_date) {
					return $exp->between($fields_date, $StartTime, $EndTime);
				}]);
			}

			$total_query_records	= $query_data_count->count();
			$start_page 			= isset($this->request->data['start']) ? $this->request->data['start'] : 1;
			$this->paginate['limit']= PAGE_RECORD_LIMIT;
			$this->paginate['page']	= ($start_page/$this->paginate['limit'])+1;
			if(isset($this->request->data['page_no']) && !empty($this->request->data['page_no']))
			{
				$posible_page 				= $total_query_records/$this->paginate['limit'];
				if($posible_page < $this->request->data['page_no']) {
					$this->paginate['page'] = $posible_page;
				} else {
					$this->paginate['page'] = $this->request->data['page_no'];
				}
			}
			else
			{
				$this->paginate['page'] 	= ($start_page/$this->paginate['limit'])+1;
			}


			$arrRequestList	= $this->paginate($query_data);
			$out 			= array();
			$counter 		= 1;
			$page_mul 		= ($this->CurrentPage-1);
			
			foreach($arrRequestList->toArray() as $key=>$val)
			{
				$temparr 	= array();
				foreach($option['colName'] as $key) {
					/*if($key=='id') {
						$temparr[$key]=$counter + ($page_mul * $this->paginate['limit']);
					}
					else*/ 

					if($key=='created') {
						$temparr[$key]	= date('m-d-Y H:i a',strtotime($val->created));
					}
					else if($key == 'application_no') {
						$temparr[$key]	= '<a href="'.URL_HTTP.'view-applyonline/'.encode($val->application_id).'" target="_blank">'.$val->apply_onlines['application_no'].'</a>';
					}
					else if($key=='status') {
						$temparr[$key]=($val->status == 1) ? 'Approved' : (($val->status == 2) ? 'Rejected' : 'Pending');
					}
					else if($key=='approved_by') {
						$temparr[$key]=!empty($val->approved_by) ? $val->members_app['name'] : '-';
					}
					else if($key=='created_by') {
						$temparr[$key]=($val->user_type == 1) ? $val->members_c['name'] : $val->installers['installer_name'];
					}
					else if($key=='modified') {
						$temparr[$key]	= !empty($val->approved_by) ? date('m-d-Y H:i a',strtotime($val->modified)) : '-';
					}
					else if($key=='action') {
						$temparr[$key]	= '';
						if($val->status != 1) {
							$temparr[$key]	= '<a href="javascript:;" class="dropdown-item SubmitRequest approve_Status" data-id="'. encode($val->id) .'"><i class="fa fa-check-square-o" aria-hidden="true"></i> Approve</a>';
						}
						if($val->status == 1) {
							if(isset($member_type) && ($authority_account == 1 || in_array($member_id,ALLOW_DELETE_APPLICATION_ACCESS)) && CAN_DELETE_APPLICATION_MEMBER == 1)
							{
								$approvedApplication 	= $this->ApplicationRequestDelete->findLatestApprovedRequest($val->application_id);
								$approval = $this->ApplyOnlineApprovals->Approvalstage($val->application_id);
								if($approvedApplication == 1 && !in_array($this->ApplyOnlineApprovals->METER_INSTALLATION,$approval)) 
								{
									$temparr[$key]	= '<a href="javascript:;" data-toggle="modal" data-target="#delete_application" class="delete_application dropdown-item" onclick="javascript:setAppID(\''. encode($val->application_id).'\');"><i class="fa fa-trash" aria-hidden="true"></i> Delete Application</a>';
								}
							}
						}
							
						$temparr[$key]		.= '<a href="javascript:;" class="dropdown-item fetch_details" data-id="'. encode($val->id) .'"><i class="fa fa-check-square-o" aria-hidden="true"></i> View Details</a>';
						$temparr['action']	= '	<span class="action-row action-btn">
													<div class="dropdown">
														<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
															Actions <i class="fa fa-chevron-down"></i>
														</button>
														<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">'.$temparr['action'].'</div>
													</div>
												</span>';
					}
					else if (isset($val[$key])) {
						$temparr[$key]	= $val[$key];
					} else {
						$temparr[$key]	= "-";
					}
				}
				$counter++;
				$out[] = $temparr;
			}
			header('Content-type: application/json');
			echo json_encode(array(	"draw" 				=> intval($this->request->data['draw']),
									"recordsTotal"    	=> intval($this->request->params['paging']['ApplicationRequestDelete']['count']),
									"recordsFiltered" 	=> intval($this->request->params['paging']['ApplicationRequestDelete']['count']),
									"data"            	=> $out));
			die;
		}
		
		$REQUEST_STATUS 	= array("0"=>"Pending","1"=>"Approved","2"=>"Rejected");
		$RECEVIED_STATUS 	= array("1"=>"YES","0"=>"NO");
		
		$this->set('arrRequestList',$arrRequestList);
		$this->set('JqdTablescr',$JqdTablescr);
		$this->set('period',$this->period);
		$this->set('limit',$this->intLimit);
		$this->set("CurrentPage",$this->CurrentPage);
		$this->set("SortBy",$this->SortBy);
		$this->set("Direction",$this->Direction);
		$this->set("REQUEST_STATUS",$REQUEST_STATUS);
		$this->set("RECEVIED_STATUS",$RECEVIED_STATUS);
		$this->set("pagetitle",'Delete Application Request');
		$this->set("page_count",0);
		$this->set("memberApproved",$memberApproved);
	}
	/**
	 *
	 * fetchDeleteAppRequest
	 *
	 * Behaviour : public
	 *
	 * @defination : Method is use to fetch installer data.
	 *
	 */
	public function fetchDeleteAppRequest()
	{
		$this->autoRender   = false;
		$response 			= array('status'=>'','id'=>'');
		$insid 				= isset($this->request->data['insid']) ? intval(decode($this->request->data['insid'])) : '';
		$application_id 	= isset($this->request->data['application_id']) ? intval(decode($this->request->data['application_id'])) : '';
		if(!empty($application_id)) {
			$requestData 	= $this->ApplicationRequestDelete->findLatestRequest($application_id);
			if(!empty($requestData)) {
				$insid 		= $requestData->id;
			}
		}
		$ins_fetchData 		= $this->ApplicationRequestDelete->find("all",['conditions'=>['id'=>$insid]])->first();
		if(!empty($ins_fetchData))
		{
			if(!empty($ins_fetchData->consumer_consent_letter)) {
				$uploadPath = APPLYONLINE_PATH.$ins_fetchData->application_id.'/'.$ins_fetchData->consumer_consent_letter;
				if(file_exists($uploadPath)) {
					$file_location 	= APPLYONLINE_URL.$ins_fetchData->application_id.'/'.$ins_fetchData->consumer_consent_letter;
					$ins_fetchData->consumer_consent_letter	= '<a href="'.$file_location.'" target="_blank">View Consent Letter</a>';
				}
			}
			if(!empty($ins_fetchData->vendor_consent_letter)) {
				$uploadPath = APPLYONLINE_PATH.$ins_fetchData->application_id.'/'.$ins_fetchData->vendor_consent_letter;
				if(file_exists($uploadPath)) {
					$file_location 	= APPLYONLINE_URL.$ins_fetchData->application_id.'/'.$ins_fetchData->vendor_consent_letter;
					$ins_fetchData->vendor_consent_letter	= '<a href="'.$file_location.'" target="_blank">View Consent Letter</a>';
				}
			}
			$ins_fetchData->encode_application_id 			= encode($ins_fetchData->application_id);
			$response										= $ins_fetchData;
		}
		echo json_encode(array('type'=>'ok','response'=>$response));
		exit;
	}
	/**
	 *
	 * ApproveDeleteRequest
	 *
	 * Behaviour : public
	 *
	 * @defination : Method is use to approved or rejected status of installer registration.
	 *
	 */
	public function ApproveDeleteRequest()
	{
		$this->autoRender   = false;
		$id                 = (isset($this->request->data['insid']) ? (decode($this->request->data['insid'])) : 0);
		$geda_approval    	= (isset($this->request->data['geda_approval']) ? $this->request->data['geda_approval'] : 0);
		$reject_reason    	= (isset($this->request->data['reject_reason']) ? $this->request->data['reject_reason'] : 0);


		$memberId         	= $this->Session->read("Members.id");
		$member_type 		= $this->Session->read('Members.member_type');
		if(empty($id)) {
			$ErrorMessage   = "Invalid Request. Please validate form details.";
			$success        = 0;
		} else {
			$requestData  	= $this->ApplicationRequestDelete->find("all",['conditions'=>['id'=>$id]])->first();
			$sms_template	= '';
			if (!empty($requestData)) {
				if ($this->request->is('post') || $this->request->is('put')) {
					$arrData['status'] 			= $geda_approval;
					$arrData['reject_reason'] 	= $reject_reason;
					$arrData['approved_by'] 	= $memberId;
					$arrData['modified_by'] 	= $memberId;
					$arrData['modified'] 		= $this->NOW();
					$this->ApplicationRequestDelete->updateAll($arrData,array('id'=>$id));
					$sms_text 					= '';
					$template_applied 			= '';
					$applyOnlinesData 	= $this->ApplyOnlines->viewApplication($requestData->application_id);
					if($geda_approval==1)
					{
						$sms_text 			= str_replace('##application_no##',$applyOnlinesData->application_no, GEDA_APPROVAL);
						$sms_template 		= 'GEDA_APPROVAL';
						$subject 			= "[REG: Application No. ".$applyOnlinesData->application_no."] Delete Application Request Accepted";
						$CUSTOMER_NAME 		= trim($applyOnlinesData->customer_name_prefixed." ".$applyOnlinesData->name_of_consumer_applicant." ".$applyOnlinesData->last_name." ".$applyOnlinesData->third_name);
						$EmailVars 			= array("CUSTOMER_NAME"=>$CUSTOMER_NAME,'application_no'=>$applyOnlinesData->application_no);
						$template_applied 	= 'delete_application_approval';
					}
					else if($geda_approval==2)
					{
						$sms_text 			= str_replace('##application_no##',$applyOnlinesData->application_no, GEDA_REJECTED);
						$sms_template 		= 'GEDA_REJECTED';
						$subject 			= "[REG: Application No. ".$applyOnlinesData->application_no."] Delete Application Request Rejected";
						$CUSTOMER_NAME 		= trim($applyOnlinesData->customer_name_prefixed." ".$applyOnlinesData->name_of_consumer_applicant." ".$applyOnlinesData->last_name." ".$applyOnlinesData->third_name);
						$EmailVars 			= array("CUSTOMER_NAME"=>$CUSTOMER_NAME,'application_no'=>$applyOnlinesData->application_no,"REASON_REJECTION"=>$arrData['reject_reason']);
						$template_applied 	= 'delete_application_rejection';
					}
					if($sms_text!='')
					{
						if(!empty($applyOnlinesData->consumer_mobile))
						{
							$this->ApplyOnlines->sendSMS($id,$applyOnlinesData->consumer_mobile,$sms_text,$sms_template);
						}
						if(!empty($applyOnlinesData->installer_mobile))
						{
							//$this->ApplyOnlines->sendSMS($id,$applyOnlinesData->installer_mobile,$sms_text);
						}
					}
					if(!empty($applyOnlinesData->installer_email) && !empty($template_applied))
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
					if(!empty($to) && !empty($template_applied))
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
					$ErrorMessage   			= "Delete Application Request status updated successfully.";
					$success 					= 1;
				}
			} else {
				$ErrorMessage   				= "Invalid Request. Please validate form details.";
				$success 						= 0;
			}
		}
		echo json_encode(array('message'=>$ErrorMessage,'success'=>$success));
		exit;
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
	public function SendConsumerDetails($id = null)
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
			$arrOutput 				= $this->SendRegistrationFailure->fetchApiSendRegistration($id);

			if(isset($arrOutput->P_OUT_STS_CD) && $arrOutput->P_OUT_STS_CD==21)
			{
				$this->Flash->error($arrOutput->P_OUT_MSG_SERVER);
			}
			else{

				$this->Flash->success('Send registraton detail successfully.');
			}
			return $this->redirect(URL_HTTP.'/apply-online-list');
		}
	}
	/**
	 *
	 * corrigendum_letter
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to view corrigendum letter
	 *
	 */
	public function corrigendum_letter($id = null)
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
		$this->layout 		= false;
		if(empty($id)) {
			$this->Flash->error('Please Select Valid Application.');             
			return $this->redirect('home');
		} else {
			$encode_id 					= $id;
			$id 						= intval(decode($id));
			$applyOnlinesData 			= $this->ApplyOnlines->viewApplication($id);
			$applyOnlinesOthersData 	= $this->ApplyOnlinesOthers->find('all',array('conditions'=>array('application_id'=>$id)))->first();
			$applyOnlinesData->aid 		= "1".str_pad($id,7, "0", STR_PAD_LEFT);
			$LETTER_APPLICATION_NO 		= $applyOnlinesData->aid;
			$APPLICATION_DATE 			= date("d.m.Y",strtotime($applyOnlinesData->created));
			$Installers_data = $this->Installers->find("all",['conditions'=>['id'=>$applyOnlinesData->installer_id]])->first();
		    $Members = $this->Members->find("all",['conditions'=>['member_type'=>'6003','name'=>'CEI']])->first();
		    $discom_data		= array();
		    $discom_name    	= "";
		    $discom_short_name	= "";
		    if(!empty($applyOnlinesData->area)){
		    	$discom_data                = $this->Members->find("all",['conditions'=>['area'=>$applyOnlinesData->area,'circle'=>'0','division'=>'0','subdivision'=>'0','section'=>'0']])->first();
		    	$discom_name                = $this->BranchMasters->find("all",['conditions'=>['id'=>$discom_data->branch_id]])->first();
		    	$discom_short_name          = $this->DiscomMaster->find("all",['conditions'=>['id'=>$discom_name->discom_id]])->first();
		    } 
		}
		$category_name = '';
		if($applyOnlinesData->social_consumer==1 && ($applyOnlinesOthersData->renewable_attr == 1 || $applyOnlinesOthersData->renewable_attr === 0))
		{
			$category_name = 'industrial/commercial';
		}
		elseif($applyOnlinesData->social_consumer==1)
		{
			$category_name = 'Institutional-social';
		}
		elseif($applyOnlinesData->govt_agency==1)
		{
			$category_name = 'government';
		}
		elseif($applyOnlinesData->disclaimer_subsidy==1)
		{
			if(($applyOnlinesOthersData->renewable_attr == 1 || $applyOnlinesOthersData->renewable_attr === 0) && $applyOnlinesData->category!=3001)
			{
				$category_name = 'industrial/commercial';
			}
			else
			{
				if($applyOnlinesData->category==3001)
				{
					$category_name = 'residential';
				}
				else
				{
					$category_name = 'industrial/commercial';
				}
			}
		}
		else{
			if($applyOnlinesData->category==3001)
			{
				$category_name = 'residential';
			}
			else
			{
				$category_name = 'industrial/commercial';
			}
		}
		$applyOnlineGedaDate= $this->ApplyOnlineApprovals->getgedaletterStageData($id);
		$project_data 		= $this->Projects->find("all",['conditions'=>['id'=>$applyOnlinesData->project_id]])->first();
		$this->set("APPLY_ONLINE_MAIN_STATUS",$this->ApplyOnlineApprovals->apply_online_main_status);
		$this->set("pageTitle","Apply-online View");
		$this->set('ApplyOnlines',$applyOnlinesData);
		$this->set('Installers_data',$Installers_data);
		$this->set('Members',$Members);
		$this->set('LETTER_APPLICATION_NO',$LETTER_APPLICATION_NO);
		$this->set('APPLICATION_DATE',$APPLICATION_DATE);
		$this->set('discom_data',$discom_data);
		$this->set('discom_name',$discom_name);
		$this->set('applyOnlineGedaDate',$applyOnlineGedaDate);
		$this->set('project_data',$project_data);
		$this->set('category_name',$category_name);
		//$this->set("APPLY_ONLINE_GUJ_STATUS",$this->ApplyOnlineApprovals->apply_online_guj_status);
		$this->set('discom_short_name',$discom_short_name);
		$this->set('applyOnlinesOthersData',$applyOnlinesOthersData);

		/* Generate PDF for estimation of project */
		require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
		$dompdf = new Dompdf($options = array());

		$dompdf->set_option("isPhpEnabled", true);
		$this->set('dompdf',$dompdf);
		$dompdf->set_base_path(SITE_ROOT_DIR_PATH.'/pdf/');
		
		
		$currentdate 	= strtotime($applyOnlinesData->created);
		$submitedStage 	= $this->ApplyOnlineApprovals->getsubmittedStageData($applyOnlinesData->id);
		if($applyOnlinesData->social_consumer==1 && ($applyOnlinesOthersData->renewable_attr == 1 || $applyOnlinesOthersData->renewable_attr === 0))
		{
			$html = $this->render('/Element/corrigendum');
		}
		elseif($applyOnlinesData->govt_agency==1)
		{
			$html = $this->render('/Element/corrigendum');
		}
		elseif($applyOnlinesData->disclaimer_subsidy==1)
		{
			if(($applyOnlinesOthersData->renewable_attr == 1 || $applyOnlinesOthersData->renewable_attr === 0) && $applyOnlinesData->category!=3001)
			{
				$html = $this->render('/Element/corrigendum');
			}
			else
			{
				$html = $this->render('/Element/corrigendum');
			}
		}
		

		$dompdf->loadHtml($html,'UTF-8');
		$dompdf->setPaper('A4', 'portrait');
		@$dompdf->render();

		// Output the generated PDF to Browser
		/*if($isdownload){
			$dompdf->stream('corrigendum-'.$LETTER_APPLICATION_NO);
		}*/
		$output = $dompdf->output();
		header("Content-type:application/pdf");
		header("Content-Disposition:inline;filename='".$LETTER_APPLICATION_NO.".pdf'");
		echo $output;
		die;
	}
	/**
	*
	* fetchLatestConsumerData
	*
	* Behaviour : public
	*
	* @param :
	*
	* @defination : Method is use to change application district
	*
	*/
	public function fetchLatestConsumerData()
	{
		$this->layout 			= false;
		$this->setMemberArea();
		$member_id 				= $this->Session->read('Members.id');
		$application_id 		= isset($this->request->data['appid'])?intval(decode($this->request->data['appid'])):0;
		if(!empty($application_id))
		{
			$OutputGuvnlData			= $this->CommonFetchDetails($application_id,0);
			$viewAppdata 				= $this->ApplyOnlines->viewApplication($application_id);
			
			$ResponseData 				= json_decode($OutputGuvnlData,2);
			$result 					= array();
			$result['subdivision'] 		= '';
			$result['subdivision_id']	= '';
			$result['division'] 		= '';
			$result['division_id'] 		= '';
			$result['circle'] 			= '';
			$result['circle_id'] 		= '';
			$result['area_id'] 			= '';

			if($ResponseData['success']==1 || $ResponseData['success']==46)
			{
				$result 				= $ResponseData['response'];
				
				if(!empty($result['sub_division_api']) && isset($result['sub_division_api']))
				{
					$DiscomMasterHt 	= TableRegistry::get('DiscomMasterHt');
					if(!empty($result['division_api']))
					{
						$conditionsArr 	= array('division_sort_code'=>$result['division_api'],
												'ht_code'=>$result['sub_division_api'],
												'discom_code'=>$this->ThirdpartyApiLog->arr_discom_map[$viewAppdata->discom]);
						//pr($conditionsArr);
						$HTSubdivision 	= $DiscomMasterHt->find('all',array('conditions'=>$conditionsArr))->first();
						if(!empty($HTSubdivision))
						{
							$result['sub_division_api'] = $HTSubdivision->sort_code;
						}
						//pr($HTSubdivision);
					}
					$subdivision  		= $result['sub_division_api'];
				}
				$arr_dis_details 		= array();
				
				if($viewAppdata->discom != $this->ApplyOnlines->torent_ahmedabad && $viewAppdata->discom != $this->ApplyOnlines->torent_surat)
				{
					$discom_details 	= $this->DiscomMaster->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['DiscomMaster.short_code'=>$subdivision,'DiscomMaster.type'=>4,'status'=>'1']]);
					$arr_dis_details 	= $discom_details->toarray();
				}
				elseif(!empty($result['division_api']))
				{
					
					$discom_details 	= $this->DiscomMaster->find("list",
										['keyField'=>'id',
										'valueField'=>'title',
										'conditions'=>['DiscomMaster.short_code'=>$result['division_api'],'DiscomMaster.type'=>4,'status'=>'1']
										]);
					$arr_dis_details 	= $discom_details->toarray();

				}
				
				if (!empty($arr_dis_details)) {
					
					$result['subdivision']			= $arr_dis_details[key($arr_dis_details)];
					$result['subdivision_id']		= key($arr_dis_details);
					$discom_data_details			= $this->DiscomMaster->find("all",['conditions'=>['id'=>key($arr_dis_details),'status'=>'1']])->first();

					if(!empty($result['division_api']))
					{
						$division_data 				= $this->DiscomMaster->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['DiscomMaster.short_code'=>$result['division_api'],'DiscomMaster.type'=>3,'area'=>$discom_data_details->area,'circle'=>$discom_data_details->circle,'status'=>'1']])->toArray();

						$result['division'] 		= $division_data[key($division_data)];
						$result['division_id']		= key($division_data);
						$arrDiscom 					= $this->DiscomMaster->GetDiscomHirarchyByID(key($division_data));
						$circle_data 				= $this->DiscomMaster->find("all",['fields'=>array('id','title'),'conditions'=>['DiscomMaster.id'=>$arrDiscom->circle,'status'=>'1']])->first();
						if(!empty($circle_data) && isset($circle_data->title)) {
							$result['circle'] 		= $circle_data->title;
							$result['circle_id'] 	= $circle_data->id;
						}
					}
				}
				if(isset($result['circle_id'])) {
					$DiscomDetails 	= $this->DiscomMaster->find('all',array('conditions'=>array('id'=>$result['circle_id'])))->first();
					$result['area_id'] 	= $DiscomDetails->area;
				}
				echo json_encode(array('type'=>'ok','msg'=>'Application found.','result'=>$result));
			} 
			else
			{
				echo json_encode(array('type'=>'error','msg'=>isset($ResponseData['response']['response_msg']) ? $ResponseData['response']['response_msg'] : 'Application not found.','result'=>$result));
			}
			
		}
		else
		{
			echo json_encode(array('type'=>'error','msg'=>'Application not found.'));
		}
		exit;
	}
	/**
	*
	* changediscomdata
	*
	* Behaviour : public
	*
	* @param :
	*
	* @defination : Method is use to change application district
	*
	*/
	public function changediscomdata()
	{
		$this->layout 			= false;
		$this->setMemberArea();
		$member_id 				= $this->Session->read('Members.id');
		$application_id 		= isset($this->request->data['appid'])?intval(decode($this->request->data['appid'])):0;
		$area 					= isset($this->request->data['new_area_id'])?$this->request->data['new_area_id']:0;
		$circle 				= isset($this->request->data['new_circle_id'])?$this->request->data['new_circle_id']:0;
		$division 				= isset($this->request->data['new_division_id'])?$this->request->data['new_division_id']:0;
		$subdivision 			= isset($this->request->data['new_subdivision_id'])?$this->request->data['new_subdivision_id']:0;
		$address 				= isset($this->request->data['new_address'])?$this->request->data['new_address']:'';
		$name_applicant 		= isset($this->request->data['new_name_applicant'])?$this->request->data['new_name_applicant']:'';
		$last_name 				= isset($this->request->data['new_last_name'])?$this->request->data['new_last_name']:'';
		$third_name 			= isset($this->request->data['new_third_name'])?$this->request->data['new_third_name']:'';
		$new_sanction_load 		= isset($this->request->data['new_sanction_load'])?$this->request->data['new_sanction_load']:'';
		$new_existing_capacity 	= isset($this->request->data['new_existing_capacity'])?$this->request->data['new_existing_capacity']:'';
		
		if(!empty($application_id))
		{
			
			$applyOnlineDetails 		= $this->ApplyOnlines->find('all',array('conditions'	=> array('id'=>$application_id)))->first();
			
			$applyOnlineOthersDetails 	= $this->ApplyOnlinesOthers->find('all',array(	
												'fields'=>array(),
												'conditions'	=> array('application_id'=>$application_id)))->first();
			if($applyOnlineDetails->area!=$area || $applyOnlineDetails->circle!=$circle || $applyOnlineDetails->division!=$division || $applyOnlineDetails->subdivision!=$subdivision || $applyOnlineDetails->name_of_consumer_applicant!=$name_applicant || $applyOnlineDetails->last_name!=$last_name || $applyOnlineDetails->third_name!=$third_name || $applyOnlineDetails->address1!=$address || $applyOnlineDetails->sanction_load_contract_demand!=$new_sanction_load || $applyOnlineOthersDetails->existing_capacity!=$new_existing_capacity) {
				
				$arrNewData 					= array();
				
				$arrOldData['area']							= $applyOnlineDetails->area;
				$arrOldData['circle']						= $applyOnlineDetails->circle;
				$arrOldData['division']						= $applyOnlineDetails->division;
				$arrOldData['subdivision'] 					= $applyOnlineDetails->subdivision;
				$arrOldData['discom_name'] 					= $applyOnlineDetails->discom_name;
				$arrOldData['member_assign_id'] 			= $applyOnlineDetails->member_assign_id;
				$arrOldData['name_of_consumer_applicant'] 	= $applyOnlineDetails->name_of_consumer_applicant;
				$arrOldData['last_name'] 					= $applyOnlineDetails->last_name;
				$arrOldData['third_name'] 					= $applyOnlineDetails->third_name;
				$arrOldData['address1'] 					= $applyOnlineDetails->address1;
				$arrOldData['sanction_load_contract_demand']= $applyOnlineDetails->sanction_load_contract_demand;
				$arrOldData['existing_capacity']			= $applyOnlineOthersDetails->existing_capacity;

				$arrUpdate 									= array();
				$arrUpdate['area'] 							= $area;
				$arrUpdate['circle'] 						= $circle;
				$arrUpdate['division'] 						= $division;
				$arrUpdate['subdivision'] 					= $subdivision;
				$arrUpdate['discom_name'] 					= $division;
				$arrUpdate['member_assign_id'] 				= $division;
				$arrUpdate['name_of_consumer_applicant'] 	= $name_applicant;
				$arrUpdate['last_name'] 					= $last_name;
				$arrUpdate['third_name'] 					= $third_name;
				$arrUpdate['address1'] 						= $address;
				$arrUpdate['sanction_load_contract_demand']	= $new_sanction_load;
				if($applyOnlineOthersDetails->is_enhancement == 1) {
					$arrOthers['existing_capacity']			= $new_existing_capacity;
					$arrNewData 							= array_merge($arrNewData, $arrOthers);
					$this->ApplyOnlinesOthers->updateAll($arrOthers,['application_id'=>$application_id]);
				}
				$arrNewData 								= array_merge($arrNewData, $arrUpdate);
				$this->ApplyOnlines->updateAll($arrUpdate,['id'=>$application_id]);


				
				$UpdateDiscomDataLogEntity 					= $this->UpdateDiscomDataLog->newEntity();
				$UpdateDiscomDataLogEntity->application_id 	= $application_id;
				$UpdateDiscomDataLogEntity->request_id 		= isset($requestData->id) ? $requestData->id : '';
				$UpdateDiscomDataLogEntity->request_no 		= isset($requestData->request_no) ? $requestData->request_no : '';
				$UpdateDiscomDataLogEntity->old_data 		= json_encode($arrOldData);
				$UpdateDiscomDataLogEntity->new_data 		= json_encode($arrNewData);
				$UpdateDiscomDataLogEntity->created 		= $this->NOW();
				$UpdateDiscomDataLogEntity->created_by 		= $member_id;
				$this->UpdateDiscomDataLog->save($UpdateDiscomDataLogEntity);
				echo json_encode(array('type'=>'ok','msg'=>'Discom data updated successfully.'));
			} else {
				echo json_encode(array('type'=>'ok','msg'=>'Discom data already match with latest data.'));
			}
			
		}
		else
		{
			echo json_encode(array('type'=>'error','msg'=>'Discom data updated failed.'));
		}
		exit;
	}
	/**
	 * GetLastResponse
	 * Behaviour : Public
	 * @defination : Method is use to GetLastResponse per application
	 */
	public function GetLastResponse($id)
	{
		$this->autoRender 	= false;
		$ApplyonlineResponse = array();
		if(!empty($id)) {
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$ApplyonlineResponse 	= $this->ThirdpartyApiLog->GetLastResponse($id);
		}

		$view 			= new View($this->request,$this->response);
		$view->layout 	= 'empty';
		$view->set('ApplyonlineResponse', $ApplyonlineResponse);
		$html = $view->render('/ApplyOnlines/get_last_response');
		$this->ApiToken->SetAPIResponse('html',$html);
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	/**
	 * GetCustomerResponse
	 * Behaviour : Public
	 * @defination : Method is use to GetCustomerResponse per application
	 */
	public function GetCustomerResponse($id)
	{
		$this->autoRender 	= false;
		$ApplyonlineResponse = array();

		if(!empty($id)) {
			$encode_id 				= $id;
			$id 					= intval(decode($id));

			$ApplyonlineResponse 	= $this->ApplyOnlines->find('all',array(
				'fields'=>array('api_response'),
				'conditions'=>array('id'=>$id)
			))->first();

		}

		$view 			= new View($this->request,$this->response);
		$view->layout 	= 'empty';
		$view->set('ApplyonlineResponse', $ApplyonlineResponse);
		$view->set('OnlyResponse',1);

		$html = $view->render('/ApplyOnlines/get_last_response');
		$this->ApiToken->SetAPIResponse('html',$html);
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	/**
	 * RecallMeter
	 * Behaviour : Public
	 * @defination : Method is use to recall meter API
	 */
	public function RecallMeter()
	{
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['recall_aplication_id'])?$this->request->data['recall_aplication_id']:0);

		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} else {
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$response 				= $this->ChargingCertificate->fetchApiMeterInstallation($id);
			$success 				= 1;
			$ErrorMessage 			= "Application applied for recall Meter.";

			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);

		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	public function UploadUndertaking()
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
			$applyOnlinesData 		= $this->ApplyOnlines->viewApplication($id);
			if (!empty($applyOnlinesData)) {
				if ($this->request->is('post')) {	
					if(!empty($this->request->data['file']['name']))
					{
						$prefix_file= '';
						$name 		= $this->request->data['file']['name'];
						
						$image_path = APPLYONLINE_PATH.$id.'/';
						if(!file_exists(APPLYONLINE_PATH.$id)) {
							@mkdir(APPLYONLINE_PATH.$id, 0777);
						}
						$scheme_id 	= $this->SchemeMaster->findActiveSchemeId();

						$file_name 	= $this->file_upload($image_path,$this->request->data['file'],false,'','','','app_u_under_','upload_undertaking');

						$this->ApplyOnlinesOthers->updateAll(['upload_undertaking' => $file_name,'scheme_id'=>$scheme_id], ['application_id' => $id]);
						$ErrorMessage 	= "Undertaking document uploaded successfully.";
						$success 		= 1;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					} else {
						$ErrorMessage 	= "Please upload undertaking file.";
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
	 *
	 * rfid_upload_docs
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to view installer
	 *
	 */
	public function rfid_upload_docs($id = null)
	{

		$this->autoRander   = false;
		$this->layout       = 'popup';
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
			
			$applyOnlinesData 		= $this->ApplyOnlines->viewApplication($id);

			
		}
		
		
		// Internal clashed Location list End//
	
		$this->set('id',$encode_id);
		//$this->set('application_id',encode($application_id));
		$this->set("pageTitle","Upload Docs");
		$this->set("MStatus",$this->ApplicationStages);
		$this->set('logged_in_id',$this->Session->read('Customers.id'));
		$this->set('is_member',$is_member);
		$this->set('encode_id',$encode_id);
		$this->set('Couchdb',$this->ReCouchdb);
		
	}
}