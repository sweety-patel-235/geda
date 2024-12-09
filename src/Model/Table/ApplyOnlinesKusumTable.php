<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;
use Cake\Core\Configure;
use Cake\Network\Email\Email;
use Couchdb\Couchdb;
use Cake\View\View;
use Dompdf\Dompdf;
class ApplyOnlinesKusumTable extends AppTable
{
	var $table = 'apply_onlines_kusum';
	var $data  = array();
	var $category_residental    = "3001";
	var $category_government    = "3004";
	var $category_industrial    = "3002";
	var $category_ht_indus      = "3006";
	var $category_commercial    = "3003";
	var $category_others        = "3005";
	var $TypeIndividual			= 8001;
	var $TypeGroup				= 8002;
	var $TypeCooperative		= 8003;
	var $TypePanchayat			= 8004;
	var $TypeFarmer				= 8005;
	var $TypeDeveloper			= 8006;
	var $torent_ahmedabad       = "15";
	var $torent_surat           = "16";
	var $torent_dahej           = "17";
	var $data_entity            = array();
	var $data_entity_others     = array();

	var $JREDA  = "6001";
	var $DISCOM = "6002";
	var $CEI    = "6003";
	var $apply_online_dropdown_status = ['1'=>'Application Submitted',
										'2'=>'Geda Letter',
										'10'=>'Document Not Verified',
										'3'=>'Document Verified',
										'4'=>'Feasibility Approved',
										'5'=>'CEI Approval',
										'6'=>'Work Starts',
										'7'=>'CEI Inspection',
										'8'=>'Meter Installation',
										'9'=>'Subsidy Claimed',
										'99'=>'Application Cancelled',
										'6002'=>'Comment Raised',
										'6000'=>'Comment Replied',
									];
	var $apply_online_status_key = ['1'=>'1',
										'2'=>'31',
										'3'=>'23',
										'4'=>'2',
										'5'=>'9',
										'6'=>'4',
										'7'=>'27',
										'8'=>'17',
										'9'=>'28',
										'10'=>'9999',
										'99'=>'99',
										'6002'=>'6002',
										'6000'=>'0'];
	var $gujarat_st_id              = '4';
	var $gujarat_st_name            = 'gujarat';
	var $arrDashboardBlocks         = ["APPLICATION_SUBMITTED"  =>1,
										"APPROVED_FROM_GEDA"    =>2,
										"DOCUMENT_VERIFIED"     =>3,
										"DOCUMENT_NOT_VERIFIED" =>10,
										"APPLICATION_CANCELLED" =>99,
										"METER_INSTALLATION"    =>8,
										"NON_SUBSIDY"           =>'1_N',
										"PCR_GENERATED"         =>'PCR',
										"SOCIAL_CONSUMER"       =>'SC',
										"RESIDENTIAL"           =>"3001",
										"HT_INDUSTRIES"         =>"3006",
										"OTHERS"                =>"3005",
										"INS_COM"               =>"3002,3003",
										"INSPECTION"            =>"INSPECTION",
										"PCR_SUBMITTED"         =>'PCR_S',
										"DELETE_APP_REQUEST"    =>'DAR',
										"MSME"                  =>'MSME'];
	var $installer_slot_array       = array("1" => array("min"=>1.00,"max"=>6.00),
											"2" => array("min"=>6.01,"max"=>10.00),
											"3" => array("min"=>10.01,"max"=>50.00),
											"4" => array("min"=>50.01,"max"=>50000.00)
										);
	var $ALLOWED_APPROVE_GEDAIDS    = ALLOW_ALL_ACCESS;
	var $mobile_excluded            = array('9958785119','9409080742');
	var $DOCUMENT_VERIFIED_STATUS   = 23;
	var $DOCUMENT_NOT_VERIFIED      = 9999;
	var $PHASE_ARRAY                = array('1'=>'Single Phase','3'=>'3 Phase');
	var $AllowedGedaIDS             = array('1324','1325','1326','1327','1328');
	public function initialize(array $config)
	{
		$this->table($this->table);       
		/*$this->belongsTo('InstallersData', [
			'className' => 'Installers',
			'foreignKey' => 'installer_id',
			'propertyName' => 'installer_data'
		]);
		$this->belongsTo('Installers1', [
			'className' => 'Installers',
			'foreignKey' => 'installer_created_id',
			'propertyName' => 'installer_created_data'
		]);*/
		 $this->belongsTo('Installers', [
            'className' => 'Installers',
            'foreignKey' => 'installer_created_id'
        ]);
		 $this->belongsTo('Installers1', [
            'className' => 'Installers',
            'foreignKey' => 'installer_id',
            'propertyName' => 'Installers1'
        ]);
	}
	

