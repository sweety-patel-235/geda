<?php
namespace App\Model\Table;

use App\Model\Table\Entity;
use App\Model\Entity\User;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\View\View;
use Dompdf\Dompdf;
use Cake\Utility\Security;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Datasource\ConnectionManager;


/**
 * Short description for file
 * This Model use for installer . It extends Table Class
 * @category  Class File
 * @Desc      Manage installer information
 * @author    Khushal Bhalsod
 * @version   Solar 1.0
 * @since     File available since Solar 1.0
 */

class DevelopersTable extends AppTable
{
	var $table 		= 'developers';
	var $data 		= array();
	var $dataRecord = array();

	public function initialize(array $config)
    {
        $this->table($this->table);        
    }
    /**
     *
     *  SendSMSActivationCode
     *
     * Behaviour : Public
     *
     * @defination :  Method for send otp to installer verification.
     *
     */
    public function SendSMSActivationCode($id,$mobile,$activation_code)
    {
		$sms_message 		= str_replace('##OTP##',$activation_code, INSTALLER_OTP);
		$sms_message 		= str_replace('##SITE_URL##',URL_HTTP, $sms_message);
       /* $MESSAGE			= urlencode($sms_message);

        $FIND_ARRAY			= array("[SMS_USER]","[SMS_PASS]","[MESSAGE]","[MOBILE]");
        $REPL_ARRAY			= array(SMS_USER,SMS_PASS,$MESSAGE,$mobile);
        $SMS_GATEWAY_URL 	= str_replace($FIND_ARRAY,$REPL_ARRAY,SMS_GATWAY_URL);*/
        $this->sendSMS($id,$mobile,$sms_message,'INSTALLER_OTP');
       // $SMS_CONTENT		= $this->ApiCall($SMS_GATEWAY_URL);

    }
    private function ApiCall($SMS_GATEWAY_URL)
    {
        $ch 				= curl_init($SMS_GATEWAY_URL);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION ,1);
        curl_setopt($ch, CURLOPT_HEADER,0);  			// DO NOT RETURN HTTP HEADERS
        curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);  	// RETURN THE CONTENTS
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT  ,0);
        $SMS_CONTENT 		= curl_exec($ch);
        return $SMS_CONTENT;
    }
    /**
	 *
	 *  GetLocationByLatLong
	 *
	 * Behaviour : Public
	 *
	 * @return : its return location(str)
	 * @defination : For getting loaction using lattitude and longitude.
	 *
	 */
	public function GetLocationByLatLong($lat, $lng)
	{
	 	$url 	= 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($lng).'&sensor=false';
		$json 	= @file_get_contents($url);
		$data	= json_decode($json);
		$status = $data->status;
		$arrResult = array();
		if($status == "OK") {
			
			$result = $data->results[0]->address_components;
			if(!empty($result)) {
				for($i=0;$i<count($result); $i++) {
				
					if($result[$i]->types[0] == 'locality') {
						$arrResult['city'] 	= (isset($result[$i]->long_name))?$result[$i]->long_name:'';
					}

					if($result[$i]->types[0] == 'administrative_area_level_1') {
						$arrResult['state'] = (isset($result[$i]->long_name))?$result[$i]->long_name:'';
					}
				}
			}
		} 
		return $arrResult;
	}
	public function validationRegister(Validator $validator)
	{
		$validator->notEmpty('installer_name');
		$validator->notEmpty('contact_person');
		$validator->notEmpty('mobile');
		$validator->add('email', 'valid-email', ['rule' => 'email']);
		//$validator->add('pincode', 'valid', ['rule' => 'numbers','message' => 'Invalid Pincode']);
		return $validator;
	}
	public function validationFronInstallerRegister(Validator $validator)
	{
		$validator->notEmpty('installer_name','Name of the Developer can not be blank.');
		$validator->notEmpty('contact_person','Name of the Head of the Developer can not be blank.');
		$validator->notEmpty('designation','Designation can not be blank.');
		$validator->notEmpty('mobile', 'Please Enter valid Mobile no.')
		->add('mobile', 'custom',[ 
			'rule'=> [$this, 'ValidateMobileNumber'],
			'message'=>'Please Enter valid Mobile no.']);
		$validator->notEmpty('email', 'Please Enter Email.')
		->add('email', 'valid-email', [
			'rule' => 'email',
			'message'=>'Please Enter valid Email.'
			]);
		$validator->notEmpty('state', 'Please Select State.');
		$validator->notEmpty('district', 'Please Select District.');
		$validator->notEmpty('pincode', 'Pincode can not be blank.');
		$validator->notEmpty('pan', 'PAN Number can not be blank.');
		$validator->notEmpty('city', 'City can not be blank.');
		$validator->notEmpty('address1', 'Street/House no can not be blank.');
		$validator->notEmpty('taluka', 'Taluka/Village can not be blank.');
		
		//$validator->notEmpty('GST', 'GST Number can not be blank.');
		// if(!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->gst_certificate))) {
		// 	$validator->notEmpty('f_gst_certificate', 'Upload GST Certificate file required.');
		// }
	
		
		if(isset($this->data['Developers']['pan']) && strlen($this->data['Developers']['pan']) != 10)
		{
			$validator->add("pan", [
				 "_empty" => [
				 "rule" => [$this, "customFunction_consumer"],
				 "message" => "Pan Card Number must be a 10 digits."
					]
			]);
		}
		else
		{
			$validator->add("pan", [
				"_empty" => [
					"rule" => [$this, "custom_unique"],
					"pass" => array('pan'),
					"message" => "PAN Card Number already exist."
						]
					]
			);
		}
		if(isset($this->data['Developers']['mobile']) && strlen($this->data['Developers']['mobile']) != 10)
		{
			$validator->add("mobile", [
				 "_empty" => [
				 "rule" => [$this, "customFunction_consumer"],
				 "message" => "Mobile Number must be a 10 digits."
					]
			]);
		}
		else
		{
			$validator->add("mobile", [
			"_empty" => [
				"rule" => [$this, "custom_unique"],
				"pass" => array('mobile'),
				"message" => "Mobile number already exist."
					]
				]
		);
		}
		$validator->add("email", [
			"_empty" => [
				"rule" => [$this, "custom_unique_customer"],
				"pass" => array('email'),
				"message" => "Email already mapped with customer."
					]
				]
		);
		
		
		if(isset($this->data['Developers']['email']) && !empty($this->data['Developers']['email'])) {
			
			$validator->add("email", [
				"_empty" => [
					"rule" => [$this, "validateEmail1"],
					"pass" => array('email'),
					"message" => "Wrong Email."
						]
					]
			);
		}
		$validator->add("email", [
			"_empty" => [
				"rule" => [$this, "custom_unique"],
				"pass" => array('email'),
				"message" => "Email already exist."
					]
				]
		);
		if(isset($this->data['Developers']['reply']) && empty($this->data['Developers']['reply'])) {
			
			$validator->notEmpty('reply', 'Please enter comments.');
		}
		
		/*$validator->add("installer_name", [
			"_empty" => [
				"rule" => [$this, "custom_unique"],
				"pass" => array('installer_name'),
				"message" => "Solar Installer Company already exist."
					]
				]
		);*/
		
		if(isset($this->data['Developers']['pincode']) && strlen($this->data['Developers']['pincode']) != 6)
		{
			$validator->add("pincode", [
				 "_empty" => [
				 "rule" => [$this, "customFunction_consumer"],
				 "message" => "Pincode must be 6 digits."
					]
			]);
		}
		if(!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->upload_undertaking))) {
			$validator->notEmpty('f_upload_undertaking', 'Upload Undertaking form file required.');
		}
		
		if(!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->registration_document))) {
			$validator->notEmpty('f_registration_document', 'Enclose self-certified copy of Entity Registration/ MOA/ROC/ROF/AOA/COI/Partnership Deed/LLP file required.');
		}
		if(!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->pan_card))) {
			$validator->notEmpty('f_pan_card', 'Upload PAN Card file required.');
		}

		if(!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->d_file_board))) {
			$validator->notEmpty('dfile_board', 'Enclose self-certified copy of Entity Registration/ MOA/ROC/ROF/AOA/COI/Partnership Deed/LLP file required.');
		}
		if(isset($this->data['Developers']['type_of_applicant']) && $this->data['Developers']['type_of_applicant'] == ''){
			$validator->notEmpty('type_of_applicant', 'Please select at-least one.');
		}
		if(isset($this->data['Developers']['type_of_applicant']) && $this->data['Developers']['type_of_applicant'] == 'Other' && $this->data['Developers']['applicant_others'] == ''){
				$validator->notEmpty('applicant_others', 'Applicant Type Other can not be blank.');
		}
		$validator->notEmpty('selected_category', 'Please select atleast one.');

		if((isset($this->data['Developers']['msme']) && ($this->data['Developers']['msme']) == ''))
		{

		$validator->add("msme", [
				"_empty" => [
					"rule" => [$this, "customFunction_consumer"],
					"message" => "Please select Applicant a MSME?"
						]
					]
			);
		}
		$validator->notEmpty('name_director', 'Name of the Managing Director / Chief Executive of the Company can not be blank.');
		$validator->notEmpty('director_mobile', 'Mobile can not be blank.')
		->add('director_mobile', 'custom',[ 
			'rule'=> [$this, 'ValidateMobileNumber'],
			'message'=>'Please Enter valid Mobile no.']);

		$validator->notEmpty('director_email', 'Please Enter Email.')
		->add('director_email', 'valid-email', [
			'rule' => 'email',
			'message'=>'Please Enter valid Email.'
			]);
		$validator->notEmpty('type_director', 'Please select atleast one.');


		$validator->notEmpty('name_authority', 'Name of the authorized Signatory can not be blank.');
		$validator->notEmpty('authority_mobile', 'Mobile can not be blank.')
		->add('authority_mobile', 'custom',[ 
			'rule'=> [$this, 'ValidateMobileNumber'],
			'message'=>'Please Enter valid Mobile no.']);
		
		$validator->notEmpty('authority_email', 'Please Enter Email.')
		->add('authority_email', 'valid-email', [
			'rule' => 'email',
			'message'=>'Please Enter valid Email.'
			]);
		if(isset($this->data['Developers']['authority_whatsapp']) && !empty($this->data['Developers']['authority_whatsapp']))
		{
			$validator->add('authority_whatsapp', 'custom',[ 
				'rule'=> [$this, 'ValidateMobileNumber'],
				'message'=>'Please Enter valid Mobile no.']);
		}
		if(isset($this->data['Developers']['contact']) && !empty($this->data['Developers']['contact']))
		{
			$validator->add('contact', 'custom',[ 
				'rule'=> [$this, 'ValidateMobileNumber'],
				'message'=>'Please Enter valid Mobile no.']);
		}
		if(isset($this->data['Developers']['director_whatsapp']) && !empty($this->data['Developers']['director_whatsapp']))
		{
			$validator->add('director_whatsapp', 'custom',[ 
				'rule'=> [$this, 'ValidateMobileNumber'],
				'message'=>'Please Enter valid Mobile no.']);
		}

		
		$validator->notEmpty('type_authority', 'Please select atleast one.');
		
		if(isset($this->data['Developers']['GST']) && !empty($this->data['Developers']['GST'])) {
				$validator->add('GST', [
				'validFormat'=>[
					'rule' => array('custom', '/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/'),
					'message' => 'Valid GST Number only'
				]
			]);
		} elseif(isset($this->data['Developers']['GST']) && !empty($this->data['Developers']['GST']) && strlen($this->data['Developers']['GST']) != 15) {
			$validator->add("GST", [
				 "_empty" => [
				 "rule" => [$this, "customFunction_consumer"],
				 "message" => "GST must be 15 characters."
					]
			]);
		}
		if(isset($this->data['Developers']['msme']) && $this->data['Developers']['msme'] == 1 && (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->d_msme)))) {
			$validator->notEmpty('d_msme', 'Upload MSME file required.');
		}
		if(isset($this->data['Developers']['type_authority']) && $this->data['Developers']['type_authority'] == 'Others') {
			$validator->notEmpty('type_authority_others', 'Designation can not be blank.');
		}
		if(isset($this->data['Developers']['type_director']) && $this->data['Developers']['type_director'] == 'Others') {
			$validator->notEmpty('type_director_others', 'Designation can not be blank.');
		}
		
		return $validator;
	}
	/**
	*
	* customFunction_consumer
	*
	* Behaviour : public
	*
	* Parameter : discom
	*
	* @defination : Method is used to check consumer number length validation.
	*
	*/
	public function customFunction_consumer($value, $context)
	{
		return false;
	}
	/**
	*
	* custom_unique
	*
	* Behaviour : public
	*
	* Parameter : discom
	*
	* @defination : Method is used to check unique validation.
	*
	*/
	public function custom_unique($value, $context,$action=array())
	{

		$arr_condition['Developers.'.$action['field']] = $value;

		if(isset($this->data['Developers']['company_id']) && !empty($this->data['Developers']['company_id']))
		{
			$arr_condition['Developers.company_id != '] = $this->data['Developers']['company_id'];
		}
		$arr_result= $this->find('all',array('conditions' => $arr_condition))->first();
		
		if(!empty($arr_result))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	/**
	*
	* custom_unique_customer
	*
	* Behaviour : public
	*
	* Parameter : discom
	*
	* @defination : Method is used to check unique validation.
	*
	*/
	public function custom_unique_customer($value, $context,$action=array())
	{
		$DeveloperCustomers = TableRegistry::get('DeveloperCustomers');
		$arr_condition['DeveloperCustomers.'.$action['field']] 	= $value;
		//$arr_condition['installers.geda_approval !='] 	= 2;
		$arr_result= $DeveloperCustomers->find('all',array('conditions' => $arr_condition,'join'=>[['table'=>'installers','type'=>'left','conditions'=>'DeveloperCustomers.installer_id = installers.id']]))->first();
		if(!empty($arr_result))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	/**
	*
	* generateDeveloperActivationCodes
	*
	* Behaviour : public
	*
	* @defination : Method is used to generate customer activation codes.
	*
	* Author : Khushal Bhalsod
	*/
	public function generateDeveloperActivationCodes()
	{
		$length		= 4;
		$alphabets 	= range('A','Z');
	    $numbers 	= range('0','9');
	    $final_array = array_merge($alphabets,$numbers);
	         
	    $activation_codes = '';
	  	while($length--) {
	    	$key = array_rand($final_array);
	    	$activation_codes .= $final_array[$key];
	    }
	    return $activation_codes;
	}

	public function getProjectInstallers($projectId)
	{
		$connection 		= ConnectionManager::get('default');
		$projectDetails 	= TableRegistry::get('Projects')->find()->where(['id' => $projectId])->first(); 
		if(empty($projectDetails)){
			return array();
		}
		/* Get Installer List */
		$mnre_image = MNRE_IMG_URL;
		$latitude 	= $projectDetails['latitude'];
		$longitude 	= $projectDetails['longitude'];
		$state 		= $projectDetails['state'];
		$installerData = $connection->execute("
   				SELECT *,
   				(((acos(sin((".$latitude."*pi()/180)) * sin((`latitude`*pi()/180))+cos((".$latitude."*pi()/180)) * cos((`latitude`*pi()/180)) * cos(((".$longitude."- `longitude`)*pi()/180))))*180/pi())*60*1.1515) as distance,
				(CASE WHEN state = '".$state."' THEN 10 ELSE 0 END) state_point,
				(CASE WHEN rating_category = 'A' THEN 10
				      WHEN rating_category = 'B' THEN 7.5
				      WHEN rating_category = 'C' THEN 5 
				      WHEN rating_category = 'D' THEN 2.5  
				      ELSE 0
					  END) cat_point,
				(
				(CASE WHEN state = '".$state."' THEN 10 ELSE 0 END)
					+
				(CASE WHEN rating_category = 'A' THEN 10
				      WHEN rating_category = 'B' THEN 7.5
				      WHEN rating_category = 'C' THEN 5 
				      WHEN rating_category = 'D' THEN 2.5  
				      ELSE 0
					  END)) as total_points
				FROM `installers` 
				INNER JOIN `installer_projects` ON (installer_projects.installer_id = installers.id)
				WHERE project_id = $projectId
				GROUP BY installers.id
				HAVING distance is not null ORDER BY total_points DESC, distance ASC")
              ->fetchAll('assoc');
        $installer = array();
        if(!empty($installerData)) {
			foreach($installerData as $key=>$value) { 
				$installer[$key]['id']				= (isset($installerData[$key]['id'])?$installerData[$key]['id']:'');
				$installer[$key]['installer_name'] 	= (isset($installerData[$key]['installer_name'])?$installerData[$key]['installer_name']:'');
				$installer[$key]['address'] 		= str_replace($installerData[$key]['city'], "", $installerData[$key]['address']);
				$installer[$key]['address'] 		= str_replace($installerData[$key]['state'], "", $installerData[$key]['address']);
				$installer[$key]['city'] 			= (isset($installerData[$key]['city'])?$installerData[$key]['city']:'');
				$installer[$key]['state'] 			= (isset($installerData[$key]['state'])?$installerData[$key]['state']:'');
				$installer[$key]['contact'] 		= (isset($installerData[$key]['mobile'])?$installerData[$key]['mobile']:'');
				$installer[$key]['email'] 			= (isset($installerData[$key]['email'])?$installerData[$key]['email']:'');
				$installer[$key]['fax_no'] 			= (isset($installerData[$key]['fax_no'])?$installerData[$key]['fax_no']:'');
				$installer[$key]['mobile'] 			= (isset($installerData[$key]['mobile'])?$installerData[$key]['mobile']:'');
				$installer[$key]['mnre_image'] 		= (!empty($installerData[$key]['application_code'])?$mnre_image:'');
				$installer[$key]['geda_image'] 		= (!empty($installerData[$key]['geda_rate'])?GEDA_IMG_URL:'');
				$installer[$key]['empanelled_logo'] =array();
				if(!empty($installerData[$key]['application_code'])){
					$installer[$key]['empanelled_logo'][] = array('image_url'=>$mnre_image);
				}
				if(!empty($installerData[$key]['geda_rate'])){
					$installer[$key]['empanelled_logo'][] = array('image_url'=>GEDA_IMG_URL);
				}
				if(isset($value['total_points'])){
					$installer[$key]['rating'] 		= round(($value['total_points']/20) * 5);
				}else{
					$installer[$key]['rating'] 		= 5;
				}
			}	
		}
		return $installer;
	}

	public function getProjectFindInstallerList($projectId)
	{
		$connection = ConnectionManager::get('default');
		$projectDetails = TableRegistry::get('Projects')->find()->where(['id' => $projectId])->first(); 
		//prd($projectDetails);
		if(empty($projectDetails)){
			return array();
		}
		$projectInstallers = TableRegistry::get('InstallerProjects')->find('all',['fields' =>['installer_ids' => 'group_concat(installer_id)']])->where(['project_id' => $projectId])->first(); 
		$installerIds = $projectInstallers['installer_ids'];
		if($installerIds == ""){$installerIds = 0;}
		
		/* Get Installer List */
		$mnre_image = MNRE_IMG_URL;
		$latitude 	= $projectDetails['latitude'];
		$longitude 	= $projectDetails['longitude'];
		$state 		= $projectDetails['state'];
		$Wherecond 	= "";
		//if (strtolower($state) == "jharkhand") {
			$Wherecond = " WHERE LOWER(state) = '".strtolower($state)."'";
		//}
		$InstallerQuery = "	SELECT *,
							(((acos(sin((".$latitude."*pi()/180)) * sin((`latitude`*pi()/180))+cos((".$latitude."*pi()/180)) * cos((`latitude`*pi()/180)) * cos(((".$longitude."- `longitude`)*pi()/180))))*180/pi())*60*1.1515) as distance,
							(CASE WHEN state = '".$state."' THEN 10 ELSE 0 END) state_point,
							(CASE WHEN rating_category = 'A' THEN 10
								WHEN rating_category = 'B' THEN 7.5
								WHEN rating_category = 'C' THEN 5 
								WHEN rating_category = 'D' THEN 2.5  
								ELSE 0
								END) cat_point,
							(
							(CASE WHEN state = '".$state."' THEN 10 ELSE 0 END)
								+
							(CASE WHEN rating_category = 'A' THEN 10
								WHEN rating_category = 'B' THEN 7.5
								WHEN rating_category = 'C' THEN 5 
								WHEN rating_category = 'D' THEN 2.5  
								ELSE 0
								END)) as total_points
							FROM `installers`
							".$Wherecond."
							GROUP BY installers.id
							HAVING distance is not null and installers.id NOT IN ($installerIds)
							ORDER BY total_points DESC, distance ASC";
		$installerData 	= $connection->execute($InstallerQuery)->fetchAll('assoc');
		$installer 		= array();
        if(!empty($installerData)) {
			foreach($installerData as $key=>$value) { 
				$installer[$key]['id']				= (isset($installerData[$key]['id'])?$installerData[$key]['id']:'');
				$installer[$key]['installer_name'] 	= (isset($installerData[$key]['installer_name'])?$installerData[$key]['installer_name']:'');
				$installer[$key]['address'] 		= str_replace($installerData[$key]['city'], "", $installerData[$key]['address']);
				$installer[$key]['address'] 		= str_replace($installerData[$key]['state'], "", $installerData[$key]['address']);
				$installer[$key]['city'] 			= (isset($installerData[$key]['city'])?$installerData[$key]['city']:'');
				$installer[$key]['state'] 			= (isset($installerData[$key]['state'])?$installerData[$key]['state']:'');
				$installer[$key]['contact'] 		= (isset($installerData[$key]['mobile'])?$installerData[$key]['mobile']:'');
				$installer[$key]['email'] 			= (isset($installerData[$key]['email'])?$installerData[$key]['email']:'');
				$installer[$key]['fax_no'] 			= (isset($installerData[$key]['fax_no'])?$installerData[$key]['fax_no']:'');
				$installer[$key]['mobile'] 			= (isset($installerData[$key]['mobile'])?$installerData[$key]['mobile']:'');
				$installer[$key]['mnre_image'] 		= (!empty($installerData[$key]['application_code'])?$mnre_image:'');
				$installer[$key]['geda_image'] 		= (!empty($installerData[$key]['geda_rate'])?GEDA_IMG_URL:'');
				$installer[$key]['empanelled_logo'] = array();
				if(!empty($installerData[$key]['application_code'])){
					$installer[$key]['empanelled_logo'][] = array('image_url'=>$mnre_image);
				}
				if(!empty($installerData[$key]['geda_rate'])){
					$installer[$key]['empanelled_logo'][] = array('image_url'=>GEDA_IMG_URL);
				}
				if(isset($value['total_points']) && $value['total_points'] > 0) {
					$installer[$key]['rating'] 	= round(($value['total_points']/20) * 5);
				}else{
					$installer[$key]['rating'] 	= 5;
				}
				
			}	
		}
		return $installer;
	}

	public function installerlist($char = '') {
        return $this->find('list', ['keyField' => 'id','valueField' => 'installer_name','conditions'=>array('installer_name like'=>$char.'%')])->order('installer_name')->limit(10)->toArray();
    }

    public function installerByStateCount($state = '') {
		return $this->find('all',['join'=>[['table'=>'states','type'=>'left','conditions'=>'states.id = Developers.state or states.statename = Developers.state']],
			'conditions'=>['Developers.stateflg' => $state]
			])->count();

	}
	public function GetInstallerList($state = '',$installer_name = '',$category_name = '') {
		$fields             = [ 'Developers.installer_name',
								'Developers.address',
								'Developers.state',
								'Developers.city',
								'Developers.email',
								'Developers.mobile',
								'installer_category_mapping.category_id',
								'installer_category_mapping.short_name',
								'installer_category_mapping.allowed_bands',
								'installer_category.category_name',
								'Developers.installer_old_name',
								'Developers.name_change_date'
                            ];
		$join_arr=[['table'=>'states','type'=>'left','conditions'=>'states.id = Developers.state or 			states.statename = Developers.state'],
					['table'=>' installer_category_mapping','type'=>'left','conditions'=>' installer_category_mapping.installer_id = Developers.id'],
					['table'=>' installer_category','type'=>'left','conditions'=>' installer_category.id = installer_category_mapping.category_id']
					];

		$name_arr =['Developers.stateflg' => $state,'Developers.status' => 1];
		if(!empty($installer_name)){
			if(is_array($installer_name))
			{ 
				array_push($name_arr,array('Developers.id in' => $installer_name));
			
			}
			else
			{
				array_push($name_arr,array('Developers.installer_name like' => '%' .$installer_name. '%'));
			}
			
		}

		if(!empty($category_name)){
			array_push($name_arr,array('installer_category_mapping.category_id ' => $category_name));
		}
		return $this->find('all',[ 'fields'=>$fields,
			'join'=>$join_arr,
			'conditions'=>$name_arr,
			'order' => ['Developers.installer_name' => 'ASC']
			]);
	}

	public function GetInstallersByState($StateName="",$latitude=0,$longitude=0,$page=1,$limit=10)
	{
		$installer 	= array();
		$arrResult 	= array();
		$mnre_image = MNRE_IMG_URL;
		$connection = ConnectionManager::get('default');
		$Wherecond 	= "";
		$orderBy 	= 'ORDER BY total_points DESC, distance ASC ';
		if(!empty($StateName) && strtolower($StateName) =='jharkhand') {
			$Wherecond 	= "AND installers.stateflg = 22";
		} else if(isset($StateName) &&  !empty($StateName)) {
			if(!empty($StateName) && strtolower($StateName) =='gujarat') {
				$orderBy = 'ORDER BY geda_rate DESC, distance ASC ';
			}
		}
		$Offset 		= ($page > 0)?($page - 1)*$limit:0;
		$LIMIT 			= "LIMIT ".$Offset.", ".($limit);
		$CountSql 		= " SELECT id,
							(((acos(sin((".$latitude."*pi()/180)) * sin((`latitude`*pi()/180))+cos((".$latitude."*pi()/180)) * cos((`latitude`*pi()/180)) * cos(((".$longitude."- `longitude`)*pi()/180))))*180/pi())*60*1.1515) as distance
							FROM `installers`
							WHERE installers.status = 1 ".$Wherecond."
							HAVING distance is not null ";
		$installerCount = $connection->execute($CountSql)->fetchAll('assoc');
		$CountRows 		= !empty($installerCount)?count($installerCount):0;
		if ($CountRows > 0)
		{
			$InstallerQuery = "	SELECT *,
								(((acos(sin((".$latitude."*pi()/180)) * sin((`latitude`*pi()/180))+cos((".$latitude."*pi()/180)) * cos((`latitude`*pi()/180)) * cos(((".$longitude."- `longitude`)*pi()/180))))*180/pi())*60*1.1515) as distance,
								(CASE WHEN state = '".$StateName."' THEN 10 ELSE 0 END) state_point,
								(CASE WHEN rating_category = 'A' THEN 10
									WHEN rating_category = 'B' THEN 7.5
									WHEN rating_category = 'C' THEN 5 
									WHEN rating_category = 'D' THEN 2.5  
									ELSE 0
									END) cat_point,
								(
									(CASE WHEN state = '".$StateName."' THEN 10 ELSE 0 END)
									+
									(
									CASE 	WHEN rating_category = 'A' THEN 10 
											WHEN rating_category = 'B' THEN 7.5 
											WHEN rating_category = 'C' THEN 5 
											WHEN rating_category = 'D' THEN 2.5
											ELSE 0
									END
									)
								) as total_points
								FROM `installers`
								WHERE installers.status = 1 ".$Wherecond."
								HAVING distance is not null ".$orderBy." ".$LIMIT;
			$installerData = $connection->execute($InstallerQuery)->fetchAll('assoc');
			if(!empty($installerData)) {
				foreach($installerData as $key=>$value) { 
					$installer[$key]['id']				= (isset($installerData[$key]['id'])?$installerData[$key]['id']:'');
					$installer[$key]['installer_name'] 	= (isset($installerData[$key]['installer_name'])?$installerData[$key]['installer_name']:'');
					$installer[$key]['address'] 		= str_replace($installerData[$key]['city'], "", $installerData[$key]['address']);
					$installer[$key]['address'] 		= str_replace($installerData[$key]['state'], "", $installerData[$key]['address']);
					$installer[$key]['city'] 			= (isset($installerData[$key]['city'])?$installerData[$key]['city']:'');
					$installer[$key]['state'] 			= (isset($installerData[$key]['state'])?$installerData[$key]['state']:'');
					$installer[$key]['contact'] 		= (isset($installerData[$key]['mobile'])?$installerData[$key]['mobile']:'');
					$installer[$key]['email'] 			= (isset($installerData[$key]['email'])?$installerData[$key]['email']:'');
					$installer[$key]['fax_no'] 			= (isset($installerData[$key]['fax_no'])?$installerData[$key]['fax_no']:'');
					$installer[$key]['mobile'] 			= (isset($installerData[$key]['mobile'])?$installerData[$key]['mobile']:'');
					$installer[$key]['mnre_image'] 		= (!empty($installerData[$key]['application_code'])?$mnre_image:'');
					$installer[$key]['geda_image'] 		= (!empty($installerData[$key]['geda_rate'])?GEDA_IMG_URL:'');
					$installer[$key]['empanelled_logo'] = array();
					if(!empty($installerData[$key]['application_code'])){
						$installer[$key]['empanelled_logo'][] = array('image_url'=>$mnre_image);
					}
					if(!empty($installerData[$key]['geda_rate'])){
						$installer[$key]['empanelled_logo'][] = array('image_url'=>GEDA_IMG_URL);
					}
					if(!empty($installerData[$key]['empaneled_city'])){
						$installer[$key]['empanelled_logo'][] = array('image_url'=>CREST_IMG_URL);
					}
					if(isset($value['total_points']) && $value['total_points'] > 0) {
						$installer[$key]['rating'] 	= round(($value['total_points']/20) * 5);
					}else{
						$installer[$key]['rating'] 	= 5;
					}
				}	
			}
		}
		$page_count = ($CountRows > 0)?ceil($CountRows / $limit):0;
		return $arrResult = array("data"=>$installer,"page_count"=>$page_count);
	}
	/**
	 *
	 * getInstallerListReport
	 *
	 * Behaviour : Public
	 *
	 *@param : 
	 *
	 * @defination : Method is use to fetch all installer from database.
	 *
	 */
	public function getInstallerListReport()
    {
    	return $this->find("list",[
														'keyField'=>'id',
														'valueField'=>'installer_name',
														'conditions'=>['status'=>'1','id not in'=>array('1275','1333')],
														'order'  => ['installer_name'=>'asc']
													]);
    }
    /**
	*
	* validateEmail1
	*
	* Behaviour : public
	*
	* Parameter : discom
	*
	* @defination : Method is used to check validateEmail1.
	*
	*/
	public function validateEmail1($value, $context,$action=array())
	{

		if(filter_var($value, FILTER_VALIDATE_EMAIL)){
			return true;
		}else{
			return false;
		}
	}
	public function saveDeveloperDetails($developerId)
	{
		if(!empty($developerId)) {
			$InstallerPlans           			= TableRegistry::get('InstallerPlans');
			$DeveloperSubscription 				= TableRegistry::get('DeveloperSubscription');
			$DeveloperCredendtials 				= TableRegistry::get('DeveloperCredendtials');
			$DeveloperActivationCodes 			= TableRegistry::get('DeveloperActivationCodes');
			$DeveloperCustomers 				= TableRegistry::get('DeveloperCustomers');
			$Parameters 						= TableRegistry::get('Parameters');

			//$this->updateAll(['payment_status' => '1','modified'=>$this->NOW()], ['id' => decode($payusave->udf1)]);
			
			$DeveloperDetails 					= $this->find('all',array('conditions'=>array('id'=>$developerId)))->first();
			if(!empty($DeveloperDetails)) {
				$arrName 						= explode(" ",$DeveloperDetails->contact_person);
				$RandomPassword 				= strtolower($arrName[0]).'@'.date('Y');
				$arrEmail 						= explode(",",$DeveloperDetails->email);
				$CustomerEmail 					= trim($arrEmail[0]);
				$developerCustomerDetails 		= $DeveloperCustomers->find('all',array('conditions'=>array('email'=>$CustomerEmail,'status'=>$DeveloperCustomers->STATUS_INACTIVE)))->first();
				if(empty($developerCustomerDetails)) {
					$DeveloperCustomersEntity 				= $DeveloperCustomers->newEntity();
					$DeveloperCustomersEntity->mobile 		= $DeveloperDetails->mobile;
					$DeveloperCustomersEntity->email 		= $CustomerEmail;
					$DeveloperCustomersEntity->name 		= $DeveloperDetails->contact_person;
					$DeveloperCustomersEntity->password 	= Security::hash(Configure::read('Security.salt') . $RandomPassword);
					$DeveloperCustomersEntity->status 		= $DeveloperCustomers->STATUS_INACTIVE;
					$DeveloperCustomersEntity->customer_type= "developer";
					$DeveloperCustomersEntity->state 		= 4;
					$DeveloperCustomersEntity->installer_id = $developerId;
					$DeveloperCustomersEntity->created 		= $this->NOW();
					if ($DeveloperCustomers->save($DeveloperCustomersEntity)) 
					{
						$insplanData 									= $InstallerPlans->get($InstallerPlans->DEFAULT_PLAN_ID);
						$DeveloperSubscriptionEntity 					= $DeveloperSubscription->newEntity();
						$DeveloperSubscriptionEntity->payment_status 	= '';
						$DeveloperSubscriptionEntity->installer_id 		= $developerId;
						$DeveloperSubscriptionEntity->coupen_code 		= '';
						$DeveloperSubscriptionEntity->transaction_id 	= '';
						$DeveloperSubscriptionEntity->created 			= $this->NOW();
						$DeveloperSubscriptionEntity->modified 			= $this->NOW();
						$DeveloperSubscriptionEntity->payment_gateway   = '';
						$DeveloperSubscriptionEntity->comment 			= '100% Discount';
						$DeveloperSubscriptionEntity->payment_data 		= '';
						$DeveloperSubscriptionEntity->amount 			= '0';
						$DeveloperSubscriptionEntity->coupen_id         = '0';
						$DeveloperSubscriptionEntity->is_flat			= '0';
						$DeveloperSubscriptionEntity->plan_name 		= $insplanData->plan_name;
						$DeveloperSubscriptionEntity->plan_price		= $insplanData->plan_price;
						$DeveloperSubscriptionEntity->plan_id			= $InstallerPlans->DEFAULT_PLAN_ID;
						$DeveloperSubscriptionEntity->user_limit		= $insplanData->user_limit;
						$DeveloperSubscriptionEntity->start_date		= date('Y-m-d');
						$DeveloperSubscriptionEntity->expire_date 		= date('Y-m-d',strtotime("+ 30 days"));
						$DeveloperSubscriptionEntity->status			= '1';
						$DeveloperSubscriptionEntity->created_by		= $DeveloperCustomersEntity->id;
						$DeveloperSubscriptionEntity->modified_by 		= $DeveloperCustomersEntity->id;
						
						$DeveloperSubscription->save($DeveloperSubscriptionEntity);
						
						$insCodeArr = array();
						for ($i=0; $i < $insplanData->user_limit; $i++) {
							$activation_codes = $this->generateDeveloperActivationCodes();
							$insCodeArr[]                                               = $activation_codes;
							$insCodedata['DeveloperActivationCodes']['installer_id']    = $developerId;
							$insCodedata['DeveloperActivationCodes']['activation_code'] = $activation_codes;
							$insCodedata['DeveloperActivationCodes']['start_date']      = date('Y-m-d');
							$insCodedata['DeveloperActivationCodes']['expire_date']     = date('Y-m-d',strtotime("+ 30 days"));
							$insCodeEntity = $DeveloperActivationCodes->newEntity($insCodedata);
							$DeveloperActivationCodes->save($insCodeEntity);
						}
						$DeveloperCustomers->updateAll(['user_role'=>$Parameters->admin_role,'default_admin'=>1,'installer_id' => $developerId,'modified' => $this->NOW()], ['id' => $DeveloperCustomersEntity->id]);

						$PasswordInfo['DeveloperCredendtials']['installer_id']  = $developerId;
						$PasswordInfo['DeveloperCredendtials']['password']      = $RandomPassword;
						$DeveloperCredendtialsEnt 								= $DeveloperCredendtials->newEntity($PasswordInfo);
						$DeveloperCredendtials->save($DeveloperCredendtialsEnt);
					}
				}
			}
		}	
	}
	/**
	 * generateDeveloperReceiptPdf
	 * Behaviour : public
	 * @param : id  : Id is use to identify for which installer letter file should be downlaoded, $isdownload=true
	 * @defination : Method is use to download .pdf file from applyonline listing
	 *
	 */
	public function generateDeveloperReceiptPdf($id,$isdownload=true,$mailData=false)
	{
		$ApplyOnlines 						= TableRegistry::get('ApplyOnlines');
		$DeveloperPayment 					= TableRegistry::get('DeveloperPayment');
		$DeveloperSuccessPayment 			= TableRegistry::get('DeveloperSuccessPayment');
		
		$MembersTable 						= TableRegistry::get('Members');
		$BranchMasters 						= TableRegistry::get('BranchMasters');
		$DiscomMaster 						= TableRegistry::get('DiscomMaster');
		$DeveloperApplicationCategoryMapping= TableRegistry::get('DeveloperApplicationCategoryMapping');
		if(empty($id)) {
			$this->Flash->error('Please Select Valid Installer.');
			return $this->redirect('home');
		} else {
			$encode_id 					= $id;
			$id 						= intval(decode($id));
			$installer_id 				= $id;
			
			$payment_data 				= $DeveloperSuccessPayment->find('all',array('conditions'=>array('installer_id'=>$id),'order'=>array('id'=>'desc')))->first();
			
			$payment_details 			= $DeveloperPayment->find('all',array('conditions'=>array('id'=>$payment_data->payment_id)))->first();

			$InstallersData 			= $this->find('all',array('conditions'=>array('id'=>$id)))->first();
		}
		$view = new View();
		$view->layout 				= false;
		$mapDetails 				= $DeveloperApplicationCategoryMapping->find('all',array(
									'fields' 	=> array('DeveloperApplicationCategoryMapping.developer_fee','application_category.category_name'),
									'join'		=> ['application_category'=>['table'=>'application_category','type'=>'left','conditions'=>'application_category.id=DeveloperApplicationCategoryMapping.application_category_id']],
									'conditions'=> array('installer_id'=>$id))
									)->toArray();
		$arrMapCategory 			= array();
		if(!empty($mapDetails)) {
			foreach($mapDetails as $arrDetails) {
				$arrMapCategory[$arrDetails->application_category['category_name']] =  $arrDetails->developer_fee;
			}
		}
		
		$view->set("pageTitle","Apply-online View");
		$view->set('InstallersData',$InstallersData);
		$view->set('payment_data',$payment_data);
		$view->set('payment_details',$payment_details);
		$view->set('arrMapCategory',$arrMapCategory);
		//$this->set("APPLY_ONLINE_GUJ_STATUS",$this->ApplyOnlineApprovals->apply_online_guj_status);

		/* Generate PDF for estimation of project */
		require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
		$dompdf = new Dompdf($options = array());
		$dompdf->set_option("isPhpEnabled", true);
		$view->set('dompdf',$dompdf);
		$dompdf->set_base_path(SITE_ROOT_DIR_PATH.'/pdf/');

		$html = $view->render('/Element/developer_payment_receipt');
		$dompdf->loadHtml($html,'UTF-8');
		$dompdf->setPaper('A4', 'portrait');
		
		$dompdf->render();

		// Output the generated PDF to Browser
		if($isdownload){
			$dompdf->stream('paymentreceipt-'.$installer_id);
		}
		$output = $dompdf->output();
		if($mailData)
		{
			$pdfPath 	= WWW_ROOT.'/tmp/paymentReceipt-'.$installer_id.'.pdf';
			file_put_contents($pdfPath, $output);
			return $pdfPath;
		}
		else
		{
			header("Content-type:application/pdf");
			header("Content-Disposition:inline;filename='".$installer_id.".pdf'");
			echo $output;
		}
		die;
	}

	public function generateUpgPackageDeveloperReceiptPdf($id,$isdownload=true,$mailData=false)
	{
		$ApplyOnlines 						= TableRegistry::get('ApplyOnlines');
		$DeveloperPayment 					= TableRegistry::get('DeveloperPayment');
		$DeveloperSuccessPayment 			= TableRegistry::get('DeveloperSuccessPayment');
		
		$MembersTable 						= TableRegistry::get('Members');
		$BranchMasters 						= TableRegistry::get('BranchMasters');
		$DiscomMaster 						= TableRegistry::get('DiscomMaster');
		$DeveloperApplicationCategoryMapping= TableRegistry::get('DeveloperApplicationCategoryMapping');
		if(empty($id)) {
			$this->Flash->error('Please Select Valid Installer.');
			return $this->redirect('home');
		} else {
			$encode_id 					= $id;
			$id 						= intval(decode($id));
			//$installer_id 				= $id;
			$payment_data 				= $DeveloperSuccessPayment->find('all',array('conditions'=>array('payment_id'=>$id),'order'=>array('id'=>'desc')))->first(); //2

			$payment_details 			= $DeveloperPayment->find('all',array('conditions'=>array('id'=>$id)))->first(); //2

			$InstallersData 			= $this->find('all',array('conditions'=>array('id'=>$payment_details->installer_id)))->first();
			
		}
		$view = new View();
		$view->layout 				= false;
		$mapDetails 				= $DeveloperApplicationCategoryMapping->find('all',array(
									'fields' 	=> array('DeveloperApplicationCategoryMapping.developer_fee','DeveloperApplicationCategoryMapping.gst_fees','application_category.category_name'),
									'join'		=> ['application_category'=>['table'=>'application_category','type'=>'left',
													'conditions'=>'application_category.id=DeveloperApplicationCategoryMapping.application_category_id']],
									'conditions'=> array('payment_success_id'=>$payment_data->id))
									)->toArray();
		$arrMapCategory 			= array();
		$gst=0;
		if(!empty($mapDetails)) {
			
			foreach($mapDetails as $arrDetails) {
				$arrMapCategory[$arrDetails->application_category['category_name']] =  $arrDetails->developer_fee;
				$gst = $gst + $arrDetails->gst_fees;
			}
		}


		
		$view->set("pageTitle","Developer Payment Receipt");
		$view->set('InstallersData',$InstallersData);
		$view->set('payment_data',$payment_data);
		$view->set('payment_details',$payment_details);
		$view->set('arrMapCategory',$arrMapCategory);
		$view->set('gst',$gst);
		//$this->set("APPLY_ONLINE_GUJ_STATUS",$this->ApplyOnlineApprovals->apply_online_guj_status);

		/* Generate PDF for estimation of project */
		require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
		$dompdf = new Dompdf($options = array());
		$dompdf->set_option("isPhpEnabled", true);
		$view->set('dompdf',$dompdf);
		$dompdf->set_base_path(SITE_ROOT_DIR_PATH.'/pdf/');

		$html = $view->render('/Element/upg_developer_payment_receipt');
		$dompdf->loadHtml($html,'UTF-8');
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();

		// Output the generated PDF to Browser
		if($isdownload){
			$dompdf->stream('paymentreceipt-'.$id);
		}
		
		$output = $dompdf->output();
		if($mailData)
		{
			$pdfPath 	= WWW_ROOT.'/tmp/paymentReceipt-'.$id.'.pdf';
			file_put_contents($pdfPath, $output);
			return $pdfPath;
		}
		else
		{
			header("Content-type:application/pdf");
			header("Content-Disposition:inline;filename='".$id.".pdf'");
			echo $output;			
		}
		die;
	}
}
?>
