<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\ORM\Table;

use Cake\Core\Configure;
use Cake\Network\Email\Email;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Controller\Component;
use Cake\Utility\Security;
use Cake\Datasource\ConnectionManager;

use Cake\Event\Event;
use Cake\View\Helper\PaginatorHelper;
use Cake\Validation\Validator;
use Dompdf\Dompdf;

class ProposalsController extends AppController
{	
	var $helpers = array('Time','Html','Form','ExPaginator');
	public $arrDefaultAdminUserRights 	= array(); 
	public $PAGE_NAME 					= '';
	public $contact_code_min = 	1000;
	public $contact_code_max =	9999;
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

		$this->loadModel('Users');
		$this->loadModel('Userrole');
		$this->loadModel('Userroleright');
		$this->loadModel('Adminaction');
		$this->loadModel('UserDepartment');
		$this->loadModel('Admintrntype');
		$this->loadModel('Admintrnmodule');
		$this->loadModel('ApiToken');
		$this->loadModel('GhiData');
		$this->loadModel('Installers');
		$this->loadModel('Projects');
		$this->loadModel('Customers');
		$this->loadModel('CustomerProjects');
		$this->loadModel('InstallerProjects');
		$this->loadModel('ProjectLeads');		
		$this->loadModel('SiteSurveys');		
		$this->loadModel('Proposal');		
		$this->loadModel('InstallerUsers');		
	
