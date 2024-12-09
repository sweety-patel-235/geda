<?php
namespace App\Controller;
use Cake\ORM\TableRegistry;
use Cake\ORM\Table;
use Cake\Core\Configure;
use Cake\Network\Email\Email;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Controller\Component;
use Cake\Utility\Security;
use Cake\Event\Event;
use Cake\View\Helper\PaginatorHelper;
use Cake\Validation\Validator;
use Dompdf\Dompdf;
use PHPExcel\PHPExcel;
class InstallersController extends FrontAppController
{	
	public $paginate = [
        'limit' => PAGE_RECORD_LIMIT,
        'order' => [
            'Installers.id ' => 'desc'
        ]
    ];
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
		$this->loadModel('States');
		$this->loadModel('Projects');
		$this->loadModel('Customers');
		$this->loadModel('CustomerProjects');
		$this->loadModel('InstallerProjects');
		$this->loadModel('InstallerPlans');
		$this->loadModel('InstallerActivationCodes');
		$this->loadModel('GhiData');
		$this->loadModel('InstallerCategory');
		$this->loadModel('ApplyOnlines');
		$this->loadModel('ApplyOnlineApprovals');
		$this->loadModel('InstallerSuccessPayment');
		$this->loadModel('InstallerPayment');
		$this->loadModel('Emaillog');
		$this->loadModel('InstallerMessage');
		$this->loadModel('FeesReturn');
	}
	/**
	 *
	 * index
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use for listing of Installer
	 *
	 * Author : dhingani yatin
	 */

	public function index($page = '1')
    {
    	if($this->Session->check('Members.state')) {
			$this->paginate['page'] = $page;
	        $current_state 			= $this->Session->read('Members.state');
	        
	        if(isset($this->request->data['Reset']) && !empty($this->request->data['Reset']))
	        {
				$this->Session->delete("Installer.Search");
				return $this->redirect(URL_HTTP.'installers');
			}
			$download_excel			= isset($this->request->data['download']) ? $this->request->data['download'] : 0;
	        if(isset($this->request->data['Search']) && !empty($this->request->data['Search']))
	        {
	        	$download_excel 	= $this->request->data['download'];
	        	
	        	$this->request->data['download'] 	= 0;	
				$this->Session->write('Installer.Search',serialize($this->request->data));
			}
			elseif($this->Session->check("Installer.Search"))
			{
				$this->request->data = unserialize($this->Session->read("Installer.Search"));
			}
			if(isset($this->request->data['state']) && !empty($this->request->data['state'])){
				$current_state = $this->request->data['state'];
			} else {
				$this->request->data['state'] = $current_state;
			}
			$installer_name 			= isset($this->request->data['installer_name']) ? $this->request->data['installer_name'] : '';
			$category_name				= isset($this->request->data['category_name']) ? $this->request->data['category_name'] : '';
	        $Installers 				= $this->Installers->GetInstallerList($current_state,$installer_name,$category_name);
	        $memberViewmobile 			= in_array($this->Session->read('Members.id'), $this->ApplyOnlines->ALLOWED_APPROVE_GEDAIDS) ? '1' : '0';
	       
	        if($download_excel == 1) {
				
				$PhpExcel 			= $this->PhpExcel;
				$PhpExcel->createExcel();
				$objDrawing 		= new \PHPExcel_Worksheet_MemoryDrawing();
				$objDrawing->setCoordinates('A1');
				$objDrawing->setWorksheet($PhpExcel->getExcelObj()->getActiveSheet());
				$j 					= 1;
				$i 					= 1;
				$arrReportFields 	= array('sr_no'			=> "Sr no",
											'installer_name'=> 'Installer Name',
											'email'			=> "Email",
											'city'			=> "City",
											'state' 		=> "State",
											'address'		=> "Address",
											'category'		=> "Category",
											'slots'			=> "Available Slots"
											);
				if($memberViewmobile==1) {
					array_push($arrReportFields, array('mobile'=>'Mobile'));
				}
				//$arrSecond 			= array('ticket_closed'=>'Ticket Closed','last_response'=>'Last Response','2nd_last_response'=> '2nd Last Response','3rd_last_response'=> '3rd Last Response','4th_last_response'=> '4th Last Response','5th_last_response'=> '5th Last Response');
				foreach ($arrReportFields as $key=>$Field_Name) {
					$RowName 	= $this->FeesReturn->GetExcelColumnName($i);

					$ColTitle  	= $Field_Name;
					$PhpExcel->writeCellValue($RowName.$j,$ColTitle);
					$PhpExcel->getExcelObj()->getActiveSheet()->getColumnDimension($RowName)->setAutoSize(true);
					$i++;
				}

				$j++;
				$i = 1;
				
				
				$InsData 	= $Installers->toArray();
				if(!empty($InsData)){
					foreach($InsData as $key=>$val) {
						
						$categoryName 		= !empty($val->installer_category['category_name'])? $val->installer_category['category_name'] : '';
						$assign_slots  		= $this->ApplyOnlines->assign_slot_array($val->installer_category_mapping['allowed_bands']);
						$assign_slots_str 	= (isset($assign_slots)) ? implode(" : ",$assign_slots) : '-';

						$RowName = $this->FeesReturn->GetExcelColumnName($i);
						$PhpExcel->writeCellValue($RowName.$j,$j-1);
						$i++;
						$RowName = $this->FeesReturn->GetExcelColumnName($i);
						$PhpExcel->writeCellValue($RowName.$j,$val->installer_name);
						$i++;
						$RowName = $this->FeesReturn->GetExcelColumnName($i);
						$PhpExcel->writeCellValue($RowName.$j,$val->email);
						$i++;
						$RowName = $this->FeesReturn->GetExcelColumnName($i);
						$PhpExcel->writeCellValue($RowName.$j,$val->city);
						$i++;
						$RowName = $this->FeesReturn->GetExcelColumnName($i);
						$PhpExcel->writeCellValue($RowName.$j,$val->state);
						$i++;
						$RowName = $this->FeesReturn->GetExcelColumnName($i);
						$PhpExcel->writeCellValue($RowName.$j,$val->address);
						$i++;
						$RowName = $this->FeesReturn->GetExcelColumnName($i);
						$PhpExcel->writeCellValue($RowName.$j,$categoryName);
						$i++;
						$RowName = $this->FeesReturn->GetExcelColumnName($i);
						$PhpExcel->writeCellValue($RowName.$j,$assign_slots_str);
						$i++;
						
						$i=1;
						$j++;
					}
				}
				$PhpExcel->downloadFile(time());
				exit;
			}
	        $installers_list 			= $this->Installers->getInstallerListReport();
	       
	       	$Installer_category =$this->InstallerCategory->find('list',['keyField'=>'id','valueField'=>'category_name'])->toArray();
	        $this->set('state_list',$this->States->find('list',['keyField'=>'id','valueField'=>'statename']));
			$this->set("pageTitle","Installer");
			$this->set("Installer_category",$Installer_category);
			$this->set('Installers',$this->paginate($Installers));
			$this->set('ApplyOnlines',$this->ApplyOnlines);
	        $this->set('installers_list',$installers_list);
	        $this->set('memberViewmobile',$memberViewmobile);
	    } else {
	    	return $this->redirect('/');
	    }
    } 

    private function SetVariables($post_variables) { 

		if(isset($post_variables['lat']))
			$this->request->data['Installers']['latitude']	= $post_variables['lat'];
		if(isset($post_variables['lon']))
			$this->request->data['Installers']['longitude']	= $post_variables['lon'];
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
	 * Author : Yugtia Technologies Pvt. Ltd.
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
	public function getinstallerlist($project_id=0)
	{
		$installerData 		= array();
		$msg 				= '';
		if($this->ismobile()){
			$this->autoRender 	= false;
			$this->SetVariables($this->request->data);
		}
		$project_id 	= decode($project_id);
		if($project_id > 0)
		{
			
			
			$project_data 	= $this->Projects->find('all',array('conditions'=>array('id'=>$project_id)))->first();
			$this->request->data['Installers']['latitude'] 	= $project_data->latitude;
			$this->request->data['Installers']['longitude'] = $project_data->longitude;
		}
		//echo $this->request->data['Installers']['latitude']." ".$this->request->data['Installers']['longitude'];
		$existingInstaller 	= array();
		$ins_ids 			= array();
		$installerList 		= $this->InstallerProjects->getProjectwiseInstallerList($project_id);
		if(!empty($installerList))
		{
			foreach($installerList as $keys=>$insArray)
			{
				$existingInstaller[]= $insArray->installers['id'];
			}
		}
		$checked='';
		if(isset($this->request->data['installer_id']))	
		{
			foreach ($this->request->data['installer_id'] as $key => $value) 
			{ 
				if(!in_array($value,$existingInstaller) && $value != '0'){
				$insProjData['InstallerProjects']['installer_id']	= $value;
				$insProjData['InstallerProjects']['project_id']		= $project_id;
				$insProjData['InstallerProjects']['status']			= 4001;
				
				$insProjEntity 			= $this->InstallerProjects->newEntity($insProjData);
				$insProjEntity->created = $this->NOW();
				$this->InstallerProjects->save($insProjEntity);
				$ins_ids[] 				= $value;
				$checked = '1';
				}
				
			}
			if($checked=='1')
			{
				$custProjectData = $this->CustomerProjects
								    ->find('all')
								    ->select(['Customer.name','Parameter.para_value','Project.latitude','Project.longitude','Customer.mobile','Customer.email','Customer.city','Customer.state','Project.city','Project.state','Project.landmark','Project.name','Project.area','Project.avg_monthly_bill','Project.estimated_kwh_year','Project.backup_type','Project.usage_hours','Project.name','Project.estimated_cost','Project.estimated_cost_subsidy','Project.payback','Project.avg_generate','Project.recommended_capacity','Project.maximum_capacity'])
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
            if(!empty($ins_ids))
            {
            	$installerData	= $this->Installers->find('all',array('conditions'=>array('id IN' =>$ins_ids)))->toArray();
            	$this->sendQueryEmail($custProjectData, $installerData);
            }
			/*Send Project Report PDF to Customer*/
			$this->sendMailToCustomer($project_id);	
			$this->Flash->success("Query send successfully.");
            return $this->redirect('project/dashboard/'.encode($project_id)); 
            //return $this->redirect(array('controller'=>'users','action'=>'index'));
			exit;		
			}
		
		}
		$installer_name = isset($this->request->data['installer_name'])?$this->request->data['installer_name']:'';
		if((!empty($this->request->data['Installers']['latitude']) && !empty($this->request->data['Installers']['longitude'])) || $installer_name!='' || $checked == '') {
		   	$location 		= GetLocationByLatLong($this->request->data['Installers']['latitude'], $this->request->data['Installers']['longitude']);
		   	if(!empty($location['state']) || !empty($location['city']) || !empty($installer_name)) {
		   		if(!empty($location['state'])) {
		   			$condition['state LIKE'] = '%'.$location['state'].'%';
				}
				if(!empty($installer_name))
				{
					$condition['installer_name LIKE'] = '%'.$installer_name.'%';
				}
				$condition['status'] = '1';
		   		$installerData  = $this->Installers->find('all',array(
					  									'conditions'=>array($condition)))->toArray();
		   	} else {
				if($this->ismobile()){
		   		$this->ApiToken->SetAPIResponse('type', 'error');
				$this->ApiToken->SetAPIResponse('msg', 'Invalid request data.');
				}
		   	}		   	
		} else {
		   $installerData  = $this->Installers->find('all',array(
					  									'conditions'=>array('status'=>'1')))->toArray();
		}		
		$arrReturn 		   = $installerData;
		if($this->ismobile()) {
			$this->ApiToken->SetAPIResponse('type', 'ok');
			$this->ApiToken->SetAPIResponse('result', $arrReturn);
			echo $this->ApiToken->GenerateAPIResponse();
			exit;
		} else {
			$this->set('arrReturn',$arrReturn);
			$this->set('pageTitle','Select Installer');
		}
	}

	/**
	 *
	 * searchinstaller
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to get search installer.
	 *
	 * Author : Yugtia Technologies Pvt. Ltd.
	 */
	public function searchinstaller()
	{
		$this->autoRender 	= false;
		$installerData 		= array();
		$this->SetVariables($this->request->data);
		
		$arrFiltres 		= $this->_generateInstallerSearchCondition();
		$arrFiltres['Installers.status'] = 1;
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
			
			$projectState = (isset($projectDetail['Project']['state'])?$projectDetail['Project']['state']:'');
			if(!empty($projectState) && strtolower($projectState) == 'jharkhand'){
				$to			= array(SEND_QUERY_EMAIL,SEND_QUERY_EMAIL_JHARKHAND);
			}else{
				$to			= array(SEND_QUERY_EMAIL);
			}
			 //SEND_QUERY_EMAIL;
			$subject	= "Project Query";
			$email 		= new Email('default');
		 	$email->profile('default');

			$email->viewVars(array('project_detail' => $projectDetail, 'installer_list' => $installerList));			
			$email->template('send_query', 'default')
				->emailFormat('html')
				->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
			    ->to($to)
			    ->subject(Configure::read('EMAIL_ENV').$subject)
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
	 * Author : Yugtia Technologies Pvt. Ltd.
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
	* Author : Yugtia Technologies Pvt. Ltd.
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
						->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
					    ->to($to)
					    ->subject(Configure::read('EMAIL_ENV').$subject)
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
	* Author : Yugtia Technologies Pvt. Ltd.
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
	* Author : Yugtia Technologies Pvt. Ltd.
	*/
	public function getInstallerProjectLead()
	{
		$this->autoRender = false;
		$this->SetVariables($this->request->data);
		$cus_id			= $this->ApiToken->customer_id;
		$customerData   = $this->Customers->find('all', array('conditions'=>array('id'=>$cus_id)))->first();
  		$installer_id   = (isset($customerData['installer_id'])?$customerData['installer_id']:0);
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
	* Author : Yugtia Technologies Pvt. Ltd.
	*/
	public function changeInstallerProjectStatus()
	{ 
		$this->autoRender = false;
		$this->SetVariables($this->request->data);
		$cus_id			= $this->ApiToken->customer_id;
		$customerData   = $this->Customers->find('all', array('conditions'=>array('id'=>$cus_id)))->first();
  		$installer_id   = (isset($customerData['installer_id'])?$customerData['installer_id']:0);
		$insLeadData 	= array();

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
	* Author : Yugtia Technologies Pvt. Ltd.
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
	* Author : Yugtia Technologies Pvt. Ltd.
	*/
	public function updateInstallerDetail()
	{ 
		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		$cus_id			= $this->ApiToken->customer_id;
		$customerData   = $this->Customers->find('all', array('conditions'=>array('id'=>$cus_id)))->first();
  		$installer_id   = (isset($customerData['installer_id'])?$customerData['installer_id']:0);
		
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

	/**
	*
	* installerlist
	*
	* Behaviour : public
	*
	* @defination : Method is used to find installer by keyword.
	*
	* Author : Yugtia Technologies Pvt. Ltd.
	*/
	public function installerlist($char='')
	{
		$this->autoRender 	= false;
		$result 			= $this->Installers->installerlist($char);
		$data 				= array();
		if(!empty($result))
		{	
			foreach ($result as $key => $val) {
				$name = $val . '|' . $key;
				array_push($data, $name);
			}
		}
		echo json_encode($data);
	}
	
	/**
	 *
	 * sendMailToCustomer
	 *
	 * Behaviour : Public
	 *	
	 * Parameter : projectId(int)
	 *
	 * @defination : Method is use to generate solar pdf report and send to customer.
	 *
	 */
	public function sendMailToCustomer($projectId='')
	{
		ini_set('max_execution_time', 300);
		$this->layout = false;
		$projectId = $projectId;
		
		/* Get project details */
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
			            	'conditions' => ['Projects.id' => $projectId],
			            	'order' => array('Projects.id' => 'DESC')])
							->autoFields(true)->first();
							
		/* Get all project installers */
		$projectInstallers 	= $this->InstallerProjects->find('all',['join'=>[
					        'installers' => [
					            'table' => 'installers',
					            'type' => 'LEFT',
					            'conditions' => ['InstallerProjects.installer_id = installers.id']
			            	]],
			            	'fields' => array('installers.installer_name','installers.address','installers.city','installers.state','installers.contact','installers.contact1'),
			            	'conditions' => ['InstallerProjects.project_id' => $projectId],
			            	'order' => array('installers.installer_name' => 'ASC')])->toArray();
		
		$ProjectEstimation 				 = $this->getProjectEstimationByPID($project->id);
		$solar_ratio 					 = $ProjectEstimation['solar_ratio'];
		$project->estimated_saving_month = $ProjectEstimation['saving_month'];
		$project->estimated_cost 		 = $ProjectEstimation['est_cost'];
		$project->estimated_cost_subsidy = $ProjectEstimation['est_cost_subsidy'] > 0?($ProjectEstimation['est_cost_subsidy']*100000):0;


		/* Generate map URL based on project location */
		$latLng = $project->latitude.",".$project->longitude;
		$mapUrl = 'https://maps.googleapis.com/maps/api/staticmap?center='.$latLng.'&maptype=hybrid&zoom=10&size=272x378&markers=color:blue%7C'.$latLng.'&sensor=false';
		$mapImage = file_get_contents($mapUrl);
		$mapImage = 'data:image/png;base64,' . base64_encode($mapImage);

		/* Radiation Graph Generate */
		$radiationGraphArr 	= $this->Projects->getSolarRediationGHIChartData($project->latitude,$project->longitude);
		$radiationGraphData['radiation_ghi_data'] = (!empty($radiationGraphArr['radiation_ghi_data'])?$radiationGraphArr['radiation_ghi_data']:array());
		if (!empty($radiationGraphData['radiation_ghi_data'])) {
			$radiationGraphImg = $this->radiationGraph($radiationGraphData);
		} else {
			$radiationGraphImg = "";
		}

		/* Energy and Month Saving Data */
		$solarRediationData 	= $this->GhiData->getGhiData($project->longitude,$project->latitude);
		$energyAndSavingDataArr = $this->Projects->getMonthEnergyAndSavingData($solarRediationData,$project->recommended_capacity,$project->avg_monthly_bill,$project->estimated_kwh_year);
		
		/* Solar PV Chart Data */
		$monthSavinData 	= (!empty($energyAndSavingDataArr['saving_data'])?$energyAndSavingDataArr['saving_data']:array());
		$monthly_saving 	= array_sum($monthSavinData);
		$estimated_cost_subsidy = isset($project->estimated_cost_subsidy)?round(($project->estimated_cost_subsidy/100000),2):$project->estimated_cost;
		$payBackGraphData 	= $this->Projects->GetPaybackChartData($estimated_cost_subsidy, $monthly_saving);
		if (!empty($payBackGraphData)) {
			$paybackGraphImg 	= $this->paybackGraph($payBackGraphData);
		} else {
			$paybackGraphImg 	= "";
		}

		/* Get Environment Benefit Data. */
		$inpdataArr['recommendedCapacity'] = $project->estimated_kwh_year;
		$inpdataArr['estimatedKWHYear'] = $project->recommended_capacity;
		$environmentData = $this->Projects->calculateSolarPowerGreenSavingsData($inpdataArr);
		
		/* Get PDF Report id. */
		$projectData 	 = array();
		$projectReportId = $this->Projects->GetProjectPDFReportId($projectId);
		$hideInstaller   = 0;
		$this->set(compact("project","projectData","projectInstallers","mapImage","radiationGraphImg","paybackGraphImg","radiationGraphData","energyAndSavingDataArr","projectReportId","environmentData","hideInstaller","monthly_saving","solar_ratio","ProjectEstimation"));
		$this->set('pageTitle','Project');

		/* Generate PDF for estimation of project */
		require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
		$dompdf = new Dompdf($options = array());
		$dompdf->set_base_path(SITE_ROOT_DIR_PATH.'/pdf/');
		$dompdf->set_option('defaultFont', "Helvetica");
		$html = $this->render('/Admin/Installers/project_estimation');
		//exit($html);
	  	$dompdf->loadHtml($html);
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();
		// Output the generated PDF to Browser
		//$dompdf->stream("project_estimation");
		$output = $dompdf->output();
		$pdfPath = SITE_ROOT_DIR_PATH.'/tmp/report-'.$projectReportId.'.pdf';
    	file_put_contents($pdfPath, $output);
		//exit;
		$to		= $project->customer['email'];
		//$to			= 'khushal@yugtia.com';
		$subject	= "Project Query Report";
		$email 		= new Email('default');
	 	$email->profile('default');

		$email->viewVars(array('project_detail' => $project, 'installer_list' => $projectInstallers));			
		$email->template('send_query_report', 'default')
				->emailFormat('html')
				->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
			    ->to($to)
			    ->attachments($pdfPath)
			    ->subject(Configure::read('EMAIL_ENV').$subject)
			    ->send();
	}

	/**
	 *
	 * getProjectEstimationByPID
	 *
	 * Behaviour : public
	 *
	 * @defination : Method is use to add new admin user and provide specific righs using admin interface
	 *
	 */
	public function getProjectEstimationByPID($project_id=0)
	{
		$this->autoRender 	= false;
		$projectData 		= array(); 
		$result 			= array(); 
		if(!empty($project_id)) {
			$projectData 										= $this->Projects->get($project_id);
			$this->request->data['latitude']					= (isset($projectData['latitude'])?$projectData['latitude']:0);
			$this->request->data['Projects']['latitude']		= (isset($projectData['latitude'])?$projectData['latitude']:0);
			$this->request->data['longitude']					= (isset($projectData['longitude'])?$projectData['longitude']:0);
			$this->request->data['Projects']['longitude']		= (isset($projectData['longitude'])?$projectData['longitude']:0);
			$this->request->data['customer_type']				= (isset($projectData['customer_type'])?$projectData['customer_type']:0);
			$this->request->data['project_type']				= (isset($projectData['customer_type'])?$projectData['customer_type']:0);
			$this->request->data['Projects']['customer_type']	= (isset($projectData['customer_type'])?$projectData['customer_type']:0);
			$this->request->data['area']						= (isset($projectData['area'])?$projectData['area']:0);
			$this->request->data['Projects']['area']			= (isset($projectData['area'])?$projectData['area']:0);
			$this->request->data['area_type'] 					= (isset($projectData['area_type'])?$projectData['area_type']:0);
			$this->request->data['bill'] 						= (isset($projectData['avg_monthly_bill'])?$projectData['avg_monthly_bill']:0);
			$this->request->data['avg_monthly_bill'] 			= (isset($projectData['avg_monthly_bill'])?$projectData['avg_monthly_bill']:0);
			$this->request->data['backup_type']					= (isset($projectData['backup_type'])?$projectData['backup_type']:0);
			$this->request->data['usage_hours']					= (isset($projectData['usage_hours'])?$projectData['usage_hours']:0);
			$this->request->data['usage_hours']					= (isset($projectData['usage_hours'])?$projectData['usage_hours']:0);
			$this->request->data['Projects']['usage_hours']		= (isset($projectData['usage_hours'])?$projectData['usage_hours']:0);
			$this->request->data['energy_con']					= (isset($projectData['estimated_kwh_year'])?$projectData['estimated_kwh_year']:0);
			$this->request->data['Projects']['estimated_kwh_year'] = (isset($projectData['estimated_kwh_year'])?$projectData['estimated_kwh_year']:0);
			$result = $this->Projects->getprojectestimation($this->request->data);
		}
		return $result;
	}

	/**
	 *
	 * radiationGraph
	 *
	 * Behaviour : Public
	 *	
	 * Parameter : gData(int)
	 *
	 * @defination : Method is use to generate solar radiation GHI graph.
	 *
	 */
	public function radiationGraph($gData = array())
	{
		require_once(ROOT . DS  . 'vendor' . DS  . 'jpgraph' . DS . 'src' . DS . 'jpgraph.php');
		require_once(ROOT . DS  . 'vendor' . DS  . 'jpgraph' . DS . 'src' . DS . 'jpgraph_bar.php');		
 		
 		$ydata = (isset($gData['radiation_ghi_data'])?array_values($gData['radiation_ghi_data']):array());
		// Create the graph. 
		$graph = new \Graph(550,350,'auto');
		$graph->img->SetMargin(30,90,40,50);
		$graph->title->Set("Monthly Solar Radiation GHI (kW h/m2)");
		$graph->SetScale("textlin");
		$graph->SetBox(false);

		//$graph->ygrid->SetColor('gray');
		$graph->ygrid->Show(false);
		$graph->ygrid->SetFill(false);
		
		$graph->yaxis->HideLine(false);
		$graph->yaxis->HideTicks(false,false);

		// For background to be gradient, setfill is needed first.
		$graph->SetBackgroundGradient('#FFFFFF', '#FFFFFF', GRAD_HOR, BGRAD_PLOT);

		// Create the bar plots
		$barplot = new \BarPlot($ydata);
		$graph->Add($barplot);
		$barplot->SetWeight(0);
		$barplot->SetFillGradient("#71BF57","#71BF57",GRAD_HOR);
		$barplot->SetWidth(17);

		// Display the graph
		$filepath = WWW_ROOT."/tmp/radiation".time().".png";
		$graphData = $graph->Stroke($filepath); 
		return $filepath;
	}

	/**
	 *
	 * paybackGraph
	 *
	 * Behaviour : Public
	 *	
	 * Parameter : gData(int)
	 *
	 * @defination : Method is use to generate payback graph.
	 *
	 */
	public function paybackGraph($gData = array())
	{
		require_once(ROOT . DS  . 'vendor' . DS  . 'jpgraph' . DS . 'src' . DS . 'jpgraph.php');
		require_once(ROOT . DS  . 'vendor' . DS  . 'jpgraph' . DS . 'src' . DS . 'jpgraph_scatter.php');		
 		
		$ydata = (isset($gData)?array_values($gData):array());
		$xdata = (isset($gData)?array_keys($gData):array());

		// Create the graph. 
		$graph = new \Graph(700,350,'auto');
		$graph->img->SetMargin(80,90,40,50);
		$graph->title->Set("");
		$graph->SetScale("intlin");
		$graph->SetShadow();
		$graph->SetBox(false);
		$graph->title->Set("Solar Payback");
		$graph->xaxis->SetPos("min"); 

		$graph->yaxis->SetLabelMargin(12);
		$graph->xaxis->SetLabelMargin(6);
		$graph->xaxis->SetTickLabels($xdata);
		$graph->yaxis->SetTickSide(SIDE_LEFT);
		$graph->xaxis->SetTickSide(SIDE_DOWN);
		// For background to be gradient, setfill is needed first.
		$graph->SetBackgroundGradient('#FFFFFF', '#FFFFFF', GRAD_HOR);

		$lineplot = new \ScatterPlot($ydata);
		$lineplot->mark->SetType(MARK_SQUARE);
		$lineplot->mark->SetFillColor("#FFCB29");
		$lineplot->SetImpuls();
		$lineplot->SetColor("#71BF57");
		$lineplot->SetWeight(6);
		$lineplot->mark->SetWidth(10);
		$graph->Add($lineplot);
		
		// Display the graph
		$filepath = WWW_ROOT."/tmp/payback".time().".png";
		$graphData = $graph->Stroke($filepath);
		return $filepath;
	}

	/**
	 * dashboard
	 * Behaviour : Public
	 * @defination : Method is use to generate installerwise dashboard.
	 */
	public function dashboard()
    {
        $pageTitle          = "Dashboard";
        $this->setCustomerArea();
        $main_branch_id     = '';
        $customers_id       = $this->Session->read("Customers.id");
        if(!empty($customers_id))
        {
	        $customer_type 	    = $this->Session->read('Customers.customer_type');
	        $customerSession    = $this->Session->read('Customers');
	        $Customer 			= $this->Customers->get($customers_id);
	        $InstallerID 		= $Customer->installer_id;
	        $main_branch_id     = array("field"=>"installer_id","id"=>$InstallerID,"member_type"=>$this->ApplyOnlines->JREDA);

	        $this->set('getProjectClusterData',$this->getProjectClusterData($InstallerID));

	        $IndividualStatus = array($this->ApplyOnlineApprovals->DOCUMENT_NOT_VERIFIED,$this->ApplyOnlineApprovals->APPLICATION_CANCELLED);
	        
	        $this->set('GrandTotalApplicationSubmitted',$this->ApplyOnlines->TotalApplicationByStatus($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->APPLICATION_SUBMITTED));
	        $this->set('GrandTotalSubmittedPVCapacity',$this->ApplyOnlines->TotalApplicationPVCapacity($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->APPLICATION_SUBMITTED));

	        /** INSTALLER WISE DATA STARTS */
	        $main_branch_id     = array("field"=>"installer_id","id"=>$InstallerID,"member_type"=>"INSTALLER");

	        $this->set('TotalApplicationSubmitted',$this->ApplyOnlines->TotalApplicationByStatus($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->APPLICATION_SUBMITTED));
	        $this->set('TotalSubmittedPVCapacity',$this->ApplyOnlines->TotalApplicationPVCapacity($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->APPLICATION_SUBMITTED));

	        $this->set('TotalApplicationGEDALetter',$this->ApplyOnlines->TotalApplicationByStatus($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->APPROVED_FROM_GEDA));
	        $this->set('TotalGEDALetterPVCapacity',$this->ApplyOnlines->TotalApplicationPVCapacity($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->APPROVED_FROM_GEDA));

	        $main_branch_id     = array("field"=>"installer_id","id"=>$InstallerID,"member_type"=>"INSTALLER");

	        $this->set('TotalApplicationVerified',$this->ApplyOnlines->TotalApplicationByStatus($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->DOCUMENT_VERIFIED));
	        $this->set('TotalVerifiedPVCapacity',$this->ApplyOnlines->TotalApplicationPVCapacity($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->DOCUMENT_VERIFIED));
	        
	        $this->set('TotalApplicationNotVerified',$this->ApplyOnlines->TotalApplicationCountByStatus($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->DOCUMENT_NOT_VERIFIED,$IndividualStatus));
	        $this->set('TotalNotVerifiedPVCapacity',$this->ApplyOnlines->TotalApplicationPVCapacityByStatus($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->DOCUMENT_NOT_VERIFIED,$IndividualStatus));
	        
	        $this->set('TotalApplicationRejected',$this->ApplyOnlines->TotalApplicationCountByStatus($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->APPLICATION_CANCELLED,$IndividualStatus));
	        $this->set('TotalRejectedPVCapacity',$this->ApplyOnlines->TotalApplicationPVCapacityByStatus($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->APPLICATION_CANCELLED,$IndividualStatus));

	        $this->set('TotalApplicationMeterInstalled',$this->ApplyOnlines->TotalApplicationByStatus($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->METER_INSTALLATION,$IndividualStatus));
	        $this->set('TotalMeterInstalledPVCapacity',$this->ApplyOnlines->TotalApplicationPVCapacity($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->METER_INSTALLATION,$IndividualStatus));

	        $this->set('TotalApplicationNonSubsidy',$this->ApplyOnlines->TotalApplicationBySubsidy($customerSession['state'],$main_branch_id,'1'));
	        $this->set('TotalNonSubsidyInstalledPVCapacity',$this->ApplyOnlines->TotalApplicationNonSubsidyPVCapacity($customerSession['state'],$main_branch_id,'1'));

	        $this->set('TotalApplicationPCR',$this->ApplyOnlines->TotalApplicationByPCR($customerSession['state'],$main_branch_id));
        	$this->set('TotalPCRInstalledPVCapacity',$this->ApplyOnlines->TotalApplicationPcrPVCapacity($customerSession['state'],$main_branch_id));
        	
        	$this->set('TotalApplicationSocial',$this->ApplyOnlines->TotalApplicationBySocial($customerSession['state'],$main_branch_id));
        	$this->set('TotalSocialInstalledPVCapacity',$this->ApplyOnlines->TotalApplicationSocialPVCapacity($customerSession['state'],$main_branch_id));


        	$this->set('TotalApplicationResidential',$this->ApplyOnlines->TotalApplicationByCategory($customerSession['state'],$main_branch_id,$this->ApplyOnlines->category_residental));
	        $this->set('TotalResidentialPVCapacity',_FormatGroupNumberV2($this->ApplyOnlines->TotalApplicationCategoryCapacity($customerSession['state'],$main_branch_id,$this->ApplyOnlines->category_residental)));

	        $this->set('TotalApplicationInsCom',$this->ApplyOnlines->TotalApplicationByCategory($customerSession['state'],$main_branch_id,$this->ApplyOnlines->category_industrial)+$this->ApplyOnlines->TotalApplicationByCategory($customerSession['state'],$main_branch_id,$this->ApplyOnlines->category_commercial));
	        $InsCapacity   = $this->ApplyOnlines->TotalApplicationCategoryCapacity($customerSession['state'],$main_branch_id,$this->ApplyOnlines->category_industrial);
	        $ComCapacity    = $this->ApplyOnlines->TotalApplicationCategoryCapacity($customerSession['state'],$main_branch_id,$this->ApplyOnlines->category_commercial);
	        $this->set('TotalInsComPVCapacity',_FormatGroupNumberV2($InsCapacity+$ComCapacity));

	        $this->set('TotalApplicationHT',$this->ApplyOnlines->TotalApplicationByCategory($customerSession['state'],$main_branch_id,$this->ApplyOnlines->category_ht_indus));
	        $this->set('TotalHTPVCapacity',_FormatGroupNumberV2($this->ApplyOnlines->TotalApplicationCategoryCapacity($customerSession['state'],$main_branch_id,$this->ApplyOnlines->category_ht_indus)));

	        $this->set('TotalApplicationOthers',$this->ApplyOnlines->TotalApplicationByCategory($customerSession['state'],$main_branch_id,$this->ApplyOnlines->category_others));
	        $this->set('TotalOthersPVCapacity',_FormatGroupNumberV2($this->ApplyOnlines->TotalApplicationCategoryCapacity($customerSession['state'],$main_branch_id,$this->ApplyOnlines->category_others)));

	        $this->set('TotalApplicationInspection',$this->ApplyOnlines->TotalApplicationByInspection($customerSession['state'],$main_branch_id));
        	$this->set('TotalInspectionPVCapacity',$this->ApplyOnlines->TotalApplicationInspectionCapacity($customerSession['state'],$main_branch_id));

	        /** INSTALLER WISE DATA ENDS */
	        
	        $CHART_HEADER           = "'Month', 'Application Submitted', 'Registration', 'Documents Verified', 'Docs. Verification Pending','Application Cancelled','Meter Installed'";
	        $arrStatus              = array($this->ApplyOnlineApprovals->APPLICATION_SUBMITTED,
	                                        $this->ApplyOnlineApprovals->APPROVED_FROM_GEDA,
	                                        $this->ApplyOnlineApprovals->DOCUMENT_VERIFIED,
	                                        $this->ApplyOnlineApprovals->DOCUMENT_NOT_VERIFIED,
	                                        $this->ApplyOnlineApprovals->APPLICATION_CANCELLED,
	                                        $this->ApplyOnlineApprovals->METER_INSTALLATION);
	        $MonthWiseStatistics    = $this->ApplyOnlines->MonthwiseApplicationStatistics($customerSession['state'],$main_branch_id,$arrStatus,$IndividualStatus);
	        $this->set('CHART_HEADER',$CHART_HEADER);
	        $this->set('arrStatusCode',$this->ApplyOnlines->arrDashboardBlocks);
	        $this->set('MonthWiseStatistics',$MonthWiseStatistics);
	        $this->set(compact('pageTitle','customerSession'));
	    }
    }
    
   /**
    * Function Name : getProjectClusterData
    * @param integer $InstallerID
    * @return
    * @author Kalpak Prajapati
    */
    public function getProjectClusterData($InstallerID=0)
    {
        $arrResult['data']      = array();
        $arrResult['map_style'] = '';
        $arrResult['map_icons'] = '';
        $resultArray            = array();
        $TypewiseProjects       = $this->Projects->find();
        $TypewiseProjects->hydrate(false);
        $TypewiseProjects->select(['Parameters.para_value','Projects.customer_type','Projects.latitude','Projects.longitude']);
        $TypewiseProjects->join(    [
                                        [
                                            'table' => 'parameters',
                                            'alias' => 'Parameters',
                                            'type' => 'INNER',
                                            'conditions' => 'Parameters.para_id = Projects.customer_type',
                                        ],
                                        [
                                            'table' => 'apply_onlines',
                                            'alias' => 'ApplyOnlines',
                                            'type' => 'INNER',
                                            'conditions' => 'ApplyOnlines.project_id = Projects.id',
                                        ]
                                    ]
                                );
        $TypewiseProjects->where(['ApplyOnlines.application_status NOT IN '=> array(22,29,30,99),'ApplyOnlines.application_status > ' => 0,'ApplyOnlines.installer_id'=>intval($InstallerID)]);
        $arrResult      = $TypewiseProjects->toList();
        $map_icons      = array();
        $map_style      = array();
        $Counter        = 0;
        $Prev_Group_Id  = 0;
        if (!empty($arrResult)) {
            foreach ($arrResult as $Row) {
                $TAG = preg_replace("/[^0-9a-z]/i","",strtolower($Row['Parameters']['para_value']));
                if (!isset($map_icons[$Row['customer_type']])) {
                    $COLOR_CODE     = $this->random_color_marker($map_style);
                    array_push($map_style,$COLOR_CODE);
                    $map_icons[$Row['customer_type']]   = array("group"=>$Row['Parameters']['para_value'],
                                                                "lbl"=>$TAG,
                                                                "count"=>0,
                                                                "icon"=>$COLOR_CODE);
                    
                    if ($Prev_Group_Id > 0) {
                        $map_icons[$Prev_Group_Id]['count'] = $Counter;
                        $Counter = 0;
                    }
                    $Prev_Group_Id = $Row['customer_type'];
                }
                $arrResult['data'][] = array(   "lat"=>$Row['latitude'],
                                                "lng"=>$Row['longitude'],
                                                "options"=>array("icon"=>"/img/mapIcons/pins/".$map_icons[$Row['customer_type']]['icon']),
                                                "tag"=>$TAG);
                
                $Counter++;
            }
            if ($Prev_Group_Id > 0) {
                $map_icons[$Prev_Group_Id]['count'] = $Counter;
                $Counter = 0;
            }
        }
        $arrResult['map_icons'] = $map_icons;
        return $arrResult;
    }

    /**
    * Function Name : random_color_marker
    * @param integer $map_icons
    * @return
    * @author Kalpak Prajapati
    */
    private function random_color_marker($map_icons)
    {
        $found      = true;
        $counter    = 0;
        $img_name   = "pin56_[RAND_NUM].png";
        while ($found) {
            $RAND_NUM   = rand(0,21);
            $color_name = str_replace("[RAND_NUM]",$RAND_NUM,$img_name);
            if (!in_array($color_name,$map_icons)) {
                $found = false;
            }
            if ($counter == 5) {
                $found = false;
            }
            $counter++;
        }
        return $color_name;
    }
	/**
	* Function Name : registration
	* @param 
	* @return
	*/
	public function registration()
	{
		$pageTitle = "Registration";
		$this->set(compact('user','pageTitle'));
	}
	/**
	 *
	 * new_registration
	 *
	 * Behaviour : public
	 *
	 * @param : 
	 * @defination : Method is use to list installer list payment done but approval pending
	 *
	 */
	public function new_registration()
	{
		$this->setMemberArea();
		$member_type 				= $this->Session->read('Members.member_type');
		$member_id 					= $this->Session->read("Members.id");
		$ses_customer_type 			= $this->Session->read('Members.member_type');
		$is_installer 				= false;
		$ALLOWED_APPROVE_GEDAIDS    = $this->ApplyOnlines->ALLOWED_APPROVE_GEDAIDS;
		$newInstallerRegistration   = ($member_id > 0 && in_array($member_id,$ALLOWED_APPROVE_GEDAIDS))?true:false;
		
		if(empty($member_id) || !$newInstallerRegistration) {
			return $this->redirect(URL_HTTP);
		}
		$from_date 				= isset($this->request->data['DateFrom'])?$this->request->data['DateFrom']:'';
		$end_date 				= isset($this->request->data['DateTo'])?$this->request->data['DateTo']:'';
		$request_status 		= isset($this->request->data['status'])?$this->request->data['status']:'';
		$request_no 			= isset($this->request->data['request_no'])?$this->request->data['request_no']:'';
		$geda_approval_status 	= isset($this->request->data['geda_approval_status'])?$this->request->data['geda_approval_status']:'';
		$installer_name 		= isset($this->request->data['installer_name'])?$this->request->data['installer_name']:'';
		$arrRequestList			= array();
		$arrCondition			= array();
		$download_excel			= isset($this->request->data['download']) ? $this->request->data['download'] : 0;
		$receipt_no 			= isset($this->request->data['receipt_no']) ? $this->request->data['receipt_no'] : '';
	       
		
		//$arrCondition['ApplyOnlines.pcr_submited IS '] 			= NULL;

		$this->SortBy		= "Installers.id";
		$this->Direction	= "DESC";
		$this->intLimit		= PAGE_RECORD_LIMIT;
		$this->CurrentPage  = 1;
		$option 			= array();
		$memberApproved 	= '0';
		
		$memberApproved 	= in_array($member_id, $this->ApplyOnlines->ALLOWED_APPROVE_GEDAIDS) ? '1' : '0';
		
		$option['colName']  = array('id','installer_name','city','payment','geda_approval','created','modified','action');
			
		$sortArr 			= array('id'			=> 'Installers.id',
									'installer_name'=> 'Installers.installer_name',
									'city' 			=> 'Installers.city',
									'payment'		=> (INSTALLER_PAYMENT_FEES + INSTALLER_GST_AMOUNT),
									'geda_approval'=> 'geda_approval',
									'created'		=> 'Installers.created',
									'modified'		=> 'Installers.modified');
		$this->SetSortingVars('Installers',$option,$sortArr);

		$option['dt_selector']			='table-example';
		$option['formId']				='formmain';
		$option['url']					= '';
		$option['recordsperpage']		= PAGE_RECORD_LIMIT;
		//$option['allsortable']			= '-1';
		$option['total_records_data']	= 0;
		$option['bPaginate']			= 'true';
		$option['bLengthChange']		= 'false';
		$option['order_by'] 			= "order : [[0,'DESC']]";
		$JqdTablescr 					= $this->JqdTable->create($option);
		$Joins 							= array();
		$CountFields	= array('UpdateDetails.id');
		$Fields 		= array('Installers.id',
								'Installers.installer_name',
								'Installers.city',
								'payment'=>(INSTALLER_PAYMENT_FEES + INSTALLER_GST_AMOUNT),
								'Installers.payment_status',
								'Installers.created',
								'Installers.modified',
								'Installers.geda_approval',
								'Installers.company_id',
								'Installers.e_invoice_url',
								);
		
		//$arrCondition['Installers.status'] 						= 0;
		$arrCondition['Installers.payment_status'] 				= 1;
		if ($installer_name != '') {
			$arrCondition['Installers.installer_name LIKE '] 	= '%'.$installer_name.'%';
		}
		if ($geda_approval_status != '') {
			$arrCondition['Installers.geda_approval'] 			= $geda_approval_status;
		}
		$this->paginate['limit'] 	= $this->intLimit;
		$this->paginate['page'] 	= $this->CurrentPage;
		if(!empty($receipt_no))
		{
			array_push($Joins,['table'=>'installer_payment','alias'=>'installer_payment','type'=>'left','conditions'=>'installer_payment.installer_id = Installers.id']);
			array_push($arrCondition,array('installer_payment.payment_status' => 'success','installer_payment.receipt_no like ' => '%'.$receipt_no.'%' ));
		}
		$query_data 	= $this->Installers->find('all',array(	'fields'		=> $Fields,
																'conditions' 	=> $arrCondition,
																'join' 			=> $Joins,
																'order'			=> array($this->SortBy=>$this->Direction)));


		if(!empty($from_date) && !empty($end_date))
		{
			$fields_date  	= "Installers.created";
			$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
			$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
			$query_data->where([function ($exp, $q) use ($StartTime, $EndTime,$fields_date) {
				return $exp->between($fields_date, $StartTime, $EndTime);
			}]);
		}
		
		$query_data_count 	= $this->Installers->find('all',array('fields'		=> $CountFields,
																	'conditions' 	=> $arrCondition,
																	'join' 			=> $Joins,
														));
		if(!empty($from_date) && !empty($end_date))
		{
			$fields_date  	= "Installers.created";
			$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
			$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
			$query_data_count->where([function ($exp, $q) use ($StartTime, $EndTime,$fields_date) {
				return $exp->between($fields_date, $StartTime, $EndTime);
			}]);
		}
		if ($this->request->is('ajax') && $download_excel==0)
		{
			
			$total_query_records	= $query_data_count->count();
			$start_page 			= isset($this->request->data['start']) ? $this->request->data['start'] : 1;
			$this->paginate['limit']= PAGE_RECORD_LIMIT;
			$this->paginate['page']	= ($start_page/$this->paginate['limit'])+1;
			if(isset($this->request->data['page_no']) && !empty($this->request->data['page_no']))
			{
				$posible_page 				= $total_query_records/$this->paginate['limit'];
				if($posible_page < $this->request->data['page_no']) {
					$this->paginate['page'] = $posible_page;
				} else {
					$this->paginate['page'] = $this->request->data['page_no'];
				}
			}
			else
			{
				$this->paginate['page'] 	= ($start_page/$this->paginate['limit'])+1;
			}


			$arrRequestList	= $this->paginate($query_data);
			$out 			= array();
			$counter 		= 1;
			$page_mul 		= ($this->CurrentPage-1);
			foreach($arrRequestList->toArray() as $key=>$val)
			{
				$temparr 	= array();
				foreach($option['colName'] as $key) {
					if($key=='id') {
						$temparr[$key]=$counter + ($page_mul * $this->paginate['limit']);
					}
					else if($key=='created') {
						$temparr[$key]	= date('m-d-Y H:i a',strtotime($val->created));
					}
					else if($key=='modified') {
						$temparr[$key]	= date('m-d-Y H:i a',strtotime($val->modified));
					}
					else if($key=='installer_name') {
						$temparr[$key]	= '<a href="/installer-registration/'.encode($val->company_id).'" target="_blank">'.$val->installer_name.'</a>';
					}
					else if($key=='geda_approval') {
						$temparr[$key]=($val->geda_approval == 1) ? 'Approved' : (($val->geda_approval == 2) ? 'Query Raised' : (($val->geda_approval == 3) ? 'Installer Replied' : 'Pending'));
					}
					else if($key=='action') {
						
							$temparr[$key]	= '';
						
							if($val->geda_approval != 1) {
								$temparr[$key]	= '<a href="javascript:;" class="dropdown-item SubmitRequest approve_Status" data-id="'. encode($val->id) .'"><i class="fa fa-check-square-o" aria-hidden="true"></i> Approve</a>';
							}
							$temparr[$key]	.= '<a href="/Installers/payment_receipt/'. encode($val->id) .'" target="_blank" class="dropdown-item">
												<i class="fa fa-download"></i> Download Receipt
											</a>';
							if(isset($val->e_invoice_url) && !empty($val->e_invoice_url)) {
								$temparr[$key]	.= '<a href="'.$val->e_invoice_url.'" target="_blank" class="dropdown-item">
												<i class="fa fa-download"></i> Download E-invoice
											</a>';
							}
							
							$temparr[$key]	.= '<a href="/installer-registration/'.encode($val->company_id).'" target="_blank" class="dropdown-item">
												<i class="fa fa-eye"></i> View Details
											</a>';
						
						$temparr['action']	= '	<span class="action-row action-btn">
													<div class="dropdown">
														<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
															Actions <i class="fa fa-chevron-down"></i>
														</button>
														<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">'.$temparr['action'].'</div>
													</div>
												</span>';
					}
					else if (isset($val[$key])) {
						$temparr[$key]	= $val[$key];
					} else {
						$temparr[$key]	= "-";
					}
				}
				$counter++;
				$out[] = $temparr;
			}
			header('Content-type: application/json');
			echo json_encode(array(	"draw" 				=> intval($this->request->data['draw']),
									"recordsTotal"    	=> intval($this->request->params['paging']['Installers']['count']),
									"recordsFiltered" 	=> intval($this->request->params['paging']['Installers']['count']),
									"data"            	=> $out));
			die;
		}
		if($download_excel == 1) {
			
			$PhpExcel 			= $this->PhpExcel;
			$PhpExcel->createExcel();
			$objDrawing 		= new \PHPExcel_Worksheet_MemoryDrawing();
			$objDrawing->setCoordinates('A1');
			$objDrawing->setWorksheet($PhpExcel->getExcelObj()->getActiveSheet());
			$j 					= 1;
			$i 					= 1;
			$arrReportFields 	= array('sr_no'			=> "Sr no",
										'installer_name'=> 'Installer Name',
										'email'			=> "Email",
										'city'			=> "City",
										'payment' 		=> "Payment",
										'payment_status'=> "Payment Status",
										'created'		=> "Created Date"
										);
			
			//$arrSecond 			= array('ticket_closed'=>'Ticket Closed','last_response'=>'Last Response','2nd_last_response'=> '2nd Last Response','3rd_last_response'=> '3rd Last Response','4th_last_response'=> '4th Last Response','5th_last_response'=> '5th Last Response');
			foreach ($arrReportFields as $key=>$Field_Name) {
				$RowName 	= $this->FeesReturn->GetExcelColumnName($i);

				$ColTitle  	= $Field_Name;
				$PhpExcel->writeCellValue($RowName.$j,$ColTitle);
				$PhpExcel->getExcelObj()->getActiveSheet()->getColumnDimension($RowName)->setAutoSize(true);
				$i++;
			}

			$j++;
			$i = 1;
			
			
			$InsData 	= $query_data->toArray();
			
			if(!empty($InsData)){
				foreach($InsData as $key=>$val) {

					$RowName = $this->FeesReturn->GetExcelColumnName($i);
					$PhpExcel->writeCellValue($RowName.$j,$j-1);
					$i++;
					$RowName = $this->FeesReturn->GetExcelColumnName($i);
					$PhpExcel->writeCellValue($RowName.$j,$val->installer_name);
					$i++;
					$RowName = $this->FeesReturn->GetExcelColumnName($i);
					$PhpExcel->writeCellValue($RowName.$j,$val->email);
					$i++;
					$RowName = $this->FeesReturn->GetExcelColumnName($i);
					$PhpExcel->writeCellValue($RowName.$j,$val->city);
					$i++;
					$RowName = $this->FeesReturn->GetExcelColumnName($i);
					$PhpExcel->writeCellValue($RowName.$j,$val->payment);
					$i++;
					$InstallerStatus 	= ($val->geda_approval == 1) ? 'Approved' : (($val->geda_approval == 2) ? 'Query Raised' : (($val->geda_approval == 3) ? 'Installer Replied' : 'Pending'));
					$RowName = $this->FeesReturn->GetExcelColumnName($i);
					$PhpExcel->writeCellValue($RowName.$j,$InstallerStatus);
					$i++;
					$createdDate	= date('m-d-Y H:i a',strtotime($val->created));
					$RowName = $this->FeesReturn->GetExcelColumnName($i);
					$PhpExcel->writeCellValue($RowName.$j,$createdDate);
					$i++;

					$i=1;
					$j++;
				}
			}
			$PhpExcel->downloadFile(time());
			exit;
		}
		$REQUEST_STATUS 	= array("0"=>"Pending","1"=>"Approved","2"=>"Query Raised","3"=>"Installer Replied");
		$RECEVIED_STATUS 	= array("1"=>"YES","0"=>"NO");
		
		$this->set('arrRequestList',$arrRequestList);
		$this->set('JqdTablescr',$JqdTablescr);
		$this->set('period',$this->period);
		$this->set('limit',$this->intLimit);
		$this->set("CurrentPage",$this->CurrentPage);
		$this->set("SortBy",$this->SortBy);
		$this->set("Direction",$this->Direction);
		$this->set("REQUEST_STATUS",$REQUEST_STATUS);
		$this->set("RECEVIED_STATUS",$RECEVIED_STATUS);
		$this->set("pagetitle",'New Installers - Rooftop');
		$this->set("page_count",0);
		$this->set("memberApproved",$memberApproved);
	}
	/**
	 *
	 * fetchInstaller
	 *
	 * Behaviour : public
	 *
	 * @defination : Method is use to fetch installer data.
	 *
	 */
	public function fetchInstaller()
	{
		$this->autoRender   = false;
		$response 			= '';
		$insid 				= intval(decode($this->request->data['insid']));
		$ins_fetchData 		= $this->Installers->find("all",['conditions'=>['id'=>$insid]])->first();
		if(!empty($ins_fetchData))
		{
			$latest_stage 					= $ins_fetchData->geda_approval;
			$ins_fetchData->geda_approval 	= ($ins_fetchData->geda_approval==3) ? 2 : $ins_fetchData->geda_approval;
			$response						= $ins_fetchData;
			$response->latest_stage			= $latest_stage;

		}
		echo json_encode(array('type'=>'ok','response'=>$response));
		exit;
	}
	/**
	 *
	 * ApproveRegistration
	 *
	 * Behaviour : public
	 *
	 * @defination : Method is use to approved or rejected status of installer registration.
	 *
	 */
	public function ApproveRegistration()
	{
		$this->autoRender   = false;
		$id                 = (isset($this->request->data['insid']) ? (decode($this->request->data['insid'])) : 0);
		$geda_approval    	= (isset($this->request->data['geda_approval']) ? $this->request->data['geda_approval'] : 0);
		$reject_reason    	= (isset($this->request->data['reject_reason']) ? $this->request->data['reject_reason'] : 0);


		$memberId         	= $this->Session->read("Members.id");
		$member_type 		= $this->Session->read('Members.member_type');
		$ErrorMessage 		= '';
		$success        	= 0;
		if(empty($id)) {
			$ErrorMessage   = "Invalid Request. Please validate form details.";
			$success        = 0;
		} else {
			$InstallersData  	= $this->Installers->find("all",['conditions'=>['id'=>$id]])->first();
			if (!empty($InstallersData)) {
				if ($this->request->is('post') || $this->request->is('put')) {

					$arrInstaller = $this->Installers->find('all',
						[
							'fields'=> ['Installers.id','Installers.company_id','Installers.email','customers.email','Installers.mobile','installer_passwords.password','Installers.contact_person'],
							'join'=>[
										[   'table'=>'installer_passwords',
											'type'=>'INNER',
											'conditions'=>'installer_passwords.installer_id = Installers.id'
										],
										[   'table'=>'customers',
											'type'=>'INNER',
											'conditions'=>'customers.installer_id = Installers.id'
										]
									],
							'conditions'=>['Installers.id'=>$id],
							'order'=>['Installers.id'=>'ASC']
						]
					)->first();

					if (!empty($arrInstaller))
					{
						if($geda_approval == 1) {
							//echo "\r\n--".$arrInstaller->id." -- ".$arrInstaller->email." -- ".$arrInstaller->customers['email']." -- ".$arrInstaller->mobile." -- ".$arrInstaller->installer_passwords['password']."--\r\n";
							if($InstallersData->registration_type==1) {
								$template_name	= 'installer_registration_kusum_login';
								$EmailVars 	= array( 'EMAIL_ADDRESS' => $arrInstaller->customers['email'],
													'PASSWD' 		=> $arrInstaller->installer_passwords['password'],
													'CONTACT_NAME' 	=> $arrInstaller->contact_person,
													'URL_HTTP'		=> URL_HTTP);

							} else {
								$paymentData 	= $this->InstallerSuccessPayment->find('all',array(
												'fields'	=> array('installer_payment.transaction_id'),
												'conditions'=> array('InstallerSuccessPayment.installer_id'=>$arrInstaller->id),
												'join' 		=> array(['table'=>'installer_payment','type'=>'left','conditions'=>['InstallerSuccessPayment.payment_id=installer_payment.id']]),
												'order'		=> ['InstallerSuccessPayment.id'=>'desc']))->first();
								$template_name	= 'installer_registration_login';
								$EmailVars 	= array( 'EMAIL_ADDRESS' => $arrInstaller->customers['email'],
													'PASSWD' 		=> $arrInstaller->installer_passwords['password'],
													'CONTACT_NAME' 	=> $arrInstaller->contact_person,
													'TRANSACTION_NO'=> $paymentData->installer_payment['transaction_id'],
													'URL_HTTP'		=> URL_HTTP);
							
							}
							
							$subject        = PRODUCT_NAME." Login Details";
							
							$EmailTo        = $arrInstaller->customers['email'];
							
							
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


							$Emaillog                  = $this->Emaillog->newEntity();
							$Emaillog->email           = $EmailTo;
							$Emaillog->send_date       = $this->NOW();
							$Emaillog->action          = "Installer Password Information";
							$Emaillog->description     = json_encode(array( 
									'EMAIL_ADDRESS' => $arrInstaller->customers['email'],
									'PASSWD' 		=> $arrInstaller->installer_passwords['password'],
									'CONTACT_NAME' 	=> $arrInstaller->contact_person,
									'TRANSACTION_NO'=> isset($paymentData->installer_payment['transaction_id']) ? $paymentData->installer_payment['transaction_id'] : '',
									'URL_HTTP'		=>URL_HTTP));
							$this->Emaillog->save($Emaillog);
							$this->Installers->updateAll(array('status'=>$this->Customers->STATUS_ACTIVE,'modified'=>$this->NOW()),array('id'=>$arrInstaller->id));
							$this->Customers->updateAll(array('status'=>$this->Customers->STATUS_ACTIVE),array('installer_id'=>$arrInstaller->id));
							
						}
						else if($geda_approval == 2) {
							$EmailTo        = $arrInstaller->customers['email'];
							
							$subject        = PRODUCT_NAME." Login Details";
							$EmailVars 		= array( 'CONTACT_NAME' => $arrInstaller->contact_person,
													'URL_HTTP'		=> URL_HTTP,
													'QUERY_RAISED' => $reject_reason,
													'LINK_URL' 		=> URL_HTTP.'installer-registration/'.encode($arrInstaller->company_id));
							//->bcc('pulkitdhingra@gmail.com')
									
							$email 		= new Email('default');
							$email->profile('default');
							$email->viewVars($EmailVars);
							$message_send = $email->template('installer_registration_rejection', 'default')
								->emailFormat('html')
								->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
								->to($EmailTo)
								->subject(Configure::read('EMAIL_ENV').$subject)
								->send();
							
							$member_id          					= $this->Session->read("Members.id");
							$member_type 							= $this->Session->read('Members.member_type');
							$browser 								= isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:"-";
							$InstallerMessageEntity					= $this->InstallerMessage->newEntity();
							$InstallerMessageEntity->installer_id 	= $arrInstaller->id;
							$InstallerMessageEntity->message 		= strip_tags($reject_reason);
							$InstallerMessageEntity->user_type 		= $member_type;
							$InstallerMessageEntity->user_id 		= $member_id;
							$InstallerMessageEntity->ip_address 	= $this->IP_ADDRESS;
							$InstallerMessageEntity->created 		= $this->NOW();
							$InstallerMessageEntity->browser_info 	= json_encode($browser);
							
							$this->InstallerMessage->save($InstallerMessageEntity);

							$Emaillog                  = $this->Emaillog->newEntity();
							$Emaillog->email           = $EmailTo;
							$Emaillog->send_date       = $this->NOW();
							$Emaillog->action          = "Query Raised for Installer";
							$Emaillog->description     = json_encode(array( 
									'CONTACT_NAME' 	=> $arrInstaller->contact_person,
									'URL_HTTP'		=>URL_HTTP.'installer-registration/'.encode($arrInstaller->company_id)));
							$this->Emaillog->save($Emaillog);
						}
						$ErrorMessage   	= "Registration Status Updated Sucessfully.";
						$success        	= 1;
						$this->Installers->updateAll(array('geda_approval'=>$geda_approval,
							'approved_by'=>$memberId,'reject_reason'=>$reject_reason,'modified'=>$this->NOW()),array('id'=>$arrInstaller->id));
					}
				}
			} else {
				$ErrorMessage   			= "Invalid Request. Please validate form details.";
				$success        			= 0;
			}
		}
		echo json_encode(array('message'=>$ErrorMessage,'success'=>$success));
		exit;
	}
	/**
	 *
	 * payment_receipt
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to view geda approval letter
	 *
	 */
	public function payment_receipt($id = null)
	{
		if(!empty($this->Session->read('Members.member_type')))
		{
			$customerId = $this->Session->read("Members.id");
		}
		if(empty($customerId))
		{
			return $this->redirect('/home');
		}
		$pdf_path = $this->Installers->generateInstallerReceiptPdf($id,false);
	}
	/**
	 *
	 * new_registration_kusum
	 *
	 * Behaviour : public
	 *
	 * @param : 
	 * @defination : Method is use to list installer list approval pending for kusum
	 *
	 */
	public function new_registration_kusum()
	{
		$this->setMemberArea();
		$member_type 				= $this->Session->read('Members.member_type');
		$member_id 					= $this->Session->read("Members.id");
		$ses_customer_type 			= $this->Session->read('Members.member_type');
		$is_installer 				= false;
		$ALLOWED_APPROVE_GEDAIDS    = $this->ApplyOnlines->ALLOWED_APPROVE_GEDAIDS;
		$newInstallerRegistration   = ($member_id > 0 && in_array($member_id,$ALLOWED_APPROVE_GEDAIDS))?true:false;
		
		if(empty($member_id) || !$newInstallerRegistration) {
			return $this->redirect(URL_HTTP);
		}
		$from_date 				= isset($this->request->data['DateFrom'])?$this->request->data['DateFrom']:'';
		$end_date 				= isset($this->request->data['DateTo'])?$this->request->data['DateTo']:'';
		$request_status 		= isset($this->request->data['status'])?$this->request->data['status']:'';
		$request_no 			= isset($this->request->data['request_no'])?$this->request->data['request_no']:'';
		$geda_approval_status 	= isset($this->request->data['geda_approval_status'])?$this->request->data['geda_approval_status']:'';
		$installer_name 		= isset($this->request->data['installer_name'])?$this->request->data['installer_name']:'';
		$arrRequestList			= array();
		$arrCondition			= array();
		
		//$arrCondition['ApplyOnlines.pcr_submited IS '] 			= NULL;

		$this->SortBy		= "Installers.id";
		$this->Direction	= "DESC";
		$this->intLimit		= PAGE_RECORD_LIMIT;
		$this->CurrentPage  = 1;
		$option 			= array();
		$memberApproved 	= '0';
		
		$memberApproved 	= in_array($member_id, $this->ApplyOnlines->ALLOWED_APPROVE_GEDAIDS) ? '1' : '0';
		
		$option['colName']  = array('id','installer_name','city','payment','geda_approval','created','action');
			
		$sortArr 			= array('id'			=> 'Installers.id',
									'installer_name'=> 'Installers.installer_name',
									'city' 			=> 'Installers.city',
									'payment'		=> (INSTALLER_PAYMENT_FEES + INSTALLER_GST_AMOUNT),
									'geda_approval'=> 'geda_approval',
									'created'		=> 'Installers.created');
		$this->SetSortingVars('Installers',$option,$sortArr);

		$option['dt_selector']			='table-example';
		$option['formId']				='formmain';
		$option['url']					= '';
		$option['recordsperpage']		= PAGE_RECORD_LIMIT;
		//$option['allsortable']			= '-1';
		$option['total_records_data']	= 0;
		$option['bPaginate']			= 'true';
		$option['bLengthChange']		= 'false';
		$option['order_by'] 			= "order : [[0,'DESC']]";
		$JqdTablescr 					= $this->JqdTable->create($option);
		$Joins 							= array();
		if ($this->request->is('ajax'))
		{
			$CountFields	= array('UpdateDetails.id');
			$Fields 		= array('Installers.id',
									'Installers.installer_name',
									'Installers.city',
									'payment'=>(INSTALLER_PAYMENT_FEES + INSTALLER_GST_AMOUNT),
									'Installers.payment_status',
									'Installers.created',
									'Installers.geda_approval',
									'Installers.company_id',
									);
			
			//$arrCondition['Installers.status'] 						= 0;
			//$arrCondition['Installers.payment_status'] 				= 1;
			$arrCondition['Installers.registration_type'] 				= 1;
			$arrCondition['Installers.otp_verified_status'] 			= 1;
			$arrCondition['Installers.otp_email_verified_status'] 		= 1;
			if ($installer_name != '') {
				$arrCondition['Installers.installer_name LIKE '] 	= '%'.$installer_name.'%';
			}
			if ($geda_approval_status != '') {
				$arrCondition['Installers.geda_approval'] 			= $geda_approval_status;
			}
			$query_data 	= $this->Installers->find('all',array(	'fields'		=> $Fields,
																		'conditions' 	=> $arrCondition,
																		'join' 			=> $Joins,
																		'order'			=> array($this->SortBy=>$this->Direction),
																		'page' 			=> $this->CurrentPage,
																		'limit' 		=> $this->intLimit));


			if(!empty($from_date) && !empty($end_date))
			{
				$fields_date  	= "Installers.created";
				$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
				$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
				$query_data->where([function ($exp, $q) use ($StartTime, $EndTime,$fields_date) {
					return $exp->between($fields_date, $StartTime, $EndTime);
				}]);
			}
			
			$query_data_count 	= $this->Installers->find('all',array('fields'		=> $CountFields,
																		'conditions' 	=> $arrCondition,
																		'join' 			=> $Joins,
															));
			if(!empty($from_date) && !empty($end_date))
			{
				$fields_date  	= "Installers.created";
				$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
				$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
				$query_data_count->where([function ($exp, $q) use ($StartTime, $EndTime,$fields_date) {
					return $exp->between($fields_date, $StartTime, $EndTime);
				}]);
			}

			$total_query_records	= $query_data_count->count();
			$start_page 			= isset($this->request->data['start']) ? $this->request->data['start'] : 1;
			$this->paginate['limit']= PAGE_RECORD_LIMIT;
			$this->paginate['page']	= ($start_page/$this->paginate['limit'])+1;
			if(isset($this->request->data['page_no']) && !empty($this->request->data['page_no']))
			{
				$posible_page 				= $total_query_records/$this->paginate['limit'];
				if($posible_page < $this->request->data['page_no']) {
					$this->paginate['page'] = $posible_page;
				} else {
					$this->paginate['page'] = $this->request->data['page_no'];
				}
			}
			else
			{
				$this->paginate['page'] 	= ($start_page/$this->paginate['limit'])+1;
			}


			$arrRequestList	= $this->paginate($query_data);
			$out 			= array();
			$counter 		= 1;
			$page_mul 		= ($this->CurrentPage-1);
			foreach($arrRequestList->toArray() as $key=>$val)
			{
				$temparr 	= array();
				foreach($option['colName'] as $key) {
					if($key=='id') {
						$temparr[$key]=$counter + ($page_mul * $this->paginate['limit']);
					}
					else if($key=='created') {
						$temparr[$key]	= date('m-d-Y H:i a',strtotime($val->created));
					}
					else if($key=='installer_name') {
						$temparr[$key]	= '<a href="/installer-registration/'.encode($val->company_id).'" target="_blank">'.$val->installer_name.'</a>';
					}
					else if($key=='geda_approval') {
						$temparr[$key]=($val->geda_approval == 1) ? 'Approved' : (($val->geda_approval == 2) ? 'Query Raised' : (($val->geda_approval == 3) ? 'Installer Replied' : 'Pending'));
					}
					else if($key=='action') {
						
							$temparr[$key]	= '';
						
							if($val->geda_approval != 1) {
								$temparr[$key]	= '<a href="javascript:;" class="dropdown-item SubmitRequest approve_Status" data-id="'. encode($val->id) .'"><i class="fa fa-check-square-o" aria-hidden="true"></i> Approve</a>';
							}
							$temparr[$key]	.= '<a href="/installer-registration/'.encode($val->company_id).'" target="_blank" class="dropdown-item">
												<i class="fa fa-eye"></i> View Details
											</a>';
						
						$temparr['action']	= '	<span class="action-row action-btn">
													<div class="dropdown">
														<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
															Actions <i class="fa fa-chevron-down"></i>
														</button>
														<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">'.$temparr['action'].'</div>
													</div>
												</span>';
					}
					else if (isset($val[$key])) {
						$temparr[$key]	= $val[$key];
					} else {
						$temparr[$key]	= "-";
					}
				}
				$counter++;
				$out[] = $temparr;
			}
			header('Content-type: application/json');
			echo json_encode(array(	"draw" 				=> intval($this->request->data['draw']),
									"recordsTotal"    	=> intval($this->request->params['paging']['Installers']['count']),
									"recordsFiltered" 	=> intval($this->request->params['paging']['Installers']['count']),
									"data"            	=> $out));
			die;
		}
		
		$REQUEST_STATUS 	= array("0"=>"Pending","1"=>"Approved","2"=>"Query Raised","3"=>"Installer Replied");
		$RECEVIED_STATUS 	= array("1"=>"YES","0"=>"NO");
		
		$this->set('arrRequestList',$arrRequestList);
		$this->set('JqdTablescr',$JqdTablescr);
		$this->set('period',$this->period);
		$this->set('limit',$this->intLimit);
		$this->set("CurrentPage",$this->CurrentPage);
		$this->set("SortBy",$this->SortBy);
		$this->set("Direction",$this->Direction);
		$this->set("REQUEST_STATUS",$REQUEST_STATUS);
		$this->set("RECEVIED_STATUS",$RECEVIED_STATUS);
		$this->set("pagetitle",'New Developer - Kusum');
		$this->set("page_count",0);
		$this->set("memberApproved",$memberApproved);
	}
}