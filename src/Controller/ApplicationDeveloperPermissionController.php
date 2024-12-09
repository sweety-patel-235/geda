<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\View\View;
use Cake\ORM\TableRegistry;
use Cake\ORM\Table;
use Hdfc\Hdfc;
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

class ApplicationDeveloperPermissionController extends FrontAppController
{

	/*
	 * initialize controller
	 *
	 * @return void
	 */
	public function initialize()
	{
		// Always enable the CSRF component.
		parent::initialize();
		$this->loadModel('ApiToken');
		$this->loadModel('Parameters');
		$this->loadModel('States');
		$this->loadModel('Sessions');
		$this->loadModel('Couchdb');
		$this->loadModel('Developers');
		$this->loadModel('Applications');
		$this->loadModel('DeveloperCustomers');
		$this->loadModel('DistrictMaster');
		$this->loadModel('TalukaMaster');
		$this->loadModel('OpenAccessApplicationDeveloperPermission');
		$this->loadModel('WindApplicationDeveloperPermission');
		$this->loadModel('ManufacturerMaster');
		$this->loadModel('ApplicationOpenAccessAdditionalData');
		$this->loadModel('ReCouchdb');
		$this->loadModel('DeveloperPermissionPayment');
		$this->loadModel('EndUseElectricity');
		$this->loadModel('wind_manufacturer_rlmm');
		$this->loadModel('WindEnergyAdditionalData');
		$this->loadModel('ApplicationGeoLocation');
		$this->loadModel('WindWtgDetail');
		$this->loadModel('OpenAccessLandDetails');
		$this->loadModel('WindLandDetails');
		$this->loadModel('WindEvaculationPoolingData');
		$this->loadModel('WindEvaculationGetcoData');
		$this->loadModel('WindShareDetails');
		$this->loadModel('DeveloperApplicationsDocs');
		$this->loadModel('MemberRoles');
		$this->loadModel('DeveloperApplicationQuery');
		$this->loadModel('ApplicationStages');
		$this->loadModel('ApplicationsMessage');
		$this->loadModel('WindHybridDpLetter');

		$this->loadModel('HybridAdditionalData');
		$this->loadModel('HybridApplicationDeveloperPermission');
		$this->loadModel('HybridEnergyAdditionalData');
		$this->loadModel('HybridWtgDetail');
		$this->loadModel('HybridLandDetails');
		$this->loadModel('HybridEvaculationPoolingData');
		$this->loadModel('HybridEvaculationGetcoData');
		$this->loadModel('HybridShareDetails');
	}

	/**
	 * Final Registration of Open-Access 
	 */
	public function OpenAccessDeveloperPermission($id = 0, $activetab = 0)
	{
		$tab_id 				= ($activetab > 1) ? $activetab : 1;
		$type_of_applicant 		= $this->ApiToken->arrFirmDropdown;
		$designation 			= $this->ApiToken->arrDesignation;
		$customerId 			= $this->Session->read("Customers.id");
		$member_id 				= $this->Session->read("Members.id");
		$ses_customer_type 		= $this->Session->read('Customers.customer_type');


		if ($ses_customer_type == "installer") {
			$is_installer 		= true;
			$customer_details 	= $this->DeveloperCustomers->find('all', array('conditions' => array('id' => $customerId)))->first();
			$installer_id 		= $customer_details['installer_id'];
		}

		if (empty($customerId) && empty($member_id)) {
			return $this->redirect(URL_HTTP . '/home');
		}
		$count 		= $this->OpenAccessApplicationDeveloperPermission->find('all', array('conditions' => array('application_id' => decode($id))))->count();

		if ($count > 0) {
			$Applications = $this->OpenAccessApplicationDeveloperPermission->find('all', array('conditions' => array('application_id' => decode($id))))->first();
			$tid = (!empty($id)) ? decode($id) : $id;

			$tab = (!empty($Applications->tab_id)) ? $Applications->tab_id : $Applications->tab_id;
		} else {
			$Applications 		= $this->Applications->find('all', array('conditions' => array('id' => decode($id))))->first();
			$tid = '';
			$tab = 0;
		}

		$arrStateData 		= $this->States->find("list", ['keyField' => 'statename', 'valueField' => 'statename', 'conditions' => ['id' => 4]])->toArray();
		$district 			= $this->DistrictMaster->find("list", ['keyField' => 'id', 'valueField' => 'name', 'conditions' => ['state_id' => 4]]);
		$discom_arr = array();
		$discoms 	= $this->BranchMasters->find("list", ['keyField' => 'id', 'valueField' => 'title', 'conditions' => ['BranchMasters.status' => '1', 'BranchMasters.parent_id' => '0', 'BranchMasters.state' => $this->ApplyOnlines->gujarat_st_id]])->toArray();
		if (!empty($discoms)) {
			foreach ($discoms as $keyid => $title) {
				$discom_arr[$keyid] = $title;
			}
		}

		$application_errors = array();
		if (!empty($this->request->data)) {

			$cur_tab 	= $this->request->data['tab_id'];

			$errors		= array();
			$this->request->data['Applications']['application_id'] 	= $id;
			$this->request->data['Applications']['application_type'] = 2;

			$this->OpenAccessApplicationDeveloperPermission->dataRecord = $Applications;

			switch ($cur_tab) {
				case '1':
					$response 		= $this->open_access_general_profile($this->request->data);
					$Applications   = $this->OpenAccessApplicationDeveloperPermission->find('all', array('conditions' => array('application_id' => decode($id))))->first();
					break;

				case '2':
					$response 		= $this->open_access_technical_details($this->request->data);
					break;

				case '3':
					$response 		= $this->open_access_land_details($this->request->data);
					break;

				case '4':
					$response 		= $this->open_access_fees_structure($this->request->data);
					break;

				default:
					break;
			}
			$arrResponse 		= json_decode($response, 1);
			$application_errors = $arrResponse['response_errors'];

			$count 		= $this->OpenAccessApplicationDeveloperPermission->find('all', array('conditions' => array('application_id' => decode($id))))->count();

			if ($count > 0) {
				$Applications = $this->OpenAccessApplicationDeveloperPermission->find('all', array('conditions' => array('application_id' => decode($id))))->first();
				$tid = (!empty($id)) ? decode($id) : $id;
			
			} 

			if (!empty($application_errors)) {

				$Applications->errors($application_errors);

				$tab_id 		= $cur_tab;
			} else {

				if (isset($this->request->data['next_' . $cur_tab]) && $arrResponse['success'] == '1') {

					$tab_id 		= $cur_tab + 1;
					// if ($tab_id == 4) {
					// 	$Applications = $this->OpenAccessApplicationDeveloperPermission->find('all', array('conditions' => array('application_id' => decode($id))))->first();
					// 	$tab = (!empty($Applications->tab_id)) ? $Applications->tab_id : $Applications->tab_id;
					// }
				} else {

					$tab_id 		= $cur_tab;

					if ($tab_id == 4)
						return $this->redirect(URL_HTTP . '/Applications/list');
				}
			}
		}

		$feesDetails = [];
		$applicationCategory = [];

		if (!empty($Applications) && isset($Applications) && $count > 0) {
			$applicationCategory 	= $this->ApplicationCategory->find('all', array('conditions' => array('id' => $Applications->application_type)))->first();
			$additionalDataSum = $this->ApplicationOpenAccessAdditionalData->getOpenAccessDataSum($Applications->id, '2');
			$provisionalApplication = $this->Applications->find('all', array('conditions' => array('id' => $Applications->application_id)))->first();
			$provisionalFees = $provisionalApplication['application_fee'];
			$invTotCapacity = $additionalDataSum['mod_inv_total_capacity'];

			if ($invTotCapacity < 1) {
				$feesDetails['application_fees'] = $applicationCategory['dev_per_fee_less_than_one_mb'] - $provisionalFees;
				$feesDetails['application_tax_percentage'] = $applicationCategory['dev_per_tax_per'];
				$feesDetails['gst_fees'] = $feesDetails['application_fees'] * $applicationCategory['dev_per_tax_per'] / 100;
				$feesDetails['application_total_fee'] = $feesDetails['application_fees'] + $feesDetails['gst_fees'];
			} else {

				$appFees = $applicationCategory['dev_per_one_mb_and_above'] * $invTotCapacity;
				$feesDetails['application_fees'] = $appFees - $provisionalFees;
				$feesDetails['application_tax_percentage'] = $applicationCategory['dev_per_tax_per'];
				$feesDetails['gst_fees'] = $feesDetails['application_fees'] * $applicationCategory['dev_per_tax_per'] / 100;
				$feesDetails['application_total_fee'] = $feesDetails['application_fees'] + $feesDetails['gst_fees'];
			}
		}

		$Module_Data 	= $this->ApplicationOpenAccessAdditionalData->fetchdata($Applications->id, 1);
		$errorModule 			= 0;
		if (!empty($application_errors) || (isset($arrResponse['addMoreError']) && $arrResponse['addMoreError'] == 1)) {
			$Module_Data 		= array();
			if (isset($this->request->data['nos_mod'])) {
				foreach ($this->request->data['nos_mod'] as $key => $value) {
					$Module_Data[$key]['nos_mod_inv'] 				= $value;
					$Module_Data[$key]['mod_inv_capacity'] 			= $this->request->data['mod_capacity'][$key];
					$Module_Data[$key]['mod_inv_total_capacity'] 	= $this->request->data['mod_total_capacity'][$key];
					$Module_Data[$key]['mod_inv_make'] 				= $this->request->data['mod_make'][$key];
					$Module_Data[$key]['type_of_spv_technologies'] 	= $this->request->data['type_of_spv'][$key];
					$Module_Data[$key]['type_of_solar_panel'] 		= $this->request->data['type_of_solar'][$key];
					$Module_Data[$key]['application_id'] 			= $Applications->id;
					$Module_Data[$key]['capacity_type'] 			= 1;
					$Module_Data[$key]['id'] 						= isset($this->request->data['id_module'][$key]) ? $this->request->data['id_module'][$key] : 0;

					if (empty($value) || empty($this->request->data['mod_capacity'][$key]) || empty($this->request->data['mod_make'][$key]) || empty($this->request->data['type_of_spv'][$key]) || empty($this->request->data['type_of_solar'][$key])) {
						$errorModule 	= 1;
					}
				}
			}
		}
		$Inverter_Data 	= $this->ApplicationOpenAccessAdditionalData->fetchdata($Applications->id, 2);

		$errorInverter 			= 0;
		if (!empty($application_errors) || (isset($arrResponse['addMoreError']) && $arrResponse['addMoreError'] == 1)) {
			$Inverter_Data 		= array();
			if (isset($this->request->data['nos_inv'])) {
				foreach ($this->request->data['nos_inv'] as $key => $value) {
					$Inverter_Data[$key]['nos_mod_inv'] 				= $value;
					$Inverter_Data[$key]['mod_inv_capacity'] 		= $this->request->data['inv_capacity'][$key];
					$Inverter_Data[$key]['mod_inv_total_capacity'] 	= $this->request->data['inv_total_capacity'][$key];
					$Inverter_Data[$key]['mod_inv_make'] 			= $this->request->data['inv_make'][$key];
					$Inverter_Data[$key]['type_of_inverter_used'] 	= $this->request->data['type_of_inverter_used'][$key];
					$Inverter_Data[$key]['application_id'] 			= $Applications->id;
					$Inverter_Data[$key]['capacity_type'] 			= 2;
					$Inverter_Data[$key]['id'] 						= isset($this->request->data['id_inverter'][$key]) ? $this->request->data['id_inverter'][$key] : 0;

					if (empty($value) || empty($this->request->data['inv_capacity'][$key]) || empty($this->request->data['inv_make'][$key]) || empty($this->request->data['type_of_inverter_used'][$key])) {
						$errorInverter 	= 1;
					}
				}
			}
		}
		$Land_Data 	= $this->OpenAccessLandDetails->fetchdata($Applications->id);

		$Land_Data_1 	= $this->OpenAccessLandDetails->fetchdata($Applications->id);
		$errorLand	= 0;
		if (!empty($application_errors) || (isset($arrResponse['addMoreError']) && $arrResponse['addMoreError'] == 1)) {

			$Land_Data 		= array();

			if (isset($this->request->data['land_category'])) {

				foreach ($this->request->data['land_category'] as $key => $value) {

					$Land_Data[$key]['land_category'] 				= $value;
					$Land_Data[$key]['land_plot_servey_no'] 		= $this->request->data['land_plot_servey_no'][$key];
					$Land_Data[$key]['land_taluka'] 				= $this->request->data['land_taluka'][$key];
					$Land_Data[$key]['land_village'] 				= $this->request->data['land_village'][$key];
					$Land_Data[$key]['land_state'] 					= 'Gujarat';
					$Land_Data[$key]['land_district'] 				= $this->request->data['land_district'][$key];
					$Land_Data[$key]['land_latitude']				= $this->request->data['land_latitude'][$key];
					$Land_Data[$key]['land_longitude']				= $this->request->data['land_longitude'][$key];
					$Land_Data[$key]['area_of_land']				= $this->request->data['area_of_land'][$key];
					$Land_Data[$key]['deed_of_land']				= $this->request->data['deed_of_land'][$key];

					if (isset($this->request->data['deed_doc'][$key])) {
						$Land_Data[$key]['deed_doc']					= isset($this->request->data['deed_doc'][$key]) ? $this->request->data['deed_doc'][$key] : '';
					} else {
						$Land_Data[$key]['deed_doc']					= isset($Land_Data_1[$key]['deed_doc']) ? $Land_Data_1[$key]['deed_doc'] : '';
						$Land_Data[$key]['app_dev_per_id']					= isset($Land_Data_1[$key]['app_dev_per_id']) ? $Land_Data_1[$key]['app_dev_per_id'] : '';
					}

					$Land_Data[$key]['application_id'] 				= $Applications->id;
					$Land_Data[$key]['id'] 							= isset($this->request->data['id_land'][$key]) ? $this->request->data['id_land'][$key] : 0;

					if (empty($value) || empty($this->request->data['land_plot_servey_no'][$key]) || empty($this->request->data['land_taluka'][$key]) || empty($this->request->data['land_village'][$key]) || empty($this->request->data['land_state'][$key]) || empty($this->request->data['land_district'][$key]) || empty($this->request->data['land_latitude'][$key]) || empty($this->request->data['land_longitude'][$key]) || empty($this->request->data['area_of_land'][$key]) || empty($this->request->data['deed_of_land'][$key]) || empty($this->request->data['a_deed_doc'][$key])) {
						$errorLand 	= 1;
					}
					$taluka	= $this->TalukaMaster->find("list", ['keyField' => 'id', 'valueField' => 'name', 'conditions' => array('district_id' => $Land_Data[$key]['land_district'])])->toArray();
					$Land_Data[$key]['taluka']			= isset($taluka) && !empty($taluka) ? $taluka : '';
				}
			}
		}


		$type_manufacturer_mod 	= $this->ManufacturerMaster->manufacturerList(1);
		$type_manufacturer_inv 	= $this->ManufacturerMaster->manufacturerList(2);
		$developer_details 		= $this->DeveloperCustomers->find('all', array('conditions' => array('id' => $customerId)))->first();
		$totalInverternos		= $this->ApplicationOpenAccessAdditionalData->getOpenAccessDataSum($Applications->id, 2);
		$totalModulenos			= $this->ApplicationOpenAccessAdditionalData->getOpenAccessDataSum($Applications->id, 1);
		$totalInverterCapacity	= $this->ApplicationOpenAccessAdditionalData->getcapacitysum($Applications->id, 2);
		$totalModuleCapacity	= $this->ApplicationOpenAccessAdditionalData->getcapacitysum($Applications->id, 1);
		
		$this->set('pageTitle', 'Re-Application');
		$this->set('developer_details', $developer_details);
		$this->set('Applications', $Applications);
		$this->set("arrStateData", $arrStateData);
		$this->set("arrDistictData", $district);
		$this->set('Couchdb', $this->ReCouchdb);
		$this->set('type_of_applicant', $type_of_applicant);
		$this->set('designation', $designation);
		$this->set('totalInverternos', $totalInverternos);
		$this->set('totalModulenos', $totalModulenos);
		$this->set('totalInverterCapacity', $totalInverterCapacity);
		$this->set('totalModuleCapacity', $totalModuleCapacity);
		$this->set("tab_id", $tab_id);
		$this->set('type_manufacturer_mod', $type_manufacturer_mod);
		$this->set('type_manufacturer_inv', $type_manufacturer_inv);
		$this->set("discom_arr", $discom_arr);
		$this->set("applicationID", $tid);
		$this->set("ApplicationError", $application_errors);
		$this->set('Open_Access_Inverter_Data', $Inverter_Data);
		$this->set('Open_Access_Module_Data', $Module_Data);
		$this->set('Open_Access_Land_Data', $Land_Data);
		$this->set('typeOfSPP', $this->OpenAccessApplicationDeveloperPermission->type_of_spp);
		$this->set('typeOfMountingSystem', $this->OpenAccessApplicationDeveloperPermission->type_of_mounting_system);
		$this->set('typeOfspv', $this->OpenAccessApplicationDeveloperPermission->type_of_spv);
		$this->set('typeOfSolarPanel', $this->OpenAccessApplicationDeveloperPermission->type_of_solar_panel);
		$this->set('typeOfInverterUsed', $this->OpenAccessApplicationDeveloperPermission->type_of_inverter_used);
		$this->set('typeOfConsumer', $this->OpenAccessApplicationDeveloperPermission->type_of_consumer);
		$this->set('typeOfMsme', $this->OpenAccessApplicationDeveloperPermission->type_of_MSME);
		$this->set('endUseOfElectricity', $this->OpenAccessApplicationDeveloperPermission->end_use_of_electricity);
		$this->set('projectForRpo', $this->OpenAccessApplicationDeveloperPermission->project_for_rpo);
		$this->set('landCategory', $this->OpenAccessApplicationDeveloperPermission->land_category);
		$this->set('deedOfLand', $this->OpenAccessApplicationDeveloperPermission->deed_of_land);
		$this->set('captive', $this->OpenAccessApplicationDeveloperPermission->captive);
		$this->set('third_party', $this->OpenAccessApplicationDeveloperPermission->third_party);
		$this->set('getcoVoltageLevel', $this->OpenAccessApplicationDeveloperPermission->getco_voltage_level);
		$this->set("errorModule", $errorModule);
		$this->set("errorInverter", $errorInverter);
		$this->set("errorLand", $errorLand);
		$this->set('applicationCategory', $applicationCategory);
		$this->set('feesDetails', $feesDetails);
		$this->set('tab', $tab);
	}

	/*
	 *
	 * general_profile
	 *
	 * Behaviour : Private
	 *
	 * @param : $request_data   : tab1 form posted data should be passed
	 * @defination : Method is use to check validation of first tab and insert/update subsidy record for first tab.
	 *
	 */
	private function open_access_general_profile($request_data)
	{

		if (!empty($this->Session->read('Members.member_type')))
			$customerId = $this->Session->read("Members.id");
		else
			$customerId = $this->Session->read("Customers.id");


		$app_id = !empty($request_data['Applications']['application_id']) ? decode($request_data['Applications']['application_id']) : 0;

		$applicaiton_exist = 0;
		if (!empty($app_id)) {
			$applicaiton_exist 	= $this->OpenAccessApplicationDeveloperPermission->find('all', array('conditions' => array('application_id' => $app_id)))->first();
			$this->OpenAccessApplicationDeveloperPermission->dataPass = $request_data['Applications'];
		}
		if (empty($applicaiton_exist)) {
			$fields = [
				'application_type', 'name_of_applicant', 'address', 'address1', 'taluka', 'pincode', 'city', 'state', 'district', 'district_code',
				'type_of_applicant', 'registration_document', 'applicant_others', 'contact', 'mobile', 'email', 'pan', 'pan_card', 'GST', 'a_msme', 'upload_undertaking',
				'name_director', 'type_director', 'type_director_others', 'director_whatsapp', 'director_mobile', 'director_email', 'name_authority', 'type_authority',
				'type_authority_others', 'd_file_board', 'authority_whatsapp', 'authority_mobile', 'authority_email', 'getco_substation', 'project_taluka', 'project_village',
				'project_state', 'project_district', 'comm_date', 'project_energy', 'project_estimated_cost', 'discom', 'getco_substation'
			];

			$applicaiton 	= $this->Applications->find('all', array('fields' => $fields, 'conditions' => array('id' => $app_id)))->first();
			$EndUseElectricity = $this->EndUseElectricity->find('all', array('conditions' => array('application_id' => $app_id)))->first();
			if (isset($EndUseElectricity) && !empty($EndUseElectricity)) {
				if ($EndUseElectricity['application_end_use_electricity'] == 1) {
					$end_use_of_electricity = 2;
				}
				if ($EndUseElectricity['application_end_use_electricity'] == 2) {
					$end_use_of_electricity = 3;
				}
				if ($EndUseElectricity['application_end_use_electricity'] == 3) {
					$end_use_of_electricity = 1;
				}
			}
			$developer_details 		= $this->DeveloperCustomers->find('all', array('conditions' => array('id' => $customerId)))->first();
			$developer				= $this->Developers->find('all', array('fields' => array('id', 'contact_person'), 'conditions' => array('id' => $developer_details->installer_id)))->first();

			$data = [
				'application_type'						=>	$applicaiton->application_type,
				'name_of_applicant'						=>	$applicaiton->name_of_applicant,
				'address'								=>	$applicaiton->address,
				'address1'								=>	$applicaiton->address1,
				'taluka'								=>	$applicaiton->taluka,
				'pincode'								=>	$applicaiton->pincode,
				'city'									=>	$applicaiton->city,
				'state'									=>	$applicaiton->state,
				'district'								=>	$applicaiton->district,
				'district_code'							=>	$applicaiton->district_code,
				'type_of_applicant'						=>	$applicaiton->type_of_applicant,
				'registration_document'					=>	$applicaiton->registration_document,
				'applicant_others'						=>	$applicaiton->applicant_others,
				'contact'								=>	$applicaiton->contact,
				'mobile'								=>	$applicaiton->mobile,
				'email'									=>	$applicaiton->email,
				'pan'									=>	$applicaiton->pan,
				'pan_card'								=>	$applicaiton->pan_card,
				'GST'									=>	$applicaiton->GST,
				'a_msme'								=>	$applicaiton->a_msme,
				'upload_undertaking'					=>	$applicaiton->upload_undertaking,
				'name_director'							=>	$applicaiton->name_director,
				'type_director'							=>	$applicaiton->type_director,
				'type_director_others'					=>	$applicaiton->type_director_others,
				'director_whatsapp'						=>	$applicaiton->director_whatsapp,
				'director_mobile'						=>	$applicaiton->director_mobile,
				'director_email'						=>	$applicaiton->director_email,
				'name_authority'						=>	$applicaiton->name_authority,
				'type_authority'						=>	$applicaiton->type_authority,
				'type_authority_others'					=>	$applicaiton->type_authority_others,
				'd_file_board'							=>	$applicaiton->d_file_board,
				'authority_whatsapp'					=>	$applicaiton->authority_whatsapp,
				'authority_mobile'						=>	$applicaiton->authority_mobile,
				'authority_email'						=>	$applicaiton->authority_email,
				'getco_substation'						=>	$applicaiton->getco_substation,
				"name_authority" 						=>	 $request_data['name_authority'],
				"type_authority" 						=>	 $request_data['type_authority'],
				"authority_whatsapp" 					=>	 $request_data['authority_whatsapp'],
				"authority_mobile" 						=>	 $request_data['authority_mobile'],
				"authority_email" 						=>	 $request_data['authority_email'],
				// "land_taluka"							=>	$applicaiton->project_taluka,
				// "land_city"								=>	$applicaiton->project_village,
				// "land_state"								=>	$applicaiton->project_state,
				// "land_district"							=>	$applicaiton->project_district,
				"proposed_date_of_commm"				=>	$applicaiton->comm_date,
				"expected_annual_output"				=>	$applicaiton->project_energy,
				"app_project_cost"						=>	$applicaiton->project_estimated_cost,
				"name_of_discome_plant_installed"		=> 	$applicaiton->discom,
				"getco_substation_name"					=> 	$applicaiton->getco_substation,
				"end_use_of_electricity"				=>	isset($end_use_of_electricity) ? $end_use_of_electricity : 0,
				"epc_constractor_nm"					=> 	isset($developer_details) ? $developer_details['name'] : '',
				"epc_constractor_add"					=>	isset($developer_details) ? $developer_details['address1'] : '',
				"epc_constractor_con_per"				=>	isset($developer) ? $developer['contact_person'] : '',
				"epc_constractor_email"					=>	isset($developer_details) ? $developer_details['email'] : '',
				"epc_constractor_mobile"				=>	isset($developer_details) ? $developer_details['mobile'] : '',
				"tab_id"								=>	1
			];

			$ApplicationEntity 				= $this->OpenAccessApplicationDeveloperPermission->newEntity($data, ['validate' => 'tab1']);
			$ApplicationEntity->created 	= $this->NOW();
			$ApplicationEntity->created_by 	= $customerId;
			$ApplicationEntity->customer_id = $customerId;

			if ($this->Session->read("Customers.id") && !empty($this->Session->read("Customers.id"))) {
				$customer_details 				= $this->DeveloperCustomers->find('all', array('conditions' => array('id' => $customerId)))->first();
				$ApplicationEntity->installer_id = $customer_details->installer_id;
			}
			$saveText = 'inserted';
		} else {

			$ApplicationEntity 					= $this->OpenAccessApplicationDeveloperPermission->patchEntity($applicaiton_exist, $request_data, ['validate' => 'tab1']);
			$saveText							= 'updated';
		}
		$ApplicationEntity->application_id  = $app_id;
		$ApplicationEntity->modified 		= $this->NOW();
		$ApplicationEntity->modified_by 	= $customerId;
		if (isset($request_data['tab_id']) && $request_data['tab_id'] == 1) {
			$ApplicationEntity->tab_1 = 1;
		}

		if (!empty($ApplicationEntity->errors())) {
			return json_encode(array('success' => '0', 'response_errors' => $ApplicationEntity->errors()));
		} else {
			$this->OpenAccessApplicationDeveloperPermission->save($ApplicationEntity);
			$this->Flash->success("Application $saveText successfully.");
			return json_encode(array('success' => '1', 'response_errors' => '', 'application_id' => $ApplicationEntity->id));
		}
	}

