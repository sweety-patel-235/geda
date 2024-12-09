<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Network\Email\Email;
use Cake\Utility\Security;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Auth\DefaultPasswordHasher;

class FesibilityReportTable extends AppTable
{
    var $table          = 'fesibility_report';
    var $RejectReason   = array("1"=>"Contract Load is less than the proposed RSPVS capacity",
                                "2"=>"Free DT Capacity is not available");        
    var $data               = array();
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
        
        $validator->notEmpty('recommended_capacity_by_discom','Recommended capacity by discom cannot be empty.');
        $validator->notEmpty('estimated_amount','Estimated amount cannot be empty.');
        $validator->notEmpty('estimated_due_date','Estimated due date cannot be empty.');
  
        if($this->data['recommended_capacity_by_discom'] > $this->data['pv_capacity']){
            $pv_data = $this->data['pv_capacity'];
            $validator->add("recommended_capacity_by_discom", [
                    "_empty" => [
                        "rule" => [$this, "customlessFunction"],
                        "message" => "Recommended Capacity by Discom must be less than of pv capacity ".$pv_data."."
                    ]
                ]
            );
        } 
        return $validator;
    }
    
    public function getReportData($id)
    {
        $fesibility_report  = $this->find('all',['conditions'=>['application_id'=>$id]])->first();
        return $fesibility_report;
    }
    public function GenerateApplicationNo($application,$state) {
        $StateCode  = $this->GetStateCode($application->application_id);
        $id         = $application->id;
        $id         = $StateCode."/RT/AH/1".str_pad($id, 7, "0", STR_PAD_LEFT);
        return $id;
    }
    public function GetStateCode($application) {
        $ApplyOnlines   = TableRegistry::get('ApplyOnlines');
        $States         = TableRegistry::get('States');
        $arrAppData     = $ApplyOnlines->find("all",['fields'=>['state','apply_state'],
                                            'conditions'=>['ApplyOnlines.id'=>$application]])->first();
    
        $STATENAME  = "";
        $arrState   = $States->find("list",['keyField'=>'id','valueField'=>'statename',
                                                'conditions'=>['States.id'=>$arrAppData->apply_state]])->toArray();
        if(!empty($arrState) && isset($arrState[$arrAppData->apply_state])) {
            $STATENAME = $arrState[$arrAppData->apply_state];
        } else {
            $STATENAME = $arrAppData->state;
        }

        $Code = "";
        if (!empty($STATENAME)) {
            switch (strtolower($STATENAME)) {
                case 'jharkhand':
                    $Code = "JH";
                    break;
                default:
                    $Code = strtoupper(substr($STATENAME,0,2));
                    break;
            }
        }
        return $Code;
    }
    public function customlessFunction($value, $context){
        return false;
    }
    public function fetchApiFeasibility($application_id,$debug=false) {
        $ApplyOnlines               = TableRegistry::get('ApplyOnlines');
        $applyOnlinesData           = $ApplyOnlines->viewApplication($application_id);

        $branch_master              = TableRegistry::get('BranchMasters');
        $branchDetails              = $branch_master->find('all',array('conditions'=>array('discom_id'=>$applyOnlinesData->area)))->first();
        $discom_id                  = $branchDetails->id;
        $thirpartyApi               = TableRegistry::get('ThirdpartyApiLog');
        $feasibility_api_data       = $thirpartyApi->searchFeasibilityApi($applyOnlinesData->consumer_no,$discom_id,$applyOnlinesData->project_id,$applyOnlinesData->id);
        $quotation_number           = '';
        $estimated_amount           = '';
        $estimated_due_date         = '';
        $flagFesibility             = 0;
        // if ($debug) {
        //     echo '<pre>';
        //     print_r($feasibility_api_data);
        //     echo "--\r\n".$feasibility_api_data->P_OUT_STS_CD;
        //     die;
        // }
        if($applyOnlinesData->discom != $ApplyOnlines->torent_ahmedabad && $applyOnlinesData->discom != $ApplyOnlines->torent_surat)
        {
            if(!empty($feasibility_api_data) && isset($feasibility_api_data->P_OUT_STS_CD))
            {
                if($feasibility_api_data->P_OUT_STS_CD == 1  || $feasibility_api_data->P_OUT_STS_CD == -1)
                {    
                    $output_details_obj     = $feasibility_api_data->P_OUT_DATA->OUTPUT_DATA;
                    $quotation_number       = $output_details_obj->SR_NUMBER;
                    $estimated_amount       = $output_details_obj->FQ_AMT;
                    $estimated_due_date     = $output_details_obj->FQ_DUE_DATE;
                    $payment_date           = $output_details_obj->FQ_PAID_DATE;
                    $sanction_load          = isset($output_details_obj->LOAD) ? $output_details_obj->LOAD : $applyOnlinesData->sanction_load_contract_demand;
                }
            }
            $flagFesibility                 = 1;
        }
        elseif($applyOnlinesData->discom == $ApplyOnlines->torent_ahmedabad || $applyOnlinesData->discom == $ApplyOnlines->torent_surat)
        {
            $feasibility_api_data       = $thirpartyApi->searchFeasibilityTorrentApi($applyOnlinesData->consumer_no,$discom_id,$applyOnlinesData->project_id,$applyOnlinesData->id,$applyOnlinesData->tno);
            if(!empty($feasibility_api_data) && isset($feasibility_api_data->OUTPUT_DATA))
            {
                $output_details_obj     = $feasibility_api_data->OUTPUT_DATA;
                $quotation_number       = $output_details_obj->SR_NUMBER;
                $estimated_amount       = $output_details_obj->FQ_AMT;
                $estimated_due_date     = !empty($output_details_obj->FQ_DUE_DATE) ? date('Y-m-d',strtotime($output_details_obj->FQ_DUE_DATE)) : '';
                $payment_date           = (isset($output_details_obj->FQ_APRV_DATE) && !empty($output_details_obj->FQ_APRV_DATE) && $output_details_obj->FQ_APRV_DATE != '1970-01-01' && $output_details_obj->FQ_APRV_DATE != '0000-00-00' && !is_null($output_details_obj->FQ_APRV_DATE)) ? date('Y-m-d',strtotime($output_details_obj->FQ_APRV_DATE)) : '';
                $sanction_load          = isset($output_details_obj->LOAD) ? $output_details_obj->LOAD : $applyOnlinesData->sanction_load_contract_demand;
                $flagFesibility         = (isset($output_details_obj->FQ_APRV_DATE) && !empty($output_details_obj->FQ_APRV_DATE) && $output_details_obj->FQ_APRV_DATE != '1970-01-01' && $output_details_obj->FQ_APRV_DATE != '0000-00-00' && !is_null($output_details_obj->FQ_APRV_DATE)) ? 1 : 0;
            }
        }
        if(trim($quotation_number)!='' && trim($estimated_amount)!='' && trim($estimated_due_date)!='' && $flagFesibility == 1)
        {
            $Members                = TableRegistry::get('Members');
            $members_data           = $Members->find('all',array('conditions'=>array('division'=>$applyOnlinesData->division,'subdivision'=>'0')))->first();
            $ApplyOnlines           = TableRegistry::get('ApplyOnlines');
            $applyOnline_data       = $ApplyOnlines->find('all',array('conditions'=>array('id'=>$application_id)))->first();
            $arrFesibility          = $this->find('all',array('conditions'=>array('application_id'=>$application_id)))->first();
            $NEWRECORD  = false;
            $pass_data  = array();
            $edit       = '';
            if (!empty($arrFesibility)) {
                $FesibilityReport                               = $this->get($arrFesibility->id);
                $FesibilityReport                               = $this->patchEntity($FesibilityReport,$pass_data);
                $FesibilityReport->id                           = $arrFesibility->id;
                $edit                                           = '1';
            } else {
                $FesibilityReport                               = $this->newEntity($pass_data);
                $NEWRECORD                                      = true;
                $FesibilityReport->created                      = $this->NOW();
                $FesibilityReport->created_by                   = $members_data->id;
            }
            $FesibilityReport->sanction_load                    = $sanction_load;//applyOnlinesData->sanction_load_contract_demand;
            $FesibilityReport->category                         = $applyOnlinesData->category;
            $FesibilityReport->recommended_capacity_by_discom   = $applyOnlinesData->pv_capacity;
            $FesibilityReport->field_officer                    = $applyOnlinesData->installer['installer_name'];
            $FesibilityReport->application_id                   = $application_id;
            $FesibilityReport->application_fee                  = $applyOnlinesData->disCom_application_fee;
            $FesibilityReport->quotation_number                 = $quotation_number;
            $FesibilityReport->estimated_amount                 = $estimated_amount;
            $FesibilityReport->estimated_due_date               = $estimated_due_date;
            $FesibilityReport->payment_date                     = $payment_date;
            $FesibilityReport->approved                         = '1';
            //$checked_date = strtotime($estimated_due_date.' -30 Days');
            $checked_date   = strtotime($applyOnline_data->created);
            if(!empty($payment_date) && $payment_date!='0000-00-00' && strtotime($payment_date) > $checked_date)
            {
               /* $payment                            = strtotime($payment_date);
                $EstimationGenerationDate           = strtotime($estimated_due_date);
                $arr_data_est                       = explode("-",$estimated_due_date);
                $expire_time = mktime(0,0,0,$arr_data_est[1],$arr_data_est[2]+32,$arr_data_est[0]);
               
              //  echo $NoDaysSinceApplicationEstimation.'<br>';
                if ($payment >=$EstimationGenerationDate && $payment <= $expire_time)
                {*/
                    $FesibilityReport->payment_approve              = '1';
                //}   
            }
            $FesibilityReport->modified                         = $this->NOW();
            $FesibilityReport->modified_by                      = $members_data->id;
            $FesibilityReport->division_approved_by             = $members_data->id;
            $FesibilityReport->division_approved_date           = $this->NOW();
            if ($this->save($FesibilityReport)) {
               // $this->CreateMyProject($application_id,true,true);

                $ApplyOnlineApprovals   = TableRegistry::get("ApplyOnlineApprovals");
                $fesibilityExist        = $ApplyOnlineApprovals->find('all',array('conditions'=>array('application_id'=>$application_id,'stage'=>$ApplyOnlineApprovals->FEASIBILITY_APPROVAL)))->first();
                if(empty($fesibilityExist))
                {
                    $this->SetApplicationStatus($ApplyOnlineApprovals->FIELD_REPORT_SUBMITTED,$application_id,'',$members_data->id);
                    $this->SetApplicationStatus($ApplyOnlineApprovals->FEASIBILITY_APPROVAL,$application_id,'',$members_data->id,$edit);
                    $this->SetApplicationStatus($ApplyOnlineApprovals->SUBSIDY_AVAILIBILITY,$application_id,'',$members_data->id);
                }
                //$this->Cei_All_Stage_APProved($application_id);
            }
            return '1';
        }
        return '0';
    }
    private function SetApplicationStatus($status,$id,$reason="",$discom_id,$edit_fesibility='')
    {  
        $member_id              = $discom_id;
        $ApplyOnlines           = TableRegistry::get("ApplyOnlines");
        $applyOnlinesData       = $ApplyOnlines->viewApplication($id);
        $ApplyOnlineApprovals   = TableRegistry::get("ApplyOnlineApprovals");
        if ($ApplyOnlineApprovals->validateNewStatus($status,$applyOnlinesData->application_status)) {
            $arrData            = array("application_status"=>$status);
           
            $ApplyOnlines->updateAll($arrData,['id' => $id]);
            $sms_text           = '';
            $subject            = '';
            $EmailVars          = array();
            if($status==$ApplyOnlineApprovals->FEASIBILITY_APPROVAL)
            {
                $sms_text           = str_replace('##geda_application_no##',$applyOnlinesData->geda_application_no,FEASIBILITY_APPROVAL);
                $subject            = "[REG: GEDA Application No. ".$applyOnlinesData->geda_application_no."] Technical Feasibility Report";
                $CUSTOMER_NAME      = trim($applyOnlinesData->customer_name_prefixed." ".$applyOnlinesData->name_of_consumer_applicant." ".$applyOnlinesData->last_name." ".$applyOnlinesData->third_name);
                $EmailVars          = array("CUSTOMER_NAME"=>$CUSTOMER_NAME,'TEXT_FESIBILITY'=>'done');
                $template_applied   = 'fesibility_approval';
                
            }
            if($edit_fesibility=='1')
            {
                $sms_text           = '';
            }
            if($sms_text!='')
            {
                if(!empty($applyOnlinesData->consumer_mobile))
                {
                    $ApplyOnlines->sendSMS($id,$applyOnlinesData->consumer_mobile,$sms_text);
                }
                if(!empty($applyOnlinesData->installer_mobile))
                {
                    //$ApplyOnlines->sendSMS($id,$applyOnlinesData->installer_mobile,$sms_text);
                }
            }
            if($subject!='')
            {
                if(!empty($applyOnlinesData->installer_email))
                {
                    $email          = new Email('default');
                    $email->profile('default');
                    $email->viewVars($EmailVars);
                    $message_send   = $email->template($template_applied, 'default')
                            ->emailFormat('html')
                            ->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
                            ->to(trim($applyOnlinesData->installer_email))
                            ->subject(Configure::read('EMAIL_ENV').$subject)
                            ->send();
                    /* Email Log */
                    $EmaillogTable             = TableRegistry::get("Emaillog");
                    $Emaillog                  = $EmaillogTable->newEntity();
                    $Emaillog->email           = $applyOnlinesData->installer_email;
                    $Emaillog->send_date       = $this->NOW();
                    $Emaillog->action          = Configure::read('EMAIL_ENV').$subject;
                    $Emaillog->description     = json_encode(array('EMAIL_ADDRESS' => $applyOnlinesData->installer_email,'EmailVars' => $EmailVars,'URL_HTTP'=>URL_HTTP));
                    $EmaillogTable->save($Emaillog);
                    /* Email Log */
                }
                $to     = trim($applyOnlinesData->consumer_email);
                if(empty($to))
                {
                    $to = trim($applyOnlinesData->email);
                }
                if(!empty($to))
                {
                    $email          = new Email('default');
                    $email->profile('default');
                    $email->viewVars($EmailVars);
                    $message_send   = $email->template($template_applied, 'default')
                            ->emailFormat('html')
                            ->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
                            ->to($to)
                            ->subject(Configure::read('EMAIL_ENV').$subject)
                            ->send();
                    /* Email Log */
                    $EmaillogTable             = TableRegistry::get("Emaillog");
                    $Emaillog                  = $EmaillogTable->newEntity();
                    $Emaillog->email           = $to;
                    $Emaillog->send_date       = $this->NOW();
                    $Emaillog->action          = Configure::read('EMAIL_ENV').$subject;
                    $Emaillog->description     = json_encode(array('EMAIL_ADDRESS' => $to,'EmailVars' => $EmailVars,'URL_HTTP'=>URL_HTTP));
                    $EmaillogTable->save($Emaillog);
                    /* Email Log */
                }
            }
        }
        $ApplyOnlineApprovals->saveStatus($id,$status,$member_id,$reason);
    }
    public function ValidateApplicationPayment($application_id)
    {
        $arrFesibility = $this->find('all',array('conditions'=>array('application_id'=>$application_id)))->first();
        if(!empty($arrFesibility))
        {
            return $arrFesibility->payment_approve;
        }
        else
        {
            return '0';
        }
    }
    public function Cei_All_Stage_APProved($application_id,$first_stage)
    {
        $ApplyOnlines           = TableRegistry::get("ApplyOnlines");
        $applyOnlinesData       = $ApplyOnlines->viewApplication($application_id);
        if($applyOnlinesData->pv_capacity <= '10'){
            $Members                = TableRegistry::get('Members');
            $members_data           = $Members->find('all',array('conditions'=>array('division'=>$applyOnlinesData->division,'subdivision'=>'0')))->first();
            $ApplyOnlineApprovals   = TableRegistry::get("ApplyOnlineApprovals");
            if($first_stage=='first')
            {
                $this->SetApplicationStatus($ApplyOnlineApprovals->DRAWING_APPLIED,$application_id,'',$members_data->id);
                $this->SetApplicationStatus($ApplyOnlineApprovals->APPROVED_FROM_CEI,$application_id,'',$members_data->id);
            }
            else if($first_stage=='second')
            {
                $this->SetApplicationStatus($ApplyOnlineApprovals->CEI_APP_NUMBER_APPLIED,$application_id,'',$members_data->id);
                $this->SetApplicationStatus($ApplyOnlineApprovals->CEI_INSPECTION_APPROVED,$application_id,'',$members_data->id);
            }
           
        }
    }
    /*
     * Function for CreateMyProject
     * @param mixed What page to display
     * @return void
     */
    private function CreateMyProject($application_id=0,$CreateMyProject=true,$is_fesibility=false)
    {
        if (!empty($application_id)) 
        {
            $ApplyOnlines           = TableRegistry::get("ApplyOnlines");
            $applyOnlinesData       = $ApplyOnlines->get($application_id);
            $applyOnlinesData->aid  = $ApplyOnlines->GenerateApplicationNo($applyOnlinesData);
            $proj_name              = "APPLICATION - ".$applyOnlinesData->aid;
            $lat                    = $applyOnlinesData->lattitue;
            $lon                    = $applyOnlinesData->longitude;
            $roof_area              = ($applyOnlinesData->pv_capacity * 12);
            $c_type                 = $applyOnlinesData->category;
            $energy_con             = !empty($applyOnlinesData->energy_con)?$applyOnlinesData->energy_con:0;
            $area_type              = '2002';
            $bill                   = $applyOnlinesData->bill;
            $backup_type            = 0;
            $hours                  = 0;
            $location_flag          = 'auto';
            $customer_id            = $applyOnlinesData->customer_id;
            $installer_id           = $applyOnlinesData->installer_id;
            $address                = $applyOnlinesData->address1;
            $city                   = $applyOnlinesData->city;
            $state                  = $applyOnlinesData->state;
            $state_short_name       = $applyOnlinesData->state;
            $pincode                = $applyOnlinesData->pincode;
            $country                = $applyOnlinesData->country;
            $SendQuery              = true;
            $request_data           = array();
            $request_data['Projects']['name']    = $proj_name;
            $Projects                                        = TableRegistry::get("Projects");
            if (!empty($applyOnlinesData->project_id)) {
                $request_data['Projects']['id']  = $applyOnlinesData->project_id;
                $project_details= $Projects->find('all',array('conditions'=>array('id'=>$applyOnlinesData->project_id)))->first();
                $request_data['proj_name']       = $project_details->name;
                $SendQuery                              = false;
            }
            $pv_app_capacity    = $applyOnlinesData->pv_capacity;
            if($is_fesibility == true)
            {
                $fesibility     = $this->find('all',array('conditions'=>array('application_id'=>$application_id)))->first();
                $pv_app_capacity= $fesibility->recommended_capacity_by_discom;
            }
            $request_data['latitude']                        = $lat;
            $request_data['Projects']['latitude']            = $lat;
            $request_data['longitude']                       = $lon;
            $request_data['Projects']['longitude']           = $lon;
            $request_data['customer_type']                   = $c_type;
            $request_data['project_type']                    = $c_type;
            $request_data['Projects']['customer_type']       = $c_type;
            $request_data['area']                            = $roof_area;
            $request_data['Projects']['area']                = $roof_area;
            $request_data['area_type']                       = $area_type;
            $request_data['bill']                            = $bill;
            $request_data['avg_monthly_bill']                = $bill;
            $request_data['backup_type']                     = $backup_type;
            $request_data['usage_hours']                     = $hours;
            $request_data['Projects']['usage_hours']         = $hours;
            $request_data['energy_con']                      = $energy_con;
            $request_data['Projects']['estimated_kwh_year']  = $energy_con;
            $request_data['recommended_capacity']            = $pv_app_capacity;
            $request_data['Projects']['recommended_capacity']= $pv_app_capacity;
            $request_data['address']                         = $address;
            $request_data['city']                            = $city;
            $request_data['state']                           = $state;
            $request_data['state_short_name']                = $state_short_name;
            $request_data['country']                         = $country;
            $request_data['postal_code']                     = $pincode;
            
            $result                                          = $Projects->getprojectestimationV2($request_data,$customer_id,$CreateMyProject);
            /** Update Project Ref. No in Table */
            if (empty($applyOnlinesData->project_id)) {
                $arrData    = array("project_id"=>$result['proj_id']);
                $ApplyOnlines->updateAll($arrData,['id' => $application_id]);
            }
            /** Update Project Ref. No in Table */
            /** Send Query to Installer */
            if ($SendQuery) $this->SendQueryToInstaller($result['proj_id'],$installer_id);
            /** Send Query to Installer */
            return $result;
        }
    }
}
?>