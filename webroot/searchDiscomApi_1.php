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
define('APIURL','http://103.233.170.222:8006/API/apicall.php');
###################### APPLICATION CONSTANT ######################

function REST_API_Curl($data_request)
{
	$conn 			= curl_init( APIURL );
	$headers 		= array('apitoken:'.GetAuthKey($data_request['apiaction'],$data_request['apitimestamp']));
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
$data_request['P_CON_NO'] 		= '100082970';//'02103057260';
$data_request['P_T_NO'] 		= '3002255188';
$data_request['P_DISCOM_CD'] 	= 5;
$Response 						= REST_API_Curl($data_request);
dd($Response);
die;
?>