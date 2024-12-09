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

class InstallerSubscriptionController extends AppController
{	
	var $helpers = array('Time','Html','Form','ExPaginator');
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
		$this->loadModel('InstallerSubscription');
		$this->loadModel('Installers');
		$this->loadModel('InstallerPlans');
		$this->set('Userright',$this->Userright);
    }
    public function get_subscription($installer_id = null)
    {
    	$this->autoRender 	= false;
		$ins_id 			= $this->request->data['installer_id'];
		$installer_id 		= decode($ins_id);
		$arrAdminuserList	= array();
		$arrUserType		= array();
		$arrCondition		= array();
		$this->SortBy		= "InstallerSubscription.id";
		$this->Direction	= "ASC";
		$this->intLimit		= PAGE_RECORD_LIMIT;
		$this->CurrentPage  = 1;
		$option  			= array();	
		$option['colName']  = array('id','plan_name','plan_price','coupen_code','amount','is_flat','payment_status','start_date','expire_date','comment','status');
		$sortArr=array();
		$this->SetSortingVars('InstallerSubscription',$option,$sortArr);
		$arrCondition		= $this->_generateInstallerSubscriptionSearchCondition($installer_id);
		$query_data         = $this->InstallerSubscription->find('all',array(
									'fields'=>array('InstallerSubscription.id','InstallerSubscription.plan_name','InstallerSubscription.plan_price','InstallerSubscription.coupen_code','InstallerSubscription.amount','InstallerSubscription.is_flat','InstallerSubscription.payment_status','InstallerSubscription.start_date','InstallerSubscription.expire_date','InstallerSubscription.comment','InstallerSubscription.status'),
									'conditions' => $arrCondition,
									'order'=>array($this->SortBy=>$this->Direction),
									'page'=> $this->CurrentPage,
									'limit' => $this->intLimit));
			
		$start_page=isset($this->request->data['start']) ? $this->request->data['start'] : 1;				
		$this->paginate['limit'] = PAGE_RECORD_LIMIT;
		$this->paginate['page']  = ($start_page/$this->paginate['limit'])+1;
		$arrAdminuserList 		= $this->paginate($query_data);
		
		$arrStatus =array(''=>'Select','pending'=>'Pending', 'inprocess'=>'In Process', 'completed'=>'Completed','deleted'=>'Deleted');
		$usertypes = array();
		$option['dt_selector']	='table-example-survey';
		$option['formId']		='formmain_surveys';
		if(!empty($project_id)){
			$option['url']			= WEB_ADMIN_PREFIX.'InstallerSubscription/get_subscription/'.$pr_id;
		}else{
			$option['url']			= WEB_ADMIN_PREFIX.'InstallerSubscription/';
		}
		$JqdTablescr = $this->JqdTable->create($option);
		$this->set('arrAdminuserList',$arrAdminuserList->toArray());
		$this->set('JqdTablescr',$JqdTablescr);
		$this->set('arrStatus',$arrStatus);
		$this->set('period',$this->period);
		$this->set('limit',$this->intLimit);
		$this->set("CurrentPage",$this->CurrentPage);
		$this->set("SortBy",$this->SortBy);
		$this->set("Direction",$this->Direction);
		$this->set("page_count",(isset($this->request->params['paging']['InstallerSubscription']['pageCount'])?$this->request->params['paging']['InstallerSubscription']['pageCount']:0));
		$out=array();
		$counter_id=1;
		foreach($arrAdminuserList->toArray() as $key=>$val) {
			$temparr=array();
			foreach($option['colName'] as $key) {
				if($key=='start_date') {
					$temparr[$key] = date('d-m-Y',strtotime($val[$key]));
					if(strtolower($temparr['payment_status']) == 'failure')
					{
						$temparr[$key] = '-';
					}
				}
				else if($key=='expire_date') {
					$temparr[$key] = date('d-m-Y',strtotime($val[$key]));
					if(strtolower($temparr['payment_status']) == 'failure')
					{
						$temparr[$key] = '-';
					}
				}
				else if($key=='payment_status') {
					$temparr[$key] = ucfirst($val[$key]);
				}
				else if($key=='is_flat') {
					$temparr[$key] = 'No';
					if($val[$key]=='1')
					{
						$temparr[$key] = 'Yes';
					}
				}
				else if($key=='status') {
					$temparr[$key] = 'In-Active';
					if($val[$key]=='1')
					{
						$temparr[$key] = 'Active';
					}
				}
				else if(isset($val[$key])){
					$temparr[$key]=$val[$key];
				}
			}
			$out[]=$temparr;
		}
		if ($this->request->is('ajax'))
		{

			header('Content-type: application/json');
			echo json_encode(array('condi'=>$arrCondition,"draw" => intval($this->request->data['draw']),
			"recordsTotal"    => intval( $this->request->params['paging']['InstallerSubscription']['count']),
			"recordsFiltered" => intval( $this->request->params['paging']['InstallerSubscription']['count']),
			"data"            => $out));
			die;
		}
		exit;

    }
    /**
	 *
	 * _generateInstallerSubscriptionSearchCondition
	 *
	 * @param : $id  : Id is use to identify for which project survey condition to be generated
	 *
	 * Behaviour : Private
	 *
	 * @defination : Method is use to generate search survey condition for perticular project
	 *
	 */
	private function _generateInstallerSubscriptionSearchCondition($id)
	{
		$arrCondition	= array();
		$blnSinCompany	= true;
		$this->request->data['InstallerSubscription']['id'] = $id;
		
		if(isset($this->request->data) && count($this->request->data)>0)
        {
            if(isset($this->request->data['InstallerSubscription']['id']) && trim($this->request->data['InstallerSubscription']['id'])!='') {
                $arrCondition['InstallerSubscription.installer_id'] = $this->request->data['InstallerSubscription']['id'];
            }
		}

		return $arrCondition;
	}
	/**
	 *
	 * get_installer_name
	 *
	 * Behaviour : public
	 *
	 * @defination : Method is use to fetch installer name from installer id
	 *
	 */
	public function get_installer_name()
	{
		$ins_id=decode($this->request->data['installer_id']);
		$arr_installer_data=$this->Installers->find('all',array('fields'=>array('Installers.installer_name'),
										  'conditions'=>array('id'=>$ins_id)))->first();
		$disp_add_sub = 0;
		if(!empty($arr_installer_data))
		{
			$arr_subscription = $this->InstallerSubscription->find('all',array('conditions'=>array('installer_id'=>$ins_id,'expire_date >='=>date('Y-m-d'))))->toArray();
			
			if(empty($arr_subscription))
			{
				$query = $this->InstallerSubscription->query();
				$query->update()
				    	->set(['status' => '0'])
				    	->where(['installer_id' => $ins_id])
				    	->execute();
				$disp_add_sub = 1;
			}
			echo $arr_installer_data['installer_name'].'|||'.$disp_add_sub;
		}
		exit;
	}
	public function add_subscription()
	{
		$this->autoRender 					= false;
    	$this->layout 						= 'popup';
		$this->InstallerSubscription->data 	= $this->request->data;
		$InstallerSubscriptionEntity		= $this->InstallerSubscription->newEntity($this->request->data,['validate' => 'addsub']);
		if(!$InstallerSubscriptionEntity->errors()) 
        {
        	$plan_id 									= $this->request->data['plan_id'];
        	$installer_id 								= decode($this->request->data['installer_id']);
        	$arr_plan 									= $this->InstallerPlans->find('all',array('conditions'=>array('id'=>$plan_id)))->toArray();
        	$InstallerSubscriptionEntity->installer_id 	= $installer_id;
        	$InstallerSubscriptionEntity->plan_name 	= $arr_plan[0]['plan_name'];
        	$InstallerSubscriptionEntity->plan_price 	= $arr_plan[0]['plan_price'];
        	$InstallerSubscriptionEntity->start_date 	= date('Y-m-d',strtotime($this->request->data['start_date']));
        	$InstallerSubscriptionEntity->expire_date 	= date('Y-m-d',strtotime($this->request->data['expire_date']));
        	$InstallerSubscriptionEntity->comment 		= 'added by admin';
        	$InstallerSubscriptionEntity->created 		= $this->NOW();
        	$InstallerSubscriptionEntity->modified 		= $this->NOW();
        	$InstallerSubscriptionEntity->created_by 	= $this->Session->read('User.id');
        	$InstallerSubscriptionEntity->modified_by 	= $this->Session->read('User.id');
        	if($arr_plan[0]['plan_price']=='0.00' || $arr_plan[0]['plan_price']=='0')
        	{
        		$InstallerSubscriptionEntity->free_flag = '1';
        	}
        	if(strtotime($this->request->data['expire_date'])>strtotime(date('Y-m-d')))
        	{
            	$InstallerSubscriptionEntity->status 	= '1';
        	}
			$this->InstallerSubscription->save($InstallerSubscriptionEntity);
			$arr_subscription = $this->InstallerSubscription->find('all',array('conditions'=>array('installer_id'=>$installer_id,'expire_date >='=>date('Y-m-d'))))->toArray();
			$disp_add_sub = 0;
			if(date('Y-m-d',strtotime($this->request->data['expire_date'])) < date('Y-m-d'))
			{
				$disp_add_sub = 1;
			}
			echo json_encode(array('result'=>'success','msg'=>'Subscription has been added sucessfully.','disp_add_sub'=>$disp_add_sub));
		}
		else
		{
			$arr_errors = $InstallerSubscriptionEntity->errors();
			$arr_result = array();
			if(isset($arr_errors['plan_id']) && isset($arr_errors['plan_id']['_empty']) && !empty($arr_errors['plan_id']['_empty']))
			{
				$arr_result['plan_id'] = $arr_errors['plan_id']['_empty'];
			}
			if(isset($arr_errors['start_date']) && isset($arr_errors['start_date']['_empty']) && !empty($arr_errors['start_date']['_empty']))
			{
				$arr_result['start_date'] = $arr_errors['start_date']['_empty'];
			}
			if(isset($arr_errors['expire_date']) && isset($arr_errors['expire_date']['_empty']) && !empty($arr_errors['expire_date']['_empty']))
			{
				$arr_result['expire_date'] = $arr_errors['expire_date']['_empty'];
			}
			echo json_encode(array('result'=>'error','msg'=>$arr_result));
		}
		//$this->set('InstallerSubscriptionErrors',$InstallerSubscriptionEntity->errors());
	}
}