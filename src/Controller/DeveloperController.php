<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Network\Email\Email;
use Cake\Event\Event;
use Hdfc\Hdfc;
use Dompdf\Dompdf;
use Cake\View\View;
use Cake\Utility\Security;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use PDO;

class DeveloperController extends FrontAppController
{

	public function initialize()
	{
		parent::initialize();
		$this->loadModel('Customers');
		$this->loadModel('Members');
		$this->loadModel('Projects');
		$this->loadModel('Parameters');
		$this->loadModel('CheckUserRole');
		$this->loadModel('ApiToken');
		$this->loadModel('PasswordReset');
		$this->loadModel('ChangePassLog');
		$this->loadModel('Installers');
		$this->loadModel('Developers');
		$this->loadModel('DeveloperCustomers');
		$this->loadModel('DeveloperPasswordReset');
		$this->loadModel('DeveloperChangePassLog');
		$this->loadModel('DeveloperPayment');
		$this->loadModel('Developers');
		$this->loadModel('ApplicationStages');
		$this->loadModel('ApplicationCategory');
		//Vishal
		$this->loadModel('MemberRoles');

		if ($this->request->params['action'] == 'change_developer_password' || $this->request->params['action'] == 'update_developer_profile' || $this->request->params['action'] == 'index' || $this->request->params['action'] == 'dashboard') {
			$this->loadComponent('Csrf');
		}
	}

	public function beforeFilter(Event $event)
	{
		parent::beforeFilter($event);
	}

	public function isAuthorized($user)
	{

		// All registered users have access
		if ($this->request->action === 'index') {
			return true;
		}

		// customer can use following actions
		if (isset($user['customer_type']) || isset($user['member_type'])) {
			return true;
		}
		/*if (isset($user['customer_type']) && ($user['customer_type'] === 'installer' || $user['customer_type'] === 'installer_user')) {
            return true;
        }*/

		// Default deny
		return false;
	}

	public function index()
	{
		$this->layout = 'frontend';
		$this->set('page_title', 'GEDA | Unified Single Window Rooftop PV Portal');
		$this->set('projectTypeArr', $this->Parameters->getProjectType());
		$this->set('backupTypeArr', $this->Projects->backupTypeArr);
		$this->set('areaTypeArr', $this->Parameters->getAreaType());
	}

