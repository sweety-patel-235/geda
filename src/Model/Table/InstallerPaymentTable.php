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
class InstallerPaymentTable extends AppTable {
	/**
	 * The status of $name is universe
	 * Potential value are Class Name
	 * @var String
	 */
	public $Name 	= 'InstallerPayment';

	/**
	 * The status of $useTable is universe
	 * Potential value are Database Table name
	 * @var String
	 */
	public $useTable = 'installer_payment';
	public function initialize(array $config)
	{
		$this->table($this->useTable);
	}
	public function savedata_success($arr_request_data,$is_mobile=0)
	{
		if(PAYMENT_METHOD=='hdfc')
		{
			$payusave 	= $this->find('all')->where(['payment_id' => $arr_request_data['order_id']])->first();
		}
		$flagNew 	 	= 0;
		if(empty($payusave)){
			$payusave 	= $this->newEntity();
			$flagNew 	= 1;
		}
		$current_date 	= date('Y-m-d');
		$current_year 	= date('Y');
		if(PAYMENT_METHOD=='hdfc')
		{
			$payusave->payment_id       =  $arr_request_data['order_id'];
			$payusave->payment_status   =  strtolower($arr_request_data['order_status']);
			$payusave->udf1             =  $arr_request_data['merchant_param1'];
			$payusave->installer_id   	=  decode($arr_request_data['merchant_param1']);
			if($arr_request_data['trans_date']!='null' && !empty($arr_request_data['trans_date']))
			{
				$payusave->payment_date     =  date('Y-m-d H:i:s',strtotime(str_replace('/', '-', $arr_request_data['trans_date'])));
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
		if($flagNew == 1 || empty($payusave->receipt_no)){
			//Receipt  No Code
			$ReceiptMaster 				= TableRegistry::get('ReceiptMaster');
			$receiptTable_sel 			= $ReceiptMaster->find();
			$receiptTable_sel->hydrate(false);
			$payu_data 					= $receiptTable_sel->select(['max' => $receiptTable_sel->func()->max('max_serial_no')])->where(array('created_year' => $current_year))->first();
			//Receipt  No Code
			
			//$payu_data                      = $payuTable_sel->select(['max' => $payuTable_sel->func()->max('max_serial_no')])->where(array('created_year' => $current_year))->first();
			$receipt_no 					= GetGenerateReceiptNo($payu_data['max']+1,date('Y-m-d'));
			//$receipt_no 					= str_replace(array('ER/'), array('PR/'), $receipt_no);
			$payusave->receipt_no           = $receipt_no;
			$payusave->max_serial_no        = $payu_data['max']+1;
		}
		$payusave->payment_data         = json_encode($arr_request_data);
		$payusave->created              = $this->NOW();
		$payusave->created_year         = $current_year;
		if ($this->save($payusave)) 
		{
			if($payusave->udf1!='' && $payusave->payment_status=='success')
			{
				$Installers                 		= TableRegistry::get('Installers');
				$InstallerCategoryMapping           = TableRegistry::get('InstallerCategoryMapping');
				$InstallerPlans           			= TableRegistry::get('InstallerPlans');
				$InstallerSubscription           	= TableRegistry::get('InstallerSubscription');
				$InstallerCredendtials           	= TableRegistry::get('InstallerCredendtials');
				$InstallerActivationCodes          	= TableRegistry::get('InstallerActivationCodes');
				$Customers          				= TableRegistry::get('Customers');
				$Parameters          				= TableRegistry::get('Parameters');
				$Installers->updateAll(['payment_status' => '1','modified'=>$this->NOW()], ['id' => decode($payusave->udf1)]);
				$InstallerSuccessPayment            = TableRegistry::get('InstallerSuccessPayment');
				$InstallerPaymentSave              	= $InstallerSuccessPayment->newEntity();
				$InstallerPaymentSave->installer_id = decode($payusave->udf1);
				$InstallerPaymentSave->payment_id   = $payusave->id;
				$InstallerPaymentSave->payment_for 	= 1;
				$InstallerPaymentSave->payment_dt 	= $payusave->payment_date;
				$InstallerPaymentSave->created 		= $this->NOW();
				$InstallerSuccessPayment->save($InstallerPaymentSave);

				//Receipt  No Code
				$ReceiptMaster->save_receipt_master(decode($payusave->udf1), $receipt_no, $payu_data['max'] + 1, 4, $current_year);
				//Receipt  No Code

				$InstallerCategoryMappingEntity 				= $InstallerCategoryMapping->newEntity();
				$InstallerCategoryMappingEntity->installer_id 	= $InstallerPaymentSave->installer_id;
				$InstallerCategoryMappingEntity->category_id 	= 3;
				$InstallerCategoryMappingEntity->allowed_bands	= '["1","2","3","4"]';
				$InstallerCategoryMappingEntity->short_name 	= '';

				$InstallerCategoryMapping->save($InstallerCategoryMappingEntity);
				$InstallerDetails 				= $Installers->find('all',array('conditions'=>array('id'=>$InstallerPaymentSave->installer_id)))->first();
				$arrName 						= explode(" ",$InstallerDetails->contact_person);
			    $RandomPassword                 = strtolower($arrName[0]).'@2020';
				$arrEmail                       = explode(",",$InstallerDetails->email);
				$CustomerEmail                  = trim($arrEmail[0]);
				$customersEntity                = $Customers->newEntity();
				$customersEntity->mobile        = $InstallerDetails->mobile;
				$customersEntity->email         = $CustomerEmail;
				$customersEntity->name          = $InstallerDetails->contact_person;
				$customersEntity->password      = Security::hash(Configure::read('Security.salt') . $RandomPassword);
				$customersEntity->status        = $Customers->STATUS_INACTIVE;
				$customersEntity->customer_type = "installer";
				$customersEntity->state         = 4;
				$customersEntity->created       = $this->NOW();
				$customercnt                    = $Customers->find('all', array('conditions'=>array('email'=>$CustomerEmail)))->count();
				$IsInstallerCreated             = $Customers->find('all', array('conditions'=>array('installer_id'=>$InstallerDetails->id)))->count();

				if ($Customers->save($customersEntity)) 
				{
					$insplanData                                    = $InstallerPlans->get($InstallerPlans->DEFAULT_PLAN_ID);
					$InstallerSubscriptionEntity                    = $InstallerSubscription->newEntity();
					$InstallerSubscriptionEntity->payment_status    = '';
					$InstallerSubscriptionEntity->installer_id      = $InstallerPaymentSave->installer_id;
					$InstallerSubscriptionEntity->coupen_code       = '';
					$InstallerSubscriptionEntity->transaction_id    = '';
					$InstallerSubscriptionEntity->created           = $this->NOW();
					$InstallerSubscriptionEntity->modified          = $this->NOW();
					$InstallerSubscriptionEntity->payment_gateway   = '';
					$InstallerSubscriptionEntity->comment           = '100% Discount';
					$InstallerSubscriptionEntity->payment_data      = '';
					$InstallerSubscriptionEntity->amount            = '0';
					$InstallerSubscriptionEntity->coupen_id         = '0';
					$InstallerSubscriptionEntity->is_flat           = '0';
					$InstallerSubscriptionEntity->plan_name         = $insplanData->plan_name;
					$InstallerSubscriptionEntity->plan_price        = $insplanData->plan_price;
					$InstallerSubscriptionEntity->plan_id           = $InstallerPlans->DEFAULT_PLAN_ID;
					$InstallerSubscriptionEntity->user_limit        = $insplanData->user_limit;
					$InstallerSubscriptionEntity->start_date        = date('Y-m-d');
					$InstallerSubscriptionEntity->expire_date       = date('Y-m-d',strtotime("+ 30 days"));
					$InstallerSubscriptionEntity->status            = '1';
					$InstallerSubscriptionEntity->created_by        = $customersEntity->id;
					$InstallerSubscriptionEntity->modified_by       = $customersEntity->id;
					$InstallerSubscription->save($InstallerSubscriptionEntity);
					$insCodeArr = array();
					for ($i=0; $i < $insplanData->user_limit; $i++) {
						$activation_codes = $Installers->generateInstallerActivationCodes();
						$insCodeArr[]                                               = $activation_codes;
						$insCodedata['InstallerActivationCodes']['installer_id']    = $InstallerPaymentSave->installer_id;
						$insCodedata['InstallerActivationCodes']['activation_code'] = $activation_codes;
						$insCodedata['InstallerActivationCodes']['start_date']      = date('Y-m-d');
						$insCodedata['InstallerActivationCodes']['expire_date']     = date('Y-m-d',strtotime("+ 30 days"));
						$insCodeEntity = $InstallerActivationCodes->newEntity($insCodedata);
						$InstallerActivationCodes->save($insCodeEntity);
					}
					$Customers->updateAll(['user_role'=>$Parameters->admin_role,'default_admin'=>1,'installer_id' => $InstallerPaymentSave->installer_id,'modified' => $this->NOW()], ['id' => $customersEntity->id]);

					$PasswordInfo['InstallerCredendtials']['installer_id']  = $InstallerPaymentSave->installer_id;
					$PasswordInfo['InstallerCredendtials']['password']      = $RandomPassword;
					$InstallerCredendtialsEnt 								= $InstallerCredendtials->newEntity($PasswordInfo);
					$InstallerCredendtials->save($InstallerCredendtialsEnt);
				}
				$InstallerPaymentRequest 		= TableRegistry::get('InstallerPaymentRequest');
				$arrPayment 					= $InstallerPaymentRequest->find('all',array('conditions'=>array('installer_id'=>decode($payusave->udf1),'response_data IS NULL'),'order'=>array('id'=>'desc')))->first();
				if(!empty($arrPayment)) {
					$arrpay['installer_id'] 	= decode($payusave->udf1);
					
					$arrpay['modified'] 		= $this->NOW();
					$arrpay['response_data']	= json_encode($arr_request_data);
					$InstallerPaymentRequest->updateAll($arrpay,array('id'=>$arrPayment->id));
				}
				$EInvoice 	= TableRegistry::get("EInvoice");
				$EInvoice->getAccessToken(decode($payusave->udf1),'installer');
				$this->SuccessPaymentEmailToInstaller(decode($payusave->udf1));
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
			$InstallerPaymentRequest 		= TableRegistry::get('InstallerPaymentRequest');
			$arrPayment 					= $InstallerPaymentRequest->find('all',array('conditions'=>array('installer_id'=>decode($payusave->udf1),'response_data IS NULL'),'order'=>array('id'=>'desc')))->first();
			if(!empty($arrPayment)) {
				$arrpay['installer_id'] 	= decode($payusave->udf1);
				$arrpay['modified'] 		= $this->NOW();
				$arrpay['response_data']	= json_encode($arr_request_data);
				$InstallerPaymentRequest->updateAll($arrpay,array('id'=>$arrPayment->id));
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
	 * SuccessPaymentEmailToInstaller To installer
	 * @param mixed What page to display
	 * @return void
	 */
	public function SuccessPaymentEmailToInstaller($installer_id=0)
	{
		$this->autoRender           = false;
		$Installers 				= TableRegistry::get('Installers');
		$InstallerPayment 			= TableRegistry::get('InstallerPayment');
		$InstallerSuccessPayment 	= TableRegistry::get('InstallerSuccessPayment');
		$InstallersData           	= $Installers->find('all',array('conditions'=>array('id'=>$installer_id)))->first();

		$to                         = !empty($InstallersData->email)?$InstallersData->email:"jayshree.tailor@yugtia.com";

		$payment_data               = $InstallerSuccessPayment->find('all',array('conditions'=>array('installer_id'=>$installer_id),'order'=>array('id'=>'desc')))->first();

		$payment_details            = $InstallerPayment->find('all',array('conditions'=>array('id'=>$payment_data->payment_id)))->first();

		$EmailVars                  = array("RECEIPT_NO"		=>$payment_details->receipt_no,
											"INSTALLER_NAME"	=> $InstallersData->installer_name);

		$email                      = new Email('default');
		$subject                    = "[REG: E-Receipt ".$payment_details->receipt_no."] Payment Successfully Done.";
		$PaymentFileName             = $this->generateInstallerReceiptPdf(encode($installer_id),false,true);
		
		$email          = new Email('default');
		$email->profile('default');
		$email->viewVars($EmailVars);
		$message_send   = $email->template('payment_receipt_mail_installer', 'default')
				->emailFormat('html')
				->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
				->to($to)
				->bcc(array("jayshree.tailor@yugtia.com",GEDA_PAYMENTRECIEPT_MAIL,"pulkitdhingra@gmail.com"))
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