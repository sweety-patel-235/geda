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
use Cake\Network\Email\Email;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class DeveloperPaymentController extends AppController
{

	public function initialize()
    {
        // Always enable the CSRF component.
		parent::initialize();	
        $this->loadModel('ApiToken');
        $this->loadModel('Payumoney');
        $this->loadModel('DeveloperPayment');
        $this->loadModel('Developers');
        $this->loadModel('DeveloperMessage');
        $this->loadModel('DeveloperCustomers');
        $this->loadModel('Emaillog');
        $this->loadModel('ThirdpartyApiLog');
        $this->loadModel('Customers');
	}
   /**
	* index
	* Behaviour : public
	* Parameter : application_form_id
	* @defination : Method is used to send parameter to payumoney.
	*/
    public function index($installer_id='')
    {
		if($installer_id == '' || (Configure::read('PAYUMONEY_PAYMENT') != 1 ))
		{
			return $this->redirect(URL_HTTP.'developer-registration');
		}
		else
		{
			$Developers 			= TableRegistry::get('Developers');
			$exist_developer 		= $Developers->find('all',array('conditions'=>array('id'=>decode($installer_id))))->toArray();
			
			if(empty($exist_developer))
			{
				return $this->redirect(URL_HTTP.'developer-registration');
			}
			elseif($exist_developer[0]->payment_status!=1)
			{
				
				/*$jreda_amount=$exist_developer[0]->jreda_processing_fee;
				$dis_amount=$exist_developer[0]->disCom_application_fee;*/
			}
			else
			{
				return $this->redirect(URL_HTTP.'developer-registration');
			}	
		}
			
		if(PAYMENT_METHOD=='hdfc')
        {
			require_once(ROOT . DS . 'vendor' . DS . 'hdfc' . DS . 'hdfc.php');

			$objHdfc 					= new Hdfc(Configure::read('HDFC_MERCHANT_KEY'),Configure::read('HDFC_SALT'),Configure::read('HDFC_ACCESS_CODE'), Configure::read('PAYU_SANDBOX'));
			$txnId 						= $objHdfc->randomTxnId();
			$hdfc['order_id'] 			= $txnId;               //Transaction Id
			$hdfc['redirect_url'] 		= URL_HTTP.'DeveloperPayment/success';//Router::url(['controller' => 'ReApplicationPayment','action' => 'success'],TRUE); // Success Url
			$hdfc['cancel_url'] 		= URL_HTTP.'DeveloperPayment/cancel';//Router::url(['controller' => 'ReApplicationPayment','action' => 'cancel'],TRUE); 	// Cancel Url
			$hdfc['amount'] 			= isset($exist_developer[0]->developer_total_fee) ? $exist_developer[0]->developer_total_fee : 0; // Amount
			$hdfc['language'] 			= 'EN';
			$hdfc['currency'] 			= 'INR';
			$hdfc['billing_name'] 		= preg_replace('/[^a-z0-9 ]/i', '',$exist_developer[0]->installer_name);
			$hdfc['billing_country'] 	= 'India';
			$hdfc['billing_address'] 	= preg_replace('/[^a-z0-9 ]/i', '',$exist_developer[0]->address);
			$hdfc['billing_city'] 		= preg_replace('/[^a-z0-9 ]/i', '', $exist_developer[0]->city);
			$hdfc['billing_state'] 		= preg_replace('/[^a-z0-9 ]/i', '',$exist_developer[0]->state);
			$hdfc['billing_zip'] 		= $exist_developer[0]->pincode;
			$hdfc['billing_tel'] 		= $exist_developer[0]->mobile;
			$hdfc['delivery_name'] 		= preg_replace('/[^a-z0-9 ]/i', '',$exist_developer[0]->installer_name);
			$hdfc['delivery_country'] 	= 'India';
			$hdfc['delivery_address'] 	= preg_replace('/[^a-z0-9 ]/i', '',$exist_developer[0]->address);
			$hdfc['delivery_city'] 		= preg_replace('/[^a-z0-9 ]/i', '', $exist_developer[0]->city);
			$hdfc['delivery_state'] 	= preg_replace('/[^a-z0-9 ]/i', '',$exist_developer[0]->state);
			$hdfc['delivery_zip'] 		= $exist_developer[0]->pincode;
			$hdfc['delivery_tel'] 		= $exist_developer[0]->mobile;
			$hdfc['merchant_param1'] 	= encode($exist_developer[0]->id);

			$request_data 								= json_encode($hdfc);
			$DeveloperPaymentRequest 					= TableRegistry::get('DeveloperPaymentRequest');
			$DeveloperRequestEntity 					= $DeveloperPaymentRequest->newEntity();
			$DeveloperRequestEntity->installer_id 		= $exist_developer[0]->id;
			$DeveloperRequestEntity->customer_id		= $exist_developer[0]->id;
			$DeveloperRequestEntity->created 			= $this->NOW();
			$DeveloperRequestEntity->modified 			= $this->NOW();
			$DeveloperRequestEntity->request_data		= $request_data;
			$DeveloperRequestEntity->amount 			= $hdfc['amount'];
			$DeveloperRequestEntity->created_by			= $exist_developer[0]->id;
			$DeveloperRequestEntity->modified_by		= $exist_developer[0]->id;
			
			$DeveloperPaymentRequest->save($DeveloperRequestEntity);
			$objHdfc->send($hdfc);
    	}
    	else
    	{
			
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
	       	   		$response=$this->DeveloperPayment->savedata_success($arr_pass_data,0);
	       	   	}
	       	   	else
	       	   	{
	       	   		$response=$this->DeveloperPayment->savedata_failure($arr_pass_data,0);
	       	   		$this->Flash->error('Payment failed.');
                	return $this->redirect(URL_HTTP.'developer-registration/'.$this->request->data['udf1']);
	       	   	}
	       	}
	       	else
	       	{
	       		$response=$this->DeveloperPayment->savedata_success($this->request->data,0);
	       	}
           	if($response==1)
            {
            	//echo"<pre>"; print_r($this->request->data['udf1']); die();
            	$this->ApproveRegistration($this->request->data['udf1'],1,'auto approval');
                $this->Flash->success('Payment done successfully.');
                return $this->redirect(URL_HTTP.'developer-registration/'.$this->request->data['udf1']);
            }
            else
            {
                $this->Flash->success('Payment done successfully.');
                return $this->redirect(URL_HTTP.'');
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
        	$response 		= $this->DeveloperPayment->savedata_failure($this->request->data,0);
			$Error_Message 	= "Error while payment process. Please try again.";
			if($response)
			{
				if (isset($this->request->data['error_Message'])) {
					$Error_Message = $this->request->data['error'].":".$this->request->data['error_Message'];
				}
				$this->Flash->error($Error_Message);
				return $this->redirect(URL_HTTP.'developer-registration/'.$this->request->data['udf1']);
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
			$payuTable = TableRegistry::get('DeveloperPayment');
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
				$DeveloperPaymentRequest 		= TableRegistry::get('DeveloperPaymentRequest');
				$arrPayment 					= $DeveloperPaymentRequest->find('all',array('conditions'=>array('installer_id'=>decode($this->request->data['udf1']),'response_data IS NULL'),'order'=>array('id'=>'desc')))->first();
				
				if(!empty($arrPayment)) {
					$arrpay['installer_id'] 	= decode($this->request->data['udf1']);
					$arrpay['modified'] 		= $this->NOW();
					$arrpay['response_data']	= json_encode($arr_pass_data);
					$DeveloperPaymentRequest->updateAll($arrpay,array('id'=>$arrPayment->id));
				}
				if (isset($this->request->data['udf1'])) {
					return $this->redirect(URL_HTTP.'developer-registration/'.$this->request->data['udf1']);
				} else {
					$this->redirect(URL_HTTP.'applications-list');
				}
			}
		}
		exit;
	} 

	 /*
	 * ApproveRegistration
	 *
	 * Behaviour : public
	 *
	 * @defination : Method is use to approved or rejected status of installer registration.
	 *
	 */
	public function ApproveRegistration($id,$geda_approval,$reason)
	{
		
		$this->autoRender   = false;
		$id                 = (isset($id) ? (decode($id)) : 0);
		$geda_approval    	= (isset($geda_approval) ? $geda_approval : 0);
		$reject_reason    	= (isset($reason) ? $reason : 0);

		$memberId         	= $this->Session->read("Members.id");
		$member_type 		= $this->Session->read('Members.member_type');
		$ErrorMessage 		= '';
		$success        	= 0;
		if(empty($id)) {
			$ErrorMessage   = "Invalid Request. Please validate form details.";
			$success        = 0;
		} else {
			$DevelopersData  	= $this->Developers->find("all",['conditions'=>['id'=>$id]])->first();
			if (!empty($DevelopersData)) {
				if ($this->request->is('post') || $this->request->is('put')) {

					$arrDeveloper = $this->Developers->find('all',
						[
							'fields'=> ['Developers.id','Developers.company_id','Developers.email','developer_customers.email','Developers.mobile','developer_passwords.password','Developers.contact_person'],
							'join'=>[
										[   'table'=>'developer_passwords',
											'type'=>'INNER',
											'conditions'=>'developer_passwords.installer_id = Developers.id'
										],
										[   'table'=>'developer_customers',
											'type'=>'INNER',
											'conditions'=>'developer_customers.installer_id = Developers.id'
										]
									],
							'conditions'=>['Developers.id'=>$id],
							'order'=>['Developers.id'=>'ASC']
						]
					)->first();

					if (!empty($arrDeveloper))
					{
						if($geda_approval == 1) {
							
							$regNo 					= str_pad($arrDeveloper->id,5, "0", STR_PAD_LEFT);
							$financialyear  		= $this->GetGenerateFinancialYear(date('Y-m-d'));
							$registration_no 		= 'GUJ/DEV/'.$financialyear.'/'.$regNo;
							$template_name	= 'developer_registration_login';
							$EmailVars 	= array( 'EMAIL_ADDRESS' 	=> $arrDeveloper->developer_customers['email'],
												'PASSWD' 			=> $arrDeveloper->developer_passwords['password'],
												'CONTACT_NAME' 		=> $arrDeveloper->contact_person,
												'TRANSACTION_NO'	=> '',
												'REGISTRATION_NO'	=>$registration_no,
												'URL_HTTP'			=> URL_HTTP);
							
							$subject        = "Unified Single Window ".RE_SHORT_NAME." Portal Login Details";
							
							$EmailTo        = $arrDeveloper->developer_customers['email'];
							
							
							$email 		= new Email('default');
							$email->profile('default');
							$email->viewVars($EmailVars);
							$message_send = $email->template($template_name, 'default')
								->emailFormat('html')
								->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
								->to($EmailTo)
								->bcc('pulkitdhingra@gmail.com')
								->subject(Configure::read('EMAIL_ENV').$subject)
								->send();

							
							$Emaillog           	= $this->Emaillog->newEntity();
							$Emaillog->email  		= $EmailTo;
							$Emaillog->send_date  	= $this->NOW();
							$Emaillog->action 		= "Developer Password Information";
							$Emaillog->description  = json_encode(array( 
									'EMAIL_ADDRESS' => $arrDeveloper->developer_customers['email'],
									'PASSWD' 		=> $arrDeveloper->developer_passwords['password'],
									'CONTACT_NAME' 	=> $arrDeveloper->contact_person,
									'TRANSACTION_NO'=> isset($paymentData->installer_payment['transaction_id']) ? $paymentData->installer_payment['transaction_id'] : '',
									'REGISTRATION_NO'=> $registration_no,
									'URL_HTTP'		=>URL_HTTP));
							$this->Emaillog->save($Emaillog);
							
							$this->Developers->updateAll(array('status'=>$this->Customers->STATUS_ACTIVE,'modified'=>$this->NOW()),array('id'=>$arrDeveloper->id));
							$this->DeveloperCustomers->updateAll(array('status'=>$this->Customers->STATUS_ACTIVE,'developer_registration_no'=>$registration_no),array('installer_id'=>$arrDeveloper->id));
							$this->send_developer_data($id);
						}
						
						$this->Developers->updateAll(array('geda_approval'=>$geda_approval,
							'approved_by'=>$memberId,'reject_reason'=>$reject_reason,'modified'=>$this->NOW()),array('id'=>$arrDeveloper->id));
					}
				}
			} else {
				$ErrorMessage   			= "Invalid Request. Please validate form details.";
				$success        			= 0;
			}
		}
		$this->Flash->success('Payment done successfully.');
        return $this->redirect(URL_HTTP.'developer-registration/'.$this->request->data['udf1']);
		
	}
	public function GetGenerateFinancialYear($date='')
	{
		$Month   	= date("m",strtotime($date));
		$Year   	= date("Y",strtotime($date));
		$ChallanNo  = "";
		if (intval($Month) >= 1 && intval($Month) <= 3) {
		$ChallanNo  .= ($Year-1)."-".date("y",strtotime($date));
		} else {
		$ChallanNo  .= $Year."-".(date("y",strtotime($date))+1);
		}
		return $ChallanNo;
	}
	public function send_developer_data($id){


		$data = $this->Developers->find('all',
						[ 'fields'=> ['Developers.id','Developers.district','Developers.email','developer_companies.company_name','developer_customers.password','developer_customers.developer_registration_no','Developers.installer_name','Developers.contact_person','Developers.designation','Developers.address','Developers.address1','Developers.taluka','Developers.pincode','Developers.city','Developers.state','district_master.name','Developers.district_code','Developers.type_of_applicant','Developers.contact1','Developers.mobile','developer_customers.email','Developers.website','Developers.pan','Developers.status','Developers.GST','Developers.upload_undertaking','Developers.msme','Developers.d_msme','Developers.name_director','Developers.type_director','Developers.type_director_others','Developers.director_whatsapp','Developers.director_mobile','Developers.director_email','Developers.name_authority','Developers.type_authority','Developers.type_authority_others','Developers.d_file_board','Developers.authority_whatsapp','Developers.authority_mobile','Developers.authority_email','Developers.pan_card','Developers.gst_certificate','Developers.registration_document','Developers.geda_approval','Developers.approved_by','Developers.e_invoice_url','Developers.stateflg'],
							'join'=>[[   'table'=>'developer_companies',
											'type'=>'left',
											'conditions'=>'developer_companies.id = Developers.company_id'
										],[   'table'=>'developer_customers',
											'type'=>'left',
											'conditions'=>'developer_customers.installer_id = Developers.id'
										],[   'table'=>'district_master',
											'type'=>'left',
											'conditions'=>'district_master.id = Developers.district'
										]],'conditions'=>['Developers.id'=>$id],'order'=>['Developers.id'=>'ASC']])->first();
		if(!empty($data['id'])){
				//$apiUrl = 'https://akshayurjasetu.guvnl.com/API/saveApprovedDeveloperData.php';
				$apiUrl = 'https://devakshayurjasetu.guvnl.com/API/saveApprovedDeveloperData.php';
				//$apiUrl = Configure::read('serviceFeasibilityCheckApplicationDetails');
				//curl request
				//[{"key":"Authorization","value":"PsPuH#GvLUn^2005","description":"","type":"text","enabled":true}]
				$conn           			= curl_init($apiUrl);
				$arrRequest = array();
				
				$arrRequest['id']							= $data['id'];
				$arrRequest['company_name']					= $data['developer_companies']['company_name'];
				$arrRequest['password']						= $data['developer_customers']['password'];
				$arrRequest['developer_registration_no']	= $data['developer_customers']['developer_registration_no'];
				$arrRequest['installer_name']				= $data['installer_name'];
				$arrRequest['contact_person']				= $data['contact_person'];

				$arrRequest['designation']					= $data['designation'];
				$arrRequest['address']						= $data['address'];
				$arrRequest['address1']						= $data['address1'];
				$arrRequest['taluka']						= $data['taluka'];
				$arrRequest['pincode']						= $data['taluka'];
				$arrRequest['city']							= $data['city'];
				$arrRequest['state']						= $data['state'];

				$arrRequest['District']						= $data['district_master']['name'];
				$arrRequest['district_code']				= $data['district_code'];
				$arrRequest['type_of_applicant']			= $data['type_of_applicant'];
				$arrRequest['contact1']						= $data['contact1'];
				$arrRequest['mobile']						= $data['mobile'];
				$arrRequest['email']						= $data['email'];
				$arrRequest['website']						= $data['website'];

				$arrRequest['pan']							= $data['pan'];
				$arrRequest['status']						= $data['status'];
				$arrRequest['GST']							= $data['GST'];
				$arrRequest['upload_undertaking']			= $data['upload_undertaking'];
				$arrRequest['msme']							= $data['msme'];
				$arrRequest['d_msme']						= $data['d_msme'];
				$arrRequest['name_director']				= $data['name_director'];

				$arrRequest['type_director']				= $data['type_director'];
				$arrRequest['type_director_others']			= $data['type_director_others'];
				$arrRequest['director_whatsapp']			= $data['director_whatsapp'];
				$arrRequest['director_mobile']				= $data['director_mobile'];
				$arrRequest['director_email']				= $data['director_email'];
				$arrRequest['name_authority']				= $data['name_authority'];
				$arrRequest['type_authority']				= $data['type_authority'];

				$arrRequest['type_authority_others']		= $data['type_authority_others'];
				$arrRequest['d_file_board']					= $data['d_file_board'];
				$arrRequest['authority_whatsapp']			= $data['authority_whatsapp'];
				$arrRequest['authority_mobile']				= $data['authority_mobile'];
				$arrRequest['authority_email']				= $data['authority_email'];
				$arrRequest['pan_card']						= $data['pan_card'];
				$arrRequest['pan_card']						= $data['pan_card'];
				
				$arrRequest['registration_document']		= $data['registration_document'];
				$arrRequest['geda_approval']				= $data['geda_approval'];
				$arrRequest['approved_by']					= $data['approved_by'];
				$arrRequest['e_invoice_url']				= $data['e_invoice_url'];
				$arrRequest['stateflg']						= $data['stateflg'];
				

				$conn    = curl_init($apiUrl);

				curl_setopt($conn, CURLOPT_CONNECTTIMEOUT, 300);
				curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($conn, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($conn, CURLOPT_HTTPHEADER, [
				    "Authorization: PsPuH#GvLUn^2005"
				]);
				curl_setopt($conn, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($conn, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($conn, CURLOPT_POSTFIELDS, http_build_query($arrRequest));
			
				$response = curl_exec($conn);
				
				curl_close ($conn);
				if(!empty($response)){
					
					$id 								= intval(decode($id));
					$thirdpartyEntity                   = $this->ThirdpartyApiLog->newEntity(); 
					$thirdpartyEntity->application_id   = $id;
					$thirdpartyEntity->project_id       = 0; 
					$thirdpartyEntity->request_data     = json_encode($arrRequest);
					$thirdpartyEntity->response_data    = $response;
					$thirdpartyEntity->api_url          = $apiUrl;
					$thirdpartyEntity->created          = $this->NOW();
					$this->ThirdpartyApiLog->save($thirdpartyEntity);
				}
				return true;
				
			}
		
			return true;

	}
}
