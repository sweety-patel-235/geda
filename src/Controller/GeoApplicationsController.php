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
class GeoApplicationsController extends FrontAppController
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
		$this->loadModel('ApplicationGeoLocationDeleted');
		$this->loadModel('TalukaMaster');
		$this->loadModel('GeoApplicationVerification');
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
	public function geo_location($id = null)
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

			$wtgno_data						= $this->ApplicationHybridAdditionalData->find("all",['conditions'=>['application_id'=>$id,'capacity_type'=>3]])->toArray();
			$applications 					= $this->ApplicationHybridAdditionalData->find();
			$total_application 				= $applications->select(['total_wtgno' => $applications->func()->sum('nos_mod_inv')])->where(array('application_id'=>$id,'capacity_type'=>3))->first();
			
			
			if(!empty($member_id)){
				$condition 	= array('application_id'=>$id,'payment_status IN'=>array(1,2)); //Add code (,'OR'=>['approved is NULL','approved is NOT'=>1])if u dont want to see approve application to member
			}else{
				$condition 	= array('application_id'=>$id);
			}
			$geo_application_data			= $this->ApplicationGeoLocation->find("all",['conditions'=>$condition])->toArray();
			$geo_application_rejected_data	= $this->ApplicationGeoLocation->find("all",['conditions'=>array('application_id'=>$id,'approved'=>2)])->toArray();
			// $geo_application_data = $this->ApplicationGeoLocation->find('all',['fields'=>array('garl.reason'),'join'=>[
			// 			'garl' => [
			// 				'table' => 'geo_application_reject_log',
			// 				'type' => 'left',
			// 				'conditions' => ['ApplicationGeoLocation.id = garl.geo_application_id'],
			// 			]]])->where(['ApplicationGeoLocation.application_id' => $id])->toArray();
			$ApplicationsDocs 				= $this->ApplicationsDocs->find('all',array('conditions'=>array('application_id'=>$id,'doc_type'=>'geo_cordinate_file')))->toArray();
			$count_of_application 			= sizeof($geo_application_data);
			$count_of_rejected_application 	= sizeof($geo_application_rejected_data);
			//$geo_application_payment_done	= $this->ApplicationGeoLocation->find("all",['conditions'=>['application_id'=>$id,'payment_status'=>1,'OR'=>['approved is NULL','approved is NOT'=>1]]])->toArray();
			
			//$geo_developer_payment_pending	= $this->ApplicationGeoLocation->find("all",['conditions'=>['application_id'=>$id,'payment_status'=>1,'approved'=>1]])->toArray();
		}

		$district 					= $this->DistrictMaster->find("list",['keyField'=>'id','valueField'=>'name','conditions'=>['state_id'=>4]])->toArray();
		$taluka 			= $this->TalukaMaster->find("list",['keyField'=>'id','valueField'=>'name','conditions'=>['state_code'=>24]])->toArray();
		$type_manufacturer_wind 	= $this->ManufacturerMaster->manufacturerDropdown(3);
		
		$rlmm 						=array('Y' => 'Yes', 'N' => 'No');
		$type_of_deed 				=array('S' => 'Sale Deed', 'L' => 'Lease Deed','AS' => 'Agreement to Sale with Possession', 'AL' => 'Agreement to Lease with Possession');
		$type_of_land 				=array('G' => 'Goverment Land', 'P' => 'Private Land','GL' => 'Geda Land', 'F' => 'Forest Land');
		$zone_drop_down 			=array('1' => '42 Q', '2' => '43 Q','3' => '42 R - North Gujarat', '4' => '43 R - North Gujarat');
		$applicationCategory 	= $this->ApplicationCategory->find('all',array('conditions'=>array('id'=>$applyOnlinesData->application_type)))->first();
		// $applicationCategory 		= $this->ApplicationCategory->find("all",['conditions'=>['id'=>$applyOnlinesData->application_type]])->first();

		$rejected_application 		= $this->GeoApplicationRejectLog->find('all',array('conditions'=>array('application_id'=>$id)))->toArray();
		$get_offline_data = $this->GeoCoordinateOfflineApproved->find('all',array('conditions'=>array('app_reg_id'=>$id)))->toArray();
		if(!empty($count_of_application)){

			$total_wtg_per = ($total_application->total_wtgno * 10 )/100;
			$total_application->total_wtgno     = ($total_application->total_wtgno + $total_wtg_per - count($get_offline_data));
			$total 		   = ceil($total_application->total_wtgno);
			if(!empty($count_of_rejected_application)){
				$total_wtg_application = ($total - $count_of_application) + $count_of_rejected_application;
			}else{
				$total_wtg_application = ($total - $count_of_application);
			}
			
	  	}else{
	  		$total_wtg_per = ($total_application->total_wtgno * 10 )/100;
			$total_application->total_wtgno     = ($total_application->total_wtgno + $total_wtg_per);
			$total 		   = ceil($total_application->total_wtgno);
			$total_wtg_application = $total ;
			
	  	}
	  	// For clashed Location list //
	  	$query1 = $this->ApplicationGeoLocation->find()->select(['id','wtg_location','application_id','application_type','x_cordinate','y_cordinate'])->where(['application_id IS NOT'=>$id,'approved'=>1]);

		$query2 = $this->GeoCoordinateOfflineApproved->find()->select(['id','wtg_location','application_id','installer_name','x_cordinate','y_cordinate'])->where(['1' => '1']);

		// Combine the two queries using unionAll()
		$query = $query1->unionAll($query2);

		// Execute the query
		$all_geo_application_data_results = $query->toArray();
		
	  	$all_geo_application_data	= $this->ApplicationGeoLocation->find("all",['fields'=>array('id','wtg_location','application_id','x_cordinate','y_cordinate'),'conditions'=>['application_id IS NOT'=>$id,'approved'=>1]])->toArray();
	  	$LocationList = [];
		foreach ($all_geo_application_data_results as $key => $value) {
			if($value['application_id'] == 0){
				$LocationList[$value['id'].'_offline'] = $value['application_type'].'-Wtg Location - ' . $value['wtg_location'] . ' , UTM Easting - ' . $value['x_cordinate']. ' , UTM Northing - ' . $value['y_cordinate'];
				
			}else{
				$LocationList[$value['id']] = $value['application_id'] .'-Wtg Location - ' . $value['wtg_location'] . ' , UTM Easting - ' . $value['x_cordinate']. ' , UTM Northing - ' . $value['y_cordinate'];
			}
		    
		}
		// clashed Location list End//

		// For Internal clashed Location list //
		$query3 = $this->ApplicationGeoLocation->find()->select(['id','wtg_location','application_id','application_type','x_cordinate','y_cordinate'])->where(['application_id'=>$id,'approved'=>1]);

		$query4 = $this->GeoCoordinateOfflineApproved->find()->select(['id','wtg_location','application_id','installer_name','x_cordinate','y_cordinate'])->where(['1' => '1']);

		// Combine the two queries using unionAll()
		$query5 = $query3->unionAll($query4);

		// Execute the query
		$all_geo_application_data_internal_results = $query5->toArray();

		//$all_geo_application_data_internal	= $this->ApplicationGeoLocation->find("all",['fields'=>array('id','application_id','wtg_location','x_cordinate','y_cordinate'),'conditions'=>['application_id'=>$id,'approved'=>1]])->toArray();
	  	$LocationList_internal = [];
		foreach ($all_geo_application_data_internal_results as $key => $value) {
			
		    if($value['application_id'] == 0){
				$LocationList_internal[$value['id'].'_offline'] = $value['application_type'].'-Wtg Location - ' . $value['wtg_location'] . ' , UTM Easting - ' . $value['x_cordinate']. ' , UTM Northing - ' . $value['y_cordinate'];
				
			}else{
				$LocationList_internal[$value['id']] = $value['application_id'] .'-Wtg Location - ' . $value['wtg_location'] . ' , UTM Easting - ' . $value['x_cordinate']. ' , UTM Northing - ' . $value['y_cordinate'];
			}

		}
		// Internal clashed Location list End//

		$geo_application_data_download			= $this->ApplicationGeoLocation->find("all",['conditions'=>array('application_id'=>$id,'approved'=>1)])->first();
		$Geo_application_paymet_log = $this->GeoApplicationPayment->find("all",['conditions'=>['application_id'=>$id,'payment_status'=>'success']])->toArray();

		$Geo_application_verification_log = $this->GeoApplicationVerification->find("all",['conditions'=>['application_id'=>$id,'shifted'=>'No']])->toArray();
		$wtg_make 			= $this->ManufacturerMaster->find("list",['keyField'=>'id','valueField'=>'name'])->toArray();
		$installers_list 		= $this->Developers->getInstallerListReport();
		//echo"<pre>"; print_r($installers_list); die();
		$this->set("Installers",$installers_list);
		$this->set("geo_application_data_download",$geo_application_data_download);
	  	$this->set("LocationList_internal",$LocationList_internal);
	  	$this->set("LocationList",$LocationList);
		$this->set("total_wtg_application",$total_wtg_application);								  	
		$this->set("zone_drop_down",$zone_drop_down);
		$this->set("Geo_application_paymet_log",$Geo_application_paymet_log);
		$this->set("Geo_application_verification_log",$Geo_application_verification_log);
		$this->set("rejected_application",$rejected_application);
		$this->set("applicationCategory",$applicationCategory);
		$this->set('id',$encode_id);
		//$this->set('geo_application_payment_done',$geo_application_payment_done);
		$this->set('wtgno_data',$wtgno_data);
		$this->set('geo_location_charges',$applicationCategory->geo_location_charges);
		$this->set('rlmm',$rlmm);
		$this->set('wtg_make',$wtg_make);
		$this->set('type_of_deed',$type_of_deed);
		$this->set('type_of_land',$type_of_land);
		$this->set('total_wtg',$total_application->total_wtgno);
		$this->set('count_of_application',$count_of_application);
		$this->set('type_manufacturer_wind',$type_manufacturer_wind);
		$this->set("district",$district);
		$this->set("taluka",$taluka);
		$this->set("pageTitle","Add WTG Cordinate");
		$this->set('Applications',$applyOnlinesData);
		$this->set("MemberTypeDiscom",$this->Members->member_type_discom);
		$this->set("APPLY_ONLINE_GUJ_STATUS",$this->ApplicationStages->apply_online_guj_status);
		$this->set("MStatus",$this->ApplicationStages);
		$this->set('logged_in_id',$this->Session->read('Customers.id'));
		$this->set('is_member',$is_member);
		$this->set('encode_id',$encode_id);
		// $this->set('totalModulenos',$totalModulenos);
		// $this->set('totalInverternos',$totalInverternos);
		$this->set('geo_application_data',$geo_application_data);
		$this->set('ApplicationsDocs',$ApplicationsDocs);
		$this->set('Couchdb',$this->ReCouchdb);
		$this->set('ApplicationGeoLocation',$this->ApplicationGeoLocation);
		$this->set('GeoApplicationClashedData',$this->GeoApplicationClashedData);
	}
	/**
	 * geo_location_savedata
	 * Behaviour : Public
	 * @defination : Method is use to save the geo_location data.
	 */
	public function geo_location_savedata()
	{	
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['AddApplication_application_id'])?$this->request->data['AddApplication_application_id']:0);
		$application_type 	= (isset($this->request->data['AddApplication_application_type'])?$this->request->data['AddApplication_application_type']:0);
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
				//||  $this->request->data['x_cordinate'] < 19.00 || $this->request->data['x_cordinate'] >  24.82 || $this->request->data['y_cordinate'] < 68.00 || $this->request->data['y_cordinate'] > 74.62
				if((empty($this->request->data['wtg_location']) || (empty($this->request->data['type_of_land'])) || 
					(empty($this->request->data['land_survey_no'])) || (empty($this->request->data['wtg_validity_date'])) || (empty($this->request->data['land_area'])) || (empty($this->request->data['geo_village'])) || 
					(empty($this->request->data['geo_taluka'])) || (empty($this->request->data['geo_district'])) || (empty($this->request->data['zone'])) || 
					(empty($this->request->data['x_cordinate'])) || (($this->request->data['x_cordinate']) < 111111.000) || (empty($this->request->data['y_cordinate']))|| (($this->request->data['y_cordinate']) < 1111111.000) || (empty($this->request->data['rlmm']))))
				{
						$ErrorMessage 	= "Please select all the details as per Required.";
						$success 		= 0;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
				} elseif (($this->request->data['type_of_land'] == 'G' || $this->request->data['type_of_land'] == 'GL' || $this->request->data['type_of_land'] == 'P' || $this->request->data['type_of_land'] == 'F') && empty($this->request->data['land_per_form']['name'])) {
					  
					  	$ErrorMessage 	= "Please select land permission form Required.";
						$success 		= 0;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
				
				} else {
					if(!empty($this->request->data['type_of_land'])){
						$wtg_validity_date = date('Y-m-d',strtotime($this->request->data['wtg_validity_date']));
						$arr_modules								= $this->ApplicationGeoLocation->newEntity();
						//$arr_modules['wtg_id']						= $this->request->data['wtg_id'];
						$arr_modules['wtg_location']				= $this->request->data['wtg_location'];
						$arr_modules['type_of_land']				= $this->request->data['type_of_land'];
						$arr_modules['land_survey_no']				= $this->request->data['land_survey_no'];
						$arr_modules['land_area']					= $this->request->data['land_area'];
						$arr_modules['wtg_validity_date']			= $wtg_validity_date;
						//$arr_modules['sub_lease_deed']			= $this->request->data['sub_lease_deed'];

						$arr_modules['geo_village']					= $this->request->data['geo_village'];
						$arr_modules['geo_taluka']					= $this->request->data['geo_taluka'];
						$arr_modules['geo_district']				= $this->request->data['geo_district'];

						$arr_modules['zone']						= $this->request->data['zone'];
						$arr_modules['x_cordinate']					= $this->request->data['x_cordinate'];
						$arr_modules['y_cordinate']					= $this->request->data['y_cordinate'];
						$arr_modules['land_per_form']				= $this->request->data['land_per_form'];

						$arr_modules['rlmm']						= $this->request->data['rlmm'];
						if($this->request->data['rlmm'] == 'Y'){
							$arr_modules['wtg_make']					= $this->request->data['wtg_make'];
							$arr_modules['wtg_model']					= $this->request->data['wtg_model'];

							$arr_modules['wtg_capacity']				= $this->request->data['wtg_capacity'];
							$arr_modules['wtg_rotor_dimension']			= $this->request->data['wtg_rotor_dimension'];
							$arr_modules['wtg_hub_height']				= $this->request->data['wtg_hub_height'];
						}else{
							$arr_modules['wtg_make']					= $this->request->data['wtg_make_n'];
							$arr_modules['wtg_model']					= $this->request->data['wtg_model_n'];
							$arr_modules['wtg_file']					= $this->request->data['wtg_file'];
							
							$arr_modules['wtg_capacity']				= $this->request->data['wtg_capacity_n'];
							$arr_modules['wtg_rotor_dimension']			= $this->request->data['wtg_rotor_dimension_n'];
							$arr_modules['wtg_hub_height']				= $this->request->data['wtg_hub_height_n'];
						}
						$this->ApplicationGeoLocation->save_data($id,$arr_modules,$customer_id,$application_type);
						
						
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
	public function geo_location_editdata()
	{	
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['UpdateApplication_application_id'])?$this->request->data['UpdateApplication_application_id']:0);
		$application_type 	= (isset($this->request->data['UpdateApplication_application_type'])?$this->request->data['UpdateApplication_application_type']:0);
		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} 
		else{
			// $encode_id 				= $id;
			// $id 					= intval(decode($id));
			//$applicationData 		= $this->Applications->viewApplication($id);
			$customer_id 			= $this->Session->read("Customers.id");
			$member_id 				= $this->Session->read("Members.id");
			if ( (!empty($customer_id) || !empty($member_id))) {
				// || $this->request->data['x_cordinate'] < 19.00 || $this->request->data['x_cordinate'] >  24.82 || $this->request->data['y_cordinate'] < 68.00 || $this->request->data['y_cordinate'] > 74.62|| (empty($this->request->data['land_per_form']['name']))
				if((empty($this->request->data['wtg_location']) || (empty($this->request->data['type_of_land'])) || (empty($this->request->data['land_survey_no'])) || (empty($this->request->data['wtg_validity_date'])) || (empty($this->request->data['land_area'])) || (empty($this->request->data['geo_village'])) || (empty($this->request->data['geo_taluka'])) || (empty($this->request->data['geo_district'])) || (empty($this->request->data['zone'])) || (empty($this->request->data['x_cordinate'])) || (empty($this->request->data['y_cordinate'])) || (empty($this->request->data['rlmm']))  ))
				{
						$ErrorMessage 	= "Please select all the details.";
						$success 		= 0;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
				} else {
					if(!empty($this->request->data['type_of_land'])){
						//$arr_modules								= $this->ApplicationGeoLocation->newEntity();
						$wtg_validity_date = date('Y-m-d',strtotime($this->request->data['wtg_validity_date']));
						$arr_modules['wtg_location']				= $this->request->data['wtg_location'];
						$arr_modules['type_of_land']				= $this->request->data['type_of_land'];
						$arr_modules['land_survey_no']				= $this->request->data['land_survey_no'];
						$arr_modules['land_area']					= $this->request->data['land_area'];
						$arr_modules['wtg_validity_date']			= $wtg_validity_date;
						//$arr_modules['sub_lease_deed']				= $this->request->data['sub_lease_deed'];

						$arr_modules['geo_village']					= $this->request->data['geo_village'];
						$arr_modules['geo_taluka']					= $this->request->data['geo_taluka'];
						$arr_modules['geo_district']				= $this->request->data['geo_district'];

						$arr_modules['zone']						= $this->request->data['zone'];
						$arr_modules['x_cordinate']					= $this->request->data['x_cordinate'];
						$arr_modules['y_cordinate']					= $this->request->data['y_cordinate'];
						$arr_modules['land_per_form']					= $this->request->data['land_per_form'];

						$arr_modules['rlmm']						= $this->request->data['rlmm'];
						if($this->request->data['rlmm'] == 'Y'){
							$arr_modules['wtg_make']					= $this->request->data['wtg_make'];
							$arr_modules['wtg_model']					= $this->request->data['wtg_model'];

							$arr_modules['wtg_capacity']				= $this->request->data['wtg_capacity'];
							$arr_modules['wtg_rotor_dimension']			= $this->request->data['wtg_rotor_dimension'];
							$arr_modules['wtg_hub_height']				= $this->request->data['wtg_hub_height'];
						}else{
							$arr_modules['wtg_make']					= $this->request->data['wtg_make_n'];
							$arr_modules['wtg_model']					= $this->request->data['wtg_model_n'];
							
							
							$arr_modules['wtg_capacity']				= $this->request->data['wtg_capacity_n'];
							$arr_modules['wtg_rotor_dimension']			= $this->request->data['wtg_rotor_dimension_n'];
							$arr_modules['wtg_hub_height']				= $this->request->data['wtg_hub_height_n'];
						}
						$this->ApplicationGeoLocation->updateAll($arr_modules,array('id'=>$this->request->data['geo_id']));
						//upload_wtg_file - if rlmm is no
						if (isset($this->request->data['wtg_file']) && !empty($this->request->data['wtg_file'])) {

							$arr_modules['wtg_file']			= $this->request->data['wtg_file'];
							$insertId							= $this->request->data['geo_id'];
							$prefix_file 	= '';
							$name 			= $arr_modules['wtg_file']['name'];

							$ext 			= substr(strtolower(strrchr($name, '.')), 1);

							$file_name 		= $prefix_file.date('Ymdhms').rand();
							$uploadPath 	= WTG_PATH.$insertId.'/';
							if(!file_exists(WTG_PATH.$insertId)) {
								@mkdir(WTG_PATH.$insertId, 0777);
							}
							$file_location 	= WWW_ROOT.$uploadPath.'wtg_file'.'_'.$file_name.'.'.$ext;
							
							if(move_uploaded_file($arr_modules['wtg_file']['tmp_name'],$file_location))
							{

								$couchdbId 		= $this->ReCouchdb->saveData($uploadPath,$file_location,$prefix_file,'wtg_file_'.$file_name.'.'.$ext,$customer_id,'wtg_file');
								
								$this->ApplicationGeoLocation->updateAll(array("wtg_file" =>'wtg_file'.'_'.$file_name.'.'.$ext,"wtg_file_type" =>'wtg_file','couchdb_id'=>$couchdbId),array("id" => $insertId));
								
							}
						}
						if (isset($this->request->data['land_per_form']) && !empty($this->request->data['land_per_form'])) {

							$arr_modules['land_per_form']			= $this->request->data['land_per_form'];
							$insertId							= $this->request->data['geo_id'];
							$prefix_file 	= '';
							$name 			= $arr_modules['land_per_form']['name'];

							$ext 			= substr(strtolower(strrchr($name, '.')), 1);

							$file_name 		= $prefix_file.date('Ymdhms').rand();
							$uploadPath 	= WTG_PATH.$insertId.'/';
							if(!file_exists(WTG_PATH.$insertId)) {
								@mkdir(WTG_PATH.$insertId, 0777);
							}
							$file_location 	= WWW_ROOT.$uploadPath.'land_per_form'.'_'.$file_name.'.'.$ext;
							
							if(move_uploaded_file($arr_modules['land_per_form']['tmp_name'],$file_location))
							{

								$land_per_form_couchdbId 		= $this->ReCouchdb->saveData($uploadPath,$file_location,$prefix_file,'land_per_form_'.$file_name.'.'.$ext,$customer_id,'land_per_form');
								
								$this->ApplicationGeoLocation->updateAll(array("land_per_form" =>'land_per_form'.'_'.$file_name.'.'.$ext,"land_per_form_type" =>'land_per_form','couchdb_id'=>$land_per_form_couchdbId),array("id" => $insertId));
								
							}
						}
						
						$ErrorMessage 	= "Record Updated Succesfully";
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
	 * GeoFileDocument
	 * Behaviour : Public
	 * @defination : Method is use to upload drwaing file certificate.
	 */
	public function GeoFileDocument()
	{	
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['GeoFileForm_application_id'])?$this->request->data['GeoFileForm_application_id']:0);

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
					if((empty($this->request->data['geo_cordinate_file']['name']))) {
						$ErrorMessage 	= "Please select KMZ file.";
						$success 		= 0;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					} else {
						if(!empty($this->request->data['geo_cordinate_file']['name']))
						{	$ApplicationsDocsEntity = $this->ApplicationsDocs->newEntity();
							$prefix_file 	= '';
							$name 			= $this->request->data['geo_cordinate_file']['name'];

							$ext 			= substr(strtolower(strrchr($name, '.')), 1);

							$file_name 		= $prefix_file.date('Ymdhms').rand();
							$uploadPath 	= APPLICATIONS_PATH.$id.'/';
							if(!file_exists(APPLICATIONS_PATH.$id)) {
								@mkdir(APPLICATIONS_PATH.$id, 0777);
							}
							$file_location 	= WWW_ROOT.$uploadPath.'geo_cordinate_file'.'_'.$file_name.'.'.$ext;
							if(move_uploaded_file($this->request->data['geo_cordinate_file']['tmp_name'],$file_location))
							{

								$couchdbId 		= $this->ReCouchdb->saveData($uploadPath,$file_location,$prefix_file, 
									'geo_cordinate_file_'.$file_name.'.'.$ext,$this->Session->read('Customers.id'),'geo_cordinate_file');
								$ApplicationsDocsEntity->couchdb_id			= $couchdbId;
								$ApplicationsDocsEntity->application_id		= $id;
								$ApplicationsDocsEntity->file_name        	= 'geo_cordinate_file'.'_'.$file_name.'.'.$ext;
								$ApplicationsDocsEntity->doc_type         	= 'geo_cordinate_file';
								$ApplicationsDocsEntity->title            	= 'geo_cordinate_file';
								$ApplicationsDocsEntity->created          	= $this->NOW();
								$ApplicationsDocsEntity->created_by         = $customer_id;
								$application_status 						= $this->ApplicationStages->CONNECTIVITY_STEP1;
								
							}
							$this->ApplicationsDocs->save($ApplicationsDocsEntity);
						}
						
						if($this->ApplicationsDocs->save($ApplicationsDocsEntity)) {
							//$this->Applications->updateAll(array('application_status'=>$application_status,'modified'=>$this->NOW(),'modified_by'=>$customer_id),array('id'=>$id));
							//$this->ApplicationStages->saveStatus($id,$this->ApplicationStages->CONNECTIVITY_STEP1,$customer_id,'');
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
	 * geo_location_approvedata
	 * Behaviour : Public
	 * @defination : Method is use to edit the geo_location data.
	 */
	public function geo_location_approvedata()
	{	
		$this->autoRender 	= false;
		$geo_id 				= (isset($this->request->data['GeoApprove_application_id'])?$this->request->data['GeoApprove_application_id']:0);
		
		if(empty($geo_id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		}	else{
			$member_id 				= $this->Session->read("Members.id");
			 if(!empty($member_id)) {
				if(!empty($geo_id)){
					$arr_modules['approved']				= 1;
					$arr_modules['approved_by']				= $member_id;
					$arr_modules['approved_date']			= $this->NOW();
					
					$this->ApplicationGeoLocation->updateAll($arr_modules,array('id'=>$geo_id));

					$geo_clashed_data   = $this->GeoApplicationClashedData->find('all',array('conditions'=>array('clashed_geo_id'=>$geo_id)))->first();
					if(!empty($geo_clashed_data)){
						$arr_moduless['approved']			= 1;
						$arr_moduless['status']				= 1;

						$this->GeoApplicationClashedData->updateAll($arr_moduless,array('clashed_geo_id'=>$geo_id));
					}

					$ErrorMessage 	= "Record Approved Succesfully";
					$success 		= 1;
					$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
					$this->ApiToken->SetAPIResponse('success',$success);
				}
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
	
	/**
	 * geo_location_rejectdata
	 * Behaviour : Public
	 * @defination : Method is use to edit the geo_location data.
	 */
	public function geo_location_rejectdata()
	{	
		$this->autoRender 	= false;
		$geo_application_id 				= (isset($this->request->data['GeoReject_application_id'])?$this->request->data['GeoReject_application_id']:0);
		
		if(empty($geo_application_id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		}	else{
			
			$member_id 				= $this->Session->read("Members.id");
			$geo_application_data   = $this->ApplicationGeoLocation->find('all',array('conditions'=>array('id'=>$geo_application_id)))->first();

				$browser 					   		= isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:"-";
				$rejectentity                  		= $this->GeoApplicationRejectLog->newEntity();
				$rejectentity->geo_application_id  	= $geo_application_id;
				$rejectentity->application_id  		= $geo_application_data->application_id;
				$rejectentity->member_id     		= $member_id;
				$rejectentity->ip_address      		= $_SERVER['REMOTE_ADDR'];
				$rejectentity->reject_reason      	= $this->request->data['reject_reason'];
				$rejectentity->browser_info	   		= json_encode($browser);
				$rejectentity->application_data		= json_encode($geo_application_data);
				$rejectentity->created 		   		= $this->NOW();
				$this->GeoApplicationRejectLog->save($rejectentity);
				
			 if(!empty($member_id)) {
				if(!empty($this->request->data['reject_reason'])){

					$arr_modules['approved']				= 2;
					$arr_modules['payment_status']		    = 2;
					//$arr_modules['payment_date']			= '0000-00-00 00:00:00';
					$arr_modules['approved_by']				= $member_id;
					$arr_modules['approved_date']			= $this->NOW();
					
					$this->ApplicationGeoLocation->updateAll($arr_modules,array('id'=>$geo_application_id));
					$ErrorMessage 	= "Record Rejected Succesfully";
					$success 		= 1;
					$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
					$this->ApiToken->SetAPIResponse('success',$success);
				}
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

	/**
	 * geo_location_approvedata
	 * Behaviour : Public
	 * @defination : Method is use to edit the geo_location data.
	 */
	public function rejectedData()
	{	
		$this->autoRender 	= false;
		$geo_id 				= (isset($this->request->data['geo_id'])?$this->request->data['geo_id']:0);
		
		if(empty($geo_id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		}	else{
			//$encode_id 				= $id;
			//$id 					= intval(decode($id));
			$member_id 				= $this->Session->read("Members.id");
			$geo_application_data   = $this->GeoApplicationRejectLog->find('all',array('conditions'=>array('geo_application_id'=>$geo_id)))->toArray();
			foreach ($geo_application_data as $key => $value) {
				$reject_reason[] = $value['reject_reason'];
			}
			$all_reason = "<pre>".implode(",\n",$reject_reason)."</pre>";

			if(!empty($geo_application_data)) {
				
					$data 	= $all_reason;
					$success 		= 1;
					$this->ApiToken->SetAPIResponse('message',$data);
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
	/**
	 * geo_location_approvedata
	 * Behaviour : Public
	 * @defination : Method is use to edit the geo_location data.
	 */
	public function clashedData()
	{	
		$this->autoRender 	= false;
		$geo_id 				= (isset($this->request->data['geo_id'])?$this->request->data['geo_id']:0);
		if(empty($geo_id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		}	else{

			 	$geo_application_data = $this->GeoApplicationClashedData->find('all',
	                                        [ 'fields'=>['clashed_geo_id','wtg_location','zone','x_cordinate','y_cordinate','developers.installer_name','remark'],
	                                            'join'=>[['table'=>'applications','type'=>'left','conditions'=>'GeoApplicationClashedData.application_id = applications.id'], ['table'=>'developers','type'=>'left','conditions'=>'applications.installer_id = developers.id'],
	                                        ['table'=>'geo_coordinate_offline_approved','type'=>'left','conditions'=>'GeoApplicationClashedData.approved_geo_offline_id = geo_coordinate_offline_approved.id']],
	                                            'conditions'=>['clashed_geo_id'=>$geo_id]])->toArray();	
		
				foreach ($geo_application_data as $key => $value) {
					$wtg_location[]		= $value['wtg_location'];
					$developer_name[]	= $value['developers']['installer_name'];
					$zone[]				= $value['zone'];
					$x_cordinate[]		= $value['x_cordinate'];
					$y_cordinate[]		= $value['y_cordinate'];
					$remark 			= $value['remark'];
				}
				
				$wtg_location = implode(',', $wtg_location);

				$developer_name = implode(',', $developer_name);

				$zone = implode(',', $zone);

				$x_cordinate = implode(',', $x_cordinate);

				$y_cordinate = implode(',', $y_cordinate);

				$clashed_reason = "WTG Coordinates Clashing with <br>Developer Name : " .$developer_name ." <br>location       : " .$wtg_location ." <br>Zone           : " .$zone ." <br>UTM Easting    : " . $x_cordinate . "<br>UTM Northing   : ". $y_cordinate . "<br>GEDA Remark    : ". $remark;

			if(!empty($geo_application_data)) {
				
					$data 	=   '<html>
									<body style="width:25px;">
         								<pre>'.$clashed_reason.'</pre>
         								
         							</body>
         						</html>';
					$success 		= 1;
					$this->ApiToken->SetAPIResponse('message',$data);
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
	/**
	 * InternalclashedData
	 * Behaviour : Public
	 * @defination : Method is use to get the InternalclashedData.
	 */
	public function InternalclashedData()
	{	
		$this->autoRender 	= false;
		$geo_id 				= (isset($this->request->data['geo_id'])?$this->request->data['geo_id']:0);

		if(empty($geo_id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		}	else{

			    $geo_application_data = $this->GeoApplicationClashedData->find('all',
	                                        [ 'fields'=>['clashed_geo_id','wtg_location','zone','x_cordinate','y_cordinate','developers.installer_name','remark'],
	                                            'join'=>[['table'=>'applications','type'=>'left','conditions'=>'GeoApplicationClashedData.application_id = applications.id'], ['table'=>'developers','type'=>'left','conditions'=>'applications.installer_id = developers.id'],
	                                        ['table'=>'geo_coordinate_offline_approved','type'=>'left','conditions'=>'GeoApplicationClashedData.approved_geo_offline_id = geo_coordinate_offline_approved.id']],
	                                            'conditions'=>['clashed_geo_id'=>$geo_id]])->toArray();	
		
				foreach ($geo_application_data as $key => $value) {
					$wtg_location[]		= $value['wtg_location'];
					$developer_name[]	= $value['developers']['installer_name'];
					$zone[]				= $value['zone'];
					$x_cordinate[]		= $value['x_cordinate'];
					$y_cordinate[]		= $value['y_cordinate'];
					$remark 			= $value['remark'];
				}
				
				$wtg_location = implode(',', $wtg_location);

				$developer_name = implode(',', $developer_name);

				$zone = implode(',', $zone);

				$x_cordinate = implode(',', $x_cordinate);

				$y_cordinate = implode(',', $y_cordinate);

				
				$clashed_reason = "WTG Coordinates Internal Clashing with <br>Developer Name : " .$developer_name ." <br>location       : " .$wtg_location ." <br>Zone           : " .$zone ." <br>UTM Easting    : " . $x_cordinate . "<br>UTM Northing   : ". $y_cordinate . "<br>GEDA Remark    : ". $remark;


			if(!empty($geo_application_data)) {
				
					$data 	=   '<html>
									<body style="width:25px;">
         								<pre>'.$clashed_reason.'</pre>
         								
         							</body>
         						</html>';
     				$success 		= 1;
					$this->ApiToken->SetAPIResponse('message',$data);
					$this->ApiToken->SetAPIResponse('geo_id',$geo_id);
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
	/**
	* downloadGeoApplicationPdf
	* Behaviour : Public
	* @defination : Method is use to download geo application pdf
	*/
	public function downloadGeoApplicationPdf($id = null)
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
		$application_data = $this->Applications->generateGeoPaymentReceiptPdf($id);
		if(empty($application_data)) {
			$this->Flash->error('Please Select Valid Application.');
			return $this->redirect('home');
		}
	}
	/**
	* downloadGeoApplicationVerifiedPdf
	* Behaviour : Public
	* @defination : Method is use to download geo application pdf
	*/
	public function downloadGeoApplicationVerifiedPdf($id = null)
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
		$application_data = $this->ApplicationGeoLocation->generateGeoApplicationVerifiedPdf($id);
		if(empty($application_data)) {
			$this->Flash->error('Please Select Valid Application.');
			return $this->redirect('home');
		}
	}

	/**
	 * developer_accept_data
	 * Behaviour : Public
	 * @defination : Method is use to accept the developer_accept_data data.
	 */
	public function developer_accept_data()
	{	
		$this->autoRender 	= false;
		$geo_id 				= (isset($this->request->data['DeveloperAccept_application_id'])?$this->request->data['DeveloperAccept_application_id']:0);
		
		if(empty($geo_id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		}	else{
			//$member_id 				= $this->Session->read("Members.id");
			if(!empty($geo_id)){
				$arr_modules['developer_action_status']			= Developer_Accept;
				$arr_modules['developer_action_status_date']	= $this->NOW();
				$arr_modules['approved']						= NULL;
				
				$this->ApplicationGeoLocation->updateAll($arr_modules,array('id'=>$geo_id));
				$geo_clashed_data   = $this->GeoApplicationClashedData->find('all',array('conditions'=>array('clashed_geo_id'=>$geo_id)))->first();
				if(!empty($geo_clashed_data)){
					$arr_moduless['approved']						= 4;
					//$arr_moduless['status']							= 2;

					$this->GeoApplicationClashedData->updateAll($arr_moduless,array('clashed_geo_id'=>$geo_id));
				}
				$ErrorMessage 	= "Record Updated Succesfully";
				$success 		= 1;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	 * developer_reject_data
	 * Behaviour : Public
	 * @defination : Method is use to reject the developer_reject_data data.
	 */

	public function developer_reject_data()
	{	
		$this->autoRender 	= false;
		$geo_application_id 				= (isset($this->request->data['DeveloperReject_application_id'])?$this->request->data['DeveloperReject_application_id']:0);
		
		if(empty($geo_application_id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		}	else{
			$member_id 				= $this->Session->read("Members.id");
			$geo_application_data   = $this->ApplicationGeoLocation->find('all',array('conditions'=>array('id'=>$geo_application_id)))->first();

	
				$browser 					   		= isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:"-";
				$rejectentity                  		= $this->GeoApplicationRejectLog->newEntity();
				$rejectentity->geo_application_id  	= $geo_application_id;
				$rejectentity->application_id  		= $geo_application_data->application_id;
				//$rejectentity->member_id     		= $member_id;
				$rejectentity->ip_address      		= $_SERVER['REMOTE_ADDR'];
				$rejectentity->reject_reason      	= $this->request->data['reject_reason'];
				$rejectentity->browser_info	   		= json_encode($browser);
				$rejectentity->application_data		= json_encode($geo_application_data);
				$rejectentity->created 		   		= $this->NOW();
				
				$this->GeoApplicationRejectLog->save($rejectentity);
				
				if(!empty($this->request->data['reject_reason'])){

					$arr_modules['developer_action_status']			= Developer_Reject;
					$arr_modules['developer_action_status_date']	= $this->NOW();
					$arr_modules['approved']						= Developer_Reject;
					$arr_modules['payment_status']					= 2;
					//$arr_modules['payment_date']					= '0000-00-00 00:00:00';
					$arr_modules['approved_by']						= $member_id;
					$arr_modules['approved_date']					= $this->NOW();
					
					$this->ApplicationGeoLocation->updateAll($arr_modules,array('id'=>$geo_application_id));

					$geo_clashed_data   = $this->GeoApplicationClashedData->find('all',array('conditions'=>array('clashed_geo_id'=>$geo_application_id)))->first();
					if(!empty($geo_clashed_data)){
						$arr_moduless['approved']						= Developer_Reject;
						$arr_moduless['status']							= Developer_Reject;
						$this->GeoApplicationClashedData->updateAll($arr_moduless,array('clashed_geo_id'=>$geo_application_id));
					}

					$ErrorMessage 	= "Record Rejected Succesfully";
					$success 		= 1;
					$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
					$this->ApiToken->SetAPIResponse('success',$success);
				}
			
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	
	/**
	 * geo_location_clashdata
	 * Behaviour : Public
	 * @defination : Method is use to get the geo_location_clashdata data.
	 */
	public function geo_location_clashdata()
	{	
		$this->autoRender 	= false;
		$geo_application_id = (isset($this->request->data['GeoClash_geo_id'])?$this->request->data['GeoClash_geo_id']:0);
		$approved_geo_ids 	= (isset($this->request->data['approved_geo_id'])?$this->request->data['approved_geo_id']:0);
		$clashed_remark 	= (isset($this->request->data['clashed_remark'])?$this->request->data['clashed_remark']:'');

		if(empty($geo_application_id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		}else{
			$member_id 				= $this->Session->read("Members.id");
			if(!empty($member_id)) {

				//$geo_application_data   = $this->ApplicationGeoLocation->find('all',array('conditions'=>array('id'=>$approved_geo_id)))->first();
				foreach ($approved_geo_ids as $key => $approved_geo_id) {
				
					if (strpos($approved_geo_id, '_') !== false) {

						$approved_geo_offline_id = explode("_offline",$approved_geo_id);
						
						$approved_geo_offline_id = $approved_geo_offline_id[0];
						
					    $geo_application_data   = $this->GeoCoordinateOfflineApproved->find('all',array('conditions'=>array('id'=>$approved_geo_offline_id)))->first();

					   $zonearray = array(1 => '42 Q', 2 => '43 Q',3 => '42 R - North Gujarat', 4 => '43 R - North Gujarat');
						// Key to check
						$keyToCheck = $geo_application_data->zone;
						if (array_key_exists($keyToCheck, $zonearray)) {
						    // Display the value corresponding to the key
						     $zone = $zonearray[$keyToCheck]; 
						}

						$clashentity                  		= $this->GeoApplicationClashedData->newEntity();
						$clashentity->clashed_geo_id  		= $geo_application_id;
						$clashentity->approved_geo_offline_id = $approved_geo_offline_id; 
						$clashentity->zone  				= $zone;
						$clashentity->x_cordinate  			= $geo_application_data->x_cordinate;
						$clashentity->y_cordinate  			= $geo_application_data->y_cordinate;
						$clashentity->wtg_location  		= $geo_application_data->wtg_location;
						$clashentity->remark				= $clashed_remark;
						$clashentity->clashed_for			= 1;
						$clashentity->created 		   		= $this->NOW();
						$this->GeoApplicationClashedData->save($clashentity);

					} else {

					    $geo_application_details   = $this->ApplicationGeoLocation->find('all',array('conditions'=>array('id'=>$approved_geo_id)))->first();

					    $zonearray = array(1 => '42 Q', 2 => '43 Q',3 => '42 R - North Gujarat', 4 => '43 R - North Gujarat');
						// Key to check
						$keyToCheck = $geo_application_details->zone;
						if (array_key_exists($keyToCheck, $zonearray)) {
						    // Display the value corresponding to the key
						     $zone = $zonearray[$keyToCheck]; 
						}

						$clashentity                  		= $this->GeoApplicationClashedData->newEntity();
						$clashentity->clashed_geo_id  		= $geo_application_id;
						$clashentity->application_id  		= $geo_application_details->application_id;
						$clashentity->approved_geo_id  		= $approved_geo_id; 
						$clashentity->zone  				= $zone;
						$clashentity->x_cordinate  			= $geo_application_details->x_cordinate;
						$clashentity->y_cordinate  			= $geo_application_details->y_cordinate;
						$clashentity->wtg_location  		= $geo_application_details->wtg_location;
						$clashentity->remark				= $clashed_remark;
						$clashentity->clashed_for			= 1;
						$clashentity->created 		   		= $this->NOW();
						$this->GeoApplicationClashedData->save($clashentity);
					}
	
				}
				//start
				
					$this->Add_clashed_data($geo_application_id);
					$ErrorMessage 	= "Clash Data Added Succesfully.";
					$success 		= 1;
					$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
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


	public function Add_clashed_data($geo_application_id){

		$geo_application_data = $this->GeoApplicationClashedData->find('all',
		                                        [ 'fields'=>['clashed_geo_id','wtg_location','zone','x_cordinate','y_cordinate','developers.installer_name','remark'],
		                                            'join'=>[['table'=>'applications','type'=>'left','conditions'=>'GeoApplicationClashedData.application_id = applications.id'], ['table'=>'developers','type'=>'left','conditions'=>'applications.installer_id = developers.id'],
		                                        ['table'=>'geo_coordinate_offline_approved','type'=>'left','conditions'=>'GeoApplicationClashedData.approved_geo_offline_id = geo_coordinate_offline_approved.id']],
		                                            'conditions'=>['clashed_geo_id'=>$geo_application_id]])->toArray();	
			
		foreach($geo_application_data as $key => $value){

			$wtg_location[]		= $value['wtg_location'];
			$developer_name[]	= $value['developers']['installer_name'];
			$zone[]				= $value['zone'];
			$x_cordinate[]		= $value['x_cordinate'];
			$y_cordinate[]		= $value['y_cordinate'];
			$remark 			= $value['remark'];
		}
		
		$wtg_location = implode(',', $wtg_location);
		$developer_name = implode(',', $developer_name);
		$zone = implode(',', $zone);
		$x_cordinate = implode(',', $x_cordinate);
		$y_cordinate = implode(',', $y_cordinate);

		$clashed_reason = "WTG Coordinates Clashing with : " .$developer_name .", location : " .$wtg_location .", Zone : " .$zone .", UTM Easting : " . $x_cordinate . ", UTM Northing : ". $y_cordinate. ", GEDA Remark : ". $remark;
		$arr_module['comment'] = $clashed_reason;
		$this->ApplicationGeoLocation->updateAll($arr_module,array('id'=>$geo_application_id));
	}
	/**
	 * geo_location_clashdata_internal
	 * Behaviour : Public
	 * @defination : Method is use to Add internal clashed Data in the geo_location data.
	 */
	public function geo_location_clashdata_internal()
	{	
		$this->autoRender 	= false;
		$geo_application_id 				= (isset($this->request->data['GeoClashInternal_geo_id'])?$this->request->data['GeoClashInternal_geo_id']:0);
		$approved_geo_ids 				= (isset($this->request->data['approved_geo_id'])?$this->request->data['approved_geo_id']:0);
		$internal_clashed_remark 	= (isset($this->request->data['internal_clashed_remark'])?$this->request->data['internal_clashed_remark']:'');
		if(empty($geo_application_id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		}else{
			$member_id 				= $this->Session->read("Members.id");

			 if(!empty($member_id)) {

			 	foreach ($approved_geo_ids as $key => $approved_geo_id) {
				 	if (strpos($approved_geo_id, '_') !== false) {

						$approved_geo_offline_id = explode("_offline",$approved_geo_id);
						
						$approved_geo_offline_id = $approved_geo_offline_id[0];
						
					    $geo_application_data   = $this->GeoCoordinateOfflineApproved->find('all',array('conditions'=>array('id'=>$approved_geo_offline_id)))->first();

					   $zonearray = array(1 => '42 Q', 2 => '43 Q',3 => '42 R - North Gujarat', 4 => '43 R - North Gujarat');
						// Key to check
						$keyToCheck = $geo_application_data->zone;
						if (array_key_exists($keyToCheck, $zonearray)) {
						    // Display the value corresponding to the key
						     $zone = $zonearray[$keyToCheck]; 
						}

						$clashentity                  		= $this->GeoApplicationClashedData->newEntity();
						$clashentity->clashed_geo_id  		= $geo_application_id;
						$clashentity->approved_geo_offline_id = $approved_geo_offline_id; 
						$clashentity->zone  				= $zone;
						$clashentity->x_cordinate  			= $geo_application_data->x_cordinate;
						$clashentity->y_cordinate  			= $geo_application_data->y_cordinate;
						$clashentity->wtg_location  		= $geo_application_data->wtg_location;
						$clashentity->remark				= $internal_clashed_remark;
						$clashentity->clashed_for			= 2;
						$clashentity->created 		   		= $this->NOW();
						$this->GeoApplicationClashedData->save($clashentity);

					} else {

					    $geo_application_data   = $this->ApplicationGeoLocation->find('all',array('conditions'=>array('id'=>$approved_geo_id)))->first();


						$arr_moduless['approved']			= 5;
						$this->ApplicationGeoLocation->updateAll($arr_moduless,array('id'=>$geo_application_id));


						$zonearray = array(1 => '42 Q', 2 => '43 Q',3 => '42 R - North Gujarat', 4 => '43 R - North Gujarat');
						// Key to check
						$keyToCheck = $geo_application_data->zone;
						if (array_key_exists($keyToCheck, $zonearray)) {
						    // Display the value corresponding to the key
						     $zone = $zonearray[$keyToCheck]; 
						}
						$clashentity                  		= $this->GeoApplicationClashedData->newEntity();
						$clashentity->clashed_geo_id  		= $geo_application_id;
						$clashentity->application_id  		= $geo_application_data->application_id;
						$clashentity->approved_geo_id  		= $approved_geo_id; 
						$clashentity->zone  				= $zone;
						$clashentity->x_cordinate  			= $geo_application_data->x_cordinate;
						$clashentity->y_cordinate  			= $geo_application_data->y_cordinate;
						$clashentity->wtg_location  		= $geo_application_data->wtg_location;
						$clashentity->remark				= $internal_clashed_remark;
						$clashentity->clashed_for			= 2;
						$clashentity->created 		   		= $this->NOW();
						$this->GeoApplicationClashedData->save($clashentity);
					}

	     		}

	     		//start
				$this->Add_internalclashed_data($geo_application_id);
				//end
				$ErrorMessage 	= "Clash Data Added Succesfully.";
				$success 		= 1;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
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

	public function Add_internalclashed_data($geo_application_id){

		$geo_application_data = $this->GeoApplicationClashedData->find('all',
	                                        [ 'fields'=>['clashed_geo_id','wtg_location','zone','x_cordinate','y_cordinate','developers.installer_name','remark'],
	                                            'join'=>[['table'=>'applications','type'=>'left','conditions'=>'GeoApplicationClashedData.application_id = applications.id'], ['table'=>'developers','type'=>'left','conditions'=>'applications.installer_id = developers.id'],
	                                        ['table'=>'geo_coordinate_offline_approved','type'=>'left','conditions'=>'GeoApplicationClashedData.approved_geo_offline_id = geo_coordinate_offline_approved.id']],
	                                            'conditions'=>['clashed_geo_id'=>$geo_application_id]])->toArray();	
		
		foreach ($geo_application_data as $key => $value) {
			$wtg_location[]		= $value['wtg_location'];
			$developer_name[]	= $value['developers']['installer_name'];
			$zone[]				= $value['zone'];
			$x_cordinate[]		= $value['x_cordinate'];
			$y_cordinate[]		= $value['y_cordinate'];
			$remark 			= $value['remark'];
		}
		
		$wtg_location = implode(',', $wtg_location);
		$developer_name = implode(',', $developer_name);
		$zone = implode(',', $zone);
		$x_cordinate = implode(',', $x_cordinate);
		$y_cordinate = implode(',', $y_cordinate);

		
		$clashed_reason = "WTG Coordinates Internal Clashing with : " .$developer_name .", location : " .$wtg_location .", Zone : " .$zone .", UTM Easting : " . $x_cordinate . ", UTM Northing : ". $y_cordinate. ", GEDA Remark : ". $remark;


		$arr_module['comment'] = $clashed_reason;
		$this->ApplicationGeoLocation->updateAll($arr_module,array('id'=>$geo_application_id));
	}
	/**
	* downloadGEOLetterPdf
	* Behaviour : Public
	* @defination : Method is use to view installer
	*/
	public function downloadGEOLetterPdf($id = null)
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
		$application_data = $this->ApplicationGeoLocation->generateGEOLetterPdf($id);
		if(empty($application_data)) {
			$this->Flash->error('Please Select Valid Application.');
			return $this->redirect('home');
		}
	}
    
    /**
	* downloadGEOLetterPdf
	* Behaviour : Public
	* @defination : Method is use to view installer
	*/
	public function downloadGEOLocation($id = null)
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
		$application_data = $this->ApplicationGeoLocation->generateGeoApplicationPdf($id);
		if(empty($application_data)) {
			$this->Flash->error('Please Select Valid Application.');
			return $this->redirect('home');
		}
	}

	/**
	 * geo_location_approvedata
	 * Behaviour : Public
	 * @defination : Method is use to edit the geo_location data.
	 */
	public function locationlistData()
	{	
		$this->autoRender 	= false;
		$geo_id 			= (isset($this->request->data['geo_id'])?$this->request->data['geo_id']:0);
		$application_id 	= (isset($this->request->data['application_id'])?$this->request->data['application_id']:0);

		if(empty($geo_id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		}	else{
			$member_id 				= $this->Session->read("Members.id");


			// For clashed Location list //
		  	$query1 = $this->ApplicationGeoLocation->find("all",['fields'=>array('id','wtg_location','application_id','application_type','x_cordinate','y_cordinate'),'conditions'=>['application_id IS NOT'=>$application_id,'approved'=>1]]);

			$query2 = $this->GeoCoordinateOfflineApproved->find()->select(['id','wtg_location','application_id','installer_name','x_cordinate','y_cordinate'])->where(['1' => '1']);

			// Combine the two queries using unionAll()
			$query = $query1->unionAll($query2);

			// Execute the query
			$all_geo_application_data_results = $query->toArray();
			
			$all_geo_application_data	= $this->ApplicationGeoLocation->find("all",['fields'=>array('id','wtg_location','application_id','x_cordinate','y_cordinate'),'conditions'=>['application_id IS NOT'=>$application_id,'approved'=>1]])->toArray();
		  	$LocationList = [];
			foreach ($all_geo_application_data_results as $key => $value) {
				
				if($value['application_id'] == 0){
					$LocationList[$value['id'].'_offline'] = $value['application_type'].'-Wtg Location - ' . $value['wtg_location'] . ' , UTM Easting - ' . $value['x_cordinate']. ' , UTM Northing - ' . $value['y_cordinate'];
					
				}else{
					$LocationList[$value['id']] = $value['application_id'] .'-Wtg Location - ' . $value['wtg_location'] . ' , UTM Easting - ' . $value['x_cordinate']. ' , UTM Northing - ' . $value['y_cordinate'];
				}

			    //$LocationList[$value['id']] = (($value['application_id'] !=0)  ? $value['application_id'] : $value['application_type'] ) .'-Wtg Location - ' . $value['wtg_location'] . ' , UTM Easting - ' . $value['x_cordinate']. ' , UTM Northing - ' . $value['y_cordinate'];
			}

			if(!empty($application_id)) {
				
					$data 			= $LocationList;
					$success 		= 1;
					$this->ApiToken->SetAPIResponse('message',$data);
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

	public function InternallocationlistData()
	{	
		$this->autoRender 	= false;
		$geo_id 			= (isset($this->request->data['geo_id'])?$this->request->data['geo_id']:0);
		$application_id 	= (isset($this->request->data['application_id'])?$this->request->data['application_id']:0);

		if(empty($geo_id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		}	else{
			
			$member_id 					= $this->Session->read("Members.id");

			// For Internal clashed Location list //
			$query3 = $this->ApplicationGeoLocation->find("all",['fields'=>array('id','wtg_location','application_id','application_type','x_cordinate','y_cordinate'),'conditions'=>['application_id'=>$application_id,'approved'=>1]]);

			$query4 = $this->GeoCoordinateOfflineApproved->find()->select(['id','wtg_location','application_id','installer_name','x_cordinate','y_cordinate'])->where(['1' => '1']);

			// Combine the two queries using unionAll()
			$query5 = $query3->unionAll($query4);

			// Execute the query
			$all_geo_application_data_internal_results = $query5->toArray();
				
			$LocationList_internal = [];
			foreach ($all_geo_application_data_internal_results as $key => $value) {
				if($value['application_id'] == 0){
					$LocationList_internal[$value['id'].'_offline'] = $value['application_type'].'-Wtg Location - ' . $value['wtg_location'] . ' , UTM Easting - ' . $value['x_cordinate']. ' , UTM Northing - ' . $value['y_cordinate'];
					
				}else{
					$LocationList_internal[$value['id']] = $value['application_id'] .'-Wtg Location - ' . $value['wtg_location'] . ' , UTM Easting - ' . $value['x_cordinate']. ' , UTM Northing - ' . $value['y_cordinate'];
				}
			    //$LocationList_internal[$value['id']] = (($value['application_id'] !=0)  ? $value['application_id'] : $value['application_type'] ) .'-Wtg Location - ' . $value['wtg_location'] . ' , UTM Easting - ' . $value['x_cordinate']. ' , UTM Northing - ' . $value['y_cordinate'];

			}
			// Internal clashed Location list End//

			if(!empty($application_id)) {
				
					$data 			= $LocationList_internal;
					$success 		= 1;
					$this->ApiToken->SetAPIResponse('message',$data);
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

	/**
	 * developer_InternalClashed_data
	 * Behaviour : Public
	 * @defination : Method is use to accept the developer_InternalClashed_data.
	 */
	public function developer_InternalClashed_data()
	{	
		$this->autoRender 	= false;
		$geo_id 				= (isset($this->request->data['InternalClashed_geo_id'])?$this->request->data['InternalClashed_geo_id']:0);

		if(empty($geo_id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		}	else{
			//$member_id 				= $this->Session->read("Members.id");
			$customer_id 			= $this->Session->read("Customers.id");

		
			if(!empty($geo_id)){
				$geo_application_data   = $this->ApplicationGeoLocation->find('all',array('conditions'=>array('id'=>$geo_id)))->first();
				
				if (isset($this->request->data['uploadfile']) && !empty($this->request->data['uploadfile'])) {
							$geo_clashed_data   = $this->GeoApplicationClashedData->find('all',array('conditions'=>array('clashed_geo_id'=>$geo_id,'clashed_for'=>2)))->first();
							$arr_modules['uploadfile']			= $this->request->data['uploadfile'];
							//$insertId							= $this->request->data['geo_id'];
							$prefix_file 	= '';
							$name 			= $arr_modules['uploadfile']['name'];

							$ext 			= substr(strtolower(strrchr($name, '.')), 1);

							$file_name 		= $prefix_file.date('Ymdhms').rand();
							$uploadPath 	= Internal_Clashed_PATH.$geo_id.'/';
							if(!file_exists(Internal_Clashed_PATH.$geo_id)) {
								@mkdir(Internal_Clashed_PATH.$geo_id, 0777);
							}
							$file_location 	= WWW_ROOT.$uploadPath.'uploadfile'.'_'.$file_name.'.'.$ext;
							
							if(move_uploaded_file($arr_modules['uploadfile']['tmp_name'],$file_location))
							{

								// $couchdbId 		= $ReCouchdb->saveData($uploadPath,$file_location,$prefix_file,'uploadfile_'.$file_name.'.'.$ext,$customer_id,'uploadfile');
								if(!empty($geo_clashed_data)){
									$arr_moduless['internal_clashed_remark']	= $this->request->data['internal_clashed_remark'];
									$arr_moduless['approved']					= 2;
									$arr_moduless['status']						= 2;
									$arr_moduless['uploadfile']					='uploadfile'.'_'.$file_name.'.'.$ext;
									$arr_moduless['uploadfile_type']			='uploadfile';
									$arr_moduless['couchdb_id']					=0; //$couchdbId;

									$this->GeoApplicationClashedData->updateAll($arr_moduless,array('clashed_geo_id'=>$geo_id));
								}
								//$this->ApplicationGeoLocation->updateAll(array("uploadfile" =>'uploadfile'.'_'.$file_name.'.'.$ext,"uploadfile_type" =>'uploadfile','couchdb_id'=>0),array("id" => $geo_id));
							}
						}
				
				$ErrorMessage 	= "Record Updated Succesfully";
				$success 		= 1;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	public function GeoLocationReport()
    {
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
		// /$authority_account 	= $this->Session->read('Members.authority_account');
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
		
		if($this->Session->check("Members.member_type")){
			$member_type = $this->Session->read("Members.member_type");
		}

		$main_branch_id = array();
		
		if(isset($this->request->data['Reset']) && !empty($this->request->data['Reset'])){
			$this->Session->delete("Customers.SearchwtgApplication");
			$this->Session->delete("MembersSearchwtgApplication");
			$this->Session->delete("Customers.Page");
			return $this->redirect(URL_HTTP.'applications-list');
		}
		
		$this->removeExtraTags();

		if(isset($this->request->data['Search']) && !empty($this->request->data['Search'])){
			$this->Session->write("MembersSearchwtgApplication",$this->request->data);
			$this->Session->write('Customers.SearchwtgApplication',serialize($this->request->data));
		} else {
			if($this->Session->check("MembersSearchwtgApplication")) {
				$this->request->data = $this->Session->read("MembersSearchwtgApplication");
			}
			if($this->Session->check("Customers.SearchwtgApplication"))
			{
				$this->request->data = unserialize($this->Session->read("Customers.SearchwtgApplication"));
			}
		} 
		$consumer_no 			= isset($this->request->data['consumer_no']) ? $this->request->data['consumer_no'] : '';
		$application_search_no 	= isset($this->request->data['provisional_search_no']) ? $this->request->data['provisional_search_no'] : '';
		$installer_name 		= (isset($this->request->data['installer_name'])) ? $this->request->data['installer_name'] : '';
		$discom_name 			= isset($this->request->data['discom_name']) ? $this->request->data['discom_name'] : '';
		$payment_status 		= isset($this->request->data['payment_status']) ? $this->request->data['payment_status'] : '';
		$order_by_form 			= isset($this->request->data['order_by_form']) ? $this->request->data['order_by_form'] : 'ApplicationGeoLocation.payment_date|DESC';
		// if(isset($this->request->data['category'][0]) && $this->request->data['category'][0]=='3002,3003')
		// {
		// 	$this->request->data['category'] = explode(",",$this->request->data['category'][0]);
		// }

		$category 				= isset($this->request->data['application_type']) ? $this->request->data['application_type'] : '';

		$this->request->data['ses_login_type'] 	= $this->Session->read('Customers.login_type');
		$this->request->data['order_by_form'] 	= $order_by_form;
		$this->request->data['registration_no'] 	= isset($this->request->data['registration_no']) ? $this->request->data['registration_no'] : '';
		$this->request->data['customer_id'] 	= $customer_id;
		$this->request->data['member_id'] 		= $member_id;
		
		$DateField 			= isset($this->request->data['DateField'])?$this->request->data['DateField']:'';
		$from_date 			= isset($this->request->data['DateFrom'])?$this->request->data['DateFrom']:'';
		$end_date 			= isset($this->request->data['DateTo'])?$this->request->data['DateTo']:'';
		$application_status = isset($this->request->data['status'])?$this->request->data['status']:'';
		$registration_no 	= isset($this->request->data['provisional_search_no'])?$this->request->data['provisional_search_no']:'';
		
		$arrAdminuserList	= array();
        $arrUserType		= array();
        $arrCondition		= array();
        $this->SortBy		= "application_geo_location.payment_date";
        $this->Direction	= "DESC";
        $this->intLimit		= 25;
        $this->CurrentPage  = 1;
        $option 			= array();

        $option['colName']  = array('id','registration_no','payment_date','wtg_location','geo_district','geo_taluka','installer_name','action_by','status','action');
		
        $sortArr=array('installer_name'=>'developers.installer_name','registration_no'=>'applications.registration_no','action_by'=>'members.address1','status'=>'ApplicationGeoLocation.wtg_verified');

        $this->SetSortingVars('ApplicationGeoLocation',$option,$sortArr);

      	$ApplicationsList 	= $this->ApplicationGeoLocation->getGeoLocationData($this->request->data,$this->SortBy,$this->Direction);
		
        $ApplicationsListData 	= $ApplicationsList['list'];
        $start_page=isset($this->request->data['start']) ? $this->request->data['start'] : 1;
       	$this->paginate['limit']= 25;
       	$this->paginate['page'] = ($start_page/$this->paginate['limit'])+1;
       	
       	try
		{
			$paginate_data = $this->paginate($ApplicationsListData);
		}
		catch (NotFoundException $e)
		{
			return $this->redirect('/Applications/list');
		}
		$arrAdminuserList				= $paginate_data;
        $usertypes 						= array();
        $option['dt_selector']			='table-example';
        $option['formId']				='formmain';
        $option['url']					= '';
        $option['recordsperpage']		= 25;
      //  $option['allsortable']			= '-all';
        $option['total_records_data']	= count($arrAdminuserList->toArray());
        $option['order_by'] 			= "order : [[5,'desc']]";
        $option['bPaginate']			= 'true';
		$option['bLengthChange']		= 'false';
       
        $arr_status_dropdown 			= $this->ApplicationStages->geo_application_dropdown_status;
        unset($arr_status_dropdown['99']);
        $JqdTablescr 			= $this->JqdTable->create($option);
       	$installers_list 		= $this->Developers->getInstallerListReport();
        $applicationCategory 	= $this->ApplicationCategory->find('list',array('keyField'=>'id','valueField'=>'category_name','conditions'=>array('id IN '=>array(3,4))))->toArray();
		$geo_id_str ='';
		$geo_id_arr = array();
        //if(!empty($this->request->data['provisional_search_no'])){

        $geo_data_arr =$arrAdminuserList->toArray();

        foreach ($geo_data_arr as $key => $value) {
        	$geo_id_arr[]= $value['id'];
        }
        
       		 $geo_id_str = $geo_id_arr;
       		if(!empty($geo_id_str)){
        	 $geo_id_str = implode(',', $geo_id_arr);
        	}
       // }
       
        $district 				= $this->DistrictMaster->find("list",['keyField'=>'id','valueField'=>'name','conditions'=>['state_id'=>4]])->toArray();
		$taluka 				= $this->TalukaMaster->find("list",['keyField'=>'id','valueField'=>'name','conditions'=>['state_code'=>24]])->toArray();
		$action_by 	= array('Gandhidham','Porbandar');
		$wtg_verified = array('1'=>'Verified','0'=>'Pending');
		$zone_drop_down 			=array('1' => '42 Q', '2' => '43 Q','3' => '42 R - North Gujarat', '4' => '43 R - North Gujarat');
        $this->set('geo_id_arr_selected',$geo_id_str);
        $this->set('applicationCategory',$applicationCategory);
        $this->set('arrAdminuserList',$arrAdminuserList->toArray());
        $this->set('JqdTablescr',$JqdTablescr);
        $this->set('period',$this->period);
        $this->set('limit',$this->intLimit);
        $this->set("CurrentPage",$this->CurrentPage);
        $this->set("SortBy",$this->SortBy);
        $this->set("Direction",$this->Direction);
        $this->set("pagetitle",'Project : ');
        $this->set("geo_application_dropdown_status",$arr_status_dropdown);
        $this->set("Installers",$installers_list);
        $this->set('district',$district);
        $this->set('taluka',$taluka);
        $this->set('action_by',$action_by);
        $this->set('wtg_verified',$wtg_verified);
        $this->set('zone_drop_down',$zone_drop_down);
        $out 		=array();
        $counter 	= '1';
        $page_mul 	= ($this->CurrentPage-1);

        foreach($arrAdminuserList->toArray() as $keys=>$val) {
        	$members  					= $this->Members->find("all",['fields'=>['id','address1','name'],'conditions'=>['id'=>$val->approved_by]])->first();
        	
			$remainingday 			= $this->ApplicationGeoLocation->CheckValidityData($val['id'],$val['application_id']);
			$clash_data 			= $this->ApplicationGeoLocation->CheckClashData($val->x_cordinate,$val->y_cordinate,$val->id);
			$member_clash_data 		= $this->ApplicationGeoLocation->Member_CheckClashData($val['id']);
			$internal_clashed_docs 	=  $this->ApplicationGeoLocation->internal_clashed_docs($val['id']);
				

			if(!empty($clash_data)){
					if($clash_data == 'Clashing'){
						$clash_text = '<p style="text-decoration: underline;color: #307FE2;">Clashing</p>';
					} elseif($clash_data == 'Internal Clashing'){
						$clash_text = '<p style="text-decoration: underline;color: #cdcd09;">Internal Clashing</p>';
					}
			}else if(!empty($member_clash_data)){
					if($member_clash_data == 'Clashing'){
						$clash_text = '<p style="text-decoration: underline;color: #307FE2;">Clashing</p>';
					}elseif($member_clash_data == 'Internal Clashing'){
						$clash_text = '<p style="text-decoration: underline;color: #cdcd09;">Internal Clashing</p>';
					}
			}else{
				$clash_text = '';
			}

        	$temparr=array();
            foreach($option['colName'] as $key) {
            	if(isset($val[$key])){
                    $temparr[$key]=$val[$key];
                }
               
                if($key=='id') {
                   $temparr[$key]= $counter+($page_mul*10);
                   $counter++;
                }
                if($key=='registration_no') {
                   $temparr[$key]= $val['applications']['registration_no'];
                }
                if($key=='payment_date') {
                   $temparr[$key]= date("Y-m-d H:i A",strtotime($val['payment_date']));
                }
                if($key=='wtg_location') {
                   $temparr[$key]= $val['wtg_location'];

                }
                if($key=='geo_district') {
                	$key1 = $val['geo_district'];
					 // Check if the key is present in the array
                    if (array_key_exists($key1, $district)) {
                        // If the key exists, show its value
                        $district_name = $district[$key1];
                        
                    }
                   $temparr[$key]= $district_name;

                }
                if($key=='geo_taluka') {
                	$keyT = $val['geo_taluka'];
                    // Check if the key is present in the array
                    if (array_key_exists($keyT, $taluka)) {
                        // If the key exists, show its value
						$taluka_name = $taluka[$keyT];
                   }
                   $temparr[$key]= $taluka_name;

                }
                // if($key=='application_type') {
                //     if($val->application_type == 2){
                //    	$temparr[$key]= 'Open Access Solar';
                //    }else if($val->application_type == 3){
                //    	$temparr[$key]= 'Wind';
                //    }else if($val->application_type == 4){
                //    	$temparr[$key]= 'Hybrid';

                //    }
                // }
                if($key=='installer_name') {
					$temparr[$key]= ucwords($val['developers']['installer_name']);
					 
				}
				if($key=='action_by') {
					$temparr[$key]= (isset($members->address1)?$members->address1 : '-');
				}
				if($key=='status') {
						if($val['wtg_verified'] == 1){
							$wtg_verified = 'Verified';
						}else{
							$wtg_verified = 'Pending';
						}
					$temparr[$key]= (isset($val['wtg_verified'])?$wtg_verified : '-');
					
				}
                if($key=='action') {
                	// $paths = URL_HTTP.'view-applications-geo-location/'.encode($val->id);
                	$url = URL_HTTP.'GeoApplications/view_coordinates_details/'.encode($val->id);
                	$title = 'View Geo Coordinates Details';
                	$geo_id = $val['id'];

                	if(!empty($geo_id_str) && $val['wtg_verified'] != 1 &&($clash_text != '' || $val['approved'] != NULL ) ){
                		$temparr[$key]	='<button type="button" class="btn btn-sm" style="color:white;background-color: #307FE2;"  onclick="javascript:showModel(\''.$title.'\',\''.$url.'\');" > Action</button>

                		<input type="checkbox" id="'.$geo_id.' " class = "verify" name="verify" onclick="javascript:show_check();">'	;
                	}else{
                		$temparr[$key]	='<button type="button" class="btn btn-sm" style="color:white;background-color: #307FE2;"  onclick="javascript:showModel(\''.$title.'\',\''.$url.'\');" > Action</button>'	;
                	}
                	

                	
					 
     //            	if($val['payment_status'] == 1 && $val['approved'] != 1){
     //            		if(!empty($clash_text)){ 
     //            			$temparr[$key]	='<span  onclick="javascript:show_clash_reason(\''.$val->id.'\');" class="text-danger bold" style="text-decoration: underline;">'. $clash_text.' </span>';
     //            		}else{ 
					// 	$temparr[$key]	='<button type="button" class="btn btn-sm" style="color:white;background-color: #307FE2;"  onclick="javascript:show_clash_modal(\''.$val->id.'\',\''.$val->application_id.'\');" title=""> Clash</button><button type="button" class="btn btn-sm" style="color:black;background-color: #f7f700;"  onclick="javascript:show_internal_clash_modal(\''.$val->id.'\',\''.$val->application_id.'\');" title=""> internal Clash</button><button type="button" class="btn btn-sm" style="color:white;background-color: #4cc972;"  onclick="javascript:show_approve_modal(\''.$val->id.'\');" title=""> No clashing</button><button type="button" class="btn btn-sm" style="color:white;background-color: #F3565D;"  onclick="javascript:show_reject_modal(\''.$val->id.'\');" title=""> Reject</button>'	;
					// 		 if(isset($internal_clashed_docs->clashed_geo_id) && !empty($internal_clashed_docs->uploadfile)){ 
					// 					  				$path = URL_HTTP.'app-docs/Internal_clashed_uploadfile/'.encode($internal_clashed_docs->clashed_geo_id);
					// 				$temparr[$key]	='<button type="button" class="btn btn-sm" style="color:white;background-color: #307FE2;"  onclick="javascript:show_clash_modal(\''.$val->id.'\',\''.$val->application_id.'\');" title=""> Clash</button><button type="button" class="btn btn-sm" style="color:black;background-color: #f7f700;"  onclick="javascript:show_internal_clash_modal(\''.$val->id.'\',\''.$val->application_id.'\');" title=""> internal Clash</button><button type="button" class="btn btn-sm" style="color:white;background-color: #4cc972;"  onclick="javascript:show_approve_modal(\''.$val->id.'\');" title=""> No clashing</button><button type="button" class="btn btn-sm" style="color:white;background-color: #F3565D;"  onclick="javascript:show_reject_modal(\''.$val->id.'\');" title=""> Reject</button><br><br>	<a href="'.$path.'" target="_blank"><i class="fa fa-eye"> View Internal Clashed Upload File</i></a>';
					// 					  			 }
					// 	} 
     //            	}elseif($val['payment_status'] == 1 && $val['approved'] == 1){ 
					//  	if(!empty($remainingday)){
					// 		$temparr[$key]	= '<i class="fa fa-check" aria-hidden="true"></i> <span class="text-success bold">No clashing </span><br><span class="text-success">Remaining Days to Complete application  '.$remainingday.'  </span>';
					// 	}else{
					// 		$temparr[$key]	= '<i class="fa fa-check" aria-hidden="true"></i> <span class="text-success bold">No clashing </span>';	
					// 	}
					// }elseif($val['approved'] == 2){ 
					 	
					//  	$temparr[$key]	= '<i class="fa fa-times" aria-hidden="true"></i> <span  onclick="javascript:show_reason(\''.$val->id.'\');" class="text-danger bold" >Rejected </span>';
					 	
					// }
				}
            }
            $out[]=$temparr;
        }
        if ($this->request->is('ajax'))
        {
            header('Content-type: application/json');
            echo json_encode(array(	"condi" 			=> $arrCondition,
            						"draw" 				=> intval($this->request->data['draw']),
					                "recordsTotal"    	=> intval( $this->request->params['paging']['ApplicationGeoLocation']['count']),
					                "recordsFiltered" 	=> intval( $this->request->params['paging']['ApplicationGeoLocation']['count']),
					                "data"            	=> $out));
            die;
        }
    }

    /**
	 * getTalukaFromDistrict
	 * Behaviour : Public
	 * @defination : Method is use to get taluka from district
	 */
	public function getTalukaFromDistrict()
	{
		$this->autoRender 	= false;
		$district 			= isset($this->request->data['district']) ? $this->request->data['district'] : '';

		$arrTaluka 			= $this->TalukaMaster->getTalukaList($district);
		$resultTaluka 		= array();
		// foreach($arrTaluka as $val) {
		// 	$resultTaluka[$val] = $val;
		// }
		foreach($arrTaluka  as $key => $val) {
			$resultTaluka[$key] = $val;
		}
		$this->ApiToken->SetAPIResponse('msg', 'list of taluka');
		$this->ApiToken->SetAPIResponse('data', $resultTaluka);
		$this->ApiToken->SetAPIResponse('type','ok');
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	/**
	 *
	 * wtg_shifting
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is add to wtg_shifting
	 *
	 */
	public function wtg_shifting($id = null)
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

			
			
			$geo_application_data			= $this->ApplicationGeoLocation->find("all",['fields'=>['id','application_id','wtg_location','x_cordinate','y_cordinate','zone','land_survey_no'],'conditions'=>['application_id'=>$id,'approved'=>1]])->toArray();
		
		}
		$zone_drop_down 			=array('1' => '42 Q', '2' => '43 Q','3' => '42 R - North Gujarat', '4' => '43 R - North Gujarat');
		
		$applicationCategory 	= $this->ApplicationCategory->find('all',array('conditions'=>array('id'=>$applyOnlinesData->application_type)))->first();
		// $applicationCategory 		= $this->ApplicationCategory->find("all",['conditions'=>['id'=>$applyOnlinesData->application_type]])->first();

								  	
		
		$this->set("applicationCategory",$applicationCategory);
		$this->set('id',$encode_id);
		$this->set('geo_application_data',$geo_application_data);
		$this->set('zone_drop_down',$zone_drop_down);
		
		$this->set('geo_location_charges',$applicationCategory->geo_location_charges);
		
		
		$this->set("pageTitle","Add Shifting of WTG Cordinates");
		$this->set('Applications',$applyOnlinesData);
		$this->set("MemberTypeDiscom",$this->Members->member_type_discom);
		$this->set("APPLY_ONLINE_GUJ_STATUS",$this->ApplicationStages->apply_online_guj_status);
		$this->set("MStatus",$this->ApplicationStages);
		$this->set('logged_in_id',$this->Session->read('Customers.id'));
		$this->set('is_member',$is_member);
		$this->set('encode_id',$encode_id);
		
		$this->set('Couchdb',$this->ReCouchdb);
		$this->set('ApplicationGeoLocation',$this->ApplicationGeoLocation);
		$this->set('GeoApplicationClashedData',$this->GeoApplicationClashedData);
	}

	/**
	 * Add_ModifyWTG
	 * Behaviour : Public
	 * @defination : Method is use to Add the shifting of modified wtg data in the geo_location.
	 */
	// public function Add_ModifyWTG()
	// {	
	// 	$this->autoRender 	= false;
	// 	$geo_application_id 				= (isset($this->request->data['GeoClashInternal_geo_id'])?$this->request->data['GeoClashInternal_geo_id']:0);
	// 	$approved_geo_id 				= (isset($this->request->data['approved_geo_id'])?$this->request->data['approved_geo_id']:0);
	// 	if(empty($geo_application_id)) {
	// 		$ErrorMessage 	= "Invalid Request. Please validate form details.";
	// 		$success 		= 0;
	// 		$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
	// 		$this->ApiToken->SetAPIResponse('success',$success);
	// 	}else{
	// 		$member_id 				= $this->Session->read("Members.id");

	// 		 if(!empty($member_id)) {


	// 		 	if (strpos($approved_geo_id, '_') !== false) {

	// 				$approved_geo_offline_id = explode("_offline",$approved_geo_id);
					
	// 				$approved_geo_offline_id = $approved_geo_offline_id[0];
					
	// 			    $geo_application_data   = $this->GeoCoordinateOfflineApproved->find('all',array('conditions'=>array('id'=>$approved_geo_offline_id)))->first();

	// 			   $zonearray = array(1 => '42 Q', 2 => '43 Q',3 => '42 R - North Gujarat', 4 => '43 R - North Gujarat');
	// 				// Key to check
	// 				$keyToCheck = $geo_application_data->zone;
	// 				if (array_key_exists($keyToCheck, $zonearray)) {
	// 				    // Display the value corresponding to the key
	// 				     $zone = $zonearray[$keyToCheck]; 
	// 				}

	// 				$clashentity                  		= $this->GeoApplicationClashedData->newEntity();
	// 				$clashentity->clashed_geo_id  		= $geo_application_id;
	// 				$clashentity->approved_geo_offline_id = $approved_geo_offline_id; 
	// 				$clashentity->zone  				= $zone;
	// 				$clashentity->x_cordinate  			= $geo_application_data->x_cordinate;
	// 				$clashentity->y_cordinate  			= $geo_application_data->y_cordinate;
	// 				$clashentity->wtg_location  		= $geo_application_data->wtg_location;
	// 				$clashentity->clashed_for			= 2;
	// 				$clashentity->created 		   		= $this->NOW();
	// 				$this->GeoApplicationClashedData->save($clashentity);

	// 			} else {

	// 			    $geo_application_data   = $this->ApplicationGeoLocation->find('all',array('conditions'=>array('id'=>$approved_geo_id)))->first();


	// 				$arr_moduless['approved']			= 5;
	// 				$this->ApplicationGeoLocation->updateAll($arr_moduless,array('id'=>$geo_application_id));


	// 				$zonearray = array(1 => '42 Q', 2 => '43 Q',3 => '42 R - North Gujarat', 4 => '43 R - North Gujarat');
	// 				// Key to check
	// 				$keyToCheck = $geo_application_data->zone;
	// 				if (array_key_exists($keyToCheck, $zonearray)) {
	// 				    // Display the value corresponding to the key
	// 				     $zone = $zonearray[$keyToCheck]; 
	// 				}
	// 				$clashentity                  		= $this->GeoApplicationClashedData->newEntity();
	// 				$clashentity->clashed_geo_id  		= $geo_application_id;
	// 				$clashentity->application_id  		= $geo_application_data->application_id;
	// 				$clashentity->approved_geo_id  		= $approved_geo_id; 
	// 				$clashentity->zone  				= $zone;
	// 				$clashentity->x_cordinate  			= $geo_application_data->x_cordinate;
	// 				$clashentity->y_cordinate  			= $geo_application_data->y_cordinate;
	// 				$clashentity->wtg_location  		= $geo_application_data->wtg_location;
	// 				$clashentity->clashed_for			= 2;
	// 				$clashentity->created 		   		= $this->NOW();
	// 				$this->GeoApplicationClashedData->save($clashentity);
	// 			}
	// 			//start
	// 			$geo_application_data_result   = $this->GeoApplicationClashedData->find('all',array('conditions'=>array('clashed_geo_id'=>$geo_application_id)))->first();
	// 			if($geo_application_data_result->application_id !=0){
	// 				$geo_application_data = $this->GeoApplicationClashedData->find('all',
	// 	                                        [ 'fields'=>['clashed_geo_id','wtg_location','zone','x_cordinate','y_cordinate','developers.installer_name'],
	// 	                                            'join'=>[['table'=>'applications','type'=>'left','conditions'=>'GeoApplicationClashedData.application_id = applications.id'], ['table'=>'developers','type'=>'left','conditions'=>'applications.installer_id = developers.id']],
	// 	                                            'conditions'=>['clashed_geo_id'=>$geo_application_id]])->first();	

	// 				$clashed_reason = "WTG Coordinates Clashing with : " .$geo_application_data['developers']['installer_name'] .", location : " .$geo_application_data['wtg_location'] .", Zone : " .$geo_application_data['zone'] .", UTM Easting : " . $geo_application_data['x_cordinate'] . ", UTM Northing : ". $geo_application_data['y_cordinate'];
	// 			}else{
					
	// 				$geo_application_data = $this->GeoApplicationClashedData->find('all',
	// 	                                        [ 'fields'=>['clashed_geo_id','wtg_location','zone','x_cordinate','y_cordinate','geo_coordinate_offline_approved.installer_name'],
	// 	                                            'join'=>[['table'=>'geo_coordinate_offline_approved','type'=>'left','conditions'=>'GeoApplicationClashedData.approved_geo_offline_id = geo_coordinate_offline_approved.id']],
	// 	                                            'conditions'=>['clashed_geo_id'=>$geo_application_id]])->first();
					
	// 				$clashed_reason = "WTG Coordinates Clashing with : " .$geo_application_data['geo_coordinate_offline_approved']['installer_name'] .", location : " .$geo_application_data['wtg_location'] .", Zone : " .$geo_application_data['zone'] .", UTM Easting : " . $geo_application_data['x_cordinate'] . ", UTM Northing : ". $geo_application_data['y_cordinate'];
	// 			}
				
 //     			$arr_module['comment'] = $clashed_reason;
     			
 //     			$this->ApplicationGeoLocation->updateAll($arr_module,array('id'=>$geo_application_id));
 //     			//end

	// 			$ErrorMessage 	= "Clash Data Added Succesfully.";
	// 			$success 		= 1;
	// 			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
	// 			$this->ApiToken->SetAPIResponse('success',$success);
	
	// 			}else {
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
	 * geo_location_verifydata
	 * Behaviour : Public
	 * @defination : Method is use to edit the geo_location data.
	 */
	/**
	 * geo_location_verifydata
	 * Behaviour : Public
	 * @defination : Method is use to edit the geo_location data.
	 */
	public function geo_location_verifydata()
	{	
		
		$this->autoRender 	= false;
		$geo_id 				= (isset($this->request->data['GeoVerify_application_id'])?$this->request->data['GeoVerify_application_id']:0);
		
		if(empty($geo_id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		}	else{
			
			$member_id 				= $this->Session->read("Members.id");
			 if(!empty($member_id)) {
				if(!empty($geo_id)){
					$geo_application_data   = $this->ApplicationGeoLocation->find('all',array('conditions'=>array('id IN'=>$geo_id)))->first();

			 		$ApplicationsVerifyEntity = $this->GeoApplicationVerification->newEntity();
							
							$ApplicationsVerifyEntity->geo_id			= $geo_id;
							$ApplicationsVerifyEntity->application_id	= $geo_application_data->application_id;
							$ApplicationsVerifyEntity->created          = $this->NOW();
							$ApplicationsVerifyEntity->created_by       = $member_id;
						
					$this->GeoApplicationVerification->save($ApplicationsVerifyEntity);
					$geo_id_array = explode( ',' ,$geo_id);
					
					foreach ($geo_id_array as $key => $value) {
						
						$arr_moduless['wtg_verified']			= 1;
						$arr_moduless['wtg_verified_by']		= $member_id;
						$arr_moduless['wtg_verified_date']		= $this->NOW();
							
						$this->ApplicationGeoLocation->updateAll($arr_moduless,array('id'=>$value));
					}
					
					$ErrorMessage 	= "Record Verified Succesfully";
					$success 		= 1;
					$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
					$this->ApiToken->SetAPIResponse('success',$success);
				}
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

	/**
	 *
	 * view_coordinates_details
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to view installer
	 *
	 */
	public function view_coordinates_details($id = null)
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
			
			$geo_location_data 		= $this->ApplicationGeoLocation->find("all",['conditions'=>['id'=>$id]])->first();
			$applyOnlinesData 		= $this->Applications->viewApplication($geo_location_data->application_id);

			
		}
		
		$applicationCategory 	= $this->ApplicationCategory->find('all',array('conditions'=>array('id'=>$applyOnlinesData->application_type)))->first();
		$application_id 		= $geo_location_data->application_id;

		$remainingday 			= $this->ApplicationGeoLocation->CheckValidityData($id,$application_id);
		$clash_data 			= $this->ApplicationGeoLocation->CheckClashData($geo_location_data->x_cordinate,$geo_location_data->y_cordinate,$id);
		$member_clash_data 		= $this->ApplicationGeoLocation->Member_CheckClashData($id);
		$internal_clashed_docs 	=  $this->ApplicationGeoLocation->internal_clashed_docs($id);
		
		if(!empty($clash_data)){
				if($clash_data == 'Clashing'){
					$clash_text = '<p style="text-decoration: underline;color: #307FE2;">Clashing</p>';
				} elseif($clash_data == 'Internal Clashing'){
					$clash_text = '<p style="text-decoration: underline;color: #cdcd09;">Internal Clashing</p>';
				}
		}else if(!empty($member_clash_data)){
				if($member_clash_data == 'Clashing'){
					$clash_text = '<p style="text-decoration: underline;color: #307FE2;">Clashing</p>';
				}elseif($member_clash_data == 'Internal Clashing'){
					$clash_text = '<p style="text-decoration: underline;color: #cdcd09;">Internal Clashing</p>';
				}
		}else{
			$clash_text = '';
		}
		$wtgno_data						= $this->ApplicationHybridAdditionalData->find("all",['conditions'=>['application_id'=>$application_id,'capacity_type'=>3]])->toArray();
		$applications 					= $this->ApplicationHybridAdditionalData->find();
		$total_application 		= $applications->select(['total_wtgno' => $applications->func()->sum('nos_mod_inv')])->where(array('application_id'=>$application_id,'capacity_type'=>3))->first();
		if(!empty($member_id)){
				$condition 	= array('application_id'=>$application_id,'payment_status IN'=>array(1,2)); //Add code (,'OR'=>['approved is NULL','approved is NOT'=>1])if u dont want to see approve application to member
		}else{
			$condition 	= array('application_id'=>$application_id);
		}
		$geo_application_data			= $this->ApplicationGeoLocation->find("all",['conditions'=>$condition])->toArray();
		$geo_application_rejected_data	= $this->ApplicationGeoLocation->find("all",['conditions'=>array('application_id'=>$id,'approved'=>2)])->toArray();

		if(!empty($count_of_application)){

			$total_wtg_per = ($total_application->total_wtgno * 10 )/100;
			$total_application->total_wtgno     = ($total_application->total_wtgno + $total_wtg_per);
			$total 		   = ceil($total_application->total_wtgno);
			if(!empty($count_of_rejected_application)){
				$total_wtg_application = ($total - $count_of_application) + $count_of_rejected_application;
			}else{
				$total_wtg_application = ($total - $count_of_application);
			}
			
	  	}else{
	  		$total_wtg_per = ($total_application->total_wtgno * 10 )/100;
			$total_application->total_wtgno     = ($total_application->total_wtgno + $total_wtg_per);
			$total 		   = ceil($total_application->total_wtgno);
			$total_wtg_application = $total ;
			
	  	}

		$applicationDocs = $this->ApplicationsDocs->find("all",['conditions'=>['application_id'=>$application_id,'doc_type'=>'geo_cordinate_file']])->toArray();
		$applicationDocsSTU = $this->ApplicationsDocs->find("all",['conditions'=>['application_id'=>$application_id,'doc_type'=>'STUstep1']])->toArray();
		$applicationDocsCTU = $this->ApplicationsDocs->find("all",['conditions'=>['application_id'=>$application_id,'doc_type'=>'CTUstep1']])->toArray();
		
		$geo_application_data_download			= $this->ApplicationGeoLocation->find("all",['conditions'=>array('application_id'=>$applyOnlinesData->application_id,'approved'=>1)])->first();

		$Geo_application_paymet_log = $this->GeoApplicationPayment->find("all",['conditions'=>['application_id'=>$applyOnlinesData->application_id,'payment_status'=>'success']])->toArray();
		
		$Geo_application_verification_log = $this->GeoApplicationVerification->find("all",['conditions'=>['application_id'=>$applyOnlinesData->application_id,'shifted'=>'No']])->toArray();
		
		$wtg_make 			= $this->ManufacturerMaster->find("list",['keyField'=>'id','valueField'=>'name','conditions'=>['id'=>
			$geo_location_data->wtg_make]])->first();
		$district 		= $this->DistrictMaster->find("list",['keyField'=>'id','valueField'=>'name','conditions'=>['id'=>
			$geo_location_data->geo_district]])->first();
		$taluka 				= $this->TalukaMaster->find("list",['keyField'=>'id','valueField'=>'name','conditions'=>['id'=>$geo_location_data->geo_taluka]])->first();

		$zonearray = array(1 => '42 Q', 2 => '43 Q',3 => '42 R - North Gujarat', 4 => '43 R - North Gujarat');
		// Key to check
		$keyToCheck = $geo_location_data->zone;
		if (array_key_exists($keyToCheck, $zonearray)) {
		    // Display the value corresponding to the key
		     $zone = $zonearray[$keyToCheck]; 
		}

		// For clashed Location list //
	  	$query1 = $this->ApplicationGeoLocation->find()->select(['id','wtg_location','application_id','application_type','x_cordinate','y_cordinate'])->where(['application_id IS NOT'=>$id,'approved'=>1]);

		$query2 = $this->GeoCoordinateOfflineApproved->find()->select(['id','wtg_location','application_id','installer_name','x_cordinate','y_cordinate'])->where(['1' => '1']);

		// Combine the two queries using unionAll()
		$query = $query1->unionAll($query2);

		// Execute the query
		$all_geo_application_data_results = $query->toArray();

	  	$all_geo_application_data	= $this->ApplicationGeoLocation->find("all",['fields'=>array('id','wtg_location','application_id','x_cordinate','y_cordinate'),'conditions'=>['application_id IS NOT'=>$id,'approved'=>1]])->toArray();
	  	$LocationList = [];
		foreach ($all_geo_application_data_results as $key => $value) {
			if($value['application_id'] == 0){
				$LocationList[$value['id'].'_offline'] = $value['application_type'].'-Wtg Location - ' . $value['wtg_location'] . ' , UTM Easting - ' . $value['x_cordinate']. ' , UTM Northing - ' . $value['y_cordinate'];
				
			}else{
				$LocationList[$value['id']] = $value['application_id'] .'-Wtg Location - ' . $value['wtg_location'] . ' , UTM Easting - ' . $value['x_cordinate']. ' , UTM Northing - ' . $value['y_cordinate'];
			}
		    
		}
		// clashed Location list End//

		// For Internal clashed Location list //
		$query3 = $this->ApplicationGeoLocation->find()->select(['id','wtg_location','application_id','application_type','x_cordinate','y_cordinate'])->where(['application_id'=>$id,'approved'=>1]);

		$query4 = $this->GeoCoordinateOfflineApproved->find()->select(['id','wtg_location','application_id','installer_name','x_cordinate','y_cordinate'])->where(['1' => '1']);

		// Combine the two queries using unionAll()
		$query5 = $query3->unionAll($query4);

		// Execute the query
		$all_geo_application_data_internal_results = $query5->toArray();

		//$all_geo_application_data_internal	= $this->ApplicationGeoLocation->find("all",['fields'=>array('id','application_id','wtg_location','x_cordinate','y_cordinate'),'conditions'=>['application_id'=>$id,'approved'=>1]])->toArray();
	  	$LocationList_internal = [];
		foreach ($all_geo_application_data_internal_results as $key => $value) {
			
		    if($value['application_id'] == 0){
				$LocationList_internal[$value['id'].'_offline'] = $value['application_type'].'-Wtg Location - ' . $value['wtg_location'] . ' , UTM Easting - ' . $value['x_cordinate']. ' , UTM Northing - ' . $value['y_cordinate'];
				
			}else{
				$LocationList_internal[$value['id']] = $value['application_id'] .'-Wtg Location - ' . $value['wtg_location'] . ' , UTM Easting - ' . $value['x_cordinate']. ' , UTM Northing - ' . $value['y_cordinate'];
			}

		}
		// Internal clashed Location list End//
		$this->set("applicationDocsSTU",$applicationDocsSTU);
		$this->set("applicationDocsCTU",$applicationDocsCTU);
		$this->set("LocationList",$LocationList);
		$this->set("LocationList_internal",$LocationList_internal);
		$this->set("geo_application_data",$geo_application_data);
		$this->set("total_wtg_application",$total_wtg_application);
		$this->set("internal_clashed_docs",$internal_clashed_docs);
		$this->set("remainingday",$remainingday);
		$this->set("clash_text",$clash_text);
		$this->set("district",$district);
		$this->set("taluka",$taluka);
		$this->set("zone",$zone);
		$this->set("wtg_make",$wtg_make);
		$this->set("applicationCategory",$applicationCategory);
		$this->set("geo_location_data",$geo_location_data);
		$this->set("applyOnlinesData",$applyOnlinesData);
		$this->set("applicationDocs",$applicationDocs);
		$this->set("geo_application_data_download",$geo_application_data_download);
		$this->set("Geo_application_paymet_log",$Geo_application_paymet_log);
		$this->set("Geo_application_verification_log",$Geo_application_verification_log);
		$this->set('id',$encode_id);
		$this->set('application_id',encode($application_id));
		$this->set("pageTitle","Application View");
		$this->set("APPLY_ONLINE_GUJ_STATUS",$this->ApplicationStages->apply_online_guj_status);
		$this->set("MStatus",$this->ApplicationStages);
		$this->set('logged_in_id',$this->Session->read('Customers.id'));
		$this->set('is_member',$is_member);
		$this->set('encode_id',$encode_id);
		$this->set('Couchdb',$this->ReCouchdb);
		
	}
	/**
	 * geo_location_editdata
	 * Behaviour : Public
	 * @defination : Method is use to edit the geo_location data.
	 */
	
	public function getSavedData()
	{
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['id'])?$this->request->data['id']:0);
		
		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		}else{
			if(!empty($id)){
					
				$geo_application_details   = $this->ApplicationGeoLocation->find('all',array('conditions'=>array('id'=>$id)))->first();
				
				//$agreement_consumer_details['application_id'] = encode($agreement_consumer_details['application_id']);
				$geo_application_details['path'] = URL_HTTP.'app-docs/land_per_form/'.encode($geo_application_details['application_id']);
				$taluka = $this->TalukaMaster->find('all',array('conditions'=>array('id'=>$geo_application_details->geo_taluka)))->first();
				
				$geo_application_details['taluka_name'] = $taluka->name;
				$geo_application_details = json_encode($geo_application_details);
				

				if(!empty($geo_application_details)) {
			
						$data 	= $geo_application_details;
						$success 		= 1;
						$this->ApiToken->SetAPIResponse('message',$data);
						$this->ApiToken->SetAPIResponse('success',$success);
				
				}else {
					$ErrorMessage 	= "Invalid Request. Please validate form details.";
					$success 		= 0;
					$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
					$this->ApiToken->SetAPIResponse('success',$success);
				}
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
		
	}
	public function download($filename) {
		
		$get_application_id   = $this->ApplicationsDocs->find('all',array('conditions'=>array('file_name'=>$filename)))->first();
		
      	$file = WWW_ROOT . APPLICATIONS_PATH . $get_application_id->application_id . DS . $filename;
	
           if (!file_exists($file) || !is_readable($file)) {
            throw new NotFoundException('The file does not exist or is not readable.');
        }
        $fileHandle = fopen($file, 'rb');

        if (!$fileHandle) {
            throw new InternalErrorException('Failed to open the file.');
        }

        // Set content type and disposition headers
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Output file contents
        readfile($file);
        // Stop further rendering
        $this->autoRender = false;
    }

    /**
	 * geo_location_raisedquery
	 * Behaviour : Public
	 * @defination : Method is use to edit the geo_location data.
	 */
	public function geo_location_raisedquery()
	{	
		$this->autoRender 	= false;
		$geo_application_id 				= (isset($this->request->data['GeoRaiseQuery_application_id'])?$this->request->data['GeoRaiseQuery_application_id']:0);
		
		if(empty($geo_application_id)) {
			$ErrorMessage 	= "Invalid Rcxfequest. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		}	else{
			
			$member_id 				= $this->Session->read("Members.id");
			
			if(!empty($member_id)) {
				if(!empty($this->request->data['query_raised_remark'])){

					$arr_modules['query_raised']				= 1;
					$arr_modules['query_raised_remark']		    = $this->request->data['query_raised_remark'];
					$arr_modules['query_raised_by']				= $member_id;
					$arr_modules['query_raised_date']			= $this->NOW();
					
					$this->ApplicationGeoLocation->updateAll($arr_modules,array('id'=>$geo_application_id));
					$ErrorMessage 	= "Query Raised Succesfully";
					$success 		= 1;
					$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
					$this->ApiToken->SetAPIResponse('success',$success);
				}
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
	/**
	 * geo_location_editdata
	 * Behaviour : Public
	 * @defination : Method is use to edit the geo_location data.
	 */
	public function geo_location_editquerydata()
	{	
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['GeoRaisedQuery_application_id'])?$this->request->data['GeoRaisedQuery_application_id']:0);
		$application_type 	= (isset($this->request->data['GeoRaisedQuery_application_type'])?$this->request->data['GeoRaisedQuery_application_type']:0);
		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} 
		else{
			// $encode_id 				= $id;
			// $id 					= intval(decode($id));
			//$applicationData 		= $this->Applications->viewApplication($id);
			$customer_id 			= $this->Session->read("Customers.id");
			$member_id 				= $this->Session->read("Members.id");
			if ( (!empty($customer_id) || !empty($member_id))) {
				// || $this->request->data['x_cordinate'] < 19.00 || $this->request->data['x_cordinate'] >  24.82 || $this->request->data['y_cordinate'] < 68.00 || $this->request->data['y_cordinate'] > 74.62|| (empty($this->request->data['land_per_form']['name']))
				if(((empty($this->request->data['type_of_land'])) || (empty($this->request->data['land_survey_no'])) ||  (empty($this->request->data['rlmm']))  ))
				{
						$ErrorMessage 	= "Please select all the details.";
						$success 		= 0;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
				} else {
					if(!empty($this->request->data['type_of_land'])){
						//$arr_modules								= $this->ApplicationGeoLocation->newEntity();
						$wtg_validity_date = date('Y-m-d',strtotime($this->request->data['wtg_validity_date']));
						$arr_modules['type_of_land']				= $this->request->data['type_of_land'];
						$arr_modules['land_survey_no']				= $this->request->data['land_survey_no'];
						
						//$arr_modules['land_per_form']					= $this->request->data['land_per_form'];

						$arr_modules['rlmm']						= $this->request->data['rlmm'];
						if($this->request->data['rlmm'] == 'Y'){
							$arr_modules['wtg_make']					= $this->request->data['wtg_make'];
							$arr_modules['wtg_model']					= $this->request->data['wtg_model'];

							$arr_modules['wtg_capacity']				= $this->request->data['wtg_capacity'];
							$arr_modules['wtg_rotor_dimension']			= $this->request->data['wtg_rotor_dimension'];
							$arr_modules['wtg_hub_height']				= $this->request->data['wtg_hub_height'];
						}else{
							$arr_modules['wtg_make']					= $this->request->data['wtg_make_n'];
							$arr_modules['wtg_model']					= $this->request->data['wtg_model_n'];
							
							$arr_modules['wtg_capacity']				= $this->request->data['wtg_capacity_n'];
							$arr_modules['wtg_rotor_dimension']			= $this->request->data['wtg_rotor_dimension_n'];
							$arr_modules['wtg_hub_height']				= $this->request->data['wtg_hub_height_n'];
						}
						$this->ApplicationGeoLocation->updateAll($arr_modules,array('id'=>$this->request->data['geo_id']));
						//upload_wtg_file - if rlmm is no
						
						if (isset($this->request->data['land_per_form']) && !empty($this->request->data['land_per_form'])) {

							$arr_modules['land_per_form']		= $this->request->data['land_per_form'];
							$insertId							= $this->request->data['geo_id'];
							$prefix_file 	= '';
							$name 			= $arr_modules['land_per_form']['name'];

							$ext 			= substr(strtolower(strrchr($name, '.')), 1);

							$file_name 		= $prefix_file.date('Ymdhms').rand();
							$uploadPath 	= WTG_PATH.$insertId.'/';
							if(!file_exists(WTG_PATH.$insertId)) {
								@mkdir(WTG_PATH.$insertId, 0777);
							}
							$file_location 	= WWW_ROOT.$uploadPath.'land_per_form'.'_'.$file_name.'.'.$ext;
							
							if(move_uploaded_file($arr_modules['land_per_form']['tmp_name'],$file_location))
							{

								$land_per_form_couchdbId 		= $this->ReCouchdb->saveData($uploadPath,$file_location,$prefix_file,'land_per_form_'.$file_name.'.'.$ext,$customer_id,'land_per_form');
								
								$this->ApplicationGeoLocation->updateAll(array("land_per_form" =>'land_per_form'.'_'.$file_name.'.'.$ext,"land_per_form_type" =>'land_per_form','couchdb_id'=>$land_per_form_couchdbId),array("id" => $insertId));
								
							}
						}
						
						$ErrorMessage 	= "Record Updated Succesfully";
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

	public function getgeoreportfromexel()
	{
		$applicationData 	= $this->fetch_re_data($this->request->data);
		
	}

    public function fetch_re_data($array_request,$return_count=0)
    {

    	$member_id 			= $this->Session->read("Members.id");
    	$customer_id 		= $this->Session->read("Customers.id");
		$state 				= '';
		$branch_id 			= '';
		$main_branch_id		= '';
		$member_type 		= '';
		$member_type 	= $this->Session->read('Members.member_type');
		$area 			= $this->Session->read('Members.area');
		$circle 		= $this->Session->read('Members.circle');
		$division 		= $this->Session->read('Members.division');
		$subdivision 	= $this->Session->read('Members.subdivision');
		$section 		= $this->Session->read('Members.section');
		$order_by_form 	= 'Applications.modified|DESC';
		
		$array_request['order_by_form'] 	= $order_by_form;
		if($this->Session->check("Members.state")){
			$state 		= $this->Session->read("Members.state");
		}
		if($this->Session->check("Members.member_type")){
			$member_type = $this->Session->read("Members.member_type");
		}
		if(empty($customer_id) && empty($member_id))
		{
			return $this->redirect(URL_HTTP.'/home');
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
		$array_request['ses_login_type'] 	= $this->Session->read('Customers.login_type');
		$array_request['order_by_form'] 	= $order_by_form;
		$array_request['customer_id'] 	= $customer_id;
		$array_request['member_id'] 		= $member_id;
    	$DateField 			= isset($array_request['DateField'])?$array_request['DateField']:'';
		$from_date 			= isset($array_request['DateFrom'])?$array_request['DateFrom']:'';
		$end_date 			= isset($array_request['DateTo'])?$array_request['DateTo']:'';
		$fields_date 		= isset($array_request['DateField'])?$array_request['DateField']:'';
		$fields_date  		= "application_stages.created";

		if (!empty($DateField) && in_array($DateField,array("application_stages.created","charging_certificate.meter_installed_date"))) {
			$fields_date 	= $DateField;
		}

		$whereCharging 		= '';
		if($fields_date != 'application_stages.created' && !empty($from_date) && !empty($end_date))
	    {
	    	$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
			$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
	    	$whereCharging 	= ' and '.$fields_date.' between '.$StartTime.' and '.$EndTime;
	    }

    	$connection         = ConnectionManager::get('default');
    	$arrRequestSelected = $this->ApplicationGeoLocation->DefaultExportFields;
    	//$sql_first 			= $this->Applications->GetReReportFields($arrRequestSelected,$array_request);
		$order_by = 'ApplicationGeoLocation.id desc';
		$ApplicationsList 	= $this->ApplicationGeoLocation->getGeoLocationData_Downloadxl($array_request,$order_by,'');
		//echo"<pre>"; print_r($ApplicationsList); die();
        $ApplicationsListData 	= $ApplicationsList['list'];

    	//$sql_count 	= "	select count(0)";
		//$sql 		= $this->Applications->QueryStr($array_request);
		//$applicationData_output = $connection->execute($ApplicationsListData)->fetchAll('assoc');
		$applicationData_output = $ApplicationsListData->toArray();
		//echo"<pre>"; print_r($applicationData_output); die();
		require_once(ROOT . DS . 'vendor' . DS . 'PhpExcel' . DS . 'PHPExcel.php');

		// Create new PHPExcel object
		$objPHPExcel = new \PHPExcel();

		$objPHPExcel->getProperties()->setCreator("creator name");

		//HEADER
		$i=1;
		$j=1;
		$objPHPExcel->setActiveSheetIndex(0);
		$arrExportFields 	= $this->ApplicationGeoLocation->ExportFields;

		$arrReportFields 	= $this->ApplicationGeoLocation->arrReportFields;
		foreach ($arrExportFields as $Field_Name) {
			$RowName 	= $this->GetExcelColumnName($i);
			
			$ColTitle  	= $arrReportFields[$Field_Name];
			$objPHPExcel->getActiveSheet()->setCellValue($RowName.$j,$ColTitle);
			
			$i++;
		}
		$j++;

		$applicationData_output_array = json_decode(json_encode($applicationData_output), true);
		//echo"<pre>"; print_r($applicationData_output_array); die();
		foreach($applicationData_output_array as $key=>$application_data) {
			$this->WriteReReportData($objPHPExcel,$j,$application_data);
			$j++;
		}
		
		$objPHPExcel->getActiveSheet()->setTitle('Geo Coordinates MIS Data');
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);

	 	$fileName=time().'.xlsx';

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename='.$fileName);
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 2024 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
	    	
    }
     private function WriteReReportData($PhpExcel,$RowID,$Report_Data)
    {
    	$gridLevel 				= $this->ApiToken->arrGridLevel;
		$EndSTU 				= $this->ApiToken->arrEndSTU;
		$EndCTU 				= $this->ApiToken->arrEndCTU;
		$injectionLevel 		= $this->ApiToken->arrInjectionLevel;
    	$i = 1;
    	//echo"<pre>"; print_r($Report_Data); die();
    	foreach ($this->ApplicationGeoLocation->ExportFields as $Field_Name) {

    		$type_of_land 				=array('G' => 'Goverment Land', 'P' => 'Private Land','GL' => 'Geda Land', 'F' => 'Forest Land');
    		// Key to check
			$keyToCheckTL = $Report_Data['type_of_land'];
			if (array_key_exists($keyToCheckTL, $type_of_land)) {
			    // Display the value corresponding to the key
			     $type_of_land = $type_of_land[$keyToCheckTL]; 
			}

			$zone_drop_down 			=array('1' => '42 Q', '2' => '43 Q','3' => '42 R - North Gujarat', '4' => '43 R - North Gujarat');

    		$zonearray = array(1 => '42 Q', 2 => '43 Q',3 => '42 R - North Gujarat', 4 => '43 R - North Gujarat');
			// Key to check
			$keyToCheck = $Report_Data['zone'];
			if (array_key_exists($keyToCheck, $zonearray)) {
			    // Display the value corresponding to the key
			     $zone = $zonearray[$keyToCheck]; 
			}
			$approveddata = array(1 => 'Approved', 2 => 'Reject',3 => 'clashed', 4 => 'developer accept',5 =>'Internal clashed');
			// Key to check
			$keyToCheckAD = $Report_Data['approved'];
			if (array_key_exists($keyToCheckAD, $approveddata)) {
			    // Display the value corresponding to the key
			     $approvedD = $approveddata[$keyToCheckAD]; 
			}
    		$RowName = $this->GetExcelColumnName($i);
    		
    		$district 				= $this->DistrictMaster->find("list",['keyField'=>'id','valueField'=>'name','conditions'=>['state_id'=>4]])->toArray();
    		
			$taluka 				= $this->TalukaMaster->find("list",['keyField'=>'id','valueField'=>'name','conditions'=>['state_code'=>24]])->toArray();
			
			if(!empty($Report_Data['approved_by']) && isset($Report_Data['approved_by'])){
				$members_data 	= $this->Members->find("all",['fields'=>['id','address1','name'],'conditions'=>['id'=>$Report_Data['approved_by']]])->first();
			}
			
            	$key1 = $Report_Data['geo_district'];
				 // Check if the key is present in the array
                if (array_key_exists($key1, $district)) {
                    // If the key exists, show its value
                    $district_name = $district[$key1];
                    
                }
                $keyT = $Report_Data['geo_taluka'];
                // Check if the key is present in the array
                if (array_key_exists($keyT, $taluka)) {
                    // If the key exists, show its value
					$taluka_name = $taluka[$keyT];
               }
            

    		$RowData = "";
    		switch ($Field_Name) {
				case 'sr_no':
					$RowData = ($RowID-1);
					break;
				
				case 'installer_name':
					$RowData = $Report_Data['developers'][$Field_Name];
					break;
				case 'created_by':
					$RowData = $Report_Data['developer_customers']['name'];
					break;
				case 'registration_no':
					$RowData =  $Report_Data['applications'][$Field_Name];
					break;
				case 'application_type':
					$RowData = ($Report_Data[$Field_Name] == 3) ? 'Wind' : 'Hybrid';
					break;
				case 'zone':
					$RowData = isset( $zone) ?  $zone : '';
					break;
				case 'type_of_land':
					$RowData = isset( $type_of_land) ?  $type_of_land : '';
					break;
				case 'rlmm':
					$RowData = ($Report_Data[$Field_Name] == 'Y') ? 'Yes' : 'No';
					break;
				case 'geo_district':
					$RowData =  isset( $district_name) ?  $district_name : '';
					break;
				case 'geo_taluka':
					$RowData =  isset( $taluka_name) ?  $taluka_name : '';
					break;
				case 'query_raised':
					$RowData =  ($Report_Data[$Field_Name] == 1) ? 'Yes' : 'No';
					break;
				case 'wtg_verified':
					$RowData =  ($Report_Data[$Field_Name] == 1) ? 'Yes' : 'No';
					break;	
				case 'payment_status':
					$RowData =  ($Report_Data[$Field_Name] == 1) ? 'Done' : 'Not Done';
					break;
				case 'created_date':
					$RowData =  isset($Report_Data[$Field_Name])?date('m-d-Y H:i a',strtotime($Report_Data[$Field_Name])): '';
					break;
				case 'wtg_verified_date':
					$RowData =  isset($Report_Data[$Field_Name])?date('m-d-Y H:i a',strtotime($Report_Data[$Field_Name])): '';
					break;
				case 'query_raised_date':
					$RowData =  isset($Report_Data[$Field_Name])?date('m-d-Y H:i a',strtotime($Report_Data[$Field_Name])): '';
					break;
				case 'approved_date':
					$RowData =  isset($Report_Data[$Field_Name])?date('m-d-Y H:i a',strtotime($Report_Data[$Field_Name])): '';
					break;
				case 'clashed_for':
					if($Report_Data['geo_application_clashed_data']['clashed_for'] == 1){
						$clashed_for = 'Clashed';
					}else if($Report_Data['geo_application_clashed_data']['clashed_for'] == 2){
						$clashed_for = 'Internal Clashed';
					}else{
						$clashed_for = '';
					}
					$RowData =  $clashed_for;
					break;
				case 'clashed_date':
					$RowData =  isset($Report_Data['geo_application_clashed_data']['created'])?date('m-d-Y H:i a',strtotime($Report_Data['geo_application_clashed_data']['created'])): '';
					break;
				case 'payment_date':
					$RowData =  isset($Report_Data[$Field_Name])?date('m-d-Y H:i a',strtotime($Report_Data[$Field_Name])): '';
					break;
				case 'wtg_validity_date':
					$RowData =  isset($Report_Data[$Field_Name])?date('m-d-Y H:i a',strtotime($Report_Data[$Field_Name])): '';
					break;
				case 'approved_by':
					$RowData =  isset($members_data['address1']) ? $members_data['address1'] : '';
					break;
				case 'wtg_verified_by':
					$RowData =  isset($members_data['name']) ? $members_data['name'] : '';
					break;
				case 'query_raised_by':
					$RowData =  isset($members_data['name']) ? $members_data['name'] : '';
					break;
				case 'approved':
					$RowData = isset( $approvedD) ?  $approvedD : '';
					break;		
				// case 'submitted_on':
				// 	$RowData = ($Report_Data['created_date']);
				// 	break;	
				
				default:
					$RowData = isset($Report_Data[$Field_Name])?$Report_Data[$Field_Name]:"";
					break;
    		}
    		$PhpExcel->getActiveSheet()->setCellValue($RowName.$RowID,$RowData);
    		$i++;
    	}
    }
	public function GetExcelColumnName($num)
	{
		$str 			= '';
		$DEFAULT_NUMBER = 64;
		while ($num > 0) {
			$Module = ($num % 26);
			$Module = ($Module > 0?$Module:26);
			$str 	= chr( $Module + $DEFAULT_NUMBER) . $str;

			if($num == 53)
			{
				$num 	= (int) ($num / 26);
			}
			else
			{
				$num 	= (int) ($num / 27);
			}
		}
		return trim($str);
	}

	public function wtg_delete($id = null){

		$customer_id 		= $this->Session->read("Customers.id");
		
		$application_type   = (isset($this->request->data['application_type'])?$this->request->data['application_type']:0);
		$application_id     = (isset($this->request->data['application_id'])?$this->request->data['application_id']:0);
		
		if(empty($id)) {
			$this->Flash->error('Please Select Valid Installer.');
			return $this->redirect('home');
		} else {

			$encode_id 						= $id;
			$id 							= intval(decode($id));
			$applyOnlinesData 				= $this->Applications->viewApplication($id);
			$geo_application_data			= $this->ApplicationGeoLocation->find("all",['fields'=>['id','application_id','wtg_location','x_cordinate','y_cordinate','zone','land_survey_no'],'conditions'=>['application_id'=>$id]])->toArray();
		
		}
		$zone_drop_down 			=array('1' => '42 Q', '2' => '43 Q','3' => '42 R - North Gujarat', '4' => '43 R - North Gujarat');
		
		$applicationCategory 	= $this->ApplicationCategory->find('all',array('conditions'=>array('id'=>$applyOnlinesData->application_type)))->first();
		
		$this->set("applicationCategory",$applicationCategory);
		$this->set('id',$encode_id);
		$this->set('geo_application_data',$geo_application_data);
		$this->set('zone_drop_down',$zone_drop_down);
		$this->set('geo_location_charges',$applicationCategory->geo_location_charges);
		
		$this->set("pageTitle","Delete WTG Cordinates");
		$this->set('Applications',$applyOnlinesData);
		$this->set("MemberTypeDiscom",$this->Members->member_type_discom);
		$this->set("APPLY_ONLINE_GUJ_STATUS",$this->ApplicationStages->apply_online_guj_status);
		$this->set("MStatus",$this->ApplicationStages);
		$this->set('logged_in_id',$this->Session->read('Customers.id'));
		$this->set('encode_id',$encode_id);
		
		$this->set('Couchdb',$this->ReCouchdb);
		$this->set('ApplicationGeoLocation',$this->ApplicationGeoLocation);
	}

	public function geo_wtg_delete()
	{	
		$this->autoRender 	= false;

		$geo_id 				= (isset($this->request->data['GeoDeleteWTG_geo_application_id'])?$this->request->data['GeoDeleteWTG_geo_application_id']:0);
		
		if(empty($geo_id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		}	else{
			$customer_id 		= $this->Session->read("Customers.id");
			
				if(!empty($geo_id)){

					$geo_application_data = $this->ApplicationGeoLocation->find('all',array('conditions'=>array('id'=>$geo_id)))->first();
					
					$deleteentity                  		= $this->ApplicationGeoLocationDeleted->newEntity();
					$deleteentity->geo_id  				= $geo_id;
					$deleteentity->geo_application_id  	= $geo_application_data->application_id;
					$deleteentity->application_data  	= json_encode($geo_application_data);
					$deleteentity->deleted_by 		   	= $customer_id;
					$deleteentity->deleted 		   		= $this->NOW();
					$this->ApplicationGeoLocationDeleted->save($deleteentity);
					
					$this->ApplicationGeoLocation->deleteAll(['id'=>$geo_id]);

					$ErrorMessage 	= "Record Deleted Succesfully";
					$success 		= 1;
					$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
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

	public function modify_make($id = null){

		
		$customer_id 		= $this->Session->read("Customers.id");
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
			$geo_application_data			= $this->ApplicationGeoLocation->find("all",['fields'=>['id','application_id','wtg_location','rlmm','wtg_make','wtg_model','wtg_capacity','wtg_rotor_dimension','wtg_hub_height'],'conditions'=>['application_id'=>$id]])->toArray();
		
		}
		$zone_drop_down 			=array('1' => '42 Q', '2' => '43 Q','3' => '42 R - North Gujarat', '4' => '43 R - North Gujarat');
		
		$applicationCategory 	= $this->ApplicationCategory->find('all',array('conditions'=>array('id'=>$applyOnlinesData->application_type)))->first();
				  	
		
		$this->set("applicationCategory",$applicationCategory);
		$this->set('id',$encode_id);
		$this->set('geo_application_data',$geo_application_data);
		$this->set('zone_drop_down',$zone_drop_down);
		$this->set('geo_location_charges',$applicationCategory->geo_location_charges);
		
		$this->set("pageTitle","WTG Cordinates Modify Make");
		$this->set('Applications',$applyOnlinesData);
		$this->set("MemberTypeDiscom",$this->Members->member_type_discom);
		$this->set("APPLY_ONLINE_GUJ_STATUS",$this->ApplicationStages->apply_online_guj_status);
		$this->set("MStatus",$this->ApplicationStages);
		$this->set('logged_in_id',$this->Session->read('Customers.id'));
		$this->set('is_member',$is_member);
		$this->set('encode_id',$encode_id);
		
		$this->set('Couchdb',$this->ReCouchdb);
		$this->set('ApplicationGeoLocation',$this->ApplicationGeoLocation);
	}

	/**
	 * geo_location_save_offline_data
	 * Behaviour : Public
	 * @defination : Method is use to save the geo_location data.
	 */
	public function geo_location_save_offline_data()
	{	
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['AddOfflineApplication_application_id'])?$this->request->data['AddOfflineApplication_application_id']:0);
		$application_type 	= (isset($this->request->data['AddOfflineApplication_application_type'])?$this->request->data['AddOfflineApplication_application_type']:0);
		
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
				
				if((empty($this->request->data['wtg_location']) || (empty($this->request->data['app_reg_no'])) || 
					(empty($this->request->data['installer_name'])) || (empty($this->request->data['district'])) || (empty($this->request->data['taluka'])) || (empty($this->request->data['village'])) || (empty($this->request->data['zone'])) || (empty($this->request->data['offline_approved_date'])) ||
					(empty($this->request->data['x_cordinate'])) || (($this->request->data['x_cordinate']) < 111111.000) || (empty($this->request->data['y_cordinate']))|| (($this->request->data['y_cordinate']) < 1111111.000)))
				{
						$ErrorMessage 	= "Please select all the details as per Required.";
						$success 		= 0;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
				} else {
					if(!empty($this->request->data['app_reg_no'])){

						$district 		= $this->DistrictMaster->find("list",['keyField'=>'id','valueField'=>'name','conditions'=>['id'=>
							$this->request->data['district']]])->first();
						
						$taluka 				= $this->TalukaMaster->find("list",['keyField'=>'id','valueField'=>'name','conditions'=>['id'=>$this->request->data['taluka']]])->first();

						$approved_date = date('Y-m-d',strtotime($this->request->data['offline_approved_date']));
						$arr_modules								= $this->GeoCoordinateOfflineApproved->newEntity();
						$arr_modules['application_id'] 				= 0;
						$arr_modules['app_reg_id'] 					= $id;
						$arr_modules['wtg_location']				= $this->request->data['wtg_location'];
						$arr_modules['app_reg_no']					= $this->request->data['app_reg_no'];
						$arr_modules['installer_name']				= $this->request->data['installer_name'];
						$arr_modules['application_type']			= $application_type;
						$arr_modules['village']						= $this->request->data['village'];
						$arr_modules['taluka']						= $taluka;
						$arr_modules['district']					= $district;
						$arr_modules['remark']						= 'NO CLASHING';
						$arr_modules['approved']					= 1;
						$arr_modules['approved_by']					= 1;
						$arr_modules['zone']						= $this->request->data['zone'];
						$arr_modules['x_cordinate']					= $this->request->data['x_cordinate'];
						$arr_modules['y_cordinate']					= $this->request->data['y_cordinate'];
						$arr_modules['approved_date']				= $approved_date;
						$arr_modules['created']						= $this->NOW();
						$arr_modules['created_by']					= isset($customer_id)?$customer_id:$member_id;
						
						$this->GeoCoordinateOfflineApproved->save($arr_modules);
						
						
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
	 * geo_location_save_offline_data
	 * Behaviour : Public
	 * @defination : Method is use to save the geo_location data.
	 */
	public function save_offline_data()
	{	
		$this->autoRender 	= false;
		
		$member_id 				= $this->Session->read("Members.id");
		//echo"<pre>"; print_r($member_id); die();
		if (!empty($member_id)) {
			
			if((empty($this->request->data['wtg_location']) ||  (empty($this->request->data['zone'])) || (empty($this->request->data['offline_approved_date'])) ||
				(empty($this->request->data['x_cordinate'])) || (($this->request->data['x_cordinate']) < 111111.000) || (empty($this->request->data['y_cordinate']))|| (($this->request->data['y_cordinate']) < 1111111.000)))
			{
					$ErrorMessage 	= "Please select all the details as per Required.";
					$success 		= 0;
					$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
					$this->ApiToken->SetAPIResponse('success',$success);
			} else {
				if(!empty($this->request->data['wtg_location'])){
					if(!empty($this->request->data['district'])){
						$district 		= $this->DistrictMaster->find("list",['keyField'=>'id','valueField'=>'name','conditions'=>['id'=>
						$this->request->data['district']]])->first();
					}
					
					if(!empty($this->request->data['taluka'])){
					$taluka 				= $this->TalukaMaster->find("list",['keyField'=>'id','valueField'=>'name','conditions'=>['id'=>$this->request->data['taluka']]])->first();
					}
					
					$approved_date = date('Y-m-d',strtotime($this->request->data['offline_approved_date']));
					$arr_modules								= $this->GeoCoordinateOfflineApproved->newEntity();
					$arr_modules['application_id'] 				= 0;
					$arr_modules['app_reg_id'] 					= '';
					$arr_modules['wtg_location']				= $this->request->data['wtg_location'];
					$arr_modules['app_reg_no']					= $this->request->data['app_reg_no'];
					$arr_modules['installer_name']				= $this->request->data['installer_name'];
					$arr_modules['application_type']			= 3;
					$arr_modules['village']						= $this->request->data['village'];
					$arr_modules['taluka']						= (isset($taluka) && !empty($taluka)) ? $taluka:'';
					$arr_modules['district']					= (isset($district) && !empty($district)) ? $district:'';
					$arr_modules['remark']						= 'NO CLASHING';
					$arr_modules['approved']					= 1;
					$arr_modules['approved_by']					= 1;
					$arr_modules['zone']						= $this->request->data['zone'];
					$arr_modules['x_cordinate']					= $this->request->data['x_cordinate'];
					$arr_modules['y_cordinate']					= $this->request->data['y_cordinate'];
					$arr_modules['approved_date']				= $approved_date;
					$arr_modules['created']						= $this->NOW();
					$arr_modules['created_by']					= isset($customer_id)?$customer_id:$member_id;
					
					$this->GeoCoordinateOfflineApproved->save($arr_modules);
					
					
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
		
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
}

