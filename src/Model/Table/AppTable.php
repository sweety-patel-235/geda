<?php

/**
 * Provides a stub for loading shared behavior among all Tables.
 *
 * Child Tables should call `parent::initialize()` first.
 */

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table as CakeORMTable;
use Cake\Validation\Validator;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\View\View;
use Dompdf\Dompdf;
use Cake\Datasource\ConnectionManager;
use Couchdb\Couchdb;

/**
 * App Model
 *
 */
class AppTable extends CakeORMTable
{

	/**
	 *
	 * The status of $arrError is universe
	 *
	 * Potential value are Error Strings (Identify Common Errors)
	 *
	 * @var Array
	 *
	 */
	public $arrError = array();

	/**
	 * The status of $AUTO_PROCESS_LOG is universe
	 * Potential value is false (identify for saving auto process log in table)
	 * @var boolean
	 */
	public $AUTO_PROCESS_LOG = false;

	/**
	 * The status of $arrModels is universe
	 * Potential value are model object (Identify Different model Object)
	 * @var Object
	 */
	public $arrModels = array();

	/**
	 * The status of $ACTIVE_STATUS is universe
	 * Potential value are model object (Identify Different model Object)
	 * @var Object
	 */
	public $ACTIVE_STATUS = 1;

	/**
	 * The status of $INACTIVE_STATUS is universe
	 * Potential value are model object (Identify Different model Object)
	 * @var Object
	 */
	public $INACTIVE_STATUS = 0;

	/**
	 * The status of $SERVER_TIMEZONE is universe
	 * Potential value are "UTC" (Identify timezone of SERVER)
	 * @public String
	 */
	public $SERVER_TIMEZONE = "UTC";
	/**
	 * The status of $SERVER_TIMEZONE_UTC is universe
	 * Potential value are "+00:00" (Identify timezone of SERVER)
	 * @public String
	 */
	public $SERVER_TIMEZONE_UTC = "+00:00";

	// Jitendra - 20-09-2014 START
	public $message				= '';
	public $customer_id			= 0;

	public $CUSTOMER_PARA_STATUS_PENDING				= 1001;
	public $CUSTOMER_PARA_STATUS_ACTIVE					= 1002;
	public $CUSTOMER_PARA_STATUS_INACTIVE				= 1003;
	public $CUSTOMER_PARA_STATUS_VERIFICATION_PENDING	= 1004;

	public $PLATFORM_MOBILE					= 'M';
	public $PLATFORM_WEB					= 'W';
	// Jitendra - 20-09-2014 END

	// This value will be always set if device mismatch.
	public $device_mismatch = false;
	public $device_not_found = false;
	public $device_id		= "";

	//added by kalpak for managing Retailer Recharge users and Default timezone for all admin
	public $DEFAULT_ADMINUSER_TIMEZONE 	= "+5.5";
	public $arrFirmDropdown = array("Individual" => "Individual", "Proprietary Firm" => "Proprietary Firm", "Partnership" => "Partnership", "Public Ltd." => "Public Ltd.", "Private Limited" => "Private Limited", "Group of Company" => "Group of Company", "LLP" => "LLP", "Other" => "Other");
	public $arrDesignation 		= array("Chairman" => "Chairman", "Managing Director" => "Managing Director", "CEO" => "CEO", "Partner" => "Partner", "CMD" => "CMD", "Others" => "Others");

	//public $arrInjectionLevel 	= array("1"=>"Below 11 kV","2"=>"11 kV","3"=>"66 kV","4"=>"Above 66 kV");
	public $arrInjectionLevel 	= array("1" => "Below 11 kV", "2" => "11 kV", "3" => "66 kV", "4" => "132 kV", "5" => "220 kV", "6" => "400 kV"); //New As per GUVNL
	public $arrGridLevel 		= array("1" => "State Transmission Utility (STU)", "2" => "Central Transmission Utility (CTU)");
	public $arrEndSTU 			= array("1" => "Captive Use", "2" => "Third Party Sale", "3" => "Sale to DISCOM", "4" => "Proto Type");
	public $arrEndCTU 			= array("1" => "Open Access", "2" => "Bid Winner", "3" => "Proto Type");
	/**
	 * Initialize method
	 *
	 * @param array $config The configuration for the Table.
	 * @return void
	 */
	public function initialize(array $config)
	{
		parent::initialize($config);
	}
	/**
	 *
	 * findConditionByPeriod
	 *
	 * Behaviour : Public
	 *
	 * @param : $date		: On base of date condition is searched
	 * @param : $period		: on base of specific period different condition or array is build
	 * @param : $DateFrom   : Start Date
	 * @param : $DateTo		: End Date
	 * @param : $TimeZone	: TimeZone for User
	 * @return :  array of final condition
	 * @defination :  Method is use to built condition on base of all the passed argument
	 *
	 */
	public function findConditionByPeriod($date, $period, $DateFrom, $DateTo, $TimeZone = 0)
	{
		$arrCondition = array();
		if ($period == 3) {
			if (!empty($DateFrom) && !empty($DateTo) && trim($DateFrom) != "00-00-0000" && trim($DateTo) != "00-00-0000") {
				$strStartDate	= $this->setDBTimezoneDate(date("Y-m-d", strtotime($DateFrom)) . " 00:00:00", "Y-m-d H:i:s", $TimeZone);
				$strEndDate		= $this->setDBTimezoneDate(date("Y-m-d", strtotime($DateTo)) . " 23:59:59", "Y-m-d H:i:s", $TimeZone);
				//$arrCondition	= array($date.' BETWEEN ? AND ? ' => array($strStartDate,$strEndDate));
				//$arrCondition	= array($date.' >= ' => $strStartDate,$date.' <= ' => $strEndDate);
				$arrCondition['between']	= array($date, $strStartDate, $strEndDate);
			}
			/*elseif (!empty($DateFrom) && empty($DateTo) && trim($DateFrom) != "00-00-0000" && trim($DateTo) == "00-00-0000")
			{
                $strDate		= $this->setDBTimezoneDate(date("Y-m-d",strtotime($DateFrom))." 00:00:00","Y-m-d H:i:s",$TimeZone);
                $arrCondition	= array($date.' > ' => $strDate);
			}
            elseif (empty($DateFrom) && !empty($DateTo) && trim($DateFrom) == "00-00-0000" && trim($DateTo) != "00-00-0000")
			{
                $strDate		= $this->setDBTimezoneDate(date("Y-m-d",strtotime($DateTo))." 23:59:59","Y-m-d H:i:s",$TimeZone);
				$arrCondition	= array($date.' < ' => $strDate);
			}*/
		} elseif ($period == 1) {
			$strDate		= date('Y-m-d');
			$strStartDate	= $this->setDBTimezoneDate($strDate . " 00:00:00", "Y-m-d H:i:s", $TimeZone);
			$strEndDate		= $this->setDBTimezoneDate($strDate . " 23:59:59", "Y-m-d H:i:s", $TimeZone);
			//$arrCondition	= array($date.' BETWEEN ? AND ? ' => array($strStartDate,$strEndDate));
			//$arrCondition	= array($date.' >= ' => $strStartDate,$date.' <= ' => $strEndDate);
			$arrCondition['between']	= array($date, $strStartDate, $strEndDate);
		} elseif ($period == 2) {
			$strDate		= date('Y-m-d', strtotime("yesterday"));
			$strStartDate	= $this->setDBTimezoneDate($strDate . " 00:00:00", "Y-m-d H:i:s", $TimeZone);
			$strEndDate		= $this->setDBTimezoneDate($strDate . " 23:59:59", "Y-m-d H:i:s", $TimeZone);
			//$arrCondition	= array($date.' BETWEEN ? AND ? ' => array($strStartDate,$strEndDate));
			//$arrCondition	= array($date.' BETWEEN ' => array($strStartDate,$strEndDate));
			//$arrCondition	= array($date.' >= ' => $strStartDate,$date.' <= ' => $strEndDate);
			$arrCondition['between']	= array($date, $strStartDate, $strEndDate);
		}
		return $arrCondition;
	}

	/**
	 *
	 * setDBTimezoneDate
	 *
	 * Behaviour : Public
	 *
	 * @param : $date		: Date for which Database timezone to be seted
	 * @param : $format		: Format in which timezone to be seted
	 * @param : $TimeZone	: TimeZone to be used in which date is to be converted
	 * @return :  Date with converted time zone
	 * @defination :  Method is use to set time zone base on date as input
	 *
	 */
	public function setDBTimezoneDate($date, $format = "Y-m-d H:i:s", $TimeZoneId = 0)
	{
		if ($date == "" || $date == "0000-00-00 00:00:00") return $date;
		/*App::uses("CakeTime", "Utility");
		$TimeZone	= str_replace(":", ".",$TimeZone);
		$int		= (int)$TimeZone;
		$pos		= abs($int-	$TimeZone);
		$pos		= ($pos/60)*100;
		$TimeZone	= $int+$pos;
		return CakeTime::format($format, $date, true, -($TimeZone));*/
		//App::import("model","TimeZone");
		/*$TimeZone		= new TimeZone();
		$timezone 		= $TimeZone->getTimeZoneById($TimeZoneId);*/

		$query = $this->find('all', array('fields' => ['timezone.id', 'timezone.name', 'timezone.utc_name', 'timezone.short_name', 'timezone.horus_name', 'timezone.iana_timezone_id']))->join([
			'timezone' => [
				'table' => 'time_zones',
				'type' => 'LEFT',
				'conditions' => [
					'timezone.id' => $TimeZoneId
				]
			],
		])->limit(1)->toArray();

		$timezone 		= $query;
		$timezone_iana 	= $timezone[0]->timezone['iana_timezone_id'];
		$time_zone 		= isset($timezone[0]->timezone['utc_name']) ? $timezone[0]->timezone['utc_name'] : "";
		$time_zone		= str_replace("UTC", "", (empty($time_zone) ? "0" : $time_zone));
		$isDst			= 1;
		$dateTz			= ConvertDateTimeWebToDB($date, "UTC", $format, $timezone_iana, $format, $isDst, "00:00");
		return $dateTz;
	}

