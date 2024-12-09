<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Shell;

use App\Controller\AppController;
use Cake\Console\Shell;
use Cake\Network\Email\Email;
use Dompdf\Dompdf;
use Cake\Core\Configure;
use Cake\View\View;
use Hdfc\Hdfc;
use Cake\Datasource\ConnectionManager;
class PendingReApplicationPaymentShell extends Shell
{

	public function initialize()
	{
		parent::initialize();
		$this->loadModel('Installers');
		$this->loadModel('Projects');
		$this->loadModel('ApplyOnlines');
		$this->loadModel('Subsidy');
		$this->loadModel('SubsidyRequest');
		$this->loadModel('SubsidyRequestApplication');
		$this->loadModel('Emaillog');
		$this->loadModel('CronApiProcess');
		$this->loadModel('GuvnlDivisionDetails');
		$this->loadModel('BranchMasters');
		$this->loadModel('Members');
		$this->loadModel('ReApplicationPaymentRequest');
		$this->loadModel('ReApplicationPayment');
		$this->loadModel('GeoApplicationPaymentRequest');
		$this->loadModel('GeoApplicationPayment');
		$this->loadModel('GeoShiftingApplicationPaymentRequest');
		$this->loadModel('GeoShiftingApplicationPayment');
	}
	public function main()
	{
		error_reporting(0);
		echo '<pre>';
		echo "\r\n--StartTime::".date("Y-m-d H:i:s")."--\r\n";
		
			/** KP */
        $ConnectionManager = ConnectionManager::get('default');
        $ConnectionManager->execute("SET @@SESSION.sql_mode='NO_ENGINE_SUBSTITUTION'");
		$this->ReApplicationPayment();
		$this->GeoApplicationPayment();
		$this->GeoShiftingApplicationPayment();
		echo "\r\n--EndTime::".date("Y-m-d H:i:s")."--\r\n";
		
	}
	public function ReApplicationPayment()
	{
		
		$pendingApplications 	= $this->ReApplicationPaymentRequest->find('all',array(
													'join' 		=> ['applications'=>['table'=>'applications','type'=>'left','conditions'=>'ReApplicationPaymentRequest.application_id=applications.id']],
													'fields'	=> array('ReApplicationPaymentRequest.application_id'),
													'conditions'=> array('request_data IS NOT NULL','applications.payment_status !='=>'1')))->distinct(['ReApplicationPaymentRequest.application_id'])->toArray();
		$updateApplication 	= 0;
		if(!empty($pendingApplications)) {
			foreach($pendingApplications as $application) {
				$requestDataAll 		= $this->ReApplicationPaymentRequest->find('all',array(
														'conditions'=> array('ReApplicationPaymentRequest.application_id'=>$application->application_id),
														'order'		=> array('ReApplicationPaymentRequest.id'=>'desc')))->toArray();
				if(!empty($requestDataAll)) {
					foreach($requestDataAll as $requestData) {
						if(!empty($requestData)) {
							$arrOutput 			= json_decode($requestData->request_data,2);
							$order_id 			= $arrOutput['order_id'];
							require_once(ROOT . DS . 'vendor' . DS . 'hdfc' . DS . 'hdfc.php');
							$objHdfc 			= new Hdfc(Configure::read('HDFC_MERCHANT_KEY'),Configure::read('HDFC_SALT'),Configure::read('HDFC_ACCESS_CODE'), Configure::read('PAYU_SANDBOX'));
							$hdfc['order_no'] 			= $order_id;
							$response 	= $objHdfc->getData($hdfc);
							print_r($response);
							if(isset($response->Order_Status_Result->order_bank_response) && !empty($response->Order_Status_Result->order_bank_response)) {
								$bankResponse 			= strtolower($response->Order_Status_Result->order_bank_response);
								
								$arrbankRes 			= explode("success",$bankResponse);
								if(count($arrbankRes) > 1) {
									echo '---------->'.$application->application_id.'<br>';
									$updateApplication++;
									$arrpassdata 					= array();
									$arrpassdata['order_id'] 		= $order_id;
									$arrpassdata['order_status'] 	= 'success';
									$arrpassdata['merchant_param1'] = encode($application->application_id);
									$arrpassdata['trans_date'] 		= $response->Order_Status_Result->order_status_date_time;
									$arrpassdata['tracking_id'] 	= $response->Order_Status_Result->reference_no;
									echo '---------->BeforeReApplicationPayment'.'<br>';
									$this->ReApplicationPayment->savedata_success($arrpassdata);
									echo '---------->ReApplicationPayment'.'<br>';
									$arrpay 					= array();
									$arrpay['application_id'] 	= $application->application_id;
							
									$arrpay['modified'] 		= $this->NOW();
									$arrpay['response_data']	= json_encode($response);
									echo '---------->BeforeReApplicationPaymentRequest'.'<br>';
									print_r($arrpay);
									$this->ReApplicationPaymentRequest->updateAll($arrpay,array('id'=>$requestData->id));
									echo '---------->ReApplicationPaymentRequest'.'<br>';
								}
							}
						}
					}
				}
			}
		}
		echo "Total Application".count($pendingApplications);
		echo "Updated Application".$updateApplication;
		
	}

