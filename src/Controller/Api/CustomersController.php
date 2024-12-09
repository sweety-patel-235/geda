<?php
namespace App\Controller\Api;

class CustomersController extends ApiMasterController
{	
	
	private function SetVariables($post_variables) {
		if(isset($post_variables['mobile']))
			$this->request->data['Customers']['mobile']		= $post_variables['mobile'];
		if(isset($post_variables['email']))
			$this->request->data['Customers']['email']		= $post_variables['email'];
		if(isset($post_variables['name']))
			$this->request->data['Customers']['name']		= $post_variables['name'];
		if(isset($post_variables['pass']))
			$this->request->data['Customers']['password']	= $post_variables['pass'];
		if(isset($post_variables['token']))
			$this->ApiToken->token	= $post_variables['token'];
	}
	
	/*
	 * initialize controller
	 *
	 * @return void
	 */
	public function initialize()
    {
        // Always enable the CSRF component.
		parent::initialize();
		$this->loadComponent('Paginator');
		$this->loadModel('Customers');
		$this->loadModel('ApiToken');
		$this->loadModel('SmsResponse');
		$this->loadModel('Installers');
    }
	

	
    /**
	 *
	 * registration
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to add new admin user and provide specific righs using admin interface
	 *
	 */
	public function registration()
	{
		$customer		  	= null;
		$customercnt	  	= 0;
		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		$customersEntity 	= $this->Customers->newEntity($this->request->data());
		$customersEntity->created = $this->NOW();
		$customercnt		= $this->Customers->find('all', array('conditions'=>array('email'=>$this->request->data['email'])))->count();
		if($customercnt == 0) {
			if ($this->Customers->save($customersEntity)) {
				$status				= 'ok';
				/* Activation code generated and sent SMS and email */
				$activation_code	= $this->Customers->GenerateActivationCode($customersEntity->id);
				$this->SendActivationCodeToCustomer($customersEntity->id,$activation_code, $customersEntity->email, $customersEntity->mobile);
				/* Activation code generated and sent SMS and email */

				/* On registration logged in user. */
				$this->ApiToken->LoggedInAPIUser($this->ApiToken->token, $customersEntity->id);
				$this->Customers->LoggedinUser($customersEntity->id);
				/* On registration logged in user. */

				//$this->SendNewUserRegistrationNotificationEmail($customersEntity->id, $customer['Customers']['email'], $customer['Customers']['mobile']);
				$this->ApiToken->SetAPIResponse('cus_id', $customersEntity->id);
				$this->ApiToken->SetAPIResponse('active_status', $this->Customers->STATUS_INACTIVE);
			} else {
				$status				= 'error';
				$error				= '';
				$this->ApiToken->SetAPIResponse('msg', $error);
			}
		} else {
			$status				= 'error';
			$error				= 'This email is already registered.';
			$this->ApiToken->SetAPIResponse('msg', $error);
		} 
		$this->ApiToken->SetAPIResponse('type', $status);
		echo $this->ApiToken->GenerateAPIResponse();
		exit;	
	}
	/**
	 *
	 * SendActivationCodeToCustomer
	 *
	 * Behaviour : private
	 *
	 * @defination : Method is use to add new admin user and provide specific righs using admin interface
	 *
	 */
	private function SendActivationCodeToCustomer($customer_id,$activation_code, $email, $mobile, $blnEmail=true)
	{
		if (!empty($mobile) && SEND_SMS) {
			//Send sms to customer
			$this->Customers->SendSMSActivationCode($customer_id,$mobile,$activation_code);
		}
		if(!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) && $blnEmail && SEND_EMAIL) {	
			//Send email to customer.
			$Email = new Email('default');
			$Email->profile('default');
			$Email->viewVars(array('activation_code' => $activation_code));
			$Email->template('send_activation_code', 'empty')
				->emailFormat('text')
				->subject(Configure::read('EMAIL_ENV').PRODUCT_NAME.' Activation Code')
				->to($email)
				->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
				->send();
		}
	}
	/**
	 *
	 * login
	 *
	 * Behaviour : public
	 *
	 * @defination : Method is use to add new admin user and provide specific righs using admin interface
	 *
	 */
	public function login()
	{
		exit("here");
		$this->autoRender = false;
		$this->SetVariables($this->request->data);
		if (!empty($this->request->data)) {
			$customersEntity = $this->Customers->newEntity($this->request->data,['validate' => 'login']);
			$conditions = array(
					"Customers.email" => (isset($this->request->data['Customers']['email'])?$this->request->data['email']:''),
					"Customers.password" =>  Security::hash(Configure::read('Security.salt') . (isset($this->request->data['Customers']['password'])?$this->request->data['Customers']['password']:'')));
			
			$error='';
			$customersEntity = $this->Customers->find('all',array('conditions' => $conditions/*, 'fields' => $fields*/))->toArray();
			if(empty($customersEntity[0]['id'])) {
				$status				= 'error';
				$error				= 'Please check user detail';
			} else { 
				/* Check customer is installer or not */
				$installercnt	= $this->Installers->find('all', array('conditions'=>array('customer_id'=>$customersEntity[0]['id'])))->count();
				$is_installer 	= ($installercnt>0)?'true':'false';
				/* Update Last Login Date */
				$custData 			= $this->Customers->get($customersEntity[0]['id']);
				$custPatchEntity 	= $this->Customers->patchEntity($custData,$this->request->data());
				$custPatchEntity->last_login_date = $this->NOW();
				$this->Customers->save($custPatchEntity);
				/* Send OTP for in active customer */
				if(isset($customersEntity[0]['status']) &&  $customersEntity[0]['status'] == $this->Customers->STATUS_INACTIVE) {
					$activation_code = $this->Customers->GenerateActivationCode($customersEntity[0]['id']);
					$this->SendActivationCodeToCustomer($customersEntity[0]['id'],$activation_code, '', $customersEntity[0]['mobile']);
				}
				/* Send OTP for in active customer */
				$status				= 'ok';
				$this->ApiToken->LoggedInAPIUser($this->ApiToken->token, $customersEntity[0]['id']);
				$this->ApiToken->SetAPIResponse('cus_id', $customersEntity[0]['id']);
				$this->ApiToken->SetAPIResponse('active_status', $customersEntity[0]['status']);
				$this->ApiToken->SetAPIResponse('is_installer', $is_installer);
				$this->ApiToken->SetAPIResponse('name',$customersEntity[0]['name']);
			}				
		} else {
			$status				= 'error';
			$error				= 'Please check user detail';
		}
		$this->ApiToken->SetAPIResponse('msg', $error);
		$this->ApiToken->SetAPIResponse('type', $status);
		echo $this->ApiToken->GenerateAPIResponse();
		exit;	
	}
	public function otpactivate()
	{
		$this->autoRender 	= false;	
		$activation_code	= $this->request->data('otp');
		$cus_id				= $this->ApiToken->customer_id;
		$this->SetVariables($this->request->data);
		$customer			= $this->Customers->GetCustomerByActivationCode($activation_code,$cus_id);
		if(!empty($customer) && $customer[0]['activation_code'] == $activation_code) {
			if($this->Customers->ActivateCustomer($customer)) {
				$this->ApiToken->SetAPIResponse('type', 'ok');
				$this->ApiToken->SetAPIResponse('msg', 'User activated successfully.');
			} else {
				$this->ApiToken->SetAPIResponse('type', 'error');
				$this->ApiToken->SetAPIResponse('msg', 'Something went wrong in activation.');
			}
		} else {
			$this->response->statusCode(403);
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'Invalid activation code.');

		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	public function forgotpass() {
		
		$this->autoRender = false;	
		$email		= $this->request->data('email');
		$customer	= $this->Customers->find('all', array('conditions'=>array('email'=>$email)))->toArray();
		$customer 	= (isset($customer[0]))?$customer[0]:'';
		if(empty($customer)) {
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'User not found.');
		} else {
			$activation_code = $this->Customers->GenerateActivationCode($customer['id']);
			$this->SendActivationCodeToCustomer($customer['id'],$activation_code, $customer['email'], $customer['mobile']);
			$this->ApiToken->LoggedInAPIUser($this->ApiToken->token, $customer['id']);
			$this->ApiToken->SetAPIResponse('type', 'ok');
			$this->ApiToken->SetAPIResponse('msg', 'You will receive new OTP shortly.');
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	public function token() {
		
		$this->autoRender = false;	
			
		$token 		= $this->ApiToken->GenerateNewToken();
		$version 	= $this->request->data('version');
		if($version != CURRENT_VERSION && $version != OLD_VERSION) {
			$this->ApiToken->SetAPIResponse('flag', '0');
			$this->ApiToken->SetAPIResponse('msg', 'Please update to latest version.');
		} else {
			$this->ApiToken->SetAPIResponse('flag', '1');
			$this->ApiToken->SetAPIResponse('msg', '');
		}
		$this->ApiToken->SetAPIResponse('type', 'ok');
		$this->ApiToken->SetAPIResponse('token', $token);
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	private function SendForgotPasswordEmailToCustomer($customer_id, $email, $mobile,$pin)
	{
		if (!empty($customer_id) && SEND_SMS) {
			//Send sms to customer
			$this->Customers->SendSMSForgotPassword($customer_id, $mobile, $pin);
		}
		if(!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) && SEND_EMAIL) {
			//Send email to customer.
			$Email = new Email('default');
			$Email->profile('default');
			$Email->viewVars(array('customer_id' => $customer_id, 'pin' => $pin));
			$Email->template('forgot_password', 'empty')
				->emailFormat('text')
				->subject(Configure::read('EMAIL_ENV').PRODUCT_NAME.' New Generated Password')
				->to($email)
				->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
				->send();
		}
	}
	
	/**
	*
	* changeloginpassword
	*
	* Behaviour : public
	*
	* @defination : Method is use to change the login password.
	*
	*/
	public function changeloginpassword() {
		
		$this->autoRender = false;
		$this->SetVariables($this->request->data);	
		$oldpassword	= $this->request->data('pass');
		$new_password	= $this->request->data('new_pass');
		$cus_id			= $this->ApiToken->customer_id;

		$custData 		= $this->Customers->get($cus_id);
		$old_password 	= Security::hash(Configure::read('Security.salt') . $oldpassword);
		
		if(!empty($custData)) {
			if(isset($oldpassword) && !empty($oldpassword) && !empty($new_password)) {
				if($old_password == $custData['password']) { 
					$custPatchEntity = $this->Customers->patchEntity($custData, $this->request->data);
					$custPatchEntity->password = $new_password;
					$this->Customers->save($custPatchEntity);

					$this->ApiToken->SetAPIResponse('type', 'ok');
					$this->ApiToken->SetAPIResponse('msg', 'Password changed successfully.');
				} else {
					$this->ApiToken->SetAPIResponse('type', 'error');
					$this->ApiToken->SetAPIResponse('msg', 'Current password does not match.');
				}
			} elseif(empty($oldpassword) && !empty($new_password)) {
				$custPatchEntity = $this->Customers->patchEntity($custData, $this->request->data);
				$custPatchEntity->password 	= $new_password;
				$this->Customers->save($custPatchEntity);
				$this->ApiToken->SetAPIResponse('type', 'ok');
				$this->ApiToken->SetAPIResponse('msg', 'Password changed successfully.');
			}
		} else{
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'User not found.');
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	*
	* getcustomerprofile
	*
	* Behaviour : public
	*
	* @defination : Method is used to get the customer profile.
	*
	*/
	public function getcustomerprofile() {

		$this->autoRender = false;
		$this->SetVariables($this->request->data);
		$cus_id		= $this->ApiToken->customer_id;
		$custData 	= array();
		if(!empty($cus_id)) {
			$custData 	= $this->Customers->find('all', array('conditions'=>array('id'=>$cus_id), 'fields'=>array('name','mobile','state')))->toArray();
		}		
		$arrReturn 	= (!empty($custData[0]))?$custData[0]:$custData; 
		$this->ApiToken->SetAPIResponse('type', 'ok');
		$this->ApiToken->SetAPIResponse('result', $arrReturn);
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	*
	* setcustomerprofile
	*
	* Behaviour : public
	*
	* @defination : Method is used to get the customer profile.
	*
	*/
	public function setcustomerprofile() {

		$this->autoRender = false;
		$this->SetVariables($this->request->data);
		
		$cus_id	= $this->ApiToken->customer_id;
		$active_status = '';
		if(!empty($cus_id)) {			
			$custData = $this->Customers->get($cus_id);
			if($this->request->data['Customers']['mobile'] != $custData['mobile']) {
				$active_status = 0;
				/* Generate Activation code and send */
				$activation_code = $this->Customers->GenerateActivationCode($custData['id']);
				$this->SendActivationCodeToCustomer($custData['id'],$activation_code, '', $this->request->data['Customers']['mobile']);
				/* set customer status to in-active */
				$customer_status = $this->Customers->STATUS_INACTIVE;
			} else {
				$active_status = 1;
				$customer_status = $this->Customers->STATUS_ACTIVE;
			}
			$custPatchEntity = $this->Customers->patchEntity($custData, $this->request->data);
			$custPatchEntity->status = $customer_status;
			unset($custPatchEntity->password);
			$this->Customers->save($custPatchEntity);
			
			$this->ApiToken->SetAPIResponse('type', 'ok');
			$this->ApiToken->SetAPIResponse('active_status', $active_status);
			$this->ApiToken->SetAPIResponse('msg', 'Profile updated successfully.');
		} else {
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('active_status', $active_status);
			$this->ApiToken->SetAPIResponse('msg', 'User not found.');
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	*
	* resendotp
	*
	* Behaviour : public
	*
	* @defination : Method is used to resend otp for verification.
	*
	*/
	public function resendotp() {

		$this->autoRender = false;
		$this->SetVariables($this->request->data);
		$cus_id	= $this->ApiToken->customer_id;
		
		if(!empty($cus_id)) {
			$custData 	= $this->Customers->get($cus_id);
			/* Activation code generated and sent SMS and email */
			$activation_code = $this->Customers->GenerateActivationCode($custData['id']);
			$this->SendActivationCodeToCustomer($custData['id'],$activation_code, $custData['email'], $custData['mobile']);
			/* Activation code generated and sent SMS and email */
			$this->ApiToken->SetAPIResponse('type', 'ok');
			$this->ApiToken->SetAPIResponse('msg', 'OTP sent successfully.');
		} else {
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'User not found.');
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	*
	* customeroffer
	*
	* Behaviour : public
	*
	* @defination : Method is used to customerr offer.
	*
	* Author : Khushal Bhalsod
	*/
	public function customeroffer() {

		$this->autoRender = false;
		$offerText = "Free download of Professional Version";
		
		$this->ApiToken->SetAPIResponse('type', 'ok');
		$this->ApiToken->SetAPIResponse('offers', $offerText);
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	
}
