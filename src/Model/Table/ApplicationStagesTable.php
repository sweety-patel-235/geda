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
class ApplicationStagesTable extends AppTable
{
	var $table 		= 'application_stages';
	var $application_status = [
		'' => 'Pending to submit',
		'1' => 'Application Submitted',
		'2' => 'Stage 1: Connectivity ',
		'3' => 'CEI Drawing', //'Subsidy available',
		'4' => 'CEI Inspection',
		'5' => 'BPTA',
		'6' => 'BPTA APPROVED',
		'7' => 'Wheeling Agreement',
		'8' => 'Wheeling Agreement Approved',
		'9' => 'Approved From CEI',
		'10' => 'Rejected From CEI',
		'11' => 'Inspection From DisCom',
		'12' => 'Application Agreement',
		'13' => 'Rejected From DisCom',
		'14' => 'Inspection From GEDA',
		'15' => 'Approved From GEDA',
		'16' => 'Rejected From GEDA',
		// '17'=>'Meter Installation',
		'18' => 'Release Subsidy',
		'19' => 'Signing Of Agreement',
		'20' => 'Field Service Initiated',
		'21' => 'Fesibility Rejected',
		'22' => 'OTP Verification Pending',
		'23' => 'Document Verified',
		'24' => 'Drawing Applied',
		'25' => 'Work Executed',
		'26' => 'CEI Inspection Applied',
		'27' => 'Inspection Approved From CEI',
		'28' => 'Subsidy Claimed',
		'29' => 'Application Pending',
		'30' => 'In Waiting List',
		'31' => 'Approved From GEDA',
		'32' => 'Rejected From GEDA',
		//'34'=>'Connectivity Step 1',
		'34' => 'Connectivity Stage 2',
		'35' => 'TFR',
		'36' => 'CTU1',
		'37' => 'STU',
		'38' => 'CTU2',
		'99' => 'Application Cancelled',
		'40' => 'Developer Application Submitted',
		'42' => 'Developer Application for Approval',
		'41' => 'Developer Application Approved'
	];
	var $apply_online_main_status = [
		'1' => 'Application Submitted',
		'2' => 'Document Verified',
		'3' => 'Provisional Letter',
		'4' => 'Stage 1: Connectivity',
		//'15'=>'WTG Co-Verification',
		'5' => 'Stage 2: Connectivity',
		//'6'=>'CEI Approval',
		//'7'=>'Developer Permission',
		'7' => 'Developer Permission',
		'6' => 'CEI Drawing',
		'8' => 'CEI Inspection',
		'9' => 'Application Agreement',
		'10' => 'Project Commissioning',
		//'7'=>'Meter Installation',
		//'7'=>'Registration',
		//'8'=>'CEI Inspection',
		//'9'=>'Subsidy Claimed'
	];
	var $apply_online_main_status_TP = [
		'1' => 'Application Submitted',
		'2' => 'Document Verified',
		'3' => 'Provisional Letter',
		'11' => 'TFR',
		//'15'=>'WTG Co-Verification',
		'7' => 'Developer Permission',
		'6' => 'CEI Drawing',
		'8' => 'CEI Inspection',
		'9' => 'Application Agreement',
		'10' => 'Project Commissioning',
	];
	var $apply_online_main_status_CTU = [
		'1' => 'Application Submitted',
		'2' => 'Document Verified',
		'3' => 'Provisional Letter',
		'12' => 'CTU - In Principal',
		//'15'=>'WTG Co-Verification',
		'14' => 'CTU - Final Principal',
		'7' => 'Developer Permission',
		'6' => 'CEI Drawing',
		'8' => 'CEI Inspection',
		'9' => 'Application Agreement',
		'10' => 'Project Commissioning',
	];
	var $apply_online_main_status_STU = [
		'1' => 'Application Submitted',
		'2' => 'Document Verified',
		'3' => 'Provisional Letter',
		'13' => 'STU',
		//'15'=>'WTG Co-Verification',
		'7' => 'Developer Permission',
		'6' => 'CEI Drawing',
		'8' => 'CEI Inspection',
		'9' => 'Application Agreement',
		'10' => 'Project Commissioning',
	];
	var $apply_online_main_status_kusum = [
		'1' => 'Application Submitted',
		'2' => 'Document Verified',
		'3' => 'GEDA Letter'
	];
	var $apply_online_guj_status  = [
		'1' => 'Application Submitted',
		'2' => 'GEDA Letter',
		'3' => 'Document Verified',
		'4' => 'CONNECTIVITY STEP1',
		//'5'=>'Cordinate Verification',
		'5' => 'STEP 2: CONNECTIVITY',
		//'6'=>'CEI Approval',
		//'6'=>'Developer Permission',
		'7' => 'Developer Permission',
		'8' => 'Application Agreement',
		//'7'=>'Registration',
		'9' => 'CEI Inspection',
		'10' => 'Subsidy Claimed'
	];

