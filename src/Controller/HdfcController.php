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
class HdfcController extends AppController
{

	public function initialize()
    {
        // Always enable the CSRF component.
		parent::initialize();	
        $this->loadModel('ApiToken');
        $this->loadModel('Payumoney');
        $this->loadModel('InstallerPayment');
	}
   /**
	*
	* index
	*
	* Behaviour : public
	*
	* Parameter : application_form_id
	*
	* @defination : Method is used to send parameter to payumoney.
	*
	*/
    public function index($application_form_id='')
    {
		if($this->Session->check('Customers')) 
		{
            $customerId = $this->Session->read('Customers.id');
			$cusTable = TableRegistry::get('Customers');	
		    $customer=$cusTable->find('all',array('conditions'=>array('id'=>$customerId)))->first();
			if($customerId=='')
			{
				return $this->redirect(URL_HTTP.'apply-online-list');
			}
        } else {
			return $this->redirect(URL_HTTP);
		}

		if($application_form_id == '' || (Configure::read('PAYUMONEY_PAYMENT') != 1 ))
		{
			return $this->redirect(URL_HTTP.'apply-online-list');
		}
		else
		{
			$apply_onlinesTable = TableRegistry::get('ApplyOnlines');
			$exist_application=$apply_onlinesTable->find('all',array('conditions'=>array('id'=>decode($application_form_id))))->toArray();
				
			if(empty($exist_application))
			{
				 return $this->redirect(URL_HTTP.'apply-online-list');
			}
			elseif($exist_application[0]->payment_status!=1)
			{
				$jreda_amount=$exist_application[0]->jreda_processing_fee;
				$dis_amount=$exist_application[0]->disCom_application_fee;
			}
			else
			{
				  return $this->redirect(URL_HTTP.'apply-online-list');
			}	
		}
		if(PAYMENT_METHOD=='hdfc')
        {
			require_once(ROOT . DS . 'vendor' . DS . 'hdfc' . DS . 'hdfc.php');
	        $objHdfc 			= new Hdfc(Configure::read('HDFC_MERCHANT_KEY'),Configure::read('HDFC_SALT'),Configure::read('HDFC_ACCESS_CODE'), Configure::read('PAYU_SANDBOX'));
			$txnId 						= $objHdfc->randomTxnId();
	        $hdfc['order_id'] 			= $txnId;               //Transaction Id
	        $hdfc['redirect_url'] 		= Router::url(['controller' => 'Payutransfer','action' => 'success'],TRUE); // Success Url
	        $hdfc['cancel_url'] 		= Router::url(['controller' => 'Payutransfer','action' => 'cancel'],TRUE); 	// Cancel Url
	        $hdfc['amount'] 			= $dis_amount + $jreda_amount; // Amount
	        $hdfc['language'] 			= 'EN';
	        $hdfc['currency'] 			= 'INR';
	        $hdfc['billing_name'] 		= preg_replace('/[^a-z0-9 ]/i', '',$exist_application[0]->name_of_consumer_applicant);
	        $hdfc['billing_country'] 	= 'India';
	        $hdfc['billing_address'] 	= preg_replace('/[^a-z0-9 ]/i', '',$exist_application[0]->address1);
	        $hdfc['billing_city'] 		= preg_replace('/[^a-z0-9 ]/i', '', $exist_application[0]->city);
	        $hdfc['billing_state'] 		= preg_replace('/[^a-z0-9 ]/i', '',$exist_application[0]->state);
	        $hdfc['billing_zip'] 		= $exist_application[0]->pincode;
	        $hdfc['billing_tel'] 		= $exist_application[0]->consumer_mobile;
	        $hdfc['delivery_name'] 		= preg_replace('/[^a-z0-9 ]/i', '',$exist_application[0]->name_of_consumer_applicant);
	        $hdfc['delivery_country'] 	= 'India';
	        $hdfc['delivery_address'] 	= preg_replace('/[^a-z0-9 ]/i', '',$exist_application[0]->address1);
	        $hdfc['delivery_city'] 		= preg_replace('/[^a-z0-9 ]/i', '', $exist_application[0]->city);
	        $hdfc['delivery_state'] 	= preg_replace('/[^a-z0-9 ]/i', '',$exist_application[0]->state);
	        $hdfc['delivery_zip'] 		= $exist_application[0]->pincode;
	        $hdfc['delivery_tel'] 		= $exist_application[0]->consumer_mobile;
	        $hdfc['merchant_param1'] 	= encode($exist_application[0]->id);
	        
	        $objHdfc->send($hdfc);
    	}
    	else
    	{
    		require_once(ROOT . DS . 'vendor' . DS . 'payumoney' . DS . 'payu.php');
	        $objPayu 			= new Payu(Configure::read('PAYU_MERCHANT_KEY'), Configure::read('PAYU_MERCHANT_SALT'), Configure::read('PAYU_SANDBOX'));
			$txnId 				= $objPayu->randomTxnId();
			$description 		= "Payment to Discom Apply online Application (".$application_form_id.")";
	        $firstSplitArr[0] 	= array("name"=>"splitID1",
										"value"=>$dis_amount,
										"merchantId"=>Configure::read('MERCHANT_ID_1'),
										"description"=>$description,
										"commission"=>"0");
	        if($jreda_amount>0)
			{
				$description 		= "Payment to JREDA Apply online Application (".$application_form_id.")";
				$firstSplitArr[1] 	= array("name"=>"splitID2",
										"value"=>$jreda_amount,
										"merchantId"=>Configure::read('MERCHANT_ID_2'),
										"description"=>$description,
										"commission"=>"0");
	    	}
	        $paymentPartsArr 	= ($firstSplitArr);
	        $finalInputArr 		= array("paymentParts" => $paymentPartsArr);
	        $Prod_info 			= json_encode($finalInputArr);
			if($application_form_id != '') {
				  $payu['udf1'] = $application_form_id;
			}
	        //Payu settings
	        $payu['txnid'] 			= $txnId;               //Transaction Id
	        $payu['firstname'] 		= $customer->name;		//'Peter';
	        $payu['email'] 			= $customer->email;		//'sachin.patel@yugtia.com';
	        $payu['phone'] 			= $customer->mobile; 	//'1234567890';
	        $payu['productinfo'] 	= $Prod_info;         	// Product Info
	        $payu['surl'] 			= Router::url(['controller' => 'Payutransfer','action' => 'success'],TRUE); // Success Url
	        $payu['furl'] 			= Router::url(['controller' => 'Payutransfer','action' => 'failure'],TRUE); // Fail Url
	        $payu['curl'] 			= Router::url(['controller' => 'Payutransfer','action' => 'cancel'],TRUE); 	// Cancel Url
	        $payu['amount'] 		= $dis_amount + $jreda_amount; // Amount
	        //Call Vendor function for send to payu
	        $objPayu->send($payu);
    	}
        
        exit;
    }

