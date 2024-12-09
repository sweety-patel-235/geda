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

class InstallationsController extends AppController
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
		$this->loadModel('SiteSurveysImages');		
		$this->loadModel('Commercial');		
		$this->loadModel('Workorder');		
		$this->loadModel('Installation');		
		$this->loadModel('ProjectInstallationPhotos');
		$this->loadModel('ApplyOnlines');
		$this->loadModel('WorkCompletion');
		$this->set('Userright',$this->Userright);
    }
    private function SetVariables($post_variables) { 
		/*if(isset($post_variables['lat']))
			$this->request->data['Installation']['latitude']		= $post_variables['lat'];
		if(isset($post_variables['lat']))
			$this->request->data['Installation']['longitude']		= $post_variables['log'];
		if(isset($post_variables['project_id']))
			$this->request->data['Installation']['project_id']		= $post_variables['project_id'];
		if(isset($post_variables['pv_cost']))
			$this->request->data['Installation']['pv_cost'] 		= $post_variables['pv_cost'];
		if(isset($post_variables['is_startdate']))
			$this->request->data['Installation']['start_date'] 		= $post_variables['is_startdate'];
		if(isset($post_variables['is_enddate']))
			$this->request->data['Installation']['end_date']   		= $post_variables['is_enddate'];
		if(isset($post_variables['m_capacity']))
			$this->request->data['Installation']['m_capacity']   	= $post_variables['m_capacity'];
		if(isset($post_variables['m_modules']))
			$this->request->data['Installation']['m_modules']   	= $post_variables['m_modules'];
		if(isset($post_variables['m_make']))
			$this->request->data['Installation']['m_make']   		= $post_variables['m_make'];
		if(isset($post_variables['m_type_modules']))
			$this->request->data['Installation']['m_type_modules']  = $post_variables['m_type_modules'];
		if(isset($post_variables['i_capacity']))
			$this->request->data['Installation']['i_capacity']   	= $post_variables['i_capacity'];
		if(isset($post_variables['i_modules']))
			$this->request->data['Installation']['i_modules']   	= $post_variables['i_modules'];
		if(isset($post_variables['i_make']))
			$this->request->data['Installation']['i_make']   		= $post_variables['i_make'];
		if(isset($post_variables['i_type_modules']))
			$this->request->data['Installation']['i_type_modules']  = $post_variables['i_type_modules'];
		if(isset($post_variables['aadhar_card_no']))
			$this->request->data['Installation']['aadhar_card_no']  = $post_variables['aadhar_card_no'];*/
	}
	/**
	 *
	 * getprojectassumption
	 *
	 * Behaviour : public
	 *
	 * @defination : Method is use to add execution data and get execution data from API
	 *
	 */
	public function getinstallastion()
	{
		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		$cus_id				= $this->ApiToken->customer_id;
		$customerData   	= $this->Customers->find('all', array('conditions'=>array('id'=>$cus_id)))->first();
  		$cus_id   			= (isset($customerData['installer_id'])?$customerData['installer_id']:0);
		$status				= 'ok';
		//$cus_id 			= '1277';
		//$project_id 		= '3157';
		//$this->request->data['project_id'] = '3157';
		$project_id 		= $this->request->data['project_id'];
		if(!empty($cus_id)){
			$commData 		= $this->Installation->find('all',array('conditions'=>array('Installation.project_id'=>$this->request->data['project_id'])))->first();
			$pre_aadhar_card= $commData['attach_aadhar_card'];
			$condition 		= array('CustomerProjects.project_id' => $this->request->data['project_id']);
			$projData 		= $this->CustomerProjects
									->find('all',array('fields'=>['Project.id','Project.name','Project.address','Project.city','Project.created','Project.recommended_capacity','Project.latitude','Project.longitude','Customers.name','Customers.mobile','Customers.email'],'join'=>[ 
										'Project' => [
								            'table' => 'projects',
								            'type' => 'INNER',
								            'conditions' => ['Project.id = CustomerProjects.project_id']
						            	],'Customers' => [
								            'table' => 'customers',
								            'type' => 'INNER',
								            'conditions' => ['Customers.id = CustomerProjects.customer_id']
						            	]],'conditions'=>$condition))->first();
			$type_modules 						= array();
			foreach($this->Installation->TYPE_MODULES as $key=>$val)
			{
				$type_modules[$key-1]['id'] 	= $key;
				$type_modules[$key-1]['text'] 	= $val;
			}
			$type_inverters 					= array();
			foreach($this->Installation->TYPE_INVERTERS as $key=>$val)
			{
				$type_inverters[$key-1]['id'] 	= $key;
				$type_inverters[$key-1]['text'] = $val;
			}
			$make_inverters 					= array();
			foreach($this->Installation->MAKE_INVERTERS as $key=>$val)
			{
				$make_inverters[$key-1]['id'] 	= $key;
				$make_inverters[$key-1]['text'] = $val;
			}	
			$data_number 						= $this->SiteSurveys->find('all')
                            					->where(['project_id' => $project_id])
                            					->first();
			$mobile_num							='';
            if(!empty($data_number))
            {
                    $mobile_num                 = $data_number->mobile;
            }
            $data_capacity 						= $this->Workorder->find('all')
                            					->where(['project_id' => $project_id])
                             					->first();
            $capacity 							= '';
            if(!empty($data_capacity))
            {
                    $capacity 					= $data_capacity->Capacity;
            }
			if(!empty($commData) && !isset($this->request->data['submit'])){
				$result 					= $commData;
				$status						= 'ok';
				//$result['project_id']		= $projData['Project']['id'];
				$result['address']			= $projData['Project']['address'];
				//$result['capacity']			= $projData['Project']['recommended_capacity'];
				$result['capacity'] 		= $capacity;
				//$result['contact']			= $projData['Customers']['mobile'];
				$result['contact']			= $mobile_num;
				$result['installer_id'] 	= $cus_id;
				$result['is_startdate'] 	= '';
				$result['is_enddate'] 		= '';
				if($commData['start_date']!='0000-00-00' && $commData['start_date']!=null)
				{
					$result['is_startdate']	= $commData['start_date']->format('d-m-Y');
				}
				if($commData['end_date']!='0000-00-00'  && $commData['end_date']!=null)
				{
					$result['is_enddate']		= $commData['end_date']->format('d-m-Y');
				}
				$result['aadhar_card_no']	= passdecrypt($commData['aadhar_card_no']);
				$result['type_modules'] 	= $type_modules;
				$result['type_inverters'] 	= $type_inverters;
				$result['make_inverters'] 	= $make_inverters;
				$module_details 			= array(array("m_capacity"=>"","m_make"=>"","m_modules"=>"","m_type_modules"=>"","m_type_other"=>""));
				$inverter_details 			= array(array("i_capacity"=>"","i_make"=>"","i_make_other"=>"","i_modules"=>"","i_type_modules"=>"","i_type_other"=>""));
				$result['module_details']	= (!empty($commData['modules_data'])?unserialize($commData['modules_data']):$module_details);
				$result['inverter_details']	= (!empty($commData['inverter_data'])?unserialize($commData['inverter_data']):$inverter_details);
				unset($result['modules_data']);
				unset($result['inverter_data']);
				$path = EXECUTION_PATH.$commData['project_id'].'/'.$commData['attach_aadhar_card'];
	            if (!empty($commData['attach_aadhar_card']) && file_exists($path)) 
				{
					$result['attach_aadhar_card'] 	= EXECUTION_URL.$commData['project_id'].'/'.$commData['attach_aadhar_card'];
				}
				else
				{
					$result['attach_aadhar_card'] 	= '';
				}
				$ExecutionDocList 					= $this->ProjectInstallationPhotos->find("all",['conditions'=>['project_installation_id'=>$commData['id']]])->toArray();
				$arr_datadoclist 					= array();
				$arr_datadoclist['others']			= array();
					$arr_datadoclist['inverters']	= array();
					$arr_datadoclist['modules']		= array();
				if(isset($ExecutionDocList) && !empty($ExecutionDocList)) 
				{
					foreach ($ExecutionDocList as $key => $value) 
                    {
						$path 								= EXECUTION_PATH.$commData['project_id'].'/'.$value['type'].'/'.$value['photo'];
						if (empty($value['photo']) || !file_exists($path)) continue;
						$arr_image_data 					= explode(".",$value['photo']);
						$arr_datadoclist[$value['type']][]  = array('imageurl'=>EXECUTION_URL.$commData['project_id'].'/'.$value['type'].'/'.$value['photo'],'mediaType'=>end($arr_image_data));	    
					}
				}

				$this->ApiToken->SetAPIResponse('result',$result);
				$this->ApiToken->SetAPIResponse('ExecutionDocList',$arr_datadoclist);
				$status						= 'ok';
			}else if(!empty($projData)){
				$status									= 'ok';
				$result['id']							= "";
				$result['project_id']					= $projData['Project']['id'];
				$result['address']						= $projData['Project']['address'];
				//$result['capacity']			= $projData['Project']['recommended_capacity'];
				$result['capacity'] 					= $capacity;
				//$result['contact']			= $projData['Customers']['mobile'];
				$result['contact']						= $mobile_num;
				$result['installer_id']					= $cus_id;
				$result['is_startdate']					= '';
				$result['is_enddate']					= '';
				$result['type_modules'] 				= $type_modules;
				$result['type_inverters'] 				= $type_inverters;
				$result['make_inverters'] 				= $make_inverters;
				/*$result['m_capacity']		= '';
				$result['m_modules']		= '';
				$result['m_make']			= '';
				$result['m_type_modules']	= '';
				$result['m_type_other']		= '';
				$result['i_capacity']		= '';
				$result['i_modules']		= '';
				$result['i_make']			= '';
				$result['i_make_other']		= '';
				$result['i_type_modules']	= '';
				$result['i_type_other']		= '';*/
				$result['module_details'] 				= array(array("m_capacity"=>"","m_make"=>"","m_modules"=>"","m_type_modules"=>"","m_type_other"=>""));
				$result['inverter_details'] 			= array(array("i_capacity"=>"","i_make"=>"","i_make_other"=>"","i_modules"=>"","i_type_modules"=>"","i_type_other"=>""));
				$result['connectivity_level']			= '';
				$result['connectivity_level_phase']		= '';
				$result['connectivity_level_voltage']	= '';
				$result['latitude']						= '';
				$result['longitude']					= '';
				$result['attach_aadhar_card']			='';
				$result['aadhar_card_no'] 				= '';
				$arr_datadoclist['others']				= array();
				$arr_datadoclist['inverters']			= array();
				$arr_datadoclist['modules']				= array();
				unset($result['modules_data']);
				unset($result['inverter_data']);
				$this->ApiToken->SetAPIResponse('ExecutionDocList',$arr_datadoclist);
				$this->ApiToken->SetAPIResponse('result',$result);
			}
			if(isset($this->request->data['submit']) && !empty($this->request->data['submit'])){
				$this->Installation->data['start_date'] ='';
				$this->Installation->data['end_date']   ='';
				if(isset($this->request->data['is_startdate']) && !empty($this->request->data['is_startdate']))
	            {
	                $this->Installation->data['start_date'] = $this->request->data['is_startdate'];
	            }
	            if(isset($this->request->data['is_enddate']) && !empty($this->request->data['is_enddate']))
	            {
	                $this->Installation->data['end_date'] = $this->request->data['is_enddate'];
	            }
				if(empty($commData)){
					$commEntity 			= $this->Installation->newEntity($this->request->data(),['validate'=>'tab']);
					$commEntity->created 	= $this->NOW(); 
				}else{
					$instData 				= $this->Installation->get($commData['id']);
					$commEntity 			= $this->Installation->patchEntity($instData,$this->request->data(),['validate'=>'tab']);
					$commEntity->attach_aadhar_card	= $pre_aadhar_card;
				}
				if(!$commEntity->errors()) 
				{
				$startdate 					= (isset($this->request->data['is_startdate'])?$this->request->data['is_startdate']:$this->NOW());
				$enddate 					= (isset($this->request->data['is_enddate'])?$this->request->data['is_enddate']:$this->NOW());
				$commEntity->installer_id 	= $cus_id;
				$commEntity->aadhar_card_no = (isset($this->request->data['aadhar_card_no'])?passencrypt($this->request->data['aadhar_card_no']):'');
				$commEntity->start_date   	= date('Y-m-d',strtotime($startdate)); 
				$commEntity->end_date	  	= date('Y-m-d',strtotime($enddate)); 
				$commEntity->modified 		= $this->NOW();
				$commEntity->modules_data 	= serialize(json_decode($this->request->data['module_details'],true));
				$commEntity->inverter_data 	= serialize(json_decode($this->request->data['inverter_details'],true));
				$image_path 				= EXECUTION_PATH.$project_id.'/';
				if(!file_exists(EXECUTION_PATH.$project_id)){
					@mkdir(EXECUTION_PATH.$project_id, 0777,true);
				}
				if(isset($this->request->data['attach_aadhar_card']) && !empty($this->request->data['attach_aadhar_card'])) {
					$db_attach_aadhar_card 			= $commEntity->attach_aadhar_card;
					if(file_exists($image_path.$db_attach_aadhar_card)){
						@unlink($image_path.$db_attach_aadhar_card);
						@unlink($image_path.'r_'.$db_attach_aadhar_card);
					}
					$file_name 						= $this->file_upload($image_path,$this->request->data['attach_aadhar_card'],true,65,65,$image_path,'aadhar');
					$commEntity->attach_aadhar_card	= $file_name;
				}
				if($this->Installation->save($commEntity)) {
					$commData 		= array();
					//,'Installation.installer_id'=>$cus_id
					$commData_latest 			= $this->Installation->find('all',array('conditions'=>array('Installation.project_id'=>$this->request->data['project_id'])))->first();
					$status						= 'ok';
					$result 					= $commData_latest;
					$result['is_startdate']		= $startdate;
					$result['is_enddate']		= $enddate;
					$result['aadhar_card_no']	= passdecrypt($commData_latest['aadhar_card_no']);
					$module_details 			= array(array("m_capacity"=>"","m_make"=>"","m_modules"=>"","m_type_modules"=>"","m_type_other"=>""));
					$inverter_details 			= array(array("i_capacity"=>"","i_make"=>"","i_make_other"=>"","i_modules"=>"","i_type_modules"=>"","i_type_other"=>""));
					$result['module_details']	= (!empty($commData_latest['modules_data'])?unserialize($commData_latest['modules_data']):$module_details);
					$result['inverter_details']	= (!empty($commData_latest['inverter_data'])?unserialize($commData_latest['inverter_data']):$inverter_details);
					unset($result['modules_data']);
					unset($result['inverter_data']);
					$path = EXECUTION_PATH.$commData_latest['project_id'].'/'.$commData_latest['attach_aadhar_card'];
					if (!empty($commData_latest['attach_aadhar_card']) && file_exists($path)) 
					{
						$result['attach_aadhar_card'] 	= EXECUTION_URL.$commData_latest['project_id'].'/'.$commData_latest['attach_aadhar_card'];
					}
					else
					{
						$result['attach_aadhar_card'] 	= '';
					}
					$ExecutionDocList 					= $this->ProjectInstallationPhotos->find("all",['conditions'=>['project_installation_id'=>$commData_latest['id']]])->toArray();
					$arr_datadoclist 					= array();
					$arr_datadoclist['others']			= array();
					$arr_datadoclist['inverters']		= array();
					$arr_datadoclist['modules']			= array();
					if(isset($ExecutionDocList) && !empty($ExecutionDocList)) 
					{
						foreach ($ExecutionDocList as $key => $value) 
	                    {
							$path = EXECUTION_PATH.$commData_latest['project_id'].'/'.$value['type'].'/'.$value['photo'];
							if (empty($value['photo']) || !file_exists($path)) continue;
							$arr_image_data 					= explode(".",$value['photo']);
							$arr_datadoclist[$value['type']][]  = array('imageurl'=>EXECUTION_URL.$commData_latest['project_id'].'/'.$value['type'].'/'.$value['photo'],'mediaType'=>end($arr_image_data));    
						}
					}
					$result['type_modules'] 				= $type_modules;
					$result['type_inverters'] 				= $type_inverters;
					$result['make_inverters'] 				= $make_inverters;
					$arr_apply                              = $this->ApplyOnlines->find('all',array('conditions'=>array('project_id'=>$project_id)))->first();
                    if(!empty($arr_apply))
                    {
                        $WorkCompletion                     = $this->WorkCompletion->getReportData($arr_apply->id);
                        if(!empty($WorkCompletion))
                        {
                            $WorkCompletion                 = $this->WorkCompletion->patchEntity($WorkCompletion,$this->request->data,['validate'=>'Add']);
                        }
                        else
                        {
                            $WorkCompletion                 = $this->WorkCompletion->newEntity($this->request->data,['validate'=>'Add']);
                        }
                        $arr_work_modules   = array();
                        $row_ins = 0;
	                    foreach($result['module_details'] as $key=>$val)
	                    {
	                        $arr_work_modules[$row_ins][0] 		= $val['m_capacity'];
	                        $arr_work_modules[$row_ins][1]      = $val['m_modules'];
	                        $arr_work_modules[$row_ins][2]      = $val['m_type_modules'];
	                        $row_ins++;
	                    }
	                    $arr_inv_modules    = array();
	                    $row_ins = 0;
	                    foreach($result['inverter_details'] as $key=>$val)
	                    {
	                        $arr_inv_modules[$row_ins][0]       = $val['i_capacity'];
	                        $arr_inv_modules[$row_ins][1]       = $val['i_modules'];
	                        $arr_inv_modules[$row_ins][2]       = $val['i_type_modules'];
	                        $arr_inv_modules[$row_ins][3]       = $val['i_make'];
	                        $row_ins++;
	                    }
                        $WorkCompletion->application_id     = $arr_apply->id;
                        $WorkCompletion->created            = $this->NOW();
                        $WorkCompletion->created_by         = $this->ApiToken->customer_id;
                        $WorkCompletion->techspec           = serialize($arr_work_modules);
                        $WorkCompletion->invertors          = serialize($arr_inv_modules);
                        $WorkCompletion->modified           = $this->NOW();
                        $WorkCompletion->modified_by        = $this->ApiToken->customer_id;
                        $this->WorkCompletion->save($WorkCompletion);
                    }
					$this->ApiToken->SetAPIResponse('result',$result);
					$this->ApiToken->SetAPIResponse('ExecutionDocList',$arr_datadoclist);
					}
				}
			}
		}
		else
		{
			$status				= 'error';
			$error				= 'Invalid Request';
			$this->ApiToken->SetAPIResponse('msg', $error);
		}
		$this->ApiToken->SetAPIResponse('type', $status);
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	public function setinstallastion()
	{
		$this->autoRender 		= false;
		$this->SetVariables($this->request->data);
		$cus_id					= $this->ApiToken->customer_id;
		$customerData   		= $this->Customers->find('all', array('conditions'=>array('id'=>$cus_id)))->first();
  		$cus_id   				= (isset($customerData['installer_id'])?$customerData['installer_id']:0);
		$project_id 			= (isset($this->request->data['project_id'])?$this->request->data['project_id']:0);
		if(!empty($cus_id) && !empty($project_id)) {
			$InstallationGet 	= $this->Installation->find('all',array('conditions'=>array('project_id'=>$project_id)))->first();
			if(empty($InstallationGet))
			{
				$commEntity 	= $this->Installation->newEntity($this->request->data());
			}
			else
			{
				$commEntity 	= $this->Installation->patchEntity($InstallationGet,$this->request->data);
			}
			$startdate 						= (isset($this->request->data['is_startdate'])?$this->request->data['is_startdate']:$this->NOW());
			$enddate 						= (isset($this->request->data['is_enddate'])?$this->request->data['is_enddate']:$this->NOW());
			$commEntity->installer_id		= $cus_id;
			$commEntity->start_date  		= date('Y-m-d',strtotime($startdate)); 
			$commEntity->end_date	 		= date('Y-m-d',strtotime($enddate)); 
			if($this->Installation->save($commEntity)) {
				$status						= 'ok';
				$result['project_id']		= $project_id;
				$result['is_startdate']		= $startdate;
				$result['is_enddate']		= $enddate;
				$result['installation_id']	= $commEntity->id;
				$this->ApiToken->SetAPIResponse('result',$result);
			} else {
				$status						= 'error';
				$error						= 'Please try after some time';
				$this->ApiToken->SetAPIResponse('msg', $error);
			} 
		}
		else
		{
			$status							= 'error';
			$error							= 'Invalid Request';
			$this->ApiToken->SetAPIResponse('msg', $error);
		}
		$this->ApiToken->SetAPIResponse('type', $status);
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	/**
	 *
	 * uploadInstallationimage
	 *
	 * Behaviour : public
	 *
	 * @defination : Method is use to add image/document which attached when setinstallastion API call
	 *
	 */
	public function uploadInstallationimage()
	{ 
		$this->autoRender	= false;
		$this->SetVariables($this->request->data);
		$cus_id				= $this->ApiToken->customer_id;
		$customerData   		= $this->Customers->find('all', array('conditions'=>array('id'=>$cus_id)))->first();
  		$cus_id   				= (isset($customerData['installer_id'])?$customerData['installer_id']:0);
		if(!empty($cus_id)) {
			/*Store installer project*/
			$project_id 			= (isset($this->request->data['project_id'])?$this->request->data['project_id']:0);
			$ExecutionData 		= $this->Installation->find('all',array('conditions'=>array('Installation.project_id'=>$this->request->data['project_id'],'Installation.installer_id'=>$cus_id)))->first();
			$type 										= (!empty($this->request->data['image_type'])?$this->request->data['image_type']:'others');
			$imagePatchEntity 							= $this->ProjectInstallationPhotos->newEntity($this->request->data);
			$imagePatchEntity->type 					= $type;
			$imagePatchEntity->project_installation_id 	= (isset($ExecutionData['id'])?$ExecutionData['id']:1);
			$imagePatchEntity->project_id 				= $this->request->data['project_id'];
			$project_ins_id 							= $imagePatchEntity->project_installation_id;
			if(!empty($this->request->data['file_attach_execution']) && $this->request->data['file_attach_execution']!='')
			{
				$image_path 				= EXECUTION_PATH.$project_id.'/'.$type.'/';
				if(!file_exists(EXECUTION_PATH.$project_id.'/'.$type)){
					@mkdir(EXECUTION_PATH.$project_id.'/'.$type, 0777,true);
				}
				$file_name 				= $this->file_upload($image_path,$this->request->data['file_attach_execution'],false,65,65,$image_path);
				$imagePatchEntity->photo 		= $file_name;
				$this->ProjectInstallationPhotos->save($imagePatchEntity);
				$this->ApiToken->SetAPIResponse('type', 'ok');
				$this->ApiToken->SetAPIResponse('msg', 'Image Uploaded Successfully.');
			} 
			else 
			{
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'Invalid request data.');
			}
		} 
		else 
		{
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'Invalid request data.');
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	public function testcheck()
	{
		//print_r(json_decode('[{\"m_capacity\":null,\"m_modules\":null,\"m_type_other\":null,\"m_make\":null,\"m_type_modules\":\"\"},{\"m_capacity\":\"100\",\"m_modules\":\"200\",\"m_type_other\":\"\",\"m_make\":\"300\",\"m_type_modules\":\"1\"}]',true));
		//exit;
		$arr_modules = unserialize('a:2:{i:0;O:8:"stdClass":5:{s:10:"m_capacity";s:3:"100";s:9:"m_modules";s:3:"200";s:12:"m_type_other";s:5:"Jigar";s:6:"m_make";s:3:"300";s:14:"m_type_modules";s:1:"4";}i:1;O:8:"stdClass":5:{s:10:"m_capacity";s:3:"100";s:9:"m_modules";s:3:"200";s:12:"m_type_other";s:0:"";s:6:"m_make";s:3:"300";s:14:"m_type_modules";s:1:"2";}}');
		print_r($arr_modules);
		$arr_work_modules = array();
		foreach($arr_modules as $key=>$val)
	                    {
	                    	//print_r($val);
	                      $arr_work_modules[$key][0] 	  = $val->m_capacity;
	                      $arr_work_modules[$key][1]      = $val->m_modules;
	                      $arr_work_modules[$key][2]      = $val->m_type_modules;
	                    }
	                    print_r($arr_work_modules);
		exit;
	}
}
