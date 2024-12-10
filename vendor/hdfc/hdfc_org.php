<?php
/**
 * CakePHP Vendor: PayuMoney
 * @author: Kapil Gupta <kapil.gp@gmail.com>
 * @version: 1.0.0
 * @created: August 20, 2016
 */
namespace Hdfc;

class Hdfc {

    // Merchant key here as provided by Payu
    private $merchantKey    = "";
    private $encRequest     = "";
    private $working_key     = "";
    private $access_code    = "";
    private $hdfcBaseURL    = "";
    //Hash code
    private $hash           = "";

    //Constructor
    public function __construct($merchantKey = "", $salt = "",$access_code = "", $sandbox = true) {

        $this->merchantKey  = $merchantKey;
        $this->working_key  = $salt;
        $this->access_code  = $access_code;
        $this->hdfcBaseURL  = "https://secure.ccavenue.com";
        if ($sandbox) {
            $this->hdfcBaseURL = "https://test.ccavenue.com";
        }
    }

    public function getAction() {
        return $this->hdfcBaseURL . '/transaction/transaction.do?command=initiateTransaction';
    }

    public function randomTxnId() {
        // Generate random transaction id
        return substr(hash('sha256', mt_rand() . microtime()), 0, 20);
    }
    public function encrypt($plainText,$key)
    {
        $secretKey = $this->hextobin(md5($key));
        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $openMode = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '','cbc', '');
        $blockSize = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, 'cbc');
        $plainPad = $this->pkcs5_pad($plainText, $blockSize);
        if (mcrypt_generic_init($openMode, $secretKey, $initVector) != -1) 
        {
              $encryptedText = mcrypt_generic($openMode, $plainPad);
                  mcrypt_generic_deinit($openMode);
                        
        } 
        return bin2hex($encryptedText);
    }
 
    public function decrypt($encryptedText,$key)
    {
        $secretKey = $this->hextobin(md5($key));
        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $encryptedText=$this->hextobin($encryptedText);
        $openMode = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '','cbc', '');
        mcrypt_generic_init($openMode, $secretKey, $initVector);
        $decryptedText = mdecrypt_generic($openMode, $encryptedText);
        $decryptedText = rtrim($decryptedText, "\0");
        mcrypt_generic_deinit($openMode);
        return $decryptedText;
        
    }
    public function pkcs5_pad ($plainText, $blockSize)
    {
        $pad = $blockSize - (strlen($plainText) % $blockSize);
        return $plainText . str_repeat(chr($pad), $pad);
    }
    public function hextobin($hexString) 
    { 
            $length = strlen($hexString); 
            $binString="";   
            $count=0; 
            while($count<$length) 
            {       
                $subString =substr($hexString,$count,2);           
                $packedString = pack("H*",$subString); 
                if ($count==0)
            {
                $binString=$packedString;
            } 
                
            else 
            {
                $binString.=$packedString;
            } 
                
            $count+=2; 
            } 
            return $binString; 
    }
    public function generateHash($posted = []) {
        
        // Hash Sequence
        $mer_str_data='';
        foreach ($posted as $key => $value){
            $mer_str_data.=$key.'='.$value.'&';
        }
        $encrypted_data=$this->encrypt($mer_str_data,$this->working_key);

        $this->encRequest = $encrypted_data;

    }
    
     public function generateHashforAPI($posted = []) {
        // Hash Sequence
        $hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10|salt";

        $hashVarsSeq = explode('|', $hashSequence);
        $hash_string = '';
        foreach($hashVarsSeq as $hash_var) {
            $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
            $hash_string .= '|';
        }
        $this->hash = strtolower(hash('sha512', $hash_string));
        return $this->hash;

    }

    public function send($posted = []) {
        // Post Request
        $posted['merchant_id'] = $this->merchantKey;
        $this->generateHash($posted);

        $posturl = $this->hdfcBaseURL . '/transaction/transaction.do?command=initiateTransaction';

        
        $payuform = '';
       
       // $payuform .= '<input type="hidden" name="order_id" value="' . $posted['order_id'] . '" />' . "\n";
        $payuform .= '<input type="hidden" name="encRequest" value="' . $this->encRequest . '" />' . "\n";
        $payuform .= '<input type="hidden" name="access_code" value="' . $this->access_code . '" />' . "\n";
       // $payuform .= '<input type="hidden" name="currency" value="INR" />' . "\n";
       // $payuform .= '<input type="hidden" name="hash" value="' . $this->hash . '" />' . "\n";
        // The form
        echo '
          <style>
            body {
                text-lign:      center;
                background-color:#fff;
                cursor: wait;
                margin: 0 auto;
                width: 200px;
            }
            .box {
              margin: 50 0px;
              width: 200px;
              background-color:#e6e6e6;
              padding: 50px;
              border: 3px solid #aaa;
            }
          </style>
          <div class="box">
            Thank you for your order. We are now redirecting you to CCAvenue to make payment.
          </div>
          <form action="' . $posturl . '" method="POST" name="payuForm" id="payform">
                ' . $payuform . '
                <input type="submit" class="button" id="submit_payu_in_payment_form" value="Pay via HDFC" />
                <a class="button cancel" href="' . $posted['cancel_url'] . '">Cancel order &amp; restore cart</a>
                <script type="text/javascript">
                    var payuForm = document.forms.payuForm;
                    payuForm.submit();
                </script>
            </form>';
        exit;
    }
    public function getData($posted = []) {
        // Post Request
       $posted['merchant_id'] = $this->merchantKey;
        pr($posted);
        $this->generateHash($posted);

        $posturl = 'https://apisecure.ccavenue.com/apis/servlet/DoWebTrans';
        $posturl = 'https://api.ccavenue.com/apis/servlet/DoWebTrans';

        
        $payuform = '';
        echo $this->encRequest.'<br>';
        echo $this->access_code;
        $final_data = 'enc_request='.$this->encRequest.'&access_code='.$this->access_code;
        /* $final_data['enc_request'] = $this->encRequest;
        $final_data['access_code'] = $this->access_code;
        $final_data['command'] = 'orderStatusTracker';
        $final_data['request_type'] = 'JSON';
        $final_data['response_type'] = 'JSON';*/
        echo 'Request Data----------<br>';
        echo $final_data.'<br>';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $posturl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-Type: application/json')) ;
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $final_data);
        // Get server response ...
        $result = curl_exec($ch);
        curl_close($ch);
        echo 'Result Data----------<br>';
        echo $result;
        exit;
       // $payuform .= '<input type="hidden" name="order_id" value="' . $posted['order_id'] . '" />' . "\n";
        $payuform .= '<input type="hidden" name="encRequest" value="' . $this->encRequest . '" />' . "\n";
        $payuform .= '<input type="hidden" name="access_code" value="' . $this->access_code . '" />' . "\n";
       // $payuform .= '<input type="hidden" name="currency" value="INR" />' . "\n";
       // $payuform .= '<input type="hidden" name="hash" value="' . $this->hash . '" />' . "\n";
        // The form
        echo '
          <style>
            body {
                text-lign:      center;
                background-color:#fff;
                cursor: wait;
                margin: 0 auto;
                width: 200px;
            }
            .box {
              margin: 50 0px;
              width: 200px;
              background-color:#e6e6e6;
              padding: 50px;
              border: 3px solid #aaa;
            }
          </style>
          <div class="box">
            Thank you for your order. We are now redirecting you to CCAvenue to make payment.
          </div>
          <form action="' . $posturl . '" method="POST" name="payuForm" id="payform">
                ' . $payuform . '
                <input type="submit" class="button" id="submit_payu_in_payment_form" value="Pay via HDFC" />
                <a class="button cancel" href="' . $posted['cancel_url'] . '">Cancel order &amp; restore cart</a>
                <script type="text/javascript">
                    var payuForm = document.forms.payuForm;
                    payuForm.submit();
                </script>
            </form>';
        exit;
    }
}