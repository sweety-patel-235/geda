<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;
use Cake\Core\Configure;
use Cake\Network\Email\Email;
use Cake\Utility\Security;
/**
 * Short description for file
 * This Model use for Ticket table. It extends AppModel Class
 * @category  Class File
 * @Desc      Manage Ticket information
 * @author    jaysinh Rajpoot
 * @version   RR
 * @since     File available since RR 1.0
 */
class GeoShiftingApplicationPaymentTable extends AppTable {
	/**
	 * The status of $name is universe
	 * Potential value are Class Name
	 * @var String
	 */
	public $Name 	= 'GeoShiftingApplicationPayment';

	/**
	 * The status of $useTable is universe
	 * Potential value are Database Table name
	 * @var String
	 */
	public $useTable = 'geo_shifting_application_payment';
	public function initialize(array $config)
	{
		$this->table($this->useTable);
	}
	public function savedata_success($arr_request_data,$is_mobile=0)
	{
		//echo"<pre>"; print_r($arr_request_data); die();
		if(PAYMENT_METHOD=='hdfc')
		{
			$payusave 	= $this->find('all')->where(['payment_id' => $arr_request_data['order_id']])->first();
		}
		$flagNew 	 	= 0;
		if(empty($payusave)){
			$payusave 	= $this->newEntity();
			$flagNew 	= 1;
		}
		$current_date 					= date('Y-m-d');
		$current_year 					= date('Y');
		$geo_id   						= explode(',', $arr_request_data['merchant_param2']);
		$geo_id_witout_zero 			= array_filter($geo_id);
		$geo_id_coma_witout_zero 		= implode(',', $geo_id_witout_zero);
		if(PAYMENT_METHOD=='hdfc')
		{
			$payusave->payment_id       =  $arr_request_data['order_id'];
			$payusave->payment_status   =  strtolower($arr_request_data['order_status']);
			$payusave->udf1             =  $arr_request_data['merchant_param1'];
			$payusave->application_id   =  decode($arr_request_data['merchant_param1']);
			$payusave->geo_id   		=  $geo_id_coma_witout_zero;
			if($arr_request_data['trans_date']!='null' && !empty($arr_request_data['trans_date']))
			{
				$payusave->payment_date =  date('Y-m-d H:i:s',strtotime(str_replace('/', '-', $arr_request_data['trans_date'])));
			}
			$payusave->transaction_id   =  $arr_request_data['tracking_id'];
			//$payusave->receipt_no       =  GetGenerateReceiptNo('',$current_date);
		}
		
		$payuTable_sel                  = $this->find();
		$payuTable_sel->hydrate(false);
		$current_year                   = date('Y');
		if (intval(date('m')) >= 1 && intval(date('m')) <= 3) {
			$current_year               = date('Y')-1;
		}
		if($flagNew == 1 || empty($payusave->receipt_no)) {

			//Receipt  No Code
			$ReceiptMaster 				= TableRegistry::get('ReceiptMaster');
			$receiptTable_sel              = $ReceiptMaster->find();
			$receiptTable_sel->hydrate(false);
			$payu_data 					= $receiptTable_sel->select(['max' => $receiptTable_sel->func()->max('max_serial_no')])->where(array('created_year' => $current_year))->first();
			//Receipt  No Code
			//$payu_data                  = $payuTable_sel->select(['max' => $payuTable_sel->func()->max('max_serial_no')])->where(array('created_year' => $current_year))->first();
			$receipt_no 				= GetGenerateReceiptNo($payu_data['max']+1,date('Y-m-d'));
			$payusave->receipt_no       = $receipt_no;
			$payusave->max_serial_no    = $payu_data['max']+1;
			$payusave->payment_amount   = $arr_request_data['amount'];
			$payusave->gst_fees       	= $arr_request_data['merchant_param3'];
			$payusave->geo_location_tds = $arr_request_data['merchant_param4'];
			$payusave->application_fees = $arr_request_data['merchant_param5'];
		}
		$payusave->payment_data         = json_encode($arr_request_data);
		$payusave->created              = $this->NOW();
		$payusave->created_year         = $current_year;
		if ($this->save($payusave)) 
		{
			if($payusave->udf1!='' && $payusave->payment_status =='success')
			{
				$goe_location_shifting_Table                 = TableRegistry::get('GeoShiftingApplication');

				$goe_location_shifting_Table->updateAll(['payment_status' => '1','payment_date'=>$this->NOW()], ['geo_application_id IN' => $geo_id,'application_id'=> decode($payusave->udf1)]);
				$GeoShiftingSuccessPayment 			= TableRegistry::get('GeoShiftingSuccessPayment');
				$applyonlinepaySave                 = $GeoShiftingSuccessPayment->newEntity();
				$applyonlinepaySave->application_id = decode($payusave->udf1);
				$applyonlinepaySave->payment_id     = $payusave->id;
				$applyonlinepaySave->payment_for    = 1;
				$applyonlinepaySave->payment_dt     = $payusave->payment_date;
				$applyonlinepaySave->created        = $this->NOW();
				$GeoShiftingSuccessPayment->save($applyonlinepaySave);

				//Receipt  No Code
				$ReceiptMaster->save_receipt_master(decode($arr_request_data['merchant_param1']), $receipt_no, $payu_data['max'] + 1,7, $current_year);
				//Receipt  No Code

				$ApplicationPaymentRequest 		= TableRegistry::get('GeoShiftingApplicationPaymentRequest');
				$arrPayment 					= $ApplicationPaymentRequest->find('all',array('conditions'=>array('application_id'=>decode($payusave->udf1),'response_data IS NULL'),'order'=>array('id'=>'desc')))->first();
				if(!empty($arrPayment)) {
					$arrpay 					= array();
					$arrpay['application_id'] 	= decode($payusave->udf1);
					$arrpay['modified'] 		= $this->NOW();
					$arrpay['response_data']	= json_encode($arr_request_data);
					$ApplicationPaymentRequest->updateAll($arrpay,array('id'=>$arrPayment->id));
				}
				// $EInvoice 	= TableRegistry::get("EInvoice");
				// $EInvoice->getAccessToken(decode($payusave->udf1),'reapplication');
				$this->SuccessPaymentEmail(decode($payusave->udf1));
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
		if(PAYMENT_METHOD=='hdfc')
		{
			$payusave = $this->find('all')->where(['payment_id' => $arr_request_data['order_id']])->first();
		}
		
		if(empty($payusave)){
			$payusave = $this->newEntity();
		}
		if(PAYMENT_METHOD=='hdfc')
		{
			$payusave->payment_id       =  $arr_request_data['order_id'];
			$payusave->payment_status   =  strtolower($arr_request_data['order_status']);
			$payusave->udf1             =  $arr_request_data['merchant_param1'];
			$payusave->application_id   =  decode($arr_request_data['merchant_param1']);
			if($arr_request_data['trans_date']!='null' && !empty($arr_request_data['trans_date']))
			{
				$payusave->payment_date	=  date('Y-m-d H:i:s',strtotime($arr_request_data['trans_date']));
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
		$payusave->payment_data     	=  json_encode($arr_request_data);
		$payusave->created              = $this->NOW();
		if ($this->save($payusave))
		{
			$ApplicationPaymentRequest 		= TableRegistry::get('GeoShiftingApplicationPaymentRequest');
			$arrPayment 					= $ApplicationPaymentRequest->find('all',array('conditions'=>array('application_id'=>decode($payusave->udf1),'response_data IS NULL'),'order'=>array('id'=>'desc')))->first();
			if(!empty($arrPayment)) {
				$arrpay['application_id'] 	= decode($payusave->udf1);
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
	 * SuccessPaymentEmail  To Customer
	 * @param mixed What page to display
	 * @return void
	 */
	public function SuccessPaymentEmail($application_id=0)
	{
		$this->autoRender           = false;
		$Applications 				= TableRegistry::get('Applications');
		$GeoShiftingSuccessPayment 			= TableRegistry::get('GeoShiftingSuccessPayment');
		$payuTable 					= TableRegistry::get('Payumoney');
		$applyOnlinesData           = $Applications->viewApplication($application_id);
		$GEDA_APPLICATION_NO        = $applyOnlinesData->application_no;
		
		
	//	$to                         = !empty($CUSTOMER_EMAIL)?$CUSTOMER_EMAIL:"kalpak.yugtia@gmail.com";

		$payment_data               = $GeoShiftingSuccessPayment->find('all',array('conditions'=>array('application_id'=>$application_id),'order'=>array('id'=>'desc')))->first();

		$payment_details            = $this->find('all',array('conditions'=>array('id'=>$payment_data->payment_id)))->first();

		$EmailVars                  = array("GEDA_APPLICATION_NO"=>$GEDA_APPLICATION_NO,
											"RECEIPT_NO"=>$payment_details->receipt_no);

		$email                      = new Email('default');
		$subject                    = "[REG: E-Receipt ".$payment_details->receipt_no."] Geo Coordinate Payment Successfully Done.";
		$PaymentFileName            = $Applications->generateGeoShiftingPaymentReceiptPdf(encode($payment_data->payment_id),false,true);
		//->bcc("jayshree.tailor@yugtia.com")
				
		$email          = new Email('default');
		$email->profile('default');
		$email->viewVars($EmailVars);
		$message_send   = $email->template('geo_payment_receipt_mail', 'default')
				->emailFormat('html')
				->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
				->to(GEDA_PAYMENTRECIEPT_MAIL)
				->bcc('nehakpal786@gmail.com')
				->subject(Configure::read('EMAIL_ENV').$subject);
				if(!empty($PaymentFileName))
				{
					$message_send->addAttachments($PaymentFileName);
				}
		
				$message_send->send();
				if(!empty($PaymentFileName))
				{
					unlink($PaymentFileName);
				}
		return true;
	}
	public function GetGenerateReceiptNo($recipt_no=0,$date="")
	{
		$Month   = date("m",strtotime($date));
		$Year   = date("y",strtotime($date));
		$ChallanNo  = "ER/D/";
		if (intval($Month) >= 1 && intval($Month) <= 3) {
		$ChallanNo  .= ($Year-1)."-".date("y",strtotime($date))."/";
		} else {
		$ChallanNo  .= $Year."-".(date("y",strtotime($date))+1)."/";
		}
		$ChallanNo .= str_pad($recipt_no,4,"0",STR_PAD_LEFT);
		return $ChallanNo;
	} 
	
}
?>