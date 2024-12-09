<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

use App\Model\Table\Entity;
use App\Model\Entity\Parameter;
use Cake\Validation\Validator;
/**
 * Short description for file
 * This Model use for Invoice table. It extends Table Class
 * @category  Class File
 * @Desc      Manage Parameter information
 * @author    Pravin Sanghani
 * @version   RR
 * @since     File available since RR 1.0
 */
class ParametersTable extends AppTable {
	/**
	 * The status of $name is universe
	 * Potential value are Class Name
	 * @var String
	 */
	//var $Name 				= 'Parameter';
	//public $primaryKey		= 'para_id';

	var $STATUS_ACTIVE   = 'A';
 	var $STATUS_INACTIVE  = 'I';

	/**
	 * The status of $name is universe
	 * Potential value are Class Name
	 * @var String
	 */
	var $para_parent_id 	= 0;
	
	/* Project type for the project */
	var $projectType = 3;

	/* Project type for the project */
	var $paraTypeArea = 2;
	/* Project type for the project */
	var $paraCustomerRights = 5;

	/* Project Source*/
    var $paraTypeProjectSource = 7;
	/** 
     * Stores Model default validation ruleset 
     * @var unknown_type 
     */ 
    var $__defaultRules = array(); 

	public $validate		= array();
	public $validationSet	= "";
	//public $belongsTo		= array('ParentPara' => array('className' => 'Parameter','foreignKey' => 'para_parent_id')); 

    public $sourceExternalLead = 7001;
    public $sourceSolarCalculator = 7002;
    public $sourceSiteSurvey = 7003;


    /* User Role Static codes*/

    public $technical_role = 5001;
    public $commercial_role = 5002;
    public $bd_role = 5003;
    public $execution_role = 5004;
    public $admin_role = 5005;

    /**
     * The status of $useTable is universe
     * Potential value are Database Table name
     * @var String
     */
    var $useTable = 'parameters';
    public function initialize(array $config)
    {
    	$this->table($this->useTable);
    	// $this->alias('parameterss');
        //$this->addBehavior('Timestamp');
       
        $this->primaryKey('para_id');
       	$this->belongsTo('ParentParameters', [
        	'className' => 'Parameters',
            'foreignKey' => 'para_parent_id',
            'joinType' => 'LEFT'
        ]);
        /*pr($this);exit;*/

    }

