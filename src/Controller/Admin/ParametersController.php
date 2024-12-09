<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

use Cake\ORM\TableRegistry;
use Cake\ORM\Table;


class ParametersController extends AppController {

	/**
	 *
	 * The status of $name is universe
	 *
	 * Potential value is name of class
	 *
	 * @public string
	 *
	 */
    public $name = 'Parameters';

    /**
	 *
	 * The status of $parameter_status is universe
	 *
	 * Potential value is name of class
	 *
	 * @public string
	 *
	 */
    public $parameter_status = array("A"=>"Active","I"=>"In-Active");

	/**
     *
     * The status of $helpers is universe
     *
     * Potential value is array of helpers to be inherited
     *
     * @public array
     *
     */
	//public $helpers=array('Js','Time','Userright','ExPaginator','TimeZone');

	var $helpers = array('Time','Html','Form','ExPaginator');
    public function initialize()
    {
        // Always enable the CSRF component.
        parent::initialize();
        $this->loadComponent('Paginator');

       	$this->loadModel('Parameters');
        $this->loadModel('Userroleright');
        $this->loadModel('Adminaction');
        $this->loadModel('Admintrntype');
        $this->loadModel('Admintrnmodule');

        $this->set('Userright',$this->Userright);
    }
	/**
	 * Displays a index
	 *
	 * @param mixed What page to display
	 * @return void
	 */
	public function index()
	{
		$this->intCurAdminUserRight = $this->Userright->LIST_PARAMETERS;
		$this->setAdminArea();
		
		$arrParameterList	= array();
		$arrCondition		= array();
		$this->SortBy		= "Parameters.para_id";
		$this->Direction 	= "DESC";
		$this->intLimit		= 10;
		$option 			= array();
		$option['colName']	= array('id','para_parent_id','para_value','status','created','action');
		
		$this->SetSortingVars('Parameters',$option);
		$arrCondition		= $this->_generateParameterearchCondition();
		$this->paginate		= array(
								'contain' => ['ParentParameters'],
								'conditions' => $arrCondition,
								'order'=>array($this->SortBy=>$this->Direction),
								'page'=>$this->CurrentPage,
								'limit' => $this->intLimit);
		$arrParameterList	= $this->paginate('Parameters')->toArray();

		$option['dt_selector']='table-example';
		$option['formId']='index-formmain';
		$option['url']= URL_HTTP.'admin/Parameters';
		
		$arrParentParaList		= $this->Parameters->find("list", ['keyField' => 'para_id','valueField' => 'para_value',"conditions"=>array("Parameters.para_parent_id"=>0)])->toArray();
		$JqdTablescr=$this->JqdTable->create($option);

		$this->set('arrParentParaList', $arrParentParaList);
		$this->set('JqdTablescr',$JqdTablescr);
		$this->set('period',$this->period);
		$this->set('limit',$this->intLimit);
		$this->set("CurrentPage",$this->CurrentPage);
		$this->set("SortBy",$this->SortBy);
		$this->set("Direction",$this->Direction);
		$this->set('status',$this->ticket_status);
		$this->set("page_count",(isset($this->request->params['paging']['Parameters']['pageCount'])?$this->request->params['paging']['Parameters']['pageCount']:0));

		$out = array();
		
		foreach($arrParameterList as $key=>$val)
		{
			$temparr	= array();
			$Actions	= array();
			$parent_id  = 0;
			foreach($option['colName'] as $key)
			{
				if($key == 'status') {
					$temparr[$key]=($val['status']== "A")?"Active":"In-Active";
				} else if($key == 'created') {
					$temparr[$key] = date('d-m-Y H:i:s',strtotime($val['created']));
				} else if($key == 'id') {
					$temparr[$key]=$val['para_id'];
				} else if($key == 'para_parent_id') {
					$temparr[$key]=($val['para_parent_id']==0)?" ":$val['parent_parameter']->para_value;
					$parent_id=$val['para_parent_id'];
				} else if($key == 'action') {
					if(!isset($val['para_parent_id']) || $val['para_parent_id'] == 0){
						$Actions[]= $this->Userright->linkEditParameterType(constant('WEB_ADMIN_PREFIX').'parameters/manageparatype/'.encode($val['para_id']),'<i class="fa fa-edit"> </i>','','rel="addparatype" title="Edit Parameter Type"');
					}else{
						$Actions[]=$this->Userright->linkEditParameter(constant('WEB_ADMIN_PREFIX').'parameters/manageparameter/'.encode($val['para_id']),'<i class="fa fa-edit"> </i>','','rel="addparameter" title="Edit Parameter"');
					}
				}else if(isset($val[$key])){
					$temparr[$key] = $val[$key];
				}
				$temparr['action'] = implode("&nbsp;",$Actions);
			}
			$out[]=$temparr;
		}
		if($this->request->is('ajax')) {
			header('Content-type: application/json');
			echo json_encode(array( 'condition'=>$this->request->data['Parameter'],"draw"     	=> intval($this->request->data['draw']),
									"recordsTotal"    => intval( $this->request->params['paging']['Parameters']['count'] ),
									"recordsFiltered" => intval( $this->request->params['paging']['Parameters']['count'] ),
									"data"            => $out));
			die;
		}
	}