	/**
	 * getDSTTimeZoneEffect
	 * Behaviour : Public
	 * @param : $DateTime		: Selected Date and time
	 * @param : $TimeZone		: Timezone for which needs to check for DST Effect
	 * @param : $DSTStart_Day	: DSTStart_Day from where the DST Effect is Starts in calander year
	 * @param : $DSTEnd_Day		: DSTEnd_Day from where the DST Effect is Ends in calander year
	 * @param : $DSTStart_Month	: DSTStart_Month from where the DST Effect is Starts in calander year
	 * @param : $DSTEnd_Month	: DSTEnd_Month from where the DST Effect is Starts in calander year
	 * @param : $DSTDiff		: DSTDiff Difference in hours
	 * @return: $NewDate		: Date with converted time zone
	 * @defination				:  Method is use to convert the selected Date with DST Effect.
	 */
	public function getDSTTimeZoneEffect($DateTime, $TimeZone, $DSTStart_Day, $DSTEnd_Day, $DSTStart_Month, $DSTEnd_Month, $DSTDiff)
	{
		$DebugVars		= false;
		$UserTimeZone	= new DateTimeZone($TimeZone);
		$Current_TS		= strtotime($DateTime);
		$transition		= $UserTimeZone->getTransitions($Current_TS, $Current_TS);
		$offset			= $transition[0]['offset'];
		$isdst			= $transition[0]['isdst'];
		$abbr			= $transition[0]['abbr'];
		$NewDate		= "";
		if ($isdst) {
			if (!ctype_digit($DSTStart_Day)) {
				$DSTStart_TS	= strtotime("$DSTStart_Day of $DSTStart_Month", strtotime("last day of $DSTStart_Month", time()));
			} else {
				$DSTStart_Month = date("m", strtotime(date("Y") . "-" . $DSTStart_Month . "-" . $DSTStart_Day . " 00:00:00"));
				$DSTStart_TS	= strtotime(date("Y") . "-" . $DSTStart_Month . "-" . $DSTStart_Day . " 00:00:00");
			}
			if (!ctype_digit($DSTEnd_Day)) {
				$DSTEnd_TS		= strtotime("$DSTEnd_Day of $DSTEnd_Month", strtotime("last day of $DSTEnd_Month", time()));
			} else {
				$DSTEnd_Month	= date("m", strtotime(date("Y") . "-" . $DSTEnd_Month . "-" . $DSTEnd_Day . " 23:59:59"));
				$DSTEnd_TS		= strtotime(date("Y") . "-" . $DSTEnd_Month . "-" . $DSTEnd_Day . " 23:59:59");
			}
			if ($DebugVars) {
				echo "<br />Offset ==> " . $offset;
				echo "<br />TimeZone Abbrivation ==> " . $abbr;
				echo "<br />Day Light Start ==> " . date("Y-m-d H:i:s", $DSTStart_TS);
				echo "<br />Day Light Ends ==> " . date("Y-m-d H:i:s", $DSTEnd_TS);
			}
			if ($Current_TS > $DSTStart_TS) {
				$New_TS = strtotime("+" . $DSTDiff . " HOUR", $Current_TS);
				if ($DebugVars) echo "<br />Hour Diff ==> (+)" . $DSTDiff;
			}
			if ($DebugVars) {
				echo "<br />Current DateTime ==>" . date("Y-m-d H:i:s", $Current_TS);
				echo "<br />New DateTime ==>" . date("Y-m-d H:i:s", $New_TS);
			}
		} else {
			$New_TS = $Current_TS;
			if ($DebugVars) echo "<br />NO DST Effect for Given TimeZone ==> " . $TimeZone . " DateTime ==> " . date("Y-m-d H:i:s", $Current_TS);
		}
		if ($DebugVars) echo "<br />";
		$NewDate = date("Y-m-d H:i:s", $New_TS);
		return $NewDate;
	}

	/**
	 *
	 * alphaNumWithSpChar
	 *
	 * Behaviour : Public
	 *
	 * @defination : Use for AlphaNumeric With Special Character Validation
	 *
	 */
	public function alphaNumWithSpChar($check)
	{
		$value = array_values($check);
		$value = $value[0];

		return preg_match("|^[-0-9a-zA-Z_&'\s]*$|", $value);
	}

	/**
	 *
	 * setSearchDateParameter
	 *
	 * Behaviour : Public
	 *
	 * @param : $period  : is use to create search condition array base on date parameter i.e search from and search to
	 * @param : $model   : if not blank is use to set parameter for specific model
	 * @return :  array of date parameter built
	 * @defination :  method is use to built date parameter array base on parameter passed as argument
	 *
	 */
	public function setSearchDateParameter($period, $model = "")
	{
		$arrDataParameter = array();
		if ($period == 1) {
			if ($model != "") {
				$arrDataParameter[$model]['DateFrom']	= date('d-m-Y');
				$arrDataParameter[$model]['DateTo']		= date('d-m-Y');
			} else {
				$arrDataParameter['DateFrom']	= date('d-m-Y');
				$arrDataParameter['DateTo']		= date('d-m-Y');
			}
		} else if ($period == 2) {
			if ($model != "") {
				$arrDataParameter[$model]['DateFrom']	= date('d-m-Y', strtotime("yesterday"));
				$arrDataParameter[$model]['DateTo']		= date('d-m-Y', strtotime("yesterday"));
			} else {
				$arrDataParameter['DateFrom']	= date('d-m-Y', strtotime("yesterday"));
				$arrDataParameter['DateTo']		= date('d-m-Y', strtotime("yesterday"));
			}
		}
		return $arrDataParameter;
	}

	/**
	 *
	 * getProcessIDsForRunningScript
	 *
	 * Behaviour : Public
	 *
	 * @param : $filename_string : pass the file name as string
	 * @return : its return as array
	 * @defination : this method get the running script as Array
	 *
	 */
	public function getProcessIDsForRunningScript($filename_string)
	{
		$tmparr = array();

		ob_start();
		system(" ps ax|grep '" . trim($filename_string) . "'| grep -v 'grep' | awk '{print$1}'");
		$cmdoutput = ob_get_contents();
		ob_end_clean();

		$cmdoutput = trim($cmdoutput);
		if (empty($cmdoutput)) return $tmparr;

		$cmdoutput = preg_replace("#\n#", ",", trim($cmdoutput));
		$tmparr = explode(",", $cmdoutput);
		return $tmparr;
	}

	/**
	 *
	 * _getIPAddressFromURL
	 *
	 * Behaviour : Public
	 *
	 * @param : $arrURL : Array of the URL for which IP address need to get.
	 * @param : $arrNetworkip : IP address if already passed at the time of registstion.
	 * @return : This method will return IP address of the passed URLs.
	 * @defination : This method will return the IPaddress of the passed URLs.
	 *
	 */
	function _getIPAddressFromURL($arrURL, $arrNetworkip = array())
	{
		if (!is_array($arrURL)) {
			return $this->admin_getip($arrURL, true);
		} else {
			if (count($arrURL) == count($arrNetworkip))
				return $arrNetworkip;
			else {
				foreach ($arrURL as $id => $url) {
					if (isset($arrNetworkip[$id]))
						continue;
					else
						$arrNetworkip[$id] = $this->admin_getip($url, true);
				}
			}
		}
		return $arrNetworkip;
	}

	/**
	 *
	 * admin_getip
	 *
	 * Behaviour : Public
	 *
	 * @param : $url  : Pass the URL value
	 * @param : $responsetype   : Pass the response type
	 * @return :  its return IP address as string
	 * @defination : this method find the host name based on passing URL parameters and its return IP address.
	 *
	 */
	function admin_getip($url, $responsetype = null)
	{
		if (strpos($url, "http:") == -1 && strpos($url, "https:") == -1)
			$url = "http://" . $url;

		$data = parse_url($url);

		if (!empty($data['host'])) {
			$host = $data['host'];
		} else {
			$host = $data['path'];
		}
		$ip = rtrim(`/usr/bin/dig $host A +short | /usr/bin/tail -1`);
		if (empty($ip))
			$ip = $data['host'];

		if (!isset($responsetype))
			echo $ip;
		else
			return $ip;
	}

	/**
	 * destroyUserSession($Session);
	 * Behaviour	: public
	 * @param		: string $Session
	 * @defination	: This method will delete user session.
	 */
	public function destroyUserSession($Session)
	{
		$Session->delete("User");
		$Session->delete("Account");
	}

	/**
	 * rand_string();
	 * Behaviour	: public
	 * @return		: string returns random length random string.
	 * @defination	: This method will generate random string with random length between 40 to 50 characters and returns generated string.
	 */
	public function rand_string()
	{
		$length  = rand(40, 50);
		$str = "";
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$size = strlen($chars);
		for ($i = 0; $i < $length; $i++) {
			$str .= $chars[rand(0, $size - 1)];
		}
		return $str;
	}

	/**
	 * makeModelObject
	 * Behaviour	: public
	 * @param		: string $ModelName
	 * @return		: object
	 * @defination	: This method used for generating modal object
	 */
	public function makeModelObject($ModelName = null)
	{
		if ($ModelName == null) return null;
		if (empty($this->arrModels[$ModelName])) {
			App::uses($ModelName, "Model");
			$this->arrModels[$ModelName] = new $ModelName();
		}
		return $this->arrModels[$ModelName];
	}

	public function getMasterCode($Prefix = "")
	{
		if (empty($Prefix)) return "";
		$this->MasterCode	= $this->makeModelObject('MasterCode');
		$CodeRow			= $this->MasterCode->find("first", array("conditions" => array("MasterCode.prefix" => $Prefix)));
		if (!empty($CodeRow)) {
			$MasterCode				= $Prefix . $CodeRow['MasterCode']['code_value'];
			$this->MasterCode->updateAll(
				array('MasterCode.code_value' => ($CodeRow['MasterCode']['code_value'] + 1)),
				array('MasterCode.id' => $CodeRow['MasterCode']['id'])
			);
			return $MasterCode;
		}
		return "";
	}

	public function GenerateHash($data)
	{
		$content		= json_encode($data);
		//echo "===>".$content."<===";
		$hash = hash_hmac('sha256', $content, HMAC_HASH_PRIVATE_KEY);
		return $hash;
	}

