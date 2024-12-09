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

class PaymentReportController extends FrontAppController
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
	/**
	 *
	 * donwloadApplicationPayment
	 * Behaviour : Public
	 * @defination : Method is use to download application Payment Data
	 *
	 */
	public function donwloadApplicationPayment($Year='',$Month='') {
		$monthFromYear 		= $Year.'-'.$Month.'-01 00:00:00';
		$endFromYear 		= date("Y-m-d", strtotime($monthFromYear." +1 month"))." 00:00:00";
		
		$sqlApplication 	= "select distinct applyonline_payment.payment_dt,payumoney.receipt_no,CONCAT(name_of_consumer_applicant, ' ',last_name, ' ',third_name) as Consumer_name,gstno ,disCom_application_fee as ApplicationFee,(jreda_processing_fee/2) as GST1,(jreda_processing_fee/2) as GST2,jreda_processing_fee as TotalGST,disCom_application_fee+jreda_processing_fee as TotalapplicationFee,transaction_id,
			apply_onlines.address1 as address1,apply_onlines.address2 as address2,apply_onlines.state as state,apply_onlines.pincode as pincode,apply_onlines.pv_capacity as pv_capacity,apply_onlines_others.e_invoice_url as e_invoice_url
			from applyonline_payment
			left join payumoney on applyonline_payment.payment_id=payumoney.id
			left join apply_onlines on applyonline_payment.application_id=apply_onlines.id 
			left join apply_onlines_others on apply_onlines.id=apply_onlines_others.application_id 
			where applyonline_payment.payment_dt between '".$monthFromYear."' and '".$endFromYear."' group by apply_onlines.id";

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
									'kw' 						=> 'kW',
									'receipt_no'			=> "Receipt No.",
									'Consumer_name'			=> "Consumer Name",
									'address1'				=> "Address1",
									'address2'				=> "Address2",
									'address3'				=> "Address3",
									'address4'				=> "Address4",
									'pincode'				=> "Pincode",
									'state'					=> "State",
									'gstno' 				=> "GST No.",
									'ApplicationFee'		=> "Application Fee",
									'GST1'					=> "GST1",
									'GST2'					=> "GST2",
									'TotalGST'				=> "TotalGST",
									'TotalapplicationFee'	=> "TotalapplicationFee",
									'transaction_id'		=> "Transaction Id",
									'e_invoice_url' 		=> "E-Invoice URL",
									'Particulars' 			=> "Particulars",
									);
		//$arrSecond 			= array('ticket_closed'=>'Ticket Closed','last_response'=>'Last Response','2nd_last_response'=> '2nd Last Response','3rd_last_response'=> '3rd Last Response','4th_last_response'=> '4th Last Response','5th_last_response'=> '5th Last Response');
		foreach ($arrReportFields as $key=>$Field_Name) {
			$RowName 	= $this->FeesReturn->GetExcelColumnName($i);

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
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$j-1);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$strDate 	= !empty($arrPaymentDate[0]) ? date('d-m-Y',strtotime($arrPaymentDate[0])) : '';
				$PhpExcel->writeCellValue($RowName.$j,$strDate);
				$i++;
				$paymentTime 		= '';
				if(isset($arrPaymentDate[1])) {
					$paymentTime 	= $arrPaymentDate[1];
				}
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$paymentTime);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['pv_capacity']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['receipt_no']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['Consumer_name']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['address1']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['address2']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,'');
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,'');
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['pincode']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['state']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['gstno']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['ApplicationFee']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['GST1']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['GST2']);
				$i++;

				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['TotalGST']);
				$i++;

				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['TotalapplicationFee']);
				$i++;

				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['transaction_id']);
				$i++;

				$e_invoice_url 	= !empty($val['e_invoice_url']) ? '=Hyperlink("'.$val['e_invoice_url'].'","'.$val['e_invoice_url'].'")' : '';
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$e_invoice_url);
				$i++;

				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,'Registration Fee- Solar Project');
				$i++;
				
	
				$i=1;
				$j++;
			}
		}
		$PhpExcel->downloadFile(time());
		exit;
	}
	/**
	 *
	 * donwloadInstallerPayment
	 * Behaviour : Public
	 * @defination : Method is use to download application Payment Data
	 *
	 */
	public function donwloadInstallerPayment($Year='',$Month='') {
		$monthFromYear 		= $Year.'-'.$Month.'-01 00:00:00';
		$endFromYear 		= date("Y-m-d", strtotime($monthFromYear." +1 month"))." 00:00:00";
		
		$sqlApplication 	= "select distinct installer_success_payment.payment_dt,installer_payment.receipt_no,installer_name,gst ,10000,900,900,1800,11800,transaction_id,e_invoice_url
								from installer_success_payment
								left join installer_payment on installer_success_payment.payment_id=installer_payment.id
								left join installers on installer_success_payment.installer_id=installers.id 
								where installer_success_payment.payment_dt between '".$monthFromYear."' and '".$endFromYear."' group by installers.id";

		$conn 				= ConnectionManager::get('default');
		$appResult 			= $conn->execute($sqlApplication)->fetchAll('assoc');
		
		$PhpExcel 			= $this->PhpExcel;
		$PhpExcel->createExcel();
		$objDrawing 		= new \PHPExcel_Worksheet_MemoryDrawing();
		$objDrawing->setCoordinates('A1');
		$objDrawing->setWorksheet($PhpExcel->getExcelObj()->getActiveSheet());
		$j 					= 1;
		$i 					= 1;
		$arrReportFields 	= array('sr_no'			=> "Sr no",
									'payment_dt' 	=> 'Payment Date',
									'receipt_no'	=> "Receipt No.",
									'installer_name'=> "Installer Name",
									'gst' 			=> "GST No.",
									'10000'			=> "10000",
									'900'			=> "900",
									'900 '			=> "900",
									'1800'			=> "1800",
									'11800'			=> "11800",
									'transaction_id'=> "Transaction Id",
									'e_invoice_url'	=> "E-Invoice URL",
									'Particulars'	=> "Particulars",
									);
		//$arrSecond 			= array('ticket_closed'=>'Ticket Closed','last_response'=>'Last Response','2nd_last_response'=> '2nd Last Response','3rd_last_response'=> '3rd Last Response','4th_last_response'=> '4th Last Response','5th_last_response'=> '5th Last Response');
		foreach ($arrReportFields as $key=>$Field_Name) {
			$RowName 	= $this->FeesReturn->GetExcelColumnName($i);

			$ColTitle  	= $Field_Name;
			$PhpExcel->writeCellValue($RowName.$j,$ColTitle);
			$PhpExcel->getExcelObj()->getActiveSheet()->getColumnDimension($RowName)->setAutoSize(true);
			$i++;
		}

		$j++;
		$i = 1;
		
		if(!empty($appResult)){
			foreach($appResult as $key=>$val) {

				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$j-1);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['payment_dt']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['receipt_no']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['installer_name']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['gst']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['10000']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['900']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['900']);
				$i++;

				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['1800']);
				$i++;

				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['11800']);
				$i++;

				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['transaction_id']);
				$i++;

				$e_invoice_url 	= !empty($val['e_invoice_url']) ? '=Hyperlink("'.$val['e_invoice_url'].'","'.$val['e_invoice_url'].'")' : '';
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$e_invoice_url);
				$i++;

				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,'Processing Fees');
				$i++;
	
				$i=1;
				$j++;
			}
		}
		$PhpExcel->downloadFile(time());
		exit;
	}
	/**
	 *
	 * listPaymentData
	 * Behaviour : Public
	 * @defination : Method is use to download application Payment Data
	 *
	 */
	public function listPaymentData() {
		$memberId 		= $this->Session->read("Members.id");
		$member_type 	= $this->Session->read('Members.member_type');
		
		if(empty($memberId) ||  $member_type!=$this->ApplyOnlines->JREDA || !in_array($memberId,ALLOW_ALL_ACCESS))
		{
			return $this->redirect(URL_HTTP);
		}
		$this->set("pagetitle",'Payment Data Report');
	}
	/**
	 *
	 * donwloadDeveloperPayment
	 * Behaviour : Public
	 * @defination : Method is use to download application Payment Data
	 *
	 */
	public function donwloadDeveloperPayment($Year='',$Month='') {
		$monthFromYear 		= $Year.'-'.$Month.'-01 00:00:00';
		$endFromYear 		= date("Y-m-d", strtotime($monthFromYear." +1 month"))." 00:00:00";
		
		$sqlApplication 	= "select distinct developer_success_payment.payment_dt,developer_payment.receipt_no,installer_name,developers.gst ,developers.developer_fee,(developer_application_category_mapping.gst_fees/2) as cgst_fees,(developer_application_category_mapping.gst_fees/2) as sgst_fees,developer_application_category_mapping.gst_fees as Total_gst,developer_application_category_mapping.developer_total_fee,transaction_id, developers.e_invoice_url,application_category.category_name as category_name,application_category.id as category_id
								from developer_success_payment
								left join developer_payment on developer_success_payment.payment_id=developer_payment.id
								left join developers on developer_success_payment.installer_id=developers.id 
								left join developer_application_category_mapping on developer_application_category_mapping.installer_id=developers.id 
								left join application_category on application_category.id=developer_application_category_mapping.application_category_id 
								where developer_success_payment.payment_dt between '".$monthFromYear."' and '".$endFromYear."' and developer_payment.id  IS NOT NULL group by developers.id order by receipt_no asc";
		
		// $sqlApplication 	= "select distinct developer_success_payment.payment_dt,developer_payment.receipt_no,installer_name,gst ,developers.developer_fee,(gst_fees/2) as cgst_fees,(gst_fees/2) as sgst_fees,gst_fees as Total_gst,developer_total_fee,transaction_id,e_invoice_url,developers.id as developer_id
		// 						from developer_success_payment
		// 						left join developer_payment on developer_success_payment.payment_id=developer_payment.id
		// 						left join developers on developer_success_payment.installer_id=developers.id 
		// 						where developer_success_payment.payment_dt between '".$monthFromYear."' and '".$endFromYear."' and developer_payment.id  IS NOT NULL group by developers.id order by receipt_no asc";

		$conn 				= ConnectionManager::get('default');
		$appResult 			= $conn->execute($sqlApplication)->fetchAll('assoc');
		
		$PhpExcel 			= $this->PhpExcel;
		$PhpExcel->createExcel();
		$objDrawing 		= new \PHPExcel_Worksheet_MemoryDrawing();
		$objDrawing->setCoordinates('A1');
		$objDrawing->setWorksheet($PhpExcel->getExcelObj()->getActiveSheet());
		$j 					= 1;
		$i 					= 1;
		$arrReportFields 	= array('sr_no'				=> "Sr no",
									'payment_dt' 		=> 'Payment Date',
									'receipt_no'		=> "Receipt No.",
									'installer_name'	=> "Installer Name",
									'gst' 				=> "GST No.",
									'developer_fee'		=> "Developer Fee",
									'cgst_fees'			=> "CGST",
									'sgst_fees'			=> "SGST",
									'Total_gst'			=> "Total GST",
									'developer_total_fee'=> "Total Fees",
									'transaction_id'	=> "Transaction Id",
									'e_invoice_url' 	=> "E-Invoice URL",
									'Particulars'		=> "Particulars",
									);
		//$arrSecond 			= array('ticket_closed'=>'Ticket Closed','last_response'=>'Last Response','2nd_last_response'=> '2nd Last Response','3rd_last_response'=> '3rd Last Response','4th_last_response'=> '4th Last Response','5th_last_response'=> '5th Last Response');
		foreach ($arrReportFields as $key=>$Field_Name) {
			$RowName 	= $this->FeesReturn->GetExcelColumnName($i);

			$ColTitle  	= $Field_Name;
			$PhpExcel->writeCellValue($RowName.$j,$ColTitle);
			$PhpExcel->getExcelObj()->getActiveSheet()->getColumnDimension($RowName)->setAutoSize(true);
			$i++;
		}

		$j++;
		$i = 1;
		
		if(!empty($appResult)){
			foreach($appResult as $key=>$val) {
				
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$j-1);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['payment_dt']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['receipt_no']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['installer_name']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['gst']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['developer_fee']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['cgst_fees']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['sgst_fees']);
				$i++;

				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['Total_gst']);
				$i++;

				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['developer_total_fee']);
				$i++;

				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['transaction_id']);
				$i++;

				$e_invoice_url 	= !empty($val['e_invoice_url']) ? '=Hyperlink("'.$val['e_invoice_url'].'","'.$val['e_invoice_url'].'")' : '';
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$e_invoice_url);
				$i++;
				
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['category_id'] == '1' ? 'Processing Fees - Resco' : 'Processing Fees - ' .$val['category_name']);
				$i++;
				

				$i=1;
				$j++;
			}
		}
		$PhpExcel->downloadFile(time());
		exit;
	}
	/**
	 *
	 * donwloadReApplicationPayment
	 * Behaviour : Public
	 * @defination : Method is use to download application Payment Data
	 *
	 */
	public function donwloadReApplicationPayment($Year='',$Month='') {
		$monthFromYear 		= $Year.'-'.$Month.'-01 00:00:00';
		$endFromYear 		= date("Y-m-d", strtotime($monthFromYear." +1 month"))." 00:00:00";
		
		
		$sqlApplication 	= "select distinct re_success_payment.payment_dt,re_application_payment.receipt_no,name_of_applicant as Consumer_name,GST ,application_fee as ApplicationFee,(gst_fees/2) as GST1,(gst_fees/2) as GST2,gst_fees as TotalGST,tds_deduction,application_total_fee as TotalapplicationFee,transaction_id,application_category.category_name as category_name,applications.address1 as address1,applications.taluka as address2,applications.state as state,applications.pincode as pincode,e_invoice_url
		from re_success_payment
		inner join re_application_payment on re_application_payment.id=re_success_payment.payment_id
		left join applications on re_application_payment.application_id=applications.id 
		left join application_category on application_category.id=applications.application_type 
		where re_success_payment.payment_dt between '".$monthFromYear."' and '".$endFromYear."' group by applications.id order by receipt_no asc";

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
									'ApplicationFee'		=> "Application Fee",
									'GST1'					=> "GST1",
									'GST2'					=> "GST2",
									'TotalGST'				=> "TotalGST",
									'TDS Deduction'			=> "tds_deduction",
									'TotalapplicationFee'	=> "TotalapplicationFee",
									'transaction_id'		=> "Transaction Id",
									'e_invoice_url' 		=> "E-Invoice URL",
									'Particulars'			=> "Particulars",
									);
		//$arrSecond 			= array('ticket_closed'=>'Ticket Closed','last_response'=>'Last Response','2nd_last_response'=> '2nd Last Response','3rd_last_response'=> '3rd Last Response','4th_last_response'=> '4th Last Response','5th_last_response'=> '5th Last Response');
		foreach ($arrReportFields as $key=>$Field_Name) {
			$RowName 	= $this->FeesReturn->GetExcelColumnName($i);

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
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$j-1);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$strDate 	= !empty($arrPaymentDate[0]) ? date('d-m-Y',strtotime($arrPaymentDate[0])) : '';
				$PhpExcel->writeCellValue($RowName.$j,$strDate);
				$i++;
				$paymentTime 		= '';
				if(isset($arrPaymentDate[1])) {
					$paymentTime 	= $arrPaymentDate[1];
				}
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$paymentTime);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['receipt_no']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['Consumer_name']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['address1']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['address2']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['pincode']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['state']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['GST']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['ApplicationFee']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['GST1']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['GST2']);
				$i++;

				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['TotalGST']);
				$i++;

				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,(($val['tds_deduction']>0) ? $val['tds_deduction'] : 0));
				$i++;

				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['TotalapplicationFee']);
				$i++;

				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['transaction_id']);
				$i++;
	
				$e_invoice_url 	= !empty($val['e_invoice_url']) ? '=Hyperlink("'.$val['e_invoice_url'].'","'.$val['e_invoice_url'].'")' : '';
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$e_invoice_url);
				$i++;
				
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,'Application Processing Fees -'. $val['category_name'] . '(Provisional)');
				$i++;

				$i=1;
				$j++;
			}
		}
		$PhpExcel->downloadFile(time());
		exit;
	}
	/**
	 *
	 * donwloadGeoApplicationPayment
	 * Behaviour : Public
	 * @defination : Method is use to download Geo application Payment Data
	 *
	 */

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
		
		foreach ($arrReportFields as $key=>$Field_Name) {
			$RowName 	= $this->FeesReturn->GetExcelColumnName($i);

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
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$j-1);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$strDate 	= !empty($arrPaymentDate[0]) ? date('d-m-Y',strtotime($arrPaymentDate[0])) : '';
				$PhpExcel->writeCellValue($RowName.$j,$strDate);
				$i++;
				$paymentTime 		= '';
				if(isset($arrPaymentDate[1])) {
					$paymentTime 	= $arrPaymentDate[1];
				}
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$paymentTime);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['receipt_no']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['Consumer_name']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['address1']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['address2']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['pincode']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['state']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['GST']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['geo_id']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['ApplicationFee']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['GST1']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['GST2']);
				$i++;

				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['TotalGST']);
				$i++;

				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,(($val['tds_deduction']>0) ? $val['tds_deduction'] : 0));
				$i++;

				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['TotalapplicationFee']);
				$i++;

				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['transaction_id']);
				$i++;

				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,'Application Coordinates Verification');
				$i++;
	
				// $e_invoice_url 	= !empty($val['e_invoice_url']) ? '=Hyperlink("'.$val['e_invoice_url'].'","'.$val['e_invoice_url'].'")' : '';
				// $RowName = $this->FeesReturn->GetExcelColumnName($i);
				// $PhpExcel->writeCellValue($RowName.$j,$e_invoice_url);
				// $i++;

				$i=1;
				$j++;
			}
		}
		$PhpExcel->downloadFile(time());
		exit;
	}
	/**
	 *
	 * donwloadAllApplicationPayment
	 * Behaviour : Public
	 * @defination : Method is use to download application Payment Data
	 *
	 */
	public function donwloadAllApplicationPayment_old($Year='',$Month='') {
		$monthFromYear 		= $Year.'-'.$Month.'-01 00:00:00';
		$endFromYear 		= date("Y-m-d", strtotime($monthFromYear." +1 month"))." 00:00:00";
		

		$sqlApplication1 = "SELECT DISTINCT 
	    applyonline_payment.payment_dt,
	    payumoney.receipt_no,
	    CONCAT(name_of_consumer_applicant, ' ', last_name, ' ', third_name) AS Consumer_name,
	    gstno AS GST,
	    disCom_application_fee AS ApplicationFee,
	    (jreda_processing_fee / 2) AS GST1,
	    (jreda_processing_fee / 2) AS GST2,
	    jreda_processing_fee AS TotalGST,
	    disCom_application_fee + jreda_processing_fee AS TotalapplicationFee,
	    payumoney.transaction_id,
	    apply_onlines.address1 AS address1,
	    apply_onlines.address2 AS address2,
	    apply_onlines.state AS state,
	    apply_onlines.pincode AS pincode,
	    apply_onlines.pv_capacity AS pv_capacity,
	    apply_onlines_others.e_invoice_url AS e_invoice_url,
	    NULL AS geo_id,
	    NULL AS tds_deduction,
	    NULL AS developer_fee,
	    NULL AS category_name,
	    NULL AS category_id
		FROM applyonline_payment
		LEFT JOIN payumoney ON applyonline_payment.payment_id = payumoney.id
		LEFT JOIN apply_onlines ON applyonline_payment.application_id = apply_onlines.id
		LEFT JOIN apply_onlines_others ON apply_onlines.id = apply_onlines_others.application_id
		WHERE applyonline_payment.payment_dt BETWEEN '".$monthFromYear."' AND '".$endFromYear."'
		GROUP BY apply_onlines.id";

		$sqlApplication2 = "SELECT DISTINCT 
		    installer_success_payment.payment_dt,
		    installer_payment.receipt_no,
		    installers.installer_name AS Consumer_name,
		    installers.gst AS GST,
		    10000 AS ApplicationFee,
		    900 AS GST1,
		    900 AS GST2,
		    1800 AS TotalGST,
		    11800 AS TotalapplicationFee,
		    installer_payment.transaction_id,
		    NULL AS address1,
		    NULL AS address2,
		    NULL AS state,
		    NULL AS pincode,
		    NULL AS pv_capacity,
		    installers.e_invoice_url AS e_invoice_url,
		    NULL AS geo_id,
		    NULL AS tds_deduction,
		    NULL AS developer_fee,
		    NULL AS category_name,
		    NULL AS category_id
		FROM installer_success_payment
		LEFT JOIN installer_payment ON installer_success_payment.payment_id = installer_payment.id
		LEFT JOIN installers ON installer_success_payment.installer_id = installers.id
		WHERE installer_success_payment.payment_dt BETWEEN '".$monthFromYear."' AND '".$endFromYear."'
		GROUP BY installers.id";

		$sqlApplication3 = "SELECT DISTINCT 
		    developer_success_payment.payment_dt,
		    developer_payment.receipt_no,
		    developers.installer_name AS Consumer_name,
		    developers.gst AS GST,
		    developers.developer_fee AS ApplicationFee,
		    (developer_application_category_mapping.gst_fees / 2) AS GST1,
		    (developer_application_category_mapping.gst_fees / 2) AS GST2,
		    developer_application_category_mapping.gst_fees AS TotalGST,
		    developer_application_category_mapping.developer_total_fee AS TotalapplicationFee,
		    developer_payment.transaction_id,
		    NULL AS address1,
		    NULL AS address2,
		    NULL AS state,
		    NULL AS pincode,
		    NULL AS pv_capacity,
		    developers.e_invoice_url AS e_invoice_url,
		    NULL AS geo_id,
		    NULL AS TDS,
		    developers.developer_fee AS developer_fee,
		    application_category.category_name AS category_name,
		    application_category.id AS category_id
		FROM developer_success_payment
		LEFT JOIN developer_payment ON developer_success_payment.payment_id = developer_payment.id
		LEFT JOIN developers ON developer_success_payment.installer_id = developers.id
		LEFT JOIN developer_application_category_mapping ON developer_application_category_mapping.installer_id = developers.id
		LEFT JOIN application_category ON application_category.id = developer_application_category_mapping.application_category_id
		WHERE developer_success_payment.payment_dt BETWEEN '".$monthFromYear."' AND '".$endFromYear."'
		AND developer_payment.id IS NOT NULL
		GROUP BY developers.id";

		$sqlApplication4 = "SELECT DISTINCT 
		    re_success_payment.payment_dt,
		    re_application_payment.receipt_no,
		    applications.name_of_applicant AS Consumer_name,
		    applications.GST AS GST,
		    NULL AS ApplicationFee,
		    NULL AS GST1,
		    NULL AS GST2,
		    NULL AS TotalGST,
		    NULL AS TotalapplicationFee,
		    re_application_payment.transaction_id,
		    applications.address1 AS address1,
		    applications.taluka AS address2,
		    applications.state AS state,
		    applications.pincode AS pincode,
		    NULL AS pv_capacity,
		    applications.e_invoice_url AS e_invoice_url,
		    NULL AS geo_id,
		    NULL AS TDS,
		    NULL AS developer_fee,
		    application_category.category_name AS category_name,
		    NULL AS category_id
		FROM re_success_payment
		INNER JOIN re_application_payment ON re_application_payment.id = re_success_payment.payment_id
		LEFT JOIN applications ON re_application_payment.application_id = applications.id
		LEFT JOIN application_category ON application_category.id = applications.application_type
		WHERE re_success_payment.payment_dt BETWEEN '".$monthFromYear."' AND '".$endFromYear."'
		GROUP BY applications.id";

		$sqlApplication5 = "SELECT DISTINCT 
		    geo_success_payment.payment_dt,
		    geo_application_payment.receipt_no,
		    applications.name_of_applicant AS Consumer_name,
		    applications.GST AS GST,
		    geo_application_payment.application_fees AS ApplicationFee,
		    (geo_application_payment.gst_fees / 2) AS GST1,
		    (geo_application_payment.gst_fees / 2) AS GST2,
		    geo_application_payment.gst_fees AS TotalGST,
		    geo_application_payment.payment_amount AS TotalapplicationFee,
		    geo_application_payment.transaction_id,
		    applications.address1 AS address1,
		    applications.taluka AS address2,
		    applications.state AS state,
		    applications.pincode AS pincode,
		    NULL AS pv_capacity,
		    NULL AS e_invoice_url,
		    geo_application_payment.geo_id AS geo_id,
		    geo_application_payment.geo_location_tds AS TDS,
		    NULL AS developer_fee,
		    NULL AS category_name,
		    NULL AS category_id
		FROM geo_success_payment
		LEFT JOIN geo_application_payment ON geo_application_payment.id = geo_success_payment.payment_id
		LEFT JOIN application_geo_location ON geo_application_payment.application_id = application_geo_location.application_id
		LEFT JOIN applications ON application_geo_location.application_id = applications.id
		WHERE geo_success_payment.payment_dt BETWEEN '".$monthFromYear."' AND '".$endFromYear."'";


		$sqlApplication = $sqlApplication1 . " UNION ALL " . $sqlApplication2 . " UNION ALL " . $sqlApplication3 . " UNION ALL " . $sqlApplication4 . " UNION ALL " . $sqlApplication5 . " ORDER BY receipt_no ASC";

		// Execute the combined query
		//$result = $pdo->query($sqlApplication);
		$conn 				= ConnectionManager::get('default');
		$appResult 			= $conn->execute($sqlApplication)->fetchAll('assoc');
		//echo"<pre>"; print_r($appResult); die();
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
									'kw' 						=> 'kW',
									'receipt_no'			=> "Receipt No.",
									'Consumer_name'			=> "Consumer Name",
									'address1'				=> "Address1",
									'address2'				=> "Address2",
									'address3'				=> "Address3",
									'address4'				=> "Address4",
									'pincode'				=> "Pincode",
									'state'					=> "State",
									'gstno' 				=> "GST No.",
									'ApplicationFee'		=> "Application Fee",
									'GST1'					=> "GST1",
									'GST2'					=> "GST2",
									'TotalGST'				=> "TotalGST",
									'TotalapplicationFee'	=> "TotalapplicationFee",
									'transaction_id'		=> "Transaction Id",
									'e_invoice_url' 		=> "E-Invoice URL",
									'Particulars' 			=> "Particulars",
									);
		//$arrSecond 			= array('ticket_closed'=>'Ticket Closed','last_response'=>'Last Response','2nd_last_response'=> '2nd Last Response','3rd_last_response'=> '3rd Last Response','4th_last_response'=> '4th Last Response','5th_last_response'=> '5th Last Response');
		foreach ($arrReportFields as $key=>$Field_Name) {
			$RowName 	= $this->FeesReturn->GetExcelColumnName($i);

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
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$j-1);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$strDate 	= !empty($arrPaymentDate[0]) ? date('d-m-Y',strtotime($arrPaymentDate[0])) : '';
				$PhpExcel->writeCellValue($RowName.$j,$strDate);
				$i++;
				$paymentTime 		= '';
				if(isset($arrPaymentDate[1])) {
					$paymentTime 	= $arrPaymentDate[1];
				}
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$paymentTime);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['pv_capacity']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['receipt_no']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['Consumer_name']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['address1']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['address2']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,'');
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,'');
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['pincode']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['state']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['gstno']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['ApplicationFee']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['GST1']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['GST2']);
				$i++;

				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['TotalGST']);
				$i++;

				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['TotalapplicationFee']);
				$i++;

				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['transaction_id']);
				$i++;

				$e_invoice_url 	= !empty($val['e_invoice_url']) ? '=Hyperlink("'.$val['e_invoice_url'].'","'.$val['e_invoice_url'].'")' : '';
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$e_invoice_url);
				$i++;

				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,'Registration Fee- Solar Project');
				$i++;
				
	
				$i=1;
				$j++;
			}
		}
		$PhpExcel->downloadFile(time());
		exit;
	}
	public function donwloadAllApplicationPayment($Year='',$Month='') {
		$monthFromYear 		= $Year.'-'.$Month.'-01 00:00:00';
		$endFromYear 		= date("Y-m-d", strtotime($monthFromYear." +1 month"))." 00:00:00";
		

		$sqlApplication1 = "WITH LatestPayments AS (
						    SELECT
						        payment_id,
						        MAX(payment_dt) AS latest_payment_dt
						    FROM applyonline_payment
						    GROUP BY payment_id
						)

						SELECT 
						    ap.payment_dt,
						    ao.pv_capacity As Capacity,
		    				NULL AS total_capacity,
						    pu.receipt_no,
						    CONCAT(ao.name_of_consumer_applicant, ' ', ao.last_name, ' ', ao.third_name) AS Consumer_name,
						    ao.address1 AS address1,
						    ao.address2 AS address2,
						    ao.state AS state,
						    ao.pincode AS pincode,
						    ao.gstno AS GST,
						    ao.disCom_application_fee AS ApplicationFee,
						    (ao.jreda_processing_fee / 2) AS GST1,
						    (ao.jreda_processing_fee / 2) AS GST2,
						    ao.jreda_processing_fee AS TotalGST,
						    NULL AS TDS,
						    NULL AS IncomeHead,
						    rm.app_type,
						    ao.application_no AS ApplicationNo,
						    ao.name_of_consumer_applicant AS ApplicationName,
						    NULL AS category_name,
						    ao.disCom_application_fee + ao.jreda_processing_fee AS TotalapplicationFee,
						    pu.transaction_id,
						    aoo.e_invoice_url AS e_invoice_url
						FROM LatestPayments lp
						JOIN applyonline_payment ap ON lp.payment_id = ap.payment_id AND lp.latest_payment_dt = ap.payment_dt
						LEFT JOIN payumoney pu ON ap.payment_id = pu.id
						LEFT JOIN apply_onlines ao ON ap.application_id = ao.id
						LEFT JOIN receipt_master rm ON ap.application_id = rm.application_id AND rm.app_type ='applyonline payment'
						LEFT JOIN apply_onlines_others aoo ON ao.id = aoo.application_id
						WHERE ap.payment_dt BETWEEN '".$monthFromYear."' AND '".$endFromYear."'";
			//GROUP BY apply_onlines.id


		$sqlApplication2 = "SELECT DISTINCT 
		    installer_success_payment.payment_dt,
		    NULL AS Capacity,
		    NULL AS total_capacity,
		    installer_payment.receipt_no,
		    installers.installer_name AS Consumer_name,
		    installers.address,
		    NULL AS address2,
		    installers.state AS state,
		    installers.pincode AS pincode,

		    installers.gst AS GST,
		    10000 AS ApplicationFee,
		    900 AS GST1,
		    900 AS GST2,
		    1800 AS TotalGST,
		    NULL AS TDS,
		    NULL AS IncomeHead,
		    rm.app_type,
		    NULL AS ApplicationNo,
		    installers.installer_name AS ApplicationName,
		    NULL AS category_name,
		    11800 AS TotalapplicationFee,
		    installer_payment.transaction_id,
		    installers.e_invoice_url AS e_invoice_url
		   
		FROM installer_success_payment
		LEFT JOIN installer_payment ON installer_success_payment.payment_id = installer_payment.id
		LEFT JOIN installers ON installer_success_payment.installer_id = installers.id
		LEFT JOIN receipt_master rm ON installers.id = rm.application_id AND rm.app_type ='installer payment'
		WHERE installer_success_payment.payment_dt BETWEEN '".$monthFromYear."' AND '".$endFromYear."' ";
		//GROUP BY installers.id

		$sqlApplication3 = "SELECT DISTINCT 
		    developer_success_payment.payment_dt,
		    NULL As Capacity,
		    NULL AS total_capacity,
		    developer_payment.receipt_no,
		    developers.installer_name AS Consumer_name,
		    developers.address,
		    NULL AS address2,
		    developers.state AS state,
		    developers.pincode AS pincode,
		    developers.gst AS GST,
		    developers.developer_fee AS ApplicationFee,
		    (developer_application_category_mapping.gst_fees / 2) AS GST1,
		    (developer_application_category_mapping.gst_fees / 2) AS GST2,
		    developer_application_category_mapping.gst_fees AS TotalGST,
		    NULL AS TDS,
		    NULL AS IncomeHead,
		    rm.app_type,
		    NULL AS ApplicationNo,
		    developers.installer_name AS ApplicationName,
		    NULL AS category_name,
		    developer_application_category_mapping.developer_total_fee AS TotalapplicationFee,
		    developer_payment.transaction_id,
		    developers.e_invoice_url AS e_invoice_url
		    
		FROM developer_success_payment
		LEFT JOIN developer_payment ON developer_success_payment.payment_id = developer_payment.id
		LEFT JOIN developers ON developer_success_payment.installer_id = developers.id
		LEFT JOIN developer_application_category_mapping ON developer_application_category_mapping.installer_id = developers.id
		LEFT JOIN application_category ON application_category.id = developer_application_category_mapping.application_category_id
		LEFT JOIN receipt_master rm ON developers.id = rm.application_id AND rm.app_type ='developer payment'
		WHERE developer_success_payment.payment_dt BETWEEN '".$monthFromYear."' AND '".$endFromYear."'
		AND developer_payment.id IS NOT NULL";
		//GROUP BY developers.id

		$sqlApplication4 = "SELECT DISTINCT 
		    re_success_payment.payment_dt,
		    applications.pv_capacity_ac As Capacity,
		    applications.total_capacity AS total_capacity,
		    re_application_payment.receipt_no,
		    applications.name_of_applicant AS Consumer_name,
		    applications.address1 AS address1,
		    applications.taluka AS address2,
		    applications.state AS state,
		    applications.pincode AS pincode,
		    applications.GST AS GST,
		    NULL AS ApplicationFee,
		    NULL AS GST1,
		    NULL AS GST2,
		    NULL AS TotalGST,
		    applications.tds_deduction AS TDS,
		    NULL AS IncomeHead,
		   	rm.app_type,
		   	applications.application_no AS ApplicationNo,
		   	applications.name_of_applicant AS ApplicationName,
		    application_category.category_name AS category_name,
		    NULL AS TotalapplicationFee,
		    re_application_payment.transaction_id,
		    applications.e_invoice_url AS e_invoice_url
		   
		FROM re_success_payment
		INNER JOIN re_application_payment ON re_application_payment.id = re_success_payment.payment_id
		LEFT JOIN applications ON re_application_payment.application_id = applications.id
		LEFT JOIN application_category ON application_category.id = applications.application_type
		LEFT JOIN receipt_master rm ON applications.id = rm.application_id AND rm.app_type ='re application'
		WHERE re_success_payment.payment_dt BETWEEN '".$monthFromYear."' AND '".$endFromYear."' ";
		//GROUP BY applications.id

		$sqlApplication5 = "SELECT DISTINCT 
		    geo_success_payment.payment_dt,
		    NULL As Capacity,
		    NULL AS total_capacity,
		    geo_application_payment.receipt_no,
		    applications.name_of_applicant AS Consumer_name,
		    applications.address1 AS address1,
		    applications.taluka AS address2,
		    applications.state AS state,
		    applications.pincode AS pincode,
		    applications.GST AS GST,
		    geo_application_payment.application_fees AS ApplicationFee,
		    (geo_application_payment.gst_fees / 2) AS GST1,
		    (geo_application_payment.gst_fees / 2) AS GST2,
		    geo_application_payment.gst_fees AS TotalGST,
		    geo_application_payment.geo_location_tds AS TDS,
		    NULL AS IncomeHead,
		    rm.app_type,
		    applications.application_no AS ApplicationNo,
		    applications.name_of_applicant AS ApplicationName,
		    NULL AS category_name,
		    geo_application_payment.payment_amount AS TotalapplicationFee,
		    geo_application_payment.transaction_id,
		    NULL AS e_invoice_url
		    
		FROM geo_success_payment
		LEFT JOIN geo_application_payment ON geo_application_payment.id = geo_success_payment.payment_id
		LEFT JOIN application_geo_location ON geo_application_payment.application_id = application_geo_location.application_id
		LEFT JOIN applications ON application_geo_location.application_id = applications.id
		LEFT JOIN receipt_master rm ON application_geo_location.id = rm.application_id AND rm.app_type ='geo location'
		WHERE geo_success_payment.payment_dt BETWEEN '".$monthFromYear."' AND '".$endFromYear."'";


		$sqlApplication = $sqlApplication1 . " UNION ALL " . $sqlApplication2 . " UNION ALL " . $sqlApplication3 . " UNION ALL " . $sqlApplication4 . " UNION ALL " . $sqlApplication5 . " ORDER BY receipt_no ASC";

		// Execute the combined query
		//$result = $pdo->query($sqlApplication);
		$conn 				= ConnectionManager::get('default');
		$appResult 			= $conn->execute($sqlApplication)->fetchAll('assoc');
		//echo"<pre>"; print_r($appResult); die();
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
									'kw' 						=> 'kW',
									'receipt_no'			=> "Receipt No.",
									'Consumer_name'			=> "Consumer Name",
									'address1'				=> "Address1",
									'address2'				=> "Address2",
									'state'					=> "State",
									'pincode'				=> "Pincode",
									'GST' 					=> "GST No.",
									'IncomeHead' 			=> "Income Head",
									'ApplicationFee'		=> "Application Fee",
									'GST1'					=> "GST1",
									'GST2'					=> "GST2",
									'TotalGST'				=> "TotalGST",
									'TDS'					=> "TDS",
									'TotalapplicationFee'	=> "TotalapplicationFee",
									'transaction_id'		=> "Transaction Id",
									'Narration' 			=> "Narration",
									'Remark' 				=> "Remark",
									'e_invoice_url' 		=> "E-Invoice URL",
									//'Particulars' 			=> "Particulars",
									);
		//$arrSecond 			= array('ticket_closed'=>'Ticket Closed','last_response'=>'Last Response','2nd_last_response'=> '2nd Last Response','3rd_last_response'=> '3rd Last Response','4th_last_response'=> '4th Last Response','5th_last_response'=> '5th Last Response');
		foreach ($arrReportFields as $key=>$Field_Name) {
			$RowName 	= $this->FeesReturn->GetExcelColumnName($i);

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
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$j-1);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$strDate 	= !empty($arrPaymentDate[0]) ? date('d-m-Y',strtotime($arrPaymentDate[0])) : '';
				$PhpExcel->writeCellValue($RowName.$j,$strDate);
				$i++;
				$paymentTime 		= '';
				if(isset($arrPaymentDate[1])) {
					$paymentTime 	= $arrPaymentDate[1];
				}
				if($val['app_type'] == 'applyonline payment'){
					$Capacity 	= $val['Capacity'];
					$IncomeHead ='Registration Fee- Solar Project';
					$narration 	= 'Application No: '.$val['ApplicationNo']. ' Received from '.$val['ApplicationName'].' for registration fees for '.$Capacity.' kW
									 solar PV project.';
				}else if($val['app_type'] == 'installer payment'){
					$Capacity 	= '';
					$IncomeHead = 'Processing Fee- Installers';
					$narration 	= 'Received from '.$val['ApplicationName'].' '.$Capacity;
				}else if($val['app_type'] == 'developer payment'){
					$Capacity 	= '';
					$IncomeHead = 'Processing Fee- Developer';
					$narration 	= 'Received from '.$val['ApplicationName'].' '.$Capacity;
				}else if($val['app_type'] == 'geo location'){
					$Capacity 	= '';
					$IncomeHead = 'Application Coordinates Verification';
					$narration 	= 'Received from '.$val['ApplicationName'].' as processing fee for WTG Co-ordinate Verification';
				}else if($val['app_type'] == 're application' && $val['category_name'] == 'Wind'){
					$Capacity 	= $val['total_capacity'];
					$IncomeHead = 'Application Processing Fees - Wind (Provisional)';
					$narration 	= 'Net Payment Received from '.$val['ApplicationName'].' for application
									processing fees for Provisional Registration of '.$Capacity.' MW '.$val['category_name'].' project.';
				}else if($val['app_type'] == 're application' && $val['category_name'] == 'Hybrid'){
					$Capacity 	= $val['total_capacity'];
					$IncomeHead = 'Application Processing Fees - Hybrid (Provisional)';
					$narration 	= 'Net Payment Received from '.$val['ApplicationName'].' for application
									processing fees for Provisional Registration of '.$Capacity.' MW '.$val['category_name'].' project.';
				}else if($val['app_type'] == 're application' && $val['category_name'] == 'Open Access Solar'){
					$Capacity 	= $val['Capacity'];
					$IncomeHead = 'Application Processing Fees - Open Access Solar (Provisional)';
					$narration 	= 'Net Payment Received from '.$val['ApplicationName'].' for application
									processing fees for Provisional Registration of '.$Capacity.' MW '.$val['category_name'].' project.';
				}
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$paymentTime);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$Capacity);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['receipt_no']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['Consumer_name']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['address1']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['address2']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['state']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['pincode']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,isset($val['GST'])?$val['GST']:'');
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,isset($IncomeHead)?$IncomeHead:'');
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['ApplicationFee']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['GST1']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['GST2']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['TotalGST']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,(($val['TDS']>0) ? $val['TDS'] : 0));
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['TotalapplicationFee']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$val['transaction_id']);
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,isset($narration)?$narration:'');
				$i++;
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName . $j, "Receipt No.:- " . $val['receipt_no'] . ", Transaction Id:- " . $val['transaction_id'] . ", Narration:- " . $narration );
				$i++;
				
				$e_invoice_url 	= !empty($val['e_invoice_url']) ? '=Hyperlink("'.$val['e_invoice_url'].'","'.$val['e_invoice_url'].'")' : '';
				$RowName = $this->FeesReturn->GetExcelColumnName($i);
				$PhpExcel->writeCellValue($RowName.$j,$e_invoice_url);
				$i++;

				// $RowName = $this->FeesReturn->GetExcelColumnName($i);
				// $PhpExcel->writeCellValue($RowName.$j,'Registration Fee- Solar Project');
				// $i++;
				
	
				$i=1;
				$j++;
			}
		}
		$PhpExcel->downloadFile(time());
		exit;
	}
}