	/**
	 *
	 * _generateParameterearchCondition
	 *
	 * Behaviour : Private
	 *
	 * @param : $id  : Id is use to identify for which user condition to be generated if its not null
	 * @defination : Method is use to generate search condition using which admin user role data can be listed
	 *
	 */
	private function _generateParameterearchCondition($id=null)
	{
		$arrCondition	= array();
		$blnSinCompany	= true;
		if(!empty($id)) $this->request->data['Parameter']['para_id'] = $id;
		if(isset($this->request->data) && count($this->request->data)>0)
        {
            if(isset($this->request->data['Parameter']['para_id']) && trim($this->request->data['Parameter']['para_id'])!='')
            {
                $strID = trim($this->request->data['Parameter']['para_id'],',');
                $arrCondition['Parameters.para_id in '] = array_unique(explode(',',$strID));
            }
			if(isset($this->request->data['Parameter']['para_parent_id']) && trim($this->request->data['Parameter']['para_parent_id'])!='') {
                if ($this->request->data['Parameter']['para_parent_id'] != "OnlyParents") {
					$arrCondition['Parameters.para_parent_id'] = $this->request->data['Parameter']['para_parent_id'];
				} else {
					$arrCondition['Parameters.para_parent_id'] = 0;
				}
			}
			if(isset($this->request->data['Parameter']['para_value']) && $this->request->data['Parameter']['para_value']!='')
            {
                $arrCondition['Parameters.para_value LIKE'] = '%'.$this->request->data['Parameter']['para_value'].'%';
            }
			if(isset($this->request->data['Parameter']['status']) && $this->request->data['Parameter']['status']!='')
            {
                $arrCondition['Parameters.status'] = $this->request->data['Parameter']['status'];
            }
			if(isset($this->request->data['Parameter']['search_date']) && $this->request->data['Parameter']['search_date']!='')
            {
				$arrDate = array('Parameters.created','Parameters.updated');
				if(in_array($this->request->data['Parameter']['search_date'], $arrDate))
				{
					if($this->request->data['Parameter']['search_period'] == 1 || $this->request->data['Parameter']['search_period'] == 2)
					{
						$arrSearchPara	= $this->Parameters->setSearchDateParameter($this->request->data['Parameter']['search_period'],'Parameter');
						$this->request->data['Parameter'] = array_merge($this->request->data['Parameter'],$arrSearchPara['Parameter']);
						
						$this->dateDisabled	= true;
					}
					$arrperiodcondi = $this->Parameters->findConditionByPeriod(	$this->request->data['Parameter']['search_date'],
																				$this->request->data['Parameter']['search_period'],
																				$this->request->data['Parameter']['DateFrom'],
																				$this->request->data['Parameter']['DateTo'],
																				$this->Session->read('User.timezone'));
					$arrCondition = array_merge($arrCondition,$arrperiodcondi);
				}
            }
		}
		return $arrCondition;
	}

