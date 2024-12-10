<?php
/************************************************************
* File Name : apibase.classe.php 							*
* purpose	: Application Base Class file 					*
* @package  : 												*
* @author 	: Kalpak Prajapati								*
* @since 	: 01/09/2018									*
************************************************************/
namespace Guvnl;

class Guvnl{

	public $GUVNL_API_URL       = GUVNL_API_URL;//'https://epaydg.guvnl.in:8001/guvnl_api_json.php'; // PRODUCTION MODE
	public $GUVNL_SALT_KEY		= GUVNL_SALT_KEY;//'4p76R6ZKGQ5rLjCh'; //PRODUCTION AUTH KEY
	public $TPL_API_URL       	= TPL_API_URL;//'https://connect.torrentpower.com/tplwss/geda/call_geda.php'; // TEST MODE TORRENT
	public $TPL_SALT_KEY		= TPL_SALT_KEY;//'test'; //TEST AUTH KEY TORRENT
	public $IPADD      			= '';
	public $SRV_CD_GCD        	= 13;
	public $SRV_CD_GCRC        	= 4; //14
	public $SRV_CD_GCPA        	= 15;
	public $SRV_CD_FSE        	= 2;
	public $SRV_CD_MID        	= 3;
	public $SRV_CD_SSD        	= 18;
	public $SRV_CD_GPD        	= 19;
	public $SRV_CD_GEN        	= 25;
	public $SRV_CD_GCBD        	= 33;

	public $P_SRV_CD 			= 1;
	public $P_MODULE_CD 		= 2;
	public $P_CLIENT_CD 		= 6;
	public $P_DISCOM_CD 		= 1;
	public $P_DT_TM 			= '';
	public $P_AUTH_KEY 			= '';
	public $P_CON_NO 			= '';
	public $P_YEAR 				= '';
	public $P_T_NO 				= '';
	public $P_CON_DETAILS 		= '';
	public $P_METHOD_TYPE 		= '';
	public $P_FILE_TRN 			= "0";
	public $method 				= "POST";

	public $P_OUT_DATA 			= '';
	public $P_OUT_STS_CD 		= ''; /* 1 FOR SUCCESS OTHER THEN 1 ERROR */
	public $P_OUT_MSG_CLIENT 	= ''; /* Message for display to Client window */
	public $P_OUT_MSG_SERVER 	= ''; /* Message for store internal purpose */
	public $API_MODE 			= API_MODE; /* 0=TEST 1=LIVE */
	public $P_OUT_FILE_ID 		= ''; /* Message for store internal purpose */
	public $now;
	public $apitoken;
	public $ApiPrivateKey;
	public $apiaction;
	public $apitimestamp;
	public $apiResponse;
	public $apirequest;
	public $allheaders;
	public $validatehash;
	public $INVALID_API_MESSAGE;
	public $apiRequestVars 		= array();

	public function __construct()
	{
		$this->now 	         		= date("Y-m-d H:i:s");
		$this->INVALID_API_MESSAGE	= INVALID_API_MESSAGE;
	}

	public function GetApiConsumerData($arrData=array())
	{

		if(!empty($arrData))
		{
			$this->P_CON_NO 		= isset($arrData['P_CON_NO'])?$arrData['P_CON_NO']:"";
			$this->P_YEAR 			= isset($arrData['P_YEAR'])?$arrData['P_YEAR']:date("Y");
			$this->P_CON_DETAILS 	= isset($arrData['P_CON_DETAILS'])?$arrData['P_CON_DETAILS']:"";
			$this->P_T_NO 			= isset($arrData['P_T_NO'])?$arrData['P_T_NO']:"";
			$this->API_MODE 		= isset($arrData['api_mode'])?$arrData['api_mode']:$this->API_MODE;
			$this->P_DISCOM_CD 		= intval(isset($arrData['P_DISCOM_CD'])?$arrData['P_DISCOM_CD']:0);
			$this->apiaction 		= isset($arrData['apiaction'])?$arrData['apiaction']:"";
			$this->apitimestamp 	= isset($arrData['apitimestamp'])?$arrData['apitimestamp']:"";
		}
		else
		{
			$this->P_CON_NO 		= isset($_POST['P_CON_NO'])?$_POST['P_CON_NO']:"";
			$this->P_YEAR 			= isset($_POST['P_YEAR'])?$_POST['P_YEAR']:date("Y");
			$this->P_CON_DETAILS 	= isset($_POST['P_CON_DETAILS'])?$_POST['P_CON_DETAILS']:"";
			$this->P_CON_NO 		= isset($_GET['P_CON_NO'])?$_GET['P_CON_NO']:$this->P_CON_NO;
			$this->P_T_NO 			= isset($_POST['P_T_NO'])?$_POST['P_T_NO']:"";
			$this->P_T_NO 			= isset($_GET['P_T_NO'])?$_GET['P_T_NO']:$this->P_T_NO;
			$this->API_MODE 		= isset($_GET['api_mode'])?$_GET['api_mode']:$this->API_MODE;
			$this->API_MODE 		= isset($_POST['api_mode'])?$_POST['api_mode']:$this->API_MODE;
			$this->P_DISCOM_CD 		= intval(isset($_POST['P_DISCOM_CD'])?$_POST['P_DISCOM_CD']:0);
			$this->P_DISCOM_CD 		= intval(isset($_GET['P_DISCOM_CD'])?$_GET['P_DISCOM_CD']:$this->P_DISCOM_CD);
			$this->apiaction 		= isset($_POST['apiaction'])?$_POST['apiaction']:"";
			$this->apitimestamp 	= isset($_POST['apitimestamp'])?$_POST['apitimestamp']:"";
			$this->apiaction 		= isset($_GET['apiaction'])?$_GET['apiaction']:$this->apiaction;
			$this->apitimestamp 	= isset($_GET['apitimestamp'])?$_GET['apitimestamp']:$this->apitimestamp;
		}

		if (strlen($this->P_CON_NO) <= 10) {
			//$this->P_CON_NO = str_pad($this->P_CON_NO,11,"0",STR_PAD_LEFT);
		}

		if($this->API_MODE==0)
		{
			$this->TPL_API_URL 		= TPL_API_URL_TEST;
			$this->TPL_SALT_KEY 	= TPL_SALT_KEY_TEST;
			$this->GUVNL_API_URL 	= GUVNL_API_URL_TEST;
			$this->GUVNL_SALT_KEY 	= GUVNL_SALT_KEY_TEST;
		}

	}

