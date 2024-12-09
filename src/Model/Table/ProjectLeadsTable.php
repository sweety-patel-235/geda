<?php
namespace App\Model\Table;

use App\Model\Table\Entity;
use App\Model\Entity\User;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
//use App\Model\Table\Security;

use Cake\Utility\Security;
use Cake\Core\Configure;
use Cake\Event\Event;
//use Cake\Event\Event;

/**
 * Used for project lead information
 * @Desc      Project Leads
 * @author    CP Soni
 */
class ProjectLeadsTable extends AppTable
{
	
	public function initialize(array $config)
    {
        $this->table('project_leads');
        $this->addAssociations([
          'belongsTo' => ['Projects','Installers']
        ]);
         
    }


    /* Get all project  for installer*/
    public function getInstallerProjects($installerId, $params = array())
    {
        if(empty($installerId)){
            return false;
        }
        $query  = $this->find('all')->contain('Projects');
        $projectData =    $query
        //->leftJoin(['pt' => 'parameters'],['pt.para_id = Projects__customer_type'])
                                ->where(['installer_id' => $installerId,'ProjectLeads.status' =>'accepted'])
                            ;
        if(!empty($params['projectType'])){
            $projectData =    $query->where(['customer_type' => $params['projectType']]);
            
        }
       // prd($projectData->toArray());                    
        return $projectData;          
    }
 
    /* Get all project leads for installer*/
    public function getProjectLeads($installerId,$type = 'pending')
    {
        if(empty($installerId)){
            return false;
        }
        $query  = $this->find('all')->contain('Projects');
        $projectData =    $query
        					//->select(['Projects.name'])
                            //->innerJoin(['pl' => 'project_leads'],['Projects.id = pl.project_id'])
                            ->where(['installer_id' => $installerId,'ProjectLeads.status' => $type])
                            //->toArray()
                            ;
        return $projectData;          
    }

    public function validationLead(Validator $validator)
    {
        $validator->notEmpty('categories', 'Category Must be select.');
        $validator->notEmpty('energy', 'Average Energy Consumption is required');
        $validator->notEmpty('location', 'Location is required.');
        return $validator;
    }

}
?>