	public function isParaTypeExists($para_id,$para_parent_id,$para_value)
	{
		if($para_id==0){//add time
			$arrConditions	= array("Parameters.para_parent_id"=>$para_parent_id,"Parameters.para_value"=>trim($para_value));
			$paratypeexists	= $this->find('all',array('conditions' => $arrConditions))->count();
			/*pr($arrConditions);
			pr($paratypeexists);exit;*/
			return ($paratypeexists > 0?false:true);
		} else {//edit time
			$arrConditions	= array("Parameters.para_id != "=>$para_id,"Parameters.para_parent_id"=>$para_parent_id,"Parameters.para_value"=>trim($para_value));
			$paratypeexists	= $this->find('all',array('conditions' => $arrConditions))->count();

			return ($paratypeexists > 0?false:true);
		}
	}
	/**
     * Add Manageparameter validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationAddmanageparameter(Validator $validator)
    {
    	$validator->notEmpty('para_parent_id', 'Parameter ID can not be blank.');
		$validator->notEmpty('para_value', 'Last Name can not be blank.');
		$validator->notEmpty('para_desc', 'Description can not be blank.');

		$validator->add('para_value', 'para_value', ['rule' =>function ($value, $context) { 
			return $this->isParaTypeExists(0,$context['data']['para_parent_id'],$context['data']['para_value']); 
		},'message' => 'Parameter already exists!']);

    	return $validator;
    }
    /**
     * Edit Manageparameter validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationEditmanageparameter(Validator $validator)
    {
    	$validator->notEmpty('para_parent_id', 'Parameter ID can not be blank.');
		$validator->notEmpty('para_value', 'Last Name can not be blank.');
		$validator->notEmpty('para_desc', 'Description can not be blank.');
		$validator->add('para_value', 'para_value', ['rule' =>function ($value, $context) { 
			return $this->isParaTypeExists($context['data']['para_id'],$context['data']['para_parent_id'],$context['data']['para_value']); 
		},'message' => 'Parameter already exists!']);
		return $validator;
    }

    /**
     * Add Manageparameter Type validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationAddmanageparatype(Validator $validator)
    {
    	$validator->notEmpty('para_value', 'Last Name can not be blank.');
		$validator->notEmpty('para_desc', 'Description can not be blank.');

		$validator->add('para_value', 'para_value', ['rule' =>function ($value, $context) { 
			//pr($context['data']);exit;
			return $this->isParaTypeExists(0,0,$context['data']['para_value']); 
		},'message' => 'Parameter already exists!']);

    	return $validator;
    }
    /**
     * Edit Manageparameter Type validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationEditmanageparatype(Validator $validator)
    {
    	$validator->notEmpty('para_value', 'Last Name can not be blank.');
		$validator->notEmpty('para_desc', 'Description can not be blank.');
		$validator->add('para_value', 'para_value', ['rule' =>function ($value, $context) { 
			return $this->isParaTypeExists($context['data']['para_id'],0,$context['data']['para_value']); 
		},'message' => 'Parameter already exists!']);
		return $validator;
    }

	public function getParaID($para_parent_id = 0)
	{
		$maxid = 0;
		if($para_parent_id == 0) {
			$LastPara	= $this->find("all",array("conditions"=>array("Parameters.para_parent_id"=>0),
													"fields"=>array("Parameters.para_id"),
													"order"=>array("Parameters.para_id"=>"DESC"),
													'limit'=>1))->toArray();
			if (!empty($LastPara)) {
				$maxid = $LastPara[0]->para_id;
			}

			$maxid = ($maxid <= 0)?1:$maxid+1;
		} else {
			$LastPara	= $this->find("all",array("conditions"=>array("Parameters.para_parent_id"=>$para_parent_id),
													"fields"=>array("Parameters.para_id"),
													"order"=>array("Parameters.para_id"=>"DESC"),
													'limit'=>1))->toArray();
			if(count($LastPara)>0) {
				$maxid = $LastPara[0]->para_id + 1;
			} else {
				$maxid = ($para_parent_id*1000) + 1;
			}
		}
		
		return $maxid;
	}

    /**
	 *
	 * GetParameterList
	 *
	 * Behaviour : Public
	 *
	 * @defination : Use for get Parameter list if having master right to logged in user
	 *
	 */
	public function GetParameterList($id){
		return $this->find('list',array('keyField' => 'para_id',
    'valueField' => 'para_value','conditions'=>array('Parameters.para_parent_id'=>$id,'para_id != '=>3004,'status'=>'A'),'order'=>array('para_id')));
		/*if(GOVERMENT_AGENCY=='0')
		{
			return $this->find('list',array('keyField' => 'para_id',
    'valueField' => 'para_value','conditions'=>array('Parameters.para_parent_id'=>$id,'para_id'=>3001),'order'=>array('para_id')));
		}
		else
		{
			return $this->find('list',array('keyField' => 'para_id',
    'valueField' => 'para_value','conditions'=>array('Parameters.para_parent_id'=>$id,'para_id != '=>3004),'order'=>array('para_id')));
		}*/
	}

	/**
	 *
	 * retriveparent
	 *
	 * Behaviour : Public
	 *
	 * @defination : Use for get Parent Parameter list if having master right to logged in user
	 *
	 */
	public function retriveparent(){
		return $this->find('list',array('fields'=>array('para_id','para_value'),'conditions'=>array('Parameters.para_parent_id'=>$this->para_parent_id)));
	}

	/**
	 *
	 * retriveparametervalue
	 *
	 * Behaviour : Public
	 *
	 * @defination : Use for get Parent Parameter list if having master right to logged in user
	 *
	 */
	public function retriveparametervalue($id){
		return $this->find('all',array('fields'=>array('para_id','para_value'),'conditions'=>array('Parameters.para_parent_id'=>$id)));
	}
	

	/* Get Customer Type list for  project */
	public function getProjectType() {
  		return $arrCategoryList = $this->find("list", ['keyField' => 'para_id','valueField' => 'para_value',"conditions"=>array("Parameters.para_parent_id"=> $this->projectType)])->toArray();    
	}

	public function getAreaType() {
  		return $arrAreaTypeList = $this->find("list", ['keyField' => 'para_id','valueField' => 'para_value',"conditions"=>array("Parameters.para_parent_id"=> $this->paraTypeArea)])->toArray();    
	}

	public function getCustomreUserRights() {
  		return $arrAreaTypeList = $this->find("list", ['keyField' => 'para_id','valueField' => 'para_value',"conditions"=>array("Parameters.para_parent_id"=> $this->paraCustomerRights)])->toArray();    
	}

    public function getProjectSource() {
        return $arrProjectSourceList = $this->find("list", ['keyField' => 'para_id','valueField' => 'para_value',"conditions"=>array("Parameters.para_parent_id"=> $this->paraTypeProjectSource)])->toArray();
    }

    public function GetParaIdByName($para_value) {
        return $arrProjectSourceList = $this->find("list", ['keyField' => 'para_id','valueField' => 'para_value',"conditions"=>array("LOWER(Parameters.para_value)"=> strtolower($para_value))])->toArray();
    }
}
?>