<?php
namespace App\Model\Table;

use App\Model\Table\Entity;
use App\Model\Entity\User;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

use Cake\Utility\Security;
use Cake\Core\Configure;
use Cake\Event\Event;

/**
 * Short description for file
 * This Model use for get customer projects detail. It extends Table Class
 * @category  Class File
 * @Desc      Manage customer project information
 * @author    Khushal Bhalsod
 * @version   Solar 1.0
 * @since     File available since Solar 1.0
 */

class CustomerProjectsTable extends AppTable
{

	public $validate		= array();
	public $validationSet	= "";
	
	var $table = 'customer_projects';

	public function initialize(array $config)
    {
        $this->table($this->table);
         $this->addAssociations([
          'belongsTo' => ['Projects','Customers']
        ]);
        $this->belongsTo('Projects', [
            'className' => 'Projects',
            'foreignKey' => 'project_id'
        ]);
        $this->belongsTo('Customers', [
            'className' => 'Customers',
            'foreignKey' => 'customer_id'
        ]);
    }

    /**
	 *
	 * The status of $validate_timezone is universe
	 *
	 * Potential value are validate time zone
	 *
	 * @var Array
	 *
	 */
	var $validate_timezone =  array(
		/*
			'timezone' => array(
					'rule' => array('maxLength',5),
					'required' => true,
					'allowEmpty' => false,
					'message' => 'Please select valid Time zone.'
			)
			*/
	);

    /**
	 *
	 *  identicalFieldValues
	 *
	 * Behaviour : Public
	 *
	 * @return : its return boolean
	 * @defination : befor saving data in User table Password field compared with Confirm password field.
	 *
	 */
    function identicalFieldValues( $field=array(), $compare_field=null )
    {
        foreach( $field as $key => $value ){
            $v1 = $value;
            $v2 = $this->data[$this->name][ $compare_field ];
            if($v1 !== $v2) {
                return FALSE;
            } else {
                continue;
            }
        }
        return TRUE;
    }
    /* Get all project leads for installer*/
    public function getProjectList($installerId,$type = '')
    {
        if(empty($installerId)){
            return false;

        }
        $condition['customer_id'] = $installerId;
        if(!empty($type))
        {
             $condition['Projects.customer_type'] = $type;
        }

        $query  = $this->find('all')->contain('Projects');
        $projectData =    $query->where($condition);
                            
        return $projectData;          
    }

    public function getProjectListByCondition($param = array())
    {
        if(empty($param['customer_id'])){
            return false;
        }
        if(!empty($param['customer_type'])) {
            $condition['projects.customer_type'] = $param['customer_type'];
        }
        if(!empty($param['project_source'])) {
            $condition['projects.project_source'] = $param['project_source'];
        }
        if(!empty($param['project_name'])) {
            $condition['projects.name LIKE'] = '%'.$param['project_name'].'%';
        }
        if(!empty($param['location'])) {
            $condition['or']['projects.state LIKE'] = '%'.$param['location'].'%';
            $condition['or']['projects.city LIKE'] = '%'.$param['location'].'%';
        }
		$condition[0] = 'projects.name is not null';
		$condition['projects.name != '] = '';
        if($param['installer_id'] != 0)
        {
            $condition[]        = array('InstallerProjects.installer_id IN ' =>$param['installer_id'],'InstallerProjects.status'=>'4002');
        }
        //$query  	 = $this->find('all')->contain('Projects');
        if(!empty($param['installer_id']) && $param['installer_id'] != 0) {
            $JoinTables = array('projects'   => array(
                                                    'table' => 'projects',
                                                    'type' => 'LEFT',
                                                    'conditions' => ['projects.id=InstallerProjects.project_id']),
                                'parameters' => array(
                                                    'table' => 'parameters',
                                                    'type' => 'LEFT',
                                                    'conditions' => ['parameters.para_id = projects.customer_type'])
                            );
            $customerdata       = $this->Customers->find('all', array('fields'=>['id','installer_id','user_role'],'conditions'=>array('id'=>$param['customer_id'])))->first();
            $user_roles = explode(",",$customerdata->user_role);
            $Parameters = TableRegistry::get('Parameters');
            if (in_array($Parameters->bd_role,$user_roles) && (!in_array($Parameters->admin_role,$user_roles))) {
                $JoinTables['ProjectAssigned'] = array( 'table'         => 'project_assign_bd',
                                                        'type'          => 'LEFT',
                                                        'conditions'    => ['ProjectAssigned.projects_id = projects.id']);
                $condition[] = array("ProjectAssigned.customers_id"=>$param['customer_id']);
            }
            $InstallerProjectsT = TableRegistry::get('InstallerProjects');
            $query              = $InstallerProjectsT->find('all',
                                array('fields'  => ['projects.id','projects.name','projects.address','projects.city','projects.state','projects.state_short_name','projects.country','projects.pincode','projects.landmark','projects.created','projects.solar_radiation','projects.area','projects.area_type','projects.customer_type','projects.capacity_kw','projects.recommended_capacity','projects.latitude','projects.longitude','parameters.para_value'],
                                'join'      => $JoinTables,
                                'conditions'=> $condition,
                                'order'     => array('projects.id'=>'DESC')));
        }
        else
        {
            if(!empty($param['customer_id'])) {
            $condition['customer_id'] = $param['customer_id'];
            }
            $JoinTables['projects'] = ['table'=>'projects','type'=>'inner','conditions'=>'projects.id=CustomerProjects.project_id'];
            $query       = $this->find('all')->select(['projects.id','projects.name','projects.verification_code',
                                                    'projects.address',
                                                    'projects.city',
                                                    'projects.state',
                                                    'projects.state_short_name',
                                                    'projects.country',
                                                    'projects.pincode',
                                                    'projects.landmark',
                                                    'projects.latitude',
                                                    'projects.longitude',
                                                    'projects.solar_radiation',
                                                    'projects.area',
                                                    'projects.area_type',
                                                    'projects.customer_type',
                                                    'projects.capacity_kw',
                                                    'projects.estimated_cost',
                                                    'projects.estimated_cost_subsidy',
                                                    'projects.estimated_kwh_year',
                                                    'projects.recommended_capacity',
                                                    'projects.maximum_capacity',
                                                    'projects.customized',
                                                    'projects.discom_id',
                                                    'projects.avg_monthly_bill',
                                                    'projects.contract_load',
                                                    'projects.backup_type',
                                                    'projects.diesel_genset_kva',
                                                    'projects.usage_hours',
                                                    'projects.avg_generate',
                                                    'projects.cost_solar',
                                                    'projects.created',
                                                    'projects.solar_ratio'])
                            ->join($JoinTables);
        }
		
        $projectData = $query->where($condition)->order(['projects.id' => 'DESC']);
		return $projectData;          
    }
}
?>