	//Develop by Vishal
	public function dashboard()
	{

		$pageTitle = "Dashboard";
		$Customers  = $this->Session->read('Customers');
		$Members    = $this->Session->read('Members');

		$db = ConnectionManager::get('default');

		if (!isset($Members) && empty($Members)) {
			$customerId = $this->Session->read('Customers.id');
			$user = $this->DeveloperCustomers->get($this->Session->read('Customers.id'));
			$installer_id = $user['installer_id'];
		} else {
			$customerId = 0;
			$installer_id = 0;
		}

		//Vishal
		$member_id 			= $this->Session->read("Members.id");
		$maxRoleOrders = $this->MemberRoles->find()->select(['app_type'])->where(['member_id' => $member_id])->toArray();
		$appTypes = array_map(function ($role) {
			return $role->app_type;
		}, $maxRoleOrders);		

		if (isset($appTypes) && !empty($appTypes)) {			
			$category   = $this->ApplicationCategory->find("all", ['fields' => array('category_name', 'id'), 'conditions'	=> array('id IN' => $appTypes)])->toArray();
		} else {			
			$category   = $this->ApplicationCategory->find("all", ['fields' => array('category_name', 'id'), 'conditions'	=> array('id !=' => 1)])->toArray();
		}
		//Vishal
		
		//$category   = $this->ApplicationCategory->find("all", ['fields' => array('category_name', 'id'), 'conditions'	=> array('id !=' => 1)])->toArray();
		

		$dashboardData = [];
		$categoryData = [];


		if (!isset($Members) && empty($Members)) {

			$dashboardTotal = [
				['total' => 0, 'capacity' => 0, 'title' => 'Total Application Submmited'],
				['total' => 0, 'capacity' => 0, 'title' => 'Total Pending Payment'],
				['total' => 0, 'capacity' => 0, 'title' => 'Total Doc. Verification Pending'],
				['total' => 0, 'capacity' => 0, 'title' => 'Total Provisional Letter'],
				['total' => 0, 'capacity' => 0, 'title' => 'Total Query Raised'],
				['total' => 0, 'capacity' => 0, 'title' => 'Total Query Reply'],
				['total' => 0, 'capacity' => 0, 'title' => 'Total Final Registration Submitted'],
				['total' => 0, 'capacity' => 0, 'title' => 'Total Final Regi. For Approval'],
				['total' => 0, 'capacity' => 0, 'title' => 'Total Final Regi. Approved'],
			];

			foreach ($category as $value) {
				$result = $db->execute("SET @p0=" . $customerId . "; SET @p1=" . $installer_id . "; SET @p2=" . $this->ApplicationStages->APPLICATION_SUBMITTED . "; SET @p3=" . $value['id'] . "; CALL `RE_Data`(@p0, @p1, @p2, @p3, @p4, @p5, @p6, @p7,@p8, @p9, @p10, @p11,@p12,@p13,@p14,@p15,@p16,@p17,@p18,@p19,@p20,@p21); ")->fetchAll('assoc');
				$r = $db->execute("SELECT @p4 AS `Application Submmited`, 
				@p5 AS `Pending Payment`, 
				@p6 AS `Doc. Verification Pending`, 
				@p7 AS `Provisional Letter`,
				@p12 AS `Query Raised`,@p13 AS `Query Reply`,
				@p16 AS `Final Registration Submitted`,@p17 AS `Final Regi For Approval`,@p18 AS `Final Regi Approved`")->fetchAll('assoc');

				$r1 = $db->execute("SELECT @p8 AS `Tot Cap Application Submmited`, 
				@p9 AS `Tot Cap Pending Payment`,
				@p10 AS `Tot Cap Doc. Verification Pending`, 
				@p11 AS `Tot Cap Provisional Letter`,
				@p14 AS `Tot Cap Query Raised`,
				@p15 AS `Tot Cap Query Reply`,@p19 AS `Tot Cap Final Reg Submitted`,
				@p20 AS `Tot Cap Final Reg For Approval`,@p21 AS `Tot Cap Final Reg Approved`;")->fetchAll('assoc');

				array_push($categoryData, $value['id']);
				$dashboardData[$value->category_name][] = $r[0];
				$dashboardData[$value->category_name][] = $r1[0];
				$dashboardTotal[0]['total'] += $r[0]['Application Submmited'];
				$dashboardTotal[0]['capacity'] += $r1[0]['Tot Cap Application Submmited'];
				$dashboardTotal[1]['total'] += $r[0]['Pending Payment'];
				$dashboardTotal[1]['capacity'] += $r1[0]['Tot Cap Pending Payment'];
				$dashboardTotal[2]['total'] += $r[0]['Doc. Verification Pending'];
				$dashboardTotal[2]['capacity'] += $r1[0]['Tot Cap Doc. Verification Pending'];
				$dashboardTotal[3]['total'] += $r[0]['Provisional Letter'];
				$dashboardTotal[3]['capacity'] += $r1[0]['Tot Cap Provisional Letter'];
				$dashboardTotal[4]['total'] += $r[0]['Query Raised'];
				$dashboardTotal[4]['capacity'] += $r1[0]['Tot Cap Query Raised'];
				$dashboardTotal[5]['total'] += $r[0]['Query Reply'];
				$dashboardTotal[5]['capacity'] += $r1[0]['Tot Cap Query Reply'];
				$dashboardTotal[6]['total'] += $r[0]['Final Registration Submitted'];
				$dashboardTotal[6]['capacity'] += $r1[0]['Tot Cap Final Reg Submitted'];
				$dashboardTotal[7]['total'] += $r[0]['Final Regi For Approval'];
				$dashboardTotal[7]['capacity'] += $r1[0]['Tot Cap Final Reg For Approval'];
				$dashboardTotal[8]['total'] += $r[0]['Final Regi Approved'];
				$dashboardTotal[8]['capacity'] += $r1[0]['Tot Cap Final Reg Approved'];
				
			}

			$bgColorClass = array('meter-installed', 'registration', 'verified', 'verification-pending', 'inspection_box', 'msme_box','registration','verification-pending','verified');

			$applicationStatus = [
				$this->ApplicationStages->APPLICATION_SUBMITTED,
				$this->ApplicationStages->APPLICATION_SUBMIT_PAYMENT_PENDING,
				$this->ApplicationStages->DOCUMENT_VERIFICATION_PENDING,
				$this->ApplicationStages->APPROVED_FROM_GEDA,
				$this->ApplicationStages->QUERY_RAISED,
				$this->ApplicationStages->QUERY_REPLY,
				$this->ApplicationStages->DEVELOPER_APPLICATION_SUBMITTED,
				$this->ApplicationStages->DEVELOPER_APPLICATION_FORWARD,
				$this->ApplicationStages->DEVELOPER_APPLICATION_VERIFIED
			];
		} else {
			//discom code
			$area 				= $this->Session->read('Members.area');
			if (!empty($area)) {
				$branchDetails 	= $this->BranchMasters->find('all', array('conditions' => array('discom_id' => $area)))->first();
			}
			$main_branch_id 	= isset($branchDetails->id) ? $branchDetails->id : 0;
			//end discom code


			$dashboardTotal = [
				['total' => 0, 'capacity' => 0, 'title' => 'Total Application Submmited'],
				//['total' => 0, 'capacity' => 0, 'title' => 'Total Pending Payment'],
				['total' => 0, 'capacity' => 0, 'title' => 'Total Doc. Verification Pending'],
				['total' => 0, 'capacity' => 0, 'title' => 'Total Provisional Letter'],
				['total' => 0, 'capacity' => 0, 'title' => 'Total Query Raised'],
				['total' => 0, 'capacity' => 0, 'title' => 'Total Query Reply'],
				['total' => 0, 'capacity' => 0, 'title' => 'Total Final Registration Submitted'],
				['total' => 0, 'capacity' => 0, 'title' => 'Total Final Reg. For Approval'],
				['total' => 0, 'capacity' => 0, 'title' => 'Total Final Reg. Approved'],
			];
			
			foreach ($category as $value) {
				
				$result = $db->execute("SET @p0=" . $customerId . "; SET @p1=" . $installer_id . "; SET @p2=" . $this->ApplicationStages->APPLICATION_SUBMITTED . "; SET @p3=" . $value['id'] . "; CALL `RE_Data`(@p0, @p1, @p2, @p3, @p4, @p5, @p6, @p7,@p8, @p9, @p10, @p11,@p12,@p13,@p14,@p15,@p16,@p17,@p18,@p19,@p20,@p21); ")->fetchAll('assoc');

				$r = $db->execute("SELECT @p4 AS `Application Submmited`, 
				@p6 AS `Doc. Verification Pending`, 
				@p7 AS `Provisional Letter`,
				@p12 AS `Query Raised`,@p13 AS `Query Reply`,
				@p16 AS `Final Registration Submitted`,@p17 AS `Final Regi For Approval`,@p18 AS `Final Regi Approved`")->fetchAll('assoc');

				
				$r1 = $db->execute("SELECT @p8 AS `Tot Cap Application Submmited`, 
				@p10 AS `Tot Cap Doc. Verification Pending`, 
				@p11 AS `Tot Cap Provisional Letter`,
				@p14 AS `Tot Cap Query Raised`,
				@p15 AS `Tot Cap Query Reply`,@p19 AS `Tot Cap Final Reg Submitted`,
				@p20 AS `Tot Cap Final Reg For Approval`,@p21 AS `Tot Cap Final Reg Approved`;")->fetchAll('assoc');
				
				array_push($categoryData, $value['id']);
				$dashboardData[$value->category_name][] = $r[0];
				$dashboardData[$value->category_name][] = $r1[0];
				$dashboardTotal[0]['total'] += $r[0]['Application Submmited'];
				$dashboardTotal[0]['capacity'] += $r1[0]['Tot Cap Application Submmited'];
				$dashboardTotal[1]['total'] += $r[0]['Doc. Verification Pending'];
				$dashboardTotal[1]['capacity'] += $r1[0]['Tot Cap Doc. Verification Pending'];
				$dashboardTotal[2]['total'] += $r[0]['Provisional Letter'];
				$dashboardTotal[2]['capacity'] += $r1[0]['Tot Cap Provisional Letter'];
				$dashboardTotal[3]['total'] += $r[0]['Query Raised'];
				$dashboardTotal[3]['capacity'] += $r1[0]['Tot Cap Query Raised'];
				$dashboardTotal[4]['total'] += $r[0]['Query Reply'];
				$dashboardTotal[4]['capacity'] += $r1[0]['Tot Cap Query Reply'];
				$dashboardTotal[5]['total'] += $r[0]['Final Registration Submitted'];
				$dashboardTotal[5]['capacity'] += $r1[0]['Tot Cap Final Reg Submitted'];
				$dashboardTotal[6]['total'] += $r[0]['Final Regi For Approval'];
				$dashboardTotal[6]['capacity'] += $r1[0]['Tot Cap Final Reg For Approval'];
				$dashboardTotal[7]['total'] += $r[0]['Final Regi Approved'];
				$dashboardTotal[7]['capacity'] += $r1[0]['Tot Cap Final Reg Approved'];
			}
			
			$bgColorClass = array('meter-installed', 'verified', 'verification-pending', 'inspection_box', 'msme_box','registration','verification-pending','verified');

			$applicationStatus = [
				$this->ApplicationStages->APPLICATION_SUBMITTED,
				$this->ApplicationStages->DOCUMENT_VERIFICATION_PENDING,
				$this->ApplicationStages->APPROVED_FROM_GEDA,
				$this->ApplicationStages->QUERY_RAISED,
				$this->ApplicationStages->QUERY_REPLY,
				$this->ApplicationStages->DEVELOPER_APPLICATION_SUBMITTED,
				$this->ApplicationStages->DEVELOPER_APPLICATION_FORWARD,
				$this->ApplicationStages->DEVELOPER_APPLICATION_VERIFIED
			];
		}
		
		$this->set('dashboardData', $dashboardData);
		$this->set('dashboardTotal', $dashboardTotal);
		$this->set('pageTitle', $pageTitle);
		$this->set('bgColorClass', $bgColorClass);
		$this->set("applicationStatus", $applicationStatus);
		$this->set("categoryData", $categoryData);
		if (isset($Members) && !empty($Members)) {
			$this->set("member", 1);
		} else {
			$this->set("member", 0);
		}
	}

	public function change_developer_password($customer_id = null)
	{

		if ($this->request->is('mobile')) {
			$this->autoRender = false;
			$this->SetVariables($this->request->data);
			$oldpassword	= $this->request->data('pass');
			$new_password	= $this->request->data('new_pass');
			$cus_id			= $this->ApiToken->customer_id;
			$user = $this->DeveloperCustomers->get($cus_id);

			$custData 		= $this->DeveloperCustomers->get($cus_id);
		} else {
			$pageTitle 	= "Change Password";
			$user 		= $this->DeveloperCustomers->get($this->Session->read('Customers.id'));
		}

		if (!empty($this->request->data)) {
			$captchaValidation = $this->captchaValidation();


			if ($captchaValidation == '0') {
				$status 			= 'error';
				$error				= 'Incorrect Captcha';
				$this->Flash->error($error);
				return $this->redirect(URL_HTTP . 'developer/change_developer_password');
			} elseif ($captchaValidation == '2') {
				$status 			= 'error';
				$error				= 'Not Validated Captcha';
				$this->Flash->error($error);
				return $this->redirect(URL_HTTP . 'developer/change_developer_password');
			}

			$this->request->data['old_password'] = $this->convert_pass($this->request->data['old_password']);
			$this->request->data['password1'] 	= $this->convert_pass($this->request->data['password1']);
			$this->request->data['password2'] 	= $this->convert_pass($this->request->data['password2']);

			$this->removeExtraTags();

			$user = $this->DeveloperCustomers->patchEntity(
				$user,
				[
					'old_password'  => $this->request->data['old_password'],
					'password'      => $this->request->data['password1'],
					'password1'     => $this->request->data['password1'],
					'password2'     => $this->request->data['password2']
				],
				['validate' => 'password']
			);

			if (empty($user->errors())) {

				if (!empty($user) && $this->DeveloperCustomers->ChangeDeveloperPassword($user, $this->request->data['password1'])) {
					if ($this->request->is('mobile')) {
						$this->ApiToken->SetAPIResponse('type', 'ok');
						$this->ApiToken->SetAPIResponse('msg', 'Password changed successfully.');
					} else {
						$this->Flash->success('The password is successfully changed');
						$this->redirect(URL_HTTP . 'developer/change_developer_password');
					}
				} else {
					if ($this->request->is('mobile')) {
						$this->ApiToken->SetAPIResponse('type', 'error');
						$this->ApiToken->SetAPIResponse('msg', 'User not found.');
					} else {
						$this->Flash->error('There was an error during the save!');
					}
				}
			}
		}
		if (!$this->request->is('mobile')) {
			$this->set(compact('user', 'pageTitle'));
		}
	}

	//Develop by Vishal
	public function update_developer_profile()
	{
		$pageTitle = "Update Profile";
		$customerId = $this->Session->read('Customers.id');
		if (!isset($customerId) || empty($customerId)) {
			return $this->redirect(URL_HTTP . 'home');
		}
		$this->removeExtraTags();
		$user = $this->DeveloperCustomers->find('all')->where(['DeveloperCustomers.id' => $customerId])->first();

		$userEntity = $this->DeveloperCustomers->patchEntity($user, $this->request->data, ['validate' => 'customer']);
		if (!$userEntity->errors() && !empty($this->request->data)) {
			$this->DeveloperCustomers->patchEntity($user, $this->request->data);
			if ($this->DeveloperCustomers->save($user)) {
				$this->Flash->success(__('Your profile has been updated.'));
				return $this->redirect(URL_HTTP . 'developer/update_developer_profile');
			}
			$this->Flash->error(__('Unable to update your profile.'));
		}

		$DeveloperPayment = TableRegistry::get('DeveloperPayment');
		$PaymentReceiptDetails = $DeveloperPayment->find('all', array('conditions' => array('installer_id' => $user['installer_id'], 'payment_status' => 'success')))->toArray(); //2

		$this->set(compact('user', 'pageTitle', 'PaymentReceiptDetails'));
	}

	public function captchaValidation()
	{
		if (CAPTCHA_DISPLAY == 1) {
			if ($this->request->data['g-recaptcha-response'] == "") {
				return 0;
			} else {
				$secret = CAPTCHA_SECRET_KEY; //Configure::read('SECRET_KEY');
				$verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$this->request->data['g-recaptcha-response']}");
				$captcha_success = json_decode($verify);
				if ($captcha_success->success == false) {
					$this->Flash->error('Not Validated Captcha');
					return 0;
				} else if ($captcha_success->success == true) {
					return 1;
				}
			}
		}
		return 1;
	}
	/**
	 *
	 * convert_pass
	 *
	 * Behaviour : Public
	 * @param : pass encrypted password to this function.
	 * @defination : Method is use to decrypt the javascript encoded password
	 *
	 */
	public function convert_pass($subpass)
	{
		if (isset($subpass) && !empty($subpass)) {
			$first = substr($subpass, 10);
			$last = substr($subpass, -10);
			$subpass = base64_decode(str_replace(array($last), array(''), $first));
		}
		return $subpass;
	}

	public function update_developer_package($customerId = null)
	{
		$customerId = $this->Session->read('Customers.id');
		if (!isset($customerId) || empty($customerId)) {
			return $this->redirect(URL_HTTP . 'home');
		}

		if (empty($this->request->data['category']) || !isset($this->request->data['category'])) {
			$this->Flash->error('You must have to select atleast one category', ['key' => 'alert']);
			return $this->redirect(URL_HTTP . 'developer/update_developer_profile');
		}
		$developerCustomer 	= $this->DeveloperCustomers->find('all', array('fields' => array('installer_id'), 'conditions' => array('id' => $customerId)))->first();
		$ApplicationCategoryDetails	 = $this->ApplicationCategory->find('all', array('conditions' => array('status' => 1, 'id in' => $this->request->data['category'])))->toArray();

		if (!empty($ApplicationCategoryDetails)) {
			$payAmount = 0;
			foreach ($ApplicationCategoryDetails as $k => $acm) {
				$payAmount += $acm['developer_charges'];
				$payAmount += ($acm['developer_charges'] * $acm['developer_tax_percentage'] / 100);
			}
		}

		$Developers 			= TableRegistry::get('Developers');
		$exist_developer 		= $Developers->find('all', array('conditions' => array('id' => $developerCustomer['installer_id'])))->toArray();

		if (PAYMENT_METHOD == 'hdfc' && !empty($exist_developer) && isset($exist_developer)) {
			require_once(ROOT . DS . 'vendor' . DS . 'hdfc' . DS . 'hdfc.php');

			$objHdfc 					= new Hdfc(Configure::read('HDFC_MERCHANT_KEY'), Configure::read('HDFC_SALT'), Configure::read('HDFC_ACCESS_CODE'), Configure::read('PAYU_SANDBOX'));
			$txnId 						= $objHdfc->randomTxnId();
			$hdfc['order_id'] 			= $txnId;               //Transaction Id
			$hdfc['redirect_url'] 		= URL_HTTP . 'developer/success'; //Router::url(['controller' => 'ReApplicationPayment','action' => 'success'],TRUE); // Success Url
			$hdfc['cancel_url'] 		= URL_HTTP . 'developer/cancel'; //Router::url(['controller' => 'ReApplicationPayment','action' => 'cancel'],TRUE); 	// Cancel Url
			$hdfc['amount'] 			= isset($payAmount) ? $payAmount : 0; // Amount
			$hdfc['language'] 			= 'EN';
			$hdfc['currency'] 			= 'INR';
			$hdfc['billing_name'] 		= preg_replace('/[^a-z0-9 ]/i', '', $exist_developer[0]->installer_name);
			$hdfc['billing_country'] 	= 'India';
			$hdfc['billing_address'] 	= preg_replace('/[^a-z0-9 ]/i', '', $exist_developer[0]->address);
			$hdfc['billing_city'] 		= preg_replace('/[^a-z0-9 ]/i', '', $exist_developer[0]->city);
			$hdfc['billing_state'] 		= preg_replace('/[^a-z0-9 ]/i', '', $exist_developer[0]->state);
			$hdfc['billing_zip'] 		= $exist_developer[0]->pincode;
			$hdfc['billing_tel'] 		= $exist_developer[0]->mobile;
			$hdfc['delivery_name'] 		= preg_replace('/[^a-z0-9 ]/i', '', $exist_developer[0]->installer_name);
			$hdfc['delivery_country'] 	= 'India';
			$hdfc['delivery_address'] 	= preg_replace('/[^a-z0-9 ]/i', '', $exist_developer[0]->address);
			$hdfc['delivery_city'] 		= preg_replace('/[^a-z0-9 ]/i', '', $exist_developer[0]->city);
			$hdfc['delivery_state'] 	= preg_replace('/[^a-z0-9 ]/i', '', $exist_developer[0]->state);
			$hdfc['delivery_zip'] 		= $exist_developer[0]->pincode;
			$hdfc['delivery_tel'] 		= $exist_developer[0]->mobile;
			$hdfc['merchant_param1'] 	= encode($exist_developer[0]->id);
			$hdfc['merchant_param2'] 	= implode(',', $this->request->data['category']);


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

			//upgrade
			$developerUpt = array();
			$developerUpt['request_for_upgrade'] 	= 1;
			$this->Developers->updateAll($developerUpt, array('id' => $exist_developer[0]->id));
			$objHdfc->send($hdfc);
		} else {
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

		if ($this->request->data) {
			if (PAYMENT_METHOD == 'hdfc') {

				require_once(ROOT . DS . 'vendor' . DS . 'hdfc' . DS . 'hdfc.php');
				$objHdfc 			= new Hdfc(Configure::read('HDFC_MERCHANT_KEY'), Configure::read('HDFC_SALT'), Configure::read('HDFC_ACCESS_CODE'), Configure::read('PAYU_SANDBOX'));
				$arr_data = $objHdfc->decrypt($this->request->data['encResp'], Configure::read('HDFC_SALT'));

				$arr_reponse_data 	= explode("&", $arr_data);
				$arr_pass_data 		= array();
				foreach ($arr_reponse_data as $res_d) {
					$arr_mk_data 					= explode("=", $res_d);
					$arr_pass_data[$arr_mk_data[0]] = $arr_mk_data[1];
				}

				$this->request->data['udf1'] = $arr_pass_data['merchant_param1'];
				if (strtolower($arr_pass_data['order_status']) == 'success') {
					$response = $this->DeveloperPayment->save_upg_pck_data_success($arr_pass_data, 0);
				} else {
					$response = $this->DeveloperPayment->save_upg_pck_data_failure($arr_pass_data, 0);
					$this->Flash->error('Payment failed.');
					return $this->redirect(URL_HTTP . 'developer/update_developer_profile/');
				}
			} else {
				$response = $this->DeveloperPayment->save_upg_pck_data_success($this->request->data, 0);
			}
			if ($response == 1) {
				$this->Flash->success('Payment done successfully.');
				return $this->redirect(URL_HTTP . 'developer/update_developer_profile/');
			} else {
				$this->Flash->success('Payment done successfully.');
				return $this->redirect(URL_HTTP . '');
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
		if ($this->request->data) {
			$response 		= $this->DeveloperPayment->savedata_failure($this->request->data, 0);
			$Error_Message 	= "Error while payment process. Please try again.";
			if ($response) {
				if (isset($this->request->data['error_Message'])) {
					$Error_Message = $this->request->data['error'] . ":" . $this->request->data['error_Message'];
				}
				$this->Flash->error($Error_Message);
				return $this->redirect(URL_HTTP . 'developer/update_developer_profile/');
			} else {
				$this->Flash->error($Error_Message);
				return $this->redirect(URL_HTTP . 'applications-list');
			}
		}
		exit;
	}

	public function cancel()
	{
		if ($this->request->data) {
			$payuTable = TableRegistry::get('DeveloperPayment');
			if (PAYMENT_METHOD == 'hdfc') {
				require_once(ROOT . DS . 'vendor' . DS . 'hdfc' . DS . 'hdfc.php');
				$objHdfc 			= new Hdfc(Configure::read('HDFC_MERCHANT_KEY'), Configure::read('HDFC_SALT'), Configure::read('HDFC_ACCESS_CODE'), Configure::read('PAYU_SANDBOX'));
				$arr_data = $objHdfc->decrypt($this->request->data['encResp'], Configure::read('HDFC_SALT'));

				$arr_reponse_data 	= explode("&", $arr_data);
				$arr_pass_data 		= array();
				foreach ($arr_reponse_data as $res_d) {
					$arr_mk_data 					= explode("=", $res_d);
					$arr_pass_data[$arr_mk_data[0]] = $arr_mk_data[1];
				}
				$payusave = $payuTable->find('all')->where(['payment_id' => $arr_pass_data['order_id']])->first();
			} else {
				$payusave = $payuTable->find('all')->where(['payment_id' => $this->request->data['mihpayid']])->first();
			}


			if (empty($payusave)) {
				$payusave = $payuTable->newEntity();
			}
			if (PAYMENT_METHOD == 'hdfc') {
				$payusave->payment_id 		= $arr_pass_data['order_id'];
				$payusave->payment_status 	= strtolower($arr_pass_data['order_status']);
				$this->request->data['udf1'] = $arr_pass_data['merchant_param1'];
				$payusave->payment_data 	= json_encode($arr_pass_data);
			} else {
				$payusave->payment_id 		= $this->request->data['mihpayid'];
				$payusave->payment_status 	= $this->request->data['status'];
				$payusave->payment_data 	= json_encode($this->request->data);
			}
			if ($payuTable->save($payusave)) {
				$DeveloperPaymentRequest 		= TableRegistry::get('DeveloperPaymentRequest');
				$arrPayment 					= $DeveloperPaymentRequest->find('all', array('conditions' => array('installer_id' => decode($this->request->data['udf1']), 'response_data IS NULL'), 'order' => array('id' => 'desc')))->first();

				if (!empty($arrPayment)) {
					$arrpay['installer_id'] 	= decode($this->request->data['udf1']);
					$arrpay['modified'] 		= $this->NOW();
					$arrpay['response_data']	= json_encode($arr_pass_data);
					$DeveloperPaymentRequest->updateAll($arrpay, array('id' => $arrPayment->id));
				}
				if (isset($this->request->data['udf1'])) {
					return $this->redirect(URL_HTTP . 'developer/update_developer_profile/');
				} else {
					$this->redirect(URL_HTTP . 'applications-list');
				}
			}
		}
		exit;
	}

	public function downloadDeveloperPaymentReceiptPdf($id)
	{

		$ApplyOnlines 						= TableRegistry::get('ApplyOnlines');
		$DeveloperPayment 					= TableRegistry::get('DeveloperPayment');
		$DeveloperSuccessPayment 			= TableRegistry::get('DeveloperSuccessPayment');

		$MembersTable 						= TableRegistry::get('Members');
		$BranchMasters 						= TableRegistry::get('BranchMasters');
		$DiscomMaster 						= TableRegistry::get('DiscomMaster');
		$DeveloperApplicationCategoryMapping = TableRegistry::get('DeveloperApplicationCategoryMapping');
		if (empty($id)) {
			$this->Flash->error('Please Select Valid Installer.');
			return $this->redirect('home');
		} else {
			$encode_id 					= $id;
			$id 						= intval(decode($id));
			//$installer_id 				= $id;
			$payment_data 				= $DeveloperSuccessPayment->find('all', array('conditions' => array('payment_id' => $id)))->first();
			$payment_details 			= $DeveloperPayment->find('all', array('conditions' => array('id' => $id)))->first();
			$InstallersData 			= $this->Developers->find('all', array('conditions' => array('id' => $payment_details->installer_id)))->first();
		}
		$view = new View();
		$view->layout 				= false;
		$mapDetails 				= $DeveloperApplicationCategoryMapping->find(
			'all',
			array(
				'fields' 	=> array('DeveloperApplicationCategoryMapping.developer_fee', 'DeveloperApplicationCategoryMapping.gst_fees', 'application_category.category_name'),
				'join'		=> ['application_category' => [
					'table' => 'application_category', 'type' => 'left',
					'conditions' => 'application_category.id=DeveloperApplicationCategoryMapping.application_category_id'
				]],
				'conditions' => array('payment_success_id' => $payment_data->id)
			)
		)->toArray();
		$arrMapCategory = array();
		$gst 			= 0;

		if (!empty($mapDetails)) {

			foreach ($mapDetails as $arrDetails) {
				$arrMapCategory[$arrDetails->application_category['category_name']] =  $arrDetails->developer_fee;
				$gst = $gst + $arrDetails->gst_fees;
			}
		}

		$view->set("pageTitle", "Developer Payment Receipt");
		$view->set('payment_data', $payment_data);
		$view->set('InstallersData', $InstallersData);
		$view->set('payment_details', $payment_details);
		$view->set('arrMapCategory', $arrMapCategory);
		$view->set('gst', $gst);

		/* Generate PDF for estimation of project */
		require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
		$dompdf = new Dompdf($options = array());
		$dompdf->set_option("isPhpEnabled", true);
		$view->set('dompdf', $dompdf);
		$dompdf->set_base_path(SITE_ROOT_DIR_PATH . '/pdf/');

		$html = $view->render('/Element/upg_developer_payment_receipt');
		$dompdf->loadHtml($html, 'UTF-8');
		$dompdf->setPaper('A4', 'portrait');
		@$dompdf->render();
		//$dompdf->stream('paymentreceipt-' . $id);
		$output = @$dompdf->output();
		header("Content-type:application/pdf");
		header("Content-Disposition:inline;filename=" . $id . ".pdf");
		echo $output;

		die;
	}
}
