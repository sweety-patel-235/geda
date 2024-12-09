<?php
namespace App\Controller\Api;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\ORM\Table;

use Cake\Core\Configure;
use Cake\Network\Email\Email;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Controller\Component;
use Cake\Utility\Security;

use Cake\Filesystem\Folder;
use Cake\Filesystem\File;

use Cake\Event\Event;;
use Cake\Validation\Validator;

class GetcoApiController extends AppController
{
	public function initialize()
    {
    	
    	// Always enable the CSRF component.
		parent::initialize();
		$this->loadModel('ApiToken');
		$this->loadModel('InstallerPlans');
		$this->loadModel('Adminaction');
		$this->loadModel('DeveloperCustomers');
		$this->loadModel('ReThirdpartyApiLog');
		$this->loadModel('Applications');
		$this->loadModel('ApplicationStages');
		$this->loadModel('ReCouchdb');
		$this->loadModel('ApplicationsDocs');
    }	
	
	/**
	 *
	 * write api log
	 *
	 * Behaviour : Public
	 *
	 * @defination :  Method is use to log any action performed by mobile user
	 *
	 */
	public function validate_login(){
		
		//jayshree.tailor@gmail.in
		// 23d42f5f3f66498b2c8ff4c20b8c5ac826e47146
		$api_url 			= 'https://srtgeda.gujarat.gov.in/api/GetcoApi/validate_login';
		$email 				= isset($this->request->data['email'])?$this->request->data['email']:0;
		$password 			= isset($this->request->data['password'])?$this->request->data['password']:0;
		
		$developer_data = $this->DeveloperCustomers->find('all',array('fields'=>['id','developer_registration_no','email','password'],'conditions'=>array('email'=>$email,'password'=>$password)))->first();
		
		$arrRequest 						  = array($email,$password);

		if(!empty($developer_data)){
			$token 		= $this->GenerateAPIToken($developer_data->id);
			$data = array();
			$data['developer_id'] 				  = $developer_data->id;
			$data['developer_registration_no']    = $developer_data->developer_registration_no;
			$data['token'] 						  = $token;
			$arrResponse						  = array('Sucess','login Credential are validated');
			$rethirdpartyEntity                   = $this->ReThirdpartyApiLog->newEntity(); 
			$rethirdpartyEntity->application_id   = 0;
			$rethirdpartyEntity->project_id       = $developer_data->id; 
			$rethirdpartyEntity->request_data     = json_encode($arrRequest);
			$rethirdpartyEntity->response_data    = json_encode($arrResponse);
			$rethirdpartyEntity->api_url          = $api_url;
			$rethirdpartyEntity->created          = $this->NOW();
			$this->ReThirdpartyApiLog->save($rethirdpartyEntity);

			$this->ApiToken->SetAPIResponse('type','success');
			$this->ApiToken->SetAPIResponse('msg', 'login Credential are validated');
			$this->ApiToken->SetAPIResponse('response', $data);
			
			echo $this->ApiToken->GenerateAPIResponse();
		}else{
			$arrResponse						  = array('failure','login Credential are Not validated');
			$rethirdpartyEntity                   = $this->ReThirdpartyApiLog->newEntity(); 
			$rethirdpartyEntity->application_id   = 0;
			$rethirdpartyEntity->project_id       = 0; 
			$rethirdpartyEntity->request_data     = json_encode($arrRequest);
			$rethirdpartyEntity->response_data    = json_encode($arrResponse);
			$rethirdpartyEntity->api_url          = $api_url;
			$rethirdpartyEntity->created          = $this->NOW();
			$this->ReThirdpartyApiLog->save($rethirdpartyEntity);
			
			$this->ApiToken->SetAPIResponse('type','failure');
			$this->ApiToken->SetAPIResponse('msg', 'login Credential are Not validated');
			$this->ApiToken->SetAPIResponse('response', '');
			
			echo $this->ApiToken->GenerateAPIResponse();
		}
		exit;
	}
	public function send_application_data(){
		
		// $api_url 			= 'https://srtgeda.gujarat.gov.in/api/GetcoApi/send_application_data';
		$api_url 			= 'http://dev.geda.in/api/GetcoApi/send_application_data';
		$registration_no 	= isset($this->request->data['registration_no'])?$this->request->data['registration_no']:0;
		
		$application_data = $this->Applications->find('all',array(
			'fields'=>['id','application_id','developers.installer_name','branch_masters.title' ,'application_category.category_name','name_of_applicant','address','address1','taluka','pincode','city','state','district_master.name','district_code','type_of_applicant','applicant_others','mobile','email','pan','application_status','GST','agency_code','msme','name_director','type_director','type_director_others','director_whatsapp','director_mobile','director_email','name_authority','type_authority','type_authority_others','authority_whatsapp','authority_mobile','authority_email','wtg_no','capacity_wtg','total_capacity','total_wind_hybrid_capacity','module_hybrid_capacity','inverter_hybrid_capacity','make','pv_capacity_ac','pv_capacity_dc','grid_connectivity','injection_level','getco_substation','project_state','project_village','project_taluka','project_district','project_energy','project_estimated_cost','approx_generation','payment_status','application_no','registration_no','e_invoice_url','created'],
			'join'=>[
				['table'=>'developers','type'=>'left','conditions'=>'Applications.installer_id = developers.id'],
				['table'=>'branch_masters','type'=>'left','conditions'=>'Applications.discom = branch_masters.id'],
				['table'=>'application_category','type'=>'left','conditions'=>'Applications.application_type = application_category.id'],
				['table'=>'district_master','type'=>'left','conditions'=>'Applications.district = district_master.id'],
			],'conditions'=>array('registration_no'=>$registration_no)))->first();
 		//json_encode($application_data);
		$arrRequest 						  = array($registration_no);

		if(!empty($application_data)){
			
			$data = array();
			$data['registration_no']    			= $registration_no;
			$data['application_data'] 				= $application_data;
			
			$arrResponse						  	= array('Sucess','Application Data Sent');
			$rethirdpartyEntity                   	= $this->ReThirdpartyApiLog->newEntity(); 
			$rethirdpartyEntity->application_id   	= $application_data->id;
			$rethirdpartyEntity->project_id      	= 0; 
			$rethirdpartyEntity->request_data     	= json_encode($arrRequest);
			$rethirdpartyEntity->response_data    	= json_encode($arrResponse);
			$rethirdpartyEntity->api_url          	= $api_url;
			$rethirdpartyEntity->created          	= $this->NOW();
			$this->ReThirdpartyApiLog->save($rethirdpartyEntity);

			$this->ApiToken->SetAPIResponse('type','success');
			$this->ApiToken->SetAPIResponse('msg', 'Application Data Sent successfully');
			$this->ApiToken->SetAPIResponse('response', $data);
			
			echo $this->ApiToken->GenerateAPIResponse();
		}else{
			$arrResponse						  = array('failure','Application Data Not Found');
			$rethirdpartyEntity                   = $this->ReThirdpartyApiLog->newEntity(); 
			$rethirdpartyEntity->application_id   = 0;
			$rethirdpartyEntity->project_id       = 0; 
			$rethirdpartyEntity->request_data     = json_encode($arrRequest);
			$rethirdpartyEntity->response_data    = json_encode($arrResponse);
			$rethirdpartyEntity->api_url          = $api_url;
			$rethirdpartyEntity->created          = $this->NOW();
			$this->ReThirdpartyApiLog->save($rethirdpartyEntity);

			$this->ApiToken->SetAPIResponse('type','failure');
			$this->ApiToken->SetAPIResponse('msg', 'Application Data Not Found');
			$this->ApiToken->SetAPIResponse('response', '');
			
			echo $this->ApiToken->GenerateAPIResponse();
		}
		exit;
	}
	public function connectivity_stage1(){
		
		// $registration_no 		= isset($this->request->data['registration_no'])?$this->request->data['registration_no']:0;
		// $stage 					= isset($this->request->data['stage'])?$this->request->data['stage']:0;
		// $connectivity_doc 		= isset($this->request->data['connectivity_doc'])?$this->request->data['connectivity_doc']:0;
		// $bg_doc 				= isset($this->request->data['bg_doc'])?$this->request->data['bg_doc']:0;
		// $status 				= isset($this->request->data['status'])?$this->request->data['status']:0;
		// $date_of_validity 		= isset($this->request->data['date_of_validity'])?$this->request->data['date_of_validity']:0;
		// $date_of_connectivity 	= isset($this->request->data['date_of_connectivity'])?$this->request->data['date_of_connectivity']:0;

		$registration_no 		= 'GEDA/PR/GM/23-24/01/23/03';
		$stage 					= 'connectivity_stage1';
		$connectivityDocUrl 		= 'file:///C:/xampp/htdocs/geda/webroot/img/applications/23/STUstep1_20240214050232355337376.pdf';
		$bgDocUrl 				= 'file:///C:/xampp/htdocs/geda/webroot/img/applications/23/bg_upload_file_202401120201211888768265.pdf';
		$status 				= 'Sucess';
		$date_of_validity 		= '2024-04-12';
		$date_of_connectivity 	= '2024-02-13';
		
		$arrRequest 			= array($registration_no,$stage,$connectivityDocUrl,$bgDocUrl,$status,$date_of_validity,$date_of_connectivity);

		$application_data = $this->Applications->find("all",['conditions'=>array('registration_no'=>$registration_no)])->first();

		if(!empty($application_data)){

			$this->Applications->updateAll(array('application_status'=>$this->ApplicationStages->STU),array('registration_no'=>$registration_no));
			$this->ApplicationStages->saveStatus($application_data->id,$this->ApplicationStages->STU,'','');

			$uploadPath 	= APPLICATIONS_PATH.$application_data->id.'/uploaded_dos';
			if(!file_exists(APPLICATIONS_PATH.$application_data->id.'/uploaded_dos')) {
				@mkdir(APPLICATIONS_PATH.$application_data->id.'/uploaded_dos', 0777);
			}

			
			$targetDir 	= WWW_ROOT.$uploadPath;

	        $connectivityDocTarget = $targetDir . '/STUstep1_connectivity'.'_'.$application_data->id.'.pdf';
	        $bgDocTarget = $targetDir . '/STUstep1_bg_upload_file'.'_'.$application_data->id.'.pdf';

	        $this->downloadAndSaveFile($connectivityDocUrl, $connectivityDocTarget);
	        $this->downloadAndSaveFile($bgDocUrl, $bgDocTarget);

        	if(!empty($connectivityDocUrl))
			{	
				$ApplicationsDocsEntity = $this->ApplicationsDocs->newEntity();
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

					$couchdbId 		= $this->ReCouchdb->saveData($uploadPath,$file_location,$prefix_file, 'STUstep1_'.$file_name.'.'.$ext,'','STUstep1');
					$ApplicationsDocsEntity->couchdb_id			= $couchdbId;
					$ApplicationsDocsEntity->application_id		= $id;
					$ApplicationsDocsEntity->file_name        	= 'STUstep1'.'_'.$file_name.'.'.$ext;
					$ApplicationsDocsEntity->doc_type         	= 'STUstep1';
					$ApplicationsDocsEntity->title            	= 'connectivity_upload_approval ';
					$ApplicationsDocsEntity->created          	= $this->NOW();
					$ApplicationsDocsEntity->created_by         = 1;
					$application_status 						= $this->ApplicationStages->STU;
					$this->ApplicationsDocs->deleteAll(['application_id' => $id,'doc_type'=>'STUstep1']);
					
					
				}
				$this->ApplicationsDocs->save($ApplicationsDocsEntity);
			}
				$arrResponse						  = array('Sucess','Connectivity Stage1 Approved');
				$rethirdpartyEntity                   = $this->ReThirdpartyApiLog->newEntity(); 
				$rethirdpartyEntity->application_id   = 0;
				$rethirdpartyEntity->project_id       = $application_data->id; 
				$rethirdpartyEntity->request_data     = json_encode($arrRequest);
				$rethirdpartyEntity->response_data    = json_encode($arrResponse);
				$rethirdpartyEntity->api_url          = $api_url;
				$rethirdpartyEntity->created          = $this->NOW();
				$this->ReThirdpartyApiLog->save($rethirdpartyEntity);

				$this->ApiToken->SetAPIResponse('msg', 'Connectivity Stage1 Approved');
				$this->ApiToken->SetAPIResponse('response', $data);
				$this->ApiToken->SetAPIResponse('type','success');
				echo $this->ApiToken->GenerateAPIResponse();
		}else{

			$arrResponse						  = array('failure','Connectivity Stage1 Not Approved');
			$rethirdpartyEntity                   = $this->ReThirdpartyApiLog->newEntity(); 
			$rethirdpartyEntity->application_id   = 0;
			$rethirdpartyEntity->project_id       = 0; 
			$rethirdpartyEntity->request_data     = json_encode($arrRequest);
			$rethirdpartyEntity->response_data    = json_encode($arrResponse);
			$rethirdpartyEntity->api_url          = $api_url;
			$rethirdpartyEntity->created          = $this->NOW();
			$this->ReThirdpartyApiLog->save($rethirdpartyEntity);

			$this->ApiToken->SetAPIResponse('msg', 'Connectivity Stage1 Not Approved');
			$this->ApiToken->SetAPIResponse('response', '');
			$this->ApiToken->SetAPIResponse('type','failure');
			echo $this->ApiToken->GenerateAPIResponse();
		}
		exit;
		
	}


	 public function GenerateAPIToken($customer_id) 
	 {
		$date						= date('Y-m-d H:i:s');
		$rand						= rand(10000,99999);
		$rand						= strtotime($date).$rand;
		$this->token				= md5($rand.HMAC_HASH_PRIVATE_KEY);

		$tokenEntity 				= $this->ApiToken->newEntity();

		$tokenEntity->token 		= $this->token;
		$tokenEntity->customer_id 	= $customer_id;
		$tokenEntity->last_access 	= $date;
		$tokenEntity->created 		= $date;

		if($this->ApiToken->save($tokenEntity)) {
			//$this->arrToken	= $this->get($tokenEntity->id);
			return $this->token;
		}
		return "";
	 }
	 private function downloadAndSaveFile($url, $target)
    {
        // Ensure the URL is valid and can be accessed
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        // Get the file contents
        $fileContent = file_get_contents($url);

        if ($fileContent === false) {
            return false;
        }

        // Write the file content to the target location
        $file = new File($target, true);
        //echo"<pre>"; print_r($file); die();
        if ($file->exists()) {
            if ($file->write($fileContent)) {
                $file->close();
                return true;
            }
        }
     }
}
