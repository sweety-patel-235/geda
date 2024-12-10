<?php
/************************************************************
* File Name : couchdb			  							*
* purpose	: Application Base Class file 					*
* @package  : 												*
* @since 	: 05/07/2021									*
************************************************************/
namespace Couchdb;

class Couchdb {

	public $NOW;
	public $HOST;
	public $PORT;
	public $USER;
	public $PASSWORD;
	public $DATABASE;
	public $HEADERS;
	public $BODY;
	public $HTTP;
	
	public function __construct()
	{
		$this->NOW 	         		= date("Y-m-d H:i:s");
		$this->HOST					= COUCHDB_HOST;
		$this->PORT					= COUCHDB_PORT;
		$this->USER					= COUCHDB_USER;
		$this->PASSWORD				= COUCHDB_PASSWORD;
		$this->DATABASE				= COUCHDB_DATABASE;
		$this->HTTP					= COUCHDB_HTTP;
	}
	/**
	 * send
	 * Behaviour : public
	 * @param : method, url, post_data
	 * @defination : Method is use to store, Get, Delete data from couchdb
	 */
	public function send($method, $url, $post_data = NULL) {  
		$s = fsockopen($this->HOST, $this->PORT, $errno, $errstr);   
		if(!$s) {  
			echo "$errno: $errstr\n";   
			return false;  
		}   

		$request = "$method $url HTTP/1.0\r\nHost: $this->HOST\r\n";   
		echo $request;
		if ($this->USER) {  
			$request .= "Authorization: Basic ".base64_encode("$this->USER:$this->PASSWORD")."\r\n";   
		}  
		if($post_data) {  
			$request .= "Content-Length: ".strlen($post_data)."\r\n\r\n";   
			$request .= "$post_data\r\n";  
		}   
		else {  
			$request .= "\r\n";  
		}  
		fwrite($s, $request);   
		$response = "";   
		while(!feof($s)) {  
			$response .= fgets($s);  
		}  
		list($this->HEADERS, $this->BODY) = explode("\r\n\r\n", $response);   
		return $this->BODY;  
	}  
	/**
	 * upload
	 * Behaviour : public
	 * @param : path, rev, filepath, $filename, $content_type
	 * @defination : Method is use to upload attachment to couchdb database
	 */
	public function upload($path, $rev, $filepath, $filename, $content_type='') {
		
		$ch 		= curl_init();
		$url 		= $this->HTTP."://".$this->HOST.":".$this->PORT;
		$fullpath 	= $url.$path.$filename.'?rev='.$rev;	

		$data 		= file_get_contents($filepath.$filename);

		$options 	= array(
			CURLOPT_URL 			=> $fullpath,
			CURLOPT_RETURNTRANSFER 	=> true,
			CURLOPT_CUSTOMREQUEST 	=> 'PUT',
			CURLOPT_HTTPHEADER 		=> array (
				"Content-Type: ".$content_type,
				"Authorization: Basic ".base64_encode("$this->USER:$this->PASSWORD"),
			),
			CURLOPT_POST 			=> true,
			CURLOPT_POSTFIELDS 		=> $data
		);
		curl_setopt_array($ch, $options);
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
	}
	/**
	 * addDcoument
	 * Behaviour : public
	 * @param : documentName, arrDocumentData, storeFilePath, storeFileName
	 * @defination : Method is use to add document to couchdb database
	 */
	public function addDcoument($documentName,$arrDocumentData,$storeFilePath,$storeFileName,$MimeType='') {
		$ch 		= curl_init();
		$payload 	= json_encode($arrDocumentData);
		$url 		= $this->HTTP."://".$this->HOST.":".$this->PORT."/".$this->DATABASE."/".$documentName;
		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT'); /* or PUT */
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-type: application/json',
			"Authorization: Basic ".base64_encode("$this->USER:$this->PASSWORD"),
			'Accept: */*'
		));
		 
		//curl_setopt($ch, CURLOPT_USERPWD, 'admin:admin@123');
		 
		$response 	= curl_exec($ch);
		 
		curl_close($ch);
		$arrResponse= json_decode($response,2);

		if(isset($arrResponse['rev']) && !empty($arrResponse['rev'])) {
			if(empty($MimeType)) {
				//$MimeType = $this->get_mime_type($storeFilePath.$storeFileName);
			}
			$response = $this->upload('/'.$this->DATABASE."/".$documentName."/", $arrResponse['rev'], $storeFilePath, $storeFileName,$this->get_mime_type($storeFilePath.$storeFileName));
		}
 		return $response;
	}
	/**
	 * get_mime_type
	 * Behaviour : public
	 * @param : file
	 * @defination : Method is use to retrive mime type of document
	 */
	public function get_mime_type($file) {
		$mtype	= '';
		$ch 	= curl_init($file);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1) ;
		$data 	= curl_exec($ch);
		if(!curl_errno($ch))
		{
			$mtype = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
		}
		curl_close($ch);
		if(empty($mtype)) {
			$mtype = false;
			if (function_exists('finfo_open')) {
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				$mtype = finfo_file($finfo, $file);
				finfo_close($finfo);
			} elseif (function_exists('mime_content_type')) {
				$mtype = mime_content_type($file);
			} 
		}
		return $mtype;
	}
	/**
	 * getDocument
	 * Behaviour : public
	 * @param : documentName, storedFile, storedMimetype
	 * @defination : Method is use to get attached document details from couchdb
	 */
	public function getDocument($documentName,$storedFile,$storedMimetype,$returnResponse=0) {
	//	$response 	= $this->send("GET", "/".$this->DATABASE."/".$documentName."/".$storedFile); 
	//	header("Content-type: ".$storedMimetype);
	//	echo $response;
		$ch 		= curl_init();
		$url 		= $this->HTTP."://".$this->HOST.":".$this->PORT."/".$this->DATABASE."/".$documentName."/".$storedFile;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET'); /* or PUT */
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-type: '.$storedMimetype,
			"Authorization: Basic ".base64_encode("$this->USER:$this->PASSWORD"),
			'Accept: */*'
		));
		$response = curl_exec($ch); 
		curl_close($ch);
		if($returnResponse == 1) {
			return $response;
		}
		header("Content-type: ".$storedMimetype);
		echo $response;
		exit;
	}
	/**
	 * deleteDocument
	 * Behaviour : public
	 * @param : documentId, rev
	 * @defination : Method is use to delete document from couchdb
	 */
	public function deleteDocument($documentId,$rev) {
		$ch 		= curl_init();
		$url 		= $this->HTTP."://".$this->HOST.":".$this->PORT."/".$this->DATABASE."/".$documentId.'?rev='.$rev;
		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE'); /* or PUT */
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-type: application/json',
			"Authorization: Basic ".base64_encode("$this->USER:$this->PASSWORD"),
			'Accept: */*'
		));
		 
		//curl_setopt($ch, CURLOPT_USERPWD, 'admin:admin@123');
		 
		$response = curl_exec($ch); 
		curl_close($ch);
		return $response;
	}
	/**
	 * documentExist
	 * Behaviour : public
	 * @param : documentId
	 * @defination : Method is use to check document exist or not on couchdb
	 */
	public function documentExist($documentId) {
		$ch 		= curl_init();
		$url 		= $this->HTTP."://".$this->HOST.":".$this->PORT."/".$this->DATABASE."/".$documentId;
		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET'); /* or PUT */
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-type: application/json',
			"Authorization: Basic ".base64_encode("$this->USER:$this->PASSWORD"),
			'Accept: */*'
		));
		 
		//curl_setopt($ch, CURLOPT_USERPWD, 'admin:admin@123');
		 
		$response = curl_exec($ch); 
		$arrResponse 	= json_decode($response,2);
		curl_close($ch);
		if(isset($arrResponse['_id']) && !empty($arrResponse['_id']))
		{
			return true;
		}
		return false;
	}
}
