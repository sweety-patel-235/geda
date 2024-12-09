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
use Cake\Network\Email\Email;
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

class ApplicationsTable extends AppTable
{
	var $table 			= 'applications';
	var $data 			= array();
	var $dataRecord 	= array();
	var $SEEGEOLOCATION = "6005";
	public function initialize(array $config)
	{
		$this->table($this->table);
	}


	// public $ExportFields 	= array();
	public $ExportFields 	= array("sr_no", "submitted_on", "scheme", "application_no", "registration_no", "installer_name", "application_status", "application_type", "pv_capacity_ac", "pv_capacity_dc", "msme", "name_director", "type_director", "director_email", "name_authority", "authority_mobile", "getco_substation", "discom", "grid_connectivity", "injection_level", "application_end_use_electricity", "inverter_hybrid_capacity", "module_hybrid_capacity", "name_of_applicant", "project_district", "project_taluka");
	public $DefaultExportFields 	= array("sr_no", "submitted_on", "scheme", "application_no", "registration_no", "installer_name", "application_status", "application_type", "pv_capacity_ac", "pv_capacity_dc", "msme", "name_director", "type_director", "director_email", "name_authority", "authority_mobile", "getco_substation", "discom", "grid_connectivity", "injection_level", "application_end_use_electricity", "inverter_hybrid_capacity", "module_hybrid_capacity", "name_of_applicant", "project_district", "project_taluka");
	public $arrReportFields 		= array(
		"sr_no"		    => "sr_no",
		"submitted_on"		=> "submitted_on",
		"scheme"			=> "scheme",
		"application_no"	=> "application_no",
		"registration_no"	=> "registration_no",
		"installer_name"	=> "developer_name",
		"application_status" => "application_status",
		"application_type" 	=> "application_type",
		"pv_capacity_ac"	=> "pv_capacity_ac",
		"pv_capacity_dc"	=> "pv_capacity_dc",
		"msme"				=> "msme",
		"name_director"		=> "name_director",
		"type_director"		=> "type_director",
		"director_email"	=> "director_email",
		"name_authority"	=> "name_authority",
		"authority_mobile"	=> "authority_mobile",
		"getco_substation"	=> "getco_substation",
		"discom"			=> "discom",
		"grid_connectivity" => "Grid Connectivity",
		"injection_level" 	=> "Injection Level",
		"application_end_use_electricity" => "End Use Electricity",
		"inverter_hybrid_capacity"  => "AC Capacity",
		"module_hybrid_capacity"    => "DC Capacity",
		"name_of_applicant" 	    => "name_of_applicant",
		"project_district" 					=> "District",
		"project_taluka" 			=> "Taluka",

	);
	public $arrReportFieldsIns 		= array(
		"sr_no"		    => "sr_no",
		"submitted_on"		=> "submitted_on",
		"scheme"			=> "scheme",
		"application_no"	=> "application_no",
		"registration_no"	=> "registration_no",
		"installer_name"	=> "installer_name",
		"application_status" => "application_status",
		"application_type"	=> "application_type",
		"pv_capacity_ac"	=> "pv_capacity_ac",
		"pv_capacity_dc"	=> "pv_capacity_dc",
		"msme"				=> "msme",
		"name_director"		=> "name_director",
		"type_director"		=> "type_director",
		"director_email"	=> "director_email",
		"name_authority"	=> "name_authority",
		"authority_mobile"	=> "authority_mobile",
		"getco_substation"	=> "getco_substation",
		"discom"			=> "discom",
		"grid_connectivity" => "Grid Connectivity",
		"injection_level" 	=> "Injection Level",
		"application_end_use_electricity" => "End Use Electricity",
		"inverter_hybrid_capacity" => "AC Capacity",
		"module_hybrid_capacity"   => "DC Capacity",
		"name_of_applicant" => "name_of_applicant",
		"project_district" 			=> "District",
		"project_taluka" 			=> "Taluka",

	);

	/**
	 *
	 * GetReReportFields
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to get all fields exported in to excel.
	 *
	 */
	public function GetReReportFields($arrExportFields, $array_request)
	{
		$this->ExportFields = $arrExportFields;

		$ReportFields = "select a.id ";
		if (in_array("submitted_on", $this->ExportFields)) {
			$ReportFields .= ", a.created ";
		}
		if (in_array("application_no", $this->ExportFields)) {
			$ReportFields .= ",a.application_no";
		}
		if (in_array("registration_no", $this->ExportFields)) {
			$ReportFields .= ",a.registration_no";
		}
		if (in_array("installer_name", $this->ExportFields)) {
			$ReportFields .= ",d.installer_name";
		}
		if (in_array("application_status", $this->ExportFields)) {
			$ReportFields .= ",a.application_status";
		}
		if (in_array("application_type", $this->ExportFields)) {
			$ReportFields .= ",a.application_type";
		}
		if (in_array("pv_capacity_ac", $this->ExportFields)) {
			$ReportFields .= ",a.pv_capacity_ac";
		}
		if (in_array("pv_capacity_dc", $this->ExportFields)) {
			$ReportFields .= ",a.pv_capacity_dc";
		}
		if (in_array("msme", $this->ExportFields)) {
			$ReportFields .= ",a.msme";
		}
		if (in_array("name_director", $this->ExportFields)) {
			$ReportFields .= ",a.name_director";
		}
		if (in_array("type_director", $this->ExportFields)) {
			$ReportFields .= ",a.type_director";
		}
		if (in_array("director_email", $this->ExportFields)) {
			$ReportFields .= ",a.director_email";
		}
		if (in_array("name_authority", $this->ExportFields)) {
			$ReportFields .= ",a.name_authority";
		}
		if (in_array("authority_mobile", $this->ExportFields)) {
			$ReportFields .= ",a.authority_mobile";
		}
		if (in_array("getco_substation", $this->ExportFields)) {
			$ReportFields .= ", a.getco_substation";
		}
		if (in_array("discom", $this->ExportFields)) {
			$ReportFields .= ",a.discom";
		}
		if (in_array("inverter_hybrid_capacity", $this->ExportFields)) {
			$ReportFields .= ",a.inverter_hybrid_capacity";
		}
		if (in_array("module_hybrid_capacity", $this->ExportFields)) {
			$ReportFields .= ",a.module_hybrid_capacity";
		}
		if (in_array("name_of_applicant", $this->ExportFields)) {
			$ReportFields .= ",a.name_of_applicant";
		}
		if (in_array("project_district", $this->ExportFields)) {
			$ReportFields .= ",dm.name";
		}
		if (in_array("project_taluka", $this->ExportFields)) {
			$ReportFields .= ",a.project_taluka";
		}

		//$this->Applications->getDataApplications($this->request->data);
		//$ApplicationsListData     = $ApplicationsList['list'];

		$ReportFields .= " from applications as a 
		 Left Join developers as d ON d.id = a.installer_id
		 Left Join application_stages as s ON s.application_id = a.id
		 Left Join district_master as dm ON dm.id = a.project_district";
		$ReportFields 		.= " where  a.application_status NOT IN('99','29','30','22','0') and s.stage = 1";
		$fields_date  		= "a.created";

		if (!empty($array_request['DateFrom']) && !empty($array_request['DateTo'])) {
			$from_date 		= date('Y-m-d', strtotime($array_request['DateFrom']));
			$end_date 		= date('Y-m-d', strtotime($array_request['DateTo']));
			$ReportFields 	.= ' and ' . $fields_date . ' BETWEEN "' . $from_date . '" and "' . $end_date . ' 23:59:59"';
		}

		$ReportFields .= " GROUP BY a.id";
		return $ReportFields;
	}

