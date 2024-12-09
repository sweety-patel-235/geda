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

class WindApplicationDeveloperPermissionTable extends AppTable
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
	var $equity_share = [
		'1' => 'Captive use with 100% equity',
		'2'	=> 'Captive use as per electricity rule 2005'
	];
	var $cgp = [
		'1' => 'Captive generating plant owned by single consumer',
		'2' => 'Partnership firm/limited Liability Partnership(LLP) with 26% capital holding',
		'3' => 'Limited Liability Partnership Company(LLPC) with 26% equity shares with voting rights',
		'4' => 'Association of Persons (AoP)',
		'5' => 'Cooperative Society',
		'6' => 'SPV/Company',
		'7' => 'CGP set up by Holding Company and consumption of energy from such CGP by Holding Company and/or Subsidiary Company',
		'8' => 'CGP set up by Subsidiary Company and consumption of energy from such CGP by Subsidiary and/or Holding Company'
	];
	var $captive = [
		'1' =>  'Captive with RPO Compliance',
		'2' =>  'Captive with REC Mechanism',
		'0'	=>  'Not Applicable'
	];

	var $gov_deed_of_land = [
		'1'	=> 'FPO Issued By Collector',
		'2'	=> 'Registered Sub Lease Deed'
		
	];
	var $private_deed_of_land = [
		'1'	=> 'Registered Sale Deed',
		'2'	=> 'Registered Lease Deed',
		'3'	=> 'Registered Sub Lease Deed'		
	];

	public function getWindDevPermissionList($appId)
	{
		$DevAppList = [];
		if (isset($appId)) {
			$DevAppList		= $this->find('all', array('fields' => array('id', 'application_id', 'payable_total_fee', 'payment_status', 'app_order', 'application_type','status'), 'conditions' => array('application_id' => decode($appId))))->toArray();
			return $DevAppList;
		} else {
			return $DevAppList;
		}
	}
	public function checkDpLetter($app_id,$dev_app_id)
	{
		$WindHybridDpLetter	= TableRegistry::get('WindHybridDpLetter');	
		$windHybridLetterData = $WindHybridDpLetter->find('all', array('conditions' => array('application_id' => decode($app_id),'app_dev_per_id'=>decode($dev_app_id))))->first();
		
		if(isset($windHybridLetterData) && !empty($windHybridLetterData)){
			return 1;
		}else{
			return 0;
		}
	}
	public function checkUploadedDPLetter($app_id,$dev_app_id)
	{
		$DeveloperApplicationsDocs	= TableRegistry::get('DeveloperApplicationsDocs');	
		$DeveloperApplicationsDocsData = $DeveloperApplicationsDocs->find('all', array('conditions' => array('application_id' => decode($app_id),'dev_app_id'=>decode($dev_app_id),'doc_type'=>'signed_dp_letter')))->first();
		
		if(isset($DeveloperApplicationsDocsData) && !empty($DeveloperApplicationsDocsData)){
			return 1;
		}else{
			return 0;
		}
	}
	public function checkDPTransfer($app_id){
		
		$WindWtgDetail   = TableRegistry::get('WindWtgDetail');
		$WindWtgDetailData = $WindWtgDetail->find('all', array(
				'join' => ['table' => 'wind_application_developer_permission', 'type' => 'LEFT', 'conditions' => ['wind_application_developer_permission.application_id = WindWtgDetail.application_id']],	
				'conditions' => array(
					'WindWtgDetail.application_id' => decode($app_id),
					'transferor'=>1,
					'wind_application_developer_permission.final_registration_no IS NOT' => null
					)
				))->count();
		if($WindWtgDetailData > 0){
			return 1;
		}else{
			return 0;
		}
	}
	public function geoLocationAvailable($appId)
	{
		$ApplicationGeoLocation 	= TableRegistry::get('ApplicationGeoLocation');
		$application_geo_loc = $ApplicationGeoLocation->find('all', array(
			'fields'	=>	array('ApplicationGeoLocation.id'),
			'join'		=> ['table' => 'wind_wtg_detail', 'type' => 'LEFT', 'conditions' => ['ApplicationGeoLocation.id = wind_wtg_detail.app_geo_loc_id']],
			'conditions' => array('ApplicationGeoLocation.application_id' => decode($appId), 'approved' => 1, "( wind_wtg_detail.app_geo_loc_id IS NULL)")
		))->count();

		$subApp = $this->find('all', array('conditions' => array('application_id' => decode($appId), 'payment_status' => 0)))->count();

		return $application_geo_loc > 0 && $subApp == 0 ? true : false;
	}

	public function calWindPvCapacity($appId,$app_dev_id,$geo_loc_ids)
	{
		$ApplicationGeoLocation 	= TableRegistry::get('ApplicationGeoLocation');
		$application_geo_loc 		= $this->find('all', array(
			'fields'	=>	array('WindApplicationDeveloperPermission.id','WindApplicationDeveloperPermission.application_id','app_geo_loc_id'=>'wind_wtg_detail.app_geo_loc_id'),
			'join'		=> ['table' => 'wind_wtg_detail', 'type' => 'LEFT', 'conditions' => ['WindApplicationDeveloperPermission.id = wind_wtg_detail.app_dev_per_id']],
			'conditions' => array('WindApplicationDeveloperPermission.application_id' => $appId, 'wind_wtg_detail.app_dev_per_id !=' =>$app_dev_id)
		))->toArray();
		
		if(isset($application_geo_loc) && !empty($application_geo_loc)){
			foreach($application_geo_loc as $key=>$value){
				array_push($geo_loc_ids,$value['app_geo_loc_id']);				
			}
		}
		$capacitySum = 0;
		if(isset($geo_loc_ids) && !empty($geo_loc_ids)){
			$ApplicationGeoLocationData  = $ApplicationGeoLocation->find('all',array(
				'fields'=>array('total_capacity'=>'SUM(wtg_capacity)'),
				'conditions'=>array('id IN'=>$geo_loc_ids)))->first();
		}
		if(isset($ApplicationGeoLocationData)&&!empty($ApplicationGeoLocationData))
		{
			$capacitySum = $ApplicationGeoLocationData->total_capacity;
		}
		//pr($capacitySum/1000); exit;
		return $capacitySum/1000;		
	}
	
	public function calRoofPvCapacity($appId,$app_dev_id,$inv_total_capacity){
		$HybridAdditionalData 				= TableRegistry::get('HybridAdditionalData');
		$application_additional_data 		= $this->find('all', array(
			'fields'	=>	array('WindApplicationDeveloperPermission.id','WindApplicationDeveloperPermission.application_id','mod_inv_total_capacity'=>'hybrid_additional_data.mod_inv_total_capacity'),
			'join'		=> ['table' => 'hybrid_additional_data', 'type' => 'LEFT', 'conditions' => ['WindApplicationDeveloperPermission.id = hybrid_additional_data.app_dev_per_id']],
			'conditions' => array('application_id' => $appId, 'hybrid_additional_data.app_dev_per_id !=' =>$app_dev_id,'hybrid_additional_data.capacity_type'=>2)
		))->toArray();
		$roofCapacitySum=0;
		if(isset($application_additional_data) && !empty($application_additional_data)){
			foreach($application_additional_data as $app=>$vapp){
				$roofCapacitySum+=$vapp['mod_inv_total_capacity'];
			}
		}
		
		if(isset($inv_total_capacity[0]) && !empty($inv_total_capacity[0])){
			foreach($inv_total_capacity as $iapp=>$ivapp){
				$roofCapacitySum+=$ivapp;
			}
		}
		
		return $roofCapacitySum;
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
			
		}else{
			
			if(isset($this->dataPass) && $this->dataPass['Applications']['application_type']==3)
			{
				
				if (!isset($this->dataPass) || (isset($this->dataPass) && !empty($this->dataPass['total_capacity']) )) {
					$capacity = $this->calWindPvCapacity(decode($this->dataPass['Applications']['application_id']),decode($this->dataPass['app_dev_id']),$this->dataPass['geo_loc_ids']);
					
					if ($capacity > $this->dataPass['total_capacity']) {
		
						$validator->add('total_capacity', 'custom', [
							'rule' => [$this, 'Capacity'],
							'message' => 'DP Permission is not more than the provisional capacity'
						]);
						
					}
				}
			}
			if(isset($this->dataPass) && $this->dataPass['Applications']['application_type']==4)
			{
				if (!isset($this->dataPass) || (isset($this->dataPass) && !empty($this->dataPass['total_wind_hybrid_capacity']) )) {
					$capacity = $this->calWindPvCapacity(decode($this->dataPass['Applications']['application_id']),decode($this->dataPass['app_dev_id']),$this->dataPass['geo_loc_ids']);
					$roofCapacity = $this->calRoofPvCapacity(decode($this->dataPass['Applications']['application_id']),decode($this->dataPass['app_dev_id']),$this->dataPass['inv_total_capacity']);
					$totalCapacity = $capacity + $roofCapacity;
					if ($capacity > $this->dataPass['total_wind_hybrid_capacity']) {
		
						$validator->add('inverter_hybrid_capacity', 'custom', [
							'rule' => [$this, 'Capacity'],
							'message' => 'DP Permission is not more than the provisional capacity'
						]);
						$validator->add('total_capacity', 'custom', [
							'rule' => [$this, 'Capacity'],
							'message' => 'DP Permission is not more than the provisional capacity'
						]);
					}
				}
			}
			
		}

		$validator->notEmpty('grid_connectivity', 'Please Select Grid Connectivity');
		$validator->notEmpty('getco_substation', 'Please Enter GETCO Substation Name');
		//$validator->notEmpty('injection_level', 'Please Select Power Injection Level');
		$validator->notEmpty('project_energy', 'Please Enter Project Energy');
		$validator->notEmpty('end_use_of_power', 'Please Select End Use of Power');

		$validator->notEmpty('app_trans_to_stu', 'Please Select');

		if (!isset($this->dataPass) || (isset($this->dataPass))) {

			// if ($this->dataPass['app_trans_to_stu'] == '0') {				
				$validator->notEmpty('discom', 'Please Select Discom');				
				$validator->notEmpty('project_estimated_cost', 'Please Enter Project Estimated Cost');				
			//}
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
							
							//$validator->notEmpty('re_equity_re_persontage','Enter Share Persontage');

							$validator->notEmpty('equity_share', 'Please Choose atleast one option');

							if ($this->dataPass['equity_share'] == '2') {
								if ($this->dataPass['re_equity_re_persontage'] < 51 || $this->dataPass['re_equity_re_persontage'] > 74) {

									$validator->notEmpty('re_equity_re_persontage', 'Enter Share Holder Name')
										->add('re_equity_re_persontage', 'custom', [
											'rule' => [$this, 'SharePersontage'],
											'message' => 'Enter Share Persontage Between 51% to 74%'
										]);
								}
	
								$validator->notEmpty('re_name_of_share_holder', 'Enter Share Holder Name');
								$validator->notEmpty('cgp', 'Please Choose atleast one option');
								if ($this->dataPass['cgp'] == '1') {
									if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->captive_share_register))) {
										$validator->notEmpty('a_captive_share_register', 'Required');
									}
									if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->captive_ca_cs_certi))) {
										$validator->notEmpty('a_captive_ca_cs_certi', 'Required');
									}
									if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->captive_balance_sheet))) {
										$validator->notEmpty('a_captive_balance_sheet', 'Required');
									}
									if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->captive_annual_audit))) {
										$validator->notEmpty('a_captive_annual_audit', 'Required');
									}
								}
								if ($this->dataPass['cgp'] == '2') {
									if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->partnership_deed))) {
										$validator->notEmpty('a_partnership_deed', 'Required');
									}
									if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->partnership_share_holding))) {
										$validator->notEmpty('a_partnership_share_holding', 'Required');
									}
									if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->partnership_return_filed))) {
										$validator->notEmpty('a_partnership_return_filed', 'Required');
									}
								}
								if ($this->dataPass['cgp'] == '3') {
									if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->limited_share_register))) {
										$validator->notEmpty('a_limited_share_register', 'Required');
									}
									if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->limited_share_certi))) {
										$validator->notEmpty('a_limited_share_certi', 'Required');
									}
									if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->limited_company_secretary_certi))) {
										$validator->notEmpty('a_limited_company_secretary_certi', 'Required');
									}
								}
								if ($this->dataPass['cgp'] == '4') {
									if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->association_certified_return_filed))) {
										$validator->notEmpty('a_association_certified_return_filed', 'Required');
									}
									if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->association_share_register))) {
										$validator->notEmpty('a_association_share_register', 'Required');
									}
									if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->association_certi_of_ca))) {
										$validator->notEmpty('a_association_certi_of_ca', 'Required');
									}
									if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->certi_from_company_secretary))) {
										$validator->notEmpty('a_certi_from_company_secretary', 'Required');
									}
								}
								if ($this->dataPass['cgp'] == '5') {
									if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->cooperative_certi_from_district_registrar))) {
										$validator->notEmpty('a_cooperative_certi_from_district_registrar', 'Required');
									}
									if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->cooperative_share_register))) {
										$validator->notEmpty('a_cooperative_share_register', 'Required');
									}
								}
								if ($this->dataPass['cgp'] == '6') {
									if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->spv_company_return_file))) {
										$validator->notEmpty('a_spv_company_return_file', 'Required');
									}
									if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->spv_company_certi_of_share_register))) {
										$validator->notEmpty('a_spv_company_certi_of_share_register', 'Required');
									}
									if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->spv_company_memorandum))) {
										$validator->notEmpty('a_spv_company_memorandum', 'Required');
									}
									if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->spv_company_articles_of_associate))) {
										$validator->notEmpty('a_spv_company_articles_of_associate', 'Required');
									}
									if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->spv_company_company_secretary))) {
										$validator->notEmpty('a_spv_company_company_secretary', 'Required');
									}
								}
								if ($this->dataPass['cgp'] == '7') {
									if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->cgp_holding_annual_balance_sheet))) {
										$validator->notEmpty('a_cgp_holding_annual_balance_sheet', 'Required');
									}
									if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->cgp_holding_acc_of_company))) {
										$validator->notEmpty('a_cgp_holding_acc_of_company', 'Required');
									}
								}
								if ($this->dataPass['cgp'] == '8') {
									if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->cgp_annual_balance_sheet))) {
										$validator->notEmpty('a_cgp_annual_balance_sheet', 'Required');
									}
									if (!isset($this->dataRecord) || (isset($this->dataRecord) && empty($this->dataRecord->cgp_acc_of_company))) {
										$validator->notEmpty('a_cgp_acc_of_company', 'Required');
									}
								}
							}
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
		/* */

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

	public function generateWindApplicationPdf($id)
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
				
				$BranchMasters 								= TableRegistry::get('BranchMasters');
				$DistrictMaster								= TableRegistry::get('DistrictMaster');
				$TalukaMaster								= TableRegistry::get('TalukaMaster');
				$WindLandDetails							= TableRegistry::get('WindLandDetails');
				$ApplicationGeoLocation						= TableRegistry::get('ApplicationGeoLocation');
				$WindEvaculationPoolingData 				= TableRegistry::get('WindEvaculationPoolingData');
				$WindEvaculationGetcoData					= TableRegistry::get('WindEvaculationGetcoData');
				$WindEnergyAdditionalData					= TableRegistry::get('WindEnergyAdditionalData');
				$ApplyOnlines 								= TableRegistry::get('ApplyOnlines');
				$Application								= TableRegistry::get('Applications');
				$DeveloperApplicationsDocs					= TableRegistry::get('DeveloperApplicationsDocs');
				$OpenAccessApplicationDeveloperPermission 	= TableRegistry::get('OpenAccessApplicationDeveloperPermission');
				$PDFFILENAME 								= getRandomNumber();

				$LETTER_APPLICATION_NO 	= $applicationId;
				$ApplicationData 		= $this->viewDetailApplication($applicationId);

				$EndCTU 				= $this->arrEndCTU;
				$type_of_applicant 		= $this->arrFirmDropdown;
				$gridLevel 				= $this->arrGridLevel;
				$injectionLevel 		= $this->arrInjectionLevel;
				$designation 			= $this->arrDesignation;
				$taluka					= $TalukaMaster->find("list", ['keyField' => 'id', 'valueField' => 'name'])->toArray();
				
				$district 				= $DistrictMaster->find("list", ['keyField' => 'id', 'valueField' => 'name', 'conditions' => ['state_id' => 4]])->toArray();
				$landData 				= $WindLandDetails->fetchdata($applicationId);
				
				$discom_arr = array();
				$discoms 	= $BranchMasters->find("list", ['keyField' => 'id', 'valueField' => 'title', 'conditions' => ['BranchMasters.status' => '1', 'BranchMasters.parent_id' => '0', 'BranchMasters.state' => $ApplyOnlines->gujarat_st_id]])->toArray();
				if (!empty($discoms)) {
					foreach ($discoms as $keyid => $title) {
						$discom_arr[$keyid] = $title;
					}
				}
				$application_geo_loc = [];
				$application_geo_loc = $ApplicationGeoLocation->find('all', array(
					'fields'	=> [
						'manufacturer_master.name', 'id', 'x_cordinate', 'y_cordinate', 'wtg_make', 'wtg_model', 'wtg_capacity',
						'wtg_rotor_dimension', 'wtg_hub_height',
					],
					'join'		=> [
						['table' => 'manufacturer_master', 'type' => 'LEFT', 'conditions' => ['wtg_make = manufacturer_master.id']],
						['table' => 'wind_wtg_detail', 'type' => 'INNER', 'conditions' => ['ApplicationGeoLocation.id = wind_wtg_detail.app_geo_loc_id']]
					],
					'conditions' => array('application_id' => $ApplicationData->application_id, 'approved' => 1, "( wind_wtg_detail.app_dev_per_id =" . $applicationId . " OR wind_wtg_detail.app_geo_loc_id IS NULL)")
				));


				$application_geo_loc_land = [];
				$application_geo_loc_land = $ApplicationGeoLocation->find('all', array(
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

				$Pooling_Data 	= $WindEvaculationPoolingData->fetchdata($applicationId);
				$Getco_Data 	= $WindEvaculationGetcoData->fetchdata($applicationId);
				$Energy_Data 	= $WindEnergyAdditionalData->find('all', array(
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

							$docExist = $DeveloperApplicationsDocs->find('all', [
								'conditions' => ['application_id' => $ApplicationData['application_id'], 'dev_app_id' => $ApplicationData['id'], 'doc_type' => $docType], 'order' => ['created' => 'DESC']
							])->first();

							if ($docExist) {
								$cgpFiles[$docType] = $docExist['file_name'];
							}
						}
					}
				}
				
				$view->set('ApplicationData', $ApplicationData);
				$view->set('designation', $designation);
				$view->set('ApplicationData', $ApplicationData);
				$view->set('EndCTU', $EndCTU);
				$view->set('gridLevel', $gridLevel);
				$view->set('injectionLevel', $injectionLevel);
				$view->set("arrDistictData", $district);
				$view->set("arrTalukaData",$taluka);
				$view->set('Wind_Pooling_Data', $Pooling_Data);
				$view->set('Wind_Getco_Data', $Getco_Data);
				$view->set('type_of_applicant', $type_of_applicant);
				$view->set('end_use_of_power', $this->end_use_of_power);
				$view->set('landCategory', $OpenAccessApplicationDeveloperPermission->land_category);
				$view->set('deedOfLand', $OpenAccessApplicationDeveloperPermission->deed_of_land);
				$view->set('govDeedOfLand', $this->gov_deed_of_land);
				$view->set('privateDeedOfLand', $this->private_deed_of_land);
				$view->set('voltageLevel', $OpenAccessApplicationDeveloperPermission->voltage_level);
				$view->set('EndSTU', $OpenAccessApplicationDeveloperPermission->end_use_of_electricity);
				$view->set('captive', $this->captive);
				$view->set('third_party', $OpenAccessApplicationDeveloperPermission->third_party);
				$view->set('endUseOfElectricity', $OpenAccessApplicationDeveloperPermission->end_use_of_electricity);
				$view->set('projectForRpo', $OpenAccessApplicationDeveloperPermission->project_for_rpo);
				$view->set('landCategory', $OpenAccessApplicationDeveloperPermission->land_category);
				//$view->set('deedOfLand', $OpenAccessApplicationDeveloperPermission->deed_of_land);
				$view->set('ApplicationGeoLocLand', $application_geo_loc_land);
				$view->set('ApplicationGeoLoc', $application_geo_loc);
				$view->set('lanDetails', $landData);
				$view->set("discom_arr", $discom_arr);
				$view->set("Energy_Data", $Energy_Data);
				$view->set('equityShare', $this->equity_share);
				$view->set('cgp', $this->cgp);
				$view->set('cgpFiles', $cgpFiles);


				/* Generate PDF for estimation of project */
				require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
				$dompdf = new Dompdf($options = array());
				$dompdf->set_base_path(SITE_ROOT_DIR_PATH . '/pdf/');
				$dompdf->set_option("isPhpEnabled", true);
				$view->set('dompdf', $dompdf);

				$html = $view->render('/Element/application-developer-permission/download_wind_application');
				$dompdf->loadHtml($html, 'UTF-8');
				
				$dompdf->setPaper('A4', 'portrait');
				$dompdf->render();
				// if ($isdownload) {

				// 	$dompdf->stream('applyonline-' . $LETTER_APPLICATION_NO);
				// }


				$output = $dompdf->output();
				header("Content-type:application/pdf");
				header("Content-Disposition:inline;filename='" . $PDFFILENAME . ".pdf'");
				echo $output;
				die;
			}
		}
	}

	public function generateHybridApplicationPdf($id)
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
				
				$BranchMasters 								= TableRegistry::get('BranchMasters');
				$DistrictMaster								= TableRegistry::get('DistrictMaster');
				$WindLandDetails							= TableRegistry::get('WindLandDetails');
				$TalukaMaster								= TableRegistry::get('TalukaMaster');
				$ApplicationGeoLocation						= TableRegistry::get('ApplicationGeoLocation');
				$WindEvaculationPoolingData 				= TableRegistry::get('WindEvaculationPoolingData');
				$WindEvaculationGetcoData					= TableRegistry::get('WindEvaculationGetcoData');
				$WindEnergyAdditionalData					= TableRegistry::get('WindEnergyAdditionalData');
				$ApplyOnlines 								= TableRegistry::get('ApplyOnlines');
				$Application								= TableRegistry::get('Applications');
				$DeveloperApplicationsDocs					= TableRegistry::get('DeveloperApplicationsDocs');
				$OpenAccessApplicationDeveloperPermission 	= TableRegistry::get('OpenAccessApplicationDeveloperPermission');
				$HybridAdditionalData 						= TableRegistry::get('HybridAdditionalData');				
				$PDFFILENAME 								= getRandomNumber();

				$LETTER_APPLICATION_NO 	= $applicationId;
				$ApplicationData 		= $this->viewDetailApplication($applicationId);

				$EndCTU 				= $this->arrEndCTU;
				$type_of_applicant 		= $this->arrFirmDropdown;
				$gridLevel 				= $this->arrGridLevel;
				$injectionLevel 		= $this->arrInjectionLevel;
				$designation 			= $this->arrDesignation;
				
				$district 				= $DistrictMaster->find("list", ['keyField' => 'id', 'valueField' => 'name', 'conditions' => ['state_id' => 4]])->toArray();
				$taluka					= $TalukaMaster->find("list", ['keyField' => 'id', 'valueField' => 'name'])->toArray();
				$Roof_Land_Data 		= $WindLandDetails->fetchdata($applicationId, 0);
				$landData 				= $WindLandDetails->fetchdata($applicationId);
				
				$discom_arr = array();
				$discoms 	= $BranchMasters->find("list", ['keyField' => 'id', 'valueField' => 'title', 'conditions' => ['BranchMasters.status' => '1', 'BranchMasters.parent_id' => '0', 'BranchMasters.state' => $ApplyOnlines->gujarat_st_id]])->toArray();
				if (!empty($discoms)) {
					foreach ($discoms as $keyid => $title) {
						$discom_arr[$keyid] = $title;
					}
				}
				$application_geo_loc = [];
				$application_geo_loc = $ApplicationGeoLocation->find('all', array(
					'fields'	=> [
						'manufacturer_master.name', 'id', 'x_cordinate', 'y_cordinate', 'wtg_make', 'wtg_model', 'wtg_capacity',
						'wtg_rotor_dimension', 'wtg_hub_height',
					],
					'join'		=> [
						['table' => 'manufacturer_master', 'type' => 'LEFT', 'conditions' => ['wtg_make = manufacturer_master.id']],
						['table' => 'wind_wtg_detail', 'type' => 'INNER', 'conditions' => ['ApplicationGeoLocation.id = wind_wtg_detail.app_geo_loc_id']]
					],
					'conditions' => array('application_id' => $ApplicationData->application_id, 'approved' => 1, "( wind_wtg_detail.app_dev_per_id =" . $applicationId . " OR wind_wtg_detail.app_geo_loc_id IS NULL)")
				));


				$application_geo_loc_land = [];
				$application_geo_loc_land = $ApplicationGeoLocation->find('all', array(
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

				$Pooling_Data 	= $WindEvaculationPoolingData->fetchdata($applicationId);
				$Getco_Data 	= $WindEvaculationGetcoData->fetchdata($applicationId);
				$Energy_Data 	= $WindEnergyAdditionalData->find('all', array(
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

							$docExist = $DeveloperApplicationsDocs->find('all', [
								'conditions' => ['application_id' => $ApplicationData['application_id'], 'dev_app_id' => $ApplicationData['id'], 'doc_type' => $docType], 'order' => ['created' => 'DESC']
							])->first();

							if ($docExist) {
								$cgpFiles[$docType] = $docExist['file_name'];
							}
						}
					}
				}

				$totalInverternos		= $HybridAdditionalData->getHybridDataSum($applicationId, 2);
				$totalModulenos			= $HybridAdditionalData->getHybridDataSum($applicationId, 1);
				$moduleAdditionalData 	= $HybridAdditionalData->find('all', array('conditions' => array('app_dev_per_id' => $applicationId, 'capacity_type' => 1), 'order' => array('id' => 'desc')))->toArray();
				$inverteAdditionalData	= $HybridAdditionalData->find('all', array('conditions' => array('app_dev_per_id' => $applicationId, 'capacity_type' => 2), 'order' => array('id' => 'desc')))->toArray();
				

				$view->set('ApplicationData', $ApplicationData);
				$view->set('designation', $designation);
				$view->set('ApplicationData', $ApplicationData);
				$view->set('EndCTU', $EndCTU);
				$view->set('gridLevel', $gridLevel);
				$view->set('injectionLevel', $injectionLevel);
				$view->set("arrDistictData", $district);
				$view->set("arrTalukaData",$taluka);
				$view->set('Wind_Pooling_Data', $Pooling_Data);
				$view->set('Wind_Getco_Data', $Getco_Data);
				$view->set('type_of_applicant', $type_of_applicant);
				$view->set('end_use_of_power', $this->end_use_of_power);
				$view->set('landCategory', $OpenAccessApplicationDeveloperPermission->land_category);
				$view->set('deedOfLand', $OpenAccessApplicationDeveloperPermission->deed_of_land);
				$view->set('govDeedOfLand', $this->gov_deed_of_land);
				$view->set('privateDeedOfLand', $this->private_deed_of_land);
				$view->set('voltageLevel', $OpenAccessApplicationDeveloperPermission->voltage_level);
				$view->set('EndSTU', $OpenAccessApplicationDeveloperPermission->end_use_of_electricity);
				$view->set('captive', $this->captive);
				$view->set('third_party', $OpenAccessApplicationDeveloperPermission->third_party);
				$view->set('endUseOfElectricity', $OpenAccessApplicationDeveloperPermission->end_use_of_electricity);
				$view->set('projectForRpo', $OpenAccessApplicationDeveloperPermission->project_for_rpo);
				$view->set('landCategory', $OpenAccessApplicationDeveloperPermission->land_category);
				$view->set('deedOfLand', $OpenAccessApplicationDeveloperPermission->deed_of_land);
				$view->set('ApplicationGeoLocLand', $application_geo_loc_land);
				$view->set('ApplicationGeoLoc', $application_geo_loc);
				$view->set('lanDetails', $landData);
				$view->set('roofLandData',$Roof_Land_Data);
				$view->set("discom_arr", $discom_arr);
				$view->set("Energy_Data", $Energy_Data);
				$view->set('equityShare', $this->equity_share);
				$view->set('cgp', $this->cgp);
				$view->set('cgpFiles', $cgpFiles);
				$view->set('totalInverternos', $totalInverternos);
				$view->set('totalModulenos', $totalModulenos);
				$view->set('moduleAdditionalData', $moduleAdditionalData);
				$view->set('inverteAdditionalData', $inverteAdditionalData);
				$view->set('typeOfspv', $OpenAccessApplicationDeveloperPermission->type_of_spv);
				$view->set('typeOfSolarPanel', $OpenAccessApplicationDeveloperPermission->type_of_solar_panel);
				$view->set('typeOfInverterUsed', $OpenAccessApplicationDeveloperPermission->type_of_inverter_used);


				/* Generate PDF for estimation of project */
				require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
				$dompdf = new Dompdf($options = array());
				$dompdf->set_base_path(SITE_ROOT_DIR_PATH . '/pdf/');
				$dompdf->set_option("isPhpEnabled", true);
				$view->set('dompdf', $dompdf);

				$html = $view->render('/Element/application-developer-permission/download_hybrid_application');
				$dompdf->loadHtml($html, 'UTF-8');
				
				$dompdf->setPaper('A4', 'portrait');
				$dompdf->render();
				if ($isdownload) {

					$dompdf->stream('developer-application-' . $LETTER_APPLICATION_NO);
				}


				$output = $dompdf->output();
				header("Content-type:application/pdf");
				header("Content-Disposition:inline;filename='" . $PDFFILENAME . ".pdf'");
				echo $output;
				die;
			}
		}
	}

	public function generateWindHybridApplicationLetter($devAppId,$appId, $isdownload = false)
	{
		if (empty($devAppId)) {
			return 0;
		} else {
			$dev_app_id 	= decode($devAppId);
			$app_id 		= decode($appId);
			
			if (empty($dev_app_id)) {
				return 0;
			} else {
				$view = new View();
				$view->layout 			= false;
				$view->set("pageTitle", "Application");

				// $BranchMasters 								= TableRegistry::get('BranchMasters');
				$DistrictMaster								= TableRegistry::get('DistrictMaster');
				$TalukaMaster								= TableRegistry::get('TalukaMaster');
				$WindLandDetails							= TableRegistry::get('WindLandDetails');
				$ApplicationGeoLocation						= TableRegistry::get('ApplicationGeoLocation');
				$WindEnergyAdditionalData					= TableRegistry::get('WindEnergyAdditionalData');			
				$Application								= TableRegistry::get('Applications');
				$ApplicationStages							= TableRegistry::get('ApplicationStages');	
				$WindEvaculationPoolingData 				= TableRegistry::get('WindEvaculationPoolingData');
				$WindEvaculationGetcoData					= TableRegistry::get('WindEvaculationGetcoData');
				$OpenAccessApplicationDeveloperPermission 	= TableRegistry::get('OpenAccessApplicationDeveloperPermission');	
				$HybridAdditionalData 						= TableRegistry::get('HybridAdditionalData');
				$WindHybridDpLetter							= TableRegistry::get('WindHybridDpLetter');	
				$PDFFILENAME 								= getRandomNumber();

				$LETTER_APPLICATION_NO 	= $dev_app_id;
				$ApplicationData 		= $this->viewDetailApplication($dev_app_id);
				
							
				$appData = $Application->find('all', 
						array('fields' 	=> array('registration_no','submitted_date'=>'application_stages.created'), 
						'join'			=> array('table'=>'application_stages','type'=>'left','conditions'=>'application_stages.application_id = Applications.id'),
						'conditions' 	=> array('Applications.id' => $ApplicationData['application_id'],'application_stages.stage' => 1)))->first();

				
				
				$devAppData = $ApplicationStages->find('all', [
						'fields' => ['application_date'=>'created'],
						'conditions' => ['application_id' => $ApplicationData['application_id'], 'reason' => $ApplicationData['id']]
						])->first();
				
				
				$taluka					= $TalukaMaster->find("list", ['keyField' => 'id', 'valueField' => 'name'])->toArray();
				
				$district 				= $DistrictMaster->find("list", ['keyField' => 'id', 'valueField' => 'name', 'conditions' => ['state_id' => 4]])->toArray();			

				$application_geo_loc_land = [];
				$application_geo_loc_land = $ApplicationGeoLocation->find('all', array(
					'fields'	=> [
						'manufacturer_master.name', 'district_master.name', 'id','wtg_make', 'x_cordinate', 'y_cordinate', 'geo_village', 'geo_taluka', 'geo_district', 'land_survey_no','type_of_land', 'wtg_location','wtg_capacity','wtg_rotor_dimension','wtg_hub_height'
					],
					'join'		=> [
						['table' => 'manufacturer_master', 'type' => 'LEFT', 'conditions' => ['wtg_make = manufacturer_master.id']],
						['table' => 'district_master', 'type' => 'LEFT', 'conditions' => ['geo_district = district_master.id']],
						['table' => 'wind_wtg_detail', 'type' => 'INNER', 'conditions' => ['ApplicationGeoLocation.id = wind_wtg_detail.app_geo_loc_id']]
					],
					'conditions' => array('ApplicationGeoLocation.application_id' => $ApplicationData->application_id, 'approved' => 1, "( wind_wtg_detail.app_dev_per_id =" . $dev_app_id . "  OR wind_wtg_detail.app_geo_loc_id IS NULL)")
				))->toArray();

				$Pooling_Data 		= $WindEvaculationPoolingData->fetchdata($dev_app_id);
				$type_of_land 		= array('G' => 'Goverment Land', 'P' => 'Private Land','GL' => 'Geda Land', 'F' => 'Forest Land');
				$pooling_village	= []; 
				$pooling_taluka		= []; 
				$pooling_district	= [];
				$pooling_vol		= [];
				if(isset($Pooling_Data) && !empty($Pooling_Data)){
					foreach($Pooling_Data as $pk=>$pv){
						if(!in_array(ucfirst(strtolower($pv['village_of_pooling_sub'])), $pooling_village, true)){
							array_push($pooling_village, ucfirst(strtolower($pv['village_of_pooling_sub'])));
						}
						if(!in_array(ucfirst(strtolower($pv['taluka_of_pooling_sub'])), $pooling_taluka, true)){
							array_push($pooling_taluka, ucfirst(strtolower($pv['taluka_of_pooling_sub'])));
						}
						$pdist = ucfirst(strtolower($district[$pv['distict_of_pooling_sub']]));
						if(!in_array($pdist, $pooling_district, true)){
							array_push($pooling_district, ucfirst(strtolower($district[$pv['distict_of_pooling_sub']])));
						}
						if(!in_array($pv['vol_of_pooling_sub'], $pooling_vol, true)){
							array_push($pooling_vol, $OpenAccessApplicationDeveloperPermission->voltage_level[$pv['vol_of_pooling_sub']]);
						}
					}
				}
				
				$Getco_Data 	= $WindEvaculationGetcoData->fetchdata($dev_app_id);
				$getco_village=[]; 
				$getco_taluka=[]; 
				$getco_vol=[];
				if(isset($Getco_Data) && !empty($Getco_Data)){
					foreach($Getco_Data as $pk=>$pv){
						if(!in_array(ucfirst(strtolower($pv['village_of_getco'])), $getco_village, true)){
							array_push($getco_village, ucfirst(strtolower($pv['village_of_getco'])));
						}
						if(!in_array(ucfirst(strtolower($pv['taluka_of_getco'])), $getco_taluka, true)){
							array_push($getco_taluka, ucfirst(strtolower($pv['taluka_of_getco'])));
						}
						
						if(!in_array($pv['vol_of_getco'], $getco_vol, true)){
							array_push($getco_vol, $pv['vol_of_getco']);
						}
					}
				}
						
				
				// $geo_village=[]; 
				// $geo_taluka=[]; 
				// $geo_district=[];
				// $capacity = 0;
				// if(isset($application_geo_loc_land) && !empty($application_geo_loc_land)){
				// 	$mw=0; $capacity=0;
				// 	foreach($application_geo_loc_land as $k=>$v){
						
				// 		if(!in_array($v['geo_village'], $geo_village, true)){
				// 			array_push($geo_village, $v['geo_village']);
				// 		}
				// 		$gtal = ucfirst(strtolower($taluka[$v['geo_taluka']]));
				// 		if(!in_array($gtal, $geo_taluka, true)){
				// 			array_push($geo_taluka, ucfirst(strtolower($taluka[$v['geo_taluka']])));
				// 		}
				// 		$gdist = ucfirst(strtolower($district[$v['geo_district']]));
				// 		if(!in_array($gdist, $geo_district, true)){
				// 			array_push($geo_district, ucfirst(strtolower($district[$v['geo_district']])));
				// 		}
				// 		$mw = $mw + $v['wtg_capacity'];
				// 		$capacity = $capacity + $mw;

				// 	} 
				// 	$capacity = $mw/1000;					
				// }

				//New
				$data = [];
				$geo_village=[]; 
				$geo_taluka=[]; 
				$geo_district=[];
				$capacity = 0;
				if (isset($application_geo_loc_land) && !empty($application_geo_loc_land)) {					
					$mw=0;				
					// organize data by make
					foreach ($application_geo_loc_land as $v) {
						if(!in_array($v['geo_village'], $geo_village, true)){
							array_push($geo_village, $v['geo_village']);
						}
						$gtal = ucfirst(strtolower($taluka[$v['geo_taluka']]));
						if(!in_array($gtal, $geo_taluka, true)){
							array_push($geo_taluka, ucfirst(strtolower($taluka[$v['geo_taluka']])));
						}
						$gdist = ucfirst(strtolower($district[$v['geo_district']]));
						if(!in_array($gdist, $geo_district, true)){
							array_push($geo_district, ucfirst(strtolower($district[$v['geo_district']])));
						}
						$mw = $mw + ($v['wtg_capacity']/1000);
						$capacity = $capacity + $mw;

						if (!isset($data[$v->wtg_make])) {
							$data[$v->wtg_make] = [
								'details' => [],
								'caption' => ''
							];
						}
						$data[$v->wtg_make]['details'][] = $v;						
					}						

					foreach ($data as $make => &$info) {
						$kw = 0;
						$makeName = '';
						$rotor = [];
						$height = [];
						foreach ($info['details'] as $detail) {
							$kw += $detail['wtg_capacity'];
							$makeName = $detail['manufacturer_master']['name']; 
							// rotor and height arrays
							if (!in_array($detail['wtg_rotor_dimension'], $rotor, true)) {
								$rotor[] = $detail['wtg_rotor_dimension'];
							}
							if (!in_array($detail['wtg_hub_height'], $height, true)) {
								$height[] = $detail['wtg_hub_height'];
							}
						}
				
						$info['caption'] = sprintf(
							'%d kW %s Make WTGs with %s meters rotor dia. and %s meters hub height',
							$kw,
							$makeName,
							implode('/', $rotor),
							implode('/', $height)
						);
					}
				
				}				
				//New

				$appName = '';
				if(isset($ApplicationData['grid_connectivity']) && !empty($ApplicationData['grid_connectivity']) && $ApplicationData['grid_connectivity'] == 1)
				{
					if(isset($ApplicationData['end_stu']) && !empty($ApplicationData['end_stu']) && $ApplicationData['end_stu'] == 1){
						$appName = "Sale of Power to GUVNL";
					}else if(isset($ApplicationData['end_stu']) && !empty($ApplicationData['end_stu']) && $ApplicationData['end_stu'] == 2){						
						if($ApplicationData['captive']==1){
							$appName = "Captive with RPO Compliance";
						}else if($ApplicationData['captive']==2){
							$appName = "Captive with REC Mechanism";
						}else{
							$appName = "Captive Use";
						}
					}else if (isset($ApplicationData['end_stu']) && !empty($ApplicationData['end_stu']) && $ApplicationData['end_stu'] == 3){
						
						if($ApplicationData['third_party']==1){
							$appName = "Third Party Sale with RPO Compliance";
						}else if($ApplicationData['third_party']==2){
							$appName = "Third Party Sale with REC Mechanism";
						}else{
							$appName = "Third Party Sale";
						}
					}
				}

				if(isset($ApplicationData['grid_connectivity']) && !empty($ApplicationData['grid_connectivity']) && $ApplicationData['grid_connectivity'] == 2)
				{
					if(isset($ApplicationData['end_ctu']) && !empty($ApplicationData['end_ctu']) && $ApplicationData['end_ctu'] == 1){
						$appName = "Open Access - ISTS";
					}else if(isset($ApplicationData['end_ctu']) && !empty($ApplicationData['end_ctu']) && $ApplicationData['end_ctu'] == 2){
						$appName = "REC Power Development and Consultancy Limited Bid Connected to ISTS";
					}else if (isset($ApplicationData['end_ctu']) && !empty($ApplicationData['end_ctu']) && $ApplicationData['end_ctu'] == 3){
						$appName = "Prototype";
					}
				}
				
				//if($ApplicationData->application_type == 4){
					$Roof_Land_Data 		= $WindLandDetails->fetchdata($dev_app_id, 0);
					$application_additional_data 		= $HybridAdditionalData->find('all', array(
						'fields'	=>	array('mod_inv_total_capacity'),
						'conditions' => array('app_dev_per_id' =>$dev_app_id,'capacity_type'=>2)
					))->toArray();

					
					$roofCapacitySum=0;
					if(isset($application_additional_data) && !empty($application_additional_data)){
						foreach($application_additional_data as $app=>$vapp){
							$roofCapacitySum+=$vapp['mod_inv_total_capacity'];
						}
					}
					$roof_district=[];
					if(isset($Roof_Land_Data) && !empty($Roof_Land_Data)){
						foreach($Roof_Land_Data as $rk=>$rv){
							$rdist = ucfirst(strtolower($district[$rv['land_district']]));
							if(!in_array($rdist, $roof_district, true)){
								array_push($roof_district, ucfirst(strtolower($district[$rv['land_district']])));
							}							
						}
					}
					$totalInverternos		= $HybridAdditionalData->getHybridDataSum($dev_app_id, 2);
					$totalModulenos			= $HybridAdditionalData->getHybridDataSum($dev_app_id, 1);
					
				//}

				
				
				$view->set('ApplicationData', $ApplicationData);
				$view->set('devAppData',$devAppData);
				$view->set("arrDistictData", $district);
				$view->set("arrTalukaData",$taluka);
				$view->set("ApplicationGeoLocLand", $data);
				$view->set('geoVillage', implode(',',$geo_village));
				$view->set('geoTaluka', implode(',',$geo_taluka));
				$view->set('geoDistrict', implode(',',$geo_district));
				$view->set('appData', $appData);
				$view->set('capacity',$capacity);
				$view->set('roofCapacitySum',$roofCapacitySum);
				$view->set('poolingVillage',implode(',',$pooling_village));
				$view->set('poolingTaluka',implode(',',$pooling_taluka));
				$view->set('poolingDistrict',implode(',',$pooling_district));
				$view->set('poolingVol',implode(',',$pooling_vol));
				$view->set('getcoVillage',implode(',',$getco_village));
				$view->set('getcoTaluka',implode(',',$getco_taluka));
				$view->set('getcoVol',implode(',',$getco_vol));
				$view->set('roofDistrict', implode(',',$roof_district));
				$view->set('roofLandData',$Roof_Land_Data);
				$view->set('totalInverternos', $totalInverternos);
				$view->set('totalModulenos', $totalModulenos);
				$view->set('appName',$appName);
				$view->set('typeOfLand',$type_of_land);

				/* Generate PDF for estimation of project */
				require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
				$dompdf = new Dompdf($options = array());
				$dompdf->set_base_path(SITE_ROOT_DIR_PATH . '/pdf/');
				$dompdf->set_option("isPhpEnabled", true);
				$view->set('dompdf', $dompdf);
				
				if($isdownload == null)
				{
					$html = $view->render('/Element/application-developer-permission/download_wind_hybrid_dp_letter');
					
					return $html;
					
				}else{
					
					$windHybridLetterData = $WindHybridDpLetter->find('all', array('conditions' => array('application_id' => $app_id,'app_dev_per_id'=>$dev_app_id)))->first();
					
					$newhtml = isset($windHybridLetterData) ? $windHybridLetterData['content'] : '';
					$view->set('newHtml', $newhtml);
					$html = $view->render('/Element/application-developer-permission/download_wind_hybrid_dp_letter');
					$dompdf->loadHtml($html, 'UTF-8');
				
					$dompdf->setPaper('A4', 'portrait');
					$dompdf->render();
					if ($isdownload) {
						$dompdf->stream('DPLetter-' . $PDFFILENAME);
					}
					$output = $dompdf->output();
					header("Content-type:application/pdf");
					header("Content-Disposition:inline;filename='" . $PDFFILENAME . ".pdf'");
					echo $output;
					die;
				}		
				
			}
		}
	}

	public function viewDetailApplication($application_id)
	{

		$fields             = [
			'applications.registration_no',
			'WindApplicationDeveloperPermission.id',
			'WindApplicationDeveloperPermission.application_id',
			'WindApplicationDeveloperPermission.customer_id',
			'WindApplicationDeveloperPermission.installer_id',
			'WindApplicationDeveloperPermission.application_type',
			'WindApplicationDeveloperPermission.name_of_applicant',
			'WindApplicationDeveloperPermission.address',
			'WindApplicationDeveloperPermission.address1',
			'WindApplicationDeveloperPermission.taluka',
			'WindApplicationDeveloperPermission.pincode',
			'WindApplicationDeveloperPermission.city',
			'WindApplicationDeveloperPermission.state',
			'WindApplicationDeveloperPermission.district',
			'WindApplicationDeveloperPermission.district_code',
			'WindApplicationDeveloperPermission.type_of_applicant',
			'WindApplicationDeveloperPermission.registration_document',
			'WindApplicationDeveloperPermission.applicant_others',
			'WindApplicationDeveloperPermission.contact',
			'WindApplicationDeveloperPermission.mobile',
			'WindApplicationDeveloperPermission.email',
			'WindApplicationDeveloperPermission.pan',
			'WindApplicationDeveloperPermission.pan_card',
			'WindApplicationDeveloperPermission.GST',
			'WindApplicationDeveloperPermission.a_msme',
			'WindApplicationDeveloperPermission.upload_undertaking',
			'WindApplicationDeveloperPermission.name_director',
			'WindApplicationDeveloperPermission.type_director',
			'WindApplicationDeveloperPermission.type_director_others',
			'WindApplicationDeveloperPermission.director_whatsapp',
			'WindApplicationDeveloperPermission.director_mobile',
			'WindApplicationDeveloperPermission.director_email',
			'WindApplicationDeveloperPermission.name_authority',
			'WindApplicationDeveloperPermission.type_authority',
			'WindApplicationDeveloperPermission.type_authority_others',
			'WindApplicationDeveloperPermission.d_file_board',
			'WindApplicationDeveloperPermission.authority_whatsapp',
			'WindApplicationDeveloperPermission.authority_mobile',
			'WindApplicationDeveloperPermission.authority_email',
			'WindApplicationDeveloperPermission.re_name_of_share_holder',
			'WindApplicationDeveloperPermission.re_equity_re_persontage',
			'WindApplicationDeveloperPermission.grid_connectivity',
			'WindApplicationDeveloperPermission.getco_substation',
			'WindApplicationDeveloperPermission.injection_level',
			'WindApplicationDeveloperPermission.project_energy',
			'WindApplicationDeveloperPermission.end_stu',
			'WindApplicationDeveloperPermission.end_ctu',
			'WindApplicationDeveloperPermission.captive',
			'WindApplicationDeveloperPermission.third_party',
			'WindApplicationDeveloperPermission.upload_sale_to_discom',
			'WindApplicationDeveloperPermission.no_due_1',
			'WindApplicationDeveloperPermission.no_due_2',
			'WindApplicationDeveloperPermission.upload_proof_of_ownership_1',
			'WindApplicationDeveloperPermission.upload_proof_of_ownership_2',
			'WindApplicationDeveloperPermission.beneficiary_obligated_entity',
			'WindApplicationDeveloperPermission.doc_of_beneficiary',
			'WindApplicationDeveloperPermission.copy_of_gerc',
			'WindApplicationDeveloperPermission.captive_conv_power_plant',
			'WindApplicationDeveloperPermission.capacity_of_cpp',
			'WindApplicationDeveloperPermission.copy_of_conventional_electricity',
			'WindApplicationDeveloperPermission.prev_solar_project',
			'WindApplicationDeveloperPermission.certi_of_stoa',
			'WindApplicationDeveloperPermission.certi_of_stoa_capacity',
			'WindApplicationDeveloperPermission.RE_generating_plant',
			'WindApplicationDeveloperPermission.stamp_of_re_gen_plant',
			'WindApplicationDeveloperPermission.details_of_third_party',
			'WindApplicationDeveloperPermission.third_party_name',
			'WindApplicationDeveloperPermission.third_party_address',
			'WindApplicationDeveloperPermission.third_party_consumer_no',
			'WindApplicationDeveloperPermission.third_party_contract_demand',
			'WindApplicationDeveloperPermission.third_party_capacity_existing_plant',
			'WindApplicationDeveloperPermission.electricity_bill_of_third_party',
			'WindApplicationDeveloperPermission.multi_third_party',
			'WindApplicationDeveloperPermission.app_trans_to_stu',
			// 'WindApplicationDeveloperPermission.sanctioned_load',
			// 'WindApplicationDeveloperPermission.consumer_no',
			// 'WindApplicationDeveloperPermission.copy_of_electricity_bill',
			'WindApplicationDeveloperPermission.discom',
			//'WindApplicationDeveloperPermission.voltage_level',
			'WindApplicationDeveloperPermission.project_estimated_cost',
			//'WindApplicationDeveloperPermission.wheel_energy_multi_location',
			'WindApplicationDeveloperPermission.permission_letter_of_getco',
			'WindApplicationDeveloperPermission.permission_lett_ref_no',
			'WindApplicationDeveloperPermission.dt_of_per_validity',
			'WindApplicationDeveloperPermission.undertaking_dec',
			'WindApplicationDeveloperPermission.micro_sitting_drawing',
			'WindApplicationDeveloperPermission.proof_of_ownership',
			'WindApplicationDeveloperPermission.notarized_contract',
			'WindApplicationDeveloperPermission.ca_certificate',
			'WindApplicationDeveloperPermission.invoice_with_gst',
			'WindApplicationDeveloperPermission.share_subscription',
			'WindApplicationDeveloperPermission.pvt_proposed_land',
			'WindApplicationDeveloperPermission.proj_sale_to_discom_no_due',
			'WindApplicationDeveloperPermission.proj_captive_use_no_due',
			'WindApplicationDeveloperPermission.cover_letter',
			'WindApplicationDeveloperPermission.liable_tds',
			'WindApplicationDeveloperPermission.terms_agree',
			'WindApplicationDeveloperPermission.application_fee',
			'WindApplicationDeveloperPermission.provisional_total_fee',
			'WindApplicationDeveloperPermission.application_total_fee',
			'WindApplicationDeveloperPermission.gst_fees',
			'WindApplicationDeveloperPermission.tds_deduction',
			'WindApplicationDeveloperPermission.payable_total_fee',
			'WindApplicationDeveloperPermission.payment_status',
			'WindApplicationDeveloperPermission.created',
			'WindApplicationDeveloperPermission.created_by',
			'WindApplicationDeveloperPermission.modified',
			'WindApplicationDeveloperPermission.modified_by',
			'application_category.category_name',
			'application_category.route_name',
			'application_category.color_code',
			'developer_customers.name',
			'developers.installer_name',
			'district_master.name',
			'WindApplicationDeveloperPermission.equity_share',
			'WindApplicationDeveloperPermission.cgp',
			'WindApplicationDeveloperPermission.inward_date',
			'WindApplicationDeveloperPermission.final_registration_no',
			'WindApplicationDeveloperPermission.final_registration_date',

		];
		$join_arr  		= [
			'application_category'						=> ['table' => 'application_category', 'type' => 'left', 'conditions' => 'WindApplicationDeveloperPermission.application_type=application_category.id'],
			'district_master'							=> ['table' => 'district_master', 'type' => 'left', 'conditions' => 'WindApplicationDeveloperPermission.district=district_master.id'],
			'applications' 								=> ['table' => 'applications', 'type' => 'left', 'conditions' => 'applications.id = WindApplicationDeveloperPermission.application_id'],
			'developer_customers' 						=> ['table' => 'developer_customers', 'type' => 'left', 'conditions' => 'developer_customers.id = WindApplicationDeveloperPermission.customer_id'],
			'developers' 								=> ['table' => 'developers', 'type' => 'left', 'conditions' => 'developer_customers.installer_id = developers.id'],
			// 'application_open_access_additional_data'	=> ['table'=>'application_open_access_additional_data','type'=>'left','conditions'=>'application_open_access_additional_data.app_dev_per_id = OpenAccessApplicationDeveloperPermission.id']
		];
		$arrApplication = $this->find('all', array(
			'fields' 	=> $fields,
			'join' 		=> $join_arr,
			'conditions' => array('WindApplicationDeveloperPermission.id' => $application_id)
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

		$array[1] = 'DP';
		$array[3] = $financialYear;
		$array[4] = $currentMonth;
		$array[7] = $id;
	
		$newString = implode('/', $array);

		return $newString;
	}
}