	var $apply_online_main_status_v2 = [
		'1' => 'Preliminary Stage',
		'2' => 'CONNECTIVITY STAGE1 ',
		'3' => 'Execution Stage',
		'4' => 'Inspection Stage',
		'5' => 'Closure Stage'
	];
	var $application_dropdown_status = [
		'1'		=> 'Application Submitted',
		'39'	=> 'WTG Co-Verification',
		'311'	=> 'Document Verification Pending',
		'31'	=> 'Provisional Letter',
		'2000'	=> 'Query Raised',
		'2111'	=> 'Query Replied',
		'40'	=> 'Developer Application Submitted',
		'42'	=> 'Developer Application for Approval',
		'41'	=> 'Developer Application Verified'
	]; //'310'	=> 'Application Submitted - Payment Pending',
	var $geo_application_dropdown_status = [
		'3'	=> 'Clashed',
		'1'	=> 'Non Clashed',
		'5'	=> 'Internal Clashed',
		'2'	=> 'Rejected',
	];

	var $APPLICATION_SUBMITTED   						= 1;
	var $CONNECTIVITY_STEP1 							= 2; //Neha
	var $DRAWING_APPLIED 								= 3;
	var $CEI_INSPECTION_APPROVED 						= 4;
	var $BPTA 											= 5;
	var $BPTA_APPROVED  								= 6;
	var $WHELLING										= 7;
	var $WHELLING_APPROVED                              = 8;
	var $METER_SEALING                              	= 9;
	var $METER_SEALING_APPROVED                         = 10;
	var $POWER_INJECTION                         		= 11;
	var $POWER_INJECTION_APPROVED                       = 12;

	var $INTIMATION_FOR_COMPLETION                      = 13;
	var $PROJECT_COMMISSIONING                          = 14;
	
	var $APPROVED_FROM_JREDA                            = 15;
	var $REJECTED_FROM_JREDA                            = 16;
	var $APPROVED_FROM_DISCOM                           = 17;
	var $REJECTED_FROM_DISCOM                           = 18;
	var $APPROVED_FROM_CEI                              = 19;
	var $REJECTED_FROM_CEI                              = 20;


	// var $FIELD_REPORT_REJECTED                          = 21;
	// var $SHOWFESIBILITYLINK                             = array(23, 20, 21);
	 var $APPLICATION_GENERATE_OTP                       = 22;
	 var $DOCUMENT_VERIFIED                              = 23;
	// var $NOTSHOWGEDALETTERLINK                          = array(22, 29);
	//var $DRAWING_APPLIED                                = 24;
	var $WORK_EXECUTED                                  = 25;
	var $CEI_APP_NUMBER_APPLIED                         = 26;
	//var $CEI_INSPECTION_APPROVED                        = 27;
	// var $CLAIM_SUBSIDY                                  = 28;
	//var $ProjectCommissioning                           = 28;
	var $APPLICATION_PENDING                            = 29;
	var $WAITING_LIST                                   = 30;
	var $APPROVED_FROM_GEDA                             = 31;
	var $REJECTED_FROM_GEDA                             = 32;
	var $APPLICATION_CANCELLED                          = 99;
	var $DOCUMENT_NOT_VERIFIED                          = 9999;
	var $CONNECTIVITY_STEP2  							= 34; //Neha
	var $TFR  											= 35; //Neha
	var $CTU1  											= 36; //Neha
	var $STU  											= 37; //Neha
	var $CTU2  											= 38; //Neha
	var $WTG_STAGE  									= 39; //Neha
	var $DEVELOPER_APPLICATION_SUBMITTED				= 40; //Vishal
	var $DEVELOPER_APPLICATION_VERIFIED					= 41; //Vishal
	var $DEVELOPER_APPLICATION_FORWARD					= 42; //Vishal
	//vishal
	var $APPLICATION_SUBMIT_PAYMENT_PENDING             = 310;
	var $DOCUMENT_VERIFICATION_PENDING                  = 311;
	var $QUERY_RAISED									= 2000;
	var $QUERY_REPLY									= 2111;

