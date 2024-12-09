<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class AtomComponent extends Component
{
	public $url 			= null;
	public $loginid			= '';
	public $password		= '';

	public $atom_payemnt_id = 0;
    public $ttype			= 'NBFundTransfer';
	public $prodid			= 'NSE';
	public $amt 			= 0;
	public $txncurr 		= 'INR';
	public $txnscamt 		= 0;
	public $clientcode  	= '';
	public $txnid       	= '';
	public $date			= '0000-00-00 00:00:00';
	public $custacc			= '';
	public $mdd				= '';
	public $atom_tempTxnId	= '';
	public $atom_token		= '';
	public $atom_txnStage	= '';
	public $atom_mmp_txn	= '';
	public $atom_mer_txn	= '';
	public $atom_amt		= '';
	public $atom_surcharge	= '';
	public $atom_prodid		= '';
	public $atom_date		= '';
	public $atom_bank_txn	= '';
	public $atom_f_code		= '';
	public $atom_clientcode	= '';
	public $atom_bank_name	= '';
	public $atom_discriminator = '';
	public $atom_CardNumber	= '';
	public $atom_desc		= '';
	public $atom_request1	= '';
	public $atom_response1	= '';
	public $atom_request2 	= '';
	public $atom_response2	= '';
	public $created			= '0000-00-00 00:00:00';
	public $updated			= '0000-00-00 00:00:00';

    public $ATOM_PAYMENT_SUCCESS_F_CODE    = 'ok';
    public $ATOM_PAYMENT_FAIL_F_CODE       = 'f';

	public $ResponseArray	= array();

    private function initializePaymentFirstRequest($payment_id, $amount, $service_charge, $merchant_txn_id, $customer_account_id, $mdd='', $customer_info=array())
    {
        //This three value should be from constant file.
        $this->url 			= 'http://paynetzuat.atomtech.in/paynetz/epi/fts';
        $this->loginid 		= 160;
        $this->password 	= 'Test@123';
        $this->ru 			= 'http://127.0.0.2/payment/process_atom_response';
        // Constant value ends

        //$this->ttype 		= 'NBFundTransfer';
        //$this->prodid 		= 'NSE'
        $this->payment_id   = $payment_id;
        $this->amount 		= $amount;
        $this->txnscamt 	= $service_charge;
        $this->txnid 		= $merchant_txn_id;
        $this->date 		= date('d/m/Y H:i:s');
        $this->custacc		= $customer_account_id;
        $this->mdd 			= $mdd;
        $this->clientcode 	= $customer_account_id;
        $this->created 		= date('Y-m-d H:i:s');

        $this->udf1 		= (isset($customer_info['name']))?$customer_info['name']:'';
        $this->udf2 		= (isset($customer_info['email']))?$customer_info['email']:'';
        $this->udf3 		= (isset($customer_info['mobile']))?$customer_info['mobile']:'';
        $this->udf4 		= (isset($customer_info['billing_addr']))?$customer_info['billing_addr']:'';
        $this->udf5 		= (isset($customer_info['bank_names']))?$customer_info['bank_names']:'';
        $this->udf6 		= (isset($customer_info['emi_tenures']))?$customer_info['emi_tenures']:'';
        $this->udf9 		= '3021';
    }

    private function GenerateAndSendFirstRequest()
    {
    	$postFields 	= '';
    	$clientcode 	= urlencode(base64_encode($this->clientcode));
    	$modifiedDate 	= str_replace(" ", "%20", $this->date);

    	$url = $this->url.'?login='.$this->loginid.'&pass='.$this->password.'&ttype='.$this->ttype.'&prodid='.$this->prodid.'&amt='.$this->amt.'&txncurr='.$this->txncurr.'&txnscamt='.$this->txnscamt.'&clientcode='.$clientcode.'&txnid='.$this->txnid.'&date='.$modifiedDate.'&custacc='.$this->custacc;

    	$postFields .= "&login=".$this->loginid;
    	$postFields .= "&pass=".$this->password;
    	$postFields .= "&ttype=".$this->ttype;
    	$postFields .= "&prodid=".$this->prodid;
    	$postFields .= "&amt=".$this->amount;
    	$postFields .= "&txncurr=".$this->txncurr;
    	$postFields .= "&txnscamt=".$this->txnscamt;
    	$postFields .= "&clientcode=".$clientcode;
    	$postFields .= "&txnid=".$this->txnid;
    	$postFields .= "&date=".$modifiedDate;
    	$postFields .= "&custacc=".$this->custacc;

    	if(!empty($this->mdd)) {
    		$postFields .='&mdd='.$this->mdd;
    	}

    	if(!empty($this->ru)) {
    		$postFields .='&ru='.$this->ru;	
    	}

    	if(!empty($this->udf1)) {
    		$postFields .='&udf1='.$this->udf1;
    	}

    	if(!empty($this->udf2)) {
    		$postFields .='&udf2='.$this->udf2;
    	}

    	if(!empty($this->udf3)) {
    		$postFields .='&udf3='.$this->udf3;
    	}

		if(!empty($this->udf4)) {
    		$postFields .='&udf4='.$this->udf4;
    	}    	

    	if(!empty($this->udf5)) {
    		$postFields .='&udf5='.$this->udf5;
    	}

    	if(!empty($this->udf6)) {
    		$postFields .='&udf6='.$this->udf6;
    	}

    	if(!empty($this->udf9)) {
    		$postFields .='&udf9='.$this->udf9;
    	}

    	//echo "=Request URL==>".$url."<====";
    	//echo "=Request Post Fields==>".$postFields."<====";

    	$sendUrl = $url."?".substr($postFields,1)."\n";

		$this->writeLog($sendUrl);
		$this->atom_payemnt_id	= $this->SaveAtomPayments($postFields);

    	$ResponseData	= $this->SendCurlRequest($url, $postFields);

    	if(!empty($ResponseData)) {

    		$this->ResponseArray	= $this->xmltoarray($ResponseData);
    		$this->UpdateAtomFirstRequestResponse($this->atom_payemnt_id, $this->ResponseArray, $ResponseData);
			$url 		= $this->ResponseArray['url'];
			$this->writeLog($url."\n");
			return true;
    	} else {
    		die("Problem in Request");
    	}
    	return false;
    }

    private function UpdateAtomFirstRequestResponse($atom_payemnt_id, $AtomResponse, $ResponseData)
    {
    	$atomPaymentsTable = TableRegistry::get('AtomPayments');
		$atomPayment = $atomPaymentsTable->get($atom_payemnt_id); // article with id 12

        $atomPayment->atom_response1 	= $ResponseData;
		$atomPayment->atom_tempTxnId 	= $AtomResponse['tempTxnId'];
		$atomPayment->atom_token 		= $AtomResponse['token'];
        $atomPayment->atom_txnStage     = 1;

		$atomPaymentsTable->save($atomPayment);
    }

    private function SaveAtomPayments($atom_request1)
    {
    	//use Cake\ORM\TableRegistry;

    	$date = date('Y-m-d H:i:s', strtotime($this->date));

		$atomPaymentsTable = TableRegistry::get('AtomPayments');
		$atomPayment = $atomPaymentsTable->newEntity();

		$atomPayment->payment_id= $this->payment_id;
        $atomPayment->ttype 	= $this->ttype;
		$atomPayment->prodid 	= $this->prodid;
		$atomPayment->amt 		= $this->amount;
		$atomPayment->txncurr 	= $this->txncurr;
		$atomPayment->txnscamt 	= $this->txnscamt;
		$atomPayment->clientcode= $this->clientcode;
		$atomPayment->txnid 	= $this->txnid;
		$atomPayment->date 		= $date;

		$atomPayment->custacc 	= $this->custacc;
		$atomPayment->mdd 		= $this->mdd;
		$atomPayment->atom_request1 = $atom_request1;
		$atomPayment->created 	= $this->created;
		$atomPayment->updated 	= $this->updated;

		if ($atomPaymentsTable->save($atomPayment)) {
		    // The $article entity contain the id now
		    return $atomPayment->id;
		}
		return 0;
    }

    private function SendCurlRequest($url, $data)
    {
    	$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_PORT , 80); 
		curl_setopt($ch, CURLOPT_SSLVERSION,3);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$returnData = curl_exec($ch);

		//echo 'Curl error: ' . curl_error($ch);

		curl_close($ch);
		return $returnData;
    }

    private function writeLog($data)
    {
		//Write here log for the request.

		/*$fileName = date("Y-m-d").".txt";
		$fp = fopen("log/".$fileName, 'a+');
		$data = date("Y-m-d H:i:s")." - ".$data;
		fwrite($fp,$data);
		fclose($fp);*/
	}

	private function xmltoarray($data)
	{
		$parser = xml_parser_create('');
		xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); 
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
		xml_parse_into_struct($parser, trim($data), $xml_values);
		xml_parser_free($parser);
		
		$returnArray 				= array();
		$returnArray['url'] 		= $xml_values[3]['value'];
		$returnArray['tempTxnId'] 	= $xml_values[5]['value'];
		$returnArray['token'] 		= $xml_values[6]['value'];

		return $returnArray;
	}

    public function AtomFirstPaymentRequest($payment_id, $amount, $service_charge, $merchant_txn_id, $customer_account_id, $mdd='', $customer_info=array())
    {
    	$this->initializePaymentFirstRequest($payment_id, $amount, $service_charge, $merchant_txn_id, $customer_account_id, $mdd, $customer_info);

    	if($this->GenerateAndSendFirstRequest()) {
    		return true;    		
    	}
    	return false;
    }

    public function RedirectOnAtomPaymentPage()
    {
    	if(!empty($this->ResponseArray)) {

    		$postFields  = "";
			$postFields .= "&ttype=".$this->ttype;
			$postFields .= "&tempTxnId=".$this->ResponseArray['tempTxnId'];
			$postFields .= "&token=".$this->ResponseArray['token'];
			$postFields .= "&txnStage=1";
			$url = $this->url."?".$postFields;

            $this->SaveSecondRequest($url);

			$this->writeLog($url."\n");
			header("Location: ".$url);
			die();	
    	}
    	return false;    	
    }

    private function SaveSecondRequest($url)
    {
        $atomPaymentsTable = TableRegistry::get('AtomPayments');
        $atomPayment = $atomPaymentsTable->get($this->atom_payemnt_id); // article with id 12

        $atomPayment->atom_request2     = $url;

        $atomPaymentsTable->save($atomPayment);        
    }

    public function ProcessAndSavePaymentResponse()
    {
    	$this->SetAtomResponseData();
        $this->SaveAtomResponse();
    }

    private function SaveAtomResponse()
    {
    	if(!empty($this->atom_mer_txn)) {

    		$atomPaymentsTable = TableRegistry::get('AtomPayments');
            $atomPayment = $atomPaymentsTable->find('all')->where(['AtomPayments.txnid' => $this->atom_mer_txn])->first();

            if($atomPayment) {
                $this->payment_id               = $atomPayment->payment_id;
				$atomPayment->atom_mmp_txn 	    = $this->atom_mmp_txn;
				$atomPayment->atom_mer_txn 	    = $this->atom_mer_txn;

				$atomPayment->atom_amt          = $this->atom_amt;
				$atomPayment->atom_surcharge    = $this->atom_surcharge;
				$atomPayment->atom_prodid       = $this->atom_prodid;
				$atomPayment->atom_date 	    = date('Y-m-d H:i:s', strtotime($this->atom_date));
				$atomPayment->atom_bank_txn     = $this->atom_bank_txn;
				$atomPayment->atom_f_code 	    = $this->atom_f_code;
				$atomPayment->atom_clientcode   = $this->atom_clientcode;
				$atomPayment->atom_bank_name 	= $this->atom_bank_name;
				$atomPayment->atom_discriminator= $this->atom_discriminator;
				$atomPayment->atom_CardNumber   = $this->atom_CardNumber;
				$atomPayment->atom_desc 		= $this->atom_desc;
				$atomPayment->atom_response2 	= $this->atom_response2;

				if ($atomPaymentsTable->save($atomPayment)) {
				    // The foreign key value was set automatically.
				    return $atomPayment->id;
				}
			}
    	}
        return 0;    	
    }

    private function SetAtomResponseData()
    {
    	$this->atom_mmp_txn = isset($_POST['mmp_txn'])?$_POST['mmp_txn']:0;
    	$this->atom_mer_txn = isset($_POST['mer_txn'])?$_POST['mer_txn']:0;
    	$this->atom_amt 	= isset($_POST['amt'])?$_POST['amt']:0;
    	$this->atom_prodid 	= isset($_POST['prod'])?$_POST['prod']:'';
    	$this->atom_date 	= isset($_POST['date'])?$_POST['date']:'';
    	$this->atom_bank_txn= isset($_POST['bank_txn'])?$_POST['bank_txn']:0;
    	$this->atom_f_code 	= isset($_POST['f_code'])?$_POST['f_code']:'';
    	$this->atom_clientcode 	= isset($_POST['clientcode'])?$_POST['clientcode']:0;

    	$this->atom_bank_name 	= isset($_POST['bank_name'])?$_POST['bank_name']:'';
    	$this->atom_merchant_id = isset($_POST['merchant_id'])?$_POST['merchant_id']:'';
    	$this->atom_discriminator	= isset($_POST['discriminator'])?$_POST['discriminator']:'';
    	$this->atom_surcharge 		= isset($_POST['surcharge'])?$_POST['surcharge']:0;
    	$this->atom_CardNumber	= isset($_POST['CardNumber'])?$_POST['CardNumber']:'';

    	/*$this->atom_udf1 		= isset($_POST['udf1'])?$_POST['udf1']:0;
    	$this->atom_udf2 		= isset($_POST['udf2'])?$_POST['udf2']:0;
    	$this->atom_udf3 		= isset($_POST['udf3'])?$_POST['udf3']:0;
    	$this->atom_udf4 		= isset($_POST['udf4'])?$_POST['udf4']:0;
    	$this->atom_udf5 		= isset($_POST['udf5'])?$_POST['udf5']:0;
    	$this->atom_udf6 		= isset($_POST['udf6'])?$_POST['udf6']:0;
    	$this->atom_udf9		= isset($_POST['udf9'])?$_POST['udf9']:0;*/

    	$this->atom_response2	= serialize($_POST);
    }

    private function CallAPI()
    {

    	$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_PORT , 80); 
		curl_setopt($ch, CURLOPT_SSLVERSION,3);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$returnData = curl_exec($ch);

		curl_close($ch);
		return $returnData;
    }
}
?>