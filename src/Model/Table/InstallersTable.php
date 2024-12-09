<?php
namespace App\Model\Table;

use App\Model\Table\Entity;
use App\Model\Entity\User;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

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

class InstallersTable extends AppTable
{
	var $table 		= 'installers';
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
		$validator->notEmpty('installer_name','Name of the Solar Installer Company can not be blank.');
		$validator->notEmpty('contact_person','Name of the Head of the Installer can not be blank.');
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
		if(!isset($this->data['Installers']['gst_check']) || (isset($this->data['Installers']['gst_check']) && $this->data['Installers']['gst_check'] != 1)) {
			$validator->notEmpty('GST', 'GST Number can not be blank.');
			if(!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->gst_certificate))) {
				$validator->notEmpty('f_gst_certificate', 'Upload GST Certificate file required.');
			}
		}
		
		
		if(isset($this->data['Installers']['pan']) && strlen($this->data['Installers']['pan']) != 10)
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
		if(isset($this->data['Installers']['mobile']) && strlen($this->data['Installers']['mobile']) != 10)
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
				"rule" => [$this, "custom_unique"],
				"pass" => array('email'),
				"message" => "Email already exist."
					]
				]
		);
		$validator->add("email", [
			"_empty" => [
				"rule" => [$this, "custom_unique_customer"],
				"pass" => array('email'),
				"message" => "Email already mapped with customer."
					]
				]
		);
		if(isset($this->data['Installers']['email']) && !empty($this->data['Installers']['email'])) {
			
			$validator->add("email", [
				"_empty" => [
					"rule" => [$this, "validateEmail1"],
					"pass" => array('email'),
					"message" => "Wrong Email."
						]
					]
			);
		}
		if(isset($this->data['Installers']['reply']) && empty($this->data['Installers']['reply'])) {
			
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
		
		if(isset($this->data['Installers']['pincode']) && strlen($this->data['Installers']['pincode']) != 6)
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
			$validator->notEmpty('f_registration_document', 'Upload Company/ Organization Registration Document file required.');
		}
		if(!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->pan_card))) {
			$validator->notEmpty('f_pan_card', 'Upload PAN Card file required.');
		}
		$validator->notEmpty('selected_category', 'Please select atleast one.');
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

		$arr_condition['Installers.'.$action['field']] = $value;
		if(isset($this->data['Installers']['company_id']) && !empty($this->data['Installers']['company_id']))
		{
			$arr_condition['Installers.company_id != '] = $this->data['Installers']['company_id'];
		}
		$arr_result= $this->find('all',array('conditions'   => $arr_condition))->first();
		
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
		$Customers = TableRegistry::get('Customers');
		$arr_condition['Customers.'.$action['field']] 	= $value;
		$arr_condition['installers.geda_approval !='] 	= 2;
		$arr_result= $Customers->find('all',array('conditions' => $arr_condition,'join'=>[['table'=>'installers','type'=>'left','conditions'=>'Customers.installer_id = installers.id']]))->first();
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
	* generateInstallerActivationCodes
	*
	* Behaviour : public
	*
	* @defination : Method is used to generate customer activation codes.
	*
	* Author : Khushal Bhalsod
	*/
	public function generateInstallerActivationCodes()
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
		return $this->find('all',['join'=>[['table'=>'states','type'=>'left','conditions'=>'states.id = Installers.state or states.statename = Installers.state']],
			'conditions'=>['Installers.stateflg' => $state]
			])->count();

	}
	public function GetInstallerList($state = '',$installer_name = '',$category_name = '') {
		$fields             = [ 'Installers.installer_name',
								'Installers.address',
								'Installers.state',
								'Installers.city',
								'Installers.email',
								'Installers.mobile',
								'installer_category_mapping.category_id',
								'installer_category_mapping.short_name',
								'installer_category_mapping.allowed_bands',
								'installer_category.category_name',
								'Installers.installer_old_name',
								'Installers.name_change_date'
                            ];
		$join_arr=[['table'=>'states','type'=>'left','conditions'=>'states.id = Installers.state or 			states.statename = Installers.state'],
					['table'=>' installer_category_mapping','type'=>'left','conditions'=>' installer_category_mapping.installer_id = Installers.id'],
					['table'=>' installer_category','type'=>'left','conditions'=>' installer_category.id = installer_category_mapping.category_id']
					];

		$name_arr =['Installers.stateflg' => $state,'Installers.status' => 1];
		if(!empty($installer_name)){
			if(is_array($installer_name))
			{ 
				array_push($name_arr,array('Installers.id in' => $installer_name));
			
			}
			else
			{
				array_push($name_arr,array('Installers.installer_name like' => '%' .$installer_name. '%'));
			}
			
		}

		if(!empty($category_name)){
			array_push($name_arr,array('installer_category_mapping.category_id ' => $category_name));
		}
		return $this->find('all',[ 'fields'=>$fields,
			'join'=>$join_arr,
			'conditions'=>$name_arr,
			'order' => ['Installers.installer_name' => 'ASC']
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
}
?>