<?php
namespace App\Controller;
ini_set('memory_limit', '-1');
ini_set('max_execution_time', '-1');
ini_set('upload_max_filesize', '10');
set_time_limit(300);

use App\Controller\AppController;
use Cake\View\View;
use Cake\ORM\TableRegistry;
use Cake\ORM\Table;

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
use PHPExcel\PHPExcel;

class ReportsController extends FrontAppController
{
	public $user_department = array();
	public $arrDefaultAdminUserRights = array();
	public $helpers 		= array('Time','Html','Form','ExPaginator');
	public $PAGE_NAME 		= '';
	public $ExportFields 	= array();
	public $paginate 		= ['limit' => PAGE_RECORD_LIMIT,'order' => ['ApplyOnlines.id ' => 'desc']];
    public $arrCapacity 		= array('0'=>'0_3','1'=>'3_6','2'=>'6_10','3'=>'10_50','4'=>'50');
    public $arrCapacityLabel	= array('0'=>'1kW  to 3kW','1'=>'3kW to 6kW','2'=>'6kW to 10kW','3'=>'10kW to 50kW','4'=>'Above 50');
    public $arrDisStage 		= array('31'=>'No of Application Registered','23'=>'Documents Verified','9999'=>'Documents Verification Pending','6002'=>'Query Generated','0'=>'Querry Resolved','2000'=>'Estimate Issued','2111'=>'Estimate Paid','1000'=>'Self Certification Updated','17'=>'Meter Installed');
    public $arrSubDivStage 		= array('31'=>'Application Registered','23'=>'Documents Verified','6002'=>'Document Verified and Query Issued','2'=>'FQ Issued','2111'=>'FQ Paid','2222'=>'FQ Not Paid','6000'=>'Under Compliance','17'=>'Bi-directional Meter Installed','1777'=>'Agreement Submitted','99'=>'Application Cancelled'); //Under Compliance means query raised but not replied by installer, Agreement Submitted from meter installation data if agreement date come
    public $AllowedGedaIDS      = array('1324','1325','1326','1327','1328','1409','1410','1405');
   
   
	/*
	 * initialize controller
	 *
	 * @return void
	 */
	public function initialize()
    {
        // Always enable the CSRF component.
		parent::initialize();
		$this->loadComponent('Paginator');

        $this->loadModel('ApiToken');
		$this->loadModel('ApplyOnlines');
		$this->loadModel('DiscomMaster');
		$this->loadModel('FesibilityReport');
		$this->loadModel('RegistrationScheme');
		$this->loadModel('RegistrationSchemeDocument');
		$this->loadModel('WorkCompletion');
		$this->loadModel('WorkCompletionDocument');
		$this->loadModel('ChargingCertificate');
		$this->loadModel('Users');
		$this->loadModel('Userrole');
		$this->loadModel('Userroleright');
		$this->loadModel('ApplyOnlineApprovals');
		$this->loadModel('Adminaction');
		$this->loadModel('Parameters');
		$this->loadModel('BranchMasters');
		$this->loadModel('Members');
		$this->loadModel('Installers');
		$this->loadModel('Customers');
		$this->loadModel('States');
		$this->loadModel('Sessions');
		$this->loadModel('ApplyonlinDocs');
		$this->loadModel('Projects');
		$this->loadModel('InstallerProjects');
		$this->loadModel('CustomerProjects');
		$this->loadModel('Payumoney');
		$this->loadModel('ApplyonlinePayment');
		$this->loadModel('ApplyonlineMessage');
		$this->loadModel('Installation');
		$this->loadModel('WorkCompletion');
		$this->loadModel('CeiApplicationDetails');
		$this->loadModel('ThirdpartyApiLog');
		$this->loadModel('InstallerCategory');
		$this->loadModel('InstallerCategoryMapping');
		$this->loadModel('ApplyonlineMessages');
		$this->loadModel('DistrictNameMapping');
		$this->loadModel('Subsidy');
		$this->loadModel('MISReportData');
		$this->loadModel('ApplicationStages');
		$this->loadModel('Applications');
		$this->loadModel('Developers');
		$this->loadModel('ApplyOnlinesRfidData');
		$this->loadModel('ApplicationCategory');
		$this->set('ApplyonlineMessage',$this->ApplyonlineMessage);
		$this->set('InspectionReport',$this->InspectionReport);
		$this->set('Userright',$this->Userright);

		$customer_type 	= $this->Session->read('Customers.customer_type');
		$this->set("customer_type",$customer_type);

		$member_type 	= $this->Session->read('Members.member_type');
		$area 			= $this->Session->read('Members.area');
		$circle 		= $this->Session->read('Members.circle');
		$division 		= $this->Session->read('Members.division');
		$subdivision 	= $this->Session->read('Members.subdivision');
		$section 		= $this->Session->read('Members.section');

		$this->set("JREDA",$this->ApplyOnlines->JREDA);
		$this->set("DISCOM",$this->ApplyOnlines->DISCOM);
		$this->set("CEI",$this->ApplyOnlines->CEI);
		$this->set("MStatus",$this->ApplyOnlineApprovals);
		$this->set("member_type",$member_type);
		$this->set("area",$area);
		$this->set("circle",$circle);
		$this->set("division",$division);
		$this->set("subdivision",$subdivision);
		$this->set("section",$section);
		//$this->set("arrReportFields",$this->arrReportFields);

		$is_installer = false;
		if ($customer_type == "installer") {
			$is_installer = true;
			$this->set("arrReportFields",$this->MISReportData->arrReportFieldsIns);
		}
		else
		{
			$this->set("arrReportFields",$this->MISReportData->arrReportFields);
		}
		$this->set("is_installer",$is_installer);
		$this->set("customer_types",array("customer","installer"));
    }

    private function GetReportFields()
    {
    	$this->ExportFields = isset($this->request->data['mis_export_fields']) && !empty($this->request->data['mis_export_fields'])?$this->request->data['mis_export_fields']:$this->DefaultExportFields;
    	$ReportFields 		= " SELECT AO.id,'125MW' AS 'Scheme',AO.created,AO.id,MIS.app_created_date ";
		if (in_array("submited_date",$this->ExportFields)) {
			$ReportFields .= ",MIS.submited_date";
		}
		if (in_array("geda_application_no",$this->ExportFields)) {
			$ReportFields .= ",AO.geda_application_no";
		}
		if (in_array("application_no",$this->ExportFields)) {
			$ReportFields .= ",AO.application_no";
		}
		if (in_array("application_status",$this->ExportFields)) {
			$ReportFields .= ",AO.application_status";
		}
		if (in_array("installer_name",$this->ExportFields)) {
			$ReportFields .= ",INS.installer_name";
		}
		if (in_array("installer_category",$this->ExportFields)) {
			$ReportFields .= ",IF (ICM.category_id = 1,'A','B') AS installer_category";
		}
		if (in_array("allowed_bands",$this->ExportFields)) {
			$ReportFields .= ",ICM.allowed_bands";
		}
		if (in_array("name",$this->ExportFields)) {
			$ReportFields .= ",P.name";
		}
		if (in_array("project_name",$this->ExportFields)) {
			$ReportFields .= ",P.name as project_name";
		}
		if (in_array("project_area",$this->ExportFields)) {
			$ReportFields .= ",P.area as project_area";
		}
		if (in_array("project_areatype",$this->ExportFields)) {
			$ReportFields .= ",P.area_type as project_areatype";
		}
		if (in_array("avg_monthly_bill",$this->ExportFields)) {
			$ReportFields .= ",P.avg_monthly_bill";
		}
		if (in_array("estimated_kwh_year",$this->ExportFields)) {
			$ReportFields .= ",P.estimated_kwh_year";
		}
		if (in_array("pv_capacity",$this->ExportFields)) {
			$ReportFields .= ",AO.pv_capacity";
		}
		if (in_array("DisCom_Name",$this->ExportFields)) {
			$ReportFields .= ",DM.title as DisCom_Name";
		}
		if (in_array("consumer_no",$this->ExportFields)) {
			$ReportFields .= ",AO.consumer_no";
		}
		if (in_array("division_title",$this->ExportFields)) {
			$ReportFields .= ",D.title as division_title";
		}
		if (in_array("subdiv_title",$this->ExportFields)) {
			$ReportFields .= ",SD.title as subdiv_title";
		}
		if (in_array("sanction_load_contract_demand",$this->ExportFields)) {
			$ReportFields .= ",AO.sanction_load_contract_demand";
		}
		if (in_array("Invertor_Phase",$this->ExportFields)) {
			$ReportFields .= ",IF (AO.transmission_line = 3, '3 Phase',(IF(AO.transmission_line = 1,'Single Phase','-'))) as Invertor_Phase";
		}
		if (in_array("App_Category",$this->ExportFields)) {
			$ReportFields .= ",PM.para_value as App_Category";
		}
		if (in_array("Net_Meter_By",$this->ExportFields)) {
			$ReportFields .= ",IF (AO.net_meter = 1, 'DisCom',IF (AO.net_meter = 2,'Installer/EA','-')) as Net_Meter_By";
		}
		if (in_array("latitude",$this->ExportFields)) {
			$ReportFields .= ",P.latitude";
		}
		if (in_array("longitude",$this->ExportFields)) {
			$ReportFields .= ",P.longitude";
		}
		if (in_array("consumer_email",$this->ExportFields)) {
			$ReportFields .= ",AO.consumer_email";
		}
		if (in_array("consumer_mobile",$this->ExportFields)) {
			$ReportFields .= ",AO.consumer_mobile";
		}
		if (in_array("installer_email",$this->ExportFields)) {
			$ReportFields .= ",AO.installer_email";
		}
		if (in_array("installer_mobile",$this->ExportFields)) {
			$ReportFields .= ",AO.installer_mobile";
		}
		if (in_array("Name_Prefix",$this->ExportFields)) {
			$ReportFields .= ",AO.customer_name_prefixed AS Name_Prefix";
		}
		if (in_array("First_Name",$this->ExportFields)) {
			$ReportFields .= ",AO.name_of_consumer_applicant AS First_Name";
		}
		if (in_array("Middle_Name",$this->ExportFields)) {
			$ReportFields .= ",AO.last_name AS Middle_Name";
		}
		if (in_array("Last_Name",$this->ExportFields)) {
			$ReportFields .= ",AO.third_name AS Last_Name";
		}
		if (in_array("landline_no",$this->ExportFields)) {
			$ReportFields .= ",AO.landline_no AS landline_no";
		}
		if (in_array("Street_House_No",$this->ExportFields)) {
			$ReportFields .= ",AO.address1 AS Street_House_No";
		}
		if (in_array("Taluka",$this->ExportFields)) {
			$ReportFields .= ",AO.address2 AS Taluka";
		}
		if (in_array("District",$this->ExportFields)) {
			$ReportFields .= ",AO.district AS District";
		}
		if (in_array("City_Village",$this->ExportFields)) {
			$ReportFields .= ",AO.city AS City_Village";
		}
		if (in_array("State",$this->ExportFields)) {
			$ReportFields .= ",AO.state AS State";
		}
		if (in_array("Pin",$this->ExportFields)) {
			$ReportFields .= ",AO.pincode AS Pin";
		}
		if (in_array("comunication_address",$this->ExportFields)) {
			$ReportFields .= ",IF (AO.comunication_address_as_above = 1, 'Same',AO.comunication_address) as comunication_address";
		}
		if (in_array("Profile_Photo",$this->ExportFields)) {
			$ReportFields .= ",MIS.Profile_Photo";
		}
		if (in_array("Premises",$this->ExportFields)) {
			$ReportFields .= ",IF (AO.owned_rented = 1, 'Rented','Owned') as Premises";
		}
		if (in_array("Electricity_Bill",$this->ExportFields)) {
			$ReportFields .= ",IF (AO.attach_recent_bill IS NULL OR AO.attach_recent_bill = '','No','Yes') as Electricity_Bill";
		}
		if (in_array("Aadhaar_No_Entered",$this->ExportFields)) {
			$ReportFields .= ",IF (AO.aadhar_no_or_pan_card_no IS NULL OR AO.aadhar_no_or_pan_card_no = '','No','Yes') as Aadhaar_No_Entered";
		}
		if (in_array("Self_Owned",$this->ExportFields)) {
			$ReportFields .= ",IF (AO.capexmode = 1, 'Yes', 'No') AS Self_Owned";
		}
		if (in_array("No_Subsidy_Required",$this->ExportFields)) {
			$ReportFields .= ",IF (AO.disclaimer_subsidy = 1, 'Yes', 'No') AS No_Subsidy_Required";
		}
		if (in_array("OTP_Verified_On",$this->ExportFields)) {
			$ReportFields .= ",MIS.otp_verified_on AS OTP_Verified_On";
		}
		if (in_array("Document_Verified_Date",$this->ExportFields)) {
			$ReportFields .= ",MIS.document_verified_date AS Document_Verified_Date";
		}
		if (in_array("Signed_Uploaded_Date",$this->ExportFields)) {
			$ReportFields .= ",MIS.signed_uploaded_date AS Signed_Uploaded_Date";
		}
		if (in_array("Last_Comment",$this->ExportFields)) {
			$ReportFields .= ",MIS.last_comment AS Last_Comment";
		}
		if (in_array("Last_Comment_Date",$this->ExportFields)) {
			$ReportFields .= ",MIS.last_comment_date AS Last_Comment_Date";
		}
		if (in_array("Last_Comment_Replied_Date",$this->ExportFields)) {
			$ReportFields .= ",MIS.last_comment_replied_date AS Last_Comment_Replied_Date";
		}
		if (in_array("Fesibility_Report_Date",$this->ExportFields)) {
			$ReportFields .= ",MIS.fesibility_report_date AS Fesibility_Report_Date";
		}
		if (in_array("Quotation_No",$this->ExportFields)) {
			$ReportFields .= ",MIS.quotation_no AS Quotation_No";
		}
		if (in_array("Discom_Estimation",$this->ExportFields)) {
			$ReportFields .= ",MIS.discom_estimation AS Discom_Estimation";
		}
		if (in_array("Payment_Due_Date",$this->ExportFields)) {
			$ReportFields .= ",MIS.payment_due_date AS Payment_Due_Date";
		}
		if (in_array("Payment_Received",$this->ExportFields)) {
			$ReportFields .= ",MIS.payment_received AS Payment_Received";
		}
		if (in_array("Payment_Date",$this->ExportFields)) {
			$ReportFields .= ",MIS.payment_date AS Payment_Date";
		}
		if (in_array("Self_Certificate",$this->ExportFields)) {
			$ReportFields .= ",IF (AO.pv_capacity <=10, ((CASE WHEN 1 = 1 THEN (select created FROM apply_online_approvals WHERE application_id = AO.id and apply_online_approvals.stage = ".$this->ApplyOnlineApprovals->APPROVED_FROM_CEI." ORDER BY apply_online_approvals.id DESC LIMIT 1)END)),'')  AS Self_Certificate";
		}
		if (in_array("drawing_app_no",$this->ExportFields)) {
			$ReportFields .= ",IF (AO.pv_capacity >10, MIS.drawing_app_no,'')  AS drawing_app_no";
		}
		if (in_array("drawing_approved_date",$this->ExportFields)) {
			//$ReportFields .= ",MIS.drawing_approved_date AS drawing_approved_date";
			$ReportFields .= ",IF (AO.pv_capacity >10, ((CASE WHEN 1 = 1 THEN (select created FROM apply_online_approvals WHERE application_id = AO.id and apply_online_approvals.stage = ".$this->ApplyOnlineApprovals->APPROVED_FROM_CEI." ORDER BY apply_online_approvals.id DESC LIMIT 1)END)),'')  AS drawing_approved_date";
		}
		if (in_array("cei_app_no",$this->ExportFields)) {
			$ReportFields .= ",IF (AO.pv_capacity >10, ((CASE WHEN 1 = 1 THEN (select cei_app_no FROM cei_application_details WHERE application_id = AO.id  ORDER BY cei_application_details.id DESC LIMIT 1)END)),'') AS cei_app_no";
		}
		if (in_array("cei_approved_date",$this->ExportFields)) {
			//$ReportFields .= ",MIS.drawing_approved_date AS drawing_approved_date";
			$ReportFields .= ",IF (AO.pv_capacity >10, ((CASE WHEN 1 = 1 THEN (select created FROM apply_online_approvals WHERE application_id = AO.id and apply_online_approvals.stage = ".$this->ApplyOnlineApprovals->CEI_INSPECTION_APPROVED." ORDER BY apply_online_approvals.id DESC LIMIT 1)END)),'')  AS cei_approved_date";
		}
		if (in_array("workorder_number",$this->ExportFields)) {
			$ReportFields .= ",MIS.workorder_number AS workorder_number";
		}
		if (in_array("workorder_number_date",$this->ExportFields)) {
			$ReportFields .= ",MIS.workorder_number_date AS workorder_number_date";
		}
		if (in_array("installation_start_date",$this->ExportFields)) {
			$ReportFields .= ",MIS.installation_start_date AS installation_start_date";
		}
		if (in_array("installation_end_data",$this->ExportFields)) {
			$ReportFields .= ",MIS.installation_end_data AS installation_end_data";
		}
		if (in_array("meter_serial_no_make",$this->ExportFields)) {
			$ReportFields .= ",MIS.meter_serial_no_make AS meter_serial_no_make";
		}
		if (in_array("meter_serial_no",$this->ExportFields)) {
			$ReportFields .= ",MIS.meter_serial_no AS meter_serial_no";
		}
		if (in_array("solar_meter_manufacture",$this->ExportFields)) {
			$ReportFields .= ",MIS.solar_meter_manufacture AS solar_meter_manufacture";
		}
		if (in_array("solar_meter_serial_no",$this->ExportFields)) {
			$ReportFields .= ",MIS.solar_meter_serial_no AS solar_meter_serial_no";
		}
		if (in_array("meter_installed_date",$this->ExportFields)) {
			$ReportFields .= ",MIS.meter_installed_date AS meter_installed_date";
		}
		if (in_array("agreement_date",$this->ExportFields)) {
			$ReportFields .= ",MIS.agreement_date AS agreement_date";
		}
		if (in_array("approval_id",$this->ExportFields)) {
			$ReportFields .= ",AO.approval_id AS approval_id";
		}
		if (in_array("pcr_code",$this->ExportFields)) {
			$ReportFields .= ",AO.pcr_code AS pcr_code";
		}
		if (in_array("msme",$this->ExportFields)) {
			$ReportFields .= ",A.msme AS msme";
		}
		return $ReportFields;
    }

