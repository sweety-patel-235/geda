<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;
use Cake\Core\Configure;
use Cake\Network\Email\Email;
use Couchdb\Couchdb;
class ApplyOnlinesTable extends AppTable
{
	var $table = 'apply_onlines';
	var $data  = array();
	var $category_residental    = "3001";
	var $category_government    = "3004";
	var $category_industrial    = "3002";
	var $category_ht_indus      = "3006";
	var $category_commercial    = "3003";
	var $category_others        = "3005";
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
		$validator->notEmpty('disclaimer', 'Disclaimer can not be blank.');
		$validator->notEmpty('customer_name_prefixed', 'Prefix can not be blank.');
		$validator->notEmpty('area_unit', 'Area unit can not be blank.');
		$validator->notEmpty('sanctioned_load', 'Sanctioned load can not be blank.');
		$validator->notEmpty('sanctioned_load_unit', 'Sanctioned load unit can not be blank.');
		$validator->notEmpty('capacity', 'Capacity can not be blank.');
		$validator->notEmpty('name_of_consumer_applicant', 'First Name Of Consumer Applicant can not be blank.');
		//$validator->notEmpty('last_name', 'Last Name Of Consumer Applicant can not be blank.');
		$validator->notEmpty('customer_name', 'Customer name can not be blank.');
		$validator->notEmpty('address1', 'Address 1 can not be blank.');
		$validator->notEmpty('address2', 'Taluka can not be blank.');
		
		if (!isset($this->data['ApplyOnlines']['comunication_address_as_above']) || $this->data['ApplyOnlines']['comunication_address_as_above'] != 1) {
			$validator->notEmpty('comunication_address', 'Communication Address can not be blank.');
		}
		$validator->notEmpty('city', 'City can not be blank.');
		$validator->notEmpty('category', 'Category must be select.');
		$validator->notEmpty('state', 'State can not be blank.');
		$validator->notEmpty('pincode', 'Pincode can not be blank.');
	   
		$validator->notEmpty('discom', 'Discom can not be blank.');
		$validator->notEmpty('discom_name', 'Discom Name can not be blank.');
		$validator->notEmpty('consumer_no', 'Consumer No can not be blank.');
		
		$validator->notEmpty('sanction_load_contract_demand', 'Sanction load contract demand can not be blank.')
		 ->add('sanction_load_contract_demand', [
			'validFormat'=>[
				'rule' => array('custom', '/(^([0-9.]+)(\d+)?$)/'),
				'message' => 'Numbers only'
			]
		]);
		$validator->notEmpty('file_attach_recent_bill', 'Recent bill file required.');
		if(strlen($this->data['ApplyOnlines']['consumer_no']) < 5 && $this->data['ApplyOnlines']['discom'] != $this->torent_ahmedabad && $this->data['ApplyOnlines']['discom'] != $this->torent_surat)
		{
			$validator->add("consumer_no", [
				 "_empty_2" => [
				 "rule" => [$this, "customFunction_consumer"],
				 "message" => "Consumer No. must be greater than or equals to 5 digits."
					]
				 ]);
		}
		elseif(strlen($this->data['ApplyOnlines']['consumer_no']) < 1 && ($this->data['ApplyOnlines']['discom'] == $this->torent_ahmedabad || $this->data['ApplyOnlines']['discom'] == $this->torent_surat))
		{

			$validator->add("consumer_no", [
			"_empty_2" => [
				"rule" => [$this, "customFunction_consumer"],
				"message" => "Consumer No. must be greater than or equals to 1 digits."
					]
				]
		);
		}
		else{
			$validator->add("consumer_no", [
				"_empty_1" => [
				"rule" => [$this, "custom_consumer_unique"],
				"message" => "Consumer No. already exist."
				   ]
				]); 
		}
		$validator->add("consumer_no", [
			"_empty_3" => [
				"rule" => [$this, "custom_consumer_block"],
				"message" => "Consumer No. is currently exist in block list."
					]
				]);
		if($this->data['ApplyOnlines']['discom'] == $this->torent_ahmedabad || $this->data['ApplyOnlines']['discom'] == 
			$this->torent_surat)
		{
		$validator->notEmpty('tno','tno can not be blank.');
		$validator->add("tno", [
			"_empty_torrent_unique" => [
				"rule" => [$this, "torent_number_unique"],
				"message" => "Torent No. already exist."
					]
				]
		);
		}
		if($this->data['ApplyOnlines']['category'] != $this->category_residental  || (isset($this->data['ApplyOnlines']['social_consumer']) && $this->data['ApplyOnlines']['social_consumer']==1)){
			$check_capacity 	= $this->data['ApplyOnlines']['sanction_load_contract_demand']/2;
			$appliedCapacity 	= $this->data['ApplyOnlines']['pv_capacity'];
			$existingText		= '';
			if(isset($this->data['ApplyOnlines']['existingCapacity']) && !empty($this->data['ApplyOnlines']['existingCapacity'])) {
				$appliedCapacity= $appliedCapacity+$this->data['ApplyOnlines']['existingCapacity'];
				$existingText 	= ' + Existing Capacity ';
			}
			if($appliedCapacity > $check_capacity)
			{
				/*$validator->add("pv_capacity", [
					"_empty_pv_cat_capacity" => [
					"rule" => [$this, "customFunction_consumer"],
					"message" => "PV capacity ".$existingText."must be less than or equals to $check_capacity kW (Sanctioned /Contract Load)."
						]
					]
				);*/
			}
		}
		if($this->data['ApplyOnlines']['pv_capacity'] < 1)
		{
			$validator->add("pv_capacity", [
				"_empty_capacity" => [
				"rule" => [$this, "customFunction_consumer"],
				"message" => "PV capacity must be greater than or equals to 1."
					]
				]
			);
		}
		$ProjectTable       = TableRegistry::get('projects');
		$projectDetails     = $ProjectTable->get($this->data['project_pass_id']);
	   
		/*if(isset($this->data['ApplyOnlines']['category']) && $this->data['ApplyOnlines']['category'] != $projectDetails->customer_type) {
				$validator->add("category", [
				"_empty" => [
					"rule" => [$this, "custom_mobile_check"],
					"message" => "Category must be same as project."
				]
					]
			);
		}*/
		if((!isset($this->data['ApplyOnlines']['social_consumer']) || $this->data['ApplyOnlines']['social_consumer']=='0') && $this->data['ApplyOnlines']['category']!=$this->category_residental && NON_RES_SOCIAL_SECTOR==1)
		{
			
			$validator->add("social_consumer", [
				"_empty" => [
				"rule" => [$this, "custom_mobile_check"],
				"message" => "Please check social consumer."
					]
				]
		);
		}
		if(isset($this->data['ApplyOnlines']['installer_id']) && !empty($this->data['ApplyOnlines']['installer_id']))
		{
			$arr_condition      = array("installer_id" => $this->data['ApplyOnlines']['installer_id']);
			$InstallerList      = TableRegistry::get('InstallerCategoryMapping');
			$arr_result         = $InstallerList->find('all',array('conditions'=>$arr_condition))->first();
		}
		$flag_valid             = 0;

		$assign_slots           = array();
		if(!empty($arr_result))
		{
			$assign_slots = $this->assign_slot_array($arr_result['allowed_bands']);
		}
		// $msg_rule       = "No slots available.";
		// if(count($assign_slots)>0)
		// {
		//     $msg_rule   = "PV capacity must be between ".implode(" or ",$assign_slots)." slot.";
		// }
		// $validator->add("pv_capacity", [
		//         "_empty" => [
		//         "rule" => [$this, "custom_installer_category"],
		//         "message" => $msg_rule
		//             ]
		//         ]
		// );
		$validator->add("pv_capacity", [
				"_empty_validation_rule" => [
				"rule" => [$this, "custom_capacity_validation"],
				"message" => "PV capacity exeeding limit."
					]
				]
			);
		if(!empty($this->data['ApplyOnlines']['consumer_email']))
		{
			$validator->add("consumer_email", "validFormat", [
			"rule" => ["email", false],
			"message" => "Email must be valid."
			]);    
		}
		if(!empty($this->data['ApplyOnlines']['installer_email']))
		{
			$validator->add("installer_email", "validFormat", [
			"rule" => ["email", false],
			"message" => "Email must be valid."
			]);
		}
		$validator->notEmpty('consumer_mobile','Consumer mobile can not be blank.');
		$validator->notEmpty('installer_mobile','Installer mobile can not be blank.');

		if($this->data['ApplyOnlines']['consumer_mobile'] == $this->data['ApplyOnlines']['installer_mobile'])
		{
			$validator->add("consumer_mobile", [
				"_empty_same_mobile" => [
					"rule" => [$this, "custom_mobile_check"],
					"message" => "Consumer mobile can not be same as installer mobile."
				]
					]
			);
		}

		if(strlen($this->data['ApplyOnlines']['consumer_mobile']) != 10)
		{
			$validator->add("consumer_mobile", [
				 "_empty" => [
				 "rule" => [$this, "customFunction_consumer"],
				 "message" => "Consumer mobile must be 10 digits."
					]
			 ]);
		}
		if(strlen($this->data['ApplyOnlines']['installer_mobile']) != 10)
		{
			$validator->add("installer_mobile", [
				 "_empty" => [
				 "rule" => [$this, "customFunction_consumer"],
				 "message" => "Installer mobile must be 10 digits."
					]
			 ]);
		}
		if(strlen($this->data['ApplyOnlines']['pincode']) != 6)
		{
			$validator->add("pincode", [
				 "_empty" => [
				 "rule" => [$this, "customFunction_consumer"],
				 "message" => "Pincode must be 6 digits."
					]
			 ]);
		}
		$validator->notEmpty('capexmode', 'Disclaimer can not be blank.')
		->add('capexmode', [
		'validFormat'=>[
			'rule' => array('custom', '/^[1-9]+$/'),
			'message' => 'Please check capex mode'
		]
		]);

		$validator->notEmpty('house_tax_holding_no', 'Premises Ownership Details No can not be blank.');  
		$validator->notEmpty('file_attach_latest_receipt', 'Premises Ownership Details file required.');
	
		//$validator->notEmpty('third_name', 'Last Name Of Consumer Applicant can not be blank.');

		if(empty($this->data['ApplyOnlines']['transmission_line']))
		{
			$validator->notEmpty('transmission_line',"Please select phase of proposed solar inverter.");
		/*$validator->add("transmission_line", [
				"_empty_t_line" => [
					"rule" => [$this, "customFunction_consumer"],
					"message" => "Please select phase of proposed solar inverter."
						]
					]
			);*/
		}
		if(($this->data['ApplyOnlines']['net_meter']) == '0')
		{
		$validator->add("net_meter", [
				"_empty_net_meter" => [
					"rule" => [$this, "customFunction_consumer"],
					"message" => "Please select provider of net meter."
						]
					]
			);
		}
		if(isset($this->data['ApplyOnlines']['category']) && $this->data['ApplyOnlines']['category']!= $this->category_residental){
			//$validator->notEmpty('payment_gateway', 'Payment gateway can not be blank.');
		}

