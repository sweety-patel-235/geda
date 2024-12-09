<?php
namespace App\Controller\Api;

class InstallersController extends ApiMasterController
{	
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
		$this->loadModel('Installers');
		$this->loadModel('Projects');
		$this->loadModel('Customers');
		$this->loadModel('CustomerProjects');
		$this->loadModel('InstallerProjects');
		$this->loadModel('InstallerPlans');
		$this->loadModel('InstallerActivationCodes');
	}

    private function SetVariables($post_variables) { 

		if(isset($post_variables['lat']))
			$this->request->data['Installers']['latitude']	= $post_variables['lat'];
		if(isset($post_variables['lon']))
			$this->request->data['Installers']['longitude']	= $post_variables['lon'];
		if(isset($post_variables['lat']))
			$this->request->data['Installers']['latitude']	= $post_variables['lat'];
		if(isset($post_variables['installer_name']))
			$this->request->data['Installers']['installer_name'] = $post_variables['installer_name'];

		if(isset($post_variables['company_name']))
			$this->request->data['Installers']['installer_name'] 	= $post_variables['company_name'];
		if(isset($post_variables['company_plan_id']))
			$this->request->data['Installers']['installer_plan_id'] = $post_variables['company_plan_id'];
		if(isset($post_variables['company_city']))
			$this->request->data['Installers']['city'] 		= $post_variables['company_city'];
		if(isset($post_variables['company_state']))
			$this->request->data['Installers']['state'] 	= $post_variables['company_state'];
		if(isset($post_variables['company_pincode']))
			$this->request->data['Installers']['pincode'] 	= $post_variables['company_pincode'];
		
		if(isset($post_variables['about_company']))
			$this->request->data['Installers']['about_installer'] = $post_variables['about_company'];
		if(isset($post_variables['company_address']))
			$this->request->data['Installers']['address'] 	= $post_variables['company_address'];
		if(isset($post_variables['company_mobile']))
			$this->request->data['Installers']['mobile'] 	= $post_variables['company_mobile'];

		if(isset($post_variables['installer_id']))
			$this->request->data['InstallerProjects']['installer_id'] 	= $post_variables['installer_id'];
		if(isset($post_variables['project_id']))
			$this->request->data['InstallerProjects']['project_id'] 	= $post_variables['project_id'];
	}

	/**
	 *
	 * _generateInstallerSearchCondition
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use generate installer search condition.
	 *
	 * Author : Khushal Bhalsod
	 */
	private function _generateInstallerSearchCondition($id=null)
	{
		$arrCondition	= array();		
		if(!empty($id)) $this->request->data['Installers']['id'] = $id;
		
		if(isset($this->request->data) && count($this->request->data)>0) {
            if(isset($this->request->data['Installers']['id']) && trim($this->request->data['Installers']['id'])!='') {
                $strID = trim($this->request->data['Installers']['id'],',');
                $arrCondition['Installers.id'] = $this->request->data['Installers']['id'];/* array_unique(explode(',',$strID));*/
            }
			/*if(isset($this->request->data['Installers']['status']) && !empty($this->request->data['Installers']['status']))
            {
                $status = $this->request->data['Installers']['status'];
				if($this->request->data['Installers']['status']=='I') $status = $this->STATUS_INACTIVE;
				$arrCondition['Installers.status'] = $status;
            }*/
			if(isset($this->request->data['Installers']['installer_name']) && $this->request->data['Installers']['installer_name']!='') {
                $arrCondition['Installers.installer_name LIKE'] = '%'.$this->request->data['Installers']['installer_name'].'%';
            }
			if(isset($this->request->data['Installers']['email']) && $this->request->data['Installers']['email']!='') {
                $arrCondition['Installers.email LIKE'] = '%'.$this->request->data['Installers']['email'].'%';
            }
			if(isset($this->request->data['Installers']['mobile']) && $this->request->data['Installers']['mobile']!='') {
                $arrCondition['Installers.mobile LIKE'] = '%'.$this->request->data['Installers']['mobile'].'%';
            }
            if(isset($this->request->data['Installers']['address']) && $this->request->data['Installers']['address']!='') {
                $arrCondition['Installers.address LIKE'] = '%'.$this->request->data['Installers']['address'].'%';
            }
			if(isset($this->request->data['Installers']['state']) && $this->request->data['Installers']['state']!='') {
                $arrCondition['Installers.state LIKE'] = '%'.$this->request->data['Installers']['state'].'%';
            }			
			if(isset($this->request->data['Installers']['search_date']) && $this->request->data['Installers']['search_date']!='') {
                if($this->request->data['Installers']['search_period'] == 1 || $this->request->data['Installers']['search_period'] == 2) {
                	$arrSearchPara	= $this->Installers->setSearchDateParameter($this->request->data['Installers']['search_period'],$this->modelClass);
                	$this->request->data[$this->modelClass] = array_merge($this->request->data[$this->modelClass],$arrSearchPara[$this->modelClass]);
                    $this->dateDisabled						= true;
                }
                $arrperiodcondi = $this->Installers->findConditionByPeriod( $this->request->data['Installers']['search_date'],
																		$this->request->data['Installers']['search_period'],
																		$this->request->data['Installers']['DateFrom'],
																		$this->request->data['Installers']['DateTo'],
																		$this->Session->read('Installers.timezone'));
               	if(!empty($arrperiodcondi)){
                	$arrCondition['between'] = $arrperiodcondi['between'];
                }
            }
		}
		return $arrCondition;
	}

	/**
	 *
	 * getinstallerlist
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to get installer list.
	 *
	 */
	public function getinstallerlist()
	{
		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		$installerData 		= array();

		if(!empty($this->request->data['Installers']['latitude']) && !empty($this->request->data['Installers']['longitude'])) {
		   	$location = $this->Installers->GetLocationByLatLong($this->request->data['Installers']['latitude'], $this->request->data['Installers']['longitude']);
		   	if(!empty($location['state']) || !empty($location['city'])) {
		   		if(!empty($location['state'])) {
		   			$condition['state LIKE'] = '%'.$location['state'].'%';
				}
		   		$installerData  = $this->Installers->find('all',array(
					  									'conditions'=>array('OR'=>$condition)))->toArray();
		   	} else {
		   		$this->ApiToken->SetAPIResponse('type', 'error');
				$this->ApiToken->SetAPIResponse('msg', 'Invalid request data.');
		   	}		   	
		} else {
		   $installerData  = $this->Installers->find('all')->toArray();
		}		
		$arrReturn 		   = $installerData;
		$this->ApiToken->SetAPIResponse('type', 'ok');
		$this->ApiToken->SetAPIResponse('result', $arrReturn);
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	 *
	 * searchinstaller
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to get search installer.
	 *
	 * Author : Khushal Bhalsod
	 */
	public function searchinstaller()
	{
		$this->autoRender 	= false;
		$installerData 		= array();
		$this->SetVariables($this->request->data);
		
		$arrFiltres 		= $this->_generateInstallerSearchCondition();
		$installerData  	= $this->Installers->find('all')->where($arrFiltres)->toArray();
		
		$this->ApiToken->SetAPIResponse('type', 'ok');
		$this->ApiToken->SetAPIResponse('result', $installerData);
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	 *
	 * sendquery
	 *
	 * Behaviour : Public
	 *	
	 * Parameter : proj_id(int), installer_id(str)	
	 *
	 * @defination : Method is use to send query.
	 *
	 */
	public function sendquery()
	{
		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		$project_id 	= $this->request->data['proj_id'];
		$installer_id 	= $this->request->data['installer_id'];
		
		if(!empty($project_id) && !empty($installer_id)) {
			$ins_ids = explode('#',$installer_id);
			
			/*Store installer project*/
			foreach ($ins_ids as $key => $value) { 
				$insProjData['InstallerProjects']['installer_id']	= $value;
				$insProjData['InstallerProjects']['project_id']		= $project_id;
				
				$insProjEntity = $this->InstallerProjects->newEntity($insProjData);
				$insProjEntity->created = $this->NOW();
				$this->InstallerProjects->save($insProjEntity);
			}
			$custProjectData = $this->CustomerProjects
								    ->find('all')
								    ->select(['Customer.name','Parameter.para_value','Project.latitude','Project.longitude','Customer.mobile','Customer.email','Customer.city','Customer.state','Project.name','Project.area','Project.city','Project.state','Project.avg_monthly_bill','Project.estimated_kwh_year','Project.backup_type','Project.usage_hours','Project.name','Project.estimated_cost','Project.estimated_cost_subsidy','Project.payback','Project.avg_generate','Project.recommended_capacity','Project.maximum_capacity'])
								    ->join([ 
										'Project' => [
								            'table' => 'projects',
								            'type' => 'INNER',
								            'conditions' => ['Project.id = CustomerProjects.project_id']
						            	],
						            	'Customer' => [
								            'table' => 'customers',
								            'type' => 'INNER',
								            'conditions' => ['Customer.id = CustomerProjects.customer_id']
						            	],
								       	'Parameter' => [
								            'table' => 'parameters',
								            'type' => 'LEFT',
								            'conditions' => ['Parameter.para_id = Project.customer_type']
						            	]])
								    ->where(array('CustomerProjects.project_id' =>$project_id))->first();
			
			$backup = (isset($custProjectData['Project']['backup_type'])?$custProjectData['Project']['backup_type']:'');
            $custProjectData['Project']['backup_type_name'] = '';
            if($backup == $this->Projects->BACKUP_TYPE_GENERATOR) {
               $custProjectData['Project']['backup_type_name'] = "Generator";
            }elseif($backup == $this->Projects->BACKUP_TYPE_INVERTER) {
                $custProjectData['Project']['backup_type_name'] = "Inverter";
            } else {
                $custProjectData['Project']['backup_type_name'] = "No";
            } 						    
           
			$installerData		= $this->Installers->find('all',array(
					  									'conditions'=>array('id IN' =>$ins_ids)))->toArray();
			$this->sendQueryEmail($custProjectData, $installerData);
			$this->ApiToken->SetAPIResponse('type', 'ok');
			$this->ApiToken->SetAPIResponse('msg', 'Query send successfully.');
		} else {
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'Invalid request data.');
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	 *
	 * sendQueryEmail
	 *
	 * Behaviour : Public
	 *		
	 * Parameter : $projectDetail(array), $installerList(array)	
	 *
	 * @defination : Method is use to send query email.
	 *
	 */
	public function sendQueryEmail($projectDetail, $installerList)
	{
		if(!empty($projectDetail) && !empty($installerList)) { 
			
			$to			= SEND_QUERY_EMAIL;
			$subject	= "Project Query";
			$email 		= new Email('default');
		 	$email->profile('default');

			$email->viewVars(array('project_detail' => $projectDetail, 'installer_list' => $installerList));			
			$email->template('send_query', 'default')
				->emailFormat('html')
				->from(array('do-not-reply@ahasolar.in' => PRODUCT_NAME))
			    ->to($to)
			    ->subject($subject)
			    ->send();
		}
	}

	/**
	 *
	 * ProfessionalVersionRegistration
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use for professional version registration.
	 *
	 * Author : Khushal Bhalsod
	 */
	public function professionalVersionRegistration()
	{
		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		$customer_id = $this->ApiToken->customer_id;
		
		if(!empty($customer_id)) { 
			$installercnt	  	= 0;
			$customerdetail 	= array();
			$customerdetail 	= $this->Customers->get($customer_id);

			$installersEntity 	= $this->Installers->newEntity($this->request->data());
			$installersEntity->created 		= $this->NOW();
			$installersEntity->customer_id 	= $customer_id;
			$installersEntity->email 		= (isset($customerdetail['email'])?$customerdetail['email']:'');
			$installersEntity->mobile 		= (isset($customerdetail['mobile'])?$customerdetail['mobile']:'');
			$installercnt = $this->Installers->find('all', array('conditions'=>array('customer_id'=>$customer_id)))->count();
			
			$planId 		= $this->request->data['Installers']['installer_plan_id']; 
			$insplanData 	= $this->InstallerPlans->get($planId);
			if($installercnt == 0) {
				if ($this->Installers->save($installersEntity)) {

					/* Send worker activation codes email */
					if(isset($insplanData['user_limit']) && $insplanData['user_limit'] > 0) { 
						$insCodeArr = array();
						for ($i=0; $i < $insplanData['user_limit']; $i++) { 
							$activation_codes = $this->Installers->generateInstallerActivationCodes();
							$insCodeArr[] = $activation_codes;
							$insCodedata['InstallerActivationCodes']['installer_id']	= $installersEntity->id;
							$insCodedata['InstallerActivationCodes']['activation_code']	= $activation_codes;
							$insCodeEntity	= $this->InstallerActivationCodes->newEntity($insCodedata);
							$this->InstallerActivationCodes->save($insCodeEntity);
						}
					}
					/*$this->SendProfessionalRegistrationNotificationEmail($installersEntity->id, $insCodeArr);*/
					$status	= 'ok';
					$this->ApiToken->SetAPIResponse('type', $status);
					$this->ApiToken->SetAPIResponse('ins_id', $installersEntity->id);				
				} else {
					$status	= 'error';
					$error	= 'Registration fail.';
					$this->ApiToken->SetAPIResponse('type', $status);
					$this->ApiToken->SetAPIResponse('msg', $error);				
				}
			} else {
				$status	= 'error';
				$error	= 'This email is already registered.';
				$this->ApiToken->SetAPIResponse('type', $status);
				$this->ApiToken->SetAPIResponse('msg', $error);
			} 
		} else {
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'Invalid request.');
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	*
	* SendProfessionalRegistrationNotificationEmail
	*
	* Behaviour : public
	*
	* @defination : Method is used to send registration email.
	*
	* Author : Khushal Bhalsod
	*/
	public function SendProfessionalRegistrationNotificationEmail($insId, $insCodeArr)
	{
		if(!empty($insId) && !empty($insCodeArr)) {
			$insData = $this->Installers->get($insId);
			if(!empty($insData['email'])) { 	
				$to			= $insData['email'];
				$subject	= PRODUCT_NAME." Registration";
				$email 		= new Email('default');
			 	$email->profile('default');

				$email->viewVars(array('installer_detail' => $insData,'installercodeArr'=>$insCodeArr));			
				$email->template('professional_registration', 'default')
						->emailFormat('html')
						->from(array('do-not-reply@ahasolar.in' => PRODUCT_NAME))
					    ->to($to)
					    ->subject($subject)
					    ->send();
			}
		}
	}

	/**
	*
	* getInstallerProfile
	*
	* Behaviour : public
	*
	* @defination : Method is used to get the Installer profile.
	*
	* Author : Khushal Bhalsod
	*/
	public function getInstallerProfile() {

		$this->autoRender = false;
		$this->SetVariables($this->request->data);
		$cus_id		= $this->ApiToken->customer_id;
		$insData 	= array();
		if(!empty($cus_id)) {
			$insData 	= $this->Installers
									->find('all')
								    ->select(['Customer.name','Installers.installer_name','Installers.mobile','Installers.installer_plan_id','Installers.about_installer','Installers.address','Installers.profile_pic','InstallerPlan.plan_name'])
								    ->join([ 
										'Customer' => [
								            'table' => 'customers',
								            'type' => 'INNER',
								            'conditions' => ['Customer.id = Installers.customer_id']
						            	],
						            	'InstallerPlan' => [
								            'table' => 'installer_plans',
								            'type' => 'INNER',
								            'conditions' => ['InstallerPlan.id = Installers.installer_plan_id']
						            	]])
								    ->where(array('Installers.customer_id' =>$cus_id))->first();
			
			$arrReturn['company_name'] 		= (isset($insData['installer_name'])?$insData['installer_name']:'');
			$arrReturn['installer_name'] 	= (isset($insData['Customer']['name'])?$insData['Customer']['name']:'');
			$arrReturn['mobile'] 			= (isset($insData['mobile'])?$insData['mobile']:'');
			$arrReturn['active_plan_id'] 	= (isset($insData['installer_plan_id'])?$insData['installer_plan_id']:'');
			$arrReturn['active_plan_name'] 	= (isset($insData['InstallerPlan']['plan_name'])?$insData['InstallerPlan']['plan_name']:'');
			$arrReturn['about_company'] 	= (isset($insData['about_installer'])?$insData['about_installer']:'');
			$arrReturn['company_address'] 	= (isset($insData['address'])?$insData['address']:'');
			$arrReturn['logo'] 				= (isset($insData['profile_pic'])?INSTALLER_PROFILE_URL.$insData['profile_pic']:'');

			$this->ApiToken->SetAPIResponse('type', 'ok');
			$this->ApiToken->SetAPIResponse('result', $arrReturn);
		} else {
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'Invalid Request.');
		}		
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	*
	* getInstallerProjectLead
	*
	* Behaviour : public
	*
	* @defination : Method is used to get the Installer project.
	*
	* Author : Khushal Bhalsod
	*/
	public function getInstallerProjectLead()
	{
		$this->autoRender = false;
		$this->SetVariables($this->request->data);
		$cus_id			= $this->ApiToken->customer_id;
		$installerdata	= $this->Installers->find('all', array('conditions'=>array('customer_id'=>$cus_id)))->first();
		$installer_id 	= (isset($installerdata['id'])?$installerdata['id']:0);
		$insLeadData 	= array();
		
		if(!empty($installer_id)) {
			$insLeadArr 	= $this->InstallerProjects
									->find('all')
								    ->select(['Project.id','Project.name','Project.address','Project.city','Project.created','Project.recommended_capacity','Parameter.para_value'])
								    ->join([ 
										'Project' => [
								            'table' => 'projects',
								            'type' => 'INNER',
								            'conditions' => ['Project.id = InstallerProjects.project_id']
						            	],
						            	'Parameter' => [
								            'table' => 'parameters',
								            'type' => 'INNER',
								            'conditions' => ['Parameter.para_id = Project.customer_type']
						            	]])
								    ->where(array('InstallerProjects.installer_id' =>$installer_id))->toArray();
			
			if(!empty($insLeadArr)) {
				foreach($insLeadArr as $key=>$value) { 
					$insLeadData[$key]['id']			= (isset($insLeadArr[$key]['Project']['id'])?$insLeadArr[$key]['Project']['id']:'');
					$insLeadData[$key]['name']			= (isset($insLeadArr[$key]['Project']['name'])?$insLeadArr[$key]['Project']['name']:'');
					$insLeadData[$key]['address'] 		= (isset($insLeadArr[$key]['Project']['address'])?$insLeadArr[$key]['Project']['address']:'');
					$insLeadData[$key]['city'] 			= (isset($insLeadArr[$key]['Project']['city'])?$insLeadArr[$key]['Project']['city']:'');
					$insLeadData[$key]['capacity'] 		= (isset($insLeadArr[$key]['Project']['recommended_capacity'])?$insLeadArr[$key]['Project']['recommended_capacity']:'');
					$insLeadData[$key]['cus_type'] 		= (isset($insLeadArr[$key]['Parameter']['para_value'])?$insLeadArr[$key]['Parameter']['para_value']:'');
					$insLeadData[$key]['proj_time'] 	= (isset($insLeadArr[$key]['Project']['created'])?date("h:i a", strtotime($insLeadArr[$key]['Project']['created'])):'');
					$insLeadData[$key]['proj_date'] 	= (isset($insLeadArr[$key]['Project']['created'])?date("d/m/Y", strtotime($insLeadArr[$key]['Project']['created'])):'');
				}	
			}			
			$this->ApiToken->SetAPIResponse('type', 'ok');
			$this->ApiToken->SetAPIResponse('result', $insLeadData);
		} else {
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'Invalid Request.');
		}		
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	*
	* changeInstallerProjectStatus
	*
	* Behaviour : public
	*
	* @defination : Method is used to change status of Installer project.
	*
	* Author : Khushal Bhalsod
	*/
	public function changeInstallerProjectStatus()
	{ 
		$this->autoRender = false;
		$this->SetVariables($this->request->data);
		$cus_id			= $this->ApiToken->customer_id;
		$installerdata	= $this->Installers->find('all', array('conditions'=>array('customer_id'=>$cus_id)))->first();
		$installer_id 	= (isset($installerdata['id'])?$installerdata['id']:0);

		$project_id 	= (isset($this->request->data['project_id'])?$this->request->data['project_id']:0);
		$status 		= (isset($this->request->data['status'])?$this->request->data['status']:0);
		
		if(!empty($installer_id) && !empty($project_id) && !empty($status)) {
			$this->InstallerProjects->updateAll(['status' => $status], ['installer_id' => $installer_id,'project_id' => $project_id]);	
			$this->ApiToken->SetAPIResponse('type', 'ok');
			$this->ApiToken->SetAPIResponse('msg', 'Project status change successfully.');
		} else {
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'Invalid Request.');
		}	
		echo $this->ApiToken->GenerateAPIResponse();
		exit;	
	}

	/**
	*
	* forwardInstallerProjectLead
	*
	* Behaviour : public
	*
	* @defination : Method is used to forward Installer project lead.
	*
	* Author : Khushal Bhalsod
	*/
	public function forwardInstallerProjectLead()
	{ 
		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		
		$project_id 	= (isset($this->request->data['project_id'])?$this->request->data['project_id']:0);
		$installer_id 	= (isset($this->request->data['installer_id'])?$this->request->data['installer_id']:0);
		
		if(!empty($project_id) && !empty($installer_id)) {
			$ins_ids = explode('#',$installer_id);
			/*Store installer project*/
			foreach ($ins_ids as $key => $value) { 
				$insProjData['InstallerProjects']['installer_id']	= $value;
				$insProjData['InstallerProjects']['project_id']		= $project_id;
				
				$insProjEntity = $this->InstallerProjects->newEntity($insProjData);
				$insProjEntity->created = $this->NOW();
				$this->InstallerProjects->save($insProjEntity);
			}
			$this->ApiToken->SetAPIResponse('type', 'ok');
			$this->ApiToken->SetAPIResponse('msg', 'Project lead forwarded.');
		} else {
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'Invalid request data.');
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	*
	* updateInstallerDetail
	*
	* Behaviour : public
	*
	* @defination : Method is used to update installer detail.
	*
	* Author : Khushal Bhalsod
	*/
	public function updateInstallerDetail()
	{ 
		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		$cus_id			= $this->ApiToken->customer_id;
		$installerdata	= $this->Installers->find('all', array('conditions'=>array('customer_id'=>$cus_id)))->first();
		$installer_id 	= (isset($installerdata['id'])?$installerdata['id']:0);
		
		if(!empty($installer_id) & !empty($this->request->data['Installers'])) { 
			$this->request->data['Installers']['modified'] 	= $this->NOW();
			$this->Installers->updateAll($this->request->data['Installers'], ['id' => $installer_id]);	
			
			/*Update Installer Profile Picture*/
			if(isset($this->request->data['Installers']['profile_pic']['name']) && !empty($this->request->data['Installers']['profile_pic']['name'])){
				$installerData 		= $this->Installers->get($installer_id);
				$db_profile_image 	= $installerData->toArray()['profile_pic'];

				$image_path = INSTALLER_PROFILE_PATH.$installer_id.'/';
				if(file_exists($image_path.$db_profile_image)){
					@unlink($image_path.$db_profile_image);
					@unlink($image_path.'r_'.$db_profile_image);
				}
				if(!file_exists(INSTALLER_PROFILE_PATH.$installer_id))
					mkdir(INSTALLER_PROFILE_PATH.$installer_id, 0755);
				$file_name = $this->file_upload($image_path,$this->request->data['Installers']['profile_pic'],true,65,65,$image_path);
				$this->Installers->updateAll(['profile_pic' => $file_name], ['id' => $installer_id]);
			}
			$this->ApiToken->SetAPIResponse('type', 'ok');
			$this->ApiToken->SetAPIResponse('msg', 'Profile updated successfully.');
		} else {
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'Invalid request data.');
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
}
