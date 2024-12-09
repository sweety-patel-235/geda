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
 * @category  Class File
 * @author    Employee Code : -
 * @version   GEDA 1.0
 * @since     File available since GEDA
 */
class MISReportDataTable extends AppTable
{
	/**
	 *
	 * The status of $name is universe
	 *
	 * Potential value are Class Name
	 *
	 * @var String
	 *
	 */
	var $table = 'mis_report_data';
	public $ExportFields 			= array();
	public $DefaultExportFieldsIns 	= array("sr_no","Scheme","geda_application_no","project_name","project_area","project_areatype","avg_monthly_bill","estimated_kwh_year","pv_capacity",
										"DisCom_Name","consumer_no","consumer_email",
										"consumer_mobile","installer_name","installer_category",
										"installer_email","installer_mobile","latitude","longitude");
	public $DefaultExportFields = array("sr_no","Scheme","geda_application_no","pv_capacity",
										"DisCom_Name","consumer_no","consumer_email",
										"consumer_mobile","installer_name","installer_category",
										"installer_email","installer_mobile");
	var $ExportExtraFields 			= array();
 	public $arrReportFields 	= array("sr_no"=>"Sr No.",
										"submited_date"=>"TimeStamp",
										"Scheme"=>"Scheme",
										"application_no"=>"Application No.",
										"geda_application_no"=>"GEDA Registeration No.",
										"latitude"=>"Lat",
										"longitude"=>"Long",
										"pv_capacity"=>"PV Capacity",
										"existing_capacity"=>"Existing PV Capacity (kW)",
										"installer_name"=>"Installer Name",
										"installer_category"=>"Installer Category",
										"installer_code"=>"Empanelment No.",
										"DisCom_Name"=>"Discom Name",
										"consumer_no"=>"Consumer No.",
										"circle"=>"Circle",
										"division_title"=>"Division/Zone",
										"subdiv_title"=>"Sub-division",
										"sanction_load_contract_demand"=>"Sanctioned / Contract Load",
										"Invertor_Phase"=>"Phase of proposed Solar Inverter",
										"App_Category"=>"Category",
										"Net_Meter_By"=>"Who will provide the Net-Meter?",
										"consumer_email"=>"Consumer Email",
										"consumer_mobile"=>"Consumer Mobile",
										"installer_email"=>"Installer Email",
										"installer_mobile"=>"Installer Mobile",
										"Name_Prefix"=>"Name Prefix",
										"First_Name"=>"First Name",
										"Middle_Name"=>"Middle Name",
										"Last_Name"=>"Last Name",
										"landline_no"=>"Landline No.",
										"Street_House_No"=>"Street/ House No.",
										"Taluka"=>"Taluka",
										"District"=>"District",
										"City_Village"=>"City/Village",
										"State"=>"State",
										"Pin"=>"Pin",
										"comunication_address"=>"Communication Address",
										"Profile_Photo"=>"Passport Size Photo",
										"common_meter"=>"Common Meter",
										"Premises"=>"Whether the Premises is owned or Rented",
										"Electricity_Bill"=>"Electricity Bill",
										"Aadhaar_No_Entered"=>"Aadhaar No. Entered",
										"Self_Owned"=>"Solar PV system Owned by the Consumer",
										"No_Subsidy_Required"=>"Don\"t Want Subsidy",
										"OTP_Verified_On"=>"OTP Verified on",
										"Signed_Uploaded_Date"=>"Signed Document Uploaded Date",
										"First_Comment"=>"First Comment",
										"First_Comment_Date"=>"First Comment Date",
										"Last_Comment"=>"Last Comment",
										"Last_Comment_Date"=>"Last Comment Date",
										"Last_Comment_Replied_Date"=>"Last Comment Replied Date",
										"Document_Verified_Date"=>"Document Verified Date",
										"Fesibility_Report_Date"=>"Field Report Status Received from DisCom on",
										"Quotation_No"=>"Quotation No",
										"Discom_Estimation"=>"DisCom Estimate Amount",
										"Payment_Due_Date"=>"Due Date",
										"Payment_Received"=>"Payment Received",
										"Payment_Date"=>"Payment Made on",
										"Self_Certificate"=>"Self- Certification",
										"drawing_app_no"=>"CEI Drawing Application ID",
										"drawing_approved_date"=>"CEI Drawing Application Approval Date",
										"cei_app_no"=>"CEI Inspection Application ID",
										"cei_approved_date"=>"CEI Inspection Approval Date",
										"workorder_number"=>"Work Order No.",
										"workorder_number_date"=>"Work Order Date",
										"installation_start_date"=>"Work Start Date",
										"installation_end_data"=>"Work End Date",
										"meter_serial_no_make"=>"Bi-directional Meter Make",
										"meter_serial_no"=>"Bi-directional Meter  No.",
										"solar_meter_manufacture"=>"Solar Meter Make",
										"solar_meter_serial_no"=>"Solar Meter  No.",
										"meter_installed_date"=>"Date of Installation of Solar Meter",
										"agreement_date"=>"Agreement Signing Date",
										"application_status"=>"Application Status",
										"approval_id"=>"Sanction ID",
										"pcr_code"=>"PCR Code"
										
										);
   	    public $arrReportFieldsIns 	= array("sr_no"=>"Sr No.",
										"submited_date"=>"TimeStamp",
										"Scheme"=>"Scheme",
										"project_name"=>"Project Name",
										"project_area" => "Project Area",
										"project_areatype" => "Project Area Type",
										"avg_monthly_bill" => "Avg. Monthly Bill",
										"estimated_kwh_year"=> "Energy Consumption",
										"application_no"=>"Application No.",
										"geda_application_no"=>"GEDA Registeration No.",
										"latitude"=>"Lat",
										"longitude"=>"Long",
										"pv_capacity"=>"PV Capacity",
										"existing_capacity"=>"Existing PV Capacity (kW)",
										"installer_name"=>"Installer Name",
										"installer_category"=>"Installer Category",
										"installer_code"=>"Empanelment No.",
										"DisCom_Name"=>"Discom Name",
										"consumer_no"=>"Consumer No.",
										"Electricity_Bill"=>"Electricity Bill",
										"circle"=>"Circle",
										"division_title"=>"Division/Zone",
										"subdiv_title"=>"Sub-division",
										"sanction_load_contract_demand"=>"Sanctioned / Contract Load",
										"Invertor_Phase"=>"Phase of proposed Solar Inverter",
										"App_Category"=>"Category",
										"Net_Meter_By"=>"Who will provide the Net-Meter?",
										"consumer_email"=>"Consumer Email",
										"consumer_mobile"=>"Consumer Mobile",
										"installer_email"=>"Installer Email",
										"installer_mobile"=>"Installer Mobile",
										"Name_Prefix"=>"Name Prefix",
										"First_Name"=>"First Name",
										"Middle_Name"=>"Middle Name",
										"Last_Name"=>"Last Name",
										"landline_no"=>"Landline No.",
										"Street_House_No"=>"Street/ House No.",
										"Taluka"=>"Taluka",
										"District"=>"District",
										"City_Village"=>"City/Village",
										"State"=>"State",
										"Pin"=>"Pin",
										"comunication_address"=>"Communication Address",
										"Profile_Photo"=>"Passport Size Photo",
										"common_meter"=>"Common Meter",
										"Premises"=>"Whether the Premises is owned or Rented",
										"Aadhaar_No_Entered"=>"Aadhaar No. Entered",
										"Self_Owned"=>"Solar PV system Owned by the Consumer",
										"No_Subsidy_Required"=>"Don\"t Want Subsidy",
										"OTP_Verified_On"=>"OTP Verified on",
										"Signed_Uploaded_Date"=>"Signed Document Uploaded Date",
										"Last_Comment"=>"Last Comment",
										"Last_Comment_Date"=>"Last Comment Date",
										"Last_Comment_Replied_Date"=>"Last Comment Replied Date",
										"Document_Verified_Date"=>"Document Verified Date",
										"Fesibility_Report_Date"=>"Field Report Status Received from DisCom on",
										"Quotation_No"=>"Quotation No",
										"Discom_Estimation"=>"DisCom Estimate Amount",
										"Payment_Due_Date"=>"Due Date",
										"Payment_Received"=>"Payment Received",
										"Payment_Date"=>"Payment Made on",
										"Self_Certificate"=>"Self- Certification",
										"drawing_app_no"=>"CEI Drawing Application ID",
										"drawing_approved_date"=>"CEI Drawing Application Approval Date",
										"cei_app_no"=>"CEI Inspection Application ID",
										"cei_approved_date"=>"CEI Inspection Approval Date",
										"workorder_number"=>"Work Order No.",
										"workorder_number_date"=>"Work Order Date",
										"installation_start_date"=>"Work Start Date",
										"installation_end_data"=>"Work End Date",
										"meter_serial_no_make"=>"Bi-directional Meter Make",
										"meter_serial_no"=>"Bi-directional Meter  No.",
										"solar_meter_manufacture"=>"Solar Meter Make",
										"solar_meter_serial_no"=>"Solar Meter  No.",
										"meter_installed_date"=>"Date of Installation of Solar Meter",
										"agreement_date"=>"Agreement Signing Date",
										"application_status"=>"Application Status",
										"approval_id"=>"Sanction ID",
										"pcr_code"=>"PCR Code"
										);
	public $arrReportExtraFields 		= array("module_capacity"=>"Module Capacity",
										"module_make"=>"Module Make",
										"manufacturer"=>"Manufacturer",
										"no_of_module"=>"No. of Module",
										"inverter_capacity"=>"Inverter Capacity",
										"inverter_make"=>"Inverter Make",
										"no_of_inverter"=>"No of Inverter");
	public function initialize(array $config)
    {
		$this->table($this->table);
    }
    /**
	 *
	 * GetReportFields
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to get all fields exported in to excel.
	 *
	 */
	public function  GetReportFields($arrExportFields)
    {
    	$ApplyOnlineApprovals  	= TableRegistry::get("ApplyOnlineApprovals");
    	$this->ExportFields 	= $arrExportFields;
    	//$this->ExportFields = isset($this->request->data['mis_export_fields']) && !empty($this->request->data['mis_export_fields'])?$this->request->data['mis_export_fields']:$this->DefaultExportFields;
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
			$ReportFields .= ",IF (AO.pv_capacity <=10, ((CASE WHEN 1 = 1 THEN (select created FROM apply_online_approvals WHERE application_id = AO.id and apply_online_approvals.stage = ".$ApplyOnlineApprovals->APPROVED_FROM_CEI." ORDER BY apply_online_approvals.id DESC LIMIT 1)END)),'')  AS Self_Certificate";
		}
		if (in_array("drawing_app_no",$this->ExportFields)) {
			$ReportFields .= ",IF (AO.pv_capacity >10, MIS.drawing_app_no,'')  AS drawing_app_no";
		}
		if (in_array("drawing_approved_date",$this->ExportFields)) {
			//$ReportFields .= ",MIS.drawing_approved_date AS drawing_approved_date";
			$ReportFields .= ",IF (AO.pv_capacity >10, ((CASE WHEN 1 = 1 THEN (select created FROM apply_online_approvals WHERE application_id = AO.id and apply_online_approvals.stage = ".$ApplyOnlineApprovals->APPROVED_FROM_CEI." ORDER BY apply_online_approvals.id DESC LIMIT 1)END)),'')  AS drawing_approved_date";
		}
		if (in_array("cei_app_no",$this->ExportFields)) {
			$ReportFields .= ",IF (AO.pv_capacity >10, ((CASE WHEN 1 = 1 THEN (select cei_app_no FROM cei_application_details WHERE application_id = AO.id  ORDER BY cei_application_details.id DESC LIMIT 1)END)),'') AS cei_app_no";
		}
		if (in_array("cei_approved_date",$this->ExportFields)) {
			//$ReportFields .= ",MIS.drawing_approved_date AS drawing_approved_date";
			$ReportFields .= ",IF (AO.pv_capacity >10, ((CASE WHEN 1 = 1 THEN (select created FROM apply_online_approvals WHERE application_id = AO.id and apply_online_approvals.stage = ".$ApplyOnlineApprovals->CEI_INSPECTION_APPROVED." ORDER BY apply_online_approvals.id DESC LIMIT 1)END)),'')  AS cei_approved_date";
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
		if (in_array("circle",$this->ExportFields)) {
			$ReportFields .= ",C.title AS circle";
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
		if (in_array("existing_capacity",$this->ExportFields)) {
			$ReportFields .= ",A.existing_capacity AS existing_capacity";
		}
		if (in_array("common_meter",$this->ExportFields)) {
			$ReportFields .= ",IF(AO.common_meter = 1,'YES','NO') AS common_meter";
		}
		if (in_array("msme",$this->ExportFields)) {
			$ReportFields .= ",A.msme";
		}
		if (in_array("govt_agency",$this->ExportFields)) {
			$ReportFields .= ",AO.govt_agency";
		}
		if (in_array("pv_dc_capacity",$this->ExportFields)) {
			$ReportFields .= ",A.pv_dc_capacity";
		}
		if (in_array("First_Comment",$this->ExportFields)) {
			$ReportFields .= ", CASE WHEN 1=1 THEN
									(
										SELECT applyonline_messages.message
										FROM applyonline_messages
										WHERE applyonline_messages.application_id=AO.id order by id asc limit 1
									) END AS First_Comment";
		}
		if (in_array("First_Comment_Date",$this->ExportFields)) {
			$ReportFields .= ", CASE WHEN 1=1 THEN
									(
										SELECT applyonline_messages.created
										FROM applyonline_messages
										WHERE applyonline_messages.application_id=AO.id order by id asc limit 1
									) END AS First_Comment_Date";
		}
		return $ReportFields;
    }
	/**
	 *
	 * MISQueryStr
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use return query string for MIS report.
	 *
	 */
	public function MISQueryStr($array_request=array())
	{
		$ApplyOnlines 			= TableRegistry::get('ApplyOnlines');
		$ApplyOnlineApprovals 	= TableRegistry::get('ApplyOnlineApprovals');
		$sql_main 				= "	FROM apply_onlines AO
									INNER JOIN projects as P ON P.id = AO.project_id
									INNER JOIN installers as INS ON INS.id = AO.installer_id
									INNER JOIN parameters as PM ON AO.category = PM.para_id
									INNER JOIN branch_masters as DM ON AO.discom = DM.id
									LEFT JOIN discom_master as D ON AO.division = D.id
									LEFT JOIN discom_master as SD ON AO.subdivision = SD.id
									LEFT JOIN discom_master as C ON AO.circle = C.id
									LEFT JOIN apply_onlines_others as A ON AO.id = A.application_id
									INNER JOIN installer_category_mapping as ICM ON ICM.installer_id = INS.id
									LEFT JOIN fesibility_report as fea ON AO.id = fea.application_id
									LEFT JOIN mis_report_data as MIS ON AO.id = MIS.application_id
									LEFT JOIN project_installation as PI ON AO.project_id = PI.project_id
									";
		$sql 				= " WHERE AO.application_status not in ('99','29','30','22','0') and AO.application_status is not NULL ";
		$application_status = isset($array_request['status'])?$array_request['status']:'';
		$installer_name 	= isset($array_request['installer_name_multi'])?$array_request['installer_name_multi']:'';
		$application_no 	= isset($array_request['application_no'])?$array_request['application_no']:'';
		$geda_application_no= isset($array_request['geda_application_no'])?$array_request['geda_application_no']:'';
		$payment_status 	= isset($array_request['payment_status'])?$array_request['payment_status']:'';
		$DateField 			= isset($array_request['DateField'])?$array_request['DateField']:'';
		$from_date 			= isset($array_request['DateFrom'])?$array_request['DateFrom']:'';
		$end_date 			= isset($array_request['DateTo'])?$array_request['DateTo']:'';
		$scheme_id			= isset($array_request['scheme_id'])?$array_request['scheme_id']:'';

		$fields_date  		= "apply_online_approvals.created";
		if (!empty($DateField) && in_array($DateField,array("apply_online_approvals.created","charging_certificate.meter_installed_date"))) {
			$fields_date 	= $DateField;
		}
		$whereCharging 		= '';
		if($fields_date != 'apply_online_approvals.created' && !empty($from_date) && !empty($end_date))
		{
			$whereCharging 	= ' and '.$fields_date.' between '.$from_date.' and '.$end_date;
		}
		if(!empty($application_status))
		{
			$passStatus = $ApplyOnlines->apply_online_status_key[$application_status];
			if($passStatus == '9999')
			{
				$sql .= " and AO.application_status = '".$ApplyOnlineApprovals->APPLICATION_SUBMITTED."'";
			}
			else
			{
				if($fields_date == 'apply_online_approvals.created' && !empty($from_date) && !empty($end_date)) {
					$sql_main 	.=" left join apply_online_approvals on AO.id = apply_online_approvals.application_id";
					$sql .= " and apply_online_approvals.stage = '".$ApplyOnlines->apply_online_status_key[$application_status]."'";
					//$FindApplicationIDs     = $ApplyOnlineApprovals->find('list',['keyField'=>'id','valueField'=>'application_id','conditions'=>[]]);
					$StartTime    		= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
					$EndTime    		= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
					$sql .= " and apply_online_approvals.created between '".$StartTime."' and '".$EndTime."'";
					/*$FindApplicationIDs->where([function ($exp, $q) use ($StartTime, $EndTime,$fields_date) {
						return $exp->between('ApplyOnlineApprovals.created', $StartTime, $EndTime);
					}]);
					$FindApplicationIDs = $FindApplicationIDs->toArray();
					if (!empty($FindApplicationIDs)) {
						$sql .= " and AO.id IN (".implode(",",array_unique($FindApplicationIDs)).")";
						if($passStatus != $ApplyOnlineApprovals->APPLICATION_CANCELLED)
						{
							$sql .= " and AO.application_status != '".$ApplyOnlineApprovals->APPLICATION_CANCELLED."'";
						}
					} else {
						$sql .= " and AO.id = '0'";
					}*/
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
			if($payment_status==0 || $payment_status==1)
			{
				$sql .= " and fea.payment_approve = '".$payment_status."'";
			}
		}
	   return $sql_main.$sql;
	}
	/**
	 *
	 * GetExcelColumnName
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to Get charector for excel
	 *
	 */
	public function GetExcelColumnName($num)
	{
		$str 			= '';
		$DEFAULT_NUMBER = 64;
		while ($num > 0) {
			$Module = ($num % 26);
			$Module = ($Module > 0?$Module:26);
			$str 	= chr( $Module + $DEFAULT_NUMBER) . $str;

			if($num == 53)
			{
				$num 	= (int) ($num / 26);
			}
			else
			{
				$num 	= (int) ($num / 27);
			}
		}
		return trim($str);
	}
	/**
	 *
	 * getAllDiscomsSearch
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to Get discom list for search in report
	 *
	 */
	public function getAllDiscomsSearch($areaPass=0)
	{
		$discom_arr 	= array();
		$BranchMasters 	= TableRegistry::get('BranchMasters');
		$arrConditionS 	= ['BranchMasters.status'=>'1','BranchMasters.parent_id'=>'0','BranchMasters.state'=>'4'];
		if(!empty($areaPass))
		{
			$arrConditionS['discom_id'] = $areaPass;
		}
		$discoms 		= $BranchMasters->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>$arrConditionS])->toArray();
		if(!empty($discoms)) {
			foreach($discoms as $id=>$title) {
				$discom_arr[$id] = $title;
			}
		}
		//$discom_arr[1]="Total of GUVNL's Subsidiary";
		//$discom_arr[2]="Total of Torrent";
		return $discom_arr;
	}
}