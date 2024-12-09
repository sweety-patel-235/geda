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
use Cake\View\View;
use Dompdf\Dompdf;

/**
 * Short description for file
 * This Model use for installer . It extends Table Class
 * @category  Class File
 * @Desc      Manage installer information
 * @author    Khushal Bhalsod
 * @version   Solar 1.0
 * @since     File available since Solar 1.0
 */

class OpenAccessApplicationDeveloperPermissionTable extends AppTable
{
	var $table  = 'open_access_application_developer_permission';


	var $type_of_spp = [
		'1' =>  'Ground Mounted',
		'2' =>  'Floating',
		'3' =>  'Canal-based'
	];

	var $type_of_mounting_system = [
		'1' =>  'Dual-axis tracking',
		'2' =>  'Single-axis tracking',
		'3' =>  'Seasonal Tilt',
		'4' =>  'Fixed Tilt'
	];

	var $type_of_spv = [
		'1' =>  'Mono-PERC',
		'2' =>  'Poly-PERC',
		'3' =>  'Polycrystalline',
		'4' =>  'Monocrystalline',
		'5' =>  'Thin-film',
		'6' =>  'Other'
	];

	var $type_of_solar_panel = [
		'1' =>  'Mono-facial',
		'2' =>  'Bi-facial'
	];

	var $type_of_inverter_used = [
		'1' =>  'String',
		'2' =>  'Central'
	];

	var $type_of_consumer = [
		'1' =>  'Goverment',
		'2' =>  'Industrial',
		'3' =>  'Commercial',
		'4' =>  'MSME Mfg. Enterprise',
		'5' =>  'Others'
	];

	var $type_of_MSME = [
		'1' =>  'Micro',
		'2' =>  'Small',
		'3' =>  'Medium',
		'4' =>  'Not Applicable'
	];

	var $end_use_of_electricity = [
		'1' =>  'Sale to DISCOM',
		'2' =>  'Captive Use',
		'3' =>  'Sale to Third Party',
	];

	var $captive = [
		'1' =>  'RPO Compliance',
		'2' =>  'REC Mechanism',
		'0'	=>  'Not Applicable'
	];
	var $third_party = [
		'1' =>  'Third Party Solar Project with RPO Compliance',
		'2' =>  'Sale to Third Party with REC Mechanism',
		'0'	=>  'Not Applicable'
	];

	var $project_for_rpo = [
		'1'	=>	'Captive',
		'2'	=>	'Third Party Sale'
	];

	var $land_category = [
		'1'	=> 'Private',
		'2'	=>	'Goverment'
	];

	var $deed_of_land = [
		'1'	=> 'Registered Sale Deed',
		'2'	=> 'Registered Lease Deed',
		'3'	=> 'Registered Sub Lease Deed',
		'4'	=>	'7/12 & 8A',
		'5'	=> 	'Index-2',
		'6'	=>	'Other Legal Doc.'
	];

	var $voltage_level = [
		'1' =>	'33/66',
		'2' =>	'33/132',
		'3' =>	'33/220',
		'4' =>	'33/400',
	];

	var $getco_voltage_level = [
		'1'	=>  'Below 11 kV',
		'2' =>	'11 kV',
		'3' =>	'Below 66 kV',
		'4' =>	'66 kV',
		'5' =>	'Above 66 kV',
	];


	public function Capacity($n)
	{

		return false;
	}

	public function getOpenAccessDevPermissionList($appId)
	{

		$DevAppList = [];
		if (isset($appId)) {
			$DevAppList		= $this->find('all', array('conditions' => array('application_id' => decode($appId))))->first();
			return $DevAppList;
		} else {
			return $DevAppList;
		}
	}



	public function validationTab1(Validator $validator)
	{

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
		$validator->notEmpty('type_authority', 'Please select atleast one.');

		if (isset($this->dataPass['type_authority']) && $this->dataPass['type_authority'] == 'Others') {
			$validator->notEmpty('type_authority_others', 'Designation can not be blank.');
		}
		return $validator;
	}

