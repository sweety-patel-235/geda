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

class ApplyOnlinesKusumController extends FrontAppController
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
		$this->loadModel('ApplyOnlineKusumApprovals');
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
		$this->loadModel('DistrictMaster');
		$this->loadModel('ApplyOnlinesKusum');
		$this->loadModel('KusumSurveyInformation');
		$this->loadModel('KusumMembers');
		$this->loadModel('ApplyonlineKusumDocs');
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
		$this->set("MStatus",$this->ApplyOnlineKusumApprovals);
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
	 * @param edited ID of apply online kusum .
	 * @return void
	 */
	public function index($id = 0) {
		$is_installer 			= false;
		$installer_id           = '';
		if(!empty($this->Session->read('Members.member_type')))
		{
			$this->setMemberArea();
			$member_type 		= $this->Session->read('Members.member_type');
			$customerId 		= $this->Session->read("Members.id");
			$ses_customer_type 	= $this->Session->read('Members.member_type');
			$is_installer 		= false;

		}
		else
		{
			$this->setCustomerArea();
			$customerId 		= $this->Session->read("Customers.id");
			$ses_customer_type 	= $this->Session->read('Customers.customer_type');
			if ($ses_customer_type == "installer") {
				$is_installer 	= true;
				$customer_details 	= $this->Customers->find('all',array('conditions'=>array('id'=>$customerId)))->first();
				$installer_id 		= $customer_details['installer_id'];
			}
		}
		$str_url 	= '';
		if(!empty($id)) {
			$str_url= $id;
			$id 	= intval(decode($id));
		}

		$arrSessionInfo['login_id'] 		= $customerId;
		$arrSessionInfo['installer_id'] 	= $installer_id;
		$arrSessionInfo['is_installer'] 	= $is_installer;

		//$this->removeExtraTags('ApplyOnlines');

		$tab 		= '';
		$ApplyonlinDocsList = array();
		$Applyonlinprofile 	= array();
	

	
		$customer_state 		= $this->Session->read('Customers.state');

		$arrDistrict 			= $this->DistrictMaster->find('list',array('keyField'=>'id','valueField'=>'name','conditions'=>array('state_id'=>$this->ApplyOnlines->gujarat_st_id),'order'=>array('name'=>'asc')))->toArray();

		$discom_list 			= array();
		$discom_list 			= $this->BranchMasters->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['BranchMasters.status'=>'1','BranchMasters.parent_id'=>'0','BranchMasters.state'=>$this->ApplyOnlines->gujarat_st_id]])->toArray();

		$installers_list 		= $this->Installers->find('list',['keyField'=>'id','valueField'=>'installer_name','join'=>[['table'=>'installer_projects','type'=>'inner','conditions'=>'installer_projects.installer_id = Installers.id']],'conditions'=>['Installers.stateflg'=>$this->ApplyOnlines->gujarat_st_id,'OR'=>['Installers.registration_type'=>0,'Installers.registration_type IS NULL']]])->toArray();	
		
		/*$discom_list 			= $this->DiscomMaster->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['DiscomMaster.state_id'=>$this->ApplyOnlines->gujarat_st_id,'DiscomMaster.type'=>3,'status'=>'1']]);*/
		
		if(!empty($id)) {
			$ApplyOnlineData 	= $this->ApplyOnlinesKusum->get($id);
			$ApplyOnlinesEntity	= $this->ApplyOnlinesKusum->patchEntity($ApplyOnlineData,$this->request->data);
			$this->request->data['ApplyOnlinesKusum']['id'] 	= $id;
			$arrMembers 		= $this->KusumMembers->find('all',array('conditions'=>array('application_id'=>$id)))->toArray();
			$arrSurveyInfo 		= $this->KusumSurveyInformation->find('all',array('conditions'=>array('application_id'=>$id)))->toArray();
			if(!empty($arrMembers) && !isset($this->request->data['ApplyOnlinesKusum']['members'])) {
				foreach($arrMembers as $memberData) {
					$this->request->data['ApplyOnlinesKusum']['members'][]=$memberData->name;
				}
			}
			
			if(!empty($arrSurveyInfo)) {
				foreach($arrSurveyInfo as $k=>$surveyInfo) {
					$this->request->data['ApplyOnlinesKusum']['land_survey_no'][$k]=$surveyInfo->survey_no;
					$this->request->data['ApplyOnlinesKusum']['land_survey_area'][$k]=$surveyInfo->survey_area;
					$this->request->data['ApplyOnlinesKusum']['land_survey_type_'.$k]=$surveyInfo->survey_type;
				}
			}
		} else {
			$ApplyOnlinesEntity	= $this->ApplyOnlinesKusum->newEntity($this->request->data);
		}
		
		if(isset($this->request->data['save_submit']) && !empty($this->request->data)) {
			$ApplyOnlinesEntity = $this->ApplyOnlinesKusum->saveDetails($this->request->data,$arrSessionInfo);
			if($ApplyOnlinesEntity->submit == 1) {
				$this->Flash->set('Your Application send successful.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
				return $this->redirect('apply-online-kusum-list');
			}
			
		}
		if(empty($ApplyOnlinesEntity->errors()) && !empty($ApplyOnlinesEntity->aadhaar_no))
		{
			$ApplyOnlinesEntity->aadhaar_no = passdecrypt($ApplyOnlinesEntity->aadhaar_no);
		}
		
		$arrRequestData 		= isset($this->request->data['ApplyOnlinesKusum']) ? $this->request->data['ApplyOnlinesKusum'] : array();	
		$this->set("customer_state",$customer_state);
		$this->set('applicant_type_kusum',$this->Parameters->GetParameterList(8));
		$this->set('arr_area_kusum',$this->Parameters->GetParameterList(9)->toArray());
		$this->set('arr_distance',$this->Parameters->GetParameterList(10)->toArray());
		$this->set('arrDistrict',$arrDistrict);
		$this->set('discom_list',$discom_list);
		$this->set('installers_list',$installers_list);
		$this->set('pageTitle','Apply Online Kusum');
		$this->set('ApplyOnlines',$ApplyOnlinesEntity);
		$this->set('RequestData',$arrRequestData);
		$this->set("str_url",$str_url);
		$this->set('ApplyOnlineErrors',$ApplyOnlinesEntity->errors());
		$this->set('ApplyOnlinesKusum',$this->ApplyOnlinesKusum);
		$this->set('Couchdb',$this->Couchdb);
	}
	/**
	 * getInstaller
	 * Behaviour : Public
	 * @param : installer_id   : pass installer_id as post parameter
	 * @defination : Method is use to set admin area use for admin base on restriction set for particular
	 */
	public function getInstaller() {
		$this->autoRender 		= false;
		$installer_id 			= isset($this->request->data['installer_id'])?$this->request->data['installer_id']:0;
		
		$installer_details 		= array();
		if (!empty($installer_id)) {
			$installer_details 	= $this->Installers->find("all",[
											'fields'=>array('email','mobile'),
											'conditions'=>['id'=>$installer_id]])->first();
			
		} else {
			
		}
		$this->ApiToken->SetAPIResponse('msg', 'Installer Details');
		$this->ApiToken->SetAPIResponse('data', $installer_details);
		$this->ApiToken->SetAPIResponse('type','ok');
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	/**
	 * applyonline_list
	 * Behaviour : Public
	 * @param : page   : Requires for page number
	 * @defination : Method is use to list kusum applications.
	 */
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
		if($this->Session->check("Members.state")){
			$state 			= $this->Session->read("Members.state");
		}
		if($this->Session->check("Customers.customer_type")){
			$cust_type 		= $this->Session->read("Customers.customer_type");
		}
	

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
		$arrSessionInfo['member_id']		= $member_id;
		$arrSessionInfo['customer_id']		= $customer_id;
		$arrSessionInfo['main_branch_id']	= $main_branch_id;
		$arrRequest['consumer_no'] 			= isset($this->request->data['consumer_no']) ? $this->request->data['consumer_no'] : '';
		$arrRequest['application_search_no']= isset($this->request->data['application_search_no']) ? $this->request->data['application_search_no'] : '';
		$arrRequest['installer_name'] 		= (isset($this->request->data['installer_name'])) ? $this->request->data['installer_name'] : '';
		$arrRequest['discom_name'] 			= isset($this->request->data['discom_name']) ? $this->request->data['discom_name'] : '';
	
		$arrRequest['order_by_form']		= isset($this->request->data['order_by_form']) ? $this->request->data['order_by_form'] : 'ApplyOnlinesKusum.modified|DESC';
		
		$arrRequest['pcr_code'] 			= isset($this->request->data['pcr_code']) ? $this->request->data['pcr_code'] : '';
		
		$arrRequest['geda_letter_status'] 	= isset($this->request->data['geda_letter_status']) ? $this->request->data['geda_letter_status'] : '';
		$arrRequest['geda_approved_status'] = isset($this->request->data['geda_approved_status']) ? $this->request->data['geda_approved_status'] : '';
		$arrRequest['from_date'] 			= isset($this->request->data['from_date']) ? $this->request->data['from_date'] : '';
		$arrRequest['to_date'] 				= isset($this->request->data['to_date']) ? $this->request->data['to_date'] : '';
		
		
		$installer_id 						= '';
		
		$this->layout = 'frontend';
		if(!empty($customer_id)) {
			

			if($cust_type == 'installer')
			{
				$customer_details 	= $this->Customers->find('all',array('conditions'=>array('id'=>$customer_id)))->first();
				$installer_id 		= $customer_details['installer_id'];
			}
			$ApplyOnlinesList 	= $this->ApplyOnlinesKusum->getDataapplyonline($arrRequest,$arrSessionInfo);
			
			$this->set('is_member',false);
		} else if(!empty($member_id)) {
			$ApplyOnlinesList 	= $this->ApplyOnlinesKusum->getDataapplyonline($arrRequest,$arrSessionInfo);
			
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
		$this->set("APPLY_ONLINE_MAIN_STATUS",$this->ApplyOnlineKusumApprovals->apply_online_main_status);
		$this->set("APPLICATION_SUBMITTED",$this->ApplyOnlineKusumApprovals->APPLICATION_SUBMITTED);
		$this->set("FEASIBILITY_APPROVAL",$this->ApplyOnlineKusumApprovals->FEASIBILITY_APPROVAL);
		$this->set("FUNDS_ARE_AVAILABLE_BUT_SCHEME_IS_NOT_ACTIVE",$this->ApplyOnlineKusumApprovals->FUNDS_ARE_AVAILABLE_BUT_SCHEME_IS_NOT_ACTIVE);
		$this->set("FUNDS_ARE_NOT_AVAILABLE",$this->ApplyOnlineKusumApprovals->FUNDS_ARE_NOT_AVAILABLE);
		$this->set("SUBSIDY_AVAILIBILITY",$this->ApplyOnlineKusumApprovals->SUBSIDY_AVAILIBILITY);
		$this->set("WORK_STARTS",$this->ApplyOnlineKusumApprovals->WORK_STARTS);
		$this->set("APPLICATION_GENERATE_OTP",$this->ApplyOnlineKusumApprovals->APPLICATION_GENERATE_OTP);
		/* end status of application */
		$this->set("JREDA",$this->ApplyOnlines->JREDA);
		$this->set("DISCOM",$this->ApplyOnlines->DISCOM);
		$this->set("CEI",$this->ApplyOnlines->CEI);
		$this->set("MStatus",$this->ApplyOnlineKusumApprovals);
		$this->set("ApplyOnlines",$this->ApplyOnlines);
		$this->set("FesibilityReport",$this->FesibilityReport);
		$this->set("application_status",$this->ApplyOnlineKusumApprovals->application_status);
		$this->set("application_dropdown_status",$this->ApplyOnlines->apply_online_dropdown_status);
		$this->set("branch_id",$branch_id);
		$this->set("subdivision",$this->Session->read("Members.subdivision"));
		$this->set('ApplyOnlineLeads',$paginate_data);
		$this->set("ApplyOnlinesList",$ApplyOnlinesList);
		$this->set("MemberTypeDiscom",$this->Members->member_type_discom);
		$this->set("discom_details",$main_branch_id);
		$this->set("payment_on",PAYUMONEY_KUSUM);
		$this->set("APPLY_ONLINE_GUJ_STATUS",$this->ApplyOnlineKusumApprovals->apply_online_guj_status);
		$this->set("applyOnlinesDataDocList",$this->ApplyonlinDocs);
		$this->set('discom_arr',$discom_arr);
		$this->set('quota_msg_disp','');
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
		$this->set("pageTitle","My Apply-online-kusum List");
	}
	/**
	 * VarifyOtp
	 * Behaviour : Public
	 * @param : appid,  otp 
	 * @defination : Method is use to varify OTP of KUSUM application
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
				/*$ErrorMessage 	= STOP_ADD_APPLICATION_MSG;
				$success 		= 0;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
				echo $this->ApiToken->GenerateAPIResponse();
				exit;*/
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->ApplyOnlinesKusum->find('all',array('Fields'=>['otp','id'],'conditions'=>array('id'=>$id)))->first();
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
						$application_status = $this->ApplyOnlineKusumApprovals->APPLICATION_PENDING;
						$this->ApplyOnlinesKusum->updateAll(array('application_status'=>$application_status),array('id'=>$id));
						$customer_id 			= $this->Session->read("Customers.id");
						$this->ApplyOnlineKusumApprovals->saveStatus($id,$this->ApplyOnlineKusumApprovals->APPLICATION_PENDING,$customer_id,'');
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
	 * Behaviour : Public
	 * @param : appid,  otp 
	 * @defination : Method is use to resend OTP of KUSUM application
	 */
	public function resend_otp($app_id)
	{
		$application_id    	= intval(decode($app_id));
		$ApplyOnlinesdata 	= $this->ApplyOnlinesKusum->find('all',array('conditions'=>array('id'=>$application_id)))->first();
		$ses_customer_type 		= $this->Session->read('Customers.customer_type');
		$is_installer 			= false;
		if ($ses_customer_type == "installer") {
			$is_installer 		= true;
		}
		if(!empty($ApplyOnlinesdata))
		{
			$sms_mobile = $ApplyOnlinesdata->installer_mobile;

			
			$sms_message =str_replace('##application_no##',$ApplyOnlinesdata->application_no, OTP_RESEND);
			$this->ApplyOnlinesKusum->SendSMSActivationCode($ApplyOnlinesdata->id,$sms_mobile,$sms_message,'OTP_RESEND');
			
			$this->Flash->set('OTP Resend successfully.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
			$this->redirect(URL_HTTP.'apply-online-kusum-list');
		}
	}
	/**
	 * UploadDocument
	 * Behaviour : Public
	 * @param : application_id in encoded format
	 * @defination : Method is use to upload sign document
	 */
	public function UploadDocument()
	{
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['application_id'])?$this->request->data['application_id']:0);
		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
		} else {
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->ApplyOnlinesKusum->find('all',array('conditions'=>array('id'=>$id)))->first();
			
			$customer_id 			= $this->Session->read("Customers.id");
			$arrSession['login_id'] = $customer_id;
			if (!empty($applyOnlinesData)) {
				if ($this->request->is('post')) {
					$output_quota 	= true;//$this->ApplyOnlines->checked_total_capacity($applyOnlinesData->installer_id,$applyOnlinesData->pv_capacity,$applyOnlinesData->category,$applyOnlinesData->social_consumer,$applyOnlinesData->id,$applyOnlinesData->disclaimer_subsidy);
					if($output_quota===true)
					{
						$this->request->data['id'] 	= $id;
						$result 	= $this->ApplyOnlinesKusum->uploadDocument($this->request->data,$arrSession,'Signed_Doc');
						if($result == 1) {
							$ErrorMessage 	= "Document Uploaded Successfully.";
							$success 		= 1;
						} else {
							$ErrorMessage 	= "Error while uploading document.";
							$success 		= 0;
						}
					}
					else
					{
						$ErrorMessage 	= $output_quota;
						$success 		= 0;
					}
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate form details.";
				$success 		= 0;
			}
		}
		$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
		$this->ApiToken->SetAPIResponse('success',$success);
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
		$message 			= (isset($this->request->data['message'])?$this->request->data['message']:0);
		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} else {
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->ApplyOnlinesKusum->find('all',array('fields'=>array('id'),'conditions'=>array('id'=>$id)))->first();
			$customer_id 			= $this->Session->read("Customers.id");
			$arrSession['login_id'] = $customer_id;

			if (!empty($applyOnlinesData)) {
				if ($this->request->is('post')) {
					
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
						$this->request->data['id'] 	= $id;

						$result 	= $this->ApplyOnlinesKusum->uploadDocument($this->request->data,$arrSession,'others',$message);
						if($result == 1) {
							$ErrorMessage 	= "Document Uploaded Successfully.";
							$success 		= 1;
						} else {
							$ErrorMessage 	= "Error while uploading document.";
							$success 		= 0;
						}
					}
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate form details.";
				$success 		= 0;
				
			}
		}
		$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
		$this->ApiToken->SetAPIResponse('success',$success);
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	/**
	 * view
	 * Behaviour : Public
	 * @defination : Method is use to view installer
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
			$applyOnlinesData 		= $this->ApplyOnlinesKusum->viewApplication($id);
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
		$FeasibilityData= $this->FesibilityReport->find("all",['conditions'=>['application_id'=>$id]])->first();
		$member_type 	= $this->Session->read('Members.member_type');
		$page_cur 		= '1';
		if($this->Session->check("Customers.Page"))
		{
			$page_cur 	= $this->Session->read("Customers.Page");
		}

		$applyOnlinesOthersData = $this->ApplyOnlinesOthers->find('all',array('conditions'=>array('application_id'=>$id)))->first();
		$arrMembers 			= $this->KusumMembers->find('all',array('conditions'=>array('application_id'=>$id)))->toArray();
		$memberViewPanAdhar 	= in_array($member_id, $this->ApplyOnlines->ALLOWED_APPROVE_GEDAIDS) ? '1' : '0';
		$applyOnlinesDataDocList= $this->ApplyonlineKusumDocs->find("all",['conditions'=>['application_id'=>$id,'doc_type in'=>array('others','Signed_Doc')]])->toArray();
		$ParametersDetails	 	= $this->Parameters->find('all',array('conditions'=>array('para_id'=>$applyOnlinesData->applicant_type_kusum)))->first();
		$this->set("APPLY_ONLINE_MAIN_STATUS",$this->ApplyOnlineKusumApprovals->apply_online_main_status);
		$this->set("pageTitle","Apply-online-kusum View");
		$this->set("discom_list",$discom_list);
		$this->set('ApplyOnlines',$applyOnlinesData);
		$this->set('transaction_id',$transaction_id);
		$this->set('payment_date',$payment_date);
		$this->set('member_type',$member_type);
		$this->set("MemberTypeDiscom",$this->Members->member_type_discom);
		$this->set("APPLY_ONLINE_GUJ_STATUS",$this->ApplyOnlineKusumApprovals->apply_online_guj_status);
		$this->set("MStatus",$this->ApplyOnlineKusumApprovals);
		$this->set('logged_in_id',$this->Session->read('Customers.id'));
		$this->set('member_type',$member_type);
		$this->set('is_member',$is_member);
		$this->set('FeasibilityData',$FeasibilityData);
		$this->set('page_cur',$page_cur);
		$this->set('encode_id',$encode_id);
		$this->set('ApplyOnlinesTable',$this->ApplyOnlinesKusum);
		$this->set('arrMembers',$arrMembers);
		$this->set('memberViewPanAdhar',$memberViewPanAdhar);
		$this->set('applyOnlinesDataDocList',$applyOnlinesDataDocList);
		$this->set("payment_on",Configure::read('PAYUMONEY_PAYMENT'));
		$this->set('Couchdb',$this->Couchdb);
		$this->set('ParametersDetails',$ParametersDetails);
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
		$this->ApplyOnlinesKusum->generateApplicationPdf($id);
	}
}