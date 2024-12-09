<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\View\View;
use Cake\ORM\TableRegistry;
use Cake\ORM\Table;

use Cake\Core\Configure;
use Cake\Network\Email\Email;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Controller\Component;
use Cake\Utility\Security;

use Cake\Event\Event;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;
use Cake\View\Helper\PaginatorHelper;
use Dompdf\Dompdf;
class SubsidyController extends FrontAppController
{
	public $helpers 	= array('Time','Html','Form','ExPaginator');
	public $paginate 	= [
					        'limit' => PAGE_RECORD_LIMIT,
					        'order' => [
					            'SubsidyRequest.id ' => 'desc'
					        ]
					    ];

	public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Paginator');
        $this->loadComponent('PhpExcel');
        $this->loadModel('ApiToken');
		$this->loadModel('ApplyOnlines');
		$this->loadModel('DiscomMaster');
		$this->loadModel('FesibilityReport');
		$this->loadModel('RegistrationScheme');
		$this->loadModel('RegistrationSchemeDocument');
		$this->loadModel('WorkCompletion');
		$this->loadModel('WorkCompletionDocument');
		$this->loadModel('ChargingCertificate');
		$this->loadModel('ApplyOnlineApprovals');
		$this->loadModel('Parameters');
		$this->loadModel('BranchMasters');
		$this->loadModel('Members');
		$this->loadModel('Installers');
		$this->loadModel('Customers');
		$this->loadModel('States');
		$this->loadModel('Sessions');
		$this->loadModel('ApplyonlinDocs');
		$this->loadModel('Projects');
		$this->loadModel('InstallerProjects');
		$this->loadModel('CustomerProjects');
		$this->loadModel('Installation');
		$this->loadModel('WorkCompletion');
		$this->loadModel('CeiApplicationDetails');
		$this->loadModel('InstallerCategory');
		$this->loadModel('InstallerCategoryMapping');
		$this->loadModel('Workorder');
		$this->loadModel('ProjectInstallationPhotos');
		$this->loadModel('Subsidy');
		$this->loadModel('SubsidyRequest');
		$this->loadModel('SubsidyRequestApplication');
		$this->loadModel('DistrictMaster');
		$this->loadModel('SubsidyCategory');
		$this->loadModel('SpinWebserviceApi');
		$this->loadModel('ApplyonlineMessage');
		$this->loadModel('Couchdb');
		$this->set('ApplyonlineMessage',$this->ApplyonlineMessage);
		$this->set('InspectionReport',$this->InspectionReport);
		$this->loadModel('Emaillog');


		$customer_type 	= $this->Session->read('Customers.customer_type');
		$this->set("customer_type",$customer_type);

		$member_type 	= $this->Session->read('Members.member_type');
		$area 			= $this->Session->read('Members.area');
		$circle 		= $this->Session->read('Members.circle');
		$division 		= $this->Session->read('Members.division');
		$subdivision 	= $this->Session->read('Members.subdivision');
		$section 		= $this->Session->read('Members.section');

		$this->set("JREDA",$this->ApplyOnlines->JREDA);
		$this->set("DISCOM",$this->ApplyOnlines->DISCOM);
		$this->set("CEI",$this->ApplyOnlines->CEI);
		$this->set("MStatus",$this->ApplyOnlineApprovals);
		$this->set("member_type",$member_type);
		$this->set("area",$area);
		$this->set("circle",$circle);
		$this->set("division",$division);
		$this->set("subdivision",$subdivision);
		$this->set("section",$section);