	public function validationTab2(Validator $validator)
	{

		$validator->notEmpty('type_of_spp', 'Please Select SPP');
		$validator->notEmpty('type_of_mounting_system', 'Please Select Type of Mounting System Used');
		$validator->notEmpty('type_of_consumer', 'Please Select Type of Consumer');
		$validator->notEmpty('type_of_msme', 'Please Select Type of MSME');
		$validator->notEmpty('end_use_of_electricity', 'Please Select End use of Electricity');
		if (!isset($this->dataPass) || (isset($this->dataPass) && !empty($this->dataPass['end_use_of_electricity']))) {
			if ($this->dataPass['end_use_of_electricity'] == '2') {
				$validator->notEmpty('captive', 'Please select RE compliance.');
			}
			if ($this->dataPass['end_use_of_electricity'] == '3') {
				$validator->notEmpty('third_party', 'Please select RE compliance.');
			}
		}

		if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->no_due_1))) {
			$validator->notEmpty('a_no_due_1', 'Upload Sale to discom "No Due certificate" required.');
		}

		if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->no_due_2))) {
			$validator->notEmpty('a_no_due_2', 'Upload Sale to discom "No Due certificate" required.');
		}
		if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->upload_proof_of_ownership_1))) {
			$validator->notEmpty('a_upload_proof_of_ownership_1', 'Upload Proof of Ownership required.');
		}

		if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->upload_proof_of_ownership_2))) {
			$validator->notEmpty('a_upload_proof_of_ownership_2', 'Upload Proof of Ownership required.');
		}
		if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->upload_undertaking_newness))) {
			$validator->notEmpty('a_upload_undertaking_newness', 'Upload Undertaking newness required.');
		}
		

		if (!isset($this->dataPass) || (isset($this->dataPass) && !empty($this->dataPass['end_use_of_electricity']))) {
			if ($this->dataPass['end_use_of_electricity'] == '1') {
				if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->upload_sale_to_discom))) {
					$validator->notEmpty('a_upload_sale_to_discom', 'Upload Sale to discom required.');
				}

				
			}

			if (($this->dataPass['end_use_of_electricity'] == '2' || $this->dataPass['end_use_of_electricity'] == '3') && ($this->dataPass['captive'] == '1' || $this->dataPass['third_party'] == '1')) {
				$validator->notEmpty('beneficiary_obligated_entity', 'Please Choose atleast one option');

				if ($this->dataPass['beneficiary_obligated_entity'] == 1 || $this->dataPass['beneficiary_obligated_entity'] == 'yes') {

					if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->doc_of_beneficiary))) {
						$validator->notEmpty('a_doc_of_beneficiary', 'Upload Doc of Beneficiary required.');
					}

					$validator->notEmpty('copy_of_gerc', 'Please Choose atleast one option');
					if ($this->dataPass['copy_of_gerc'] == 1 || $this->dataPass['copy_of_gerc'] == 'yes') {
						if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->doc_of_gerc_license))) {
							$validator->notEmpty('a_doc_of_gerc_license', 'Upload Doc of GERC License is Required.');
						}
					}
					$validator->notEmpty('captive_conv_power_plant', 'Please Choose atleast one option');
					
					if ($this->dataPass['captive_conv_power_plant'] == 1 || $this->dataPass['captive_conv_power_plant'] == 'yes') {
						$validator->notEmpty('capacity_of_cpp', 'Please Choose atleast one option');
						$validator->notEmpty('prev_solar_project', 'Please Choose atleast one option');

						if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->copy_of_conventional_electricity))) {
							$validator->notEmpty('a_copy_of_conventional_electricity', 'Upload Copy of Conventional Electricity required.');
						}
					}
					$validator->notEmpty('certi_of_stoa', 'Please Choose atleast one option');


					$validator->notEmpty('RE_generating_plant', 'Please Choose atleast one option');

					if (!isset($this->dataPass) || (isset($this->dataPass) && !empty($this->dataPass['RE_generating_plant']))) {
						if ($this->dataPass['RE_generating_plant'] == 1 || $this->dataPass['RE_generating_plant'] == 'yes') {
							if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->stamp_of_re_gen_plant))) {
								$validator->notEmpty('a_stamp_of_re_gen_plant', 'Upload Stamp of Re Generate Plant required.');
							}
						}
					}

					if (!isset($this->dataPass) || (isset($this->dataPass) && !empty($this->dataPass['certi_of_stoa']))) {
						if ($this->dataPass['certi_of_stoa'] == 1 || $this->dataPass['certi_of_stoa'] == 'yes') {
							$validator->notEmpty('certi_of_stoa_capacity', 'Please Enter MW of cerificate issued');
						}
					}
				}
			}

			if ($this->dataPass['end_use_of_electricity'] == '3' && ($this->dataPass['third_party'] == '1' || $this->dataPass['third_party'] == '2')) {
				$validator->notEmpty('details_of_third_party', 'Please Choose atleast one option');
				if ($this->dataPass['details_of_third_party'] == 1 || $this->dataPass['details_of_third_party'] == 'yes') {
					$validator->notEmpty('third_party_name', 'Please Enter Third Party Name');
					$validator->notEmpty('third_party_address', 'Please Enter Third Party Address');
					$validator->notEmpty('third_party_consumer_no', 'Please Enter Third Party Consumer No');
					$validator->notEmpty('third_party_contract_demand', 'Please Enter Third Party Contract Demand');
					$validator->notEmpty('third_party_capacity_existing_plant', 'Please Enter Third Party Existing Plant Capacity');

					if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->electricit_bill_of_third_party))) {
						$validator->notEmpty('a_electricit_bill_of_third_party', 'Upload Electricity Bill of Third Party required.');
					}
				}
			}

			if (($this->dataPass['end_use_of_electricity'] == '2' || $this->dataPass['end_use_of_electricity'] == '3') && ($this->dataPass['captive'] == '2' || $this->dataPass['third_party'] == '2')) {
				$validator->notEmpty('phy_copy_of_rec_reg_web', 'Please Choose atleast one option');
				if ($this->dataPass['phy_copy_of_rec_reg_web'] == 1 || $this->dataPass['phy_copy_of_rec_reg_web'] == 'yes') {
					if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->rec_accrediation_cer))) {
						$validator->notEmpty('a_rec_accrediation_cer', 'Upload REC Accrediation Certificate required.');
						$validator->notEmpty('receipt_copy_of_rec_reg_web', 'Please Choose atleast one option');
						$validator->notEmpty('power_eva_arra_per', 'Please Choose atleast one option');
					}
				}
				
			}
		}

		// $validator->notEmpty('sanctioned_load', 'Please Enter Sanctioned Load');
		// $validator->notEmpty('consumer_no', 'Please Enter Consumer No');
		// $validator->notEmpty('existing_solar_plan', 'Please Enter Existing Solar Plan MW');

		$validator->notEmpty('name_of_discome_plant_installed', 'Please Select DisCom where Plant Installed');
		$validator->notEmpty('name_of_discome_power_wheeled', 'Please Select Name of DisCom Power Wheeled');
		$validator->notEmpty('getco_substation_name', 'Please Enter Getco Substation Name');
		$validator->notEmpty('getco_voltage_level', 'Please Select Voltage Level');

		$validator->notEmpty('expected_annual_output', 'Please Enter Expected Annual Output of energy from the proposed project in kWh');
		$validator->notEmpty('proposed_date_of_commm', 'Please Enter date of commissioning');
		$validator->notEmpty('app_project_cost', 'Please Enter Approx. Project Cost');

		$validator->notEmpty('epc_constractor_nm ', 'Please Enter EPC Contractor Name');
		$validator->notEmpty('epc_constractor_add', 'Please Enter EPC Contractor Address');
		$validator->notEmpty('epc_constractor_con_per', 'Please Enter EPC Contractor Contact Person');

		$validator->notEmpty('epc_constractor_email', 'Please Enter EPC Contractor Email')
			->add('epc_constractor_email', 'valid-email', [
				'rule' => 'email',
				'message' => 'Please Enter valid Email.'
			]);

		$validator->notEmpty('epc_constractor_mobile', 'Please Enter EPC Contractor Mobile')
			->add('epc_constractor_mobile', 'custom', [
				'rule' => [$this, 'ValidateMobileNumber'],
				'message' => 'Please Enter valid Mobile no.'
			]);

		
		if (!isset($this->dataPass) || (isset($this->dataPass) && !empty($this->dataPass['pv_capacity_dc']))) {
			$modTotal = 0;
			foreach ($this->dataPass['mod_total_capacity'] as $ky => $dt) {
				if (!empty($dt))
					$modTotal += $dt;
			}
			
			if ($modTotal > $this->dataPass['pv_capacity_dc']) {

				$validator->add('module_hybrid_capacity', 'custom', [
					'rule' => [$this, 'Capacity'],
					'message' => 'DC capacity is not more than provisional dc capacity'
				]);
			}
		}
		if (!isset($this->dataPass) || (isset($this->dataPass) && !empty($this->dataPass['pv_capacity_ac']))) {
			$modTotal = 0;
			foreach ($this->dataPass['inv_total_capacity'] as $ky => $dt) {
				if (!empty($dt))
					$modTotal += $dt;
			}
			if ($modTotal > $this->dataPass['pv_capacity_ac']) {

				$validator->add('inverter_hybrid_capacity', 'custom', [
					'rule' => [$this, 'Capacity'],
					'message' => 'DP Permission is not more than the provisional AC capacity'. $this->dataPass["pv_capacity_ac"].' kW.'
				]);
			}
		}

		return $validator;
	}

	public function validationTab3(Validator $validator)
	{
		
		if (!isset($this->dataPass) || $this->dataPass['documents'] == 0) {
			
			$validator->notEmpty('documents_error', 'Please Select');

			
		}
		//pr($validator); exit;
		return $validator;
	}

	/**
	 * generateDeveloperReceiptPdf
	 * Behaviour : public
	 * @param : id  : Id is use to identify for which installer letter file should be downlaoded, $isdownload=true
	 * @defination : Method is use to download .pdf file from applyonline listing
	 *
	 */
	public function generateDeveloperPermissionReceiptPdf($id, $appType, $isdownload = true, $mailData = false)
	{

		$OpenAccessApplicationDeveloperPermission 	= TableRegistry::get('OpenAccessApplicationDeveloperPermission');
		$WindApplicationDeveloperPermission 		= TableRegistry::get('WindApplicationDeveloperPermission');
		$DeveloperPermissionPayment 				= TableRegistry::get('DeveloperPermissionPayment');
		$DeveloperPermissionSuccessPayment 			= TableRegistry::get('DeveloperPermissionSuccessPayment');
		$ReApplicationPayment 						= TableRegistry::get('ReApplicationPayment');
		$Developers 								= TableRegistry::get('Developers');
		$ApplicationOpenAccessAdditionalData		= TableRegistry::get('ApplicationOpenAccessAdditionalData');
		$WindWtgDetail								= TableRegistry::get('WindWtgDetail');
		$HybridAdditionalData						= TableRegistry::get('HybridAdditionalData');

		if (empty($id)) {
			$this->Flash->error('Please Select Valid Installer.');
			return $this->redirect('home');
		} else {

			$id 					= intval(decode($id));
			if ($appType == 2) {
				$dev_per_app		= $OpenAccessApplicationDeveloperPermission->find('all', array('conditions' => array('id' => $id)))->first();
				$capSum 			= $ApplicationOpenAccessAdditionalData->getOpenAccessDataSum($dev_per_app->id, '2');
				$capacitySum		= $capSum['mod_inv_total_capacity'];
			}
			if ($appType == 3) {
				$dev_per_app		= $WindApplicationDeveloperPermission->find('all', array('conditions' => array('id' => $id)))->first();
				$capacitySum 		= $WindWtgDetail->getWtgSum($dev_per_app->id) ?? 0;
			}
			if ($appType == 4) {
				$dev_per_app		= $WindApplicationDeveloperPermission->find('all', array('conditions' => array('id' => $id)))->first();

				$additionalDataSum 	= $HybridAdditionalData->getHybridDataSum($dev_per_app->id, '2');
				$invTotCapacity 	= $additionalDataSum['mod_inv_total_capacity'] ?? 0;
				$capacityWtgSum 	= $WindWtgDetail->getWtgSum($dev_per_app->id) ?? 0;
				$capacitySum 		= $capacityWtgSum + $invTotCapacity;
		
			}

			$payment_data 			= $DeveloperPermissionSuccessPayment->find('all', array('conditions' => array('dev_per_app_id' => $id, 'application_type' => $appType), 'order' => array('id' => 'desc')))->first();

			$payment_details 		= $DeveloperPermissionPayment->find('all', array('conditions' => array('id' => $payment_data->payment_id)))->first();

			$InstallersData  		= $Developers->find('all', array('conditions' => array('id' => $dev_per_app->installer_id)))->first();

			$provisionalPayment   	= $ReApplicationPayment->find('all', array('conditions' => array('application_id' => $dev_per_app->application_id)))->first();

			if ($appType == 2) {
				$strType = "Open Access";
			} else if ($appType == 3) {
				$strType = "Wind";
			} else if ($appType == 4) {
				$strType = "Hybrid";
			} else {
				$strType = '';
			}

			if (isset($provisionalPayment) && !empty($provisionalPayment)) {
				$provisionalReceipt = $provisionalPayment['receipt_no'] . ' Date: ' . date('d-m-Y', strtotime($provisionalPayment['payment_date']));
			} else {
				$provisionalReceipt = '';
			}
		}

		$view = new View();
		$view->layout 				= false;

		$view->set("pageTitle", "Developer Permission Receipt");
		$view->set('InstallersData', $InstallersData);
		$view->set('payment_data', $payment_data);
		$view->set('payment_details', $payment_details);
		$view->set('DeveloperPermissionData', $dev_per_app);
		$view->set('applicationType', $strType);
		$view->set('provisionalReceipt', $provisionalReceipt);
		$view->set('capacity', isset($capacitySum) ? $capacitySum : 0);

		/* Generate PDF for estimation of project */
		require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
		$dompdf = new Dompdf($options = array());
		$dompdf->set_option("isPhpEnabled", true);
		$view->set('dompdf', $dompdf);
		$dompdf->set_base_path(SITE_ROOT_DIR_PATH . '/pdf/');

		$html = $view->render('/Element/developer_permission_payment_receipt');
		$dompdf->loadHtml($html, 'UTF-8');
		$dompdf->setPaper('A4', 'portrait');

		@$dompdf->render();

		// Output the generated PDF to Browser
		if ($isdownload) {
			$dompdf->stream('paymentreceipt-' . $id);
		}
		$output = $dompdf->output();
		if ($mailData) {

			$pdfPath 	= WWW_ROOT . '/tmp/developerPaymentReceipt-' . $id . '.pdf';
			file_put_contents($pdfPath, $output);
			return $pdfPath;
		} else {
			header("Content-type:application/pdf");
			header("Content-Disposition:inline;filename=" . $id . ".pdf");
			echo $output;
		}
		die;
	}

	public function generateOpenAccessApplicationPdf($id, $isdownload = false)
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

				$ApplicationOpenAccessAdditionalData 	= TableRegistry::get('ApplicationOpenAccessAdditionalData');
				$OpenAccessLandDetails 					= TableRegistry::get('OpenAccessLandDetails');
				$DistrictMaster							= TableRegistry::get('DistrictMaster');
				$BranchMasters 							= TableRegistry::get('BranchMasters');
				$ApplyOnlines 							= TableRegistry::get('ApplyOnlines');
				$Application							= TableRegistry::get('Applications');
				$TalukaMaster							= TableRegistry::get('TalukaMaster');
				$PDFFILENAME 							= getRandomNumber();

				$LETTER_APPLICATION_NO 	= $applicationId;
				$ApplicationData 		= $this->viewDetailApplication($applicationId);
				$EndSTU 				= $this->arrEndSTU;
				$EndCTU 				= $this->arrEndCTU;
				$type_of_applicant 		= $this->arrFirmDropdown;
				$designation 			= $this->arrDesignation;

				$appData = $Application->find('all', array('fields' => array('pv_capacity_ac', 'pv_capacity_dc'), 'conditions' => array('id' => $applicationId)))->first();


				$totalInverternos		= $ApplicationOpenAccessAdditionalData->getOpenAccessDataSum($applicationId, 2);
				$district 				= $DistrictMaster->find("list", ['keyField' => 'id', 'valueField' => 'name', 'conditions' => ['state_id' => 4]])->toArray();
				$arrTalukaData			= $TalukaMaster->find("list", ['keyField' => 'id', 'valueField' => 'name'])->toArray();		
				$totalModulenos			= $ApplicationOpenAccessAdditionalData->getOpenAccessDataSum($applicationId, 1);
				$moduleAdditionalData 	= $ApplicationOpenAccessAdditionalData->find('all', array('conditions' => array('app_dev_per_id' => $applicationId, 'capacity_type' => 1), 'order' => array('id' => 'desc')))->toArray();
				$inverteAdditionalData	= $ApplicationOpenAccessAdditionalData->find('all', array('conditions' => array('app_dev_per_id' => $applicationId, 'capacity_type' => 2), 'order' => array('id' => 'desc')))->toArray();
				$landData 				= $OpenAccessLandDetails->fetchdata($applicationId);

				$discom_arr = array();
				$discoms 	= $BranchMasters->find("list", ['keyField' => 'id', 'valueField' => 'title', 'conditions' => ['BranchMasters.status' => '1', 'BranchMasters.parent_id' => '0', 'BranchMasters.state' => $ApplyOnlines->gujarat_st_id]])->toArray();
				if (!empty($discoms)) {
					foreach ($discoms as $keyid => $title) {
						$discom_arr[$keyid] = $title;
					}
				}

				$view->set('ApplicationData', $ApplicationData);
				$view->set('EndSTU', $EndSTU);
				$view->set('EndCTU', $EndCTU);
				$view->set('totalInverternos', $totalInverternos);
				$view->set('totalModulenos', $totalModulenos);
				$view->set('moduleAdditionalData', $moduleAdditionalData);
				$view->set("arrDistictData", $district);
				$view->set("arrTalukaData", $arrTalukaData);
				$view->set('inverteAdditionalData', $inverteAdditionalData);
				$view->set('type_of_applicant', $type_of_applicant);
				$view->set('designation', $designation);
				$view->set('typeOfSPP', $this->type_of_spp);
				$view->set('typeOfMountingSystem', $this->type_of_mounting_system);
				$view->set('typeOfspv', $this->type_of_spv);
				$view->set('typeOfSolarPanel', $this->type_of_solar_panel);
				$view->set('typeOfInverterUsed', $this->type_of_inverter_used);
				$view->set('typeOfConsumer', $this->type_of_consumer);
				$view->set('typeOfMsme', $this->type_of_MSME);
				$view->set('endUseOfElectricity', $this->end_use_of_electricity);
				$view->set('projectForRpo', $this->project_for_rpo);
				$view->set('landCategory', $this->land_category);
				$view->set('deedOfLand', $this->deed_of_land);
				$view->set('lanDetails', $landData);
				$view->set('discom', $discom_arr);
				$view->set('appData', $appData);


				/* Generate PDF for estimation of project */
				require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
				$dompdf = new Dompdf($options = array());
				$dompdf->set_base_path(SITE_ROOT_DIR_PATH . '/pdf/');
				$dompdf->set_option("isPhpEnabled", true);
				$view->set('dompdf', $dompdf);

				$html = $view->render('/Element/application-developer-permission/download_open_access_application');
				$dompdf->loadHtml($html, 'UTF-8');

				$dompdf->setPaper('A4', 'portrait');
				$dompdf->render();
				if ($isdownload) {

					$dompdf->stream('openaccess-' . $LETTER_APPLICATION_NO);
				}


				$output = $dompdf->output();
				header("Content-type:application/pdf");
				header("Content-Disposition:inline;filename='" . $PDFFILENAME . ".pdf'");
				echo $output;
				die;
			}
		}
	}

	public function generateOpenAccessApplicationLetter($id, $isdownload = false)
	{
		
		if (empty($id)) {
			return 0;
		} else {
			$devAppId 	= decode($id);
			if (empty($devAppId)) {
				return 0;
			} else {
				$view = new View();
				$view->layout 			= false;
				$view->set("pageTitle", "Application");

				$ApplicationOpenAccessAdditionalData 	= TableRegistry::get('ApplicationOpenAccessAdditionalData');
				$OpenAccessLandDetails 					= TableRegistry::get('OpenAccessLandDetails');
				$DistrictMaster							= TableRegistry::get('DistrictMaster');
				$BranchMasters 							= TableRegistry::get('BranchMasters');
				$ApplyOnlines 							= TableRegistry::get('ApplyOnlines');
				$Application							= TableRegistry::get('Applications');
				$TalukaMaster							= TableRegistry::get('TalukaMaster');
				
				$PDFFILENAME 							= getRandomNumber();

				$LETTER_APPLICATION_NO 	= $devAppId;
				$ApplicationData 		= $this->viewDetailApplication($devAppId);
				$EndSTU 				= $this->arrEndSTU;
				$EndCTU 				= $this->arrEndCTU;
				$type_of_applicant 		= $this->arrFirmDropdown;
				$designation 			= $this->arrDesignation;

				$appData = $Application->find('all', array('fields' => array('registration_no'), 'conditions' => array('id' => $ApplicationData['application_id'])))->first();
				

				$district 				= $DistrictMaster->find("list", ['keyField' => 'id', 'valueField' => 'name', 'conditions' => ['state_id' => 4]])->toArray();
				$arrTalukaData			= $TalukaMaster->find("list", ['keyField' => 'id', 'valueField' => 'name'])->toArray();				
				$totalInverternos		= $ApplicationOpenAccessAdditionalData->getOpenAccessDataSum($devAppId, 2);
				$totalModulenos			= $ApplicationOpenAccessAdditionalData->getOpenAccessDataSum($devAppId, 1);
				$moduleAdditionalData 	= $ApplicationOpenAccessAdditionalData->find('all', array('conditions' => array('app_dev_per_id' => $devAppId, 'capacity_type' => 1), 'order' => array('id' => 'desc')))->toArray();
				$inverteAdditionalData	= $ApplicationOpenAccessAdditionalData->find('all', array('conditions' => array('app_dev_per_id' => $devAppId, 'capacity_type' => 2), 'order' => array('id' => 'desc')))->toArray();
				$landData 				= $OpenAccessLandDetails->fetchdata($devAppId);

				$discom_arr = array();
				$discoms 	= $BranchMasters->find("list", ['keyField' => 'id', 'valueField' => 'title', 'conditions' => ['BranchMasters.status' => '1', 'BranchMasters.parent_id' => '0', 'BranchMasters.state' => $ApplyOnlines->gujarat_st_id]])->toArray();
				if (!empty($discoms)) {
					foreach ($discoms as $keyid => $title) {
						$discom_arr[$keyid] = $title;
					}
				}



				$view->set('ApplicationData', $ApplicationData);
				$view->set('EndSTU', $EndSTU);
				$view->set('EndCTU', $EndCTU);
				$view->set('totalInverternos', $totalInverternos);
				$view->set('totalModulenos', $totalModulenos);
				$view->set('moduleAdditionalData', $moduleAdditionalData);
				$view->set('inverteAdditionalData', $inverteAdditionalData);
				$view->set("arrDistictData", $district);
				$view->set("arrTalukaData", $arrTalukaData);
				$view->set('type_of_applicant', $type_of_applicant);
				$view->set('designation', $designation);
				$view->set('typeOfSPP', $this->type_of_spp);
				$view->set('typeOfMountingSystem', $this->type_of_mounting_system); // need
				$view->set('typeOfspv', $this->type_of_spv);
				$view->set('typeOfSolarPanel', $this->type_of_solar_panel);
				$view->set('typeOfInverterUsed', $this->type_of_inverter_used);
				$view->set('typeOfConsumer', $this->type_of_consumer);
				$view->set('typeOfMsme', $this->type_of_MSME);
				$view->set('endUseOfElectricity', $this->end_use_of_electricity);
				$view->set('projectForRpo', $this->project_for_rpo);
				$view->set('landCategory', $this->land_category);
				$view->set('deedOfLand', $this->deed_of_land);
				$view->set('lanDetails', $landData);
				$view->set('discom', $discom_arr);
				$view->set('appData', $appData);


				/* Generate PDF for estimation of project */
				require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
				$dompdf = new Dompdf($options = array());
				$dompdf->set_base_path(SITE_ROOT_DIR_PATH . '/pdf/');
				$dompdf->set_option("isPhpEnabled", true);
				$view->set('dompdf', $dompdf);


				if (isset($ApplicationData['end_use_of_electricity']) && !empty($ApplicationData['end_use_of_electricity'])) {
					if ($ApplicationData['end_use_of_electricity'] == 1) {
						$html = $view->render('/Element/application-developer-permission/download_open_access_sale_to_discom_letter');
					}
					if ($ApplicationData['end_use_of_electricity'] == 2) {
						if ($ApplicationData['captive'] == 0) {
							$html = $view->render('/Element/application-developer-permission/download_open_access_captive_letter');
						}
						if ($ApplicationData['captive'] == 1) {
							$html = $view->render('/Element/application-developer-permission/download_open_access_captive_rpo_letter');
						}
						if ($ApplicationData['captive'] == 2) {
							$html = $view->render('/Element/application-developer-permission/download_open_access_captive_rec_letter');
						}
					}
					if ($ApplicationData['end_use_of_electricity'] == 3) {
						if ($ApplicationData['third_party'] == 0) {
							$html = $view->render('/Element/application-developer-permission/download_open_access_third_party_letter');
						}
						if ($ApplicationData['third_party'] == 1) {
							$html = $view->render('/Element/application-developer-permission/download_open_access_third_party_rpo_letter');
						}
						if ($ApplicationData['third_party'] == 2) {
							$html = $view->render('/Element/application-developer-permission/download_open_access_third_party_rec_letter');
						}
					}
				}

				//$html = $view->render('/Element/application-developer-permission/download_open_access_captive_letter');
				$dompdf->loadHtml($html, 'UTF-8');

				$dompdf->setPaper('A4', 'portrait');
				$dompdf->render();
				if ($isdownload) {

					$dompdf->stream('openaccess-' . $LETTER_APPLICATION_NO);
				}


				$output = $dompdf->output();
				header("Content-type:application/pdf");
				header("Content-Disposition:inline;filename='" . $PDFFILENAME . ".pdf'");
				echo $output;
				die;
			}
		}
	}
	public function viewDetailApplication($application_id)
	{

		$fields             = [
			'applications.registration_no',
			'OpenAccessApplicationDeveloperPermission.id',
			'OpenAccessApplicationDeveloperPermission.application_id',
			'OpenAccessApplicationDeveloperPermission.customer_id',
			'OpenAccessApplicationDeveloperPermission.installer_id',
			'OpenAccessApplicationDeveloperPermission.application_type',
			'OpenAccessApplicationDeveloperPermission.name_of_applicant',
			'OpenAccessApplicationDeveloperPermission.address',
			'OpenAccessApplicationDeveloperPermission.address1',
			'OpenAccessApplicationDeveloperPermission.taluka',
			'OpenAccessApplicationDeveloperPermission.pincode',
			'OpenAccessApplicationDeveloperPermission.city',
			'OpenAccessApplicationDeveloperPermission.state',
			'OpenAccessApplicationDeveloperPermission.district',
			'OpenAccessApplicationDeveloperPermission.district_code',
			'OpenAccessApplicationDeveloperPermission.type_of_applicant',
			'OpenAccessApplicationDeveloperPermission.registration_document',
			'OpenAccessApplicationDeveloperPermission.applicant_others',
			'OpenAccessApplicationDeveloperPermission.contact',
			'OpenAccessApplicationDeveloperPermission.mobile',
			'OpenAccessApplicationDeveloperPermission.email',
			'OpenAccessApplicationDeveloperPermission.pan',
			'OpenAccessApplicationDeveloperPermission.pan_card',
			'OpenAccessApplicationDeveloperPermission.GST',
			'OpenAccessApplicationDeveloperPermission.a_msme',
			'OpenAccessApplicationDeveloperPermission.upload_undertaking',
			'OpenAccessApplicationDeveloperPermission.name_director',
			'OpenAccessApplicationDeveloperPermission.type_director',
			'OpenAccessApplicationDeveloperPermission.type_director_others',
			'OpenAccessApplicationDeveloperPermission.director_whatsapp',
			'OpenAccessApplicationDeveloperPermission.director_mobile',
			'OpenAccessApplicationDeveloperPermission.director_email',
			'OpenAccessApplicationDeveloperPermission.name_authority',
			'OpenAccessApplicationDeveloperPermission.type_authority',
			'OpenAccessApplicationDeveloperPermission.type_authority_others',
			'OpenAccessApplicationDeveloperPermission.d_file_board',
			'OpenAccessApplicationDeveloperPermission.authority_whatsapp',
			'OpenAccessApplicationDeveloperPermission.authority_mobile',
			'OpenAccessApplicationDeveloperPermission.authority_email',
			'OpenAccessApplicationDeveloperPermission.type_of_power_project',
			'OpenAccessApplicationDeveloperPermission.type_of_spp',
			'OpenAccessApplicationDeveloperPermission.type_of_mounting_system',
			'OpenAccessApplicationDeveloperPermission.type_of_consumer',
			'OpenAccessApplicationDeveloperPermission.type_of_msme',
			'OpenAccessApplicationDeveloperPermission.end_use_of_electricity',
			'OpenAccessApplicationDeveloperPermission.captive',
			'OpenAccessApplicationDeveloperPermission.third_party',
			'OpenAccessApplicationDeveloperPermission.upload_sale_to_discom',
			'OpenAccessApplicationDeveloperPermission.no_due_1',
			'OpenAccessApplicationDeveloperPermission.no_due_2',
			'OpenAccessApplicationDeveloperPermission.upload_proof_of_ownership_1',
			'OpenAccessApplicationDeveloperPermission.upload_proof_of_ownership_2',
			'OpenAccessApplicationDeveloperPermission.upload_undertaking_newness',
			'OpenAccessApplicationDeveloperPermission.sanctioned_load',
			'OpenAccessApplicationDeveloperPermission.consumer_no',
			'OpenAccessApplicationDeveloperPermission.existing_solar_plan',
			'OpenAccessApplicationDeveloperPermission.name_of_discome_plant_installed',
			'OpenAccessApplicationDeveloperPermission.name_of_discome_power_wheeled',
			'OpenAccessApplicationDeveloperPermission.getco_substation_name',
			'OpenAccessApplicationDeveloperPermission.expected_annual_output',
			'OpenAccessApplicationDeveloperPermission.proposed_date_of_commm',
			'OpenAccessApplicationDeveloperPermission.app_project_cost',
			'OpenAccessApplicationDeveloperPermission.epc_constractor_nm',
			'OpenAccessApplicationDeveloperPermission.epc_constractor_add',
			'OpenAccessApplicationDeveloperPermission.epc_constractor_con_per',
			'OpenAccessApplicationDeveloperPermission.epc_constractor_email',
			'OpenAccessApplicationDeveloperPermission.epc_constractor_mobile',
			'OpenAccessApplicationDeveloperPermission.beneficiary_obligated_entity',
			'OpenAccessApplicationDeveloperPermission.doc_of_beneficiary',
			'OpenAccessApplicationDeveloperPermission.copy_of_gerc',
			'OpenAccessApplicationDeveloperPermission.doc_of_gerc_license',
			'OpenAccessApplicationDeveloperPermission.captive_conv_power_plant',
			'OpenAccessApplicationDeveloperPermission.capacity_of_cpp',
			'OpenAccessApplicationDeveloperPermission.copy_of_conventional_electricity',
			'OpenAccessApplicationDeveloperPermission.prev_solar_project',
			'OpenAccessApplicationDeveloperPermission.certi_of_stoa',
			'OpenAccessApplicationDeveloperPermission.certi_of_stoa_capacity',
			'OpenAccessApplicationDeveloperPermission.RE_generating_plant',
			'OpenAccessApplicationDeveloperPermission.stamp_of_re_gen_plant',
			'OpenAccessApplicationDeveloperPermission.details_of_third_party',
			'OpenAccessApplicationDeveloperPermission.third_party_name',
			'OpenAccessApplicationDeveloperPermission.third_party_address',
			'OpenAccessApplicationDeveloperPermission.third_party_consumer_no',
			'OpenAccessApplicationDeveloperPermission.third_party_contract_demand',
			'OpenAccessApplicationDeveloperPermission.third_party_capacity_existing_plant',
			'OpenAccessApplicationDeveloperPermission.electricit_bill_of_third_party',
			'OpenAccessApplicationDeveloperPermission.multi_third_party',
			'OpenAccessApplicationDeveloperPermission.phy_copy_of_rec_reg_web',
			'OpenAccessApplicationDeveloperPermission.rec_accrediation_cer',
			'OpenAccessApplicationDeveloperPermission.receipt_copy_of_rec_reg_web',
			'OpenAccessApplicationDeveloperPermission.power_eva_arra_per',
			'OpenAccessApplicationDeveloperPermission.liable_tds',
			'OpenAccessApplicationDeveloperPermission.terms_agree',
			'OpenAccessApplicationDeveloperPermission.application_fee',
			'OpenAccessApplicationDeveloperPermission.provisional_total_fee',
			'OpenAccessApplicationDeveloperPermission.application_total_fee',
			'OpenAccessApplicationDeveloperPermission.gst_fees',
			'OpenAccessApplicationDeveloperPermission.tds_deduction',
			'OpenAccessApplicationDeveloperPermission.payable_total_fee',
			'OpenAccessApplicationDeveloperPermission.payment_status',
			'OpenAccessApplicationDeveloperPermission.created',
			'OpenAccessApplicationDeveloperPermission.created_by',
			'OpenAccessApplicationDeveloperPermission.modified',
			'OpenAccessApplicationDeveloperPermission.modified_by',
			'OpenAccessApplicationDeveloperPermission.inward_date',
			'OpenAccessApplicationDeveloperPermission.final_registration_no',
			'OpenAccessApplicationDeveloperPermission.final_registration_date',
			'application_category.category_name',
			'application_category.route_name',
			'application_category.color_code',
			'developer_customers.name',
			'developers.installer_name',
			'district_master.name'

		];
		$join_arr  		= [
			'application_category'						=> ['table' => 'application_category', 'type' => 'left', 'conditions' => 'OpenAccessApplicationDeveloperPermission.application_type=application_category.id'],
			'district_master'							=> ['table' => 'district_master', 'type' => 'left', 'conditions' => 'OpenAccessApplicationDeveloperPermission.district=district_master.id'],
			'applications' 								=> ['table' => 'applications', 'type' => 'left', 'conditions' => 'applications.id = OpenAccessApplicationDeveloperPermission.application_id'],
			'developer_customers' 						=> ['table' => 'developer_customers', 'type' => 'left', 'conditions' => 'developer_customers.id = OpenAccessApplicationDeveloperPermission.customer_id'],
			'developers' 								=> ['table' => 'developers', 'type' => 'left', 'conditions' => 'developer_customers.installer_id = developers.id'],
			// 'application_open_access_additional_data'	=> ['table'=>'application_open_access_additional_data','type'=>'left','conditions'=>'application_open_access_additional_data.app_dev_per_id = OpenAccessApplicationDeveloperPermission.id']
		];
		$arrApplication = $this->find('all', array(
			'fields' 	=> $fields,
			'join' 		=> $join_arr,
			'conditions' => array('OpenAccessApplicationDeveloperPermission.id' => $application_id)
		))->first();

		return $arrApplication;
	}

	public function regNumber($string,$id)
	{
		$array = explode('/', $string);

		// Get the current financial year
		$currentMonth = date('m');
		$currentYear = date('y');
		if ($currentMonth >= 4) {
			$financialYear = $currentYear . '-' . ($currentYear + 1);
		} else {
			$financialYear = ($currentYear - 1) . '-' . $currentYear;
		}

		$array[1] = 'FR';
		$array[3] = $financialYear;
		$array[4] = $currentMonth;
		$array[7] = $id;
	
		$newString = implode('/', $array);

		return $newString;
	}

	
}
