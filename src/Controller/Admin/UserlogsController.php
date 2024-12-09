<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

class UserlogsController extends AppController {

    /**
	 * 
	 * The status of $intLimit is universe
	 *
	 * Potential value is limit use to restring data to be fetch from database or to be display on screen
	 *
	 * @var int
	 *
	 */
	var $intLimit = 25;
    var $helpers = array('Time','Html','Form','ExPaginator');
    public function initialize()
    {
        // Always enable the CSRF component.
        parent::initialize();
        $this->loadComponent('Paginator');

       	$this->loadModel('Userlogs');
       	$this->loadModel('Users');
        $this->loadModel('Userroleright');
        $this->loadModel('Adminaction');
        $this->loadModel('Admintrntype');
        $this->loadModel('Admintrnmodule');

        $this->set('Userright',$this->Userright);
    }
	/**
	 * 
	 * _generateAdminlogSearchCondition
	 *
	 * Behaviour : Private
	 *
	 * @defination : Method is use to generate search condition for igadmin log use to list data accrodingly 
	 *
	 */
    private function _generateAdminlogSearchCondition()
	{
		$arrCondition = array();
		$blnSinCompany = true;
		if(isset($this->request->data) && count($this->request->data)>0)
        {
            if(isset($this->request->data['Userlog']['adminuserid']) && $this->request->data['Userlog']['adminuserid']!='')
            {
                $arrCondition['Userlogs.adminuserid'] = $this->request->data['Userlog']['adminuserid'];
            }
			if(isset($this->request->data['User']['username']) && $this->request->data['User']['username']!='')
            {
                $arrCondition['Users.username LIKE'] = '%'.$this->request->data['User']['username'].'%';
            }
			if(isset($this->request->data['Userlog']['actionid']) && $this->request->data['Userlog']['actionid']!='')
            {
                $arrCondition['Userlogs.actionid'] = $this->request->data['Userlog']['actionid'];
            }
			if(isset($this->request->data['Userlog']['actionvalue']) && $this->request->data['Userlog']['actionvalue']!='')
            {
                $arrCondition['Userlogs.actionvalue LIKE'] = '%'.$this->request->data['Userlog']['actionvalue'].'%';
            }
			if(isset($this->request->data['Userlog']['remark']) && $this->request->data['Userlog']['remark']!='')
            {
                $arrCondition['Userlogs.remark LIKE'] = '%'.$this->request->data['Userlog']['remark'].'%';
            }
			if(isset($this->request->data['Userlog']['ipaddress']) && $this->request->data['Userlog']['ipaddress']!='')
            {
                $arrCondition['Userlogs.ipaddress LIKE'] = '%'.$this->request->data['Userlog']['ipaddress'].'%';
            }
			if(isset($this->request->data['Userlog']['search_date']) && $this->request->data['Userlog']['search_date']!='')
            {
                if($this->request->data['Userlog']['search_period'] == 1 || $this->request->data['Userlog']['search_period'] == 2)
                {
                	$arrSearchPara	= $this->Userlogs->setSearchDateParameter($this->request->data['Userlog']['search_period'],'Userlog');
                   	$this->request->data['Userlog'] = array_merge($this->request->data['Userlog'],$arrSearchPara['Userlog']);

                    //$this->request->data['Userlog']	= Set::merge($this->request->data['Userlog'],$arrSearchPara);
                    $this->dateDisabled			= true;
                }

                $arrperiodcondi = $this->Userlogs->findConditionByPeriod(	$this->request->data['Userlog']['search_date'],
																			$this->request->data['Userlog']['search_period'],
																			$this->request->data['Userlog']['DateFrom'],
																			$this->request->data['Userlog']['DateTo'],
																			$this->Session->read('User.timezone'));
                $arrCondition = array_merge($arrCondition,$arrperiodcondi);
		    }
		}
		return $arrCondition;
	}
	
	/**
	 * 
	 * admin_index
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use as default page for adminlogs, List entire logs base on search condition created 
	 *
	 */
	function index()
	{
		$this->initAdminRightHelper();
		$this->intCurAdminUserRight = $this->Userright->ANALYSTS_LOG_REPORT;
		$this->setAdminArea();
		
		$arrAdminlogList	= array();
		$arrAdminaction		= array();
		$arrUserType		= array();
		$arrCondition		= array();
		$this->SortBy		= "Userlogs.id";
		$this->Direction	= "DESC";
		$option=array();
		$option['colName']= array('adminuserid','actionid','remark','created','ipaddress');
		$option['order_by'] = "'order': [[ 3, 'desc' ]]";
		$this->SetSortingVars('Userlogs',$option);
		
		$arrCondition		= $this->_generateAdminlogSearchCondition();
		$this->paginate		= array('contain' => ['Users'],
									'conditions' => $arrCondition,
									'order'=>array($this->SortBy=>$this->Direction),
									'page'=>$this->CurrentPage,
									'limit' => $this->intLimit);
		$arrAdminlogList	= $this->paginate('Userlogs')->toArray();
		$arrAdminuser 		= $this->Users->find('list',['keyField' => 'id','valueField' => 'username'])->toArray();
		$arrAdminaction 	= $this->Adminaction->find('list',['keyField' => 'id','valueField' => 'description'])->order(['description'=>'ASC'])->toArray();
		
		$arrUserType[''] 	= 'Select';

		if(is_array($this->usertypes) && count($this->usertypes) > 0 )
		{
			foreach($this->usertypes as $key=>$value)
				$arrUserType[$key] = $value;
		}
		$out=array();
		foreach($arrAdminlogList as $key=>$val)	{
			$temparr=array();
			foreach($option['colName'] as $key)	{
				if(isset($val[$key])){
					if($key=='adminuserid' && array_key_exists($val[$key],$arrAdminuser)){	
						$temparr[$key] = $arrAdminuser[$val[$key]];
					}else if($key=='created'){
						$temparr[$key] = date('d-m-Y H:i:s',strtotime($val[$key]));
					}else if($key=='actionid' && array_key_exists($val[$key],$arrAdminaction)){
						$temparr[$key] = $arrAdminaction[$val[$key]];
					}else {	
						$temparr[$key] = $val[$key];
					}
				}
			}
			$out[]=$temparr;
		}
		$option['dt_selector']='grid_table_log';
		$option['formId']='formmain';
		$option['url']= URL_HTTP.'admin/userlogs';
		$JqdTablescr=$this->JqdTable->create($option);
		$this->set('JqdTablescr',$JqdTablescr);
		$this->set('arrAdminlogList',$arrAdminlogList);
		$this->set('arrUserType',$arrUserType);
		$this->set('period',$this->period);
		$this->set('arrAdminuser',$arrAdminuser);
		$this->set('arrAdminaction',$arrAdminaction);
		$this->set('period',$this->period);
		$this->set('limit',$this->intLimit);
		$this->set("CurrentPage",$this->CurrentPage);
		$this->set("SortBy",$this->SortBy);
		$this->set("Direction",$this->Direction);
		$this->set("page_count",(isset($this->request->params['paging']['Userlogs']['pageCount'])?$this->request->params['paging']['Userlogs']['pageCount']:0));
		if ($this->request->is('Ajax')) {
			header('Content-type: application/json');
			echo json_encode(array( 'conditionasdasd'=>$arrCondition,"draw"     => intval($this->request->data['draw']),
			"recordsTotal"    => intval( $this->request->params['paging']['Userlogs']['count']),
			"recordsFiltered" => intval( $this->request->params['paging']['Userlogs']['count']),
			"data"            => $out));
			die;
		}
	}
}
?>