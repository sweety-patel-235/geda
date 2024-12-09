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
class DeveloperPermissionPaymentTable extends AppTable
{
	/**
	 * The status of $name is universe
	 * Potential value are Class Name
	 * @var String
	 */
	public $Name 	= 'DeveloperPermissionPayment';

	/**
	 * The status of $useTable is universe
	 * Potential value are Database Table name
	 * @var String
	 */
	public $useTable = 'developer_permission_payment';
	public function initialize(array $config)
	{
		$this->table($this->useTable);
	}
	public function savedata_success($arr_request_data, $is_mobile = 0)
	{
		$ApplicationStages = TableRegistry::get('ApplicationStages');
		if (PAYMENT_METHOD == 'hdfc') {
			$payusave 	= $this->find('all')->where(['payment_id' => $arr_request_data['order_id']])->first();
		}
		$flagNew 	 	= 0;
		if (empty($payusave)) {
			$payusave 	= $this->newEntity();
			$flagNew 	= 1;
		}
		$current_date 	= date('Y-m-d');
		$current_year 	= date('Y');
		if (PAYMENT_METHOD == 'hdfc') {
			$payusave->payment_id       	=  $arr_request_data['order_id'];
			$payusave->payment_status   	=  strtolower($arr_request_data['order_status']);
			$payusave->udf1             	=  $arr_request_data['merchant_param1'];
			$payusave->dev_per_app_id   	=  decode($arr_request_data['merchant_param2']);
			$payusave->application_type   	=  $arr_request_data['merchant_param3'];
			if ($arr_request_data['trans_date'] != 'null' && !empty($arr_request_data['trans_date'])) {
				$payusave->payment_date =  date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $arr_request_data['trans_date'])));
			}
			$payusave->transaction_id   =  $arr_request_data['tracking_id'];
			//$payusave->receipt_no       =  GetGenerateReceiptNo('',$current_date);
		}

		$payuTable_sel                  = $this->find();
		$payuTable_sel->hydrate(false);
		$current_year                   = date('Y');
		if (intval(date('m')) >= 1 && intval(date('m')) <= 3) {
			$current_year               = date('Y') - 1;
		}
		if ($flagNew == 1 || empty($payusave->receipt_no)) {
			
			//Receipt  No Code
			$ReceiptMaster 				= TableRegistry::get('ReceiptMaster');
			$receiptTable_sel              = $ReceiptMaster->find();
			$receiptTable_sel->hydrate(false);
			$payu_data 					= $receiptTable_sel->select(['max' => $receiptTable_sel->func()->max('max_serial_no')])->where(array('created_year' => $current_year))->first();
			//Receipt  No Code

			//$payu_data 					= $payuTable_sel->select(['max' => $payuTable_sel->func()->max('max_serial_no')])->where(array('created_year' => $current_year))->first();
			$receipt_no 				= $this->GetGenerateReceiptNo($payu_data['max'] + 1, date('Y-m-d'));
			$payusave->receipt_no 		= $receipt_no;
			$payusave->max_serial_no 	= $payu_data['max'] + 1;
		}
		$payusave->payment_data 		= json_encode($arr_request_data);
		$payusave->created 				= $this->NOW();
		$payusave->created_year 		= $current_year;

		if ($this->save($payusave)) {
			if ($payusave->udf1 != '' && $payusave->dev_per_app_id != '' && $payusave->payment_status == 'success') {
				
				if (isset($payusave->application_type) && $payusave->application_type == 2) {
					$OpenAccessApplicationDeveloperPermission 	= TableRegistry::get('OpenAccessApplicationDeveloperPermission');
					$OpenAccessApplicationDeveloperPermission->updateAll(['payment_status' => '1', 'modified' => $this->NOW()], ['id' => $payusave->dev_per_app_id]);
					
					$ApplicationStages->saveStatus(decode($payusave->udf1),$ApplicationStages->DEVELOPER_APPLICATION_SUBMITTED,'','');
				}
				if (isset($payusave->application_type) && $payusave->application_type == 3) {
					$WindApplicationDeveloperPermission 	= TableRegistry::get('WindApplicationDeveloperPermission');
					$WindApplicationDeveloperPermission->updateAll(['payment_status' => '1', 'modified' => $this->NOW()], ['id' => $payusave->dev_per_app_id]);
					$ApplicationStages->saveDevAppStatus(decode($payusave->udf1),$ApplicationStages->DEVELOPER_APPLICATION_SUBMITTED,'',$payusave->dev_per_app_id);
				}
				if (isset($payusave->application_type) && $payusave->application_type == 4) {
					$WindApplicationDeveloperPermission 	= TableRegistry::get('WindApplicationDeveloperPermission');
					$WindApplicationDeveloperPermission->updateAll(['payment_status' => '1', 'modified' => $this->NOW()], ['id' => $payusave->dev_per_app_id]);
					$ApplicationStages->saveDevAppStatus(decode($payusave->udf1),$ApplicationStages->DEVELOPER_APPLICATION_SUBMITTED,'',$payusave->dev_per_app_id);
				}
				//Receipt  No Code
				$ReceiptMaster->save_receipt_master(decode($arr_request_data['merchant_param1']), $receipt_no, $payu_data['max'] + 1, 2, $current_year);
				//Receipt  No Code
				$DeveloperPermissionSuccessPayment 		= TableRegistry::get('DeveloperPermissionSuccessPayment');
				$applyonlinepaySave 					= $DeveloperPermissionSuccessPayment->newEntity();
				$applyonlinepaySave->udf1			 	= $payusave->udf1;
				$applyonlinepaySave->dev_per_app_id 	= $payusave->dev_per_app_id;
				$applyonlinepaySave->application_type 	= $payusave->application_type;
				$applyonlinepaySave->payment_id 		= $payusave->id;
				$applyonlinepaySave->payment_for		= 1;
				$applyonlinepaySave->payment_dt 		= $payusave->payment_date;
				$applyonlinepaySave->created 			= $this->NOW();

				$successPayment = $DeveloperPermissionSuccessPayment->save($applyonlinepaySave);

				$DeveloperPermissionPaymentRequest 	= TableRegistry::get('DeveloperPermissionPaymentRequest');
				$arrPayment 						= $DeveloperPermissionPaymentRequest->find('all', array('conditions' => array('dev_per_app_id' => $payusave->dev_per_app_id, 'application_type' => $payusave->application_type, 'OR' => array('response_data IS NULL', 'response_data' => '')), 'order' => array('id' => 'desc')))->first();
				if (!empty($arrPayment)) {
					$arrpay 					= array();
					$arrpay['dev_per_app_id'] 	= $payusave->dev_per_app_id;
					$arrpay['application_type'] = $payusave->application_type;
					$arrpay['modified'] 		= $this->NOW();
					$arrpay['response_data']	= json_encode($arr_request_data);
					$DeveloperPermissionPaymentRequest->updateAll($arrpay, array('id' => $arrPayment->id));
				}

				$this->SuccessPaymentEmail($payusave->dev_per_app_id,$payusave->application_type);

			}

			if ($is_mobile == 0) {
				if ($payusave->udf1 != '') {
					return 1;
				} else {
					return 0;
				}
			} else {
				return 1;
			}
		}
	}

	public function savedata_failure($arr_request_data, $is_mobile = 0)
	{
		if (PAYMENT_METHOD == 'hdfc') {
			$payusave = $this->find('all')->where(['payment_id' => $arr_request_data['order_id']])->first();
		}

		if (empty($payusave)) {
			$payusave = $this->newEntity();
		}
		if (PAYMENT_METHOD == 'hdfc') {
			$payusave->payment_id       	=  $arr_request_data['order_id'];
			$payusave->payment_status   	=  strtolower($arr_request_data['order_status']);
			$payusave->udf1             	=  $arr_request_data['merchant_param1'];
			$payusave->dev_per_app_id   	=  decode($arr_request_data['merchant_param2']);
			$payusave->application_type   	=  $arr_request_data['merchant_param3'];
			if ($arr_request_data['trans_date'] != 'null' && !empty($arr_request_data['trans_date'])) {
				$payusave->payment_date	=  date('Y-m-d H:i:s', strtotime($arr_request_data['trans_date']));
			}
			$payusave->transaction_id   =  $arr_request_data['tracking_id'];
		}

		$payusave->payment_data     	=  json_encode($arr_request_data);
		$payusave->created              = $this->NOW();
		if ($this->save($payusave)) {
			$DeveloperPermissionPaymentRequest 		= TableRegistry::get('DeveloperPermissionPaymentRequest');
			$arrPayment 					= $DeveloperPermissionPaymentRequest->find('all', array('conditions' => array('dev_per_app_id' => $payusave->dev_per_app_id, 'application_type' => $payusave->application_type, 'response_data IS NULL'), 'order' => array('id' => 'desc')))->first();

			if (!empty($arrPayment)) {

				$arrpay 					= array();
				$arrpay['dev_per_app_id'] 	= $payusave->dev_per_app_id;
				$arrpay['application_type'] = $payusave->application_type;
				$arrpay['modified'] 		= $this->NOW();
				$arrpay['response_data']	= json_encode($arr_request_data);
				$DeveloperPermissionPaymentRequest->updateAll($arrpay, array('id' => $arrPayment->id));
			}
			if ($is_mobile == 0) {
				if ($payusave->udf1 != '') {
					return 1;
				} else {
					return 0;
				}
			} else {
				return 1;
			}
		}
	}
	/*
	 * SuccessPaymentEmail  To Customer
	 * @param mixed What page to display
	 * @return void
	 */
	public function SuccessPaymentEmail($dev_per_app_id = 0,$appType=0)
	{
		
		$this->autoRender 							= false;
		$Developers 								= TableRegistry::get('Developers');
		$OpenAccessApplicationDeveloperPermission 	= TableRegistry::get('OpenAccessApplicationDeveloperPermission');
		$WindApplicationDeveloperPermission			= TableRegistry::get('WindApplicationDeveloperPermission');
		$DeveloperPermissionSuccessPayment 			= TableRegistry::get('DeveloperPermissionSuccessPayment');
		$payuTable 									= TableRegistry::get('Payumoney');
		
		if ($appType == 2) {
			$DevPerData  		= $OpenAccessApplicationDeveloperPermission->find('all', array('conditions' => array('id' => $dev_per_app_id)))->first();						
		} else {
			$DevPerData			= $WindApplicationDeveloperPermission->find('all', array('conditions' => array('id' => $dev_per_app_id)))->first();			
		}
		
		$InstallersData  					= $Developers->find('all', array('conditions' => array('id' => $DevPerData->installer_id)))->first();
		$payment_data               		= $DeveloperPermissionSuccessPayment->find('all', array('conditions' => array('dev_per_app_id' => $dev_per_app_id, 'application_type' => $appType), 'order' => array('id' => 'desc')))->first();
		$payment_details            		= $this->find('all', array('conditions' => array('id' => $payment_data->payment_id)))->first();

		$EmailVars                  		= array(
			"INSTALLER_NAME" => $InstallersData->installer_name,
			"RECEIPT_NO"	=> $payment_details->receipt_no
		);
		
		$email                      		= new Email('default');
		$subject                    		= "[REG: E-Receipt " . $payment_details->receipt_no . "] Payment Successfully Done.";
		
		$PaymentFileName            		= $OpenAccessApplicationDeveloperPermission->generateDeveloperPermissionReceiptPdf(encode($dev_per_app_id), $appType,false, true);
		

		$email          					= new Email('default');
		$email->profile('default');
		$email->viewVars($EmailVars);
		$message_send   = $email->template('payment_receipt_mail_developer', 'default')
			->emailFormat('html')
			->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
			// ->to(GEDA_PAYMENTRECIEPT_MAIL)
			// ->bcc('pulkitdhingra@gmail.com')
			->to('ami.joshi@ahasolar.in')
			->bcc('vishal.sutariya@ahasolar.in')
			->subject(Configure::read('EMAIL_ENV') . $subject);
		if (!empty($PaymentFileName)) {
			$message_send->addAttachments($PaymentFileName);
		}

		$message_send->send();
		if (!empty($PaymentFileName)) {
			unlink($PaymentFileName);
		}
		return true;
	}

	public function GetGenerateReceiptNo($recipt_no = 0, $date = "")
	{
		$Month   = date("m", strtotime($date));
		$Year   = date("y", strtotime($date));
		$ChallanNo  = "ER/D/";
		if (intval($Month) >= 1 && intval($Month) <= 3) {
			$ChallanNo  .= ($Year - 1) . "-" . date("y", strtotime($date)) . "/";
		} else {
			$ChallanNo  .= $Year . "-" . (date("y", strtotime($date)) + 1) . "/";
		}
		$ChallanNo .= str_pad($recipt_no, 4, "0", STR_PAD_LEFT);
		return $ChallanNo;
	}
}
