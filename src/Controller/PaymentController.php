<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

class PaymentController extends AppController
{
	// The other component your component uses
    public $components 		= ['Atom'];
    public $uses 			= ['Payment'];
    public $id          	= 0;
    public $uniqueid		= '';
    public $company_id		= 0;
    public $customer_id		= 0;
    public $txt_status		= '';
    public $created			= '0000-00-00 00:00:00';
    public $updated			= '0000-00-00 00:00:00';
    public $amount			= 0;
    public $service_charge 	= 0;
    public $currency 		= 'INR';
    public $return_url		= '';
    public $request_str		= '';
    public $trans_id 		= '';
    public $token 			= '';
    public $error			= '';

    public $payment_response = array();
    public $partner_request_id = 0;

	public function index()
	{
		$this->layout = 'payment';
	}

	private function SetTokenAndTransactionID()
	{
		# code...
		$this->token 		= (isset($_GET['token']))?trim($_GET['token']):'';
		$this->trans_id 	= (isset($_GET['txn_id']))?trim($_GET['txn_id']):'';
	}

	public function process_payment()
	{
		//die('first die');
		print_r($this->request->data);
		// This should be dynamic values.
		/*$company_id 	= 5;
		$customer_id 	= 21;
		$amount 		= 234;
		$service_charge = 10.20;
		$currency 		= 'INR';*/
		// Dynamic values end

		$this->SetTokenAndTransactionID();
		if($this->RetrivePartnerRequestAndInitializePaymentData($this->token, $this->trans_id)) {

			if($payment_id = $this->SavePayment()) {
			
				$merchant_txn_id		= $payment_id;
				$customer_account_id 	= $this->customer_id;
				$customer_info			= array();

				$customer_info['email']	= 'jitu.rathodld.ld@gmail.com';
				$customer_info['mobile'] = '9825096687';

				if($this->Atom->AtomFirstPaymentRequest($payment_id, $this->amount, $this->service_charge, $merchant_txn_id, $customer_account_id, $this->mdd, $customer_info)) {

					$this->UpdatePaymentTxnStatus($payment_id, 1);
					//$this->UpdateAtomResponse();

					$this->Atom->RedirectOnAtomPaymentPage();
				}
				
			} else {
				die("Problem in generating payment record in database");
			}	
		} else {
			die("Bad Request");
		}			
	}

	public function index_new()
	{
		$this->SetPartnerCompanyRequestData();
		$this->company_id 	= 1;
		$this->customer_id 	= 20;
		$this->amount		= '187.83';
		$this->return_url	= 'http://127.0.0.2/payment/partner_return_url';
		$this->company_txn_id= '6837';

		if($this->ValidatePartnerRequest()) {
			if($partner_request_id = $this->ProcessAndSaveCompanyPaymentRequest()) {
				echo json_encode($this->GenerateResponse());	
			} else {
				$response = array('status' => 'F');
				echo json_encode($response);
			}
		} else {
			$response = array('status' => 'F', 'error' => $this->error);
			echo json_encode($response);
		}
		echo "<br>";
		echo "http://127.0.0.2/payment/process_payment?txn_id=$this->trans_id&token=$this->token";
		die();
	}

	public function partner_return_url()
	{
		_d($_POST);
		die;
	}

	private function ValidatePartnerRequest()
	{
		# code...
		if(empty($this->company_id)) {
			$this->error = 'Not valid company request.';
			return false;
		}

		if(empty($this->customer_id)) {
			$this->error = 'Not valid customer request.';
			return false;
		}

		if(empty($this->amount) || !isCurrency($this->amount) || $this->amount<100) {
			$this->error = 'Not valid payment amount.';
			return false;
		}
		return true;
	}

	private function GenerateResponse()
	{
		return array('status' => 'OK', 'txn_id' => $this->trans_id, 'token' => $this->token);
	}

