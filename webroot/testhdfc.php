<?php
error_reporting(0);
function encrypt($plainText,$key)
	{
		$secretKey = hextobin(md5($key));
		$initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
	  	$openMode = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '','cbc', '');
	  	$blockSize = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, 'cbc');
		$plainPad = pkcs5_pad($plainText, $blockSize);
	  	if (mcrypt_generic_init($openMode, $secretKey, $initVector) != -1) 
		{
		      $encryptedText = mcrypt_generic($openMode, $plainPad);
	      	      mcrypt_generic_deinit($openMode);
		      			
		} 
		return bin2hex($encryptedText);
	}
 
	function decrypt($encryptedText,$key)
	{
		$secretKey = hextobin(md5($key));
		$initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
		$encryptedText=hextobin($encryptedText);
	  	$openMode = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '','cbc', '');
		mcrypt_generic_init($openMode, $secretKey, $initVector);
		$decryptedText = mdecrypt_generic($openMode, $encryptedText);
		$decryptedText = rtrim($decryptedText, "\0");
	 	mcrypt_generic_deinit($openMode);
		return $decryptedText;
		
	}
	function pkcs5_pad ($plainText, $blockSize)
	{
	    $pad = $blockSize - (strlen($plainText) % $blockSize);
	    return $plainText . str_repeat(chr($pad), $pad);
	}
 	function hextobin($hexString) 
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
    function generateHash($posted = []) {
        $merchant_data 	= array();
        $mer_str_data 	= '';
        foreach ($posted as $key => $value){
        	$mer_str_data.=$key.'='.$value.'&';
			$merchant_data[]=$key.'='.$value;
		}
		$mer_str= implode("&",$merchant_data);
 		
 		/*$mer_str_data = "order_id=123455646565&merchant_id=193106&currency=INR&amount=2050&redirect_url=https://geda.ahasolar.in/payutransfer/success&cancel_url=https://geda.ahasolar.in/payutransfer/cancel&tid=1541397456568&language=EN&billing_name=Charli&billing_address=Room no 1101, near Railway station Ambad&billing_city=Indore&billing_state=MP&billing_zip=425001&billing_country=India&billing_tel=9876543210&billing_email=test@test.com&delivery_name=Chaplin&delivery_address=room no.701 near bus stand&delivery_city=Hyderabad&delivery_state=Andhra&delivery_zip=425001&delivery_country=India&delivery_tel=9876543210&merchant_param1=additional Info.&merchant_param2=additional Info.&merchant_param3=additional Info.&merchant_param4=additional Info.&merchant_param5=additional Info.&promo_code=&customer_identifier=&";
 		echo $mer_str_data;*/
 		echo $mer_str."&language=EN&billing_name=Charli&billing_address=Room no 1101, near Railway station Ambad&billing_city=Indore&billing_state=MP&billing_zip=425001&billing_country=India&billing_tel=9876543210&billing_email=test@test.com&delivery_name=Chaplin&delivery_address=room no.701 near bus stand&delivery_city=Hyderabad&delivery_state=Andhra&delivery_zip=425001&delivery_country=India&delivery_tel=9876543210&merchant_param1=11111&merchant_param2=additional Info.&merchant_param3=additional Info.&merchant_param4=additional Info.&merchant_param5=additional Info.&promo_code=&customer_identifier=&";
 		$encrypted_data=encrypt($mer_str."&language=EN&billing_name=Charli&billing_address=Room no 1101, near Railway station Ambad&billing_city=Indore&billing_state=MP&billing_zip=425001&billing_country=India&billing_tel=9876543210&billing_email=test@test.com&delivery_name=Chaplin&delivery_address=room no.701 near bus stand&delivery_city=Hyderabad&delivery_state=Andhra&delivery_zip=425001&delivery_country=India&delivery_tel=9876543210&merchant_param1=11111&merchant_param2=additional Info.&merchant_param3=additional Info.&merchant_param4=additional Info.&merchant_param5=additional Info.&promo_code=&customer_identifier=&",'89C2E26FBDE51C08E358BE73C41C9184');
	return $encrypted_data;
    }
    $posted_array['order_id']		='123455646565';
    $posted_array['merchant_id']	='193106';
    $posted_array['currency']		='INR';
    $posted_array['amount']			='2050';
    $posted_array['redirect_url']	='http://gujarat.ahasolar.in/payutransfer/success';
    $posted_array['cancel_url']		='http://gujarat.ahasolar.in/payutransfer/cancel';
    $posted_array['tid']			='';
   // $posted_array['name']			='test';
    //$posted_array['address']		='test';
    $encRequest 					= generateHash($posted_array);