	public function getMethodType($apiaction)
	{
		switch ($apiaction) {
			case "get_consumer_details":
			{
				return 2;
				break;
			}
			case "get_consumer_registration_charges":
			{
				return 2;
				break;
			}
			case "send_consumer_registration_details":
			{
				return 1;
				break;
			}
			case "send_subsidy_details":
			{
				return 1;
				break;
			}
			case "get_subsidy_payment_details":
			{
				return 2;
				break;
			}
			case "get_generation_details":
			{
				return 2;
				break;
			}
			case "send_consumer_bank_details":
			{
				return 1;
				break;
			}
			default:
			{
				return 2;
				break;
			}
		}
	}

	public function GenerateHashKey()
	{
		if($this->P_DISCOM_CD==5 || $this->P_DISCOM_CD==6 || $this->P_DISCOM_CD==7) {
			$this->P_CLIENT_CD 	= 1;
			$salt_key    		= $this->TPL_SALT_KEY;
			$P_AUTH_KEY 		= $this->P_SRV_CD.$this->P_CLIENT_CD.$this->P_DISCOM_CD.$this->P_DT_TM.$salt_key;
			$this->P_AUTH_KEY 	= hash('sha256',$P_AUTH_KEY);
		} else {
			$P_AUTH_KEY 		= $this->P_SRV_CD.$this->P_CLIENT_CD.$this->P_DISCOM_CD.$this->P_DT_TM;
			$hash_hmac_content 	= $P_AUTH_KEY.$this->GUVNL_SALT_KEY;
			$this->P_AUTH_KEY 	= hash('sha256', $hash_hmac_content);
		}
	}

	public function GetApiActionResponse($arrRequest=array())
	{
		$this->P_DT_TM = date("d.m.Y.H.i.s");
		$this->GetApiConsumerData($arrRequest);

		switch ($this->apiaction) {
			case 'get_consumer_details':
			{
				$this->P_CLIENT_CD 	= 1;
				$this->SRV_CD_GCD 	= 1;
				$this->P_SRV_CD 	= $this->SRV_CD_GCD; //Get Estimate deails against consumer no.
				$this->P_METHOD_TYPE= $this->getMethodType($this->apiaction);
				
				$this->GenerateHashKey();
				$this->GetConsumerInformation();
				break;
			}
			case 'get_consumer_registration_charges':
			{
				$this->P_SRV_CD 	= $this->SRV_CD_GCPA; //Get Estimate deails against consumer no for registration.
				$this->P_METHOD_TYPE= $this->getMethodType($this->apiaction);
				$this->GenerateHashKey();
				$this->GetConsumerRegistrationCharges();
				break;
			}
			case 'send_consumer_registration_details':
			{

				$this->P_CLIENT_CD 	= 1;
				$this->P_SRV_CD 	= $this->SRV_CD_GCRC; //Get Estimate deails against consumer no for registration.
				$this->P_METHOD_TYPE= $this->getMethodType($this->apiaction);
				$this->GenerateHashKey();
				
				$this->SendConsumerRegistrationDetails();
				break;
			}
			case 'get_fr_estimate':
			{
				$this->P_CLIENT_CD 	= 1;
				$this->P_SRV_CD 	= $this->SRV_CD_FSE; //Get Estimate deails against consumer no.
				$this->P_METHOD_TYPE= $this->getMethodType($this->apiaction);
				$this->GenerateHashKey();
				$this->GetFesibilityEstimation();
				break;
			}
			case 'get_meter_details':
			{
				$this->P_CLIENT_CD 	= 1;
				$this->P_SRV_CD 	= $this->SRV_CD_MID; //Get Meter details against consumer no.
				$this->P_METHOD_TYPE= $this->getMethodType($this->apiaction);
				$this->GenerateHashKey();
				$this->GetMeterInstallationDetails();
				break;
			}
			case 'send_subsidy_details':
			{
				$this->P_SRV_CD 	= $this->SRV_CD_SSD; //Get Meter details against consumer no.
				$this->P_METHOD_TYPE= $this->getMethodType($this->apiaction);
				$this->GenerateHashKey();
				$this->SendSubsidyDetails();
				break;
			}
			case 'get_subsidy_payment_details':
			{
				$this->P_SRV_CD 	= $this->SRV_CD_GPD; //Get Meter details against consumer no.
				$this->P_METHOD_TYPE= $this->getMethodType($this->apiaction);
				$this->GenerateHashKey();
				$this->GetSubsidyPaymentDetails();
				break;
			}
			case 'get_generation_details':
			{
				$this->P_SRV_CD 		= $this->SRV_CD_GEN; //Get Meter GENERATION against consumer no.
				$this->P_METHOD_TYPE	= $this->getMethodType($this->apiaction);
				$this->GenerateHashKey();
				$this->GetConsumerGenerationData();
				break;
			}
			case 'send_consumer_bank_details':
			{
				$this->P_SRV_CD 	= $this->SRV_CD_GCBD; //Get Estimate deails against consumer no for registration.
				$this->P_METHOD_TYPE= $this->getMethodType($this->apiaction);
				$this->P_MODULE_CD	= 2;
				$this->GenerateHashKey();
				$this->SendConsumerBankDetails();
				break;
			}
			default:
			{
				$this->INVALID_API_MESSAGE = INVALID_API_MESSAGE;
				$this->GetApiFailResponse();
				break;
			}
		}
		return json_encode($this->apiResponse);
	}