	/**
	 * imgfile_upload
	 *
	 * Behaviour : public	
	 * @param : id  : $file is use to identify for which image should be select and $path is use to identify the image folder path.	
	 * @defination : Method is use to save the image in file folder .
	 */
	public function open_access_imgfile_upload($file, $prefix_file = '', $application_id, $file_field, $access_type = '')
	{

		$customerId 	= $this->Session->read('Customers.id');

		$name 			= $file['name'];

		$path 			= APPLICATIONS_DEVELOPER_PERMISSION_PATH . 'open_access/' . $application_id . '/';
		if (!file_exists(APPLICATIONS_DEVELOPER_PERMISSION_PATH . 'open_access/' . $application_id)) {
			@mkdir(APPLICATIONS_DEVELOPER_PERMISSION_PATH . 'open_access/' . $application_id, 0777, true);
		}
		$updateRequestData 	= $this->OpenAccessApplicationDeveloperPermission->find('all', array('conditions' => array('id' => $application_id)))->first();
		if (!empty($updateRequestData->$file_field) && file_exists($path . $updateRequestData->$file_field)) {
			@unlink($path . $updateRequestData->$file_field);
		}
		$ext    		= substr(strtolower(strrchr($file['name'], '.')), 1);
		$file_name   	= $prefix_file . date('YmdHis') . rand();
		$file_location  = $path . $file_name . '.' . $ext;

		move_uploaded_file($file['tmp_name'], $file_location);

		$passFileName 	= $file_name . '.' . $ext;
		$couchdbId 		= $this->ReCouchdb->saveData($path, $file_location, $prefix_file, $passFileName, $customerId, $access_type);

		return $file_name . '.' . $ext;
	}

	public function open_access_land_imgfile_upload($file, $prefix_file = '', $application_id, $file_field, $access_type = '', $fileName = '')
	{
		$customerId 	= $this->Session->read('Customers.id');

		$name 			= $file['name'];

		$path 			= APPLICATIONS_DEVELOPER_PERMISSION_PATH . 'open_access/' . $application_id . '/';
		if (!file_exists(APPLICATIONS_DEVELOPER_PERMISSION_PATH . 'open_access/' . $application_id)) {
			@mkdir(APPLICATIONS_DEVELOPER_PERMISSION_PATH . 'open_access/' . $application_id, 0777, true);
		}

		if (isset($fileName) && !empty($fileName)) {
			$updateRequestData 	= $this->OpenAccessLandDetails->find('all', array('conditions' => array('app_dev_per_id' => $application_id, 'deed_doc' => $fileName)))->first();
			if (!empty($updateRequestData->deed_doc) && file_exists($path . $updateRequestData->deed_doc)) {
				@unlink($path . $updateRequestData->deed_doc);
			}
		}
		$ext    		= substr(strtolower(strrchr($file['name'], '.')), 1);
		$file_name   	= $prefix_file . date('YmdHis') . rand();
		$file_location  = $path . $file_name . '.' . $ext;

		move_uploaded_file($file['tmp_name'], $file_location);

		$passFileName 	= $file_name . '.' . $ext;
		$couchdbId 		= $this->ReCouchdb->saveData($path, $file_location, $prefix_file, $passFileName, $customerId, $access_type);

		return $file_name . '.' . $ext;
	}
	/**
	 *
	 * technical_details
	 *
	 * Behaviour : Private
	 *
	 * @param : $request_data   : tab2 form posted data should be passed
	 * @defination : Method is use to check validation of first tab and insert/update subsidy record for first tab.
	 *
	 */
	private function open_access_technical_details($request_data)
	{

		if (!empty($this->Session->read('Members.member_type')))
			$customerId = $this->Session->read("Members.id");
		else
			$customerId = $this->Session->read("Customers.id");


		$app_id = !empty($request_data['Applications']['application_id']) ? decode($request_data['Applications']['application_id']) : 0;
		$applicaiton_exist = 0;
		if (!empty($app_id)) {
			$applicaiton_exist 	= $this->OpenAccessApplicationDeveloperPermission->find('all', array('conditions' => array('application_id' => $app_id)))->first();
			$pvCapacity = $this->Applications->find('all', array('fields' => array('pv_capacity_dc', 'pv_capacity_ac'), 'conditions' => array('id' => decode($request_data['Applications']['application_id']))))->first();
			$request_data['pv_capacity_dc'] = $pvCapacity['pv_capacity_dc'];
			$request_data['pv_capacity_ac'] = $pvCapacity['pv_capacity_ac'];
			$this->OpenAccessApplicationDeveloperPermission->dataPass = $request_data;
		}

		if (!empty($applicaiton_exist)) {

			$ApplicationEntity 				= $this->OpenAccessApplicationDeveloperPermission->patchEntity($applicaiton_exist, $request_data, ['validate' => 'tab2']);
			$saveText						= 'updated';
		}
		$ApplicationEntity->modified 		= $this->NOW();
		$ApplicationEntity->modified_by 	= $customerId;

		//upload_sale_to_discom - file
		if (isset($this->request->data['a_upload_sale_to_discom']['tmp_name']) && !empty($this->request->data['a_upload_sale_to_discom']['tmp_name'])) {
			$file_name 	= $this->open_access_imgfile_upload($this->request->data['a_upload_sale_to_discom'], 'saledis', $ApplicationEntity->id, 'upload_sale_to_discom', 'upload_sale_to_discom');
			$this->OpenAccessApplicationDeveloperPermission->updateAll(
				array("upload_sale_to_discom" => $file_name),
				array("id" => $ApplicationEntity->id)
			);
		}

		//no_due_1 - file
		if (isset($this->request->data['a_no_due_1']['tmp_name']) && !empty($this->request->data['a_no_due_1']['tmp_name'])) {
			$file_name 	= $this->open_access_imgfile_upload($this->request->data['a_no_due_1'], 'nodue1', $ApplicationEntity->id, 'no_due_1', 'no_due_1');
			$this->OpenAccessApplicationDeveloperPermission->updateAll(
				array("no_due_1" => $file_name),
				array("id" => $ApplicationEntity->id)
			);
		}

		//no_due_2 - file
		if (isset($this->request->data['a_no_due_2']['tmp_name']) && !empty($this->request->data['a_no_due_2']['tmp_name'])) {
			$file_name 	= $this->open_access_imgfile_upload($this->request->data['a_no_due_2'], 'nodue2', $ApplicationEntity->id, 'no_due_2', 'no_due_2');
			$this->OpenAccessApplicationDeveloperPermission->updateAll(
				array("no_due_2" => $file_name),
				array("id" => $ApplicationEntity->id)
			);
		}

		//upload_proof_of_ownership_1 - file
		if (isset($this->request->data['a_upload_proof_of_ownership_1']['tmp_name']) && !empty($this->request->data['a_upload_proof_of_ownership_1']['tmp_name'])) {
			$file_name 	= $this->open_access_imgfile_upload($this->request->data['a_upload_proof_of_ownership_1'], 'own1', $ApplicationEntity->id, 'upload_proof_of_ownership_1', 'upload_proof_of_ownership_1');
			$this->OpenAccessApplicationDeveloperPermission->updateAll(
				array("upload_proof_of_ownership_1" => $file_name),
				array("id" => $ApplicationEntity->id)
			);
		}

		//upload_proof_of_ownership_2 - file
		if (isset($this->request->data['a_upload_proof_of_ownership_2']['tmp_name']) && !empty($this->request->data['a_upload_proof_of_ownership_2']['tmp_name'])) {
			$file_name 	= $this->open_access_imgfile_upload($this->request->data['a_upload_proof_of_ownership_2'], 'own2', $ApplicationEntity->id, 'upload_proof_of_ownership_2', 'upload_proof_of_ownership_2');
			$this->OpenAccessApplicationDeveloperPermission->updateAll(
				array("upload_proof_of_ownership_2" => $file_name),
				array("id" => $ApplicationEntity->id)
			);
		}

		//doc_of_beneficiary
		if (isset($this->request->data['a_doc_of_beneficiary']['tmp_name']) && !empty($this->request->data['a_doc_of_beneficiary']['tmp_name'])) {
			$file_name 	= $this->open_access_imgfile_upload($this->request->data['a_doc_of_beneficiary'], 'docofben', $ApplicationEntity->id, 'doc_of_beneficiary', 'doc_of_beneficiary');
			$this->OpenAccessApplicationDeveloperPermission->updateAll(
				array("doc_of_beneficiary" => $file_name),
				array("id" => $ApplicationEntity->id)
			);
		}

		//copy_of_conventional_electricity
		if (isset($this->request->data['a_copy_of_conventional_electricity']['tmp_name']) && !empty($this->request->data['a_copy_of_conventional_electricity']['tmp_name'])) {
			$file_name 	= $this->open_access_imgfile_upload($this->request->data['a_copy_of_conventional_electricity'], 'cpconele', $ApplicationEntity->id, 'copy_of_conventional_electricity', 'copy_of_conventional_electricity');
			$this->OpenAccessApplicationDeveloperPermission->updateAll(
				array("copy_of_conventional_electricity" => $file_name),
				array("id" => $ApplicationEntity->id)
			);
		}

		//stamp_of_re_gen_plant
		if (isset($this->request->data['a_stamp_of_re_gen_plant']['tmp_name']) && !empty($this->request->data['a_stamp_of_re_gen_plant']['tmp_name'])) {
			$file_name 	= $this->open_access_imgfile_upload($this->request->data['a_stamp_of_re_gen_plant'], 'stregenpl', $ApplicationEntity->id, 'stamp_of_re_gen_plant', 'stamp_of_re_gen_plant');
			$this->OpenAccessApplicationDeveloperPermission->updateAll(
				array("stamp_of_re_gen_plant" => $file_name),
				array("id" => $ApplicationEntity->id)
			);
		}

		//electricit_bill_of_third_party
		if (isset($this->request->data['a_electricit_bill_of_third_party']['tmp_name']) && !empty($this->request->data['a_electricit_bill_of_third_party']['tmp_name'])) {
			$file_name 	= $this->open_access_imgfile_upload($this->request->data['a_electricit_bill_of_third_party'], 'eb', $ApplicationEntity->id, 'electricit_bill_of_third_party', 'electricit_bill_of_third_party');
			$this->OpenAccessApplicationDeveloperPermission->updateAll(
				array("electricit_bill_of_third_party" => $file_name),
				array("id" => $ApplicationEntity->id)
			);
		}

		//multi third party consumer
		if (isset($this->request->data['a_multi_third_party']['tmp_name']) && !empty($this->request->data['a_multi_third_party']['tmp_name'])) {
			$file_name 	= $this->open_access_imgfile_upload($this->request->data['a_multi_third_party'], 'multhird', $ApplicationEntity->id, 'multi_third_party', 'multi_third_party');
			$this->OpenAccessApplicationDeveloperPermission->updateAll(
				array("multi_third_party" => $file_name),
				array("id" => $ApplicationEntity->id)
			);
		}

		//rec accrediation certificate
		if (isset($this->request->data['a_rec_accrediation_cer']['tmp_name']) && !empty($this->request->data['a_rec_accrediation_cer']['tmp_name'])) {
			$file_name 	= $this->open_access_imgfile_upload($this->request->data['a_rec_accrediation_cer'], 'rec_reg_cer', $ApplicationEntity->id, 'rec_accrediation_cer', 'rec_accrediation_cer');

			$this->OpenAccessApplicationDeveloperPermission->updateAll(
				array("rec_accrediation_cer" => $file_name),
				array("id" => $ApplicationEntity->id)
			);
		}

		//doc of gerc license copy
		if (isset($this->request->data['a_doc_of_gerc_license']['tmp_name']) && !empty($this->request->data['a_doc_of_gerc_license']['tmp_name'])) {
			$file_name 	= $this->open_access_imgfile_upload($this->request->data['a_doc_of_gerc_license'], 'rec_reg_cer', $ApplicationEntity->id, 'doc_of_gerc_license', 'doc_of_gerc_license');

			$this->OpenAccessApplicationDeveloperPermission->updateAll(
				array("doc_of_gerc_license" => $file_name),
				array("id" => $ApplicationEntity->id)
			);
		}

		//upload undertaking newness
		if (isset($this->request->data['a_upload_undertaking_newness']['tmp_name']) && !empty($this->request->data['a_upload_undertaking_newness']['tmp_name'])) {
			$file_name 	= $this->open_access_imgfile_upload($this->request->data['a_upload_undertaking_newness'], 'rec_reg_cer', $ApplicationEntity->id, 'upload_undertaking_newness', 'upload_undertaking_newness');

			$this->OpenAccessApplicationDeveloperPermission->updateAll(
				array("upload_undertaking_newness" => $file_name),
				array("id" => $ApplicationEntity->id)
			);
		}

		if (!empty($ApplicationEntity->errors())) {

			return json_encode(array('success' => '0', 'response_errors' => $ApplicationEntity->errors(), 'addMoreError' => 1));
		} else {

			$errorModule 			= 0;
			if (isset($request_data['nos_mod'])) {

				foreach ($request_data['nos_mod'] as $key => $value) {
					if (empty($value) || empty($request_data['mod_capacity'][$key]) || empty($request_data['mod_make'][$key]) || empty($request_data['type_of_spv'][$key]) || empty($request_data['type_of_solar'][$key])) {
						$errorModule 	= 1;
					}
				}
			}
			$errorInverter 			= 0;
			if (isset($request_data['nos_inv'])) {
				foreach ($request_data['nos_inv'] as $key => $value) {
					if (empty($value) || empty($request_data['inv_capacity'][$key]) || empty($request_data['inv_make'][$key]) || empty($request_data['type_of_inverter_used'][$key])) {
						$errorInverter 	= 1;
					}
				}
			}
			if (!empty($ApplicationEntity->errors()) || $errorModule == 1 || $errorInverter == 1) {
				return json_encode(array('success' => '0', 'response_errors' => $ApplicationEntity->errors(), 'addMoreError' => 1));
			} else {
				if (isset($request_data['tab_id']) && $request_data['tab_id'] == 2) {
					$ApplicationEntity->tab_2 = 1;
				}
				$ApplicationEntity->proposed_date_of_commm = date('Y-m-d', strtotime($request_data['proposed_date_of_commm']));
				$this->OpenAccessApplicationDeveloperPermission->save($ApplicationEntity);



				if (isset($request_data['nos_mod']) && !empty($request_data['nos_mod'])) {

					$this->ApplicationOpenAccessAdditionalData->deleteAll(['app_dev_per_id' => $ApplicationEntity->id, 'capacity_type' => 1]);
					foreach ($request_data['nos_mod'] as $key => $val) {
						if (!empty($request_data['nos_mod'][$key])) {
							$arr_modules['nos_mod']				= $val;
							$arr_modules['mod_capacity']		= $request_data['mod_capacity'][$key];
							$arr_modules['mod_total_capacity']	= $request_data['mod_total_capacity'][$key];
							$arr_modules['mod_make']			= $request_data['mod_make'][$key];
							$arr_modules['type_of_spv']			= $request_data['type_of_spv'][$key];
							$arr_modules['type_of_solar']		= $request_data['type_of_solar'][$key];
							$this->ApplicationOpenAccessAdditionalData->save_module_open_access($ApplicationEntity->id, $arr_modules, $this->Session->read('Members.id'));
						}
					}
				}
				if (isset($request_data['nos_inv']) && !empty($request_data['nos_inv'])) {

					$this->ApplicationOpenAccessAdditionalData->deleteAll(['app_dev_per_id' => $ApplicationEntity->id, 'capacity_type' => 2]);
					foreach ($request_data['nos_inv'] as $key => $val) {
						if (!empty($request_data['nos_inv'][$key])) {
							$arr_inverters['nos_inv']				= $val;
							$arr_inverters['inv_capacity']			= $request_data['inv_capacity'][$key];
							$arr_inverters['inv_total_capacity']	= $request_data['inv_total_capacity'][$key];
							$arr_inverters['inv_make']				= $request_data['inv_make'][$key];
							$arr_inverters['type_of_inverter_used']	= $request_data['type_of_inverter_used'][$key];
							$this->ApplicationOpenAccessAdditionalData->save_inverter_open_access($ApplicationEntity->id, $arr_inverters, $this->Session->read('Members.id'));
						}
					}
				}
				$this->Flash->success("Application $saveText successfully!!!!.");
				return json_encode(array('success' => '1', 'response_errors' => ''));
			}
		}
	}