	public function GeoApplicationPayment()
	{
		
		$pendingApplications 	= $this->GeoApplicationPaymentRequest->find('all',array(
													'join' 		=> [
													['table'=>'geo_application_payment','type'=>'left','conditions'=>'geo_application_payment.application_id = GeoApplicationPaymentRequest.application_id'],
													],
													'fields'	=> array('GeoApplicationPaymentRequest.geo_id','GeoApplicationPaymentRequest.application_id','GeoApplicationPaymentRequest.response_data'),
													'conditions'=> array('geo_application_payment.payment_status'=>'pending' ,'GeoApplicationPaymentRequest.response_data is NOT NULL' )))->distinct(['GeoApplicationPaymentRequest.geo_id'])->toArray();
		
		$updateApplication 	= 0;
		if(!empty($pendingApplications)) {
			foreach($pendingApplications as $application) {
				$geo_id = $application['geo_id'];
				$requestDataAll 		= $this->GeoApplicationPaymentRequest->find('all',array(
														'conditions'=> array('GeoApplicationPaymentRequest.geo_id like'=>'%'.$geo_id.'%'),
														'order'		=> array('GeoApplicationPaymentRequest.id'=>'desc')))->toArray();
				
				if(!empty($requestDataAll)) {
					foreach($requestDataAll as $requestData) {
						if(!empty($requestData)) {
							$arrOutput 			= json_decode($requestData->request_data,2);
							
							$order_id 			= $arrOutput['order_id'];
							require_once(ROOT . DS . 'vendor' . DS . 'hdfc' . DS . 'hdfc.php');
							$objHdfc 			= new Hdfc(Configure::read('HDFC_MERCHANT_KEY'),Configure::read('HDFC_SALT'),Configure::read('HDFC_ACCESS_CODE'), Configure::read('PAYU_SANDBOX'));
							$hdfc['order_no'] 			= $order_id;
							$response 	= $objHdfc->getData($hdfc);
							print_r($response);
						
							if(isset($response->Order_Status_Result->order_bank_response) && !empty($response->Order_Status_Result->order_bank_response)) {
								$bankResponse 			= strtolower($response->Order_Status_Result->order_bank_response);
								
								$arrbankRes 			= explode("success",$bankResponse);
								
								if(count($arrbankRes) > 1) {
								
									$updateApplication++;
									$arrpassdata 							= array();
									$arrpassdata['order_id'] 				= $order_id;
									$arrpassdata['order_status'] 			= 'success';
									
									$arrpassdata['amount'] 					= $arrOutput['amount'];
									$arrpassdata['merchant_param1'] 		= $arrOutput['merchant_param1'];
									$arrpassdata['merchant_param2'] 		= $geo_id;
									$arrpassdata['merchant_param3'] 		= $arrOutput['merchant_param3'];
									$arrpassdata['merchant_param4'] 		= $arrOutput['merchant_param4'];
									$arrpassdata['merchant_param5'] 		= $arrOutput['merchant_param5'];
									$arrpassdata['trans_date'] 		= $response->Order_Status_Result->order_status_date_time;
									$arrpassdata['tracking_id'] 	= $response->Order_Status_Result->reference_no;
									echo '---------->BeforeGeoApplicationPayment'.'<br>';
									
									$this->GeoApplicationPayment->savedata_success($arrpassdata);
									
									echo '---------->GeoApplicationPayment'.'<br>';
									$arrpay 					= array();
									$arrpay['application_id'] 	= $application['application_id'];
							
									$arrpay['modified'] 		= $this->NOW();
									$arrpay['response_data']	= json_encode($response);
									echo '---------->BeforeGeoApplicationPaymentRequest'.'<br>';
									print_r($arrpay);
									$this->GeoApplicationPaymentRequest->updateAll($arrpay,array('id'=>$requestData->id));
									echo '---------->GeoApplicationPaymentRequest'.'<br>';
								}
							}
						}
					}
				}
			}
		}
		echo "Total Application".count($pendingApplications);
		echo "Updated Application".$updateApplication;
	
	}

