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
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Payu\Payu;
use Hdfc\Hdfc;
use Cake\Routing\Router;
use Cake\Utility\Security;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class GeoShiftingApplicationPaymentController extends AppController
{

	public function initialize()
    {
        // Always enable the CSRF component.
		parent::initialize();	
        $this->loadModel('ApiToken');
        $this->loadModel('Payumoney');
        $this->loadModel('GeoShiftingApplicationPayment');
        $this->loadModel('ApplicationCategory');
	}
   
    public function index()
    {
    	//echo"<pre>"; print_r($this->request->data); die();
    	$this->autoRender 		= false;
    	$geo_payment			=(isset($this->request->data['geo_payment'])?$this->request->data['geo_payment']:0);
    	$application_form_id	= (isset($this->request->data['geo_id'])?$this->request->data['geo_id']:0);
    	$id 					= (isset($this->request->data['Paymentapplication_id'])?$this->request->data['Paymentapplication_id']:0);
		$application_type 		= (isset($this->request->data['Paymentapplication_type'])?$this->request->data['Paymentapplication_type']:0);
		$geo_id 				= explode(',', $application_form_id);
		


		$applicationCategory 	= $this->ApplicationCategory->find('all',array('conditions'=>array('id'=>$application_type)))->first();
		//echo"<pre>"; print_r($this->request->data); 
		//echo"<pre>"; print_r($this->request->data); die();
		 // [geo_id] => 60,61
	    // [gst_fees] => 360
	    // [geo_total_fee] => 2360
	    // [geo_location_tds] => 200
	    // [net_payable] => 2160
	    // [liable_tds] => 1
	    // [terms_agree] => 1
		if(!empty($this->request->data['geo_id'])){
			$geo_id   					= explode(',', $this->request->data['geo_id']);
			$geo_id_witout_zero = array_filter($geo_id);
			$geo_payment_amount 		= count($geo_id_witout_zero) * $applicationCategory->geo_location_charges;

			$geo_payment_with_gst 		=($geo_payment_amount*$applicationCategory->geo_location_tax)/100;
			
	        $geo_total_payment_amount 	=($geo_payment_amount+$geo_payment_with_gst);

	        $geo_tds_amount 			= ($geo_payment_amount*$applicationCategory->application_tds_percentage)/100;
        	$geo_total_tds_amount 		= ($geo_total_payment_amount-$geo_tds_amount);
        	
	        if($this->request->data['liable_tds'] == 1 || $this->request->data['terms_agree'] == 1){
	        	$net_payable_amount 	= $geo_total_tds_amount;
	        	
	        	$geo_location_tds 		= (isset($this->request->data['geo_location_tds'])?$this->request->data['geo_location_tds']:0);	
	        }else{
	        	$net_payable_amount     = $geo_total_payment_amount;	
	        }
		}

		if($this->Session->check('Customers')) 
		{
			$customerId 		= $this->Session->read('Customers.id');
			$cusTable 			= TableRegistry::get('DeveloperCustomers');	
			$customer 			= $cusTable->find('all',array('conditions'=>array('id'=>$customerId)))->first();
		
			if($customerId=='')
			{
				//return $this->redirect(URL_HTTP.'applications-list');
				return $this->redirect(URL_HTTP.'applications_wtg_shifting/'.$id);
			}
		} else {
			return $this->redirect(URL_HTTP);
		}
		
		if($application_form_id == '' || (Configure::read('PAYUMONEY_PAYMENT') != 1 ))
		{

			//return $this->redirect(URL_HTTP.'applications-list');
			return $this->redirect(URL_HTTP.'applications_wtg_shifting/'.$id);
		}
		else
		{
			$ApplicationGeoLocationTable 	= TableRegistry::get('ApplicationGeoLocation');
			$apply_onlinesTable 	= TableRegistry::get('Applications');
			$exist_application_Geo 		= $ApplicationGeoLocationTable->find('all',array('conditions'=>array('application_id'=>decode($id) ,'id IN' => ($geo_id))))->toArray();
			
			$exist_application 		= $apply_onlinesTable->find('all',array('conditions'=>array('id'=>decode($id))))->toArray();
			//echo"<pre>"; print_r($geo_payment); die();	
			if(empty($geo_payment))
			{
				//return $this->redirect(URL_HTTP.'applications-list');
				return $this->redirect(URL_HTTP.'applications_wtg_shifting/'.$id);
			}	
		}
			
		if(PAYMENT_METHOD=='hdfc')
        {
			require_once(ROOT . DS . 'vendor' . DS . 'hdfc' . DS . 'hdfc.php');

			$objHdfc 					= new Hdfc(Configure::read('HDFC_MERCHANT_KEY'),Configure::read('HDFC_SALT'),Configure::read('HDFC_ACCESS_CODE'), Configure::read('PAYU_SANDBOX'));
			$txnId 						= $objHdfc->randomTxnId();
			$hdfc['order_id'] 			= $txnId;               //Transaction Id
			$hdfc['redirect_url'] 		= URL_HTTP.'GeoShiftingPayment/success';//Router::url(['controller' => 'GeoShiftingApplicationPayment','action' => 'success'],TRUE); // Success Url
			$hdfc['cancel_url'] 		= URL_HTTP.'GeoShiftingPayment/cancel';//Router::url(['controller' => 'GeoShiftingApplicationPayment','action' => 'cancel'],TRUE); 	// Cancel Url
			$hdfc['amount'] 			= isset($net_payable_amount) ? $net_payable_amount : 0; // Amount
			$hdfc['language'] 			= 'EN';
			$hdfc['currency'] 			= 'INR';
			$hdfc['billing_name'] 		= preg_replace('/[^a-z0-9 ]/i', '',$exist_application[0]->name_of_applicant);
			$hdfc['billing_country'] 	= 'India';
			$hdfc['billing_address'] 	= preg_replace('/[^a-z0-9 ]/i', '',$exist_application[0]->address);
			$hdfc['billing_city'] 		= preg_replace('/[^a-z0-9 ]/i', '', $exist_application[0]->city);
			$hdfc['billing_state'] 		= preg_replace('/[^a-z0-9 ]/i', '',$exist_application[0]->state);
			$hdfc['billing_zip'] 		= $exist_application[0]->pincode;
			$hdfc['billing_tel'] 		= $exist_application[0]->mobile;
			$hdfc['delivery_name'] 		= preg_replace('/[^a-z0-9 ]/i', '',$exist_application[0]->name_of_applicant);
			$hdfc['delivery_country'] 	= 'India';
			$hdfc['delivery_address'] 	= preg_replace('/[^a-z0-9 ]/i', '',$exist_application[0]->address);
			$hdfc['delivery_city'] 		= preg_replace('/[^a-z0-9 ]/i', '', $exist_application[0]->city);
			$hdfc['delivery_state'] 	= preg_replace('/[^a-z0-9 ]/i', '',$exist_application[0]->state);
			$hdfc['delivery_zip'] 		= $exist_application[0]->pincode;
			$hdfc['delivery_tel'] 		= $exist_application[0]->mobile;
			$hdfc['merchant_param1'] 	= encode($exist_application[0]->id);
			$hdfc['merchant_param2'] 	= $this->request->data['geo_id'];
			$hdfc['merchant_param3'] 	= $geo_payment_with_gst;
			$hdfc['merchant_param4'] 	= (isset($geo_location_tds)?$geo_location_tds:0);
			$hdfc['merchant_param5'] 	= $geo_payment_amount;
			
	   

			$request_data 								= json_encode($hdfc);
			$ApplicationPaymentRequest 					= TableRegistry::get('GeoShiftingApplicationPaymentRequest');
			$ApplicationRequestEntity 					= $ApplicationPaymentRequest->newEntity();
			$ApplicationRequestEntity->application_id 	= $exist_application[0]->id;
			$ApplicationRequestEntity->customer_id		= $customerId;
			$ApplicationRequestEntity->geo_id			= $this->request->data['geo_id'];
			$ApplicationRequestEntity->created 			= $this->NOW();
			$ApplicationRequestEntity->modified 		= $this->NOW();
			$ApplicationRequestEntity->request_data		= $request_data;
			$ApplicationRequestEntity->amount 			= $hdfc['amount'];
			$ApplicationRequestEntity->created_by		= $customerId;
			$ApplicationRequestEntity->modified_by		= $customerId;
			$ApplicationPaymentRequest->save($ApplicationRequestEntity);
			$objHdfc->send($hdfc);
    	}
    	
        exit;
    }

    /**
	* success
	* Behaviour : public
	* @defination : Method is used to insert and update data after successful payment.
	*/
	public function success()
	{
		
		if($this->request->data)
		{
           	if(PAYMENT_METHOD=='hdfc')
        	{
	           	require_once(ROOT . DS . 'vendor' . DS . 'hdfc' . DS . 'hdfc.php');
	       	   	$objHdfc 			= new Hdfc(Configure::read('HDFC_MERCHANT_KEY'),Configure::read('HDFC_SALT'),Configure::read('HDFC_ACCESS_CODE'), Configure::read('PAYU_SANDBOX'));
	       	   	$arr_data= $objHdfc->decrypt($this->request->data['encResp'],Configure::read('HDFC_SALT'));

	       	   	$arr_reponse_data 	= explode("&",$arr_data);
	       	   	$arr_pass_data 		= array();
	       	   	foreach($arr_reponse_data as $res_d)
	       	   	{
	       	   		$arr_mk_data 					= explode("=",$res_d);
	       	   		$arr_pass_data[$arr_mk_data[0]] = $arr_mk_data[1];
	       	   	}
	       	   	$this->request->data['udf1'] = $arr_pass_data['merchant_param1'];
	       	   	if(strtolower($arr_pass_data['order_status'])=='success')
	       	   	{
	       	   		$response=$this->GeoShiftingApplicationPayment->savedata_success($arr_pass_data,0);
	       	   	}
	       	   	else
	       	   	{
	       	   		$response=$this->GeoShiftingApplicationPayment->savedata_failure($arr_pass_data,0);
	       	   		$this->Flash->error('Payment failed.');
                	//return $this->redirect(URL_HTTP.'view-applyonline/'.$this->request->data['udf1']);
                	return $this->redirect(URL_HTTP.'applications-list');
	       	   	}
	       	}
	       	// else
	       	// {
	       	// 	$response=$this->GeoShiftingApplicationPayment->savedata_success($this->request->data,0);
	       	// }
	       	if($response==1)
            {
                $this->Flash->success('Payment done successfully.');
                //return $this->redirect(URL_HTTP.'applications_wtg_shifting/'.$this->request->data['udf1']);
                return $this->redirect(URL_HTTP.'applications-list');
            }
            else
            {
                $this->Flash->success('Payment done successfully.');
                return $this->redirect(URL_HTTP.'applications-list');
            }		
        }	
        exit;
	}
	/**
	* failure
	* Behaviour : public
	* @defination : Method is used to insert and update data in case of payment fail.
	*/
	public function failure()
	{
        if($this->request->data){
        	$response 		= $this->GeoShiftingApplicationPayment->savedata_failure($this->request->data,0);
			$Error_Message 	= "Error while payment process. Please try again.";
			if($response)
			{
				if (isset($this->request->data['error_Message'])) {
					$Error_Message = $this->request->data['error'].":".$this->request->data['error_Message'];
				}
				$this->Flash->error($Error_Message);
				//return $this->redirect(URL_HTTP.'view-applyonline/'.$this->request->data['udf1']);
				return $this->redirect(URL_HTTP.'applications-list');
			}
			else
			{
				$this->Flash->error($Error_Message);
				return $this->redirect(URL_HTTP.'applications-list');
			}		
        }
        exit;
	}

	public function cancel()
	{
	if($this->request->data){
		$payuTable = TableRegistry::get('GeoShiftingApplicationPayment');
		if(PAYMENT_METHOD=='hdfc')
		{
			require_once(ROOT . DS . 'vendor' . DS . 'hdfc' . DS . 'hdfc.php');
				$objHdfc 			= new Hdfc(Configure::read('HDFC_MERCHANT_KEY'),Configure::read('HDFC_SALT'),Configure::read('HDFC_ACCESS_CODE'), Configure::read('PAYU_SANDBOX'));
				$arr_data= $objHdfc->decrypt($this->request->data['encResp'],Configure::read('HDFC_SALT'));
				
				$arr_reponse_data 	= explode("&",$arr_data);
				$arr_pass_data 		= array();
				foreach($arr_reponse_data as $res_d)
				{
					$arr_mk_data 					= explode("=",$res_d);
					$arr_pass_data[$arr_mk_data[0]] = $arr_mk_data[1];
				}
				$payusave = $payuTable->find('all')->where(['payment_id' => $arr_pass_data['order_id']])->first();
			}
			else
			{
				$payusave = $payuTable->find('all')->where(['payment_id' => $this->request->data['mihpayid']])->first();
			}


		if(empty($payusave)){
			$payusave = $payuTable->newEntity();
		}
		if(PAYMENT_METHOD=='hdfc')
		{
			$payusave->payment_id 		= $arr_pass_data['order_id'];
			$payusave->payment_status 	= strtolower($arr_pass_data['order_status']);
			$this->request->data['udf1']= $arr_pass_data['merchant_param1'];
			$payusave->payment_data 	= json_encode($arr_pass_data);
		}
		else
		{
			$payusave->payment_id 		= $this->request->data['mihpayid'];
			$payusave->payment_status 	= $this->request->data['status'];
			$payusave->payment_data 	= json_encode($this->request->data);
		}
		if ($payuTable->save($payusave)) {
			$ApplicationPaymentRequest 		= TableRegistry::get('GeoShiftingApplicationPaymentRequest');
			$arrPayment 					= $ApplicationPaymentRequest->find('all',array('conditions'=>array('application_id'=>decode($this->request->data['udf1']),'response_data IS NULL'),'order'=>array('id'=>'desc')))->first();
			
			if(!empty($arrPayment)) {
				$arrpay['application_id'] 	= decode($this->request->data['udf1']);
				$arrpay['modified'] 		= $this->NOW();
				$arrpay['response_data']	= json_encode($arr_pass_data);
				$ApplicationPaymentRequest->updateAll($arrpay,array('id'=>$arrPayment->id));
			}
			if (isset($this->request->data['udf1'])) {
				//return $this->redirect(URL_HTTP.'view-applyonline/'.$this->request->data['udf1']);
				return $this->redirect(URL_HTTP.'applications-list');
			} else {
				return $this->redirect(URL_HTTP.'applications-list');
			}
		}
	}
	exit;
	} 
}
