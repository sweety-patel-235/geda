<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;
use Cake\Core\Configure;
use Cake\Network\Email\Email;
use Cake\View\View;
use Dompdf\Dompdf;
/**
 * Short description for file
 * This Model use for Ticket table. It extends AppModel Class
 * @category  Class File
 * @Desc      Manage Ticket information
 * @author    jaysinh Rajpoot
 * @version   RR
 * @since     File available since RR 1.0
 */
class PayumoneyKusumTable extends AppTable {
	/**
	 * The status of $name is universe
	 * Potential value are Class Name
	 * @var String
	 */
	public $Name 	= 'PayumoneyKusum';

	/**
	 * The status of $useTable is universe
	 * Potential value are Database Table name
	 * @var String
	 */
	public $useTable = 'payumoney_kusum';
	public function initialize(array $config)
	{
		$this->table($this->useTable);
	}
	public function savedata_success($arr_request_data,$is_mobile=0)
	{
		$payuTable          = TableRegistry::get('PayumoneyKusum');

		if(PAYMENT_METHOD=='hdfc')
		{
			$payusave = $payuTable->find('all')->where(['payment_id' => $arr_request_data['order_id']])->first();
		}
		else
		{
			$payusave = $payuTable->find('all')->where(['payment_id' => $arr_request_data['mihpayid']])->first();
		}
		$flagNew      = 0;
		if(empty($payusave)){
			$payusave = $payuTable->newEntity();
			$flagNew  = 1;
		}
		$current_date = date('Y-m-d');
		$current_year = date('Y');
		if(PAYMENT_METHOD=='hdfc')
		{
			$payusave->payment_id       =  $arr_request_data['order_id'];
			$payusave->payment_status   =  strtolower($arr_request_data['order_status']);
			$payusave->udf1             =  $arr_request_data['merchant_param1'];
			$payusave->application_id   =  decode($arr_request_data['merchant_param1']);
			if($arr_request_data['trans_date']!='null' && !empty($arr_request_data['trans_date']))
			{
				$payusave->payment_date =  date('Y-m-d H:i:s',strtotime(str_replace('/', '-', $arr_request_data['trans_date'])));
			}
			$payusave->transaction_id  	=  $arr_request_data['tracking_id'];
			if($flagNew == 1){
				$payusave->receipt_no 	=  GetGenerateReceiptNo('',$current_date);
			}
		}
		else
		{
			$payusave->payment_id       = $arr_request_data['mihpayid'];
			$payusave->payment_status   = $arr_request_data['status'];
			$payusave->udf1             = $arr_request_data['udf1'];
			$payusave->application_id   = decode($arr_request_data['udf1']);
			$payusave->payment_date     = $arr_request_data['addedon'];
			$payusave->transaction_id   = $arr_request_data['txnid'];
		}
		$payuTable_sel                  = $payuTable->find();
		$payuTable_sel->hydrate(false);
		$current_year                   = date('Y');
		if (intval(date('m')) >= 1 && intval(date('m')) <= 3) {
			$current_year               = date('Y')-1;
		}
		if($flagNew == 1){
			$payu_data                  = $payuTable_sel->select(['max' => $payuTable_sel->func()->max('PayumoneyKusum.max_serial_no')])->where(array('created_year' => $current_year))->first();
			$payusave->receipt_no       = GetGenerateReceiptNo($payu_data['max']+1,date('Y-m-d'));
			$payusave->max_serial_no    = $payu_data['max']+1;
		}
		$payusave->payment_data         = json_encode($arr_request_data);
		$payusave->created              = $this->NOW();
		$payusave->created_year         = $current_year;
		if ($payuTable->save($payusave)) 
		{
			if($payusave->udf1!='')
			{
				$apply_onlinesTable 				= TableRegistry::get('ApplyOnlinesKusum');
				$apply_onlinesTable->updateAll(['payment_status' => '1','modified'=>$this->NOW()], ['id' => decode($payusave->udf1)]);
				$applyonlinepayTable 				= TableRegistry::get('applyonline_kusum_payment');
				$applyonlinepaySave                 = $applyonlinepayTable->newEntity();
				$applyonlinepaySave->application_id = decode($payusave->udf1);
				$applyonlinepaySave->payment_id 	= $payusave->id;
				$applyonlinepaySave->payment_for    = 1;
				$applyonlinepaySave->payment_dt 	= $payusave->payment_date;
				$applyonlinepaySave->created        = $this->NOW();
				$applyonlinepayTable->save($applyonlinepaySave);
				$this->SuccessPaymentEmailToGEDA(decode($payusave->udf1));

				$ApplicationPaymentRequest 		= TableRegistry::get('ApplicationKusumPaymentRequest');
				$arrPayment 					= $ApplicationPaymentRequest->find('all',array('conditions'=>array('application_id'=>decode($payusave->udf1),'response_data IS NULL'),'order'=>array('id'=>'desc')))->first();
				if(!empty($arrPayment)) {
					$arrpay['application_id'] 	= decode($payusave->udf1);
					
					$arrpay['modified'] 		= $this->NOW();
					$arrpay['response_data']	= json_encode($arr_request_data);
					$ApplicationPaymentRequest->updateAll($arrpay,array('id'=>$arrPayment->id));
				}
			}
			if($is_mobile==0)
			{
				if($payusave->udf1!='')
				{
					return 1;
				}
				else
				{
					 return 0;
				}
			}
			else
			{
				return 1;
			}
				  
		}
	}
	public function savedata_failure($arr_request_data,$is_mobile=0)
	{
		$payuTable = TableRegistry::get('PayumoneyKusum');
		if(PAYMENT_METHOD=='hdfc')
		{
			$payusave = $payuTable->find('all')->where(['payment_id' => $arr_request_data['order_id']])->first();
		}
		else
		{
			$payusave = $payuTable->find('all')->where(['payment_id' => $arr_request_data['mihpayid']])->first();
		}
		if(empty($payusave)){
			$payusave = $payuTable->newEntity();
		}
		if(PAYMENT_METHOD=='hdfc')
		{
			$payusave->payment_id       =  $arr_request_data['order_id'];
			$payusave->payment_status   =  strtolower($arr_request_data['order_status']);
			$payusave->udf1             =  $arr_request_data['merchant_param1'];
			$payusave->application_id   =  decode($arr_request_data['merchant_param1']);
			if($arr_request_data['trans_date']!='null' && !empty($arr_request_data['trans_date']))
			{
				$payusave->payment_date     =  date('Y-m-d H:i:s',strtotime($arr_request_data['trans_date']));
			}
			$payusave->transaction_id   =  $arr_request_data['tracking_id'];
		}
		else
		{
			$payusave->payment_id       =  $arr_request_data['mihpayid'];
			$payusave->payment_status   =  $arr_request_data['status'];        
			$payusave->transaction_id   =  $arr_request_data['txnid'];
			$payusave->payment_date     =  $arr_request_data['addedon'];     
			$payusave->udf1             =  $arr_request_data['udf1'];
			$payusave->application_id   =  decode($arr_request_data['udf1']);
		}
		$payusave->payment_data     =  json_encode($arr_request_data);
		$payusave->created              = $this->NOW();
		if ($payuTable->save($payusave))
		{

			$ApplicationPaymentRequest 			= TableRegistry::get('ApplicationKusumPaymentRequest');
				$arrPayment 					= $ApplicationPaymentRequest->find('all',array('conditions'=>array('application_id'=>$payusave->application_id,'response_data IS NULL'),'order'=>array('id'=>'desc')))->first();
				if(!empty($arrPayment)) {
					$arrpay['application_id'] 	= $payusave->application_id;
					
					$arrpay['modified'] 		= $this->NOW();
					$arrpay['response_data']	= json_encode($arr_request_data);
					$ApplicationPaymentRequest->updateAll($arrpay,array('id'=>$arrPayment->id));
				}
			if($is_mobile==0)
			{
				if($payusave->udf1!='')
				{
					 return 1;
				}
				else
				{
				   return 0;
				}
			}
			else
			{
				return 1;
			}     
		}
	}
	/*
	 * SuccessPaymentEmailToGEDA To Customer
	 * @param mixed What page to display
	 * @return void
	 */
	public function SuccessPaymentEmailToGEDA($application_id=0)
	{
		$this->autoRender           = false;
		$ApplyOnlines 				= TableRegistry::get('ApplyOnlinesKusum');
		$ApplyonlinePayment 		= TableRegistry::get('ApplyonlineKusumPayment');
		$payuTable 					= TableRegistry::get('PayumoneyKusum');
		$applyOnlinesData           = $ApplyOnlines->viewApplication($application_id);
		$GEDA_APPLICATION_NO        = $applyOnlinesData->geda_application_no;
		
		
		$to                         = !empty($CUSTOMER_EMAIL)?$CUSTOMER_EMAIL:"kalpak.yugtia@gmail.com";

		$payment_data               = $ApplyonlinePayment->find('all',array('conditions'=>array('application_id'=>$application_id),'order'=>array('id'=>'desc')))->first();

		$payment_details            = $payuTable->find('all',array('conditions'=>array('id'=>$payment_data->payment_id)))->first();

		$EmailVars                  = array("GEDA_APPLICATION_NO"=>$GEDA_APPLICATION_NO,
											"RECEIPT_NO"=>$payment_details->receipt_no);

		$email                      = new Email('default');
		$subject                    = "[REG: E-Receipt ".$payment_details->receipt_no."] Payment Successfully Done.";
		$PaymentFileName             = $this->generatePaymentKusumReceiptPdf(encode($application_id),false,true);
		//->bcc("jayshree.tailor@yugtia.com")
				
		$email          = new Email('default');
		$email->profile('default');
		$email->viewVars($EmailVars);
		$message_send   = $email->template('payment_receipt_mail', 'default')
				->emailFormat('html')
				->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
				->to(GEDA_PAYMENTRECIEPT_MAIL)
				->subject(Configure::read('EMAIL_ENV').$subject);
				if(!empty($PaymentFileName))
				{
					$message_send->addAttachments($PaymentFileName);
				}
		
				$message_send->send();
				unlink($PaymentFileName);
		return true;
	}
	/**
	 * generatePaymentKusumReceiptPdf
	 * Behaviour : public
	 * @param : id  : Id is use to identify for which application letter file should be downlaoded, $isdownload=true
	 * @defination : Method is use to download .pdf file from applyonline listing
	 *
	 */
	public function generatePaymentKusumReceiptPdf($id,$isdownload=true,$mailData=false)
	{
		
		$ApplyOnlines 			= TableRegistry::get('ApplyOnlinesKusum');
		$ApplyonlinePayment 	= TableRegistry::get('ApplyonlineKusumPayment');
		$Installers 			= TableRegistry::get('Installers');
		$MembersTable 			= TableRegistry::get('Members');
		$BranchMasters 			= TableRegistry::get('BranchMasters');
		$DiscomMaster 			= TableRegistry::get('DiscomMaster');
		$ApplyOnlineApprovals 	= TableRegistry::get('ApplyOnlineKusumApprovals');
		
		if(empty($id)) {
			$this->Flash->error('Please Select Valid Installer.');
			return $this->redirect('home');
		} else {
			$encode_id 					= $id;
			$id 						= intval(decode($id));
			$payment_data 				= $ApplyonlinePayment->find('all',array('conditions'=>array('application_id'=>$id),'order'=>array('id'=>'desc')))->first();

			$payment_details 			= $this->find('all',array('conditions'=>array('id'=>$payment_data->payment_id)))->first();


			$applyOnlinesData 			= $ApplyOnlines->viewApplication($id);

			$applyOnlinesData->aid 		= "1".str_pad($id,7, "0", STR_PAD_LEFT);
			$LETTER_APPLICATION_NO 		= $applyOnlinesData->aid;
			$APPLICATION_DATE 			= date("d.m.Y",strtotime($applyOnlinesData->created));
			$Installers_data = $Installers->find("all",['conditions'=>['id'=>$applyOnlinesData->installer_id]])->first();
		    $Members = $MembersTable->find("all",['conditions'=>['member_type'=>'6003','name'=>'CEI']])->first();
		    $discom_data =array();
		    $discom_name ="";
		    if(!empty($applyOnlinesData->area)){
		    	$discom_data                = $MembersTable->find("all",['conditions'=>['area'=>$applyOnlinesData->area,'circle'=>'0','division'=>'0','subdivision'=>'0','section'=>'0']])->first();
		    	$discom_name                = $BranchMasters->find("all",['conditions'=>['id'=>$discom_data->branch_id]])->first();
		    	$discom_short_name          = $DiscomMaster->find("all",['conditions'=>['id'=>$discom_name->discom_id]])->first();
		    }

		}
		
		$applyOnlineGedaDate = $ApplyOnlineApprovals->getgedaletterStageData($id);
		$view = new View();
		$view->layout 			= false;
		$view->set("APPLY_ONLINE_MAIN_STATUS",$ApplyOnlineApprovals->apply_online_main_status);
		$view->set("pageTitle","Apply-online View");
		$view->set('ApplyOnlines',$applyOnlinesData);
		$view->set('Installers_data',$Installers_data);
		$view->set('Members',$Members);
		$view->set('LETTER_APPLICATION_NO',$LETTER_APPLICATION_NO);
		$view->set('APPLICATION_DATE',$APPLICATION_DATE);
		$view->set('discom_data',$discom_data);
		$view->set('discom_name',$discom_name);
		$view->set('applyOnlineGedaDate',$applyOnlineGedaDate);
		$view->set('payment_data',$payment_data);
		$view->set('payment_details',$payment_details);
		//$this->set("APPLY_ONLINE_GUJ_STATUS",$this->ApplyOnlineApprovals->apply_online_guj_status);
		$view->set('discom_short_name',$discom_short_name);

		/* Generate PDF for estimation of project */
		require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
		$dompdf = new Dompdf($options = array());
		$dompdf->set_option("isPhpEnabled", true);
		$view->set('dompdf',$dompdf);
		$dompdf->set_base_path(SITE_ROOT_DIR_PATH.'/pdf/');

		$html = $view->render('/Element/paymentreceipt');
		$dompdf->loadHtml($html,'UTF-8');
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();

		// Output the generated PDF to Browser
		if($isdownload){
			$dompdf->stream('applyonlinepayment-'.$LETTER_APPLICATION_NO);
		}
		$output = $dompdf->output();
		if($mailData)
		{
			$pdfPath 	= WWW_ROOT.'/tmp/paymentReceipt-'.$LETTER_APPLICATION_NO.'.pdf';
			file_put_contents($pdfPath, $output);
			return $pdfPath;
		}
		else
		{

			header("Content-type:application/pdf");
			header("Content-Disposition:inline;filename='".$LETTER_APPLICATION_NO.".pdf'");
			echo $output;
		}
		die;
	}
}
?>