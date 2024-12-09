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
class PendingApplicationPaymentShell extends Shell
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
		$this->loadModel('ApplicationPaymentRequest');
		$this->loadModel('Payumoney');
	}

	public function main()
	{
		error_reporting(0);
		echo "\r\n--StartTime::".date("Y-m-d H:i:s")."--\r\n";

     	/** KP */
        $ConnectionManager = ConnectionManager::get('default');
        $ConnectionManager->execute("SET @@SESSION.sql_mode='NO_ENGINE_SUBSTITUTION'");
        /** KP */

		$pendingApplications 	= $this->ApplicationPaymentRequest->find('all',array(
													'join' 		=> ['apply_onlines'=>['table'=>'apply_onlines','type'=>'left','conditions'=>'ApplicationPaymentRequest.application_id=apply_onlines.id']],
													'fields'	=> array('ApplicationPaymentRequest.application_id'),
													'conditions'=> array('response_data IS NULL','apply_onlines.payment_status !='=>'1')))->distinct(['ApplicationPaymentRequest.application_id'])->toArray();
		$updateApplication 	= 0;
		if(!empty($pendingApplications)) {
			foreach($pendingApplications as $application) {
				$requestDataAll 		= $this->ApplicationPaymentRequest->find('all',array(
														'conditions'=> array('ApplicationPaymentRequest.application_id'=>$application->application_id),
														'order'		=> array('ApplicationPaymentRequest.id'=>'desc')))->toArray();
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
									
									$this->Payumoney->savedata_success($arrpassdata);
									$arrpay 					= array();
									$arrpay['application_id'] 	= $application->application_id;
							
									$arrpay['modified'] 		= $this->NOW();
									$arrpay['response_data']	= json_encode($response);
									$this->ApplicationPaymentRequest->updateAll($arrpay,array('id'=>$requestData->id));
								}
							}
						}
					}
				}
			}
		}
		echo "Total Application".count($pendingApplications);
		echo "Updated Application".$updateApplication;
		echo "\r\n--EndTime::".date("Y-m-d H:i:s")."--\r\n";
	}
}