	public function initialize(array $config)
	{
		$this->table($this->table);
	}
	/**
	 * saveStatus
	 * Behaviour : public
	 * Parameter : application_id, status, created_by (login person either developer/installer/member), reason
	 * @defination : In order to get financial year.
	 */
	public function saveStatus($application_id, $status, $created_by, $reason = "")
	{
		$conditions = array('application_id' => $application_id, 'stage' => $status);
		$Count      = $this->find('all', ['conditions' => $conditions])->first();
		if (empty($Count)) {
			$applicationEntity  				= $this->newEntity();
			$applicationEntity->application_id  = $application_id;
			$applicationEntity->member_id 		= $created_by;
			$applicationEntity->stage 			= $status;
			$applicationEntity->reason 			= $reason;
			$applicationEntity->created 		= $this->NOW();

			$Applications  						= TableRegistry::get('Applications');
			$Applications->updateAll(array('application_status' => $status, 'modified' => $this->NOW(), 'modified_by' => $created_by), array('id' => $application_id));
			return $this->save($applicationEntity);
		}
	}
	public function saveDevAppStatus($application_id, $status, $created_by, $reason = "")
	{
		$conditions = array('application_id' => $application_id, 'stage' => $status, 'reason' => $reason);
		$Count      = $this->find('all', ['conditions' => $conditions])->first();
		if (empty($Count)) {
			$applicationEntity  				= $this->newEntity();
			$applicationEntity->application_id  = $application_id;
			$applicationEntity->member_id 		= $created_by;
			$applicationEntity->stage 			= $status;
			$applicationEntity->reason 			= $reason;
			$applicationEntity->created 		= $this->NOW();

			$Applications  						= TableRegistry::get('Applications');
			$Applications->updateAll(array('application_status' => $status, 'modified' => $this->NOW(), 'modified_by' => $created_by), array('id' => $application_id));
			return $this->save($applicationEntity);
		}
	}
	public function Approvalstage($application_id)
	{
		$approve_stage    = $this->find('all', array('conditions' => array('application_id' => $application_id)))->distinct(['stage'])->toArray();
		$stage          = array();
		foreach ($approve_stage as $value) {
			$stage[]    = $value['stage'];
		}
		return $stage;
	}
	public function getLastStage($application_id)
	{
		$arr_data           = array();
		$last_stage         = $this->find('all', array('conditions' => array('application_id' => $application_id), 'order' => array('id' => 'desc')))->first();
		return ($last_stage);
	}
	public function getsubmittedStageData($application_id)
	{
		$arr_data           = array();
		$app_stage         = $this->find('all', array('conditions' => array('application_id' => $application_id, 'stage' => $this->APPLICATION_SUBMITTED), 'order' => array('id' => 'desc')))->first();
		return ($app_stage);
	}
	public function getgedaletterStageData($application_id)
	{
		$arr_data           	= array();
		$ReApplicationPayment 	= TableRegistry::get('ReApplicationPayment');
		$app_stage         	 	= $this->find('all', array('conditions' => array('application_id' => $application_id, 'stage' => $this->APPROVED_FROM_GEDA), 'order' => array('id' => 'desc')))->first();
		$payment_data       	= $ReApplicationPayment->find('all', array('conditions' => array('application_id' => $application_id), 'order' => array('id' => 'desc')))->first();

		if (!empty($payment_data) && isset($payment_data->payment_dt) && !empty($payment_data->payment_dt)) {
			$app_stage->created     = $payment_data->payment_dt;
		}
		return ($app_stage);
	}
	public function all_status_application($application_id, $apply_state = '')
	{
		$arr_data           = array();
		$arr_approve        = $this->find('all', array('conditions' => array('application_id' => $application_id)))->distinct(['stage'])->toArray();

		foreach ($arr_approve as $a_data) {
			$active_data    = 1;
			switch ($a_data->stage) {
				case 1: {
						$active_data     = 1;
						break;
					}
				case 2: {
						$active_data = 4;
						$active_data = 13;
						break;
					}
				case 3: {
						$active_data = 6;
						break;
					}
				case 4: {
						$active_data = 8;
						break;
					}
				case 5: {
						$active_data = 8;
						break;
					}
				case 6: {
						$active_data = 16;
						break;
					}
				case 7: {
						break;
					}
				case 8: {
						$active_data = 9;
						break;
					}
				case 9: {
						//$active_data = 16;
						break;
					}
				case 10: {
						$active_data = 17;
						break;
					}	
				case 11: {
						//$active_data = 16;
						break;
					}
				case 12: {
						$active_data = 18;
						break;
					}
				case 13: {
						//$active_data = 18;
						break;
					}
				case 14: {
						$active_data = 10;
						break;
					}
				case 34: {
						$active_data = 5;
						break;
					}
				case 35: {
						$active_data = 11;
						break;
					}
				case 36: {
						$active_data = 12;
						break;
					}
				case 38: {
						$active_data = 14;
						break;
					}
				case 37: {
						$active_data = 13;
						break;
					}
				case 41: {
						$active_data = 7;
						break;
					}
			
				// case 27: {
				// 		$active_data = 7;
				// 		break;
				// 	}
				
				case 31:
				case 21: {
						$active_data = 2;
						break;
					}
				
				case 23: {
						$active_data = 3;
						break;
					}
				case 22:
				default: {
						$active_data = 0;
						break;
					}
			}
			$arr_data[$active_data][] = $a_data->stage;
			// if($active_data=='3')
			// {
			//     $arr_data['2'][] = $a_data->stage;
			// }
		}

		return ($arr_data);
	}
	public function validateNewStatus($NextStatus, $CurrentStatus)
	{
		if ($CurrentStatus == $this->REJECTED_FROM_JREDA && $NextStatus == $this->APPROVED_FROM_JREDA) {
			return true;
		} else if ($CurrentStatus == $this->REJECTED_FROM_DISCOM && $NextStatus == $this->APPROVED_FROM_DISCOM) {
			return true;
		} else if ($CurrentStatus == $this->REJECTED_FROM_CEI && $NextStatus == $this->APPROVED_FROM_CEI) {
			return true;
		} else if ($CurrentStatus >= $NextStatus && !in_array($CurrentStatus, array(20, 21, 23, 24, 27, 25, 17, 31, 32))) {
			return false;
		} else {
			return true;
		}
	}
}
