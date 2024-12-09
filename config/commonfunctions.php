<?php

//$aes = new Crypt_AES();
//$rsa = new Crypt_RSA();

function nl2SC($str,$seprator = ',')
{
	if(empty($str))
		return ;
	return str_replace(array("\n","\r","\r\n","<BR \>"), $seprator, $str );
}
function sc2NL($str,$seperator = "\r\n")
{
	if(empty($str))
		return ;
	return str_replace(",", $seperator, $str );
}

function igencode($id, $key="")
{
	$len			= 10;
	$md5_key		= (!empty($key)?md5($key):md5(SECRET_ENC_KEY));
	$len_jobid		= 16;
	$sub_md5key1	= substr($md5_key, 0, $len);
	$sub_md5key2	= substr($md5_key, $len);
	return $sub_md5key1.$id.$sub_md5key2;
}

function igdecode($encodeid,$vauletype='integer')
{
	$strRet = "";
	$len = 10;
	$sub_md5key1 = substr($encodeid, 0, $len);
	$sub_md5key2 = substr($encodeid, -1*(32-$len));
	$strRet = str_replace(array($sub_md5key1, $sub_md5key2), '', $encodeid);
	if($vauletype=='integer')
		$strRet = (int) $strRet;
	else
		$strRet = $strRet;

	return $strRet;
}

function encode($id, $key="")
{
	$len			= 10;
	$md5_key		= (!empty($key)?md5($key):md5(SECRET_ENC_KEY));
	$len_jobid		= 16;
	$sub_md5key1	= substr($md5_key, 0, $len);
	$sub_md5key2	= substr($md5_key, $len);
	return $sub_md5key1.$id.$sub_md5key2;
}

function decode($encodeid,$vauletype='integer')
{
	$strRet = "";
	$len = 10;
	$sub_md5key1 = substr($encodeid, 0, $len);
	$sub_md5key2 = substr($encodeid, -1*(32-$len));
	$strRet = str_replace(array($sub_md5key1, $sub_md5key2), '', $encodeid);
	if($vauletype=='integer')
		$strRet = (int) $strRet;
	else
		$strRet = $strRet;

	return $strRet;
}

function convert_links($text,$width = 100,$wrap=1)
{
 //First match things beginning with http:// (or other protocols)

 $not_anchor = '(?<!"|href=|href\s=\s|href=\s|href\s=)';
 $protocol = '(http|ftp|https):\/\/';
 $domain = '[\w]+(.[\w]+)';
 $subdir = '([\w\-\.;,@?^=%&:\/~\+#]*[\w\-\@?^=%&\/~\+#])?';
 $expr = '/' . $not_anchor . $protocol . $domain . $subdir . '/i';
 $txtwidth = $width;
 if ($wrap == 1)
 {
  $result = preg_replace_callback( $expr, "callback_url",$text);
 }
 elseif($wrap == 0)
 {
  $result = preg_replace( $expr, "<a href='$0' title='$0' target='_blank'>$0</a>",$text);
 }
 //Now match things beginning with www.
 $not_anchor = '(?<!"|href=|href\s=\s|href=\s|href\s=)';
 $not_http = '(?<!:\/\/)';
 /*$domain = 'www(.[\w]+)';*/
 $domain = 'www\.([\w]+)';
 $subdir = '([\w\-\.;,@?^=%&:\/~\+#]*[\w\-\@?^=%&\/~\+#])?';
 $expr = '/' . $not_anchor . $not_http . $domain . $subdir . '/i';
 $result=preg_replace($expr,"<a href='http://$0' title='http://$0'>[link]</a>", $result );
 return wordwrap($result,$width,"<br>",1);
}
function callback_url($strarr)
{
 $final_display = "<a href='".$strarr[0]."' title='".$strarr[0]."' target='_blank'>".$strarr[0]."</a>";
 return $final_display;
}

function GetMonthName($val)
{
	if(is_int($val)){
		return date('F', mktime(0,0,0,$val,1));          
	}
}

function SortScanResultByStatus($e1,$e2) {
	if ($e1["T"] == $e2["T"]) {
        return 0;
    }
    return ($e1["T"] < $e2["T"])?1:-1;
}

function SortScanResultByType($e1,$e2) {
	if ($e1["ORDER_BY"] == $e2["ORDER_BY"]) {
        return 0;
    }
    return ($e1["ORDER_BY"] > $e2["ORDER_BY"])?1:-1;
}

function SortScanResultByUrl($e1,$e2) {
	return strcmp($e1["url"], $e2["url"]);
}

/**
 * Function : repairHTML
 * @desc : Method is use to repair html file, insert wbr after 30 characters
 * @return string with wbr tag
 */
function repairHTML($string,$noc = 30)
{
	//		$string = string;
	//Below  4 line to be uncommented if Tidy is installed and needed to be use
	$string = str_replace('%20','t$mPV@r!@blE', $string);
	$string = tidy_parse_string($string, array('show-body-only'=>true, 'indent'=>false,'doctype'=> 'omit','wrap' => 0));
	$string = str_replace('t$mPV@r!@blE','%20', $string);
	$string = stripslashes(preg_replace('/(<)([^<>]*)(>)/e','str_replace(" ", "[SPACE]", "$1$2$3")',$string));

	$repairedHtml = '';

	$arrString = explode(' ',$string);
	if(!empty($arrString))
		$repairedHtml = findAndInsertWBRforNonWhiteCharacter($arrString,$noc);
	return $repairedHtml;
	/*
	 return $repairedHtml = stripslashes(str_replace("[SPACE]"," ",$repairedHtml)); // This line to be replaced with above if tidy is to be used
	*/
}
/**
 *
 * @param $arrString
 * @desc : method is use to insert wbr if there is non white characters
 * @return multitype:|string
 */
function findAndInsertWBRforNonWhiteCharacter($arrString,$noc)
{
	$stringWithoutTag = '';
	$isNextStringGtThn30 = 0;
	$arrBrk = array();
	if(empty($arrString))
		return $arrBrk;
	foreach($arrString as $k=>$sd) //$sd array of chunk seprated with white space
	{
		if($k < count($arrString)-1 && strlen($arrString[$k+1]) > $noc)
			$isNextStringGtThn30  = 1;

		$stringWithoutTag = strip_tags($sd);
		$arrString[$k] =HtmlEntitySafeSplit($sd,$noc,'<wbr/>',$isNextStringGtThn30);// str_replace($stringWithoutTag,addWBRtag($stringWithoutTag,$noc),$sd);
	}
	$string =  implode(' ',$arrString);
	return $string;
}
/**
 * getArrOfPosforHTMLtag
 * @param $html : peice of string
 * @desc: method is use to get position of html tag if its in string
 * @return multitype:unknown
 */
