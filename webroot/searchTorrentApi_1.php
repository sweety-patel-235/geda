<?php
echo '<prE>';
		$url    		= 'https://connect.torrentpower.com/tplwss/geda/call_geda.php';
		$str_data 		= '';
		//$input_array 	= '701000021';
		
        $data_request 			= array("P_IN_DATA"		=> '320379',
	    							"P_SRV_CD"		=> 1,
	    							"P_CLIENT_CD"	=> 1,
	    							"P_DISCOM_CD"	=> 5,
	    							"P_DT_TM"		=> '16.09.2019.11.35.15',
    								"P_AUTH_KEY"	=> 'test');
        $data_request = array_merge($data_request,array('P_IN_DATA2'=>'3000675608'));
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
		$conn 				= curl_init( $url.$str_data );
		curl_setopt( $conn, CURLOPT_HTTPHEADER, array());
        curl_setopt( $conn, CURLOPT_CONNECTTIMEOUT, 300 );
        curl_setopt( $conn, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $conn, CURLOPT_SSL_VERIFYHOST, 0 );
        curl_setopt( $conn, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $conn, CURLOPT_POST, true );
    	curl_setopt( $conn, CURLOPT_POSTFIELDS, $post_string);
    	
        $output 			= curl_exec( $conn );
		$Response   		= json_decode($output);

		print_r($Response);
		exit;
		?>