	public function GetApiFailResponse()
	{
		$this->apiResponse = array(	"P_OUT_STS_CD"=>0,
									"P_OUT_DATA"=>array(),
									"P_OUT_MSG_CLIENT"=>INVALID_API_MESSAGE,
									"P_OUT_MSG_SERVER"=>"",
									"apiaction"=>$this->apiaction,
									"apitimestamp"=>$this->apitimestamp,
									"apirequest"=>$this->apirequest);
		return json_encode($this->apiResponse);
	}

	public function GetConsumerInformation()
	{
		$url    			= $this->GUVNL_API_URL;
		
		if($this->P_DISCOM_CD==5 || $this->P_DISCOM_CD==6 || $this->P_DISCOM_CD==7)
		{
			$url    		= $this->TPL_API_URL;
			$P_IN_DATA 			= array("P_IN_DATA"		=> $this->P_CON_NO,
										"P_SRV_CD"		=> $this->P_SRV_CD,
										"P_CLIENT_CD"	=> $this->P_CLIENT_CD,
										"P_DISCOM_CD"	=> $this->P_DISCOM_CD,
										"P_DT_TM"		=> $this->P_DT_TM,
										"P_AUTH_KEY"	=> $this->P_AUTH_KEY,
										"P_IN_DATA2"	=> $this->P_T_NO);
		} else {
			/*$P_IN_DATA 			= array("CLIENT_CD"		=> $this->P_CLIENT_CD,
										"DISCOM_CD"		=> $this->P_DISCOM_CD,
										"MODULE_CD"		=> $this->P_MODULE_CD,
										"SRV_CD"		=> $this->P_SRV_CD,
										"USR_NM"		=> USR_NM,
										"USR_PWD"		=> USR_PWD,
										"METHD_TYPE"	=> $this->P_METHOD_TYPE,
										"IN_DATA"		=> array("CNSMR_NO"=>$this->P_CON_NO),
										"FILE_TRN"		=> "0",
										"FTP_ID"		=> "0",
										"FTP_ID"		=> "0",
										"FILE_NAME"		=> "",
										"FTP_PATH"		=> "",
										"DT_TM"			=> $this->P_DT_TM,
										"AUTH_KEY" 		=> $this->P_AUTH_KEY);*/
			$P_IN_DATA 			= array("P_IN_DATA"		=> array("INPUT_DATA"=>array("cnsmr_no"=>$this->P_CON_NO)),
										"P_SRV_CD"		=> $this->P_SRV_CD,
										"P_CLIENT_CD"	=> $this->P_CLIENT_CD,
										"P_DISCOM_CD"	=> $this->P_DISCOM_CD,
										"P_DT_TM"		=> $this->P_DT_TM,
										"P_AUTH_KEY"	=> $this->P_AUTH_KEY);
		}
		$this->apirequest	= $P_IN_DATA;
		$this->CallAPI($url, $P_IN_DATA);
	}

	public function GetConsumerRegistrationCharges()
	{
		$url    			= $this->GUVNL_API_URL;
		if($this->P_DISCOM_CD==5 || $this->P_DISCOM_CD==6 || $this->P_DISCOM_CD==7)
		{
			$url    		= $this->TPL_API_URL;
			$P_IN_DATA 			= array("P_IN_DATA"		=> $this->P_CON_NO,
										"P_SRV_CD"		=> $this->P_SRV_CD,
										"P_CLIENT_CD"	=> $this->P_CLIENT_CD,
										"P_DISCOM_CD"	=> $this->P_DISCOM_CD,
										"P_DT_TM"		=> $this->P_DT_TM,
										"P_AUTH_KEY"	=> $this->P_AUTH_KEY,
										"P_IN_DATA2"	=> $this->P_T_NO);
		} else {
			$P_IN_DATA 			= array("CLIENT_CD"		=> $this->P_CLIENT_CD,
										"DISCOM_CD"		=> $this->P_DISCOM_CD,
										"MODULE_CD"		=> $this->P_MODULE_CD,
										"SRV_CD"		=> $this->P_SRV_CD,
										"USR_NM"		=> USR_NM,
										"USR_PWD"		=> USR_PWD,
										"METHD_TYPE"	=> $this->P_METHOD_TYPE,
										"IN_DATA"		=> array("CNSMR_NO"=>$this->P_CON_NO),
										"FILE_TRN"		=> "0",
										"FTP_ID"		=> "0",
										"FTP_ID"		=> "0",
										"FILE_NAME"		=> "",
										"FTP_PATH"		=> "",
										"DT_TM"			=> $this->P_DT_TM,
										"AUTH_KEY" 		=> $this->P_AUTH_KEY);
		}
		$this->CallAPI($url, $P_IN_DATA);
	}

