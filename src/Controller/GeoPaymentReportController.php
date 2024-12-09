<?php
namespace App\Controller;
ini_set('memory_limit', '-1');
ini_set('max_execution_time', '-1');
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
use Dompdf\Dompdf;
use PHPExcel\PHPExcel;

class GeoPaymentReportController extends FrontAppController
{
	public $user_department = array();
	public $arrDefaultAdminUserRights = array();
	public $helpers = array('Time','Html','Form','ExPaginator');
	public $PAGE_NAME = '';
	public $CUSTOMER_STATE_ID = 4;
	public $paginate = [
		'limit' => PAGE_RECORD_LIMIT,
		'order' => [
			'ApplyOnlines.id ' => 'desc'
		]
	];

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
		$this->loadModel('ApplicationDeleteLog');
		$this->loadModel('SpinWebserviceApi');
		$this->loadModel('UpdateDetails');
		$this->loadModel('UpdateDetailsApplicationsLog');
		$this->loadModel('UpdateCapacity');
		$this->loadModel('UpdateCapacityApplicationsLog');
		$this->loadModel('UpdateCapacityProjectsLog');
		$this->loadModel('Subsidy');
		$this->loadModel('ApplyOnlinesOthers');
		$this->loadModel('Inspectionpdf');
		$this->loadModel('PreRegistration');
		$this->loadModel('ApplicationPayment');
		$this->loadModel('DistrictMaster');
		$this->loadModel('EnergyGenerationLog');
		$this->loadModel('MISReportData');
		$this->loadModel('Workorder');
		$this->loadModel('ApplyonlineReport');
		$this->loadModel('InstallerTotalCapacity');
		$this->loadModel('ApplicationPhasechangeLog');
		$this->loadModel('SolarTypeLog');
		$this->loadModel('SendRegistrationFailure');
		$this->loadModel('ApplyonlineUnReadMessage');
		$this->loadModel('MeterRecall');
		$this->loadModel('SubsidyRequest');
		$this->loadModel('SubsidyRequestApplication');
		$this->loadModel('UpdateDiscomDataLog');
		$this->loadModel('SchemeMaster');
		$this->loadModel('ApplyonlinePaymentDocs');
		$this->loadModel('FeesReturn');
		$this->loadModel('Couchdb');
		$this->set('ApplyonlineMessage',$this->ApplyonlineMessage);
		$this->set('InspectionReport',$this->InspectionReport);
		$this->set('Userright',$this->Userright);
	}

	public function listGeoPaymentData() {
		$memberId 		= $this->Session->read("Members.id");
		$member_type 	= $this->Session->read('Members.member_type');
		
		if(empty($memberId) ||  $member_type!=$this->ApplyOnlines->JREDA || !in_array($memberId,ALLOW_ALL_ACCESS))
		{
			return $this->redirect(URL_HTTP);
		}
		$this->set("pagetitle",'Geo Payment Data Report');
	}
	
	
	public function donwloadGeoApplicationPayment($Year='',$Month='') {
		$monthFromYear 		= $Year.'-'.$Month.'-01 00:00:00';
		$endFromYear 		= date("Y-m-d", strtotime($monthFromYear." +1 month"))." 00:00:00";
		//echo"<pre>"; print_r($endFromYear); die();
		
		$sqlApplication 	= "select distinct geo_success_payment.payment_dt,geo_application_payment.receipt_no, 												geo_application_payment.geo_id,geo_application_payment.application_fees as ApplicationFee, 
								geo_application_payment.payment_amount as TotalapplicationFee,geo_application_payment.geo_location_tds as tds_deduction,
								applications.name_of_applicant as Consumer_name,applications.GST,
								(geo_application_payment.gst_fees/2) as GST1,(geo_application_payment.gst_fees/2) as GST2,geo_application_payment.gst_fees as TotalGST,geo_application_payment.transaction_id,applications.address1 as address1,applications.taluka as address2,applications.state as state,applications.pincode as pincode
							from geo_success_payment
							left join geo_application_payment on geo_application_payment.id=geo_success_payment.payment_id
							left join application_geo_location on geo_application_payment.application_id=application_geo_location.application_id
							left join applications on application_geo_location.application_id=applications.id 
							where geo_success_payment.payment_dt between '".$monthFromYear."' and '".$endFromYear."' ";

		$conn 				= ConnectionManager::get('default');
		$appResult 			= $conn->execute($sqlApplication)->fetchAll('assoc');
		
		$PhpExcel 			= $this->PhpExcel;
		$PhpExcel->createExcel();
		$objDrawing 		= new \PHPExcel_Worksheet_MemoryDrawing();
		$objDrawing->setCoordinates('A1');
		$objDrawing->setWorksheet($PhpExcel->getExcelObj()->getActiveSheet());
		$j 					= 1;
		$i 					= 1;
		$arrReportFields 	= array('sr_no'					=> "Sr no",
									'payment_dt' 			=> 'Payment Date',
									'' 						=> 'Payment Time',
									'receipt_no'			=> "Receipt No.",
									'Consumer_name'			=> "Consumer Name",
									'address1'				=> "Address1",
									'address2'				=> "Address2",
									'pincode'				=> "Pincode",
									'state'					=> "State",
									'GST' 					=> "GST No.",
									'geo_id'				=> "Application Geo IDs",
									'ApplicationFee'		=> "Application Fee",
									'GST1'					=> "GST1",
									'GST2'					=> "GST2",
									'TotalGST'				=> "TotalGST",
									'TDS Deduction'			=> "tds_deduction",
									'TotalapplicationFee'	=> "TotalapplicationFee",
									'transaction_id'		=> "Transaction Id",
									'Particulars'			=> "Particulars",
								);
		//$arrSecond 			= array('ticket_closed'=>'Ticket Closed','last_response'=>'Last Response','2nd_last_response'=> '2nd Last Response','3rd_last_response'=> '3rd Last Response','4th_last_response'=> '4th Last Response','5th_last_response'=> '5th Last Response');
	//	echo"<pre>"; print_r($arrReportFields); die();
		foreach ($arrReportFields as $key=>$Field_Name) {
			$RowName 	= $this->GetExcelColumnName($i);

			$ColTitle  	= $Field_Name;
			$PhpExcel->writeCellValue($RowName.$j,$ColTitle);
			$PhpExcel->getExcelObj()->getActiveSheet()->getColumnDimension($RowName)->setAutoSize(true);
			$i++;
		}

		$j++;
		$i = 1;
		
		if(!empty($appResult)){
			foreach($appResult as $key=>$val) {

				$arrPaymentDate 	= explode(" ",$val['payment_dt']);
				$RowName = $this->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$j-1);
				$i++;
				$RowName = $this->GetExcelColumnName($i);
				$strDate 	= !empty($arrPaymentDate[0]) ? date('d-m-Y',strtotime($arrPaymentDate[0])) : '';
				$PhpExcel->writeCellValue($RowName.$j,$strDate);
				$i++;
				$paymentTime 		= '';
				if(isset($arrPaymentDate[1])) {
					$paymentTime 	= $arrPaymentDate[1];
				}
				$RowName = $this->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$paymentTime);
				$i++;
				$RowName = $this->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['receipt_no']);
				$i++;
				$RowName = $this->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['Consumer_name']);
				$i++;
				$RowName = $this->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['address1']);
				$i++;
				$RowName = $this->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['address2']);
				$i++;
				$RowName = $this->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['pincode']);
				$i++;
				$RowName = $this->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['state']);
				$i++;
				$RowName = $this->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['GST']);
				$i++;
				$RowName = $this->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['geo_id']);
				$i++;
				$RowName = $this->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['ApplicationFee']);
				$i++;
				$RowName = $this->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['GST1']);
				$i++;
				$RowName = $this->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['GST2']);
				$i++;

				$RowName = $this->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['TotalGST']);
				$i++;

				$RowName = $this->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,(($val['tds_deduction']>0) ? $val['tds_deduction'] : 0));
				$i++;

				$RowName = $this->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['TotalapplicationFee']);
				$i++;

				$RowName = $this->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['transaction_id']);
				$i++;

				$RowName = $this->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,'Application Coordinates Verification');
				$i++;
	
				// $e_invoice_url 	= !empty($val['e_invoice_url']) ? '=Hyperlink("'.$val['e_invoice_url'].'","'.$val['e_invoice_url'].'")' : '';
				// $RowName = $this->GetExcelColumnName($i);
				// $PhpExcel->writeCellValue($RowName.$j,$e_invoice_url);
				// $i++;

				$i=1;
				$j++;
			}
		}
		$PhpExcel->downloadFile(time());
		exit;
	}

	public function GetExcelColumnName($num)
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
}