<?php
namespace App\Controller\Admin;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\ORM\Table;
use Cake\Core\Configure;
use Cake\Routing\Router;
use Cake\Network\Email\Email;
use Cake\Network\Exception\NotFoundException;
use Payu\Payu;
use Cake\View\Exception\MissingTemplateException;
use Cake\Controller\Component;
use Cake\Utility\Security;
use Cake\Event\Event;
use Cake\View\Helper\PaginatorHelper;
use Cake\Validation\Validator;

class PayutransferController extends AppController
{
	public $user_department = array();
	public $arrDefaultAdminUserRights = array(); 
	public $helpers = array('Time','Html','Form','ExPaginator');
	public $PAGE_NAME = '';
	
	/*
	 * initialize controller
	 *
	 * @return void
	 */
	public function initialize()
    {
        // Always enable the CSRF component.
		parent::initialize();
		$this->loadModel('ApiToken');
		$this->loadModel('Payumoney');
		$this->loadModel('InstallerPlans');
		$this->loadModel('Installers');
		$this->loadModel('InstallersCoupan');
	}
	/**
	*
	* payumoney_app
	*
	* Behaviour : public
	*
	* Parameter : application_form_id
	*
	* @defination : API Method is used to send payumoney data to app and fetch the data from customer id which get from ApiToken
	*
	*/
	public function payumoney_app()
    {
        require_once(ROOT . DS . 'vendor' . DS . 'payumoney' . DS . 'payu.php');
        $objPayu 	= new Payu(Configure::read('PAYU_MERCHANT_KEY'), Configure::read('PAYU_MERCHANT_SALT'), Configure::read('PAYU_SANDBOX'));
		$txnId 		= $objPayu->randomTxnId();
		$customer_id= $this->ApiToken->customer_id;
		if(!empty($customer_id)) 
		{
			if((Configure::read('PAYUMONEY_PAYMENT') == 1 || (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] == "203.88.138.46")))
			{
				$arr_data_pass=$this->request->data;
				$application_form_id='';
				if(!empty($arr_data_pass))
				{
					$application_form_id = $arr_data_pass['application_form_id'];
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
					$exist_application 	= $apply_onlinesTable->find('all',array('conditions'=>array('id'=>decode($application_form_id))))->toArray();
					$cusTable = TableRegistry::get('Customers');	
				    $customer=$cusTable->find('all',array('conditions'=>array('id'=>$customer_id)))->first();
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
						if($exist_application[0]->apply_state == '4' || strtolower($exist_application[0]->apply_state)=='gujarat')
						{
							$dis_amount 	= $exist_application[0]->disCom_application_fee;
							$jreda_text 	= 'GEDA';
						}
						else
						{
							if($exist_application[0]->pv_capacity>50)
							{
								$dis_amount = Configure::read('PV_CAPACITY_GT50');
							}
							else
							{
								$dis_amount = Configure::read('PV_CAPACITY_LT50');
							}
							$jreda_text 	= 'JREDA';
						}
						$jreda_amount 		= $exist_application[0]->jreda_processing_fee;

						$description 		= "Payment to Discom Apply online Application (".$application_form_id.")";
						$firstSplitArr[0] 	= array("name"=>"splitID1",
													"value"=>$dis_amount,
													"merchantId"=>Configure::read('MERCHANT_ID_1'),
													"description"=>$description,
													"commission"=>"0");
						if($jreda_amount>0)
						{
							$description 		= "Payment to ".$jreda_text." Apply online Application (".$application_form_id.")";
							$firstSplitArr[1] 	= array("name"=>"splitID2",
													"value"=>$jreda_amount,
													"merchantId"=>Configure::read('MERCHANT_ID_2'),
													"description"=>$description,
													"commission"=>"0");
						}
						$paymentPartsArr 	= ($firstSplitArr);
						$finalInputArr 		= array("paymentParts" => $paymentPartsArr);	
						$Prod_info 			= ($finalInputArr);
						if($application_form_id!='')
						{
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
			} else {
				$status = "error";
				$this->ApiToken->SetAPIResponse('type', $status);
				$this->ApiToken->SetAPIResponse('msg', 'Payment mode off');
			}
		}
		else
		{
			$status = "error";
			$this->ApiToken->SetAPIResponse('type', $status);
			$this->ApiToken->SetAPIResponse('msg', 'Invalid Customer Id!');
		}
	    echo $this->ApiToken->GenerateAPIResponse();
        exit;
    }
	
    /**
	*
	* createhash
	*
	* Behaviour : public
	*
	* @defination : API Method is used to send hash to app
	*
	*/
	public function createhash()
	{
		$this->autoRender 	= false;
		$customer_id = $this->ApiToken->customer_id;
		if(!empty($customer_id)) 
		{
		require_once(ROOT . DS . 'vendor' . DS . 'payumoney' . DS . 'payu.php');
        $objPayu = new Payu(Configure::read('PAYU_MERCHANT_KEY'), Configure::read('PAYU_MERCHANT_SALT'), Configure::read('PAYU_SANDBOX'));
		$arr_data_pass = array();
		$arr_data_pass = $this->request->data;
		$arr_data_pass['productinfo'] = $arr_data_pass['productInfo'];
		$arr_data_pass['firstname'] = $arr_data_pass['firstName'];
		unset($arr_data_pass['productInfo']);
		unset($arr_data_pass['firstName']);
		unset($arr_data_pass['SURL']);
		unset($arr_data_pass['FURL']);
		unset($arr_data_pass['phone']);
		$status = 'ok';
		$this->ApiToken->SetAPIResponse('type', $status); 
		$this->ApiToken->SetAPIResponse('hash', $objPayu->generateHashforAPI($this->request->data)); 
		}
		else
		{
			$status = "error";
			$this->ApiToken->SetAPIResponse('type', $status);
			$this->ApiToken->SetAPIResponse('msg', 'Invalid Customer Id!');
		}
		echo $this->ApiToken->GenerateAPIResponse();
        exit;
	}
	  /**
	*
	* success
	*
	* Behaviour : public
	*
	* @defination : API method is used to insert and update data after successful payment form Mobile APP
	*
	*/
	public function success()
	{
        if($this->request->data)
		{
			$response=$this->Payumoney->savedata_success($this->request->data,1);
			if($response==1)
			{
			?>
                <script type="text/javascript">
                var toast='<?php echo json_encode($this->request->data);?>';
                    if(typeof Android !== "undefined" && Android !== null) {
                        Android.onSuccess(toast);
                    } else {
                        //alert("Not viewing in webview");
                    }
                </script>
                <?php
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
        	$response=$this->Payumoney->savedata_failure($this->request->data,1);
			if($response==1)
			{
			?>
				<script type="text/javascript">
		    	var toast='<?php echo json_encode($this->request->data);?>';
		        if(typeof Android !== "undefined" && Android !== null) {
		            Android.onFailure(toast);
		        } else {
		            //alert("Not viewing in webview");
		        }
				</script>
		<?php
            }
        }
        exit;
	}
	/**
	*
	* payumoney_installer_upgrade
	*
	* Behaviour : public
	*
	* @defination : API Method is used to send payumoney data to app and fetch the data from customer id which get from ApiToken and also fetch data from installer_id posted when API call.
	*
	*/
	public function payumoney_installer_upgrade()
    {
        require_once(ROOT . DS . 'vendor' . DS . 'payumoney' . DS . 'payu.php');
        $objPayu 	= new Payu(Configure::read('PAYU_MERCHANT_KEY'), Configure::read('PAYU_MERCHANT_SALT'), Configure::read('PAYU_SANDBOX'));
		$txnId 		= $objPayu->randomTxnId();
		if($this->Session->check('Customers')) 
		{
            $customer_id = $this->Session->read('Customers.id');
        }
        //$customer_id = '53';
		$customer_id= $this->ApiToken->customer_id;
		if(!empty($customer_id)) 
		{
			if((Configure::read('PAYUMONEY_PAYMENT') == 1 ))
			{
				$arr_data_pass 		= $this->request->data;
				$installer_id 		= '';
				if(!empty($arr_data_pass))
				{
					$installer_id 	= $arr_data_pass['installer_id']; //$arr_data_pass['installer_id'];
					$coupan_code 	= $arr_data_pass['coupan_code'];
				}	
				//$installer_id = encode('1277');
				//$coupan_code  = 'CEO1';
				if($installer_id == '')
				{
					$status = "error";
					$this->ApiToken->SetAPIResponse('type', $status);
					$this->ApiToken->SetAPIResponse('msg', 'Pass Application Form Id!');
				}
				else
				{
					$cusTable = TableRegistry::get('Customers');	
				    $customer = $cusTable->find('all',array('conditions'=>array('id'=>$customer_id)))->first();
					if(empty($customer))
					{
						$status = "error";
						$this->ApiToken->SetAPIResponse('type', $status);
						$this->ApiToken->SetAPIResponse('msg', 'Customer Not Found!');	
					}
					else
					{	
						$installer_details		= $this->Installers->find('all',array('conditions'=>array('id'=>decode($installer_id))))->toArray();
						$planId 				= $installer_details[0]['installer_plan_id'];
						if($planId > 0)
						{
							$insplanData 		= $this->InstallerPlans->get($planId);
							$amount 			= $insplanData['plan_price'];
							if($coupan_code != '')
							{
							$installerCupn = $this->InstallersCoupan->find('all', array('conditions'=>array('coupan_code'=>$coupan_code)))->toArray();
								if(!empty($installerCupn)){
									$coupanObj 	= $installerCupn[0];
									if(empty($coupanObj->is_flat)  || $coupanObj->is_flat == 0)
									{
										$amount = $amount - (($amount * $coupanObj->amount)/100);
									} else {
										$amount = $amount - $coupanObj->amount;
									}
								}
							}
							$description 		= "Payment Installer Registration (".$installer_id.")";
							$firstSplitArr[0] 	= array("name"=>"splitID1",
														"value"=>$amount,
														"merchantId"=>Configure::read('MERCHANT_ID_1'),
														"description"=>$description,
														"commission"=>"0");
							
							$paymentPartsArr 	= ($firstSplitArr);
							$finalInputArr 		= array("paymentParts" => $paymentPartsArr);	
							$Prod_info 			= json_encode($finalInputArr); //remove json_encode for APP
							if($installer_id!='')
							{
								  $payu['udf1'] = $installer_id;
							}
							$payu['udf2'] 		= $coupan_code;
							//Payu settings
							$payu['txnid'] 		= $txnId;               //Transaction Id
							$payu['firstname'] 	= $customer->name;		//'Peter';
							$payu['email'] 		= $customer->email;		//'sachin.patel@yugtia.com';
							$payu['phone'] 		= $customer->mobile; 	//'1234567890';
							$payu['productinfo']= $Prod_info;         	// Product Info
							$payu['surl'] 		= Router::url(['controller' => 'Payutransfer','action' => 'installer_success'],TRUE); // Success Url
							$payu['furl'] 		= Router::url(['controller' => 'Payutransfer','action' => 'installer_failure'],TRUE); // Fail Url
							$payu['curl'] 		= Router::url(['controller' => 'Payutransfer','action' => 'installer_cancel'],TRUE); 	// Cancel Url
							$payu['amount'] 	= $amount; // Amount
							//$objPayu->send($payu);
	        				//exit;
							$this->ApiToken->SetAPIResponse('result', $payu);	
							$status = 'ok';
							$this->ApiToken->SetAPIResponse('type', $status); 
						}
						else 
						{
							$status = "error";
							$this->ApiToken->SetAPIResponse('type', $status);
							$this->ApiToken->SetAPIResponse('msg', 'Plan not set');
						}
					}	
				}
			} else {
				$status = "error";
				$this->ApiToken->SetAPIResponse('type', $status);
				$this->ApiToken->SetAPIResponse('msg', 'Payment mode off');
			}
		}
		else
		{
			$status = "error";
			$this->ApiToken->SetAPIResponse('type', $status);
			$this->ApiToken->SetAPIResponse('msg', 'Invalid Customer Id!');
		}
	    echo $this->ApiToken->GenerateAPIResponse();
        exit;
    }
    /**
	*
	* installer_success
	*
	* Behaviour : public
	*
	* @defination : API method is used to insert and update data after successful payment form Mobile APP
	*
	*/
	public function installer_success()
	{
        if($this->request->data)
		{
			$response=$this->InstallerSubscription->saveinstaller_success($this->request->data,1);
			if($response==1)
			{
			?>
                <script type="text/javascript">
                var toast='<?php echo json_encode($this->request->data);?>';
                    if(typeof Android !== "undefined" && Android !== null) {
                        Android.onSuccess(toast);
                    } else {
                        //alert("Not viewing in webview");
                    }
                </script>
                <?php
            }
        }
        exit;
	}
	/**
	*
	* installer_failure
	*
	* Behaviour : public
	*
	* @defination : API method is used to insert and update data after successful payment form Mobile APP
	*
	*/
	public function installer_failure()
	{
        if($this->request->data)
		{
			$response=$this->InstallerSubscription->saveinstaller_failure($this->request->data,1);
			if($response==1)
			{
			?>
                <script type="text/javascript">
                var toast='<?php echo json_encode($this->request->data);?>';
                    if(typeof Android !== "undefined" && Android !== null) {
                        Android.onSuccess(toast);
                    } else {
                        //alert("Not viewing in webview");
                    }
                </script>
                <?php
            }
        }
        exit;
	}
}