function getArrOfPosforHTMLtag($html)
{
	$string = preg_match_all('/(<[^<>]*>)/',$html,$matches);
	$arrPos = array();
	$arrPosforWBR = array();
	if(!empty($matches))
	{
		foreach($matches as $k=>$tag)
		{
			foreach($tag as $key=>$htmtags)
			{
				$occurances = array();
				$start = 0;
				while($start = strpos($html,$htmtags,$start))
				{
					$arrPos[$start] = $htmtags;
					$start++;
				}
			}
		}
	}
	return $arrPos;
}
/**
 * HtmlEntitySafeSplit
 * @param  $html : peice of string
 * @param  $size : size i.e noc after which wbr to be inserted
 * @param  $delim : delimeter to be used in our case <wbr>
 * @param  $isNxtStringGrtThn30 : to check wether nest string i.e word is less then given character
 * @desc :  Method is use to split the string and insert wbr after spliting it
 * @return string
 */
function HtmlEntitySafeSplit($html,$size,$delim,$isNxtStringGrtThn30 = 0)
{
	$arrPos = array();
	$arrPosforWBR = array();
	$arrPosForSpecialCharacterWbr = array();
	$arrPos = getArrOfPosforHTMLtag($html);
	$withoutHTMLTag = strip_tags($html);
	$unsafe = 0;
	$deli = 0;
	$out = '';
	$pos=0;
	for($i=0;$i<strlen($withoutHTMLTag);$i++)
	{
		if($pos >= $size && !$unsafe)
		{

			if(!in_array($i,$arrPosforWBR))
				$arrPosforWBR[$i] = $i;
			$out.=$delim;
			$unsafe=0;
			$pos=0;
			$deli = 1;
		}
		$c=substr($withoutHTMLTag,$i,1);
		if($c == "&")
			$unsafe=1;
		elseif($c == "," || $c == "." || $c == ":" || $c == ";" ) // Special character to be included after which termination is to be needed
		{
			if($isNxtStringGrtThn30)
			{
				$arrPosForSpecialCharacterWbr =getWbrPosForSpecialCharacter($c,$html);
				if(!empty($arrPosForSpecialCharacterWbr))
				{
					foreach($arrPosForSpecialCharacterWbr as $k=>$pos)
					{
						if(!in_array($pos,$arrPosforWBR))
							$arrPosforWBR[$pos] = $pos;
					}
					$pos=0;
					$unsafe=0;
				}

			}
		}
		else
			$unsafe=0;
		$out.=$c;
		$pos++;
	}
	return appendDelim($html,$arrPos,$delim,$arrPosforWBR);
}
/**
 * getWbrPosForSpecialCharacter
 * @param  $c : character to be checked
 * @param  $html : string in which character is to be checked
 * @desc : Method is to be used to get wbr position for special character if used
 * @return multitype:number
 */
function getWbrPosForSpecialCharacter($c,$html)
{
	$arrPositionForWbrSplitBySpecialCharacter = array();
	$arrSplitBySpecialCharacter = array();
	$arrSplitBySpecialCharacter = explode($c,$html);
	foreach($arrSplitBySpecialCharacter as $k=>$splitedString)
	{
		if($k < count($arrSplitBySpecialCharacter)-1)
			$arrPositionForWbrSplitBySpecialCharacter[] = strlen($splitedString)+1;
	}
	return $arrPositionForWbrSplitBySpecialCharacter;
}
/**
 * getPositionLessThenWbr
 * @param  $arrPos
 * @param  $wbrPos
 * @desc Method is to be used to get Position if its less then wbr
 * @return number
 */
function getPositionLessThenWbr($arrPos,$wbrPos)
{
	$old = 0;
	foreach($arrPos as $k=>$tag)
	{
		if($k > $wbrPos )
		{
			return $old;
		}
		$old =  $old+strlen($tag);
	}
	return $old;
}
/**
 * appendDelim
 * @param  $html : String in which delimeter in our case <wbr> tag to be inserted
 * @param  $arrPos : position where tag are present with tag name
 * @param  $delim : delimeter i.e tag to be inserted
 * @param  $arrPosforWBR : poition wbr to be inserted
 * @return string
 */
function appendDelim($html,$arrPos,$delim,$arrPosforWBR)
{
	if(empty($arrPosforWBR))
		return $html;
	$cntINC = 0;
	$addedchar = 0;
	$addTagCount = 0;
	$positionFilled = array(0=>0);

	foreach($arrPosforWBR as $k=>$wbrPos)
	{
		$addTagCount = 0;
		$addTagCount  = getPositionLessThenWbr($arrPos,$wbrPos);
		if(!empty($arrPos))
		{
			$positionFilled[] = $wbrPos;
			$addedchar = $addedchar+$addTagCount;
			if(substr($html,$wbrPos+$addedchar,1) == '>')
			{
				$stringA = substr($html,0,$wbrPos+$addedchar+1);
				$stringB = substr($html,$wbrPos+$addedchar+1);
			}
			elseif(substr($html,$wbrPos+$addedchar-1,1) == '<')
			{
				$stringA = substr($html,0,$wbrPos+$addedchar-1);
				$stringB = substr($html,$wbrPos+$addedchar-1);
			}
			else
			{
				$stringA = substr($html,0,$wbrPos+$addedchar);
				$stringB = substr($html,$wbrPos+$addedchar);
			}
			$html = $stringA."<wbr/>".$stringB;
			$addedchar = $addedchar-$addTagCount;
			$addedchar = $addedchar+6;

		}else
		{
			if(!in_array($wbrPos,$positionFilled))
			{
				$positionFilled[] = $wbrPos;
				$stringA = substr($html,0,$wbrPos+$addedchar);
				$stringB = substr($html,$wbrPos+$addedchar);
				$html = $stringA."<wbr/>".$stringB;
				$addedchar = $addedchar+6;
			}
		}
	}
	return $html;
}