	/**
	 * Add validation rules.
	 *
	 * @param \Cake\Validation\Validator $validator Validator instance.
	 * @return \Cake\Validation\Validator
	 */
	public function validationAdd(Validator $validator)
	{   
		$validator->notEmpty('installer_id', 'Installer must be select.');
		$validator->notEmpty('applicant_type_kusum', 'Applicant type must be select.');
		$validator->notEmpty('application_type_name', 'Name can not be blank.');
		$validator->add("application_type_name", [
					 "validFormat" => [
					 'rule' => array('custom', '/^[A-Za-z ]+$/'),
					 'message' => 'Applicant Name alphabets and space are allows.'
						]
			]);
		$validator->notEmpty('aadhaar_no', 'Aadhaar no. can not be blank.');
		if(strlen(trim($this->data['ApplyOnlinesKusum']['aadhaar_no']))!=12)
		{
			$validator->add("aadhaar_no", [
				 "_empty" => [
				 "rule" => [$this, "customFalse"],
				 "message" => "Aadhaar Card Number must be a 12 digits."
					]
			 ]);
		}
		$validator->add("aadhaar_no", [
				 "validFormat" => [
				 'rule' => array('custom', '/^[0-9]+$/'),
				 'message' => 'Aadhaar no. digits are allows.'
					]
		]);

		if(empty($this->data_entity) || (!empty($this->data_entity) && empty($this->data_entity->aadhaar_file))) {
			$validator->notEmpty('file_aadhaar_card', 'Copy of Aadhaar Card file required.');
		}
		if(isset($this->data['ApplyOnlinesKusum']['applicant_type_kusum']) && !empty($this->data['ApplyOnlinesKusum']['applicant_type_kusum'])) 
		{
			if(in_array($this->data['ApplyOnlinesKusum']['applicant_type_kusum'],array($this->TypeIndividual,$this->TypeGroup,$this->TypeCooperative,$this->TypeFarmer,$this->TypeDeveloper))) {
				
				if(empty($this->data_entity) || (!empty($this->data_entity) && empty($this->data_entity->copy_registration))) {
					$validator->notEmpty('file_copy_registration', 'Enclosed Copy Of Registration file required.');
				}
			}
			if(in_array($this->data['ApplyOnlinesKusum']['applicant_type_kusum'],array($this->TypeIndividual))) {
				
			}
			if(isset($this->data['ApplyOnlinesKusum']['members']) && !empty($this->data['ApplyOnlinesKusum']['members']) && in_array($this->data['ApplyOnlinesKusum']['applicant_type_kusum'],array($this->TypeGroup)))
			{
				$flag_val_exist 	= 0;
				$flag_val_notvalid 	= 0;
				foreach($this->data['ApplyOnlinesKusum']['members'] as $key=>$val){
					if(trim($val) != '') {
						$flag_val_exist 	= 1;
					}
					if(!preg_match('/^[A-Za-z\s]+$/', $val)) {
						$flag_val_notvalid 	= 1;
					}
					
				}
				if($flag_val_exist == 0) {
					$validator->add("members", [
							"_empty" => [
							"rule" => [$this, "customFalse"],
							"message" => "Please enter at least one member name."
							]
						]
					);
				}
				if($flag_val_notvalid == 1) {
					$validator->add("members", [
							"_empty" => [
							"rule" => [$this, "customFalse"],
							"message" => "Member Name alphabets and space are allows."
							]
						]
					);
				}
			}
		}
		
		$validator->notEmpty('correspondence_address', 'Correspondence address can not be blank.');
		$validator->notEmpty('authorized_person', 'Name of the authorized person can not be blank.');
		if(empty($this->data_entity) || (!empty($this->data_entity) && empty($this->data_entity->authorize_letter))) {
			$validator->notEmpty('file_authorize_letter', 'Enclose Letter Of Authorization file required.');
		}
		$validator->notEmpty('mobile', 'Mobile Number can not be blank.');
		$validator->notEmpty('email', 'E-mail Id can not be blank.');
		$validator->notEmpty('discom', 'Discom must be select.');
		$validator->notEmpty('division', 'Division must be select.');
		$validator->notEmpty('subdivision', 'Subdivision must be select.');
		//$validator->notEmpty('name_power_utility', 'Name of power utility can not be blank.');
		$validator->notEmpty('district', 'District must be select.');
		$validator->notEmpty('panchayat_committee', 'Taluka can not be blank.');
		$validator->notEmpty('name_substation', 'Name of substation can not be blank.');
		$validator->notEmpty('declare_capacity', 'Declared capacity for Solar Power Project can not be blank.');
		$validator->notEmpty('village_name', 'Name of Village can not be blank.');
		$validator->notEmpty('taluka', 'Taluka can not be blank.');
		$validator->notEmpty('land_district', 'Land district must be select.');
		if(empty($this->data_entity) || (!empty($this->data_entity) && empty($this->data_entity->jamabandi))) {
			$validator->notEmpty('file_jamabandi', 'Copy of Jamabandi file required.');
		}
		$validator->notEmpty('pv_capacity', 'Solar plant capacity can not be blank.');
		$validator->notEmpty('distance_plant', 'Distance between the Proposed land and sub-station can not be blank.');
		$validator->notEmpty('distance_type', 'Distance type must be select.');
		$validator->notEmpty('option_solar', 'Options available must be select.');
		$validator->notEmpty('installer_id', 'Installer Name must be select.');
		$validator->notEmpty('installer_email', 'Installer Email can not be blank.');
		$validator->notEmpty('installer_mobile', 'Installer Mobile can not be blank.');
		
		//if(!empty($this->data_entity) && empty($this->data_entity->file_copy_registration)){
			
		//}
		
		return $validator;
	}
	/**
	 * saveDetails
	 * Behaviour : Public
	 * @param 		: $arrRequest - requires for pass post form data, $arrSessionInfo - requires for pass login person information.
	 * @defination : Method is use to save data for apply online kusum
	 */
	public function saveDetails($arrRequest,$arrSessionInfo) {
		$this->data 				= $arrRequest;
			
		$KusumMembers 				= TableRegistry::get('KusumMembers');
		$KusumSurveyInformation 	= TableRegistry::get('KusumSurveyInformation');
		$DiscomMaster 				= TableRegistry::get('DiscomMaster');
		$ApplyOnlineKusumApprovals 	= TableRegistry::get('ApplyOnlineKusumApprovals');

		if(isset($arrRequest['ApplyOnlinesKusum']['id']) && !empty($arrRequest['ApplyOnlinesKusum']['id'])) {
			$arrDetails 		= $this->get($arrRequest['ApplyOnlinesKusum']['id']);
			$this->data_entity 	= $arrDetails;
			$ApplyOnlinesEntity	= $this->patchEntity($arrDetails,$arrRequest,['validate'=>'Add']);

		} else {
			$ApplyOnlinesEntity	= $this->newEntity($arrRequest,['validate'=>'Add']);
		}

		$ApplyOnlinesEntity->submit = 0;
		$this->data_entity 			= $ApplyOnlinesEntity;
		if(empty($ApplyOnlinesEntity->errors())) {
			if(!empty($arrRequest['ApplyOnlinesKusum']['aadhaar_no']))
			{
				$ApplyOnlinesEntity->aadhaar_no	= passencrypt($arrRequest['ApplyOnlinesKusum']['aadhaar_no']);
			}
			$ApplyOnlinesEntity->created 	 			= $this->NOW();
			$ApplyOnlinesEntity->created_by  			= $arrSessionInfo['login_id'];
			$ApplyOnlinesEntity->modified 	 			= $this->NOW();
			$ApplyOnlinesEntity->modified_by 			= $arrSessionInfo['login_id'];
			$ApplyOnlinesEntity->customer_id			= $arrSessionInfo['login_id'];
			$ApplyOnlinesEntity->installer_created_id	= $arrSessionInfo['installer_id'];
			$arrDiscom 									= $DiscomMaster->GetDiscomHirarchyByID($ApplyOnlinesEntity->subdivision);
			$ApplyOnlinesEntity->area 					= $arrDiscom->area;
			$ApplyOnlinesEntity->circle 				= $arrDiscom->circle;
			$this->save($ApplyOnlinesEntity);
			$approval 									= $ApplyOnlineKusumApprovals->Approvalstage($ApplyOnlinesEntity->id);
			
			if(!in_array($ApplyOnlineKusumApprovals->APPLICATION_PENDING,$approval))
			{
				$application_status = $ApplyOnlineKusumApprovals->APPLICATION_GENERATE_OTP;
			}
			else
			{
				$approval 			= $ApplyOnlineKusumApprovals->find('all',array('conditions'=>array('application_id'=>$ApplyOnlinesEntity->id)))->last();
				$application_status = $approval->stage;
			}

			$application_no 		= $this->GenerateApplicationNo($ApplyOnlinesEntity);
			$this->updateAll(array('application_no'=>$application_no,'application_status'=>$application_status),array('id'=>$ApplyOnlinesEntity->id));

			if(!in_array($ApplyOnlineKusumApprovals->APPLICATION_PENDING,$approval))
			{
				$sms_mobile 		= $ApplyOnlinesEntity->installer_mobile;
				$sms_message 		= str_replace('##application_no##', $ApplyOnlinesEntity->application_no, OTP_VERIFICATION);
				$this->SendSMSActivationCode($ApplyOnlinesEntity->id,$sms_mobile,$sms_message,'OTP_VERIFICATION');
			}

			$image_path = APPLYONLINE_KUSUM_PATH.$ApplyOnlinesEntity->id.'/';
			if(!file_exists(APPLYONLINE_KUSUM_PATH.$ApplyOnlinesEntity->id)) {
				@mkdir(APPLYONLINE_KUSUM_PATH.$ApplyOnlinesEntity->id, 0777);
			}
			if(!empty($arrRequest['ApplyOnlinesKusum']['file_copy_registration']) && !empty($arrRequest['ApplyOnlinesKusum']['file_copy_registration']['name']) ) {
				$db_copy_registration = $ApplyOnlinesEntity->copy_registration;
				if(file_exists($image_path.$db_copy_registration) && !empty($db_copy_registration)){
					@unlink($image_path.$db_copy_registration);
				}
				$file_name = $this->file_upload($image_path,$arrRequest['ApplyOnlinesKusum']['file_copy_registration'],false,'','',$image_path,'k_cr','copy_registration',$arrSessionInfo['login_id']);
				$this->updateAll(['copy_registration' => $file_name], ['id' => $ApplyOnlinesEntity->id]);
			}
			if(!empty($arrRequest['ApplyOnlinesKusum']['file_jamabandi']) && !empty($arrRequest['ApplyOnlinesKusum']['file_jamabandi']['name']) ) {
				$db_jamabandi = $ApplyOnlinesEntity->jamabandi;
				if(file_exists($image_path.$db_jamabandi) && !empty($db_jamabandi)){
					@unlink($image_path.$db_jamabandi);
				}
				$file_name = $this->file_upload($image_path,$arrRequest['ApplyOnlinesKusum']['file_jamabandi'],false,'','',$image_path,'k_jam','jamabandi',$arrSessionInfo['login_id']);

				$this->updateAll(['jamabandi' => $file_name], ['id' => $ApplyOnlinesEntity->id]);
			}
			if(!empty($arrRequest['ApplyOnlinesKusum']['file_authorize_letter']) && !empty($arrRequest['ApplyOnlinesKusum']['file_authorize_letter']['name']) ) {
				$db_authorize_letter = $ApplyOnlinesEntity->authorize_letter;
				if(file_exists($image_path.$db_authorize_letter) && !empty($db_authorize_letter)){
					@unlink($image_path.$db_authorize_letter);
				}
				$file_name = $this->file_upload($image_path,$arrRequest['ApplyOnlinesKusum']['file_authorize_letter'],false,'','',$image_path,'k_al','authorize_letter',$arrSessionInfo['login_id']);
				$this->updateAll(['authorize_letter' => $file_name], ['id' => $ApplyOnlinesEntity->id]);
			}
			if(!empty($arrRequest['ApplyOnlinesKusum']['file_aadhaar_card']) && !empty($arrRequest['ApplyOnlinesKusum']['file_aadhaar_card']['name'])) {
				$db_aadhaar_file = $ApplyOnlinesEntity->aadhaar_file;
				if(file_exists($image_path.$db_aadhaar_file) && !empty($db_aadhaar_file)){
					@unlink($image_path.$db_aadhaar_file);
				}
				$file_name = $this->file_upload($image_path,$arrRequest['ApplyOnlinesKusum']['file_aadhaar_card'],false,'','',$image_path,'k_adh','aadhaar_file',$arrSessionInfo['login_id']);
				$this->updateAll(['aadhaar_file' => $file_name], ['id' => $ApplyOnlinesEntity->id]);
			}
		
			if(isset($arrRequest['ApplyOnlinesKusum']['members']) && !empty($arrRequest['ApplyOnlinesKusum']['members'])) {
				if(isset($arrRequest['ApplyOnlinesKusum']['id']) && !empty($arrRequest['ApplyOnlinesKusum']['id'])) {
					$KusumMembers->deleteAll(['application_id'=>$arrRequest['ApplyOnlinesKusum']['id']]);
				}
				foreach($arrRequest['ApplyOnlinesKusum']['members'] as $memberName) {
					if(!empty(trim($memberName))) {
						$memberEntity 					= $KusumMembers->newEntity();
						$memberEntity->application_id 	= $ApplyOnlinesEntity->id;
						$memberEntity->name 			= $memberName;
						$memberEntity->created 			= $this->NOW();
						$memberEntity->created_by 		= $arrSessionInfo['login_id'];
						$memberEntity->modified 		= $this->NOW();
						$memberEntity->modified_by 		= $arrSessionInfo['login_id'];
						$KusumMembers->save($memberEntity);
					}
				}
			}

			if(isset($arrRequest['ApplyOnlinesKusum']['land_survey_area']) && !empty($arrRequest['ApplyOnlinesKusum']['land_survey_area'])) {
				if(isset($arrRequest['ApplyOnlinesKusum']['id']) && !empty($arrRequest['ApplyOnlinesKusum']['id'])) {
					$KusumSurveyInformation->deleteAll(['application_id'=>$arrRequest['ApplyOnlinesKusum']['id']]);
				}
				foreach($arrRequest['ApplyOnlinesKusum']['land_survey_area'] as $k => $surveyarea) {
					if(!empty($surveyarea))
					{
						$surveyEntity 					= $KusumSurveyInformation->newEntity();
						$surveyEntity->application_id 	= $ApplyOnlinesEntity->id;
						$surveyEntity->survey_area 		= $surveyarea;
						$surveyEntity->survey_no 		= $arrRequest['ApplyOnlinesKusum']['land_survey_no'][$k];
						$surveyEntity->survey_type 		= $arrRequest['ApplyOnlinesKusum']['land_survey_type_'.$k];
						$surveyEntity->created 			= $this->NOW();
						$surveyEntity->created_by 		= $arrSessionInfo['login_id'];
						$surveyEntity->modified 		= $this->NOW();
						$surveyEntity->modified_by 		= $arrSessionInfo['login_id'];
						$KusumSurveyInformation->save($surveyEntity);
					}
				}
			}
			$ApplyOnlinesEntity->submit = 1;
		}
		return $ApplyOnlinesEntity;
	}