    public function fetch_data($array_request,$return_count=0)
    {
    	$member_id 			= $this->Session->read("Members.id");
    	$customer_id 		= $this->Session->read("Customers.id");
		$state 				= '';
		$branch_id 			= '';
		$main_branch_id		= '';
		$member_type 		= '';

		$member_type 	= $this->Session->read('Members.member_type');
		$area 			= $this->Session->read('Members.area');
		$circle 		= $this->Session->read('Members.circle');
		$division 		= $this->Session->read('Members.division');
		$subdivision 	= $this->Session->read('Members.subdivision');
		$section 		= $this->Session->read('Members.section');

		if($this->Session->check("Members.state")){
			$state 		= $this->Session->read("Members.state");
		}
		if($this->Session->check("Members.member_type")){
			$member_type = $this->Session->read("Members.member_type");
		}

		$main_branch_id = array();
		if (!empty($member_id) && $member_type == $this->ApplyOnlines->DISCOM)
		{
			$field      = "area";
			$id         = $area;

			if (!empty($section)) {
				$field      = "section";
				$id         = $section;
			} else if (!empty($subdivision)) {
				$field      = "subdivision";
				$id         = $subdivision;
			} else if (!empty($division)) {
				$field      = "division";
				$id         = $division;
			} else if (!empty($circle)) {
				$field      = "circle";
				$id         = $circle;
			}
			$main_branch_id = array("field"=>$field,"id"=>$id);
		}
		$DateField 			= isset($array_request['DateField'])?$array_request['DateField']:'';
		$from_date 			= isset($array_request['DateFrom'])?$array_request['DateFrom']:'';
		$end_date 			= isset($array_request['DateTo'])?$array_request['DateTo']:'';
		$fields_date 		= isset($array_request['DateField'])?$array_request['DateField']:'';
		$fields_date  		= "apply_online_approvals.created";
		if (!empty($DateField) && in_array($DateField,array("apply_online_approvals.created","charging_certificate.meter_installed_date"))) {
			$fields_date 	= $DateField;
		}

		$whereCharging 		= '';
		if($fields_date != 'apply_online_approvals.created' && !empty($from_date) && !empty($end_date))
	    {
	    	$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
			$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
	    	$whereCharging 	= ' and '.$fields_date.' between '.$StartTime.' and '.$EndTime;
	    }

    	$connection         = ConnectionManager::get('default');
    	if(!empty($customer_id))
		{
			$arrRequestSelected = isset($this->request->data['mis_export_fields']) && !empty($this->request->data['mis_export_fields'])?$this->request->data['mis_export_fields']:$this->MISReportData->DefaultExportFieldsIns;
		}
		else
		{
			$arrRequestSelected = isset($this->request->data['mis_export_fields']) && !empty($this->request->data['mis_export_fields'])?$this->request->data['mis_export_fields']:$this->MISReportData->DefaultExportFields;
		}
    	$sql_first 			= $this->MISReportData->GetReportFields($arrRequestSelected);
		$social_consumer 	= '';
		if (!empty($member_id)) {
			if(in_array($member_id,$this->AllowedGedaIDS)) {
	        } else {
	        	//$social_consumer = " and P.project_social_consumer !='1'";
	        }
		} else {
			//$social_consumer = " and P.project_social_consumer !='1'";
		}
		$sql_count 	= "	select count(0)";
		$sql 		= "	FROM apply_onlines AO
						INNER JOIN projects as P ON P.id = AO.project_id
						INNER JOIN installers as INS ON INS.id = AO.installer_id
						INNER JOIN installer_category_mapping as ICM ON ICM.installer_id = INS.id
						INNER JOIN parameters as PM ON AO.category = PM.para_id
						INNER JOIN branch_masters as DM ON AO.discom = DM.id
						LEFT JOIN discom_master as D ON AO.division = D.id
						LEFT JOIN discom_master as C ON AO.circle = C.id
						LEFT JOIN discom_master as SD ON AO.subdivision = SD.id
						LEFT JOIN fesibility_report as fea ON AO.id = fea.application_id
						LEFT JOIN mis_report_data as MIS ON AO.id = MIS.application_id
						LEFT JOIN apply_online_approvals  ON AO.id = apply_online_approvals.application_id
						LEFT JOIN charging_certificate on  AO.id = charging_certificate.application_id
						LEFT JOIN apply_onlines_others as A ON AO.id = A.application_id
						WHERE AO.application_status not in ('99','29','30','22','0')
						and AO.application_status is not NULL $social_consumer ";
		$application_status = (isset($array_request['status']) && !empty($array_request['status']))?$array_request['status']:'1';
		$installer_name 	= isset($array_request['installer_name_multi'])?$array_request['installer_name_multi']:'';
		$application_no 	= isset($array_request['application_no'])?$array_request['application_no']:'';
		$geda_application_no= isset($array_request['geda_application_no'])?$array_request['geda_application_no']:'';
		$payment_status 	= isset($array_request['payment_status'])?$array_request['payment_status']:'';
		$govt_agency 		= isset($array_request['govt_agency'])?$array_request['govt_agency']:'';
		if(!empty($main_branch_id))
		{
			$sql .= " and AO.".$main_branch_id['field']." = '".$main_branch_id['id']."'";
			$sql .= " and (AO.payment_status = '1' or AO.category='3001')";
		}
		$sql .= " and apply_online_approvals.stage = '1'";
		if(!empty($application_status))
		{
			$passStatus = $this->ApplyOnlines->apply_online_status_key[$application_status];
	        if($passStatus == '9999')
	        {
	            $sql .= " and AO.application_status = '".$this->ApplyOnlineApprovals->APPLICATION_SUBMITTED."'";
	        }
	        else
	        {
	        	if($fields_date == 'apply_online_approvals.created' && !empty($from_date) && !empty($end_date)) {
	            	$FindApplicationIDs     = $this->ApplyOnlineApprovals->find('list',['keyField'=>'id','valueField'=>'application_id','conditions'=>['stage'=>$this->ApplyOnlines->apply_online_status_key[$application_status]]]);
	            	$StartTime    		= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
					$EndTime    		= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
		    		$FindApplicationIDs->where([function ($exp, $q) use ($StartTime, $EndTime,$fields_date) {
						return $exp->between('ApplyOnlineApprovals.created', $StartTime, $EndTime);
			   		}]);
			   		$FindApplicationIDs = $FindApplicationIDs->toArray();
			   		if (!empty($FindApplicationIDs)) {
		                $sql .= " and AO.id IN (".implode(",",array_unique($FindApplicationIDs)).")";
		                if($passStatus != $this->ApplyOnlineApprovals->APPLICATION_CANCELLED)
		                {
		                    $sql .= " and AO.application_status != '".$this->ApplyOnlineApprovals->APPLICATION_CANCELLED."'";
		                }
		            } else {
		                $sql .= " and AO.id = '0'";
		            }
	            }
	            if($fields_date == 'charging_certificate.meter_installed_date' && !empty($from_date) && !empty($end_date)) {
	            	$StartTime    		= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
					$EndTime    		= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
	            	$sql .= " and charging_certificate.meter_installed_date BETWEEN '".$StartTime."' AND '".$EndTime."' ";
	            }
	        }
		}
		if(!empty($installer_name))
	   	{
	   		$sql .= " and AO.installer_id in ('".implode("','",$installer_name)."') ";
	   	}
	   	if(!empty($application_no))
	   	{
	   		$sql .= " and AO.application_no like '%".$application_no."%'";
	   	}
	   	if(!empty($geda_application_no))
	   	{
	   		$sql .= " and AO.geda_application_no like '%".$geda_application_no."%'";
	   	}
	   	if($payment_status!='')
	   	{
	   		$sql .= " and fea.payment_approve = '".$payment_status."'";
	   	}
	   	if($govt_agency!='') {
	   		$sql .= " and AO.govt_agency = '".$govt_agency."'";
        }
	   	if(!empty($customer_id))
	   	{
	   		$installerdata	= $this->Customers->find('all', array('fields'=>array('installer_id'),'conditions'=>array('id'=>$customer_id)))->first();
			$installer_id 	= (isset($installerdata['installer_id'])?$installerdata['installer_id']:0);
			$sql .= " and AO.installer_id='".$installer_id."'";
	   	}
	   //	$sql 		= $sql.$whereCharging;
	   	if($return_count==0) {
	   		$sql .= " order by app_created_date desc, AO.id desc ";
	   		$applicationData_output = $connection->execute($sql_first.$sql)->fetchAll('assoc');
	   		return $applicationData_output;
	   	} else {
	   		
	   		$applicationData_count = $connection->execute($sql_count.$sql)->fetchAll('assoc');
	   		return $applicationData_count[0]['count(0)'];
	   	}
    }

    private function GetExcelColumnName($num)
    {
		$str 			= '';
		$DEFAULT_NUMBER = 64;
		while ($num > 0) {
			$Module = ($num % 26);
			$Module = ($Module > 0?$Module:26);
			$str 	= chr( $Module + $DEFAULT_NUMBER) . $str;
			$num 	= (int) ($num / 26);
		}
		return trim($str);
    }

	public function getreportfromexel()
	{

		$application_cnt 	= $this->fetch_data($this->request->data,1);
		$customer_id 		= $this->Session->read("Customers.id");
		if($application_cnt>5000)
		{
			$this->Flash->error('At a time only 5000 records must be download.');
			return $this->redirect('/reports/MISReport');
		}
		else
		{
			$applicationData 	= $this->fetch_data($this->request->data);

			$PhpExcel 			= new \PHPExcel();
			$PhpExcel->createExcel();
			$objDrawing 		= new \PHPExcel_Worksheet_MemoryDrawing();
			$objDrawing->setCoordinates('A1');
			$objDrawing->setWorksheet($PhpExcel->getExcelObj()->getActiveSheet());
			$j 					= 1;
			$i 					= 1;
			$arrExportFields 	= $this->MISReportData->ExportFields;
			$arrReportFields 	= $this->MISReportData->arrReportFields;
			if(!empty($customer_id))
			{
				$arrReportFields 	= $this->MISReportData->arrReportFieldsIns;
			}
			
			//foreach ($this->ExportFields as $Field_Name) {
			foreach ($arrExportFields as $Field_Name) {
				$RowName 	= $this->MISReportData->GetExcelColumnName($i);
				$ColTitle  	= isset($arrReportFields[$Field_Name])?$arrReportFields[$Field_Name]:"";
				$PhpExcel->writeCellValue($RowName.$j,$ColTitle);
				$PhpExcel->getExcelObj()->getActiveSheet()->getColumnDimension($RowName)->setAutoSize(true);
				$i++;
			}
			$j++;
			foreach($applicationData as $key=>$application_data) {
				$this->WriteReportData($PhpExcel,$j,$application_data);
				$j++;
			}
			$PhpExcel->downloadFile(time());
			exit;
		}
	}

    private function WriteReportData($PhpExcel,$RowID,$Report_Data)
    {
    	$i = 1;
    	foreach ($this->MISReportData->ExportFields as $Field_Name) {
    		$RowName = $this->MISReportData->GetExcelColumnName($i);
    		$RowData = "";
    		switch ($Field_Name) {
    			case 'sr_no':
    				$RowData = ($RowID-1);
    				break;
    			case 'application_status':
    				$RowData = isset($this->ApplyOnlineApprovals->application_status[$Report_Data[$Field_Name]])?$this->ApplyOnlineApprovals->application_status[$Report_Data[$Field_Name]]:"";
    				break;
				case 'approval_id':
					$RowData = isset($this->Subsidy->SPIN_APPROVAL[$Report_Data[$Field_Name]]['no'])?$this->Subsidy->SPIN_APPROVAL[$Report_Data[$Field_Name]]['no']:"";
				break;
				case 'geda_application_no':
					$detailsData 	= $this->ApplyOnlines->find('all',
														array('fields'	=>array('payment_status'),
															'conditions'=>array('id'=>$Report_Data['id'])))->first();
					
					$RowData = ($detailsData->payment_status != 1) ? '-' : $Report_Data[$Field_Name];
				break;
    			default:
    				$RowData = isset($Report_Data[$Field_Name])?$Report_Data[$Field_Name]:"";
    				break;
    		}
			if ($RowData == "0000-00-00 00:00:00" || $RowData == "0000-00-00") {
				$RowData = "";
			}
			$PhpExcel->getActiveSheet()->setCellValue($RowName.$RowID,$RowData);
    		$i++;
    	}
    }

	public function MISReport()
    {
    	$member_id 			= $this->Session->read("Members.id");
    	$customer_id 		= $this->Session->read("Customers.id");
		$state 				= '';
		$branch_id 			= '';
		$main_branch_id		= '';
		$member_type 		= '';

		$member_type 	= $this->Session->read('Members.member_type');
		$area 			= $this->Session->read('Members.area');
		$circle 		= $this->Session->read('Members.circle');
		$division 		= $this->Session->read('Members.division');
		$subdivision 	= $this->Session->read('Members.subdivision');
		$section 		= $this->Session->read('Members.section');
		if(empty($member_id) && empty($customer_id)) {
			return $this->redirect('home');
		}
		if($this->Session->check("Members.state")){
			$state = $this->Session->read("Members.state");
		}
		if($this->Session->check("Members.member_type")){
			$member_type = $this->Session->read("Members.member_type");
		}
		$main_branch_id = array();
		if (!empty($member_id) && $member_type == $this->ApplyOnlines->DISCOM)
		{
			$field      = "area";
			$id         = $area;
			if (!empty($section)) {
				$field      = "section";
				$id         = $section;
			} else if (!empty($subdivision)) {
				$field      = "subdivision";
				$id         = $subdivision;
			} else if (!empty($division)) {
				$field      = "division";
				$id         = $division;
			} else if (!empty($circle)) {
				$field      = "circle";
				$id         = $circle;
			}
			$main_branch_id = array("field"=>$field,"id"=>$id);
		}
		$DateField 			= isset($this->request->data['DateField'])?$this->request->data['DateField']:'';
		$from_date 			= isset($this->request->data['DateFrom'])?$this->request->data['DateFrom']:'';
		$end_date 			= isset($this->request->data['DateTo'])?$this->request->data['DateTo']:'';
		$application_status = isset($this->request->data['status'])?$this->request->data['status']:'';
		$installer_name 	= (isset($this->request->data['installer_name_multi']) && !empty($this->request->data['installer_name_multi']))?explode(",",$this->request->data['installer_name_multi']):'';
		$application_no 	= isset($this->request->data['application_no'])?$this->request->data['application_no']:'';
		$geda_application_no= isset($this->request->data['geda_application_no'])?$this->request->data['geda_application_no']:'';
		$payment_status		= isset($this->request->data['payment_status'])?$this->request->data['payment_status']:'';
		$govt_agency		= isset($this->request->data['govt_agency'])?$this->request->data['govt_agency']:0;

        $arrAdminuserList	= array();
        $arrUserType		= array();
        $arrCondition		= array();
        $this->SortBy		= "apply_online_approvals.created";
        $this->Direction	= "DESC";
        $this->intLimit		= 50;
        $this->CurrentPage  = 1;
        $option 			= array();

        $option['colName']  = array('id','application_no','geda_application_no','application_status','installer_name','submitted_on');
        $sortArr=array('installer_name'=>'installers.installer_name','submitted_on'=>'apply_online_approvals.created');
        $this->SetSortingVars('ApplyOnlines',$option,$sortArr);
        $arrCondition		= array('application_status not in'=> array('99','29','30','22','0'));
        if(!empty($main_branch_id))
		{
 			array_push($arrCondition, array('ApplyOnlines.'.$main_branch_id['field']=>$main_branch_id['id']));
 			array_push($arrCondition, array('OR'=>['ApplyOnlines.payment_status'=>'1','ApplyOnlines.category'=>'3001']));
		}
		$fields_date  	= "apply_online_approvals.created";
		if (!empty($DateField) && in_array($DateField,array("apply_online_approvals.created","charging_certificate.meter_installed_date"))) {
			$fields_date = $DateField;
		}
		if(!empty($application_status))
		{
			$passStatus = $this->ApplyOnlines->apply_online_status_key[$application_status];
	        if($passStatus == '9999')
	        {
	            array_push($arrCondition,array('ApplyOnlines.application_status'=>$this->ApplyOnlineApprovals->APPLICATION_SUBMITTED));
	        }
	        else
	        {
	            $FindApplicationIDs     = $this->ApplyOnlineApprovals->find('list',['keyField'=>'id','valueField'=>'application_id','conditions'=>['stage'=>$this->ApplyOnlines->apply_online_status_key[$application_status]]]);
	            if($fields_date == 'apply_online_approvals.created' && !empty($from_date) && !empty($end_date))
	            {
	            	$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
					$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
		    		$FindApplicationIDs->where([function ($exp, $q) use ($StartTime, $EndTime,$fields_date) {
						return $exp->between('ApplyOnlineApprovals.created', $StartTime, $EndTime);
			   		}]);
	            }
			   	$FindApplicationIDs = $FindApplicationIDs->toArray();
	            if (!empty($FindApplicationIDs)) {
	                array_push($arrCondition,array('ApplyOnlines.id IN ' => array_unique($FindApplicationIDs)));
	                if($passStatus != $this->ApplyOnlineApprovals->APPLICATION_CANCELLED)
	                {
	                    array_push($arrCondition,array('ApplyOnlines.application_status != ' => $this->ApplyOnlineApprovals->APPLICATION_CANCELLED));
	                }
	            } else {
	                array_push($arrCondition,array('ApplyOnlines.id' => 0 ));
	            }
	        }
		}
       	if(!empty($installer_name)) {
       		$arrCondition['ApplyOnlines.installer_id in'] = $installer_name;
       	}
       	if(!empty($application_no)) {
       		array_push($arrCondition, array('ApplyOnlines.application_no like'=>'%'.$application_no.'%'));
       	}
       	if(!empty($geda_application_no)) {
       		array_push($arrCondition, array('ApplyOnlines.geda_application_no like'=>'%'.$geda_application_no.'%'));
       	}
       	if(!empty($customer_id)) {
       		$installerdata	= $this->Customers->find('all', array('conditions'=>array('id'=>$customer_id)))->first();
			$installer_id 	= (isset($installerdata['installer_id'])?$installerdata['installer_id']:0);
			array_push($arrCondition, array('ApplyOnlines.installer_id' => $installer_id));
       	}
       	if($payment_status!='') {
            array_push($arrCondition,array('fesibility_report.payment_approve'=>$payment_status));
        }
        if($govt_agency!='') {
            array_push($arrCondition,array('ApplyOnlines.govt_agency'=>$govt_agency));
        }
       	$social_consumer = '';
		array_push($arrCondition,array('apply_online_approvals.stage'=>'1'));
        $query_data=$this->ApplyOnlines->find('all',array(
            'fields'=>array('ApplyOnlines.id','ApplyOnlines.application_status','ApplyOnlines.application_no','ApplyOnlines.geda_application_no','installers.installer_name','apply_online_approvals.created','ApplyOnlines.payment_status'),
            'join'=>array('installers'=>array('table'=>'installers','conditions'=>'ApplyOnlines.installer_id = installers.id','type'=>'inner'),
            	'apply_online_approvals'=>array('table'=>'apply_online_approvals','conditions'=>'ApplyOnlines.id = apply_online_approvals.application_id','type'=>'left'),
            	'projects'=>array('table'=>'projects','conditions'=>'ApplyOnlines.project_id = projects.id','type'=>'left'),
            	'fesibility_report'=>array('table'=>'fesibility_report','conditions'=>'ApplyOnlines.id = fesibility_report.application_id','type'=>'left'),
            	'charging_certificate'=>array('table'=>'charging_certificate','conditions'=>'ApplyOnlines.id = charging_certificate.application_id','type'=>'left'),
            ),
            'conditions' => $arrCondition,
            'order'=>array($this->SortBy=>$this->Direction),
            'page'=> $this->CurrentPage,
            'limit' => $this->intLimit));

        if($fields_date != 'apply_online_approvals.created' && !empty($from_date) && !empty($end_date))
        {
			$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
			$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
	        $query_data->where([function ($exp, $q) use ($StartTime, $EndTime,$fields_date) {
				return $exp->between($fields_date, $StartTime, $EndTime);
	   		}]);
        }

        $query_data_count=$this->ApplyOnlines->find('all',array(
            'fields'=>array('ApplyOnlines.id','ApplyOnlines.application_status','ApplyOnlines.application_no','ApplyOnlines.geda_application_no','installers.installer_name','apply_online_approvals.created'),
            'join'=>array('installers'=>array('table'=>'installers','conditions'=>'ApplyOnlines.installer_id = installers.id','type'=>'inner'),
            	'apply_online_approvals'=>array('table'=>'apply_online_approvals','conditions'=>'ApplyOnlines.id = apply_online_approvals.application_id','type'=>'left'),
            	'projects'=>array('table'=>'projects','conditions'=>'ApplyOnlines.project_id = projects.id','type'=>'left'),
            	'fesibility_report'=>array('table'=>'fesibility_report','conditions'=>'ApplyOnlines.id = fesibility_report.application_id','type'=>'left'),
            	'charging_certificate'=>array('table'=>'charging_certificate','conditions'=>'ApplyOnlines.id = charging_certificate.application_id','type'=>'left'),
            ),
            'conditions' => $arrCondition,
            'order'=>array($this->SortBy=>$this->Direction)));
        if($fields_date != 'apply_online_approvals.created' && !empty($from_date) && !empty($end_date))
        {
			$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
			$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
	    	$query_data_count->where([function ($exp, $q) use ($StartTime, $EndTime,$fields_date) {
				return $exp->between($fields_date, $StartTime, $EndTime);
	   		}]);
        }
		$total_query_records= count($query_data_count->toArray());
       	$start_page=isset($this->request->data['start']) ? $this->request->data['start'] : 1;
       	$this->paginate['limit']= 50;
       	$this->paginate['page'] = ($start_page/$this->paginate['limit'])+1;
       	if(isset($this->request->data['page_no']) && !empty($this->request->data['page_no']))
       	{
       		$posible_page 		= $total_query_records/$this->paginate['limit'];
       		if($posible_page<$this->request->data['page_no'])
       		{
       			$this->paginate['page'] = $posible_page;
       		}
       		else
       		{
       			$this->paginate['page'] = $this->request->data['page_no'];
       		}
       	}
       	else
       	{
       		$this->paginate['page'] = ($start_page/$this->paginate['limit'])+1;
       	}
        $arrAdminuserList				= $this->paginate($query_data);
        $usertypes 						= array();
        $option['dt_selector']			='table-example';
        $option['formId']				='formmain';
        $option['url']					= '';
        $option['recordsperpage']		= '50';
        $option['allsortable']			= '-all';
        $option['total_records_data']	= count($arrAdminuserList->toArray());
        $option['order_by'] 			= "order : [[5,'desc']]";
        $arr_status_dropdown 			= $this->ApplyOnlines->apply_online_dropdown_status;
        unset($arr_status_dropdown['99']);
        $JqdTablescr 			= $this->JqdTable->create($option);
        $installers_list 		= $this->Installers->getInstallerListReport();
        $this->set('arrAdminuserList',$arrAdminuserList->toArray());
        $this->set('JqdTablescr',$JqdTablescr);
        $this->set('period',$this->period);
        $this->set('limit',$this->intLimit);
        $this->set("CurrentPage",$this->CurrentPage);
        $this->set("SortBy",$this->SortBy);
        $this->set("Direction",$this->Direction);
        $this->set("pagetitle",'Project : ');
        $this->set("application_dropdown_status",$arr_status_dropdown);
        $this->set("Installers",$installers_list);
        $this->set("default_fields",!empty($customer_id) ? array_keys($this->MISReportData->arrReportFieldsIns) : array_keys($this->MISReportData->arrReportFields));
        $this->set("page_count",(isset($this->request->params['paging']['ProjectSurvey']['pageCount'])?$this->request->params['paging']['ProjectSurvey']['pageCount']:0));

        $out 		=array();
        $counter 	= '1';
        $page_mul 	= ($this->CurrentPage-1);
        foreach($arrAdminuserList->toArray() as $key=>$val) {
        	$temparr=array();
            foreach($option['colName'] as $key) {
                if(isset($val[$key])){
                    $temparr[$key]=$val[$key];
                }
                if($key=='id') {
                   $temparr[$key]= $counter+($page_mul*50);
                   $counter++;
                }
                if($key=='installer_name') {
                   $temparr[$key]= ucwords($val['installers']['installer_name']);
                }
                if($key=='application_status') {
                   $temparr[$key]= $this->ApplyOnlineApprovals->application_status[$val->application_status];
                }
                if($key=='geda_application_no') {
					$temparr[$key]= ($val->payment_status != 1) ? '-' : $val->geda_application_no;
				}
                if($key=='submitted_on') {
                	if(!empty($val['apply_online_approvals']['created']))
                	{
                		$temparr[$key]= date('m-d-Y H:i a',strtotime($val['apply_online_approvals']['created']));
                	}
                	else
                	{
                		$temparr[$key]= '-';
                	}

                }
            }
            $out[]=$temparr;
        }

        if ($this->request->is('ajax'))
        {
            header('Content-type: application/json');
            echo json_encode(array(	"condi" 			=> $arrCondition,
            						"draw" 				=> intval($this->request->data['draw']),
					                "recordsTotal"    	=> intval( $this->request->params['paging']['ApplyOnlines']['count']),
					                "recordsFiltered" 	=> intval( $this->request->params['paging']['ApplyOnlines']['count']),
					                "data"            	=> $out));
            die;
        }
    }
    
