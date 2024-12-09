<?php
/**
 * Short description for file
 * This Model use for products. It extends AppModel Class
 * @author Kalpak Prajapati
 * @category  Class File
 * @Desc      Provides infomration related to products
 * @author    Kalpak Pajapati
 * @version   
 * @since     2015-10-26
 */
 namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

use App\Model\Table\Entity;
use Cake\Validation\Validator;
class PaymentRequestTable extends AppTable {
	/**
	 * The status of $name is universe
	 * Potential value are Class Name
	 * @public String
	 */
	public $name = "PaymentRequest";
	
	/**
	* The status of $useDbConfig is universe
	* Potential value are Database Connection String
	* @var String
	*/
	public $useDbConfig	= "default";

	/**
	 * The status of $useTable is universe
	 * Potential value are Class Name
	 * @public String
	 */
	public $debug			= false;
	public $method			= 'POST';
	public $api_response	= array();
	public $error_message	= "";
	
	public function initialize(array $config)
    {
        $this->table('customer_payment_requests');
    }

	/**
	 * generateRequestID : geneate unique request id of payment request
	 * Behaviour : Public
	 * @param  
	 * @throws 
	 * @return Retuns the Random generated request id.
	 */
	public function generateRequestID($limit=32)
	{
		return strtoupper(substr(md5(uniqid(mt_rand())),0,$limit));
	}

	/**
	 * GetPaymentURL : get payment url
	 * Behaviour : Public
	 * @param  
	 * @throws 
	 * @return Retuns the Random generated request id.
	 */
	public function GetPaymentURL($customer_id,$email,$mobile,$amount)
	{
		$arrResult				= array();
		$api_name				= 'payment/initiate_payment';
		$data['company_id']		= COMPANY_ID;
		$data['customer_id']	= $customer_id;
		$data['amount']			= $amount;
		$data['email']			= $email;
		$data['mobile']			= $mobile;
		$data['ru']				= PAYMENT_RETURN_URL;
		$data['txn_id']			= $this->generateRequestID();
		$arrResult				= $this->ApiCall($api_name, $data);
		if(isset($arrResult->status) && strtolower($arrResult->status) == 'ok') {
			$this->SavePaymentRequest($data);
			return true;
		} else {
			$this->error_message = (isset($this->api_response->error)?$this->api_response->error:'Something went wrong during payment process. Please try again.');
			$this->SavePaymentRequest($data);
			return false;
		}
	}

	/**
	 * getPaymentRequestDetails : get payment url
	 * Behaviour : Public
	 * @param  string $txn_id
	 * @throws 
	 * @return Retuns payment details.
	 */
	public function getPaymentRequestDetails($txn_id)
	{
		$arrResult				= array();
		$api_name				= 'payment/GetTransactionDetail';
		$data['txn_id']			= $txn_id;
		$data['company_id']		= COMPANY_ID;
		$arrResult				= $this->ApiCall($api_name, $data);
		$this->debug 			= true;
		if(isset($arrResult->status) && strtolower($arrResult->status) == 'ok') {
			return true;
		} else {
			$this->error_message = "Invalid transaction request details. Please try again.";
			return false;
		}
	}

	/**
	 * getTransactionPageUrl
	 * Behaviour : Public
	 * @param
	 * @throws 
	 * @return Retuns payment page url.
	 */
	public function getTransactionPageUrl()
	{
		return (isset($this->api_response->redirect_url)?$this->api_response->redirect_url:'error');
	}

	/**
	 * SavePaymentRequest : get payment url
	 * Behaviour : Public
	 * @param  
	 * @throws 
	 * @return 
	 */
	public function SavePaymentRequest($data)
	{
		
		$PaymentRequest['customer_id']	= $data['customer_id'];
		$PaymentRequest['request_id']		= $data['txn_id'];
		$PaymentRequest['amount']			= $data['amount'];
		$PaymentRequest['status']			= ((isset($this->api_response->type) && $this->api_response->type == 'ok')?1:0);
		$PaymentRequest['failed_reason']	= (isset($this->api_response->error)?$this->api_response->error:'');
		$PaymentRequest['txn_id']			= (isset($this->api_response->txn_id)?$this->api_response->txn_id:'');
		$PaymentRequest['token']			= (isset($this->api_response->token)?$this->api_response->token:'');
		$PaymentRequest['created']		= date("Y-m-d H:i:s");
		$PaymentRequest['modified']		= date("Y-m-d H:i:s");
		$newEntity = $this->newEntity($PaymentRequest);
		$this->save($newEntity);
		return $newEntity->id;
	}

	/**
	 * REST_API_Curl : Create a new job for web application in critical watch.
	 *
	 * Behaviour : Public
	 *
	 * @param  string  $function_url  URL of registered web application
	 * @throws Some_Exception_Class If something interesting cannot happen
	 * @return Retuns the critical watch RST API response after converting json string to readable PHP variable.
	 */
	public function ApiCall($function_url, $data)
	{
		$url = PAYMENT_URL.$function_url;

		if ($this->debug) {
			CakeLog::write('payment_log',"URL :: ".$url."\r\n");
			CakeLog::write('payment_log',"Params :: ".json_encode($data)."\r\n");
		}
		$conn = curl_init( $url );
		curl_setopt( $conn, CURLOPT_CONNECTTIMEOUT, 60 );
		curl_setopt( $conn, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $conn, CURLOPT_SSL_VERIFYHOST, 2 );
		curl_setopt( $conn, CURLOPT_RETURNTRANSFER, true );
		if($this->method == 'POST') curl_setopt( $conn, CURLOPT_POST, false );
		if(is_array($data) && count($data)>0) curl_setopt($conn, CURLOPT_POSTFIELDS, $data);
		$output = curl_exec( $conn );
		curl_close($conn);
		$arrResult	        = json_decode($output);
		$this->api_response	= $arrResult;
        if ($this->debug) {
			CakeLog::write('payment_log',"Response :: ".json_encode($output)."\r\n");
		}
        return $arrResult;
	}
}
?>