/**
 *
 * _dateDiff
 *
 * Behaviour : public
 *
 * @param : $date1   : 1st date argument use to calculate differece while comparing to 2nd date argument
 * @param : $date2   : 2st date argument use to calculate differece while comparing to 1nd date argument
 * @return :  Array of difference
 * @defination : Method is use to calcualte  difference of 2 dates and return it in array
 *
 */
function dateDiff($date1,$date2)
{
	$date1 = strtotime($date1);
	$date2 = strtotime($date2);

	$dateDiff    = $date1 - $date2;
	$dateDiff = abs($dateDiff);
	$fullDays    = floor($dateDiff/(60*60*24));
	$fullHours   = floor(($dateDiff-($fullDays*60*60*24))/(60*60));
	$fullMinutes = floor(($dateDiff-($fullDays*60*60*24)-($fullHours*60*60))/60);
	$fullSeconds = floor(($dateDiff-($fullDays*60*60*24)-($fullHours*60*60)-($fullMinutes*60)));
	return array("Day"=>$fullDays,"Hour"=>$fullHours,"Minute"=>$fullMinutes,"Second"=>$fullSeconds);
}

/**
 *
 * replaceContent
 *
 * Behaviour : public
 *
 * @param : $text   : text in which somethig need to replace.
 * @param : $fieldname  : Name of the field
 * @param : $type  : Service Type of the field.
 * @return :  content replaced string
 * @defination : Method is use to replace something in the some field text value before presenting to customer.
 *
 */
function replaceContent($text,$fieldname='description',$type='AA')
{
	if(is_null($text) || empty($text)) return $text;
	$fieldname 	= strtolower($fieldname);
	$type 		= strtolower($type);
	return $text;
}

/**
 * GenerateFormatedURL
 * Behaviour	: public
 * @param		: $controller : controller
 * @param		: $action		: action
 * @param		: $params		: Parameter passed with url.
 * @return		: formatted url string
 * @defination	: Method is use for formatting URL.
 */
function GenerateFormatedURL($controller,$action="",$params="")
{
	// 
}
/**
 * convertDatetoMysqlFormat
 * Behaviour	: public
 * @param		: $dateToConvert		: date to be converted.
 * @param		: $DatenTime		:  if date and time format to be converted, value of $DatenTime will be 1.
 * @return		: formatted url string
 * @defination	: Method is use for formatting URL.
 */
function convertDatetoMysqlFormat($dateToConvert,$DatenTime = 0)
{
	if (is_array($dateToConvert)) return "";
	if(empty($DatenTime))
		return  date("Y-m-d", strtotime($dateToConvert));
	else
		return  date("Y-m-d H:i:s", strtotime($dateToConvert));
}

/**
* SortDateAsc
* Behaviour		: public
* @param		: $date1
* @param		: $date2
* @return		: boolean
* @defination	: Method is use for Sorting Date.
*/
function SortDateAsc($date1,$date2)
{
	if (strtotime($date2) <= strtotime($date1)) {
		return true;
	} else {
		return false;
	}
}
/**
* DBVarConv
* Behaviour		: public
* @param		: string
* @return		: string
* @defination	: Method is use for Mysql Escape String.
*/
function DBVarConv($var)
{
	if(is_array($var) || $var=='')
		return $var;
	else
		return addslashes(html_entity_decode(ReplaceSpecialChars($var)));
}
/**
* ReplaceSpecialChars
* Behaviour		: public
* @param		: string
* @return		: string
* @defination	: Method is use for Replacing Special Char.
*/
function ReplaceSpecialChars($str)
{
	return $str;
}
/**
* ReplaceQuotes
* Behaviour		: public
* @param		: string
* @return		: string
* @defination	: Method is use for Replacing Quotes.
*/
function ReplaceQuotes($str)
{
	return str_replace(array("'",'"'),array("",""),$str);
}
/**
* ReplaceNonAlphaNumbers
* Behaviour		: public
* @param		: string
* @return		: string
* @defination	: Method is use for Replacing Special Char.
*/
function ReplaceNonAlphaNumbers($str)
{
	return DBVarConv(preg_replace("/[^\da-z\-_ \(\)\+=|,\.!@#$%\^&\*`~<>\?]/i","",$str));
}
/**
* HTMLVarConv
* Behaviour		: public
* @param		: string
* @return		: string
* @defination	: Method is use for converting html tag to display.
*/
function HTMLVarConv($var)
{
	return stripslashes(htmlspecialchars(html_entity_decode($var)));
}


/**
* CustomEncrypt
* Behaviour		: public
* @param		: string $str
* @param		: string $lngth
* @return		: string $retstr
* @defination	: Method is use for String Encryption.
*/
function CustomEncrypt($str,$lngth=16)
{
	$lngth	= (strlen($str) > $lngth)?strlen($str):$lngth;
	$str	= substr($str,0,$lngth);
	$str	= str_pad($str,$lngth," ");
	$retstr	= "";
	for($i=0;$i<$lngth;$i++)
	{
		$sch	= substr($str,$i,1);
		$iasc	= ord($sch) + 2*$i + 30;
		if($iasc > 255) $iasc = $iasc-255;
		$sch	= chr($iasc);
		$retstr	= $retstr.$sch;
	}
	$retstr	= implode("*",unpack('C*',$retstr));
	return $retstr;
}

/**
* customdecrypt
* Behaviour		: public
* @param		: string $str
* @param		: boolean $mask
* @param		: string $maskstr
* @return		: string $retstr
* @defination	: Method is use for String Decription.
*/
function CustomDecrypt($str,$mask=false,$maskstr="*")
{
	$retstr	= "";
	$string = '';
	$data	= explode('*',$str);
	for ($i=0;$i<count($data);$i++) if ($data[$i] != '') $string = $string.pack('C*',$data[$i]);
	$str	= $string;
	$lngth	= strlen($str);
	for($i=0;$i<$lngth;$i++)
	{
		$sch	= substr($str,$i,1);
		$iasc	= ord($sch) - 2*$i - 30;
		if($iasc <= 0) $iasc = 255+$iasc;
		$sch	= chr($iasc);
		$retstr	= $retstr.$sch;
	}
	if ($mask) {
		$retstr = str_repeat($maskstr,strlen($retstr));
	}
	return trim($retstr);
}

/**
 * EncodeManualAlertInputData
 * Behaviour : public
 * @param : string $content
 * @return : string $content
 * @defination : method to convert string in encoded format.
 */
