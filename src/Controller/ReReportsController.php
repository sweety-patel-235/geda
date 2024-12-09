<?php
namespace App\Controller;
ini_set('memory_limit', '-1');
ini_set('max_execution_time', '-1');
set_time_limit(0);
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
use Cake\View\Helper\PaginatorHelper;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;
use PHPExcel\PHPExcel;

class ReReportsController extends FrontAppController
{
	public $user_department = array();
	public $arrDefaultAdminUserRights = array();
	public $helpers 		= array('Time','Html','Form','ExPaginator');
	public $PAGE_NAME 		= '';
	public $ExportFields 	= array();
	public $paginate 		= ['limit' => PAGE_RECORD_LIMIT,'order' => ['ApplyOnlines.id ' => 'desc']];
    public $arrCapacity 		= array('0'=>'0_3','1'=>'3_6','2'=>'6_10','3'=>'10_50','4'=>'50');
    public $arrCapacityLabel	= array('0'=>'1kW  to 3kW','1'=>'3kW to 6kW','2'=>'6kW to 10kW','3'=>'10kW to 50kW','4'=>'Above 50');
    public $arrDisStage 		= array('31'=>'No of Application Registered','23'=>'Documents Verified','9999'=>'Documents Verification Pending','6002'=>'Query Generated','0'=>'Querry Resolved','2000'=>'Estimate Issued','2111'=>'Estimate Paid','1000'=>'Self Certification Updated','17'=>'Meter Installed');
    public $arrSubDivStage 		= array('31'=>'Application Registered','23'=>'Documents Verified','6002'=>'Document Verified and Query Issued','2'=>'FQ Issued','2111'=>'FQ Paid','2222'=>'FQ Not Paid','6000'=>'Under Compliance','17'=>'Bi-directional Meter Installed','1777'=>'Agreement Submitted','99'=>'Application Cancelled'); //Under Compliance means query raised but not replied by installer, Agreement Submitted from meter installation data if agreement date come
    public $AllowedGedaIDS      = array('1324','1325','1326','1327','1328','1409','1410','1405');
   
   
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

        $this->loadModel('ApiToken');
		$this->loadModel('ApplyOnlines');
		$this->loadModel('DiscomMaster');
		$this->loadModel('FesibilityReport');
		$this->loadModel('RegistrationScheme');
		$this->loadModel('RegistrationSchemeDocument');
		$this->loadModel('WorkCompletion');
		$this->loadModel('WorkCompletionDocument');
		$this->loadModel('ChargingCertificate');
		$this->loadModel('Users');
		$this->loadModel('Userrole');
		$this->loadModel('Userroleright');
		$this->loadModel('ApplyOnlineApprovals');
		$this->loadModel('Adminaction');
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
		$this->loadModel('Payumoney');
		$this->loadModel('ApplyonlinePayment');
		$this->loadModel('ApplyonlineMessage');
		$this->loadModel('Installation');
		$this->loadModel('WorkCompletion');
		$this->loadModel('CeiApplicationDetails');
		$this->loadModel('ThirdpartyApiLog');
		$this->loadModel('InstallerCategory');
		$this->loadModel('InstallerCategoryMapping');
		$this->loadModel('ApplyonlineMessages');
		$this->loadModel('DistrictNameMapping');
		$this->loadModel('Subsidy');
		$this->loadModel('MISReportData');
		$this->loadModel('ApplicationStages');
		$this->loadModel('Applications');
		$this->loadModel('Developers');
		$this->set('ApplyonlineMessage',$this->ApplyonlineMessage);
		$this->set('InspectionReport',$this->InspectionReport);
		$this->set('Userright',$this->Userright);

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
		//$this->set("arrReportFields",$this->arrReportFields);