		$this->set('Userright',$this->Userright);
    }

    private function SetVariables($post_variables) { 

		if(isset($post_variables['site_survey']))
			$this->request->data['Proposal']['site_survey']		= $post_variables['site_survey'];
		if(isset($post_variables['proj_id']))
			$this->request->data['Proposal']['project_id']		= $post_variables['proj_id'];
		if(isset($post_variables['installer_id']))
			$this->request->data['Proposal']['installer_id']		= $post_variables['installer_id'];
		if(isset($post_variables['commercial1']))
			$this->request->data['Proposal']['commercial1']		= $post_variables['commercial1'];
		if(isset($post_variables['commercial2']))
			$this->request->data['Proposal']['commercial2']		= $post_variables['commercial2'];
		if(isset($post_variables['terms_conditions']))
			$this->request->data['Proposal']['terms_conditions']		= $post_variables['terms_conditions'];
		if(isset($post_variables['email_customer']))
			$this->request->data['Proposal']['email_customer']		= $post_variables['email_customer'];
		if(isset($post_variables['email_team']))
			$this->request->data['Proposal']['email_team']		= $post_variables['email_team'];
		if(isset($post_variables['email_coworker']))
			$this->request->data['Proposal']['email_coworker']		= $post_variables['email_coworker'];
		if(isset($post_variables['email']))
			$this->request->data['Proposal']['others_email']		= $post_variables['email'];
		if(isset($post_variables['proj_id']))
			$this->request->data['Proposal']['project_id']		= $post_variables['proj_id'];
		if(isset($post_variables['survey_id']))
			$this->request->data['Proposal']['survey_id']		= $post_variables['survey_id'];
		
	}

	/**
	 *
	 * getProjectEstimation
	 *
	 * Behaviour : public
	 *
	 * @defination : Method is use to add new admin user and provide specific righs using admin interface
	 *
	 */
	public function sendproposalweb()
	{
		$this->layout = false;
		$cus_id = "673";
		$project_id="512";
		if(!empty($project_id)){
		$project 	= $this->Projects->find('all',['join'=>[
					        'c' => [
					            'table' => 'customer_projects',
					            'type' => 'LEFT',
					            'conditions' => ['c.project_id = Projects.id']
			            	],
			            	'customer' => [
					            'table' => 'customers',
					            'type' => 'LEFT',
					            'conditions' => ['customer.id = c.customer_id']
			            	],
			            	'custtype' => [
					            'table' => 'parameters',
					            'type' => 'LEFT',
					            'conditions' => ['custtype.para_id = Projects.customer_type']
			            	]],
			            	'fields' => array('custtype.para_value','customer.name','customer.email','customer.mobile','customer.city','customer.state'),
			            	'conditions' => ['Projects.id' => $project_id],
			            	'order' => array('Projects.id' => 'DESC')])->autoFields(true)->first();
			$filePath = $this->genratePDFSiteSurveyreport($project_id,$project,false,1);

		}
	}
	public function sendproposal()
	{
		$this->autoRender 	= false;
		$this->layout = false;		
		$this->SetVariables($this->request->data);
		$cus_id	= $this->ApiToken->customer_id;	
		$customerData   = $this->Customers->find('all', array('conditions'=>array('id'=>$cus_id)))->first();
  		$cus_id   = (isset($customerData['installer_id'])?$customerData['installer_id']:0);
		$status="error";
		$project_id 	= (isset($this->request->data['Proposal']['project_id'])?$this->request->data['Proposal']['project_id']:0);
		
		if(!empty($cus_id) && !empty($project_id)) {
			if(isset($this->request->data['Proposal']['project_id']) && !empty($this->request->data['Proposal']['project_id'])){}
			if(isset($this->request->data['Proposal']['site_survey']) && !empty($this->request->data['Proposal']['site_survey'])){}
			if(isset($this->request->data['Proposal']['commercial1']) && !empty($this->request->data['Proposal']['commercial1'])){}
			if(isset($this->request->data['Proposal']['commercial2']) && !empty($this->request->data['Proposal']['commercial2'])){}
			if(isset($this->request->data['Proposal']['terms_conditions']) && !empty($this->request->data['Proposal']['terms_conditions'])){}
			if(isset($this->request->data['Proposal']['email_customer']) && !empty($this->request->data['Proposal']['email_customer'])){}
			if(isset($this->request->data['Proposal']['email_team']) && !empty($this->request->data['Proposal']['email_team'])){}
			if(isset($this->request->data['Proposal']['others_email']) && !empty($this->request->data['Proposal']['others_email'])){}
			
			$proposalArr = $this->Proposal->find("all",array("conditions"=>array("Proposal.project_id"=>$project_id,"Proposal.installer_id"=>$cus_id)))->first();
			if(!empty($proposalArr)){
				$dataGet = $this->Proposal->get($proposalArr['id']);
				$newProposal = $this->Proposal->patchEntity($dataGet,$this->request->data());
				$newProposal->installer_id  = $cus_id;
			}else{
				$newProposal = $this->Proposal->newEntity($this->request->data());
				$newProposal->installer_id  = $cus_id;
			}
            $emailData = array();
			if(!empty($newProposal->others_email)) {
				$emailsArr = explode(",", $newProposal->others_email);
				if(!empty($emailsArr)) {
					foreach ($emailsArr as $key => $value) {
						$emailData[$key]= array('email'=>$value);
					}
				}
			}
            $messege['site_survey'] 		=  (!empty($newProposal->site_survey)?$newProposal->site_survey:0);
			$messege['commercial1'] 		=  (!empty($newProposal->commercial1)?$newProposal->commercial1:0);
			$messege['commercial2']		 	=  (!empty($newProposal->commercial2)?$newProposal->commercial2:0);
			$messege['terms_conditions'] 	=  (!empty($newProposal->terms_conditions)?$newProposal->terms_conditions:0);
			$messege['email_customer'] 		=  (!empty($newProposal->email_customer)?$newProposal->email_customer:0);
			$messege['email_team'] 			=  (!empty($newProposal->email_team)?$newProposal->email_team:0);
			$messege['others_email'] 		=  (!empty($newProposal->others_email)?$emailData:array());
			
			if(!empty($project_id)){
			$project 	= $this->Projects->find('all',['join'=>[
						        'c' => [
						            'table' => 'customer_projects',
						            'type' => 'LEFT',
						            'conditions' => ['c.project_id = Projects.id']
				            	],
				            	'customer' => [
						            'table' => 'customers',
						            'type' => 'LEFT',
						            'conditions' => ['customer.id = c.customer_id']
				            	],
				            	'custtype' => [
						            'table' => 'parameters',
						            'type' => 'LEFT',
						            'conditions' => ['custtype.para_id = Projects.customer_type']
				            	]],
				            	'fields' => array('custtype.para_value','customer.name','customer.email','customer.mobile','customer.city','customer.state'),
				            	'conditions' => ['Projects.id' => $project_id],
				            	'order' => array('Projects.id' => 'DESC')])->autoFields(true)->first();
				//$filePath = $this->genratePDFSiteSurveyreport($project_id,$project,false,1);
				
				if(!empty($newProposal->email_customer)) {
					$customerEmail = $this->CustomerProjects->findByProjectId($project_id)->contain('Customers')->first();
					$customerEmail = (isset($customerEmail['customer']['email'])?$customerEmail['customer']['email']:'');
					$cusEmail['email'] = $customerEmail;
					array_push($emailData, $cusEmail);
				}
				if(!empty($newProposal->email_team)) {
					$teamEmail = $this->InstallerUsers->GetInstallerUserEmail($cus_id);
				}
				$emailListArr = (!empty($teamEmail)?array_merge($emailData,$teamEmail):$emailData);
				
				if(!empty($emailListArr)) {
					foreach ($emailListArr as $key => $value) {
						if(!empty($value['email'])) {
							
							$Email = new Email('default');
							$Email->profile('default');
							$Email->viewVars(array('project_detail' => $project));
							$Email->template('send_installers_query_report', 'default')
								->emailFormat('html')
								->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
								->to($value['email'])
								->subject(Configure::read('EMAIL_ENV').'Survey Report')
								->attachments(array())
								->send();
						}
					}
				}
                $status				= 'ok';
				$error				= 'Proposal sent successfully';
				if($this->request->data("submit")  =='1'){
					$this->Proposal->save($newProposal);
					$this->ApiToken->SetAPIResponse('msg', $error);
				}else{
					$this->ApiToken->SetAPIResponse('result', $messege);
					$this->ApiToken->SetAPIResponse('msg', $error);
				}
				
				
			} else {
				$status				= 'error';
				$error				= 'Please try after some time';
				$this->ApiToken->SetAPIResponse('msg', $error);
			}
		}
		$this->ApiToken->SetAPIResponse('type', $status);
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	
}
