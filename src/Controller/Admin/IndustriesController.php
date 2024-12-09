<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

use Cake\ORM\TableRegistry;
use Cake\ORM\Table;

class IndustriesController extends AppController {

	/**
	 *
	 * The status of $name is universe
	 *
	 * Potential value is name of class
	 *
	 * @public string
	 *
	 */
    public $name = 'Industries';

    /**
	 *
	 * The status of $industrie_status is universe
	 *
	 * Potential value is name of class
	 *
	 * @public string
	 *
	 */
    public $industrie_status = array("1"=>"Active","0"=>"In-Active");

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

       	$this->loadModel('Industries');
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
		
		$arrIndustrieList		= array();
		$arrCondition			= array();
		$this->SortBy			= "Industries.para_id";
		$this->Direction 		= "DESC";
		$this->intLimit			= 10;
		$option 				= array();
		$option['colName']		= array('id','parent_id','industry_name','status','created','action');
		
		$this->SetSortingVars('Industries',$option);
		$arrCondition			= $this->_generateParameterearchCondition();
		$this->paginate			= array(
									'contain' => ['ParentIndustries'],
									'conditions' => $arrCondition,
									'order'=>array($this->SortBy=>$this->Direction),
									'page'=>$this->CurrentPage,
									'limit' => $this->intLimit);

		$arrIndustrieList		= $this->paginate('Industries')->toArray();
		$option['dt_selector']	='table-example';
		$option['formId']		='formmain';
		$option['url']			= URL_HTTP.'admin/Industries';
		
		$arrParentParaList		= $this->Industries->find("list", ['keyField' => 'id','valueField' => 'industry_name'],array("conditions"=>array("parent_id"=>0)))->toArray();
		
		
		$JqdTablescr			=$this->JqdTable->create($option);
		$this->set('arrParentParaList', $arrParentParaList);
		$this->set('JqdTablescr',$JqdTablescr);
		$this->set('period',$this->period);
		$this->set('limit',$this->intLimit);
		$this->set("CurrentPage",$this->CurrentPage);
		$this->set("SortBy",$this->SortBy);
		$this->set("Direction",$this->Direction);
		$this->set('status',$this->ticket_status);
		$this->set("page_count",(isset($this->request->params['paging']['Industries']['pageCount'])?$this->request->params['paging']['Industries']['pageCount']:0));