	public function SendConsumerRegistrationDetails()
	{
		$url    			= $this->GUVNL_API_URL;
		if($this->P_DISCOM_CD==5 || $this->P_DISCOM_CD==6 || $this->P_DISCOM_CD==7) {
			$url    		= $this->TPL_API_URL;
			$P_IN_DATA 		= array("P_IN_DATA"		=> $this->P_CON_NO,
									"P_SRV_CD"		=> $this->P_SRV_CD,
									"P_CLIENT_CD"	=> $this->P_CLIENT_CD,
									"P_DISCOM_CD"	=> $this->P_DISCOM_CD,
									"P_DT_TM"		=> $this->P_DT_TM,
									"P_AUTH_KEY"	=> $this->P_AUTH_KEY,
									"P_IN_DATA2"	=> $this->P_T_NO);
		} else {
			$IN_DATA 			= array("CNSMR_NO"=>trim($this->P_CON_NO),
										"CNSMR_NAME"=>(isset($this->P_CON_DETAILS['CNSMR_NAME'])?$this->P_CON_DETAILS['CNSMR_NAME']:""),
										"APPLIED_LOAD"=>(isset($this->P_CON_DETAILS['APPLIED_LOAD'])?$this->P_CON_DETAILS['APPLIED_LOAD']:""),
										"CNSMR_MOBILE_NO"=>(isset($this->P_CON_DETAILS['CNSMR_MOBILE_NO'])?$this->P_CON_DETAILS['CNSMR_MOBILE_NO']:""),
										"CNSMR_EMAIL_ID"=>(isset($this->P_CON_DETAILS['CNSMR_EMAIL_ID'])?$this->P_CON_DETAILS['CNSMR_EMAIL_ID']:""),
										"VENDOR_CODE"=>(isset($this->P_CON_DETAILS['VENDOR_CODE'])?$this->P_CON_DETAILS['VENDOR_CODE']:""),
										"VENDOR_NAME"=>(isset($this->P_CON_DETAILS['VENDOR_NAME'])?$this->P_CON_DETAILS['VENDOR_NAME']:""),
										"CNSMR_APPL_NO"=>(isset($this->P_CON_DETAILS['CNSMR_APPL_NO'])?$this->P_CON_DETAILS['CNSMR_APPL_NO']:""),
										"APPLICATION_NO"=>(isset($this->P_CON_DETAILS['APPLICATION_NO'])?$this->P_CON_DETAILS['APPLICATION_NO']:""),
										"SOLAR_TYPE"=>(isset($this->P_CON_DETAILS['SOLAR_TYPE'])?$this->P_CON_DETAILS['SOLAR_TYPE']:""));

			/*$P_IN_DATA 			= array("CLIENT_CD"		=> $this->P_CLIENT_CD,
										"DISCOM_CD"		=> $this->P_DISCOM_CD,
										"MODULE_CD"		=> $this->P_MODULE_CD,
										"SRV_CD"		=> $this->P_SRV_CD,
										"USR_NM"		=> USR_NM,
										"USR_PWD"		=> USR_PWD,
										"METHD_TYPE"	=> $this->P_METHOD_TYPE,
										"IN_DATA"		=> $IN_DATA,
										"FILE_TRN"		=> "0",
										"FTP_ID"		=> "0",
										"FTP_ID"		=> "0",
										"FILE_NAME"		=> "",
										"FTP_PATH"		=> "",
										"DT_TM"			=> $this->P_DT_TM,
										"AUTH_KEY" 		=> $this->P_AUTH_KEY);*/
			$P_IN_DATA 			= array("P_IN_DATA"		=> array("INPUT_DATA"=>$IN_DATA),
										"P_SRV_CD"		=> $this->P_SRV_CD,
										"P_CLIENT_CD"	=> $this->P_CLIENT_CD,
										"P_DISCOM_CD"	=> $this->P_DISCOM_CD,
										"P_DT_TM"		=> $this->P_DT_TM,
										"P_AUTH_KEY"	=> $this->P_AUTH_KEY);
		}

		$this->CallAPI($url, $P_IN_DATA);
	}