	/**
	 *
	 * land_details
	 *
	 * Behaviour : Private
	 *
	 * @param : $request_data   : tab3 form posted data should be passed
	 * @defination : Method is use to check validation of first tab and insert/update subsidy record for first tab.
	 *
	 */
	private function open_access_land_details($request_data)
	{

		if (!empty($this->Session->read('Members.member_type')))
			$customerId = $this->Session->read("Members.id");
		else
			$customerId = $this->Session->read("Customers.id");

		$app_id = !empty($request_data['Applications']['application_id']) ? decode($request_data['Applications']['application_id']) : 0;
		$applicaiton_exist = 0;
		if (!empty($app_id)) {
			$applicaiton_exist 	= $this->OpenAccessApplicationDeveloperPermission->find('all', array('conditions' => array('application_id' => $app_id)))->first();
			$this->OpenAccessApplicationDeveloperPermission->dataPass = $request_data;
		}

		if (!empty($applicaiton_exist)) {

			$ApplicationEntity 				= $this->OpenAccessApplicationDeveloperPermission->patchEntity($applicaiton_exist, $request_data, ['validate' => 'tab2']);
			$saveText						= 'updated';
		}
		$ApplicationEntity->modified 		= $this->NOW();
		$ApplicationEntity->modified_by 	= $customerId;
		if (isset($request_data['tab_id']) && $request_data['tab_id'] == 3) {
			$ApplicationEntity->tab_3 = 1;
		}

		if (!empty($ApplicationEntity->errors())) {

			return json_encode(array('success' => '0', 'response_errors' => $ApplicationEntity->errors(), 'addMoreError' => 1));
		} else {

			$errorLand 			= 0;
			if (isset($request_data['land_category'])) {

				foreach ($request_data['land_category'] as $key => $value) {


					if (empty($value) || empty($this->request->data['land_plot_servey_no'][$key]) || empty($this->request->data['land_taluka'][$key]) || empty($this->request->data['land_village'][$key]) || empty($this->request->data['land_district'][$key]) || empty($this->request->data['land_latitude'][$key]) || empty($this->request->data['land_longitude'][$key]) || empty($this->request->data['area_of_land'][$key]) || empty($this->request->data['deed_of_land'][$key])) {
						$errorLand 	= 1;
					}
				}
			}

			if (!empty($ApplicationEntity->errors()) || $errorLand == 1) {
				return json_encode(array('success' => '0', 'response_errors' => $ApplicationEntity->errors(), 'addMoreError' => 1));
			} else {


				$this->OpenAccessApplicationDeveloperPermission->save($ApplicationEntity);

				if (isset($request_data['land_category']) && !empty($request_data['land_category'])) {

					//$this->OpenAccessLandDetails->deleteAll(['app_dev_per_id' => $ApplicationEntity->id]);
					foreach ($request_data['land_category'] as $key => $val) {
						$arr_land_detail = [];
						if (!empty($request_data['land_category'][$key])) {
							$arr_land_detail['land_category']			= $val;
							$arr_land_detail['land_plot_servey_no']		= $request_data['land_plot_servey_no'][$key];
							$arr_land_detail['land_village']			= $request_data['land_village'][$key];
							$arr_land_detail['land_taluka']				= $request_data['land_taluka'][$key];
							$arr_land_detail['land_state']				= 'Gujarat';
							$arr_land_detail['land_district']			= $request_data['land_district'][$key];
							$arr_land_detail['land_latitude']			= $request_data['land_latitude'][$key];
							$arr_land_detail['land_longitude']			= $request_data['land_longitude'][$key];
							$arr_land_detail['area_of_land']			= $request_data['area_of_land'][$key];
							$arr_land_detail['deed_of_land']			= $request_data['deed_of_land'][$key];
							$arr_land_detail['id_land']					= isset($request_data['id_land'][$key]) ? $request_data['id_land'][$key] : '';


							$arr_land_detail['deed_doc'] = '';
							$fl = '';
							if (isset($this->request->data['a_deed_doc_' . $key]['tmp_name']) && !empty($this->request->data['a_deed_doc_' . $key]['tmp_name'])) {

								$fl = isset($request_data['deed_file'][$key]) ? $request_data['deed_file'][$key] : '';
								$file_name 	= $this->open_access_land_imgfile_upload($this->request->data['a_deed_doc_' . $key], 'deedDoc' . $key, $ApplicationEntity->id, 'deed_doc', 'deed_doc', $fl);
								$arr_land_detail['deed_doc']			=	isset($file_name) ? $file_name : null;
							}

							$this->OpenAccessLandDetails->save_open_access_land_details($ApplicationEntity->id, $arr_land_detail, $this->Session->read('Members.id'));
						}
					}
					$this->Flash->success("Application $saveText successfully!!!!.");
					return json_encode(array('success' => '1', 'response_errors' => ''));
				}
			}
		}
	}
	/* 
		Remove Modules
	*/
	public function remove_modules()
	{
		$id 				= (isset($this->request->data['id']) ? $this->request->data['id'] : 0);
		$this->autoRender 	= false;

		if (empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message', $ErrorMessage);
			$this->ApiToken->SetAPIResponse('success', $success);
		} else {

			$this->ApplicationOpenAccessAdditionalData->deleteAll(['id' => $id, 'capacity_type', $this->request->data['capacity_type']]);
			$success 		= 1;
			$this->ApiToken->SetAPIResponse('success', $success);
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/* Remove Land Data
	*/
	public function remove_land()
	{
		$id 				= (isset($this->request->data['id']) ? $this->request->data['id'] : 0);
		$this->autoRender 	= false;

		if (empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message', $ErrorMessage);
			$this->ApiToken->SetAPIResponse('success', $success);
		} else {

			$this->OpenAccessLandDetails->deleteAll(['id' => $id]);
			$success 		= 1;
			$this->ApiToken->SetAPIResponse('success', $success);
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/****
	 * Open Access Form Download
	 */
	public function  open_access_form_pdf($id = null)
	{
		if (!empty($this->Session->read('Members.member_type'))) {
			$customerId = $this->Session->read("Members.id");
		} else {
			$customerId = $this->Session->read("Customers.id");
		}
		if (empty($customerId)) {
			return $this->redirect('/home');
		}

		$application_data = $this->OpenAccessApplicationDeveloperPermission->generateOpenAccessApplicationPdf($id);

		if (empty($application_data)) {
			$this->Flash->error('Please Select Valid Application.');
			return $this->redirect('home');
		}
	}

	public function open_access_letter($id = null)
	{
		if (!empty($this->Session->read('Members.member_type'))) {
			$customerId = $this->Session->read("Members.id");
		} else {
			$customerId = $this->Session->read("Customers.id");
		}
		if (empty($customerId)) {
			return $this->redirect('/home');
		}

		$application_data = $this->OpenAccessApplicationDeveloperPermission->generateOpenAccessApplicationLetter($id);

		if (empty($application_data)) {
			$this->Flash->error('Please Select Valid Application.');
			return $this->redirect('home');
		}
	}

	public function open_access_view($id)
	{

		if (!empty($this->Session->read('Members.member_type'))) {
			$customerId = $this->Session->read("Members.id");
		} else {
			$customerId = $this->Session->read("Customers.id");
		}
		if (empty($customerId)) {
			return $this->redirect('/home');
		}
		$applicationId = decode($id);
		$ApplicationData = $this->OpenAccessApplicationDeveloperPermission->viewDetailApplication($applicationId);
		$EndSTU 				= $this->ApiToken->arrEndSTU;
		$EndCTU 				= $this->ApiToken->arrEndCTU;
		$type_of_applicant 		= $this->ApiToken->arrFirmDropdown;



		$totalInverternos		= $this->ApplicationOpenAccessAdditionalData->getOpenAccessDataSum($applicationId, 2);
		$totalModulenos			= $this->ApplicationOpenAccessAdditionalData->getOpenAccessDataSum($applicationId, 1);
		$moduleAdditionalData 	= $this->ApplicationOpenAccessAdditionalData->find('all', array('conditions' => array('app_dev_per_id' => $applicationId, 'capacity_type' => 1), 'order' => array('id' => 'desc')))->toArray();
		$inverteAdditionalData	= $this->ApplicationOpenAccessAdditionalData->find('all', array('conditions' => array('app_dev_per_id' => $applicationId, 'capacity_type' => 2), 'order' => array('id' => 'desc')))->toArray();
		$landData 				= $this->OpenAccessLandDetails->fetchdata($applicationId);

		$district 				= $this->DistrictMaster->find("list", ['keyField' => 'id', 'valueField' => 'name', 'conditions' => ['state_id' => 4]])->toArray();
		$arrTalukaData			= $this->TalukaMaster->find("list", ['keyField' => 'id', 'valueField' => 'name'])->toArray();
		$discom_arr = array();
		$discoms 	= $this->BranchMasters->find("list", ['keyField' => 'id', 'valueField' => 'title', 'conditions' => ['BranchMasters.status' => '1', 'BranchMasters.parent_id' => '0', 'BranchMasters.state' => $this->ApplyOnlines->gujarat_st_id]])->toArray();
		if (!empty($discoms)) {
			foreach ($discoms as $keyid => $title) {
				$discom_arr[$keyid] = $title;
			}
		}

		$this->set('ApplicationData', $ApplicationData);
		$this->set('EndSTU', $EndSTU);
		$this->set('EndCTU', $EndCTU);
		$this->set('totalInverternos', $totalInverternos);
		$this->set('totalModulenos', $totalModulenos);
		$this->set('moduleAdditionalData', $moduleAdditionalData);
		$this->set('inverteAdditionalData', $inverteAdditionalData);
		$this->set('type_of_applicant', $type_of_applicant);
		$this->set('typeOfSPP', $this->OpenAccessApplicationDeveloperPermission->type_of_spp);
		$this->set('typeOfMountingSystem', $this->OpenAccessApplicationDeveloperPermission->type_of_mounting_system);
		$this->set('typeOfspv', $this->OpenAccessApplicationDeveloperPermission->type_of_spv);
		$this->set('typeOfSolarPanel', $this->OpenAccessApplicationDeveloperPermission->type_of_solar_panel);
		$this->set('typeOfInverterUsed', $this->OpenAccessApplicationDeveloperPermission->type_of_inverter_used);
		$this->set('typeOfConsumer', $this->OpenAccessApplicationDeveloperPermission->type_of_consumer);
		$this->set('typeOfMsme', $this->OpenAccessApplicationDeveloperPermission->type_of_MSME);
		$this->set('endUseOfElectricity', $this->OpenAccessApplicationDeveloperPermission->end_use_of_electricity);
		$this->set('projectForRpo', $this->OpenAccessApplicationDeveloperPermission->project_for_rpo);
		$this->set('landCategory', $this->OpenAccessApplicationDeveloperPermission->land_category);
		$this->set('deedOfLand', $this->OpenAccessApplicationDeveloperPermission->deed_of_land);
		$this->set('lanDetails', $landData);
		$this->set('Couchdb', $this->ReCouchdb);
		$this->set("discom_arr", $discom_arr);
		$this->set("arrDistictData", $district);
		$this->set("arrTalukaData", $arrTalukaData);
	}
	/**
	 *
	 * fees_structure
	 *
	 * Behaviour : Private
	 *
	 * @param : $request_data   : tab3 form posted data should be passed
	 * @defination : Method is use to check validation of first tab and insert/update subsidy record for first tab.
	 *
	 */
	private function open_access_fees_structure($request_data)
	{

		if (!empty($this->Session->read('Members.member_type')))
			$customerId 				= $this->Session->read("Members.id");
		else
			$customerId 				= $this->Session->read("Customers.id");


		$app_id = !empty($request_data['Applications']['application_id']) ? decode($request_data['Applications']['application_id']) : 0;
		$applicaiton_exist = 0;
		if (!empty($app_id)) {
			$application = $this->Applications->find('all', array('conditions' => array('id' => $app_id)))->first();
			$applicaiton_exist 	= $this->OpenAccessApplicationDeveloperPermission->find('all', array('conditions' => array('application_id' => $app_id)))->first();
			$this->OpenAccessApplicationDeveloperPermission->dataPass = $request_data;
			$this->OpenAccessApplicationDeveloperPermission->dataPass['documents_error'] = '';
		}
		$ApplicationEntity 				= $this->OpenAccessApplicationDeveloperPermission->patchEntity($applicaiton_exist, $request_data, ['validate' => 'tab3']);

		$saveText						= 'updated';
		$ApplicationEntity->modified 		= $this->NOW();
		$ApplicationEntity->modified_by 	= $customerId;


		if (!empty($applicaiton_exist) && isset($applicaiton_exist)) {
			$applicationCategory 	= $this->ApplicationCategory->find('all', array('conditions' => array('id' => $applicaiton_exist->application_type)))->first();
			$additionalDataSum = $this->ApplicationOpenAccessAdditionalData->getOpenAccessDataSum($applicaiton_exist->id, '2');
			$invTotCapacity = $additionalDataSum['mod_inv_total_capacity'];

			if ($invTotCapacity < 1) {
				$application_fee 			= 	isset($applicationCategory->dev_per_fee_less_than_one_mb) ? $applicationCategory->dev_per_fee_less_than_one_mb : 0;
				$application_tax_percentage = 	isset($applicationCategory->dev_per_tax_per) ? $applicationCategory->dev_per_tax_per : 0;

				$provisional_total_fee		=	isset($application->application_fee) ? $application->application_fee : 0;
				$application_total_fee 		= 	$application_fee - $provisional_total_fee;
				$gst_fees 					= 	($application_total_fee * $application_tax_percentage) / 100;
				$payable_total_fee			=	$application_total_fee + $gst_fees;
			} else {
				$application_fee 			= 	isset($applicationCategory->dev_per_one_mb_and_above) ? $applicationCategory->dev_per_one_mb_and_above * $invTotCapacity : 0;
				$application_tax_percentage = 	isset($applicationCategory->dev_per_tax_per) ? $applicationCategory->dev_per_tax_per : 0;
				$provisional_total_fee		=	isset($application->application_fee) ? $application->application_fee : 0;
				$application_total_fee 		= 	$application_fee - $provisional_total_fee;
				$gst_fees 					= 	($application_total_fee * $application_tax_percentage) / 100;
				$payable_total_fee			=	$application_total_fee + $gst_fees;
			}


			$tds_deduction = 0;
			if ($request_data['liable_tds'] == 1 && $request_data['terms_agree'] == 1) {
				$application_tds_percentage = isset($applicationCategory->application_tds_percentage) ? $applicationCategory->application_tds_percentage : 0;
				$tds_deduction 								= 	($application_total_fee * $application_tds_percentage) / 100;
				$ApplicationEntity->tds_deduction 			= 	$tds_deduction;
				$payable_total_fee							=	$payable_total_fee - $tds_deduction;
			} else {
				$ApplicationEntity->tds_deduction 			= 0;
				$ApplicationEntity->liable_tds 				= 0;
				$ApplicationEntity->terms_agree 			= 0;
			}

			$ApplicationEntity->application_fee 		= $application_fee;
			$ApplicationEntity->gst_fees 				= $gst_fees;
			$ApplicationEntity->application_total_fee 	= $application_total_fee;
			$ApplicationEntity->provisional_total_fee 	= $provisional_total_fee;
			$ApplicationEntity->payable_total_fee 		= $payable_total_fee;
			$ApplicationEntity->documents 				= $request_data['documents'];
			$ApplicationEntity->editable				= 0;
		}


		if (!empty($ApplicationEntity->errors())) {
			return json_encode(array('success' => '0', 'response_errors' => $ApplicationEntity->errors()));
		} else {

			$this->OpenAccessApplicationDeveloperPermission->save($ApplicationEntity);
			$this->Flash->success("Application $saveText successfully.");
			return json_encode(array('success' => '1', 'response_errors' => ''));
		}
	}

	public function developer_form_approval()
	{
		$application_id 				= (isset($this->request->data['id']) ? decode($this->request->data['id']) : 0);
		$dev_app_id						= (isset($this->request->data['dev_app_id']) ? decode($this->request->data['dev_app_id']) : 0);
		$app_type						= (isset($this->request->data['app_type']) ? $this->request->data['app_type'] : 0);
		$this->autoRender 	= false;

		if (!empty($application_id) && isset($application_id) && !empty($dev_app_id) && isset($dev_app_id) && isset($app_type)) {

			if ($app_type == 2) {

				$fields             = [

					'OpenAccessApplicationDeveloperPermission.id',
					'OpenAccessApplicationDeveloperPermission.application_id',
					'OpenAccessApplicationDeveloperPermission.application_type',
					'OpenAccessApplicationDeveloperPermission.taluka',
					'OpenAccessApplicationDeveloperPermission.city',
					'OpenAccessApplicationDeveloperPermission.getco_substation_name',
					'OpenAccessApplicationDeveloperPermission.end_use_of_electricity',
					'application_category.category_name',
					'district_master.name',
					'OpenAccessApplicationDeveloperPermission.inward_date',
				];
				$join_arr  		= [
					'application_category'	=> ['table' => 'application_category', 'type' => 'left', 'conditions' => 'OpenAccessApplicationDeveloperPermission.application_type=application_category.id'],
					'district_master'		=> ['table' => 'district_master', 'type' => 'left', 'conditions' => 'OpenAccessApplicationDeveloperPermission.district=district_master.id'],

				];
				$application = $this->OpenAccessApplicationDeveloperPermission->find('all', array(
					'fields' 	=> $fields,
					'join' 		=> $join_arr,
					'conditions' => array('OpenAccessApplicationDeveloperPermission.application_id' => $application_id)
				))->first();
			}
			if($app_type == 3 || $app_type == 4){
				$fields             = [

					'WindApplicationDeveloperPermission.id',
					'WindApplicationDeveloperPermission.application_id',
					'WindApplicationDeveloperPermission.application_type',
					'WindApplicationDeveloperPermission.taluka',
					'WindApplicationDeveloperPermission.city',
					'WindApplicationDeveloperPermission.getco_substation',
					'WindApplicationDeveloperPermission.grid_connectivity',
					'WindApplicationDeveloperPermission.end_stu',
					'WindApplicationDeveloperPermission.end_ctu',
					'application_category.category_name',
					'district_master.name',
					'WindApplicationDeveloperPermission.inward_date',
				];
				$join_arr  		= [
					'application_category'	=> ['table' => 'application_category', 'type' => 'left', 'conditions' => 'WindApplicationDeveloperPermission.application_type=application_category.id'],
					'district_master'		=> ['table' => 'district_master', 'type' => 'left', 'conditions' => 'WindApplicationDeveloperPermission.district=district_master.id'],

				];
				$application = $this->WindApplicationDeveloperPermission->find('all', array(
					'fields' 	=> $fields,
					'join' 		=> $join_arr,
					'conditions' => array('WindApplicationDeveloperPermission.application_id' => $application_id,'WindApplicationDeveloperPermission.id' => $dev_app_id)
				))->first();

			}

			$response = [];
			if (!empty($application) && isset($application)) {
				$EndCTU 				= $this->ApiToken->arrEndCTU;
				$end_use_of_electricity = $this->OpenAccessApplicationDeveloperPermission->end_use_of_electricity;

				if($app_type == 3 || $app_type == 4){
					if($application['grid_connectivity']==1){
						$grid_connectivity = $end_use_of_electricity[$application['end_stu']];
					}else{
						$grid_connectivity = $EndCTU[$application['end_ctu']];
					}
					$getco_substation = $application['getco_substation'];
				}else{
					$grid_connectivity = $end_use_of_electricity[$application['end_use_of_electricity']];
					$getco_substation = $application['getco_substation_name'];
				}
				$response = [
					'district' => $application['district_master']['name'],
					'taluka' => $application['taluka'],
					'city' => $application['city'],
					'appType' => $application['application_category']['category_name'],
					'substation' => $getco_substation,
					'endUse' => $grid_connectivity,
					'inward_date'=>	isset($application['inward_date']) && !empty($application['inward_date'])?date('d-m-Y', strtotime($application['inward_date'])):''				
				];
			}

			$roles = $this->MemberRoles->find('all', array(
				'fields' 		=> array('role', 'member_id'),
				'conditions' 	=> array('app_type' => $app_type, 'member_id !=' => $this->Session->read("Members.id")),
				'order' 		=> array('role_order' => 'DESC')
			))->toArray();

			

			if (isset($roles) && !empty($roles)) {
				foreach ($roles as $k => $v) {
					$statusData = $this->DeveloperApplicationQuery->find('all', array(
						'fields' 	=> array('status','created'),
						'conditions' => array('application_id' => $application_id, 'app_dev_per_id' => $dev_app_id, 'member_id' => $v['member_id']),
						'order' => array('id' => 'DESC')
					))->first();

					$roles[$k]['status'] = isset($statusData['status']) ? $statusData['status'] : '';
					$roles[$k]['created'] = isset($statusData['created']) ? $statusData['created'] : '';
				}
				$response['roles'] = $roles;
				//$response['approvalFlag']=$approvalFlag;
			}
			
			$maxRoleOrder = $this->MemberRoles->find()
				->select(['member_id', 'role_order'])
				->where(['app_type' => $app_type])
				->order(['role_order' => 'DESC'])
				->first();
			$jpoFlag = 0;
			if ($maxRoleOrder) {
				if ($maxRoleOrder->member_id ==  $this->Session->read("Members.id")) {
					$jpoFlag = 1;
				}
			}
			$response['jpoFlag'] = $jpoFlag;

			//$response['roles'] = $roles;
			echo json_encode($response);
			return;
		}
		exit;
	}

	public function developer_form_approval_msg()
	{
		$application_id 				= (isset($this->request->data['id']) ? decode($this->request->data['id']) : 0);
		$dev_app_id						= (isset($this->request->data['dev_app_id']) ? decode($this->request->data['dev_app_id']) : 0);
		$this->autoRender 				= false;

		if (!empty($application_id) && isset($application_id)) {

			$fields             = [
				'DeveloperApplicationQuery.id',
				'DeveloperApplicationQuery.app_dev_per_id',
				'DeveloperApplicationQuery.application_id',
				'DeveloperApplicationQuery.member_id',
				'member_role' => 'mr.role',
				'DeveloperApplicationQuery.query_msg',
				'DeveloperApplicationQuery.forward_to',
				'forward_role' => 'fr.role',
				'DeveloperApplicationQuery.status',
				'DeveloperApplicationQuery.created'
			];
			$join_arr  		= [
				'mr'	=> ['table' => 'member_roles', 'type' => 'left', 'conditions' => 'DeveloperApplicationQuery.member_id=mr.member_id'],
				'fr'	=> ['table' => 'member_roles', 'type' => 'left', 'conditions' => 'DeveloperApplicationQuery.forward_to=fr.member_id'],
			];
			$applicationMsgs = $this->DeveloperApplicationQuery->find('all', array(
				'fields' 	=> $fields,
				'join' 		=> $join_arr,
				'conditions' => array('DeveloperApplicationQuery.application_id' => $application_id, 'DeveloperApplicationQuery.app_dev_per_id' => $dev_app_id, 'DeveloperApplicationQuery.status' => 2, 'mr.app_type' => 2),
				'group' => array('DeveloperApplicationQuery.id'),
				'order' => array('DeveloperApplicationQuery.id' => 'DESC')
			))->toArray();

			echo json_encode($applicationMsgs);
			return;
		}
	}

	public function developer_application_query()
	{
		$this->autoRender 				= false;
		$memberId 		= $this->Session->read("Members.id");
		if (empty($memberId)) {
			return $this->redirect('/home');
		}
		$devAppId 		= isset($this->request->data['dev_app_id']) && !empty($this->request->data['dev_app_id']) ? decode($this->request->data['dev_app_id']) : 0;
		$applicationId 	= isset($this->request->data['application_id']) && !empty($this->request->data['application_id']) ? decode($this->request->data('application_id')) : 0;
		$action 		= $this->request->data['action'];
		$forward		= isset($this->request->data['forward']) && !empty($this->request->data['forward']) ? $this->request->data('forward') : 0;
		$appType		= isset($this->request->data['app_type']) && !empty($this->request->data['app_type']) ? $this->request->data('app_type') : 0;

		$inwardDate 	= isset($this->request->data['inward_date']) && !empty($this->request->data['inward_date']) ? date('Y-m-d', strtotime($this->request->data['inward_date'])) : '';

		if (!empty($this->request->data)) {

			$save_data_entity    				= $this->DeveloperApplicationQuery->newEntity();

			$save_data_entity->app_dev_per_id 	= $devAppId;
			$save_data_entity->application_id 	= $applicationId;
			$save_data_entity->member_id 		= isset($memberId) && !empty($memberId) ? $memberId : 0;
			$save_data_entity->query_msg 		= $this->request->data('comment');
			$save_data_entity->status 			= $action;
			$save_data_entity->forward_to		= $forward;
			

			if ($action == 2) {
				$this->ApplicationStages->saveDevAppStatus($applicationId, $this->ApplicationStages->DEVELOPER_APPLICATION_FORWARD, $memberId, $devAppId);
			}

			if ($action == 1 && !empty($inwardDate) && isset($inwardDate)) {

				$application = $this->Applications->find('all', array('fields' => array('registration_no'), 'conditions' => array('id' => $applicationId)))->first();
				if($appType == 2){
					$finalRegistration = $this->OpenAccessApplicationDeveloperPermission->regNumber($application['registration_no'], $devAppId);
				}
				if($appType == 3 || $appType == 4){
					$finalRegistration = $this->WindApplicationDeveloperPermission->regNumber($application['registration_no'], $devAppId);
				}
				$updateData = [
					'inward_date' 				=> $inwardDate,
					'final_registration_no' 	=> $finalRegistration,
					'final_registration_date' 	=> $this->NOW(),
					'status'					=> 1,
					'editable'					=> 0,
					'modified' 					=> $this->NOW(),
					'modified_by'				=> $memberId
				];
				$this->ApplicationStages->saveDevAppStatus($applicationId, $this->ApplicationStages->DEVELOPER_APPLICATION_VERIFIED, $memberId, $devAppId);
				if($appType == 2){
					$result = $this->OpenAccessApplicationDeveloperPermission->updateAll($updateData, array("application_id" => $applicationId, 'id' => $devAppId));
				}
				if($appType == 3 || $appType == 4){
					$result = $this->WindApplicationDeveloperPermission->updateAll($updateData, array("application_id" => $applicationId, 'id' => $devAppId));
				}
			}
			if ($action == 3) {

				$message 			= (isset($this->request->data['developer_comment']) ? $this->request->data['developer_comment'] : '');
				$for_claim 			= 0;

				$applyOnlinesData 		= $this->Applications->viewApplication($applicationId);

				if (!empty($applyOnlinesData)) {
					if ($this->request->is('post') || $this->request->is('put')) {
						$customer_type 						= $this->Session->read('Customers.customer_type');
						$customer_id          				= $this->Session->read("Customers.id");
						$member_id          				= $this->Session->read("Members.id");
						$member_type 						= $this->Session->read('Members.member_type');

						$browser 										= isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "-";
						$ApplicationsMessageEntity						= $this->ApplicationsMessage->newEntity();
						$ApplicationsMessageEntity->application_id 		= $applicationId;
						$ApplicationsMessageEntity->message 			= strip_tags($message);
						$ApplicationsMessageEntity->user_type 			= !empty($customer_type) ? 0 : $member_type;
						$ApplicationsMessageEntity->user_id 			= !empty($customer_id) ? $customer_id : $member_id;
						$ApplicationsMessageEntity->ip_address 			= $this->IP_ADDRESS;
						$ApplicationsMessageEntity->for_claim 			= $for_claim;
						$ApplicationsMessageEntity->application_status 	= $applyOnlinesData->application_status;
						$ApplicationsMessageEntity->created 			= $this->NOW();
						$ApplicationsMessageEntity->browser_info 		= json_encode($browser);

						if ($this->ApplicationsMessage->save($ApplicationsMessageEntity)) {
							$updateData = [
								'editable'				=> 1,
								'modified' 				=> $this->NOW(),
								'modified_by'			=> $memberId
							];
							$result = $this->OpenAccessApplicationDeveloperPermission->updateAll($updateData, array("application_id" => $applicationId, 'id' => $devAppId));

							$applyid 		= $applyOnlinesData->id;
							if (!empty($applyid)) {
								$data 				= $this->Applications->get($applyid);
								$data->query_sent	= '1';
								$data->query_date 	= date('Y-m-d H:i:s');
								$data->modified 	= date('Y-m-d H:i:s');
								$this->Applications->save($data);
							}
							$response = ['status' => 1, 'message' => 'Message sent successfully.'];
							echo json_encode($response);
							return;
						} else {
							$response = ['status' => 0, 'message' => 'Error while sending message.'];
							echo json_encode($response);
							return;
						}
					}
				}
			}
			if ($this->DeveloperApplicationQuery->save($save_data_entity)) {
				if($appType == 2){
					$result = $this->OpenAccessApplicationDeveloperPermission->updateAll(array('inward_date'=>$inwardDate), array("application_id" => $applicationId, 'id' => $devAppId));
				}
				if($appType == 3 || $appType == 4){
					$result = $this->WindApplicationDeveloperPermission->updateAll(array('inward_date'=>$inwardDate), array("application_id" => $applicationId, 'id' => $devAppId));
				}
				$response = ['status' => 1, 'message' => 'Data saved successfully.'];
			} else {
				$response = ['status' => 0, 'message' => 'Data could not be saved. Please try again.'];
			}
			echo json_encode($response);
			return;
		}
		exit;
	}
	/**
	 * Final Registration of Wind 
	 */
	public function WindDeveloperPermission($id = 0, $dev_app_id = 0, $activetab = 0)
	{

		$tab_id 				= ($activetab > 1) ? $activetab : 1;
		$type_of_applicant 		= $this->ApiToken->arrFirmDropdown;
		$designation 			= $this->ApiToken->arrDesignation;
		$EndSTU 				= $this->ApiToken->arrEndSTU;
		$EndCTU 				= $this->ApiToken->arrEndCTU;
		$customerId 			= $this->Session->read("Customers.id");
		$member_id 				= $this->Session->read("Members.id");
		$ses_customer_type 		= $this->Session->read('Customers.customer_type');
		$gridLevel 				= $this->ApiToken->arrGridLevel;
		$injectionLevel 		= $this->ApiToken->arrInjectionLevel;

		if ($ses_customer_type == "installer") {
			$is_installer 		= true;
			$customer_details 	= $this->DeveloperCustomers->find('all', array('conditions' => array('id' => $customerId)))->first();
			$installer_id 		= $customer_details['installer_id'];
		}

		if (empty($customerId) && empty($member_id)) {
			return $this->redirect(URL_HTTP . '/home');
		}

		$count 		= $this->WindApplicationDeveloperPermission->find('all', array('conditions' => array('id' => decode($dev_app_id))))->count();
		if ($count > 0) {
			$Applications = $this->WindApplicationDeveloperPermission->find('all', array('conditions' => array('id' => decode($dev_app_id))))->first();
			$tid = (!empty($id)) ? decode($id) : $id;
		} else {
			$Applications 		= $this->Applications->find('all', array('conditions' => array('id' => decode($id))))->first();
			$tid = '';
		}

		//state,distict,taluka data
		$arrStateData 		= $this->States->find("list", ['keyField' => 'statename', 'valueField' => 'statename'])->toArray();
		$district 			= $this->DistrictMaster->find("list", ['keyField' => 'id', 'valueField' => 'name', 'conditions' => ['state_id' => 4]]);
		$arrTalukaData		= $this->TalukaMaster->find("list", ['keyField' => 'id', 'valueField' => 'name']);

		//discom list
		$discom_arr = array();
		$discoms 	= $this->BranchMasters->find("list", ['keyField' => 'id', 'valueField' => 'title', 'conditions' => ['BranchMasters.status' => '1', 'BranchMasters.parent_id' => '0', 'BranchMasters.state' => $this->ApplyOnlines->gujarat_st_id]])->toArray();
		if (!empty($discoms)) {
			foreach ($discoms as $keyid => $title) {
				$discom_arr[$keyid] = $title;
			}
		}

		//end use of electricity depends of application id
		$arrEndUse 			= $this->EndUseElectricity->find('all', array('conditions' => ['application_id' => decode($id)]))->toArray();
		$arrEndUseElec		= array();
		if (!empty($arrEndUse)) {
			foreach ($arrEndUse as $selectEnd) {
				$arrEndUseElec[] 	= $selectEnd->application_end_use_electricity;
			}
		}

		$application_errors = array();
		$app_dev_id = isset($dev_app_id) ? $dev_app_id : 0;

		if (!empty($this->request->data)) {

			$cur_tab 	= $this->request->data['tab_id'];
			$errors		= array();
			$this->request->data['Applications']['application_id'] 	= $id;
			$this->request->data['Applications']['application_type'] = isset($Applications) && !empty($Applications) ? $Applications['application_type'] : 0;



			if (isset($this->request->data['cgp']) && !empty($this->request->data['cgp'])) {
				$this->wind_share_cgp_files($this->request->data['cgp'], $Applications['id'], $Applications['application_id']);
			}

			switch ($cur_tab) {
				case '1':
					$response 		= $this->wind_general_profile($this->request->data);
					break;
				case '2':
					$response 		= $this->wind_technical_details($this->request->data);
					break;
				case '3':
					$response 		= $this->wind_project_details($this->request->data);
					break;
				case '4':
					$response 		= $this->wind_upload($this->request->data);
					break;
				case '5':
					$response 		= $this->wind_fees_structure($this->request->data);
					break;
				default:
					break;
			}

			$arrResponse 		= json_decode($response, 1);

			$application_errors = $arrResponse['response_errors'];
			$app_dev_id = isset($arrResponse['app_dev_id']) && !empty($arrResponse['app_dev_id']) ? encode($arrResponse['app_dev_id']) : 0;

			$count 		= $this->WindApplicationDeveloperPermission->find('all', array('conditions' => array('id' => decode($app_dev_id))))->count();
			if ($count > 0) {

				$Applications = $this->WindApplicationDeveloperPermission->find('all', array('conditions' => array('id' => decode($app_dev_id))))->first();

				$tid = (!empty($id)) ? decode($id) : $id;
			} else {
				$Applications 		= $this->Applications->find('all', array('conditions' => array('id' => decode($id))))->first();
				$tid = '';
			}

			$this->WindApplicationDeveloperPermission->dataRecord = $Applications;

			if (!empty($application_errors)) {

				$Applications->errors($application_errors);

				$tab_id 		= $cur_tab;
			} else {

				if (isset($this->request->data['next_' . $cur_tab]) && $arrResponse['success'] == '1') {
					$tab_id 		= $cur_tab + 1;
				} else {
					$tab_id 		= $cur_tab;
					if ($tab_id == 5)
						return $this->redirect(URL_HTTP . '/Applications/list');
				}
			}
			if (isset($this->request->data['cgp']) && !empty($this->request->data['cgp'])) {
				$docFieldArr = $this->wind_share_cgp_files($this->request->data['cgp'], $Applications['id'], $Applications['application_id']);
			}
		}

		$type_manufacturer_wind 	= $this->ManufacturerMaster->manufacturerDropdown(3);
		$application_geo_loc = [];

		$application_geo_loc = $this->ApplicationGeoLocation->find('all', array(
			'fields'	=> [
				'manufacturer_master.name', 'id', 'x_cordinate', 'y_cordinate', 'wtg_make', 'wtg_model', 'wtg_capacity',
				'wtg_rotor_dimension', 'wtg_hub_height',
			],
			'join'		=> [
				['table' => 'manufacturer_master', 'type' => 'LEFT', 'conditions' => ['wtg_make = manufacturer_master.id']],
				['table' => 'wind_wtg_detail', 'type' => 'LEFT', 'conditions' => ['ApplicationGeoLocation.id = wind_wtg_detail.app_geo_loc_id']]
			],
			'conditions' => array('application_id' => decode($id), 'approved' => 1, "( wind_wtg_detail.app_dev_per_id =" . decode($app_dev_id) . "  OR wind_wtg_detail.app_geo_loc_id IS NULL)")
		))->toArray();

		$application_geo_loc_land = [];
		$application_geo_loc_land = $this->ApplicationGeoLocation->find('all', array(
			'fields'	=> ['id', 'x_cordinate', 'y_cordinate', 'geo_village', 'geo_taluka', 'geo_district', 'sub_lease_deed', 'land_survey_no', 'type_of_land'],
			'join'		=> [['table' => 'wind_wtg_detail', 'type' => 'LEFT', 'conditions' => ['ApplicationGeoLocation.id = wind_wtg_detail.app_geo_loc_id']]],
			'conditions' => array('application_id' => decode($id), 'approved' => 1, "( wind_wtg_detail.app_dev_per_id =" . decode($app_dev_id) . ")")
		))->toArray();


		$geo_location_land_details = [];
		if (isset($application_geo_loc_land) && !empty($application_geo_loc_land)) {
			foreach ($application_geo_loc_land as $ak => $av) {

				$windLand = $this->WindLandDetails->find('all', array('conditions' => ['app_geo_loc_id' => $av['id']]))->first();

				if (isset($windLand) && !empty($windLand)) {
					$data['app_geo_loc_id'] = $windLand['app_geo_loc_id'];
					$data['x_cordinate'] 	= $windLand['land_latitude'];
					$data['y_cordinate'] 	= $windLand['land_longitude'];
					$data['geo_village'] 	= $windLand['land_village'];
					$data['geo_taluka'] 	= $windLand['land_taluka'];
					$data['geo_district'] 	= $windLand['land_district'];
					$data['geo_state']		= '4';
					$data['type_of_land'] 	= $windLand['land_category'];
					$data['land_survey_no']	= $windLand['land_plot_servey_no'];
					$data['land_area']		= $windLand['area_of_land'];
					$data['deed_of_land']	= $windLand['deed_of_land'];
					$data['deed_doc']		= $windLand['deed_doc'];
				} else {

					$data['app_geo_loc_id'] = $av['id'];
					$data['x_cordinate'] 	= $av['x_cordinate'];
					$data['y_cordinate'] 	= $av['y_cordinate'];
					$data['geo_village'] 	= $av['geo_village'];
					$data['geo_taluka'] 	= $av['geo_taluka'];
					$data['geo_district'] 	= $av['geo_district'];
					$data['geo_state']		= '4';
					$data['type_of_land'] 	= $av['type_of_land'];
					$data['land_survey_no']	= $av['land_survey_no'];
					$data['land_area']		= '';
					$data['deed_of_land']	= '';
					$data['deed_doc']		= '';
				}

				array_push($geo_location_land_details, $data);
			}
		}

		$geoVariable = [];
		if ($count > 0) {
			$geoData = $this->WindWtgDetail->find('all', array('conditions' => array('app_dev_per_id' => decode($app_dev_id))))->toArray();
			foreach ($geoData as $gk => $gv) {
				array_push($geoVariable, $gv['app_geo_loc_id']);
			}
			if (isset($this->request->data['geo_loc_ids']) && !empty($this->request->data['geo_loc_ids'])) {
				foreach ($this->request->data['geo_loc_ids'] as $gek => $gev) {

					if (!in_array($gev, $geoVariable)) {
						array_push($geoVariable, $gev);
					}
				}
			}
		}

		$Energy_Data 	= $this->WindEnergyAdditionalData->find('all', array(
			'fields' => array('app_dev_per_id', 'energy_discom', 'energy_per', 'id'),
			'conditions' => array('app_dev_per_id' => decode($app_dev_id))
		))->toArray();

		$errorModule 			= 0;
		$errorEnergy = 0;
		if (!empty($application_errors) || (isset($arrResponse['addMoreError']) && $arrResponse['addMoreError'] == 1)) {
			$Energy_Data 		= array();
			if (isset($this->request->data['energy_discom'])) {
				foreach ($this->request->data['energy_discom'] as $key => $value) {
					$Energy_Data[$key]['energy_discom'] 		= $value;
					$Energy_Data[$key]['energy_per'] 			= $this->request->data['energy_per'][$key];

					if (empty($value) || empty($this->request->data['energy_discom'][$key]) || empty($this->request->data['energy_per'][$key])) {
						$errorEnergy 	= 1;
					}
				}
			}
		}

		$applicationCategory = '';
		$feesDetails = [];
		if (!empty($Applications) && isset($Applications) && $count > 0) {
			$applicationCategory = $this->ApplicationCategory->find('all', array('conditions' => array('id' => $Applications->application_type)))->first();

			$capacitySum = 0;
			$provisionalFees = 0;

			if ($Applications->application_type == 3) {

				$capacitySum = $this->WindWtgDetail->getWtgSum($Applications->id) ?? 0;
			} elseif ($Applications->application_type == 4) {

				$additionalDataSum = $this->HybridAdditionalData->getHybridDataSum($Applications->id, '2');
				$invTotCapacity = $additionalDataSum['mod_inv_total_capacity'] ?? 0;
				$capacityWtgSum = $this->WindWtgDetail->getWtgSum($Applications->id) ?? 0;
				$capacitySum = $capacityWtgSum + $invTotCapacity;
			}

			if ($capacitySum > 0 && isset($applicationCategory) && !empty($applicationCategory)) {
				if ($capacitySum <= 25) {
					$dev_application_fees = $applicationCategory['dev_per_upto_25_mw'];
				} elseif ($capacitySum > 25 && $capacitySum <= 50) {
					$dev_application_fees = $applicationCategory['dev_per_above_25_to_50_mw'];
				} elseif ($capacitySum > 50 && $capacitySum <= 75) {
					$dev_application_fees = $applicationCategory['dev_per_above_50_to_75_mw'];
				} elseif ($capacitySum > 75 && $capacitySum <= 100) {
					$dev_application_fees = $applicationCategory['dev_per_above_75_to_100_mw'];
				} elseif ($capacitySum > 100) {
					$dev_application_fees = $applicationCategory['dev_per_above_100_mw'];
				} else {
					$dev_application_fees = 0;
				}

				$checkProvisionalFees = $this->WindApplicationDeveloperPermission->find('all', array('conditions' => array('application_id' => $Applications->application_id, 'payment_status' => 1)))->first();
				if (isset($checkProvisionalFees) && !empty($checkProvisionalFees)) {
					$provisionalFees = 0;
				} else {

					$application = $this->Applications->find('all', array('conditions' => array('id' => $Applications->application_id)))->first();
					$provisionalFees = isset($application) ? $application->application_fee : 0;
				}

				$feesDetails['application_fees'] = $dev_application_fees - $provisionalFees;
				$feesDetails['application_tax_percentage'] = $applicationCategory['dev_per_tax_per'];
				$feesDetails['gst_fees'] = $feesDetails['application_fees'] * $feesDetails['application_tax_percentage'] / 100;
				$feesDetails['application_total_fee'] = $feesDetails['application_fees'] + $feesDetails['gst_fees'];
			}
		}


		//Geo Land 
		$errorLand	= 0;
		if (!empty($application_errors) || (isset($arrResponse['addMoreError']) && $arrResponse['addMoreError'] == 1)) {
			if (isset($this->request->data['land_category'])) {
				foreach ($this->request->data['land_category'] as $key => $value) {
					if (empty($value) || empty($this->request->data['land_plot_servey_no'][$key]) || empty($this->request->data['land_village'][$key]) || empty($this->request->data['land_taluka'][$key]) || empty($this->request->data['land_state'][$key]) || empty($this->request->data['land_district'][$key]) || empty($this->request->data['land_latitude'][$key]) || empty($this->request->data['land_longitude'][$key]) || empty($this->request->data['area_of_land'][$key]) || empty($this->request->data['deed_of_land'][$key]) || empty($this->request->data['a_deed_doc'][$key])) {
						$errorLand 	= 1;
					}
				}
			}
		}

		//Inverter(Roof) Land
		$errorInvLand = 0;
		$Roof_Land_Data = $this->WindLandDetails->fetchdata($Applications->id, 0);
		$Land_Data_1 	= $this->WindLandDetails->fetchdata($Applications->id, 0);

		if (!empty($application_errors) || (isset($arrResponse['addMoreError']) && $arrResponse['addMoreError'] == 1)) {

			$Roof_Land_Data 		= array();

			if (isset($this->request->data['inv_land_category'])) {

				foreach ($this->request->data['inv_land_category'] as $key => $value) {

					$Roof_Land_Data[$key]['land_category'] 				= $value;
					$Roof_Land_Data[$key]['land_plot_servey_no'] 		= $this->request->data['inv_land_plot_servey_no'][$key];
					$Roof_Land_Data[$key]['land_village'] 				= $this->request->data['inv_land_village'][$key];
					$Roof_Land_Data[$key]['land_taluka'] 				= $this->request->data['inv_land_taluka'][$key];
					$Roof_Land_Data[$key]['land_state'] 					= 'Gujarat';
					$Roof_Land_Data[$key]['land_district'] 				= $this->request->data['inv_land_district'][$key];
					$Roof_Land_Data[$key]['land_latitude']				= $this->request->data['inv_land_latitude'][$key];
					$Roof_Land_Data[$key]['land_longitude']				= $this->request->data['inv_land_longitude'][$key];
					$Roof_Land_Data[$key]['area_of_land']				= $this->request->data['inv_area_of_land'][$key];
					$Roof_Land_Data[$key]['deed_of_land']				= $this->request->data['inv_deed_of_land'][$key];

					if (isset($this->request->data['inv_deed_doc'][$key])) {
						$Roof_Land_Data[$key]['deed_doc']					= isset($this->request->data['inv_deed_doc'][$key]) ? $this->request->data['inv_deed_doc'][$key] : '';
					} else {
						$Roof_Land_Data[$key]['deed_doc']					= isset($Land_Data_1[$key]['deed_doc']) ? $Land_Data_1[$key]['deed_doc'] : '';
						$Roof_Land_Data[$key]['app_dev_per_id']				= isset($Land_Data_1[$key]['app_dev_per_id']) ? $Land_Data_1[$key]['app_dev_per_id'] : '';
						$Roof_Land_Data[$key]['couch_id']					= isset($Land_Data_1[$key]['couch_id']) ? $Land_Data_1[$key]['couch_id'] : '';
					}

					$Roof_Land_Data[$key]['application_id'] 				= $Applications->id;
					$Roof_Land_Data[$key]['id'] 							= isset($this->request->data['id_inv_land'][$key]) ? $this->request->data['id_inv_land'][$key] : 0;

					if (empty($value) || empty($this->request->data['inv_land_plot_servey_no'][$key]) || empty($this->request->data['inv_land_village'][$key]) || empty($this->request->data['inv_land_taluka'][$key]) || empty($this->request->data['inv_land_state'][$key]) || empty($this->request->data['inv_land_district'][$key]) || empty($this->request->data['inv_land_latitude'][$key]) || empty($this->request->data['inv_land_longitude'][$key]) || empty($this->request->data['inv_area_of_land'][$key]) || empty($this->request->data['inv_deed_of_land'][$key]) || empty($this->request->data['a_inv_deed_doc'][$key])) {
						$errorInvLand 	= 1;
					}

					$taluka	= $this->TalukaMaster->find("list", ['keyField' => 'id', 'valueField' => 'name', 'conditions' => array('district_id' => $Roof_Land_Data[$key]['land_district'])])->toArray();
					$Roof_Land_Data[$key]['taluka']			= isset($taluka) && !empty($taluka) ? $taluka : '';
				}
			}
		}


		$Pooling_Data 	= $this->WindEvaculationPoolingData->fetchdata($Applications->id);
		$errorPooling	= 0;
		if (!empty($application_errors) || (isset($arrResponse['addMoreError']) && $arrResponse['addMoreError'] == 1)) {
			$Pooling_Data 		= array();
			if (isset($this->request->data['name_of_pooling_sub'])) {

				foreach ($this->request->data['name_of_pooling_sub'] as $pkey => $pvalue) {

					$Pooling_Data[$pkey]['name_of_pooling_sub'] 		= $pvalue;
					$Pooling_Data[$pkey]['distict_of_pooling_sub'] 		= $this->request->data['distict_of_pooling_sub'][$pkey];
					$Pooling_Data[$pkey]['taluka_of_pooling_sub'] 		= $this->request->data['taluka_of_pooling_sub'][$pkey];
					$Pooling_Data[$pkey]['village_of_pooling_sub'] 		= $this->request->data['village_of_pooling_sub'][$pkey];
					//$Pooling_Data[$pkey]['cap_of_pooling_sub'] 			= $this->request->data['cap_of_pooling_sub'][$pkey];
					$Pooling_Data[$pkey]['vol_of_pooling_sub']			= $this->request->data['vol_of_pooling_sub'][$pkey];
					$Pooling_Data[$pkey]['sub_mw_of_pooling_sub']		= $this->request->data['sub_mw_of_pooling_sub'][$pkey];
					$Pooling_Data[$pkey]['sub_mva_of_pooling_sub']		= $this->request->data['sub_mva_of_pooling_sub'][$pkey];
					$Pooling_Data[$pkey]['conn_mw_of_pooling_sub']		= $this->request->data['conn_mw_of_pooling_sub'][$pkey];
					$Pooling_Data[$pkey]['conn_mva_of_pooling_sub']		= $this->request->data['conn_mva_of_pooling_sub'][$pkey];
					$Pooling_Data[$pkey]['application_id'] 				= $Applications->id;
					$Pooling_Data[$pkey]['id'] 							= isset($this->request->data['id_pooling'][$pkey]) ? $this->request->data['id_pooling'][$pkey] : 0;

					if (empty($pvalue) || empty($this->request->data['distict_of_pooling_sub'][$pkey]) || empty($this->request->data['taluka_of_pooling_sub'][$pkey]) || empty($this->request->data['village_of_pooling_sub'][$pkey])  || empty($this->request->data['vol_of_pooling_sub'][$pkey]) || empty($this->request->data['sub_mw_of_pooling_sub'][$pkey]) || empty($this->request->data['sub_mva_of_pooling_sub'][$pkey]) || empty($this->request->data['conn_mw_of_pooling_sub'][$pkey]) || empty($this->request->data['conn_mva_of_pooling_sub'][$pkey])) {


						$errorPooling 	= 1;
					}
				}
			}
		}

		$Getco_Data 	= $this->WindEvaculationGetcoData->fetchdata($Applications->id);
		$errorGetco		= 0;
		if (!empty($application_errors) || (isset($arrResponse['addMoreError']) && $arrResponse['addMoreError'] == 1)) {
			$Getco_Data 		= array();

			if (isset($this->request->data['name_of_getco'])) {

				foreach ($this->request->data['name_of_getco'] as $gkey => $gvalue) {
					$Getco_Data[$gkey]['name_of_getco'] 				= $gvalue;
					$Getco_Data[$gkey]['distict_of_getco'] 			= $this->request->data['distict_of_getco'][$gkey];
					$Getco_Data[$gkey]['taluka_of_getco'] 			= $this->request->data['taluka_of_getco'][$gkey];
					$Getco_Data[$gkey]['village_of_getco'] 			= $this->request->data['village_of_getco'][$gkey];
					$Getco_Data[$gkey]['cap_of_getco'] 				= $this->request->data['cap_of_getco'][$gkey];
					$Getco_Data[$gkey]['vol_of_getco']				= $this->request->data['vol_of_getco'][$gkey];
					$Getco_Data[$gkey]['sub_mw_of_getco']			= $this->request->data['sub_mw_of_getco'][$gkey];
					$Getco_Data[$gkey]['sub_mva_of_getco']			= $this->request->data['sub_mva_of_getco'][$gkey];
					$Getco_Data[$gkey]['conn_mw_of_getco']			= $this->request->data['conn_mw_of_getco'][$gkey];
					$Getco_Data[$gkey]['application_id'] 			= $Applications->id;
					$Getco_Data[$gkey]['id'] 						= isset($this->request->data['id_getco'][$gkey]) ? $this->request->data['id_getco'][$gkey] : 0;

					if (empty($gvalue)  || empty($this->request->data['distict_of_getco'][$gkey]) || empty($this->request->data['taluka_of_getco'][$gkey]) || empty($this->request->data['village_of_getco'][$gkey]) || empty($this->request->data['cap_of_getco'][$gkey]) || empty($this->request->data['vol_of_getco'][$gkey]) || empty($this->request->data['sub_mw_of_getco'][$gkey]) || empty($this->request->data['sub_mva_of_getco'][$gkey]) || empty($this->request->data['conn_mw_of_getco'][$gkey])) {

						$errorGetco 	= 1;
					}
				}
			}
		}


		$Consumer_Share_Data 	= $this->WindShareDetails->fetchdata($Applications->id);
		$errorShare		= 0;
		if (!empty($application_errors) || (isset($arrResponse['addMoreError']) && $arrResponse['addMoreError'] == 1)) {
			$Consumer_Share_Data 		= array();
			if (isset($this->request->data['name_of_share_holder'])) {

				foreach ($this->request->data['name_of_share_holder'] as $key => $value) {
					$Consumer_Share_Data[$key]['name_of_share_holder'] 		= $value;
					$Consumer_Share_Data[$key]['equity_persontage'] 		= $this->request->data['equity_persontage'][$key];
					$Consumer_Share_Data[$key]['application_id'] 			= $Applications->id;
					$Consumer_Share_Data[$key]['id'] 						= isset($this->request->data['id_consumer'][$key]) ? $this->request->data['id_consumer'][$key] : 0;

					if (empty($value)  || empty($this->request->data['equity_persontage'][$key])) {
						$errorShare 	= 1;
					}
				}
			}
		}

		//Hybrid Module & Inverter
		$type_manufacturer_mod 	= $this->ManufacturerMaster->manufacturerList(1);
		$type_manufacturer_inv 	= $this->ManufacturerMaster->manufacturerList(2);
		$totalInverternos		= $this->HybridAdditionalData->getHybridDataSum($Applications->id, 2);
		$totalModulenos			= $this->HybridAdditionalData->getHybridDataSum($Applications->id, 1);
		$totalInverterCapacity	= $this->HybridAdditionalData->getcapacitysum($Applications->id, 2);
		$totalModuleCapacity	= $this->HybridAdditionalData->getcapacitysum($Applications->id, 1);

		$Module_Data 	= $this->HybridAdditionalData->fetchdata($Applications->id, 1);
		$errorModule 			= 0;
		if (!empty($application_errors) || (isset($arrResponse['addMoreError']) && $arrResponse['addMoreError'] == 1)) {
			$Module_Data 		= array();
			if (isset($this->request->data['nos_mod'])) {
				foreach ($this->request->data['nos_mod'] as $key => $value) {
					$Module_Data[$key]['nos_mod_inv'] 				= $value;
					$Module_Data[$key]['mod_inv_capacity'] 			= $this->request->data['mod_capacity'][$key];
					$Module_Data[$key]['mod_inv_total_capacity'] 	= $this->request->data['mod_total_capacity'][$key];
					$Module_Data[$key]['mod_inv_make'] 				= $this->request->data['mod_make'][$key];
					$Module_Data[$key]['type_of_spv_technologies'] 	= $this->request->data['type_of_spv'][$key];
					$Module_Data[$key]['type_of_solar_panel'] 		= $this->request->data['type_of_solar'][$key];
					$Module_Data[$key]['application_id'] 			= $Applications->id;
					$Module_Data[$key]['capacity_type'] 			= 1;
					$Module_Data[$key]['id'] 						= isset($this->request->data['id_module'][$key]) ? $this->request->data['id_module'][$key] : 0;

					if (empty($value) || empty($this->request->data['mod_capacity'][$key]) || empty($this->request->data['mod_make'][$key]) || empty($this->request->data['type_of_spv'][$key]) || empty($this->request->data['type_of_solar'][$key])) {
						$errorModule 	= 1;
					}
				}
			}
		}

		$Inverter_Data 	= $this->HybridAdditionalData->fetchdata($Applications->id, 2);
		$errorInverter 			= 0;
		if (!empty($application_errors) || (isset($arrResponse['addMoreError']) && $arrResponse['addMoreError'] == 1)) {
			$Inverter_Data 		= array();
			if (isset($this->request->data['nos_inv'])) {
				foreach ($this->request->data['nos_inv'] as $key => $value) {
					$Inverter_Data[$key]['nos_mod_inv'] 				= $value;
					$Inverter_Data[$key]['mod_inv_capacity'] 		= $this->request->data['inv_capacity'][$key];
					$Inverter_Data[$key]['mod_inv_total_capacity'] 	= $this->request->data['inv_total_capacity'][$key];
					$Inverter_Data[$key]['mod_inv_make'] 			= $this->request->data['inv_make'][$key];
					$Inverter_Data[$key]['type_of_inverter_used'] 	= $this->request->data['type_of_inverter_used'][$key];
					$Inverter_Data[$key]['application_id'] 			= $Applications->id;
					$Inverter_Data[$key]['capacity_type'] 			= 2;
					$Inverter_Data[$key]['id'] 						= isset($this->request->data['id_inverter'][$key]) ? $this->request->data['id_inverter'][$key] : 0;

					if (empty($value) || empty($this->request->data['inv_capacity'][$key]) || empty($this->request->data['inv_make'][$key]) || empty($this->request->data['type_of_inverter_used'][$key])) {
						$errorInverter 	= 1;
					}
				}
			}
		}

		$developer_details 		= $this->DeveloperCustomers->find('all', array('conditions' => array('id' => $customerId)))->first();


		$this->set('app_dev_id', $app_dev_id);
		$this->set('geoVariable', $geoVariable);
		$this->set('pageTitle', 'Re-Application');
		$this->set('developer_details', $developer_details);
		$this->set("arrStateData", $arrStateData);
		$this->set("arrTalukaData", $arrTalukaData);
		$this->set("arrDistictData", $district);
		$this->set('Applications', $Applications);
		$this->set('Couchdb', $this->ReCouchdb);
		$this->set('type_of_applicant', $type_of_applicant);
		$this->set('designation', $designation);
		$this->set("tab_id", $tab_id);
		$this->set('type_manufacturer_wind', $type_manufacturer_wind);
		$this->set("discom_arr", $discom_arr);
		$this->set("applicationID", $tid);
		$this->set("ApplicationError", $application_errors);
		$this->set('end_use_of_power', $this->WindApplicationDeveloperPermission->end_use_of_power);
		$this->set('landCategory', $this->OpenAccessApplicationDeveloperPermission->land_category);
		$this->set('deedOfLand', $this->OpenAccessApplicationDeveloperPermission->deed_of_land);
		$this->set('govDeedOfLand', $this->WindApplicationDeveloperPermission->gov_deed_of_land);
		$this->set('privateDeedOfLand', $this->WindApplicationDeveloperPermission->private_deed_of_land);
		$this->set('voltageLevel', $this->OpenAccessApplicationDeveloperPermission->voltage_level);
		$this->set('gridLevel', $gridLevel);
		$this->set('injectionLevel', $injectionLevel);
		$this->set("arrEndUseElec", $arrEndUseElec);
		//$this->set('EndSTU', $EndSTU);
		$this->set('EndSTU', $this->OpenAccessApplicationDeveloperPermission->end_use_of_electricity);
		$this->set('captive', $this->WindApplicationDeveloperPermission->captive);
		$this->set('third_party', $this->OpenAccessApplicationDeveloperPermission->third_party);
		$this->set('EndCTU', $EndCTU);
		$this->set('ApplicationGeoLoc', $application_geo_loc);
		$this->set('energyData', $Energy_Data);
		$this->set('feesDetails', $feesDetails);
		$this->set('ApplicationGeoLocLand', $geo_location_land_details);
		$this->set('Wind_Pooling_Data', $Pooling_Data);
		$this->set('Wind_Getco_Data', $Getco_Data);
		$this->set('Consumer_Share_Data', $Consumer_Share_Data);
		$this->set('errorShare', $errorShare);
		$this->set("errorLand", $errorLand);
		$this->set('errorPooling', $errorPooling);
		$this->set('errorGetco', $errorGetco);
		$this->set('errorEnergy', $errorEnergy);
		$this->set('equityShare', $this->WindApplicationDeveloperPermission->equity_share);
		$this->set('cgp', $this->WindApplicationDeveloperPermission->cgp);
		//Hybrid
		$this->set("errorInvLand", $errorInvLand);
		$this->set("Roof_Land_Data", $Roof_Land_Data);
		$this->set("errorModule", $errorModule);
		$this->set("errorInverter", $errorInverter);
		$this->set('type_manufacturer_mod', $type_manufacturer_mod);
		$this->set('type_manufacturer_inv', $type_manufacturer_inv);
		$this->set('Hybrid_Inverter_Data', $Inverter_Data);
		$this->set('Hybrid_Module_Data', $Module_Data);
		$this->set('typeOfspv', $this->OpenAccessApplicationDeveloperPermission->type_of_spv);
		$this->set('typeOfSolarPanel', $this->OpenAccessApplicationDeveloperPermission->type_of_solar_panel);
		$this->set('typeOfInverterUsed', $this->OpenAccessApplicationDeveloperPermission->type_of_inverter_used);
		$this->set('totalInverternos', $totalInverternos);
		$this->set('totalModulenos', $totalModulenos);
		$this->set('totalInverterCapacity', $totalInverterCapacity);
		$this->set('totalModuleCapacity', $totalModuleCapacity);
	}
	private function wind_share_cgp_files($cgp, $dev_id, $app_id)
	{
		$docTypesMap = [
			1 => ['captive_share_register', 'captive_ca_cs_certi', 'captive_balance_sheet', 'captive_annual_audit'],
			2 => ['partnership_deed', 'partnership_share_holding', 'partnership_return_filed'],
			3 => ['limited_share_register', 'limited_share_certi', 'limited_company_secretary_certi'],
			4 => ['association_certified_return_filed', 'association_share_register', 'association_certi_of_ca', 'certi_from_company_secretary'],
			5 => ['cooperative_certi_from_district_registrar', 'cooperative_share_register'],
			6 => ['spv_company_return_file', 'spv_company_certi_of_share_register', 'spv_company_memorandum', 'spv_company_articles_of_associate', 'spv_company_company_secretary'],
			7 => ['cgp_holding_annual_balance_sheet', 'cgp_holding_acc_of_company'],
			8 => ['cgp_annual_balance_sheet', 'cgp_acc_of_company']
		];
		$docFields = [];
		if (array_key_exists($cgp, $docTypesMap)) {
			foreach ($docTypesMap[$cgp] as $docType) {
				$docExist = $this->DeveloperApplicationsDocs->find('all', [
					'conditions' => ['application_id' => $app_id, 'dev_app_id' => $dev_id, 'doc_type' => $docType], 'order' => ['created' => 'DESC']
				])->first();

				if ($docExist) {
					$this->WindApplicationDeveloperPermission->dataRecord[$docType] = $docExist['file_name'];
					$docFields[$docType] = $docExist['file_name'];
				}
			}
		}
		return $docFields;
	}
	private function wind_general_profile($request_data)
	{

		if (!empty($this->Session->read('Members.member_type')))
			$customerId = $this->Session->read("Members.id");
		else
			$customerId = $this->Session->read("Customers.id");


		$app_id = !empty($request_data['Applications']['application_id']) ? decode($request_data['Applications']['application_id']) : 0;
		$dev_app_id = !empty($request_data['app_dev_id']) ? decode($request_data['app_dev_id']) : 0;
		$applicaiton_exist = 0;
		if (!empty($app_id)) {
			$applicaiton_exist 	= $this->WindApplicationDeveloperPermission->find('all', array('conditions' => array('application_id' => $app_id, 'id' => $dev_app_id)))->first();
			$this->WindApplicationDeveloperPermission->dataPass = $request_data;
		}
		if (empty($applicaiton_exist)) {

			$fields = [
				'application_type', 'name_of_applicant', 'address', 'address1', 'taluka', 'pincode', 'city', 'state', 'district', 'district_code',
				'type_of_applicant', 'registration_document', 'applicant_others', 'contact', 'mobile', 'email', 'pan', 'pan_card', 'GST', 'a_msme', 'upload_undertaking',
				'name_director', 'type_director', 'type_director_others', 'director_whatsapp', 'director_mobile', 'director_email', 'name_authority', 'type_authority',
				'type_authority_others', 'd_file_board', 'authority_whatsapp', 'authority_mobile', 'authority_email', 'getco_substation', 'project_estimated_cost',
				'discom', 'grid_connectivity', 'injection_level', 'project_energy'
			];


			$applicaiton 	= $this->Applications->find('all', array('fields' => $fields, 'conditions' => array('id' => $app_id)))->first();
			$existApp = $this->WindApplicationDeveloperPermission->find('all', array('fields' => array('app_order'), 'conditions' => array('application_id' => $app_id), 'order' => array('id' => 'desc')))->first();
			if (isset($existApp) && !empty($existApp)) {
				$appOrder = $existApp->app_order =  $existApp->app_order + 1;
			} else {
				$appOrder = 1;
			}
			$EndUseElectricity = $this->EndUseElectricity->find('all', array('conditions' => array('application_id' => $app_id)))->first();
			if (isset($EndUseElectricity) && !empty($EndUseElectricity)) {
				if ($EndUseElectricity['application_end_use_electricity'] == 1) {
					$end_use_of_electricity = 2;
				}
				if ($EndUseElectricity['application_end_use_electricity'] == 2) {
					$end_use_of_electricity = 3;
				}
				if ($EndUseElectricity['application_end_use_electricity'] == 3) {
					$end_use_of_electricity = 1;
				}
			}

			$data = [
				'application_type'			=>	$applicaiton->application_type,
				'name_of_applicant'			=>	$applicaiton->name_of_applicant,
				'address'					=>	$applicaiton->address,
				'address1'					=>	$applicaiton->address1,
				'taluka'					=>	$applicaiton->taluka,
				'pincode'					=>	$applicaiton->pincode,
				'city'						=>	$applicaiton->city,
				'state'						=>	$applicaiton->state,
				'district'					=>	$applicaiton->district,
				'district_code'				=>	$applicaiton->district_code,
				'type_of_applicant'			=>	$applicaiton->type_of_applicant,
				'registration_document'		=>	$applicaiton->registration_document,
				'applicant_others'			=>	$applicaiton->applicant_others,
				'contact'					=>	$applicaiton->contact,
				'mobile'					=>	$applicaiton->mobile,
				'email'						=>	$applicaiton->email,
				'pan'						=>	$applicaiton->pan,
				'pan_card'					=>	$applicaiton->pan_card,
				'GST'						=>	$applicaiton->GST,
				'a_msme'					=>	$applicaiton->a_msme,
				'upload_undertaking'		=>	$applicaiton->upload_undertaking,
				'name_director'				=>	$applicaiton->name_director,
				'type_director'				=>	$applicaiton->type_director,
				'type_director_others'		=>	$applicaiton->type_director_others,
				'director_whatsapp'			=>	$applicaiton->director_whatsapp,
				'director_mobile'			=>	$applicaiton->director_mobile,
				'director_email'			=>	$applicaiton->director_email,
				'name_authority'			=>	$applicaiton->name_authority,
				'type_authority'			=>	$applicaiton->type_authority,
				'type_authority_others'		=>	$applicaiton->type_authority_others,
				'd_file_board'				=>	$applicaiton->d_file_board,
				'authority_whatsapp'		=>	$applicaiton->authority_whatsapp,
				'authority_mobile'			=>	$applicaiton->authority_mobile,
				'authority_email'			=>	$applicaiton->authority_email,
				'getco_substation'			=>	$applicaiton->getco_substation,
				"name_authority" 			=>	 $request_data['name_authority'],
				"type_authority" 			=>	 $request_data['type_authority'],
				"authority_whatsapp" 		=>	 $request_data['authority_whatsapp'],
				"authority_mobile" 			=>	 $request_data['authority_mobile'],
				"authority_email" 			=>	 $request_data['authority_email'],
				"tab_id"					=>	1,
				"grid_connectivity"			=>	$applicaiton->grid_connectivity,
				"injection_level"			=> 	$applicaiton->injection_level,
				"project_energy"			=>	$applicaiton->project_energy,
				"project_estimated_cost"	=>	$applicaiton->project_estimated_cost,

			];

			if (isset($end_use_of_electricity) && $applicaiton->grid_connectivity == 1) {
				$data['end_stu'] = $end_use_of_electricity;
			}
			if (isset($end_use_of_electricity) && $applicaiton->grid_connectivity == 2) {
				$data['end_ctu'] = $end_use_of_electricity;
			}
			$ApplicationEntity 				= $this->WindApplicationDeveloperPermission->newEntity($data, ['validate' => 'tab1']);
			$ApplicationEntity->created 	= $this->NOW();
			$ApplicationEntity->created_by 	= $customerId;
			$ApplicationEntity->customer_id = $customerId;

			if ($this->Session->read("Customers.id") && !empty($this->Session->read("Customers.id"))) {
				$customer_details 				= $this->DeveloperCustomers->find('all', array('conditions' => array('id' => $customerId)))->first();
				$ApplicationEntity->installer_id = $customer_details->installer_id;
			}
			$ApplicationEntity->app_order 		= $appOrder;
			$saveText = 'inserted';
		} else {

			$ApplicationEntity 					= $this->WindApplicationDeveloperPermission->patchEntity($applicaiton_exist, $request_data, ['validate' => 'tab1']);
			$saveText							= 'updated';
		}
		$ApplicationEntity->application_id 	= $app_id;
		$ApplicationEntity->modified 		= $this->NOW();
		$ApplicationEntity->modified_by 	= $customerId;
		if (isset($request_data['tab_id']) && $request_data['tab_id'] == 1) {
			$ApplicationEntity->tab_1 = 1;
		}

		if (!empty($ApplicationEntity->errors())) {
			return json_encode(array('success' => '0', 'response_errors' => $ApplicationEntity->errors(), 'app_dev_id' => $dev_app_id));
		} else {
			$profileData = $this->WindApplicationDeveloperPermission->save($ApplicationEntity);
			//$this->request->data = [];
			$this->Flash->success("Application $saveText successfully.");

			return json_encode(array('success' => '1', 'response_errors' => '', 'application_id' => $ApplicationEntity->id, 'app_dev_id' => $profileData->id));
		}
	}

	/**
	 * imgfile_upload
	 *
	 * Behaviour : public	
	 * @param : id  : $file is use to identify for which image should be select and $path is use to identify the image folder path.	
	 * @defination : Method is use to save the image in file folder .
	 */

	public function wind_imgfile_upload($file, $prefix_file = '', $application_id, $file_field, $access_type = '')
	{

		$customerId 	= $this->Session->read('Customers.id');
		$name 			= $file['name'];
		$path 			= APPLICATIONS_DEVELOPER_PERMISSION_PATH . 'wind/' . $application_id . '/';
		if (!file_exists(APPLICATIONS_DEVELOPER_PERMISSION_PATH . 'wind/' . $application_id)) {
			@mkdir(APPLICATIONS_DEVELOPER_PERMISSION_PATH . 'wind/' . $application_id, 0777, true);
		}
		$updateRequestData 	= $this->WindApplicationDeveloperPermission->find('all', array('conditions' => array('id' => $application_id)))->first();
		if (!empty($updateRequestData->$file_field) && file_exists($path . $updateRequestData->$file_field)) {
			@unlink($path . $updateRequestData->$file_field);
		}
		$ext    		= substr(strtolower(strrchr($file['name'], '.')), 1);
		$file_name   	= $prefix_file . date('YmdHis') . rand();
		$file_location  = $path . $file_name . '.' . $ext;

		move_uploaded_file($file['tmp_name'], $file_location);

		$passFileName 	= $file_name . '.' . $ext;
		$couchdbId 		= $this->ReCouchdb->saveData($path, $file_location, $prefix_file, $passFileName, $customerId, $access_type);

		return $file_name . '.' . $ext;
	}

	public function wind_share_cgp_imgfile_upload($file, $prefix_file = '', $app_id, $application_id, $file_field, $access_type = '')
	{

		$customerId 	= $this->Session->read('Customers.id');
		$name 			= $file['name'];
		$path 			= APPLICATIONS_DEVELOPER_PERMISSION_PATH . 'wind/' . $application_id . '/';
		if (!file_exists(APPLICATIONS_DEVELOPER_PERMISSION_PATH . 'wind/' . $application_id)) {
			@mkdir(APPLICATIONS_DEVELOPER_PERMISSION_PATH . 'wind/' . $application_id, 0777, true);
		}
		$updateRequestData 	= $this->DeveloperApplicationsDocs->find('all', array('conditions' => array('application_id' => $app_id, 'dev_app_id' => $application_id, 'doc_type' => $access_type)))->first();
		if (!empty($updateRequestData->file_name) && file_exists($path . $updateRequestData->file_name)) {
			@unlink($path . $updateRequestData->file_name);
		}
		$ext    		= substr(strtolower(strrchr($file['name'], '.')), 1);
		$file_name   	= $prefix_file . date('YmdHis') . rand();
		$file_location  = $path . $file_name . '.' . $ext;

		move_uploaded_file($file['tmp_name'], $file_location);

		$passFileName 	= $file_name . '.' . $ext;
		$couchdbId 		= $this->ReCouchdb->saveData($path, $file_location, $prefix_file, $passFileName, $customerId, $access_type);

		if (!empty($this->Session->read('Members.member_type')))
			$customerId = $this->Session->read("Members.id");
		else
			$customerId = $this->Session->read("Customers.id");

		$ApplicationEntity11 	= $this->DeveloperApplicationsDocs->find('all', array('conditions' => array('application_id' => $app_id, 'dev_app_id' => $application_id, 'doc_type' => $access_type)))->first();

		if (empty($ApplicationEntity11)) {
			$ApplicationEntity11 = $this->DeveloperApplicationsDocs->newEntity();
			$ApplicationEntity11->created 	= $this->NOW();
			$ApplicationEntity11->created_by 	= $customerId;
		}
		$ApplicationEntity11->application_id = $app_id;
		$ApplicationEntity11->dev_app_id = $application_id;
		$ApplicationEntity11->couchdb_id = $couchdbId;
		$ApplicationEntity11->file_name = $passFileName;
		$ApplicationEntity11->title = $file_field;
		$ApplicationEntity11->doc_type = $access_type;

		

		// if (!empty($doc_exist)) {
			$this->DeveloperApplicationsDocs->save($ApplicationEntity11);
			// $this->DeveloperApplicationsDocs->updateAll($ApplicationEntity11, array("application_id" => $app_id, 'dev_app_id' => $application_id, 'doc_type' => $access_type));
		// } else {
		// 	$ApplicationEntity11->created 	= $this->NOW();
		// 	$ApplicationEntity11->created_by 	= $customerId;
		// 	$this->DeveloperApplicationsDocs->save($ApplicationEntity11);
		// }
		return $file_name . '.' . $ext;
	}

	public function wind_land_imgfile_upload($file, $prefix_file = '', $application_id, $file_field, $access_type = '', $fileName)
	{

		$customerId 	= $this->Session->read('Customers.id');

		$name 			= $file['name'];

		$path 			= APPLICATIONS_DEVELOPER_PERMISSION_PATH . 'wind/' . $application_id . '/';
		if (!file_exists(APPLICATIONS_DEVELOPER_PERMISSION_PATH . 'wind/' . $application_id)) {
			@mkdir(APPLICATIONS_DEVELOPER_PERMISSION_PATH . 'wind/' . $application_id, 0777, true);
		}
		if (isset($fileName) && !empty($fileName)) {
			$updateRequestData 	= $this->WindLandDetails->find('all', array('conditions' => array('app_dev_per_id' => $application_id, 'deed_doc' => $fileName)))->first();

			if (!empty($updateRequestData->$file_field) && file_exists($path . $updateRequestData->$file_field)) {
				@unlink($path . $updateRequestData->$file_field);
			}
		}
		$ext    		= substr(strtolower(strrchr($file['name'], '.')), 1);
		$file_name   	= $prefix_file . date('YmdHis') . rand();
		$file_location  = $path . $file_name . '.' . $ext;

		move_uploaded_file($file['tmp_name'], $file_location);

		$passFileName 	= $file_name . '.' . $ext;
		$couchdbId 		= $this->ReCouchdb->saveData($path, $file_location, $prefix_file, $passFileName, $customerId, $access_type);

		return $file_name . '.' . $ext;
	}

	/***
	 * Wind Technical Detail
	 */
	private function wind_technical_details($request_data)
	{

		if (!empty($this->Session->read('Members.member_type')))
			$customerId = $this->Session->read("Members.id");
		else
			$customerId = $this->Session->read("Customers.id");


		$app_id = !empty($request_data['Applications']['application_id']) ? decode($request_data['Applications']['application_id']) : 0;
		$dev_app_id = !empty($request_data['app_dev_id']) ? decode($request_data['app_dev_id']) : 0;
		$applicaiton_exist = '';

		if (!empty($app_id)) {
			$applicaiton_exist 	= $this->WindApplicationDeveloperPermission->find('all', array('conditions' => array('application_id' => $app_id, 'id' => $dev_app_id)))->first();
			$pvCapacity = $this->Applications->find('all', array('fields' => array('total_wind_hybrid_capacity', 'total_capacity'), 'conditions' => array('id' => $applicaiton_exist['application_id'])))->first();
			$request_data['total_wind_hybrid_capacity'] = $pvCapacity['total_wind_hybrid_capacity'];
			$request_data['total_capacity'] = $pvCapacity['total_capacity'];
			$this->WindApplicationDeveloperPermission->dataRecord = $applicaiton_exist;
			$this->WindApplicationDeveloperPermission->dataPass = $request_data;
		}

		$ApplicationEntity 					= $this->WindApplicationDeveloperPermission->patchEntity($applicaiton_exist, $request_data, ['validate' => 'tab2']);
		$saveText							= 'updated';
		$ApplicationEntity->modified 		= $this->NOW();
		$ApplicationEntity->modified_by 	= $customerId;

		if (isset($request_data['tab_id']) && $request_data['tab_id'] == 2) {
			$ApplicationEntity->tab_2 = 1;
		}

		if (!empty($ApplicationEntity->errors())) {
			return json_encode(array('success' => '0', 'response_errors' => $ApplicationEntity->errors(), 'addMoreError' => 1, 'app_dev_id' => $dev_app_id));
		} else {
			$errorGeoLoc 			= 0;
			if (isset($request_data['geo_loc_ids'])) {
				foreach ($request_data['geo_loc_ids'] as $gkey => $gvalue) {
					if (!isset($gvalue)) {
						$errorGeoLoc 	= 1;
					}
				}
			}

			$errorShare 			= 0;
			if (isset($request_data['name_of_share_holder'])) {
				foreach ($request_data['name_of_share_holder'] as $skey => $svalue) {
					if (!isset($svalue)) {
						$errorShare 	= 1;
					}
				}
			}

			$errorModule 			= 0;
			if (isset($request_data['nos_mod'])) {

				foreach ($request_data['nos_mod'] as $key => $value) {
					if (empty($value) || empty($request_data['mod_capacity'][$key]) || empty($request_data['mod_make'][$key]) || empty($request_data['type_of_spv'][$key]) || empty($request_data['type_of_solar'][$key])) {
						$errorModule 	= 1;
					}
				}
			}

			$errorInverter 			= 0;
			if (isset($request_data['nos_inv'])) {
				foreach ($request_data['nos_inv'] as $key => $value) {
					if (empty($value) || empty($request_data['inv_capacity'][$key]) || empty($request_data['inv_make'][$key]) || empty($request_data['type_of_inverter_used'][$key])) {
						$errorInverter 	= 1;
					}
				}
			}

			if (!empty($ApplicationEntity->errors()) ||  $errorGeoLoc == 1 || $errorShare == 1 || $errorModule == 1 || $errorInverter == 1) {
				return json_encode(array('success' => '0', 'response_errors' => $ApplicationEntity->errors(), 'addMoreError' => 1, 'app_dev_id' => $dev_app_id));
			} else {

				$this->WindApplicationDeveloperPermission->save($ApplicationEntity);

				//upload_sale_to_discom - file
				if (isset($this->request->data['a_upload_sale_to_discom']['tmp_name']) && !empty($this->request->data['a_upload_sale_to_discom']['tmp_name'])) {
					$file_name 	= $this->wind_imgfile_upload($this->request->data['a_upload_sale_to_discom'], 'saledis', $ApplicationEntity->id, 'upload_sale_to_discom', 'upload_sale_to_discom');
					$this->WindApplicationDeveloperPermission->updateAll(
						array("upload_sale_to_discom" => $file_name),
						array("id" => $ApplicationEntity->id)
					);
				}

				//no_due_1 - file
				if (isset($this->request->data['a_no_due_1']['tmp_name']) && !empty($this->request->data['a_no_due_1']['tmp_name'])) {
					$file_name 	= $this->wind_imgfile_upload($this->request->data['a_no_due_1'], 'nodue1', $ApplicationEntity->id, 'no_due_1', 'no_due_1');
					$this->WindApplicationDeveloperPermission->updateAll(
						array("no_due_1" => $file_name),
						array("id" => $ApplicationEntity->id)
					);
				}

				//no_due_2 - file
				if (isset($this->request->data['a_no_due_2']['tmp_name']) && !empty($this->request->data['a_no_due_2']['tmp_name'])) {
					$file_name 	= $this->wind_imgfile_upload($this->request->data['a_no_due_2'], 'nodue2', $ApplicationEntity->id, 'no_due_2', 'no_due_2');
					$this->WindApplicationDeveloperPermission->updateAll(
						array("no_due_2" => $file_name),
						array("id" => $ApplicationEntity->id)
					);
				}

				//upload_proof_of_ownership_1 - file
				if (isset($this->request->data['a_upload_proof_of_ownership_1']['tmp_name']) && !empty($this->request->data['a_upload_proof_of_ownership_1']['tmp_name'])) {
					$file_name 	= $this->wind_imgfile_upload($this->request->data['a_upload_proof_of_ownership_1'], 'own1', $ApplicationEntity->id, 'upload_proof_of_ownership_1', 'upload_proof_of_ownership_1');
					$this->WindApplicationDeveloperPermission->updateAll(
						array("upload_proof_of_ownership_1" => $file_name),
						array("id" => $ApplicationEntity->id)
					);
				}

				//upload_proof_of_ownership_2 - file
				if (isset($this->request->data['a_upload_proof_of_ownership_2']['tmp_name']) && !empty($this->request->data['a_upload_proof_of_ownership_2']['tmp_name'])) {
					$file_name 	= $this->wind_imgfile_upload($this->request->data['a_upload_proof_of_ownership_2'], 'own2', $ApplicationEntity->id, 'upload_proof_of_ownership_2', 'upload_proof_of_ownership_2');
					$this->WindApplicationDeveloperPermission->updateAll(
						array("upload_proof_of_ownership_2" => $file_name),
						array("id" => $ApplicationEntity->id)
					);
				}

				//Electricity Bill
				if (isset($this->request->data['a_copy_of_electricity_bill']['tmp_name']) && !empty($this->request->data['a_copy_of_electricity_bill']['tmp_name'])) {
					$file_name 	= $this->wind_imgfile_upload($this->request->data['a_copy_of_electricity_bill'], 'electricitybill', $ApplicationEntity->id, 'copy_of_electricity_bill', 'copy_of_electricity_bill');
					$this->WindApplicationDeveloperPermission->updateAll(
						array("copy_of_electricity_bill" => $file_name),
						array("id" => $ApplicationEntity->id)
					);
				}

				// 1. captive_share_register
				if (isset($this->request->data['a_captive_share_register']['tmp_name']) && !empty($this->request->data['a_captive_share_register']['tmp_name'])) {

					$file_name 	= $this->wind_share_cgp_imgfile_upload($this->request->data['a_captive_share_register'], 'captiveshareregister', $ApplicationEntity->application_id, $ApplicationEntity->id, 'captive_share_register', 'captive_share_register');
				}

				// captive_ca_cs_certi
				if (isset($this->request->data['a_captive_ca_cs_certi']['tmp_name']) && !empty($this->request->data['a_captive_ca_cs_certi']['tmp_name'])) {
					$file_name 	= $this->wind_share_cgp_imgfile_upload($this->request->data['a_captive_ca_cs_certi'], 'captivecacscerti', $ApplicationEntity->application_id, $ApplicationEntity->id, 'captive_ca_cs_certi', 'captive_ca_cs_certi');
				}

				// captive_balance_sheet
				if (isset($this->request->data['a_captive_balance_sheet']['tmp_name']) && !empty($this->request->data['a_captive_balance_sheet']['tmp_name'])) {
					$file_name 	= $this->wind_share_cgp_imgfile_upload($this->request->data['a_captive_balance_sheet'], 'captivebalancesheet', $ApplicationEntity->application_id, $ApplicationEntity->id, 'captive_balance_sheet', 'captive_balance_sheet');
				}

				// captive_annual_audit
				if (isset($this->request->data['a_captive_annual_audit']['tmp_name']) && !empty($this->request->data['a_captive_annual_audit']['tmp_name'])) {
					$file_name 	= $this->wind_share_cgp_imgfile_upload($this->request->data['a_captive_annual_audit'], 'captiveannualaudit', $ApplicationEntity->application_id, $ApplicationEntity->id, 'captive_annual_audit', 'captive_annual_audit');
				}

				// 2. partnership_deed
				if (isset($this->request->data['a_partnership_deed']['tmp_name']) && !empty($this->request->data['a_partnership_deed']['tmp_name'])) {
					$file_name 	= $this->wind_share_cgp_imgfile_upload($this->request->data['a_partnership_deed'], 'partnershipdeed', $ApplicationEntity->application_id, $ApplicationEntity->id, 'partnership_deed', 'partnership_deed');
				}

				//partnership_share_holding
				if (isset($this->request->data['a_partnership_share_holding']['tmp_name']) && !empty($this->request->data['a_partnership_share_holding']['tmp_name'])) {
					$file_name 	= $this->wind_share_cgp_imgfile_upload($this->request->data['a_partnership_share_holding'], 'partnershipshareholding', $ApplicationEntity->application_id, $ApplicationEntity->id, 'partnership_share_holding', 'partnership_share_holding');
				}

				//partnership_return_filed
				if (isset($this->request->data['a_partnership_return_filed']['tmp_name']) && !empty($this->request->data['a_partnership_return_filed']['tmp_name'])) {
					$file_name 	= $this->wind_share_cgp_imgfile_upload($this->request->data['a_partnership_return_filed'], 'partnershipreturnfiled', $ApplicationEntity->application_id, $ApplicationEntity->id, 'partnership_return_filed', 'partnership_return_filed');
				}

				// 3.limited_share_register
				if (isset($this->request->data['a_limited_share_register']['tmp_name']) && !empty($this->request->data['a_limited_share_register']['tmp_name'])) {
					$file_name 	= $this->wind_share_cgp_imgfile_upload($this->request->data['a_limited_share_register'], 'limitedshareregister', $ApplicationEntity->application_id, $ApplicationEntity->id, 'limited_share_register', 'limited_share_register');
				}

				//limited_share_certi
				if (isset($this->request->data['a_limited_share_certi']['tmp_name']) && !empty($this->request->data['a_limited_share_certi']['tmp_name'])) {
					$file_name 	= $this->wind_share_cgp_imgfile_upload($this->request->data['a_limited_share_certi'], 'limitedsharecerti', $ApplicationEntity->application_id, $ApplicationEntity->id, 'limited_share_certi', 'limited_share_certi');
				}

				//limited_company_secretary_certi
				if (isset($this->request->data['a_limited_company_secretary_certi']['tmp_name']) && !empty($this->request->data['a_limited_company_secretary_certi']['tmp_name'])) {
					$file_name 	= $this->wind_share_cgp_imgfile_upload($this->request->data['a_limited_company_secretary_certi'], 'limitedcompanysecretarycerti', $ApplicationEntity->application_id, $ApplicationEntity->id, 'limited_company_secretary_certi', 'limited_company_secretary_certi');
				}

				// 4.association_certified_return_filed
				if (isset($this->request->data['a_association_certified_return_filed']['tmp_name']) && !empty($this->request->data['a_association_certified_return_filed']['tmp_name'])) {
					$file_name 	= $this->wind_share_cgp_imgfile_upload($this->request->data['a_association_certified_return_filed'], 'associationcertifiedreturnfiled', $ApplicationEntity->application_id, $ApplicationEntity->id, 'association_certified_return_filed', 'association_certified_return_filed');
				}

				//association_share_register
				if (isset($this->request->data['a_association_share_register']['tmp_name']) && !empty($this->request->data['a_association_share_register']['tmp_name'])) {
					$file_name 	= $this->wind_share_cgp_imgfile_upload($this->request->data['a_association_share_register'], 'associationshareregister', $ApplicationEntity->application_id, $ApplicationEntity->id, 'association_share_register', 'association_share_register');
				}

				//association_certi_of_ca
				if (isset($this->request->data['a_association_certi_of_ca']['tmp_name']) && !empty($this->request->data['a_association_certi_of_ca']['tmp_name'])) {
					$file_name 	= $this->wind_share_cgp_imgfile_upload($this->request->data['a_association_certi_of_ca'], 'associationcertiofca', $ApplicationEntity->application_id, $ApplicationEntity->id, 'association_certi_of_ca', 'association_certi_of_ca');
				}

				//certi_from_company_secretary
				if (isset($this->request->data['a_certi_from_company_secretary']['tmp_name']) && !empty($this->request->data['a_certi_from_company_secretary']['tmp_name'])) {
					$file_name 	= $this->wind_share_cgp_imgfile_upload($this->request->data['a_certi_from_company_secretary'], 'certifromcompanysecretary', $ApplicationEntity->application_id, $ApplicationEntity->id, 'certi_from_company_secretary', 'certi_from_company_secretary');
				}

				// 5.cooperative_certi_from_district_registrar
				if (isset($this->request->data['a_cooperative_certi_from_district_registrar']['tmp_name']) && !empty($this->request->data['a_cooperative_certi_from_district_registrar']['tmp_name'])) {
					$file_name 	= $this->wind_share_cgp_imgfile_upload($this->request->data['a_cooperative_certi_from_district_registrar'], 'cooperativecerti', $ApplicationEntity->application_id, $ApplicationEntity->id, 'cooperative_certi_from_district_registrar', 'cooperative_certi_from_district_registrar');
				}

				//cooperative_share_register
				if (isset($this->request->data['a_cooperative_share_register']['tmp_name']) && !empty($this->request->data['a_cooperative_share_register']['tmp_name'])) {
					$file_name 	= $this->wind_share_cgp_imgfile_upload($this->request->data['a_cooperative_share_register'], 'cooperativeshare', $ApplicationEntity->application_id, $ApplicationEntity->id, 'cooperative_share_register', 'cooperative_share_register');
				}

				//6.spv_company_return_file
				if (isset($this->request->data['a_spv_company_return_file']['tmp_name']) && !empty($this->request->data['a_spv_company_return_file']['tmp_name'])) {
					$file_name 	= $this->wind_share_cgp_imgfile_upload($this->request->data['a_spv_company_return_file'], 'spvcompanyreturnfile', $ApplicationEntity->application_id, $ApplicationEntity->id, 'spv_company_return_file', 'spv_company_return_file');
				}

				//spv_company_certi_of_share_register
				if (isset($this->request->data['a_spv_company_certi_of_share_register']['tmp_name']) && !empty($this->request->data['a_spv_company_certi_of_share_register']['tmp_name'])) {
					$file_name 	= $this->wind_share_cgp_imgfile_upload($this->request->data['a_spv_company_certi_of_share_register'], 'spvcompanycerti', $ApplicationEntity->application_id, $ApplicationEntity->id, 'spv_company_certi_of_share_register', 'spv_company_certi_of_share_register');
				}

				//spv_company_memorandum
				if (isset($this->request->data['a_spv_company_memorandum']['tmp_name']) && !empty($this->request->data['a_spv_company_memorandum']['tmp_name'])) {
					$file_name 	= $this->wind_share_cgp_imgfile_upload($this->request->data['a_spv_company_memorandum'], 'spvcompanymemorandum', $ApplicationEntity->application_id, $ApplicationEntity->id, 'spv_company_memorandum', 'spv_company_memorandum');
				}

				//spv_company_articles_of_associate
				if (isset($this->request->data['a_spv_company_articles_of_associate']['tmp_name']) && !empty($this->request->data['a_spv_company_articles_of_associate']['tmp_name'])) {
					$file_name 	= $this->wind_share_cgp_imgfile_upload($this->request->data['a_spv_company_articles_of_associate'], 'spvcompanyarticles', $ApplicationEntity->application_id, $ApplicationEntity->id, 'spv_company_articles_of_associate', 'spv_company_articles_of_associate');
				}

				//spv_company_company_secretary
				if (isset($this->request->data['a_spv_company_company_secretary']['tmp_name']) && !empty($this->request->data['a_spv_company_company_secretary']['tmp_name'])) {
					$file_name 	= $this->wind_share_cgp_imgfile_upload($this->request->data['a_spv_company_company_secretary'], 'spvcompanycompany', $ApplicationEntity->application_id, $ApplicationEntity->id, 'spv_company_company_secretary', 'spv_company_company_secretary');
				}

				//7.cgp_holding_annual_balance_sheet
				if (isset($this->request->data['a_cgp_holding_annual_balance_sheet']['tmp_name']) && !empty($this->request->data['a_cgp_holding_annual_balance_sheet']['tmp_name'])) {
					$file_name 	= $this->wind_share_cgp_imgfile_upload($this->request->data['a_cgp_holding_annual_balance_sheet'], 'cgpholdingannual', $ApplicationEntity->application_id, $ApplicationEntity->id, 'cgp_holding_annual_balance_sheet', 'cgp_holding_annual_balance_sheet');
				}

				//cgp_holding_acc_of_company
				if (isset($this->request->data['a_cgp_holding_acc_of_company']['tmp_name']) && !empty($this->request->data['a_cgp_holding_acc_of_company']['tmp_name'])) {
					$file_name 	= $this->wind_share_cgp_imgfile_upload($this->request->data['a_cgp_holding_acc_of_company'], 'cgpholdingacc', $ApplicationEntity->application_id, $ApplicationEntity->id, 'cgp_holding_acc_of_company', 'cgp_holding_acc_of_company');
				}

				//8.cgp_annual_balance_sheet
				if (isset($this->request->data['a_cgp_annual_balance_sheet']['tmp_name']) && !empty($this->request->data['a_cgp_annual_balance_sheet']['tmp_name'])) {
					$file_name 	= $this->wind_share_cgp_imgfile_upload($this->request->data['a_cgp_annual_balance_sheet'], 'cgpannualbalance', $ApplicationEntity->application_id, $ApplicationEntity->id, 'cgp_annual_balance_sheet', 'cgp_annual_balance_sheet');
				}

				//cgp_acc_of_company
				if (isset($this->request->data['a_cgp_acc_of_company']['tmp_name']) && !empty($this->request->data['a_cgp_acc_of_company']['tmp_name'])) {
					$file_name 	= $this->wind_share_cgp_imgfile_upload($this->request->data['a_cgp_acc_of_company'], 'cgpacc', $ApplicationEntity->application_id, $ApplicationEntity->id, 'cgp_acc_of_company', 'cgp_acc_of_company');
				}

				if (isset($request_data['geo_loc_ids']) && !empty($request_data['geo_loc_ids'])) {
					$this->WindWtgDetail->deleteAll(['app_dev_per_id' => $ApplicationEntity->id]);

					$this->WindApplicationDeveloperPermission->updateAll(
						array("tab_3" => '0'),
						array("id" => $ApplicationEntity->id)
					);
					$landNotExists = $this->WindLandDetails->find('all', [
						'conditions' => [
							'app_geo_loc_id NOT IN' => $request_data['geo_loc_ids'],
							'app_geo_loc_id !=' => 0,
							'app_dev_per_id' => $ApplicationEntity->id
						]
					])->toArray();
					if (isset($landNotExists) && !empty($landNotExists)) {
						foreach ($landNotExists as $key => $value) {
							$path = APPLICATIONS_DEVELOPER_PERMISSION_PATH . 'wind/' . $ApplicationEntity->id . '/';
							if (!empty($value['deed_doc']) && file_exists($path . $value['deed_doc'])) {
								@unlink($path . $value['deed_doc']);
							}

							$this->WindLandDetails->deleteAll(['id' => $value['id']]);
						}
					}
					foreach ($request_data['geo_loc_ids'] as $key => $val) {

						if (!empty($request_data['geo_loc_ids'][$key])) {

							$save_data           							= TableRegistry::get('WindWtgDetail');
							$save_data_entity    							= $save_data->newEntity();
							$save_data_entity->app_dev_per_id   		 	= $ApplicationEntity->id;
							$save_data_entity->app_geo_loc_id   			= $val;
							$save_data_entity->created_by   		 		= $customerId;
							$save_data_entity->created_date           	    = $this->NOW();

							$save_data->save($save_data_entity);
						}
					}
				}

				if (isset($request_data['name_of_share_holder']) && !empty($request_data['name_of_share_holder'])) {

					$this->WindShareDetails->deleteAll(['app_dev_per_id' => $ApplicationEntity->id]);
					foreach ($request_data['name_of_share_holder'] as $key => $val) {
						if (!empty($request_data['name_of_share_holder'][$key])) {

							$arr_modules['name_of_share_holder']				= $val;
							$arr_modules['equity_persontage']					= $request_data['equity_persontage'][$key];
							$this->WindShareDetails->save_wind_share_details($ApplicationEntity->id, $arr_modules, $customerId);
						}
					}
				}

				//Hybrid
				if (isset($request_data['nos_mod']) && !empty($request_data['nos_mod'])) {

					$this->HybridAdditionalData->deleteAll(['app_dev_per_id' => $ApplicationEntity->id, 'capacity_type' => 1]);
					foreach ($request_data['nos_mod'] as $key => $val) {
						if (!empty($request_data['nos_mod'][$key])) {
							$arr_modules['nos_mod']				= $val;
							$arr_modules['mod_capacity']		= $request_data['mod_capacity'][$key];
							$arr_modules['mod_total_capacity']	= $request_data['mod_total_capacity'][$key];
							$arr_modules['mod_make']			= $request_data['mod_make'][$key];
							$arr_modules['type_of_spv']			= $request_data['type_of_spv'][$key];
							$arr_modules['type_of_solar']		= $request_data['type_of_solar'][$key];
							$this->HybridAdditionalData->save_module_hybrid($ApplicationEntity->id, $arr_modules, $this->Session->read('Members.id'));
						}
					}
				}
				if (isset($request_data['nos_inv']) && !empty($request_data['nos_inv'])) {

					$this->HybridAdditionalData->deleteAll(['app_dev_per_id' => $ApplicationEntity->id, 'capacity_type' => 2]);
					foreach ($request_data['nos_inv'] as $key => $val) {
						if (!empty($request_data['nos_inv'][$key])) {
							$arr_inverters['nos_inv']				= $val;
							$arr_inverters['inv_capacity']			= $request_data['inv_capacity'][$key];
							$arr_inverters['inv_total_capacity']	= $request_data['inv_total_capacity'][$key];
							$arr_inverters['inv_make']				= $request_data['inv_make'][$key];
							$arr_inverters['type_of_inverter_used']	= $request_data['type_of_inverter_used'][$key];
							$this->HybridAdditionalData->save_inverter_hybrid($ApplicationEntity->id, $arr_inverters, $this->Session->read('Members.id'));
						}
					}
				}

				$this->Flash->success("Application $saveText successfully!!!!.");
				return json_encode(array('success' => '1', 'response_errors' => '', 'app_dev_id' => $dev_app_id));
			}
		}
	}

	private function wind_project_details($request_data)
	{

		if (!empty($this->Session->read('Members.member_type')))
			$customerId = $this->Session->read("Members.id");
		else
			$customerId = $this->Session->read("Customers.id");

		$app_id = !empty($request_data['Applications']['application_id']) ? decode($request_data['Applications']['application_id']) : 0;
		$dev_app_id = !empty($request_data['app_dev_id']) ? decode($request_data['app_dev_id']) : 0;

		$applicaiton_exist = 0;
		if (!empty($app_id)) {
			$applicaiton_exist 	= $this->WindApplicationDeveloperPermission->find('all', array('conditions' => array('application_id' => $app_id, 'id' => $dev_app_id)))->first();
			$this->WindApplicationDeveloperPermission->dataRecord = $applicaiton_exist;
			$this->WindApplicationDeveloperPermission->dataPass = $request_data;
		}

		if (!empty($applicaiton_exist)) {
			$ApplicationEntity 				= $this->WindApplicationDeveloperPermission->patchEntity($applicaiton_exist, $request_data, ['validate' => 'tab3']);
			$saveText						= 'updated';
		}
		$ApplicationEntity->modified 		= $this->NOW();
		$ApplicationEntity->modified_by 	= $customerId;
		if (isset($request_data['tab_id']) && $request_data['tab_id'] == 3) {
			$ApplicationEntity->tab_3 = 1;
		}
		if (!empty($ApplicationEntity->errors())) {
			return json_encode(array('success' => '0', 'response_errors' => $ApplicationEntity->errors(), 'addMoreError' => 1, 'app_dev_id' => $dev_app_id));
		} else {


			$errorPooling	= 0;
			if (isset($request_data['name_of_pooling_sub'])) {
				foreach ($this->request->data['name_of_pooling_sub'] as $pkey => $pvalue) {
					if (empty($pvalue) || empty($this->request->data['distict_of_pooling_sub'][$pkey]) || empty($this->request->data['taluka_of_pooling_sub'][$pkey]) || empty($this->request->data['village_of_pooling_sub'][$pkey]) || empty($this->request->data['vol_of_pooling_sub'][$pkey]) || empty($this->request->data['sub_mw_of_pooling_sub'][$pkey]) || empty($this->request->data['sub_mva_of_pooling_sub'][$pkey]) || empty($this->request->data['conn_mw_of_pooling_sub'][$pkey]) || empty($this->request->data['conn_mva_of_pooling_sub'][$pkey])) {
						$errorPooling 	= 1;
					}
				}
			}

			$errorGetco		= 0;
			if (isset($request_data['name_of_getco'])) {
				foreach ($this->request->data['name_of_getco'] as $gkey => $gvalue) {
					if (empty($gvalue)  || empty($this->request->data['distict_of_getco'][$gkey]) || empty($this->request->data['taluka_of_getco'][$gkey]) || empty($this->request->data['village_of_getco'][$gkey]) || empty($this->request->data['cap_of_getco'][$gkey]) || empty($this->request->data['vol_of_getco'][$gkey]) || empty($this->request->data['sub_mw_of_getco'][$gkey]) || empty($this->request->data['sub_mva_of_getco'][$gkey]) || empty($this->request->data['conn_mw_of_getco'][$gkey])) {
						$errorGetco 	= 1;
					}
				}
			}

			$errorLand 			= 0;
			if (isset($request_data['land_category'])) {
				foreach ($request_data['land_category'] as $key => $value) {
					if (empty($value) || empty($this->request->data['land_plot_servey_no'][$key]) || empty($this->request->data['land_village'][$key]) || empty($this->request->data['land_taluka'][$key]) || empty($this->request->data['land_district'][$key]) || empty($this->request->data['land_latitude'][$key]) || empty($this->request->data['land_longitude'][$key]) || empty($this->request->data['area_of_land'][$key]) || empty($this->request->data['deed_of_land'][$key])) {
						$errorLand 	= 1;
					}
				}
			}

			//Inverter(Rooftop)
			$errorInvLand 		= 0;
			if (isset($request_data['inv_land_category'])) {
				foreach ($request_data['inv_land_category'] as $key => $value) {
					if (empty($value) || empty($this->request->data['inv_land_plot_servey_no'][$key]) || empty($this->request->data['inv_land_village'][$key]) || empty($this->request->data['inv_land_taluka'][$key]) || empty($this->request->data['inv_land_district'][$key]) || empty($this->request->data['inv_land_latitude'][$key]) || empty($this->request->data['inv_land_longitude'][$key]) || empty($this->request->data['inv_area_of_land'][$key]) || empty($this->request->data['inv_deed_of_land'][$key])) {
						$errorInvLand = 1;
					}
				}
			}

			if (!empty($ApplicationEntity->errors()) || $errorLand == 1 || $errorInvLand == 1 || $errorGetco == 1 || $errorPooling == 1) {

				return json_encode(array('success' => '0', 'response_errors' => $ApplicationEntity->errors(), 'addMoreError' => 1, 'app_dev_id' => $dev_app_id));
			} else {

				$ApplicationEntity->dt_of_per_validity = date('Y-m-d', strtotime($request_data['dt_of_per_validity']));
				$this->WindApplicationDeveloperPermission->save($ApplicationEntity);

				//Getco Permission Letter
				if (isset($this->request->data['a_permission_letter_of_getco']['tmp_name']) && !empty($this->request->data['a_permission_letter_of_getco']['tmp_name'])) {
					$permision_file_name 	= $this->wind_imgfile_upload($this->request->data['a_permission_letter_of_getco'], 'getcopermission', $ApplicationEntity->id, 'permission_letter_of_getco', 'permission_letter_of_getco');
					$this->WindApplicationDeveloperPermission->updateAll(
						array("permission_letter_of_getco" => $permision_file_name),
						array("id" => $ApplicationEntity->id)
					);
				}



				//Land Detail Geo
				if (isset($request_data['land_category']) && !empty($request_data['land_category'])) {

					//$this->WindLandDetails->deleteAll(['app_dev_per_id' => $ApplicationEntity->id, 'app_geo_loc_id !=' => 0]);
					foreach ($request_data['land_category'] as $key => $val) {
						$arr_land_detail = [];
						if (!empty($request_data['land_category'][$key])) {
							$arr_land_detail['land_category']			= $val;
							$arr_land_detail['land_plot_servey_no']		= $request_data['land_plot_servey_no'][$key];
							$arr_land_detail['land_village']			= $request_data['land_village'][$key];
							$arr_land_detail['land_taluka']				= $request_data['land_taluka'][$key];
							$arr_land_detail['land_state']				= 'Gujarat';
							$arr_land_detail['land_district']			= $request_data['land_district'][$key];
							$arr_land_detail['land_latitude']			= $request_data['land_latitude'][$key];
							$arr_land_detail['land_longitude']			= $request_data['land_longitude'][$key];
							$arr_land_detail['area_of_land']			= $request_data['area_of_land'][$key];
							$arr_land_detail['deed_of_land']			= $request_data['deed_of_land'][$key];
							$arr_land_detail['app_geo_loc_id']			= $request_data['app_geo_loc_id'][$key];

							//deed_doc - file

							if (isset($this->request->data['a_deed_doc'][$key]['tmp_name']) && !empty($this->request->data['a_deed_doc'][$key]['tmp_name'])) {

								$fl = isset($request_data['deed_file'][$key]) ? $request_data['deed_file'][$key] : '';

								$file_name 	= $this->wind_land_imgfile_upload($this->request->data['a_deed_doc'][$key], 'deedDoc' . $key, $ApplicationEntity->id,  'deed_doc', 'deed_doc', $fl);

								$arr_land_detail['deed_doc']			=	isset($file_name) ? $file_name : null;

								$couch_data 	= $this->ReCouchdb->find('all', array('fields' => array('id'), 'conditions' => array('application_id' => $ApplicationEntity->id), 'order' => array('id' => 'desc')))->first();

								$arr_land_detail['couch_id']			=	isset($couch_data) ? $couch_data->id : null;
							}
							$this->WindLandDetails->save_wind_land_details($ApplicationEntity->id, $arr_land_detail, $this->Session->read('Members.id'));
						}
					}
				}

				//Inverter(Rooftop)
				if (isset($request_data['inv_land_category']) && !empty($request_data['inv_land_category'])) {

					//$this->OpenAccessLandDetails->deleteAll(['app_dev_per_id' => $ApplicationEntity->id]);
					foreach ($request_data['inv_land_category'] as $key => $val) {
						$arr_land_detail = [];
						if (!empty($request_data['inv_land_category'][$key])) {
							$arr_land_detail['land_category']			= $val;
							$arr_land_detail['land_plot_servey_no']		= $request_data['inv_land_plot_servey_no'][$key];
							$arr_land_detail['land_village']			= $request_data['inv_land_village'][$key];
							$arr_land_detail['land_taluka']				= $request_data['inv_land_taluka'][$key];
							$arr_land_detail['land_state']				= 'Gujarat';
							$arr_land_detail['land_district']			= $request_data['inv_land_district'][$key];
							$arr_land_detail['land_latitude']			= $request_data['inv_land_latitude'][$key];
							$arr_land_detail['land_longitude']			= $request_data['inv_land_longitude'][$key];
							$arr_land_detail['area_of_land']			= $request_data['inv_area_of_land'][$key];
							$arr_land_detail['deed_of_land']			= $request_data['inv_deed_of_land'][$key];
							$arr_land_detail['id_inv_land']				= isset($request_data['id_inv_land'][$key]) ? $request_data['id_inv_land'][$key] : '';


							if (isset($this->request->data['a_inv_deed_doc_' . $key]['tmp_name']) && !empty($this->request->data['a_inv_deed_doc_' . $key]['tmp_name'])) {

								$fl = isset($request_data['inv_deed_file_' . $key]) ? $request_data['inv_deed_file_' . $key] : '';

								$file_name 	= $this->wind_land_imgfile_upload($this->request->data['a_inv_deed_doc_' . $key], 'invdeedDoc' . $key, $ApplicationEntity->id, 'deed_doc', 'deed_doc', $fl);

								$arr_land_detail['deed_doc']			=	isset($file_name) ? $file_name : null;

								$couch_data 	= $this->ReCouchdb->find('all', array('fields' => array('id'), 'conditions' => array('application_id' => $ApplicationEntity->id), 'order' => array('id' => 'desc')))->first();

								$arr_land_detail['couch_id']			=	isset($couch_data) ? $couch_data->id : null;
							}

							$this->WindLandDetails->save_open_access_land_details($ApplicationEntity->id, $arr_land_detail, $this->Session->read('Members.id'));
						}
					}
				}


				//Pooling Details
				if (isset($request_data['name_of_pooling_sub']) && !empty($request_data['name_of_pooling_sub'])) {

					$this->WindEvaculationPoolingData->deleteAll(['app_dev_per_id' => $ApplicationEntity->id]);
					foreach ($this->request->data['name_of_pooling_sub'] as $key => $value) {
						$arr_pooling_data['name_of_pooling_sub'] 			= $value;
						$arr_pooling_data['distict_of_pooling_sub'] 		= $this->request->data['distict_of_pooling_sub'][$key];
						$arr_pooling_data['taluka_of_pooling_sub'] 			= $this->request->data['taluka_of_pooling_sub'][$key];
						$arr_pooling_data['village_of_pooling_sub'] 		= $this->request->data['village_of_pooling_sub'][$key];
						//$arr_pooling_data['cap_of_pooling_sub'] 			= $this->request->data['cap_of_pooling_sub'][$key];
						$arr_pooling_data['vol_of_pooling_sub']				= $this->request->data['vol_of_pooling_sub'][$key];
						$arr_pooling_data['sub_mw_of_pooling_sub']			= $this->request->data['sub_mw_of_pooling_sub'][$key];
						$arr_pooling_data['sub_mva_of_pooling_sub']			= $this->request->data['sub_mva_of_pooling_sub'][$key];
						$arr_pooling_data['conn_mw_of_pooling_sub']			= $this->request->data['conn_mw_of_pooling_sub'][$key];
						$arr_pooling_data['conn_mva_of_pooling_sub']		= $this->request->data['conn_mva_of_pooling_sub'][$key];


						$this->WindEvaculationPoolingData->save_pooling($ApplicationEntity->id, $arr_pooling_data, $this->Session->read('Members.id'));
					}
				}

				//Getco Details
				if (isset($request_data['name_of_getco']) && !empty($request_data['name_of_getco'])) {

					$this->WindEvaculationGetcoData->deleteAll(['app_dev_per_id' => $ApplicationEntity->id]);
					foreach ($this->request->data['name_of_getco'] as $key => $value) {
						$arr_getco_data['name_of_getco'] 			= $value;
						$arr_getco_data['distict_of_getco'] 		= $this->request->data['distict_of_getco'][$key];
						$arr_getco_data['taluka_of_getco'] 			= $this->request->data['taluka_of_getco'][$key];
						$arr_getco_data['village_of_getco'] 		= $this->request->data['village_of_getco'][$key];
						$arr_getco_data['cap_of_getco'] 			= $this->request->data['cap_of_getco'][$key];
						$arr_getco_data['vol_of_getco']				= $this->request->data['vol_of_getco'][$key];
						$arr_getco_data['sub_mw_of_getco']			= $this->request->data['sub_mw_of_getco'][$key];
						$arr_getco_data['sub_mva_of_getco']			= $this->request->data['sub_mva_of_getco'][$key];
						$arr_getco_data['conn_mw_of_getco']			= $this->request->data['conn_mw_of_getco'][$key];

						$this->WindEvaculationGetcoData->save_getco($ApplicationEntity->id, $arr_getco_data, $this->Session->read('Members.id'));
					}
				}

				$this->Flash->success("Application $saveText successfully!!!!.");
				return json_encode(array('success' => '1', 'response_errors' => '', 'app_dev_id' => $dev_app_id));
			}
		}
	}

	private function wind_upload($request_data)
	{

		if (!empty($this->Session->read('Members.member_type')))
			$customerId = $this->Session->read("Members.id");
		else
			$customerId = $this->Session->read("Customers.id");

		$app_id = !empty($request_data['Applications']['application_id']) ? decode($request_data['Applications']['application_id']) : 0;
		$dev_app_id = !empty($request_data['app_dev_id']) ? decode($request_data['app_dev_id']) : 0;

		$applicaiton_exist = 0;
		if (!empty($app_id)) {
			$applicaiton_exist 	= $this->WindApplicationDeveloperPermission->find('all', array('conditions' => array('application_id' => $app_id, 'id' => $dev_app_id)))->first();
			$this->WindApplicationDeveloperPermission->dataRecord = $applicaiton_exist;
			$this->WindApplicationDeveloperPermission->dataPass = $request_data;
			$this->WindApplicationDeveloperPermission->dataPass['app_trans_to_stu'] = $applicaiton_exist->app_trans_to_stu;
		}
		if (!empty($applicaiton_exist)) {

			$ApplicationEntity 				= $this->WindApplicationDeveloperPermission->patchEntity($applicaiton_exist, $request_data, ['validate' => 'tab4']);
			$saveText						= 'updated';
		}
		$ApplicationEntity->modified 		= $this->NOW();
		$ApplicationEntity->modified_by 	= $customerId;
		if (isset($request_data['tab_id']) && $request_data['tab_id'] == 4) {
			$ApplicationEntity->tab_4 = 1;
		}
		if (!empty($ApplicationEntity->errors())) {
			return json_encode(array('success' => '0', 'response_errors' => $ApplicationEntity->errors(), 'addMoreError' => 1, 'app_dev_id' => $dev_app_id));
		} else {

			if (!empty($ApplicationEntity->errors())) {
				return json_encode(array('success' => '0', 'response_errors' => $ApplicationEntity->errors(), 'addMoreError' => 1, 'app_dev_id' => $dev_app_id));
			} else {

				//Undertaking Declaration
				if (isset($this->request->data['a_undertaking_dec']['tmp_name']) && !empty($this->request->data['a_undertaking_dec']['tmp_name'])) {
					$file_name 	= $this->wind_imgfile_upload($this->request->data['a_undertaking_dec'], 'undertakingdeclaration', $ApplicationEntity->id, 'undertaking_dec', 'undertaking_dec');
					$this->WindApplicationDeveloperPermission->updateAll(
						array("undertaking_dec" => $file_name),
						array("id" => $ApplicationEntity->id)
					);
				}
				//Micro sitting Drawing  y
				if (isset($this->request->data['a_micro_sitting_drawing']['tmp_name']) && !empty($this->request->data['a_micro_sitting_drawing']['tmp_name'])) {
					$file_name 	= $this->wind_imgfile_upload($this->request->data['a_micro_sitting_drawing'], 'microdrawing', $ApplicationEntity->id, 'micro_sitting_drawing', 'micro_sitting_drawing');
					$this->WindApplicationDeveloperPermission->updateAll(
						array("micro_sitting_drawing" => $file_name),
						array("id" => $ApplicationEntity->id)
					);
				}

				//Proof of Ownership y
				if (isset($this->request->data['a_proof_of_ownership']['tmp_name']) && !empty($this->request->data['a_proof_of_ownership']['tmp_name'])) {
					$file_name 	= $this->wind_imgfile_upload($this->request->data['a_proof_of_ownership'], 'proofofownership', $ApplicationEntity->id, 'proof_of_ownership', 'proof_of_ownership');
					$this->WindApplicationDeveloperPermission->updateAll(
						array("proof_of_ownership" => $file_name),
						array("id" => $ApplicationEntity->id)
					);
				}

				//Notarized Contract y
				if (isset($this->request->data['a_notarized_contract']['tmp_name']) && !empty($this->request->data['a_notarized_contract']['tmp_name'])) {
					$file_name 	= $this->wind_imgfile_upload($this->request->data['a_notarized_contract'], 'notarizedcontract', $ApplicationEntity->id, 'notarized_contract', 'notarized_contract');
					$this->WindApplicationDeveloperPermission->updateAll(
						array("notarized_contract" => $file_name),
						array("id" => $ApplicationEntity->id)
					);
				}

				//CA Certificate y
				if (isset($this->request->data['a_ca_certificate']['tmp_name']) && !empty($this->request->data['a_ca_certificate']['tmp_name'])) {
					$file_name 	= $this->wind_imgfile_upload($this->request->data['a_ca_certificate'], 'cacerti', $ApplicationEntity->id, 'ca_certificate', 'ca_certificate');
					$this->WindApplicationDeveloperPermission->updateAll(
						array("ca_certificate" => $file_name),
						array("id" => $ApplicationEntity->id)
					);
				}

				//Invoice With GST y
				if (isset($this->request->data['a_invoice_with_gst']['tmp_name']) && !empty($this->request->data['a_invoice_with_gst']['tmp_name'])) {
					$file_name 	= $this->wind_imgfile_upload($this->request->data['a_invoice_with_gst'], 'invoice', $ApplicationEntity->id, 'invoice_with_gst', 'invoice_with_gst');
					$this->WindApplicationDeveloperPermission->updateAll(
						array("invoice_with_gst" => $file_name),
						array("id" => $ApplicationEntity->id)
					);
				}

				//Share Subscription y
				if (isset($this->request->data['a_share_subscription']['tmp_name']) && !empty($this->request->data['a_share_subscription']['tmp_name'])) {
					$file_name 	= $this->wind_imgfile_upload($this->request->data['a_share_subscription'], 'sharesubscription', $ApplicationEntity->id, 'share_subscription', 'share_subscription');
					$this->WindApplicationDeveloperPermission->updateAll(
						array("share_subscription" => $file_name),
						array("id" => $ApplicationEntity->id)
					);
				}

				//Private Land y
				if (isset($this->request->data['a_pvt_proposed_land']['tmp_name']) && !empty($this->request->data['a_pvt_proposed_land']['tmp_name'])) {
					$file_name 	= $this->wind_imgfile_upload($this->request->data['a_pvt_proposed_land'], 'privateland', $ApplicationEntity->id, 'pvt_proposed_land', 'pvt_proposed_land');
					$this->WindApplicationDeveloperPermission->updateAll(
						array("pvt_proposed_land" => $file_name),
						array("id" => $ApplicationEntity->id)
					);
				}


				//Project Sale To Discom No Due Certificate y
				if (isset($this->request->data['a_proj_sale_to_discom_no_due']['tmp_name']) && !empty($this->request->data['a_proj_sale_to_discom_no_due']['tmp_name'])) {
					$file_name 	= $this->wind_imgfile_upload($this->request->data['a_proj_sale_to_discom_no_due'], 'saletodiscomnodue', $ApplicationEntity->id, 'proj_sale_to_discom_no_due', 'proj_sale_to_discom_no_due');
					$this->WindApplicationDeveloperPermission->updateAll(
						array("proj_sale_to_discom_no_due" => $file_name),
						array("id" => $ApplicationEntity->id)
					);
				}


				//Project Captive Use No Due Certificate y
				if (isset($this->request->data['a_proj_captive_use_no_due']['tmp_name']) && !empty($this->request->data['a_proj_captive_use_no_due']['tmp_name'])) {
					$file_name 	= $this->wind_imgfile_upload($this->request->data['a_proj_captive_use_no_due'], 'captiveusenodue', $ApplicationEntity->id, 'proj_captive_use_no_due', 'proj_captive_use_no_due');
					$this->WindApplicationDeveloperPermission->updateAll(
						array("proj_captive_use_no_due" => $file_name),
						array("id" => $ApplicationEntity->id)
					);
				}


				$this->WindApplicationDeveloperPermission->save($ApplicationEntity);
				$this->Flash->success("Application $saveText successfully!!!!.");
				return json_encode(array('success' => '1', 'response_errors' => '', 'app_dev_id' => $dev_app_id));
			}
		}
	}

	/* 
		Remove Modules
	*/
	public function remove_hybrid_modules()
	{

		$id 				= (isset($this->request->data['id']) ? $this->request->data['id'] : 0);
		$this->autoRender 	= false;

		if (empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message', $ErrorMessage);
			$this->ApiToken->SetAPIResponse('success', $success);
		} else {

			$this->HybridAdditionalData->deleteAll(['id' => $id, 'capacity_type', $this->request->data['capacity_type']]);
			$success 		= 1;
			$this->ApiToken->SetAPIResponse('success', $success);
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	private function wind_fees_structure($request_data)
	{

		if (!empty($this->Session->read('Members.member_type')))
			$customerId 				= $this->Session->read("Members.id");
		else
			$customerId 				= $this->Session->read("Customers.id");

		$app_id = !empty($request_data['Applications']['application_id']) ? decode($request_data['Applications']['application_id']) : 0;
		$dev_app_id = !empty($request_data['app_dev_id']) ? decode($request_data['app_dev_id']) : 0;

		$applicaiton_exist = 0;
		if (!empty($app_id) && !empty($dev_app_id)) {

			$applicaiton_exist 	= $this->WindApplicationDeveloperPermission->find('all', array('conditions' => array('application_id' => $app_id, 'id' => $dev_app_id)))->first();
			$this->WindApplicationDeveloperPermission->dataPass = $request_data;
		}

		$ApplicationEntity 					= $this->WindApplicationDeveloperPermission->patchEntity($applicaiton_exist, $request_data);
		$saveText							= 'updated';
		$ApplicationEntity->modified 		= $this->NOW();
		$ApplicationEntity->modified_by 	= $customerId;


		if (!empty($applicaiton_exist) && isset($applicaiton_exist)) {

			$applicationCategory = $this->ApplicationCategory->find('all', array('conditions' => array('id' => $applicaiton_exist->application_type)))->first();

			$capacitySum = 0;
			$provisionalFees = 0;

			if ($applicaiton_exist->application_type == 3) {

				$capacitySum = $this->WindWtgDetail->getWtgSum($applicaiton_exist->id) ?? 0;
			} elseif ($applicaiton_exist->application_type == 4) {

				$additionalDataSum = $this->HybridAdditionalData->getHybridDataSum($applicaiton_exist->id, '2');
				$invTotCapacity = $additionalDataSum['mod_inv_total_capacity'] ?? 0;
				$capacityWtgSum = $this->WindWtgDetail->getWtgSum($applicaiton_exist->id) ?? 0;
				$capacitySum = $capacityWtgSum + $invTotCapacity;
			}

			if ($capacitySum > 0) {

				if ($capacitySum <= 25) {

					$application_fee = isset($applicationCategory->dev_per_upto_25_mw) ? $applicationCategory->dev_per_upto_25_mw : 0;
				} elseif ($capacitySum > 25 && $capacitySum <= 50) {

					$application_fee = isset($applicationCategory->dev_per_above_25_to_50_mw) ? $applicationCategory->dev_per_above_25_to_50_mw : 0;
				} elseif ($capacitySum > 50 && $capacitySum <= 75) {

					$application_fee = isset($applicationCategory->dev_per_above_50_to_75_mw) ? $applicationCategory->dev_per_above_50_to_75_mw : 0;
				} elseif ($capacitySum > 75 && $capacitySum <= 100) {

					$application_fee = isset($applicationCategory->dev_per_above_75_to_100_mw) ? $applicationCategory->dev_per_above_75_to_100_mw : 0;
				} elseif ($capacitySum > 100) {

					$application_fee = isset($applicationCategory->dev_per_above_100_mw) ? $applicationCategory->dev_per_above_100_mw : 0;
				} else {
					$application_fee = 0;
				}

				$checkProvisionalFees = $this->WindApplicationDeveloperPermission->find('all', array('conditions' => array('application_id' => $applicaiton_exist->application_id, 'payment_status' => 1)))->first();
				if (isset($checkProvisionalFees) && !empty($checkProvisionalFees)) {
					$provisionalFees = 0;
				} else {

					$application = $this->Applications->find('all', array('conditions' => array('id' => $applicaiton_exist->application_id)))->first();
					$provisional_fees = isset($application) ? $application->application_fee : 0;
				}
			}


			$application_tax_percentage = 	isset($applicationCategory->dev_per_tax_per) ? $applicationCategory->dev_per_tax_per : 0;
			$provisional_total_fee		=	$provisional_fees;
			$application_total_fee		= 	$application_fee - $provisional_fees;
			$gst_fees 					= 	($application_total_fee * $application_tax_percentage) / 100;
			$payable_total_fee			=	$application_total_fee + $gst_fees;

			$tds_deduction = 0;
			if ($request_data['liable_tds'] == 1 && $request_data['terms_agree'] == 1) {
				$application_tds_percentage = isset($applicationCategory->application_tds_percentage) ? $applicationCategory->application_tds_percentage : 0;
				$tds_deduction 								= 	($application_total_fee * $application_tds_percentage) / 100;
				$ApplicationEntity->tds_deduction 			= 	$tds_deduction;
				$payable_total_fee							=	$payable_total_fee - $tds_deduction;
			} else {
				$ApplicationEntity->tds_deduction 			= 0;
				$ApplicationEntity->liable_tds 				= 0;
				$ApplicationEntity->terms_agree 			= 0;
			}

			$ApplicationEntity->application_fee 		= $application_fee;
			$ApplicationEntity->gst_fees 				= $gst_fees;
			$ApplicationEntity->application_total_fee 	= $application_total_fee;
			$ApplicationEntity->provisional_total_fee 	= $provisional_total_fee;
			$ApplicationEntity->payable_total_fee 		= $payable_total_fee;
		}


		if (!empty($ApplicationEntity->errors())) {
			return json_encode(array('success' => '0', 'response_errors' => $ApplicationEntity->errors()));
		} else {

			$this->WindApplicationDeveloperPermission->save($ApplicationEntity);
			$this->Flash->success("Application $saveText successfully.");
			return json_encode(array('success' => '1', 'response_errors' => ''));
		}
	}

	private function wind_share($request_data)
	{
	}

	public function remove_energy()
	{
		$id 				= (isset($this->request->data['id']) ? $this->request->data['id'] : 0);
		$this->autoRender 	= false;

		if (empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message', $ErrorMessage);
			$this->ApiToken->SetAPIResponse('success', $success);
		} else {

			$this->WindEnergyAdditionalData->deleteAll(['id' => $id]);
			$success 		= 1;
			$this->ApiToken->SetAPIResponse('success', $success);
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/* Remove Evaculation Pooling Data
	*/
	public function remove_pooling_sub()
	{
		$id 				= (isset($this->request->data['id']) ? $this->request->data['id'] : 0);
		$this->autoRender 	= false;

		if (empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message', $ErrorMessage);
			$this->ApiToken->SetAPIResponse('success', $success);
		} else {

			$this->WindEvaculationPoolingData->deleteAll(['id' => $id]);
			$success 		= 1;
			$this->ApiToken->SetAPIResponse('success', $success);
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/* Remove Evaculation Getco Data
	*/
	public function remove_getco_sub()
	{
		$id 				= (isset($this->request->data['id']) ? $this->request->data['id'] : 0);
		$this->autoRender 	= false;

		if (empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message', $ErrorMessage);
			$this->ApiToken->SetAPIResponse('success', $success);
		} else {

			$this->WindEvaculationGetcoData->deleteAll(['id' => $id]);
			$success 		= 1;
			$this->ApiToken->SetAPIResponse('success', $success);
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/* Remove Land Data
	*/
	public function remove_hybrid_land()
	{
		$id 				= (isset($this->request->data['id']) ? $this->request->data['id'] : 0);
		$this->autoRender 	= false;

		if (empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message', $ErrorMessage);
			$this->ApiToken->SetAPIResponse('success', $success);
		} else {
			$landExists = $this->WindLandDetails->find('all', [
				'conditions' => ['id' => $id]
			])->first();

			if (isset($landExists) && !empty($landExists)) {
				$path = APPLICATIONS_DEVELOPER_PERMISSION_PATH . 'wind/' . $landExists->app_dev_per_id . '/';
				if (!empty($landExists->deed_doc) && file_exists($path . $landExists->deed_doc)) {
					@unlink($path . $landExists->deed_doc);
				}
			}
			$this->WindLandDetails->deleteAll(['id' => $id]);
			$success 		= 1;
			$this->ApiToken->SetAPIResponse('success', $success);
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	public function wind_view($id)
	{

		if (!empty($this->Session->read('Members.member_type'))) {
			$customerId = $this->Session->read("Members.id");
		} else {
			$customerId = $this->Session->read("Customers.id");
		}
		if (empty($customerId)) {
			return $this->redirect('/home');
		}

		$applicationId 			= decode($id);
		$ApplicationData 		= $this->WindApplicationDeveloperPermission->viewDetailApplication($applicationId);

		$EndCTU 				= $this->ApiToken->arrEndCTU;
		$type_of_applicant 		= $this->ApiToken->arrFirmDropdown;
		$gridLevel 				= $this->ApiToken->arrGridLevel;
		$injectionLevel 		= $this->ApiToken->arrInjectionLevel;

		$district 				= $this->DistrictMaster->find("list", ['keyField' => 'id', 'valueField' => 'name', 'conditions' => ['state_id' => 4]])->toArray();
		$taluka					= $this->TalukaMaster->find("list", ['keyField' => 'id', 'valueField' => 'name'])->toArray();
		$landData 				= $this->WindLandDetails->fetchdata($applicationId);
		$Roof_Land_Data 		= $this->WindLandDetails->fetchdata($applicationId, 0);
		$totalInverternos		= $this->HybridAdditionalData->getHybridDataSum($applicationId, 2);
		$totalModulenos			= $this->HybridAdditionalData->getHybridDataSum($applicationId, 1);
		$moduleAdditionalData 	= $this->HybridAdditionalData->find('all', array('conditions' => array('app_dev_per_id' => $applicationId, 'capacity_type' => 1), 'order' => array('id' => 'desc')))->toArray();
		$inverteAdditionalData	= $this->HybridAdditionalData->find('all', array('conditions' => array('app_dev_per_id' => $applicationId, 'capacity_type' => 2), 'order' => array('id' => 'desc')))->toArray();


		$discom_arr = array();
		$discoms 	= $this->BranchMasters->find("list", ['keyField' => 'id', 'valueField' => 'title', 'conditions' => ['BranchMasters.status' => '1', 'BranchMasters.parent_id' => '0', 'BranchMasters.state' => $this->ApplyOnlines->gujarat_st_id]])->toArray();
		if (!empty($discoms)) {
			foreach ($discoms as $keyid => $title) {
				$discom_arr[$keyid] = $title;
			}
		}
		$application_geo_loc = [];
		$application_geo_loc = $this->ApplicationGeoLocation->find('all', array(
			'fields'	=> [
				'manufacturer_master.name', 'id', 'x_cordinate', 'y_cordinate', 'wtg_make', 'wtg_model', 'wtg_capacity',
				'wtg_rotor_dimension', 'wtg_hub_height',
			],
			'join'		=> [
				['table' => 'manufacturer_master', 'type' => 'LEFT', 'conditions' => ['wtg_make = manufacturer_master.id']],
				['table' => 'wind_wtg_detail', 'type' => 'INNER', 'conditions' => ['ApplicationGeoLocation.id = wind_wtg_detail.app_geo_loc_id']]
			],
			'conditions' => array('application_id' => $ApplicationData->application_id, 'approved' => 1, "( wind_wtg_detail.app_dev_per_id =" . $applicationId . "  
			OR wind_wtg_detail.app_geo_loc_id IS NULL)")
		));


		$application_geo_loc_land = [];
		$application_geo_loc_land = $this->ApplicationGeoLocation->find('all', array(
			'fields'	=> [
				'manufacturer_master.name', 'district_master.name', 'id', 'x_cordinate', 'y_cordinate', 'geo_village', 'geo_taluka', 'geo_district', 'sub_lease_deed', 'sub_lease_deed', 'land_survey_no',
				'type_of_land', 'wtg_location'
			],
			'join'		=> [
				['table' => 'manufacturer_master', 'type' => 'LEFT', 'conditions' => ['wtg_make = manufacturer_master.id']],
				['table' => 'district_master', 'type' => 'LEFT', 'conditions' => ['geo_district = district_master.id']],
				['table' => 'wind_wtg_detail', 'type' => 'INNER', 'conditions' => ['ApplicationGeoLocation.id = wind_wtg_detail.app_geo_loc_id']]

			],
			'conditions' => array('application_id' => $ApplicationData->application_id, 'approved' => 1, "( wind_wtg_detail.app_dev_per_id =" . $applicationId . "  OR wind_wtg_detail.app_geo_loc_id IS NULL)")
		))->toArray();

		$geoVariable = [];

		$Pooling_Data 	= $this->WindEvaculationPoolingData->fetchdata($applicationId);
		$Getco_Data 	= $this->WindEvaculationGetcoData->fetchdata($applicationId);
		$Energy_Data 	= $this->WindEnergyAdditionalData->find('all', array(
			'fields' => array('app_dev_per_id', 'energy_discom', 'energy_per', 'id'),
			'conditions' => array('app_dev_per_id' => $applicationId)
		))->toArray();

		$cgpFiles = [];
		if (isset($ApplicationData['equity_share']) && $ApplicationData['equity_share'] == 2) {
			$docTypesMap = [
				1 => ['captive_share_register', 'captive_ca_cs_certi', 'captive_balance_sheet', 'captive_annual_audit'],
				2 => ['partnership_deed', 'partnership_share_holding', 'partnership_return_filed'],
				3 => ['limited_share_register', 'limited_share_certi', 'limited_company_secretary_certi'],
				4 => ['association_certified_return_filed', 'association_share_register', 'association_certi_of_ca', 'certi_from_company_secretary'],
				5 => ['cooperative_certi_from_district_registrar', 'cooperative_share_register'],
				6 => ['spv_company_return_file', 'spv_company_certi_of_share_register', 'spv_company_memorandum', 'spv_company_articles_of_associate', 'spv_company_company_secretary'],
				7 => ['cgp_holding_annual_balance_sheet', 'cgp_holding_acc_of_company'],
				8 => ['cgp_annual_balance_sheet', 'cgp_acc_of_company']
			];

			if (array_key_exists($ApplicationData['cgp'], $docTypesMap)) {
				foreach ($docTypesMap[$ApplicationData['cgp']] as $docType) {

					$docExist = $this->DeveloperApplicationsDocs->find('all', [
						'conditions' => ['application_id' => $ApplicationData['application_id'], 'dev_app_id' => $ApplicationData['id'], 'doc_type' => $docType], 'order' => ['created' => 'DESC']
					])->first();

					if ($docExist) {
						$cgpFiles[$docType] = $docExist['file_name'];
					}
				}
			}
		}

		$this->set('ApplicationData', $ApplicationData);
		$this->set('EndCTU', $EndCTU);
		$this->set('gridLevel', $gridLevel);
		$this->set('injectionLevel', $injectionLevel);
		$this->set("arrDistictData", $district);
		$this->set("arrTalukaData", $taluka);
		$this->set('Wind_Pooling_Data', $Pooling_Data);
		$this->set('Wind_Getco_Data', $Getco_Data);
		$this->set('type_of_applicant', $type_of_applicant);
		$this->set('end_use_of_power', $this->WindApplicationDeveloperPermission->end_use_of_power);
		$this->set('landCategory', $this->OpenAccessApplicationDeveloperPermission->land_category);
		$this->set('deedOfLand', $this->OpenAccessApplicationDeveloperPermission->deed_of_land);
		$this->set('govDeedOfLand', $this->WindApplicationDeveloperPermission->gov_deed_of_land);
		$this->set('privateDeedOfLand', $this->WindApplicationDeveloperPermission->private_deed_of_land);
		$this->set('voltageLevel', $this->OpenAccessApplicationDeveloperPermission->voltage_level);
		$this->set('EndSTU', $this->OpenAccessApplicationDeveloperPermission->end_use_of_electricity);
		$this->set('captive', $this->WindApplicationDeveloperPermission->captive);
		$this->set('third_party', $this->OpenAccessApplicationDeveloperPermission->third_party);
		$this->set('endUseOfElectricity', $this->OpenAccessApplicationDeveloperPermission->end_use_of_electricity);
		$this->set('projectForRpo', $this->OpenAccessApplicationDeveloperPermission->project_for_rpo);
		$this->set('landCategory', $this->OpenAccessApplicationDeveloperPermission->land_category);
		$this->set('deedOfLand', $this->OpenAccessApplicationDeveloperPermission->deed_of_land);
		$this->set('ApplicationGeoLocLand', $application_geo_loc_land);
		$this->set('ApplicationGeoLoc', $application_geo_loc);
		$this->set('lanDetails', $landData);
		$this->set('roofLandData', $Roof_Land_Data);
		$this->set('Couchdb', $this->ReCouchdb);
		$this->set("discom_arr", $discom_arr);
		$this->set("Energy_Data", $Energy_Data);
		$this->set('equityShare', $this->WindApplicationDeveloperPermission->equity_share);
		$this->set('cgp', $this->WindApplicationDeveloperPermission->cgp);
		$this->set('cgpFiles', $cgpFiles);
		$this->set('totalInverternos', $totalInverternos);
		$this->set('totalModulenos', $totalModulenos);
		$this->set('moduleAdditionalData', $moduleAdditionalData);
		$this->set('inverteAdditionalData', $inverteAdditionalData);
		$this->set('typeOfspv', $this->OpenAccessApplicationDeveloperPermission->type_of_spv);
		$this->set('typeOfSolarPanel', $this->OpenAccessApplicationDeveloperPermission->type_of_solar_panel);
		$this->set('typeOfInverterUsed', $this->OpenAccessApplicationDeveloperPermission->type_of_inverter_used);
	}

	/****
	 * Wind Form Download
	 */
	public function get_taluka()
	{

		$id 				= (isset($this->request->data['district_id']) ? $this->request->data['district_id'] : 0);
		$taluka = [];
		if (!empty($id) && isset($id)) {
			$taluka	= $this->TalukaMaster->find("list", ['keyField' => 'id', 'valueField' => 'name', 'conditions' => array('district_id' => $id)])->toArray();
		}
		echo json_encode($taluka);
		exit;
	}
	public function wind_form_pdf($id = null)
	{
		if (!empty($this->Session->read('Members.member_type'))) {
			$customerId = $this->Session->read("Members.id");
		} else {
			$customerId = $this->Session->read("Customers.id");
		}
		if (empty($customerId)) {
			return $this->redirect('/home');
		}

		$application_data = $this->WindApplicationDeveloperPermission->generateWindApplicationPdf($id);

		if (empty($application_data)) {
			$this->Flash->error('Please Select Valid Application.');
			return $this->redirect('home');
		}
	}

	public function hybrid_form_pdf($id = null)
	{
		if (!empty($this->Session->read('Members.member_type'))) {
			$customerId = $this->Session->read("Members.id");
		} else {
			$customerId = $this->Session->read("Customers.id");
		}
		if (empty($customerId)) {
			return $this->redirect('/home');
		}

		$application_data = $this->WindApplicationDeveloperPermission->generateHybridApplicationPdf($id);

		if (empty($application_data)) {
			$this->Flash->error('Please Select Valid Application.');
			return $this->redirect('home');
		}
	}

	public function wind_hybrid_letter($id = null,$app_id=null)
	{
		
		if (!empty($this->Session->read('Members.member_type'))) {
			$customerId = $this->Session->read("Members.id");
		} else {
			$customerId = $this->Session->read("Customers.id");
		}

		if (empty($customerId)) {
			return $this->redirect('/home');
		}

		$application_data = $this->WindApplicationDeveloperPermission->generateWindHybridApplicationLetter($id,$app_id,1);
		
		if (empty($application_data)) {
			$this->Flash->error('Please Select Valid Application.');
			return $this->redirect('home');
		}
	}
	public function create_wind_hybrid_dp_letter()
	{
		$dev_app_id	= (isset($this->request->data['dev_app_id']) ? $this->request->data['dev_app_id'] : 0);
		$application_id	= (isset($this->request->data['application_id']) ? $this->request->data['application_id'] : 0);
		
		$this->autoRender 	= false;
		$response=[];
		$checkExist = $this->WindHybridDpLetter->find('all', array('conditions' => array('application_id' => decode($application_id),'app_dev_per_id'=>decode($dev_app_id))))->first();		
	
		if(!empty($checkExist) && isset($checkExist))
		{			
			$response['html'] = (isset($checkExist)&& !empty($checkExist)) ? $checkExist['content'] : '';
		}else{
			$dp_letter_html = $this->WindApplicationDeveloperPermission->generateWindHybridApplicationLetter($dev_app_id,$application_id);
			$response['html'] = $dp_letter_html;
		}
			
		echo json_encode($response);
		return;
		exit;
		
	}
	public function save_wind_hybrid_dp_letter()
	{
		$this->autoRender 				= false;
		if(!empty($this->Session->read('Members.member_type'))){
			$customerId 				= $this->Session->read("Members.id");
		}
		else{
			$customerId 				= $this->Session->read("Customers.id");
		}
		
		$dev_app_id			= (isset($this->request->data['dp_dev_app_id']) ? decode($this->request->data['dp_dev_app_id']) : 0);
		$application_id 	= (isset($this->request->data['dp_application_id']) ? decode($this->request->data['dp_application_id']) : 0);
		$app_type		 	= (isset($this->request->data['dp_app_type']) ? $this->request->data['dp_app_type'] : 0);
		$content 			= isset($this->request->data['content']) ? $this->request->data['content'] : '';

		$request_data = [
			'app_dev_per_id' 	=> $dev_app_id,
			'application_id' 	=> $application_id,
			'content'			=> $content,
			'app_type'			=> $app_type,
		];
		
		$checkExist = $this->WindHybridDpLetter->find('all', array('conditions' => array('application_id' => $application_id,'app_dev_per_id'=>$dev_app_id)))->first();
		

		if(empty($checkExist) && !isset($checkExist))
		{
			$ApplicationEntity 				= $this->WindHybridDpLetter->newEntity($request_data);
			$ApplicationEntity->created 	= $this->NOW();
			$ApplicationEntity->created_by 	= $customerId;			
		}
		else
		{
			$ApplicationEntity 					= $this->WindHybridDpLetter->patchEntity($checkExist,$request_data);
			$ApplicationEntity->modified 		= $this->NOW();
			$ApplicationEntity->modified_by 	= $customerId;
		}
				
		
		if ($this->WindHybridDpLetter->save($ApplicationEntity)) {
			
			$response = ['status' => 1, 'message' => 'DP Letter Save Successfully.'];
			echo json_encode($response);
			return;
		} else {
			$response = ['status' => 0, 'message' => 'Error While Saving'];
			echo json_encode($response);
			return;
		}

	}

	public function  upload_dp_letter()
	{

		$this->autoRender = false;
		if(!empty($this->Session->read('Members.member_type'))){
			$customerId = $this->Session->read("Members.id");
		}
		else{
			$customerId = $this->Session->read("Customers.id");
		}
		
		$dev_app_id			= (isset($this->request->data['upload_dp_dev_app_id']) ? decode($this->request->data['upload_dp_dev_app_id']) : 0);
		$application_id 	= (isset($this->request->data['upload_dp_application_id']) ? decode($this->request->data['upload_dp_application_id']) : 0);
		$app_type			= (isset($this->request->data['upload_dp_app_type']) ? $this->request->data['upload_dp_app_type'] : 0);
		
		if($app_type == 2){
			
			if (isset($this->request->data['upload_signed_dp_letter']['tmp_name']) && !empty($this->request->data['upload_signed_dp_letter']['tmp_name'])) {
				$file_name 	= $this->open_access_imgfile_upload($this->request->data['upload_signed_dp_letter'], 'finalregletter', $dev_app_id, 'final_registration_letter', 'final_registration_letter');
				$this->OpenAccessApplicationDeveloperPermission->updateAll(
					array("final_registration_letter" => $file_name),
					array("id" => $dev_app_id)
				);
				if (isset($file_name) && !empty($file_name)) {
					$response = ['status' => 1, 'message' => 'Final Registration Letter Uploded Successfully.'];
					echo json_encode($response);
					return;
				} else {
					$response = ['status' => 0, 'message' => 'Error While Saving'];
					echo json_encode($response);
					return;
				}
			}
		}else if($app_type == 3 || $app_type == 4){
			if (isset($this->request->data['upload_signed_dp_letter']['tmp_name']) && !empty($this->request->data['upload_signed_dp_letter']['tmp_name'])) {
				$file_name 	= $this->wind_share_cgp_imgfile_upload($this->request->data['upload_signed_dp_letter'], 'signeddpletter', $application_id, $dev_app_id, 'signed_dp_letter', 'signed_dp_letter');
			}
			if (isset($file_name) && !empty($file_name)) {
				$response = ['status' => 1, 'message' => 'DP Letter Uploded Successfully.'];
				echo json_encode($response);
				return;
			} else {
				$response = ['status' => 0, 'message' => 'Error While Saving'];
				echo json_encode($response);
				return;
			}
		}else{
			$response = ['status' => 0, 'message' => 'Error Wtthile Saving'];
			echo json_encode($response);
			return;
		}	
	}

	public function payment($appId = 0, $devAppId = 0, $appType = 0)
	{

		if (isset($appId) && $appType == 2) {
			$application_exist 	= $this->OpenAccessApplicationDeveloperPermission->find('all', array('conditions' => array('application_id' => decode($appId), 'application_type' => $appType)))->first();
		}
		if (isset($appId) && isset($devAppId) && $appType == 3) {
			$application_exist 	= $this->WindApplicationDeveloperPermission->find('all', array('conditions' => array('application_id' => decode($appId), 'id' => decode($devAppId))))->first();
		}
		if (isset($appId) && isset($devAppId) && $appType == 4) {
			$application_exist 	= $this->WindApplicationDeveloperPermission->find('all', array('conditions' => array('application_id' => decode($appId), 'id' => decode($devAppId))))->first();
		}

		if (PAYMENT_METHOD == 'hdfc' && !empty($application_exist)) {

			require_once(ROOT . DS . 'vendor' . DS . 'hdfc' . DS . 'hdfc.php');

			$objHdfc 					= new Hdfc(Configure::read('HDFC_MERCHANT_KEY'), Configure::read('HDFC_SALT'), Configure::read('HDFC_ACCESS_CODE'), Configure::read('PAYU_SANDBOX'));
			$txnId 						= $objHdfc->randomTxnId();
			$hdfc['order_id'] 			= $txnId;
			$hdfc['redirect_url'] 		= URL_HTTP . 'developer-permission-payment/success';
			$hdfc['cancel_url'] 		= URL_HTTP . 'developer-permission-payment/cancel';
			$hdfc['amount'] 			= isset($application_exist->payable_total_fee) ? $application_exist->payable_total_fee : 0; // Amount
			$hdfc['language'] 			= 'EN';
			$hdfc['currency'] 			= 'INR';
			$hdfc['billing_name'] 		= preg_replace('/[^a-z0-9 ]/i', '', $application_exist->name_of_applicant);
			$hdfc['billing_country'] 	= 'India';
			$hdfc['billing_address'] 	= preg_replace('/[^a-z0-9 ]/i', '', $application_exist->address);
			$hdfc['billing_city'] 		= preg_replace('/[^a-z0-9 ]/i', '', $application_exist->city);
			$hdfc['billing_state'] 		= preg_replace('/[^a-z0-9 ]/i', '', $application_exist->state);
			$hdfc['billing_zip'] 		= $application_exist->pincode;
			$hdfc['billing_tel'] 		= $application_exist->mobile;
			$hdfc['delivery_name'] 		= preg_replace('/[^a-z0-9 ]/i', '', $application_exist->name_of_applicant);
			$hdfc['delivery_country'] 	= 'India';
			$hdfc['delivery_address'] 	= preg_replace('/[^a-z0-9 ]/i', '', $application_exist->address);
			$hdfc['delivery_city'] 		= preg_replace('/[^a-z0-9 ]/i', '', $application_exist->city);
			$hdfc['delivery_state'] 	= preg_replace('/[^a-z0-9 ]/i', '', $application_exist->state);
			$hdfc['delivery_zip'] 		= $application_exist->pincode;
			$hdfc['delivery_tel'] 		= $application_exist->mobile;
			$hdfc['merchant_param1'] 	= encode($application_exist->application_id);
			$hdfc['merchant_param2'] 	= encode($application_exist->id);
			$hdfc['merchant_param3'] 	= $application_exist->application_type;

			$request_data 										= json_encode($hdfc);
			$DeveloperPermissionPaymentRequest 					= TableRegistry::get('DeveloperPermissionPaymentRequest');
			$DeveloperPermissionRequestEntity 					= $DeveloperPermissionPaymentRequest->newEntity();
			$DeveloperPermissionRequestEntity->dev_per_app_id 	= $application_exist->id;
			$DeveloperPermissionRequestEntity->application_type = $application_exist->application_type;
			$DeveloperPermissionRequestEntity->customer_id		= $application_exist->customer_id;
			$DeveloperPermissionRequestEntity->created 			= $this->NOW();
			$DeveloperPermissionRequestEntity->modified 		= $this->NOW();
			$DeveloperPermissionRequestEntity->request_data		= $request_data;
			$DeveloperPermissionRequestEntity->amount 			= $hdfc['amount'];
			$DeveloperPermissionRequestEntity->created_by		= $application_exist->customer_id;
			$DeveloperPermissionRequestEntity->modified_by		= $application_exist->customer_id;

			$DeveloperPermissionPaymentRequest->save($DeveloperPermissionRequestEntity);

			$objHdfc->send($hdfc);
		} else {
		}

		exit;
	}

	/**
	 * success
	 * Behaviour : public
	 * @defination : Method is used to insert and update data after successful payment.
	 */
	public function success()
	{

		if ($this->request->data) {

			if (PAYMENT_METHOD == 'hdfc') {
				require_once(ROOT . DS . 'vendor' . DS . 'hdfc' . DS . 'hdfc.php');
				$objHdfc 			= new Hdfc(Configure::read('HDFC_MERCHANT_KEY'), Configure::read('HDFC_SALT'), Configure::read('HDFC_ACCESS_CODE'), Configure::read('PAYU_SANDBOX'));
				$arr_data = $objHdfc->decrypt($this->request->data['encResp'], Configure::read('HDFC_SALT'));

				$arr_reponse_data 	= explode("&", $arr_data);
				$arr_pass_data 		= array();
				foreach ($arr_reponse_data as $res_d) {
					$arr_mk_data 					= explode("=", $res_d);
					$arr_pass_data[$arr_mk_data[0]] = $arr_mk_data[1];
				}

				$this->request->data['udf1'] = $arr_pass_data['merchant_param1'];
				if (strtolower($arr_pass_data['order_status']) == 'success') {
					$response = $this->DeveloperPermissionPayment->savedata_success($arr_pass_data, 0);
				} else {
					$response = $this->DeveloperPermissionPayment->savedata_failure($arr_pass_data, 0);
					$this->Flash->error('Payment failed.');
					return $this->redirect(URL_HTTP . 'applications-list');
				}
			} else {
				$response = $this->DeveloperPermissionPayment->savedata_success($this->request->data, 0);
			}
			if ($response == 1) {
				$this->Flash->success('Payment done successfully.');
				return $this->redirect(URL_HTTP . 'applications-list');
			} else {
				$this->Flash->success('Payment done successfully.');
				return $this->redirect(URL_HTTP . '');
			}
		}
		exit;
	}

	/**
	 * failure
	 * Behaviour : public
	 * @defination : Method is used to insert and update data in case of payment fail.
	 */
	public function failure()
	{
		if ($this->request->data) {
			$response 		= $this->DeveloperPermissionPayment->savedata_failure($this->request->data, 0);
			$Error_Message 	= "Error while payment process. Please try again.";
			if ($response) {
				if (isset($this->request->data['error_Message'])) {
					$Error_Message = $this->request->data['error'] . ":" . $this->request->data['error_Message'];
				}
				$this->Flash->error($Error_Message);
				return $this->redirect(URL_HTTP . 'applications-list');
			} else {
				$this->Flash->error($Error_Message);
				return $this->redirect(URL_HTTP . 'applications-list');
			}
		}
		exit;
	}

	public function cancel()
	{
		if ($this->request->data) {
			$payuTable = TableRegistry::get('DeveloperPermissionPayment');
			if (PAYMENT_METHOD == 'hdfc') {
				require_once(ROOT . DS . 'vendor' . DS . 'hdfc' . DS . 'hdfc.php');
				$objHdfc 			= new Hdfc(Configure::read('HDFC_MERCHANT_KEY'), Configure::read('HDFC_SALT'), Configure::read('HDFC_ACCESS_CODE'), Configure::read('PAYU_SANDBOX'));
				$arr_data = $objHdfc->decrypt($this->request->data['encResp'], Configure::read('HDFC_SALT'));

				$arr_reponse_data 	= explode("&", $arr_data);
				$arr_pass_data 		= array();
				foreach ($arr_reponse_data as $res_d) {
					$arr_mk_data 					= explode("=", $res_d);
					$arr_pass_data[$arr_mk_data[0]] = $arr_mk_data[1];
				}
				$payusave = $payuTable->find('all')->where(['payment_id' => $arr_pass_data['order_id']])->first();
			} else {
				$payusave = $payuTable->find('all')->where(['payment_id' => $this->request->data['mihpayid']])->first();
			}


			if (empty($payusave)) {
				$payusave = $payuTable->newEntity();
			}
			if (PAYMENT_METHOD == 'hdfc') {
				$payusave->payment_id 		= $arr_pass_data['order_id'];
				$payusave->payment_status 	= strtolower($arr_pass_data['order_status']);
				$this->request->data['udf1'] = $arr_pass_data['merchant_param1'];
				$this->request->data['udf2'] = $arr_pass_data['merchant_param2'];
				$this->request->data['udf3'] = $arr_pass_data['merchant_param3'];
				$payusave->payment_data 	= json_encode($arr_pass_data);
			} else {
				$payusave->payment_id 		= $this->request->data['mihpayid'];
				$payusave->payment_status 	= $this->request->data['status'];
				$payusave->payment_data 	= json_encode($this->request->data);
			}
			if ($payuTable->save($payusave)) {
				$DeveloperPermissionPaymentRequest 		= TableRegistry::get('DeveloperPermissionPaymentRequest');
				$arrPayment = $DeveloperPermissionPaymentRequest->find('all', array('conditions' =>
				array(
					'dev_per_app_id' => decode($this->request->data['udf2']),
					'application_type' => $this->request->data['udf3'],
					'response_data IS NULL'
				), 'order' => array('id' => 'desc')))->first();

				if (!empty($arrPayment)) {
					$arrpay['dev_per_app_id'] 	= decode($this->request->data['udf2']);
					$arrpay['modified'] 		= $this->NOW();
					$arrpay['response_data']	= json_encode($arr_pass_data);
					$DeveloperPermissionPaymentRequest->updateAll($arrpay, array('id' => $arrPayment->id));
				}
				if (isset($this->request->data['udf1'])) {
					return $this->redirect(URL_HTTP . 'applications-list');
				} else {
					$this->redirect(URL_HTTP . 'applications-list');
				}
			}
		}
		exit;
	}

	/**
	 * generateDeveloperReceiptPdf
	 * Behaviour : public
	 * @param : id  : Id is use to identify for which installer letter file should be downlaoded, $isdownload=true
	 * @defination : Method is use to download .pdf file from applyonline listing
	 *
	 */
	public function generateApplicationDeveloperPermissionReceiptPdf($dev_per_app_id, $app_type)
	{
		if (isset($dev_per_app_id) && isset($app_type)) {

			$this->OpenAccessApplicationDeveloperPermission->generateDeveloperPermissionReceiptPdf($dev_per_app_id, $app_type, true, false);
		}
	}
}