		$out = array();
		foreach($arrIndustrieList as $key=>$val)
		{
			$temparr	= array();
			$Actions	= array();
			$parent_id  = 0;
			foreach($option['colName'] as $key)
			{
				if($key == 'status') {
					$temparr[$key]=($val['status']== "A")?"#Active":"#In-Active";
				} else if($key == 'created') {
					$temparr[$key] = date('Y-m-d H:i:s',strtotime($val['created']));
				} else if($key == 'parent_id') {
					$temparr[$key]=($val['parent_id']==0)?"#":$val['parent_industry']->industry_name;
					$parent_id=$val['parent_id'];
				} else if($key == 'action') {
					if(!isset($val['parent_id']) || $val['parent_id'] == 0){
						$Actions[]= $this->Userright->linkEditIndustrieType(constant('WEB_ADMIN_PREFIX').'industries/manageindustype/'.encode($val['id']),'<i class="fa fa-edit"> </i>','','rel="addparatype" title="Edit Parameter Type"');
					}else{
						$Actions[]=$this->Userright->linkEditIndustrie(constant('WEB_ADMIN_PREFIX').'industries/manageindustrie/'.encode($val['id']),'<i class="fa fa-edit"> </i>','','rel="addindustrie" title="Edit Parameter"');
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
			echo json_encode(array( "draw"            => intval($this->request->data['draw']),
									"recordsTotal"    => intval( $this->request->params['paging']['Industries']['count'] ),
									"recordsFiltered" => intval( $this->request->params['paging']['Industries']['count'] ),
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
		//pr($this->request->data['Industrie']);
		if(!empty($id)) $this->request->data['Industrie']['id'] = $id;
		if(isset($this->request->data) && count($this->request->data)>0)
        {
            if(isset($this->request->data['Industrie']['id']) && trim($this->request->data['Industrie']['id'])!='')
            {
                $arrCondition['Industries.id IN '] = explode(',', $this->request->data['Industrie']['id']);
            }
			if(isset($this->request->data['Industrie']['parent_id']) && trim($this->request->data['Industrie']['parent_id'])!='') {
                if ($this->request->data['Industrie']['parent_id'] != "OnlyParents") {
					//$strID = trim($this->request->data['Industrie']['parent_id'],',');
					//$arrCondition['Industries.parent_id'] = array_unique(explode(',',$strID));
					$arrCondition['Industries.parent_id'] = $this->request->data['Industrie']['parent_id'];
				} else {
					$arrCondition['Industries.parent_id'] = 0;
				}
			}
			
			if(isset($this->request->data['Industrie']['industry_name']) && $this->request->data['Industrie']['industry_name']!='')
            {
                $arrCondition['Industries.industry_name LIKE'] = '%'.$this->request->data['Industrie']['industry_name'].'%';
            }
			if(isset($this->request->data['Industrie']['status']) && $this->request->data['Industrie']['status']!='')
            {
                $arrCondition['Industries.status'] = $this->request->data['Industrie']['status'];
            }
			if(isset($this->request->data['Industrie']['search_date']) && $this->request->data['Industrie']['search_date']!='')
            {
				$arrDate=array('Industries.created','Industries.updated');
				if(in_array($this->request->data['Industrie']['search_date'], $arrDate))
				{
					if($this->request->data['Industrie']['search_period'] == 1 || $this->request->data['Industrie']['search_period'] == 2)
					{
						$arrSearchPara	= $this->Industries->setSearchDateParameter($this->request->data['Industrie']['search_period'],'Industrie');
						$this->request->data['Industrie'] = array_merge($this->request->data['Industrie'],$arrSearchPara['Industrie']);
						$this->dateDisabled	= true;
					}
					$arrperiodcondi = $this->Industries->findConditionByPeriod(	$this->request->data['Industrie']['search_date'],
																				$this->request->data['Industrie']['search_period'],
																				$this->request->data['Industrie']['DateFrom'],
																				$this->request->data['Industrie']['DateTo'],
																				$this->Session->read('Adminuser.timezone'));
					$arrCondition = array_merge($arrCondition,$arrperiodcondi);
				}
            }
		}
		return $arrCondition;
	}

	/**
	 * manageindustype
	 * Behaviour : Public
	 * @defination : Method is use to add new industrie type
	 */
	public function manageindustype($id = null)
	{
		//$this->layout = "popup";

		if(empty($id)) {
			$this->intCurAdminUserRight = $this->Userright->ADD_PARAMETER_TYPE;
			$industrieEntity 			= $this->Industries->newEntity($this->request->data,['validate'=>'addmanageindustype']);
		} else {
			$id=intval(decode($id));
			$this->intCurAdminUserRight = $this->Userright->EDIT_PARAMETER_TYPE;
			
			$industrieData 				= $this->Industries->get($id);
			$industrieEntity 			= $this->Industries->patchEntity($industrieData,$this->request->data,['validate'=>'editmanageindustype']);
		}
		$this->setAdminArea();
		$arrAdminDefaultRights	= array();
		$arrAdminTransaction	= array();
		$arrError = array();

		//$this->Parameter->setValiationRules("industrie_type");

		if (!empty($this->request->data))
		{ 
			if (empty($id)) {
				$industrieEntity->parent_id 	= 0;
				$industrieEntity->display_order = $this->Industries->retrivemaxDisplayOrder(0);;
				$industrieEntity->created 		= $this->NOW();
				//$industrieEntity->created_by 	= $this->Session->read('User.id');
			} else {
				$industrieEntity->parent_id 	= 0;
				//$industrieEntity->updated 		= $this->NOW();
				//$industrieEntity->updated_by 	= $this->Session->read('User.id');
			}
			pr($industrieEntity);exit;
			//if($this->Parameter->validates()) {
				if ($this->Industries->save($industrieEntity))
				{
					if(empty($id)){
						$this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->ADD_PARAMETER_TYPE,$industrieEntity->para_id,'Added Parameter id::'.$industrieEntity->para_id);
						$this->Flash->set('Parameter Type added successfully.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
                       	return $this->redirect('admin/industries');
					} else {
						$this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->EDIT_PARAMETER_TYPE,$industrieEntity->para_id,'Updated Parameter id::'.$industrieEntity->para_id);
                        $this->Flash->set('Parameter Type updated successfully.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
                       	return $this->redirect('admin/industries');
					}
				}
			//}
		} else if(!empty($id)){
			$industrieData 		= $this->Industries->get($id);
			$industrieEntity 	= $this->Industries->patchEntity($industrieData,$this->request->data());
			
			if(!isset($industrieEntity->id) || empty($industrieEntity->id)) {
                $this->Flash->set('Invalid Parameter Type.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'error']]);
			}
		}

		$this->set('Industries',$industrieEntity);
		$this->set('Parameter',$industrieEntity);
		$this->set('para_status',$this->industrie_status);
		$this->set('data',	$this->request->data);
		$this->set('arrError', $arrError);
		$this->set('para_id', $id);
	}

	/**
	 * manageindustrie
	 * Behaviour : Public
	 * @defination : Method is use to add new industrie type
	 */
	public function manageindustrie($id = null)
	{
		//$this->layout = "popup";

		if(empty($id)) {
			$this->intCurAdminUserRight = $this->Userright->ADD_PARAMETER;
			$industrieEntity 			= $this->Industries->newEntity($this->request->data(),['validate'=>'addmanageindustrie']);
		} else {
			$id=intval(decode($id));
			$this->intCurAdminUserRight = $this->Userright->EDIT_PARAMETER;
			$industrieData 				= $this->Industries->get($id);
			$industrieEntity 			= $this->Industries->patchEntity($industrieData,$this->request->data(),['validate'=>'editmanageindustrie']);
				
			//$industrieEntity 			= $this->Parameter->patchEntity($this->request->data(),['validate'=>'editmanageindustrie']);
		}

		$this->setAdminArea();
		$arrAdminDefaultRights	= array();
		$arrAdminTransaction	= array();
		$arrError 				= array();
		//pr($this->request->data);
		if (!empty($this->request->data))
		{
			if (empty($id)) {
				$industrieEntity->display_order	= $this->Industries->retrivemaxDisplayOrder($this->request->data['Industries']['parent_id']);
				$industrieEntity->created 		= $this->NOW();
				$industrieEntity->created_by 	= $this->Session->read('User.id');
			} else {
				$industrieEntity->updated 		= $this->NOW();
				$industrieEntity->updated_by 	= $this->Session->read('User.id');
			}
			//if(!$industrieEntity->errors()) {
				if ($this->Industries->save($industrieEntity))
				{
					if(empty($id)){
						$this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->ADD_PARAMETER_TYPE,$industrieEntity->para_id,'Added Parameter id::'.$industrieEntity->para_id);
						$this->Flash->set('Industrie added successfully.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
                       	return $this->redirect('admin/industries');
					} else {
						$this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->EDIT_PARAMETER,$industrieEntity->para_id,'Updated Parameter id::'.$industrieEntity->para_id);
                        $this->Flash->set('Industrie updated successfully.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
                       	return $this->redirect('admin/industries');
					}
				}
			//}
		} else if(!empty($id)){
			
			//$industrieData 		= $this->Industries->get($id);
			//$industrieEntity 	= $this->Industries->patchEntity($industrieData,$this->request->data());

			if(!isset($industrieEntity->id) || empty($industrieEntity->id)) {
                $this->Flash->set('Invalid Industrie Type.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'error']]);
			}
		}
		
		$arrParentIndrList		= $this->Industries->find("list", ['keyField' => 'id','valueField' => 'industry_name',"conditions"=>array("Industries.parent_id"=>0)])->toArray();
			
		$this->set('Industries',$industrieEntity);
		$this->set('arrParentIndrList', $arrParentIndrList);
		$this->set('para_status',$this->industrie_status);
		$this->set('data',	$this->request->data);
		$this->set('arrError', $arrError);
		$this->set('para_id', $id);
	}
}