	public function GetFesibilityEstimation()
	{
		$url    			= $this->GUVNL_API_URL;
		if($this->P_DISCOM_CD==5 || $this->P_DISCOM_CD==6 || $this->P_DISCOM_CD==7) {
			$P_IN_DATA 			= array("P_IN_DATA"		=> array("INPUT_DATA"=>array("cnsmr_no"=>$this->P_CON_NO)),
									"P_SRV_CD"		=> $this->P_SRV_CD,
									"P_CLIENT_CD"	=> $this->P_CLIENT_CD,
									"P_DISCOM_CD"	=> $this->P_DISCOM_CD,
									"P_DT_TM"		=> $this->P_DT_TM,
									"P_AUTH_KEY"	=> $this->P_AUTH_KEY);
		} else {
			/*$P_IN_DATA 			= array("CLIENT_CD"		=> $this->P_CLIENT_CD,
										"DISCOM_CD"		=> $this->P_DISCOM_CD,
										"MODULE_CD"		=> $this->P_MODULE_CD,
										"SRV_CD"		=> $this->P_SRV_CD,
										"USR_NM"		=> USR_NM,
										"USR_PWD"		=> USR_PWD,
										"METHD_TYPE"	=> $this->P_METHOD_TYPE,
										"IN_DATA"		=> array("CNSMR_NO"=>$this->P_CON_NO),
										"FILE_TRN"		=> "0",
										"FTP_ID"		=> "0",
										"FTP_ID"		=> "0",
										"FILE_NAME"		=> "",
										"FTP_PATH"		=> "",
										"DT_TM"			=> $this->P_DT_TM,
										"AUTH_KEY" 		=> $this->P_AUTH_KEY);*/
			$P_IN_DATA 			= array("P_IN_DATA"		=> array("INPUT_DATA"=>array("cnsmr_no"=>$this->P_CON_NO)),
										"P_SRV_CD"		=> $this->P_SRV_CD,
										"P_CLIENT_CD"	=> $this->P_CLIENT_CD,
										"P_DISCOM_CD"	=> $this->P_DISCOM_CD,
										"P_DT_TM"		=> $this->P_DT_TM,
										"P_AUTH_KEY"	=> $this->P_AUTH_KEY);
		}

		$this->apirequest	= $P_IN_DATA;
		$this->CallAPI($url, $P_IN_DATA);
	}

