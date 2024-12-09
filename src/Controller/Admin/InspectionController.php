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

use Cake\Event\Event;
use Cake\View\Helper\PaginatorHelper;
use Cake\Validation\Validator;
use Dompdf\Dompdf;

class InspectionController extends AppController
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
		$this->loadComponent('Paginator');

        $this->loadModel('ApplyOnlines');
		$this->loadModel('ApiToken');
		$this->loadModel('Users');
		$this->loadModel('Userrole');
		$this->loadModel('Installers');
		$this->loadModel('Userroleright');
		$this->loadModel('BranchMasters');
		$this->loadModel('DiscomMaster');
		$this->loadModel('Adminaction');
		$this->loadModel('ApplyOnlineApprovals');
		$this->loadModel('Parameters');
		$this->loadModel('Customers');
		$this->loadModel('Sessions');
		$this->loadModel('States');
		$this->loadModel('ApplyonlinDocs');
		$this->loadModel('Payumoney');
		$this->loadModel('ApplyonlinePayment');
		$this->loadModel('FesibilityReport');
		$this->loadModel('Projects');
		$this->loadModel('InstallerProjects');
		$this->loadModel('CustomerProjects');
		$this->loadModel('Installation');
		$this->loadModel('WorkCompletion');
		$this->loadModel('ThirdpartyApiLog');
		$this->loadModel('InstallerCategoryMapping');
		$this->loadModel('CeiApplicationDetails');
		$this->loadModel('ApplyonlineMessage');
		$this->loadModel('Inspection');
		$this->loadModel('Inspectionpdf');
		$this->loadComponent('PhpExcel');
		$this->set('Userright',$this->Userright);
    }
	public function fetchDetailsRegistrationNumber()
	{
		$this->autoRender   = false;
		$geda_application_no= isset($this->request->data['geda_application_no']) ? $this->request->data['geda_application_no'] : '';
		$fields = [ 'ApplyOnlines.id',
                    'name_of_consumer_applicant'=> "CONCAT(ApplyOnlines.name_of_consumer_applicant, ' ', ApplyOnlines.last_name, ' ', ApplyOnlines.third_name)",
                    'ApplyOnlines.consumer_no',
                    'ApplyOnlines.geda_application_no',
                    'ApplyOnlines.pcr_code',
                    'ApplyOnlines.pcr_submited',
                    'ApplyOnlines.approval_id',
                    'ApplyOnlines.pv_capacity',
                    'ApplyOnlines.disclaimer_subsidy',
                    'ApplyOnlines.customer_name_prefixed',
                    'ApplyOnlines.address1',
                    'ApplyOnlines.address2',
                    'ApplyOnlines.city',
                    'ApplyOnlines.state',
                    'ApplyOnlines.pincode',
                    'ApplyOnlines.consumer_mobile',
                    'installer.installer_name',
                    'project_installation.modules_data',
                    'project_installation.inverter_data',
                    'project_installation.meter_manufacture',
                    'project_installation.meter_serial_no',
                    'project_installation.solar_meter_manufacture',
                    'project_installation.solar_meter_serial_no',
                    'project_installation.bi_date',
                    'project_installation.agreement_date',
            ];
		$applyOnlineDetails = $this->ApplyOnlines->find('all',[
			'fields' 	=> $fields,
			'join'=>[   
                        ['table'=>'installers','alias'=>'installer','type'=>'left','conditions'=>'installer.id = ApplyOnlines.installer_id'],
                        ['table'=>'project_installation','type'=>'left','conditions'=>'project_installation.project_id = ApplyOnlines.project_id'],
                    ],
			'conditions'=> ['geda_application_no'=>$geda_application_no]
			])->first();
		$status 			= '';
		$status_msg 		= '';
		$detailsOutput 		= array();

		if(empty($applyOnlineDetails))
		{
			$status 		= 'failure';
			$status_msg 	= 'Registration number not found - '.$geda_application_no;
			
		}
		else
		{
			$approval 			= $this->ApplyOnlineApprovals->Approvalstage($applyOnlineDetails->id);
			/*if(!empty($applyOnlineDetails->pcr_submited))
			{
				$status 		= 'failure';
				$status_msg 	= 'PCR already generated for registration number '.$geda_application_no;
			}
			else*/
			if(!in_array($this->ApplyOnlineApprovals->METER_INSTALLATION,$approval))
			{
				$status 		= 'failure';
				$status_msg 	= 'Meter installation still remaining for registration number '.$geda_application_no;
			}
			else
			{
				if($applyOnlineDetails->disclaimer_subsidy=='0')
				{
					$status 								= 'success';
					$status_msg 							= 'Record found.';
					$detailsOutput['geda_registation_no']	= $geda_application_no;
					$detailsOutput['empaneled_vendor']		= $applyOnlineDetails->installer['installer_name'];
					$detailsOutput['name_prefix']			= $applyOnlineDetails->customer_name_prefixed;
					$detailsOutput['name_of_user']			= $applyOnlineDetails->name_of_consumer_applicant;
					$detailsOutput['pv_capacity']			= $applyOnlineDetails->pv_capacity;
					$detailsOutput['consumer_mobile']		= $applyOnlineDetails->consumer_mobile;
					$address 								= '';
					if (!empty($applyOnlineDetails->address1)) 
					{ 
						$address = $applyOnlineDetails->address1.' ';
					}
                    if (!empty($applyOnlineDetails->address2)) 
                    {
                    	$address.= '- '.$applyOnlineDetails->address2.' ';
                    }
                    if (!empty($applyOnlineDetails->city)) 
                    { 
                    	$address.= $applyOnlineDetails->city;
                    } 
                    if (!empty($applyOnlineDetails->state)) 
                    { 
                    	$address.= ' ,'.$applyOnlineDetails->state;
                    } 
                    if (!empty($applyOnlineDetails->pincode)) 
                    { 
                    	$address.= ' ,'.$applyOnlineDetails->pincode; 
                    } 
                    $detailsOutput['address']				= $address;
					$detailsOutput['make_net_meter']		= !empty($applyOnlineDetails->project_installation['meter_manufacture']) ? $applyOnlineDetails->project_installation['meter_manufacture'] : '';
					$detailsOutput['serial_net_meter']		= !empty($applyOnlineDetails->project_installation['meter_serial_no']) ? $applyOnlineDetails->project_installation['meter_serial_no'] : '';
					$detailsOutput['make_solar_meter']		= !empty($applyOnlineDetails->project_installation['solar_meter_manufacture']) ? $applyOnlineDetails->project_installation['solar_meter_manufacture'] : '';
					$detailsOutput['serial_solar_meter']	= !empty($applyOnlineDetails->project_installation['solar_meter_serial_no']) ? $applyOnlineDetails->project_installation['solar_meter_serial_no'] : '';
					$detailsOutput['modules_data']     		= !empty($applyOnlineDetails->project_installation['modules_data']) ? unserialize($applyOnlineDetails->project_installation['modules_data']) : '';
	                $detailsOutput['inverter_data']    		= !empty($applyOnlineDetails->project_installation['inverter_data']) ? unserialize($applyOnlineDetails->project_installation['inverter_data']) : '';
	                
				}
				else
				{
					$status 		= 'failure';
					$status_msg 	= 'Registration number '.$geda_application_no." registered in non subsidy";
				}
			}
		}
		echo json_encode(array("status"=>$status,"status_msg"=>$status_msg,"response"=>$detailsOutput));
	}
	/**
     *
     * InspectionPdf
     *
     * Behaviour : Public
     *
     * @defination : Method is use to generate inspection pdf against application number.
     */
	public function InspectionPdf(){
		$this->autoRender   = false;
		$inspection_id            = isset($this->request->data['inspection_id']) ? decode($this->request->data['inspection_id']) : '';
        $geda_no       	 = isset($this->request->data['geda_no']) ? $this->request->data['geda_no'] : '';
        $pdf_path                = isset($this->request->data['pdf_path']) ? $this->request->data['pdf_path'] : '';
        
        if(!empty($inspection_id) && !empty($inspection_id) && !empty($pdf_path)){
        	$applyonline = $this->ApplyOnlines->find("all",['conditions'=>['geda_application_no'=>$geda_no]])->first();
        	
        	if(!empty($applyonline)){
        		$Inspectionpdfdata 					= $this->Inspectionpdf->newEntity();
	            $Inspectionpdfdata->inspection_id 	= $inspection_id;
	            $Inspectionpdfdata->application_id  = !empty($applyonline->id) ? $applyonline->id : '';
	            $Inspectionpdfdata->pdf_file      	= $this->request->data['pdf_path']['name'];
	            $Inspectionpdfdata->geda_number     = $geda_no;
	            $Inspectionpdfdata->created         = $this->NOW();
	            $inspectionsave = $this->Inspectionpdf->save($Inspectionpdfdata);
	            if($inspectionsave){
					$path 			= INSPECTION_PATH.$applyonline->id.'/';
                    if(!file_exists(INSPECTION_PATH.$applyonline->id)){
                        mkdir(INSPECTION_PATH.$applyonline->id, 0755,true);
                    }
			        $ext    		= substr(strtolower(strrchr($this->request->data['pdf_path']['name'], '.')), 1);
			        $file_name   	= 'Inspectionpdf-'.$inspection_id;
			        $file_location 	= $path.$file_name.'.'.$ext;
			        move_uploaded_file($this->request->data['pdf_path']['tmp_name'],$file_location);
			        $this->ApiToken->SetAPIResponse('type', 'ok');
            		$this->ApiToken->SetAPIResponse('msg','Pdf Generated Sucessfully.');

            	}
            }
            else{
				$this->ApiToken->SetAPIResponse('type', 'error');
            	$this->ApiToken->SetAPIResponse('msg','Geda Registration Number not found.');
        	}
        	$this->ApiToken->SetAPIResponse('inspection_id',$inspection_id);
	        echo $this->ApiToken->GenerateAPIResponse();
	        exit;    
        }
	}
}