		$is_installer = false;
		if ($customer_type == "installer") {
			$is_installer = true;
			$this->set("arrReportFields",$this->MISReportData->arrReportFieldsIns);
		}
		else
		{
			$this->set("arrReportFields",$this->MISReportData->arrReportFields);
		}
		$this->set("is_installer",$is_installer);
		$this->set("customer_types",array("customer","installer"));
    }

    private function GetExcelColumnName($num)
    {
		// $str 			= '';
		// $DEFAULT_NUMBER = 64;
		// while ($num > 0) {
		// 	$Module = ($num % 26);
		// 	$Module = ($Module > 0?$Module:26);
		// 	$str 	= chr( $Module + $DEFAULT_NUMBER) . $str;
		// 	$num 	= (int) ($num / 26);
		// }
		// return trim($str);
		$str 			= '';
		$DEFAULT_NUMBER = 64;
		while ($num > 0) {
			$Module = ($num % 26);
			$Module = ($Module > 0?$Module:26);
			$str 	= chr( $Module + $DEFAULT_NUMBER) . $str;

			if($num == 53)
			{
				$num 	= (int) ($num / 26);
			}
			else
			{
				$num 	= (int) ($num / 27);
			}
		}
		return trim($str);
    }


	public function ReMISReport()
    {
		$customer_id 		= $this->Session->read("Customers.id");
		$member_id 			= $this->Session->read("Members.id");
		$state 				= '';
		$branch_id 			= '';
		$main_branch_id		= '';
		$member_type 		= '';
		
		$member_type 		= $this->Session->read('Members.member_type');
		$area 				= $this->Session->read('Members.area');
		$circle 			= $this->Session->read('Members.circle');
		$division 			= $this->Session->read('Members.division');
		$subdivision 		= $this->Session->read('Members.subdivision');
		$section 			= $this->Session->read('Members.section');
		$authority_account 	= $this->Session->read('Members.authority_account');
		$TotalPvCapacity 	= 0;
		$login_type 		= '';

		if($this->Session->check("Members.state")){
			$state 			= $this->Session->read("Members.state");
		}
		if($this->Session->check("Customers.customer_type")){
			$cust_type 		= $this->Session->read("Customers.customer_type");
		}
		if($this->Session->check("Customers.login_type")){
			$login_type 	= $this->Session->read("Customers.login_type");
		}
		if(empty($customer_id) && empty($member_id))
		{
			return $this->redirect(URL_HTTP.'/home');
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
			$main_branch_id = array("field"=>$field,"id"=>$id);
		}
		if(isset($this->request->data['Reset']) && !empty($this->request->data['Reset'])){
			$this->Session->delete("Customers.SearchApplication");
			$this->Session->delete("MembersSearchApplication");
			$this->Session->delete("Customers.Page");
			return $this->redirect(URL_HTTP.'applications-list');
		}
		//$this->Session->write('Customers.Page',$page);

		$this->removeExtraTags();

		if(isset($this->request->data['Search']) && !empty($this->request->data['Search'])){
			$this->Session->write("MembersSearchApplication",$this->request->data);
			$this->Session->write('Customers.SearchApplication',serialize($this->request->data));
		} else {
			if($this->Session->check("MembersSearchApplication")) {
				$this->request->data = $this->Session->read("MembersSearchApplication");
			}
			if($this->Session->check("Customers.SearchApplication"))
			{
				$this->request->data = unserialize($this->Session->read("Customers.SearchApplication"));
			}
		}
		$consumer_no 			= isset($this->request->data['consumer_no']) ? $this->request->data['consumer_no'] : '';
		$application_search_no 	= isset($this->request->data['application_search_no']) ? $this->request->data['application_search_no'] : '';
		$installer_name 		= (isset($this->request->data['installer_name'])) ? $this->request->data['installer_name'] : '';
		$discom_name 			= isset($this->request->data['discom_name']) ? $this->request->data['discom_name'] : '';
		$payment_status 		= isset($this->request->data['payment_status']) ? $this->request->data['payment_status'] : '';
		$order_by_form 			= isset($this->request->data['order_by_form']) ? $this->request->data['order_by_form'] : 'Applications.modified|DESC';
		$disclaimer_subsidy 	= isset($this->request->data['disclaimer_subsidy']) ? $this->request->data['disclaimer_subsidy'] : '';
		$pcr_code 				= isset($this->request->data['pcr_code']) ? $this->request->data['pcr_code'] : '';
		$msme 					= isset($this->request->data['msme']) ? $this->request->data['msme'] : '';
		$msmeonly 				= isset($this->request->data['msmeonly']) ? $this->request->data['msmeonly'] : '';
		$inspection_status 		= isset($this->request->data['inspection_status']) ? $this->request->data['inspection_status'] : '';
		$geda_letter_status 	= isset($this->request->data['geda_letter_status']) ? $this->request->data['geda_letter_status'] : '';
		$geda_approved_status 	= isset($this->request->data['geda_approved_status']) ? $this->request->data['geda_approved_status'] : '';
		if(isset($this->request->data['category'][0]) && $this->request->data['category'][0]=='3002,3003')
		{
			$this->request->data['category'] = explode(",",$this->request->data['category'][0]);
		}
		$category 				= isset($this->request->data['category']) ? $this->request->data['category'] : '';
		$receipt_no 			= isset($this->request->data['receipt_no']) ? $this->request->data['receipt_no'] : '';
		$is_enhancement 		= isset($this->request->data['is_enhancement']) ? $this->request->data['is_enhancement'] : '';
		$this->request->data['ses_login_type'] 	= $this->Session->read('Customers.login_type');
		$this->request->data['order_by_form'] 	= $order_by_form;
		$this->request->data['customer_id'] 	= $customer_id;
		$this->request->data['member_id'] 		= $member_id;

		$DateField 			= isset($this->request->data['DateField'])?$this->request->data['DateField']:'';
		$from_date 			= isset($this->request->data['DateFrom'])?$this->request->data['DateFrom']:'';
		$end_date 			= isset($this->request->data['DateTo'])?$this->request->data['DateTo']:'';
		$application_status = isset($this->request->data['status'])?$this->request->data['status']:'';
		$installer_name 	= (isset($this->request->data['installer_name_multi']) && !empty($this->request->data['installer_name_multi']))?explode(",",$this->request->data['installer_name_multi']):'';
		
		$arrAdminuserList	= array();
        $arrUserType		= array();
        $arrCondition		= array();
        $this->SortBy		= "application_stages.created";
        $this->Direction	= "DESC";
        $this->intLimit		= 10;
        $this->CurrentPage  = 1;
        $option 			= array();

        $option['colName']  = array('id','application_no','name_of_applicant','application_type','installer_name','submitted_on');

       	$sortArr=array('installer_name'=>'installers.installer_name','submitted_on'=>'application_stages.created','application_no'=>'application.application_no','application_type'=>'application.application_type');
        $this->SetSortingVars('Applications',$option,$sortArr);

        $ApplicationsList 	= $this->Applications->getDataApplications($this->request->data);
        $ApplicationsListData 	= $ApplicationsList['list'];
        $start_page=isset($this->request->data['start']) ? $this->request->data['start'] : 1;
       	$this->paginate['limit']= 10;
       	$this->paginate['page'] = ($start_page/$this->paginate['limit'])+1;
       
       	try
		{
			$paginate_data = $this->paginate($ApplicationsListData);
		}
		catch (NotFoundException $e)
		{
			return $this->redirect('/Applications/list');
		}
		$arrAdminuserList				= $paginate_data;
        $usertypes 						= array();
        $option['dt_selector']			='table-example';
        $option['formId']				='formmain';
        $option['url']					= '';
        $option['recordsperpage']		= '10';
        $option['allsortable']			= '-all';
        $option['total_records_data']	= count($arrAdminuserList->toArray());
        $option['order_by'] 			= "order : [[5,'desc']]";

        $arr_status_dropdown 			= $this->ApplicationStages->application_dropdown_status;
        unset($arr_status_dropdown['99']);
        $JqdTablescr 			= $this->JqdTable->create($option);
        $installers_list 		= $this->Developers->getInstallerListReport();
        $applicationCategory 	= $this->ApplicationCategory->find('list',array('keyField'=>'id','valueField'=>'category_name','conditions'=>array('id !='=>1)))->toArray();
        $this->set('applicationCategory',$applicationCategory);
        $this->set('arrAdminuserList',$arrAdminuserList->toArray());
        $this->set('JqdTablescr',$JqdTablescr);
        $this->set('period',$this->period);
        $this->set('limit',$this->intLimit);
        $this->set("CurrentPage",$this->CurrentPage);
        $this->set("SortBy",$this->SortBy);
        $this->set("Direction",$this->Direction);
        $this->set("pagetitle",'Project : ');
        $this->set("application_dropdown_status",$arr_status_dropdown);
        $this->set("Installers",$installers_list);
        $this->set("default_fields",!empty($customer_id) ? array_keys($this->Applications->arrReportFieldsIns) : array_keys($this->Applications->arrReportFields));
        $this->set("page_count",(isset($this->request->params['paging']['ProjectSurvey']['pageCount'])?$this->request->params['paging']['ProjectSurvey']['pageCount']:0));

        $out 		=array();
        $counter 	= '1';
        $page_mul 	= ($this->CurrentPage-1);
        foreach($arrAdminuserList->toArray() as $key=>$val) {
        	$temparr=array();
            foreach($option['colName'] as $key) {
                if(isset($val[$key])){
                    $temparr[$key]=$val[$key];
                }
                if($key=='id') {
                   $temparr[$key]= $counter+($page_mul*10);
                   $counter++;
                }
                if($key=='installer_name') {
                   $temparr[$key]= ucwords($val['developers']['installer_name']);
                }
                if($key=='application_type') {
                   //$temparr[$key]= $val->application_status;
                   if($val->application_type == 2){
                   	$temparr[$key] = 'Open Access Solar';
                   }else if($val->application_type == 3){
                   	$temparr[$key] = 'Wind';
                   }else if($val->application_type == 4){
                   	$temparr[$key] = 'Hybrid';
                   }else if($val->application_type == 5){
                   	$temparr[$key] = 'PM-KUSUM A&C';
                   }else if($val->application_type == 6){
                   	$temparr[$key] = 'PM-KUSUM C';
                   }
                }
                if($key=='name_of_applicant') {
					$temparr[$key]= $val->name_of_applicant;
				}
                if($key=='submitted_on') {
                	if(!empty($val->submitted_date))
                	{
                		$temparr[$key]= date('m-d-Y H:i a',strtotime($val->submitted_date));
                	}
                	else
                	{
                		$temparr[$key]= '-';
                	}

                }
            }
            $out[]=$temparr;
        }

        if ($this->request->is('ajax'))
        {
            header('Content-type: application/json');
            echo json_encode(array(	"condi" 			=> $arrCondition,
            						"draw" 				=> intval($this->request->data['draw']),
					                "recordsTotal"    	=> intval( $this->request->params['paging']['Applications']['count']),
					                "recordsFiltered" 	=> intval( $this->request->params['paging']['Applications']['count']),
					                "data"            	=> $out));
            die;
        }
    }

	public function getrereportfromexel()
	{
		$applicationData 	= $this->fetch_re_data($this->request->data);
		
	}

    private function WriteReReportData($PhpExcel,$RowID,$Report_Data)
    {
    	$gridLevel 				= $this->ApiToken->arrGridLevel;
		$EndSTU 				= $this->ApiToken->arrEndSTU;
		$EndCTU 				= $this->ApiToken->arrEndCTU;
		$injectionLevel 		= $this->ApiToken->arrInjectionLevel;
    	$i = 1;

    	foreach ($this->Applications->ExportFields as $Field_Name) {

    		$RowName = $this->MISReportData->GetExcelColumnName($i);
    		
    		$RowData = "";
    		switch ($Field_Name) {
				case 'sr_no':
					$RowData = ($RowID-1);
					break;
				case 'scheme':
					$RowData = "RE-2023-24";
					break;
				case 'installer_name':
					$RowData = $Report_Data['developers'][$Field_Name];
					break;
				case 'msme':
					$RowData = ($Report_Data[$Field_Name] == 1) ? 'YES' : 'NO';
					break;
				case 'application_type':
					$RowData = $Report_Data['application_category']['route_name'];
					break;
				case 'project_district':
					$RowData = $Report_Data['district_master']['name'];
					break;
				case 'discom':
					$RowData = $Report_Data['branch_masters']['title'];
					break;
				case 'submitted_on':
					$RowData = ($Report_Data['submitted_date']);
					break;	
				case 'grid_connectivity':
					$RowData = isset($gridLevel[$Report_Data['grid_connectivity']]) ? $gridLevel[$Report_Data['grid_connectivity']] : '-';
					break;	
				case 'injection_level':
					if($Report_Data['grid_connectivity'] == 1) {
						$RowData = isset($injectionLevel[$Report_Data['injection_level']]) ? $injectionLevel[$Report_Data['injection_level']] : '';
					} 
					if($Report_Data['grid_connectivity'] == 2) {
						$RowData = isset($Report_Data['injection_level_ctu']) ? $Report_Data['injection_level_ctu'] : '';
					}
					break;
				case 'application_end_use_electricity':
					if($Report_Data['grid_connectivity'] == 1) {
						//$RowData = (!empty($Report_Data['application_end_use_electricity'])) ? ($EndSTU[$Report_Data['application_end_use_electricity']]) : '-';
						$RowData = isset($EndSTU[$Report_Data['application_end_use_electricity']['application_end_use_electricity']]) ? $EndSTU[$Report_Data['application_end_use_electricity']['application_end_use_electricity']] : '';
					} elseif($Report_Data['grid_connectivity'] == 2) {
						$RowData = isset($EndCTU[$Report_Data['application_end_use_electricity']['application_end_use_electricity']]) ? $EndCTU[$Report_Data['application_end_use_electricity']['application_end_use_electricity']] : '';
					}
					break;	
				case 'pv_capacity_ac':
					if($Report_Data['application_type'] == 3) {
						$RowData = isset($Report_Data['total_capacity'])?$Report_Data['total_capacity']:"";
					} else if($Report_Data['application_type'] == 4) {
						$RowData = isset($Report_Data['total_wind_hybrid_capacity'])?$Report_Data['total_wind_hybrid_capacity']:"";
					} else {
						$RowData = isset($Report_Data[$Field_Name])?$Report_Data[$Field_Name]:"";
					}
				break;
				case 'pv_capacity_dc':
					if($Report_Data['application_type'] == 4) {
						$RowData = (!empty($Report_Data['capacity_wtg']) && !empty($Report_Data['wtg_no'])) ? (($Report_Data['wtg_no'] * $Report_Data['capacity_wtg'])) : '';
					} else {
						$RowData = isset($Report_Data[$Field_Name])?$Report_Data[$Field_Name]:"";
					}
				break;
				case 'application_status':
					$RowData = isset($this->ApplicationStages->application_status[$Report_Data['application_status']]) ? $this->ApplicationStages->application_status[$Report_Data['application_status']] : '';
				break;
				case 'module_hybrid_capacity':
					$RowData = isset($Report_Data['module_hybrid_capacity']) ? $Report_Data['module_hybrid_capacity'] . ' MW' : '';
				break;
				case 'inverter_hybrid_capacity':
					$RowData = isset($Report_Data['inverter_hybrid_capacity']) ? $Report_Data['inverter_hybrid_capacity'] . ' MW' : '';
				break;
				default:
					$RowData = isset($Report_Data[$Field_Name])?$Report_Data[$Field_Name]:"";
					break;
    		}
    		$PhpExcel->getActiveSheet()->setCellValue($RowName.$RowID,$RowData);
    		$i++;
    	}
    }
    public function fetch_re_data($array_request,$return_count=0)
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
		$order_by_form 	= 'Applications.modified|DESC';
		$array_request['order_by_form'] 	= $order_by_form;
		if($this->Session->check("Members.state")){
			$state 		= $this->Session->read("Members.state");
		}
		if($this->Session->check("Members.member_type")){
			$member_type = $this->Session->read("Members.member_type");
		}
		if(empty($customer_id) && empty($member_id))
		{
			return $this->redirect(URL_HTTP.'/home');
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
			$main_branch_id = array("field"=>$field,"id"=>$id);
		}
		$array_request['ses_login_type'] 	= $this->Session->read('Customers.login_type');
		$array_request['order_by_form'] 	= $order_by_form;
		$array_request['customer_id'] 	= $customer_id;
		$array_request['member_id'] 		= $member_id;
    	$DateField 			= isset($array_request['DateField'])?$array_request['DateField']:'';
		$from_date 			= isset($array_request['DateFrom'])?$array_request['DateFrom']:'';
		$end_date 			= isset($array_request['DateTo'])?$array_request['DateTo']:'';
		$fields_date 		= isset($array_request['DateField'])?$array_request['DateField']:'';
		$fields_date  		= "application_stages.created";

		if (!empty($DateField) && in_array($DateField,array("application_stages.created","charging_certificate.meter_installed_date"))) {
			$fields_date 	= $DateField;
		}

		$whereCharging 		= '';
		if($fields_date != 'application_stages.created' && !empty($from_date) && !empty($end_date))
	    {
	    	$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
			$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
	    	$whereCharging 	= ' and '.$fields_date.' between '.$StartTime.' and '.$EndTime;
	    }

    	$connection         = ConnectionManager::get('default');
    	$arrRequestSelected = $this->Applications->DefaultExportFields;
    	//$sql_first 			= $this->Applications->GetReReportFields($arrRequestSelected,$array_request);
		
		$ApplicationsList 	= $this->Applications->getDataApplications($array_request);

        $ApplicationsListData 	= $ApplicationsList['list'];
    	//$sql_count 	= "	select count(0)";
		//$sql 		= $this->Applications->QueryStr($array_request);
		//$applicationData_output = $connection->execute($ApplicationsListData)->fetchAll('assoc');
		$applicationData_output = $ApplicationsListData->toArray();
		require_once(ROOT . DS . 'vendor' . DS . 'PhpExcel' . DS . 'PHPExcel.php');

		// Create new PHPExcel object
		$objPHPExcel = new \PHPExcel();

		$objPHPExcel->getProperties()->setCreator("creator name");

		//HEADER
		$i=1;
		$j=1;
		$objPHPExcel->setActiveSheetIndex(0);
		$arrExportFields 	= $this->Applications->ExportFields;

		$arrReportFields 	= $this->Applications->arrReportFields;
		foreach ($arrExportFields as $Field_Name) {
			$RowName 	= $this->GetExcelColumnName($i);
			
			$ColTitle  	= $arrReportFields[$Field_Name];
			$objPHPExcel->getActiveSheet()->setCellValue($RowName.$j,$ColTitle);
			
			$i++;
		}
		$j++;

		$applicationData_output_array = json_decode(json_encode($applicationData_output), true);
		foreach($applicationData_output_array as $key=>$application_data) {
			$this->WriteReReportData($objPHPExcel,$j,$application_data);
			$j++;
		}
		
		$objPHPExcel->getActiveSheet()->setTitle('Re MIS Data');
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);

	 	$fileName=time().'.xlsx';

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename='.$fileName);
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 2024 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
	    	
    }
   

}