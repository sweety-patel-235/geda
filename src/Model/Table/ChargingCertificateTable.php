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

class ChargingCertificateTable extends AppTable
{
	var $table = 'charging_certificate';
	
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
        $validator->notEmpty('agreement_date', 'Agreement date cannot be blank.');
        $validator->notEmpty('meter_installed_date', 'Meter Installed date cannot be blank.');
        /*
        $validator->notEmpty('installer_id', 'Installer must be select.');
        $validator->notEmpty('disclaimer', 'Disclaimer can not be blank.');
        $validator->notEmpty('customer_name_prefixed', 'Customer Name Prefixed can not be blank.');
        //$validator->notEmpty('rooftop_area', 'Rooftop Area can not be blank.');
        $validator->notEmpty('area_unit', 'Area unit can not be blank.');
        $validator->notEmpty('sanctioned_load', 'Sanctioned load can not be blank.');
        $validator->notEmpty('sanctioned_load_unit', 'Sanctioned load unit can not be blank.');
        $validator->notEmpty('capacity', 'Capacity can not be blank.');
        $validator->notEmpty('name_of_consumer_applicant', 'Name Of Consumer Applicant can not be blank.');
        $validator->notEmpty('customer_name', 'Customer name can not be blank.');
        $validator->notEmpty('address1', 'Address 1 can not be blank.');
        //$validator->notEmpty('address2', 'Address 2 can not be blank.');
        $validator->notEmpty('comunication_address', 'Communication Address can not be blank.');
        $validator->notEmpty('city', 'City can not be blank.');
        $validator->notEmpty('category', 'Category must be select.');
        $validator->notEmpty('state', 'State can not be blank.');
        $validator->notEmpty('pincode', 'Pincode can not be blank.');
        $validator->notEmpty('mobile', 'Mobile can not be blank.');
        //$validator->notEmpty('landline_no', 'Landline no can not be blank.');
        $validator->notEmpty('email', 'Email can not be blank.');
        $validator->notEmpty('discom_name', 'Discom Name can not be blank.');
        $validator->notEmpty('consumer_no', 'Consumer No can not be blank.');
        //$validator->notEmpty('aadhar_no_or_pan_card_no', 'Aadhar no./PAN card no. can not be blank.');
        $validator->notEmpty('sanction_load_contract_demand', 'Sanction load contract demand can not be blank.');
        $validator->notEmpty('file_attach_recent_bill', 'Recent bill file required.');
        $validator->notEmpty('file_attach_latest_receipt', 'Latest receipt file required.');
        $validator->notEmpty('acknowledgement_tax_pay', 'Acknowledgement TAX pay can not be blank.');
        $validator->notEmpty('pv_capacity', 'PV Capacity can not be blank.');
        $validator->notEmpty('tod_billing_system', 'ToD billing system can not be blank.');
        $validator->notEmpty('avail_accelerated_depreciation_benefits', 'Avail accelerated depreciation benefits can not be blank.');
        $validator->notEmpty('payment_gateway', 'Payment gateway can not be blank.');
        $validator->notEmpty('disCom_application_fee', 'DisCom application fee can not be blank.');
        $validator->notEmpty('jreda_processing_fee', 'JREDA processing fee can not be blank.');
    	$validator->add("email", "validFormat", [
		    "rule" => ["email", false],
		    "message" => "Email must be valid."
		]);
        */
    	return $validator;
    }

    public function getReportData($id)
    {
        $ChargingCertificate   = $this->find('all',['conditions'=>['application_id'=>$id]])->first();
        return $ChargingCertificate;
    }

    public function GenerateApplicationNo($application,$state) {
        $StateCode  = $this->GetStateCode($state);
        $id         = $application->id;
        $YEAR       = date("y",strtotime($application->created))."-".(date("y",strtotime($application->created))+1);
        $id         = $StateCode."/RT/AH/REG/".$YEAR."/1".str_pad($id, 5, "0", STR_PAD_LEFT);
        return $id;
    }
    public function GetStateCode($state) {
        $Code = "";
        switch (strtolower($state)) {
            case 'jharkhand':
                $Code = "JH";
                break;
            default:
                $Code = strtoupper(substr($state,0,2));
                break;
        }
        return $Code;
    }
    public function fetchApiMeterInstallation($application_id) {
        $ApplyOnlines               = TableRegistry::get('ApplyOnlines');
        $applyOnlinesData           = $ApplyOnlines->viewApplication($application_id);

        $branch_master              = TableRegistry::get('BranchMasters');
        $branchDetails              = $branch_master->find('all',array('conditions'=>array('discom_id'=>$applyOnlinesData->area)))->first();
        
        $discom_id                  = $branchDetails->id;
        $flg_meter_data             = 0;
        $thirpartyApi               = TableRegistry::get('ThirdpartyApiLog');
        if($applyOnlinesData->discom != $ApplyOnlines->torent_ahmedabad && $applyOnlinesData->discom != $ApplyOnlines->torent_surat)
        {
            $meter_api_data             = $rethirpartyApi->searchMeterApi($applyOnlinesData->consumer_no,$discom_id,$applyOnlinesData->project_id,$applyOnlinesData->id);
            if(!empty($meter_api_data) && isset($meter_api_data->P_OUT_STS_CD))
            {
                if($meter_api_data->P_OUT_STS_CD == 1  || $meter_api_data->P_OUT_STS_CD == -1)
                {    
                    $output_details_obj         = $meter_api_data->P_OUT_DATA->OUTPUT_DATA;
                    $flg_meter_data     = 1;
                }
            }
        }
        elseif($applyOnlinesData->discom == $ApplyOnlines->torent_ahmedabad || $applyOnlinesData->discom == $ApplyOnlines->torent_surat)
        {      
            $meter_api_data             = $thirpartyApi->searchMeterTorrentApi($applyOnlinesData->consumer_no,$discom_id,$applyOnlinesData->project_id,$applyOnlinesData->id,$applyOnlinesData->tno);
            if(!empty($meter_api_data) && isset($meter_api_data->OUTPUT_DATA))
            {
                $output_details_obj     = $meter_api_data->OUTPUT_DATA;
                $flg_meter_data         = 1;
            }
        }
        if($flg_meter_data == 1 && isset($output_details_obj->METER_STATUS) && strtolower($output_details_obj->METER_STATUS)=='yes')
        {
            
            $Members                = TableRegistry::get('Members');
            $members_data           = $Members->find('all',array('conditions'=>array('division'=>$applyOnlinesData->division,'subdivision'=>'0')))->first();
            $arrCertificate         = $this->find('all',array('conditions'=>array('application_id'=>$application_id)))->first();
            $NEWRECORD  = false;
            $pass_data = array();
            if (!empty($arrCertificate)) {
                $CertificateReport                      = $this->get($arrCertificate->id);
                $CertificateReport                      = $this->patchEntity($CertificateReport,$pass_data);
                $CertificateReport->id                  = $arrCertificate->id;
            } else {
                $CertificateReport                      = $this->newEntity($pass_data);
                $NEWRECORD                              = true;
                $CertificateReport->created             = $this->NOW();
                $CertificateReport->created_by          = $members_data->id;
            }
            $CertificateReport->sanctioned_load_phase   = $applyOnlinesData->transmission_line;
            $CertificateReport->pv_capacity_phase       = $applyOnlinesData->transmission_line;
            $CertificateReport->application_id          = $application_id;
            $CertificateReport->modified                = $this->NOW();
            $CertificateReport->modified_by             = $members_data->id;
            $CertificateReport->meter_installed_date    = $output_details_obj->METER_INSTALL_DATE;
            $CertificateReport->agreement_date          = $output_details_obj->AGREEMENT_DATE;
            $CertificateReport->bi_directional_meter    = $output_details_obj->BI_DIRECTIONAL_METER;
            $CertificateReport->solar_meter             = $output_details_obj->SOLAR_METER;
            if ($this->save($CertificateReport)) {
                $certificateId          = $CertificateReport->id;
                $ApplyOnlineApprovals   = TableRegistry::get("ApplyOnlineApprovals");

                $this->SetApplicationStatus($ApplyOnlineApprovals->METER_INSTALLATION,$application_id,'',$members_data->id);
                $this->SetApplicationStatus($ApplyOnlineApprovals->APPROVED_FROM_DISCOM,$application_id,'',$members_data->id);
                $Installation           = TableRegistry::get('Installation');
                $Execution_data         = $Installation->find('all',array('conditions'=>array('project_id'=>$applyOnlinesData->project_id)))->first();
                if(!empty($Execution_data))
                {
                    $arrUpdate          = array();
                    if(empty($Execution_data->meter_serial_no))
                    {
                        $arrUpdate['meter_serial_no']       = $output_details_obj->BI_DIRECTIONAL_METER;
                    }
                    if(empty($Execution_data->solar_meter_serial_no))
                    {
                        $arrUpdate['solar_meter_serial_no'] = $output_details_obj->SOLAR_METER;
                    }
                    if(empty($Execution_data->bi_date) || $Execution_data->bi_date=='0000-00-00')
                    {
                        $arrUpdate['bi_date']               = $output_details_obj->METER_INSTALL_DATE;
                    }
                    if(empty($Execution_data->agreement_date) || $Execution_data->agreement_date=='0000-00-00')
                    {
                        $arrUpdate['agreement_date']        = $output_details_obj->AGREEMENT_DATE;
                    }   
                    if(!empty($arrUpdate))
                    {
                        $Installation->updateAll($arrUpdate,array('project_id'=>$applyOnlinesData->project_id));
                        $this->updateAll(array('update_execution'=>'1'),array('id'=>$certificateId));
                    }
                }
            }
            return '1';
        }
        return '0';
    }
    private function SetApplicationStatus($status,$id,$reason="",$discom_id)
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
            if($status==$ApplyOnlineApprovals->METER_INSTALLATION)
            {
                $sms_text           = str_replace('##geda_application_no##',$applyOnlinesData->geda_application_no,METER_INSTALLATION);
                $subject            = "[REG: GEDA Application No. ".$applyOnlinesData->geda_application_no."] Meter Installation Report";
                $CUSTOMER_NAME      = trim($applyOnlinesData->customer_name_prefixed." ".$applyOnlinesData->name_of_consumer_applicant." ".$applyOnlinesData->last_name." ".$applyOnlinesData->third_name);
                $EmailVars          = array("CUSTOMER_NAME"=>$CUSTOMER_NAME,'METER_INSTALLATION'=>'done');
                $template_applied   = 'meter_installation';
                
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
                $to     = $applyOnlinesData->consumer_email;
                if(empty($to))
                {
                    $to = $applyOnlinesData->email;
                }
                if(!empty($to))
                {
                    $email          = new Email('default');
                    $email->profile('default');
                    $email->viewVars($EmailVars);
                    $message_send   = $email->template($template_applied, 'default')
                            ->emailFormat('html')
                            ->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
                            ->to(trim($to))
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
}
?>