function EncodeManualAlertInputData($content)
{
	return htmlentities(preg_replace("/<xmp(.*?)>(.*?)<\/xmp>/si","$2",$content), ENT_COMPAT, 'UTF-8');
}
/**
 * DecodeManualAlertInputData
 * Behaviour : public
 * @param : string $content
 * @return : string $content
 * @defination : method to convert string in decoded format.
 */
function DecodeManualAlertInputData($content)
{
	return html_entity_decode($content);
}

/**
 * ConvertDateTimeDBToWeb
 * Behaviour : public
 * @param : datetime $date
 * @param : string $ConvertTZ
 * @param : string $Format
 * @param : string $SourceTZ
 * @param : string $SourceDateFormat
 * @return : datetime $ConvertedDate
 * @defination : method to convert database time to customer time with timezone.
 */
function ConvertDateTimeDBToWeb($date,$ConvertTZ,$Format="Y-m-d H:i:s",$SourceTZ="UTC",$SourceDateFormat="Y-m-d H:i:s",$isDST=true,$UTCTZ="UTC")
{
	$ConvertedDate		= "";
	$Format				= (empty($Format)?"Y-m-d H:i:s":$Format);
	$SourceDateFormat	= (empty($SourceDateFormat)?"Y-m-d H:i:s":$SourceDateFormat);
	$date				= (empty($date)?date($SourceDateFormat):$date);
	$SourceTZ			= (empty($SourceTZ)?"UTC":$SourceTZ);
	$ConvertTZ			= (empty($ConvertTZ)?"UTC":$ConvertTZ);
	$UTCTZ				= (empty($UTCTZ)?"UTC":$UTCTZ);
	
	$ConvertTZ			= (($ConvertTZ == "+00:00")?"UTC":$ConvertTZ);
	$SourceTZ			= (($SourceTZ == "+00:00")?"UTC":$SourceTZ);
	$UTCTZ				= str_replace("utc","",strtolower($UTCTZ));
	$UTCTZ				= (($UTCTZ == "+00:00")?"UTC":$UTCTZ);
	// create DateTime object
	if (strtolower($ConvertTZ) == strtolower($SourceTZ)) {
		$ConvertedDate = date($Format,strtotime($date));
		return $ConvertedDate;
	}
	if (!class_exists("DateTimeZone") || !class_exists("DateTime") ) {
		$ConvertedDate = date($Format,strtotime($date));
		return $ConvertedDate;
	}
	if (PHP_VERSION > "5.3") {
		if ($isDST) {
			$SourceTZDate	= DateTime::createFromFormat($SourceDateFormat, $date, new DateTimeZone($SourceTZ));
		} else if (strtolower($UTCTZ) == "UTC") {
			$SourceTZDate	= DateTime::createFromFormat($SourceDateFormat, $date, new DateTimeZone($UTCTZ));
		} else {
			return ConvertDateUTC($date,$Format,$ConvertTZ,$UTCTZ); //this is for no DST effect.
		}
		// check source datetime
		$warning_count	= DateTime::getLastErrors();
		if($SourceTZDate && $warning_count['warning_count'] == 0 && $warning_count['error_count'] == 0) {
			//convert timezone
			if ($isDST) {
				$SourceTZDate->setTimeZone(new DateTimeZone($ConvertTZ));
			} else {
				$SourceTZDate->setTimeZone(new DateTimeZone($ConvertTZ));
			}
			//convert dateformat
			$ConvertedDate = $SourceTZDate->format($Format);
		} else {
			$ConvertedDate = date($Format,strtotime($date));
		}
	} else {
		$SourceTZDate = new DateTime();
		$Year 	= date("Y",strtotime($date));
		$Month 	= date("m",strtotime($date));
		$Day 	= date("d",strtotime($date));
		$Hour 	= date("H",strtotime($date));
		$Minute = date("i",strtotime($date));
		$Second = date("s",strtotime($date));
			
		if ($isDST) {
			$SourceTZDate->setDate($Year,$Month,$Day);
			$SourceTZDate->setTime($Hour,$Minute,$Second);
		} else if (strtolower($UTCTZ) == "UTC") {
			$SourceTZDate->setDate($Year,$Month,$Day);
			$SourceTZDate->setTime($Hour,$Minute,$Second);
		} else {
			return ConvertDateUTC($date,$Format,$ConvertTZ,$UTCTZ); //this is for no DST effect.
		}
		// check source datetime
		if($SourceTZDate) {
			//convert timezone
			if ($isDST) {
				$SourceTZDate->setTimeZone(new DateTimeZone($ConvertTZ));
			} else {
				$SourceTZDate->setTimeZone(new DateTimeZone($ConvertTZ));
			}
			//convert dateformat
			$ConvertedDate = $SourceTZDate->format($Format);
		} else {
			$ConvertedDate = date($Format,strtotime($date));
		}
	}
	return $ConvertedDate;
}

/**
 * ConvertDateTimeWebToDB
 * Behaviour : public
 * @param : datetime $date
 * @param : string $ConvertTZ
 * @param : string $Format
 * @param : string $SourceTZ
 * @param : string $SourceDateFormat
 * @return : datetime $ConvertedDate
 * @defination : method to convert customer time to database time with timezone.
 */
function ConvertDateTimeWebToDB($date,$ConvertTZ="UTC",$Format="Y-m-d H:i:s",$SourceTZ="",$SourceDateFormat="Y-m-d H:i:s",$isDST=true,$UTCTZ="UTC")
{
	return ConvertDateTimeDBToWeb($date,$ConvertTZ,$Format,$SourceTZ,$SourceDateFormat,$isDST,$UTCTZ);
}

/**
 * ConvertDateUTC
 * Behaviour : public
 * @param : datetime $date
 * @param : string $format
 * @param : string $ConvertTZ
 * @param : string $TimeZone
 * @return : datetime $ConvertedDate
 * @defination : method to convert datetime using cake utility.
 */