    /**
	*
	* success
	*
	* Behaviour : public
	*
	* @defination : Method is used to insert and update data after successful payment.
	*
	*/
	public function success()
	{
		//$this->request->data['encResp']='d4ada0f828bd2dd9411089cd661bbce3f23e100fa92cba523310929e749c34fcd3e6eaf9b88ee4edf2d358b535c414c961747927d0162ac0f5b89ea1d1f4d416cd1d1dfe1617930755da53473022806c4d8cc8f409a37a5aba7fd24e3da4af73bbe286a68eae7b55ee231f6aedacdabb046304169857949be201893933e35be39c079f09a82a1472247b6641d6fce828d952f0ef45f2c3100d6a38b82a2b58169e57a80cb05657e5bb805787e88435ec597573af465e240c5756ec30d7e77e70afe1afdfc65e4e348122c5f3da68751c935afd9b848133bdbcb2027ba129bcb991a9a9e0cbf8b32fd0447b691e7d14ecab894a78ba73206e13233441d3c7eb7b171059e4b4131a0b6a980a889d8bf84f4c97e12a46d9fd04f32cf38dc6172b084139178a4facf60895739a9b061e4a40827691fea3692945b25dbbf068b933217b17d0c1f236c5d39056af4b6f123b6014f2d19b2fbaf5faefdba4c1c4ea0770f43944ccdd752e4ee2a088c62fc6842b84eea28a2a41c0f3f1a07c51e226db5b2fdc83ead1ef693924faf5f018b4c93d580174b329744f5994fb0ac70f0f8567a2f8551d97e258bb39009e02512206c62280323765848506da1737a6a59313660227eccaaeed5770d74f800ea9d27a7e6c46ca47d8d6ff2f97f1680a0b2914f100893ccb7bc8cfd3474577340fb093a283ae127e317e3db71026d4bf62232b4e46f298f3e177389cb5137de4da22b6e8ce65f2c3f86a7ca4e81fca2504b6cbaae6a0c892261d6e164200df9ed10bf9508c35cb981c285bf5203086d942f10cc278f9ed4573645659bc1d3a7dbaf9ea8901b42625fe82c81844561f65d4b85ba560a67429d16d2ce310452aad4610301a3ff3c6d699ecf72aacb7322b119689e7ba87ddc7324fad2aa65c8ab760ad60fd1e6bfe42376111f9689bca43531bf4870b6e85c9397ef44bc9d3c1313d95888d6a04806384f2417e6924e7f3abc8fd618491fce2319cc19c9143be11c77ce2b10f2d4461eef283a94c42704f7ff3f1c199e8e1f75f4ec5c2d6d5cbd2f4f0e1106c383c77b95320d7dd481326d6d89803a816d12262dcd8be4013ecada40bac68';
		//$this->request->data['orderNo']='7e38e5cbe192f833b4da';
		
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
	       	   		$response=$this->Payumoney->savedata_success($arr_pass_data,0);
	       	   	}
	       	   	else
	       	   	{
	       	   		$response=$this->Payumoney->savedata_failure($arr_pass_data,0);
	       	   		$this->Flash->error('Payment failed.');
                	return $this->redirect(URL_HTTP.'view-applyonline/'.$this->request->data['udf1']);
	       	   	}
	       	}
	       	else
	       	{
	       		$response=$this->Payumoney->savedata_success($this->request->data,0);
	       	}
           	if($response==1)
            {
                $this->Flash->success('Payment done successfully.');
                return $this->redirect(URL_HTTP.'view-applyonline/'.$this->request->data['udf1']);
            }
            else
            {
                $this->Flash->success('Payment done successfully.');
                return $this->redirect(URL_HTTP.'apply-online-list');
            }		
        }	
        exit;
	}
	/**
	*
	* failure
	*
	* Behaviour : public
	*
	* @defination : Method is used to insert and update data in case of payment fail.
	*
	*/
	public function failure()
	{
        if($this->request->data){
        	$response 		= $this->Payumoney->savedata_failure($this->request->data,0);
			$Error_Message 	= "Error while payment process. Please try again.";
			if($response)
			{
				if (isset($this->request->data['error_Message'])) {
					$Error_Message = $this->request->data['error'].":".$this->request->data['error_Message'];
				}
				$this->Flash->error($Error_Message);
				return $this->redirect(URL_HTTP.'view-applyonline/'.$this->request->data['udf1']);
			}
			else
			{
				$this->Flash->error($Error_Message);
				return $this->redirect(URL_HTTP.'apply-online-list');
			}		
        }
        exit;
	}

	public function cancel()
	{
        if($this->request->data){
            $payuTable = TableRegistry::get('Payumoney');
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
				if (isset($this->request->data['udf1'])) {
					return $this->redirect(URL_HTTP.'view-applyonline/'.$this->request->data['udf1']);
				} else {
					$this->redirect(URL_HTTP.'apply-online-list');
				}
            }
        }
        exit;
	}
	/*public function payumoney_app()
    {

        require_once(ROOT . DS . 'vendor' . DS . 'payumoney' . DS . 'payu.php');
        $objPayu = new Payu(Configure::read('PAYU_MERCHANT_KEY'), Configure::read('PAYU_MERCHANT_SALT'), true);
        
		$txnId = $objPayu->randomTxnId();
		$cus_id	= $this->ApiToken->customer_id;
		$arr_data_pass=$this->request->data;
		$application_form_id='';
		if(!empty($arr_data_pass))
		{
			$application_form_id=$arr_data_pass['application_form_id'];
		}
			
		
		if($application_form_id=='')
		{
			$status = "error";
				$this->ApiToken->SetAPIResponse('type', $status);
				$this->ApiToken->SetAPIResponse('msg', 'Pass Application Form Id!');
		}
		else
		{
			$apply_onlinesTable = TableRegistry::get('ApplyOnlines');
			$exist_application=$apply_onlinesTable->find('all',array('conditions'=>array('id'=>decode($application_form_id))))->toArray();
			$cusTable = TableRegistry::get('Customers');	
		    $customer=$cusTable->find('all',array('conditions'=>array('id'=>$cus_id)))->first();
			if(empty($exist_application))
			{
				$status = "error";
				$this->ApiToken->SetAPIResponse('type', $status);
				$this->ApiToken->SetAPIResponse('msg', 'Application Not Found!');
			}
			elseif(empty($customer))
			{
				$status = "error";
				$this->ApiToken->SetAPIResponse('type', $status);
				$this->ApiToken->SetAPIResponse('msg', 'Customer Not Found!');	
			}
			elseif($exist_application[0]->payment_status!=1)
			{	
				if($exist_application[0]->pv_capacity>50)
				{
					$dis_amount=Configure::read('PV_CAPACITY_GT50');
				}
				else
				{
					$dis_amount=Configure::read('PV_CAPACITY_LT50');
				}
				$jreda_amount=$exist_application[0]->jreda_processing_fee;
				$firstSplitArr[0] = array("name"=>"splitID1", "value"=>$dis_amount, "merchantId"=>"4825050", "description"=>"test description1", "commission"=>"0");
				$firstSplitArr[1] = array("name"=>"splitID2", "value"=>$jreda_amount, "merchantId"=>"4825051", "description"=>"test description2", "commission"=>"0");
				$paymentPartsArr = ($firstSplitArr);
				$finalInputArr = array("paymentParts" => $paymentPartsArr);	
				$Prod_info = ($finalInputArr);
				if($application_form_id!='')
				{
					  $payu['udf1'] = $application_form_id;
				}
			
				//Payu settings
				$payu['txnid'] = $txnId;                                                  //Transaction Id
				$payu['firstname'] = $customer->name;//'Peter';
				$payu['email'] = $customer->email;//'sachin.patel@yugtia.com';
				$payu['phone'] = $customer->mobile;//'1234567890';
				$payu['productinfo'] = $Prod_info;         // Product Info
				$payu['surl'] = Router::url(['controller' => 'Payutransfer','action' => 'success'],TRUE);   // Success Url
				$payu['furl'] = Router::url(['controller' => 'Payutransfer','action' => 'failure'],TRUE);  // Fail Url
				$payu['curl'] = Router::url(['controller' => 'Payutransfer','action' => 'cancel'],TRUE); // Cancel Url
				$payu['amount'] = $dis_amount+$jreda_amount; 
					$this->ApiToken->SetAPIResponse('result', $payu);	
					$status = 'ok';
					$this->ApiToken->SetAPIResponse('type', $status);
					 
					 
			}
			else
			{
			$status = "error";
			$this->ApiToken->SetAPIResponse('type', $status);
			$this->ApiToken->SetAPIResponse('msg', 'Payment Already Done!');
			}
			
		}
	   echo $this->ApiToken->GenerateAPIResponse();
        exit;
    }
	public function createhase()
	{
		//pr($this->request->data);
		require_once(ROOT . DS . 'vendor' . DS . 'payumoney' . DS . 'payu.php');
        $objPayu = new Payu(Configure::read('PAYU_MERCHANT_KEY'), Configure::read('PAYU_MERCHANT_SALT'), true);
		$arr_data_pass=array();
		$arr_data_pass=$this->request->data;
		//print_r($arr_data_pass);
		$arr_data_pass['productinfo']=$arr_data_pass['productInfo'];
		$arr_data_pass['firstname']=$arr_data_pass['firstName'];
		unset($arr_data_pass['productInfo']);
		unset($arr_data_pass['firstName']);
		unset($arr_data_pass['SURL']);
		unset($arr_data_pass['FURL']);
		unset($arr_data_pass['phone']);
		
		
		$arr_passed['key']=$this->request->data('amount');
		$arr_passed['txnid']=$this->request->data('key');
		$arr_passed['amount']=$this->request->data('txnid');
		$arr_passed['productinfo']=$this->request->data('amount');
		$arr_passed['firstname']=$this->request->data('amount');
		$arr_passed['email']=$this->request->data('amount');
		$arr_passed['udf1']=$this->request->data('amount');
		$arr_passed['udf2']=$this->request->data('amount');
		echo json_encode(array("hash"=>$objPayu->generateHashforAPI($this->request->data)));
		exit;
	}*/
	/**
	*
	* installer_payment
	*
	* Behaviour : public
	*
	* Parameter : installer_id
	*
	* @defination : Method is used to send parameter to HDFC for installer payment.
	*
	*/
    public function installer_payment($installer_id='')
    {
		

		
		if(PAYMENT_METHOD=='hdfc')
        {
			require_once(ROOT . DS . 'vendor' . DS . 'hdfc' . DS . 'hdfc.php');
	        $objHdfc 			= new Hdfc(Configure::read('HDFC_MERCHANT_KEY'),Configure::read('HDFC_SALT'),Configure::read('HDFC_ACCESS_CODE'), Configure::read('PAYU_SANDBOX'));
			
	        $hdfc['order_no'] 			= '8253beede2a78eb4664a';               //Transaction Id
	     
	        
	        $response 	= $objHdfc->getData($hdfc);
	        print_r($response);
    	}
        exit;
    }
    /**
	*
	* installer_success
	*
	* Behaviour : public
	*
	* @defination : Method is used to insert and update data after successful payment.
	*
	*/
	public function installer_success()
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
	       	   		$response=$this->InstallerPayment->savedata_success($arr_pass_data,0);
	       	   	}
	       	   	else
	       	   	{
	       	   		$response=$this->InstallerPayment->savedata_failure($arr_pass_data,0);
	       	   		$this->Flash->error('Payment failed.');
                	return $this->redirect(URL_HTTP.'installer-payment/'.$this->request->data['udf1']);
	       	   	}
	       	}
	       	
           	if($response==1)
            {
                $this->Flash->success('Payment done successfully.');
                return $this->redirect(URL_HTTP.'installer-payment/'.$this->request->data['udf1']);
            }
            else
            {
                $this->Flash->success('Payment done successfully.');
                return $this->redirect(URL_HTTP.'installer-registration');
            }		
        }	
        exit;
	}
	public function installer_cancel()
	{
		if($this->request->data) {
			$payuTable = TableRegistry::get('Payumoney');
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
			$inspaymentsave 	= $this->InstallerPayment->find('all')->where(['payment_id' => $arr_pass_data['order_id']])->first();

			if(empty($inspaymentsave)){
				$inspaymentsave = $this->InstallerPayment->newEntity();
			}
			if(PAYMENT_METHOD=='hdfc')
			{
				$inspaymentsave->payment_id 		= $arr_pass_data['order_id'];
				$inspaymentsave->payment_status 	= strtolower($arr_pass_data['order_status']);
				$inspaymentsave->udf1 				= $arr_pass_data['merchant_param1'];
				$inspaymentsave->installer_id 		= decode($arr_pass_data['merchant_param1']);
				$this->request->data['udf1']		= $arr_pass_data['merchant_param1'];
				$inspaymentsave->payment_data 		= json_encode($arr_pass_data);
				$inspaymentsave->created 			= $this->NOW();
			}
			else
			{
				$inspaymentsave->payment_id 		= $this->request->data['mihpayid'];
				$inspaymentsave->payment_status 	= $this->request->data['status'];
				$inspaymentsave->payment_data 	= json_encode($this->request->data);
			}
			if ($this->InstallerPayment->save($inspaymentsave)) {
				if (isset($this->request->data['udf1'])) {
					return $this->redirect(URL_HTTP.'installer-payment/'.$this->request->data['udf1']);
				} else {
					$this->redirect(URL_HTTP.'installer-registration');
				}
			}
		}
		exit;
	}
}
