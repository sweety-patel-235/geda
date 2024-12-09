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

class ApplyOnlinesController extends AppController
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
		$this->loadComponent('PhpExcel');
		$this->set('Userright',$this->Userright);
    }

	/*
	 * Displays a index
	 *
	 * @param mixed What page to display
	 * @return void
	 */
	public function index() {

		$this->intCurAdminUserRight = $this->Userright->LIST_APPLYONLINES;
		$this->setAdminArea();

		if (!empty($this->ApplyOnlines->validate)) {
			foreach ($this->ApplyOnlines->validate as $field => $rules) {
				$this->ApplyOnlines->validator()->remove($field); //Remove all validation in search page
			}
		}

		$arrcustomerList	= array();
		$arrCondition		= array();
		$this->SortBy		= "ApplyOnlines.id";
		$this->Direction	= "DESC";
		$this->intLimit		= isset($this->request->data['length']) ? $this->request->data['length'] : PAGE_RECORD_LIMIT;
		$start_page 		= isset($this->request->data['start']) ? $this->request->data['start'] : 1;
		$this->CurrentPage  = $start_page;
		$option 			= array();
		$sortArr 			= array();
		$option['order_by'] = "'order': [[ 0, 'desc' ]]";
		$option['colName']  = array('id','customer_id','customer_type','email','pv_capacity','city','state','created','action');
		$sortArr=array('customer_type'=>'parameters.para_value');
		$this->SetSortingVars('ApplyOnlines',$option,$sortArr);
		$arrCondition		= $this->_generateCustomerSearchCondition();
		$arr_date_search=array();
		if(array_key_exists('date_search', $arrCondition))
		{
			$arr_date_search=$arrCondition['date_search'];
			unset($arrCondition['date_search']);
		}
		$query_data		= $this->ApplyOnlines->find('all',array(
									'fields'=>[	'ApplyOnlines.id','customers.name','parameters.para_value','ApplyOnlines.city',
												'ApplyOnlines.state','ApplyOnlines.created','ApplyOnlines.pv_capacity','ApplyOnlines.email'],
									'join'=>[['table'=>'customers','type'=>'INNER','conditions'=>'ApplyOnlines.customer_id = customers.id'],
									['table'=>'parameters','type'=>'LEFT','conditions'=>'ApplyOnlines.category = parameters.para_id']],
									'conditions' => $arrCondition,
									'order'=>array($this->SortBy => $this->Direction),
									'page'=> $this->CurrentPage,
									'limit' => $this->intLimit));
		if(!empty($arr_date_search))
		{
			$fields_date = $arr_date_search['between'][0];
			$StartTime  = $arr_date_search['between'][1];
   			$EndTime    = $arr_date_search['between'][2];
            $query_data->where([function ($exp, $q) use ($StartTime, $EndTime,$fields_date) {
			return $exp->between($fields_date, $StartTime, $EndTime);
       		 }]);
		}
		$this->paginate['limit'] 	= $this->intLimit;
		$this->paginate['page'] 	= ($start_page/$this->paginate['limit'])+1;
		$arr_parameters 			= $this->Parameters->GetParameterList(3)->toArray();
		$arrcustomerList			= $this->paginate($query_data);
		$usertypes 					= array();
		$option['dt_selector']		= 'table-example';
		$option['formId']			= 'formmain';
		$option['url']				= WEB_ADMIN_PREFIX.'ApplyOnlines';
		$JqdTablescr 				= $this->JqdTable->create($option);
		$this->set('arrcustomerList',$arrcustomerList->toArray());
		$this->set('JqdTablescr',$JqdTablescr);
		$this->set('period',$this->period);
		$this->set('customer_type_list',$this->Parameters->GetParameterList(3));
		$this->set('customer_list_list',$this->Customers->GetCustomernameList());
		$this->set('limit',$this->intLimit);
		$this->set("CurrentPage",$this->CurrentPage);
		$this->set("SortBy",$this->SortBy);
		$this->set("Direction",$this->Direction);
		$this->set("page_count",(isset($this->request->params['paging']['ApplyOnlines']['pageCount'])?$this->request->params['paging']['ApplyOnlines']['pageCount']:0));
		$out = array();
		$blnEditApplyOnlinesRights		= $this->Userright->checkadminrights($this->Userright->LIST_APPLYONLINES);
		foreach($arrcustomerList->toArray() as $key => $val) {
			$temparr = array();
			foreach($option['colName'] as $key) {
				if($key == 'customer_id') {
					$temparr[$key] = $val['customers']['name'];
				}
				else if($key == 'customer_type') {
					$temparr[$key] = ucfirst($val['parameters']['para_value']);
				} else if(isset($val[$key])) {
					if($key == 'created')
						$temparr[$key] = date("d-m-Y H:i:s",strtotime($val[$key]));
					else
						$temparr[$key] = $val[$key];
				} else {
					$temparr[$key]='';
				}
				if($key == 'action') {
					if($key=='action') {
						$temparr['action']='';
						if($blnEditApplyOnlinesRights){
								$temparr['action'].= $this->Userright->linkListProjects(constant('WEB_URL').constant('ADMIN_PATH').'ApplyOnlines/view/'.encode($val['id']),'<i class="fa fa-eye"></i>','',' target="_blank" title = "View Apply Online"')."&nbsp;";
								$temparr['action'].= $this->Userright->linkListProjects(constant('WEB_URL').constant('ADMIN_PATH').'ApplyOnlines/preview_subsidy_approval_letter/'.encode($val['id']),'<i class="fa fa-download"></i>','',' title = "Download Apply Online"')."&nbsp;";
						}
					}
				}
			}
			$out[] = $temparr;
		}
		if ($this->request->is('ajax')){
			header('Content-type: application/json');
			echo json_encode(array('condi'=>$arrCondition,"draw" => intval($this->request->data['draw']),
			"recordsTotal"    => intval( $this->request->params['paging']['ApplyOnlines']['count']),
			"recordsFiltered" => intval( $this->request->params['paging']['ApplyOnlines']['count']),
			"data"            => $out));
			die;
		}
	}

	/**
	 *
	 * _generateCustomerSearchCondition
	 *
	 * Behaviour : Private
	 *
	 * @param : $id  : Id is use to identify for which user condition to be generated if its not null
	 * @defination : Method is use to generate search condition using which admin user data can be listed
	 *
	 */
	private function _generateCustomerSearchCondition($id=null)
	{
		$arrCondition	= array();
		$blnSinCompany	= true;
		if(!empty($id)) $this->request->data['ApplyOnlines']['id'] = $id;

		if(isset($this->request->data) && count($this->request->data)>0)
        {
            if(isset($this->request->data['ApplyOnlines']['id']) && trim($this->request->data['ApplyOnlines']['id'])!='')
            {
                $strID = trim($this->request->data['ApplyOnlines']['id'],',');
                $arrCondition['ApplyOnlines.id'] = $this->request->data['ApplyOnlines']['id'];
            }

			if(isset($this->request->data['ApplyOnlines']['email']) && $this->request->data['ApplyOnlines']['email']!='')
            {
                $arrCondition['ApplyOnlines.email LIKE'] = '%'.$this->request->data['ApplyOnlines']['email'].'%';
            }
            if(isset($this->request->data['ApplyOnlines']['customer_type']) && $this->request->data['ApplyOnlines']['customer_type']!='')
            {
                $arrCondition['ApplyOnlines.customer_type'] = $this->request->data['ApplyOnlines']['customer_type'];
            }
            if(isset($this->request->data['ApplyOnlines']['customer_id']) && $this->request->data['ApplyOnlines']['customer_id']!='')
            {
                $arrCondition['ApplyOnlines.customer_id'] = $this->request->data['ApplyOnlines']['customer_id'];
            }
			if(isset($this->request->data['ApplyOnlines']['mobile']) && $this->request->data['ApplyOnlines']['mobile']!='')
            {
                $arrCondition['ApplyOnlines.mobile LIKE'] = '%'.$this->request->data['ApplyOnlines']['mobile'].'%';
            }
			if(isset($this->request->data['ApplyOnlines']['city']) && $this->request->data['ApplyOnlines']['city']!='')
            {
                $arrCondition['ApplyOnlines.city LIKE'] = '%'.$this->request->data['ApplyOnlines']['city'].'%';
            }
            if(isset($this->request->data['ApplyOnlines']['state']) && $this->request->data['ApplyOnlines']['state']!='')
            {
                $arrCondition['ApplyOnlines.state LIKE'] = '%'.$this->request->data['ApplyOnlines']['state'].'%';
            }
            if(isset($this->request->data['ApplyOnlines']['search_date']) && $this->request->data['ApplyOnlines']['search_date']!='')
            {
                if($this->request->data['ApplyOnlines']['search_period'] == 1 || $this->request->data['ApplyOnlines']['search_period'] == 2)
                {
                	$arrSearchPara	= $this->ApplyOnlines->setSearchDateParameter($this->request->data['ApplyOnlines']['search_period'],$this->modelClass);

                	$this->request->data[$this->modelClass] = array_merge($this->request->data[$this->modelClass],$arrSearchPara[$this->modelClass]);
                    $this->dateDisabled						= true;
                }
                $arrperiodcondi = $this->ApplyOnlines->findConditionByPeriod( $this->request->data['ApplyOnlines']['search_date'],
																		$this->request->data['ApplyOnlines']['search_period'],
																		$this->request->data['ApplyOnlines']['DateFrom'],
																		$this->request->data['ApplyOnlines']['DateTo'],
																		$this->Session->read('ApplyOnlines.timezone'));
               	if(!empty($arrperiodcondi)){
                	$arrCondition['date_search']=$arrperiodcondi;
                }
            }
		}
		return $arrCondition;
	}
	/**
	 *
	 * view
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to view installer
	 *
	 */
	public function view($id= null)
	{
		if(empty($id)) {
			$this->Flash->error('Please Select Valid Apply Onlines.');
			return $this->redirect(WEB_ADMIN_PREFIX.'/ApplyOnlines');
		} else {
			$encode_id = $id;
			$id=intval(decode($id));
			$applyOnlinesData = $this->ApplyOnlines->viewApplication($id);
			$applyOnlinesDataDocList = $this->ApplyonlinDocs->find("all",['conditions'=>['application_id'=>$id,'doc_type'=>'others']])->toArray();
			$Applyonlinprofile  = $this->ApplyonlinDocs->find('all',['conditions'=>['application_id'=>$id,'doc_type'=>'profile']])->first();
			$GetProjectEstimation 		= $this->GetProjectEstimation($id);
		}
		$discom_list = $this->BranchMasters->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['BranchMasters.parent_id'=>'0']])->toArray();
		$payumoney_data = $this->Payumoney->find('all',['fields'=>array('Payumoney.transaction_id','Payumoney.payment_date'),'join'=>[
				        'ap' => [
				            'table' => 'applyonline_payment',
				            'type' => 'INNER',
				            'conditions' => ['Payumoney.id = ap.payment_id']
		            	]]])->where(['ap.application_id' => $id])->first();
		$transaction_id='';
		$payment_date='';
		if(!empty($payumoney_data))
		{
			$transaction_id=($payumoney_data->transaction_id);
			$payment_date=(!empty($payumoney_data->payment_date) ? date(LIST_DATE_FORMAT,strtotime($payumoney_data->payment_date)) : '');
		}
		$this->set("APPLY_ONLINE_MAIN_STATUS",$this->ApplyOnlineApprovals->apply_online_main_status);
		$this->set("discom_list",$discom_list);
		$this->set('applyOnlinesData',$applyOnlinesData);
		$this->set('ApplyOnlines',$applyOnlinesData);
		$this->set("applyOnlinesDataDocList",$applyOnlinesDataDocList);
		$this->set("GetProjectEstimation",$GetProjectEstimation);
		$this->set('transaction_id',$transaction_id);
		$this->set('payment_date',$payment_date);
		$this->set('Applyonlinprofile',$Applyonlinprofile);
		$this->set("APPLY_ONLINE_GUJ_STATUS",$this->ApplyOnlineApprovals->apply_online_guj_status);
	}

	/**
	 * save
	 * Behaviour : Public
	 * @defination : Method is use to save aplication
	 */
	public function save()
	{
		$this->autoRender 	= false;
		$customerId			= $this->ApiToken->customer_id;

		if(!empty($customerId)) {
			$applyDataEntity	= $this->ApplyOnlines->newEntity($this->request->data);
			$applyDataEntity->customer_id 	= $customerId;
			$applyDataEntity->created 		= $this->now();
			$this->ApplyOnlines->save($applyDataEntity);
			$type = "ok";
			$msg = "You have apply successfully.";
		} else {
			$type = "error";
			$msg = "Customer not found.";
		}

		$this->ApiToken->SetAPIResponse('type', $type);
		$this->ApiToken->SetAPIResponse('msg', $msg);

		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	/*
	 * Displays a index
	 *
	 * @param mixed What page to display
	 * @return void
	 */
	public function app_save() {
		$this->autoRender = false;
		$id 		= isset($this->request->data['id']) ? $this->request->data['id'] : '';
		$customerId = $this->ApiToken->customer_id;
		$installerdata	= $this->Customers->find('all', array('conditions'=>array('id'=>$customerId)))->first();
		$installer_id 	= (isset($installerdata['installer_id'])?$installerdata['installer_id']:0);
		if(isset($customerId) && !empty($customerId)) {
			$this->layout 	= 'frontend';
			$project_id 	= isset($this->request->data['project_id'])?$this->request->data['project_id']:0;
			$is_exist 		= false;
			if ($project_id > 0 && empty($id)) {
				$app_cnt = $this->ApplyOnlines->find("all",['fields'=>['id'],'conditions'=>['ApplyOnlines.project_id'=>$project_id]])->toArray();
				if (!empty($app_cnt)) {
					$is_exist = true;
				}
			}
			if(empty($project_id) && empty($installer_id)) {
				$status = 'error';
				$message = 'Missing Project details for Applyonline submission.';
			}
			else if(isset($this->request->data) && !empty($this->request->data)) {
				if(isset($this->request->data['middle_name']) && !empty($this->request->data['middle_name']))
				{
					$last_name 							= $this->request->data['last_name'];
					$this->request->data['last_name'] 	= $this->request->data['middle_name'];
					$this->request->data['third_name'] 	= $last_name;
				}
				else
				{
					$this->request->data['middle_name'] 	= $this->request->data['last_name'];
				}
					$this->ApplyOnlines->data['ApplyOnlines'] 	= $this->request->data;
					if(!empty($id)){
						$ApplyOnlineData 					= $this->ApplyOnlines->get($id);
						$ApplyOnlinesEntity					= $this->ApplyOnlines->patchEntity($ApplyOnlineData,$this->request->data,['validate'=>'add']);

					}else{
						$ApplyOnlinesEntity			= $this->ApplyOnlines->newEntity($this->request->data,['validate'=>'add']);
						$ApplyOnlinesEntity->created 					= $this->now();
					}
					if(!$ApplyOnlinesEntity->errors()) {
						$ApplyOnlinesEntity->customer_id 				= $customerId;
						$ApplyOnlinesEntity->project_id 				= $project_id;
						$ApplyOnlinesEntity->modified 		            = $this->NOW();
							$approval=$this->ApplyOnlineApprovals->Approvalstage($ApplyOnlinesEntity->id);
							$applyOnline 			= $this->ApplyOnlines->find();
							$total_application 		= $applyOnline->select(['total_pvcapacity' => $applyOnline->func()->sum('pv_capacity')])->where(array('installer_id'=>$ApplyOnlinesEntity->installer_id))->first();
							$installerCapacityTotal = $total_application->total_pvcapacity;
							$availableCapacityData 	= $this->InstallerCategoryMapping->find('all',['fields'=>['installer_category.capacity'],'join'=>[['table'=>'installer_category','type'=>'left','conditions'=>'InstallerCategoryMapping.category_id = installer_category.id']],'conditions'=>['InstallerCategoryMapping.installer_id'=>$ApplyOnlinesEntity->installer_id]])->toArray();

							if(!empty($availableCapacityData) && $installerCapacityTotal>$availableCapacityData[0]['installer_category']['capacity'])
							{
								$ApplyOnlinesEntity->application_status = $this->ApplyOnlineApprovals->WAITING_LIST;
							}
							else if(!in_array(29,$approval))
							{
								$ApplyOnlinesEntity->application_status = $this->ApplyOnlineApprovals->APPLICATION_GENERATE_OTP;
							}
							else
							{
								$approval = $this->ApplyOnlineApprovals->find('all',array('conditions'=>array('application_id'=>$ApplyOnlinesEntity->id)))->last();
								$ApplyOnlinesEntity->application_status = $approval->stage;
							}

						$ApplyOnlinesEntity->division 					= isset($this->request->data['discom_name'])?$this->request->data['discom_name']:0;
						$ApplyOnlinesEntity->aadhar_no_or_pan_card_no	= passencrypt($this->request->data['aadhar_no_or_pan_card_no']);
						$ApplyOnlinesEntity->pan_card_no 				= passencrypt($this->request->data['pan_card_no']);
						$ApplyOnlinesEntity->house_tax_holding_no 		= passencrypt($this->request->data['house_tax_holding_no']);
						$ApplyOnlinesEntity->lattitue 					= isset($this->request->data['latitude'])?$this->request->data['latitude']:'';
						$ApplyOnlinesEntity->mobile                     = isset($this->request->data['consumer_mobile'])?$this->request->data['consumer_mobile']:0;
						$ApplyOnlinesEntity->email                      = isset($this->request->data['consumer_email'])?$this->request->data['consumer_email']:0;
						if (!empty($ApplyOnlinesEntity->division)) {
							$arrDiscom 					= $this->DiscomMaster->GetDiscomHirarchyByID($ApplyOnlinesEntity->discom_name);
							$ApplyOnlinesEntity->area 	= $arrDiscom->area;
							$ApplyOnlinesEntity->circle = $arrDiscom->circle;
						}
						if(!empty($id)){
							$ApplyOnlineData 		    = $this->ApplyOnlines->get($id);
							$ApplyOnlinesEntity->project_id = $ApplyOnlineData->project_id;
						}
						$project_details = $this->Projects->find('all',array('conditions'=>array('id'=>$project_id)))->first();
						if(!empty($project_details)){
							$ApplyOnlinesEntity->roof_of_proposed = $project_details->area;
							$ApplyOnlinesEntity->energy_con       = $project_details->estimated_kwh_year;
							$ApplyOnlinesEntity->bill             = $project_details->avg_monthly_bill;
							$ApplyOnlinesEntity->social_consumer  = $project_details->project_social_consumer;
							$ApplyOnlinesEntity->common_meter	  = $project_details->project_common_meter;
						}
						if($this->ApplyOnlines->save($ApplyOnlinesEntity))
						{
							$id = $ApplyOnlinesEntity->id;
							$application_no 			= $this->ApplyOnlines->GenerateApplicationNo($ApplyOnlinesEntity);
							$this->ApplyOnlines->updateAll(array('application_no'=>$application_no),array('id'=>$id));
							$project_ins_id 			= $project_id;
							if(empty($project_id))
							{
								$project_msg_data 		= $this->CreateMyProject($ApplyOnlinesEntity->id,true);
								$project_ins_id 		= $project_msg_data['proj_id'];
								if($project_ins_id != '')
								{
									$this->ApplyOnlines->updateAll(['project_id' => $project_ins_id], ['id' => $ApplyOnlinesEntity->id]);
								}
							}
							$this->ApplyOnlines->updateAll(['payment_status' => '0'], ['id' => $ApplyOnlinesEntity->id]);
							$approval=$this->ApplyOnlineApprovals->Approvalstage($ApplyOnlinesEntity->id);
							if($ApplyOnlinesEntity->application_status!=$this->ApplyOnlineApprovals->WAITING_LIST && !in_array(29,$approval)){

								$sms_mobile = $ApplyOnlinesEntity->installer_mobile;
								if($installer_id > 0)
								{
									$sms_mobile = $ApplyOnlinesEntity->consumer_mobile;
								}
								$sms_message = str_replace('##application_no##',              $ApplyOnlinesEntity->application_no, OTP_VERIFICATION);
								$this->ApplyOnlines->SendSMSActivationCode($ApplyOnlinesEntity->id,$sms_mobile,$sms_message);
							}
							$customerData       		= $this->Customers->find('all', array('conditions'=>array('id'=>$customerId)))->first();
   							$cus_installer_id           = (isset($customerData['installer_id'])?$customerData['installer_id']:0);

						$message = 'Your Aplication Form Submitted Successfully.';
						$status = 'ok';
						$this->ApiToken->SetAPIResponse('application_id',$ApplyOnlinesEntity->id);
						$this->ApiToken->SetAPIResponse('encoded_application_id',encode($ApplyOnlinesEntity->id));
						}
					} else if($this->ismobile() || (isset($this->request->data['mobile_type']) && $this->request->data['mobile_type'] == 1)) {
						$errors = $ApplyOnlinesEntity->errors();
						$message = 'Some field are required '.json_encode($errors);
						if(array_key_exists('consumer_no', $errors))
						{
							$message = implode(", ",$errors['consumer_no']);
						}
						elseif(array_key_exists('consumer_mobile', $errors))
						{
							$message = implode(", ",$errors['consumer_mobile']);
						}
						elseif(array_key_exists('transmission_line', $errors))
						{
							$message = implode(", ",$errors['transmission_line']);
						}
						elseif(array_key_exists('sanction_load_contract_demand', $errors))
						{
							$message = implode(", ",$errors['sanction_load_contract_demand']);
						}
						elseif(array_key_exists('profile_image', $errors))
						{
							$message = $errors['profile_image']['_empty'];
						}
						elseif(array_key_exists('pv_capacity', $errors))
						{
							$message = implode(", ",$errors['pv_capacity']);
						}
						elseif(array_key_exists('capexmode', $errors))
						{
							$message = $errors['capexmode']['_empty'];
						}
						elseif(array_key_exists('tno', $errors))
						{
							$message = implode(", ",$errors['tno']);
						}
						elseif(array_key_exists('house_tax_holding_no', $errors))
						{
							$message = $errors['house_tax_holding_no']['_empty'];
						}
						elseif(array_key_exists('file_attach_latest_receipt', $errors))
						{
							$message = $errors['file_attach_latest_receipt']['_empty'];
						}
						elseif(array_key_exists('net_meter', $errors))
						{
							$message = implode(", ",$errors['net_meter']);
						}
						elseif(array_key_exists('payment_gateway', $errors))
						{
							$message = $errors['payment_gateway']['_empty'];
						}
						$status = 'error';
					}
				} else {
					$message = 'Missing details for Applyonline submission.';
					$status = 'error';
					if (empty($project_id)) {
						$message = 'Missing Project details for Applyonline submission.';
					} else if ($is_exist) {
						$message = 'Application is already been submitted for selected project.';
					 }
				}
			} else {
				$message = 'customer id not found';
				$status = 'error';
			}
			$this->ApiToken->SetAPIResponse('msg', $message);
			$this->ApiToken->SetAPIResponse('type', $status);
			echo $this->ApiToken->GenerateAPIResponse();
			exit;
		}
	/*
	 * API for save document of applayonline from
	 *
	 * @param mixed What page to display
	 * @return void
	*/
	public function uploadAplicationDoc() {
		if(!empty($this->request->data) && isset($this->request->data['id']) && !empty($this->request->data['id'])) {
				$ApplyOnlineGet = $this->ApplyOnlines->get($this->request->data['id']);
				$ApplyOnlinesEntity = $this->ApplyOnlines->patchEntity($ApplyOnlineGet,$this->request->data);

			$image_path = APPLYONLINE_PATH.$ApplyOnlinesEntity->id.'/';

			if(!file_exists(APPLYONLINE_PATH.$ApplyOnlinesEntity->id)){
				@mkdir(APPLYONLINE_PATH.$ApplyOnlinesEntity->id, 0777,true);
			}

			if(!empty($this->request->data['file_attach_photo_scan_of_aadhar'])) {
				$db_attach_photo_scan_of_aadhar = $ApplyOnlinesEntity->attach_photo_scan_of_aadhar;
				if(file_exists($image_path.$db_attach_photo_scan_of_aadhar)){
					@unlink($image_path.$db_attach_photo_scan_of_aadhar);
					@unlink($image_path.'r_'.$db_attach_photo_scan_of_aadhar);
				}
				$file_name = $this->file_upload($image_path,$this->request->data['file_attach_photo_scan_of_aadhar'],true,65,65,$image_path,'aadhar');
				$this->ApplyOnlines->updateAll(['attach_photo_scan_of_aadhar' => $file_name], ['id' => $ApplyOnlinesEntity->id]);
			}
			if(!empty($this->request->data['file_attach_recent_bill'])) {
				$db_attach_recent_bill = $ApplyOnlinesEntity->attach_recent_bill;
				if(file_exists($image_path.$db_attach_recent_bill)){
					@unlink($image_path.$db_attach_recent_bill);
					@unlink($image_path.'r_'.$db_attach_recent_bill);
				}
				$file_name = $this->file_upload($image_path,$this->request->data['file_attach_recent_bill'],true,65,65,$image_path,'recent');

				$this->ApplyOnlines->updateAll(['attach_recent_bill' => $file_name], ['id' => $ApplyOnlinesEntity->id]);
			}
			if(!empty($this->request->data['file_attach_latest_receipt'])) {
				$db_attach_recent_bill = $ApplyOnlinesEntity->attach_latest_receipt;
				if(file_exists($image_path.$db_attach_recent_bill)){
					@unlink($image_path.$db_attach_recent_bill);
					@unlink($image_path.'r_'.$db_attach_recent_bill);
				}
				$file_name = $this->file_upload($image_path,$this->request->data['file_attach_latest_receipt'],true,65,65,$image_path,'tax_receipt_');

				$this->ApplyOnlines->updateAll(['attach_latest_receipt' => $file_name], ['id' => $ApplyOnlinesEntity->id]);
			}
			if(!empty($this->request->data['file_attach_pan_card_scan']) && !empty($this->request->data['file_attach_pan_card_scan']['name']) ) {
				$db_attach_pan_card_scan = $ApplyOnlinesEntity->attach_pan_card_scan;
				if(file_exists($image_path.$db_attach_pan_card_scan)){
					@unlink($image_path.$db_attach_pan_card_scan);
					@unlink($image_path.'r_'.$db_attach_pan_card_scan);
				}
				$file_name = $this->file_upload($image_path,$this->request->data['file_attach_pan_card_scan'],true,65,65,$image_path,'pan_');

				$this->ApplyOnlines->updateAll(['attach_pan_card_scan' => $file_name], ['id' => $ApplyOnlinesEntity->id]);
			}

			if(!empty($this->request->data['aplication_doc_file1'])) {
				$doc_id 	= isset($this->request->data['doc_id']) ? $this->request->data['doc_id'] : '';
				if(!empty($doc_id)){
					$getEntity = $this->ApplyonlinDocs->get($this->request->data['doc_id']);
					$ApplyonlinDocsEntity = $this->ApplyOnlines->patchEntity($getEntity,$this->request->data);
					$ApplyonlinDocsEntity->id =$doc_id;
				}
				else{
					$ApplyonlinDocsEntity = $this->ApplyonlinDocs->newEntity();
				}
				$db_file_name = $ApplyonlinDocsEntity->file_name;
					if(file_exists($image_path.$db_file_name)){
						@unlink($image_path.$db_file_name);
						@unlink($image_path.'r_'.$db_file_name);
					}
					$file_name = $this->file_upload($image_path,$this->request->data['aplication_doc_file1'],true,65,65,$image_path,'doc_0');

					$ApplyonlinDocsEntity->file_name 		= $file_name;
					$ApplyonlinDocsEntity->doc_type 		= 'others';
				    $ApplyonlinDocsEntity->application_id 	= $ApplyOnlinesEntity->id;
				    $ApplyonlinDocsEntity->created 			= $this->NOW();

	                $this->ApplyonlinDocs->save($ApplyonlinDocsEntity);
			}
			if(!empty($this->request->data['aplication_doc_file2'])) {
				$doc_id 	= isset($this->request->data['doc_id']) ? $this->request->data['doc_id'] : '';
				if(!empty($doc_id)){
					$getEntity = $this->ApplyonlinDocs->get($this->request->data['doc_id']);
					$ApplyonlinDocsEntity = $this->ApplyOnlines->patchEntity($getEntity,$this->request->data);
					$ApplyonlinDocsEntity->id =$doc_id;
				}
				else{
					$ApplyonlinDocsEntity = $this->ApplyonlinDocs->newEntity();
				}
				$db_file_name = $ApplyonlinDocsEntity->file_name;
					if(file_exists($image_path.$db_file_name)){
						@unlink($image_path.$db_file_name);
						@unlink($image_path.'r_'.$db_file_name);
					}
					$file_name = $this->file_upload($image_path,$this->request->data['aplication_doc_file2'],true,65,65,$image_path,'doc_1');

					$ApplyonlinDocsEntity->file_name 		= $file_name;
					$ApplyonlinDocsEntity->doc_type 		= 'others';
				    $ApplyonlinDocsEntity->application_id 	= $ApplyOnlinesEntity->id;
				    $ApplyonlinDocsEntity->created 			= $this->NOW();
	                $this->ApplyonlinDocs->save($ApplyonlinDocsEntity);
			}
			if(!empty($this->request->data['profile_image'])) {
				$getEntity  = $this->ApplyonlinDocs->find('all',array('conditions'=>array('application_id'=>$this->request->data['id'],'doc_type'=>'profile')))->first();
				if(!empty($getEntity)){
	                $ApplyonlinDocsEntity = $this->ApplyonlinDocs->patchEntity($getEntity,array());
				}
				else{
					$ApplyonlinDocsEntity = $this->ApplyonlinDocs->newEntity();
				}

				$db_file_name = $ApplyonlinDocsEntity->file_name;
					if(file_exists($image_path.$db_file_name)){
						@unlink($image_path.$db_file_name);
						@unlink($image_path.'r_'.$db_file_name);
					}
					$file_name = $this->file_upload($image_path,$this->request->data['profile_image'],true,65,65,$image_path,'profile_');

					$ApplyonlinDocsEntity->file_name 		= $file_name;
					$ApplyonlinDocsEntity->doc_type 		= 'profile';
				    $ApplyonlinDocsEntity->application_id 	= $ApplyOnlinesEntity->id;
				    $ApplyonlinDocsEntity->created 			= $this->NOW();
	                $this->ApplyonlinDocs->save($ApplyonlinDocsEntity);
			}
			if(!empty($this->request->data['upload_signed_doc'])) {

	               $ApplyonlinDocsEntity = $this->ApplyonlinDocs->newEntity();
			       $db_file_name = $ApplyonlinDocsEntity->file_name;
					if(file_exists($image_path.$db_file_name)){
						@unlink($image_path.$db_file_name);
						@unlink($image_path.'r_'.$db_file_name);
					}
					$file_name = $this->file_upload($image_path,$this->request->data['upload_signed_doc'],true,65,65,$image_path,'doc_');

					$ApplyonlinDocsEntity->file_name 		= $file_name;
					$ApplyonlinDocsEntity->doc_type 		= 'Signed_Doc';
				    $ApplyonlinDocsEntity->application_id 	= $ApplyOnlinesEntity->id;
				    $ApplyonlinDocsEntity->title            = 'Upload_Document';
				    $ApplyonlinDocsEntity->created 			= $this->NOW();
	                $this->ApplyonlinDocs->save($ApplyonlinDocsEntity);

	                $application_status = $this->ApplyOnlineApprovals->APPLICATION_SUBMITTED;
	                $this->ApplyOnlines->updateAll(array('application_status'=>$application_status),array('id' => $ApplyOnlinesEntity->id));
	                $customer_id 		= $this->ApiToken->customer_id;
	                $this->ApplyOnlineApprovals->saveStatus($ApplyOnlinesEntity->id,$this->ApplyOnlineApprovals->APPLICATION_SUBMITTED,$customer_id,'');
	                if($ApplyOnlinesEntity->category == $this->ApplyOnlines->category_residental){
				    	$application_status = $this->ApplyOnlineApprovals->APPROVED_FROM_GEDA;
						$this->ApplyOnlines->updateAll(array('application_status'=>$application_status),array('id' => $ApplyOnlinesEntity->id));
				    	$this->ApplyOnlineApprovals->saveStatus($ApplyOnlinesEntity->id,$this->ApplyOnlineApprovals->APPROVED_FROM_GEDA,$customer_id,'');
				    	$applyOnlinesData 		= $this->ApplyOnlines->viewApplication($ApplyOnlinesEntity->id);
				    	$geda_application_no 	= $this->ApplyOnlines->GenerateGedaApplicationNo($applyOnlinesData);
						$this->ApplyOnlines->updateAll(array('geda_application_no'=>$geda_application_no),array('id'=>$ApplyOnlinesEntity->id));
					}
			}
			if(!empty($this->request->data['Self_Certificate'])) {

	               $ApplyonlinDocsEntity = $this->ApplyonlinDocs->newEntity();
			       $db_file_name = $ApplyonlinDocsEntity->file_name;
					if(file_exists($image_path.$db_file_name)){
						@unlink($image_path.$db_file_name);
						@unlink($image_path.'r_'.$db_file_name);
					}
					$file_name = $this->file_upload($image_path,$this->request->data['Self_Certificate'],true,65,65,$image_path,'doc_');

					$ApplyonlinDocsEntity->file_name 		= $file_name;
					$ApplyonlinDocsEntity->doc_type 		= 'Self_Certificate';
				    $ApplyonlinDocsEntity->application_id 	= $ApplyOnlinesEntity->id;
				    $ApplyonlinDocsEntity->title            = 'Self Certificate';
				    $ApplyonlinDocsEntity->created 			= $this->NOW();
				    $this->FesibilityReport->Cei_All_Stage_APProved($ApplyOnlinesEntity->id,'first');
	                $this->ApplyonlinDocs->save($ApplyonlinDocsEntity);
	        }
			$message = 'Application document upload';
			$status = 'ok';
			$this->ApiToken->SetAPIResponse('msg', $message);
			$this->ApiToken->SetAPIResponse('type', $status);
			echo $this->ApiToken->GenerateAPIResponse();
			exit;
		} else {

			$message = 'Error in application online document upload';
			$status = 'error';
			$this->ApiToken->SetAPIResponse('msg', $message);
			$this->ApiToken->SetAPIResponse('type', $status);
			echo $this->ApiToken->GenerateAPIResponse();
			exit;
		}
	}

	/*
	 * API for get getBillCategory of applayonline from
	 *
	 * @param mixed What page to display
	 * @return void
	*/
	public function getBillCategory(){
		$BillCategoryList 		= $this->Parameters->GetParameterList(3);
		$var_data 				= array();
		$branchmaster_list 		= array();
		$subdivision  			= array();
		$seldivision 			= '';
		$seldiscom 				= '';
		$data_subdiv			= array();
		$data_subdiv['first_name'] 			= '';
		$data_subdiv['middle_name'] 		= '';
		$data_subdiv['last_name'] 			= '';
		$data_subdiv['address1'] 			= '';
		$data_subdiv['sanction_load'] 		= '';
		$data_subdiv['category'] 			= '';
		$data_subdiv['transmission_line']	= '';
		$data_subdiv['address2']			= '';
		$data_subdiv['state']				= '';
		$data_subdiv['city']				= '';
		$data_subdiv['pincode']				= '';
		$data_subdiv['circle_api']			= '';
		$data_subdiv['division_api']		= '';
		$data_subdiv['sub_division_api']	= '';
		$data_subdiv['success']             = '0';
		$data_subdiv['response_msg'] 		= '';
		$data_flag_api 			= '0';
		$consumer_no 			= isset($this->request->data['consumer_no'])?$this->request->data['consumer_no']:0;
		$tno 					= isset($this->request->data['tno'])?$this->request->data['tno']:0;
		$sel_installer_id 		= isset($this->request->data['sel_installer_id'])?$this->request->data['sel_installer_id']:0;
		$type_modules[] 		= array("id"=>0,"text"=>"");
		$type_inverters[] 		= array("id"=>0,"text"=>"");
		$make_inverters[]		= array("id"=>0,"text"=>"");
		$customer_id 		= $this->ApiToken->customer_id;
		if(!empty($customer_id))
		{
		if(isset($this->request->data['discom_id']) && !empty($this->request->data['discom_id']) && empty($consumer_no) && $this->request->data['state']=='4')
		{
			$discom 			= $this->request->data['discom_id'];
			$branch_detail 		= $this->BranchMasters->find('all',array('conditions'=>array('id'=>$discom)))->first();
			$branchmaster_list	= $this->DiscomMaster->find("all",['fields'=>['id','title'],'conditions'=>['DiscomMaster.area'=>$branch_detail->discom_id,'DiscomMaster.type'=>3]])->toArray();
		}
		elseif(isset($this->request->data['state']) && !empty($this->request->data['state'])) {
			$branchmaster_list 	= $this->DiscomMaster->find("all",['fields'=>['id','title'],'conditions'=>['DiscomMaster.state_id'=>$this->request->data['state'],'DiscomMaster.type'=>3]])->toArray();
		}
		if(isset($this->request->data['state']) && $this->request->data['state']=='4')
		{
			foreach($this->Installation->TYPE_MODULES as $key=>$val)
			{
				$type_modules[$key-1]['id'] 	= $key;
				$type_modules[$key-1]['text'] 	= $val;
			}
			foreach($this->Installation->TYPE_INVERTERS as $key=>$val)
			{
				$type_inverters[$key-1]['id'] 	= $key;
				$type_inverters[$key-1]['text'] = $val;
			}
			foreach($this->Installation->MAKE_INVERTERS as $key=>$val)
			{
				$make_inverters[$key-1]['id'] 	= $key;
				$make_inverters[$key-1]['text'] = $val;
			}
		}
		$discom_details 	= array();

		if (!empty($consumer_no) && $this->request->data['state']=='4' && isset($this->request->data['discom_id']) && !empty($this->request->data['discom_id'])) {
			$discom_id  = $this->request->data['discom_id'];
			$arr_output = $this->ThirdpartyApiLog->searchConsumerApi($consumer_no,$this->request->data['discom_id'],'0','0',$tno);

			$data_subdiv['success']             = '1';
			if(!empty($arr_output))
			{
				$flag_disp_data 				= 0;
				if($discom_id != $this->ApplyOnlines->torent_ahmedabad && $discom_id != $this->ApplyOnlines->torent_surat)
				{
				$data_subdiv['success'] 		= $arr_output->P_OUT_STS_CD;
				$data_subdiv['response_msg'] 	= $arr_output->P_OUT_MSG_SERVER;
				if($arr_output->P_OUT_STS_CD == 1 || $arr_output->P_OUT_STS_CD == -1)
				{
						if(isset($arr_output->P_OUT_DATA->OUTPUT_DATA))
						{
					$output_details_obj 				= $arr_output->P_OUT_DATA->OUTPUT_DATA;
					$arr_name 							= explode(" ",$output_details_obj->NAME);
							$flag_disp_data 		= 1;
						}
						elseif(isset($arr_output->P_OUT_DATA))
						{
							$output_details_obj 	= $arr_output->P_OUT_DATA;
							$arr_name 							= explode(" ",$output_details_obj->NAME);
							$flag_disp_data 		= 1;
						}
					}
					else
					{
						$data_subdiv['success'] 		= '0';
						$data_subdiv['response_msg'] 	= 'Invalid Consumer Number.';
					}
				}
				else
				{
					if(isset($arr_output->P_OUT_DATA) && !empty($arr_output->P_OUT_DATA))
					{
						$output_details_obj 			= $arr_output->P_OUT_DATA;
						$data_subdiv['success'] 		= $output_details_obj->P_OUT_STS_CD;
						$data_subdiv['response_msg'] 	= $output_details_obj->P_OUT_MSG_SERVER;
						if(strtolower($arr_output->P_OUT_DATA->P_OUT_MSG_CLIENT)=='success')
						{
							$arr_name 							= explode(" ",$output_details_obj->NAME);
							$flag_disp_data 		= 1;
						}
					}
					else
					{
						$data_subdiv['success'] 		= '0';
						$data_subdiv['response_msg'] 	= 'Invalid Consumer Number.';
					}
				}
				if($flag_disp_data == 1)
				{
					if(count($arr_name)>2)
					{
						$data_subdiv['first_name'] 			= $arr_name[0];
						$data_subdiv['middle_name'] 		= $arr_name[1];
						$data_subdiv['last_name'] 			= $arr_name[2];
					}
					else
					{
						$data_subdiv['first_name'] 			= $arr_name[0];
						$data_subdiv['last_name'] 			= $arr_name[1];
					}
					if($discom_id != $this->ApplyOnlines->torent_ahmedabad && $discom_id != $this->ApplyOnlines->torent_surat)
						{
							if(isset($output_details_obj->ADDRESS))
							{
					$data_subdiv['address1'] 			= $output_details_obj->ADDRESS;
								$data_subdiv['api_consumer_no'] = $arr_output->apirequest->P_IN_DATA->INPUT_DATA->cnsmr_no;
							}
							elseif(isset($output_details_obj->ADDRS))
							{
								$data_subdiv['address1'] 		= $output_details_obj->ADDRS;
								$data_subdiv['api_consumer_no'] = $consumer_no;
							}
						}
						else
						{
							$data_subdiv['address1'] 		= $output_details_obj->ADDRS;
							$data_subdiv['api_consumer_no'] = $consumer_no;
						}
					$data_subdiv['sanction_load'] 		= $output_details_obj->LOAD;
						$data_subdiv['category'] 			= $this->ThirdpartyApiLog->arr_category_map[strtoupper($output_details_obj->CATEGORY)];
					$data_subdiv['transmission_line']	= $this->ThirdpartyApiLog->arr_phase_map[$output_details_obj->PHASE];
					$data_subdiv['address2']			= $output_details_obj->TALUKA;
					$data_subdiv['district']			= $output_details_obj->DISTRICT;
					$data_subdiv['city']				= $output_details_obj->CITY;
					$data_subdiv['circle_api']			= $output_details_obj->CIRCLE;
					$data_subdiv['division_api']		= $output_details_obj->DIV;
					$data_subdiv['sub_division_api']	= $output_details_obj->SDO;
					$data_flag_api 						= '1';
				}
			}
			$subdivision_search 	= substr($consumer_no,0,3);
			if(!empty($data_subdiv['sub_division_api']))
			{
				$subdivision_search  		= $data_subdiv['sub_division_api'];
			}
			$arr_dis_details = array();
			if($discom_id != $this->ApplyOnlines->torent_ahmedabad && $discom_id != $this->ApplyOnlines->torent_surat)
			{
				$discom_details 		= $this->DiscomMaster->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['DiscomMaster.short_code'=>$subdivision_search,'DiscomMaster.type'=>4,'status'=>'1']]);
				$arr_dis_details 		= $discom_details->toarray();
			}
			elseif(!empty($data_subdiv['division_api']))
			{
				$discom_details 		= $this->DiscomMaster->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['DiscomMaster.short_code'=>$data_subdiv['division_api'],'DiscomMaster.type'=>4,'status'=>'1']]);
				$arr_dis_details 		= $discom_details->toarray();
			}
			$discom_details 		= $this->DiscomMaster->find("all",['conditions'=>['DiscomMaster.short_code'=>$subdivision_search,'DiscomMaster.type'=>4]])->first();
			if(!empty($arr_dis_details))
			{
				$subdivision[] 		= $arr_dis_details;
				$discom_data_details		= $this->DiscomMaster->find("all",['conditions'=>['id'=>key($arr_dis_details),'status'=>'1']])->first();
				$branchmaster_list 			= $this->DiscomMaster->find("all",['fields'=>['id','title'],'conditions'=>['circle'=>$discom_data_details->circle,'type'=>'3','status'=>'1']])->toArray();
				$data_subdiv['division'] 	= $branchmaster_list;
				$data_subdiv['seldivision']	= $discom_data_details->division;
				if(!empty($data_subdiv['division_api']))
				{
					$branchmaster_list 		= $this->DiscomMaster->find("all",['fields'=>['id','title'],'conditions'=>['DiscomMaster.short_code'=>$data_subdiv['division_api'],'DiscomMaster.type'=>3,'area'=>$discom_data_details->area,'circle'=>$discom_data_details->circle,'status'=>'1']])->toArray();
				}
				$discoms 			= $this->BranchMasters->find("all",['conditions'=>array('discom_id'=>$discom_data_details->area)])->first();

				$seldiscom			= $discoms['id'];
				$discoms 			= $this->BranchMasters->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>array('state'=>$this->request->data['state'])])->toArray();
			}
			else
			{
				$subdivision[] 	= array("id"=>0,"title"=>"--NO DISCOM--");
				$seldiscom	    = $discom_id;
			}
		}
		else
		{
			$subdivision[] 		= array("id"=>0,"title"=>"--NO DISCOM--");
		}
		if(!empty($BillCategoryList)) {
			foreach($BillCategoryList as $k=>$v){
				$var_data[] = ['id'=>$k,'name'=>$v];
			}
			$status = 'ok';
			$message = 'Bill Category List';
		}
		if(empty($branchmaster_list)) {
			$branchmaster_list = array();
		}
		$discom_arr = array();
		if(empty($discom_details))
		{
			$discoms 	= $this->BranchMasters->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['BranchMasters.status'=>'1','BranchMasters.parent_id'=>'0','BranchMasters.state'=>$this->request->data['state']]])->toArray();
		}
		if(empty($discoms)) {
			$discom_arr[] = array("id"=>0,"title"=>"--NO DISCOM--");
		} else {
			foreach($discoms as $id=>$title) {
				$discom_arr[] = array("id"=>$id,"title"=>$title);
			}
		}

		$customer_details 	= $this->Customers->find('all',array('conditions'=>array('id'=>$customer_id)))->first();
		$installercnt		= $this->Installers->find('all', array('conditions'=>array('id'=>$customer_details['installer_id'])))->count();
        $is_installer 		= ($installercnt>0)?'true':'false';

		$installer_email 	= '';
		$installer_mobile 	= '';
		$consumer_email 	= '';
		$consumer_mobile 	= '';

		$assign_slots           = array();
		if(isset($customer_details['installer_id']) && !empty($customer_details['installer_id']))
        {
  			$arr_condition      = array("installer_id" => $customer_details['installer_id']);
            $InstallerList      = TableRegistry::get('InstallerCategoryMapping');
            $arr_result         = $InstallerList->find('all',array('conditions'=>$arr_condition))->first();
            if(!empty($arr_result))
            {
                $arr_assign_band    = json_decode($arr_result['allowed_bands']);
                foreach($arr_assign_band as $value_band)
                {
                    $assign_slots[] = intval($this->ApplyOnlines->installer_slot_array[$value_band]['min']).' - '.intval($this->ApplyOnlines->installer_slot_array[$value_band]['max']).' kW';

                }

            }
        }
		if(($this->request->data['state']=='4' || strtolower($this->request->data['state'])=='gujarat') && $is_installer=='false' && $sel_installer_id>0)
		{
			$installer_details 	= $this->Installers->find('all',array('conditions'=>array('id'=>$sel_installer_id)))->first();
			$consumer_email 	= $customer_details->email;
			$consumer_mobile 	= $customer_details->mobile;
			$installer_email 	= $installer_details->email;
			$installer_mobile 	= $installer_details->mobile;
		}
		elseif(($this->request->data['state']=='4' || strtolower($this->request->data['state'])=='gujarat') && $is_installer=='true')
		{
			$installer_details 	= $this->Installers->find('all',array('conditions'=>array('id'=>$customer_details['installer_id'])))->first();
			$installer_email 	= $installer_details->email;
			$installer_mobile 	= $installer_details->mobile;
			$consumer_email 	= '';
			$consumer_mobile 	= '';
		}
		$assign_slot=implode(", ",$assign_slots);
		$this->ApiToken->SetAPIResponse('data', array('bill_category'=>$var_data,'discom_list'=>$branchmaster_list,'discoms'=>$discom_arr,'subdivision'=>$subdivision,'seldiscom'=>$seldiscom,'seldivision'=>$seldivision,'type_modules'=>$type_modules,'type_inverters'=>$type_inverters,'make_inverters'=>$make_inverters,'installer_email'=>$installer_email,'installer_mobile'=>$installer_mobile,'consumer_email'=>$consumer_email,'consumer_mobile'=>$consumer_mobile,'data_from_api'=>$data_subdiv,'data_flag_api'=>$data_flag_api,'pv_capacity_slots'=>$assign_slot));
		$this->ApiToken->SetAPIResponse('msg', $message);
		$this->ApiToken->SetAPIResponse('type', $status);
		}
		else
		{
			$status = "error";
			$this->ApiToken->SetAPIResponse('type', $status);
			$this->ApiToken->SetAPIResponse('msg', 'Invalid Customer Id!'.$customer_id);
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/*
	 * API for get paymentfeebycapacity of applayonline from
	 *
	 * @param mixed What page to display
	 * @return void
	*/
	public function paymentfeebycapacity(){
		$data 		= [];
		$check_amt  = 0;
		if(isset($this->request->data['capacity'])){
			$capacity 	= $this->request->data['capacity'];
			$pv_lt_50	= Configure::read('PV_CAPACITY_LT50');
			$pv_gt_50	= Configure::read('PV_CAPACITY_GT50');
			$jreda 		= 0;
			$var_data 	= [['discom'=>$pv_lt_50,'jreda'=>$jreda,'total'=>($pv_lt_50+$jreda),'is_payment_required'=>'0'],['discom'=>$pv_gt_50,'jreda'=>$jreda,'total'=>($pv_gt_50+$jreda),'is_payment_required'=>'0']];
			if($capacity > 50){
				$data 		= $var_data[1];
				$check_amt 	= $pv_gt_50+$jreda;
			} else {
				$data 		= $var_data[0];
				$check_amt 	= $pv_lt_50+$jreda;
			}
			$status = 'ok';
			$message = 'list of fee';
		}
		elseif(isset($this->request->data['category']))
		{
			$customer_category 	= $this->request->data['category'];
			$amt_government 	= Configure::read('APPLY_AMOUNT_GOVERNMENT');
			$amt_non_government = Configure::read('APPLY_AMOUNT_NON_GOVERNMENT');
			$amt_residental 	= Configure::read('APPLY_AMOUNT_RESIDENTIAL');
			$amt_gov_tax 		= Configure::read('APPLY_AMOUNT_GOV_TAX');
			$amt_non_gov_tax 	= Configure::read('APPLY_AMOUNT_NON_GOV_TAX');
			$amt_tax_percent 	= Configure::read('APPLY_TAX_PERCENT');
			if($customer_category == '3001'){
                $applicable_amt = $amt_residental;
                $tax_applicable = 0;

            }
            elseif($customer_category == '3004'){
                $applicable_amt = $amt_government;
                $tax_applicable = $amt_gov_tax;

            }
            else{
                $applicable_amt = $amt_non_government;
                $tax_applicable = $amt_non_gov_tax;
            }
            $tax_amount 		= $amt_tax_percent;
	        if($amt_tax_percent=='%')
	        {
	            $tax_amount 	= ($applicable_amt*$tax_applicable)/100;
	        }
            $var_data 			= [['discom'=>$applicable_amt,'jreda'=>$tax_amount,'total'=>($applicable_amt+$tax_amount),'is_payment_required'=>'0']];
            $check_amt 			= $applicable_amt+$tax_amount;
            $data 				= $var_data[0];
            $status 			= 'ok';
			$message 			= 'list of fee';
		}
		else {
			$status = 'error';
			$message = 'please pass capacity';
		}
		if($check_amt>0)
		{
			$data['is_payment_required'] = '1';
		}
		$this->ApiToken->SetAPIResponse('msg', $message);
		$this->ApiToken->SetAPIResponse('type', $status);
		$this->ApiToken->SetAPIResponse('data', $data);
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	/*
	 * API for get instoler list by state_id of applayonline from
	 *
	 * @param mixed What page to display
	 * @return void
	*/
	public function instoler_list_by_state_id(){
		$this->autoRender = false;
		$state = $this->request->data['state'];
		$installers_list = $this->Installers->find('all',['fields'=>['id','installer_name'],'join'=>[['table'=>'states','type'=>'inner','conditions'=>'states.statename = Installers.state']],'conditions'=>['Installers.stateflg'=>$state]])->toArray();
		$this->ApiToken->SetAPIResponse('msg', 'list of installers');
		$this->ApiToken->SetAPIResponse('data', $installers_list);
		$this->ApiToken->SetAPIResponse('type','ok');
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/*
	 * Send Application Letter To Customer
	 * @param mixed What page to display
	 * @return void
	 */
	private function SendApplicationLetterToCustomer($id=0)
	{
		$this->autoRender 			= false;
		$applyOnlinesData 			= $this->ApplyOnlines->viewApplication($id);
		$applyOnlinesData->aid 		= $this->ApplyOnlines->GenerateApplicationNo($applyOnlinesData);
		$LETTER_APPLICATION_NO 		= $applyOnlinesData->aid;
		$STATENAME 					= "";
		$arrState 	= $this->States->find("list",['keyField'=>'id','valueField'=>'statename','conditions'=>['States.id'=>$applyOnlinesData->apply_state]])->toArray();
		if(!empty($arrState) && isset($arrState[$applyOnlinesData->apply_state])) {
			$STATENAME = $arrState[$applyOnlinesData->apply_state];
		}
		$CUSTOMER_NAME 				= trim($applyOnlinesData->customer_name_prefixed." ".$applyOnlinesData->name_of_consumer_applicant);
		$EmailVars 					= array("LETTER_APPLICATION_NO"=>$LETTER_APPLICATION_NO,
											"CUSTOMER_NAME"=>$CUSTOMER_NAME,
											"STATENAME"=>$STATENAME);
		$to = '';
		if (!empty($applyOnlinesData->email)) {
			$to = $applyOnlinesData->email;
		} else {
			//$to = "kalpak@yugtia.com";
		}
		$email 		= new Email('default');
		$subject 	= Configure::read('EMAIL_ENV')."Submission of Rooftop Solar PV Application No - ".$LETTER_APPLICATION_NO;
	 	$email->profile('default');
		$email->viewVars($EmailVars);
		if($to!='')
		{
			$message_send = $email->template('application_submission_letter', 'default')
			->emailFormat('html')
			->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
		    ->to($to)
			->subject($subject)
		    ->send();
		}

		return true;
	}
	/**
	 *
	 * apply_online_listapi
	 *
	 * Behaviour : public
	 *
	 * @defination : API Method is use to Retrive all apply online list for customer
	 *
	 */
	public function apply_online_listapi($page = '1')
	{
		$this->autoRender 	= false;
		$state 				= '';
		$customer_id 		= $this->ApiToken->customer_id;
		$this->intLimit		= isset($this->request->data['limit']) ? $this->request->data['limit'] : PAGE_RECORD_LIMIT;
		$start_page 		= isset($this->request->data['page']) ? $this->request->data['page'] : $page;
		$consumer_no 		= isset($this->request->data['consumer_no']) ? $this->request->data['consumer_no'] : '';
		$application_search_no 	= isset($this->request->data['application_search_no']) ? $this->request->data['application_search_no'] : '';
		$installer_name 	= isset($this->request->data['installer_name']) ? $this->request->data['installer_name'] : '';
		$discom_name 	= isset($this->request->data['discom_name']) ? $this->request->data['discom_name'] : '';
		$this->CurrentPage  = $start_page;
		if(!empty($customer_id))
		{
			$customer_details 	= $this->Customers->find('all',array('conditions'=>array('id'=>$customer_id)))->first();
			$installer_id 		= (isset($customer_details['installer_id'])?$customer_details['installer_id']:'');
			if(isset($this->request->data['from_date']) && isset($this->request->data['to_date']) && !empty($this->request->data['from_date']) && !empty($this->request->data['to_date']))
			{
				$ApplyOnlinesList 	= $this->ApplyOnlines->getDataapplyonline($customer_id,'',$state,$this->request->data['from_date'],$this->request->data['to_date'],'',$this->request->data['status'],$installer_id,$consumer_no,$application_search_no,$installer_name,$discom_name);
			}
			else
			{
				if(!empty($this->request->data['status']))
				{
					$ApplyOnlinesList 	= $this->ApplyOnlines->getDataapplyonline($customer_id,'',$state,'','','',$this->request->data['status'],$installer_id,$consumer_no,$application_search_no,$installer_name,$discom_name);
				}
				else
				{
					$ApplyOnlinesList 	= $this->ApplyOnlines->getDataapplyonline($customer_id,'',$state,'','','','',$installer_id,$consumer_no,$application_search_no,$installer_name,$discom_name);
				}
			}
			$this->paginate['limit'] 	= $this->intLimit;
			$this->paginate['page'] 	= $this->CurrentPage;
			$arr_data 					= array();
			try {
				$ApplyOnlineLeads			= $this->paginate($ApplyOnlinesList);
				$counter 					= 0;
				foreach($ApplyOnlineLeads as $ApplyOnlineLead)
				{
					$approval=$this->ApplyOnlineApprovals->Approvalstage($ApplyOnlineLead->id);
					$arr_data[$counter]['name']=(!empty($ApplyOnlineLead->customer_name_prefixed) ? $ApplyOnlineLead->customer_name_prefixed:'-').' '.(!empty($ApplyOnlineLead->name_of_consumer_applicant) ? $ApplyOnlineLead->name_of_consumer_applicant : $ApplyOnlineLead->application_no);
					$arr_data[$counter]['id']=$counter;
					$payment_status=$ApplyOnlineLead->payment_status;
					if($ApplyOnlineLead->payment_status!='1')
					{
						$payment_status=0;
					}
					$arr_data[$counter]['payment_status']=$payment_status;
					$Approved       = "";
					$pv_capacity    = (!empty($ApplyOnlineLead->pv_capacity) ? $ApplyOnlineLead->pv_capacity : '-');
					$FesibilityData=$this->FesibilityReport->getReportData($ApplyOnlineLead->id);

					 if (!empty($FesibilityData))
					 {
						if ($FesibilityData->approved == 1)
						{
							if ($FesibilityData->approved_by_subdivision) {
								$Approved = "<span class='text-info'>Approved by Sub-division</span>";
							} else {
								$Approved = "<span class='text-info'>Approved by Section</span>";
							}
						}
						else
						{
							$Reason     = isset($FesibilityReport->RejectReason[$FesibilityData->reason])?" - ".$FesibilityReport->RejectReason[$FesibilityData->reason]:"";
							if ($FesibilityData->approved_by_subdivision) {
								$Approved = trim("<span class='text-danger'>Rejected by Sub-division</span> ".$Reason);
							} else {
								$Approved = trim("<span class='text-danger'>Rejected by Section</span> ".$Reason);
							}
						}
						if ($ApplyOnlineLead->application_status != $this->ApplyOnlineApprovals->FIELD_REPORT_SUBMITTED || $ApplyOnlineLead->application_status != $this->ApplyOnlineApprovals->FIELD_REPORT_REJECTED )
						{
							$pv_capacity = $FesibilityData->recommended_capacity_by_discom;
						}
					}
					$action =array();
					$action['varify_otp'] 		            = 1;
					if($ApplyOnlineLead->application_status == $this->ApplyOnlineApprovals->APPLICATION_GENERATE_OTP)
					{
						$action['varify_otp'] 	            = 0;
					}
					$action['download_application'] 		= 1;
					$action['upload_signed_document'] 		= 0;
					if($ApplyOnlineLead->application_status == $this->ApplyOnlineApprovals->APPLICATION_PENDING)
					{
						$action['upload_signed_document'] 	= 1;
					}

					$action['geda_registration_letter']		= 0;
					if(in_array($this->ApplyOnlineApprovals->APPROVED_FROM_GEDA,$approval)) {
						$action['geda_registration_letter']	= 1;
					}

					$action['cei_drawing_num']		        = 0;
					if(in_array($this->ApplyOnlineApprovals->SUBSIDY_AVAILIBILITY,$approval) && !in_array($this->ApplyOnlineApprovals->DRAWING_APPLIED,$approval) && isset($FesibilityData->recommended_capacity_by_discom) && $FesibilityData->recommended_capacity_by_discom >= '10') {
                        $action['cei_rdawing_num']		    = 1;
                    }
                    $action['cei_inspection_num']		    = 0;
                    if(in_array($this->ApplyOnlineApprovals->APPROVED_FROM_CEI,$approval) && !in_array($this->ApplyOnlineApprovals->CEI_APP_NUMBER_APPLIED,$approval) && isset($FesibilityData->recommended_capacity_by_discom) && $FesibilityData->recommended_capacity_by_discom >= '10') {
                    	$action['cei_inspection_num']		= 1;
                    }

                    $AllowedEditStatus = array($this->ApplyOnlineApprovals->APPLICATION_GENERATE_OTP,$this->ApplyOnlineApprovals->APPLICATION_SUBMITTED,$this->ApplyOnlineApprovals->APPLICATION_PENDING);
                    $action['edit']							= 0;
                    if(in_array($ApplyOnlineLead->application_status,$AllowedEditStatus)) {
                            $action['edit']					= 1;
                   	}
                   	else if(empty($ApplyOnlineLead->application_status)) {
                            $action['edit']					= 1;
                    }
                    if($ApplyOnlineLead->query_sent == "1" && !in_array($this->ApplyOnlineApprovals->FEASIBILITY_APPROVAL,$approval) )
                    {
                            $action['edit']				= 1;
                    }

                    $applyOnlinesDataDoc= $this->ApplyonlinDocs->find("all",['conditions'=>['application_id'=>$ApplyOnlineLead->id,'doc_type'=>'Self_Certificate']])->first();
                    $action['self_certification']			= 0;
                    if(in_array($this->ApplyOnlineApprovals->SUBSIDY_AVAILIBILITY,$approval) && !empty($FesibilityData)  && isset($FesibilityData->payment_approve)  &&  isset($FesibilityData->recommended_capacity_by_discom) && $FesibilityData->recommended_capacity_by_discom <= '10'&& empty($applyOnlinesDataDoc) )
                    {
                            $action['self_certification']	= 1;
                   	}
                   	$action['replay_message']			    = 0;
                   	if($ApplyOnlineLead->query_sent=='1' && !in_array($this->ApplyOnlineApprovals->APPLICATION_CANCELLED,$approval)){
                   			$action['replay_message']	    = 1;
                   	}
                   	$action['claim_subsidy']				= 0;
                   	if($ApplyOnlineLead->application_status == $this->ApplyOnlineApprovals->APPROVED_FROM_JREDA)
                    {
                            $action['claim_subsidy']		= 1;
                    }
					$arr_data[$counter]['category_id'] 		= $ApplyOnlineLead->category;
					$arr_data[$counter]['pv_capacity'] 		= $pv_capacity;
					$arr_data[$counter]['roof_of_proposed']	= !empty($ApplyOnlineLead->roof_of_proposed)?$ApplyOnlineLead->roof_of_proposed:'-';
					$arr_data[$counter]['installer_name']	= !empty($ApplyOnlineLead->installer['installer_name'])?$ApplyOnlineLead->installer['installer_name']:'-';
					$arr_data[$counter]['created']			= !empty($ApplyOnlineLead->created) ? date(LIST_DATE_FORMAT,strtotime($ApplyOnlineLead->created)) : '';
					$arr_data[$counter]['app_form_id']		= $ApplyOnlineLead->id;
					$arr_data[$counter]['app_id_encode']	= encode($ApplyOnlineLead->id);
					$arr_data[$counter]['consumer_no'] 		= $ApplyOnlineLead->consumer_no;
					$arr_data[$counter]['application_no'] 	= $ApplyOnlineLead->application_no;
					if ($ApplyOnlineLead->payment_mode == 1) {
						$arr_data[$counter]['payment_mode']= "1";
					} else {
						$arr_data[$counter]['payment_mode']= "0";
					}
					if($ApplyOnlineLead->payment_status == '1')
					{
						$arr_data[$counter]['payment_text']= "Payment has been done.";
					}
					else
					{
						$arr_data[$counter]['payment_text']			= "Payment has been pending.";
						$payment_data = $this->Payumoney->find('all',array('conditions'=>array('udf1'=>encode($ApplyOnlineLead->id),'payment_status'=>'failure')))->toArray();
						if(!empty($payment_data))
						{
							$arr_data[$counter]['payment_text']		= "Payment has been failed.";
						}
					}
					$arr_data[$counter]['is_payment_required'] 		= "0";
					$total_amount       = $ApplyOnlineLead->disCom_application_fee+$ApplyOnlineLead->jreda_processing_fee;
                    if($total_amount>0)
                    {
                    	$arr_data[$counter]['is_payment_required'] 	= "1";
                    }
                    if($arr_data[$counter]['is_payment_required']   == "0")
                    {
                    	$arr_data[$counter]['payment_text'] 		= "";
                    }
                    $FesibilityData=$this->FesibilityReport->getReportData($ApplyOnlineLead->id);
                    $arr_data[$counter]['feasibility_comment']      = !empty($FesibilityData->message)?$FesibilityData->message:'-';
                    $disDetails =  $this->ApplyOnlines->getDiscomDetails($ApplyOnlineLead->circle,$ApplyOnlineLead->division,$ApplyOnlineLead->subdivision,$ApplyOnlineLead->area);
                    $arr_data[$counter]['discom']                   = $disDetails;

                    $arr_data[$counter]['display_button']           = $action;
                    $applyonline_current_stage  			= $this->findcurrentstage($ApplyOnlineLead);
					$arr_data[$counter]['stage_no'] 		= $applyonline_current_stage['no'];
					$arr_data[$counter]['stage_title'] 		= $applyonline_current_stage['title'];
					$counter++;
				}
				$this->ApiToken->SetAPIResponse('limit', $this->intLimit);
				$this->ApiToken->SetAPIResponse('CurrentPage', $this->CurrentPage);
				$this->ApiToken->SetAPIResponse('SortBy', $this->SortBy);
				$this->ApiToken->SetAPIResponse('Direction', $this->Direction);
				$this->ApiToken->SetAPIResponse('page_count',(isset($this->request->params['paging']['ApplyOnlines']['pageCount'])?$this->request->params['paging']['ApplyOnlines']['pageCount']:0));
				$this->ApiToken->SetAPIResponse('result', $arr_data);
				$status = 'ok';
				$this->ApiToken->SetAPIResponse('type', $status);
			} catch (NotFoundException $e) {
				$this->ApiToken->SetAPIResponse('type', 'error');
				$this->ApiToken->SetAPIResponse('result', $arr_data);
				$this->ApiToken->SetAPIResponse('limit', $this->intLimit);
				$this->ApiToken->SetAPIResponse('CurrentPage', $this->CurrentPage);
				$this->ApiToken->SetAPIResponse('page_count',0);
			}
		}
		else
		{
			$status = "error";
			$this->ApiToken->SetAPIResponse('type', $status);
			$this->ApiToken->SetAPIResponse('msg', 'Invalid Customer Id!'.$customer_id);
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	 *
	 * view_api
	 *
	 * @param : Pass application_form_id in encoded format
	 *
	 * Behaviour : public
	 *
	 * @defination : API Method is use to Retrive details of apply online application
	 *
	 */
	public function view_api()
	{
		$this->autoRender 	= false;
		$customer_id = $this->ApiToken->customer_id;

		if(!empty($customer_id))
		{
			$arr_data_pass=$this->request->data;
			$application_form_id='';
			if(!empty($arr_data_pass))
			{
				$application_form_id=$arr_data_pass['application_form_id'];
			}
			if($application_form_id=='')
			{
					$status = "error";
					$this->ApiToken->SetAPIResponse('type', $status);
					$this->ApiToken->SetAPIResponse('msg', 'Pass Application Form Id!');
			}
			else
			{
				if(!empty($arr_data_pass))
				{
					$encode_id 				= $arr_data_pass['application_form_id'];
					$id 					= intval(decode($application_form_id));
					$APPLYONLINE_PATH 		= WWW_ROOT.APPLYONLINE_PATH;
					$applyOnlinesData 		= $this->ApplyOnlines->viewApplication($id);
					$applyOnlinesDataDocList= $this->ApplyonlinDocs->find("all",['conditions'=>['application_id'=>$id,'doc_type in'=>array('others','Signed_Doc','Self_Certificate')]])->toArray();
					$applyOnlinesprofile 	= $this->ApplyonlinDocs->find("all",['conditions'=>['application_id'=>$id,'doc_type'=>'profile']])->first();
					$arr_datadoclist=array();
					if(isset($applyOnlinesDataDocList) && !empty($applyOnlinesDataDocList))
					{
							foreach ($applyOnlinesDataDocList as $key => $value)
		                    {
								  $path = $APPLYONLINE_PATH.$applyOnlinesData->id.'/'.$value['file_name'];
								  if (empty($value['file_name']) || !file_exists($path)) continue;
								  if($value['doc_type']=='others'){
								  	 $arr_datadoclist[$key]['title']=$value['title'];
								  }
								  else{
								  	 $arr_datadoclist[$key]['title']=$value['doc_type'];
								  }
								  $arr_datadoclist[$key]['file_name']=APPLYONLINE_URL.$applyOnlinesData->id.'/'.$value['file_name'];
								  $arr_datadoclist[$key]['doc_id']=$value['id'];

							}
					}
					$applyOnlinesData->profile_image	= '';
					$path 								= $APPLYONLINE_PATH.$applyOnlinesData->id.'/'.$applyOnlinesprofile['file_name'];
					if(!empty($applyOnlinesprofile['file_name']) && file_exists($path))
					{
						$applyOnlinesData->profile_image= APPLYONLINE_URL.$applyOnlinesData->id.'/'.$applyOnlinesprofile['file_name'];
					}
					$discom_list = $this->BranchMasters->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['BranchMasters.parent_id'=>'0']])->toArray();
					$payumoney_data = $this->Payumoney->find('all',['fields'=>array('Payumoney.transaction_id','Payumoney.payment_date'),'join'=>[
						        'ap' => [
						            'table' => 'applyonline_payment',
						            'type' => 'INNER',
						            'conditions' => ['Payumoney.id = ap.payment_id']
				            	]]])->where(['ap.application_id' => $id])->first();
					$transaction_id='';

					$payment_date='';
					$action =array();
					$action['varify_otp'] 		            = 1;
					if($applyOnlinesData->application_status == $this->ApplyOnlineApprovals->APPLICATION_GENERATE_OTP)
					{
						$action['varify_otp'] 	            = 0;
					}
					$AllowedEditStatus = array($this->ApplyOnlineApprovals->APPLICATION_GENERATE_OTP,$this->ApplyOnlineApprovals->APPLICATION_SUBMITTED,$this->ApplyOnlineApprovals->APPLICATION_PENDING);
	                $action['edit']							= 0;
	                if(in_array($applyOnlinesData->application_status,$AllowedEditStatus)) {
	                        $action['edit']					= 1;
	               	}
	               	else if(empty($applyOnlinesData->application_status)) {
	                        $action['edit']					= 1;
	                }
	                if($applyOnlinesData->query_sent == "1" && !in_array($this->ApplyOnlineApprovals->FEASIBILITY_APPROVAL,$approval) )
	                {
	                        $action['edit']				= 1;
	                }
					$application_status=$this->ApplyOnlineApprovals->application_status;
					$applyOnlinesData->application_status=isset($application_status[$applyOnlinesData->application_status]) ? $application_status[$applyOnlinesData->application_status] : '-';

					if(!empty($payumoney_data))
					{
						$transaction_id=($payumoney_data->transaction_id);
						$payment_date=(!empty($payumoney_data->payment_date) ? date(LIST_DATE_FORMAT,strtotime($payumoney_data->payment_date)) : '');
					}
					$applyOnlinesData->created=(!empty($applyOnlinesData->created) ? date(LIST_DATE_FORMAT,strtotime($applyOnlinesData->created)) : '');

					$path = $APPLYONLINE_PATH.$applyOnlinesData->id.'/'.$applyOnlinesData->attach_pan_card_scan;
		            if (!empty($applyOnlinesData->attach_pan_card_scan) && file_exists($path))
					{
						$applyOnlinesData->attach_pan_card_scan=APPLYONLINE_URL.$applyOnlinesData->id.'/'.$applyOnlinesData->attach_pan_card_scan;
					}
					else
					{
						$applyOnlinesData->attach_pan_card_scan='';
					}
					if(!empty($applyOnlinesData->attach_detail_project_report))
					{
						$applyOnlinesData->attach_detail_project_report=APPLYONLINE_URL.$applyOnlinesData->id.'/'.$applyOnlinesData->attach_detail_project_report;
					}
					else
					{
						$applyOnlinesData->attach_detail_project_report='';
					}
					$path = $APPLYONLINE_PATH.$applyOnlinesData->id.'/'.$applyOnlinesData->attach_recent_bill;
		            if (!empty($applyOnlinesData->attach_recent_bill) && file_exists($path))
					{
						$applyOnlinesData->attach_recent_bill=APPLYONLINE_URL.$applyOnlinesData->id.'/'.$applyOnlinesData->attach_recent_bill;
					}
					else
					{
						$applyOnlinesData->attach_recent_bill='';
					}
					$path = $APPLYONLINE_PATH.$applyOnlinesData->id.'/'.$applyOnlinesData->attach_latest_receipt;
		            if (!empty($applyOnlinesData->attach_latest_receipt) && file_exists($path))
					{
						$applyOnlinesData->attach_latest_receipt=APPLYONLINE_URL.$applyOnlinesData->id.'/'.$applyOnlinesData->attach_latest_receipt;
					}
					else
					{
						$applyOnlinesData->attach_latest_receipt='';
					}
					$applyOnlinesData->discom_name 	= isset($discom_list[$applyOnlinesData->discom_name]) ? $discom_list[$applyOnlinesData->discom_name] : '';
					$applyOnlinesData->category_id 	= $applyOnlinesData->category;
					if($applyOnlinesData->parameter_cats['para_value']!='' && $applyOnlinesData->parameter_cats['para_value']!='null')
					{
						$applyOnlinesData->category = $applyOnlinesData->parameter_cats['para_value'];
					}
					$path = $APPLYONLINE_PATH.$applyOnlinesData->id.'/'.$applyOnlinesData->attach_photo_scan_of_aadhar;
					if (!empty($applyOnlinesData->attach_photo_scan_of_aadhar) && file_exists($path))
					{
						$applyOnlinesData->attach_photo_scan_of_aadhar=APPLYONLINE_URL.$applyOnlinesData->id.'/'.$applyOnlinesData->attach_photo_scan_of_aadhar;
						if($applyOnlinesData->category==3001  || strtolower($applyOnlinesData->category)=='residental' || strtolower($applyOnlinesData->category)=='residential')
						{
		                    if($customer_id!=$applyOnlinesData->customer_id)
		                   	{
		                   		$applyOnlinesData->attach_photo_scan_of_aadhar='';
		                   	}
	                   	}
					}
					else
					{
						$applyOnlinesData->attach_photo_scan_of_aadhar='';
					}
					if($applyOnlinesData->payment_status!='1')
					{
						$applyOnlinesData->payment_status=0;
					}
					if($applyOnlinesData->comunication_address_as_above == '1' || $applyOnlinesData->comunication_address == '1')
	                {
	                    $applyOnlinesData->comunication_address = $applyOnlinesData->address1.'<br/>'.$applyOnlinesData->address2;
	                }
	                else if($applyOnlinesData->comunication_address == '0')
	                {
	                    $applyOnlinesData->comunication_address = '';
	                }
					$applyOnlinesData->aadhar_no_or_pan_card_no = passdecrypt($applyOnlinesData->aadhar_no_or_pan_card_no);
					if($applyOnlinesData->category==3001  || strtolower($applyOnlinesData->category)=='residental' || strtolower($applyOnlinesData->category)=='residential')
					{
	                    if($customer_id!=$applyOnlinesData->customer_id)
	                   	{
	                   		$applyOnlinesData->aadhar_no_or_pan_card_no = 'Yes';
	                   	}
	               	}
					$applyOnlinesData->pan_card_no 				= passdecrypt($applyOnlinesData->pan_card_no);
					$applyOnlinesData->house_tax_holding_no 	= passdecrypt($applyOnlinesData->house_tax_holding_no);
					$applyOnlinesData->transmission_text 		= '';
					if($applyOnlinesData->transmission_line=='1')
					{
						$applyOnlinesData->transmission_text 	= 'Single Phase';
					}
					elseif($applyOnlinesData->transmission_line=='3')
					{
						$applyOnlinesData->transmission_text 	= '3 Phase';
					}
					$FesibilityData 	= $this->FesibilityReport->getReportData($applyOnlinesData->id);
					$approval 			= $this->ApplyOnlineApprovals->Approvalstage($applyOnlinesData->id);
					$applyOnlinesData->estimated_amount 	= '';
					$applyOnlinesData->estimated_due_date 	= '';
					if(in_array($this->ApplyOnlineApprovals->FEASIBILITY_APPROVAL,$approval) && !empty($FesibilityData))
	                {
	                	$applyOnlinesData->estimated_amount = $FesibilityData->estimated_amount;
	                	if(!empty($FesibilityData->estimated_due_date))
	                    {
		                    $est_date = date('Y-m-d',strtotime($FesibilityData->estimated_due_date));
		                    $arr_data_date = explode(' ',$FesibilityData->estimated_due_date);
		                    if($arr_data_date[0]!='0000-00-00' && $est_date!='1970-01-01')
		                    {
		                        $data_date = explode(' ',date(LIST_DATE_FORMAT,strtotime($arr_data_date[0])));
		                        $applyOnlinesData->estimated_due_date = $data_date[0];
		                    }
	                    }
	                }
	                $applyonline_current_stage  	= $this->findcurrentstage($applyOnlinesData);
					$applyOnlinesData->stage_no 	= $applyonline_current_stage['no'];
					$applyOnlinesData->stage_title 	= $applyonline_current_stage['title'];
					$this->ApiToken->SetAPIResponse('result', array('applyOnlinesData'=>$applyOnlinesData,'applyOnlinesDataDocList'=>$arr_datadoclist,'transaction_id'=>$transaction_id,'payment_date'=>$payment_date,'display_button'=>$action));
					$status = 'ok';
					$this->ApiToken->SetAPIResponse('type', $status);
				}
			}
		}
		else
		{
			$status = "error";
			$this->ApiToken->SetAPIResponse('type', $status);
			$this->ApiToken->SetAPIResponse('msg', 'Invalid Customer Id!');
		}

		echo $this->ApiToken->GenerateAPIResponse();
		exit;

	}

	/*
	 * Function for GetProjectEstimation
	 * @param mixed What page to display
	 * @return void
	 */
	private function GetProjectEstimation($application_id=0)
	{
		if (!empty($application_id))
		{
			$applyOnlinesData = $this->ApplyOnlines->get($application_id);
			$applyOnlinesData->aid 	= $this->ApplyOnlines->GenerateApplicationNo($applyOnlinesData);
			$proj_name 		= "APPLICATION - ".$applyOnlinesData->aid;
			$lat 			= $applyOnlinesData->lattitue;
			$lon 			= $applyOnlinesData->longitude;
			$roof_area 		= ($applyOnlinesData->pv_capacity * 12);
			$c_type 		= $applyOnlinesData->category;
			$energy_con 	= !empty($applyOnlinesData->energy_con)?$applyOnlinesData->energy_con:0;
			$area_type 		= '2002';
			$bill 			= $applyOnlinesData->bill;
			$backup_type 	= 0;
			$hours 			= 0;
			$location_flag 	= 'auto';
			$customer_id 	= $applyOnlinesData->customer_id;
			$installer_id 	= $applyOnlinesData->installer_id;

			$address 			= $applyOnlinesData->address1;
			$city 				= $applyOnlinesData->city;
			$state 				= $applyOnlinesData->state;
			$state_short_name 	= $applyOnlinesData->state;
			$pincode 			= $applyOnlinesData->pincode;
			$country 			= $applyOnlinesData->country;

			if (!empty($applyOnlinesData->project_id)) {
				$this->request->data['Projects']['id']				= $applyOnlinesData->project_id;
			}
			$this->request->data['latitude']						= $lat;
			$this->request->data['Projects']['latitude']			= $lat;
			$this->request->data['longitude']						= $lon;
			$this->request->data['Projects']['longitude']			= $lon;
			$this->request->data['customer_type']					= $c_type;
			$this->request->data['project_type']					= $c_type;
			$this->request->data['Projects']['customer_type']		= $c_type;
			$this->request->data['area']							= $roof_area;
			$this->request->data['Projects']['area']				= $roof_area;
			$this->request->data['area_type'] 						= $area_type;
			$this->request->data['bill'] 							= $bill;
			$this->request->data['avg_monthly_bill'] 				= $bill;
			$this->request->data['backup_type']						= $backup_type;
			$this->request->data['usage_hours']						= $hours;
			$this->request->data['Projects']['usage_hours']			= $hours;
			$this->request->data['energy_con']						= $energy_con;
			$this->request->data['Projects']['estimated_kwh_year'] 	= $energy_con;
			$this->request->data['recommended_capacity']			= $applyOnlinesData->pv_capacity;
			$this->request->data['Projects']['recommended_capacity']= $applyOnlinesData->pv_capacity;
			$this->request->data['address']							= $address;
			$this->request->data['city']							= $city;
			$this->request->data['state']							= $state;
			$this->request->data['state_short_name']				= $state_short_name;
			$this->request->data['country']							= $country;
			$this->request->data['postal_code']						= $pincode;
			$result 												= $this->Projects->getprojectestimationV2($this->request->data,$customer_id,false);
			return $result;
		}
	}

	public function preview_subsidy_approval_letter($id=0)
	{
		$id 						= decode($id);
		$isdownload 				= true;
		$this->layout 				= false;
		$applyOnlinesData 			= $this->ApplyOnlines->viewApplication($id);
		$applyOnlinesData->aid 		= $this->ApplyOnlines->GenerateApplicationNo($applyOnlinesData);
		$division 					= $this->DiscomMaster->find("all",['fields'=>['title'],'conditions'=>['DiscomMaster.id'=>$applyOnlinesData->division,'DiscomMaster.type'=>3]])->toArray();
		$circle 					= $this->DiscomMaster->find("all",['fields'=>['title'],'conditions'=>['DiscomMaster.id'=>$applyOnlinesData->circle,'DiscomMaster.type'=>2]])->toArray();
		$subdivision 				= $this->DiscomMaster->find("all",['fields'=>['title'],'conditions'=>['DiscomMaster.id'=>$applyOnlinesData->subdivision,'DiscomMaster.type'=>4]])->toArray();

		$LETTER_APPLICATION_NO 		= $applyOnlinesData->aid;
		$APPLICATION_DATE 			= date("d.M.Y",strtotime($applyOnlinesData->created));
		$CUSTOMER_NAME 				= trim($applyOnlinesData->customer_name_prefixed." ".$applyOnlinesData->name_of_consumer_applicant);
		$CUSTOMER_EMAIL 			= $applyOnlinesData->email;
		$CUSTOMER_ADDRESS 			= "";

		if (!empty($applyOnlinesData->address1)) {
			$CUSTOMER_ADDRESS .= $applyOnlinesData->address1.",<br />";
		}
		if (!empty($applyOnlinesData->address2)) {
			$CUSTOMER_ADDRESS .= $applyOnlinesData->address2.",<br />";
		}
		if (!empty($applyOnlinesData->city)) {
			$CUSTOMER_ADDRESS .= ucfirst($applyOnlinesData->city)." ";
		}
		if (!empty($applyOnlinesData->state)) {
			$CUSTOMER_ADDRESS .= ucfirst($applyOnlinesData->state)." ";
		}
		if (!empty($CUSTOMER_ADDRESS)) {
			$CUSTOMER_ADDRESS .= "INDIA";
		}
		$project_id 	= $applyOnlinesData->project_id;
		$CreateProject 	= false;
		if (empty($project_id)) {
			$CreateProject = false;
		}
		$AhaProjectData 			= $this->CreateMyProject($id,$CreateProject);
		$customer_id 				= $applyOnlinesData->customer_id;
		$ESTIMATED_COST 			= 0;
		$TOTAL_SUBSIDY_AMOUNT 		= 0;
		$STATE_SUBSIDY 				= "-";
		$STATE_SUBSIDY_AMOUNT 		= "-";
		$CENTRAL_SUBSIDY 			= "-";
		$CENTRAL_SUBSIDY_AMOUNT 	= "-";
		if (!empty($AhaProjectData)) {
			$SubsidyDetail 			= $AhaProjectData['SubsidyDetail'];

			if ($SubsidyDetail['state_subcidy_type'] == 0) {
				$STATE_SUBSIDY 			= $SubsidyDetail['state_subsidy']."%";
				$STATE_SUBSIDY_AMOUNT 	= ($SubsidyDetail['state_subsidy_amount'] > 0)?$this->get_money_indian_format($SubsidyDetail['state_subsidy_amount']):"-";
			} else {
				$STATE_SUBSIDY 			= ($SubsidyDetail['state_subsidy'] > 0)?$this->get_money_indian_format($SubsidyDetail['state_subsidy']):"-";
				$STATE_SUBSIDY_AMOUNT 	= ($SubsidyDetail['state_subsidy_amount'] > 0)?$this->get_money_indian_format($SubsidyDetail['state_subsidy_amount']):"-";
			}

			if ($SubsidyDetail['central_subcidy_type'] == 0) {
				$CENTRAL_SUBSIDY 			= $SubsidyDetail['central_subsidy']."%";
				$CENTRAL_SUBSIDY_AMOUNT 	= ($SubsidyDetail['central_subsidy_amount'] > 0)?$this->get_money_indian_format($SubsidyDetail['central_subsidy_amount']):"-";
			} else {
				$CENTRAL_SUBSIDY 			= ($SubsidyDetail['central_subsidy'] > 0)?$this->get_money_indian_format($SubsidyDetail['central_subsidy']):"-";
				$CENTRAL_SUBSIDY_AMOUNT 	= ($SubsidyDetail['central_subsidy_amount'] > 0)?$this->get_money_indian_format($SubsidyDetail['central_subsidy_amount']):"-";
			}

			$ESTIMATED_COST 		= ($SubsidyDetail['total_cost'] > 0)?$this->get_money_indian_format($SubsidyDetail['total_cost']):0;
			$TOTAL_SUBSIDY_AMOUNT 	= ($SubsidyDetail['total_subsidy'] > 0)?$this->get_money_indian_format($SubsidyDetail['total_subsidy']):0;
		}

		$FESIBILITY_APPROVED_DATE 	= $APPLICATION_DATE;
		$FESIBILITY_REF_NO 			= "";
		$fesibility 				= $this->FesibilityReport->getReportData($id);
		$fesibility_aid 			= '';
		if (!empty($fesibility))
		{
			$fesibility_aid 			= $this->FesibilityReport->GenerateApplicationNo($fesibility,$applyOnlinesData->state);
			$FESIBILITY_APPROVED_DATE 	= date("d.M.Y",strtotime($fesibility->division_approved_date));
			$APPROVED_CAPACITY 			= floatval($fesibility->recommended_capacity_by_discom);
		}

		$INSTALLER_NAME 			= $applyOnlinesData->installer['installer_name'];
		$CONSUMER_NO 				= $applyOnlinesData->consumer_no;
		$FESIBILITY_REF_NO 			= $fesibility_aid;
		$APPLICATION_NO				= $applyOnlinesData->aid;
		$APPROVED_CAPACITY 			= floatval($applyOnlinesData->pv_capacity);
		$SUBDIVISION 				= (isset($subdivision[0]['title'])?$subdivision[0]['title']:"-");
		$DIVISION 					= (isset($division[0]['title'])?$division[0]['title']:"-");

		$JREDA_WORK_ORDER_NO 		= isset($applyOnlinesData->installer['jreda_work_order'])?$applyOnlinesData->installer['jreda_work_order']:"-";
		$JERDA_WORK_NIB 			= isset($applyOnlinesData->installer['jreda_nib_no'])?$applyOnlinesData->installer['jreda_nib_no']:"-";
		$AGREEMENT_DATE 			= $APPLICATION_DATE;
		$CUSTOMER_TYPE 				= isset($applyOnlinesData->parameter_cats['para_value'])?$applyOnlinesData->parameter_cats['para_value']:"-";;
		$INSTALLATION_DATE 			= date('d.M.Y', strtotime("+3 months", strtotime($APPLICATION_DATE)));

		$EmailVars 					= array("LETTER_APPLICATION_NO"=>$LETTER_APPLICATION_NO,
											"APPLICATION_DATE"=>$APPLICATION_DATE,
											"FESIBILITY_APPROVED_DATE"=>$FESIBILITY_APPROVED_DATE,
											"INSTALLER_NAME"=>$INSTALLER_NAME,
											"CUSTOMER_NAME"=>$CUSTOMER_NAME,
											"ESTIMATED_COST"=>$ESTIMATED_COST,
											"STATE_SUBSIDY"=>$STATE_SUBSIDY,
											"STATE_SUBSIDY_AMOUNT"=>$STATE_SUBSIDY_AMOUNT,
											"CENTRAL_SUBSIDY"=>$CENTRAL_SUBSIDY,
											"CENTRAL_SUBSIDY_AMOUNT"=>$CENTRAL_SUBSIDY_AMOUNT,
											"TOTAL_SUBSIDY_AMOUNT"=>$TOTAL_SUBSIDY_AMOUNT,
											"CUSTOMER_ADDRESS"=>$CUSTOMER_ADDRESS,
											"CONSUMER_NO"=>$CONSUMER_NO,
											"FESIBILITY_REF_NO"=>$FESIBILITY_REF_NO,
											"APPLICATION_NO"=>$APPLICATION_NO,
											"APPROVED_CAPACITY"=>$APPROVED_CAPACITY,
											"SUBDIVISION"=>$SUBDIVISION,
											"DIVISION"=>$DIVISION,
											"INSTALLATION_DATE"=>$INSTALLATION_DATE,
											"JREDA_WORK_ORDER_NO"=>$JREDA_WORK_ORDER_NO,
											"JERDA_WORK_NIB"=>$JERDA_WORK_NIB,
											"AGREEMENT_DATE"=>$AGREEMENT_DATE,
											"CUSTOMER_TYPE"=>$CUSTOMER_TYPE);
		$this->set(compact('LETTER_APPLICATION_NO','APPLICATION_DATE','FESIBILITY_APPROVED_DATE','INSTALLER_NAME','CUSTOMER_NAME','ESTIMATED_COST','STATE_SUBSIDY','STATE_SUBSIDY_AMOUNT','CENTRAL_SUBSIDY','CENTRAL_SUBSIDY_AMOUNT','TOTAL_SUBSIDY_AMOUNT','CUSTOMER_ADDRESS','CONSUMER_NO','FESIBILITY_REF_NO','APPLICATION_NO','APPROVED_CAPACITY','SUBDIVISION','DIVISION','INSTALLATION_DATE','JREDA_WORK_ORDER_NO','JERDA_WORK_NIB','AGREEMENT_DATE','CUSTOMER_TYPE'));
		$subsidy_id 	= $this->GetApplysubsidyId($id);
		require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
		$dompdf = new Dompdf($options = array());

		$dompdf->set_base_path(SITE_ROOT_DIR_PATH.'/pdf/');
		$dompdf->set_option('defaultFont', "Courier");

		$html = $this->render('../ApplyOnlines/preview_subsidy_approval_letter');

		//exit($html);
		$dompdf->loadHtml($html,'UTF-8');
		$dompdf->setPaper('A4', 'portrait');

		$dompdf->render();
		// Output the generated PDF to Browser
		if($isdownload){
			$dompdf->stream('subsidy-'.$subsidy_id);
		}
		$output = $dompdf->output();
		$pdfPath = SITE_ROOT_DIR_PATH.'/tmp/subsidy-'.$subsidy_id.'.pdf';
		file_put_contents($pdfPath, $output);
		//return $pdfPath;
	}

	/*
	 * get_money_indian_format
	 * @param mixed $amount
	 * @param boolean $suffix
	 * @return mixed $thecash
	 */
	private function get_money_indian_format($amount, $suffix = 1)
	{
		$explrestunits = "";
		$num = $amount;
		if(strlen($num)>3) {
			$lastthree = substr($num, strlen($num)-3, strlen($num));
			$restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
			$restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
			$expunit = str_split($restunits, 2);
			for($i=0; $i<sizeof($expunit); $i++) {
				// creates each of the 2's group and adds a comma to the end
				if($i==0) {
					$explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
				} else {
					$explrestunits .= $expunit[$i].",";
				}
			}
			$thecash = $explrestunits.$lastthree;
		} else {
			$thecash = $num;
		}
		if(!$suffix) {
			return $thecash;
		} else {
			return 'Rs. '. $thecash.'/-';
		}
	}

	/*
	 * Function for CreateMyProject
	 * @param mixed What page to display
	 * @return void
	 */
	private function CreateMyProject($application_id=0,$CreateMyProject=true)
	{
		if (!empty($application_id))
		{
			$applyOnlinesData = $this->ApplyOnlines->get($application_id);
			$applyOnlinesData->aid 	= $this->ApplyOnlines->GenerateApplicationNo($applyOnlinesData);
			$proj_name 		= "APPLICATION - ".$applyOnlinesData->aid;
			$lat 			= $applyOnlinesData->lattitue;
			$lon 			= $applyOnlinesData->longitude;
			$roof_area 		= ($applyOnlinesData->pv_capacity * 12);
			$c_type 		= $applyOnlinesData->category;
			$energy_con 	= !empty($applyOnlinesData->energy_con)?$applyOnlinesData->energy_con:0;
			$area_type 		= '2002';
			$bill 			= $applyOnlinesData->bill;
			$backup_type 	= 0;
			$hours 			= 0;
			$location_flag 	= 'auto';
			$customer_id 	= $applyOnlinesData->customer_id;
			$installer_id 	= $applyOnlinesData->installer_id;

			$address 			= $applyOnlinesData->address1;
			$city 				= $applyOnlinesData->city;
			$state 				= $applyOnlinesData->state;
			$state_short_name 	= $applyOnlinesData->state;
			$pincode 			= $applyOnlinesData->pincode;
			$country 			= $applyOnlinesData->country;
			$SendQuery 			= true;
			if (!empty($applyOnlinesData->project_id)) {
				$this->request->data['Projects']['id']	= $applyOnlinesData->project_id;
				$SendQuery 								= false;
			}
			$this->request->data['proj_name']						= $proj_name;
			$this->request->data['latitude']						= $lat;
			$this->request->data['Projects']['latitude']			= $lat;
			$this->request->data['longitude']						= $lon;
			$this->request->data['Projects']['longitude']			= $lon;
			$this->request->data['customer_type']					= $c_type;
			$this->request->data['project_type']					= $c_type;
			$this->request->data['Projects']['customer_type']		= $c_type;
			$this->request->data['area']							= $roof_area;
			$this->request->data['Projects']['area']				= $roof_area;
			$this->request->data['area_type'] 						= $area_type;
			$this->request->data['bill'] 							= $bill;
			$this->request->data['avg_monthly_bill'] 				= $bill;
			$this->request->data['backup_type']						= $backup_type;
			$this->request->data['usage_hours']						= $hours;
			$this->request->data['Projects']['usage_hours']			= $hours;
			$this->request->data['energy_con']						= $energy_con;
			$this->request->data['Projects']['estimated_kwh_year'] 	= $energy_con;
			$this->request->data['recommended_capacity']			= $applyOnlinesData->pv_capacity;
			$this->request->data['Projects']['recommended_capacity']= $applyOnlinesData->pv_capacity;
			$this->request->data['address']							= $address;
			$this->request->data['city']							= $city;
			$this->request->data['state']							= $state;
			$this->request->data['state_short_name']				= $state_short_name;
			$this->request->data['country']							= $country;
			$this->request->data['postal_code']						= $pincode;
			$result 												= $this->Projects->getprojectestimationV2($this->request->data,$customer_id,$CreateMyProject);

			/** Update Project Ref. No in Table */
			$arrData 	= array("project_id"=>$result['proj_id']);
			$this->ApplyOnlines->updateAll($arrData,['id' => $application_id]);
			/** Update Project Ref. No in Table */

			/** Send Query to Installer */
			if ($SendQuery) $this->SendQueryToInstaller($result['proj_id'],$installer_id);
			/** Send Query to Installer */

			return $result;
		}
	}

	/**
	 * SendQueryToInstaller
	 * Behaviour : private
	 * Parameter : $project_id, $installer_id
	 * @defination : Method is use to send query email.
	 */
	private function SendQueryToInstaller($project_id,$installer_id)
	{
		$this->autoRender 	= false;
		if(!empty($project_id) && !empty($installer_id))
		{
			$insProjData['InstallerProjects']['installer_id']	= $installer_id;
			$insProjData['InstallerProjects']['project_id']		= $project_id;
			$insProjEntity 			= $this->InstallerProjects->newEntity($insProjData);
			$insProjEntity->created = $this->NOW();
			$this->InstallerProjects->save($insProjEntity);
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
			$Installers = $this->Installers->find('all',array('conditions'=>array('id' =>$installer_id)))->toArray();
			$this->sendQueryEmail($custProjectData, $Installers);
		}
	}

	/**
	 * sendQueryEmail
	 * Behaviour : private
	 * Parameter : $projectDetail(array), $installerList(array)
	 * @defination : Method is use to send query email.
	 */
	private function sendQueryEmail($projectDetail, $installerList)
	{
		if(!empty($projectDetail) && !empty($installerList))
		{
			$to			= SEND_QUERY_EMAIL;
			$subject	= Configure::read('EMAIL_ENV')."Project Query";
			$email 		= new Email('default');
		 	$email->profile('default');
			$email->viewVars(array('project_detail' => $projectDetail, 'installer_list' => $installerList));
			$email->template('send_query', 'default')
				->emailFormat('html')
				->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
			    ->to($to)
			    ->subject($subject)
			    ->send();
		}
	}
	public function GetApplysubsidyId($appId)
	{
		$reportId = '';
		if(!empty($appId)) {
			if(strlen($appId)==1) $appId = "00000".$appId;
			if(strlen($appId)==2) $appId = "0000".$appId;
			if(strlen($appId)==3) $appId = "000".$appId;
			if(strlen($appId)==4) $appId = "00".$appId;
			if(strlen($appId)==5) $appId = "0".$appId;
			$reportId = $appId;
		}
		return $reportId;
	}
	/**
	 *
	 * _generateApplyonlineSearchCondition
	 *
	 * Behaviour : Private
	 *
	 * @param : $id  : Id is use to identify for which project condition to be generated if its not null
	 *
	 * @defination : Method is use to generate search condition using which projct data can be listed
	 *
	 */
	private function _generateApplyonlineSearchCondition($id=null)
	{
		$arrCondition	= array();
		$blnSinCompany	= true;
		if(!empty($id)) $this->request->data['Projects']['id'] = $id;
		//if(count($this->request->data)==0) $this->request->data['Projects']['status'] = $this->Projects->STATUS_ACTIVE;
		if(isset($this->request->data) && count($this->request->data)>0)
        {
            if(isset($this->request->data['Projects']['id']) && trim($this->request->data['Projects']['id'])!='') {
                $strID = trim($this->request->data['Projects']['id'],',');
                $arrCondition['Projects.id'] = $this->request->data['Projects']['id'];
            }

			if(isset($this->request->data['Projects']['name']) && $this->request->data['Projects']['name']!='') {
                $arrCondition['Projects.name LIKE'] = '%'.$this->request->data['Projects']['name'].'%';
            }
			if(isset($this->request->data['Projects']['city']) && $this->request->data['Projects']['city']!='') {
                $arrCondition['Projects.city LIKE'] = '%'.$this->request->data['Projects']['city'].'%';
            }
			if(isset($this->request->data['Projects']['email']) && $this->request->data['Projects']['email']!='') {
                $arrCondition['c.email LIKE'] = '%'.$this->request->data['Projects']['email'].'%';
            }
			if(isset($this->request->data['Projects']['state']) && $this->request->data['Projects']['state']!='') {
                $arrCondition['Projects.state LIKE'] = '%'.$this->request->data['Projects']['state'].'%';
            }
			if(isset($this->request->data['Projects']['status']) && $this->request->data['Projects']['status']!=''){
                $arrCondition['Projects.status'] = $this->request->data['Projects']['status'];
            }
			if(isset($this->request->data['Projects']['search_date']) && $this->request->data['Projects']['search_date']!='') {
            	$arrDate = array('Projects.created');
				if(in_array($this->request->data['Projects']['search_date'], $arrDate)) {
					if($this->request->data['Projects']['search_period'] == 1 || $this->request->data['Projects']['search_period'] == 2) {
						$arrSearchPara	= $this->Projects->setSearchDateParameter($this->request->data['Projects']['search_period'],'Projects');
						$this->request->data['Projects'] = array_merge($this->request->data['Projects'],$arrSearchPara['Projects']);
						$this->dateDisabled	= true;
					}
					$arrperiodcondi = $this->Projects->findConditionByPeriod($this->request->data['Projects']['search_date'],
																				$this->request->data['Projects']['search_period'],
																				$this->request->data['Projects']['DateFrom'],
																				$this->request->data['Projects']['DateTo'],
																				$this->Session->read('Projects.timezone'));

				if(!empty($arrperiodcondi)){
						$arrCondition['date_search']=$arrperiodcondi;
                }
				}
			}
		}
		//print_r($arrCondition);
		//die;
		return $arrCondition;
	}
	/**
	 *
	 * downloadcsv
	 *
	 * Behaviour : public
	 *
	 * @defination : Method is use to download .xls file from project list
	 *
	 */
	public function downloadcsv()
	{
		$arrCondition		= $this->_generateCustomerSearchCondition();
		$arr_date_search=array();
		if(array_key_exists('date_search', $arrCondition))
		{
			$arr_date_search=$arrCondition['date_search'];
			unset($arrCondition['date_search']);
		}
		$query_data		= $this->ApplyOnlines->find('all',array('fields'=>['ApplyOnlines.id','name_of_consumer_applicant','ApplyOnlines.email','ApplyOnlines.pv_capacity','ApplyOnlines.address1','ApplyOnlines.address2','ApplyOnlines.city','ApplyOnlines.state','ApplyOnlines.pincode','ApplyOnlines.mobile','ApplyOnlines.consumer_no','ApplyOnlines.energy_con','ApplyOnlines.bill','ApplyOnlines.lattitue','ApplyOnlines.longitude','ApplyOnlines.created','installers.installer_name','customers.name','parameters.para_value','branch_masters.title'],
									'join'=>[['table'=>'customers','type'=>'INNER','conditions'=>'ApplyOnlines.customer_id = customers.id'],
									['table'=>'parameters','type'=>'LEFT','conditions'=>'ApplyOnlines.category = parameters.para_id'],
									['table'=>'installers','type'=>'LEFT','conditions'=>' ApplyOnlines.installer_id = installers.id'],
									['table'=>'branch_masters','type'=>'LEFT','conditions'=>' ApplyOnlines.discom = branch_masters.id']],
									'conditions' => $arrCondition,
									));

		if(!empty($arr_date_search))
		{
			$fields_date = $arr_date_search['between'][0];
			$StartTime  = $arr_date_search['between'][1];
   			$EndTime    = $arr_date_search['between'][2];
            $query_data->where([function ($exp, $q) use ($StartTime, $EndTime,$fields_date) {
			return $exp->between($fields_date, $StartTime, $EndTime);
       		 }]);
		}

		$applyonlineArr = $query_data->toArray();

		$PhpExcel=$this->PhpExcel;
		$PhpExcel->createExcel();

		$objDrawing = new \PHPExcel_Worksheet_MemoryDrawing();
		$PhpExcel->writeCellValue('A1', 'Applyonline ID');
		$PhpExcel->writeCellValue('B1', 'Installer Name');
		$PhpExcel->writeCellValue('C1', 'Consumer Applicant');
		$PhpExcel->writeCellValue('D1', 'Email');
		$PhpExcel->writeCellValue('E1', 'PV Capacity');
		$PhpExcel->writeCellValue('F1', 'Address1');
		$PhpExcel->writeCellValue('G1', 'Address2');
		$PhpExcel->writeCellValue('H1', 'City');
		$PhpExcel->writeCellValue('I1', 'State');
		$PhpExcel->writeCellValue('J1', 'Pincode');
		$PhpExcel->writeCellValue('K1', 'Mobile');
		$PhpExcel->writeCellValue('L1', 'Discome');
		$PhpExcel->writeCellValue('M1', 'Consumer Number');
		$PhpExcel->writeCellValue('N1', 'Customer Name');
		$PhpExcel->writeCellValue('O1', 'Customer Type');
		$PhpExcel->writeCellValue('P1', 'Energy Consume');
		$PhpExcel->writeCellValue('Q1', 'Bill');
		$PhpExcel->writeCellValue('R1', 'Lattitue');
		$PhpExcel->writeCellValue('S1', 'Longitude');
		$PhpExcel->writeCellValue('T1', 'Created');
		$PhpExcel->fillCellFont('A1:T1','000000',TRUE);
		$j=2;
		if (!empty($applyonlineArr))
		{
			foreach ($applyonlineArr as $row)
			{
				$PhpExcel->writeCellValue('A'.$j, $row->id);
				$PhpExcel->writeCellValue('B'.$j, $row['installers']['installer_name']);
				$PhpExcel->writeCellValue('C'.$j, $row->name_of_consumer_applicant);
				$PhpExcel->writeCellValue('D'.$j, $row->email);
				$PhpExcel->writeCellValue('E'.$j, $row->pv_capacity);
				$PhpExcel->writeCellValue('F'.$j, $row->address1);
				$PhpExcel->writeCellValue('G'.$j, $row->address2);
				$PhpExcel->writeCellValue('H'.$j, $row->city);
				$PhpExcel->writeCellValue('I'.$j, $row->state);
				$PhpExcel->writeCellValue('J'.$j, $row->pincode);
				$PhpExcel->writeCellValue('K'.$j, $row->mobile);
				$PhpExcel->writeCellValue('L'.$j, $row['branch_masters']['title']);
				$PhpExcel->writeCellValue('M'.$j, $row->consumer_no);
				$PhpExcel->writeCellValue('N'.$j, $row['customers']['name']);
				$PhpExcel->writeCellValue('O'.$j, $row['parameters']['para_value']);
				$PhpExcel->writeCellValue('P'.$j, $row->energy_con);
				$PhpExcel->writeCellValue('Q'.$j, $row->bill);
				$PhpExcel->writeCellValue('R'.$j, $row->lattitue);
				$PhpExcel->writeCellValue('S'.$j, $row->longitude);
				$PhpExcel->writeCellValue('T'.$j, date('m/d/Y H:i:s',strtotime($row->created)));
				$j++;
			}
		}
		for($i=65;$i<=84;$i++)
		{
			if($i==65)
			{
				$PhpExcel->getExcelObj()->getActiveSheet()->getColumnDimension(chr($i))->setWidth(11);
				$PhpExcel->getExcelObj()->getActiveSheet()->getStyle(chr($i).'1')->getAlignment()->setWrapText(true);
			}
			elseif($i==79 || $i==84)
			{
				$PhpExcel->getExcelObj()->getActiveSheet()->getColumnDimension(chr($i))->setWidth(17);
				$PhpExcel->getExcelObj()->getActiveSheet()->getStyle(chr($i).'1')->getAlignment()->setWrapText(true);
			}
			elseif($i==66)
			{
				$PhpExcel->getExcelObj()->getActiveSheet()->getColumnDimension(chr($i))->setWidth(40);
			}
			elseif($i==67 || $i==72 || $i==73 || $i==74 || $i==75 || $i==76 || $i==77 || $i==78)
			{
				$PhpExcel->getExcelObj()->getActiveSheet()->getColumnDimension(chr($i))->setAutoSize(true);
			}
			elseif($i==68 || $i==70 || $i==71)
			{
				$PhpExcel->getExcelObj()->getActiveSheet()->getColumnDimension(chr($i))->setWidth(50);
			}
			else
			{
				$PhpExcel->getExcelObj()->getActiveSheet()->getStyle(chr($i).'1')->getAlignment()->setWrapText(true);
			}
		}
		$PhpExcel->downloadFile(time());
		exit;
	}
	public function generateapplyviewpdf($id= null)
	{
		$this->layout 	= false;
		$isdownload 	= true;
		if(empty($id)) {
			$this->Flash->error('Please Select Valid Apply Onlines.');
			return $this->redirect(WEB_ADMIN_PREFIX.'/ApplyOnlines');
		}
		else {
			$encode_id = $id;
			$id=intval(decode($id));
			$applyOnlinesData = $this->ApplyOnlines->viewApplication($id);
			$applyOnlinesDataDocList = $this->ApplyonlinDocs->find("all",['conditions'=>['application_id'=>$id,'doc_type'=>'others']])->toArray();
			$GetProjectEstimation 		= $this->GetProjectEstimation($id);
		}
		$applyonline_id 	= $this->GetApplysubsidyId($id);
		$discom_list 	= $this->BranchMasters->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['BranchMasters.parent_id'=>'0']])->toArray();
		$payumoney_data = $this->Payumoney->find('all',['fields'=>array('Payumoney.transaction_id','Payumoney.payment_date'),'join'=>[
				        'ap' => [
				            'table' => 'applyonline_payment',
				            'type' => 'INNER',
				            'conditions' => ['Payumoney.id = ap.payment_id']
		            	]]])->where(['ap.application_id' => $id])->first();
		$transaction_id='';
		$payment_date='';
		if(!empty($payumoney_data))
		{
			$transaction_id = ($payumoney_data->transaction_id);
			$payment_date 	= (!empty($payumoney_data->payment_date) ? date(LIST_DATE_FORMAT,strtotime($payumoney_data->payment_date)) : '');
		}
		$this->set("APPLY_ONLINE_MAIN_STATUS",$this->ApplyOnlineApprovals->apply_online_main_status);
		$this->set("discom_list",$discom_list);
		$this->set('applyOnlinesData',$applyOnlinesData);
		$this->set('ApplyOnlines',$applyOnlinesData);
		$this->set("applyOnlinesDataDocList",$applyOnlinesDataDocList);
		$this->set("GetProjectEstimation",$GetProjectEstimation);
		$this->set('transaction_id',$transaction_id);
		$this->set('payment_date',$payment_date);

		require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
		$dompdf = new Dompdf($options = array());
		$this->set('dompdf',$dompdf);
		$dompdf->set_base_path(SITE_ROOT_DIR_PATH.'/pdf/');
		//$dompdf->set_option('defaultFont', "Courier");

		$html = $this->render('/admin/ApplyOnlines/view');

		//exit($html);
		$dompdf->loadHtml($html,'UTF-8');
		$dompdf->setPaper('A4', 'portrait');

		$dompdf->render();

		// Output the generated PDF to Browser
		if($isdownload){
			$dompdf->stream('applyonline-'.$applyonline_id);
		}
		$output = $dompdf->output();
		$pdfPath = SITE_ROOT_DIR_PATH.'/tmp/applyonline-'.$applyonline_id.'.pdf';
		file_put_contents($pdfPath, $output);
		//return $pdfPath;
	}
	public function VarifyOtp() {
		$this->autoRender = false;
		$customerId = $this->ApiToken->customer_id;
		$id 				= (isset($this->request->data['appid'])?$this->request->data['appid']:0);
		$otp 				= (isset($this->request->data['otp'])?$this->request->data['otp']:'');
		if(empty($id) || empty($otp)) {
			$ErrorMessage 	= "Please Enter OTP.";
			$success 		= 'error';
			$this->ApiToken->SetAPIResponse('msg',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('type',$success);
		}
		else {
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->ApplyOnlines->find('all',array('Fields'=>['otp','id'],'conditions'=>array('id'=>$id)))->first();
			if (!empty($applyOnlinesData)) {
				if ($this->request->is('post') || $this->request->is('put')) {
					if($otp == $applyOnlinesData->otp) {
						if(!empty($applyOnlinesData->otp_created_date))
						{
							$otp_created_date 	= strtotime($applyOnlinesData->otp_created_date);
							$current_date 		= strtotime($this->NOW());
							$datediff 			= ($current_date - $otp_created_date);
							if(($datediff/(60)) > OTP_VALIDITY_TIME)
							{
								$ErrorMessage 	= "OTP has been expired. Click on Resend OTP button in order to get new OTP.";
								$success 		= 0;
								$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
								$this->ApiToken->SetAPIResponse('success',$success);
								echo $this->ApiToken->GenerateAPIResponse();
								exit;
							}
						}
						$application_status = $this->ApplyOnlineApprovals->APPLICATION_PENDING;
						$this->ApplyOnlines->updateAll(array('application_status'=>$application_status),array('id'=>$id));
						$this->ApplyOnlineApprovals->saveStatus($id,$this->ApplyOnlineApprovals->APPLICATION_PENDING,$customerId,'');
						//$this->SendApplicationLetterToCustomer($id);

						$this->Flash->set('OTP Verified successfully.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
			            $ErrorMessage 	= "OTP Verified successfully.";
						$success 		= 'ok';
						$this->ApiToken->SetAPIResponse('msg',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('type',$success);
					} else {
						$ErrorMessage 	= "Error while otp varification.";
						$success 		= 'error';
						$this->ApiToken->SetAPIResponse('msg',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('type',$success);
					}
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate form details.";
				$success 		= 'error';
				$this->ApiToken->SetAPIResponse('msg',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('type',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	public function resend_otp()
	{
		$this->autoRender 		= false;
		$customerId 			= $this->ApiToken->customer_id;
		$customersdata 			= $this->Customers->find('all',array('conditions' => array("id"=>$customerId)))->first();
		$installercnt			= $this->Installers->find('all', array('conditions'=>array('id'=>$customersdata['installer_id'])))->count();
        $is_installer 			= ($installercnt>0)?'true':'false';
		$id 					= (isset($this->request->data['appid'])?$this->request->data['appid']:0);
		$application_id    		= intval(decode($id));
		$ApplyOnlinesdata 		= $this->ApplyOnlines->find('all',array('conditions'=>array('id'=>$application_id)))->first();
		$ses_customer_type 		= $this->Session->read('Customers.customer_type');
		if(!empty($ApplyOnlinesdata))
		{
			$sms_mobile = $ApplyOnlinesdata->installer_mobile;
			if($is_installer==true)
			{
				$sms_mobile = $ApplyOnlinesdata->consumer_mobile;
			}
			$sms_message = str_replace('##application_no##',$ApplyOnlinesdata->application_no, OTP_RESEND);
			//urlencode("Your activation code is ACTIVATION_CODE for application #".$ApplyOnlinesdata->id);
			$this->ApplyOnlines->SendSMSActivationCode($ApplyOnlinesdata->id,$sms_mobile,$sms_message);
			$ErrorMessage 	= "OTP Resend successfully.";
			$success 		= 'ok';
			$this->ApiToken->SetAPIResponse('msg',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('type',$success);
		}
		else
		{
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 'error';
			$this->ApiToken->SetAPIResponse('msg',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('type',$success);
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	/**
	 *
	 * download_letter
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to download application.
	 *
	 */
	public function download_letter($app_id = null,$download_letter = null)
	{
		switch($download_letter){
			case "download_application":
	       		$this->generateApplicationPdf($app_id,false,true);
	        break;
	        case "geda_letter":
	        	$this->generateGedaLetterPdf($app_id,false,true);
	        break;
	    	default:
	        	echo "No files available.";
	        break;
		}
	}

	/**
	 *
	 * cei_fetchstatus
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to fetch status from thirdparty API and update or add record in cei application details.
	 *
	 */
	public function fetch_status_api()
	{
		$this->autoRender 	= false;
		$application_no 	= isset($this->request->data['application_no']) ? $this->request->data['application_no'] : '';
		$cei_drawing_no 	= isset($this->request->data['cei_drawing_no']) ? $this->request->data['cei_drawing_no'] : '';
		$cei_number 		= isset($this->request->data['cei_number']) ? $this->request->data['cei_number'] : '';
		$api_type 			= isset($this->request->data['api_type']) ? $this->request->data['api_type'] : '';
		$appid 				= decode($application_no);
		$pass_param 		= $cei_drawing_no;
		if($pass_param == '')
		{
			$pass_param 	= $cei_number;
		}
		$response 			= $this->ThirdpartyApiLog->third_party_call($appid,$pass_param,$api_type);
		$exist_cei 			= $this->CeiApplicationDetails->find('all',array('conditions'=>array('application_id'=>$appid)))->first();
		if(empty($exist_cei))
		{
			$ceiappEntity						= $this->CeiApplicationDetails->newEntity($this->request->data);
			$ceiappEntity->application_id 		= $appid;
			$ceiappEntity->created 				= $this->NOW();
			$ceiappEntity->updated 				= $this->NOW();
		}
		else
		{
			$getceidata 						= $this->CeiApplicationDetails->get($exist_cei->id);
			$ceiappEntity						= $this->CeiApplicationDetails->patchEntity($getceidata,$this->request->data);
			$ceiappEntity->updated 				= $this->NOW();
		}
		if($cei_drawing_no!='')
		{
			$ceiappEntity->drawing_app_no 		= $cei_drawing_no;
			$ceiappEntity->drawing_app_status	= $response;
			$ceiappEntity->status 				= '1';
			$status_update 						= $this->ApplyOnlineApprovals->DRAWING_APPLIED;
		}
		if($cei_number!='')
		{
			$ceiappEntity->cei_app_no 			= $cei_number;
			$ceiappEntity->cei_app_status		= $response;
			$ceiappEntity->status 				= '2';
			$status_update 						= $this->ApplyOnlineApprovals->CEI_APP_NUMBER_APPLIED;
		}
		if($this->CeiApplicationDetails->save($ceiappEntity)){
			$this->ApiToken->SetAPIResponse('msg',$response);
			$this->ApiToken->SetAPIResponse('type','ok');
		}
		else
		{
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 'error';
			$this->ApiToken->SetAPIResponse('msg',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('type',$success);
		}
		//$this->CeiApplicationDetails->save($ceiappEntity);

		$this->ApiToken->SetAPIResponse('fetch_status', $response);
			echo $this->ApiToken->GenerateAPIResponse();
			exit;
	}
	/**
	 *
	 * save_cei_number
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to save the cei drawing number and cei inspection number.
	 *
	 */
	public function save_cei_number(){
		$stage 				= (isset($this->request->data['stage'])?$this->request->data['stage']:0);
		switch($stage)
			{
				case 1:
					$application_id 	    = isset($this->request->data['application_id']) ? $this->request->data['application_id'] : '';
					$drawing_app_no 		= (isset($this->request->data['drawing_app_no'])?($this->request->data['drawing_app_no']):"");
					$drawing_app_status 	= (isset($this->request->data['drawing_app_status'])?($this->request->data['drawing_app_status']):"");
					$status = 0 ;
					$reason 				= '';
					$exist_cei 			= $this->CeiApplicationDetails->find('all',array('conditions'=>array('application_id'=>$application_id)))->first();
					$ceiappEntity       = $this->request->data;
					if(empty($exist_cei))
					{
							$ceiappEntity					= $this->CeiApplicationDetails->newEntity($this->request->data);
							$ceiappEntity->application_id 	= $application_id;
							$ceiappEntity->created 			= $this->NOW();
							$ceiappEntity->updated 			= $this->NOW();
					}
					else
					{
							$getceidata 					= $this->CeiApplicationDetails->get($exist_cei->id);
						    $ceiappEntity					= $this->CeiApplicationDetails->patchEntity($getceidata,$this->request->data);
				            $ceiappEntity->updated 			= $this->NOW();
			        }
			 		$ceiappEntity->status 			= '1';
					$status 						= $this->ApplyOnlineApprovals->DRAWING_APPLIED;
					if($this->CeiApplicationDetails->save($ceiappEntity))
					{
						$this->SetApplicationStatus($status,$application_id,$reason);
						if(strtolower($drawing_app_status) == 'completed')
						{
							$status 				= $this->ApplyOnlineApprovals->APPROVED_FROM_CEI;
							$this->SetApplicationStatus($status,$application_id,$reason);
						}
					}
					$this->ApiToken->SetAPIResponse('msg', 'Applicaton status updated.');
					$this->ApiToken->SetAPIResponse('type','ok');
					break;

				case 2:
					$application_id 	    = isset($this->request->data['application_id']) ? $this->request->data['application_id'] : '';
					$cei_app_no 			= (isset($this->request->data['cei_app_no'])?($this->request->data['cei_app_no']):"");
					$cei_app_status 		= (isset($this->request->data['cei_app_status'])?($this->request->data['cei_app_status']):"");
					$status = 0 ;
					$reason 				= '';
					$exist_cei 				= $this->CeiApplicationDetails->find('all',array('conditions'=>array('application_id'=>$application_id)))->first();
					$ceiappEntity       	= $this->request->data;
					if(empty($exist_cei))
					{
						$ceiappEntity					= $this->CeiApplicationDetails->newEntity($this->request->data);
						$ceiappEntity->application_id 	= $application_id;
						$ceiappEntity->created 			= $this->NOW();
						$ceiappEntity->updated 			= $this->NOW();
					}
					else
					{
						$getceidata 					= $this->CeiApplicationDetails->get($exist_cei->id);
						$ceiappEntity					= $this->CeiApplicationDetails->patchEntity($getceidata,$this->request->data);
						$ceiappEntity->updated 			= $this->NOW();
					}
					$ceiappEntity->status 				= '2';
					$status 							= $this->ApplyOnlineApprovals->CEI_APP_NUMBER_APPLIED;
					if($this->CeiApplicationDetails->save($ceiappEntity))
					{
						$this->SetApplicationStatus($status,$application_id,$reason);
						if(strtolower($cei_app_status) == 'completed')
						{
							$status 				= $this->ApplyOnlineApprovals->CEI_INSPECTION_APPROVED;
							$this->SetApplicationStatus($status,$application_id,$reason);
						}
					}

					$this->ApiToken->SetAPIResponse('msg', 'Applicaton status updated.');
					$this->ApiToken->SetAPIResponse('type','ok');
					break;

				default:
					$this->ApiToken->SetAPIResponse('msg', 'Applicaton status update failed.');
					$this->ApiToken->SetAPIResponse('type','error');
					break;

		}
		echo $this->ApiToken->GenerateAPIResponse();
			exit;
	}
	public function SetApplicationStatus($status,$id,$reason="")
	{
		$member_id 			= $this->Session->read('Members.id');
		$applyOnlinesData 	= $this->ApplyOnlines->viewApplication($id);
		if ($this->ApplyOnlineApprovals->validateNewStatus($status,$applyOnlinesData->application_status)) {
			$arrData 		= array("application_status"=>$status);
        	$this->ApplyOnlines->updateAll($arrData,['id' => $id]);
        	$sms_text 		= '';
        	$subject 		= '';
        	$EmailVars 		= array();
        	if($status==$this->ApplyOnlineApprovals->DRAWING_APPLIED)
        	{
        		$sms_text 			= str_replace('##geda_application_no##',$applyOnlinesData->geda_application_no,DRAWING_APPLIED);
				$subject 			= "[REG: GEDA Application No. ".$applyOnlinesData->geda_application_no."] CEI Drawing Applied";
				$CUSTOMER_NAME 		= trim($applyOnlinesData->customer_name_prefixed." ".$applyOnlinesData->name_of_consumer_applicant." ".$applyOnlinesData->last_name." ".$applyOnlinesData->third_name);
				$cei_data 			= $this->CeiApplicationDetails->find('all',array('conditions'=>array('application_id'=>$id)))->first();
				$drawing_number 	= '';
				if(!empty($cei_data))
				{
					$drawing_number = $cei_data->drawing_app_no;
				}
				$EmailVars 			= array("CUSTOMER_NAME"=>$CUSTOMER_NAME,'CEI_DRAWING_NUMBER'=>$drawing_number);
				$template_applied 	= 'drawing_applied';
        	}
        	else if($status==$this->ApplyOnlineApprovals->CEI_APP_NUMBER_APPLIED)
        	{
        		$sms_text 			= str_replace('##geda_application_no##',$applyOnlinesData->geda_application_no,CEI_APP_NUMBER_APPLIED);
				$subject 			= "[REG: GEDA Application No. ".$applyOnlinesData->geda_application_no."] CEI Application Number";
				$CUSTOMER_NAME 		= trim($applyOnlinesData->customer_name_prefixed." ".$applyOnlinesData->name_of_consumer_applicant." ".$applyOnlinesData->last_name." ".$applyOnlinesData->third_name);
				$cei_data	        = $this->CeiApplicationDetails->find('all',array('conditions'=>array('application_id'=>$id)))->first();
				$cei_app_no         = '';
				if(!empty($cei_data))
				{
					$cei_app_no     = $cei_data->cei_app_no;
				}
				$EmailVars 			= array("CUSTOMER_NAME"=>$CUSTOMER_NAME,'CEI_APPLICATION_NUMBER'=>$cei_app_no);
				$template_applied 	= 'ceinumber_applied';
        	}
        	else if($status==$this->ApplyOnlineApprovals->APPROVED_FROM_GEDA)
        	{
        		$sms_text 			= str_replace('##application_no##',$applyOnlinesData->application_no, GEDA_APPROVAL);
				$subject 			= "[REG: Application No. ".$applyOnlinesData->application_no."] Inspection From GEDA";
				$CUSTOMER_NAME 		= trim($applyOnlinesData->customer_name_prefixed." ".$applyOnlinesData->name_of_consumer_applicant." ".$applyOnlinesData->last_name." ".$applyOnlinesData->third_name);
				$EmailVars 			= array("CUSTOMER_NAME"=>$CUSTOMER_NAME,'application_no'=>$applyOnlinesData->application_no);
				$template_applied 	= 'geda_approval';
        	}
        	else if($status==$this->ApplyOnlineApprovals->CLAIM_SUBSIDY)
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
					$this->ApplyOnlines->sendSMS($id,$applyOnlinesData->installer_mobile,$sms_text);
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
			    }
			    $to 	= $applyOnlinesData->consumer_email;
			    if(empty($to))
			    {
			    	$to = $applyOnlinesData->email;
			    }
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
			    }
        	}
		}
		$this->ApplyOnlineApprovals->saveStatus($id,$status,$member_id,$reason);
	}
	public function ReplayMessage()
	{
		$this->autoRender 	= false;
		$id 				= isset($this->request->data['id']) ? $this->request->data['id'] : '';

		$message 			= isset($this->request->data['message']) ? $this->request->data['message'] : '';
		if(empty($id) || empty($message)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} else {
			$id 					= intval(decode($id));
			$applyOnlinesData 		= $this->ApplyOnlines->viewApplication($id);
			if (!empty($applyOnlinesData)) {
				if(isset($this->request->data) && !empty($this->request->data)){
					$customerId      = $this->ApiToken->customer_id;
					$browser 							= isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:"-";
					$ApplyonlineMessageEntity					= $this->ApplyonlineMessage->newEntity();
					$ApplyonlineMessageEntity->application_id 	= $id;
					$ApplyonlineMessageEntity->message 			= strip_tags($message);
					$ApplyonlineMessageEntity->user_type 		= 0;
					$ApplyonlineMessageEntity->user_id 			= $customerId;
					$ApplyonlineMessageEntity->ip_address 		= $this->IP_ADDRESS;
					$ApplyonlineMessageEntity->created 			= $this->NOW();
					$ApplyonlineMessageEntity->browser_info 	= json_encode($browser);
					if($this->ApplyonlineMessage->save($ApplyonlineMessageEntity)) {
						$applyid=$applyOnlinesData->id;
						if(!empty($applyid)){

							$data=$this->ApplyOnlines->get($applyid);
							$data->query_sent='0';
							$data->query_date=date('0-0-0 0:0:0');
							$this->ApplyOnlines->save($data);

						}
						$this->ApplyOnlines->SendEmailToCustomer($id,$ApplyonlineMessageEntity->id);
						$ErrorMessage 	= "Message sent successfully.";
						$success 		= 1;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					} else {
						$ErrorMessage 	= "Error while sending message.";
						$success 		= 0;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					}
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate form details.";
				$success 		= 0;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	/**
	 *
	 * fetchprojectdata
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to fetch data from project details according to application id.
	 *
	 */
	public function fetchprojectdata()
	{
		$id 	= isset($this->request->data['id']) ? $this->request->data['id'] : '';
		$appid 				= intval(decode($id));
		$application_data 	= $this->ApplyOnlines->find('all',array('conditions'=>array('id'=>$appid)))->first();
		$project_data 		= $this->Projects->find('all',array('conditions'=>array('id'=>$application_data->project_id)))->first();
		$areaTypeArr    	= $this->Parameters->getAreaType();
		$arr_data=array();
		if(!empty($project_data))
		{
			$arearType 		= (isset($project_data->area_type)?$project_data->area_type:'');
            $project_data->area_type_text = (!empty($arearType) && isset($areaTypeArr[$arearType])) ? $areaTypeArr[$arearType] : '';
            $arr_data['rooftop_area']  			 = $project_data->area.' '.$project_data->area_type_text;
            $arr_data['avg_mothly_consumption']  = $project_data->estimated_kwh_year.' '.'kWh';
            $arr_data['avg_mothly_bill']         = $project_data->avg_monthly_bill.' '.'Rs';
            $arr_data['recommended_capacity']	 = $project_data->recommended_capacity.' '.'kW';
            $arr_data['estimated_cost']			 = $project_data->estimated_cost.' '.'Rs';
            $arr_data['subsidy_amt']			 = ($project_data->estimated_cost_subsidy/100000).' '.'Rs';
            $result = $arr_data;

            if(isset($this->request->data['submit']) && $this->request->data['submit'] == 1){

				$status = $this->ApplyOnlineApprovals->CLAIM_SUBSIDY;
				$reason = '';
				$this->SetApplicationStatus($status,$appid,$reason);
				$this->ApiToken->SetAPIResponse('msg', 'Applicaton status updated.');
				$this->ApiToken->SetAPIResponse('type','ok');

            }
            $this->ApiToken->SetAPIResponse('result',$result);
				$status = 'ok';
				$this->ApiToken->SetAPIResponse('type', $status);
		}
		else
		{
			$result 	= "Invalid Request.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	/**
	 *
	 * getSubDivisionTorrent
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to get subdivision of surat and ahmedabad torrent
	 *
	 */
	public function getSubDivisionTorrent()
	{
		$this->autoRender 					= false;
		$division_id 						= isset($this->request->data['division'])?$this->request->data['division']:0;
		$discom_details 					= $this->DiscomMaster->find("all",['conditions'=>['DiscomMaster.division'=>$division_id,'DiscomMaster.type'=>4,'status'=>'1']])->first();
		$data['subdivision']				= '';
		if(!empty($discom_details))
		{
			$data['subdivision']['title']	= $discom_details->title;
			$data['subdivision']['id']		= $discom_details->id;
		}
		$this->ApiToken->SetAPIResponse('msg', 'list of subdivision');
		$this->ApiToken->SetAPIResponse('data', $data);
		$this->ApiToken->SetAPIResponse('type','ok');
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	/**
	 *
	 * getSubDivisionOther
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to get subdivision of division which not having in torrent ahmedabad / surat
	 *
	 */
	public function getSubDivisionOther()
	{
		$this->autoRender 		= false;
		$division_id 			= isset($this->request->data['division'])?$this->request->data['division']:0;
		$discom_details 		= $this->DiscomMaster->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['DiscomMaster.division'=>$division_id,'DiscomMaster.type'=>4,'status'=>'1']])->toArray();
		$data['subdivision']	= '';
		if(!empty($discom_details))
		{
			$data['subdivision']= $discom_details;
		}
		$this->ApiToken->SetAPIResponse('msg', 'list of subdivision');
		$this->ApiToken->SetAPIResponse('data', $data);
		$this->ApiToken->SetAPIResponse('type','ok');
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	/**
	*
	* getDivision
	*
	* Behaviour : public
	*
	* Parameter : discom
	*
	* @defination : Method is used to get division for perticular discom.
	*
	*/
	public function getDivision() {
		$this->autoRender 		= false;
		$discom 				= isset($this->request->data['discom'])?$this->request->data['discom']:0;
		$data 					= array();
		if (!empty($discom)) {
			$branch_detail 		= $this->BranchMasters->find('all',array('conditions'=>array('id'=>$discom)))->first();
			$division 			= $this->DiscomMaster->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['DiscomMaster.area'=>$branch_detail->discom_id,'DiscomMaster.type'=>3,'status'=>'1']]);
			$data['division'] 	= $division;
		}
		$this->ApiToken->SetAPIResponse('msg', 'list of division');
		$this->ApiToken->SetAPIResponse('data', $data);
		$this->ApiToken->SetAPIResponse('type','ok');
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	/**
	 *
	 * save_other_doc
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to add other doc and profile image
	 *
	 */
	public function save_other_doc($data,$ApplyOnlinesEntityId,$doc_type='others',$customer_id){
		$ids 		= array(0,0);
		$image_path = APPLYONLINE_PATH.$ApplyOnlinesEntityId.'/';
		if(!empty($data['Aplication_doc_title']))
		{
			foreach ($data['Aplication_doc_title'] as $key => $value) {
	        	if(!empty($data['Aplication_doc_title'][$key])) {
		            if(!empty($data['Aplication_doc_id'][$key])) {
		                $getEntity  			= $this->ApplyonlinDocs->get($data['Aplication_doc_id'][$key]);
		                $ApplyonlinDocsEntity 	= $this->ApplyonlinDocs->patchEntity($getEntity,array());
		            } else {
		            	$ApplyonlinDocsEntity 	= $this->ApplyonlinDocs->newEntity();
		            }
		            if(isset($data['Aplication_doc_file'][$key]) && !empty($data['Aplication_doc_file'][$key]['name'])) {
						$db_file_name = $ApplyonlinDocsEntity->file_name;
						if(file_exists($image_path.$db_file_name)){
							@unlink($image_path.$db_file_name);
							@unlink($image_path.'r_'.$db_file_name);
						}
						$image_path = APPLYONLINE_PATH.$ApplyOnlinesEntityId.'/';
						$file_name = $this->file_upload($image_path,$data['Aplication_doc_file'][$key],true,65,65,$image_path,'doc_'.$key);
						$ApplyonlinDocsEntity->doc_type 	= $doc_type;
						$ApplyonlinDocsEntity->file_name 	= $file_name;
						$ApplyonlinDocsEntity->created 		= $this->NOW();
					}
		            $ApplyonlinDocsEntity->title 			= $data['Aplication_doc_title'][$key];
		            $ApplyonlinDocsEntity->application_id 	= $ApplyOnlinesEntityId;
		            $this->ApplyonlinDocs->save($ApplyonlinDocsEntity);
		            $ids[] = $ApplyonlinDocsEntity->id;
		        }
	        }
		}
		$key  = 0;
        if(isset($data['profile_image']) && !empty($data['profile_image']['name']))
		{
			if(!empty($data['profile_image_id'])) {
	                $getEntity  			= $this->ApplyonlinDocs->get($data['profile_image_id']);
	                $ApplyonlinDocsEntity 	= $this->ApplyonlinDocs->patchEntity($getEntity,array());
	        } else {
	            	$ApplyonlinDocsEntity 	= $this->ApplyonlinDocs->newEntity();
	        }
			$db_file_name 	= $ApplyonlinDocsEntity->file_name;
			if(file_exists($image_path.$db_file_name)){
				@unlink($image_path.$db_file_name);
				@unlink($image_path.'r_'.$db_file_name);
			}
			$image_path 	= APPLYONLINE_PATH.$ApplyOnlinesEntityId.'/';
			$file_name 		= $this->file_upload($image_path,$data['profile_image'],true,65,65,$image_path,'doc_'.$key);
			$ApplyonlinDocsEntity->doc_type 		= 'profile';
			$ApplyonlinDocsEntity->file_name 		= $file_name;
			$ApplyonlinDocsEntity->application_id 	= $ApplyOnlinesEntityId;
			$ApplyonlinDocsEntity->created 			= $this->NOW();
			$this->ApplyonlinDocs->save($ApplyonlinDocsEntity);
			$ids[] = $ApplyonlinDocsEntity->id;
		}
		if(isset($data['signed_doc']) && !empty($data['signed_doc']['name']))
		{
			$prefix_file 	= '';
			$name 			= $data['signed_doc']['name'];
			$ext 			= substr(strtolower(strrchr($name, '.')), 1);
			$file_name 		= $prefix_file.date('Ymdhms').rand();
	        $uploadPath 	= APPLYONLINE_PATH.$ApplyOnlinesEntityId.'/';
			if(!file_exists(APPLYONLINE_PATH.$ApplyOnlinesEntityId))
			{
				@mkdir(APPLYONLINE_PATH.$ApplyOnlinesEntityId, 0777);
			}
	       	$file_location 			= WWW_ROOT.$uploadPath.'doc'.'_'.$file_name.'.'.$ext;
			$ApplyonlinDocsEntity 	= $this->ApplyonlinDocs->newEntity();

			$image_path = APPLYONLINE_PATH.$ApplyOnlinesEntityId.'/';
			if(move_uploaded_file($data['signed_doc']['tmp_name'],$file_location))
			{
				$ApplyonlinDocsEntity->doc_type 		= 'Signed_Doc';
				$ApplyonlinDocsEntity->file_name 		= 'doc'.'_'.$file_name.'.'.$ext;
				$ApplyonlinDocsEntity->application_id 	= $ApplyOnlinesEntityId;
				$ApplyonlinDocsEntity->created 			= $this->NOW();
				$ApplyonlinDocsEntity->title            = 'Upload_Document';
				$this->ApplyonlinDocs->save($ApplyonlinDocsEntity);
				$ids[] = $ApplyonlinDocsEntity->id;
			}
			$application_status 	= $this->ApplyOnlineApprovals->APPLICATION_SUBMITTED;
			$this->ApplyOnlines->updateAll(array('application_status'=>$application_status),array('id'=>$ApplyOnlinesEntityId));
			$this->ApplyOnlineApprovals->saveStatus($ApplyOnlinesEntityId,$this->ApplyOnlineApprovals->APPLICATION_SUBMITTED,$customer_id,'');
			$application_status 	= $this->ApplyOnlineApprovals->APPROVED_FROM_GEDA;
			$this->ApplyOnlines->updateAll(array('application_status'=>$application_status),array('id'=>$ApplyOnlinesEntityId));
			$this->ApplyOnlineApprovals->saveStatus($ApplyOnlinesEntityId,$this->ApplyOnlineApprovals->APPROVED_FROM_GEDA,$customer_id,'');
			$status 				= $this->ApplyOnlineApprovals->APPROVED_FROM_GEDA;
			$reason 				= "";
			$this->SetApplicationStatus($status,$ApplyOnlinesEntityId,$reason);
		}
    }
	/**
	 *
	 * add_application
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to add application from admin panel
	 *
	 */
	public function add_application($id = 0, $project_id = 0)
	{
		//$this->setAdminArea();
		$ApplyOnlinesEntity	= $this->ApplyOnlines->newEntity($this->request->data);
		if(isset($this->request->data) && !empty($this->request->data))
		{
			//print_r($this->request->session());
			 //print_r($this->request->session());
			//echo $this->request->session()->read('User.usertype');
			//exit;

			$installer_id = $this->request->data['ApplyOnlines']['installer_id'];

			$customer_data= $this->Customers->find('all',array('conditions'=>array('installer_id'=>$installer_id)))->first();
			$this->Projects->data_post 					= $this->request->data;
			$this->Projects->data_post['ins_id'] 		= $installer_id;
			$ApplyOnlinesEntity							= $this->Projects->newEntity($this->request->data);
			$project_errors 							= $ApplyOnlinesEntity->errors();

			if(!$ApplyOnlinesEntity->errors())
			{
				$this->request->data['project_social_consumer']		= 0;
				$customerId 										= $customer_data->id;
				$resultArr = $this->Projects->getprojectestimation($this->request->data,$customerId);
				$this->request->data['ApplyOnlines']['project_id'] 	= $resultArr['proj_id'];
				$this->request->data['ApplyOnlines']['lattitue'] 	= $this->request->data['latitude'];
				$this->request->data['ApplyOnlines']['longitude'] 	= $this->request->data['longitude'];
				$this->request->data['ApplyOnlines']['apply_state'] = 4;
				$this->request->data['ApplyOnlines']['disclaimer']  = 1;
				$ApplyOnlinesEntity	= $this->ApplyOnlines->newEntity($this->request->data);
				$ApplyOnlinesEntity->common_meter 	 				= isset($this->request->data['project_common_meter']) ? $this->request->data['project_common_meter'] : '0';
				$ApplyOnlinesEntity->social_consumer 	 			= 0;
				$ApplyOnlinesEntity->roof_of_proposed 				= $this->request->data['area'];
				$ApplyOnlinesEntity->bill 							= $this->request->data['avg_monthly_bill'];
				$ApplyOnlinesEntity->energy_con 					= $this->request->data['energy_con'];
				$ApplyOnlinesEntity->house_tax_holding_no 			= passencrypt($this->request->data['ApplyOnlines']['house_tax_holding_no']);
				$ApplyOnlinesEntity->aadhar_no_or_pan_card_no		= passencrypt($this->request->data['ApplyOnlines']['aadhar_no_or_pan_card_no']);
				$ApplyOnlinesEntity->customer_id					= $customerId;
				$arrDiscom 											= $this->DiscomMaster->GetDiscomHirarchyByID($ApplyOnlinesEntity->discom_name);
				$ApplyOnlinesEntity->area 							= $arrDiscom->area;
				$ApplyOnlinesEntity->circle 						= $arrDiscom->circle;
				$ApplyOnlinesEntity->division 						= $ApplyOnlinesEntity->discom_name;
				$ApplyOnlinesEntity->category 						= $this->request->data['project_type'];
				$ApplyOnlinesEntity->created 						= $this->NOW();
				$ApplyOnlinesEntity->created_by						= $installer_id;
				$this->ApplyOnlines->save($ApplyOnlinesEntity);
				$image_path = APPLYONLINE_PATH.$ApplyOnlinesEntity->id.'/';
				if(!file_exists(APPLYONLINE_PATH.$ApplyOnlinesEntity->id)) {
					@mkdir(APPLYONLINE_PATH.$ApplyOnlinesEntity->id, 0777);
				}
				if(!empty($this->request->data['ApplyOnlines']['file_attach_photo_scan_of_aadhar']) && !empty($this->request->data['ApplyOnlines']['file_attach_photo_scan_of_aadhar']['name'])) {
					$db_attach_photo_scan_of_aadhar = $ApplyOnlinesEntity->attach_photo_scan_of_aadhar;
					if(file_exists($image_path.$db_attach_photo_scan_of_aadhar)){
						//@unlink($image_path.$db_attach_photo_scan_of_aadhar);
						//@unlink($image_path.'r_'.$db_attach_photo_scan_of_aadhar);
					}
					$file_name = $this->file_upload($image_path,$this->request->data['ApplyOnlines']['file_attach_photo_scan_of_aadhar'],true,65,65,$image_path,'aadhar');
					$this->ApplyOnlines->updateAll(['attach_photo_scan_of_aadhar' => $file_name], ['id' => $ApplyOnlinesEntity->id]);
				}
				if(!empty($this->request->data['ApplyOnlines']['file_attach_recent_bill']) && !empty($this->request->data['ApplyOnlines']['file_attach_recent_bill']['name'])) {
					$db_attach_recent_bill = $ApplyOnlinesEntity->attach_recent_bill;
					if(file_exists($image_path.$db_attach_recent_bill)){
						//@unlink($image_path.$db_attach_recent_bill);
						//@unlink($image_path.'r_'.$db_attach_recent_bill);
					}
					$file_name = $this->file_upload($image_path,$this->request->data['ApplyOnlines']['file_attach_recent_bill'],true,65,65,$image_path,'recent');
					$this->ApplyOnlines->updateAll(['attach_recent_bill' => $file_name], ['id' => $ApplyOnlinesEntity->id]);
				}
				if(!empty($this->request->data['ApplyOnlines']['file_attach_latest_receipt']) && !empty($this->request->data['ApplyOnlines']['file_attach_latest_receipt']['name']) ) {
					$db_attach_recent_bill = $ApplyOnlinesEntity->attach_latest_receipt;
					if(file_exists($image_path.$db_attach_recent_bill)){
						//@unlink($image_path.$db_attach_recent_bill);
						//@unlink($image_path.'r_'.$db_attach_recent_bill);
					}
					$file_name = $this->file_upload($image_path,$this->request->data['ApplyOnlines']['file_attach_latest_receipt'],true,65,65,$image_path,'tax_receipt_');
					$this->ApplyOnlines->updateAll(['attach_latest_receipt' => $file_name], ['id' => $ApplyOnlinesEntity->id]);
				}
				if(!empty($this->request->data['ApplyOnlines']['file_attach_pan_card_scan']) && !empty($this->request->data['ApplyOnlines']['file_attach_pan_card_scan']['name']) ) {
					$db_attach_pan_card_scan = $ApplyOnlinesEntity->attach_pan_card_scan;
					if(file_exists($image_path.$db_attach_pan_card_scan)){
						//@unlink($image_path.$db_attach_pan_card_scan);
						//@unlink($image_path.'r_'.$db_attach_pan_card_scan);
					}
					$file_name = $this->file_upload($image_path,$this->request->data['ApplyOnlines']['file_attach_pan_card_scan'],true,65,65,$image_path,'pan_');
					$this->ApplyOnlines->updateAll(['attach_pan_card_scan' => $file_name], ['id' => $ApplyOnlinesEntity->id]);
				}
				$this->save_other_doc($this->request->data['ApplyOnlines'],$ApplyOnlinesEntity->id,'others',$customerId);
				$this->Flash->success('Application added successfully.');
				return $this->redirect(WEB_ADMIN_PREFIX.'/ApplyOnlines');
			}
		}
		else
		{
			//$this->request->data['ApplyOnlines']['transmission_line']	= 1;
			//$this->request->data['ApplyOnlines']['net_meter']			= 1;
		}

		$this->ApplyOnlines->data_entity 	= $ApplyOnlinesEntity;
		$ApplyOnlinesEntity->created 		= $this->NOW();
		$installersList 	= $this->Installers->find('list',['keyField'=>'id','valueField'=>'installer_name'])->toArray();
		$discom_arr 		= array();
		$state 				= 4;
		$discoms 			= $this->BranchMasters->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['BranchMasters.status'=>'1','BranchMasters.parent_id'=>'0','BranchMasters.state'=>$state]])->toArray();
		if(!empty($discoms)) {
			foreach($discoms as $id=>$title) {
				$discom_arr[$id] = $title;
			}
		}
		$discom_list 		= $this->DiscomMaster->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['DiscomMaster.state_id'=>$state,'DiscomMaster.type'=>3,'status'=>'1']]);
		$this->set('ApplyOnlines',$ApplyOnlinesEntity);
		$this->set('pageTitle','Apply Online');
		$this->set('installersList',$installersList);
		$this->set('ApplyOnlineErrors',$ApplyOnlinesEntity->errors());
		$this->set('projectTypeArr',$this->Parameters->GetParameterList(3));
		$this->set('areaTypeArr',$this->Parameters->getAreaType());
		$this->set('backupTypeArr',$this->Projects->backupTypeArr);
		$this->set('discom_arr',$discom_arr);
		$this->set('discom_list',$discom_list);
		$this->set('ApplyOnlineObj',$this->ApplyOnlines);
		$this->set('customer_name_prifix',$this->Customers->customer_name_prifix);
	}

	public function mapview()
	{
		$this->intCurAdminUserRight = $this->Userright->LIST_APPLYONLINES;
		$this->setAdminArea();
		$Installers 		= $this->Installers->find('list',['keyField'=>'id','valueField'=>'installer_name'])->toArray();
		$discom_arr 		= array();
		$state 				= 4;
		$discoms 			= $this->BranchMasters->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['BranchMasters.status'=>'1','BranchMasters.parent_id'=>'0','BranchMasters.state'=>$state]])->toArray();
		$this->set('getProjectClusterData',$this->getProjectClusterData());
		$this->set('Categories',$this->Parameters->GetParameterList(3));
		$this->set('Discoms',$discoms);
		$this->set('Installers',$Installers);
	}

	/**
    * Function Name : random_color_marker
    * @param $map_icons
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
    * Function Name : getProjectClusterData
    * @param
    * @return
    * @author Kalpak Prajapati
    */
    public function getProjectClusterData()
    {
        $arrResult['data']      = array();
        $arrResult['map_style'] = '';
        $arrResult['map_icons'] = '';
        $resultArray        	= array();
        $arrCondition 			= array('ApplyOnlines.lattitue IS NOT ' => NULL,
    									'ApplyOnlines.longitude IS NOT ' => NULL,
    									'ApplyOnlines.application_status IS NOT ' => NULL);
		$Joins 					= array('Parameters' => ['table' => 'parameters','type' => 'INNER','conditions' => ['Parameters.para_id = ApplyOnlines.category']],
										'Installers' => ['table' => 'installers','type' => 'INNER','conditions' => ['Installers.id = ApplyOnlines.installer_id']]);
        $arrFields 				= array('Parameters.para_value','ApplyOnlines.category','ApplyOnlines.pv_capacity',
	        							'ApplyOnlines.lattitue','ApplyOnlines.longitude','Installers.installer_name',
	        							'ApplyOnlines.geda_application_no','ApplyOnlines.application_no');
        if (isset($this->request->data['ApplyOnlines']['discom']) && !empty($this->request->data['ApplyOnlines']['discom'])) {
        	$arrCondition['ApplyOnlines.discom'] = intval($this->request->data['ApplyOnlines']['discom']);
        }
        if (isset($this->request->data['ApplyOnlines']['installer']) && !empty($this->request->data['ApplyOnlines']['installer'])) {
        	$arrCondition['ApplyOnlines.installer_id'] = intval($this->request->data['ApplyOnlines']['installer']);
        }
        if (isset($this->request->data['ApplyOnlines']['category']) && !empty($this->request->data['ApplyOnlines']['category'])) {
        	$arrCondition['ApplyOnlines.category'] = intval($this->request->data['ApplyOnlines']['category']);
        }
        if (isset($this->request->data['ApplyOnlines']['city']) && !empty($this->request->data['ApplyOnlines']['city'])) {
        	$arrCondition['ApplyOnlines.city LIKE '] = "%".$this->request->data['ApplyOnlines']['city']."%";
        }
		if (isset($this->request->data['ApplyOnlines']['meter_installed']) && $this->request->data['ApplyOnlines']['meter_installed'] != '') {
			if ($this->request->data['ApplyOnlines']['meter_installed'] == 1 || $this->request->data['ApplyOnlines']['meter_installed'] == 0) {
				$Joins['Meter_Installed'] = ['table' => 'apply_online_approvals',
											'type' => 'LEFT',
											'conditions' => ['Meter_Installed.application_id = ApplyOnlines.id AND Meter_Installed.stage = '.$this->ApplyOnlineApprovals->METER_INSTALLATION]];
				$arrFields['Meter_Installed_Status'] = "IF(Meter_Installed.id > 0,'Y','N')";
			}
        }
        $arrClusterRows  = $this->ApplyOnlines
        						->find('all',['fields'=>$arrFields,'join'=>$Joins])
        						->hydrate(false)
        						->where($arrCondition);
        if (isset($this->request->data['ApplyOnlines']['meter_installed']) && $this->request->data['ApplyOnlines']['meter_installed'] != '') {
	        if (isset($this->request->data['ApplyOnlines']['meter_installed']) && $this->request->data['ApplyOnlines']['meter_installed'] == 1) {
				$arrClusterRows->having("Meter_Installed_Status = 'Y'");
			} else if (isset($this->request->data['ApplyOnlines']['meter_installed']) && $this->request->data['ApplyOnlines']['meter_installed'] == 0) {
				$arrClusterRows->having("Meter_Installed_Status = 'N'");
			}
		}
		$arrClusterRows->order('ApplyOnlines.category','ASC');
        $arrResultRows	= $arrClusterRows->toList();
        $map_icons      = array();
        $map_style      = array();
        $Counter        = 0;
        $ApplicationCnt = 0;
        $Prev_Group_Id  = 0;
        if (!empty($arrResultRows)) {
            foreach ($arrResultRows as $Row) {
            	$TAG = preg_replace("/[^0-9a-z]/i","",strtolower($Row['Parameters']['para_value']));
                if (!isset($map_icons[$Row['category']])) {
                    $COLOR_CODE     = $this->random_color_marker($map_style);
                    array_push($map_style,$COLOR_CODE);
                    $map_icons[$Row['category']]   = array("group"=>$Row['Parameters']['para_value'],
                                                            "lbl"=>$TAG,
                                                            "count"=>0,
                                                            "icon"=>$COLOR_CODE);

                    if ($Prev_Group_Id > 0) {
                        $map_icons[$Prev_Group_Id]['count'] = $Counter;
                        $Counter = 0;
                    }
                    $Prev_Group_Id = $Row['category'];
                }
                $Application_No 		= (!empty($Row['geda_application_no'])?$Row['geda_application_no']:$Row['application_no']);
                $arrResult['data'][] 	= array("lat"=>$Row['lattitue'],
                                                "lng"=>$Row['longitude'],
                                                "options"=>array("icon"=>"/img/mapIcons/pins/".$map_icons[$Row['category']]['icon']),
                                                "tag"=>$TAG,
                                            	"data"=>array(	"Category"=>$Row['Parameters']['para_value'],
                                            					"Installer"=>$Row['Installers']['installer_name'],
                                            					"Capacity"=>$Row['pv_capacity'],
                                            					"Application_No"=>$Application_No));

                $Counter++;
                $ApplicationCnt++;
            }
            if ($Prev_Group_Id > 0) {
                $map_icons[$Prev_Group_Id]['count'] = $Counter;
                $Counter = 0;
            }
        }
        $this->set("ApplicationCnt",$ApplicationCnt);
        $arrResult['map_icons'] = $map_icons;
        return $arrResult;
    }
    /**
     *
     * findcurrentstage
     *
     * Behaviour : Public
     *
     * @defination : Method is use to find current stage of application.
     */
	public function findcurrentstage($applyOnlinesData)
	{
		$arr_application_status     = $this->ApplyOnlineApprovals->all_status_application($applyOnlinesData->id);
        $APPLY_ONLINE_MAIN_STATUS   = $this->ApplyOnlineApprovals->apply_online_main_status;
       	$stage_data 				= array();
        $stage_data['no']       	= "";
	    $stage_data['title']    	= "";
	    $stage_data['flag']     	= "";
        foreach ($APPLY_ONLINE_MAIN_STATUS as $key => $value)
        {
        	if($key == 9 && SHOW_SUBSIDY_EXECUTION == 1 && $applyOnlinesData->disclaimer_subsidy == 1)
            {

            }
            else{
     			$IsActive = array_key_exists($key, $arr_application_status)?$status_flag=1:$status_flag = 0;
                if($status_flag == 1){
	                $stage_data['no']       = $key;
	                $stage_data['title']    = $value;
	                $stage_data['flag']     = $IsActive;
                }
            }
        }
		return  $stage_data;
	}
}