	public function viewApplication($application_id)
	{
		$arrApplication = $this->find('all', array('conditions' => array('id' => $application_id)))->first();
		return $arrApplication;
	}
	public function validationTab1(Validator $validator)
	{
		$validator->notEmpty('name_of_applicant', 'Name of the Applicant can not be blank.');
		//$validator->notEmpty('address','Address of Registered Office can not be blank.');
		$validator->notEmpty('designation', 'Designation can not be blank.');
		$validator->notEmpty('mobile', 'Please Enter valid Mobile no.')
			->add('mobile', 'custom', [
				'rule' => [$this, 'ValidateMobileNumber'],
				'message' => 'Please Enter valid Mobile no.'
			]);
		$validator->notEmpty('email', 'Please Enter Consumer Email.')
			->add('email', 'valid-email', [
				'rule' => 'email',
				'message' => 'Please Enter valid Email.'
			]);
		$validator->notEmpty('state', 'Please Select State.');
		$validator->notEmpty('district', 'Please Select District.');
		$validator->notEmpty('pincode', 'Pincode can not be blank.');
		$validator->notEmpty('pan', 'PAN Number can not be blank.');
		$validator->notEmpty('city', 'City can not be blank.');
		$validator->notEmpty('address1', 'Street/House no can not be blank.');
		$validator->notEmpty('taluka', 'Taluka/Village can not be blank.');

		//$validator->notEmpty('GST', 'GST Number can not be blank.');
		if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->gst_certificate))) {
			$validator->notEmpty('f_gst_certificate', 'Upload GST Certificate file required.');
		}

		if (isset($this->dataPass['pan']) && strlen($this->dataPass['pan']) != 10) {
			$validator->add("pan", [
				"_empty" => [
					"rule" => [$this, "customFunction_false"],
					"message" => "Pan Card Number must be a 10 digits."
				]
			]);
		} else {
			/*$validator->add("pan", [
				"_empty" => [
					"rule" => [$this, "custom_unique"],
					"pass" => array('pan'),
					"message" => "PAN Card Number already exist."
						]
					]
			);*/
		}
		if (isset($this->dataPass['mobile']) && strlen($this->dataPass['mobile']) != 10) {
			$validator->add("mobile", [
				"_empty" => [
					"rule" => [$this, "customFunction_false"],
					"message" => "Mobile Number must be a 10 digits."
				]
			]);
		} else {
			/*$validator->add("mobile", [
			"_empty" => [
				"rule" => [$this, "custom_unique"],
				"pass" => array('mobile'),
				"message" => "Mobile number already exist."
					]
				]
		);*/
		}
		/*$validator->add("email", [
			"_empty" => [
				"rule" => [$this, "custom_unique"],
				"pass" => array('email'),
				"message" => "Email already exist."
					]
				]
		);*/
		/*	$validator->add("email", [
			"_empty" => [
				"rule" => [$this, "custom_unique_customer"],
				"pass" => array('email'),
				"message" => "Email already mapped with customer."
					]
				]
		);*/
		if (isset($this->dataPass['email']) && !empty($this->dataPass['email'])) {

			$validator->add(
				"email",
				[
					"_empty" => [
						"rule" => [$this, "validateEmail1"],
						"pass" => array('email'),
						"message" => "Wrong Email."
					]
				]
			);
		}


		/*$validator->add("installer_name", [
			"_empty" => [
				"rule" => [$this, "custom_unique"],
				"pass" => array('installer_name'),
				"message" => "Solar Installer Company already exist."
					]
				]
		);*/

		if (isset($this->dataPass['pincode']) && strlen($this->dataPass['pincode']) != 6) {
			$validator->add("pincode", [
				"_empty" => [
					"rule" => [$this, "customFunction_false"],
					"message" => "Pincode must be 6 digits."
				]
			]);
		}
		if (isset($this->dataPass['application_type']) && $this->dataPass['application_type'] == 5) {
		} else {
			if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->upload_undertaking))) {
				$validator->notEmpty('a_upload_undertaking', 'Upload Undertaking form file required.');
			}
		}

		if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->registration_document))) {
			$validator->notEmpty('f_registration_document', 'Enclose self-certified copy of Entity Registration/ MOA/ROC/ROF/AOA/COI/Partnership Deed/LLP file required.');
		}
		if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->pan_card))) {
			$validator->notEmpty('f_pan_card', 'Upload PAN Card file required.');
		}

		if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->d_file_board))) {
			$validator->notEmpty('f_file_board', 'Enclose self-certified copy of Entity Registration/ MOA/ROC/ROF/AOA/COI/Partnership Deed/LLP file required.');
		}


		if (isset($this->dataPass['type_of_applicant']) && $this->dataPass['type_of_applicant'] == '') {
			$validator->notEmpty('type_of_applicant', 'Please select at-least one.');
		}
		if (isset($this->dataPass['type_of_applicant']) && $this->dataPass['type_of_applicant'] == 'Other' && $this->dataPass['applicant_others'] == '') {
			$validator->notEmpty('applicant_others', 'Applicant Type Other can not be blank.');
		}
		$validator->notEmpty('selected_category', 'Please select atleast one.');

		if (isset($this->dataPass["msme"]) && $this->dataPass["msme"] == "") {
			$validator->add("msme", [
				"_empty" => [
					"rule" => [$this, "customFunction_false"],
					"message" => "Please select Applicant a MSME?",
				],
			]);
		}
		if (!isset($this->dataPass["kusum_type"]) || (isset($this->dataPass["kusum_type"]) && $this->dataPass["kusum_type"] == "" && $this->dataPass["application_type"] == "5")) {
			$validator->add("kusum_type", [
				"_empty" => [
					"rule" => [$this, "customFunction_false"],
					"message" => "Please select KUSUM Type",
				],
			]);
		}
		$validator->notEmpty('name_director', 'Name of the Managing Director / Chief Executive of the Company can not be blank.');
		$validator->notEmpty('director_mobile', 'Mobile can not be blank.')
			->add('director_mobile', 'custom', [
				'rule' => [$this, 'ValidateMobileNumber'],
				'message' => 'Please Enter valid Mobile no.'
			]);

		$validator->notEmpty('director_email', 'Please Enter Email.')
			->add('director_email', 'valid-email', [
				'rule' => 'email',
				'message' => 'Please Enter valid Email.'
			]);
		$validator->notEmpty('type_director', 'Please select atleast one.');


		$validator->notEmpty('name_authority', 'Name of the authorized Signatory can not be blank.');
		$validator->notEmpty('authority_mobile', 'Mobile can not be blank.')
			->add('authority_mobile', 'custom', [
				'rule' => [$this, 'ValidateMobileNumber'],
				'message' => 'Please Enter valid Mobile no.'
			]);

		$validator->notEmpty('authority_email', 'Please Enter Email.')
			->add('authority_email', 'valid-email', [
				'rule' => 'email',
				'message' => 'Please Enter valid Email.'
			]);
		if (isset($this->dataPass['authority_whatsapp']) && !empty($this->dataPass['authority_whatsapp'])) {
			$validator->add('authority_whatsapp', 'custom', [
				'rule' => [$this, 'ValidateMobileNumber'],
				'message' => 'Please Enter valid Mobile no.'
			]);
		}
		if (isset($this->dataPass['contact']) && !empty($this->dataPass['contact'])) {
			$validator->add('contact', 'custom', [
				'rule' => [$this, 'ValidateMobileNumber'],
				'message' => 'Please Enter valid Mobile no.'
			]);
		}
		if (isset($this->dataPass['director_whatsapp']) && !empty($this->dataPass['director_whatsapp'])) {
			$validator->add('director_whatsapp', 'custom', [
				'rule' => [$this, 'ValidateMobileNumber'],
				'message' => 'Please Enter valid Mobile no.'
			]);
		}


		$validator->notEmpty('type_authority', 'Please select atleast one.');

		if (isset($this->dataPass['GST']) && !empty($this->dataPass['GST'])) {
			$validator->add('GST', [
				'validFormat' => [
					'rule' => array('custom', '/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/'),
					'message' => 'Valid GST Number only'
				]
			]);
		} elseif (isset($this->dataPass['GST']) && !empty($this->dataPass['GST']) && strlen($this->dataPass['GST']) != 15) {
			$validator->add("GST", [
				"_empty" => [
					"rule" => [$this, "customFunction_false"],
					"message" => "GST must be 15 characters."
				]
			]);
		}

		if (isset($this->dataPass['msme']) && $this->dataPass['msme'] == 1 && (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->a_msme)))) {
			$validator->notEmpty('app_msme', 'Upload MSME file required.');
		}
		//pr($this->dataRecord);
		if (isset($this->dataPass['type_authority']) && $this->dataPass['type_authority'] == 'Others') {
			$validator->notEmpty('type_authority_others', 'Designation can not be blank.');
		}
		if (isset($this->dataPass['type_director']) && $this->dataPass['type_director'] == 'Others') {
			$validator->notEmpty('type_director_others', 'Designation can not be blank.');
		}
		return $validator;
	}
	/**
	 *
	 * customFunction_false
	 *
	 * Behaviour : public
	 *
	 * Parameter : discom
	 *
	 * @defination : Method is used to check consumer number length validation.
	 *
	 */
	public function customFunction_false($value, $context)
	{
		return false;
	}
	/**
	 * validateEmail1
	 * Behaviour : public
	 * Parameter : discom
	 * @defination : Method is used to check validateEmail1.
	 */
	public function validateEmail1($value, $context, $action = array())
	{

		if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * validationTab2
	 * Behaviour : public
	 * Parameter : validator
	 * @defination : Validation for tab2.
	 */
	public function validationTab2(Validator $validator)
	{
		$validator->notEmpty('pv_capacity_ac', 'Project Capacity AC can not be blank.');
		$validator->notEmpty('pv_capacity_dc', 'Project Capacity DC can not be blank.');

		$flag_nos_mod_enter    = 0;
		//$validator->notEmpty('make', 'Make can not be blank.');
		$validator->notEmpty('grid_connectivity', 'Please Select Grid Connectivity.');
		$validator->notEmpty('discom', 'Please Select Discom.');


		if ($this->dataPass['grid_connectivity'] == 2) {
			$flgctu 	= 0;
			$validator->notEmpty('injection_level_ctu', 'Power Injection Level can not be blank.');
			$validator->notEmpty('end_ctu', 'Please Select at least one.');
			/*if(isset($this->dataPass['end_ctu'])) {
				foreach($this->dataPass['end_ctu'] as $ctuval) {
					if(!empty($ctuval)) {
						$flgctu 	= 1;
					}
				}
			}
			if(empty($flgctu)) {
				$validator->notEmpty('end_ctu_select', 'Please Select at least one.');
			}*/
		} else {
			$validator->notEmpty('injection_level', 'Please Select Power Injection Level.');
			$validator->notEmpty('end_stu', 'Please Select at least one.');
		}
		$validator->notEmpty('getco_substation', 'Name of Proposed GETCO / PGCIL Substation can not be blank.');
		$validator->notEmpty('project_state', 'State can not be blank.');
		$validator->notEmpty('project_village', 'Village can not be blank.');
		$validator->notEmpty('project_taluka', 'Taluka can not be blank.');
		$validator->notEmpty('project_district', 'Please Select Power District.');

		$validator->notEmpty('comm_date', 'Tentative date of commissioning can not be blank.');

		if (isset($this->dataPass['comm_date']) && !empty($this->dataPass['comm_date'])) {
			if (isset($this->dataPass['created']) && !empty($this->dataPass['created'])) {
				if (strtotime(date('Y-m-d', strtotime($this->dataPass['created']))) > strtotime(date('Y-m-d', strtotime($this->dataPass['comm_date'])))) {
					$validator->add('comm_date', 'custom', [
						'rule' => [$this, 'customFunction_false'],
						'message' => 'Tentative date of commissioning should greater than or equals to ' . date('d-m-Y', strtotime($this->dataPass['created'])) . '.'
					]);
				}
			} else if (strtotime(date('Y-m-d', strtotime($this->dataPass['comm_date']))) < strtotime(date('Y-m-d'))) {
				$validator->add('comm_date', 'custom', [
					'rule' => [$this, 'customFunction_false'],
					'message' => 'Tentative date of commissioning should greater than or equals to ' . date('d-m-Y') . '.'
				]);
			}
		}
		if (isset($this->dataPass['project_energy']) && empty($this->dataPass['project_energy'])) {
			$validator->add('project_energy', 'custom', [
				'rule' => [$this, 'customFunction_false'],
				'message' => 'Expected Annual output of energy from the proposed project in kWh can not be blank.'
			]);
		}
		if (isset($this->dataPass['approx_generation']) && empty($this->dataPass['approx_generation'])) {
			$validator->add('approx_generation', 'custom', [
				'rule' => [$this, 'customFunction_false'],
				'message' => 'Approximate employment generation can not be blank.'
			]);
		}
		if (isset($this->dataPass['project_estimated_cost']) && empty($this->dataPass['project_estimated_cost'])) {
			$validator->add('project_estimated_cost', 'custom', [
				'rule' => [$this, 'customFunction_false'],
				'message' => 'Approximate Project Cost can not be blank.'
			]);
		}


		if ($this->dataPass['grid_connectivity'] == 1 && $this->dataPass['end_stu'] == 3 && (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->f_sale_discom)))) {
			$validator->notEmpty('f_sale_discom', 'Sale to DISCOM file required.');
		}

		return $validator;
	}
	/**
	 * getDataApplications
	 * Behaviour : public
	 * Parameter : arrRequestData all parameter of request as well as session passed to this variable
	 * @defination : Listing of applications.
	 */
	public function getDataApplications($arrRequestData = array())
	{
		$ApplicationStages   	= TableRegistry::get('ApplicationStages');

		$fields             = [
			'Applications.id',
			'Applications.customer_id',
			'Applications.installer_id',
			'Applications.discom',
			'Applications.name_of_applicant',
			'Applications.address',
			'Applications.email',
			'Applications.city',
			'Applications.taluka',
			'Applications.project_taluka',
			// 'Applications.project_district',
			'Applications.payment_status',
			'Applications.application_type',
			'Applications.kusum_type',
			'Applications.pv_capacity_ac',
			'Applications.pv_capacity_dc',
			'Applications.application_status',
			'Applications.application_no',
			'Applications.capacity_wtg',
			'Applications.wtg_no',
			'Applications.getco_substation',
			'Applications.grid_connectivity',
			'Applications.modified',
			'Applications.query_sent',
			'Applications.query_date',
			'Applications.total_wind_hybrid_capacity',
			'Applications.module_hybrid_capacity',
			'Applications.inverter_hybrid_capacity',
			'Applications.registration_no',
			'Applications.msme',
			'Applications.total_capacity',
			'Applications.injection_level_ctu',
			'Applications.injection_level',
			'Applications.name_director',
			'Applications.type_director',
			'Applications.director_email',
			'Applications.name_authority',
			'Applications.authority_mobile',
			'Applications.e_invoice_url',
			'Applications.workorder_installer_id',
			'application_category.category_name',
			'application_category.route_name',
			'application_category.color_code',
			'developer_customers.name',
			'district_master.name',
			'developers.installer_name',
			'Applications.created',
			'application_end_use_electricity.application_end_use_electricity',
			'branch_masters.title',
			'application_connectivity_step.connectivity_type',
			"submitted_date" => "(( CASE WHEN 1 = 1 THEN( select created FROM application_stages 
								WHERE 
								  application_stages.application_id = Applications.id 
								  AND application_stages.stage = '" . $ApplicationStages->APPLICATION_SUBMITTED . "' 
								group by 
								  stage) END ))"
		];


		$arrOrderBy     = explode("|", $arrRequestData['order_by_form']);
		$join_arr  		= [
			'application_category'	=> ['table' => 'application_category', 'type' => 'left', 'conditions' => 'Applications.application_type=application_category.id'],
			'district_master'		=> ['table' => 'district_master', 'type' => 'left', 'conditions' => 'Applications.project_district=district_master.id'],
			'branch_masters'		=> ['table' => 'branch_masters', 'type' => 'left', 'conditions' => 'Applications.discom=branch_masters.id'],
			'application_connectivity_step'	=> ['table' => 'application_connectivity_step', 'type' => 'left', 'conditions' => 'Applications.id=application_connectivity_step.application_id'],
			'application_end_use_electricity' => ['table' => 'application_end_use_electricity', 'type' => 'left', 'conditions' => 'Applications.id=application_end_use_electricity.application_id'],
			//Vishal
			"wind_wtg_detail" => ["table" => "wind_wtg_detail", "type" => "left", "conditions" => "Applications.id=wind_wtg_detail.application_id"],
			//Vishal
		];

		//if(!empty($arrRequestData['customer_id'])) {

		$condition_arr  	= array();
		$condition_union_arr = array();
		array_push($join_arr, ['table' => 'developer_customers', 'type' => 'left', 'conditions' => 'developer_customers.id = Applications.customer_id']);
		array_push($join_arr, ['table' => 'developers', 'type' => 'left', 'conditions' => 'developers.id = Applications.installer_id']);
		$str_group_by 		= '1';
		$flag_stages_table 	= 0;
		if (isset($arrRequestData['customer_id']) && !empty($arrRequestData['customer_id'])) {
			$condition_arr 	= ['Applications.customer_id' => $arrRequestData['customer_id']];
			//$condition_arr 	= ['or'=>array('Applications.customer_id'=>$arrRequestData['customer_id'],array('Applications.workorder_installer_id'=>$arrRequestData['installer_id']),'Applications.application_status>'=>0)];
		} elseif (isset($arrRequestData['member_id']) && !empty($arrRequestData['member_id'])) {
			array_push($join_arr, ['table' => 'application_stages', 'type' => 'left', 'conditions' => 'application_stages.application_id = Applications.id']);
			$flag_stages_table 	= 1;
			//if($arrRequestData['customer_id'])
			$condition_arr 		= ['application_stages.stage' => $ApplicationStages->APPLICATION_SUBMITTED];
		}
		if (isset($arrRequestData['installer_id']) && !empty($arrRequestData['installer_id'])) {
			$condition_union_arr 	= ['Applications.workorder_installer_id' => $arrRequestData['installer_id'], 'Applications.application_status > ' => 0];
		}

		//Vishal
		if (isset($arrRequestData["display_app"]) && !empty($arrRequestData["display_app"])) {
			$condition_arr["Applications.application_type IN"] = $arrRequestData["display_app"];
		}
		//Vishal
		if (isset($arrRequestData['member_type']) && !empty($arrRequestData['member_type']) && $arrRequestData['member_type'] == 6005) {
			$condition_arr['application_type IN'] = array(3, 4);
		}
		if (isset($arrRequestData['application_type']) && !empty($arrRequestData['application_type'])) {
			$condition_arr['application_type']			= $arrRequestData['application_type'];
		}
		if (isset($arrRequestData['name_of_applicant']) && !empty($arrRequestData['name_of_applicant'])) {
			$condition_arr['name_of_applicant like ']	= '%' . $arrRequestData['name_of_applicant'] . '%';
		}
		if (isset($arrRequestData['payment_status']) && $arrRequestData['payment_status'] != '') {
			$condition_arr['Applications.payment_status']	= $arrRequestData['payment_status'];
		}
		if (isset($arrRequestData['application_search_no']) && $arrRequestData['application_search_no'] != '') {
			$condition_arr['application_no like ']	= '%' . $arrRequestData['application_search_no'] . '%';
		}
		if (isset($arrRequestData['receipt_no']) && !empty($arrRequestData['receipt_no'])) {
			array_push($join_arr, ['table' => 're_application_payment', 'alias' => 're_application_payment', 'type' => 'left', 'conditions' => 're_application_payment.application_id = Applications.id']);
			array_push($condition_arr, array('re_application_payment.payment_status' => 'success', 're_application_payment.receipt_no like ' => '%' . $arrRequestData['receipt_no'] . '%'));
		}
		if (isset($arrRequestData['DateFrom']) && !empty($arrRequestData['DateFrom']) && isset($arrRequestData['DateTo']) && !empty($arrRequestData['DateTo'])) {
			//$where_data     = ['Applications.id IS NOT'=>NULL];
			$condition_arr  = ["Applications.created BETWEEN :start AND :end"];
		} else {
			$condition_arr['Applications.id IS NOT']  = NULL;
		}

		if (isset($arrRequestData['application_status']) && $arrRequestData['application_status'] != '') {
			switch ($arrRequestData['application_status']) {

				case '310':
					if ($flag_stages_table == 0) {
						array_push($join_arr, ['table' => 'application_stages', 'type' => 'left', 'conditions' => 'application_stages.application_id = Applications.id']);
						$flag_stages_table 							= 1;
					}
					$condition_arr['application_stages.stage']		= 1;
					$condition_arr['Applications.payment_status']	= 0;
					break;
				case '311':
					$condition_arr['Applications.application_status']	= $ApplicationStages->APPLICATION_SUBMITTED;
					$condition_arr['Applications.payment_status']		= 1;
					$condition_arr[] 	= '(( CASE WHEN 1 = 1 THEN( select count(applications_messages.id) FROM applications_messages
								WHERE
								  applications_messages.application_id = Applications.id and applications_messages.application_status=1 ) END )) = 0';
					break;
				case '2000':
					$condition_arr['Applications.query_sent'] 			= 1;
					break;
				case '2111':
					$condition_arr['Applications.query_sent !='] 		= 1;
					/*$condition_arr[] 	= ['(( CASE WHEN 1 = 1 THEN( select count(id) FROM applications_messages
								WHERE
								  applications_messages.application_id = Applications.id
								group by
								  applications_messages.application_id) END )) > 0'];*/
					$condition_arr[] 	= 'Applications.application_status = (( CASE WHEN 1 = 1 THEN( select application_status FROM applications_messages
								WHERE
								  applications_messages.application_id = Applications.id
								order by applications_messages.id desc limit 1) END ))';
					break;
				case '1':
					if ($flag_stages_table == 0) {
						array_push($join_arr, ['table' => 'application_stages', 'type' => 'left', 'conditions' => 'application_stages.application_id = Applications.id']);
					}
					$condition_arr['application_stages.stage']	= $ApplicationStages->APPLICATION_SUBMITTED;
					break;
				case '39':
					if ($flag_stages_table == 0) {
						array_push($join_arr, ['table' => 'application_stages', 'type' => 'left', 'conditions' => 'application_stages.application_id = Applications.id']);
					}
					$condition_arr['application_stages.stage IN']	= array(35, 36, 37);
					break;
					//Vishal
				case "42":
					if ($flag_stages_table == 0) {
						array_push($join_arr, [
							"table" => "application_stages",
							"type" => "left",
							"conditions" =>
							"application_stages.application_id = Applications.id",
						]);
					}

					$condition_arr["application_stages.stage"] =
						$arrRequestData["application_status"];
					$condition_arr["OR"] = 'Applications.application_status = (( CASE WHEN 1 = 1 THEN( select application_status FROM applications_messages
								WHERE
								  applications_messages.application_id = Applications.id
								order by applications_messages.id desc limit 1) END ))';
					break;
					//Vishal
				default:
					if ($flag_stages_table == 0) {
						array_push($join_arr, ['table' => 'application_stages', 'type' => 'left', 'conditions' => 'application_stages.application_id = Applications.id']);
					}
					$condition_arr['application_stages.stage']	= $arrRequestData['application_status'];
					$condition_arr['OR'] 	= array('(( CASE WHEN 1 = 1 THEN( select count(applications_messages.id) FROM applications_messages
								WHERE
								  applications_messages.application_id = Applications.id and applications_messages.application_status=' . $arrRequestData['application_status'] . ' ) END )) = 0', '(( CASE WHEN 1 = 1 THEN( select count(applications_messages.id) FROM applications_messages
								WHERE
								  applications_messages.application_id = Applications.id and applications_messages.application_status=' . $arrRequestData['application_status'] . ' ) END )) > 0 
								  and 
								  Applications.application_status != (( CASE WHEN 1 = 1 THEN( select application_status FROM applications_messages
								WHERE
								  applications_messages.application_id = Applications.id
								order by applications_messages.id desc limit 1) END ))');
					/*$condition_arr[] 	= 'Applications.application_status != (( CASE WHEN 1 = 1 THEN( select application_status FROM applications_messages
								WHERE
								  applications_messages.application_id = Applications.id
								order by applications_messages.id desc limit 1) END ))';*/
					$condition_arr['Applications.query_sent !='] = 1;
					break;
			}
		}
		if (isset($arrRequestData['main_branch_id']) && !empty($arrRequestData['main_branch_id'])) {
			$condition_arr['Applications.discom'] = $arrRequestData['main_branch_id'];
		}

		$ApplyOnlinesList   = $this->find("all", [
			'fields'		=> $fields,
			'join'   		=> $join_arr,
			'conditions'	=> $condition_arr,
			'order'			=> [$arrOrderBy[0] => $arrOrderBy[1], 'Applications.created' => $arrOrderBy[1]]
		]);

		if (isset($arrRequestData['DateFrom']) && isset($arrRequestData['DateTo']) && !empty($arrRequestData['DateFrom']) && !empty($arrRequestData['DateTo'])) {
			$StartTime  = date("Y-m-d", strtotime(str_replace('/', '-', $arrRequestData['DateFrom']))) . " 00:00:00";
			$EndTime    = date("Y-m-d", strtotime(str_replace('/', '-', $arrRequestData['DateTo']))) . " 23:59:59";
			$ApplyOnlinesList->bind(':start', $StartTime, 'date')->bind(':end', $EndTime, 'date')->count();
		}

		$ApplyOnlinesPVCapacity = $this->find('all', [
			'join'   => $join_arr,
			'fields'    => array('TotalCapacityData' => 'count(Applications.id)'),
			'conditions' => $condition_arr
		]);

		if (!empty($condition_union_arr)) {
			$ApplyOnlinesUnionList   = $this->find("all", [
				'fields'		=> $fields,
				'join'   		=> $join_arr,
				'conditions'	=> $condition_union_arr,
				'order'			=> [$arrOrderBy[0] => $arrOrderBy[1], 'Applications.created' => $arrOrderBy[1]]
			]);
			if (isset($arrRequestData['DateFrom']) && isset($arrRequestData['DateTo']) && !empty($arrRequestData['DateFrom']) && !empty($arrRequestData['DateTo'])) {
				$StartTime  = date("Y-m-d", strtotime(str_replace('/', '-', $arrRequestData['DateFrom']))) . " 00:00:00";
				$EndTime    = date("Y-m-d", strtotime(str_replace('/', '-', $arrRequestData['DateTo']))) . " 23:59:59";
				$ApplyOnlinesList->bind(':start', $StartTime, 'date')->bind(':end', $EndTime, 'date')->count();
				$ApplyOnlinesUnionList->bind(':start', $StartTime, 'date')->bind(':end', $EndTime, 'date')->count();
			}
			$ApplyOnlinesList->unionAll($ApplyOnlinesUnionList);
		}


		/*$ApplyOnlinesUnionPVCapacity = $this->find('all',[
				'join'   => $join_arr,
				'fields'    => array('TotalCapacityData'=>'count(Applications.id)'),
				'conditions'=> $conditionUnion_arr]);*/

		$arrResult['list']              = $ApplyOnlinesList;
		$arrResult['TotalCapacityData'] = 0;
		return $arrResult;
		//} 
	}
	/**
	 * GenerateRegistrationNo
	 * Behaviour : public
	 * Parameter : application all parameter of selected application
	 * @defination : In order to generate registration number.
	 */
	public function GenerateRegistrationNo($application)
	{
		$id 					= $application->id;
		$ApplicationCategory 	= TableRegistry::get('ApplicationCategory');
		$ApplicationStages 		= TableRegistry::get('ApplicationStages');
		$appCategoryDetails 	= $ApplicationCategory->find('all', array('conditions' => array('id' => $application->application_type)))->first();

		$financialyear  		= $this->GetGenerateFinancialYear(date('Y-m-d'));
		$month 					= date('m');
		$id             		= $application->id;
		$appCategoryDetails 	= $ApplicationCategory->find('all', array('conditions' => array('id' => $application->application_type)))->first();
		$applicationCount 		= $ApplicationStages->find('all', array(
			'conditions' => array(
				'ApplicationStages.stage' => $ApplicationStages->APPROVED_FROM_GEDA,
				"DATE_FORMAT(ApplicationStages.created, '%m') = " => date('m'),
				'applications.payment_status'	=> 1
			),
			'join' 		=> array(['table' => 'applications', 'type' => 'left', 'conditions' => 'ApplicationStages.application_id=applications.id'])
		))->distinct(['application_id'])->toArray();
		$applicationMonth		= str_pad(count($applicationCount), 2, "0", STR_PAD_LEFT);
		$applicationCount 		= $ApplicationStages->find('all', array(
			'conditions' => array(
				'ApplicationStages.stage'		=> $ApplicationStages->APPROVED_FROM_GEDA,
				'applications.payment_status'	=> 1,
				'applications.application_type'	=> $application->application_type
			),
			'join' 		=> array(['table' => 'applications', 'type' => 'left', 'conditions' => 'ApplicationStages.application_id=applications.id'])
		))->distinct(['application_id'])->toArray();
		$applicationType		= str_pad(count($applicationCount), 2, "0", STR_PAD_LEFT);;


		//$registration_no 		= "PLGEDA/".$financialyear.$month.$applicationMonth."/".$appCategoryDetails->category_short_name."/".str_pad($id,7, "0", STR_PAD_LEFT);
		$appCategoryDetails->category_short_name = ($application->application_type == 5 && $application->kusum_type == 1) ? 'PMK-A' : (($application->application_type == 5 && $application->kusum_type == 2) ? 'PMK-C'  : $appCategoryDetails->category_short_name);
		$registration_no 		= "GEDA/PR" . "/" . $appCategoryDetails->category_short_name . "/" . $financialyear . "/" . $month . "/" . str_pad($id, 2, "0", STR_PAD_LEFT) . "/" . $applicationType;

		return $registration_no;
	}
	/**
	 * GenerateApplicationNo
	 * Behaviour : public
	 * Parameter : application all parameter of selected application
	 * @defination : In order to generate application number.
	 */
	public function GenerateApplicationNo($application)
	{
		$id 					= $application->id;
		$ApplicationCategory 	= TableRegistry::get('ApplicationCategory');
		$appCategoryDetails 	= $ApplicationCategory->find('all', array('conditions' => array('id' => $application->application_type)))->first();

		$appCategoryDetails->category_short_name = ($application->application_type == 5 && $application->kusum_type == 1) ? 'PMK-A' : (($application->application_type == 5 && $application->kusum_type == 2) ? 'PMK-C'  : $appCategoryDetails->category_short_name);
		$financialyear  		= $this->GetGenerateFinancialYear(date('Y-m-d'));
		$application_no 		= "GEDA/RE/" . $financialyear . "/" . $appCategoryDetails->category_short_name . "/" . str_pad($id, 2, "0", STR_PAD_LEFT);
		return $application_no;
	}
	/**
	 * GetGenerateFinancialYear
	 * Behaviour : public
	 * Parameter : date should be pass for which we need to get financial year
	 * @defination : In order to get financial year.
	 */
	public function GetGenerateFinancialYear($date = '')
	{
		$Month   = date("m", strtotime($date));
		$Year   = date("y", strtotime($date));
		$ChallanNo  = "";
		if (intval($Month) >= 1 && intval($Month) <= 3) {
			$ChallanNo  .= ($Year - 1) . "-" . date("y", strtotime($date));
		} else {
			$ChallanNo  .= $Year . "-" . (date("y", strtotime($date)) + 1);
		}
		return $ChallanNo;
	}
	public function GetStateCode($state, $state_name = "")
	{
		$STATENAME  = "";
		$Code       = "";
		$States     = TableRegistry::get('States');
		$arrState   = $States->find("all", ['conditions' => ['OR' => ['States.id' => $state, 'States.statename' => $state]]])->first();
		if (!empty($arrState)) {
			$STATENAME = $arrState->state_code;
			$Code = $STATENAME;
		} else {
			$arrState   = $States->find("all", ['conditions' => ['OR' => ['LOWER(States.statename)' => strtolower($state_name)]]])->first();
			if (!empty($arrState)) {
				$STATENAME  = $arrState->state_code;
				$Code       = $STATENAME;
			} else {
				$STATENAME  = $state_name;
				$Code       = strtoupper(substr($STATENAME, 0, 2));
			}
		}
		return $Code;
	}
	/**
	 *  SendSMSActivationCode
	 * Behaviour : Public
	 * @defination :  Method for send otp msg to authority mobile .
	 */
	public function SendSMSActivationCode($application_id, $mobile, $message, $type = '')
	{
		$getapply_online = $this->find('all', array('conditions' => array('id' => $application_id)))->first();
		if (isset($getapply_online) && !empty($getapply_online) && !empty($mobile)) {
			$x = 4; // Amount of digits
			$min = pow(10, $x);
			$max = (pow(10, $x + 1) - 1);
			$activation_code    = rand($min, $max);

			$this->updateAll(
				array("otp" => $activation_code, 'otp_created_date' => $this->NOW(), 'modified' => $this->NOW()),
				array("id" => $application_id)
			);
			$message = str_replace('##ACTIVATION_CODE##', $activation_code, $message);

			$this->sendSMS($application_id, $mobile, $message, $type);
		}
	}
	/**
	 * generateApplicationPdf
	 * Behaviour : public
	 * @param : id  : id is use to generate applications, $isdownload=true
	 * @defination : Method is use to download .pdf file from download application
	 *
	 */
	public function generateApplicationPdf($id, $isdownload = true)
	{

		if (empty($id)) {
			return 0;
		} else {
			$applicationId 	= decode($id);
			if (empty($applicationId)) {
				return 0;
			} else {
				$view = new View();
				$view->layout 			= false;
				$view->set("pageTitle", "Application");
				$EndUseElectricity 				= TableRegistry::get('EndUseElectricity');
				$ApplicationHybridAdditionalData = TableRegistry::get('ApplicationHybridAdditionalData');
				$ManufacturerMaster = TableRegistry::get('ManufacturerMaster');
				$OpenAccessApplicationDeveloperPermission = TableRegistry::get('OpenAccessApplicationDeveloperPermission');

				$PDFFILENAME 			= getRandomNumber();
				$LETTER_APPLICATION_NO 	= $applicationId;
				$ApplicationData 		= $this->viewDetailApplication($applicationId);
				$gridLevel 				= $this->arrGridLevel;
				$injectionLevel 		= $this->arrInjectionLevel;
				$EndSTU 				= $this->arrEndSTU;
				$EndCTU 				= $this->arrEndCTU;

				$EndUseDetails 			= $EndUseElectricity->find('all', array('conditions' => array('application_id' => $applicationId)))->first();
				$allModuleData			= $ApplicationHybridAdditionalData->fetchdatafordownloadapplication($applicationId, 1);
				$allInverterData		= $ApplicationHybridAdditionalData->fetchdatafordownloadapplication($applicationId, 2);
				$allWindData			= $ApplicationHybridAdditionalData->fetchdatafordownloadapplication($applicationId, 3);

				$view->set('ApplicationData', $ApplicationData);
				$view->set('EmptyDataCharector', '-');
				$view->set('gridLevel', $gridLevel);
				$view->set('injectionLevel', $injectionLevel);
				$view->set('EndSTU', $EndSTU);
				$view->set('EndCTU', $EndCTU);
				$view->set('EndUseDetails', $EndUseDetails);
				$view->set('allModuleData', $allModuleData);
				$view->set('allInverterData', $allInverterData);
				$view->set('allWindData', $allWindData);
				$view->set('type_of_spv', $OpenAccessApplicationDeveloperPermission->type_of_spv);
				$view->set('type_of_solar_panel', $OpenAccessApplicationDeveloperPermission->type_of_solar_panel);
				$view->set('type_of_inverter_used', $OpenAccessApplicationDeveloperPermission->type_of_inverter_used);
				/* Generate PDF for estimation of project */
				require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
				$dompdf = new Dompdf($options = array());
				$dompdf->set_base_path(SITE_ROOT_DIR_PATH . '/pdf/');
				$dompdf->set_option("isPhpEnabled", true);
				$view->set('dompdf', $dompdf);
				$html = $view->render('/Element/applications/download_application');
				$dompdf->loadHtml($html, 'UTF-8');

				$dompdf->setPaper('A4', 'portrait');
				$dompdf->render();
				if ($isdownload) {

					$dompdf->stream('applyonline-' . $LETTER_APPLICATION_NO);
				}
				$output = $dompdf->output();
				header("Content-type:application/pdf");
				header("Content-Disposition:inline;filename='" . $PDFFILENAME . ".pdf'");
				echo $output;
				die;
			}
		}
	}
	/**
	 * generateRegistrationPdf
	 * Behaviour : public
	 * @param : id  : id is use to generate applications, $isdownload=true
	 * @defination : Method is use to download .pdf file from download application
	 *
	 */
	public function generateRegistrationPdf($id, $isdownload = false)
	{

		if (empty($id)) {
			return 0;
		} else {
			$view = new View();
			$view->layout 			= false;
			$id 					= decode($id);
			$applicationDetails 	= $this->viewDetailApplication($id);
			$ApplicationStages 		= TableRegistry::get('ApplicationStages');
			$EndUseElectricity 		= TableRegistry::get('EndUseElectricity');
			$DiscomMaster 			= TableRegistry::get('DiscomMaster');
			$BranchMasters 			= TableRegistry::get('BranchMasters');
			$ApplicationHybridAdditionalData 			= TableRegistry::get('ApplicationHybridAdditionalData');
			$applyOnlineGedaDate 	= $ApplicationStages->getgedaletterStageData($id);
			$injectionLevel 		= $this->arrInjectionLevel;
			$gridLevel 				= $this->arrGridLevel;
			$EndSTU 				= $this->arrEndSTU;
			$EndCTU 				= $this->arrEndCTU;
			$EndUseDetails 			= $EndUseElectricity->find('all', array('conditions' => array('application_id' => $id)))->first();
			$discom_short_name		= "";
			$totalModulenos				= $ApplicationHybridAdditionalData->getwinddatasum($id, 1);
			$totalInverternos			= $ApplicationHybridAdditionalData->getwinddatasum($id, 2);
			//$ss_name 				= get_substation_details();
			$apiUrl  = 'https://akshayurjasetu.guvnl.com/API/SSMaster.php';


			$conn    = curl_init($apiUrl);

			curl_setopt($conn, CURLOPT_CONNECTTIMEOUT, 300);
			curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($conn, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($conn, CURLOPT_HTTPHEADER, [
				"Authorization: PsPuH#GvLUn^2005"
			]);
			curl_setopt($conn, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($conn, CURLOPT_RETURNTRANSFER, true);

			$response = curl_exec($conn);
			$responseData = json_decode($response, true);
			if (json_last_error() === JSON_ERROR_NONE) {
				// Extract only the SS ID and SS Name for the dropdown
				$dropdownData = [];
				foreach ($responseData as $item) {
					$dropdownData[$item['SS_ID']] = $item['SS_Name'];
				}
			}
			$ss_id = $applicationDetails->getco_substation;

			$keyToCheck = $applicationDetails->getco_substation;
			if (array_key_exists($keyToCheck, $dropdownData)) {
				$ss_name = $dropdownData[$keyToCheck];
			} else {
				$ss_name = '';
			}
			if (!empty($applicationDetails->discom)) {
				$discom_name  		= $BranchMasters->find("all", ['conditions' => ['id' => $applicationDetails->discom]])->first();
				$discom_short_name  = $DiscomMaster->find("all", ['conditions' => ['id' => $discom_name->discom_id]])->first();
			}
			$date = date('Y-m-d', strtotime($applyOnlineGedaDate->created));
			$NewText = '';
			$New_updates 	= New_updates;
			if ($New_updates <= $date) {
				if ($applicationDetails->application_type == 3) {
					$NewText = '8. The provisions of "Revised List of Models & Manufactures" (RLMM) & its amendment issued by MNRE from time to time shall be applicable.';
				} elseif ($applicationDetails->application_type == 4) {
					$NewText = '8. The provisions of "Revised List of Models & Manufactures" (RLMM), "Approved List of Models and Manufacturers" (ALMM) & its amendment issued by MNRE from time to time shall be applicable.';
				} else {
					$NewText = '8. The provisions of "Approved List of Models and Manufacturers" (ALMM) & its amendment issued by MNRE from time to time shall be applicable.';
				}
			}

			$view->set("pageTitle", "Application");
			$view->set('totalModulenos', $totalModulenos);
			$view->set('totalInverternos', $totalInverternos);
			$view->set('applicationDetails', $applicationDetails);
			$view->set('ss_name', $ss_name);
			$view->set('applyOnlineGedaDate', $applyOnlineGedaDate);
			$view->set('injectionLevel', $injectionLevel);
			$view->set('gridLevel', $gridLevel);
			$view->set('EndUseDetails', $EndUseDetails);
			$view->set('EndSTU', $EndSTU);
			$view->set('EndCTU', $EndCTU);
			$view->set('EmptyDataCharector', '');
			$view->set('discom_short_name', $discom_short_name);
			$view->set('NewText', $NewText);

			$PDFFILENAME = getRandomNumber();
			$LETTER_APPLICATION_NO 	= decode($id);
			$LETTER_APPLICATION_NO 	= $applicationDetails->application_no;

			/* Generate PDF for estimation of project */
			require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
			$dompdf = new Dompdf($options = array());
			$dompdf->set_base_path(SITE_ROOT_DIR_PATH . '/pdf/');
			$dompdf->set_option("isPhpEnabled", true);
			$view->set('dompdf', $dompdf);

			$html = $view->render('/Element/applications/download_registration_application');
			$dompdf->loadHtml($html, 'UTF-8');

			$dompdf->setPaper('A4', 'portrait');
			$dompdf->render();
			if ($isdownload) {

				$dompdf->stream('applyonline-' . $LETTER_APPLICATION_NO);
			}
			$output = $dompdf->output();
			header("Content-type:application/pdf");
			header("Content-Disposition:inline;filename=" . $LETTER_APPLICATION_NO . ".pdf");
			echo $output;
			die;
		}
	}

	/**
	 * generateOpenAccessRegistrationPdf
	 * Behaviour : public
	 * @param : id  : id is use to generate applications, $isdownload=true
	 * @defination : Method is use to download .pdf file from download application
	 *
	 */
	public function generateOpenAccessRegistrationPdf($id, $isdownload = false)
	{

		if (empty($id)) {
			return 0;
		} else {
			$view = new View();
			$view->layout 			= false;
			$id 					= decode($id);
			$applicationDetails 	= $this->viewDetailApplication($id);
			$ApplicationStages 		= TableRegistry::get('ApplicationStages');
			$EndUseElectricity 		= TableRegistry::get('EndUseElectricity');
			$DiscomMaster 			= TableRegistry::get('DiscomMaster');
			$BranchMasters 			= TableRegistry::get('BranchMasters');
			$ApplicationHybridAdditionalData 			= TableRegistry::get('ApplicationHybridAdditionalData');
			$applyOnlineGedaDate 	= $ApplicationStages->getgedaletterStageData($id);
			$injectionLevel 		= $this->arrInjectionLevel;
			$gridLevel 				= $this->arrGridLevel;
			$EndSTU 				= $this->arrEndSTU;
			$EndCTU 				= $this->arrEndCTU;
			$EndUseDetails 			= $EndUseElectricity->find('all', array('conditions' => array('application_id' => $id)))->first();
			$discom_short_name		= "";
			$totalModulenos				= $ApplicationHybridAdditionalData->getwinddatasum($id, 1);
			$totalInverternos			= $ApplicationHybridAdditionalData->getwinddatasum($id, 2);

			if (!empty($applicationDetails->discom)) {
				$discom_name  		= $BranchMasters->find("all", ['conditions' => ['id' => $applicationDetails->discom]])->first();
				$discom_short_name  = $DiscomMaster->find("all", ['conditions' => ['id' => $discom_name->discom_id]])->first();
			}
			$date = date('Y-m-d', strtotime($applyOnlineGedaDate->created));
			$NewText = '';
			$New_updates 	= New_updates;
			if ($New_updates <= $date) {
				if ($applicationDetails->application_type == 3) {
					$NewText = '8. The provisions of "Revised List of Models & Manufactures" (RLMM) & its amendment issued by MNRE from time to time shall be applicable.';
				} elseif ($applicationDetails->application_type == 4) {
					$NewText = '8. The provisions of "Revised List of Models & Manufactures" (RLMM), "Approved List of Models and Manufacturers" (ALMM) & its amendment issued by MNRE from time to time shall be applicable.';
				} else {
					$NewText = '8. The provisions of "Approved List of Models and Manufacturers" (ALMM) & its amendment issued by MNRE from time to time shall be applicable.';
				}
			}

			$view->set("pageTitle", "Application");
			$view->set('totalModulenos', $totalModulenos);
			$view->set('totalInverternos', $totalInverternos);
			$view->set('applicationDetails', $applicationDetails);
			$view->set('applyOnlineGedaDate', $applyOnlineGedaDate);
			$view->set('injectionLevel', $injectionLevel);
			$view->set('gridLevel', $gridLevel);
			$view->set('EndUseDetails', $EndUseDetails);
			$view->set('EndSTU', $EndSTU);
			$view->set('EndCTU', $EndCTU);
			$view->set('EmptyDataCharector', '');
			$view->set('discom_short_name', $discom_short_name);
			$view->set('NewText', $NewText);

			$PDFFILENAME = getRandomNumber();
			$LETTER_APPLICATION_NO 	= decode($id);
			$LETTER_APPLICATION_NO 	= $applicationDetails->application_no;

			/* Generate PDF for estimation of project */
			require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
			$dompdf = new Dompdf($options = array());
			$dompdf->set_base_path(SITE_ROOT_DIR_PATH . '/pdf/');
			$dompdf->set_option("isPhpEnabled", true);
			$view->set('dompdf', $dompdf);

			$html = $view->render('/Element/applications/download_open_access_registration_application');
			$dompdf->loadHtml($html, 'UTF-8');

			$dompdf->setPaper('A4', 'portrait');
			$dompdf->render();
			if ($isdownload) {

				$dompdf->stream('applyonline-' . $LETTER_APPLICATION_NO);
			}
			$output = $dompdf->output();
			header("Content-type:application/pdf");
			header("Content-Disposition:inline;filename=" . $LETTER_APPLICATION_NO . ".pdf");
			echo $output;
			die;
		}
	}
	public function viewDetailApplication($application_id)
	{
		$fields             = [
			'Applications.id',
			'Applications.customer_id',
			'Applications.installer_id',
			'Applications.discom',
			'Applications.application_type',
			'Applications.kusum_type',
			'Applications.name_of_applicant',
			'Applications.address',
			'Applications.address1',
			'Applications.taluka',
			'Applications.pincode',
			'Applications.city',
			'Applications.state',
			'Applications.district',
			'Applications.district_code',
			'Applications.type_of_applicant',
			'Applications.applicant_others',
			'Applications.contact',
			'Applications.mobile',
			'Applications.email',
			'Applications.pan',
			'Applications.application_status',
			'Applications.otp',
			'Applications.otp_created_date',
			'Applications.otp_verified_status',
			'Applications.GST',
			'Applications.agency_code',
			'Applications.upload_undertaking',
			'Applications.msme',
			'Applications.a_msme',
			'Applications.name_director',
			'Applications.type_director',
			'Applications.type_director_others',
			'Applications.director_whatsapp',
			'Applications.director_mobile',
			'Applications.director_email',
			'Applications.name_authority',
			'Applications.type_authority',
			'Applications.type_authority_others',
			'Applications.d_file_board',
			'Applications.authority_whatsapp',
			'Applications.authority_mobile',
			'Applications.authority_email',
			'Applications.pan_card',
			'Applications.gst_certificate',
			'Applications.registration_document',
			'Applications.wtg_no',
			'Applications.capacity_wtg',
			'Applications.total_capacity',
			'Applications.make',
			'Applications.pv_capacity_ac',
			'Applications.pv_capacity_dc',
			'Applications.grid_connectivity',
			'Applications.injection_level',
			'Applications.injection_level_ctu',
			'Applications.getco_substation',
			'Applications.f_sale_discom',
			'Applications.project_state',
			'Applications.project_village',
			'Applications.project_taluka',
			'Applications.project_district',
			'Applications.project_energy',
			'Applications.comm_date',
			'Applications.project_estimated_cost',
			'Applications.approx_generation',
			'Applications.application_fee',
			'Applications.gst_fees',
			'Applications.application_total_fee',
			'Applications.tds_deduction',
			'Applications.payment_status',
			'Applications.geda_approval',
			'Applications.approved_by',
			'Applications.reject_reason',
			'Applications.application_no',
			'Applications.registration_no',
			'Applications.created',
			'Applications.created_by',
			'Applications.modified',
			'Applications.modified_by',
			'Applications.stateflg',
			'Applications.total_wind_hybrid_capacity',
			'Applications.module_hybrid_capacity',
			'Applications.inverter_hybrid_capacity',
			'application_category.category_name',
			'application_category.route_name',
			'application_category.color_code',
			'developer_customers.name',
			'developers.installer_name',
			'district_master.name',
			'dm_project.name'
		];
		$join_arr  		= [
			'application_category'	=> ['table' => 'application_category', 'type' => 'left', 'conditions' => 'Applications.application_type=application_category.id'],
			'district_master'		=> ['table' => 'district_master', 'type' => 'left', 'conditions' => 'Applications.district=district_master.id'],
			'dm_project'			=> ['table' => 'district_master', 'type' => 'left', 'conditions' => 'Applications.project_district=dm_project.id'],
			'developer_customers' 	=> ['table' => 'developer_customers', 'type' => 'left', 'conditions' => 'developer_customers.id = Applications.customer_id'],
			'developers' 			=> ['table' => 'developers', 'type' => 'left', 'conditions' => 'developer_customers.installer_id = developers.id']
		];
		$arrApplication = $this->find('all', array(
			'fields' 	=> $fields,
			'join' 		=> $join_arr,
			'conditions' => array('Applications.id' => $application_id)
		))->first();

		return $arrApplication;
	}
	/**
	 * generateRePaymentReceiptPdf
	 * Behaviour : public
	 * @param : id  : Id is use to identify for which application letter file should be downlaoded, $isdownload=true
	 * @defination : Method is use to download .pdf file from applyonline listing
	 *
	 */
	public function generateRePaymentReceiptPdf($id, $isdownload = true, $mailData = false)
	{
		$ReSuccessPayment 		= TableRegistry::get('ReSuccessPayment');
		$ReApplicationPayment 	= TableRegistry::get('ReApplicationPayment');

		$Installers 			= TableRegistry::get('Installers');
		$MembersTable 			= TableRegistry::get('Members');
		$BranchMasters 			= TableRegistry::get('BranchMasters');
		$DiscomMaster 			= TableRegistry::get('DiscomMaster');
		$ApplyOnlineApprovals 	= TableRegistry::get('ApplyOnlineApprovals');

		if (empty($id)) {
			$this->Flash->error('Please Select Valid Installer.');
			return $this->redirect('home');
		} else {
			$encode_id 					= $id;
			$id 						= intval(decode($id));
			$payment_data 				= $ReSuccessPayment->find('all', array('conditions' => array('application_id' => $id), 'order' => array('id' => 'desc')))->first();

			$payment_details 			= $ReApplicationPayment->find('all', array('conditions' => array('id' => $payment_data->payment_id)))->first();

			$applyOnlinesData 			= $this->viewDetailApplication($id);

			$applyOnlinesData->aid 		= str_pad($id, 2, "0", STR_PAD_LEFT);
			$LETTER_APPLICATION_NO 		= $applyOnlinesData->aid;
			$APPLICATION_DATE 			= date("d.m.Y", strtotime($applyOnlinesData->created));
		}

		$view = new View();
		$view->layout 			= false;

		$view->set("pageTitle", "Application View");
		$view->set('Applications', $applyOnlinesData);

		$view->set('LETTER_APPLICATION_NO', $LETTER_APPLICATION_NO);
		$view->set('APPLICATION_DATE', $APPLICATION_DATE);

		//$view->set('applyOnlineGedaDate',$applyOnlineGedaDate);
		$view->set('payment_data', $payment_data);
		$view->set('payment_details', $payment_details);



		/* Generate PDF for estimation of project */
		require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
		$dompdf = new Dompdf($options = array());
		$dompdf->set_option("isPhpEnabled", true);
		$view->set('dompdf', $dompdf);
		$dompdf->set_base_path(SITE_ROOT_DIR_PATH . '/pdf/');

		$html = $view->render('/Element/re_paymentreceipt');

		$dompdf->loadHtml($html, 'UTF-8');
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();

		// Output the generated PDF to Browser
		if ($isdownload) {
			$dompdf->stream('applicationpayment-' . $LETTER_APPLICATION_NO);
		}
		$output = @$dompdf->output();
		if ($mailData) {
			$pdfPath 	= WWW_ROOT . '/tmp/paymentReceipt-' . $LETTER_APPLICATION_NO . '.pdf';
			file_put_contents($pdfPath, $output);
			return $pdfPath;
		} else {
			header("Content-type:application/pdf");
			header("Content-Disposition:inline;filename='" . $LETTER_APPLICATION_NO . ".pdf'");
			echo $output;
		}
		die;
	}
	/*
	 * Send Application Letter To Customer
	 * @param mixed What page to display
	 * @return void
	 */
	public function SendEmailToCustomer($id = 0, $message_id = 0)
	{
		$this->autoRender           = false;
		$applyOnlinesData           = $this->viewApplication($id);
		$applyOnlinesData->aid      = $this->GenerateApplicationNo($applyOnlinesData);
		$ApplicationsMessage         = TableRegistry::get('ApplicationsMessage');
		$GetLastMessage             = $ApplicationsMessage->GetLastMessageByApplication($id, $message_id);

		$LETTER_APPLICATION_NO      = $applyOnlinesData->aid;
		$CUSTOMER_EMAIL             = $applyOnlinesData->email;
		$CUSTOMER_NAME              = trim($applyOnlinesData->customer_name_prefixed . " " . $applyOnlinesData->name_of_consumer_applicant);
		$APPLICATION_DATE           = date("d.m.Y", strtotime($applyOnlinesData->created));
		$MESSAGE_BY                 = isset($GetLastMessage['comment_by']) ? $GetLastMessage['comment_by'] : "";
		$MESSAGE                    = isset($GetLastMessage['message']) ? $GetLastMessage['message'] : "";
		$EmailVars                  = array(
			"LETTER_APPLICATION_NO" => $LETTER_APPLICATION_NO,
			"APPLICATION_DATE" => $APPLICATION_DATE,
			"MESSAGE" => $MESSAGE,
			"MESSAGE_BY" => $MESSAGE_BY,
			"CUSTOMER_NAME" => $CUSTOMER_NAME
		);

		$to         = !empty($CUSTOMER_EMAIL) ? $CUSTOMER_EMAIL : "";
		$email      = new Email('default');
		$subject    = "[REG: Application No. " . $LETTER_APPLICATION_NO . "] Clarification required in the submitted document";
		$email->profile('default');
		$email->viewVars($EmailVars);
		if (!empty($to)) {
			$message_send = $email->template('email_template_for_communication', 'default')
				->emailFormat('html')
				->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
				->to($to)
				->subject(Configure::read('EMAIL_ENV') . $subject)
				->send();
		}

		if (!empty($applyOnlinesData->installer_email)) {
			$email          = new Email('default');
			$email->profile('default');
			$email->viewVars($EmailVars);
			$message_send   = $email->template('email_template_for_communication', 'default')
				->emailFormat('html')
				->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
				->to($applyOnlinesData->installer_email)
				->subject(Configure::read('EMAIL_ENV') . $subject)
				->send();
		}

		return true;
	}
	/**
	 * generateGeoPaymentReceiptPdf
	 * Behaviour : public
	 * @param : id  : Id is use to identify for which application letter file should be downlaoded, $isdownload=true
	 * @defination : Method is use to download .pdf file from applyonline listing
	 *
	 */
	public function generateGeoPaymentReceiptPdf($id, $isdownload = true, $mailData = false)
	{
		$GeoSuccessPayment 		= TableRegistry::get('GeoSuccessPayment');
		$GeoApplicationPayment 	= TableRegistry::get('GeoApplicationPayment');
		$ApplicationGeoLocation = TableRegistry::get('ApplicationGeoLocation');
		$ApplicationCategory    = TableRegistry::get('ApplicationCategory');

		$Developers 			= TableRegistry::get('Developers');
		$MembersTable 			= TableRegistry::get('Members');
		$BranchMasters 			= TableRegistry::get('BranchMasters');
		$DiscomMaster 			= TableRegistry::get('DiscomMaster');

		if (empty($id)) {
			$this->Flash->error('Please Select Valid Installer.');
			return $this->redirect('home');
		} else {
			$encode_id 					= $id;
			$id 						= intval(decode($id));

			$payment_data 				= $GeoSuccessPayment->find('all', array('conditions' => array('payment_id' => $id), 'order' => array('id' => 'desc')))->first();

			$payment_details 			= $GeoApplicationPayment->find('all', array('conditions' => array('id' => $payment_data->payment_id)))->first();

			$data  = json_decode($payment_details->payment_data);
			$geo_id   					= explode(',', $data->merchant_param2);
			//echo"<pre>"; print_r($geo_id); die();
			$geo_application_data 		= $ApplicationGeoLocation->find('all', array('conditions' => array('id IN' => ($geo_id))))->toarray();
			// foreach ($geo_application_data as $key => $value) {
			// 	$capacity[] = $value['wtg_capacity']/1000;
			// }
			// $capacitysum   				= array_sum($capacity);
			$count_of_application 		= sizeof($geo_application_data);
			$applicationData 			= $this->viewDetailApplication($payment_data->application_id);
			$applicationData->aid 		= str_pad($payment_data->application_id, 2, "0", STR_PAD_LEFT);
			$LETTER_APPLICATION_NO 		= $applicationData->aid;
			$APPLICATION_DATE 			= date("d.m.Y", strtotime($applicationData->created));
			$applicationCategory 	    = $ApplicationCategory->find('all', array('conditions' => array('id' => $applicationData->application_type)))->first();
			$InstallersData  		= $Developers->find('all', array('conditions' => array('id' => $applicationData->installer_id)))->first();
			//echo"<pre>"; print_r($InstallersData); die();

		}

		$view = new View();
		$view->layout 			= false;

		$view->set("pageTitle", "Application View");
		$view->set('Applications', $applicationData);
		$view->set('count_of_application', $count_of_application);
		$view->set('applicationCategory', $applicationCategory);
		$view->set('geo_application_data', $geo_application_data);
		$view->set('InstallersData', $InstallersData);

		$view->set('LETTER_APPLICATION_NO', $LETTER_APPLICATION_NO);
		$view->set('APPLICATION_DATE', $APPLICATION_DATE);
		// $view->set('capacitysum',$capacitysum);

		//$view->set('applyOnlineGedaDate',$applyOnlineGedaDate);
		$view->set('payment_data', $payment_data);
		$view->set('payment_details', $payment_details);



		/* Generate PDF for estimation of project */
		require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
		$dompdf = new Dompdf($options = array());
		$dompdf->set_option("isPhpEnabled", true);
		$view->set('dompdf', $dompdf);
		$dompdf->set_base_path(SITE_ROOT_DIR_PATH . '/pdf/');

		$html = $view->render('/Element/geo_paymentreceipt');

		$dompdf->loadHtml($html, 'UTF-8');
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();

		// Output the generated PDF to Browser
		if ($isdownload) {
			$dompdf->stream('geoapplicationpayment-' . $LETTER_APPLICATION_NO);
		}
		$output = @$dompdf->output();
		if ($mailData) {
			$pdfPath 	= WWW_ROOT . '/tmp/paymentReceipt-' . $LETTER_APPLICATION_NO . '.pdf';
			file_put_contents($pdfPath, $output);
			return $pdfPath;
		} else {
			header("Content-type:application/pdf");
			header("Content-Disposition:inline;filename='" . $LETTER_APPLICATION_NO . ".pdf'");
			echo $output;
		}
		die;
	}

	/**
	 * generateGeoShiftingPaymentReceiptPdf
	 * Behaviour : public
	 * @param : id  : Id is use to identify for which application letter file should be downlaoded, $isdownload=true
	 * @defination : Method is use to download .pdf file from applyonline listing
	 *
	 */
	public function generateGeoShiftingPaymentReceiptPdf($id, $isdownload = true, $mailData = false)
	{
		$GeoShiftingSuccessPayment 		= TableRegistry::get('GeoShiftingSuccessPayment');
		$GeoShiftingApplicationPayment 	= TableRegistry::get('GeoShiftingApplicationPayment');
		$GeoShiftingApplication = TableRegistry::get('GeoShiftingApplication');
		$ApplicationGeoLocation = TableRegistry::get('ApplicationGeoLocation');
		$ApplicationCategory    = TableRegistry::get('ApplicationCategory');

		$Developers 			= TableRegistry::get('Developers');
		$MembersTable 			= TableRegistry::get('Members');
		$BranchMasters 			= TableRegistry::get('BranchMasters');
		$DiscomMaster 			= TableRegistry::get('DiscomMaster');

		if (empty($id)) {
			$this->Flash->error('Please Select Valid Installer.');
			return $this->redirect('home');
		} else {
			$encode_id 					= $id;
			$id 						= intval(decode($id));

			$payment_data 				= $GeoShiftingSuccessPayment->find('all', array('conditions' => array('payment_id' => $id), 'order' => array('id' => 'desc')))->first();

			$payment_details 			= $GeoShiftingApplicationPayment->find('all', array('conditions' => array('id' => $payment_data->payment_id)))->first();

			$data  = json_decode($payment_details->payment_data);
			$geo_id   					= explode(',', $data->merchant_param2);
			//echo"<pre>"; print_r($geo_id); die();
			// $geo_application_data 		= $ApplicationGeoLocation->find('all',array('conditions'=>array('id IN' => ($geo_id))))->toarray();
			$geo_application_data 		= $GeoShiftingApplication->find('all', array('conditions' => array('geo_application_id IN' => ($geo_id))))->toarray();
			// foreach ($geo_application_data as $key => $value) {
			// 	$capacity[] = $value['wtg_capacity']/1000;
			// }
			// $capacitysum   				= array_sum($capacity);
			$count_of_application 		= sizeof($geo_application_data);
			$applicationData 			= $this->viewDetailApplication($payment_data->application_id);
			$applicationData->aid 		= str_pad($payment_data->application_id, 2, "0", STR_PAD_LEFT);
			$LETTER_APPLICATION_NO 		= $applicationData->aid;
			$APPLICATION_DATE 			= date("d.m.Y", strtotime($applicationData->created));
			$applicationCategory 	    = $ApplicationCategory->find('all', array('conditions' => array('id' => $applicationData->application_type)))->first();
			$InstallersData  		= $Developers->find('all', array('conditions' => array('id' => $applicationData->installer_id)))->first();
			//echo"<pre>"; print_r($InstallersData); die();

		}

		$view = new View();
		$view->layout 			= false;

		$view->set("pageTitle", "Application View");
		$view->set('Applications', $applicationData);
		$view->set('count_of_application', $count_of_application);
		$view->set('applicationCategory', $applicationCategory);
		$view->set('geo_application_data', $geo_application_data);
		$view->set('InstallersData', $InstallersData);

		$view->set('LETTER_APPLICATION_NO', $LETTER_APPLICATION_NO);
		$view->set('APPLICATION_DATE', $APPLICATION_DATE);
		// $view->set('capacitysum',$capacitysum);

		//$view->set('applyOnlineGedaDate',$applyOnlineGedaDate);
		$view->set('payment_data', $payment_data);
		$view->set('payment_details', $payment_details);



		/* Generate PDF for estimation of project */
		require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
		$dompdf = new Dompdf($options = array());
		$dompdf->set_option("isPhpEnabled", true);
		$view->set('dompdf', $dompdf);
		$dompdf->set_base_path(SITE_ROOT_DIR_PATH . '/pdf/');

		$html = $view->render('/Element/geo_shifting_paymentreceipt');

		$dompdf->loadHtml($html, 'UTF-8');
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();

		// Output the generated PDF to Browser
		if ($isdownload) {
			$dompdf->stream('geoapplicationpayment-' . $LETTER_APPLICATION_NO);
		}
		$output = @$dompdf->output();
		if ($mailData) {
			$pdfPath 	= WWW_ROOT . '/tmp/paymentReceipt-' . $LETTER_APPLICATION_NO . '.pdf';
			file_put_contents($pdfPath, $output);
			return $pdfPath;
		} else {
			header("Content-type:application/pdf");
			header("Content-Disposition:inline;filename='" . $LETTER_APPLICATION_NO . ".pdf'");
			echo $output;
		}
		die;
	}

	
}
