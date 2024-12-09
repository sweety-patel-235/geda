<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;
use Cake\Core\Configure;
use Cake\Network\Email\Email;
/**
 * Short description for file
 * This Model use for Ticket table. It extends AppModel Class
 * @category  Class File
 * @Desc      Manage Ticket information
 * @author    jaysinh Rajpoot
 * @version   RR
 * @since     File available since RR 1.0
 */
class PayumoneyTable extends AppTable {
	/**
	 * The status of $name is universe
	 * Potential value are Class Name
	 * @var String
	 */
	public $Name 	= 'Payumoney';

	/**
	 * The status of $useTable is universe
	 * Potential value are Database Table name
	 * @var String
	 */
	public $useTable = 'payumoney';
	public function initialize(array $config)
	{
		$this->table($this->useTable);
	}
	public function savedata_success($arr_request_data,$is_mobile=0)
	{
		$payuTable          = TableRegistry::get('Payumoney');

		if(PAYMENT_METHOD=='hdfc')
		{
			$payusave = $payuTable->find('all')->where(['payment_id' => $arr_request_data['order_id']])->first();
		}
		else
		{
			$payusave = $payuTable->find('all')->where(['payment_id' => $arr_request_data['mihpayid']])->first();
		}
		$flagNew      = 0;
		//$flagNew      = 1;
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
				$payusave->payment_date     =  date('Y-m-d H:i:s',strtotime(str_replace('/', '-', $arr_request_data['trans_date'])));
			}
			$payusave->transaction_id   =  $arr_request_data['tracking_id'];
			if($flagNew == 1){
				$payusave->receipt_no       =  GetGenerateReceiptNo('',$current_date);
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
		if($flagNew == 1 || empty($payusave->receipt_no)) {
			//Receipt  No Code
			$ReceiptMaster 				= TableRegistry::get('ReceiptMaster');
			$receiptTable_sel 			= $ReceiptMaster->find();
			$receiptTable_sel->hydrate(false);
			$payu_data 					= $receiptTable_sel->select(['max' => $receiptTable_sel->func()->max('max_serial_no')])->where(array('created_year' => $current_year))->first();
			//Receipt  No Code

			//$payu_data                  = $payuTable_sel->select(['max' => $payuTable_sel->func()->max('Payumoney.max_serial_no')])->where(array('created_year' => $current_year))->first();
			$receipt_no 				= GetGenerateReceiptNo($payu_data['max']+1,date('Y-m-d'));
			$payusave->receipt_no       = $receipt_no;
			$payusave->max_serial_no    = $payu_data['max']+1;
		}
		$payusave->payment_data         = json_encode($arr_request_data);
		$payusave->created              = $this->NOW();
		$payusave->created_year         = $current_year;
		if ($payuTable->save($payusave)) 
		{
			if($payusave->udf1!='' && $payusave->payment_status=='success')
			{
				$apply_onlinesTable                 = TableRegistry::get('ApplyOnlines');
				$apply_onlinesTable->updateAll(['payment_status' => '1','modified'=>$this->NOW()], ['id' => decode($payusave->udf1)]);
				$applyonlinepayTable                = TableRegistry::get('applyonline_payment');
				$applyonlinepaySave                 = $applyonlinepayTable->newEntity();
				$applyonlinepaySave->application_id = decode($payusave->udf1);
				$applyonlinepaySave->payment_id     = $payusave->id;
				$applyonlinepaySave->payment_for    = 1;
				$applyonlinepaySave->payment_dt     = $payusave->payment_date;
				$applyonlinepaySave->created        = $this->NOW();
				$applyonlinepayTable->save($applyonlinepaySave);
				
				//Receipt  No Code
				$ReceiptMaster->save_receipt_master(decode($payusave->udf1), $receipt_no, $payu_data['max'] + 1, 6, $current_year);
				//Receipt  No Code

				$ApplicationPaymentRequest 		= TableRegistry::get('ApplicationPaymentRequest');
				$arrPayment 					= $ApplicationPaymentRequest->find('all',array('conditions'=>array('application_id'=>decode($payusave->udf1),'response_data IS NULL'),'order'=>array('id'=>'desc')))->first();
				if(!empty($arrPayment)) {
					$arrpay['application_id'] 	= decode($payusave->udf1);
					
					$arrpay['modified'] 		= $this->NOW();
					$arrpay['response_data']	= json_encode($arr_request_data);
					$ApplicationPaymentRequest->updateAll($arrpay,array('id'=>$arrPayment->id));
				}
				$EInvoice 	= TableRegistry::get("EInvoice");
				$EInvoice->getAccessToken(decode($payusave->udf1),'application');
				$this->SuccessPaymentEmailToGEDA(decode($payusave->udf1));
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
		$payuTable = TableRegistry::get('Payumoney');
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

			$ApplicationPaymentRequest 			= TableRegistry::get('ApplicationPaymentRequest');
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
		$ApplyOnlines = TableRegistry::get('ApplyOnlines');
		$ApplyonlinePayment = TableRegistry::get('ApplyonlinePayment');
		$payuTable = TableRegistry::get('Payumoney');
		$applyOnlinesData           = $ApplyOnlines->viewApplication($application_id);
		$GEDA_APPLICATION_NO        = $applyOnlinesData->geda_application_no;
		
		
		$to                         = !empty($CUSTOMER_EMAIL)?$CUSTOMER_EMAIL:"kalpak.yugtia@gmail.com";

		$payment_data               = $ApplyonlinePayment->find('all',array('conditions'=>array('application_id'=>$application_id),'order'=>array('id'=>'desc')))->first();

		$payment_details            = $payuTable->find('all',array('conditions'=>array('id'=>$payment_data->payment_id)))->first();

		$EmailVars                  = array("GEDA_APPLICATION_NO"=>$GEDA_APPLICATION_NO,
											"RECEIPT_NO"=>$payment_details->receipt_no);

		$email                      = new Email('default');
		$subject                    = "[REG: E-Receipt ".$payment_details->receipt_no."] Payment Successfully Done.";
		$PaymentFileName             = $this->generatePaymentReceiptPdf(encode($application_id),false,true);
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
}
?>