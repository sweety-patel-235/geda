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
class GeoShiftingApplicationController extends FrontAppController
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
		$this->loadModel('GeoApplicationVerification');
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
		$this->loadModel('ApplicationHybridAdditionalData');
		$this->loadModel('SendRegistrationFailure');
		$this->loadModel('ReCouchdb');
		$this->loadModel('Developers');
		$this->loadModel('DeveloperCustomers');
		$this->loadModel('DistrictMaster');
		$this->loadModel('ManufacturerMaster');
		$this->loadModel('Applications');
		$this->loadModel('ApplicationCategory');
		$this->loadModel('ApplicationStages');
		$this->loadModel('ApplicationsDocs');
		$this->loadModel('ApplicationGeoLocation');
		$this->loadModel('GeoShiftingApplicationRejectLog');
		$this->loadModel('GeoShiftingApplicationPayment'); 
		$this->loadModel('GeoApplicationClashedData');
		$this->loadModel('GeoCoordinateOfflineApproved');
		$this->loadModel('TalukaMaster');
		$this->loadModel('GeoShiftingApplication');
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
		//echo"<pre>"; print_r($is_member); die();
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

			if(!empty($member_id)){
				$condition 	= array('ApplicationGeoLocation.application_id'=>$id,'geo_shifting_application.payment_status'=>1,'ApplicationGeoLocation.approved'=>1); //Add code (,'OR'=>['approved is NULL','approved is NOT'=>1])if u dont want to see approve application to member
			}else{
				$condition 	= array('ApplicationGeoLocation.application_id'=>$id,'ApplicationGeoLocation.approved'=>1);
			}
			
			// $geo_application_data			= $this->ApplicationGeoLocation->find("all",['fields'=>['id','application_id','wtg_location','x_cordinate','y_cordinate','zone','land_survey_no'],'conditions'=>['application_id'=>$id,'approved'=>1]])->toArray();
			$geo_application_data = $this->ApplicationGeoLocation->find('all',
		                                        [ 'fields'=>['id','application_id','wtg_location','x_cordinate','y_cordinate','zone','land_survey_no','geo_shifting_application.modified_zone','geo_shifting_application.payment_status','geo_shifting_application.approved','geo_shifting_application.modified_x_cordinate','geo_shifting_application.modified_y_cordinate','geo_shifting_application.wtg_verified','geo_shifting_application.id','geo_shifting_application.old_x_cordinate','geo_shifting_application.old_y_cordinate','geo_shifting_application.old_zone',],
		                                            'join'=>[['table'=>'geo_shifting_application','type'=>'left','conditions'=>'ApplicationGeoLocation.id = geo_shifting_application.geo_application_id']],
		                                            'conditions'=>$condition])->toArray();	
			//echo"<pre>"; print_r($geo_application_data); die();
		
		}
		$zone_drop_down 			=array('1' => '42 Q', '2' => '43 Q','3' => '42 R - North Gujarat', '4' => '43 R - North Gujarat');
		//echo"<pre>"; print_r($geo_application_data); die();
		$applicationCategory 	= $this->ApplicationCategory->find('all',array('conditions'=>array('id'=>$applyOnlinesData->application_type)))->first();
		// $applicationCategory 		= $this->ApplicationCategory->find("all",['conditions'=>['id'=>$applyOnlinesData->application_type]])->first();

		//echo"<pre>"; print_r($applicationCategory); die();	
		$Geo_application_paymet_log = $this->GeoShiftingApplicationPayment->find("all",['conditions'=>['application_id'=>$id,'payment_status'=>'success']])->toArray();	

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
		$Geo_application_verification_log = $this->GeoApplicationVerification->find("all",['conditions'=>['application_id'=>$applyOnlinesData->id,'shifted'=>'Yes']])->toArray();
			//echo"<pre>"; print_r($Geo_application_verification_log); die();
		$this->set("applicationCategory",$applicationCategory);
		$this->set('id',$encode_id);
		$this->set('geo_application_data',$geo_application_data);
		$this->set('zone_drop_down',$zone_drop_down);
		$this->set('Geo_application_verification_log',$Geo_application_verification_log);
		$this->set('geo_location_charges',$applicationCategory->geo_location_charges);
		$this->set('Geo_application_paymet_log',$Geo_application_paymet_log);
		$this->set("LocationList_internal",$LocationList_internal);
	  	$this->set("LocationList",$LocationList);
		
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
	
	public function Add_ModifyWTG()
	{	
		
		$this->autoRender 	= false;
		$geo_application_id 				= (isset($this->request->data['ModifyWTG_geo_application_id'])?$this->request->data['ModifyWTG_geo_application_id']:0);
		$id 				= (isset($this->request->data['ModifyWTG_application_id'])?$this->request->data['ModifyWTG_application_id']:0);
		//echo"<pre>"; print_r($id); die();
		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} 
		else{
			$applicationData 		= $this->Applications->viewApplication($id);
			//echo"<pre>"; print_r($applicationData); die();
			$customer_id 			= $this->Session->read("Customers.id");
			$member_id 				= $this->Session->read("Members.id");
			if (!empty($applicationData) && (!empty($customer_id) || !empty($member_id))) {
				//echo"<pre>"; print_r($customer_id); die();
				if(empty($this->request->data['x_cordinate']) || empty($this->request->data['y_cordinate']) || empty($this->request->data['zone']))
				{
						$ErrorMessage 	= "Please select all the details.";
						$success 		= 0;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
				} 
				else {
					$geo_shifting_data   = $this->GeoShiftingApplication->find('all',array('conditions'=>array('geo_application_id'=>$geo_application_id)))->first();
					if(empty($geo_shifting_data)){
						$geo_application_data   = $this->ApplicationGeoLocation->find('all',array('conditions'=>array('id'=>$geo_application_id)))->first();

						$arr_modules								= $this->GeoShiftingApplication->newEntity();
						
						$arr_modules['geo_application_id']			= $geo_application_id;
						$arr_modules['wtg_location']				= $geo_application_data->wtg_location;
						$arr_modules['land_survey_no']				= $geo_application_data->land_survey_no;
						
						$arr_modules['old_zone']					= $geo_application_data->zone;
						$arr_modules['old_x_cordinate']				= $geo_application_data->x_cordinate;
						$arr_modules['old_y_cordinate']				= $geo_application_data->y_cordinate;

						$arr_modules['modified_zone']				= $this->request->data['zone'];
						$arr_modules['modified_x_cordinate']		= $this->request->data['x_cordinate'];
						$arr_modules['modified_y_cordinate']		= $this->request->data['y_cordinate'];
						
						$this->GeoShiftingApplication->save_data($id,$arr_modules,$customer_id);
						
						
						$ErrorMessage 	= "Record Added Succesfully";
						$success 		= 1;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					}else if(!empty($geo_shifting_data)){
						$arr_modules['modified_zone']				= $this->request->data['zone'];
						$arr_modules['modified_x_cordinate']		= $this->request->data['x_cordinate'];
						$arr_modules['modified_y_cordinate']		= $this->request->data['y_cordinate'];
						
						$this->GeoShiftingApplication->updateAll($arr_modules,array('geo_application_id'=>$geo_application_id));
						$ErrorMessage 	= "Record updated Succesfully";
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
	
	public function geo_shifting_approvedata()
	{	
		$this->autoRender 	= false;
		$geo_id 				= (isset($this->request->data['GeoShiftingApprove_geo_application_id'])?$this->request->data['GeoShiftingApprove_geo_application_id']:0);
		
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
					
					$this->GeoShiftingApplication->updateAll($arr_modules,array('geo_application_id'=>$geo_id));

					$shifting_data = $this->GeoShiftingApplication->find('all',array('conditions'=>array('geo_application_id'=>$geo_id)))->first();
					$arr_module['zone']					= $shifting_data->modified_zone;
					$arr_module['x_cordinate']			= $shifting_data->modified_x_cordinate;
					$arr_module['y_cordinate']			= $shifting_data->modified_y_cordinate;
					
					$this->ApplicationGeoLocation->updateAll($arr_module,array('id'=>$geo_id));

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
	public function geo_shifting_rejectdata()
	{	
		//echo"<pre>"; print_r($this->request->data); die();
		$this->autoRender 	= false;
		$geo_application_id 				= (isset($this->request->data['GeoShiftingReject_geo_application_id'])?$this->request->data['GeoShiftingReject_geo_application_id']:0);
		$shifting_id 				= (isset($this->request->data['GeoShiftingReject_shifting_id'])?$this->request->data['GeoShiftingReject_shifting_id']:0);
		
		if(empty($geo_application_id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		}	else{
			
			$member_id 				= $this->Session->read("Members.id");
			$geo_application_data   = $this->GeoShiftingApplication->find('all',array('conditions'=>array('id'=>$shifting_id)))->first();

				$browser 					   		= isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:"-";
				$rejectentity                  		= $this->GeoShiftingApplicationRejectLog->newEntity();
				$rejectentity->geo_application_id  	= $geo_application_id;
				$rejectentity->geo_shifting_application_id  	= $shifting_id ;
				$rejectentity->application_id  		= $geo_application_data->application_id;
				$rejectentity->member_id     		= $member_id;
				$rejectentity->ip_address      		= $_SERVER['REMOTE_ADDR'];
				$rejectentity->reject_reason      	= $this->request->data['reject_reason'];
				$rejectentity->browser_info	   		= json_encode($browser);
				$rejectentity->application_data		= json_encode($geo_application_data);
				$rejectentity->created 		   		= $this->NOW();
				$this->GeoShiftingApplicationRejectLog->save($rejectentity);
				
			 if(!empty($member_id)) {
				if(!empty($this->request->data['reject_reason'])){

					$arr_modules['approved']				= 2;
					$arr_modules['approved_by']				= $member_id;
					$arr_modules['approved_date']			= $this->NOW();
					
					$this->GeoShiftingApplication->updateAll($arr_modules,array('id'=>$shifting_id));
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
		//echo"<pre>"; print_r($this->request->data); die();
		$this->autoRender 	= false;
		$shifting_id 				= (isset($this->request->data['shifting_id'])?$this->request->data['shifting_id']:0);
		
		if(empty($shifting_id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		}	else{
			//$encode_id 				= $id;
			//$id 					= intval(decode($id));
			$member_id 				= $this->Session->read("Members.id");
			$geo_application_data   = $this->GeoShiftingApplicationRejectLog->find('all',array('conditions'=>array('geo_shifting_application_id'=>$shifting_id)))->toArray();
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
	* downloadGeoShiftingApplicationPdf
	* Behaviour : Public
	* @defination : Method is use to download geo application pdf
	*/
	public function downloadGeoShiftingApplicationPdf($id = null)
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
		$application_data = $this->Applications->generateGeoShiftingPaymentReceiptPdf($id);
		if(empty($application_data)) {
			$this->Flash->error('Please Select Valid Application.');
			return $this->redirect('home');
		}
	}

	/**
	 * geo_location_clashdata
	 * Behaviour : Public
	 * @defination : Method is use to get the geo_location_clashdata data.
	 */
	public function geo_location_shifting_clashdata()
	{	
		//echo"<pre>"; print_r($this->request->data); die();
		$this->autoRender 	= false;
		$geo_application_id = (isset($this->request->data['GeoShiftingClash_geo_id'])?$this->request->data['GeoShiftingClash_geo_id']:0);
		$shifting_id = (isset($this->request->data['GeoShiftingClash_shifting_id'])?$this->request->data['GeoShiftingClash_shifting_id']:0);
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
						$clashentity->shifting_id  			= $shifting_id;
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
						$clashentity->shifting_id  			= $shifting_id;
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
				
					$this->Add_clashed_data($geo_application_id,$shifting_id);
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

	public function Add_clashed_data($geo_application_id,$shifting_id){

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

		$clashed_reason = "Shifted WTG Coordinates Clashing with : " .$developer_name .", location : " .$wtg_location .", Zone : " .$zone .", UTM Easting : " . $x_cordinate . ", UTM Northing : ". $y_cordinate. ", GEDA Remark : ". $remark;
		$arr_modules['comment'] 		= $clashed_reason;
		//$arr_module['approved']		= 3;  //approved_by  approved_date
		//$arr_module['wtg_verified'] = 0;   //wtg_verified_by wtg_verified_date
		//$this->ApplicationGeoLocation->updateAll($arr_module,array('id'=>$geo_application_id));

		$arr_modules['approved']		= 3;  //approved_by  approved_datepp
		//echo"<pre>"; print_r($arr_modules); die();

		$this->GeoShiftingApplication->updateAll($arr_modules,array('geo_application_id'=>$geo_application_id));
	}

	/**
	 * geo_location_clashdata_internal
	 * Behaviour : Public
	 * @defination : Method is use to Add internal clashed Data in the geo_location data.
	 */
	public function geo_location_clashdata_internal()
	{	

		$this->autoRender 	= false;
		$geo_application_id 				= (isset($this->request->data['GeoShiftingClashInternal_geo_id'])?$this->request->data['GeoShiftingClashInternal_geo_id']:0);
		$shifting_id = (isset($this->request->data['GeoShiftingClashInternal_shifting_id'])?$this->request->data['GeoShiftingClashInternal_shifting_id']:0);
		$approved_geo_ids 				= (isset($this->request->data['approved_geo_id'])?$this->request->data['approved_geo_id']:0);
		$internal_clashed_remark 	= (isset($this->request->data['internal_clashed_remark'])?$this->request->data['internal_clashed_remark']:'');
		//echo"<pre>"; print_r($this->request->data); die();

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
						$clashentity->shifting_id  			= $shifting_id;
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


						//$arr_moduless['approved']			= 5;
						//$this->ApplicationGeoLocation->updateAll($arr_moduless,array('id'=>$geo_application_id));


						$zonearray = array(1 => '42 Q', 2 => '43 Q',3 => '42 R - North Gujarat', 4 => '43 R - North Gujarat');
						// Key to check
						$keyToCheck = $geo_application_data->zone;
						if (array_key_exists($keyToCheck, $zonearray)) {
						    // Display the value corresponding to the key
						     $zone = $zonearray[$keyToCheck]; 
						}
						$clashentity                  		= $this->GeoApplicationClashedData->newEntity();
						$clashentity->clashed_geo_id  		= $geo_application_id;
						$clashentity->shifting_id  			= $shifting_id;
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
				$this->Add_internalclashed_data($geo_application_id,$shifting_id);
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

	public function Add_internalclashed_data($geo_application_id,$shifting_id){

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

		
		$clashed_reason = "Shifted WTG Coordinates Internal Clashing with : " .$developer_name .", location : " .$wtg_location .", Zone : " .$zone .", UTM Easting : " . $x_cordinate . ", UTM Northing : ". $y_cordinate. ", GEDA Remark : ". $remark;


		$arr_modules['comment'] = $clashed_reason;
		//$this->ApplicationGeoLocation->updateAll($arr_module,array('id'=>$geo_application_id));

		$arr_modules['approved']		= 4;  //approved_by  approved_datepp
		//echo"<pre>"; print_r($arr_modules); die();

		$this->GeoShiftingApplication->updateAll($arr_modules,array('geo_application_id'=>$geo_application_id));
	}

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
	public function geo_location_shifting_verifydata()
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
					$geo_application_data   = $this->GeoShiftingApplication->find('all',array('conditions'=>array('geo_application_id IN'=>$geo_id)))->first();
						
			 		$ApplicationsVerifyEntity = $this->GeoApplicationVerification->newEntity();
							
							$ApplicationsVerifyEntity->geo_id			= $geo_id;
							$ApplicationsVerifyEntity->shifted    		= 'Yes';
							$ApplicationsVerifyEntity->application_id	= $geo_application_data->application_id;
							$ApplicationsVerifyEntity->created          = $this->NOW();
							$ApplicationsVerifyEntity->created_by       = $member_id;
						
					$this->GeoApplicationVerification->save($ApplicationsVerifyEntity);
					$geo_id_array = explode( ',' ,$geo_id);
					
					foreach ($geo_id_array as $key => $value) {
						
						$arr_moduless['wtg_verified']			= 1;
						$arr_moduless['wtg_verified_by']		= $member_id;
						$arr_moduless['wtg_verified_date']		= $this->NOW();
							
						$this->GeoShiftingApplication->updateAll($arr_moduless,array('geo_application_id'=>$value));
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
	* downloadGeoApplicationVerifiedPdf
	* Behaviour : Public
	* @defination : Method is use to download geo application pdf
	*/
	public function downloadGeoApplicationShiftingVerifiedPdf($id = null)
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
		$application_data = $this->GeoShiftingApplication->generateGeoApplicationShiftingVerifiedPdf($id);
		if(empty($application_data)) {
			$this->Flash->error('Please Select Valid Application.');
			return $this->redirect('home');
		}
	}


	public function GeoLocationShiftingReport()
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
			$this->Session->delete("Customers.SearchshiftingApplication");
			$this->Session->delete("MembersSearchshiftingApplication");
			$this->Session->delete("Customers.Page");
			return $this->redirect(URL_HTTP.'applications-list');
		}
		
		$this->removeExtraTags();

		if(isset($this->request->data['Search']) && !empty($this->request->data['Search'])){
			$this->Session->write("MembersSearchshiftingApplication",$this->request->data);
			$this->Session->write('Customers.SearchshiftingApplication',serialize($this->request->data));
		} else {
			if($this->Session->check("MembersSearchshiftingApplication")) {
				$this->request->data = $this->Session->read("MembersSearchshiftingApplication");
			}
			if($this->Session->check("Customers.SearchshiftingApplication"))
			{
				$this->request->data = unserialize($this->Session->read("Customers.SearchshiftingApplication"));
			}
		} 
		$consumer_no 			= isset($this->request->data['consumer_no']) ? $this->request->data['consumer_no'] : '';
		$application_search_no 	= isset($this->request->data['provisional_search_no']) ? $this->request->data['provisional_search_no'] : '';
		$installer_name 		= (isset($this->request->data['installer_name'])) ? $this->request->data['installer_name'] : '';
		$discom_name 			= isset($this->request->data['discom_name']) ? $this->request->data['discom_name'] : '';
		$payment_status 		= isset($this->request->data['payment_status']) ? $this->request->data['payment_status'] : '';
		$order_by_form 			= isset($this->request->data['order_by_form']) ? $this->request->data['order_by_form'] : 'GeoShiftingApplication.payment_date|DESC';
		
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
        $this->SortBy		= "geo_shifting_application.created_date";
        $this->Direction	= "DESC";
        $this->intLimit		= 25;
        $this->CurrentPage  = 1;
        $option 			= array();

        $option['colName']  = array('id','registration_no','payment_date','wtg_location','old_x_cordinate','old_y_cordinate','modified_x_cordinate','modified_y_cordinate','installer_name','action_by','status','action');
		
        $sortArr=array('registration_no'=>'applications.registration_no');

        $this->SetSortingVars('GeoShiftingApplication',$option,$sortArr);

      	$ApplicationsList 	= $this->GeoShiftingApplication->getGeoLocationShiftingData($this->request->data,$this->SortBy,$this->Direction);
		//echo"<pre>"; print_r($ApplicationsList); die();
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
       
        $arr_status_dropdown 			= array('3'	=> 'Clashed',
											'1'	=> 'Non Clashed',
											'4'	=> 'Internal Clashed',
											'2'	=> 'Rejected');
        unset($arr_status_dropdown['99']);
        $JqdTablescr 			= $this->JqdTable->create($option);
       	$installers_list 		= $this->Developers->getInstallerListReport();
        $applicationCategory 	= $this->ApplicationCategory->find('list',array('keyField'=>'id','valueField'=>'category_name','conditions'=>array('id IN '=>array(3,4))))->toArray();
		$geo_id_str ='';
		$geo_id_arr = array();
        
        $geo_data_arr =$arrAdminuserList->toArray();
        //echo"<pre>"; print_r($geo_data_arr ); die();
        foreach ($geo_data_arr as $key => $value) {
        	$geo_id_arr[]= $value['geo_application_id'];
        }
        
       		 $geo_id_str = $geo_id_arr;
       		if(!empty($geo_id_str)){
        	 $geo_id_str = implode(',', $geo_id_arr);
        	}
       
       
        $district 				= $this->DistrictMaster->find("list",['keyField'=>'id','valueField'=>'name','conditions'=>['state_id'=>4]])->toArray();
		$taluka 				= $this->TalukaMaster->find("list",['keyField'=>'id','valueField'=>'name','conditions'=>['state_code'=>24]])->toArray();
		$action_by 	= array('Gandhidham','Porbandar');
		$wtg_verified = array('1'=>'Verified','0'=>'Pending');
		
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

        $out 		=array();
        $counter 	= '1';
        $page_mul 	= ($this->CurrentPage-1);

        foreach($arrAdminuserList->toArray() as $keys=>$val) {
        	//$members  					= $this->Members->find("all",['fields'=>['id','address1','name'],'conditions'=>['id'=>$val->approved_by]])->first();
        	
			$remainingday 			= $this->ApplicationGeoLocation->CheckValidityData($val['geo_application_id'],$val['application_id']);
			$clash_data 			= $this->ApplicationGeoLocation->CheckClashData($val->x_cordinate,$val->y_cordinate,$val->id);
			$member_clash_data 		= $this->ApplicationGeoLocation->Member_CheckClashData($val['geo_application_id']);
			$internal_clashed_docs 	=  $this->ApplicationGeoLocation->internal_clashed_docs($val['geo_application_id']);
				

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
             //echo"<pre>"; print_r($val); die();
                if($key=='id') {
                   $temparr[$key]= $counter+($page_mul*10);
                   $counter++;
                }
                if($key=='registration_no') {
                   $temparr[$key]= $val['applications']['registration_no'];
                }
                if($key=='payment_date') {
                   $temparr[$key]= isset($val['payment_date'])?date("Y-m-d H:i A",strtotime($val['payment_date'])):'';
                }
                if($key=='wtg_location') {
                   $temparr[$key]= $val['wtg_location'];

                } 
                if($key=='old_x_cordinate') {
                   $temparr[$key]= $val['old_x_cordinate'];

                } 
                if($key=='old_y_cordinate') {
                   $temparr[$key]= $val['old_y_cordinate'];

                } 
                if($key=='modified_x_cordinate') {
                   $temparr[$key]= $val['modified_x_cordinate'];

                } 
                if($key=='modified_y_cordinate') {
                   $temparr[$key]= $val['modified_y_cordinate'];

                } 
      //           if($key=='geo_district') {
      //           	$key1 = $val['geo_district'];
					 // // Check if the key is present in the array
      //               if (array_key_exists($key1, $district)) {
      //                   // If the key exists, show its value
      //                   $district_name = $district[$key1];
                        
      //               }
      //              $temparr[$key]= $district_name;

      //           }
      //           if($key=='geo_taluka') {
      //           	$keyT = $val['geo_taluka'];
      //               // Check if the key is present in the array
      //               if (array_key_exists($keyT, $taluka)) {
      //                   // If the key exists, show its value
						// $taluka_name = $taluka[$keyT];
      //              }
      //              $temparr[$key]= $taluka_name;

      //           }
                if($key=='installer_name') {
					$temparr[$key]= ucwords($val['developers']['installer_name']);
					 
				}
				if($key=='action_by') {
					$temparr[$key]= (isset($members->address1)?$members->address1 : '-');
				}
				if($key=='status') {
					// /echo"<pre>"; print_r($val); die();
						if($val['wtg_verified'] == 1){
							$wtg_verified = 'Verified';
						}else{
							$wtg_verified = 'Pending';
						}
					$temparr[$key]= (isset($val['wtg_verified'])?$wtg_verified : '-');
					
				}//view_shifted_coordinates_details
				if($key=='action') {
                	// $paths = URL_HTTP.'view-applications-geo-location/'.encode($val->id);
                	$url = URL_HTTP.'GeoShiftingApplication/view_shifted_coordinates_details/'.encode($val->id);
                	$title = 'View Shifted Geo Coordinates Details';
                	$geo_id = $val['geo_application_id'];

                	if(!empty($geo_id_str) && $val['wtg_verified'] != 1 &&($clash_text != '' || $val['approved'] != NULL ) ){
                		$temparr[$key]	='<button type="button" class="btn btn-sm" style="color:white;background-color: #307FE2;"  onclick="javascript:showModel(\''.$title.'\',\''.$url.'\');" > Action</button>

                		<input type="checkbox" id="'.$geo_id.' " class = "verify" name="verify" onclick="javascript:show_check();">'	;
                	}else{
                		$temparr[$key]	='<button type="button" class="btn btn-sm" style="color:white;background-color: #307FE2;"  onclick="javascript:showModel(\''.$title.'\',\''.$url.'\');" > Action</button>'	;
                	}
              
				}
                
            }
            $out[]=$temparr;
             
        } //echo"<pre>"; print_r($out); die();
        if ($this->request->is('ajax'))
        {
            header('Content-type: application/json');
            echo json_encode(array(	"condi" 			=> $arrCondition,
            						"draw" 				=> intval($this->request->data['draw']),
					                "recordsTotal"    	=> intval( $this->request->params['paging']['GeoShiftingApplication']['count']),
					                "recordsFiltered" 	=> intval( $this->request->params['paging']['GeoShiftingApplication']['count']),
					                "data"            	=> $out));
            die;
        }
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
	public function view_shifted_coordinates_details($id = null)
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
			
			$geo_location_data 		= $this->GeoShiftingApplication->find("all",['conditions'=>['id'=>$id]])->first();
			$applyOnlinesData 		= $this->Applications->viewApplication($geo_location_data->application_id);

			
		}
		
		$applicationCategory 	= $this->ApplicationCategory->find('all',array('conditions'=>array('id'=>$applyOnlinesData->application_type)))->first();
		$application_id 		= $geo_location_data->application_id;

		// $remainingday 			= $this->ApplicationGeoLocation->CheckValidityData($id,$application_id);
		// $clash_data 			= $this->ApplicationGeoLocation->CheckClashData($geo_location_data->x_cordinate,$geo_location_data->y_cordinate,$id);
		// $member_clash_data 		= $this->ApplicationGeoLocation->Member_CheckClashData($id);
		 $internal_clashed_docs 	=  $this->ApplicationGeoLocation->internal_clashed_docs($id);
		
		// if(!empty($clash_data)){
		// 		if($clash_data == 'Clashing'){
		// 			$clash_text = '<p style="text-decoration: underline;color: #307FE2;">Clashing</p>';
		// 		} elseif($clash_data == 'Internal Clashing'){
		// 			$clash_text = '<p style="text-decoration: underline;color: #cdcd09;">Internal Clashing</p>';
		// 		}
		// }else if(!empty($member_clash_data)){
		// 		if($member_clash_data == 'Clashing'){
		// 			$clash_text = '<p style="text-decoration: underline;color: #307FE2;">Clashing</p>';
		// 		}elseif($member_clash_data == 'Internal Clashing'){
		// 			$clash_text = '<p style="text-decoration: underline;color: #cdcd09;">Internal Clashing</p>';
		// 		}
		// }else{
		// 	$clash_text = '';
		// }
	
		$clash_text = '';

		if(!empty($member_id)){
				$condition 	= array('id'=>$id,'payment_status IN'=>array(1,2)); //Add code (,'OR'=>['approved is NULL','approved is NOT'=>1])if u dont want to see approve application to member
		}else{
			$condition 	= array('id'=>$id);
		}
		$geo_application_data			= $this->GeoShiftingApplication->find("all",['conditions'=>$condition])->first();
		$geo_application_rejected_data	= $this->GeoShiftingApplication->find("all",['conditions'=>array('id'=>$id,'approved'=>2)])->first();



		$Geo_application_paymet_log = $this->GeoShiftingApplicationPayment->find("all",['conditions'=>['application_id'=>$applyOnlinesData->application_id,'payment_status'=>'success']])->toArray();
		
		$Geo_application_verification_log = $this->GeoApplicationVerification->find("all",['conditions'=>['application_id'=>$applyOnlinesData->application_id,'shifted'=>'Yes']])->toArray();
		//echo"<pre>"; print_r($Geo_application_verification_log); die();
		$wtg_make 			= $this->ManufacturerMaster->find("list",['keyField'=>'id','valueField'=>'name','conditions'=>['id'=>
			$geo_location_data->wtg_make]])->first();
		$district 		= $this->DistrictMaster->find("list",['keyField'=>'id','valueField'=>'name','conditions'=>['id'=>
			$geo_location_data->geo_district]])->first();
		$taluka 				= $this->TalukaMaster->find("list",['keyField'=>'id','valueField'=>'name','conditions'=>['id'=>$geo_location_data->geo_taluka]])->first();

		$zonearray = array(1 => '42 Q', 2 => '43 Q',3 => '42 R - North Gujarat', 4 => '43 R - North Gujarat');
		// Key to check
		$keyToCheck = $geo_location_data->old_zone;
		if (array_key_exists($keyToCheck, $zonearray)) {
		    // Display the value corresponding to the key
		     $old_zone = $zonearray[$keyToCheck]; 
		}

		$keyToCheck1 = $geo_location_data->modified_zone;
		if (array_key_exists($keyToCheck1, $zonearray)) {
		    // Display the value corresponding to the key
		     $modified_zone = $zonearray[$keyToCheck1]; 
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
		//echo"<pre>"; print_r($geo_location_data); die();
		$this->set("LocationList",$LocationList);
		$this->set("LocationList_internal",$LocationList_internal);
		$this->set("geo_application_data",$geo_application_data);
		
		$this->set("internal_clashed_docs",$internal_clashed_docs);
		//$this->set("remainingday",$remainingday);
		$this->set("clash_text",$clash_text);
		$this->set("district",$district);
		$this->set("taluka",$taluka);
		$this->set("old_zone",$old_zone);
		$this->set("modified_zone",$modified_zone);
		$this->set("wtg_make",$wtg_make);
		$this->set("applicationCategory",$applicationCategory);
		$this->set("geo_location_data",$geo_location_data);
		$this->set("applyOnlinesData",$applyOnlinesData);
		//$this->set("applicationDocs",$applicationDocs);
		//$this->set("geo_application_data_download",$geo_application_data_download);
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
		$fields_date  		= "geo_shifting_application.created_date";

		if (!empty($DateField) && in_array($DateField,array("geo_shifting_application.created_date"))) {
			$fields_date 	= $DateField;
		}

		$whereCharging 		= '';
		if($fields_date != 'geo_shifting_application.created_date' && !empty($from_date) && !empty($end_date))
	    {
	    	$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
			$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
	    	$whereCharging 	= ' and '.$fields_date.' between '.$StartTime.' and '.$EndTime;
	    }

    	$connection         = ConnectionManager::get('default');
    	$arrRequestSelected = $this->GeoShiftingApplication->DefaultExportFields;
    	//$sql_first 			= $this->Applications->GetReReportFields($arrRequestSelected,$array_request);
		$order_by = 'GeoShiftingApplication.id desc';
		$ApplicationsList 	= $this->GeoShiftingApplication->getGeoLocationShiftingData_Downloadxl($array_request,$order_by,'');
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
		$arrExportFields 	= $this->GeoShiftingApplication->ExportFields;

		$arrReportFields 	= $this->GeoShiftingApplication->arrReportFields;
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
		
		$objPHPExcel->getActiveSheet()->setTitle('Shifting Data');
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
    	//echo"<pre>"; print_r($this->GeoShiftingApplication->ExportFields); die();
    	foreach ($this->GeoShiftingApplication->ExportFields as $Field_Name) {

   //  		$type_of_land 				=array('G' => 'Goverment Land', 'P' => 'Private Land','GL' => 'Geda Land', 'F' => 'Forest Land');
   //  		// Key to check
			// $keyToCheckTL = $Report_Data['type_of_land'];
			// if (array_key_exists($keyToCheckTL, $type_of_land)) {
			//     // Display the value corresponding to the key
			//      $type_of_land = $type_of_land[$keyToCheckTL]; 
			// }

			$zone_drop_down 			=array('1' => '42 Q', '2' => '43 Q','3' => '42 R - North Gujarat', '4' => '43 R - North Gujarat');

    		$zonearray = array(1 => '42 Q', 2 => '43 Q',3 => '42 R - North Gujarat', 4 => '43 R - North Gujarat');
			// Key to check
			$keyToCheck = $Report_Data['old_zone'];
			if (array_key_exists($keyToCheck, $zonearray)) {
			    // Display the value corresponding to the key
			     $zone = $zonearray[$keyToCheck]; 
			}

			$keyToCheckM = $Report_Data['modified_zone'];
			if (array_key_exists($keyToCheckM, $zonearray)) {
			    // Display the value corresponding to the key
			     $zoneM = $zonearray[$keyToCheckM]; 
			}
			$approveddata = array(1 => 'Approved', 2 => 'Reject',3 => 'clashed', 4 => 'Internal clashed');
			// Key to check
			$keyToCheckAD = $Report_Data['approved'];
			if (array_key_exists($keyToCheckAD, $approveddata)) {
			    // Display the value corresponding to the key
			     $approvedD = $approveddata[$keyToCheckAD]; 
			}
     		$RowName = $this->GetExcelColumnName($i);
    		
   //  		$district 				= $this->DistrictMaster->find("list",['keyField'=>'id','valueField'=>'name','conditions'=>['state_id'=>4]])->toArray();
    		
			// $taluka 				= $this->TalukaMaster->find("list",['keyField'=>'id','valueField'=>'name','conditions'=>['state_code'=>24]])->toArray();
			
			// if(!empty($Report_Data['approved_by']) && isset($Report_Data['approved_by'])){
			// 	$members_data 	= $this->Members->find("all",['fields'=>['id','address1','name'],'conditions'=>['id'=>$Report_Data['approved_by']]])->first();
			// }
			
   //          	$key1 = $Report_Data['geo_district'];
			// 	 // Check if the key is present in the array
   //              if (array_key_exists($key1, $district)) {
   //                  // If the key exists, show its value
   //                  $district_name = $district[$key1];
                    
   //              }
   //              $keyT = $Report_Data['geo_taluka'];
   //              // Check if the key is present in the array
   //              if (array_key_exists($keyT, $taluka)) {
   //                  // If the key exists, show its value
			// 		$taluka_name = $taluka[$keyT];
   //             }
            

    		$RowData = "";
    		switch ($Field_Name) {
				case 'sr_no':
					$RowData = ($RowID-1);
					break;
				
				// //case 'installer_name':
				// 	$RowData = $Report_Data['developers'][$Field_Name];
				// 	break;
				case 'created_by':
					$RowData = $Report_Data['developer_customers']['name'];
					break;
				case 'registration_no':
					$RowData =  $Report_Data['applications'][$Field_Name];
					break;
				// case 'application_type':
				// 	$RowData = ($Report_Data[$Field_Name] == 3) ? 'Wind' : 'Hybrid';
				// 	break;
				case 'old_zone':
					$RowData = isset( $zone) ?  $zone : '';
					break;
				case 'modified_zone':
					$RowData = isset( $zone) ?  $zoneM : '';
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
				// case 'query_raised_date':
				// 	$RowData =  isset($Report_Data[$Field_Name])?date('m-d-Y H:i a',strtotime($Report_Data[$Field_Name])): '';
				// 	break;
				case 'approved_date':
					$RowData =  isset($Report_Data[$Field_Name])?date('m-d-Y H:i a',strtotime($Report_Data[$Field_Name])): '';
					break;
				// case 'clashed_for':
				// 	if($Report_Data['geo_application_clashed_data']['clashed_for'] == 1){
				// 		$clashed_for = 'Clashed';
				// 	}else if($Report_Data['geo_application_clashed_data']['clashed_for'] == 2){
				// 		$clashed_for = 'Internal Clashed';
				// 	}else{
				// 		$clashed_for = '';
				// 	}
				// 	$RowData =  $clashed_for;
				// 	break;
				// case 'clashed_date':
				// 	$RowData =  isset($Report_Data['geo_application_clashed_data']['created'])?date('m-d-Y H:i a',strtotime($Report_Data['geo_application_clashed_data']['created'])): '';
				// 	break;
				// case 'payment_date':
				// 	$RowData =  isset($Report_Data[$Field_Name])?date('m-d-Y H:i a',strtotime($Report_Data[$Field_Name])): '';
				// 	break;
				case 'wtg_validity_date':
					$RowData =  isset($Report_Data[$Field_Name])?date('m-d-Y H:i a',strtotime($Report_Data[$Field_Name])): '';
					break;
				case 'approved_by':
					$RowData =  isset($members_data['address1']) ? $members_data['address1'] : '';
					break;
				case 'wtg_verified_by':
					$RowData =  isset($members_data['name']) ? $members_data['name'] : '';
					break;
				// case 'query_raised_by':
				// 	$RowData =  isset($members_data['name']) ? $members_data['name'] : '';
				// 	break;
				case 'approved':
					$RowData = isset( $approvedD) ?  $approvedD : '';
					break;		
					
				
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
	/**
	 * geo_location_verifydata
	 * Behaviour : Public
	 * @defination : Method is use to edit the geo_location data.
	 */
	public function geo_location_verifydata()
	{	
		//echo"<pre>"; print_r($this->request->data); die();
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
					$geo_application_data   = $this->GeoShiftingApplication->find('all',array('conditions'=>array('geo_application_id IN'=>$geo_id)))->first();

			 		$ApplicationsVerifyEntity = $this->GeoApplicationVerification->newEntity();
							
							$ApplicationsVerifyEntity->geo_id			= $geo_id;
							$ApplicationsVerifyEntity->application_id	= $geo_application_data->application_id;
							$ApplicationsVerifyEntity->created          = $this->NOW();
							$ApplicationsVerifyEntity->created_by       = $member_id;
							$ApplicationsVerifyEntity->shifted			= 'Yes';
						
					$this->GeoApplicationVerification->save($ApplicationsVerifyEntity);
					$geo_id_array = explode( ',' ,$geo_id);
					
					foreach ($geo_id_array as $key => $value) {
						
						$arr_moduless['wtg_verified']			= 1;
						$arr_moduless['wtg_verified_by']		= $member_id;
						$arr_moduless['wtg_verified_date']		= $this->NOW();
							
						$this->GeoShiftingApplication->updateAll($arr_moduless,array('geo_application_id'=>$value));
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

}

