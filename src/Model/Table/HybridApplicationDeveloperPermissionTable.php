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

class HybridApplicationDeveloperPermissionTable extends AppTable
{
	var $table  = 'wind_application_developer_permission';
	public function Capacity($n)
	{
		return false;
	}
	public function SharePersontage($n)
	{
		return false;
	}
	var $end_use_of_power = [
		'1' =>  'Captive with RPO',
		'2' =>  'Captive with REC',
		'3' =>  'Third Party Sale with RPO',
		'4' =>  'N/A'
	];

	public function getHybridDevPermissionList($appId)
	{
		$DevAppList = [];
		if (isset($appId)) {
			$DevAppList		= $this->find('all', array('fields' => array('id', 'application_id', 'payable_total_fee', 'payment_status', 'app_order', 'application_type'), 'conditions' => array('application_id' => decode($appId))))->toArray();
			return $DevAppList;
		} else {
			return $DevAppList;
		}
	}
	public function geoLocationAvailable($appId)
	{
		$ApplicationGeoLocation 	= TableRegistry::get('ApplicationGeoLocation');
		$application_geo_loc = $ApplicationGeoLocation->find('all', array(
			'fields'	=>	array('ApplicationGeoLocation.id'),
			'join'		=> ['table' => 'wind_wtg_detail', 'type' => 'LEFT', 'conditions' => ['ApplicationGeoLocation.id = wind_wtg_detail.app_geo_loc_id']],
			'conditions' => array('application_id' => decode($appId), 'approved' => 1, "( wind_wtg_detail.app_geo_loc_id IS NULL)")
		))->count();

		$subApp = $this->find('all', array('conditions' => array('application_id' => decode($appId), 'payment_status' => 0)))->count();

		return $application_geo_loc > 0 && $subApp == 0 ? true : false;
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
		if ((isset($this->dataPass) && empty($this->dataPass['geo_loc_ids']))) {
			$validator->notEmpty('geo_loc', 'Select atleast one geograhic area');
		}
		$validator->notEmpty('grid_connectivity', 'Please Select Grid Connectivity');
		$validator->notEmpty('getco_substation', 'Please Enter GETCO Substation Name');
		$validator->notEmpty('injection_level', 'Please Select Power Injection Level');
		$validator->notEmpty('project_energy', 'Please Enter Project Energy');
		$validator->notEmpty('end_use_of_power', 'Please Select End Use of Power');

		$validator->notEmpty('app_trans_to_stu', 'Please Select');

		if (!isset($this->dataPass) || (isset($this->dataPass))) {

			if ($this->dataPass['app_trans_to_stu'] == '0') {
				$validator->notEmpty('sanctioned_load', 'Please Enter Sanctioned Load');
				$validator->notEmpty('consumer_no', 'Please Enter Consumer No.');
				$validator->notEmpty('discom', 'Please Select Discom');
				$validator->notEmpty('voltage_level', 'Please Select Voltage Level');
				$validator->notEmpty('project_estimated_cost', 'Please Enter Project Estimated Cost');
				$validator->notEmpty('wheel_energy_multi_location', 'Please Select');

				if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->copy_of_electricity_bill))) {
					$validator->notEmpty('a_copy_of_electricity_bill', 'Upload Electricity Bill.');
				}
			}
		}

		if (!isset($this->dataPass) || (isset($this->dataPass) && !empty($this->dataPass['grid_connectivity']))) {
			if ($this->dataPass['grid_connectivity'] == '1') {
				$validator->notEmpty('end_stu', 'Please Select State Transmission Utility');

				//New Added
				if (!isset($this->dataPass) || (isset($this->dataPass) && !empty($this->dataPass['end_stu']))) {
					if ($this->dataPass['end_stu'] == '2') {
						$validator->notEmpty('captive', 'Please select RE compliance.');
					}
					if ($this->dataPass['end_stu'] == '3') {
						$validator->notEmpty('third_party', 'Please select RE compliance.');
					}

					//Open access
					if (!isset($this->dataPass) || (isset($this->dataPass) && !empty($this->dataPass['end_stu']))) {
						if ($this->dataPass['end_stu'] == '1') {
							if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->upload_sale_to_discom))) {
								$validator->notEmpty('a_upload_sale_to_discom', 'Upload Sale to discom required.');
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
						}
						if (($this->dataPass['end_stu'] == '2')) {
							if ($this->dataPass['re_equity_re_persontage'] < 51 || $this->dataPass['re_equity_re_persontage'] > 74) {

								$validator->notEmpty('re_equity_re_persontage', 'Enter Share Holder Name')
									->add('re_equity_re_persontage', 'custom', [
										'rule' => [$this, 'SharePersontage'],
										'message' => 'Enter Share Persontage Between 51% to 74%'
									]);
							}

							$validator->notEmpty('re_name_of_share_holder', 'Enter Share Holder Name');
							//$validator->notEmpty('re_equity_re_persontage','Enter Share Persontage');
						}

						if (($this->dataPass['end_stu'] == '2' || $this->dataPass['end_stu'] == '3') && ($this->dataPass['captive'] == '1' || $this->dataPass['third_party'] == '1')) {
							$validator->notEmpty('beneficiary_obligated_entity', 'Please Choose atleast one option');

							if ($this->dataPass['beneficiary_obligated_entity'] == 1 || $this->dataPass['beneficiary_obligated_entity'] == 'yes') {

								if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->doc_of_beneficiary))) {
									$validator->notEmpty('a_doc_of_beneficiary', 'Upload Doc of Beneficiary required.');
								}

								$validator->notEmpty('copy_of_gerc', 'Please Choose atleast one option');
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

						if ($this->dataPass['end_stu'] == '3' && ($this->dataPass['third_party'] == '1' || $this->dataPass['third_party'] == '2')) {
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

						if (($this->dataPass['end_stu'] == '2' || $this->dataPass['end_stu'] == '3') && ($this->dataPass['captive'] == '2' || $this->dataPass['third_party'] == '2')) {
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
					//eND Open access
				}
				//End New Added

			}
			if ($this->dataPass['grid_connectivity'] == '2') {
				$validator->notEmpty('end_ctu', 'Please Select Central Transmission Utility');
			}
		}
		return $validator;
	}

	public function validationTab3(Validator $validator)
	{
		$validator->notEmpty('name_of_pooling_sub', 'Required');
		$validator->notEmpty('loc_of_pooling_sub', 'Required');
		$validator->notEmpty('cap_of_pooling_sub', 'Required');
		$validator->notEmpty('vol_of_pooling_sub', 'Required');
		$validator->notEmpty('sub_mw_of_pooling_sub', 'Required');
		$validator->notEmpty('sub_mva_of_pooling_sub', 'Required');
		$validator->notEmpty('conn_mw_of_pooling_sub', 'Required');
		$validator->notEmpty('conn_mva_of_pooling_sub', 'Required');
		$validator->notEmpty('name_of_getco', 'Required');
		$validator->notEmpty('loc_of_getco', 'Required');
		$validator->notEmpty('cap_of_getco', 'Required');
		$validator->notEmpty('vol_of_getco', 'Required');
		$validator->notEmpty('sub_mw_of_getco', 'Required');
		$validator->notEmpty('sub_mva_of_getco', 'Required');
		$validator->notEmpty('conn_mw_of_getco', 'Required');

		$validator->notEmpty('permission_lett_ref_no', 'Please Enter Permission Letter Reference No.');
		$validator->notEmpty('dt_of_per_validity', 'Please Select Date of Permission Validity');

		if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->permission_letter_of_getco))) {
			$validator->notEmpty('a_permission_letter_of_getco', 'Please upload  Permission Letter of Getco');
		}

		return $validator;
	}

	public function validationTab4(Validator $validator)
	{


		if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->undertaking_dec))) {
			$validator->notEmpty('a_undertaking_dec', 'Required');
		}

		if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->micro_sitting_drawing))) {
			$validator->notEmpty('a_micro_sitting_drawing', 'Required');
		}

		if ($this->dataPass['app_trans_to_stu'] == '0') {
			if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->proof_of_ownership))) {
				$validator->notEmpty('a_proof_of_ownership', 'Required');
			}

			if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->notarized_contract))) {
				$validator->notEmpty('a_notarized_contract', 'Required');
			}
			if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->ca_certificate))) {
				$validator->notEmpty('a_ca_certificate', 'Required');
			}
			if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->invoice_with_gst))) {
				$validator->notEmpty('a_invoice_with_gst', 'Required');
			}

			if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->share_subscription))) {
				$validator->notEmpty('a_share_subscription', 'Required');
			}
		}

		if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->pvt_proposed_land))) {
			$validator->notEmpty('a_pvt_proposed_land', 'Required');
		}
		if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->proj_sale_to_discom_no_due))) {
			$validator->notEmpty('a_proj_sale_to_discom_no_due', 'Required');
		}
		if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->proj_captive_use_no_due))) {
			$validator->notEmpty('a_proj_captive_use_no_due', 'Required');
		}

		return $validator;
	}


	public function viewDetailApplication($application_id)
	{

		$fields             = [
			'applications.registration_no',
			'HybridApplicationDeveloperPermission.id',
			'HybridApplicationDeveloperPermission.application_id',
			'HybridApplicationDeveloperPermission.customer_id',
			'HybridApplicationDeveloperPermission.installer_id',
			'HybridApplicationDeveloperPermission.application_type',
			'HybridApplicationDeveloperPermission.name_of_applicant',
			'HybridApplicationDeveloperPermission.address',
			'HybridApplicationDeveloperPermission.address1',
			'HybridApplicationDeveloperPermission.taluka',
			'HybridApplicationDeveloperPermission.pincode',
			'HybridApplicationDeveloperPermission.city',
			'HybridApplicationDeveloperPermission.state',
			'HybridApplicationDeveloperPermission.district',
			'HybridApplicationDeveloperPermission.district_code',
			'HybridApplicationDeveloperPermission.type_of_applicant',
			'HybridApplicationDeveloperPermission.registration_document',
			'HybridApplicationDeveloperPermission.applicant_others',
			'HybridApplicationDeveloperPermission.contact',
			'HybridApplicationDeveloperPermission.mobile',
			'HybridApplicationDeveloperPermission.email',
			'HybridApplicationDeveloperPermission.pan',
			'HybridApplicationDeveloperPermission.pan_card',
			'HybridApplicationDeveloperPermission.GST',
			'HybridApplicationDeveloperPermission.a_msme',
			'HybridApplicationDeveloperPermission.upload_undertaking',
			'HybridApplicationDeveloperPermission.name_director',
			'HybridApplicationDeveloperPermission.type_director',
			'HybridApplicationDeveloperPermission.type_director_others',
			'HybridApplicationDeveloperPermission.director_whatsapp',
			'HybridApplicationDeveloperPermission.director_mobile',
			'HybridApplicationDeveloperPermission.director_email',
			'HybridApplicationDeveloperPermission.name_authority',
			'HybridApplicationDeveloperPermission.type_authority',
			'HybridApplicationDeveloperPermission.type_authority_others',
			'HybridApplicationDeveloperPermission.d_file_board',
			'HybridApplicationDeveloperPermission.authority_whatsapp',
			'HybridApplicationDeveloperPermission.authority_mobile',
			'HybridApplicationDeveloperPermission.authority_email',
			'HybridApplicationDeveloperPermission.re_name_of_share_holder',
			'HybridApplicationDeveloperPermission.re_equity_re_persontage',
			'HybridApplicationDeveloperPermission.grid_connectivity',
			'HybridApplicationDeveloperPermission.getco_substation',
			'HybridApplicationDeveloperPermission.injection_level',
			'HybridApplicationDeveloperPermission.project_energy',
			'HybridApplicationDeveloperPermission.end_stu',
			'HybridApplicationDeveloperPermission.end_ctu',
			'HybridApplicationDeveloperPermission.captive',
			'HybridApplicationDeveloperPermission.third_party',
			'HybridApplicationDeveloperPermission.upload_sale_to_discom',
			'HybridApplicationDeveloperPermission.no_due_1',
			'HybridApplicationDeveloperPermission.no_due_2',
			'HybridApplicationDeveloperPermission.upload_proof_of_ownership_1',
			'HybridApplicationDeveloperPermission.upload_proof_of_ownership_2',
			'HybridApplicationDeveloperPermission.beneficiary_obligated_entity',
			'HybridApplicationDeveloperPermission.doc_of_beneficiary',
			'HybridApplicationDeveloperPermission.copy_of_gerc',
			'HybridApplicationDeveloperPermission.captive_conv_power_plant',
			'HybridApplicationDeveloperPermission.capacity_of_cpp',
			'HybridApplicationDeveloperPermission.copy_of_conventional_electricity',
			'HybridApplicationDeveloperPermission.prev_solar_project',
			'HybridApplicationDeveloperPermission.certi_of_stoa',
			'HybridApplicationDeveloperPermission.certi_of_stoa_capacity',
			'HybridApplicationDeveloperPermission.RE_generating_plant',
			'HybridApplicationDeveloperPermission.stamp_of_re_gen_plant',
			'HybridApplicationDeveloperPermission.details_of_third_party',
			'HybridApplicationDeveloperPermission.third_party_name',
			'HybridApplicationDeveloperPermission.third_party_address',
			'HybridApplicationDeveloperPermission.third_party_consumer_no',
			'HybridApplicationDeveloperPermission.third_party_contract_demand',
			'HybridApplicationDeveloperPermission.third_party_capacity_existing_plant',
			'HybridApplicationDeveloperPermission.electricity_bill_of_third_party',
			'HybridApplicationDeveloperPermission.multi_third_party',
			'HybridApplicationDeveloperPermission.app_trans_to_stu',
			'HybridApplicationDeveloperPermission.sanctioned_load',
			'HybridApplicationDeveloperPermission.consumer_no',
			'HybridApplicationDeveloperPermission.copy_of_electricity_bill',
			'HybridApplicationDeveloperPermission.discom',
			'HybridApplicationDeveloperPermission.voltage_level',
			'HybridApplicationDeveloperPermission.project_estimated_cost',
			'HybridApplicationDeveloperPermission.wheel_energy_multi_location',
			'HybridApplicationDeveloperPermission.permission_letter_of_getco',
			'HybridApplicationDeveloperPermission.permission_lett_ref_no',
			'HybridApplicationDeveloperPermission.dt_of_per_validity',
			'HybridApplicationDeveloperPermission.undertaking_dec',
			'HybridApplicationDeveloperPermission.micro_sitting_drawing',
			'HybridApplicationDeveloperPermission.proof_of_ownership',
			'HybridApplicationDeveloperPermission.notarized_contract',
			'HybridApplicationDeveloperPermission.ca_certificate',
			'HybridApplicationDeveloperPermission.invoice_with_gst',
			'HybridApplicationDeveloperPermission.share_subscription',
			'HybridApplicationDeveloperPermission.pvt_proposed_land',
			'HybridApplicationDeveloperPermission.proj_sale_to_discom_no_due',
			'HybridApplicationDeveloperPermission.proj_captive_use_no_due',
			'HybridApplicationDeveloperPermission.cover_letter',
			'HybridApplicationDeveloperPermission.liable_tds',
			'HybridApplicationDeveloperPermission.terms_agree',
			'HybridApplicationDeveloperPermission.application_fee',
			'HybridApplicationDeveloperPermission.provisional_total_fee',
			'HybridApplicationDeveloperPermission.application_total_fee',
			'HybridApplicationDeveloperPermission.gst_fees',
			'HybridApplicationDeveloperPermission.tds_deduction',
			'HybridApplicationDeveloperPermission.payable_total_fee',
			'HybridApplicationDeveloperPermission.payment_status',
			'HybridApplicationDeveloperPermission.created',
			'HybridApplicationDeveloperPermission.created_by',
			'HybridApplicationDeveloperPermission.modified',
			'HybridApplicationDeveloperPermission.modified_by',
			'application_category.category_name',
			'application_category.route_name',
			'application_category.color_code',
			'developer_customers.name',
			'developers.installer_name',
			'district_master.name'

		];
		$join_arr  		= [
			'application_category'						=> ['table' => 'application_category', 'type' => 'left', 'conditions' => 'HybridApplicationDeveloperPermission.application_type=application_category.id'],
			'district_master'							=> ['table' => 'district_master', 'type' => 'left', 'conditions' => 'HybridApplicationDeveloperPermission.district=district_master.id'],
			'applications' 								=> ['table' => 'applications', 'type' => 'left', 'conditions' => 'applications.id = HybridApplicationDeveloperPermission.application_id'],
			'developer_customers' 						=> ['table' => 'developer_customers', 'type' => 'left', 'conditions' => 'developer_customers.id = HybridApplicationDeveloperPermission.customer_id'],
			'developers' 								=> ['table' => 'developers', 'type' => 'left', 'conditions' => 'developer_customers.installer_id = developers.id'],
			// 'application_open_access_additional_data'	=> ['table'=>'application_open_access_additional_data','type'=>'left','conditions'=>'application_open_access_additional_data.app_dev_per_id = OpenAccessApplicationDeveloperPermission.id']
		];
		$arrApplication = $this->find('all', array(
			'fields' 	=> $fields,
			'join' 		=> $join_arr,
			'conditions' => array('HybridApplicationDeveloperPermission.id' => $application_id)
		))->first();

		return $arrApplication;
	}

	/**
	 * generateDeveloperReceiptPdf
	 * Behaviour : public
	 * @param : id  : Id is use to identify for which installer letter file should be downlaoded, $isdownload=true
	 * @defination : Method is use to download .pdf file from applyonline listing
	 *
	 */
	public function generateDeveloperPermissionReceiptPdf($id, $isdownload = true, $mailData = false)
	{

		$ApplicationDeveloperPermission 	= TableRegistry::get('ApplicationDeveloperPermission');
		$DeveloperPermissionPayment 		= TableRegistry::get('DeveloperPermissionPayment');
		$DeveloperPermissionSuccessPayment 	= TableRegistry::get('DeveloperPermissionSuccessPayment');
		$Developers 						= TableRegistry::get('Developers');

		if (empty($id)) {
			$this->Flash->error('Please Select Valid Installer.');
			return $this->redirect('home');
		} else {
			$encode_id 					= $id;
			$id 						= intval(decode($id));
			$installer_id 				= $id;


			$dev_per_app				= $ApplicationDeveloperPermission->find('all', array('conditions' => array('application_id' => $id)))->first();

			$payment_data 				= $DeveloperPermissionSuccessPayment->find('all', array('conditions' => array('dev_per_app_id' => $dev_per_app->id), 'order' => array('id' => 'desc')))->first();

			$payment_details 			= $DeveloperPermissionPayment->find('all', array('conditions' => array('id' => $payment_data->payment_id)))->first();

			$InstallersData  			= $Developers->find('all', array('conditions' => array('id' => $dev_per_app->installer_id)))->first();
		}

		$view = new View();
		$view->layout 				= false;

		$view->set("pageTitle", "Developer Permission Receipt");
		$view->set('InstallersData', $InstallersData);
		$view->set('payment_data', $payment_data);
		$view->set('payment_details', $payment_details);
		$view->set('DeveloperPermissionData', $dev_per_app);

		/* Generate PDF for estimation of project */
		require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
		$dompdf = new Dompdf($options = array());
		$dompdf->set_option("isPhpEnabled", true);
		$view->set('dompdf', $dompdf);
		$dompdf->set_base_path(SITE_ROOT_DIR_PATH . '/pdf/');

		$html = $view->render('/Element/developer_permission_payment_receipt');
		$dompdf->loadHtml($html, 'UTF-8');
		$dompdf->setPaper('A4', 'portrait');

		$dompdf->render();

		// Output the generated PDF to Browser
		if ($isdownload) {
			$dompdf->stream('paymentreceipt-' . $installer_id);
		}
		$output = $dompdf->output();
		if ($mailData) {
			$pdfPath 	= WWW_ROOT . '/tmp/developerPaymentReceipt-' . $installer_id . '.pdf';
			file_put_contents($pdfPath, $output);
			return $pdfPath;
		} else {
			header("Content-type:application/pdf");
			header("Content-Disposition:inline;filename='" . $installer_id . ".pdf'");
			echo $output;
		}
		die;
	}
}