	/**
	 * manageparatype
	 * Behaviour : Public
	 * @defination : Method is use to add new parameter type
	 */
	public function manageparatype($id = null)
	{
		//$this->layout = "popup";

		if(empty($id)) {
			$this->intCurAdminUserRight = $this->Userright->ADD_PARAMETER_TYPE;
			$parameterEntity 			= $this->Parameters->newEntity($this->request->data,['validate'=>'addmanageparatype']);
		} else {
			$id=intval(decode($id));
			$this->intCurAdminUserRight = $this->Userright->EDIT_PARAMETER_TYPE;
			$parameterData 				= $this->Parameters->get($id);
			$parameterEntity 			= $this->Parameters->patchEntity($parameterData,$this->request->data,['validate'=>'editmanageparatype']);
		}
		$this->setAdminArea();
		$arrAdminDefaultRights	= array();
		$arrAdminTransaction	= array();
		$arrError = array();

		//$this->Parameter->setValiationRules("parameter_type");

		if (!empty($this->request->data))
		{ 
			if (empty($id)) {
				$parameterEntity->para_id 		= $this->Parameters->getParaID();
				$parameterEntity->created 		= $this->NOW();
				$parameterEntity->created_by 	= $this->Session->read('User.id');
			} else {
				$parameterEntity->updated 		= $this->NOW();
				$parameterEntity->updated_by 	= $this->Session->read('User.id');
			}
			//if($this->Parameter->validates()) {
				if ($this->Parameters->save($parameterEntity))
				{
					if(empty($id)){
						$this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->ADD_PARAMETER_TYPE,$parameterEntity->para_id,'Added Parameter id::'.$parameterEntity->para_id);
						$this->Flash->set('Parameter Type added successfully.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
                       	return $this->redirect('admin/parameters');
					} else {
						$this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->EDIT_PARAMETER_TYPE,$parameterEntity->para_id,'Updated Parameter id::'.$parameterEntity->para_id);
                        $this->Flash->set('Parameter Type updated successfully.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
                       	return $this->redirect('admin/parameters');
					}
				}
			//}
		} else if(!empty($id)){
			$parameterData 		= $this->Parameters->get($id);
			$parameterEntity 	= $this->Parameters->patchEntity($parameterData,$this->request->data());
			
			if(!isset($parameterEntity->para_id) || empty($parameterEntity->para_id)) {
                $this->Flash->set('Invalid Parameter Type.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'error']]);
			}
		}

		$this->set('Parameter',$parameterEntity);
		$this->set('para_status',$this->parameter_status);
		$this->set('data',	$this->request->data);
		$this->set('arrError', $arrError);
		$this->set('para_id', $id);
	}

	/**
	 * manageparameter
	 * Behaviour : Public
	 * @defination : Method is use to add new parameter type
	 */
	public function manageparameter($id = null)
	{
		//$this->layout = "popup";
		if(empty($id)) {
			$this->intCurAdminUserRight = $this->Userright->ADD_PARAMETER;
			$parameterEntity 			= $this->Parameters->newEntity($this->request->data(),['validate'=>'addmanageparameter']);
		} else {
			$id=intval(decode($id));
			$this->intCurAdminUserRight = $this->Userright->EDIT_PARAMETER;
			$parameterData 				= $this->Parameters->get($id);
			$parameterEntity 			= $this->Parameters->patchEntity($parameterData,$this->request->data(),['validate'=>'editmanageparameter']);
				
			//$parameterEntity 			= $this->Parameter->patchEntity($this->request->data(),['validate'=>'editmanageparameter']);
		}

		$this->setAdminArea();
		$arrAdminDefaultRights	= array();
		$arrAdminTransaction	= array();
		$arrError 				= array();
		
		if (!empty($this->request->data))
		{
			if (empty($id)) {
				$parameterEntity->para_id 		= $this->Parameters->getParaID($this->request->data['Parameters']['para_parent_id']);
				$parameterEntity->created 		= $this->NOW();
				$parameterEntity->created_by 	= $this->Session->read('User.id');
			} else {
				$parameterEntity->updated 		= $this->NOW();
				$parameterEntity->updated_by 	= $this->Session->read('User.id');
			}
			if(!$parameterEntity->errors()) {
				
				if ($this->Parameters->save($parameterEntity))
				{
					if(empty($id)){
						$this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->ADD_PARAMETER_TYPE,$parameterEntity->para_id,'Added Parameter id::'.$parameterEntity->para_id);
						$this->Flash->set('Parameter added successfully.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
                       	return $this->redirect('admin/parameters');
					} else {
						$this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->EDIT_PARAMETER,$parameterEntity->para_id,'Updated Parameter id::'.$parameterEntity->para_id);
                        $this->Flash->set('Parameter updated successfully.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
                       	return $this->redirect('admin/parameters');
					}
				}
			}
		} else if(!empty($id)) {			
			$parameterData 		= $this->Parameters->get($id);
			$parameterEntity 	= $this->Parameters->patchEntity($parameterData,$this->request->data());
			if(!isset($parameterEntity->para_id) || empty($parameterEntity->para_id)) {
                $this->Flash->set('Invalid Parameter Type.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'error']]);
			}
		}
		
		$arrParentParaList		= $this->Parameters->find("list", ['keyField' => 'para_id','valueField' => 'para_value',"conditions"=>array("Parameters.para_parent_id"=>0)])->toArray();
			
		$this->set('Parameter',$parameterEntity);											
		$this->set('arrParentParaList', $arrParentParaList);
		$this->set('para_status',$this->parameter_status);
		$this->set('data',	$this->request->data);
		$this->set('arrError', $arrError);
		$this->set('para_id', $id);
	}
}