	public function isUniqueValue($field, $value, $id = 0)
	{
		$fields[$this->name . '.' . $field] = $value;
		if (!empty($id)) $fields[$this->name . '.id'] = "<> $id";
		$this->recursive = -1;
		if ($this->hasAny($fields)) {
			$this->invalidate('unique_' . $field);
			return false;
		} else {
			return true;
		}
	}

	/**
	 * now
	 * Behaviour : Public
	 * @return : date
	 * @defination : Method is get the current date and time
	 */
	public function NOW()
	{
		return date("Y-m-d H:i:s");
	}

	/**
	 * EncryptPassword
	 * Behaviour : Public
	 * @return : date
	 * @defination : Method is to encrypt password
	 */
	public function EncryptPassword($pin)
	{
		return md5($pin);
	}

	public function ValidateMobileNumber($mobile)
	{
		if (preg_match('/^((\+){0,1}91(\s){0,1}(\-){0,1}(\s){0,1})?([0-9]{10})$/', $mobile, $matches)) {
			//print_r($matches);
			return true;
		} else {
			return false;
		}
	}

	public function ValidateDTH($mobile)
	{
		return true;
		if (preg_match('/^((\+){0,1}91(\s){0,1}(\-){0,1}(\s){0,1})?([0-9]{10})$/', $mobile, $matches)) {
			return true;
		} else {
			return false;
		}
	}

	public function ConvertRewardPointsToAmount($reward_points = 0, $collection_user = false)
	{

		if (empty($reward_points)) return 0;

		$Value = ($collection_user) ? COLLECTION_USER_POINT : NON_COLLECTION_USER_POINT;

		$amount = $reward_points / $Value;

		return number_format($amount, 2);
	}

	public function ConvertAmountToRewardPoints($amount = 0, $collection_user = false)
	{

		if (empty($amount)) return 0;

		$Value = ($collection_user) ? COLLECTION_USER_POINT : NON_COLLECTION_USER_POINT;

		$reward_points = $amount * $Value;

		return ceil($reward_points);
	}

	public function Send_Push_Notification($registatoin_ids, $message)
	{
		// Set POST variables
		$url 		= "https://android.googleapis.com/gcm/send";
		$fields 	= array("registration_ids" => array($registatoin_ids), "data" => array("price" => $message));
		_d($fields);
		$headers 	= array('Authorization: key=AIzaSyC1nlckZ5c_DhI9RMi4eFIDD4dTq63-wHM', 'Content-Type: application/json');
		// Open connection
		$ch 		= curl_init();
		// Set the url, number of POST vars, POST data
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// Disabling SSL Certificate support temporarly
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		// Execute post
		$result = curl_exec($ch);
		if ($result === FALSE) {
			//die('Curl failed: ' . curl_error($ch));
			return false;
		}
		// Close connection
		curl_close($ch);
		//echo $result;
		$response = json_decode($result, true);
		_d($response);
		if (isset($response['results'][0]['error'])) {
			return false;
		} else {
			return true;
		}
	}

