<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

class ContactsController extends AppController {

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
       	$this->loadModel('Contactus');
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
            if(isset($this->request->data['Contactus']['adminuserid']) && $this->request->data['Contactus']['adminuserid']!='')
            {
                $arrCondition['Contactus.adminuserid'] = $this->request->data['Contactus']['adminuserid'];
            }
			if(isset($this->request->data['Contactus']['username']) && $this->request->data['Contactus']['username']!='')
            {
                $arrCondition['Contactus.username LIKE'] = '%'.$this->request->data['Contactus']['username'].'%';
            }
			if(isset($this->request->data['Contactus']['remark']) && $this->request->data['Contactus']['remark']!='')
            {
                $arrCondition['Contactus.remark LIKE'] = '%'.$this->request->data['Contactus']['remark'].'%';
            }
			if(isset($this->request->data['Contactus']['ipaddress']) && $this->request->data['Contactus']['ipaddress']!='')
            {
                $arrCondition['Userlogs.ipaddress LIKE'] = '%'.$this->request->data['Contactus']['ipaddress'].'%';
            }
			if(isset($this->request->data['Contactus']['search_date']) && $this->request->data['Contactus']['search_date']!='')
            {
                if($this->request->data['Contactus']['search_period'] == 1 || $this->request->data['Contactus']['search_period'] == 2)
                {
                	$arrSearchPara	= $this->Userlogs->setSearchDateParameter($this->request->data['Contactus']['search_period'],'Contactus');
                   	$this->request->data['Contactus'] = array_merge($this->request->data['Contactus'],$arrSearchPara['Contactus']);

                    //$this->request->data['Contactus']	= Set::merge($this->request->data['Contactus'],$arrSearchPara);
                    $this->dateDisabled			= true;
                }

                $arrperiodcondi = $this->Userlogs->findConditionByPeriod(	$this->request->data['Contactus']['search_date'],
																			$this->request->data['Contactus']['search_period'],
																			$this->request->data['Contactus']['DateFrom'],
																			$this->request->data['Contactus']['DateTo'],
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
		$this->intCurAdminUserRight = $this->Userright->LIST_CONTACTUS;
		$this->setAdminArea();
		
		$arrUserType		= array();
		$arrCondition		= array();
		$this->SortBy		= "Contactus.id";
		$this->Direction	= "DESC";
		$option=array();
		$option['colName']= array('id','name','email','subject','mobile','created','ipaddress');
		$option['order_by'] = "'order': [[ 3, 'desc' ]]";
		$this->SetSortingVars('Contactus',$option);
		
		$arrCondition		= $this->_generateAdminlogSearchCondition();
		$this->paginate		= array('conditions' => $arrCondition,
									'order'=>array($this->SortBy => $this->Direction),
									'page'=>$this->CurrentPage,
									'limit' => $this->intLimit);
		$arrAdminlogList	= $this->paginate('Contactus')->toArray();
		
		$out=array();
		foreach($arrAdminlogList as $kaey=>$val)	{
			$temparr=array();
			foreach($option['colName'] as $key)	{
				if(isset($val->$key)){
					if($key=='created'){
						$temparr[$key] = date('d-m-Y H:i:s',strtotime($val->$key));
					}else {	
						$temparr[$key] = $val->$key;
					}
				}
			}
			$out[]=$temparr;
		}
		$option['dt_selector']='grid_table_log';
		$option['formId']='formmain';
		$option['url']= URL_HTTP.'admin/contacts';
		$JqdTablescr=$this->JqdTable->create($option);
		$this->set('JqdTablescr',$JqdTablescr);
		$this->set('arrAdminlogList',$arrAdminlogList);
		$this->set('period',$this->period);
		$this->set('limit',$this->intLimit);
		$this->set("CurrentPage",$this->CurrentPage);
		$this->set("SortBy",$this->SortBy);
		$this->set("Direction",$this->Direction);
		$this->set("page_count",(isset($this->request->params['paging']['Contactus']['pageCount'])?$this->request->params['paging']['Contactus']['pageCount']:0));
		
		if ($this->request->is('Ajax')) {
			header('Content-type: application/json');
			echo json_encode(array( 'condition'=>$arrCondition,"draw" => intval(isset($this->request->data['draw'])?$this->request->data['draw']:0),
			"recordsTotal"    => intval( $this->request->params['paging']['Contactus']['count']),
			"recordsFiltered" => intval( $this->request->params['paging']['Contactus']['count']),
			"data"            => $out));
			die;
		}
	}
}
?>