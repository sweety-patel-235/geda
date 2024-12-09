<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class ApplyOnlineApprovalsTable extends AppTable
{
	var $table              = 'apply_online_approvals';
	var $application_status = [ ''=>'Pending to submit',
								'1'=>'Application Submitted',
								'2'=>'Feasibility Approved',
								'3'=>'Feasibility Approved',//'Subsidy available',
								'4'=>'Work Starts',
								'5'=>'Funds are not available',
								'6'=>'Funds are available but scheme is not active',
								'7'=>'Registration',
								'8'=>'Inspection and Approval From CEI',
								'9'=>'Approved From CEI',
								'10'=>'Rejected From CEI',
								'11'=>'Inspection From DisCom',
								'12'=>'Meter Installation',
								'13'=>'Rejected From DisCom',
								'14'=>'Inspection From GEDA',
								'15'=>'Approved From GEDA',
								'16'=>'Rejected From GEDA',
								'17'=>'Meter Installation',
								'18'=>'Release Subsidy',
								'19'=>'Signing Of Agreement',
								'20'=>'Field Service Initiated',
								'21'=>'Fesibility Rejected',
								'22'=>'OTP Verification Pending',
								'23'=>'Document Verified',
								'24'=>'Drawing Applied',
								'25'=>'Work Executed',
								'26'=>'CEI Inspection Applied',
								'27'=>'Inspection Approved From CEI',
								'28'=>'Subsidy Claimed',
								'29'=>'Application Pending',
								'30'=>'In Waiting List',
								'31'=>'Approved From GEDA',
								'32'=>'Rejected From GEDA',
								'99'=>'Application Cancelled'
							];
	var $apply_online_main_status = [   '1'=>'Application Submitted',
										'2'=>'GEDA Letter',
										'3'=>'Document Verified',
										'4'=>'Feasibility Approved',
										'5'=>'CEI Approval',
										'6'=>'Work Starts',
										'7'=>'CEI Inspection',
										'8'=>'Meter Installation',
										//'7'=>'Meter Installation',
										//'7'=>'Registration',
										//'8'=>'CEI Inspection',
										'9'=>'Subsidy Claimed'];
	var $apply_online_guj_status  = [   '1'=>'Application Submitted',
										'2'=>'GEDA Letter',
										'3'=>'Document Verified',
										'4'=>'Feasibility Approved',
										'5'=>'CEI Approval',
										'6'=>'Work Starts',
										'7'=>'Meter Installation',
										//'7'=>'Registration',
										'8'=>'CEI Inspection',
										'9'=>'Subsidy Claimed'];
	var $apply_online_main_status_v2 = ['1'=>'Preliminary Stage',
										'2'=>'Feasibiliy Stage',
										'3'=>'Execution Stage',
										'4'=>'Inspection Stage',
										'5'=>'Closure Stage'];
	var $APPLICATION_SUBMITTED                          = 1;
	var $FEASIBILITY_APPROVAL                           = 2;
	var $SUBSIDY_AVAILIBILITY                           = 3;
	var $WORK_STARTS                                    = 4;
	var $FUNDS_ARE_NOT_AVAILABLE                        = 5;
	var $FUNDS_ARE_AVAILABLE_BUT_SCHEME_IS_NOT_ACTIVE   = 6;
	var $REGISTRATION                                   = 7;
	var $INSPECTION_FROM_CEI                            = 8;
	var $APPROVED_FROM_CEI                              = 9;
	var $REJECTED_FROM_CEI                              = 10;
	var $INSPECTION_FROM_DISCOM                         = 11;
	var $APPROVED_FROM_DISCOM                           = 12;
	var $REJECTED_FROM_DISCOM                           = 13;
	var $INSPECTION_FROM_JREDA                          = 14;
	var $APPROVED_FROM_JREDA                            = 15;
	var $REJECTED_FROM_JREDA                            = 16;
	var $METER_INSTALLATION                             = 17;
	var $RELEASE_SUBSIDY                                = 18;
	var $SIGNING_OF_AGREEMENT                           = 19;
	var $FIELD_REPORT_SUBMITTED                         = 20;
	var $FIELD_REPORT_REJECTED                          = 21;
	var $SHOWFESIBILITYLINK                             = array(23,20,21);
	var $APPLICATION_GENERATE_OTP                       = 22;
	var $DOCUMENT_VERIFIED                              = 23;
	var $NOTSHOWGEDALETTERLINK                          = array(22,29);
	var $DRAWING_APPLIED                                = 24;
	var $WORK_EXECUTED                                  = 25;
	var $CEI_APP_NUMBER_APPLIED                         = 26;
	var $CEI_INSPECTION_APPROVED                        = 27;
	var $CLAIM_SUBSIDY                                  = 28;
	var $APPLICATION_PENDING                            = 29;
	var $WAITING_LIST                                   = 30;
	var $APPROVED_FROM_GEDA                             = 31;
	var $REJECTED_FROM_GEDA                             = 32;
	var $APPLICATION_CANCELLED                          = 99;
	var $DOCUMENT_NOT_VERIFIED                          = 9999;

	public function initialize(array $config)
	{
		$this->table($this->table);       	
	}

	public function saveStatus($aplication_id,$status,$member_id,$reason="") {
		$conditions = array('application_id'=>$aplication_id,'stage'=>$status);
		$Count      = $this->find('all',['conditions'=>$conditions])->count();
		if ($Count == 0) {
			$applicationEntaty               = $this->newEntity();
			$applicationEntaty->application_id   = $aplication_id;
			$applicationEntaty->member_id        = $member_id;
			$applicationEntaty->stage            = $status;
			$applicationEntaty->reason           = $reason;
			$applicationEntaty->created          = $this->NOW();
		   
			return $this->save($applicationEntaty);
		}
	}

	public function validateNewStatus($NextStatus,$CurrentStatus)
	{
		if ($CurrentStatus == $this->REJECTED_FROM_JREDA && $NextStatus == $this->APPROVED_FROM_JREDA) {
			return true;
		} else if ($CurrentStatus == $this->REJECTED_FROM_DISCOM && $NextStatus == $this->APPROVED_FROM_DISCOM) {
			return true;
		} else if ($CurrentStatus == $this->REJECTED_FROM_CEI && $NextStatus == $this->APPROVED_FROM_CEI) {
			return true;
		} else if ($CurrentStatus >= $NextStatus && !in_array($CurrentStatus,array(20,21,23,24,27,25,17,31,32))) {
			return false;
		} else {
			return true;
		}
	}
	public function can_workstart($apply_status)
	{
		if(!empty($apply_status) && !in_array($apply_status, array(1,22,23,20,21)))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	public function all_status_application($application_id,$apply_state='')
	{
		$arr_data           = array();
		$arr_approve        = $this->find('all',array('conditions'=>array('application_id'=>$application_id)))->distinct(['stage'])->toArray();
	   
		foreach($arr_approve as $a_data)
		{
			$active_data    = 1;
			 switch ($a_data->stage) 
			{
				case 1: {
					$active_data     = 1;
					break;
				}
				case 2: {
					$active_data = 4;
					break;
				}
				case 3: 
				case 5: 
				case 6: 
				case 24:
				{
					$active_data = 4;
					break;
				}
				case 4: 
				case 25:
				{
					$active_data = 6;
					break;
				}
				case 9: 
				{
					$active_data = 5;
					break;
				}
				case 15:
				{
					$active_data = 7;
					break;
				}
				case 12:
				//case 26:
				{
					$active_data = 8;
					break;
				}
				case 7: 
				case 17:
				{
					$active_data = 8;
					break;
				}
				case 8: 
				{
					$active_data = 7;
					break;
				}
				case 27: 
				{
					$active_data = 7;
					break;
				}
				case 20:
				case 31: 
				case 21:
				{
					$active_data = 2;
					break;
				}
				case 28:
				{
					$active_data = 9;
					break;
				}
				case 23:
				{
					$active_data = 3;
					break;
				}
				case 22:
				default:
				{
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
	public function ApprovedfesibilityStatus($application_id){
		$fesibility_approved        = $this->find('all',array('conditions'=>array('application_id'=>$application_id,'stage'=>$this->FIELD_REPORT_SUBMITTED)))->distinct(['stage'])->first();
		if(!empty($fesibility_approved ))
		{
			return true;
		}
		else
		{
			return false;
		}
		
	}
	public function ApprovedCEIStatus($application_id){
		$cei_approved        = $this->find('all',array('conditions'=>array('application_id'=>$application_id,'stage'=>$this->APPROVED_FROM_CEI)))->distinct(['stage'])->first();
		if(!empty($cei_approved ))
		{
			return true;
		}
		else
		{
			return false;
		}
		
	}
	public function ApprovedCEIInspectionStatus($application_id){
		$cei_approved        = $this->find('all',array('conditions'=>array('application_id'=>$application_id,'stage'=>$this->CEI_INSPECTION_APPROVED)))->distinct(['stage'])->first();
		if(!empty($cei_approved ))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	public function ApprovedMeterInstallation($application_id){
		$status_meterinstallation        = $this->find('all',array('conditions'=>array('application_id'=>$application_id,'stage'=>$this->METER_INSTALLATION)))->distinct(['stage'])->first();
		if(!empty($status_meterinstallation ))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	public function Approvalstage($application_id){
	  $approve_stage    = $this->find('all',array('conditions'=>array('application_id'=>$application_id)))->distinct(['stage'])->toArray();
		$stage          = array();
		foreach($approve_stage as $value){
			$stage[]    = $value['stage'];
		}
		return $stage;
	}
	public function getLastStage($application_id)
	{
		$arr_data           = array();
		$last_stage         = $this->find('all',array('conditions'=>array('application_id'=>$application_id),'order'=>array('id'=>'desc')))->first();
		return ($last_stage);
	}
	public function getsubmittedStageData($application_id)
	{
		$arr_data           = array();
		$app_stage         = $this->find('all',array('conditions'=>array('application_id'=>$application_id,'stage'=>$this->APPLICATION_SUBMITTED),'order'=>array('id'=>'desc')))->first();
		return ($app_stage);
	}
	public function getgedaletterStageData($application_id)
	{
		$arr_data           = array();
		$ApplyonlinePayment = TableRegistry::get('ApplyonlinePayment');
		$app_stage          = $this->find('all',array('conditions'=>array('application_id'=>$application_id,'stage'=>$this->APPROVED_FROM_GEDA),'order'=>array('id'=>'desc')))->first();
		$payment_data       = $ApplyonlinePayment->find('all',array('conditions'=>array('application_id'=>$application_id),'order'=>array('id'=>'desc')))->first();
		if(!empty($payment_data) && isset($payment_data->payment_dt) && !empty($payment_data->payment_dt)) {
			$app_stage->created     = $payment_data->payment_dt;
		}
		return ($app_stage);
	}
	public function getApprovedBy($application_id)
	{
		$arr_data           = array();
		$app_stage          = $this->find('all',array('fields'=>'member.name','join'=>[['table'=>'members','alias'=>'member','conditions'=>'ApplyOnlineApprovals.member_id = member.id']],'conditions'=>array('application_id'=>$application_id,'stage'=>$this->APPROVED_FROM_GEDA)))->first();
		return ($app_stage);
	}
	public function getmeterInstalledStageData($application_id)
	{
		$arr_data           = array();
		$app_stage         = $this->find('all',array('conditions'=>array('application_id'=>$application_id,'stage'=>$this->METER_INSTALLATION),'order'=>array('id'=>'desc')))->first();
		return ($app_stage);
	}
	public function getgedaletterStageDataMIS($application_id)
	{
		$arr_data           = array();
		$ApplyonlinePayment = TableRegistry::get('ApplyonlinePayment');
		$ApplyOnlines 		= TableRegistry::get('ApplyOnlines');
		$app_stage          = $this->find('all',array('conditions'=>array('application_id'=>$application_id,'stage'=>$this->APPROVED_FROM_GEDA),'order'=>array('id'=>'desc')))->first();
		$applyOnlinesData  	= $ApplyOnlines->find('all',array('conditions'=>array('id'=>$application_id)))->first();
		$payment_data       = $ApplyonlinePayment->find('all',array('conditions'=>array('application_id'=>$application_id),'order'=>array('id'=>'desc')))->first();
		if($applyOnlinesData->application_status == $this->APPROVED_FROM_GEDA && ($applyOnlinesData->category!=$ApplyOnlines->category_residental || ($applyOnlinesData->social_consumer==1 && SOCIAL_SECTOR_PAYMENT==1)) && $applyOnlinesData->payment_status==0) {
			$app_stage->created = '';
		} else if(!empty($payment_data) && isset($payment_data->payment_dt) && !empty($payment_data->payment_dt)) {
			$app_stage->created     = $payment_data->payment_dt;
		}
		return ($app_stage);
	}
}
?>