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
//use Cake\I18n\Date;
class ApplicationsAgreementController extends FrontAppController
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
		$this->loadModel('ApplicationStages');
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
		$this->loadModel('WindApplicationDeveloperPermission');
		$this->loadModel("OpenAccessApplicationDeveloperPermission");
		$this->loadModel('wind_manufacturer_rlmm');
		$this->loadModel('GeoApplicationRejectLog');
		$this->loadModel('GeoApplicationPayment');
		$this->loadModel('GeoApplicationClashedData');
		$this->loadModel('GeoCoordinateOfflineApproved');
		$this->loadModel('TalukaMaster');
		$this->loadModel('ApplicationsAgreement');
		$this->loadModel('AgreementConsumerDetails');
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
	 * geo_cordinate
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is add to geo_cordinate
	 *
	 */
	public function applications_agreement($id = null)
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

			//$wtgno_data						= $this->ApplicationGeoLocation->find("all",['conditions'=>['application_id'=>$id,'capacity_type'=>3]])->toArray();

			$wtgno_data = $this->ApplicationGeoLocation->find('all',
	                                        [ 'fields'=>['wtg_location','wtg_capacity','id'],
	                                            'join'=>[['table'=>'wind_wtg_detail','type'=>'left','conditions'=>'ApplicationGeoLocation.id = wind_wtg_detail.app_geo_loc_id'], ['table'=>'wind_application_developer_permission','type'=>'left','conditions'=>'wind_wtg_detail.app_dev_per_id = wind_application_developer_permission.id']],
	                                            'conditions'=>['NOT' => ['ApplicationGeoLocation.remaining_wtg_location' => 0],'wind_application_developer_permission.payment_status'=>1,'ApplicationGeoLocation.application_id'=>$id]])->toArray();

			
		  	$WtgList = [];
			foreach ($wtgno_data as $key => $value) {
				$WtgList[$value['id']] = 'Wtg Location - ' . $value['wtg_location'];
			}
	
			$ApplicationsDocs 				= $this->ApplicationsDocs->find('all',array('conditions'=>array('application_id'=>$id,'doc_type'=>'geo_cordinate_file')))->toArray();
			
			$applications_agreement_data 	= $this->ApplicationsAgreement->find('all',array('conditions'=>array('application_id'=>$id)))->first();

			$agreement_consumer_details 		= $this->AgreementConsumerDetails->find('all',array('conditions'=>array('application_id'=>$id)))->toArray();

			
		}

		$applicationCategory 	= $this->ApplicationCategory->find('all',array('conditions'=>array('id'=>$applyOnlinesData->application_type)))->first();
		
	  	//echo"<pre>"; print_r($agreement_consumer_details); die();
		$this->set("WtgList",$WtgList);
		$this->set("applications_agreement_data",$applications_agreement_data);
		$this->set("agreement_consumer_details",$agreement_consumer_details);
		$this->set("applicationCategory",$applicationCategory);
		$this->set('id',$encode_id);
		$this->set('wtgno_data',$wtgno_data);
		$this->set('geo_location_charges',$applicationCategory->geo_location_charges);
		$this->set("pageTitle","Add Agreement Application");
		$this->set('Applications',$applyOnlinesData);
		$this->set("MemberTypeDiscom",$this->Members->member_type_discom);
		$this->set("MStatus",$this->ApplicationStages);
		$this->set('logged_in_id',$this->Session->read('Customers.id'));
		$this->set('is_member',$is_member);
		$this->set('encode_id',$encode_id);
		$this->set('ApplicationsDocs',$ApplicationsDocs);
		$this->set('Couchdb',$this->ReCouchdb);
		$this->set('ApplicationsAgreement',$this->ApplicationsAgreement);
	}
	/**
	 * geo_location_savedata
	 * Behaviour : Public
	 * @defination : Method is use to save the geo_location data.
	 */
	public function application_agreement_savedata()
	{	
		//echo"<pre>"; print_r($this->request->data); die();
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['AddAgreementApplication_application_id'])?$this->request->data['AddAgreementApplication_application_id']:0);
		$application_type 	= (isset($this->request->data['AddAgreementApplication_application_type'])?$this->request->data['AddAgreementApplication_application_type']:0);
		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} 
		else{
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applicationData 		= $this->Applications->viewApplication($id);
			$customer_id 			= $this->Session->read("Customers.id");
			$member_id 				= $this->Session->read("Members.id");
			if (!empty($applicationData) && (!empty($customer_id) || !empty($member_id))) {
				
				if((empty($this->request->data['consumer_no']) || (empty($this->request->data['consumer_name'])) || 
					(empty($this->request->data['discom_name'])) || (empty($this->request->data['wtg_location'])) || (empty($this->request->data['wtg_capacity'])) || (empty($this->request->data['percentage_share'])) || 
					(empty($this->request->data['capacity_allocated'])) || (empty($this->request->data['transmission_agree_doc'])) || (empty($this->request->data['whelling_agree_doc'])) ))
				{
						$ErrorMessage 	= "Please select all the details as per Required.";
						$success 		= 0;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
				} else {
					$application_geo_location_data   		= $this->ApplicationGeoLocation->find('all',array('conditions'=>array('id'=>$this->request->data['wtg_location'])))->first();
				
					
					$applications_agreement_data   = $this->ApplicationsAgreement->find('all',array('conditions'=>array('application_id'=>$id)))->first();
					if(empty($applications_agreement_data)){

						$arr_modules										= $this->ApplicationsAgreement->newEntity();
						$arr_modules['consumer_count']						= 0;
						$arr_modules['total_consumer_details_application']	= 0;
						$arr_modules['total_capacity']						= 0;
						$arr_modules['total_allocate_capacity']				= 0;

						$this->ApplicationsAgreement->save_data($id,$arr_modules,$customer_id);
						
					}
					if($application_geo_location_data->remaining_wtg_location < $this->request->data['percentage_share']){
						//echo"<pre>"; print_r($this->request->data); die();
						$ErrorMessage 	= "The Remaining Percentage is $application_geo_location_data->remaining_wtg_location ";
						$success 		= 0;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					}else{
						$applications_agreement_data   = $this->ApplicationsAgreement->find('all',array('conditions'=>array('application_id'=>$id)))->first();
						$geo_location_data   = $this->ApplicationGeoLocation->find('all',array('conditions'=>array('id'=>$this->request->data['wtg_location'])))->first();

						$arr_modules							= $this->AgreementConsumerDetails->newEntity();
						$arr_modules['agreement_id']			= $applications_agreement_data->id;
						$arr_modules['consumer_no']				= $this->request->data['consumer_no'];
						$arr_modules['consumer_name']			= $this->request->data['consumer_name'];
						$arr_modules['discom_name']				= $this->request->data['discom_name'];
						$arr_modules['geo_location_id']			= $this->request->data['wtg_location'];
						
						$arr_modules['wtg_location']			= $geo_location_data->wtg_location;
						$arr_modules['wtg_capacity']			= $this->request->data['wtg_capacity'];
						
						$arr_modules['percentage_share']		= $this->request->data['percentage_share'];
						$arr_modules['capacity_allocated']		= $this->request->data['capacity_allocated'];
						$arr_modules['transmission_agree_doc']	= $this->request->data['transmission_agree_doc'];
						$arr_modules['whelling_agree_doc']		= $this->request->data['whelling_agree_doc'];

						$this->AgreementConsumerDetails->save_data($id,$arr_modules,$customer_id,$application_type);
						if(($application_geo_location_data->used_wtg_location <= 100) && ($application_geo_location_data->remaining_wtg_location >= $this->request->data['percentage_share'])){

							$arr_module['used_wtg_location']		= $application_geo_location_data->used_wtg_location + $this->request->data['percentage_share'];
							$arr_module['remaining_wtg_location']	= $application_geo_location_data->remaining_wtg_location - $this->request->data['percentage_share'];
							$this->ApplicationGeoLocation->updateAll($arr_module,array('id'=>$this->request->data['wtg_location']));
						}
						$arr_mod['total_consumer_details_application']		= $applications_agreement_data->total_consumer_details_application + 1;
						$arr_mod['total_capacity']							= $applications_agreement_data->total_capacity + $this->request->data['wtg_capacity'];
						$arr_mod['total_allocate_capacity']					= $applications_agreement_data->total_allocate_capacity + $this->request->data['capacity_allocated'];
						$this->ApplicationsAgreement->updateAll($arr_mod,array('application_id'=>$id));	
						
						$ErrorMessage 	= "Record Added Succesfully";
						$success 		= 1;
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
	 * geo_location_editdata
	 * Behaviour : Public
	 * @defination : Method is use to edit the geo_location data.
	 */
	
	public function getSavedData()
	{
		// /echo"<pre>"; print_r($this->request->data); die();
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['id'])?$this->request->data['id']:0);
		
		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		}else{
			if(!empty($id)){
					
					$agreement_consumer_details   = $this->AgreementConsumerDetails->find('all',array('conditions'=>array('id'=>$id)))->first();
					//$agreement_consumer_details['application_id'] = encode($agreement_consumer_details['application_id']);
					$agreement_consumer_details['path1'] = URL_HTTP.'app-docs/transmission_agree_doc/'.encode($agreement_consumer_details['application_id']);
					$agreement_consumer_details['path2'] = URL_HTTP.'app-docs/whelling_agree_doc/'.encode($agreement_consumer_details['application_id']);
					
					$agreement_consumer_details = json_encode($agreement_consumer_details);
					//echo"<pre>"; print_r($agreement_consumer_details); die();

					if(!empty($agreement_consumer_details)) {
				
							$data 	= $agreement_consumer_details;
							$success 		= 1;
							$this->ApiToken->SetAPIResponse('message',$data);
							$this->ApiToken->SetAPIResponse('success',$success);
					
					}else {
						$ErrorMessage 	= "Invalid Request. Please validate form details.";
						$success 		= 0;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					}

					// if(!empty($geo_clashed_data)){
					// 	$arr_moduless['approved']			= 1;
					// 	$arr_moduless['status']				= 1;

					// 	$this->GeoApplicationClashedData->updateAll($arr_moduless,array('clashed_geo_id'=>$id));
					// }

					// $ErrorMessage 	= "Record Approved Succesfully";
					// $success 		= 1;
					// $this->ApiToken->SetAPIResponse('message',$ErrorMessage);
					// $this->ApiToken->SetAPIResponse('success',$success);
				}
			
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
		
	}
	/**
	 * geo_location_approvedata
	 * Behaviour : Public
	 * @defination : Method is use to edit the geo_location data.
	 */
	

    /**
	 * getTalukaFromDistrict
	 * Behaviour : Public
	 * @defination : Method is use to get taluka from district
	 */
	public function getCapacityFromLocation()
	{
		$this->autoRender 	= false;
		$wtg_location 			= isset($this->request->data['wtg_location']) ? $this->request->data['wtg_location'] : '';

		
		$geo_application_data   = $this->ApplicationGeoLocation->find('all',array('conditions'=>array('id'=>$wtg_location)))->first();
		
		$resultCapacity 	= $geo_application_data->wtg_capacity;
		
		$this->ApiToken->SetAPIResponse('msg', 'Capacity');
		$this->ApiToken->SetAPIResponse('data', $resultCapacity);
		$this->ApiToken->SetAPIResponse('success','1');
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

}