	private function SetPartnerCompanyRequestData()
	{
		# code...
		$this->company_id 	= isset($_POST['company_id'])?$_POST['company_id']:0;
		$this->customer_id 	= isset($_POST['customer_id'])?$_POST['customer_id']:0;
		$this->company_txn_id= isset($_POST['txn_id'])?$_POST['txn_id']:0;
		//$this->request_str 	= isset($_POST['request_str'])?$_POST['request_str']:'';
		$this->return_url 	= isset($_POST['ru'])?$_POST['ru']:0;
		$this->amount 		= isset($_POST['amount'])?$_POST['amount']:0;
		$this->service_charge= isset($_POST['service_charge'])?$_POST['service_charge']:0;
		$this->currency 	= isset($_POST['curr'])?$_POST['curr']:'INR';
		$this->request_str 	= serialize($_POST);
		$this->created 		= date('Y-m-d H:i:s');
		$this->token 		= md5(uniqid(mt_rand(), true));
		if(!empty($this->company_id))
			$this->trans_id 	= uniqid($this->company_id.'j');
		else
			$this->trans_id 	= uniqid();
	}

	private function ProcessAndSaveCompanyPaymentRequest()
	{
		// Save Data into payments table START.
		$PartnerRequestsTable 	= TableRegistry::get('PartnerRequests');		
		$PartnerRequest 		= $PartnerRequestsTable->newEntity();

		$PartnerRequest->company_id 	= $this->company_id;
		$PartnerRequest->customer_id 	= $this->customer_id;
		$PartnerRequest->company_txn_id = $this->company_txn_id;
		$PartnerRequest->request_str 	= $this->request_str;
		$PartnerRequest->return_url 	= $this->return_url;
		$PartnerRequest->token 			= $this->token;
		$PartnerRequest->trans_id 		= $this->trans_id;
		$PartnerRequest->amount 		= $this->amount;
		$PartnerRequest->service_charge	= $this->service_charge;
		$PartnerRequest->currency 		= $this->currency;
		$PartnerRequest->created 		= $this->created;

		if ($PartnerRequestsTable->save($PartnerRequest)) {
			return $PartnerRequest->id;
		}
		return 0;
	}

	public function process_atom_response()
	{
		$this->Atom->ProcessAndSavePaymentResponse();
		$payment_id 	= $this->Atom->payment_id;

		if(!empty($payment_id)) {

			$payment_status	= strtolower($this->Atom->atom_f_code);			

			if($payment_status == $this->Atom->ATOM_PAYMENT_SUCCESS_F_CODE) {
				$this->UpdatePaymentStatus($this->Atom->payment_id,1);
			} elseif ($payment_status == $this->Atom->ATOM_PAYMENT_FAIL_F_CODE) {
				# code...
				$this->UpdatePaymentStatus($this->Atom->payment_id,0);
			} else {
				mail('jitendra@yugtia.com','Unknown payment status code', 'Payment Status ===>'.$payment_status);
			}
			$this->SendResponseToPartnerSites($payment_id);

		} else {
			mail('jitendra@yugtia.com','Unknown payment ID', 'Payment ID ===>'.$payment_id);
		}

		$this->set('partner_request_id', $this->partner_request_id);
		$this->set('payment_status', $payment_status);
		$this->set('company_txn_id', $this->company_txn_id);
		$this->set('ru', $this->return_url);	
	}

	private function gen_redirect_and_form($addr, $page, $msg, $host="")
	{
		
	}

	public function redirect_url()
	{
		$path = '/payment/partner_return_url';
		$host = '127.0.0.2';
		$query = "data1=value1&data2=value2";
		$query = urlencode($query);

		header("POST $path HTTP/1.1\r\n" );
	    header("Host: $host\r\n" );
	    header("Content-type: application/x-www-form-urlencoded\r\n" );
	    header("Content-length: " . strlen($query) . "\r\n" );
	    header("Connection: close\r\n\r\n" );
	    header($query);
	    die;
	 }


	private function sendHttpRequest($host, $path, $query)
	{
	    header("POST $path HTTP/1.1\r\n" );
	    header("Host: $host\r\n" );
	    header("Content-type: application/x-www-form-urlencoded\r\n" );
	    header("Content-length: " . strlen($query) . "\r\n" );
	    header("Connection: close\r\n\r\n" );
	    header($query);
	    die;
	}

