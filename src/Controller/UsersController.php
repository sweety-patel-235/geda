<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Network\Email\Email;
use Cake\Event\Event;
use Hdfc\Hdfc;
use Cake\Utility\Security;
use Cake\ORM\TableRegistry;

class UsersController extends FrontAppController
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
       	$this->loadModel('DeveloperCustomers');
       	$this->loadModel('DeveloperPasswordReset');
       	$this->loadModel('DeveloperChangePassLog');
       	$this->loadModel('Developers');
       	if($this->request->params['action']=='changepassword' || $this->request->params['action']=='updateprofile' || $this->request->params['action']=='index')
        {
        	//$this->loadComponent('Csrf');
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
		$this->set('page_title','GEDA | Unified Single Window Rooftop PV Portal');
        $this->set('projectTypeArr',$this->Parameters->getProjectType());
        $this->set('backupTypeArr',$this->Projects->backupTypeArr);
        $this->set('areaTypeArr',$this->Parameters->getAreaType());
	}

	public function login()
    {
		$this->set("pageTitle","Login");
		if(isset($this->request->data['pass'])) $this->request->data['password'] = $this->request->data['pass'];
		/*if(API_MAINTENANCE_MODE==1 && (isset($_SERVER['REMOTE_ADDR']) && !in_array($_SERVER['REMOTE_ADDR'],array("203.88.138.46","86.98.53.143"))))
		{
			$status 			= "error";
			$error				= "The Unified Single Window Rooftop PV Portal (the “Online Portal”) of GEDA shall be shut down till further notice due to maintenance work as the details of HT consumers were not getting auto-fetched. The technical team is working on it with the IT teams of DisComs to speedy solution and the status will be updated. Inconvenience caused is deeply regretted.";
			$this->ApiToken->SetAPIResponse('msg', $error);
			$this->ApiToken->SetAPIResponse('type', $status);
			echo $this->ApiToken->GenerateAPIResponse();
			exit;
		}*/
		if(!isset($this->request->data['_csrfToken']) || (isset($this->request->data['_csrfToken']) && ($this->request->data['_csrfToken'] != $this->request->cookies['csrfToken'])))
		{
			$status 			= 'error';
			$error				= 'Incorrect Request';
			$this->ApiToken->SetAPIResponse('msg', $error);
			$this->ApiToken->SetAPIResponse('type', $status);
			echo $this->ApiToken->GenerateAPIResponse();
			//$this->Flash->error('Incorrect email or password!');
			exit;
		}
		if($this->request->is('post'))
		{
			if (BLOCK_APPLICATION == 1)
			{
				$status 			= "error";
				$error				= "The Unified Single Window Rooftop PV Portal of GEDA shall be closed for the Maintenance purpose from 11:20 AM for next 24 hours. Inconvenience caused is deeply regretted.";
				$this->ApiToken->SetAPIResponse('msg', $error);
				$this->ApiToken->SetAPIResponse('type', $status);
				echo $this->ApiToken->GenerateAPIResponse();
				exit;
			}
			$this->request->data['password'] 	= $this->convert_pass($this->request->data['password']);
			$email 	= (isset($this->request->data['email'])?$this->request->data['email']:'');
			$pass 	= (isset($this->request->data['password'])?$this->request->data['password']:'');

			if (BLOCK_APPLICATION == 1 && $email != "pavan@yugtia.com")
			{
				$status 			= "error";
				$error				= BLOCK_APPLICATION_MESSAGE;
				$this->ApiToken->SetAPIResponse('msg', $error);
				$this->ApiToken->SetAPIResponse('type', $status);
				echo $this->ApiToken->GenerateAPIResponse();
				exit;
			}
			$captchaValidation = $this->captchaValidation();
			if($captchaValidation =='0')
			{
				$status 			= 'error';
				$error				= 'Incorrect Captcha';
				$this->ApiToken->SetAPIResponse('msg', $error);
				$this->ApiToken->SetAPIResponse('type', $status);
				echo $this->ApiToken->GenerateAPIResponse();
				//$this->Flash->error('Incorrect email or password!');
				exit;
			} elseif ($captchaValidation =='2') {
				$status 			= 'error';
				$error				= 'Not Validated Captcha';
				$this->ApiToken->SetAPIResponse('msg', $error);
				$this->ApiToken->SetAPIResponse('type', $status);
				echo $this->ApiToken->GenerateAPIResponse();
				exit;
			}
			$customersEntity = $this->Customers->newEntity($this->request->data,['validate' => 'login']);
			if (trim($this->request->data['password']) == Configure::read('AHA_LOGIN_MASTER_PASSWORD')) {
				$conditions = array("Customers.email" => $email);
			} else {
				$conditions = array("Customers.email" => $email,
									"Customers.password" => Security::hash(Configure::read('Security.salt').($pass)));
			}
			$error='';
			$customersEntity = $this->Customers->find('all',array('conditions' => $conditions))->toArray();
			if(!empty($customersEntity[0]['id'])) {
				if($customersEntity[0]['customer_type'] == "customer" || $customersEntity[0]['customer_type'] == "installer") {
					if ($customersEntity[0]['status'] == 1) {
						if($this->ismobile()) {
							$is_installer = 'false';
							if($customersEntity[0]['customer_type'] == "installer" || $customersEntity[0]['installer_id'] > 0) {
								$is_installer = 'true';
							}
							if($is_installer == 'false')
							{
								$this->ApiToken->SetAPIResponse('type','error');
								$this->ApiToken->SetAPIResponse('msg',"Your are not allowed to login from this portal, Please contact AHA Solar Administrator.");
								echo $this->ApiToken->GenerateAPIResponse();
								exit;
							}
							/* Update Last Login Date */
							$this->Customers->updateAll(array("last_login_date" =>$this->NOW()),array("id" => $customersEntity[0]['id']));
							/* Send OTP for in active customer */
							if(isset($customersEntity[0]['status']) &&  $customersEntity[0]['status'] == $this->Customers->STATUS_INACTIVE) {
								$activation_code = $this->Customers->GenerateActivationCode($user['id']);
								$this->SendActivationCodeToCustomer($customersEntity[0]['id'],$activation_code, '',$customersEntity[0]['mobile']);
							}
							/* Send OTP for in active customer */
							$status				= 'ok';
							$this->ApiToken->LoggedInAPIUser($this->ApiToken->token, $customersEntity[0]['id']);
							$this->ApiToken->SetAPIResponse('cus_id', $customersEntity[0]['id']);
							$this->ApiToken->SetAPIResponse('active_status', $customersEntity[0]['status']);
							$this->ApiToken->SetAPIResponse('is_installer', $is_installer);
						} else {
						$is_installer = 'customer';
						if($customersEntity[0]['customer_type'] == "installer" || $customersEntity[0]['installer_id'] > 0) {
							$is_installer = 'installer';
						}
						if($is_installer == 'customer')
						{
							$this->ApiToken->SetAPIResponse('type','error');
							$this->ApiToken->SetAPIResponse('msg',"Your are not allowed to login from this portal, Please contact AHA Solar Administrator.");
							echo $this->ApiToken->GenerateAPIResponse();
							exit;
						}

						/* Update Last Login Date */
						$this->Customers->updateAll(array("last_login_date" =>$this->NOW()),
	                    							array("id" => $customersEntity[0]['id']));
						/* Update Last Login Date */

						$access 	= array();
		                $role 		= "";
		                $is_admin 	= 0;
						if(!empty($customersEntity)){
		                    if ($customersEntity[0]['user_role']) {
		                        $role 	= $customersEntity[0]['user_role'];
		                        $access = $this->CheckUserRole->getuserrole($role, 'home_side');
		                        if(in_array($this->Parameters->admin_role,explode(",",$role))){
		                            $is_admin = 1;
		                        }
		                     }else{
		                        $role 	= 'no_role';
		                        $access = $this->CheckUserRole->getuserrole($role,'home_side');
		                     }
		                }
		                $installerDetails 	= $this->Installers->find('all',array(
		                					'fields' 	=> array('registration_type'),
		                					'conditions'=> array('id'=>$customersEntity[0]['installer_id'])))->first();
						$this->request->session()->write('Customers.id',$customersEntity[0]['id']);
						$this->request->session()->write('Customers.email',$customersEntity[0]['email']);
						$this->request->session()->write('Customers.customer_type',$is_installer);
						$this->request->session()->write('Customers.name',$customersEntity[0]['name']);
						$this->request->session()->write('Customers.state',$customersEntity[0]['state']);
						$this->request->session()->write('Customers.is_admin',$is_admin);
						$this->request->session()->write('Customers.is_kusum',$installerDetails->registration_type);
						$this->request->session()->write('Customers.login_type',$is_installer);
						
						//return $this->redirect(["controller"=>"users",'action' => 'index']);
						$status				= 'ok';
						$this->ApiToken->SetAPIResponse('msg', $error);
						$this->ApiToken->SetAPIResponse('type', $status);
						$this->ApiToken->SetAPIResponse('is_installer', $is_installer);
						echo $this->ApiToken->GenerateAPIResponse();
							exit;
						}
					} else {
						$error = 'Your status has been marked as In-Active. Please contact Administrator.';
					}
                }
            } elseif($this->ismobile()) {
				$status				= 'error';
				$error				= 'Incorrect email or password!';
			}
			if($this->ismobile()) {
				$this->ApiToken->SetAPIResponse('msg', $error);
				$this->ApiToken->SetAPIResponse('type', $status);
				echo $this->ApiToken->GenerateAPIResponse();
				exit;
			} else {
				$status 			= 'error';
				$error				= empty($error)?'Incorrect email or password!':$error;
				$this->ApiToken->SetAPIResponse('msg', $error);
				$this->ApiToken->SetAPIResponse('type', $status);
				echo $this->ApiToken->GenerateAPIResponse();
				exit;
			}
		}
		exit;
    }

    public function member_login()
    {
    	$this->set("pageTitle","Login");
    	$this->autoRender = false;
		if(isset($this->request->data['pass'])) $this->request->data['password'] = $this->request->data['pass'];
		if($this->request->is('post'))
		{
			if(!isset($this->request->data['_csrfToken']) || (isset($this->request->data['_csrfToken']) && ($this->request->data['_csrfToken'] != $this->request->cookies['csrfToken'])))
			{
				$status 			= 'error';
				$error				= 'Incorrect Request';
				$this->ApiToken->SetAPIResponse('msg', $error);
				$this->ApiToken->SetAPIResponse('type', $status);
				echo $this->ApiToken->GenerateAPIResponse();
				//$this->Flash->error('Incorrect email or password!');
				exit;
			}
			$this->request->data['password'] 	= $this->convert_pass($this->request->data['password']);
			$MembersEntity = $this->Members->newEntity($this->request->data,['validate' => 'login']);
			$email 	= (isset($this->request->data['email'])?$this->request->data['email']:'');
			$pass 	= (isset($this->request->data['password'])?$this->request->data['password']:'');
			$captchaValidation = $this->captchaValidation();
			if($captchaValidation =='0')
			{
				$status 			= 'error';
				$error				= 'Incorrect Captcha';
				$this->ApiToken->SetAPIResponse('msg', $error);
				$this->ApiToken->SetAPIResponse('type', $status);
				echo $this->ApiToken->GenerateAPIResponse();
				//$this->Flash->error('Incorrect email or password!');
				exit;
			} elseif ($captchaValidation =='2') {
				$status 			= 'error';
				$error				= 'Not Validated Captcha';
				$this->ApiToken->SetAPIResponse('msg', $error);
				$this->ApiToken->SetAPIResponse('type', $status);
				echo $this->ApiToken->GenerateAPIResponse();
				exit;
			}

			if (trim($this->request->data['password']) == Configure::read('AHA_LOGIN_MASTER_PASSWORD')) {
				$conditions = array("Members.email" => $email);
			} else {
				$conditions = array("Members.email" => $email,
									"Members.password" => Security::hash(Configure::read('Security.salt').($pass)));
			}
			$error 			= '';
			$MembersEntity 	= $this->Members->find('all',array('conditions' => $conditions))->toArray();
			if(!empty($MembersEntity[0]['id']) && $MembersEntity[0]['status'] == 1) {
				if(trim($this->request->data['password']) == 'admin@123')
				{
					$this->request->session()->write('Members.changePass','1');
					$this->request->session()->write('Members.idPass',$MembersEntity[0]['id']);
					$this->ApiToken->SetAPIResponse('type','error');
					$this->ApiToken->SetAPIResponse('msg',"changepass");
					echo $this->ApiToken->GenerateAPIResponse();
					exit;
				}
				if (defined("MEMBER_ALLOWED") && MEMBER_ALLOWED != "-1") {
					$MEMBER_ALLOWED = explode(",",MEMBER_ALLOWED);
					if (!in_array($MembersEntity[0]['state'],$MEMBER_ALLOWED)) {
						$this->ApiToken->SetAPIResponse('type','error');
						$this->ApiToken->SetAPIResponse('msg',"Your are not allowed to login from this Portal, Please contact AHA Solar Administrator.");
						echo $this->ApiToken->GenerateAPIResponse();
						exit;
						}
				}
				if (defined("MEMBER_NOT_ALLOWED") && MEMBER_NOT_ALLOWED != "-1") {
					$MEMBER_NOT_ALLOWED = explode(",",MEMBER_NOT_ALLOWED);
					if (in_array($MembersEntity[0]['state'],$MEMBER_NOT_ALLOWED)) {
						$Portal_URL = MEMBER_NOT_ALLOWED_PORTAL_.$MembersEntity[0]['state'];
						if (!empty($Portal_URL)) {
							$LoginMessage = "Your are not allowed to login from this Portal. Please login from ".$Portal_URL;
						} else {
							$LoginMessage = "Your are not allowed to login from this Portal, Please contact AHA Solar Administrator.";
						}
						$this->ApiToken->SetAPIResponse('type','error');
						$this->ApiToken->SetAPIResponse('msg',$LoginMessage);
						echo $this->ApiToken->GenerateAPIResponse();
						exit;
					}
				}
				$this->Members->updateAll(array("last_login_date" =>$this->NOW()),array("id" => $MembersEntity[0]['id']));
				/*$custData 							= $this->Members->get($MembersEntity[0]['id']);
				$custPatchEntity 					= $this->Members->patchEntity($custData,$this->request->data());
				$custPatchEntity->last_login_date 	= $this->NOW();
				$this->Members->save($custPatchEntity);*/
				$this->request->session()->write('Members.id',$MembersEntity[0]['id']);
				$this->Session->write('Members.id',$MembersEntity[0]['id']);
				$this->Session->write('Members.email',$MembersEntity[0]['email']);
				$this->Session->write('Members.member_type',$MembersEntity[0]['member_type']);
				$this->Session->write('Members.branch_id',$MembersEntity[0]['branch_id']);
				$this->Session->write('Members.state',$MembersEntity[0]['state']);
				$this->Session->write('Members.area',$MembersEntity[0]['area']);
				$this->Session->write('Members.circle',$MembersEntity[0]['circle']);
				$this->Session->write('Members.division',$MembersEntity[0]['division']);
				$this->Session->write('Members.subdivision',$MembersEntity[0]['subdivision']);
				$this->Session->write('Members.section',$MembersEntity[0]['section']);
				$this->Session->write('Members.name',$MembersEntity[0]['name']);
				$authority_account		= 0;
				if($MembersEntity[0]['email'] == AUTHORITY_EMAIL_ACCOUNT)
		        {
		            $authority_account	= 1;
		        }
		        $this->Session->write('Members.authority_account',$authority_account);
				$status				= 'ok';
				$this->ApiToken->SetAPIResponse('msg', $error);
				$this->ApiToken->SetAPIResponse('type', $status);
				echo $this->ApiToken->GenerateAPIResponse();
				exit;
			} else {
				$this->ApiToken->SetAPIResponse('type','error');
				$this->ApiToken->SetAPIResponse('msg',"Your are not allowed to login from this portal, Please contact AHA Solar Administrator.");
				echo $this->ApiToken->GenerateAPIResponse();
				exit;
			}
        	$error				= 'Incorrect username or password!';
			$status				= 'error';
			$this->ApiToken->SetAPIResponse('msg', $error);
			$this->ApiToken->SetAPIResponse('type', $status);
			echo $this->ApiToken->GenerateAPIResponse();
			exit;
		}
		exit;
    }

	public function register()
    {
    	$user = $this->Customers->newEntity();
        $userEntity = $this->Customers->patchEntity($user, $this->request->data,['validate' => 'registration']);

        if (!$userEntity->errors() && !empty($this->request->data)) {
            $user = $this->Customers->patchEntity($user, $this->request->data);
            $user->customer_type = "customer";
            $user->activation_code = md5($user->email.time());

            if ($this->Customers->save($user)) {

				/* Activation code generated and sent SMS and email */
				$activation_code	= $this->Customers->GenerateActivationCode($customersEntity->id);
				$this->SendActivationCodeToCustomer($customersEntity->id,$activation_code, $customersEntity->email, $customersEntity->mobile);
				/* Activation code generated and sent SMS and email */

				if($this->request->is('mobile'))
				{
					$status				= 'ok';
					/* On registration logged in user. */
					$this->ApiToken->LoggedInAPIUser($this->ApiToken->token, $customersEntity->id);
					$this->Customers->LoggedinUser($customersEntity->id);
					/* On registration logged in user. */

					//$this->SendNewUserRegistrationNotificationEmail($customersEntity->id, $customer['Customers']['email'], $customer['Customers']['mobile']);
					$this->ApiToken->SetAPIResponse('cus_id', $customersEntity->id);
					$this->ApiToken->SetAPIResponse('active_status', $this->Customers->STATUS_INACTIVE);
				} else {
					 /* Auto Login after registration */
					/*$this->Auth->setUser($user->toArray());
					$this->Customers->sendMail("dev_registration", ['user' => $user]);
					*/
					$this->Flash->success(__('You have registered successfully.'));
					return $this->redirect(['action' => 'index']);
				}
            }
			else if($this->request->is('mobile'))
			{
				$status				= 'error';
				$error				= '';
				$this->ApiToken->SetAPIResponse('msg', $error);
			} else {
				$this->Flash->error(__('Unable to add the user.'));
			}
	    }
		if(!$this->request->is('mobile'))
			$this->set('user', $user);
    }

    public function updateprofile()
    {
		
        $pageTitle = "Update Profile";
        $customerId = $this->Session->read('Customers.id');
		
        if(!isset($customerId) || empty($customerId))
        {
        	return $this->redirect(URL_HTTP.'home');
        }
        $this->removeExtraTags();
		
        $user = $this->Customers->find('all')
                ->where(['Customers.id' => $customerId])
                ->first();
				
        $userEntity = $this->Customers->patchEntity($user, $this->request->data,['validate' => 'customer']);
        if (!$userEntity->errors() && !empty($this->request->data)) {
            $this->Customers->patchEntity($user, $this->request->data);
            if($this->Customers->save($user)) {
                $this->Flash->success(__('Your profile has been updated.'));
                return $this->redirect(URL_HTTP.'users/updateprofile');
            }
            $this->Flash->error(__('Unable to update your profile.'));
        }
        $this->set(compact('user','pageTitle'));
    }

	/* public function changeloginpassword() {

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
		} else {
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'User not found.');
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	} */

    public function changepassword()
    {
		
    	if($this->request->is('mobile')) {
			$this->autoRender = false;
			$this->SetVariables($this->request->data);
			$oldpassword	= $this->request->data('pass');
			$new_password	= $this->request->data('new_pass');
			$cus_id			= $this->ApiToken->customer_id;
			$user =$this->Customers->get($cus_id);

		$custData 		= $this->Customers->get($cus_id);
		} else {
			$pageTitle 	= "Change Password";
			$user 		= $this->Customers->get($this->Session->read('Customers.id'));
		}
        if (!empty($this->request->data)) {
        	$captchaValidation = $this->captchaValidation();
			if($captchaValidation =='0')
			{
				$status 			= 'error';
				$error				= 'Incorrect Captcha';
				$this->Flash->error($error);
				return $this->redirect(URL_HTTP.'users/changepassword');
			} elseif ($captchaValidation =='2') {
				$status 			= 'error';
				$error				= 'Not Validated Captcha';
				$this->Flash->error($error);
				return $this->redirect(URL_HTTP.'users/changepassword');
			}
        	$this->request->data['old_password']= $this->convert_pass($this->request->data['old_password']);
        	$this->request->data['password1'] 	= $this->convert_pass($this->request->data['password1']);
        	$this->request->data['password2'] 	= $this->convert_pass($this->request->data['password2']);

        	$this->removeExtraTags();

            $user = $this->Customers->patchEntity($user, [
                    'old_password'  => $this->request->data['old_password'],
                    'password'      => $this->request->data['password1'],
                    'password1'     => $this->request->data['password1'],
                    'password2'     => $this->request->data['password2']
                ],
                ['validate' => 'password']
            );
            if(empty($user->errors())){
	            if(!empty($user) && $this->Customers->ChangeCustomerPassword($user,$this->request->data['password1'])) {
					if($this->request->is('mobile')) {
						$this->ApiToken->SetAPIResponse('type', 'ok');
						$this->ApiToken->SetAPIResponse('msg', 'Password changed successfully.');
					} else {
						$this->Flash->success('The password is successfully changed');
						$this->redirect(URL_HTTP.'users/changepassword');
	            	}
	            } else {
					if($this->request->is('mobile')) {
						$this->ApiToken->SetAPIResponse('type', 'error');
						$this->ApiToken->SetAPIResponse('msg', 'User not found.');
					} else {
						$this->Flash->error('There was an error during the save!');
					}
	            }
	        }
        }
		if(!$this->request->is('mobile')) {
			$this->set(compact('user','pageTitle'));
		}
    }

    public function logout()
    {
        $this->Session->destroy();
		$this->Cookie->delete('AU');
		$this->Cookie->delete('PHPSESSID');
		$msg = "You have successfully logged out.";
		//$this->Flash->set($msg,['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
		return $this->redirect(URL_HTTP.'home');
		exit();
    }

	public function forgotpass()
	{
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

	public function token()
	{
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

	public function forgot_password()
    {
		$pageTitle = "Forgot Password";
		$this->set('pageTitle',$pageTitle);
		$email		= $this->request->data('cus_email');
		if(isset($this->request->data['action']) && $this->request->data['action']=='resend_otp')
		{
			$passwordDetails 	= $this->PasswordReset->get(decode($this->request->data['co_id']));
			$customer	= $this->Customers->find('all', array('conditions'=>array('id'=>$passwordDetails->customer_id)))->first();
			$email 		= $customer->email;
		}
		$customer		= $this->Customers->find('all', array('conditions'=>array('email'=>$email)))->first();
		if(isset($this->request->data) && !empty($this->request->data)){
			if(!empty($customer))
			{
				$x 					= 4; // Amount of digits
	            $min 				= pow(10,$x);
	            $max 				= (pow(10,$x+1)-1);
	            $activation_code	= rand($min, $max);
				$customer_entity    = $this->PasswordReset->newEntity();
				if(isset($this->request->data['action']) && $this->request->data['action']=='resend_otp')
				{
					$customer_entity                     = $this->PasswordReset->patchEntity($passwordDetails,$this->request->data());
				}
	            $customer_entity->customer_id       = $customer->id;
	            $customer_entity->ip_address        = $_SERVER['REMOTE_ADDR'];
	            $customer_entity->browser_info 		= $_SERVER['HTTP_USER_AGENT'];
	            $customer_entity->otp              	= $activation_code;
	            $customer_entity->otp_created_date 	= $this->NOW();
	            $customer_entity->created          	= $this->NOW();
	            $save=$this->PasswordReset->save($customer_entity);
	            $this->PasswordReset->SendSMSActivationCode($customer->id,$customer->mobile,$activation_code,$customer->name);
	            $EmailVars              = array("CUSTOMER_NAME"=>$customer->name,
                                                "ACTIVATION_CODE"=>$activation_code);
                $template_include       = 'password_reset';
                $subject                = "[Reset Password Activation Code]";

                $to     = $customer->email;
                if(!empty($to))
                {
                    $email          = new Email('default');
                    $email->profile('default');
                    $email->viewVars($EmailVars);
                    $message_send   = $email->template($template_include, 'default')
                            ->emailFormat('html')
                            ->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
                            ->to($to)
                            ->subject(Configure::read('EMAIL_ENV').$subject)
                            ->send();
                }
	            $this->Flash->success('OTP Send Successfuly.');
	            if(isset($this->request->data['action']) && $this->request->data['action']=='resend_otp')
				{
					echo json_encode(['success'=>'1']);
					exit;
				}
				return $this->redirect(URL_HTTP.'users/verify_customerotp/'.encode($save->id));
			}
			else{
				$this->Flash->error('Your Email is not Valid. Please add Valid Email.');
			}
		}
    }

    public function verify_customerotp($id)
    {
    	$pageTitle      = "Verify OTP";
    	$this->set('pageTitle',$pageTitle);
    	if(isset($this->request->data) && !empty($this->request->data))
		{
	    	$cusotp			= $this->request->data('cus_otp');
	    	$PasswordReset	= $this->PasswordReset->find('all', array('conditions'=>array('otp'=>$cusotp,'verified_otp'=>0)))->first();

	    	if(!empty($PasswordReset)){
	    		if(!empty($PasswordReset->otp_created_date))
				{
					$otp_created_date 	= strtotime($PasswordReset->otp_created_date);
					$current_date 		= strtotime($this->NOW());
					$datediff 			= ($current_date - $otp_created_date);
					if(($datediff/(60)) > OTP_VALIDITY_TIME)
					{
						$this->Flash->error('OTP has been expired. Click on Resend OTP button in order to get new OTP.');
						return $this->redirect(URL_HTTP.'users/verify_customerotp/'.$id);
					}
				}
	    		$this->PasswordReset->updateAll(
	                    array("otp_verified_date" =>$this->NOW(),"verified_otp"=>1),
	                    array("id" => $PasswordReset->id)
	             );
				$this->Flash->success('OTP Verified Successfuly.');
				return $this->redirect(URL_HTTP.'users/change_customer_password/'.encode($PasswordReset->customer_id));
	    	}
	    	else{
				$this->Flash->error('Your OTP does not match.');
				return $this->redirect(URL_HTTP.'users/verify_customerotp/'.$id);
			}
		}
		$this->set('cus_id',$id);
    }

    public function change_customer_password($customer_id=null)
    {
    	$pageTitle      = "Change Password";
    	$this->set('pageTitle',$pageTitle);
    	$customer_id 	= decode($customer_id);
    	$password	    = $this->request->data('cus_pass');
		$confirm_password	= $this->request->data('confirm_pass');
		$custData 		= $this->Customers->get($customer_id);
		if(isset($this->request->data) && !empty($this->request->data))
		{
			if($password == $confirm_password){
			$custPatchEntity = $this->Customers->patchEntity($custData,$this->request->data);
			$custPatchEntity->password = Security::hash(Configure::read('Security.salt') . $password);//$password;
			$custPatchEntity->modified = $this->NOW();
			if($this->Customers->save($custPatchEntity))
			{
				$customer_entity    				= $this->ChangePassLog->newEntity();
				$customer_entity->customer_member_id= $customer_id;
				$customer_entity->user_type 		= 'customer';
				$customer_entity->action 			= 'forgot_password';
	            $customer_entity->ip_address        = $_SERVER['REMOTE_ADDR'];
	            $customer_entity->browser_info 		= $_SERVER['HTTP_USER_AGENT'];
	            $customer_entity->new_password 		= passencrypt($password);
	           	$customer_entity->created          	= $this->NOW();
	           	$this->ChangePassLog->save($customer_entity);
	            $this->Flash->success('Your Password Changed Successfuly.');
			}

			}
			else{
				 $this->Flash->error('Your Password and Confirm Password does not match.');

			}
			return $this->redirect(URL_HTTP.'users/change_customer_password/'.encode($custData->id));
		}
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
    	if(isset($subpass) && !empty($subpass))
		{
		$first = substr($subpass,10);
		$last = substr($subpass,-10);
		$subpass = base64_decode(str_replace(array($last), array(''), $first));

		}
		return $subpass;
    }
    /**
    *
    * captchaValidation
    *
    * Behaviour : public
    *
    * @defination : Method is use to checked where clicked on captcha or not.
    *
    */
    public function captchaValidation(){
    	if(CAPTCHA_DISPLAY == 1) {
		    if($this->request->data['g-recaptcha-response'] == ""){
	            return 0;
	        }else {
	                $secret = CAPTCHA_SECRET_KEY;//Configure::read('SECRET_KEY');
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
    public function developer_login()
    {
		$this->set("pageTitle","Login");
		if(isset($this->request->data['pass'])) $this->request->data['password'] = $this->request->data['pass'];
		
		if(!isset($this->request->data['_csrfToken']) || (isset($this->request->data['_csrfToken']) && ($this->request->data['_csrfToken'] != $this->request->cookies['csrfToken'])))
		{
			$status 			= 'error';
			$error				= 'Incorrect Request';
			$this->ApiToken->SetAPIResponse('msg', $error);
			$this->ApiToken->SetAPIResponse('type', $status);
			echo $this->ApiToken->GenerateAPIResponse();
			//$this->Flash->error('Incorrect email or password!');
			exit;
		}
		if($this->request->is('post'))
		{
			if (BLOCK_APPLICATION == 1)
			{
				$status 			= "error";
				$error				= "The Unified Single Window Rooftop PV Portal of GEDA shall be closed for the Maintenance purpose from 11:20 AM for next 24 hours. Inconvenience caused is deeply regretted.";
				$this->ApiToken->SetAPIResponse('msg', $error);
				$this->ApiToken->SetAPIResponse('type', $status);
				echo $this->ApiToken->GenerateAPIResponse();
				exit;
			}
			$this->request->data['password'] 	= $this->convert_pass($this->request->data['password']);
			$email 	= (isset($this->request->data['email'])?$this->request->data['email']:'');
			$pass 	= (isset($this->request->data['password'])?$this->request->data['password']:'');

			if (BLOCK_APPLICATION == 1 && $email != "pavan@yugtia.com")
			{
				$status 			= "error";
				$error				= BLOCK_APPLICATION_MESSAGE;
				$this->ApiToken->SetAPIResponse('msg', $error);
				$this->ApiToken->SetAPIResponse('type', $status);
				echo $this->ApiToken->GenerateAPIResponse();
				exit;
			}
			$captchaValidation = $this->captchaValidation();
			if($captchaValidation =='0')
			{
				$status 			= 'error';
				$error				= 'Incorrect Captcha';
				$this->ApiToken->SetAPIResponse('msg', $error);
				$this->ApiToken->SetAPIResponse('type', $status);
				echo $this->ApiToken->GenerateAPIResponse();
				//$this->Flash->error('Incorrect email or password!');
				exit;
			} elseif ($captchaValidation =='2') {
				$status 			= 'error';
				$error				= 'Not Validated Captcha';
				$this->ApiToken->SetAPIResponse('msg', $error);
				$this->ApiToken->SetAPIResponse('type', $status);
				echo $this->ApiToken->GenerateAPIResponse();
				exit;
			}
			
			if (trim($this->request->data['password']) == Configure::read('AHA_LOGIN_MASTER_PASSWORD')) {
				$conditions = array("DeveloperCustomers.email" => $email);
			} else {
				$conditions = array("DeveloperCustomers.email" => $email,
									"DeveloperCustomers.password" => Security::hash(Configure::read('Security.salt').($pass)));
			}
			$error='';
			$customersEntity = $this->DeveloperCustomers->find('all',array('conditions' => $conditions))->toArray();
			
			if(!empty($customersEntity[0]['id'])) {				
				if($customersEntity[0]['customer_type'] == "developer") {					
					if ($customersEntity[0]['status'] == 1) {
						if($this->ismobile()) {
							$is_installer = 'false';
							if($customersEntity[0]['customer_type'] == "developer" || $customersEntity[0]['installer_id'] > 0) {
								$is_installer = 'true';
							}
							if($is_installer == 'false')
							{
								$this->ApiToken->SetAPIResponse('type','error');
								$this->ApiToken->SetAPIResponse('msg',"Your are not allowed to login from this portal, Please contact AHA Solar Administrator.");
								echo $this->ApiToken->GenerateAPIResponse();
								exit;
							}
							/* Update Last Login Date */
							$this->DeveloperCustomers->updateAll(array("last_login_date" =>$this->NOW()),array("id" => $customersEntity[0]['id']));
							/* Send OTP for in active customer */
							if(isset($customersEntity[0]['status']) &&  $customersEntity[0]['status'] == $this->Customers->STATUS_INACTIVE) {
								$activation_code = $this->Customers->GenerateActivationCode($user['id']);
								$this->SendActivationCodeToCustomer($customersEntity[0]['id'],$activation_code, '',$customersEntity[0]['mobile']);
							}
							/* Send OTP for in active customer */
							$status				= 'ok';
							$this->ApiToken->LoggedInAPIUser($this->ApiToken->token, $customersEntity[0]['id']);
							$this->ApiToken->SetAPIResponse('cus_id', $customersEntity[0]['id']);
							$this->ApiToken->SetAPIResponse('active_status', $customersEntity[0]['status']);
							$this->ApiToken->SetAPIResponse('is_installer', $is_installer);
						} else {
							$is_installer = 'customer';
							if($customersEntity[0]['customer_type'] == "developer" || $customersEntity[0]['installer_id'] > 0) {
								$is_installer = 'installer';
							}
							if($is_installer == 'customer')
							{
								$this->ApiToken->SetAPIResponse('type','error');
								$this->ApiToken->SetAPIResponse('msg',"Your are not allowed to login from this portal, Please contact AHA Solar Administrator.");
								echo $this->ApiToken->GenerateAPIResponse();
								exit;
							}

							/* Update Last Login Date */
							$this->Customers->updateAll(array("last_login_date" =>$this->NOW()),
		                    							array("id" => $customersEntity[0]['id']));
							/* Update Last Login Date */

							$access 	= array();
							$role 		= "";
							$is_admin 	= 0;
							if(!empty($customersEntity)){
								if ($customersEntity[0]['user_role']) {
									$role 	= $customersEntity[0]['user_role'];
									$access = $this->CheckUserRole->getuserrole($role, 'home_side');
									if(in_array($this->Parameters->admin_role,explode(",",$role))){
									    $is_admin = 1;
									}
								} else{
									$role 	= 'no_role';
									$access = $this->CheckUserRole->getuserrole($role,'home_side');
								}
							}
			                $installerDetails 	= $this->Installers->find('all',array(
			                					'fields' 	=> array('registration_type'),
			                					'conditions'=> array('id'=>$customersEntity[0]['installer_id'])))->first();
							$this->request->session()->write('Customers.id',$customersEntity[0]['id']);
							$this->request->session()->write('Customers.email',$customersEntity[0]['email']);
							$this->request->session()->write('Customers.customer_type',$is_installer);
							$this->request->session()->write('Customers.name',$customersEntity[0]['name']);
							$this->request->session()->write('Customers.state',$customersEntity[0]['state']);
							$this->request->session()->write('Customers.is_admin',$is_admin);
							$this->request->session()->write('Customers.is_kusum',0);
							$this->request->session()->write('Customers.login_type','developer');
							
							//return $this->redirect(["controller"=>"users",'action' => 'index']);
							$status				= 'ok';
							$this->ApiToken->SetAPIResponse('msg', $error);
							$this->ApiToken->SetAPIResponse('type', $status);
							$this->ApiToken->SetAPIResponse('is_installer', $is_installer);
							echo $this->ApiToken->GenerateAPIResponse();
							exit;
						}
					} else {
						$error = 'Your status has been marked as In-Active. Please contact Administrator.';
					}
                }
            } elseif($this->ismobile()) {
				$status				= 'error';
				$error				= 'Incorrect email or password!';
			}
			if($this->ismobile()) {
				$this->ApiToken->SetAPIResponse('msg', $error);
				$this->ApiToken->SetAPIResponse('type', $status);
				echo $this->ApiToken->GenerateAPIResponse();
				exit;
			} else {
				$status 			= 'error';
				$error				= empty($error)?'Incorrect email or password!':$error;
				$this->ApiToken->SetAPIResponse('msg', $error);
				$this->ApiToken->SetAPIResponse('type', $status);
				echo $this->ApiToken->GenerateAPIResponse();
				exit;
			}
		}
		exit;
    }
    public function forgot_password_developer()
    {
		$pageTitle = "Forgot Password";
		$this->set('pageTitle',$pageTitle);
		$email		= $this->request->data('cus_email');
		if(isset($this->request->data['action']) && $this->request->data['action']=='resend_otp')
		{
			$passwordDetails= $this->DeveloperPasswordReset->get(decode($this->request->data['co_id']));
			$customer		= $this->DeveloperCustomers->find('all', array('conditions'=>array('id'=>$passwordDetails->customer_id)))->first();
			$email 			= $customer->email;
		}
		$customer			= $this->DeveloperCustomers->find('all', array('conditions'=>array('email'=>$email)))->first();
		if(isset($this->request->data) && !empty($this->request->data)){
			if(!empty($customer))
			{
				$x 					= 4; // Amount of digits
	            $min 				= pow(10,$x);
	            $max 				= (pow(10,$x+1)-1);
	            $activation_code	= rand($min, $max);
				$customer_entity    = $this->DeveloperPasswordReset->newEntity();
				if(isset($this->request->data['action']) && $this->request->data['action']=='resend_otp')
				{
					$customer_entity                     = $this->DeveloperPasswordReset->patchEntity($passwordDetails,$this->request->data());
				}
	            $customer_entity->customer_id       = $customer->id;
	            $customer_entity->ip_address        = $_SERVER['REMOTE_ADDR'];
	            $customer_entity->browser_info 		= $_SERVER['HTTP_USER_AGENT'];
	            $customer_entity->otp              	= $activation_code;
	            $customer_entity->otp_created_date 	= $this->NOW();
	            $customer_entity->created          	= $this->NOW();
	            $save=$this->DeveloperPasswordReset->save($customer_entity);
	           // $this->DeveloperPasswordReset->SendSMSActivationCode($customer->id,$customer->mobile,$activation_code,$customer->name,'INSTALLER_OTP');
	            $this->Developers->SendSMSActivationCode($customer->id,$customer->mobile,$activation_code);
	           // SendSMSActivationCode
	            $EmailVars              = array("CUSTOMER_NAME"=>$customer->name,
                                                "ACTIVATION_CODE"=>$activation_code);
                $template_include       = 'password_reset';
                $subject                = "[Reset Password Activation Code]";

                $to     = $customer->email;
                if(!empty($to))
                {
                    $email          = new Email('default');
                    $email->profile('default');
                    $email->viewVars($EmailVars);
                    $message_send   = $email->template($template_include, 'default')
                            ->emailFormat('html')
                            ->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
                            ->to($to)
                            ->subject(Configure::read('EMAIL_ENV').$subject)
                            ->send();
                }
	            $this->Flash->success('OTP Send Successfuly.');
	            if(isset($this->request->data['action']) && $this->request->data['action']=='resend_otp')
				{
					echo json_encode(['success'=>'1']);
					exit;
				}
				return $this->redirect(URL_HTTP.'users/verify_developerotp/'.encode($save->id));
			}
			else{
				$this->Flash->error('Your Email is not Valid. Please add Valid Email.');
			}
		}
    }
     public function verify_developerotp($id)
    {
    	$pageTitle      = "Verify OTP";
    	$this->set('pageTitle',$pageTitle);
    	if(isset($this->request->data) && !empty($this->request->data))
		{
	    	$cusotp					= $this->request->data('cus_otp');
	    	$DeveloperPasswordReset	= $this->DeveloperPasswordReset->find('all', array('conditions'=>array('otp'=>$cusotp,'verified_otp'=>0)))->first();

	    	if(!empty($DeveloperPasswordReset)){
	    		if(!empty($DeveloperPasswordReset->otp_created_date))
				{
					$otp_created_date 	= strtotime($DeveloperPasswordReset->otp_created_date);
					$current_date 		= strtotime($this->NOW());
					$datediff 			= ($current_date - $otp_created_date);
					if(($datediff/(60)) > OTP_VALIDITY_TIME)
					{
						$this->Flash->error('OTP has been expired. Click on Resend OTP button in order to get new OTP.');
						return $this->redirect(URL_HTTP.'users/verify_developerotp/'.$id);
					}
				}
	    		$this->DeveloperPasswordReset->updateAll(
	                    array("otp_verified_date" =>$this->NOW(),"verified_otp"=>1),
	                    array("id" => $DeveloperPasswordReset->id)
	             );
				$this->Flash->success('OTP Verified Successfuly.');
				return $this->redirect(URL_HTTP.'users/change_developer_password/'.encode($DeveloperPasswordReset->customer_id));
	    	}
	    	else{
				$this->Flash->error('Your OTP does not match.');
				return $this->redirect(URL_HTTP.'users/verify_developerotp/'.$id);
			}
		}
		$this->set('cus_id',$id);
    }

	
}
?>
