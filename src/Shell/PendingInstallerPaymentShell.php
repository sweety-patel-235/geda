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
class PendingInstallerPaymentShell extends Shell
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
		$this->loadModel('InstallerPaymentRequest');
		$this->loadModel('InstallerPayment');
	}

	public function main()
	{
		error_reporting(0);
		echo "\r\n--StartTime::".date("Y-m-d H:i:s")."--\r\n";
     	/** KP */
        $ConnectionManager = ConnectionManager::get('default');
        $ConnectionManager->execute("SET @@SESSION.sql_mode='NO_ENGINE_SUBSTITUTION'");
        /** KP */

		$pendingInstallers 	= $this->InstallerPaymentRequest->find('all',array(
													'join' 		=> ['installers'=>['table'=>'installers','type'=>'left','conditions'=>'InstallerPaymentRequest.installer_id=installers.id']],
													'fields'	=> array('InstallerPaymentRequest.installer_id'),
													'conditions'=> array('response_data IS NULL','installers.payment_status !='=>'1')))->distinct(['InstallerPaymentRequest.installer_id'])->toArray();
		$updateInstaller 	= 0;
		if(!empty($pendingInstallers)) {
			foreach($pendingInstallers as $installer) {
				$requestDataAll 		= $this->InstallerPaymentRequest->find('all',array(
														'conditions'=> array('InstallerPaymentRequest.installer_id'=>$installer->installer_id),
														'order'		=> array('InstallerPaymentRequest.id'=>'desc')))->toArray();
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
							//echo '---------->'.$application->application_id.'<br>';
							if(isset($response->Order_Status_Result->order_bank_response) && !empty($response->Order_Status_Result->order_bank_response)) {
								$bankResponse 			= strtolower($response->Order_Status_Result->order_bank_response);
								
								$arrbankRes 			= explode("success",$bankResponse);
								if(count($arrbankRes) > 1 || $bankResponse=='s') {
									echo '---------->'.$installer->installer_id.'<br>';
									$updateInstaller++;
									$arrpassdata 					= array();
									$arrpassdata['order_id'] 		= $order_id;
									$arrpassdata['order_status'] 	= 'success';
									$arrpassdata['merchant_param1'] = encode($installer->installer_id);
									$arrpassdata['trans_date'] 		= $response->Order_Status_Result->order_status_date_time;
									$arrpassdata['tracking_id'] 	= $response->Order_Status_Result->reference_no;
									
									$this->InstallerPayment->savedata_success($arrpassdata);
									$arrpay 					= array();
									$arrpay['installer_id'] 	= $installer->installer_id;
							
									$arrpay['modified'] 		= $this->NOW();
									$arrpay['response_data']	= json_encode($response);
									$this->InstallerPaymentRequest->updateAll($arrpay,array('id'=>$requestData->id));
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