	private function SendResponseToPartnerSites($payment_id)
	{
		# code...
		$paymentsTable = TableRegistry::get('Payments');
		$payment = $paymentsTable->get($payment_id);

		if($payment) {

			$PartnerRequestsTable 	= TableRegistry::get('PartnerRequests');
			$partner_request 		= $PartnerRequestsTable->get($payment->partner_request_id);
			//_d($partner_request);
			if($partner_request) {
				$this->return_url 		= $partner_request->return_url;
				$this->company_txn_id 	= $partner_request->company_txn_id;
				$this->partner_request_id = $partner_request->id;
			}
		}
	}

	private function UpdatePaymentStatus($payment_id, $status)
	{
		$paymentsTable = TableRegistry::get('Payments');
		$payment = $paymentsTable->get($payment_id); // article with id 12

		$payment->status = $status;
		$paymentsTable->save($payment);
	}

	private function UpdatePaymentTxnStatus($payment_id, $txt_status)
	{
		$paymentsTable = TableRegistry::get('Payments');
		$payment = $paymentsTable->get($payment_id); // article with id 12

		$payment->txt_status = $txt_status;
		$paymentsTable->save($payment);
	}

	private function SavePayment()
	{
		// Save Data into payments table START.
		$paymentsTable = TableRegistry::get('Payments');
		
		$payment = $paymentsTable->newEntity();

		$payment->uniqueid 		= $this->uniqueid;
		$payment->company_id 	= $this->company_id;
		$payment->customer_id 	= $this->customer_id;
		$payment->partner_request_id = $this->partner_request_id;		
		$payment->txt_status 	= $this->txt_status;
		$payment->created 		= $this->created;
		$payment->updated 		= $this->updated;

		if ($paymentsTable->save($payment)) {

		   	$paymentDetailTable = TableRegistry::get('PaymentDetails');
			$paymentDetail = $paymentDetailTable->newEntity();

			$paymentDetail->id 				= $payment->id;
			$paymentDetail->amount 			= $this->amount;
			$paymentDetail->currency 		= $this->currency;
			$paymentDetail->service_charge 	= $this->service_charge;
			$paymentDetail->updated 		= $this->updated;

			if(!$paymentDetailTable->save($paymentDetail)) {
				mail('jitendra@yugtia.com', 'Payment Detail Save Fail', $payment->id);
			}
			return $payment->id;
		}
		// Save Data into payments table END.
		return 0;
	}

	private function RetrivePartnerRequestAndInitializePaymentData($token, $trans_id)
	{
		# code...
		if(empty($token) || empty($trans_id)) {
			return false;
		}

		$PartnerRequestsTable = TableRegistry::get('PartnerRequests');
        $PartnerRequest = $PartnerRequestsTable->find('all')->where(['PartnerRequests.trans_id' => $trans_id, 'PartnerRequests.token' => $token])->first();
        if($PartnerRequest) {

        	$this->uniqueid 	= uniqid();
			$this->company_id	= $PartnerRequest->company_id;
			$this->customer_id 	= $PartnerRequest->customer_id;
			$this->txt_status 	= -1;
			$this->amount 		= $PartnerRequest->amount;
			$this->service_charge = $PartnerRequest->service_charge;
			$this->currency		= $PartnerRequest->currency;
			$this->partner_request_id = $PartnerRequest->id;
			$this->created 		= date('Y-m-d H:i:s');
			$this->updated 		= date('Y-m-d H:i:s');

			//Removing token once it will find in our database.
			//$PartnerRequest->token = '';
			//$PartnerRequestsTable->save($PartnerRequest);

			return true;
        }
        return false;
	}

	public function initializePayment($company_id, $customer_id, $amount, $service_charge, $currency = 'INR')
	{
		$this->uniqueid 	= uniqid();
		$this->company_id	= $company_id;
		$this->customer_id 	= $customer_id;
		$this->txt_status 	= -1;
		$this->amount 		= $amount;
		$this->service_charge = $service_charge;
		$this->currency		= $currency;
		$this->created 		= date('Y-m-d H:i:s');
		$this->updated 		= date('Y-m-d H:i:s');
	}
}