		$is_installer 		= false;
		if ($customer_type == "installer") {
			$is_installer 	= true;
		}
		$this->set("is_installer",$is_installer);
		$this->set("customer_types",array("customer","installer"));
    }

    /**
     *
     * validateAccess
     *
     * Behaviour : Public
     *
     * @param : $isRistricted   : Value is true of false, base on this restriction is set in admin adrea
     * @defination : Method is use to set admin area use for admin base on restriction set for particular
     *
     */
    public function validateAccess($application_status="")
    {
		switch ($application_status) {
			case 'SUBMIT_SUBSIDY_REQUEST':
			{
				$customer_type = $this->Session->read('Customers.customer_type');
				if ($customer_type == "customer" || $customer_type == "installer") {
					return true;
				}
				break;
			}
			case 'APPROVE_SUBSIDY_REQUEST':
			{
				$member_type = $this->Session->read('Members.member_type');
				if ($member_type == $this->ApplyOnlines->JREDA) {
					return true;
				}
				break;
			}
			case 'REVIEW_SUBSIDY_REQUEST':
			{
				$customer_type = $this->Session->read('Customers.customer_type');
				if ($customer_type == "customer" || $customer_type == "installer") {
					return true;
				}
				break;
			}
			case 'DOWNLOAD_CLAIM_COVERLETTER_PDF':
			{
				$customer_type = $this->Session->read('Customers.customer_type');
				if ($customer_type == "customer" || $customer_type == "installer") {
					return true;
				}
				break;
			}
			case 'SUBSIDY_PAYMENT_REPORT':
			{
				$member_type = $this->Session->read('Members.member_type');
				if ($member_type == $this->ApplyOnlines->JREDA) {
					return true;
				}
				break;
			}
		}
		$this->Flash->error('You are not authorized to access this section.');
		return $this->redirect(URL_HTTP.'/apply-online-list');
    }

	/**
     *
     * index
     *
     * Behaviour : Public
     *
     * @param : $id   : apply online encoded id should be passed
     * @defination : Method is use to subsidy claim initial page which having 7 tabs
     *
     */
	public function index($id = 0)
	{
		$is_installer 			= false;
		$installer_id           = '';
		$tab_id					= 1;
		$Subsidy 				= "";
		$is_member 	 			= false;
		//$this->setCustomerArea();
		$customerId 			= $this->Session->read("Customers.id");
		$ses_customer_type 		= $this->Session->read('Customers.customer_type');
		if($ses_customer_type == "installer") {
			$is_installer 		= true;
			$customer_details 	= $this->Customers->find('all',array('conditions'=>array('id'=>$customerId)))->first();
			$installer_id 		= $customer_details['installer_id'];
		}

		$member_id 				= $this->Session->read("Members.id");
		$member_type 			= $this->Session->read('Members.member_type');

		if(empty($customerId) && empty($member_id))
		{
			return $this->redirect(URL_HTTP.'/home');
		}
		if (!empty($member_id) && $member_type != $this->ApplyOnlines->JREDA)
		{
			$this->Flash->error('You are not authorized to access this application.');
			return $this->redirect(URL_HTTP.'/apply-online-list');
		}
		$hideInverterDetail		= 0;
		if (!empty($member_id) && !in_array($member_id,$this->ApplyOnlines->ALLOWED_APPROVE_GEDAIDS))
		{
			$hideInverterDetail = 1;
		}

		if(!empty($member_id))
		{
			$is_member 	 		= true;
		}
		$id 					= intval(decode($id));
		$ApplyOnlines 			= $this->ApplyOnlines->viewApplication($id);
		$project_id 			= $ApplyOnlines->project_id;
		$Workorder 				= $this->Workorder->find('all',array('conditions'=>array('project_id'=>$project_id)))->first();
		$Project				= $this->Projects->find('all',array('conditions'=>array('id'=>$project_id)))->first();
		$ProjectExecutionData	= $this->Installation->find('all',array('conditions'=>array('project_id'=>$project_id)))->first();
		$Subsidy_errors 		= array();

		$this->removeExtraTags('Subsidy');

		$SubsidyExist 			= $this->Subsidy->viewSubsidy($id);
		$Fesibility				= $this->FesibilityReport->find('all',array('conditions'=>array('application_id'=>$id)))->first();
		if(empty($SubsidyExist))
		{
			$SubsidyExist 									= $this->Subsidy->newEntity();
		}
		if(empty($SubsidyExist) || $SubsidyExist->tab6_submit!=1)
		{
			$modules_data     = isset($ProjectExecutionData->modules_data) ? unserialize($ProjectExecutionData->modules_data) : '';
			$inverter_data     = isset($ProjectExecutionData->inverter_data) ? unserialize($ProjectExecutionData->inverter_data) : '';
                        $total_commulative= 0;
            for($i=1;$i<=3;$i++)
            {
                $row            = $i-1;
                $m_capacity     = '';
                $m_make         = '';
                $m_modules      = '';
                $m_type_modules = '';
                $m_type_other   = '';
                if (isset($modules_data[$row]))
                {
                    $m_capacity         = $modules_data[$row]['m_capacity'];
                    $m_make             = $modules_data[$row]['m_make'];
                    $m_modules          = $modules_data[$row]['m_modules'];
                    $m_type_modules     = $modules_data[$row]['m_type_modules'];
                    $m_type_other       = $modules_data[$row]['m_type_other'];
                    $total_commulative  = $total_commulative + ($modules_data[$row]['m_capacity'] * $modules_data[$row]['m_modules']);
                }
            }
            if ($total_commulative > 0)
			{
				$total_commulative  = round(($total_commulative/1000),3);
			}
          	$total_commulative_i  = 0;
			for($i=1;$i<=3;$i++)
			{
				$row                  = $i-1;
				$i_capacity           = '';
				$i_make               = '';
				$i_make_other         = '';
				$i_modules            = '';
				$i_type_modules       = '';
				$i_type_other         = '';
				$i_phase              = '';
				if (isset($inverter_data[$row]))
				{
					$i_capacity         = $inverter_data[$row]['i_capacity'];
					$i_make             = $inverter_data[$row]['i_make'];
					$i_make_other       = $inverter_data[$row]['i_make_other'];
					$i_modules          = $inverter_data[$row]['i_modules'];
					$i_type_modules     = $inverter_data[$row]['i_type_modules'];
					$i_type_other       = $inverter_data[$row]['i_type_other'];
					if(isset($inverter_data[$row]['i_phase']))
					{
					  $i_phase       = $inverter_data[$row]['i_phase'];
					}
					$total_commulative_i= $total_commulative_i + ($inverter_data[$row]['i_capacity']*$inverter_data[$row]['i_modules']);
				}
			}
			if ($total_commulative_i > 0)
            {
                $total_commulative_i  = round(($total_commulative_i),3);
            }
            $min_cap = min($total_commulative,$total_commulative_i,$ApplyOnlines->pv_capacity);
			$this->CreateMyProject($ApplyOnlines->id,true,$min_cap);
		}
		$error_recent_bill 	= 0;
		$DOCUMENT_PATH  	= WWW_ROOT.APPLYONLINE_PATH.$ApplyOnlines->id."/";
		if(!empty($ApplyOnlines->attach_recent_bill))
		{
            if (file_exists($DOCUMENT_PATH.$ApplyOnlines->attach_recent_bill))
            {
                $file_size 				= filesize($DOCUMENT_PATH.$ApplyOnlines->attach_recent_bill)/1000;
                $file_ext 				= explode(".",$ApplyOnlines->attach_recent_bill);
                if($file_size>1024 || strtolower($file_ext[1])!='pdf')
                {
                	$error_recent_bill 	= 1;
                }
                if($error_recent_bill == 0 && empty($SubsidyExist->recent_bill))
		       	{
		       		$file_recent 				= $this->copyfile_upload($ApplyOnlines->attach_recent_bill,$DOCUMENT_PATH,'recent_',$ApplyOnlines->id);
		       		$this->Subsidy->updateAll(array('recent_bill'=>$file_recent),array('application_id'=>$ApplyOnlines->id));
		       		$SubsidyExist->recent_bill 	= $file_recent;
		       	}
            }

       	}
       	if(!empty($ApplyOnlines->attach_pan_card_scan))
		{
            if (file_exists($DOCUMENT_PATH.$ApplyOnlines->attach_pan_card_scan))
            {
                $file_size 				= filesize($DOCUMENT_PATH.$ApplyOnlines->attach_pan_card_scan)/1000;
                $file_ext 				= explode(".",$ApplyOnlines->attach_pan_card_scan);
                if($file_size<=200 && strtolower($file_ext[1])=='pdf' && empty($SubsidyExist->aadhar_card))
		       	{
		       		$file_recent 				= $this->copyfile_upload($ApplyOnlines->attach_pan_card_scan,$DOCUMENT_PATH,'pan_',$ApplyOnlines->id);
		       		$this->Subsidy->updateAll(array('aadhar_card'=>$file_recent),array('application_id'=>$ApplyOnlines->id));
		       		$SubsidyExist->aadhar_card 	= $file_recent;
		       	}
            }
       	}
       	if(!empty($ApplyOnlines->aadhar_no_or_pan_card_no))
		{
			$SubsidyExist->aadhar_no 	= passdecrypt($ApplyOnlines->aadhar_no_or_pan_card_no);
       	}
       	elseif(!empty($ApplyOnlines->pan_card_no))
		{
			$SubsidyExist->aadhar_no 	= passdecrypt($ApplyOnlines->pan_card_no);
       	}
       	if(!empty($ApplyOnlines->consumer_mobile))
		{
			$SubsidyExist->consumer_mobile 	= $ApplyOnlines->consumer_mobile;
       	}
       	if(!empty($ApplyOnlines->pincode))
		{
			$SubsidyExist->pincode 		= $ApplyOnlines->pincode;
       	}
		if(!empty($this->request->data))
		{
			$cur_tab 											= $this->request->data['tab_id'];
			$errors												= array();
			$this->request->data['Subsidy']['application_id'] 	= $id;
			switch ($cur_tab) {
				case '1':
					$response 		= $this->project_details($this->request->data,$Project);
					$Project		= $this->Projects->find('all',array('conditions'=>array('id'=>$project_id)))->first();
				break;
				case '2':
					$response 		= $this->id_proof($this->request->data,$error_recent_bill,$ApplyOnlines->category);//json_encode(array('success'=>'1','response_errors'=>''));
				break;
				case '3':
					$response 		= $this->work_order($this->request->data,$Workorder);
				break;
				case '4':
					$response 		= $this->cei_docs($this->request->data,$Fesibility->recommended_capacity_by_discom);
				break;
				case '5':
					$response 		= $this->execution_details($this->request->data,$project_id);
				break;
				case '6':
					$requestSubsidy = $this->request->data['Subsidy'];
					foreach($requestSubsidy['m_capacity'] as $key=>$val)
			        {
			            $arr_modules[$key]['m_capacity']        = $val;
			            $arr_modules[$key]['m_make']            = $requestSubsidy['m_make'][$key];
			            $arr_modules[$key]['m_modules']         = $requestSubsidy['m_modules'][$key];
			            $arr_modules[$key]['m_type_modules']    = $requestSubsidy['m_type_modules'][$key];
			            $arr_modules[$key]['m_type_other']      = $requestSubsidy['m_type_other'][$key];
			            $arr_work_modules[$key][0]              = $val;
			            $arr_work_modules[$key][1]              = $requestSubsidy['m_modules'][$key];
			            $arr_work_modules[$key][2]              = $requestSubsidy['m_type_modules'][$key];
			        }
			        $arr_inverters      = array();
			        $arr_inv_modules    = array();
			        foreach($requestSubsidy['i_capacity'] as $key=>$val)
			        {
			            $arr_inverters[$key]['i_capacity']      = $val;
			            $arr_inverters[$key]['i_make']          = $requestSubsidy['i_make'][$key];
			            $arr_inverters[$key]['i_make_other']    = $requestSubsidy['i_make_other'][$key];
			            $arr_inverters[$key]['i_modules']       = $requestSubsidy['i_modules'][$key];
			            $arr_inverters[$key]['i_type_modules']  = $requestSubsidy['i_type_modules'][$key];
			            $arr_inverters[$key]['i_type_other']    = $requestSubsidy['i_type_other'][$key];
			            $arr_inverters[$key]['i_phase']         = $requestSubsidy['i_phase'][$key];
			            $arr_inv_modules[$key][0]               = $val;
			            $arr_inv_modules[$key][1]               = $requestSubsidy['i_modules'][$key];
			            $arr_inv_modules[$key][2]               = $requestSubsidy['i_type_modules'][$key];
			            $arr_inv_modules[$key][3]               = $requestSubsidy['i_make'][$key];
			        }
			        $this->request->data['Subsidy']['modules_data']   		= serialize($arr_modules);
			        $this->request->data['Subsidy']['inverter_data']  		= serialize($arr_inverters);
			        $SubsidyExist->modules_data 							= $this->request->data['Subsidy']['modules_data'];
			        $SubsidyExist->inverter_data 							= $this->request->data['Subsidy']['inverter_data'];
					$response 	= $this->technical_details($this->request->data,$ApplyOnlines->pv_capacity,$project_id,$ApplyOnlines->social_consumer);
				break;
				case '7':
					$response 	= $this->for_social_sector($this->request->data);
				break;

				default:
					# code...
					break;
			}

			$arrResponse 		= json_decode($response,1);
			$Subsidy_errors     = $arrResponse['response_errors'];
			if(!empty($Subsidy_errors))
			{
				$SubsidyExist->errors($Subsidy_errors);
			}
			if(isset($this->request->data['next_'.$cur_tab]) && $arrResponse['success']=='1')
			{
				$SubsidyExist 	= $this->Subsidy->viewSubsidy($id);
				if(!empty($SubsidyExist->aadhar_no))
				{
					$SubsidyExist->aadhar_no = passdecrypt($SubsidyExist->aadhar_no);
				}
				$tab_id 		= ($cur_tab<7) ? ($cur_tab+1) : $cur_tab;
				if($ApplyOnlines->social_consumer==1)
				{
					$tab_id 	= ($cur_tab<8) ? ($cur_tab+1) : $cur_tab;
				}

				if(($tab_id==8 && $ApplyOnlines->social_consumer==1) || ($tab_id==7 && $ApplyOnlines->social_consumer==0))
				{
					return $this->redirect(URL_HTTP.'/apply-online-list');
				}
			}
			else
			{
				$tab_id 		= $cur_tab;
			}
		}
		$Self_Certificate			= $this->ApplyonlinDocs->find('all',array('conditions'=>array('application_id'=>$id,'doc_type'=>'Self_Certificate')))->first();

		if(empty($Subsidy_errors))
		{
			if(!empty($SubsidyExist))
			{
				if(!empty($Self_Certificate) && (empty($SubsidyExist->cei_self_certification_date) || $SubsidyExist->cei_self_certification_date=='0000-00-00'))
				{
					$SubsidyExist->cei_self_certification_date 	= date('d-m-Y',strtotime($Self_Certificate->created));
				}
				if(isset($SubsidyExist->cei_licence_expiry_date) && $SubsidyExist->cei_licence_expiry_date!='0000-00-00')
				{
					$SubsidyExist->cei_licence_expiry_date 		= date('d-m-Y',strtotime($SubsidyExist->cei_licence_expiry_date));
				}
				if(isset($SubsidyExist->bidirectional_meter_date) && $SubsidyExist->bidirectional_meter_date!='0000-00-00')
				{
					$SubsidyExist->bidirectional_meter_date 	= date('d-m-Y',strtotime($SubsidyExist->bidirectional_meter_date));
				}
				if(isset($SubsidyExist->comm_date) && $SubsidyExist->comm_date!='0000-00-00')
				{
					$SubsidyExist->comm_date 					= date('d-m-Y',strtotime($SubsidyExist->comm_date));
				}
				if(isset($SubsidyExist->inv_password) && !empty($SubsidyExist->inv_password))
				{
					$SubsidyExist->inv_password 				= passdecrypt($SubsidyExist->inv_password);
				}
			}
			if(empty($SubsidyExist->aadhar_no))
			{
				if(!empty($ApplyOnlines->aadhar_no_or_pan_card_no))
				{
					$SubsidyExist->aadhar_no 	= passdecrypt($ApplyOnlines->aadhar_no_or_pan_card_no);
		       	}
		       	elseif(!empty($ApplyOnlines->pan_card_no))
				{
					$SubsidyExist->aadhar_no 	= passdecrypt($ApplyOnlines->pan_card_no);
		       	}
			}

			if((!isset($SubsidyExist->latitude) ||  $SubsidyExist->latitude==0) && isset($Project->latitude))
			{
				$SubsidyExist->latitude 						= $Project->latitude;
			}
			if((!isset($SubsidyExist->longitude)  || $SubsidyExist->longitude==0) && isset($Project->longitude))
			{
				$SubsidyExist->longitude 						= $Project->longitude;
			}
			if(!isset($SubsidyExist->bidirectional_meter_date) || empty($SubsidyExist->bidirectional_meter_date))
			{
				$SubsidyExist->bidirectional_meter_date 		= isset($ProjectExecutionData->bi_date)?date('d-m-Y',strtotime($ProjectExecutionData->bi_date)):'';
			}
			if(!isset($SubsidyExist->bidirectional_manufacture_name) || empty($SubsidyExist->bidirectional_manufacture_name))
			{
				$SubsidyExist->bidirectional_manufacture_name 	= isset($ProjectExecutionData->meter_manufacture)?$ProjectExecutionData->meter_manufacture:'';
			}
			if(!isset($SubsidyExist->bidirectional_serial_no) || empty($SubsidyExist->bidirectional_serial_no))
			{
				$SubsidyExist->bidirectional_serial_no 			= isset($ProjectExecutionData->meter_serial_no)?$ProjectExecutionData->meter_serial_no:'';
			}
			if(!isset($SubsidyExist->solar_manufacture_name) || empty($SubsidyExist->solar_manufacture_name))
			{
				$SubsidyExist->solar_manufacture_name 			= isset($ProjectExecutionData->solar_meter_manufacture)?$ProjectExecutionData->solar_meter_manufacture:'';
			}
			if(!isset($SubsidyExist->solar_serial_no) || empty($SubsidyExist->solar_serial_no))
			{
				$SubsidyExist->solar_serial_no 					= isset($ProjectExecutionData->solar_meter_serial_no)?$ProjectExecutionData->solar_meter_serial_no:'';
			}
			if(!isset($SubsidyExist->grid_level_voltage) || empty($SubsidyExist->grid_level_voltage))
			{
				$SubsidyExist->grid_level_voltage 				= isset($ProjectExecutionData->connectivity_level_voltage)?$ProjectExecutionData->connectivity_level_voltage:'415';
			}
			if(!isset($SubsidyExist->grid_level_phase) || empty($SubsidyExist->grid_level_phase))
			{
				$SubsidyExist->grid_level_phase 				= isset($ProjectExecutionData->connectivity_level_phase)?$ProjectExecutionData->connectivity_level_phase:'1';
			}
			if(!isset($SubsidyExist->modules_data) || empty($SubsidyExist->modules_data))
			{
				$SubsidyExist->modules_data 					= isset($ProjectExecutionData->modules_data)?$ProjectExecutionData->modules_data:'';
			}
			if(!isset($SubsidyExist->inverter_data) || empty($SubsidyExist->inverter_data))
			{
				$SubsidyExist->inverter_data 					= isset($ProjectExecutionData->inverter_data)?$ProjectExecutionData->inverter_data:'';
			}
			if((!isset($SubsidyExist->subcategory) || empty($SubsidyExist->subcategory)) && isset($this->request->data['Subsidy']['subcategory']))
			{
				$SubsidyExist->subcategory 						= $this->request->data['Subsidy']['subcategory'];
			}
		}

		$InstallerID 				= $ApplyOnlines->installer_id;
		if ($InstallerID != $installer_id && $is_member==false) {
			$this->Flash->error('You are not authorized to access this application.');
			return $this->redirect(URL_HTTP.'/apply-online-list');
		}
		$Installer					= $this->Installers->find('all',array('conditions'=>array('id'=>$InstallerID)))->first();
		$APPROVED_FROM_GEDA			= $this->ApplyOnlineApprovals->find('all',array('conditions'=>array('application_id'=>$id,'stage'=>$this->ApplyOnlineApprovals->APPROVED_FROM_GEDA)))->first();
		$GEDA_REGISTRATION_DATE		= "-";
		if (!empty($APPROVED_FROM_GEDA->id) && !empty($APPROVED_FROM_GEDA->created)) {
			$GEDA_REGISTRATION_DATE	= date("d/M/Y",strtotime($APPROVED_FROM_GEDA->created));
		} else {
			$GEDA_REGISTRATION_DATE	= date("d/M/Y",strtotime($ApplyOnlines->created));
		}


		$ProfilePicture				= $this->ApplyonlinDocs->find('all',array('conditions'=>array('application_id'=>$id,'doc_type'=>'profile')))->first();
		$Signed_Doc					= $this->ApplyonlinDocs->find('all',array('conditions'=>array('application_id'=>$id,'doc_type'=>'Signed_Doc')))->first();
		$ProjectModulePhotos		= $this->ProjectInstallationPhotos->find('all',array('conditions'=>array('project_id'=>$project_id,'type'=>'modules')))->first();
		$ProjectIntverterPhotos		= $this->ProjectInstallationPhotos->find('all',array('conditions'=>array('project_id'=>$project_id,'type'=>'inverters')))->first();
		$ProjectOtherPhotos			= $this->ProjectInstallationPhotos->find('all',array('conditions'=>array('project_id'=>$project_id,'type'=>'others')))->first();

		$Applyonlinprofile  		= $this->ApplyonlinDocs->find('all',['conditions'=>['application_id'=>$id,'doc_type'=>'profile']])->first();

		$ApplicationCategory 		= $this->Parameters->GetParameterList(3)->toArray();
		$ApplicationCategoryTitle 	= "";
		foreach ($ApplicationCategory as $CategoryID => $CategoryTitle) {
			if ($CategoryID == $ApplyOnlines->category) {
				$ApplicationCategoryTitle = $CategoryTitle;
			}
		}
		$CUSTOMER_ADDRESS 		= "";
		if (!empty($ApplyOnlines->address1)) {
			$CUSTOMER_ADDRESS .= $ApplyOnlines->address1.", ";
		}
		if (!empty($ApplyOnlines->address2)) {
			$CUSTOMER_ADDRESS .= $ApplyOnlines->address2.", ";
		}
		if (!empty($ApplyOnlines->city)) {
			$CUSTOMER_ADDRESS .= $ApplyOnlines->city.", ";
		}
		if (!empty($ApplyOnlines->state)) {
			$CUSTOMER_ADDRESS .= $ApplyOnlines->state.", ";
		}
		if (!empty($ApplyOnlines->pincode)) {
			$CUSTOMER_ADDRESS .= $ApplyOnlines->pincode.", ";
		}
		$CUSTOMER_ADDRESS 	= trim($CUSTOMER_ADDRESS,", ");
		$DOCUMENT_PATH 		= APPLYONLINE_PATH.$ApplyOnlines->id."/";
    	$IMAGE_EXT     		= array("png","jpg","gif","jpeg","bmp");

    	$type_modules 		= $this->Installation->TYPE_MODULES ;
        $type_inverters 	= $this->Installation->TYPE_INVERTERS ;
        $make_inverters 	= $this->Installation->MAKE_INVERTERS ;
        $inv_phase 			= $this->Installation->INV_PHASE_TYPE ;

        unset($type_modules['']);
        unset($type_inverters['']);
        unset($make_inverters['']);
        $arrDistrict 		= $this->DistrictMaster->find('list',array('keyField'=>'id','valueField'=>'name','order'=>array('name'=>'asc')))->toArray();
        if($ApplyOnlines->social_consumer==1)
        {
        	$arrConditions  = array('parent_id'=>0,'category_id'=>'1');
        }
        else
        {
        	$arrConditions  = array('parent_id'=>0,'category_id'=>$ApplyOnlines->category);
        }
        $arrCategory 		= $this->SubsidyCategory->find('list',array('keyField'=>'id','valueField'=>'name','conditions'=>$arrConditions,'order'=>array('id'=>'asc')))->toArray();

        $arrSubcategory 	= $this->SubsidyCategory->find('all',array('conditions'=>array('parent_id !='=>0),'order'=>array('id'=>'asc')))->toArray();
        $arrSubData 		= array();
        foreach($arrSubcategory as $val)
        {
        	$arrSubData[$val['parent_id']][$val['id']]=$val['name'];
        }

        $CanEdit 			= $this->CanEditSubsidyDetails($id);
        $ApplyOnlinesData 	= $this->ApplyOnlines->find();
        $TotalCapacityData  = $ApplyOnlinesData->select(['TotalCapacity' => $ApplyOnlinesData->func()->sum('ApplyOnlines.pv_capacity')])->where(['approval_id' => SPIN_APPROVAL_ID])->toArray();
        $SpinSubmit  		= true;
        if($TotalCapacityData[0]['TotalCapacity'] > SPIN_APPROVED_CAPACITY)
        {
        	$SpinSubmit  	= false;
        }
        $LastFailSpinResponse   = $this->SpinWebserviceApi->GetLatestSPINResponse($id);
        $displayUpload 		= false;
        $arrFailedres 		= explode("Not Valid Mime type.",$LastFailSpinResponse);
        $arrFailedresSize 	= explode("Maximum allowed size for file is ",$LastFailSpinResponse);
        if(count($arrFailedres)>1 || count($arrFailedresSize)>1)
        {
        	$displayUpload 	= true;
        }
        if($this->Session->read('Members.authority_account') == 1)
        {
        	$CanEdit 		= true;
        }
        
       	$this->set("SpinSubmit",$SpinSubmit);
       	$this->set("CanEdit",$CanEdit);
       	$this->set("customerId",$customerId);
		$this->set("application_id",encode($id));
		$this->set("is_installer",$is_installer);
		$this->set("installer_id",$installer_id);
		$this->set("tab_id",$tab_id);
		$this->set("MODE_OF_PROJECT","<b>CAPEX</b>");
		$this->set("METERING_ARRANGEMENT","<b>Net metering</b>");
		$this->set("DOCUMENT_PATH",$DOCUMENT_PATH);
		$this->set("IMAGE_EXT",$IMAGE_EXT);
		$this->set("GEDA_REGISTRATION_DATE",$GEDA_REGISTRATION_DATE);
		$this->set("CUSTOMER_ADDRESS",$CUSTOMER_ADDRESS);
		$this->set("APPLICATION_CATEGORY_TITLE",$ApplicationCategoryTitle);
		$this->set("ApplyOnlines",$ApplyOnlines);
		$this->set("Project",$Project);
		$this->set("Subsidy",$SubsidyExist);
		$this->set("SubsidyErrors",$Subsidy_errors);
		$this->set("Installer",$Installer);
		$this->set("Fesibility",$Fesibility);
		$this->set("ProfilePicture",$ProfilePicture);
		$this->set("Signed_Doc",$Signed_Doc);
		$this->set("Self_Certificate",$Self_Certificate);
		$this->set("ProjectModulePhotos",$ProjectModulePhotos);
		$this->set("ProjectIntverterPhotos",$ProjectIntverterPhotos);
		$this->set("ProjectOtherPhotos",$ProjectOtherPhotos);
		$this->set("ProjectExecutionData",$ProjectExecutionData);
		$this->set("type_modules",$type_modules);
		$this->set("type_inverters",$type_inverters);
		$this->set("make_inverters",$make_inverters);
		$this->set('inv_phase',$inv_phase);
		$this->set('Applyonlinprofile',$Applyonlinprofile);
		$this->set('Workorder',$Workorder);
		$this->set('arrDistrict',$arrDistrict);
		$this->set('arrCategory',$arrCategory);
		$this->set('arrSubData',json_encode($arrSubData));
		$this->set('postedData',$this->request->data);
		$this->set('is_member',$is_member);
		$this->set('SubsidyTable',$this->Subsidy);
		$this->set('ApplyOnlinesTable',$this->ApplyOnlines);
		$this->set('displayUpload',$displayUpload);
		$this->set('hideInverterDetail',$hideInverterDetail);
		$this->set('authority_account',$this->Session->read('Members.authority_account'));
		$this->set("pageTitle","Subsidy Claim Section");
		$this->set("Couchdb",$this->Couchdb);
	}

	/**
     *
     * project_details
     *
     * Behaviour : Private
     *
     * @param : $request_data   : tab1 form posted data should be passed
     * @defination : Method is use to check validation of first tab and insert/update subsidy record for first tab.
     *
     */
	private function project_details($request_data,$project_data)
	{
		if(!empty($this->Session->read('Members.member_type')))
		{
			$customerId 				= $this->Session->read("Members.id");
		}
		else
		{
			$customerId 				= $this->Session->read("Customers.id");
		}
		$subsidy_exist 									= $this->Subsidy->viewSubsidy($request_data['Subsidy']['application_id']);
		$this->Subsidy->dataPass 						= $request_data['Subsidy'];
		if(!isset($request_data['Subsidy']['profile_image']))
		{
			$this->Subsidy->dataPass['profile_image']	= '';
		}
		//
		if(empty($subsidy_exist))
		{
			$SubsidyEntity 				= $this->Subsidy->newEntity($request_data,['validate'=>'tab1']);
			$SubsidyEntity->created 	= $this->NOW();
			$SubsidyEntity->created_by 	= $customerId;
			$saveText					= 'inserted';
		}
		else
		{
			$SubsidyEntity 				= $this->Subsidy->patchEntity($subsidy_exist,$request_data,['validate'=>'tab1']);
			$saveText					= 'updated';

		}
		$SubsidyEntity->updated 		= $this->NOW();
		$SubsidyEntity->updated_by 		= $customerId;
		$SubsidyEntity->tab1_submit 	= '1';
		//$SubsidyEntity->tab2_submit 	= '1';

		if(!empty($SubsidyEntity->errors()))
		{
			return json_encode(array('success'=>'0','response_errors'=>$SubsidyEntity->errors()));
		}
		else
		{
			$this->Subsidy->save($SubsidyEntity);
			$this->ApplyOnlines->updateAll(array('mobile'=>$request_data['Subsidy']['consumer_mobile'],'consumer_mobile'=>$request_data['Subsidy']['consumer_mobile'],'pincode'=>$request_data['Subsidy']['pincode']),array('project_id'=>$project_data->id));
			if($SubsidyEntity->common_meter == 1 && $project_data->project_common_meter!=1)
			{
				$this->Projects->updateAll(array('project_common_meter'=>1),array('id'=>$project_data->id));
				$this->ApplyOnlines->updateAll(array('common_meter'=>1),array('project_id'=>$project_data->id));
			}
			if(isset($request_data['Subsidy']['profile_image']['tmp_name']) && !empty($request_data['Subsidy']['profile_image']['tmp_name']))
            {
            	$applyOnlineProfile 	= $this->ApplyonlinDocs->find('all',array('application_id'=>$request_data['Subsidy']['application_id'],'doc_type'=>'profile'))->first();


            	$this->ApplyonlinDocs->deleteAll(array('application_id'=>$request_data['Subsidy']['application_id'],'doc_type'=>'profile'));
            	$ApplyonlinDocsEntity 	= $this->ApplyonlinDocs->newEntity();
            	$image_path 			= APPLYONLINE_PATH.$request_data['Subsidy']['application_id'].'/';
				$file_name 				= $this->file_upload($image_path,$request_data['Subsidy']['profile_image'],true,65,65,$image_path,'doc_');
				$ApplyonlinDocsEntity->doc_type 		= 'profile';
				$ApplyonlinDocsEntity->file_name 		= $file_name;
				$ApplyonlinDocsEntity->application_id 	= $request_data['Subsidy']['application_id'];
				$ApplyonlinDocsEntity->created 			= $this->NOW();

				$this->ApplyonlinDocs->save($ApplyonlinDocsEntity);
				if(!empty($applyOnlineProfile))
            	{
            		$fileName 				= $applyOnlineProfile->file_name;
            		if(!empty($fileName))
            		{
            			$path = APPLYONLINE_PATH.$request_data['Subsidy']['application_id'].'/'.$fileName;
		                if (file_exists($path))
		                {
		                	unlink($path);
	                	}
            		}
            	}
				$this->SpinWebserviceApi->AddPcrFiles($request_data['Subsidy']['application_id']);
            }
			$this->Flash->success("Subsidy $saveText successfully.");
			return json_encode(array('success'=>'1','response_errors'=>''));
		}
	}
	/**
     *
     * id_proof
     *
     * Behaviour : Private
     *
     * @param : $request_data   : tab3 form posted data should be passed
     * @defination : Method is use to check validation of third tab and insert/update subsidy record for third tab.
     *
     */
	private function id_proof($request_data,$error_bill,$category)
	{

		if(!empty($this->Session->read('Members.member_type')))
		{
			$customerId 		= $this->Session->read("Members.id");
		}
		else
		{
			$customerId 		= $this->Session->read("Customers.id");
		}
		$subsidy_exist 			= $this->Subsidy->viewSubsidy($request_data['Subsidy']['application_id']);
		$this->Subsidy->dataPass 					= $request_data['Subsidy'];
		$this->Subsidy->dataPass['aadhar_card']		= (isset($subsidy_exist->aadhar_card) && !empty($subsidy_exist->aadhar_card))?$subsidy_exist->aadhar_card:'';
		$this->Subsidy->dataPass['recent_bill']		= (isset($subsidy_exist->recent_bill) && !empty($subsidy_exist->recent_bill))?$subsidy_exist->recent_bill:'';
		$this->Subsidy->dataPass['error_bill']		= $error_bill;
		if(empty($subsidy_exist))
		{
			$SubsidyEntity = $this->Subsidy->newEntity($request_data,['validate'=>'tab2']);
			$SubsidyEntity->created 	= $this->NOW();
			$SubsidyEntity->created_by 	= $customerId;
			$saveText					= 'inserted';
		}
		else
		{
			$SubsidyEntity 				= $this->Subsidy->patchEntity($subsidy_exist,$request_data,['validate'=>'tab2']);
			$saveText					= 'updated';
		}
		$SubsidyEntity->updated 		= $this->NOW();
		$SubsidyEntity->updated_by 		= $customerId;
		$SubsidyEntity->tab2_submit 	= '1';

		if(!empty($SubsidyEntity->errors()))
		{
			return json_encode(array('success'=>'0','response_errors'=>$SubsidyEntity->errors()));
		}
		else
		{
			$SubsidyEntity->aadhar_card 	= $this->Subsidy->dataPass['aadhar_card'];
			$SubsidyEntity->recent_bill 	= $this->Subsidy->dataPass['recent_bill'];
			if(isset($request_data['Subsidy']['aadhar_card']['tmp_name']) && !empty($request_data['Subsidy']['aadhar_card']['tmp_name']))
            {
                $file_name 					= $this->imgfile_upload ($request_data['Subsidy']['aadhar_card'],'aadhar_',$request_data['Subsidy']['application_id'],'aadhar_card','aadhar_card');
                $SubsidyEntity->aadhar_card= $file_name;
            }
			if(isset($request_data['Subsidy']['recent_bill']['tmp_name']) && !empty($request_data['Subsidy']['recent_bill']['tmp_name']))
            {
                $file_name 					= $this->imgfile_upload ($request_data['Subsidy']['recent_bill'],'recent_',$request_data['Subsidy']['application_id'],'recent_bill','recent_bill');
                $SubsidyEntity->recent_bill = $file_name;
            }
            $SubsidyEntity->aadhar_no 		= passencrypt($request_data['Subsidy']['aadhar_no']);
            $this->Subsidy->save($SubsidyEntity);
            if($this->ApplyOnlines->category_residental == $category)
            {
            	$this->ApplyOnlines->updateAll(array('aadhar_no_or_pan_card_no'=>passencrypt($request_data['Subsidy']['aadhar_no'])),array('id'=>$request_data['Subsidy']['application_id']));
            }
            else
            {
            	$this->ApplyOnlines->updateAll(array('pan_card_no'=>passencrypt($request_data['Subsidy']['aadhar_no'])),array('id'=>$request_data['Subsidy']['application_id']));
            }
            $authority_account 	= $this->Session->read('Members.authority_account');
            $arrApplications 	= $this->ApplyOnlines->find('all',array('fields' 		=> array('pcr_code','pcr_submited'),
        										  'conditions' 	=> array('id'=>$request_data['Subsidy']['application_id'])))->first();

            if($authority_account == 1 && isset($arrApplications->pcr_code) && !empty($arrApplications->pcr_code) && empty($arrApplications->pcr_submited))
            {
            	$this->SpinWebserviceApi->AddPcrFiles($request_data['Subsidy']['application_id']);
            }

			$this->Flash->success("Subsidy $saveText successfully.");
			return json_encode(array('success'=>'1','response_errors'=>''));
		}
	}
	/**
     *
     * work_order
     *
     * Behaviour : Private
     *
     * @param : $request_data   : tab3 form posted data should be passed
     * @defination : Method is use to check validation of third tab and insert/update subsidy record for third tab.
     *
     */
	private function work_order($request_data,$work_order_exist)
	{

		if(!empty($this->Session->read('Members.member_type')))
		{
			$customerId 		= $this->Session->read("Members.id");
		}
		else
		{
			$customerId 		= $this->Session->read("Customers.id");
		}
		$subsidy_exist 			= $this->Subsidy->viewSubsidy($request_data['Subsidy']['application_id']);
		$this->Subsidy->dataPass 						= $request_data['Subsidy'];
		$this->Subsidy->dataPass['workorder_attachment']= (isset($work_order_exist->attached_doc) && !empty($work_order_exist->attached_doc))?$work_order_exist->attached_doc:'';
		$this->Subsidy->dataPass['invoice_copy']		= (isset($subsidy_exist->invoice_copy) && !empty($subsidy_exist->invoice_copy))?$subsidy_exist->invoice_copy:'';
		$this->Subsidy->dataPass['mou_document']		= (isset($subsidy_exist->mou_document) && !empty($subsidy_exist->mou_document))?$subsidy_exist->mou_document:'';
		if(empty($subsidy_exist))
		{
			$SubsidyEntity = $this->Subsidy->newEntity($request_data,['validate'=>'tab3']);
			$SubsidyEntity->created 	= $this->NOW();
			$SubsidyEntity->created_by 	= $customerId;
			$saveText					= 'inserted';
		}
		else
		{
			$SubsidyEntity 				= $this->Subsidy->patchEntity($subsidy_exist,$request_data,['validate'=>'tab3']);
			$saveText					= 'updated';
		}
		$SubsidyEntity->updated 		= $this->NOW();
		$SubsidyEntity->updated_by 		= $customerId;
		$SubsidyEntity->tab3_submit 	= '1';

		if(!empty($SubsidyEntity->errors()))
		{
			return json_encode(array('success'=>'0','response_errors'=>$SubsidyEntity->errors()));
		}
		else
		{
			$SubsidyEntity->invoice_copy 	= $this->Subsidy->dataPass['invoice_copy'];
			$SubsidyEntity->mou_document 	= $this->Subsidy->dataPass['mou_document'];
			if(isset($request_data['Subsidy']['invoice_copy']['tmp_name']) && !empty($request_data['Subsidy']['invoice_copy']['tmp_name']))
            {
                $file_name 					= $this->imgfile_upload ($request_data['Subsidy']['invoice_copy'],'inv_',$request_data['Subsidy']['application_id'],'invoice_copy','invoice_copy');
                $SubsidyEntity->invoice_copy= $file_name;
            }
            if(isset($request_data['Subsidy']['mou_document']['tmp_name']) && !empty($request_data['Subsidy']['mou_document']['tmp_name']))
            {
                $file_name 					= $this->imgfile_upload ($request_data['Subsidy']['mou_document'],'mou_',$request_data['Subsidy']['application_id'],'mou_document','mou_document');
                $SubsidyEntity->mou_document= $file_name;
            }
            $this->Subsidy->save($SubsidyEntity);
			$this->Flash->success("Subsidy $saveText successfully.");
			return json_encode(array('success'=>'1','response_errors'=>''));
		}
	}

	/**
     *
     * cei_docs
     *
     * Behaviour : Private
     *
     * @param : $request_data   : tab4 form posted data should be passed
     * @defination : Method is use to check validation of tab4 and insert/update subsidy record for tab4.
     *
     */
	private function cei_docs($request_data,$recommended_capacity)
	{

		if(!empty($this->Session->read('Members.member_type')))
		{
			$customerId 		= $this->Session->read("Members.id");
		}
		else
		{
			$customerId 		= $this->Session->read("Customers.id");
		}
		$subsidy_exist 			= $this->Subsidy->viewSubsidy($request_data['Subsidy']['application_id']);
		$this->Subsidy->dataPass 							= $request_data['Subsidy'];
		$this->Subsidy->dataPass['cei_approval_doc']		= (isset($subsidy_exist->cei_approval_doc) && !empty($subsidy_exist->cei_approval_doc))?$subsidy_exist->cei_approval_doc:'';
		$this->Subsidy->dataPass['cei_inspection_doc']		= (isset($subsidy_exist->cei_inspection_doc) && !empty($subsidy_exist->cei_inspection_doc))?$subsidy_exist->cei_inspection_doc:'';
		$this->Subsidy->dataPass['cei_self_certification']	= (isset($subsidy_exist->cei_self_certification) && !empty($subsidy_exist->cei_self_certification))?$subsidy_exist->cei_self_certification:'';
		$this->Subsidy->dataPass['recommended_capacity']	= $recommended_capacity;
		if(empty($subsidy_exist))
		{
			$SubsidyEntity 				= $this->Subsidy->newEntity($request_data,['validate'=>'tab4']);
			$SubsidyEntity->created 	= $this->NOW();
			$SubsidyEntity->created_by 	= $customerId;
			$saveText					= 'inserted';
		}
		else
		{
			$SubsidyEntity = $this->Subsidy->patchEntity($subsidy_exist,$request_data,['validate'=>'tab4']);
			$saveText					= 'updated';
		}
		$SubsidyEntity->updated 		= $this->NOW();
		$SubsidyEntity->updated_by 		= $customerId;
		$SubsidyEntity->tab4_submit 	= '1';

		if(!empty($SubsidyEntity->errors()))
		{
			return json_encode(array('success'=>'0','response_errors'=>$SubsidyEntity->errors()));
		}
		else
		{
			$SubsidyEntity->cei_approval_doc			= $this->Subsidy->dataPass['cei_approval_doc'];
			$SubsidyEntity->cei_inspection_doc			= $this->Subsidy->dataPass['cei_inspection_doc'];
			$SubsidyEntity->cei_self_certification		= $this->Subsidy->dataPass['cei_self_certification'];
			if(isset($request_data['Subsidy']['cei_approval_doc']['tmp_name']) && !empty($request_data['Subsidy']['cei_approval_doc']['tmp_name']))
            {
                $file_name 								= $this->imgfile_upload ($request_data['Subsidy']['cei_approval_doc'],'cad_',$request_data['Subsidy']['application_id'],'cei_approval_doc','cei_approval_doc');
                $SubsidyEntity->cei_approval_doc		= $file_name;
            }
            if(isset($request_data['Subsidy']['cei_inspection_doc']['tmp_name']) && !empty($request_data['Subsidy']['cei_inspection_doc']['tmp_name']))
            {
                $file_name 								= $this->imgfile_upload ($request_data['Subsidy']['cei_inspection_doc'],'cid_',$request_data['Subsidy']['application_id'],'cei_inspection_doc','cei_inspection_doc');
                $SubsidyEntity->cei_inspection_doc 		= $file_name;
            }
			if(isset($request_data['Subsidy']['cei_self_certification']['tmp_name']) && !empty($request_data['Subsidy']['cei_self_certification']['tmp_name']))
            {
                $file_name 								= $this->imgfile_upload ($request_data['Subsidy']['cei_self_certification'],'csc_',$request_data['Subsidy']['application_id'],'cei_self_certification','cei_self_certification');
                $SubsidyEntity->cei_self_certification 	= $file_name;
            }
            $SubsidyEntity->cei_licence_expiry_date     = date('Y-m-d',strtotime($SubsidyEntity->cei_licence_expiry_date));
			$this->Subsidy->save($SubsidyEntity);
			$this->Flash->success("Subsidy $saveText successfully.");
			return json_encode(array('success'=>'1','response_errors'=>''));
		}
	}

	/**
     *
     * execution_details
     *
     * Behaviour : Private
     *
     * @param : $request_data   : tab5 form posted data should be passed
     * @defination : Method is use to check validation of tab5 and insert/update subsidy record for tab5.
     *
     */
	private function execution_details($request_data,$project_id)
	{
		if(!empty($this->Session->read('Members.member_type')))
		{
			$customerId 		= $this->Session->read("Members.id");
		}
		else
		{
			$customerId 		= $this->Session->read("Customers.id");
		}
		$subsidy_exist 			= $this->Subsidy->viewSubsidy($request_data['Subsidy']['application_id']);
		$this->Subsidy->dataPass 										= $request_data['Subsidy'];
		$this->Subsidy->dataPass['bidirectional_installation_sheet']	= (isset($subsidy_exist->bidirectional_installation_sheet) && !empty($subsidy_exist->bidirectional_installation_sheet))?$subsidy_exist->bidirectional_installation_sheet:'';
		$this->Subsidy->dataPass['bidirectional_meter_certification']	= (isset($subsidy_exist->bidirectional_meter_certification) && !empty($subsidy_exist->bidirectional_meter_certification))?$subsidy_exist->bidirectional_meter_certification:'';
		$this->Subsidy->dataPass['meter_sealing_report']				= (isset($subsidy_exist->meter_sealing_report) && !empty($subsidy_exist->meter_sealing_report))?$subsidy_exist->meter_sealing_report:'';
		if(empty($subsidy_exist))
		{
			$SubsidyEntity 				= $this->Subsidy->newEntity($request_data,['validate'=>'tab5']);
			$SubsidyEntity->created 	= $this->NOW();
			$SubsidyEntity->created_by 	= $customerId;
			$saveText					= 'inserted';
		}
		else
		{
			$SubsidyEntity = $this->Subsidy->patchEntity($subsidy_exist,$request_data,['validate'=>'tab5']);
			$saveText					= 'updated';
		}
		$SubsidyEntity->updated 		= $this->NOW();
		$SubsidyEntity->updated_by 		= $customerId;
		$SubsidyEntity->tab5_submit 	= '1';

		if(!empty($SubsidyEntity->errors()))
		{
			return json_encode(array('success'=>'0','response_errors'=>$SubsidyEntity->errors()));
		}
		else
		{
			$SubsidyEntity->bidirectional_installation_sheet		= $this->Subsidy->dataPass['bidirectional_installation_sheet'];
			$SubsidyEntity->bidirectional_meter_certification		= $this->Subsidy->dataPass['bidirectional_meter_certification'];
			$SubsidyEntity->meter_sealing_report					= $this->Subsidy->dataPass['meter_sealing_report'];
			if(isset($request_data['Subsidy']['bidirectional_installation_sheet']['tmp_name']) && !empty($request_data['Subsidy']['bidirectional_installation_sheet']['tmp_name']))
            {
                $file_name 											= $this->imgfile_upload ($request_data['Subsidy']['bidirectional_installation_sheet'],'bis_',$request_data['Subsidy']['application_id'],'bidirectional_installation_sheet','bidirectional_installation_sheet');
                $SubsidyEntity->bidirectional_installation_sheet 	= $file_name;
            }
            if(isset($request_data['Subsidy']['bidirectional_meter_certification']['tmp_name']) && !empty($request_data['Subsidy']['bidirectional_meter_certification']['tmp_name']))
            {
                $file_name 											= $this->imgfile_upload ($request_data['Subsidy']['bidirectional_meter_certification'],'bmc_',$request_data['Subsidy']['application_id'],'bidirectional_meter_certification','bidirectional_meter_certification');
                $SubsidyEntity->bidirectional_meter_certification 	= $file_name;
            }
			if(isset($request_data['Subsidy']['meter_sealing_report']['tmp_name']) && !empty($request_data['Subsidy']['meter_sealing_report']['tmp_name']))
            {
                $file_name 											= $this->imgfile_upload ($request_data['Subsidy']['meter_sealing_report'],'msr_',$request_data['Subsidy']['application_id'],'meter_sealing_report','meter_sealing_report');
                $SubsidyEntity->meter_sealing_report 				= $file_name;
            }
            $SubsidyEntity->bidirectional_meter_date     			= date('Y-m-d',strtotime($SubsidyEntity->bidirectional_meter_date));
			$this->Subsidy->save($SubsidyEntity);
			$arr_update	= array('bi_date'					=> $SubsidyEntity->bidirectional_meter_date,
								'meter_manufacture'			=> $SubsidyEntity->bidirectional_manufacture_name,
								'meter_serial_no'			=> $SubsidyEntity->bidirectional_serial_no,
								'solar_meter_manufacture'	=> $SubsidyEntity->solar_manufacture_name,
								'solar_meter_serial_no'		=> $SubsidyEntity->solar_serial_no,
								'connectivity_level_voltage'=> $SubsidyEntity->grid_level_voltage,
								'connectivity_level_phase'	=> $SubsidyEntity->grid_level_phase
								);
			$this->Installation->updateAll($arr_update,array('project_id'=>$project_id));
			$this->CreateMyProject($request_data['Subsidy']['application_id'],true,'');

			$authority_account 	= $this->Session->read('Members.authority_account');
            $arrApplications 	= $this->ApplyOnlines->find('all',array('fields' 		=> array('pcr_code','pcr_submited'),
        										  'conditions' 	=> array('id'=>$request_data['Subsidy']['application_id'])))->first();

            if($authority_account == 1 && isset($arrApplications->pcr_code) && !empty($arrApplications->pcr_code) && empty($arrApplications->pcr_submited))
            {
            	$this->SpinWebserviceApi->AddPcrFiles($request_data['Subsidy']['application_id']);
            }

			$this->Flash->success("Subsidy $saveText successfully.");
			return json_encode(array('success'=>'1','response_errors'=>''));
		}
	}

	/**
     *
     * technical_details
     *
     * Behaviour : Private
     *
     * @param : $request_data   : tab6 form posted data should be passed
     * @defination : Method is use to check validation of tab6 and insert/update subsidy record for tab6.
     *
     */
	private function technical_details($request_data,$recommended_capacity,$project_id,$social_consumer)
	{

		if(!empty($this->Session->read('Members.member_type')))
		{
			$customerId 		= $this->Session->read("Members.id");
		}
		else
		{
			$customerId 		= $this->Session->read("Customers.id");
		}
		$subsidy_exist 			= $this->Subsidy->viewSubsidy($request_data['Subsidy']['application_id']);
		$this->Subsidy->dataPass 							= $request_data['Subsidy'];
		$this->Subsidy->dataPass['pv_module_serial']		= (isset($subsidy_exist->pv_module_serial) && !empty($subsidy_exist->pv_module_serial))?$subsidy_exist->pv_module_serial:'';
		$this->Subsidy->dataPass['pv_module_certificate']	= (isset($subsidy_exist->pv_module_certificate) && !empty($subsidy_exist->pv_module_certificate))?$subsidy_exist->pv_module_certificate:'';
		$this->Subsidy->dataPass['pv_module_sheet']			= (isset($subsidy_exist->pv_module_sheet) && !empty($subsidy_exist->pv_module_sheet))?$subsidy_exist->pv_module_sheet:'';
		$this->Subsidy->dataPass['inverter_serial']			= (isset($subsidy_exist->inverter_serial) && !empty($subsidy_exist->inverter_serial))?$subsidy_exist->inverter_serial:'';
		$this->Subsidy->dataPass['inverter_certificate']	= (isset($subsidy_exist->inverter_certificate) && !empty($subsidy_exist->inverter_certificate))?$subsidy_exist->inverter_certificate:'';
		$this->Subsidy->dataPass['inverter_sheet']			= (isset($subsidy_exist->inverter_sheet) && !empty($subsidy_exist->inverter_sheet))?$subsidy_exist->inverter_sheet:'';
		$this->Subsidy->dataPass['pv_plant_site_photo']		= (isset($subsidy_exist->pv_plant_site_photo) && !empty($subsidy_exist->pv_plant_site_photo))?$subsidy_exist->pv_plant_site_photo:'';
		$this->Subsidy->dataPass['undertaking_consumer']	= (isset($subsidy_exist->undertaking_consumer) && !empty($subsidy_exist->undertaking_consumer))?$subsidy_exist->undertaking_consumer:'';
		$this->Subsidy->dataPass['geda_inspection_report']	= (isset($subsidy_exist->geda_inspection_report) && !empty($subsidy_exist->geda_inspection_report))?$subsidy_exist->geda_inspection_report:'';
		$this->Subsidy->dataPass['cumulative_module']  		= 0;
        $this->Subsidy->dataPass['cumulative_inverter']		= 0;
        $this->Subsidy->dataPass['approved_capacity']  		= $recommended_capacity;
		if(empty($subsidy_exist))
		{
			$SubsidyEntity 				= $this->Subsidy->newEntity($request_data,['validate'=>'tab6']);
			$SubsidyEntity->created 	= $this->NOW();
			$SubsidyEntity->created_by 	= $customerId;
			$saveText					= 'inserted';
		}
		else
		{
			$SubsidyEntity 				= $this->Subsidy->patchEntity($subsidy_exist,$request_data,['validate'=>'tab6']);
			$saveText					= 'updated';
		}
		$SubsidyEntity->updated 		= $this->NOW();
		$SubsidyEntity->updated_by 		= $customerId;
		$SubsidyEntity->tab6_submit 	= '1';
		$flag_submit 					= '0';
		if($SubsidyEntity->tab1_submit==1 && $SubsidyEntity->tab2_submit==1 && $SubsidyEntity->tab3_submit==1 && $SubsidyEntity->tab4_submit==1 && $SubsidyEntity->tab5_submit==1 && $social_consumer==0)
		{
			$SubsidyEntity->subsidy_submit 	= '1';
			$flag_submit 					= 1;
		}
		if(!empty($SubsidyEntity->errors()))
		{
			return json_encode(array('success'=>'0','response_errors'=>$SubsidyEntity->errors()));
		}
		else
		{
			$SubsidyEntity->pv_module_serial 		= $this->Subsidy->dataPass['pv_module_serial'];
			$SubsidyEntity->pv_module_certificate	= $this->Subsidy->dataPass['pv_module_certificate'];
			$SubsidyEntity->pv_module_sheet			= $this->Subsidy->dataPass['pv_module_sheet'];
			$SubsidyEntity->inverter_serial			= $this->Subsidy->dataPass['inverter_serial'];
			$SubsidyEntity->inverter_certificate	= $this->Subsidy->dataPass['inverter_certificate'];
			$SubsidyEntity->inverter_sheet			= $this->Subsidy->dataPass['inverter_sheet'];
			$SubsidyEntity->pv_plant_site_photo		= $this->Subsidy->dataPass['pv_plant_site_photo'];
			$SubsidyEntity->undertaking_consumer	= $this->Subsidy->dataPass['undertaking_consumer'];
			$SubsidyEntity->geda_inspection_report	= $this->Subsidy->dataPass['geda_inspection_report'];

			if(isset($request_data['Subsidy']['pv_module_serial']['tmp_name']) && !empty($request_data['Subsidy']['pv_module_serial']['tmp_name']))
            {
                $file_name 								= $this->imgfile_upload ($request_data['Subsidy']['pv_module_serial'],'mod_ser_',$request_data['Subsidy']['application_id'],'pv_module_serial','pv_module_serial');
                $SubsidyEntity->pv_module_serial		= $file_name;
            }
			if(isset($request_data['Subsidy']['pv_module_certificate']['tmp_name']) && !empty($request_data['Subsidy']['pv_module_certificate']['tmp_name']))
            {
                $file_name 								= $this->imgfile_upload ($request_data['Subsidy']['pv_module_certificate'],'mod_cert_',$request_data['Subsidy']['application_id'],'pv_module_certificate','pv_module_certificate');
                $SubsidyEntity->pv_module_certificate	= $file_name;
            }
			if(isset($request_data['Subsidy']['pv_module_sheet']['tmp_name']) && !empty($request_data['Subsidy']['pv_module_sheet']['tmp_name']))
            {
                $file_name 								= $this->imgfile_upload ($request_data['Subsidy']['pv_module_sheet'],'mod_sheet_',$request_data['Subsidy']['application_id'],'pv_module_sheet','pv_module_sheet');
                $SubsidyEntity->pv_module_sheet			= $file_name;
            }
			if(isset($request_data['Subsidy']['inverter_serial']['tmp_name']) && !empty($request_data['Subsidy']['inverter_serial']['tmp_name']))
            {
                $file_name 								= $this->imgfile_upload ($request_data['Subsidy']['inverter_serial'],'in_ser_',$request_data['Subsidy']['application_id'],'inverter_serial','inverter_serial');
                $SubsidyEntity->inverter_serial			= $file_name;
            }
			if(isset($request_data['Subsidy']['inverter_certificate']['tmp_name']) && !empty($request_data['Subsidy']['inverter_certificate']['tmp_name']))
            {
                $file_name 								= $this->imgfile_upload ($request_data['Subsidy']['inverter_certificate'],'in_cert_',$request_data['Subsidy']['application_id'],'inverter_certificate','inverter_certificate');
                $SubsidyEntity->inverter_certificate	= $file_name;
            }
			if(isset($request_data['Subsidy']['inverter_sheet']['tmp_name']) && !empty($request_data['Subsidy']['inverter_sheet']['tmp_name']))
            {
                $file_name 								= $this->imgfile_upload ($request_data['Subsidy']['inverter_sheet'],'in_sheet_',$request_data['Subsidy']['application_id'],'inverter_sheet','inverter_sheet');
                $SubsidyEntity->inverter_sheet			= $file_name;
            }
			if(isset($request_data['Subsidy']['pv_plant_site_photo']['tmp_name']) && !empty($request_data['Subsidy']['pv_plant_site_photo']['tmp_name']))
            {
                $file_name 								= $this->imgfile_upload ($request_data['Subsidy']['pv_plant_site_photo'],'pv_',$request_data['Subsidy']['application_id'],'pv_plant_site_photo','pv_plant_site_photo');
                $SubsidyEntity->pv_plant_site_photo		= $file_name;
            }
            if(isset($request_data['Subsidy']['undertaking_consumer']['tmp_name']) && !empty($request_data['Subsidy']['undertaking_consumer']['tmp_name']))
            {
                $file_name 								= $this->imgfile_upload ($request_data['Subsidy']['undertaking_consumer'],'un_c_',$request_data['Subsidy']['application_id'],'undertaking_consumer','undertaking_consumer');
                $SubsidyEntity->undertaking_consumer 	= $file_name;
            }
            if(isset($request_data['Subsidy']['geda_inspection_report']['tmp_name']) && !empty($request_data['Subsidy']['geda_inspection_report']['tmp_name']))
            {
                $file_name 								= $this->imgfile_upload ($request_data['Subsidy']['geda_inspection_report'],'g_i_r',$request_data['Subsidy']['application_id'],'geda_inspection_report','geda_inspection_report');
                $SubsidyEntity->geda_inspection_report 	= $file_name;
            }
            $SubsidyEntity->comm_date     				= date('Y-m-d',strtotime($SubsidyEntity->comm_date));
            $SubsidyEntity->inv_password				= passencrypt($SubsidyEntity->inv_password);
			$this->Subsidy->save($SubsidyEntity);

			if($flag_submit==0 && $social_consumer==0)
			{
				$this->Flash->success("Somedata of any tab remaining to submit.");
			}
			elseif($flag_submit==1 && $social_consumer==0)
			{
				$status = $this->ApplyOnlineApprovals->CLAIM_SUBSIDY;
				$reason = '';
				$this->SetApplicationStatus($status,$request_data['Subsidy']['application_id'],$reason);
				$this->Flash->success("Subsidy Submitted successfully.");
			}
			$ExecutionData 	= $this->Installation->find('all',array('conditions'=>array('project_id'=>$project_id)))->first();
			if(!empty($ExecutionData))
			{
				$arr_update	= array('modules_data'=> $SubsidyEntity->modules_data,
								'inverter_data'=> $SubsidyEntity->inverter_data
								);
				$this->Installation->updateAll($arr_update,array('project_id'=>$project_id));
			}
			else
			{
				$workorderData   	= $this->Workorder->find('all',array("conditions"=>array('Workorder.project_id'=>$project_id)))->first();
				$startDate 			= '0000-00-00';
				if(!empty($workorderData))
				{
					$startDate 		= $workorderData->workorder_date;
				}
				$applyOnlinesView 	= $this->ApplyOnlines->viewApplication($request_data['Subsidy']['application_id']);

				$executionEntity 							= $this->Installation->newEntity();
				$executionEntity->project_id 				= $project_id;
				$executionEntity->installer_id 				= $applyOnlinesView->installer_id;
				$executionEntity->modules_data 				= $SubsidyEntity->modules_data;
				$executionEntity->inverter_data 			= $SubsidyEntity->inverter_data;
				$executionEntity->start_date 				= $startDate;
				$executionEntity->end_date 					= $SubsidyEntity->comm_date;
				$executionEntity->solar_meter_manufacture 	= $SubsidyEntity->solar_manufacture_name;
				$executionEntity->solar_meter_serial_no 	= $SubsidyEntity->solar_serial_no;
				$executionEntity->meter_manufacture 		= $SubsidyEntity->bidirectional_manufacture_name;
				$executionEntity->meter_serial_no 			= $SubsidyEntity->bidirectional_serial_no;
				$executionEntity->created 					= $this->NOW();
				$this->Installation->save($executionEntity);
				$NextStatus                        			= $this->ApplyOnlineApprovals->WORK_EXECUTED;
				$this->ApplyOnlineApprovals->saveStatus($request_data['Subsidy']['application_id'],$NextStatus,$customerId,'');

			}
			
			$modules_data     = isset($SubsidyEntity->modules_data) ? unserialize($SubsidyEntity->modules_data) : '';
			$inverter_data     = isset($SubsidyEntity->inverter_data) ? unserialize($SubsidyEntity->inverter_data) : '';

            $total_commulative= 0;

            for($i=1;$i<=3;$i++)
            {
                $row            = $i-1;
                $m_capacity     = '';
                $m_make         = '';
                $m_modules      = '';
                $m_type_modules = '';
                $m_type_other   = '';
                pr($inverter_data);
				
                if (isset($modules_data[$row]))
                {
                    $m_capacity         = !empty($modules_data[$row]['m_capacity']) ? $modules_data[$row]['m_capacity'] : 0;
                    $m_make             = $modules_data[$row]['m_make'];
                    $m_modules          = !empty($modules_data[$row]['m_modules']) ? $modules_data[$row]['m_modules'] : 0;
                    $m_type_modules     = $modules_data[$row]['m_type_modules'];
                    $m_type_other       = $modules_data[$row]['m_type_other'];
                    $total_commulative  = $total_commulative + ($m_capacity * $m_modules);
                }
            }
            if ($total_commulative > 0)
			{
				$total_commulative  = round(($total_commulative/1000),3);
			}

          	$total_commulative_i  = 0;
			for($i=1;$i<=3;$i++)
			{
				$row                  = $i-1;
				$i_capacity           = '';
				$i_make               = '';
				$i_make_other         = '';
				$i_modules            = '';
				$i_type_modules       = '';
				$i_type_other         = '';
				$i_phase              = '';

				if (isset($inverter_data[$row]))
				{
					$i_capacity         = !empty($inverter_data[$row]['i_capacity']) ? $inverter_data[$row]['i_capacity'] : 0;
					$i_make             = $inverter_data[$row]['i_make'];
					$i_make_other       = $inverter_data[$row]['i_make_other'];
					$i_modules          = !empty($inverter_data[$row]['i_modules']) ? $inverter_data[$row]['i_modules'] : 0;
					$i_type_modules     = $inverter_data[$row]['i_type_modules'];
					$i_type_other       = $inverter_data[$row]['i_type_other'];
					if(isset($inverter_data[$row]['i_phase']))
					{
					  $i_phase       = $inverter_data[$row]['i_phase'];
					}
					$total_commulative_i= $total_commulative_i + ($i_capacity*$i_modules);
				}
			}
			if ($total_commulative_i > 0)
            {
                $total_commulative_i  = round(($total_commulative_i),3);
            }
            $min_cap = min($total_commulative,$total_commulative_i,$recommended_capacity);
			$this->CreateMyProject($SubsidyEntity->application_id,true,$min_cap);

			$authority_account 	= $this->Session->read('Members.authority_account');
            $arrApplications 	= $this->ApplyOnlines->find('all',array('fields' 		=> array('pcr_code','pcr_submited'),
        										  'conditions' 	=> array('id'=>$SubsidyEntity->application_id)))->first();

            if($authority_account == 1 && isset($arrApplications->pcr_code) && !empty($arrApplications->pcr_code) && empty($arrApplications->pcr_submited))
            {
            	$this->SpinWebserviceApi->AddPcrFiles($SubsidyEntity->application_id);
            }

			if($social_consumer==1)
			{
				$this->Flash->success("Subsidy $saveText successfully.");
			}
			return json_encode(array('success'=>'1','response_errors'=>''));
		}
	}

	/**
     *
     * for_social_sector
     *
     * Behaviour : Private
     *
     * @param : $request_data   : tab7 form posted data should be passed
     * @defination : Method is use to check validation of tab7 and insert/update subsidy record for tab7.
     *
     */
	private function for_social_sector($request_data)
	{
		if(!empty($this->Session->read('Members.member_type')))
		{
			$customerId 		= $this->Session->read("Members.id");
		}
		else
		{
			$customerId 		= $this->Session->read("Customers.id");
		}
		$subsidy_exist 			= $this->Subsidy->viewSubsidy($request_data['Subsidy']['application_id']);
		$this->Subsidy->dataPass 						= $request_data['Subsidy'];
		$this->Subsidy->dataPass['signing_authority']	= (isset($subsidy_exist->signing_authority) && !empty($subsidy_exist->signing_authority))?$subsidy_exist->signing_authority:'';
		$this->Subsidy->dataPass['charity_certificate']	= (isset($subsidy_exist->charity_certificate) && !empty($subsidy_exist->charity_certificate))?$subsidy_exist->charity_certificate:'';
		$this->Subsidy->dataPass['authority_letter']	= (isset($subsidy_exist->authority_letter) && !empty($subsidy_exist->authority_letter))?$subsidy_exist->authority_letter:'';
		$this->Subsidy->dataPass['formb']				= (isset($subsidy_exist->formb) && !empty($subsidy_exist->formb))?$subsidy_exist->formb:'';
		$this->Subsidy->dataPass['formc']				= (isset($subsidy_exist->formc) && !empty($subsidy_exist->formc))?$subsidy_exist->formc:'';
		$this->Subsidy->dataPass['affidavit']			= (isset($subsidy_exist->affidavit) && !empty($subsidy_exist->affidavit))?$subsidy_exist->affidavit:'';
		$this->Subsidy->dataPass['agreement_stamp']		= (isset($subsidy_exist->agreement_stamp) && !empty($subsidy_exist->agreement_stamp))?$subsidy_exist->agreement_stamp:'';
		$this->Subsidy->dataPass['social_excel']		= (isset($subsidy_exist->social_excel) && !empty($subsidy_exist->social_excel))?$subsidy_exist->social_excel:'';
		$this->Subsidy->dataPass['social_pdf']			= (isset($subsidy_exist->social_pdf) && !empty($subsidy_exist->social_pdf))?$subsidy_exist->social_pdf:'';

		if(empty($subsidy_exist))
		{
			$SubsidyEntity 				= $this->Subsidy->newEntity($request_data,['validate'=>'tab7']);
			$SubsidyEntity->created 	= $this->NOW();
			$SubsidyEntity->created_by 	= $customerId;
			$saveText					= 'inserted';
		}
		else
		{
			$SubsidyEntity 				= $this->Subsidy->patchEntity($subsidy_exist,$request_data,['validate'=>'tab7']);
			$saveText					= 'updated';
		}
		$SubsidyEntity->updated 		= $this->NOW();
		$SubsidyEntity->updated_by 		= $customerId;
		$SubsidyEntity->subsidy_submit 	= '0';
		$flag_submit 					= 0;
		if($SubsidyEntity->tab1_submit==1 && $SubsidyEntity->tab2_submit==1 && $SubsidyEntity->tab3_submit==1 && $SubsidyEntity->tab4_submit==1 && $SubsidyEntity->tab5_submit==1 && $SubsidyEntity->tab6_submit==1)
		{
			$SubsidyEntity->subsidy_submit 	= '1';
			$flag_submit 					= 1;
		}
		if(!empty($SubsidyEntity->errors()))
		{
			return json_encode(array('success'=>'0','response_errors'=>$SubsidyEntity->errors()));
		}
		else
		{
			$SubsidyEntity->signing_authority	= $this->Subsidy->dataPass['signing_authority'];
			$SubsidyEntity->charity_certificate	= $this->Subsidy->dataPass['charity_certificate'];
			$SubsidyEntity->authority_letter 	= $this->Subsidy->dataPass['authority_letter'];
			$SubsidyEntity->formb 				= $this->Subsidy->dataPass['formb'];
			$SubsidyEntity->formc 				= $this->Subsidy->dataPass['formc'];
			$SubsidyEntity->affidavit 			= $this->Subsidy->dataPass['affidavit'];
			$SubsidyEntity->agreement_stamp 	= $this->Subsidy->dataPass['agreement_stamp'];
			$SubsidyEntity->social_excel 		= $this->Subsidy->dataPass['social_excel'];
			$SubsidyEntity->social_pdf 			= $this->Subsidy->dataPass['social_pdf'];
			if(isset($request_data['Subsidy']['signing_authority']['tmp_name']) && !empty($request_data['Subsidy']['signing_authority']['tmp_name']))
            {
                $file_name 							= $this->imgfile_upload ($request_data['Subsidy']['signing_authority'],'s_a_',$request_data['Subsidy']['application_id'],'signing_authority','signing_authority');
                $SubsidyEntity->signing_authority	= $file_name;
            }
            if(isset($request_data['Subsidy']['charity_certificate']['tmp_name']) && !empty($request_data['Subsidy']['charity_certificate']['tmp_name']))
            {
                $file_name 							= $this->imgfile_upload ($request_data['Subsidy']['charity_certificate'],'c_c_',$request_data['Subsidy']['application_id'],'charity_certificate','charity_certificate');
                $SubsidyEntity->charity_certificate = $file_name;
            }
			if(isset($request_data['Subsidy']['authority_letter']['tmp_name']) && !empty($request_data['Subsidy']['authority_letter']['tmp_name']))
            {
                $file_name 							= $this->imgfile_upload ($request_data['Subsidy']['authority_letter'],'a_l_',$request_data['Subsidy']['application_id'],'authority_letter','authority_letter');
                $SubsidyEntity->authority_letter 	= $file_name;
            }
            if(isset($request_data['Subsidy']['formb']['tmp_name']) && !empty($request_data['Subsidy']['formb']['tmp_name']))
            {
                $file_name 							= $this->imgfile_upload ($request_data['Subsidy']['formb'],'formb_',$request_data['Subsidy']['application_id'],'formb','formb');
                $SubsidyEntity->formb 				= $file_name;
            }
            if(isset($request_data['Subsidy']['formc']['tmp_name']) && !empty($request_data['Subsidy']['formc']['tmp_name']))
            {
                $file_name 							= $this->imgfile_upload ($request_data['Subsidy']['formc'],'formc_',$request_data['Subsidy']['application_id'],'formc','formc');
                $SubsidyEntity->formc 				= $file_name;
            }
            if(isset($request_data['Subsidy']['affidavit']['tmp_name']) && !empty($request_data['Subsidy']['affidavit']['tmp_name']))
            {
                $file_name 							= $this->imgfile_upload ($request_data['Subsidy']['affidavit'],'aff_',$request_data['Subsidy']['application_id'],'affidavit','affidavit');
                $SubsidyEntity->affidavit 			= $file_name;
            }
            if(isset($request_data['Subsidy']['agreement_stamp']['tmp_name']) && !empty($request_data['Subsidy']['agreement_stamp']['tmp_name']))
            {
                $file_name 							= $this->imgfile_upload ($request_data['Subsidy']['agreement_stamp'],'a_s_',$request_data['Subsidy']['application_id'],'agreement_stamp','agreement_stamp');
                $SubsidyEntity->agreement_stamp 	= $file_name;
            }
            if(isset($request_data['Subsidy']['social_excel']['tmp_name']) && !empty($request_data['Subsidy']['social_excel']['tmp_name']))
            {
                $file_name 							= $this->imgfile_upload ($request_data['Subsidy']['social_excel'],'excel_',$request_data['Subsidy']['application_id'],'social_excel','social_excel');
                $SubsidyEntity->social_excel 		= $file_name;
            }
            if(isset($request_data['Subsidy']['social_pdf']['tmp_name']) && !empty($request_data['Subsidy']['social_pdf']['tmp_name']))
            {
                $file_name 							= $this->imgfile_upload ($request_data['Subsidy']['social_pdf'],'pdf_',$request_data['Subsidy']['application_id'],'social_pdf','social_pdf');
                $SubsidyEntity->social_pdf 			= $file_name;
            }
            $SubsidyEntity->bidirectional_meter_date= date('Y-m-d',strtotime($SubsidyEntity->bidirectional_meter_date));

			$this->Subsidy->save($SubsidyEntity);
			if($flag_submit==0)
			{
				$this->Flash->success("Somedata of any tab remaining to submit.");
			}
			else
			{
				$status = $this->ApplyOnlineApprovals->CLAIM_SUBSIDY;
				$reason = '';
				$this->SetApplicationStatus($status,$request_data['Subsidy']['application_id'],$reason);
				$this->Flash->success("Subsidy Submitted successfully.");
			}
			return json_encode(array('success'=>'1','response_errors'=>''));
		}
	}

	/**
    *
    * imgfile_upload
    *
    * Behaviour : public
    *
    * @param : id  : $file is use to identify for which image should be select and $path is use to identify the image folder path.
    *
    * @defination : Method is use to save the image in file folder .
    *
    */
    public function imgfile_upload($file,$prefix_file='',$application_id,$file_field,$access_type='')
    {
    	$customerId 	= $this->Session->read('Customers.id');
    	$name 			= $file['name'];
        $path 			= WWW_ROOT.SUBSIDY_PATH.$application_id.'/';
        if(!file_exists(SUBSIDY_PATH.$application_id)){
            @mkdir(SUBSIDY_PATH.$application_id, 0777,true);
        }
        $subsidyData 	= $this->Subsidy->viewSubsidy($application_id);
        if(!empty($subsidyData->$file_field) && file_exists($path.$subsidyData->$file_field))
        {
        	@unlink($path.$subsidyData->$file_field);
        }
        $ext    		= substr(strtolower(strrchr($file['name'], '.')), 1);
        $file_name   	= $prefix_file.date('YmdHis').rand();
        $file_location  = $path.$file_name.'.'.$ext;
        move_uploaded_file($file['tmp_name'],$file_location);
        $passFileName 	= $file_name.'.'.$ext;
		$couchdbId 		= $this->Couchdb->saveData($path,$file_location,$prefix_file,$passFileName,$customerId,$access_type);
        return $file_name.'.'.$ext;
    }

    /**
    *
    * copyfile_upload
    *
    * Behaviour : public
    *
    * @param : id  : $file is use to identify for which  should be select and $path is use to identify the image folder path.
    *
    * @defination : Method is use to save the image in file folder .
    *
    */
    public function copyfile_upload($file,$source_path='',$prefix_file='',$application_id)
    {
    	$path 			= WWW_ROOT.SUBSIDY_PATH.$application_id.'/';
        if(!file_exists(SUBSIDY_PATH.$application_id)){
            @mkdir(SUBSIDY_PATH.$application_id, 0777,true);
        }
        $ext    		= substr(strtolower(strrchr($file, '.')), 1);
        $file_name   	= $prefix_file.date('YmdHis').rand();
        $file_location  = $path.$file_name.'.'.$ext;
        copy($source_path.$file,$file_location);
        return $file_name.'.'.$ext;
    }

	/**
    *
    * getapplication
    *
    * Behaviour : public
    *
    * @param :
    *
    * @defination : Test method is use to get all documents for particular application .
    *
    */
    public function getapplication()
    {
    	$this->autoRender 	= false;
    	$this->Subsidy->GetApplicationDocuments('28');
    }

	/**
     *
     * claimsubsidy
     *
     * Behaviour : public
     *
     * @param : $request_data   : tab1 form posted data should be passed
     * @defination : Method is use to check validation of first tab and insert/update subsidy record for first tab.
     *
     */
	public function claimsubsidy()
	{
		$this->validateAccess("REVIEW_SUBSIDY_REQUEST");
		$this->setCustomerArea();
		$customerId 			= $this->Session->read("Customers.id");
		$ses_customer_type 		= $this->Session->read('Customers.customer_type');
		$InstallerID 			= 0;
		if($ses_customer_type == "installer") {
			$is_installer 		= true;
			$customer_details 	= $this->Customers->find('all',array('conditions'=>array('id'=>$customerId)))->first();
			$InstallerID 		= $customer_details['installer_id'];
		}

		$from_date 				= isset($this->request->data['DateFrom'])?$this->request->data['DateFrom']:'';
		$end_date 				= isset($this->request->data['DateTo'])?$this->request->data['DateTo']:'';
		$request_status 		= isset($this->request->data['status'])?$this->request->data['status']:'';
		$request_no 			= isset($this->request->data['request_no'])?$this->request->data['request_no']:'';
		$recevied_status 		= isset($this->request->data['recevied_status'])?$this->request->data['recevied_status']:'';
		$geda_application_no 	= isset($this->request->data['geda_application_no'])?$this->request->data['geda_application_no']:'';
		$arrRequestList			= array();

        $arrCondition['ApplyOnlines.installer_id'] 				= $InstallerID;
        $arrCondition['ApplyOnlines.disclaimer_subsidy'] 		= 0;
		$arrCondition['ApplyOnlines.application_status IN'] 	= array($this->ApplyOnlineApprovals->CLAIM_SUBSIDY);
		//$arrCondition['ApplyOnlines.pcr_submited IS '] 			= NULL;

        $this->SortBy		= "SubsidyRequest.request_date";
        $this->Direction	= "ASC";
        $this->intLimit		= PAGE_RECORD_LIMIT;
        $this->CurrentPage  = 1;
        $option 			= array();
        $option['colName']  = array('id','geda_application_no','request_no','request_date','received_date','action');
        $sortArr 			= array('id'=>'ApplyOnlines.id',
        							'geda_application_no'=>'ApplyOnlines.geda_application_no',
        							'request_no'=>'SubsidyRequest.request_no',
        							'request_date'=>'SubsidyRequestApplication.request_date',
        							'processed'=>'SubsidyRequestApplication.processed',
	        						'received_at'=>'SubsidyRequestApplication.received_at',
	        						'received_date'=>'SubsidyRequestApplication.received_date');
        $this->SetSortingVars('ApplyOnlines',$option,$sortArr);

        $option['dt_selector']			='table-example';
        $option['formId']				='formmain';
        $option['url']					= '';
        $option['recordsperpage']		= PAGE_RECORD_LIMIT;
        $option['allsortable']			= '-all';
        $option['total_records_data']	= 0;
        $option['bPaginate']			= 'true';
        $option['bLengthChange']		= 'false';
        $option['order_by'] 			= "order : [[3,'ASC']]";
        $JqdTablescr 					= $this->JqdTable->create($option);
        $Joins 							= array([	'table'		=> $this->SubsidyRequestApplication->table,
        											'alias' 	=> 'SubsidyRequestApplication',
        											'type' 		=> 'LEFT',
        											'conditions'=> 'SubsidyRequestApplication.application_id = ApplyOnlines.id'],
        										[	'table' 	=> $this->SubsidyRequest->table,
        											'alias' 	=> 'SubsidyRequest',
        											'type'		=> 'LEFT',
        											'conditions'=> 'SubsidyRequestApplication.request_id = SubsidyRequest.id'],
        										);

        if ($this->request->is('ajax'))
        {
        	if ($request_status != '') {
        		if ($request_status != 99) {
        			$arrCondition['SubsidyRequestApplication.processed'] = $request_status;
        		} else if ($request_status == 99) {
        			$arrCondition['SubsidyRequestApplication.processed IS'] = NULL;
        		}
        	}
        	if ($request_no != '') {
        		$arrCondition['SubsidyRequest.request_no'] = $request_no;
        	}
        	if ($recevied_status != '') {
        		$arrCondition['SubsidyRequestApplication.received_at'] = $recevied_status;
        	}
        	if ($geda_application_no != '') {
        		$arrCondition['ApplyOnlines.geda_application_no LIKE '] = '%'.$geda_application_no.'%';
        	}
	        $CountFields		= array('ApplyOnlines.id');
	        $Fields 			= array('ApplyOnlines.id',
	        							'ApplyOnlines.geda_application_no',
	        							'SubsidyRequest.id',
	        							'SubsidyRequest.request_no',
	        							'SubsidyRequestApplication.request_date',
	        							'SubsidyRequestApplication.processed',
	        							'SubsidyRequestApplication.received_at',
	        							'SubsidyRequestApplication.received_date');
	        $query_data 		= $this->ApplyOnlines->find('all',array(	'fields'		=> $Fields,
															            	'conditions' 	=> $arrCondition,
															            	'join' 			=> $Joins,
															            	'order'			=> array($this->SortBy=>$this->Direction),
															            	'page' 			=> $this->CurrentPage,
															            	'limit' 		=> $this->intLimit));
	        if(!empty($from_date) && !empty($end_date))
	        {
	        	$fields_date  	= "SubsidyRequestApplication.request_date";
				$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
				$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
		        $query_data->where([function ($exp, $q) use ($StartTime, $EndTime,$fields_date) {
					return $exp->between($fields_date, $StartTime, $EndTime);
		   		}]);
	        }
	        $query_data_count 	= $this->ApplyOnlines->find('all',array('fields'=>$CountFields,'join'=>$Joins,'conditions'=>$arrCondition));
	        if(!empty($from_date) && !empty($end_date))
	        {
	        	$fields_date  	= "SubsidyRequestApplication.request_date";
				$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
				$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
		        $query_data_count->where([function ($exp, $q) use ($StartTime, $EndTime,$fields_date) {
					return $exp->between($fields_date, $StartTime, $EndTime);
		   		}]);
	        }
			$total_query_records	= $query_data_count->count();
	       	$start_page 			= isset($this->request->data['start']) ? $this->request->data['start'] : 1;
	       	$this->paginate['limit']= PAGE_RECORD_LIMIT;
	       	$this->paginate['page'] = ($start_page/$this->paginate['limit'])+1;
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
	       		$this->paginate['page'] = ($start_page/$this->paginate['limit'])+1;
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
	                	if (is_null($val->SubsidyRequestApplication['processed'])) {
	                    	$temparr[$key]="<input class=\"request-application\" id=\"application_id[]\" name=\"application_id[]\" type=\"checkbox\" value=\"".encode($val[$key])."\" />";
	                    } else {
	                    	$temparr[$key]=$counter + ($page_mul * $this->paginate['limit']);
	                    }
	                } else if($key=='processed') {
	                	if (is_null($val->SubsidyRequestApplication['processed'])) {
	                		$temparr[$key] = "NOT SUBMITTED";
	                	} else if ($val->SubsidyRequestApplication['processed'] == 1) {
	                		$temparr[$key] = "REQUEST PROCESSED";
	                	} else {
	                		$temparr[$key] = "REQUEST PENDING";
	                	}
	                } else if($key=='received_at') {
	                	if ($val->SubsidyRequestApplication['received_at'] == 1)
	                	{
	                		$temparr[$key] = "REQUEST RECEVIED AT GEDA";
	                	} else {
	                		$temparr[$key] = "REQUEST NOT SUBMITTED AT GEDA";
	                	}
	                } else if($key=='request_date') {
	                	if(!is_null($val->SubsidyRequestApplication['request_date']) && !empty($val->SubsidyRequestApplication['request_date']) && trim($val->SubsidyRequestApplication['request_date']) != '0000-00-00 00:00:00')
	                	{
	                		$temparr[$key]	= date('m-d-Y H:i a',strtotime($val->SubsidyRequestApplication['request_date']));
	                	}
	                	else
	                	{
	                		$temparr[$key] 	= '-';
	                	}
	                } else if($key=='received_date') {
	                	if(!is_null($val->SubsidyRequestApplication['received_date']) && !empty($val->SubsidyRequestApplication['received_date'])&& trim($val->SubsidyRequestApplication['received_date']) != '0000-00-00 00:00:00')
	                	{
	                		$temparr[$key]	= date('m-d-Y H:i a',strtotime($val->SubsidyRequestApplication['received_date']));
	                	}
	                	else
	                	{
	                		$temparr[$key]	= '-';
	                	}
	                } else if($key=='request_no') {
	                	if(!is_null($val->SubsidyRequest['request_no']) && !empty($val->SubsidyRequest['request_no']))
	                	{
	                		if($val->SubsidyRequestApplication['received_at'] == 1)
	                		{
	                			$temparr[$key]	= '<a target="SUBSIDY_COVERLETTER" href="'.URL_HTTP.'subsidy/coverletter/'.encode($val->SubsidyRequest['id']).'">'.$val->SubsidyRequest['request_no'].'</a>';
	                		}
	                		else
	                		{
	                			$temparr[$key]	= $val->SubsidyRequest['request_no'];
	                		}
	                	}
	                	else
	                	{
	                		$temparr[$key]	= '-';
	                	}
	                } else if($key=='geda_application_no') {
	                	if(!is_null($val->geda_application_no) && !empty($val->geda_application_no))
	                	{
	                		$temparr[$key]	= '<a href="'.URL_HTTP.'subsidy/'.encode($val->id).'">'.$val->geda_application_no.'</a>';
	                	}
	                	else
	                	{
	                		$temparr[$key]	= '-';
	                	}
	                } else if($key=='action') {
	                	if ($this->ApplyonlineMessage->QueryRaisedForSubsidy($val->id,1)) {
	                		$temparr[$key]	= '<button type="button" class="btn green SubmitRequest" onclick="javascript:show_modal(\''.encode($val->id).'\');">
                        <i class="fa fa-envelope" aria-hidden="true"></i> Reply To Query</button>';
	                	} else {
	                		$temparr[$key]	= '';
	                	}
	                } else if (isset($val[$key])) {
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
					                "recordsTotal"    	=> intval($this->request->params['paging']['ApplyOnlines']['count']),
					                "recordsFiltered" 	=> intval($this->request->params['paging']['ApplyOnlines']['count']),
					                "data"            	=> $out));
            die;
        }
        $REQUEST_STATUS 	= array("0"=>"Email Not Generated","1"=>"Email Generated","99"=>"Claim Not Submitted");
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
        $this->set("pagetitle",'Subsidy Claim Request');
        $this->set("page_count",0);
	}

	/**
     *
     * subsidyclaims
     *
     * Behaviour : public
     *
     * @param : $request_data   : tab1 form posted data should be passed
     * @defination : Method is use to check validation of first tab and insert/update subsidy record for first tab.
     *
     */
	public function subsidyclaims()
	{
		$this->validateAccess("APPROVE_SUBSIDY_REQUEST");
		$this->setMemberArea();

		$from_date 			= isset($this->request->data['DateFrom'])?$this->request->data['DateFrom']:'';
		$end_date 			= isset($this->request->data['DateTo'])?$this->request->data['DateTo']:'';
		$request_status 	= isset($this->request->data['status'])?$this->request->data['status']:'';
		$request_no 		= isset($this->request->data['request_no'])?$this->request->data['request_no']:'';
		$recevied_status 	= isset($this->request->data['recevied_status'])?$this->request->data['recevied_status']:'';
		$geda_application_no= isset($this->request->data['geda_application_no'])?$this->request->data['geda_application_no']:'';
		$installer_name 	= isset($this->request->data['installer_name_multi']) && !empty($this->request->data['installer_name_multi'])?explode(",",$this->request->data['installer_name_multi']):'';
		$pcr_code 			= isset($this->request->data['pcr_code'])?$this->request->data['pcr_code']:'';
		$query_sent 		= isset($this->request->data['query_sent'])?$this->request->data['query_sent']:0;
		$startPage 			= (isset($this->request->data['startPage']) && !empty($this->request->data['startPage'])) ? $this->request->data['startPage'] : 1;
		$arrRequestList		= array();
        $arrCondition		= array('SubsidyRequestApplication.id !='=>0);

        $this->SortBy		= "SubsidyRequest.request_date";
        $this->Direction	= "ASC";
        $this->intLimit		= PAGE_RECORD_LIMIT;
        $this->CurrentPage  = 1;
        $Joins 				= array([	'table'		=> $this->ApplyOnlines->table,
										'alias' 	=> 'ApplyOnlines',
										'type' 		=> 'LEFT',
										'conditions'=> 'SubsidyRequestApplication.application_id = ApplyOnlines.id'],
									[	'table' 	=> $this->SubsidyRequest->table,
										'alias' 	=> 'SubsidyRequest',
										'type'		=> 'LEFT',
										'conditions'=> 'SubsidyRequestApplication.request_id = SubsidyRequest.id'],
									[	'table' 	=> $this->Installers->table,
										'alias' 	=> 'Installers',
										'type'		=> 'LEFT',
										'conditions'=> 'ApplyOnlines.installer_id = Installers.id'],
									[	'table' 	=> 'members',
										'alias' 	=> 'Members',
										'type'		=> 'LEFT',
										'conditions'=> 'SubsidyRequestApplication.received_by = Members.id'],
									[	'table' 	=> $this->ApplyonlineMessage->table,
										'alias' 	=> 'applyonline_messages',
										'type'		=> 'LEFT',
										'conditions'=> 'applyonline_messages.application_id = SubsidyRequestApplication.application_id'],
									);
        $option 			= array();
        $MemberId 			= $this->Session->read("Members.id");
        $CountFields		= array('SubsidyRequestApplication.id');
	    $Fields 			= array('SubsidyRequestApplication.id',
	        							'SubsidyRequestApplication.application_id',
	        							'SubsidyRequest.id',
	        							'SubsidyRequest.request_no',
	        							'ApplyOnlines.geda_application_no',
	        							'SubsidyRequestApplication.request_date',
	        							'SubsidyRequestApplication.processed',
	        							'SubsidyRequestApplication.received_at',
	        							'Members.name',
	        							'SubsidyRequestApplication.received_date');
        $memberApproved 	= 1;//in_array($MemberId, $this->ApplyOnlines->ALLOWED_APPROVE_GEDAIDS) ? '1' : '0';
        //if(in_array($MemberId, $this->ApplyOnlines->ALLOWED_APPROVE_GEDAIDS))
        //{
        	$option['colName']  = array('id','request_no','geda_application_no','request_date','received_date','received_by','last_commentby','action');
        	$sortArr 			= array('id'=>'SubsidyRequestApplication.id',
        							'request_no'=>'SubsidyRequest.request_no',
        							'geda_application_no'=>'ApplyOnlines.geda_application_no',
        							'request_date'=>'SubsidyRequestApplication.request_date',
        							'received_date'=>'SubsidyRequestApplication.received_date',
        							'received_by'=>'Members.name',
        							'last_commentby'=>'Members1.name',
        							'received_date'=>'SubsidyRequestApplication.received_date');
        	
        	array_push($Joins, [	'table' 	=> 'members',
										'alias' 	=> 'Members1',
										'type'		=> 'LEFT',
										'conditions'=> 'applyonline_messages.user_id = Members1.id']);
        	array_push($Fields, 'Members1.name');
        /*}
        else
        {
        	$option['colName']  = array('id','request_no','geda_application_no','request_date','received_date','action');
        	$sortArr 			= array('id'=>'SubsidyRequestApplication.id',
        							'request_no'=>'SubsidyRequest.request_no',
        							'geda_application_no'=>'ApplyOnlines.geda_application_no',
        							'request_date'=>'SubsidyRequestApplication.request_date',
        							'received_date'=>'SubsidyRequestApplication.received_date',
        							'received_date'=>'SubsidyRequestApplication.received_date');
        }*/
        $this->SetSortingVars('SubsidyRequestApplication',$option,$sortArr);
        $option['dt_selector']			='table-example';
        $option['formId']				='formmain';
        $option['url']					= '';
        $option['recordsperpage']		= PAGE_RECORD_LIMIT;
        $option['bPaginate']			= 'true';
        $option['bLengthChange']		= 'false';
        $option['total_records_data']	= 0;
        $option['order_by'] 			= "order : [[3,'ASC']]";
        $option['start_from'] 			= $startPage;
        $option['page'] 				= $startPage;
        $JqdTablescr 					= $this->JqdTable->create($option);
        if ($this->request->is('ajax'))
        {
        	if ($request_status != '') {
        		$arrCondition['SubsidyRequestApplication.processed'] 	= $request_status;
        	}
        	if ($request_no != '') {
        		$arrCondition['SubsidyRequest.request_no'] 				= $request_no;
        	}
        	if ($recevied_status != '') {
        		$arrCondition['SubsidyRequestApplication.received_at'] 	= $recevied_status;
        	}
        	if ($geda_application_no != '') {
        		$arrCondition['ApplyOnlines.geda_application_no LIKE '] = '%'.$geda_application_no.'%';
        	}
        	if (!empty($installer_name)) {
        		$arrCondition['Installers.id in'] = $installer_name;
        	}
        	if($pcr_code=='1')
            {
            	$arrCondition['ApplyOnlines.pcr_submited IS NOT '] 		= NULL;
            }
            else if($pcr_code=='0')
            {
            	$arrCondition['ApplyOnlines.pcr_submited IS '] 			= NULL;
            }
            if (!empty($query_sent) && $query_sent==1) {
            	$arrCondition['ApplyOnlines.query_sent'] 				= 1;
            	$arrCondition['applyonline_messages.for_claim'] 		= 1;
            }
            if (!empty($query_sent) && $query_sent==2) {
            	$arrCondition['ApplyOnlines.query_sent'] 				= 0;
            	$arrCondition['applyonline_messages.for_claim'] 		= 2;
            }
	        if (!empty($query_sent) && $query_sent==3) {
            	$arrCondition['SubsidyRequestApplication.received_at'] 	= 0;
            	$arrCondition['ApplyOnlines.query_sent'] 				= 0;
            }
	        $query_data 		= $this->SubsidyRequestApplication->find('all',array(	'fields'		=> $Fields,
															            	'conditions' 	=> $arrCondition,
															            	'join' 			=> $Joins,
															            	'order'			=> array($this->SortBy=>$this->Direction),
															            	'page' 			=> ($startPage),
															            	'limit' 		=> $this->intLimit))->distinct(['SubsidyRequestApplication.application_id']);
	        
	        if(!empty($from_date) && !empty($end_date))
	        {
	        	$fields_date  	= "SubsidyRequestApplication.request_date";
				$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
				$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
		        $query_data->where([function ($exp, $q) use ($StartTime, $EndTime,$fields_date) {
					return $exp->between($fields_date, $StartTime, $EndTime);
		   		}]);
	        }
	        $query_data_count 	= $this->SubsidyRequestApplication->find('all',array('fields'=>$CountFields,'join'=>$Joins,'conditions'=>$arrCondition));
	        if(!empty($from_date) && !empty($end_date))
	        {
	        	$fields_date  	= "SubsidyRequestApplication.request_date";
				$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
				$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
		        $query_data_count->where([function ($exp, $q) use ($StartTime, $EndTime,$fields_date) {
					return $exp->between($fields_date, $StartTime, $EndTime);
		   		}]);
	        }
			$total_query_records	= $query_data_count->count();
	       	$start_page 			= isset($this->request->data['start']) ? $this->request->data['start'] : 1;
	       	$this->paginate['limit']= PAGE_RECORD_LIMIT;
	       	$this->paginate['page'] = ($start_page/$this->paginate['limit'])+1;
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
	        	$temparr = array();
	            foreach($option['colName'] as $key) {
	                if($key=='id') {
	                	if (is_null($val->received_at) || empty($val->received_at)) {
	                    	$temparr[$key]="<input class=\"request-application\" id=\"application_id[]\" name=\"application_id[]\" type=\"checkbox\" value=\"".encode($val->application_id)."\" />";
	                    } else {
	                    	$temparr[$key]=$counter + ($page_mul * $this->paginate['limit']);
	                    }
	                } else if($key=='processed') {
	                	if (is_null($val->processed)) {
	                		$temparr[$key] 	= "NOT SUBMITTED";
	                	} else if ($val->processed == 1) {
	                		$temparr[$key] 	= "REQUEST PROCESSED";
	                	} else {
	                		$temparr[$key] 	= "REQUEST PENDING";
	                	}
	                } else if($key=='received_at') {
	                	if ($val->received_at == 1)
	                	{
	                		$temparr[$key] 	= "REQUEST RECEVIED AT GEDA";
	                	} else {
	                		$temparr[$key] 	= "REQUEST NOT SUBMITTED AT GEDA";
	                	}
	                } else if($key=='request_date') {
	                	if(!is_null($val->request_date) && !empty($val->request_date) && trim($val->request_date) != '0000-00-00 00:00:00')
	                	{
	                		$temparr[$key]	= date('m-d-Y H:i a',strtotime($val->request_date));
	                	}
	                	else
	                	{
	                		$temparr[$key] 	= '-';
	                	}
	                } else if($key=='received_date') {
	                	if(!is_null($val->received_date) && !empty($val->received_date) && trim($val->received_date) != '0000-00-00 00:00:00')
	                	{
	                		$temparr[$key]	= date('m-d-Y H:i a',strtotime($val->received_date));
	                	}
	                	else
	                	{
	                		$temparr[$key]	= '-';
	                	}
	                } else if($key=='request_no') {
	                	if(!is_null($val->SubsidyRequest['request_no']) && !empty($val->SubsidyRequest['request_no']))
	                	{
	                		$temparr[$key]	= $val->SubsidyRequest['request_no'];
	                	}
	                	else
	                	{
	                		$temparr[$key]	= '-';
	                	}
	                } else if($key=='geda_application_no') {
	                	if(!is_null($val->ApplyOnlines['geda_application_no']) && !empty($val->ApplyOnlines['geda_application_no']))
	                	{
	                		$temparr[$key]	= '<a href="'.URL_HTTP.'subsidy/'.encode($val->application_id).'">'.$val->ApplyOnlines['geda_application_no'].'</a>';
	                	}
	                	else
	                	{
	                		$temparr[$key]	= '-';
	                	}
	                } else if($key=='received_by') {
	                	if(!is_null($val->Members['name']) && !empty($val->Members['name']))
	                	{
	                		$temparr[$key]	= $val->Members['name'];
	                	}
	                	else
	                	{
	                		$temparr[$key]	= '-';
	                	}
	                } else if($key=='last_commentby') {
	                	//$messageData 		= $this->ApplyonlineMessage->QueryRaisedBy($val->application_id,1);
	                	if(!is_null($val->Members1['name']) && !empty($val->Members1['name']))
	                	{
	                		$temparr[$key]	= $val->Members1['name'];
	                	}
	                	else
	                	{
	                		$temparr[$key]	= '-';
	                	}
	                	/*if (!empty($messageData)) {
	                		//$messageData->members['name'];
	                	} else {
	                		$temparr[$key]	= '-';
	                	}*/
	                }else if($key=='action') {
	                	if ($this->ApplyonlineMessage->QueryRaisedForSubsidy($val->application_id,1)) {
	                		$temparr[$key]	= '<span class="text-info bold">WAITING FOR REPLY</span>';
	                	} else {
	                		$temparr[$key]	= '<button type="button" class="btn green SubmitRequest" onclick="javascript:show_modal(\''.encode($val->application_id).'\');">
                        <i class="fa fa-envelope" aria-hidden="true"></i> Send Message</button>';
	                	}
	                } else if (isset($val[$key])) {
	                	$temparr[$key]		= $val[$key];
	                } else {
	                	$temparr[$key]		= "-";
	                }
	            }
	            $counter++;
	            $out[] = $temparr;
	        }
            header('Content-type: application/json');
            echo json_encode(array(	"draw" 				=> intval($this->request->data['draw']),
					                "recordsTotal"    	=> intval($this->request->params['paging']['SubsidyRequestApplication']['count']),
					                "recordsFiltered" 	=> intval($this->request->params['paging']['SubsidyRequestApplication']['count']),
					                "CurrentPage" 		=> $this->CurrentPage,
					                "arrCondition" 		=> $arrCondition,
					                "installer_name" 	=> $installer_name,
					                "data"            	=> $out));
            die;
        }
        $installers_list 	= $this->Installers->find("all",
													[
														'fields'=>['Installers.id','Installers.installer_name'],
														'join'=>['installer_category_mapping'=>['table'=>'installer_category_mapping','conditions'=>'Installers.id=installer_category_mapping.installer_id','type'=>'INNER']],
														'conditions'=> ['installer_category_mapping.category_id IN '=>['1','2']]
													]
													)->toArray();
		$arrlist 			= array('0'=>'--SELECT INSTALLER--');
		foreach($installers_list as $inslist) {
			$arrlist[$inslist->id] = $inslist->installer_name;
		}
		$installers_list 		= $this->Installers->getInstallerListReport();
        $REQUEST_STATUS 	= array("0"=>"Email Not Generated","1"=>"Email Generated");
        $PCR_STATUS 		= array("0"=>"PCR Not Submitted","1"=>"PCR Submitted");
        $RECEVIED_STATUS 	= array("1"=>"YES","0"=>"NO");
        $this->set('arrRequestList',$arrRequestList);
        $this->set('JqdTablescr',$JqdTablescr);
        $this->set('period',$this->period);
        $this->set('limit',$this->intLimit);
        $this->set("CurrentPage",$this->CurrentPage);
        $this->set("SortBy",$this->SortBy);
        $this->set("Direction",$this->Direction);
        $this->set("REQUEST_STATUS",$REQUEST_STATUS);
        $this->set("PCR_STATUS",$PCR_STATUS);
        $this->set("RECEVIED_STATUS",$RECEVIED_STATUS);
        $this->set("Installers",$arrlist);
        $this->set("pagetitle",'Subsidy Claim Request');
        $this->set("memberApproved",$memberApproved);
        $this->set("Installers",$installers_list);
        $this->set("page_count",0);
	}

	/**
     *
     * savesubsidyclaims
     *
     * Behaviour : public
     *
     * @param :
     * @defination : Method is use to check validation of first tab and insert/update subsidy record for first tab.
     *
     */
	public function savesubsidyclaims()
	{
		$this->autoRender 		= false;
		$this->validateAccess("SUBMIT_SUBSIDY_REQUEST");
		$this->setCustomerArea();
		$customerId 			= $this->Session->read("Customers.id");
		$ses_customer_type 		= $this->Session->read('Customers.customer_type');
		$InstallerID 			= 0;
		$Type 					= "error";
		$Message 				= "Invalid Request, Please try again.";
		if($ses_customer_type == "installer") {
			$is_installer 		= true;
			$customer_details 	= $this->Customers->find('all',array('conditions'=>array('id'=>$customerId)))->first();
			$InstallerID 		= $customer_details['installer_id'];
			if (!empty($InstallerID) && $this->request->is('ajax'))
			{
				$Installer_Mapping 	= $this->InstallerCategoryMapping->find('all',array('conditions'=>array('installer_id'=>$InstallerID)))->first();
				$SubsidyRequestCount= $this->SubsidyRequest->find('all',array('conditions'=>array('installer_id'=>$InstallerID)))->count();
				$ApplicationIDs 	= $this->request->data['request_id'];
				$RequestCounter 	= empty($SubsidyRequestCount)?1:($SubsidyRequestCount+1);
				$REQUEST_NO 		= $Installer_Mapping->short_name."_".$RequestCounter."_".date("d_m",strtotime($this->NOW()));
				if (!empty($ApplicationIDs)) {
					$SubsidyRequest 							= $this->SubsidyRequest->newEntity();
					$SubsidyRequest->installer_id 				= $InstallerID;
					$SubsidyRequest->request_no 				= $REQUEST_NO;
					$SubsidyRequest->request_date 				= $this->NOW();
					$SubsidyRequest->ip_address 				= $this->request->clientIp();
					$SubsidyRequest->received_at 				= 0;
					$SubsidyRequest->received_date 				= '';
					$SubsidyRequest->received_ip_address 		= '';
					$SubsidyRequest->processed 					= 0;
					$SubsidyRequest->processed_completion_date 	= '';
					$this->SubsidyRequest->save($SubsidyRequest);
					foreach ($ApplicationIDs as $ApplicationID) {
						$ApplicationCount = $this->SubsidyRequestApplication->find('all',array('conditions'=>array('application_id'=>decode($ApplicationID))))->count();
						if (empty($ApplicationCount))
						{
							$SubsidyRequestApplication 								= $this->SubsidyRequestApplication->newEntity();
							$SubsidyRequestApplication->request_id 					= $SubsidyRequest->id;
							$SubsidyRequestApplication->application_id 				= decode($ApplicationID);
							$SubsidyRequestApplication->processed 					= 0;
							$SubsidyRequestApplication->processed_completion_date 	= '';
							$SubsidyRequestApplication->request_date 				= $this->NOW();
							$this->SubsidyRequestApplication->save($SubsidyRequestApplication);
						}
					}
					$Type 		= "ok";
					$Message 	= "Claim Request added in queue. Please review the Subsidy Claim Report under \"Reports\" option.";
				} else {
					$Message 	= "Please select at least one record to proceed further.";
				}
			}
		}
		$this->ApiToken->SetAPIResponse('msg',$Message);
		$this->ApiToken->SetAPIResponse('type',$Type);
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
     *
     * approvesubsidyclaims
     *
     * Behaviour : public
     *
     * @param :
     * @defination : Method is use to check validation of first tab and insert/update subsidy record for first tab.
     *
     */
	public function approvesubsidyclaims()
	{
		$this->autoRender 		= false;
		$this->validateAccess("APPROVE_SUBSIDY_REQUEST");
		$this->setMemberArea();
		$MemberID 				= $this->Session->read("Members.id");
		$Type 					= "error";
		$Message 				= "Invalid Request, Please try again.";
		$member_type 			= $this->Session->read('Members.member_type');
		if ($member_type == $this->ApplyOnlines->JREDA)
		{
			if (!empty($MemberID) && $this->request->is('ajax'))
			{
				$ApplicationIDs = $this->request->data['request_id'];
				if (!empty($ApplicationIDs))
				{
					foreach ($ApplicationIDs as $ApplicationID)
					{
						$ApplicationID 			= decode($ApplicationID);
						$SubsidyClaimRequest 	= $this->SubsidyRequestApplication->FindByAppID($ApplicationID);
						if (!empty($SubsidyClaimRequest) && isset($SubsidyClaimRequest[0]->request_id))
						{
							$this->SubsidyRequestApplication->updateAll([	'received_at' 			=> 1,
																			'received_by' 			=> $MemberID,
																			'received_ip_address' 	=> $this->request->clientIp(),
																			'received_date' 		=> $this->NOW()],
																			['application_id' 		=> $ApplicationID]);
							$conditions = ["received_at"=>0,"request_id"=>$SubsidyClaimRequest[0]->request_id];
							$Count 		= $this->SubsidyRequestApplication->find("all",["conditions"=>$conditions])->count();
							if ($Count == 0)
							{
								$this->SubsidyRequest->updateAll([	'received_at' 			=> 1,
																	'received_by' 			=> $MemberID,
																	'received_ip_address' 	=> $this->request->clientIp(),
																	'received_date' 		=> $this->NOW()],
																	['id' 					=> $SubsidyClaimRequest[0]->request_id]);
							}
						}
					}
					$Type 		= "ok";
					$Message 	= "Subsidy Claim Received by GEDA.";
				} else {
					$Message 	= "Please select at least one record to proceed further.";
				}
			}
		}
		$this->ApiToken->SetAPIResponse('msg',$Message);
		$this->ApiToken->SetAPIResponse('type',$Type);
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
     *
     * SetApplicationStatus
     *
     * Behaviour : public
     *
     * @param :
     * @defination : Method is use to set application status and send SMS and send mail.
     *
     */
	private function SetApplicationStatus($status,$id,$reason="")
	{
		if(!empty($this->Session->read('Members.member_type')))
		{
			$member_id 				= $this->Session->read("Members.id");
		}
		else
		{
			$member_id 				= $this->Session->read("Customers.id");
		}
		$applyOnlinesData 	= $this->ApplyOnlines->viewApplication($id);
		if ($this->ApplyOnlineApprovals->validateNewStatus($status,$applyOnlinesData->application_status) || $status=='CANCELLED_REOPEN')
		{
			if($status!='CANCELLED_REOPEN')
			{
				$arrData 			= array("application_status"=>$status);
        		$this->ApplyOnlines->updateAll($arrData,['id' => $id]);
        	}
        	$sms_text 				= '';
        	$subject 				= '';
        	$EmailVars 				= array();
			$sms_template 			= '';
        	if($status==$this->ApplyOnlineApprovals->CLAIM_SUBSIDY)
        	{
        		$sms_text 			= str_replace('##geda_application_no##',$applyOnlinesData->geda_application_no,CLAIM_SUBSIDY);
				$sms_template 		= 'CLAIM_SUBSIDY';
				$subject 			= "[REG: GEDA Application No. ".$applyOnlinesData->geda_application_no."] Subsidy Claimed";
				$CUSTOMER_NAME 		= trim($applyOnlinesData->customer_name_prefixed." ".$applyOnlinesData->name_of_consumer_applicant." ".$applyOnlinesData->last_name." ".$applyOnlinesData->third_name);
				$EmailVars 			= array("CUSTOMER_NAME"=>$CUSTOMER_NAME,'geda_no'=>$applyOnlinesData->geda_application_no);
				$template_applied 	= 'claimed_subsidy';
        	}

        	if($sms_text!='')
        	{
        		if(!empty($applyOnlinesData->consumer_mobile))
				{
					$this->ApplyOnlines->sendSMS($id,$applyOnlinesData->consumer_mobile,$sms_text,$sms_template);
				}
				if(!empty($applyOnlinesData->installer_mobile))
				{
					//$this->ApplyOnlines->sendSMS($id,$applyOnlinesData->installer_mobile,$sms_text);
				}
        	}
        	if($subject!='')
        	{
        		if(!empty($applyOnlinesData->installer_email))
			    {
					$email 			= new Email('default');
					$email->profile('default');
					$email->viewVars($EmailVars);
					$message_send 	= $email->template($template_applied, 'default')
							->emailFormat('html')
							->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
						    ->to($applyOnlinesData->installer_email)
						    ->subject(Configure::read('EMAIL_ENV').$subject)
						    ->send();
					/* Email Log */
                    $Emaillog                  = $this->Emaillog->newEntity();
                    $Emaillog->email           = $applyOnlinesData->installer_email;
                    $Emaillog->send_date       = $this->NOW();
                    $Emaillog->action          = Configure::read('EMAIL_ENV').$subject;
                    $Emaillog->description     = json_encode(array('EMAIL_ADDRESS' => $applyOnlinesData->installer_email,'EmailVars' => $EmailVars,'URL_HTTP'=>URL_HTTP));
                    $this->Emaillog->save($Emaillog);
                    /* Email Log */
			    }
			    $to 				= empty($applyOnlinesData->consumer_email) ? $applyOnlinesData->email : $applyOnlinesData->consumer_email;
			    if(!empty($to))
			    {
			    	$email 			= new Email('default');
					$email->profile('default');
					$email->viewVars($EmailVars);
					$message_send 	= $email->template($template_applied, 'default')
							->emailFormat('html')
							->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
						    ->to($to)
						    ->subject(Configure::read('EMAIL_ENV').$subject)
						    ->send();
					/* Email Log */
                    $Emaillog                  = $this->Emaillog->newEntity();
                    $Emaillog->email           = $to;
                    $Emaillog->send_date       = $this->NOW();
                    $Emaillog->action          = Configure::read('EMAIL_ENV').$subject;
                    $Emaillog->description     = json_encode(array('EMAIL_ADDRESS' => $to,'EmailVars' => $EmailVars,'URL_HTTP'=>URL_HTTP));
                    $this->Emaillog->save($Emaillog);
                    /* Email Log */
			    }
        	}
		}
		if($status!='CANCELLED_REOPEN')
		{
			$this->ApplyOnlineApprovals->saveStatus($id,$status,$member_id,$reason);
		}
	}

	/**
     *
     * subsidypaymentreport
     *
     * Behaviour : public
     *
     * @param :
     * @defination : Method is use to list report for subsidy payment.
     *
     */
	public function subsidypaymentreport()
	{
		$this->validateAccess("SUBSIDY_PAYMENT_REPORT");
		$this->setMemberArea();
		$installer_id 		= (!empty($this->request->data('installer_id'))?$this->request->data('installer_id') : 0);
		$exporttype 		= (!empty($this->request->data('exporttype'))?$this->request->data('exporttype') : "");
		$arrSubsidyPayment 	= $this->SubsidyRequestApplication->find('all',array(
											'fields' 	=> array('apply_onlines.pv_capacity',
																'apply_onlines.geda_application_no',
																'apply_onlines.social_consumer',
																'apply_onlines.common_meter',
																'SubsidyRequestApplication.received_date',
																'SubsidyRequestApplication.application_id',
																'subsidy_claim_requests.request_no',
																'projects.estimated_cost',
																'projects.recommended_capacity',
																'projects.state',
																'projects.customer_type',
																'installer_category_mapping.category_id'),
											'conditions'=> array('SubsidyRequestApplication.received_at'=>'1','SubsidyRequestApplication.processed'=>'1','apply_onlines.installer_id in '=>$installer_id),
											'join'		=> [['table'=>'apply_onlines','conditions'=>'SubsidyRequestApplication.application_id=apply_onlines.id','type'=>'left'],
											['table'=>'installer_category_mapping','conditions'=>'apply_onlines.installer_id=installer_category_mapping.installer_id','type'=>'left'],
															['table'=>'projects','conditions'=>'apply_onlines.project_id=projects.id','type'=>'left'],
															['table'=>'subsidy_claim_requests','conditions'=>'subsidy_claim_requests.id=SubsidyRequestApplication.request_id','type'=>'left']],
											'order'		=> array('subsidy_claim_requests.request_no'=>'asc')))->toArray();
		//$InstallerCategory 	= $this->InstallerCategoryMapping->find('all',array('conditions'=>array('installer_id'=>$installer_id)))->first();
		if (!empty($exporttype) && $exporttype == "csv") {
			$this->autoRender = false;
			$this->DownloadPaymentReport($arrSubsidyPayment);
		}
		$installers_list 	= $this->Installers->find("all",
													[
														'fields'=>['Installers.id','Installers.installer_name'],
														'join'=>['installer_category_mapping'=>['table'=>'installer_category_mapping','conditions'=>'Installers.id=installer_category_mapping.installer_id','type'=>'INNER']],
														'conditions'=> ['installer_category_mapping.category_id IN '=>['1','2']]
													]
													)->toArray();
		$arrlist 			= array('0'=>'--SELECT INSTALLER--');
		foreach($installers_list as $inslist) {
			$arrlist[$inslist->id] = $inslist->installer_name;
		}
		$this->set("pagetitle",'Subsidy Payment Report');
		$this->set("arrSubsidyPayment",$arrSubsidyPayment);
		$this->set("Projects",$this->Projects);
		$this->set("installer_id",$installer_id);
		$this->set("Subsidy",$this->Subsidy);
		$this->set("Installers",$arrlist);
		//$this->set("InstallerCategory",$InstallerCategory);
	}

	/**
    *
    * CreateMyProject
    *
    * Behaviour : private
    *
    * @param : application_id, CreateMyProject
    *
    * @defination : Method is use to update capacity in project and apply online table.
    *
    */
    private function CreateMyProject($application_id=0,$CreateMyProject=true,$set_capacity='')
    {
    	$app_details   		= $this->ApplyOnlines->find('all',array('conditions'=>array('id'=>$application_id)))->first();
    	$subsidy_details   	= $this->Subsidy->find('all',array('conditions'=>array('application_id'=>$application_id)))->first();
		$project_data       = $this->Projects->find('all',array('conditions'=>array('id'=>$app_details->project_id)))->first();
        if(!empty($project_data))
        {
        	if(strtolower($project_data->state)!='gujarat')
        	{
        		$this->Projects->updateAll([
        									'state' 			=> 'Gujarat',
        									'state_short_name' 	=> 'GJ'
        									],
        									['id' 	=> $app_details->project_id]);
        		$project_data       = $this->Projects->find('all',array('conditions'=>array('id'=>$app_details->project_id)))->first();
        	}
        	$latitude 		= $project_data->latitude;
        	$longitude 		= $project_data->longitude;
        	$pv_app_capacity= $app_details->pv_capacity;

            if(!empty($subsidy_details->latitude) && !empty($subsidy_details->longitude))
            {
            	$latitude 		= $subsidy_details->latitude;
        		$longitude 		= $subsidy_details->longitude;
        		$pv_app_capacity= $project_data->recommended_capacity;
            }
            if($set_capacity != '')
            {
                $pv_app_capacity= $set_capacity;
            }

            $arr_project_data['proj_name']              = $project_data->name;
            $arr_project_data['latitude']               = $latitude;
            $arr_project_data['longitude']              = $longitude;
            $arr_project_data['customer_type']          = $project_data->customer_type;
            $arr_project_data['project_type']           = $project_data->customer_type;
            $arr_project_data['area']                   = $project_data->area;
            $arr_project_data['area_type']              = $project_data->area_type;
            $arr_project_data['bill']                   = $project_data->avg_monthly_bill;
            $arr_project_data['avg_monthly_bill']       = $project_data->avg_monthly_bill;
            $arr_project_data['backup_type']            = $project_data->backup_type;
            $arr_project_data['usage_hours']            = $project_data->usage_hours;
            $arr_project_data['energy_con']             = $project_data->estimated_kwh_year;
            $arr_project_data['recommended_capacity']   = $pv_app_capacity;
            $arr_project_data['address']                = $project_data->address;
            $arr_project_data['city']                   = $project_data->city;
            $arr_project_data['state']                  = $project_data->state;
            $arr_project_data['state_short_name']       = $project_data->state_short_name;
            $arr_project_data['country']                = $project_data->country;
            $arr_project_data['postal_code']            = $project_data->pincode;
            $arr_project_data['Projects']['id']         = $project_data->id;
            $result                                     = $this->Projects->getprojectestimationV2($arr_project_data,$app_details->customer_id,$CreateMyProject);
        }
		return $result;
    }

    /**
	 * generateSubsidyCoverLetterPDF
	 * Behaviour : public
	 * @param : $request_id
	 * @defination : Method is use to generate coverletter pdf
	 */
	public function generateSubsidyCoverLetterPDF($id=0)
	{
		$this->validateAccess("DOWNLOAD_CLAIM_COVERLETTER_PDF");
		$this->setCustomerArea();
		$Conditions 					= array('request_id'=>decode($id));
		$isdownload 					= false;
		$ListOfApplications				= $this->SubsidyRequestApplication->find('all',array('conditions'=>$Conditions))->toArray();
		$SubsidyRequestDetails 			= $this->SubsidyRequest->find('all',array('conditions' => array('id'=>decode($id))))->first();

		if (empty($SubsidyRequestDetails)) {
			$this->Flash->error('Please select valid subsidy claim request.');
			return $this->redirect(URL_HTTP.'apply-online-list');
		}

		$No_of_Projects					= 0;
		$Total_Capacity					= 0;
		$No_of_Residential				= 0;
		$Total_Capacity_Residential		= 0;
		$No_of_Social_Sector			= 0;
		$Total_Capacity_Social_Sector	= 0;
		$Total_MNRE_Amount 				= 0;
		$Total_State_Amount 			= 0;
		$Total_Subsidy_Amount 			= 0;
		$Total_Geda_Inspection_Report 	= 0;
		$Application_Nos				= array();
		foreach($ListOfApplications as $ListOfApplication)
		{
			$ApplyOnline 	= $this->ApplyOnlines->viewApplication($ListOfApplication->application_id);
			$SubsidyDetails	= $this->Subsidy->find('all',array('conditions'=>array('application_id'=>$ListOfApplication->application_id)))->first();
			$ProjectData 	= $this->Projects->get($ApplyOnline->project_id);
			array_push($Application_Nos,$ApplyOnline->geda_application_no);
			$No_of_Projects++;
			$Total_Capacity += $ApplyOnline->pv_capacity;
			if ($ApplyOnline->category == $this->ApplyOnlines->category_residental) {
				if ($ApplyOnline->social_consumer == 1) {
					$No_of_Social_Sector++;
					$Total_Capacity_Social_Sector += $ApplyOnline->pv_capacity;
				} else {
					$No_of_Residential++;
					$Total_Capacity_Residential += $ApplyOnline->pv_capacity;
				}
				$capitalCost 		= $this->Projects->calculatecapitalcost($ProjectData->recommended_capacity,$ProjectData->state,$ProjectData->customer_type);
				$SubsidyDetailInfo 	= $this->Projects->calculatecapitalcostwithsubsidy($ProjectData->recommended_capacity,$capitalCost,$ProjectData->state,$ProjectData->customer_type,true,$ApplyOnline->social_consumer);
				if($ApplyOnline->social_consumer==1 || $ApplyOnline->common_meter==1)
	            {
	            	$SubsidyDetailInfo['state_subsidy_amount'] 	= 0;
	            }
				$Total_MNRE_Amount += $SubsidyDetailInfo['central_subsidy_amount'];
				$Total_State_Amount += $SubsidyDetailInfo['state_subsidy_amount'];
				$Total_Subsidy_Amount += $SubsidyDetailInfo['total_subsidy'];
			}
			if(isset($SubsidyDetails->geda_inspection_report) && !empty($SubsidyDetails->geda_inspection_report))
            {
                $path = SUBSIDY_PATH.$SubsidyDetails->application_id."/".$SubsidyDetails->geda_inspection_report;
                if (file_exists($path))
                {
                    $Total_Geda_Inspection_Report++;
                }
            }
			$INSTALLER_NAME = $ApplyOnline->installer['installer_name'];
		}

		$GEDA_APPLICATION_NOS 		= implode("<br />",$Application_Nos);
		$REQUEST_GENERATION_DATE 	= date("d/M/Y",strtotime($SubsidyRequestDetails->request_date));

		$view 			= new View();
    	$view->layout 	= false;
    	$view->set('REQUEST_GENERATION_DATE',$REQUEST_GENERATION_DATE);
		$view->set('COVER_LETTER_ADDRESS_DETAILS',SUBSIDY_COVER_LETTER_ADDRESS);
		$view->set('INSTALLER_NAME',$INSTALLER_NAME);
		$view->set('No_of_Projects',$No_of_Projects);
		$view->set('Total_Capacity',$Total_Capacity);
		$view->set('No_of_Residential',$No_of_Residential);
		$view->set('Total_Capacity_Residential',$Total_Capacity_Residential);
		$view->set('No_of_Social_Sector',$No_of_Social_Sector);
		$view->set('Total_Capacity_Social_Sector',$Total_Capacity_Social_Sector);
		$view->set('Total_MNRE_Amount',_FormatNumberV2($Total_MNRE_Amount));
		$view->set('Total_State_Amount',_FormatNumberV2($Total_State_Amount));
		$view->set('Total_Subsidy_Amount',_FormatNumberV2($Total_Subsidy_Amount));
		$view->set('GEDA_APPLICATION_NOS',$GEDA_APPLICATION_NOS);
		$view->set('Total_Geda_Inspection_Report',$Total_Geda_Inspection_Report);
		$view->set('REQUEST_NO',$SubsidyRequestDetails->request_no);

		/* Generate PDF for estimation of project */
		require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
		$dompdf = new Dompdf($options = array());
		$dompdf->set_base_path(WWW_ROOT.'/pdf/');
		$view->set('dompdf',$dompdf);
		$html = $view->render('/Element/subsidy_coverletter');
		$dompdf->loadHtml($html,'UTF-8');
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();
		// Output the generated PDF to Browser
		$output = $dompdf->output();
		header("Content-type:application/pdf");
		header("Content-Disposition:inline;filename='".$SubsidyRequestDetails->request_no.".pdf'");
		echo $output;
		die;
	}

	/**
	 * DownloadPaymentReport
	 * Behaviour : private
	 * @param : $ReportRow
	 * @defination : Method is use to download payment report
	 */
	private function DownloadPaymentReport($ReportRows,$InstallerCategory=array())
	{
		$TotalStateSubsidy  = 0;
        $TotalMnreSubsidy   = 0;
        $TotalPvCapacity    = 0;
        $TotalDeduction     = 0;
        $TotalNetPayable    = 0;
        $RowID 				= 1;
        //$PhpExcel 			= $this->PhpExcel;
        $PhpExcel 			= new \PHPExcel();
      //  $PhpExcel->createExcel();
        //$PhpExcel->additonalSheet(1,'Subsidy Payment Report');
       /* $PhpExcel->writeCellValue('A'.$RowID, 'Sr No.');
        $PhpExcel->writeCellValue('B'.$RowID, 'Request No.');
        $PhpExcel->writeCellValue('C'.$RowID, 'GEDA Reg. No.');
        $PhpExcel->writeCellValue('D'.$RowID, 'PV Capacity (kW)');
        $PhpExcel->writeCellValue('E'.$RowID, 'MNRE Subsidy (Rs.)');
        $PhpExcel->writeCellValue('F'.$RowID, 'State Subsidy (Rs.)');
        $PhpExcel->writeCellValue('G'.$RowID, 'Deduction (Rs.)');
        $PhpExcel->writeCellValue('H'.$RowID, 'NET Payable By GEDA (Rs.)'); */
        $PhpExcel->setActiveSheetIndex(0)->setTitle('Subsidy Payment Report');
        $PhpExcel->setActiveSheetIndex(0)->setCellValue('A'.$RowID, 'Sr No.');
        $PhpExcel->setActiveSheetIndex(0)->setCellValue('B'.$RowID, 'Request No.');
        $PhpExcel->setActiveSheetIndex(0)->setCellValue('C'.$RowID, 'GEDA Reg. No.');
        $PhpExcel->setActiveSheetIndex(0)->setCellValue('D'.$RowID, 'PV Capacity (kW)');
        $PhpExcel->setActiveSheetIndex(0)->setCellValue('E'.$RowID, 'MNRE Subsidy (Rs.)');
        $PhpExcel->setActiveSheetIndex(0)->setCellValue('F'.$RowID, 'State Subsidy (Rs.)');
        $PhpExcel->setActiveSheetIndex(0)->setCellValue('G'.$RowID, 'Deduction (Rs.)');
        $PhpExcel->setActiveSheetIndex(0)->setCellValue('H'.$RowID, 'NET Payable By GEDA (Rs.)');
        if (!empty($ReportRows))
        {
	        foreach($ReportRows as $key=>$val)
	        {
	        	$subsidy_data   = $this->Projects->calculatecapitalcostwithsubsidy($val['projects']['recommended_capacity'],$val['projects']['estimated_cost'],$val['projects']['state'],$val['projects']['customer_type'],true,$val['apply_onlines']['social_consumer']);
	            if ($subsidy_data['state_subcidy_type'] == 0) {
	                $STATE_SUBSIDY          = $subsidy_data['state_subsidy']."%";
	                $STATE_SUBSIDY_AMOUNT   = ($subsidy_data['state_subsidy_amount'] > 0)?$subsidy_data['state_subsidy_amount']:"0.00";
	            } else {
	                $STATE_SUBSIDY          = ($subsidy_data['state_subsidy'] > 0)?$subsidy_data['state_subsidy']:"-";
	                $STATE_SUBSIDY_AMOUNT   = ($subsidy_data['state_subsidy_amount'] > 0)?$subsidy_data['state_subsidy_amount']:"0.00";
	            }
	            if ($subsidy_data['central_subcidy_type'] == 0) {
	                $CENTRAL_SUBSIDY            = $subsidy_data['central_subsidy']."%";
	                $CENTRAL_SUBSIDY_AMOUNT     = ($subsidy_data['central_subsidy_amount'] > 0)?$subsidy_data['central_subsidy_amount']:"0.00";
	            } else {
	                $CENTRAL_SUBSIDY            = ($subsidy_data['central_subsidy'] > 0)?$subsidy_data['central_subsidy']:"-";
	                $CENTRAL_SUBSIDY_AMOUNT     = ($subsidy_data['central_subsidy_amount'] > 0)?$subsidy_data['central_subsidy_amount']:"0.00";
	            }
	            if($val['apply_onlines']['social_consumer']==1 || $val['apply_onlines']['common_meter']==1)
	            {
	                $STATE_SUBSIDY          	= 0;
	                $STATE_SUBSIDY_AMOUNT   	= 0;
	            }
	            $TotalSubsidy       = $CENTRAL_SUBSIDY_AMOUNT + $STATE_SUBSIDY_AMOUNT;
	            $TotalPvCapacity    = $TotalPvCapacity + $val['projects']['recommended_capacity'];
	            $Deduction 			= $this->Subsidy->calculateDeduction($TotalPvCapacity,$val['installer_category_mapping']['category_id'],$val['projects']['estimated_cost']);
	            $NetPayable         = $TotalSubsidy - $Deduction;
	            $TotalMnreSubsidy   = $TotalMnreSubsidy + $CENTRAL_SUBSIDY_AMOUNT;
	            $TotalStateSubsidy  = $TotalStateSubsidy + $STATE_SUBSIDY_AMOUNT;
	            $TotalDeduction     = $TotalDeduction + $Deduction;
	            $TotalNetPayable    = $TotalNetPayable + $NetPayable;

				$RowID++;
				$PhpExcel->setActiveSheetIndex(0)->setCellValue('A'.$RowID, ($RowID-1));
		        $PhpExcel->setActiveSheetIndex(0)->setCellValue('B'.$RowID, $val['subsidy_claim_requests']['request_no']);
		        $PhpExcel->setActiveSheetIndex(0)->setCellValue('C'.$RowID, $val['apply_onlines']['geda_application_no']);
		        $PhpExcel->setActiveSheetIndex(0)->setCellValue('D'.$RowID, $val['projects']['recommended_capacity']);
		        $PhpExcel->setActiveSheetIndex(0)->setCellValue('E'.$RowID, _FormatNumberV2($CENTRAL_SUBSIDY_AMOUNT));
		        $PhpExcel->setActiveSheetIndex(0)->setCellValue('F'.$RowID, _FormatNumberV2($STATE_SUBSIDY_AMOUNT));
		        $PhpExcel->setActiveSheetIndex(0)->setCellValue('G'.$RowID, (($Deduction>0)?_FormatNumberV2($Deduction):"0.00"));
		        $PhpExcel->setActiveSheetIndex(0)->setCellValue('H'.$RowID, _FormatNumberV2($NetPayable));
	        }

			$RowID++;
			$PhpExcel->setActiveSheetIndex(0)->setCellValue('A'.$RowID, "");
	        $PhpExcel->setActiveSheetIndex(0)->setCellValue('B'.$RowID, "");
	        $PhpExcel->setActiveSheetIndex(0)->setCellValue('C'.$RowID, "Total");
	        $PhpExcel->setActiveSheetIndex(0)->setCellValue('D'.$RowID, $TotalPvCapacity);
	        $PhpExcel->setActiveSheetIndex(0)->setCellValue('E'.$RowID, _FormatNumberV2($TotalMnreSubsidy));
	        $PhpExcel->setActiveSheetIndex(0)->setCellValue('F'.$RowID, _FormatNumberV2($TotalStateSubsidy));
	        $PhpExcel->setActiveSheetIndex(0)->setCellValue('G'.$RowID, (($TotalDeduction>0)?_FormatNumberV2($TotalDeduction):"0.00"));
	        $PhpExcel->setActiveSheetIndex(0)->setCellValue('H'.$RowID, _FormatNumberV2($TotalNetPayable));
	    }
	    $randNum 		= strtoupper(substr(md5(uniqid(mt_rand())),0,10));
		$exportFile		= "EXPORT_".$randNum;
	    //$PhpExcel->downloadFile($exportFile);
	    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$exportFile.'.xlsx"');
		$objWriter = \PHPExcel_IOFactory::createWriter($PhpExcel, 'Excel2007');
		@$objWriter->save('php://output');
		die();
	}

	/**
	 * CanEditSubsidyDetails
	 * Behaviour : private
	 * @param : $application_id
	 * @return : boolean $CanEdit
	 * @defination : Method is use to download payment report
	 */
	private function CanEditSubsidyDetails($application_id=0)
	{
		$ApplyOnlines = $this->ApplyOnlines->find();
		$ApplyOnlines->hydrate(false);
		$ApplyOnlines->select('ApplyOnlines.pcr_code','ApplyOnlines.pcr_submited');
		$ApplyOnlines->where(["id"=>$application_id]);
		$arrRequestList = $ApplyOnlines->toList();
		$CanEdit 		= true;
		foreach ($arrRequestList as $selectedRow)
		{
			if (!empty($selectedRow['pcr_code']))
			{
				$CanEdit = false;
			}
		}
		return $CanEdit;
	}
	/**
	 * RemoveGedaLetter
	 * Behaviour : public
	 * @param : pass application_id as posted parameter
	 * @return : json encode
	 * @defination : Method is use to remove geda inspection report from subsidy claim
	 */
	public function RemoveGedaLetter()
	{
		$this->autoRender 	= false;
		$application_id 	= intval(decode($this->request->data['application_id']));
		$result 			= 0;
		if(!empty($application_id))
		{
			$subsidyDetails = $this->Subsidy->find('all',array('conditions'=>array('application_id'=>$application_id)))->first();
			if(!empty($subsidyDetails) && !empty($subsidyDetails->geda_inspection_report))
			{
				$path = SUBSIDY_PATH.$subsidyDetails->application_id."/".$subsidyDetails->geda_inspection_report;
                if (file_exists($path))
                {
                	unlink($path);
                	$this->Subsidy->updateAll(array('geda_inspection_report'=>'','updated'=>$this->NOW()),array('application_id'=>$application_id));
                	$result = 1;
                }
			}
		}
		echo json_encode(array('success'=>$result));
	}
}
?>
