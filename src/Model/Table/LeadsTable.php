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
class LeadsTable extends AppTable
{
    var $data= array();
    var $customer_name  = '';
    var $customer_email = '';
	public function initialize(array $config)
    {
        $this->table('leads');
    }

    public function validationLead(Validator $validator)
    {
        $validator->notEmpty('categories', 'Category Must be select');
        $validator->notEmpty('project_name', 'Project Name is required');
        $validator->notEmpty('source_lead', 'Source of Lead Must be select');
        $validator->notEmpty('status', 'Status of lead Must be select');
        $validator->notEmpty('energy', 'Average Energy Consumption is required');
        $validator->notEmpty('area', 'Area is required');
        $validator->notEmpty('location', 'Location is required');
        $validator->notEmpty('avg_monthly_bill', 'Monthly Bill is required');
        $validator->notEmpty('avg_energy_consum', 'Average Energy Consumption is required');
        $validator->notEmpty('contract_load', 'Contract Load is required');


        if (isset($this->data['source_lead']) && $this->data['source_lead'] == 1) {
            $validator->notEmpty('lead_cold_calling', 'Please Enter Cold calling.');
        }
        if (isset($this->data['source_lead']) && $this->data['source_lead'] == 2) {
            $validator->notEmpty('lead_reference', 'Please Enter Reference.');
        }
        if (isset($this->data['source_lead']) && $this->data['source_lead'] == 7) {
            $validator->notEmpty('lead_other', 'Please Enter Other value.');
        }
        if (isset($this->data['status']) && $this->data['status'] == 'archived') {
            $validator->notEmpty('status_archived', 'Please Enter Reason.');
        }
        if (isset($this->data['userid']) && $this->data['userid'] =="") {
            $validator->notEmpty('userid', 'Please select User');
        }





        return $validator;
    }

}
?>