	public function GetMeterInstallationDetails()
	{
		$url    			= $this->GUVNL_API_URL;
		if($this->P_DISCOM_CD==5 || $this->P_DISCOM_CD==6 || $this->P_DISCOM_CD==7) {
		$P_IN_DATA 			= array("P_IN_DATA"		=> array("INPUT_DATA"=>array("cnsmr_no"=>$this->P_CON_NO)),
									"P_SRV_CD"		=> $this->P_SRV_CD,
									"P_CLIENT_CD"	=> $this->P_CLIENT_CD,
									"P_DISCOM_CD"	=> $this->P_DISCOM_CD,
									"P_DT_TM"		=> $this->P_DT_TM,
									"P_AUTH_KEY"	=> $this->P_AUTH_KEY);
		} else {
			/*$P_IN_DATA 			= array("CLIENT_CD"		=> $this->P_CLIENT_CD,
										"DISCOM_CD"		=> $this->P_DISCOM_CD,
										"MODULE_CD"		=> $this->P_MODULE_CD,
										"SRV_CD"		=> $this->P_SRV_CD,
										"USR_NM"		=> USR_NM,
										"USR_PWD"		=> USR_PWD,
										"METHD_TYPE"	=> $this->P_METHOD_TYPE,
										"IN_DATA"		=> array("CNSMR_NO"=>$this->P_CON_NO),
										"FILE_TRN"		=> "0",
										"FTP_ID"		=> "0",
										"FTP_ID"		=> "0",
										"FILE_NAME"		=> "",
										"FTP_PATH"		=> "",
										"DT_TM"			=> $this->P_DT_TM,
										"AUTH_KEY" 		=> $this->P_AUTH_KEY);*/
			$P_IN_DATA 			= array("P_IN_DATA"		=> array("INPUT_DATA"=>array("cnsmr_no"=>$this->P_CON_NO)),
										"P_SRV_CD"		=> $this->P_SRV_CD,
										"P_CLIENT_CD"	=> $this->P_CLIENT_CD,
										"P_DISCOM_CD"	=> $this->P_DISCOM_CD,
										"P_DT_TM"		=> $this->P_DT_TM,
										"P_AUTH_KEY"	=> $this->P_AUTH_KEY);
		}
		//print_r($P_IN_DATA);
		$this->apirequest	= $P_IN_DATA;
		$this->CallAPI($url, $P_IN_DATA);
	}
	public function SendConsumerBankDetails()
	{
		$url    			= $this->GUVNL_API_URL;
		if($this->P_DISCOM_CD==5 || $this->P_DISCOM_CD==6 || $this->P_DISCOM_CD==7) {
			$url    		= $this->TPL_API_URL;
			$P_IN_DATA 		= array("P_IN_DATA"		=> $this->P_CON_NO,
									"P_SRV_CD"		=> $this->P_SRV_CD,
									"P_CLIENT_CD"	=> $this->P_CLIENT_CD,
									"P_DISCOM_CD"	=> $this->P_DISCOM_CD,
									"P_DT_TM"		=> $this->P_DT_TM,
									"P_AUTH_KEY"	=> $this->P_AUTH_KEY,
									"P_IN_DATA2"	=> $this->P_T_NO);
		} else {
			$IN_DATA 			= array("CNSMR_NO"=>trim($this->P_CON_NO),
										"APPLICATION_NO"=>(isset($this->P_CON_DETAILS['APPLICATION_NO'])?$this->P_CON_DETAILS['APPLICATION_NO']:""),
										"CNSMR_NAME"=>(isset($this->P_CON_DETAILS['CNSMR_NAME'])?$this->P_CON_DETAILS['CNSMR_NAME']:""),
										"MOBILE_NO"=>(isset($this->P_CON_DETAILS['MOBILE_NO'])?$this->P_CON_DETAILS['MOBILE_NO']:""),
										"EMAIL_ID"=>(isset($this->P_CON_DETAILS['EMAIL_ID'])?$this->P_CON_DETAILS['EMAIL_ID']:""),
										"BANK_ACC_NO"=>(isset($this->P_CON_DETAILS['BANK_ACC_NO'])?$this->P_CON_DETAILS['BANK_ACC_NO']:""),
										"AC_HOLDER_NAME"=>(isset($this->P_CON_DETAILS['AC_HOLDER_NAME'])?$this->P_CON_DETAILS['AC_HOLDER_NAME']:""),
										"NAME_OF_BANK"=>(isset($this->P_CON_DETAILS['NAME_OF_BANK'])?$this->P_CON_DETAILS['NAME_OF_BANK']:""),
										"IFSC_CODE"=>(isset($this->P_CON_DETAILS['IFSC_CODE'])?$this->P_CON_DETAILS['IFSC_CODE']:""),
										"BRANCH_NAME"=>(isset($this->P_CON_DETAILS['BRANCH_NAME'])?$this->P_CON_DETAILS['BRANCH_NAME']:""));

			$P_IN_DATA 			= array("CLIENT_CD"		=> $this->P_CLIENT_CD,
										"DISCOM_CD"		=> $this->P_DISCOM_CD,
										"MODULE_CD"		=> $this->P_MODULE_CD,
										"SRV_CD"		=> $this->P_SRV_CD,
										"USR_NM"		=> USR_NM,
										"USR_PWD"		=> USR_PWD,
										"METHD_TYPE"	=> $this->P_METHOD_TYPE,
										"IN_DATA"		=> $IN_DATA,
										"FILE_TRN"		=> "0",
										"FTP_ID"		=> "0",
										"FTP_ID"		=> "0",
										"FILE_NAME"		=> "",
										"FTP_PATH"		=> "",
										"DT_TM"			=> $this->P_DT_TM,
										"AUTH_KEY" 		=> $this->P_AUTH_KEY);
		}

		$this->CallAPI($url, $P_IN_DATA);
	}
	/**
	 * CallAPI : Create a new job for web application in critical watch.
	 *
	 * Behaviour : Public
	 *
	 * @param  string  $function_url  URL of registered web application
	 * @throws Some_Exception_Class If something interesting cannot happen
	 * @return Retuns the critical watch RST API response after converting json string to readable PHP variable.
	 */
	public function CallAPI($url, $data_request)
	{
		
		$post_string   			= json_encode($data_request);
		$this->apiRequestVars	= $data_request;
		$str_imp 				= array();
		$str_data 				= '';
		if(($this->P_DISCOM_CD==5 || $this->P_DISCOM_CD==6 || $this->P_DISCOM_CD==7)) {
			foreach($data_request as $key=>$val)
			{
				$str_imp[]	= $key."=".$val;
			}
			if(!empty($str_imp))
			{
				$str_data 	= '?'.implode("&",$str_imp);
			}
			$this->IPADD 	= '103.233.170.222';
		}

		$conn 				= curl_init( $url.$str_data );
		// curl_setopt( $conn, CURLOPT_HTTPHEADER, array('Content-Type: application/json','X-Forwarded-For: '.$this->IPADD));
		curl_setopt( $conn, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt( $conn, CURLOPT_CONNECTTIMEOUT, 120 );
		curl_setopt( $conn, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $conn, CURLOPT_SSL_VERIFYHOST, 0 );
		curl_setopt( $conn, CURLOPT_RETURNTRANSFER, true );
		if(($this->P_DISCOM_CD!=5 && $this->P_DISCOM_CD!=6 && $this->P_DISCOM_CD!=7)) {
			curl_setopt( $conn, CURLOPT_POST, true );
			curl_setopt( $conn, CURLOPT_POSTFIELDS, $post_string);
		}

		$output 			= curl_exec( $conn );
		
		$Response   		= json_decode($output);
		if((isset($Response->P_OUT_DATA))) { 
			if (isset($Response->P_OUT_STS_CD))
			{
				$this->P_OUT_DATA 		= $Response->P_OUT_DATA;
				$this->P_OUT_STS_CD 	= $Response->P_OUT_STS_CD;
				$this->P_OUT_MSG_CLIENT = $Response->P_OUT_MSG_CLIENT;
				$this->P_OUT_MSG_SERVER = $Response->P_OUT_MSG_SERVER;
			}
			$this->P_OUT_DATA 			= $Response->P_OUT_DATA;
			if(empty($this->P_OUT_DATA) && isset($Response->P_OUT_STS_CD) && $Response->P_OUT_STS_CD == 1 && $this->apiaction == 'send_consumer_registration_details') {
				$this->P_OUT_DATA 		= 'Success';
			}
			if(isset($Response->P_OUT_DATA) && !empty($Response->P_OUT_DATA))
			{
				$this->P_OUT_STS_CD 	= $Response->P_OUT_STS_CD;
				$this->P_OUT_MSG_CLIENT = $Response->P_OUT_MSG_CLIENT;
				$this->P_OUT_MSG_SERVER = $Response->P_OUT_MSG_SERVER;
			}
		} else {
			if (isset($Response->OUT_STS_CD))
			{
				$this->P_OUT_DATA 		= $Response->OUT_DATA;
				$this->P_OUT_STS_CD 	= $Response->OUT_STS_CD;
				$this->P_OUT_MSG_CLIENT = $Response->OUT_MSG_CLIENT;
				$this->P_OUT_MSG_SERVER = $Response->OUT_MSG_SERVER;
			}
			if(empty($this->P_OUT_DATA) && isset($Response->P_OUT_STS_CD) && $Response->P_OUT_STS_CD == 1 && $this->apiaction == 'send_consumer_registration_details') {
				$this->P_OUT_DATA 		= 'Success';
				$this->P_OUT_MSG_CLIENT = $Response->P_OUT_MSG_CLIENT;
				$this->P_OUT_MSG_SERVER = $Response->P_OUT_MSG_SERVER;
				$this->P_OUT_STS_CD 	= $Response->P_OUT_STS_CD;
			}
		}
		
		
		
		/*OUT {"OUT_DATA":"","OUT_STS_CD":"21","OUT_MSG_CLIENT":"OVER USAGE","OUT_MSG_SERVER":"OVER USAGE APP, PLEASE CONTACT ADMINISTRATOR","OUT_FILE_ID":"0"} {"OUT_DATA":"","OUT_STS_CD":"21","OUT_MSG_CLIENT":"OVER USAGE","OUT_MSG_SERVER":"OVER USAGE APP, PLEASE CONTACT ADMINISTRATOR","OUT_FILE_ID":"0"}*/
		
		if ($this->P_OUT_STS_CD != '')
		{
			$this->apiResponse = array(	"P_OUT_STS_CD"=>$this->P_OUT_STS_CD,
										"P_OUT_DATA"=>$this->P_OUT_DATA,
										"P_OUT_MSG_CLIENT"=>$this->P_OUT_MSG_CLIENT,
										"P_OUT_MSG_SERVER"=>$this->P_OUT_MSG_SERVER,
										"apiaction"=>$this->apiaction,
										"apitimestamp"=>$this->apitimestamp,
										"apirequest"=>$this->apirequest,
										"APIURL"	=>$url.$str_data);
		} else {
			$this->apiResponse = array(	"P_OUT_STS_CD"=>0,
										"P_OUT_DATA"=>array(),
										"P_OUT_MSG_CLIENT"=>$this->INVALID_API_MESSAGE,
										"P_OUT_MSG_SERVER"=>$this->P_OUT_MSG_SERVER,
										"apiaction"=>$this->apiaction,
										"apitimestamp"=>$this->apitimestamp,
										"apirequest"=>$this->apirequest,
										"APIURL"	=>$url.$str_data);
		}
	}
	public function SendSubsidyDetails()
	{
		$url    			= $this->GUVNL_API_URL;
		if($this->P_DISCOM_CD==5 || $this->P_DISCOM_CD==6 || $this->P_DISCOM_CD==7) {
			$url    		= $this->TPL_API_URL;
			$P_IN_DATA 		= array("P_IN_DATA"		=> $this->P_CON_NO,
									"P_SRV_CD"		=> $this->P_SRV_CD,
									"P_CLIENT_CD"	=> $this->P_CLIENT_CD,
									"P_DISCOM_CD"	=> $this->P_DISCOM_CD,
									"P_DT_TM"		=> $this->P_DT_TM,
									"P_AUTH_KEY"	=> $this->P_AUTH_KEY,
									"P_IN_DATA2"	=> $this->P_T_NO);
		} else {

			$IN_DATA 			= array("CLAIM_NO"=>(isset($this->P_CON_DETAILS['CLAIM_NO'])?$this->P_CON_DETAILS['CLAIM_NO']:""),
									"CLAIM_DATE"=>(isset($this->P_CON_DETAILS['CLAIM_DATE'])?$this->P_CON_DETAILS['CLAIM_DATE']:""),
									"DIVISION_CODE"=>(isset($this->P_CON_DETAILS['DIVISION_CODE'])?$this->P_CON_DETAILS['DIVISION_CODE']:""),
									"DIVISION_NAME"=>(isset($this->P_CON_DETAILS['DIVISION_NAME'])?$this->P_CON_DETAILS['DIVISION_NAME']:""),
									"NET_AMOUNT"=>(isset($this->P_CON_DETAILS['NET_AMOUNT'])?$this->P_CON_DETAILS['NET_AMOUNT']:""),
									"VENDOR_CODE"=>(isset($this->P_CON_DETAILS['VENDOR_CODE'])?$this->P_CON_DETAILS['VENDOR_CODE']:""),
									"VENDOR_NAME"=>(isset($this->P_CON_DETAILS['VENDOR_NAME'])?$this->P_CON_DETAILS['VENDOR_NAME']:""),
									"DESCRIPTION"=>(isset($this->P_CON_DETAILS['DESCRIPTION'])?$this->P_CON_DETAILS['DESCRIPTION']:""),
									"GROSS_ACCOUNT"=>(isset($this->P_CON_DETAILS['GROSS_ACCOUNT'])?$this->P_CON_DETAILS['GROSS_ACCOUNT']:""),
									"GROSS_AMOUNT"=>(isset($this->P_CON_DETAILS['GROSS_AMOUNT'])?$this->P_CON_DETAILS['GROSS_AMOUNT']:""),
									"PBG_ACCOUNT"=>(isset($this->P_CON_DETAILS['PBG_ACCOUNT'])?$this->P_CON_DETAILS['PBG_ACCOUNT']:""),
									"PBG_AMOUNT"=>(isset($this->P_CON_DETAILS['PBG_AMOUNT'])?$this->P_CON_DETAILS['PBG_AMOUNT']:""),
									"PENALTY_ACCOUNT"=>(isset($this->P_CON_DETAILS['PENALTY_ACCOUNT'])?$this->P_CON_DETAILS['PENALTY_ACCOUNT']:""),
									"PENALTY_AMOUNT"=>(isset($this->P_CON_DETAILS['PENALTY_AMOUNT'])?$this->P_CON_DETAILS['PENALTY_AMOUNT']:""),
									"SANCTION_ID"=>(isset($this->P_CON_DETAILS['SANCTION_ID'])?$this->P_CON_DETAILS['SANCTION_ID']:""),
									"Schyear"=>(isset($this->P_CON_DETAILS['Schyear'])?$this->P_CON_DETAILS['Schyear']:""));

			$P_IN_DATA 			= array("CLIENT_CD"		=> $this->P_CLIENT_CD,
										"DISCOM_CD"		=> $this->P_DISCOM_CD,
										"MODULE_CD"		=> $this->P_MODULE_CD,
										"SRV_CD"		=> $this->P_SRV_CD,
										"USR_NM"		=> USR_NM,
										"USR_PWD"		=> USR_PWD,
										"METHD_TYPE"	=> $this->P_METHOD_TYPE,
										"IN_DATA"		=> $IN_DATA,
										"FILE_TRN"		=> "0",
										"FTP_ID"		=> "0",
										"FILE_NAME"		=> "",
										"FTP_PATH"		=> "",
										"DT_TM"			=> $this->P_DT_TM,
										"AUTH_KEY" 		=> $this->P_AUTH_KEY);
		}
		$this->CallAPI($url, $P_IN_DATA);
	}
	public function GetSubsidyPaymentDetails()
	{
		$url    			= $this->GUVNL_API_URL;
		if($this->P_DISCOM_CD==5 || $this->P_DISCOM_CD==6 || $this->P_DISCOM_CD==7) {
			$url    		= $this->TPL_API_URL;
			$P_IN_DATA 		= array("P_IN_DATA"		=> $this->P_CON_NO,
									"P_SRV_CD"		=> $this->P_SRV_CD,
									"P_CLIENT_CD"	=> $this->P_CLIENT_CD,
									"P_DISCOM_CD"	=> $this->P_DISCOM_CD,
									"P_DT_TM"		=> $this->P_DT_TM,
									"P_AUTH_KEY"	=> $this->P_AUTH_KEY,
									"P_IN_DATA2"	=> $this->P_T_NO);
		} else {

			$IN_DATA 			= array("CLAIM_NO"=>(isset($this->P_CON_DETAILS['CLAIM_NO'])?$this->P_CON_DETAILS['CLAIM_NO']:""));

			$P_IN_DATA 			= array("CLIENT_CD"		=> $this->P_CLIENT_CD,
										"DISCOM_CD"		=> $this->P_DISCOM_CD,
										"MODULE_CD"		=> $this->P_MODULE_CD,
										"SRV_CD"		=> $this->P_SRV_CD,
										"USR_NM"		=> USR_NM,
										"USR_PWD"		=> USR_PWD,
										"METHD_TYPE"	=> $this->P_METHOD_TYPE,
										"IN_DATA"		=> $IN_DATA,
										"FILE_TRN"		=> "0",
										"FTP_ID"		=> "0",
										"FILE_NAME"		=> "",
										"FTP_PATH"		=> "",
										"DT_TM"			=> $this->P_DT_TM,
										"AUTH_KEY" 		=> $this->P_AUTH_KEY);
		}
		$this->CallAPI($url, $P_IN_DATA);
	}

	public function GetConsumerGenerationData()
	{
		$url    			= $this->GUVNL_API_URL;
		if($this->P_DISCOM_CD==5 || $this->P_DISCOM_CD==6 || $this->P_DISCOM_CD==7)
		{
			$url    		= $this->TPL_API_URL;
			$P_IN_DATA 		= array("P_IN_DATA"		=> array("INPUT_DATA"=>array("cnsmr_no"=>$this->P_CON_NO,"YEAR"=>$this->P_YEAR)),
									"P_SRV_CD"		=> $this->P_SRV_CD,
									"P_CLIENT_CD"	=> $this->P_CLIENT_CD,
									"P_DISCOM_CD"	=> $this->P_DISCOM_CD,
									"P_DT_TM"		=> $this->P_DT_TM,
									"P_AUTH_KEY"	=> $this->P_AUTH_KEY,
									"P_IN_DATA2"	=> $this->P_T_NO);
		} else {
			$P_IN_DATA 		= array("CLIENT_CD"		=> $this->P_CLIENT_CD,
									"DISCOM_CD"		=> $this->P_DISCOM_CD,
									"MODULE_CD"		=> $this->P_MODULE_CD,
									"SRV_CD"		=> $this->P_SRV_CD,
									"USR_NM"		=> USR_NM,
									"USR_PWD"		=> USR_PWD,
									"METHD_TYPE"	=> $this->P_METHOD_TYPE,
									"IN_DATA"		=> array("CNSMR_NO"=>$this->P_CON_NO,"YEAR"=>$this->P_YEAR),
									"FILE_TRN"		=> "0",
									"FTP_ID"		=> "0",
									"FTP_ID"		=> "0",
									"FILE_NAME"		=> "",
									"FTP_PATH"		=> "",
									"DT_TM"			=> $this->P_DT_TM,
									"AUTH_KEY" 		=> $this->P_AUTH_KEY);
		}
		$this->apirequest	= $P_IN_DATA;
		$this->CallAPI($url, $P_IN_DATA);
	}
}