	/**
	 * Add check validation.
	 *
	 * @param \Cake\Validation\Validator $validator Validator instance.
	 * @return \Cake\Validation\Validator
	 */
	public function checkSize($data, $required = false){
		$data = array_shift($data);
		if(!$required && $data['error'] == 4){
			return true;
		}
		if($data['size'] == 0||$data['size']/1024 > 3000){
			return false;
		}
		return true;
	}

	public function GenerateApplicationNo($application) {
		$StateCode  = 'GUJ';//$this->GetStateCode($application->apply_state,$application->state);
		$id         = $application->id;
		$appendStr  = 'KUS';
		/*if(($application->apply_state==$this->gujarat_st_id || strtolower($application->apply_state)==$this->gujarat_st_name) && $application->category!='')
		{
			$Param      = TableRegistry::get('Parameters');
			$arrParam   = $Param->find("all",['conditions'=>['para_id'=>$application->category]])->first();
			if(!empty($arrParam->para_value))
			{
				$appendStr = substr(strtoupper($arrParam->para_value), 0,3);
			}
		}*/
		$id         = $StateCode."/RT/".$appendStr."/1".str_pad($id,7, "0", STR_PAD_LEFT);
		return $id;
	}

	public function GetStateCode($state,$state_name="") 
	{
		$STATENAME  = "";
		$Code       = "";
		$States     = TableRegistry::get('States');
		$arrState   = $States->find("all",['conditions'=>['OR'=>['States.id'=>$state,'States.statename'=>$state]]])->first();
		if(!empty($arrState)) {
			$STATENAME = $arrState->state_code;   
			$Code = $STATENAME;
		} else {
			$arrState   = $States->find("all",['conditions'=>['OR'=>['LOWER(States.statename)'=>strtolower($state_name)]]])->first();
			if(!empty($arrState)) {
				$STATENAME  = $arrState->state_code;   
				$Code       = $STATENAME;
			}
			else
			{
				$STATENAME  = $state_name;
				$Code       = strtoupper(substr($STATENAME,0,2));
			}
			
		}
		return $Code;
	}

	
	/**
	*
	* customFunction_consumer
	* Behaviour : public
	* Parameter : discom
	* @defination : Method is used to check consumer number length validation.
	*/
	public function customFalse($value, $context)
	{
		return false;
		
	}
	/**
	* custom_consumer_unique
	* Behaviour : public
	* Parameter : discom
	* @defination : Method is used to check consumer number unique last 6 digit.
	*/
	public function custom_consumer_unique($value, $context)
	{
		//$last_data = substr($value,-6);
		//$arr_result= $this->find('all',array('conditions'=>array("consumer_no like "=>'%'.$last_data)))->toArray();
		$arr_condition = array("ApplyOnlines.consumer_no" => $value,"ApplyOnlines.discom"=>$this->data['ApplyOnlines']['discom']);
		if(isset($this->data['ApplyOnlines']['id']) && !empty($this->data['ApplyOnlines']['id']))
		{
			$arr_condition = array("ApplyOnlines.consumer_no" => $value,'ApplyOnlines.id != '=>$this->data['ApplyOnlines']['id'],"ApplyOnlines.discom"=>$this->data['ApplyOnlines']['discom']);
		}
		$arr_result= $this->find('all',array('conditions'   => $arr_condition,
											'fields'        => array('installers.installer_name','ApplyOnlines.created'),
											'join'          => [['table'=>'installers','type'=>'inner','conditions'=>'ApplyOnlines.installer_id = installers.id']]
											))->first();
		
		if(!empty($arr_result))
		{
			return 'Application against the Consumer no. is already generated by '.$arr_result->installers['installer_name'].' on '.date(LIST_DATE_FORMAT,strtotime($arr_result->created)).'.';
		}
		else
		{
			return true;
		}
	}
	/**
	* custom_mobile_check
	* Behaviour : public
	* @defination : Method is used to return false when consumer mobile and installer mobile are same.
	*/
	public function custom_mobile_check($value, $context) {
		return false;
	}
	/**
	 * SendSMSActivationCode
	 * Behaviour : Public
	 * @defination :  Method for send otp msg to consumer mobile .
	 */
	public function SendSMSActivationCode($application_id,$mobile,$message,$type='')
	{
		$getapply_online = $this->find('all',array('conditions'=>array('id'=>$application_id)))->first();
		if(isset($getapply_online) && !empty($getapply_online) && !empty($mobile))
		{
			$x = 4; // Amount of digits
			$min = pow(10,$x);
			$max = (pow(10,$x+1)-1);
			$activation_code    = rand($min, $max);

			$this->updateAll(
				array("otp" => $activation_code,'otp_created_date' => $this->NOW(),'modified'=>$this->NOW()),
				array("id" => $application_id)
			);
			$message = str_replace('##ACTIVATION_CODE##',$activation_code,$message);
			$this->sendSMS($application_id,$mobile,$message,$type);
		}
	}
	public function GetDiscomCode($area)
	{
		$discom         = TableRegistry::get('DiscomMaster');
		$arrDiscom      = $discom->find("all",['conditions'=>array('id'=>$area)])->first();
		$disp_discom    = '';
		if(!empty($arrDiscom))
		{
			$disp_discom= strtoupper(substr($arrDiscom->title,0,2));
		}
		return $disp_discom;
	}
	public function GetInstallerCode($installer_id)
	{
		return 'INS';
	}
	public function GenerateGedaApplicationNo($application) {
		$DiscomCode     = $this->GetDiscomCode($application->area);
		$Category_text  = 'KUS';//strtoupper(substr($application->parameter_cats['para_value'],0,3));
		$strGov        	= "";
		//$InstallerCode  = $this->GetInstallerCode($application->installer_id);
		/*if($application->govt_agency==1)
		{
			$strGov     = "/GOV";
		}*/

		$id             = $application->id;
		$g_id           = $DiscomCode."/".$Category_text.$strGov."/1".str_pad($id,7, "0", STR_PAD_LEFT);

		return $g_id;
	}
	public function torent_number_unique($value, $context)
	{
		//$last_data = substr($value,-6);
		//$arr_result= $this->find('all',array('conditions'=>array("consumer_no like "=>'%'.$last_data)))->toArray();
		$arr_condition = array("tno" => $value);
		if(isset($this->data['ApplyOnlines']['id']) && !empty($this->data['ApplyOnlines']['id']))
		{
			$arr_condition = array("tno" => $value,'id != '=>$this->data['ApplyOnlines']['id']);
		}
		$arr_result= $this->find('all',array('conditions'=>$arr_condition))->toArray();
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
	* custom_consumer_block
	* Behaviour : public
	* Parameter : discom
	* @defination : Method is used to check consumer number in block list or available.
	*/
	public function custom_consumer_block($value, $context)
	{
		//$last_data = substr($value,-6);
		//$arr_result= $this->find('all',array('conditions'=>array("consumer_no like "=>'%'.$last_data)))->toArray();
		if(isset($this->data['ApplyOnlines']['category']) && !empty($this->data['ApplyOnlines']['category']))
		{
		return $this->check_consumer_block($value,$this->data['ApplyOnlines']['discom'],$this->data['ApplyOnlines']['category']);
		}
		return true;
	}
	/**
	* custom_installer_category
	* Behaviour : public
	* Parameter : 
	* @defination : Method is used to check pv_capacity slot available or not.
	*/
	public function custom_installer_category($value, $context)
	{

		if(isset($this->data['ApplyOnlines']['installer_id']) && !empty($this->data['ApplyOnlines']['installer_id']))
		{
			$arr_condition      = array("installer_id" => $this->data['ApplyOnlines']['installer_id']);
			$InstallerList      = TableRegistry::get('InstallerCategoryMapping');
			$arr_result         = $InstallerList->find('all',array('conditions'=>$arr_condition))->first();
		}
		$flag_valid             = 0;
		if(!empty($arr_result))
		{
			$arr_assign_band    = json_decode($arr_result['allowed_bands']);
			foreach($arr_assign_band as $value_band)
			{
				if($value>=$this->installer_slot_array[$value_band]['min'] && $value<=$this->installer_slot_array[$value_band]['max'])
				{
					$flag_valid = 1;

				}
			}
			if($flag_valid==1)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}            
	public function getDiscomDetails($circle,$division,$subdivision,$area,$return_array=0)             
	{
		$DiscomMaster           = TableRegistry::get('discom_master');
		$arr_discoms            = array();
		$str_output             = '-';
		if(!empty($area)){
			$disDetails         = $DiscomMaster->find('all',array('conditions'=>array('id'=>$area)))->first();
			if(!empty($disDetails))
			{
				$arr_discoms[]  = $disDetails->title;
			}
		}
		if(!empty($circle))
		{
			$disDetails         = $DiscomMaster->find('all',array('conditions'=>array('id'=>$circle)))->first();
			if(!empty($disDetails))
			{
				$arr_discoms[]  = $disDetails->title;
			}  
		}
		if(!empty($division))
		{
			$disDetails         = $DiscomMaster->find('all',array('conditions'=>array('id'=>$division)))->first();
			if(!empty($disDetails))
			{
				$arr_discoms[]  = $disDetails->title;
			}
		}
		if(!empty($subdivision))
		{
			$disDetails         = $DiscomMaster->find('all',array('conditions'=>array('id'=>$subdivision)))->first();
			if(!empty($disDetails))
			{
				$arr_discoms[]  = $disDetails->title;
			}
		}

		if(!empty($arr_discoms))
		{
			$str_output         = implode(" / ", $arr_discoms);
		}
		if($return_array == 0) {
			return $str_output;
		} else {
			return $arr_discoms;
		}
	}
	/**
	* custom_consumer_mobile
	* Behaviour : public
	* Parameter : discom
	* @defination : Method is used to check consumer number use 3 times available.
	*/
	public function custom_consumer_mobile($value, $context)
	{
		if(!in_array($value,$this->mobile_excluded))
		{
			$arr_condition      = array("consumer_mobile" => $value);
			if(isset($this->data['ApplyOnlines']['id']) && !empty($this->data['ApplyOnlines']['id']))
			{
				$arr_condition  = array("consumer_mobile" => $value,'id != '=>$this->data['ApplyOnlines']['id']);
			}
			$arr_result         = $this->find('all',array('conditions'=>$arr_condition))->toArray();
			if(count($arr_result)>=3)
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		else
		{
			return true;
		}
	}
	
	

	public function saveErrorLog($application_id,$appliction_errors)
	{
		$apply_onlines_errors_log           = TableRegistry::get('ApplyOnlinesErrorsLog');
		$apply_onlines_errors_log_entity    = $apply_onlines_errors_log->newEntity();
		$apply_onlines_errors_log_entity->application_id    = $application_id;
		$apply_onlines_errors_log_entity->error_data        = json_encode($appliction_errors);
		$apply_onlines_errors_log_entity->created           = $this->NOW();
		$apply_onlines_errors_log->save($apply_onlines_errors_log_entity);
	}
	
	public function assign_slot_array($allowed_bands)
	{
		$assign_slots_f             = array();
		if(!empty($allowed_bands))
		{
			$arr_assign_band        = json_decode($allowed_bands);
			foreach($arr_assign_band as $value_band)
			{
				$assign_slots_f[]   = intval($this->installer_slot_array[$value_band]['min']).' - '.intval($this->installer_slot_array[$value_band]['max']).' kW';
			}
		}
		return $assign_slots_f;
	}
	/*
	 * Send Application Letter To Customer
	 * @param mixed What page to display
	 * @return void
	 */
	public function SendEmailToCustomer($id=0,$message_id=0)
	{
		$this->autoRender           = false;
		$applyOnlinesData           = $this->viewApplication($id);
		$applyOnlinesData->aid      = $this->GenerateApplicationNo($applyOnlinesData);
		$ApplyonlineMessage         = TableRegistry::get('ApplyonlineMessage');
		$GetLastMessage             = $ApplyonlineMessage->GetLastMessageByApplication($id,$message_id);
		$LETTER_APPLICATION_NO      = $applyOnlinesData->aid;
		$CUSTOMER_EMAIL             = $applyOnlinesData->email;
		$CUSTOMER_NAME              = trim($applyOnlinesData->customer_name_prefixed." ".$applyOnlinesData->name_of_consumer_applicant);
		$APPLICATION_DATE           = date("d.m.Y",strtotime($applyOnlinesData->created));
		$MESSAGE_BY                 = isset($GetLastMessage['comment_by'])?$GetLastMessage['comment_by']:"";
		$MESSAGE                    = isset($GetLastMessage['message'])?$GetLastMessage['message']:"";
		$EmailVars                  = array("LETTER_APPLICATION_NO"=>$LETTER_APPLICATION_NO,
											"APPLICATION_DATE"=>$APPLICATION_DATE,
											"MESSAGE"=>$MESSAGE,
											"MESSAGE_BY"=>$MESSAGE_BY,
											"CUSTOMER_NAME"=>$CUSTOMER_NAME);
		$to         = !empty($CUSTOMER_EMAIL)?$CUSTOMER_EMAIL:"";
		$email      = new Email('default');
		$subject    = "[REG: Application No. ".$LETTER_APPLICATION_NO."] Clarification required in the submitted document";
		$email->profile('default');
		$email->viewVars($EmailVars);
		if(!empty($to)) {
			$message_send = $email->template('email_template_for_communication', 'default')
				->emailFormat('html')
				->from(array(FROM_ACTIVATION_EMAIL =>PRODUCT_NAME))
				->to($to)
				->subject(Configure::read('EMAIL_ENV').$subject)
				->send();
		}
		if(!empty($applyOnlinesData->installer_email))
		{
			$email          = new Email('default');
			$email->profile('default');
			$email->viewVars($EmailVars);
			$message_send   = $email->template('email_template_for_communication', 'default')
					->emailFormat('html')
					->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
					->to($applyOnlinesData->installer_email)
					->subject(Configure::read('EMAIL_ENV').$subject)
					->send();
		}
		return true;
	}
	public function file_upload($path, $file,$is_resize = false,$width="", $hight="",$resize_path='',$prefix_file = '',$access_type='',$login_id)
	{
		$Couchdb 		= TableRegistry::get('Couchdb');
		$customerId 	= $login_id;
		$ext 			= substr(strtolower(strrchr($file['name'], '.')), 1);
		$file_name 		= $prefix_file.date('Ymdhis').rand();
		$file_location 	= WWW_ROOT.$path.$file_name.'.'.$ext;
		move_uploaded_file($file['tmp_name'],$file_location);
		if($is_resize && !empty($width) && !empty($hight) && !empty($file_location)){
			@$resize_path = WWW_ROOT.$resize_path.'r_'.$file_name.'.'.$ext;
			@$this->Image->prepare($file_location);
			@$this->Image->resize($width,$hight);//width,height,Red,Green,Blue
			@$this->Image->save($resize_path);
		}
		$passFileName 	= $file_name.'.'.$ext;
		$couchdbId 		= $Couchdb->saveData($path,$file_location,$prefix_file,$passFileName,$customerId,$access_type);
		return $file_name.'.'.$ext;
	}
	/**
	 * getDataapplyonline
	 * Behaviour : Public
	 * @param 		: $arrRequest - requires for pass post form data, $arrSessionInfo - requires for pass login person information.
	 * @defination : Method is use to save data for apply online kusum
	 */
	public function getDataapplyonline($arrRequest,$arrSessionInfo) 
	{
		//,$customer_id = '',$member_id ='',$state="",$fromDate = '',$toDate = '',$main_branch_id = '',$application_status = '',$installer_id = '',$consumer_no='',$application_search_no='',$installer_name='',$discom_name='',$payment_status='',$order_by_form="ApplyOnlines.modified|DESC",$disclaimer_subsidy='',$pcr_code='',$msme='',$msmeonly='',$category='',$inspection_status='',$geda_letter_status='',$geda_approved_status='',$receipt_no='',$is_enhancement=''
		$order_by_form="ApplyOnlinesKusum.modified|DESC";
		$apply_approval_table   = TableRegistry::get('ApplyOnlineApprovals');
		
		$fields             = ['ApplyOnlinesKusum.id',
								'ApplyOnlinesKusum.customer_id',
								'ApplyOnlinesKusum.installer_id',
								'ApplyOnlinesKusum.pv_capacity',
								'ApplyOnlinesKusum.application_type_name',
								'ApplyOnlinesKusum.area',
								'ApplyOnlinesKusum.circle',
								'ApplyOnlinesKusum.division',
								'ApplyOnlinesKusum.subdivision',
								'ApplyOnlinesKusum.application_no',
								'ApplyOnlinesKusum.application_status',
								'ApplyOnlinesKusum.payment_status',
								'ApplyOnlinesKusum.created',
								'ApplyOnlinesKusum.modified',
								'installer_data.installer_name',
								'installer_created_data.installer_name',
							  ];
		
		$arrOrderBy     = explode("|",$order_by_form);
		if(isset($arrSessionInfo['customer_id']) && !empty($arrSessionInfo['customer_id'])){
			
			$customer_id 	= $arrSessionInfo['customer_id'];
			$condition_arr  = array('ApplyOnlinesKusum.id !='=>0);
			/* */
			$join_arr = [['table'=>'installers','alias'=>'installer_data','type'=>'left','conditions'=>'installer_data.id = ApplyOnlinesKusum.installer_id'],
						['table'=>'installers','alias'=>'installer_created_data','type'=>'left','conditions'=>'installer_created_data.id = ApplyOnlinesKusum.installer_created_id']	
								
							];
			$str_group_by = '1';

			
			$condition_arr     = ['OR'=>['ApplyOnlinesKusum.customer_id'=>$customer_id,'ApplyOnlinesKusum.created_by'=>$customer_id]];
			
		
			
			if(isset($arrRequest['application_search_no']) && !empty($arrRequest['application_search_no'])) {
				array_push($condition_arr,array('ApplyOnlinesKusum.application_no like '=>'%'.$application_search_no.'%'));
			}
			if(!empty($installer_name)){
				array_push($condition_arr,array('ApplyOnlinesKusum.installer_id in '=>$installer_name));
			}
			if(!empty($discom_name)){
				array_push($condition_arr,array('ApplyOnlinesKusum.discom'=>$discom_name));
			}
			
			/*$arrContains[]=[
				'Installers' => function ($q) {
					return $q->autoFields(false)->select(['id','installer_name']);
				}
			];
	        $arrContains[]=[
	        	'Installers1'=> function ($q) {
					return $q->autoFields(false)->select(['id','installer_name']);
				}
			];foreach($arrContains as $contain) {
					$ApplyOnlinesList = $ApplyOnlinesList->contain($contain);
			}*/
		
			$ApplyOnlinesList   = $this->find("all",[
				'fields' => $fields,
				'join'   => $join_arr,
				'conditions'=>$condition_arr,
				'order'=>[$arrOrderBy[0]=>$arrOrderBy[1],'ApplyOnlinesKusum.created'=>$arrOrderBy[1]]]);
		

			if(isset($fromDate) && isset($toDate) && !empty($fromDate) && !empty($toDate)){
				$StartTime  = date("Y-m-d",strtotime(str_replace('/', '-',$fromDate)))." 00:00:00";
				$EndTime    = date("Y-m-d",strtotime(str_replace('/', '-', $toDate)))." 23:59:59";
				$ApplyOnlinesList->bind(':start',$StartTime,'date')->bind(':end',$EndTime,'date')->count();
			}
			$ApplyOnlinesPVCapacity = $this->find('all',[
				'join'   => $join_arr,
				'fields'    => array('TotalCapacityData'=>'sum(ApplyOnlinesKusum.pv_capacity)'),
				'conditions'=> $condition_arr]);

			/*if(isset($fromDate) && isset($toDate) && !empty($fromDate) && !empty($toDate)){
				$StartTime  = date("Y-m-d",strtotime(str_replace('/', '-',$fromDate)))." 00:00:00";
				$EndTime    = date("Y-m-d",strtotime(str_replace('/', '-', $toDate)))." 23:59:59";
				$ApplyOnlinesPVCapacity->bind(':start',$StartTime,'date')->bind(':end',$EndTime,'date')->count();
			}*/
			$PVCapacityTotal=$ApplyOnlinesPVCapacity->first();

			$arrResult['list']              = $ApplyOnlinesList;
			$arrResult['TotalCapacityData'] = $PVCapacityTotal->TotalCapacityData;
			return $arrResult;
		} else if(isset($arrSessionInfo['member_id']) && !empty($arrSessionInfo['member_id'])  && isset($arrSessionInfo['main_branch_id']) && !empty($arrSessionInfo['main_branch_id'])) {
		
		   $join_arr = [   
							['table'=>'states','type'=>'left','conditions'=>'states.id = ApplyOnlines.apply_state'],
							['table'=>'installers','alias'=>'installer','type'=>'left','conditions'=>'installer.id = ApplyOnlines.installer_id'],
							['table'=>'customers','type'=>'left','conditions'=>'customers.id = ApplyOnlines.customer_id']
						];
			$str_group_by = '1';
			$apply_approval_table   = TableRegistry::get('ApplyOnlineApprovals');
			$condition_arr = array();
			if(empty($application_status)){
				if(isset($fromDate) && isset($toDate) && !empty($fromDate) && !empty($toDate)){
					$condition_arr = ['ApplyOnlines.'.$main_branch_id['field']=>$main_branch_id['id'],
									'states.id' => $state,
									'ApplyOnlines.created BETWEEN :start AND :end','or'=>['ApplyOnlines.application_status != '=>'0','ApplyOnlines.application_status is not null'],
									'ApplyOnlines.application_status not in '=>array($apply_approval_table->APPLICATION_GENERATE_OTP,$apply_approval_table->WAITING_LIST,$apply_approval_table->APPLICATION_PENDING,$apply_approval_table->APPLICATION_CANCELLED,$apply_approval_table->APPLICATION_SUBMITTED,0)];
				} else {
					$condition_arr = [  'ApplyOnlines.'.$main_branch_id['field']=>$main_branch_id['id'],
										'states.id' => $state,
										'or'=>['ApplyOnlines.application_status != '=>'0','ApplyOnlines.application_status is not null'],
										'ApplyOnlines.application_status not in '=>array($apply_approval_table->APPLICATION_GENERATE_OTP,$apply_approval_table->WAITING_LIST,$apply_approval_table->APPLICATION_PENDING,$apply_approval_table->APPLICATION_CANCELLED,$apply_approval_table->APPLICATION_SUBMITTED,0)];

				}
			} else {
				if(isset($fromDate) && isset($toDate) && !empty($fromDate) && !empty($toDate)){
					$condition_arr = [  'ApplyOnlines.'.$main_branch_id['field']=>$main_branch_id['id'],'states.id' => $state,"(select created FROM apply_online_approvals WHERE apply_online_approvals.application_id = ApplyOnlines.id AND apply_online_approvals.stage = '".$apply_online_approvals->APPROVED_FROM_GEDA."' group by stage) BETWEEN :start AND :end"];
				} else {
					$condition_arr = [  'ApplyOnlines.'.$main_branch_id['field']=>$main_branch_id['id'],
										'states.id' => $state];
				}
				if(!empty($application_status))
				{
					$passStatus = $this->apply_online_status_key[$application_status];   
					if($passStatus == '9999')
					{
						array_push($condition_arr,array('ApplyOnlines.application_status'=>$apply_approval_table->APPROVED_FROM_GEDA));
					}
					elseif($passStatus == '6002' || $passStatus == '0')
					{
						$mesages_table   = TableRegistry::get('ApplyonlineMessages');
						$user_type       = array($passStatus);

						if($passStatus == '6002')
						{
							array_push($user_type,'6001');
							array_push($user_type,'6003');
						}
						$FindApplicationIDs     = $mesages_table->find('list',['keyField'=>'id','valueField'=>'application_id','conditions'=>['user_type in '=>$user_type,'AND' => [['message not like'=>'Reopen by %'], ['message not like'=>'Reset by %']]]])->toArray();
						if (!empty($FindApplicationIDs)) {
							array_push($condition_arr,array('ApplyOnlines.id IN ' => array_unique($FindApplicationIDs)));
							array_push($condition_arr,array('ApplyOnlines.application_status != ' => $apply_approval_table->APPLICATION_CANCELLED));
						} else {
							array_push($condition_arr,array('ApplyOnlines.id' => 0 ));
						}
						
					}
					else
					{
						$apply_approval_table   = TableRegistry::get('ApplyOnlineApprovals');
					
						array_push($join_arr, ['table'=>'apply_online_approvals','alias'=>'apply_online_approvals','type'=>'INNER','conditions'=>'ApplyOnlines.id = apply_online_approvals.application_id']);
						array_push($condition_arr,array('apply_online_approvals.stage' => $this->apply_online_status_key[$application_status]));
						if($passStatus != $apply_approval_table->APPLICATION_CANCELLED)
						{
							array_push($condition_arr,array('ApplyOnlines.application_status != ' => $apply_approval_table->APPLICATION_CANCELLED));
						}
					}
			   
				}
				else
				{
					array_push($condition_arr,array('ApplyOnlines.application_status'=>$application_status));
				}
			}
			if(!empty($consumer_no)) {
				array_push($condition_arr,array('ApplyOnlines.consumer_no like '=>'%'.$consumer_no.'%'));
			}
			if(!empty($application_search_no)) {
				array_push($condition_arr,array('ApplyOnlines.application_no like '=>'%'.$application_search_no.'%'));
			}
			if(!empty($installer_name)){
				array_push($condition_arr,array('ApplyOnlines.installer_id in '=>$installer_name));
			}
			if(!empty($discom_name)){
				array_push($condition_arr,array('ApplyOnlines.discom'=>$discom_name));
			}
			if($payment_status!=''){
				array_push($condition_arr,array('fea.payment_approve'=>$payment_status));
			}
			if($disclaimer_subsidy!='') {
				array_push($condition_arr,array('ApplyOnlines.disclaimer_subsidy'=>$disclaimer_subsidy));
			}
			if($pcr_code=='1')
			{
				array_push($condition_arr,array('ApplyOnlines.pcr_code IS NOT NULL'));
			}
			else if($pcr_code=='0')
			{
				array_push($condition_arr,array('ApplyOnlines.pcr_code IS NULL'));
			}
			else if($pcr_code=='2')
			{
				array_push($condition_arr,array('ApplyOnlines.pcr_submited IS NULL'));
				array_push($condition_arr,array('ApplyOnlines.pcr_code IS NOT NULL'));
			}
			else if($pcr_code=='3')
			{
				array_push($condition_arr,array('ApplyOnlines.pcr_submited IS NOT NULL'));
				array_push($condition_arr,array('ApplyOnlines.pcr_code IS NOT NULL'));
			}
			
			if($msme !='')
			{
				if($msme == 1) {
					array_push($condition_arr,array('apply_onlines_others.contract_load_more'=>$msme,'apply_onlines_others.msme'=>$msme));
				} else {
					array_push($condition_arr,array('OR'=>array('apply_onlines_others.contract_load_more'=>$msme,'apply_onlines_others.contract_load_more IS NULL')));
				}
			}
			if($msmeonly !='')
			{
				if($msmeonly == 1) {
					array_push($condition_arr,array('apply_onlines_others.msme'=>$msmeonly));
				} else {
					array_push($condition_arr,array('OR'=>array('apply_onlines_others.msme'=>$msmeonly,'apply_onlines_others.msme IS NULL')));
				}
			}
			if(isset($category[0]) && !empty($category[0]))
			{
				array_push($condition_arr,array('ApplyOnlines.category in'=>$category));
			}
			if($inspection_status =='1')
			{
				array_push($condition_arr,array('inspdf.inspection_id IS NOT NULL'));
			}
			elseif($inspection_status =='0')
			{
				array_push($condition_arr,array('inspdf.inspection_id IS NULL'));
			}
			if($is_enhancement =='1')
			{
				array_push($condition_arr,array('apply_onlines_others.is_enhancement'=>'1'));
			}
			elseif($is_enhancement =='0')
			{
				array_push($condition_arr,array('apply_onlines_others.is_enhancement !='=>'1'));
			}
			array_push($condition_arr,array('OR'=>['ApplyOnlines.payment_status'=>'1','ApplyOnlines.category'=>'3001']));
			array_push($condition_arr,array('OR'=>['project.project_social_consumer !='=>'1','project.project_disclaimer_subsidy'=>'1']));
			if(!empty($receipt_no))
			{
				array_push($join_arr,['table'=>'payumoney','alias'=>'payumoney','type'=>'left','conditions'=>'payumoney.application_id = ApplyOnlines.id']);
				array_push($condition_arr,array('payumoney.payment_status' => 'success','payumoney.receipt_no like ' => '%'.$receipt_no.'%' ));

			}
			
			$ApplyOnlinesList   = $this->find("all",[
				'fields'=>$fields,
				'join'=>$join_arr,
				'conditions'=>$condition_arr,
				'order'=>[$arrOrderBy[0]=>$arrOrderBy[1],'ApplyOnlines.created'=>$arrOrderBy[1]]]);
			if(isset($fromDate) && isset($toDate) && !empty($fromDate) && !empty($toDate)){
					$StartTime  = date("Y-m-d",strtotime(str_replace('/', '-',$fromDate)))." 00:00:00";
					$EndTime    = date("Y-m-d",strtotime(str_replace('/', '-', $toDate)))." 23:59:59";
				$ApplyOnlinesList->bind(':start', $StartTime, 'date')->bind(':end',   $EndTime, 'date')->count();
			}
			$ApplyOnlinesPVCapacity = $this->find('all',[
				'join'   => $join_arr,
				'fields'    => array('TotalCapacityData'=>'sum(ApplyOnlines.pv_capacity)'),
				'conditions'=> $condition_arr]);
			
			if(isset($fromDate) && isset($toDate) && !empty($fromDate) && !empty($toDate)){
				$StartTime  = date("Y-m-d",strtotime(str_replace('/', '-',$fromDate)))." 00:00:00";
				$EndTime    = date("Y-m-d",strtotime(str_replace('/', '-', $toDate)))." 23:59:59";
				$ApplyOnlinesPVCapacity->bind(':start',$StartTime,'date')->bind(':end',$EndTime,'date')->count();
			}
			$PVCapacityTotal=$ApplyOnlinesPVCapacity->first();
			$arrResult['list']              = $ApplyOnlinesList;
			$arrResult['TotalCapacityData'] = $PVCapacityTotal->TotalCapacityData;
			return $arrResult;
		} else if(isset($arrSessionInfo['member_id']) && !empty($arrSessionInfo['member_id'])) {
			
			$join_arr = [['table'=>'installers','alias'=>'installer_data','type'=>'left','conditions'=>'installer_data.id = ApplyOnlinesKusum.installer_id'],
						['table'=>'installers','alias'=>'installer_created_data','type'=>'left','conditions'=>'installer_created_data.id = ApplyOnlinesKusum.installer_created_id'],
						['table'=>'customers','type'=>'left','conditions'=>'customers.id = ApplyOnlinesKusum.customer_id']
								
							];
			
			$str_group_by = '1';
			$condition_arr = array();
			$apply_approval_table   = TableRegistry::get('ApplyOnlineKusumApprovals');

			if(empty($application_status)) {
				if(isset($fromDate) && isset($toDate) && !empty($fromDate) && !empty($toDate)){
					$condition_arr = ['states.id' => $state,"(select created FROM apply_online_kusum_approvals WHERE apply_online_kusum_approvals.application_id = ApplyOnlinesKusum.id AND apply_online_kusum_approvals.stage = '".$apply_approval_table->APPROVED_FROM_GEDA."' group by stage) BETWEEN :start AND :end",'or'=>['ApplyOnlinesKusum.application_status != '=>'0','ApplyOnlinesKusum.application_status is not null'],'ApplyOnlinesKusum.application_status not in '=>array($apply_approval_table->APPLICATION_GENERATE_OTP,$apply_approval_table->WAITING_LIST,$apply_approval_table->APPLICATION_PENDING,$apply_approval_table->APPLICATION_CANCELLED,0)];
				} else {
					$condition_arr = ['or'=>['ApplyOnlinesKusum.application_status != '=>'0','ApplyOnlinesKusum.application_status is not null'],'ApplyOnlinesKusum.application_status not in '=>array($apply_approval_table->APPLICATION_GENERATE_OTP,$apply_approval_table->WAITING_LIST,$apply_approval_table->APPLICATION_PENDING,$apply_approval_table->APPLICATION_CANCELLED,0)];
				}
			} else {
				if(isset($fromDate) && isset($toDate) && !empty($fromDate) && !empty($toDate)){
					$condition_arr = ["(select created FROM apply_online_kusum_approvals WHERE apply_online_kusum_approvals.application_id = ApplyOnlinesKusum.id AND apply_online_kusum_approvals.stage = '".$apply_approval_table->APPROVED_FROM_GEDA."' group by stage) BETWEEN :start AND :end"];
				} else {
					$condition_arr = [];
				}
				if(!empty($application_status))
				{
					$passStatus = $this->apply_online_status_key[$application_status];   
					if($passStatus == '9999')
					{
						array_push($condition_arr,array('ApplyOnlinesKusum.application_status'=>$apply_approval_table->APPROVED_FROM_GEDA));
					}
					elseif($passStatus == '6002' || $passStatus == '0')
					{
						$mesages_table   = TableRegistry::get('ApplyonlineMessages');
						$user_type       = array($passStatus);

						if($passStatus == '6002')
						{
							array_push($user_type,'6001');
							array_push($user_type,'6003');
						}
						$FindApplicationIDs     = $mesages_table->find('list',['keyField'=>'id','valueField'=>'application_id','conditions'=>['user_type in '=>$user_type,'AND' => [['message not like'=>'Reopen by %'], ['message not like'=>'Reset by %']]]])->toArray();
						if (!empty($FindApplicationIDs)) {
							array_push($condition_arr,array('ApplyOnlinesKusum.id IN ' => array_unique($FindApplicationIDs)));
							array_push($condition_arr,array('ApplyOnlinesKusum.application_status != ' => $apply_approval_table->APPLICATION_CANCELLED));
						} else {
							array_push($condition_arr,array('ApplyOnlinesKusum.id' => 0 ));
						}
					  
					}
					else
					{
						$apply_approval_table   = TableRegistry::get('ApplyOnlineApprovals');
						
						array_push($join_arr, ['table'=>'apply_online_kusum_approvals','alias'=>'apply_online_kusum_approvals','type'=>'INNER','conditions'=>'ApplyOnlinesKusum.id = apply_online_kusum_approvals.application_id']);
						array_push($condition_arr,array('apply_online_kusum_approvals.stage' => $this->apply_online_status_key[$application_status]));
						if($passStatus != $apply_approval_table->APPLICATION_CANCELLED)
						{
							array_push($condition_arr,array('ApplyOnlinesKusum.application_status != ' => $apply_approval_table->APPLICATION_CANCELLED));
						}
					}
				}
				else
				{
					array_push($condition_arr,array('ApplyOnlinesKusum.application_status'=>$application_status));
				}
			}

			
			$ApplyOnlinesList   = $this->find("all",[
				'fields'=>$fields,
				'join'=>$join_arr,
				'conditions'=>$condition_arr,
				'order'=>[$arrOrderBy[0]=>$arrOrderBy[1],'ApplyOnlinesKusum.created'=>$arrOrderBy[1]]]);
			if(isset($fromDate) && isset($toDate) && !empty($fromDate) && !empty($toDate)){
					$StartTime  = date("Y-m-d",strtotime(str_replace('/', '-',$fromDate)))." 00:00:00";
					$EndTime    = date("Y-m-d",strtotime(str_replace('/', '-', $toDate)))." 23:59:59";
					$ApplyOnlinesList->bind(':start', $StartTime, 'date')->bind(':end',   $EndTime, 'date')->count();
			}
			$ApplyOnlinesPVCapacity = $this->find('all',[
				'join'   => $join_arr,
				'fields'    => array('TotalCapacityData'=>'sum(ApplyOnlinesKusum.pv_capacity)'),
				'conditions'=> $condition_arr]);
			
			if(isset($fromDate) && isset($toDate) && !empty($fromDate) && !empty($toDate)){
				$StartTime  = date("Y-m-d",strtotime(str_replace('/', '-',$fromDate)))." 00:00:00";
				$EndTime    = date("Y-m-d",strtotime(str_replace('/', '-', $toDate)))." 23:59:59";
				$ApplyOnlinesPVCapacity->bind(':start',$StartTime,'date')->bind(':end',$EndTime,'date')->count();
			}
			$PVCapacityTotal=$ApplyOnlinesPVCapacity->first();
			$arrResult['list']              = $ApplyOnlinesList;
			$arrResult['TotalCapacityData'] = $PVCapacityTotal->TotalCapacityData;
			return $arrResult;
		}
	}
	public function uploadDocument($arrRequest,$arrSessionInfo=array(),$type='',$Filetitle=''){
		$ApplyonlineKusumDocs 		= TableRegistry::get('ApplyonlineKusumDocs');
		$Couchdb 					= TableRegistry::get('Couchdb');
		$ApplyOnlineKusumApprovals 	= TableRegistry::get('ApplyOnlineKusumApprovals');
		$ApplyonlinDocsEntity 		= $ApplyonlineKusumDocs->newEntity();
		
		if(isset($arrRequest['id']) && isset($arrRequest['file']['name']) && !empty($arrRequest['file']['name']))
		{			
			$applyOnlinesData 		= $this->find('all',array('fields'=>array('id','area'),'conditions'=>array('id'=>$arrRequest['id'])))->first();
			$prefix_file 			= '';
			$name 					= $arrRequest['file']['name'];
			$ext 					= substr(strtolower(strrchr($name, '.')), 1);
			$file_name 				= $prefix_file.date('Ymdhms').rand();

			$uploadPath 			= APPLYONLINE_KUSUM_PATH.$arrRequest['id'].'/';
			if(!file_exists(APPLYONLINE_KUSUM_PATH.$arrRequest['id'])) {
				@mkdir(APPLYONLINE_KUSUM_PATH.$arrRequest['id'], 0777);
			}
			$login_id 				= isset($arrSessionInfo['login_id']) && !empty($arrSessionInfo['login_id']) ? $arrSessionInfo['login_id'] : 0;
			
			$file_location 			= WWW_ROOT.$uploadPath.'doc'.'_'.$file_name.'.'.$ext;

			if(move_uploaded_file($arrRequest['file']['tmp_name'],$file_location))
			{
				$couchType 			= 'others';
				if($type == 'Signed_Doc') {
					$couchType 		= 'Signed_Doc_Kusum';
				}
				$couchdbId 			= $Couchdb->saveData($uploadPath,$file_location,$prefix_file,'doc_'.$file_name.'.'.$ext,$login_id,$couchType);
				$ApplyonlinDocsEntity->couchdb_id		= $couchdbId;
				$ApplyonlinDocsEntity->application_id	= $arrRequest['id'];
				$ApplyonlinDocsEntity->file_name        = 'doc'.'_'.$file_name.'.'.$ext;
				$ApplyonlinDocsEntity->doc_type         = $type;
				$ApplyonlinDocsEntity->title            = empty($Filetitle) ? 'Upload_Document' : $Filetitle;
				$ApplyonlinDocsEntity->created          = $this->NOW();
				
				if($type == 'Signed_Doc') {
					$application_status = $ApplyOnlineKusumApprovals->APPLICATION_SUBMITTED;
					$this->updateAll(array('application_status'=>$application_status),array('id'=>$arrRequest['id']));
					$customer_id 		= $login_id;
					$ApplyOnlineKusumApprovals->saveStatus($arrRequest['id'],$ApplyOnlineKusumApprovals->APPLICATION_SUBMITTED,$customer_id,'');
					
					$application_status = $ApplyOnlineKusumApprovals->APPROVED_FROM_GEDA;
					$this->updateAll(array('application_status'=>$application_status),array('id'=>$arrRequest['id']));
					$ApplyOnlineKusumApprovals->saveStatus($arrRequest['id'],$ApplyOnlineKusumApprovals->APPROVED_FROM_GEDA,$customer_id,'');
					$geda_application_no= $this->GenerateGedaApplicationNo($applyOnlinesData);
					$this->updateAll(array('geda_application_no'=>$geda_application_no),array('id'=>$applyOnlinesData->id));
				}	
			}
		
			if($ApplyonlineKusumDocs->save($ApplyonlinDocsEntity)) {
				//$this->SendApplicationLetterToCustomer($arrRequest['id']);
				return 1;
			} 
		}

		return 0;
	}
	public function viewApplication($id)
	{
		$Fields 	= $this->schema()->columns();
		$Fields[] = 'installers.installer_name';
		$Fields[] = 'installers_created.installer_name';
		/*$applyOnlinesData 	= $this->find('all')->fields([,'installers.installer_name'])->join([
                              
            ])->where(['ApplyOnlinesKusum.id'=>$id])->first();
		*/
		$applyOnlinesData  = $this->find('all',array('conditions'=>array('ApplyOnlinesKusum.id'=>$id),
													'fields'	=> $Fields,
													'join' 		=> ['installers'=>  [
														'table'      => 'installers',
														'type'       => 'LEFT',
														'conditions' => 'installers.id = ApplyOnlinesKusum.installer_id',
													],
													'installers_created'=>  [
														'table'      => 'installers',
														'type'       => 'LEFT',
														'conditions' => 'installers_created.id = ApplyOnlinesKusum.installer_created_id',
													]]))->first();

		
		return $applyOnlinesData;
		$fields = ['ApplyOnlines.id',
			'ApplyOnlines.customer_id',
			'ApplyOnlines.installer_id',
			'ApplyOnlines.disclaimer',
			'ApplyOnlines.customer_name_prefixed',
			'ApplyOnlines.customer_name',
			'name_of_consumer_applicant'=> "CONCAT(ApplyOnlines.name_of_consumer_applicant, ' ', ApplyOnlines.last_name, ' ', ApplyOnlines.third_name)",
			'ApplyOnlines.last_name',
			'ApplyOnlines.third_name',
			'ApplyOnlines.address1',
			'ApplyOnlines.address2',
			'ApplyOnlines.comunication_address',
			'ApplyOnlines.comunication_address_as_above',
			'ApplyOnlines.city',
			'ApplyOnlines.state',
			'ApplyOnlines.pincode',
			'ApplyOnlines.mobile',
			'ApplyOnlines.landline_no',
			'ApplyOnlines.aadhar_no_or_pan_card_no',
			'ApplyOnlines.attach_photo_scan_of_aadhar',
			'ApplyOnlines.attach_pan_card_scan',
			'ApplyOnlines.pan_card_no',
			'ApplyOnlines.sanction_load_contract_demand',
			'ApplyOnlines.category',
			'ApplyOnlines.attach_recent_bill',
			'ApplyOnlines.house_tax_holding_no',
			'ApplyOnlines.attach_latest_receipt',
			'ApplyOnlines.acknowledgement_tax_pay',
			'ApplyOnlines.pv_capacity',
			'ApplyOnlines.tod_billing_system',
			'ApplyOnlines.avail_accelerated_depreciation_benefits',
			'ApplyOnlines.payment_gateway',
			'ApplyOnlines.disCom_application_fee',
			'ApplyOnlines.jreda_processing_fee',
			'ApplyOnlines.roof_of_proposed',
			'ApplyOnlines.email',
			'ApplyOnlines.discom_name',
			'ApplyOnlines.discom',
			'ApplyOnlines.bank_ac_no',
			'ApplyOnlines.bank_name',
			'ApplyOnlines.ifsc_code',
			'ApplyOnlines.consumer_no',
			'ApplyOnlines.application_status',
			'ApplyOnlines.created',
			'ApplyOnlines.area',
			'ApplyOnlines.circle',
			'ApplyOnlines.division',
			'ApplyOnlines.subdivision',
			'ApplyOnlines.section',
			'ApplyOnlines.project_id',
			'ApplyOnlines.apply_state',
			'customers.name',
			'customers.email',
			'installer.installer_name',
			'installer.jreda_work_order',
			'installer.jreda_nib_no',
			'parameter_cats.para_value',
			'ApplyOnlines.payment_status',
			'ApplyOnlines.payment_mode',
			'ApplyOnlines.energy_con',
			'ApplyOnlines.bill',
			'ApplyOnlines.social_consumer',
			'ApplyOnlines.application_no',
			'ApplyOnlines.transmission_line',
			'ApplyOnlines.consumer_email',
			'ApplyOnlines.consumer_mobile',
			'ApplyOnlines.installer_email',
			'ApplyOnlines.installer_mobile',
			'ApplyOnlines.geda_application_no',
			'ApplyOnlines.disclaimer_subsidy',
			'ApplyOnlines.modified',
			'ApplyOnlines.location_proposed',
			'ApplyOnlines.net_meter',
			'ApplyOnlines.district',
			'ApplyOnlines.govt_agency',
			'ApplyOnlines.owned_rented',
			'ApplyOnlines.capexmode',
			'ApplyOnlines.gstno',
			'ApplyOnlines.pcr_code',
			'ApplyOnlines.approval_id',
			'ApplyOnlines.common_meter',
			'ApplyOnlines.tno',
			'apply_onlines_others.jir_unique_code',
			'apply_onlines_others.is_enhancement',
			'apply_onlines_others.existing_capacity',
			'apply_onlines_others.msme',
			'installer.designation',
			];
			$this->layout       = 'frontend';
			$applyOnlinesData   = $this->find('all',[
				'fields'=>$fields,
				'join'=>[   
							['table'=>'installers','alias'=>'installer','type'=>'left','conditions'=>'installer.id = ApplyOnlines.installer_id'],
							['table'=>'customers','type'=>'left','conditions'=>'customers.id = ApplyOnlines.customer_id'],
							['table'=>'parameters','alias'=>'parameter_cats','type'=>'left','conditions'=>'parameter_cats.para_id = ApplyOnlines.category'],
							['table'=>'apply_onlines_others','alias'=>'apply_onlines_others','type'=>'left','conditions'=>'apply_onlines_others.application_id = ApplyOnlines.id']
						],
				'conditions'=>['ApplyOnlines.id'=>$id]
				])->last();
			return $applyOnlinesData;
	}
	/**
	 * getDistrictName
	 * Behaviour : public
	 * @param : $distirct id
	 * @defination : Method is use to fetch district name
	 */
	public function getDistrictName($distirct)             
	{
		$DistrictMaster 	= TableRegistry::get('DistrictMaster');
		$district_name	 	= '';
		if(!empty($distirct)){
			$disDetails         = $DistrictMaster->find('all',array('conditions'=>array('id'=>$distirct)))->first();
			if(!empty($disDetails))
			{
				$district_name  = $disDetails->name;
			}
		}
		return $district_name;
	}
	/**
	 * generateApplicationPdf
	 * Behaviour : public
	 * @param : id  : Id is use to identify for which site PDF file should be downlaoded, $isdownload=true
	 * @defination : Method is use to download .pdf file from modal popup of applyonline listing
	 *
	 */
	public function generateApplicationPdf($id,$isdownload=true,$mobile=false)
	{
		$ApplyOnlineKusumApprovals 	= TableRegistry::get('ApplyOnlineKusumApprovals');
		$ApplyonlineKusumDocs 		= TableRegistry::get('ApplyonlineKusumDocs');
		$Couchdb 					= TableRegistry::get('Couchdb');
		$DiscomMaster 				= TableRegistry::get('DiscomMaster');
		$BranchMasters 				= TableRegistry::get('BranchMasters');
		$Payumoney 					= TableRegistry::get('Payumoney');
		$Parameters 				= TableRegistry::get('Parameters');
		$KusumMembers 				= TableRegistry::get('KusumMembers');

		if(empty($id)) {
			return 0;
		} else {
			$encode_id 					= $id;
			$id 						= intval(decode($id));
			$applyOnlinesData 			= $this->viewApplication($id);
			$applyOnlinesData->aid 		= $this->GenerateApplicationNo($applyOnlinesData);
			$LETTER_APPLICATION_NO 		= $applyOnlinesData->aid;
			$APPLICATION_DATE 			= date("d.m.Y",strtotime($applyOnlinesData->created));
			$applyOnlinesDataDocList 	= $ApplyonlineKusumDocs->find("all",['conditions'=>['application_id'=>$id,'doc_type'=>'others']])->toArray();
			$Applyonlinprofile  		= $ApplyonlineKusumDocs->find('all',['conditions'=>['application_id'=>$id,'doc_type'=>'profile']])->first();
			$divison_list = "";
			if(!empty($applyOnlinesData->division)){
				$divison_list = $DiscomMaster->find('all',['conditions'=>['id'=>$applyOnlinesData->division]])->first();
			}
		}
		$discom_list = $BranchMasters->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['BranchMasters.parent_id'=>'0']])->toArray();
		$payumoney_data = $Payumoney->find('all',['fields'=>array('Payumoney.transaction_id','Payumoney.payment_date'),'join'=>[
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
		$applyOnlinesOthersData 	= array();
		
		
		
		$documentProfile	=  '';
			
		$ParametersDetails	= $Parameters->find('all',array('conditions'=>array('para_id'=>$applyOnlinesData->applicant_type_kusum)))->first();
		$arrMembers 		= $KusumMembers->find('all',array('conditions'=>array('application_id'=>$id)))->toArray();
		
		$submitedStage 		= $ApplyOnlineKusumApprovals->getsubmittedStageData($id);

		$view 				= new View;
		$view->layout		= false;
		$view->set("APPLY_ONLINE_MAIN_STATUS",$ApplyOnlineKusumApprovals->apply_online_main_status);
		$view->set("pageTitle","Apply-online View");
		$view->set("applyOnlinesDataDocList",$applyOnlinesDataDocList);
		
		$view->set("discom_list",$discom_list);
		$view->set('ApplyOnlines',$applyOnlinesData);
		$view->set('transaction_id',$transaction_id);
		$view->set('LETTER_APPLICATION_NO',$LETTER_APPLICATION_NO);
		$view->set('APPLICATION_DATE',$APPLICATION_DATE);
		$view->set('payment_date',$payment_date);
		$view->set('Applyonlinprofile',$Applyonlinprofile);
		$view->set("APPLY_ONLINE_GUJ_STATUS",$ApplyOnlineKusumApprovals->apply_online_guj_status);
		$view->set("divison_list",$divison_list);
		$view->set('applyOnlinesOthersData',$applyOnlinesOthersData);
		$view->set('submitedStage',$submitedStage);
		$view->set('Couchdb',$Couchdb);
		$view->set('documentProfile',$documentProfile);
		$view->set('ParametersDetails',$ParametersDetails);
		$view->set('ApplyOnlinesTable',$this);
		$view->set('arrMembers',$arrMembers);

		/* Generate PDF for estimation of project */

		require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';

		//$options = new Options();
		//$options->set('enable_html5_parser', true);
		//$options['enable_html5_parser'] = true;
		$dompdf = new Dompdf($options = array());
		
		//$dompdf = new Dompdf($options = array());
		$dompdf->set_option("isPhpEnabled", true);
		//$dompdf->set_option("enable_html5_parser", true);

		$view->set('dompdf',$dompdf);
		$dompdf->set_base_path(SITE_ROOT_DIR_PATH.'/pdf/');
		$html = $view->render('/Element/applyonline_kusum');

		$dompdf->loadHtml($html,'UTF-8');

		$dompdf->setPaper('A4', 'portrait');

		@$dompdf->render();

		// Output the generated PDF to Browser
		if($isdownload){
			$dompdf->stream('applyonline-kusum-'.$LETTER_APPLICATION_NO);	
		}
		$output = $dompdf->output();
		if($mobile){
			header("Content-type:application/pdf");
			header("Content-Disposition:inline;filename='".$LETTER_APPLICATION_NO.".pdf'");
			echo $output;
			die;
		
		}		
	}
}
?>