function ConvertDateUTC($date,$format,$ConvertTZ,$TimeZone)
{
	App::uses("CakeTime", "Utility");
	$sign = "";
	if (strtoupper($ConvertTZ) == "UTC") {
		if (substr($TimeZone,0,1) == "-") {
			$TimeZone = str_replace("-","+",$TimeZone);
		} else if (substr($TimeZone,0,1) == "+") {
			$TimeZone = str_replace("+","-",$TimeZone);
			$sign = "-";
		}
	}
	$TimeZone	= str_replace(":", ".",$TimeZone);
	$int		= (int)$TimeZone;
	$pos		= abs($int-$TimeZone);
	$pos		= ($pos/60)*100;
	$TimeZone	= $int+($sign.$pos);
	return CakeTime::format($format,$date,true,$TimeZone);
}

/**
 * _d
 * Behaviour : public
 * @param : mixed $x
 * @return :
 * @defination : This method will print the argument with proper html formatting.
 */
function _d($x)
{
	echo "<pre>";
	print_r($x);
	echo "</pre>";
}



/**
 * GetWebsiteIPList
 * Behaviour : public
 * @defination : Resolve IPs by the hostname of website URL
 * @websiteURL : the website URL
 * @return : The array of IP string
 */
function GetWebsiteIPList($websiteURL)
{
	// Get host from website URL
	$parse = parse_url($websiteURL);
	if (empty($parse)) return null;

	$website_resolved_ip = "";
	if (array_key_exists('host', $parse))
	{
		$hostname = $parse['host'];

		// Get IP addresses by hostname
		$website_resolved_ip = gethostbynamel($hostname);
	}
	
	return $website_resolved_ip;
}


/**
 * is_ip_in_range
 * Behaviour : public
 * @defination : Check is the specific IP address is in an IP range or not
 * @ip : the IP address
 * @range : the IP range with CIDR format (ex: 192.168.0.1/24)
 * @return : true, if @ip is in @range
 */
function is_ip_in_range( $ip, $range ) 
{
	if ( strpos( $range, '/' ) == false ) 
	{
		$range .= '/32';
	}

	list( $range, $netmask ) = explode( '/', $range, 2 );
	$range_decimal = ip2long( $range );
	$ip_decimal = ip2long( $ip );
	$wildcard_decimal = pow( 2, ( 32 - $netmask ) ) - 1;
	$netmask_decimal = ~ $wildcard_decimal;
	return ( ( $ip_decimal & $netmask_decimal ) == ( $range_decimal & $netmask_decimal ) );
}


/**
 * @name getRandomNumber
 * @uses getting random number key
 * @param int $limit
 * @return $randnum
 * @author Kalpak Prajapati
 * @since 2008-08-20
 */
function getRandomNumber($limit=10)
{
	$randnum = strtolower(substr(md5(uniqid(mt_rand())),0,$limit));
	return $randnum;
}

/**
 * @name getUtf8forJson
 * @uses getting random number key
 * @param int $limit
 * @return $randnum
 * @author Kalpak Prajapati
 * @since 2008-08-20
 */
function getUtf8forJson($value=null)
{
	$randnum = htmlentities( (string) $value, ENT_QUOTES, 'utf-8', FALSE);
	return $randnum;
}
/**
 * @name getRoundLatLong
 * @uses getting Lat Long Value as Per Logic
 * @param int $val
 * @return $returnVal
 * @author Pravin Sanghani
 * @since 2015-11-19
 */
function getRoundLatLong($val) { 
	$returnVal 		= 0;
	$firstDecimal 	= 0;
	$lastDecimal 	= 0;
	if(!empty($val)) {
		$arrResult 	= explode('.',round($val, 2));
		$whole 		= isset($arrResult[0])?$arrResult[0]:0;
		$decimal 	= isset($arrResult[1])?$arrResult[1]:0;
		if (isset($decimal) && $decimal > 0) {
			$firstDecimal 	= substr($decimal,0,1);
			$lastDecimal 	= (substr($decimal,1)!='')?substr($decimal,1):0;
		}
		$returnVal 		= floatval($whole.".".$firstDecimal.str_replace($lastDecimal , '5' , $lastDecimal));
	}
	return $returnVal;
}
/**
 * @name GetLocationByLatLong
 * @uses getting Lat Long Value as Per Logic
 * @param int $val
 * @return $returnVal
 * @author Khushal Bhalsod
 * @since 2015-11-19
 */
function GetLocationByLatLong($lat, $lng)
{
    //$api_key    = "AIzaSyDjQMLis6ro0ao-2Bh_tWt5CvrzA6961AI";
    $api_key    = GOOGLE_MAP_KEY;//"AIzaSyD2eOQS5UOkDNZQX6OY0YBcBSsfiErrjh0";
    $url        = 'https://maps.googleapis.com/maps/api/geocode/json?&key='.$api_key.'&latlng='.trim($lat).','.trim($lng).'&sensor=false';
    
    $ch         = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL,$url);
    $result     = curl_exec($ch);
    curl_close($ch);
    $data   	= json_decode($result);
    $status 	= isset($data->status)?$data->status:"";
    $arrResult 	= array();
    if($status == "OK") {
        $result                 = $data->results[0]->address_components;
        $arrResult['address']   = '';
        if(!empty($result)) {
            for($i=0;$i<count($result); $i++) {
                if (is_array($result[$i]->types)) {
                    if(in_array('premise',$result[$i]->types)) {
                        $arrResult['address'] .= $result[$i]->long_name.", ";
                    }
                    if(in_array('street_number',$result[$i]->types)) {
                        $arrResult['address'] .= $result[$i]->long_name.", ";
                    }
                    if(in_array('route',$result[$i]->types)) {
                        $arrResult['address'] .= $result[$i]->long_name.", ";
                    }
                    if(in_array('sublocality_level_1',$result[$i]->types)) {
                        $arrResult['address'] .= $result[$i]->long_name.", ";
                    }
                    if(in_array('postal_code',$result[$i]->types)) {
                        $arrResult['postal_code'] = (isset($result[$i]->long_name))?$result[$i]->long_name:'';
                    }
                    if(in_array('locality',$result[$i]->types)) {
                        $arrResult['city'] = (isset($result[$i]->long_name))?$result[$i]->long_name:'';
                    }
                    if(in_array('administrative_area_level_1',$result[$i]->types)) {
                        $arrResult['state'] = (isset($result[$i]->long_name))?$result[$i]->long_name:'';
                        $arrResult['state_short_name'] = (isset($result[$i]->short_name))?$result[$i]->short_name:'';
                    }
                    if(in_array('country',$result[$i]->types)) {
                        $arrResult['country'] = $result[$i]->long_name;
                    }
                }
            }
            $arrResult['address'] = rtrim($arrResult['address'],", ");
        }
        $arrResult['landmark'] = (isset($data->results[0]->formatted_address)? trim($data->results[0]->formatted_address):'');
    }
    return $arrResult;
}