	public function GeoShiftingApplicationPayment()
	{
		
		$pendingApplications 	= $this->GeoShiftingApplicationPaymentRequest->find('all',array(
													'join' 		=> [
													['table'=>'geo_shifting_application_payment','type'=>'left','conditions'=>'geo_shifting_application_payment.application_id = GeoShiftingApplicationPaymentRequest.application_id'],
													],
													'fields'	=> array('GeoShiftingApplicationPaymentRequest.geo_id','GeoShiftingApplicationPaymentRequest.application_id','GeoShiftingApplicationPaymentRequest.response_data'),
													'conditions'=> array('geo_shifting_application_payment.payment_status'=>'pending' ,'GeoShiftingApplicationPaymentRequest.response_data is NOT NULL' )))->distinct(['GeoShiftingApplicationPaymentRequest.geo_id'])->toArray();
		
		$updateApplication 	= 0;
		if(!empty($pendingApplications)) {
			foreach($pendingApplications as $application) {
				
				$geo_id = $application['geo_id'];
				$requestDataAll 		= $this->GeoShiftingApplicationPaymentRequest->find('all',array(
														'conditions'=> array('GeoShiftingApplicationPaymentRequest.geo_id like'=>'%'.$geo_id.'%'),
														'order'		=> array('GeoShiftingApplicationPaymentRequest.id'=>'desc')))->toArray();
				
				if(!empty($requestDataAll)) {
					foreach($requestDataAll as $requestData) {
						if(!empty($requestData)) {
							$arrOutput 			= json_decode($requestData->request_data,2);
							
							$order_id 			= $arrOutput['order_id'];
							require_once(ROOT . DS . 'vendor' . DS . 'hdfc' . DS . 'hdfc.php');
							$objHdfc 			= new Hdfc(Configure::read('HDFC_MERCHANT_KEY'),Configure::read('HDFC_SALT'),Configure::read('HDFC_ACCESS_CODE'), Configure::read('PAYU_SANDBOX'));
							$hdfc['order_no'] 			= $order_id;
							$response 	= $objHdfc->getData($hdfc);
							
							if(isset($response->Order_Status_Result->order_bank_response) && !empty($response->Order_Status_Result->order_bank_response)) {
								$bankResponse 			= strtolower($response->Order_Status_Result->order_bank_response);
								
								$arrbankRes 			= explode("success",$bankResponse);
								
								if(count($arrbankRes) > 1) {
								
									$updateApplication++;
									$arrpassdata 							= array();
									$arrpassdata['order_id'] 				= $order_id;
									$arrpassdata['order_status'] 			= 'success';
									
									$arrpassdata['amount'] 					= $arrOutput['amount'];
									$arrpassdata['merchant_param1'] 		= $arrOutput['merchant_param1'];
									$arrpassdata['merchant_param2'] 		= $geo_id;
									$arrpassdata['merchant_param3'] 		= $arrOutput['merchant_param3'];
									$arrpassdata['merchant_param4'] 		= $arrOutput['merchant_param4'];
									$arrpassdata['merchant_param5'] 		= $arrOutput['merchant_param5'];
									$arrpassdata['trans_date'] 		= $response->Order_Status_Result->order_status_date_time;
									$arrpassdata['tracking_id'] 	= $response->Order_Status_Result->reference_no;
									echo '---------->BeforeGeoShiftingApplicationPayment'.'<br>';
									echo"<pre>"; print_r($arrpassdata); 
									$this->GeoShiftingApplicationPayment->savedata_success($arrpassdata);
									
									echo '---------->GeoShiftingApplicationPayment'.'<br>';
									$arrpay 					= array();
									$arrpay['application_id'] 	= $application['application_id'];
							
									$arrpay['modified'] 		= $this->NOW();
									$arrpay['response_data']	= json_encode($response);
									echo '---------->BeforeGeoShiftingApplicationPaymentRequest'.'<br>';
									print_r($arrpay);
									$this->GeoShiftingApplicationPaymentRequest->updateAll($arrpay,array('id'=>$requestData->id));
									echo '---------->GeoShiftingApplicationPaymentRequest'.'<br>';
								}
							}
						}
					}
				}
			}
		}
		echo "Total Application".count($pendingApplications);
		echo "Updated Application".$updateApplication;
	}
}