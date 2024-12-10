<?php
/************************************************************
* File Name : testapi.php 									*
* purpose	: Application index function file 				*
* @package  : api											*
* @author 	: Kalpak Prajapati								*
* @since 	: 01/09/2018									*
************************************************************/
date_default_timezone_set("Asia/Kolkata");
###################### APPLICATION CONSTANT ######################
ini_set("display_errors", 1);
ini_set("track_errors", 1);
ini_set("html_errors", 1);
error_reporting(E_ALL);
define('HMAC_HASH_PRIVATE_KEY', '9XTMgu0h0R14lm8vO2cQYG8SFMe4A50j');
//define('APIURL','http://103.233.170.222:8006/API/apicall.php');
define('APIURL','https://gsdcgateext.gujarat.gov.in:8243/geda_miscapicall/v1');

define('BEARER_TOKEN',"eyJ4NXQiOiJNell4TW1Ga09HWXdNV0kwWldObU5EY3hOR1l3WW1NNFpUQTNNV0kyTkRBelpHUXpOR00wWkdSbE5qSmtPREZrWkRSaU9URmtNV0ZoTXpVMlpHVmxOZyIsImtpZCI6Ik16WXhNbUZrT0dZd01XSTBaV05tTkRjeE5HWXdZbU00WlRBM01XSTJOREF6WkdRek5HTTBaR1JsTmpKa09ERmtaRFJpT1RGa01XRmhNelUyWkdWbE5nX1JTMjU2IiwiYWxnIjoiUlMyNTYifQ.eyJzdWIiOiJnc2RjYXBpZ3dzdSIsImF1dCI6IkFQUExJQ0FUSU9OIiwiYXVkIjoiZGtqbTJ6Mm92NlBibDZEOWplR0dRdUV5R29jYSIsIm5iZiI6MTYyNzM4NTkxMiwiYXpwIjoiZGtqbTJ6Mm92NlBibDZEOWplR0dRdUV5R29jYSIsInNjb3BlIjoiZGVmYXVsdCIsImlzcyI6Imh0dHBzOlwvXC9nc2RjYXBpZ2F0ZXdheWV4dC5ndWphcmF0Lmdvdi5pbjo5NDQzXC9vYXV0aDJcL3Rva2VuIiwiZXhwIjoxOTg3Mzg1OTEyLCJpYXQiOjE2MjczODU5MTIsImp0aSI6IjFlYWU0MzNjLTVhOGQtNDE2MS05NGIzLWY2OThmNTdmYjU3MiJ9.c1N4EbVdEbdF9fgt3CvBeVtE4vIhavADdtJ3CFt17jf7TAw4LW__IB4tJi6CcgrVC6uVs3_7Wncp5h_tGF2XCc8PSqpeujiHAxtkzGhXMmNjlrBQkyhZccy8zPFQQDjUODvRWKLqfY5Q7qffFgoJz-DQCteP7eXjox3P8Rjm9MwLo0dRjrjHSvCYFjDdZRfqKxByyxxuWBluwGDvQJlsuRZeWCQ_37xkwDN79iq-DFhPn6qcrPiaFw8f4pRUseXLTq9DQeckZRiZKHiN0I3CeAVsXL9sJJ3G1NTv7syyuINeCEQ9i669L5QlhQkaKZWTFOgpwijp59Y7w_-AzqTQDA");
###################### APPLICATION CONSTANT ######################

function REST_API_Curl($data_request)
{
	$conn 			= curl_init( APIURL );
	$headers 		= array('apitoken:'.GetAuthKey($data_request['apiaction'],$data_request['apitimestamp']),"Authorization: Bearer ".BEARER_TOKEN);
	curl_setopt( $conn, CURLOPT_HTTPHEADER,$headers);
    curl_setopt( $conn, CURLOPT_CONNECTTIMEOUT, 300 );
    curl_setopt( $conn, CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt( $conn, CURLOPT_SSL_VERIFYHOST, 2 );
    curl_setopt( $conn, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $conn, CURLOPT_POST, true);
	curl_setopt( $conn, CURLOPT_POSTFIELDS,$data_request);
    $output 			= curl_exec( $conn );
    $Response   		= json_decode($output);
    return $Response;
}

function GetAuthKey($apiaction,$apitimestamp)
{
	$hash_hmac_content 	= array($apiaction,$apitimestamp);
	$content			= json_encode($hash_hmac_content);
	$hash 				= hash_hmac('sha512', $content, HMAC_HASH_PRIVATE_KEY);
	return $hash;
}

function dd($vars)
{
	echo "<pre>";
	print_r($vars);
	echo "</pre>";
}

$data_request['apiaction'] 		= 'get_consumer_details';
$data_request['apitimestamp'] 	= date("d.m.Y.H.i.s");
$data_request['P_CON_NO'] 		= '02103057260';//'100082970';//;
$data_request['P_T_NO'] 		= ''; //3002255188
$data_request['P_DISCOM_CD'] 	= 2;//5;
$Response 						= REST_API_Curl($data_request);
dd($Response);
die;
?>