		if(isset($this->data['ApplyOnlines']['category']) && $this->data['ApplyOnlines']['category'] == $this->category_residental) {
			$validator->notEmpty('aadhar_no_or_pan_card_no', 'Aadhar no. can not be blank.');
			if(strlen($this->data['ApplyOnlines']['aadhar_no_or_pan_card_no']) < 12 || strlen($this->data['ApplyOnlines']['aadhar_no_or_pan_card_no']) > 12)
			{
				$validator->add("aadhar_no_or_pan_card_no", [
					 "_empty_photo_id_A" => [
					 "rule" => [$this, "customFunction_consumer"],
					 "message" => "Aadhar Card Number must be a 12 digits."
						]
				 ]);
			}
			if (!preg_match("/^[0-9]+$/i", $this->data['ApplyOnlines']['aadhar_no_or_pan_card_no']))
			{
				$validator->add("aadhar_no", [
						"_empty_photo_id_A" => [
							"rule" => [$this, "customFunction_consumer"],
							"message" => "Aadhar Card Number must be numbers only."
						]
					]
				);
			}
			if(!empty($this->data_entity) && empty($this->data_entity->attach_photo_scan_of_aadhar)){
			$validator->notEmpty('file_attach_photo_scan_of_aadhar', 'Aadhar card file required.');
			}
		} else {
			$validator->notEmpty('pan_card_no', 'PAN card no. can not be blank.');
			if(strlen($this->data['ApplyOnlines']['pan_card_no']) < 10 || strlen($this->data['ApplyOnlines']['pan_card_no']) > 10)
			{
				$validator->add("pan_card_no", [
					 "_empty_photo_id_P" => [
					 "rule" => [$this, "customFunction_consumer"],
					 "message" => "Pan Card Number must be a 10 digits."
						]
				 ]);
			}
			if(!empty($this->data_entity) && empty($this->data_entity->attach_pan_card_scan)){
			$validator->notEmpty('file_attach_pan_card_scan', 'PAN card file required.');
		}
		}
		$validator->notEmpty('pv_capacity', 'PV Capacity can not be blank.')
		 ->add('pv_capacity', [
			'validFormat'=>[
				'rule' => array('custom', '/^[0-9]+\.?[0-9]*$/'),
				'message' => 'Numbers only'
			]
		]);
		$validator->notEmpty('disCom_application_fee', 'DisCom application fee can not be blank.');
		$validator->add('test', [
			'validFormat'=>[
				'rule' => array('custom', '/^[1-9]+$/'),
				'message' => 'Approval all detail of application'
			]
		]);
		$validator->notEmpty('jreda_processing_fee', 'JREDA processing fee can not be blank.');
		$validator->notEmpty('disclaimer3', 'Disclaimer can not be blank.')
		->add('disclaimer3', [
			'validFormat'=>[
				'rule' => array('custom', '/^[1-9]+$/'),
				'message' => 'Disclaimer must be check'
			]
		]);
		$validator->add("email", "validFormat", [
			"rule" => ["email", false],
			"message" => "Email must be valid."
		]);
		if(!empty($this->data['ApplyOnlines']['gstno']))
		{
			if(strlen($this->data['gstno']) < 15 || strlen($this->data['gstno']) > 15)
			{
				$validator->add("gstno", [
					 "_empty_gstno" => [
					 "rule" => [$this, "customFunction_consumer"],
					 "message" => "GST Number must be a 15 digits."
						]
					 ]);
			}
		}
		return $validator;
	}

	/**
	 * Add validation rules.
	 *
	 * @param \Cake\Validation\Validator $validator Validator instance.
	 * @return \Cake\Validation\Validator
	 */
	public function validationTab1(Validator $validator)
	{   
		$validator->notEmpty('installer_id', 'Installer must be select.');
		$validator->notEmpty('disclaimer', 'Disclaimer can not be blank.')
		->add('disclaimer', [
			'validFormat'=>[
				'rule' => array('custom', '/^[1-9]+$/'),
				'message' => 'Disclaimer must be check'
			]
		]);
		return $validator;
	}
	/**
	 * Add validation rules.
	 *
	 * @param \Cake\Validation\Validator $validator Validator instance.
	 * @return \Cake\Validation\Validator
	 */
	public function validationTab2(Validator $validator)
	{   
		$validator->notEmpty('installer_id', 'Installer must be select.');
		$validator->notEmpty('disclaimer', 'Disclaimer can not be blank.');
		$validator->notEmpty('customer_name_prefixed', 'Prefix can not be blank.');
		$validator->notEmpty('area_unit', 'Area unit can not be blank.');
		$validator->notEmpty('sanctioned_load', 'Sanctioned load can not be blank.');
		$validator->notEmpty('sanctioned_load_unit', 'Sanctioned load unit can not be blank.');
		$validator->notEmpty('capacity', 'Capacity can not be blank.');
		$validator->notEmpty('name_of_consumer_applicant', 'First Name Of Consumer Applicant can not be blank.');
		//$validator->notEmpty('third_name', 'Last Name Of Consumer Applicant can not be blank.');
	   if(empty($this->data['ApplyOnlines']['transmission_line']))
		{
			$validator->notEmpty('transmission_line',"Please select phase of proposed solar inverter.");
		/*$validator->add("transmission_line", [
				"_empty_ts_line" => [
					"rule" => [$this, "customFunction_consumer"],
					"message" => "Please select phase of proposed solar inverter."
						]
					]
			);*/
		}
		if(($this->data['ApplyOnlines']['net_meter']) == '0')
		{
		$validator->add("net_meter", [
				"_empty_netmeter" => [
					"rule" => [$this, "customFunction_consumer"],
					"message" => "Please select provider of net meter."
						]
					]
			);
		}
		$validator->notEmpty('address1', 'Address 1 can not be blank.'); 
		$validator->notEmpty('address2', 'Taluka can not be blank.');
		if (!isset($this->data['ApplyOnlines']['comunication_address_as_above']) || $this->data['ApplyOnlines']['comunication_address_as_above'] != 1) {
			$validator->notEmpty('comunication_address', 'Communication Address can not be blank.');
		}
		$validator->notEmpty('city', 'City can not be blank.');
		$validator->notEmpty('category', 'Category must be select.');

		$validator->notEmpty('state', 'State can not be blank.');
		$validator->notEmpty('pincode', 'Pincode can not be blank.');
		$validator->notEmpty('mobile', 'Mobile can not be blank.');
		$validator->notEmpty('email', 'Email can not be blank.');
		$validator->notEmpty('discom', 'Discom can not be blank.');
		$validator->notEmpty('discom_name', 'Division can not be blank.');
		$flagValiationSanction 		= 1;
		$newSchemeApp 				= 0;
		if(isset($this->data['ApplyOnlines']['id'])) {
			$applyOnlineData 		= $this->find('all',array('fields'=>array('created'),'conditions'=>array('id'=>$this->data['ApplyOnlines']['id'])))->first();
			if(strtotime($applyOnlineData->created) >= strtotime(APPLICATION_NEW_SCHEME_DATE)) {
				$newSchemeApp 		= 1;
			}
		}
		$docsMandatory 				= 1;
		if(isset($this->data['ApplyOnlines']['govt_agency']) && $this->data['ApplyOnlines']['govt_agency'] == 1) {
			if(isset($applyOnlineData)) {
				if(strtotime($applyOnlineData->created) >= strtotime(GOVERMNET_AGENCY_DOCUMENTNOT)) {
					$docsMandatory 	= 0;
				}
			}
		}

		/*if(!isset($this->data['ApplyOnlines']['id']) || (isset($this->data['ApplyOnlines']['id']) && empty($this->data['ApplyOnlines']['id'])))
		{*/
			/*if(isset($this->data['ApplyOnlines']['category']) && $this->data['ApplyOnlines']['category'] == $this->category_residental) {
				$validator->add("category", [
					 "_empty" => [
					 "rule" => [$this, "customFunction_consumer"],
					 "message" => "Residential category not allowed."
						]
				 ]);
			}*/
		//}
		if($docsMandatory == 1) {
			if(isset($this->data['ApplyOnlines']['category']) && $this->data['ApplyOnlines']['category'] == $this->category_residental) {
				$validator->notEmpty('aadhar_no_or_pan_card_no', 'Aadhar no. can not be blank.');
				if(strlen($this->data['ApplyOnlines']['aadhar_no_or_pan_card_no']) < 12 || strlen($this->data['ApplyOnlines']['aadhar_no_or_pan_card_no']) > 12)
				{
					$validator->add("aadhar_no_or_pan_card_no", [
						 "_empty_photo_id_AA" => [
						 "rule" => [$this, "customFunction_consumer"],
						 "message" => "Aadhar Card Number must be a 12 digits."
							]
					 ]);
				}
				if (!preg_match("/^[0-9]+$/i", $this->data['ApplyOnlines']['aadhar_no_or_pan_card_no']))
				{
					$validator->add("aadhar_no", [
							"_empty_photo_id_AA" => [
								"rule" => [$this, "customFunction_consumer"],
								"message" => "Numbers only."
							]
						]
					);
				}
				if(!empty($this->data_entity) && empty($this->data_entity->attach_photo_scan_of_aadhar)){
				$validator->notEmpty('file_attach_photo_scan_of_aadhar', 'Aadhar card file required.');
				}
			} else {
				$validator->notEmpty('pan_card_no', 'PAN card no. can not be blank.');
				if(strlen($this->data['ApplyOnlines']['pan_card_no']) < 10 || strlen($this->data['ApplyOnlines']['pan_card_no']) > 10)
				{
					$validator->add("pan_card_no", [
						 "_empty_photo_id_PP" => [
						 "rule" => [$this, "customFunction_consumer"],
						 "message" => "Pan Card Number must be a 10 digits."
							]
					 ]);
				}
				if(!empty($this->data_entity) && empty($this->data_entity->attach_pan_card_scan)){
				$validator->notEmpty('file_attach_pan_card_scan', 'PAN card file required.');
				}
			}
		}
		$validator->notEmpty('sanction_load_contract_demand', 'Sanction load contract demand can not be blank.')
		 ->add('sanction_load_contract_demand', [
			'validFormat'=>[
				'rule' => array('custom', '/(^([0-9.]+)(\d+)?$)/'),
				'message' => 'Numbers only'
			]
		]);
		if($docsMandatory == 1)
		{
			if(!empty($this->data_entity) && empty($this->data_entity->attach_recent_bill)){
				$validator->notEmpty('file_attach_recent_bill', 'Recent bill file required.');
			}
			if(!empty($this->data_entity) && empty($this->data_entity->attach_latest_receipt)) {
				//$validator->notEmpty('file_attach_latest_receipt', 'Latest receipt file required.');
			}
			if(!empty($this->data_entity) && empty($this->data_entity->profile_image_id)) {
				$validator->notEmpty('profile_image', 'Profile Image file required.');
			}
		}
			
		
        if(isset($this->data['ApplyOnlines']['map_installer_id'])) {
        	if(empty($this->data['ApplyOnlines']['map_installer_id'])) {
	        		$validator->add('map_installer_id', [
					'_empty'=>[
						'rule' => [$this, "customFunction_consumer"],
						'message' => 'Please select Installer'
					]
				]);
        	}

	       	if(!empty($this->data_entity) && empty($this->data_entity->file_agreement_customer['name'])) {
				$validator->notEmpty('agreement_customer', 'Agreement between Customer file required.');
			}
			if(!empty($this->data_entity) && empty($this->data_entity->file_ppa_doc['name'])) {
				$validator->notEmpty('ppa_doc', 'PPA Document file required.');
			}
        }
		$validator->notEmpty('pv_capacity', 'PV Capacity can not be blank.')
		 ->add('pv_capacity', [
			'validFormat'=>[
				'rule' => array('custom', '/^[0-9]+\.?[0-9]*$/'),
				'message' => 'Numbers only'
			]
		]);
		
		$validator->add("consumer_no", [
			"_empty_consumer_block" => [
				"rule" => [$this, "custom_consumer_block"],
				"message" => "Consumer No. is currently exist in block list."
					]
				]);
		if(strlen($this->data['ApplyOnlines']['consumer_no']) < 5 && $this->data['ApplyOnlines']['discom'] != $this->torent_ahmedabad && $this->data['ApplyOnlines']['discom'] != $this->torent_surat)
		{

			$validator->add("consumer_no", [
			"_empty_format_error" => [
				"rule" => [$this, "customFunction_consumer"],
				"message" => "Consumer No. must be greater than or equals to 5 digits."
					]
				]
		);
		}
		elseif(strlen($this->data['ApplyOnlines']['consumer_no']) < 1 && ($this->data['ApplyOnlines']['discom'] == $this->torent_ahmedabad || $this->data['ApplyOnlines']['discom'] == $this->torent_surat))
		{

			$validator->add("consumer_no", [
			"_empty_format_error" => [
				"rule" => [$this, "customFunction_consumer"],
				"message" => "Consumer No. must be greater than or equals to 1 digits."
					]
				]
		);
		}
		else
		{
			if(!isset($this->data['isEnhancement']) || $this->data['isEnhancement'] != 1)
			{
				$validator->add("consumer_no", [
				"_empty_consumer_exists" => [
					"rule" => [$this, "custom_consumer_unique"],
					"message" => "Consumer No. already exist."
						]
					]
				);
			}
		}
				
		if($this->data['ApplyOnlines']['discom'] == $this->torent_ahmedabad || $this->data['ApplyOnlines']['discom'] == $this->torent_surat){
		$validator->notEmpty('tno','tno can not be blank.');
			if(!isset($this->data['isEnhancement']) || $this->data['isEnhancement'] != 1)
			{
				$validator->add("tno", [
					"_empty_tno" => [
						"rule" => [$this, "torent_number_unique"],
						"message" => "Torent No. already exist."
							]
						]
				);
			}
		}
		
		if(isset($this->data['ApplyOnlines']['msme']) && $this->data['ApplyOnlines']['msme'] == 1 ) {
			if($this->data['ApplyOnlines']['contract_load_more'] == '' && $newSchemeApp == 0) {
				$validator->notEmpty('contract_load_more', 'Please check at-least one.');
			}
			if((isset($this->data['ApplyOnlines']['contract_load_more']) && $this->data['ApplyOnlines']['contract_load_more'] == 1 && $newSchemeApp == 0) || $newSchemeApp == 1) {
				if($this->data['ApplyOnlines']['msme_category'] == '') {
					$validator->notEmpty('msme_category', 'Please select at-least one.');
				}
				if(!empty($this->data_entity_others) && empty($this->data_entity_others->upload_certificate)){
					$validator->notEmpty('file_upload_certificate', 'Upload certificate file required.');
				}
				if($this->data['ApplyOnlines']['type_of_applicant'] == ''){
					$validator->notEmpty('type_of_applicant', 'Please select at-least one.');
				}
				
				if($this->data['ApplyOnlines']['msme_aadhaar_no'] == ''){
					$validator->notEmpty('msme_aadhaar_no', 'MSME Udhyog Aadhaar No. can not be blank.');
				}
				
				if($this->data['ApplyOnlines']['type_authority'] == ''){
					$validator->notEmpty('type_authority', 'Please select at-least one.');
				}
				if($this->data['ApplyOnlines']['name_authority'] == ''){
					$validator->notEmpty('name_authority', 'Name of Authority can not be blank.');
				}
			}
			if($this->data['ApplyOnlines']['type_of_applicant'] == 'Other' && $this->data['ApplyOnlines']['applicant_others'] == ''){
					$validator->notEmpty('applicant_others', 'Applicant Type Other can not be blank.');
			}
			/*if(!empty($this->data['ApplyOnlines']['msme_aadhaar_no']) && strlen($this->data['ApplyOnlines']['msme_aadhaar_no']) < 12 || strlen($this->data['ApplyOnlines']['msme_aadhaar_no']) > 12)
			{
				$validator->add("msme_aadhaar_no", [
					 "_empty" => [
					 "rule" => [$this, "customFunction_consumer"],
					 "message" => "Aadhar Card Number must be a 12 digits."
						]
				 ]);
			}*/
			if($this->data['ApplyOnlines']['contract_load_more'] == 1) {
				$flagValiationSanction 	= 0;
			}
			
		}
		
		if(isset($this->data['ApplyOnlines']['rpo_rec']) && $this->data['ApplyOnlines']['rpo_rec'] == '' ) {
			$validator->notEmpty('rpo_rec', 'Please check at-least one.');
		}
		if(isset($this->data['ApplyOnlines']['rpo_rec']) && $this->data['ApplyOnlines']['rpo_rec'] == 1 ) {
			$arrValidate 	= array('rpo_is_captive','rpo_is_obligation','gerc_is_distribution','rpo_is_cpp','rpo_is_captive_rpo','rpo_is_cert_getco');
			foreach($arrValidate as $Fval) {
				if(isset($this->data['ApplyOnlines'][$Fval]) && $this->data['ApplyOnlines'][$Fval] == '' ) {
					$validator->notEmpty($Fval, 'Please check at-least one.');
				}
			}
			if(!empty($this->data_entity_others) && empty($this->data_entity_others->gerc_certificate) && $this->data['ApplyOnlines']['gerc_is_distribution'] == 1){
				$validator->notEmpty('file_gerc_certificate', 'Certificate file is required.');
			}
			if(!empty($this->data_entity_others) && empty($this->data_entity_others->capacity_cpp) && $this->data['ApplyOnlines']['rpo_is_cpp'] == 1){
				$validator->notEmpty('capacity_cpp', 'Capacity is required.');
			}
			if(!empty($this->data_entity_others) && empty($this->data_entity_others->capacity_rpo_cert) && $this->data['ApplyOnlines']['rpo_is_cert_getco'] == 1){
				$validator->notEmpty('capacity_rpo_cert', 'Capacity is required.');
			}
		}
		if(isset($this->data['ApplyOnlines']['rpo_rec']) && $this->data['ApplyOnlines']['rpo_rec'] == 2 ) {
			$arrValidate 	= array('rec_is_registration','rec_is_receipt','rec_is_power_evaluation');
			if(isset($this->data['ApplyOnlines']['rec_is_allowed_sancation']) && empty($this->data['ApplyOnlines']['rec_is_allowed_sancation'])) {

				$validator->add("rec_is_allowed_sancation", [
					 "_empty" => [
					 "rule" => [$this, "customFunction_consumer"],
					 "message" => "Installation of Solar Project shall be allowed up to Sanctioned load/ Contract demand."
						]
				 ]);
				
			}
			if(isset($this->data['ApplyOnlines']['rec_is_valid_min_cap']) && empty($this->data['ApplyOnlines']['rec_is_valid_min_cap'])) {
				$validator->notEmpty('rec_is_valid_min_cap', 'Minimum Capacity of Solar Project shall be 250 kW.');
				$validator->add("rec_is_valid_min_cap", [
					 "_empty" => [
					 "rule" => [$this, "customFunction_consumer"],
					 "message" => "Minimum Capacity of Solar Project shall be 250 kW."
						]
				 ]);
			}
			
			foreach($arrValidate as $Fval) {
				if(isset($this->data['ApplyOnlines'][$Fval]) && $this->data['ApplyOnlines'][$Fval] == '' ) {
					$validator->notEmpty($Fval, 'Please check at-least one.');
				}
			}
			if(!empty($this->data_entity_others) && empty($this->data_entity_others->rec_registration_copy) && $this->data['ApplyOnlines']['rec_is_registration'] == 1){
				$validator->notEmpty('file_rec_registration_copy', 'Physical copy of application letter file required.');
			}
			if(!empty($this->data_entity_others) && empty($this->data_entity_others->rec_receipt_copy) && $this->data['ApplyOnlines']['rec_is_receipt'] == 1){
				$validator->notEmpty('file_rec_receipt_copy', 'Copy of receipt for application file required.');
			}
			if(!empty($this->data_entity_others) && empty($this->data_entity_others->rec_power_evaluation) && $this->data['ApplyOnlines']['rec_is_power_evaluation'] == 1){
				$validator->notEmpty('file_rec_power_evaluation', 'Power Evacuation Arrangement permission letter file required.');
			}
		}
		/*if(!empty($this->data_entity_others)){
				$validator->notEmpty('app_upload_undertaking', 'Undertaking file required.');
			}*/
		
		if(isset($this->data['ApplyOnlines']['category']) && !empty($this->data['ApplyOnlines']['category']))
		{
			//if(($this->data['ApplyOnlines']['category'] != $this->category_residental || (isset($this->data['ApplyOnlines']['social_consumer']) && $this->data['ApplyOnlines']['social_consumer']==1)) && (!isset($this->data['ApplyOnlines']['govt_agency']) || (isset($this->data['ApplyOnlines']['govt_agency']) && $this->data['ApplyOnlines']['govt_agency'] != 1)) && $flagValiationSanction==1 && $newSchemeApp == 0) 
			// || (isset($this->data['ApplyOnlines']['social_consumer']) && $this->data['ApplyOnlines']['social_consumer']==1) && $newSchemeApp == 0
			if(($this->data['ApplyOnlines']['category'] != $this->category_residental) && (!isset($this->data['ApplyOnlines']['govt_agency']) || (isset($this->data['ApplyOnlines']['govt_agency']) && $this->data['ApplyOnlines']['govt_agency'] != 1)) && $flagValiationSanction==1 && $newSchemeApp == 1) 
			{
				//$check_capacity 	= $this->data['ApplyOnlines']['sanction_load_contract_demand']/2;
				$check_capacity 	= $this->data['ApplyOnlines']['sanction_load_contract_demand'];
				$appliedCapacity 	= $this->data['ApplyOnlines']['pv_capacity'];
				$existingText		= '';
				if(isset($this->data['ApplyOnlines']['existingCapacity']) && !empty($this->data['ApplyOnlines']['existingCapacity'])) {
					$appliedCapacity= $appliedCapacity+$this->data['ApplyOnlines']['existingCapacity'];
					$existingText 	= ' + Existing Capacity ';
				}
				if($appliedCapacity > $check_capacity) //$appliedCapacity > $check_capacity //!=
				{
					//"message" => "PV capacity ".$existingText."must be less than or equals to $check_capacity."
					/*$validator->add("pv_capacity", [
						"_empty_pv_cat_capacity" => [
						"rule" => [$this, "customFunction_consumer"],
						"message" => "PV capacity ".$existingText." can be less than or equal to $check_capacity kW (Sanctioned /Contract Load)."
							]
						]
					);*/
				}
			}
		}
		$ProjectTable       = TableRegistry::get('projects');
		$projectDetails     = $ProjectTable->get($this->data['project_pass_id']);
	   
		/*if(isset($this->data['ApplyOnlines']['category']) && $this->data['ApplyOnlines']['category'] != $projectDetails->customer_type) {
				$validator->add("category", [
				"_empty" => [
					"rule" => [$this, "custom_mobile_check"],
					"message" => "Category must be same as project."
				]
					]
			);
		}
		*/
		if(isset($this->data['ApplyOnlines']['pv_dc_capacity']) && empty($this->data['ApplyOnlines']['pv_dc_capacity']))
		{
			$validator->notEmpty('pv_dc_capacity', 'Plant AC Capacity can not be blank.');   
		}
		if(isset($this->data['ApplyOnlines']['existing_ac_capacity']) && empty($this->data['ApplyOnlines']['existing_ac_capacity']))
		{
			$validator->notEmpty('existing_ac_capacity', 'Existing AC Capacity can not be blank.');   
		}
		if(IS_CAPTIVE_OPEN == 1)
		{
			// && empty($this->data['social_consumer'])
			if($newSchemeApp == 0) {
				if(($this->data['ApplyOnlines']['category'] == $this->category_industrial || $this->data['ApplyOnlines']['category'] == $this->category_ht_indus || $this->data['ApplyOnlines']['category'] == $this->category_commercial  || $this->data['ApplyOnlines']['category'] == $this->category_others))
				{
					
					if(isset($this->data['renewable_attr']) && $this->data['renewable_attr'] == '')
					{
						$validator->notEmpty('hi_renewable_attr', 'Please check at-least one.');
					}
				}
				//&& empty($this->data['social_consumer'])
				if(isset($this->data['renewable_attr']) && $this->data['renewable_attr'] == '0' && $this->data['renewable_rec'] == '')
				{
					$validator->notEmpty('hi_renewable_rec', 'Please check at-least one.');   
				}
				if($this->data['renewable_rec'] == '1' && $this->data['ApplyOnlines']['pv_capacity'] < 250)
				{
					$validator->add("pv_capacity", [
						"_empty" => [
						"rule" => [$this, "customFunction_consumer"],
						"message" => "PV capacity must be greater than or equals to 250."
							]
						]
					);
				}
			}
			
			/*if((!isset($this->data['ApplyOnlines']['social_consumer']) || $this->data['ApplyOnlines']['social_consumer']=='0') && $this->data['ApplyOnlines']['category']==$this->category_others && NON_RES_SOCIAL_SECTOR==1)
			{

				$validator->add("social_consumer", [
					"_empty" => [
					"rule" => [$this, "custom_mobile_check"],
					"message" => "Please check social consumer."
						]
					]
				);
			}*/
			
			if($this->data['ApplyOnlines']['pv_capacity'] > 5000)
			{
				$validator->add("pv_capacity", [
					"_empty" => [
					"rule" => [$this, "customFunction_consumer"],
					"message" => "PV capacity must be less than or equals to 5000."
						]
					]
				);
			}
		}
		else
		{
			if((!isset($this->data['ApplyOnlines']['social_consumer']) || $this->data['ApplyOnlines']['social_consumer']=='0') && $this->data['ApplyOnlines']['category']!=$this->category_residental && NON_RES_SOCIAL_SECTOR==1)
			{

				$validator->add("social_consumer", [
					"_empty" => [
					"rule" => [$this, "custom_mobile_check"],
					"message" => "Please check social consumer."
						]
					]
			);
			}
		}

		if($this->data['ApplyOnlines']['category']!=$this->category_residental)
		{
			$validator->notEmpty('consumer_email', 'Consumer email can not be blank.');
			if($docsMandatory == 1) {
				if(empty($this->data['file_company_incorporation']['name']) && empty($this->data['ApplyOnlines']['hi_file_company_incorporation'])){
				
					$validator->notEmpty('hi_file_company_incorporation', 'Company Incorporation / Registration Certificate or Partnership deed file required.');        
				}
				if(empty($this->data['file_board']['name']) && empty($this->data['ApplyOnlines']['hi_file_board'])){
					$validator->notEmpty('hi_file_board', 'Copy of Board resolution authorizing person for signing all the documents related to proposed project file required.');
				}
			}
		}
		
		/*$this->find('all',array(
			'conditions'=>  array('id'=>$this->data['ApplyOnlines']['id'])))->first();*/
		if(isset($this->data['ApplyOnlines']['installer_id']) && !empty($this->data['ApplyOnlines']['installer_id']))
		{
			$arr_condition      = array("installer_id" => $this->data['ApplyOnlines']['installer_id']);
			$InstallerList      = TableRegistry::get('InstallerCategoryMapping');
			$arr_result         = $InstallerList->find('all',array('conditions'=>$arr_condition))->first();
		}
		$flag_valid             = 0;
		$assign_slots           = array();
		if(!empty($arr_result))
		{
			$assign_slots = $this->assign_slot_array($arr_result['allowed_bands']);
			
		}
		$msg_rule       = "No slots available.";
		if(count($assign_slots)>0)
		{
			$msg_rule   = "PV capacity must be between ".implode(" or ",$assign_slots)." slot.";
		}

		$validator->add("pv_capacity", [
				"_empty_category_val" => [
				"rule" => [$this, "custom_installer_category"],
				"message" => $msg_rule
					]
				]
			);  
		$validator->add("pv_capacity", [
				"_empty_capacity_over" => [
				"rule" => [$this, "custom_capacity_validation"],
				"message" => "PV capacity exceeding limit."
					]
				]
			);
		if($this->data['ApplyOnlines']['pv_capacity'] < 1)
		{
			$validator->add("pv_capacity", [
				"_empty" => [
				"rule" => [$this, "customFunction_consumer"],
				"message" => "PV capacity must be greater than or equals to 1."
					]
				]
			);
		}
		if($this->data['ApplyOnlines']['pv_capacity'] > 1000)
		{
			$validator->add("pv_capacity", [
				"_empty" => [
				"rule" => [$this, "customFunction_consumer"],
				"message" => "PV capacity must be less than or equals to 1000."
					]
				]
			);
		}
		
		if(!empty($this->data['ApplyOnlines']['consumer_email']))
		{
		$validator->add("consumer_email", "validFormat", [
		"rule" => ["email", false],
		"message" => "Email must be valid."
		]);    
		}
		if(!empty($this->data['ApplyOnlines']['installer_email']))
		{
		$validator->add("installer_email", "validFormat", [
			"rule" => ["email", false],
			"message" => "Email must be valid."
			]);
		}
		$validator->add("consumer_mobile", [
			"_empty_consumer_mobile_limit" => [
				"rule" => [$this, "custom_consumer_mobile"],
				"message" => "Consumer mobile can not be use more than 3 times."
			]
				]
		);
				
		$validator->notEmpty('consumer_mobile','Consumer mobile can not be blank.');
		if(isset($this->data['ApplyOnlines']['map_installer_id'])) {
			$validator->notEmpty('installer_mobile','Developer mobile can not be blank.');
		} else {
			$validator->notEmpty('installer_mobile','Installer mobile can not be blank.');
		}

		if($this->data['ApplyOnlines']['consumer_mobile'] == $this->data['ApplyOnlines']['installer_mobile'])
		{
		$validator->add("consumer_mobile", [
			"_empty_consumer_mobile_duplicate" => [
				"rule" => [$this, "custom_mobile_check"],
				"message" => "Consumer mobile can not be same as installer mobile."
			]
				]
		);
		}
		if(strlen($this->data['ApplyOnlines']['consumer_mobile']) != 10)
		{
			$validator->add("consumer_mobile", [
				 "_empty" => [
				 "rule" => [$this, "customFunction_consumer"],
				 "message" => "Consumer mobile must be 10 digits."
					]
			 ]);
		}
		if(strlen($this->data['ApplyOnlines']['installer_mobile']) != 10)
		{
			$validator->add("installer_mobile", [
				 "_empty" => [
				 "rule" => [$this, "customFunction_consumer"],
				 "message" => "Installer mobile must be 10 digits."
					]
			 ]);
		}
		if(strlen($this->data['ApplyOnlines']['pincode']) != 6)
		{
			$validator->add("pincode", [
				 "_empty" => [
				 "rule" => [$this, "customFunction_consumer"],
				 "message" => "Pincode must be 6 digits."
					]
			 ]);
		}
		$validator->notEmpty('capexmode', 'Disclaimer can not be blank.')
	   ->add('capexmode', [
		'validFormat'=>[
		'rule' => array('custom', '/^[1-9]+$/'),
		'message' => 'Please check capex mode'
			]
		]);
		$textValidation 		= 'Premises Ownership Details No';
		$textValidationFile 	= 'Premises Ownership Details';
		if($docsMandatory == 0) {
			$textValidation 	= 'Work Order No';
			$textValidationFile = 'Work Order Document';
	   	}
		$validator->notEmpty('house_tax_holding_no', $textValidation.' can not be blank.');
		if(!empty($this->data_entity) && empty($this->data_entity->attach_latest_receipt))
		{
			$validator->notEmpty('file_attach_latest_receipt', $textValidationFile.' file required.');
		}	 
		$validator->notEmpty('energy_con', 'Average Monthly Units can not be blank.')
		 ->add('energy_con', [
			'validFormat'=>[
				'rule' => array('custom', '/(^([0-9]+)(\d+)?$)/'),
				'message' => 'Numbers only'
			]
		]); 
		$validator->notEmpty('bill', 'Bill can not be blank.')
		 ->add('bill', [
			'validFormat'=>[
				'rule' => array('custom', '/(^([0-9.]+)(\d+)?$)/'),
				'message' => 'Numbers only'
			]
		]);     
		 
		$validator->notEmpty('roof_of_proposed', 'Roof of proposed can not be blank.');
	   
		
		return $validator;
	}
	/**
	 * Add validation rules.
	 *
	 * @param \Cake\Validation\Validator $validator Validator instance.
	 * @return \Cake\Validation\Validator
	 */
	public function validationTab3(Validator $validator)
	{   
		if(isset($this->data['ApplyOnlines']['category']) && $this->data['ApplyOnlines']['category']!=$this->category_residental)
		{
		//$validator->notEmpty('payment_gateway', 'Payment gateway can not be blank.');
		}
		if(CAPTCHA_DISPLAY == 1) {			
			$validator->notEmpty('g-recaptcha-response', 'The captcha code field cannot be left empty');
		}
		$validator->notEmpty('disCom_application_fee', 'DisCom application fee can not be blank.');
		$validator->add('test', [
			'validFormat'=>[
				'rule' => array('custom', '/^[1-9]+$/'),
				'message' => 'Approval all detail of application'
			]
		]);
		$validator->notEmpty('test', 'DisCom application fee can not be blank.');
		$validator->notEmpty('jreda_processing_fee', 'JREDA processing fee can not be blank.');
		$validator->notEmpty('disclaimer3', 'Disclaimer can not be blank.')
		->add('disclaimer3', [
			'validFormat'=>[
				'rule' => array('custom', '/^[1-9]+$/'),
				'message' => 'Disclaimer must be check'
			]
		]);
		if(!empty($this->data['ApplyOnlines']['gstno']))
		{
			if(strlen($this->data['ApplyOnlines']['gstno']) < 15 || strlen($this->data['ApplyOnlines']['gstno']) > 15)
			{
				$validator->add("gstno", [
					 "_empty_gstno_2" => [
					 "rule" => [$this, "customFunction_consumer"],
					 "message" => "GST Number must be a 15 digits."
						]
					 ]);
			}
		}
		return $validator;
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

	public function applyonlineByStateCount($state = '',$fromDate='',$toDate = '',$main_branch_id = '') 
	{
		if(empty($fromDate) && empty($toDate)){
			if(empty($main_branch_id)){
				return $this->find('all',['join'=>[
						['table'=>'states',
						'type'=>'left',
						'conditions'=>'states.id = ApplyOnlines.state or states.statename = ApplyOnlines.state']
					],
				'conditions'=>['states.id' => $state]
				])->count();
			} else {
				if ($main_branch_id['member_type'] == $this->DISCOM)
				{
					return $this->find('all',['join'=>[
							['table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.state or states.statename = ApplyOnlines.state']
						],
						'conditions'=>['states.id' => $state,
						'ApplyOnlines.'.$main_branch_id['field']=>$main_branch_id['id']]
						])->count();
				} else {
					return $this->find('all',['join'=>[
							['table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.state or states.statename = ApplyOnlines.state']
						],
						'conditions'=>['states.id' => $state]
						])->count();
				}
			}
		} else {
			$StartTime  = date("Y-m-d",strtotime(str_replace('/', '-',$fromDate)))." 00:00:00";
			$EndTime    = date("Y-m-d",strtotime(str_replace('/', '-', $toDate)))." 23:59:59";
			if(empty($main_branch_id)){
			return $this->find('all',['join'=>[['table'=>'states','type'=>'left','conditions'=>'states.id = ApplyOnlines.state or states.statename = ApplyOnlines.state']],
				'conditions'=>['states.id' => $state,'ApplyOnlines.created BETWEEN :start AND :end']
				])->bind(':start', $StartTime, 'date')->bind(':end',   $EndTime, 'date')->count();
			} else {
				if ($main_branch_id['member_type'] == $this->DISCOM)
				{
					return $this->find('all',['join'=>[['table'=>'states','type'=>'left','conditions'=>'states.id = ApplyOnlines.state or states.statename = ApplyOnlines.state']],
						'conditions'=>['states.id' => $state,'ApplyOnlines.created BETWEEN :start AND :end','ApplyOnlines.'.$main_branch_id['field']=>$main_branch_id['id']]
						])->bind(':start', $StartTime, 'date')->bind(':end',   $EndTime, 'date')->count();
				} else {
					return $this->find('all',['join'=>[['table'=>'states','type'=>'left','conditions'=>'states.id = ApplyOnlines.state or states.statename = ApplyOnlines.state']],
						'conditions'=>['states.id' => $state,'ApplyOnlines.created BETWEEN :start AND :end']
						])->bind(':start', $StartTime, 'date')->bind(':end',   $EndTime, 'date')->count();
				}
			}
		}
	}

	public function getApplHistoryCurrentYear($state = '',$main_branch_id)
	{
		$ApplyOnlines = $this->find();
		$ApplyOnlines->hydrate(false);
		if ($main_branch_id['member_type'] == $this->DISCOM)
		{
			$ApplyOnlines->select(['Apply_Month' => 'MONTH(ApplyOnlines.created)','count' => $ApplyOnlines->func()->count('ApplyOnlines.id')])->group('Apply_Month')
			->join(['table'=>'states','type'=>'left','conditions'=>'states.id = ApplyOnlines.state or states.statename = ApplyOnlines.state'])
			->where(['states.id' => $state,'ApplyOnlines.'.$main_branch_id['field']=>$main_branch_id['id'],function ($exp,$q) {
				$StartTime  = date("Y",strtotime($this->NOW()))."-01-01 00:00:00";
				$EndTime    = date("Y",strtotime($this->NOW()))."-12-31 23:59:59";
				return $exp->between('ApplyOnlines.created', $StartTime, $EndTime);
			}]);
		} else {
			$ApplyOnlines->select(['Apply_Month' => 'MONTH(ApplyOnlines.created)','count' => $ApplyOnlines->func()->count('ApplyOnlines.id')])->group('Apply_Month')
			->join(['table'=>'states','type'=>'left','conditions'=>'states.id = ApplyOnlines.state or states.statename = ApplyOnlines.state'])
			->where(['states.id' => $state,function ($exp,$q) {
				$StartTime  = date("Y",strtotime($this->NOW()))."-01-01 00:00:00";
				$EndTime    = date("Y",strtotime($this->NOW()))."-12-31 23:59:59";
				return $exp->between('ApplyOnlines.created', $StartTime, $EndTime);
			}]);
		}
		$resultArray = $ApplyOnlines->toList();
		if (!empty($resultArray)) {
			$arrResult = array();
			foreach ($resultArray as $resultRow) {
				$MONTH              = strtoupper(date("M",strtotime(date("Y",strtotime($this->NOW()))."-".$resultRow['Apply_Month']."-01 00:00:00")));
				$arrResult[$MONTH]  = $resultRow['count'];
			}
			$resultArray = $arrResult;
		}else{
			$resultArray = array();
		}
		return $resultArray;
	}

	public function getApplyCapacityCurrentYear($state = '',$main_branch_id = '')
	{
		$ApplyOnlines = $this->find();
		$ApplyOnlines->hydrate(false);
		if ($main_branch_id['member_type'] == $this->DISCOM)
		{
			$ApplyOnlines->select(['Apply_Month' => 'MONTH(ApplyOnlines.created)','count' => $ApplyOnlines->func()->sum('ApplyOnlines.pv_capacity')])->group('Apply_Month')->join(['table'=>'states','type'=>'left','conditions'=>'states.id = ApplyOnlines.state or states.statename = ApplyOnlines.state'])
			->where(['states.id' => $state,'ApplyOnlines.'.$main_branch_id['field']=>$main_branch_id['id'],function ($exp,$q) {
				$StartTime  = date("Y",strtotime($this->NOW()))."-01-01 00:00:00";
				$EndTime    = date("Y",strtotime($this->NOW()))."-12-31 23:59:59";
				return $exp->between('ApplyOnlines.created', $StartTime, $EndTime);
			}]);
		} else {
			$ApplyOnlines->select(['Apply_Month' => 'MONTH(ApplyOnlines.created)','count' => $ApplyOnlines->func()->sum('ApplyOnlines.pv_capacity')])->group('Apply_Month')->join(['table'=>'states','type'=>'left','conditions'=>'states.id = ApplyOnlines.state or states.statename = ApplyOnlines.state'])
			->where(['states.id' => $state,function ($exp,$q) {
				$StartTime  = date("Y",strtotime($this->NOW()))."-01-01 00:00:00";
				$EndTime    = date("Y",strtotime($this->NOW()))."-12-31 23:59:59";
				return $exp->between('ApplyOnlines.created', $StartTime, $EndTime);
			}]);
		}
		$resultArray = $ApplyOnlines->toList();
		if(!empty($resultArray)) {
			$arrResult = array();
			foreach($resultArray as $resultRow) {
				$MONTH              = strtoupper(date("M",strtotime(date("Y",strtotime($this->NOW()))."-".$resultRow['Apply_Month']."-01 00:00:00")));
				$arrResult[$MONTH]  = $resultRow['count'];
			}
			$resultArray = $arrResult;
		} else {
			$resultArray = array();
		}
		return $resultArray;
	}

	public function getDataapplyonline($customer_id = '',$member_id ='',$state="",$fromDate = '',$toDate = '',$main_branch_id = '',$application_status = '',$installer_id = '',$consumer_no='',$application_search_no='',$installer_name='',$discom_name='',$payment_status='',$order_by_form="ApplyOnlines.modified|DESC",$disclaimer_subsidy='',$pcr_code='',$msme='',$msmeonly='',$category='',$inspection_status='',$geda_letter_status='',$geda_approved_status='',$receipt_no='',$is_enhancement='',$arrExtra=array()) 
	{
		if(isset($category[0]) && $category[0]=='3002,3003')
		{
			$category = explode(",",$category[0]);
		}

		$apply_approval_table   = TableRegistry::get('ApplyOnlineApprovals');
		$fields             = ['ApplyOnlines.id',
								'ApplyOnlines.customer_id',
								'ApplyOnlines.installer_id',
								'ApplyOnlines.disclaimer',
								'ApplyOnlines.customer_name_prefixed',
								'ApplyOnlines.customer_name',
								'name_of_consumer_applicant' => "CONCAT(ApplyOnlines.name_of_consumer_applicant, ' ', ApplyOnlines.last_name, ' ', ApplyOnlines.third_name)",
								'ApplyOnlines.address1',
								'ApplyOnlines.address2',
								'ApplyOnlines.comunication_address',
								'ApplyOnlines.city',
								'ApplyOnlines.state',
								'ApplyOnlines.pincode',
								'ApplyOnlines.mobile',
								'ApplyOnlines.landline_no',
								'ApplyOnlines.aadhar_no_or_pan_card_no',
								'ApplyOnlines.attach_photo_scan_of_aadhar',
								'ApplyOnlines.pan_card_no',
								'ApplyOnlines.attach_pan_card_scan',
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
								'ApplyOnlines.email',
								'ApplyOnlines.discom_name',
								'ApplyOnlines.consumer_no',
								'ApplyOnlines.roof_of_proposed',
								'ApplyOnlines.created',
								'ApplyOnlines.application_status',
								'ApplyOnlines.member_assign_id',
								'ApplyOnlines.area',
								'ApplyOnlines.circle',
								'ApplyOnlines.division',
								'ApplyOnlines.subdivision',
								'ApplyOnlines.section',
								'ApplyOnlines.payment_status',
								'ApplyOnlines.payment_mode',
								'ApplyOnlines.apply_state',
								'ApplyOnlines.application_no',
								'ApplyOnlines.project_id',
								'ApplyOnlines.geda_application_no',
								'ApplyOnlines.disclaimer_subsidy',
								'installer.installer_name',
								'project.name',
								'ApplyOnlines.query_sent',
								'ApplyOnlines.query_date',
								'ApplyOnlines.location_proposed',
								'ApplyOnlines.discom',
								'ApplyOnlines.net_meter',
								'ApplyOnlines.district',
								'ApplyOnlines.govt_agency',
								'ApplyOnlines.owned_rented',
								'ApplyOnlines.modified',
								'ApplyOnlines.capexmode',
								'ApplyOnlines.gstno',
								'ApplyOnlines.social_consumer',
								'ApplyOnlines.pcr_code',
								'ApplyOnlines.common_meter',
								'apply_onlines_others.jir_unique_code',
								'apply_onlines_others.is_enhancement',
								'apply_onlines_others.existing_capacity',
								'apply_onlines_others.msme',
								'apply_onlines_others.e_invoice_url',
								'apply_onlines_others.map_installer_id',
								'apply_onlines_others.upload_undertaking',
								'apply_onlines_others.scheme_id',
								"submitted_date"=>"(( CASE WHEN 1 = 1 THEN( select created FROM apply_online_approvals 
								WHERE 
								  apply_online_approvals.application_id = ApplyOnlines.id 
								  AND apply_online_approvals.stage = '".$apply_approval_table->APPLICATION_SUBMITTED."' 
								group by 
								  stage) END ))"
							  ];
		if($arrExtra['ses_login_type'] == 'developer') {
			array_push($fields,'developer_customers.name');
		} else {
			array_push($fields,'customers.name');
		}
		
							
								
		$arrOrderBy     = explode("|",$order_by_form);
		if(!empty($customer_id)){
			
			$condition_arr  = array();
			$join_arr = [   
								['table'=>'projects','alias'=>'project','type'=>'left','conditions'=>'ApplyOnlines.project_id = project.id'],
								['table'=>'fesibility_report','alias'=>'fea','type'=>'left','conditions'=>'ApplyOnlines.id = fea.application_id'],
								['table'=>'inspection_pdf','alias'=>'inspdf','type'=>'left','conditions'=>'ApplyOnlines.id = inspdf.application_id'],
								['table'=>'apply_onlines_others','alias'=>'apply_onlines_others','type'=>'left','conditions'=>'apply_onlines_others.application_id = ApplyOnlines.id']
							];
			if($arrExtra['ses_login_type'] == 'developer') { 
				array_push($join_arr,['table'=>'installers','alias'=>'installer','type'=>'left','conditions'=>'installer.id = apply_onlines_others.map_installer_id']);
				array_push($join_arr,['table'=>'developer_customers','type'=>'left','conditions'=>'developer_customers.id = ApplyOnlines.customer_id']);
		
			} else {
				array_push($join_arr,['table'=>'installers','alias'=>'installer','type'=>'left','conditions'=>'installer.id = ApplyOnlines.installer_id']);
				array_push($join_arr,['table'=>'customers','type'=>'left','conditions'=>'customers.id = ApplyOnlines.customer_id']);
			}		
			$str_group_by = '1';

			if(!empty($installer_id))
			{
				$where_data     = ['OR'=>['ApplyOnlines.customer_id'=>$customer_id,'ApplyOnlines.installer_id'=>$installer_id,'AND'=>array('apply_onlines_others.map_installer_id'=>$installer_id,'or'=>['ApplyOnlines.application_status != '=>'0','ApplyOnlines.application_status is not null'],'ApplyOnlines.application_status not in '=>array($apply_approval_table->APPLICATION_GENERATE_OTP,$apply_approval_table->WAITING_LIST,$apply_approval_table->APPLICATION_PENDING,$apply_approval_table->APPLICATION_CANCELLED,0))]];
				
				array_push($where_data, ['apply_onlines_others.created_by_type'=>$arrExtra['ses_login_type']]);
				
			}
			else
			{
				$where_data         = ['ApplyOnlines.customer_id'=>$customer_id];
			}
			if(empty($application_status)) {
				if(isset($fromDate) && isset($toDate) && !empty($fromDate) && !empty($toDate)){
					$condition_arr  = [$where_data,"(select created FROM apply_online_approvals WHERE apply_online_approvals.application_id = ApplyOnlines.id AND apply_online_approvals.stage = '".$apply_approval_table->APPROVED_FROM_GEDA."' group by stage) BETWEEN :start AND :end"];
				} else {
					$condition_arr  = [$where_data];
				}
			} else {
				if(isset($fromDate) && isset($toDate) && !empty($fromDate) && !empty($toDate)){
					$condition_arr  = [$where_data,"(select created FROM apply_online_approvals WHERE apply_online_approvals.application_id = ApplyOnlines.id AND apply_online_approvals.stage = '".$apply_approval_table->APPROVED_FROM_GEDA."' group by stage) BETWEEN :start AND :end"];
				} else {
					$condition_arr  = [$where_data];
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
					   /* array_push($join_arr, ['table'=>'applyonline_messages','alias'=>'applyonline_messages','type'=>'INNER','conditions'=>'ApplyOnlines.id = applyonline_messages.application_id']);
						array_push($condition_arr,array('applyonline_messages.user_type in '=>$user_type,'AND' => [['applyonline_messages.message not like'=>'Reopen by %'], ['applyonline_messages.message not like'=>'Reset by %']]));
						if($passStatus != $apply_approval_table->APPLICATION_CANCELLED)
						{
							array_push($condition_arr,array('ApplyOnlines.application_status != ' => $apply_approval_table->APPLICATION_CANCELLED));
						}*/
					}
					else
					{
						$apply_approval_table   = TableRegistry::get('ApplyOnlineApprovals');
						/*$FindApplicationIDs     = $apply_approval_table->find('list',['keyField'=>'id','valueField'=>'application_id','conditions'=>['stage'=>$this->apply_online_status_key[$application_status]]])->toArray();
						if (!empty($FindApplicationIDs)) {
							array_push($condition_arr,array('ApplyOnlines.id IN ' => array_unique($FindApplicationIDs)));
							if($passStatus != $apply_approval_table->APPLICATION_CANCELLED)
							{
								array_push($condition_arr,array('ApplyOnlines.application_status != ' => $apply_approval_table->APPLICATION_CANCELLED));
							}
						} else {
							array_push($condition_arr,array('ApplyOnlines.id' => 0 ));
						}*/
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
			/*if($social_consumer !='')
			{
				array_push($condition_arr,array('ApplyOnlines.social_consumer'=>$social_consumer));
			}*/
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
			if(isset($category[0]) && !empty($category[0]))
			{
				array_push($condition_arr,array('ApplyOnlines.category in'=>$category));
			}
			array_push($condition_arr,array('OR'=>['project.project_social_consumer !='=>'1','project.project_disclaimer_subsidy'=>'1']));
			if(!empty($receipt_no))
			{
				array_push($join_arr,['table'=>'payumoney','alias'=>'payumoney','type'=>'left','conditions'=>'payumoney.application_id = ApplyOnlines.id']);
				array_push($condition_arr,array('payumoney.payment_status' => 'success','payumoney.receipt_no like ' => '%'.$receipt_no.'%' ));

			}
			$ApplyOnlinesList   = $this->find("all",[
				'fields' => $fields,
				'join'   => $join_arr,
				'conditions'=>$condition_arr,
				'order'=>[$arrOrderBy[0]=>$arrOrderBy[1],'ApplyOnlines.created'=>$arrOrderBy[1]]]);
			if(isset($fromDate) && isset($toDate) && !empty($fromDate) && !empty($toDate)){
				$StartTime  = date("Y-m-d",strtotime(str_replace('/', '-',$fromDate)))." 00:00:00";
				$EndTime    = date("Y-m-d",strtotime(str_replace('/', '-', $toDate)))." 23:59:59";
				$ApplyOnlinesList->bind(':start',$StartTime,'date')->bind(':end',$EndTime,'date')->count();
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
		} else if(!empty($member_id) && !empty($main_branch_id)) {
		   $join_arr = [   
							['table'=>'states','type'=>'left','conditions'=>'states.id = ApplyOnlines.apply_state'],
							['table'=>'installers','alias'=>'installer','type'=>'left','conditions'=>'installer.id = ApplyOnlines.installer_id'],
							['table'=>'projects','alias'=>'project','type'=>'left','conditions'=>'ApplyOnlines.project_id = project.id'],
							['table'=>'fesibility_report','alias'=>'fea','type'=>'left','conditions'=>'ApplyOnlines.id = fea.application_id'],
							['table'=>'inspection_pdf','alias'=>'inspdf','type'=>'left','conditions'=>'ApplyOnlines.id = inspdf.application_id'],
							['table'=>'apply_onlines_others','alias'=>'apply_onlines_others','type'=>'left','conditions'=>'apply_onlines_others.application_id = ApplyOnlines.id']
						];
				if($arrExtra['ses_login_type'] == 'developer') { 
					array_push($join_arr,['table'=>'developer_customers','type'=>'left','conditions'=>'developer_customers.id = ApplyOnlines.customer_id']);
			
				} else {
					array_push($join_arr,['table'=>'customers','type'=>'left','conditions'=>'customers.id = ApplyOnlines.customer_id']);
				}		
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
						/*array_push($join_arr, ['table'=>'applyonline_messages','alias'=>'applyonline_messages','type'=>'INNER','conditions'=>'ApplyOnlines.id = applyonline_messages.application_id']);
						array_push($condition_arr,array('applyonline_messages.user_type in '=>$user_type,'AND' => [['applyonline_messages.message not like'=>'Reopen by %'], ['applyonline_messages.message not like'=>'Reset by %']]));
						if($passStatus != $apply_approval_table->APPLICATION_CANCELLED)
						{
							array_push($condition_arr,array('ApplyOnlines.application_status != ' => $apply_approval_table->APPLICATION_CANCELLED));
						}*/
					}
					else
					{
						$apply_approval_table   = TableRegistry::get('ApplyOnlineApprovals');
						/*$FindApplicationIDs     = $apply_approval_table->find('list',['keyField'=>'id','valueField'=>'application_id','conditions'=>['stage'=>$this->apply_online_status_key[$application_status]]])->toArray();
						if (!empty($FindApplicationIDs)) {
							array_push($condition_arr,array('ApplyOnlines.id IN ' => array_unique($FindApplicationIDs)));
							if($passStatus != $apply_approval_table->APPLICATION_CANCELLED)
							{
								array_push($condition_arr,array('ApplyOnlines.application_status != ' => $apply_approval_table->APPLICATION_CANCELLED));
							}
						} else {
							array_push($condition_arr,array('ApplyOnlines.id' => 0 ));
						}*/
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
			/*if($social_consumer !='')
			{
				array_push($condition_arr,array('ApplyOnlines.social_consumer'=>$social_consumer));
			}*/
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
		} else if(!empty($member_id)) {
			$join_arr = [   
							['table'=>'states','type'=>'left','conditions'=>'states.id = ApplyOnlines.apply_state'],
							['table'=>'installers','alias'=>'installer','type'=>'left','conditions'=>'installer.id = ApplyOnlines.installer_id'],
							['table'=>'projects','alias'=>'project','type'=>'left','conditions'=>'ApplyOnlines.project_id = project.id'],
							['table'=>'fesibility_report','alias'=>'fea','type'=>'left','conditions'=>'ApplyOnlines.id = fea.application_id'],
							['table'=>'inspection_pdf','alias'=>'inspdf','type'=>'left','conditions'=>'ApplyOnlines.id = inspdf.application_id'],
							['table'=>'apply_onlines_others','alias'=>'apply_onlines_others','type'=>'left','conditions'=>'apply_onlines_others.application_id = ApplyOnlines.id']
						];

			if($arrExtra['ses_login_type'] == 'developer') { 
				array_push($join_arr,['table'=>'developer_customers','type'=>'left','conditions'=>'developer_customers.id = ApplyOnlines.customer_id']);
		
			} else {
				array_push($join_arr,['table'=>'customers','type'=>'left','conditions'=>'customers.id = ApplyOnlines.customer_id']);
			}	
			$str_group_by = '1';
			$condition_arr = array();
			$apply_approval_table   = TableRegistry::get('ApplyOnlineApprovals');

			if(empty($application_status)){
				if(isset($fromDate) && isset($toDate) && !empty($fromDate) && !empty($toDate)){
					$condition_arr = ['states.id' => $state,"(select created FROM apply_online_approvals WHERE apply_online_approvals.application_id = ApplyOnlines.id AND apply_online_approvals.stage = '".$apply_approval_table->APPROVED_FROM_GEDA."' group by stage) BETWEEN :start AND :end",'or'=>['ApplyOnlines.application_status != '=>'0','ApplyOnlines.application_status is not null'],'ApplyOnlines.application_status not in '=>array($apply_approval_table->APPLICATION_GENERATE_OTP,$apply_approval_table->WAITING_LIST,$apply_approval_table->APPLICATION_PENDING,$apply_approval_table->APPLICATION_CANCELLED,0)];
				} else {
					$condition_arr = ['states.id' => $state,'or'=>['ApplyOnlines.application_status != '=>'0','ApplyOnlines.application_status is not null'],'ApplyOnlines.application_status not in '=>array($apply_approval_table->APPLICATION_GENERATE_OTP,$apply_approval_table->WAITING_LIST,$apply_approval_table->APPLICATION_PENDING,$apply_approval_table->APPLICATION_CANCELLED,0)];
				}
			} else {
				if(isset($fromDate) && isset($toDate) && !empty($fromDate) && !empty($toDate)){
					$condition_arr = ['states.id' => $state,"(select created FROM apply_online_approvals WHERE apply_online_approvals.application_id = ApplyOnlines.id AND apply_online_approvals.stage = '".$apply_approval_table->APPROVED_FROM_GEDA."' group by stage) BETWEEN :start AND :end"];
				} else {
					$condition_arr = ['states.id' => $state];
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
					   /* array_push($join_arr, ['table'=>'applyonline_messages','alias'=>'applyonline_messages','type'=>'INNER','conditions'=>'ApplyOnlines.id = applyonline_messages.application_id']);
						array_push($condition_arr,array('applyonline_messages.user_type in '=>$user_type,'AND' => [['applyonline_messages.message not like'=>'Reopen by %'], ['applyonline_messages.message not like'=>'Reset by %']]));
						if($passStatus != $apply_approval_table->APPLICATION_CANCELLED)
						{
							array_push($condition_arr,array('ApplyOnlines.application_status != ' => $apply_approval_table->APPLICATION_CANCELLED));
						}*/
					}
					else
					{
						$apply_approval_table   = TableRegistry::get('ApplyOnlineApprovals');
						/*$FindApplicationIDs     = $apply_approval_table->find('list',['keyField'=>'id','valueField'=>'application_id','conditions'=>['stage'=>$this->apply_online_status_key[$application_status]]])->toArray();
						if (!empty($FindApplicationIDs)) {
							array_push($condition_arr,array('ApplyOnlines.id IN ' => array_unique($FindApplicationIDs)));
							if($passStatus != $apply_approval_table->APPLICATION_CANCELLED)
							{
								array_push($condition_arr,array('ApplyOnlines.application_status != ' => $apply_approval_table->APPLICATION_CANCELLED));
							}
						} else {
							array_push($condition_arr,array('ApplyOnlines.id' => 0 ));
						}*/
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
			/*if($social_consumer !='')
			{
				array_push($condition_arr,array('ApplyOnlines.social_consumer'=>$social_consumer));
			}*/
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
			if(!in_array($member_id,$this->AllowedGedaIDS))
			{
				array_push($condition_arr,array('OR'=>['project.project_social_consumer !='=>'1','project.project_disclaimer_subsidy'=>'1']));
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
			if(!empty($geda_letter_status))
			{
				$FindApplicationIDsPayment  = $this->find('list',['keyField'=>'id','valueField'=>'id','conditions'=>['OR'=>['ApplyOnlines.category !=' => '3001',['social_consumer'=>'1','payment_status'=>'0']]]])->toArray();
				$FindApplicationIDsPayment1  = $this->find('list',['keyField'=>'id','valueField'=>'id','conditions'=>['OR'=>['ApplyOnlines.category !=' => '3001',['social_consumer'=>'1','disclaimer_subsidy'=>'0']],'payment_status'=>'0']])->toArray();
				
				if($geda_letter_status==1)
				{ 
				
					/*$apply_approval_table   = TableRegistry::get('ApplyOnlineApprovals');
					$FindApplicationIDs     = $apply_approval_table->find('list',['keyField'=>'id','valueField'=>'application_id','conditions'=>['stage'=>$apply_approval_table->APPROVED_FROM_GEDA]])->toArray();
					if (!empty($FindApplicationIDs)) {
						//array_push($condition_arr,array('ApplyOnlines.id NOT IN ' => array_unique($FindApplicationIDs)));
					}
					if (!empty($FindApplicationIDsPayment1)) {
						array_push($condition_arr,array('OR'=>['ApplyOnlines.id IN ' => array_unique($FindApplicationIDsPayment1),'ApplyOnlines.application_status'=>'1']));
						//array_push($condition_arr,array('ApplyOnlines.application_status'=>'1'));
					}*/
					array_push($condition_arr,array('ApplyOnlines.application_status in' => array($apply_approval_table->APPLICATION_SUBMITTED,$apply_approval_table->APPROVED_FROM_GEDA),'ApplyOnlines.payment_status'=>'0'));
				}
				elseif($geda_letter_status==2)
				{
					
					/*if (!empty($FindApplicationIDsPayment)) {
						array_push($condition_arr,array('ApplyOnlines.id NOT IN ' => array_unique($FindApplicationIDsPayment)));
					}
					$apply_approval_table   = TableRegistry::get('ApplyOnlineApprovals');
					$FindApplicationIDs     = $apply_approval_table->find('list',['keyField'=>'id','valueField'=>'application_id','conditions'=>['stage'=>$this->apply_online_status_key[$geda_letter_status]]])->toArray();
					if (!empty($FindApplicationIDs)) {
						array_push($condition_arr,array('ApplyOnlines.id IN ' => array_unique($FindApplicationIDs)));
					} else {
						array_push($condition_arr,array('ApplyOnlines.id' => 0 ));
					}*/
					array_push($condition_arr,array('ApplyOnlines.application_status' => $this->apply_online_status_key[$geda_letter_status],'ApplyOnlines.payment_status'=>'1'));
				}
				
				
			}
			if(!empty($geda_approved_status))
			{
				if($geda_approved_status==1)
				{
					array_push($condition_arr,array('ApplyOnlines.application_status' => $apply_approval_table->APPLICATION_SUBMITTED ));
				}
				elseif($geda_approved_status==2)
				{
					array_push($condition_arr,array('ApplyOnlines.application_status' => $apply_approval_table->APPROVED_FROM_GEDA,'OR'=>['ApplyOnlines.category !='=>$this->category_residental,'ApplyOnlines.social_consumer'=>'1'],'ApplyOnlines.payment_status'=>'0'));
				}
				elseif($geda_approved_status==3)
				{
					array_push($condition_arr,array('ApplyOnlines.application_status' => $apply_approval_table->APPROVED_FROM_GEDA,'OR'=>['ApplyOnlines.category !='=>$this->category_residental,'ApplyOnlines.social_consumer'=>'1'],'ApplyOnlines.payment_status'=>'1'));
				}
				elseif($geda_approved_status==4)
				{
					array_push($condition_arr,array('ApplyOnlines.application_status' => $apply_approval_table->REJECTED_FROM_GEDA));
				}
			}
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
		}
	}
	
	public function getDataapplyonlineRecent($customer_id = '',$member_id ='',$state="",$fromDate = '',$toDate = '',$main_branch_id = '')
	{
		$fields             = ['ApplyOnlines.id',
								'ApplyOnlines.customer_id',
								'ApplyOnlines.installer_id',
								'ApplyOnlines.disclaimer',
								'ApplyOnlines.customer_name_prefixed',
								'ApplyOnlines.customer_name',
								'ApplyOnlines.name_of_consumer_applicant',
								'ApplyOnlines.address1',
								'ApplyOnlines.address2',
								'ApplyOnlines.comunication_address',
								'ApplyOnlines.city',
								'ApplyOnlines.state',
								'ApplyOnlines.pincode',
								'ApplyOnlines.mobile',
								'ApplyOnlines.landline_no',
								'ApplyOnlines.aadhar_no_or_pan_card_no',
								'ApplyOnlines.attach_photo_scan_of_aadhar',
								'ApplyOnlines.pan_card_no',
								'ApplyOnlines.attach_pan_card_scan',
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
								'ApplyOnlines.email',
								'ApplyOnlines.discom_name',
								'ApplyOnlines.consumer_no',
								'ApplyOnlines.roof_of_proposed',
								'ApplyOnlines.created',
								'ApplyOnlines.area',
								'ApplyOnlines.circle',
								'ApplyOnlines.division',
								'ApplyOnlines.subdivision',
								'ApplyOnlines.section',
								'customers.name'];
		if(!empty($customer_id)){
			$ApplyOnlinesList   = $this->find("all",[
				'fields' => $fields,
				'join'   => [   
								['table'=>'installers','alias'=>'installer','type'=>'left','conditions'=>'installer.id = ApplyOnlines.installer_id'],
								['table'=>'customers','type'=>'left','conditions'=>'customers.id = ApplyOnlines.customer_id']
							],
				'conditions'=>['ApplyOnlines.customer_id'=>$customer_id],
				'limit'=>10,
				'order'=>['ApplyOnlines.created'=>'DESC']]);
			if(!empty($fromDate) && !empty($toDate)) {
					$StartTime  = date("Y-m-d",strtotime(str_replace('/', '-',$fromDate)))." 00:00:00";
					$EndTime    = date("Y-m-d",strtotime(str_replace('/', '-', $toDate)))." 23:59:59";
				$ApplyOnlinesList->where(['ApplyOnlines.created BETWEEN :start AND :end']);
				$ApplyOnlinesList->bind(':start', $StartTime, 'date')->bind(':end',   $EndTime, 'date');
			}
			return $ApplyOnlinesList->toArray();
		} else if(!empty($member_id) && !empty($main_branch_id)) {
			if ($main_branch_id['member_type'] = $this->DISCOM) {
				$condition_arr = array( 'states.id' => $state,
										'ApplyOnlines.'.$main_branch_id['field']=>$main_branch_id['id']);
			} else {
				$condition_arr = array('states.id' => $state);
			}
			$ApplyOnlinesList   = $this->find("all",[
				'fields'=>$fields,
				'join'=>[   
							['table'=>'states','type'=>'left','conditions'=>'states.id = ApplyOnlines.state or states.statename = ApplyOnlines.state'],
							['table'=>'installers','alias'=>'installer','type'=>'left','conditions'=>'installer.id = ApplyOnlines.installer_id'],
							['table'=>'customers','type'=>'left','conditions'=>'customers.id = ApplyOnlines.customer_id']
						],
				'conditions'=>$condition_arr,
				'limit'=>10,
				'order'=>['ApplyOnlines.created'=>'DESC']]);
			if(!empty($fromDate) && !empty($toDate)){
				$StartTime  = date("Y-m-d",strtotime(str_replace('/', '-', $fromDate)))." 00:00:00";
				$EndTime    = date("Y-m-d",strtotime(str_replace('/', '-', $toDate)))." 23:59:59";
				$ApplyOnlinesList->where(['ApplyOnlines.created BETWEEN :start AND :end']);
				$ApplyOnlinesList->bind(':start', $StartTime, 'date')->bind(':end',   $EndTime, 'date');
			}
			return $ApplyOnlinesList->toArray();
		}
	}

	public function viewApplication($id)
	{
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
			'apply_onlines_others.map_installer_id',
			'apply_onlines_others.upload_undertaking',
			'apply_onlines_others.scheme_id',
			'installer.designation',
			'installer.email'
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

	public function setStatus($data) 
	{
		$ApplyOnlineData    = $this->get($data['id']);
		$ApplyOnlineEntity  = $this->patchEntity($ApplyOnlineData,$data);
		if($this->save($ApplyOnlineEntity)) {
		   return  $ApplyOnlineEntity;
		} else {
			return  array();
		}
	}

	public function GenerateApplicationNo($application) {
		$StateCode  = $this->GetStateCode($application->apply_state,$application->state);
		$id         = $application->id;
		$appendStr  = "AH";
		if(($application->apply_state==$this->gujarat_st_id || strtolower($application->apply_state)==$this->gujarat_st_name) && $application->category!='')
		{
			$Param      = TableRegistry::get('Parameters');
			$arrParam   = $Param->find("all",['conditions'=>['para_id'=>$application->category]])->first();
			if(!empty($arrParam->para_value))
			{
				$appendStr = substr(strtoupper($arrParam->para_value), 0,3);
			}
		}
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

	public function UpdateApplicationData($data,$id) 
	{
		$arrData = array("comunication_address"=>$data['comunication_address'],
						"consumer_no"=>$data['consumer_no'],
						//"mobile"=>$data['mobile'],
						"category"=>$data['category']);
		$this->updateAll($arrData,['id' => $id]);
		return true;
	}

	public function ApproveFesibilityMatrix($pv=0,$subdivision=0,$division=0,$area=0,$section=0)
	{
		$CanApprove = false;
		if ($pv <= 5 && ($section == 0 || $section > 0)) {
			$CanApprove = true;
		} else if ($pv > 5 && $pv <= 50 && $section == 0 && $division > 0) {
			$CanApprove = true;
		} else if ($subdivision == 0 && $division > 0) {
			$CanApprove = true;
		}
		return $CanApprove;
	}

	public function ApproveFesibilityMatrixV2($pv=0,$division=0,$area=0,$circle=0,$subdivision=0,$section=0)
	{
		$CanApprove = false;
		if ($pv <= 5 && $section == 0 && $subdivision > 0) {
			$CanApprove = true;
		} else if ($pv > 5 && $pv <= 50 && $subdivision == 0 && $division > 0) {
			$CanApprove = true;
		} else if ($division == 0 && $circle > 0) {
			$CanApprove = true;
		}
		return $CanApprove;
	}

	public function ApproveCEIMatrix($pv=0)
	{
		$CanApprove = false;
		if ($pv < 10) {
			$CanApprove = true;
		}
		return $CanApprove;
	}
	
	public function Approvedapplication($state = '',$main_branch_id = '',$application_status=1) 
	{
		if ($main_branch_id['member_type'] == $this->DISCOM)
		{
			if ($application_status == 2) {
				$arrCond = array('states.id' => $state,
									'ApplyOnlines.'.$main_branch_id['field']=>$main_branch_id['id'],
									'ApplyOnlines.application_status'=>$application_status
							);
			} else {
				$arrCond = array('states.id' => $state,
									'ApplyOnlines.'.$main_branch_id['field']=>$main_branch_id['id'],
									'ApplyOnlines.application_status != '=> 2
							);
			}
		} else {
			if ($application_status == 2) {
				$arrCond = array('states.id' => $state,'ApplyOnlines.application_status'=>$application_status);
			} else {
				$arrCond = array('states.id' => $state,'ApplyOnlines.application_status != '=> 2);
			}
		}
		if ($main_branch_id['member_type'] == $this->DISCOM)
		{
			return $this->find('all',['join'=>[
					['table'=>'states',
					'type'=>'left',
					'conditions'=>'states.id = ApplyOnlines.state or states.statename = ApplyOnlines.state']
				],
				'conditions'=>$arrCond
				])->count();
		} else {
			return $this->find('all',['join'=>[
					['table'=>'states',
					'type'=>'left',
					'conditions'=>'states.id = ApplyOnlines.state or states.statename = ApplyOnlines.state']
				],
				'conditions'=>$arrCond
				])->count();
		}
	}

	public function TotalApplication($state = '',$main_branch_id='') 
	{
		$STATUS = NULL;
		if ($main_branch_id['member_type'] == $this->DISCOM)
		{
			return $this->find('all',['join'=>[
					['table'=>'states',
					'type'=>'left',
					'conditions'=>'states.id = ApplyOnlines.state or states.statename = ApplyOnlines.state']
				],
				'conditions'=>[ 'states.id' => $state,
								'ApplyOnlines.application_status IS NOT' => $STATUS,
								'ApplyOnlines.'.$main_branch_id['field']=>$main_branch_id['id']
				]
				])->count();
		} else {
			return $this->find('all',['join'=>[
					['table'=>'states',
					'type'=>'left',
					'conditions'=>'states.id = ApplyOnlines.state or states.statename = ApplyOnlines.state']
				],
				'conditions'=>[ 'states.id' => $state,'ApplyOnlines.application_status IS NOT' => $STATUS]
				])->count();
		}
	}

	public function SetStatusToWorkOrderStats($id,$Work_Starts)
	{
		$arrData = array("application_status"=>$Work_Starts);
		$this->updateAll($arrData,['id' => $id]);
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
		/*if(strlen($value) <= 6)
		{
			
		}
		else
		{
			return true;
		}*/
	}
	/**
	*
	* custom_consumer_unique
	*
	* Behaviour : public
	*
	* Parameter : discom
	*
	* @defination : Method is used to check consumer number unique last 6 digit.
	*
	*/
	public function custom_consumer_unique($value, $context)
	{
		//$last_data = substr($value,-6);
		//$arr_result= $this->find('all',array('conditions'=>array("consumer_no like "=>'%'.$last_data)))->toArray();
		$ApplyOnlineApprovals 	= TableRegistry::get('ApplyOnlineApprovals');
		$arr_condition 			= array("ApplyOnlines.consumer_no" => $value,"ApplyOnlines.discom"=>$this->data['ApplyOnlines']['discom']);
		if(isset($this->data['ApplyOnlines']['id']) && !empty($this->data['ApplyOnlines']['id']))
		{
			$arr_condition = array("ApplyOnlines.consumer_no" => $value,'ApplyOnlines.id != '=>$this->data['ApplyOnlines']['id'],"ApplyOnlines.discom"=>$this->data['ApplyOnlines']['discom']);
		}
		$arr_result= $this->find('all',array('conditions'   => $arr_condition,
											'fields'        => array('installers.installer_name','ApplyOnlines.created','ApplyOnlines.id'),
											'join'          => [['table'=>'installers','type'=>'inner','conditions'=>'ApplyOnlines.installer_id = installers.id']]
											))->toArray();
		$count 					= 0;
		foreach($arr_result as $application) {
			$approval 			= $ApplyOnlineApprovals->Approvalstage($application->id);
			if(in_array($ApplyOnlineApprovals->METER_INSTALLATION, $approval)) {
				$count++;
			}
		}
		
		if($count == count($arr_result)) {
			return true;
		}
		if(!empty($arr_result))
		{
			return 'Application against the Consumer no. is already generated by '.$arr_result[0]->installers['installer_name'].' on '.date(LIST_DATE_FORMAT,strtotime($arr_result[0]->created)).'.';
		}
		else
		{
			return true;
		}
	}
	/**
	*
	* custom_mobile_check
	*
	* Behaviour : public
	*
	* @defination : Method is used to return false when consumer mobile and installer mobile are same.
	*
	*/
	public function custom_mobile_check($value, $context) {
			return false;
	}
	/**
	 *
	 *  SendSMSActivationCode
	 *
	 * Behaviour : Public
	 *
	 * @defination :  Method for send otp msg to consumer mobile .
	 *
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
		$Category_text  = strtoupper(substr($application->parameter_cats['para_value'],0,3));
		 $strGov        = "";
		//$InstallerCode  = $this->GetInstallerCode($application->installer_id);
		if($application->govt_agency==1)
		{
			$strGov     = "/GOV";
		}

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
	*
	* custom_consumer_block
	*
	* Behaviour : public
	*
	* Parameter : discom
	*
	* @defination : Method is used to check consumer number in block list or available.
	*
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
	*
	* custom_installer_category
	*
	* Behaviour : public
	*
	* Parameter : 
	*
	* @defination : Method is used to check pv_capacity slot available or not.
	*
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
	public function getDiscomDetails($circle,$division,$subdivision,$area)             
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
		return $str_output;
	}
	/**
	*
	* custom_consumer_mobile
	*
	* Behaviour : public
	*
	* Parameter : discom
	*
	* @defination : Method is used to check consumer number use 3 times available.
	*
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
	
	public function TotalApplicationByStatus($state = '',$main_branch_id='',$application_status=0) 
	{
		$ApplyOnlines = $this->find();
		$ApplyOnlines->hydrate(false);
		$apply_approval_table       = TableRegistry::get('ApplyOnlineApprovals');
		if ($main_branch_id['member_type'] == $this->DISCOM)
		{
			if($apply_approval_table->APPLICATION_SUBMITTED == $application_status)
			{
				$application_status = $apply_approval_table->APPROVED_FROM_GEDA;
			}
			$ApplyOnlines->select(['TotalCount' => $ApplyOnlines->func()->count(0)])
			->join([
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.apply_state'
						],
						[
							'table'=>'apply_online_approvals',
							'type'=>'left',
							'conditions'=>'apply_online_approvals.application_id = ApplyOnlines.id'
						]
					]
				)
			->where([   'states.id' => $state,
						'apply_online_approvals.stage IN' => $application_status,
						'OR'=>['ApplyOnlines.payment_status'=>'1','ApplyOnlines.category'=>'3001'],
						'ApplyOnlines.'.$main_branch_id['field']=>$main_branch_id['id']]);
		} else if ($main_branch_id['member_type'] == "CUSTOMER" || $main_branch_id['member_type'] == "INSTALLER") {
			$ApplyOnlines->select(['TotalCount' => $ApplyOnlines->func()->count(0)])
			->join([
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.apply_state'
						],
						[
							'table'=>'apply_online_approvals',
							'type'=>'left',
							'conditions'=>'apply_online_approvals.application_id = ApplyOnlines.id'
						]
					]
				)
			->where([   'states.id' => $state,
						'apply_online_approvals.stage IN' => $application_status,
						'ApplyOnlines.'.$main_branch_id['field']=>$main_branch_id['id']]);
		} else {
			$ApplyOnlines->select(['TotalCount' => $ApplyOnlines->func()->count(0)])
			->join([
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.apply_state'
						],
						[
							'table'=>'apply_online_approvals',
							'type'=>'left',
							'conditions'=>'apply_online_approvals.application_id = ApplyOnlines.id'
						]
					]
				)
			->where(['states.id' => $state,'apply_online_approvals.stage IN' => $application_status]);
		}
	   
		if($application_status!=$apply_approval_table->APPLICATION_CANCELLED)
		{
			$ApplyOnlines->where(['ApplyOnlines.application_status != ' => $apply_approval_table->APPLICATION_CANCELLED]);
		}
		$resultArray = $ApplyOnlines->toList();
		$TotalCount  = isset($resultArray[0]['TotalCount'])?$resultArray[0]['TotalCount']:0;
		return $TotalCount;
	}

	public function TotalApplicationCountByStatus($state = '',$main_branch_id='',$application_status=0)
	{
		$ApplyOnlines = $this->find();
		$ApplyOnlines->hydrate(false);
		$apply_approval_table       = TableRegistry::get('ApplyOnlineApprovals');
		if ($main_branch_id['member_type'] == $this->DISCOM)
		{
			if($apply_approval_table->APPLICATION_SUBMITTED == $application_status)
			{
				$application_status = $apply_approval_table->APPROVED_FROM_GEDA;
			}
			
			$ApplyOnlines->select(['TotalCount' => $ApplyOnlines->func()->count(0)])
			->join([
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.apply_state'
						]
					]
				)
			->where([   'states.id' => $state,
						'ApplyOnlines.'.$main_branch_id['field']=>$main_branch_id['id'],
						'OR'=>['ApplyOnlines.payment_status'=>'1','ApplyOnlines.category'=>'3001']]);
		} else if ($main_branch_id['member_type'] == "CUSTOMER" || $main_branch_id['member_type'] == "INSTALLER") {
			$ApplyOnlines->select(['TotalCount' => $ApplyOnlines->func()->count(0)])
			->join([
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.apply_state'
						]
					]
				)
			->where([   'states.id' => $state,
						'ApplyOnlines.'.$main_branch_id['field']=>$main_branch_id['id']]);
		} else {
			$ApplyOnlines->select(['TotalCount' => $ApplyOnlines->func()->count(0)])
			->join([
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.apply_state'
						]
					]
				)
			->where(['states.id' => $state]);

			
		}
		
		if($application_status==$apply_approval_table->DOCUMENT_NOT_VERIFIED)
		{
			$ApplyOnlines->where(array('ApplyOnlines.application_status'=>$apply_approval_table->APPROVED_FROM_GEDA));
		}
		else
		{
			$ApplyOnlines->where(array('ApplyOnlines.application_status'=>$application_status));
		}
		$resultArray = $ApplyOnlines->toList();
		$TotalCount  = isset($resultArray[0]['TotalCount'])?$resultArray[0]['TotalCount']:0;
		return $TotalCount;
	}

	public function TotalApplicationPVCapacity($state = '',$main_branch_id='',$application_status=0) 
	{
		$ApplyOnlines = $this->find();
		$ApplyOnlines->hydrate(false);
		$apply_approval_table   = TableRegistry::get('ApplyOnlineApprovals');
		if ($main_branch_id['member_type'] == $this->DISCOM)
		{
			if($apply_approval_table->APPLICATION_SUBMITTED == $application_status)
			{
				$application_status = $apply_approval_table->APPROVED_FROM_GEDA;
			}
			$ApplyOnlines->select(['TotalCapacity' => $ApplyOnlines->func()->sum('ApplyOnlines.pv_capacity')])
			->join([
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.apply_state'
						],
						[
							'table'=>'apply_online_approvals',
							'type'=>'left',
							'conditions'=>'apply_online_approvals.application_id = ApplyOnlines.id'
						]
					]
				)
			->where([   'states.id' => $state,
						'apply_online_approvals.stage IN' => $application_status,
						'OR'=>['ApplyOnlines.payment_status'=>'1','ApplyOnlines.category'=>'3001'],
						'ApplyOnlines.'.$main_branch_id['field']=>$main_branch_id['id']]);
		} else if ($main_branch_id['member_type'] == "CUSTOMER" || $main_branch_id['member_type'] == "INSTALLER") {
			$ApplyOnlines->select(['TotalCapacity' => $ApplyOnlines->func()->sum('ApplyOnlines.pv_capacity')])
			->join([
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.apply_state'
						],
						[
							'table'=>'apply_online_approvals',
							'type'=>'left',
							'conditions'=>'apply_online_approvals.application_id = ApplyOnlines.id'
						]
					]
				)
			->where([   'states.id' => $state,
						'apply_online_approvals.stage IN' => $application_status,
						'ApplyOnlines.'.$main_branch_id['field']=>$main_branch_id['id']]);
		} else {
			$ApplyOnlines->select(['TotalCapacity' => $ApplyOnlines->func()->sum('ApplyOnlines.pv_capacity')])
			->join([
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.apply_state'
						],
						[
							'table'=>'apply_online_approvals',
							'type'=>'left',
							'conditions'=>'apply_online_approvals.application_id = ApplyOnlines.id'
						]
					]
				)
			->where(['states.id' => $state,'apply_online_approvals.stage IN' => $application_status]);
		}
		
		if($application_status!=$apply_approval_table->APPLICATION_CANCELLED)
		{
			$ApplyOnlines->where(['ApplyOnlines.application_status != ' => $apply_approval_table->APPLICATION_CANCELLED]);
		}
		$resultArray    = $ApplyOnlines->toList();
		$TotalCapacity  = isset($resultArray[0]['TotalCapacity'])?$resultArray[0]['TotalCapacity']:0;
		return (!empty($TotalCapacity)?_FormatGroupNumberV2($TotalCapacity):0);
	}

	public function TotalApplicationPVCapacityByStatus($state = '',$main_branch_id='',$application_status=0)
	{
		$ApplyOnlines = $this->find();
		$ApplyOnlines->hydrate(false);
		if ($main_branch_id['member_type'] == $this->DISCOM)
		{
			$ApplyOnlines->select(['TotalCapacity' => $ApplyOnlines->func()->sum('ApplyOnlines.pv_capacity')])
			->join([
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.apply_state'
						]
					]
				)
			->where([   'states.id' => $state,
						'ApplyOnlines.'.$main_branch_id['field']=>$main_branch_id['id']]);
		} else if ($main_branch_id['member_type'] == "CUSTOMER" || $main_branch_id['member_type'] == "INSTALLER") {
			$ApplyOnlines->select(['TotalCapacity' => $ApplyOnlines->func()->sum('ApplyOnlines.pv_capacity')])
			->join([
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.apply_state'
						]
					]
				)
			->where([   'states.id' => $state,
						'ApplyOnlines.'.$main_branch_id['field']=>$main_branch_id['id']]);
		} else {
			$ApplyOnlines->select(['TotalCapacity' => $ApplyOnlines->func()->sum('ApplyOnlines.pv_capacity')])
			->join([
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.apply_state'
						]
					]
				)
			->where(['states.id' => $state]);
		}
		$apply_approval_table   = TableRegistry::get('ApplyOnlineApprovals');
		if($application_status==$apply_approval_table->DOCUMENT_NOT_VERIFIED)
		{
			$ApplyOnlines->where(array('ApplyOnlines.application_status'=>$apply_approval_table->APPROVED_FROM_GEDA));
		}
		else
		{
			$ApplyOnlines->where(array('ApplyOnlines.application_status'=>$application_status));
		}
		$resultArray    = $ApplyOnlines->toList();
		$TotalCapacity  = isset($resultArray[0]['TotalCapacity'])?$resultArray[0]['TotalCapacity']:0;
		return (!empty($TotalCapacity)?_FormatGroupNumberV2($TotalCapacity):0);
	}

	public function MonthwiseApplicationStatistics($state = '',$main_branch_id, $arrStatus,$IndividualStatus)
	{
		$MonthwiseStats     = array();
		$arrStatusStats = array();
		foreach ($arrStatus as $application_status)
		{
			$WhereCondition                 = array();
			$WhereCondition['states.id']    = $state;
			if ($main_branch_id['member_type'] == $this->DISCOM)
			{
				$WhereCondition['ApplyOnlines.'.$main_branch_id['field']] = $main_branch_id['id'];
			} else if ($main_branch_id['member_type'] == "CUSTOMER" || $main_branch_id['member_type'] == "INSTALLER") {
				$WhereCondition['ApplyOnlines.'.$main_branch_id['field']] = $main_branch_id['id'];
			}
			$ApplyOnlines       = $this->find();
			$ApplyOnlines->hydrate(false);
			if (!in_array($application_status,$IndividualStatus)) {
				$WhereCondition['apply_online_approvals.stage IN'] = $application_status;
				$ApplyOnlines->select([ 'Apply_Month' => 'MONTH(ApplyOnlines.created)','count' => $ApplyOnlines->func()->count('ApplyOnlines.id')])
				->group('Apply_Month')
				->join([
							['table'=>'states','type'=>'left','conditions'=>'states.id = ApplyOnlines.apply_state'],
							[
								'table'=>'apply_online_approvals',
								'type'=>'left',
								'conditions'=>'apply_online_approvals.application_id = ApplyOnlines.id'
							]
						]
					);
			} else {
				if ($application_status == $this->DOCUMENT_NOT_VERIFIED) {
					$WhereCondition['ApplyOnlines.application_status'] = $this->DOCUMENT_VERIFIED_STATUS;
				} else {
					$WhereCondition['ApplyOnlines.application_status'] = $application_status;
				}
				$ApplyOnlines->select([ 'Apply_Month' => 'MONTH(ApplyOnlines.created)','count' => $ApplyOnlines->func()->count('ApplyOnlines.id')])
				->group('Apply_Month')
				->join(['table'=>'states','type'=>'left','conditions'=>'states.id = ApplyOnlines.apply_state']);
			}
			$ApplyOnlines->where(array_merge($WhereCondition,array(function ($exp,$q) {
							$StartTime  = date("Y",strtotime($this->NOW()))."-01-01 00:00:00";
							$EndTime    = date("Y",strtotime($this->NOW()))."-12-31 23:59:59";
							return $exp->between('ApplyOnlines.created', $StartTime, $EndTime);
						})));
			$resultArray = $ApplyOnlines->toList();
			if (!empty($resultArray)) {
				foreach ($resultArray as $resultRow) {
					$arrStatusStats[$application_status][$resultRow['Apply_Month']] = $resultRow['count'];
				}
			}
		}
		$CurrentMonth = date("m",strtotime($this->NOW()));
		for ($i=1; $i<=$CurrentMonth; $i++) {
			$j = 0;
			$MonthwiseStats[$i][$j] = "'".date("M",strtotime(date("Y")."-$i-01 00:00:00"))."'";
			foreach ($arrStatus as $application_status) {
				$j++;
				$count = isset($arrStatusStats[$application_status][$i])?$arrStatusStats[$application_status][$i]:0;
				array_push($MonthwiseStats[$i], $count);
			}
		}
		return $MonthwiseStats;
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

	public function IsInstallerAllowedToSubmit($customer_id=0)
	{
		$IsAllowed          = true;
		$connection         = ConnectionManager::get('default');
		$arrApplication     = array();
		$ApplicationID      = " SELECT AO.id AS App_Id
								FROM apply_onlines AO
								INNER JOIN apply_online_approvals AA ON AO.id = AA.application_id
								INNER JOIN installer_category_mapping ICM ON AO.installer_id = ICM.installer_id
								INNER JOIN installers IM ON AO.installer_id = IM.id
								INNER JOIN customers CM ON CM.installer_id = IM.id
								WHERE (AA.stage IN (1,31,30,23,22) OR AO.application_status IN (1,31,30,23,22))
								AND ICM.category_id = 2
								AND CM.id = '".intval($customer_id)."'";
		$installerData      = $connection->execute($ApplicationID)->fetchAll('assoc');
		if (!empty($installerData)) {
			foreach ($installerData as $key => $value) {
				array_push($arrApplication,$installerData[$key]['App_Id']);
			}
		}
		if (!empty($arrApplication)) {
			$SelectSql          = " SELECT SUM(AO.pv_capacity) AS TotalCapacity
									FROM apply_onlines AO
									WHERE AO.id IN (".implode(",",array_unique($arrApplication)).")";
			$CapacityData      = $connection->execute($SelectSql)->fetchAll('assoc');
			if (!empty($CapacityData) && isset($CapacityData[0]['TotalCapacity'])) {
				if (intval($CapacityData[0]['TotalCapacity']) > 140) {
					$IsAllowed = false;
				}
			}
		}
		return $IsAllowed;
	}
	 /**
	*
	* custom_capacity_validation
	*
	* Behaviour : public
	*
	* Parameter : capacity
	*
	* @defination : Method is used to check installer Category A (Max. Under Residential: 93.50 MW Max. Under Social Sector: 16.50 MW) Category B (Max. Under Residential: 12.75 MW Max. Under Social Sector: 2.25 MW)
	*
	*/
	public function custom_capacity_validation($value, $context)
	{
		if(isset($this->data['ApplyOnlines']['installer_id']) && !empty($this->data['ApplyOnlines']['installer_id']))
		{
			return $this->checked_total_capacity($this->data['ApplyOnlines']['installer_id'],$value);
			/*$installer_id           = $this->data['ApplyOnlines']['installer_id'];
			$apply_approval_table   = TableRegistry::get('ApplyOnlineApprovals');
			$applyOnline            = $this->find();
			if(isset($this->data['ApplyOnlines']['social_consumer']) && $this->data['ApplyOnlines']['social_consumer']=='1')
			{
				$where_query        = " and apply_onlines.social_consumer='1' ";
			}
			else
			{
				$where_query        = " and apply_onlines.social_consumer='0' ";
			}
			$connection             = ConnectionManager::get('default');
			$arrApplication         = array();
			$ApplicationID          = "SELECT SUM(apply_onlines.pv_capacity) as total_pv_capacity,ic.capacity,ic.id from apply_onlines left join installer_category_mapping as icm on apply_onlines.installer_id=icm.installer_id left join installer_category as ic on icm.category_id=ic.id
				WHERE apply_onlines.id In (SELECT apply_onlines.id FROM `apply_onlines`
				LEFT JOIN apply_online_approvals ON apply_online_approvals.application_id = apply_onlines.id
				WHERE apply_onlines.installer_id in (select installer_id from installer_category_mapping where category_id=(select category_id from installer_category_mapping  where installer_id='".$installer_id."')) $where_query AND apply_online_approvals.stage IN ('".$apply_approval_table->DOCUMENT_VERIFIED."', '".$apply_approval_table->APPROVED_FROM_GEDA."') GROUP by apply_online_approvals.application_id)";

			$installerData              = $connection->execute($ApplicationID)->fetchAll('assoc');
			$total_MW_capacity          = $installerData[0]['total_pv_capacity'];
			$InstallerCategoryMapping   = TableRegistry::get('InstallerCategoryMapping');
			
			$allocated_capacity         = $installerData[0]['capacity'];
			$allocated_id               = $installerData[0]['id'];
			//echo $allocated_id;
			if(!empty($total_MW_capacity) && $total_MW_capacity>0)
			{
				if(isset($this->data['ApplyOnlines']['social_consumer']) && $this->data['ApplyOnlines']['social_consumer']=='1')
				{
					$capacity_allowed   = ($allocated_capacity*SOCIAL_CAT_PER)/100;
				}
				else
				{
					$capacity_allowed   = ($allocated_capacity*RESIDENTIAL_CAT_PER)/100;
				}
				$checked_capacity           = (($capacity_allowed));
				if(($total_MW_capacity+$value) > $checked_capacity)
				{
					if(($checked_capacity-$total_MW_capacity)>0)
					{
						return 'Your PV capacity quota has been finished. Kindly contact GEDA office for further detail.';
					   // return 'You can enter maximum pv capacity equals to '.($checked_capacity-$total_MW_capacity).;
					}
					else
					{
						return 'Your PV capacity quota has been finished. Kindly contact GEDA office for further detail.';
					   // return 'You have reached maximum limit of pv capacity - '.($checked_capacity).;
					}
				}
			}  */
		}
		return true;
		//return false;
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
				->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
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
	/**
	*
	* checked_total_capacity
	*
	* Behaviour : public
	*
	* Parameter : capacity
	*
	* @defination : Method is used to check installer Category A (Max. Under Residential: 93.50 MW Max. Under Social Sector: 16.50 MW) Category B (Max. Under Residential: 12.75 MW Max. Under Social Sector: 2.25 MW)
	*
	*/
	public function checked_total_capacity($installer_id,$value=0,$category_pass_id=0,$social_consumer=0,$pass_application_id=0,$disclaimer_subsidy=0)
	{
		//return STOP_ADD_APPLICATION_MSG;
		//echo $this->data['ApplyOnlines']['installer_id'];
		return true;
		if(isset($this->data['ApplyOnlines']['disclaimer_subsidy']))
		{
			$disclaimer_subsidy     = $this->data['ApplyOnlines']['disclaimer_subsidy'];
		}
		if(isset($this->data['ApplyOnlines']['category']) && !empty($this->data['ApplyOnlines']['category']))
		{
			$category_pass_id       = $this->data['ApplyOnlines']['category'];
		}
		if(isset($this->data['ApplyOnlines']['pv_capacity']) && !empty($this->data['ApplyOnlines']['pv_capacity']))
		{
			$value                  = $this->data['ApplyOnlines']['pv_capacity'];
		}
		
		if(strtotime(date('Y-m-d H:i:s')) > strtotime(STOP_ADD_APPLICATION) && !in_array($_SERVER['REMOTE_ADDR'],array("203.88.138.46","203.88.147.186","43.252.198.81")))
		{
			//return STOP_ADD_APPLICATION_MSG;
		}
		if(isset($installer_id) && !empty($installer_id) && $disclaimer_subsidy==0)
		{
			$installer_id           = $installer_id;
			$apply_approval_table   = TableRegistry::get('ApplyOnlineApprovals');
			$applyOnline            = $this->find();
			if((isset($this->data['ApplyOnlines']['social_consumer']) && $this->data['ApplyOnlines']['social_consumer']=='1') || $social_consumer==1)
			{
				$where_query        = " and apply_onlines.social_consumer='1' ";
			}
			else
			{
				$where_query        = " and apply_onlines.social_consumer='0' ";
			}
			$cur_pv_capacity        = "0";
			$where_query_data       = "";
			if(isset($this->data['ApplyOnlines']['id']) && !empty($this->data['ApplyOnlines']['id']))
			{
				$where_query_data   = " and apply_onlines.id !='".$this->data['ApplyOnlines']['id']."' ";
				$pass_application_id= $this->data['ApplyOnlines']['id'];
				$current_app_data       = $this->viewApplication($pass_application_id);
			}
			
			$connection             = ConnectionManager::get('default');
			$installergoryMapping   = $connection->execute("SELECT ic.capacity as capacity, ic.id as id
								FROM installer_category_mapping as icm left join installer_category as ic on icm.category_id=ic.id
								WHERE installer_id=$installer_id")->fetchAll('assoc');
			/*$InstallersList       = $connection->execute("select installer_id from installer_category_mapping where category_id='".$installergoryMapping[0]['id']."'")->fetchAll('assoc');
			$arrInstallers          = array();
			$arrApplication         = array();
			if(!empty($InstallersList))
			{
				foreach($InstallersList as $ins)
				{
					$arrInstallers[]    = $ins['installer_id'];
				}
			}

			$ApplicationList        = $connection->execute("SELECT apply_onlines.id FROM `apply_onlines`
				LEFT JOIN apply_online_approvals ON apply_online_approvals.application_id = apply_onlines.id
				WHERE apply_onlines.installer_id in (".implode(",",$arrInstallers).") AND apply_online_approvals.stage = '".$apply_approval_table->APPLICATION_SUBMITTED."' GROUP by apply_online_approvals.application_id")->fetchAll('assoc');
				
			if(!empty($ApplicationList))
			{
				foreach($ApplicationList as $app)
				{
					$arrApplication[]   = $app['id'];
				}
			}
		   $ApplicationID          = "SELECT SUM(apply_onlines.pv_capacity) as total_pv_capacity
				from apply_onlines 
				WHERE apply_onlines.id In (".implode(",",$arrApplication).") $where_query $where_query_data";*/
			
			$ApplicationID          = "SELECT SUM(apply_onlines.pv_capacity) as total_pv_capacity
			from apply_onlines
			INNER JOIN installer_category_mapping ON installer_category_mapping.installer_id = apply_onlines.installer_id
			INNEr JOIN apply_online_approvals ON apply_online_approvals.application_id= apply_onlines.id
			WHERE installer_category_mapping.category_id = ".$installergoryMapping[0]['id']." and apply_online_approvals.stage=".$apply_approval_table->APPLICATION_SUBMITTED." $where_query $where_query_data";
			$installerData              = $connection->execute($ApplicationID)->fetchAll('assoc');
			$total_MW_capacity          = $installerData[0]['total_pv_capacity'];
			$InstallerCategoryMapping   = TableRegistry::get('InstallerCategoryMapping');
			
			$allocated_capacity         = $installergoryMapping[0]['capacity'];//$installerData[0]['capacity'];
			$allocated_id               = $installergoryMapping[0]['id'];//$installerData[0]['id'];
			//echo $allocated_id;
			//echo $allocated_id;
			$flag_check_social          = 0;
			$flag_check_res             = 0;
			if(!empty($total_MW_capacity) && $total_MW_capacity>0 && $allocated_id!=3)
			{
				if((isset($this->data['ApplyOnlines']['social_consumer']) && $this->data['ApplyOnlines']['social_consumer']=='1') || $social_consumer==1)
				{
					$capacity_allowed   = ($allocated_capacity*SOCIAL_CAT_PER)/100;
					$flag_check_social  = 1;
				}
				else
				{
					$checked_category = $category_pass_id;
					if(isset($this->data['ApplyOnlines']['category']) && !empty($this->data['ApplyOnlines']['category']))
					{
						$checked_category = $this->data['ApplyOnlines']['category'];
					}
					if($checked_category==$this->category_residental)
					{
						$flag_check_res = 1;
					}
					$approval_table     = TableRegistry::get('ApplyOnlineApprovals');
					$approval_stages    = $approval_table->Approvalstage($pass_application_id);
					if(in_array($approval_table->APPLICATION_SUBMITTED,$approval_stages))
					{
						if($current_app_data->pv_capacity>=$value)
						{
							return true;
						}
						else
						{
							return "PV capacity must be less than or equals to ".$current_app_data->pv_capacity;
						}
					}
				   if($allocated_id==2)
					{
						if(isset($pass_application_id) && !empty($pass_application_id))
						{
							$wating_list        = TableRegistry::get('WaitinglistApplications');
							$waiting_details    = $wating_list->find('all',array('conditions'=>array('id'=>$pass_application_id)))->toArray();
							if(!empty($waiting_details))
							{
								return true;
							}
							
						}
						 /*$ApplicationID          = "SELECT SUM(apply_onlines.pv_capacity) as total_pv_capacity from apply_onlines 
						WHERE apply_onlines.id In (SELECT apply_onlines.id FROM `apply_onlines`
						LEFT JOIN apply_online_approvals ON apply_online_approvals.application_id = apply_onlines.id
						WHERE apply_onlines.installer_id in ('".$installer_id."')  AND apply_online_approvals.stage IN ('".$apply_approval_table->APPROVED_FROM_GEDA."') GROUP by apply_online_approvals.application_id) and category='".$this->category_residental."' $where_query_data";
						$installerData          = $connection->execute($ApplicationID)->fetchAll('assoc');
						$total_MW_capacity      = $installerData[0]['total_pv_capacity'];
						$capacity_allowed       = RESIDENTIAL_TOTAL_CAPACITY; */

					}
					/*else
					{*/
						$capacity_allowed           = ($allocated_capacity*RESIDENTIAL_CAT_PER)/100;
					//}
				}
				$checked_capacity           = (($capacity_allowed));
				//&& !empty($pass_application_id) && $pass_application_id>APPLICATION_ID_START
				if(isset($category_pass_id) && $category_pass_id == $this->category_residental && ($value<MINIMUM_CAPACITY || $value>MAXIMUM_CAPACITY) && strtotime(DATE_STOP_1_1_3) >= strtotime(date('Y-m-d H:i:s')))
				{
					return "PV capacity must be between ".MINIMUM_CAPACITY." and ".MAXIMUM_CAPACITY.".";
				}
				elseif(($total_MW_capacity+$value) > $checked_capacity && strtotime(DATE_STOP_1_1_3) < strtotime(date('Y-m-d H:i:s')))
				{
					$error_msg = '';
					if($flag_check_res==1)
					{
						$error_msg= 'for Residential';
					}
					if($flag_check_social==1)
					{
						$error_msg= 'for Social Consumer';
					}
					if($flag_check_social==0 && $flag_check_res==0)
					{
						return true;
					}
					return "Your PV capacity $error_msg quota has been finished. Kindly contact GEDA office for further detail.";
					  
				}
				else
				{
					//&& in_array($_SERVER['REMOTE_ADDR'],array("203.88.138.46","103.251.217.139","27.61.246.91"))&& $allocated_id=='1'
					/*if(isset($this->data['ApplyOnlines']['category']) && $this->data['ApplyOnlines']['category'] == $this->category_residental && ($this->data['ApplyOnlines']['pv_capacity']<MINIMUM_CAPACITY || $this->data['ApplyOnlines']['pv_capacity']>MAXIMUM_CAPACITY) && isset($pass_application_id) && !empty($pass_application_id) && $pass_application_id>APPLICATION_ID_START)
					{
						return "PV capacity must be between ".MINIMUM_CAPACITY." and ".MAXIMUM_CAPACITY.".";
					}*/
				}
			}  
		}
		return true;
		//return false;
	}
	/**
	*
	* check_consumer_block
	*
	* Behaviour : public
	*
	* Parameter : discom
	*
	* @defination : Method is used to check consumer number in block list or available.
	*
	*/
	public function check_consumer_block($consumer_no, $discom_d='',$category='')
	{
		return true;
		//$last_data = substr($value,-6);
		//$arr_result= $this->find('all',array('conditions'=>array("consumer_no like "=>'%'.$last_data)))->toArray();
		$arr_condition = array("consumer_no" => $consumer_no);
		if(isset($discom_d) && !empty($discom_d) && ($discom_d== $this->torent_ahmedabad || $discom_d== $this->torent_surat))
		{
			$arr_condition = array("consumer_no" => $consumer_no,"category" => $category);
		}

		$blockList      = TableRegistry::get('BlockList');
		$arr_result     = $blockList->find('all',array('conditions'=>$arr_condition))->toArray();
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
	* FindByApplicationNo
	*
	* Behaviour : public
	*
	* Parameter : $application_search_no
	*
	* @defination : Method is used to check application status.
	*
	*/
	public function FindByApplicationNo($geda_application_no='',$geda_consumer_no='', $geda_mobile_no='', $order_by_form="ApplyOnlines.modified|DESC") 
	{
		$apply_approval_table   = TableRegistry::get('ApplyOnlineApprovals');
		$fields             = ['ApplyOnlines.id',
								'ApplyOnlines.customer_id',
								'ApplyOnlines.installer_id',
								'ApplyOnlines.disclaimer',
								'ApplyOnlines.customer_name_prefixed',
								'ApplyOnlines.customer_name',
								'name_of_consumer_applicant' => "CONCAT(ApplyOnlines.name_of_consumer_applicant, ' ', ApplyOnlines.last_name, ' ', ApplyOnlines.third_name)",
								'ApplyOnlines.address1',
								'ApplyOnlines.address2',
								'ApplyOnlines.comunication_address',
								'ApplyOnlines.city',
								'ApplyOnlines.state',
								'ApplyOnlines.pincode',
								'ApplyOnlines.mobile',
								'ApplyOnlines.landline_no',
								'ApplyOnlines.aadhar_no_or_pan_card_no',
								'ApplyOnlines.attach_photo_scan_of_aadhar',
								'ApplyOnlines.pan_card_no',
								'ApplyOnlines.attach_pan_card_scan',
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
								'ApplyOnlines.email',
								'ApplyOnlines.discom_name',
								'ApplyOnlines.consumer_no',
								'ApplyOnlines.roof_of_proposed',
								'ApplyOnlines.created',
								'ApplyOnlines.application_status',
								'ApplyOnlines.member_assign_id',
								'customers.name',
								'ApplyOnlines.area',
								'ApplyOnlines.circle',
								'ApplyOnlines.division',
								'ApplyOnlines.subdivision',
								'ApplyOnlines.section',
								'ApplyOnlines.payment_status',
								'ApplyOnlines.payment_mode',
								'ApplyOnlines.apply_state',
								'ApplyOnlines.application_no',
								'ApplyOnlines.project_id',
								'ApplyOnlines.geda_application_no',
								'ApplyOnlines.disclaimer_subsidy',
								'installer.installer_name',
								'project.name',
								'ApplyOnlines.query_sent',
								'ApplyOnlines.query_date',
								'ApplyOnlines.location_proposed',
								'ApplyOnlines.discom',
								'ApplyOnlines.net_meter',
								'ApplyOnlines.district',
								'ApplyOnlines.govt_agency',
								'ApplyOnlines.owned_rented',
								'ApplyOnlines.modified',
								'ApplyOnlines.capexmode',
								'ApplyOnlines.gstno',
								"submitted_date"=>"(( CASE WHEN 1 = 1 THEN( select created FROM apply_online_approvals 
								WHERE 
								  apply_online_approvals.application_id = ApplyOnlines.id 
								  AND apply_online_approvals.stage = '".$apply_approval_table->APPLICATION_SUBMITTED."' 
								group by 
								  stage) END ))"
							  ];
		$arrOrderBy     = explode("|",$order_by_form);
		$condition_arr  = array();
		if(!empty($geda_application_no)) {
			array_push($condition_arr,array('ApplyOnlines.geda_application_no'=>$geda_application_no));
		} else {
			array_push($condition_arr,array('ApplyOnlines.geda_application_no'=>'empty_geda_no'));
		}
		if(!empty($geda_consumer_no)) {
			array_push($condition_arr,array('ApplyOnlines.consumer_no'=>$geda_consumer_no));
		} else {
			array_push($condition_arr,array('ApplyOnlines.consumer_no'=>'empty_consumer_no'));
		}
		if(!empty($geda_mobile_no)) {
			array_push($condition_arr,array('ApplyOnlines.consumer_mobile'=>$geda_mobile_no));
		} else {
			array_push($condition_arr,array('ApplyOnlines.consumer_mobile'=>'empty_mobile_no'));
		}
		$join_arr = [   
						['table'=>'installers','alias'=>'installer','type'=>'left','conditions'=>'installer.id = ApplyOnlines.installer_id'],
						['table'=>'customers','type'=>'left','conditions'=>'customers.id = ApplyOnlines.customer_id'],
						['table'=>'projects','alias'=>'project','type'=>'left','conditions'=>'ApplyOnlines.project_id = project.id'],
						['table'=>'fesibility_report','alias'=>'fea','type'=>'left','conditions'=>'ApplyOnlines.id = fea.application_id']
					];
		$ApplyOnlinesList   = $this->find("all",['fields' => $fields,'join'   => $join_arr,'conditions'=>$condition_arr])->first();
		return $ApplyOnlinesList;
	}

	/**
	*
	* GetApplicationSummaryDetails
	*
	* Behaviour : public
	*
	* Parameter : application_ids
	*
	* @defination : Method is used to get application summary.
	*
	*/
	public function GetApplicationSummaryDetails($application_ids="")
	{
		if (empty($application_ids)) return false;

		$application_ids = explode(",",$application_ids);

		$arrResult = array();

		$fields = [ 'ApplyOnlines.id',
					'name_of_consumer_applicant'=> "CONCAT(ApplyOnlines.name_of_consumer_applicant, ' ', ApplyOnlines.last_name, ' ', ApplyOnlines.third_name)",
					'ApplyOnlines.address1',
					'ApplyOnlines.address2',
					'ApplyOnlines.city',
					'ApplyOnlines.state',
					'ApplyOnlines.pincode',
					'ApplyOnlines.mobile',
					'ApplyOnlines.consumer_mobile',
					'ApplyOnlines.landline_no',
					'ApplyOnlines.aadhar_no_or_pan_card_no',
					'ApplyOnlines.attach_photo_scan_of_aadhar',
					'ApplyOnlines.attach_pan_card_scan',
					'ApplyOnlines.pan_card_no',
					'ApplyOnlines.attach_recent_bill',
					'ApplyOnlines.house_tax_holding_no',
					'ApplyOnlines.attach_latest_receipt',
					'ApplyOnlines.pv_capacity',
					'ApplyOnlines.email',
					'ApplyOnlines.discom_name',
					'ApplyOnlines.discom',
					'ApplyOnlines.consumer_no',
					'ApplyOnlines.geda_application_no',
					'ApplyOnlines.pcr_code',
					'ApplyOnlines.pcr_submited',
					'ApplyOnlines.approval_id',
					'ApplyOnlines.social_consumer',
					'ApplyOnlines.common_meter',
					'customers.name',
					'customers.email',
					'installer.installer_name',
					'parameter_cats.para_value',
					'branch_masters.title',
					'geda_registration_date'=>'apply_online_approvals.created',
					'project_installation.modules_data',
					'project_installation.inverter_data',
					'project_installation.meter_manufacture',
					'project_installation.meter_serial_no',
					'project_installation.solar_meter_manufacture',
					'project_installation.solar_meter_serial_no',
					'project_installation.bi_date',
					'project_installation.agreement_date',
					'fesibility_report.payment_date',
					'apply_onlines_subsidy.cei_licence_no',
					'apply_onlines_subsidy.cei_authorised_by',
					'apply_onlines_subsidy.cei_licence_expiry_date',
					'apply_onlines_subsidy.cei_self_certification_date',
					'apply_onlines_subsidy.cei_contractor',
					'apply_onlines_subsidy.cei_superviser',
					'apply_onlines_subsidy.signing_authority',
					'apply_onlines_subsidy.modules_data',
					'apply_onlines_subsidy.inverter_data',
					'apply_onlines_subsidy.comm_date',
					'projects.estimated_cost',
					'projects.recommended_capacity',
					'projects.state',
					'projects.customer_type',
					'subsidy_claim_requests.request_no',
			];
		$applyOnlinesData   = $this->find('all',[
			'fields'=>$fields,
			'join'=>[   
						['table'=>'installers','alias'=>'installer','type'=>'left','conditions'=>'installer.id = ApplyOnlines.installer_id'],
						['table'=>'customers','type'=>'left','conditions'=>'customers.id = ApplyOnlines.customer_id'],
						['table'=>'branch_masters','type'=>'left','conditions'=>'branch_masters.id = ApplyOnlines.discom'],
						['table'=>'fesibility_report','type'=>'left','conditions'=>'fesibility_report.application_id = ApplyOnlines.id'],
						['table'=>'project_installation','type'=>'left','conditions'=>'project_installation.project_id = ApplyOnlines.project_id'],
						['table'=>'apply_online_approvals','type'=>'left','conditions'=>['apply_online_approvals.application_id = ApplyOnlines.id','apply_online_approvals.stage = 31']],
						['table'=>'apply_onlines_subsidy','type'=>'left','conditions'=>'apply_onlines_subsidy.application_id = ApplyOnlines.id'],
						['table'=>'projects','type'=>'left','conditions'=>'ApplyOnlines.project_id = projects.id'],
						['table'=>'parameters','alias'=>'parameter_cats','type'=>'left','conditions'=>'parameter_cats.para_id = ApplyOnlines.category'],
						['table'=>'subsidy_claim_request_applications','conditions'=>'subsidy_claim_request_applications.application_id = ApplyOnlines.id','type'=>'left'],
						['table'=>'subsidy_claim_requests','conditions'=>'subsidy_claim_request_applications.request_id = subsidy_claim_requests.id','type'=>'left']
					],
			'conditions'=>['ApplyOnlines.id IN '=>$application_ids]
			])->toArray();
		if (!empty($applyOnlinesData)) 
		{
			$Projects               = TableRegistry::get('Projects');
			$ApplyonlinDocs         = TableRegistry::get('ApplyonlinDocs');
			foreach($applyOnlinesData as $application)
			{
				$Applyonlinprofile      = $ApplyonlinDocs->find('all',['conditions'=>['application_id'=>$application['id'],'doc_type'=>'profile']])->first();
				$Profile_Photo_Url      = "";
				$ProfileFileName      	= "";
				$DOCUMENT_PATH          = WWW_ROOT.APPLYONLINE_PATH.($application['id']).'/';
				if(!empty($Applyonlinprofile)) 
				{
					$IMAGE_PATH = $DOCUMENT_PATH.$Applyonlinprofile['file_name'];
					$Couchdb 	= TableRegistry::get('Couchdb');
					if (!empty($Applyonlinprofile['file_name']) && $Couchdb->documentExist($application['id'],$Applyonlinprofile['file_name'])) 
					{
						/*$ext                = pathinfo($IMAGE_PATH, PATHINFO_EXTENSION);
						$converted_filename = $DOCUMENT_PATH.$application['id']."_profile_photo.jpg";
						if (!file_exists($converted_filename)) 
						{
							if ($ext == "png" || $ext == "gif" || $ext == "jpeg")
							{
								//new file name once the picture is converted
								if ($ext=="png") $new_pic = imagecreatefrompng($IMAGE_PATH);
								if ($ext=="gif") $new_pic = imagecreatefromgif($IMAGE_PATH);
								if ($ext=="jpeg") $new_pic = imagecreatefromjpeg($IMAGE_PATH);

								// Create a new true color image with the same size
								$w = imagesx($new_pic);
								$h = imagesy($new_pic);
								$white = imagecreatetruecolor($w, $h);

								// Fill the new image with white background
								$bg = imagecolorallocate($white, 255, 255, 255);
								imagefill($white, 0, 0, $bg);

								// Copy original transparent image onto the new image
								imagecopy($white, $new_pic, 0, 0, 0, 0, $w, $h);

								$new_pic = $white;
								imagejpeg($new_pic, $converted_filename);
								imagedestroy($new_pic);
							} else {
								$converted_filename = $IMAGE_PATH;
							}
						}*/
						$apply_profile_data = $Couchdb->find('all',array('conditions'=>array('id'=>$Applyonlinprofile->couchdb_id)))->first();
						require_once(ROOT . DS . 'vendor' . DS . 'couchdb' . DS . 'couchdb.php');
						$COUCHDB 			= new Couchdb();
						$documentProfile	= $COUCHDB->getDocument($apply_profile_data->document_id,$apply_profile_data->file_attached,$apply_profile_data->doc_mime_type,1);
						$Profile_Photo_Url 	= $documentProfile;
						$ProfileFileName 	= $Applyonlinprofile['file_name'];
					}
				}
				$subsidy_data = $Projects->calculatecapitalcostwithsubsidy($application['projects']['recommended_capacity'],$application['projects']['estimated_cost'],$application['projects']['state'],$application['projects']['customer_type'],true,$application['social_consumer']);
				if($application['social_consumer']==1 || $application['common_meter']==1)
				{
					$subsidy_data['state_subsidy_amount']=0;
				}
				$application['projects']['subsidy_details'] = $subsidy_data;
				$application['Profile_Photo_Url'] 			= $Profile_Photo_Url;
				$application['ProfileFileName'] 			= $ProfileFileName;
				$arrResult[] = $application;
			}
		}
		return $arrResult;
	}
	public function TotalApplicationBySubsidy($state = '',$main_branch_id='',$disclaimer_subsidy=1) 
	{
		$ApplyOnlines = $this->find();
		$ApplyOnlines->hydrate(false);
		$apply_approval_table   = TableRegistry::get('ApplyOnlineApprovals');
		if ($main_branch_id['member_type'] == $this->DISCOM)
		{
			$ApplyOnlines->select(['TotalCount' => $ApplyOnlines->func()->count(0)])
			->join([
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.apply_state'
						],
						[
							'table'=>'apply_online_approvals',
							'type'=>'left',
							'conditions'=>'apply_online_approvals.application_id = ApplyOnlines.id'
						]
					]
				)
			->where([   'states.id' => $state,
						'ApplyOnlines.disclaimer_subsidy' => $disclaimer_subsidy,
						'apply_online_approvals.stage IN' => array($apply_approval_table->APPLICATION_SUBMITTED),
						'ApplyOnlines.'.$main_branch_id['field']=>$main_branch_id['id']]);
		} else if ($main_branch_id['member_type'] == "CUSTOMER" || $main_branch_id['member_type'] == "INSTALLER") {
			$ApplyOnlines->select(['TotalCount' => $ApplyOnlines->func()->count(0)])
			->join([
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.apply_state'
						],
						[
							'table'=>'apply_online_approvals',
							'type'=>'left',
							'conditions'=>'apply_online_approvals.application_id = ApplyOnlines.id'
						]
					]
				)
			->where([   'states.id' => $state,
						'ApplyOnlines.disclaimer_subsidy'=>$disclaimer_subsidy,
						'apply_online_approvals.stage IN' => array($apply_approval_table->APPLICATION_SUBMITTED),
						'ApplyOnlines.'.$main_branch_id['field']=>$main_branch_id['id']]);
		} else {
			$ApplyOnlines->select(['TotalCount' => $ApplyOnlines->func()->count(0)])
			->join([
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.apply_state'
						],
						[
							'table'=>'apply_online_approvals',
							'type'=>'left',
							'conditions'=>'apply_online_approvals.application_id = ApplyOnlines.id'
						]
					]
				)
			->where(['states.id' => $state,'apply_online_approvals.stage IN' => array($apply_approval_table->APPLICATION_SUBMITTED),'ApplyOnlines.disclaimer_subsidy'=>$disclaimer_subsidy]);
		}
	   
		$ApplyOnlines->where(['ApplyOnlines.application_status != ' => $apply_approval_table->APPLICATION_CANCELLED]);
		$resultArray = $ApplyOnlines->toList();
		$TotalCount  = isset($resultArray[0]['TotalCount'])?$resultArray[0]['TotalCount']:0;
		return $TotalCount;
	}
	public function TotalApplicationNonSubsidyPVCapacity($state = '',$main_branch_id='',$disclaimer_subsidy=1) 
	{
		$ApplyOnlines = $this->find();
		$ApplyOnlines->hydrate(false);
		$apply_approval_table   = TableRegistry::get('ApplyOnlineApprovals');
		if ($main_branch_id['member_type'] == $this->DISCOM)
		{
			$ApplyOnlines->select(['TotalCapacity' => $ApplyOnlines->func()->sum('ApplyOnlines.pv_capacity')])
			->join([
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.apply_state'
						],
						[
							'table'=>'apply_online_approvals',
							'type'=>'left',
							'conditions'=>'apply_online_approvals.application_id = ApplyOnlines.id'
						]
					]
				)
			->where([   'states.id' => $state,
						'ApplyOnlines.disclaimer_subsidy' => $disclaimer_subsidy,
						'apply_online_approvals.stage IN' => array($apply_approval_table->APPLICATION_SUBMITTED),
						'ApplyOnlines.'.$main_branch_id['field']=>$main_branch_id['id']]);
		} else if ($main_branch_id['member_type'] == "CUSTOMER" || $main_branch_id['member_type'] == "INSTALLER") {
			$ApplyOnlines->select(['TotalCapacity' => $ApplyOnlines->func()->sum('ApplyOnlines.pv_capacity')])
			->join([
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.apply_state'
						],
						[
							'table'=>'apply_online_approvals',
							'type'=>'left',
							'conditions'=>'apply_online_approvals.application_id = ApplyOnlines.id'
						]
					]
				)
			->where([   'states.id' => $state,
						'ApplyOnlines.disclaimer_subsidy' => $disclaimer_subsidy,
						'apply_online_approvals.stage IN' => array($apply_approval_table->APPLICATION_SUBMITTED),
						'ApplyOnlines.'.$main_branch_id['field']=>$main_branch_id['id']]);
		} else {
			$ApplyOnlines->select(['TotalCapacity' => $ApplyOnlines->func()->sum('ApplyOnlines.pv_capacity')])
			->join([
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.apply_state'
						],
						[
							'table'=>'apply_online_approvals',
							'type'=>'left',
							'conditions'=>'apply_online_approvals.application_id = ApplyOnlines.id'
						]
					]
				)
			->where(['states.id' => $state,'apply_online_approvals.stage IN' => array($apply_approval_table->APPLICATION_SUBMITTED),'ApplyOnlines.disclaimer_subsidy'=>$disclaimer_subsidy]);
		}
		$ApplyOnlines->where(['ApplyOnlines.application_status != ' => $apply_approval_table->APPLICATION_CANCELLED]);
		$resultArray    = $ApplyOnlines->toList();
		$TotalCapacity  = isset($resultArray[0]['TotalCapacity'])?$resultArray[0]['TotalCapacity']:0;
		return (!empty($TotalCapacity)?_FormatGroupNumberV2($TotalCapacity):0);
	}
	/**
	 * TotalApplicationByPCR
	 * Behaviour : public
	 * @param : $state, $main_branch_id
	 * @defination : Method is use to find total application of pcr submitted
	 */
	public function TotalApplicationByPCR($state = '',$main_branch_id='') 
	{
		$ApplyOnlines = $this->find();
		$ApplyOnlines->hydrate(false);
		$apply_approval_table   = TableRegistry::get('ApplyOnlineApprovals');
		if ($main_branch_id['member_type'] == $this->DISCOM)
		{
			$ApplyOnlines->select(['TotalCount' => $ApplyOnlines->func()->count(0)])
			->join([
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.apply_state'
						],
						[
							'table'=>'apply_online_approvals',
							'type'=>'left',
							'conditions'=>'apply_online_approvals.application_id = ApplyOnlines.id'
						]
					]
				)
			->where([   'states.id' => $state,
						'ApplyOnlines.pcr_code IS NOT NULL',
						'apply_online_approvals.stage IN' => array($apply_approval_table->APPLICATION_SUBMITTED),
						'ApplyOnlines.'.$main_branch_id['field']=>$main_branch_id['id']]);
		} else if ($main_branch_id['member_type'] == "CUSTOMER" || $main_branch_id['member_type'] == "INSTALLER") {
			$ApplyOnlines->select(['TotalCount' => $ApplyOnlines->func()->count(0)])
			->join([
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.apply_state'
						],
						[
							'table'=>'apply_online_approvals',
							'type'=>'left',
							'conditions'=>'apply_online_approvals.application_id = ApplyOnlines.id'
						]
					]
				)
			->where([   'states.id' => $state,
						'ApplyOnlines.pcr_code IS NOT NULL',
						'apply_online_approvals.stage IN' => array($apply_approval_table->APPLICATION_SUBMITTED),
						'ApplyOnlines.'.$main_branch_id['field']=>$main_branch_id['id']]);
		} else {
			$ApplyOnlines->select(['TotalCount' => $ApplyOnlines->func()->count(0)])
			->join([
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.apply_state'
						],
						[
							'table'=>'apply_online_approvals',
							'type'=>'left',
							'conditions'=>'apply_online_approvals.application_id = ApplyOnlines.id'
						]
					]
				)
			->where(['states.id' => $state,'apply_online_approvals.stage IN' => array($apply_approval_table->APPLICATION_SUBMITTED),'ApplyOnlines.pcr_code IS NOT NULL']);
		}
	   
		$ApplyOnlines->where(['ApplyOnlines.application_status != ' => $apply_approval_table->APPLICATION_CANCELLED]);

		$resultArray = $ApplyOnlines->toList();
		$TotalCount  = isset($resultArray[0]['TotalCount'])?$resultArray[0]['TotalCount']:0;
		return $TotalCount;
	}
	/**
	 * TotalApplicationPcrPVCapacity
	 * Behaviour : public
	 * @param : $state, $main_branch_id
	 * @defination : Method is use to find total pv capacity of pcr submitted
	 */
	public function TotalApplicationPcrPVCapacity($state = '',$main_branch_id='') 
	{
		$ApplyOnlines = $this->find();
		$ApplyOnlines->hydrate(false);
		$apply_approval_table   = TableRegistry::get('ApplyOnlineApprovals');
		if ($main_branch_id['member_type'] == $this->DISCOM)
		{
			$ApplyOnlines->select(['TotalCapacity' => $ApplyOnlines->func()->sum('ApplyOnlines.pv_capacity')])
			->join([
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.apply_state'
						],
						[
							'table'=>'apply_online_approvals',
							'type'=>'left',
							'conditions'=>'apply_online_approvals.application_id = ApplyOnlines.id'
						]
					]
				)
			->where([   'states.id' => $state,
						'ApplyOnlines.pcr_code IS NOT NULL',
						'apply_online_approvals.stage IN' => array($apply_approval_table->APPLICATION_SUBMITTED),
						'ApplyOnlines.'.$main_branch_id['field']=>$main_branch_id['id']]);
		} else if ($main_branch_id['member_type'] == "CUSTOMER" || $main_branch_id['member_type'] == "INSTALLER") {
			$ApplyOnlines->select(['TotalCapacity' => $ApplyOnlines->func()->sum('ApplyOnlines.pv_capacity')])
			->join([
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.apply_state'
						],
						[
							'table'=>'apply_online_approvals',
							'type'=>'left',
							'conditions'=>'apply_online_approvals.application_id = ApplyOnlines.id'
						]
					]
				)
			->where([   'states.id' => $state,
						'ApplyOnlines.pcr_code IS NOT NULL',
						'apply_online_approvals.stage IN' => array($apply_approval_table->APPLICATION_SUBMITTED),
						'ApplyOnlines.'.$main_branch_id['field']=>$main_branch_id['id']]);
		} else {
			$ApplyOnlines->select(['TotalCapacity' => $ApplyOnlines->func()->sum('ApplyOnlines.pv_capacity')])
			->join([
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.apply_state'
						],
						[
							'table'=>'apply_online_approvals',
							'type'=>'left',
							'conditions'=>'apply_online_approvals.application_id = ApplyOnlines.id'
						]
					]
				)
			->where(['states.id' => $state,'apply_online_approvals.stage IN' => array($apply_approval_table->APPLICATION_SUBMITTED),'ApplyOnlines.pcr_code IS NOT NULL']);
		}
		$ApplyOnlines->where(['ApplyOnlines.application_status != ' => $apply_approval_table->APPLICATION_CANCELLED]);
		$resultArray    = $ApplyOnlines->toList();
		$TotalCapacity  = isset($resultArray[0]['TotalCapacity'])?$resultArray[0]['TotalCapacity']:0;
		return (!empty($TotalCapacity)?_FormatGroupNumberV2($TotalCapacity):0);
	}
	/**
	 * TotalApplicationBySocial
	 * Behaviour : public
	 * @param : $state, $main_branch_id
	 * @defination : Method is use to find total application of social sector
	 */
	public function TotalApplicationBySocial($state = '',$main_branch_id='') 
	{
		$ApplyOnlines = $this->find();
		$ApplyOnlines->hydrate(false);
		$apply_approval_table   = TableRegistry::get('ApplyOnlineApprovals');
		if ($main_branch_id['member_type'] == $this->DISCOM)
		{
			$ApplyOnlines->select(['TotalCount' => $ApplyOnlines->func()->count(0)])
			->join([
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.apply_state'
						],
						[
							'table'=>'apply_online_approvals',
							'type'=>'left',
							'conditions'=>'apply_online_approvals.application_id = ApplyOnlines.id'
						]
					]
				)
			->where([   'states.id' => $state,
						'ApplyOnlines.social_consumer' => '1',
						'apply_online_approvals.stage IN' => array($apply_approval_table->APPROVED_FROM_GEDA),
						'ApplyOnlines.'.$main_branch_id['field']=>$main_branch_id['id']]);
		} else if ($main_branch_id['member_type'] == "CUSTOMER" || $main_branch_id['member_type'] == "INSTALLER") {
			$ApplyOnlines->select(['TotalCount' => $ApplyOnlines->func()->count(0)])
			->join([
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.apply_state'
						],
						[
							'table'=>'apply_online_approvals',
							'type'=>'left',
							'conditions'=>'apply_online_approvals.application_id = ApplyOnlines.id'
						]
					]
				)
			->where([   'states.id' => $state,
						'ApplyOnlines.social_consumer' => '1',
						'apply_online_approvals.stage IN' => array($apply_approval_table->APPLICATION_SUBMITTED),
						'ApplyOnlines.'.$main_branch_id['field']=>$main_branch_id['id']]);
		} else {
			$ApplyOnlines->select(['TotalCount' => $ApplyOnlines->func()->count(0)])
			->join([
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.apply_state'
						],
						[
							'table'=>'apply_online_approvals',
							'type'=>'left',
							'conditions'=>'apply_online_approvals.application_id = ApplyOnlines.id'
						]
					]
				)
			->where(['states.id' => $state,'apply_online_approvals.stage IN' => array($apply_approval_table->APPLICATION_SUBMITTED),'ApplyOnlines.social_consumer' => '1']);
		}
	   
		$ApplyOnlines->where(['ApplyOnlines.application_status != ' => $apply_approval_table->APPLICATION_CANCELLED]);
		$resultArray = $ApplyOnlines->toList();
		$TotalCount  = isset($resultArray[0]['TotalCount'])?$resultArray[0]['TotalCount']:0;
		return $TotalCount;
	}
	/**
	 * TotalApplicationSocialPVCapacity
	 * Behaviour : public
	 * @param : $state, $main_branch_id
	 * @defination : Method is use to find total pv capacity of social sector
	 */
	public function TotalApplicationSocialPVCapacity($state = '',$main_branch_id='') 
	{
		$ApplyOnlines = $this->find();
		$ApplyOnlines->hydrate(false);
		$apply_approval_table   = TableRegistry::get('ApplyOnlineApprovals');
		if ($main_branch_id['member_type'] == $this->DISCOM)
		{
			$ApplyOnlines->select(['TotalCapacity' => $ApplyOnlines->func()->sum('ApplyOnlines.pv_capacity')])
			->join([
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.apply_state'
						],
						[
							'table'=>'apply_online_approvals',
							'type'=>'left',
							'conditions'=>'apply_online_approvals.application_id = ApplyOnlines.id'
						]
					]
				)
			->where([   'states.id' => $state,
						'ApplyOnlines.social_consumer' => '1',
						'apply_online_approvals.stage IN' => array($apply_approval_table->APPROVED_FROM_GEDA),
						'ApplyOnlines.'.$main_branch_id['field']=>$main_branch_id['id']]);
		} else if ($main_branch_id['member_type'] == "CUSTOMER" || $main_branch_id['member_type'] == "INSTALLER") {
			$ApplyOnlines->select(['TotalCapacity' => $ApplyOnlines->func()->sum('ApplyOnlines.pv_capacity')])
			->join([
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.apply_state'
						],
						[
							'table'=>'apply_online_approvals',
							'type'=>'left',
							'conditions'=>'apply_online_approvals.application_id = ApplyOnlines.id'
						]
					]
				)
			->where([   'states.id' => $state,
						'ApplyOnlines.social_consumer' => '1',
						'apply_online_approvals.stage IN' => array($apply_approval_table->APPLICATION_SUBMITTED),
						'ApplyOnlines.'.$main_branch_id['field']=>$main_branch_id['id']]);
		} else {
			$ApplyOnlines->select(['TotalCapacity' => $ApplyOnlines->func()->sum('ApplyOnlines.pv_capacity')])
			->join([
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.apply_state'
						],
						[
							'table'=>'apply_online_approvals',
							'type'=>'left',
							'conditions'=>'apply_online_approvals.application_id = ApplyOnlines.id'
						]
					]
				)
			->where(['states.id' => $state,'apply_online_approvals.stage IN' => array($apply_approval_table->APPLICATION_SUBMITTED),'ApplyOnlines.social_consumer' => '1']);
		}
		$ApplyOnlines->where(['ApplyOnlines.application_status != ' => $apply_approval_table->APPLICATION_CANCELLED]);
		$resultArray    = $ApplyOnlines->toList();
		$TotalCapacity  = isset($resultArray[0]['TotalCapacity'])?$resultArray[0]['TotalCapacity']:0;
		return (!empty($TotalCapacity)?_FormatGroupNumberV2($TotalCapacity):0);
	}
	/**
	 * TotalApplicationByResidential
	 * Behaviour : public
	 * @param : $state, $main_branch_id
	 * @defination : Method is use to find total application of social sector
	 */
	public function TotalApplicationByCategory($state = '',$main_branch_id='',$cateory='') 
	{
		$ApplyOnlines = $this->find();
		$ApplyOnlines->hydrate(false);
		$apply_approval_table   = TableRegistry::get('ApplyOnlineApprovals');
		if ($main_branch_id['member_type'] == $this->DISCOM)
		{
			$ApplyOnlines->select(['TotalCount' => $ApplyOnlines->func()->count(0)])
			->join([
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.apply_state'
						],
						[
							'table'=>'apply_online_approvals',
							'type'=>'left',
							'conditions'=>'apply_online_approvals.application_id = ApplyOnlines.id'
						]
					]
				)
			->where([   'states.id' => $state,
						'apply_online_approvals.stage IN' => array($apply_approval_table->APPROVED_FROM_GEDA),
						'ApplyOnlines.'.$main_branch_id['field']=>$main_branch_id['id']]);
		} else if ($main_branch_id['member_type'] == "CUSTOMER" || $main_branch_id['member_type'] == "INSTALLER") {
			$ApplyOnlines->select(['TotalCount' => $ApplyOnlines->func()->count(0)])
			->join([
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.apply_state'
						],
						[
							'table'=>'apply_online_approvals',
							'type'=>'left',
							'conditions'=>'apply_online_approvals.application_id = ApplyOnlines.id'
						]
					]
				)
			->where([   'states.id' => $state,
						'apply_online_approvals.stage IN' => array($apply_approval_table->APPLICATION_SUBMITTED),
						'ApplyOnlines.'.$main_branch_id['field']=>$main_branch_id['id']]);
		} else {
			$ApplyOnlines->select(['TotalCount' => $ApplyOnlines->func()->count(0)])
			->join([
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.apply_state'
						],
						[
							'table'=>'apply_online_approvals',
							'type'=>'left',
							'conditions'=>'apply_online_approvals.application_id = ApplyOnlines.id'
						]
					]
				)
			->where(['states.id' => $state,'apply_online_approvals.stage IN' => array($apply_approval_table->APPLICATION_SUBMITTED)]);
		}
	   
		$ApplyOnlines->where(['ApplyOnlines.application_status != ' => $apply_approval_table->APPLICATION_CANCELLED]);
		$ApplyOnlines->where(['ApplyOnlines.category = ' => $cateory]);
		$resultArray = $ApplyOnlines->toList();
		$TotalCount  = isset($resultArray[0]['TotalCount'])?$resultArray[0]['TotalCount']:0;
		return $TotalCount;
	}
	/**
	 * TotalApplicationSocialPVCapacity
	 * Behaviour : public
	 * @param : $state, $main_branch_id
	 * @defination : Method is use to find total pv capacity of social sector
	 */
	public function TotalApplicationCategoryCapacity($state = '',$main_branch_id='',$cateory='') 
	{
		$ApplyOnlines = $this->find();
		$ApplyOnlines->hydrate(false);
		$apply_approval_table   = TableRegistry::get('ApplyOnlineApprovals');
		if ($main_branch_id['member_type'] == $this->DISCOM)
		{
			$ApplyOnlines->select(['TotalCapacity' => $ApplyOnlines->func()->sum('ApplyOnlines.pv_capacity')])
			->join([
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.apply_state'
						],
						[
							'table'=>'apply_online_approvals',
							'type'=>'left',
							'conditions'=>'apply_online_approvals.application_id = ApplyOnlines.id'
						]
					]
				)
			->where([   'states.id' => $state,
						'apply_online_approvals.stage IN' => array($apply_approval_table->APPROVED_FROM_GEDA),
						'ApplyOnlines.'.$main_branch_id['field']=>$main_branch_id['id']]);
		} else if ($main_branch_id['member_type'] == "CUSTOMER" || $main_branch_id['member_type'] == "INSTALLER") {
			$ApplyOnlines->select(['TotalCapacity' => $ApplyOnlines->func()->sum('ApplyOnlines.pv_capacity')])
			->join([
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.apply_state'
						],
						[
							'table'=>'apply_online_approvals',
							'type'=>'left',
							'conditions'=>'apply_online_approvals.application_id = ApplyOnlines.id'
						]
					]
				)
			->where([   'states.id' => $state,
						'apply_online_approvals.stage IN' => array($apply_approval_table->APPLICATION_SUBMITTED),
						'ApplyOnlines.'.$main_branch_id['field']=>$main_branch_id['id']]);
		} else {
			$ApplyOnlines->select(['TotalCapacity' => $ApplyOnlines->func()->sum('ApplyOnlines.pv_capacity')])
			->join([
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.apply_state'
						],
						[
							'table'=>'apply_online_approvals',
							'type'=>'left',
							'conditions'=>'apply_online_approvals.application_id = ApplyOnlines.id'
						]
					]
				)
			->where(['states.id' => $state,'apply_online_approvals.stage IN' => array($apply_approval_table->APPLICATION_SUBMITTED)]);
		}
		$ApplyOnlines->where(['ApplyOnlines.application_status != ' => $apply_approval_table->APPLICATION_CANCELLED]);
		$ApplyOnlines->where(['ApplyOnlines.category = ' => $cateory]);
		$resultArray    = $ApplyOnlines->toList();
		$TotalCapacity  = isset($resultArray[0]['TotalCapacity'])?$resultArray[0]['TotalCapacity']:0;
		return (!empty($TotalCapacity)?($TotalCapacity):0);
	}
	/**
	 * TotalApplicationByInspection
	 * Behaviour : public
	 * @param : $state, $main_branch_id
	 * @defination : Method is use to find total application of Inspection done
	 */
	public function TotalApplicationByInspection($state = '',$main_branch_id='') 
	{
		$ApplyOnlines = $this->find();
		$ApplyOnlines->hydrate(false);
		$apply_approval_table   = TableRegistry::get('ApplyOnlineApprovals');
		$Inspectionpdf   = TableRegistry::get('Inspectionpdf')->find();
		$Inspectionpdf->hydrate(false);
		if ($main_branch_id['member_type'] == $this->DISCOM)
		{
			$Inspectionpdf->select(['TotalCount' => $Inspectionpdf->func()->count(0)])
			->join([
						[   'table'=>'apply_onlines',
							'type'=>'left',
							'conditions'=>'Inspectionpdf.application_id = apply_onlines.id'
						],
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = apply_onlines.apply_state'
						],
						[
							'table'=>'apply_online_approvals',
							'type'=>'left',
							'conditions'=>'apply_online_approvals.application_id = apply_onlines.id'
						]
					]
				)
			->where([   'states.id' => $state,
						'apply_online_approvals.stage IN' => array($apply_approval_table->APPROVED_FROM_GEDA),
						'apply_onlines.'.$main_branch_id['field']=>$main_branch_id['id']]);
		} else if ($main_branch_id['member_type'] == "CUSTOMER" || $main_branch_id['member_type'] == "INSTALLER") {
			$Inspectionpdf->select(['TotalCount' => $Inspectionpdf->func()->count(0)])
			->join([
						[   'table'=>'apply_onlines',
							'type'=>'left',
							'conditions'=>'Inspectionpdf.application_id = apply_onlines.id'
						],
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = apply_onlines.apply_state'
						],
						[
							'table'=>'apply_online_approvals',
							'type'=>'left',
							'conditions'=>'apply_online_approvals.application_id = apply_onlines.id'
						]
					]
				)
			->where([   'states.id' => $state,
						'apply_online_approvals.stage IN' => array($apply_approval_table->APPLICATION_SUBMITTED),
						'apply_onlines.'.$main_branch_id['field']=>$main_branch_id['id']]);
		} else {
			$Inspectionpdf->select(['TotalCount' => $Inspectionpdf->func()->count(0)])
			->join([
						[   'table'=>'apply_onlines',
							'type'=>'left',
							'conditions'=>'Inspectionpdf.application_id = apply_onlines.id'
						],
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = apply_onlines.apply_state'
						],
						[
							'table'=>'apply_online_approvals',
							'type'=>'left',
							'conditions'=>'apply_online_approvals.application_id = apply_onlines.id'
						]
					]
				)
			->where(['states.id' => $state,'apply_online_approvals.stage IN' => array($apply_approval_table->APPLICATION_SUBMITTED)]);
		}
	   
		$Inspectionpdf->where(['apply_onlines.application_status != ' => $apply_approval_table->APPLICATION_CANCELLED]);
		
		$resultArray = $Inspectionpdf->toList();
		$TotalCount  = isset($resultArray[0]['TotalCount'])?$resultArray[0]['TotalCount']:0;
		return $TotalCount;
	}
	/**
	 * TotalApplicationInspectionCapacity
	 * Behaviour : public
	 * @param : $state, $main_branch_id
	 * @defination : Method is use to find total pv capacity of Inspection
	 */
	public function TotalApplicationInspectionCapacity($state = '',$main_branch_id='') 
	{
		$ApplyOnlines = $this->find();
		$ApplyOnlines->hydrate(false);
		$apply_approval_table   = TableRegistry::get('ApplyOnlineApprovals');
		$Inspectionpdf   = TableRegistry::get('Inspectionpdf')->find();
		$Inspectionpdf->hydrate(false);
		if ($main_branch_id['member_type'] == $this->DISCOM)
		{
			$Inspectionpdf->select(['TotalCapacity' => $Inspectionpdf->func()->sum('apply_onlines.pv_capacity')])
			->join([
						[   'table'=>'apply_onlines',
							'type'=>'left',
							'conditions'=>'Inspectionpdf.application_id = apply_onlines.id'
						],
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = apply_onlines.apply_state'
						],
						[
							'table'=>'apply_online_approvals',
							'type'=>'left',
							'conditions'=>'apply_online_approvals.application_id = apply_onlines.id'
						]
					]
				)
			->where([   'states.id' => $state,
						'apply_online_approvals.stage IN' => array($apply_approval_table->APPROVED_FROM_GEDA),
						'apply_onlines.'.$main_branch_id['field']=>$main_branch_id['id']]);
		} else if ($main_branch_id['member_type'] == "CUSTOMER" || $main_branch_id['member_type'] == "INSTALLER") {
			$Inspectionpdf->select(['TotalCapacity' => $Inspectionpdf->func()->sum('apply_onlines.pv_capacity')])
			->join([
						[   'table'=>'apply_onlines',
							'type'=>'left',
							'conditions'=>'Inspectionpdf.application_id = apply_onlines.id'
						],
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = apply_onlines.apply_state'
						],
						[
							'table'=>'apply_online_approvals',
							'type'=>'left',
							'conditions'=>'apply_online_approvals.application_id = apply_onlines.id'
						]
					]
				)
			->where([   'states.id' => $state,
						'apply_online_approvals.stage IN' => array($apply_approval_table->APPLICATION_SUBMITTED),
						'apply_onlines.'.$main_branch_id['field']=>$main_branch_id['id']]);
		} else {
			$Inspectionpdf->select(['TotalCapacity' => $Inspectionpdf->func()->sum('apply_onlines.pv_capacity')])
			->join([
						[   'table'=>'apply_onlines',
							'type'=>'left',
							'conditions'=>'Inspectionpdf.application_id = apply_onlines.id'
						],
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = apply_onlines.apply_state'
						],
						[
							'table'=>'apply_online_approvals',
							'type'=>'left',
							'conditions'=>'apply_online_approvals.application_id = apply_onlines.id'
						]
					]
				)
			->where(['states.id' => $state,'apply_online_approvals.stage IN' => array($apply_approval_table->APPLICATION_SUBMITTED)]);
		}
		$Inspectionpdf->where(['apply_onlines.application_status != ' => $apply_approval_table->APPLICATION_CANCELLED]);
		
		$resultArray    = $Inspectionpdf->toList();
		$TotalCapacity  = isset($resultArray[0]['TotalCapacity'])?$resultArray[0]['TotalCapacity']:0;
		return (!empty($TotalCapacity)?_FormatGroupNumberV2($TotalCapacity):0);
	}
	/**
	 * TotalApplicationByPCRSubmitted
	 * Behaviour : public
	 * @param : $state, $main_branch_id
	 * @defination : Method is use to find total application of pcr submitted
	 */
	public function TotalApplicationByPCRSubmitted($state = '',$main_branch_id='') 
	{
		$ApplyOnlines = $this->find();
		$ApplyOnlines->hydrate(false);
		$apply_approval_table   = TableRegistry::get('ApplyOnlineApprovals');
		if ($main_branch_id['member_type'] == $this->DISCOM)
		{
			$ApplyOnlines->select(['TotalCount' => $ApplyOnlines->func()->count(0)])
			->join([
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.apply_state'
						],
						[
							'table'=>'apply_online_approvals',
							'type'=>'left',
							'conditions'=>'apply_online_approvals.application_id = ApplyOnlines.id'
						]
					]
				)
			->where([   'states.id' => $state,
						'ApplyOnlines.pcr_submited IS NOT NULL',
						'apply_online_approvals.stage IN' => array($apply_approval_table->APPLICATION_SUBMITTED),
						'ApplyOnlines.'.$main_branch_id['field']=>$main_branch_id['id']]);
		} else if ($main_branch_id['member_type'] == "CUSTOMER" || $main_branch_id['member_type'] == "INSTALLER") {
			$ApplyOnlines->select(['TotalCount' => $ApplyOnlines->func()->count(0)])
			->join([
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.apply_state'
						],
						[
							'table'=>'apply_online_approvals',
							'type'=>'left',
							'conditions'=>'apply_online_approvals.application_id = ApplyOnlines.id'
						]
					]
				)
			->where([   'states.id' => $state,
						'ApplyOnlines.pcr_submited IS NOT NULL',
						'apply_online_approvals.stage IN' => array($apply_approval_table->APPLICATION_SUBMITTED),
						'ApplyOnlines.'.$main_branch_id['field']=>$main_branch_id['id']]);
		} else {
			$ApplyOnlines->select(['TotalCount' => $ApplyOnlines->func()->count(0)])
			->join([
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.apply_state'
						],
						[
							'table'=>'apply_online_approvals',
							'type'=>'left',
							'conditions'=>'apply_online_approvals.application_id = ApplyOnlines.id'
						]
					]
				)
			->where(['states.id' => $state,'apply_online_approvals.stage IN' => array($apply_approval_table->APPLICATION_SUBMITTED),'ApplyOnlines.pcr_submited IS NOT NULL']);
		}
	   
		$ApplyOnlines->where(['ApplyOnlines.application_status != ' => $apply_approval_table->APPLICATION_CANCELLED]);

		$resultArray = $ApplyOnlines->toList();
		$TotalCount  = isset($resultArray[0]['TotalCount'])?$resultArray[0]['TotalCount']:0;
		return $TotalCount;
	}
	/**
	 * TotalApplicationPcrSubmittedPVCapacity
	 * Behaviour : public
	 * @param : $state, $main_branch_id
	 * @defination : Method is use to find total pv capacity of pcr submitted
	 */
	public function TotalApplicationPcrSubmittedPVCapacity($state = '',$main_branch_id='') 
	{
		$ApplyOnlines = $this->find();
		$ApplyOnlines->hydrate(false);
		$apply_approval_table   = TableRegistry::get('ApplyOnlineApprovals');
		if ($main_branch_id['member_type'] == $this->DISCOM)
		{
			$ApplyOnlines->select(['TotalCapacity' => $ApplyOnlines->func()->sum('ApplyOnlines.pv_capacity')])
			->join([
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.apply_state'
						],
						[
							'table'=>'apply_online_approvals',
							'type'=>'left',
							'conditions'=>'apply_online_approvals.application_id = ApplyOnlines.id'
						]
					]
				)
			->where([   'states.id' => $state,
						'ApplyOnlines.pcr_submited IS NOT NULL',
						'apply_online_approvals.stage IN' => array($apply_approval_table->APPLICATION_SUBMITTED),
						'ApplyOnlines.'.$main_branch_id['field']=>$main_branch_id['id']]);
		} else if ($main_branch_id['member_type'] == "CUSTOMER" || $main_branch_id['member_type'] == "INSTALLER") {
			$ApplyOnlines->select(['TotalCapacity' => $ApplyOnlines->func()->sum('ApplyOnlines.pv_capacity')])
			->join([
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.apply_state'
						],
						[
							'table'=>'apply_online_approvals',
							'type'=>'left',
							'conditions'=>'apply_online_approvals.application_id = ApplyOnlines.id'
						]
					]
				)
			->where([   'states.id' => $state,
						'ApplyOnlines.pcr_submited IS NOT NULL',
						'apply_online_approvals.stage IN' => array($apply_approval_table->APPLICATION_SUBMITTED),
						'ApplyOnlines.'.$main_branch_id['field']=>$main_branch_id['id']]);
		} else {
			$ApplyOnlines->select(['TotalCapacity' => $ApplyOnlines->func()->sum('ApplyOnlines.pv_capacity')])
			->join([
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.apply_state'
						],
						[
							'table'=>'apply_online_approvals',
							'type'=>'left',
							'conditions'=>'apply_online_approvals.application_id = ApplyOnlines.id'
						]
					]
				)
			->where(['states.id' => $state,'apply_online_approvals.stage IN' => array($apply_approval_table->APPLICATION_SUBMITTED),'ApplyOnlines.pcr_submited IS NOT NULL']);
		}
		$ApplyOnlines->where(['ApplyOnlines.application_status != ' => $apply_approval_table->APPLICATION_CANCELLED]);
		$resultArray    = $ApplyOnlines->toList();
		$TotalCapacity  = isset($resultArray[0]['TotalCapacity'])?$resultArray[0]['TotalCapacity']:0;
		return (!empty($TotalCapacity)?_FormatGroupNumberV2($TotalCapacity):0);
	}
	/**
	 * TotalApplicationByMsme
	 * Behaviour : public
	 * @param : $state, $main_branch_id
	 * @defination : Method is use to find total application of MSME pending
	 */
	public function TotalApplicationByMsme($state = '',$main_branch_id='',$capacity=0) 
	{
		$ApplyOnlines 				= $this->find();
		$ApplyOnlines->hydrate(false);
		$apply_approval_table   	= TableRegistry::get('ApplyOnlineApprovals');
		$arrConditions['states.id'] = $state;
		$arrConditions['ApplyOnlines.application_status'] = $apply_approval_table->APPROVED_FROM_GEDA;
		$arrConditions['ApplyOnlines.payment_status'] = 0;
		$arrConditions[]		= array('OR'=>['ApplyOnlines.category !='=>$this->category_residental,'ApplyOnlines.social_consumer'=>'1']);

		if ($main_branch_id['member_type'] == $this->DISCOM)
		{
			$arrConditions['ApplyOnlines.'.$main_branch_id['field']]	= $main_branch_id['id'];
		} 
		if($capacity == 1) {
			$arrFields['TotalCount'] = $ApplyOnlines->func()->sum('ApplyOnlines.pv_capacity');
		} else {
			$arrFields['TotalCount'] = $ApplyOnlines->func()->count(0);
		}
		
	   	$ApplyOnlines->select($arrFields)
			->join([
						[   'table'=>'states',
							'type'=>'left',
							'conditions'=>'states.id = ApplyOnlines.apply_state'
						]
					]
				)
			->where($arrConditions);
		$resultArray = $ApplyOnlines->toList();
		if($capacity == 1) {
			$TotalCount  = isset($resultArray[0]['TotalCount'])?_FormatGroupNumberV2($resultArray[0]['TotalCount']):0;
		} else {
			$TotalCount  = isset($resultArray[0]['TotalCount'])?$resultArray[0]['TotalCount']:0;
		}
		return $TotalCount;
	}
	/**
	 * fetchDataForRegistration
	 * Behaviour : public
	 * @param : $application id
	 * @defination : Method is use to fetch reuired data from database in order to pass GUVNL
	 */
	public function fetchDataForRegistration($application_id='')
	{
		$Installers 		= TableRegistry::get('Installers');
		$SolarTypeLog 		= TableRegistry::get('SolarTypeLog');
		$applyOnlineDetails = $this->find('all',array(	'fields'	=> array('pv_capacity',
																			'consumer_mobile',
																			'consumer_email',
																			'installer_id',
																			'consumer_no',
																			'name_of_consumer_applicant' => "CONCAT(name_of_consumer_applicant, ' ', last_name, ' ', third_name)"),
														'conditions'=>array('id'=>$application_id)))->first();
		
		
		$arr 				= array();
		if(!empty($applyOnlineDetails))
		{
			$installerDetails 	= $Installers->find('all',array(
										'fields'	=> array('Installers.installer_name','installer_category_mapping.short_name','Installers.email'),
										'conditions'=> array('Installers.id'=>$applyOnlineDetails->installer_id),
										'join'		=> [['table'=>'installer_category_mapping','type'=>'left','conditions'=>'Installers.id = installer_category_mapping.installer_id']]
										))->first();

			$arr['CNSMR_NO'] 		= $applyOnlineDetails->consumer_no;
			$arr['CNSMR_NAME'] 		= $applyOnlineDetails->name_of_consumer_applicant;
			$arr['APPLIED_LOAD'] 	= $applyOnlineDetails->pv_capacity;
			$arr['CNSMR_MOBILE_NO'] = $applyOnlineDetails->consumer_mobile;
			$arr['CNSMR_EMAIL_ID'] 	= $applyOnlineDetails->consumer_email;
			$arr['VENDOR_CODE'] 	= $applyOnlineDetails->installer_id;
			$arr['VENDOR_NAME'] 	= $installerDetails->installer_name;
			$arr['APPLICATION_NO'] 	= $application_id;
			//$arr['VENDOR_EMAIL'] 	= $installerDetails->email;
			$arr['SOLAR_TYPE']		= $SolarTypeLog->findSolarTypeFlag($application_id);
		}
		return $arr;
	}
	public function TotalApplicationByStatusView($state = '',$main_branch_id='',$application_status=0,$capacityTotal=0,$arrReqData=array()) 
	{
		$ApplicationsStagesDetails  = TableRegistry::get('applications_stages_details');
		$ApplyOnlineApprovals  		= TableRegistry::get('ApplyOnlineApprovals');
		$ApplyOnlines 				= $ApplicationsStagesDetails->find();
		$ApplyOnlines->hydrate(false);
		$pass_application_status 	= $application_status;
		
		if($ApplyOnlineApprovals->APPLICATION_SUBMITTED == $application_status) 
		{
			$application_status = $ApplyOnlineApprovals->APPROVED_FROM_GEDA;
		} 
		if($ApplyOnlineApprovals->APPLICATION_CANCELLED == $application_status || $ApplyOnlineApprovals->DOCUMENT_NOT_VERIFIED == $application_status) 
		{ 
			$application_status = $ApplyOnlineApprovals->APPLICATION_SUBMITTED;
		}
		if ($main_branch_id['member_type'] == $this->DISCOM)
		{
			$arrConditions 	= [ 'applications_stages_details.stage' => $application_status,
								'OR'=>['applications_stages_details.payment_status'=>'1','applications_stages_details.category'=>'3001'],
								'applications_stages_details.'.$main_branch_id['field']=>$main_branch_id['id']];
		} else if ($main_branch_id['member_type'] == "CUSTOMER" || $main_branch_id['member_type'] == "INSTALLER") {
			$arrConditions 	= [   'applications_stages_details.stage' => $application_status,
						'applications_stages_details.'.$main_branch_id['field']=>$main_branch_id['id']];
		} else {
			$arrConditions 	= ['applications_stages_details.stage' => $application_status];
		}

		if($pass_application_status!=$ApplyOnlineApprovals->APPLICATION_CANCELLED)
		{
			$arrConditions['applications_stages_details.application_status != '] 	= $ApplyOnlineApprovals->APPLICATION_CANCELLED;
		} else if($pass_application_status==$ApplyOnlineApprovals->APPLICATION_CANCELLED) {
			$arrConditions['applications_stages_details.application_status'] 		= $ApplyOnlineApprovals->APPLICATION_CANCELLED;
		}
		if($pass_application_status==$ApplyOnlineApprovals->DOCUMENT_NOT_VERIFIED)
		{
			$arrConditions['applications_stages_details.application_status'] 		= $ApplyOnlineApprovals->APPROVED_FROM_GEDA;
		}
		if(isset($arrReqData['disclaimer_subsidy']) && !empty($arrReqData['disclaimer_subsidy'])) {
			$arrConditions['applications_stages_details.disclaimer_subsidy'] 	= $arrReqData['disclaimer_subsidy'];
		} else if(isset($arrReqData['pcr_code']) && !empty($arrReqData['pcr_code'])) {
			$arrConditions['applications_stages_details.pcr_code IS NOT'] 		=  NULL;
		} else if(isset($arrReqData['pcr_submited']) && !empty($arrReqData['pcr_submited'])) {
			$arrConditions['applications_stages_details.pcr_submited IS NOT'] 	=  NULL;
		} else if(isset($arrReqData['category']) && !empty($arrReqData['category'])) {
			if(is_array($arrReqData['category'])) {
				$arrConditions['applications_stages_details.category in'] 		=  $arrReqData['category'];
			} else {
				$arrConditions['applications_stages_details.category'] 			=  $arrReqData['category'];
			}
		} else if(isset($arrReqData['social_consumer']) && !empty($arrReqData['social_consumer'])) {
			$arrConditions['applications_stages_details.social_consumer'] 		=  $arrReqData['social_consumer'];
		}
		if($capacityTotal == 0) {
			$ApplyOnlines->select(['TotalCount' => $ApplyOnlines->func()->count(0)])->where($arrConditions);
			$resultArray 	= $ApplyOnlines->toList();
			$TotalCount  	= isset($resultArray[0]['TotalCount'])?$resultArray[0]['TotalCount']:0;
			return $TotalCount;
		} else if($capacityTotal == 1) {
			$ApplyOnlines->select(['TotalCapacity' => $ApplyOnlines->func()->sum('applications_stages_details.pv_capacity')])->where($arrConditions);
			$resultArray    = $ApplyOnlines->toList();
			$TotalCapacity  = isset($resultArray[0]['TotalCapacity'])?$resultArray[0]['TotalCapacity']:0;
			return (!empty($TotalCapacity)?_FormatGroupNumberV2($TotalCapacity):0);
		}
	}
}
?>