?>
<?php /*
<form action="https://test.ccavenue.com/transaction/transaction.do?command=initiateTransaction" method="POST" name="payform_1" id="payform_1">
<input type="hidden" name="encRequest" value="<?php echo $encRequest;?>" />
<input type="hidden" name="access_code" value="AVGM80FJ87AJ09MGJA" />
    <input type="submit" class="button" id="submit_payu_in_payment_form" value="Pay via HDFC" />
    <a class="button cancel" href="https://geda.ahasolar.in/payutransfer/cancel">Cancel order &amp; restore cart</a>
    <script type="text/javascript">
        var payuForm = document.forms.payform_1;
       // payuForm.submit();
    </script>
</form> 

Test Card Details                               Failed card details        
VISA CREDIT CARD                                VISA CREDIT CARD          
Card No:4012 0010 3714 1112                     4111 1111 1111 1111   
Exp:12/2020                                     Exp:12/2020                                                        
CVV:123                                         CVV:123


*/

?>
<form action="https://test.ccavenue.com/transaction/transaction.do?command=initiateTransaction" method="POST" name="payuForm" id="payform">
                <input type="hidden" name="encRequest" value="ab2206c855481b3d83d30bca8425c6c1df2bbdd3f06cb9c16d6a83fb0b685767846e88958fd5475c6b0d040f366f5ce8f7b8c02a464c04ef982a6819082ef2ca9312b69e2306b2ba486c1c1843946c29ecaeaf2ccf3a7d6b76c4a0be8a3e4a124319ceeffcaa365c589a3d81e1a1a72c9af6e3dee18c0bae9646efdd0c82053702b1a54cf9c7732f45afa9066bde5339d8925aa0590a7b08131694da78a77a34daacc655269830a8996f85d4fec8aacac24711e2b8bc069ebf4d2a0eddbb74d0bb2f9405d88452314beea1feaacbc536e8e90e1b96e986a9353e92a15bf78871ee20885f5329085e823b5fa0f2a6bbed71568eb2acd9c39c22b7e62eb6dd0016c2a443e357c2815d44f3fb73e6b710f664d8a4828d5657352a054f82a1dc0afbcc1ce3a4420c8f099be6ca0a781109ef8af4c23d50c3b276730d7f5d28c19a02cfbf97fb5c73d12a8ef61370e4f83e8fa013b8df544da5bb7683f61c856de900fc273dfd59c86d71a84e789c94fc50d62aba9bf510a38f03c9b2df2535f7283015d33cfcd3717673f47045c78e467f2c7728301c367f914fc42d62765527d96cb3000b390f2178944bfd738f4bdb512305dfc5671e044c939e10349a32f9d522" />
