<?php
namespace App\Model\Table;

use App\Model\Table\Entity;
use App\Model\Entity\User;
use Guvnl\Guvnl;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Network\Email\Email;
use Cake\Utility\Security;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Datasource\ConnectionManager;

class ReThirdpartyApiLogTable extends AppTable
{
	var $table                  = 're_thirdparty_api_log';
	var $print_api_response		= false;
	var $apitimestamp           = '';
	var $data                   = array();
	var $p_con_details          = array();
	var $apidiscom              = '';
	var $apiconsumer            = '';
	var $apiapplication_id      = '0';
	var $apiproject_id          = '0';
	var $apiaction              = '';
	var $api_t_no               = '';
	//var $APIURL                 = 'https://122.169.105.79/api/apicall.php';
	//var $APIURL                 = 'https://gsdcgate1.gujarat.gov.in:8082/apiCall';
	//var $APIURL                 = 'https://gsdcgateext.gujarat.gov.in:8243/geda_miscapicall/v1';
	var $APIURL                 = 'https://epaydg.guvnl.in:8001/outer_client_json.php';
	var $GSDC_API_KEY			= '76468a8c-56e2-4a59-94a0-8a4b7c17f369';
	//var $TOREENTAPIURL          = 'https://connect.torrentpower.com/tplwss/geda/call_geda.php';
	// var $APIURL                 = 'https://gsdcgateext.gujarat.gov.in:8243/geda_miscapicall/v1';
	var $TOREENTAPIURL          = 'https://gsdcgateext.gujarat.gov.in:8243/geda_torrent_app/v1';
	var $TOREENTAPI_KEY         = 'test';
	var $HMAC_HASH_PRIVATE_KEY  = '9XTMgu0h0R14lm8vO2cQYG8SFMe4A50j';
	var $BEARER_TOKEN 			= BEARER_TOKEN;//"eyJ4NXQiOiJNell4TW1Ga09HWXdNV0kwWldObU5EY3hOR1l3WW1NNFpUQTNNV0kyTkRBelpHUXpOR00wWkdSbE5qSmtPREZrWkRSaU9URmtNV0ZoTXpVMlpHVmxOZyIsImtpZCI6Ik16WXhNbUZrT0dZd01XSTBaV05tTkRjeE5HWXdZbU00WlRBM01XSTJOREF6WkdRek5HTTBaR1JsTmpKa09ERmtaRFJpT1RGa01XRmhNelUyWkdWbE5nX1JTMjU2IiwiYWxnIjoiUlMyNTYifQ.eyJzdWIiOiJnc2RjYXBpZ3dzdSIsImF1dCI6IkFQUExJQ0FUSU9OIiwiYXVkIjoiZGtqbTJ6Mm92NlBibDZEOWplR0dRdUV5R29jYSIsIm5iZiI6MTYyNzM4NTkxMiwiYXpwIjoiZGtqbTJ6Mm92NlBibDZEOWplR0dRdUV5R29jYSIsInNjb3BlIjoiZGVmYXVsdCIsImlzcyI6Imh0dHBzOlwvXC9nc2RjYXBpZ2F0ZXdheWV4dC5ndWphcmF0Lmdvdi5pbjo5NDQzXC9vYXV0aDJcL3Rva2VuIiwiZXhwIjoxOTg3Mzg1OTEyLCJpYXQiOjE2MjczODU5MTIsImp0aSI6IjFlYWU0MzNjLTVhOGQtNDE2MS05NGIzLWY2OThmNTdmYjU3MiJ9.c1N4EbVdEbdF9fgt3CvBeVtE4vIhavADdtJ3CFt17jf7TAw4LW__IB4tJi6CcgrVC6uVs3_7Wncp5h_tGF2XCc8PSqpeujiHAxtkzGhXMmNjlrBQkyhZccy8zPFQQDjUODvRWKLqfY5Q7qffFgoJz-DQCteP7eXjox3P8Rjm9MwLo0dRjrjHSvCYFjDdZRfqKxByyxxuWBluwGDvQJlsuRZeWCQ_37xkwDN79iq-DFhPn6qcrPiaFw8f4pRUseXLTq9DQeckZRiZKHiN0I3CeAVsXL9sJJ3G1NTv7syyuINeCEQ9i669L5QlhQkaKZWTFOgpwijp59Y7w_-AzqTQDA";
	var $SDC_AUTH 				= "";
	var $arr_discom_map         = array(''=>'0','11'=>'1','12'=>'2','13'=>'4','14'=>'3','15'=>'5','16'=>'6','17'=>'7');
	var $arr_category_map       = array('RESIDENTIAL'       =>'3001',
										'GENERAL LIGHTING'  =>'3005',
										'MSI'               =>'3002',
										'WATER WORKS'       =>'3005',
										'AGRICULTURAL'      =>'3005', 
										'STREET LIGHT'      =>'3005',
										'TEMPORARY'         =>'3005',
										'DUMMY CONSUMER'    =>'3005',
										'INFRASTRUCTURE'    =>'3005',
										'SOLAR'             =>'3005',
										'OTHERS'            =>'3005',
										'COMMERCIAL'        =>'3003',
										'HI'                =>'3006',
										'INDUSTRIAL'       	=>'3002',
										''       			=>'3005'
									);
	var $arr_phase_map          = array("p"         => '',
										"0"         => '',
										"1"         => '',
										"1P2W"      => '1',
										"1Ph2W"     => '1',
										"1phase"    => '1',
										"3 PHASE"   => '3',
										"3P2W"      => '3',
										"3P4W"      => '3',
										"3-PH."     => '3',
										"3Ph4W"     => '3',
										"A"         => '',
										"P"         => '',
										"PHASE"     => '',
										"POLY"      => '',
										"POLYPHASE" => '',
										"S"         => '1',
										"SINGLE"    => '1',
										"T"         => '3',
										"THREE"     => '3',
										"3"         => '3',
										""          => '3'
									);
	var $API_CALL_VIA_URL = false; //CALL ALL THIRDPARTY API VIA SUBDOMAIN OR NOT
	public function initialize(array $config)
	{
		$this->apitimestamp = date("d.m.Y.H.i.s");
		$this->table($this->table);
		$this->SDC_AUTH 	= "Authorization: Bearer ".$this->BEARER_TOKEN;
		$this->SDC_AUTH 	= "GSDC-Api-Key:".$this->GSDC_API_KEY;
	}
	/**
	 *
	 * third_party_call
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to call different different third party APIs.
	 *
	 */
	public function third_party_call($application_id, $drawing_cei_number, $api_type)
	{
			
		switch($api_type)
		{
			case 'drawing':
			//$api_url = 'http://www.nascentlayers.com/ceiced3/token/planApproval?appid='.$drawing_cei_number;
			//$api_url = 'http://ceiced.nascentinfo.com/ceiced/token/planApproval?appid='.$drawing_cei_number;
			//$api_url = 'https://ceicedeservice.gujarat.gov.in/ceiced/token/planApproval?appid='.$drawing_cei_number;
			//$api_url = 'https://gsdcgateext.gujarat.gov.in:8243/geda_testinsprpt/v1?appid='.$drawing_cei_number;
			//$api_url = 'https://gsdcgateext.gujarat.gov.in:8243/ceicedeserviceplanApproval/v1?appid='.$drawing_cei_number;
			$api_url = 'https://ceicedeservice.gujarat.gov.in/ceiced/token/planApproval?appid='.$drawing_cei_number;
			$response= 'Partial';
			break;
			case 'cei':
			//$api_url = 'http://www.nascentlayers.com/ceiced3/token/testInspReport?appid='.$drawing_cei_number;
			//$api_url = 'http://ceiced.nascentinfo.com/ceiced/token/testInspReport?appid='.$drawing_cei_number;
			//$api_url = 'https://ceicedeservice.gujarat.gov.in/ceiced/token/testInspReport?appid='.$drawing_cei_number;
			//$api_url = 'https://gsdcgateext.gujarat.gov.in:8243/geda_testinsprpt/v1?appid='.$drawing_cei_number;
			//$api_url = 'https://gsdcgateext.gujarat.gov.in:8243/geda_testinsprpt/v1?appid='.$drawing_cei_number;
			$api_url = 'https://ceicedeservice.gujarat.gov.in/ceiced/token/testInspReport?appid='.$drawing_cei_number;
			$response= 'Full';
			break;
		}
		$ch = curl_init($api_url);

		curl_setopt($ch, CURLOPT_FOLLOWLOCATION ,1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer ".BEARER_TOKEN));
		curl_setopt($ch, CURLOPT_HEADER,0);             // DO NOT RETURN HTTP HEADERS
		curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);   // RETURN THE CONTENTS
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT  ,0);
		$api_response                       = curl_exec($ch);
		$request_data                       = $drawing_cei_number;
		$thirdpartyEntity                   = $this->newEntity();
		$thirdpartyEntity->application_id   = $application_id;
		$thirdpartyEntity->request_data     = $request_data;
		$thirdpartyEntity->response_data    = $api_response;
		$thirdpartyEntity->api_url          = $api_url;
		$thirdpartyEntity->created          = $this->NOW();
		$this->save($thirdpartyEntity);
		$arr_response                       = json_decode($thirdpartyEntity->response_data);
		if(isset($arr_response->file_status))
		{
			return $arr_response->file_status;
		}
		else
		{
			return $arr_response->msg;
		}
	}
	public function searchConsumerApi($consumer_no,$discom_id,$project_id,$application_id,$t_no='')
	{
		$this->apiaction            = 'get_consumer_details';
		$this->apiconsumer          = $consumer_no;
		$this->apidiscom            = $this->arr_discom_map[$discom_id];
		$this->apiapplication_id    = !empty($application_id) ? $application_id : 0;
		$this->apiproject_id        = !empty($project_id) ? $project_id : 0;
		$this->api_t_no             = !empty($t_no) ? $t_no : 0;
		return $this->commonApi('search_consumer');
	}
	
	
	public function commonApi($action='')
	{   
		$Response                   = new \stdClass();
		if(API_MAINTENANCE_MODE==1 && !in_array($_SERVER['REMOTE_ADDR'],array("203.88.138.46","86.98.53.143")))
		{
			$Response->P_OUT_STS_CD     = 1001;
			$Response->P_OUT_MSG_SERVER = 'GEDA server is under maintenance.';
			$Response->P_OUT_DATA       = '';
		}
		else
		{
			$authorization          = $this->getHashKey();
			$ThirdpartyApiUsageLog  = TableRegistry::get('ThirdpartyApiUsageLog');
			$arr_thirdpartyusage    = $ThirdpartyApiUsageLog->find('all',array('conditions'=>array('clear_usage_date IS NULL'),'order'=>'id desc'))->first();
			$stop_api_call          = 0;
			if($this->apiaction == 'send_consumer_registration_details' && $this->apidiscom!='5' && $this->apidiscom!='6' && $this->apidiscom!='7')
			{
				$SendRegistrationFailure        = TableRegistry::get('SendRegistrationFailure');
				$RegistrationFailureData        = $SendRegistrationFailure->find('all',array('conditions'=>array('application_id'=>$this->apiapplication_id)))->first();
				if(empty($RegistrationFailureData))
				{
					$SendRegistrationFailureEntity                  = $SendRegistrationFailure->newEntity();
					$SendRegistrationFailureEntity->application_id  = $this->apiapplication_id;
					$SendRegistrationFailureEntity->created         = $this->NOW();
					$SendRegistrationFailure->save($SendRegistrationFailureEntity);
				}
			}
			if(!empty($arr_thirdpartyusage) && $this->apidiscom!='5' && $this->apidiscom!='6'  && $this->apidiscom!='7')
			{
				$now                = strtotime(date("Y-m-d H:i:s")); // or your date as well
				$your_date          = strtotime($arr_thirdpartyusage->created);
				$datediff           = $now - $your_date;
				$Minutes            = ($datediff > 0)?round($datediff / (60)):0;
				if($Minutes<=GUVNL_USAGE_MINUTES)
				{
					$stop_api_call  = '1';
					$remaining_time = GUVNL_USAGE_MINUTES-$Minutes;
					$Response->P_OUT_STS_CD     = $arr_thirdpartyusage->code;
					$Response->P_OUT_MSG_SERVER = 'Due to excessive query at the server, GUVNL has stopped the service due to security reasons. Kindly try after '.$remaining_time.' mins.';
					$Response->P_OUT_DATA       = '';
				}
			}
			if($stop_api_call==0)
			{
				if($this->apiaction == 'send_consumer_registration_details')
				{
					$ApplyOnlines               = TableRegistry::get('ApplyOnlines');
					$this->p_con_details        = $ApplyOnlines->fetchDataForRegistration($this->apiapplication_id);
				}
				$data_request   = [
						'P_DISCOM_CD'   => $this->apidiscom,
						'P_CON_NO'      => $this->apiconsumer,
						'apitimestamp'  => $this->apitimestamp,
						'apiaction'     => $this->apiaction
					];
				if($this->apiaction == 'send_consumer_registration_details') {
					$data_request['P_CON_DETAILS'] 	= $this->p_con_details;
				}
				$flag_con       = 0;
				if($this->apidiscom!='5' && $this->apidiscom!='6'  && $this->apidiscom!='7')
				{
					$conn           = curl_init( $this->APIURL );
					$flag_con       = 1;
				}
				else if($this->apiaction=='get_consumer_details')
				{
					/*$data_request   = array("P_IN_DATA" => $this->apiconsumer,
										"P_IN_DATA2"    => $this->api_t_no,
										"P_SRV_CD"      => '1',
										"P_CLIENT_CD"   => '1',
										"P_DISCOM_CD"   => $this->apidiscom,
										"P_DT_TM"       => date("d.m.Y.H.i.s"),
										"P_AUTH_KEY"    => $this->TOREENTAPI_KEY);*/
					$data_request   = array('P_DISCOM_CD'   => $this->apidiscom,
											"P_CON_NO" 		=> $this->apiconsumer,
											"P_T_NO"    	=> $this->api_t_no,
											"P_AUTH_KEY"    => $this->TOREENTAPI_KEY,
											'apitimestamp'  => $this->apitimestamp,
											'apiaction'     => $this->apiaction);
					$str_imp            = array();
					$str_data           = '';
					foreach($data_request as $key=>$val)
					{
						$str_imp[]  = $key."=".$val;
					}
					if(!empty($str_imp))
					{
						$str_data   = '?'.implode("&",$str_imp);
					}
					$conn           = curl_init( $this->APIURL); 
					$flag_con       = 1;
				}
				if($flag_con==1)
				{
					$headers        = array('apitoken:'.$authorization,$this->SDC_AUTH,"Authorization: Bearer ".BEARER_TOKEN,'Content-Type: application/json');
					if($this->apidiscom==7 || $this->apidiscom==6 || $this->apidiscom==5)
					{
						$data_request   = array("P_IN_DATA" 	=> $this->apiconsumer,
												"P_IN_DATA2"    => $this->api_t_no,
												"P_SRV_CD"      => '1',
												"P_CLIENT_CD"   => '1',
												"P_DISCOM_CD"   => $this->apidiscom,
												"P_DT_TM"       => date("d.m.Y.H.i.s"),
												"P_AUTH_KEY"    => $this->TOREENTAPI_KEY);
						$url            = $this->TOREENTAPIURL;
						$str_data       = '';
						$str_imp        = array();
						foreach($data_request as $key=>$val)
						{
							$str_imp[]  = $key."=".$val;
						}
						if(!empty($str_imp))
						{
							$str_data   = '?'.implode("&",$str_imp);
						}
						$post_string = json_encode($data_request);
						$conn        = curl_init( $url.$str_data);
						curl_setopt( $conn, CURLOPT_HTTPHEADER, array("Authorization: Bearer ".BEARER_TOKEN));
						curl_setopt( $conn, CURLOPT_CONNECTTIMEOUT, 300 );
						curl_setopt( $conn, CURLOPT_SSL_VERIFYPEER, false );
						curl_setopt( $conn, CURLOPT_SSL_VERIFYHOST, 0 );
						curl_setopt( $conn, CURLOPT_RETURNTRANSFER, true );
						//curl_setopt( $conn, CURLOPT_POST, true );
						//curl_setopt( $conn, CURLOPT_POSTFIELDS, $post_string);
						$output 	= curl_exec( $conn );
						
						$Response   = json_decode($output);
						$url            = $this->TOREENTAPIURL;
					}
					else
					{
						if((!$this->API_CALL_VIA_URL) && ($this->apiaction == 'get_consumer_details' || $this->apiaction == 'get_fr_estimate' || $this->apiaction == 'get_meter_details')) {
							require_once(ROOT . DS . 'vendor' . DS . 'guvnl' . DS . 'guvnl.php');
			       			$GUVNL 				= new Guvnl();
							$output 			= $GUVNL->GetApiActionResponse($data_request);
							
							$ApiVariables 		= $GUVNL->apiRequestVars;
							$Response       	= json_decode($output);
							$this->Api_Response = $Response;
							if ($this->print_api_response) {
								print_r($data_request);
								print_r($Response);
							}
							$this->APIURL 	= GUVNL_API_URL;
						} else {
							curl_setopt($conn, CURLOPT_HTTPHEADER,$headers);
							curl_setopt($conn, CURLOPT_CONNECTTIMEOUT, 300 );
							curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, false );
							curl_setopt($conn, CURLOPT_SSL_VERIFYHOST, 0);
							curl_setopt($conn, CURLOPT_FOLLOWLOCATION, True);
							curl_setopt($conn, CURLOPT_RETURNTRANSFER, true );
							//curl_setopt($conn, CURLOPT_PORT,'8006');
							curl_setopt($conn, CURLOPT_POST, true);
							curl_setopt($conn, CURLOPT_POSTFIELDS,json_encode($data_request));
							$output         = curl_exec( $conn );
							$Response       = json_decode($output);
						}
						$url            = $this->APIURL;
					}
					
					curl_close ($conn);
					$thirdpartyEntity                   = $this->newEntity();
					$thirdpartyEntity->application_id   = $this->apiapplication_id;
					$thirdpartyEntity->project_id       = $this->apiproject_id;
					$thirdpartyEntity->request_data     = json_encode($data_request);
					$thirdpartyEntity->response_data    = $output;
					$thirdpartyEntity->api_url          = $url;
					$thirdpartyEntity->created          = $this->NOW();
					$this->save($thirdpartyEntity);
					$output_code                        = '';
					
					if((!empty($Response) && isset($Response->P_OUT_STS_CD)) || (($this->apidiscom=='7' || $this->apidiscom=='5' || $this->apidiscom=='6') && isset($Response->P_OUT_DATA)))
					{

						if($this->apidiscom!='5' && $this->apidiscom!='6' && $this->apidiscom!='7')
						{
						$output_code                        = $Response->P_OUT_STS_CD;
						}
						if($output_code==21)
						{
							$UsageLogEntity             = $ThirdpartyApiUsageLog->newEntity();
							$UsageLogEntity->code       = $Response->P_OUT_STS_CD;
							$UsageLogEntity->message    = $Response->P_OUT_MSG_SERVER;
							$UsageLogEntity->created    = $this->NOW();
							$ThirdpartyApiUsageLog->save($UsageLogEntity);
							
							$Response->P_OUT_STS_CD         = $Response->P_OUT_STS_CD;
							$Response->P_OUT_MSG_SERVER     = 'Due to excessive query at the server, GUVNL has stopped the service due to security reasons. Kindly try after '.GUVNL_USAGE_MINUTES.' mins.';
							$Response->P_OUT_DATA           = '';
						}
						else
						{
							if(empty($Response->P_OUT_MSG_SERVER) && $this->apidiscom!='7' && $this->apidiscom!='5' && $this->apidiscom!='6')
							{
								$Response->P_OUT_MSG_SERVER = 'Unable to get data from GUVNL server.';
							}
							$arr_thirdpartyusage            = $ThirdpartyApiUsageLog->find('all',array('conditions'=>array('clear_usage_date IS NULL'),'order'=>'id desc'))->first();
							if(!empty($arr_thirdpartyusage))
							{
								$thirdparty_Data                    = $ThirdpartyApiUsageLog->get($arr_thirdpartyusage->id);
								$UsageLogEntity                     = $ThirdpartyApiUsageLog->patchEntity($thirdparty_Data,array());
								$UsageLogEntity->clear_usage_date   = $this->NOW();
								$ThirdpartyApiUsageLog->save($UsageLogEntity);
							}
						}
						if($action=='search_consumer' && $this->apiapplication_id != 0)
						{
							$applyOnline                    = TableRegistry::get('ApplyOnlines');
							$applyOnline_Data               = $applyOnline->get($this->apiapplication_id);
							$ApplyonlinEntity               = $applyOnline->patchEntity($applyOnline_Data,array());
							$ApplyonlinEntity->api_response = $output;
							$ApplyonlinEntity->id           = $this->apiapplication_id;
							$applyOnline->save($ApplyonlinEntity);
						}
						if($this->apiaction == 'send_consumer_registration_details' && ((isset($Response->P_OUT_MSG_SERVER) && strtoupper($Response->P_OUT_MSG_SERVER)!='DATA RECEIVED IN EURJA') || empty($output)))
						{
							$SendRegistrationFailure        = TableRegistry::get('SendRegistrationFailure');
							$RegistrationFailureData        = $SendRegistrationFailure->find('all',array('conditions'=>array('application_id'=>$this->apiapplication_id)))->first();
							if(empty($RegistrationFailureData))
							{
								$SendRegistrationFailureEntity  = $SendRegistrationFailure->newEntity();
								$SendRegistrationFailureEntity->application_id  = $this->apiapplication_id;
								$SendRegistrationFailureEntity->created         = $this->NOW();
								$SendRegistrationFailure->save($SendRegistrationFailureEntity);
							}
						}
						if($this->apiaction == 'send_consumer_registration_details' && isset($Response->P_OUT_MSG_CLIENT) && strtoupper($Response->P_OUT_MSG_CLIENT)=='DATA RECEIVED IN EURJA')
						{
							$SendRegistrationFailure        = TableRegistry::get('SendRegistrationFailure');
							$SendRegistrationFailure->deleteAll(['application_id' => $this->apiapplication_id]);
						}
					}
					else
					{
						if($this->apiaction == 'send_consumer_registration_details' && $this->apidiscom!='5' && $this->apidiscom!='6' && $this->apidiscom!='7')
						{
							$SendRegistrationFailure        = TableRegistry::get('SendRegistrationFailure');
							$RegistrationFailureData        = $SendRegistrationFailure->find('all',array('conditions'=>array('application_id'=>$this->apiapplication_id)))->first();
							if(empty($RegistrationFailureData))
							{
								$SendRegistrationFailureEntity  = $SendRegistrationFailure->newEntity();
								$SendRegistrationFailureEntity->application_id  = $this->apiapplication_id;
								$SendRegistrationFailureEntity->created         = $this->NOW();
								$SendRegistrationFailure->save($SendRegistrationFailureEntity);
							}
							$Response                   = new \stdClass();
							$Response->P_OUT_STS_CD     = 1001;
							$Response->P_OUT_MSG_SERVER = '';
							$Response->P_OUT_DATA       = '';
						}
						else
						{
							$Response                   = new \stdClass();
							$Response->P_OUT_STS_CD     = 1001;
							$Response->P_OUT_MSG_SERVER = 'Response not comming from GEDA';
							$Response->P_OUT_DATA       = '';
						}
					}
				}
			}
		}
		return $Response;
	}

	public function GetLastResponse($applicationId)
	{
		if(!empty($applicationId)) {
			$ResponseData 	= $this->find('all',array('conditions'=>array('application_id'=>$applicationId),
								'order'		=>array('id'=>'desc')))->first();
			return $ResponseData;
		}
		return '';
	}
}