    /**
	 *
	 * categorySummary
	 *
	 * Behaviour : Public
	 *
	 *@param :
	 *
	 * @defination : Method is use to display summary report.
	 *
	 */
    public function categorySummary()
    {
    	$member_id 			= $this->Session->read("Members.id");
    	$state 				= '';
		$branch_id 			= '';
		$main_branch_id		= '';
		$member_type 		= '';
		$final_data 		= array();

		$member_type 		= $this->Session->read('Members.member_type');
		$area 				= $this->Session->read('Members.area');
		$circle 			= $this->Session->read('Members.circle');
		$division 			= $this->Session->read('Members.division');
		$subdivision 		= $this->Session->read('Members.subdivision');
		$section 			= $this->Session->read('Members.section');
		if(empty($member_id) && $member_type!=$this->ApplyOnlines->JREDA)
		{
			return $this->redirect('home');
		}
		if (!empty($member_id) && $member_type == $this->ApplyOnlines->DISCOM)
		{
			$field      = "area";
			$id         = $area;

			if (!empty($section)) {
				$field      = "section";
				$id         = $section;
			} else if (!empty($subdivision)) {
				$field      = "subdivision";
				$id         = $subdivision;
			} else if (!empty($division)) {
				$field      = "division";
				$id         = $division;
			} else if (!empty($circle)) {
				$field      = "circle";
				$id         = $circle;
			}
			$main_branch_id = array("field"=>$field,"id"=>$id);
		}
		if($this->Session->check("Members.state")){
			$state 			= $this->Session->read("Members.state");
		}
		$array_request 		= $this->request->data;
		$from_date 			= isset($array_request['DateFrom'])?$array_request['DateFrom']:'';
		$end_date 			= isset($array_request['DateTo'])?$array_request['DateTo']:'';
		$application_status = isset($array_request['status'])?$array_request['status']:'';
		$installer_name 	= isset($array_request['installer_name'])?$array_request['installer_name']:'';
		$application_no 	= isset($array_request['application_no'])?$array_request['application_no']:'';
		$geda_application_no= isset($array_request['geda_application_no'])?$array_request['geda_application_no']:'';
		$sql 				= '';
		if(!empty($from_date) && !empty($end_date))
		{
			$fields_date  	= "(( CASE WHEN 1 = 1 THEN( select created FROM apply_online_approvals
								    WHERE
								      apply_online_approvals.application_id = apply_onlines.id
								      AND apply_online_approvals.stage = '31'
								    group by
								      stage) END ))";

			$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
			$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
		    $sql 			.= " and $fields_date between '".$StartTime."' and '".$EndTime."'";
		}
		if(!empty($main_branch_id))
		{
			$sql .= " and apply_onlines.".$main_branch_id['field']." = '".$main_branch_id['id']."'";
		}
		if(!empty($application_status))
		{
			$passStatus = $this->ApplyOnlines->apply_online_status_key[$application_status];
	        if($passStatus == '9999')
	        {
	            $sql .= " and apply_onlines.application_status = '".$this->ApplyOnlineApprovals->APPROVED_FROM_GEDA."'";
	            $sql .= " AND apply_online_approvals.stage = '".$this->ApplyOnlineApprovals->APPROVED_FROM_GEDA."'";
	        }
	        else
	        {
	            $FindApplicationIDs     = $this->ApplyOnlineApprovals->find('list',['keyField'=>'id','valueField'=>'application_id','conditions'=>['stage'=>$this->ApplyOnlines->apply_online_status_key[$application_status]]])->toArray();
	            if (!empty($FindApplicationIDs)) {
	                $sql .= " and apply_onlines.id IN (".implode(",",array_unique($FindApplicationIDs)).") ";
	                if($passStatus != $this->ApplyOnlineApprovals->APPLICATION_CANCELLED)
	                {
	                    $sql .= " and apply_onlines.application_status != '".$this->ApplyOnlineApprovals->APPLICATION_CANCELLED."'";
	                }
	            } else {
	                $sql .= " and apply_onlines.id = '0'";
	            }
	            $stage 	= $this->ApplyOnlines->apply_online_status_key[$application_status];
	            $sql .= " AND apply_online_approvals.stage = $stage";
	        }
		}
		else
		{
			$stage= $this->ApplyOnlineApprovals->APPROVED_FROM_GEDA;
			$sql .= " AND apply_online_approvals.stage = $stage";
		}
		if(!empty($installer_name))
	   	{
	   		//$sql .= " and INS.installer_name like '%".$installer_name."%'";
	   		$sql .= " and INS.id in ('".implode("','",$installer_name)."')";
	   	}
	   	if(!empty($application_no))
	   	{
	   		$sql .= " and application_no like '%".$application_no."%'";
	   	}
	   	if(!empty($geda_application_no))
	   	{
	   		$sql .= " and geda_application_no like '%".$geda_application_no."%'";
	   	}
		foreach($this->arrCapacity as $key=>$val)
		{
			$arr_min_max 		= explode("_",$val);

			$connection         = ConnectionManager::get('default');
			$str_where 			= '';
			if(isset($arr_min_max[0]))
			{
				$str_where 		= " pv_capacity > $arr_min_max[0] ";
			}
			if(isset($arr_min_max[1]))
			{
				$str_where 		.= " AND pv_capacity <= $arr_min_max[1]";
			}
			$sql_first 			= " SELECT
									CASE WHEN 1=1 THEN
									(
										SELECT COUNT(apply_onlines.id)
										FROM apply_onlines
										INNER JOIN apply_online_approvals ON apply_onlines.id = apply_online_approvals.application_id
										INNER JOIN installers as INS ON INS.id = apply_onlines.installer_id
										WHERE $str_where $sql
									) END AS TOTAL_APPLICATION,
									CASE WHEN 1=1 THEN
									(
										SELECT SUM(pv_capacity)
										FROM apply_onlines
										INNER JOIN apply_online_approvals ON apply_onlines.id = apply_online_approvals.application_id
										INNER JOIN installers as INS ON INS.id = apply_onlines.installer_id
										WHERE $str_where $sql
										AND (apply_onlines.disclaimer_subsidy = 0 OR apply_onlines.disclaimer_subsidy IS NULL)
									) END AS TOTAL_CAPACITY_WITH_SUBSITY,
									CASE WHEN 1=1 THEN
									(
										SELECT SUM(pv_capacity)
										FROM apply_onlines
										INNER JOIN apply_online_approvals ON apply_onlines.id = apply_online_approvals.application_id
										INNER JOIN installers as INS ON INS.id = apply_onlines.installer_id
										WHERE $str_where $sql
										AND (apply_onlines.disclaimer_subsidy = 1)
									) END AS TOTAL_CAPACITY_WITHOUT_SUBSITY";

			$applicationData_output 	= $connection->execute($sql_first)->fetchAll('assoc');
			$final_data[$key] 			= $applicationData_output[0];
		}
		$arr_status_dropdown 			= $this->ApplyOnlines->apply_online_dropdown_status;
		$installers_list 				= $this->Installers->getInstallerListReport();
		$this->set('final_data',$final_data);
        $this->set('arrCapacity',$this->arrCapacity);
        $this->set('arrCapacityLabel',$this->arrCapacityLabel);
       	$this->set("pagetitle",'Category Summary Report');
       	$this->set("application_dropdown_status",$arr_status_dropdown);
       	$this->set("installers_list",$installers_list);

    }
    /**
	 *
	 * districtMeter
	 *
	 * Behaviour : Public
	 *
	 *@param :
	 *
	 * @defination : Method is use to display district wise meter installation report.
	 *
	 */
    public function districtMeter()
    {
    	$member_id 			= $this->Session->read("Members.id");
    	$state 				= '';
		$branch_id 			= '';
		$main_branch_id		= '';
		$member_type 		= '';
		$final_data 		= array();

		$member_type 		= $this->Session->read('Members.member_type');
		$area 				= $this->Session->read('Members.area');
		$circle 			= $this->Session->read('Members.circle');
		$division 			= $this->Session->read('Members.division');
		$subdivision 		= $this->Session->read('Members.subdivision');
		$section 			= $this->Session->read('Members.section');
		if(empty($member_id) && $member_type!=$this->ApplyOnlines->JREDA)
		{
			return $this->redirect('home');
		}
		if (!empty($member_id) && $member_type == $this->ApplyOnlines->DISCOM)
		{
			$field      = "area";
			$id         = $area;

			if (!empty($section)) {
				$field      = "section";
				$id         = $section;
			} else if (!empty($subdivision)) {
				$field      = "subdivision";
				$id         = $subdivision;
			} else if (!empty($division)) {
				$field      = "division";
				$id         = $division;
			} else if (!empty($circle)) {
				$field      = "circle";
				$id         = $circle;
			}
			$main_branch_id = array("field"=>$field,"id"=>$id);
		}
		if($this->Session->check("Members.state")){
			$state 			= $this->Session->read("Members.state");
		}
		$array_request 		= $this->request->data;
		$from_date 			= isset($array_request['DateFrom'])?$array_request['DateFrom']:'';
		$end_date 			= isset($array_request['DateTo'])?$array_request['DateTo']:'';
		$application_status = isset($array_request['status'])?$array_request['status']:'';
		$installer_name 	= isset($array_request['installer_name'])?$array_request['installer_name']:'';

		$application_no 	= isset($array_request['application_no'])?$array_request['application_no']:'';
		$geda_application_no= isset($array_request['geda_application_no'])?$array_request['geda_application_no']:'';
		$category 			= isset($array_request['category'])?$array_request['category']:'';
		$sql 				= '';
		$stage 				= $this->ApplyOnlineApprovals->METER_INSTALLATION;
		if(!empty($from_date) && !empty($end_date))
		{
			$fields_date  	= "(( CASE WHEN 1 = 1 THEN( select created FROM apply_online_approvals
								    WHERE
								      apply_online_approvals.application_id = apply_onlines.id
								      AND apply_online_approvals.stage = '".$stage."'
								    group by
								      stage) END ))";

			$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
			$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
		    $sql 			.= " and $fields_date between '".$StartTime."' and '".$EndTime."'";
		}
		if(!empty($main_branch_id))
		{
			$sql .= " and apply_onlines.".$main_branch_id['field']." = '".$main_branch_id['id']."'";
		}


		$sql .= " AND apply_online_approvals.stage = $stage";
		if(!empty($installer_name))
	   	{
	   		//$sql .= " and INS.installer_name like '%".$installer_name."%'";
	   		$sql .= " and INS.id in ('".implode("','",$installer_name)."')";
	   	}
	   	if(!empty($application_no))
	   	{
	   		$sql .= " and application_no like '%".$application_no."%'";
	   	}
	   	if(!empty($geda_application_no))
	   	{
	   		$sql .= " and geda_application_no like '%".$geda_application_no."%'";
	   	}
	   	if(!empty($category))
	   	{
	   		if($category == $this->ApplyOnlines->category_residental)
	   		{
	   			$sql .= " and category = '".$category."'";
	   		}
	   		elseif($category != $this->ApplyOnlines->category_residental)
	   		{
	   			$sql .= " and category != '".$this->ApplyOnlines->category_residental."'";
	   		}
	   	}
	   	$arrDistricts 	= $this->ApplyOnlines->find('all',
								[
									'fields'		=>['ApplyOnlines.district'],
									'join'			=>[['table'=>'apply_online_approvals','type'=>'left','conditions'=>'apply_online_approvals.application_id = ApplyOnlines.id']],
									'conditions'	=> ['apply_online_approvals.stage '=>$stage],
									'order'   		=> ['district'=>'asc']
								]
							)->distinct(['district'])->toArray();

		foreach($arrDistricts as $key=>$val)
		{
			$connection         = ConnectionManager::get('default');
			$str_where 		= " apply_onlines.district = '".$val->district."'";

			$sql_first 			= " SELECT
									CASE WHEN 1=1 THEN
									(
										SELECT COUNT(apply_onlines.id)
										FROM apply_onlines
										INNER JOIN apply_online_approvals ON apply_onlines.id = apply_online_approvals.application_id
										INNER JOIN installers as INS ON INS.id = apply_onlines.installer_id
										WHERE $str_where $sql
									) END AS TOTAL_METER_INSTALLED,
									CASE WHEN 1=1 THEN
									(
										SELECT SUM(pv_capacity)
										FROM apply_onlines
										INNER JOIN apply_online_approvals ON apply_onlines.id = apply_online_approvals.application_id
										INNER JOIN installers as INS ON INS.id = apply_onlines.installer_id
										WHERE $str_where $sql
									) END AS TOTAL_CAPACITY";

			$applicationData_output 		= $connection->execute($sql_first)->fetchAll('assoc');
			$final_data[$key] 				= $applicationData_output[0];
			$final_data[$key]['DISTRICT']	= $val->district;
		}

		$arrCountData 		= array();
		$arrCapacityData	= array();
		foreach($final_data as $key => $val)
		{
			$mappingData 	= $this->DistrictNameMapping->find('all',
									array('fields'	=>['DistrictNameMapping.district_code','district_master.name'],
										'join' 		=> [['table'      =>'district_master',
														 'conditions' => 'DistrictNameMapping.district_code = district_master.district_code']],
											'conditions'=>array('DistrictNameMapping.district_name'=>$val['DISTRICT'])))->first();
			if($mappingData)
			{
				if(!array_key_exists($mappingData->district_master['name'], $arrCountData))
				{
					$arrCountData[$mappingData->district_master['name']]	= $val['TOTAL_METER_INSTALLED'];
					$arrCapacityData[$mappingData->district_master['name']]	= $val['TOTAL_CAPACITY'];
				}
				else
				{
					$arrCountData[$mappingData->district_master['name']]		= $arrCountData[$mappingData->district_master['name']]+$val['TOTAL_METER_INSTALLED'];
					$arrCapacityData[$mappingData->district_master['name']]	= $arrCapacityData[$mappingData->district_master['name']]+$val['TOTAL_CAPACITY'];
				}
			}
			else
			{
				if(!array_key_exists($val['DISTRICT'], $arrCountData))
				{
					$arrCountData[$val['DISTRICT']]			= $val['TOTAL_METER_INSTALLED'];
					$arrCapacityData[$val['DISTRICT']]		= $val['TOTAL_CAPACITY'];
				}
				else
				{
					$arrCountData[$val['DISTRICT']]		= $arrCountData[$val['DISTRICT']]+$val['TOTAL_METER_INSTALLED'];
					$arrCapacityData[$val['DISTRICT']]	= $arrCapacityData[$val['DISTRICT']]+$val['TOTAL_CAPACITY'];
				}
			}


		}
		$arr_status_dropdown 	= $this->ApplyOnlines->apply_online_dropdown_status;
		$installers_list 		= $this->Installers->getInstallerListReport();
		$DisplayFilter 			= 0;
		if(in_array($member_id, $this->ApplyOnlines->ALLOWED_APPROVE_GEDAIDS))
		{
			$DisplayFilter 		= 1;
		}
		$this->set('final_data',$final_data);
        $this->set("pagetitle",'District Wise Meter Installation');
       	$this->set("application_dropdown_status",$arr_status_dropdown);
       	$this->set("arrCountData",$arrCountData);
       	$this->set("arrCapacityData",$arrCapacityData);
       	$this->set("installers_list",$installers_list);
       	$this->set("DisplayFilter",$DisplayFilter);
    }
    /**
	 *
	 * meterCP
	 *
	 * Behaviour : Public
	 *
	 *@param :
	 *
	 * @defination : Method is use to display Meter Installation CP Wise report.
	 *
	 */
    public function meterCp()
    {
    	$member_id 			= $this->Session->read("Members.id");
    	$state 				= '';
		$branch_id 			= '';
		$main_branch_id		= '';
		$member_type 		= '';
		$final_data 		= array();

		$member_type 		= $this->Session->read('Members.member_type');
		$area 				= $this->Session->read('Members.area');
		$circle 			= $this->Session->read('Members.circle');
		$division 			= $this->Session->read('Members.division');
		$subdivision 		= $this->Session->read('Members.subdivision');
		$section 			= $this->Session->read('Members.section');
		if(empty($member_id) && $member_type!=$this->ApplyOnlines->JREDA)
		{
			return $this->redirect('home');
		}
		if($this->Session->check("Members.state")){
			$state 			= $this->Session->read("Members.state");
		}
		if (!empty($member_id) && $member_type == $this->ApplyOnlines->DISCOM)
		{
			$field      = "area";
			$id         = $area;

			if (!empty($section)) {
				$field      = "section";
				$id         = $section;
			} else if (!empty($subdivision)) {
				$field      = "subdivision";
				$id         = $subdivision;
			} else if (!empty($division)) {
				$field      = "division";
				$id         = $division;
			} else if (!empty($circle)) {
				$field      = "circle";
				$id         = $circle;
			}
			$main_branch_id = array("field"=>$field,"id"=>$id);
		}
		$array_request 		= $this->request->data;
		$from_date 			= isset($array_request['DateFrom'])?$array_request['DateFrom']:'';
		$end_date 			= isset($array_request['DateTo'])?$array_request['DateTo']:'';
		$application_status = isset($array_request['status'])?$array_request['status']:'';
		$installer_name 	= isset($array_request['installer_name'])?$array_request['installer_name']:'';
		$application_no 	= isset($array_request['application_no'])?$array_request['application_no']:'';
		$geda_application_no= isset($array_request['geda_application_no'])?$array_request['geda_application_no']:'';
		$category 			= isset($array_request['category'])?$array_request['category']:'';
		$sql 				= '';
		$stage 				= $this->ApplyOnlineApprovals->METER_INSTALLATION;
		if(!empty($from_date) && !empty($end_date))
		{
			$fields_date  	= "(( CASE WHEN 1 = 1 THEN( select created FROM apply_online_approvals
								    WHERE
								      apply_online_approvals.application_id = apply_onlines.id
								      AND apply_online_approvals.stage = '".$stage."'
								    group by
								      stage) END ))";

			$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
			$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
		    $sql 			.= " and $fields_date between '".$StartTime."' and '".$EndTime."'";
		}
		if(!empty($main_branch_id))
		{
			$sql .= " and apply_onlines.".$main_branch_id['field']." = '".$main_branch_id['id']."'";
		}


		$sql .= " AND apply_online_approvals.stage = $stage";
		$Condition_installer = array('apply_online_approvals.stage '=>$stage);
		if(!empty($installer_name))
	   	{
	   		//$sql .= " and INS.installer_name like '%".$installer_name."%'";
	   		$sql .= " and INS.id in ('".implode("','",$installer_name)."')";
	   		$Condition_installer = array('ApplyOnlines.installer_id in'=>$installer_name,'apply_online_approvals.stage '=>$stage);
	   	}
	   	if(!empty($application_no))
	   	{
	   		$sql .= " and application_no like '%".$application_no."%'";
	   	}
	   	if(!empty($geda_application_no))
	   	{
	   		$sql .= " and geda_application_no like '%".$geda_application_no."%'";
	   	}
	   	if(!empty($category))
	   	{
	   		if($category == $this->ApplyOnlines->category_residental)
	   		{
	   			$sql .= " and category = '".$category."'";
	   		}
	   		elseif($category != $this->ApplyOnlines->category_residental)
	   		{
	   			$sql .= " and category != '".$this->ApplyOnlines->category_residental."'";
	   		}
	   	}
	   	$arrInstallers 	= $this->ApplyOnlines->find('all',
								[
									'fields'		=>['ApplyOnlines.installer_id','installers.installer_name','installer_category.category_name','installer_category.id'],
									'join'			=>[['table'=>'apply_online_approvals','type'=>'left','conditions'=>'apply_online_approvals.application_id = ApplyOnlines.id'],
														['table'=>'installers','type'=>'left','conditions'=>'ApplyOnlines.installer_id = installers.id'],
														['table'=>'installer_category_mapping','type'=>'left','conditions'=>'ApplyOnlines.installer_id = installer_category_mapping.installer_id'],
														['table'=>'installer_category','type'=>'left','conditions'=>'installer_category_mapping.category_id = installer_category.id']],
									'conditions'	=> $Condition_installer,
									'order'   		=> ['installers.installer_name'=>'asc']
								]
							)->distinct(['ApplyOnlines.installer_id'])->toArray();

	   	foreach($arrInstallers as $key=>$val)
		{
			$connection         = ConnectionManager::get('default');
			$str_where 			= " installer_id = '".$val->installer_id."'";
			$sql_first 			= " SELECT
									CASE WHEN 1=1 THEN
									(
										SELECT COUNT(apply_onlines.id)
										FROM apply_onlines
										INNER JOIN apply_online_approvals ON apply_onlines.id = apply_online_approvals.application_id
										INNER JOIN installers as INS ON INS.id = apply_onlines.installer_id
										WHERE $str_where $sql
									) END AS TOTAL_METER_INSTALLED,
									CASE WHEN 1=1 THEN
									(
										SELECT SUM(pv_capacity)
										FROM apply_onlines
										INNER JOIN apply_online_approvals ON apply_onlines.id = apply_online_approvals.application_id
										INNER JOIN installers as INS ON INS.id = apply_onlines.installer_id
										WHERE $str_where $sql
									) END AS TOTAL_CAPACITY";

			$applicationData_output 			= $connection->execute($sql_first)->fetchAll('assoc');
			$final_data[$key] 					= $applicationData_output[0];
			$final_data[$key]['installer']		= $val->installers['installer_name'];
			$final_data[$key]['category_name']	= $val->installer_category['category_name'];
			if($val->installer_category['id']==1 || $val->installer_category['id']==2)
			{
				$final_data[$key]['scheme_type']= 'Subsidy';
			}
			if($val->installer_category['id']==3)
			{
				$final_data[$key]['scheme_type']= 'Non-subsidy';
			}

		}
		$arr_status_dropdown 	= $this->ApplyOnlines->apply_online_dropdown_status;
		$installers_list 		= $this->Installers->getInstallerListReport();
		$DisplayFilter 			= 0;
		if(in_array($member_id, $this->ApplyOnlines->ALLOWED_APPROVE_GEDAIDS))
		{
			$DisplayFilter 		= 1;
		}
		$this->set('final_data',$final_data);
        $this->set("pagetitle",'Solar PV Installer Wise Rooftop PV Solar Installation');
       	$this->set("application_dropdown_status",$arr_status_dropdown);
       	$this->set("installers_list",$installers_list);
       	$this->set("DisplayFilter",$DisplayFilter);

    }
    /**
	 *
	 * monthRrss
	 *
	 * Behaviour : Public
	 *
	 *@param :
	 *
	 * @defination : Method is use to display Month Wise Application Received For Residential Rooftop Project report.
	 *
	 */
    public function monthRrss()
    {
    	$member_id 			= $this->Session->read("Members.id");
    	$state 				= '';
		$branch_id 			= '';
		$main_branch_id		= '';
		$member_type 		= '';
		$final_data 		= array();

		$member_type 		= $this->Session->read('Members.member_type');
		$area 				= $this->Session->read('Members.area');
		$circle 			= $this->Session->read('Members.circle');
		$division 			= $this->Session->read('Members.division');
		$subdivision 		= $this->Session->read('Members.subdivision');
		$section 			= $this->Session->read('Members.section');
		if(empty($member_id) && $member_type!=$this->ApplyOnlines->JREDA)
		{
			return $this->redirect('home');
		}
		if (!empty($member_id) && $member_type == $this->ApplyOnlines->DISCOM)
		{
			$field      = "area";
			$id         = $area;

			if (!empty($section)) {
				$field      = "section";
				$id         = $section;
			} else if (!empty($subdivision)) {
				$field      = "subdivision";
				$id         = $subdivision;
			} else if (!empty($division)) {
				$field      = "division";
				$id         = $division;
			} else if (!empty($circle)) {
				$field      = "circle";
				$id         = $circle;
			}
			$main_branch_id = array("field"=>$field,"id"=>$id);
		}
		if($this->Session->check("Members.state")){
			$state 			= $this->Session->read("Members.state");
		}
		$array_request 		= $this->request->data;
		$from_date 			= isset($array_request['DateFrom'])?$array_request['DateFrom']:'';
		$end_date 			= isset($array_request['DateTo'])?$array_request['DateTo']:'';
		$application_status = isset($array_request['status'])?$array_request['status']:'';
		$installer_name 	= isset($array_request['installer_name'])?$array_request['installer_name']:'';
		$application_no 	= isset($array_request['application_no'])?$array_request['application_no']:'';
		$geda_application_no= isset($array_request['geda_application_no'])?$array_request['geda_application_no']:'';
		$sql 				= '';
		$sql_meter 			= '';
		$sql_reg 			= '';
		$stage 				= $this->ApplyOnlineApprovals->METER_INSTALLATION;
		$stage_reg 			= $this->ApplyOnlineApprovals->APPROVED_FROM_GEDA;
		if(!empty($from_date) && !empty($end_date))
		{
			$fields_date  	= "(( CASE WHEN 1 = 1 THEN( select created FROM apply_online_approvals
								    WHERE
								      apply_online_approvals.application_id = apply_onlines.id
								      AND apply_online_approvals.stage = $stage
								    group by
								      stage) END ))";

			$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
			$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
		    $sql_meter 		.= " and $fields_date between '".$StartTime."' and '".$EndTime."'";

		    $fields_date  	= "(( CASE WHEN 1 = 1 THEN( select created FROM apply_online_approvals
								    WHERE
								      apply_online_approvals.application_id = apply_onlines.id
								      AND apply_online_approvals.stage = $stage_reg
								    group by
								      stage) END ))";

			$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
			$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
		    $sql_reg 		.= " and $fields_date between '".$StartTime."' and '".$EndTime."'";
		}
		if(!empty($main_branch_id))
		{
			$sql .= " and apply_onlines.".$main_branch_id['field']." = '".$main_branch_id['id']."'";
		}



		if(!empty($installer_name))
	   	{
	   		//$sql .= " and INS.installer_name like '%".$installer_name."%'";
	   		$sql .= " and INS.id in ('".implode("','",$installer_name)."')";
	   	}
	   	if(!empty($application_no))
	   	{
	   		$sql .= " and application_no like '%".$application_no."%'";
	   	}
	   	if(!empty($geda_application_no))
	   	{
	   		$sql .= " and geda_application_no like '%".$geda_application_no."%'";
	   	}
	    $start_year 	= '2018';
	    $start_month	= '09';
		$cur_year 		= date('Y');
	    $cur_month		= date('m',strtotime(date('Y-m-d').' +1 month'));
	    $startYearMonth = '201809';
	    $curYearMonth 	= date('Ym');
	   	while($startYearMonth!=$curYearMonth)
		{
			$start_date 		= $start_year."-".$start_month."-01 00:00:00";
			$end_date 			= $start_year."-".$start_month."-31 23:59:59";
			$str_where 			= " apply_online_approvals.created between '".$start_date."' and '".$end_date."'";
			$connection         = ConnectionManager::get('default');

			$sql_first 			= " SELECT
									CASE WHEN 1=1 THEN
									(
										SELECT COUNT(apply_onlines.id)
										FROM apply_onlines
										INNER JOIN apply_online_approvals ON apply_onlines.id = apply_online_approvals.application_id
										INNER JOIN installers as INS ON INS.id = apply_onlines.installer_id
										WHERE $str_where AND apply_online_approvals.stage = $stage $sql $sql_meter
									) END AS TOTAL_METER_INSTALLED,
									CASE WHEN 1=1 THEN
									(
										SELECT SUM(pv_capacity)
										FROM apply_onlines
										INNER JOIN apply_online_approvals ON apply_onlines.id = apply_online_approvals.application_id
										INNER JOIN installers as INS ON INS.id = apply_onlines.installer_id
										WHERE $str_where AND apply_online_approvals.stage = $stage $sql $sql_meter
									) END AS TOTAL_METER_CAPACITY,
									CASE WHEN 1=1 THEN
									(
										SELECT COUNT(apply_onlines.id)
										FROM apply_onlines
										INNER JOIN apply_online_approvals ON apply_onlines.id = apply_online_approvals.application_id
										INNER JOIN installers as INS ON INS.id = apply_onlines.installer_id
										WHERE $str_where AND apply_online_approvals.stage = $stage_reg $sql $sql_reg
									) END AS TOTAL_REGISTERED,
									CASE WHEN 1=1 THEN
									(
										SELECT SUM(pv_capacity)
										FROM apply_onlines
										INNER JOIN apply_online_approvals ON apply_onlines.id = apply_online_approvals.application_id
										INNER JOIN installers as INS ON INS.id = apply_onlines.installer_id
										WHERE $str_where AND apply_online_approvals.stage = $stage_reg $sql $sql_reg
									) END AS TOTAL_REGISTERED_CAPACITY";

			$applicationData_output 							= $connection->execute($sql_first)->fetchAll('assoc');
			$final_data[$start_year."-".$start_month] 			= $applicationData_output[0];
			$final_data[$start_year."-".$start_month]['YEAR']	= $start_year;
			$final_data[$start_year."-".$start_month]['MONTH'] 	= date('F',strtotime($start_date));
			$startYearMonth 									= $start_year.$start_month;
			$start_month										= date('m',strtotime($start_date.' +1 month'));
			$start_year											= date('Y',strtotime($start_date.' +1 month'));

		}
		$arr_status_dropdown= $this->ApplyOnlines->apply_online_dropdown_status;
		$installers_list 	= $this->Installers->getInstallerListReport();
		$this->set('final_data',$final_data);
        $this->set("pagetitle",'Month Wise Application Received For Residential Rooftop Project');
       	$this->set("application_dropdown_status",$arr_status_dropdown);
       	$this->set("installers_list",$installers_list);
    }
    /**
	 *
	 * discomSummary
	 *
	 * Behaviour : Public
	 *
	 *@param :
	 *
	 * @defination : Method is use to display Discom Wise Summary report.
	 *
	 */
    public function discomSummary()
    {
    	$member_id 			= $this->Session->read("Members.id");
    	$state 				= '';
		$branch_id 			= '';
		$main_branch_id		= '';
		$member_type 		= '';
		$final_data 		= array();

		$member_type 		= $this->Session->read('Members.member_type');
		$area 				= $this->Session->read('Members.area');
		$circle 			= $this->Session->read('Members.circle');
		$division 			= $this->Session->read('Members.division');
		$subdivision 		= $this->Session->read('Members.subdivision');
		$section 			= $this->Session->read('Members.section');
		if(empty($member_id) && $member_type!=$this->ApplyOnlines->JREDA)
		{
			return $this->redirect('home');
		}
		if($this->Session->check("Members.state")){
			$state 			= $this->Session->read("Members.state");
		}
		if (!empty($member_id) && $member_type == $this->ApplyOnlines->DISCOM)
		{
			$field      = "area";
			$id         = $area;

			if (!empty($section)) {
				$field      = "section";
				$id         = $section;
			} else if (!empty($subdivision)) {
				$field      = "subdivision";
				$id         = $subdivision;
			} else if (!empty($division)) {
				$field      = "division";
				$id         = $division;
			} else if (!empty($circle)) {
				$field      = "circle";
				$id         = $circle;
			}
			$main_branch_id = array("field"=>$field,"id"=>$id);
		}
		$array_request 		= $this->request->data;
		$from_date 			= isset($array_request['DateFrom'])?$array_request['DateFrom']:'';
		$end_date 			= isset($array_request['DateTo'])?$array_request['DateTo']:'';
		$application_status = isset($array_request['status'])?$array_request['status']:'';
		$installer_name 	= isset($array_request['installer_name'])?$array_request['installer_name']:'';
		$application_no 	= isset($array_request['application_no'])?$array_request['application_no']:'';
		$geda_application_no= isset($array_request['geda_application_no'])?$array_request['geda_application_no']:'';
		$sql 				= '';
		$sql_meter 			= '';
		$sql_reg 			= '';
		$stage 				= $this->ApplyOnlineApprovals->METER_INSTALLATION;
		$stage_reg 			= $this->ApplyOnlineApprovals->APPROVED_FROM_GEDA;
		if(!empty($from_date) && !empty($end_date))
		{
			$fields_date  	= "(( CASE WHEN 1 = 1 THEN( select created FROM apply_online_approvals
								    WHERE
								      apply_online_approvals.application_id = apply_onlines.id
								      AND apply_online_approvals.stage = $stage
								    group by
								      stage) END ))";

			$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
			$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
		    $sql_meter 		.= " and $fields_date between '".$StartTime."' and '".$EndTime."'";


		}
		if(!empty($main_branch_id))
		{
			$sql .= " and apply_onlines.".$main_branch_id['field']." = '".$main_branch_id['id']."'";
		}



		if(!empty($installer_name))
	   	{
	   		//$sql .= " and INS.installer_name like '%".$installer_name."%'";
	   		$sql .= " and INS.id in ('".implode("','",$installer_name)."')";
	   	}
	   	if(!empty($application_no))
	   	{
	   		$sql .= " and application_no like '%".$application_no."%'";
	   	}
	   	if(!empty($geda_application_no))
	   	{
	   		$sql .= " and geda_application_no like '%".$geda_application_no."%'";
	   	}
	   	if($member_type!=6001)
	   	{
	   		$conditionsArr 		= array('type'=>'1','id'=>$area);
	   	}
	   	else
	   	{
	   		$conditionsArr 		= array('type'=>'1');
	   	}
	    $DiscomMaster   = $this->DiscomMaster->find('all',array('conditions'=>$conditionsArr))->toArray();
	   	foreach($DiscomMaster as $key=>$val)
		{
			$str_where 			= " apply_onlines.area = '".$val->id."'";
			$connection         = ConnectionManager::get('default');
			foreach($this->arrDisStage as $key_stage=>$stage_sql)
			{
				$stage_where 	= $key_stage;
				$sql_reg 		= '';
				if($key_stage==9999)
				{
					$stage_where 	= $this->ApplyOnlineApprovals->APPROVED_FROM_GEDA. " AND  apply_onlines.application_status='".$this->ApplyOnlineApprovals->APPROVED_FROM_GEDA."'";
					if(!empty($from_date) && !empty($end_date))
					{
						$fields_date  	= "(( CASE WHEN 1 = 1 THEN( select created FROM apply_online_approvals
										    WHERE
										      apply_online_approvals.application_id = apply_onlines.id
										      AND apply_online_approvals.stage = $stage_where
										    group by
										      stage) END ))";

						$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
						$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
					    $sql_reg 		= " and $fields_date between '".$StartTime."' and '".$EndTime."'";
					}
				}
				elseif($key_stage==2000)
				{
					$arr_fea 		= $this->FesibilityReport->find('list',array('keyField'=>'id','valueField'=>'application_id','conditions'=>array('payment_approve'=>'0')))->toArray();

					$stage_where 	= $this->ApplyOnlineApprovals->FEASIBILITY_APPROVAL. " AND  apply_onlines.id IN (".implode(",",$arr_fea).")";
					if(!empty($from_date) && !empty($end_date))
					{
						$fields_date  	= "(( CASE WHEN 1 = 1 THEN( select created FROM apply_online_approvals
										    WHERE
										      apply_online_approvals.application_id = apply_onlines.id
										      AND apply_online_approvals.stage = $stage_where
										    group by
										      stage) END ))";

						$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
						$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
					    $sql_reg 		= " and $fields_date between '".$StartTime."' and '".$EndTime."'";
					}
				}
				elseif($key_stage==2111)
				{
					$arr_fea 		= $this->FesibilityReport->find('list',array('keyField'=>'id','valueField'=>'application_id','conditions'=>array('payment_approve'=>'1')));
					$fields_date  	= "payment_date";
					if(!empty($from_date) && !empty($end_date))
					{
						$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
						$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
				        $arr_fea->where([function ($exp, $q) use ($StartTime, $EndTime,$fields_date) {
							return $exp->between($fields_date, $StartTime, $EndTime);
				   		}]);
			    	}
			    	$output_app 		= implode(",",$arr_fea->toArray());
			    	if(empty($output_app))
			    	{
			    		$output_app 	= '0';
			    	}
					$stage_where 	= $this->ApplyOnlineApprovals->FEASIBILITY_APPROVAL. " AND  apply_onlines.id IN (".$output_app.")";
				}
				elseif($key_stage==1000)
				{
					$arr_self 		= $this->ApplyonlinDocs->find('list',array('keyField'=>'id','valueField'=>'application_id','conditions'=>array('doc_type'=>'Self_Certificate')))->toArray();
					$stage_where 	= $this->ApplyOnlineApprovals->APPROVED_FROM_CEI. " AND  apply_onlines.id IN (".implode(",",$arr_self).")";
					if(!empty($from_date) && !empty($end_date))
					{
						$fields_date  	= "(( CASE WHEN 1 = 1 THEN( select created FROM apply_online_approvals
										    WHERE
										      apply_online_approvals.application_id = apply_onlines.id
										      AND apply_online_approvals.stage = $stage_where
										    group by
										      stage) END ))";

						$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
						$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
					    $sql_reg 		= " and $fields_date between '".$StartTime."' and '".$EndTime."'";
					}
				}
				elseif($key_stage==6002 || $key_stage==0)
				{

					$FindApplicationIDs     = $this->ApplyonlineMessages->find('list',['keyField'=>'id','valueField'=>'application_id','conditions'=>['user_type'=>$key_stage]]);
					$fields_date  	= "created";
					if(!empty($from_date) && !empty($end_date))
					{
						$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
						$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
				        $FindApplicationIDs->where([function ($exp, $q) use ($StartTime, $EndTime,$fields_date) {
							return $exp->between($fields_date, $StartTime, $EndTime);
				   		}]);
			    	}
			    	$output_app 		= implode(",",$FindApplicationIDs->toArray());
			    	if(empty($output_app))
			    	{
			    		$output_app 	= '0';
			    	}
					$stage_where 			= $this->ApplyOnlineApprovals->APPROVED_FROM_GEDA. " AND  apply_onlines.id IN (".$output_app.")";
				}
				else
				{
					$sql_reg 			= '';
					if(!empty($from_date) && !empty($end_date))
					{
						$fields_date  	= "(( CASE WHEN 1 = 1 THEN( select created FROM apply_online_approvals
										    WHERE
										      apply_online_approvals.application_id = apply_onlines.id
										      AND apply_online_approvals.stage = $stage_where
										    group by
										      stage) END ))";

						$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
						$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
					    $sql_reg 		= " and $fields_date between '".$StartTime."' and '".$EndTime."'";
					}
				}

				$sql_first 			= " SELECT
									CASE WHEN 1=1 THEN
									(
										SELECT COUNT(apply_onlines.id)
										FROM apply_onlines
										INNER JOIN apply_online_approvals ON apply_onlines.id = apply_online_approvals.application_id
										INNER JOIN installers as INS ON INS.id = apply_onlines.installer_id
										WHERE $str_where AND apply_onlines.transmission_line = '1' AND apply_online_approvals.stage = $stage_where $sql $sql_reg
									) END AS TOTAL_REGISTERED_1,
									CASE WHEN 1=1 THEN
									(
										SELECT COUNT(apply_onlines.id)
										FROM apply_onlines
										INNER JOIN apply_online_approvals ON apply_onlines.id = apply_online_approvals.application_id
										INNER JOIN installers as INS ON INS.id = apply_onlines.installer_id
										WHERE $str_where AND apply_onlines.transmission_line = '3' AND apply_online_approvals.stage = $stage_where $sql $sql_reg
									) END AS TOTAL_REGISTERED_3";

			$applicationData_output = $connection->execute($sql_first)->fetchAll('assoc');
			$final_data[$key_stage][$val->id]	= $applicationData_output[0];

			}
		}

		$arr_status_dropdown = $this->ApplyOnlines->apply_online_dropdown_status;
		$installers_list 		= $this->Installers->getInstallerListReport();

		$this->set('DiscomMaster',$DiscomMaster);
		$this->set('final_data',$final_data);
		$this->set('arrDisStage',$this->arrDisStage);
        $this->set("pagetitle",'Discom Wise Summary');
       	$this->set("application_dropdown_status",$arr_status_dropdown);
       	$this->set("installers_list",$installers_list);
    }
    /**
	 *
	 * subdivisionSummary
	 *
	 * Behaviour : Public
	 *
	 *@param :
	 *
	 * @defination : Method is use to display subdivision summary report.
	 *
	 */
    public function subdivisionSummary()
    {
    	$member_id 			= $this->Session->read("Members.id");
    	$state 				= '';
		$branch_id 			= '';
		$main_branch_id		= '';
		$member_type 		= '';
		$final_data 		= array();

		$member_type 		= $this->Session->read('Members.member_type');
		$area 				= $this->Session->read('Members.area');
		$circle 			= $this->Session->read('Members.circle');
		$division 			= $this->Session->read('Members.division');
		$subdivision 		= $this->Session->read('Members.subdivision');
		$section 			= $this->Session->read('Members.section');
		if(empty($member_id) && $member_type!=$this->ApplyOnlines->JREDA)
		{
			return $this->redirect('home');
		}
		if($this->Session->check("Members.state")){
			$state 			= $this->Session->read("Members.state");
		}
		if (!empty($member_id) && $member_type == $this->ApplyOnlines->DISCOM)
		{
			$field      = "area";
			$id         = $area;

			if (!empty($section)) {
				$field      = "section";
				$id         = $section;
			} else if (!empty($subdivision)) {
				$field      = "subdivision";
				$id         = $subdivision;
			} else if (!empty($division)) {
				$field      = "division";
				$id         = $division;
			} else if (!empty($circle)) {
				$field      = "circle";
				$id         = $circle;
			}
			$main_branch_id = array("field"=>$field,"id"=>$id);
		}
		$array_request 		= $this->request->data;
		$from_date 			= isset($array_request['DateFrom'])?$array_request['DateFrom']:'';
		$end_date 			= isset($array_request['DateTo'])?$array_request['DateTo']:'';
		$application_status = isset($array_request['status'])?$array_request['status']:'';
		$installer_name 	= isset($array_request['installer_name'])?$array_request['installer_name']:'';
		$application_no 	= isset($array_request['application_no'])?$array_request['application_no']:'';
		$geda_application_no= isset($array_request['geda_application_no'])?$array_request['geda_application_no']:'';
		$discom 			= isset($array_request['discom'])?$array_request['discom']:'466';
		$sql 				= '';
		$arr_condition		= array();
		if(!empty($main_branch_id))
		{
			$sql .= " and apply_onlines.".$main_branch_id['field']." = '".$main_branch_id['id']."'";
			$arr_condition["apply_onlines.".$main_branch_id['field']]=$main_branch_id['id'];
		}
		if(!empty($installer_name))
	   	{
	   		//$sql .= " and INS.installer_name like '%".$installer_name."%'";
	   		//$arr_condition["INS.installer_name like"]="'%$installer_name%'";
	   		//$sql .= " and INS.id in ('".implode("','",$installer_name)."')";
	   		//$arr_condition["INS.id in "] = $installer_name;
	   	}
	   	if(!empty($application_no))
	   	{
	   		$sql .= " and application_no like '%".$application_no."%'";
	   		$arr_condition["application_no like"]="'%$application_no%'";
	   	}
	   	if(!empty($geda_application_no))
	   	{
	   		$sql .= " and geda_application_no like '%".$geda_application_no."%'";
	   		$arr_condition["geda_application_no like"]="'%$geda_application_no%'";
	   	}
	   	if($member_type!=6001)
	   	{
	   		$discom 			= $area;
	   	}
	   	$arrSubdivision 		= $this->DiscomMaster->find('all',array(
									'conditions'=> array('DiscomMaster.type'=>'4','DiscomMaster.area'=>$discom),
									'fields'	=> array('DiscomMaster.id','DiscomMaster.title','dm.title','circle.title','division.title'),
									'join' 		=> array('dm'=>array('table'=>'discom_master','conditions'=>'DiscomMaster.area = dm.id','type'=>'left'),
										'circle'=>array('table'=>'discom_master','conditions'=>'DiscomMaster.circle = circle.id','type'=>'left'),
										'division'=>array('table'=>'discom_master','conditions'=>'DiscomMaster.division = division.id','type'=>'left')),
									'order'		=> array('DiscomMaster.division'=>'asc','DiscomMaster.title'=>'asc')))->toArray();


		foreach($arrSubdivision as $key=>$val)
		{
			$str_where 			= " apply_onlines.subdivision = '".$val->id."'";
			$connection         = ConnectionManager::get('default');
			$fields 			= array();
			foreach($this->arrSubDivStage as $key_stage=>$stage_sql)
			{
				$stage_where 		= $key_stage;
				$sql_reg 			= '';
				$arr_condition_all 	= $arr_condition;
				switch($key_stage)
				{
					case '2222':
						$arr_condition_all['payment_approve']				= '0';
						$arr_condition_all['apply_onlines.subdivision']		= $val->id;
						$arr_condition_all['apply_online_approvals.stage'] 	= $this->ApplyOnlineApprovals->FEASIBILITY_APPROVAL;
						$arr_fea_sql 	= $this->FesibilityReport->find('all',array(
											'fields'=>['FesibilityReport.application_id'],
											'join'	=>['apply_onlines'=>['table'=>'apply_onlines','conditions'=>['FesibilityReport.application_id = apply_onlines.id'],'type'=>'left'],
												'apply_online_approvals'=>['table'=>'apply_online_approvals','conditions'=>['apply_onlines.id = apply_online_approvals.application_id'],'type'=>'inner']],
											'conditions'=>$arr_condition_all));

						if(!empty($from_date) && !empty($end_date))
				        {
				        	$fields_date  	= "apply_online_approvals.created";
							$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
							$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
					        $arr_fea_sql->where([function ($exp, $q) use ($StartTime, $EndTime,$fields_date) {
								return $exp->between($fields_date, $StartTime, $EndTime);
					   		}]);
				        }
				        $arr_fea 			= $arr_fea_sql->toArray();
						$count_fea 			= count($arr_fea);
						$fields[] 		= "((CASE WHEN 1=1 THEN( SELECT $count_fea ) END)) AS '$key_stage'";
					break;
					case '2111':
						$arr_condition_all['payment_approve']				= '1';
						$arr_condition_all['apply_onlines.subdivision']		= $val->id;
						$arr_condition_all['apply_online_approvals.stage'] 	= $this->ApplyOnlineApprovals->FEASIBILITY_APPROVAL;
						$arr_fea_sql 	= $this->FesibilityReport->find('all',array(
											'fields'=>['FesibilityReport.application_id'],
											'join'	=>['apply_onlines'=>['table'=>'apply_onlines','conditions'=>['FesibilityReport.application_id = apply_onlines.id'],'type'=>'left'],
														'apply_online_approvals'=>['table'=>'apply_online_approvals','conditions'=>['apply_onlines.id = apply_online_approvals.application_id'],'type'=>'inner']],
											'conditions'=>$arr_condition_all));
						if(!empty($from_date) && !empty($end_date))
				        {
				        	$fields_date  	= "FesibilityReport.payment_date";
							$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
							$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
					        $arr_fea_sql->where([function ($exp, $q) use ($StartTime, $EndTime,$fields_date) {
								return $exp->between($fields_date, $StartTime, $EndTime);
					   		}]);
				        }
				        $arr_fea 	= $arr_fea_sql->toArray();
						$count_fea 	= count($arr_fea);
						$fields[] 	= "((CASE WHEN 1=1 THEN( SELECT $count_fea ) END)) AS '$key_stage'";
					break;
					case '6002':
						$arr_condition_all['user_type']						= $key_stage;
						$arr_condition_all['apply_onlines.subdivision']		= $val->id;
						$arr_condition_all['apply_online_approvals.stage'] 	= $this->ApplyOnlineApprovals->DOCUMENT_VERIFIED;
						$FindApplicationIDSql 	= $this->ApplyonlineMessages->find('all',array(
													'fields'=>[	'ApplyonlineMessages.application_id'],
													'join'	=>[	'apply_onlines'=>['table'=>'				apply_onlines','conditions'=>['				ApplyonlineMessages.application_id = 			apply_onlines.id'],'type'=>'left']			,
																'apply_online_approvals'=>['table'=>'
																apply_online_approvals','conditions'=>['
																apply_online_approvals.application_id =
																apply_onlines.id'],'type'=>'left']
																],
													'conditions'=>$arr_condition_all));
						if(!empty($from_date) && !empty($end_date))
				        {
				        	$fields_date  	= "ApplyonlineMessages.created";
							$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
							$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
					        $FindApplicationIDSql->where([function ($exp, $q) use ($StartTime, $EndTime,$fields_date) {
								return $exp->between($fields_date, $StartTime, $EndTime);
					   		}]);
				        }
				        $FindApplicationIDs = $FindApplicationIDSql->toArray();
						$count_msg 			= count($FindApplicationIDs);
						$fields[] 			= "((CASE WHEN 1=1 THEN( SELECT $count_msg ) END)) AS '$key_stage'";
					break;
					case '6000':
						$stage_where 		= $this->ApplyOnlineApprovals->APPROVED_FROM_GEDA. " AND  apply_onlines.query_sent=1";
						if(!empty($from_date) && !empty($end_date))
						{
							$fields_date  	= "apply_onlines.query_date";
							$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
							$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
						    $stage_where 	.= " and $fields_date between '".$StartTime."' and '".$EndTime."'";
						}
						$sql_reg 			= '';
						$fields[] 			= "((CASE WHEN 1=1 THEN(
							                SELECT COUNT(apply_onlines.id)
							                FROM apply_onlines
							                INNER JOIN apply_online_approvals ON apply_onlines.id = apply_online_approvals.application_id
							                WHERE $str_where AND apply_online_approvals.stage = $stage_where $sql $sql_reg
										) END)) AS '$key_stage'";
					break;
					case '1777':
						$stage_where 		= $this->ApplyOnlineApprovals->METER_INSTALLATION." and charging_certificate.agreement_date Not in ('NULL','0000-00-00 00:00:00')";
						if(!empty($from_date) && !empty($end_date))
						{
							$fields_date  	= "charging_certificate.agreement_date";
							$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
							$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
						    $stage_where 			.= " and $fields_date between '".$StartTime."' and '".$EndTime."'";
						}
						$fields[] 		= "((CASE WHEN 1=1 THEN(
							                SELECT COUNT(apply_onlines.id)
							                FROM apply_onlines
							                INNER JOIN charging_certificate ON apply_onlines.id = charging_certificate.application_id
							                INNER JOIN apply_online_approvals ON apply_onlines.id = apply_online_approvals.application_id
							                WHERE $str_where AND apply_online_approvals.stage = $stage_where $sql $sql_reg
										) END)) AS '$key_stage'";
					break;
					default:
						if(!empty($from_date) && !empty($end_date))
						{
							$fields_date= "apply_online_approvals.created";
							$StartTime 	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
							$EndTime    = date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
						    $stage_where.= " and $fields_date between '".$StartTime."' and '".$EndTime."'";
						}
						$fields[] 		= "((CASE WHEN 1=1 THEN(
							                SELECT COUNT(apply_onlines.id)
							                FROM apply_onlines
							                INNER JOIN apply_online_approvals ON apply_onlines.id = apply_online_approvals.application_id
							                WHERE $str_where AND apply_online_approvals.stage = $stage_where $sql $sql_reg group by apply_online_approvals.stage
										) END)) AS '$key_stage'";
					break;
				}
			}
			$sql_first 			= " SELECT ".implode(",",$fields);

			$applicationData_output = $connection->execute($sql_first)->fetchAll('assoc');
			$final_data[$val->id]	= $applicationData_output[0];
		}
		if($member_type!=6001)
	   	{
	   		$conditionsArr 		= array('type'=>'1','id'=>$area);
	   	}
	   	else
	   	{
	   		$conditionsArr 		= array('type'=>'1');
	   	}
		$discom_list 			= $this->DiscomMaster->find('list',array('keyField'=>'id','valueField'=>'title','conditions'=>$conditionsArr))->toArray();
		$arr_status_dropdown 			= $this->ApplyOnlines->apply_online_dropdown_status;
		$installers_list 				= $this->Installers->getInstallerListReport();

		$this->set('final_data',$final_data);
        $this->set('arrSubDivStage',$this->arrSubDivStage);
        $this->set('arrSubdivision',$arrSubdivision);
        $this->set('discom_list',$discom_list);
       	$this->set("pagetitle",'Subdivision Wise Summary');
       	$this->set("application_dropdown_status",$arr_status_dropdown);
       	$this->set("installers_list",$installers_list);
    }
    /**
	 *
	 * talukaSummary
	 *
	 * Behaviour : Public
	 *
	 *@param :
	 *
	 * @defination : Method is use to display taluka summary report.
	 *
	 */
    public function talukaSummary()
    {
    	$member_id 			= $this->Session->read("Members.id");
    	$state 				= '';
		$branch_id 			= '';
		$main_branch_id		= '';
		$member_type 		= '';
		$final_data 		= array();

		$member_type 		= $this->Session->read('Members.member_type');
		$area 				= $this->Session->read('Members.area');
		$circle 			= $this->Session->read('Members.circle');
		$division 			= $this->Session->read('Members.division');
		$subdivision 		= $this->Session->read('Members.subdivision');
		$section 			= $this->Session->read('Members.section');
		if(empty($member_id) && $member_type!=$this->ApplyOnlines->JREDA)
		{
			return $this->redirect('home');
		}
		if (!empty($member_id) && $member_type == $this->ApplyOnlines->DISCOM)
		{
			$field      = "area";
			$id         = $area;

			if (!empty($section)) {
				$field      = "section";
				$id         = $section;
			} else if (!empty($subdivision)) {
				$field      = "subdivision";
				$id         = $subdivision;
			} else if (!empty($division)) {
				$field      = "division";
				$id         = $division;
			} else if (!empty($circle)) {
				$field      = "circle";
				$id         = $circle;
			}
			$main_branch_id = array("field"=>$field,"id"=>$id);
		}
		if($this->Session->check("Members.state")){
			$state 			= $this->Session->read("Members.state");
		}
		$array_request 		= $this->request->data;
		$from_date 			= isset($array_request['DateFrom'])?$array_request['DateFrom']:'';
		$end_date 			= isset($array_request['DateTo'])?$array_request['DateTo']:'';
		$application_status = isset($array_request['status'])?$array_request['status']:'';
		$installer_name 	= isset($array_request['installer_name'])?$array_request['installer_name']:'';
		$application_no 	= isset($array_request['application_no'])?$array_request['application_no']:'';
		$geda_application_no= isset($array_request['geda_application_no'])?$array_request['geda_application_no']:'';
		$discom 			= isset($array_request['discom'])?$array_request['discom']:'466';
		$sql 				= '';
		$arr_condition		= array();
		if(!empty($main_branch_id))
		{
			$sql .= " and apply_onlines.".$main_branch_id['field']." = '".$main_branch_id['id']."'";
			$arr_condition["apply_onlines.".$main_branch_id['field']]=$main_branch_id['id'];
		}
		if(!empty($installer_name))
	   	{
	   		$sql .= " and INS.installer_name like '%".$installer_name."%'";
	   		$arr_condition["INS.installer_name like"]="'%$installer_name%'";
	   	}
	   	if(!empty($application_no))
	   	{
	   		$sql .= " and application_no like '%".$application_no."%'";
	   		$arr_condition["application_no like"]="'%$application_no%'";
	   	}
	   	if(!empty($geda_application_no))
	   	{
	   		$sql .= " and geda_application_no like '%".$geda_application_no."%'";
	   		$arr_condition["geda_application_no like"]="'%$geda_application_no%'";
	   	}
	   	if($member_type!=6001)
	   	{
	   		$discom = $area;
	   	}
	   	$arrSubdivision 		= $this->DiscomMaster->find('all',array(
									'conditions'=> array('DiscomMaster.type'=>'4','DiscomMaster.area'=>$discom),
									'fields'	=> array('DiscomMaster.id','DiscomMaster.title','dm.title','circle.title','division.title'),
									'join' 		=> array('dm'=>array('table'=>'discom_master','conditions'=>'DiscomMaster.area = dm.id','type'=>'left'),
										'circle'=>array('table'=>'discom_master','conditions'=>'DiscomMaster.circle = circle.id','type'=>'left'),
										'division'=>array('table'=>'discom_master','conditions'=>'DiscomMaster.division = division.id','type'=>'left')),
									'order'		=> array('DiscomMaster.division'=>'asc','DiscomMaster.title'=>'asc')))->toArray();
	   	$arrSubdivision 		= $this->ApplyOnlines->find('all',array(
									'conditions'=> array('ApplyOnlines.area'=>$discom),
									'fields'	=> array('ApplyOnlines.address2','dm.title','circle.title','division.title','ApplyOnlines.area'),
									'join' 		=> array('dm'=>array('table'=>'discom_master','conditions'=>'ApplyOnlines.area = dm.id','type'=>'left'),
										'circle'=>array('table'=>'discom_master','conditions'=>'ApplyOnlines.circle = circle.id','type'=>'left'),
										'division'=>array('table'=>'discom_master','conditions'=>'ApplyOnlines.division = division.id','type'=>'left')),
									'order'		=> array('ApplyOnlines.address2'=>'asc')))->distinct(['ApplyOnlines.address2'])->toArray();

		foreach($arrSubdivision as $key=>$val)
		{
			$str_where 			= " apply_onlines.address2 = '".$val->address2."' and area='".$val->area."'";
			$connection         = ConnectionManager::get('default');
			$fields 			= array();
			foreach($this->arrSubDivStage as $key_stage=>$stage_sql)
			{
				$stage_where 		= $key_stage;
				$sql_reg 			= '';
				$arr_condition_all 	= $arr_condition;
				switch($key_stage)
				{
					case '2222':
						$arr_condition_all['payment_approve']				= '0';
						$arr_condition_all['apply_onlines.address2']		= $val->address2;
						$arr_condition_all['apply_online_approvals.stage'] 	= $this->ApplyOnlineApprovals->FEASIBILITY_APPROVAL;
						$arr_fea_sql 	= $this->FesibilityReport->find('all',array(
											'fields'=>['FesibilityReport.application_id'],
											'join'	=>['apply_onlines'=>['table'=>'apply_onlines','conditions'=>['FesibilityReport.application_id = apply_onlines.id'],'type'=>'left'],
												'apply_online_approvals'=>['table'=>'apply_online_approvals','conditions'=>['apply_onlines.id = apply_online_approvals.application_id'],'type'=>'inner']],
											'conditions'=>$arr_condition_all));

						if(!empty($from_date) && !empty($end_date))
				        {
				        	$fields_date  	= "apply_online_approvals.created";
							$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
							$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
					        $arr_fea_sql->where([function ($exp, $q) use ($StartTime, $EndTime,$fields_date) {
								return $exp->between($fields_date, $StartTime, $EndTime);
					   		}]);
				        }
				        $arr_fea 			= $arr_fea_sql->toArray();
						$count_fea 			= count($arr_fea);
						$fields[] 		= "((CASE WHEN 1=1 THEN( SELECT $count_fea ) END)) AS '$key_stage'";
					break;
					case '2111':
						$arr_condition_all['payment_approve']				= '1';
						$arr_condition_all['apply_onlines.address2']		= $val->address2;
						$arr_condition_all['apply_online_approvals.stage'] 	= $this->ApplyOnlineApprovals->FEASIBILITY_APPROVAL;
						$arr_fea_sql 	= $this->FesibilityReport->find('all',array(
											'fields'=>['FesibilityReport.application_id'],
											'join'	=>['apply_onlines'=>['table'=>'apply_onlines','conditions'=>['FesibilityReport.application_id = apply_onlines.id'],'type'=>'left'],
														'apply_online_approvals'=>['table'=>'apply_online_approvals','conditions'=>['apply_onlines.id = apply_online_approvals.application_id'],'type'=>'inner']],
											'conditions'=>$arr_condition_all));
						if(!empty($from_date) && !empty($end_date))
				        {
				        	$fields_date  	= "FesibilityReport.payment_date";
							$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
							$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
					        $arr_fea_sql->where([function ($exp, $q) use ($StartTime, $EndTime,$fields_date) {
								return $exp->between($fields_date, $StartTime, $EndTime);
					   		}]);
				        }
				        $arr_fea 	= $arr_fea_sql->toArray();
						$count_fea 	= count($arr_fea);
						$fields[] 	= "((CASE WHEN 1=1 THEN( SELECT $count_fea ) END)) AS '$key_stage'";
					break;
					case '6002':
						$arr_condition_all['user_type']						= $key_stage;
						$arr_condition_all['apply_onlines.address2']		= $val->address2;
						$arr_condition_all['apply_online_approvals.stage'] 	= $this->ApplyOnlineApprovals->DOCUMENT_VERIFIED;
						$FindApplicationIDSql 	= $this->ApplyonlineMessages->find('all',array(
													'fields'=>[	'ApplyonlineMessages.application_id'],
													'join'	=>[	'apply_onlines'=>['table'=>'				apply_onlines','conditions'=>['				ApplyonlineMessages.application_id = 			apply_onlines.id'],'type'=>'left']			,
																'apply_online_approvals'=>['table'=>'
																apply_online_approvals','conditions'=>['
																apply_online_approvals.application_id =
																apply_onlines.id'],'type'=>'left']
																],
													'conditions'=>$arr_condition_all));
						if(!empty($from_date) && !empty($end_date))
				        {
				        	$fields_date  	= "ApplyonlineMessages.created";
							$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
							$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
					        $FindApplicationIDSql->where([function ($exp, $q) use ($StartTime, $EndTime,$fields_date) {
								return $exp->between($fields_date, $StartTime, $EndTime);
					   		}]);
				        }
				        $FindApplicationIDs = $FindApplicationIDSql->toArray();
						$count_msg 			= count($FindApplicationIDs);
						$fields[] 			= "((CASE WHEN 1=1 THEN( SELECT $count_msg ) END)) AS '$key_stage'";
					break;
					case '6000':
						$stage_where 		= $this->ApplyOnlineApprovals->APPROVED_FROM_GEDA. " AND  apply_onlines.query_sent=1";
						if(!empty($from_date) && !empty($end_date))
						{
							$fields_date  	= "apply_onlines.query_date";
							$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
							$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
						    $stage_where 	.= " and $fields_date between '".$StartTime."' and '".$EndTime."'";
						}
						$sql_reg 			= '';
						$fields[] 			= "((CASE WHEN 1=1 THEN(
							                SELECT COUNT(apply_onlines.id)
							                FROM apply_onlines
							                INNER JOIN apply_online_approvals ON apply_onlines.id = apply_online_approvals.application_id
							                WHERE $str_where AND apply_online_approvals.stage = $stage_where $sql $sql_reg
										) END)) AS '$key_stage'";
					break;
					case '1777':
						$stage_where 		= $this->ApplyOnlineApprovals->METER_INSTALLATION." and charging_certificate.agreement_date Not in ('NULL','0000-00-00 00:00:00')";
						if(!empty($from_date) && !empty($end_date))
						{
							$fields_date  	= "charging_certificate.agreement_date";
							$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
							$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
						    $stage_where 			.= " and $fields_date between '".$StartTime."' and '".$EndTime."'";
						}
						$fields[] 		= "((CASE WHEN 1=1 THEN(
							                SELECT COUNT(apply_onlines.id)
							                FROM apply_onlines
							                INNER JOIN charging_certificate ON apply_onlines.id = charging_certificate.application_id
							                INNER JOIN apply_online_approvals ON apply_onlines.id = apply_online_approvals.application_id
							                WHERE $str_where AND apply_online_approvals.stage = $stage_where $sql $sql_reg
										) END)) AS '$key_stage'";
					break;
					default:
						if(!empty($from_date) && !empty($end_date))
						{
							$fields_date= "apply_online_approvals.created";
							$StartTime 	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
							$EndTime    = date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
						    $stage_where.= " and $fields_date between '".$StartTime."' and '".$EndTime."'";
						}
						$fields[] 		= "((CASE WHEN 1=1 THEN(
							                SELECT COUNT(apply_onlines.id)
							                FROM apply_onlines
							                INNER JOIN apply_online_approvals ON apply_onlines.id = apply_online_approvals.application_id
							                WHERE $str_where AND apply_online_approvals.stage = $stage_where $sql $sql_reg group by apply_online_approvals.stage
										) END)) AS '$key_stage'";
					break;
				}
			}
			$sql_first 			= " SELECT ".implode(",",$fields);

			$applicationData_output = $connection->execute($sql_first)->fetchAll('assoc');
			$final_data[$val->address2]	= $applicationData_output[0];
		}
		if($member_type!=6001)
	   	{
	   		$conditionsArr 		= array('type'=>'1','id'=>$area);
	   	}
	   	else
	   	{
	   		$conditionsArr 		= array('type'=>'1');
	   	}
		$discom_list 			= $this->DiscomMaster->find('list',array('keyField'=>'id','valueField'=>'title','conditions'=>$conditionsArr))->toArray();
		$arr_status_dropdown 	= $this->ApplyOnlines->apply_online_dropdown_status;
		$this->set('final_data',$final_data);
        $this->set('arrSubDivStage',$this->arrSubDivStage);
        $this->set('arrSubdivision',$arrSubdivision);
        $this->set('discom_list',$discom_list);
       	$this->set("pagetitle",'Subdivision Wise Summary');
       	$this->set("application_dropdown_status",$arr_status_dropdown);
    }
    public function fetch_dataDiscom()
    {
    	$member_id 			= $this->Session->read("Members.id");
    	$customer_id 		= $this->Session->read("Customers.id");
		$state 				= '';
		$branch_id 			= '';
		$main_branch_id		= '';
		$member_type 		= '';
		$array_request=array();
		$return_count=0;
		$member_type 	= $this->Session->read('Members.member_type');
		$area 			= $this->Session->read('Members.area');
		$circle 		= $this->Session->read('Members.circle');
		$division 		= $this->Session->read('Members.division');
		$subdivision 	= $this->Session->read('Members.subdivision');
		$section 		= $this->Session->read('Members.section');

		if($this->Session->check("Members.state")){
			$state 		= $this->Session->read("Members.state");
		}
		if($this->Session->check("Members.member_type")){
			$member_type = $this->Session->read("Members.member_type");
		}

		$main_branch_id = array();
		if (!empty($member_id) && $member_type == $this->ApplyOnlines->DISCOM)
		{
			$field      = "area";
			$id         = $area;

			if (!empty($section)) {
				$field      = "section";
				$id         = $section;
			} else if (!empty($subdivision)) {
				$field      = "subdivision";
				$id         = $subdivision;
			} else if (!empty($division)) {
				$field      = "division";
				$id         = $division;
			} else if (!empty($circle)) {
				$field      = "circle";
				$id         = $circle;
			}
			$main_branch_id = array("field"=>$field,"id"=>$id);
		}
		$DateField 			= isset($array_request['DateField'])?$array_request['DateField']:'';
		$from_date 			= isset($array_request['DateFrom'])?$array_request['DateFrom']:'';
		$end_date 			= isset($array_request['DateTo'])?$array_request['DateTo']:'';
		$this->request->data['mis_export_fields'] = array("sr_no",
										"submited_date",
										"application_status",
										"Scheme",
										"application_no",
										"geda_application_no",
										"installer_name",
										"installer_category",
										"allowed_bands",
										"name",
										"pv_capacity",
										"DisCom_Name",
										"consumer_no",
										"division_title",
										"subdiv_title",
										"sanction_load_contract_demand",
										"Invertor_Phase",
										"App_Category",
										"Net_Meter_By",
										"latitude",
										"longitude",
										"consumer_email",
										"consumer_mobile",
										"installer_email",
										"installer_mobile",
										"Name_Prefix",
										"First_Name",
										"Middle_Name",
										"Last_Name",
										"landline_no",
										"Street_House_No",
										"Taluka",
										"District",
										"City_Village",
										"State",
										"Pin",
										"comunication_address",
										"Profile_Photo",
										"Premises",
										"Electricity_Bill",
										"Aadhaar_No_Entered",
										"Self_Owned",
										"No_Subsidy_Required",
										"OTP_Verified_On",
										"Signed_Uploaded_Date",
										"Last_Commentt",
										"Last_Comment_Date",
										"Last_Comment_Replied_Date",
										"Document_Verified_Date",
										"Fesibility_Report_Date",
										"Quotation_No",
										"Discom_Estimation",
										"Payment_Due_Date",
										"Payment_Received",
										"Payment_Date",
										"Self_Certificate",
										"drawing_app_no",
										"drawing_approved_date",
										"workorder_number",
										"workorder_number_date",
										"installation_start_date",
										"installation_end_data",
										"meter_serial_no_make",
										"meter_serial_no",
										"solar_meter_manufacture",
										"solar_meter_serial_no",
										"meter_installed_date",
										"agreement_date");
		$fields_date  		= "apply_online_approvals.created";
		if (!empty($DateField) && in_array($DateField,array("apply_online_approvals.created","charging_certificate.meter_installed_date"))) {
			$fields_date 	= $DateField;
		}
		$whereCharging 		= '';
		if($fields_date != 'apply_online_approvals.created' && !empty($from_date) && !empty($end_date))
	    {
	    	$whereCharging 	= ' and '.$fields_date.' between '.$from_date.' and '.$end_date;
	    }
    	$connection         = ConnectionManager::get('default');
    	$sql_first 			= $this->GetReportFields();
		$social_consumer 	= '';
		if (!empty($member_id)) {
			if(in_array($member_id,$this->AllowedGedaIDS)) {
	        } else {
	        	//$social_consumer = " and P.project_social_consumer !='1'";
	        }
		} else {
			//$social_consumer = " and P.project_social_consumer !='1'";
		}
		$sql_count 	= "	select count(0)";
		$sql 		= "	FROM apply_onlines AO
						INNER JOIN projects as P ON P.id = AO.project_id
						INNER JOIN installers as INS ON INS.id = AO.installer_id
						INNER JOIN installer_category_mapping as ICM ON ICM.installer_id = INS.id
						INNER JOIN parameters as PM ON AO.category = PM.para_id
						INNER JOIN branch_masters as DM ON AO.discom = DM.id
						LEFT JOIN discom_master as D ON AO.division = D.id
						LEFT JOIN discom_master as SD ON AO.subdivision = SD.id
						LEFT JOIN fesibility_report as fea ON AO.id = fea.application_id
						LEFT JOIN mis_report_data as MIS ON AO.id = MIS.application_id
						WHERE AO.application_status not in ('99','29','30','22','0')
						and AO.application_status is not NULL $social_consumer ";
		$application_status = isset($array_request['status'])?$array_request['status']:'';
		$installer_name 	= isset($array_request['installer_name_multi'])?$array_request['installer_name_multi']:'';
		$application_no 	= isset($array_request['application_no'])?$array_request['application_no']:'';
		$geda_application_no= isset($array_request['geda_application_no'])?$array_request['geda_application_no']:'';
		$payment_status 	= isset($array_request['payment_status'])?$array_request['payment_status']:'';
		if(!empty($main_branch_id))
		{
			$sql .= " and AO.".$main_branch_id['field']." = '".$main_branch_id['id']."'";
			$sql .= " and (AO.payment_status = '1' or AO.category='3001')";
		}
		if(!empty($application_status))
		{
			$passStatus = $this->ApplyOnlines->apply_online_status_key[$application_status];
	        if($passStatus == '9999')
	        {
	            $sql .= " and AO.application_status = '".$this->ApplyOnlineApprovals->APPLICATION_SUBMITTED."'";
	        }
	        else
	        {
	        	if($fields_date == 'apply_online_approvals.created' && !empty($from_date) && !empty($end_date)) {
	            	$FindApplicationIDs     = $this->ApplyOnlineApprovals->find('list',['keyField'=>'id','valueField'=>'application_id','conditions'=>['stage'=>$this->ApplyOnlines->apply_online_status_key[$application_status]]]);
	            	$StartTime    		= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
					$EndTime    		= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
		    		$FindApplicationIDs->where([function ($exp, $q) use ($StartTime, $EndTime,$fields_date) {
						return $exp->between('ApplyOnlineApprovals.created', $StartTime, $EndTime);
			   		}]);
			   		$FindApplicationIDs = $FindApplicationIDs->toArray();
			   		if (!empty($FindApplicationIDs)) {
		                $sql .= " and AO.id IN (".implode(",",array_unique($FindApplicationIDs)).")";
		                if($passStatus != $this->ApplyOnlineApprovals->APPLICATION_CANCELLED)
		                {
		                    $sql .= " and AO.application_status != '".$this->ApplyOnlineApprovals->APPLICATION_CANCELLED."'";
		                }
		            } else {
		                $sql .= " and AO.id = '0'";
		            }
	            }
	            if($fields_date == 'charging_certificate.meter_installed_date' && !empty($from_date) && !empty($end_date)) {
	            	$sql .= " and MIS.meter_installed_date BETWEEN '".$from_date."' AND '".$end_date."' ";
	            }
	        }
		}
		if(!empty($installer_name))
	   	{
	   		$sql .= " and AO.installer_id in ('".implode("','",$installer_name)."') ";
	   	}
	   	if(!empty($application_no))
	   	{
	   		$sql .= " and AO.application_no like '%".$application_no."%'";
	   	}
	   	if(!empty($geda_application_no))
	   	{
	   		$sql .= " and AO.geda_application_no like '%".$geda_application_no."%'";
	   	}
	   	if($payment_status!='')
	   	{
	   		$sql .= " and fea.payment_approve = '".$payment_status."'";
	   	}
	   	$sql .= " and AO.district like '%DOHAD%'";
	   	if(!empty($customer_id))
	   	{
	   		$installerdata	= $this->Customers->find('all', array('fields'=>array('installer_id'),'conditions'=>array('id'=>$customer_id)))->first();
			$installer_id 	= (isset($installerdata['installer_id'])?$installerdata['installer_id']:0);
			$sql .= " and AO.installer_id='".$installer_id."'";
	   	}

	   		$sql .= " order by app_created_date desc, AO.id desc ";
	   		$applicationData_output = $connection->execute($sql_first.$sql)->fetchAll('assoc');
	   		//return $applicationData_output;

	   	$applicationData 	= $applicationData_output;
		$PhpExcel 			= $this->PhpExcel;
		$PhpExcel->createExcel();
		$objDrawing 		= new \PHPExcel_Worksheet_MemoryDrawing();
		$objDrawing->setCoordinates('A1');
		$objDrawing->setWorksheet($PhpExcel->getExcelObj()->getActiveSheet());
		$j 					= 1;
		$i 					= 1;
		foreach ($this->ExportFields as $Field_Name) {
			$RowName 	= $this->GetExcelColumnName($i);
			$ColTitle  	= isset($this->arrReportFields[$Field_Name])?$this->arrReportFields[$Field_Name]:"";
			$PhpExcel->writeCellValue($RowName.$j,$ColTitle);
			$PhpExcel->getExcelObj()->getActiveSheet()->getColumnDimension($RowName)->setAutoSize(true);
			$i++;
		}
		$j++;
		foreach($applicationData as $key=>$application_data) {
			$this->WriteReportData($PhpExcel,$j,$application_data);
			$j++;
		}
		$PhpExcel->downloadFile(time());
		exit;
    }

	/**
	 *
	 * validateAccess
	 *
	 * Behaviour : Public
	 *
	 * @param : $isRistricted   : Value is true of false, base on this restriction is set in admin adrea
	 * @defination : Method is use to set admin area use for admin base on restriction set for particular
	 *
	 */
	public function validateAccess($REPORT_TYPE="")
	{
		switch ($REPORT_TYPE) {
			case 'APPLICATION_PAYMENT_REPORT':
			{
				$member_type = $this->Session->read('Members.member_type');
				if ($member_type == $this->ApplyOnlines->JREDA) {
					return true;
				}
				break;
			}
		}
		$this->Flash->error('You are not authorized to access this section.');
		return $this->redirect(URL_HTTP.'/apply-online-list');
	}

	/**
	 *
	 * getapplicationpaymentreport
	 *
	 * Behaviour : Public
	 *
	 * @param :
	 * @defination : Method is use to get application payment report
	 *
	 */
	public function getapplicationpaymentreport()
	{
		$member_id = $this->Session->read("Members.id");
		$this->validateAccess("APPLICATION_PAYMENT_REPORT");
		$array_request 		= $this->request->data;
		if (isset($array_request['download']) && !empty($array_request['download']))
		{
			$from_date 			= isset($array_request['DateFrom'])?$array_request['DateFrom']:'';
			$end_date 			= isset($array_request['DateTo'])?$array_request['DateTo']:'';
			$WhereCond			= "";
			if (!empty($from_date) && !empty($end_date))
			{
				$START_TIME	= date("Y-m-d",strtotime($from_date))." 00:00:00";
				$END_TIME	= date("Y-m-d",strtotime($end_date))." 23:59:59";
				$WhereCond 	= " AND applyonline_payment.payment_dt BETWEEN '".$START_TIME."' AND '".$END_TIME."'";
			}

			$SELECT_SQL 	= "	SELECT apply_onlines.application_no, apply_onlines.geda_application_no, apply_onlines.consumer_no,
								apply_onlines.tno,apply_onlines.pv_capacity,applyonline_payment.payment_dt,
								payumoney.transaction_id,payumoney.receipt_no
								FROM apply_onlines
								INNER JOIN applyonline_payment ON applyonline_payment.application_id = apply_onlines.id
								INNER JOIN payumoney ON applyonline_payment.payment_id = payumoney.id
								WHERE payumoney.payment_status = 'success' $WhereCond
								ORDER BY applyonline_payment.payment_dt ASC";
			$connection 	= ConnectionManager::get('default');
			$AppPaymentRes 	= $connection->execute($SELECT_SQL)->fetchAll('assoc');
			$arrResult		= array();
			$ExportFields 	= array("sr_no"=>"Sr No.",
									"application_no"=>"Application No",
									"geda_application_no"=>"GEDA Registration No",
									"consumer_no"=>"Consumer No",
									"tno"=>"T NO",
									"pv_capacity"=>"PV Capacity",
									"payment_dt"=>"Payment Date",
									"transaction_id"=>"Transaction ID",
									"receipt_no"=>"Receipt No");
			$PhpExcel 			= $this->PhpExcel;
			$PhpExcel->createExcel();
			$R 					= 1;
			$C 					= 1;
			foreach ($ExportFields as $Field_Name=>$ColTitle) {
				$RowName 	= $this->GetExcelColumnName($C);
				$PhpExcel->writeCellValue($RowName.$R,$ColTitle);
				$PhpExcel->getExcelObj()->getActiveSheet(1)->getColumnDimension($RowName)->setAutoSize(true);
				$C++;
			}
			$R++;
			foreach($AppPaymentRes as $RowID=>$Report_Data)
			{
				$C = 1;
				foreach ($ExportFields as $Field_Name=>$ColTitle)
				{
					$RowName = $this->GetExcelColumnName($C);
					$RowData = "";
					switch ($Field_Name) {
						case 'sr_no':
							$RowData = ($RowID+1);
							break;
						case 'payment_dt':
							$RowData = date("Y-m-d H:i A",strtotime($Report_Data[$Field_Name]));
							break;
						default:
							$RowData = isset($Report_Data[$Field_Name])?$Report_Data[$Field_Name]:"";
						break;
					}
					if ($RowData == "0000-00-00 00:00:00" || $RowData == "0000-00-00") {
						$RowData = "";
					}
					$PhpExcel->writeCellValue($RowName.$R,$RowData);
					$C++;
				}
				$R++;
			}
			$PhpExcel->downloadFile(time());
			exit;
		}
		$this->set("pagetitle",'Download Application Payment Report');
		$this->render("application_payment_report");
	}
	/**
	*
	* DownloadMISReport
	*
	* Behaviour : public
	*
	* Parameter : discom
	*
	* @defination : Method is used to get DownloadMISReport.
	*
	*/
	public function DownloadMISReport()
	{
		$this->autoRender	= false;
		$member_id 			= $this->Session->read("Members.id");
		$state 				= '';
		$branch_id 			= '';
		$main_branch_id		= '';
		$member_type 		= '';
		$final_data 		= array();
		$member_type 		= $this->Session->read('Members.member_type');
		$area 				= $this->Session->read('Members.area');
		$circle 			= $this->Session->read('Members.circle');
		$division 			= $this->Session->read('Members.division');
		$subdivision 		= $this->Session->read('Members.subdivision');
		$section 			= $this->Session->read('Members.section');

		if(!empty($member_id) && ($member_type==$this->ApplyOnlines->JREDA ||  $member_type==$this->ApplyOnlines->DISCOM))
		{
			if($member_type==$this->ApplyOnlines->JREDA)
			{
				$ZipFileName = MISREPORT_PATH.date("Ymd")."MISReport.zip";
				if (!file_exists($ZipFileName)) {
					$ZipFileName = MISREPORT_PATH.date("Ymd",strtotime("-1 day"))."MISReport.zip";
				}
			}
			elseif($member_type==$this->ApplyOnlines->DISCOM && empty($circle) && empty($division) && empty($subdivision))
			{
				$discom_short_name 	= $this->DiscomMaster->find("all",['conditions'=>['id'=>$area]])->first();
				$shortName 			= $area;
				if(!empty($discom_short_name))
				{
					//$shortName 		= $discom_short_name->title;
					$shortName 		= str_replace(array(" "),array("_"),$discom_short_name->title);
				}
				$ZipFileName    	= MISREPORT_PATH.date("Ymd")."MISReport_".$shortName.".zip";
				if (!file_exists($ZipFileName)) {
					$ZipFileName = MISREPORT_PATH.date("Ymd",strtotime("-1 day"))."MISReport_".$shortName.".zip";
				}
			}
			else
			{
				return $this->redirect('home');
				exit;
			}
			if (file_exists($ZipFileName)) {
				header('Content-Type: application/zip');
				header('Content-Disposition: attachment; filename="'.basename($ZipFileName).'"');
				header('Content-Length: ' . filesize($ZipFileName));
				flush();
				readfile($ZipFileName);
			}
		}
		else
		{
			return $this->redirect('home');
		}
		exit;
	}
	/**
	*
	* DownloadDeleteMISReport
	*
	* Behaviour : public
	*
	* Parameter : discom
	*
	* @defination : Method is used to get DownloadDeleteMISReport.
	*
	*/
	public function DownloadExtendedMISReport()
	{
		$this->autoRender	= false;
		$member_id 			= $this->Session->read("Members.id");
		$member_type 		= $this->Session->read('Members.member_type');
		$authority_account 	= $this->Session->read('Members.authority_account');

		if(!empty($member_id) && ($member_type==$this->ApplyOnlines->JREDA) && $authority_account == 1)
		{
			if($member_type==$this->ApplyOnlines->JREDA)
			{
				$ZipFileName = MISREPORT_PATH.date("Ymd")."MISExtendedReport.zip";
				if (!file_exists($ZipFileName)) {
					$ZipFileName = MISREPORT_PATH.date("Ymd")."MISExtendedReport.zip";
				}
			}
			else
			{
				return $this->redirect('home');
				exit;
			}
			if (file_exists($ZipFileName)) {
				header('Content-Type: application/zip');
				header('Content-Disposition: attachment; filename="'.basename($ZipFileName).'"');
				header('Content-Length: ' . filesize($ZipFileName));
				flush();
				readfile($ZipFileName);
			}
		}
		else
		{
			return $this->redirect('home');
		}
		exit;
	}
	/**
	*
	* getCircleReport
	*
	* Behaviour : public
	*
	* Parameter : discom
	*
	* @defination : Method is used to get division for perticular discom.
	*
	*/
	public function getCircleReport()
	{
		$this->autoRender 		= false;
		$discom 				= isset($this->request->data['discom'])?$this->request->data['discom']:0;
		$sel_circle 			= isset($this->request->data['sel_circle'])?decode($this->request->data['sel_circle']):0;
		$circle_login 			= $this->Session->read('Members.circle');
		if(!empty($circle_login)) {
			$sel_circle 		= $circle_login;
		}
		$data 					= array();
		if (!empty($discom)) {
			$branch_detail 		= $this->BranchMasters->find('all',array('conditions'=>array('id'=>$discom)))->first();
			if (!empty($sel_circle)) {
				$circle 	= $this->DiscomMaster->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['DiscomMaster.id'=>$sel_circle,'status'=>'1']]);
			} else {
				$circle		= $this->DiscomMaster->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['DiscomMaster.area'=>$branch_detail->discom_id,'DiscomMaster.type'=>2,'status'=>'1']]);
			}
			$data['circle'] 	= $circle;
		}
		$this->ApiToken->SetAPIResponse('msg', 'list of circle');
		$this->ApiToken->SetAPIResponse('data', $data);
		$this->ApiToken->SetAPIResponse('type','ok');
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	*
	* getDivisionReport
	*
	* Behaviour : public
	*
	* Parameter : discom
	*
	* @defination : Method is used to get division for perticular discom.
	*
	*/
	public function getDivisionReport()
	{
		$this->autoRender 		= false;
		$discom 				= isset($this->request->data['discom'])?$this->request->data['discom']:0;
		$circle 				= isset($this->request->data['circle'])?$this->request->data['circle']:0;
		$sel_division 			= isset($this->request->data['sel_division'])?decode($this->request->data['sel_division']):0;
		$division_login 		= $this->Session->read('Members.division');
		if(!empty($division_login)) {
			$sel_division 		= $division_login;
		}
		$data 					= array();
		if (!empty($discom)) {
			$branch_detail 		= $this->BranchMasters->find('all',array('conditions'=>array('id'=>$discom)))->first();
			if (!empty($sel_division)) {
				$division 	= $this->DiscomMaster->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['DiscomMaster.id'=>$sel_division,'status'=>'1']]);
			} else {
				$division 	= $this->DiscomMaster->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['DiscomMaster.area'=>$branch_detail->discom_id,'DiscomMaster.circle'=>$circle,'DiscomMaster.type'=>3,'status'=>'1']]);
			}
			$data['division'] 	= $division;
		}
		$this->ApiToken->SetAPIResponse('msg', 'list of division');
		$this->ApiToken->SetAPIResponse('data', $data);
		$this->ApiToken->SetAPIResponse('type','ok');
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	*
	* getSubdivisionReport
	*
	* Behaviour : public
	*
	* Parameter : discom
	*
	* @defination : Method is used to get division for perticular discom.
	*
	*/
	public function getSubdivisionReport()
	{
		$this->autoRender 		= false;
		$discom 				= isset($this->request->data['discom'])?$this->request->data['discom']:0;
		$circle 				= isset($this->request->data['circle'])?$this->request->data['circle']:0;
		$division 				= isset($this->request->data['division'])?$this->request->data['division']:0;
		$subdivision_login 		= $this->Session->read('Members.subdivision');
		$data 					= array();
		if (!empty($discom)) {
			$branch_detail 			= $this->BranchMasters->find('all',array('conditions'=>array('id'=>$discom)))->first();
			$arrConditions 		= ['DiscomMaster.area'=>$branch_detail->discom_id,'DiscomMaster.circle'=>$circle,'DiscomMaster.division'=>$division,'DiscomMaster.type'=>4,'status'=>'1'];
			if(!empty($subdivision_login)) {
				array_push(($arrConditions), array('DiscomMaster.id'=>$subdivision_login));
			}
			$division 				= $this->DiscomMaster->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>$arrConditions]);
			$data['subdivision'] 	= $division;
		}
		$this->ApiToken->SetAPIResponse('msg', 'list of subdivision');
		$this->ApiToken->SetAPIResponse('data', $data);
		$this->ApiToken->SetAPIResponse('type','ok');
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	public function RFIDReport()
    {
    	$member_id 			= $this->Session->read("Members.id");
    	$customer_id 		= $this->Session->read("Customers.id");
		$state 				= '';
		$branch_id 			= '';
		$main_branch_id		= '';
		$member_type 		= '';

		$member_type 	= $this->Session->read('Members.member_type');
		$area 			= $this->Session->read('Members.area');
		$circle 		= $this->Session->read('Members.circle');
		$division 		= $this->Session->read('Members.division');
		$subdivision 	= $this->Session->read('Members.subdivision');
		$section 		= $this->Session->read('Members.section');
		if(empty($member_id) && empty($customer_id)) {
			return $this->redirect('home');
		}
		if($this->Session->check("Members.state")){
			$state = $this->Session->read("Members.state");
		}
		if($this->Session->check("Members.member_type")){
			$member_type = $this->Session->read("Members.member_type");
		}
		$main_branch_id = array();
		if (!empty($member_id) && $member_type == $this->ApplyOnlines->DISCOM)
		{
			$field      = "area";
			$id         = $area;
			if (!empty($section)) {
				$field      = "section";
				$id         = $section;
			} else if (!empty($subdivision)) {
				$field      = "subdivision";
				$id         = $subdivision;
			} else if (!empty($division)) {
				$field      = "division";
				$id         = $division;
			} else if (!empty($circle)) {
				$field      = "circle";
				$id         = $circle;
			}
			$main_branch_id = array("field"=>$field,"id"=>$id);
		}
		$DateField 			= isset($this->request->data['DateField'])?$this->request->data['DateField']:'';
		$from_date 			= isset($this->request->data['DateFrom'])?$this->request->data['DateFrom']:'';
		$end_date 			= isset($this->request->data['DateTo'])?$this->request->data['DateTo']:'';
		$application_status = isset($this->request->data['status'])?$this->request->data['status']:'';
		$installer_name 	= (isset($this->request->data['installer_name_multi']) && !empty($this->request->data['installer_name_multi']))?explode(",",$this->request->data['installer_name_multi']):'';
		$application_no 	= isset($this->request->data['application_no'])?$this->request->data['application_no']:'';
		$geda_application_no= isset($this->request->data['geda_application_no'])?$this->request->data['geda_application_no']:'';
		$payment_status		= isset($this->request->data['payment_status'])?$this->request->data['payment_status']:'';
		$govt_agency		= isset($this->request->data['govt_agency'])?$this->request->data['govt_agency']:0;

        $arrAdminuserList	= array();
        $arrUserType		= array();
        $arrCondition		= array();
        $this->SortBy		= "apply_online_approvals.created";
        $this->Direction	= "DESC";
        $this->intLimit		= 50;
        $this->CurrentPage  = 1;
        $option 			= array();

        $option['colName']  = array('id','application_no','application_status','pv_capacity','rfid_upload','undertaking_upload','undertaking_upload_sample','action');
        $sortArr=array('installer_name'=>'installers.installer_name');//,'submitted_on'=>'apply_online_approvals.created'
        $this->SetSortingVars('ApplyOnlines',$option,$sortArr);
        $arrCondition		= array('application_status not in'=> array('99','29','30','22','0'));
        if(!empty($main_branch_id))
		{
 			array_push($arrCondition, array('ApplyOnlines.'.$main_branch_id['field']=>$main_branch_id['id']));
 			array_push($arrCondition, array('OR'=>['ApplyOnlines.payment_status'=>'1','ApplyOnlines.category'=>'3001']));
		}
		$fields_date  	= "apply_online_approvals.created";
		if (!empty($DateField) && in_array($DateField,array("apply_online_approvals.created","charging_certificate.meter_installed_date"))) {
			$fields_date = $DateField;
		}
		if(!empty($application_status))
		{
			$passStatus = $this->ApplyOnlines->apply_online_status_key[$application_status];
	        if($passStatus == '9999')
	        {
	            array_push($arrCondition,array('ApplyOnlines.application_status'=>$this->ApplyOnlineApprovals->APPLICATION_SUBMITTED));
	        }
	        else
	        {
	            $FindApplicationIDs     = $this->ApplyOnlineApprovals->find('list',['keyField'=>'id','valueField'=>'application_id','conditions'=>['stage'=>$this->ApplyOnlines->apply_online_status_key[$application_status]]]);
	            if($fields_date == 'apply_online_approvals.created' && !empty($from_date) && !empty($end_date))
	            {
	            	$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
					$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
		    		$FindApplicationIDs->where([function ($exp, $q) use ($StartTime, $EndTime,$fields_date) {
						return $exp->between('ApplyOnlineApprovals.created', $StartTime, $EndTime);
			   		}]);
	            }
			   	$FindApplicationIDs = $FindApplicationIDs->toArray();
	            if (!empty($FindApplicationIDs)) {
	                array_push($arrCondition,array('ApplyOnlines.id IN ' => array_unique($FindApplicationIDs)));
	                if($passStatus != $this->ApplyOnlineApprovals->APPLICATION_CANCELLED)
	                {
	                    array_push($arrCondition,array('ApplyOnlines.application_status != ' => $this->ApplyOnlineApprovals->APPLICATION_CANCELLED));
	                }
	            } else {
	                array_push($arrCondition,array('ApplyOnlines.id' => 0 ));
	            }
	        }
		}
       	if(!empty($installer_name)) {
       		$arrCondition['ApplyOnlines.installer_id in'] = $installer_name;
       	}
       	if(!empty($application_no)) {
       		array_push($arrCondition, array('ApplyOnlines.application_no like'=>'%'.$application_no.'%'));
       	}
       	// if(!empty($geda_application_no)) {
       	// 	array_push($arrCondition, array('ApplyOnlines.geda_application_no like'=>'%'.$geda_application_no.'%'));
       	// }
       	if(!empty($customer_id)) {
       		$installerdata	= $this->Customers->find('all', array('conditions'=>array('id'=>$customer_id)))->first();
			$installer_id 	= (isset($installerdata['installer_id'])?$installerdata['installer_id']:0);
			array_push($arrCondition, array('ApplyOnlines.installer_id' => $installer_id));
       	}
       	if($payment_status!='') {
            array_push($arrCondition,array('fesibility_report.payment_approve'=>$payment_status));
        }
        if($govt_agency!='') {
            array_push($arrCondition,array('ApplyOnlines.govt_agency'=>$govt_agency));
        }
       	$social_consumer = '';
		array_push($arrCondition,array('apply_online_approvals.stage'=>'17'));
        $query_data=$this->ApplyOnlines->find('all',array(
            'fields'=>array('ApplyOnlines.id','ApplyOnlines.application_status','ApplyOnlines.application_no','ApplyOnlines.geda_application_no','ApplyOnlines.pv_capacity','ApplyOnlines.rfid_upload','ApplyOnlines.undertaking_upload','installers.installer_name','apply_online_approvals.created','ApplyOnlines.payment_status'),
            'join'=>array('installers'=>array('table'=>'installers','conditions'=>'ApplyOnlines.installer_id = installers.id','type'=>'inner'),
            	'apply_online_approvals'=>array('table'=>'apply_online_approvals','conditions'=>'ApplyOnlines.id = apply_online_approvals.application_id','type'=>'left'),
            	'projects'=>array('table'=>'projects','conditions'=>'ApplyOnlines.project_id = projects.id','type'=>'left'),
            	'fesibility_report'=>array('table'=>'fesibility_report','conditions'=>'ApplyOnlines.id = fesibility_report.application_id','type'=>'left'),
            	'charging_certificate'=>array('table'=>'charging_certificate','conditions'=>'ApplyOnlines.id = charging_certificate.application_id','type'=>'left'),
            ),
            'conditions' => $arrCondition,
            'order'=>array($this->SortBy=>$this->Direction),
            'page'=> $this->CurrentPage,
            'limit' => $this->intLimit));

        if($fields_date != 'apply_online_approvals.created' && !empty($from_date) && !empty($end_date))
        {
			$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
			$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
	        $query_data->where([function ($exp, $q) use ($StartTime, $EndTime,$fields_date) {
				return $exp->between($fields_date, $StartTime, $EndTime);
	   		}]);
        }
        $applicationCategory 	= $this->ApplicationCategory->find('list',array('keyField'=>'id','valueField'=>'category_name','conditions'=>array('id IN' => array(2, 3, 4))))->toArray();
        $query_data_count=$this->ApplyOnlines->find('all',array(
            'fields'=>array('ApplyOnlines.id','ApplyOnlines.application_status','ApplyOnlines.application_no','ApplyOnlines.geda_application_no','ApplyOnlines.pv_capacity','ApplyOnlines.rfid_upload','ApplyOnlines.undertaking_upload','installers.installer_name','apply_online_approvals.created'),
            'join'=>array('installers'=>array('table'=>'installers','conditions'=>'ApplyOnlines.installer_id = installers.id','type'=>'inner'),
            	'apply_online_approvals'=>array('table'=>'apply_online_approvals','conditions'=>'ApplyOnlines.id = apply_online_approvals.application_id','type'=>'left'),
            	'projects'=>array('table'=>'projects','conditions'=>'ApplyOnlines.project_id = projects.id','type'=>'left'),
            	'fesibility_report'=>array('table'=>'fesibility_report','conditions'=>'ApplyOnlines.id = fesibility_report.application_id','type'=>'left'),
            	'charging_certificate'=>array('table'=>'charging_certificate','conditions'=>'ApplyOnlines.id = charging_certificate.application_id','type'=>'left'),
            ),
            'conditions' => $arrCondition,
            'order'=>array($this->SortBy=>$this->Direction)));
        if($fields_date != 'apply_online_approvals.created' && !empty($from_date) && !empty($end_date))
        {
			$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
			$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
	    	$query_data_count->where([function ($exp, $q) use ($StartTime, $EndTime,$fields_date) {
				return $exp->between($fields_date, $StartTime, $EndTime);
	   		}]);
        }
		$total_query_records= count($query_data_count->toArray());
       	$start_page=isset($this->request->data['start']) ? $this->request->data['start'] : 1;
       	$this->paginate['limit']= 50;
       	$this->paginate['page'] = ($start_page/$this->paginate['limit'])+1;
       	if(isset($this->request->data['page_no']) && !empty($this->request->data['page_no']))
       	{
       		$posible_page 		= $total_query_records/$this->paginate['limit'];
       		if($posible_page<$this->request->data['page_no'])
       		{
       			$this->paginate['page'] = $posible_page;
       		}
       		else
       		{
       			$this->paginate['page'] = $this->request->data['page_no'];
       		}
       	}
       	else
       	{
       		$this->paginate['page'] = ($start_page/$this->paginate['limit'])+1;
       	}
        $arrAdminuserList				= $this->paginate($query_data);
        $usertypes 						= array();
        $option['dt_selector']			='table-example';
        $option['formId']				='formmain';
        $option['url']					= '';
        $option['recordsperpage']		= '50';
        $option['allsortable']			= '-all';
        $option['total_records_data']	= count($arrAdminuserList->toArray());
        $option['order_by'] 			= "order : [[5,'desc']]";
        $arr_status_dropdown 			= $this->ApplyOnlines->apply_online_dropdown_status;
        unset($arr_status_dropdown['99']);
        $JqdTablescr 			= $this->JqdTable->create($option);
        $installers_list 		= $this->Installers->getInstallerListReport();
        $this->set('arrAdminuserList',$arrAdminuserList->toArray());
        $this->set('JqdTablescr',$JqdTablescr);
        $this->set('period',$this->period);
        $this->set('limit',$this->intLimit);
        $this->set("CurrentPage",$this->CurrentPage);
        $this->set("SortBy",$this->SortBy);
        $this->set("Direction",$this->Direction);
        $this->set("pagetitle",'Project : ');
        $this->set("application_dropdown_status",$arr_status_dropdown);
        $this->set("applicationCategory",$applicationCategory);
        $this->set("Installers",$installers_list);
        $this->set("default_fields",!empty($customer_id) ? array_keys($this->MISReportData->arrReportFieldsIns) : array_keys($this->MISReportData->arrReportFields));
        $this->set("page_count",(isset($this->request->params['paging']['ProjectSurvey']['pageCount'])?$this->request->params['paging']['ProjectSurvey']['pageCount']:0));

        $out 		=array();
        $counter 	= '1';
        $page_mul 	= ($this->CurrentPage-1);
        foreach($arrAdminuserList->toArray() as $key=>$val) {
        	$temparr=array();
            foreach($option['colName'] as $key) {

                if(isset($val[$key])){
                    $temparr[$key]=$val[$key];
                }
                if($key=='id') {
                   $temparr[$key]= $counter+($page_mul*50);
                   $counter++;
                }
                if($key=='pv_capacity') {
                   $temparr[$key]= $val['pv_capacity'];
                }
                if($key=='rfid_upload') {
                  // $temparr[$key]= $val['rfid_upload'];
                	if( !empty($val['rfid_upload'])){
                		$temparr[$key]='<a href="'.URL_HTTP.'app-docs/rfid_upload/'. encode($val['id']).'" target="_blank"><i class="fa fa-download"></i></a>';
                		//$temparr[$key]='<a href="'.URL_HTTP.'app-docs/rfid_upload/'. encode($val['id']).'" target="_blank">'.URL_HTTP.'app-docs/rfid_upload/'. encode($val['id']).'</a>';
                	}else{
                		$temparr[$key]='Kindly upload RFID Documents';
                	}	
                	
                }
                if($key=='undertaking_upload') {
                   //$temparr[$key]= $val['undertaking_upload'];
                	if( !empty($val['undertaking_upload'])){
                		$temparr[$key]='<a href="'.URL_HTTP.'app-docs/undertaking_upload/'. encode($val['id']).'" target="_blank"><i class="fa fa-download"></i></a>';
                   		//$temparr[$key]='<a href="'.URL_HTTP.'app-docs/undertaking_upload/'. encode($val['id']).'" target="_blank">'.URL_HTTP.'app-docs/undertaking_upload/'. encode($val['id']).'</a>';
                   	}else{
                		$temparr[$key]='Kindly upload Undertaking Documents';
                	}
                }
                if($key=='undertaking_upload_sample') {

                   //$temparr[$key]= $val['undertaking_upload'];
                	
                   $temparr[$key]='<a href="/UNDERTAKING_RFID.docx"  style="text-decoration: underline;"><strong>Download Undertaking Letter Format</strong></a>';
                  // print_r($temparr[$key]);die;
                }
                 
                if($key=='application_status') {
                   $temparr[$key]= $this->ApplyOnlineApprovals->application_status[$val->application_status];
                }

   				//             if($key=='geda_application_no') {
				// 	$temparr[$key]= ($val->payment_status != 1) ? '-' : $val->geda_application_no;
				// }
                // if($key=='submitted_on') {
                // 	if(!empty($val['apply_online_approvals']['created']))
                // 	{
                // 		$temparr[$key]= date('m-d-Y H:i a',strtotime($val['apply_online_approvals']['created']));
                // 	}
                // 	else
                // 	{
                // 		$temparr[$key]= '-';
                // 	}

                // }
                //  if($key=='action') {
                //  	$url = URL_HTTP.'ApplyOnlines/rfid_upload_docs/'.encode($val->id);
                //  	$title = 'Upload Docs';
                // 	$geo_id = $val['id'];
                // 	$temparr[$key]	='<button type="button" class="btn btn-sm" style="color:white;background-color: #307FE2;"  onclick="javascript:showModel(\''.$title.'\',\''.$url.'\',\''.$geo_id.'\');" > Action</button>';

                // }
                if($key=='action') {
						
					$temparr[$key]	= '<a href="javascript:;" class="dropdown-item SubmitRequest upload_docs" data-id="'.$val['id'].'"><i class="fa fa-check-square-o" aria-hidden="true"></i> Upload Docs</a>';
							
						
					$temparr['action']	= '	<span class="action-row action-btn">
												<div class="dropdown">
													<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
														Actions <i class="fa fa-chevron-down"></i>
													</button>
													<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">'.$temparr['action'].'</div>
												</div>
											</span>';
				}

            }
            $out[]=$temparr;
        }

        if ($this->request->is('ajax'))
        {
            header('Content-type: application/json');
            echo json_encode(array(	"condi" 			=> $arrCondition,
            						"draw" 				=> intval( $this->request->data['draw']),
					                "recordsTotal"    	=> intval( $this->request->params['paging']['ApplyOnlines']['count']),
					                "recordsFiltered" 	=> intval( $this->request->params['paging']['ApplyOnlines']['count']),
					                "data"            	=> $out));
            die;
        }
    }
    

	public function Add_rfid_docs()
	{	
		//echo'<pre>'; print_r($this->request->data);die;
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['insid'])?$this->request->data['insid']:0);

		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} 
		else{
			// $encode_id 				= $id;
			// $id 					= intval(decode($id));
			$applyOnlinesData 		= $this->ApplyOnlines->viewApplication($id);
			$customer_id 			= $this->Session->read("Customers.id");
			$member_id 				= $this->Session->read("Members.id");


			if (!empty($applyOnlinesData) && (!empty($customer_id) || !empty($member_id))) {
				if ($this->request->is('post')) {
					if( (empty($this->request->data['rfid_upload_file']['name'])) ) {
						$ErrorMessage 	= "Please select Final Principal Document.";
						$success 		= 0;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					} else {
						
						if(!empty($this->request->data['rfid_upload_file']['name']))
						{	
							$image_path = APPLYONLINE_PATH.$id.'/';
							if(!file_exists(APPLYONLINE_PATH.$id)) {
								@mkdir(APPLYONLINE_PATH.$id, 0777);
							}
							//echo'<pre>'; print_r($image_path);die;
							$file_name = $this->file_upload($image_path,$this->request->data['rfid_upload_file'],false,'','','','rfid_upload_file','rfid_upload');
							$this->ApplyOnlines->updateAll(['rfid_upload' => $file_name], ['id' => $id]);
							
						}

						if(!empty($this->request->data['under_upload_file']['name']))
						{	
							$image_path = APPLYONLINE_PATH.$id.'/';
							if(!file_exists(APPLYONLINE_PATH.$id)) {
								@mkdir(APPLYONLINE_PATH.$id, 0777);
							}
							$file_name = $this->file_upload($image_path,$this->request->data['under_upload_file'],false,'','','','under_upload_file','undertaking_upload');
							$this->ApplyOnlines->updateAll(['undertaking_upload' => $file_name], ['id' => $id]);
							
						}
						$ErrorMessage 	= "Upload Document Succesfully.";
						$success 		= 1;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
						
					}
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate form details.";
				$success 		= 0;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	
	public function Add_rfid_data()
	{	
		//echo"<pre>"; print_r($this->request->data); die();
		$this->autoRender 	= false;
		
		$customer_id 			= $this->Session->read("Customers.id");
		$member_id 				= $this->Session->read("Members.id");
		if ( (!empty($customer_id) || !empty($member_id))) {
			
			if((empty($this->request->data['application_no']) || (empty($this->request->data['project_details'])) || 
				(empty($this->request->data['rfid_upload_file']['name'])) || (empty($this->request->data['undertaking_upload_file']['name'])) ))
			{
					$ErrorMessage 	= "Please select all the details as per Required.";
					$success 		= 0;
					$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
					$this->ApiToken->SetAPIResponse('success',$success);
			} else {
				if(!empty($this->request->data['rfid_upload_file']['name'])){
					$arr_modules 					= $this->ApplyOnlinesRfidData->newEntity();
				    $arr_modules['application_no'] 	= $this->request->data['application_no'];
				    $arr_modules['project_details'] = $this->request->data['project_details'];
				    $arr_modules['application_type'] = $this->request->data['application_type'];
				    $arr_modules['created_date'] 	= $this->now();
				    // Save the entity
				    if ($savedEntity = $this->ApplyOnlinesRfidData->save($arr_modules)) {
				        // Get the insert ID
				        $insertId = $savedEntity->id;
				    

				        $image_path = APPLYONLINE_RFID_PATH.$insertId.'/';
							if(!file_exists(APPLYONLINE_RFID_PATH.$insertId)) {
								@mkdir(APPLYONLINE_RFID_PATH.$insertId, 0777);
							}

							//echo'<pre>'; print_r($image_path);die;
							//echo'<pre>'; print_r($image_path);die;
							$file_name = $this->file_upload_rfid($image_path,$this->request->data['rfid_upload_file'],false,'','','','rfid_upload_file','rfid_upload_file');
							$this->ApplyOnlinesRfidData->updateAll(['rfid_upload_file' => $file_name], ['id' => $insertId]);

							$file_name = $this->file_upload_rfid($image_path,$this->request->data['undertaking_upload_file'],false,'','','','undertaking_upload_file','undertaking_upload_file');
							$this->ApplyOnlinesRfidData->updateAll(['undertaking_upload_file' => $file_name], ['id' => $insertId]);
				        // You can now use $insertId for further processing
				    }
					
					$ErrorMessage 	= "Record Added Succesfully";
					$success 		= 1;
					$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
					$this->ApiToken->SetAPIResponse('success',$success);
				}
				
			}
		} else {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		}
		
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	public function rfid_data($id = null)
	{
		$customer_id 		= $this->Session->read("Customers.id");
		$member_id 			= $this->Session->read("Members.id");
		
		$is_member          = false;
		if(!empty($member_id)){
			$is_member      = true;
		}
		//$application_category = $this->ApplicationCategory->find('all',array('conditions'=>array('id IN' => array(2, 3, 4))))->toArray();
		$applicationCategory 	= $this->ApplicationCategory->find('list',array('keyField'=>'id','valueField'=>'category_name','conditions'=>array('id IN' => array(2, 3, 4))))->toArray();
		//echo"<pre>"; print_r($applicationCategory); die();
		$rfid_data	= $this->ApplyOnlinesRfidData->find("all",['conditions'=>array('1'=>'1')])->toArray();
		
		$this->set("rfid_data",$rfid_data);
		$this->set("applicationCategory",$applicationCategory);
		$this->set("pageTitle","Check Rfid Data");
		
	}

}