<input type="hidden" name="access_code" value="AVGM80FJ87AJ09MGJA" />

                <input type="submit" class="button" id="submit_payu_in_payment_form" value="Pay via HDFC" />
                <a class="button cancel" href="http://demo-gujarat.ahasolar.in/payutransfer/cancel">Cancel order &amp; restore cart</a>
                <script type="text/javascript">
                    var payuForm = document.forms.payuForm;
                   // payuForm.submit();
                </script>
            </form>
            <?php /*
<form action="https://test.ccavenue.com/transaction/transaction.do?command=initiateTransaction" method="POST" name="payuForm" id="payform">
                <input type="hidden" name="encRequest" value="88776f9d2a2c1cc9d8d8550d40e3d73c9dd549b570d7fd57a635bf94f1dbacee2175986b1c59eda9c00fe2c24e4b1a70e0aaf300776a7e838c6f05c3eb63815e0f3717d7360ed00b7747228ceae3aba8c1703e3b4aa1db50475b66f31e19f5cef0f820ce1438b0517c0d6c5313db04db034255a626cb8bfb81f8b5ed83158627088a35dbb99b4246b1695c48f954cee96ecb086f1a78e80973fb2819a809e406f1ff8afbde9870ef3b767c97f55bc6ab118ee9bf00e6d881eb04893582101d92391954997b9ba6aec99b7d59de81bac5fa7133d4cc187cd8612a68e8a42778e091f79ee2e9a4e5fa2a9b16ad5d803af1e8ec4846488aefb3626956d28472c2f4f78c2a46bf41233f9e9976917cf1c1a548161c6c74143894ddc0f0c93f6676c0cc6886305daf313f4c11dcec8597950ecedaf218ba160a2f2cb16a53a7f9319a2921675971d5d6d71cecb40d6eb07b68665890e7154e85caedf41c950490644fd8ee3e7605a1d3711c43ff1611ea153b767ce3baea82bec2182d89b7256ab191f144f1637e55ea568bd2a28e0696b303286e4131df64e52fddbd786248f08897" />
<input type="hidden" name="access_code" value="AVGM80FJ87AJ09MGJA" />

                <input type="submit" class="button" id="submit_payu_in_payment_form" value="Pay via HDFC" />
                <a class="button cancel" href="http://gujarat.ahasolar.in/payutransfer/cancel">Cancel order &amp; restore cart</a>
                <script type="text/javascript">
                    var payuForm = document.forms.payuForm;
                   // payuForm.submit();
                </script>
            </form>
Form test second
<br>
<?php/*
<form action="https://test.ccavenue.com/transaction/transaction.do?command=initiateTransaction" method="POST" name="payuForm" id="payform">
                <input type="hidden" name="encRequest" value="3a1112de2b639e771b19afe78ecbe45d301e6fc892d8212256a7b2ee7fe6e6b87860bef616d6d08d0f98f640414114804c31959d4bf3f71449af57298b2426d93f27adf1751d07add37f2b6a86867d16d45e9e665516d013d94442c3e11fd7a9976831999112913cd061b267a4ea3572e8ff1692e4436a87488fa4c99ea97f7ea2edd5fa75308c0db1e065e9de232d74e80b566a53df0dc1b66f2fb4651d1ad02f3451e46c531ff0642f163f38b7b0ceb3677624ee10f83904553ff409b10057854e76becd7df4bda5425a1c02e18d05000005325f6ddba7c0be3588f769a9ab8187c5dcede155429e359907af643c418b72c4534feb037754ef37dc664c939fde163ac8bb891a709107f087a8ece7a04dc54819f90232aacab2474aac0d309db2b3677ce0691e7792e929789df485035a81bc1cc220d0f9a634804007f0556bf230dfc4b469766be9edf1fb7934ab805c23cf3ba0d76f463a5cd20d4cf81b07191f33594640ecb7644ba1b72391e2ba9516bc2c4d8c8fb68bb572f7c6512c72a9e729fad33f45b0c7381305e24fca9d" />
<input type="hidden" name="access_code" value="AVGM80FJ87AJ09MGJA" />

                <input type="submit" class="button" id="submit_payu_in_payment_form" value="Pay via HDFC" />
                <a class="button cancel" href="http://gujarat.ahasolar.in/payutransfer/cancel">Cancel order &amp; restore cart</a>
                <script type="text/javascript">
                    var payuForm = document.forms.payuForm;
                   // payuForm.submit();
                </script>
            </form>
            */?>