function _FormatGroupNumber($Amount=0){
	$newAmount = $Amount;
	if(!empty($Amount)) {
		$newAmount = number_format($Amount);
	}
	return $newAmount;
}

function _FormatGroupNumberV2($Amount=0){
	$newAmount = $Amount;
	if(!empty($Amount)) {
		$newAmount = number_format($Amount,2);
	}
	return $newAmount;
}

function _FormatNumber($Amount=0){
	$newAmount = $Amount;
	if(!empty($Amount) && $Amount > 0) {
		$newAmount = str_replace(",","",$Amount);
	}
	return $newAmount;
}

function _FormatNumberV2($Amount=0){
	setlocale(LC_MONETARY, 'en_IN');
	$newAmount = $Amount;
	if(!empty($Amount) && $Amount > 0) {
		if (!function_exists("money_format")) 
		{  
			$newAmount = number_format($Amount,2,'.',',');
		}
		else
		{
			$newAmount = money_format('%!i', $Amount);
		}
	} else {
		$newAmount = ($Amount == 0)?"0.00":$Amount;
	}
	return $newAmount;
}


/**
 * @param : mixed $x
 * @return :
 * @defination : This method will print the argument with proper html formatting.
 */
function prd($x)
{
	echo "<pre>";
	print_r($x);
	echo "</pre>";
	exit;
}

function isValidEmail($email)
{
	$valid = preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i", $email);
	if ($valid)
	{
		$test1=strpos($email, "@"); 								//value must be >1
		$test2=strpos(substr($email,strpos($email,"@")), ".");    	//value must be >1
		$test3=strlen($email);                                    	//value must be >6
		$test4=substr_count ($email,"@");                         	//value must be 1
		if ($test1<2 or $test2<2 or $test3<7 or $test4!=1) {
			$valid = false;
		}
		$email_server=substr($email,strpos($email, "@")+1);
		if(defined("CONFIG_SERVER") && CONFIG_SERVER != "LOCAL") {
			if (checkdnsrr($email_server)!=1) {
				$valid = false;
			}
		}
	}
	return $valid;
}
function passencrypt($str)
{
    $lngth = strlen($str);
    $str = substr($str, 0, $lngth);
    $str = str_pad($str, $lngth, " ");
    $retstr = "";
    for ($i = 0; $i < $lngth; $i++) {
        $sch = substr($str, $i, 1);
        $iasc = ord($sch) + 2 * $i + 30;
        if ($iasc > 255)
            $iasc = $iasc - 255;
        $sch = chr($iasc);
        $retstr = $retstr . $sch;
    }
    $retstr = implode("*", unpack('C*', $retstr));

    return $retstr;
}
function passdecrypt($pass)
{
    $retstr = "";
    $string = '';
    $data = explode('*', $pass);

    for ($i = 0; $i < count($data); $i++) {
        if ($data[$i] != '')
            $string = $string . pack('C*', $data[$i]);
    }
    $str = $string;
    $lngth = strlen($str);
    for ($i = 0; $i < $lngth; $i++) {
        $sch = substr($str, $i, 1);
        $iasc = ord($sch) - 2 * $i - 30;
        if ($iasc <= 0)
            $iasc = 255 + $iasc;
        $sch = chr($iasc);
        $retstr = $retstr . $sch;
    }
    return trim($retstr);
}
/**
* Function Name : getIndianCurrency
* @param : $number
* @return : 
* @author Kalpak Prajapati
*/
function getIndianCurrency($number)
{
    $decimal = round($number - ($no = floor($number)), 2) * 100;
    $hundred = null;
    $digits_length = strlen($no);
    $i = 0;
    $str = array();
    $words = array(0 => '', 1 => 'one', 2 => 'two',
        3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
        7 => 'seven', 8 => 'eight', 9 => 'nine',
        10 => 'ten', 11 => 'eleven', 12 => 'twelve',
        13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
        16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
        19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
        40 => 'forty', 50 => 'fifty', 60 => 'sixty',
        70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
    $digits = array('', 'hundred','thousand','lakh', 'crore');
    while( $i < $digits_length ) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
        } else $str[] = null;
    }
    $Rupees = implode('', array_reverse($str));
    $paise = ($decimal) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
    //return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;
    return ($Rupees ? "INR ".$Rupees." Only ": '');
}
 /**
 * Function Name : GetGenerateReceiptNo
 * @param $date
 * @return
 * @author Kalpak Prajapati
 */
