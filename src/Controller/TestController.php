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
use Hdfc\Hdfc;
use AES\AES;



class TestController extends FrontAppController
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
		$this->loadModel('CronApiProcess');
		$this->loadModel('ThirdpartyApiLog');
		$this->loadModel('ApplicationDeleteLog');
		$this->loadModel('UniqueVisitorCount');
		$this->loadModel('ApplicationPaymentRequest');
		$this->loadModel('InstallerPaymentRequest');
		$this->loadModel('InstallerPayment');
		$this->loadModel('Payumoney');
		$this->loadModel('SendRegistrationFailure');
		$this->set('ApplyonlineMessage',$this->ApplyonlineMessage);
		$this->set('InspectionReport',$this->InspectionReport);
		$this->loadModel('Emaillog');
		$this->loadModel('Couchdb');
		$this->loadModel('ApplyOnlinesOthers');
		$this->loadModel('EInvoice');
		$this->loadModel('FeesReturnApiLog');
		$this->loadModel('FeesReturn');
		$this->loadModel('Developers');
		$this->loadModel('Applications');
		$this->loadModel('GeoApplicationPaymentRequest');
		$this->loadModel('GetConsumerData');

		//test Vishal
		$this->loadModel('DeveloperPaymentRequest');
		$this->loadModel('DeveloperPayment');
		
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
		if((isset($_SERVER['REMOTE_ADDR']) && !in_array($_SERVER['REMOTE_ADDR'],array("203.88.138.46"))))
		{
			//$this->redirect('/home');
		}
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
		$this->setCustomerArea();
		$customerId 			= $this->Session->read("Customers.id");
		$ses_customer_type 		= $this->Session->read('Customers.customer_type');
		if($ses_customer_type == "installer") {
			$is_installer 		= true;
			$customer_details 	= $this->Customers->find('all',array('conditions'=>array('id'=>$customerId)))->first();
			$installer_id 		= $customer_details['installer_id'];
		}
		$id 					= intval(decode($id));
		$ApplyOnlines 			= $this->ApplyOnlines->viewApplication($id);
		$project_id 			= $ApplyOnlines->project_id;
		$Workorder 				= $this->Workorder->find('all',array('conditions'=>array('project_id'=>$project_id)))->first();
		$Project				= $this->Projects->find('all',array('conditions'=>array('id'=>$project_id)))->first();
		$ProjectExecutionData	= $this->Installation->find('all',array('conditions'=>array('project_id'=>$project_id)))->first();
		$Subsidy_errors 		= array();

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
            $min_cap = min($total_commulative,$total_commulative_i);
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
		if(!empty($this->request->data))
		{
			$cur_tab 											= $this->request->data['tab_id'];
			$errors												= array();
			$this->request->data['Subsidy']['application_id'] 	= $id;
			switch ($cur_tab) {
				case '1':
					$response 		= $this->project_details($this->request->data);	
				break;
				case '2':
					$response 		= $this->id_proof($this->request->data,$error_recent_bill);//json_encode(array('success'=>'1','response_errors'=>''));
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
					//$response 	= $this->technical_details($this->request->data,$Project->recommended_capacity,$project_id,$ApplyOnlines->social_consumer);
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
				$tab_id 		= ($cur_tab<7) ? ($cur_tab+1) : $cur_tab;

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
		if ($InstallerID != $installer_id) {
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
        //$arrDistrict 		= array(''=>'Select District');
        $arrDistrict 		= $this->DistrictMaster->find('list',array('keyField'=>'id','valueField'=>'name','order'=>array('name'=>'asc')))->toArray();
       // $arrCategory 		= array(''=>'Select Category');
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
		$this->set("pageTitle","Subsidy Claim Section");
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
	private function project_details($request_data)
	{
		if(!empty($this->Session->read('Members.member_type')))
		{
			$customerId 				= $this->Session->read("Members.id");
		}
		else
		{
			$customerId 				= $this->Session->read("Customers.id");
		}
		$subsidy_exist 					= $this->Subsidy->viewSubsidy($request_data['Subsidy']['application_id']);
		$this->Subsidy->dataPass 		= $request_data['Subsidy'];
		if(empty($subsidy_exist))
		{
			$SubsidyEntity = $this->Subsidy->newEntity($request_data,['validate'=>'tab1']);
			$SubsidyEntity->created 	= $this->NOW();
			$SubsidyEntity->created_by 	= $customerId;
			$saveText					= 'inserted';
		}
		else
		{
			$SubsidyEntity = $this->Subsidy->patchEntity($subsidy_exist,$request_data,['validate'=>'tab1']);
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
	private function id_proof($request_data,$error_bill)
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
                $file_name 					= $this->imgfile_upload ($request_data['Subsidy']['aadhar_card'],'aadhar_',$request_data['Subsidy']['application_id'],'aadhar_card');
                $SubsidyEntity->aadhar_card= $file_name;
            }
			if(isset($request_data['Subsidy']['recent_bill']['tmp_name']) && !empty($request_data['Subsidy']['recent_bill']['tmp_name']))
            {
                $file_name 					= $this->imgfile_upload ($request_data['Subsidy']['recent_bill'],'recent_',$request_data['Subsidy']['application_id'],'recent_bill');
                $SubsidyEntity->recent_bill= $file_name;
            }
            $this->Subsidy->save($SubsidyEntity);
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
                $file_name 					= $this->imgfile_upload ($request_data['Subsidy']['invoice_copy'],'inv_',$request_data['Subsidy']['application_id'],'invoice_copy');
                $SubsidyEntity->invoice_copy= $file_name;
            }
            if(isset($request_data['Subsidy']['mou_document']['tmp_name']) && !empty($request_data['Subsidy']['mou_document']['tmp_name']))
            {
                $file_name 					= $this->imgfile_upload ($request_data['Subsidy']['mou_document'],'mou_',$request_data['Subsidy']['application_id'],'mou_document');
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
                $file_name 								= $this->imgfile_upload ($request_data['Subsidy']['cei_approval_doc'],'cad_',$request_data['Subsidy']['application_id'],'cei_approval_doc');
                $SubsidyEntity->cei_approval_doc		= $file_name;
            }
            if(isset($request_data['Subsidy']['cei_inspection_doc']['tmp_name']) && !empty($request_data['Subsidy']['cei_inspection_doc']['tmp_name']))
            {
                $file_name 								= $this->imgfile_upload ($request_data['Subsidy']['cei_inspection_doc'],'cid_',$request_data['Subsidy']['application_id'],'cei_inspection_doc');
                $SubsidyEntity->cei_inspection_doc 		= $file_name;
            }
			if(isset($request_data['Subsidy']['cei_self_certification']['tmp_name']) && !empty($request_data['Subsidy']['cei_self_certification']['tmp_name']))
            {
                $file_name 								= $this->imgfile_upload ($request_data['Subsidy']['cei_self_certification'],'csc_',$request_data['Subsidy']['application_id'],'cei_self_certification');
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
                $file_name 											= $this->imgfile_upload ($request_data['Subsidy']['bidirectional_installation_sheet'],'bis_',$request_data['Subsidy']['application_id'],'bidirectional_installation_sheet');
                $SubsidyEntity->bidirectional_installation_sheet 	= $file_name;
            }
            if(isset($request_data['Subsidy']['bidirectional_meter_certification']['tmp_name']) && !empty($request_data['Subsidy']['bidirectional_meter_certification']['tmp_name']))
            {
                $file_name 											= $this->imgfile_upload ($request_data['Subsidy']['bidirectional_meter_certification'],'bmc_',$request_data['Subsidy']['application_id'],'bidirectional_meter_certification');
                $SubsidyEntity->bidirectional_meter_certification 	= $file_name;
            }
			if(isset($request_data['Subsidy']['meter_sealing_report']['tmp_name']) && !empty($request_data['Subsidy']['meter_sealing_report']['tmp_name']))
            {
                $file_name 											= $this->imgfile_upload ($request_data['Subsidy']['meter_sealing_report'],'msr_',$request_data['Subsidy']['application_id'],'meter_sealing_report');
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
                $file_name 								= $this->imgfile_upload ($request_data['Subsidy']['pv_module_serial'],'mod_ser_',$request_data['Subsidy']['application_id'],'pv_module_serial');
                $SubsidyEntity->pv_module_serial		= $file_name;
            }
			if(isset($request_data['Subsidy']['pv_module_certificate']['tmp_name']) && !empty($request_data['Subsidy']['pv_module_certificate']['tmp_name']))
            {
                $file_name 								= $this->imgfile_upload ($request_data['Subsidy']['pv_module_certificate'],'mod_cert_',$request_data['Subsidy']['application_id'],'pv_module_certificate');
                $SubsidyEntity->pv_module_certificate	= $file_name;
            }
			if(isset($request_data['Subsidy']['pv_module_sheet']['tmp_name']) && !empty($request_data['Subsidy']['pv_module_sheet']['tmp_name']))
            {
                $file_name 								= $this->imgfile_upload ($request_data['Subsidy']['pv_module_sheet'],'mod_sheet_',$request_data['Subsidy']['application_id'],'pv_module_sheet');
                $SubsidyEntity->pv_module_sheet			= $file_name;
            }
			if(isset($request_data['Subsidy']['inverter_serial']['tmp_name']) && !empty($request_data['Subsidy']['inverter_serial']['tmp_name']))
            {
                $file_name 								= $this->imgfile_upload ($request_data['Subsidy']['inverter_serial'],'in_ser_',$request_data['Subsidy']['application_id'],'inverter_serial');
                $SubsidyEntity->inverter_serial			= $file_name;
            }
			if(isset($request_data['Subsidy']['inverter_certificate']['tmp_name']) && !empty($request_data['Subsidy']['inverter_certificate']['tmp_name']))
            {
                $file_name 								= $this->imgfile_upload ($request_data['Subsidy']['inverter_certificate'],'in_cert_',$request_data['Subsidy']['application_id'],'inverter_certificate');
                $SubsidyEntity->inverter_certificate	= $file_name;
            }
			if(isset($request_data['Subsidy']['inverter_sheet']['tmp_name']) && !empty($request_data['Subsidy']['inverter_sheet']['tmp_name']))
            {
                $file_name 								= $this->imgfile_upload ($request_data['Subsidy']['inverter_sheet'],'in_sheet_',$request_data['Subsidy']['application_id'],'inverter_sheet');
                $SubsidyEntity->inverter_sheet			= $file_name;
            }
			if(isset($request_data['Subsidy']['pv_plant_site_photo']['tmp_name']) && !empty($request_data['Subsidy']['pv_plant_site_photo']['tmp_name']))
            {
                $file_name 								= $this->imgfile_upload ($request_data['Subsidy']['pv_plant_site_photo'],'pv_',$request_data['Subsidy']['application_id'],'pv_plant_site_photo');
                $SubsidyEntity->pv_plant_site_photo		= $file_name;
            }
            if(isset($request_data['Subsidy']['undertaking_consumer']['tmp_name']) && !empty($request_data['Subsidy']['undertaking_consumer']['tmp_name']))
            {
                $file_name 								= $this->imgfile_upload ($request_data['Subsidy']['undertaking_consumer'],'un_c_',$request_data['Subsidy']['application_id'],'undertaking_consumer');
                $SubsidyEntity->undertaking_consumer 	= $file_name;
            }
            if(isset($request_data['Subsidy']['geda_inspection_report']['tmp_name']) && !empty($request_data['Subsidy']['geda_inspection_report']['tmp_name']))
            {
                $file_name 								= $this->imgfile_upload ($request_data['Subsidy']['geda_inspection_report'],'g_i_r',$request_data['Subsidy']['application_id'],'geda_inspection_report');
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
			$arr_update	= array('modules_data'=> $SubsidyEntity->modules_data,
								'inverter_data'=> $SubsidyEntity->inverter_data
								);
			$this->Installation->updateAll($arr_update,array('project_id'=>$project_id));

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
            $min_cap = min($total_commulative,$total_commulative_i);
			$this->CreateMyProject($SubsidyEntity->application_id,true,$min_cap);

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
                $file_name 							= $this->imgfile_upload ($request_data['Subsidy']['signing_authority'],'s_a_',$request_data['Subsidy']['application_id'],'signing_authority');
                $SubsidyEntity->signing_authority	= $file_name;
            }
            if(isset($request_data['Subsidy']['charity_certificate']['tmp_name']) && !empty($request_data['Subsidy']['charity_certificate']['tmp_name']))
            {
                $file_name 							= $this->imgfile_upload ($request_data['Subsidy']['charity_certificate'],'c_c_',$request_data['Subsidy']['application_id'],'charity_certificate');
                $SubsidyEntity->charity_certificate = $file_name;
            }
			if(isset($request_data['Subsidy']['authority_letter']['tmp_name']) && !empty($request_data['Subsidy']['authority_letter']['tmp_name']))
            {
                $file_name 							= $this->imgfile_upload ($request_data['Subsidy']['authority_letter'],'a_l_',$request_data['Subsidy']['application_id'],'authority_letter');
                $SubsidyEntity->authority_letter 	= $file_name;
            }
            if(isset($request_data['Subsidy']['formb']['tmp_name']) && !empty($request_data['Subsidy']['formb']['tmp_name']))
            {
                $file_name 							= $this->imgfile_upload ($request_data['Subsidy']['formb'],'formb_',$request_data['Subsidy']['application_id'],'formb');
                $SubsidyEntity->formb 				= $file_name;
            }
            if(isset($request_data['Subsidy']['formc']['tmp_name']) && !empty($request_data['Subsidy']['formc']['tmp_name']))
            {
                $file_name 							= $this->imgfile_upload ($request_data['Subsidy']['formc'],'formc_',$request_data['Subsidy']['application_id'],'formc');
                $SubsidyEntity->formc 				= $file_name;
            }
            if(isset($request_data['Subsidy']['affidavit']['tmp_name']) && !empty($request_data['Subsidy']['affidavit']['tmp_name']))
            {
                $file_name 							= $this->imgfile_upload ($request_data['Subsidy']['affidavit'],'aff_',$request_data['Subsidy']['application_id'],'affidavit');
                $SubsidyEntity->affidavit 			= $file_name;
            }
            if(isset($request_data['Subsidy']['agreement_stamp']['tmp_name']) && !empty($request_data['Subsidy']['agreement_stamp']['tmp_name']))
            {
                $file_name 							= $this->imgfile_upload ($request_data['Subsidy']['agreement_stamp'],'a_s_',$request_data['Subsidy']['application_id'],'agreement_stamp');
                $SubsidyEntity->agreement_stamp 	= $file_name;
            }
            if(isset($request_data['Subsidy']['social_excel']['tmp_name']) && !empty($request_data['Subsidy']['social_excel']['tmp_name']))
            {
                $file_name 							= $this->imgfile_upload ($request_data['Subsidy']['social_excel'],'excel_',$request_data['Subsidy']['application_id'],'social_excel');
                $SubsidyEntity->social_excel 		= $file_name;
            }
            if(isset($request_data['Subsidy']['social_pdf']['tmp_name']) && !empty($request_data['Subsidy']['social_pdf']['tmp_name']))
            {
                $file_name 							= $this->imgfile_upload ($request_data['Subsidy']['social_pdf'],'pdf_',$request_data['Subsidy']['application_id'],'social_pdf');
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
    public function imgfile_upload($file,$prefix_file='',$application_id,$file_field)
    {
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
		$arrCondition['ApplyOnlines.pcr_submited IS NOT '] 		= NULL;
		
        $this->SortBy		= "SubsidyRequest.request_date";
        $this->Direction	= "ASC";
        $this->intLimit		= PAGE_RECORD_LIMIT;
        $this->CurrentPage  = 1;
        $option 			= array();
        $option['colName']  = array('id','geda_application_no','request_no','request_date','received_date');
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
        $option['order_by'] 			= "order : [[0,'ASC']]";
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
	                }
	                else if($key=='processed') {
	                	if (is_null($val->SubsidyRequestApplication['processed'])) {
	                		$temparr[$key] = "NOT SUBMITTED";
	                	} else if ($val->SubsidyRequestApplication['processed'] == 1) {
	                		$temparr[$key] = "REQUEST PROCESSED";
	                	} else {
	                		$temparr[$key] = "REQUEST PENDING";
	                	}
	                }
	                else if($key=='received_at') {
	                	if ($val->SubsidyRequestApplication['received_at'] == 1) 
	                	{
	                		$temparr[$key] = "REQUEST RECEVIED AT GEDA";
	                	} else {
	                		$temparr[$key] = "REQUEST NOT SUBMITTED AT GEDA";
	                	}
	                }
	                else if($key=='request_date') {
	                	if(!is_null($val->SubsidyRequestApplication['request_date']) && !empty($val->SubsidyRequestApplication['request_date']) && trim($val->SubsidyRequestApplication['request_date']) != '0000-00-00 00:00:00')
	                	{
	                		$temparr[$key]	= date('m-d-Y H:i a',strtotime($val->SubsidyRequestApplication['request_date']));
	                	}
	                	else
	                	{
	                		$temparr[$key] 	= '-';
	                	}
	                }
	                else if($key=='received_date') {
	                	if(!is_null($val->SubsidyRequestApplication['received_date']) && !empty($val->SubsidyRequestApplication['received_date'])&& trim($val->SubsidyRequestApplication['received_date']) != '0000-00-00 00:00:00')
	                	{
	                		$temparr[$key]	= date('m-d-Y H:i a',strtotime($val->SubsidyRequestApplication['received_date']));
	                	}
	                	else
	                	{
	                		$temparr[$key]	= '-';
	                	}
	                }
	                else if($key=='request_no') {
	                	if(!is_null($val->SubsidyRequest['request_no']) && !empty($val->SubsidyRequest['request_no']))
	                	{
	                		$temparr[$key]	= $val->SubsidyRequest['request_no'];
	                	}
	                	else
	                	{
	                		$temparr[$key]	= '-';
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

        $REQUEST_STATUS 	= array("0"=>"Process Pendiing","1"=>"Processed","99"=>"Not Submitted");
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
		$arrRequestList		= array();
        $arrCondition		= array();
        
        $this->SortBy		= "SubsidyRequestApplication.request_date";
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
									);
        $option 			= array();
        $option['colName']  = array('id','request_no','geda_application_no','request_date','received_date');
        $sortArr 			= array('id'=>'SubsidyRequestApplication.id',
        							'request_no'=>'SubsidyRequest.request_no',
        							'geda_application_no'=>'ApplyOnlines.geda_application_no',
        							'request_date'=>'SubsidyRequestApplication.request_date',
        							'processed'=>'SubsidyRequestApplication.processed',
        							'received_at'=>'SubsidyRequestApplication.received_at',
        							'received_date'=>'SubsidyRequestApplication.received_date');
        $this->SetSortingVars('SubsidyRequestApplication',$option,$sortArr);

        $option['dt_selector']			='table-example';
        $option['formId']				='formmain';
        $option['url']					= '';
        $option['recordsperpage']		= PAGE_RECORD_LIMIT;
        $option['allsortable']			= '-all';
        $option['bPaginate']			= 'true';
        $option['bLengthChange']		= 'false';
        $option['total_records_data']	= 0;
        $option['order_by'] 			= "order : [[2,'ASC']]";
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
	        $CountFields		= array('SubsidyRequestApplication.id');
	        $Fields 			= array('SubsidyRequestApplication.id',
	        							'SubsidyRequestApplication.application_id',
	        							'SubsidyRequest.request_no',
	        							'ApplyOnlines.geda_application_no',
	        							'SubsidyRequestApplication.request_date',
	        							'SubsidyRequestApplication.processed',
	        							'SubsidyRequestApplication.received_at',
	        							'SubsidyRequestApplication.received_date');
	        $query_data 		= $this->SubsidyRequestApplication->find('all',array(	'fields'		=> $Fields,
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
	                }
	                else if($key=='processed') {
	                	if (is_null($val->processed)) {
	                		$temparr[$key] 	= "NOT SUBMITTED";
	                	} else if ($val->processed == 1) {
	                		$temparr[$key] 	= "REQUEST PROCESSED";
	                	} else {
	                		$temparr[$key] 	= "REQUEST PENDING";
	                	}
	                }
	                else if($key=='received_at') {
	                	if ($val->received_at == 1) 
	                	{
	                		$temparr[$key] 	= "REQUEST RECEVIED AT GEDA";
	                	} else {
	                		$temparr[$key] 	= "REQUEST NOT SUBMITTED AT GEDA";
	                	}
	                }
	                else if($key=='request_date') {
	                	if(!is_null($val->request_date) && !empty($val->request_date) && trim($val->request_date) != '0000-00-00 00:00:00')
	                	{
	                		$temparr[$key]	= date('m-d-Y H:i a',strtotime($val->request_date));
	                	}
	                	else
	                	{
	                		$temparr[$key] 	= '-';
	                	}
	                }
	                else if($key=='received_date') {
	                	if(!is_null($val->received_date) && !empty($val->received_date) && trim($val->received_date) != '0000-00-00 00:00:00')
	                	{
	                		$temparr[$key]	= date('m-d-Y H:i a',strtotime($val->received_date));
	                	}
	                	else
	                	{
	                		$temparr[$key]	= '-';
	                	}
	                }
	                else if($key=='request_no') {
	                	if(!is_null($val->SubsidyRequest['request_no']) && !empty($val->SubsidyRequest['request_no']))
	                	{
	                		$temparr[$key]	= $val->SubsidyRequest['request_no'];
	                	}
	                	else
	                	{
	                		$temparr[$key]	= '-';
	                	}
	                }
	                else if($key=='geda_application_no') {
	                	if(!is_null($val->ApplyOnlines['geda_application_no']) && !empty($val->ApplyOnlines['geda_application_no']))
	                	{
	                		$temparr[$key]	= $val->ApplyOnlines['geda_application_no'];
	                	}
	                	else
	                	{
	                		$temparr[$key]	= '-';
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
					                "data"            	=> $out));
            die;
        }

        $REQUEST_STATUS 	= array("0"=>"Process Pendiing","1"=>"Processed");
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
						$SubsidyRequestApplication 								= $this->SubsidyRequestApplication->newEntity();
						$SubsidyRequestApplication->request_id 					= $SubsidyRequest->id;
						$SubsidyRequestApplication->application_id 				= decode($ApplicationID);
						$SubsidyRequestApplication->processed 					= 0;
						$SubsidyRequestApplication->processed_completion_date 	= '';
						$SubsidyRequestApplication->request_date 				= $this->NOW();
						$this->SubsidyRequestApplication->save($SubsidyRequestApplication);
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
     * createPassword
     *
     * Behaviour : public
     *
     * @param :
     * @defination : Method is use to createPassword.
     *
     */
	public function createPassword()
	{
		$this->autoRender 	= false;

		// echo passencrypt('592004164148').'<br/>';
		// echo passdecrypt('113*130*143*100*87*97*91*93').'<br>';
		// echo passdecrypt('95*100*111*109*116*104*91*94*97*100*103').'<br>';
		// echo passdecrypt('96*146*139*155*135*95*98*98*103*98').'<br>';
		//echo passdecrypt('95*129*155*153*153*144*106*93*96*99').'<br>';
		//echo passencrypt('geolocation@2024').'<br/>';
		// echo decode('ec4d673ef91275b1b9144a41e5a13f06156b').'<br/>';
		// echo passencrypt('991372947607').'<br/>';
		// echo passencrypt('602211').'<br/>';
		// echo passencrypt('606669843886').'<br/>';
		// echo passencrypt('461765628319').'<br/>';
		// echo passencrypt('243184710608').'<br/>';
		// echo passencrypt('396969462490').'<br/>';
		// echo passencrypt('594796396764').'<br/>';
		// echo passencrypt('565565267726').'<br/>';
		// echo passencrypt('410911072096').'<br/>';
		// echo passencrypt('663536628287').'<br/>';
		// echo passencrypt('691843579803').'<br/>';
		// $activation_code	= rand(1000, 9999);
		// $activation_code 	= 'demo@123';
		//$activation_code 	= 'Axaykumar@2024';
		$activation_code 	= 'Kalpesh@2024'; //Raval@2024  sdo.amrelitown2@gebmail.com
		
		$password 			= Security::hash(Configure::read('Security.salt') . $activation_code);
		echo '<br>'.$password." --> ".$activation_code;

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
		$member_id 			= $this->Session->read('Members.id');
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
        	
        	if($status==$this->ApplyOnlineApprovals->CLAIM_SUBSIDY)
        	{
        		$sms_text 			= str_replace('##geda_application_no##',$applyOnlinesData->geda_application_no,CLAIM_SUBSIDY);
				$subject 			= "[REG: GEDA Application No. ".$applyOnlinesData->geda_application_no."] Subsidy Claimed";
				$CUSTOMER_NAME 		= trim($applyOnlinesData->customer_name_prefixed." ".$applyOnlinesData->name_of_consumer_applicant." ".$applyOnlinesData->last_name." ".$applyOnlinesData->third_name);
				$EmailVars 			= array("CUSTOMER_NAME"=>$CUSTOMER_NAME,'geda_no'=>$applyOnlinesData->geda_application_no);
				$template_applied 	= 'claimed_subsidy';	
        	}	
        	
        	if($sms_text!='')
        	{
        		if(!empty($applyOnlinesData->consumer_mobile))
				{
					$this->ApplyOnlines->sendSMS($id,$applyOnlinesData->consumer_mobile,$sms_text);
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
							->from(array('do-not-reply@ahasolar.in' => PRODUCT_NAME))
						    ->to($applyOnlinesData->installer_email)
						    ->subject(Configure::read('EMAIL_ENV').$subject)
						    ->send();
			    }
			    $to 				= empty($applyOnlinesData->consumer_email) ? $applyOnlinesData->email : $applyOnlinesData->consumer_email;
			    if(!empty($to))
			    {
			    	$email 			= new Email('default');
					$email->profile('default');
					$email->viewVars($EmailVars);
					$message_send 	= $email->template($template_applied, 'default')
							->emailFormat('html')
							->from(array('do-not-reply@ahasolar.in' => PRODUCT_NAME))
						    ->to($to)
						    ->subject(Configure::read('EMAIL_ENV').$subject)
						    ->send();
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
     * webservices
     *
     * Behaviour : public
     *
     * @param :
     * @defination : Method is use to check webservices.
     *
     */
	public function webservices()
	{
		$this->autoRender = false;
		//echo passencrypt(720303776357);
		/*$this->ChargingCertificate->fetchApiMeterInstallation(68691);
		exit;*/
		$this->SpinWebserviceApi->AddPcrFiles(10239);  
		exit;
		//$this->SpinWebserviceApi->AddPcrFiles(30504);
		//$this->SpinWebserviceApi->AddPcrFiles(30521);
		//$this->SpinWebserviceApi->AddPcrFiles(30926);
		//$this->SpinWebserviceApi->AddPcrFiles(42009);
		//$this->SpinWebserviceApi->AddPcrFiles(9262);
		$this->SpinWebserviceApi->AddPcrFiles(3535);
		//$this->SpinWebserviceApi->AddPcrFiles(10411);
		//$this->SpinWebserviceApi->AddPcrFiles(42009);
		//$this->SpinWebserviceApi->AddPcrFiles(9330);
		//$this->SpinWebserviceApi->AddPcrFiles(20095);
		//$this->SpinWebserviceApi->AddPcrFiles(1970);
		//$this->SpinWebserviceApi->AddPcrFiles(37891);
		//$this->SpinWebserviceApi->AddPcrFiles(38996);
		//$this->SpinWebserviceApi->AddPcrFiles(39198);
		//$this->SpinWebserviceApi->AddPcrFiles(34970);
		//$this->SpinWebserviceApi->AddPcrFiles(39459);
		/*$this->SpinWebserviceApi->AddPcrFiles(36291);
		$this->SpinWebserviceApi->AddPcrFiles(3515);
		$this->SpinWebserviceApi->AddPcrFiles(26286);
		$this->SpinWebserviceApi->AddPcrFiles(32539);
		$this->SpinWebserviceApi->AddPcrFiles(32845);*/
		//$this->SpinWebserviceApi->discom_catgApi();
		//$this->SpinWebserviceApi->pcr_submit(10263);
		//$this->SpinWebserviceApi->AddPcrFiles(30404);
		//$this->SpinWebserviceApi->AddPcrFiles(40442);
		//$this->SpinWebserviceApi->AddPcrFiles(34816);
		//$this->SpinWebserviceApi->AddPcrFiles(33892);
		//$this->SpinWebserviceApi->AddPcrFiles(26620);
		//$this->SpinWebserviceApi->AddPcrFiles(2069);
		//$this->SpinWebserviceApi->pcr_submit(25073);  
		//echo passencrypt('252892699935');
		//$this->SpinWebserviceApi->AddPcrFiles(6185);
		//$this->SpinWebserviceApi->AddPcrFiles(6185);
		//$this->SpinWebserviceApi->AddPcrFiles(20752);
		//$this->SpinWebserviceApi->AddPcrFiles(21391);
		//$this->SpinWebserviceApi->AddPcrFiles(35022);
		//$this->SpinWebserviceApi->AddPcrFiles(21800);
		//$this->SpinWebserviceApi->AddPcrFiles(25692);
		//$this->SpinWebserviceApi->AddPcrFiles(40442);
		//$this->SpinWebserviceApi->AddPcrFiles(28920);
		//$this->ChargingCertificate->fetchApiMeterInstallation(18849);
		//$this->SpinWebserviceApi->AddPcrFiles(15798);
		//$this->SpinWebserviceApi->pcr_submit(5097);  
		//$this->SpinWebserviceApi->AddPcrFiles(4060);
		//$this->SpinWebserviceApi->AddPcrFiles(14501);
		//$this->SpinWebserviceApi->AddPcrFiles(35789);
		//$this->SpinWebserviceApi->AddPcrFiles(14280);
		//$this->SpinWebserviceApi->AddPcrFiles(12961);
		//$this->SpinWebserviceApi->pcr_submit(9);  
		//$this->SpinWebserviceApi->AddPcrFiles(7558);
		//$this->SpinWebserviceApi->AddPcrFiles(13638);
		//$this->SpinWebserviceApi->AddPcrFiles(189);
		//$this->SpinWebserviceApi->AddPcrFiles(3009);
		//$this->SpinWebserviceApi->AddPcrFiles(2613);
		//$this->SpinWebserviceApi->AddPcrFiles(233);
		//$this->SpinWebserviceApi->AddPcrFiles(6747);
		//$this->SpinWebserviceApi->AddPcrFiles(16232);
		//$this->SpinWebserviceApi->pcr_submit(3054);  
		//$this->SpinWebserviceApi->pcr_submit(107);  
		//echo passencrypt(229384516968);
		//echo passdecrypt('80*82*91*87*94*92*95*98*103*102*106*84');
       // exit;
		exit;
		//echo $_SERVER['HTTP_RANGE'];
		//exit;
		$ApplyOnlines       = $this->ApplyOnlines->find();
        $application_status = array($this->ApplyOnlineApprovals->CLAIM_SUBSIDY);
        $application_id 	= array(9,47,318,477,521,552,642,1481,6821,8878,8988,9522,9592,9600,9620);
        $application_id 	= array(4991);
        $application_id 	= array(5960);
        $application_id 	= array(5957);
        $application_id 	= array(153);
        $application_id 	= array(17898);
        $application_id 	= array(19303);
        $application_id 	= array(21142);
        $application_id 	= array(20504,15402,13219,19881,13245);
        $application_id 	= array(5474);
        $application_id 	= array(16843,24435);
        $application_id 	= array(477,1690,22246);
        $application_id 	= array(11278);
        $application_id 	= array(306);
        $application_id 	= array(477);



echo "\r\n--StartTime::".date("Y-m-d H:i:s")."--\r\n";
        $query_sent                 = 1;
        $GUJARAT_STATE              = 4;
        $CurrentHour                = date("H");
        $LastProcessedApplicationID = 0;
        
        $LIMIT_QUERY_SENT           = 10; 
        
        $ApplyOnlines       = $this->ApplyOnlines->find();
        
        $application_status = array($this->ApplyOnlineApprovals->CLAIM_SUBSIDY);
        $LastRowID          = $this->CronApiProcess->GetLastRowID("check_pcr_report");
        $arrConditions      = [ 'application_status IN '=>$application_status,
                                'pcr_code IS NULL',
                                'subsidy_claim_request_applications.received_at'=>'1',
                                'apply_state'=>$GUJARAT_STATE];
        if (!empty($LastRowID)) 
        {
            array_push($arrConditions,array('ApplyOnlines.id > '=>$LastRowID));
        }
        $arrApplications    = $this->ApplyOnlines->find('all',
                                                            [   'fields'        => ["ApplyOnlines.id"],
                                                                'join'          => [
                                                                    [   'table'=>'subsidy_claim_request_applications',
                                                                        'type'=>'INNER',
                                                                        'conditions'=>'subsidy_claim_request_applications.application_id = ApplyOnlines.id'
                                                                    ]
                                                                ],
                                                                'conditions'    => $arrConditions,
                                                                'limit'         => $LIMIT_QUERY_SENT,
                                                                'order'         => 'ApplyOnlines.id ASC',
                                                            ]
                                                    );
        $FetchedRowCount    = $arrApplications->count();

        echo "Total FetchedRowCount ==> ".$FetchedRowCount."\r\n";

        if (!empty($FetchedRowCount))
        {
			foreach($arrApplications as $arrApplication)
			{
				pr($arrApplication);
                $LastProcessedApplicationID = $arrApplication->id;
                $TotalCapacityData          = $ApplyOnlines->select(['TotalCapacity' => $ApplyOnlines->func()->sum('ApplyOnlines.pv_capacity')])->where(['apply_state' => $GUJARAT_STATE,
                    'approval_id' => SPIN_APPROVAL_ID])->toArray();
                if(($TotalCapacityData[0]['TotalCapacity']+$arrApplication->pv_capacity)<SPIN_APPROVED_CAPACITY)
                {
                    $LastProcessedApplicationID = $arrApplication->id;
			       // $this->SpinWebserviceApi->pcr_submit($arrApplication->id);  
                    $this->CronApiProcess->saveAPILog($LastProcessedApplicationID,"check_pcr_report");
                }
                else
                {
                    echo "Total Capacity ".$TotalCapacityData[0]['TotalCapacity']." Crossed ".SPIN_APPROVED_CAPACITY." limit";
                } 
			}
		}
        else
        {
            $this->CronApiProcess->saveAPILog($LastProcessedApplicationID,"check_pcr_report");
        }
        $ApplyOnlines       = $this->ApplyOnlines->find();
        $application_status = array($this->ApplyOnlineApprovals->CLAIM_SUBSIDY);
            
        $arrConditions      = [ 'application_status IN '=>$application_status,
                                'pcr_code IS NOT NULL',
                                'pcr_submited IS NULL',
                                'apply_state'=>$GUJARAT_STATE];
        $arrApplications    = $this->ApplyOnlines->find('all',
                                                            [   'fields'        => ["id"],
                                                                'conditions'    => $arrConditions,
                                                                'order'         => 'ApplyOnlines.id ASC',
                                                            ]
                                                    );
        $FetchedRowCount    = $arrApplications->count();
        echo "Total FetchedRowCount For Files ==> ".$FetchedRowCount."\r\n";
        if (!empty($FetchedRowCount))
        {
            foreach($arrApplications as $arrApplication)
            {
                $LastProcessedApplicationID = $arrApplication->id;
                //$this->SpinWebserviceApi->AddPcrFiles($arrApplication->id);   
            }
        }
		echo "\r\n--EndTime::".date("Y-m-d H:i:s")."--\r\n";



       // $this->generateSubsidyCoverLetterPDF('93',date("d/M/Y"),true);
        exit;
        //$this->SpinWebserviceApi->pcr_submit(119);
        //exit;
        //echo passencrypt(711036725368);
       // exit;
          //$this->SpinWebserviceApi->AddPcrFiles(22259);      
         // $this->SpinWebserviceApi->AddPcrFiles(24669);      
        $arrConditions      = [ 'application_status IN '=>$application_status,
                                'pcr_code IS NOT NULL',
                                'pcr_submited IS NULL',
                                'apply_state'=>4,
                            	'id IN '=>$application_id];
        $arrApplications    = $this->ApplyOnlines->find('all',
                                                            [   'fields'        => ["id"],
                                                                'conditions'    => $arrConditions,
                                                                'order'         => 'ApplyOnlines.id ASC',
                                                            ]
                                                    );
        $FetchedRowCount    = $arrApplications->count();
       // $this->SpinWebserviceApi->pcr_submit('5762');   
        
        echo "Total FetchedRowCount For Files ==> ".$FetchedRowCount."\r\n";
        if (!empty($FetchedRowCount))
        {
            foreach($arrApplications as $arrApplication)
            {
            	pr($arrApplication);
                $LastProcessedApplicationID = $arrApplication->id;
                $this->SpinWebserviceApi->AddPcrFiles($arrApplication->id);   
            }
        }
		//$this->SpinWebserviceApi->pcr_submit(19);
		//$this->SpinWebserviceApi->AddPcrFiles(19);
		//$this->SpinWebserviceApi->AddPcrFiles(41);
		//$this->SpinWebserviceApi->AddPcrFiles(97);
		//$this->SpinWebserviceApi->discom_catgApi();
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
		echo $this->request->data('installer_name');
		$installer_id 		= '1275';
		$arrSubsidyPayment 	= $this->SubsidyRequestApplication->find('all',array(
											'fields' 	=> array('apply_onlines.pv_capacity',
																'apply_onlines.geda_application_no',
																'SubsidyRequestApplication.received_date',
																'SubsidyRequestApplication.application_id',
																'subsidy_claim_requests.request_no',
																'projects.estimated_cost',
																'projects.recommended_capacity',
																'projects.state',
																'projects.customer_type'),
											'conditions'=> array('SubsidyRequestApplication.received_at'=>'1','SubsidyRequestApplication.processed'=>'1','apply_onlines.installer_id'=>$installer_id),
											'join'		=> [['table'=>'apply_onlines','conditions'=>'SubsidyRequestApplication.application_id=apply_onlines.id','type'=>'left'],
															['table'=>'projects','conditions'=>'apply_onlines.project_id=projects.id','type'=>'left'],
															['table'=>'subsidy_claim_requests','conditions'=>'subsidy_claim_requests.id=SubsidyRequestApplication.request_id','type'=>'left']],
											'order'		=> array('SubsidyRequestApplication.received_date'=>'asc')))->toArray();
		$InstallerCategory 	= $this->InstallerCategoryMapping->find('all',array('conditions'=>array('installer_id'=>$installer_id)))->first();
		
		$installers_list 	= $this->ApplyOnlines->find("all",['fields'=>['ApplyOnlines.installer_id','installers.installer_name'],'join'=>['installers'=>['table'=>'installers','conditions'=>'ApplyOnlines.installer_id=installers.id','type'=>'left']]])->toArray();
		
		$arrlist 			= array();
		foreach($installers_list as $inslist)
		{
			$arrlist[$inslist->installer_id] = $inslist->installers['installer_name'];
		}
		

		$this->set("pagetitle",'Subsidy Payment Report');
		$this->set("arrSubsidyPayment",$arrSubsidyPayment);
		$this->set("Projects",$this->Projects);
		$this->set("installer_id",$installer_id);
		$this->set("Subsidy",$this->Subsidy);
		$this->set("Installers",$arrlist);
		$this->set("InstallerCategory",$InstallerCategory);
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
    	$app_details   	= $this->ApplyOnlines->find('all',array('conditions'=>array('id'=>$application_id)))->first();
		$project_data       = $this->Projects->find('all',array('conditions'=>array('id'=>$app_details->project_id)))->first();
        if(!empty($project_data))
        {
        	$pv_app_capacity    = $app_details->pv_capacity;
            if($set_capacity != '')
            {
                $pv_app_capacity= $set_capacity;
            }
            $arr_project_data['proj_name']              = $project_data->name;
            $arr_project_data['latitude']               = $project_data->latitude;
            $arr_project_data['longitude']              = $project_data->longitude;
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
    public function check_filesize()
    {
    	$this->autoRender = false;
    	/*$applyOnlines 	= $this->ApplyOnlines->find('all')->toArray();
    	$count 			= 0;
    	$application_id = array();
    	foreach($applyOnlines as $application)
    	{
    		$DOCUMENT_PATH  = WWW_ROOT.APPLYONLINE_PATH.$application->id."/";
    		if(!empty($application->attach_recent_bill))
    		{
                if (file_exists($DOCUMENT_PATH.$application->attach_recent_bill)) 
                {
                    $file_size = filesize($DOCUMENT_PATH.$application->attach_recent_bill)/1000;
                    $file_ext = explode(".",$application->attach_recent_bill);
                    if($file_size>1024)
                    {
                    	echo $application->id." ==> ".$file_size." ==>".$application->attach_recent_bill. " ------------->".$application->created.'<br>';
                    	$count++;
                    	$application_id[]=$application->id;
                    }
                    if(strtolower($file_ext[1])!='pdf')
                    {
                    	echo $application->id." ==> EXTTTTTTTTTTT".$file_size." ==>".$application->attach_recent_bill. " ------------->".$application->created.'<br>';
                    	$count++;
                    	$application_id[]=$application->id;
                    }

                }
           	}
    	}*/
    	$applyOnlines 	= $this->ApplyonlinDocs->find('all',array('conditions'=>array('doc_type'=>'profile')))->toArray();
    	$count 			= 0;
    	$application_id = array();
    	foreach($applyOnlines as $application)
    	{
    		$DOCUMENT_PATH  = WWW_ROOT.APPLYONLINE_PATH.$application->application_id."/";
    		if(!empty($application->file_name))
    		{
                if (file_exists($DOCUMENT_PATH.$application->file_name)) 
                {

                    $file_size = filesize($DOCUMENT_PATH.$application->file_name)/1000;
                    $file_ext = explode(".",$application->file_name);
                   	if($file_size>200)
                   	{
                    	echo $application->application_id." ==> ".$file_size." ==>".$application->file_name. " ------------->".$application->created.'<br>';
                    	$count++;
                    	$application_id[]=$application->id;
                    }
                    if(strtolower($file_ext[1])!='pdf')
                    {
                    	//echo $application->id." ==> EXTTTTTTTTTTT ".$file_size." ==>".$application->file_name. " ------------->".$application->created.'<br>';
                    	//$count++;
                    	//$application_id[]=$application->id;
                    }

                }
           	}
    	}
    	echo $count;
    	echo '<br>';
    	echo implode(",",$application_id);
    	$count=0;
    	/*foreach($applyOnlines as $application)
    	{
    		$DOCUMENT_PATH  = WWW_ROOT.APPLYONLINE_PATH.$application->id."/";
    		if(!empty($application->attach_recent_bill))
    		{
                if (file_exists($DOCUMENT_PATH.$application->attach_recent_bill)) 
                {
                	$file_ext = pathinfo($DOCUMENT_PATH.$application->attach_recent_bill);
                   
                    if(strtolower($file_ext)!='pdf')
                    {
                    	echo $application->id." ==> ".$application->attach_recent_bill.'<br>';
                    	$count++;
                    }
                }
           	}
    	}
    	echo $count;*/
    }
    public function updateRecentBill()
    {
    	$this->autoRender 	= false;
    	//$arr_application 	= array(13777,22246,9328,18134,916,5911,11014,216,14031,12850,197,11563,11156,8823,5762,10280,206,1753,1200,11815,212,225,3040,232,7771,1032,12872,13940,1206,666,8592,341,145,1634,300,25741,1582,861,303,17064,316,16203,3810,7752,2486,12825,319,1511,12833,4170,1690,1659,14375,641,1665,17898,6073,326,327,2110,2689,2863,112,2119,2792,2805,2097,1688,287,17888,28854,154,5581,2780,100,152,3447,6802,5368,256,1671,345,552,6848,1606,8604,2141,5328,7761,9982,7768,1267,5960,39030,7770,5757,318,22235,1481,8732,5120,9,344,6983,99,7102,6969,104,5125,88,914,15554,1584,5132,76,687,2012,20728,18138,13821,849,6035);
    	//$subsidy 			= $this->Subsidy->find('all',array('conditions'=>array('application_id in'=>$arr_application)))->toArray();
    	$subsidy 			= $this->Subsidy->find('all',array('conditions'=>array('tab2_submit'=>'1')))->toArray();
    	$count 				= 0;
    	$count_copy 		= 0;
    	$application_id 	= array();
    	$application_copy_id= array();
    	foreach($subsidy as $subsidy_details)
    	{
    		$application 			= $this->ApplyOnlines->find('all',array('conditions'=>array('id'=>$subsidy_details->application_id)))->first();
    		$DOCUMENT_PATH  = WWW_ROOT.APPLYONLINE_PATH.$application->id."/";
    		if(!empty($application->attach_recent_bill) && empty($subsidy_details->recent_bill))
    		{
                if (file_exists($DOCUMENT_PATH.$application->attach_recent_bill)) 
                {
                    $file_size = filesize($DOCUMENT_PATH.$application->attach_recent_bill)/1000;
                    $file_ext = explode(".",$application->attach_recent_bill);
                    if($file_size>1024)
                    {
                    	echo $application->id." ==> ".$file_size." ==>".$DOCUMENT_PATH.$application->attach_recent_bill. " ------------->".$application->created.'<br>';
                    	$count++;
                    	$application_id[]=$application->id;
                    }
                    elseif(strtolower($file_ext[1])!='pdf')
                    {
                    	echo $application->id." ==> EXTTTTTTTTTTT".$file_size." ==>".$DOCUMENT_PATH.$application->attach_recent_bill. " ------------->".$application->created.'<br>';
                    	$count++;
                    	$application_id[]=$application->id;
                    }
                    else
                    {
                    	echo $application->id." copy ".$application->attach_recent_bill;
                    	$application_copy_id[]=$application->id;
                    	$count_copy++;
                    	/*$DOCUMENT_PATH  	= WWW_ROOT.APPLYONLINE_PATH.$application->id."/";
						if(!empty($application->attach_recent_bill))
						{
				            if (file_exists($DOCUMENT_PATH.$application->attach_recent_bill)) 
				            {
				                $file_size 				= filesize($DOCUMENT_PATH.$application->attach_recent_bill)/1000;
				                $file_ext 				= explode(".",$application->attach_recent_bill);
				                $error_recent_bill 		= 0;
				                if($file_size>1024 || strtolower($file_ext[1])!='pdf')
				                {
				                	$error_recent_bill 	= 1;
				                }
				                if($error_recent_bill == 0 && empty($SubsidyExist->recent_bill))
						       	{
						       		$file_recent 				= $this->copyfile_upload($application->attach_recent_bill,$DOCUMENT_PATH,'recent_',$application->id);
						       		$this->Subsidy->updateAll(array('recent_bill'=>$file_recent),array('application_id'=>$application->id));
						       	}
				            }
				            
				       	}*/
                    }

                }
           	}
    	}
    	echo $count;
    	echo '<br>';
    	echo implode(",",$application_id);
    	echo '<br>';
    	echo $count_copy;
    	echo '<br>';
    	echo implode(",",$application_copy_id);
    }
    public function testZipsummary()
    {
    	$this->autoRender 	= false;
		echo "\r\n--StartTime::".date("Y-m-d H:i:s")."--\r\n";




$LastRowID              = $this->CronApiProcess->GetLastRowID("send_subsidyzip_cron");

        $Conditions 			= array('SubsidyRequest.processed'=>0,
        							'apply_onlines.pcr_submited IS NOT NULL',
        							'subsidy_claim_request_applications.received_at'=>'1');
        if (!empty($LastRowID)) {
        	$Conditions['SubsidyRequest.id > '] = $LastRowID;
        }
        $SubsidyRequests 		= $this->SubsidyRequest->find('all',
										array( 	'fields'=> ['SubsidyRequest.id','SubsidyRequest.request_date'],
												'join'=>[
	                                                        [   'table'=>'subsidy_claim_request_applications',
	                                                            'type'=>'left',
	                                                            'conditions'=>'subsidy_claim_request_applications.request_id = SubsidyRequest.id'
	                                                        ],
	                                                        [   'table'=>'apply_onlines',
	                                                            'type'=>'left',
	                                                            'conditions'=>'subsidy_claim_request_applications.application_id = apply_onlines.id'
	                                                        ]
	                                                    ],
												'conditions'=>$Conditions))->distinct(['SubsidyRequest.id'])->toArray();
		if (empty($SubsidyRequests))
		{
			$this->CronApiProcess->saveAPILog(0,"send_subsidyzip_cron");
			$Conditions 		= array('SubsidyRequest.processed'=>0,
	        							'apply_onlines.pcr_submited IS NOT NULL',
	        							'subsidy_claim_request_applications.received_at'=>'1');
			$SubsidyRequests 	= $this->SubsidyRequest->find('all',
										array( 	'fields'=> ['SubsidyRequest.id','SubsidyRequest.request_date'],
												'join'=>[
	                                                        [   'table'=>'subsidy_claim_request_applications',
	                                                            'type'=>'left',
	                                                            'conditions'=>'subsidy_claim_request_applications.request_id = SubsidyRequest.id'
	                                                        ],
	                                                        [   'table'=>'apply_onlines',
	                                                            'type'=>'left',
	                                                            'conditions'=>'subsidy_claim_request_applications.application_id = apply_onlines.id'
	                                                        ]
	                                                    ],
												'conditions'=>$Conditions))->distinct(['SubsidyRequest.id'])->toArray();
		}
		
		$DoProcess			= true;
		$SendEMail			= true;
		$processed 			= 1;
		if (!empty($SubsidyRequests))
		{
			foreach($SubsidyRequests as $SubsidyRequest)
			{
				$Conditions 		= array('processed'=>0,'request_id'=>$SubsidyRequest->id,'apply_onlines.pcr_submited IS NOT NULL','received_at'=>'1');
				//echo "\r\n--application conditions::".print_r($Conditions)."--\r\n";
				$Request_Date 		= $SubsidyRequest->request_date;
				$ListOfApplications	= $this->SubsidyRequestApplication->find('all',
										array( 	'join'=>[
	                                                        [   'table'=>'apply_onlines',
	                                                            'type'=>'left',
	                                                            'conditions'=>'SubsidyRequestApplication.application_id = apply_onlines.id'
	                                                        ]
	                                                    ],
												'conditions'=>$Conditions))->toArray();
				
				foreach($ListOfApplications as $ListOfApplication)
				{
					// $application_ids 	= array();
					// array_push($application_ids,$ListOfApplication->application_id);
					
					if ($DoProcess)
					{
						echo "\r\n--Application ID:: ".$ListOfApplication->application_id." -->".$SubsidyRequest->id."--<br>";
						$ApplyOnline 	= $this->ApplyOnlines->viewApplication($ListOfApplication->application_id);
						// $ArrDocuments 	= $this->Subsidy->GetApplicationDocuments($ListOfApplication->application_id);
						$documentArr 	= array();

						$SubsidySummarySheet 	= "";
						$SubsidySummarySheet 	= $this->SubsidyRequest->generateSubsidySummarySheet($ListOfApplication->application_id,true);
						echo "test";
						exit;

					}
				}
				
			}
		}
		echo "\r\n--EndTime::".date("Y-m-d H:i:s")."--\r\n";
		exit;







		$LastRowID              = $this->CronApiProcess->GetLastRowID("send_subsidyzip_cron");

        $Conditions 			= array('SubsidyRequest.processed'=>0,
        							'apply_onlines.pcr_submited IS NOT NULL',
        							'subsidy_claim_request_applications.received_at'=>'1');
        if (!empty($LastRowID)) {
        	$Conditions['SubsidyRequest.id > '] = $LastRowID;
        }
        pr($Conditions);
        $SubsidyRequests 		= $this->SubsidyRequest->find('all',
										array( 	'fields'=> ['SubsidyRequest.id','SubsidyRequest.request_date'],
												'join'=>[
	                                                        [   'table'=>'subsidy_claim_request_applications',
	                                                            'type'=>'left',
	                                                            'conditions'=>'subsidy_claim_request_applications.request_id = SubsidyRequest.id'
	                                                        ],
	                                                        [   'table'=>'apply_onlines',
	                                                            'type'=>'left',
	                                                            'conditions'=>'subsidy_claim_request_applications.application_id = apply_onlines.id'
	                                                        ]
	                                                    ],
												'conditions'=>$Conditions))->distinct(['SubsidyRequest.id'])->toArray();
        
		if (empty($SubsidyRequests))
		{
			echo "ifff";
			$this->CronApiProcess->saveAPILog(0,"send_subsidyzip_cron");
			$Conditions 		= array('SubsidyRequest.processed'=>0,
	        							'apply_onlines.pcr_submited IS NOT NULL',
	        							'subsidy_claim_request_applications.received_at'=>'1');
			$SubsidyRequests 	= $this->SubsidyRequest->find('all',
										array( 	'fields'=> ['SubsidyRequest.id','SubsidyRequest.request_date'],
												'join'=>[
	                                                        [   'table'=>'subsidy_claim_request_applications',
	                                                            'type'=>'left',
	                                                            'conditions'=>'subsidy_claim_request_applications.request_id = SubsidyRequest.id'
	                                                        ],
	                                                        [   'table'=>'apply_onlines',
	                                                            'type'=>'left',
	                                                            'conditions'=>'subsidy_claim_request_applications.application_id = apply_onlines.id'
	                                                        ]
	                                                    ],
												'conditions'=>$Conditions))->distinct(['SubsidyRequest.id'])->toArray();
		}
		$DoProcess			= true;
		$SendEMail			= true;
		$processed 			= 1;
		pr($SubsidyRequests);

		if (!empty($SubsidyRequests))
		{
			foreach($SubsidyRequests as $SubsidyRequest)
			{
				$Conditions 		= array('processed'=>0,'request_id'=>$SubsidyRequest->id,'apply_onlines.pcr_submited IS NOT NULL','received_at'=>'1');
				//echo "\r\n--application conditions::".print_r($Conditions)."--\r\n";
				$Request_Date 		= $SubsidyRequest->request_date;
				$ListOfApplications	= $this->SubsidyRequestApplication->find('all',
										array( 	'join'=>[
	                                                        [   'table'=>'apply_onlines',
	                                                            'type'=>'left',
	                                                            'conditions'=>'SubsidyRequestApplication.application_id = apply_onlines.id'
	                                                        ]
	                                                    ],
												'conditions'=>$Conditions))->toArray();
				foreach($ListOfApplications as $ListOfApplication)
				{
					echo $ListOfApplication->application_id.'<br/>';
					if ($DoProcess)
					{
						$ApplyOnline 	= $this->ApplyOnlines->viewApplication($ListOfApplication->application_id);
						pr($ApplyOnline);
					}

				}
			}
		}


		echo "test";
		exit;

        echo "\r\n--StartTime::".date("Y-m-d H:i:s")."--\r\n";
        $Conditions 		= array('processed'=>0,'apply_onlines.pcr_submited IS NOT NULL','received_at'=>'1');

		

        $ListRequestApp	= $this->SubsidyRequestApplication->find('list',
									array( 	'fields'=>array('request_id'),
											'join'=>[
                                                        [   'table'=>'apply_onlines',
                                                            'type'=>'left',
                                                            'conditions'=>'SubsidyRequestApplication.application_id = apply_onlines.id'
                                                        ]
                                                    ],
                                            'keyField'=>'id','valueField'=>'request_id',
											'conditions'=>$Conditions))->distinct(['request_id'])->toArray();
        
        $Conditions 		= array('processed'=>0,'received_at'=>'1','id in'=>$ListRequestApp);
		$SubsidyRequests 	= $this->SubsidyRequest->find('all',array('conditions' => $Conditions,'limit'=>20))->toArray();
		$DoProcess			= true;
		$SendEMail			= true;
		$processed 			= 1;
		foreach($SubsidyRequests as $SubsidyRequest)
		{
			$Conditions 		= array('processed'=>0,'request_id'=>$SubsidyRequest->id,'apply_onlines.pcr_submited IS NOT NULL','received_at'=>'1');
			pr($Conditions);
			$Request_Date 		= $SubsidyRequest->request_date;
			$ListOfApplications	= $this->SubsidyRequestApplication->find('all',
									array( 	'join'=>[
                                                        [   'table'=>'apply_onlines',
                                                            'type'=>'left',
                                                            'conditions'=>'SubsidyRequestApplication.application_id = apply_onlines.id'
                                                        ]
                                                    ],
											'conditions'=>$Conditions))->toArray();
			pr($ListOfApplications);
		
		}

exit;

        $Conditions 		= array('processed'=>0);
		$SubsidyRequests 	= $this->SubsidyRequest->find('all',array('conditions' => $Conditions,'limit'=>20))->toArray();
		$DoProcess			= true;
		$SendEMail			= true;
		$processed 			= 1;
		foreach($SubsidyRequests as $SubsidyRequest)
		{
			$Conditions 		= array('processed'=>0,'request_id'=>$SubsidyRequest->id,'apply_onlines.pcr_submited IS NOT NULL','received_at'=>'1');
			$Request_Date 		= $SubsidyRequest->request_date;
			$ListOfApplications	= $this->SubsidyRequestApplication->find('all',
									array( 	'join'=>[
                                                        [   'table'=>'apply_onlines',
                                                            'type'=>'left',
                                                            'conditions'=>'SubsidyRequestApplication.application_id = apply_onlines.id'
                                                        ]
                                                    ],
											'conditions'=>$Conditions))->toArray();
			
			foreach($ListOfApplications as $ListOfApplication)
			{
				// $application_ids 	= array();
				// array_push($application_ids,$ListOfApplication->application_id);
				echo "\r\n--Application ID:: ".$ListOfApplication->application_id."--\r\n";
				if ($DoProcess)
				{
					$ApplyOnline 	= $this->ApplyOnlines->viewApplication($ListOfApplication->application_id);
					$ArrDocuments 	= $this->Subsidy->GetApplicationDocuments($ListOfApplication->application_id);
					$documentArr 	= array();

					$SubsidySummarySheet 	= "";
					$SubsidySummarySheet 	= $this->SubsidyRequest->generateSubsidySummarySheet($ListOfApplication->application_id,true);
					
					echo "\r\n--SubsidySummarySheet:: ".$SubsidySummarySheet."--\r\n";

					foreach ($ArrDocuments as $document) {
						if (!empty($document) && basename($document) != "") array_push($documentArr, $document);
					}

					if (!empty($SubsidySummarySheet) && file_exists($SubsidySummarySheet)) {
						array_push($documentArr, $SubsidySummarySheet);
					}
					$ZipFileList 	= implode(" ",$documentArr);
					$ApplicationID 	= $ApplyOnline->id;
					$GEDA_APP_NO	= str_replace("/","_",$ApplyOnline->geda_application_no);
					$ZipFileName	= WWW_ROOT.APPLYONLINE_PATH.$ApplicationID."/".$GEDA_APP_NO.".zip";
					if (file_exists($ZipFileName)) {
						unlink($ZipFileName);
					}
					echo "\r\n--ZipFileName:: ".$ZipFileName."--\r\n";
					$ZipCommand		= "zip -j ".$ZipFileName." ".$ZipFileList;
					system($ZipCommand);

					if (file_exists($ZipFileName) && $SendEMail) 
					{
						$this->SubsidyRequestApplication->updateAll(['processed'=>$processed,'processed_completion_date'=>$this->NOW()],
																	['id' => $ListOfApplication->id]);
						$GenerateCoverLetter 	= $this->GenerateCoverLetter($SubsidyRequest->id);
						$GenerateCoverLetterPDF = "";
						if ($GenerateCoverLetter) {
							$GenerateCoverLetterPDF = $this->generateSubsidyCoverLetterPDF($SubsidyRequest->id,$Request_Date);
							echo "\r\n--GenerateCoverLetterPDF:: ".$GenerateCoverLetterPDF."--\r\n";
						}

						$Conditions 	= array('Installers.id'=>$ApplyOnline->installer_id);
	        			$fields 		= ['Installers.id','Installers.installer_name','Installers.email'];
						$arrInstaller	= $this->Installers->find('all',array('fields'=>$fields,'conditions'=>$Conditions))->first();
	        			if (!empty($arrInstaller))
	        			{
	        				if (isset($arrInstaller->email) && !empty($arrInstaller->email))
			                {
			                    $subject   	= "GEDA | USRTP: Subsidy Document/REG. No: ".$ApplyOnline->geda_application_no;
			                    $email     	= new Email('default');
			                    $EmailTo   	= trim($arrInstaller->email);
			                    echo "\r\n--EmailTo:: ".$EmailTo."--\r\n";
			                    if (Configure::read('SERVER_MODE') != "PROD") {
			                    	$EmailTo 	= "kalpak@yugtia.com";
			                    }
			                    echo "\r\n--EmailTo:: ".$EmailTo."--\r\n";
			                    if ($GenerateCoverLetter && !empty($GenerateCoverLetterPDF)) {
									$MESSAGE 	= "Dear ".$arrInstaller->installer_name.",<br /><br />With reference to the GEDA Registration no. <b>".$ApplyOnline->geda_application_no."</b>, the documents required for the submission at GEDA are attached herein. The Cover Letter is attached with this email which needs to be enclosed along with other documents while submitting the hard copy at GEDA office, Gandhinagar. Thank you.<br /><br />Regards,<br /><br />Support Team";
			                    } else {
			                    	$MESSAGE 	= "Dear ".$arrInstaller->installer_name.",<br /><br />With reference to the GEDA Registration no. <b>".$ApplyOnline->geda_application_no."</b>, the documents required for the submission at GEDA are attached herein. Thank you.<br /><br />Regards,<br /><br />Support Team";
			                    }
			                    $email->profile('default');
			                    $email->viewVars(array( 'MESSAGE_CONTENT' => $MESSAGE));
			                    $email->template('installer_notification_email', 'default')
			                            ->emailFormat('html')
			                            ->from(array('info.geda@ahasolar.in' => PRODUCT_NAME))
			                            ->to($EmailTo)
			                            ->bcc('pulkitdhingra@gmail.com')
			                            ->subject($subject);
			                    if ($GenerateCoverLetter && !empty($GenerateCoverLetterPDF)) {
			                    	$email->addAttachments($GenerateCoverLetterPDF);
			                    }
			                    $email->addAttachments($ZipFileName);
			                    $email->send();
			                    $Emaillog                  = $this->Emaillog->newEntity();
			                    $Emaillog->email           = $EmailTo;
			                    $Emaillog->send_date       = $this->NOW();
			                    $Emaillog->action          = $subject;
			                    $Emaillog->description     = json_encode(array( 'EMAIL_ADDRESS' => $EmailTo));
			                    $this->Emaillog->save($Emaillog);

			                    unlink($ZipFileName);
			                    if (!empty($GenerateCoverLetterPDF)) unlink($GenerateCoverLetterPDF);
			                    if (!empty($SubsidySummarySheet) && file_exists($SubsidySummarySheet)) unlink($SubsidySummarySheet);
			                }
	        			}
					}
				}
			}
			if ($DoProcess) {
				$this->SubsidyRequest->updateAll(['processed'=>$processed,'processed_completion_date'=>$this->NOW()],
												['id' => $SubsidyRequest->id]);
			}
		}
		echo "\r\n--EndTime::".date("Y-m-d H:i:s")."--\r\n";
    }
    /**
	 * GenerateCoverLetter
	 * Behaviour : public
	 * @param : $request_id
	 * @defination : Method is use to check for generate coverletter pdf
	 */
	private function GenerateCoverLetter($request_id)
	{
		$Conditions 		= array('processed'=>'0','request_id'=>$request_id);
		$ListOfApplications	= $this->SubsidyRequestApplication->find('all',array('conditions'=>$Conditions))->count();
		pr($ListOfApplications);
		if (empty($ListOfApplications)) {
			echo "ifffff";
			return true;
		} else {
			echo "elll";
			return false;
		}
	}
	/**
	 * generateSubsidyCoverLetterPDF
	 * Behaviour : public
	 * @param : $request_id
	 * @param : $SubsidyRequests
	 * @param : $isdownload
	 * @defination : Method is use to generate coverletter pdf
	 */
	public function generateSubsidyCoverLetterPDF($request_id=0,$Request_Date,$isdownload=false)
	{
		$Conditions 					= array('request_id'=>$request_id);
		$ListOfApplications				= $this->SubsidyRequestApplication->find('all',array('conditions'=>$Conditions))->toArray();
		$SubsidyRequestDetails 			= $this->SubsidyRequest->find('all',array('conditions' => array('id'=>$request_id)))->first();
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
		$REQUEST_GENERATION_DATE 	= date("d/M/Y",strtotime($Request_Date));

		$this->layout 	= false;
    	$this->set('REQUEST_GENERATION_DATE',$REQUEST_GENERATION_DATE);
		$this->set('COVER_LETTER_ADDRESS_DETAILS',SUBSIDY_COVER_LETTER_ADDRESS);
		$this->set('INSTALLER_NAME',$INSTALLER_NAME);
		$this->set('No_of_Projects',$No_of_Projects);
		$this->set('Total_Capacity',$Total_Capacity);
		$this->set('No_of_Residential',$No_of_Residential);
		$this->set('Total_Capacity_Residential',$Total_Capacity_Residential);
		$this->set('No_of_Social_Sector',$No_of_Social_Sector);
		$this->set('Total_Capacity_Social_Sector',$Total_Capacity_Social_Sector);
		$this->set('Total_MNRE_Amount',_FormatNumberV2($Total_MNRE_Amount));
		$this->set('Total_State_Amount',_FormatNumberV2($Total_State_Amount));
		$this->set('Total_Subsidy_Amount',_FormatNumberV2($Total_Subsidy_Amount));
		$this->set('GEDA_APPLICATION_NOS',$GEDA_APPLICATION_NOS);
		$this->set('Total_Geda_Inspection_Report',$Total_Geda_Inspection_Report);
		$this->set('REQUEST_NO',$SubsidyRequestDetails->request_no);

		/* Generate PDF for estimation of project */
		require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
		$dompdf = new Dompdf($options = array());
		$dompdf->set_base_path(WWW_ROOT.'/pdf/');
		$this->set('dompdf',$dompdf);
		$html = $this->render('/Element/subsidy_coverletter');
		$dompdf->loadHtml($html,'UTF-8');
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();
		// Output the generated PDF to Browser
		if($isdownload) {
			$dompdf->stream('subsidy_coverletter-'.$request_id);	
		} else {
			$output 	= $dompdf->output();
			$pdfPath 	= WWW_ROOT.'/tmp/subsidy_coverletter-'.$request_id.'.pdf';
			file_put_contents($pdfPath, $output);	
			return $pdfPath;
		}
	}
	public function convert_images()
	{
		$this->autoRender 	= false;
		$connection    		= ConnectionManager::get('default');
		$sql 				= "select * from applyonlin_docs where SUBSTRING_INDEX(file_name,'.',-1)='jpeg' and  doc_type = 'profile'";
		$application 	 	= $connection->execute($sql)->fetchAll('assoc');
		pr($application);
		$arr_appid 			= array();
		$count 				= 0;
		foreach($application as $app)
		{
			echo $this->image_load($app['application_id'],$app['file_name']).'<br>';
			exit;
			/*$cur_path 	= $app['file_name'];
			$arr_ext 	= explode(".",$cur_path);
			$new_path 	= $arr_ext[0].'.jpg';//$app['file_name'];
			$DOCUMENT_PATH  = APPLYONLINE_PATH.$app['application_id'].'/';
			$oldpath 	= $DOCUMENT_PATH.$cur_path;
			$newpath 	= $DOCUMENT_PATH.$new_path;
			echo $oldpath. " -- >".$newpath.'<br>';
			$exec = 'convert $oldpath $newpath';
			$arr_appid[] = $app['application_id'];
			$count++;*/
			//copy($oldpath,$newpath);
			//$this->ApplyonlinDocs->updateAll(array('file_name'=>$new_path),array('id'=>$app['id']));
		}
		echo '<br>'.implode(",",$arr_appid);
		echo '<br>'.$count;
		
		
		//copy($oldpath,$newpath);
		//exec($exec);
	}
	public  function image_load($application_id,$imageold)
	{
		$DOCUMENT_PATH   	= WWW_ROOT.APPLYONLINE_PATH.($application_id).'/';
         $IMAGE_PATH 	= $DOCUMENT_PATH.$imageold;
        if (file_exists($IMAGE_PATH)) 
        {
            $ext                = pathinfo($IMAGE_PATH, PATHINFO_EXTENSION);
            $converted_filename = $DOCUMENT_PATH.$application_id."_profile_new_photo.jpg";
            if (!file_exists($converted_filename))
            {
                if ($ext == "png" || $ext == "gif" || $ext == "jpeg")
                {
                	header("Content-type: image/jpeg");
                    //new file name once the picture is converted
                    if ($ext=="png") $new_pic = imagecreatefrompng($IMAGE_PATH);
                    if ($ext=="gif") $new_pic = imagecreatefromgif($IMAGE_PATH);
                    if ($ext=="jpeg") $new_pic = imagecreatefromjpeg($IMAGE_PATH);

                    // Create a new true color image with the same size
                    $w = imagesx($new_pic);
                    $h = imagesy($new_pic);
                    $white = imagecreatetruecolor($w, $h);

                    // Fill the new image with white background
                    $bg = imagecolorallocate($white, 255, 255, 255);
                    imagefill($white, 0, 0, $bg);

                    // Copy original transparent image onto the new image
                    imagecopy($white, $new_pic, 0, 0, 0, 0, $w, $h);

                    $new_pic = $white;
                    imagejpeg($new_pic, $converted_filename);
                    imagedestroy($new_pic);
                } else {
                    $converted_filename = $IMAGE_PATH;
                }
            }
            return $converted_filename;
        }
	}
	public function getApprovalList()
	{
		$this->autoRender = false;
		$this->SpinWebserviceApi->discom_catgApi();
	}
	public function testmimetype()
	{
		//echo $this->get_mime_type($_SERVER['DOCUMENT_ROOT'].'/img/doc_020180927120942913301045.jpg');
		echo $this->get_mime_type($_SERVER['DOCUMENT_ROOT'].'/PP Photo RT-RES-10040442.jpeg');

		exit;
	}
	public function get_mime_type($file) {
		$mtype = false;
		if (function_exists('finfo_open')) {
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mtype = finfo_file($finfo, $file);
		finfo_close($finfo);
		} elseif (function_exists('mime_content_type')) {
		$mtype = mime_content_type($file);
		} 
		return $mtype;
	}
	public function copywholeFolder()
	{
		$arrData = array(42180,42609,42866,42874,42879,42906,42932,42934,42947,42950,42957,42991,42992,42999,43016,43021,43030,43034,43036,43041,43052,43078,43094,43112,43132,43201,43205,43231,43238,43240,43245,43247,43248,43253,43254,43255,43260,43264,43267,43271,43273,43276,43280,43281,43282,43286,43287,43291,43295,43297,43307,43308,43313,43315,43316,43317,43320,43322,43323,43324,43329,43330,43332,43336,43338,43348,43353,43354,43355,43357,43359,43361,43364,43372,43375,43376,43380,43385,43386,43387,43388,43390,43391,43393,43394,43397,43401,43403,43404,43408,43411,43412,43415,43417,43419,43420,43421,43423,43424,43425,43428,43432,43438,43439,43443,43445,43446,43452,43453,43455,43457,43459,43460,43461,43464,43468,43473,43474,43484,43485,43490,43492,43493,43495,43498,43501,43505,43506,43508,43513,43514,43515,43518,43519,43530,43534,43537,43545,43547,43548,43549,43551,43553,43554,43561,43567,43569,43573,43574,43577,43578,43580,43581,43582,43584,43587,43590,43591,43594,43596,43599,43600,43603,43608,43609,43610,43613,43615,43616,43619,43623,43624,43627,43630,43631,43633,43635,43636,43638,43640,43641,43642,43644,43645,43646,43647,43648,43650,43651,43652,43653,43654,43655,43656,43657,43658,43660,43662,43663,43664,43667,43669,43671,43673,43674,43675,43676,43678,43679,43680,43681,43683,43685,43687,43689,43690,43691,43692,43693,43694,43696,43698,43700,43701,43702,43706,43707,43708,43709,43710,43711,43713,43715,43717,43719,43722,43724,43726,43727,43728,43732,43733,43734,43735,43737,43738,43739,43741,43742,43743,43744,43745,43747,43750,43752,43754,43755,43756,43757,43758,43759,43760,43761,43762,43763,43764,43765,43766,43768,43769,43770,43772,43774,43776,43779,43781,43782,43783,43784,43785,43786,43787,43789,43790,43791,43792,43793,43794,43799,43800,43801,43803,43804,43806,43808,43810,43813,43815,43816,43818,43820,43821,43825,43826,43827,43829,43830,43832,43833,43835,43837,43838,43840,43841,43842,43843,43844,43845,43846,43847,43848,43850,43852,43854,43856,43858,43860,43863,43866,43867,43868,43870,43871,43873,43875,43876,43877,43881,43883,43884,43885,43887,43888,43890,43892,43893,43894,43895,43896,43897,43900,43901,43902,43903,43904,43905,43906,43907,43908,43909,43910,43911,43912,43914,43916,43917,43919,43920,43923,43925,43926,43927,43928,43930,43931,43932,43933,43934,43936,43937,43939,43941,43942,43945,43947,43948,43949,43950,43951,43952,43953,43955,43956,43957,43958,43959,43960,43961,43962,43963,43964,43965,43967,43968,43970,43974,43975,43977,43979,43981,43982,43984,43985,43986,43988,43990,43991,43992,43994,43995,43997,43998,43999,44000,44002,44004,44007,44008,44009,44010,44011,44012,44014,44015,44016,44019,44021,44023,44024,44027,44028,44029,44030,44032,44031,44034,44035,44037,44039,44041,44042,44044,44045,44046,44047,44048,44049,44051,44052,44054,44055,44056,44060,44061,44062,44064,44065,44068,44069,44070,44072,44073,44074,44078,44079,44080,44081,44083,44084,44085,44086,44087,44088,44089,44090,44092,44093,44094,44096,44102,44103,44104,44107,44109,44110,44111,44114,44115,44116,44118,44119,44120,44121,44122,44123,44124,44127,44128,44129,44130,44131,44133,44136,44138,44140,44142,44143,44144,44146,44147,44150,44152,44155,44156,44158,44159,44161,44162,44163,44164,44166,44168,44170,44171,44173,44175,44178,44179,44182,44183,44185,44186,44187,44188,44190,44191,44192,44193,44194,44195,44197,44198,44199,44200,44202,44207,44208,44209,44212,44213,44214,44215,44217,44219,44223,44224,44226,44228,44229,44230,44232,44234,44237,44238,44239,44240,44242,44243,44245,44251,44252,44272,44276,44277,44279,44282,44289,44296,44314,44317,44330,44332,44334,44342,44346,44347,44348,44350,44351,44353,44359,44360,44363,44368,44369,44372,44380,44382,44396,44397,44399,44400,44402,44404,44406,44407,44409,44413,44417,44418,44426,44433,44434,44435,44436,44437,44440,44441,44442,44446,44447,44449,44451,44453,44454,44456,44457,44459,44460,44462,44463,44464,44465,44467,44471,44475,44476,44477,44479,44481,44485,44486,44487,44489,44490,44492,44493,44495,44496,44501,44502,44503,44504,44505,44507,44508,44509,44510,44512,44514,44515,44517,44518,44521,44527,44529,44532,44533,44534,44535,44537,44540,44542,44543,44544,44545,44548,44549,44550,44551,44554,44555,44557,44558,44560,44561,44563,44564,44565,44566,44567,44569,44571,44572,44575,44576,44579,44580,44583,44584,44585,44590,44591,44592,44593,44596,44597,44598,44599,44600,44601,44604,44606,44607,44609,44610,44612,44614,44615,44618,44622,44623,44624,44625,44629,44630,44631,44632,44638);
		$deleteCounter = 0;
		foreach($arrData as $application_id)
		{
			$src = WWW_ROOT.APPLYONLINE_PATH.$application_id;
			$dest = WWW_ROOT."/img/backupSocialSector/".$application_id;
			//$src = "/home/www/domain-name.com/source/folders/123456";
			//$dest = "/home/www/domain-name.com/test/123456";

			//shell_exec("cp -r $src $dest");

			//echo "<H3>Copy Paste completed!</H3>";
			echo "src  ==>".$src.'<br>';
			$files = glob($src . '/*');
				foreach ($files as $file) {
					unlink($file);
				}
			if(rmdir($src))
			{
				$deleteCounter++;
			}
		}
		echo "Foleder ==>".$deleteCounter." Deleted";
		exit;

		//$this->ChargingCertificate->fetchApiMeterInstallation(6500);
		//$this->ChargingCertificate->fetchApiMeterInstallation(6500);
		
	}
	public function convertpngtojpg()
	{
		//274,305,309,315,424,460,475,501,617,763,848,1080,1101,1133,1162,1172,1173,1174,1176,1177,1276,1403,1589,1621,1638,1650,1681,1718,1720,1723,1724,1726,1729,1731,1801,1823,1847,1870,2314,2322,2351,2367,2375,2376,2435,2466,2483,2512,2528,2584,2626,2686,2732,2788,2804,2839,2846,2993,3066,3088,3190,3305,3370,3408,3504,3556,3580,3700,3835,4025,4287,4638,4810,4822,4840,4899,4908,4960,5211,5675,5982,5987,6004,6007,6010,6012,6014,6046,6083,6084,6103,6112,6308,6315,6457,7137,7156,7182,7319,7921,8099,8230,8488,8515,8816,8850,9142,9782,9943,10072,10725,10740,10744,10750,10753,10760,10765,10778,10779,10784,10786,10792,10793,10805,10808,10814,10818,11361,12297,12324,12438,12946,12961,12977,13033,13055,13064,13091,13109,13255,13263,13284,13422,13426,13429,13698,13716,13728,13763,13774,13792,13850,13885,13908,13993,13997,13999,14004,14030,14095,14132,14136,14138,14213,14217,14220,14222,14223,14225,14247,14255,14260,14262,14266,14268,14362,14427,14567,14588,14590,14606,14618,14629,14734,14766,15091,15100,16105,16110,16113,16114,16116,16118,16122,
		//$arrData = '16128,16135,16187,16874,16885,16935,16978,16990,16994,16995,17379,17393,17451,17667,17691,17716,17768,17911,17941,17986,18257,18729,19494,19746,19754,19782,20095,20752,20767,21079,21105,22208,22231,22233,23263,24512,25780,26470,26632,27055,27232,27655,27676,27777,27958,28034,28114,28120,28122,28143,28150,28153,28158,28163,28172,28237,28243,28248,28261,29191,29606,29623,30404,30445,31210,32317,32336,32733,32810,33047,33070,33892,34440,34533,34774,34836,35215,35309,35340,38829,38886';
		$arrData = '527,1501,3471,8461,9763,9815,15321,17969,19780';//,27670,33682';
		$arrData = '30404';
		$sql 				= "select * from applyonlin_docs where application_id in($arrData) and  doc_type = 'profile'";
		$connection    		= ConnectionManager::get('default');
		$application 	 	= $connection->execute($sql)->fetchAll('assoc');
		//pr($application);
		$arr_appid 			= array();
		$count 				= 0;
		foreach($application as $app)
		{
			//echo $app['application_id'].", ".$app['file_name'].'<br>';
			//echo $this->image_load($app['application_id'],$app['file_name']).'<br>';
			$arrFileData = explode(".", $app['file_name']);
			$DOCUMENT_PATH  	= WWW_ROOT.APPLYONLINE_PATH.$app['application_id']."/".$arrFileData[0].'.jpg';
				$filename = $arrFileData[0].'.jpg';
            if (file_exists($DOCUMENT_PATH)) 
            {
            	$count++;
            	//$sql 				= "update applyonlin_docs set file_name='".$filename."' where id='".$app['id']."'";
            	//$connection->execute($sql);
            	$this->SpinWebserviceApi->AddPcrFiles($app['application_id']);
            	echo $app['application_id'].", ".$app['file_name'].'<br>';
            }
		}
		echo 'Count------'.$count;
		exit;
	}
	public function fetch_data($array_request,$return_count=0)
    {
    	$member_id 			= $this->Session->read("Members.id");
    	$customer_id 		= $this->Session->read("Customers.id");
		$state 				= '';
		$branch_id 			= '';
		$main_branch_id		= '';
		$member_type 		= '';
		
		$member_type 	= $this->Session->read('Members.member_type');
		$area 			= $this->Session->read('Members.area');
		$circle 		= $this->Session->read('Members.circle');
		$division 		= $this->Session->read('Members.division');
		$subdivision 	= $this->Session->read('Members.subdivision');
		$section 		= $this->Session->read('Members.section');

		if($this->Session->check("Members.state")){
			$state 		= $this->Session->read("Members.state");
		}
		if($this->Session->check("Customers.id")){
			//return $this->redirect(URL_HTTP.'/apply-online-list');
		}
		/*
		if($this->Session->check("Members.branch_id")) {
			$branch_id 		= $this->Session->read("Members.branch_id");
			if(!empty($branch_id)) {
				$main_branch_id = $this->BranchMasters->findMasterId($branch_id);
			}
		}
		*/

		if($this->Session->check("Members.member_type")){
			$member_type = $this->Session->read("Members.member_type");
		}

		$main_branch_id = array();
		if (!empty($member_id) && $member_type == $this->ApplyOnlines->DISCOM) 
		{
			$field      = "area";
			$id         = $area;
			
			if (!empty($section)) {
				$field      = "section";
				$id         = $section;
			} else if (!empty($subdivision)) {
				$field      = "subdivision";
				$id         = $subdivision;
			} else if (!empty($division)) {
				$field      = "division";
				$id         = $division;
			} else if (!empty($circle)) {
				$field      = "circle";
				$id         = $circle;
			}
			//$main_branch_id = array("field"=>$field,"id"=>$id);
		}
    	$connection         = ConnectionManager::get('default');
    	$sql_first 			= "SELECT AO.id,'125MW' AS 'Scheme',AO.created,
							(( CASE WHEN 1 = 1 THEN( select created FROM apply_online_approvals 
							    WHERE 
							      apply_online_approvals.application_id = AO.id 
							      AND apply_online_approvals.stage = '31' 
							    group by 
							      stage) END )) as submited_date,
				AO.geda_application_no, AO.application_no, AO.application_status, 
				INS.installer_name,IF (ICM.category_id = 1,'A','B') AS installer_category,ICM.allowed_bands,
				P.name,AO.pv_capacity,DM.title as 'DisCom Name',AO.consumer_no,
				D.title as division_title, SD.title as subdiv_title, AO.sanction_load_contract_demand,
				IF (AO.transmission_line = 3, '3 Phase',(IF(AO.transmission_line = 1,'Single Phase','-'))) as Invertor_Phase,
				PM.para_value as App_Category,
				IF (AO.net_meter = 1, 'DisCom',IF (AO.net_meter = 2,'Installer/ EA','-')) as Net_Meter_By,
				P.latitude, P.longitude,
				AO.consumer_email, AO.consumer_mobile,
				AO.installer_email, AO.installer_mobile,
				AO.customer_name_prefixed AS Name_Prefix,
				AO.name_of_consumer_applicant AS First_Name,
				AO.last_name AS Middle_Name,
				AO.third_name AS Last_Name,
				AO.landline_no AS landline_no,
				AO.address1 AS 'Street/ House No.',
				AO.address2 AS 'Taluka',
				AO.district AS 'District',
				AO.city AS 'City/Village',
				AO.state AS 'State',
				AO.pincode AS 'Pin',
				IF (AO.comunication_address_as_above = 1, 'Same',AO.comunication_address) as comunication_address,
				IF ((CASE WHEN 1=1 THEN (
				                SELECT COUNT(0) AS CNT
				                FROM applyonlin_docs
				                WHERE applyonlin_docs.application_id = AO.id
				                AND applyonlin_docs.doc_type = 'profile'
				) END) = 1, 'Yes', 'No') AS Profile_Photo,
				IF (AO.owned_rented = 1, 'Rented','Owned') as 'Premises',
				IF (AO.attach_recent_bill IS NULL OR AO.attach_recent_bill = '','No','Yes') as 'Electricity_Bill',
				IF (AO.aadhar_no_or_pan_card_no IS NULL OR AO.aadhar_no_or_pan_card_no = '','No','Yes') as 'Aadhaar No. Entered',
				IF (AO.capexmode = 1, 'Yes', 'No') AS 'Solar PV system Owned by the Consumer',
				IF (AO.capexmode = 1, 'Yes', 'No') AS 'Solar PV system Owned by the Consumer',
				IF (AO.disclaimer_subsidy = 1, 'Yes', 'No') AS 'Don\'t Want Subsidy',
				((CASE WHEN 1=1 THEN(
				                SELECT created
				                FROM apply_online_approvals
				                WHERE apply_online_approvals.application_id = AO.id
				                AND apply_online_approvals.stage = 1 group by apply_online_approvals.stage
				) END)) AS OTP_Verified_On,
				((CASE WHEN 1=1 THEN(
				                SELECT created
				                FROM apply_online_approvals
				                WHERE apply_online_approvals.application_id = AO.id
				                AND apply_online_approvals.stage = 23
				) END)) AS Document_Verified_Date,
				((CASE WHEN 1=1 THEN(
				                select CONCAT(SUBSTR(file_name,5,4),'-',SUBSTR(file_name,9,2),'-',SUBSTR(file_name,11,2),' ',
				                SUBSTR(file_name,13,2),':',SUBSTR(file_name,15,2),':',SUBSTR(file_name,17,2))
				                FROM applyonlin_docs
				                WHERE applyonlin_docs.application_id = AO.id
				                AND applyonlin_docs.doc_type = 'Signed_Doc'  group by doc_type
				) END)) AS 'Signed_Uploaded_Date',
				((CASE WHEN 1=1 THEN(
				                SELECT concat(applyonline_messages.message,'|',applyonline_messages.created)
				                FROM applyonline_messages
				                WHERE applyonline_messages.application_id = AO.id
				                AND user_type = '6002'
				                ORDER BY applyonline_messages.id DESC
				                LIMIT 1
				) END) ) AS 'Last_Comment',
				((CASE WHEN 1=1 THEN(
				                SELECT applyonline_messages.created
				                FROM applyonline_messages
				                WHERE applyonline_messages.application_id = AO.id
				                AND user_type = '0'
				                ORDER BY applyonline_messages.id DESC
				                LIMIT 1
				) END) ) AS 'Last_Comment_Replied_Date',
				((CASE WHEN 1=1 THEN(
				                SELECT concat(fesibility_report.created,'|',fesibility_report.quotation_number,'|',fesibility_report.estimated_amount,'|',fesibility_report.estimated_due_date,'|',IF (fesibility_report.payment_approve = 1,'Yes','No'),'|',fesibility_report.payment_date)
				                FROM fesibility_report
				                WHERE fesibility_report.application_id = AO.id
				                ORDER BY fesibility_report.id DESC LIMIT 1
				) END) ) AS 'fesibility_data',
				IF ((CASE WHEN 1=1 THEN (
				                SELECT COUNT(0) AS CNT
				                FROM applyonlin_docs
				                WHERE applyonlin_docs.application_id = AO.id
				                AND applyonlin_docs.doc_type = 'Self_Certificate' LIMIT 1
				) END) = 1, 'Yes', 'No') AS Self_Certificate,
				((CASE WHEN 1=1 THEN (
				                SELECT cei_application_details.drawing_app_no
				                FROM cei_application_details
				                WHERE cei_application_details.application_id = AO.id LIMIT 1
				) END)) AS 'drawing_app_no',
				((CASE WHEN 1=1 THEN (
				                SELECT created
				                FROM apply_online_approvals
				                WHERE apply_online_approvals.application_id = AO.id
				                AND apply_online_approvals.stage = 9 limit 1
				) END)) AS 'drawing_approved_date',
				((CASE WHEN 1=1 THEN (
				                SELECT concat(workorder_number,'|',workorder_date)
				                FROM project_workorder
				                WHERE project_workorder.project_id = AO.project_id LIMIT 1
				) END)) AS 'workorder_number_date',
				((CASE WHEN 1=1 THEN (
				                SELECT concat(start_date,'|',end_date,'|',meter_manufacture,'|',meter_serial_no,'|',solar_meter_manufacture,'|',solar_meter_serial_no)
				                FROM project_installation
				                WHERE project_installation.project_id = AO.project_id Limit 1
				) END)) AS 'installation_data',
				((CASE WHEN 1=1 THEN (
				                SELECT concat(meter_installed_date,'|',agreement_date)
				                FROM charging_certificate
				                WHERE charging_certificate.application_id = AO.id LIMIT 1
				) END)) AS 'charging_data',
				((CASE WHEN 1=1 THEN (
				                SELECT concat(payment_id,'|',transaction_id)
				                FROM payumoney
				                WHERE payumoney.application_id = AO.id and payment_status='success' LIMIT 1
				) END)) AS 'payment_payu',
				IF (AO.payment_status = 1, 'Yes', 'No') AS 'application_payment_status' ";
		$social_consumer = " AO.social_consumer ='1' and AO.disclaimer_subsidy !='1'";
		
		
		$sql_count = "select count(0)";
		$sql = "
			FROM apply_onlines AO
			INNER JOIN projects as P ON P.id = AO.project_id
			INNER JOIN installers as INS ON INS.id = AO.installer_id
			INNER JOIN installer_category_mapping as ICM ON ICM.installer_id = INS.id
			INNER JOIN parameters as PM ON AO.category = PM.para_id
			INNER JOIN branch_masters as DM ON AO.discom = DM.id
			LEFT JOIN discom_master as D ON AO.division = D.id
			LEFT JOIN discom_master as SD ON AO.subdivision = SD.id 
			where  $social_consumer
			";
		$from_date 			= isset($array_request['DateFrom'])?$array_request['DateFrom']:'';
		$end_date 			= isset($array_request['DateTo'])?$array_request['DateTo']:'';
		$application_status = isset($array_request['status'])?$array_request['status']:'';
		$installer_name 	= isset($array_request['installer_name'])?$array_request['installer_name']:'';
		$application_no 	= isset($array_request['application_no'])?$array_request['application_no']:'';
		$geda_application_no= isset($array_request['geda_application_no'])?$array_request['geda_application_no']:'';
		if(!empty($from_date) && !empty($end_date))
		{
			$fields_date  	= "(( CASE WHEN 1 = 1 THEN( select created FROM apply_online_approvals 
								    WHERE 
								      apply_online_approvals.application_id = AO.id 
								      AND apply_online_approvals.stage = '1' 
								    group by 
								      stage) END ))";

			$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
			$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
		    $sql 			.= " and $fields_date between '".$StartTime."' and '".$EndTime."'";
		}
		if(!empty($main_branch_id))
		{
			$sql .= " and AO.".$main_branch_id['field']." = '".$main_branch_id['id']."'";
		}
		if(!empty($application_status))
		{
			$passStatus = $this->ApplyOnlines->apply_online_status_key[$application_status];   
	        if($passStatus == '9999')
	        {
	            $sql .= " and AO.application_status = '".$this->ApplyOnlineApprovals->APPLICATION_SUBMITTED."'";
	        }
	        else
	        {
	            $FindApplicationIDs     = $this->ApplyOnlineApprovals->find('list',['keyField'=>'id','valueField'=>'application_id','conditions'=>['stage'=>$this->ApplyOnlines->apply_online_status_key[$application_status]]])->toArray();
	            if (!empty($FindApplicationIDs)) {
	                $sql .= " and AO.id IN (".implode(",",array_unique($FindApplicationIDs)).")";
	                if($passStatus != $this->ApplyOnlineApprovals->APPLICATION_CANCELLED)
	                {
	                    $sql .= " and AO.application_status != '".$this->ApplyOnlineApprovals->APPLICATION_CANCELLED."'";
	                }
	            } else {
	                $sql .= " and AO.id = '0'";
	            }
	        }
		}
		if(!empty($installer_name))
	   	{
	   		$sql .= " and INS.installer_name like '%".$installer_name."%'";
	   	}
	   	if(!empty($application_no))
	   	{
	   		$sql .= " and AO.application_no like '%".$application_no."%'";
	   	}
	   	if(!empty($geda_application_no))
	   	{
	   		$sql .= " and AO.geda_application_no like '%".$geda_application_no."%'";
	   	}
	   	if(!empty($customer_id))
	   	{
	   		$installerdata	= $this->Customers->find('all', array('conditions'=>array('id'=>$customer_id)))->first();
			$installer_id 	= (isset($installerdata['installer_id'])?$installerdata['installer_id']:0);
			//$sql .= " and AO.installer_id='".$installer_id."'";
	   	}
	   	$sql .= " order by  (select created FROM apply_online_approvals WHERE apply_online_approvals.application_id = AO.id AND apply_online_approvals.stage = '1' group by stage) desc ,AO.id desc ";
	   	
	   	if($return_count==0)
	   	{
	   		$applicationData_output 	= $connection->execute($sql_first.$sql)->fetchAll('assoc');
	   		return $applicationData_output;
	   	}
	   	else
	   	{
	   		$applicationData_count 		= $connection->execute($sql_count.$sql)->fetchAll('assoc');
	   		return $applicationData_count[0]['count(0)'];
	   	}
    }
	public function getreportfromexel()
	{
		$application_cnt = $this->fetch_data($this->request->data,1);
		if($application_cnt>5000)
		{
			$this->Flash->error('At a time only 5000 records must be download.');             
			return $this->redirect('/reports/MISReport');
		}
		else
		{
			$applicationData = $this->fetch_data($this->request->data);
			$PhpExcel=$this->PhpExcel;
			$PhpExcel->createExcel();
			// Add a drawing to the worksheetecho date('H:i:s') . " Add a drawing to the worksheet\n";
			$objDrawing = new \PHPExcel_Worksheet_MemoryDrawing();
			
			$objDrawing->setCoordinates('A1');
			$objDrawing->setWorksheet($PhpExcel->getExcelObj()->getActiveSheet());
			$j=1;
			$PhpExcel->writeCellValue('A'.$j, 'Sr No.');
			$PhpExcel->writeCellValue('B'.$j, 'TimeStamp');
			$PhpExcel->writeCellValue('C'.$j, 'Application Status');
			//$PhpExcel->writeCellValue('C'.$j, "Orientation");
			$PhpExcel->writeCellValue('D'.$j, "Scheme");
			$PhpExcel->writeCellValue('E'.$j, "Application No.");
			$PhpExcel->writeCellValue('F'.$j, "GEDA Registeration No.");
			$PhpExcel->writeCellValue('G'.$j, "Installer Name");
			$PhpExcel->writeCellValue('H'.$j, "Installer Category");
			$PhpExcel->writeCellValue('I'.$j, "PV Capacity Band");
			$PhpExcel->writeCellValue('J'.$j,"Project Name");
			$PhpExcel->writeCellValue('K'.$j,"PV Capacity");
			$PhpExcel->writeCellValue('L'.$j,"Discom Name");
			$PhpExcel->writeCellValue('M'.$j,"Consumer No.");
			$PhpExcel->writeCellValue('N'.$j,"Division/Zone");
			$PhpExcel->writeCellValue('O'.$j,"Sub-division");
			$PhpExcel->writeCellValue('P'.$j,"Sanctioned / Contract Load");
			$PhpExcel->writeCellValue('Q'.$j,"Phase of proposed Solar Inverter");
			$PhpExcel->writeCellValue('R'.$j,"Category");
			$PhpExcel->writeCellValue('S'.$j,"Who will provide the Net-Meter?");
			$PhpExcel->writeCellValue('T'.$j,"Lat");
			$PhpExcel->writeCellValue('U'.$j,"Long");
			$PhpExcel->writeCellValue('V'.$j,"Consumer Email");
			$PhpExcel->writeCellValue('W'.$j,"Consumer Mobile");
			$PhpExcel->writeCellValue('X'.$j,"Installer Email");
			$PhpExcel->writeCellValue('Y'.$j,"Installer Mobile");
			$PhpExcel->writeCellValue('Z'.$j,"Name Prefix");
			$PhpExcel->writeCellValue('AA'.$j,"First Name");
			$PhpExcel->writeCellValue('AB'.$j,"Middle Name");
			$PhpExcel->writeCellValue('AC'.$j,"Last Name");
			$PhpExcel->writeCellValue('AD'.$j,"Landline No.");
			$PhpExcel->writeCellValue('AE'.$j,"Street/ House No.");
			$PhpExcel->writeCellValue('AF'.$j,"Taluka");
			$PhpExcel->writeCellValue('AG'.$j,"District");
			$PhpExcel->writeCellValue('AH'.$j,"City/Village");
			$PhpExcel->writeCellValue('AI'.$j,"State");
			$PhpExcel->writeCellValue('AJ'.$j,"Pin");
			$PhpExcel->writeCellValue('AK'.$j,"Communication Address");
			$PhpExcel->writeCellValue('AL'.$j,"Passport Size Photo");
			$PhpExcel->writeCellValue('AM'.$j,"Whether the Premises is owned or Rented");
			$PhpExcel->writeCellValue('AN'.$j,"Electricity Bill");
			$PhpExcel->writeCellValue('AO'.$j,"Aadhaar No. Entered");
			$PhpExcel->writeCellValue('AP'.$j,"Solar PV system Owned by the Consumer");
			$PhpExcel->writeCellValue('AQ'.$j,"Don't Want Subsidy");
			$PhpExcel->writeCellValue('AR'.$j,"OTP Verified on");
			$PhpExcel->writeCellValue('AS'.$j,"Signed Document Uploaded Date");
			$PhpExcel->writeCellValue('AT'.$j,"Last Comment");
			$PhpExcel->writeCellValue('AU'.$j,"Last Comment Date");
			$PhpExcel->writeCellValue('AV'.$j,"Last Comment Replied Date");
			$PhpExcel->writeCellValue('AW'.$j,"Document Verified Date");
			$PhpExcel->writeCellValue('AX'.$j,"Field Report Status Received from DisCom on");
			$PhpExcel->writeCellValue('AY'.$j,"Quotation No.");
			$PhpExcel->writeCellValue('AZ'.$j,"DisCom Estimate Amount");
			$PhpExcel->writeCellValue('BA'.$j,"Due Date");
			$PhpExcel->writeCellValue('BB'.$j,"Payment Received");
			$PhpExcel->writeCellValue('BC'.$j,"Payment Made on");
			$PhpExcel->writeCellValue('BD'.$j,"Self- Certification");
			$PhpExcel->writeCellValue('BE'.$j,"CEI Drawing Application ID");
			$PhpExcel->writeCellValue('BF'.$j,"CEI Drawing Application Approval Date");
			$PhpExcel->writeCellValue('BG'.$j,"Work Order No.");
			$PhpExcel->writeCellValue('BH'.$j,"Work Order Date");
			$PhpExcel->writeCellValue('BI'.$j,"Work Start Date");
			$PhpExcel->writeCellValue('BJ'.$j,"Work End Date");
			$PhpExcel->writeCellValue('BK'.$j,"Bi-directional Meter Make");
			$PhpExcel->writeCellValue('BL'.$j,"Bi-directional Meter  No.");
			$PhpExcel->writeCellValue('BM'.$j,"Solar Meter Make");
			$PhpExcel->writeCellValue('BN'.$j,"Solar Meter  No.");
			$PhpExcel->writeCellValue('BO'.$j,"Date of Installation of Solar Meter");
			$PhpExcel->writeCellValue('BP'.$j,"Agreement Signing Date");
			$PhpExcel->writeCellValue('BQ'.$j,"Payment Status");
			$PhpExcel->writeCellValue('BR'.$j,"Payment Id");
			$PhpExcel->writeCellValue('BS'.$j,"Transaction ID");
			
			$j++;
			for($ch=65;$ch<=90;$ch++)
			{
				if($ch!=84 && $ch!=85)
				$PhpExcel->getExcelObj()->getActiveSheet()->getColumnDimension(chr($ch))->setAutoSize(true);
			}
			for($ch=65;$ch<=90;$ch++)
			{
				$PhpExcel->getExcelObj()->getActiveSheet()->getColumnDimension('A'.chr($ch))->setAutoSize(true);
			}
			for($ch=65;$ch<=81;$ch++)
			{
				$PhpExcel->getExcelObj()->getActiveSheet()->getColumnDimension('B'.chr($ch))->setAutoSize(true);
			}
			foreach($applicationData as $key=>$application_data)
			{
				$PhpExcel->writeCellValue('A'.$j, ($j-1));
				$PhpExcel->writeCellValue('B'.$j, $application_data['submited_date']);
				$PhpExcel->writeCellValue('C'.$j, $this->ApplyOnlineApprovals->application_status[$application_data['application_status']]);
				$PhpExcel->writeCellValue('D'.$j, $application_data['Scheme']);
				$PhpExcel->writeCellValue('E'.$j, $application_data['application_no']);
				$PhpExcel->writeCellValue('F'.$j, $application_data['geda_application_no']);
				$PhpExcel->writeCellValue('G'.$j, $application_data['installer_name']);
				$PhpExcel->writeCellValue('H'.$j, $application_data['installer_category']);
				$PhpExcel->writeCellValue('I'.$j, $application_data['allowed_bands']);
				$PhpExcel->writeCellValue('J'.$j, $application_data['name']);
				$PhpExcel->writeCellValue('K'.$j, floatval($application_data['pv_capacity']));
				$PhpExcel->writeCellValue('L'.$j, $application_data['DisCom Name']);
				$PhpExcel->writeCellValue('M'.$j, $application_data['consumer_no']);
				$PhpExcel->writeCellValue('N'.$j, $application_data['division_title']);
				$PhpExcel->writeCellValue('O'.$j, $application_data['subdiv_title']);
				$PhpExcel->writeCellValue('P'.$j, $application_data['sanction_load_contract_demand']);
				$PhpExcel->writeCellValue('Q'.$j, $application_data['Invertor_Phase']);
				$PhpExcel->writeCellValue('R'.$j, $application_data['App_Category']);
				$PhpExcel->writeCellValue('S'.$j, $application_data['Net_Meter_By']);
				$PhpExcel->writeCellValue('T'.$j, $application_data['latitude']);
				$PhpExcel->writeCellValue('U'.$j, $application_data['longitude']);
				$PhpExcel->writeCellValue('V'.$j, $application_data['consumer_email']);
				$PhpExcel->writeCellValue('W'.$j, $application_data['consumer_mobile']);
				$PhpExcel->writeCellValue('X'.$j, $application_data['installer_email']);
				$PhpExcel->writeCellValue('Y'.$j, $application_data['installer_mobile']);
				$PhpExcel->writeCellValue('Z'.$j, $application_data['Name_Prefix']);
				$PhpExcel->writeCellValue('AA'.$j, $application_data['First_Name']);
				$PhpExcel->writeCellValue('AB'.$j, $application_data['Middle_Name']);
				$PhpExcel->writeCellValue('AC'.$j, $application_data['Last_Name']);
				$PhpExcel->writeCellValue('AD'.$j, $application_data['landline_no']);
				$PhpExcel->writeCellValue('AE'.$j, $application_data['Street/ House No.']);
				$PhpExcel->writeCellValue('AF'.$j, $application_data['Taluka']);
				$PhpExcel->writeCellValue('AG'.$j, $application_data['District']);
				$PhpExcel->writeCellValue('AH'.$j, $application_data['City/Village']);
				$PhpExcel->writeCellValue('AI'.$j, $application_data['State']);
				$PhpExcel->writeCellValue('AJ'.$j, $application_data['Pin']);
				$PhpExcel->writeCellValue('AK'.$j, $application_data['comunication_address']);
				$PhpExcel->writeCellValue('AL'.$j, $application_data['Profile_Photo']);
				$PhpExcel->writeCellValue('AM'.$j, $application_data['Premises']);
				$PhpExcel->writeCellValue('AN'.$j, $application_data['Electricity_Bill']);
				$PhpExcel->writeCellValue('AO'.$j, $application_data['Aadhaar No. Entered']);
				$PhpExcel->writeCellValue('AP'.$j, $application_data['Solar PV system Owned by the Consumer']);
				$PhpExcel->writeCellValue('AQ'.$j, $application_data["Don't Want Subsidy"]);
				$PhpExcel->writeCellValue('AR'.$j, $application_data["OTP_Verified_On"]);
				$PhpExcel->writeCellValue('AS'.$j, $application_data["Signed_Uploaded_Date"]);
				$arr_comment_date = explode("|",$application_data["Last_Comment"]);
				if(!isset($arr_comment_date[1]))
				{
					$comment_date = NULL;
				}
				else
				{
					$comment_date = $arr_comment_date[1];
				}
				$PhpExcel->writeCellValue('AT'.$j, $arr_comment_date[0]);
				$PhpExcel->writeCellValue('AU'.$j, $comment_date);
				$PhpExcel->writeCellValue('AV'.$j, $application_data["Last_Comment_Replied_Date"]);
				$PhpExcel->writeCellValue('AW'.$j, $application_data["Document_Verified_Date"]);
				$arr_feasibility_data 	= explode("|",$application_data["fesibility_data"]);
				$fe_1 					=	isset($arr_feasibility_data[1]) ? $arr_feasibility_data[1] : 'NULL';
				$fe_2 					=	isset($arr_feasibility_data[2]) ? $arr_feasibility_data[2] : 'NULL';
				$fe_3 					=	isset($arr_feasibility_data[3]) ? $arr_feasibility_data[3] : 'NULL';
				$fe_4 					=	isset($arr_feasibility_data[4]) ? $arr_feasibility_data[4] : 'NULL';
				$fe_5 					=	isset($arr_feasibility_data[5]) ? $arr_feasibility_data[5] : 'NULL';
				$PhpExcel->writeCellValue('AX'.$j, $arr_feasibility_data[0]);
				$PhpExcel->writeCellValue('AY'.$j, $fe_1);
				$PhpExcel->writeCellValue('AZ'.$j, $fe_2);
				$PhpExcel->writeCellValue('BA'.$j, $fe_3);
				$PhpExcel->writeCellValue('BB'.$j, $fe_4);
				$PhpExcel->writeCellValue('BC'.$j, $fe_5);
				$PhpExcel->writeCellValue('BD'.$j, $application_data["Self_Certificate"]);
				$PhpExcel->writeCellValue('BE'.$j, $application_data["drawing_app_no"]);
				$PhpExcel->writeCellValue('BF'.$j, $application_data["drawing_approved_date"]);
				$arr_work_data = explode("|",$application_data["workorder_number_date"]);
				$PhpExcel->writeCellValue('BG'.$j, $arr_work_data[0]);
				$workorder_date 	=	isset($arr_work_data[1]) ? $arr_work_data[1] : 'NULL';
				$PhpExcel->writeCellValue('BH'.$j, $workorder_date);
				$arr_installation_data 		= explode("|",$application_data["installation_data"]);
				$end_date 					=	isset($arr_installation_data[1]) ? $arr_installation_data[1] : 'NULL';
				$meter_serial_no_make 		=	isset($arr_installation_data[2]) ? $arr_installation_data[2] : 'NULL';
				$meter_serial_no 			=	isset($arr_installation_data[3]) ? $arr_installation_data[3] : 'NULL';
				$solar_meter_manufacture 	=	isset($arr_installation_data[4]) ? $arr_installation_data[4] : 'NULL';
				$solar_meter_serial_no 		=	isset($arr_installation_data[5]) ? $arr_installation_data[5] : 'NULL';
				$PhpExcel->writeCellValue('BI'.$j, $arr_installation_data[0]);
				$PhpExcel->writeCellValue('BJ'.$j, $end_date);
				$PhpExcel->writeCellValue('BK'.$j, $meter_serial_no_make);
				$PhpExcel->writeCellValue('BL'.$j, $meter_serial_no);
				$PhpExcel->writeCellValue('BM'.$j, $solar_meter_manufacture);
				$PhpExcel->writeCellValue('BN'.$j, $solar_meter_serial_no);
				$arr_charging_data 	= explode("|",$application_data["charging_data"]);	
				$PhpExcel->writeCellValue('BO'.$j, $arr_charging_data[0]);
				$agreement_date 	=	isset($arr_charging_data[1]) ? $arr_charging_data[1] : 'NULL';
				$PhpExcel->writeCellValue('BP'.$j, $agreement_date);
				$PhpExcel->writeCellValue('BQ'.$j,$application_data['application_payment_status']);
				$arr_payment_data 	= explode("|",$application_data["payment_payu"]);	
				$transactionID 		=	isset($arr_payment_data[1]) ? $arr_payment_data[1] : 'NULL';
				$PhpExcel->writeCellValue('BR'.$j,$arr_payment_data['0']);
				$PhpExcel->writeCellValue('BS'.$j,$transactionID);
				$j++;
			}
			$PhpExcel->downloadFile(time());
			exit;
		}
	}
	/**
    *
    * modifysubdivision
    *
    * Behaviour : private
    *
    * @param : 
    *
    * @defination : Method is use to modify discom details
    *
    */
	public function modifysubdivision()
	{
		//274,305,309,315,424,460,475,501,617,763,848,1080,1101,1133,1162,1172,1173,1174,1176,1177,1276,1403,1589,1621,1638,1650,1681,1718,1720,1723,1724,1726,1729,1731,1801,1823,1847,1870,2314,2322,2351,2367,2375,2376,2435,2466,2483,2512,2528,2584,2626,2686,2732,2788,2804,2839,2846,2993,3066,3088,3190,3305,3370,3408,3504,3556,3580,3700,3835,4025,4287,4638,4810,4822,4840,4899,4908,4960,5211,5675,5982,5987,6004,6007,6010,6012,6014,6046,6083,6084,6103,6112,6308,6315,6457,7137,7156,7182,7319,7921,8099,8230,8488,8515,8816,8850,9142,9782,9943,10072,10725,10740,10744,10750,10753,10760,10765,10778,10779,10784,10786,10792,10793,10805,10808,10814,10818,11361,12297,12324,12438,12946,12961,12977,13033,13055,13064,13091,13109,13255,13263,13284,13422,13426,13429,13698,13716,13728,13763,13774,13792,13850,13885,13908,13993,13997,13999,14004,14030,14095,14132,14136,14138,14213,14217,14220,14222,14223,14225,14247,14255,14260,14262,14266,14268,14362,14427,14567,14588,14590,14606,14618,14629,14734,14766,15091,15100,16105,16110,16113,16114,16116,16118,16122,
		//$arrData = '16128,16135,16187,16874,16885,16935,16978,16990,16994,16995,17379,17393,17451,17667,17691,17716,17768,17911,17941,17986,18257,18729,19494,19746,19754,19782,20095,20752,20767,21079,21105,22208,22231,22233,23263,24512,25780,26470,26632,27055,27232,27655,27676,27777,27958,28034,28114,28120,28122,28143,28150,28153,28158,28163,28172,28237,28243,28248,28261,29191,29606,29623,30404,30445,31210,32317,32336,32733,32810,33047,33070,33892,34440,34533,34774,34836,35215,35309,35340,38829,38886';
		$arrData = '527,1501,3471,8461,9763,9815,15321,17969,19780';//,27670,33682';
		$arrData = '30404';
		$sql 				= "select * from apply_onlines where id in (47873,47868,47865,47836,47833,47831,47824,47822,47800,47793,47766,47762,47753,47750,47719,47713,47706,47701,47681,47679,47664,47646,47643,47633,47631,47597,47577,47565,47551,47526,47521,47513,47428,47365,47339,47320,47276,47259,47254,47220,47212,47208,47154,47150,47126,47119,47105,47101,47065,46990,46970,46960,46949,46812,46731,46710,46691,46686,46654,46651,46648,46558,46544,46518,46460,46432,46426,46386,46274,46267,46266,46251,46245,46157,46128,46126,46111,46080,46017,46000,45930,45869,45851,45837,45792,45751,45677,45652,45646,45643,45639,45630,45615,45613,45535,45513,45154)";
		$connection    		= ConnectionManager::get('default');
		$application 	 	= $connection->execute($sql)->fetchAll('assoc');
		//pr($application);
		$arr_appid 			= array();
		$count 				= 0;
		foreach($application as $app)
		{

			$arrResponseData= json_decode($app['api_response'],2);
			if($arrResponseData['P_OUT_STS_CD']==1)
			{
				$data_subdiv 	= $arrResponseData['P_OUT_DATA']['OUTPUT_DATA'];
				$data_subdiv['sub_division_api'] = $data_subdiv['SDO'];
				$data_subdiv['division_api'] = $data_subdiv['DIV'];
				$data_subdiv['circle_api'] = $data_subdiv['CIRCLE'];
				$discom_id = $app['discom'];
				if(!empty($data_subdiv['sub_division_api']))
				{
					$DiscomMasterHt 	= TableRegistry::get('DiscomMasterHt');
					if(!empty($data_subdiv['division_api']))
					{
						$conditionsArr 	= array('division_sort_code'=>$data_subdiv['division_api'],
												'ht_code'			=>$data_subdiv['sub_division_api'],
												'circle_sort_code'	=>$data_subdiv['circle_api'],
												'discom_code'		=>$this->ThirdpartyApiLog->arr_discom_map[$discom_id]);
						
						$HTSubdivision 	= $DiscomMasterHt->find('all',array('conditions'=>$conditionsArr))->first();
						
						if(!empty($HTSubdivision))
						{
							$data_subdiv['sub_division_api'] = $HTSubdivision->sort_code;
						}
						
					}
					$subdivision  		= $data_subdiv['sub_division_api'];
					$arr_dis_details 	= array();
					$discom_details 	= $this->DiscomMaster->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['DiscomMaster.short_code'=>$subdivision,'DiscomMaster.type'=>4,'status'=>'1']]);
					$arr_dis_details 	= $discom_details->toarray();
					if (!empty($arr_dis_details)) {
						$data_subdiv['subdivision']	= $arr_dis_details;
						$discom_data_details		= $this->DiscomMaster->find("all",['conditions'=>['id'=>key($arr_dis_details),'status'=>'1']])->first();
						
						$division_data 				= $this->DiscomMaster->find("all",['conditions'=>['circle'=>$discom_data_details->circle,'type'=>'3','status'=>'1']])->first();
						//$data_subdiv['division'] 	= $division_data->id;
						$data_subdiv['seldivision']	= $discom_data_details->division;
						if(!empty($data_subdiv['division_api']))
						{
							$division_data 		= $this->DiscomMaster->find("all",['conditions'=>['DiscomMaster.short_code'=>$data_subdiv['division_api'],'DiscomMaster.type'=>3,'area'=>$discom_data_details->area,'circle'=>$discom_data_details->circle,'status'=>'1']])->first();
							//$data_subdiv['division'] 	= $division_data->id;
						}
						$branch_detail 		= $this->BranchMasters->find("all",array('conditions'=>array('discom_id'=>$discom_data_details->area)))->first();
						$data_subdiv['seldiscom']	= $branch_detail->id;
						$arrDiscom 					= $this->DiscomMaster->GetDiscomHirarchyByID($data_subdiv['seldivision']);
						$data_subdiv['selcircle']	= $arrDiscom->circle;
						$data_subdiv['selsubdivision']	= key($data_subdiv['subdivision']);
						//$this->ApplyOnlines->updateAll(array('circle'=>$data_subdiv['selcircle'],'division'=>$data_subdiv['seldivision'],'subdivision'=>$data_subdiv['selsubdivision']),array('id'=>$app['id']));

					}
					pr($data_subdiv);
				}
				//pr($arrResponseData);
				echo $data_subdiv['CIRCLE']." ".$data_subdiv['DIV']." ".$data_subdiv['SDO']."==>".$app['id']."<br>";
				$count++;
			}
			
			
            	
            	
		}
		echo 'Count------'.$count;
		exit;
	}
	/**
	 * delete_application
	 * Behaviour : Public
	 * @defination : Method is use to delete application - to test delete application shell.
	 */
	public function delete_application()
	{
		$arrStatus 			= array($this->ApplyOnlineApprovals->APPLICATION_PENDING);
		$GUJARAT_STATE 		= 4;
		$Date 				= date('Y-m-d',strtotime(date('Y-m-d').' -3 days'));
		$arrApplications 	= array(42447,42434,42078,42041,42039,42003,41993,41835,41832,41830,41826,41820,41813,41806,41789,41722,41219,40161,39981,39970,39958,39824,39812,39785,39749,39736,39680,39622,39607,39588,39553,39525,39513,39504,39482,39475,39461,39457,39449,39213,39176,39156,39148,39144,39118,39097,39095,39089,39013,39001,38970,38951,38876,38873,38696,38681,38680,38676,38668,38659,38657,38648,38647,38644,38636,38627,38620,38618,38611,38607,38604,38602,38592,38584,38579,38573,38571,38546,38543,38525,38523,38497,38495,38493,38489,38485,38480,38475,38470,38459,38435,38426,38425,38402,38397,38396,38386,38379,38378,38377,38376,38365,38339,38334,38329,38322,38283,38275,38217,38172,38169,38166,38159,38153,38130,38129,38127,38126,38124,38101,38099,38089,38088,38066,38061,38031,38028,38027,38014,38012,37999,37997,37993,37972,37969,37958,37935,37923,37896,37890,37876,37866,37829,37822,37808,37798,37788,37784,37780,37765,37754,37745,37727,37712,37711,37706,37703,37683,37680,37677,37672,37671,37636,37633,37624,37593,37578,37561,37537,37526,37524,37522,37514,37485,37468,37467,37433,37411,37410,37403,37386,37374,37370,37343,37302,37292,37288,37283,37279,37275,37235,37231,37230,37228,37226,37214,37208,37205,37203,37200,37161,37155,37154,37153,37094,37087,37074,37050,37046,37039,37029,37017,37016,36988,36982,36978,36948,36928,36914,36901,36878,36876,36866,36864,36857,36849,36793,36783,36754,36695,36636,36630,36628,36622,36591,36518,36512,36503,36496,36484,36468,36452,36441,36413,36410,36403,36388,36374,36271,36265,36237,36202,36185,36183,36159,36132,36100,36052,36038,35866,35531,35523,35496,35448,35447,35424,35384,35373,35282,35247,35222,35191,35175,35166,35083,35018,34972,34966,34943,34873,34797,34760,34744,34724,34696,34672,34620,34603,34583,34405,34387,34202,34111,34098,34085,34052,34039,34003,33957,33950,33683,33672,33662,33633,33620,33600,33584,33544,33523,33512,33504,33474,33453,33424,33407,33316,33312,33266,33243,33208,33197,33186,33177,33156,33077,33064,33058,32923,32826,32716,32633,32621,32552,32524,32476,32283,32278,32264,32239,32235,32232,32212,32189,32148,31933,31876,31781,31751,31743,31730,31688,31596,31563,31561,31494,31491,31486,31378,31352,31327,31326,31253,31245,31187,31152,31036,31031,31015,30993,30943,30925,30923,30899,30898,30775,30650,30610,30585,30532,30502,30453,30268,30178,30168,30105,29998,29870,29851,29835,29804,29743,29727,29726,29657,29643,29633,29620,29366,29356,29203,29200,29199,29197,29195,29190,29158,29100,28836,28825,28776,28763,28751,28591,28584,28575,28523,28493,28488,28472,28425,28311,28227,28223,28157,28127,28043,27994,27986,27935,27928,27922,27707,27695,27680,27654,27630,27577,27511,27417,27385,27375,27360,27345,27334,27315,27308,27301,27287,27266,27253,27229,27222,27204,27177,27158,27152,27134,27129,27064,27037,26980,26821,26810,26762,26540,26391,25557,25461,25404,25386,25285,25274,25268,25161,25151,24962,24933,24871,24856,24813,24796,24777,24758,24653,24540,24537,24530,24376,24230,23784,23527,23366,23359,23329,23269,22873,22772,22632,22485,22451,22283,22220,22142,21992,21748,21701,21692,21281,21242,21232,20989,20941,20927,20885,20773,20751,20722,20526,20513,20482,20473,20424,20197,20075,20051,19945,19859,19504,19358,19271,19076,19061,19022,17891,17880,17862,17849,17836,17829,17776,17626,17312,17229,17206,16986,16979,16820,16784,16702,16660,16609,16598,16372,16168,16148,15830,15821,15805,15766,15713,15643,15632,15470,15428,15296,14950,14754,14419,14346,14294,14293,13855,13852,13791,13788,13287,12350,11830,11445,10541,10525,10500,10209,10161,9577,9205,8960,8770,8508,8487,8373,8345,8086,8046,7901,7874,7519,7311,7267,7069,6644,6494,6100,5853,5481,5254,4985,4927,4832,4184,3908,3884,3789,3215,2735,2718,2623,2355,1959,1400,1126,56);
		//$arrApplications 	= array(1906,2469,3867,4632,4658,5430,5888,6021,6151,6436,6498,7157,7167,7362,7514,8267,10075,10985,12061,12082,12322,12734,14388,15625,15627,16805,17357,17749,18900,21702,21712,21730,21766,21783,21804,21877,21884,21974,22013,22033,22335,22493,22970,23408,23411,23612,23643,24734,25074,25272,25273,25437,25866,27119,27267,27778,27946,28688,29374,29845,30495,30557,30825,31203,31276,33103,34084,34333,35206,35265,35304,35501,35691,35827,35828,36175,36798,37533,37791,38255,38380,38448,38508,38542,38558,38570,38583,38632,38658,38661,38683,38811,38831,38858);
		//$arrApplications 	= array(209,247);
		//$arrApplications 	= array(251,295,312,591,627,723,866,903,1169,1212,1240,1248,1294,1326,1356,1476,1586,1805,1815,1931,1956,2176,2193,2244,2338,2374,2531,2554,2660,2801,2806,2832,2885,2903,2942,3106,3125,3127,3134,3209,3234,3236,3308,3312,3321,3359,3455,3558,3644,3667,3702,3809,4027,4108,4154,4227,4349,4356,4405,4406,4443,4474,4491,4492,4572,4703,4754,4796,4800,4814,4820,4856,4888,4895,4929,5020,5081,5155,5293,5377,5492,5538,5558,5580,5701,5703,5732,5734,5764,5900,5905,5909,6165,6194,6368,6424,6475,6507,6515,6532,6571,6614,6631,6750,6784,6853,6909,6927,6933,7030,7037,7109,7125,7131,7134,7370,7415,7426,7515,7545,7750,7769,8047,8052,8096,8102,8108,8278,8297,8330,8374,8395,8486,8609,8622,8791,8870,8902,8941,9067,9325,9438,9587,9807,9961,9987,10429,10767,10775,10789,10803,10813,10824,10875,11017,11096,11215,11230,11275,11577,11713,12003,12081,12094,12100,12131,12211,12248,12282,12301,12315,12363,12386,12469,12474,12509,12679,12873,13015,13069,13156,13176,13205,13221,13347,13352,13416,13421,13450,13490,13603,13604,13829,13877,13942,13958,14215,14301,14343,14348,14355,14381,14407,14461,14547,14554,14701,14720,14755,14982,15023,15036,15148,15191,15377,15467,15615,15656,15734,15757,15784,15824,15869,16033,16140,16273,16399,16420,16427,16474,16509,16515,16567,16574,16575,16579,16633,16802,16837,16943,17092,17124,17231,17235,17272,17296,17385,17440,17504,17521,17522,17533,17534,17594,17683,17712,17788,17814,17820,17846,17863,17866,17884,17959,18020,18228,18278,18316,18366,18413,18504,18571,18601,18632,18797,18851,18958,19231,19272,19293,19322,19394,19413,19434,19592,19606,19607,19613,19628,19638,19660,19682,19691,19766,19889,19940,19971,19982,20006,20007,20020,20054,20073,20074,20097,20147,20195,20201,20290,20296,20386,20477,20484,20517,20520,20543,20548,20570,20588,20640,20657,20676,20716,20718,20888,20940,21017,21041,21087,21098,21125,21135,21145,21173,21378,21399,21462,21483,21501,21569,21678,21722,21753,21760,21791,21881,21917,21953,21969,21998,22004,22034,22194,22382,22423,22437,22504,22549,22576,22582,22667,22798,22809,22830,23002,23036,23041,23043,23064,23065,23078,23086,23092,23153,23223,23232,23250,23305,23336,23410,23423,23442,23635,23691,23697,23761,23781,23802,23865,23890,23906,23914,24097,24107,24140,24171,24209,24277);
		$arrApplications 	= array(24289,24295,24296,24309,24350,24575,24581,24594,24607,24608,24636,24639,24651,24678,24711,24736,24803,24805,24902,24944,24954,24965,24974,24975,25046,25087,25164,25173,25179,25185,25369,25432,25451,25554,25560,25588,25646,25731,25795,25910,25949,25995,26088,26113,26160,26165,26170,26195,26198,26201,26228,26294,26367,26393,26398,26428,26440,26450,26505,26559,26647,26669,26672,26699,26756,26828,26886,26932,26935,27049,27109,27123,27191,27216,27238,27254,27310,27355,27499,27585,27612,27635,27636,27645,27660,27741,27742,27773,27803,27816,27822,27823,27833,27837,27838,27884,27911,27943,27951,27980,27991,28176,28196,28232,28312,28332,28357,28422,28464,28475,28486,28602,28639,28663,28700,28744,28801,28871,29052,29080,29082,29157,29249,29351,29445,29526,29549,29559,29612,29646,29725,29759,29844,29862,29892,29903,29929,29997,30032,30046,30062,30165,30265,30284,30299,30315,30387,30406,30455,30470,30554,30559,30570,30604,30628,30798,31251,31331,31605,31671,31796,31831,31909,32046,32259,32377,32430,32445,32484,32624,32740,32744,32825,32909,32946,32952,32967,32993,33005,33026,33063,33088,33141,33144,33146,33221,33354,33359,33492,33556,33560,33568,33635,33653,33663,33685,33740,33758,33776,33782,33881,33916,34102,34174,34235,34257,34325,34345,34433,34448,34451,34454,34486,34493,34518,34536,34546,34602,34612,34710,34716,34745,34929,35006,35115,35140,35229,35272,35370,35385,35413,35428,35452,35491,35536,35539,35542,35550,35565,35572,35577,35591,35594,35610,35627,35642,35667,35669,35678,35686,35706,35722,35817,35851,35929,35941,35944,35961,36061,36113,36139,36174,36316,36357,36398,36444,36461,36508,36625,36638,36645,36655,36668,36680,36694,36709,36716,36722,36772,36778,36795,36833,36902,36934,36944,36983,37011,37033,37072,37076,37104,37280,37334,37404,37409,37500,37520,37570,37635,37650,37737,37801,37860,37897,37914,37915,37932,37939,38046,38118,38139,38157,38273,38372,38375,38422,38432,38540,38885,38894,38961,39007,39134,39208,39209,39256,39262,39263,39274,39279,39287,39322,39324,39331,39340,39349,39351,39356,39378,39393,39422,39425,39426,39473,39640,39696,39893,40033,40047,40107,40171,40228,40306,40392,40399,40405,40434,40482,40488,40549,40592,40618,40619,40634,40658,40680,40732,40784,40785,40828,40854,41009,41106,41245,41256,41310,41317,41449,41515,41568,41576,41577,41595,41606,41610,41645,41753,41776,41831,41833,41939,41983,42028,42029,42033,42037,42040,42045,42059,42156,42210,42239,42271,42310,42345,42357,42359,42377,42384,42422,42423,42435,47288,47294);
		$arrApplications 	= array(798,4222,10334,9983,12365,16622,16749,16790,16467,16814,17116,11866,19567,17977,17172,14747,19415,19444,22061,23271,16276,16167,24183,21693,24494,24562,25686,12072,14669,12702,26795,25700,25714,26461,27916,28321,29682,15174,29098,29085,32309,32334,32991,20515,19672,19762,20217,20250,13937,32784,21634,31569,35044,35156,35295,20233,35606,35882,35858,37084,37031,14385,13012,28845,22114,37318,37496,33277,39040,39848,40486,40217,40195,41271,41672,42038,42379,46720,47527,47622,47911,48801,50388,52169,52407,53143,54429,54428,56953,57963,59750,59889,60346,60865,60854,60617,62109,62901,63833,62948,64271,63022,64626,64245,64683,66954,67539,66501,68064,68118,68379,68365,62643,68458,70396,70481,71432,72351,73140,73863,73866,74049,74927,75664,76511,76610,76613,76673,76685,77236,78328,78393,78869,78614,70607,80983,80972,82380,82773,83978,83983,84285,84755,85808,83118,86936,87789,87571,88556,88637,88759,89040,88692,89513,89822,87806,90501,91470,92002,92417,92420,92422,92424,92491,92994,93184,93329,93405,93406,93415,94077,94602,95251,96108,96107,94732,97256,98517,98854);
		/*,46711,106302,*/
		$arrApplications 	= array(62471,46790,62118,66756,67566,67949,72929,95536,95555,95890,108770,111663,111679,112743,114958,116701,70516,114988,108276,56939,116634,102534,61067,47131,49666,49667,61849,61932,76655,103016,114588,116521,116523,116527,116529,116533,116534,116536,116540,116582,116913,116916,116925,116931,97789,102667,102739,102743,100813,71829,72617,76047,86947,109690,115863,7453,46614,71051,116358,117011,60382,60386,60397,87839,48255,84606,84721,85412,86020,110320,110337,110401,82880,70034,83861,88279,46328,74459,106530,107440,116872,117134,64307,71075,114549,115704,116486,66086,62336,62350,94098,94100,107908,71172,49043,49961,55703,77127,80285,114915,110480,51175,103435,115761,113109,114211,116667,116671,103832,88894,116393,46953,76806,116680,116851,93835,75929,68744,116895,79407,79800,108871,66174,46724,54524,91182,117299,116049,117137,117296,74273,74392,45698,63847,60522,65003,65019,65023,89794,46465,116968,107273,70888,113908,47035,89168,115668,116029,117080,50830,76723,56165,56166,57241,63943,86898,46228,72300,114262,115057,109729,51799,115460,115462,115922,116972,114617,67123,73494,46522,56085,105316,106005,101574,112150,112173,112308,115299,115331,115488,115489,115657,115765,116355,116723,116725,116727,116728,116729,116734,116736,116743,116992,117161,117171,117254,117256,116987,61214,72451,93271,93275,100596,75093,107253,47325,98928,102971,104837,104946,111643,115164,73421,98403,107240,61734,70334,115572,115815,116609,117059,115269,64322,114860,53112,112694,47139,66796,74413,100469,102232,102836,103949,108858,109198,111049,111052,113366,113368,114440,114478,114480,116264,117398,117403,45789,59941,64136,114538,73157,90820,91296,115268,115274,115869,115888,96358,116918,46231,72936,75487,85772,88756,114625,114761,116002,117077,55048,54771,46489,47817,47828,47885,47969,48227,48342,48343,48344,65993,66256,66258,66997,96610,98610,107356,46818,52939,113828,48111,57309,59601,109968,53910,113992,117106,66742,110683,107064,46308,47696,47699,49346,55914,110235,117173,115975,99196,100354,107121,111823,68046,69201,115685,73124,62233,82070,105047,113480,85116,66520,51671,51672,66562,66951,68449,73869,84356,114747,115135,115341,115523,116136,116962,117215,73042,87619,113928,78661,116599,48225,62189,63719,80007,80011,80025,80027,80053,80056,80068,80083,80086,113597,116889,63622,63748,64664,45986,46025,51654,68221,68981,69118,83084,83093,89955,64617,112818,68651,78890,78896,78913,78914,109487,104193,45949,56758,71444,77204,85948,66004,66171,68890,68891,69293,90147,114430,113941,87317,113729,57222,64365,112379,112387,112389,53376,79449,53769,94851,47463,116830,47839,106196,108901,68733,117262,106354,114065,114070,116052,116754,110957,107477,112037,80014,80020,105455,64058,107803,115882,116829,116836,112075,81187,74035,74037,74038,74055,74064,74065,74069,74071,74072,74080,74082,59964,59981,69903,77060,110605,111728,113405,114806,102760,102824,102853,115201,48096,112186,113211,114247,46266,79153,117006,46257,70158,47406,103594,115308,116180,116552,94262,115692,102480,63611,86547,116957,112055,114129,115515,112120,63756,100440,116058,117283,73265,74337,74432,87741,88095,117184,65212,65808,65812,111978,112580,117302,108034,47521,115197,115715,116984,115371,77462,103459,77876,88981,107206,112547,112204,115830,116503,115188,65961,89507,85315,85317,112264,71969,77787,77792,81864,107131,50788,87123,87469,115610,110490,64144,111040,63686,112345,116242,72923,68444,115077,50545,64443,109430,107874,66761,93992,109722,111834,116856,114956,66122,106547,115125,70203,70205,70206,70207,70213,70215,70233,70240,81527,48423,48428,104716,97668,108035,115510,117166,117197,117259,114784,108829,112788,117010,109299,109359,113024,113102,114960,110471,116122,116140,59913,63942,61845,112283,112511,112549,117157,79990,52024,52075,59562,59568,59577,59803,59805,93215,66974,114797,113598,57137,66036,66125,102301,112987,115953,73752,78883,80511,80523,80524,80526,80570,80578,80580,80581,80611,80615,80645,80660,90152,90155,90157,90160,90187,90188,90189,90198,90199,109736,109741,112112,68332,111789,61372,89051,92072,100152,87447,76514,102310,116994,115986,62604,71636,61513,62218,75085,75625,116953,81778,90239,110149,109932,116555,117279,108331,66983,68706,90071,85290,98840,117202,117203,117204,63934,115380,115384,115558,116795,116805,60890,98358,60501,70964,114900,68726,87213,116336,71614,66984,116359,87210,81820,100410,100419,100444,100461,100544,100549,100556,115224,78755,78758,116807,117406,90311,117123,70060,114327,114331,114435,114570,77348,83410,105421,115434,76603,107935,102801,105512,105664,112895,114213,113129,109700,59625,63738,105106,105077,74442,55987,66904,111906,113459,115831,76179,76369,116915,67439,111174,117039,114777,116461,115627,116966,115366,116553,82187,106265,114778,85743,103413,103669,84861,85591,85593,65045,65850,89630,66570,66702,66703,89762,114705,87479,115950,113096,116223,115290,114693,107723,81209,116619,85830,110963,115629,68685,109999,63781,65895,67591,89081,86180,115568,74349,72424,73007,95010,115895,112473,87250,97977,86271,67064,67900,68067,73496,73567,73572,73574,73576,76774,110218,109577,108827,115513,105251,107102,116806,116812,116585,117155,70272,70371,70885,76635,82474,106330,111155,115166,112064,115675,115678,115679,81137,102632,111338,113347,115112,79999,114672,116176,116205,106701,107662,72752,110418,110434,104451,111424,111487,104025,79879,79188,79867,110643,109712,117163,117195,116642,75935,98280,86881,111372,99215,113307,95065,116783,116148,85445,84070,85622,115581,113267,100482,115877,107829,111249,115370,115748,108873,102110,102116,102142,102147,102153,102163,102194,102211,102221,102229,102252,91053,91357,91359,91361,91513,91518,91521,91552,91566,91570,91574,91583,91587,91596,105863,104953,88966,88972,102474,102492,95177,116844,116876,111291,115603,117337,104538,115123,109773,115665,116746,113214,115696,112734,108020,110936,111644,116600,115226,115414,108479,114047,110653,116377,105193,103593,107604,109522,103465,108433,108713,116741,107269,109025,115807,116139);
		$arrApplications 	= array(100200,100204,100206,100211,100214,100216,100234,100237,100240,100245,100259,100525,100552,100567,100592,102057,102911,102922,104769,104817,105144,105339);
		//$arrApplications 	= array(27999);
		//exit;
		$counter 					= 0;
		foreach($arrApplications as $arrApplication)
		{
			$id 					= $arrApplication;
			//$applyOnlinesData 		= $this->ApplyOnlines->viewApplication($id);
			$application_data 		= $this->ApplyOnlines->find('all',array('conditions'=>array('id'=>$id)))->toArray();
			//$proj_id = $applyOnlinesData['project_id'];
			if (!empty($application_data)) {
				//$this->InstallerProjects->deleteAll(['project_id' => $proj_id]);
				//$proj_data 			= $this->Projects->get($proj_id);
				//$this->Projects->delete($proj_data);
				//$this->ApplyonlinDocs->deleteAll(['application_id' => $id]);
				//$this->ApplyOnlineApprovals->deleteAll(['application_id' => $id]);
				
				/*$path 				= APPLYONLINE_PATH.$id;
				if (file_exists($path))
				{
					$removedir 		= "rm -rf ".$path;
					system($removedir);
				}*/
				$application_data[0]['delete_type']= 'script';
				$browser 					   	= isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:"-";
				$removeentity                  	= $this->ApplicationDeleteLog->newEntity();
				$removeentity->application_id  	= $id;
				$removeentity->customer_id     	= 0;
				$removeentity->ip_address      	= $_SERVER['REMOTE_ADDR'];
				$removeentity->browser_info	   	= json_encode($browser);
				$removeentity->application_data	= json_encode($application_data);
				$removeentity->reason			= 'Deleted as requested';
				$removeentity->created 		   	= $this->NOW();
				if($this->ApplicationDeleteLog->save($removeentity)){
				$entity 			= $this->ApplyOnlines->get($id);
				$this->ApplyOnlines->delete($entity);
				echo "application ".$id." deleted<br>";
				$counter++;
				}

			}
		}
		echo "Total Deleted ".$counter;
		exit;
	}
	public function getVisitorCounter()
	{
		$this->layout 	= false;
		$connection     = ConnectionManager::get('default');
		$countvisit 	= $connection->execute("SELECT count(DISTINCT ip_address,created) as total_counter FROM `visitor_tracker`")->fetchAll('assoc');
    	$this->UniqueVisitorCount->updateAll(array('visitor_total_count'=>$countvisit[0]['total_counter']),array('id'=>1));
    	echo "Updated";
    	exit;
	}
	public function TestfeasibilityTorrent()
	{
		echo '<pre>';
		$this->layout 	= false;

		$application_id = 127383;
		$applications   = array();

		//$application_id = 63057;
		$application_id = 70970;
		//$application_id = 62351;
		$application_id = 67207;
		$application_id = 72114;
		$application_id = 98353;
		/*$arrResult 		= $this->FesibilityReport->fetchApiFeasibility($application_id,true);
		print_r($arrResult);
		$application_id = 96510;*/
		$application_id = 107554;
		$arrResult 		= $this->FesibilityReport->fetchApiFeasibility($application_id,true);
		print_r($arrResult);
		exit;
		
		echo "\r\n--StartTime::".date("Y-m-d H:i:s")."--\r\n";
        $query_sent 		          = 1;
        $GUJARAT_STATE 		          = 4;
        $CurrentHour                  = date("H");
        if ($CurrentHour > 8 && $CurrentHour < 20) {
            $LIMIT_QUERY_SENT         = 5; 
        } else {
            $LIMIT_QUERY_SENT	      = 5;
        }
        
        $LastProcessedApplicationID   = 0;
        $application_status = array($this->ApplyOnlineApprovals->DOCUMENT_VERIFIED,
                                    $this->ApplyOnlineApprovals->FIELD_REPORT_SUBMITTED,
                                    $this->ApplyOnlineApprovals->DOCUMENT_VERIFIED,
                                    $this->ApplyOnlineApprovals->FEASIBILITY_APPROVAL,
                                    $this->ApplyOnlineApprovals->SUBSIDY_AVAILIBILITY,
                                    $this->ApplyOnlineApprovals->WORK_STARTS,
                                    $this->ApplyOnlineApprovals->APPROVED_FROM_CEI,
                                    $this->ApplyOnlineApprovals->METER_INSTALLATION,
                                    $this->ApplyOnlineApprovals->DRAWING_APPLIED,
                                    $this->ApplyOnlineApprovals->WORK_EXECUTED,
                                    $this->ApplyOnlineApprovals->CEI_APP_NUMBER_APPLIED,
                                    $this->ApplyOnlineApprovals->APPROVED_FROM_DISCOM,
                                    $this->ApplyOnlineApprovals->CEI_INSPECTION_APPROVED);
        $LastRowID          = $this->CronApiProcess->GetLastRowID("check_to_fe_report");
        if (!empty($LastRowID)) 
        {
            $arrConditions      = [ 'application_status IN '=>$application_status,
                                    'apply_state'=>$GUJARAT_STATE,
                                    'ApplyOnlines.id > '=>$LastRowID,
                                    'ApplyOnlines.discom IN '=>array($this->ApplyOnlines->torent_ahmedabad,$this->ApplyOnlines->torent_surat),
                                    ['OR'=>['payment_approve'=>'0','payment_approve IS NULL']]];
        } else {
            $arrConditions      = [ 'application_status IN '=>$application_status,
                                    'apply_state'=>$GUJARAT_STATE,
                                    'ApplyOnlines.id > '=>$LastRowID,
                                    'ApplyOnlines.discom IN '=>array($this->ApplyOnlines->torent_ahmedabad,$this->ApplyOnlines->torent_surat),
                                    ['OR'=>['payment_approve'=>'0','payment_approve IS NULL']]];
        }
        $arrApplications 	= $this->ApplyOnlines->find('all',
                                                                [   'fields'        => ["ApplyOnlines.id"],
                                                                    'join'          => ['table'           =>'fesibility_report',
                                                                        'type'=>'left',
                                                                        'conditions'=>'fesibility_report.application_id = ApplyOnlines.id'
                                                                    ],
                                                                    'conditions'    => $arrConditions,
                                                                    'limit'         => $LIMIT_QUERY_SENT,
                                                                    'order'         => 'ApplyOnlines.id ASC',
                                                                ]
                                                        );
        $FetchedRowCount = $arrApplications->count();
        //echo "\r\n--FetchedRowCount::".$FetchedRowCount."--\r\n";
        echo "\r\n--arrConditions::".json_encode($arrConditions)."--\r\n";
       
        //die;
        if (!empty($FetchedRowCount))
        {
			foreach($arrApplications as $rowid=>$arrApplication)
			{
                $allStages  = $this->ApplyOnlineApprovals->Approvalstage($arrApplication->id);
                
                if(in_array($this->ApplyOnlineApprovals->DOCUMENT_VERIFIED,$allStages))
                {
                    echo "\r\n--rowid::".$rowid."--\r\n";
                    $LastProcessedApplicationID = $arrApplication->id;
                    $this->FesibilityReport->fetchApiFeasibility($arrApplication->id,true);
                    $this->CronApiProcess->saveAPILog($LastProcessedApplicationID,"check_to_fe_report");
                }
			}
            echo "\r\n--LastProcessedApplicationID::".$LastProcessedApplicationID."--\r\n";
		} else {
            $this->CronApiProcess->saveAPILog($LastProcessedApplicationID,"check_to_fe_report");
        }
		echo "\r\n--EndTime::".date("Y-m-d H:i:s")."--\r\n";
		exit;
	}
	public function TestMeterTorrent()
	{
		echo '<pre>'; //63057
		$arrMeter = array(51162,55665,58126,61522,63564,63655,64285,66753,66755,66759,66760,66763,67116,68575,71341,71363);
		$arrMeter = array(80490,80465);
		foreach($arrMeter as $val) {

			$response 	= $this->ChargingCertificate->fetchApiMeterInstallation($val);
			print_r($response);
		}
		exit;
		$this->layout 	= false;
		echo "\r\n--StartTime::".date("Y-m-d H:i:s")."--\r\n";
        $query_sent 		          = 1;
        $GUJARAT_STATE 		          = 4;
        $CurrentHour                  = date("H");
        if ($CurrentHour > 8 && $CurrentHour < 20) {
            $LIMIT_QUERY_SENT         = 4; 
        } else {
            $LIMIT_QUERY_SENT         = 4;
        }
        $LastProcessedApplicationID   = 0;
        $application_status           = array(  $this->ApplyOnlineApprovals->CEI_INSPECTION_APPROVED, $this->ApplyOnlineApprovals->WORK_STARTS, $this->ApplyOnlineApprovals->WORK_EXECUTED,$this->ApplyOnlineApprovals->APPROVED_FROM_CEI);
        $LastRowID                    = $this->CronApiProcess->GetLastRowID("check_t_meter_report");
        if (!empty($LastRowID)) 
        {
            $arrConditions      = [ 'application_status IN '=>$application_status,
                                    'apply_state'=>$GUJARAT_STATE,
                                    'ApplyOnlines.discom IN '=>array($this->ApplyOnlines->torent_ahmedabad,$this->ApplyOnlines->torent_surat),
                                    'id > '=>$LastRowID];
        } else {
            $arrConditions      = [ 'application_status IN '=>$application_status,
                                    'ApplyOnlines.discom IN '=>array($this->ApplyOnlines->torent_ahmedabad,$this->ApplyOnlines->torent_surat),
                                    'apply_state'=>$GUJARAT_STATE];
        }
        $arrApplications    = $this->ApplyOnlines->find('all',
                                                                [   'fields'        => ["id"],
                                                                    'conditions'    => $arrConditions,
                                                                    'limit'         => $LIMIT_QUERY_SENT,
                                                                    'order'         => 'ApplyOnlines.id ASC',
                                                                ]
                                                        );
        $FetchedRowCount = $arrApplications->count();
        if (!empty($FetchedRowCount))
        {
			foreach($arrApplications as $arrApplication)
			{
                $LastProcessedApplicationID = $arrApplication->id;
				$this->ChargingCertificate->fetchApiMeterInstallation($arrApplication->id);
                $this->CronApiProcess->saveAPILog($LastProcessedApplicationID,"check_t_meter_report");
			}

		} else {
            $this->CronApiProcess->saveAPILog($LastProcessedApplicationID,"check_t_meter_report");
        }
		echo "\r\n--EndTime::".date("Y-m-d H:i:s")."--\r\n";
		exit;
	}
	public function testData()
	{
		//$input_array 		= array("INPUT_DATA"=>array("cnsmr_no"=>'701000021'));
		//$arr_reponseData 	= $this->ThirdpartyApiLog->searchConsumerApi('701000021',17,0,0,'3002557709');
		//pr($arr_reponseData);
		echo '<prE>';
		$url    		= 'https://connect.torrentpower.com/tplwss/geda/call_geda.php';
		$str_data 		= '';
		//$input_array 	= '701000021';
		
        $data_request 			= array("P_IN_DATA"		=> '320379',
	    							"P_SRV_CD"		=> 1,
	    							"P_CLIENT_CD"	=> 1,
	    							"P_DISCOM_CD"	=> 5,
	    							"P_DT_TM"		=> '16.09.2019.11.35.15',
    								"P_AUTH_KEY"	=> 'test');
        $data_request = array_merge($data_request,array('P_IN_DATA2'=>'3000675608'));
    	//unset($data_request['P_CLIENT_CD']);
    	//unset($data_request['P_SRV_CD']);
    	$str_imp 			= array();
    	foreach($data_request as $key=>$val)
	   		{
	   			$str_imp[]	= $key."=".$val;
	   		}
	   		if(!empty($str_imp))
	   		{
	   			$str_data 	= '?'.implode("&",$str_imp);
	   		}

		$post_string   		= json_encode($data_request);
		echo $url.$str_data;
		$conn 				= curl_init( $url.$str_data );
		curl_setopt( $conn, CURLOPT_HTTPHEADER, array());
        curl_setopt( $conn, CURLOPT_CONNECTTIMEOUT, 300 );
        curl_setopt( $conn, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $conn, CURLOPT_SSL_VERIFYHOST, 0 );
        curl_setopt( $conn, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $conn, CURLOPT_POST, true );
    	curl_setopt( $conn, CURLOPT_POSTFIELDS, $post_string);
    	
        $output 			= curl_exec( $conn );
		$Response   		= json_decode($output);

		print_r($Response);
		exit;
	}
	/**
	 * generateSubsidySummarySheet
	 * Behaviour : public
	 * @param : id  : application_ids is use to generate applications, $isdownload=true
	 * @defination : Method is use to download .pdf file from applyonline listing
	 *
	 */
	public function generateSubsidySummarySheet()
	{
		$application_ids = 31240;

		if(empty($application_ids)) {
			return false;
		} else {
			$ApplyOnlines 		= TableRegistry::get('ApplyOnlines');
			$application_ids = explode(",",$application_ids);

		$arrResult = array();

		$fields = [ 'ApplyOnlines.id',
					'name_of_consumer_applicant'=> "CONCAT(ApplyOnlines.name_of_consumer_applicant, ' ', ApplyOnlines.last_name, ' ', ApplyOnlines.third_name)",
					'ApplyOnlines.address1',
					'ApplyOnlines.address2',
					'ApplyOnlines.city',
					'ApplyOnlines.state',
					'ApplyOnlines.pincode',
					'ApplyOnlines.mobile',
					'ApplyOnlines.consumer_mobile',
					'ApplyOnlines.landline_no',
					'ApplyOnlines.aadhar_no_or_pan_card_no',
					'ApplyOnlines.attach_photo_scan_of_aadhar',
					'ApplyOnlines.attach_pan_card_scan',
					'ApplyOnlines.pan_card_no',
					'ApplyOnlines.attach_recent_bill',
					'ApplyOnlines.house_tax_holding_no',
					'ApplyOnlines.attach_latest_receipt',
					'ApplyOnlines.pv_capacity',
					'ApplyOnlines.email',
					'ApplyOnlines.discom_name',
					'ApplyOnlines.discom',
					'ApplyOnlines.consumer_no',
					'ApplyOnlines.geda_application_no',
					'ApplyOnlines.pcr_code',
					'ApplyOnlines.pcr_submited',
					'ApplyOnlines.approval_id',
					'ApplyOnlines.social_consumer',
					'ApplyOnlines.common_meter',
					'customers.name',
					'customers.email',
					'installer.installer_name',
					'parameter_cats.para_value',
					'branch_masters.title',
					'geda_registration_date'=>'apply_online_approvals.created',
					'project_installation.modules_data',
					'project_installation.inverter_data',
					'project_installation.meter_manufacture',
					'project_installation.meter_serial_no',
					'project_installation.solar_meter_manufacture',
					'project_installation.solar_meter_serial_no',
					'project_installation.bi_date',
					'project_installation.agreement_date',
					'fesibility_report.payment_date',
					'apply_onlines_subsidy.cei_licence_no',
					'apply_onlines_subsidy.cei_authorised_by',
					'apply_onlines_subsidy.cei_licence_expiry_date',
					'apply_onlines_subsidy.cei_self_certification_date',
					'apply_onlines_subsidy.cei_contractor',
					'apply_onlines_subsidy.cei_superviser',
					'apply_onlines_subsidy.signing_authority',
					'apply_onlines_subsidy.modules_data',
					'apply_onlines_subsidy.inverter_data',
					'apply_onlines_subsidy.comm_date',
					'projects.estimated_cost',
					'projects.recommended_capacity',
					'projects.state',
					'projects.customer_type',
					'subsidy_claim_requests.request_no',
			];

		$applyOnlinesData   = $this->ApplyOnlines->find('all',[
			'fields'=>$fields,
			'join'=>[   
						['table'=>'installers','alias'=>'installer','type'=>'left','conditions'=>'installer.id = ApplyOnlines.installer_id'],
						['table'=>'customers','type'=>'left','conditions'=>'customers.id = ApplyOnlines.customer_id'],
						['table'=>'branch_masters','type'=>'left','conditions'=>'branch_masters.id = ApplyOnlines.discom'],
						['table'=>'fesibility_report','type'=>'left','conditions'=>'fesibility_report.application_id = ApplyOnlines.id'],
						['table'=>'project_installation','type'=>'left','conditions'=>'project_installation.project_id = ApplyOnlines.project_id'],
						['table'=>'apply_online_approvals','type'=>'left','conditions'=>['apply_online_approvals.application_id = ApplyOnlines.id','apply_online_approvals.stage = 31']],
						['table'=>'apply_onlines_subsidy','type'=>'left','conditions'=>'apply_onlines_subsidy.application_id = ApplyOnlines.id'],
						['table'=>'projects','type'=>'left','conditions'=>'ApplyOnlines.project_id = projects.id'],
						['table'=>'parameters','alias'=>'parameter_cats','type'=>'left','conditions'=>'parameter_cats.para_id = ApplyOnlines.category'],
						['table'=>'subsidy_claim_request_applications','conditions'=>'subsidy_claim_request_applications.application_id = ApplyOnlines.id','type'=>'left'],
						['table'=>'subsidy_claim_requests','conditions'=>'subsidy_claim_request_applications.request_id = subsidy_claim_requests.id','type'=>'left']
					],
			'conditions'=>['ApplyOnlines.id IN '=>$application_ids]
			]);
		echo '<pre>';
		debug($aaplyOnlinesData);
		print_r($applyOnlinesData);

		if (!empty($applyOnlinesData)) 
		{
			$Projects               = TableRegistry::get('Projects');
			$ApplyonlinDocs         = TableRegistry::get('ApplyonlinDocs');
			foreach($applyOnlinesData as $application)
			{
				$Applyonlinprofile      = $ApplyonlinDocs->find('all',['conditions'=>['application_id'=>$application['id'],'doc_type'=>'profile']])->first();
				$Profile_Photo_Url      = "";
				$DOCUMENT_PATH          = WWW_ROOT.APPLYONLINE_PATH.($application['id']).'/';

				if(!empty($Applyonlinprofile)) 
				{
					$IMAGE_PATH = $DOCUMENT_PATH.$Applyonlinprofile['file_name'];
					if (file_exists($IMAGE_PATH)) 
					{
						$ext                = pathinfo($IMAGE_PATH, PATHINFO_EXTENSION);
						$converted_filename = $DOCUMENT_PATH.$application['id']."_profile_photo.jpg";
						if (!file_exists($converted_filename)) 
						{

							if ($ext == "png" || $ext == "gif" || $ext == "jpeg")
							{
								//new file name once the picture is converted
								if ($ext=="png") $new_pic = imagecreatefrompng($IMAGE_PATH);
								if ($ext=="gif") $new_pic = imagecreatefromgif($IMAGE_PATH);
								if ($ext=="jpeg") $new_pic = imagecreatefromjpeg($IMAGE_PATH);

								// Create a new true color image with the same size
								$w = imagesx($new_pic);
								$h = imagesy($new_pic);
								$white = imagecreatetruecolor($w, $h);

								// Fill the new image with white background
								$bg = imagecolorallocate($white, 255, 255, 255);
								imagefill($white, 0, 0, $bg);

								// Copy original transparent image onto the new image
								imagecopy($white, $new_pic, 0, 0, 0, 0, $w, $h);

								$new_pic = $white;
								imagejpeg($new_pic, $converted_filename);
								imagedestroy($new_pic);
							} else {
								$converted_filename = $IMAGE_PATH;
							}
						}
						$Profile_Photo_Url = $converted_filename;
					}
				}
				
				$subsidy_data = $Projects->calculatecapitalcostwithsubsidy($application['projects']['recommended_capacity'],$application['projects']['estimated_cost'],$application['projects']['state'],$application['projects']['customer_type'],true,$application['social_consumer']);
				if($application['social_consumer']==1 || $application['common_meter']==1)
				{
					$subsidy_data['state_subsidy_amount']=0;
				}
				$application['projects']['subsidy_details'] = $subsidy_data;
				$application['Profile_Photo_Url'] = $Profile_Photo_Url;
				$arrResult[] = $application;
			}
		}
			$arrApplications 	= $arrResult;
			if (empty($arrApplications)) {
				return false;
			}
		}
		
		$Installation 			= TableRegistry::get('Installation');
		$SubsidyTable 			= TableRegistry::get('Subsidy');
		$view = new View();
		$view->layout 			= false;
		$view->set("pageTitle","Subsidy Summary Sheet");
		$view->set("arrApplications",$arrApplications);
		$view->set("type_modules",$Installation->TYPE_MODULES);
		$view->set("type_inverters",$Installation->TYPE_INVERTERS);
		$view->set("make_inverters",$Installation->MAKE_INVERTERS);
		$view->set("inv_phase",$Installation->INV_PHASE_TYPE);
		$view->set("SubsidyTable",$SubsidyTable);

		$PDFFILENAME = getRandomNumber();
		/* Generate PDF for estimation of project */
		require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
		$dompdf = new Dompdf($options = array());
		$dompdf->set_base_path(SITE_ROOT_DIR_PATH.'/pdf/');
		$dompdf->set_option("isPhpEnabled", true);
		$view->set('dompdf',$dompdf);
		$html = $view->render('/Element/pdf-template/subsidy_summary_sheet');
		$dompdf->loadHtml($html,'UTF-8');
		echo $html;
echo "new";
		exit;
		$dompdf->setPaper('A4', 'landscape');
		$dompdf->render();

		if($isdownload) {
			$output = $dompdf->output();
			$pdfPath = SITE_ROOT_DIR_PATH.'/tmp/subsidy_summary_sheet-'.$PDFFILENAME.'.pdf';
			file_put_contents($pdfPath, $output);
			return $pdfPath;
		}
		$output = $dompdf->output();
		header("Content-type:application/pdf");
		header("Content-Disposition:inline;filename='".$PDFFILENAME.".pdf'");
		echo $output;
		die;
	}
	public function geda_letter($id = null)
	{
		if(!empty($this->Session->read('Members.member_type')))
		{
			$customerId = $this->Session->read("Members.id");
		}
		else
		{
			$customerId = $this->Session->read("Customers.id");
		}

		if(empty($customerId))
		{
			return $this->redirect('/home');
		}
		$this->layout 		= false;
		if(empty($id)) {
			$this->Flash->error('Please Select Valid Installer.');
			return $this->redirect('home');
		} else {
			$encode_id 					= $id;
			$id 						= intval(decode($id));
			$applyOnlinesData 			= $this->ApplyOnlines->viewApplication($id);
			$applyOnlinesOthersData 	= $this->ApplyOnlinesOthers->find('all',array('conditions'=>array('application_id'=>$id)))->first();
			$applyOnlinesData->aid 		= "1".str_pad($id,7, "0", STR_PAD_LEFT);
			$LETTER_APPLICATION_NO 		= $applyOnlinesData->aid;
			$APPLICATION_DATE 			= date("d.m.Y",strtotime($applyOnlinesData->created));
			$Installers_data = $this->Installers->find("all",['conditions'=>['id'=>$applyOnlinesData->installer_id]])->first();
		    $Members = $this->Members->find("all",['conditions'=>['member_type'=>'6003','name'=>'CEI']])->first();
		    $discom_data		= array();
		    $discom_name    	= "";
		    $discom_short_name	= "";
		    if(!empty($applyOnlinesData->area)){
		    	$discom_data                = $this->Members->find("all",['conditions'=>['area'=>$applyOnlinesData->area,'circle'=>'0','division'=>'0','subdivision'=>'0','section'=>'0']])->first();
		    	$discom_name                = $this->BranchMasters->find("all",['conditions'=>['id'=>$discom_data->branch_id]])->first();
		    	$discom_short_name          = $this->DiscomMaster->find("all",['conditions'=>['id'=>$discom_name->discom_id]])->first();
		    }

		}
		$category_name = '';
		if($applyOnlinesData->social_consumer==1)
		{
			$category_name = 'Institutional-social';
		}
		else{
			if($applyOnlinesData->category==3001){
				$category_name = 'residential';
			}
			else{
				$category_name = 'industrial/commercial';
			}
		}
		$applyOnlineGedaDate= $this->ApplyOnlineApprovals->getgedaletterStageData($id);
		$project_data 		= $this->Projects->find("all",['conditions'=>['id'=>$applyOnlinesData->project_id]])->first();
		$this->set("APPLY_ONLINE_MAIN_STATUS",$this->ApplyOnlineApprovals->apply_online_main_status);
		$this->set("pageTitle","Apply-online View");
		$this->set('ApplyOnlines',$applyOnlinesData);
		$this->set('Installers_data',$Installers_data);
		$this->set('Members',$Members);
		$this->set('LETTER_APPLICATION_NO',$LETTER_APPLICATION_NO);
		$this->set('APPLICATION_DATE',$APPLICATION_DATE);
		$this->set('discom_data',$discom_data);
		$this->set('discom_name',$discom_name);
		$this->set('applyOnlineGedaDate',$applyOnlineGedaDate);
		$this->set('project_data',$project_data);
		$this->set('category_name',$category_name);
		//$this->set("APPLY_ONLINE_GUJ_STATUS",$this->ApplyOnlineApprovals->apply_online_guj_status);
		$this->set('discom_short_name',$discom_short_name);
		$this->set('applyOnlinesOthersData',$applyOnlinesOthersData);

		/* Generate PDF for estimation of project */
		require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
		$dompdf = new Dompdf($options = array());
		$dompdf->set_option("isPhpEnabled", true);
		$this->set('dompdf',$dompdf);
		$dompdf->set_base_path(SITE_ROOT_DIR_PATH.'/pdf/');


		$currentdate 	= strtotime('2020-07-14 00:00:00'); //$applyOnlinesData->created
		if($applyOnlinesData->social_consumer==1 && ($applyOnlinesOthersData->renewable_attr == 1 || $applyOnlinesOthersData->renewable_attr === 0))
		{
			if($currentdate >= strtotime(OPEN_NEW_QUATA)) {
				$html = $this->render('/Element/applyonlineindustrialopen');
			} else {
				$html = $this->render('/Element/applyonlineindustrial');
			}
			
		}
		elseif($applyOnlinesData->social_consumer==1)
		{
			$html = $this->render('/Element/applyonlinesocialsector');
		}
		elseif($applyOnlinesData->govt_agency==1)
		{
			if($currentdate >= strtotime(OPEN_NEW_QUATA)) {
				$html = $this->render('/Element/applyonlineindustrialopen');
			} else {
				$html = $this->render('/Element/applyonlinegovernment');
			}
		}
		elseif($applyOnlinesData->disclaimer_subsidy==1)
		{
			if(($applyOnlinesOthersData->renewable_attr == 1 || $applyOnlinesOthersData->renewable_attr === 0) && $applyOnlinesData->category!=3001)
			{
				if($currentdate >= strtotime(OPEN_NEW_QUATA)) {
					$html = $this->render('/Element/applyonlineindustrialopen');
				} else {
					$html = $this->render('/Element/applyonlineindustrial');
				}
			}
			else
			{
				if($currentdate >= strtotime(OPEN_NEW_QUATA)) {
					$html = $this->render('/Element/applyonlinenonsubsidyopen');
				} else {
					$html = $this->render('/Element/applyonlinenonsubsidy');
				}
			}
		}
		else{
			if($applyOnlinesData->category==3001)
			{
				$html = $this->render('/Element/applyonlineresidencial');
			}
			else
			{
				$html = $this->render('/Element/applyonlineindustrial');
			}
		}


		$dompdf->loadHtml($html,'UTF-8');
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();

		// Output the generated PDF to Browser
		if($isdownload){
			if($applyOnlinesData->social_consumer==1)
			{
				$dompdf->stream('applyonlinesocialsector-'.$LETTER_APPLICATION_NO);
			}else{
				if($applyOnlinesData->category==3001)
				{
					$dompdf->stream('applyonlineresidencial-'.$LETTER_APPLICATION_NO);
				}
			else
				{
					$dompdf->stream('applyonlineindustrial-'.$LETTER_APPLICATION_NO);
				}
			}
		}
		$output = $dompdf->output();
		header("Content-type:application/pdf");
		header("Content-Disposition:inline;filename='".$LETTER_APPLICATION_NO.".pdf'");
		echo $output;
		die;
		//$pdf_path = $this->generateGedaLetterPdf($id,false,false);
	}
	public function sendInstallerMail()
	{
		$InstallerPayment 	= TableRegistry::get("InstallerPayment");
		$arrinsPayment 		= $InstallerPayment->find('all',array('conditions'=>array('payment_status'=>'success','id >'=>77),'limit'=>10))->toArray();
		pr($arrinsPayment);
		foreach($arrinsPayment as $arrins) {
			//$InstallerPayment->SuccessPaymentEmailToInstaller($arrins->installer_id);
			echo $arrins->installer_id.'<br/>';
		}
		
		exit;
	}
	public function getConsumerDirect() {

	$hash_hmac_content  = array('get_consumer_details',date("d.m.Y.H.i.s"));
        $content            = json_encode($hash_hmac_content);
        $hash               = hash_hmac('sha512', $content, HMAC_HASH_PRIVATE_KEY);
    $data_request['P_DISCOM_CD'] = 1;
    $data_request['P_CON_NO'] = 18852016848;
    $data_request['apitimestamp'] = date("d.m.Y.H.i.s");
    $data_request['apiaction'] = 'get_consumer_details';
   /* $conn           = curl_init('https://103.233.170.222/api/apicall.php'); 
    $headers        = array('apitoken:'.$hash);
     curl_setopt($conn, CURLOPT_HTTPHEADER,$headers);
                        curl_setopt($conn, CURLOPT_CONNECTTIMEOUT, 300 );
                        curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, false );
                        curl_setopt($conn, CURLOPT_SSL_VERIFYHOST, 0);
                        curl_setopt($conn, CURLOPT_FOLLOWLOCATION, True);
                        curl_setopt($conn, CURLOPT_RETURNTRANSFER, true );
                        //if($this->apidiscom!='5' && $this->apidiscom!='6')
                        //{
                            curl_setopt($conn, CURLOPT_PORT,'8006');
                            curl_setopt($conn, CURLOPT_POST, true);
                            curl_setopt($conn, CURLOPT_POSTFIELDS,$data_request);
                        //}
                        $output         = curl_exec( $conn );
                        $Response       = json_decode($output);
                        pr($Response);*/

                       $P_SRV_CD = 4;
                       $P_CLIENT_CD = 1;
                       $P_DISCOM_CD = 3;
                       $P_DT_TM = date("d.m.Y.H.i.s");
		
		$input_array 		= array("INPUT_DATA"=>array("cnsmr_no"=>'31309077703'));
		$input_array 		= array("INPUT_DATA"=>$this->ApplyOnlines->fetchDataForRegistration(4));
		$salt_key    		= '4p76R6ZKGQ5rLjCh';
		$P_AUTH_KEY 		= $P_SRV_CD.$P_CLIENT_CD.$P_DISCOM_CD.$P_DT_TM.$salt_key;
		$AUTH_KEY 	= hash('sha256',$P_AUTH_KEY);

        $P_IN_DATA 			= array("P_IN_DATA"		=> $input_array,
	    							"P_SRV_CD"		=> $P_SRV_CD,
	    							"P_CLIENT_CD"	=> $P_CLIENT_CD,
	    							"P_DISCOM_CD"	=> $P_DISCOM_CD,
	    							"P_DT_TM"		=> $P_DT_TM,
									"P_AUTH_KEY"	=> $AUTH_KEY);
        echo 'https://epaydg.guvnl.in:8001/guvnl_api_json.php';

		$conn 				= curl_init( 'https://epaydg.guvnl.in:8001/guvnl_api_json.php' );
		curl_setopt( $conn, CURLOPT_HTTPHEADER, array('Content-Type: application/json','X-Forwarded-For: 103.233.170.222'));
        curl_setopt( $conn, CURLOPT_CONNECTTIMEOUT, 300 );
        curl_setopt( $conn, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $conn, CURLOPT_SSL_VERIFYHOST, 0 );
        curl_setopt( $conn, CURLOPT_RETURNTRANSFER, true );
        $post_string   		= json_encode($P_IN_DATA);
         echo $post_string;
    		curl_setopt( $conn, CURLOPT_POST, true );
        	curl_setopt( $conn, CURLOPT_POSTFIELDS, $post_string);
    	
        $output 			= curl_exec( $conn );
		$Response   		= json_decode($output);
pr($Response);
                        exit;



	}
	public function sendpaymentreceipt_installer()
	{
		$InstallerPayment 	= TableRegistry::get("InstallerPayment");
		$sendData 	= $InstallerPayment->SuccessPaymentEmailToInstaller(2713);
		echo 'Sent -->'.$sendData;
		exit;
	}
	public function pendingApplicationpayment() {
		error_reporting(0);
		//'apply_onlines.payment_status !='=>'1'
		echo "\r\n--StartTime::".date("Y-m-d H:i:s")."--\r\n";
		$pendingApplications 	= $this->ApplicationPaymentRequest->find('all',array(
													'join' 		=> ['apply_onlines'=>['table'=>'apply_onlines','type'=>'left','conditions'=>'ApplicationPaymentRequest.application_id=apply_onlines.id']],
													'fields'	=> array('ApplicationPaymentRequest.application_id'),
													'conditions'=> array('apply_onlines.payment_status !='=>'1','apply_onlines.id in'=>array(96503,94862)),
													'limit'		=>'1',
													'page' 		=>'1'))->distinct(['ApplicationPaymentRequest.application_id'])->toArray();
		echo '<pre>';
		/*require_once(ROOT . DS . 'vendor' . DS . 'hdfc' . DS . 'hdfc.php');
					$objHdfc 			= new Hdfc(Configure::read('HDFC_MERCHANT_KEY'),Configure::read('HDFC_SALT'),Configure::read('HDFC_ACCESS_CODE'), Configure::read('PAYU_SANDBOX'));
					$hdfc['reference_no'] 			= '109017083389';
					$response 	= $objHdfc->getData($hdfc);
					print_r($response);*/
		$updateApplication 	= 0;
		if(!empty($pendingApplications)) {
			foreach($pendingApplications as $application) {
				$requestDataAll 		= $this->ApplicationPaymentRequest->find('all',array(
														'conditions'=> array('ApplicationPaymentRequest.application_id'=>$application->application_id),
														'order'		=> array('ApplicationPaymentRequest.id'=>'desc')))->toArray();
				if(!empty($requestDataAll)) {
					foreach($requestDataAll as $requestData) {
						if(!empty($requestData)) {
							$arrOutput 			= json_decode($requestData->request_data,2);
							$order_id 			= $arrOutput['order_id'];
							
							
							
							require_once(ROOT . DS . 'vendor' . DS . 'hdfc' . DS . 'hdfc.php');
							$objHdfc 			= new Hdfc(Configure::read('HDFC_MERCHANT_KEY'),Configure::read('HDFC_SALT'),Configure::read('HDFC_ACCESS_CODE'), Configure::read('PAYU_SANDBOX'));
							$hdfc['order_no'] 			= $order_id;
							$response 	= $objHdfc->getData($hdfc);
							print_r($response);
							//echo '---------->'.$application->application_id.'<br>';
							if(isset($response->Order_Status_Result->order_bank_response) && !empty($response->Order_Status_Result->order_bank_response)) {
								$bankResponse 			= strtolower($response->Order_Status_Result->order_bank_response);
								
								$arrbankRes 			= explode("success",$bankResponse);
								if(count($arrbankRes) > 1) {
									echo '---------->'.$application->application_id.'<br>';
									$updateApplication++;
									$arrpassdata 					= array();
									$arrpassdata['order_id'] 		= $order_id;
									$arrpassdata['order_status'] 	= 'success';
									$arrpassdata['merchant_param1'] = encode($application->application_id);
									$arrpassdata['trans_date'] 		= $response->Order_Status_Result->order_status_date_time;
									$arrpassdata['tracking_id'] 	= $response->Order_Status_Result->reference_no;
									
									$this->Payumoney->savedata_success($arrpassdata);
									$arrpay['application_id'] 	= $application->application_id;
							
									$arrpay['modified'] 		= $this->NOW();
									$arrpay['response_data']	= json_encode($response);
									$this->ApplicationPaymentRequest->updateAll($arrpay,array('id'=>$requestData->id));
								}
							}
						}
					}
						
				}
				
			}
		}
		echo "Total Application".count($pendingApplications);
		echo "Updated Application".$updateApplication;
		echo "\r\n--EndTime::".date("Y-m-d H:i:s")."--\r\n";
		exit;
	}
	public function pendingInstallerspayment() {
		error_reporting(0);
		//'apply_onlines.payment_status !='=>'1'
		echo "\r\n--StartTime::".date("Y-m-d H:i:s")."--\r\n";
		$pendingInstallers 	= $this->InstallerPaymentRequest->find('all',array(
													'join' 		=> ['installers'=>['table'=>'installers','type'=>'left','conditions'=>'InstallerPaymentRequest.installer_id=installers.id']],
													'fields'	=> array('InstallerPaymentRequest.installer_id'),
													'limit' 	=>5,
													'conditions'=> array('response_data IS NULL','installers.payment_status !='=>'1')
													))->distinct(['InstallerPaymentRequest.installer_id'])->toArray();
		echo '<pre>';
		$updateInstaller 	= 0;
		if(!empty($pendingInstallers)) {
			foreach($pendingInstallers as $installer) {
				$requestDataAll 		= $this->InstallerPaymentRequest->find('all',array(
														'conditions'=> array('InstallerPaymentRequest.installer_id'=>$installer->installer_id),
														'order'		=> array('InstallerPaymentRequest.id'=>'desc')))->toArray();

				if(!empty($requestDataAll)) {
					foreach($requestDataAll as $requestData) {
						if(!empty($requestData)) {
							$arrOutput 			= json_decode($requestData->request_data,2);
							$order_id 			= $arrOutput['order_id'];
							
							
							
							require_once(ROOT . DS . 'vendor' . DS . 'hdfc' . DS . 'hdfc.php');
							$objHdfc 			= new Hdfc(Configure::read('HDFC_MERCHANT_KEY'),Configure::read('HDFC_SALT'),Configure::read('HDFC_ACCESS_CODE'), Configure::read('PAYU_SANDBOX'));
							$hdfc['order_no'] 			= $order_id;
							$response 	= $objHdfc->getData($hdfc);
							print_r($response);
							//echo '---------->'.$application->application_id.'<br>';
							if(isset($response->Order_Status_Result->order_bank_response) && !empty($response->Order_Status_Result->order_bank_response)) {
								$bankResponse 			= strtolower($response->Order_Status_Result->order_bank_response);
								
								$arrbankRes 			= explode("success",$bankResponse);
								if(count($arrbankRes) > 1 || $bankResponse=='s') {
									echo '---------->'.$installer->installer_id.'<br>';
									$updateInstaller++;
									$arrpassdata 					= array();
									$arrpassdata['order_id'] 		= $order_id;
									$arrpassdata['order_status'] 	= 'success';
									$arrpassdata['merchant_param1'] = encode($installer->installer_id);
									$arrpassdata['trans_date'] 		= $response->Order_Status_Result->order_status_date_time;
									$arrpassdata['tracking_id'] 	= $response->Order_Status_Result->reference_no;
									
									$this->InstallerPayment->savedata_success($arrpassdata);
									$arrpay['installer_id'] 	= $installer->installer_id;
							
									$arrpay['modified'] 		= $this->NOW();
									$arrpay['response_data']	= json_encode($response);
									$this->InstallerPaymentRequest->updateAll($arrpay,array('id'=>$requestData->id));
								}
							}
						}
					}		
				}
			}
		}
		echo "Total Installers".count($pendingInstallers);
		echo "Updated Installers".$updateInstaller;
		echo "\r\n--EndTime::".date("Y-m-d H:i:s")."--\r\n";
		exit;
	}
	public function sendRegistration() {
		$arrResponse 	= $this->SendRegistrationFailure->fetchApiSendRegistration(70019);
		print_r($arrResponse);
		//exit;
		//$arrResponse 	= $this->SendRegistrationFailure->fetchApiSendRegistration(67730);
		//print_r($arrResponse);
		exit;
	}
	public function uploadApplyOnlines() {
		//$application_id = '12';'application_id'=>$application_id,
		// /
		echo "\r\n--StartTime::".date("Y-m-d H:i:s")."--\r\n";
		$serverBasePath = 'https://geda.ahasolar.in/';
		$arrDocs 		= $this->ApplyonlinDocs->find('all',array('conditions'=>array('doc_type in'=>array('profile','Self_Certificate')),'order'=>array('id'=>'asc'),'limit'=>'5'))->toArray();
		if(!empty($arrDocs)) {
			foreach($arrDocs as $doc) {
				if(!empty($doc->doc_type)) {
					$prefix_file 	= '';
					$customerId 	= 0;
					$access_type 	= $doc->doc_type;
					$filePath		= $serverBasePath.APPLYONLINE_PATH.$doc->application_id."/";
					$file_location 	= $serverBasePath.APPLYONLINE_PATH.$doc->application_id.'/'.$doc->file_name;

					$passFileName 	= $doc->file_name;
					$couchdbId 		= $this->Couchdb->saveData($filePath,$file_location,$prefix_file,$passFileName,$customerId,$access_type);
					$this->ApplyonlinDocs->updateAll(['couchdb_id'=>$couchdbId],['id'=>$doc->id]);
				}
			}
		}
		$arrDocs 		= $this->ApplyonlinDocs->find('all',array('conditions'=>array('doc_type in'=>array('others')),'group'=>['application_id'],'limit'=>'5'))->toArray();
		
		if(!empty($arrDocs)) {
			foreach($arrDocs as $doc) {
				$arrDocsData 		= $this->ApplyonlinDocs->find('all',array('conditions'=>array('doc_type in'=>array('others'),'application_id'=>$doc->application_id),'order'=>array('id'=>'asc')))->toArray();
				if(!empty($arrDocsData)) {
					foreach($arrDocsData as $key => $docdata) {
						if(!empty($docdata->doc_type)) {
							$prefix_file 	= 'doc_'.$key;
							$customerId 	= 0;
							$access_type 	= $docdata->doc_type.($key+1);
							$filePath		= $serverBasePath.APPLYONLINE_PATH.$docdata->application_id."/";
							$file_location 	= $serverBasePath.APPLYONLINE_PATH.$docdata->application_id.'/'.$docdata->file_name;
							echo $file_location.'<br>';
							$passFileName 	= $docdata->file_name;
							$couchdbId 		= $this->Couchdb->saveData($filePath,$file_location,$prefix_file,$passFileName,$customerId,$access_type);
							$this->ApplyonlinDocs->updateAll(['couchdb_id'=>$couchdbId],['id'=>$docdata->id]);
						}
					}
				}
			}
		}

		$arrDocs 		= $this->ApplyOnlines->find('all',array('fields'=>array('attach_pan_card_scan','attach_photo_scan_of_aadhar','attach_latest_receipt','attach_recent_bill','attach_detail_project_report','id'),'order'=>array('id'=>'asc'),'limit'=>'2'))->toArray();
		if(!empty($arrDocs)) {
			foreach($arrDocs as $doc) {
				if(!empty($doc->attach_pan_card_scan)) {
					$arrPrefix 		= explode("pan_",$doc->attach_pan_card_scan);
					$doc_type 		= 'attach_pan_card_scan';
					$prefix_file 	= count($arrPrefix)>1 ? 'pan_' : '';
					$customerId 	= 0;
					$access_type 	= $doc_type;
					$filePath		= $serverBasePath.APPLYONLINE_PATH.$doc->id."/";
					$file_location 	= $serverBasePath.APPLYONLINE_PATH.$doc->id.'/'.$doc->attach_pan_card_scan;
					$passFileName 	= $doc->attach_pan_card_scan;
					$couchdbId 		= $this->Couchdb->saveData($filePath,$file_location,$prefix_file,$passFileName,$customerId,$access_type);
				}
				if(!empty($doc->attach_photo_scan_of_aadhar)) {
					$arrPrefix 		= explode("aadhar",$doc->attach_photo_scan_of_aadhar);
					$doc_type 		= 'attach_photo_scan_of_aadhar';
					$prefix_file 	= count($arrPrefix)>1 ? 'aadhar' : '';
					$customerId 	= 0;
					$access_type 	= $doc_type;
					$filePath		= $serverBasePath.APPLYONLINE_PATH.$doc->id."/";
					$file_location 	= $serverBasePath.APPLYONLINE_PATH.$doc->id.'/'.$doc->attach_photo_scan_of_aadhar;
					$passFileName 	= $doc->attach_photo_scan_of_aadhar;
					$couchdbId 		= $this->Couchdb->saveData($filePath,$file_location,$prefix_file,$passFileName,$customerId,$access_type);
				}
				if(!empty($doc->attach_latest_receipt)) {
					$arrPrefix 		= explode("tax_receipt_",$doc->attach_latest_receipt);
					$doc_type 		= 'attach_latest_receipt';
					$prefix_file 	= count($arrPrefix)>1 ? 'tax_receipt_' : '';
					$customerId 	= 0;
					$access_type 	= $doc_type;
					$filePath		= $serverBasePath.APPLYONLINE_PATH.$doc->id."/";
					$file_location 	= $serverBasePath.APPLYONLINE_PATH.$doc->id.'/'.$doc->attach_latest_receipt;
					$passFileName 	= $doc->attach_latest_receipt;
					$couchdbId 		= $this->Couchdb->saveData($filePath,$file_location,$prefix_file,$passFileName,$customerId,$access_type);
				}
				if(!empty($doc->attach_recent_bill)) {
					$arrPrefix 		= explode("recent",$doc->attach_recent_bill);
					$doc_type 		= 'attach_recent_bill';
					$prefix_file 	= count($arrPrefix)>1 ? 'recent' : '';
					$customerId 	= 0;
					$access_type 	= $doc_type;
					$filePath		= $serverBasePath.APPLYONLINE_PATH.$doc->id."/";
					$file_location 	= $serverBasePath.APPLYONLINE_PATH.$doc->id.'/'.$doc->attach_recent_bill;
					$passFileName 	= $doc->attach_recent_bill;
					$couchdbId 		= $this->Couchdb->saveData($filePath,$file_location,$prefix_file,$passFileName,$customerId,$access_type);
				}
				if(!empty($doc->attach_detail_project_report)) {
					$arrPrefix 		= explode("_",$doc->attach_detail_project_report);
					$doc_type 		= 'attach_detail_project_report';
					$prefix_file 	= count($arrPrefix)>1 ? $arrPrefix[0] : '';
					$customerId 	= 0;
					$access_type 	= $doc_type;
					$filePath		= $serverBasePath.APPLYONLINE_PATH.$doc->id."/";
					$file_location 	= $serverBasePath.APPLYONLINE_PATH.$doc->id.'/'.$doc->attach_detail_project_report;
					$passFileName 	= $doc->attach_detail_project_report;
					$couchdbId 		= $this->Couchdb->saveData($filePath,$file_location,$prefix_file,$passFileName,$customerId,$access_type);
				}
			}
		}
		

		$arrDocs 		= $this->ApplyOnlinesOthers->find('all',array(
								'conditions'=> array('OR'=>array('file_company_incorporation !='=>'','file_board !='=>'','upload_certificate !='=>'')),
								'fields'	=>array('file_company_incorporation','file_board','upload_certificate','application_id'),'order'=>array('id'=>'asc'),'limit'=>'2'))->toArray();

		if(!empty($arrDocs)) {
			foreach($arrDocs as $doc) {
				if(!empty($doc->file_company_incorporation)) {
					$arrPrefix 		= explode("incop_",$doc->file_company_incorporation);
					$doc_type 		= 'file_company_incorporation';
					$prefix_file 	= count($arrPrefix)>1 ? 'incop_' : '';
					$customerId 	= 0;
					$access_type 	= $doc_type;
					$filePath		= $serverBasePath.APPLYONLINE_PATH.$doc->application_id."/";
					$file_location 	= $serverBasePath.APPLYONLINE_PATH.$doc->application_id.'/'.$doc->file_company_incorporation;
					$passFileName 	= $doc->file_company_incorporation;
					$couchdbId 		= $this->Couchdb->saveData($filePath,$file_location,$prefix_file,$passFileName,$customerId,$access_type);
				}
				if(!empty($doc->file_board)) {
					$arrPrefix 		= explode("board_",$doc->file_board);
					$doc_type 		= 'file_board';
					$prefix_file 	= count($arrPrefix)>1 ? 'board_' : '';
					$customerId 	= 0;
					$access_type 	= $doc_type;
					$filePath		= $serverBasePath.APPLYONLINE_PATH.$doc->application_id."/";
					$file_location 	= $serverBasePath.APPLYONLINE_PATH.$doc->application_id.'/'.$doc->file_board;
					$passFileName 	= $doc->file_board;
					$couchdbId 		= $this->Couchdb->saveData($filePath,$file_location,$prefix_file,$passFileName,$customerId,$access_type);
				}
				if(!empty($doc->upload_certificate)) {
					$arrPrefix 		= explode("upcert_",$doc->upload_certificate);
					$doc_type 		= 'upload_certificate';
					$prefix_file 	= count($arrPrefix)>1 ? 'upcert_' : '';
					$customerId 	= 0;
					$access_type 	= $doc_type;
					$filePath		= $serverBasePath.APPLYONLINE_PATH.$doc->application_id."/";
					$file_location 	= $serverBasePath.APPLYONLINE_PATH.$doc->application_id.'/'.$doc->upload_certificate;
					$passFileName 	= $doc->upload_certificate;
					$couchdbId 		= $this->Couchdb->saveData($filePath,$file_location,$prefix_file,$passFileName,$customerId,$access_type);
				}
			}
		}
		
		echo "\r\n--EndTime::".date("Y-m-d H:i:s")."--\r\n";
		exit;
	}
	public function uploadFesibility() {
		echo "\r\n--StartTime::".date("Y-m-d H:i:s")."--\r\n";
		$serverBasePath = 'https://geda.ahasolar.in/';
		$arrDocs 		= $this->FesibilityReport->find('all',array('conditions'=>array('file_name !='=>''),'order'=>array('id'=>'asc'),'limit'=>'2'))->toArray();
		if(!empty($arrDocs)) {
			foreach($arrDocs as $doc) {
				if(!empty($doc->file_name)) {
					$prefix_file 	= '';
					$customerId 	= 0;
					$access_type 	= 'paymentdata';
					$filePath		= $serverBasePath.FEASIBILITY_PATH.$doc->application_id."/paymentdata/";
					$file_location 	= $serverBasePath.FEASIBILITY_PATH.$doc->application_id.'/paymentdata/'.$doc->file_name;
					$passFileName 	= $doc->file_name;
					$couchdbId 		= $this->Couchdb->saveData($filePath,$file_location,$prefix_file,$passFileName,$customerId,$access_type);
				}
			}
		}
		echo "\r\n--EndTime::".date("Y-m-d H:i:s")."--\r\n";
		exit;
	}
	public function uploadWorkOrder() {
		echo "\r\n--StartTime::".date("Y-m-d H:i:s")."--\r\n";
		$serverBasePath = 'https://geda.ahasolar.in/';
		$arrDocs 		= $this->Workorder->find('all',array('conditions'=>array('attached_doc !='=>''),'order'=>array('id'=>'asc'),'limit'=>'2'))->toArray();
		if(!empty($arrDocs)) {
			foreach($arrDocs as $doc) {
				pr($doc);
				if(!empty($doc->attached_doc)) {
					$prefix_file 	= '';
					$customerId 	= 0;
					$access_type 	= 'workorder_data';
					$filePath		= $serverBasePath.WORKORDER_PATH.$doc->project_id."/";
					$file_location 	= $serverBasePath.WORKORDER_PATH.$doc->project_id.'/'.$doc->attached_doc;
					$passFileName 	= $doc->attached_doc;
					$couchdbId 		= $this->Couchdb->saveData($filePath,$file_location,$prefix_file,$passFileName,$customerId,$access_type);
				}
			}
		}
		echo "\r\n--EndTime::".date("Y-m-d H:i:s")."--\r\n";
		exit;
	}
	public function uploadExecution() {
		echo "\r\n--StartTime::".date("Y-m-d H:i:s")."--\r\n";
		$serverBasePath = 'https://geda.ahasolar.in/';
		$arrDocs 		= $this->ProjectInstallationPhotos->find('all',array('order'=>array('id'=>'asc'),'limit'=>'7'))->toArray();
		if(!empty($arrDocs)) {
			foreach($arrDocs as $doc) {
				pr($doc);
				if(!empty($doc->type)) {
					$prefix_file 	= '';
					$customerId 	= 0;
					$access_type 	= $doc->type;
					$filePath		= $serverBasePath.EXECUTION_PATH.$doc->project_id."/".$doc->type."/";
					$file_location 	= $serverBasePath.EXECUTION_PATH.$doc->project_id."/".$doc->type.'/'.$doc->photo;
					$passFileName 	= $doc->photo;
					$couchdbId 		= $this->Couchdb->saveData($filePath,$file_location,$prefix_file,$passFileName,$customerId,$access_type);
					$this->ProjectInstallationPhotos->updateAll(['couchdb_id'=>$couchdbId],['id'=>$doc->id]);
				}
			}
		}
		echo "\r\n--EndTime::".date("Y-m-d H:i:s")."--\r\n";
		exit;
	}
	public function uploadUpdateDetails() {
		echo "\r\n--StartTime::".date("Y-m-d H:i:s")."--\r\n";
		$serverBasePath = 'https://geda.ahasolar.in/';
		$arrDocs 		= $this->UpdateDetails->find('all',array(
								'conditions'=> array('OR'=>array('profile_image !='=>'','electricity_bill !='=>'','aadhar_card !='=>'')),
								'order'		=> array('id'=>'asc'),'limit'=>'3'))->toArray();
		if(!empty($arrDocs)) {
			foreach($arrDocs as $doc) {
				pr($doc);
				if(!empty($doc->profile_image)) {
					$arrPrefix 		= explode("profile_",$doc->profile_image);
					$doc_type 		= 'profile_image';
					$prefix_file 	= count($arrPrefix)>1 ? 'profile_' : '';
					$customerId 	= 0;
					$access_type 	= $doc_type;
					$filePath		= $serverBasePath.UPDATEDETAILS_PATH.$doc->application_id."/";
					$file_location 	= $serverBasePath.UPDATEDETAILS_PATH.$doc->application_id.'/'.$doc->profile_image;
					$passFileName 	= $doc->profile_image;
					$couchdbId 		= $this->Couchdb->saveData($filePath,$file_location,$prefix_file,$passFileName,$customerId,$access_type);
				}
				if(!empty($doc->electricity_bill)) {
					$arrPrefix 		= explode("ele_",$doc->electricity_bill);
					$doc_type 		= 'electricity_bill';
					$prefix_file 	= count($arrPrefix)>1 ? 'ele_' : '';
					$customerId 	= 0;
					$access_type 	= $doc_type;
					$filePath		= $serverBasePath.UPDATEDETAILS_PATH.$doc->application_id."/";
					$file_location 	= $serverBasePath.UPDATEDETAILS_PATH.$doc->application_id.'/'.$doc->electricity_bill;
					$passFileName 	= $doc->electricity_bill;
					$couchdbId 		= $this->Couchdb->saveData($filePath,$file_location,$prefix_file,$passFileName,$customerId,$access_type);
				}
				if(!empty($doc->aadhar_card)) {
					$arrPrefix 		= explode("aadhar_",$doc->aadhar_card);
					$doc_type 		= 'aadhar_card_update';
					$prefix_file 	= count($arrPrefix)>1 ? 'aadhar_' : '';
					$customerId 	= 0;
					$access_type 	= $doc_type;
					$filePath		= $serverBasePath.UPDATEDETAILS_PATH.$doc->application_id."/";
					$file_location 	= $serverBasePath.UPDATEDETAILS_PATH.$doc->application_id.'/'.$doc->aadhar_card;
					$passFileName 	= $doc->aadhar_card;
					$couchdbId 		= $this->Couchdb->saveData($filePath,$file_location,$prefix_file,$passFileName,$customerId,$access_type);
				}
			}
		}
		echo "\r\n--EndTime::".date("Y-m-d H:i:s")."--\r\n";
		exit;
	}
	public function customerEntry()
	{
		$InstallerPayment 			= TableRegistry::get("InstallerPayment");
		$Installers 				= TableRegistry::get("Installers");
		$Customers 					= TableRegistry::get("Customers");
		$InstallerPlans 			= TableRegistry::get("InstallerPlans");
		$InstallerSubscription 		= TableRegistry::get("InstallerSubscription");
		$InstallerCredendtials 		= TableRegistry::get("InstallerCredendtials");
		$InstallerActivationCodes 	= TableRegistry::get("InstallerActivationCodes");
		$Parameters 				= TableRegistry::get("Parameters");
		$installer_id 				= 3878;
		$InstallerDetails 				= $Installers->find('all',array('conditions'=>array('id'=>$installer_id)))->first();
		$arrName 						= explode(" ",$InstallerDetails->contact_person);
	    $RandomPassword                 = strtolower($arrName[0]).'@2020';
		$arrEmail                       = explode(",",$InstallerDetails->email);
		$CustomerEmail                  = trim($arrEmail[0]);
		$customersEntity                = $Customers->newEntity();
		$customersEntity->mobile        = $InstallerDetails->mobile;
		$customersEntity->email         = $CustomerEmail;
		$customersEntity->name          = $InstallerDetails->contact_person;
		$customersEntity->password      = Security::hash(Configure::read('Security.salt') . $RandomPassword);
		$customersEntity->status        = $Customers->STATUS_INACTIVE;
		$customersEntity->customer_type = "installer";
		$customersEntity->state         = 4;
		$customersEntity->created       = $this->NOW();
		$customercnt                    = $Customers->find('all', array('conditions'=>array('email'=>$CustomerEmail)))->count();
		$IsInstallerCreated             = $Customers->find('all', array('conditions'=>array('installer_id'=>$InstallerDetails->id)))->count();

		if ($Customers->save($customersEntity)) 
		{
			echo 'Customer Entry Done'.'<br>';
			$insplanData                                    = $InstallerPlans->get($InstallerPlans->DEFAULT_PLAN_ID);
			$InstallerSubscriptionEntity                    = $InstallerSubscription->newEntity();
			$InstallerSubscriptionEntity->payment_status    = '';
			$InstallerSubscriptionEntity->installer_id      = $installer_id;
			$InstallerSubscriptionEntity->coupen_code       = '';
			$InstallerSubscriptionEntity->transaction_id    = '';
			$InstallerSubscriptionEntity->created           = $this->NOW();
			$InstallerSubscriptionEntity->modified          = $this->NOW();
			$InstallerSubscriptionEntity->payment_gateway   = '';
			$InstallerSubscriptionEntity->comment           = '100% Discount';
			$InstallerSubscriptionEntity->payment_data      = '';
			$InstallerSubscriptionEntity->amount            = '0';
			$InstallerSubscriptionEntity->coupen_id         = '0';
			$InstallerSubscriptionEntity->is_flat           = '0';
			$InstallerSubscriptionEntity->plan_name         = $insplanData->plan_name;
			$InstallerSubscriptionEntity->plan_price        = $insplanData->plan_price;
			$InstallerSubscriptionEntity->plan_id           = $InstallerPlans->DEFAULT_PLAN_ID;
			$InstallerSubscriptionEntity->user_limit        = $insplanData->user_limit;
			$InstallerSubscriptionEntity->start_date        = date('Y-m-d');
			$InstallerSubscriptionEntity->expire_date       = date('Y-m-d',strtotime("+ 30 days"));
			$InstallerSubscriptionEntity->status            = '1';
			$InstallerSubscriptionEntity->created_by        = $customersEntity->id;
			$InstallerSubscriptionEntity->modified_by       = $customersEntity->id;
			$InstallerSubscription->save($InstallerSubscriptionEntity);
			echo 'Subscription Entry Done'.'<br>';
			$insCodeArr = array();
			for ($i=0; $i < $insplanData->user_limit; $i++) {
				$activation_codes = $Installers->generateInstallerActivationCodes();
				$insCodeArr[]                                               = $activation_codes;
				$insCodedata['InstallerActivationCodes']['installer_id']    = $installer_id;
				$insCodedata['InstallerActivationCodes']['activation_code'] = $activation_codes;
				$insCodedata['InstallerActivationCodes']['start_date']      = date('Y-m-d');
				$insCodedata['InstallerActivationCodes']['expire_date']     = date('Y-m-d',strtotime("+ 30 days"));
				$insCodeEntity = $InstallerActivationCodes->newEntity($insCodedata);
				$InstallerActivationCodes->save($insCodeEntity);
				echo 'Activation Code Entry Done'.'<br>';
			}
			$Customers->updateAll(['user_role'=>$Parameters->admin_role,'default_admin'=>1,'installer_id' => $installer_id,'modified' => $this->NOW()], ['id' => $customersEntity->id]);

			$PasswordInfo['InstallerCredendtials']['installer_id']  = $installer_id;
			$PasswordInfo['InstallerCredendtials']['password']      = $RandomPassword;
			$InstallerCredendtialsEnt 								= $InstallerCredendtials->newEntity($PasswordInfo);
			$InstallerCredendtials->save($InstallerCredendtialsEnt);
			echo 'Password Entry Done'.'<br>';
		}
		$InstallerPaymentDetails  		= $InstallerPayment->find('all')->where(['installer_id' => $installer_id])->first();
		$InstallerPaymentRequest 		= TableRegistry::get('InstallerPaymentRequest');
		$arrPayment 					= $InstallerPaymentRequest->find('all',array('conditions'=>array('installer_id'=>$installer_id,'response_data IS NULL'),'order'=>array('id'=>'desc')))->first();
		pr($arrPayment);
		if(!empty($arrPayment)) {
			$arrpay['installer_id'] 	= $installer_id;
			
			$arrpay['modified'] 		= $this->NOW();
			$arrpay['response_data']	= isset($InstallerPaymentDetails->payment_data) ? $InstallerPaymentDetails->payment_data : '';
			$InstallerPaymentRequest->updateAll($arrpay,array('id'=>$arrPayment->id));
			echo 'Payment Request Entry Done'.'<br>';
		}
		$this->EInvoice->getAccessToken($installer_id,'installer');
		$InstallerPayment->SuccessPaymentEmailToInstaller($installer_id);
		echo 'Mail Done';
		exit;
	}
	public function testemail(){
		echo '<pre>';
		$ApplyOnlines           = TableRegistry::get("ApplyOnlines");
        $applyOnlinesData       = $ApplyOnlines->viewApplication(66612);
		$subject            = "[REG: GEDA Application No. ".$applyOnlinesData->geda_application_no."] Technical Feasibility Report";
        $CUSTOMER_NAME      = trim($applyOnlinesData->customer_name_prefixed." ".$applyOnlinesData->name_of_consumer_applicant." ".$applyOnlinesData->last_name." ".$applyOnlinesData->third_name);
        $EmailVars          = array("CUSTOMER_NAME"=>$CUSTOMER_NAME,'TEXT_FESIBILITY'=>'done');
        $template_applied   = 'fesibility_approval';
		$email          = new Email('default');
                    $email->profile('default');
                    $email->viewVars($EmailVars);
                    $message_send   = $email->template($template_applied, 'default')
                            ->emailFormat('html')
                            ->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
                            ->to('jayshree.tailor@ahasolar.in')
                            ->subject(Configure::read('EMAIL_ENV').$subject)
                            ->send();
                            echo 'Mail Sent';
                            exit;
		$subject   	= "GEDA | USRTP: Subsidy Document/REG. No: 1111";
        $email     	= new Email('default');
        $EmailTo   	= 'jayshree.tailor@ahasolar.in';
        echo "\r\n--EmailTo:: ".$EmailTo."--\r\n";
        print_r(array('noreply-srtgeda@gujarat.gov.in' => PRODUCT_NAME));
        
        $MESSAGE 	= "Dear installer,<br /><br />With reference to the GEDA Registration no. <b>1111</b>, the documents required for the submission at GEDA are attached herein. Thank you.<br /><br />Regards,<br /><br />Support Team";
     //  echo mail($EmailTo,'SubjectData','MSg',array('noreply-srtgeda@gujarat.gov.in' => PRODUCT_NAME));
        $email->profile('default');
        $email->viewVars(array( 'MESSAGE_CONTENT' => $MESSAGE));
        $email->template('installer_notification_email', 'default')
                ->emailFormat('html')
                ->from(array('noreply-srtgeda@gujarat.gov.in' => PRODUCT_NAME))
                ->to($EmailTo)
                ->subject($subject);
        $output = $email->send();
        print_r($output);
        echo "Mail Sent ";


        exit;
	}
	public function checkfesibility() {
		echo "\r\n--StartTime::".date("Y-m-d H:i:s")."--\r\n";

		/** KP */
		$ConnectionManager = ConnectionManager::get('default');
		$ConnectionManager->execute("SET @@SESSION.sql_mode='NO_ENGINE_SUBSTITUTION'");
		/** KP */
        $query_sent 		          = 1;
        $GUJARAT_STATE 		          = 4;
        $CurrentHour                  = date("H");
        if ($CurrentHour > 8 && $CurrentHour < 20) {
            $LIMIT_QUERY_SENT         = 10; 
        } else {
            $LIMIT_QUERY_SENT	      = 1500;
        }
        
        $LastProcessedApplicationID   = 0;
        $application_status = array($this->ApplyOnlineApprovals->DOCUMENT_VERIFIED,
                                    $this->ApplyOnlineApprovals->FIELD_REPORT_SUBMITTED,
                                    $this->ApplyOnlineApprovals->FEASIBILITY_APPROVAL,
                                    $this->ApplyOnlineApprovals->SUBSIDY_AVAILIBILITY,
                                    $this->ApplyOnlineApprovals->WORK_STARTS,
                                    $this->ApplyOnlineApprovals->APPROVED_FROM_CEI,
                                    $this->ApplyOnlineApprovals->METER_INSTALLATION,
                                    $this->ApplyOnlineApprovals->DRAWING_APPLIED,
                                    $this->ApplyOnlineApprovals->WORK_EXECUTED,
                                    $this->ApplyOnlineApprovals->CEI_APP_NUMBER_APPLIED,
                                    $this->ApplyOnlineApprovals->APPROVED_FROM_DISCOM,
                                    $this->ApplyOnlineApprovals->CEI_INSPECTION_APPROVED);
        $LastRowID          = 66117;//$this->CronApiProcess->GetLastRowID("check_fe_report");
        if (!empty($LastRowID)) 
        {
            $arrConditions      = [ 'application_status IN '=>$application_status,
                                    'apply_state'=>$GUJARAT_STATE,
                                    'ApplyOnlines.id > '=>$LastRowID,
                                    'ApplyOnlines.discom NOT IN '=>array($this->ApplyOnlines->torent_ahmedabad,$this->ApplyOnlines->torent_surat),
                                    ['OR'=>['payment_approve'=>'0','payment_approve IS NULL']]];
        } else {
            $arrConditions      = [ 'application_status IN '=>$application_status,
                                    'apply_state'=>$GUJARAT_STATE,
                                    'ApplyOnlines.discom NOT IN '=>array($this->ApplyOnlines->torent_ahmedabad,$this->ApplyOnlines->torent_surat),
                                    ['OR'=>['payment_approve'=>'0','payment_approve IS NULL']]];
        }

        $arrApplications 	= $this->ApplyOnlines->find('all',
                                                                [   'fields'        => ["ApplyOnlines.id"],
                                                                    'join'          => ['table'           =>'fesibility_report',
                                                                        'type'=>'left',
                                                                        'conditions'=>'fesibility_report.application_id = ApplyOnlines.id'
                                                                    ],
                                                                    'conditions'    => $arrConditions,
                                                                    'limit'         => $LIMIT_QUERY_SENT,
                                                                    'order'         => 'ApplyOnlines.id ASC'
                                                                ]
                                                        )->toArray();
        $FetchedRowCount = count($arrApplications);
        echo "\r\n--FetchedRowCount::".$FetchedRowCount."--\r\n";
        echo "\r\n--arrConditions::".json_encode($arrConditions)."--\r\n";
      // die;
        if (!empty($FetchedRowCount))
        {
			foreach($arrApplications as $rowid=>$arrApplication)
			{
                $allStages  = $this->ApplyOnlineApprovals->Approvalstage($arrApplication->id);
                if(in_array($this->ApplyOnlineApprovals->APPROVED_FROM_GEDA,$allStages))
                {
                    echo "\r\n--rowid::".$rowid."--\r\n";
                    $LastProcessedApplicationID = $arrApplication->id;
                    $this->FesibilityReport->fetchApiFeasibility($arrApplication->id,true);
                    $this->CronApiProcess->saveAPILog($LastProcessedApplicationID,"check_fe_report");
                }
			}
            echo "\r\n--LastProcessedApplicationID::".$LastProcessedApplicationID."--\r\n";
		} else {
            $this->CronApiProcess->saveAPILog($LastProcessedApplicationID,"check_fe_report");
        }
		echo "\r\n--EndTime::".date("Y-m-d H:i:s")."--\r\n";
		exit;
	}
	public function fetchDeletedApplication()
	{
		$arrApplications 	= array(49296);
		$arrApplications 	= array(86893);
		$arrApplications 	= array(48634);
		$arrApplications 	= array(107671,107672);
		$arrApplications 	= array(89822);
		$arrApplications 	= array(60617,64271,74927,82380,88759,95251,98854);
		$arrApplications 	= array(117000);
		$arrApplications 	= array(112927);
		//$application_id 	= 186527;
		foreach($arrApplications as $application_id) {
			$arrDeletedData 	= $this->ApplicationDeleteLog->find('all',array('conditions'=>array('application_id'=>$application_id)))->first();
			$arrApplication 	= $this->ApplyOnlines->find('all',array('conditions'=>array('id'=>$application_id)))->first();
			
			
			echo '<pre>';
			
			if(!empty($arrDeletedData) && empty($arrApplication)) {
				if(!empty($arrDeletedData->application_data)) {
					$arrData 		= json_decode($arrDeletedData->application_data,2);
					unset($arrData['id']);
					unset($arrData['delete_type']);
					print_r($arrData);
					//exit;
					$applyEntity 	= $this->ApplyOnlines->newEntity($arrData);
					$applyEntity->id= $application_id;

					$this->ApplyOnlines->save($applyEntity);

				/*	$arrData['created']  					= !empty($arrData['created']) ? date('Y-m-d H:i:s',strtotime($arrData['created'])) : '';
					$arrData['modified']  					= !empty($arrData['modified']) ? date('Y-m-d H:i:s',strtotime($arrData['modified'])) : '';
					$this->ApplyOnlines->updateAll($arrData,['id'=>$application_id]);*/
					echo 'Records updated';
				}
			}
		}
		
		exit;
	}
	public function exeCron()
	{
		//echo shell_exec('php /var/www/html/srtgeda.gujarat.gov.in/html/bin/cake.php CheckFesibilityStatus');
		//echo shell_exec('php /var/www/html/srtgeda.gujarat.gov.in/html/bin/cake.php PendingDeveloperPayment');
		echo shell_exec('php /var/www/html/srtgeda.gujarat.gov.in/html/bin/cake.php PendingReApplicationPayment');
		exit;
	}
	public function getConsumerDetailsDirect() {

		$hash_hmac_content  = array('send_consumer_registration_details',date("d.m.Y.H.i.s"));
        $content            = json_encode($hash_hmac_content);
        $hash               = hash_hmac('sha512', $content, HMAC_HASH_PRIVATE_KEY);
		$data_request['P_DISCOM_CD'] 	= 1;
		$data_request['P_CON_NO'] 		= 18852016848;
		$data_request['apitimestamp'] 	= date("d.m.Y.H.i.s");
		$data_request['apiaction'] 		= 'send_consumer_registration_details';
	   /* $conn           = curl_init('https://103.233.170.222/api/apicall.php'); 
	    $headers        = array('apitoken:'.$hash);
	     curl_setopt($conn, CURLOPT_HTTPHEADER,$headers);
	                        curl_setopt($conn, CURLOPT_CONNECTTIMEOUT, 300 );
	                        curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, false );
	                        curl_setopt($conn, CURLOPT_SSL_VERIFYHOST, 0);
	                        curl_setopt($conn, CURLOPT_FOLLOWLOCATION, True);
	                        curl_setopt($conn, CURLOPT_RETURNTRANSFER, true );
	                        //if($this->apidiscom!='5' && $this->apidiscom!='6')
	                        //{
	                            curl_setopt($conn, CURLOPT_PORT,'8006');
	                            curl_setopt($conn, CURLOPT_POST, true);
	                            curl_setopt($conn, CURLOPT_POSTFIELDS,$data_request);
	                        //}
	                        $output         = curl_exec( $conn );
	                        $Response       = json_decode($output);
	                        pr($Response);*/

		$P_SRV_CD = 4;
		$P_CLIENT_CD = 1;
		$P_DISCOM_CD = 2;
		$P_DT_TM = date("d.m.Y.H.i.s");
		/*{"P_DISCOM_CD":"2","P_CON_NO":"14106115913","apitimestamp":"28.03.2023.15.29.32","apiaction":"send_consumer_registration_details","P_CON_DETAILS":{"CNSMR_NO":"14106115913","CNSMR_NAME":"SANJAYBHAI DAHYABHAI SHAH","APPLIED_LOAD":"10","CNSMR_MOBILE_NO":"9425653789","CNSMR_EMAIL_ID":"KTSOLARENERGY14418@GMAIL.COM","VENDOR_CODE":3202,"VENDOR_NAME":"PAHALSOLAREPCLLP","APPLICATION_NO":103998,"SOLAR_TYPE":"NEW"}}*/
		$input_array 		= array("INPUT_DATA"=>array("CNSMR_NO"=>'14106115913',
										"CNSMR_NAME"=>'SANJAYBHAI DAHYABHAI SHAH',
										"APPLIED_LOAD"=>'10',
										"CNSMR_MOBILE_NO"=>'9425653789',
										"CNSMR_EMAIL_ID"=>'KTSOLARENERGY14418@GMAIL.COM',
										"VENDOR_CODE"=>3202,
										"VENDOR_NAME"=>'PAHALSOLAREPCLLP',
										"CNSMR_APPL_NO"=>103998,
										"SOLAR_TYPE"=>'NEW'));
		//$input_array 		= array("INPUT_DATA"=>$this->ApplyOnlines->fetchDataForRegistration(4));
		$salt_key    		= '4p76R6ZKGQ5rLjCh';

		$P_AUTH_KEY 		= $P_SRV_CD.$P_CLIENT_CD.$P_DISCOM_CD.$P_DT_TM.$salt_key;
		$AUTH_KEY 			= hash('sha256',$P_AUTH_KEY);

        $P_IN_DATA 			= array("P_IN_DATA"		=> $input_array,
	    							"P_SRV_CD"		=> $P_SRV_CD,
	    							"P_CLIENT_CD"	=> $P_CLIENT_CD,
	    							"P_DISCOM_CD"	=> $P_DISCOM_CD,
	    							"P_DT_TM"		=> $P_DT_TM,
									"P_AUTH_KEY"	=> $AUTH_KEY);
      
        echo 'https://epaydg.guvnl.in:8001/guvnl_api_json.php';

		$conn 				= curl_init( 'https://epaydg.guvnl.in:8001/guvnl_api_json.php' );
		curl_setopt( $conn, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt( $conn, CURLOPT_CONNECTTIMEOUT, 300 );
        curl_setopt( $conn, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $conn, CURLOPT_SSL_VERIFYHOST, 0 );
        curl_setopt( $conn, CURLOPT_RETURNTRANSFER, true );
        $post_string   		= json_encode($P_IN_DATA);
         echo $post_string;
    		curl_setopt( $conn, CURLOPT_POST, true );
        	curl_setopt( $conn, CURLOPT_POSTFIELDS, $post_string);
    	
        $output 			= curl_exec( $conn );
		$Response   		= json_decode($output);
		pr($Response);
        exit;
	}
	public function getExec()
	{
		
		//$response 	= $this->EInvoice->getAccessToken('106238','application');
		//$response 	= $this->EInvoice->getAccessToken('106645','application');
		//$response 	= $this->EInvoice->getAccessToken('434','reapplication');
		//$response 	= $this->EInvoice->getAccessToken('9','reapplication');
		//$response 	= $this->EInvoice->getAccessToken('309','reapplication');
		//,,,,
		//7,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,26,27,28,29,30,31,32,33,34,35,36,37,38,39
		//40,41,42,43,44,46,47,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,88,89,90,91,92,94,95,97,98,99,100,102,103,104,105,106,107,109,110,111,113,114,115,116,117,118,119,120,121,122
		//123,124,127,128,129,131,132,134,135,136,138,139,140,141,142,143,144,145,146,147,148,149,150,151,152,153,155,156,157,158,159,160,162,163,164,165,166,167,168,169,170,171,172,173,174,175,176,177,178,179,180,181,182,183,184,185,186,187,188,189,190,191,192,193,194,195,196,197,198,199,200,201,203,204,205,206,207,208,209,210,211,212,213,214
		//217,218,219,220,221,223,224,225,229,230,231,235,239,240,241,242,243,244,245,246,247,249,250,253,254,255,256,259,260,261,262,264,265,268,269,270,272,273,274,275,277,278,279,280,281,282,283,284,285,287,288,290,291,294,295,296,298,300,302,303,304,305,306,307,308,310,311,313,314,315,316,317,318,319,321,322,323,324,325,326,327,328,330,331,332,333,336,337,338,339,340,342,343,344,350,351,352,353,355,356,360,361,364,367,368,369,370,371,372,373,374,375,376,378,380,381,382,383,384,387,391,395,396,400
		//$arrApplication = array(401,402,403,405,406,407,408,410,414,416,417,419,423,424,434,475,494);
		$arrApplication = array(15,16,17,20,21,22,23,25,27,29,30,32,34,42,47,52,53,55,56,60,70,71,72,73,75,77,78,80,81,83,84,85,86,87,88,89,90,91,93,94,95,96,97,98,100,103,104,105,106,107,108,109,110,111,113,114,115,116,118,119,120,121,122,123,124,125,126,127,128,129,130,131,132,134,135,136,137,139,140,142,144,145,146,147,148,149,150,151,152,153,154,155,156,157,158,160,162,163,165,166,167,168,169,170,171,172,173);
		foreach($arrApplication as $applicationID) {
			$response 	= $this->EInvoice->getAccessToken($applicationID,'developer');
			print_r($response);
		}
		
		//$response 	= $this->EInvoice->getAccessToken('4','developer');
		
		exit;
	}
	public function setCreated() {
		$this->ApplyOnlinesOthers->updateAll(['created_by_type'=>'installer'],['created_by_type'=>'']);
		echo 'updated';
		exit;
	}
	public function sendEmailDisclaimer() {
		echo '<pre>';
		$ApplyOnlines           = TableRegistry::get("ApplyOnlines");
		$arrApplications = array(116636,117117,114022,116788,117291,117114,117115,117076,117093,117099,117082,117073,112506,117153,116716,117060,116744,115594,117339,117383,117380,117376,117374,114063,117113,115291,117164,116188,116510,116378,116234,116037,116909,117047,116834,116989,116943,115708,116022,116476,116309,116898,116703,117304,117175,116436,116279,114939,117201,116458,117386,117323,116715,117260,117292);
		foreach($arrApplications as $application_id) {
			$applyOnlinesData       = $ApplyOnlines->viewApplication($application_id);
			$subject            = "[REG: GEDA Portal | Request to Submit Undertaking of Application No. ".$applyOnlinesData->geda_application_no."]";

			echo $application_id." --- ".$applyOnlinesData->installer_email;
			$CUSTOMER_NAME      = trim($applyOnlinesData->customer_name_prefixed." ".$applyOnlinesData->name_of_consumer_applicant." ".$applyOnlinesData->last_name." ".$applyOnlinesData->third_name);
			$EmailVars          = array("CUSTOMER_NAME"=>$CUSTOMER_NAME,'APPLICATION_NO'=>$applyOnlinesData->geda_application_no);
			$template_applied   = 'application_disclaimer_upload';
			$email          = new Email('default');
			$email->profile('default');
			$email->viewVars($EmailVars);
			$message_send   = $email->template($template_applied, 'default')
				->emailFormat('html')
				->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
				->to($applyOnlinesData->installer_email)
				->subject(Configure::read('EMAIL_ENV').$subject)
				->send();
			//$output = $email->send();
			echo 'Mail Sent<br>';
			
		}
        exit;
	}
	function validate_signature()
	{
		$requestURIId 		= 'pfxdb1c930d182a771db2ffcab712bb1cb6';
$private_key = <<<EOD
-----BEGIN PRIVATE KEY-----
MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCTMoc/CzB7uxOx
I8bopKNUvc/SvGNEshra0/YrbdKCy5ErWhlNYh/XOsZeaj2rPPxNhVMgQNqpdPYC
Iw5v9BbTM54N4x3qyEKW7T3xO9a9zv7js4BGlmca1K+WXPEn29sjt9zRl9KxTWpD
+f4TzWsb6RtacvZ2jWM0Q+LTnK5WiWgwXX0mT1QvAxfP1ErJ+azXhrrAovohPqfU
UmtvuR0kxaKJOzaH1sElyc2JNfaoWPmDyxFzr8eIbOLdQ6lihqqGvFUgyHqpRzHa
gFDu4lLeiJuILzNe6KUy1YUwJQpt+MRvb9zwQc6UDM1nm/PTICBkH84dD4ObF2cT
dOXvp4rNAgMBAAECggEABtTN/QmGZv+gItTvusVFRT4H4pZWnw4K/APhRVOz9NkA
tDvat8IpiyIRqbkRgpxycyCQYotP+pRQhHnfBigUVRnsqGVVcpt3p6x79vGZfjaY
krW2atA5GcAsI/TNRlXIPkieqWV75DmdCGmWNpIOef2gBNsYABmAtKC/6qU3XBGz
Yv0OlyKbBCliExchcpNGfZMxZ2FYpeLMKwZFcvubf4ldaZR4oKuh7F2muxwfVoA4
up7c64H4LTa70ts+GXXEqfwrFOaSMN2chE0VzsXVIM8httSq6uJMe5yROyNKdtWg
/Rqxzm/RcO5iuXPy8MyPhTE263ukcTQPx5RRddeh6wKBgQDPW/BQfyShP77b+Oq5
8paxNiqDXK/JrBkc7Un4T5Oa0V660M6oh1x3qi0GYNqVr2clDFhati0kjFawsMIS
Uoau5VnhlDRH4ry/XynfGQ/yzAaLudW4pHG5Cnc6cs6XDd0V0hDGbQHWtHfaoynw
ZDcewK7rj+FrgW4ptWTHBbwGfwKBgQC1udV0AxrcyUXQJcLt1Kyq1zaCrGX+UL+G
dNzk/B4PzT+Es0Hn1yD50Sn2m2LfzgNxqlzTlDwWaDWWfubu6voJ4DBSt6kbTDfC
1BXV91nwEtKmzvLbRBAJf4Vm1bPpaXFsT+DiUR3j3LwS5DReW2IDuKyCXy0ssUqN
EEhXoVEAswKBgCZ1W9XiLu4FP6XWvdotBwvpCuuANk5GMAYwcGawg6TULiih76JM
MLc1BdLIBeJ7PLsfVgfFAAxmRvHQZr41Niub+BahgSzP/cfUo5RwNogGlTQ3DE+J
mFoEeeaKQoy7koSoiFn0/8FNiWkwl+ew/pQiko64CcwBnmf377AF/UCLAoGBALP/
l+/LS4Y5To83d/a+2zB07ydLv9LBBJQXmNyu5M/eCvZT4AnVynHnvdroWm03z618
g2mGwGWpXrrsg61Ozc+OYg7sn/HL8sdl7yL6V/k1i7Vx8pdAuWnPB8GuFwAxUwln
rWY91o9miltj8oMrnM/20dhokYRdL2y+Hgm+XU+FAoGBAIWnhZ9kRovulmmBDkqB
EWmJ4dqjxDsc+Fcnlr3TSZ0CksBHv6B7OsgANJ+IF7DVEfsJ5wvLPcCAbb4DgszE
K3PlwLXZcOZHL/P7ho1q+V+5QgFuFjxkzKUensLDahJ259r2ihYB61CNtz+MrZQO
VCrVIg5vYA7Rg739XuCYPv3c
-----END PRIVATE KEY-----
EOD;

/*$this->certificateValue = <<<EOD
-----BEGIN CERTIFICATE-----
MIIG2jCCBcKgAwIBAgIQGiNejjo2gWMWMyXYjJJFlTANBgkqhkiG9w0BAQsFADCB
ujELMAkGA1UEBhMCVVMxFjAUBgNVBAoTDUVudHJ1c3QsIEluYy4xKDAmBgNVBAsT
H1NlZSB3d3cuZW50cnVzdC5uZXQvbGVnYWwtdGVybXMxOTA3BgNVBAsTMChjKSAy
MDEyIEVudHJ1c3QsIEluYy4gLSBmb3IgYXV0aG9yaXplZCB1c2Ugb25seTEuMCwG
A1UEAxMlRW50cnVzdCBDZXJ0aWZpY2F0aW9uIEF1dGhvcml0eSAtIEwxSzAeFw0y
MjA3MjEwNTUwNDBaFw0yMzA4MTkwNTUwNDBaMHYxCzAJBgNVBAYTAklOMRAwDgYD
VQQIEwdHdWphcmF0MRQwEgYDVQQHEwtHYW5kaGluYWdhcjEkMCIGA1UEChMbR3Vq
YXJhdCBJbmZvcm1hdGljcyBMaW1pdGVkMRkwFwYDVQQDDBAqLmd1amFyYXQuZ292
LmluMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA0xJhkSbK8zKnWlNQ
VdexfSyJLFJRXB4J9ALjxV1MvV41AcvtVmC5msHeu+GoA4ugy8W7segGAvzU04jT
ReX2yqUiItP/Kjs0jP1i++cgtVmQpiJrdPirUM7puigOUtqKFsrxUenOqrYY9R/t
iWcV46JiJNbuQ52T73XAG4ADkWdxH7W+UpxhqD1tIktPspjiHsuqm0tCUjXqmKbm
iuaqYrwL3C9hEmfchQRBAMzrJdl0IYS2P7VsUaaVI4rDJY/ig2ODm4ekekj6+Php
po+9nn+tUbAzjcdnbJkAE54FNAYLHYQeM2bY9ZY7WjwAW+URrVwFZZPOPJH5fQe1
DbynZQIDAQABo4IDHTCCAxkwDAYDVR0TAQH/BAIwADAdBgNVHQ4EFgQUz8+zzSOK
Gc8+7qog9W3SrEFH3LAwHwYDVR0jBBgwFoAUgqJwdN28Uz/Pe9T3zX+nYMYKTL8w
aAYIKwYBBQUHAQEEXDBaMCMGCCsGAQUFBzABhhdodHRwOi8vb2NzcC5lbnRydXN0
Lm5ldDAzBggrBgEFBQcwAoYnaHR0cDovL2FpYS5lbnRydXN0Lm5ldC9sMWstY2hh
aW4yNTYuY2VyMDMGA1UdHwQsMCowKKAmoCSGImh0dHA6Ly9jcmwuZW50cnVzdC5u
ZXQvbGV2ZWwxay5jcmwwKwYDVR0RBCQwIoIQKi5ndWphcmF0Lmdvdi5pboIOZ3Vq
YXJhdC5nb3YuaW4wDgYDVR0PAQH/BAQDAgWgMB0GA1UdJQQWMBQGCCsGAQUFBwMB
BggrBgEFBQcDAjBMBgNVHSAERTBDMDcGCmCGSAGG+mwKAQUwKTAnBggrBgEFBQcC
ARYbaHR0cHM6Ly93d3cuZW50cnVzdC5uZXQvcnBhMAgGBmeBDAECAjCCAX4GCisG
AQQB1nkCBAIEggFuBIIBagFoAHYAVYHUwhaQNgFK6gubVzxT8MDkOHhwJQgXL6Oq
HQcT0wwAAAGCH07mUQAABAMARzBFAiAsPEZ/HDhHMBYF/8fpEzCG3b+AyKU5oZQh
s0qBzIMkeQIhAIszsTssJxCL3MR9x5+8NqP4EV8Ze3eE7x9F620THFT8AHYAtz77
JN+cTbp18jnFulj0bF38Qs96nzXEnh0JgSXttJkAAAGCH07mRwAABAMARzBFAiEA
ubKggORmBNiesxLlWDP8iSdHOy/KQVBNV6Kl+6MmbqkCIF+ElHUSt1BQnzHAo+1B
iegSJoL1CeroN3wuPPusQeX/AHYArfe++nz/EMiLnT2cHj4YarRnKV3PsQwkyoWG
NOvcgooAAAGCH07mLQAABAMARzBFAiACz5hih7lZmvRRXy1pvO4/7OaY5nYKMjOd
DcdfNnFcWAIhAK1YWKg5bf54vUjibgeR14LpG7cG0yAy9Du4FGlqg8aOMA0GCSqG
SIb3DQEBCwUAA4IBAQAJwwNZYMtyAbMxncDjt5TDTbZvzJoNHr3+QZaWQAFMXlGp
f99p+tt2oqobWgKI8kh8YR1hsfjI7k6KUrzbzR2tVL1OM8AxACp7n1wHiehH3aib
myihxAHgndQqdEtF3Rw2ie4NZAoomUhxPkLWvT6IHxqM+BkVhKJ5EYVb9FCPhUmI
DOPf9oo11qMXRmyhzMiFUIO5jDCSA6mOE9liR2M6XzPMEqiyFWVFCwdsWAkEmckl
yst/UheTxUNzvyvpMCSISEBmM/+vh/No1xyQvBN2gBQNKucYtpHM99r+8NyKeicg
p7iYQ/euEfzAVpFOHohlEdC9rkVfomTeG+zQSQcP
-----END CERTIFICATE-----
EOD;*/
$certificateValue = <<<EOD
-----BEGIN CERTIFICATE-----
MIIG1jCCBb6gAwIBAgIQIlm0xMM8sLZp5PxWd/BUBzANBgkqhkiG9w0BAQsFADCB
ujELMAkGA1UEBhMCVVMxFjAUBgNVBAoTDUVudHJ1c3QsIEluYy4xKDAmBgNVBAsT
H1NlZSB3d3cuZW50cnVzdC5uZXQvbGVnYWwtdGVybXMxOTA3BgNVBAsTMChjKSAy
MDEyIEVudHJ1c3QsIEluYy4gLSBmb3IgYXV0aG9yaXplZCB1c2Ugb25seTEuMCwG
A1UEAxMlRW50cnVzdCBDZXJ0aWZpY2F0aW9uIEF1dGhvcml0eSAtIEwxSzAeFw0y
MzA4MDgxMjM3MzlaFw0yNDA5MDgxMjM3MzhaMHQxCzAJBgNVBAYTAklOMRQwEgYD
VQQHEwtHYW5kaGluYWdhcjE0MDIGA1UECgwrRGVwYXJ0bWVudCBvZiBTY2llbmNl
ICYgVGVjaG5vbG9neSwgR3VqYXJhdDEZMBcGA1UEAwwQKi5ndWphcmF0Lmdvdi5p
bjCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAJMyhz8LMHu7E7Ejxuik
o1S9z9K8Y0SyGtrT9itt0oLLkStaGU1iH9c6xl5qPas8/E2FUyBA2ql09gIjDm/0
FtMzng3jHerIQpbtPfE71r3O/uOzgEaWZxrUr5Zc8Sfb2yO33NGX0rFNakP5/hPN
axvpG1py9naNYzRD4tOcrlaJaDBdfSZPVC8DF8/USsn5rNeGusCi+iE+p9RSa2+5
HSTFook7NofWwSXJzYk19qhY+YPLEXOvx4hs4t1DqWKGqoa8VSDIeqlHMdqAUO7i
Ut6Im4gvM17opTLVhTAlCm34xG9v3PBBzpQMzWeb89MgIGQfzh0Pg5sXZxN05e+n
is0CAwEAAaOCAxswggMXMAwGA1UdEwEB/wQCMAAwHQYDVR0OBBYEFGdP9nNYDJtn
KFCzK9ux4b1ROnUwMB8GA1UdIwQYMBaAFIKicHTdvFM/z3vU981/p2DGCky/MGgG
CCsGAQUFBwEBBFwwWjAjBggrBgEFBQcwAYYXaHR0cDovL29jc3AuZW50cnVzdC5u
ZXQwMwYIKwYBBQUHMAKGJ2h0dHA6Ly9haWEuZW50cnVzdC5uZXQvbDFrLWNoYWlu
MjU2LmNlcjAzBgNVHR8ELDAqMCigJqAkhiJodHRwOi8vY3JsLmVudHJ1c3QubmV0
L2xldmVsMWsuY3JsMCsGA1UdEQQkMCKCECouZ3VqYXJhdC5nb3YuaW6CDmd1amFy
YXQuZ292LmluMA4GA1UdDwEB/wQEAwIFoDAdBgNVHSUEFjAUBggrBgEFBQcDAQYI
KwYBBQUHAwIwTAYDVR0gBEUwQzA3BgpghkgBhvpsCgEFMCkwJwYIKwYBBQUHAgEW
G2h0dHBzOi8vd3d3LmVudHJ1c3QubmV0L3JwYTAIBgZngQwBAgIwggF8BgorBgEE
AdZ5AgQCBIIBbASCAWgBZgB1AD8XS0/XIkdYlB1lHIS+DRLtkDd/H4Vq68G/KIXs
+GRuAAABidUnJjUAAAQDAEYwRAIgEj/PkZzpFCJp9Z/5gMkVEedQ/T5fT7EOLX9N
Suy7DdkCIDPVfm/nizJuDy7RYh/ylky+PYAH3vWp8ecL4oFI6CzMAHUAdv+IPwq2
+5VRwmHM9Ye6NLSkzbsp3GhCCp/mZ0xaOnQAAAGJ1ScmJgAABAMARjBEAiBEVLN8
UDfD03xucbwTE5Nt+cWTDyOOIxQF4C0MHx+wSQIgfkHsBfzCFrbrwUsRYGoJaG4n
c6ttG85Z1ZKWIXGFFQIAdgDatr9rP7W2Ip+bwrtca+hwkXFsu1GEhTS9pD0wSNf7
qwAAAYnVJyYhAAAEAwBHMEUCIGbzUf2uYWTEO8rfuhp6YmoDczVgrtt/MNWgUNRX
cwNrAiEA8x1MQpRYCYrXNi0X+ahx7K3WO8N/hA9CmbN2lDCKQ9EwDQYJKoZIhvcN
AQELBQADggEBAE/RgTkMpXiCC+7PLcd0pp4bcp6CethQQrTmLL5A6ftg4glePXvC
5VJuPo55y9H1FWw8t2V0YORGYJuF5GRZOMLmBAnDTS1H0djaSy9L5bRJc/L1XiAg
MvgL69s4fGKNS9HkerkijC3PK2GSxtLQ8VHA2k4U0xfqjVbJCLM3GibWNb3kp9NC
XGenMeduJTYbIohdyXuqaKw0Wia1edbxista6TN16lYJhhDI/Px3+3yz0/8/36JU
j8xfx1rW+UMT6tiryFXuhgCgR3cY1w3oFiWhwq8XkMVc47kh3/KdazyVRA/5uHCh
M1vL3WuDmeyBZZIrF1/P3FhzfuZ9uLiWx5Y=
-----END CERTIFICATE-----
EOD;

	//$xmlseclibs_srcdir = 'D:/xampp/htdocs/ahasolar/geda/config/xmlsignature/src';
	
//	require $xmlseclibs_srcdir . '/XMLSecurityKey.php';
//	require $xmlseclibs_srcdir . '/XMLSecurityDSig.php';
//	require $xmlseclibs_srcdir . '/XMLSecEnc.php';
//	require $xmlseclibs_srcdir . '/Utils/XPath.php';

//	$doc 		= new DOMDocument();

	$xmlData 	='<faxml xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="CO_NEF.xsd" Id="pfxdb1c930d182a771db2ffcab712bb1cb6">
 			   <header>
 			      	<extsysname>COAPI</extsysname>
 					<datpost>2022-12-30</datpost>
 					<batchnumext>100001</batchnumext>
 					<idtxn>CO_NEF</idtxn>
 					<codcurr>INR</codcurr>
 					<iduser>APIUSER@CBXMGRT3</iduser>
 					<idcust>10246013</idcust>
 					<groupid>CBXMGRT3</groupid>
 					<reqdatetime>2022-12-30T19:28:59</reqdatetime>
 			   </header>
 			   <summary>
 			      <orgsumpmt>1</orgsumpmt>
 			      <orgcountpmt>1</orgcountpmt>
 			   </summary>
 			   <paymentlist>
 			      <payment>
 			         <stanext>1</stanext>
 			         <paymentrefno>NEFT00001</paymentrefno>
 			         <CustId>10246013</CustId>
 			         <Amount>1</Amount>
 			         <RemitterName>HDFC Bank Ltd</RemitterName>
 			         <RemitterAccount>00040350004239</RemitterAccount>
 			         <RemitterAccountType>10</RemitterAccountType>
 			         <Remitter_Address_1>HDFC Bank Ltd. Retail Assets</Remitter_Address_1>
 			         <Remitter_Address_2>Chandivali</Remitter_Address_2>
 			         <Remitter_Address_3>Mumbai - 400072</Remitter_Address_3>
 			         <Remitter_Address_4/>
 			         <BeneIFSCCODE>CITI0000001</BeneIFSCCODE>
 			         <BeneAccountType>11</BeneAccountType>
 			         <BeneAccountNumber>041131210001</BeneAccountNumber>
 			         <BeneName>SRINIVAS MOTORS</BeneName>
 			         <BeneAddress_1/>
 			         <BeneAddress_2/>
 			         <BeneAddress_3/>
 			         <BeneAddress_4/>
 			         <RemitInformation_1>API BTG WBO</RemitInformation_1>
 			         <RemitInformation_2/>
 			         <RemitInformation_3/>
 			         <RemitInformation_4/>
 			         <RemitInformation_5/>
 			         <RemitInformation_6/>
 			         <ContactDetailsID/>
 			         <ContactDetailsDETAIL/>
 			         <codcurr>INR</codcurr>
 			         <refstan>2</refstan>
 			         <forcedebit>N</forcedebit>
 			         <txndesc>BTG WBO API</txndesc>
 			         <beneid/>
 			         <emailid>jayshree.tailor@ahasolar.in</emailid>
 			         <advice1/>
 			         <advice2/>
 			         <advice3/>
 			         <advice4/>
 			         <advice5/>
 			         <advice6/>
 			         <advice7/>
 			         <advice8/>
 			         <advice9/>
 			         <advice10/>
 			         <addnlfield1/>
 			         <addnlfield2/>
 			         <addnlfield3/>
 			         <addnlfield4/>
 			         <addnlfield5/>
 			      </payment>
 			   </paymentlist>
 			</faxml>';
 //echo $xmlData;
 $SignedPayload =  generateXMLSignature($xmlData,$private_key,$certificateValue,$requestURIId);

				$SignedPayload 		= str_replace(array('</faxml>','<Signature '), array('','</faxml><Signature '), $SignedPayload);
				
				$payloadWithSigned 	= '593b2tjNtP3YJjPw<?xml version="1.0" encoding="UTF-8"?><request>'.$SignedPayload.'</request>';
				echo $payloadWithSigned;
exit;
	
	}
	public function decryptResponse()
	{
		
		$arrResponse['ResponseSignatureEncryptedValue'] = "LB2vxpRhLA98TiM2TBdK1yR8B3uz+KEWYXs67bAmQgkuJvaJ3XSfVaLGWxoPcFQGIW4cBV\/GS9W5GhSmLGMk3GlMoLw5W7fNFm3yxvrTu1Mtsc2fFoe3SUiwDCL6EVaw3nRBf\/h1ZmwBSiZJ1ScBm9Lnnf0XmwuKZtZEa57vNMa4g3Jy6bIGrPooUBTVCxpu4CT30ZhGptFrrbY+5Ysr8HQSON5T5W8Gjnytk2HfU7W9FExwSr5rGLte797SRbDnRQUvsiQury\/30fqJ28H9uO4KgEsd28W4zb\/gQgeJGZ0t1FF311K79i4k+XdoGNfguzi9G\/Uzl+T6wlv1A6K3oskmf8xTvVGzS3C7SewqRf+qUkvZ7fjn1Lp48ZUqG2w+yVxNsqts1M6wmzKH39Byjs3Jx4GH6XHtbUU8XDSgUZLqJbvd5WWwNSEgXbjFDxPMaGrxDcQTRLPaqfJPq+1iwQTIQ3zvbyvwgt0bZZXyy9YLFHCfmmjKVZ+WIcNVVAVzlGWPOmBOW\/VsgOB3hH3SLPBBOP0I0eLywA7oIY2QIjamvtHlX1ltGKxWOjwIiSqzMJ67SsgpEJtJ9VxrOzfx51FjQD6AvAAsdBHin77iVQhC2g9aSlUTDVqF3iCjb8+F1UhmCUxTPSXsV84uVXRiRPxnTK2fRsvhjhDmN9pgx55BtB40XZJLkXa9ZRg8lo0dbaMFlKpMnk313AvCFNG2uLpkfOOWVxGGXzbAJoKxIo6iZhWwfJ7HP+0OEU1mtHGALaevgFGQppdCMJs7YoD8hRxGaCM6BmETCakqUAQy45Hn7+XIhQcYOqLgYYhPqGWvQW6TV4Vo6aRK+R33C0wGvOB8ZjCQPv\/4rnds4Kb4Cpx0QQVBGnXDYx6vOckwklKXCoqvM5PhMLoZk70FOR0cwOpm\/BjXxnIC\/Cn+3fPjZ35RPHmDMgpKAJXFWkrMNPQlhvUrWnCXFWmqdlRP1w7KqLuTLmnAyPIyMPl3Wu1lo42jbC9jziA9kWZLuohrdGRK8qLbV1S7iMUjEbfi\/2m6qeaMX8DoG17UDiVRFveXBEi5Oe+GbfG05\/bYCM1cgDQ0Lcut2KAbt5o4m2w\/y8WGDiyQQd+7uhqQlfKNRrc1eJH9+YqzefEKOewf\/WN2B1WFTWG3zwZtHY\/hFmdlpvEDk8B1cp0w6ywNDUh1NRu8aNZ\/BdDUR3TQdUc0w\/AddId6Q8e2FSQsTh78cREiX6vO959cwo+5zJTmL2zUOnFwcATwijPzARSOBrb332OGfB5ciKsIhymKkaCTdAiGeCUWHmE7Vvd8RWhQcNpYZhLkC2K+GJH\/r6xNMguX4rdvggagFYhkfsqvv1LwRwzA339Tgzm6e0BD\/4A\/BpXRpAIakv8qXrpWOf6Jm\/5pJl4V8ryfqA14Sy3RVHVcP19sPcSjvb5KyalPiucv\/KTy9zo2EJAIG\/5Q9jH7gdgHGokz5Lpuh037FcnMjD+aEKSfIpD06fBsa+kRsbekt++FqtdwP7HizxcJm5bZK7OHUwkScRd5lxJIWyF+ucbhrjbGaqVl2Lj\/SY6wcrbeJcoI3yjm766gr5a0ceCOS13IAfKJMcitQPI3HINebwRaIpPHqGFoMfOiEkukC+RpqpNFK0xIOQtTgDN6ou+cDfS66smMO+E6kk7HTsOyCoYeGG0eGprbCQiwKIvIXrG90dyIBMHdnhyL2W1tUMTo3x96rUtwuPxrHQaAOddcWN\/qnw++sy12zmEG4gmoDwGqfBOzNiiIKdKV+LWQCk2va\/xQYykV6jtT8v16so4mvtxso5g\/JTLisJxdglOhf\/e8ccp6OChM+qoRrqM7UwV6oaXwq05ubl5+RejI5O1GfRFmfqwPO3RR3AEelqKkVDtjIP2hhCJz1HGIw4+hRdq0so2loMcvj32KrgwsLjijBcVIojvNahKVkB53JfDeJLCuudvGr1vKFSECjuKov+UXZOrDRsHrP4CcLuo9YeLz+m9NdC9adTctIY94eMfXNSjhK5DbL9BIo9z8xYxRmal6SYCFqSTupBVWOvusx\/sBkyDVOb6tkoK\/oe63axS4tBJq\/mjV7oaghqNiO\/t1q+DDmfWqX7LPBIabVbmU3YY6X+DOYtCudY0bD3QJKq9tx49VhDQT0YYDt98Mthog0aRH\/XRpj45P0JMp7DX7sRsoUJFUNy3PHbOWnZGUFCIRhVMl+pUwl1wORb8GdboZGd+1WFcIpHJ\/d7AobrTf0Zm7fMMr7sSsE9yPE91U4Z19QyOO+oY+nCvNQd2rrMFJTbGSBVtANrvrsVDfXJMbPGw3J1XUhwuYWrNQDneMRsXgxoNLLpEuyUsrCBgZzy\/M\/iW3wAYwXkWLAARoeBM9I0LT2Vbqkp5e\/R7ErCXvzhPwW3sGj7WlNJA8DOQdpX4S2GL1Lf+ZVGFZ8NrC+fbmp+ukvjEBt3k25n1zImms3Clewsen\/MlA\/DdyNbBVe6EQAaMAAd8SMr2WGKF+7FtGTAmPBD7pHNRwKNf54oGrYmBBxVtta1wM05BSKttaXzCGlk5ZibimSE5UPbl4wUeB2LFKbexQlij7LrG0y6gSHYMv6RGeqPnYx8EqqtcreC\/FgdkKwjEhEobSHBJskdeZVUaNPPIMc7hysM8Dvgt4yhTDCRNxwHSokd3fi8mWT3v5aJj6QR6xCzfPWUTpOeHVM21i0LoBdBawQHqHr08JjGnbtagDGueZ14TCRdIFqYm3fhVXXN6ugIkFDOFknYd3uqVp0F1JSz9dbXxb10mkrGJR3udQglRQQaeejJ1ixdkrdHK5Ghh5CcztaSvptOg3+UvDW3QZSYgx3W6OBHAysV\/SGDT6mdmwNjjHIAAhyvnvyryekR9H57lJmzEHJGDPFLEFcAnVMWl\/zW\/vHyPP0RPb+PZYl4Zu1dOX6eQRUh0Bje1EvSQSCHAuPsrW69VsgsX626tm01tqVberwTrmwwvhpFelQwt7ME2SIniK56jMa3R\/D4GnX1upX43dShO4G+71XmWHYM0v4jncPin\/xi+67qjwLDuedidLS1++Kml818kcH7qbKhDZKq6yrmfBkpaGBuEjpQkK7RKsXIAeGrh3lLSh5yUp9n00W8M6xTK8dXuWrsX0Kygw+RbuowdNpbiXFsaas9GhwVGXA4KJL9mAfuPTsy7CuNp16GvRVrIK\/kQS8Af4xNmwKrCcQRpJ1zQRxEGipjNjzVQQZ\/y5AjPFJjM1ynvW5V20D6Fh1Qxl2ElG4FBk02K2a4rpRCuqCct+FjEvxfnNG3kAT9jRXUGl9lwwtJ4VnMqUt\/tf8lFOT5hswidx3TuxLAhuhqjpWQsWTX\/\/Gsi7JkYqAqKJQNTCA0CA5mb\/irOxKOpBlg7MfxCpE1BcRrm93nj4jvmvPdvQGKqu5yNMiRxavUBYEOXK4QXe9Vn8KCPuuzreLWP2ssTiKoCbO0kZyhfaaWbrA66DabHn9QA56lH3JwyUBd\/aU3BujPWd\/0Sob5u5FUTLA1xMuRg14K\/GH7wDRN2mOK8k8rKq4nnOola+h0roToFw+5bGXXZoJlTFNxGTBnOp7ydzN+ZknhgFfEvYt\/0MHwuLREThi\/x78aTDFvy4qant0KcnJvVm3ZGvOhGG5Cj8Pu+DQOfriPVJyyc+d\/Tpypc9ZsPJJZOdRbYI5Q459hWDO1r4kdwm14sVAYHw2v9feCMQGnE26QxXknWM2j1F6A7I8+bUJJ9I8vcnUUH\/qeV34VRAsdJBGtfILPWxlIUWGh5SFWLXWxkEGv0bs8bXKgOR32OTwslDP3ct+dvz00ssTeX6CcEjdenNfxRSl+9MwnL6SfEgDuVAhVALfnUgr4QgehEMgJ+XPTG\/i\/F3EbsDY4u+mz+NFwci6P3bUmMHouLn+qFMyaudhCbNj79t3n+11tkuWFP44rjf+45O+slBcysVs6TyvmfgedGE7zxCM68sU+mQLPnyx8kJQHsP3ARoIrVnyHK\/7WGyajBZv1zMZgkNdTkPbFx6sSbicxckpMiyZUta2dcBXo+eb0\/dpFmaVBzrrDidtlsGdozY6\/tYt88xN96k\/YRPuC44\/x3mBiztgcRZWoQWoWlmmXRLH6CLq6zHJ2PC8AQH\/ihxV5UswicXG4G58lT912mVuAN8f6TdIlEbpyanpJZABKotKcnN+NlTuU5eXt+otXaJTS1xpq9Aq+9E01Ny1J2c04XxgpMrlOVD3cPJUJO\/n2U4z\/5n7iKHHwYYumw6wg9wJz6cyxsOVp5RTmgFYDUmnin+szHqgZQqmUc+6w+5utF5qB8fOajQ7\/WDkg+L\/v9FtRkAoWX3Nq+a7wUL\/IwV+\/C\/E903WFs1i78CXK4fv9pMUjoWCtHfwYoDnzASzVKBvr+pHo5\/KSYSK02iAE3fHIkZJTxKJeCY2Ba5kdPOYCV5JdYMNysepbaqKuaJVO2alCMO1Rr6Jqq4l8dPa4v3Y\/Go5GYB7NzcUCqq6r67+r9aMePOZiOWMZX4cs5\/tXWXUa+xwOX6u3aPZCULEfPuWSgJJMOxeu+yDfpbKN\/GxfUvQYJEJJRJ02Wyz7m8hB77xuMPui957Vml9dbbw86zEh\/t37vH7f4T79jXrrSwU1Sqf7pWtUihiCqtQSWFfPM\/nZ8bXgnugelJbtbXoo88zPws+9nZdyG9ZFl0LshpQ1APBAJnSS3JFSKoJkEOi2eZYz1zNaRHY92CDuWvax9OXkugDtIJf\/EYi0XSElaKN9Q69pZCCZxoaQjV9V9n0pMYaGTUOVt7DxYH9jGa8NXrWaLPbyADV\/AuBM6+Zy9E7J+5SiM8f4o14Iph1ywXrwBmz2O+554z1wFFdkhrG5Dmtz6Rz9fgIdynRnMufJI0pacMOlu6Pk+BMzv6HkwC0mYcMhCzLZgvak4YIsEd75dTKbes8yrGNlXVpNZXu9wKJPjAX8jYBlnlBOT0TDz62+H8Cb\/U+1yzp6R+cQzZFcxryoZPYnrWDWl\/2fmIhqylEsmfiXNdhhJAHEAHvjJ69p+AewW1A0pnojMoEhg1O8tt\/XyjzL9Q1nIROg5SsMGHuP\/j5YxR1xaLv\/J0GdSzh8OUSM+tTNAf3AIdfJNw\/Z+NfS5XqDExvATergYu1bMNyzPo9ldnwCuGEyZuP4va0A0vCvqpzWR9GNf9bipYLRFcGWjWmmQctwUumHNqCxCTbC4RrFcKcGfHBm0qsWTwaRmyVdo\/L1nUPvWd7\/lEh8fNu6YBR2d2txdii7Cgxu0edB0Vg6\/L51xQ5jkLxv8uWFdn5f3+0Re7GTFMphjp6VjTwr88Xhq2lWwQLHa+vRehoThdY3qRScLH1tzU3N3j4+piB4W4fQwfsUGUAg6OX2EtsQPOsXkr2+awQUawPZB2epyNsw1tuFm3YY\/Pxsin9S2dUSBrCJzUCG3T\/CoYNlXIE3hvOJcGu885OlMJ7rn526RMuKiUK4McvzgRVKONdpuVBWqTZ6bSIjxOUqmoZJKVE\/YZ\/Byc\/DaryLlFRpt1cWmbfECpCPjmGojK7NWmYeBZ8Rl3ZGFon5MWWjUghIh8M1dQpFuFcZa4e4IPBTnupc2wZFeZIpW0v3ixRpk\/j+nnV9RupnwsDZZ+M6whqkP\/qSShJWwUSFRETufmNdLb9WZNmfZDOLMIlLv0fjr0PBs0KBgLl9fmggkLcKuYiA781Xf4m3OJV+yN\/EuCv0TddmkAaJXi1Rk8ZdyM6KhIynUT2YPq4qsUVyLZpeX264szt4i1GJWwb2P++afTTKNZJpiy8HvgSMVPQ1IcwNLuEUKDRi5xtltqOsTmkY2hSBXjmJC9hvrl9LRCtbEQE57RH6Bwz2xCofuziuOdjHsCi3c9B6wT7djlPWXJjS1oCH2rXcJ0dWXdJLQIHD+Icw1BJwIRyiNQ0cK9jODXaDd5CkZmU4NqCUJMapmevyC7GqQWs325HWn7zQKXmH0u9Zc2qFpaAyI57hAj14OevZzjgNYQIdhh0kQ2\/TFkIDYoB\/pnqg9C939\/o1Dms\/VJAHBCnDOKlJfms70vKTbTw5TzCB7lHErptrRsctnpUflHgq1IApvXhmr0NqZ\/1kldIqCLNf+IVOf8xStWWrDJF2w\/DlN+155nB4GKe6erNuoQusfXY0kJiVM4vKY4hwKjH+d+elrKbA2IqLktxHv32lEjWgYuMXyYO4wCXInletxCtVd1GdLXKJoUratoC6eTjkohSJP6lvrlf\/XTQPmNUY4zujnUtkNOWo9jAFoBmsqV7RYMY586aCO2hInUhW5D2mZBgsFPCCSQDZNEKrVXQhtqCOPOwtvHsbkLZSBHLa6KOTX6LWf\/FzJm\/mBCmZLdzCXGEGRC0aK\/S8dp0hBorZm0hN2bOdoI9yO5lBdiMyluWbikEvm7iLNmuBpvTtuxZJJm3SR1oYuD4q+46KexCvMfN8plWAucLZRTSeRq8X+PrP2tCvZsiNSpAJ7Bf\/zMsoAyCgIag2NHwQnZ89F+0x2VDoCLVHPbU9DijRYIQVoFSaPy09xoiMZ98BDLtJMOsI0whKZYvoai8pxdrwH0hpVSJlhHzLhWBQlPbNO773MNlE7YCXsVslpmDPdJkTA7tRXHdvmFMyseuzUU6VC4KLVoffMENhQpWskyJ3PRzccKeXhWH9Qd85d2OafMqrG\/q9Ev5swXzjUMmfgLRPJb+1tmrYelRtWh8BlTmK2wJ06+vCTJLhNAalB9uSmsNZeANNW8B3zzE0fxXKS85MZw87\/3mX3UkQZM\/3BppGSlY\/gydYS\/74xzCYX8H\/kmawRJ2Wse3Yml+LPQn6rPicGvkg5iQyxRKwbdOdN2ydl2hYtrPksazOF721lUSd9q+3dZzYhyO70shjXCMgRL+GOmBRGFb2aEZlq3tF6DaFyRdJFw+1pC+f3W4b2j5XJ84f1BMMYtL9xhHYoIYUWj1TTzZwRhW9sJHNh3ODQ9+Vk62hUWEpM3VJaT+++\/XFFZu38XtfrH94dE6uqnwTAICCkCy\/ubNWD2RGPPXIPAGNs2BG\/0xUfMQoy+vC1QDH6yETYtnFZ63RuYRaU74RVcnA4Nf3YgdOU8lDXoouPoxBJGwrmrUIdOo\/cio2xjXqFrmZ9paz+XAljH7C7eLpkAXTfQPoSR87+\/6Zi0diojLB1NSqA43YVp3+8HLZ7ugCu+CLujzwj9UP5YRj7dOPEYtpozmwuoh5FmYa1S17+tPBuAfMibUFBlbmrwInTIo4n8oflvfQsfAHEo9AeXYE203b1E3TYwhb557qsw+6nwoz43JxXPt1Wcs6D1DnuOFterLVJGGrX32lBhPV2gWzGqFd+nxBenpg8D3F1AAKX6TPbIHKNwedeVv0Zpi4oB8+GofdRTPh8fjiYcTx+YPPUA7v0RqXZiA+MkcQIvoc+Qkt+zeo5XTcIK5PC7UJccxXyYmheozL79MwRuU9wduwqDu7XGvHeEgZ+8ZvSojNzRH6cXvsDnCMU=";
				
		$arrResponse['GWSymmetricKeyEncryptedValue']= "euxVyYvQHwSb8Z1oyYdGNAwgSOsur+a5MWukP4FbRug4x2AQayQozKh3m62di7SdNfMwikpo8pPnAvMaz\/cMmzb\/iYyAAWZE2vLZcERvrWw+98x4ESMLTdlxwGqSpIAX5u12pMNWXFPojbxxeRMIJUA24ZV9uMhg36A6QPZ5xSv9oXVs7FrCcyRtEPkEQthGb1d05MVKMh0G2kExk3ooHHH262D1WsnNUmF9w3LCFvgs7BMzAO2jTowa5uJ\/TyxdFUVCzH+jRvVXm1V\/qNS8B+QmnZi6XHUKVZ8rZN2YPsr7Mtg8N1FDfRNX+CD40UWmTWbgL39Hu+efftINH2cnvg==";
		$arrResponse['Scope']						= "GUJEDACX";
		$arrResponse['TransactionId']				= "ec4d673ef9121b1b9144a41e5a13f06156b";
		$arrResponse['Status']						= "SUCCESS";
		$arrResponse['log_id']						= "491";

		if(isset($arrResponse['Status']) && $arrResponse['Status'] == 'SUCCESS')
		{
			if(isset($arrResponse['GWSymmetricKeyEncryptedValue']) && !empty($arrResponse['GWSymmetricKeyEncryptedValue'])) {
				$GWSymmetricKeyDecryptedValue 	= dcryptRSA($arrResponse['GWSymmetricKeyEncryptedValue']);
				
				
				if(isset($arrResponse['ResponseSignatureEncryptedValue']) && !empty($arrResponse['ResponseSignatureEncryptedValue'])) {
					//echo $arrResponse['ResponseSignatureEncryptedValue'].'<br>';
					
					$ResponseSignatureDecryptedValue 	= '<?xml version="1.0" encoding="UTF-8"?><response><faxml xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" Id="W1YZ1jpLAmWWhKi2CwGeUj52NG2rqnXR" xsi:noNamespaceSchemaLocation="CO_NEF.xsd"><header><batchnum>20233261119350108</batchnum><batchnumext>100170</batchnumext><codcurr>INR</codcurr><codstatus>0</codstatus><datpost>2023-11-22</datpost><extsysname>COAPI</extsysname><groupid>GUJEDACX</groupid><idcust>67645125</idcust><idtxn>CO_NEF</idtxn><iduser>APIUSER</iduser><reqdatetime>2023-11-22T19:35:27</reqdatetime><txtstatus>ACCEPTED</txtstatus></header><summary><orgcountpmt>1</orgcountpmt><orgsumpmt>2500</orgsumpmt></summary><paymentlist><payment><stanext>1</stanext><paymentrefno>NEFT00170</paymentrefno><CustId>67645125</CustId><Amount>2500</Amount><RemitterName>HDFC Bank Ltd</RemitterName><RemitterAccount>50100147616733</RemitterAccount><RemitterAccountType>10</RemitterAccountType><Remitter_Address_1>HDFC Bank Ltd. Retail Assets</Remitter_Address_1><Remitter_Address_2>Chandivali</Remitter_Address_2><Remitter_Address_3>Mumbai - 400072</Remitter_Address_3><Remitter_Address_4/><BeneIFSCCODE>SBIN0004867</BeneIFSCCODE><BeneAccountType>11</BeneAccountType><BeneAccountNumber>40093086436</BeneAccountNumber><BeneName>Shree Radhakrishna Solar</BeneName><BeneAddress_1/><BeneAddress_2/><BeneAddress_3/><BeneAddress_4/><RemitInformation_1>API BTG WBO</RemitInformation_1><RemitInformation_2/><RemitInformation_3/><RemitInformation_4/><RemitInformation_5/><RemitInformation_6/><ContactDetailsID/><ContactDetailsDETAIL/><codcurr>INR</codcurr><refstan>2</refstan><forcedebit>N</forcedebit><txndesc>BTG WBO API</txndesc><beneid/><emailid>shreeradhakrishnasolar@gmail.com</emailid><advice1/><advice2/><advice3/><advice4/><advice5/><advice6/><advice7/><advice8/><advice9/><advice10/><addnlfield1/><addnlfield2/><addnlfield3/><addnlfield4/><addnlfield5/></payment></paymentlist></faxml><Signature xmlns="http://www.w3.org/2000/09/xmldsig#"><SignedInfo><CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315"/><SignatureMethod Algorithm="http://www.w3.org/2001/04/xmldsig-more#rsa-sha256"/><Reference URI="#W1YZ1jpLAmWWhKi2CwGeUj52NG2rqnXR"><DigestMethod Algorithm="http://www.w3.org/2001/04/xmlenc#sha256"/><DigestValue>MmwNhM8R7Kk5mcM1dnCMVnSIAVMdJCT/IW3qSavbS9I=</DigestValue></Reference></SignedInfo><SignatureValue>EswjbQAMg1O/RQWvWTfu9KVH6dxEJrpLQ5VjBjzcBQ/YDYdNeYDruG6qpt+TGF8e7BNhASbCJV4s&#xD;bYmx+5gPocF9TT80/Xu25a6g2wRWfSD9f4jluAVENz1ryLETiwR5RpmK2kNbwqy3JICGSsCPQH/d&#xD;nHOYrAny3r22Sp8fyVQTzPUvjUiIRBHJuGNo6o2rdO8ZCERd2mVKi55etLn/6McdIsvdswcmYpk+&#xD;BbYU6KUZdnt61WWlVB2UQxsFCwxcnoYpdYxaInkUh6vcZ9piTq48CiPlYHtSiXn/L1uSOo22xYNZ&#xD;kz58HwnELR6WiE9LI9TBFk2lp9IwkyjdjfGEDg==</SignatureValue><KeyInfo><X509Data><X509SubjectName>CN=api.hdfcbank.com,O=Hdfc Bank Limited,L=Mumbai,ST=Maharashtra,C=IN,2.5.4.5=#1306303830363138,2.5.4.15=#0c1450726976617465204f7267616e697a6174696f6e,1.3.6.1.4.1.311.60.2.1.3=#1302494e</X509SubjectName><X509Certificate>MIIG2DCCBcCgAwIBAgIQDYlipXEDIYJn8Dknt3u68TANBgkqhkiG9w0BAQsFADBhMQswCQYDVQQG&#xD;EwJVUzEVMBMGA1UEChMMRGlnaUNlcnQgSW5jMRkwFwYDVQQLExB3d3cuZGlnaWNlcnQuY29tMSAw&#xD;HgYDVQQDExdHZW9UcnVzdCBFViBSU0EgQ0EgMjAxODAeFw0yMzAzMTYwMDAwMDBaFw0yNDA0MTIy&#xD;MzU5NTlaMIGwMRMwEQYLKwYBBAGCNzwCAQMTAklOMR0wGwYDVQQPDBRQcml2YXRlIE9yZ2FuaXph&#xD;dGlvbjEPMA0GA1UEBRMGMDgwNjE4MQswCQYDVQQGEwJJTjEUMBIGA1UECBMLTWFoYXJhc2h0cmEx&#xD;DzANBgNVBAcTBk11bWJhaTEaMBgGA1UEChMRSGRmYyBCYW5rIExpbWl0ZWQxGTAXBgNVBAMTEGFw&#xD;aS5oZGZjYmFuay5jb20wggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQCzCuKu4+TK/yUo&#xD;wyvxwAWH4SkkEWZ3NeqdbIe7VualhcnMhkBaB9EsU58rDohgQXpaKEDzIglTmmy20j9AN2VCNd+g&#xD;1aDWcD7kYyTAaZx4QWxkNYcAPlCUx2HxYreCTTtaznJG1JQyJ5Jb4kJ/SZezUdT/opMUwmXEARHP&#xD;j6nYjwVCgqC5yJxFHJ9bTjS/JkR+B1ubi9It1+x3MhwsVtmzLsI3RkIKdyWfoFzmFsUt/WRQDCIP&#xD;dNranUMDsy4ZgdwAuzxbdvSCEDmUPcVZg9eOY2kUPqB02470er05XIVn9OYI7DqNafs4cZ01RNWV&#xD;xBWXHv5XnZLhApZYMuEz56wpAgMBAAGjggM6MIIDNjAfBgNVHSMEGDAWgBTKkmdSYd6u/LoiK38c&#xD;h0wl+2+ZWDAdBgNVHQ4EFgQUNXCmfUgJYOZRlNyG2vXJ1S16HakwMQYDVR0RBCowKIIQYXBpLmhk&#xD;ZmNiYW5rLmNvbYIUd3d3LmFwaS5oZGZjYmFuay5jb20wDgYDVR0PAQH/BAQDAgWgMB0GA1UdJQQW&#xD;MBQGCCsGAQUFBwMBBggrBgEFBQcDAjBABgNVHR8EOTA3MDWgM6Axhi9odHRwOi8vY2RwLmdlb3Ry&#xD;dXN0LmNvbS9HZW9UcnVzdEVWUlNBQ0EyMDE4LmNybDBKBgNVHSAEQzBBMAsGCWCGSAGG/WwCATAy&#xD;BgVngQwBATApMCcGCCsGAQUFBwIBFhtodHRwOi8vd3d3LmRpZ2ljZXJ0LmNvbS9DUFMwdwYIKwYB&#xD;BQUHAQEEazBpMCYGCCsGAQUFBzABhhpodHRwOi8vc3RhdHVzLmdlb3RydXN0LmNvbTA/BggrBgEF&#xD;BQcwAoYzaHR0cDovL2NhY2VydHMuZ2VvdHJ1c3QuY29tL0dlb1RydXN0RVZSU0FDQTIwMTguY3J0&#xD;MAkGA1UdEwQCMAAwggF+BgorBgEEAdZ5AgQCBIIBbgSCAWoBaAB2AO7N0GTV2xrOxVy3nbTNE6Iy&#xD;h0Z8vOzew1FIWUZxH7WbAAABhuoCR8QAAAQDAEcwRQIgTboE+zlGzXlnmNa638anIXO63aF8Bhkx&#xD;XhshvwHq8uYCIQC0VzhNNXWyj0e40J5d7NOiDeuBZXgEkSyRYAOQ+m/2ZwB3AHPZnokbTJZ4oCB9&#xD;R53mssYc0FFecRkqjGuAEHrBd3K1AAABhuoCSCgAAAQDAEgwRgIhAMA2sx0mElZu+f//10uqJjNk&#xD;CyA55s3NMnr3Ien1NqylAiEA5h9u0f2n/kvvYjXYN6Ak1uvygZKt04n69WDVWxo8aEEAdQBIsONr&#xD;2qZHNA/lagL6nTDrHFIBy1bdLIHZu7+rOdiEcwAAAYbqAkf2AAAEAwBGMEQCIBUWYYvt22rozIbE&#xD;VxHWkI/3bJwovPcxSRdW4eBcqHDsAiADXJyAuIUtQm1csX/gKmd/rKZp6FqB3SSvD01ffuUPVTAN&#xD;BgkqhkiG9w0BAQsFAAOCAQEAXTlwe0go0lWGpceC9L89HsbU/Hwaei9I5WQ4/JOtheZ2IXQFIVMU&#xD;yUI9AN56VBdpadiL/jnhXizDa+ePvbvb9QqCWOpVih+v+jkHuZDdHLiqp3hq0REYSPrGqtkpsaZe&#xD;S0Ni06leMuaj+Tn2Z6IRO5VlLTQ1SmKHeyUwCiwKXVtrCvRHOTtnvLp+/BRKo4TwbrbRQZp53pmg&#xD;XyqIiwzoBVJZYe/ZoMF2Umv+wyXpESwU1amGWYJ3OHLzDbdvbLR9/4T3nJrtxDH9vDXOzBDsLqng&#xD;wXhm7NhOBUMEYPuTKMeEQzbV5Cf67Y+ilWZ064DZEXJ24Wh/PZtaBdO+0WTzHw==</X509Certificate></X509Data></KeyInfo></Signature></response>';//decryptAES($arrResponse['ResponseSignatureEncryptedValue'],$GWSymmetricKeyDecryptedValue);
					
					//$validateSingnature 	= validateSignature(trim($ResponseSignatureDecryptedValue));
					
					//if($validateSingnature == 1) {
						$xmlOutputData 		= simplexml_load_string(trim($ResponseSignatureDecryptedValue));
						//print_r($xmlOutputData);
						$json 				= json_encode($xmlOutputData);
						$ResponseXML 		= json_decode($json,true);
						
						if(isset($arrResponse['log_id']) && !empty($arrResponse['log_id']))
						{
							$this->FeesReturnApiLog->updateAll(['response_xml_payload'=>json_encode($ResponseXML)],['id'=>$arrResponse['log_id']]);
						}
						if(isset($ResponseXML['faxml']['header']))
						{
							$outResponse 					= $ResponseXML['faxml']['header'];
							$arrFeesReturn['txtstatus'] 	= isset($outResponse['txtstatus']) ? $outResponse['txtstatus'] : '';
							$arrFeesReturn['batchnum'] 		= isset($outResponse['batchnum']) ? $outResponse['batchnum'] : ''; 
							$arrFeesReturn['batchnumext'] 	= isset($outResponse['batchnumext']) ? $outResponse['batchnumext'] : ''; 
							$arrFeesReturn['codstatus'] 	= isset($outResponse['codstatus']) ? $outResponse['codstatus'] : ''; 
							$arrFeesReturn['datpost'] 		= isset($outResponse['datpost']) ? $outResponse['datpost'] : ''; 
							$arrFeesReturn['paymentrefno'] 	= isset($ResponseXML['faxml']['paymentlist']['payment']['paymentrefno']) ? $ResponseXML['faxml']['paymentlist']['payment']['paymentrefno'] : ''; 
							if(isset($ResponseXML['faxml']['header']['txtstatus']) && strtolower($ResponseXML['faxml']['header']['txtstatus']) == 'accepted') {

								$arrFeesReturn['payment_init_date']	= $this->NOW();
							}
							$this->FeesReturn->updateAll($arrFeesReturn,['id'=>'121']);
							echo 'record updated successfully.';
						}
					//} else {
						//$msg 		= 	$validateSingnature;
					//}
					
					//print_r($xmlOutputData->Signature->SignatureValue);
					//	print_r($xmlOutputData->Signature);
					//print_r($xmlOutputData->SignatureValue);
					exit;
				

				
				}
			}
		}
	}
	public function sendEmailNotPaidDeveloper() {
		$arrEmails 		= array('jayshree.tailor@ahasolar.in','solarplanet1812@gmail.com','kushank.p@ahasolar.in','jayshreetailor1.18@gmail.com','kushank.p@ahasolar.in','harishkhiya@gmail.com','jayshreetailor.18@gmail.com','harishkhiya@gmail.com','pulkitdhingra@gmail.com','pulkitdhingra@gmail.com','nilesh.panchal@operaenergy.in','dileeptiwari5358@gmail.com','epc@betterenergies.in','liasioning@ambitenergy.in','gm@ambitenergy.in','ashish.goswami@matgrow.solar','sungevityrenewablepvtltd@gmail.com','account@ambitenergy.in','procure@ambitenergy.in','prafulpatel@signovatiles.com','krushnaminfrapvtltd@gmail.com','parasbhai@pahalsolar.com','HIREN@PROZEALGREEN.COM','manishbhai@pahalsolar.com','bapasitaram_shailesh@yahoo.com','avinashbhai@pahalsolar.com','amit@operaenergy.in','avinashbhai@pahalsolar.com','avinashbhai@pahalsolar.com','rajeshbhai_jada@pahalsolar.com','rajeshbhai_beladiya@pahalsolar.com','sandipbhai@pahalsolar.com','sandipbhai@pahalsolar.com','mansukhbhai@pahalsolar.com','gaurav@pahalsolar.com','vikasbhai@pahalsolar.com','rajeshbhai_beladiya@pahalsolar.com','tusharbhai@pahalsolar.com','alpeshbhai@pahalsolar.com','vinuszankhana4@gmail.com','harshadbhai@pahalsolar.com','pkakadiya757@gmail.com','monaben@pahalsolar.com','monaben@pahalsolar.com','jiviben@pahalsolar.com','jiviben@pahalsolar.com','monaben@pahalsolar.com','ashmitaben@pahalsolar.com','monaben@pahalsolar.com','harshadbhai@pahalsolar.com','SCIONPOWER@YAHOO.COM','vikash@greenpill.in','dhavalsavani@icloud.com','ncchoksi@gnfc.in','saharacorporationco@gmail.com','raynexps@gmail.com','info@harsha-abakus.com','krunalsynth@yahoo.com','shailesh.shekhar@junipergreenenergy.com','bapasitaramsynthetic192@yahoo.com','sales.epc2016@gmail.com','fuzentechnologies@gmail.com','info@greindia.com','shamim.khan@kpgroup.co','hardik.maheta@kpigreenenergy.com','powersundrop@gmail.com','nikhil.sanghani@adityabirla.com','bapasitaramtextile@yahoo.com','lumesolarenergy@gmail.com','sumit@greenbeam.in','insolation.india@gmail.com','ka.vishwanath@o2power.in','info@zodiacenergy.com','dipal@saanikaindustries.com','testing@example.com','testing@example.com','axximumservices@gmail.com','sandeepc@suzlon.com','Aniket@soleos.in','info@kashyap.in','rohit.hadpe@ayanapower.com','info.pv@farmsonsolar.com','ramakant.sharma@kpenergy.co','ZEBRONSOLAR@GMAIL.COM','kushank.p@ahasolar.in','nuglade.pvt.ltd@gmail.com','solarsales@jjpvsolar.com','info@ananyurja.com','YOGESH@ACME.IN');
		pr($arrEmails);
		foreach($arrEmails as $key=>$data) {
			$subject 		= 'SRT Portal GEDA | Payment gateway - Live';
			$email 			= new Email('default');
			$email->profile('default');
			$email->viewVars(array());
			$message_send 	= $email->template('re_developer_not_paid', 'default')
					->emailFormat('html')
					->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
				    ->to($data)
				    ->subject(Configure::read('EMAIL_ENV').$subject)
				    ->send();
				    echo 'sent - '.$key." =>".$data.'<br>';
				    
		}
		exit;
	}
	function saveDeveloperAccount()
	{
		$id=73;
		$id=98;
		$id=4;
		$id=5;
		$this->Developers->saveDeveloperDetails($id);
		echo "saved";
		exit;
	}
	public function GenerateRegistrationNo() {
		$application = $this->Applications->viewApplication(9);
		echo $this->Applications->GenerateRegistrationNo($application);
		exit;

	}
	public function sendDeveloperEmail() {
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
							'conditions'=>['Developers.id'=>158],
							'order'=>['Developers.id'=>'ASC']
						]
					)->first();
		$registration_no 	= 'GUJ/DEV/2023-24/00158';
		$EmailVars 	= array( 'EMAIL_ADDRESS' 	=> $arrDeveloper->developer_customers['email'],
							'PASSWD' 			=> $arrDeveloper->developer_passwords['password'],
							'CONTACT_NAME' 		=> $arrDeveloper->contact_person,
							'TRANSACTION_NO'	=> '',
							'REGISTRATION_NO'	=>$registration_no,
							'URL_HTTP'			=> URL_HTTP);
		
		$subject        = "Unified Single Window ".RE_SHORT_NAME." Portal Login Details";
		
		$EmailTo        = $arrDeveloper->developer_customers['email'];
		
		$template_name	= 'developer_registration_login';
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
	$message_send = $email->template($template_name, 'default')
			->emailFormat('html')
			->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
			->to('jayshree.tailor@ahasolar.in')
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
		echo 'Mail Sent';
		exit;
	}
	public function sendEinvoice()
	{
		$arrOutput = $this->EInvoice->getAccessToken('434','reapplication');
		pr($arrOutput);
		exit;
	}

	public function paymentTest()
	{
		
		error_reporting(0);
		echo '<pre>';
		echo "\r\n--StartTime::".date("Y-m-d H:i:s")."--\r\n";
		
		/** KP */
        $ConnectionManager = ConnectionManager::get('default');
        $ConnectionManager->execute("SET @@SESSION.sql_mode='NO_ENGINE_SUBSTITUTION'");
        /** KP */
        
		$pendingInstallers 	= $this->DeveloperPaymentRequest->find('all',array(
								'join' 		=> ['developers'=>['table'=>'developers','type'=>'left','conditions'=>'DeveloperPaymentRequest.installer_id=developers.id']],
								'fields'	=> array('DeveloperPaymentRequest.installer_id'),
								'conditions'=> array('request_data IS NOT NULL','developers.request_for_upgrade'=>'1')))
								->distinct(['DeveloperPaymentRequest.installer_id'])->toArray();
		
		$updateInstaller 	= 0;
		if(!empty($pendingInstallers)) {
			foreach($pendingInstallers as $installer) {
				$requestDataAll 		= $this->DeveloperPaymentRequest->find('all',array(
														'conditions'=> array('DeveloperPaymentRequest.installer_id'=>$installer->installer_id),
														'order'		=> array('DeveloperPaymentRequest.id'=>'desc')))->toArray();
				
				if(!empty($requestDataAll)) {
					
					foreach($requestDataAll as $requestData) {
						
						if(!empty($requestData)) {
							
							$arrOutput 			= json_decode($requestData->request_data,2);
							$order_id 			= $arrOutput['order_id'];
							
							$count = $this->DeveloperPayment->find('all',array('conditions'=> array('DeveloperPayment.payment_id'=>$order_id)))->count();
							
							if($count == 0)
							{								
								require_once(ROOT . DS . 'vendor' . DS . 'hdfc' . DS . 'hdfc.php');
								$objHdfc 			= new Hdfc(Configure::read('HDFC_MERCHANT_KEY'),Configure::read('HDFC_SALT'),Configure::read('HDFC_ACCESS_CODE'), Configure::read('PAYU_SANDBOX'));
								
								$hdfc['order_no'] 			= $order_id;
								
								$response 	= $objHdfc->getData($hdfc);
								
								//echo '---------->'.$application->application_id.'<br>';
								if(isset($response->Order_Status_Result->order_bank_response) && !empty($response->Order_Status_Result->order_bank_response)) {
									
									$bankResponse 			= strtolower($response->Order_Status_Result->order_bank_response);
									
									$arrbankRes 			= explode("success",$bankResponse);
									if(count($arrbankRes) > 1 || $bankResponse=='s') {
										echo '---------->'.$installer->installer_id.'<br>';
										
										$arrpassdata 					= array();
										$arrpassdata['order_id'] 		= $order_id;
										$arrpassdata['order_status'] 	= 'success';
										$arrpassdata['merchant_param1'] = encode($installer->installer_id);
										$arrpassdata['merchant_param2'] = $arrOutput['merchant_param2'];
										$arrpassdata['trans_date'] 		= $response->Order_Status_Result->order_status_date_time;
										$arrpassdata['tracking_id'] 	= $response->Order_Status_Result->reference_no;
										echo '---------->BeforeUpgradeDeveloperPayment'.'<br>';
										$this->DeveloperPayment->save_upg_pck_data_success($arrpassdata); 
										echo '---------->UpgradeDeveloperPayment'.'<br>';
										$arrpay 					= array();
										$arrpay['installer_id'] 	= $installer->installer_id;
								
										$arrpay['modified'] 		= $this->NOW();
										$arrpay['response_data']	= json_encode($response);
										echo '---------->BeforeUpgradeDeveloperPaymentRequest'.'<br>';
										$this->DeveloperPaymentRequest->updateAll($arrpay,array('id'=>$requestData->id));
										echo '---------->UpgradeDeveloperPaymentRequest'.'<br>';
										$updateInstaller++;
										echo '----------> AP'.$installer->installer_id.'<br>';
									}
								}
							}
						}
						
					}
					
				}
			}
		}
		echo "Total Installer".count($pendingInstallers);
		echo "Updated Installer".$updateInstaller;
		echo "\r\n--EndTime::".date("Y-m-d H:i:s")."--\r\n";
	}
	public function GeopaymentTest()
	{
		error_reporting(0);
		echo '<pre>';
		echo "\r\n--StartTime::".date("Y-m-d H:i:s")."--\r\n";
		
			/** KP */
        $ConnectionManager = ConnectionManager::get('default');
        $ConnectionManager->execute("SET @@SESSION.sql_mode='NO_ENGINE_SUBSTITUTION'");
        /** KP */
      

		$pendingApplications 	= $this->GeoApplicationPaymentRequest->find('all',array(
													'join' 		=> [' application_geo_location'=>['table'=>' application_geo_location','type'=>'left','conditions'=>'GeoApplicationPaymentRequest.application_id = application_geo_location.application_id']],
													'fields'	=> array('GeoApplicationPaymentRequest.geo_id'),
													'conditions'=> array('application_geo_location.payment_status !='=>'1')))->distinct(['GeoApplicationPaymentRequest.geo_id'])->toArray();
		$updateApplication 	= 0;
		if(!empty($pendingApplications)) {
			foreach($pendingApplications as $application) {
				$requestDataAll 		= $this->GeoApplicationPaymentRequest->find('all',array(
														'conditions'=> array('GeoApplicationPaymentRequest.geo_id'=>$application->geo_id),
														'order'		=> array('GeoApplicationPaymentRequest.id'=>'desc')))->toArray();

				if(!empty($requestDataAll)) {
					foreach($requestDataAll as $requestData) {
						if(!empty($requestData)) {
							$arrOutput 			= json_decode($requestData->request_data,2);

							$order_id 			= $arrOutput['order_id'];
							require_once(ROOT . DS . 'vendor' . DS . 'hdfc' . DS . 'hdfc.php');
							$objHdfc 			= new Hdfc(Configure::read('HDFC_MERCHANT_KEY'),Configure::read('HDFC_SALT'),Configure::read('HDFC_ACCESS_CODE'), Configure::read('PAYU_SANDBOX'));
							$hdfc['order_no'] 			= $order_id;
							$response 	= $objHdfc->getData($hdfc);
							print_r($response);
							echo"<pre>"; print_r($hdfc['order_no'] ); die();
							if(isset($response->Order_Status_Result->order_bank_response) && !empty($response->Order_Status_Result->order_bank_response)) {
								$bankResponse 			= strtolower($response->Order_Status_Result->order_bank_response);
								
								$arrbankRes 			= explode("success",$bankResponse);
								if(count($arrbankRes) > 1) {
									echo '---------->'.$application->application_id.'<br>';
									$updateApplication++;
									$arrpassdata 					= array();
									$arrpassdata['order_id'] 		= $order_id;
									$arrpassdata['order_status'] 	= 'success';
									$arrpassdata['merchant_param1'] = encode($application->application_id);
									$arrpassdata['trans_date'] 		= $response->Order_Status_Result->order_status_date_time;
									$arrpassdata['tracking_id'] 	= $response->Order_Status_Result->reference_no;
									echo '---------->BeforeReApplicationPayment'.'<br>';
									$this->GeoApplicationPayment->savedata_success($arrpassdata);
									echo '---------->ReApplicationPayment'.'<br>';
									$arrpay 					= array();
									$arrpay['application_id'] 	= $application->application_id;
							
									$arrpay['modified'] 		= $this->NOW();
									$arrpay['response_data']	= json_encode($response);
									echo '---------->BeforeGeoApplicationPaymentRequest'.'<br>';
									print_r($arrpay);
									$this->GeoApplicationPaymentRequest->updateAll($arrpay,array('id'=>$requestData->id));
									echo '---------->GeoApplicationPaymentRequest'.'<br>';
								}
							}
						}
					}
				}
			}
		}
		echo "Total Application".count($pendingApplications);
		echo "Updated Application".$updateApplication;
		echo "\r\n--EndTime::".date("Y-m-d H:i:s")."--\r\n";
		echo '</pre>';
		exit;
	}
	public function downloadCSV()
	{
		$this->autoRender = false;
	
		$csvFields = $this->ApplyonlineMessage->find('all', [
			'fields' => ['application_id', 'message', 'user_id', 'created']
		]);

		$message_count_data 		= $this->ApplyonlineMessage->find('all', [
			'fields'	=> array('message_count' => 'COUNT(message)'),
			'group' 	=> array('application_id'),
			'order' 	=> array('message_count' => 'DESC')
		])->first();

		$csvContent = "Application ID";

		if ($message_count_data && isset($message_count_data['message_count'])) {
			for ($i = 1; $i <= $message_count_data['message_count']; $i++) {
				$csvContent .= ",Comment-$i,Created By,Created On";
			}
		}

		$csvContent .= "\r\n";
		$messages = $csvFields->toArray();
		$groupedMessages = [];
		foreach ($messages as $msg) {
			$groupedMessages[$msg['application_id']][] = $msg;
		}
	
	
		foreach ($groupedMessages as $appId => $msgs) {
			$row = "$appId";
			foreach ($msgs as $msg) {
				$dateTime = date('Y-m-d H:i:s', strtotime($msg['created']));
				$row .= ",\"{$msg['message']}\",{$msg['user_id']},\"{$dateTime}\"";
			}
			$row .= "\r\n";
			$csvContent .= $row;
		}

		$csvfilerootpath 	= SITE_ROOT_DIR_PATH."/tmp/projects-".time().".csv";

		$fp 			= fopen($csvfilerootpath,"w+");
		fwrite($fp,$csvContent);
		fclose($fp);
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: public");
		header("Content-Description: File Transfer");
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename="'.basename($csvfilerootpath).'"');
		header("Content-Length: ".filesize($csvfilerootpath));
		ob_end_flush();
		@readfile($csvfilerootpath);
		$this->response->download($csvfilerootpath);
		// @unlink($csvfilepath);
		die;
	}

	public function validate_login(){
		
		//$this->autoRender 			= false;
		//jayshree.tailor@gmail.in
		// 23d42f5f3f66498b2c8ff4c20b8c5ac826e47146
		$email 				= isset($this->request->data['email'])?$this->request->data['email']:0;
		$password 			= isset($this->request->data['password'])?$this->request->data['password']:0;
		//$email 				= $this->request->data['email'];
		//$password 			= $this->request->data['password'];
		//echo"<pre>"; print_r($this->request->data); die();
		$developer_data = $this->DeveloperCustomers->find('all',array('fields'=>['id','email','password'],'conditions'=>array('email'=>$email,'password'=>$password)))->first();

		if(!empty($developer_data)){
			$token 		= $this->GenerateAPIToken($developer_data->id);
			$data = array();
			$data['developer_id'] 	= $developer_data->id;
			$data['token'] 			= $token;

			$this->ApiToken->SetAPIResponse('msg', 'login Credential are validated');
			$this->ApiToken->SetAPIResponse('response', $data);
			$this->ApiToken->SetAPIResponse('type','success');
			echo $this->ApiToken->GenerateAPIResponse();
		}else{
			$this->ApiToken->SetAPIResponse('msg', 'login Credential are Not validated');
			$this->ApiToken->SetAPIResponse('response', '');
			$this->ApiToken->SetAPIResponse('type','failure');
			echo $this->ApiToken->GenerateAPIResponse();
		}
		exit;
	}

	public function GenerateAPIToken($customer_id) 
	{
		$date						= date('Y-m-d H:i:s');
		$rand						= rand(10000,99999);
		$rand						= strtotime($date).$rand;
		$this->token				= md5($rand.HMAC_HASH_PRIVATE_KEY);

		$tokenEntity 				= $this->ApiToken->newEntity();

		$tokenEntity->token 		= $this->token;
		$tokenEntity->customer_id 	= $customer_id;
		$tokenEntity->last_access 	= $date;
		$tokenEntity->created 		= $date;

		if($this->ApiToken->save($tokenEntity)) {
			//$this->arrToken	= $this->get($tokenEntity->id);
			return $this->token;
		}
		return "";
	}

	public function saveDocument()
    {
        // URLs of the documents
        $connectivityDocUrl = 'file:///C:/xampp/htdocs/geda/webroot/img/applications/23/STUstep1_20240214050232355337376.pdf';
        $bgDocUrl = 'file:///C:/xampp/htdocs/geda/webroot/img/applications/23/bg_upload_file_202401120201211888768265.pdf';

        // Define the target directory and filenames
        $targetDir = WWW_ROOT . 'files/documents/';
        $connectivityDocTarget = $targetDir . 'STUstep1.pdf';
        $bgDocTarget = $targetDir . 'bg_upload_file.pdf';

        // Ensure the target directory exists
        $folder = new Folder($targetDir, true, 0755);

        // Download and save the connectivity document
        if ($this->downloadAndSaveFile($connectivityDocUrl, $connectivityDocTarget)) {
            $this->Flash->success(__('Connectivity document has been saved.'));
        } else {
            $this->Flash->error(__('Failed to save connectivity document.'));
        }

        // Download and save the background document
        if ($this->downloadAndSaveFile($bgDocUrl, $bgDocTarget)) {
            $this->Flash->success(__('Background document has been saved.'));
        } else {
            $this->Flash->error(__('Failed to save background document.'));
        }

        // Redirect to some page
        return $this->redirect(['action' => 'index']);
    }

    private function downloadAndSaveFile($url, $target)
    {
        // Ensure the URL is valid and can be accessed
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            Log::error('Invalid URL: ' . $url);
            return false;
        }

        // Get the file contents
        $fileContent = file_get_contents($url);
        if ($fileContent === false) {
            Log::error('Failed to get contents of URL: ' . $url);
            return false;
        }

        // Write the file content to the target location
        try {
            $file = new File($target, true, 0644);
            if ($file->exists()) {
                if ($file->write($fileContent)) {
                    $file->close();
                    return true;
                } else {
                    Log::error('Failed to write to file: ' . $target);
                }
            } else {
                Log::error('File does not exist after creation: ' . $target);
            }
        } catch (\Exception $e) {
            Log::error('Exception while handling file: ' . $e->getMessage());
        }

        return false;
    }

    public function save_consumer_data()
    {
    	//echo"<pre>"; print_r(); die();

   	$consumerNumbers = ['44224004216', '40509000061', '14953005714', '40501002049', '40521009197', '14953007034', '14953007042', '44223006304', '14958024190', '14958024182', '17414070921', '17429047262', '44248005212', '14953003500', '40506004066', '40505002930', '40504004433', '40511002858', '40515002860', '40517002973', '40517004020', '40519004485', '44225002586', '44210005070', '40517002604', '18111108710', '40508002303', '40501011951', '45952002200', '15902015804', '40508004080', '40508001013', '40501011943', '40502100095', '40501028072', '40501041176', '12928021868', '17427029500', '14935014601', '44228006965', '18104105124', '18104121421', '44205028121', '44209020338', '44210006211', '44248005220', '18126105780', '18131100421', '44213003043', '45952003320', '17415013018', '17415013972', '17415032292', '14935018062', '14921001839', '44224003805', '44224004216', '44220005382', '18104103156', '08530023560', '18104002856', '40519004515', '44338018801', '44205017022', '44209003018', '44210000752', '40519001729', '44232001581', '44248002698', '40502004150', '45951025303', '14958044124', '14958024204', '14958024212', '14933038490', '14933038511', '14933038481', '14933038449', '17414066215', '17414066207', '14965002555', '17416025540', '17429047122', '17429047270', '17429047319', '14968003730', '14968003706', '17427029518', '14935012457', '14935014555', '14921014710', '14921014728', '18104106724', '44338008270', '44205028113', '44210006319', '44232017445', '44232017437', '44232017453', '18111102453', '18111102291', '44212003481', '45952001661', '14968008325', '14968014449', '10825004349', '10825003288', '14965003250', '40512004226', '10825004519', '45241064028', '45240000344', '45242001240', '45914000092', '45914003245', '14966000602'
		 ];

		//$consumerNumbers = $this->GetConsumerData->find('all')->toArray();
		
        foreach ($consumerNumbers as $number) {
        	//echo"<pre>"; print_r($number->consumer_no); die();
        	//$number->consumer_no
            $discom_id = 11;
   			 $consumerDataEntity = $this->GetConsumerData->newEntity();

			 $consumerDataEntity->consumer_no		= $number;
			
			 $this->GetConsumerData->save($consumerDataEntity);
			// $arr_output = $this->ThirdpartyApiLog->searchConsumerApi($number,$discom_id,'','','');
			// echo"<pre>"; print_r($arr_output); die();
			// 		$data_subdiv['name'] 		= $output_details_obj->NAME;
			// 		$data_subdiv['address'] 	= $output_details_obj->ADDRESS;
			// 		$data_subdiv['city'] 		= $output_details_obj->CITY;
			// 		$data_subdiv['taluka'] 		= $output_details_obj->TALUKA;
			// 		$data_subdiv['district'] 	= $output_details_obj->DISTRICT;
			// 		$data_subdiv['load'] 		= $output_details_obj->LOAD;
			// 		$data_subdiv['category'] 	= $output_details_obj->CATEGORY;
			// 		$data_subdiv['tariff'] 		= $output_details_obj->TARIFF;
			// 		$data_subdiv['phase'] 		= $output_details_obj->PHASE;
			// 		$data_subdiv['solar_flag'] 	= $output_details_obj->SOLAR_FLAG;

			// $this->GetConsumerData->updateAll($data_subdiv,array('consumer_no'=>$number));
        }
        exit;
     
    }
   
}
?>