	public function encrypt($data, $key, $encmode = 'ECB')
	{

		$cypher = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, '');
		switch ($encmode) {
			case 'ECB':
				$cypher = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, '');
				break;
			case 'CBC':
				$cypher = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
				break;
			case 'CFB':
				$cypher = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CFB, '');
				break;
			case 'OFB':
				$cypher = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_OFB, '');
				break;
			default:
				$cypher = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, '');
				break;
		}
		// initialize encryption handle
		if (@mcrypt_generic_init($cypher, $key, '') != -1) {
			// decrypt
			$encrypted = mcrypt_generic($cypher, $data); // ( mdecrypt_generic($cypher, $data);

			// clean up
			mcrypt_generic_deinit($cypher);
			mcrypt_module_close($cypher);

			return strtr(base64_encode($encrypted), '+/=', '-_,');
			//return $encrypted;
		}
		return false;
	}

	public function decrypt($data, $key, $encmode = 'ECB')
	{
		$return_data = '';
		$cypher = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, '');
		switch ($encmode) {
			case 'ECB':
				$cypher = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, '');
				break;
			case 'CBC':
				$cypher = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
				break;
			case 'CFB':
				$cypher = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CFB, '');
				break;
			case 'OFB':
				$cypher = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_OFB, '');
				break;
			default:
				$cypher = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, '');
				break;
		}
		// initialize encryption handle
		if (@mcrypt_generic_init($cypher, $key, '') != -1) {
			// decrypt
			if ($data != '')
				$return_data = mdecrypt_generic($cypher, base64_decode(strtr($data, '-_,', '+/=')));
			else
				$return_data['strreplacestring_decryptedstring'] = '';

			// clean up
			mcrypt_generic_deinit($cypher);
			mcrypt_module_close($cypher);

			return $return_data;
		}
		return false;
	}
	/**
	 * checks is the field value is unqiue in the table
	 * note: we are overriding the default cakephp isUnique test as the original appears to be broken
	 *
	 * @param string $data Unused ($this->data is used instead)
	 * @param mnixed $fields field name (or array of field names) to validate
	 * @return boolean true if combination of fields is unique
	 */
	function checkUnique($data, $fields)
	{

		if (!is_array($fields)) {
			$fields = array($fields);
		}
		foreach ($fields as $key) {
			$tmp[$key] = $this->data[$this->name][$key];
		}
		if (isset($this->data[$this->name][$this->primaryKey])) {
			$tmp[$this->primaryKey] = "<>" . $this->data[$this->name][$this->primaryKey];
		}
		return $this->isUnique($tmp, false);
	}

	public function validateEmail($email)
	{
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return true;
		} else {
			return false;
		}
	}

	public function getLastQuery()
	{
		$dbo = $this->getDatasource();
		$logs = $dbo->getLog();
		$lastLog = end($logs['log']);
		return $lastLog['query'];
	}
	public function sendSMS($application_id, $mobile, $message, $type = "")
	{

		$ConnectionManager	= ConnectionManager::get('default');
		$TEMPLATE_ID_SQL	= "	SELECT sms_template_mapping.template_id
									FROM sms_template_mapping
									WHERE sms_template_mapping.sms_template ='" . $type . "'";

		$TEMPLATE_ID_RES 	= $ConnectionManager->execute($TEMPLATE_ID_SQL)->fetchAll('assoc');

		$TEMPLATE_ID 		= isset($TEMPLATE_ID_RES[0]['template_id']) ? $TEMPLATE_ID_RES[0]['template_id'] : "";

		$MESSAGE           = urlencode($message);
		$FIND_ARRAY        = array("[SMS_USER]", "[SMS_PASS]", "[MESSAGE]", "[MOBILE]", "[TEMPLATE_ID]");
		$REPL_ARRAY        = array(SMS_USER, SMS_PASS, $MESSAGE, $mobile, $TEMPLATE_ID);
		$SMS_GATEWAY_URL   = str_replace($FIND_ARRAY, $REPL_ARRAY, SMS_GATWAY_URL);
		$ch                = curl_init($SMS_GATEWAY_URL);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);             // DO NOT RETURN HTTP HEADERS
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   // RETURN THE CONTENTS
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
		$SMS_RSEPONSE        = curl_exec($ch);
		$application_sms_log                    = TableRegistry::get('ApplicationSmsLog');
		$application_smsEntity                  = $application_sms_log->newEntity();
		$application_smsEntity->application_id  = $application_id;
		$application_smsEntity->mobile          = $mobile;
		$application_smsEntity->sms_request     = $SMS_GATEWAY_URL;
		$application_smsEntity->sms_response    = $SMS_RSEPONSE;
		$application_smsEntity->created         = $this->NOW();
		$application_sms_log->save($application_smsEntity);
	}
	/**
	 *
	 * donwload_view_docs
	 *
	 * Behaviour : Public
	 *
	 *@param : pass type and encrypted id of application/document
	 *
	 * @defination : Method is use to download/view image and document attached with application.
	 *
	 */
	public function donwload_view_docs($type, $id)
	{

		$Couchdb 		= TableRegistry::get('Couchdb');
		$ReCouchdb 		= TableRegistry::get('ReCouchdb');
		require_once(ROOT . DS . 'vendor' . DS . 'couchdb' . DS . 'couchdb.php');
		$COUCHDB 		= new Couchdb();
		$doc_id 		= intval(decode($id));
		switch ($type) {
			case 'attach_pan_card_scan':
			case 'attach_photo_scan_of_aadhar':
			case 'attach_latest_receipt':
			case 'attach_recent_bill':
			case 'attach_detail_project_report':
				$ApplyOnlines 		= TableRegistry::get('ApplyOnlines');
				$apply_onlines_data = $ApplyOnlines->find('all', array('conditions' => array('id' => $doc_id)))->first();
				$apply_onlines_data = $Couchdb->find('all', array('conditions' => array('application_id' => $doc_id, 'access_type' => $type)))->first();

				if (!empty($apply_onlines_data)) {
					$COUCHDB->getDocument($apply_onlines_data->document_id, $apply_onlines_data->file_attached, $apply_onlines_data->doc_mime_type);
				}
				$document 			= $apply_onlines_data->$type;
				$image_path 		= WWW_ROOT . APPLYONLINE_PATH . $doc_id . '/';
				break;
			case 'sldc_file1':
			case 'sldc_file2':
			case 'sldc_file3':
			case 'sldc_file4':
			case 'sldc_file5':
				$Applications 		= TableRegistry::get('SldcApplicationDetails');
				$apply_onlines_data = $Applications->find('all', array('conditions' => array('application_id' => $doc_id)))->first();
				
				$document 			= $apply_onlines_data->$type;
				
				$image_path 		= WWW_ROOT . APPLICATIONS_PATH . $doc_id . '/';
				break;
			case 'bpta_document1':
			case 'bpta_document2':
				$Applications 		= TableRegistry::get('BptaApplicationDetails');
				$apply_onlines_data = $Applications->find('all', array('conditions' => array('application_id' => $doc_id)))->first();
				
				$document 			= $apply_onlines_data->$type;
				
				$image_path 		= WWW_ROOT . APPLICATIONS_PATH . $doc_id . '/';
				break;
			case 'Wheeling_Agreement_document':
				$Applications 		= TableRegistry::get('WheelingApplicationDetails');
				$apply_onlines_data = $Applications->find('all', array('conditions' => array('application_id' => $doc_id)))->first();
				
				$document 			= $apply_onlines_data->$type;
				
				$image_path 		= WWW_ROOT . APPLICATIONS_PATH . $doc_id . '/';
				break;
			case 'meter_sealing_report':
				$Applications 		= TableRegistry::get('MeterSealingApplicationDetails');
				$apply_onlines_data = $Applications->find('all', array('conditions' => array('application_id' => $doc_id)))->first();
				
				$document 			= $apply_onlines_data->$type;
				
				$image_path 		= WWW_ROOT . APPLICATIONS_PATH . $doc_id . '/';
				break;
			case 'power_injection_report':
				$Applications 		= TableRegistry::get('PowerInjectionApplicationDetails');
				$apply_onlines_data = $Applications->find('all', array('conditions' => array('application_id' => $doc_id)))->first();
				
				$document 			= $apply_onlines_data->$type;
				
				$image_path 		= WWW_ROOT . APPLICATIONS_PATH . $doc_id . '/';
				break;
			case 'rfid_upload':
				$ApplyOnlines 		= TableRegistry::get('ApplyOnlines');
				$apply_onlines_data = $ApplyOnlines->find('all', array('conditions' => array('id' => $doc_id)))->first();
				//$apply_onlines_data = $Couchdb->find('all', array('conditions' => array('application_id' => $doc_id, 'access_type' => $type)))->first();

				$document 			= $apply_onlines_data->$type;
				$image_path 		= WWW_ROOT . APPLYONLINE_PATH . $doc_id . '/';
				break;
			case 'undertaking_upload':
				$ApplyOnlines 		= TableRegistry::get('ApplyOnlines');
				$apply_onlines_data = $ApplyOnlines->find('all', array('conditions' => array('id' => $doc_id)))->first();
				//$apply_onlines_data = $Couchdb->find('all', array('conditions' => array('application_id' => $doc_id, 'access_type' => $type)))->first();

				$document 			= $apply_onlines_data->$type;
				$image_path 		= WWW_ROOT . APPLYONLINE_PATH . $doc_id . '/';
				break;
			case 'rfid_upload_file':
				$ApplyOnlines 		= TableRegistry::get('ApplyOnlinesRfidData');
				$apply_onlines_data = $ApplyOnlines->find('all', array('conditions' => array('id' => $doc_id)))->first();
				//$apply_onlines_data = $Couchdb->find('all', array('conditions' => array('application_id' => $doc_id, 'access_type' => $type)))->first();

				$document 			= $apply_onlines_data->$type;
				$image_path 		= WWW_ROOT . APPLYONLINE_RFID_PATH . $doc_id . '/';
				break;
			case 'undertaking_upload_file':
				$ApplyOnlines 		= TableRegistry::get('ApplyOnlinesRfidData');
				$apply_onlines_data = $ApplyOnlines->find('all', array('conditions' => array('id' => $doc_id)))->first();
				//$apply_onlines_data = $Couchdb->find('all', array('conditions' => array('application_id' => $doc_id, 'access_type' => $type)))->first();

				$document 			= $apply_onlines_data->$type;
				$image_path 		= WWW_ROOT . APPLYONLINE_RFID_PATH . $doc_id . '/';
				break;
			case 'profile':
				$ApplyonlinDocs 	= TableRegistry::get('ApplyonlinDocs');
				$apply_onlines_docs = $ApplyonlinDocs->find('all', array('conditions' => array('application_id' => $doc_id, 'doc_type' => $type)))->first();
				$apply_onlines_data = $Couchdb->find('all', array('conditions' => array('id' => $apply_onlines_docs->couchdb_id)))->first();
				if (!empty($apply_onlines_data)) {
					$COUCHDB->getDocument($apply_onlines_data->document_id, $apply_onlines_data->file_attached, $apply_onlines_data->doc_mime_type);
				}
				$document 			= $apply_onlines_docs->file_name;
				$image_path 		= WWW_ROOT . APPLYONLINE_PATH . $doc_id . '/';
				break;
			case 'paymentdata':
				$ApplyonlinDocs 	= TableRegistry::get('FesibilityReport');
				$apply_onlines_docs = $ApplyonlinDocs->find('all', array('conditions' => array('application_id' => $doc_id)))->first();
				$apply_onlines_docs = $Couchdb->find('all', array('conditions' => array('application_id' => $doc_id, 'access_type' => $type)))->first();
				if (!empty($apply_onlines_docs)) {
					$COUCHDB->getDocument($apply_onlines_docs->document_id, $apply_onlines_docs->file_attached, $apply_onlines_docs->doc_mime_type);
				}
				$document 			= 'paymentdata/' . $apply_onlines_docs->file_name;
				$image_path 		= WWW_ROOT . FEASIBILITY_PATH . $doc_id . '/';
				break;
			case 'workorder_data':
				$ApplyonlinDocs 	= TableRegistry::get('Workorder');
				$apply_onlines_docs = $ApplyonlinDocs->find('all', array('conditions' => array('project_id' => $doc_id)))->first();
				$apply_onlines_docs = $Couchdb->find('all', array('conditions' => array('application_id' => $doc_id, 'access_type' => $type)))->first();
				if (!empty($apply_onlines_docs)) {
					$COUCHDB->getDocument($apply_onlines_docs->document_id, $apply_onlines_docs->file_attached, $apply_onlines_docs->doc_mime_type);
				}
				$document 			= $apply_onlines_docs->attached_doc;
				$image_path 		= WWW_ROOT . WORKORDER_PATH . $doc_id . '/';
				break;
			case 'modules':
			case 'inverters':
			case 'others_exe':
				$ApplyonlinDocs 	= TableRegistry::get('ProjectInstallationPhotos');
				$apply_onlines_docs = $ApplyonlinDocs->find('all', array('conditions' => array('id' => $doc_id)))->first();

				$apply_onlines_docs = $Couchdb->find('all', array('conditions' => array('id' => $apply_onlines_docs->couchdb_id)))->first();
				if (!empty($apply_onlines_docs)) {
					$COUCHDB->getDocument($apply_onlines_docs->document_id, $apply_onlines_docs->file_attached, $apply_onlines_docs->doc_mime_type);
				}
				$document 			= $apply_onlines_docs->type . '/' . $apply_onlines_docs->photo;
				$image_path 		= WWW_ROOT . EXECUTION_PATH . $apply_onlines_docs->project_id . '/';
				break;
			case 'aadhar_card':
			case 'recent_bill':
			case 'invoice_copy':
			case 'mou_document':
			case 'cei_approval_doc':
			case 'cei_inspection_doc':
			case 'cei_self_certification':
			case 'bidirectional_installation_sheet':
			case 'bidirectional_meter_certification':
			case 'meter_sealing_report':
			case 'pv_module_serial':
			case 'pv_module_certificate':
			case 'pv_module_sheet':
			case 'inverter_serial':
			case 'inverter_certificate':
			case 'inverter_sheet':
			case 'pv_plant_site_photo':
			case 'undertaking_consumer':
			case 'signing_authority':
			case 'charity_certificate':
			case 'authority_letter':
			case 'formb':
			case 'formc':
			case 'affidavit':
			case 'agreement_stamp':
			case 'social_excel':
			case 'social_pdf':
			case 'geda_inspection_report':
				$ApplyonlinDocs 	= TableRegistry::get('Subsidy');
				$apply_onlines_docs = $ApplyonlinDocs->find('all', array('conditions' => array('id' => $doc_id)))->first();
				$apply_onlines_docs = $Couchdb->find('all', array('conditions' => array('application_id' => $apply_onlines_docs->application_id, 'access_type' => $type)))->first();
				if (!empty($apply_onlines_docs)) {
					$COUCHDB->getDocument($apply_onlines_docs->document_id, $apply_onlines_docs->file_attached, $apply_onlines_docs->doc_mime_type);
				}
				$document 			= $apply_onlines_docs->$type;
				$image_path 		= WWW_ROOT . SUBSIDY_PATH . $apply_onlines_docs->application_id . '/';
				break;
			case 'profile_image':
			case 'electricity_bill':
			case 'aadhar_card_update':
				$orgType 		 	= $type;
				if ($type == 'aadhar_card_update') {
					$type 			= 'aadhar_card';
				}
				$ApplyonlinDocs 	= TableRegistry::get('UpdateDetails');
				$apply_onlines_docs = $ApplyonlinDocs->find('all', array('conditions' => array('id' => $doc_id)))->first();
				$apply_onlines_docs = $Couchdb->find('all', array('conditions' => array('application_id' => $apply_onlines_docs->application_id, 'access_type' => $orgType)))->first();
				if (!empty($apply_onlines_docs)) {
					$COUCHDB->getDocument($apply_onlines_docs->document_id, $apply_onlines_docs->file_attached, $apply_onlines_docs->doc_mime_type);
				}
				$document 			= $apply_onlines_docs->$type;
				$image_path 		= WWW_ROOT . UPDATEDETAILS_PATH . $apply_onlines_docs->application_id . '/';

				break;
			case 'file_company_incorporation':
			case 'file_board':
			case 'upload_certificate':
			case 'gerc_certificate':
			case 'rec_registration_copy':
			case 'rec_receipt_copy':
			case 'rec_power_evaluation':
			case 'ppa_doc':
			case 'agreement_customer':
			case 'app_upload_undertaking':
				$ApplyonlinDocs 	= TableRegistry::get('ApplyOnlinesOthers');
				$apply_onlines_docs = $ApplyonlinDocs->find('all', array('conditions' => array('application_id' => $doc_id)))->first();
				$type 	= ($type == 'app_upload_undertaking') ? 'upload_undertaking' : $type;
				$apply_onlines_data = $Couchdb->find('all', array('conditions' => array('application_id' => $doc_id, 'access_type' => $type)))->first();
				if (!empty($apply_onlines_data)) {
					$COUCHDB->getDocument($apply_onlines_data->document_id, $apply_onlines_data->file_attached, $apply_onlines_data->doc_mime_type);
				}
				$document 			= $apply_onlines_docs->$type;
				$image_path 		= WWW_ROOT . APPLYONLINE_PATH . $apply_onlines_docs->application_id . '/';
				break;
			case 'consent_letter':
				$ApplyonlinDocs 	= TableRegistry::get('UpdateCapacity');
				$apply_onlines_docs = $ApplyonlinDocs->find('all', array('conditions' => array('id' => $doc_id)))->first();
				$apply_onlines_docs = $Couchdb->find('all', array('conditions' => array('application_id' => $apply_onlines_docs->application_id, 'access_type' => $type)))->first();
				if (!empty($apply_onlines_docs)) {
					$COUCHDB->getDocument($apply_onlines_docs->document_id, $apply_onlines_docs->file_attached, $apply_onlines_docs->doc_mime_type);
				}
				$document 			= $apply_onlines_docs->$type;
				$image_path 		= WWW_ROOT . UPDATEDETAILS_PATH . $apply_onlines_docs->application_id . '/';
				break;
			case 'Self_Certificate':
				$ApplyonlinDocs 	= TableRegistry::get('ApplyonlinDocs');
				$apply_onlines_docs = $ApplyonlinDocs->find('all', array('conditions' => array('id' => $doc_id)))->first();
				if (isset($apply_onlines_docs->couchdb_id) && !empty($apply_onlines_docs->couchdb_id)) {
					$apply_onlines_data = $Couchdb->find('all', array('conditions' => array('id' => $apply_onlines_docs->couchdb_id)))->first();
					if (!empty($apply_onlines_data)) {
						$COUCHDB->getDocument($apply_onlines_data->document_id, $apply_onlines_data->file_attached, $apply_onlines_data->doc_mime_type);
					}
				}
				$document 			= $apply_onlines_docs->file_name;
				$image_path 		= WWW_ROOT . APPLYONLINE_PATH . $apply_onlines_docs->application_id . '/';
				break;
			case 'gst_certificate':
			case 'upload_undertaking':
			case 'pan_card':
			case 'registration_document':
				$ApplyonlinDocs 	= TableRegistry::get('Installers');
				$apply_onlines_docs = $ApplyonlinDocs->find('all', array('conditions' => array('id' => $doc_id)))->first();

				$apply_onlines_docs = $Couchdb->find('all', array('conditions' => array('application_id' => $doc_id, 'access_type' => $type)))->first();
				if (!empty($apply_onlines_docs)) {
					$COUCHDB->getDocument($apply_onlines_docs->document_id, $apply_onlines_docs->file_attached, $apply_onlines_docs->doc_mime_type);
				}
				$document 			= $apply_onlines_docs->$type;
				$image_path 		= WWW_ROOT . INSTALLER_PROFILE_PATH . $apply_onlines_docs->id . '/';
				break;
			case 'd_gst_certificate':
			case 'd_upload_undertaking':
			case 'd_pan_card':
			case 'd_registration_document':
			case 'd_file_board':
			case 'd_msme':
				$ApplyonlinDocs 	= TableRegistry::get('Developers');
				$apply_onlines_docs = $ApplyonlinDocs->find('all', array('conditions' => array('id' => $doc_id)))->first();

				$apply_onlines_docs = $Couchdb->find('all', array('conditions' => array('application_id' => $doc_id, 'access_type' => $type)))->first();
				if (!empty($apply_onlines_docs)) {
					$COUCHDB->getDocument($apply_onlines_docs->document_id, $apply_onlines_docs->file_attached, $apply_onlines_docs->doc_mime_type);
				}
				$document 			= $apply_onlines_docs->$type;
				$image_path 		= WWW_ROOT . DEVELOPER_PROFILE_PATH . $apply_onlines_docs->id . '/';
				break;
			case 'a_pan_card':
			case 'a_registration_document':
			case 'a_file_board':
			case 'f_sale_discom':
			case 'a_msme':
			case 'a_upload_undertaking':
				$Applications 		= TableRegistry::get('Applications');
				$Applications 		= $Applications->find('all', array('conditions' => array('id' => $doc_id)))->first();

				$application_docs 	= $Couchdb->find('all', array('conditions' => array('application_id' => $doc_id, 'access_type' => $type)))->first();
				if (!empty($application_docs)) {
					$COUCHDB->getDocument($application_docs->document_id, $application_docs->file_attached, $application_docs->doc_mime_type);
				}
				$document 			= $application_docs->$type;
				$image_path 		= WWW_ROOT . APPLICATIONS_PATH . $application_docs->id . '/';
				break;
			case 'others_Application':
			case 'Signed_Doc_Application':
				$arrTypes			= explode('_Application', $type);
				if (count($arrTypes) > 1) {
					$type 			= $arrTypes[0];
				}
				$ApplyonlinDocs 	= TableRegistry::get('ApplicationsDocs');
				$apply_onlines_docs = $ApplyonlinDocs->find('all', array('conditions' => array('id' => $doc_id, 'doc_type' => $type)))->first();
				if (isset($apply_onlines_docs->couchdb_id) && !empty($apply_onlines_docs->couchdb_id)) {
					$apply_onlines_data = $Couchdb->find('all', array('conditions' => array('id' => $apply_onlines_docs->couchdb_id)))->first();
					if (!empty($apply_onlines_data)) {
						$COUCHDB->getDocument($apply_onlines_data->document_id, $apply_onlines_data->file_attached, $apply_onlines_data->doc_mime_type);
					}
				}
				$document 			= $apply_onlines_docs->file_name;
				$image_path 		= WWW_ROOT . APPLICATIONS_PATH . $apply_onlines_docs->application_id . '/';
				break;
			case 'authorize_letter':
			case 'copy_registration':
			case 'jamabandi':
			case 'aadhaar_file':
				$ApplyOnlines 		= TableRegistry::get('ApplyOnlinesKusum');
				$apply_onlines_data = $ApplyOnlines->find('all', array('conditions' => array('id' => $doc_id)))->first();
				$apply_onlines_data = $Couchdb->find('all', array('conditions' => array('application_id' => $doc_id, 'access_type' => $type)))->first();

				if (!empty($apply_onlines_data)) {
					$COUCHDB->getDocument($apply_onlines_data->document_id, $apply_onlines_data->file_attached, $apply_onlines_data->doc_mime_type);
				}
				$document 			= $apply_onlines_data->$type;
				$image_path 		= WWW_ROOT . APPLYONLINE_KUSUM_PATH . $doc_id . '/';
				break;
			case 'fr_copy_registration':
			case 'fr_pan_card':
			case 'fr_receipt':
			case 'fr_indemnity_bond':
			case 'fr_account_cheque':
				$ApplyOnlines 		= TableRegistry::get('FeesReturn');
				$apply_onlines_data = $ApplyOnlines->find('all', array('conditions' => array('id' => $doc_id)))->first();
				$apply_onlines_data = $Couchdb->find('all', array('conditions' => array('application_id' => $doc_id, 'access_type' => $type)))->first();

				if (!empty($apply_onlines_data)) {
					$COUCHDB->getDocument($apply_onlines_data->document_id, $apply_onlines_data->file_attached, $apply_onlines_data->doc_mime_type);
				}

				$document 			= $apply_onlines_data->$type;
				$image_path 		= WWW_ROOT . APPLYONLINE_KUSUM_PATH . $doc_id . '/';
				break;
			case 'others-kusum':
			case 'Signed_Doc-kusum':
				$arrTypes			= explode('-kusum', $type);
				if (count($arrTypes) > 1) {
					$type 			= $arrTypes[0];
				}
				$ApplyonlinDocs 	= TableRegistry::get('ApplyonlineKusumDocs');
				$apply_onlines_docs = $ApplyonlinDocs->find('all', array('conditions' => array('id' => $doc_id, 'doc_type' => $type)))->first();
				if (isset($apply_onlines_docs->couchdb_id) && !empty($apply_onlines_docs->couchdb_id)) {
					$apply_onlines_data = $Couchdb->find('all', array('conditions' => array('id' => $apply_onlines_docs->couchdb_id)))->first();
					if (!empty($apply_onlines_data)) {
						$COUCHDB->getDocument($apply_onlines_data->document_id, $apply_onlines_data->file_attached, $apply_onlines_data->doc_mime_type);
					}
				}
				$document 			= $apply_onlines_docs->file_name;
				$image_path 		= WWW_ROOT . APPLYONLINE_KUSUM_PATH . $apply_onlines_docs->application_id . '/';
				break;
			case 'workorder':
				$DeveloperWorkorder 	= TableRegistry::get('DeveloperWorkorder');

				$developer_wo_details 	= $DeveloperWorkorder->find('all', array('conditions' => array('id' => $doc_id)))->first();
				$apply_onlines_data 	= $ReCouchdb->find('all', array('conditions' => array('application_id' => $doc_id, 'access_type' => $type)))->first();

				if (!empty($apply_onlines_data)) {
					$COUCHDB->getDocument($apply_onlines_data->document_id, $apply_onlines_data->file_attached, $apply_onlines_data->doc_mime_type);
				}
				$document 			= $developer_wo_details->workorder_doc;
				$image_path 		= WWW_ROOT . DEVELOPER_WORKORDER_PATH . $developer_wo_details->id . '/';
				break;
			case 'wtg_file':
			case 'land_per_form':
			case 'transmission_agree_doc';
			case 'whelling_agree_doc';
				$ApplicationGeoLocation 	= TableRegistry::get('ApplicationGeoLocation');

				$application_wtg_file_details 	= $ApplicationGeoLocation->find('all', array('conditions' => array('id' => $doc_id)))->first();
				$apply_onlines_data 	= $ReCouchdb->find('all', array('conditions' => array('application_id' => $doc_id, 'access_type' => $type)))->first();

				if (!empty($apply_onlines_data)) {
					$COUCHDB->getDocument($apply_onlines_data->document_id, $apply_onlines_data->file_attached, $apply_onlines_data->doc_mime_type);
				}
				$document 			= $application_wtg_file_details->wtg_file;
				$image_path 		= WWW_ROOT . WTG_PATH . $application_wtg_file_details->id . '/';
				break;
			case 'Internal_clashed_uploadfile':
				$GeoApplicationClashedData 	= TableRegistry::get('GeoApplicationClashedData');

				$application_wtg_file_details 	= $GeoApplicationClashedData->find('all', array('conditions' => array('clashed_geo_id' => $doc_id)))->first();
				$apply_onlines_data 	= $ReCouchdb->find('all', array('conditions' => array('application_id' => $doc_id, 'access_type' => $type)))->first();

				if (!empty($apply_onlines_data)) {
					$COUCHDB->getDocument($apply_onlines_data->document_id, $apply_onlines_data->file_attached, $apply_onlines_data->doc_mime_type);
				}
				$document 			= $application_wtg_file_details->uploadfile;
				$image_path 		= WWW_ROOT . Internal_Clashed_PATH . $application_wtg_file_details->clashed_geo_id . '/';
				break;
			case 'geo_cordinate_file':
			case 'TPfile':
			case 'STUstep1':
			case 'STUstep2':
			case 'CTUstep1':
			case 'CTUstep2':
			case 'ProjectCommissioning':
			//case 'Wheeling_Agreement':
				$ApplicationsDocs 	= TableRegistry::get('ApplicationsDocs');
				$apply_onlines_docs = $ApplicationsDocs->find('all', array('conditions' => array('application_id' => $doc_id, 'doc_type' => $type)))->first();
				if (isset($apply_onlines_docs->couchdb_id) && !empty($apply_onlines_docs->couchdb_id)) {
					$apply_onlines_data 	= $ReCouchdb->find('all', array('conditions' => array('application_id' => $doc_id, 'access_type' => $type)))->first();

					if (!empty($apply_onlines_data)) {

						$COUCHDB->getDocument($apply_onlines_data->document_id, $apply_onlines_data->file_attached, $apply_onlines_data->doc_mime_type);
					}
				}
				$document 			= $apply_onlines_docs->file_name;
				$image_path 		= WWW_ROOT . APPLICATIONS_PATH . $apply_onlines_docs->application_id . '/';
				break;

				//Vishal
				//Open Access & Wind General Profile
			case 'p_pan_card':
			case 'p_registration_document':
			case 'p_d_file_board':
			case 'p_d_sale_discom':
			case 'p_a_msme':
			case 'p_upload_undertaking':
				$arrType = substr($type, 2);
				$Applications 		= TableRegistry::get('Applications');
				$Applications 		= $Applications->find('all', array('conditions' => array('id' => $doc_id)))->first();

				$application_docs 	= $Couchdb->find('all', array('conditions' => array('application_id' => $doc_id, 'access_type' => $type)))->first();

				if (!empty($application_docs)) {
					$COUCHDB->getDocument($application_docs->document_id, $application_docs->file_attached, $application_docs->doc_mime_type);
				}
				$document 			= $Applications->$arrType;
				$image_path 		= WWW_ROOT . APPLICATIONS_PATH . $Applications->id . '/';

				break;
				//Open Access
			case 'p_upload_sale_to_discom':
			case 'p_no_due_1':
			case 'p_no_due_2':
			case 'p_upload_proof_of_ownership_1':
			case 'p_upload_proof_of_ownership_2':
			case 'p_doc_of_beneficiary':
			case 'p_doc_of_gerc_license':
			case 'p_upload_undertaking_newness':
			case 'p_copy_of_conventional_electricity':
			case 'p_stamp_of_re_gen_plant':
			case 'p_electricit_bill_of_third_party':
			case 'p_multi_third_party':
			case 'p_rec_accrediation_cer':
			case 'p_final_registration_letter':
				$arrType = substr($type, 2);
				$OpenAccessApplicationDeveloperPermission 	= TableRegistry::get('OpenAccessApplicationDeveloperPermission');
				$open_access_details 	= $OpenAccessApplicationDeveloperPermission->find('all', array('conditions' => array('id' => $doc_id)))->first();
				$openAccessData 		= $ReCouchdb->find('all', array('conditions' => array('application_id' => $doc_id, 'access_type' => $arrType)))->first();
				if (!empty($openAccessData)) {
					$COUCHDB->getDocument($openAccessData->document_id, $openAccessData->file_attached, $openAccessData->doc_mime_type);
				}
				$document 			= $open_access_details->$arrType;
				$image_path 		= WWW_ROOT . APPLICATIONS_DEVELOPER_PERMISSION_PATH . 'open_access/' . $open_access_details->id . '/';
				break;

				// case 'p_deed_doc':
				// 	$arrType = substr($type,2);
				// 	$OpenAccessLandDetails 	= TableRegistry::get('OpenAccessLandDetails');				
				// 	$open_access_details 	= $OpenAccessLandDetails->find('all',array('conditions'=>array('id'=>$doc_id)))->first();
				// 	$openAccessData 		= $ReCouchdb->find('all',array('conditions'=>array('application_id'=>$doc_id,'access_type'=>$arrType)))->first();				
				// 	if(!empty($openAccessData)) {
				// 		$COUCHDB->getDocument($openAccessData->document_id,$openAccessData->file_attached,$openAccessData->doc_mime_type);
				// 	}
				// 	$document 			= $open_access_details->$arrType;
				// 	$image_path 		= WWW_ROOT.APPLICATIONS_DEVELOPER_PERMISSION_PATH.'open_access/'.$open_access_details->id.'/';

			case 'p_deed_doc':
				$arrType = substr($type, 2);
				$OpenAccessLandDetails 	= TableRegistry::get('OpenAccessLandDetails');

				$open_access_details 	= $OpenAccessLandDetails->find('all', array('conditions' => array('id' => $doc_id)))->first();
				$strVar = $open_access_details->$arrType;

				$openAccessData 		= $ReCouchdb->find('all', array('conditions' => array(
					'application_id' => $open_access_details->app_dev_per_id,
					'access_type' => $arrType,
					'file_attached' => $strVar
				)))->first();
				if (!empty($openAccessData)) {
					$COUCHDB->getDocument($openAccessData->document_id, $openAccessData->file_attached, $openAccessData->doc_mime_type);
				}
				$document 			= $open_access_details->$arrType;
				$image_path 		= WWW_ROOT . APPLICATIONS_DEVELOPER_PERMISSION_PATH . 'open_access/' . $open_access_details->app_dev_per_id . '/';
				break;
				//wind
			case 'w_upload_sale_to_discom':
			case 'w_no_due_1':
			case 'w_no_due_2':
			case 'w_upload_proof_of_ownership_1':
			case 'w_upload_proof_of_ownership_2':
			case 'w_doc_of_beneficiary':
			case 'w_copy_of_conventional_electricity':
			case 'w_stamp_of_re_gen_plant':
			case 'w_rec_accrediation_cer':
			case 'w_electricit_bill_of_third_party':
			case 'w_multi_third_party':

			case 'p_copy_of_electricity_bill':
			case 'p_permission_letter_of_getco':

			case 'p_undertaking_dec':
			case 'p_micro_sitting_drawing':
			case 'p_proof_of_ownership':
			case 'p_notarized_contract':
			case 'p_ca_certificate':
			case 'p_invoice_with_gst':
			case 'p_share_subscription':
			case 'p_pvt_proposed_land':
			case 'p_proj_sale_to_discom_no_due':
			case 'p_proj_captive_use_no_due':

				$arrType = substr($type, 2);
				$WindApplicationDeveloperPermission 	= TableRegistry::get('WindApplicationDeveloperPermission');
				$wind_details 	= $WindApplicationDeveloperPermission->find('all', array('conditions' => array('id' => $doc_id)))->first();
				$windData 		= $ReCouchdb->find('all', array('conditions' => array('application_id' => $doc_id, 'access_type' => $arrType)))->first();
				if (!empty($windData)) {
					$COUCHDB->getDocument($windData->document_id, $windData->file_attached, $windData->doc_mime_type);
				}
				$document 			= $wind_details->$arrType;
				$image_path 		= WWW_ROOT . APPLICATIONS_DEVELOPER_PERMISSION_PATH . 'wind/' . $wind_details->id . '/';

				break;

			case 'p_captive_share_register':
			case 'p_captive_ca_cs_certi':
			case 'p_captive_balance_sheet':
			case 'p_captive_annual_audit':
			case 'p_partnership_deed':
			case 'p_partnership_share_holding':
			case 'p_partnership_return_filed':
			case 'p_limited_share_register':
			case 'p_limited_share_certi':
			case 'p_limited_company_secretary_certi':
			case 'p_association_certified_return_filed':
			case 'p_association_share_register':
			case 'p_association_certi_of_ca':
			case 'p_certi_from_company_secretary':
			case 'p_cooperative_certi_from_district_registrar':
			case 'p_cooperative_share_register':
			case 'p_spv_company_return_file':
			case 'p_spv_company_certi_of_share_register':
			case 'p_spv_company_memorandum':
			case 'p_spv_company_articles_of_associate':
			case 'p_spv_company_company_secretary':
			case 'p_cgp_holding_annual_balance_sheet':
			case 'p_cgp_holding_acc_of_company':
			case 'p_cgp_annual_balance_sheet':
			case 'p_cgp_acc_of_company':
			case 'p_signed_dp_letter':
				$DeveloperApplicationsDocs 	= TableRegistry::get('DeveloperApplicationsDocs');
				$arrType = substr($type, 2);
				$developer_app_docs = $DeveloperApplicationsDocs->find('all', array('conditions' => array('dev_app_id' => $doc_id, 'doc_type' => $arrType)))->first();

				if (isset($developer_app_docs->couchdb_id) && !empty($developer_app_docs->couchdb_id)) {
					$dev_app_data 	= $ReCouchdb->find('all', array('conditions' => array('application_id' => $doc_id, 'access_type' => $arrType)))->first();
					if (!empty($dev_app_data)) {
						$COUCHDB->getDocument($dev_app_data->document_id, $dev_app_data->file_attached, $dev_app_data->doc_mime_type);
					}
				}
				$document 			= $developer_app_docs->file_name;
				$image_path 		= WWW_ROOT . APPLICATIONS_DEVELOPER_PERMISSION_PATH . 'wind/' .  $developer_app_docs->dev_app_id . '/';

				break;

			case 'pl_deed_doc':
				$arrType = substr($type, 3);

				$WindLandDetails 	= TableRegistry::get('WindLandDetails');
				$wind_details 		= $WindLandDetails->find('all', array('conditions' => array('app_geo_loc_id' => $doc_id)))->first();
				$windData 	= $ReCouchdb->find('all', array('conditions' => array('application_id' => $doc_id, 'access_type' => $arrType)))->first();
				if (!empty($windData)) {
					$COUCHDB->getDocument($windData->document_id, $windData->file_attached, $windData->doc_mime_type);
				}
				$document 			= $wind_details->$arrType;
				$image_path 		= WWW_ROOT . APPLICATIONS_DEVELOPER_PERMISSION_PATH . 'wind/' . $wind_details->app_dev_per_id . '/';
				break;

			case 'pi_deed_doc':
				$arrType = substr($type, 3);

				$WindLandDetails 	= TableRegistry::get('WindLandDetails');
				$wind_details 		= $WindLandDetails->find('all', array('conditions' => array('couch_id' => $doc_id)))->first();
				$windData 	= $ReCouchdb->find('all', array('conditions' => array('id' => $doc_id, 'access_type' => $arrType)))->first();
				if (!empty($windData)) {
					$COUCHDB->getDocument($windData->document_id, $windData->file_attached, $windData->doc_mime_type);
				}
				$document 			= $wind_details->$arrType;
				$image_path 		= WWW_ROOT . APPLICATIONS_DEVELOPER_PERMISSION_PATH . 'wind/' . $wind_details->app_dev_per_id . '/';
				break;


				//transfer
			
			case 't_upload_sale_to_discom':
			case 't_no_due_1':
			case 't_no_due_2':
			case 't_upload_proof_of_ownership_1':
			case 't_upload_proof_of_ownership_2':
			case 't_doc_of_beneficiary':
			case 't_copy_of_conventional_electricity':
			case 't_stamp_of_re_gen_plant':
			case 't_rec_accrediation_cer':
			case 't_electricit_bill_of_third_party':
			case 't_multi_third_party':

			case 't_copy_of_electricity_bill':
			case 't_permission_letter_of_getco':

			case 't_undertaking_dec':
			case 't_dev_per_by_geda':
			case 't_consent_lett_of_dev':
			case 't_proof_of_ownership':
			case 't_notarized_contract':
			case 't_ca_certificate':
			case 't_invoice_with_gst':
			case 't_share_subscription':
			case 't_pvt_proposed_land':
			case 't_proj_sale_to_discom_no_due':
			case 't_proj_captive_use_no_due':

				$arrType = substr($type, 2);
				$TransferDeveloperPermission 	= TableRegistry::get('TransferDeveloperPermission');
				$wind_details 	= $TransferDeveloperPermission->find('all', array('conditions' => array('id' => $doc_id)))->first();
				$windData 		= $ReCouchdb->find('all', array('conditions' => array('application_id' => $doc_id, 'access_type' => $arrType)))->first();
				if (!empty($windData)) {
					$COUCHDB->getDocument($windData->document_id, $windData->file_attached, $windData->doc_mime_type);
				}
				$document 			= $wind_details->$arrType;
				$image_path 		= WWW_ROOT . APPLICATIONS_DEVELOPER_PERMISSION_PATH . 'wind/transfer/' . $wind_details->id . '/';
				break;

			case 't_transfer_captive_share_register':
			case 't_transfer_captive_ca_cs_certi':
			case 't_transfer_captive_balance_sheet':
			case 't_transfer_captive_annual_audit':
			case 't_transfer_partnership_deed':
			case 't_transfer_partnership_share_holding':
			case 't_transfer_partnership_return_filed':
			case 't_transfer_limited_share_register':
			case 't_transfer_limited_share_certi':
			case 't_transfer_limited_company_secretary_certi':
			case 't_transfer_association_certified_return_filed':
			case 't_transfer_association_share_register':
			case 't_transfer_association_certi_of_ca':
			case 't_transfer_certi_from_company_secretary':
			case 't_transfer_cooperative_certi_from_district_registrar':
			case 't_transfer_cooperative_share_register':
			case 't_transfer_spv_company_return_file':
			case 't_transfer_spv_company_certi_of_share_register':
			case 't_transfer_spv_company_memorandum':
			case 't_transfer_spv_company_articles_of_associate':
			case 't_transfer_spv_company_company_secretary':
			case 't_transfer_cgp_holding_annual_balance_sheet':
			case 't_transfer_cgp_holding_acc_of_company':
			case 't_transfer_cgp_annual_balance_sheet':
			case 't_transfer_cgp_acc_of_company':
			case 't_signed_tp_letter':
				$DeveloperApplicationsDocs 	= TableRegistry::get('DeveloperApplicationsDocs');
				$arrType = substr($type, 2);
				$developer_app_docs = $DeveloperApplicationsDocs->find('all', array('conditions' => array('dev_app_id' => $doc_id, 'doc_type' => $arrType)))->first();
				
				if (isset($developer_app_docs->couchdb_id) && !empty($developer_app_docs->couchdb_id)) {
					$dev_app_data 	= $ReCouchdb->find('all', array('conditions' => array('application_id' => $doc_id, 'access_type' => $arrType)))->first();
					if (!empty($dev_app_data)) {
						$COUCHDB->getDocument($dev_app_data->document_id, $dev_app_data->file_attached, $dev_app_data->doc_mime_type);
					}
				}
				$document 			= $developer_app_docs->file_name;

				$image_path 		= WWW_ROOT . APPLICATIONS_DEVELOPER_PERMISSION_PATH . 'wind/transfer/' . $developer_app_docs->dev_app_id . '/';

				break;

			case 'tl_deed_doc':
				$arrType = substr($type, 3);

				$TransferWindLandDetails 	= TableRegistry::get('TransferWindLandDetails');
				$wind_details 		= $TransferWindLandDetails->find('all', array('conditions' => array('app_geo_loc_id' => $doc_id)))->first();
				
				$windData 	= $ReCouchdb->find('all', array('conditions' => array('application_id' => $doc_id, 'access_type' => $arrType)))->first();
				if (!empty($windData)) {
					$COUCHDB->getDocument($windData->document_id, $windData->file_attached, $windData->doc_mime_type);
				}
				$document 			= $wind_details->$arrType;
				$image_path 		= WWW_ROOT . APPLICATIONS_DEVELOPER_PERMISSION_PATH . 'wind/transfer/' . $wind_details->tran_app_dev_per_id . '/';

				
				break;

			case 'ti_deed_doc':
				$arrType = substr($type, 3);

				$TransferWindLandDetails 	= TableRegistry::get('TransferWindLandDetails');
				$wind_details 		= $TransferWindLandDetails->find('all', array('conditions' => array('couch_id' => $doc_id)))->first();
				
				$windData 	= $ReCouchdb->find('all', array('conditions' => array('id' => $doc_id, 'access_type' => $arrType)))->first();
				if (!empty($windData)) {
					$COUCHDB->getDocument($windData->document_id, $windData->file_attached, $windData->doc_mime_type);
				}
				$document 			= $wind_details->$arrType;
				$image_path 		= WWW_ROOT . APPLICATIONS_DEVELOPER_PERMISSION_PATH . 'wind/transfer/' . $wind_details->tran_app_dev_per_id . '/';
				break;

			case 'others':
			default:
				$ApplyonlinDocs 	= TableRegistry::get('ApplyonlinDocs');
				$apply_onlines_docs = $ApplyonlinDocs->find('all', array('conditions' => array('id' => $doc_id, 'doc_type' => $type)))->first();
				if (isset($apply_onlines_docs->couchdb_id) && !empty($apply_onlines_docs->couchdb_id)) {
					$apply_onlines_data = $Couchdb->find('all', array('conditions' => array('id' => $apply_onlines_docs->couchdb_id)))->first();
					if (!empty($apply_onlines_data)) {
						$COUCHDB->getDocument($apply_onlines_data->document_id, $apply_onlines_data->file_attached, $apply_onlines_data->doc_mime_type);
					}
				}
				$document 			= $apply_onlines_docs->file_name;
				$image_path 		= WWW_ROOT . APPLYONLINE_PATH . $apply_onlines_docs->application_id . '/';
				break;
		}
		$file_path 					= $image_path . $document;
		if (file_exists($file_path)) {
			$file_ext = pathinfo($file_path, PATHINFO_EXTENSION);

			switch (strtolower($file_ext)) {
				case 'png':
					header('Content-Type: image/png');
					break;
				case 'jpg':
				case 'jpeg':
					header('Content-Type: image/jpg');
					break;
				case 'gif':
					header('Content-Type: image/gif');
					break;
				case 'pdf':
					header('Content-Type: application/pdf');
					header("Content-Disposition:inline;filename='" . $document . "'");
					break;
				case 'docx':
					header('Content-Type: application/octet-stream');
					header("Content-Disposition:inline;filename='" . $document . "'");
					break;
				case 'xlsx':
					header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
					header("Content-Disposition:inline;filename='" . $document . "'");
					break;
			}

			readfile($file_path);
		}
	}



	/**
	 * generateSubsidySummarySheet
	 * Behaviour : public
	 * @param : id  : application_ids is use to generate applications, $isdownload=true
	 * @defination : Method is use to download .pdf file from applyonline listing
	 *
	 */
	public function generateSubsidySummarySheet($application_ids = "", $isdownload = true)
	{
		if (empty($application_ids)) {
			return false;
		} else {
			$ApplyOnlines 		= TableRegistry::get('ApplyOnlines');
			$arrApplications 	= $ApplyOnlines->GetApplicationSummaryDetails($application_ids);
			if (empty($arrApplications)) {
				return false;
			}
		}
		$Installation 			= TableRegistry::get('Installation');
		$SubsidyTable 			= TableRegistry::get('Subsidy');
		$view = new View();
		$view->layout 			= false;
		$view->set("pageTitle", "Subsidy Summary Sheet");
		$view->set("arrApplications", $arrApplications);
		$view->set("type_modules", $Installation->TYPE_MODULES);
		$view->set("type_inverters", $Installation->TYPE_INVERTERS);
		$view->set("make_inverters", $Installation->MAKE_INVERTERS);
		$view->set("inv_phase", $Installation->INV_PHASE_TYPE);
		$view->set("SubsidyTable", $SubsidyTable);

		$PDFFILENAME = getRandomNumber();
		/* Generate PDF for estimation of project */
		require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
		$dompdf = new Dompdf($options = array());
		$dompdf->set_base_path(SITE_ROOT_DIR_PATH . '/pdf/');
		$dompdf->set_option("isPhpEnabled", true);
		$view->set('dompdf', $dompdf);
		$html = $view->render('/Element/pdf-template/subsidy_summary_sheet');
		$dompdf->loadHtml($html, 'UTF-8');

		$dompdf->setPaper('A4', 'landscape');
		$dompdf->render();
		if ($isdownload) {
			$output = $dompdf->output();
			$pdfPath = SITE_ROOT_DIR_PATH . '/tmp/subsidy_summary_sheet-' . $PDFFILENAME . '.pdf';
			file_put_contents($pdfPath, $output);
			return $pdfPath;
		}
		$output = $dompdf->output();
		header("Content-type:application/pdf");
		header("Content-Disposition:inline;filename='" . $PDFFILENAME . ".pdf'");
		echo $output;
		die;
	}
	/**
	 * generatePaymentReceiptPdf
	 * Behaviour : public
	 * @param : id  : Id is use to identify for which application letter file should be downlaoded, $isdownload=true
	 * @defination : Method is use to download .pdf file from applyonline listing
	 *
	 */
	public function generatePaymentReceiptPdf($id, $isdownload = true, $mailData = false)
	{
		$ApplyOnlines 			= TableRegistry::get('ApplyOnlines');
		$ApplyonlinePayment 	= TableRegistry::get('ApplyonlinePayment');
		$Payumoney 				= TableRegistry::get('Payumoney');
		$Installers 			= TableRegistry::get('Installers');
		$MembersTable 			= TableRegistry::get('Members');
		$BranchMasters 			= TableRegistry::get('BranchMasters');
		$DiscomMaster 			= TableRegistry::get('DiscomMaster');
		$ApplyOnlineApprovals 	= TableRegistry::get('ApplyOnlineApprovals');
		$discom_short_name 		= '';
		if (empty($id)) {
			$this->Flash->error('Please Select Valid Installer.');
			return $this->redirect('home');
		} else {
			$encode_id 					= $id;
			$id 						= intval(decode($id));
			$payment_data 				= $ApplyonlinePayment->find('all', array('conditions' => array('application_id' => $id), 'order' => array('id' => 'desc')))->first();

			$payment_details 			= $Payumoney->find('all', array('conditions' => array('id' => $payment_data->payment_id)))->first();

			$applyOnlinesData 			= $ApplyOnlines->viewApplication($id);
			$applyOnlinesData->aid 		= "1" . str_pad($id, 7, "0", STR_PAD_LEFT);
			$LETTER_APPLICATION_NO 		= $applyOnlinesData->aid;
			$APPLICATION_DATE 			= date("d.m.Y", strtotime($applyOnlinesData->created));
			$Installers_data = $Installers->find("all", ['conditions' => ['id' => $applyOnlinesData->installer_id]])->first();
			$Members = $MembersTable->find("all", ['conditions' => ['member_type' => '6003', 'name' => 'CEI']])->first();
			$discom_data = array();
			$discom_name = "";
			if (!empty($applyOnlinesData->area)) {
				$discom_data                = $MembersTable->find("all", ['conditions' => ['area' => $applyOnlinesData->area, 'circle' => '0', 'division' => '0', 'subdivision' => '0', 'section' => '0']])->first();
				$discom_name                = $BranchMasters->find("all", ['conditions' => ['id' => $discom_data->branch_id]])->first();
				$discom_short_name          = $DiscomMaster->find("all", ['conditions' => ['id' => $discom_name->discom_id]])->first();
			}
		}
		$applyOnlineGedaDate = $ApplyOnlineApprovals->getgedaletterStageData($id);
		$view = new View();
		$view->layout 			= false;
		$view->set("APPLY_ONLINE_MAIN_STATUS", $ApplyOnlineApprovals->apply_online_main_status);
		$view->set("pageTitle", "Apply-online View");
		$view->set('ApplyOnlines', $applyOnlinesData);
		$view->set('Installers_data', $Installers_data);
		$view->set('Members', $Members);
		$view->set('LETTER_APPLICATION_NO', $LETTER_APPLICATION_NO);
		$view->set('APPLICATION_DATE', $APPLICATION_DATE);
		$view->set('discom_data', $discom_data);
		$view->set('discom_name', $discom_name);
		$view->set('applyOnlineGedaDate', $applyOnlineGedaDate);
		$view->set('payment_data', $payment_data);
		$view->set('payment_details', $payment_details);
		//$this->set("APPLY_ONLINE_GUJ_STATUS",$this->ApplyOnlineApprovals->apply_online_guj_status);
		$view->set('discom_short_name', $discom_short_name);

		/* Generate PDF for estimation of project */
		require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
		$dompdf = new Dompdf($options = array());
		$dompdf->set_option("isPhpEnabled", true);
		$view->set('dompdf', $dompdf);
		$dompdf->set_base_path(SITE_ROOT_DIR_PATH . '/pdf/');

		$html = $view->render('/Element/paymentreceipt');
		$dompdf->loadHtml($html, 'UTF-8');
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();

		// Output the generated PDF to Browser
		if ($isdownload) {
			$dompdf->stream('applyonlinepayment-' . $LETTER_APPLICATION_NO);
		}
		$output = $dompdf->output();
		if ($mailData) {
			$pdfPath 	= WWW_ROOT . '/tmp/paymentReceipt-' . $LETTER_APPLICATION_NO . '.pdf';
			file_put_contents($pdfPath, $output);
			return $pdfPath;
		} else {

			header("Content-type:application/pdf");
			header("Content-Disposition:inline;filename='" . $LETTER_APPLICATION_NO . ".pdf'");
			echo $output;
		}
		die;
	}
	/**
	 *
	 * visitorTracker
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is used to count the visitor for website.
	 *
	 */
	public function visitorTracker($cust_id = '')
	{

		$api_url        					= 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$arrUrlData 						= explode("/admin/", $api_url);
		$visit_tracker		                = TableRegistry::get('VisitTracker');
		if (count($arrUrlData) <= 1) {
			$VisitorEntity                      = $visit_tracker->newEntity();
			$VisitorEntity['ip_address']        = $_SERVER['REMOTE_ADDR'];
			$VisitorEntity['browser_info']      = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "-";
			$VisitorEntity['url']               = $api_url;
			$VisitorEntity['user_id']           = $cust_id;
			$VisitorEntity->created_at    		= $this->NOW();
			$VisitorEntity->created    			= date('Y-m-d');
			$visit_tracker->save($VisitorEntity);
		}
		$connection     = ConnectionManager::get('default');
		//$countvisit 	= $connection->execute("SELECT DISTINCT ip_address,DATE_FORMAT(created_at,'%Y-%m-%d') FROM `visitor_tracker`")->fetchAll('assoc');
		$countvisit 	= $connection->execute("SELECT * FROM `unique_visitor_count`")->fetchAll('assoc');
		$counter 		= 100000 + $countvisit[0]['visitor_total_count'];
		$var_visit 		= str_pad($counter, 7, "0", STR_PAD_LEFT);
		$visitor_count	= str_split($var_visit);

		return $visitor_count;
	}
	/**
	 * generateInstallerReceiptPdf
	 * Behaviour : public
	 * @param : id  : Id is use to identify for which installer letter file should be downlaoded, $isdownload=true
	 * @defination : Method is use to download .pdf file from applyonline listing
	 *
	 */
	public function generateInstallerReceiptPdf($id, $isdownload = true, $mailData = false)
	{
		$ApplyOnlines 				= TableRegistry::get('ApplyOnlines');
		$InstallerPayment 			= TableRegistry::get('InstallerPayment');
		$InstallerSuccessPayment 	= TableRegistry::get('InstallerSuccessPayment');
		$Installers 				= TableRegistry::get('Installers');
		$MembersTable 				= TableRegistry::get('Members');
		$BranchMasters 				= TableRegistry::get('BranchMasters');
		$DiscomMaster 				= TableRegistry::get('DiscomMaster');
		$ApplyOnlineApprovals 		= TableRegistry::get('ApplyOnlineApprovals');
		if (empty($id)) {
			$this->Flash->error('Please Select Valid Installer.');
			return $this->redirect('home');
		} else {
			$encode_id 					= $id;
			$id 						= intval(decode($id));
			$installer_id 				= $id;
			$payment_data 				= $InstallerSuccessPayment->find('all', array('conditions' => array('installer_id' => $id), 'order' => array('id' => 'desc')))->first();

			$payment_details 			= $InstallerPayment->find('all', array('conditions' => array('id' => $payment_data->payment_id)))->first();

			$InstallersData 			= $Installers->find('all', array('conditions' => array('id' => $id)))->first();
		}
		$view = new View();
		$view->layout 			= false;
		$view->set("APPLY_ONLINE_MAIN_STATUS", $ApplyOnlineApprovals->apply_online_main_status);
		$view->set("pageTitle", "Apply-online View");
		$view->set('InstallersData', $InstallersData);
		$view->set('payment_data', $payment_data);
		$view->set('payment_details', $payment_details);
		//$this->set("APPLY_ONLINE_GUJ_STATUS",$this->ApplyOnlineApprovals->apply_online_guj_status);

		/* Generate PDF for estimation of project */
		require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
		$dompdf = new Dompdf($options = array());
		$dompdf->set_option("isPhpEnabled", true);
		$view->set('dompdf', $dompdf);
		$dompdf->set_base_path(SITE_ROOT_DIR_PATH . '/pdf/');

		$html = $view->render('/Element/installer_paymentreceipt');
		$dompdf->loadHtml($html, 'UTF-8');
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();

		// Output the generated PDF to Browser
		if ($isdownload) {
			$dompdf->stream('paymentreceipt-' . $installer_id);
		}
		$output = $dompdf->output();
		if ($mailData) {
			$pdfPath 	= WWW_ROOT . '/tmp/paymentReceipt-' . $installer_id . '.pdf';
			file_put_contents($pdfPath, $output);
			return $pdfPath;
		} else {
			header("Content-type:application/pdf");
			header("Content-Disposition:inline;filename='" . $installer_id . ".pdf'");
			echo $output;
		}
		die;
	}
}