function GetGenerateReceiptNo($recipt_no=0,$date="")
{
	$Month   = date("m",strtotime($date));
	$Year   = date("Y",strtotime($date));
	$ChallanNo  = "ER/";
	if (intval($Month) >= 1 && intval($Month) <= 3) {
	$ChallanNo  .= ($Year-1)."-".date("y",strtotime($date))."/";
	} else {
	$ChallanNo  .= $Year."-".(date("y",strtotime($date))+1)."/";
	}
	$ChallanNo .= str_pad($recipt_no,4,"0",STR_PAD_LEFT);
	return $ChallanNo;
}
function cryptAES($plaintext,$symmetricKey)
{
	include('cryptlib/Crypt/AES.php');
	$aes = new Crypt_AES();
	$aes->setKey($symmetricKey);
	$size = 10 * 1024;
	for ($i = 0; $i < $size; $i++) {
	    //$plaintext.= 'a';
	}
	return base64_encode($aes->encrypt($plaintext));
}
function cryptRSA($plaintext)
{
	include('cryptlib/Crypt/RSA.php');

	$rsa = new Crypt_RSA();
/*$rsa->loadKey('MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAtUlK8MdCzJb5ROqmfW6B
/KnXsAhWaHM8JNV3XmY0yyzZw4QsQKaqGoAvujKSwQeS1Uq+uJGcRXvmoWrMlqWA
cLeGxswGCCVptS/gu2JP/hQ+r3bo7Xv9Jb4KdVQN7IGJUt9BZ4lb9tWRjgseSTNx
sicFUpVj68Xw+ZWYZXdhARm3TtkhYmNKuMstVe9rA7dTQdAj9D/MJFZ7r+axC9n0
uj6M6I2QdS5EoV+Bvoerb669duen6dvgFBRJSp93dO0WpotJT+z9oeCbJEUIxgK/
Td/mjUWgD0+DbR8KIkZ9OLCB2rFXH0UzkLCEpooWeGW7ZA8nmsU7/eQrPBcx3EdU
xwIDAQAB'); // HDFCpublic key for UAT*/
$rsa->loadKey('MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAswriruPkyv8lKMMr8cAF
h+EpJBFmdzXqnWyHu1bmpYXJzIZAWgfRLFOfKw6IYEF6WihA8yIJU5psttI/QDdl
QjXfoNWg1nA+5GMkwGmceEFsZDWHAD5QlMdh8WK3gk07Ws5yRtSUMieSW+JCf0mX
s1HU/6KTFMJlxAERz4+p2I8FQoKgucicRRyfW040vyZEfgdbm4vSLdfsdzIcLFbZ
sy7CN0ZCCncln6Bc5hbFLf1kUAwiD3Ta2p1DA7MuGYHcALs8W3b0ghA5lD3FWYPX
jmNpFD6gdNuO9Hq9OVyFZ/TmCOw6jWn7OHGdNUTVlcQVlx7+V52S4QKWWDLhM+es
KQIDAQAB'); // HDFCpublic key for Live
/*$rsa->loadKey('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
ypB1ksAMzzKKK/LEtdnCfm9Haq/ze0KJZo01uLI='); // HDFCpublic key*/

	
	$rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
	$ciphertext = $rsa->encrypt($plaintext);
	return base64_encode($ciphertext);
}
function dcryptRSA($plaintext)
{

	//include('cryptlib/Crypt/RSA.php');
	$private_key = <<<EOD
-----BEGIN PRIVATE KEY-----
MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQDTEmGRJsrzMqda
U1BV17F9LIksUlFcHgn0AuPFXUy9XjUBy+1WYLmawd674agDi6DLxbux6AYC/NTT
iNNF5fbKpSIi0/8qOzSM/WL75yC1WZCmImt0+KtQzum6KA5S2ooWyvFR6c6qthj1
H+2JZxXjomIk1u5DnZPvdcAbgAORZ3Eftb5SnGGoPW0iS0+ymOIey6qbS0JSNeqY
puaK5qpivAvcL2ESZ9yFBEEAzOsl2XQhhLY/tWxRppUjisMlj+KDY4Obh6R6SPr4
+Gmmj72ef61RsDONx2dsmQATngU0BgsdhB4zZtj1ljtaPABb5RGtXAVlk848kfl9
B7UNvKdlAgMBAAECggEAIJ1wVnG5njnKCALkC4JgX+NqsN6+yVlaQrUoRVOPlkVr
3cK0AmBriGEuYzbRHkx273Xhn8cmwqhsfza0etJi6lj4/OES8Tojz9N0S6YhH5S0
Qs+tGMPiE62ytRFtITAOyCCE3e+fKobSisdruOB6eT8azkQonhRyixKADwp0GAVY
EY7RRITP0kotem5TtZ8BtRQn2yEDyYLLO+0pAA7vUsiD+u2IG+xqB5fG2t7zDw0/
/baFqW/KJ1km+0tbQmT7zXvy/d9F5l9TrEe8L/jXibPG9+sQLhOg5v3f2hgva8rj
Lq8N9ol+dEXwOaHts410G5Aijz2w3kaLeSjO5nu/YwKBgQDYSGYVyI313PbI+tJ5
NqSePNP48CW2HXhTBF2SjcQykBZpwd/cfYULb2z/e8aS7xGgsKjOsj0Z74SNC9vj
2nb3N5dWtdXiFW6JQLMmEq49bdeFo/bubMBCmI2ZnQtleamPUm6v/liw1yqkzKkA
fYaXZZS5EQwqnlfVPPwUPKubMwKBgQD51QJotF87CksRF8hc+dpFCPKVw/uSymez
AYnDSdI+JHarjN416c6oWcUeRbE30614msWZPyBRXDjzoEE5zugByYBVaooJ4Ep8
R5gjlekwi9kzeqiYhr6Kwpcuqfkjy+FoSMRK6jiUdrPogLQAbwWqy5iLikncrKnx
F5qcbF7zBwKBgFz3MbonTK3j3sgg2Bt2G2hQ6SRVxT/0huXYOIhoG29Ic/nddeYG
pgt2R7nBcGd0D3WsucKu5oihZa5i7I+SNhSpdom0+0yEvdCNWPQCj5akAkHVaqyt
Xi7B+AuRb3acxv9uBVns0B6jPhc8SWCGlDW7WiP6aepfyY1E+22Pbov1AoGAVzKL
hrP90QOEs9CTNDBYiGPZF4Cx28gdbZMJ3El1wg7EBJhELpkOch/y9t/oPM366+9J
LHWl9/+yOQYj/eNDguwriKSIzW2lUb9DUJhQLYuCIb+b/LB67L+CON1GgcH1SIqt
SGB7owXTQUE6kjQtzDEHaxy3Lvhs0CMm6ZXBhh0CgYAUtjf6nl8Sr1w7pNAMOrQr
MoC0mHw7UfOYiDapCJClCoFki95IAI8JX/zMvsgLr/MB2+GEJPMtBSOPxDBCMe/a
HlwgwJt+t7sBDIgTooL44WinMDIN60wEnwfPBfOMSl+5S3gw4W4vTub6fObAF5q+
iyfwmQQXm8uupBEsmE3liQ==
-----END PRIVATE KEY-----
EOD;
$pkey_private = openssl_pkey_get_private($private_key);
//echo 'pkey_private==>';
//echo $pkey_private;
openssl_private_decrypt(base64_decode($plaintext), $decrypted, $pkey_private);
return $decrypted;
/*	$rsa = new Crypt_RSA();
$rsa->loadKey($private_key); // HDFCpublic key

	
	$rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
	$ciphertext = $rsa->decrypt($plaintext);
	return $ciphertext;*/
}
function decryptAES($plaintext,$symmetricKey)
{
	//include('cryptlib/Crypt/AES.php');
	
	$aes = new Crypt_AES();
	$decode   = base64_decode($plaintext);
	$aes->setKey($symmetricKey);

	$size = 10 * 1024;
	for ($i = 0; $i < $size; $i++) {
	   // $plaintext.= 'a';
	}
	return $aes->decrypt($decode);
}
function generateXMLSignature($xmlData,$private_key,$certificateValue,$requestURIId)
{
	$xmlseclibs_srcdir = dirname(__FILE__) . '/xmlsignature/src/';
	require $xmlseclibs_srcdir . '/XMLSecurityKey.php';
	require $xmlseclibs_srcdir . '/XMLSecurityDSig.php';
	require $xmlseclibs_srcdir . '/XMLSecEnc.php';
	require $xmlseclibs_srcdir . '/Utils/XPath.php';

	$doc 		= new DOMDocument();

	//$doc->load('createXML.xml');
	$doc->loadXml($xmlData);

	// Create a new Security object 
	$objDSig = new XMLSecurityDSig('');
	// Use the c14n exclusive canonicalization
	//$objDSig->setCanonicalMethod(XMLSecurityDSig::EXC_C14N);
	$objDSig->setCanonicalMethod(XMLSecurityDSig::C14N);
	// Sign using SHA-256
	$objDSig->addReference(
	    $doc, 
	    XMLSecurityDSig::SHA256, 
	    null,
	    array('force_uri' => false,'id_name' => 'Id','overwrite'=>false),
	    $requestURIId
	);

// Create a new (private) Security key
$objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA256, array('type'=>'private'));

/*
If key has a passphrase, set it using
$objKey->passphrase = '<passphrase>';
*/
// Load the private key
$objKey->loadKey($_SERVER['DOCUMENT_ROOT'].'/key_data/govapi.key', TRUE);

//$objKey->loadKey($private_key, FALSE);

	// Sign the XML file
	$objDSig->sign($objKey);

	// Add the associated public key to the signature

//$objDSig->add509Cert($certificateValue);
//$objDSig->add509Cert(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/key_data/geda.pfx'));
$objDSig->add509Cert(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/key_data/ServerCertificate.crt'));

	// Append the signature to the XML
// /,$doc->parentNode
	
	$xmlOutput 	= $objDSig->appendSignature($doc->documentElement);

	//$xmlOutput 	= $objDSig->appendToKeyInfo($doc->documentElement);
	
	// Save the signed XML
	//$returnXML 	= $doc->save('signed.xml');
	$digitalSignature 	= $doc->saveXML();
	
	//documentElement
$digitalSignature 	= str_replace(array('<?xml version="1.0"?>
'), array(''), $digitalSignature);

	return $digitalSignature;
}
function generateGUID($prefix='pfx')
{
    $uuid = md5(uniqid(mt_rand(), true));
    $guid = $prefix.substr($uuid, 0, 8).
            substr($uuid, 8, 4).
            substr($uuid, 12, 4).
            substr($uuid, 16, 4).
            substr($uuid, 20, 12);
    return $guid;
}
function validateSignature($xmlData)
{
	/*$xmlseclibs_srcdir = dirname(__FILE__) . '/xmlsignature/src/';
	/*require $xmlseclibs_srcdir . '/XMLSecurityKey.php';
	require $xmlseclibs_srcdir . '/XMLSecurityDSig.php';
	require $xmlseclibs_srcdir . '/XMLSecEnc.php';
	require $xmlseclibs_srcdir . '/Utils/XPath.php';*/

	$doc 			= new DOMDocument();
	$doc->loadXml($xmlData);
	$objXMLSecDSig 	= new XMLSecurityDSig('');
    $objDSig 		= $objXMLSecDSig->locateSignature($doc);
    $objXMLSecDSig->canonicalizeSignedInfo();
	$retVal 		= $objXMLSecDSig->validateReference();

	if (! $retVal) {
		return "Reference Validation Failed";
	}
    if (! $objDSig) {
   	 	return "Cannot locate Signature Node";
    }
    $objKey = $objXMLSecDSig->locateKey();
   
    $objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA256, array('type'=>'public'));
/************HDFC public key for UAT*****************/
   /* $public_key = <<<EOD
-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAtUlK8MdCzJb5ROqmfW6B
/KnXsAhWaHM8JNV3XmY0yyzZw4QsQKaqGoAvujKSwQeS1Uq+uJGcRXvmoWrMlqWA
cLeGxswGCCVptS/gu2JP/hQ+r3bo7Xv9Jb4KdVQN7IGJUt9BZ4lb9tWRjgseSTNx
sicFUpVj68Xw+ZWYZXdhARm3TtkhYmNKuMstVe9rA7dTQdAj9D/MJFZ7r+axC9n0
uj6M6I2QdS5EoV+Bvoerb669duen6dvgFBRJSp93dO0WpotJT+z9oeCbJEUIxgK/
Td/mjUWgD0+DbR8KIkZ9OLCB2rFXH0UzkLCEpooWeGW7ZA8nmsU7/eQrPBcx3EdU
xwIDAQAB
-----END PUBLIC KEY-----
EOD;    */
/*************HDFC public key for live*****************/
$public_key = <<<EOD
-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAswriruPkyv8lKMMr8cAF
h+EpJBFmdzXqnWyHu1bmpYXJzIZAWgfRLFOfKw6IYEF6WihA8yIJU5psttI/QDdl
QjXfoNWg1nA+5GMkwGmceEFsZDWHAD5QlMdh8WK3gk07Ws5yRtSUMieSW+JCf0mX
s1HU/6KTFMJlxAERz4+p2I8FQoKgucicRRyfW040vyZEfgdbm4vSLdfsdzIcLFbZ
sy7CN0ZCCncln6Bc5hbFLf1kUAwiD3Ta2p1DA7MuGYHcALs8W3b0ghA5lD3FWYPX
jmNpFD6gdNuO9Hq9OVyFZ/TmCOw6jWn7OHGdNUTVlcQVlx7+V52S4QKWWDLhM+es
KQIDAQAB
-----END PUBLIC KEY-----
EOD;

	$objKey->loadKey($public_key);
	if ($objXMLSecDSig->verify($objKey)) {
		return 1;
	} else {
		return 'Signature Verification Failed From Response.';
	}
}
function getRandomString($n) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$randomString = '';

	for ($i = 0; $i < $n; $i++) {
		$index = rand(0, strlen($characters) - 1);
		$randomString .= $characters[$index];
	}

	return $randomString;
	}
?>