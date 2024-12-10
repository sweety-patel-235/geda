<?php
echo '<prE>';
define('BEARER_TOKEN','eyJ4NXQiOiJNell4TW1Ga09HWXdNV0kwWldObU5EY3hOR1l3WW1NNFpUQTNNV0kyTkRBelpHUXpOR00wWkdSbE5qSmtPREZrWkRSaU9URmtNV0ZoTXpVMlpHVmxOZyIsImtpZCI6Ik16WXhNbUZrT0dZd01XSTBaV05tTkRjeE5HWXdZbU00WlRBM01XSTJOREF6WkdRek5HTTBaR1JsTmpKa09ERmtaRFJpT1RGa01XRmhNelUyWkdWbE5nX1JTMjU2IiwiYWxnIjoiUlMyNTYifQ.eyJzdWIiOiJnc2RjYXBpZ3dzdSIsImF1dCI6IkFQUExJQ0FUSU9OIiwiYXVkIjoiZGtqbTJ6Mm92NlBibDZEOWplR0dRdUV5R29jYSIsIm5iZiI6MTYyNzM4NTkxMiwiYXpwIjoiZGtqbTJ6Mm92NlBibDZEOWplR0dRdUV5R29jYSIsInNjb3BlIjoiZGVmYXVsdCIsImlzcyI6Imh0dHBzOlwvXC9nc2RjYXBpZ2F0ZXdheWV4dC5ndWphcmF0Lmdvdi5pbjo5NDQzXC9vYXV0aDJcL3Rva2VuIiwiZXhwIjoxOTg3Mzg1OTEyLCJpYXQiOjE2MjczODU5MTIsImp0aSI6IjFlYWU0MzNjLTVhOGQtNDE2MS05NGIzLWY2OThmNTdmYjU3MiJ9.c1N4EbVdEbdF9fgt3CvBeVtE4vIhavADdtJ3CFt17jf7TAw4LW__IB4tJi6CcgrVC6uVs3_7Wncp5h_tGF2XCc8PSqpeujiHAxtkzGhXMmNjlrBQkyhZccy8zPFQQDjUODvRWKLqfY5Q7qffFgoJz-DQCteP7eXjox3P8Rjm9MwLo0dRjrjHSvCYFjDdZRfqKxByyxxuWBluwGDvQJlsuRZeWCQ_37xkwDN79iq-DFhPn6qcrPiaFw8f4pRUseXLTq9DQeckZRiZKHiN0I3CeAVsXL9sJJ3G1NTv7syyuINeCEQ9i669L5QlhQkaKZWTFOgpwijp59Y7w_-AzqTQDA');
		$url    		= 'https://gsdcgateext.gujarat.gov.in:8243/geda_testinsprpt/v1';
		//$url    		= 'https://gsdcgateext.gujarat.gov.in:8243/geda_torrent_app/v1';
		$str_data 		= '';
		//$input_array 	= '701000021';
		
        $data_request 			= array("appid"		=> '123');
     
    	//unset($data_request['P_CLIENT_CD']);
    	//unset($data_request['P_SRV_CD']);
    	$str_imp 			= array();
    	foreach($data_request as $key=>$val)
	   		{
	   			$str_imp[]	= $key."=".$val;
	   		}
	   		if(!empty($str_imp))
	   		{
	   			$str_data 	= '?'.implode("&",$str_imp);
	   		}

		$post_string   		= json_encode($data_request);
		echo $url.$str_data;
		//print_r($post_string);
		$conn 				= curl_init( $url.$str_data );
		curl_setopt( $conn, CURLOPT_HTTPHEADER, array("Authorization: Bearer ".BEARER_TOKEN));
        curl_setopt( $conn, CURLOPT_CONNECTTIMEOUT, 300 );
        curl_setopt( $conn, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $conn, CURLOPT_SSL_VERIFYHOST, 0 );
        curl_setopt( $conn, CURLOPT_RETURNTRANSFER, true );
		//curl_setopt( $conn, CURLOPT_POST, true );
    	//curl_setopt( $conn, CURLOPT_POSTFIELDS, $post_string);
    	
        $output 			= curl_exec( $conn );
		$Response   		= json_decode($output);

		print_r($Response);


		$url    		= 'https://gsdcgateext.gujarat.gov.in:8243/geda_testinsprpt/v1';
		//$url    		= 'https://gsdcgateext.gujarat.gov.in:8243/geda_torrent_app/v1';
		$str_data 		= '';
		//$input_array 	= '701000021';
		
        $data_request 			= array("appid"		=> '345');
     
    	//unset($data_request['P_CLIENT_CD']);
    	//unset($data_request['P_SRV_CD']);
    	$str_imp 			= array();
    	foreach($data_request as $key=>$val)
	   		{
	   			$str_imp[]	= $key."=".$val;
	   		}
	   		if(!empty($str_imp))
	   		{
	   			$str_data 	= '?'.implode("&",$str_imp);
	   		}

		$post_string   		= json_encode($data_request);
		echo $url.$str_data;
		//print_r($post_string);
		$conn 				= curl_init( $url.$str_data );
		curl_setopt( $conn, CURLOPT_HTTPHEADER, array("Authorization: Bearer ".BEARER_TOKEN));
        curl_setopt( $conn, CURLOPT_CONNECTTIMEOUT, 300 );
        curl_setopt( $conn, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $conn, CURLOPT_SSL_VERIFYHOST, 0 );
        curl_setopt( $conn, CURLOPT_RETURNTRANSFER, true );
		//curl_setopt( $conn, CURLOPT_POST, true );
    	//curl_setopt( $conn, CURLOPT_POSTFIELDS, $post_string);
    	
        $output 			= curl_exec( $conn );
		$Response   		= json_decode($output);

		print_r($Response);
		exit;
		
		?>